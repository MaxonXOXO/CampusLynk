<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.3-ANALYZE-001 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$stateFile = $baseDir . '/docs/migration/STATE.json';
$featureMatrixFile = $baseDir . '/docs/migration/FEATURE_MATRIX.md';
$designSystemFile = $baseDir . '/docs/migration/DESIGN_SYSTEM.md';

$detailedReport = <<<REPORT
# M2.3 READ-ONLY ARCHITECTURAL & PRINT REPORT ANALYSIS REPORT (Task: M2.3-ANALYZE-001)

## 1. Legacy & Target Files Inspected
- Legacy Print View: `legacy-academic-platform/carmel-linx-laravel/resources/views/r21_project/project_report_print.blade.php` (1,025 lines, 53 KB).
- Target Layout Shell: `carmel-linx-laravel/resources/views/components/layouts/report-layout.blade.php` (47 lines).
- Target Controller: `carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php` (`printReport()` at lines 1340–1543).
- Target Route: `GET /r21/classroom/project/{subjectId}/report/print` in `carmel-linx-laravel/routes/web.php`.
- Target Test: `carmel-linx-laravel/tests/Feature/R21MajorProjectControllerTest.php` (`test_print_report_endpoint` at lines 451–476).

## 2. Complete Functional & Report-Section Inventory (5 Statutory Report Types)
1. **Shared Institutional Header:** College emblem, "CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA", Department title, Regulation banner (SBTE Kerala Revision 2021), and meta details box (Subject Code, Name, Semester, Year, Batch, Guide).
2. **Report Type 1: Group-Wise Breakdown (`type=group_breakdown`):** Single page per project group with `.page-break`. Group title, guide, student marks (CIA 75M + ESE 50M = 125M), and examiner signatures for separate departmental filing.
3. **Report Type 2: Continuous Internal Assessment (CIA) Register (`type=cia_register`):** Clause 11.2.5 statutory marksheet: Attendance marks (15M), Diary (30M), Dept Review (30M), Total CIA (75M), Total in Words, CIA Grade, Eligibility status, and summary stats.
4. **Report Type 3: SBTE Official Final Mark Entry Statement (`type=sbte_submission`):** Official mark entry statement for Controller of Examinations / SBTE portal submission with marks in words (CIA 75M, ESE 50M, Grand Total 125M), final grades, and S/A/B/C/D/E/F statistics.
5. **Report Type 4: Clause 11.3.4 ESE 8-Rubric Score Sheet (`type=ese_rubrics`):** Detailed 8-rubric score sheet (Proto 10M, Tools 5M, Pres 7.5M, Inno 2.5M, Viva 7.5M, Indiv 7.5M, Grp 5M, Rep 5M = 50M) signed jointly by Internal and External Examiners.
6. **Report Type 5: Consolidated Broad Register (`type=consolidated`):** Comprehensive multi-column broad register with full CIA + ESE breakdown, grade distribution, and statutory signatures.
7. **Shared Statutory Signatures Block:** Reusable 4-up/5-up signature grid for Faculty Guide, Internal Examiner, External Examiner, HOD, and Principal.

## 3. Data & Controller Dependencies
- All required data is ALREADY prepared by `R21VirtualClassroomMajorProjectController::printReport()`:
  - `$subject`, `$classroom`, `$department`, `$fullDepartment`, `$groupedProjects`, `$students` (with numbers in words), `$attainmentSummary`, `$totalStudents`, `$completedCount`, `$passedCount`, `$failedCount`, `$passRate`, `$avgCiaOverall`, `$avgEseOverall`, `$avgGrandOverall`, `$gradeStats`, `$examiners`, `$reportType`, `$selectedGroupId`, `$currentYear`.
- Zero missing controller methods. Zero database changes required.
- Integration Note: In `printReport()`, add `if (request()->wantsJson()) return response()->json(...)` before view rendering to ensure existing JSON API tests continue passing seamlessly.

## 4. Master Shell `<x-layouts.report-layout>` Integration & A4 Pagination
- The report will mount inside `<x-layouts.report-layout>`.
- Add an optional `orientation` prop (`'portrait'` vs `'landscape'`) to `report-layout.blade.php` to support wide A4 landscape registers (8mm margins, `.page-break` pagination).
- Action controls (`Back` and `Print / Save PDF`) are automatically provided by `report-layout`.

