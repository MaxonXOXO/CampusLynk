<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s1 = App\Models\BatchSubject::firstOrCreate(['classroom_id'=>'EL_2025_2028','semester'=>1,'subject_code'=>'EL101'], ['subject_name'=>'Basic Electronics', 'subject_type'=>'Theory', 'credits'=>3]);
$s2 = App\Models\BatchSubject::firstOrCreate(['classroom_id'=>'EL_2025_2028','semester'=>1,'subject_code'=>'MA101'], ['subject_name'=>'Mathematics I', 'subject_type'=>'Theory', 'credits'=>4]);

$student = App\Models\Student::where('classroom_id','EL_2025_2028')->first();
if($student) {
    App\Models\StudentSemesterMarks::firstOrCreate(['reg_no'=>$student->reg_no, 'semester'=>1, 'subject_code'=>'EL101'], ['grade'=>'A+', 'subject_name'=>'Basic Electronics', 'internal_marks'=>40, 'board_marks'=>80, 'total_marks'=>120]);
    App\Models\StudentSemesterMarks::firstOrCreate(['reg_no'=>$student->reg_no, 'semester'=>1, 'subject_code'=>'MA101'], ['grade'=>'B', 'subject_name'=>'Mathematics I', 'internal_marks'=>35, 'board_marks'=>60, 'total_marks'=>95]);
    App\Models\StudentSemesterSummary::firstOrCreate(['reg_no'=>$student->reg_no, 'semester'=>1], ['sgpa'=>8.5, 'attendance_percentage'=>92.5, 'activity_points'=>15]);
    
    // Also S2
    App\Models\BatchSubject::firstOrCreate(['classroom_id'=>'EL_2025_2028','semester'=>2,'subject_code'=>'EL102'], ['subject_name'=>'Advanced Electronics', 'subject_type'=>'Theory', 'credits'=>3]);
    App\Models\StudentSemesterMarks::firstOrCreate(['reg_no'=>$student->reg_no, 'semester'=>2, 'subject_code'=>'EL102'], ['grade'=>'A', 'subject_name'=>'Advanced Electronics', 'internal_marks'=>45, 'board_marks'=>70, 'total_marks'=>115]);
    App\Models\StudentSemesterSummary::firstOrCreate(['reg_no'=>$student->reg_no, 'semester'=>2], ['sgpa'=>9.0, 'attendance_percentage'=>95, 'activity_points'=>30]);
}

echo "Done\n";
