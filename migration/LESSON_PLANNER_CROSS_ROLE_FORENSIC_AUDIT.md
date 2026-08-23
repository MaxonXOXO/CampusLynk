# CampusLynk — Lesson Planner Cross-Role Forensic Audit

**Phase:** 2E.2C — Lesson Planner & Lesson Plan Workspace Cross-Role Forensic Audit  
**Status:** **READ-ONLY AUDIT COMPLETE** (No application code modified)  
**Date:** August 23, 2026  
**Auditor:** Antigravity AI Pair Programmer & Academic Engine Inspector  

---

## 1. Executive Summary

A comprehensive, repository-wide forensic audit of the **Lesson Planner and Lesson Plan Workspace** was performed across all roles, curricula (Revision 2026 & Revision 2021), database models, controller APIs, client-side scripts, and external consumers (Course Files, Attendance Logs, and A4 Print Generation).

### Key Architectural Discoveries:
1. **Single Source of Truth (`lesson_plans` Table):** All classroom modalities (R2026 Theory, R2026 Practicum, R2026 Practical, R2026 Drawing, R2026 Health & Physical, R2021 Theory, and R2021 Practical) share a single primary database table: `lesson_plans` (`batch_subject_id` foreign key).
2. **Template Persistence (`lesson_plan_templates` Table):** A dedicated cross-batch template table (`lesson_plan_templates`) stores standard lesson plans by `subject_code`, allowing lecturers across different batches and departments to instantly clone verified syllabus plans.
3. **Four Modality-Specific Generation Engines:**
   - **R2026 Theory / R2021 Theory:** Automatic expansion from parsed modules + COs with automatic scaling to 60/75 target hours + 4 sequential Series Tests appended at the end.
   - **R2026 Practicum:** Dual 90-Hour generation splitting exactly into 45 Theory lecture hours (modes `L`, `ST`) and 45 Practical laboratory hours in 3-hour blocks (modes `P`, `SP`).
   - **R2026 Practical:** Lab experiment session generation supporting `Single/Whole Class Batch` and `Split Batch (Batch A/B)`.
   - **R2026 Drawing:** 45-Hour drawing lab schedule covering Manual Drawing (CO1, CO2) & CAD Drafting (CO3, CO4), Series Exams, and OEE.
   - **R2026 Health & Physical:** 30-Hour physical fitness activity schedule.
4. **Attendance Linkage (`AttendanceController@store`):** When faculty logs a period attendance entry referencing `lesson_plan_id`, the system automatically transitions the corresponding lesson plan row's status from `'Pending'` to `'Completed'` and stamps `actual_date`.
5. **Course File Document 8 Linkage (`CourseFileController@previewDocument8`):** Official institutional Course Files dynamically extract and render Document 8 ("Course Plan") by querying `lesson_plans` directly, calculating cumulative syllabus hours and engaged hours.

---

## 2. Discovered Implementations

| Implementation Context | View Path | Primary Controller | Primary Database Table | Active Endpoints |
| :--- | :--- | :--- | :--- | :--- |
| **R2026 Theory Classroom** | `resources/views/r26/virtual_classroom_theory.blade.php` | `R26ClassroomController` | `lesson_plans` | `POST /api/r26/classroom/{id}/lesson-plans/bulk-update`<br>`GET /r26/classroom/lesson-plan/print/{id}` |
| **R2026 Practicum Classroom** | `resources/views/r26_practicum/virtual_classroom_practicum.blade.php` | `R26VirtualClassroomPracticumController` | `lesson_plans`, `r26_practicum_course_files` | `POST /api/r26/classroom/practicum/{id}/lesson-plan/save-all`<br>`POST /api/r26/classroom/practicum/{id}/lesson-plan/save`<br>`GET /r26/classroom/practicum/{id}/print-lesson-plan` |
| **R2026 Practical Classroom** | `resources/views/r26_practical/virtual_classroom_practical.blade.php` | `R26VirtualClassroomPracticalController` | `lesson_plans`, `r26_practical_course_files` | `POST /api/r26/classroom/practical/{id}/lesson-plan/generate`<br>`POST /api/r26/classroom/practical/{id}/lesson-plans/bulk-update`<br>`GET /r26/classroom/lesson-plan/print/{id}` |
| **R2026 Drawing Classroom** | `resources/views/r26_drawing/virtual_classroom_drawing.blade.php` | `R26VirtualClassroomDrawingController` | `lesson_plans`, `r26_drawing_course_files` | `POST /api/r26/classroom/drawing/{id}/lesson-plan/generate`<br>`POST /api/r26/classroom/drawing/{id}/lesson-plan/save`<br>`GET /r26/classroom/drawing/lesson-plan/print/{id}` |
| **R2026 Health & Physical** | `resources/views/r26_health_physical/virtual_classroom_health_physical.blade.php` | `R26VirtualClassroomHealthPhysicalController` | `lesson_plans`, `r26_health_physical_course_files` | `POST /api/r26/classroom/health-physical/{id}/lesson-plan/save`<br>`GET /r26/classroom/health-physical/{id}/print/lesson-plan` |
| **R2021 Theory Classroom** | `resources/views/lecturer_dashboard.blade.php` | `ClassroomController` | `lesson_plans`, `lesson_plan_templates`, `course_files` | `POST /api/classroom/{id}/lesson-plans/regenerate`<br>`POST /api/classroom/{id}/lesson-plans/bulk-update`<br>`POST /api/classroom/{id}/lesson-plans/save-as-template`<br>`GET /api/classroom/{id}/lesson-plans/load-template`<br>`DELETE /api/classroom/{id}/lesson-plans/{planId}`<br>`GET /classroom/{id}/lesson-plan/print` |
| **R2021 Practical Classroom** | `resources/views/lecturer_dashboard.blade.php` & `virtual_classroom_practical.blade.php` | `ClassroomController` | `lesson_plans`, `practical_experiments` | `POST /api/classroom/{id}/practical/lesson-plans/generate`<br>`POST /api/classroom/{id}/save-lesson-plans` |
| **Attendance Logging** | `resources/views/attendance_log.blade.php` | `AttendanceController` | `lesson_plans`, `class_logs_attendance` | `POST /api/attendance/class-log/store` (stamps `actual_date` & `Completed`) |
| **Course File (Doc 8)** | `resources/views/course_files/preview_doc_8.blade.php` | `CourseFileController` | `lesson_plans`, `cf_course_files` | `GET /api/course-files/{id}/preview/8` |

