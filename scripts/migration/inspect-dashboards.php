<?php

function inspectFile($path, $name, $markers) {
    if (!file_exists($path)) {
        echo "$name: Not found\n";
        return;
    }
    $lines = file($path);
    echo "=== $name (Total: " . count($lines) . " lines) ===\n";
    foreach ($lines as $idx => $line) {
        foreach ($markers as $mKey => $needle) {
            if (stripos($line, $needle) !== false) {
                echo sprintf("  %-25s: Line %d\n", $mKey, $idx + 1);
            }
        }
    }
    echo "\n";
}

$base = 'd:/CampusLynk/CampusLynk/carmel-linx-laravel/resources/views/';

inspectFile($base . 'hod_dashboard.blade.php', 'HOD Dashboard', [
    'panelBatches' => 'id="panelBatches"',
    'panelDirectory' => 'id="panelDirectory"',
    'panelSubjects' => 'id="panelSubjects"',
    'panelAudit' => 'id="panelAudit"',
    'panelLeave_ledger' => 'id="panelLeave_ledger"',
    'panelProf_activities' => 'id="panelProf_activities"',
    'panelProfile' => 'id="panelProfile"',
    'panelReport_centre' => 'id="panelReport_centre"',
    'scripts' => '<script>'
]);

inspectFile($base . 'lecturer_dashboard.blade.php', 'Lecturer Dashboard', [
    'panelDashboard' => 'id="panelDashboard"',
    'panelClassroom' => 'id="panelClassroom"',
    'panelSecurity' => 'id="panelSecurity"',
    'panelMobileSeminar' => 'id="panelMobileSeminar"',
    'scripts' => '<script>'
]);

inspectFile($base . 'tutor_dashboard.blade.php', 'Tutor Dashboard', [
    'panelRoster' => 'id="panelRoster"',
    'panelRollNumbers' => 'id="panelRollNumbers"',
    'panelAudit' => 'id="panelAudit"',
    'panelProfile' => 'id="panelProfile"',
    'panelMentoring' => 'id="panelMentoring"',
    'panelActivity' => 'id="panelActivity"',
    'panelLeaveApproval' => 'id="panelLeaveApproval"',
    'scripts' => '<script>'
]);

inspectFile($base . 'student_dashboard.blade.php', 'Student Dashboard', [
    'panelExams' => 'id="panelExams"',
    'panelMarks' => 'id="panelMarks"',
    'panelProfile' => 'id="panelProfile"',
    'panelMentoring' => 'id="panelMentoring"',
    'panelActivity' => 'id="panelActivity"',
    'panelSeminar' => 'id="panelSeminar"',
    'panelAttendance' => 'id="panelAttendance"',
    'panelMock_test' => 'id="panelMock_test"',
    'scripts' => '<script>'
]);
