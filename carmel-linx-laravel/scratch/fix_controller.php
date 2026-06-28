<?php
$path = 'app/Http/Controllers/CourseFileController.php';
$content = file_get_contents($path);
$content = str_replace('\$', '$', $content);
file_put_contents($path, $content);
echo "Fixed backslash before dollar signs.";