---

## 3. Cross-Role Architecture

```
                                  ┌───────────────────────────────┐
                                  │      Academic Management      │
                                  │   (HOD / Principal / SBTE)    │
                                  └───────────────┬───────────────┘
                                                  │ (Audit & Coverage Monitoring)
                                                  ▼
                         ┌─────────────────────────────────────────────────┐
                         │              Lecturer / Faculty                 │
                         │          (Virtual Classroom Workspace)          │
                         └──────────────┬───────────────────┬──────────────┘
                                        │                   │
                     ┌──────────────────┴──┐             ┌──┴──────────────────┐
                     │ Lesson Plan Creation│             │   Session Execution │
                     │   & Optimization    │             │  & Attendance Logs  │
                     └──────────┬──────────┘             └──────────┬──────────┘
                                │                                   │
                                ▼                                   ▼
             ┌─────────────────────────────────────────────────────────────────┐
             │                   Authoritative Database Records               │
             │           `lesson_plans` & `lesson_plan_templates`              │
             └──────────────────┬───────────────────┬──────────────────────────┘
                                │                   │
                                ▼                   ▼
             ┌─────────────────────────────┐     ┌─────────────────────────────┐
             │   Course File Document 8    │     │   A4 Official Print Out     │
             │  (Institutional Accreditation)    │  (Semester Verification)    │
             └─────────────────────────────┘     └─────────────────────────────┘
```

| Role | Revision | Primary Entry View | Route Endpoint | Controller | Status / Capability |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Lecturer / Faculty** | R2026 | `r26/virtual_classroom_theory.blade.php` | `/r26/classroom/theory/{id}` | `R26ClassroomController` | Full CRUD, Bulk Update, Template Save, Print |
| **Lecturer / Faculty** | R2026 Practicum | `r26_practicum/virtual_classroom_practicum.blade.php` | `/r26/classroom/practicum/{id}` | `R26VirtualClassroomPracticumController` | 90-Hr Theory (45h) + Lab (45h) Planner, Sub-batches, Print |
| **Lecturer / Faculty** | R2026 Practical | `r26_practical/virtual_classroom_practical.blade.php` | `/r26/classroom/practical/{id}` | `R26VirtualClassroomPracticalController` | Single/Split Batch Planner Generation, Batch A/B, Print |
| **Lecturer / Faculty** | R2026 Drawing | `r26_drawing/virtual_classroom_drawing.blade.php` | `/r26/classroom/drawing/{id}` | `R26VirtualClassroomDrawingController` | 45-Hour Drawing Lab Generation, CAD/Manual, Print |
| **Lecturer / Faculty** | R2026 Health & Phys | `r26_health_physical/virtual_classroom_health_physical.blade.php` | `/r26/classroom/health-physical/{id}` | `R26VirtualClassroomHealthPhysicalController` | 30-Hour Activity Schedule, Series Tests, Print |
| **Lecturer / Faculty** | R2021 Theory | `lecturer_dashboard.blade.php` | `/dashboard/lecturer` $\rightarrow$ `openClassroom({id})` | `ClassroomController` | Full Dynamic Regeneration, Row Autosave, Template Load/Save, Print |
| **Lecturer / Faculty** | R2021 Practical | `lecturer_dashboard.blade.php` | `/dashboard/lecturer` $\rightarrow$ `openClassroom({id})` | `ClassroomController` | Experiment-driven Planner Generation (Combined/Separate), Print |
| **Tutor** | All | `resources/views/attendance_log.blade.php` | `/api/attendance/class-log/store` | `AttendanceController` | Consumes `lesson_plans` dropdown during period logging |
| **HOD / Principal** | All | `resources/views/hod_dashboard.blade.php` | `/dashboard/hod` | `HodDashboardController` | Read-only Syllabus Coverage & Attendance Verification |

---

## 4. Classroom Integration

| Classroom View | Tab / Container Identifier | Activation Mechanism | Data Population Mode |
| :--- | :--- | :--- | :--- |
| **R2026 Theory** | Container: `#tab-planner`<br>Button: `switchTab('planner')` | Client Tab Switching | Server-side rendered `@forelse($lessonPlans as $lp)` into `#plannerTableBody` |
| **R2026 Practicum** | Containers:<br>1. `#theory-subcontent-planner`<br>2. `#lab-subcontent-planner` | Subtab triggers:<br>`switchTheorySubtab('planner')`<br>`switchLabSubtab('planner')` | Server-side rendered into 45h Theory (`whereIn('mode', ['L','ST'])`) and 45h Lab (`whereIn('mode', ['P','SP'])`) |
| **R2026 Practical** | Container: `#tab-lesson_plan`<br>Table: `#lesson-plan-table` | `switchTab('lesson_plan')` | Server-side rendered `@forelse($lessonPlans as $lp)` with dynamic batch generation |
| **R2026 Drawing** | Container: `#tab-lessonplan`<br>Table: `#lesson-plan-table` | `switchTab('lessonplan')` | Server-side rendered `@forelse($lessonPlans as $lp)` with auto-grow textareas |
| **R2026 Health & Physical** | Container: `#tab-lesson`<br>Table: Table with 30-Hour schedule | `switchTab('lesson')` | Server-side rendered `@foreach($lessonPlans as $lp)` |
| **R2021 Theory & Practical** | Container: `#coursePlannerContent`<br>Tab: `#tabPlanner` | `switchPanelTab('planner')` | Dynamic AJAX rendering via `renderCoursePlanner(data.data.lesson_plans)` |

---

## 5. Lesson Plan Data Model

### Database Table: `lesson_plans`

