# CAMPUSLYNK — HOD REPORT CENTRE
## FULL SECTION FORENSIC AUDIT & MIGRATION PREPARATION REPORT
**Date:** August 22, 2026  
**Auditor:** DeepMind Antigravity AI Engineering Suite  
**Status:** COMPLETE FORENSIC AUDIT (READ-ONLY — ZERO PRODUCTION CODE MODIFICATIONS)  
**Target Domain:** Head of Department (HOD) Report Centre & Institutional Reporting Engine  

---

## 1. Executive Summary

The **HOD Report Centre** (`/hod/report-centre`) serves as the analytical reporting hub for Department Heads across Carmel Polytechnic College. It enables HODs to compile academic coverage metrics, attendance shortage rosters, condonation candidates, remedial coaching outcomes, faculty teaching workload distributions, departmental timetables, extracurricular activity point audits, course file compliance registries, academic calendar planning, and SBTE/NBA accreditation documentation.

### Key Forensic Findings:
1. **Current Architectural State:** The HOD Report Centre is currently implemented as a **standalone legacy Blade view** (`resources/views/hod_report_centre.blade.php`), styled with legacy dark theme (`bg-slate-950`), runtime `@tailwindcss/browser@4` CDN, and direct Google Fonts stylesheets.
2. **Navigation Isolation:** In the HOD sidebar navigation (`config/navigation/hod.php`), the `report_centre` item points to an external full-page URL (`/hod/report-centre`) rather than utilizing client-side panel switching (`handleHodSidebarNav('report_centre')`), creating a disjointed user experience compared to Batch Management, User Directory, Subject Allocation, Leave Ledger, and Professional Activities.
3. **Multi-Tier Output Pipeline:** The reporting engine consists of **11 distinct report modules** that follow a two-step pattern:
   - **Step 1 (Filter & Scope Selection):** User selects a target batch, semester, or report scope via interactive modal dialogs or sub-panels.
   - **Step 2 (A4 Document Generation):** The system generates high-fidelity printable HTML/PDF views (`window.print()`) pre-configured with CSS `@page { size: A4 ... }` in portrait or landscape orientations.
4. **Zero Data Disruption Principle:** The underlying data aggregation pipelines in `routes/web.php`, `AcademicCalendarController.php`, `RemedialController.php`, `StaffLeaveController.php`, and `MentoringController.php` are fully functional and compute complex relational metrics across 21 database tables and JSON timetable files. **All backend controllers, routes, and data aggregation logic must remain 100% preserved.**

---

## 2. Complete Report Centre File Inventory

| File Path | Role / Purpose | Type | Current Status / Styling |
|:---|:---|:---|:---|
| [`config/navigation/hod.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/config/navigation/hod.php) | HOD sidebar navigation configuration | Config | Active; links `report_centre` to `/hod/report-centre` |
| [`routes/web.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/routes/web.php) | Route definitions & closure data aggregators | Routes | Active; contains 18 HOD reporting and print routes |
| [`app/Http/Controllers/AcademicCalendarController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/AcademicCalendarController.php) | Academic calendar CRUD, AI PDF extraction | Controller | Production Active |
| [`app/Http/Controllers/RemedialController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/RemedialController.php) | Remedial coaching reports & diagnostics | Controller | Production Active |
| [`app/Http/Controllers/StaffLeaveController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/StaffLeaveController.php) | Staff leave master ledger & PDF generator | Controller | Production Active |
| [`app/Http/Controllers/MentoringController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/MentoringController.php) | Student mentoring diary & condonation prints | Controller | Production Active |
| [`app/Http/Controllers/AttendanceController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/AttendanceController.php) | Class log attendance reporting endpoints | Controller | Production Active |
| [`resources/views/hod_report_centre.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_report_centre.blade.php) | Primary HOD Report Centre catalog & modals | Blade View | Legacy Dark UI, Tailwind CDN |
| [`resources/views/hod_workload_panel.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_workload_panel.blade.php) | Faculty workload & timetable printer sub-desk | Blade View | Legacy Dark UI, Tailwind CDN |
| [`resources/views/hod_attendance_summary_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_attendance_summary_print.blade.php) | Printable A4 Landscape attendance summary | Print View | A4 Landscape, 12px tabular font |
| [`resources/views/hod_remedial_report_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_remedial_report_print.blade.php) | Printable A4 Portrait remedial analysis report | Print View | A4 Portrait, 12px tabular font |
| [`resources/views/hod_course_files_report_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_course_files_report_print.blade.php) | Printable A4 Landscape course files compliance | Print View | A4 Landscape, 12px tabular font |
| [`resources/views/hod_activity_points_report_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_activity_points_report_print.blade.php) | Printable A4 Portrait activity points report | Print View | A4 Portrait, 12px tabular font |
| [`resources/views/hod_workload_report_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_workload_report_print.blade.php) | Printable A4 Portrait faculty workload report | Print View | A4 Portrait, 12px tabular font |
| [`resources/views/hod_consolidated_timetable_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_consolidated_timetable_print.blade.php) | Printable A4 Landscape multi-batch timetable | Print View | A4 Landscape, 11px grid |
| [`resources/views/hod_academic_calendar.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_academic_calendar.blade.php) | Academic calendar configuration planner | Blade View | Legacy Dark UI, Tailwind CDN |
| [`resources/views/hod_academic_calendar_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_academic_calendar_print.blade.php) | Printable A4 Portrait academic calendar | Print View | A4 Portrait, Monthly tabular layout |
| [`resources/views/hod_sbte_audit.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_sbte_audit.blade.php) | SBTE 16-criteria annual audit console | Blade View | Legacy Dark UI, Tailwind CDN |
| [`resources/views/hod_sbte_audit_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_sbte_audit_print.blade.php) | Printable A4 Portrait SBTE Part C dossier | Print View | A4 Portrait multi-page |
| [`resources/views/hod_nba_audit.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_nba_audit.blade.php) | NBA Criteria 1–10 document repository | Blade View | Legacy Dark UI, Tailwind CDN |
| [`resources/views/hod_nba_audit_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_nba_audit_print.blade.php) | Printable A4 Portrait NBA criteria audit | Print View | A4 Portrait overview |
| [`resources/views/staff_leave_reports.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/staff_leave_reports.blade.php) | Staff leave master ledger & filter view | Blade View | Active in standalone & HOD panel |
| [`resources/views/staff_leave_pdf.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/staff_leave_pdf.blade.php) | Formal leave application approval PDF | Print View | A4 Portrait official order |
| [`resources/views/classroom_condonation_report_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/classroom_condonation_report_print.blade.php) | Tutor classroom condonation statement | Print View | A4 Portrait class statement |

