<?php
/**
 * Directly parse and upload the Embedded Systems syllabus PDF
 * bypassing the browser file picker — simulates the exact uploadSyllabus() flow.
 * Usage: php upload_syllabus_local.php
 */
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// ---------- CONFIG ----------
$subjectId = 10;   // Embedded Systems EL-5041
$pdfPath   = 'C:/Users/fotonlabz/Downloads/5041(1).pdf';
// ----------------------------

if (!file_exists($pdfPath)) {
    // Try other names
    $candidates = glob('C:/Users/fotonlabz/Downloads/*5041*') ?: [];
    $candidates = array_merge($candidates, glob('C:/Users/fotonlabz/Downloads/*EL-5041*') ?: []);
    $candidates = array_merge($candidates, glob('C:/Users/fotonlabz/Downloads/*embedded*') ?: []);
    if (empty($candidates)) {
        die("❌ PDF not found at $pdfPath and no alternatives found in Downloads.\nPlease check the filename and try again.\n");
    }
    $pdfPath = $candidates[0];
    echo "📄 Found PDF: $pdfPath\n";
}

echo "📄 PDF: $pdfPath (" . number_format(filesize($pdfPath) / 1024, 1) . " KB)\n";

// ── Extract text from PDF ─────────────────────────────────────────────────────
echo "\n🔍 Extracting text from PDF...\n";
$text = '';
try {
    $parser = new \Smalot\PdfParser\Parser();
    $pdf    = $parser->parseFile($pdfPath);
    $text   = $pdf->getText();
    echo "✅ Extracted " . strlen($text) . " characters of text\n";
    echo "   First 300 chars:\n";
    echo "   " . str_replace("\n", "\n   ", substr($text, 0, 300)) . "\n\n";
} catch (\Exception $e) {
    die("❌ PDF parsing failed: " . $e->getMessage() . "\n");
}

if (strlen($text) < 100) {
    die("❌ PDF appears to be image-based (scanned). Extracted text is too short. Cannot auto-parse.\n");
}

// ── Call Gemini API ───────────────────────────────────────────────────────────
$apiKey = env('GEMINI_API_KEY');
if (!$apiKey) {
    die("❌ GEMINI_API_KEY not set in .env\n");
}

echo "🤖 Calling Gemini AI to parse syllabus...\n";

$prompt = "You are an expert academic syllabus parser. Carefully extract structured information from the raw syllabus text provided.

Return ONLY valid JSON with NO markdown, NO code fences, NO explanation — just the raw JSON object.

The JSON must match this schema exactly:
{
  \"cos\": [
    { \"id\": \"CO1\", \"description\": \"...\", \"duration\": 13, \"cognitive_level\": \"Understanding\" }
  ],
  \"copo\": {
    \"CO1\": { \"PO1\": 3, \"PO2\": 2, \"PO3\": 1, \"PO4\": null, \"PO5\": null, \"PO6\": null, \"PO7\": null, \"PO8\": null, \"PO9\": null, \"PO10\": null, \"PO11\": null, \"PO12\": null }
  },
  \"modules\": [
    { \"module_id\": \"I\", \"content\": \"Exact text of the module contents section\" }
  ],
  \"textbooks\": [\"Author Name, Title, Publisher, Year\"],
  \"lesson_plan\": [
    { \"co_id\": \"CO1\", \"topic_content\": \"Exact topic text as written in syllabus\", \"allocated_hours\": 2, \"pedagogy\": \"Lecture\" }
  ]
}

