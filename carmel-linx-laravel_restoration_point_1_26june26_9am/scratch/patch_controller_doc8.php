<?php
$path = 'app/Http/Controllers/CourseFileController.php';
$content = file_get_contents($path);

// Insert methods at the bottom of the class
$methods = <<<PHP

    /**
     * Helper to render Document 8: Course Plan
     */
    private function previewDocument8(\$cf)
    {
        \$batchSubjectId = \$cf->batch_subject_id;
        
        \$lessonPlans = \Illuminate\Support\Facades\DB::table('lesson_plans')
            ->where('batch_subject_id', \$batchSubjectId)
            ->orderBy('day_no')
            ->get();

        return view('course_files.preview_doc_8', compact('lessonPlans', 'cf'))->render();
    }
PHP;

if (strpos($content, 'previewDocument8') === false) {
    // Replace the last closing brace of the class
    $content = preg_replace('/}\s*$/', $methods . "\n}\n", $content);
    file_put_contents($path, $content);
    echo "Added previewDocument8.\n";
} else {
    echo "Already added.\n";
}
