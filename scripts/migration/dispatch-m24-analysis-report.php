<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.4-ANALYZE-001 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$stateFile = $baseDir . '/docs/migration/STATE.json';
$featureMatrixFile = $baseDir . '/docs/migration/FEATURE_MATRIX.md';
$designSystemFile = $baseDir . '/docs/migration/DESIGN_SYSTEM.md';

$detailedReport = <<<REPORT
# M2.4 READ-ONLY ARCHITECTURAL ANALYSIS REPORT (Task: M2.4-ANALYZE-001)

## 1. Legacy & Target Artifacts Inspected
### Legacy Sources (Read-Only Reference)
- Controller: `legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomSeminarController.php` (858 lines).
- Models:
  - `legacy-academic-platform/carmel-linx-laravel/app/Models/SeminarEvaluation.php` (32 lines).
  - `legacy-academic-platform/carmel-linx-laravel/app/Models/StudentSeminarRegistration.php` (37 lines).
  - `legacy-academic-platform/carmel-linx-laravel/app/Models/SeminarAcceptance.php` (25 lines).
- Migrations:
  - `2026_07_10_222944_create_seminar_evaluations_table.php`
  - `2026_07_10_225048_create_student_seminar_registrations_table.php`
  - `2026_07_10_234013_create_seminar_acceptances_table.php`
- Routes: `legacy-academic-platform/carmel-linx-laravel/routes/web.php` (lines 644–650).

### Target Implementation Status (CampusLynk)
- Models:
  - `carmel-linx-laravel/app/Models/SeminarEvaluation.php` (ALREADY EXISTS and matches legacy).
  - `carmel-linx-laravel/app/Models/StudentSeminarRegistration.php` (ALREADY EXISTS and matches legacy).
  - `carmel-linx-laravel/app/Models/SeminarAcceptance.php` (ALREADY EXISTS and matches legacy).
  - `carmel-linx-laravel/app/Models/CourseFile.php` (ALREADY EXISTS with JSON casts).
  - `carmel-linx-laravel/app/Models/AcademicMark.php` (ALREADY EXISTS with UUID support).
- Migrations:
  - All 3 seminar migrations ALREADY EXIST in `carmel-linx-laravel/database/migrations/`.
  - Zero database schema changes or migrations required.
- Shared Services:
  - `carmel-linx-laravel/app/Services/AttainmentService.php` ALREADY contains official SBTE Seminar configuration (lines 181–196: 100% CIE, 75M CIA, 0M ESE, 50% threshold) and `calculateBatchLevel()`.
- Missing Target Artifacts:
  - Controller: `carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomSeminarController.php` (genuinely missing).
  - Routes: 6 web routes in `carmel-linx-laravel/routes/web.php` (genuinely missing).

## 2. Complete Endpoint & Functional Inventory (6 Endpoints)
1. `GET /r21/classroom/seminar/{subjectId}` (`show`):
   - Auth guard: session `userId`.
   - Response negotiation: checks `if (request()->wantsJson()) return response()->json(...)` before view check.
   - Aggregates enrolled students, multi-assessor rubric evaluations, seminar topic/date/guide registrations, lab batches, attendance logs.
   - Calculates 6 rubrics, assessor averages, SBTE attendance mark, letter grade, grade points, pass/fail result, and summary statistics.
2. `POST /r21/classroom/seminar/{subjectId}/syllabus` (`uploadSyllabus`):
   - Validates `syllabus_file` (mimes:pdf, max:15MB).
   - Stores file on public storage disk under `syllabi/`.
   - Updates `CourseFile.syllabus_pdf_path`.
3. `POST /r21/classroom/seminar/{subjectId}/evaluate` (`saveEvaluation`):
   - Validates 6 statutory rubrics:
     - `relevance`: min:0, max:7.5
     - `literature`: min:0, max:7.5
     - `presentation`: min:0, max:37.5
     - `interaction`: min:0, max:7.5
     - `report`: min:0, max:7.5
     - `attendance`: min:0, max:7.5
     - Total: capped at 75.0M.
   - Multi-assessor support: stores per-assessor score in `SeminarEvaluation` and averages across all assessors.
   - Updates `StudentSeminarRegistration` (`topic`, `presentation_date`, `guide_mobile_no`) if provided.
   - Upserts `syllabus_registry` entry.
   - Upserts `AcademicMark` (`category = 'Seminar'`, `max_marks = 75`, `marks_obtained = $averageScore`).
4. `POST /r21/classroom/seminar/{subjectId}/schedule` (`updateSeminarSchedule`):
   - Validates `reg_no`, `topic`, `presentation_date` (nullable|date), `guide_mobile_no` (nullable|string).
   - Upserts `StudentSeminarRegistration`.
5. `GET /r21/classroom/seminar/{subjectId}/print` (`printReport`):
   - Supports 3 report types: `consolidated`, `cia_submission`, `schedule`.
   - Prepares student roster, rubric averages, splitup components (67.5M seminar + 7.5M attd), grade data, and scores in words.
   - Response negotiation: returns JSON if requested via API/test.
6. `GET /r21/classroom/seminar/{subjectId}/attainment-summary` (`getAttainmentSummary`):
   - Computes NBA direct attainment (100% CIE-based for Seminar) across CO1–CO3 using `AttainmentService::calculateBatchLevel`.
   - Threshold: 50% of 67.5M academic marks (33.75M).
   - Incorporates course exit survey ratings.

