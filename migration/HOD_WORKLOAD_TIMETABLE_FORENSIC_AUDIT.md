# CAMPUSLYNK — HOD FACULTY WORKLOAD & TIMETABLE SUBSYSTEM
## PHASE 2C.5B — CROSS-ROLE FORENSIC AUDIT & MIGRATION BASELINE
**Date:** August 22, 2026  
**Auditor:** DeepMind Antigravity AI Engineering Suite  
**Status:** COMPLETE FORENSIC AUDIT (READ-ONLY — ZERO PRODUCTION CODE MODIFICATIONS)  
**Primary Target:** `resources/views/hod_workload_panel.blade.php`  
**Cross-Role Targets:** `resources/views/principal_today_timetable.blade.php`, `ExecutiveControlDeskController.php`, `R26VirtualClassroomPracticumController.php`

---

## 1. Executive Summary

The **HOD Faculty Workload & Timetable subsystem** provides departmental academic leadership with capabilities to:
1. **Department Faculty Workload:** Dynamically compile and print the official **Faculty Workload Commencement Report** across all lecturers, demonstrators, physical instructors, and HOD in the department, calculating weekly theory and lab engaged hours.
2. **Individual Batch Weekly Timetable:** Select a classroom and semester to preview and print the finalized **A4 Landscape Weekly Class Timetable** with dynamic legend and staff allocations.
3. **Semester Consolidated Timetable (Clash Audit):** Select 2 or 3 active classes to compile a **Consolidated Department Timetable Matrix** displaying period-by-period schedules side-by-side for clash auditing and master schedule review.

### Key Forensic Findings:
- **Hybrid Data Architecture:** Classroom schedules are stored on disk as **JSON files** (`storage/app/timetables/{classroom_id}.json`), while faculty, subjects, and staff allocations are stored in **MySQL relational tables** (`class_management`, `staff_profiles`, `batch_subjects`, `subject_staff_assignments`).
- **Real-Time Workload Engine:** Workload hours are computed dynamically in PHP at print time by scanning all departmental JSON files matching `{branchCode}_*.json` and joining them with `batch_subjects` and `subject_staff_assignments`.
- **Cross-Role Disparity:** While HOD operates strictly within their branch scope (`session('userBranch')`) focusing on semester planning, the Principal and Executive desk (`PrincipalDashboardController`, `ExecutiveControlDeskController`) operates institution-wide across all 8 branches with live date-based tracking, day-order resolution, and period substitution monitoring.
- **Dedicated Print Pipeline:** All print documents (`hod_workload_report_print.blade.php`, `hod_consolidated_timetable_print.blade.php`, and dynamic browser popups) use dedicated A4 print styles (`@page { size: A4 landscape/portrait; margin: 0.5cm; }`).

---

## 2. Complete Architecture Map & Subsystem Inventory

```mermaid
flowchart TD
    subgraph HOD Workspace
        A["HOD Dashboard (#panelReport_centre)"] -->|Card 3: Workload & Timetables| B["/hod/report-centre/workload-panel (hod_workload_panel.blade.php)"]
        
        B -->|Card 1: Print Workload| C["GET /hod/workload-report/print"]
        C -->|Scans {branch}_*.json + DB| D["hod_workload_report_print.blade.php (A4 Portrait)"]
        
        B -->|Card 2: Print Batch Timetable| E["JS: printSingleTimetable()"]
        E -->|API 1: GET /api/hod/batches/{id}/subjects?semester={sem}| F["DataController::getBatchSubjects"]
        E -->|API 2: GET /api/hod/batches/{id}/timetable| G["storage/app/timetables/{id}.json"]
        F & G -->|JS: triggerPrintTimetableWindow()| H["window.open() Dynamic A4 Landscape Popup"]
        
        B -->|Card 3: Consolidated Sheet| I["GET /hod/consolidated-timetable/print?batches[]=..."]
        I -->|Reads selected JSONs + DB| J["hod_consolidated_timetable_print.blade.php (A4 Landscape)"]
    end

    subgraph Principal & Executive Subsystem
        K["Principal Desk (/dashboard/principal)"] --> L["/dashboard/principal/today-timetable (principal_today_timetable.blade.php)"]
        L -->|API: GET /api/principal/today-timetable| M["PrincipalDashboardController::getTodayTimetableData"]
        M -->|Reads all dept JSONs + attendance logs| N["Live Institution-Wide Daily Matrix"]
        
        O["Executive Control Desk"] -->|API: GET /api/admin/timetables/all-departments| P["ExecutiveControlDeskController::getAllDepartmentTimetables"]
        P -->|Reads all dept JSONs + batch_subjects| Q["Cross-Department Timetable Matrix"]
    end

    subgraph Course File & Practicum Subsystem
        R["Lecturer / Virtual Classroom"] --> S["/r26/classroom/practicum/{id}/print-timetable"]
        S --> T["R26VirtualClassroomPracticumController::printClassroomTimetable"]
        T --> U["r26_practicum/timetable_print.blade.php (A4 Landscape)"]
    end
```

