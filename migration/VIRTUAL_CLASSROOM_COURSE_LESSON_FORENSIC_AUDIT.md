# CampusLynk Forensic Audit — Phase 2E.2
# Virtual Classroom Course Structure, Syllabus & Lesson Planner

**Audit Date:** August 23, 2026  
**Status:** COMPLETE (READ-ONLY FORENSIC BASELINE)  
**Scope:** Course Structure, Course Outcomes, Syllabus Parsing/Upload, Lesson Planning Workspace, Plan Templates, and Print Templates across **Revision 2021 (R2021)** and **Revision 2026 (R2026)**.  
**Constraint:** READ-ONLY FORENSIC AUDIT — NO PRODUCTION FILES MODIFIED.

---

## 1. Executive Summary

Phase 2E.2 targets the academic planning core of the CampusLynk Virtual Classroom: **Course Structure / Overview**, **Syllabus / Course Outcome Extraction**, **Lesson Planner / Scheduling Workspaces**, **Cross-Batch Templates**, and **Print Layouts**.

Following the successful execution of Phase 2E.1 (Desktop Shell & Foundation Modernization), this forensic audit establishes an exhaustive inventory of the frontend DOM elements, dynamic JavaScript table renderers, backend API endpoints, database schemas, and print engines that power these workflows.

### Fundamental Architectural Tenet:
> **The goal of Phase 2E.2 is a unified, state-of-the-art UI experience (CampusLynk Design System), NEVER a merged academic engine.**
> R2021 and R2026 have distinct curricular rules, credit distribution models, and evaluation criteria that must be preserved with 100% fidelity.

---

## 2. File Inventory

### 2.1 Target Blade Views & Panels

| Component & Target | Path | Role & Primary Focus |
| :--- | :--- | :--- |
| **R2026 Theory Classroom** | `resources/views/r26/virtual_classroom_theory.blade.php` | Course Outline tab (`#tab-outline`), Lesson Planner workspace (`#tab-planner`), Syllabus upload, auto-scale hours, cross-batch templates. |
| **R2026 Practicum Classroom** | `resources/views/r26_practicum/virtual_classroom_practicum.blade.php` | Dual-mode 90h workspace (45h Theory + 45h Lab), Course File document manager, interactive 90h schedule table. |
| **R2026 Practical Virtual Lab** | `resources/views/r26_practical/virtual_classroom_practical.blade.php` | Lab Course Outline (`#tab-outline`), Lab Experiments (Table 2.2), Open-Ended (Table 2.3), Lab Lesson Planner (`#tab-lesson_plan`). |
| **R2026 Drawing Hall** | `resources/views/r26_drawing/virtual_classroom_drawing.blade.php` | 45h Graphics & Drafting Coursework, Syllabus breakdown (`#tab-syllabus`), Plate submission schedule (`#tab-lessonplan`). |
| **R2026 Health & Physical** | `resources/views/r26_health_physical/virtual_classroom_health_physical.blade.php` | 30h Physical Activity Schedule (`#tab-lesson`), CO-PO Articulation Matrix (`#tab-copo`), Day-to-Day Log. |
| **R2021 Lecturer Dashboard** | `resources/views/lecturer_dashboard.blade.php` (`#panelClassroom`) | Legacy SBTE Theory Course Structure (`#courseStructureContent`), Interactive Editable Lesson Planner (`#coursePlannerContent`), syllabus upload card. |
| **R2021 Practical Classroom** | `resources/views/virtual_classroom_practical.blade.php` | Table 2.2 Lab Work Log, Table 2.3 Open-Ended Projects, Table 3.1 Series Tests. |

### 2.2 Print & Export Views (Protected)

| View Path | Output Document Type | Orientation | Target Engine |
| :--- | :--- | :--- | :--- |
| `resources/views/r26/lesson_plan_print.blade.php` | R2026 Theory Syllabus Lesson Planner | A4 Portrait | Browser Print / PDF |
| `resources/views/r26_practicum/lesson_plan_print.blade.php` | R2026 Practicum 90-Hour Combined Planner | A4 Portrait | Browser Print / PDF |
| `resources/views/r26/course_file_pdf.blade.php` | R2026 Comprehensive Course File Dossier | A4 Portrait | Automated PDF / Print |
| `resources/views/r26/course_file_preparation.blade.php` | R2026 Course File Preparation Checklist | A4 Portrait | Browser Print |
| `resources/views/r26_drawing/reports_print.blade.php` | R2026 Drawing Plate & Lesson Plan Report | A4 Landscape/Portrait | Browser Print |
| `resources/views/r26_health_physical/reports_print.blade.php` | R2026 30-Hour Activity Schedule Report | A4 Portrait | Browser Print |

### 2.3 Controllers & Backend Classes

| Controller Name | File Path | Scope & Core Methods |
| :--- | :--- | :--- |
| `ClassroomController` | `app/Http/Controllers/ClassroomController.php` | R2021 `uploadSyllabus`, `getCourseDetails`, `regenerateLessonPlans`, `bulkUpdateLessonPlans`, `saveAsTemplate`, `loadTemplate`, `deleteLessonPlanRow`, `saveTheoryCoPoMapping`. |
| `R26ClassroomController` | `app/Http/Controllers/R26ClassroomController.php` | R2026 `viewTheoryClassroom`, `uploadSyllabus`, `bulkUpdateLessonPlans`, `printLessonPlan`, `saveCourseFileDoc`. |
| `R26VirtualClassroomPracticumController` | `app/Http/Controllers/R26VirtualClassroomPracticumController.php` | R2026 Practicum `uploadSyllabus`, `generate90HourLessonPlan`, `saveLessonPlanRow`, `saveAllLessonPlans`, `saveCoPoMatrix`, `printLessonPlanPdf`. |
| `R26VirtualClassroomPracticalController` | `app/Http/Controllers/R26VirtualClassroomPracticalController.php` | R2026 Practical `uploadSyllabus`, `generateLessonPlan`, `bulkUpdateLessonPlans`, `saveCoPoMapping`. |
| `R26VirtualClassroomDrawingController` | `app/Http/Controllers/R26VirtualClassroomDrawingController.php` | R2026 Drawing `uploadSyllabus`, `generateLessonPlanApi`, `bulkUpdateLessonPlans`, `printLessonPlan`. |
| `R26VirtualClassroomHealthPhysicalController` | `app/Http/Controllers/R26VirtualClassroomHealthPhysicalController.php` | R2026 Health & Phys `uploadSyllabus`, `bulkUpdateLessonPlans`, `printReport`. |
| `CourseFileController` | `app/Http/Controllers/CourseFileController.php` | Cross-curriculum course file documents (`getCourseFile`, `saveCourseFile`, `saveCoPoMapping`). |