---

## 3. HOD Report Centre Catalog & Inventory

The HOD Report Centre provides 11 primary reporting capabilities:

```
┌────────────────────────────────────────────────────────────────────────────────────────┐
│                              HOD REPORT CENTRE CATALOG                                 │
├────┬───────────────────────────────────────┬────────────────────────┬──────────────────┤
│ #  │ Report Module                         │ Workflow Mechanism     │ Output Format    │
├────┼───────────────────────────────────────┼────────────────────────┼──────────────────┤
│ 1  │ Attendance, Log & Condonation         │ Modal (Batch + Type)   │ A4 Landscape     │
│ 2  │ Remedial Coaching Analytics           │ Modal (Batch)          │ A4 Portrait      │
│ 3  │ Faculty Workload & Timetables         │ Dedicated Sub-panel    │ A4 Port / Land   │
│ 4  │ Extra-Curricular Activity Claims      │ Modal (Batch + Sem)    │ A4 Portrait      │
│ 5  │ Department Course Files Auditing      │ Modal (Batch)          │ A4 Landscape     │
│ 6  │ Student Mentoring Diaries             │ Direct Action / Modal  │ A4 Portrait      │
│ 7  │ SBTE Annual Compliance Audit          │ Full Dedicated Desk    │ A4 Portrait      │
│ 8  │ NBA Criteria Accreditation Repository │ Full Dedicated Desk    │ A4 Portrait      │
│ 9  │ Academic Calendar Planner             │ Full Dedicated Desk    │ A4 Portrait      │
│ 10 │ Security & Operations Audit Trail     │ Panel Integration      │ Tabular Logs     │
│ 11 │ Staff Leave Master Ledger             │ Panel / External Desk  │ A4 Port Ledger   │
└────┴───────────────────────────────────────┴────────────────────────┴──────────────────┘
```

### Granular Report Breakdown:

#### 1. Attendance, Log & Condonation Report
* **Purpose:** Provides departmental attendance analytics, lesson plan completion coverage rates, class hour tallies, and student attendance deficiency listings.
* **Input Filters:** 
  - Target Classroom (`classroom_id`)
  - Report Type: `coverage` (Course Coverage Rates & Hours), `roster` (Student Attendance Matrix), `condonation` (Attendance Shortage < 75% List).
* **Data Sources:** `class_management`, `batch_subjects`, `students`, `class_logs_attendance`, `lesson_plans`, `subject_staff_assignments`, `staff_profiles`.
* **Endpoint / Controller:** `GET /hod/attendance-summary/print` (routes/web.php:841)
* **Output Format:** A4 Landscape Printable View (`hod_attendance_summary_print.blade.php`).
* **Permissions / Roles:** HOD, Principal (`Session::get('userRole')` check).

#### 2. Remedial Coaching Analytics Report
* **Purpose:** Summarizes slower learner diagnostics, remedial coaching rooms, conducted contact hours, student rosters, and post-diagnostic assessment improvement outcomes.
* **Input Filters:** Target Classroom (`classroom_id`).
* **Data Sources:** `class_management`, `remedial_rooms`, `batch_subjects`, `staff_profiles`, `remedial_session_logs`, `remedial_students`, `students`, `remedial_assessments`.
* **Endpoint / Controller:** `GET /hod/remedial-report/print` (routes/web.php:966)
* **Output Format:** A4 Portrait Printable View (`hod_remedial_report_print.blade.php`).
* **Permissions / Roles:** HOD, Principal.

#### 3. Faculty Workload & Department Timetables
* **Purpose:** Calculates total weekly theory vs. laboratory teaching hours per faculty member derived from live classroom timetables; generates individual batch timetables and consolidated clash-review master sheets.
* **Sub-Reports:**
  - **A. Department Faculty Workload:** `GET /hod/workload-report/print` $\rightarrow$ A4 Portrait (`hod_workload_report_print.blade.php`).
  - **B. Individual Batch Timetable:** In-memory client popup with A4 Landscape print stylesheet (`printSingleTimetable()` in `hod_workload_panel.blade.php`).
  - **C. Consolidated 2–3 Batch Timetable:** `GET /hod/consolidated-timetable/print?batches[]=...` $\rightarrow$ A4 Landscape (`hod_consolidated_timetable_print.blade.php`).
* **Data Sources:** `staff_profiles`, `batch_subjects`, `subject_staff_assignments`, JSON timetable files in `storage/app/timetables/*.json`.
* **Permissions / Roles:** HOD, Principal.

#### 4. Extra-Curricular Activity Points Audit
* **Purpose:** Evaluates diploma program student activity point claims against the mandatory 75-point course completion threshold (SBTE Kerala standard).
* **Input Filters:** Target Classroom (`classroom_id`), Semester Scope (`all` or `1` through `6`).
* **Data Sources:** `class_management`, `students`, `activity_point_claims`.
* **Endpoint / Controller:** `GET /hod/activity-points-report/print` (routes/web.php:1108)
* **Output Format:** A4 Portrait Printable View (`hod_activity_points_report_print.blade.php`) with per-student claim certificate breakdowns.
* **Permissions / Roles:** HOD, Principal.