---

## 3. Cross-Role Implementation Comparison

| Capability | HOD Workload Desk | Principal / Executive Desk | Lecturer / Practicum | Difference & Analysis | Recommended Source for HOD Migration |
|:---|:---|:---|:---|:---|:---|
| **Role & Scope** | Department Only (`session('userBranch')`) | Institution-Wide (All 8 Departments) | Single Subject / Batch | HOD is scoped to branch; Principal sees institutional cross-department grid. | **Keep HOD branch scope intact.** |
| **Primary Focus** | Weekly semester schedules, faculty engaged hours, clash audit | Daily live tracking per date, substitutions, faculty presence | Course File Item 1 & Item 2 compliance | HOD manages semester layout; Principal monitors daily execution. | **Preserve HOD semester-based workflows.** |
| **Shell Archetype** | Standalone legacy dark view (`bg-slate-950`) | Full App Shell (`<x-layouts.app-shell>`) + Date Picker | Specialized print view | Principal uses modern CampusLynk shell; HOD workload desk is legacy. | **Adopt `<x-layouts.app-shell>` for HOD desk.** |
| **Workload Calculation** | PHP real-time scan of `{branch}_*.json` | Period attendance logs & substitution tracking | Assigned weekly contact hours | HOD calculates official commencement workload; Principal tracks live hours. | **Preserve HOD formula verbatim.** |
| **Single Timetable Loading** | Client-side `fetch()` of subjects + timetable | Server-side aggregation with cached state | Server-side JSON load | HOD generates dynamic client popup; Practicum renders backend Blade. | **Preserve HOD dynamic print pipeline.** |
| **Consolidated Timetable** | 2–3 batches side-by-side per period | Full institution matrix (all classrooms per day) | N/A (Not available) | HOD provides clash audit for 2–3 department classes; Principal audits all 8 branches. | **Preserve HOD 2–3 batch clash audit.** |
| **Parallel Labs Support** | Basic consecutive slot merging | Supports `is_parallel` & `parallel_labs` array | Standard 2/3-hour practicum slot | Principal parser handles parallel split batches; HOD handles consecutive period merging. | **Preserve existing HOD merging.** |
| **Timetable Persistence** | `POST /api/hod/batches/{id}/timetable` | Read-only matrix view | Read-only course file view | Only HOD has timetable authoring REST endpoint. | **Preserve HOD save/load APIs.** |

---

## 4. Route & API Inventory

