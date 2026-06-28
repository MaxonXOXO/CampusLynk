<?php
// Scratch file to test submitTest logic
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::create(
        '/api/student/online-tests/fake-id/submit',
        'POST',
        ['answers' => [0 => 'Real-time performance constraints', 1 => 'Reset the system on software hang']]
    )
);
echo $response->getContent();
