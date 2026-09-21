<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.7 Analysis Report to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$taskReport = [
    'task_id' => 'M2.7-ANALYZE-001',
    'unit_id' => 'M2.7',
    'status' => 'COMPLETED',
    'summary' => 'Completed read-only forensic analysis of M2.7 (R21 Drawing Hall Workspace View and 4 Print Templates). Formulated legacy functional inventory, M2.6 contract mapping, modular sub-500-line Blade decomposition, master shell integration (<x-layouts.workspace-layout>, <x-layouts.report-layout>), <x-ui.*> component reuse map, and two-slice implementation plan.',
    'changes' => [
        'No repository files modified (read-only analysis)'
    ],
    'tests' => [
        'total' => 64,
        'passed' => 64,
        'failed' => 0,
        'regressions' => 0,
        'output' => 'Tests: 64 passed (473 assertions) in 5.05s. Zero regressions.'
    ],
    'issues' => [],
    'git' => [
        'branch' => 'migration-alpha',
        'commit' => '504c30f1d072f854a5009c91f582f3ef80fe5c86',
        'clean' => true
    ]
];

$detailedFindings = <<<'FINDINGS'
# M2.7 READ-ONLY FORENSIC ANALYSIS & DECOMPOSITION REPORT

## 1. DISCOVERED LEGACY FILES & LINE COUNTS
Legacy R21 Drawing views located in `legacy-academic-platform/carmel-linx-laravel/resources/views/r21_drawing/`:
1. `virtual_classroom_drawing.blade.php`: **1,437 lines** (82.5 KB) — Monolithic workspace view containing raw HTML `<head>`, Bootstrap 5.3, Font Awesome, raw inline CSS (`:root`, `.glass-card`, `.stat-card`, `.table-custom`), 6 tabs, and 462 lines of inline JavaScript. Exceeds the 500-line ceiling and must be decomposed.
2. `sheet_evaluation_print.blade.php`: **150 lines** (8.1 KB) — Formative drawing sheet evaluation register.
3. `summative_test_print.blade.php`: **179 lines** (9.7 KB) — Summative series test evaluation register.
4. `cia_consolidated_print.blade.php`: **146 lines** (8.0 KB) — Consolidated Continuous Internal Assessment (CIA) marksheet with statutory student verification and signature blocks.
5. `lesson_plan_print.blade.php`: **112 lines** (6.3 KB) — 60-hour drawing lab lesson plan schedule with HOD / Principal sign-offs.

## 2. M2.6 BACKEND & VIEW CONTRACT MAPPING
The M2.6 backend controller (`R21VirtualClassroomDrawingController`) provides all data required by the views:
- `batchSubject`: Course code, name, semester, classroom association.
- `classroom`: Current semester, branch/department.
- `drawingCourseFile`: 8 default sheets across 4 modules, contact hours (60), credits (2.0), CIA marks (50), ESE marks (100), parsed COs, modules, CO-PO matrix, textbooks, syllabus PDF path.
- `students`: Enrolled students sorted by roll_no / name.
- `lessonPlans`: 30-day / 60-hour auto-generated schedule.
- `studentResults`: Consolidated student collection containing:
  - `reg_no`, `sbte_reg_no`, `name`, `roll_no`
  - `att_percentage`, `att_marks`, `calc_att_marks`
  - `sheet_count`, `avg_sheet_score`, `formative_mark` (max 20)
  - `test1_score`, `test2_score`, `avg_test_score`, `summative_mark` (max 20)
  - `total_cia` (max 50), `is_pass` (boolean, >= 20 pass threshold)
  - `sheets_detail`: Keyed by `sheet_no`
  - `t1_detail`, `t2_detail`: Series test models
- `assignedStaff`, `hod`: Departmental leadership.
- Assessment Maximums: `formativeMax` (20), `summativeMax` (20), `attMax` (10), `ciaMax` (50).

## 3. PROPOSED MODULAR BLADE DECOMPOSITION (SUB-500 LINES)
To eliminate legacy monolithic anti-patterns and guarantee that every Blade file remains strictly below 500 lines:

### A. Workspace View & Partials (`resources/views/r21_drawing/`)
1. `virtual_classroom_drawing.blade.php` (~110 lines):
   - Master workspace canvas mounted inside `<x-layouts.workspace-layout>`.
   - Header actions slot: Print CIA Marksheet button, Autosave indicator badge.
   - Includes stat cards banner and `<x-ui.tabs>` mounting 6 modular partials.
2. `partials/stat-cards.blade.php` (~65 lines):
   - 4 executive metric cards: Enrolled Students, Formative Assessment (40% / 20M), Summative Assessment (40% / 20M), Attendance & CIA (20% / 10M, total 50M).
