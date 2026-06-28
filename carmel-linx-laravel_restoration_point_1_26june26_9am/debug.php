<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$tests = DB::table('test_configs')->whereIn('test_id', ['b3d10873-7106-11f1-931c-283926634940', '3e8f389e-7108-11f1-931c-283926634940'])->get();
foreach($tests as $t) { echo $t->test_id . ' -> ' . $t->questions_payload . PHP_EOL; }
