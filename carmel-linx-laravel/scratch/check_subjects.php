<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$lps = DB::table('lesson_plans')->where('batch_subject_id', 5)->take(5)->get();
foreach ($lps as $lp) {
    echo "ID: {$lp->id} | BatchSubject: {$lp->batch_subject_id} | Day: {$lp->day_no} | CO: {$lp->co_id} | Topic: {$lp->topic_content}\n";
}
