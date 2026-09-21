<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.1-IMPLEMENT-001 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$detailedReport = <<<REPORT
# M2.1-IMPLEMENT-001 IMPLEMENTATION TASK REPORT (Slice 1: Schema & Eloquent Models Parity)

## 1. Implementation Summary
- Task ID: M2.1-IMPLEMENT-001
- Unit ID: M2.1
- Slice: Slice 1 - R21 Major Project Schema and Eloquent Models Parity
- Objective: Establish R21 Major Project database schema and Eloquent model parity with legacy system, with dedicated automated verification.

## 2. Created Files (Whitelisted Only)
1. carmel-linx-laravel/database/migrations/2026_09_12_000001_create_r21_major_project_tables.php
   - Creates `r21_major_project_course_files` (batch_subject_id unique, syllabus_pdf_path, course_title, course_code, semester, cia_marks=75, ese_marks=50, credits=4.0, JSON columns: parsed_cos, parsed_copo, project_groups, attainment_settings).
   - Creates `r21_major_project_evaluations` (batch_subject_id, reg_no, group_id, project_title, formative_diary_marks, summative_dept_marks, attendance_marks, total_cia_75, ESE components 1-8, total_ese_50, ese_grade, grand_total_125, passed, remarks).
   - Unique key on `(batch_subject_id, reg_no)`. Foreign keys to `batch_subjects` and `students`.
2. carmel-linx-laravel/app/Models/R21MajorProjectCourseFile.php
   - Table: `r21_major_project_course_files`
   - Fillable: batch_subject_id, syllabus_pdf_path, course_title, course_code, semester, cia_marks, ese_marks, credits, parsed_cos, parsed_copo, project_groups, attainment_settings.
   - Casts: cia_marks (int), ese_marks (int), credits (float), parsed_cos (array), parsed_copo (array), project_groups (array), attainment_settings (array).
   - Relationships: `batchSubject()` -> BelongsTo BatchSubject.
3. carmel-linx-laravel/app/Models/R21MajorProjectEvaluation.php
   - Table: `r21_major_project_evaluations`
   - Fillable: batch_subject_id, reg_no, group_id, project_title, formative_diary_marks, summative_dept_marks, attendance_marks, total_cia_75, ese_prototype, ese_modern_tools, ese_presentation, ese_innovativeness, ese_viva, ese_individual_contrib, ese_group_activity, ese_project_report, total_ese_50, ese_grade, grand_total_125, passed, remarks.
   - Casts: all mark fields (float), passed (boolean).
   - Relationships: `batchSubject()` -> BelongsTo BatchSubject, `student()` -> BelongsTo Student (reg_no).
4. carmel-linx-laravel/tests/Unit/R21MajorProjectModelsTest.php
   - 5 dedicated unit tests covering model instantiation, fillable assignment, casts, relationships, and migration structure.

## 3. Automated Verification Evidence
- PHP Syntax Checks:
  - php -l database/migrations/2026_09_12_000001_create_r21_major_project_tables.php -> PASS (0 errors)
  - php -l app/Models/R21MajorProjectCourseFile.php -> PASS (0 errors)
  - php -l app/Models/R21MajorProjectEvaluation.php -> PASS (0 errors)
  - php -l tests/Unit/R21MajorProjectModelsTest.php -> PASS (0 errors)
- Dedicated Unit Test Suite:
  - php artisan test --filter=R21MajorProjectModelsTest -> PASS (5 tests, 61 assertions in 0.55s)
- Full Project Test Suite:
  - php artisan test -> PASS (13 tests, 80 assertions in 0.79s)
  - Existing AuditLogTest (6 tests, 17 assertions) continues to PASS with zero regressions.

## 4. Constraint & Safety Invariants Verification
- Allowed Files Whitelist: Only the 4 authorized files were created.
- Untouched Files:
  - routes/web.php: NOT modified.
  - R21VirtualClassroomMajorProjectController.php: NOT created yet.
  - AttainmentService.php: NOT created yet.
  - app/Models/User.php: Untouched.
  - Frontend / view / print artifacts: Untouched.
- Destructive Operations: Zero DB wipe / fresh / drop / truncate operations executed.

## 5. Design System & Anti-Duplication Compliance
- The implementation strictly enforces CampusLynk's Design System (DESIGN_SYSTEM.md).
- Slice 1 establishes database schema and Eloquent model parity without touching any UI.
- All subsequent UI work for M2.1 (Slice 2), M2.2 (workspace view), and M2.3 (print report) will be mounted inside <x-layouts.workspace-layout> and <x-layouts.report-layout>, utilizing <x-ui.*> components to prevent component duplication.