#### 5. Department Course Files Compliance Report
* **Purpose:** Audits syllabus PDF uploads, Course Outcome (CO) statements, CO-PO mappings, and NBA course file preparation progress across all allocated subjects.
* **Input Filters:** Target Classroom (`classroom_id`).
* **Data Sources:** `class_management`, `batch_subjects`, `subject_staff_assignments`, `staff_profiles`, `course_files`, `cf_course_files`.
* **Endpoint / Controller:** `GET /hod/course-files-report/print` (routes/web.php:1041)
* **Output Format:** A4 Landscape Printable View (`hod_course_files_report_print.blade.php`).
* **Permissions / Roles:** HOD, Principal.

#### 6. Student Mentoring Diaries
* **Purpose:** Generates comprehensive student cumulative mentoring dossiers, academic progression history, leave records, and counselor notes.
* **Data Sources:** `students`, `tutor_diaries`, `student_family_details`, `student_prior_education`, `student_fee_records`, `extracurricular_activities`, `leave_records`, `disciplinary_actions`, `academic_marks`.
* **Endpoints:** `GET /diary/{regNo}/print`, `GET /diary/{regNo}/leave-report`, `GET /classroom/{classroomId}/condonation-report` (`MentoringController.php`).

#### 7. SBTE Annual Compliance Audit (Part C)
* **Purpose:** 16-criterion comprehensive departmental self-assessment dossier for State Board of Technical Education (SBTE) annual institutional inspection.
* **Input Filters:** Academic Year (e.g. `2025-2026`, `2026-2027`).
* **Data Sources:** `sbte_department_audits`, `sbte_audit_documents`, `students`, `academic_marks`, `staff_professional_activities`.
* **Endpoints:**
  - Console: `GET /hod/sbte-audit`
  - Save: `POST /hod/sbte-audit/save`
  - Print: `GET|POST /hod/sbte-audit/print`
  - Auto-generate APIs: `/api/hod/sbte-audit/generate-perf`, `/api/hod/sbte-audit/generate-course-files`, `/api/hod/sbte-audit/fetch-staff-activities`
* **Output Format:** A4 Portrait Comprehensive Dossier (`hod_sbte_audit_print.blade.php`).

#### 8. NBA Criteria Accreditation Repository
* **Purpose:** Organizes academic audit files, course files, laboratory logs, and continuous improvement action plans categorized under NBA Criteria 1 to 10.
* **Data Sources:** `nba_criteria_documents`.
* **Endpoints:**
  - Console: `GET /hod/nba-audit`
  - Upload: `POST /hod/nba-audit/upload`
  - Print: `GET /hod/nba-audit/print` (`hod_nba_audit_print.blade.php`).

#### 9. Academic Calendar Planner
* **Purpose:** Semester-by-semester departmental academic event scheduling with automated AI PDF extraction from SITTTR circulars and working day calculation.
* **Data Sources:** `academic_calendars`, storage files in `storage/app/public/academic_calendars/`.
* **Endpoints:**
  - Planner: `GET /hod/academic-calendar`
  - Save: `POST /api/academic-calendar/save`
  - Print: `GET /hod/academic-calendar/{id}/print` (`hod_academic_calendar_print.blade.php`)
  - AI Parse: `POST /api/academic-calendar/parse-pdf` (`AcademicCalendarController.php`)

#### 10. Security & Operations Audit Trail
* **Purpose:** Real-time departmental audit log of critical administrative actions, password resets, marks modifications, and session events.
* **Data Sources:** `audit_logs`.
* **Status:** Already integrated and active in `hod_dashboard.blade.php` under `#panelAudit`.

#### 11. Staff Leave Master Ledger
* **Purpose:** Multi-stage staff leave approval audit trail, CL/CCL/DL/ML leave balance accounting, and formal leave order generation.
* **Data Sources:** `staff_leave_requests`, `staff_profiles`.
* **Endpoints:** `GET /staff/leave/reports`, `GET /api/staff/leave/reports-data`, `GET /staff/leave/{id}/pdf`.
* **Status:** Already integrated and active in `hod_dashboard.blade.php` under `#panelLeave_ledger`.

---

## 4. Report Centre UI & DOM Inventory

### 4.1 Master Elements in `hod_report_centre.blade.php`:

```
<header> (Legacy Sticky Header)
  ├── <a> [Back to /dashboard/hod]
  ├── <div> [Badge "RC"]
  └── <h1> [Report Centre — {branch} Dept]
<main>
  ├── <div> [Hero Banner: "Centralized Analytical Report Engine"]
  └── <div> [Grid: 11 Report Cards]
        ├── Card 1: Attendance, Log, Condonation (btn -> openAttendanceModal())
        ├── Card 2: Remedial Session Analysis (btn -> openRemedialModal())
        ├── Card 3: Faculty Workload & Timetables (link -> /hod/report-centre/workload-panel)
        ├── Card 4: Extra-Curricular Claims (btn -> openActivityPointsModal())
        ├── Card 5: Department Course Files (btn -> openCourseFilesModal())
        ├── Card 6: Student Mentoring Diaries (btn -> alert coming soon / open modal)
        ├── Card 7: SBTE Annual Compliance Audit (link -> /hod/sbte-audit)
        ├── Card 8: NBA Criteria Accreditation (link -> /hod/nba-audit)
        ├── Card 9: Academic Calendar Prep (link -> /hod/academic-calendar)
        ├── Card 10: Security & Operations Audit (link -> alert / #panelAudit)
        └── Card 11: Staff Leave Master Ledger (link -> /staff/leave/reports)
```

### 4.2 Modal Dialogs DOM Inventory:

