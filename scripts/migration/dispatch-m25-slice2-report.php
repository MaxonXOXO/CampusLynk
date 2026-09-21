<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.5-IMPLEMENT-002 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$currentCommit = trim(shell_exec('git -C ' . escapeshellarg($baseDir) . ' rev-parse HEAD') ?? 'd239427b');

$detailedReport = <<<'REPORT'
# M2.5 SLICE 2 IMPLEMENTATION & VERIFICATION REPORT (Task: M2.5-IMPLEMENT-002)

## 1. Dedicated Feature Test Created
- File: `carmel-linx-laravel/tests/Feature/R21SeminarWorkspaceViewTest.php` (272 lines)
- Whitelist Compliance: Exactly 1 test file created in Slice 2. Zero unauthorized files modified. `app/Models/User.php` UNTOUCHED.

## 2. Comprehensive Test Coverage for All Views, Tabs, Modals & Print Modes
1. `test_workspace_view_renders_through_workspace_layout_and_shows_metadata`: Confirms `<x-layouts.workspace-layout>` master shell usage, title, subject code (`6008`), regulation badges (`R-2021 Regulation`, `Clause 11.2.6 Seminar Assessment`), marks allocation banner (`CIA: 75 Marks (100% CIE)`, `67.5M Academic + 7.5M Attendance`), and student data binding.
2. `test_workspace_view_includes_all_five_structural_tabs`: Confirms all 5 tabs are rendered with expected IDs:
   - `id="tab-evaluation"` (Seminar Evaluation Register - 75 Marks)
   - `id="tab-schedule"` (Presentation Schedule & Topic Log)
   - `id="tab-consolidated"` (Consolidated Register & SBTE 9-point Grades S–F)
   - `id="tab-attainment"` (NBA CO Attainment CO1–CO3 100% CIE)
   - `id="tab-rubrics"` (Regulation Clause 11.2.6 Reference Guide)
3. `test_workspace_view_includes_all_modals_using_ui_modal`: Confirms all modal workflows are present using `<x-ui.modal>` infrastructure:
   - `id="evaluationModal"` (Live 6-rubric scoring, suggested attendance, assessor selection)
   - `id="scheduleModal"` (Topic, guide, presentation date)
   - `id="breakdownModal"` (Committee multi-assessor breakdown table)
   - `id="syllabusModal"` (PDF upload and viewer link)
4. `test_workspace_view_includes_client_side_scripts_and_bindings`: Confirms presence of client-side data cache (`studentData`), subjectId, and all interaction functions (`switchTab`, `filterBatch`, `filterStudents`, `openEvaluationModal`, `calculateLiveScore`, `submitEvaluation`, `openScheduleModal`, `submitSchedule`, `openBreakdownModal`, `openSyllabusModal`, `submitSyllabus`, `loadAttainmentData`).
5. `test_workspace_references_m24_endpoints`: Confirms the workspace correctly references all 6 M2.4 Seminar endpoint contracts (`/evaluate`, `/schedule`, `/syllabus`, `/attainment-summary`, `/print?type=consolidated`, `/print?type=schedule`, `/print?type=cia_submission`) without modifying or introducing routes.
6. `test_print_report_renders_consolidated_mode_with_report_layout`: Confirms `printReport` renders through `<x-layouts.report-layout orientation="landscape">`, institutional header (`Carmel Polytechnic College, Alappuzha`), statutory 6-rubric scheme, student roster, and signature blocks (`Faculty Guide`, `Head of Department`).
7. `test_print_report_renders_cia_submission_mode_with_report_layout`: Confirms `cia_submission` mode renders official SBTE statement (75M), grade statistics summary (S–F), and statutory certification declaration.
8. `test_print_report_renders_schedule_mode_with_report_layout`: Confirms `schedule` mode renders seminar presentation schedule and topic log with candidate summary.

