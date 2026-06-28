<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/api/admin/users?role=student', 'GET');
// Set session for Lecturer 1 EL
$request->setLaravelSession($app['session']->driver());
$request->session()->put('userId', '9100000001');
$request->session()->put('userRole', 'Lecturer');
$request->session()->put('userBranch', 'EL');

$response = $kernel->handle($request);
echo $response->getContent();