| Modal ID | Purpose | Controls & Inputs | Action Handlers |
|:---|:---|:---|:---|
| `attendanceModal` | Attendance summary filter | `<select id="selectAttendanceBatch">`<br>`<select id="selectAttendanceReportType">` | `closeAttendanceModal()`<br>`printAttendanceSummary()` |
| `remedialModal` | Remedial report filter | `<select id="selectRemedialBatch">` | `closeRemedialModal()`<br>`printRemedialReport()` |
| `courseFilesModal` | Course files compliance filter | `<select id="selectCourseFilesBatch">` | `closeCourseFilesModal()`<br>`printCourseFilesReport()` |
| `activityPointsModal` | Activity points audit filter | `<select id="selectActivityBatch">`<br>`<select id="selectActivitySemester">` | `closeActivityPointsModal()`<br>`printActivityPointsReport()` |

### 4.3 Sub-Panel DOM Inventory (`hod_workload_panel.blade.php`):

| Element ID / Class | Type | Purpose | Action Handler |
|:---|:---|:---|:---|
| `<a href="/hod/workload-report/print">` | Anchor | Direct link to A4 Portrait workload report | Native navigation (target `_blank`) |
| `singleBatchSelect` | Select | Target batch for individual timetable print | Fed to `printSingleTimetable()` |
| `singleSemSelect` | Select | Target semester for timetable print | Fed to `printSingleTimetable()` |
| `button` | Button | Triggers in-memory timetable compilation | `onclick="printSingleTimetable()"` |
| `consolidatedForm` | Form | GET form targeting `/hod/consolidated-timetable/print` | `onsubmit="return validateConsolidatedForm(event)"` |
| `.batch-checkbox` | Checkbox array | Batch selection inputs (`name="batches[]"`) | Change listener enforcing Max 3 selection |
| `selectionStatus` | Span | Dynamic feedback indicator | Updated by `updateSelectionStatus()` |

---

## 5. JavaScript Forensic Audit

| Function Name | Location | Trigger | DOM Dependencies | API / Endpoint Dependencies | Payload / Output | Side Effects |
|:---|:---|:---|:---|:---|:---|:---|
| `openAttendanceModal()` | `hod_report_centre.blade.php` | Card 1 "Compile Logs" button click | `#attendanceModal` | None | None | Removes `hidden`, adds `flex` |
| `closeAttendanceModal()` | `hod_report_centre.blade.php` | Close button / Cancel | `#attendanceModal` | None | None | Adds `hidden`, removes `flex` |
| `printAttendanceSummary()` | `hod_report_centre.blade.php` | Modal "Print Summary" button | `#selectAttendanceBatch`<br>`#selectAttendanceReportType` | `/hod/attendance-summary/print` | `classroom_id`, `report_type` | Closes modal, opens `window.open(...)` |
| `openRemedialModal()` | `hod_report_centre.blade.php` | Card 2 "Analyze Data" button | `#remedialModal` | None | None | Removes `hidden`, adds `flex` |
| `closeRemedialModal()` | `hod_report_centre.blade.php` | Close button / Cancel | `#remedialModal` | None | None | Adds `hidden`, removes `flex` |
| `printRemedialReport()` | `hod_report_centre.blade.php` | Modal "Print Report" button | `#selectRemedialBatch` | `/hod/remedial-report/print` | `classroom_id` | Closes modal, opens `window.open(...)` |
| `openCourseFilesModal()` | `hod_report_centre.blade.php` | Card 5 "Check Status" button | `#courseFilesModal` | None | None | Removes `hidden`, adds `flex` |
| `closeCourseFilesModal()` | `hod_report_centre.blade.php` | Close button / Cancel | `#courseFilesModal` | None | None | Adds `hidden`, removes `flex` |
| `printCourseFilesReport()` | `hod_report_centre.blade.php` | Modal "Print Report" button | `#selectCourseFilesBatch` | `/hod/course-files-report/print` | `classroom_id` | Closes modal, opens `window.open(...)` |
| `openActivityPointsModal()` | `hod_report_centre.blade.php` | Card 4 "View Claims" button | `#activityPointsModal` | None | None | Removes `hidden`, adds `flex` |
| `closeActivityPointsModal()` | `hod_report_centre.blade.php` | Close button / Cancel | `#activityPointsModal` | None | None | Adds `hidden`, removes `flex` |
| `printActivityPointsReport()` | `hod_report_centre.blade.php` | Modal "Print Report" button | `#selectActivityBatch`<br>`#selectActivitySemester` | `/hod/activity-points-report/print` | `classroom_id`, `semester` | Closes modal, opens `window.open(...)` |
| `updateSelectionStatus()` | `hod_workload_panel.blade.php` | Checkbox change | `.batch-checkbox:checked`<br>`#selectionStatus` | None | None | Updates text count: `"X of 3 batches selected"` |
| `validateConsolidatedForm(e)` | `hod_workload_panel.blade.php` | Form submit | `.batch-checkbox:checked` | None | None | Blocks submit and alerts if `< 2` batches |
| `printSingleTimetable()` | `hod_workload_panel.blade.php` | "Print Timetable" button | `#singleBatchSelect`<br>`#singleSemSelect` | `GET /api/hod/batches/{id}/subjects`<br>`GET /api/hod/batches/{id}/timetable` | `semester` | Assembles in-memory HTML and calls `triggerPrintTimetableWindow()` |
| `triggerPrintTimetableWindow()` | `hod_workload_panel.blade.php` | Internal invocation | In-memory window | None | Rendered HTML | Opens `window.open('', '_blank')`, writes HTML with print styles |

---

## 6. Route & API Inventory

```
UI Action / Filter
  ↓
JavaScript Trigger
  ↓
HTTP GET / POST Route
  ↓
Controller Method / Route Closure
  ↓
Eloquent Models / DB Queries
  ↓
Blade View / JSON Stream
```

