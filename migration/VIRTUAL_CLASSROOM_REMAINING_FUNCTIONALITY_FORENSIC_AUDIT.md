# CampusLynk — Virtual Classroom Forensic Audit
# Phase 2E.3 — Comprehensive Audit of Remaining Ecosystem Functionality

**Phase:** Phase 2E.3 — Remaining Functionality Forensic Audit & Migration Architecture  
**Scope:** Complete Virtual Classroom Ecosystem (R2026 Classrooms, R2021 Classrooms, HOD/Principal Audits, Print & Assessment Pipelines)  
**Status:** **READ-ONLY AUDIT COMPLETED — ZERO PRODUCTION FILES MODIFIED**  
**Date:** August 23, 2026  
**Auditor & Lead Architect:** Antigravity AI Pair Programmer  

---

## 1. Executive Summary

This forensic audit establishes the complete functional, technical, and architectural baseline of all remaining unmodernized workspaces in the CampusLynk Virtual Classroom ecosystem.

Following the successful execution and verification of:
- **Phase 2E.1:** Desktop Foundation Shell & Outer Layout
- **Phase 2E.2A:** Course Overview, Syllabus Structure, Outcome Badges, and CO-PO Mapping
- **Phase 2E.2B:** Syllabus PDF Upload, Intelligent Regex/AI Parsing, and Course Module Extraction
- **Phase 2E.2C.1:** R2026 Theory Lesson Planner Modernization (45-Hour Lecture/Series Test Delivery Workspace)
- **Phase 2E.2C.2-1:** R2026 Practicum Theory Lesson Planner (45-Hour Discrete Hourly Delivery Workspace)
- **Phase 2E.2C.2-2:** R2026 Practicum Practical Lab Lesson Planner (45-Hour / 15-Block 3-Hour Chunked Workspace)

This document maps out the remaining **7 classroom interfaces**, **48 primary subtab workspaces**, **54 interactive modals**, **367 client-side JavaScript functions**, **195 backend API/web routes**, **65 print/export templates**, and **22 persistence models/tables**.

---

## 2. Completed Migration Boundaries (Protected Ground)

The following components are **fully modernized and verified**. They must be treated as **strict boundaries** and must not be redesigned or regressed:

```
┌────────────────────────────────────────────────────────────────────────┐
│ COMPLETED & PROTECTED MIGRATION BOUNDARIES                             │
├──────────────────────────────────┬─────────────────────────────────────┤
│ Workspace Component              │ Container / File Reference          │
├──────────────────────────────────┼─────────────────────────────────────┤
│ 1. Desktop Outer Shell           │ virtual_classroom_*.blade.php       │
│ 2. Course Overview & Specs       │ #tab-outline / #*-subcontent-overview│
│ 3. Syllabus PDF Upload & Parser  │ #syllabusUploadModal / parser JS    │
│ 4. CO-PO Articulation Matrix     │ #copoMatrixTable                    │
│ 5. R2026 Theory Lesson Planner   │ #tab-planner in theory classroom    │
│ 6. Practicum Theory Planner      │ #theory-subcontent-planner          │
│ 7. Practicum Lab Planner         │ #lab-subcontent-planner (15 blocks) │
└──────────────────────────────────┴─────────────────────────────────────┘
```

---

## 3. Complete Classroom Ecosystem Inventory

The CampusLynk Virtual Classroom ecosystem is divided into **two curriculum generations** across **seven dedicated Blade views**:

| Generation | Classroom Type | Blade View Path | Lines | Size | Primary Controller |
| :--- | :--- | :--- | :---: | :---: | :--- |
| **R2026** | **Theory Classroom** | `resources/views/r26/virtual_classroom_theory.blade.php` | 3,708 | 248 KB | `R26ClassroomController` |
| **R2026** | **Practicum Classroom** | `resources/views/r26_practicum/virtual_classroom_practicum.blade.php` | 5,315 | 344 KB | `R26VirtualClassroomPracticumController` |
| **R2026** | **Practical / Virtual Lab** | `resources/views/r26_practical/virtual_classroom_practical.blade.php` | 2,287 | 148 KB | `R26VirtualClassroomPracticalController` |
| **R2026** | **Engineering Drawing** | `resources/views/r26_drawing/virtual_classroom_drawing.blade.php` | 1,997 | 132 KB | `R26VirtualClassroomDrawingController` |
| **R2026** | **Health & Physical Ed.** | `resources/views/r26_health_physical/virtual_classroom_health_physical.blade.php` | 1,250 | 82 KB | `R26VirtualClassroomHealthPhysicalController` |
| **R2021** | **Practical Classroom** | `resources/views/virtual_classroom_practical.blade.php` | 978 | 64 KB | `VirtualClassroomPracticalController` |
| **R2021** | **Lecturer Dashboard** | `resources/views/lecturer_dashboard.blade.php` | 6,986 | 452 KB | `LecturerController` |