| HTTP Method | Route URI | Controller / Handler | Auth & Role | Request Parameters | Response Structure | Consumer |
|:---|:---|:---|:---|:---|:---|:---|
| `GET` | `/hod/report-centre/workload-panel` | Closure (`routes/web.php:790`) | HOD, Principal | None | HTML View (`hod_workload_panel.blade.php`) with `$department`, `$batches` | HOD Report Centre Card 3 |
| `GET` | `/hod/workload-report/print` | Closure (`routes/web.php:1162`) | HOD, Principal | None | HTML View (`hod_workload_report_print.blade.php`) | Card 1 Print Action |
| `GET` | `/hod/consolidated-timetable/print` | Closure (`routes/web.php:805`) | HOD, Principal | `batches[]` (array of classroom IDs) | HTML View (`hod_consolidated_timetable_print.blade.php`) | Card 3 Form Submit |
| `GET` | `/api/hod/batches/{classroomId}/subjects` | `DataController@getBatchSubjects` | HOD, Principal | `semester` (query string, optional) | JSON: `{ status: "SUCCESS", subjects: [...] }` | Card 2 JS `printSingleTimetable()` |
| `GET` | `/api/hod/batches/{classroomId}/timetable` | Closure (`routes/web.php:1279`) | HOD, Principal | `classroomId` (route param) | JSON: `{ status: "SUCCESS", timetable: {...} }` | Card 2 JS `printSingleTimetable()` |
| `POST` | `/api/hod/batches/{classroomId}/timetable` | Closure (`routes/web.php:1292`) | HOD, Principal | `classroomId` (route param), JSON body | JSON: `{ status: "SUCCESS", message: "..." }` | Timetable Builder / Editor |
| `GET` | `/api/principal/today-timetable` | `PrincipalDashboardController@getTodayTimetableData` | Principal, Admin, HOD | `date` (query string) | JSON: `{ success: true, activeDayOrder: "...", timetable: [...] }` | Principal Desk |
| `GET` | `/api/admin/timetables/all-departments` | `ExecutiveControlDeskController@getAllDepartmentTimetables` | Principal, SuperAdmin, HOD | `department`, `day` | JSON: `{ status: "SUCCESS", timetables: [...] }` | Executive Desk |

---

## 5. Complete JavaScript Inventory

| Function | Purpose | Trigger / Caller | DOM IDs / Classes Used | API / Route Dependencies | Side Effects |
|:---|:---|:---|:---|:---|:---|
| `updateSelectionStatus()` | Updates the selection counter text display (e.g. "2 of 3 batches selected"). | Triggered on `.batch-checkbox` `change` event. | `#selectionStatus`, `.batch-checkbox` | None | Mutates `#selectionStatus.innerText`. |
| `validateConsolidatedForm(e)` | Enforces minimum selection of 2 batches before submitting consolidated form. | Form `onsubmit` on `#consolidatedForm`. | `.batch-checkbox` | None | Shows `alert()` and calls `e.preventDefault()` if checked count < 2. |
| `printSingleTimetable()` | Reads selected batch and semester, fetches subjects and timetable JSON in parallel, and dispatches print window. | Button `onclick` on Card 2. | `#singleBatchSelect`, `#singleSemSelect` | `GET /api/hod/batches/{id}/subjects?semester={sem}`<br>`GET /api/hod/batches/{id}/timetable` | Calls `triggerPrintTimetableWindow(...)`. |
| `triggerPrintTimetableWindow(classroomId, sem, allocatedSubjects, timetableData)` | Constructs dynamic A4 landscape timetable HTML, merges lab slots, builds legend, and triggers browser print window. | Called by `printSingleTimetable()` upon Promise resolution. | Dynamic popup document DOM | None | Opens new browser popup window via `window.open('', '_blank')` and calls `document.write()`. |
| `.batch-checkbox` Change Listener | Enforces maximum limit of 3 batches. | Checkbox `change` event. | `.batch-checkbox` | None | Unchecks checkbox and alerts if `checkedCount > 3`. |

---

## 6. DOM Preservation Baseline & Contract

| DOM Identifier | Element Type | Category | Purpose in Subsystem | Read / Write | JS Dependencies |
|:---|:---|:---|:---|:---:|:---|
| `#singleBatchSelect` | `<select>` | Batch Selection | Selects the active classroom ID for single timetable print. | Read | `printSingleTimetable()` |
| `#singleSemSelect` | `<select>` | Semester Selection | Selects semester (1 to 6) to filter subjects and determine term. | Read | `printSingleTimetable()` |
| `#consolidatedForm` | `<form>` | Consolidated Timetable | Form container targeting `/hod/consolidated-timetable/print` with `target="_blank"`. | Read / Submit | `validateConsolidatedForm(e)` |
| `.batch-checkbox` | `<input type="checkbox">` | Consolidated Timetable | Checkbox array with `name="batches[]"` for multi-batch selection. | Read / Write | `.batch-checkbox` listener, `updateSelectionStatus()`, `validateConsolidatedForm()` |
| `#selectionStatus` | `<span>` | Status Indicator | Dynamic counter displaying "X of 3 batches selected". | Write | `updateSelectionStatus()` |

