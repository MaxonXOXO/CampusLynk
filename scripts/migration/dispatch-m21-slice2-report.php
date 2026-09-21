<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.1-IMPLEMENT-002 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$detailedReport = <<<REPORT
# M2.1-IMPLEMENT-002 IMPLEMENTATION TASK REPORT (Slice 2: AttainmentService, Controller Endpoints, Routes & Feature Tests)

## 1. Task Summary
- Task ID: M2.1-IMPLEMENT-002
- Unit ID: M2.1
- Slice: Slice 2 - AttainmentService, R21VirtualClassroomMajorProjectController, Route Registration & Feature Tests
- Objective: Implement Slice 2 of M2.1: port AttainmentService, port all 11 R21 Virtual Classroom Major Project backend endpoints into the modern CampusLynk architecture, register the required authenticated routes, and create dedicated feature tests covering endpoint validation, authentication, and attainment behavior.

## 2. Whitelisted Files Implemented / Modified
1. carmel-linx-laravel/app/Services/AttainmentService.php [NEW]
   - Implemented self-contained domain calculation service:
     - SBTE_GRADE_SCALE constants (S, A, B, C, D, E, F, FE)
     - `getGradePoints(?string $grade): int`
     - `isGradeMet(?string $studentGrade, string $thresholdGrade = 'D'): bool`
     - `percentageToGrade(float $pct): string`
     - `gradeToMarks(string $grade, float $maxMarks = 60.0): float`
     - `calculateBatchLevel(float $metPercent, float $lvl3 = 65.0, float $lvl2 = 55.0, float $lvl1 = 45.0): int`
     - `getLevelLabel(int $level): string`
     - `calculateDirectAttainment(float $ciaLevel, float $eseLevel, float $wInternal = 0.30, float $wExternal = 0.70): float`
     - `calculateOverallAttainment(float|int $directLevel, float|int $indirectLevel, float $wDirect = 0.80, float $wIndirect = 0.20): float`
     - `convertMarksToGrade(float $marks, float $maxMarks = 75.0): string`
     - `convertGradeToMarks(string $grade, float $maxMarks = 75.0): float`
     - `getDefaultEseConfig(string $subjectType = 'Theory', string $revision = 'REV2021'): array`
     - `calculateCiaAttainmentLevel(...)`
     - `getProgramOutcomes()`, `getProgramSpecificOutcomes()`
     - `calculateCoursePoContribution()`, `calculateProgramDirectAttainment()`, `calculateProgramFinalAttainment()`

2. carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php [NEW]
   - Implemented all 11 backend endpoints with modern authentication, validation, and error handling:
     1. `GET /r21/classroom/project/{subjectId}` -> `show($subjectId)`: Loads/initializes R21MajorProjectCourseFile, calculates attendance marks, group assignments, student results, and dashboard statistics.
     2. `POST /r21/classroom/project/{subjectId}/syllabus` -> `uploadSyllabus(Request $request, $subjectId)`: Validates PDF upload (max 15MB) and updates syllabus_pdf_path.
     3. `POST /r21/classroom/project/{subjectId}/save-groups` -> `saveGroups(Request $request, $subjectId)`: Validates and saves project groups, syncs group_id/project_title to evaluations.
     4. `POST /r21/classroom/project/{subjectId}/save-evaluation` -> `saveEvaluation(Request $request, $subjectId)`: Validates mark bounds (formative max 30, summative max 30, attendance max 15, rubric components), performs two-way grade/mark conversion via AttainmentService, syncs evaluations, academic_marks, and student_board_grades.
     5. `GET /r21/classroom/project/{subjectId}/ese-marks` -> `getEseMarks($subjectId)`: Returns dual-entry student list, met counts, and batch attainment level.
     6. `POST /r21/classroom/project/{subjectId}/ese-marks/bulk-update` -> `bulkUpdateEseMarks(Request $request, $subjectId)`: Bulk updates student marks/grades, syncs evaluations, academic_marks, and student_board_grades.
     7. `GET /r21/classroom/project/{subjectId}/attainment-summary` -> `getAttainmentSummary($subjectId)`: Calculates CIE level (60M academic), ESE level (50M), direct attainment (30% CIE + 70% ESE), indirect exit survey attainment, and overall attainment (80% Direct + 20% Indirect).
     8. `POST /r21/classroom/project/{subjectId}/examiners` -> `saveExaminers(Request $request, $subjectId)`: Saves internal & external examiner configurations in course file settings.
     9. `POST /r21/classroom/project/{subjectId}/group-ese` -> `saveGroupEse(Request $request, $subjectId)`: Applies common ESE rubrics to all group members.
     10. `POST /r21/classroom/project/{subjectId}/group-cia` -> `saveGroupCia(Request $request, $subjectId)`: Applies formative, summative, and attendance marks to all group members.
     11. `GET /r21/classroom/project/{subjectId}/report/print` -> `printReport(Request $request, $subjectId)`: Aggregates consolidated and group-wise evaluation data, statistics, grade distributions, and examiner details.

3. carmel-linx-laravel/routes/web.php [MODIFIED]
   - Registered all 11 routes under the web middleware group. Verified with `php artisan route:list --path=r21/classroom/project`.

4. carmel-linx-laravel/tests/Feature/R21MajorProjectControllerTest.php [NEW]
   - 11 dedicated feature tests covering:
     - `test_unauthenticated_requests_are_rejected` (auth guard verification)
     - `test_attainment_service_methods` (core calculation verification)
     - `test_show_endpoint_initializes_course_file_and_returns_data`
     - `test_save_groups_endpoint`
     - `test_save_evaluation_endpoint` (calculations, persistence, academic_marks sync)
     - `test_save_evaluation_validation_bounds` (out of bounds marks rejected with 422)
     - `test_ese_marks_and_bulk_update`
     - `test_attainment_summary_endpoint`
     - `test_save_examiners_endpoint`
     - `test_group_ese_and_cia_updates`
     - `test_print_report_endpoint`