---

## 3. Feature Inventory

### 3.1 Course Structure / Overview

| Curricular Modality | Metadata Rendered | Course Outcomes Structure | Modules & Topics Structure | Attainment & Evaluation Mapping |
| :--- | :--- | :--- | :--- | :--- |
| **R2026 Theory** | Title, Code, Credits, L:T:P:R (e.g. 3:0:0:1), Semester, Target Hours (45h/60h), Regulation R2026, CIE (60M) / ESE (40M). | 4 or 5 COs (CO1..CO5) with Blooms Cognitive Levels (`Remember`, `Understand`, `Apply`, `Analyze`, `Evaluate`, `Create`). | 4 Standard Modules (Module I to IV), each with Unit Titles, Topics list, and Allocated Hours. | CO-PO Articulation Matrix (PO1..PO11, PSO1..PSO2) with mapping values 1 (Low), 2 (Medium), 3 (High). |
| **R2026 Practicum** | Title, Code, Credits, Total 90h (45h Theory + 45h Lab), Semester, CIE (60M) / ESE (40M). | 4 to 6 COs mapped jointly to theory topics and lab experiments. | Module I to IV Theory topics + Part B Lab Experiments list. | Dual Theory COs and Practical Performance Competency matrix. |
| **R2026 Practical** | Title, Code, Credits, 45h/60h Lab Hours, Continuous Day Work 30M, Series 15M, OEE 10M, Attendance 5M. | Lab Performance Outcomes (CO1..CO4) emphasizing procedural, psychomotor, and safety skills. | Categorized into Table 2.2 Experiments and Table 2.3 Open-Ended Projects. | Experiment-to-CO rubric mapping with 6-criteria assessment scale. |
| **R2026 Drawing** | Title, Code, 45 Hours Coursework, CIE 60M (CA 20M + Slot 20M + Test 15M + Att 5M), ESE 40M. | Drawing & CAD Outcomes (CO1..CO4) focusing on drafting conventions, projection, and CAD drafting. | Plate slots, Dimensioning, Sectional Views, Isometric projections. | Exercise-to-CO rubric mapping with accuracy and linework standards. |
| **R2026 Health & Phys** | Title, Code, 30 Hours Physical Activity, CIE 60M (Day Work 30M + Fitness 30M), ESE 40M. | Physical Health & Fitness Outcomes (CO1..CO4) covering BMI, posture, warming-up, endurance, sports. | 30 Activity sessions covering calisthenics, track drills, games, and logbook. | Activity-to-CO mapping with physiological and skill execution criteria. |
| **R2021 Theory (Legacy)** | Title, Code, Semester, Regulation R2021, Contact Hours, CIE/ESE Breakdown. | CO1..CO6 with Blooms Cognitive Levels. | Dynamic module cards (Module I to IV/V/VI) with content lists. | CO-PO Matrix with cell click editing and percentage direct attainment linkage. |

---

### 3.2 Syllabus Management & Parsing Engines

```
[ Upload PDF (Max 10MB) ]
            │
            ▼
┌──────────────────────────────────────────────────────────┐
│             Smalot / AI Fallback Extraction              │
├──────────────────────────────────────────────────────────┤
│ 1. Isolate text AFTER first "Course Outline"             │
│ 2. Extract Metadata (Title, Code, Credits, LTPR)         │
│ 3. Extract COs & Cognitive Levels (CO1..CO5)             │
│ 4. Extract Modules I..IV Topics & Target Hours           │
│ 5. Extract Textbooks, References & Online Resources      │
└──────────────────────────────────────────────────────────┘
            │
            ├──────────────────────────┬──────────────────────────┐
            ▼                          ▼                          ▼
┌────────────────────────┐ ┌────────────────────────┐ ┌────────────────────────┐
│  `course_files` Table  │ │ `lesson_plans` Table   │ │ Cross-Batch Templates  │
│ (JSON parsed_modules,  │ │ (Generated Day 1..N,   │ │ (`lesson_plan_         │
│  parsed_cos, etc.)     │ │  Appended 4 Series)    │ │  templates` table)     │
└────────────────────────┘ └────────────────────────┘ └────────────────────────┘
```

#### Syllabus Extraction & Isolation Rule:
> **Module Content Isolation:** Text extraction must strictly discard all outcome tables at the beginning of the syllabus PDF and start parsing modules only *after* the first occurrence of `"Course Outline"`. This prevents duplicate Module I contents and outcome bleeding.

---

### 3.3 Lesson Planner Engine

