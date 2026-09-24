<?php

$targetWeb = file_get_contents('d:/CampusLynk/CampusLynk/carmel-linx-laravel/routes/web.php');

$searchTerms = [
    'upload-assignment-image',
    'attainment-report',
    'course-file/print',
    'ese-marks',
    'attainment-summary',
    'practical/attendance-log',
    'practical/cia-summary',
    'session-check',
    'delete-log',
    'import-sbte-pdf',
    'sync-from-lesson-plan',
    'attendance/consolidated',
    'tutor/attendance',
    'campus-event',
    'deleteLessonPlanRow',
    'markAlertAsRead'
];

foreach ($searchTerms as $term) {
    echo "=== TERM: $term ===\n";
    $lines = explode("\n", $targetWeb);
    foreach ($lines as $num => $line) {
        if (stripos($line, $term) !== false) {
            echo "Line " . ($num + 1) . ": " . trim($line) . "\n";
        }
    }
}
