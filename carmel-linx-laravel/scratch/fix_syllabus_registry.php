<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$batchSubjects = DB::table('batch_subjects')->get();

echo "Checking syllabus registry..." . PHP_EOL;

foreach ($batchSubjects as $subj) {
    $exists = DB::table('syllabus_registry')->where('subject_code', $subj->subject_code)->exists();
    if (!$exists) {
        // Find course_file if exists to get CO count
        $cf = DB::table('course_files')->where('batch_subject_id', $subj->id)->first();
        $coCount = 6;
        if ($cf && !empty($cf->parsed_cos)) {
            $cos = json_decode($cf->parsed_cos, true);
            if (is_array($cos)) {
                $coCount = count($cos);
            }
        }

        DB::table('syllabus_registry')->insert([
            'subject_code' => $subj->subject_code,
            'subject_name' => $subj->subject_name,
            'revision_year' => 2021,
            'co_count' => $coCount,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "  CREATED: Registered missing subject code '{$subj->subject_code}' ({$subj->subject_name})" . PHP_EOL;
    } else {
        echo "  EXISTS: Subject code '{$subj->subject_code}' is already registered." . PHP_EOL;
    }
}

echo "Done!" . PHP_EOL;
