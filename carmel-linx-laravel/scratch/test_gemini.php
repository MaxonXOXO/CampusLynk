<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('GEMINI_API_KEY');
echo "API Key loaded: " . ($apiKey ? substr($apiKey, 0, 20) . '...' : 'NOT FOUND') . PHP_EOL;

$prompt = "Generate exactly 3 descriptive questions for Course Outcome 'CO1' based on the topic: 'Develop basic single stage and multistage amplifiers'. Return ONLY a valid JSON array of strings: [\"Question 1?\", \"Question 2?\", \"Question 3?\"]";

echo "Calling Gemini API..." . PHP_EOL;

try {
    $response = Http::timeout(30)->post(
        "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
        [
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => ['responseMimeType' => 'application/json']
        ]
    );

    echo "HTTP Status: " . $response->status() . PHP_EOL;

    if ($response->successful()) {
        $jsonString = $response->json('candidates.0.content.parts.0.text');
        echo "Raw response: " . $jsonString . PHP_EOL;
        $parsed = json_decode(trim($jsonString), true);
        if (is_array($parsed)) {
            echo PHP_EOL . "SUCCESS! Generated " . count($parsed) . " questions:" . PHP_EOL;
            foreach ($parsed as $i => $q) {
                echo "  " . ($i+1) . ". " . $q . PHP_EOL;
            }
        } else {
            echo "ERROR: Could not parse JSON from response." . PHP_EOL;
        }
    } else {
        echo "API Error Body: " . $response->body() . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . PHP_EOL;
}
