<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.2-ANALYZE-001 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$stateFile = $baseDir . '/docs/migration/STATE.json';
$featureMatrixFile = $baseDir . '/docs/migration/FEATURE_MATRIX.md';
$designSystemFile = $baseDir . '/docs/migration/DESIGN_SYSTEM.md';

$stateJson = file_get_contents($stateFile);
$featureMatrixMd = file_get_contents($featureMatrixFile);
$designSystemMd = file_exists($designSystemFile) ? file_get_contents($designSystemFile) : '';

$detailedReport = <<<REPORT
# M2.2 READ-ONLY ARCHITECTURAL & UI MODERNIZATION ANALYSIS REPORT (Task: M2.2-ANALYZE-001)

## 1. Legacy Workspace Files Inspected
- `legacy-academic-platform/carmel-linx-laravel/resources/views/r21_project/virtual_classroom_project.blade.php` (2,612 lines, 182 KB monolithic Blade view).
- Target Layout Shell: `carmel-linx-laravel/resources/views/components/layouts/workspace-layout.blade.php` (32 lines).
- Target Component Inventory: `carmel-linx-laravel/resources/views/components/ui/` (`table`, `modal`, `card`, `tabs`, `button`, `badge`, `input`, `select`, `icon`, `progress`, `alert`).
- Target Controller & Routes: `R21VirtualClassroomMajorProjectController.php` (11 registered endpoints in M2.1).

## 2. Complete Functional-Section Inventory (7 Tabs & 6 Modals)
1. **Header & Context:** Regulation badges (`R-2021 Regulation`, `Clause 11.2.5 & 11.3.4`), subject name/code, department, semester, marks summary (CIA 75M | ESE 50M | Total 125M), header action controls (Attendance Log link, Project Groups setup, Print Reports dropdown, Back button).
2. **Stats Quick Strip:** 6 metrics cards: Enrolled Students, Evaluated Count, Pending Count, Passed Count, Average CIA (75M), Average ESE (50M).
3. **Tab Navigation Bar:** 7 tabs with active state indicators:
   - `tab-register`: Master Marksheet / Register
   - `tab-cia`: CIA Evaluation (75M)
   - `tab-ese`: ESE 8-Rubrics Assessment (50M)
   - `tab-reports`: Consolidated Reports & Print Links
   - `tab-groups`: Project Groups Allocation & Guide Assignment
   - `tab-attainment`: NBA Course Outcome (CO) Attainment & Exit Survey
   - `tab-rubrics`: SBTE Regulation Clause & Rubrics Reference Guide
4. **Master Marksheet Tab (`tab-register`):** Search/filter input, Table 1 with columns: Roll/Reg No, Student Name, Group, Project Title, Diary (30M), Dept Eval (30M), Attd (15M), CIA Total (75M), ESE Total (50M), ESE Grade, Grand Total (125M), Final Grade, Result (Pass/Fail badge), Actions (Evaluate button).
5. **CIA Evaluation Tab (`tab-cia`):** Batch actions (Group CIA button, Attendance sync status), Table 2 with columns: Roll/Reg No, Student Name, Attendance %, Attd Mark (15M), Weekly Diary (30M), Dept Review (30M), CIA Total (75M), CIA Grade, Status badge, Action (Evaluate CIA button).
6. **ESE 8-Rubrics Tab (`tab-ese`):** Examiners Panel launcher, Group ESE button, Table 3 with columns: Roll/Reg No, Student Name, 8 individual rubrics (Proto 10M, Tools 5M, Pres 7.5M, Inno 2.5M, Viva 7.5M, Indiv 7.5M, Grp 5M, Rep 5M), ESE Total (50M), ESE Grade, Status, Action (Evaluate Rubrics button).
7. **Consolidated Reports Tab (`tab-reports`):** 5 report cards with direct print launchers, Table 4 showing the comprehensive multi-column roll-wise broad register with full CIA + ESE breakdown.
8. **Project Groups Tab (`tab-groups`):** Group creation / edition interface, Add Group button, Group card list with Title, Guide selector, Member checkboxes/badges, Save Groups button, Delete group confirmation.
9. **CO Attainment Tab (`tab-attainment`):** Direct/Indirect attainment calculation summary, Exit Survey initiation & URL copy controls, Table 5 showing CO Tag, CO Description, Assessment status, CIE Met %, CIE Level, ESE Level, Direct Attainment (30:70), Indirect Attainment, Overall Attainment (80:20), NBA Rating.
10. **Regulation Rubrics Tab (`tab-rubrics`):** Static reference documentation explaining Clause 11.2.5 (CIA 75M formula) and Clause 11.3.4 (ESE 8-rubric scoring system).
11. **Modals Container:** 6 modal dialogs:
    - `ciaEvalModal`: Individual student CIA evaluation (Diary, Dept, Attd, live totals, grade calculation).
    - `groupCiaModal`: Batch apply common Diary & Dept marks to an entire project group.
    - `evalModal`: Comprehensive evaluation modal (CIA + direct ESE marks/grades).
    - `groupEseModal`: Batch apply 8 ESE rubrics to an entire project group.
    - `examinersModal`: Configure Internal and External Examiner details (Name, Designation, Institution, Exam Date).
    - `modalDeleteGroupConfirm`: Delete confirmation modal for project groups.

