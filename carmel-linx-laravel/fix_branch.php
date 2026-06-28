<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$affected = DB::table('students')->where('branch', 'Electronics')->update(['branch' => 'EL']);
echo "Updated $affected rows.";
