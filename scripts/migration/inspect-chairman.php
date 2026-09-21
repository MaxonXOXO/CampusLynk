<?php

$filePath = 'd:/CampusLynk/CampusLynk/carmel-linx-laravel/resources/views/chairman_dashboard.blade.php';
$lines = file($filePath);
$total = count($lines);

echo "Chairman total lines: $total\n";

$markers = [
    'panelDashboard' => 'id="panelDashboard"',
    'panelDirectory' => 'id="panelDirectory"',
    'panelAudit' => 'id="panelAudit"',
    'modals' => '<!-- MODAL',
    'scripts' => '<script>'
];

foreach ($lines as $idx => $line) {
    foreach ($markers as $name => $needle) {
        if (stripos($line, $needle) !== false) {
            echo sprintf("%-25s: Line %d\n", $name, $idx + 1);
        }
    }
}
