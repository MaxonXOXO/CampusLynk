<?php
require __DIR__.'/vendor/autoload.php';
\ = require_once __DIR__.'/bootstrap/app.php';
\->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('leave_records', function (Blueprint \) {
    \->string('leave_date')->change();
    if (!Schema::hasColumn('leave_records', 'no_of_days')) {
        \->string('no_of_days', 20)->nullable()->after('leave_date');
    }
});

echo 'Database altered successfully';
