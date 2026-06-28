<?php

$path = 'app/Http/Controllers/CourseFileController.php';
$content = file_get_contents($path);

// 1. Fix eager loading
$content = str_replace(
    "SubjectStaffAssignment::with(['batchSubject.batch', 'batchSubject.subject'])", 
    "SubjectStaffAssignment::with(['batchSubject.classroom'])", 
    $content
);

// 2. Fix relationship access
$content = str_replace("if (!\$bs || !\$bs->batch) continue;", "if (!\$bs || !\$bs->classroom) continue;", $content);
$content = str_replace("\$batchYear = \$bs->batch->batch_year;", "\$batchYear = \$bs->classroom->batch_year;", $content);
$content = str_replace("\$branch = \$bs->batch->branch;", "\$branch = \$bs->classroom->branch;", $content);

// 3. Fix subject properties
$content = str_replace("'subject_code'     => \$bs->subject->subject_code ?? '',", "'subject_code'     => \$bs->subject_code,", $content);
$content = str_replace("'subject_name'     => \$bs->subject->subject_name ?? '',", "'subject_name'     => \$bs->subject_name,", $content);

// 4. Any others?
// Check if getCourseFile or generatePdf use these wrong relationships.
// It seems generatePdf might use batchSubject->batch as well? Let's fix that globally if it does.
$content = str_replace("->batch->", "->classroom->", $content);
$content = str_replace("->subject->subject_code", "->subject_code", $content);
$content = str_replace("->subject->subject_name", "->subject_name", $content);

file_put_contents($path, $content);

echo "Fixed relationships in CourseFileController.\n";
