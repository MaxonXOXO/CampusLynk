<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ACADEMIC MARKS ===" . PHP_EOL;
$marks = DB::table('academic_marks')->get();
foreach ($marks as $m) {
    echo "  reg_no={$m->reg_no} subject={$m->subject_code} category={$m->category} co={$m->co_tag} marks={$m->marks_obtained}" . PHP_EOL;
}

echo PHP_EOL . "=== TASK SUBMISSIONS ===" . PHP_EOL;
$subs = DB::table('student_task_submissions')->get();
foreach ($subs as $s) {
    echo "  reg_no={$s->reg_no} subject={$s->subject_code} category={$s->category} co={$s->co_tag} status={$s->status}" . PHP_EOL;
}
