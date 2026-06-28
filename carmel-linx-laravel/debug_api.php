<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$request = Illuminate\Http\Request::create('/api/test-engine/report/b3d10873-7106-11f1-931c-283926634940', 'GET');
$request->setLaravelSession(app('session')->driver());
$request->session()->put('userRole', 'Lecturer');
$controller = app()->make('App\Http\Controllers\TestEngineController');
print_r($controller->generateTestReport($request, 'b3d10873-7106-11f1-931c-283926634940')->getData());
