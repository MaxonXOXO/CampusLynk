<?php

/**
 * CampusLynk Migration Control Plane — Query ChatGPT for M2.7 Task Dispatch
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$stateFile = $baseDir . '/docs/migration/STATE.json';
$stateJson = file_get_contents($stateFile);
$state = json_decode($stateJson, true);
$currentCommit = trim(shell_exec('git -C ' . escapeshellarg($baseDir) . ' rev-parse HEAD') ?? '504c30f1');

$authoritativeContext = <<<'CONTEXT'
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Current Commit: 504c30f1
- Active Execution State: IDLE
- Completed Units (7 / 28):
  - M1.1: Database Schema Migrations and Eloquent Models Parity (commit 06f68cc6)
  - M2.1: R21 Major Project Backend Controller and Evaluation Endpoints (commit da44888c)
  - M2.2: R21 Major Project Frontend Workspace Layout & UI Modernization (commit 66b3310d)
  - M2.3: R21 Major Project Consolidated Print Report (commit c580607b)
  - M2.4: R21 Seminar Controller and Presentation Rubrics Backend (commit d239427b)
  - M2.5: R21 Seminar Workspace View and Print Summary (commit 8186c67f)
  - M2.6: R21 Drawing Hall Controller and Sheet Evaluation Backend (commit 1bd25cef)
- Full Test Suite Status: 64 tests passed (473 assertions), 0 failures, 0 regressions in 5.05s.
- Control Plane Checkpoint: Committed commit 504c30f1 checkpointing M2.6 and formally registering the `role_dashboards` feature (Units M9.1–M9.4) in STATE.json and FEATURE_MATRIX.md.
- Critical Files: `app/Models/User.php` completely untouched.

## NEXT AVAILABLE UNIT IN DEPENDENCY GRAPH
- **M2.7: R21 Drawing Hall Workspace View and 4 Print Templates**
  - Dependencies: `[M2.6]` (SATISFIED).
  - Priority: Critical.
  - Strict UI & Design Constraints:
    1. Virtual Drawing Hall Workspace MUST mount inside `<x-layouts.workspace-layout>`.
    2. All 4 statutory print templates (Formative Sheet Register, Summative Series Test Register, Consolidated CIA Marksheet, Lesson Plan) MUST mount inside `<x-layouts.report-layout>`.
    3. UI elements MUST reuse `<x-ui.*>` components (`<x-ui.card>`, `<x-ui.button>`, `<x-ui.modal>`, `<x-ui.table>`, `<x-ui.badge>`, `<x-ui.tabs>`, `<x-ui.icon>`).
    4. Strict sub-500-line decomposition rule across all Blade partials.

Please evaluate M2.7 and issue the initial formal TASK_DISPATCH for M2.7 read-only analysis (e.g. M2.7-ANALYZE-001).
Return decision: 'TASK_DISPATCH' with payload.task_dispatch and state_transition.
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'ARCHITECTURE_REVIEW',
    'M2.7',
    'NOT_STARTED',
    [
        'unit_title' => 'R21 Drawing Hall Workspace View and 4 Print Templates (M2.7)',
        'objective' => 'Evaluate next unit in dependency graph (M2.7) following M2.6 checkpoint completion and issue initial TASK_DISPATCH with strict design-system (<x-layouts.workspace-layout>, <x-layouts.report-layout>) and sub-500-line decomposition constraints.',
        'explicit_request' => "Unit M2.6 is COMPLETED and checkpointed (commit 1bd25cef, control-plane commit 504c30f1). Role dashboards feature (M9.1-M9.4) is registered. Please issue the formal TASK_DISPATCH for M2.7 (read-only analysis).",
        'context' => [
            'dependencies' => ['M2.6'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.7 Task Dispatch Request to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 90, false, $authoritativeContext);

echo "\n--- Bridge Response Result ---\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if (!empty($result['success'])) {
    echo "\n[SUCCESS] Received validated supervisor directive from ChatGPT!\n";
    echo "Decision: " . ($result['decision'] ?? 'UNKNOWN') . "\n";
    exit(0);
} else {
    echo "\n[FAIL] Bridge request failed or rejected by fail-closed guardrails.\n";
    exit(1);
}
