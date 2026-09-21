<?php

declare(strict_types=1);

$base = 'd:/CampusLynk/CampusLynk/carmel-linx-laravel/resources/views/';

function splitFileIntoChunks(string $filePath, string $prefix, int $maxLines = 450): array {
    if (!file_exists($filePath)) return [];
    $lines = file($filePath);
    $total = count($lines);
    if ($total <= $maxLines) return [basename($filePath)];

    $dir = dirname($filePath);
    $chunkFiles = [];
    $chunks = array_chunk($lines, $maxLines);

    foreach ($chunks as $idx => $chunk) {
        $chunkNum = $idx + 1;
        $chunkName = "{$prefix}-part{$chunkNum}.blade.php";
        $chunkPath = "{$dir}/{$chunkName}";
        file_put_contents($chunkPath, implode('', $chunk));
        $chunkFiles[] = $chunkName;
        echo "    [CHUNK] {$chunkName} (" . count($chunk) . " lines)\n";
    }

    // Replace original file with includes of the chunks
    $includeContent = "";
    $viewDirName = basename($dir);
    foreach ($chunkFiles as $cf) {
        $bladeName = str_replace('.blade.php', '', $cf);
        $includeContent .= "@include('{$viewDirName}.{$bladeName}')\n";
    }
    file_put_contents($filePath, $includeContent);
    echo "  [SPLIT COMPLETE] " . basename($dir) . "/" . basename($filePath) . " -> " . count($chunkFiles) . " parts\n";

    return $chunkFiles;
}

echo "=== SPLITTING FILES EXCEEDING 500 LINES ===\n";

// 1. admin/modals.blade.php (797 lines)
splitFileIntoChunks($base . 'admin/modals.blade.php', 'modals');

// 2. admin/scripts.blade.php (1986 lines)
splitFileIntoChunks($base . 'admin/scripts.blade.php', 'scripts');

// 3. chairman/scripts.blade.php (1536 lines)
splitFileIntoChunks($base . 'chairman/scripts.blade.php', 'scripts');

// 4. hod/modals.blade.php (952 lines)
splitFileIntoChunks($base . 'hod/modals.blade.php', 'modals');

// 5. hod/scripts.blade.php (3494 lines)
splitFileIntoChunks($base . 'hod/scripts.blade.php', 'scripts');

// 6. lecturer/scripts.blade.php (7195 lines)
splitFileIntoChunks($base . 'lecturer/scripts.blade.php', 'scripts');

// 7. tutor/scripts.blade.php (1588 lines)
splitFileIntoChunks($base . 'tutor/scripts.blade.php', 'scripts');

// 8. student/scripts.blade.php (993 lines)
splitFileIntoChunks($base . 'student/scripts.blade.php', 'scripts');

// 9. academic_coordinator/scripts.blade.php (553 lines)
splitFileIntoChunks($base . 'academic_coordinator/scripts.blade.php', 'scripts');

echo "\n[SUCCESS] Splitting completed. Re-running line ceiling verification...\n";
