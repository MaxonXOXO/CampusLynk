<?php

$legacyWeb = file('d:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/routes/web.php');
$unmapped = [
    'virtual-lab/user-manual',
    'virtual-lab/manual',
    'virtual-lab/user-manual/pdf',
    'virtual-lab/pdf',
    'api/auth/auto-login',
    'api/student/materials',
    'lesson-plans',
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
    'attendance/report/print',
    'campus-event/today',
    'face-punch',
    'register-face',
    'verify-and-punch',
    'attendance-report'
];

foreach ($legacyWeb as $idx => $line) {
    foreach ($unmapped as $u) {
        if (stripos($line, $u) !== false) {
            echo ($idx + 1) . ': ' . trim($line) . PHP_EOL;
        }
    }
}
