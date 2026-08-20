# CampusLynk — HOD Staff Leave Master Ledger Forensic Baseline

**Phase:** Staff Leave Master Ledger UI Migration & Parity  
**Target Panel:** `#panelLeave_ledger`  
**Reference Files:**  
1. `resources/views/admin_control_desk.blade.php` (Principal Desk Modernized Leave Ledger)  
2. `resources/views/staff_leave_reports.blade.php` (Legacy Dark View)  
**Target View:** `resources/views/hod_dashboard.blade.php`  
**Execution Date:** August 20, 2026  

---

## 1. Architectural Overview & Forensic Comparison

| Dimension | Legacy Standalone (`staff_leave_reports.blade.php`) | Modernized HOD Panel (`#panelLeave_ledger`) |
|:---|:---|:---|
| **Theme / Archetype** | Dark Bootstrap (`#0f172a`, FontAwesome, small fonts) | CampusLynk Data Table & Metric Cards (`#FFFFFF`, Poppins $\ge 14\text{px}$, Lucide) |
| **Navigation** | Full-page navigation to `/staff/leave/reports` | Asynchronous panel switching `handleHodSidebarNav('leave_ledger')` |
| **Data Fetching** | Server-side blade rendering | Asynchronous JSON via `GET /api/staff/leave/reports-data` |
| **Summary KPIs** | 6 Category Totals (CL, CCL, DL, ML, LOP, SL) | 6 Reactive Metric Cards (`#leaveKpiTotal`, `#leaveKpiCL`, `#leaveKpiCCL`, `#leaveKpiDL`, `#leaveKpiML`, `#leaveKpiLOP`) |
| **Approval Actions** | Full-page form redirects | In-line HOD approval/rejection via `POST /api/staff/leave/process-approval` |
| **PDF Export** | `/staff/leave/{id}/pdf` | `/staff/leave/{id}/pdf` (Direct PDF Order View) |

---

## 2. DOM ID & Component Inventory

| DOM ID | Element Type | Role / Functionality | Status |
|:---|:---|:---|:---:|
| `panelLeave_ledger` | Container `<div>` | Master container wrapper for Leave Ledger panel | ✅ Integrated |
| `leaveLedgerYear` | Select `<select>` | Academic Year filter dropdown | ✅ Preserved |
| `leaveLedgerDept` | Select `<select>` | Department filter dropdown (defaults to active branch) | ✅ Preserved |
| `leaveLedgerStatus` | Select `<select>` | Multi-stage status filter dropdown | ✅ Preserved |
| `leaveKpiTotal` | Number `<span>` | Total days across filtered leave applications | ✅ Preserved |
| `leaveKpiCL` | Number `<span>` | Casual Leave (CL) total days metric | ✅ Preserved |
| `leaveKpiCCL` | Number `<span>` | Compensatory Casual Leave (CCL) total days metric | ✅ Preserved |
| `leaveKpiDL` | Number `<span>` | Duty Leave (DL) total days metric | ✅ Preserved |
| `leaveKpiML` | Number `<span>` | Medical Leave (ML) total days metric | ✅ Preserved |
| `leaveKpiLOP` | Number `<span>` | Loss of Pay (LOP) total days metric | ✅ Preserved |
| `leaveLedgerTableBody` | Table `<tbody>` | Dynamic insertion point for leave applications | ✅ Preserved |

---

## 3. JavaScript Functions & Backend Routes

| Function / Action | HTTP Method | Endpoint | Payload / Behavior |
|:---|:---:|:---|:---|
| `loadLeaveLedger()` | `GET` | `/api/staff/leave/reports-data` | `?department=...&academic_year=...&status=...` |
| `processLeaveApproval(id, decision)` | `POST` | `/api/staff/leave/process-approval` | `{ leave_id: id, stage: 'HOD', decision: 'Approve'|'Reject', remarks: '...' }` |
| Print Leave Order | `GET` | `/staff/leave/{id}/pdf` | Opens printable official PDF leave application |
