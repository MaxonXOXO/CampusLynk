# CampusLynk — Virtual Classroom Forensic Architecture & UI Audit
## Phase 2E.0 — Forensic Audit & Baseline Report

> **Document Status:** Authoritative Forensic Baseline  
> **Audit Type:** Read-Only Codebase & UI Forensic Audit (No production modifications)  
> **Target Subsystem:** Virtual Classroom (Theory, Practical, Practicum, Drawing Hall, Health & Physical, Mobile, Course Files)  
> **Academic Regulations Covered:** State Board of Technical Education (SBTE) Revision 2021 & Revision 2026 (R2026)  
> **Date:** August 23, 2026

---

## 1. Executive Summary

A comprehensive, read-only forensic audit of the **Virtual Classroom** subsystem across the entire repository was conducted. The audit reveals that CampusLynk manages **six distinct classroom modalities** across two active academic regulations (Revision 2021 and Revision 2026), comprising **11 primary Blade views, 8 specialized backend controllers, 76 dedicated API endpoints, 80 tables, 411 interactive buttons, 280 form inputs, and 418 client-side JavaScript functions spanning 21,994 lines of view code**.

### Key Forensic Findings:

1. **Subsystem Fragmentation:**
   - **Revision 2021 Theory Classroom:** Embedded directly within `resources/views/lecturer_dashboard.blade.php` (6,937 lines) inside a monolithic panel (`#panelClassroom`) styled with hardcoded dark backgrounds (`#060b13`), custom CSS overrides, and inline styles.
   - **Revision 2026 Theory Classroom:** Housed in a standalone view `resources/views/r26/virtual_classroom_theory.blade.php` (3,137 lines) using an independent layout shell with 10 internal tab workspaces.
   - **Revision 2026 Practicum (Joint Theory + Lab):** Housed in `resources/views/r26_practicum/virtual_classroom_practicum.blade.php` (4,458 lines), featuring specialized dual-component evaluations (Theory CIE + Practical CIE + Experiment continuous evaluation + Series QP generation).
   - **Revision 2026 Practical (Lab-Only):** Housed in `resources/views/r26_practical/virtual_classroom_practical.blade.php` (2,298 lines).
   - **Revision 2026 Virtual Drawing Hall:** Housed in `resources/views/r26_drawing/virtual_classroom_drawing.blade.php` (1,820 lines), featuring drawing plate slots, practical tests, and OEE.
   - **Revision 2026 Health & Physical Education:** Housed in `resources/views/r26_health_physical/virtual_classroom_health_physical.blade.php` (1,009 lines), featuring fitness testing and physical activity assessments.
   - **Revision 2021 Practical Classroom:** Housed in `resources/views/virtual_classroom_practical.blade.php` (884 lines).

2. **UI & Architectural Debt:**
   - Monolithic views containing thousands of lines of unmodularized HTML and JavaScript.
   - 411 raw `<button>` elements and 280 raw `<input>` elements without shared component abstraction.
   - 58 raw `<select>` dropdowns instead of the standardized CampusLynk searchable custom select component.
   - Micro-fonts (`text-xs`, `text-[11px]`, `text-[10px]`) pervasive across mark-entry grids and rubrics (violating the minimum 14px `text-sm` rule).
   - Redundant external CDN dependencies (`flatpickr`, `xlsx`, `lucide`, `google-fonts`) loaded repeatedly across view headers rather than via the Vite pipeline.

3. **Academic Integrity:**
   - The backend business logic, evaluation rubrics, grade calculators (R2026 7-grade scale `S, A, B, C, D, E, F`), Question Paper generators, and PDF rendering pipelines are mathematically sound and deeply integrated.
   - The migration must be strictly **UI-only**, completely preserving all calculation engines, database models, session contracts, and JavaScript calculation routines.

---

## 2. Complete Implementation Inventory

