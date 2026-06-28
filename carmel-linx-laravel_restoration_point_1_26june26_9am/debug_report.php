<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

Illuminate\Support\Facades\Session::put('userId', '25EL1001');
$c = app()->make('App\Http\Controllers\DataController');
echo json_encode($c->getAcademicReport()->getData());
