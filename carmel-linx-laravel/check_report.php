<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

$regNo = "25EL1001";
$request = Request::create("/api/student/academic-report", "GET");
Session::put("userId", $regNo);
Session::put("userRole", "Student");
$controller = new App\Http\Controllers\DataController();
$response = $controller->getAcademicReport($request);
echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
