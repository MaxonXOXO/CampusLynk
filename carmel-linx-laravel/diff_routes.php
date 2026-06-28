<?php
$backup = file_get_contents("../carmel-linx-laravel_restoration_point_1_26june26_9am/routes/web.php");
$current = file_get_contents("routes/web.php");

// Find all Route::... in backup that are NOT in current
preg_match_all("/Route::.*?;\r?\n/m", $backup, $backupRoutes);
preg_match_all("/Route::.*?;\r?\n/m", $current, $currentRoutes);

$bRoutes = array_map('trim', $backupRoutes[0]);
$cRoutes = array_map('trim', $currentRoutes[0]);

$missing = array_diff($bRoutes, $cRoutes);
foreach($missing as $m) {
    if (strpos($m, 'CourseFileController') !== false || strpos($m, 'course-files') !== false || strpos($m, 'Remedial') !== false) {
        echo $m . "\n";
    }
}