---

## 4. View-by-View Functional Map

### 1. R2026 Theory Virtual Classroom (`virtual_classroom_theory.blade.php`)

```
TABS & WORKSPACES:
├── [MODERNIZED] tab-outline: Course Overview, Course Outcomes, Modules, CO-PO Matrix
├── [MODERNIZED] tab-planner: 45-Hour Theory Lesson Planner (L + ST)
├── [UNMODERNIZED] tab-cia: Formative / Continuous Internal Assessment (CA1 + CA2)
│   ├── CA1: Assignment 1 (5M), Attendance (5M), Series Test 1 scaled (10M)
│   ├── CA2: Assignment 2 / Seminar / Self-Learning (5M), Attendance (5M), Series Test 2 scaled (10M)
│   └── CIA Consolidated Table (40 Marks CIE Total)
├── [UNMODERNIZED] tab-series: Series Examinations (ST1 & ST2)
│   ├── Question Paper Generation Configuration (Part A 1M/2M + Part B 5M/7M)
│   ├── Bloom's Taxonomy Level Breakdown (Remember, Understand, Apply, Analyze)
│   ├── Series Exam Mark Entry Matrix & Scheme of Valuation
├── [UNMODERNIZED] tab-internals: Final CIE Consolidated Register & HOD Approval
│   ├── Official 7-Grade Revision 2026 Distribution (S, A, B, C, D, E, F)
│   ├── HOD Submission Action, Lock & Approval Pipeline
├── [UNMODERNIZED] tab-attainment: Direct & Indirect CO-PO Attainment Engine
│   ├── Direct Attainment (CIA 40% + ESE 60%)
│   ├── Indirect Attainment (Course Exit Survey + Mid-Semester Feedback)
│   └── 12-PO + 3-PSO Attainment Matrix Calculation
├── [UNMODERNIZED] tab-roster: Student Academic Register & Batch Details
└── [UNMODERNIZED] tab-materials: Virtual Learning Materials & Lecture Notes Upload
```

---

### 2. R2026 Practicum Virtual Classroom (`virtual_classroom_practicum.blade.php`)

```
DUAL-MODE CLASSROOM ARCHITECTURE:
├── MODE 1: THEORY CLASSROOM (#mode-theory)
│   ├── [MODERNIZED] #theory-subcontent-overview: 45-Hour Theory Syllabus
│   ├── [MODERNIZED] #theory-subcontent-planner: 45-Hour Theory Delivery Planner
│   ├── [UNMODERNIZED] #theory-subcontent-sl: Self-Learning Evaluation & Customization (CA - 5 CIA Marks)
│   │   ├── Activity Topic Configuration per CO
│   │   └── Student Splitup Mark Entry Modal (#slMarksModal)
│   ├── [UNMODERNIZED] #theory-subcontent-series: Theory Series Exams (ST1 & ST2 - 30 Marks scaled)
│   ├── [UNMODERNIZED] #theory-subcontent-ese: End Semester Written Exam Mark Entry (60 Marks)
│   ├── [UNMODERNIZED] #theory-subcontent-surveys: Mid-Semester & Course Exit Survey Evaluation
│   ├── [UNMODERNIZED] #theory-subcontent-attendance: Hourly Theory Attendance Log & Calculation
│   ├── [UNMODERNIZED] #theory-subcontent-materials: Theory Study Materials Upload
│   └── [UNMODERNIZED] #theory-subcontent-roster: Student Roster
└── MODE 2: PRACTICAL LAB CLASSROOM (#mode-lab)
    ├── [MODERNIZED] #lab-subcontent-overview: 45-Hour Practical Syllabus
    ├── [MODERNIZED] #lab-subcontent-planner: 15 3-Hour Chunked Lab Session Blocks (data-block-ids)
    ├── [UNMODERNIZED] #lab-subcontent-eval: Continuous Practical Evaluation (CE - 10 CIA Marks)
    │   ├── Table 2.2 Rubrics (Prep 10M, Setup 10M, Obs 5M, Analysis 10M, Viva 10M, Workmanship 5M)
    │   └── Scaled /50 -> 10 CIA Marks Matrix
    ├── [UNMODERNIZED] #lab-subcontent-series: Practical Series Examination (SP1 & SP2 - 35 Marks scaled)
    ├── [UNMODERNIZED] #lab-subcontent-ese: Practical End Semester Exam Marks (60 Marks)
    ├── [UNMODERNIZED] #lab-subcontent-surveys: Practical Indirect Feedback
    ├── [UNMODERNIZED] #lab-subcontent-attendance: Lab Sub-Batch Attendance (Batch A / Batch B)
    ├── [UNMODERNIZED] #lab-subcontent-materials: Lab Experiment Sheets & Manuals
    └── [UNMODERNIZED] #lab-subcontent-roster: Student Lab Batches
```

