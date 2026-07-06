<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AcademicCalendarController extends Controller
{
    /**
     * Show the academic calendar dashboard for HOD.
     */
    public function index()
    {
        $branch = Session::get('userBranch');
        $role   = Session::get('userRole');
        if (!$branch || !in_array($role, ['HOD', 'Principal', 'Super_Admin', 'Admin'])) {
            return redirect('/login');
        }
        $calendars = DB::table('academic_calendars')
            ->where('branch', $branch)
            ->orderBy('semester')
            ->get()
            ->keyBy('semester');

        return view('hod_academic_calendar', compact('calendars', 'branch'));
    }

    /**
     * Upload / update a semester calendar PDF and metadata.
     */
    public function store(Request $request)
    {
        $branch   = Session::get('userBranch');
        $semester = (int) $request->input('semester');
        $year     = trim($request->input('academic_year', date('Y') . '-' . (date('Y') + 1)));

        if (!$branch || $semester < 1 || $semester > 6) {
            return response()->json(['status' => 'ERROR', 'message' => 'Invalid input.']);
        }

        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $file     = $request->file('pdf');
            $filename = 'cal_' . $branch . '_sem' . $semester . '_' . time() . '.pdf';
            $pdfPath  = $file->storeAs('academic_calendars', $filename, 'public');
        }

        $existing = DB::table('academic_calendars')
            ->where('branch', $branch)
            ->where('semester', $semester)
            ->first();

        $activities = $request->input('activities', '[]');

        if ($existing) {
            $update = ['academic_year' => $year, 'activities' => $activities, 'updated_at' => now()];
            if ($pdfPath) $update['pdf_path'] = $pdfPath;
            DB::table('academic_calendars')->where('id', $existing->id)->update($update);
            $id = $existing->id;
        } else {
            $id = DB::table('academic_calendars')->insertGetId([
                'branch'        => $branch,
                'semester'      => $semester,
                'academic_year' => $year,
                'pdf_path'      => $pdfPath,
                'activities'    => $activities,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        return response()->json(['status' => 'SUCCESS', 'id' => $id, 'message' => "Semester $semester calendar saved."]);
    }

    /**
     * A4 print view for one semester calendar.
     */
    public function printCalendar($id)
    {
        $branch = Session::get('userBranch');
        $cal    = DB::table('academic_calendars')->where('id', $id)->where('branch', $branch)->first();
        if (!$cal) abort(404);
        $activities = json_decode($cal->activities ?? '[]', true);
        return view('hod_academic_calendar_print', compact('cal', 'activities', 'branch'));
    }

    /**
     * Parse the uploaded SITTTR PDF using smalot/pdfparser → Gemini 1.5 Flash AI
     * and return structured calendar entries as JSON for the frontend to prefill.
     */
    public function parsePdf(Request $request)
    {
        $branch   = Session::get('userBranch');
        $semester = (int) $request->input('semester');

        if (!$branch || $semester < 1 || $semester > 6) {
            return response()->json(['status' => 'ERROR', 'message' => 'Invalid request.']);
        }

        $cal = DB::table('academic_calendars')
            ->where('branch', $branch)
            ->where('semester', $semester)
            ->first();

        if (!$cal || !$cal->pdf_path) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => 'No PDF uploaded for this semester. Upload the SITTTR PDF first, save, then click Auto-Fetch.',
            ]);
        }

        $pdfFullPath = storage_path('app/public/' . $cal->pdf_path);
        if (!file_exists($pdfFullPath)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Uploaded PDF not found on server.']);
        }

        // ── Step 1: Extract raw text via smalot/pdfparser ────────────────────
        try {
            $parser  = new \Smalot\PdfParser\Parser();
            $pdf     = $parser->parseFile($pdfFullPath);
            $rawText = $pdf->getText();
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'PDF read error: ' . $e->getMessage()]);
        }

        if (strlen(trim($rawText)) < 30) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => 'This PDF appears to be image-scanned and contains no readable text. Please enter calendar entries manually.',
            ]);
        }

        // Truncate to stay within Gemini input token limits
        $rawText = mb_substr($rawText, 0, 12000);

        // ── Step 2: Ask Gemini 1.5 Flash to extract structured calendar data ──
        $apiKey = config('services.gemini.key');

        $prompt = <<<PROMPT
