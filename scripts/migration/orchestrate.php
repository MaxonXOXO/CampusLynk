<?php

/**
 * CampusLynk High-Velocity Migration Orchestrator CLI
 *
 * Usage:
 *   php scripts/migration/orchestrate.php report
 *   php scripts/migration/orchestrate.php form-wave
 *   php scripts/migration/orchestrate.php simulate
 *   php scripts/migration/orchestrate.php verify
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator/HighVelocityOrchestrator.php';

use CampusLynk\Migration\Orchestrator\HighVelocityOrchestrator;

$orchestrator = new HighVelocityOrchestrator();
$command = $argv[1] ?? 'report';

switch ($command) {
    case 'report':
        echo $orchestrator->generateReadinessReport() . "\n";
        break;

    case 'form-wave':
        if ($argc > 2) {
            $waveId = $argv[2];
            $waveName = $argv[3] ?? "Wave {$waveId}";
            $unitIds = array_slice($argv, 4);
            $wave = $orchestrator->formExplicitWave($waveId, $waveName, $unitIds);
        } else {
            $wave = $orchestrator->formNextWave(4);
        }
        if ($wave) {
            echo "[SUCCESS] Wave Formed:\n";
            echo json_encode($wave, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
        } else {
            echo "[INFO] No ready units available to form a new wave.\n";
        }
        break;

    case 'simulate':
        echo "=== Running Orchestrator High-Velocity Simulations ===\n\n";
        $results = $orchestrator->runSimulations();
        $allPassed = true;
        foreach ($results as $key => $res) {
            $status = $res['passed'] ? '[PASS]' : '[FAIL]';
            echo "{$status} {$res['name']}\n";
            echo "       Details: " . json_encode($res['details'], JSON_UNESCAPED_SLASHES) . "\n\n";
            if (!$res['passed']) $allPassed = false;
        }
        if ($allPassed) {
            echo "All 4 High-Velocity Scenarios PASSED cleanly!\n";
            exit(0);
        } else {
            echo "Some simulations FAILED.\n";
            exit(1);
        }
        break;

    case 'verify':
        echo "=== Verifying Orchestrator & State Schema ===\n";
        passthru('php ' . escapeshellarg(__DIR__ . '/validate-state.php'), $exitCode);
        if ($exitCode === 0) {
            echo "\n=== Running Simulations ===\n";
            passthru('php ' . escapeshellarg(__FILE__) . ' simulate', $simExitCode);
            exit($simExitCode);
        }
        exit($exitCode);
        break;

    default:
        echo "Unknown command: {$command}\n";
        echo "Usage: php scripts/migration/orchestrate.php [report|form-wave|simulate|verify]\n";
        exit(1);
}
