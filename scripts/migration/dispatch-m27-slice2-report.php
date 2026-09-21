<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.7 Slice 2 Implementation & Checkpoint Request to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$taskReport = [
    'task_id' => 'M2.7-SLICE2-001',
    'unit_id' => 'M2.7',
    'status' => 'COMPLETED',
    'summary' => 'Completed M2.7 Slice 2: Created all 4 statutory print templates mounting <x-layouts.report-layout> (landscape and portrait), created dedicated feature test R21DrawingWorkspaceViewTest (6 passed, 36 assertions), and verified full test suite: 70 passed (509 assertions, 0 regressions in 4.95s). All Blade files under 500 lines. User.php untouched.',
    'changes' => [
        'carmel-linx-laravel/resources/views/r21_drawing/sheet_evaluation_print.blade.php',
        'carmel-linx-laravel/resources/views/r21_drawing/summative_test_print.blade.php',
        'carmel-linx-laravel/resources/views/r21_drawing/cia_consolidated_print.blade.php',
        'carmel-linx-laravel/resources/views/r21_drawing/lesson_plan_print.blade.php',
        'carmel-linx-laravel/tests/Feature/R21DrawingWorkspaceViewTest.php',
        'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomDrawingController.php'
    ],
    'tests' => [
        'total' => 70,
        'passed' => 70,
        'failed' => 0,
        'regressions' => 0,
        'output' => 'Full application suite: 70 passed (509 assertions) in 4.95s. R21DrawingWorkspaceViewTest: 6 passed (36 assertions). R21DrawingControllerTest: 9 passed (57 assertions). Zero regressions.'
    ],
    'issues' => [],
    'git' => [
        'branch' => 'migration-alpha',
        'commit' => '504c30f129c67b192ba7f0d21213aa77af369b86',
        'clean' => false
    ]
];

$detailedFindings = <<<'FINDINGS'
# M2.7 SLICE 2 IMPLEMENTATION & VERIFICATION REPORT

## 1. IMPLEMENTATION SUMMARY: 4 STATUTORY PRINT TEMPLATES (< 500 LINES CEILING)
Created all 4 statutory print templates inside `resources/views/r21_drawing/`, strictly mounting `<x-layouts.report-layout>` and adhering to the Kerala SBTE R-2021 Regulation:
1. **Formative Sheet Register (`sheet_evaluation_print.blade.php` - 110 lines)**:
   - Mounts `<x-layouts.report-layout orientation="landscape">`.
   - Continuous evaluation register showing 8 sheets with Timely Completion + Appearance marks, student averages out of 100, scaled formative marks out of 20, and signature blocks.
2. **Summative Series Test Register (`summative_test_print.blade.php` - 125 lines)**:
   - Mounts `<x-layouts.report-layout orientation="landscape">`.
   - Shows Series Test 1 & 2 four-criteria breakdown (Procedure 40, Final 30, Dimensioning 20, Neatness 10 = 100), average test scores, scaled summative marks out of 20, and signature blocks.
3. **Consolidated CIA Marksheet (`cia_consolidated_print.blade.php` - 118 lines)**:
   - Mounts `<x-layouts.report-layout orientation="portrait">`.
   - Statutory SBTE continuous internal evaluation statement: Formative (20M) + Summative (20M) + Attendance (10M) = 50M CIA, SBTE letter grades (S–F), 40% pass eligibility, statutory certification statement, and 3-signatory block (Faculty, HOD, Principal).
4. **Lesson Plan & Syllabus Compliance (`lesson_plan_print.blade.php` - 92 lines)**:
   - Mounts `<x-layouts.report-layout orientation="portrait">`.
   - 60-contact-hour session distribution table with day number, planned date, hours, topic/exercise, module, CO mapping, pedagogy, status, and signatory block.

## 2. DEDICATED FEATURE TEST: `tests/Feature/R21DrawingWorkspaceViewTest.php`
Created dedicated feature test suite with 6 tests:
1. `test_workspace_view_renders_successfully_for_authenticated_faculty`: Verifies workspace rendering through `<x-layouts.workspace-layout>`, tabs, metadata, and student roster.
2. `test_workspace_redirects_unauthenticated_user`: Verifies security redirect.
3. `test_print_formative_sheet_register_renders_view`: Verifies formative print view.
4. `test_print_summative_test_register_renders_view`: Verifies summative print view.
5. `test_print_consolidated_cia_marksheet_renders_view`: Verifies consolidated CIA print view.
6. `test_print_lesson_plan_renders_view`: Verifies lesson plan print view.

## 3. VERIFICATION & TEST RESULTS
- `php artisan test --filter=R21DrawingWorkspaceViewTest`: **6 passed (36 assertions) in 1.38s**.
- `php artisan test --filter=R21Drawing`: **15 passed (93 assertions) in 1.73s**.
- Full application test suite (`php artisan test`): **70 passed (509 assertions), 0 failures, 0 regressions in 4.95s**.
- Safety Boundary: `app/Models/User.php` UNTOUCHED.
- Sub-500-Line Ceiling: Verified across all 13 Blade files (max length 320 lines).

## 4. CHECKPOINT REQUEST
Unit M2.7 (R21 Drawing Hall Workspace View and 4 Print Templates) is fully implemented, verified, and regression-tested across both Slice 1 and Slice 2. Requesting checkpoint authorization to mark M2.7 as COMPLETED and commit unit.
FINDINGS;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.7',
    'COMPLETED',
    [
        'unit_title' => 'R21 Drawing Hall Workspace View and 4 Print Templates (M2.7)',
        'objective' => 'Report completion of M2.7 Slice 2 (4 print templates and dedicated feature test) and request checkpoint authorization to mark Unit M2.7 as COMPLETED.',
        'explicit_request' => "Completed M2.7 Slice 2. All 4 statutory print templates created using report-layout. Dedicated feature test R21DrawingWorkspaceViewTest passed. Full test suite: 70 passed (509 assertions, 0 regressions). User.php untouched. Requesting supervisor checkpoint authorization to mark Unit M2.7 COMPLETED.",
        'context' => [
            'dependencies' => ['M2.6'],
            'allowed_files' => [
                'carmel-linx-laravel/resources/views/r21_drawing/sheet_evaluation_print.blade.php',
                'carmel-linx-laravel/resources/views/r21_drawing/summative_test_print.blade.php',
                'carmel-linx-laravel/resources/views/r21_drawing/cia_consolidated_print.blade.php',
                'carmel-linx-laravel/resources/views/r21_drawing/lesson_plan_print.blade.php',
                'carmel-linx-laravel/tests/Feature/R21DrawingWorkspaceViewTest.php',
                'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomDrawingController.php'
            ],
            'task_report' => $taskReport
        ]
    ],
    $runId
);

echo "Dispatching M2.7 Slice 2 Implementation Report to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 90, false, $detailedFindings);

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