---

## 7. Data Flow & Data Source Forensics

```
┌─────────────────────────────────────────────────────────────────────────────┐
│ 1. MySQL: class_management                                                  │
│    Fields: classroom_id, branch, batch_year, current_semester               │
│    Purpose: Feeds #singleBatchSelect, #consolidatedForm checkboxes, batches  │
└──────────────────────────────────────┬──────────────────────────────────────┘
                                       │
┌──────────────────────────────────────▼──────────────────────────────────────┐
│ 2. Disk Storage: storage/app/timetables/{classroom_id}.json                 │
│    Purpose: Persists 5-day x 6-period slot schedule (Day 1..Day 5, Slots 1..6)│
└──────────────────────────────────────┬──────────────────────────────────────┘
                                       │
┌──────────────────────────────────────▼──────────────────────────────────────┐
│ 3. MySQL: batch_subjects & subject_staff_assignments                         │
│    Fields: subject_code, subject_name, subject_type, semester, staff_mobile │
│    Purpose: Resolves course names, course types (Theory/Lab), and faculty   │
└──────────────────────────────────────┬──────────────────────────────────────┘
                                       │
┌──────────────────────────────────────▼──────────────────────────────────────┐
│ 4. MySQL: staff_profiles                                                    │
│    Fields: mobile_no, name, designation, branch                             │
│    Purpose: Faculty roster for Workload Commencement Report and legend keys │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 8. Timetable JSON / Storage Schema

- **Path:** `storage/app/timetables/{classroom_id}.json`
- **Naming Pattern:** `{BranchCode}_{AdmYear}_{GradYear}.json` (e.g. `CT_2024_2027.json`)
- **Read Method:** `file_get_contents($path)` + `json_decode(..., true)`
- **Write Method:** `file_put_contents($path, json_encode($request->all(), JSON_PRETTY_PRINT))`

### Authoritative JSON Schema (Placeholder Example):
```json
{
  "Day 1": {
    "1": { "subject": "2001", "staff": "Prof. Sample Faculty A" },
    "2": { "subject": "2001", "staff": "Prof. Sample Faculty A" },
    "3": { "subject": "2002", "staff": "Prof. Sample Faculty B" },
    "4": { "subject": "2003", "staff": "Prof. Sample Faculty C" },
    "5": { "subject": "2008", "staff": "Prof. Sample Faculty D" },
    "6": { "subject": "2008", "staff": "Prof. Sample Faculty D" }
  },
  "Day 2": {
    "1": { "subject": "2004", "staff": "Prof. Sample Faculty E" },
    "2": { "subject": "2005", "staff": "Prof. Sample Faculty F" },
    "3": { "subject": "2006", "staff": "Prof. Sample Faculty G" },
    "4": { "subject": "2007", "staff": "Prof. Sample Faculty H" },
    "5": { "subject": "2007", "staff": "Prof. Sample Faculty H" },
    "6": { "subject": "2007", "staff": "Prof. Sample Faculty H" }
  },
  "Day 3": { ... },
  "Day 4": { ... },
  "Day 5": { ... }
}
```

### Revision Compatibility:
- **Revision 2021 (R2021):** Standard 4-digit subject codes (`2001`, `2002`). Lab courses typically span 3 periods in the afternoon.
- **Revision 2026 (R2026):** Standard course codes with Practicum courses (`PBL`, `Summer Internship`). Timetable slot format remains identical (`subject` + `staff`).

---

## 9. Timetable Business Rules & Constraints

| Rule / Constraint | Scope | Enforcement Level | Exact Mechanism |
|:---|:---:|:---:|:---|
| **Maximum Batches for Consolidated Sheet** | Consolidated Timetable | **ENFORCED** | JS listener rejects 4th checkbox selection and alerts user. |
| **Minimum Batches for Consolidated Sheet** | Consolidated Timetable | **ENFORCED** | `validateConsolidatedForm(e)` blocks submission if `< 2` batches selected. |
| **Day Order Definition** | Timetable Matrix | **ENFORCED** | Exactly 5 working days (`Day 1` through `Day 5`). |
| **Period Count per Day** | Timetable Matrix | **ENFORCED** | Exactly 6 periods per day (3 Forenoon: 09:00–12:10, 3 Afternoon: 01:00–04:00). |
| **Lunch Break Placement** | Timetable Grid | **ENFORCED** | Fixed between Period 3 and Period 4 (12:10 PM – 01:00 PM). Spans all 5 days vertically. |
| **Lab Slot Merging** | Print Views | **ENFORCED** | Consecutive periods with identical subject codes merge with `colspan="2"` or `colspan="3"`. |
| **Department Isolation** | Workload Calculation | **ENFORCED** | Workload report only processes JSON files where `stripos($classroomId, $branchCode . "_") === 0`. |
| **Designation Filter for Workload** | Faculty Workload | **ENFORCED** | Restricted to `['Lecturer', 'Demonstrator', 'Physical_Instructor', 'Physical Instructor', 'HOD']`. |
| **Period Conflict / Clash Detection** | Consolidated Timetable | **DISPLAYED ONLY** | Visual side-by-side comparison allows HOD to spot simultaneous faculty bookings. |

---

## 10. Print Pipeline & Document Forensics

### 1. Faculty Workload Commencement Report (`/hod/workload-report/print`):
- **Template:** `resources/views/hod_workload_report_print.blade.php`
- **Orientation:** **A4 Portrait** (`@page { size: A4 portrait; margin: 1cm; }`)
- **Typography:** Arial, `12px` tabular font size, high-contrast black on white.
- **Table Columns:** `Sl. No.`, `Faculty Name`, `Designation`, `Theory Hours / Week`, `Lab Hours / Week`, `Total Load (Hrs)`.
- **Signatures:** Dual signature blocks for **Head of Department** and **Principal**.

### 2. Consolidated Department Timetable (`/hod/consolidated-timetable/print`):
- **Template:** `resources/views/hod_consolidated_timetable_print.blade.php`
- **Orientation:** **A4 Landscape** (`@page { size: A4 landscape; margin: 0.5cm; }`)
- **Row Structure:** Day column has `rowspan="{{ count($batches) }}"`; each batch is rendered on a separate row per day.
- **Lunch Column:** Spans all days and batches vertically (`rowspan="{{ 5 * count($batches) }}"`).
- **Table Borders:** `2px solid #000000` with high-contrast print CSS.

