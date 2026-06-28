<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/api/course-files/subjects', 'GET');
// Mock the session user role
$request->setLaravelSession($app['session']->driver());
$request->session()->put('userId', '7902967664'); // Mock a staff mobile number
$request->session()->put('userRole', 'Lecturer');

$response = $kernel->handle($request);
echo $response->getContent();