| Column | Data Type | Nullable | Default | Business Meaning | Active Consumers |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | NO | Auto-increment | Primary Key | All Controllers, Views, APIs |
| `batch_subject_id` | `BIGINT UNSIGNED` | NO | None | Foreign Key referencing `batch_subjects.id` | All Controllers, Views, APIs |
| `day_no` | `INT` | NO | `1` | Session / Period sequence number ($1 \dots N$) | All Views, Table ordering, Print |
| `co_id` | `VARCHAR(50)` | YES | `NULL` | Mapped Course Outcome tag (`CO1`, `CO2`, `CO3`, `CO4`, `CO5`, `CO6`) | All Views, Attainment, Analytics |
| `topic_content` | `TEXT` | YES | `NULL` | Detailed academic topic, lecture title, or lab experiment description | All Views, Attendance, Course Files |
| `allocated_hours` | `INT` | YES | `1` | Planned contact hours for this session (typically 1h for Theory, 3h for Lab) | All Views, Total Hours calculation |
| `proposed_date` | `DATE` | YES | `NULL` | Target date scheduled on academic calendar | All Views, Calendar sync, Timeline |
| `actual_date` | `DATE` | YES | `NULL` | Date class was actually conducted (stamped by Attendance or Manual) | All Views, Attendance, Course File Doc 8 |
| `actual_hours` | `INT` | YES | `NULL` | Actual hours engaged during session | Course File Doc 8, Progress reports |
| `pedagogy` | `VARCHAR(100)` | YES | `'Lecture'` | Teaching method (`Lecture`, `Tutorial`, `Practical`, `Exam`, `Test`, `Demonstration`, `Group Activity`, `PPT Presentation`) | All Views, Analytics, Print |
| `mode` | `VARCHAR(20)` | YES | `'L'` | Curriculum modality code (`L` = Lecture, `P` = Practical, `ST` = Series Theory, `SP` = Series Practical) | R26 Practicum, R26 Practical, R26 Drawing |
| `sub_batch` | `VARCHAR(50)` | YES | `NULL` | Lab sub-batch split (`Batch A`, `Batch B`, `Batch A & B`, `All Students`) | R26 Practicum, R26 Practical, Attendance |
| `taxonomy` | `VARCHAR(100)` | YES | `NULL` | Cognitive Bloom's Taxonomy Level (`Remember`, `Understand`, `Apply`, `Analyze`, `Evaluate`, `Create`) | R26 Theory, Assessment linkage |
| `remarks` | `VARCHAR(255)` | YES | `NULL` | Faculty delivery notes, revision flags, or delay justification | All Views, Print templates |
| `status` | `ENUM('Pending', 'Completed')` | NO | `'Pending'` | Delivery state (`Pending` or `Completed`) | All Views, Progress bars, Attendance |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Record creation timestamp | Eloquent / Audit |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Record modification timestamp | Eloquent / Audit |

### Database Table: `lesson_plan_templates`

| Column | Data Type | Nullable | Default | Business Meaning | Active Consumers |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | NO | Auto-increment | Primary Key | `ClassroomController` |
| `subject_code` | `VARCHAR(50)` | NO | None | Course Subject Code (e.g. `1001`, `2001`, `3013`) | `saveAsTemplate`, `loadTemplate` |
| `day_no` | `INT` | NO | `1` | Period sequence number | Template cloning engine |
| `co_id` | `VARCHAR(50)` | YES | `NULL` | Course Outcome tag | Template cloning engine |
| `topic_content` | `TEXT` | YES | `NULL` | Standardized curriculum topic description | Template cloning engine |
| `pedagogy` | `VARCHAR(100)` | YES | `'Lecture'` | Standardized pedagogy | Template cloning engine |
| `remarks` | `TEXT` | YES | `NULL` | Standardized delivery notes | Template cloning engine |
| `created_at` | `TIMESTAMP` | YES | `NULL` | Timestamp | Eloquent |
| `updated_at` | `TIMESTAMP` | YES | `NULL` | Timestamp | Eloquent |

---

## 6. Route & API Contract Audit

```
┌────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                   COMPLETE LESSON PLANNER API REGISTRY                                  │
├────────────────────────────────┬─────────┬─────────────────────────────────────────────────────────────┤
│ Endpoint URI                   │ Method  │ Controller Action & Handler                                 │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/r26/classroom/{id}/       │ POST    │ R26ClassroomController@bulkUpdateLessonPlans                │
│   lesson-plans/bulk-update     │         │ Payload: { rows: [ {id, topic_content, pedagogy, ...} ] }   │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/r26/classroom/practicum/  │ POST    │ R26VirtualClassroomPracticumController@saveAllLessonPlans   │
│   {id}/lesson-plan/save-all    │         │ Payload: { plans: [ {id, pedagogy, proposed_date, ...} ] }  │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/r26/classroom/practicum/  │ POST    │ R26VirtualClassroomPracticumController@saveLessonPlanRow    │
│   {id}/lesson-plan/save        │         │ Payload: { plan_id, pedagogy, topic_content, ... }          │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/r26/classroom/practical/  │ POST    │ R26VirtualClassroomPracticalController@generateLessonPlan   │
│   {id}/lesson-plan/generate    │         │ Payload: { mode: "single" | "split" }                       │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/r26/classroom/practical/  │ POST    │ R26VirtualClassroomPracticalController@bulkUpdateLessonPlans│
│   {id}/lesson-plans/bulk-update│         │ Payload: { plans: [ {id, proposed_date, ...} ] }            │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/r26/classroom/drawing/    │ POST    │ R26VirtualClassroomDrawingController@generateLessonPlanApi  │
│   {id}/lesson-plan/generate    │         │ Payload: { mode: "single" }                                 │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/r26/classroom/drawing/    │ POST    │ R26VirtualClassroomDrawingController@bulkUpdateLessonPlans  │
│   {id}/lesson-plan/save        │         │ Payload: { plans: [ {id, proposed_date, ...} ] }            │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/r26/classroom/            │ POST    │ R26VirtualClassroomHealthPhysicalController@bulkUpdate...   │
│   health-physical/{id}/.../save│         │ Payload: { plans: [ {id, topic_content, ...} ] }            │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/classroom/{id}/           │ POST    │ ClassroomController@regenerateLessonPlans                   │
│   lesson-plans/regenerate      │         │ Payload: {} (Scales to CO duration sum + 2)                 │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/classroom/{id}/           │ POST    │ ClassroomController@bulkUpdateLessonPlans                   │
│   lesson-plans/bulk-update     │         │ Payload: { rows: [ {id, topic_content, pedagogy, ...} ] }   │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/classroom/{id}/           │ POST    │ ClassroomController@saveAsTemplate                          │
│   lesson-plans/save-as-template│         │ Payload: {} (Saves all current subject rows into templates) │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/classroom/{id}/           │ GET     │ ClassroomController@loadTemplate                            │
│   lesson-plans/load-template   │         │ Returns: { status: "SUCCESS", data: [ ...templateRows ] }   │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/classroom/{id}/           │ DELETE  │ ClassroomController@deleteLessonPlanRow                     │
│   lesson-plans/{planId}        │         │ Returns: { status: "SUCCESS", message: "Row deleted" }      │
├────────────────────────────────┼─────────┼─────────────────────────────────────────────────────────────┤
│ /api/classroom/{id}/practical/ │ POST    │ ClassroomController@generateLessonPlansFromExperiments      │
│   lesson-plans/generate        │         │ Payload: { session_type: "combined"|"separate", hours: 3 }  │
└────────────────────────────────┴─────────┴─────────────────────────────────────────────────────────────┘
```

