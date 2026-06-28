<?php
$path = 'app/Http/Controllers/CourseFileController.php';
$content = file_get_contents($path);

// Fix eager loading in getCourseFile and generatePdf
$content = str_replace(
    "'batchSubject.subject', 'batchSubject.batch'",
    "'batchSubject.classroom'",
    $content
);
$content = str_replace(
    "['sectionA', 'sectionB', 'sectionC', 'sectionD', 'batchSubject.classroom']",
    "['sectionA', 'sectionB', 'sectionC', 'sectionD', 'batchSubject.classroom', 'documents']",
    $content
);

file_put_contents($path, $content);
echo "Fixed relationships.\n";