---

### 3. R2026 Practical / Virtual Lab Classroom (`virtual_classroom_practical.blade.php`)

```
PRACTICAL LAB WORKSPACES:
├── [MODERNIZED] tab-outline: Practical Syllabus & CO Specifications
├── [UNMODERNIZED] tab-experiments: Experiments Inventory & Rubric Mapping (Experiments 1 to N)
├── [UNMODERNIZED] tab-lesson_plan: Lab Daywork Session Planner
├── [UNMODERNIZED] tab-table22: Continuous Daywork Evaluation (Table 2.2 - 6 Rubric Criteria /50)
├── [UNMODERNIZED] tab-table23: Open-Ended Experiments (Table 2.3 - Design, Execution, Report /50)
├── [UNMODERNIZED] tab-series_qp: Practical Series Exam Question Paper Configuration
├── [UNMODERNIZED] tab-table31: Series Practical Exam Evaluation Register (Table 3.1 /50)
├── [UNMODERNIZED] tab-surveys: Student Lab Feedback & Indirect Attainment
├── [UNMODERNIZED] tab-ese: End Semester Practical Board Exam Marks Entry
├── [UNMODERNIZED] tab-summary: Consolidated Lab CIA Summary Sheet (CE 20M + ST 20M + Attend 10M = 50M)
└── [UNMODERNIZED] tab-materials: Lab Manuals & Datasheets
```

---

### 4. R2026 Engineering Drawing Classroom (`virtual_classroom_drawing.blade.php`)

```
ENGINEERING DRAWING WORKSPACES:
├── [UNMODERNIZED] tab-overview: Drawing Specifications, Sheet Sizes, Equipment Requirements
├── [UNMODERNIZED] tab-lesson_plan: Drawing Sheet Plate Delivery Planner
├── [UNMODERNIZED] tab-slots: Continuous Drawing Plate Evaluation (Drawing Accuracy, Linework, Lettering, Dimensions)
├── [UNMODERNIZED] tab-tests: Drawing Series Practical Tests (Test 1, Test 2 /50)
├── [UNMODERNIZED] tab-oee: Open-Ended Creative Drawing Project Evaluations
├── [UNMODERNIZED] tab-ese: End Semester Drawing Exam Marks
├── [UNMODERNIZED] tab-summary: Drawing Consolidated CIE Summary Sheet
└── [UNMODERNIZED] tab-materials: CAD Models, 2D/3D Projection References, PDF Exercises
```

---

### 5. R2026 Health & Physical Education Classroom (`virtual_classroom_health_physical.blade.php`)

