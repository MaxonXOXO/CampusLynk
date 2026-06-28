<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

Illuminate\Support\Facades\Session::put('userId', '25EL1001');
Illuminate\Support\Facades\Session::put('userRole', 'Student');
$controller = new \App\Http\Controllers\MentoringController();
$res = $controller->getFullStudentDiary('25EL1001');

echo json_encode($res->getData(), JSON_PRETTY_PRINT);
