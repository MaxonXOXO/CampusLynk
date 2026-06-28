<?php
$path = 'resources/views/course_files_dashboard.blade.php';
$content = file_get_contents($path);

// Find everything from `<head>` to `</head>`
preg_match('/<head>(.*?)<\/head>/s', $content, $matches);
if (isset($matches[1])) {
    $head = $matches[1];
    
    // Replace all `<script>...</script>` blocks inside `<head>` EXCEPT the tailwind one
    $head = preg_replace('/<script>(.*?)<\/script>/s', '', $head);
    
    // Now replace the head back
    $content = str_replace($matches[1], $head, $content);
    file_put_contents($path, $content);
    echo "Removed bad scripts from head.\n";
} else {
    echo "Could not find head.\n";
}