```
HEALTH & PHYSICAL EDUCATION WORKSPACES:
├── [UNMODERNIZED] tab-overview: Course Specifications & Wellness Policy
├── [UNMODERNIZED] tab-copo: Health CO-PO Articulation Matrix
├── [UNMODERNIZED] tab-lesson: 30-Hour Physical Activity Schedule
├── [UNMODERNIZED] tab-activity: Continuous Physical Activity & Attendance Log
├── [UNMODERNIZED] tab-fitness: Physical Fitness Battery & Skill Tests (CA1 / CA2)
├── [UNMODERNIZED] tab-summary: Health & Physical Consolidated CIA Summary Sheet
└── [UNMODERNIZED] tab-surveys: Health & Fitness Student Wellness Surveys
```

---

### 6. Lecturer Dashboard — R2021 Theory Classroom (`lecturer_dashboard.blade.php`)

```
REVISION 2021 WORKSPACES:
├── Subject Switcher & Assigned Classroom Cards
├── R2021 Syllabus & Module Specifications
├── R2021 Lesson Planner (60-Hour Format)
├── Assignment Question Generator & Rubrics
├── Internal Assessment (Series Tests 1 & 2, Assignment Marks, Attendance)
├── Direct & Indirect Attainment Engine (12 PO Tables)
├── Question Paper Generation Engine
└── Course File Document Generator (Documents 1 to 14)
```

---

### 7. R2021 Practical Classroom (`virtual_classroom_practical.blade.php`)

```
REVISION 2021 LAB WORKSPACES:
├── Table 2.2: Daywork Evaluation (Revision 2021 Rubrics)
├── Table 2.3: Open-Ended Projects
├── Table 3.1: Series Practical Exam
└── Lab Summary Sheet
```

---

## 5. Remaining Workspace Classification Matrix

| Workspace Feature | Category | State | Risk Level |
| :--- | :--- | :--- | :---: |
| **Self-Learning Evaluation (R26 Theory/Practicum)** | Formative Assessment | Legacy UI, Complete Engine | **MEDIUM** |
| **Continuous Practical Evaluation (Table 2.2 Rubrics)** | Lab Assessment | Legacy UI, Complete Engine | **HIGH** |
| **Series Exam QP Generator & Bloom's Breakdown** | Assessment Config | Legacy UI, Complete Engine | **HIGH** |
| **Series Exam Mark Entry & Answer Schemes** | Summative Assessment | Legacy UI, Complete Engine | **MEDIUM** |
| **Consolidated CIE / Internals (40M CIE)** | Grading / CIE | Legacy UI, Complete Engine | **CRITICAL** |
| **End Semester Exam Marks Entry (ESE 60M)** | Board Exam Marks | Legacy UI, Complete Engine | **MEDIUM** |
| **Direct / Indirect CO-PO Attainment Engine** | Academic Analytics | Legacy UI, Complete Engine | **CRITICAL** |
| **Student Attendance Register & Conduct Log** | Attendance | Legacy UI, Complete Engine | **HIGH** |
| **Course File Document Manager (Docs 1–14 / R26 Docs)** | Quality Assurance / NBA | Partially Modernized | **MEDIUM** |
| **Practical Lab Experiments Inventory & Rubrics** | Practical Planning | Legacy UI, Complete Engine | **LOW** |
| **Drawing Plate / Slot Evaluations** | Drawing Assessment | Legacy UI, Complete Engine | **MEDIUM** |
| **Physical Activity & Fitness Battery Tests** | Health Assessment | Legacy UI, Complete Engine | **LOW** |
| **Survey Evaluation (Mid-Sem & Course Exit)** | Indirect Feedback | Legacy UI, Complete Engine | **LOW** |
| **Study Materials & Digital Resources** | Content Repository | Legacy UI, Complete Engine | **LOW** |

---

## 6. DOM Preservation Matrix

The following DOM element IDs and data attributes are strictly bound to JavaScript calculation engines, modal handlers, and persistence routines:

