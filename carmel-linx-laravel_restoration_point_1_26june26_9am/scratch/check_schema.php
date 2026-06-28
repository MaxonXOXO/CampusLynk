<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "batch_subjects columns:\n";
print_r(Schema::getColumnListing('batch_subjects'));

echo "\nlesson_plans columns:\n";
print_r(Schema::getColumnListing('lesson_plans'));
