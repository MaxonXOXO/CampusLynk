<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$test = \Illuminate\Support\Facades\DB::table('test_configs')->where('selected_cos', 'LIKE', '%CO4%')->first();
$req = \Illuminate\Http\Request::create('/api/student/online-tests/' . $test->test_id . '/start', 'POST');
session()->put('userId', '25EL1001');

$controller = new \App\Http\Controllers\TestEngineController();
$res = $controller->startTest($req, $test->test_id);
echo json_encode($res->getData());
