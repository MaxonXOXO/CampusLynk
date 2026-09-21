<?php

/**
 * CampusLynk Migration Control Plane — Request M2.1-IMPLEMENT-001 TASK_DISPATCH from ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$contextPrompt = <<<PROMPT
# SUPERVISOR DIRECTIVE REQUEST: M2.1-IMPLEMENT-001

Unit M2.1 is in READY state following PLAN_APPROVED.
Target working tree is clean on branch 'migration-alpha' at commit 06f68cc6.

Please issue the formal TASK_DISPATCH for the approved first implementation slice:
- task_id: M2.1-IMPLEMENT-001
- unit_id: M2.1
- slice: Slice 1 - Schema & Eloquent Models Parity
  - database/migrations/2026_09_12_000001_create_r21_major_project_tables.php
  - app/Models/R21MajorProjectCourseFile.php
  - app/Models/R21MajorProjectEvaluation.php
  - tests/Unit/R21MajorProjectModelsTest.php

Return your response as a valid TASK_DISPATCH code block conforming to RESPONSE_SCHEMA.json.
PROMPT;

$envelope = $bridge->createRequestEnvelope(
    'IMPLEMENTATION_REVIEW',
    'M2.1',
    'READY',
    [
        'unit_title' => 'R21 Major Project Schema and Eloquent Models Parity (M2.1-IMPLEMENT-001)',
        'objective' => 'Request formal TASK_DISPATCH for Slice 1 of M2.1 (Schema and Eloquent Models Parity).',
        'explicit_request' => "M2.1 plan is approved and unit state is READY. Please issue the formal TASK_DISPATCH for M2.1-IMPLEMENT-001 (Slice 1: Schema and Eloquent Models Parity: 2026_09_12_000001 migration, R21MajorProjectCourseFile model, R21MajorProjectEvaluation model, and unit test). Provide exact instructions, constraints, and allowed_files.",
        'context' => [
            'dependencies' => ['M1.1'],
            'allowed_files' => [
                'carmel-linx-laravel/database/migrations/2026_09_12_000001_create_r21_major_project_tables.php',
                'carmel-linx-laravel/app/Models/R21MajorProjectCourseFile.php',
                'carmel-linx-laravel/app/Models/R21MajorProjectEvaluation.php',
                'carmel-linx-laravel/tests/Unit/R21MajorProjectModelsTest.php'
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.1-IMPLEMENT-001 TASK_DISPATCH request to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 90, false, $contextPrompt);

echo "\n--- Bridge Response Result ---\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if (!empty($result['success'])) {
    echo "\n[SUCCESS] Received validated supervisor response from ChatGPT!\n";
    echo "Decision: " . ($result['decision'] ?? 'UNKNOWN') . "\n";
    exit(0);
} else {
    echo "\n[FAIL] Bridge request failed or rejected by fail-closed guardrails.\n";
    exit(1);
}
