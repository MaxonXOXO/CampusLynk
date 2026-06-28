<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$student = DB::table('students')->first();
if (!$student) {
    echo "No students found.\n";
    exit;
}

$regNo = $student->reg_no;

// Semester 1 Summary
DB::table('student_semester_summary')->insert([
    'reg_no' => $regNo,
    'semester' => 1,
    'sgpa' => 8.5,
    'cgpa' => 8.5,
    'activity_points' => 20,
    'created_at' => now(),
    'updated_at' => now()
]);

// Semester 1 Subjects
$subjects = [
    ['code' => 'MAT101', 'name' => 'Engineering Mathematics I', 'int' => 45, 'board' => 88, 'att' => 95],
    ['code' => 'PHY101', 'name' => 'Engineering Physics', 'int' => 42, 'board' => 80, 'att' => 92],
    ['code' => 'CS101', 'name' => 'Introduction to Computing', 'int' => 48, 'board' => 95, 'att' => 98],
    ['code' => 'ENG101', 'name' => 'Professional Communication', 'int' => 40, 'board' => 75, 'att' => 88],
    ['code' => 'EE101', 'name' => 'Basic Electrical Engg', 'int' => 44, 'board' => 82, 'att' => 90],
];

foreach ($subjects as $s) {
    $total = $s['int'] + $s['board'];
    $grade = 'B';
    if ($total >= 90) $grade = 'S';
    elseif ($total >= 85) $grade = 'A+';
    elseif ($total >= 80) $grade = 'A';
    elseif ($total >= 70) $grade = 'B+';

    DB::table('student_semester_marks')->insert([
        'reg_no' => $regNo,
        'semester' => 1,
        'subject_code' => $s['code'],
        'subject_name' => $s['name'],
        'internal_marks' => $s['int'],
        'board_marks' => $s['board'],
        'total_marks' => $total,
        'grade' => $grade,
        'attendance_percentage' => $s['att'],
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

// Semester 2 Summary
DB::table('student_semester_summary')->insert([
    'reg_no' => $regNo,
    'semester' => 2,
    'sgpa' => 9.2,
    'cgpa' => 8.85,
    'activity_points' => 40,
    'created_at' => now(),
    'updated_at' => now()
]);

// Semester 2 Subjects
$subjects2 = [
    ['code' => 'MAT102', 'name' => 'Engineering Mathematics II', 'int' => 48, 'board' => 92, 'att' => 96],
    ['code' => 'CHM101', 'name' => 'Engineering Chemistry', 'int' => 46, 'board' => 85, 'att' => 94],
    ['code' => 'CS102', 'name' => 'Data Structures in C', 'int' => 50, 'board' => 98, 'att' => 100],
    ['code' => 'ME101', 'name' => 'Basic Mechanical Engg', 'int' => 45, 'board' => 86, 'att' => 91],
    ['code' => 'EC101', 'name' => 'Basic Electronics Engg', 'int' => 47, 'board' => 89, 'att' => 93],
];

foreach ($subjects2 as $s) {
    $total = $s['int'] + $s['board'];
    $grade = 'B';
    if ($total >= 90) $grade = 'S';
    elseif ($total >= 85) $grade = 'A+';
    elseif ($total >= 80) $grade = 'A';
    elseif ($total >= 70) $grade = 'B+';

    DB::table('student_semester_marks')->insert([
        'reg_no' => $regNo,
        'semester' => 2,
        'subject_code' => $s['code'],
        'subject_name' => $s['name'],
        'internal_marks' => $s['int'],
        'board_marks' => $s['board'],
        'total_marks' => $total,
        'grade' => $grade,
        'attendance_percentage' => $s['att'],
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

echo "Dummy data inserted successfully for student: " . $regNo . "\n";