| HTTP Method | URI Pattern | Handler / Action | Auth & Role Required | Query / Body Parameters | Response Output | Primary Consumer |
|:---|:---|:---|:---|:---|:---|:---|
| `GET` | `/hod/report-centre` | `routes/web.php:781` (Closure) | Session: `HOD`, `Principal` | None | `hod_report_centre.blade.php` with `$batches` | HOD Sidebar Navigation |
| `GET` | `/hod/report-centre/workload-panel` | `routes/web.php:795` (Closure) | Session: `HOD`, `Principal` | None | `hod_workload_panel.blade.php` | Report Centre Card 3 |
| `GET` | `/hod/attendance-summary/print` | `routes/web.php:841` (Closure) | Session: `HOD`, `Principal` | `classroom_id`, `report_type` | `hod_attendance_summary_print.blade.php` | Attendance Modal |
| `GET` | `/hod/remedial-report/print` | `routes/web.php:966` (Closure) | Session: `HOD`, `Principal` | `classroom_id` | `hod_remedial_report_print.blade.php` | Remedial Modal |
| `GET` | `/hod/course-files-report/print` | `routes/web.php:1041` (Closure) | Session: `HOD`, `Principal` | `classroom_id` | `hod_course_files_report_print.blade.php` | Course Files Modal |
| `GET` | `/hod/activity-points-report/print` | `routes/web.php:1108` (Closure) | Session: `HOD`, `Principal` | `classroom_id`, `semester` | `hod_activity_points_report_print.blade.php` | Activity Points Modal |
| `GET` | `/hod/workload-report/print` | `routes/web.php:1167` (Closure) | Session: `HOD`, `Principal` | None (Branch from Session) | `hod_workload_report_print.blade.php` | Workload Sub-Panel |
| `GET` | `/hod/consolidated-timetable/print` | `routes/web.php:810` (Closure) | Session: `HOD`, `Principal` | `batches[]` (array of `classroom_id`) | `hod_consolidated_timetable_print.blade.php` | Workload Sub-Panel |
| `GET` | `/api/hod/batches/{classroomId}/timetable` | `routes/web.php:1284` (Closure) | Session: `HOD`, `Principal` | Path: `classroomId` | JSON `{status: "SUCCESS", timetable: {...}}` | Timetable Print & Editor |
| `POST` | `/api/hod/batches/{classroomId}/timetable` | `routes/web.php:1297` (Closure) | Session: `HOD`, `Principal` | Timetable JSON Matrix | JSON `{status: "SUCCESS", message: "..."}` | Timetable Editor |
| `GET` | `/hod/academic-calendar` | `AcademicCalendarController@index` | Session: `HOD`, `Principal`, `Admin` | None | `hod_academic_calendar.blade.php` | Report Centre Card 9 |
| `POST` | `/api/academic-calendar/save` | `AcademicCalendarController@store` | Session: `HOD`, `Principal`, `Admin` | `semester`, `academic_year`, `activities`, `pdf` | JSON `{status: "SUCCESS", id: ...}` | Academic Calendar Desk |
| `GET` | `/hod/academic-calendar/{id}/print` | `AcademicCalendarController@printCalendar` | Session: `HOD`, `Principal`, `Admin` | Path: `id` | `hod_academic_calendar_print.blade.php` | Academic Calendar Desk |
| `POST` | `/api/academic-calendar/parse-pdf` | `AcademicCalendarController@parsePdf` | Session: `HOD`, `Principal`, `Admin` | `semester` | JSON `{status: "SUCCESS", entries: [...]}` | AI Auto-Fetch Button |
| `GET` | `/hod/sbte-audit` | `routes/web.php:1342` (Closure) | Session: `HOD`, `Principal` | `academic_year` | `hod_sbte_audit.blade.php` | Report Centre Card 7 |
| `POST` | `/hod/sbte-audit/save` | `routes/web.php:1384` (Closure) | Session: `HOD`, `Principal` | Form 16-criteria payload | Redirect with success flash | SBTE Audit Console |
| `GET|POST` | `/hod/sbte-audit/print` | `routes/web.php:1435` (Closure) | Session: `HOD`, `Principal` | `academic_year` | `hod_sbte_audit_print.blade.php` | SBTE Audit Console |
| `GET` | `/hod/nba-audit` | `routes/web.php:1602` (Closure) | Session: `HOD`, `Principal` | None | `hod_nba_audit.blade.php` | Report Centre Card 8 |
| `POST` | `/hod/nba-audit/upload` | `routes/web.php:1653` (Closure) | Session: `HOD`, `Principal` | `doc_id`, file `document` | JSON `{status: "SUCCESS"}` | NBA Audit Console |
| `GET` | `/hod/nba-audit/print` | `routes/web.php:1684` (Closure) | Session: `HOD`, `Principal` | None | `hod_nba_audit_print.blade.php` | NBA Audit Console |

---

## 7. Database & Storage Source Audit

