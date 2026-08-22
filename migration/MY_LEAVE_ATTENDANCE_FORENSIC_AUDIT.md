# CAMPUSLYNK — MY LEAVE & ATTENDANCE DESKTOP RESTORATION
## PHASE 2D.0 — FORENSIC AUDIT & ARCHITECTURAL BASELINE

**Document Version:** 1.0.0  
**Audit Date:** August 22, 2026  
**Status:** COMPLETE (READ-ONLY FORENSIC AUDIT)  
**Target Subsystem:** Staff Self-Service Leave Application, Leave Balances, Biometric Attendance, and Multi-Tier Approvals

---

## 1. Executive Summary

CampusLynk currently includes a comprehensive **Staff Leave Application & Biometric Attendance System** supporting:
1. **Personal Leave Application** (Casual Leave, Compensatory Casual Leave, Duty Leave, Medical Leave, Loss of Pay, Special Leave) with session selection (Full Day, FN, AN), CCL duty date linking, and dynamic multi-period substitute arrangements.
2. **Personal Leave Balances & History** (e.g., Casual Leave 15 days total, taken days, remaining balance) with live multi-tier status tracking and official A4 PDF Leave Application generation.
3. **Multi-Stage Approval Hierarchy** with automated routing:
   - **Self-Financing (SF) Stream** (EL, CT, AU, Gen SF): HOD &rarr; Academic Coordinator &rarr; Principal (3-tier approval).
   - **Aided Stream** (EEE, ME, CE, Gen Aided): HOD &rarr; Principal (2-tier approval; skips Academic Coordinator).
4. **Biometric SF Staff Time Punch Tracking** (Morning IN, Evening OUT, On-Campus Geofence verification, campus residency duration calculation).
5. **Supervisor Master Leave Ledgers** for HOD, Academic Coordinator, and Principal/Executive desks.

However, the individual **staff self-service portal ("My Leave & Attendance")** is currently inaccessible on desktop workstations. It was developed within a dark-mode mobile container (`staff_mobile_dashboard.blade.php`), and `MentoringController@showStaffMobileDashboard` automatically redirects desktop User-Agents away to role dashboards unless `mode=mobile` is explicitly supplied. Furthermore, standard desktop navigation configurations (`config/navigation/faculty.php`) omit the self-service leave link entirely.

This audit establishes the forensic baseline required to build a native **CampusLynk Desktop "My Leave & Attendance" Workspace** utilizing existing backend APIs without altering or breaking the existing mobile subsystem.

---

## 2. Exact Existing Mobile Implementation

### Primary View
- **File:** `resources/views/staff_mobile_dashboard.blade.php` (1,363 lines)
- **Primary Route:** `GET /staff/mobile`
- **Controller Action:** `App\Http\Controllers\MentoringController@showStaffMobileDashboard`

### Layout & Architecture
- Uses Bootstrap 5.3 + FontAwesome 6.4 + Plus Jakarta Sans + custom dark theme (`#090d16`, `#0f172a`).
- Outer wrapper `.mobile-container` is constrained to `max-width: 520px` (or `max-width: 960px` when forced on desktop).
- Fixed bottom mobile tab bar (`.bottom-nav`) with 6 tabs:
  1. **Classes (`#tab-classes`):** Today's assigned classes timetable, institution-wide Day Order selection (Day 1–5), and links to Mark Attendance.
  2. **To-Do (`#tab-todo`):** Staff task alerts (pending student leaves, assignment submissions, series exam schedules).
  3. **Remedial (`#tab-remedial`):** Remedial sessions allocated to staff with link to Remedial Portal (`/remedial-sessions`).
  4. **Leave (`#tab-leave`):** Staff Leave Portal (Apply Leave button, Pending Staff Approvals queue for supervisors, My Leave Application History, CCL Date toggle, and PDF download).
  5. **Mentoring (`#tab-mentoring`):** Mentor-specific tutorship batches and student leave approval actions (`/api/mentoring/leave/action`).
  6. **Profile (`#tab-profile`):** Staff profile overview and account password update form.
- Embedded Face Punch / Biometric status card for SF staff (`/sf-attendance/face-punch`).

---

## 3. Why It Is Currently Hidden

The self-service leave portal is hidden/inaccessible on desktop via three distinct mechanisms:

