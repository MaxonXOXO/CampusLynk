<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$backupFile = 'C:/Users/fotonlabz/Desktop/Carmel Linx june 29 5pm WOrking/database_backup.sql';
$handle = fopen($backupFile, 'w');

// Disable foreign key checks
fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

$tables = DB::select("SHOW TABLES");
$dbName = env('DB_DATABASE');
$tableKey = "Tables_in_" . $dbName;

foreach ($tables as $t) {
    $tableName = $t->$tableKey;
    
    // Structure
    $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
    $createSql = $createTable->{'Create Table'} . ";\n\n";
    fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
    fwrite($handle, $createSql);
    
    // Data
    $rows = DB::table($tableName)->get();
    foreach ($rows as $row) {
        $rowArray = (array) $row;
        $keys = array_map(function($k) { return "`$k`"; }, array_keys($rowArray));
        $values = array_map(function($v) {
            if ($v === null) return "NULL";
            return DB::getPdo()->quote($v);
        }, array_values($rowArray));
        
        $insertSql = "INSERT INTO `{$tableName}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ");\n";
        fwrite($handle, $insertSql);
    }
    fwrite($handle, "\n");
}

fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
fclose($handle);
echo "Database backup completed successfully to: {$backupFile}\n";