| Engine Property | R2021 Theory | R2026 Theory | R2026 Practicum | R2026 Practical | R2026 Drawing |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Target Sessions** | Scaled to 45 / 60h | Scaled to 45 / 60h | Exactly 90h (45 Theory + 45 Lab) | Scaled to Lab Slots (typically 15–30 sessions) | Exactly 45h (Plate Exercises) |
| **Series Tests Appending** | Appends exactly 4 Series Tests sequentially | Appends 4 Series Tests sequentially | Embedded Theory/Practical Series | 2 Series Practical Tests (Table 3.1) | 2 Series Drawing Tests |
| **Date Allocation** | Calculated from batch start date & institutional timetable | Timetable matching with proposed/actual dates | Dual timetable schedule (Theory + Lab hours) | Lab day slot matching | Drafting session schedule |
| **Pedagogy / Methodology** | Chalk & Talk, PPT, Blended, Flipped, Activity | Chalk & Talk, Presentation, Group Discussion, Digital | Lecture + Hands-on Lab execution | Hands-on Experimentation, Demonstration | Board Drafting, CAD Modeling |
| **Learning Resources** | Textbooks, Web URLs, NPTEL | Textbooks, E-books, LMS links | Lab Manual, Datasheets, Software | Lab Manual, Virtual Labs, Simulators | Drawing Instruments, CAD Workstation |
| **Completion Tracking** | Status: `Pending`, `Completed`, `Rescheduled` | Status: `Pending`, `Completed`, `Rescheduled` | Status: `Pending`, `Completed`, `Rescheduled` | Status: `Pending`, `Completed` | Status: `Pending`, `Completed` |
| **Remarks** | Free text input per session | Free text input per session | Session-specific notes | Experiment remarks | Plate evaluation remarks |

---

## 4. DOM Contract & Element Preservation Matrix

The following DOM elements are actively referenced by JavaScript event listeners, AJAX payloads, and modal dialogs. **They MUST NOT be deleted, renamed, or have their structural hierarchy broken.**

### 4.1 R2026 Theory Classroom (`r26/virtual_classroom_theory.blade.php`)

| DOM ID | Tag / Element Type | Referenced by JS Function(s) | Role & Preservation Directive |
| :--- | :--- | :--- | :--- |
| `btn-outline` | `<button>` | `switchTab('outline')` | Tab switcher for Course Outline. |
| `btn-planner` | `<button>` | `switchTab('planner')` | Tab switcher for Lesson Planner. |
| `tab-outline-content` | `<div>` | `switchTab()` | Content wrapper for Course Outline & COs. |
| `tab-planner-content` | `<div>` | `switchTab()` | Content wrapper for Lesson Planner workspace. |
| `lesson-plan-table-body` | `<tbody>` | `saveLessonPlanEdits()`, `renderLessonPlanTable()` | Dynamic container for lesson plan table rows. |
| `lp-row-{id}` | `<tr>` | `saveLessonPlanEdits()`, `deleteRow()` | Dynamic row selector containing `data-id` attribute. |
| `lp-proposed-date-{id}` | `<input type="date">` | `saveLessonPlanEdits()` | Proposed session date picker. |
| `lp-actual-date-{id}` | `<input type="date">` | `saveLessonPlanEdits()` | Actual session date picker. |
| `lp-topic-{id}` | `<input type="text">` | `saveLessonPlanEdits()` | Topic content description input. |
| `lp-co-{id}` | `<select>` | `saveLessonPlanEdits()` | Course Outcome selector dropdown (`CO1`..`CO5`). |
| `lp-hours-{id}` | `<input type="number">` | `saveLessonPlanEdits()` | Allocated duration in hours. |
| `lp-status-{id}` | `<select>` | `saveLessonPlanEdits()` | Session completion status dropdown. |
| `lp-pedagogy-{id}` | `<input type="text">` | `saveLessonPlanEdits()` | Teaching pedagogy description. |
| `lp-remarks-{id}` | `<input type="text">` | `saveLessonPlanEdits()` | Session remarks input. |
| `btn-save-planner` | `<button>` | `saveLessonPlanEdits()` | Bulk saves all planner table inputs via AJAX. |
| `btn-save-template` | `<button>` | `saveAsTemplate()` | Saves active plan as cross-batch template. |
| `btn-load-template` | `<button>` | `loadLessonPlanTemplate()` | Loads shared syllabus template into current plan. |
| `syllabus-file-input` | `<input type="file">` | `performSyllabusUpload()` | Hidden file input for Syllabus PDF upload. |
| `modal-upload-syllabus` | `<div>` | `openUploadModal()`, `closeUploadModal()` | Modal dialog wrapper for syllabus extraction. |

---

### 4.2 R2021 Lecturer Dashboard (`lecturer_dashboard.blade.php` `#panelClassroom`)

| DOM ID | Tag / Element Type | Referenced by JS Function(s) | Role & Preservation Directive |
| :--- | :--- | :--- | :--- |
| `tabStructure` | `<button>` | `toggleClassroomTab('structure')` | Tab switcher for R2021 Course Structure. |
| `tabPlanner` | `<button>` | `toggleClassroomTab('planner')` | Tab switcher for R2021 Lesson Planner. |
| `courseStructureContent` | `<div>` | `renderCourseStructure()`, `toggleClassroomTab()` | Container populated by JS for Modules, COs, Textbooks. |
| `coursePlannerContent` | `<div>` | `renderCoursePlanner()`, `toggleClassroomTab()` | Container for interactive lesson plan table. |
| `syllabusUploadBox` | `<div>` | `handleSyllabusUpload()` | Clickable upload trigger box. |
| `syllabusFileInput` | `<input type="file">` | `handleSyllabusUpload()` | Hidden file input for syllabus upload. |
| `syllabusUploadProgress` | `<div>` | `handleSyllabusUpload()` | Progress spinner and status bar container. |
| `syllabusProgressText` | `<span>` | `handleSyllabusUpload()` | Animated status label during PDF parsing. |
| `parseStatusBadge` | `<span>` | `handleSyllabusUpload()`, `loadCourseDetails()` | Badge displaying upload / parsed status. |
| `activeSyllabusCard` | `<div>` | `loadCourseDetails()`, `downloadSyllabusPDF()` | Card shown when syllabus PDF exists in storage. |
| `downloadSyllabusBtn` | `<button>` | `downloadSyllabusPDF()` | Downloads active syllabus PDF via API. |
| `lessonPlanTableBody` | `<tbody>` | `renderCoursePlanner()`, `saveLessonPlanChanges()` | Dynamic lesson plan rows table container. |
| `plan-row-{id}` | `<tr>` | `markPlanDirty()`, `autoSavePlanRow()` | Individual plan row with `data-id` attribute. |
| `generatePlannerModal` | `<div>` | `openGeneratePlannerModal()`, `closeGeneratePlannerModal()` | Modal for re-generating planner from timetable. |

