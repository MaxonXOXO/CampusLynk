<?php

$methodsToCheck = [
    'App\Http\Controllers\AuthController' => ['autoLoginViaToken'],
    'App\Http\Controllers\VirtualLearningMaterialController' => ['markAlertAsRead'],
    'App\Http\Controllers\R26VirtualClassroomPracticalController' => ['deleteLessonPlanRow'],
    'App\Http\Controllers\ClassroomController' => [
        'uploadAssignmentImage',
        'printAttainmentReport',
        'printCourseFileA4',
        'getEseMarks',
        'getAttainmentSummary'
    ],
    'App\Http\Controllers\VirtualClassroomPracticalController' => [
        'getAttendanceLog',
        'saveStudentCiaSummary'
    ],
    'App\Http\Controllers\AttendanceController' => [
        'checkSessionAttendance',
        'deleteAttendanceLog',
        'getConsolidatedTutorAttendance',
        'printTutorAttendanceReport'
    ],
    'App\Http\Controllers\SbteSubjectLogImportController' => [
        'importPdf',
        'syncFromLessonPlan'
    ],
    'App\Http\Controllers\PrincipalScheduledEventController' => [
        'getTodayCampusEvent'
    ]
];

$baseDir = 'd:/CampusLynk/CampusLynk/carmel-linx-laravel/app/Http/Controllers/';

foreach ($methodsToCheck as $class => $methods) {
    $parts = explode('\\', $class);
    $className = end($parts);
    $filePath = $baseDir . $className . '.php';

    echo "Class: $className (" . (file_exists($filePath) ? "EXISTS" : "MISSING") . ")\n";
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        foreach ($methods as $m) {
            $hasMethod = preg_match('/function\s+' . $m . '\s*\(/i', $content);
            echo "  - $m: " . ($hasMethod ? "FOUND" : "NOT FOUND") . "\n";
        }
    }
}