## 5. Monolith Decomposition (All Files Well Below 500 Lines)
1. `resources/views/r21_project/project_report_print.blade.php` (~70 lines): Root report view using `<x-layouts.report-layout>`.
2. `resources/views/r21_project/partials/print/header.blade.php` (~60 lines): Shared institutional header & metadata box.
3. `resources/views/r21_project/partials/print/signatures.blade.php` (~50 lines): Shared statutory signatures block.
4. `resources/views/r21_project/partials/print/group-breakdown.blade.php` (~150 lines): Single page per group breakdown.
5. `resources/views/r21_project/partials/print/cia-register.blade.php` (~140 lines): CIA Clause 11.2.5 register.
6. `resources/views/r21_project/partials/print/sbte-submission.blade.php` (~140 lines): SBTE official statement.
7. `resources/views/r21_project/partials/print/ese-rubrics.blade.php` (~150 lines): ESE Clause 11.3.4 8-rubric score sheet.
8. `resources/views/r21_project/partials/print/consolidated.blade.php` (~160 lines): Consolidated broad register.

Total line count: ~920 lines (down from 1,025 lines in legacy monolith; maximum file length 160 lines).

## 6. Proposed Implementation Slices
- **Slice 1 (M2.3-IMPLEMENT-001): Report Layout Enhancement & Modular Print Templates**
  - Whitelist:
    - `carmel-linx-laravel/resources/views/components/layouts/report-layout.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/project_report_print.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/print/header.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/print/signatures.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/print/group-breakdown.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/print/cia-register.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/print/sbte-submission.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/print/ese-rubrics.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/print/consolidated.blade.php`
    - `carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php` (minimal `wantsJson` check)
  - Acceptance Criteria: All 5 report types render inside `<x-layouts.report-layout>`, all Blade files under 500 lines, zero syntax errors, `R21MajorProjectControllerTest` passes.
- **Slice 2 (M2.3-IMPLEMENT-002): Dedicated Print Report Feature Test & Verification**
  - Whitelist:
    - `carmel-linx-laravel/tests/Feature/R21MajorProjectPrintReportTest.php`
  - Acceptance Criteria: Feature tests asserting HTTP 200, correct titles, headers, data, and signatures for all 5 report types.

## 7. Recommended First Implementation Slice
Authorize **Slice 1 (M2.3-IMPLEMENT-001)**: Report Layout Enhancement & Modular Print Templates.
REPORT;

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: 66b3310dedc64a3e1067065bbc715a78846d37b5
- Active Execution State: ANALYZING (Unit M2.3)
- Completed Units (3/24): M1.1, M2.1, M2.2
- Test Suite Status: 29 tests passed (214 assertions), 0 failures, 0 regressions.
- Critical Files: User.php untouched.

{$detailedReport}
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.3',
    'ANALYZING',
    [
        'unit_title' => 'R21 Major Project Consolidated Print Report (M2.3)',
        'objective' => 'Perform a read-only architectural analysis of the R21 Major Project Consolidated Print Report and determine the concrete implementation slices required to migrate it into the existing CampusLynk report architecture.',
        'explicit_request' => "Review the read-only architectural analysis for task 'M2.3-ANALYZE-001'. Confirm whether the decomposition into 8 modular partials, Master Shell <x-layouts.report-layout> usage, and two-slice implementation plan are approved, and issue TASK_DISPATCH for Slice 1 (M2.3-IMPLEMENT-001). Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M2.2'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ],
            'task_report' => [
                'task_id' => 'M2.3-ANALYZE-001',
                'unit_id' => 'M2.3',
                'status' => 'COMPLETED',
                'summary' => 'Read-only architectural analysis completed for M2.3. Legacy 1,025-line print monolith decomposed into 8 modular Blade partials (< 160 lines each) extending <x-layouts.report-layout>. Covers all 5 statutory report types (group breakdown, CIA register, SBTE statement, ESE 8-rubrics, consolidated register). Reuses shared institutional header and signatures. Proposed 2-slice implementation plan with Slice 1 (M2.3-IMPLEMENT-001) as first concrete task.',
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

echo "Dispatching M2.3-ANALYZE-001 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