---

## 7. JavaScript Contract Audit

| JS Function Name | Source View File | Trigger Element | DOM Dependencies | API Request / Mutation |
| :--- | :--- | :--- | :--- | :--- |
| `saveLessonPlanEdits()` | `virtual_classroom_theory.blade.php` | `#btnSavePlanner` | `#plannerTableBody tr`, `[data-field="..."]` | POST `/api/r26/classroom/{id}/lesson-plans/bulk-update` |
| `saveAllLessonPlans()` | `virtual_classroom_practicum.blade.php` | Save All Buttons | `tr[id^="lp-row-"]`, `data-block-ids`, `#lp-topic-*` | POST `/api/r26/classroom/practicum/{id}/lesson-plan/save-all` |
| `generateLessonTimeline()` | `virtual_classroom_practical.blade.php` | Generate Planner Button | `#lesson_planner_mode` | POST `/api/r26/classroom/practical/{id}/lesson-plan/generate` |
| `saveLessonPlannerBulk()` | `virtual_classroom_practical.blade.php` | Save Planner Entries Button | `.lesson-plan-row`, `.lp-proposed`, `.lp-actual` | POST `/api/r26/classroom/practical/{id}/lesson-plans/bulk-update` |
| `generateLessonTimeline()` | `virtual_classroom_drawing.blade.php` | Generate Planner Button | `#lesson_planner_mode` | POST `/api/r26/classroom/drawing/{id}/lesson-plan/generate` |
| `saveLessonPlannerBulk()` | `virtual_classroom_drawing.blade.php` | Save Planner Button | `.lesson-plan-row`, `.lp-topic`, `.lp-co`, `.lp-hours` | POST `/api/r26/classroom/drawing/{id}/lesson-plan/save` |
| `saveLessonPlan()` | `virtual_classroom_health_physical.blade.php` | Save Plan Updates Button | `#topic_*`, `#pdate_*`, `#adate_*` | POST `/api/r26/classroom/health-physical/{id}/lesson-plan/save` |
| `renderCoursePlanner()` | `lecturer_dashboard.blade.php` | `loadCourseDetails()`, Tab Switch | `#coursePlannerContent` | Pure JS dynamic DOM template renderer |
| `markPlanDirty(id)` | `lecturer_dashboard.blade.php` | Row input `onchange` | `#planSaveStatusBar`, `_dirtyPlanRows` | Displays floating save notification bar |
| `autoSavePlanRow(id, tr)` | `lecturer_dashboard.blade.php` | Date input `onchange` | Row inputs | Real-time immediate background row persistence |
| `saveLessonPlanChanges()` | `lecturer_dashboard.blade.php` | `#btnSavePlan` | `#lessonPlanTable tbody tr` | POST `/api/classroom/{id}/lesson-plans/bulk-update` |
| `regenerateLessonPlan()` | `lecturer_dashboard.blade.php` | `#btnRegenPlan` | Syllabus Modules & COs | POST `/api/classroom/{id}/lesson-plans/regenerate` |
| `saveLessonPlanAsTemplate()` | `lecturer_dashboard.blade.php` | `#btnSavePlanTemplate` | Current Lesson Plans | POST `/api/classroom/{id}/lesson-plans/save-as-template` |
| `loadLessonPlanTemplate()` | `lecturer_dashboard.blade.php` | Load Template Button | Database templates | GET `/api/classroom/{id}/lesson-plans/load-template` |
| `generatePlannerFromExperiments()` | `lecturer_dashboard.blade.php` | Modal submit | `#gen_session_type`, `#gen_allocated_hours` | POST `/api/classroom/{id}/practical/lesson-plans/generate` |

---

## 8. DOM Contract Preservation Registry

