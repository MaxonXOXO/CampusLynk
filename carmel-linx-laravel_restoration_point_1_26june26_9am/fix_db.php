<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c = json_decode(\Illuminate\Support\Facades\DB::table('course_files')->where('id', 1)->value('summative_manual_tests'), true);
if (isset($c['CO2']['date_of_exam'])) {
    unset($c['CO2']['date_of_exam']);
}
\Illuminate\Support\Facades\DB::table('course_files')->where('id', 1)->update(['summative_manual_tests' => json_encode($c)]);

// Now update test configs so start time is in the past!
\Illuminate\Support\Facades\DB::table('test_configs')->update(['start_time' => now()->subHours(5)]);
echo 'DONE';
