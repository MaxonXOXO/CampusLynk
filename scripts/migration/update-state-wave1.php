<?php

declare(strict_types=1);

$stateFile = 'd:/CampusLynk/CampusLynk/docs/migration/STATE.json';
$state = json_decode(file_get_contents($stateFile), true);

$now = gmdate('Y-m-d\TH:i:s\Z');

// 1. Update feature
$state['features']['role_dashboards']['state'] = 'COMPLETED';

// 2. Update units M9.1 - M9.4
$m9Units = ['M9.1', 'M9.2', 'M9.3', 'M9.4'];
foreach ($m9Units as $uId) {
    $state['units'][$uId]['state'] = 'COMPLETED';
    $state['units'][$uId]['completed_at'] = $now;
    $state['units'][$uId]['updated_at'] = $now;
}

// 3. Update wave W1
$state['waves']['W1']['state'] = 'VERIFIED';
$state['waves']['W1']['started_at'] = '2026-09-21T17:15:00Z';
$state['waves']['W1']['verification_summary'] = [
    'status' => 'PASS',
    'level_1' => '22 passed, 68 assertions across 4 unit test classes',
    'level_2' => '22 passed, 68 assertions in 2.09s',
    'level_3' => '92 passed, 577 assertions in 6.55s',
    'regressions' => 0
];

// 4. Update metrics
$state['metrics']['units_completed'] = 12; // 8 + 4
$state['metrics']['units_total'] = 28;
$state['metrics']['units_blocked'] = 7;

file_put_contents($stateFile, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
echo "[SUCCESS] STATE.json updated for Wave 1 completion!\n";
