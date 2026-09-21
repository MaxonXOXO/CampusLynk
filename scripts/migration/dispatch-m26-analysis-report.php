<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.6 Analysis & Role Dashboards Report to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$taskReport = [
    'task_id' => 'M2.6-ANALYZE-001',
    'unit_id' => 'M2.6',
    'status' => 'COMPLETED',
    'summary' => 'Completed read-only architectural analysis of M2.6 (R21 Drawing Hall Controller & Sheet Evaluation Backend) and comprehensive role dashboards migration architecture. Formulated legacy-to-target parity map, statutory SBTE Kerala Regulation 11.2.3 requirements, implementation slice decomposition, and an anti-duplication role dashboard strategy utilizing CampusLynk master shells (<x-layouts.faculty-shell>, <x-layouts.dashboard-layout>) and <x-ui.*> components.',
    'changes' => [
        'No repository files modified (read-only analysis)'
    ],
    'tests' => [
        'total' => 55,
        'passed' => 55,
        'failed' => 0,
        'regressions' => 0,
        'output' => 'Tests: 55 passed (416 assertions) in 2.95s. Zero regressions.'
    ],
    'issues' => [],
    'git' => [
        'branch' => 'migration-alpha',
        'commit' => '8186c67f463c9d06a50204a8754374d07caeae9c',
        'clean' => true
    ]
];

$detailedFindings = <<<'FINDINGS'
# M2.6 ARCHITECTURAL ANALYSIS & ROLE DASHBOARDS MIGRATION REPORT

## 1. M2.6 R21 DRAWING HALL FUNCTIONAL INVENTORY & STATUTORY REQUIREMENTS
Under Kerala SBTE Revision 2021 Regulation Clause 11.2.3, Engineering Drawing courses carry 50 Continuous Internal Assessment (CIA) marks and 100 End Semester Examination (ESE) marks:
- **Formative Assessment (Clause 11.2.3.b — 40% of CIA / 20 Marks)**:
  - Continuous evaluation of drawing sheets (minimum 2 sheets per module across 4 modules = 8 sheets).
  - Evaluation rubric: Timely Completion (50% / Max 50) + Appearance and Organization (50% / Max 50) = Total 100%.
  - Formative mark = (Average sheet score / 100) * 20.
- **Summative Assessment (Clause 11.2.3.a — 40% of CIA / 20 Marks)**:
  - Average of two Series Tests (Test 1 covering Modules I & II, Test 2 covering Modules III & IV).
  - Evaluation rubric: Procedure Drawing (40%) + Final Drawing (30%) + Dimensioning (20%) + Neatness (10%) = Total 100%.
  - Summative mark = (Average test score / 100) * 20.
- **Attendance & Performance (Clause 11.2.3.c — 20% of CIA / 10 Marks)**:
  - Computed from `student_attendance` with SBTE percentage slabs:
    - >= 90%: 10.0M (100%)
    - 80% to 89%: 8.0M (80%)
    - 75% to 79%: 6.0M (60%)
    - 70% to 74%: 4.0M (40%)
    - 65% to 69%: 2.0M (20%)
    - < 65%: 0.0M
  - Support for manual override marks with ceiling validation.
- **Consolidated CIA**:
  - Total CIA = Formative (20) + Summative (20) + Attendance (10) = Max 50.
  - Pass eligibility: Minimum 40% (20 / 50).
- **Course File & Lesson Plan**:
  - Auto-initializes 8 default sheets across 4 modules.
  - Auto-generates standard 30-day lesson plan if existing plans < 15.

## 2. LEGACY-TO-TARGET PARITY MAP FOR M2.6
- **Database Tables**:
  - `r21_drawing_course_files` (batch_subject_id, syllabus_pdf_path, credits 2.0, contact_hours 60, parsed_cos, parsed_modules, parsed_sheets, parsed_copo, parsed_textbooks, series_test_qps).
  - `r21_drawing_sheet_evaluations` (batch_subject_id, sheet_no, reg_no, timely_completion, appearance_organization, total_score_100, is_absent, remarks; unique key).
  - `r21_drawing_series_tests` (batch_subject_id, test_no, reg_no, procedure_drawing, final_drawing, dimensioning, neatness, total_score_100, is_absent, remarks; unique key).
  - `r21_drawing_attendance_evaluations` (batch_subject_id, reg_no, total_hours, attended_hours, attendance_percentage, attendance_mark, override_mark, final_attendance_mark; unique key).