| Element / Container ID | Associated Workspace | JavaScript Consumer | Preservation Rule |
| :--- | :--- | :--- | :---: |
| `#tab-cia`, `#tab-series`, `#tab-internals` | R2026 Theory Tabs | `switchTab()` | **STRICT ID PRESERVATION** |
| `#theory-subcontent-sl`, `#theory-subcontent-eval` | Practicum Subtabs | `switchTheorySubtab()`, `switchLabSubtab()` | **STRICT ID PRESERVATION** |
| `#slMarksModal`, `#sl_mark_input_{regNo}` | Self-Learning Marks | `openSlMarksModal()`, `saveSlMarks()` | **STRICT ID PRESERVATION** |
| `#experimentEvalModal`, `#exp_prep_{regNo}` | Practical Rubrics | `openExperimentEvalModal()`, `saveExpEval()` | **STRICT ID PRESERVATION** |
| `#seriesExamModal`, `#st_mark_q{qNo}_{regNo}` | Series Exams | `openSeriesModal()`, `saveSeriesMarks()` | **STRICT ID PRESERVATION** |
| `#ciaMarksModal`, `#cia_assign_{regNo}` | CIA Consolidated | `openCiaModal()`, `saveCiaMarks()` | **STRICT ID PRESERVATION** |
| `#eseMarksModal`, `#ese_mark_{regNo}` | ESE Marks | `openEseModal()`, `saveEseMarks()` | **STRICT ID PRESERVATION** |
| `#attainmentConfigModal`, `#target_level_{co}` | CO Attainment | `calculateAttainment()`, `saveAttainment()` | **STRICT ID PRESERVATION** |
| `#courseFileDocModal`, `#doc_upload_{docNo}` | Course File | `openDocModal()`, `saveCourseFileDoc()` | **STRICT ID PRESERVATION** |
| `input[id^="att-status-"]`, `tr[id^="att-row-"]` | Attendance Log | `markAttendance()`, `saveAttendance()` | **STRICT ID PRESERVATION** |

---

## 7. JavaScript Function Matrix

Below is the inventory of critical JavaScript functions across the Virtual Classroom ecosystem:

| Function Name | View / File | Action / Calculation | Classification |
| :--- | :--- | :--- | :---: |
| `saveAllLessonPlans()` | Theory & Practicum | Serializes 90 rows with `data-block-ids` | **DO NOT TOUCH ENGINE** |
| `saveSlMarks()` | Practicum / Theory | Serializes Self-Learning marks per CO | **CAUTION** |
| `saveExperimentMarks()` | Practical / Practicum | Evaluates 6 Table 2.2 Rubric criteria | **DO NOT TOUCH ENGINE** |
| `saveSeriesTheoryMarks()` | Practicum / Theory | Computes scaled 30M $\rightarrow$ 10M CIA | **DO NOT TOUCH ENGINE** |
| `saveSeriesPracticalMarks()` | Practicum / Practical | Computes scaled 35M $\rightarrow$ 10M CIA | **DO NOT TOUCH ENGINE** |
| `calculateCiaTotals()` | Theory & Practicum | Sums CA1 (20M) + CA2 (20M) = 40M CIE | **DO NOT TOUCH ENGINE** |
| `computeCoAttainment()` | Theory & Practical | Calculates Direct (40/60) + Indirect (Surveys) | **DO NOT TOUCH ENGINE** |
| `generateQuestionPaper()` | Theory / Series Exam | Selects Part A & Part B questions per CO | **CAUTION** |
| `saveCourseFileDoc()` | All Classrooms | Persists NBA Documents 1 to 14 | **SAFE UI REFACTOR** |
| `markAttendance()` | Theory & Lab | Updates attendance status per student | **CAUTION** |
| `saveEseMarks()` | All Classrooms | Persists 60M Written/Practical ESE marks | **SAFE UI REFACTOR** |
| `filterAttendanceRows()` | Attendance Tabs | Client-side search and batch filtering | **SAFE UI REFACTOR** |
| `switchTab()`, `switchMode()` | Classroom Shells | Container visibility switcher | **SAFE UI REFACTOR** |

---

## 8. Backend / API Contract Matrix

