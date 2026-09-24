<?php

/**
 * CampusLynk Migration Control Plane — Dispatch Phase 2 Task Report to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_02';

$phase2ReportContext = <<<'CONTEXT'
# TASK REPORT: M10.1-PHASE2 — R26 PRACTICUM & BASIC SCIENCE WORKSPACE COMPLETE

## 1. EXECUTIVE SUMMARY
Phase 2 of Wave 6 (Backwards Parity Reconciliation) has been successfully executed in strict conformance with the Architect's directive. The 7,366-line legacy Basic Science Practicum monolith was NOT blindly copied; instead, it was decomposed into modular Blade partials and mounted inside `<x-layouts.workspace-layout>`.

## 2. RECONCILED ARTIFACTS & IMPLEMENTATION
1. `resources/views/r26_practicum/virtual_classroom_basic_science_practicum.blade.php`:
   - Clean master workspace shell (~125 lines) reusing modular partials and mounting inside `<x-layouts.workspace-layout>`.
   - Dual Mode Switcher: Virtual Theory Classroom vs Basic Science Lab Workspace.
2. `resources/views/r26_practicum/partials/basic_science_evaluation.blade.php`:
   - Dedicated evaluation desk incorporating the 40M CIA (5M Attendance + 5M SLA + 10M Series Written + 10M Continuous Lab CE + 10M Practical Tests) and 60M ESE breakdown cards.
   - Table 2.2 Continuous Lab Work Evaluator with debounced inline-editable auto-save via AJAX.
   - Table 3.1 Practical Series Tests evaluator entry triggers.
3. `app/Http/Controllers/R26VirtualClassroomPracticumController.php`:
   - Updated `show()` method to dynamically select between `virtual_classroom_basic_science_practicum` (for basic science subjects) and `virtual_classroom_practicum` (for core engineering practicum).
4. `app/Http/Controllers/R26ClassroomController.php`:
   - Added support for `$deletedIds` in `bulkUpdateLessonPlans` to honor lesson plan row deletion with confirmation.

## 3. VERIFICATION
- PHP Syntax / Linting: `php -l` passed with zero errors across all modified files.
- Feature Test: `R26PracticumWorkspaceViewTest` passed with 26 assertions.
- Regression Suite: Full regression suite executed (`163 tests, 960 assertions, 0 failures, 11.9s`).

## 4. HANDOFF STATE FOR M10.1-PHASE3
The Practicum & Basic Science workspace is complete. Phase 3 covers:
- Virtual Lab 2021 & Statutory Print Restoration:
  - 4 practical print views: `classroom_practical_experiments_print`, `classroom_practical_final_results_print`, `classroom_practical_series_print`, `classroom_practical_student_report_print`
  - Classroom & Tutor prints: `classroom_subject_log_print`, `classroom_theory_final_results_print`, `classroom_theory_roster_print`, `tutor/progress_report_card_print`, `tutor/progress_report_consolidated_print`
  - `partials/lab_batch_setup_modal.blade.php` and practical routes harmonization.

Please evaluate this Task Report and issue the TASK_DISPATCH for Phase 3.
CONTEXT;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M10.1',
    'IMPLEMENTED',
    [
        'unit_title' => 'Wave 6: Backwards Migration Parity & Reconciliation (M10.1)',
        'objective' => 'Submit Phase 2 R26 Practicum & Basic Science Workspace report and request Phase 3 Task Dispatch.',
        'explicit_request' => 'Phase 2 Practicum & Basic Science Workspace is complete with modular decomposition and 163 tests passing. Please evaluate and issue TASK_DISPATCH for Phase 3 (Virtual Lab 2021 & Statutory Prints).',
        'context' => [
            'dependencies' => ['M10.1-PHASE1'],
            'allowed_files' => [
                'resources/views/r26_practicum/virtual_classroom_basic_science_practicum.blade.php',
                'resources/views/r26_practicum/partials/basic_science_evaluation.blade.php',
                'app/Http/Controllers/R26VirtualClassroomPracticumController.php',
                'app/Http/Controllers/R26ClassroomController.php'
            ],
            'task_report' => [
                'task_id' => 'M10.1-PHASE2',
                'unit_id' => 'M10.1',
                'status' => 'COMPLETED',
                'summary' => 'Basic Science Practicum view decomposed and integrated into workspace shell; Table 2.2 debounced autosave restored; lesson plan row deletion reconciled; 163 tests passing.',
                'changes' => [
                    'resources/views/r26_practicum/virtual_classroom_basic_science_practicum.blade.php',
                    'resources/views/r26_practicum/partials/basic_science_evaluation.blade.php',
                    'app/Http/Controllers/R26VirtualClassroomPracticumController.php',
                    'app/Http/Controllers/R26ClassroomController.php'
                ],
                'tests' => [
                    'suite' => 'PHPUnit',
                    'passed' => 163,
                    'assertions' => 960,
                    'failures' => 0
                ],
                'issues' => [],
                'git' => [
                    'branch' => 'migration-alpha',
                    'head' => '8ae50886'
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching Phase 2 Task Report to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 60, false, $phase2ReportContext);

echo "\n--- Bridge Response Result ---\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if (!empty($result['success'])) {
    echo "\n[SUCCESS] Received Phase 3 directive from ChatGPT!\n";
    exit(0);
} else {
    echo "\n[FAIL] Bridge request failed.\n";
    exit(1);
}