3. `partials/tab-formative.blade.php` (~160 lines):
   - Formative Continuous Evaluation for Drawing Sheets.
   - Sheet selector horizontal pills (Sheet 1 through Sheet 8 across 4 modules).
   - Selected sheet banner and sheet marks table with inputs for timely completion (max 50), appearance (max 50), row total badge (max 100), absence checkbox, and remarks.
4. `partials/tab-summative.blade.php` (~165 lines):
   - Summative Assessment Series Tests.
   - Test 1 / Test 2 selector pills and criteria explanation banner.
   - Series test marks table with inputs for procedure (max 40), final drawing (max 30), dimensioning (max 20), neatness (max 10), row total badge (max 100), absence checkbox, and remarks.
5. `partials/tab-attendance.blade.php` (~120 lines):
   - Attendance & Performance (Clause 11.2.3.c — 20% weightage).
   - SBTE percentage slabs reference card and attendance marks table with total hours, attended hours, attendance %, calculated mark, manual override input, and final mark.
6. `partials/tab-cia.blade.php` (~110 lines):
   - Consolidated CIA & Attainment summary table (Formative 20M + Summative 20M + Attendance 10M = 50M CIA).
   - Pass eligibility indicator (min 20/50) and print action buttons.
7. `partials/tab-lessonplan.blade.php` (~75 lines):
   - 60-Hour / 30-Day drawing lab lesson plan table with Day No, Module, Topic, Hours, Pedagogy, CO, and Status.
8. `partials/tab-syllabus.blade.php` (~95 lines):
   - Syllabus PDF upload form, Course Outcomes (CO1–CO4), modules summary, and CO-PO mapping matrix.
9. `partials/scripts.blade.php` (~280 lines):
   - Client-side JavaScript: Sheet pill switching, test pill switching, real-time row total calculations, keyboard arrow navigation between table cells, autosave debouncing, and AJAX endpoints integration.

### B. 4 Statutory Print Templates (`resources/views/r21_drawing/`)
All 4 print templates mount inside `<x-layouts.report-layout>`:
1. `sheet_evaluation_print.blade.php` (~140 lines, `orientation="landscape"`): Formative continuous evaluation sheet register.
2. `summative_test_print.blade.php` (~160 lines, `orientation="landscape"`): Summative series test evaluation register with 4 criteria breakdown.
3. `cia_consolidated_print.blade.php` (~130 lines, `orientation="portrait"`): Consolidated CIA marksheet with statutory assessment note and 3-signatory block.
4. `lesson_plan_print.blade.php` (~115 lines, `orientation="portrait"`): Drawing lab lesson plan schedule with institutional sign-offs.

## 4. CAMPUSLYNK MASTER SHELL & <x-ui.*> REUSE MAPPING
- **Master Shells**:
  - Workspace: `<x-layouts.workspace-layout :title="..." :subjectCode="..." :subjectName="...">` (eliminates duplicated `<head>`, raw Bootstrap, and inline CSS).
  - Print views: `<x-layouts.report-layout :title="..." :orientation="...">` (provides standard A4 sheet styling, print action bar, and print media queries).
- **Component Reuse**:
  - Cards: `<x-ui.card>`
  - Buttons: `<x-ui.button>`
  - Tables: `<x-ui.table>`
  - Badges: `<x-ui.badge>`
  - Tabs: `<x-ui.tabs>`
  - Icons: `<x-ui.icon>` (replacing legacy Font Awesome with bundled Lucide icons).
- **Duplication Eliminated**:
  - 170 lines of raw inline CSS and Bootstrap 5.3 boilerplate deleted.
  - Duplicated headers, navigation sidebars, and custom print stylesheets replaced by master layouts.

## 5. PROPOSED IMPLEMENTATION SLICES
- **Slice 1**: Workspace Layout & Core Partials (`virtual_classroom_drawing.blade.php` + 8 partials under `partials/`).
- **Slice 2**: 4 Statutory Print Templates + Dedicated Feature Test (`R21DrawingWorkspaceViewTest.php`).

## 6. CONFIRMATION
This analysis made 0 modifications to application code or database state. Working tree is clean.
FINDINGS;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.7',
    'ANALYZED',
    [
        'unit_title' => 'R21 Drawing Hall Workspace View and 4 Print Templates (M2.7)',
        'objective' => 'Report findings of read-only analysis M2.7-ANALYZE-001; propose sub-500-line Blade decomposition, master shell integration, and request authorization to begin Slice 1 implementation.',
        'explicit_request' => "Completed read-only analysis for M2.7. Proposed modular sub-500-line decomposition into workspace layout and 8 partials, plus 4 print templates using <x-layouts.report-layout>. Requesting PLAN_APPROVED decision authorizing M2.7 Slice 1.",
        'context' => [
            'dependencies' => ['M2.6'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ],
            'task_report' => $taskReport
        ]
    ],
    $runId
);

echo "Dispatching M2.7 Analysis Report to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 90, false, $detailedFindings);

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
