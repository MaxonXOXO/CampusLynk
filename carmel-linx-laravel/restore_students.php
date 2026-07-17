<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;

$students = Student::where('classroom_id', 'like', '%_LET')->get();
echo "Found " . $students->count() . " students to restore.\n";
foreach ($students as $student) {
    $old = $student->classroom_id;
    $new = str_replace('_LET', '', $old);
    $student->classroom_id = $new;
    $student->save();
    echo "Restored student {$student->reg_no}: {$old} -> {$new}\n";
}
echo "Done!\n";