---

### 4.3 R2026 Practicum Classroom (`r26_practicum/virtual_classroom_practicum.blade.php`)

| DOM ID | Tag / Element Type | Referenced by JS Function(s) | Role & Preservation Directive |
| :--- | :--- | :--- | :--- |
| `mode-btn-theory` | `<button>` | `switchMode('theory')` | Switches between Theory and Lab modes. |
| `mode-btn-lab` | `<button>` | `switchMode('lab')` | Switches between Theory and Lab modes. |
| `theory-tab-outline` | `<button>` | `switchTheorySubtab('outline')` | Theory Course Outline subtab switcher. |
| `theory-tab-planner` | `<button>` | `switchTheorySubtab('planner')` | Theory Lesson Planner subtab switcher. |
| `subtab-theory-outline` | `<div>` | `switchTheorySubtab()` | Content container for Theory Course Outline. |
| `subtab-theory-planner` | `<div>` | `switchTheorySubtab()` | Content container for Theory 45h Lesson Planner. |
| `subtab-lab-planner` | `<div>` | `switchLabSubtab()` | Content container for Lab 45h Schedule. |
| `btn-save-practicum-plans` | `<button>` | `saveAllLessonPlans()` | Bulk updates both theory and lab plan rows. |

---

### 4.4 R2026 Practical Classroom (`r26_practical/virtual_classroom_practical.blade.php`)

| DOM ID | Tag / Element Type | Referenced by JS Function(s) | Role & Preservation Directive |
| :--- | :--- | :--- | :--- |
| `btn-outline` | `<button>` | `switchTab('outline')` | Practical Lab Outline switcher. |
| `btn-experiments` | `<button>` | `switchTab('experiments')` | Lab Experiments list switcher. |
| `btn-lesson_plan` | `<button>` | `switchTab('lesson_plan')` | Practical Lesson Planner switcher. |
| `tab-outline` | `<div>` | `switchTab()` | Content wrapper for Lab Course Outline. |
| `tab-experiments` | `<div>` | `switchTab()` | Content wrapper for Table 2.2 experiment setup. |
| `tab-lesson_plan` | `<div>` | `switchTab()` | Content wrapper for Lab Lesson Plan table. |
| `lesson_planner_mode` | `<select>` | `generateLessonTimeline()` | Mode selector (Day-wise / Experiment-wise). |
| `lesson-plan-table` | `<table>` | `generateLessonTimeline()`, `saveLessonPlannerBulk()` | Table element for practical lesson plan. |
| `lesson-plan-rows-container` | `<tbody>` | `generateLessonTimeline()`, `saveLessonPlannerBulk()` | Container for practical lesson plan rows. |

---

## 5. JavaScript Contract & Function Matrix

The following JavaScript functions handle user interactions, calculations, date syncing, modal dialogs, and AJAX backend calls for Course Structure and Lesson Planning:

```
[ User Interaction / Upload / Edit ]
                 │
                 ├──► performSyllabusUpload() ──► POST /api/r26/classroom/{id}/syllabus
                 │
                 ├──► renderCourseStructure() ──► Populates COs, Modules, CO-PO Matrix
                 │
                 ├──► renderCoursePlanner()   ──► Renders Day 1..N rows with Inputs
                 │
                 ├──► markPlanDirty(lpId)     ──► Flags row for batch persistence
                 │
                 ├──► saveLessonPlanEdits()   ──► POST /api/r26/classroom/{id}/lesson-plans/bulk-update
                 │
                 ├──► loadLessonPlanTemplate()──► GET /api/classroom/{id}/lesson-plans/load-template
                 │
                 └──► saveAsTemplate()        ──► POST /api/classroom/{id}/lesson-plans/save-as-template
```

### Granular JavaScript Function Inventory:

