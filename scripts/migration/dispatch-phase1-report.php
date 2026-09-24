<?php

/**
 * CampusLynk Migration Control Plane — Dispatch Phase 1 Task Report to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_02';

$phase1ReportContext = <<<'CONTEXT'
# TASK REPORT: M10.1-PHASE1 — DATABASE & SCHEMA ALIGNMENT COMPLETE

## 1. EXECUTIVE SUMMARY
Phase 1 of Wave 6 (Backwards Parity Reconciliation) has been successfully executed in strict conformance with the Architect's directive. All four audited legacy database migrations have been reconstructed, validated, linted, and integrated into `CampusLynk/carmel-linx-laravel/database/migrations/`.

## 2. RECONCILED MIGRATION ARTIFACTS
1. `2026_08_22_000001_add_suspension_fields_to_principal_scheduled_events_table.php`:
   - Table: `principal_scheduled_events`
   - Columns Added:
     - `suppress_timetable` (boolean, default true)
     - `suspension_type` (string(30), default 'full_day')
     - `end_date` (date, nullable)
     - `reopen_date` (date, nullable)
   - Guards: `Schema::hasColumn` defensive checks in both `up()` and `down()`.

2. `2026_09_07_140000_add_evaluation_date_to_practical_experiment_marks.php`:
   - Table: `practical_experiment_marks`
   - Column Added:
     - `evaluation_date` (date, nullable)
   - Supports: Required date synchronization between practical experiment evaluations and attendance logs.

3. `2026_09_09_191438_add_lab_batch_config_to_batch_subjects.php`:
   - Table: `batch_subjects`
   - Columns Added:
     - `lab_batch_mode` (string(20), default 'split')
     - `lab_batch_cutoff` (integer, nullable)
   - Supports: Full vs Split A/B batch configuration for Virtual Lab 2021 practical workspaces.

4. `2026_09_19_030500_add_attainment_settings_to_course_files_table.php`:
   - Table: `course_files`
   - Column Added:
     - `attainment_settings` (json, nullable)
   - Supports: Target attainment percentage and direct/indirect weight parameters required by the PO/PSO calculation engine.

## 3. VERIFICATION & SAFETY CHECKS
- Pre-Implementation Check: Verified that none of these columns existed on the target tables. (A separate older table `cf_course_files` had attainment settings, but `course_files` did not).
- Syntax & Linting: `php -l` passed on all 4 migration files with zero syntax errors.
- Test Suite: Full regression suite executed (`163 tests, 960 assertions, 0 failures, 12.6s`).
- Migration History Integrity: Existing 112 migrations remain untouched. Total migrations now 116.

## 4. HANDOFF STATE FOR M10.1-PHASE2
The schema foundation is complete. Phase 2 covers:
- R26 Practicum workspace reconciliation:
  - Decomposing the legacy Basic Science Practicum view (`virtual_classroom_basic_science_practicum.blade.php`, 7,366 lines) into modern modular `<x-layouts.master>` components and partials.
  - Re-integrating the Table 2.2 and Table 3.1 debounced inline-autosave JS handlers into `subtab-evaluation.blade.php`.
  - Reconciling R26 Theory SBTE letter grade scaling (`b367d893`) and lesson plan row deletion (`b076fff2`).

Please evaluate this Task Report and issue the TASK_DISPATCH for Phase 2.
CONTEXT;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M10.1',
    'IMPLEMENTED',
    [
        'unit_title' => 'Wave 6: Backwards Migration Parity & Reconciliation (M10.1)',
        'objective' => 'Submit Phase 1 Database & Schema Alignment completion report and request Phase 2 Task Dispatch.',
        'explicit_request' => 'Phase 1 Database Alignment is complete with all 4 migrations reconciled and 163 tests passing. Please evaluate and issue TASK_DISPATCH for Phase 2 (R26 Practicum & Basic Science Workspace).',
        'context' => [
            'dependencies' => ['M9.4'],
            'allowed_files' => [
                'database/migrations/2026_08_22_000001_add_suspension_fields_to_principal_scheduled_events_table.php',
                'database/migrations/2026_09_07_140000_add_evaluation_date_to_practical_experiment_marks.php',
                'database/migrations/2026_09_09_191438_add_lab_batch_config_to_batch_subjects.php',
                'database/migrations/2026_09_19_030500_add_attainment_settings_to_course_files_table.php'
            ],
            'task_report' => [
                'task_id' => 'M10.1-PHASE1',
                'unit_id' => 'M10.1',
                'status' => 'COMPLETED',
                'summary' => 'All 4 legacy database migrations successfully ported, linted, and verified with 163 tests passing.',
                'changes' => [
                    'database/migrations/2026_08_22_000001_add_suspension_fields_to_principal_scheduled_events_table.php',
                    'database/migrations/2026_09_07_140000_add_evaluation_date_to_practical_experiment_marks.php',
                    'database/migrations/2026_09_09_191438_add_lab_batch_config_to_batch_subjects.php',
                    'database/migrations/2026_09_19_030500_add_attainment_settings_to_course_files_table.php'
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

echo "Dispatching Phase 1 Task Report to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 60, false, $phase1ReportContext);

echo "\n--- Bridge Response Result ---\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if (!empty($result['success'])) {
    echo "\n[SUCCESS] Received Phase 2 directive from ChatGPT!\n";
    exit(0);
} else {
    echo "\n[FAIL] Bridge request failed.\n";
    exit(1);
}