### 3. Individual Batch Timetable (Dynamic Client Popup):
- **Generator:** `triggerPrintTimetableWindow()`
- **Orientation:** **A4 Landscape** (`@page { size: A4 landscape; margin: 0.5cm; }`)
- **Structure:** 5 rows (Day 1–5), 6 period columns + vertical lunch break column.
- **Legend Box:** Formatted table of scheduled subject codes, full course names, and assigned staff names.

---

## 11. UI Forensic Audit & Quantified Violations

| Violation Category | Quantified Count | Observation in `hod_workload_panel.blade.php` |
|:---|:---:|:---|
| **Dark Legacy Background** | Entire View | Uses `bg-slate-950 text-slate-300` and `card-gradient` (`linear-gradient(135deg, rgba(30, 41, 59, 0.4)...)`). |
| **CDN Scripts & Fonts** | 3 instances | Runtime CDN `@tailwindcss/browser@4`, Google Fonts `Inter`, `Material Symbols Rounded`. |
| **Micro-Fonts (<12px)** | 4 instances | Uses `text-[10px]` and `text-[11px]` font sizes for labels. |
| **Raw Select Dropdowns** | 2 elements | `<select id="singleBatchSelect">` and `<select id="singleSemSelect">` with custom dark styles. |
| **Raw Checkboxes** | Dynamic loop | Checkboxes styled with `accent-emerald-500` and dark container backgrounds. |
| **Isolated Header** | 1 instance | Custom top bar with raw back button rather than CampusLynk App Shell. |