| Function Name | View Location | Trigger & Event | DOM Dependencies | API Endpoint Called | Mutation & Risk Assessment |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `handleSyllabusUpload(input)` | `lecturer_dashboard` | `onchange` on file input | `#syllabusUploadBox`, `#syllabusUploadProgress`, `#parseStatusBadge` | `POST /api/classroom/{id}/syllabus` | Uploads PDF, parses text, updates state. **HIGH RISK (DOM-bound).** |
| `loadCourseDetails(subjectId)` | `lecturer_dashboard` | Page load / Tab click | `#courseStructureContent`, `#activeSyllabusCard` | `GET /api/course-files/{id}` | Fetches JSON and calls `renderCourseStructure`. **MEDIUM RISK.** |
| `renderCourseStructure(...)` | `lecturer_dashboard` | Invoked by `loadCourseDetails` | `#courseStructureContent` | None (Client Renderer) | Injects HTML for Outcome Cards, Modules, Textbooks. **HIGH RISK.** |
| `renderCoursePlanner(plans)` | `lecturer_dashboard` | Invoked by `loadCourseDetails` | `#lessonPlanTableBody` | None (Client Renderer) | Builds interactive table rows with datepickers, selects, inputs. **HIGH RISK.** |
| `markPlanDirty(lpId)` | `lecturer_dashboard` | `oninput`, `onchange` on row | `.plan-row`, `#btn-save-planner` | None (State flag) | Adds visual dirty highlight and enables Save button. **LOW RISK.** |
| `saveLessonPlanChanges()` | `lecturer_dashboard` | Click on Save button | `.plan-row[data-dirty="1"]` | `POST /api/classroom/{id}/lesson-plans/bulk-update` | Serializes dirty inputs into JSON array and sends to server. **HIGH RISK.** |
| `regenerateLessonPlan()` | `lecturer_dashboard` | Click on Regenerate button | `#lessonPlanTableBody` | `POST /api/classroom/{id}/lesson-plans/regenerate` | Re-runs date and topic scaling from timetable. **HIGH RISK.** |
| `saveLessonPlanAsTemplate()` | `lecturer_dashboard` | Click on Save Template button | `#lessonPlanTableBody` | `POST /api/classroom/{id}/lesson-plans/save-as-template` | Persists rows into `lesson_plan_templates` table. **MEDIUM RISK.** |
| `loadLessonPlanTemplate()` | `lecturer_dashboard` | Click on Load Template button | `#lessonPlanTableBody` | `GET /api/classroom/{id}/lesson-plans/load-template` | Overwrites active plan rows with template content. **HIGH RISK.** |
| `performSyllabusUpload(input)` | `r26/virtual_classroom_theory` | `onchange` on file input | `#modal-upload-syllabus`, `#progress-bar` | `POST /api/r26/classroom/{id}/syllabus` | Uploads R2026 syllabus PDF, parses 4 modules + COs. **HIGH RISK.** |
| `saveLessonPlanEdits()` | `r26/virtual_classroom_theory` | Click on Save button | `.lesson-plan-row`, `tbody` inputs | `POST /api/r26/classroom/{id}/lesson-plans/bulk-update` | Serializes all table rows into payload. **HIGH RISK.** |
| `saveAllLessonPlans()` | `r26_practicum` | Click on Save Practicum Plan | `#practicum-theory-table`, `#practicum-lab-table` | `POST /api/r26/classroom/practicum/{id}/lesson-plan/save-all` | Collects 90-hour theory and lab schedule inputs. **HIGH RISK.** |
| `generateLessonTimeline()` | `r26_practical` | Click on Generate Timeline | `#lesson-plan-rows-container`, `#lesson_planner_mode` | `POST /api/r26/classroom/practical/{id}/lesson-plan/generate` | Generates lab session dates from experiment list. **HIGH RISK.** |
| `saveLessonPlannerBulk()` | `r26_practical` | Click on Save Practical Plan | `.lesson-plan-row` | `POST /api/r26/classroom/practical/{id}/lesson-plans/bulk-update` | Persists lab lesson plan rows. **HIGH RISK.** |
| `saveLessonPlan()` | `r26_health_physical` | Click on Save Activity Plan | `#tab-lesson` inputs | `POST /api/r26/classroom/health-physical/{id}/lesson-plan/save` | Updates 30-hour physical activity schedule rows. **HIGH RISK.** |

---

## 6. API / Backend Contract Matrix

All API endpoints involved in Course Structure, Syllabus, and Lesson Planning must remain unchanged in route parameters, payload schemas, and response formats:

| HTTP Method & URI | Controller Action | Request Payload Contract | Response JSON Contract | Auth / Role Scope |
| :--- | :--- | :--- | :--- | :--- |
| `POST /api/classroom/{id}/syllabus` | `ClassroomController@uploadSyllabus` | `multipart/form-data`: `syllabus_file` (PDF, max 10MB) | `{"status": "SUCCESS", "message": "...", "data": {"modules": [...], "cos": [...], "target_hours": 45}}` | Session Auth (`userId`, `Lecturer/HOD`) |
| `GET /api/classroom/{id}/syllabus/download` | `ClassroomController@downloadSyllabusFile` | None | Binary PDF Stream (`application/pdf`) | Session Auth |
| `POST /api/classroom/{id}/lesson-plans/bulk-update` | `ClassroomController@bulkUpdateLessonPlans` | `{"plans": [{"id": 12, "day_no": 1, "topic_content": "...", "co_id": "CO1", "allocated_hours": 1, "proposed_date": "2026-09-01", "actual_date": "2026-09-01", "status": "Completed", "pedagogy": "...", "remarks": "..."}]}` | `{"status": "SUCCESS", "message": "Lesson plans updated successfully."}` | Session Auth (`Lecturer/HOD`) |
| `POST /api/classroom/{id}/lesson-plans/regenerate` | `ClassroomController@regenerateLessonPlans` | `{"scale_hours": 45, "include_series_tests": true}` | `{"status": "SUCCESS", "message": "Lesson plans regenerated.", "plans": [...]}` | Session Auth (`Lecturer/HOD`) |
| `POST /api/classroom/{id}/lesson-plans/save-as-template` | `ClassroomController@saveAsTemplate` | `{"subject_code": "2001"}` | `{"status": "SUCCESS", "message": "Template saved for cross-batch use."}` | Session Auth (`Lecturer/HOD`) |
| `GET /api/classroom/{id}/lesson-plans/load-template` | `ClassroomController@loadTemplate` | None | `{"status": "SUCCESS", "data": [{"day_no": 1, "co_id": "CO1", "topic_content": "...", "pedagogy": "...", "remarks": "..."}]}` | Session Auth |
| `DELETE /api/classroom/{id}/lesson-plans/{planId}` | `ClassroomController@deleteLessonPlanRow` | None | `{"status": "SUCCESS", "message": "Row removed."}` | Session Auth |
| `POST /api/classroom/{id}/copo-mapping/save` | `ClassroomController@saveTheoryCoPoMapping` | `{"mappings": {"CO1": {"PO1": 3, "PO2": 2, ...}}}` | `{"status": "SUCCESS", "message": "CO-PO mapping saved."}` | Session Auth |
| `POST /api/r26/classroom/{id}/syllabus` | `R26ClassroomController@uploadSyllabus` | `multipart/form-data`: `syllabus_file` | `{"status": "SUCCESS", "message": "...", "course_file": {...}}` | Session Auth |
| `POST /api/r26/classroom/{id}/lesson-plans/bulk-update` | `R26ClassroomController@bulkUpdateLessonPlans` | `{"plans": [...]}` | `{"status": "SUCCESS", "message": "Saved."}` | Session Auth |
| `POST /api/r26/classroom/practicum/{id}/syllabus` | `R26VirtualClassroomPracticumController@uploadSyllabus` | `multipart/form-data`: `syllabus_file` | `{"status": "SUCCESS", "message": "..."}` | Session Auth |
| `POST /api/r26/classroom/practicum/{id}/lesson-plan/save-all` | `R26VirtualClassroomPracticumController@saveAllLessonPlans` | `{"plans": [{"id": ..., "mode": "L/P", "day_no": ..., "topic": ...}]}` | `{"status": "SUCCESS", "message": "Practicum plan updated."}` | Session Auth |
| `POST /api/r26/classroom/practical/{id}/syllabus` | `R26VirtualClassroomPracticalController@uploadSyllabus` | `multipart/form-data`: `syllabus_file` | `{"status": "SUCCESS", "message": "..."}` | Session Auth |
| `POST /api/r26/classroom/practical/{id}/lesson-plan/generate` | `R26VirtualClassroomPracticalController@generateLessonPlan` | `{"mode": "experiment-wise"}` | `{"status": "SUCCESS", "plans": [...]}` | Session Auth |