| DOM Identifier / Selector | Element Type | Critical Purpose | Associated JS Function | Preservation Classification |
| :--- | :--- | :--- | :--- | :--- |
| `#tab-planner` | `<div>` Container | Outer Tab Container in R26 Theory | `switchTab('planner')` | **STRICT PRESERVE** |
| `#plannerTableBody` | `<tbody>` | Row container in R26 Theory | `saveLessonPlanEdits()` | **STRICT PRESERVE** |
| `[data-lp-id]` | Attribute | Row ID binding in R26 Theory | `saveLessonPlanEdits()` | **STRICT PRESERVE** |
| `[data-field="topic_content"]` | Attribute | Topic value selector in R26 Theory | `saveLessonPlanEdits()` | **STRICT PRESERVE** |
| `[data-field="pedagogy"]` | Attribute | Pedagogy select in R26 Theory | `saveLessonPlanEdits()` | **STRICT PRESERVE** |
| `[data-field="taxonomy"]` | Attribute | Taxonomy input in R26 Theory | `saveLessonPlanEdits()` | **STRICT PRESERVE** |
| `[data-field="proposed_date"]` | Attribute | Proposed date in R26 Theory | `saveLessonPlanEdits()` | **STRICT PRESERVE** |
| `[data-field="actual_date"]` | Attribute | Actual date in R26 Theory | `saveLessonPlanEdits()` | **STRICT PRESERVE** |
| `[data-field="allocated_hours"]` | Attribute | Hours input in R26 Theory | `saveLessonPlanEdits()` | **STRICT PRESERVE** |
| `[data-field="status"]` | Attribute | Status select in R26 Theory | `saveLessonPlanEdits()` | **STRICT PRESERVE** |
| `#theory-subcontent-planner` | `<div>` Container | 45h Theory Subtab in Practicum | `switchTheorySubtab('planner')` | **STRICT PRESERVE** |
| `#lab-subcontent-planner` | `<div>` Container | 45h Lab Subtab in Practicum | `switchLabSubtab('planner')` | **STRICT PRESERVE** |
| `tr[id^="lp-row-"]` | Row Selector | Row container in Practicum | `saveAllLessonPlans()` | **STRICT PRESERVE** |
| `data-plan-id` | Attribute | Plan ID in Practicum | `saveAllLessonPlans()` | **STRICT PRESERVE** |
| `data-block-ids` | Attribute | 3-Hour block grouping in Practicum | `saveAllLessonPlans()` | **STRICT PRESERVE** |
| `#tab-lesson_plan` | `<div>` Container | Practical Planner Tab | `switchTab('lesson_plan')` | **STRICT PRESERVE** |
| `#lesson_planner_mode` | `<select>` | Mode selector (Single/Split) | `generateLessonTimeline()` | **STRICT PRESERVE** |
| `.lesson-plan-row` | Row Class | Row container in Practical/Drawing | `saveLessonPlannerBulk()` | **STRICT PRESERVE** |
| `.lp-proposed` | Input Class | Proposed date in Practical/Drawing | `saveLessonPlannerBulk()` | **STRICT PRESERVE** |
| `.lp-actual` | Input Class | Actual date in Practical/Drawing | `saveLessonPlannerBulk()` | **STRICT PRESERVE** |
| `.lp-topic` | Input/Textarea | Topic description in Practical/Drawing | `saveLessonPlannerBulk()` | **STRICT PRESERVE** |
| `.lp-co` | Select Class | CO select in Practical/Drawing | `saveLessonPlannerBulk()` | **STRICT PRESERVE** |
| `.lp-hours` | Input Class | Hours input in Practical/Drawing | `saveLessonPlannerBulk()` | **STRICT PRESERVE** |
| `.lp-pedagogy` | Select Class | Pedagogy in Practical/Drawing | `saveLessonPlannerBulk()` | **STRICT PRESERVE** |
| `.lp-status` | Select Class | Status select in Practical/Drawing | `saveLessonPlannerBulk()` | **STRICT PRESERVE** |
| `#coursePlannerContent` | `<div>` Container | Planner workspace in Dashboard | `renderCoursePlanner()` | **STRICT PRESERVE** |
| `#lessonPlanTable` | `<table>` | Planner table in Dashboard | `saveLessonPlanChanges()` | **STRICT PRESERVE** |
| `#btnSavePlan` | `<button>` | Save button in Dashboard | `saveLessonPlanChanges()` | **STRICT PRESERVE** |
| `#btnRegenPlan` | `<button>` | Regenerate button in Dashboard | `regenerateLessonPlan()` | **STRICT PRESERVE** |
| `#btnSavePlanTemplate` | `<button>` | Save template button in Dashboard | `saveLessonPlanAsTemplate()` | **STRICT PRESERVE** |
| `#planSaveStatusBar` | `<div>` | Floating save bar in Dashboard | `markPlanDirty()` | **STRICT PRESERVE** |

---

## 9. Lesson Planning Workflow Forensics

```
 ┌───────────────────────────┐
 │ 1. Enter Virtual Classroom│
 └─────────────┬─────────────┘
               │
               ▼
 ┌───────────────────────────┐
 │ 2. Select Lesson Plan Tab │
 └─────────────┬─────────────┘
               │
      ┌────────┴───────────────────────────┐
      │                                    │
      ▼ (If Empty)                         ▼ (If Populated)
 ┌───────────────────────────┐    ┌───────────────────────────────────┐
 │ A. Generate from Syllabus │    │ B. Inspect Timeline & Progress    │
 │    or Load Saved Template │    │    - Total & Remaining Hours      │
 └─────────────┬─────────────┘    │    - Completed vs Pending Days    │
               │                  └────────────────┬──────────────────┘
               │                                   │
               ▼                                   ▼
 ┌────────────────────────────────────────────────────────────────────┐
 │ 3. Interactive In-Place Row Editing                                │
 │    - Update Topic Content, Pedagogy, Bloom Taxonomy                │
 │    - Schedule Proposed Dates / Input Actual Dates                  │
 │    - Allocate Contact Hours / Assign Lab Sub-Batches (A/B)         │
 └─────────────────────────────────┬──────────────────────────────────┘
                                   │
                                   ▼
 ┌────────────────────────────────────────────────────────────────────┐
 │ 4. Multi-Level Persistence                                         │
 │    - Single-row auto-save (on date adjustment)                     │
 │    - Bulk workspace save (Save All Changes)                        │
 │    - Cross-batch template save (Save as Reusable Template)         │
 └─────────────────────────────────┬──────────────────────────────────┘
                                   │
                                   ▼
 ┌────────────────────────────────────────────────────────────────────┐
 │ 5. Downstream System Synchronization                               │
 │    - Attendance Logging marks plan items Completed                 │
 │    - Course File Document 8 updates hours engaged                  │
 │    - Official A4 Print View renders updated institutional schedule │
 └────────────────────────────────────────────────────────────────────┘
```

---

## 10. Calculation & Business Logic Audit

### 10.1 Theory Hours Auto-Scaling Algorithm (`ClassroomController@expandLessonPlansToHourly`)
- **Formula:** $TargetHours = \sum (\text{CO Hours}) + 2$ (Defaults to 60 or 75 if unstated).
- **Expansion Ratio:** $Ratio = \frac{TargetHours - 4}{InitialTopicCount}$.
- **Expansion Logic:**
  - If a module has 10 topics and 15 allocated hours, topics with detailed syllabus content (commas/subtopics) expand proportionally into 1-hour sessions.
  - Exactly **4 Series Tests** are appended at the end sequentially with `pedagogy = 'Test'`, `allocated_hours = 1`, `day_no = N-3, N-2, N-1, N`.

### 10.2 Practicum 90-Hour Split Engine (`R26VirtualClassroomPracticumController@generate90HourLessonPlan`)
- **45 Theory Hours:**
  - 41 Lecture periods (`mode = 'L'`, `allocated_hours = 1`).
  - 4 Theory Series Exams (`mode = 'ST'`, `allocated_hours = 1`) for CO1, CO2, CO3, and CO4.