---

## 12. CampusLynk Design System Migration Mapping

| Existing Legacy Element | Current Usage | CampusLynk Target Component / Style |
|:---|:---|:---|
| Standalone `<html>` / `<body>` | Standalone dark page | `<x-layouts.app-shell title="CampusLynk - Faculty Workload & Timetable Desk" :topbarTitle="'Workload & Timetables'" :activeNav="'report_centre'">` |
| Top Bar / Back Button | Custom header | Standard CampusLynk Topbar with breadcrumb: `Report Centre > Workload & Timetables` |
| Card 1: Faculty Workload | Dark gradient card | White card surface (`bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs`) with Lucide `briefcase` icon |
| Card 2: Batch Timetable | Dark gradient card | White card surface (`bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs`) with Lucide `calendar` icon |
| Card 3: Consolidated Sheet | Dark gradient card | White card surface (`bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs`) with Lucide `layout-grid` icon |
| Select Dropdowns | Raw dark `<select>` | Styled `<x-ui.select>` with `border-slate-200 rounded-xl px-3.5 py-2.5 text-sm` |
| Batch Checkbox Grid | Dark row containers | Interactive white selection cards (`bg-slate-50/50 border border-slate-200 hover:border-blue-400 rounded-xl p-3.5`) |
| Action Buttons | Raw gradient buttons | CampusLynk primary blue button (`bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium text-sm`) |

---

## 13. HOD vs Principal Data-Scope Differences

| Aspect | HOD Scope | Principal / Executive Scope |
|:---|:---|:---|
| **Department Visibility** | Strictly restricted to `session('userBranch')` | Access to all 8 branches (EL, ME, CE, EEE, CT, AU, GEN_AIDED, GEN_SF) |
| **Batch Visibility** | Batches where `branch == userBranch` | All active institutional classrooms across all semesters |
| **Faculty Visibility** | Department faculty in `staff_profiles` where `branch == userBranch` | All institutional faculty & demonstrators |
| **Workload Perspective** | Weekly theory + lab hours for semester commencement sign-off | Real-time period substitution, faculty attendance, and daily coverage tracking |
| **Authoring Permissions** | Can save/edit timetable JSON (`POST /api/hod/batches/{id}/timetable`) | Audit & monitoring permissions |

---

## 14. Duplication & Technical Debt Analysis

| Area | Duplication / Redundancy | Classification | Recommendation |
|:---|:---|:---:|:---|
| **Department Name Translation** | Hardcoded array `$deptNames` repeated across 3 print templates | **SAFE TO SHARE** | Use global `getFullBranchName($code)` helper. |
| **Single Timetable Renderer** | Dynamic JS in `hod_workload_panel` vs Blade in `r26_practicum/timetable_print` | **MUST REMAIN ROLE-SPECIFIC** | Keep HOD dynamic popup generator intact to avoid breaking HOD print workflows. |
| **Consolidated Timetable Grid** | HOD 2–3 batch comparison vs Principal all-department matrix | **MUST REMAIN ROLE-SPECIFIC** | HOD clash audit is optimized for semester planning; Principal is optimized for daily operations. |
| **Workload Computation** | Workload logic in `routes/web.php:1162` | **POSSIBLE TO SHARE** | Leave backend calculation in route closure intact; zero risk to existing reporting. |

---

## 15. Migration Risk Assessment

| Risk Domain | Risk Level | Rationale | Mitigation Strategy |
|:---|:---:|:---|:---|
| **Timetable JSON Compatibility** | **HIGH** | If file paths or JSON structure are altered, all student schedules, mentor dossiers, and principal tracking break. | **Zero changes to `storage/app/timetables/` paths or JSON schemas.** |
| **Print Output Degradation** | **HIGH** | Modifying print CSS could cause table overflow on A4 landscape/portrait pages. | **Zero changes to existing print templates.** |
| **DOM ID Breakage** | **MEDIUM** | Renaming select or checkbox IDs would break `printSingleTimetable()` and `validateConsolidatedForm()`. | **Strict preservation contract on all 5 critical DOM IDs.** |
| **UI Modernization** | **LOW** | Converting `hod_workload_panel.blade.php` to `<x-layouts.app-shell>` is clean and well-isolated. | **Follow standard CampusLynk design tokens and components.** |

