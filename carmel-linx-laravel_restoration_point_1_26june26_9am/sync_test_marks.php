<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$attempts = \Illuminate\Support\Facades\DB::table('test_attempts')
    ->where('status', 'completed')
    ->get();

foreach ($attempts as $attempt) {
    $test = \Illuminate\Support\Facades\DB::table('test_configs')
        ->where('test_id', $attempt->test_id)
        ->first();
        
    if (!$test) continue;

    $payload = json_decode($test->questions_payload, true);
    if (!$payload) continue;

    $answers = json_decode($attempt->responses, true) ?? [];
    
    $coScores = [];
    foreach ($payload as $index => $q) {
        $co = $q['co'] ?? 'CO1';
        if (!isset($coScores[$co])) {
            $coScores[$co] = ['max' => 0, 'obtained' => 0];
        }
        $coScores[$co]['max'] += 1;

        $studentAns = isset($answers[$index]) ? trim((string)$answers[$index]) : null;
        $correctAns = $q['ans'] !== null ? trim((string)$q['ans']) : null;
        $isCorrect = ($correctAns !== null && strcasecmp($studentAns, $correctAns) === 0);
        if ($isCorrect) {
            $coScores[$co]['obtained'] += 1;
        }
    }

    foreach ($coScores as $co => $data) {
        \Illuminate\Support\Facades\DB::table('academic_marks')->updateOrInsert([
            'reg_no' => $attempt->reg_no,
            'subject_code' => $test->subject_code,
            'category' => 'Online Test',
            'co_tag' => $co
        ], [
            'max_marks' => $data['max'],
            'marks_obtained' => $data['obtained'],
            'entered_by' => null,
            'updated_at' => now()
        ]);
    }
}
echo "Synced " . count($attempts) . " attempts.";
