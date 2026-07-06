<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Find Electric Circuits subject
$subjects = DB::table('batch_subjects')
    ->where('subject_name', 'like', '%electric%')
    ->orWhere('subject_name', 'like', '%circuit%')
    ->get(['id','subject_name','subject_code','classroom_id']);

echo "=== MATCHING SUBJECTS ===" . PHP_EOL;
foreach ($subjects as $s) {
    echo "  id={$s->id}  name={$s->subject_name}  code={$s->subject_code}  class={$s->classroom_id}" . PHP_EOL;

    $cf = DB::table('course_files')->where('batch_subject_id', $s->id)->first();
    if ($cf) {
        $hasCos  = !empty($cf->parsed_cos) && $cf->parsed_cos !== 'null';
        $hasQs   = !empty($cf->assignment_questions) && $cf->assignment_questions !== 'null' && $cf->assignment_questions !== '[]';
        $coCount = $hasCos ? count(json_decode($cf->parsed_cos, true) ?? []) : 0;
        echo "    CourseFile id={$cf->id}  has_parsed_cos=" . ($hasCos ? "YES ($coCount COs)" : "NO") . "  has_questions=" . ($hasQs ? 'YES' : 'NO') . PHP_EOL;
        if ($hasCos) {
            $cos = json_decode($cf->parsed_cos, true);
            foreach ($cos as $co) {
                $desc = substr($co['description'] ?? 'N/A', 0, 80);
                echo "      {$co['id']}: {$desc}" . PHP_EOL;
            }
        }
    } else {
        echo "    *** NO COURSE FILE FOUND ***" . PHP_EOL;
    }
}

if ($subjects->isEmpty()) {
    echo "No subjects found with 'electric' or 'circuit' in name." . PHP_EOL;
    echo PHP_EOL . "=== ALL BATCH SUBJECTS ===" . PHP_EOL;
    $all = DB::table('batch_subjects')->get(['id','subject_name','subject_code']);
    foreach ($all as $s) {
        echo "  id={$s->id}  name={$s->subject_name}  code={$s->subject_code}" . PHP_EOL;
    }
}
