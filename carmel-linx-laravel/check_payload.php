<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$test = \Illuminate\Support\Facades\DB::table('test_configs')->first();
echo "Type: " . gettype($test->questions_payload) . "\n";
echo "Value: " . substr(json_encode($test->questions_payload), 0, 100) . "\n";
