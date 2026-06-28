<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Initialize Session properly
$session = $app->make('session');
$request = Illuminate\Http\Request::create('/api/student/online-tests', 'GET');

// Attach session to request
$request->setLaravelSession($session->driver());
$request->session()->put('userId', '25EL1001');
$request->session()->put('classroomId', 'EL_2025_2028');

$response = $kernel->handle($request);
echo $response->getContent();