---

## 7. Data Model & Storage Architecture

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           `batch_subjects`                              │
│ (id, classroom_id, semester, subject_code, subject_name, subject_type) │
└────────────────────────────────────┬────────────────────────────────────┘
                                     │ 1:1
                                     ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                             `course_files`                              │
│ (id, batch_subject_id, syllabus_pdf_path, parsed_modules [JSON],       │
│  parsed_cos [JSON], parsed_copo [JSON], parsed_textbooks [JSON])        │
└────────────────────────────────────┬────────────────────────────────────┘
                                     │ 1:N
                                     ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                             `lesson_plans`                              │
│ (id, batch_subject_id, day_no, co_id, topic_content, allocated_hours,   │
│  proposed_date, actual_date, status, pedagogy, learning_resources,     │
│  remarks, created_at, updated_at)                                       │
└─────────────────────────────────────────────────────────────────────────┘
                                     ▲
                                     │ Cloned / Seeded via
┌────────────────────────────────────┴────────────────────────────────────┐
│                        `lesson_plan_templates`                          │
│ (id, subject_code, day_no, co_id, topic_content, pedagogy, remarks)     │
└─────────────────────────────────────────────────────────────────────────┘
```

### 7.1 Database Table Schema Details

#### 1. `course_files` Table
- **`batch_subject_id`** (BIGINT): Foreign key linking to `batch_subjects.id`.
- **`syllabus_pdf_path`** (VARCHAR): Relative storage path to uploaded syllabus PDF.
- **`parsed_modules`** (LONGTEXT / JSON): Array of Module objects (`module_no`, `module_title`, `topics`, `hours`).
- **`parsed_cos`** (LONGTEXT / JSON): Array of Outcome objects (`id`, `code`, `description`, `cognitive_level`).
- **`parsed_copo`** (LONGTEXT / JSON): Key-value matrix of CO to PO mapping strengths (`CO1` -> `PO1: 3`).
- **`parsed_textbooks`** (LONGTEXT / JSON): Textbooks, references, and digital learning links.

#### 2. `lesson_plans` Table
- **`id`** (BIGINT AUTO_INCREMENT): Primary Key.
- **`batch_subject_id`** (BIGINT): Links session row to batch subject.
- **`day_no`** (INT): Sequential hour / session number (Day 1..45/60/90).
- **`co_id`** (VARCHAR 10): Course Outcome Tag (`CO1`, `CO2`, `CO3`, `CO4`, `CO5`).
- **`topic_content`** (TEXT): Precise academic topic or lab exercise description.
- **`allocated_hours`** (DECIMAL 3,1): Duration in academic hours (Default `1.0`).
- **`proposed_date`** (DATE): Scheduled delivery date from timetable.
- **`actual_date`** (DATE nullable): Real classroom execution date.
- **`status`** (ENUM / VARCHAR): `Pending`, `Completed`, `Rescheduled`.
- **`pedagogy`** (VARCHAR 255): Teaching method (`Chalk & Talk`, `Presentation`, `Demonstration`, `Activity`).
- **`learning_resources`** (VARCHAR 255): Educational resources (`Textbook`, `NPTEL`, `Lab Manual`, `Slides`).
- **`remarks`** (TEXT nullable): Faculty notes or rescheduling rationale.

#### 3. `lesson_plan_templates` Table
- **`subject_code`** (VARCHAR 20): Subject identifier (e.g. `2001`, `1001`).
- **`day_no`** (INT): Day number.
- **`co_id`** (VARCHAR 10): Outcome tag.
- **`topic_content`** (TEXT): Standardized syllabus topic content.
- **`pedagogy`** (VARCHAR 255): Recommended teaching methodology.
- **`remarks`** (TEXT): Recommended delivery guidelines.

---

## 8. Print & Export Architecture

The print views generate formal academic documentation for institutional compliance, KTU/DTE/SBTE audits, and NBA accreditation inspections.

### 8.1 Print Engine Inventory

| Print Document | Endpoint / Route | Blade View File | Layout & Orientation | Content Sections Rendered |
| :--- | :--- | :--- | :--- | :--- |
| **Theory Lesson Plan** | `/r26/classroom/lesson-plan/print/{id}` | `resources/views/r26/lesson_plan_print.blade.php` | A4 Portrait, Header / Meta Table | Institution Header, Course Metadata, CO-PO Grid, 45/60-Hour Session Table (Day, Date, CO, Topic, Pedagogy, Status, Sign). |
| **Practicum Lesson Plan** | `/r26/classroom/practicum/{id}/print-lesson-plan` | `resources/views/r26_practicum/lesson_plan_print.blade.php` | A4 Portrait, Compact Typography (13px) | Combined 90-Hour Schedule: Part A Theory (Day 1..45) + Part B Practical (Day 1..45). |
| **Drawing Lesson Plan** | `/r26/classroom/drawing/lesson-plan/print/{id}` | `resources/views/r26_drawing/reports_print.blade.php` | A4 Landscape/Portrait | 45-Hour Plate Drafting schedule, CAD software exercises, CA & OEE timeline. |
| **Health & Phys Schedule** | `/r26/classroom/health-physical/{id}/print/lesson-plan` | `resources/views/r26_health_physical/reports_print.blade.php` | A4 Portrait | 30-Hour Physical Activity schedule, BMI calculation, posture drills, sports training log. |
| **Legacy R2021 Plan** | `/classroom/{id}/lesson-plan/print` | `resources/views/lesson_plan_print.blade.php` | A4 Portrait | SBTE Standard Day-wise Lesson Plan with Principal/HOD signature blocks. |

---

## 9. Cross-Version Comparison Matrix

| Architectural Dimension | R2021 Theory (Legacy) | R2026 Theory | R2026 Practicum | R2026 Practical | R2026 Drawing | R2026 Health & Phys |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **Curriculum Code** | Revision 2021 | Revision 2026 | Revision 2026 | Revision 2026 | Revision 2026 | Revision 2026 |
| **Course Outlines** | 5–6 Modules | 4 Standard Modules | 4 Modules + Lab | Experiment List | Plate Units | 4 Activity Units |
| **Course Outcomes** | CO1..CO6 | CO1..CO4/CO5 | CO1..CO5 | CO1..CO4 | CO1..CO4 | CO1..CO4 |
| **Target Hours** | 45 or 60 Hours | 45 or 60 Hours | Exactly 90 Hours | 45 or 60 Hours | Exactly 45 Hours | Exactly 30 Hours |
| **Grading System** | Percentage / Marks | 7-Grade Scale (`S`..`F`) | 7-Grade Scale | 7-Grade Scale | 7-Grade Scale | 7-Grade Scale |
| **Lesson Table Type** | In-place editable inputs | Dense table with save bar | Dual theory/lab tables | Experiment timeline table | Exercise slot table | Activity row table |
| **Cross-Batch Templates** | `lesson_plan_templates` | `lesson_plan_templates` | Unified schema | Seeded from Syllabi | Seeded from Plates | Seeded from Activities |
| **Shared Visual Archetype** | CampusLynk Light UI | CampusLynk Light UI | CampusLynk Light UI | CampusLynk Light UI | CampusLynk Light UI | CampusLynk Light UI |

---

## 10. UI Modernization Opportunities for Phase 2E.2

Phase 2E.2 provides the opportunity to replace legacy table sprawl with a **modern, ergonomic academic workspace**:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│  COURSE IDENTITY HERO                                                       │
│  [ R2026 · THEORY ] [ S1 · CE ] [ 2001 ] Professional Ethics & Practices    │
├─────────────────────────────────────────────────────────────────────────────┤
│  PLANNING METRICS BAR                                                       │
│  • Total Planned: 45 / 45 Hrs   • Completed: 18 (40%)   • Proposed End: Dec │
├────────────────────────┬────────────────────────────────────────────────────┤
│  NAV / OUTLINE TREE    │  LESSON PLANNING WORKSPACE                         │
│  • Module I (10 Hrs)   │  ┌───────────────────────────────────────────────┐ │
│  • Module II (12 Hrs)  │  │ Week 1 · Orientation & Foundations (3 Hrs)    │ │
│  • Module III (11 Hrs) │  ├───────────────────────────────────────────────┤ │
│  • Module IV (12 Hrs)  │  │ Day 1  CO1  [ Topic Input... ]  [Date] [Done] │ │
│  • Series Tests (4)    │  │ Day 2  CO1  [ Topic Input... ]  [Date] [Done] │ │
│  ───────────────────── │  │ Day 3  CO1  [ Topic Input... ]  [Date] [Done] │ │
│  ACTIONS:              │  └───────────────────────────────────────────────┘ │
│  [ Upload Syllabus ]   │  ┌───────────────────────────────────────────────┐ │
│  [ Load Template ]     │  │ Week 2 · Structural Ethics (3 Hrs)            │ │
│  [ Save All Changes ]  │  │ Day 4  CO1  [ Topic Input... ]  [Date] [Done] │ │
│  [ Print A4 Plan ]     │  └───────────────────────────────────────────────┘ │
└────────────────────────┴────────────────────────────────────────────────────┘
```

