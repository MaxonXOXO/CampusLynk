<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/api/student/online-tests', 'GET');
$session = $app->make('session')->driver('array');
$session->put('userId', '25EL1001');
$session->put('classroomId', 'EL_2025_2028');
$request->setLaravelSession($session);
$response = $kernel->handle($request);
echo $response->getContent();