## 3. Automated Verification Evidence
- PHP Syntax Checks:
  - php -l app/Services/AttainmentService.php -> PASS (0 errors)
  - php -l app/Http/Controllers/R21VirtualClassroomMajorProjectController.php -> PASS (0 errors)
  - php -l routes/web.php -> PASS (0 errors)
  - php -l tests/Feature/R21MajorProjectControllerTest.php -> PASS (0 errors)
- Artisan Route List:
  - `php artisan route:list --path=r21/classroom/project` -> 11 routes verified.
- Dedicated Feature Test Suite:
  - `php artisan test --filter=R21MajorProjectControllerTest` -> PASS (11 tests, 85 assertions in 1.52s)
- Full Project Test Suite:
  - `php artisan test` -> PASS (24 tests, 165 assertions in 2.17s)
  - Existing AuditLogTest (6 tests, 17 assertions) continues to PASS with zero regressions.
  - Slice 1 R21MajorProjectModelsTest (5 tests, 61 assertions) continues to PASS with zero regressions.

## 4. Constraint & Safety Invariants Verification
- Allowed Files Whitelist: Exactly the 4 authorized files were created/modified.
- Untouched Files:
  - `app/Models/User.php`: Untouched.
  - Existing Slice 1 migrations and models: Untouched.
  - Control-plane files (STATE.json, FEATURE_MATRIX.md): Untouched.
- Destructive Operations: Zero DB wipe / fresh / drop / truncate operations executed.

## 5. Design System & Anti-Duplication Compliance
- Complies strictly with DESIGN_SYSTEM.md and AI_MIGRATION_RULES.md.
- Zero duplicated UI or styling components introduced.
- Backend controller methods are view-agnostic and return clean structured data ready for integration with `<x-layouts.workspace-layout>` (for M2.2) and `<x-layouts.report-layout>` (for M2.3) using global `<x-ui.*>` components.
REPORT;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.1',
    'IMPLEMENTING',
    [
        'unit_title' => 'R21 Major Project Controller, AttainmentService & Routes (M2.1-IMPLEMENT-002)',
        'objective' => 'Implement Slice 2 of M2.1: port AttainmentService, port all 11 R21 Virtual Classroom Major Project backend endpoints into modern CampusLynk architecture, register authenticated routes, and create dedicated feature tests.',
        'explicit_request' => "Review the implementation TASK_REPORT for task 'M2.1-IMPLEMENT-002'. Confirm whether Slice 2 satisfies architectural criteria. NOTE: RESPONSE_SCHEMA.json enforces additionalProperties: false on payload. Every migration decision must consider existing CampusLynk global components (<x-ui.*>) and Master Shell layouts (<x-layouts.*>) to prevent duplication. If approving the implementation, return decision: 'PLAN_APPROVED' or next task directive.",
        'context' => [
            'dependencies' => ['M1.1'],
            'allowed_files' => [
                'carmel-linx-laravel/app/Services/AttainmentService.php',
                'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php',
                'carmel-linx-laravel/routes/web.php',
                'carmel-linx-laravel/tests/Feature/R21MajorProjectControllerTest.php'
            ],
            'task_report' => [
                'task_id' => 'M2.1-IMPLEMENT-002',
                'unit_id' => 'M2.1',
                'status' => 'COMPLETED',
                'summary' => 'Slice 2 of M2.1 completed. Created AttainmentService.php (378 lines), R21VirtualClassroomMajorProjectController.php (11 endpoints), registered 11 authenticated routes in routes/web.php, and created dedicated feature test suite R21MajorProjectControllerTest.php. All 11 dedicated tests (85 assertions) passed. Full project suite (24 tests, 165 assertions) passed. Zero files modified outside whitelist. User.php untouched.',
                'changes' => [
                    'carmel-linx-laravel/app/Services/AttainmentService.php (Ported SBTE Kerala grade scale, ESE configuration, and CO-PO attainment calculations)',
                    'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php (Ported all 11 backend endpoints with modern auth, validation, and error handling)',
                    'carmel-linx-laravel/routes/web.php (Registered 11 authenticated /r21/classroom/project/{subjectId}/* routes)',
                    'carmel-linx-laravel/tests/Feature/R21MajorProjectControllerTest.php (Created 11 dedicated feature tests covering auth guard, attainment service, all endpoints, validation, and print report)'
                ],
                'tests' => [
                    'focused' => 'php artisan test --filter=R21MajorProjectControllerTest -> PASS (11 tests, 85 assertions in 1.52s)',
                    'full_suite' => 'php artisan test -> PASS (24 tests, 165 assertions in 2.17s)',
                    'syntax' => 'php -l on all 4 files -> PASS (0 errors)',
                    'routes' => 'php artisan route:list --path=r21/classroom/project -> 11 routes verified'
                ],
                'issues' => [],
                'git' => [
                    'branch' => 'migration-alpha',
                    'created_files' => [
                        'carmel-linx-laravel/app/Services/AttainmentService.php',
                        'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php',
                        'carmel-linx-laravel/tests/Feature/R21MajorProjectControllerTest.php'
                    ],
                    'modified_application_files' => [
                        'carmel-linx-laravel/routes/web.php'
                    ],
                    'user_model_untouched' => true
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.1-IMPLEMENT-002 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
