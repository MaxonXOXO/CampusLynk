<?php

/**
 * CampusLynk Migration Control Plane — Query ChatGPT for M2.5 Task Dispatch Directive
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$currentCommit = trim(shell_exec('git -C ' . escapeshellarg($baseDir) . ' rev-parse HEAD') ?? 'd239427b');

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: {$currentCommit}
- Active Execution State: IDLE
- Completed Units (5/24):
  - M1.1: Database Schema Migrations and Eloquent Models Parity (commit 06f68cc6)
  - M2.1: R21 Major Project Backend Controller and Evaluation Endpoints (commit da44888c)
  - M2.2: R21 Major Project Frontend Workspace Layout & UI Modernization (commit 66b3310d)
  - M2.3: R21 Major Project Consolidated Print Report (commit c580607b)
  - M2.4: R21 Seminar Controller and Presentation Rubrics Backend (commit {$currentCommit})
- Full Test Suite Status: 47 tests passed (350 assertions), 0 failures, 0 regressions in 3.69s.
- Critical Safety Boundary: `app/Models/User.php` untouched. Zero DB schema changes.

## NEXT AVAILABLE UNIT IN DEPENDENCY GRAPH: M2.5
- **Unit ID:** M2.5
- **Feature:** `r21_seminar` (R21 Seminar Virtual Classroom)
- **Title:** R21 Seminar Workspace View and Print Summary
- **Dependencies:** `[M2.4]` (SATISFIED by commit {$currentCommit})
- **Priority:** Critical

## MANDATORY SUPERVISOR DIRECTIVE (GLOBAL SHELLS, COMPONENTS & ANTI-DUPLICATION)
Every migration task and decision MUST strictly consider existing CampusLynk global shells and components:
1. **Master Shells**:
   - The virtual classroom view MUST mount inside `<x-layouts.workspace-layout>` (`resources/views/components/layouts/workspace-layout.blade.php`).
   - The print view MUST mount inside `<x-layouts.report-layout>` (`resources/views/components/layouts/report-layout.blade.php`).
2. **Global UI Components**:
   - All interactive controls, cards, badges, tabs, modals, tables, and form inputs MUST reuse `<x-ui.*>` components (`<x-ui.button>`, `<x-ui.card>`, `<x-ui.modal>`, `<x-ui.table>`, `<x-ui.badge>`, `<x-ui.tabs>`, `<x-ui.input>`, `<x-ui.select>`, `<x-ui.icon>`).
3. **Anti-Duplication**:
   - NEVER create ad-hoc button styles, custom modal dialogs, or raw table markup when global components exist.
   - Eliminating duplication is the primary goal along with UI overhauls.
4. **Sub-500-Line Modular Decomposition**:
   - The monolithic legacy views (`virtual_classroom_seminar.blade.php` at 2,171 lines and `seminar_report_print.blade.php` at 680 lines) MUST be decomposed into modular Blade partials (< 500 lines each) under `resources/views/r21_seminar/partials/`.

Please evaluate Unit M2.5 and issue the initial read-only analysis `TASK_DISPATCH` (`M2.5-ANALYZE-001`).
Return decision: 'TASK_DISPATCH' with payload.task_dispatch.
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'ARCHITECTURE_REVIEW',
    'M2.5',
    'NOT_STARTED',
    [
        'unit_title' => 'R21 Seminar Workspace View and Print Summary (M2.5)',
        'objective' => 'Evaluate next unit in dependency graph (M2.5) following M2.4 completion and issue initial read-only analysis TASK_DISPATCH (M2.5-ANALYZE-001) with strict global shell (<x-layouts.workspace-layout>, <x-layouts.report-layout>), global component (<x-ui.*>), and sub-500-line decomposition constraints.',
        'explicit_request' => "Unit M2.4 is COMPLETED and checkpointed (commit {$currentCommit}). All dependencies for M2.5 are satisfied. Please issue the initial analysis TASK_DISPATCH for M2.5 (M2.5-ANALYZE-001). Every task must consider existing CampusLynk global shells and components to avoid duplication. Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M2.4'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.5 Task Dispatch Request to ChatGPT via CDP bridge...\n";
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
