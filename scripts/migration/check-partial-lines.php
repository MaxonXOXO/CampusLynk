<?php

$dirs = ["admin", "chairman", "hod", "lecturer", "tutor", "workshop", "student", "parent", "academic_coordinator", "general_coordinator"];
$base = "d:/CampusLynk/CampusLynk/carmel-linx-laravel/resources/views/";
$over = 0;

foreach ($dirs as $d) {
    $dirPath = $base . $d;
    if (!is_dir($dirPath)) continue;
    foreach (scandir($dirPath) as $f) {
        if (str_ends_with($f, ".blade.php")) {
            $lines = count(file("$dirPath/$f"));
            if ($lines > 500) {
                echo sprintf("[EXCEEDS 500] %-35s: %d lines\n", "$d/$f", $lines);
                $over++;
            }
        }
    }
}

if ($over === 0) {
    echo "All partials are strictly under 500 lines!\n";
} else {
    echo "Total files exceeding 500 lines: $over\n";
}
