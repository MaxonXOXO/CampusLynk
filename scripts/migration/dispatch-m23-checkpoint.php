<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.3 Checkpoint & Next Action to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$detailedReport = <<<REPORT
# M2.3 CHECKPOINT COMPLETION & NEXT UNIT DIRECTIVE REQUEST

## 1. Unit M2.3 & Feature r21_major_project Completed
- Unit ID: M2.3
- Title: R21 Major Project Consolidated Print Report
- State: COMPLETED
- Checkpoint Commit: `c580607b0ecda518e906c271887e224e756b2f76`
- Feature Status: `r21_major_project` is now 100% COMPLETED (all 3 units M2.1, M2.2, M2.3 completed and checkpointed).
- Migration Progress: 4 / 24 units completed (M1.1, M2.1, M2.2, M2.3).

## 2. Evidence Summary
- 8 modular print partials created under `carmel-linx-laravel/resources/views/r21_project/partials/print/` (all < 110 lines, max 107 lines).
- Master shell `<x-layouts.report-layout>` integrated with A4 print styling and orientation support.
- All 5 statutory report modes verified (group breakdown, CIA register, SBTE statement, ESE 8-rubrics, consolidated broad register).
- Dedicated feature test: `carmel-linx-laravel/tests/Feature/R21MajorProjectPrintReportTest.php` (8 tests, 77 assertions).
- Full application test suite: 37 tests, 291 assertions, 0 failures, 0 regressions.
- Safety: `app/Models/User.php` untouched. Whitelist strictly respected.

## 3. Next Unit Request
Feature `r21_major_project` is complete. Please review the dependency graph and issue `TASK_DISPATCH` for the next migration unit (e.g., `M2.4`: R21 Seminar Controller and Presentation Rubrics Backend, or your chosen priority unit).
REPORT;

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: c580607b0ecda518e906c271887e224e756b2f76
- Completed Units (4/24): M1.1, M2.1, M2.2, M2.3
- Completed Features: database_schema, r21_major_project
- Test Suite Status: 37 tests passed (291 assertions), 0 failures, 0 regressions.
- Safety Boundary: User.php untouched.

{$detailedReport}
CONTEXT;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.3',
    'COMPLETED',
    [
        'unit_title' => 'R21 Major Project Consolidated Print Report Checkpoint (M2.3)',
        'objective' => 'Finalize M2.3 checkpoint and obtain directive for the next migration unit.',
        'explicit_request' => "Acknowledge completion of Unit M2.3 and Feature 'r21_major_project'. Provide TASK_DISPATCH for the next unit in the migration graph (e.g. M2.4: R21 Seminar Controller and Presentation Rubrics Backend). Return decision: 'TASK_DISPATCH' with payload.task_dispatch for the chosen unit.",
        'context' => [
            'dependencies' => ['M2.2'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ],
            'task_report' => [
                'task_id' => 'M2.3-CHECKPOINT-001',
                'unit_id' => 'M2.3',
                'status' => 'COMPLETED',
                'summary' => 'Unit M2.3 and Feature r21_major_project are fully completed and committed (commit c580607b). 37 tests passed across the application suite (291 assertions). 4 of 24 units completed. Ready for next unit dispatch.',
                'changes' => [
                    'docs/migration/STATE.json',
                    'docs/migration/FEATURE_MATRIX.md'
                ],
                'tests' => [
                    'full_suite' => 'php artisan test: 37 passed (291 assertions, 0 failures, 0 regressions)'
                ],
                'issues' => [],
                'git' => [
                    'branch' => 'migration-alpha',
                    'whitelisted_files' => 2,
                    'unapproved_changes' => 0,
                    'user_model_untouched' => true
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.3 Checkpoint & Next Action Request to ChatGPT via CDP bridge...\n";
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
