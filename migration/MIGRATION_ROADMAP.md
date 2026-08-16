# CampusLynk UI Migration Roadmap
**Standard:** Granular Page-by-Page Migration Pipeline  
**Governance:** AI Rules & Component Freeze Policy  

---

## Migration Sequence & Progress

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                 CAMPUSLYNK REORGANIZED MIGRATION PIPELINE                   │
├───────────┬─────────────┬─────────────┬───────────┬───────────┬─────────────┤
│ Phase 1   │ Phase 2A    │ Phase 2B    │ Phase 2C  │ Phase 2D  │ Phase 2E    │
│ Auth      │ Student     │ Lecturer    │ HOD       │ Tutor     │ Admin       │
│ Gateway   │ Dashboard   │ Dashboard   │ Dashboard │ Dashboard │ Dashboard   │
├───────────┼─────────────┼─────────────┼───────────┼───────────┼─────────────┤
│ Phase 2F  │ Phase 3     │ Phase 4     │ Phase 5   │ Phase 6   │             │
│ Principal │ Mentoring & │ Interactive │ Tables &  │ Printable │             │
│ Dashboard │ 360° Dossier│ Workspaces  │ Registries│ A4 Reports│             │
└───────────┴─────────────┴─────────────┴───────────┴───────────┴─────────────┘
```

---

### Phase 1: Authentication & Startup Experience
* **Status:** ✅ **COMPLETED & VERIFIED**
* **Scope:**
  * `resources/views/login.blade.php` (Institutional Sign In)
  * `resources/views/modern/auth/splash.blade.php` (Splash Gateway)
  * `resources/views/modern/auth/forgot-password.blade.php` (Account Recovery)
  * `resources/views/modern/auth/reset-password.blade.php` (Password Reset)
  * `resources/views/modern/auth/access-denied.blade.php` (HTTP 403 Restricted)
  * `resources/views/modern/auth/session-expired.blade.php` (Security Timeout)
  * `resources/views/modern/auth/loading.blade.php` (Workspace Sync)
  * `resources/views/modern/auth/auth-error.blade.php` (Diagnostic Error)

---

### Phase 2: Role-Based Core Dashboards (Page-by-Page)
* **Phase 2A — Student Dashboard**: `resources/views/student_dashboard.blade.php` [Status: ✅ **COMPLETED & AUDITED**]
* **Phase 2B — Lecturer Dashboard**: `resources/views/lecturer_dashboard.blade.php` [Status: 🟡 READY TO INITIATE]
* **Phase 2C — HOD Dashboard**: `resources/views/hod_dashboard.blade.php` [Status: ⚪ QUEUED]
* **Phase 2D — Tutor Dashboard**: `resources/views/tutor_dashboard.blade.php` [Status: ⚪ QUEUED]
* **Phase 2E — Admin Dashboard**: `resources/views/admin_dashboard.blade.php` [Status: ⚪ QUEUED]
* **Phase 2F — Principal Dashboard**: `resources/views/principal_dashboard.blade.php` [Status: ⚪ QUEUED]

---

### Phase 3: Student Mentoring & 360° Dossiers
* **Target Views:**
  * `student_mentoring_diary_full.blade.php`
  * `tutor_student_diary_full.blade.php`
  * `student_mentoring_panel.blade.php`

---

### Phase 4: Interactive Workspaces & Virtual Classrooms
* **Target Views:**
  * `r26/virtual_classroom_theory.blade.php` (Theory Lesson Planner & CIE Series Test Mark Entry)
  * `r26_practicum/virtual_classroom_practicum.blade.php` (Practicum & Lab Log Evaluation)
  * `r26_practical/virtual_classroom_practical.blade.php` (Practical Lab Examination Workspace)
  * `r26_drawing/virtual_classroom_drawing.blade.php` (Engineering Drawing Studio)

---

### Phase 5: Data Tables & Registry Hubs
* **Target Views:**
  * `admin_show_users_table.blade.php` (User Management Roster)
  * `attendance_log.blade.php` & `sf_staff_attendance_report.blade.php` (Biometric Punch Logs)
  * `staff_leave_reports.blade.php` (Leave Balance & Approval Management)

---

### Phase 6: Printable Reports & Accreditation Documents
* **Target Views:**
  * `r26/internals_cie_print.blade.php` (Continuous Internal Evaluation Official Marksheet)
  * `r26/attainment_report_print.blade.php` (NBA CO/PO Direct & Indirect Attainment Statements)
  * `r26/lesson_plan_print.blade.php` (Course Outline & Delivery Schedule)
  * `hod_consolidated_timetable_print.blade.php` (Master Department Timetable)
