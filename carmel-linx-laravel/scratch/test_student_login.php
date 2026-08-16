<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

$controller = new AuthController();
$request = Request::create('/login', 'POST', [
    'userId' => '2401001',
    'password' => 'student123',
    'roleType' => 'student'
]);

$response = $controller->login($request);
echo $response->getContent() . PHP_EOL;