- **Models**: `R21DrawingCourseFile`, `R21DrawingSheetEvaluation`, `R21DrawingSeriesTest`, `R21DrawingAttendanceEvaluation`.
- **Controller**: `R21VirtualClassroomDrawingController.php` with 9 endpoints:
  - `GET /r21/classroom/drawing/{subjectId}`
  - `POST /r21/classroom/drawing/{subjectId}/syllabus`
  - `POST /r21/classroom/drawing/{subjectId}/sheets/save`
  - `POST /r21/classroom/drawing/{subjectId}/tests/save`
  - `POST /r21/classroom/drawing/{subjectId}/attendance/save`
  - `GET /r21/classroom/drawing/{subjectId}/print/sheets` (for M2.7)
  - `GET /r21/classroom/drawing/{subjectId}/print/tests` (for M2.7)
  - `GET /r21/classroom/drawing/{subjectId}/print/cia` (for M2.7)
  - `GET /r21/classroom/drawing/{subjectId}/print/lesson-plan` (for M2.7)
- **Target Status**: Currently 0% in target (only R26 drawing exists).

## 3. PROPOSED M2.6 IMPLEMENTATION SLICES
- **Slice 1 (M2.6 Backend & Evaluation Endpoints)**:
  - Migration: `database/migrations/2026_09_22_000001_create_r21_drawing_tables.php`
  - Models: `app/Models/R21DrawingCourseFile.php`, `R21DrawingSheetEvaluation.php`, `R21DrawingSeriesTest.php`, `R21DrawingAttendanceEvaluation.php`
  - Controller: `app/Http/Controllers/R21VirtualClassroomDrawingController.php`
  - Routes: Register in `routes/web.php` within staff auth group
  - Tests: `tests/Feature/R21DrawingControllerTest.php` (covering course file, 30-day lesson plan, sheet evaluations, series test criteria, attendance slabs, overrides, consolidated CIA, error handling)
  - Whitelist: Only above files allowed; `app/Models/User.php` untouched.
- **Slice 2 (M2.7 Frontend Workspace & Print Templates)**:
  - Virtual drawing hall workspace mounted in `<x-layouts.workspace-layout>` decomposed into modular Blade partials (all < 500 lines).
  - 4 statutory print templates mounted in `<x-layouts.report-layout>`.

## 4. ROLE DASHBOARDS ARCHITECTURE & DUPLICATION INVENTORY
### Legacy Duplication Audit
- Legacy repository contains 20+ monolithic dashboard files totaling >2.5 MB of code:
  - `lecturer_dashboard.blade.php`: 464 KB
  - `hod_dashboard.blade.php`: 271 KB
  - `admin_control_desk.blade.php` / `principal_dashboard.blade.php`: 254 KB
  - `chairman_dashboard.blade.php`: 158 KB
  - `student_dashboard.blade.php`: 123 KB
  - `tutor_dashboard.blade.php`: 114 KB
  - `staff_mobile_dashboard.blade.php`: 78 KB
  - `hod_mobile_dashboard.blade.php`: 70 KB
  - `academic_coordinator_dashboard.blade.php`: 69 KB
  - `remedial_dashboard.blade.php`: 62 KB
  - `workshop_superintendent_dashboard.blade.php`: 58 KB
- Legacy Duplication Root Cause:
  - Repeated full HTML `<head>`, duplicated Google Fonts, raw CSS classes, copy-pasted sidebar navigation (15-20 links), repeated topbars, repeated profile/leave/attendance modals, and duplicated JS logic.

### CampusLynk Shared-Shell & Component Architecture
- **Master Shells**:
  1. `<x-layouts.faculty-shell>`: Unified shell for instructional staff (`Lecturer`, `HOD`, `Tutor`, `Demonstrator`, `Trade_Instructor`, `Workshop_Superintendent`, `Academic_Coordinator`). Automatically includes dynamic `<x-layout.sidebar>` and `<x-layout.topbar>`.
  2. `<x-layouts.dashboard-layout>`: Unified shell for executive/administrative roles (`Principal`, `Admin`, `Super_Admin`, `Chairman`) wrapping `<x-layouts.app-shell>` with breadcrumbs and action slots.
  3. `<x-layouts.app-shell>`: Base foundation with anti-FOUC sidebar state, bundled Lucide icons, and responsive canvas.