## 6. CRITICAL SUPERVISOR MANDATE: GLOBAL COMPONENT REUSE & ANTI-DUPLICATION
- Every task and architectural decision MUST consider existing CampusLynk global shells (<x-layouts.workspace-layout>, <x-layouts.app-shell>, <x-layouts.report-layout>), global UI components (<x-ui.*>), and design language.
- Eliminating UI duplication (ad-hoc CSS classes, custom buttons/modals/tables, raw unstyled HTML) along with complete UI overhauls is the PRIMARY architectural goal of this migration.
- Monolithic legacy views must be broken down into modular Blade partials and components.
REPORT;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.1',
    'IMPLEMENTING',
    [
        'unit_title' => 'R21 Major Project Schema and Eloquent Models Parity (M2.1-IMPLEMENT-001)',
        'objective' => 'Implement Slice 1 of M2.1: establish R21 Major Project database schema and Eloquent model parity with the legacy system, with dedicated automated verification.',
        'explicit_request' => "Review the implementation TASK_REPORT for task 'M2.1-IMPLEMENT-001'. Confirm whether Slice 1 satisfies architectural criteria. NOTE: RESPONSE_SCHEMA.json enforces additionalProperties: false on payload (do NOT use unauthorized keys like 'next_directive'). If dispatching the next task (Slice 2), use decision: 'TASK_DISPATCH' with payload.task_dispatch. If approving, use decision: 'PLAN_APPROVED'. Every migration decision must consider existing CampusLynk global components (<x-ui.*>) and Master Shell layouts (<x-layouts.*>) to prevent duplication.",
        'context' => [
            'dependencies' => ['M1.1'],
            'allowed_files' => [
                'carmel-linx-laravel/database/migrations/2026_09_12_000001_create_r21_major_project_tables.php',
                'carmel-linx-laravel/app/Models/R21MajorProjectCourseFile.php',
                'carmel-linx-laravel/app/Models/R21MajorProjectEvaluation.php',
                'carmel-linx-laravel/tests/Unit/R21MajorProjectModelsTest.php'
            ],
            'task_report' => [
                'task_id' => 'M2.1-IMPLEMENT-001',
                'unit_id' => 'M2.1',
                'status' => 'COMPLETED',
                'summary' => 'Slice 1 of M2.1 completed. Created migration 2026_09_12_000001_create_r21_major_project_tables.php, model R21MajorProjectCourseFile, model R21MajorProjectEvaluation, and dedicated test suite R21MajorProjectModelsTest. All 5 dedicated unit tests (61 assertions) passed. All 13 full-suite tests (80 assertions) passed. Zero files modified outside whitelist. User.php untouched.',
                'changes' => [
                    'carmel-linx-laravel/database/migrations/2026_09_12_000001_create_r21_major_project_tables.php (Created migration with tables r21_major_project_course_files and r21_major_project_evaluations matching legacy schema)',
                    'carmel-linx-laravel/app/Models/R21MajorProjectCourseFile.php (Created Eloquent model with fillable, casts for JSON/numbers, and batchSubject relationship)',
                    'carmel-linx-laravel/app/Models/R21MajorProjectEvaluation.php (Created Eloquent model with fillable, casts for floats/boolean, and batchSubject and student relationships)',
                    'carmel-linx-laravel/tests/Unit/R21MajorProjectModelsTest.php (Created dedicated unit test suite covering table names, fillable, casts, relationships, and migration structure)'
                ],
                'tests' => [
                    'focused' => 'php artisan test --filter=R21MajorProjectModelsTest -> PASS (5 tests, 61 assertions)',
                    'full_suite' => 'php artisan test -> PASS (13 tests, 80 assertions)',
                    'syntax' => 'php -l on migration, both models, and test -> PASS (0 errors)'
                ],
                'issues' => [],
                'git' => [
                    'branch' => 'migration-alpha',
                    'created_files' => [
                        'carmel-linx-laravel/database/migrations/2026_09_12_000001_create_r21_major_project_tables.php',
                        'carmel-linx-laravel/app/Models/R21MajorProjectCourseFile.php',
                        'carmel-linx-laravel/app/Models/R21MajorProjectEvaluation.php',
                        'carmel-linx-laravel/tests/Unit/R21MajorProjectModelsTest.php'
                    ],
                    'modified_application_files' => [],
                    'user_model_untouched' => true
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.1-IMPLEMENT-001 TASK_REPORT to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 90, false, $detailedReport);

echo "\n--- Bridge Response Result ---\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if (!empty($result['success'])) {
    echo "\n[SUCCESS] Received validated supervisor response from ChatGPT!\n";
    echo "Decision: " . ($result['decision'] ?? 'UNKNOWN') . "\n";
    exit(0);
} else {
    echo "\n[FAIL] Bridge request failed or rejected by fail-closed guardrails.\n";
    exit(1);
}