You are an expert assistant reading a SITTTR (State Institute of Technical Teachers Training and Research, Kerala) Academic Calendar PDF for Semester {$semester}.

Extract ALL calendar events listed in the document including class commencement dates, holidays, internal assessments, board exams, special events, and any other notable dates.

Return ONLY a valid JSON array (no markdown fences, no explanation text, just the raw JSON array):
[
  {"month":"August","date":"5","activity":"Classes commence","type":"Academic"},
  {"month":"August","date":"15","activity":"Independence Day","type":"Holiday"},
  {"month":"October","date":"2","activity":"Gandhi Jayanti","type":"Holiday"}
]

Rules:
- "month": full English month name only (January, February, ..., December)
- "date": day number as a string ("1" through "31")
- "activity": concise English description, maximum 80 characters
- "type": MUST be exactly one of these values: Academic, Exam, Holiday, Event, Department, Other
  - Academic  = class commencement, end of term, re-opening, working day notices
  - Exam      = internal assessment, model exam, practical exam, SBTE/board exam
  - Holiday   = public holiday, vacation period, festival holiday
  - Event     = cultural fest, sports meet, seminar, industrial visit, workshop
  - Department= department-specific programme or activity
  - Other     = any other notable entry
- For date ranges (e.g. "10–14 August"), create ONE entry using only the START date
- Skip page headers, footers, and repetitive administrative text
- If you cannot confidently identify the month or date for an entry, skip it

PDF TEXT BELOW:
{$rawText}
PROMPT;

        try {
            $response = Http::timeout(55)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents'         => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => ['temperature' => 0.1, 'maxOutputTokens' => 4096],
                ]);

            if (!$response->successful()) {
                return response()->json([
                    'status'  => 'ERROR',
                    'message' => 'Gemini API responded with error ' . $response->status() . '. Check your API key.',
                ]);
            }

            $geminiText = $response->json('candidates.0.content.parts.0.text', '');

        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'AI request failed: ' . $e->getMessage()]);
        }

        // ── Step 3: Parse & sanitise the JSON from Gemini's response ─────────
        $cleanText = trim(preg_replace('/```json|```/i', '', $geminiText));
        $start     = strpos($cleanText, '[');
        $end       = strrpos($cleanText, ']');

        if ($start === false || $end === false) {
            return response()->json([
                'status'  => 'ERROR',
                'message' => 'AI returned an unexpected format. Try clicking Auto-Fetch again.',
            ]);
        }

        $parsed = json_decode(substr($cleanText, $start, $end - $start + 1), true);

        if (!is_array($parsed)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Could not parse AI response. Please try again.']);
        }

        $validTypes = ['Academic', 'Exam', 'Holiday', 'Event', 'Department', 'Other'];
        $allMonths  = ['January','February','March','April','May','June',
                       'July','August','September','October','November','December'];
        $sanitised  = [];

        foreach ($parsed as $entry) {
            $month    = ucfirst(strtolower(trim($entry['month'] ?? '')));
            $date     = (string)(int)($entry['date'] ?? 0);
            $activity = trim(mb_substr($entry['activity'] ?? '', 0, 120));
            $type     = in_array($entry['type'] ?? '', $validTypes) ? $entry['type'] : 'Academic';

            if (in_array($month, $allMonths) && (int)$date >= 1 && (int)$date <= 31 && $activity !== '') {
                $sanitised[] = compact('month', 'date', 'activity', 'type');
            }
        }

        // Sort by month order then date within month
        usort($sanitised, function ($a, $b) use ($allMonths) {
            $ma = array_search($a['month'], $allMonths);
            $mb = array_search($b['month'], $allMonths);
            if ($ma !== $mb) return $ma - $mb;
            return (int)$a['date'] - (int)$b['date'];
        });

        return response()->json([
            'status'  => 'SUCCESS',
            'count'   => count($sanitised),
            'entries' => $sanitised,
        ]);
    }
}