CRITICAL RULES FOR lesson_plan:
- Generate ONE row per MODULE OUTCOME row (e.g. M1.01, M1.02, M1.03, etc. for each CO).
- Use the EXACT topic text from each row of the Course Outline table (e.g. \"Describe embedded system, illustrate difference from general purpose computer\").
- Use the EXACT duration (hours) from each row of the Course Outline table (e.g. 2).
- DO NOT summarize or combine multiple rows into one — keep each row separate.
- If the syllabus does not have a Course Outline table, generate one lesson_plan entry per topic sentence in each module's contents, using 1 or 2 hours per topic.
- The co_id must match the CO number that each module outcome belongs to.
- pedagogy should be Lecture for theory topics, Lab for practical topics, Demo for demonstrations.

CRITICAL RULES FOR modules:
- Copy the EXACT text from the Contents section of each module.
- Do NOT use generic placeholder text.

Syllabus text:

" . substr($text, 0, 18000);

$response = Illuminate\Support\Facades\Http::timeout(60)->post(
    "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
    [
        'contents'       => [['parts' => [['text' => $prompt]]]],
        'generationConfig' => ['responseMimeType' => 'application/json'],
    ]
);

if (!$response->successful()) {
    die("❌ Gemini API error: " . $response->body() . "\n");
}

$jsonString = $response->json('candidates.0.content.parts.0.text');
$cleanJson  = trim(str_replace(['```json', '```JSON', '```'], '', $jsonString));
$parsed     = json_decode($cleanJson, true);

if (!$parsed) {
    die("❌ Could not parse Gemini response as JSON.\nRaw:\n" . substr($cleanJson, 0, 500) . "\n");
}

echo "\n✅ Gemini parsed successfully!\n";
echo "   COs:          " . count($parsed['cos'] ?? []) . "\n";
echo "   Modules:      " . count($parsed['modules'] ?? []) . "\n";
echo "   Lesson plans: " . count($parsed['lesson_plan'] ?? []) . "\n\n";

// Show CO summary
echo "─── Course Outcomes ───────────────────────────────────────────────────────\n";
foreach (($parsed['cos'] ?? []) as $co) {
    echo "  {$co['id']} ({$co['duration']} hrs): {$co['description']}\n";
}
echo "\n";

// Show lesson plan preview
echo "─── Lesson Plan (first 20 rows from Gemini) ───────────────────────────────\n";
$lpCount = 0;
foreach (($parsed['lesson_plan'] ?? []) as $lp) {
    $lpCount++;
    echo "  Row {$lpCount} | {$lp['co_id']} | {$lp['allocated_hours']}h | {$lp['topic_content']}\n";
    if ($lpCount >= 20) { echo "  ... (showing first 20)\n"; break; }
}
echo "\n";

// ── Save to DB ────────────────────────────────────────────────────────────────
echo "💾 Saving parsed data to database...\n";

$bs = DB::table('batch_subjects')->where('id', $subjectId)->first();

// Copy storage syllabus path
$storagePath = 'syllabi/' . $subjectId . '_' . time() . '.pdf';
\Illuminate\Support\Facades\Storage::disk('public')->put($storagePath, file_get_contents($pdfPath));

$extractedCos       = $parsed['cos'] ?? [];
$extractedCoPo      = $parsed['copo'] ?? [];
$extractedModules   = $parsed['modules'] ?? [];
$extractedTextbooks = $parsed['textbooks'] ?? [];
$lessonPlans        = $parsed['lesson_plan'] ?? [];

// Fallbacks
if (empty($extractedCos)) {
    $extractedCos = [
        ['id'=>'CO1','description'=>'Understand embedded system basics','duration'=>13,'cognitive_level'=>'Understanding'],
        ['id'=>'CO2','description'=>'Analyze embedded architectures','duration'=>13,'cognitive_level'=>'Analyzing'],
        ['id'=>'CO3','description'=>'Design embedded solutions','duration'=>13,'cognitive_level'=>'Applying'],
        ['id'=>'CO4','description'=>'Evaluate embedded implementations','duration'=>13,'cognitive_level'=>'Evaluating'],
    ];
}
foreach ($extractedCos as &$co) {
    if (empty($co['duration'])) $co['duration'] = 15;
    if (empty($co['cognitive_level'])) $co['cognitive_level'] = 'Applying';
}
unset($co);

// Save course_files
DB::table('course_files')->updateOrInsert(
    ['batch_subject_id' => $subjectId],
    [
        'syllabus_pdf_path' => '/storage/' . $storagePath,
        'parsed_cos'        => json_encode($extractedCos),
        'parsed_copo'       => json_encode($extractedCoPo),
        'parsed_modules'    => json_encode($extractedModules),
        'parsed_textbooks'  => json_encode($extractedTextbooks),
        'updated_at'        => now(),
        'created_at'        => now(),
    ]
);
echo "   ✅ course_files saved\n";

// ── Expand lesson plans ───────────────────────────────────────────────────────
function splitTopicIntoAtomicDays(string $topic, int $hours): array {
    if ($hours <= 1) return [$topic];
    $commaParts = array_values(array_filter(array_map('trim', explode(',', $topic)), fn($s) => strlen($s) > 5));
    $merged = []; $buffer = '';
    foreach ($commaParts as $part) {
        if (strlen($buffer) > 0 && strlen($part) < 10 && !preg_match('/^[A-Z]/', $part)) {
            $buffer .= ', ' . $part;
        } else {
            if ($buffer !== '') $merged[] = ucfirst($buffer);
            $buffer = $part;
        }
    }
    if ($buffer !== '') $merged[] = ucfirst($buffer);
    if (count($merged) >= $hours) return array_slice($merged, 0, $hours);

    $sentenceParts = array_values(array_filter(preg_split('/[.;]\s+/', $topic, -1, PREG_SPLIT_NO_EMPTY), fn($s) => strlen(trim($s)) > 5));
    $sentenceParts = array_map('trim', $sentenceParts);
    if (count($sentenceParts) >= $hours) return array_slice(array_map('ucfirst', $sentenceParts), 0, $hours);

    $base = !empty($merged) ? $merged : [$topic];
    $pads = ['Revision & Problem Solving','Practice Problems & Exercises','Doubt Clearing & Discussion','Worked Examples','Tutorial Session'];
    $result = $base;
    for ($i = 0; $i < ($hours - count($result)); $i++) {
        $result[] = end($base) . ' – ' . $pads[$i % count($pads)];
    }
    return array_slice($result, 0, $hours);
}

$expanded = [];
$dayNo = 1;
foreach ($lessonPlans as $lp) {
    $hours  = max(1, (int)($lp['allocated_hours'] ?? 1));
    $topic  = trim($lp['topic_content'] ?? 'Lecture');
    $coId   = $lp['co_id'] ?? null;
    $peda   = $lp['pedagogy'] ?? 'Lecture';
    if ($hours === 1) {
        $expanded[] = ['day_no'=>$dayNo++,'co_id'=>$coId,'topic_content'=>$topic,'allocated_hours'=>1,'pedagogy'=>$peda,'remarks'=>null];
    } else {
        foreach (splitTopicIntoAtomicDays($topic, $hours) as $atomicTopic) {
            $expanded[] = ['day_no'=>$dayNo++,'co_id'=>$coId,'topic_content'=>$atomicTopic,'allocated_hours'=>1,'pedagogy'=>$peda,'remarks'=>null];
        }
    }
}
// Add test days
$expanded[] = ['day_no'=>$dayNo++,'co_id'=>null,'topic_content'=>'Series Test - I / Internal Assessment','allocated_hours'=>1,'pedagogy'=>'Test','remarks'=>'Series Test - I'];
$expanded[] = ['day_no'=>$dayNo++,'co_id'=>null,'topic_content'=>'Series Test - II / Internal Assessment','allocated_hours'=>1,'pedagogy'=>'Test','remarks'=>'Series Test - II'];
$expanded[] = ['day_no'=>$dayNo++,'co_id'=>null,'topic_content'=>'Series Test - III / Internal Assessment','allocated_hours'=>1,'pedagogy'=>'Test','remarks'=>'Series Test - III'];
$expanded[] = ['day_no'=>$dayNo,  'co_id'=>null,'topic_content'=>'Series Test - IV / Internal Assessment','allocated_hours'=>1,'pedagogy'=>'Test','remarks'=>'Series Test - IV'];

DB::table('lesson_plans')->where('batch_subject_id', $subjectId)->delete();
$now = now();
foreach ($expanded as $lp) {
    DB::table('lesson_plans')->insert([
        'batch_subject_id' => (int)$subjectId,
        'day_no'           => $lp['day_no'],
        'co_id'            => $lp['co_id'],
        'topic_content'    => $lp['topic_content'],
        'allocated_hours'  => 1,
        'pedagogy'         => $lp['pedagogy'],
        'remarks'          => $lp['remarks'],
        'proposed_date'    => null,
        'actual_date'      => null,
        'actual_hours'     => null,
        'status'           => 'Pending',
        'created_at'       => $now,
        'updated_at'       => $now,
    ]);
}

$count = DB::table('lesson_plans')->where('batch_subject_id', $subjectId)->count();
echo "   ✅ lesson_plans saved: $count rows\n\n";

echo "─── First 25 Lesson Plan Rows (Final) ────────────────────────────────────\n";
$rows = DB::table('lesson_plans')->where('batch_subject_id', $subjectId)->orderBy('day_no')->limit(25)->get();
foreach ($rows as $r) {
    echo "  Day " . str_pad($r->day_no, 3) . " | " . str_pad($r->co_id ?? 'TEST', 4) . " | " . $r->topic_content . "\n";
}

echo "\n✅ DONE! Now open the virtual classroom → Lesson Plan tab to verify.\n";
echo "   Or hit the 'Regenerate' button if you want to regenerate from stored data.\n";
