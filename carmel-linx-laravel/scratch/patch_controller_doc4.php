<?php
$path = 'app/Http/Controllers/CourseFileController.php';
$content = file_get_contents($path);

// Insert methods at the bottom of the class
$methods = <<<PHP

    /**
     * Helper to render Document 4: Course Syllabus
     */
    private function previewDocument4(\$cf)
    {
        \$oldCourseFile = \Illuminate\Support\Facades\DB::table('course_files')
            ->where('batch_subject_id', \$cf->batch_subject_id)
            ->first();

        \$pdfUrl = null;
        if (\$oldCourseFile && !empty(\$oldCourseFile->syllabus_pdf_path)) {
            \$pdfUrl = url(\$oldCourseFile->syllabus_pdf_path);
        }

        return view('course_files.preview_doc_4', compact('pdfUrl', 'cf'))->render();
    }
PHP;

if (strpos($content, 'previewDocument4') === false || strpos($content, 'previewDocument4($cf)') === false) {
    // Replace the last closing brace of the class
    $content = preg_replace('/}\s*$/', $methods . "\n}\n", $content);
    file_put_contents($path, $content);
    echo "Added previewDocument4.\n";
} else {
    echo "Already added.\n";
}
