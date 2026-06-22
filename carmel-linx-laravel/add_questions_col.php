<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('course_files', 'assignment_questions')) {
    Schema::table('course_files', function(Blueprint $table) {
        $table->json('assignment_questions')->nullable();
    });
    echo "Column assignment_questions added.";
} else {
    echo "Column already exists.";
}