1. **User-Agent Detection & Auto-Redirect:**  
   In `app/Http/Controllers/MentoringController.php` (lines 1793–1825):
   ```php
   $ua = strtolower($request->header('User-Agent', ''));
   $isMobileDevice = (bool)preg_match('/(android|bb\d+|meego).+mobile|avail|blackberry|iphone|ipad|ipod|palm|phone|opera mini|iemobile/i', $ua);

   if (!$isMobileDevice && !$request->has('mobile') && $request->input('mode') !== 'mobile') {
       // Automatically redirects desktop browser to role dashboard
       return redirect('/dashboard/' . $roleRoute . '?mode=desktop');
   }
   ```
2. **Omission in Desktop Navigation Configuration:**  
   `config/navigation/faculty.php` defines desktop sidebar links for `my_batches`, `attendance_log`, `remedial`, `course_files`, `prof_activities`, and `profile`, but has **no entry** for `my_leave`.
3. **Absence of Self-Service Panel in Desktop Faculty Workspace:**  
   `resources/views/lecturer_dashboard.blade.php` (the primary desktop interface for lecturers, tutors, and demonstrators) has batch management and virtual classroom panels, but no self-service leave application or balance card.

---

## 4. Route Inventory

| HTTP Method | Route URI | Controller / Handler | Middleware / Auth | Purpose |
|-------------|-----------|----------------------|-------------------|---------|
| `GET` | `/staff/mobile` | `MentoringController@showStaffMobileDashboard` | Session `userId` (Excludes Student) | Renders mobile staff portal view |
| `POST` | `/api/staff/leave/apply` | `StaffLeaveController@applyLeave` | Session `userId` | Submits new staff leave application |
| `GET` | `/api/staff/leave/my-history` | `StaffLeaveController@getMyLeaveHistory` | Session `userId` | Fetches staff member's leave requests & CL balance |
| `GET` | `/api/staff/leave/pending-approvals` | `StaffLeaveController@getPendingApprovals` | Role check (HOD, Coordinator, Principal, Admin) | Fetches pending leave approvals for approver |
| `POST` | `/api/staff/leave/process-approval` | `StaffLeaveController@processApproval` | Session `userId` + Role check | Approves or rejects leave at HOD/Coordinator/Principal stage |
| `GET` | `/staff/leave/{id}/pdf` | `StaffLeaveController@generateLeavePDF` | Session `userId` | Renders formal A4 PDF leave application document |
| `GET` | `/staff/leave/reports` | `StaffLeaveController@getLeaveReports` | HOD, Coordinator, Principal, Admin | Renders / queries master leave ledger |
| `GET` | `/api/staff/leave/reports-data` | Route Closure (`routes/web.php:1843`) | HOD, Principal, Admin | JSON data endpoint for master leave ledger |
| `GET` | `/staff/attendance-log` | `AttendanceController@viewPage` | Session `userId` | Class student attendance log |
| `GET` | `/sf-attendance/face-punch` | Route Closure (`routes/web.php`) | SF Staff Session | Biometric face punch interface |
| `GET` | `/api/sf-attendance/data` | Route Closure (`routes/web.php:1933`) | Admin, Principal, HOD | Biometric time punch logs |

---

## 5. API Inventory & Contracts

### 5.1 Apply Leave (`POST /api/staff/leave/apply`)
- **Headers:** `Content-Type: application/json`, `X-CSRF-TOKEN: ...`
- **Request Payload:**
  ```json
  {
    "leave_type": "Casual Leave",
    "session_type": "Full Day",
    "from_date": "2026-08-25",
    "to_date": "2026-08-26",
    "ccl_date": null,
    "total_days": 2.0,
    "reason": "Family function",
    "work_arrangement": [
      {
        "classroom": "CT-S5",
        "substitute_name": "Prof. John Doe",
        "date": "2026-08-25"
      }
    ]
  }
  ```
- **Validation Rules:**
  - `leave_type`: Required, string (`Casual Leave`, `Compensatory Casual Leave`, `Duty Leave`, `Medical Leave`, `Loss of Pay`, `Special Leave`).
  - `session_type`: Required, in: `Full Day`, `FN`, `AN`.
  - `from_date`: Required, date.
  - `to_date`: Required, date, after_or_equal:from_date.
  - `ccl_date`: Nullable, date (Required if CCL).
  - `total_days`: Required, numeric, min: 0.5.
  - `reason`: Required, string.
  - `work_arrangement`: Nullable, array.
- **Response Structure (Success):**
  ```json
  {
    "status": "SUCCESS",
    "message": "Leave application submitted successfully and sent to HOD for approval.",
    "leave_code": "SLV-2026-AB12CD",
    "request": { ... }
  }
  ```

