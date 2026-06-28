<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\StudentSemesterMarks;
use App\Models\StudentSemesterSummary;

$classroomId = 'EL_2025_2028';
$password = Hash::make('1234');

$firstNames = ['Rahul', 'Sneha', 'Arun', 'Priya', 'Kiran', 'Anjali', 'Deepak', 'Meera', 'Vijay', 'Divya'];
$lastNames = ['Kumar', 'Nair', 'Menon', 'Pillai', 'Krishnan', 'Varma', 'Iyer', 'George', 'Thomas', 'Joseph'];

for ($i = 1; $i <= 10; $i++) {
    $regNo = 'EL2025' . sprintf('%03d', $i);
    $admNo = 'ADM' . rand(1000, 9999) . sprintf('%03d', $i);
    $name = $firstNames[$i-1] . ' ' . $lastNames[$i-1];
    $email = strtolower($firstNames[$i-1]) . $i . '@example.com';
    $phone = '9800000' . sprintf('%03d', $i);

    $student = Student::updateOrCreate(
        ['reg_no' => $regNo],
        [
            'adm_no' => $admNo,
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'branch' => 'Electronics',
            'admission_year' => 2025,
            'classroom_id' => $classroomId,
            'status' => 'Approved',
            'academic_status' => 'Active',
            'photo_url' => 'https://i.pravatar.cc/150?u=' . $regNo
        ]
    );

    // Give them some S1 data
    $grades = ['A+', 'A', 'B+', 'B', 'C+', 'C'];
    StudentSemesterMarks::updateOrCreate(
        ['reg_no' => $regNo, 'semester' => 1, 'subject_code' => 'EL101'],
        ['grade' => $grades[array_rand($grades)], 'subject_name' => 'Basic Electronics']
    );
    StudentSemesterMarks::updateOrCreate(
        ['reg_no' => $regNo, 'semester' => 1, 'subject_code' => 'MA101'],
        ['grade' => $grades[array_rand($grades)], 'subject_name' => 'Mathematics I']
    );

    $sgpa = rand(65, 95) / 10;
    $att = rand(75, 100);
    $act = rand(5, 20);
    StudentSemesterSummary::updateOrCreate(
        ['reg_no' => $regNo, 'semester' => 1],
        ['sgpa' => $sgpa, 'attendance_percentage' => $att, 'activity_points' => $act]
    );
    
    // Give them some S2 data
    StudentSemesterMarks::updateOrCreate(
        ['reg_no' => $regNo, 'semester' => 2, 'subject_code' => 'EL102'],
        ['grade' => $grades[array_rand($grades)], 'subject_name' => 'Advanced Electronics']
    );
    $sgpa2 = rand(65, 95) / 10;
    StudentSemesterSummary::updateOrCreate(
        ['reg_no' => $regNo, 'semester' => 2],
        ['sgpa' => $sgpa2, 'cgpa' => round(($sgpa + $sgpa2)/2, 2), 'attendance_percentage' => rand(75, 100), 'activity_points' => $act + rand(5, 15)]
    );
}

echo "Successfully seeded 10 students into batch {$classroomId}.\n";