| Modality / Implementation | Blade View | Primary Controller | Lines | Size | Key Scope |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **R2021 Theory Classroom** | [`lecturer_dashboard.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/lecturer_dashboard.blade.php) (`#panelClassroom`) | `ClassroomController.php` | 6,937 | 385.5 KB | Syllabus, Lesson Plan, Assignments (CO1-CO4), Summative Tests, Question Bank, Online Tests, Seminars |
| **R2021 Practical Classroom** | [`virtual_classroom_practical.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/virtual_classroom_practical.blade.php) | `ClassroomController.php` / `VirtualClassroomPracticalController.php` | 884 | 51.4 KB | Experiments list, Lab evaluations, Practical tests, Rubric scoring |
| **R2026 Theory Classroom** | [`r26/virtual_classroom_theory.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/r26/virtual_classroom_theory.blade.php) | `R26ClassroomController.php` | 3,137 | 180.1 KB | 10 Tabs: Overview, Lesson Plan, CIA Marks, Self-Learning, Assignments, 4 Series Tests, ESE Marks, Attainment, Surveys, Course File |
| **R2026 Practical (Lab-Only)** | [`r26_practical/virtual_classroom_practical.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/r26_practical/virtual_classroom_practical.blade.php) | `R26VirtualClassroomPracticalController.php` | 2,298 | 133.9 KB | 8 Tabs: Overview, CO-PO, Experiments, Lesson Plan, Continuous Eval, Open-Ended, Series Exams, ESE Marks |
| **R2026 Practicum (Joint)** | [`r26_practicum/virtual_classroom_practicum.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/r26_practicum/virtual_classroom_practicum.blade.php) | `R26VirtualClassroomPracticumController.php` | 4,458 | 275.1 KB | Joint Theory & Lab: CO-PO Matrix, 60h/75h Lesson Plans, Continuous Lab Eval, Series Theory Tests, Series Lab Tests, Self-Learning Splitup, QP Generator |
| **R2026 Drawing Hall** | [`r26_drawing/virtual_classroom_drawing.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/r26_drawing/virtual_classroom_drawing.blade.php) | `R26VirtualClassroomDrawingController.php` | 1,820 | 111.7 KB | Drawing Plate Slot Evaluations (Slots 1–10), Practical Test 1 & 2, Open-Ended Exercises, Series QP generator |
| **R2026 Health & Physical** | [`r26_health_physical/virtual_classroom_health_physical.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/r26_health_physical/virtual_classroom_health_physical.blade.php) | `R26VirtualClassroomHealthPhysicalController.php` | 1,009 | 65.1 KB | Physical Activity Assessment, Fitness Tests (Cooper 12-min run, Shuttle run, etc.), ESE grades |
| **Course Files Dashboard** | [`course_files_dashboard.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/course_files_dashboard.blade.php) | `CourseFileController.php` | 819 | 53.0 KB | 25 Mandatory NBA/SBTE Course File Items, Live Completion Ledger, A4 Preview & PDF Export |
| **R2026 Course File Prep** | [`r26/course_file_preparation.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/r26/course_file_preparation.blade.php) | `R26ClassroomController.php` | 312 | 15.5 KB | Theory course file compilation & digital ledger |
| **R2026 Practical Course File** | [`r26_practical/course_file_preparation.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/r26_practical/course_file_preparation.blade.php) | `R26VirtualClassroomPracticalController.php` | 163 | 8.4 KB | Practical lab course file generation |
| **R2026 Practicum Course File** | [`r26_practicum/course_file_preparation.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/r26_practicum/course_file_preparation.blade.php) | `R26VirtualClassroomPracticumController.php` | 157 | 7.8 KB | Practicum joint course file generation |

---

## 3. Architecture & Navigation Map

```
                     Faculty / HOD / Lecturer Entry
                                  │
                                  ▼
                   ┌──────────────────────────────┐
                   │  My Batches (Faculty Desk)   │
                   │   GET /dashboard/lecturer    │
                   └──────────────┬───────────────┘
                                  │
      ┌───────────────────────────┼───────────────────────────┐
      │ (Select Batch & Subject)  │                           │
      ▼                           ▼                           ▼
┌──────────────┐          ┌──────────────┐          ┌───────────────────┐
│ Revision 2021│          │ Revision 2026│          │   Course Files    │
│  Classrooms  │          │  Classrooms  │          │ GET /course-files │
└──────┬───────┘          └──────┬───────┘          └───────────────────┘
       │                         │
       ├─► Theory & Seminar      ├─► Theory Classroom
       │   `#panelClassroom`     │   `GET /r26/classroom/theory/{id}`
       │   (in lecturer_db)      │
       │                         ├─► Practical Classroom (Lab)
       └─► Practical (Lab)       │   `GET /r26/classroom/practical/{id}`
           `GET /classroom/      │
            practical/{id}`      ├─► Practicum (Joint Theory + Lab)
                                 │   `GET /r26/classroom/practicum/{id}`
                                 │
                                 ├─► Virtual Drawing Hall
                                 │   `GET /r26/classroom/drawing/{id}`
                                 │
                                 └─► Health & Physical Education
                                     `GET /r26/classroom/health-physical/{id}`
```

---

## 4. Route Map & Backend Endpoints

### 4.1. Revision 2026 Theory Classroom Endpoints

| HTTP Method | URI | Controller Action | Purpose |
| :--- | :--- | :--- | :--- |
| `GET` | `/r26/classroom/theory/{subjectId}` | `R26ClassroomController@viewTheoryClassroom` | Main R2026 Theory Workspace View |
| `POST` | `/api/r26/classroom/{subjectId}/syllabus` | `R26ClassroomController@uploadSyllabus` | Upload & parse syllabus PDF (Course Outline isolation) |
| `POST` | `/api/r26/classroom/{subjectId}/lesson-plans/bulk-update` | `R26ClassroomController@bulkUpdateLessonPlans` | Save/update 60-day lesson plan schedule |
| `GET` | `/r26/classroom/lesson-plan/print/{subjectId}` | `R26ClassroomController@printLessonPlan` | A4 Printable Lesson Plan |
| `POST` | `/api/r26/classroom/{subjectId}/cia-marks/bulk-update` | `R26ClassroomController@bulkUpdateCiaMarks` | Continuous Internal Assessment mark matrix |
| `POST` | `/api/r26/classroom/{subjectId}/self-learning/bulk-update`| `R26ClassroomController@bulkUpdateSelfLearningMarks`| 4-Module Self Learning Assessment scores |
| `GET` | `/r26/classroom/self-learning/print/{subjectId}` | `R26ClassroomController@printSelfLearningReport` | A4 Self Learning Marks report |
| `POST` | `/api/r26/classroom/{subjectId}/assignment/{coTag}` | `R26ClassroomController@saveAssignment` | Save Assignment Question Paper & Rubric |
| `POST` | `/api/r26/classroom/{subjectId}/assignment/{coTag}/notify`| `R26ClassroomController@notifyAssignment` | Send Student Notification for Assignment |
| `GET` | `/r26/classroom/assignment/{subjectId}/print-qp/{coTag}` | `R26ClassroomController@printAssignmentQp` | Print Assignment Question Paper |
| `GET` | `/r26/classroom/assignment/{subjectId}/print-scheme/{coTag}`| `R26ClassroomController@printAssignmentScheme` | Print Assignment Evaluation Scheme |
| `POST` | `/api/r26/classroom/{subjectId}/series-exams/configure` | `R26ClassroomController@configureSeriesExams` | Configure 4 Series Tests schedule |
| `POST` | `/api/r26/classroom/{subjectId}/series-exams/{examId}` | `R26ClassroomController@saveSeriesExam` | Save Series Exam Questions & Blueprint |
| `POST` | `/api/r26/classroom/{subjectId}/series-exams/{examId}/lock` | `R26ClassroomController@lockSeriesExam` | Lock Series Exam against tampering |
| `POST` | `/api/r26/classroom/{subjectId}/series-exams/marks/bulk-update`| `R26ClassroomController@bulkUpdateSeriesExamMarks`| Series Exam mark entry (CO-tagged) |
| `POST` | `/api/r26/classroom/{subjectId}/ese-marks/bulk-update` | `R26ClassroomController@bulkUpdateEseMarks` | End Semester Examination marks |
| `GET` | `/api/r26/classroom/{subjectId}/attainment-summary` | `R26ClassroomController@getAttainmentSummary` | CO-PO Direct & Indirect Attainment stats |
| `GET` | `/r26/classroom/series-exams/{examId}/print-qp` | `R26ClassroomController@printSeriesExamQp` | Print Series Exam QP |
| `GET` | `/r26/classroom/series-exams/{examId}/print-scheme` | `R26ClassroomController@printSeriesExamScheme` | Print Series Exam Scheme |
| `GET` | `/r26/classroom/{subjectId}/series-exams/print-marks` | `R26ClassroomController@printSeriesExamMarks` | Print Series Exam Marks Statement |
| `GET` | `/r26/classroom/{subjectId}/internals/print-cie` | `R26ClassroomController@printInternalMarksheet` | Print Consolidated CIE Marksheet |
| `GET` | `/r26/classroom/{subjectId}/final-results/print` | `R26ClassroomController@printFinalResults` | Print Final Academic Result Ledger |
| `GET` | `/r26/classroom/{subjectId}/nba/attainment-report` | `R26ClassroomController@printAttainmentReport` | Print NBA SAR CO-PO Attainment Report |
| `GET` | `/r26/classroom/course-file/{subjectId}` | `R26ClassroomController@viewCourseFile` | R2026 Course File Digital Folder |
| `POST` | `/api/r26/classroom/course-file/{subjectId}/save-doc` | `R26ClassroomController@saveCourseFileDoc` | Save Course File Document metadata |
| `POST` | `/api/r26/classroom/course-file/{subjectId}/upload-doc` | `R26ClassroomController@uploadCourseFileDocAttachment` | Upload PDF attachment for Course File |
| `GET` | `/r26/classroom/course-file/{subjectId}/print-pdf` | `R26ClassroomController@printCourseFilePdf` | Print Compiled Course File A4 Document |

### 4.2. Revision 2026 Practicum (Joint Theory + Lab) Endpoints

| HTTP Method | URI | Controller Action | Purpose |
| :--- | :--- | :--- | :--- |
| `GET` | `/r26/classroom/practicum/{subjectId}` | `R26VirtualClassroomPracticumController@show` | Practicum Main Workspace |
| `POST` | `/api/r26/classroom/practicum/{subjectId}/syllabus` | `R26VirtualClassroomPracticumController@uploadSyllabus` | Upload & parse Practicum Syllabus |
| `POST` | `/api/r26/classroom/practicum/{subjectId}/copo-matrix/save` | `R26VirtualClassroomPracticumController@saveCoPoMatrix` | Save 6 CO x 7 PO/PSO Correlation Matrix |
| `POST` | `/api/r26/classroom/practicum/{subjectId}/lesson-plan/save-all` | `R26VirtualClassroomPracticumController@saveAllLessonPlans`| Save Practicum Dual Schedule |
| `POST` | `/api/r26/classroom/practicum/{subjectId}/evaluate/experiment` | `R26VirtualClassroomPracticumController@saveExperimentMarks`| Save Continuous Lab Experiment Marks |
| `POST` | `/api/r26/classroom/practicum/{subjectId}/evaluate/series-theory` | `R26VirtualClassroomPracticumController@saveSeriesTheoryMarks`| Save Series Theory Test Marks |
| `POST` | `/api/r26/classroom/practicum/{subjectId}/evaluate/series-practical` | `R26VirtualClassroomPracticumController@saveSeriesPracticalMarks`| Save Series Lab Test Marks |
| `POST` | `/api/r26/classroom/practicum/{subjectId}/evaluate/self-learning/marks` | `R26VirtualClassroomPracticumController@saveSelfLearningMarks`| Save Self-Learning Splitup Marks |
| `POST` | `/api/r26/classroom/practicum/{subjectId}/evaluate/ese` | `R26VirtualClassroomPracticumController@saveEseMarks` | Save Practicum ESE Marks |
| `POST` | `/api/r26/classroom/practicum/{subjectId}/series-qp/generate/{seriesNo}`| `R26VirtualClassroomPracticumController@generateSeriesQp`| AI / Algorithmic Series QP Generator |
| `POST` | `/api/r26/classroom/practicum/{subjectId}/series-qp/save/{seriesNo}` | `R26VirtualClassroomPracticumController@saveSeriesQp` | Save Generated Series QP |
| `GET` | `/r26/classroom/practicum/{subjectId}/series-qp/print-qp/{seriesNo}` | `R26VirtualClassroomPracticumController@printSeriesQpPdf`| Print Series QP |
| `GET` | `/r26/classroom/practicum/{subjectId}/series-qp/print-scheme/{seriesNo}` | `R26VirtualClassroomPracticumController@printSeriesSchemePdf`| Print Series Evaluation Scheme |
| `GET` | `/r26/classroom/practicum/{subjectId}/series-qp/print-key/{seriesNo}` | `R26VirtualClassroomPracticumController@printSeriesAnswerKeyPdf`| Print Series Answer Key |
| `GET` | `/r26/classroom/practicum/{subjectId}/print-lesson-plan` | `R26VirtualClassroomPracticumController@printLessonPlanPdf`| Print Practicum Lesson Plan |
| `GET` | `/r26/classroom/practicum/{subjectId}/print-self-learning-splitup` | `R26VirtualClassroomPracticumController@printSelfLearningSplitupPdf`| Print Self-Learning Splitup PDF |
| `GET` | `/r26/classroom/practicum/{subjectId}/print-self-learning-summary` | `R26VirtualClassroomPracticumController@printSelfLearningSummaryPdf`| Print Self-Learning Summary PDF |
| `GET` | `/r26/classroom/practicum/{subjectId}/attendance-report` | `R26VirtualClassroomPracticumController@printAttendanceReport`| Print Monthly Attendance Report |
| `GET` | `/r26/classroom/practicum/{subjectId}/attendance-consolidated` | `R26VirtualClassroomPracticumController@printConsolidatedAttendanceReport`| Print Consolidated Attendance |

### 4.3. Revision 2026 Practical (Lab-Only), Drawing & Health Endpoints

| HTTP Method | URI | Controller Action | Purpose |
| :--- | :--- | :--- | :--- |
| `GET` | `/r26/classroom/practical/{subjectId}` | `R26VirtualClassroomPracticalController@show` | Practical Lab Workspace |
| `POST` | `/api/r26/classroom/practical/{subjectId}/experiments` | `R26VirtualClassroomPracticalController@saveExperimentsList` | Save Lab Experiments Directory |
| `POST` | `/api/r26/classroom/practical/{subjectId}/evaluate/experiment` | `R26VirtualClassroomPracticalController@saveExperimentMarks` | Continuous Lab Experiment Marks |
| `POST` | `/api/r26/classroom/practical/{subjectId}/evaluate/open-ended` | `R26VirtualClassroomPracticalController@saveOpenEndedMarks` | Open-Ended Experiment Evaluation |
| `POST` | `/api/r26/classroom/practical/{subjectId}/evaluate/series` | `R26VirtualClassroomPracticalController@saveSeriesExamMarks` | Lab Model Test Marks |
| `GET` | `/r26/classroom/drawing/{subjectId}` | `R26VirtualClassroomDrawingController@show` | Virtual Drawing Hall Workspace |
| `POST` | `/api/r26/classroom/drawing/{subjectId}/evaluate/slot` | `R26VirtualClassroomDrawingController@saveSlotMarks` | Drawing Plate Slot Evaluation |
| `POST` | `/api/r26/classroom/drawing/{subjectId}/evaluate/practical-test` | `R26VirtualClassroomDrawingController@savePracticalTestMarks` | Drawing Practical Test Marks |
| `GET` | `/r26/classroom/health-physical/{subjectId}` | `R26VirtualClassroomHealthPhysicalController@show` | Health & Physical Workspace |
| `POST` | `/api/r26/classroom/health-physical/{subjectId}/evaluate/fitness-test` | `R26VirtualClassroomHealthPhysicalController@saveFitnessTestMarks`| Fitness Test Battery Marks |

### 4.4. Revision 2021 Classroom Endpoints

| HTTP Method | URI | Controller Action | Purpose |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/classroom/{subjectId}/syllabus` | `ClassroomController@uploadSyllabus` | Syllabus PDF Parser |
| `GET` | `/api/classroom/{subjectId}/details` | `ClassroomController@getCourseDetails` | Full R2021 Course Data Hydration |
| `POST` | `/api/classroom/{subjectId}/lesson-plans/regenerate` | `ClassroomController@regenerateLessonPlans` | Regenerate Lesson Plan Schedule |
| `POST` | `/api/classroom/{subjectId}/lesson-plans/bulk-update` | `ClassroomController@bulkUpdateLessonPlans` | Update Day/Topic status |
| `POST` | `/api/classroom/{subjectId}/lesson-plans/save-as-template` | `ClassroomController@saveAsTemplate` | Save to `lesson_plan_templates` table |
| `GET` | `/api/classroom/{subjectId}/lesson-plans/load-template` | `ClassroomController@loadTemplate` | Load cross-batch lesson template |
| `GET` | `/api/classroom/{subjectId}/generate-questions` | `ClassroomController@generateAssignmentQuestions` | AI/Rule assignment question generator |
| `POST` | `/api/classroom/{subjectId}/save-assignment-questions` | `ClassroomController@saveAssignmentQuestions` | Store assignment question set |
| `POST` | `/api/classroom/{subjectId}/save-assignment-marks` | `ClassroomController@saveAssignmentMarks` | Student assignment mark entry |
| `POST` | `/api/classroom/{subjectId}/generate-summative-paper` | `ClassroomController@generateSummativePaper` | Summative Test Paper Generator |
| `POST` | `/api/classroom/{subjectId}/save-written-test-marks` | `ClassroomController@saveWrittenTestMarks` | Summative Written Test Mark Entry |
| `GET` | `/api/classroom/{subjectId}/question-bank` | `ClassroomController@getQuestionBank` | Retrieve Question Bank Pool |
| `POST` | `/api/classroom/{subjectId}/question-bank/upload` | `ClassroomController@uploadQuestionBank` | Excel/CSV Question Bank Upload |
| `POST` | `/api/classroom/{subjectId}/question-bank/seed-ai` | `ClassroomController@seedQuestionBankWithAi` | Gemini AI Question Bank Auto-Seeder |
| `GET` | `/api/classroom/{subjectId}/seminar/evaluations` | `ClassroomController@getSeminarEvaluations` | R2021 Seminar Evaluation Records |
| `POST` | `/api/classroom/{subjectId}/seminar/evaluate` | `ClassroomController@saveSeminarEvaluation` | Save Seminar Presentation Marks |
| `GET` | `/classroom/{subjectId}/assignment-report` | `ClassroomController@printAssignmentReport` | Print Assignment Statement |
| `GET` | `/classroom/{subjectId}/summative-report` | `ClassroomController@printSummativeReport` | Print Summative Statement |
| `GET` | `/classroom/{subjectId}/lesson-plan/print` | `ClassroomController@printLessonPlan` | Print Lesson Plan |
| `GET` | `/classroom/{subjectId}/seminar-report` | `ClassroomController@printSeminarReport` | Print Seminar Marks Report |

---

## 5. Revision Comparison & Workflow Matrix

| Dimension | Revision 2021 (R2021) | Revision 2026 Theory (R2026) | Revision 2026 Practicum | Revision 2026 Practical (Lab) |
| :--- | :--- | :--- | :--- | :--- |
| **Academic Scheme** | 2021 Regulation | 2026 Outcome-Based (OBE) | 2026 Outcome-Based (OBE) | 2026 Outcome-Based (OBE) |
| **Subject Types** | Theory / Practical / Seminar | Pure Theory | Joint Theory + Lab (e.g. FSD, IoT) | Pure Laboratory |
| **Grading System** | Percentage / CGPA | Official 7-Grade Scale (`S, A, B, C, D, E, F`) | Official 7-Grade Scale (`S, A, B, C, D, E, F`) | Official 7-Grade Scale (`S, A, B, C, D, E, F`) |
| **Total Target Hours** | 60–75 Hours | 60 Days / Hours | 60h or 75h Dual Structure | 45–60 Hours |
| **Series Tests** | 2 Internal Tests | Exactly 4 Series Tests | 2 Series Theory + 2 Series Lab | 1–2 Lab Model Tests |
| **Continuous Assessment (CIE)** | Internal Written + Assignment + Attendance | CIA (Attendance + Self Learning + Series Tests) | Continuous Experiment Eval + Self Learning + Series Tests | Continuous Experiment Eval + Open-Ended Experiments + Lab Model Exam |
| **Self Learning** | Optional Assignment Rubric | Mandatory 4-Module SLA | Mandatory Self-Learning Splitup | N/A |
| **Course Outcomes (CO)** | CO1 to CO4/CO5 | CO1 to CO4 (Clean filtering of empty COs) | CO1 to CO6 (Direct Theory & Lab CO mapping) | CO1 to CO4 Lab Outcomes |
| **Course File Requirement** | Legacy SBTE 18-doc format | 25 Mandatory NBA/SBTE Items | 25 Mandatory NBA/SBTE Items | Practical Lab Course File |

---

## 6. JavaScript Function & DOM Preservation Matrix

Below is the authoritative inventory of high-stakes DOM elements and JavaScript functions that must be preserved intact during UI modernization:

### 6.1. High-Stakes JavaScript Functions

| Function Name | View Location | Purpose / Action | Critical DOM Dependencies |
| :--- | :--- | :--- | :--- |
| `initClassroom(subjectId)` | `lecturer_dashboard.blade.php` | Hydrates R2021 Virtual Classroom from `/api/classroom/{id}/details` | `#panelClassroom`, `#vcTitle`, `#vcSubtitle` |
| `switchClassroomTab(tabName)` | `lecturer_dashboard.blade.php` | Switches tabs (Structure, Planner, Assessment, Summative, etc.) | `#tabStructure`, `#tabPlanner`, `#tabAssessment`, `#tabSummative` |
| `renderCourseStructure(data)` | `lecturer_dashboard.blade.php` | Renders dynamic Course Outcomes & Modules (filtered) | `#courseStructureContainer`, `#coListContainer` |
| `renderLessonPlanner(plans)` | `lecturer_dashboard.blade.php` | Renders Day-by-Day Lesson Plan Grid | `#lessonPlannerTableBody`, `#planStatusBadge` |
| `bulkSaveLessonPlans()` | `lecturer_dashboard.blade.php` | POSTs modified lesson plans to backend | `#lessonPlannerTableBody` inputs |
| `saveAsTemplate()` | `lecturer_dashboard.blade.php` | Persists lesson plan to `lesson_plan_templates` table | `#btnSaveTemplate` |
| `loadTemplate()` | `lecturer_dashboard.blade.php` | Pulls cross-batch lesson template | `#btnLoadTemplate` |
| `generateQuestionsWithAi()` | `lecturer_dashboard.blade.php` | Calls Gemini AI to generate assignment questions | `#aiPromptInput`, `#generatedQuestionsList` |
| `saveAssignmentMarks()` | `lecturer_dashboard.blade.php` | POSTs student assignment marks matrix | `#assignmentMarksTableBody` inputs |
| `saveSummativeMarks()` | `lecturer_dashboard.blade.php` | POSTs summative test marks | `#summativeMarksTableBody` inputs |
| `switchR26Tab(tabId)` | `r26/virtual_classroom_theory.blade.php` | Switches among 10 R2026 Theory workspaces | `#tab-overview`, `#tab-lesson-plan`, `#tab-cia-marks`, etc. |
| `saveCiaMarks()` | `r26/virtual_classroom_theory.blade.php` | POSTs Continuous Internal Assessment mark matrix | `#ciaMarksTable` inputs |
| `saveSelfLearningMarks()` | `r26/virtual_classroom_theory.blade.php` | POSTs Self Learning marks for Modules 1–4 | `#selfLearningMarksTable` inputs |
| `configureSeriesExams()` | `r26/virtual_classroom_theory.blade.php` | Sets up 4 Series Tests structure | `#seriesConfigModal`, `#seriesExamDate` inputs |
| `saveSeriesExamMarks(seriesNo)` | `r26/virtual_classroom_theory.blade.php` | POSTs Series Exam scores | `#seriesMarksTable_{n}` inputs |
| `saveEseMarks()` | `r26/virtual_classroom_theory.blade.php` | POSTs End Semester Exam scores | `#eseMarksTable` inputs |
| `renderAttainmentCharts()` | `r26/virtual_classroom_theory.blade.php` | Visualizes CO-PO attainment bars & spider radar | `#coAttainmentChart`, `#poAttainmentChart` |
| `switchPracticumTab(tabId)` | `r26_practicum/...` | Switches between Practicum Theory & Practical tabs | `#practicumTabContainer` |
| `saveExperimentMarks(expId)` | `r26_practicum/...` | POSTs lab experiment continuous score | `#expMarksTable` |
| `generatePracticumSeriesQp(no)`| `r26_practicum/...` | Algorithmic Series Question Paper generator | `#seriesQpPreviewModal` |

### 6.2. Critical DOM IDs & Containers

| DOM Element ID | File Context | Type / Function | Preservation Requirement |
| :--- | :--- | :--- | :--- |
| `panelClassroom` | `lecturer_dashboard` | Main R2021 Classroom Container | **MUST PRESERVE** (Toggled by `openClassroom`) |
| `vcTitle` | `lecturer_dashboard` | Header Subject Title | **MUST PRESERVE** (Hydrated by JS) |
| `vcSubtitle` | `lecturer_dashboard` | Header Branch/Sem Subtitle | **MUST PRESERVE** (Hydrated by JS) |
| `tabStructure` | `lecturer_dashboard` | Tab Button: Course Structure | **MUST PRESERVE** |
| `tabPlanner` | `lecturer_dashboard` | Tab Button: Lesson Planner | **MUST PRESERVE** |
| `tabAssessment` | `lecturer_dashboard` | Tab Button: Assignments | **MUST PRESERVE** |
| `tabSummative` | `lecturer_dashboard` | Tab Button: Summative Tests | **MUST PRESERVE** |
| `courseStructureContainer` | `lecturer_dashboard` | Dynamic CO & Modules wrapper | **MUST PRESERVE** (Rendered via JS) |
| `lessonPlannerTableBody` | `lecturer_dashboard` | `<tbody>` for Lesson Plan rows | **MUST PRESERVE** |
| `assignmentMarksTableBody` | `lecturer_dashboard` | `<tbody>` for Assignment Marks | **MUST PRESERVE** |
| `summativeMarksTableBody` | `lecturer_dashboard` | `<tbody>` for Summative Marks | **MUST PRESERVE** |
| `tab-overview` | `r26/virtual_classroom_theory`| Tab Panel: Subject Overview | **MUST PRESERVE** |
| `tab-lesson-plan` | `r26/virtual_classroom_theory`| Tab Panel: 60-Day Planner | **MUST PRESERVE** |
| `tab-cia-marks` | `r26/virtual_classroom_theory`| Tab Panel: CIA Mark Matrix | **MUST PRESERVE** |
| `tab-self-learning` | `r26/virtual_classroom_theory`| Tab Panel: Self-Learning SLA | **MUST PRESERVE** |
| `tab-series-exams` | `r26/virtual_classroom_theory`| Tab Panel: 4 Series Tests | **MUST PRESERVE** |
| `tab-attainment` | `r26/virtual_classroom_theory`| Tab Panel: CO-PO Attainment | **MUST PRESERVE** |
| `tab-course-file` | `r26/virtual_classroom_theory`| Tab Panel: Digital Course File| **MUST PRESERVE** |

---

## 7. Raw UI & Legacy Debt Audit

A detailed line-by-line syntax scan reveals the following legacy debt across the classroom views:

| View File | Raw `<select>` | Raw `<button>` | Raw `<input>` | Raw `<table>` | Micro-Fonts (`text-xs`/`text-[px]`) | Inline `style="..."` | Redundant CDNs |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `lecturer_dashboard.blade.php` | 9 | 93 | 80 | 23 | 114 | 88 | Flatpickr, SheetJS, Lucide, Fonts |
| `r26/virtual_classroom_theory.blade.php` | 12 | 68 | 44 | 15 | 76 | 42 | Flatpickr, ChartJS, Lucide |
| `r26_practicum/virtual_classroom_practicum.blade.php` | 17 | 105 | 39 | 17 | 128 | 56 | Flatpickr, Lucide |
| `r26_practical/virtual_classroom_practical.blade.php` | 11 | 43 | 25 | 4 | 54 | 28 | Lucide |
| `r26_drawing/virtual_classroom_drawing.blade.php` | 6 | 31 | 63 | 8 | 48 | 19 | Lucide |
| `r26_health_physical/...health_physical.blade.php` | 0 | 24 | 10 | 8 | 32 | 14 | Lucide |
| `virtual_classroom_practical.blade.php` | 2 | 21 | 4 | 1 | 22 | 9 | Lucide |
| `course_files_dashboard.blade.php` | 1 | 21 | 6 | 1 | 18 | 12 | Lucide |
| **TOTALS** | **58** | **411** | **280** | **80** | **492** | **268** | **—** |

### Priority Debt Remediation Targets:
1. **Pervasive Micro-Fonts:** 492 instances of `text-xs`, `text-[10px]`, `text-[11px]`, and `text-[9px]` in data grids and rubric tables will be upgraded to `text-sm` (14px) and `text-base` (16px) per the `AGENTS.md` responsive font standard.
2. **Dark Theme / Text Shadows in R2021:** `#panelClassroom` has `#060b13 !important` background with glowing borders. This must be modernized into crisp CampusLynk light/glass surface architecture without text glows.
3. **Raw Dropdowns:** Replace all 58 raw `<select>` elements with `<x-ui.select>` custom search select components.

---

## 8. Mobile Implementation Audit

| View | Mobile Status | Behavior & Handling |
| :--- | :--- | :--- |
| [`student_mobile_dashboard.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/student_mobile_dashboard.blade.php) | **Dedicated Mobile App** | Student self-service: views course outlines, submitted assignments, online tests, and attendance. **DO NOT MODIFY.** |
| [`staff_mobile_dashboard.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/staff_mobile_dashboard.blade.php) | **Dedicated Mobile App** | Staff self-service: mobile attendance punch, daily schedule, leave requests. **DO NOT MODIFY.** |
| [`hod_mobile_dashboard.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_mobile_dashboard.blade.php) | **Dedicated Mobile App** | HOD quick mobile approval desk. **DO NOT MODIFY.** |
| Desktop Classrooms | **Desktop Workspace** | The 6 desktop virtual classrooms are primary desktop web applications with responsive breakpoints (`sm:`, `md:`, `lg:`). |

---

## 9. CampusLynk UI Design System References

The modernized Virtual Classroom will leverage established CampusLynk design system components:

1. **Application Shell & Container:**
   - `<x-layouts.app-shell>` with full-width extended canvas (`w-full` / `max-w-[1600px] mx-auto p-4 sm:p-6 lg:p-8`).
2. **Typography & Hierarchy:**
   - Google Font Poppins (300, 400, 500, 600, 700), minimum 14px (`text-sm`) for data entry and table cells.
3. **Hero Header:**
   - Slate-900 bold titles, branch/regulation pill badges (`bg-blue-50 text-blue-700 font-semibold border border-blue-200/80`), quick action CTAs (`bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-xs`).
4. **Card Surfaces:**
   - Clean white rounded cards (`bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs`).
5. **Interactive Controls:**
   - CampusLynk searchable select component for dropdowns.
   - Smooth animated spin indicators (`animate-spin`) on refresh actions.
   - Pill tabs with active states (`bg-white text-slate-900 shadow-xs` vs `text-slate-600 hover:text-slate-900`).

---

## 10. Recommended Phased Migration Strategy

To guarantee zero regression and zero downtime, the Virtual Classroom migration should be broken down into **7 strictly ordered phases**:

```
┌────────────────────────────────────────────────────────────────────────┐
│ Phase 2E.1 — Virtual Classroom Desktop Foundation & Shell Architecture │
│              (App Shell, Topbar, Hero Card, Role-aware Breadcrumbs)    │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│ Phase 2E.2 — Syllabus Parser, Course Structure & 60-Day Lesson Planner │
│              (Course Outline isolation, Day-by-Day Grid, Templates)    │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│ Phase 2E.3 — Continuous Assessments, Self-Learning & CIA Mark Entry    │
│              (4-Module SLA, Attendance Weightage, CIA Matrix)          │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│ Phase 2E.4 — Series Examinations Management & Question Paper Generator │
│              (4 Series Tests, CO Tagging, Blueprints, PDF Printing)    │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│ Phase 2E.5 — Practicum & Practical Laboratory Virtual Workspaces      │
│              (Joint Theory+Lab, Continuous Exp Scoring, Open-Ended)    │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│ Phase 2E.6 — Drawing Hall & Health & Physical Education Classrooms     │
│              (Plate Slot Evaluations, Fitness Test Battery)            │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
┌───────────────────────────────────▼────────────────────────────────────┐
│ Phase 2E.7 — Digital Course Files Hub & NBA / SBTE Attainment Reports  │
│              (25 Mandatory Items, Live Ledger, SAR Attainment Radar)   │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 11. High-Risk Areas & Mitigation Safeguards

| High-Risk Area | Potential Failure Mode | Mitigation Safeguard |
| :--- | :--- | :--- |
| **Syllabus Parsing Duplication** | Matching outcome tables at top of PDF causes Module 1 to duplicate across other modules. | **Strict Rule:** Always isolate text *after* the first occurrence of "Course Outline". |
| **R2026 Official 7-Grade Scale** | Accidentally using legacy grades (`A+`, `B+`, `O`). | **Strict Rule:** Enforce `S, A, B, C, D, E, F` standard exclusively. |
| **Series Tests Scaling** | Hardcoding 2 tests instead of 4 tests. | **Strict Rule:** Automatically sequence 4 Series Tests to achieve the required 60 target hours. |
| **Lesson Plan Template Loading** | Querying non-existent `template_data` JSON column. | **Strict Rule:** Query rows from `lesson_plan_templates` table (`day_no`, `co_id`, `topic_content`). |
| **Monolithic JS Breakage in R2021** | Modifying JS function names breaks dependent AJAX handlers. | **Strict Rule:** Keep all function signatures, global variables, and DOM element IDs identical. |

---

## 12. Verification & Testing Strategy

Each migration phase will be verified using the standard test harness:
1. **CLI View Compilation:** Verify Blade syntax and variables via PHP CLI test scripts.
2. **Data Hydration Audit:** Execute test AJAX fetches against `/api/classroom/*` and `/api/r26/classroom/*` to ensure full JSON compatibility.
3. **DOM & Asset Integrity:** Run `npm run build` and `php artisan view:clear` to validate Vite bundles and cache consistency.
4. **Multi-Role Validation:** Test view rendering under `Lecturer`, `HOD`, `Tutor`, `Demonstrator`, and `Principal` session contexts.

---

> **Audit Conclusion:**  
> The forensic audit is complete and recorded in `migration/VIRTUAL_CLASSROOM_FORENSIC_AUDIT.md`.  
> No production code has been modified. Ready to proceed with Phase 2E implementation upon instruction.