```
┌────────────────────────────────────────────────────────────────────────┐
│                      DATABASE & STORAGE RELATIONS                      │
├───────────────────────────────┬────────────────────────────────────────┤
│ Entity / Table                │ Core Dependent Columns & Fields        │
├───────────────────────────────┼────────────────────────────────────────┤
│ class_management              │ classroom_id, branch, current_semester │
│ batch_subjects                │ id, classroom_id, subject_code, name   │
│ subject_staff_assignments     │ batch_subject_id, staff_mobile_no      │
│ staff_profiles                │ mobile_no, name, branch, designation   │
│ students                      │ reg_no, sbte_reg_no, roll_no, name     │
│ class_logs_attendance         │ batch_subject_id, present_students     │
│ lesson_plans                  │ batch_subject_id, status (Completed)   │
│ remedial_rooms                │ room_id, classroom_id, subject_code    │
│ remedial_students             │ room_id, reg_no                        │
│ remedial_session_logs         │ room_id, log_date, conducted_hours     │
│ remedial_assessments          │ room_id, assessment_id, max_marks      │
│ course_files                  │ batch_subject_id, syllabus_pdf_path    │
│ cf_course_files               │ batch_subject_id, status               │
│ activity_point_claims         │ reg_no, semester, points_awarded       │
│ academic_calendars            │ branch, semester, activities (JSON)    │
│ sbte_department_audits        │ academic_year, branch, 16 data JSONs   │
│ nba_criteria_documents        │ branch, criteria_no, file_path         │
│ staff_leave_requests          │ leave_id, staff_mobile_no, hod_status  │
│ audit_logs                    │ user_id, action, details, created_at   │
├───────────────────────────────┴────────────────────────────────────────┤
│ File Storage Paths:                                                    │
│ storage/app/timetables/{classroom_id}.json                             │
│ storage/app/public/academic_calendars/cal_{branch}_sem{sem}_{time}.pdf│
│ public/uploads/nba_audit/nba_{time}_{name}                             │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 8. Print & PDF Forensics

All Report Centre print outputs are executed via client-side `window.print()` rendering dedicated Blade templates.

| Report Document | Blade Source | Route | Page Size | Orientation | Print CSS Standards | Screen vs Print Behavior |
|:---|:---|:---|:---|:---|:---|:---|
| **Attendance Summary & Coverage** | `hod_attendance_summary_print.blade.php` | `/hod/attendance-summary/print` | A4 | Landscape (`@page { size: A4 landscape; margin: 0.5cm; }`) | Tabular font `12px !important;`, `.no-print` hidden, `page-break-inside: avoid;` on rows | Top action bar on screen; clean white sheet on print |
| **Remedial Coaching Analytics** | `hod_remedial_report_print.blade.php` | `/hod/remedial-report/print` | A4 | Portrait (`@page { size: A4 portrait; margin: 0.5cm; }`) | Tabular font `12px !important;`, 3-column signature block at base | Top action bar on screen; clean white sheet on print |
| **Course Files Auditing** | `hod_course_files_report_print.blade.php` | `/hod/course-files-report/print` | A4 | Landscape (`@page { size: A4 landscape; margin: 0.5cm; }`) | Tabular font `12px !important;`, 3-column signature block at base | Top action bar on screen; clean white sheet on print |
| **Activity Points Audit** | `hod_activity_points_report_print.blade.php` | `/hod/activity-points-report/print` | A4 | Portrait (`@page { size: A4 portrait; margin: 0.5cm; }`) | Explicit `.page-break` between cumulative summary and claims breakdown | Top action bar on screen; clean white sheet on print |
| **Faculty Workload Report** | `hod_workload_report_print.blade.php` | `/hod/workload-report/print` | A4 | Portrait (`@page { size: A4 portrait; margin: 1cm; }`) | Solid borders `2px solid #000000 !important;`, HOD + Principal signatures | Dark preview on screen; high-contrast monochrome print |
| **Consolidated Timetable** | `hod_consolidated_timetable_print.blade.php` | `/hod/consolidated-timetable/print` | A4 | Landscape (`@page { size: A4 landscape; margin: 0.5cm; }`) | Day column rowspanned, Lunch column rotated vertically (`writing-mode: vertical-rl`) | Dark preview on screen; high-contrast monochrome print |
| **Individual Timetable** | In-memory HTML popup in `hod_workload_panel` | Dynamic Popup | A4 | Landscape (`@page { size: A4 landscape; margin: 0.5cm; }`) | Merged period blocks (Colspan 2 or 3), Subject abbreviations legend | Dark preview on screen; high-contrast monochrome print |
| **Academic Calendar** | `hod_academic_calendar_print.blade.php` | `/hod/academic-calendar/{id}/print` | A4 | Portrait (`@page { size: A4 portrait; margin: 0.5cm; }`) | Monthly calendars with working day tallies and Sunday highlights | Clean light preview with print toolbar |
| **SBTE Part C Dossier** | `hod_sbte_audit_print.blade.php` | `/hod/sbte-audit/print` | A4 | Portrait (`@page { size: A4 portrait; margin: 0.8cm; }`) | Multi-page structured audit tables across 16 criteria | Clean light preview with print toolbar |
| **NBA Accreditation Audit** | `hod_nba_audit_print.blade.php` | `/hod/nba-audit/print` | A4 | Portrait (`@page { size: A4 portrait; margin: 0.8cm; }`) | 10 Criteria document checklist and verification matrix | Clean light preview with print toolbar |

---

## 9. Principal / Admin Comparison

| Feature / Domain | HOD Implementation | Principal Implementation | Admin Implementation | Architectural Role |
|:---|:---|:---|:---|:---|
| **Department Scope** | Strictly filtered by HOD's branch (`session('userBranch')`) | All branches selectable or institution-wide aggregated | All branches selectable | HOD uses automatic branch filtering; Principal uses branch switcher |
| **Attendance Summary** | Single batch coverage & roster | All branches overview | Daily staff biometric punches (`/sf-attendance/attendance-report`) | Shared underlying data (`class_logs_attendance`) |
| **Faculty Workload** | Department lecturers & demonstrators | College-wide faculty workload distribution | User management registry | HOD provides granular departmental allocation |
| **Timetables** | Department classrooms only | "Today's Timetable" live institution overview (`principal_today_timetable.blade.php`) | None | Timetable JSON files are canonical single source of truth |
| **Leave Ledger** | Department staff approvals & ledger | Final approval authority & executive summary | Leave record audits | Uses common `staff_leave_requests` table |
| **Executive Digest** | None | `/admin/executive-digest/pdf` board report | Administrative executive summary | Principal/Chairman level document |

---

## 10. Design System Violations in Current Report Centre UI

The existing `resources/views/hod_report_centre.blade.php` and `resources/views/hod_workload_panel.blade.php` violate the modern CampusLynk design standard:

1. **Prohibited CDN Scripts:**
   - ❌ Uses runtime Tailwind CDN `<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>`.
   - ❌ Uses external CDN stylesheet link for Tailwind 2.2 in print popups (`tailwindcss@2.2.19`).
2. **Prohibited Font Stylesheet Duplications:**
   - ❌ Direct `<link>` tags for `Inter` and `Material Symbols Rounded` in view `<head>`.