- **45 Lab Practical Hours:**
  - 13 Practical Lab Sessions of 3 hours each = 39 Hours (`mode = 'P'`, `allocated_hours = 3`).
  - 2 Practical Series Exams of 3 hours each = 6 Hours (`mode = 'SP'`, `allocated_hours = 3`).
  - Total = $45\text{h (Theory)} + 45\text{h (Lab)} = 90\text{ Hours}$.

### 10.3 Drawing 45-Hour Split Engine (`R26VirtualClassroomDrawingController@generate45HourLabLessonPlan`)
- 13 Manual Drawing & CAD Drafting sessions (3 to 6 hours each) = 38 Hours.
- 2 Series Practical Tests (CA1 & CA2) = 4 Hours.
- 1 Open-Ended Project (OEE) = 3 Hours.
- Total = $38 + 4 + 3 = 45\text{ Hours}$.

### 10.4 Coverage & Completion Logic
- **Completed Hours:** $\sum (\text{allocated\_hours}) \text{ where } status = '\text{Completed}'$.
- **Pending Hours:** $\sum (\text{allocated\_hours}) \text{ where } status = '\text{Pending}'$.
- **Syllabus Coverage %:** $\frac{\text{Completed Hours}}{\text{Total Allocated Hours}} \times 100\%$.

---

## 11. Course File & Institutional Attainment Integration

```
                         ┌──────────────────────────────────┐
                         │   Syllabus PDF / Course Setup    │
                         └────────────────┬─────────────────┘
                                          │
                                          ▼
                         ┌──────────────────────────────────┐
                         │      `lesson_plans` Records      │
                         └───────┬──────────────────┬───────┘
                                 │                  │
                ┌────────────────┴─┐              ┌─┴────────────────┐
                │                  │              │                  │
                ▼                  ▼              ▼                  ▼
       ┌─────────────────┐ ┌───────────────┐ ┌───────────────┐ ┌─────────────────┐
       │ Attendance Log  │ │ Course File   │ │ Institutional │ │ Official A4     │
       │ Class Session   │ │ Document 8    │ │ NBA / SBTE    │ │ Print Schedule  │
       │ Progress Linkage│ │ (Course Plan) │ │ Attainment    │ │ Verification    │
       └─────────────────┘ └───────────────┘ └───────────────┘ └─────────────────┘
```

1. **Document 8 in Course File:** `CourseFileController@previewDocument8` directly loads all `lesson_plans` where `batch_subject_id = $id`, calculating cumulative syllabus hours and engaged hours.
2. **Attendance Session Selection:** `attendance_log.blade.php` displays a selector populated with incomplete `lesson_plans` topics for that course. Upon submission, it marks the lesson plan item `Completed` and records the class date.
3. **NBA / SBTE Compliance:** The lesson plan table provides documented proof of syllabus coverage per Course Outcome (CO1–CO6) and cognitive taxonomy level.

---

## 12. Template System Audit

### Architecture & Storage:
- **Table:** `lesson_plan_templates`
- **Fields:** `subject_code`, `day_no`, `co_id`, `topic_content`, `pedagogy`, `remarks`.
- **Active Records in Database:** **62 template rows** for subject code `3013`.
- **Save Flow (`saveAsTemplate`):** Lecturers click "Save as Template", which queries all current `lesson_plans` for the subject, clears any existing template rows for that `subject_code`, and creates matching `lesson_plan_templates` entries.
- **Load Flow (`loadTemplate`):** Lecturers in another batch/department teaching the same `subject_code` click "Load Template". The system queries `lesson_plan_templates`, deletes current batch `lesson_plans`, and clones all template rows into `lesson_plans`.

---

## 13. Print / Export Architecture

| Print Route Endpoint | Controller Action | View Template | Format & Orientation |
| :--- | :--- | :--- | :--- |
| `GET /r26/classroom/lesson-plan/print/{id}` | `R26ClassroomController@printLessonPlan` | `r26/lesson_plan_print.blade.php` | Browser Print / A4 Portrait |
| `GET /r26/classroom/practicum/{id}/print-lesson-plan` | `R26VirtualClassroomPracticumController@printLessonPlanPdf` | `r26_practicum/lesson_plan_print.blade.php` | Browser Print / A4 Portrait (Theory + Lab) |
| `GET /r26/classroom/practical/lesson-plan/print/{id}` | `R26VirtualClassroomPracticalController@printLessonPlan` | `r26_practical/lesson_plan_print.blade.php` | Browser Print / A4 Portrait |
| `GET /r26/classroom/drawing/lesson-plan/print/{id}` | `R26VirtualClassroomDrawingController@printLessonPlan` | `r26_drawing/lesson_plan_print.blade.php` | Browser Print / A4 Portrait |
| `GET /r26/classroom/health-physical/{id}/print/lesson-plan` | `R26VirtualClassroomHealthPhysicalController@printLessonPlan` | `r26_health_physical/reports_print.blade.php` | Browser Print / A4 Portrait |
| `GET /classroom/{id}/lesson-plan/print` | `ClassroomController@printLessonPlan` | `classroom_lesson_plan_print.blade.php` | Browser Print / A4 Portrait |
| `GET /api/course-files/{id}/preview/8` | `CourseFileController@previewDocument8` | `course_files/preview_doc_8.blade.php` | Course File A4 Embed |

---

## 14. Mobile Preservation Boundary

### Critical Mobile Isolation:
- `resources/views/staff_mobile_dashboard.blade.php`
- Mobile routes: `/mobile/*` and `/staff/mobile/*`
- **Audit Findings:** The mobile interface does not contain full inline lesson plan editing; it focuses on period punch attendance and quick schedule checks.
- **Rule:** **MOBILE PRESERVATION BOUNDARY:** All future desktop lesson planner workspace modernizations must NOT alter or break mobile view structures.

---

## 15. Legacy UI Assessment

1. **Inconsistent Table Layouts:**
   - R26 Theory uses a dark table with `bg-slate-950/50` inputs.
   - R26 Practicum uses an embedded dark table with tall 650px max-height scroll container.
   - R26 Practical uses a separate dark table with hardcoded CO dropdowns.
   - R26 Drawing uses Bootstrap styling (`btn-success`, `form-control-custom`) mixed with dark cards.
   - R2021 Dashboard uses dynamic template strings with Material Symbols icons.