### 5.2 My Leave History (`GET /api/staff/leave/my-history`)
- **Authentication:** Resolved via `Session::get('userId')` (staff mobile number).
- **Response Structure (Success):**
  ```json
  {
    "status": "SUCCESS",
    "leaves": [
      {
        "id": 14,
        "leave_code": "SLV-2026-X8Y9Z1",
        "staff_mobile": "9876543210",
        "staff_name": "Prof. Alex",
        "designation": "Lecturer",
        "department": "CT",
        "leave_type": "Casual Leave",
        "session_type": "Full Day",
        "from_date": "2026-08-20",
        "to_date": "2026-08-20",
        "ccl_date": null,
        "total_days": 1.0,
        "reason": "Personal urgent work",
        "work_arrangement": [],
        "hod_status": "Approved",
        "coordinator_status": "Approved",
        "principal_status": "Approved",
        "overall_status": "Approved",
        "created_at": "2026-08-19T10:00:00.000000Z"
      }
    ],
    "cl_total": 15,
    "cl_taken": 3.5
  }
  ```

### 5.3 Pending Approvals (`GET /api/staff/leave/pending-approvals`)
- **Role-based Filtering:**
  - `HOD`: Returns requests where `department = userBranch` AND `overall_status = 'Pending_HOD'`.
  - `Academic_Coordinator`: Returns requests where `overall_status = 'Pending_Coordinator'`.
  - `Principal` / `Super_Admin` / `Admin`: Returns requests where `overall_status` is `Pending_Principal`, `Pending_HOD`, or `Pending_Coordinator`.
- **Response Structure:**
  ```json
  {
    "status": "SUCCESS",
    "role": "HOD",
    "approvals": [ ... ]
  }
  ```

### 5.4 Process Approval (`POST /api/staff/leave/process-approval`)
- **Payload:**
  ```json
  {
    "leave_id": 14,
    "stage": "HOD",
    "action": "Approved",
    "remarks": "Recommended"
  }
  ```
- **Hierarchy Progression:**
  - If **SF Department** (`EL`, `CT`, `AU`, `GEN_SF`): HOD `Approved` &rarr; status becomes `Pending_Coordinator`. Coordinator `Approved` &rarr; status becomes `Pending_Principal`. Principal `Approved` &rarr; status becomes `Approved`.
  - If **Aided Department** (`EEE`, `ME`, `CE`, `GEN_AIDED`): HOD `Approved` &rarr; status skips Coordinator and becomes `Pending_Principal` with coordinator status marked `N/A (Aided Stream)`. Principal `Approved` &rarr; status becomes `Approved`.
  - If any stage selects `Rejected`, status immediately becomes `Rejected`.

---

## 6. Controller Inventory

1. **`App\Http\Controllers\StaffLeaveController`** (371 lines)
   - `applyLeave(Request $request)`: Validates input, creates digital signature hash, inserts into `staff_leave_requests`.
   - `getMyLeaveHistory()`: Computes CL taken vs 15 total days, fetches ordered history.
   - `getPendingApprovals()`: Resolves user role and queries applicable approval queue.
   - `processApproval(Request $request)`: Executes 2-tier or 3-tier hierarchy transition.
   - `generateLeavePDF($id)`: Prepares data for formal A4 print view `staff_leave_pdf.blade.php`.
   - `getLeaveReports(Request $request)`: Computes category summary (CL, CCL, DL, ML, LOP, SL) and renders/returns JSON ledger.
2. **`App\Http\Controllers\MentoringController`** (2,129 lines)
   - `showStaffMobileDashboard(Request $request)`: Fetches staff profile, assigned subjects, remedial rooms, biometric time punch, to-dos, and renders mobile view.

---

## 7. JavaScript Function Inventory (Mobile Reference)

