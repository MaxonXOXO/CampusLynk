<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Table student_task_submissions exists: " . (Schema::hasTable('student_task_submissions') ? 'YES' : 'NO') . PHP_EOL;
