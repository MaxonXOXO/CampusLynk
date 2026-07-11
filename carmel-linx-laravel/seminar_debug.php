<?php
// Test getSeminarEvaluations for batch_subject_id=16 as lecturer 9100000001

$subjectId = 16;
$userId = '9100000001';

$bs = \App\Models\BatchSubject::find($subjectId);
echo "BatchSubject: classroom_id={$bs->classroom_id}, semester={$bs->semester}\n";

$students = \App\Models\Student::where('classroom_id', $bs->classroom_id)
    ->where('semester', $bs->semester)
    ->get(['reg_no', 'name', 'roll_no', 'sbte_reg_no']);

echo "Students found: " . count($students) . "\n";

$myEvaluations = \App\Models\SeminarEvaluation::where('batch_subject_id', $subjectId)
    ->where('assessor_mobile_no', $userId)
    ->get();

echo "My evaluations: " . count($myEvaluations) . "\n";

$allEvaluations = \App\Models\SeminarEvaluation::where('batch_subject_id', $subjectId)->get();
echo "All evaluations: " . count($allEvaluations) . "\n";

foreach ($students as $student) {
    $myEval = $myEvaluations->where('reg_no', $student->reg_no)->first();
    $studentAllEvals = $allEvaluations->where('reg_no', $student->reg_no);
    $evalCount = $studentAllEvals->count();
    $averageScore = $evalCount > 0 ? round($studentAllEvals->avg('total_score'), 2) : 0;
    
    echo "Student {$student->reg_no}: myEval=" . ($myEval ? "total={$myEval->total_score}" : "null") . ", avg={$averageScore}, evalCount={$evalCount}\n";
}
