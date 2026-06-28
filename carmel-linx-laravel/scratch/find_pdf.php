<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\Schema;

$tables = array_map('current', Illuminate\Support\Facades\DB::select('SHOW TABLES'));
foreach ($tables as $table) {
    $cols = Schema::getColumnListing($table);
    foreach ($cols as $col) {
        if (str_contains(strtolower($col), 'pdf') || str_contains(strtolower($col), 'path') || str_contains(strtolower($col), 'file')) {
            echo "$table -> $col\n";
        }
    }
}
