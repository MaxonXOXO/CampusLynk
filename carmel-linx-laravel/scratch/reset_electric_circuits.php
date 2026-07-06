<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$subjectId = 5; // Electric Circuits

$deleted = DB::table('course_files')->where('batch_subject_id', $subjectId)->delete();

if ($deleted) {
    echo "SUCCESS: Deleted course file record for Electric Circuits (subject_id = {$subjectId}). You can now re-upload the correct syllabus PDF from the dashboard." . PHP_EOL;
} else {
    echo "INFO: No course file record found for subject_id = {$subjectId}." . PHP_EOL;
}