## 3. Legacy Endpoint / AJAX Reconciliation
All 11 endpoints are fully covered by M2.1:
- `POST /r21/classroom/project/{subjectId}/save-evaluation` -> `saveEvaluation()`
- `POST /r21/classroom/project/{subjectId}/group-cia` -> `saveGroupCia()`
- `POST /r21/classroom/project/{subjectId}/save-groups` -> `saveGroups()`
- `POST /r21/classroom/project/{subjectId}/examiners` -> `saveExaminers()`
- `POST /r21/classroom/project/{subjectId}/group-ese` -> `saveGroupEse()`
- `GET  /r21/classroom/project/{subjectId}/attainment-summary` -> `getAttainmentSummary()`
- `GET  /r21/classroom/project/{subjectId}/report/print` -> `printReport()`
- `POST /api/classroom/{subjectId}/course-exit/initiate` -> `CourseExitSurveyController::initiateSurvey` (already in `routes/web.php:799`)
- `POST /api/classroom/{subjectId}/course-exit/close` -> `CourseExitSurveyController::closeSurvey` (already in `routes/web.php:800`)
- External `/staff/attendance-log` link preserved as an anchor tag.

## 4. UI Element to CampusLynk `<x-ui.*>` Component Mapping
- Master Shell: `<x-layouts.workspace-layout title="..." subjectCode="..." subjectName="..." activeNav="academics">` with `<x-slot:headerActions>` for attendance link, group setup, print dropdown, and back button.
- Tables: Replaced with `<x-ui.table :headers="[...]">`.
- Modals: All 6 modals replaced with `<x-ui.modal id="..." title="..." maxWidth="...">`.
- Cards: Stats cards and report cards replaced with `<x-ui.card>`.
- Tabs: Tab bar replaced with `<x-ui.tabs>`.
- Buttons: Replaced with `<x-ui.button variant="primary|secondary|danger">`.
- Form Controls: Replaced with `<x-ui.input>` and `<x-ui.select>`.
- Badges: Replaced with `<x-ui.badge variant="success|warning|danger|info|neutral">`.
- Icons: Replaced with `<x-ui.icon name="...">`.
- Zero raw HTML buttons, zero inline modals, zero custom `<style>` blocks.

## 5. Monolith Decomposition (No File Exceeds 500 Lines)
1. `resources/views/r21_project/virtual_classroom_project.blade.php` (~120 lines): Root workspace extending `<x-layouts.workspace-layout>`.
2. `resources/views/r21_project/partials/stats-strip.blade.php` (~70 lines): Quick stats strip.
3. `resources/views/r21_project/partials/tab-register.blade.php` (~180 lines): Master marksheet table.
4. `resources/views/r21_project/partials/tab-cia.blade.php` (~150 lines): CIA 75M evaluation table.
5. `resources/views/r21_project/partials/tab-ese.blade.php` (~180 lines): ESE 50M 8-rubric table.
6. `resources/views/r21_project/partials/tab-reports.blade.php` (~220 lines): Consolidated reports & print launchers.
7. `resources/views/r21_project/partials/tab-groups.blade.php` (~190 lines): Project groups & guide allocation.
8. `resources/views/r21_project/partials/tab-attainment.blade.php` (~240 lines): CO attainment & exit survey.
9. `resources/views/r21_project/partials/tab-rubrics.blade.php` (~160 lines): Regulation & rubrics reference.
10. `resources/views/r21_project/partials/modals.blade.php` (~380 lines): 6 modals using `<x-ui.modal>`.
11. `resources/views/r21_project/partials/scripts.blade.php` (~420 lines): Client-side JS, calculation routines, AJAX handlers.

