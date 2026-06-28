<?php
$path = 'app/Http/Controllers/CourseFileController.php';
$content = file_get_contents($path);

// Insert methods at the bottom of the class
$methods = <<<PHP

    /**
     * Helper to render Document 11: Internal Examination Result Analysis
     */
    private function previewDocument11(\$cf)
    {
        \$subjectCode = \$cf->batchSubject->subject_code ?? null;
        \$semester = \$cf->batchSubject->semester ?? null;
        
        // Fetch from student_semester_marks joined with students table
        \$marks = \Illuminate\Support\Facades\DB::table('student_semester_marks')
            ->join('students', 'student_semester_marks.reg_no', '=', 'students.reg_no')
            ->select('students.name', 'student_semester_marks.reg_no', 'student_semester_marks.internal_marks', 'student_semester_marks.attendance_percentage')
            ->where('student_semester_marks.subject_code', \$subjectCode)
            ->where('student_semester_marks.semester', \$semester)
            ->orderBy('students.name')
            ->get();

        return view('course_files.preview_doc_11', compact('marks', 'cf'))->render();
    }
PHP;

if (strpos($content, 'previewDocument11') === false) {
    // Replace the last closing brace of the class
    $content = preg_replace('/}\s*$/', $methods . "\n}\n", $content);
    file_put_contents($path, $content);
    echo "Added previewDocument11.\n";
} else {
    echo "Already added.\n";
}
