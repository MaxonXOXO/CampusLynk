<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.5-ANALYZE-001 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$currentCommit = trim(shell_exec('git -C ' . escapeshellarg($baseDir) . ' rev-parse HEAD') ?? 'd239427b');

$detailedReport = <<<'REPORT'
# M2.5 READ-ONLY ARCHITECTURAL & UI MODERNIZATION ANALYSIS REPORT (Task: M2.5-ANALYZE-001)

## 1. Legacy Seminar Files Inspected (Read-Only)
- `legacy-academic-platform/carmel-linx-laravel/resources/views/r21_seminar/virtual_classroom_seminar.blade.php` (2,171 lines, 142 KB monolithic Blade view).
- `legacy-academic-platform/carmel-linx-laravel/resources/views/r21_seminar/seminar_report_print.blade.php` (680 lines, 31 KB monolithic print view).
- Target Layout Shells:
  - `carmel-linx-laravel/resources/views/components/layouts/workspace-layout.blade.php` (32 lines, master virtual classroom shell).
  - `carmel-linx-laravel/resources/views/components/layouts/report-layout.blade.php` (86 lines, master institutional print report shell with orientation prop).
- Target Component Inventory: `carmel-linx-laravel/resources/views/components/ui/` (`button`, `card`, `modal`, `table`, `badge`, `tabs`, `input`, `select`, `icon`, `progress`, `alert`).
- Target Controller & Routes: `R21VirtualClassroomSeminarController.php` (all 6 statutory endpoints implemented and verified in M2.4).

## 2. Complete Functional Inventory (5 Tabs, 5 Modals, 3 Print Modes)
### A. Virtual Classroom Workspace
1. **Header & Context:**
   - Regulation badges (`R-2021 Regulation`, `Clause 11.2.6 Assessment Rubrics`), subject code/name, department, semester.
   - Scheme summary: 100% CIE = 75 Marks Total (67.5M Academic + 7.5M Attendance).
   - Header actions: Attendance Log link (`/staff/attendance-log`), Print Register launcher (`/r21/classroom/seminar/{subjectId}/print?type=consolidated`), Syllabus upload button (`openSyllabusModal()`), Back button (`/dashboard`).
2. **Stats Quick Strip:**
   - 5 metrics cards: Enrolled Students, Evaluated Count, Pending Count, Class Average (75M), Current Assessor Indicator.
3. **Workspace Tabs (5 Tabs):**
   - `tab-evaluation` (Seminar Evaluation Register - 75 Marks):
     - Rubric Guide Banner (Relevance 7.5M, Literature 7.5M, Presentation 37.5M, Interaction 7.5M, Report 7.5M, Attendance 7.5M).
     - Search and batch filters (All, Batch 1, Batch 2, Unassigned).
     - Student Table: Roll, Reg No, Name, Batch, Seminar Topic & Guide, 6 Rubric scores, My Score, Committee Average (with clickable breakdown modal), SBTE Grade, Action ("Evaluate" / "Re-evaluate").
   - `tab-schedule` (Seminar Schedule & Topic Log):
     - Schedule Table: Roll, Reg No, Name, Presentation Date, Approved Seminar Topic, Guide, Status, Action ("Edit Schedule").
   - `tab-consolidated` (Consolidated Register & Grade Distribution):
     - SBTE 9-point grade distribution cards (S, A, B, C, D, E, F) with counts and percentages.
     - Class statistics: Pass Rate, Highest, Lowest, Class Average.
     - Consolidated Table: Roll, Reg No, Name, Seminar Component (67.5M), Attendance Component (7.5M), Total (75M), Score in Words, SBTE Grade & Point, Result badge.
   - `tab-attainment` (NBA CO Attainment CO1–CO3):
     - Attainment matrix table: CO Tag, Description, CIE Assessed, CIE Met %, CIE Level, Direct Attainment (100% CIE), Indirect Attainment, Overall Attainment.
     - Course Exit Survey status, response count, survey initiation & report links.
   - `tab-rubrics` (Regulation Clause 11.2.6 Reference Guide):
     - Static reference documentation detailing assessment rubrics, attendance slabs, committee assessment rules, and grading scale.
