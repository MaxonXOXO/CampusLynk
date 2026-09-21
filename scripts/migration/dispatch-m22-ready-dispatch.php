<?php

/**
 * CampusLynk Migration Control Plane — Request M2.2-IMPLEMENT-001 TASK_DISPATCH from ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$contextPrompt = <<<PROMPT
# SUPERVISOR DIRECTIVE REQUEST: M2.2-IMPLEMENT-001 (Slice 1)

Unit M2.2 is in READY state following PLAN_APPROVED.
Target working tree is on branch 'migration-alpha' at commit da44888c716a59a7e3f4381ce4297094d106ec40.

Please issue the formal TASK_DISPATCH for the approved first implementation slice:
- task_id: M2.2-IMPLEMENT-001
- unit_id: M2.2
- slice: Slice 1 - Master Shell & Modular Blade Partials Structure
  - carmel-linx-laravel/resources/views/r21_project/virtual_classroom_project.blade.php
  - carmel-linx-laravel/resources/views/r21_project/partials/stats-strip.blade.php
  - carmel-linx-laravel/resources/views/r21_project/partials/tab-register.blade.php
  - carmel-linx-laravel/resources/views/r21_project/partials/tab-cia.blade.php
  - carmel-linx-laravel/resources/views/r21_project/partials/tab-ese.blade.php
  - carmel-linx-laravel/resources/views/r21_project/partials/tab-reports.blade.php
  - carmel-linx-laravel/resources/views/r21_project/partials/tab-groups.blade.php
  - carmel-linx-laravel/resources/views/r21_project/partials/tab-attainment.blade.php
  - carmel-linx-laravel/resources/views/r21_project/partials/tab-rubrics.blade.php

## MANDATORY CONSTRAINTS:
1. Workspace must use `<x-layouts.workspace-layout>` with props and `<x-slot:headerActions>`.
2. All tables must use `<x-ui.table>`, cards `<x-ui.card>`, badges `<x-ui.badge>`, tabs `<x-ui.tabs>`, buttons `<x-ui.button>`.
3. No single Blade view may exceed 500 lines.
4. Zero duplication of UI components or custom CSS styles.
5. Modals and client-side JavaScript are deferred to Slice 2 (M2.2-IMPLEMENT-002).
6. Do NOT touch `app/Models/User.php`.

Return your response as a valid TASK_DISPATCH code block conforming to RESPONSE_SCHEMA.json.
PROMPT;

$envelope = $bridge->createRequestEnvelope(
    'IMPLEMENTATION_REVIEW',
    'M2.2',
    'READY',
    [
        'unit_title' => 'R21 Major Project Master Shell & Modular Blade Partials (M2.2-IMPLEMENT-001)',
        'objective' => 'Request formal TASK_DISPATCH for Slice 1 of M2.2 (Master Shell & Modular Blade Partials Structure).',
        'explicit_request' => "M2.2 plan is approved and unit state is READY. Please issue the formal TASK_DISPATCH for M2.2-IMPLEMENT-001 (Slice 1: Master Shell & Modular Blade Partials Structure). Every task must consider existing CampusLynk global shells (<x-layouts.workspace-layout>) and components (<x-ui.*>) to avoid duplication. Provide exact instructions, constraints, and allowed_files.",
        'context' => [
            'dependencies' => ['M2.1'],
            'allowed_files' => [
                'carmel-linx-laravel/resources/views/r21_project/virtual_classroom_project.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/stats-strip.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-register.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-cia.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-ese.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-reports.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-groups.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-attainment.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-rubrics.blade.php'
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.2-IMPLEMENT-001 TASK_DISPATCH request to ChatGPT via CDP bridge...\n";
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