| Function Name | Target DOM Container | API Endpoint Called | Description |
|---------------|----------------------|---------------------|-------------|
| `switchStaffTab(e, tabId)` | `.tab-pane`, `.nav-link-mobile` | N/A | Toggles mobile active tab pane |
| `openStaffLeaveModal()` | `#staffLeaveModal` | N/A | Resets and displays leave modal |
| `closeStaffLeaveModal()` | `#staffLeaveModal` | N/A | Hides leave modal |
| `toggleCclDateField()` | `#cclDateBox`, `#clBalanceInfo` | N/A | Toggles CCL work date input & CL balance |
| `addWorkArrangementRow()` | `#arrList` | N/A | Adds classroom & substitute staff row |
| `removeWorkArrangementRow(idx)` | `#arrList` | N/A | Removes work arrangement row by index |
| `renderWorkArrangements()` | `#arrList` | N/A | Re-renders substitute badges |
| `submitStaffLeaveRequest()` | `#staffLeaveAlert`, `#btnSubmitStaffLeave` | `POST /api/staff/leave/apply` | Validates & sends leave JSON |
| `loadMyLeaveHistory()` | `#myLeaveHistoryContainer`, `#clBalanceInfo` | `GET /api/staff/leave/my-history` | Renders leave history cards & CL metrics |
| `loadPendingApprovals()` | `#pendingApprovalsContainer` | `GET /api/staff/leave/pending-approvals` | Renders supervisor action list |
| `actionLeaveApproval(id, stage, action)` | `#pendingApprovalsContainer` | `POST /api/staff/leave/process-approval` | Prompts for remarks and executes decision |
| `selectDayOrder(dayKey)` | `#selectedDayBadge`, `#timetableScheduleContainer` | `POST /api/system/set-day-order` | Updates college-wide day order |
| `updateStaffPassword()` | `#staffPwdAlert` | `POST /api/admin/user/reset-password` | Updates user account password |

---

## 8. DOM & Component Inventory

### Key Form Elements in Leave Application
- `#slvType`: `<select>` (Casual Leave, Compensatory Casual Leave, Duty Leave, Medical Leave, Loss of Pay, Special Leave).
- `#slvSession`: `<select>` (`Full Day`, `FN`, `AN`).
- `#slvFromDate`: `<input type="date">`.
- `#slvToDate`: `<input type="date">`.
- `#slvTotalDays`: `<input type="number" step="0.5" min="0.5">`.
- `#slvCclDate`: `<input type="date">` (Container: `#cclDateBox`).
- `#slvReason`: `<textarea rows="2">`.
- `#arrClassroom`: `<input type="text">` (Period/Class).
- `#arrSubstitute`: `<input type="text">` (Substitute Staff Name).
- `#arrList`: `<ul>` container for substitute mappings.
- `#btnSubmitStaffLeave`: `<button>` trigger for submission.

---

## 9. Role & Authorization Matrix

| User Role | Can View Personal Leave & Balances | Can Apply For Leave | Can View Personal Biometric Status | Can Approve Dept Leaves (Stage 1: HOD) | Can Approve SF Leaves (Stage 2: Coord) | Can Final-Approve Leaves (Stage 3: Principal) | Can View Master Leave Ledger |
|---|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| **Lecturer / Faculty** | YES | YES | YES (SF only) | NO | NO | NO | NO |
| **Tutor** | YES | YES | YES (SF only) | NO | NO | NO | NO |
| **Demonstrator / Trade Instructor** | YES | YES | YES (SF only) | NO | NO | NO | NO |
| **Workshop Superintendent** | YES | YES | YES (SF only) | NO | NO | NO | NO |
| **HOD** | YES | YES | YES (SF only) | YES (Own Dept) | NO | NO | YES (Own Dept) |
| **Academic Coordinator (SF)** | YES | YES | YES (SF only) | NO | YES (SF Depts) | NO | YES (All Depts) |
| **Principal / Executive** | YES | YES | YES (SF only) | YES (Fallback) | YES (Fallback) | YES (All College) | YES (All Depts) |
| **Admin / Super Admin** | YES | YES | YES (SF only) | YES (Admin override) | YES (Admin override) | YES (All College) | YES (All Depts) |
| **Student** | NO | NO (Student Leave is separate) | NO | NO | NO | NO | NO |

---

## 10. Database & Data Dependencies

### Table: `staff_leave_requests` (34 columns)
- `id` (BIGINT, Primary Key)
- `leave_code` (VARCHAR, e.g. `SLV-2026-XXXXXX`)
- `staff_mobile` (VARCHAR, links to `staff_profiles.mobile_no`)
- `staff_name`, `designation`, `department` (VARCHAR)
- `leave_type`, `from_date`, `to_date`, `session_type`, `ccl_date`, `total_days`, `reason`
- `work_arrangement` (JSON array of `{classroom, substitute_name, date}`)
- `staff_signature_hash` (VARCHAR 64-char SHA256)
- `submitted_at` (DATETIME)
- `hod_status`, `hod_mobile`, `hod_name`, `hod_remarks`, `hod_action_at`
- `coordinator_status`, `coordinator_mobile`, `coordinator_name`, `coordinator_remarks`, `coordinator_action_at`
- `principal_status`, `principal_mobile`, `principal_name`, `principal_remarks`, `principal_action_at`
- `overall_status` (`Pending_HOD`, `Pending_Coordinator`, `Pending_Principal`, `Approved`, `Rejected`)
- `created_at`, `updated_at`

