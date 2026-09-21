<?php

$state = json_decode(file_get_contents(__DIR__ . '/../../docs/migration/STATE.json'), true);

$totalUnits = count($state['units']);
$completedUnits = 0;
$inProgressUnits = 0;
$notStartedUnits = 0;

$unitList = [];
foreach ($state['units'] as $id => $u) {
    $st = $u['state'];
    if ($st === 'COMPLETED') $completedUnits++;
    elseif ($st === 'IN_PROGRESS' || $st === 'IMPLEMENTING' || $st === 'ANALYZING') $inProgressUnits++;
    else $notStartedUnits++;

    $unitList[] = [
        'id' => $id,
        'feature' => $u['feature'],
        'title' => $u['title'],
        'state' => $st
    ];
}

echo "Total Units: $totalUnits\n";
echo "Completed Units: $completedUnits\n";
echo "In Progress Units: $inProgressUnits\n";
echo "Not Started Units: $notStartedUnits\n";
echo "Unit Completion Rate: " . round(($completedUnits / $totalUnits) * 100, 1) . "%\n\n";

$features = $state['features'];
$totalFeatures = count($features);
$completedFeatures = 0;
$inProgressFeatures = 0;
$notStartedFeatures = 0;

foreach ($features as $fId => $f) {
    $st = $f['status'];
    if ($st === 'COMPLETED') $completedFeatures++;
    elseif ($st === 'IN_PROGRESS') $inProgressFeatures++;
    else $notStartedFeatures++;
}

echo "Total Features: $totalFeatures\n";
echo "Completed Features: $completedFeatures\n";
echo "In Progress Features: $inProgressFeatures\n";
echo "Not Started Features: $notStartedFeatures\n";
echo "Feature Completion Rate: " . round(($completedFeatures / $totalFeatures) * 100, 1) . "%\n\n";

echo "Units Breakdown:\n";
foreach ($unitList as $u) {
    echo "  - {$u['id']} [{$u['state']}]: {$u['title']} (Feature: {$u['feature']})\n";
}
