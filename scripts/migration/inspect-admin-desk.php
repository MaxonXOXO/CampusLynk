<?php

$filePath = 'd:/CampusLynk/CampusLynk/carmel-linx-laravel/resources/views/admin_control_desk.blade.php';
$lines = file($filePath);
$total = count($lines);

echo "Total lines: $total\n";

$markers = [
    'panelDashboard' => 'id="panelDashboard"',
    'panelAll_timetables' => 'id="panelAll_timetables"',
    'panelDirectory' => 'id="panelDirectory"',
    'panelBackups' => 'id="panelBackups"',
    'panelAudit' => 'id="panelAudit"',
    'panelSettings' => 'id="panelSettings"',
    'panelProf_activities' => 'id="panelProf_activities"',
    'panelLeave_ledger' => 'id="panelLeave_ledger"',
    'panelSf_attendance' => 'id="panelSf_attendance"',
    'panelProfile' => 'id="panelProfile"',
    'modals' => '<!-- MODALS',
    'scripts' => '<script>'
];

foreach ($lines as $idx => $line) {
    foreach ($markers as $name => $needle) {
        if (stripos($line, $needle) !== false) {
            echo sprintf("%-25s: Line %d\n", $name, $idx + 1);
        }
    }
}
