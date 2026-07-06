<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$regNo = '25EL1001'; // Target test student
$subjectCode = 'EL-3041'; // Electric Circuits
$today = now()->toDateString();

echo "=== Mock Practice Test Limits Diagnostics ===" . PHP_EOL;

// Clean up existing attempts for today first to run a clean test
DB::table('student_mock_test_attempts')
    ->where('reg_no', $regNo)
    ->where('subject_code', $subjectCode)
    ->where('attempted_date', $today)
    ->delete();

echo "1. Attempt status initially (should be false/does not exist): ";
$exists = DB::table('student_mock_test_attempts')
    ->where('reg_no', $regNo)
    ->where('subject_code', $subjectCode)
    ->where('attempted_date', $today)
    ->exists();
echo ($exists ? "YES" : "NO") . PHP_EOL;

// Insert attempt
DB::table('student_mock_test_attempts')->insert([
    'reg_no' => $regNo,
    'subject_code' => $subjectCode,
    'attempted_date' => $today,
    'created_at' => now(),
    'updated_at' => now()
]);
echo "  Logged mock attempt for student {$regNo} on {$subjectCode} for today." . PHP_EOL;

echo "2. Attempt status after log (should be true/exists): ";
$exists2 = DB::table('student_mock_test_attempts')
    ->where('reg_no', $regNo)
    ->where('subject_code', $subjectCode)
    ->where('attempted_date', $today)
    ->exists();
echo ($exists2 ? "YES" : "NO") . PHP_EOL;

// Clean up to return database to clean state
DB::table('student_mock_test_attempts')
    ->where('reg_no', $regNo)
    ->where('subject_code', $subjectCode)
    ->where('attempted_date', $today)
    ->delete();
echo "  Cleaned up test logs." . PHP_EOL;

echo "Done!" . PHP_EOL;
