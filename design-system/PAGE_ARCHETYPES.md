# CampusLynk Page Archetypes & Route Classification
**Version:** 1.0.0  
**Classification Coverage:** 378 Institutional Routes & Workspaces

---

## 1. Executive Summary & Archetype Matrix
To guarantee consistent UI/UX architecture and prevent ad-hoc page layouts, all routes and views across the platform are categorized into **8 structural archetypes**. Every page within an archetype inherits identical layout scaffolding, header treatments, component states, and responsive behaviors.

```
┌────────────────────────────────────────────────────────────────────────┐
│                        CAMPUSLYNK PAGE ARCHETYPES                      │
├──────────────┬──────────────┬──────────────┬──────────────┬────────────┤
│  Dashboard   │  Data Table  │     Form     │    Report    │  Profile   │
├──────────────┼──────────────┼──────────────┼──────────────┼────────────┤
│   Settings   │     Auth     │  Workspace   │              │            │
└──────────────┴──────────────┴──────────────┴──────────────┴────────────┘
```

---

## 2. Archetype Definitions & Blueprint Specs

### 2.1 Archetype: Dashboard
* **Primary Purpose:** Role-based operational overview, real-time KPI metrics, graphical trend analysis, and prioritized action items.
* **Layout Structure:** Topbar Header → KPI Summary Cards (4-column grid) → 2-Column Analytics Widget Split (Charts / Activity Feed) → Actionable Quick Links.
* **Key Components:** `Card.Metric.v1`, `Chart.Bar.v1`, `Chart.Line.v1`, `Badge.Status.v1`, `List.Actionable.v1`.
* **Classified Routes:**
  * `GET /dashboard/student` (Student Learning & Exam Dashboard)
  * `GET /dashboard/hod` (Department Head Overview & Workload Dashboard)
  * `GET /dashboard/lecturer` (Faculty Classroom & Subject Hub)
  * `GET /dashboard/tutor` (Tutor Supervision & Mentoring Desk)
  * `GET /dashboard/admin` (Administrative Operations Console)
  * `GET /dashboard/superadmin` (Institution Governance Desk)
  * `GET /dashboard/principal` (Campus Academic & Financial Overview)
  * `GET /dashboard/chairman` (Executive Institutional Digest)
  * `GET /dashboard/general-coordinator-aided` (General Aided Dept Overview)
  * `GET /dashboard/general-coordinator-sf` (General Self-Finance Dept Overview)
  * `GET /dashboard/academic-coordinator` (Campus Academic Programs Desk)
  * `GET /dashboard/remedial` (Remedial Coaching Center Hub)
  * `GET /dashboard/workshop` (Workshop Superintendent Dashboard)
  * `GET /dashboard/demonstrator` (Lab Demonstrator Hub)
  * `GET /dashboard/tradeinstructor` (Trade Instructor Practical Desk)
  * `GET /parent/dashboard` (Parent Monitoring Portal)

---

### 2.2 Archetype: Data Table
* **Primary Purpose:** Large-scale data exploration, tabular filtering, column sorting, pagination, and batch operations.
* **Layout Structure:** Top Action Bar (Search + Multi-select Filter Chips + Export CTA) → DataTable Container → Pagination Toolbar.
* **Key Components:** `DataTable.v1`, `Input.Search.v1`, `Chip.Filter.v1`, `Button.Secondary.v1`, `Pagination.v1`, `EmptyState.v1`.
* **Classified Routes:**
  * `GET /staff/attendance-log` (Faculty & Staff Attendance Logs)
  * `GET /staff/leave/reports` (Leave Request Roster & Status Grid)
  * `GET /superadmin/show-users` (User Account Registry & Access Matrix)
  * `GET /api/hod/student-roster` (Department Student Registry)
  * `GET /api/hod/staff-roster` (Department Faculty & Staff List)
  * `GET /api/admin/pending-approvals` (Pending Account Verifications)
  * `GET /api/r26/students/batch-roster` (R2026 Batch Student Registries)
  * `GET /sf-attendance/attendance-report` (Self-Finance Daily Punch Logs)
  * `GET /remedial-sessions` (Remedial Student Attendance & Logs)
  * `GET /api/course-files/registry` (Department Course Files Inventory)

---

### 2.3 Archetype: Form
* **Primary Purpose:** Transactional data entry, profile creation, syllabus configuration, and survey responses.
* **Layout Structure:** Breadcrumb Header → Form Container (Single or 2-column grid with grouped sections) → Sticky Bottom Save/Cancel Actions.
* **Key Components:** `Input.Text.v1`, `Input.Select.v1`, `Input.DatePicker.v1`, `Input.FileUpload.v1`, `Button.Primary.v1`, `Alert.v1`.
* **Classified Routes:**
  * `POST /register/student` (Student Admission Registration)
  * `POST /register/staff` (Faculty & Staff Onboarding Form)
  * `POST /api/hod/save-branch-config` (Vision, Mission & PEO Setup)
  * `POST /api/syllabus/save-course-outline` (Syllabus & Module Entry)
  * `POST /api/test-config/create` (Series Exam & Model Exam Configurator)
  * `POST /sf-attendance/geofence-setup` (Campus Geofence Parameter Setup)
  * `POST /sf-attendance/register-face` (Staff Biometric Face Enrollment)
  * `POST /staff/professional-activities/save` (Faculty Activity Submission)
  * `POST /api/leave/submit-request` (Staff Leave Application Form)
  * `POST /student/survey/{surveyId}` (Mid-Semester Survey Submission)
  * `POST /student/course-exit/{surveyId}` (Course Exit Survey Submission)

