<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.4-IMPLEMENT-001 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$detailedReport = <<<'REPORT'
# M2.4 SLICE 1 IMPLEMENTATION REPORT (Task: M2.4-IMPLEMENT-001)

## 1. Whitelisted Files Created & Modified
1. `carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomSeminarController.php` (NEW, 549 lines)
2. `carmel-linx-laravel/routes/web.php` (MODIFIED, +8 lines, registered 6 web routes)

Whitelist Compliance: Exactly 2 files. Zero unapproved application files modified. `app/Models/User.php` UNTOUCHED.

## 2. Complete Six-Endpoint Implementation Parity
1. `show($subjectId)`:
   - Authenticated session check with `userId`.
   - Response negotiation: returns JSON if `request()->wantsJson()`; checks if view exists before HTML rendering.
   - Enrolled student query via `Student::getClassroomStudentsQuery()`.
   - 6-rubric aggregation across multiple assessors (Relevance 7.5, Literature 7.5, Presentation 37.5, Interaction 7.5, Report 7.5, Attendance 7.5 = 75M Max).
   - SBTE attendance slab calculation (>=90%: 7.5M, >=80%: 6.0M, >=75%: 4.5M, >=70%: 3.0M, >=65%: 1.5M, <65%: 0.0M).
   - SBTE 9-point grading scale (S, A, B, C, D, E, F) and summary statistics.
2. `uploadSyllabus(Request $request, $subjectId)`:
   - Validates `syllabus_file` (required|mimes:pdf|max:15360).
   - Stores file on public disk under `syllabi/` and updates `CourseFile.syllabus_pdf_path`.
3. `saveEvaluation(Request $request, $subjectId)`:
   - Validates all 6 rubrics within exact statutory bounds (relevance: 7.5, literature: 7.5, presentation: 37.5, interaction: 7.5, report: 7.5, attendance: 7.5).
   - Multi-assessor support: stores per-assessor score in `SeminarEvaluation` and averages across all assessors.
   - Updates `StudentSeminarRegistration` (`topic`, `presentation_date`, `guide_mobile_no`) if provided.
   - Upserts `syllabus_registry` entry.
   - Upserts `AcademicMark` with `category = 'Seminar'`, `co_tag = 'CO1'`, `max_marks = 75`, `marks_obtained = $averageScore`.
4. `updateSeminarSchedule(Request $request, $subjectId)`:
   - Validates `reg_no`, `topic`, `presentation_date` (nullable|date), `guide_mobile_no` (nullable|string).
   - Upserts `StudentSeminarRegistration`.
5. `printReport(Request $request, $subjectId)`:
   - Supports 3 report modes: `consolidated`, `cia_submission`, `schedule`.
   - Prepares student roster, rubric averages, splitup components (67.5M seminar + 7.5M attd), grade data, and scores in words.
   - Response negotiation: returns JSON if requested via API/test.
6. `getAttainmentSummary($subjectId)`:
   - Computes NBA direct attainment (100% CIE-based for Seminar) across CO1–CO3 using `AttainmentService::calculateBatchLevel`.
   - Academic threshold: 50% of 67.5M academic marks (33.75M).
   - Course exit survey integration.

## 3. Model & Service Reuse (Anti-Duplication)
- Models Reused: `SeminarEvaluation`, `StudentSeminarRegistration`, `SeminarAcceptance`, `CourseFile`, and `AcademicMark`. Zero duplicate models or migrations created.
- Services Reused: `AttainmentService` is reused directly for SBTE Seminar configuration and batch attainment level calculations.
- Zero duplicate grading or calculation services introduced.

## 4. Verification & Testing Evidence
- Syntax linting: `php -l` on `R21VirtualClassroomSeminarController.php` and `routes/web.php` -> PASS (0 syntax errors).
- Route registration: `php artisan route:list --path=r21/classroom/seminar` verified exactly 6 routes active:
  - `GET|HEAD r21/classroom/seminar/{subjectId}`
  - `GET|HEAD r21/classroom/seminar/{subjectId}/attainment-summary`
  - `POST     r21/classroom/seminar/{subjectId}/evaluate`
  - `GET|HEAD r21/classroom/seminar/{subjectId}/print`
  - `POST     r21/classroom/seminar/{subjectId}/schedule`
  - `POST     r21/classroom/seminar/{subjectId}/syllabus`
- Full test suite: `php artisan test` -> PASS (37 passed, 291 assertions, 0 failures, 0 regressions).
- Safety boundary: `app/Models/User.php` UNTOUCHED. Zero database modifications.

## 5. Next Step Request
Request authorization for **Slice 2 (M2.4-IMPLEMENT-002)**: Create dedicated backend feature test `carmel-linx-laravel/tests/Feature/R21SeminarControllerTest.php` covering all 6 endpoints, validation rules, multi-assessor evaluation, `AcademicMark` upsert, and attainment calculation.
REPORT;

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: c580607b0ecda518e906c271887e224e756b2f76
- Active Execution State: IMPLEMENTING (Unit M2.4, Task M2.4-IMPLEMENT-001)
- Completed Units (4/24): M1.1, M2.1, M2.2, M2.3
- Test Suite Status: 37 tests passed (291 assertions), 0 failures, 0 regressions.
- Safety Boundary: User.php untouched.

{$detailedReport}
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.4',
    'IMPLEMENTING',
    [
        'unit_title' => 'R21 Seminar Backend Controller & Routes (M2.4-IMPLEMENT-001)',
        'objective' => 'Implement Slice 1 of M2.4: Create R21VirtualClassroomSeminarController with all 6 endpoints and register web routes in routes/web.php.',
        'explicit_request' => "Review the implementation TASK_REPORT for task 'M2.4-IMPLEMENT-001' (Slice 1: Controller Implementation & Route Registration). Confirm whether Slice 1 is approved and issue TASK_DISPATCH for Slice 2 (M2.4-IMPLEMENT-002: Dedicated Seminar Backend Feature Test & Verification). Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M1.1'],
            'allowed_files' => [
                'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomSeminarController.php',
                'carmel-linx-laravel/routes/web.php'
            ],
            'task_report' => [
                'task_id' => 'M2.4-IMPLEMENT-001',
                'unit_id' => 'M2.4',
                'status' => 'COMPLETED',
                'summary' => 'Slice 1 completed. R21VirtualClassroomSeminarController created (549 lines) implementing all 6 endpoints with auth guards, validation, multi-assessor averaging, SBTE grading, and response negotiation. 6 web routes registered in routes/web.php. All 5 existing models and AttainmentService reused. 37 tests passed (291 assertions). User.php untouched.',
                'changes' => [
                    'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomSeminarController.php',
                    'carmel-linx-laravel/routes/web.php'
                ],
                'tests' => [
                    'syntax' => 'php -l passed across both files (0 errors)',
                    'route_list' => 'php artisan route:list verified all 6 seminar endpoints',
                    'regression' => 'php artisan test: 37 passed (291 assertions, 0 failures, 0 regressions)'
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

echo "Dispatching M2.4-IMPLEMENT-001 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
