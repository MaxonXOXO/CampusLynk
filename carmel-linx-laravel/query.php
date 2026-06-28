<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$attempts = DB::table('test_attempts')->where('status', 'completed')->get();
print_r($attempts);