| HTTP Method | API Endpoint | Controller & Method | Database Operations |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/r26/classroom/practicum/{id}/lesson-plan/save-all` | `R26VirtualClassroomPracticumController@saveAllLessonPlans` | Updates `lesson_plans` (90 rows) |
| `POST` | `/api/r26/classroom/practicum/{id}/evaluate/self-learning/marks` | `R26VirtualClassroomPracticumController@saveSelfLearningMarks` | Updates `r26_practicum_course_files` JSON |
| `POST` | `/api/r26/classroom/practicum/{id}/evaluate/experiment/marks` | `R26VirtualClassroomPracticumController@saveExperimentMarks` | Updates `r26_practicum_experiment_evaluations` |
| `POST` | `/api/r26/classroom/practicum/{id}/series-theory/marks` | `R26VirtualClassroomPracticumController@saveSeriesTheoryMarks` | Updates `r26_practicum_series_theory` |
| `POST` | `/api/r26/classroom/practicum/{id}/series-practical/marks` | `R26VirtualClassroomPracticumController@saveSeriesPracticalMarks` | Updates `r26_practicum_series_practical` |
| `POST` | `/api/r26/classroom/practicum/{id}/ese/marks` | `R26VirtualClassroomPracticumController@saveEseMarks` | Updates `r26_practicum_ese_marks` |
| `POST` | `/api/r26/classroom/course-file/{id}/save-doc` | `R26ClassroomController@saveCourseFileDoc` | Updates `r26_course_file_documents` |
| `POST` | `/api/r26/classroom/{id}/cia-marks/bulk-update` | `R26ClassroomController@bulkUpdateCiaMarks` | Updates `academic_marks` / `r26_course_files` |
| `POST` | `/api/r26/classroom/{id}/attainment/save` | `R26ClassroomController@saveAttainment` | Updates `co_attainments` / JSON |
| `POST` | `/api/staff/attendance/save` | `AttendanceController@saveAttendance` | Inserts `class_logs_attendance` |

---

## 9. Database / Storage Dependency Map

```
DATABASE PERSISTENCE ARCHITECTURE:
├── Core Academic Structure:
│   ├── batch_subjects (84 records — links subjects, batches, staff, schemes)
│   ├── students (1,054 records — master student repository)
│   └── staff_profiles (88 records — faculty and demonstrator profiles)
├── Planning & Syllabus:
│   ├── lesson_plans (952 records — 45h/90h hourly lesson delivery schedules)
│   ├── lesson_plan_templates (62 records — cross-batch master templates)
│   └── syllabus_registry (10 records — syllabus PDF storage & JSON metadata)
├── R2026 Evaluation & Assessment:
│   ├── r26_course_files / r26_course_file_documents (27 records — NBA Course File)
│   ├── r26_practicum_course_files (3 records)
│   ├── r26_practical_course_files / r26_practical_experiment_evaluations
│   ├── r26_drawing_course_files / r26_drawing_slot_evaluations
│   ├── r26_health_physical_course_files / r26_health_physical_fitness_tests
│   └── r26_series_exam_qps / r26_question_bank
└── Attendance & Surveys:
    ├── class_logs_attendance (20 records — subject hourly attendance logs)
    └── mid_semester_surveys / course_exit_surveys