---

### 2.4 Archetype: Report
* **Primary Purpose:** Formal institutional documentation, printable official transcripts, audit records, and marksheet PDFs.
* **Layout Structure:** Printable A4 Scaffolding → Institution Branding Header → Structured Metadata Grid → Standard Tabular Data → Signature Block.
* **Key Components:** `Report.Header.v1`, `Report.Table.v1`, `Report.Signature.v1`, `Button.Print.v1`.
* **Classified Routes:**
  * `GET /r26/classroom/{subjectId}/internals/print-cie` (Continuous Internal Evaluation Marksheet)
  * `GET /r26/classroom/{subjectId}/final-results/print` (Final Grade Summary Report)
  * `GET /r26/classroom/{subjectId}/nba/attainment-report` (NBA CO/PO Attainment Report)
  * `GET /r26/classroom/practicum/{subjectId}/print-course-file` (Consolidated Practicum Course File)
  * `GET /r26/classroom/drawing/{subjectId}/attendance-report` (Drawing Attendance Statement)
  * `GET /r26/classroom/health-physical/{subjectId}/print/{type}` (Physical Fitness Report)
  * `GET /hod/academic-calendar/print` (Official Academic Calendar)
  * `GET /hod/consolidated-timetable/print` (Department Timetable Master)
  * `GET /staff/leave/{id}/pdf` (Staff Leave Approval Letter)
  * `GET /tutor/mentoring-diary/{regNo}/print` (Student Mentoring Dossier)
  * `GET /admin/executive-digest/pdf` (Campus Executive Summary Digest)

---

### 2.5 Archetype: Profile
* **Primary Purpose:** Individualized 360-degree record view for students, staff members, and classrooms.
* **Layout Structure:** Profile Banner (Photo, Name, Badges, Contact Pills) → Sub-navigation Tabs (`Academic Performance`, `Attendance`, `Activities`, `Discipline`, `Notes`) → Tab Content Pane.
* **Key Components:** `Profile.Avatar.v1`, `Tabs.Underline.v1`, `Card.Dossier.v1`, `Timeline.Activity.v1`.
* **Classified Routes:**
  * `GET /student/mentoring-diary` (Student Self-Profile & Mentoring Portfolio)
  * `GET /tutor/mentoring-diary/{regNo}` (Tutor 360° Student Investigation Dossier)
  * `GET /api/staff/profile-view/{mobileNo}` (Faculty Comprehensive Dossier)
  * `GET /parent/dashboard` (Parent View of Ward's Performance & Fee Records)

---

### 2.6 Archetype: Settings
* **Primary Purpose:** Global system controls, program outcome mapping, access keys, and preferences.
* **Layout Structure:** Left Vertical Settings Nav → Right Content Configuration Panel → Validation Feedback.
* **Key Components:** `Nav.Vertical.v1`, `Input.Toggle.v1`, `Input.KeySecret.v1`, `Button.Destructive.v1`.
* **Classified Routes:**
  * `GET /settings/system-parameters` (Campus Linx Global Configurations)
  * `GET /settings/po-pso-mappings` (Accreditation Outcome Settings)
  * `GET /settings/campus-geofence` (Location & Boundary Geofencing)
  * `GET /api/settings/backup-registry` (Google Drive & Local Backup Configurations)

---

### 2.7 Archetype: Authentication
* **Primary Purpose:** Gateway access, role selection, authentication verification, and password recovery.
* **Layout Structure:** Centered Card on Deep Indigo-Slate Mesh → Brand Logo Header → Role Selector Tabs → Input Stack → Primary Action CTA.
* **Key Components:** `Auth.Card.v1`, `Auth.Tabs.v1`, `Input.Password.v1`, `Button.Primary.v1`.
* **Classified Routes:**
  * `GET /` & `GET /login` (Main Institutional Login Gateway)
  * `POST /login` (Credential Verification Endpoint)
  * `POST /api/auth/recover-account` (Password Recovery & OTP)
  * `GET /logout` (Session Termination & Redirect)
  * `GET /parent/login` (Parent Dedicated Gateway)

---

### 2.8 Archetype: Workspace
* **Primary Purpose:** Deep-focus, multi-tool interactive environments (Virtual Classrooms, Exam Creation, Real-time Mark Evaluation).
* **Layout Structure:** Top Utility Toolbar (Breadcrumbs + Context Switcher + Quick Actions) → Split Canvas (Sidebar Tool Navigator + Interactive Content Pane) → Modal Action Triggers.
* **Key Components:** `Workspace.Toolbar.v1`, `Workspace.Sidebar.v1`, `LessonPlan.Grid.v1`, `TestBuilder.v1`.
* **Classified Routes:**
  * `GET /r26/classroom/theory/{subjectId}` (Theory Virtual Classroom & Lesson Planner)
  * `GET /r26/classroom/practicum/{subjectId}` (Practicum Virtual Classroom & Lab Splits)
  * `GET /r26/classroom/practical/{subjectId}` (Practical Experiment & Series Evaluator)
  * `GET /r26/classroom/drawing/{subjectId}` (Engineering Drawing Slot & OEE Studio)
  * `GET /r26/classroom/health-physical/{subjectId}` (Health & Physical Education Lab)
  * `GET /student/mock-test` (Student Live Examination Workspace & Timer)
