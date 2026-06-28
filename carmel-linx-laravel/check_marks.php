<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
print_r(DB::select('SELECT DISTINCT category, co_tag FROM academic_marks LIMIT 20'));