2. **Typography & Readability Issues:**
   - Small font sizes (`text-[10px]`, `text-[11px]`) are used for badges, hours, and date inputs.
   - Low contrast placeholder text in inline topic textareas.
3. **Lack of High-Level Metric Summaries:**
   - Most views lack a clean metric card header showing Planned Hours, Completed Hours, Remaining Hours, and Syllabus Coverage %.

---

## 16. Modernization Opportunities (CampusLynk Standards)

```
┌────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                        PROPOSED CAMPUSLYNK LESSON PLANNER WORKSPACE DESIGN                             │
├────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ ┌──────────────────────┐ ┌──────────────────────┐ ┌──────────────────────┐ ┌─────────────────────────┐ │
│ │ 🎯 60 Planned Hours  │ │ ✅ 42 Completed Hours│ │ ⏳ 18 Pending Hours  │ │ 📊 70% Course Coverage  │ │
│ └──────────────────────┘ └──────────────────────┘ └──────────────────────┘ └─────────────────────────┘ │
├────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ [🔍 Filter by Module / Unit] [🏷 Filter by CO] [📅 Filter by Status: All / Pending / Completed]         │
├────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│  Day/Period │ CO Tag │ Topic & Content (14px Clean Solid) │ Pedagogy │ Proposed Date │ Actual │ Status │
│  ──────────┼────────┼────────────────────────────────────┼──────────┼───────────────┼────────┼────────┤
│   Period 1  │ [CO1]  │ Introduction to Microcontrollers  │ Lecture  │  2026-06-15   │ [✓]    │ Done   │
│   Period 2  │ [CO1]  │ Architecture and Pinout Diagram    │ Lecture  │  2026-06-17   │ [✓]    │ Done   │
│   Period 3  │ [CO1]  │ Internal Registers & ALU Operations│ Lecture  │  2026-06-19   │ [ ]    │ Pending│
└────────────────────────────────────────────────────────────────────────────────────────────────────────┘
```

1. **Unified CampusLynk Metric Bar:**
   - **Total Planned Hours** (with target syllabus hours indicator)
   - **Completed Hours & Engaged Sessions**
   - **Remaining Hours**
   - **Syllabus Coverage Progress Ring / Bar**
2. **High-Contrast Modern Table Canvas:**
   - Clean white card `#FFFFFF`, `#FAFAFB` background, subtle `border-slate-200/80` borders.
   - Solid high-contrast badges for COs (`CO1` emerald, `CO2` blue, `CO3` purple, `CO4` amber).
   - Minimum 14px typography for topics and form controls.
3. **Module & CO Quick Filter Pills:**
   - One-click filtering by Module (`Module 1`, `Module 2`, etc.) or CO tag (`CO1`–`CO6`).
4. **Intuitive Action Toolbar:**
   - Clean Lucide-style icons for "Save Changes", "Save Template", "Load Template", "Regenerate", and "Print Plan".

---

## 17. Responsive Assessment

| Screen Width | Viewport Device | Current Legacy Behavior | Modernization Strategy |
| :--- | :--- | :--- | :--- |
| **$\ge$ 1440px** | Large Desktop | Wide table with empty horizontal margins | Expand table cleanly with full topic visibility |
| **1024px – 1439px**| Standard Laptop | Horizontal scroll triggered on wide columns | Optimized column widths with compact date inputs |
| **768px – 1023px** | Tablet / iPad | Horizontal scrollbar; form controls cramped | Responsive scroll container with sticky Day column |
| **< 768px** | Mobile Screen | Dense horizontal table overflow | Horizontal scroll wrapper with minimum 14px text |

---

## 18. Complete Preservation Matrix

| Artifact / Component | Type | Responsibility | Classification |
| :--- | :--- | :--- | :--- |
| `lesson_plans` table | Database | Primary persistent store | **STRICT PRESERVE (No schema changes)** |
| `lesson_plan_templates` table | Database | Reusable template store | **STRICT PRESERVE (No schema changes)** |
| `R26ClassroomController@bulkUpdateLessonPlans` | Controller API | Updates R26 theory plans | **STRICT PRESERVE (API contract)** |
| `R26VirtualClassroomPracticumController@saveAllLessonPlans` | Controller API | Saves 90h practicum plans | **STRICT PRESERVE (API contract)** |
| `R26VirtualClassroomPracticalController@bulkUpdateLessonPlans` | Controller API | Saves practical plans | **STRICT PRESERVE (API contract)** |
| `R26VirtualClassroomDrawingController@bulkUpdateLessonPlans` | Controller API | Saves drawing plans | **STRICT PRESERVE (API contract)** |
| `R26VirtualClassroomHealthPhysicalController@bulkUpdateLessonPlans` | Controller API | Saves health & phys plans | **STRICT PRESERVE (API contract)** |
| `ClassroomController@regenerateLessonPlans` | Controller API | Scales & generates theory plans | **STRICT PRESERVE (API contract)** |
| `ClassroomController@bulkUpdateLessonPlans` | Controller API | Saves R2021 theory plans | **STRICT PRESERVE (API contract)** |
| `ClassroomController@saveAsTemplate` | Controller API | Saves cross-batch template | **STRICT PRESERVE (API contract)** |
| `ClassroomController@loadTemplate` | Controller API | Loads cross-batch template | **STRICT PRESERVE (API contract)** |
| `AttendanceController@store` | Controller | Marks plan Completed on punch | **STRICT PRESERVE (Downstream consumer)** |
| `CourseFileController@previewDocument8` | Controller | Renders Doc 8 Course Plan | **STRICT PRESERVE (Downstream consumer)** |
| `r26/lesson_plan_print.blade.php` | Print View | A4 print output | **STRICT PRESERVE** |
| `r26_practicum/lesson_plan_print.blade.php` | Print View | A4 print output | **STRICT PRESERVE** |
| `r26_practical/lesson_plan_print.blade.php` | Print View | A4 print output | **STRICT PRESERVE** |
| `r26_drawing/lesson_plan_print.blade.php` | Print View | A4 print output | **STRICT PRESERVE** |
| `r26_health_physical/reports_print.blade.php` | Print View | A4 print output | **STRICT PRESERVE** |
| `classroom_lesson_plan_print.blade.php` | Print View | A4 print output | **STRICT PRESERVE** |
| `staff_mobile_dashboard.blade.php` | Mobile View | Mobile interface | **MOBILE PRESERVATION BOUNDARY** |
| All DOM IDs (`#tab-planner`, `#plannerTableBody`, etc.) | Frontend | DOM selectors | **STRICT PRESERVE (IDs & names)** |
| Table Card & Form Markup | Frontend | UI Presentation | **SAFE TO MODERNIZE** |

