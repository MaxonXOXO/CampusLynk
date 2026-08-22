# CAMPUSLYNK — MY LEAVE & ATTENDANCE
## PHASE 2D.2 — LEAVE BALANCE & ATTENDANCE WORKSPACE REPORT

**Document Version:** 1.0.0  
**Execution Date:** August 22, 2026  
**Status:** COMPLETED & VERIFIED  
**Phase:** 2D.2 (Leave Balance Summary & Today's Attendance Record)

---

## 1. Executive Summary

In Phase 2D.2, the first two functional sections of the **My Leave & Attendance** desktop workspace have been implemented and integrated with existing backend APIs:
1. **Section 1: Leave Balance Summary** — Interactive, responsive card grid showing statutory Casual Leave (CL 15-day quota with live progress bar), Compensatory Casual Leave (CCL), Duty Leave (DL), Medical Leave (ML), Loss of Pay (LOP), and Special Leave (SL), dynamically hydrated via `GET /api/staff/leave/my-history`.
2. **Section 2: Today's Attendance Record** — Real-time biometric attendance and residency summary displaying formatted Morning IN time, Evening OUT time, working duration calculation, on-campus geofence verification, and one-click biometric portal linkage (`/sf-attendance/face-punch`).

---

## 2. APIs Discovered & Data Contracts Reused

| API Route | Method | Source Controller | Data Mappings Used |
|---|:---:|---|---|
| `/api/staff/leave/my-history` | `GET` | `StaffLeaveController@getMyLeaveHistory` | `cl_total` (15), `cl_taken`, `leaves` array (aggregated for `CCL`, `DL`, `ML`, `LOP`, `SL`, and total days) |
| `/sf-attendance/face-punch` | `GET` | Mobile Face Punch Portal | Biometric punch link for Self-Financing and eligible staff |
| Database: `sf_staff_time_punches` | Query | `SfStaffTimePunch` Model | `punch_date`, `in_time`, `out_time`, `in_premises_status`, `in_gps_distance_meters`, `biometric_confidence`, `liveness_type` |

---

## 3. UI Implementation Details

### Section 1: Leave Balance Summary
- **Casual Leave (CL):** Displays available days (e.g. `15.0`), days taken, percentage used, and a responsive progress bar (`#clProgressBar`).
- **Compensatory Casual Leave (CCL):** Displays days taken with earned holiday duty badge.
- **Duty Leave (DL):** Displays days taken with official absence badge.
- **Medical Leave (ML):** Displays days taken with health/medical certification badge.
- **Secondary Leave Strip:** Compact inline summary of Loss of Pay (`LOP`), Special Leave (`SL`), and Total Absent Days.
- **Manual Refresh Action:** `loadLeaveBalances()` button with spinning indicator and status synchronization badge (`Live Ledger Synced` vs `Sync Failed (Offline)`).

### Section 2: Today's Attendance Record
- **Overall State Badge:** `NOT PUNCHED` (Amber), `CHECKED IN` (Blue), or `COMPLETED` (Emerald).
- **Morning IN Box:** Formatted 12-hour time (`hh:mm A`) with entry evaluation (`EARLY IN`, `PRESENT`, `LATE IN`).
- **Evening OUT Box:** Formatted 12-hour time (`hh:mm A`) with departure evaluation (`EARLY OUT`, `ON TIME OUT`, `LATE OUT`).
- **Working Hours Box:** Computed hours and minutes (e.g. `7h 45m`) with real-time residency status.
- **Geofence & Biometric Verification Panel:** Verification indicator showing GPS proximity to Carmel Polytechnic Campus and confidence metrics.

---

## 4. Preservation & Scope Boundaries

- **Sections Deferred:**
  - **Section 3: Leave Application History** &rarr; Explicitly preserved as a placeholder for **Phase 2D.3**.
  - **Section 4: Multi-Stage Approval Status** &rarr; Explicitly preserved as a placeholder for **Phase 2D.4**.
  - **Apply Leave Modal** &rarr; Placeholder button maintained for **Phase 2D.3**.
- **Mobile Subsystem Preserved:**
  - `resources/views/staff_mobile_dashboard.blade.php`: Strictly UNTOUCHED.
  - `GET /staff/mobile`: Strictly UNTOUCHED and fully functional.
  - Mobile bottom navigation, CSS, and JS: 100% preserved.
- **Backend & Database Integrity:**
  - No database migrations, alterations, or seeders.
  - No business logic or calculation duplication.

---

## 5. Build & Verification Results

1. **PHP Linter Checks:**
   - `app/Http/Controllers/StaffLeaveController.php`: `[PASS] No syntax errors detected`
   - `routes/web.php`: `[PASS] No syntax errors detected`
2. **Vite Production Asset Build:**
   - Compiled production bundle successfully (`app-C8AjP31E.css`, `app-C3vyz9wK.js`).
3. **Laravel Cache Clear:**
   - `view:clear`, `route:clear`, `config:clear`: All cleared cleanly.
4. **Smoke Test Execution (`test_my_leave_phase2d2.php`):**
   - Initial unpunched state rendering: `[PASS]`
   - Active & completed time punch rendering: `[PASS]`
   - Mobile preservation (`staff_mobile_dashboard`): `[PASS]`
   - Core dashboards (`lecturer_dashboard`, `hod_dashboard`): `[PASS]`

---

## 6. Files Modified & Untouched

### Files Modified:
- `app/Http/Controllers/StaffLeaveController.php`: Hydrated `$todayPunch` in `showMyLeaveDesktop`.
- `resources/views/staff_my_leave.blade.php`: Implemented Sections 1 & 2 with live data bindings.

### Files Untouched:
- `resources/views/staff_mobile_dashboard.blade.php`
- `resources/views/sf_staff_face_punch.blade.php`
- `resources/views/staff_leave_pdf.blade.php`
- `config/navigation/*`
- `database/migrations/*`
- All existing API endpoints and backend calculation rules.

---

PHASE 2D.2 LEAVE BALANCE & ATTENDANCE WORKSPACE COMPLETE — MOBILE SUBSYSTEM PRESERVED — NO LEAVE BUSINESS LOGIC MODIFIED.
