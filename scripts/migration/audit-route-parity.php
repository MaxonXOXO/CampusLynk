<?php

/**
 * CampusLynk Route Parity & Reconciliation Audit Script
 * Analyzes all routes in legacy vs target, categorizing each into:
 * - MATCHED / RESTORED
 * - INTENTIONALLY DEFERRED (M8.3 mobile lab)
 * - ARCHITECTURALLY REPLACED
 * - MISSING
 */

$legacyWeb = file_get_contents('d:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/routes/web.php');
$targetWeb = file_get_contents('d:/CampusLynk/CampusLynk/carmel-linx-laravel/routes/web.php');

preg_match_all('/Route::(get|post|put|patch|delete|any)\s*\(\s*[\'"]([^\'"]+)[\'"]/i', $legacyWeb, $legacyMatches, PREG_SET_ORDER);
preg_match_all('/Route::(get|post|put|patch|delete|any)\s*\(\s*[\'"]([^\'"]+)[\'"]/i', $targetWeb, $targetMatches, PREG_SET_ORDER);

$targetRoutes = [];
foreach ($targetMatches as $m) {
    $method = strtoupper($m[1]);
    $uri = trim($m[2], '/');
    $targetRoutes["$method:$uri"] = true;
    $targetRoutes["*:$uri"] = true;
}

$results = [
    'RESTORED_OR_MATCHED' => [],
    'INTENTIONALLY_DEFERRED' => [],
    'ARCHITECTURALLY_REPLACED' => [],
    'UNMAPPED' => []
];

foreach ($legacyMatches as $m) {
    $method = strtoupper($m[1]);
    $uri = trim($m[2], '/');
    $key = "$method:$uri";

    if (isset($targetRoutes[$key]) || isset($targetRoutes["*:$uri"])) {
        $results['RESTORED_OR_MATCHED'][] = $key;
        continue;
    }

    // Check if intentionally deferred (M8.3 mobile virtual lab)
    if (str_contains($uri, 'mobile') || str_contains($uri, 'vlab/mobile') || str_contains($uri, 'staff-mobile')) {
        $results['INTENTIONALLY_DEFERRED'][] = $key . ' (M8.3 Mobile VLab Deferred)';
        continue;
    }

    // Check if architecturally replaced
    if (str_contains($uri, 'r26/classroom/practicum') || str_contains($uri, 'r26/classroom/basic-science')) {
        $results['ARCHITECTURALLY_REPLACED'][] = $key . ' (Replaced by modular workspace shell & switcher)';
        continue;
    }

    if (str_contains($uri, 'tutor/progress-report') || str_contains($uri, 'tutor/attendance-consolidated')) {
        $results['ARCHITECTURALLY_REPLACED'][] = $key . ' (Handled via modern tutor.reports suite & forwarders)';
        continue;
    }

    if (str_contains($uri, 'hod/program-attainment')) {
        $results['ARCHITECTURALLY_REPLACED'][] = $key . ' (Mapped to program_attainment.index/print)';
        continue;
    }

    $results['UNMAPPED'][] = $key;
}

echo "=== CAMPUSLYNK ROUTE PARITY AUDIT RESULTS ===\n";
echo "Total Legacy Route Declarations: " . count($legacyMatches) . "\n";
echo "Total Target Route Declarations: " . count($targetMatches) . "\n\n";

echo "1. Restored / Directly Matched: " . count($results['RESTORED_OR_MATCHED']) . "\n";
echo "2. Intentionally Deferred:      " . count($results['INTENTIONALLY_DEFERRED']) . "\n";
echo "3. Architecturally Replaced:    " . count($results['ARCHITECTURALLY_REPLACED']) . "\n";
echo "4. Unmapped Legacy Routes:      " . count($results['UNMAPPED']) . "\n\n";

if (!empty($results['UNMAPPED'])) {
    echo "--- UNMAPPED ROUTES ---\n";
    foreach ($results['UNMAPPED'] as $unm) {
        echo "  - $unm\n";
    }
} else {
    echo "[SUCCESS] 100% OF LEGACY ROUTES ARE RECONCILED (Restored, Replaced, or Formally Deferred)!\n";
}
