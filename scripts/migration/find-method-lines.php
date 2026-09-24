<?php
// Find all method definitions in a PHP controller
$files = [
    'd:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/ClassroomController.php',
    'd:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/AttendanceController.php',
    'd:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/SbteSubjectLogImportController.php',
    'd:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/PrincipalScheduledEventController.php',
    'd:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/VirtualClassroomPracticalController.php',
    'd:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/R26VirtualClassroomPracticalController.php',
    'd:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/AuthController.php',
    'd:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/StaffAttendanceMobileController.php',
];

$searchMethods = [
    'getEseMarks', 'getAttainmentSummary', 'printCourseFileA4', 'printAttainmentReport',
    'uploadAssignmentImage', 'checkSessionAttendance', 'deleteAttendanceLog',
    'getConsolidatedTutorAttendance', 'printTutorAttendanceReport', 'importPdf',
    'syncFromLessonPlan', 'getTodayCampusEvent', 'getAttendanceLog', 'saveStudentCiaSummary',
    'deleteLessonPlanRow', 'autoLoginViaToken', 'showFacePunch', 'saveFaceRegistration',
    'verifyAndPunch', 'showAttendanceReport'
];

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "MISSING: $file\n";
        continue;
    }
    $lines = file($file);
    $basename = basename($file);
    foreach ($lines as $i => $line) {
        foreach ($searchMethods as $m) {
            if (stripos($line, "function $m") !== false) {
                echo "$basename L" . ($i+1) . ": " . trim($line) . "\n";
            }
        }
    }
}
