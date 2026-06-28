<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$branches = App\Models\ClassManagement::distinct()->pluck('branch')->toArray();
echo json_encode($branches);
