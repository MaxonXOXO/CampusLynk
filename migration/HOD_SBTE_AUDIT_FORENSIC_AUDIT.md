# CAMPUSLYNK — SBTE ANNUAL AUDIT (PART C)
## FORENSIC UI MIGRATION AUDIT & PRESERVATION BASELINE
**Date:** August 22, 2026  
**Auditor:** DeepMind Antigravity AI Engineering Suite  
**Status:** STRICT READ-ONLY / FORENSIC INSPECTION COMPLETE (ZERO PRODUCTION CODE MODIFIED)  
**Primary Surface:** `resources/views/hod_sbte_audit.blade.php` (1,016 lines)  
**Primary Print Target:** `resources/views/hod_sbte_audit_print.blade.php` (579 lines)  
**Primary Route:** `/hod/sbte-audit`  
**Database Table:** `sbte_department_audits`

---

## 1. Executive Summary

The **SBTE Academic Audit (Part C - Program Details)** console provides Department Heads with a comprehensive self-assessment workspace aligned with the **State Board of Technical Education (SBTE) & SITTTR Kerala 16-Criteria Annual Audit Standards**.

### Subsystem Capabilities:
1. **16 Statutory Audit Criteria:** Full institutional data entry for student intake, academic pass rates, placement metrics, student-faculty ratios (SFR), infrastructure adequacy, vision/mission statements, teaching-learning compliance, course files coverage, faculty training, and student/faculty achievements.
2. **Automated Live Database Aggregation:** Three real-time data compilation bridges:
   - **Academic Performance Generator:** Aggregates pass percentages and arrears from `student_board_grades` across S1–S6 for CAY, CAY-1, and CAY-2.
   - **Course Files Compliance Compiler:** Counts active curriculum subjects in `batch_subjects` and verified syllabi in `course_files`.
   - **Staff Portfolio Synchronization:** Imports verified FDPs, publications, books, and curriculum syllabus gaps from `staff_professional_activities`.
3. **Official A4 Portrait Board Printouts:** Generates multi-page formatted DTE/SBTE Part C inspection reports (`/hod/sbte-audit/print`) with signature blocks for HOD, Principal, and State Auditors.

---

## 2. Current UI Architecture