```

---

## 10. Print / Export Dependency Map

The ecosystem maintains **65 dedicated print/report templates** formatted specifically for **A4 portrait/landscape compliance**.

### Key Print Routes:
1. `/r26/classroom/practicum/{id}/print-lesson-plan` $\rightarrow$ Unified 90-Hour Lesson Plan Document.
2. `/r26/classroom/theory/{id}/print-lesson-plan` $\rightarrow$ 45-Hour Theory Lesson Plan Document.
3. `/r26/classroom/course-file/{id}/print-pdf` $\rightarrow$ Complete NBA Course File (Documents 1–14).
4. `/r26/classroom/series-exams/{id}/print-qp` $\rightarrow$ Formatted Series Exam Question Paper with Bloom's mapping.
5. `/r26/classroom/series-exams/{id}/print-scheme` $\rightarrow$ Official Scheme of Valuation & Scoring Guide.
6. `/r26/classroom/internals/{id}/print-cie` $\rightarrow$ Consolidated 40M CIE Internal Mark Sheet.
7. `/r26/classroom/practicum/{id}/attendance/print` $\rightarrow$ Subject Attendance Summary & Condonation Register.

> [!IMPORTANT]
> All print templates rely on server-side Blade rendering with inline CSS media `@media print`. They must not be modified or converted to dynamic client-side HTML during UI modernizations.

---

## 11. Cross-Role Dependency Map

| Role | Access Level | Primary Virtual Classroom Interactions |
| :--- | :--- | :--- |
| **Lecturer / Faculty** | Full Read/Write | Owns lesson planning, attendance entry, assignment evaluation, series exams, CIE mark submission. |
| **Tutor / Class Advisor** | Roster & Mentoring | Monitors aggregate student attendance, leaves, condonation reports, mentoring records. |
| **HOD (Head of Dept)** | Audit & Approval | Reviews lesson plan progress, verifies CIE registers, approves series exam question papers, audits NBA course files. |
| **Principal** | Executive Audit | Accesses institutional academic digests, overall syllabus completion percentages, department pass projections. |
| **Student** | Read-Only | Views personal attendance percentage, internal CIE marks, study materials, submitted assignment receipts. |

---

## 12. Mobile Preservation Analysis

- The mobile interface for staff is located at `resources/views/staff_mobile_dashboard.blade.php`.
- Student mobile views use separate lightweight mobile layouts.
- **Rule:** Zero desktop CSS refactoring or DOM alterations must leak into `staff_mobile_dashboard.blade.php`. Desktop modernization must strictly use Tailwind utility classes scoped to desktop container IDs.

---

## 13. Legacy UI Forensic Analysis

The remaining unmodernized workspaces suffer from common legacy design patterns:
1. **Dark Glassmorphism & High-Contrast Visual Fatigue:** Excessive use of `bg-slate-900`, `bg-slate-950`, `border-slate-800`, and glowing text shadows (`text-shadow`).
2. **Micro-Fonts (< 12px):** Inputs and table cells using `text-xs` (10px–11px), causing severe eye strain.
3. **Modal Overcrowding:** Complex mark entry matrices crammed into fixed-width popups without sticky headers.
4. **Scattered Action Controls:** Save, Print, and Filter buttons placed inconsistently across different subtabs.

### Target CampusLynk Visual Design Language:
- **Canvas:** `#FAFAFB` clean light background.
- **Cards:** Crisp `#FFFFFF` surface cards with `border-slate-200/80` and subtle elevation (`shadow-xs`).
- **Typography:** Minimum 14px (`text-sm` or `text-base`) for inputs, controls, forms, table data, and labels across all user and staff interfaces.
- **Colors:** Solid high-contrast text; Emerald for Completed/Approved; Amber for Pending/In-Progress; Indigo/Purple for Academic Accents.
- **Icons:** Modern Lucide vector SVGs.

---

## 14. Functional Risk Matrix

| Classroom / Workspace | UI Risk | JS Risk | Backend Risk | Data Risk | Print Risk | Mobile Risk | Overall Risk |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **Continuous Practical Evaluation (CE 10M Rubrics)** | HIGH | HIGH | MEDIUM | MEDIUM | LOW | LOW | **HIGH** |
| **Theory & Practical Series Exam Workspaces** | HIGH | HIGH | HIGH | MEDIUM | MEDIUM | LOW | **HIGH** |
| **Consolidated Internal Assessment (40M CIE)** | HIGH | CRITICAL | HIGH | CRITICAL | HIGH | LOW | **CRITICAL** |
| **Direct & Indirect CO-PO Attainment Engine** | HIGH | CRITICAL | HIGH | CRITICAL | HIGH | LOW | **CRITICAL** |
| **Self-Learning Evaluation (5 CIA Marks)** | MEDIUM | MEDIUM | LOW | LOW | LOW | LOW | **MEDIUM** |
| **Course Attendance Register & Hourly Logs** | MEDIUM | HIGH | MEDIUM | MEDIUM | MEDIUM | LOW | **MEDIUM** |
| **R2026 Practical / Virtual Lab Classroom** | HIGH | HIGH | MEDIUM | MEDIUM | LOW | LOW | **HIGH** |
| **R2026 Engineering Drawing Classroom** | HIGH | HIGH | MEDIUM | MEDIUM | LOW | LOW | **HIGH** |
| **R2026 Health & Physical Education Classroom** | MEDIUM | MEDIUM | LOW | LOW | LOW | LOW | **MEDIUM** |
| **Course File Document Manager (NBA Docs 1–14)** | MEDIUM | MEDIUM | LOW | LOW | LOW | LOW | **MEDIUM** |
| **Study Materials & Video Resources** | LOW | LOW | LOW | LOW | LOW | LOW | **LOW** |

