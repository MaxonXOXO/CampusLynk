<?php
namespace App\Http\Controllers;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$students = [];
$names = ['Arjun K', 'Meenakshi S', 'Rahul Menon', 'Anjali Nair', 'Gautham V', 'Sneha M', 'Vishnu P', 'Kavya T', 'Adithya R', 'Neha K'];

for ($i = 0; $i < 10; $i++) {
    $reg = 'KAR23CS' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
    $students[] = [
        'reg_no' => $reg,
        'adm_no' => 'ADM' . (2000 + $i),
        'name' => $names[$i],
        'email' => 'student' . $i . '@example.com',
        'password' => Hash::make('password'),
        'branch' => 'Computer Engineering',
        'admission_year' => 2023,
        'classroom_id' => 'CS5A',
        'status' => 'Approved',
        'created_at' => now(),
        'updated_at' => now(),
    ];
}

DB::table('students')->insertOrIgnore($students);
echo "Injected 10 dummy students into CS5A.";