4. **Modals Container (5 Modals):**
   - `evaluationModal`: Individual student 6-rubric evaluation, live score calculator, suggested attendance indicator, topic/guide/presentation_date editing, assessor selector.
   - `scheduleModal`: Quick topic, guide, and presentation date assignment.
   - `breakdownModal`: Committee multi-assessor score table showing individual faculty marks and average.
   - `syllabusModal`: Syllabus PDF upload and viewer link.
   - `batchModal`: Lab Batch A/B splitting assignment.

### B. Consolidated Print Report (3 Modes)
1. `consolidated`: Consolidated 6-rubric detailed evaluation register (Clause 11.2.6).
2. `cia_submission`: Official SBTE final CIA mark entry statement (75M) with grade statistics and statutory certification declaration.
3. `schedule`: Seminar presentation schedule & topic log.
4. Shared Print Partials: Institutional header (`partials/print/header.blade.php`) and statutory signature blocks (`partials/print/signatures.blade.php`).

## 3. Backend & Route Reconciliations (M2.4 Parity)
All 6 endpoints implemented in M2.4 are directly consumed by the views:
- `GET  /r21/classroom/seminar/{subjectId}` -> renders workspace or returns JSON.
- `POST /r21/classroom/seminar/{subjectId}/syllabus` -> syllabus upload.
- `POST /r21/classroom/seminar/{subjectId}/evaluate` -> save evaluation.
- `POST /r21/classroom/seminar/{subjectId}/schedule` -> update schedule.
- `GET  /r21/classroom/seminar/{subjectId}/print` -> renders print report (modes: `consolidated`, `cia_submission`, `schedule`).
- `GET  /r21/classroom/seminar/{subjectId}/attainment-summary` -> fetches attainment matrix.
- External link: `/staff/attendance-log?subject_id=...` preserved.

## 4. UI Element to CampusLynk Global Component Mapping
- Master Shells:
  - Virtual Classroom: `<x-layouts.workspace-layout>` with `<x-slot:headerActions>`.
  - Print View: `<x-layouts.report-layout>` with `orientation="landscape"`.
- Global UI Components:
  - Tables: `<x-ui.table>` with standard header configurations.
  - Modals: `<x-ui.modal id="..." title="..." maxWidth="...">`.
  - Cards & Metric Strips: `<x-ui.card>`.
  - Tabs: Tab navigation bar styled with design tokens and icons.
  - Buttons: `<x-ui.button variant="primary|secondary|danger">`.
  - Inputs & Selects: `<x-ui.input>` and `<x-ui.select>`.
  - Badges: `<x-ui.badge variant="success|warning|danger|info|neutral">`.
  - Icons: `<x-ui.icon name="...">`.
- Anti-Duplication: Zero raw HTML buttons, zero inline modals, zero custom duplicate CSS.

## 5. Sub-500-Line Modular Decomposition Plan
Both monolithic legacy views (2,171 lines and 680 lines) will be decomposed into 15 modular Blade files, all strictly under 350 lines:
1. `resources/views/r21_seminar/virtual_classroom_seminar.blade.php` (~100 lines) — Main workspace shell.
2. `resources/views/r21_seminar/partials/stats-strip.blade.php` (~60 lines) — Quick metrics strip.
3. `resources/views/r21_seminar/partials/tab-evaluation.blade.php` (~180 lines) — 6-rubric evaluation table.
4. `resources/views/r21_seminar/partials/tab-schedule.blade.php` (~120 lines) — Seminar schedule table.
5. `resources/views/r21_seminar/partials/tab-consolidated.blade.php` (~170 lines) — Consolidated table & grade cards.
6. `resources/views/r21_seminar/partials/tab-attainment.blade.php` (~150 lines) — Attainment matrix table.
7. `resources/views/r21_seminar/partials/tab-rubrics.blade.php` (~110 lines) — Statutory reference guide.
8. `resources/views/r21_seminar/partials/modals.blade.php` (~280 lines) — 5 modals using `<x-ui.modal>`.
9. `resources/views/r21_seminar/partials/scripts.blade.php` (~320 lines) — Client JS, live calculation, AJAX handlers.
10. `resources/views/r21_seminar/seminar_report_print.blade.php` (~40 lines) — Main print report shell.
11. `resources/views/r21_seminar/partials/print/header.blade.php` (~40 lines) — Institutional print header.
12. `resources/views/r21_seminar/partials/print/signatures.blade.php` (~35 lines) — Statutory signatures block.
13. `resources/views/r21_seminar/partials/print/consolidated.blade.php` (~130 lines) — 6-rubric print template.
14. `resources/views/r21_seminar/partials/print/cia-submission.blade.php` (~130 lines) — SBTE final statement template.
15. `resources/views/r21_seminar/partials/print/schedule.blade.php` (~90 lines) — Schedule & topic print template.