- **Component Reuse**:
  - Replace hundreds of lines of inline HTML with `<x-ui.card>`, `<x-ui.button>`, `<x-ui.modal>`, `<x-ui.table>`, `<x-ui.badge>`, `<x-ui.tabs>`, `<x-ui.icon>`.
- **Reference Implementations**:
  - `trade_instructor_dashboard.blade.php` (271 lines) and `demonstrator_dashboard.blade.php` (240 lines) demonstrate 95% code reduction using `<x-layout.sidebar role="trade_instructor">` and `<x-ui.*>`.

### Roles Discovered Across Legacy & Target
1. **Principal** (`/dashboard/principal`): Institutional approvals queue, student registrations, credential reset, department audits.
2. **HOD** (`/dashboard/hod`): Staff directory, batch allocations, department attendance overview, subject allocations, leave approval ledger.
3. **Lecturer / Faculty** (`/dashboard/lecturer`): Assigned classes, virtual classrooms, lesson plans, course files, attendance logging.
4. **Trade Instructor / Tradesman / Workshop Instructor** (`/dashboard/tradeinstructor`): Workshop practical batches, equipment records.
5. **Demonstrator / Laboratory Assistant** (`/dashboard/demonstrator`): Laboratory practical batches, lab rubrics.
6. **Tutor** (`/dashboard/tutor`): Ward roster, attendance consolidation, PTA progress reports, parent communications.
7. **Academic Coordinator / General Coordinator** (`/dashboard/academic-coordinator`): Curriculum coordination, aided/SF department supervision.
8. **Chairman** (`/dashboard/chairman`): Executive KPIs, faculty attendance roll, institutional metrics.
9. **Admin / Super Admin** (`/dashboard/admin`, `/dashboard/superadmin`): User accounts, system configuration, audit logs (`AuditLog`).
10. **Student** (`/dashboard/student`): Course registrations, attendance percentage, continuous assessment marks, fee status.
11. **Parent** (`/dashboard/parent`): Ward attendance, marks summaries, circulars.

### Control-Plane Representation & Dependency Graph Placement
- Register a dedicated feature `role_dashboards` in `STATE.json` / `FEATURE_MATRIX.md` decomposed into 4 modular units:
  - **M9.1**: Executive & Administrative Dashboards (`Principal`, `Admin`, `Super_Admin`, `Chairman`) via `<x-layouts.dashboard-layout>`.
  - **M9.2**: Faculty & Academic Leadership Dashboards (`HOD`, `Lecturer`, `Tutor`, `Academic_Coordinator`) via `<x-layouts.faculty-shell>`.
  - **M9.3**: Laboratory & Workshop Staff Dashboards (`Trade_Instructor`, `Demonstrator`, `Laboratory_Assistant`, `Workshop_Superintendent`) via `<x-layouts.faculty-shell>`.
  - **M9.4**: Student & Parent Portals (`Student`, `Parent`) via responsive modern shells.
- **Sequencing Recommendation**:
  - Complete `M2.6` (R21 Drawing Hall Backend) and `M2.7` (R21 Drawing Hall Frontend & Prints) to finish the R21 virtual classroom suite.
  - Formally incorporate `role_dashboards` (Units M9.1–M9.4) into `STATE.json` and `FEATURE_MATRIX.md` at the next checkpoint.
  - Proceed with M2.6 Slice 1 implementation immediately.

## 5. CONFIRMATION
This analysis made 0 modifications to application code, models, migrations, views, or database state. Working tree is clean.
FINDINGS;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.6',
    'ANALYZED',
    [
        'unit_title' => 'R21 Drawing Hall Controller & Sheet Evaluation Backend (M2.6)',
        'objective' => 'Report findings of read-only analysis M2.6-ANALYZE-001 and role dashboards architecture; request authorization to begin M2.6 Slice 1 implementation.',
        'explicit_request' => "Completed read-only analysis for M2.6 and role dashboards architecture. Zero repository changes made. All 55 tests pass. Requesting PLAN_APPROVED decision authorizing M2.6 Slice 1 (backend controller, models, migration, and dedicated feature test).",
        'context' => [
            'dependencies' => ['M1.1'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ],
            'task_report' => $taskReport
        ]
    ],
    $runId
);

echo "Dispatching M2.6 Analysis & Role Dashboards Report to ChatGPT via CDP bridge...\n";
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
