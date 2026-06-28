<?php

$path = 'app/Http/Controllers/CourseFileController.php';
$content = file_get_contents($path);

// Eager load documents when getting a course file
$content = str_replace(
    "\$cf = CfCourseFile::where('id', \$id)->first();",
    "\$cf = CfCourseFile::with('documents')->where('id', \$id)->first();",
    $content
);

// Save the checklist items in saveCourseFile()
$oldSaveLogic = "\$cf->status = 'Draft';\n        \$cf->save();";
$newSaveLogic = "\$cf->status = 'Draft';\n        \$cf->save();

        if (\$request->has('checklist')) {
            foreach (\$request->input('checklist') as \$doc) {
                \App\Models\CfCourseFileDocument::updateOrCreate(
                    [
                        'course_file_id' => \$cf->id,
                        'document_number' => \$doc['document_number']
                    ],
                    [
                        'document_name' => \$doc['document_name'],
                        'is_checked' => \$doc['is_checked'],
                        'remarks' => \$doc['remarks']
                    ]
                );
            }
        }";

if (strpos($content, "request->has('checklist')") === false) {
    $content = str_replace($oldSaveLogic, $newSaveLogic, $content);
}

file_put_contents($path, $content);
echo "Checklist backend logic added.\n";
