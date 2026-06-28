<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    view('course_files_dashboard')->render();
    echo "SUCCESS";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
} catch (\Throwable $e) {
    echo "FATAL: " . $e->getMessage() . "\n";
}