---

## 16. Recommended Migration Strategy (Phase 2C.5B Roadmap)

When authorized to execute Phase 2C.5B, follow this exact 7-phase sequence:

- **Phase A — Shell & Layout Modernization:** Wrap `hod_workload_panel.blade.php` in `<x-layouts.app-shell>` with `:activeNav="'report_centre'"`, `#FAFAFB` canvas, and Poppins typography.
- **Phase B — Faculty Workload Card UI:** Modernize Card 1 (Department Faculty Workload) with white card surface, Lucide `briefcase` icon, and direct print trigger.
- **Phase C — Individual Batch Timetable Card UI:** Modernize Card 2 with clean 14px selectors (`#singleBatchSelect`, `#singleSemSelect`) and print trigger.
- **Phase D — Consolidated Timetable Form UI:** Modernize Card 3 with interactive batch selection cards (`.batch-checkbox`), selection counter (`#selectionStatus`), and clash audit form submission.
- **Phase E — JavaScript & Function Validation:** Verify `printSingleTimetable()`, `triggerPrintTimetableWindow()`, `validateConsolidatedForm()`, and `updateSelectionStatus()`.
- **Phase F — Print Pipeline Verification:** Verify all 3 print outputs (A4 Portrait Workload, A4 Landscape Individual, A4 Landscape Consolidated).
- **Phase G — Build & Regression Testing:** Run `npm.cmd run build` and clear Laravel view/route/config caches.

---

## 17. Deferred / Untouched Areas

The following components MUST remain untouched during UI migration:
1. `storage/app/timetables/` directory and all JSON file reading/writing operations.
2. Workload calculation formulas in `routes/web.php:1162`.
3. Dedicated A4 print templates: `hod_workload_report_print.blade.php` and `hod_consolidated_timetable_print.blade.php`.
4. Principal/Executive desks: `principal_today_timetable.blade.php` and `ExecutiveControlDeskController.php`.
5. Database schema and tables (`class_management`, `batch_subjects`, `staff_profiles`, `subject_staff_assignments`).

---

## 18. Final Preservation Contract

### Critical DOM IDs:
- `#singleBatchSelect`
- `#singleSemSelect`
- `#consolidatedForm`
- `#selectionStatus`
- `.batch-checkbox`

### Critical JavaScript Functions:
- `printSingleTimetable()`
- `triggerPrintTimetableWindow(classroomId, sem, allocatedSubjects, timetableData)`
- `validateConsolidatedForm(e)`
- `updateSelectionStatus()`

### Critical Endpoints & Parameters:
- `GET /hod/report-centre/workload-panel`
- `GET /hod/workload-report/print`
- `GET /hod/consolidated-timetable/print?batches[]={id}`
- `GET /api/hod/batches/{classroomId}/subjects?semester={sem}`
- `GET /api/hod/batches/{classroomId}/timetable`

---

## 19. Audit Verdict

```
============================================================
AUDIT VERDICT
============================================================

1. SUB-SYSTEM MIGRATION READINESS:
   -> The HOD Workload & Timetable subsystem is FULLY AUDITED and READY for UI migration.

2. EXECUTION PRIORITY:
   -> Phase 2C.5B: Modernize `resources/views/hod_workload_panel.blade.php` into the CampusLynk App Shell.

3. ARCHITECTURAL BOUNDARY:
   -> The Workload Desk should remain a DEDICATED SUB-DESK at `/hod/report-centre/workload-panel`
      linked from `#panelReport_centre` Card 3, rather than being squeezed into a single dashboard panel.

4. PRESERVATION MANDATE:
   -> 100% preserve timetable JSON storage, backend workload math, and dedicated A4 print views.

5. CROSS-ROLE SHARING:
   -> HOD and Principal implementations MUST retain role-specific scopes (HOD = branch semester planning;
      Principal = institutional daily tracking). They should share visual design tokens, but NOT business logic.
============================================================
```