### Table: `sf_staff_time_punches` (23 columns)
- `id` (BIGINT, Primary Key)
- `staff_id` (VARCHAR, links to staff mobile/ID)
- `staff_name`, `punch_date`, `in_time`, `out_time`
- `in_gps_lat`, `in_gps_lng`, `in_gps_distance_meters`, `in_premises_status`
- `out_gps_lat`, `out_gps_lng`, `out_gps_distance_meters`, `out_premises_status`
- `liveness_type`, `liveness_score`, `biometric_confidence`, `punch_status`
- `in_snapshot_url`, `out_snapshot_url`, `remarks`
- `created_at`, `updated_at`

---

## 11. Existing Desktop Implementations

The following desktop interfaces already touch leave or attendance data:
1. **HOD Dashboard (`hod_dashboard.blade.php`):** Contains `panelLeave_ledger` for department-wide leave monitoring and filtering.
2. **Admin Control Desk (`admin_control_desk.blade.php`):** Contains college-wide `panelLeave_ledger` and `panelSf_attendance`.
3. **Staff Attendance Log (`attendance_log.blade.php`):** Dedicated desktop page for faculty to record classroom student attendance per period.
4. **Staff Leave Master Report (`staff_leave_reports.blade.php`):** Standalone table view for printing multi-department leave reports.
5. **Printable Leave Form (`staff_leave_pdf.blade.php`):** A4 formal print view for individual approved/pending leave applications.

---

## 12. Mobile Preservation Contract

To guarantee that the existing mobile application and future React Native applications remain completely unaffected:
1. **Zero Modifications to `staff_mobile_dashboard.blade.php`:** The file will remain strictly as-is.
2. **Zero Route Breakages for Mobile Clients:** `GET /staff/mobile` and `POST /api/mentoring/leave/action` will remain unchanged.
3. **Zero API Changes to Core Leave Endpoints:** The desktop workspace will call the exact same endpoints (`/api/staff/leave/apply`, `/api/staff/leave/my-history`, `/api/staff/leave/pending-approvals`, `/api/staff/leave/process-approval`).
4. **Independent View Separation:** Desktop functionality will live in a dedicated desktop Blade view (`resources/views/staff_my_leave.blade.php`) using `<x-layouts.app-shell>` rather than forcing responsive desktop CSS onto the mobile view.

---

## 13. CampusLynk Desktop Integration Recommendation

### Architecture Strategy: Dedicated Desktop Workspace (`staff_my_leave.blade.php`)
Rather than crowding `lecturer_dashboard.blade.php` with massive modal and tab logic or modifying `staff_mobile_dashboard.blade.php`, the recommended architecture is:

1. **Dedicated View:** `resources/views/staff_my_leave.blade.php`
   - Built on `<x-layouts.app-shell activeNav="my_leave">`.
   - Uses `#FAFAFB` background canvas, Poppins typography, Lucide icons, and Tailwind utility styling.
   - Fully conforms to the CampusLynk Design System and the 14px minimum font policy.
2. **Unified Navigation Integration:**
   - Add `my_leave` item to `config/navigation/faculty.php`:
     ```php
     [
         'id' => 'my_leave',
         'label' => 'My Leave & Attendance',
         'icon' => 'calendar-check-2',
         'url' => '/staff/my-leave',
     ]
     ```
   - Inherited automatically by `tutor`, `lecturer`, `demonstrator`, `trade_instructor`, and `workshop_superintendent`.
3. **Desktop Layout Composition:**
   - **Hero Card:** Staff identity, designation, department badge, and primary action button (`Apply for Leave`).
   - **Leave Balance KPI Row:** 4 KPI cards (Casual Leave Balance [Total: 15 / Taken / Remaining], Compensatory CCL Days, Duty Leave DL Days, Other Leaves).
   - **Today's Attendance & Time Punch Widget:** Shows Morning IN / Evening OUT times, on-campus geofence verification, and total campus duration (for SF staff).
   - **Pending Approvals Queue (for HOD / Coordinator / Principal):** Collapsible review drawer or section for 1-click approvals with modal remarks.
   - **My Leave Applications Table / Ledger:** Clean tabular history with sortable columns, multi-stage approval badges (`Pending HOD`, `Pending Coordinator`, `Pending Principal`, `Approved`, `Rejected`), and 1-click PDF download links.
   - **Application Modal:** Sleek sliding drawer or modal with session picker, CCL date selector, dynamic substitute addition, and live validation.

