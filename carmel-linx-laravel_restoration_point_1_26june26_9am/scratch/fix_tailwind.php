<?php
$path = 'resources/views/course_files_dashboard.blade.php';
$content = file_get_contents($path);

$badTailwind = '<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4">
        const defaultChecklist';

$goodTailwind = '<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        const defaultChecklist';

$content = str_replace($badTailwind, $goodTailwind, $content);
file_put_contents($path, $content);
echo "Fixed Tailwind script tag.\n";
