<?php
$path = 'app/Http/Controllers/CourseFileController.php';
$content = file_get_contents($path);

// Insert methods at the bottom of the class
$methods = <<<PHP

    /**
     * Save custom payload data for a specific document (e.g., student list remarks).
     */
    public function saveDocumentPayload(Request \$request, \$id, \$docNo)
    {
        \$cf = CfCourseFile::find(\$id);
        if (!\$cf) {
            return response()->json(['status' => 'ERROR', 'message' => 'Course file not found']);
        }

        \$doc = CfCourseFileDocument::firstOrCreate(
            ['course_file_id' => \$cf->id, 'document_number' => \$docNo],
            ['document_name' => 'Document ' . \$docNo]
        );

        \$doc->data_payload = \$request->input('payload');
        // Auto-check it when saving payload
        \$doc->is_checked = true;
        \$doc->save();

        return response()->json(['status' => 'SUCCESS', 'message' => 'Document saved successfully']);
    }

    /**
     * Helper to render Document 3: Student List
     */
    private function previewDocument3(\$cf)
    {
        \$doc = CfCourseFileDocument::where('course_file_id', \$cf->id)->where('document_number', 3)->first();
        \$payload = \$doc && \$doc->data_payload ? json_decode(\$doc->data_payload, true) : null;

        if (!\$payload) {
            // Generate initial payload
            \$bs = \$cf->batchSubject;
            if (!\$bs || !\$bs->classroom_id) {
                return "<div class='text-red-500 font-bold p-8'>Classroom mapping not found for this subject.</div>";
            }
            \$students = \App\Models\Student::where('classroom_id', \$bs->classroom_id)->orderBy('name')->get();
            
            \$payload = [];
            \$roll = 1;
            foreach (\$students as \$s) {
                \$payload[] = [
                    'roll_no' => \$roll++,
                    'reg_no' => \$s->reg_no,
                    'name' => \$s->name,
                    'type' => \$s->admission_type ?? 'Regular',
                    'remarks' => ''
                ];
            }
        }

        return view('course_files.preview_doc_3', compact('payload', 'cf'))->render();
    }
PHP;

if (strpos($content, 'saveDocumentPayload') === false) {
    // Replace the last closing brace of the class
    $content = preg_replace('/}\s*$/', $methods . "\n}\n", $content);
    file_put_contents($path, $content);
    echo "Added saveDocumentPayload to CourseFileController.\n";
} else {
    echo "Already added.\n";
}