---

## 14. Proposed Migration Boundaries

```
┌────────────────────────────────────────────────────────────────────────┐
│                   SHARED BACKEND API LAYER (UNTOUCHED)                 │
│  /api/staff/leave/apply             /api/staff/leave/my-history        │
│  /api/staff/leave/pending-approvals /api/staff/leave/process-approval  │
│  /staff/leave/{id}/pdf              /api/sf-attendance/data            │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │
          ┌─────────────────────────┴─────────────────────────┐
          │                                                   │
┌─────────▼────────────────────────┐        ┌─────────────────▼──────────────────┐
│  EXISTING MOBILE SUBSYSTEM       │        │  NEW CAMPUSLYNK DESKTOP SUBSYSTEM  │
│  (100% PRESERVED & UNTOUCHED)    │        │  (PHASE 2D.1 IMPLEMENTATION)       │
│                                  │        │                                    │
│  • GET /staff/mobile             │        │  • GET /staff/my-leave             │
│  • staff_mobile_dashboard.blade  │        │  • staff_my_leave.blade.php        │
│  • Bootstrap 5 + Dark Theme      │        │  • <x-layouts.app-shell>           │
│  • Mobile Bottom Tab Nav         │        │  • config/navigation/faculty.php   │
│  • Future React Native Target    │        │  • Poppins + Lucide + #FAFAFB      │
└──────────────────────────────────┘        └────────────────────────────────────┘
```

---

## 15. Files That Would Need Modification (During Phase 2D.1 Implementation)

1. `routes/web.php`: Add `Route::get('/staff/my-leave', [App\Http\Controllers\StaffLeaveController::class, 'showMyLeaveDesktop']);`
2. `app/Http/Controllers/StaffLeaveController.php`: Add `showMyLeaveDesktop(Request $request)` method to supply staff profile, leave balances, and today's punch data.
3. `config/navigation/faculty.php`: Add `my_leave` navigation entry.
4. `resources/views/staff_my_leave.blade.php`: Create new desktop Blade view.

---

## 16. Files That Must Remain Untouched

- `resources/views/staff_mobile_dashboard.blade.php` (Mobile view strictly preserved)
- `resources/views/sf_staff_face_punch.blade.php`
- `resources/views/staff_leave_pdf.blade.php`
- `database/migrations/*` (Database schema untouched)
- `app/Models/StaffLeaveRequest.php`
- `app/Models/SfStaffTimePunch.php`

---

## 17. Risks & Compatibility Checks

| Risk Factor | Severity | Mitigation Strategy |
|---|:---:|---|
| **Digital Signature Hash Integrity** | High | Reuse exact SHA-256 payload generation in `StaffLeaveController@applyLeave` |
| **Stream Routing Divergence (SF vs Aided)** | High | Use the existing `isSelfFinancingDepartment()` logic in `StaffLeaveController` |
| **CL Balance Calculation** | Medium | Sum `total_days` where `leave_type = 'Casual Leave'` and `overall_status != 'Rejected'` |
| **User Session Identification** | Medium | Strictly use `Session::get('userId')` which stores the staff member's mobile number |

---

## 18. Recommended Implementation Phases

- **Phase 2D.0 (Current):** Forensic Audit & Architectural Baseline (**COMPLETED**).
- **Phase 2D.1:** Route & Controller Setup for `GET /staff/my-leave` and navigation configuration.
- **Phase 2D.2:** Build `resources/views/staff_my_leave.blade.php` using `<x-layouts.app-shell>`.
- **Phase 2D.3:** Implement Leave Application Modal with dynamic work arrangement table.
- **Phase 2D.4:** Verification across Lecturer, Tutor, HOD, and SF Coordinator roles.

---

## 19. Verification Strategy

1. **Syntax & Compilation Verification:** `php -l` on modified files and `npm run build` on assets.
2. **Role Access Testing:** Verify navigation visibility across Lecturer, Tutor, Demonstrator, and HOD sessions.
3. **Application Lifecycle Test:** Submit leave as Lecturer &rarr; verify in HOD pending queue &rarr; approve &rarr; verify status change and PDF generation.
4. **Mobile Independence Test:** Access `/staff/mobile?mode=mobile` to verify mobile dashboard renders identically without regression.

---

FORENSIC AUDIT COMPLETE — NO PRODUCTION FILES MODIFIED.
