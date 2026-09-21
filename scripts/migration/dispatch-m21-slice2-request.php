<?php

/**
 * CampusLynk Migration Control Plane — Request M2.1-IMPLEMENT-002 TASK_DISPATCH from ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$contextPrompt = <<<PROMPT
# SUPERVISOR DIRECTIVE REQUEST: M2.1-IMPLEMENT-002

Unit M2.1 Slice 1 (Schema & Models Parity) has been approved by the Supervisor.
Unit M2.1 is now in READY state.
Working tree is clean on branch 'migration-alpha' with Slice 1 artifacts.

Please issue the formal TASK_DISPATCH for the approved Slice 2:
- task_id: M2.1-IMPLEMENT-002
- unit_id: M2.1
- slice: Slice 2 - AttainmentService, R21VirtualClassroomMajorProjectController, Route Registration & Feature Tests
  - app/Services/AttainmentService.php (Port required service methods for ESE config, grade conversions, CO-PO attainment)
  - app/Http/Controllers/R21VirtualClassroomMajorProjectController.php (Port all 11 backend endpoints with modern validation and auth)
  - routes/web.php (Register the 11 /r21/classroom/project/{subjectId}/* routes under auth middleware)
  - tests/Feature/R21MajorProjectControllerTest.php (Dedicated feature tests for endpoint validation, auth, and attainment)

MANDATORY DIRECTIVE:
Every task and architectural decision must consider existing CampusLynk global shells (<x-layouts.workspace-layout>), global components (<x-ui.*>), and design language to prevent duplication.

Return your response with decision: "TASK_DISPATCH" and payload.task_dispatch conforming to RESPONSE_SCHEMA.json.
PROMPT;

$envelope = $bridge->createRequestEnvelope(
    'IMPLEMENTATION_REVIEW',
    'M2.1',
    'READY',
    [
        'unit_title' => 'R21 Major Project Controller, AttainmentService & Routes (M2.1-IMPLEMENT-002)',
        'objective' => 'Request formal TASK_DISPATCH for Slice 2 of M2.1: AttainmentService, R21VirtualClassroomMajorProjectController, route registration, and feature-level tests.',
        'explicit_request' => "Slice 1 is approved and M2.1 is in READY state. Please issue the formal TASK_DISPATCH for M2.1-IMPLEMENT-002 (Slice 2: AttainmentService, R21VirtualClassroomMajorProjectController, route registration in routes/web.php, and feature test tests/Feature/R21MajorProjectControllerTest.php). Every task must consider existing CampusLynk global shells/components/design language and avoid duplication. Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M1.1'],
            'allowed_files' => [
                'carmel-linx-laravel/app/Services/AttainmentService.php',
                'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php',
                'carmel-linx-laravel/routes/web.php',
                'carmel-linx-laravel/tests/Feature/R21MajorProjectControllerTest.php'
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.1-IMPLEMENT-002 TASK_DISPATCH request to ChatGPT via CDP bridge...\n";
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
