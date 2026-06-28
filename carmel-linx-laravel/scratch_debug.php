<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Support\Facades\DB;

$latestTest = DB::table('test_configs')->orderBy('created_at', 'desc')->first();
echo "Latest Test ID: " . $latestTest->test_id . "\n";
echo "Payload: " . substr($latestTest->questions_payload, 0, 500) . "\n";

$latestAttempt = DB::table('test_attempts')->where('test_id', $latestTest->test_id)->orderBy('created_at', 'desc')->first();
if ($latestAttempt) {
    echo "Attempt Score: " . $latestAttempt->total_score . "\n";
    echo "Responses: " . $latestAttempt->responses . "\n";
    
    $payload = json_decode($latestTest->questions_payload, true);
    $answers = json_decode($latestAttempt->responses, true);
    
    foreach ($payload as $index => $q) {
        $studentAns = isset($answers[$index]) ? trim((string)$answers[$index]) : null;
        $correctAns = $q['ans'] !== null ? trim((string)$q['ans']) : null;
        $isCorrect = ($correctAns !== null && strcasecmp($studentAns, $correctAns) === 0);
        echo "Q" . $index . ": " . $studentAns . " === " . $correctAns . " -> " . ($isCorrect ? 'TRUE' : 'FALSE') . "\n";
    }
} else {
    echo "No attempts found for this test.\n";
}
