<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/api/admin/users?role=student', 'GET');
// Mock the session manually
$app['session']->driver()->put('userId', '9100000001');
$app['session']->driver()->put('userRole', 'Lecturer');
$app['session']->driver()->put('userBranch', 'EL');

$request->setLaravelSession($app['session']->driver());
$response = $kernel->handle($request);
echo $response->getContent();