3. **Legacy Dark Theme:**
   - ❌ Hardcoded dark palettes (`bg-slate-950`, `bg-slate-900`, `card-gradient`, `text-slate-300`).
   - ❌ Must be transitioned to CampusLynk canonical canvas `#FAFAFB` with `#FFFFFF` surfaces.
4. **Prohibited Gradients & Glowing Box-Shadows:**
   - ❌ `bg-gradient-to-br from-amber-500 to-orange-600`
   - ❌ `bg-gradient-to-r from-sky-500 to-blue-600`
   - ❌ `shadow-amber-500/20`, `shadow-2xl`
   - ❌ Must be replaced with crisp `1px solid #E5E7EB` borders and standard elevation (`shadow-sm`).
5. **Micro-Font Policy Violations:**
   - ❌ Uses `text-[10px]` and `text-[11px]` on card subtitles, badges, and table headers.
   - ❌ Must strictly enforce minimum `14px (text-sm)` for data entry and descriptions.
6. **Form Controls:**
   - ❌ Native unstyled `<select>` elements inside modals.
   - ❌ Must be mapped to standard tokenized form selects / `<x-ui.select>`.

---

## 11. Component Mapping Matrix

| Legacy Element in Report Centre | Modern CampusLynk Replacement Component | Token / Style Configuration |
|:---|:---|:---|
| Outer Standalone Shell (`<!DOCTYPE>`, `<header>`, `<footer>`) | `<x-layouts.app-shell>` or integrated `#panelReport_centre` inside `hod_dashboard.blade.php` | Canonical `#FAFAFB` backdrop, unified topbar & sidebar |
| Hero Banner | Card Header Container | `bg-white border border-slate-200 rounded-2xl p-6 shadow-sm` |
| Report Grid Cards | `<x-ui.card>` / Structured Card Token | `bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between` |
| Status Pills ("Ready", "Active", "Pending") | `<x-ui.badge>` | `variant="success"`, `variant="warning"`, `variant="neutral"` (`px-3 py-1 rounded-full text-xs font-semibold`) |
| Action Buttons ("Compile Logs", "Analyze Data", "View Panel") | `<x-ui.button>` | Primary Blue `#2563EB` (`variant="primary"`) or Secondary White (`variant="secondary"`) with `12px (rounded-xl)` radius |
| Filter Modal Backdrops & Dialogs | `<x-ui.modal>` | Standard centered dialog (`max-w-md bg-white border border-slate-200 rounded-2xl p-6 shadow-xl`) |
| Batch & Report Type Dropdowns | `<x-ui.select>` | Height `44px`, `12px (rounded-xl)` radius, `14px (text-sm)` font, crisp `#E5E7EB` border |
| Vector Icons | Standard Lucide Vector Icons | `20px` / `24px` uniform stroke vector icons (e.g. `file-text`, `users`, `calendar`, `bar-chart-3`, `check-circle-2`) |

---

## 12. Responsive Architecture Analysis