---

## 15. Recommended Migration Priority & Phasing Strategy

To ensure zero downtime, absolute preservation of assessment algorithms, and clean incremental verification, the remaining Virtual Classroom modernizations should follow this structured sequence:

```
┌────────────────────────────────────────────────────────────────────────┐
│ RECOMMENDED PHASE ROADMAP (PHASE 2E.3 ONWARDS)                         │
├───────────────┬───────────────────────────────────┬────────────────────┤
│ Phase         │ Focus Area / Target Scope         │ Rationale          │
├───────────────┼───────────────────────────────────┼────────────────────┤
│ Phase 2E.3A   │ Formative Assessment & Evaluation │ Self-Learning (5M) │
│               │ - R26 Theory & Practicum SL       │ & Table 2.2 Rubrics│
│               │ - Practical Lab Table 2.2 Rubrics │ are core daily ops.│
├───────────────┼───────────────────────────────────┼────────────────────┤
│ Phase 2E.3B   │ Series Examinations & QP Generator│ Unifies Question   │
│               │ - Series Tests 1 & 2 Mark Entry   │ Paper generator &  │
│               │ - Bloom's Taxonomy QP Generator   │ Series mark entry. │
├───────────────┼───────────────────────────────────┼────────────────────┤
│ Phase 2E.3C   │ Course Attendance & Student Roster│ High-frequency     │
│               │ - Hourly Attendance Logging       │ staff workflow;    │
│               │ - Condonation & Percentage Engine │ zero grading risk. │
├───────────────┼───────────────────────────────────┼────────────────────┤
│ Phase 2E.3D   │ Consolidated CIE & Grade Approval │ Critical milestone │
│               │ - 40M CIE Internal Mark Sheets    │ for semester marks │
│               │ - R2026 7-Grade Scale (S to F)    │ & HOD lock/submit. │
├───────────────┼───────────────────────────────────┼────────────────────┤
│ Phase 2E.3E   │ Specialized Classrooms            │ Extends design     │
│               │ - R2026 Engineering Drawing       │ system to remaining│
│               │ - R2026 Health & Physical Ed.     │ niche subjects.    │
├───────────────┼───────────────────────────────────┼────────────────────┤
│ Phase 2E.3F   │ CO-PO Attainment & NBA Course File│ Complex analytics  │
│               │ - Direct & Indirect Attainment    │ and multi-document │
│               │ - NBA Course File Docs 1 to 14    │ generation engine. │
└───────────────┴───────────────────────────────────┴────────────────────┘
```

---

## 16. Explicit "DO NOT TOUCH" Boundaries

During all subsequent UI modernization phases, the following rules remain mandatory:

1. **NO Controller Logic Changes:** Do not alter calculation formulas, grade point mappings, or request handlers in `R26ClassroomController`, `R26VirtualClassroomPracticumController`, `R26VirtualClassroomPracticalController`, etc.
2. **NO Database Schema Migrations:** Do not alter table schemas, drop columns, or create new required database columns.
3. **NO Modification of Evaluation Algorithms:**
   - Table 2.2 Scaling: Criteria 1–6 (50 Marks) $\rightarrow$ scaled to 10 CIA marks.
   - Series Test Scaling: 30/35/50 Marks $\rightarrow$ scaled to 10 CIA marks.
   - Revision 2026 Official 7-Grade Scale: Strict adherence to `S, A, B, C, D, E, F`.
4. **NO Print Template Redesigns:** Print Blade templates must remain server-side rendered A4 documents.
5. **NO Changes to Mobile Dashboard:** `staff_mobile_dashboard.blade.php` must remain untouched.

---

## 17. Final Audit Conclusion

The forensic audit of the CampusLynk Virtual Classroom ecosystem is complete. The system architecture, database bindings, JavaScript calculation engines, and print pipelines have been cataloged with zero production code modifications.

The immediate recommended next step is:  
👉 **Phase 2E.3A — Formative Assessment & Practical Evaluation Modernization** (Self-Learning Evaluation in Theory/Practicum and Table 2.2 Continuous Lab Rubrics).

---
*End of Forensic Audit.*
