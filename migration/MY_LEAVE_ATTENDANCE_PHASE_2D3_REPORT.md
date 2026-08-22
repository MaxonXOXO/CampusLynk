# CAMPUSLYNK — MY LEAVE & ATTENDANCE
## PHASE 2D.3 — MY LEAVE APPLICATION & HISTORY REPORT

**Document Version:** 1.0.0  
**Execution Date:** August 22, 2026  
**Status:** COMPLETED & VERIFIED  
**Phase:** 2D.3 (Leave Application Modal & Leave Application History)

---

## 1. Executive Summary

In Phase 2D.3, the **Leave Application Modal / Drawer** and **Section 3: Leave Application History** have been implemented for the desktop **My Leave & Attendance** workspace (`resources/views/staff_my_leave.blade.php`).

This enables faculty and staff to:
1. Submit formal leave applications with dynamic category validation (CL quota check, CCL worked date requirement, session selection, auto-computed day duration, and period-wise substitute arrangements) via `POST /api/staff/leave/apply`.
2. View real-time historical records, substitute arrangements, and hierarchical approval statuses (`Pending HOD`, `Pending Coordinator`, `Pending Principal`, `Approved`, `Rejected`) via `GET /api/staff/leave/my-history`.
3. Open and download official A4 printable Leave Application PDFs (`GET /staff/leave/{id}/pdf`) in a new tab without disrupting the CampusLynk workspace.

---

## 2. API Contracts & Backend Integration

| Endpoint | Method | Purpose | Request / Response Payload |
|---|:---:|---|---|
| `/api/staff/leave/apply` | `POST` | Submit new leave application | **Body:** `{ leave_type, session_type, from_date, to_date, ccl_date, total_days, reason, work_arrangement: [{ classroom, substitute_name, date }] }`<br>**Response:** `{ status: "SUCCESS", message: "...", leave_code: "SLV-...", request: {...} }` |
| `/api/staff/leave/my-history` | `GET` | Retrieve staff leave records & CL quota | **Response:** `{ status: "SUCCESS", cl_total: 15, cl_taken: 2.0, leaves: [{ id, leave_code, leave_type, session_type, from_date, to_date, total_days, reason, work_arrangement, overall_status, ... }] }` |
| `/staff/leave/{id}/pdf` | `GET` | View/Print official A4 PDF | Generates formal printable leave order with digital signature hash and multi-tier approval blocks. |

---

## 3. UI Implementation Details

### Apply Leave Modal (`#applyLeaveModal`)
- **Category Support:** Casual Leave (CL), Compensatory Casual Leave (CCL), Duty Leave (DL), Medical Leave (ML), Loss of Pay (LOP), Special Leave (SL).
- **Session Types:** Full Day (1.0 d/day), FN - Forenoon (0.5 d), AN - Afternoon (0.5 d).
- **Conditional CCL Duty Date:** Automatically prompts for the holiday date worked when CCL is chosen.
- **Duration Calculation:** Automatically computes duration based on `from_date`, `to_date`, and `session_type` with manual override options.
- **Dynamic Substitutes:** Allows adding multiple class/period substitute faculty arrangements with interactive chips and one-click removal.
- **CSRF & Submit Protection:** Secure headers (`X-CSRF-TOKEN`) with spinner state on `#btnSubmitLeave` to prevent duplicate clicks.
- **Auto-Refresh:** On successful submission, balances and history are re-hydrated automatically.

### Section 3: Leave Application History
- **Desktop Table View:** Responsive layout showing Leave Type & Code, Date Range & Duration, Reason & Substitute Tags, Multi-Stage Approval Status Badge, and Order PDF button.
- **Mobile Card View:** Graceful collapsing on narrow screens.
- **PDF Download Integration:** Direct 1-click action opening `/staff/leave/{id}/pdf` in `target="_blank"`.
- **Empty & Error States:** Illustrated empty ledger state with quick-apply action, and retry button for network drops.

---

## 4. Preservation & Scope Verification

- **Section 4 (Multi-Stage Approval Status):** Explicitly preserved as a placeholder deferred to **Phase 2D.4**.
- **Mobile Subsystem Preserved:** `resources/views/staff_mobile_dashboard.blade.php` and `GET /staff/mobile` remain 100% untouched.
- **PDF Template Preserved:** `resources/views/staff_leave_pdf.blade.php` remains 100% untouched.
- **Backend Business Logic:** No changes to backend approval workflows, digital signature generation, or database migrations.

---

## 5. Build & Verification Results

1. **PHP Linter Checks:**
   - `app/Http/Controllers/StaffLeaveController.php`: `[PASS] No syntax errors detected`
   - `routes/web.php`: `[PASS] No syntax errors detected`
2. **Vite Production Asset Build:**
   - Production assets compiled successfully (`app-BzaQpGYD.css`, `app-gmC_pBXH.js`).
3. **Laravel Cache Clear:**
   - `view:clear`, `route:clear`, `config:clear`: All cleared successfully.
4. **Smoke Test (`test_my_leave_phase2d3.php`):**
   - Modal and form elements: `[PASS]`
   - Leave history container and table bindings: `[PASS]`
   - PDF download route links: `[PASS]`
   - Mobile preservation (`staff_mobile_dashboard`): `[PASS]`
   - Core dashboards (`lecturer_dashboard`, `hod_dashboard`): `[PASS]`

---

PHASE 2D.3 MY LEAVE APPLICATION & HISTORY COMPLETE — MOBILE SUBSYSTEM PRESERVED — NO LEAVE BUSINESS LOGIC MODIFIED.
