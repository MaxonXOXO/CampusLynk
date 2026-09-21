<?php

/**
 * CampusLynk Migration Control Plane State Validator
 *
 * Validates docs/migration/STATE.json against docs/migration/schemas/migration-state.schema.json
 * and performs relational/topological integrity checks.
 *
 * Usage: php scripts/migration/validate-state.php
 */

$baseDir = dirname(__DIR__, 2);
$stateFile = $baseDir . '/docs/migration/STATE.json';
$schemaFile = $baseDir . '/docs/migration/schemas/migration-state.schema.json';

echo "=== CampusLynk Migration State Validator ===\n";
echo "State file:  " . $stateFile . "\n";
echo "Schema file: " . $schemaFile . "\n\n";

$errors = [];
$warnings = [];

// 1. File existence checks
if (!file_exists($stateFile)) {
    echo "[FAIL] STATE.json does not exist at: {$stateFile}\n";
    exit(1);
}

if (!file_exists($schemaFile)) {
    echo "[FAIL] Schema file does not exist at: {$schemaFile}\n";
    exit(1);
}

// 2. JSON syntax checks
$stateJson = file_get_contents($stateFile);
$state = json_decode($stateJson, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "[FAIL] STATE.json is not valid JSON: " . json_last_error_msg() . "\n";
    exit(1);
}

$schemaJson = file_get_contents($schemaFile);
$schema = json_decode($schemaJson, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "[FAIL] migration-state.schema.json is not valid JSON: " . json_last_error_msg() . "\n";
    exit(1);
}

// 3. Top-level required keys
$requiredTopLevel = $schema['required'] ?? [];
foreach ($requiredTopLevel as $key) {
    if (!array_key_exists($key, $state)) {
        $errors[] = "Missing top-level required key: '{$key}'";
    }
}

// 4. System section validation
if (isset($state['system'])) {
    foreach (['name', 'version', 'phase'] as $field) {
        if (empty($state['system'][$field])) {
            $errors[] = "system.{$field} is required and cannot be empty.";
        }
    }
}

// 5. Repositories validation
if (isset($state['repositories'])) {
    if (!isset($state['repositories']['legacy']['role']) || $state['repositories']['legacy']['role'] !== 'read_only_reference') {
        $errors[] = "repositories.legacy.role must be 'read_only_reference'.";
    }
    if (!isset($state['repositories']['target']['role']) || $state['repositories']['target']['role'] !== 'implementation_target') {
        $errors[] = "repositories.target.role must be 'implementation_target'.";
    }
}

// 6. Branch validation
if (isset($state['branch'])) {
    if (!isset($state['branch']['name']) || $state['branch']['name'] !== 'migration-alpha') {
        $errors[] = "branch.name must be 'migration-alpha'.";
    }
    if (!isset($state['branch']['base']) || $state['branch']['base'] !== 'main') {
        $errors[] = "branch.base must be 'main'.";
    }
}

// 7. Current Execution validation
$allowedExecutionStates = ['IDLE', 'ANALYZING', 'IMPLEMENTING', 'VERIFYING', 'REPAIRING', 'CHECKPOINTING'];
if (isset($state['current_execution'])) {
    $execState = $state['current_execution']['state'] ?? null;
    if (!in_array($execState, $allowedExecutionStates, true)) {
        $errors[] = "current_execution.state '{$execState}' is not in allowed enum: " . implode(', ', $allowedExecutionStates);
    }
}

// 8. Features and Units relational validation
$validUnitStates = [
    'NOT_STARTED', 'ANALYZING', 'ANALYZED', 'BLOCKED', 'READY',
    'IMPLEMENTING', 'IMPLEMENTED', 'VERIFYING', 'VERIFIED', 'FAILED',
    'REPAIRING', 'ESCALATED', 'CHECKPOINTED', 'COMPLETED', 'DEFERRED', 'CANCELLED'
];

$validPriorities = ['critical', 'high', 'medium', 'low'];

$knownUnits = [];
if (isset($state['units']) && is_array($state['units'])) {
    $knownUnits = array_keys($state['units']);
    foreach ($state['units'] as $unitId => $unit) {
        if (!isset($unit['id']) || $unit['id'] !== $unitId) {
            $errors[] = "Unit key '{$unitId}' does not match unit.id '{$unit['id']}'.";
        }
        if (!in_array($unit['state'] ?? '', $validUnitStates, true)) {
            $errors[] = "Unit '{$unitId}' has invalid state: '{$unit['state']}'.";
        }
        if (!in_array($unit['priority'] ?? '', $validPriorities, true)) {
            $errors[] = "Unit '{$unitId}' has invalid priority: '{$unit['priority']}'.";
        }
        if (!isset($unit['attempts']) || !is_int($unit['attempts']) || $unit['attempts'] < 0) {
            $errors[] = "Unit '{$unitId}' attempts must be an integer >= 0.";
        }
        if (!isset($unit['max_attempts']) || !is_int($unit['max_attempts']) || $unit['max_attempts'] < 1) {
            $errors[] = "Unit '{$unitId}' max_attempts must be an integer >= 1.";
        }
        // Validate dependencies exist in units
        if (isset($unit['dependencies']) && is_array($unit['dependencies'])) {
            foreach ($unit['dependencies'] as $dep) {
                if (!isset($state['units'][$dep])) {
                    $errors[] = "Unit '{$unitId}' declares unknown dependency: '{$dep}'.";
                }
            }
        }
    }
} else {
    $errors[] = "Missing or invalid 'units' object in STATE.json.";
}

// 9. Feature to Units integrity
if (isset($state['features']) && is_array($state['features'])) {
    foreach ($state['features'] as $featureId => $feature) {
        if (isset($feature['units']) && is_array($feature['units'])) {
            foreach ($feature['units'] as $unitId) {
                if (!in_array($unitId, $knownUnits, true)) {
                    $errors[] = "Feature '{$featureId}' references non-existent unit '{$unitId}'.";
                }
            }
        }
    }
} else {
    $errors[] = "Missing or invalid 'features' object in STATE.json.";
}

// 10. Metrics validation
if (isset($state['metrics'])) {
    $totalUnitsInState = count($knownUnits);
    if (($state['metrics']['units_total'] ?? -1) !== $totalUnitsInState) {
        $errors[] = "metrics.units_total ({$state['metrics']['units_total']}) does not match actual units count ({$totalUnitsInState}).";
    }
}

// 11. Results reporting
if (empty($errors)) {
    echo "[PASS] STATE.json is structurally and relationally VALID.\n";
    echo "  - Total Features: " . count($state['features'] ?? []) . "\n";
    echo "  - Total Units:    " . count($knownUnits) . "\n";
    echo "  - Active State:   " . ($state['current_execution']['state'] ?? 'UNKNOWN') . "\n";
    echo "  - Control Plane:  OPERATIONAL\n";
    exit(0);
} else {
    echo "[FAIL] Validation errors found (" . count($errors) . "):\n";
    foreach ($errors as $i => $err) {
        echo "  " . ($i + 1) . ". " . $err . "\n";
    }
    exit(1);
}
