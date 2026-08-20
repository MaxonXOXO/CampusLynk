# CampusLynk Phase 2C.4 — HOD SF Staff Attendance Ledger Forensic Baseline

**Phase:** Phase 2C.4 — Forensic Baseline & DOM / API Inventory  
**Target:** Self-Financing (SF) Staff Attendance Master Ledger  
**Reference Files:**  
1. `resources/views/admin_control_desk.blade.php` (Modernized Executive Desk Implementation)  
2. `resources/views/sf_staff_attendance_report.blade.php` (Legacy Standalone Report Implementation)  
**Target View:** `resources/views/hod_dashboard.blade.php`  
**Execution Date:** August 20, 2026  

---

## 1. Architectural Overview & Forensic Comparison

The Self-Financing (SF) Staff Attendance Ledger tracks daily biometric face recognition punches and campus GPS geofence compliance for self-financing faculty members (Shift: 09:00 AM – 04:00 PM).

### Comparison Matrix

| Architectural Dimension | Legacy Standalone (`sf_staff_attendance_report.blade.php`) | Principal Desk (`admin_control_desk.blade.php`) | Target HOD Panel (`hod_dashboard.blade.php`) |
|:---|:---|:---|:---|
| **Archetype** | Standalone Dark View (`#0f172a`, FontAwesome, Bootstrap) | Integrated Asynchronous Panel (`#panelSf_attendance`) | Integrated Asynchronous Panel (`#panelSf_attendance`) |
| **Data Fetching** | Server-rendered Blade with polling page reload | Asynchronous JSON via `GET /api/sf-attendance/data` | Asynchronous JSON via `GET /api/sf-attendance/data` |
| **Navigation** | Full-page navigation `/sf-attendance/attendance-report` | Panel switcher: `switchPanel('sf_attendance')` | Panel switcher: `handleHodSidebarNav('sf_attendance')` |
| **Geofence Modal** | `#modalGeofence` (Custom dark modal) | `#geofenceModal` (CampusLynk Tailwind modal) | `#geofenceModal` (CampusLynk Modal.v1) |
| **Punch Deletion** | `deletePunchRecord(id, ...)` via `DELETE /sf-attendance/delete-punch/{id}` | `deleteSfPunch(id)` via `DELETE /sf-attendance/delete-punch/{id}` | `deleteSfPunch(id)` via `DELETE /sf-attendance/delete-punch/{id}` |
| **GPS Geolocation** | `detectCurrentLocation()` via `navigator.geolocation` | `captureCurrentGPS()` via `navigator.geolocation` | `captureCurrentGPS()` via `navigator.geolocation` |

---

## 2. DOM ID Inventory

| DOM ID | Element Type | Role / Functionality | Target State |
|:---|:---|:---|:---:|
| `panelSf_attendance` | `<div>` Container | Master container panel for SF Attendance Ledger | ✅ Integrated |
| `sfAttStartDate` | `<input type="date">` | Filter start date (defaults to 1st of current month) | ✅ Preserved |
| `sfAttEndDate` | `<input type="date">` | Filter end date (defaults to current date) | ✅ Preserved |
| `sfAttSearch` | `<input type="text">` | Live debounced faculty search (name / staff ID) | ✅ Preserved |
| `sfAttendanceTableBody` | `<tbody>` | Dynamic insertion point for punch log table rows | ✅ Preserved |
| `geofenceModal` | `<div>` Modal Frame | Campus GPS & Geofence setup modal container | ✅ Preserved |
| `geofenceForm` | `<form>` | Form wrapper for campus geofence coordinates | ✅ Preserved |
| `geoCampusName` | `<input type="text">` | Campus institution name string | ✅ Preserved |
| `geoLat` | `<input type="number">`| Centroid latitude (°N) | ✅ Preserved |
| `geoLng` | `<input type="number">`| Centroid longitude (°E) | ✅ Preserved |
| `geoRadius` | `<input type="number">`| Allowed geofence circle radius in meters | ✅ Preserved |
| `geoAccuracy` | `<input type="number">`| Maximum acceptable device GPS accuracy threshold | ✅ Preserved |
| `geofenceAlert` | `<div>` Alert Box | Validation feedback message container | ✅ Preserved |
| `btnSaveGeofence` | `<button type="submit">` | Form submission button with loading state | ✅ Preserved |

---

## 3. JavaScript Functions Inventory

| Function Name | Location / Source | Responsibility & Behavior | Status |
|:---|:---|:---|:---:|
| `debounceSfSearch()` | `admin_control_desk.blade.php` | Debounces keyboard input (350ms) before triggering `loadSfAttendance()` | ✅ Preserved |
| `loadSfAttendance()` | `admin_control_desk.blade.php` | Asynchronously queries `/api/sf-attendance/data` with date/search params and renders rows | ✅ Preserved |
| `deleteSfPunch(id)` | `admin_control_desk.blade.php` | Confirms and sends `DELETE /sf-attendance/delete-punch/{id}` | ✅ Preserved |
| `openGeofenceModal()` | `admin_control_desk.blade.php` | Fetches `/api/admin/geofence/settings` and displays `#geofenceModal` | ✅ Preserved |
| `closeGeofenceModal()`| `admin_control_desk.blade.php` | Hides `#geofenceModal` | ✅ Preserved |
| `captureCurrentGPS()` | `admin_control_desk.blade.php` | Reads device GPS via `navigator.geolocation.getCurrentPosition` | ✅ Preserved |
| `submitGeofenceSetup(e)`| `admin_control_desk.blade.php` | Submits JSON payload to `POST /sf-attendance/geofence-setup` | ✅ Preserved |

---

## 4. Backend API & Route Inventory

| HTTP Method | Route URI | Controller Action / Handler | Payload / Parameters | Response Structure | Reused As-Is |
|:---|:---|:---|:---|:---|:---:|
| `GET` | `/api/sf-attendance/data` | `AdminController@getSfAttendanceData` | `?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD&search=...` | `{ status: 'SUCCESS', punches: [...], geofence: {...} }` | ✅ YES |
| `GET` | `/api/admin/geofence/settings` | `AdminController@getGeofenceSettings` | None | `{ status: 'SUCCESS', geofence: { campus_name, centroid_lat, centroid_lng, radius_meters, max_accuracy_meters } }` | ✅ YES |
| `POST` | `/sf-attendance/geofence-setup` | `AdminController@saveGeofenceSetup` | `{ campus_name, centroid_lat, centroid_lng, radius_meters, max_accuracy_meters }` | `{ status: 'SUCCESS', message: '...' }` | ✅ YES |
| `DELETE` | `/sf-attendance/delete-punch/{id}` | `AdminController@deletePunch` | URL parameter `{id}` | `{ status: 'SUCCESS', message: '...' }` | ✅ YES |

---

## 5. Scope Isolation Boundaries

To protect codebase integrity during Phase 2C.4:
- **Untouched HOD Panels:** `#panelBatches`, `#panelDirectory`, `#panelSubjects`, `#panelAudit`, `#panelProfile` remain 100% untouched.
- **Excluded Features:** Staff Leave Ledger (`staff_leave_reports.blade.php`), Subject Allocation, Audit Trail, and Mobile views are strictly excluded.
- **Single Workspace Rule:** `/dashboard/hod` remains the canonical route. No external redirects or duplicate sub-shells.