## 6. Proposed Implementation Slices
- **Slice 1 (M2.2-IMPLEMENT-001): Master Shell & Modular Blade Partials Structure**
  - Whitelist (9 files):
    - `carmel-linx-laravel/resources/views/r21_project/virtual_classroom_project.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/stats-strip.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/tab-register.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/tab-cia.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/tab-ese.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/tab-reports.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/tab-groups.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/tab-attainment.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/tab-rubrics.blade.php`
  - Acceptance Criteria: Master shell renders correctly, all 7 tabs structured with `<x-ui.*>`, no view exceeds 500 lines, zero syntax errors (`php -l`), full test suite passes without regressions.
- **Slice 2 (M2.2-IMPLEMENT-002): Modals, Interactivity & AJAX Bindings**
  - Whitelist (3 files):
    - `carmel-linx-laravel/resources/views/r21_project/partials/modals.blade.php`
    - `carmel-linx-laravel/resources/views/r21_project/partials/scripts.blade.php`
    - `carmel-linx-laravel/tests/Feature/R21MajorProjectWorkspaceViewTest.php`
  - Acceptance Criteria: All 6 modals functional with `<x-ui.modal>`, client-side calculations and AJAX requests operational, dedicated feature test passing.

## 7. Recommended First Implementation Slice
Authorize **Slice 1 (M2.2-IMPLEMENT-001)**: Master Shell & Modular Blade Partials Structure.
REPORT;

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: da44888c716a59a7e3f4381ce4297094d106ec40
- Active Execution State: ANALYZING (Unit M2.2)
- Completed Units:
  - M1.1: Database Schema Migrations and Eloquent Models Parity (commit 06f68cc6)
  - M2.1: R21 Major Project Backend Controller and Evaluation Endpoints (commit da44888c)
- Test Suite Status: 24 tests passed (165 assertions), 0 failures, 0 regressions.

## DESIGN SYSTEM MANDATE & ANTI-DUPLICATION
- Workspace MUST mount inside `<x-layouts.workspace-layout>`.
- All tables must use `<x-ui.table>`, modals `<x-ui.modal>`, cards `<x-ui.card>`, buttons `<x-ui.button>`, tabs `<x-ui.tabs>`, badges `<x-ui.badge>`, inputs `<x-ui.input>`, selects `<x-ui.select>`.
- No single Blade view may exceed 500 lines. Monolith must be decomposed into modular partials.
- Zero raw HTML buttons, zero inline modals, zero custom duplicate CSS.

{$detailedReport}
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.2',
    'ANALYZING',
    [
        'unit_title' => 'R21 Major Project Frontend Workspace Layout & UI Modernization (M2.2)',
        'objective' => 'Perform a read-only architectural analysis of the R21 Major Project frontend workspace migration and produce an implementation-ready decomposition that preserves CampusLynk existing design system while replacing the legacy 2,611-line monolithic workspace.',
        'explicit_request' => "Review the read-only architectural analysis for task 'M2.2-ANALYZE-001'. Confirm whether the decomposition, design system compliance, component mappings, and two-slice implementation plan are approved, and issue TASK_DISPATCH for Slice 1 (M2.2-IMPLEMENT-001). Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M2.1'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ],
            'task_report' => [
                'task_id' => 'M2.2-ANALYZE-001',
                'unit_id' => 'M2.2',
                'status' => 'COMPLETED',
                'summary' => 'Read-only architectural analysis completed for M2.2. Legacy 2,611-line view mapped into 7 functional tabs and 6 modals. 100% mapped to existing <x-ui.*> components and <x-layouts.workspace-layout>. Decomposed into 11 modular Blade files, all under 500 lines. All 11 AJAX endpoints reconciled against M2.1. Proposed 2-slice implementation plan with Slice 1 (M2.2-IMPLEMENT-001) as first concrete task.',
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

echo "Dispatching M2.2-ANALYZE-001 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