---

## 19. Recommended Migration Phases

To ensure zero downtime, perfect regression prevention, and modular validation, we recommend executing Phase 2E.2C in the following structured subphases:

1. **Phase 2E.2C.1 — R2026 Theory Lesson Planner Modernization:**
   - Modernize `#tab-planner` in `r26/virtual_classroom_theory.blade.php`.
   - Implement metric summary cards (Planned, Completed, Remaining, Coverage %).
   - Upgrade table markup to CampusLynk white card styling with minimum 14px typography and solid CO badges.
   - Preserve `#plannerTableBody`, `[data-lp-id]`, `[data-field="..."]`, `saveLessonPlanEdits()`, and `saveAsTemplate()`.
2. **Phase 2E.2C.2 — R2026 Practicum & Practical Lesson Planners Modernization:**
   - Modernize `#theory-subcontent-planner` & `#lab-subcontent-planner` in `r26_practicum/virtual_classroom_practicum.blade.php`.
   - Modernize `#tab-lesson_plan` in `r26_practical/virtual_classroom_practical.blade.php`.
   - Preserve `saveAllLessonPlans()`, `generateLessonTimeline()`, and `saveLessonPlannerBulk()`.
3. **Phase 2E.2C.3 — R2026 Drawing & Health & Physical Planners Modernization:**
   - Modernize `#tab-lessonplan` in `r26_drawing/virtual_classroom_drawing.blade.php`.
   - Modernize `#tab-lesson` in `r26_health_physical/virtual_classroom_health_physical.blade.php`.
   - Preserve `generateLessonTimeline()`, `saveLessonPlannerBulk()`, and `saveLessonPlan()`.
4. **Phase 2E.2C.4 — R2021 Theory & Practical (Lecturer Dashboard) Modernization:**
   - Modernize `renderCoursePlanner()` in `resources/views/lecturer_dashboard.blade.php`.
   - Upgrade empty state, action toolbar, metric summary, and table row generation.
   - Preserve `markPlanDirty()`, `autoSavePlanRow()`, `saveLessonPlanChanges()`, `regenerateLessonPlan()`, `saveLessonPlanAsTemplate()`, and `loadLessonPlanTemplate()`.
5. **Phase 2E.2C.5 — Cross-View Verification & Final Deliverables:**
   - Run canonical route verification across all 6 classroom views.
   - Validate attendance completion stamping and Course File Document 8 rendering.
   - Compile assets with Vite and generate migration report.

---

## 20. Regression Test Plan

```text
=== CANONICAL REGRESSION SUITE FOR LESSON PLANNER ===

1. Blade Compilation & Route Rendering:
   - GET /r26/classroom/theory/3             -> HTTP 200 (renders #tab-planner)
   - GET /r26/classroom/practicum/3          -> HTTP 200 (renders #theory-subcontent-planner & #lab-subcontent-planner)
   - GET /r26/classroom/practical/3          -> HTTP 302/200 (renders #tab-lesson_plan)
   - GET /r26/classroom/drawing/3            -> HTTP 200 (renders #tab-lessonplan)
   - GET /r26/classroom/health-physical/3   -> HTTP 200 (renders #tab-lesson)
   - GET /dashboard/lecturer                 -> HTTP 200 (renders #coursePlannerContent)

2. API Mutation Endpoints:
   - POST /api/r26/classroom/{id}/lesson-plans/bulk-update       -> HTTP 200 { status: 'SUCCESS' }
   - POST /api/r26/classroom/practicum/{id}/lesson-plan/save-all -> HTTP 200 { status: 'SUCCESS' }
   - POST /api/r26/classroom/practical/{id}/lesson-plan/generate -> HTTP 200 { success: true }
   - POST /api/r26/classroom/drawing/{id}/lesson-plan/save       -> HTTP 200 { status: 'SUCCESS' }
   - POST /api/classroom/{id}/lesson-plans/regenerate            -> HTTP 200 { status: 'SUCCESS' }
   - POST /api/classroom/{id}/lesson-plans/save-as-template      -> HTTP 200 { status: 'SUCCESS' }
   - GET  /api/classroom/{id}/lesson-plans/load-template         -> HTTP 200 { status: 'SUCCESS' }

3. Downstream Integrity:
   - Attendance submission updates lesson plan status to 'Completed'.
   - Course File previewDocument8 accurately calculates cumulative syllabus and engaged hours.
   - Print routes render without CSS breakage.
```

---

## 21. Risk Register

| Risk ID | Risk Description | Severity | Mitigation Strategy |
| :--- | :--- | :--- | :--- |
| **R-LP-1** | Renaming field selector attributes (e.g. `data-field="topic_content"`) breaking bulk save JS | **CRITICAL** | Strictly retain all existing data attributes and element IDs across all views. |
| **R-LP-2** | Altering 3-hour session block grouping (`data-block-ids`) in Practicum | **HIGH** | Maintain exact `data-block-ids` attribute on practical rows in `r26_practicum`. |
| **R-LP-3** | Breaking Attendance auto-completion of lesson plans | **HIGH** | Do not modify `lesson_plans.id` bindings or status enum values (`Pending`/`Completed`). |
| **R-LP-4** | Micro-fonts reappearing in date inputs or pedagogy dropdowns | **MEDIUM** | Enforce minimum 14px (`text-sm`) for all inputs, textareas, and select elements. |
| **R-LP-5** | Accidental modification of Mobile dashboard | **CRITICAL** | Enforce Mobile Preservation Boundary on `staff_mobile_dashboard.blade.php`. |

---

## 22. Final Recommendation

The forensic audit confirms that the lesson planning subsystem is exceptionally well architected, highly consistent at the database layer (`lesson_plans`), and tightly integrated with attendance, course files, and print generation.

The UI across all 6 classroom views is fully prepared for modernization into the crisp CampusLynk design system.

**Next Action:** Await explicit user approval before beginning implementation of Phase 2E.2C.