### Key UI Modernization Patterns:
1. **Course Overview:**
   - Visual Course Outcome Cards with Bloom's Cognitive Level badges.
   - Interactive Module Expansion Cards displaying unit breakdown and hours allocation.
   - Responsive CO-PO Articulation Matrix with clean high-contrast level indicators (1, 2, 3).
2. **Syllabus Management:**
   - Dedicated Drag-and-Drop PDF Upload Card with live progress indicators.
   - Parsed Summary Card displaying extracted Course Outcomes, Modules, and Textbook references.
   - Clear parser status badges (`Extracted & Active`, `Needs Upload`, `Processing`).
3. **Lesson Planner Workspace:**
   - Structured Session Rows with clean datepickers, styled outcome dropdowns, and status pills.
   - Auto-Calculated Progress Bar tracking planned vs completed hours.
   - Quick action toolbar for Save Changes, Auto-Scale, Cross-Batch Template Load/Save, and A4 Print.

---

## 11. Shared Component Opportunities

Phase 2E.2 can leverage the existing CampusLynk Blade component library while preserving all JavaScript ID bindings:

- **Form Controls:** `<x-ui.input>`, `<x-ui.select>`, `<x-ui.button>` for standard form elements.
- **Badges & Pills:** `<x-ui.badge>` for Cognitive Levels, Completion Status, and CO Tags.
- **Card Containers:** `<x-ui.card>` for Course Overview, Module Cards, and Syllabus Upload boxes.
- **Table Components:** `<x-ui.table>` for the dense Lesson Plan workspace with responsive scrolling.
- **Modal Dialogs:** `<x-ui.modal>` for Syllabus PDF upload, Timetable regeneration, and Template management.

