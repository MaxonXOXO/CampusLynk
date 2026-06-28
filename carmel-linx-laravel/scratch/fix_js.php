<?php
$path = 'resources/views/course_files_dashboard.blade.php';
$content = file_get_contents($path);
$content = str_replace("fetch(`/api/course-files//preview/`)", "fetch(`/api/course-files/\${currentCfId}/preview/\${docNo}`)", $content);
file_put_contents($path, $content);
echo "Fixed interpolation.\n";