## 3. Statutory Grading & Calculation Standards
- **Clause 11.2.6 Evaluation Splitup (75 Marks Total):**
  - Seminar Evaluation: 67.5 Marks (Relevance 7.5, Literature 7.5, Presentation 37.5, Interaction 7.5, Report 7.5)
  - Attendance: 7.5 Marks (SBTE slab: >=90%: 7.5M, >=80%: 6.0M, >=75%: 4.5M, >=70%: 3.0M, >=65%: 1.5M, <65%: 0.0M)
- **SBTE 9-Point Grade Scale (out of 75M):**
  - S: >= 90% (67.5M) — 10 points (Pass)
  - A: >= 80% (60.0M) — 9 points (Pass)
  - B: >= 70% (52.5M) — 8 points (Pass)
  - C: >= 60% (45.0M) — 7 points (Pass)
  - D: >= 50% (37.5M) — 6 points (Pass)
  - E: >= 40% (30.0M) — 5 points (Pass)
  - F: < 40% (< 30.0M) — 0 points (Failed)

## 4. Reusable Target Infrastructure & Anti-Duplication
- **Shared Models:** `SeminarEvaluation`, `StudentSeminarRegistration`, `SeminarAcceptance`, `CourseFile`, `AcademicMark` are all already present in target.
- **Shared Services:** `AttainmentService` is reused directly for SBTE grading and attainment; no duplicate calculation services will be created.
- **Shared Utilities:** Response negotiation pattern established in M2.1/M2.3 is reused.
- **Master Shell / UI:** Blade views and print templates belong to M2.5; M2.4 is strictly backend controller and endpoints.

## 5. Route Integration & Collision Analysis
- Target route prefix: `/r21/classroom/seminar/{subjectId}/*`.
- Grouped inside existing `Route::middleware(['web'])->group(...)` in `routes/web.php`.
- Zero route collisions with `/r21/classroom/project/*` or existing legacy classroom routes.

## 6. Proposed Implementation Slices
- **Slice 1 (M2.4-IMPLEMENT-001): Controller Implementation & Route Registration**
  - Whitelist:
    - `carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomSeminarController.php` [NEW]
    - `carmel-linx-laravel/routes/web.php` [MODIFY]
  - Acceptance Criteria:
    - Controller implements all 6 endpoints with auth guards, validation, rubric calculations, and response negotiation.
    - `php -l` passes on both files with 0 syntax errors.
    - Zero database modifications. Full existing test suite (37 tests) passes without regression.
- **Slice 2 (M2.4-IMPLEMENT-002): Dedicated Seminar Backend Feature Test Suite**
  - Whitelist:
    - `carmel-linx-laravel/tests/Feature/R21SeminarControllerTest.php` [NEW]
  - Acceptance Criteria:
    - Dedicated test covers all 6 endpoints: unauthenticated rejection, `show` data payload, `uploadSyllabus`, `saveEvaluation` bounds validation and AcademicMark upsert, `updateSeminarSchedule`, `printReport`, and `getAttainmentSummary`.
    - Dedicated test passes with 100% pass rate. Full suite passes with 0 regressions.

## 7. Recommended First Implementation Slice
Authorize **Slice 1 (M2.4-IMPLEMENT-001)**: Controller Implementation & Route Registration.
REPORT;

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: c580607b0ecda518e906c271887e224e756b2f76
- Active Execution State: ANALYZING (Unit M2.4)
- Completed Units (4/24): M1.1, M2.1, M2.2, M2.3
- Test Suite Status: 37 tests passed (291 assertions), 0 failures, 0 regressions.
- Safety Boundary: User.php untouched.

{$detailedReport}
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.4',
    'ANALYZING',
    [
        'unit_title' => 'R21 Seminar Controller and Presentation Rubrics Backend (M2.4)',
        'objective' => 'Perform a read-only architectural analysis of the R21 Seminar Controller and Presentation Rubrics Backend migration and determine the concrete implementation slices required to achieve legacy functional parity in CampusLynk.',
        'explicit_request' => "Review the read-only architectural analysis for task 'M2.4-ANALYZE-001'. Confirm whether the 6-endpoint inventory, statutory rubric calculation rules, reuse of existing models/services, and two-slice implementation plan are approved, and issue TASK_DISPATCH for Slice 1 (M2.4-IMPLEMENT-001). Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M1.1'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ],
            'task_report' => [
                'task_id' => 'M2.4-ANALYZE-001',
                'unit_id' => 'M2.4',
                'status' => 'COMPLETED',
                'summary' => 'Read-only architectural analysis completed for M2.4. All 3 models (SeminarEvaluation, StudentSeminarRegistration, SeminarAcceptance) and migrations already exist in target. AttainmentService already supports Seminar. Proposed 2-slice plan: Slice 1 implements R21VirtualClassroomSeminarController (6 endpoints) and web routes; Slice 2 adds dedicated feature tests. Zero database changes required. User.php untouched.',
                'changes' => [],
                'tests' => [
                    'syntax' => 'N/A (Read-only analysis)',
                    'regressions' => 'Zero changes made'
                ],
                'issues' => [],
                'git' => [
                    'branch' => 'migration-alpha',
                    'whitelisted_files' => 0,
                    'unapproved_changes' => 0,
                    'user_model_untouched' => true
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.4-ANALYZE-001 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