---

## 12. Mobile Preservation Boundary

```
╔══════════════════════════════════════════════════════════════════════════════╗
║                       MOBILE BOUNDARY — DO NOT TOUCH                         ║
╠══════════════════════════════════════════════════════════════════════════════╣
║ The following mobile views are strictly protected and MUST NOT be modified:  ║
║                                                                              ║
║ 1. resources/views/staff_mobile_dashboard.blade.php                          ║
║ 2. resources/views/student_mobile_dashboard.blade.php                        ║
║ 3. resources/views/hod_mobile_dashboard.blade.php                            ║
║                                                                              ║
║ Mobile interfaces use dedicated lightweight views and mobile-specific JS.   ║
║ All Phase 2E.2 work is strictly isolated to Desktop Virtual Classrooms.      ║
╚══════════════════════════════════════════════════════════════════════════════╝
```

---

## 13. Migration Risk Classification

| Component / Sub-Feature | Risk Level | Rationale & Critical Safeguards |
| :--- | :--- | :--- |
| **Course Outline Display** | 🟢 **SAFE UI-ONLY** | Purely renders parsed JSON from database. No complex live input state. |
| **CO-PO Matrix Table** | 🟡 **DOM-SENSITIVE** | Table cells trigger click events and persist strengths via AJAX. Matrix keys must match `PO1`..`PO11`. |
| **Syllabus Upload Dropzone** | 🟡 **DOM-SENSITIVE** | File input triggers `performSyllabusUpload` / `handleSyllabusUpload`. Status badges must match IDs. |
| **Lesson Plan Row Inputs** | 🟠 **JS-COUPLED** | Heavy bidirectional JS coupling (`markPlanDirty`, `saveLessonPlanEdits`, `collectPlanRow`). Input names and `data-id` attributes must remain intact. |
| **Auto-Scaling & Series Tests** | 🟠 **JS-COUPLED** | Generates exactly 4 Series Tests sequentially and scales hours dynamically. Algorithm in controller must not be altered. |
| **Template Load / Save** | 🟡 **DOM-SENSITIVE** | Loads rows directly from `lesson_plan_templates` table (`day_no`, `co_id`, `topic_content`). |
| **Print / PDF Templates** | 🔴 **DO NOT TOUCH** | Fixed A4 layout with exact institutional header formatting and signature blocks. |
| **Syllabus Parsing Backend** | 🔴 **DO NOT TOUCH** | Core regex / Smalot PDF text isolation algorithms must remain completely untouched. |

---

## 14. Recommended Phase 2E.2 Implementation Sequence

To ensure zero downtime and absolute behavioral preservation, Phase 2E.2 should be executed in 5 focused, sequential sub-phases:

```
Phase 2E.2A: Course Overview & Outcomes Workspace (R2021 & R2026)
     │
     ▼
Phase 2E.2B: Syllabus Upload, Parsing & Status Cards
     │
     ▼
Phase 2E.2C: Lesson Planner Desktop Workspaces (Theory, Practicum, Practical)
     │
     ▼
Phase 2E.2D: Cross-Batch Templates & Timeline Auto-Scaling Integration
     │
     ▼
Phase 2E.2E: Print Actions & Final Cross-Browser Verification
```

### Detailed Sub-Phase Scope:
1. **Phase 2E.2A — Course Overview & Outcomes:** Modernize Course Outline cards, Blooms Cognitive Level tags, and CO-PO matrix visualization across all 6 classroom views.
2. **Phase 2E.2B — Syllabus Upload & Extraction:** Modernize the upload dropzone, animated progress indicator, and active syllabus summary card.
3. **Phase 2E.2C — Lesson Planner Workspace:** Upgrade the dense session tables into modern, responsive planning workspaces with styled datepickers and status pills.
4. **Phase 2E.2D — Templates & Scheduling:** Enhance template save/load modals and timetable auto-scaling triggers.
5. **Phase 2E.2E — Verification & Reporting:** Execute automated render testing, Vite asset compilation, and compile the final Phase 2E.2 completion report.

---

## 15. Explicit "DO NOT MODIFY" List

During Phase 2E.2 implementation, the following components are strictly out-of-bounds:

1. **DO NOT modify database schemas or migrations:** (`course_files`, `lesson_plans`, `lesson_plan_templates`, `batch_subjects`, etc.).
2. **DO NOT alter syllabus extraction algorithms:** (`Smalot\PdfParser` regex patterns and "Course Outline" text isolation logic).
3. **DO NOT alter lesson plan auto-generation formulas:** (Target hours scaling, exactly 4 Series Tests appending rule).
4. **DO NOT alter API route signatures or JSON contracts:** All routes in `routes/web.php` and controller methods must retain their exact payloads.
5. **DO NOT rename or delete JavaScript function names or DOM element IDs:** All 418 JS functions and DOM identifiers must remain intact.
6. **DO NOT touch print Blade views:** (`lesson_plan_print.blade.php`, `course_file_pdf.blade.php`, etc.).
7. **DO NOT touch mobile views:** (`staff_mobile_dashboard.blade.php`, `student_mobile_dashboard.blade.php`, `hod_mobile_dashboard.blade.php`).
8. **DO NOT merge R2021 and R2026 academic engines:** Shared visual design only; academic logic remains version-specific.
