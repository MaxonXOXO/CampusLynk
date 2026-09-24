<?php

/**
 * CampusLynk Migration Control Plane — Dispatch Phase 3 Task Report to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_02';

$phase3ReportContext = <<<'CONTEXT'
# TASK REPORT: M10.1-PHASE3 — VIRTUAL LAB 2021 & STATUTORY PRINTS COMPLETE

## 1. EXECUTIVE SUMMARY
Phase 3 of Wave 6 (Backwards Parity Reconciliation) has been successfully executed in strict conformance with the Architect's directive. All 11 statutory print views, modal slices, controller endpoints, and routes have been restored, modernized into `<x-layouts.report-layout>`, and verified. Every single Blade file remains strictly below the 410-line limit.

## 2. RECONCILED ARTIFACTS & IMPLEMENTATION
1. **Practical Statutory Prints (Revision 2021)**:
   - `resources/views/classroom_practical_experiments_print.blade.php` (128 lines, `<x-layouts.report-layout>`): Experiments conducted & attendance log report with summary KPIs.
   - `resources/views/classroom_practical_series_print.blade.php` (85 lines, `<x-layouts.report-layout>`): Practical series examination marksheet (15M).
   - `resources/views/classroom_practical_final_results_print.blade.php` (284 lines, `<x-layouts.report-layout>`): Consolidated ESE & final results marksheet (125M) with SBTE R21 grading scale and grade distribution table.
   - `resources/views/classroom_practical_student_report_print.blade.php` (189 lines, `<x-layouts.report-layout>`): Individual student continuous evaluation & attendance card with 5-rubric formative breakdown.

2. **Theory & Classroom Statutory Prints**:
   - `resources/views/classroom_theory_final_results_print.blade.php` (108 lines, `<x-layouts.report-layout>` landscape): Consolidated Theory CIE 50M marksheet.
   - `resources/views/classroom_theory_roster_print.blade.php` (149 lines, `<x-layouts.report-layout>` portrait): Class roster & outcome attainment register.
   - `resources/views/classroom_subject_log_print.blade.php` (106 lines, `<x-layouts.report-layout>` portrait): Official classroom teaching & attendance log register.

3. **Tutor Reports Backwards Compatibility**:
   - `resources/views/tutor/progress_report_card_print.blade.php` (1 line): Clean forwarder to modernized `tutor.reports.student_progress_card`.
   - `resources/views/tutor/progress_report_consolidated_print.blade.php` (1 line): Clean forwarder to `tutor.reports.consolidated_progress`.
   - `resources/views/tutor/attendance_consolidated_print.blade.php` (1 line): Clean forwarder to `tutor.reports.consolidated_attendance`.

4. **Modular Lab Batch Setup Modal**:
   - `resources/views/partials/lab_batch_setup_modal.blade.php` (220 lines): Decomposed modal UI.
   - `resources/views/partials/lab_batch_setup_scripts.blade.php` (360 lines): Dedicated interactive JavaScript controller.

5. **Controllers & Database Portability**:
   - `app/Http/Controllers/AttendanceController.php`: added `getLabBatchSetup`, `saveLabBatchAssignments`, and `syncPracticalExperimentsWithLogs`.
   - `app/Http/Controllers/VirtualClassroomPracticalController.php`: updated `printReport` for R21/R26 branching; added `getPracticalReportData`, `printSeriesReport`, `printFinalResults`, `printExperimentsLog`, and `printStudentReport`.
   - `app/Http/Controllers/ClassroomController.php`: added `printTheoryFinalResults`, `printTheoryClassRoster`, `printTheoryClassLog`, `updatePracticalExperimentDate`, `saveBulkPracticalEvaluations`, and `syncLessonPlanDatesFromLogs`.
   - Optimized raw queries across all controllers to use ANSI-standard `CASE WHEN roll_no IS NULL THEN 1 ELSE 0 END` for full cross-database portability.

6. **Route Registration**:
   - Registered all practical and theory print, date sync, and batch setup routes in `routes/web.php`.

## 3. VERIFICATION
- PHP Syntax / Linting: `php -l` passed with zero errors across all modified controllers, views, and routes.
- Dedicated Feature Test: `Phase3StatutoryPrintRoutesTest` passed with 8/8 tests, 32 assertions.
- Full Regression Suite: Complete test suite executed (`171 tests, 992 assertions, 0 failures, 12.6s, 100% pass`).

## 4. HANDOFF STATE FOR M10.1-PHASE4
Phase 3 is 100% complete and fully verified.
Phase 4 covers:
- Attainment Engine: HOD Program Attainment dashboard and print template in `<x-layouts.master>`.
- SBTE Bulk Import: Teams attendance PDF parser `parse_sbte_subject_log.py` and `partials/sbte_bulk_import_modal.blade.php`.
- Carmie AI: Query model `database/data/carmie_query_model.json` and `queryGemini` integration in `CarmieAssistantController.php`.

Please evaluate this Task Report, issue the approval for Phase 3, and dispatch the TASK_DISPATCH for Phase 4.
CONTEXT;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M10.1',
    'IMPLEMENTED',
    [
        'unit_title' => 'Wave 6: Backwards Migration Parity & Reconciliation (M10.1)',
        'objective' => 'Submit Phase 3 Virtual Lab 2021 & Statutory Prints report and request Phase 4 Task Dispatch.',
        'explicit_request' => 'Phase 3 Virtual Lab 2021 & Statutory Prints is complete with 11 views, routes, controllers, and 171 tests passing (992 assertions). Please evaluate and issue TASK_DISPATCH for Phase 4 (Attainment Engine, SBTE Import & Carmie AI).',
        'context' => [
            'dependencies' => ['M10.1-PHASE1', 'M10.1-PHASE2'],
            'allowed_files' => [
                'resources/views/classroom_practical_experiments_print.blade.php',
                'resources/views/classroom_practical_final_results_print.blade.php',
                'resources/views/classroom_practical_series_print.blade.php',
                'resources/views/classroom_practical_student_report_print.blade.php',
                'resources/views/classroom_theory_final_results_print.blade.php',
                'resources/views/classroom_theory_roster_print.blade.php',
                'resources/views/classroom_subject_log_print.blade.php',
                'resources/views/tutor/progress_report_card_print.blade.php',
                'resources/views/tutor/progress_report_consolidated_print.blade.php',
                'resources/views/tutor/attendance_consolidated_print.blade.php',
                'resources/views/partials/lab_batch_setup_modal.blade.php',
                'resources/views/partials/lab_batch_setup_scripts.blade.php',
                'app/Http/Controllers/AttendanceController.php',
                'app/Http/Controllers/VirtualClassroomPracticalController.php',
                'app/Http/Controllers/ClassroomController.php',
                'routes/web.php'
            ],
            'task_report' => [
                'task_id' => 'M10.1-PHASE3',
                'unit_id' => 'M10.1',
                'status' => 'COMPLETED',
                'summary' => 'All Phase 3 statutory print views restored under x-layouts.report-layout; controllers extended; routes registered; ANSI portable ordering enforced; 171 tests passing with 992 assertions.',
                'changes' => [
                    'resources/views/classroom_practical_experiments_print.blade.php',
                    'resources/views/classroom_practical_final_results_print.blade.php',
                    'resources/views/classroom_practical_series_print.blade.php',
                    'resources/views/classroom_practical_student_report_print.blade.php',
                    'resources/views/classroom_theory_final_results_print.blade.php',
                    'resources/views/classroom_theory_roster_print.blade.php',
                    'resources/views/classroom_subject_log_print.blade.php',
                    'resources/views/tutor/progress_report_card_print.blade.php',
                    'resources/views/tutor/progress_report_consolidated_print.blade.php',
                    'resources/views/tutor/attendance_consolidated_print.blade.php',
                    'resources/views/partials/lab_batch_setup_modal.blade.php',
                    'resources/views/partials/lab_batch_setup_scripts.blade.php',
                    'app/Http/Controllers/AttendanceController.php',
                    'app/Http/Controllers/VirtualClassroomPracticalController.php',
                    'app/Http/Controllers/ClassroomController.php',
                    'routes/web.php'
                ],
                'tests' => [
                    'suite' => 'PHPUnit',
                    'passed' => 171,
                    'assertions' => 992,
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

echo "Dispatching Phase 3 Task Report to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 60, false, $phase3ReportContext);

echo "\n--- Bridge Response Result ---\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if (!empty($result['success'])) {
    echo "\n[SUCCESS] Received Phase 4 directive from ChatGPT!\n";
    exit(0);
} else {
    echo "\n[FAIL] Bridge request failed.\n";
    exit(1);
}
