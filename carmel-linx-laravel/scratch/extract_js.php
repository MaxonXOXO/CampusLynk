<?php
$content = file_get_contents('resources/views/course_files_dashboard.blade.php');
preg_match_all('/<script>(.*?)<\/script>/s', $content, $matches);
foreach ($matches[1] as $idx => $script) {
    file_put_contents("scratch/script_$idx.js", $script);
}
echo "Saved " . count($matches[1]) . " scripts.";
