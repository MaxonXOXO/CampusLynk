<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Exception;

class SbteSubjectLogImportService
{
    /**
     * Parse an SBTE Subject Log PDF or raw text into normalized session records.
     *
     * @param string $filePath Absolute path to PDF or text file
     * @return array
     */
    public function parseFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return ['status' => 'ERROR', 'message' => "File not found: {$filePath}"];
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($extension === 'txt') {
            $text = file_get_contents($filePath);
            return $this->parseText($text);
        }

        // Try Python parser first if available
        $pythonResult = $this->tryPythonParser($filePath);
        if ($pythonResult && isset($pythonResult['status']) && $pythonResult['status'] === 'SUCCESS') {
            return $pythonResult;
        }

        // Pure PHP PDF text extraction fallback
        $text = $this->extractTextFromPdf($filePath);
        if (!empty(trim($text))) {
            return $this->parseText($text);
        }

        return [
            'status' => 'ERROR',
            'message' => 'The PDF does not contain extractable text or could not be decoded.'
        ];
    }

    /**
     * Parse raw text formatted as an SBTE Subject Log.
     *
     * @param string $fullText
     * @return array
     */
    public function parseText(string $fullText): array
    {
        if (empty(trim($fullText))) {
            return ['status' => 'ERROR', 'message' => 'Text content is empty.'];
        }

        // Extract Header metadata
        preg_match('/Programme:\s*(.*)/i', $fullText, $progMatch);
        preg_match('/Course:\s*(.*?)\s*\((\w+)\)\s*Semester:\s*(\w+)/i', $fullText, $courseMatch);
        if (!$courseMatch) {
            preg_match('/Course:\s*(.*?)\s*Semester:\s*(\w+)/i', $fullText, $courseMatch);
        }
        preg_match('/Faculty:\s*(.*)/i', $fullText, $facultyMatch);

        $courseTitle = isset($courseMatch[1]) ? trim($courseMatch[1]) : '';
        $courseCode = isset($courseMatch[2]) && count($courseMatch) >= 4 ? trim($courseMatch[2]) : '';
        $semester = isset($courseMatch[3]) ? trim($courseMatch[3]) : (isset($courseMatch[2]) ? trim($courseMatch[2]) : '');
        $facultyName = isset($facultyMatch[1]) ? trim($facultyMatch[1]) : '';
        $programme = isset($progMatch[1]) ? trim($progMatch[1]) : '';

        // Match table rows: "1. 15-01-2026 1, 2 Introduction to Subject ... "
        preg_match_all('/(?ms)^(\d+)\.\s+(\d{2}-\d{2}-\d{4})\s+([\d,\s]+?)\s*(.*?)(?=\n\d+\.|\Z)/', $fullText, $rawMatches, PREG_SET_ORDER);

        $sessions = [];
        foreach ($rawMatches as $m) {
            $sl = (int)$m[1];
            $rawDt = trim($m[2]);
            $dParts = explode('-', $rawDt);
            $isoDate = count($dParts) === 3 ? "{$dParts[2]}-{$dParts[1]}-{$dParts[0]}" : $rawDt;

            $rawHours = trim($m[3]);
            preg_match_all('/\d+/', $rawHours, $hourDigits);
            $hours = array_map('intval', $hourDigits[0] ?? []);

            $rawContent = trim($m[4]);
            if (!empty($facultyName)) {
                $rawContent = preg_replace('/\s*' . preg_quote($facultyName, '/') . '.*$/i', '', $rawContent);
            }
            $rawContent = preg_replace('/\s+\d+\s*$/', '', $rawContent);
            $content = trim(preg_replace('/\s+/', ' ', $rawContent));

            if (str_starts_with($content, ',')) {
                preg_match_all('/\d+/', substr($content, 0, 6), $extraDigits);
                foreach ($extraDigits[0] as $eh) {
                    $ehInt = (int)$eh;
                    if (!in_array($ehInt, $hours, true)) {
                        $hours[] = $ehInt;
                    }
                }
                $content = trim(preg_replace('/^[\d,\s]+/', '', $content));
            }

            if (empty($hours)) {
                $hours = [1];
            }

            sort($hours);

            $sessions[] = [
                'sl_no' => $sl,
                'date' => $isoDate,
                'display_date' => $rawDt,
                'hours' => $hours,
                'hours_count' => count($hours),
                'contents' => $content,
                'faculty' => $facultyName
            ];
        }

        return [
            'status' => 'SUCCESS',
            'programme' => $programme,
            'course_title' => $courseTitle,
            'course_code' => $courseCode,
            'semester' => $semester,
            'faculty' => $facultyName,
            'total_sessions' => count($sessions),
            'total_hours' => array_sum(array_column($sessions, 'hours_count')),
            'sessions' => $sessions
        ];
    }

    /**
     * Imports parsed sessions into class_logs_attendance for a given batch subject.
     *
     * @param int $batchSubjectId
     * @param array $sessions
     * @param string $recordedBy
     * @param bool $overwriteExisting
     * @return array
     */
    public function importSessions(int $batchSubjectId, array $sessions, string $recordedBy, bool $overwriteExisting = false): array
    {
        // Verify batch_subject exists
        $subject = DB::table('batch_subjects')->where('id', $batchSubjectId)->first();
        if (!$subject) {
            throw new Exception("Batch subject with ID {$batchSubjectId} not found.");
        }

        $importedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($sessions as $session) {
                $date = $session['date'] ?? null;
                $topics = $session['contents'] ?? '';
                $hours = $session['hours'] ?? [1];

                if (!$date) {
                    continue;
                }

                foreach ($hours as $period) {
                    $existing = DB::table('class_logs_attendance')
                        ->where('batch_subject_id', $batchSubjectId)
                        ->where('date', $date)
                        ->where('period', $period)
                        ->first();

                    if ($existing) {
                        if ($overwriteExisting) {
                            DB::table('class_logs_attendance')
                                ->where('id', $existing->id)
                                ->update([
                                    'topics_covered' => $topics,
                                    'recorded_by' => $recordedBy,
                                    'updated_at' => now(),
                                ]);
                            $updatedCount++;
                        } else {
                            $skippedCount++;
                        }
                    } else {
                        DB::table('class_logs_attendance')->insert([
                            'batch_subject_id' => $batchSubjectId,
                            'date' => $date,
                            'period' => $period,
                            'topics_covered' => $topics,
                            'recorded_by' => $recordedBy,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $importedCount++;
                    }
                }
            }

            DB::commit();

            return [
                'success' => true,
                'batch_subject_id' => $batchSubjectId,
                'imported' => $importedCount,
                'updated' => $updatedCount,
                'skipped' => $skippedCount,
                'total_processed' => $importedCount + $updatedCount + $skippedCount,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("SBTE Subject Log Import Failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Pure PHP extraction of text streams from a PDF file.
     *
     * @param string $pdfPath
     * @return string
     */
    private function extractTextFromPdf(string $pdfPath): string
    {
        $content = file_get_contents($pdfPath);
        if (!$content) {
            return '';
        }

        $text = '';

        // Extract streams
        preg_match_all('/stream[\r\n]+(.*?)[\r\n]+endstream/s', $content, $streamMatches);

        foreach ($streamMatches[1] as $stream) {
            $decompressed = @gzuncompress($stream);
            if ($decompressed === false) {
                $decompressed = @gzinflate($stream);
            }
            if ($decompressed === false) {
                $decompressed = $stream;
            }

            // Extract text from text blocks: BT ... ET
            if (preg_match_all('/BT[\r\n]+(.*?)[\r\n]+ET/s', $decompressed, $btMatches)) {
                foreach ($btMatches[1] as $bt) {
                    // Match (string) Tj or ' or "
                    if (preg_match_all('/\((.*?)\)\s*(?:Tj|\'|")/s', $bt, $tjMatches)) {
                        foreach ($tjMatches[1] as $t) {
                            $text .= $this->unescapePdfString($t) . ' ';
                        }
                        $text .= "\n";
                    }

                    // Match array of strings: [(...) ... (...)] TJ
                    if (preg_match_all('/\[(.*?)\]\s*TJ/s', $bt, $arrMatches)) {
                        foreach ($arrMatches[1] as $arr) {
                            if (preg_match_all('/\((.*?)\)/s', $arr, $subMatches)) {
                                foreach ($subMatches[1] as $t) {
                                    $text .= $this->unescapePdfString($t);
                                }
                            }
                        }
                        $text .= "\n";
                    }
                }
            }
        }

        return $text;
    }

    /**
     * Unescape standard PDF string escape sequences.
     */
    private function unescapePdfString(string $str): string
    {
        $str = str_replace(['\\(', '\\)', '\\\\'], ['(', ')', '\\'], $str);
        $str = str_replace(['\\r', '\\n', '\\t'], ["\r", "\n", "\t"], $str);
        return $str;
    }

    /**
     * Attempt to execute python script if available.
     */
    private function tryPythonParser(string $pdfPath): ?array
    {
        $scriptPath = app_path('Services/parse_sbte_subject_log.py');
        if (!file_exists($scriptPath)) {
            return null;
        }

        try {
            $cmd = "python " . escapeshellarg($scriptPath) . " " . escapeshellarg($pdfPath);
            $output = shell_exec($cmd);
            if ($output) {
                $decoded = json_decode($output, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }
        } catch (Exception $e) {
            // Ignore python execution error, fallback to PHP
        }

        return null;
    }
}
