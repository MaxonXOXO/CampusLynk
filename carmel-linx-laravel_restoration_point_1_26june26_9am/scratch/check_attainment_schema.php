<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\Schema;

$tables = [
    'academic_marks', 
    'student_semester_marks', 
    'student_task_submissions', 
    'test_attempts', 
    'test_configs'
];

foreach ($tables as $table) {
    echo "$table columns:\n";
    print_r(Schema::getColumnListing($table));
    echo "\n";
}
