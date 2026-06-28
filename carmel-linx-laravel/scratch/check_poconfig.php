<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\Schema;

echo "po_config:\n";
print_r(Schema::getColumnListing('po_config'));

echo "\nsyllabus_registry:\n";
print_r(Schema::getColumnListing('syllabus_registry'));
