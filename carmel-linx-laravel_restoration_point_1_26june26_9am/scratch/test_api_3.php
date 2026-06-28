<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\CourseFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

Session::put('userId', '7902967664');

$controller = new CourseFileController();
$request = new Request();
$response = $controller->getStaffSubjects($request);

echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
