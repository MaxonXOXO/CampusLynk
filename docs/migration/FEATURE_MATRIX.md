# Migration Feature Matrix & Inventory

> **Source of Truth:** September 21, 2026 Audit Report (`audit_report.md` / `implementation_plan.md`)  
> **Status:** Active Migration — Units M1.1, M2.1, M2.2, M2.3, M2.4, M2.5, M2.6 COMPLETED  

---

## 1. Feature Inventory & Delta Matrix

| Feature | Legacy Status | CampusLynk Status | Priority | Dependencies | Migration State | Feature Flag | Notes |
| :--- | :--- | :--- | :---: | :--- | :---: | :---: | :--- |
| **Database Schema & Core Models** | Legacy DB schema & models | Implemented & Verified (`AuditLog` parity + 6 unit tests) | 🔴 Critical | None | Done (`06f68cc6`) | None (Core DB) | Unit M1.1 completed. Commit `06f68cc61082081c4e598bfe6a61d6b1b6222f0d`. |
| **R21 Major Project** | Fully implemented (`R21VirtualClassroomMajorProjectController`, 2,611-line view, 1,025-line print report, 2 models) | Fully Implemented & Verified (`M2.1`, `M2.2`, `M2.3` completed) | 🔴 Critical | DB tables `r21_major_project_*`, `workspace-layout`, `report-layout` | Done | `FEATURE_R21_PROJECT` | Units M2.1, M2.2, and M2.3 completed (models, migration, AttainmentService, controller, routes, workspace layout, 11 workspace partials, 8 modular print partials, 6 modals, 37 passing tests). |
| **R21 Seminar** | Fully implemented (`R21VirtualClassroomSeminarController`, 2,171-line view, 680-line print report) | Fully Implemented & Verified (`M2.4`, `M2.5` completed) | 🔴 Critical | DB schema, `workspace-layout`, `report-layout` | Done | `FEATURE_R21_SEMINAR` | Units M2.4 and M2.5 completed (controller, 6 routes, 15 modular Blade partials, master shells `<x-layouts.workspace-layout>` and `<x-layouts.report-layout>`, 55 passing tests). |
| **R21 Drawing** | Fully implemented (`R21VirtualClassroomDrawingController`, 1,436-line view, 4 print templates, 4 models) | Backend Implemented & Verified (`M2.6`); Frontend Backlog (`M2.7`) | 🔴 Critical | DB tables `r21_drawing_*`, `workspace-layout`, `report-layout` | In-Implementation | `FEATURE_R21_DRAWING` | Unit M2.6 completed (migration, 4 models, controller, routes, 64 tests passing, commit `1bd25cef`). Unit M2.7 (workspace view & 4 print templates) next. |
| **Role Dashboards Consolidation** | 20+ duplicated monolithic dashboards (>2.5 MB redundant code) | Shared-Shell Architecture (`faculty-shell`, `dashboard-layout`, `app-shell`, `x-ui.*`) | 🟠 High | `NavigationService`, `x-layouts.*`, `x-ui.*` | Backlog | `FEATURE_ROLE_DASHBOARDS` | Units M9.1–M9.4. Consolidates Principal, HOD, Lecturer, Trade Instructor, Demonstrator, Tutor, Academic Coordinator, Chairman, Admin, Student, Parent dashboards eliminating legacy duplication. |
| **R26 Basic Science Practicum** | Fully implemented (`virtual_classroom_basic_science_practicum.blade.php`, 7,398 lines) | Missing | 🔴 Critical | `R26VirtualClassroomPracticumController`, `workspace-layout` | Backlog | `FEATURE_R26_PRACTICUM` | 40M CIA / 60M ESE breakdown cards, Table 2.2 and Table 3.1 fullscreen evaluator modals, inline autosave. Must decompose monolithic view. |
| **R26 Theory Synchronization** | Expanded to 5,325 lines (SBTE Grade entry, 60M scaling, space-saving tabs, attendance linking) | Diverged (3,337 lines, lacking new tab controls, SBTE scaling, row deletion) | 🔴 Critical | `R26ClassroomController` | Backlog | None (In-place sync) | Synchronize missing methods (`saveCoPoMatrix`), lesson plan row delete confirmation, and ESE autosave. |
| **SBTE Subject Log Import** | Implemented (`SbteSubjectLogImportController`, Python OCR parser `parse_sbte_subject_log.py`, modal) | Missing | 🟠 High | Python 3 runtime, `batch_subjects` | Backlog | `FEATURE_SBTE_IMPORT` | Parses official SBTE PDF logs and updates subject lesson logs and attendance records. |
| **Carmie AI Assistant** | Implemented (`CarmieAssistantController`, `CarmiePlaybookService`, `carmie_playbook.json` with 1,581 lines) | Missing | 🟡 Medium | JSON playbook assets, `app-shell` | Backlog | `FEATURE_CARMIE_AI` | Contextual AI chat assistant, manual citation search, revision category tabs, question learning model. |
| **HOD Program Attainment** | Implemented (`ProgramAttainmentController`, `AttainmentService`, dashboard & print views) | Missing | 🟠 High | DB table `program_attainments`, `app-shell` | Backlog | `FEATURE_PROGRAM_ATTAINMENT` | Calculates NBA Criterion 3 PO1–PO11 and PSO1–PSO3 direct and indirect attainment on 10-point scale. |
| **Tutor Progress Reports** | Implemented (3 print templates: consolidated attendance, progress card, consolidated progress report) | Missing | 🟠 High | `TutorController`, Tutor Master Shell | Backlog | `FEATURE_TUTOR_REPORTS` | Generates official student report cards and batch performance summaries for PTA meetings. |
| **Web Push Notifications** | Implemented (`PushNotificationController`, `PushNotificationService`, `push_subscriptions` table) | Missing | 🟢 Low | VAPID keys, service worker | Backlog | `FEATURE_PUSH_NOTIFICATIONS` | Browser web push notifications for reminders, deadlines, and circulars. |
| **Staff Birthdays** | Implemented (`StaffBirthdayController`, `staff_birthday_wishes` table, modal) | Missing | 🟢 Low | `staff_profiles` DOB column | Backlog | `FEATURE_STAFF_BIRTHDAYS` | Daily dashboard birthday alerts and greeting dispatch tracking. |
| **Staff Mobile Virtual Lab** | Implemented (`StaffMobileVirtualLabController`, 1,238-line mobile view) | Missing | 🟢 Low | `classroom_practical` endpoints | Backlog | `FEATURE_MOBILE_LAB` | Lightweight mobile view for practical lab evaluation on smartphones. |
| **Missing Shared Controller Methods** | Present in legacy controllers | Missing in shared CampusLynk controllers | 🟠 High | Existing CampusLynk controllers | Backlog | None (Direct additions) | Restores stripped methods: `AttendanceController` (session check, delete log, tutor report), `ClassroomController` (ESE bulk update, attainment, course file A4 print), etc. |
| **Print / Report Suite** | 10 dedicated A4 print templates in legacy | Missing or fragmented in CampusLynk | 🟠 High | Controller print endpoints | Backlog | None | Institutional print stylesheets, student registers, series exam marks lists, and course files. |
| **Lab Batch A/B Splitting** | Implemented in practical classrooms (batch cutoff, A/B assignment, separate evaluation) | Missing in CampusLynk practical views | 🟠 High | `batch_subjects.lab_batch_mode` | Backlog | `FEATURE_LAB_BATCHES` | Allows dividing lab classes into Batch A and Batch B for rotating experiments. |
| **Video / Study Materials Functionality** | Implemented direct MP4/WebM video clip upload and embedded player | Audio/doc upload only in CampusLynk | 🟢 Low | Storage disk configuration | Backlog | `FEATURE_VIDEO_MATERIALS` | Enables multimedia study material uploads with inline HTML5 video player. |

---

## 2. Status Glossary

- **Backlog:** Specified in inventory, ready for autonomous unit design; zero implementation work started.
- **In-Analysis:** Currently being inspected by Scanner / Architect agents.
- **In-Implementation:** Active implementation unit being coded by Implementer.
- **In-Verification:** Code submitted to Verifier for automated testing and DoD audit.
- **Done:** Satisfies all `MIGRATION_DEFINITION_OF_DONE.md` criteria and committed with atomic SHA.