## 6. Proposed Implementation Slices
- **Slice 1 (M2.5-IMPLEMENT-001): Master Shells & Modular Blade Partials Structure**
  - Scope: Implement the complete 15-file Blade architecture under `resources/views/r21_seminar/` mounting `<x-layouts.workspace-layout>` and `<x-layouts.report-layout>`, fully reusing `<x-ui.*>` components, strictly adhering to the sub-500-line ceiling.
  - Whitelist: Exactly the 15 Blade view files under `carmel-linx-laravel/resources/views/r21_seminar/`.
  - Acceptance Criteria: Master shells render cleanly, all tabs/modals/print templates structured, zero syntax errors (`php -l`), full test suite passes without regressions.
- **Slice 2 (M2.5-IMPLEMENT-002): Dedicated Feature Test & Verification**
  - Scope: Create dedicated feature test `carmel-linx-laravel/tests/Feature/R21SeminarWorkspaceViewTest.php` covering workspace rendering, layout mounting, tabs, modals, print view modes, and data binding.
  - Whitelist: `carmel-linx-laravel/tests/Feature/R21SeminarWorkspaceViewTest.php`.
  - Acceptance Criteria: Dedicated tests pass, full regression suite passes (47+ tests, 350+ assertions, 0 failures).

## 7. Recommended Next Action
Authorize **Slice 1 (M2.5-IMPLEMENT-001)**: Master Shells & Modular Blade Partials Structure.
REPORT;

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: {$currentCommit}
- Active Execution State: ANALYZING (Unit M2.5)
- Completed Units (5/24): M1.1, M2.1, M2.2, M2.3, M2.4
- Test Suite Status: 47 tests passed (350 assertions), 0 failures, 0 regressions in 3.69s.
- Safety Boundary: `app/Models/User.php` untouched. Zero DB schema changes.

## DESIGN SYSTEM MANDATE & ANTI-DUPLICATION
- Workspace MUST mount inside `<x-layouts.workspace-layout>`.
- Print view MUST mount inside `<x-layouts.report-layout>`.
- All tables must use `<x-ui.table>`, modals `<x-ui.modal>`, cards `<x-ui.card>`, buttons `<x-ui.button>`, badges `<x-ui.badge>`, inputs `<x-ui.input>`, selects `<x-ui.select>`.
- Every Blade view/partial MUST remain strictly below 500 lines. Monoliths must be decomposed into modular partials.
- Zero raw HTML buttons, zero inline modals, zero custom duplicate CSS.

{$detailedReport}
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.5',
    'ANALYZING',
    [
        'unit_title' => 'R21 Seminar Workspace View and Print Summary (M2.5)',
        'objective' => 'Perform a read-only architectural analysis of the R21 Seminar frontend workspace and print summary migration and produce an implementation-ready decomposition that preserves CampusLynk existing design system while replacing the legacy 2,171-line and 680-line monolithic views.',
        'explicit_request' => "Review the read-only architectural analysis for task 'M2.5-ANALYZE-001'. Confirm whether the decomposition, design system compliance, component mappings, sub-500-line modular structure, and two-slice implementation plan are approved, and issue TASK_DISPATCH for Slice 1 (M2.5-IMPLEMENT-001). Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M2.4'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ],
            'task_report' => [
                'task_id' => 'M2.5-ANALYZE-001',
                'unit_id' => 'M2.5',
                'status' => 'COMPLETED',
                'summary' => 'Read-only architectural analysis completed for M2.5. Legacy 2,171-line classroom view and 680-line print view mapped into 5 functional tabs, 5 modals, and 3 print modes. 100% mapped to existing <x-ui.*> components, <x-layouts.workspace-layout>, and <x-layouts.report-layout>. Decomposed into 15 modular Blade files, all strictly under 350 lines. All 6 endpoints reconciled against M2.4. Proposed 2-slice implementation plan with Slice 1 (M2.5-IMPLEMENT-001) as first concrete task.',
                'changes' => [],
                'tests' => [
                    'syntax' => 'N/A (Read-only analysis)',
                    'regressions' => 'Zero changes made to application code or database'
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

echo "Dispatching M2.5-ANALYZE-001 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