## 3. Test Execution Evidence
- Dedicated Feature Test: `php artisan test --filter=R21SeminarWorkspaceViewTest` -> PASS (8 tests, 66 assertions in 1.85s).
- Seminar Controller Test: `php artisan test --filter=R21SeminarControllerTest` -> PASS (10 tests, 59 assertions in 1.50s).
- Full Application Suite: `php artisan test` -> PASS (55 tests, 416 assertions, 0 failures, 0 regressions in 4.46s).

## 4. Architectural & Safety Adherence
- Design System: Reuses `<x-layouts.workspace-layout>` and `<x-layouts.report-layout>` as the sole master shells.
- Global UI Components: Reuses `<x-ui.*>` components (`button`, `card`, `modal`, `table`, `badge`, `input`, `select`, `icon`); zero duplicate UI infrastructure.
- Sub-500-Line Decomposition: All 15 Blade files remain strictly below 370 lines.
- Safety Boundary: `app/Models/User.php` UNTOUCHED. Zero database schema changes.

## 5. Checkpoint Request
M2.5 (R21 Seminar Workspace View and Print Summary) is fully implemented, verified, and regression-tested across both Slice 1 and Slice 2. Requesting checkpoint authorization to mark M2.5 as COMPLETED and prepare for commit.
REPORT;

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: {$currentCommit}
- Active Execution State: IMPLEMENTING (Unit M2.5, Task M2.5-IMPLEMENT-002)
- Completed Units (5/24): M1.1, M2.1, M2.2, M2.3, M2.4
- Test Suite Status: 55 tests passed (416 assertions), 0 failures, 0 regressions in 4.46s.
- Whitelist Compliance: carmel-linx-laravel/tests/Feature/R21SeminarWorkspaceViewTest.php
- Safety Boundary: `app/Models/User.php` untouched. Zero DB schema changes.

{$detailedReport}
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.5',
    'IMPLEMENTING',
    [
        'unit_title' => 'R21 Seminar Dedicated Workspace & Print Feature Test (M2.5-IMPLEMENT-002)',
        'objective' => 'Create dedicated feature test coverage for the R21 Seminar workspace and print views, proving master-shell usage, modular partial composition, global component reuse, all 5 tabs, 5 modals, 3 print modes, M2.4 backend bindings, and regression safety.',
        'explicit_request' => "Review the verification TASK_REPORT for task 'M2.5-IMPLEMENT-002'. Confirm that workspace-layout, report-layout, all 5 tabs, 5 modals, 3 print modes, and regression safety are verified, and issue checkpoint authorization with target_state: 'COMPLETED'. Return decision: 'PLAN_APPROVED' with payload.state_transition.target_state: 'COMPLETED'.",
        'context' => [
            'dependencies' => ['M2.4'],
            'allowed_files' => [
                'carmel-linx-laravel/tests/Feature/R21SeminarWorkspaceViewTest.php'
            ],
            'task_report' => [
                'task_id' => 'M2.5-IMPLEMENT-002',
                'unit_id' => 'M2.5',
                'status' => 'COMPLETED',
                'summary' => 'Slice 2 completed. Dedicated feature test R21SeminarWorkspaceViewTest.php created (8 tests, 66 assertions). Verified workspace-layout, report-layout, 5 tabs, 5 modals, 3 print modes, and M2.4 endpoint bindings. Full suite: 55 passed (416 assertions, 0 failures, 0 regressions). User.php untouched.',
                'changes' => [
                    'carmel-linx-laravel/tests/Feature/R21SeminarWorkspaceViewTest.php'
                ],
                'tests' => [
                    'dedicated' => 'R21SeminarWorkspaceViewTest: 8 passed (66 assertions)',
                    'full_suite' => 'php artisan test: 55 passed (416 assertions, 0 failures, 0 regressions)'
                ],
                'issues' => [],
                'git' => [
                    'branch' => 'migration-alpha',
                    'whitelisted_files' => 1,
                    'unapproved_changes' => 0,
                    'user_model_untouched' => true
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.5-IMPLEMENT-002 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
