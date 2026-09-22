<?php

namespace App\Http\Controllers;

use App\Services\SbteSubjectLogImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class SbteSubjectLogImportController extends Controller
{
    protected SbteSubjectLogImportService $importService;

    public function __construct(SbteSubjectLogImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Parse an SBTE Subject Log PDF or raw text.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function parse(Request $request): JsonResponse
    {
        $request->validate([
            'pdf_file' => 'nullable|file|max:10240',
            'raw_text' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                $filePath = $file->getRealPath();
                $result = $this->importService->parseFile($filePath);
            } elseif ($request->filled('raw_text')) {
                $result = $this->importService->parseText($request->input('raw_text'));
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a PDF file or raw text to parse.'
                ], 422);
            }

            if (isset($result['status']) && $result['status'] === 'ERROR') {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to parse SBTE subject log.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error parsing file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk import parsed sessions into class_logs_attendance.
     *
     * @param Request $request
     * @param int|string $subjectId
     * @return JsonResponse
     */
    public function import(Request $request, $subjectId): JsonResponse
    {
        $request->validate([
            'sessions' => 'required|array|min:1',
            'sessions.*.date' => 'required|date',
            'sessions.*.hours' => 'required|array',
            'sessions.*.contents' => 'nullable|string',
            'recorded_by' => 'nullable|string|max:255',
            'overwrite' => 'nullable|boolean',
        ]);

        $recordedBy = $request->input('recorded_by')
            ?: (Auth::check() ? (Auth::user()->name ?? 'Faculty') : 'SBTE Import');
        $overwrite = (bool)$request->input('overwrite', false);

        try {
            $result = $this->importService->importSessions(
                (int)$subjectId,
                $request->input('sessions'),
                $recordedBy,
                $overwrite
            );

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$result['imported']} session(s), updated {$result['updated']}, skipped {$result['skipped']}.",
                'result' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
