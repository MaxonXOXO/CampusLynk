<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$request = Illuminate\Http\Request::create('/api/classroom/1/active-online-tests', 'GET');
$request->setLaravelSession(app('session')->driver());
$request->session()->put('userRole', 'Lecturer');
$controller = app()->make('App\Http\Controllers\TestEngineController');
print_r($controller->getActiveTestsLecturer($request, 1)->getData());