* **Desktop Layout (> 1024px):**
  - Report Cards reflow into a clean **4-column grid** (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4`).
  - Hero header displays operational summary and quick status indicators.
  - Modals render centered with `max-w-md` constraints.
* **Tablet Layout (768px – 1023px):**
  - Report Cards reflow into a balanced **2-column grid** (`grid-cols-2 gap-4`).
  - Action buttons retain full touch-target compliance (`44px` height).
* **Mobile Layout (< 768px):**
  - Report Cards stack into a **1-column vertical stream** (`grid-cols-1 gap-3.5`).
  - Modal dialogs occupy full mobile width with comfortable touch padding.
* **Screen vs. Print Layout Distinction:**
  - **Screen UI:** 100% fluid, responsive across all viewports.
  - **Print Views:** Strictly locked to physical A4 standard dimensions (`210mm × 297mm` Portrait or `297mm × 210mm` Landscape) with `@page` and `@media print` rules.

---

## 13. Preservation Contract

The following items are strictly categorized for migration safety:

```
┌────────────────────────────────────────────────────────────────────────┐
│                          PRESERVATION CONTRACT                         │
├──────────────┬─────────────────────────────────────────────────────────┤
│ CRITICAL     │ • All 18 Route endpoints and HTTP methods               │
│ (Zero Change │ • All 4 Modal IDs: attendanceModal, remedialModal,      │
│  Permitted)  │   courseFilesModal, activityPointsModal                 │
│              │ • All 6 Modal Select IDs: selectAttendanceBatch,        │
│              │   selectAttendanceReportType, selectRemedialBatch,      │
│              │   selectCourseFilesBatch, selectActivityBatch,          │
│              │   selectActivitySemester                                │
│              │ • All 12 JS Action Functions: printAttendanceSummary,   │
│              │   printRemedialReport, printCourseFilesReport,          │
│              │   printActivityPointsReport, printSingleTimetable, etc. │
│              │ • All 6 Print Blade templates: hod_*_print.blade.php    │
│              │ • Timetable JSON read/write logic in storage/app/       │
├──────────────┼─────────────────────────────────────────────────────────┤
│ IMPORTANT    │ • HOD navigation active state synchronization           │
│ (Modernize   │ • CSRF token forwarding in AJAX / Form POSTs            │
│  UI Only)    │ • Strict branch scoping: session('userBranch')          │
│              │ • 14px minimum typography standard                      │
├──────────────┼─────────────────────────────────────────────────────────┤
│ OPTIONAL     │ • Visual icon color tags                                │
│              │ • Card numerical badge counters (1–11)                  │
└──────────────┴─────────────────────────────────────────────────────────┘
```

---

## 14. Evaluated Migration Architectures & Recommendation

### Option A: Embedded Panel with In-Dashboard Previews
* **Concept:** Place all report tables directly inside the dashboard canvas.
* **Risk:** Extreme clutter; multi-page A4 tables (like 16-criteria SBTE audits or 5-subject attendance matrices) will break the dashboard layout.
* **Verdict:** REJECTED.

### Option B: External Standalone Page Migration
* **Concept:** Keep `/hod/report-centre` as a separate URL and only update its CSS.
* **Risk:** Forces full page reloads and breaks the single-workspace architecture already established in Phase 2C.1 for HOD.
* **Verdict:** REJECTED.

### Option C: Hybrid Single-Workspace Panel + Dedicated A4 Print Workflows (RECOMMENDED)
* **Concept:**
  1. **HOD Report Centre as an Integrated Panel (`#panelReport_centre` in `hod_dashboard.blade.php`):**
     - Clicking "Report Centre" in the HOD sidebar invokes `handleHodSidebarNav('report_centre')`, smoothly transitioning to the Report Centre panel without page reloads.
     - Also maintain `/hod/report-centre` as a server-side redirect or alias to `/dashboard/hod?panel=report_centre` for backwards compatibility.
  2. **Modernized UI:** Render the 11 report category cards using CampusLynk standard white card surfaces, Lucide vector icons, and crisp `<x-ui.*>` modal dialogs.
  3. **Dedicated Print Workflows Intact:** When a report is triggered, it opens the existing high-fidelity A4 print views (`hod_*_print.blade.php`) in a dedicated browser tab/window ready for `window.print()`.
* **Advantage:** Delivers a seamless, modern single-workspace interface while preserving 100% of tested printing, PDF generation, and database calculations.

---

## 15. Decision Matrix

### KEEP AS-IS
* All backend route closures and controller methods in `routes/web.php`.
* `AcademicCalendarController.php`, `RemedialController.php`, `StaffLeaveController.php`, `MentoringController.php`, `AttendanceController.php`.
* Database schema across all 21 reporting tables.
* Timetable JSON files in `storage/app/timetables/*.json`.

### MODERNIZE
* The Report Centre UI catalog and filter modals: replace legacy dark theme (`bg-slate-950`), gradients, glows, and micro-fonts with CampusLynk light theme (`#FAFAFB` canvas, `#FFFFFF` cards, `#2563EB` accents, minimum 14px typography).
* The Workload & Timetable Control Desk UI (`hod_workload_panel.blade.php`).

### WRAP WITH CAMPUSLYNK SHELL / INTEGRATE AS HOD PANEL
* Integrate the Report Centre catalog directly into `hod_dashboard.blade.php` as `#panelReport_centre` activated via `handleHodSidebarNav('report_centre')`.
* Update `config/navigation/hod.php` to set `'onclick' => "handleHodSidebarNav('report_centre')"` for the `report_centre` nav item.

### KEEP AS DEDICATED PRINT DOCUMENTS
* `hod_attendance_summary_print.blade.php` (A4 Landscape)
* `hod_remedial_report_print.blade.php` (A4 Portrait)
* `hod_course_files_report_print.blade.php` (A4 Landscape)
* `hod_activity_points_report_print.blade.php` (A4 Portrait)
* `hod_workload_report_print.blade.php` (A4 Portrait)
* `hod_consolidated_timetable_print.blade.php` (A4 Landscape)
* `hod_academic_calendar_print.blade.php` (A4 Portrait)
* `hod_sbte_audit_print.blade.php` (A4 Portrait)
* `hod_nba_audit_print.blade.php` (A4 Portrait)
* `staff_leave_pdf.blade.php` (A4 Portrait)

### DO NOT TOUCH
* All database tables, foreign keys, and indexes.
* All scoring, attendance percentage formulas, and threshold logic (e.g. 75% attendance condonation cut-off, 75 activity points course completion standard).

---

## 16. Final Recommendation: Granular Migration Execution Plan

When migration is authorized, execute in the following 5 strictly separated phases:

### PHASE A — Navigation & Shell Integration
1. Update `config/navigation/hod.php` to configure `report_centre` with `handleHodSidebarNav('report_centre')`.
2. Update `hod_dashboard.blade.php` panel list and query parser to recognize `report_centre` as a first-class panel.
3. Update route `GET /hod/report-centre` to redirect to `/dashboard/hod?panel=report_centre`.

### PHASE B — Report Centre UI Modernization
1. Build `#panelReport_centre` in `hod_dashboard.blade.php` using CampusLynk design system tokens:
   - 11 Report Cards in responsive 4-column grid.
   - Status badges (`<x-ui.badge>`), primary actions (`<x-ui.button>`), Lucide vector icons.
2. Build standardized modal dialogs:
   - `#attendanceModal` with `#selectAttendanceBatch` and `#selectAttendanceReportType`.
   - `#remedialModal` with `#selectRemedialBatch`.
   - `#courseFilesModal` with `#selectCourseFilesBatch`.
   - `#activityPointsModal` with `#selectActivityBatch` and `#selectActivitySemester`.

### PHASE C — Sub-Desk Modernization (`hod_workload_panel.blade.php`)
1. Wrap `hod_workload_panel.blade.php` with `<x-layouts.app-shell>` or modernize its inner card layouts with light design tokens.
2. Preserve `printSingleTimetable()`, `validateConsolidatedForm()`, and checkbox constraints.

### PHASE D — Print & PDF Verification
1. Verify all 10 A4 print templates render with clean light backgrounds, correct page breaks, and working `window.print()` triggers.
2. Remove any lingering Tailwind CDN links from standalone print views where `@vite` or embedded CSS can be cleanly inherited.

### PHASE E — Final Consistency & Smoke Testing
1. Run `php artisan view:clear` and `npm run build`.
2. Test live across HOD login accounts (`Deepa Nair` - `9495519943` [CT], `Fr Siji Thomas` - `9400087440` [CE], `Rajesh R` - `9446787989` [ME]).
3. Validate responsive views at 1440px, 768px, and 375px.

---
*Report completed under strict READ-ONLY protocol. Ready for Phase A implementation upon user request.*
