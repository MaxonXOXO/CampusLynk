<?php
$path = 'app/Http/Controllers/CourseFileController.php';
$content = file_get_contents($path);

// Insert methods at the bottom of the class
$methods = <<<PHP

    /**
     * Handle saving the CO-PO Mapping for Document 6
     */
    public function saveCoPoMapping(Request \$request, \$id)
    {
        \$cf = \App\Models\CfCourseFile::find(\$id);
        if (!\$cf || !\$cf->batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Invalid course file']);
        }

        \$subjectCode = \$cf->batchSubject->subject_code;
        \$mappingData = \$request->input('co_po_mapping');

        // Save to syllabus_registry to reuse across batches
        \Illuminate\Support\Facades\DB::table('syllabus_registry')
            ->updateOrInsert(
                ['subject_code' => \$subjectCode],
                ['co_po_mapping' => json_encode(\$mappingData), 'updated_at' => now()]
            );

        // Auto check document 6
        \$doc = \App\Models\CfCourseFileDocument::firstOrCreate(
            ['course_file_id' => \$cf->id, 'document_number' => 6],
            ['document_name' => 'Document 6']
        );
        \$doc->is_checked = true;
        \$doc->save();

        return response()->json(['status' => 'SUCCESS', 'message' => 'CO-PO Mapping saved']);
    }

    /**
     * Helper to render Document 6: Course Outcomes & CO-PO Mapping
     */
    private function previewDocument6(\$cf)
    {
        \$subjectCode = \$cf->batchSubject->subject_code ?? null;
        \$registry = \Illuminate\Support\Facades\DB::table('syllabus_registry')->where('subject_code', \$subjectCode)->first();
        
        \$mapping = null;
        if (\$registry && !empty(\$registry->co_po_mapping)) {
            \$mapping = json_decode(\$registry->co_po_mapping, true);
        }

        // Generate an empty mapping grid if none exists (CO1-CO6, PO1-PO7, PSO1-PSO3)
        if (!\$mapping) {
            \$mapping = [];
            for (\$i = 1; \$i <= 6; \$i++) {
                \$row = ['co' => 'CO' . \$i, 'description' => ''];
                for (\$j = 1; \$j <= 7; \$j++) \$row['po' . \$j] = '';
                for (\$k = 1; \$k <= 3; \$k++) \$row['pso' . \$k] = '';
                \$mapping[] = \$row;
            }
        }

        return view('course_files.preview_doc_6', compact('mapping', 'cf'))->render();
    }
PHP;

if (strpos($content, 'saveCoPoMapping') === false) {
    // Replace the last closing brace of the class
    $content = preg_replace('/}\s*$/', $methods . "\n}\n", $content);
    file_put_contents($path, $content);
    echo "Added saveCoPoMapping.\n";
} else {
    echo "Already added.\n";
}
