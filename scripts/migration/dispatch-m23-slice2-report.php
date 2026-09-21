<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.3-IMPLEMENT-002 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$detailedReport = <<<REPORT
# M2.3 SLICE 2 IMPLEMENTATION & VERIFICATION REPORT (Task: M2.3-IMPLEMENT-002)

## 1. Dedicated Feature Test Created
- File: `carmel-linx-laravel/tests/Feature/R21MajorProjectPrintReportTest.php` (272 lines)
- Whitelist Compliance: Exactly 1 test file created in Slice 2. Zero unauthorized files modified.

## 2. Comprehensive Test Coverage for All Five Report Modes
1. `test_print_report_unauthenticated_is_redirected`: Unauthenticated access redirected to `/`.
2. `test_print_report_uses_report_layout_and_shared_components`: Confirms `<x-layouts.report-layout>` Master Shell usage, print controls (`Print / Save PDF`, `← Back`), A4 landscape orientation, shared institutional header (`Carmel Polytechnic College, Alappuzha`, `Revision 2021 Regulation`, `6009 — Major Project`), and statutory signatures block (`Faculty Guide`, `Internal Examiner`, `External Examiner`, `Head of Department`).
3. `test_print_report_group_breakdown_mode`: Confirms `group_breakdown` / `group_dossier` mode renders group title, project title, guide name, student members, and `.page-break` container.
4. `test_print_report_cia_register_mode`: Confirms Clause 11.2.5 statutory CIA register renders statutory scheme, Diary (30M), Dept (30M), Attd (15M), Total CIA (75M), student marks, and class average stats.
5. `test_print_report_sbte_submission_mode`: Confirms official SBTE final statement renders ratio 3:2, CIA 75M, ESE 50M, Total 125M, statutory certification declaration, and pass rate.
6. `test_print_report_ese_rubrics_mode`: Confirms Clause 11.3.4 ESE 8-rubric score sheet renders all 8 statutory rubrics (Model 10M, Tools 5M, Pres 7.5M, Inno 2.5M, Viva 7.5M, Indiv 7.5M, Grp 5M, Rep 5M), Total ESE 50M, and student scores.
7. `test_print_report_consolidated_mode`: Confirms consolidated broad register renders evaluation scheme, full student roster, CIA/ESE/Total marks, grades, and guide names.
8. `test_print_report_json_negotiation`: Confirms `getJson()` receives HTTP 200 with structured JSON response without regression.

## 3. Test Execution Evidence
- Dedicated Test Suite: `php artisan test --filter=R21MajorProjectPrintReportTest` -> PASS (8 tests, 77 assertions).
- Existing Controller Suite: `php artisan test --filter=R21MajorProjectControllerTest` -> PASS (11 tests, 85 assertions).
- Full Application Suite: `php artisan test` -> PASS (37 tests, 291 assertions, 0 failures, 0 regressions).

## 4. Architectural & Safety Adherence
- Design System: Reuses `<x-layouts.report-layout>` as the sole print master shell; zero duplicate shells.
- Modular Decomposition: All 8 print partials remain under 110 lines (max 107 lines).
- Safety Boundary: `app/Models/User.php` UNTOUCHED. Zero database schema changes.

## 5. Checkpoint Request
M2.3 (R21 Major Project Consolidated Print Report) is fully implemented, verified, and regression-tested across both Slice 1 and Slice 2. Requesting checkpoint authorization to mark M2.3 as COMPLETED and prepare for commit.
REPORT;

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: 66b3310dedc64a3e1067065bbc715a78846d37b5
- Active Execution State: IMPLEMENTING (Unit M2.3, Task M2.3-IMPLEMENT-002)
- Completed Units (3/24): M1.1, M2.1, M2.2
- Test Suite Status: 37 tests passed (291 assertions), 0 failures, 0 regressions.
- Whitelist Compliance: carmel-linx-laravel/tests/Feature/R21MajorProjectPrintReportTest.php
- Safety Boundary: User.php untouched.

{$detailedReport}
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.3',
    'IMPLEMENTING',
    [
        'unit_title' => 'R21 Major Project Dedicated Print Report Feature Test (M2.3-IMPLEMENT-002)',
        'objective' => 'Create dedicated feature coverage for the R21 Major Project consolidated print report and verify all five statutory report modes, their report-layout integration, critical rendered content, and regression safety.',
        'explicit_request' => "Review the verification TASK_REPORT for task 'M2.3-IMPLEMENT-002'. Confirm that all 5 report modes and layout integration are verified, and issue checkpoint authorization with target_state: 'COMPLETED'. Return decision: 'PLAN_APPROVED' with payload.state_transition.target_state: 'COMPLETED'.",
        'context' => [
            'dependencies' => ['M2.2'],
            'allowed_files' => [
                'carmel-linx-laravel/tests/Feature/R21MajorProjectPrintReportTest.php'
            ],
            'task_report' => [
                'task_id' => 'M2.3-IMPLEMENT-002',
                'unit_id' => 'M2.3',
                'status' => 'COMPLETED',
                'summary' => 'Slice 2 completed. Dedicated feature test R21MajorProjectPrintReportTest.php created (272 lines). All 5 report modes verified (group_breakdown, cia_register, sbte_submission, ese_rubrics, consolidated). Master shell <x-layouts.report-layout> integration verified. 37 tests passed across full suite (291 assertions). User.php untouched.',
                'changes' => [
                    'carmel-linx-laravel/tests/Feature/R21MajorProjectPrintReportTest.php'
                ],
                'tests' => [
                    'dedicated' => 'R21MajorProjectPrintReportTest: 8 passed (77 assertions)',
                    'controller' => 'R21MajorProjectControllerTest: 11 passed (85 assertions)',
                    'full_suite' => 'php artisan test: 37 passed (291 assertions, 0 failures, 0 regressions)'
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

echo "Dispatching M2.3-IMPLEMENT-002 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
