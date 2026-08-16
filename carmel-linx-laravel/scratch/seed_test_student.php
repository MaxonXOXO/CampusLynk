<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Seed a primary test student account
DB::table('students')->updateOrInsert(
    ['reg_no' => '2401001'],
    [
        'adm_no' => 'ADM2024001',
        'sbte_reg_no' => '24EL001',
        'name' => 'Alex Johnson',
        'email' => 'alex.johnson@carmel.edu.in',
        'password' => 'student123',
        'phone' => '9876543210',
        'branch' => 'Electronics Engineering',
        'admission_year' => 2024,
        'admission_type' => 'Regular',
        'semester' => 4,
        'classroom_id' => 'EL_2024_2027',
        'status' => 'Approved',
        'academic_status' => 'Active',
        'created_at' => now(),
        'updated_at' => now()
    ]
);

// Seed a second student for Computer Science & Engineering
DB::table('students')->updateOrInsert(
    ['reg_no' => '2402001'],
    [
        'adm_no' => 'ADM2024002',
        'sbte_reg_no' => '24CT001',
        'name' => 'Maria Davis',
        'email' => 'maria.davis@carmel.edu.in',
        'password' => 'student123',
        'phone' => '9876543211',
        'branch' => 'Computer Engineering',
        'admission_year' => 2024,
        'admission_type' => 'Regular',
        'semester' => 4,
        'classroom_id' => 'CT_2024_2027',
        'status' => 'Approved',
        'academic_status' => 'Active',
        'created_at' => now(),
        'updated_at' => now()
    ]
);

echo "Test student accounts verified.\n";
