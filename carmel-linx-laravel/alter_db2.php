<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('leave_records', function (Blueprint $table) {
    // We must use raw statement if enum/date change fails without doctrine/dbal sometimes, but string is usually okay.
    // Actually, DB::statement is safer for modifying column types if doctrine/dbal is missing.
    DB::statement('ALTER TABLE leave_records MODIFY leave_date VARCHAR(100)');
    
    if (!Schema::hasColumn('leave_records', 'no_of_days')) {
        $table->string('no_of_days', 20)->nullable()->after('leave_date');
    }
});

echo 'Database altered successfully';
