<?php

/**
 * CampusLynk Migration Control Plane — Resumption & Role Dashboards Addition Directive
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$stateFile = $baseDir . '/docs/migration/STATE.json';
$featureMatrixFile = $baseDir . '/docs/migration/FEATURE_MATRIX.md';
$designSystemFile = $baseDir . '/docs/migration/DESIGN_SYSTEM.md';

$stateJson = file_get_contents($stateFile);
$state = json_decode($stateJson, true);
$currentCommit = trim(shell_exec('git -C ' . escapeshellarg($baseDir) . ' rev-parse HEAD') ?? '8186c67f');

$authoritativeContext = <<<'CONTEXT'
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT & USER DIRECTIVE

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Current Commit: 8186c67f463c9d06a50204a8754374d07caeae9c
- Active Execution State: IDLE
- Completed Units (6 / 24):
  - M1.1: Database Schema Migrations and Eloquent Models Parity (commit 06f68cc6)
  - M2.1: R21 Major Project Backend Controller and Evaluation Endpoints (commit da44888c)
  - M2.2: R21 Major Project Frontend Workspace Layout & UI Modernization (commit 66b3310d)
  - M2.3: R21 Major Project Consolidated Print Report (commit c580607b)
  - M2.4: R21 Seminar Controller and Presentation Rubrics Backend (commit d239427b)
  - M2.5: R21 Seminar Workspace View and Print Summary (commit 8186c67f)
- Full Test Suite Status: 55 tests passed (416 assertions), 0 failures, 0 regressions.
- Strict Constraints: `app/Models/User.php` untouched; all Blade files strictly < 500 lines.

## USER ADDITION & ARCHITECTURAL MANDATE: ROLE DASHBOARDS MIGRATION
The user has issued an explicit addition to the migration path:
> "you may continue the work from where it stopped, however ialso have an addition to the current migration path whch is the dashboard migrations for each roles such as pricipal , hod , lecturer , trade instructor lab assistant , etc an also the other roles and dashboads that exist , we mus be also fixing the duplication error i the legacy editio with this new migration setup. so consider that while also continuing the current seup."

### Legacy Duplication Problem Analysis
- In the legacy edition, over 20 monolithic dashboard files exist with massive, redundant code:
  - `lecturer_dashboard.blade.php`: 464 KB
  - `hod_dashboard.blade.php`: 271 KB
  - `admin_control_desk.blade.php` / `principal_dashboard.blade.php`: 254 KB
  - `chairman_dashboard.blade.php`: 158 KB
  - `student_dashboard.blade.php`: 123 KB
  - `tutor_dashboard.blade.php`: 114 KB
  - Legacy views copy-pasted identical sidebar navigation, topbars, profile headers, attendance/leave modals, and raw inline CSS across every role.
- In CampusLynk, clean shared global architecture already exists:
  - `<x-layouts.faculty-shell>`: unified faculty/staff shell with `<x-layout.sidebar>` and `<x-layout.topbar>`.
  - `<x-layouts.dashboard-layout>`: mounts `<x-layouts.app-shell>` with breadcrumbs, heading/subheading, and action slots for admin/principal/chairman roles.
  - `<x-ui.*>` components (`<x-ui.card>`, `<x-ui.button>`, `<x-ui.modal>`, `<x-ui.table>`, `<x-ui.badge>`, `<x-ui.tabs>`, `<x-ui.icon>`).
  - Reference implementations: `trade_instructor_dashboard.blade.php` (271 lines) and `demonstrator_dashboard.blade.php`.

## SUPERVISOR DECISION REQUEST
As the sole Architect/Supervisor, please decide:
1. Roadmap Sequencing:
   - Should we incorporate a dedicated `role_dashboards` feature (covering Principal, HOD, Lecturer, Trade Instructor, Lab Assistant, Demonstrator, Tutor, Student, Parent) into `STATE.json` / `FEATURE_MATRIX.md` right now?
   - What should the immediate next task be:
     - Option A: Proceed with `M2.6` (R21 Drawing Hall Controller and Sheet Evaluation Backend) as the next unit in the active dependency graph, while authorizing the role dashboards addition to `STATE.json`.
     - Option B: Initiate the Role Dashboards feature analysis immediately.
2. Please issue the next formal `TASK_DISPATCH` (e.g. for `M2.6-ANALYZE-001` or Dashboard Analysis) conforming strictly to `RESPONSE_SCHEMA.json`.

Return decision: 'TASK_DISPATCH' with payload.task_dispatch and state_transition.
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'ARCHITECTURE_REVIEW',
    'M2.6',
    'NOT_STARTED',
    [
        'unit_title' => 'R21 Drawing Hall Controller & Role Dashboards Migration Path Addition',
        'objective' => 'Evaluate next unit in dependency graph (M2.6) following M2.5 completion, and evaluate user directive to add Role Dashboards migration (Principal, HOD, Lecturer, Trade Instructor, Lab Assistant, etc.) eliminating legacy duplication via <x-layouts.*> and <x-ui.*>. Issue next formal TASK_DISPATCH.',
        'explicit_request' => "Units M1.1 through M2.5 are COMPLETED and checkpointed (commit 8186c67f). User has requested adding role dashboards migration to eliminate legacy duplication. Please decide sequencing and issue formal TASK_DISPATCH conforming strictly to RESPONSE_SCHEMA.json.",
        'context' => [
            'dependencies' => ['M1.1'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ]
        ]
    ],
    $runId
);

echo "Dispatching Resumption & Role Dashboards Request to ChatGPT via CDP bridge...\n";
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
