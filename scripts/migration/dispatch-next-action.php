<?php

/**
 * CampusLynk Migration Control Plane — Query ChatGPT for Next Action Directive
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$stateFile = $baseDir . '/docs/migration/STATE.json';
$featureMatrixFile = $baseDir . '/docs/migration/FEATURE_MATRIX.md';
$designSystemFile = $baseDir . '/docs/migration/DESIGN_SYSTEM.md';
$unitSpecFile = $baseDir . '/docs/migration/MIGRATION_UNIT_SPEC.md';

$stateJson = file_get_contents($stateFile);
$featureMatrixMd = file_get_contents($featureMatrixFile);
$designSystemMd = file_exists($designSystemFile) ? file_get_contents($designSystemFile) : '';
$unitSpecMd = file_exists($unitSpecFile) ? file_get_contents($unitSpecFile) : '';

$state = json_decode($stateJson, true);
$currentCommit = trim(shell_exec('git -C ' . escapeshellarg($baseDir) . ' rev-parse HEAD') ?? '66b3310d');

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: {$state['branch']['name']}
- Base: {$state['branch']['base']}
- Current Commit: {$currentCommit}
- Active Execution State: IDLE
- Completed Units (3/24):
  - M1.1: Database Schema Migrations and Eloquent Models Parity (commit 06f68cc6)
  - M2.1: R21 Major Project Backend Controller and Evaluation Endpoints (commit da44888c)
  - M2.2: R21 Major Project Frontend Workspace Layout & UI Modernization (commit 66b3310d)
- Full Test Suite Status: 29 tests passed (214 assertions), 0 failures, 0 regressions in 2.62s.
- Critical Files: `app/Models/User.php` untouched.

## NEXT AVAILABLE UNITS IN DEPENDENCY GRAPH
1. **M2.3: R21 Major Project Consolidated Print Report**
   - Dependencies: `[M2.2]` (SATISFIED).
   - Priority: Critical.
   - Design System Requirement: MUST mount inside `<x-layouts.report-layout>` (`resources/views/components/layouts/report-layout.blade.php`) for official institutional A4 print formatting.
2. **M2.4: R21 Seminar Controller and Presentation Rubrics Backend**
   - Dependencies: `[M1.1]` (SATISFIED).
   - Priority: Critical.

## MANDATORY SUPERVISOR DIRECTIVE
Every migration decision and task dispatch MUST strictly consider existing CampusLynk global shells and components:
- Master Shells: `<x-layouts.workspace-layout>` for virtual classrooms, `<x-layouts.report-layout>` for print reports, `<x-layouts.app-shell>` for dashboards.
- Global Components: `<x-ui.*>` (`button`, `card`, `modal`, `table`, `badge`, `tabs`, `input`, `select`, `icon`).
- Zero Duplication: Eliminating component and UI duplication is the primary goal along with UI overhauls.

Please evaluate the dependency graph and issue the next formal TASK_DISPATCH (e.g. for M2.3 read-only analysis or implementation).
Return decision: 'TASK_DISPATCH' with payload.task_dispatch.
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'ARCHITECTURE_REVIEW',
    'M2.3',
    'NOT_STARTED',
    [
        'unit_title' => 'R21 Major Project Consolidated Print Report (M2.3)',
        'objective' => 'Evaluate next unit in dependency graph (M2.3) following M2.2 checkpoint completion and issue initial TASK_DISPATCH with strict design-system (<x-layouts.report-layout>) and anti-duplication constraints.',
        'explicit_request' => "Units M1.1, M2.1, and M2.2 are COMPLETED and checkpointed (current commit: 66b3310d). All dependencies for M2.3 are satisfied. Please evaluate M2.3 and issue the formal TASK_DISPATCH for M2.3. Every task must consider existing CampusLynk global shells (<x-layouts.report-layout>) and components (<x-ui.*>) to avoid duplication. Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M2.2'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ]
        ]
    ],
    $runId
);

echo "Dispatching Next Action / M2.3 Task Dispatch Request to ChatGPT via CDP bridge...\n";
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
