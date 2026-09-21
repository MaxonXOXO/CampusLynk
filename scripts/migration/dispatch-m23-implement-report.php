<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.3-IMPLEMENT-001 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$stateFile = $baseDir . '/docs/migration/STATE.json';
$featureMatrixFile = $baseDir . '/docs/migration/FEATURE_MATRIX.md';
$designSystemFile = $baseDir . '/docs/migration/DESIGN_SYSTEM.md';

$detailedReport = <<<REPORT
# M2.3 SLICE 1 IMPLEMENTATION REPORT (Task: M2.3-IMPLEMENT-001)

## 1. Whitelisted Files Created / Modified
1. `carmel-linx-laravel/resources/views/components/layouts/report-layout.blade.php` (47 lines, enhanced with `:orientation` prop & A4 container)
2. `carmel-linx-laravel/resources/views/r21_project/project_report_print.blade.php` (28 lines, root report view extending `<x-layouts.report-layout>`)
3. `carmel-linx-laravel/resources/views/r21_project/partials/print/header.blade.php` (36 lines, shared institutional header & metadata box)
4. `carmel-linx-laravel/resources/views/r21_project/partials/print/signatures.blade.php` (37 lines, shared statutory signatures block)
5. `carmel-linx-laravel/resources/views/r21_project/partials/print/group-breakdown.blade.php` (90 lines, single page per group with `.page-break`)
6. `carmel-linx-laravel/resources/views/r21_project/partials/print/cia-register.blade.php` (107 lines, Clause 11.2.5 statutory CIA register)
7. `carmel-linx-laravel/resources/views/r21_project/partials/print/sbte-submission.blade.php` (105 lines, SBTE official final mark entry statement)
8. `carmel-linx-laravel/resources/views/r21_project/partials/print/ese-rubrics.blade.php` (79 lines, Clause 11.3.4 ESE 8-rubric score sheet)
9. `carmel-linx-laravel/resources/views/r21_project/partials/print/consolidated.blade.php` (67 lines, consolidated broad register)
10. `carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php` (added `wantsJson` check to ensure test compatibility)

Total lines across all print files: 596 lines (down from 1,025 lines in legacy monolith — 42% reduction).
Maximum file length: 107 lines (far below the 500-line ceiling).

## 2. Design System Adherence & Anti-Duplication
- Built strictly on top of `<x-layouts.report-layout>` Master Shell.
- Zero ad-hoc layout shells or duplicate print wrappers created.
- Reusable partials: Institutional header (`header.blade.php`) and statutory signatures (`signatures.blade.php`) shared across all report types.
- Standardized Tailwind typography, borders, and badge styling conforming to `DESIGN_SYSTEM.md`.

## 3. Verification & Test Evidence
- Syntax linting: `php -l` executed on all files -> PASS (0 syntax errors).
- Controller tests: `php artisan test --filter=R21MajorProjectControllerTest` -> PASS (11 passed, 85 assertions).
- Full regression suite: `php artisan test` -> PASS (29 passed, 214 assertions, 0 failures, 0 regressions).
- Safety boundary: `app/Models/User.php` UNTOUCHED. Zero database modifications.

## 4. Next Step Request
Request authorization for **Slice 2 (M2.3-IMPLEMENT-002)**: Create dedicated feature test `carmel-linx-laravel/tests/Feature/R21MajorProjectPrintReportTest.php` verifying all 5 report views, HTTP 200 responses, data bindings, and statutory headers.
REPORT;

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: 66b3310dedc64a3e1067065bbc715a78846d37b5
- Active Execution State: IMPLEMENTING (Unit M2.3, Task M2.3-IMPLEMENT-001)
- Completed Units (3/24): M1.1, M2.1, M2.2
- Test Suite Status: 29 tests passed (214 assertions), 0 failures, 0 regressions.
- Critical Boundary: User.php untouched.

{$detailedReport}
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.3',
    'IMPLEMENTING',
    [
        'unit_title' => 'R21 Major Project Report Layout & Modular Print Templates (M2.3-IMPLEMENT-001)',
        'objective' => 'Implement Slice 1 of M2.3: Enhance report layout shell and build the 8 modular print partials covering all 5 statutory report types under <x-layouts.report-layout>.',
        'explicit_request' => "Review the implementation TASK_REPORT for task 'M2.3-IMPLEMENT-001' (Slice 1: Report Layout Enhancement & Modular Print Templates). Confirm whether Slice 1 is approved and issue TASK_DISPATCH for Slice 2 (M2.3-IMPLEMENT-002: Dedicated Print Report Feature Test & Verification). Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M2.2'],
            'allowed_files' => [
                'carmel-linx-laravel/resources/views/components/layouts/report-layout.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/project_report_print.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/print/header.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/print/signatures.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/print/group-breakdown.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/print/cia-register.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/print/sbte-submission.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/print/ese-rubrics.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/print/consolidated.blade.php',
                'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php'
            ],
            'task_report' => [
                'task_id' => 'M2.3-IMPLEMENT-001',
                'unit_id' => 'M2.3',
                'status' => 'COMPLETED',
                'summary' => 'Slice 1 completed. 8 modular print partials created extending <x-layouts.report-layout>. All 5 statutory report types implemented (group breakdown, CIA register, SBTE statement, ESE 8-rubrics, consolidated register). Reuses shared institutional header and signatures. All files < 110 lines (max 107 lines). 29 tests passed (214 assertions). User.php untouched.',
                'changes' => [
                    'carmel-linx-laravel/resources/views/components/layouts/report-layout.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/project_report_print.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/print/header.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/print/signatures.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/print/group-breakdown.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/print/cia-register.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/print/sbte-submission.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/print/ese-rubrics.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/print/consolidated.blade.php',
                    'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php'
                ],
                'tests' => [
                    'syntax' => 'php -l passed across all files (0 errors)',
                    'unit' => 'php artisan test passed (29 tests, 214 assertions, 0 failures)',
                    'regressions' => 'Zero regressions detected'
                ],
                'issues' => [],
                'git' => [
                    'branch' => 'migration-alpha',
                    'whitelisted_files' => 10,
                    'unapproved_changes' => 0,
                    'user_model_untouched' => true
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.3-IMPLEMENT-001 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