- **Layout File:** Standalone HTML view ([`resources/views/hod_sbte_audit.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_sbte_audit.blade.php)) with legacy dark background (`#020617`), `@tailwindcss/browser@4` runtime CDN, Google Fonts Inter CDN, and Material Symbols Rounded CDN.
- **Micro-Fonts (<14px):** Extensive global override forcing `font-size: 0.76rem !important` (12px) on inputs, selects, tables, labels, and buttons.
- **Top Bar:** Standalone dark header with back navigation (`/hod/report-centre`), academic year selector form (`<form method="GET">`), "Save Progress" submit button (`type="submit" form="auditForm"`), and "Print Part C" button (`/hod/sbte-audit/print`).
- **Main Body:** A single continuous `<form id="auditForm" method="POST" action="/hod/sbte-audit/save">` containing 16 criteria blocks.
- **Footer:** Standalone copyright bar.

---

## 3. Navigation Analysis (`config/navigation/hod.php`)

| Level | Link Path | Calling Mechanism | Presentation Shell | Status |
|:---|:---|:---|:---|:---:|
| **HOD Sidebar** | `/dashboard/hod?panel=report_centre` | `handleHodSidebarNav('report_centre')` | Native CampusLynk App Shell | Active |
| **Report Centre Catalog** | `/hod/sbte-audit` | Card 7 ("SBTE Academic Audit") `href="/hod/sbte-audit"` | Sub-Desk Navigation | Active |
| **Sub-Desk Header** | `/hod/report-centre` | Back Arrow Anchor | Standalone Header | Active |
| **Print Output** | `/hod/sbte-audit/print` | Target Blank Anchor | A4 Portrait Print View | Active |

---

## 4. UI Structure & 16-Criteria Breakdown

```
SBTE Academic Audit Console (hod_sbte_audit.blade.php)
│
├── 1. Top Header Bar (Sticky)
│   ├── Back Link (/hod/report-centre)
│   ├── Title: SBTE Academic Audit (Part C)
│   ├── Academic Year Selector (<select name="academic_year">)
│   ├── Save Progress Button (form="auditForm")
│   └── Print Part C Action (/hod/sbte-audit/print)
│
├── 2. Context Overview Banner
│   └── Academic Year, Department Badge, Guidance text
│
└── 3. Master Form (#auditForm -> POST /hod/sbte-audit/save)
    ├── Criterion 1: Program & HOD Details (nba_accredited, hod_name, faculty_count)
    ├── Criterion 2: Enrollment Data (Intake, Enrolled, Present for CAY, CAY-1, CAY-2)
    ├── Criterion 3: Academic Performance Without Backlog (S1–S6 table + DB Generate CTA)
    ├── Criterion 4: Academic Performance With Backlog (S1–S6 table + DB Generate CTA)
    ├── Criterion 5: Placement Metrics (CAY-1, CAY-2, CAY-3 Admitted, Placed, Higher Ed, Entrepreneurs)
    ├── Criterion 6: Professional Activities (Societies + Publications list + Staff Logs CTA)
    ├── Criterion 7: Student Faculty Ratio (SFR for CAY, CAY-1, CAY-2)
    ├── Criterion 8: Infrastructure of Program (Classrooms, Labs, HOD Cabin, Area, Adequacy, Ambience)
    ├── Criterion 9: Vision, Mission, PEOs, & PSOs (Vision, Mission, PEOs, PSOs statements & dissemination)
    ├── Criterion 10: Teaching - Learning Process (8 items: Gaps, Weak/Bright, Calendar, Labs, Projects + Staff Logs CTA)
    ├── Criterion 11: Course Files (CAY-3, CAY-2, CAY-1 Coverage & Attainment + DB Generate CTA)
    ├── Criterion 12: Faculty Training Participation (Dynamic Table + Staff Logs CTA + Add Row)
    ├── Criterion 13: FDPs Conducted in Past 3 Years (Dynamic Table + Staff Logs CTA + Add Row)
    ├── Criterion 14: Consultancy & Testing Funds (Dynamic Table + Add Row)
    └── Criterion 15: Remarkable Achievements (Dynamic Table + Add Row + Auto-Reindexing)
```

---

## 5. Functional Workflow Inventory

| # | User Action | Triggering Element | Function Called | Target Endpoint | HTTP Method | Payload | UI Update / Side Effect |
|:---:|:---|:---|:---|:---|:---:|:---|:---|
| **1** | Switch Academic Year | `<select name="academic_year">` | `onchange="this.form.submit()"` | `/hod/sbte-audit` | `GET` | `?academic_year=YYYY-YYYY` | Reloads audit data for selected year. |
| **2** | Save Audit Progress | Submit Button / Header Button | Form Submission | `/hod/sbte-audit/save` | `POST` | Master FormData (all 16 criteria JSON arrays) | Flashes `session('success')` and persists state in `sbte_department_audits`. |
| **3** | Print Part C Report | Print Button | Anchor link | `/hod/sbte-audit/print` | `GET` | `?academic_year=YYYY-YYYY` | Opens official A4 portrait print preview. |
| **4** | Generate Academic Performance | "Generate from DB" Button (Crit 3 & 4) | `generateAcademicPerformance()` | `/api/hod/sbte-audit/generate-perf` | `GET` | None | Updates S1–S6 CAY/CAY-1/CAY-2 Reg & Pass inputs for Criteria 3 & 4. |
| **5** | Generate Course Files Metrics | "Generate from DB" Button (Crit 11) | `generateCourseFiles()` | `/api/hod/sbte-audit/generate-course-files` | `GET` | None | Updates CAY-3, CAY-2, CAY-1 course numbers, completed files, PO/PSO attainment. |
| **6** | Fetch Staff Activities & Training | "Fetch from Staff Logs" Button (Crit 6, 10, 12, 13) | `fetchStaffActivities()` | `/api/hod/sbte-audit/fetch-staff-activities` | `GET` | `?academic_year=...` | Appends publications to `#publicationsContainer`, marks syllabus gaps in Crit 10, and adds rows to `#facultyTrainingContainer`. |
| **7** | Add / Remove Professional Society | "+ Add Society" Button | `addSocietyRow()` / inline `.remove()` | Local DOM | None | — | Appends text input to `#societiesContainer`. |
| **8** | Add / Remove Publication | "+ Add Publication" Button | `addPublicationRow()` / inline `.remove()` | Local DOM | None | — | Appends text input to `#publicationsContainer`. |
| **9** | Add / Remove Faculty Training | "+ Add Faculty Training" Button | `addFacultyTrainingRow()` / inline `.remove()` | Local DOM | None | — | Appends 6-column `<tr>` to `#facultyTrainingContainer`. |
| **10** | Add / Remove FDP Conducted | "+ Add FDP Conducted" Button | `addFdpConductedRow()` / inline `.remove()` | Local DOM | None | — | Appends 5-column `<tr>` to `#fdpConductedContainer`. |
| **11** | Add / Remove Consultancy Record | "+ Add Consultancy" Button | `addConsultancyRow()` / inline `.remove()` | Local DOM | None | — | Appends 6-column `<tr>` to `#consultancyContainer`. |
| **12** | Add / Remove / Reindex Achievement | "+ Add Achievement" Button | `addAchievementRow()` / `removeAchievementRow()` | `reindexAchievements()` | None | — | Appends 6-column `<tr>` to `#achievementsContainer` and recalculates sequential row indices (`0..N`). |

---

## 6. Complete DOM Preservation Matrix

### A. Critical DOM Identifiers (Must NOT be changed):
| Identifier | Element Type | Criticality | Purpose |
|:---|:---:|:---:|:---|
| `#auditForm` | `<form>` | **CRITICAL** | Master form bound to header submit button and `/hod/sbte-audit/save`. |
| `#societiesContainer` | `<div>` | **CRITICAL** | Target container for `addSocietyRow()`. |
| `#publicationsContainer` | `<div>` | **CRITICAL** | Target container for `addPublicationRow()` and `fetchStaffActivities()`. |
| `#facultyTrainingContainer` | `<tbody>` | **CRITICAL** | Target container for `addFacultyTrainingRow()` and `fetchStaffActivities()`. |
| `#fdpConductedContainer` | `<tbody>` | **CRITICAL** | Target container for `addFdpConductedRow()`. |
| `#consultancyContainer` | `<tbody>` | **CRITICAL** | Target container for `addConsultancyRow()`. |
| `#achievementsContainer` | `<tbody>` | **CRITICAL** | Target container for `addAchievementRow()`, `removeAchievementRow()`, and `reindexAchievements()`. |

### B. Critical Form Field Names (100% Preserved for Controller Serialization):
- `academic_year`
- `nba_accredited`
- `professional_activities[hod_name]`
- `professional_activities[faculty_count]`
- `professional_activities[societies][]`
- `professional_activities[publications][]`
- `enrollment[{CAY|CAY-1|CAY-2}][intake|enrolled|present]`
- `perf_no_backlog[{1..6}][{CAY|CAY-1|CAY-2}][reg|pass]`
- `perf_with_backlog[{1..6}][{CAY|CAY-1|CAY-2}][reg|pass]`
- `placement[{CAY-1|CAY-2|CAY-3}][admitted|placed|higher_ed|entrepreneurs]`
- `sfr[{CAY|CAY-1|CAY-2}]`
- `infrastructure[{item}][number|area|adequacy|ambience]`
- `vision_mission[vision|mission|peos|psos|remarks]`
- `teaching_learning[{key}][status|remarks]`
- `course_files[{CAY-3|CAY-2|CAY-1}][rev_year|courses|completed|po_attained|pso_attained]`
- `faculty_training[{index}][name|designation|title|duration|venue]`
- `fdp_conducted[{index}][title|attended|date_from|funding]`
- `consultancy[{index}][name|date|fund|faculty|remarks]`
- `achievements[{index}][category|name|achievement|remarks]`

### C. JavaScript-Dependent Classes:
- `.achievement-row`
- `.row-num`

---

## 7. JavaScript Preservation Matrix

| JavaScript Function | Purpose & Mechanism | DOM Dependencies | API Dependencies | Status |
|:---|:---|:---|:---|:---:|
| `generateAcademicPerformance()` | Fetches live arrears/pass data and updates S1–S6 Reg/Pass inputs. | `input[name="perf_no_backlog[...]"]`, `input[name="perf_with_backlog[...]"]` | `GET /api/hod/sbte-audit/generate-perf` | **PRESERVE** |
| `generateCourseFiles()` | Fetches curriculum course files completion and updates CAY-3..CAY-1 inputs. | `input[name="course_files[...]"]`, `select[name="course_files[...]"]` | `GET /api/hod/sbte-audit/generate-course-files` | **PRESERVE** |
| `fetchStaffActivities()` | Aggregates publications, syllabus gaps, and training events from staff logs. | `#publicationsContainer`, `select[name="teaching_learning[gaps][status]"]`, `input[name="teaching_learning[gaps][remarks]"]`, `#facultyTrainingContainer` | `GET /api/hod/sbte-audit/fetch-staff-activities` | **PRESERVE** |
| `addSocietyRow()` | Dynamically creates professional society input with remove button. | `#societiesContainer` | None | **PRESERVE** |
| `addPublicationRow()` | Dynamically creates publication text input with remove button. | `#publicationsContainer` | None | **PRESERVE** |
| `addFacultyTrainingRow()` | Appends editable 6-cell training record row to table. | `#facultyTrainingContainer` | None | **PRESERVE** |
| `addFdpConductedRow()` | Appends editable 5-cell FDP record row to table. | `#fdpConductedContainer` | None | **PRESERVE** |
| `addConsultancyRow()` | Appends editable 6-cell consultancy record row to table. | `#consultancyContainer` | None | **PRESERVE** |
| `addAchievementRow()` | Appends editable achievement record row with category select. | `#achievementsContainer` | Calls `reindexAchievements()` | **PRESERVE** |
| `removeAchievementRow(btn)` | Removes target achievement row and refreshes row numbers. | `#achievementsContainer` | Calls `reindexAchievements()` | **PRESERVE** |
| `reindexAchievements()` | Iterates through rows in `#achievementsContainer`, sets `.row-num` text and renames input array keys (`[0]`, `[1]`, etc.). | `#achievementsContainer`, `.row-num`, `select`, `input` | None | **PRESERVE** |

*(Note: Legacy file line 798 contains an accidental stray `}` bracket which will be cleanly sanitized during UI modernization).*

---

## 8. Backend / API Contract Matrix

| Route URI | HTTP Method | Auth / Role | Input Parameters | Database Storage / Queries | Response |
|:---|:---:|:---|:---|:---|:---|
| `/hod/sbte-audit` | `GET` | HOD, Principal | `academic_year` (optional) | Reads `sbte_department_audits` by `academic_year` & `branch`. | Renders `hod_sbte_audit.blade.php`. |
| `/hod/sbte-audit/save` | `POST` | HOD, Principal | All 16 criteria form inputs | Upserts `sbte_department_audits` record with JSON columns. | Redirects back with `session('success')`. |
| `/hod/sbte-audit/print` | `GET`/`POST` | HOD, Principal | `academic_year` | Reads or updates `sbte_department_audits`. | Renders `hod_sbte_audit_print.blade.php`. |
| `/api/hod/sbte-audit/generate-perf` | `GET` | HOD, Principal | None (`session('userBranch')`) | Queries `student_board_grades` joined with `students` across S1–S6. | JSON: `{ perf_no_backlog: {...}, perf_with_backlog: {...} }` |
| `/api/hod/sbte-audit/generate-course-files` | `GET` | HOD, Principal | None (`session('userBranch')`) | Counts `batch_subjects` and verified `course_files` by branch. | JSON: `{ course_files: { 'CAY-3': {...}, ... } }` |
| `/api/hod/sbte-audit/fetch-staff-activities` | `GET` | HOD, Principal | `academic_year` | Queries `staff_professional_activities` joined with `staff_profiles`. | JSON: `{ activities: { publication: [...], gap_in_syllabus: [...], fdp_attended: [...], ... } }` |

---

## 9. Print / PDF / Export Pipeline

- **Print Route:** `/hod/sbte-audit/print`
- **Print View:** [`resources/views/hod_sbte_audit_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_sbte_audit_print.blade.php) (579 lines)
- **Format:** A4 Portrait (`@page { size: A4 portrait; margin: 0.5cm; }`), monochrome high-contrast tables.
- **Includes:** Directorate of Technical Education (Govt. of Kerala) official header, Part C Program Details, all 16 criteria tables, and three official signature blocks (HOD, Principal, Auditor).
- **Migration Status:** **DO NOT MODIFY DURING UI MIGRATION**.

---

## 10. Design System Violations in Legacy Interactive View

| Category | Finding | CampusLynk Requirement |
|:---|:---|:---|
| **External CDNs** | `@tailwindcss/browser@4`, Google Fonts `Inter`, `Material Symbols Rounded` CDN. | Canonical Vite bundling (`@vite(['resources/css/app.css', 'resources/js/app.js'])`). |
| **Dark Legacy Backgrounds** | `#020617` body background, `#0b1329` radial gradients, `bg-slate-950/60` cards. | `#FAFAFB` canvas with clean `#FFFFFF` cards and `border-slate-200/80`. |
| **Micro-Fonts** | Global CSS forces `font-size: 0.76rem !important` (12px) on all inputs and tables. | Minimum 14px (`text-sm`) interactive typography for excellent readability. |
| **Icons** | Material Symbols (`verified_user`, `arrow_back`, `save`, `print`). | Native Lucide icons (`award`, `arrow-left`, `save`, `printer`, `sparkles`, `plus`, `trash-2`). |
| **Card Hierarchy** | 16 long stacked cards requiring extensive vertical scrolling. | Structured tabbed or segmented criteria navigation (e.g., Criteria 1–5, 6–10, 11–15). |

---

## 11. Responsive Audit

- **1440px (Desktop):** Functional but visually overwhelming due to endless vertical stacking of 16 dense criteria blocks.
- **1024px / 768px (Tablet):** Multi-column grid inputs wrap abruptly; table action buttons lack touch target padding.
- **375px (Mobile):** Horizontal overflow on wide tables (Criterion 3, 4, 8, 11, 14); micro-fonts (12px) create severe eye strain.

---

## 12. Comparison With Existing CampusLynk HOD Sub-Desks

| Attribute | Workload Sub-Desk (Phase 2C.5B) | Academic Calendar Sub-Desk (Phase 2C.6) | SBTE Audit Sub-Desk (Recommended) |
|:---|:---|:---|:---|
| **Layout Shell** | `<x-layouts.app-shell>` | `<x-layouts.app-shell>` | `<x-layouts.app-shell>` |
| **Canvas & Theme** | `#FAFAFB` with `#FFFFFF` cards | `#FAFAFB` with `#FFFFFF` cards | `#FAFAFB` with `#FFFFFF` cards |
| **Typography** | Poppins (14px min) | Poppins (14px min) | Poppins (14px min) |
| **Icon Set** | Lucide | Lucide | Lucide |
| **Navigation** | Breadcrumb to Report Centre | Breadcrumb to Report Centre | Breadcrumb to Report Centre |
| **Workflows** | Single + Consolidated Timetables | SITTTR Upload + AI Parse | 16 Criteria + 3 DB Generation APIs |

---

## 13. Recommended Modern UI Architecture

```
CampusLynk App Shell (<x-layouts.app-shell>)
│
├── Topbar: "SBTE Academic Audit" (activeNav: report_centre)
│
└── Breadcrumb: Report Centre  /  SBTE Academic Audit (Part C)
    │
    ├── Header Card: Department Badge, Academic Year Selector, Auto-Save Status, Print Action
    │
    ├── Guidance Banner: SITTTR Part C Compliance Guidelines
    │
    ├── Criteria Segmented Navigation (Tabbed / Accordion Grouping)
    │   ├── Tab 1: Program, Enrollment & Results (Criteria 1–5)
    │   ├── Tab 2: Faculty, Staff & Teaching-Learning (Criteria 6–10)
    │   └── Tab 3: Course Files, Training & Achievements (Criteria 11–15)
    │
    └── Action Bar:
        ├── "Save Audit Progress" (Primary CTA)
        ├── "Print Part C Report" (Secondary CTA)
        └── Auto-Compilation Triggers (Sparkles Indigo CTAs)
```

---

## 14. Files to Be Modified vs Untouched

- **🟢 Modified View:** [`resources/views/hod_sbte_audit.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_sbte_audit.blade.php) (UI redesign into `<x-layouts.app-shell>`).
- **🔴 Untouched Core Files:**
  - `routes/web.php` (Routes 1337–1596, 1752–1798)
  - `resources/views/hod_sbte_audit_print.blade.php`
  - `sbte_department_audits` database table
  - `student_board_grades`, `batch_subjects`, `course_files`, `staff_professional_activities` tables.

---

## 15. Migration Baseline Inventory Summary

```
============================================================
SBTE AUDIT MIGRATION BASELINE
============================================================

View:
resources/views/hod_sbte_audit.blade.php

Route:
/hod/sbte-audit

Functional Sections / Criteria:
16 distinct criteria sections + 1 master header + 1 context banner

JavaScript Functions:
11 functions (generateAcademicPerformance, generateCourseFiles, fetchStaffActivities, addSocietyRow, addPublicationRow, addFacultyTrainingRow, addFdpConductedRow, addConsultancyRow, addAchievementRow, removeAchievementRow, reindexAchievements)

Critical DOM IDs:
7 container IDs (#auditForm, #societiesContainer, #publicationsContainer, #facultyTrainingContainer, #fdpConductedContainer, #consultancyContainer, #achievementsContainer)

Backend Endpoints / APIs:
6 endpoints (GET /hod/sbte-audit, POST /hod/sbte-audit/save, GET/POST /hod/sbte-audit/print, GET /api/hod/sbte-audit/generate-perf, GET /api/hod/sbte-audit/generate-course-files, GET /api/hod/sbte-audit/fetch-staff-activities)

Print / PDF Pipeline:
1 dedicated template (GET /hod/sbte-audit/print -> hod_sbte_audit_print.blade.php)

Forms:
2 (<form method="GET"> for year switch, <form id="auditForm"> for master save)

Tables:
9 interactive data tables (Criteria 2, 3, 4, 5, 8, 10, 11, 12, 13, 14, 15)

Modals:
0 (Uses inline dynamic row creation)

Business Logic Risk:
LOW (Backend data calculation APIs and JSON upsert logic untouched)

UI Migration Risk:
LOW (Preserving form input names and container IDs guarantees 100% backward compatibility)

Recommended Shell:
<x-layouts.app-shell title="CampusLynk - SBTE Academic Audit" topbarTitle="SBTE Academic Audit" activeNav="report_centre">

Recommended Next Step:
UI migration after baseline approval (Phase 2C.7)
============================================================
```
