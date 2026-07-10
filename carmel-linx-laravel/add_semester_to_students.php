<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('students', 'semester')) {
    Schema::table('students', function (Blueprint $table) {
        $table->integer('semester')->default(1)->after('classroom_id');
    });
    echo "Semester column added to students table successfully.\n";
} else {
    echo "Semester column already exists in students table.\n";
}
