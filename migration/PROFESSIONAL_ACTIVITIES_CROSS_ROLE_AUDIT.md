# CampusLynk — Professional Activities Cross-Role Forensic Audit & Migration Preparation

**Target Feature:** Faculty Academic & Professional Activities  
**Execution Type:** Read-Only Cross-Role Forensic Audit  
**Date:** August 20, 2026  
**Audited Implementations:**  
1. **Principal / Control Desk:** `resources/views/admin_control_desk.blade.php` (`#panelProf_activities`)  
2. **Faculty / Lecturer Standalone:** `resources/views/lecturer_professional_activities.blade.php` (`/staff/professional-activities`)  
3. **Department Head Audit Integration:** `resources/views/hod_sbte_audit.blade.php` & `routes/web.php`  
**Target Migration View:** `resources/views/hod_dashboard.blade.php` (`#panelProf_activities`)  

---

## 1. Executive Summary & Located Implementations

| Implementation | Location / File | Route / Access URL | State / Architecture | Primary Role Focus |
|:---|:---|:---|:---|:---|
| **Principal / Admin** | `resources/views/admin_control_desk.blade.php` (lines 874–1006, 2636–2740) | `/dashboard/principal` / `/dashboard/admin` (Panel `#panelProf_activities`) | Integrated Asynchronous Panel (5/7 Split Layout, Metric Cards, Feed) | Institution-wide monitoring across all 8 branches + self-recording |
| **Faculty / Standalone** | `resources/views/lecturer_professional_activities.blade.php` | `GET /staff/professional-activities` | Standalone Dark Blade View (`#020617`, Material Symbols, Form POST) | Personal staff portfolio recording with 8 dynamic activity type schemas |
| **HOD Sidebar Nav** | `config/navigation/hod.php` (line 72) | Currently points to `url => '/staff/professional-activities'` | External navigation away from HOD single-workspace console | Needs integration into `hod_dashboard.blade.php` |
| **SBTE Dept Audit** | `resources/views/hod_sbte_audit.blade.php` | `GET /hod/sbte-audit` | Structured Audit Form aggregating staff activities via backend API | Department-level accreditation and compliance reporting |

---

## 2. Forensic Architecture Comparison

```
┌──────────────────────────────────────────────────────────────────────────────────────────┐
│                         PROFESSIONAL ACTIVITIES ARCHITECTURE MATRIX                      │
├──────────────────────────────┬──────────────────────────────┬────────────────────────────┤
│ Architectural Aspect         │ Principal Control Desk       │ Standalone / Lecturer View │
├──────────────────────────────┼──────────────────────────────┼────────────────────────────┤
│ View Archetype               │ Integrated Async Panel       │ Standalone Dark View       │
│ Layout Structure             │ 5/7 Split Grid:              │ 5/7 Split Grid:            │
│                              │ Left: Entry Form             │ Left: Dynamic Schema Form  │
│                              │ Right: 3 Metric Cards + Feed │ Right: Activity Card Feed  │
│ Data Loading                 │ Asynchronous JSON via        │ Server-rendered Blade with │
│                              │ GET /api/.../fetch           │ GET request URL params     │
│ Dynamic Activity Schemas     │ Fixed single form template   │ 8 Dynamic JSON Schemas     │
│ Activity Type Coverage       │ 8 Categories                 │ 8 Categories               │
│ Scope / Filtering            │ All Departments & AY Filter  │ Personal Mobile No & AY    │
│ Iconography                  │ Material Symbols (CDN)       │ Material Symbols (CDN)     │
│ Visual Standard              │ Partial Tailwind white cards │ Dark Slate-950 container   │
└──────────────────────────────┴──────────────────────────────┴────────────────────────────┘
```

---

## 3. Detailed Side-by-Side Functional Inventory

### 3.1 DOM & Visual Elements

| Component / DOM ID | Principal Implementation (`admin_control_desk.blade.php`) | Lecturer / Standalone Implementation (`lecturer_professional_activities.blade.php`) | Target HOD Implementation |
|:---|:---|:---|:---|
| **Panel Container** | `#panelProf_activities` | `div.max-w-7xl` | `#panelProf_activities` |
| **Academic Year Filter** | `#profActAyFilter` (`<select>`) | `select[name="academic_year"]` | `#profActAyFilter` |
| **Department Filter** | `#profActDeptFilter` (`<select>` across all depts) | None (Locked to user session) | `#profActDeptFilter` (Pre-selected to HOD's branch) |
| **Activity Entry Form** | `#profActivityForm` (`<form>`) | `<form action="/staff/professional-activities/save">` | `#profActivityForm` |
| **Category Switcher** | `#profActType` (`<select>`) | `#activityTypeSelector` (`<select>`) | `#profActType` with dynamic schema render |
| **Dynamic Fields Box** | None (Static form fields) | `#dynamicFieldsContainer` | `#profActDynamicFields` |
| **Metric Card 1** | `#profActTotalCount` (Total Recorded) | Counter in list header | `#profActTotalCount` |
| **Metric Card 2** | `#profActFdpCount` (FDPs & Workshops) | None | `#profActFdpCount` |
| **Metric Card 3** | `#profActPubCount` (Publications) | None | `#profActPubCount` |
| **Registry Header** | `#profActRegistryCount` | Total items badge | `#profActRegistryCount` |
| **Activity Feed** | `#profActListContainer` | Activity cards loop | `#profActListContainer` |
| **Alert Box** | `#profActAlert` | `@if(session('success'))` | `#profActAlert` |

---

### 3.2 Supported Activity Categories (8 Activity Types)

Both implementations operate on the exact same 8 standardized academic activity types in `staff_professional_activities`:

1. `fdp_attended`: Faculty Development Program (FDP) / Training
2. `workshop_attended`: Technical Workshop / Hands-on BootCamp
3. `course_attended`: Online Certification / MOOC / NPTEL Course
4. `gap_in_syllabus`: Curriculum Gap Identified & Bridge Course Plan
5. `project_guided`: Student Major / Minor Project Guided
6. `seminar_guided`: Student Technical Seminar Guided
7. `publication`: Journal / Conference Research Publication
8. `book_published`: Authored Book / Book Chapter (with ISBN)

---

## 4. Backend API & Route Audit

### 4.1 Endpoint Inventory

| HTTP Method | Route URI | Source Handler / Controller | Request Payload | Response Structure | Reusability for HOD |
|:---|:---|:---|:---|:---|:---:|
| `GET` | `/api/staff/professional-activities/fetch` | Closure in `routes/web.php:1903` | `?academic_year=...&department=...&activity_type=...` | `{ status: 'SUCCESS', academic_year: '...', records: [...] }` | ✅ **100% REUSABLE** |
| `POST` | `/staff/professional-activities/save` | Closure in `routes/web.php:1723` | JSON / Form: `{ academic_year, activity_type, details: {...} }` | Flash session or JSON `{ status: 'SUCCESS' }` | ✅ **100% REUSABLE** |
| `POST` | `/staff/professional-activities/delete/{id}` | Closure in `routes/web.php:1745` | URL parameter `{id}` | Flash session or JSON | ✅ **100% REUSABLE** |
| `GET` | `/api/hod/sbte-audit/fetch-staff-activities` | Closure in `routes/web.php:1757` | `?academic_year=...` (Scoped to HOD session branch) | `{ status: 'SUCCESS', activities: {...} }` | ✅ **REUSABLE FOR AUDIT** |

### 4.2 Backend Data Schema (`staff_professional_activities`)
```sql
CREATE TABLE `staff_professional_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lecturer_mobile_no` varchar(255) NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `details` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `staff_professional_activities_lecturer_mobile_no_foreign` (`lecturer_mobile_no`),
  CONSTRAINT `staff_professional_activities_lecturer_mobile_no_foreign` 
    FOREIGN KEY (`lecturer_mobile_no`) REFERENCES `staff_profiles` (`mobile_no`) ON DELETE CASCADE
);
```

---

## 5. Functional Difference Analysis (Principal vs HOD)

| Dimension | Principal Behavior | HOD Behavior | Justification & Architectural Rule |
|:---|:---|:---|:---|
| **Department Scope** | Defaults to **All Departments**, with dropdown to filter any of the 8 branches. | Defaults to **HOD's Department Branch** (`$activeBranch`), allowing HOD to inspect all faculty in their department. | HOD is responsible for department accreditation, NBA SAR Criterion 5 (Faculty Contributions), and SBTE audits. |
| **Activity Ownership** | Can view all faculty entries; recording sets `lecturer_mobile_no` to Principal's ID. | Views all activities of faculty in their branch; recording sets `lecturer_mobile_no` to HOD's ID. | Enforces strict database integrity and foreign key constraints without modifying database tables. |
| **Delete Permission** | Can delete records owned by their session. | Can delete records owned by their session. | Prevents accidental deletion of another faculty member's certified publication. |
| **View Context** | Multi-department executive bird's-eye view. | Department-focused faculty accomplishment tracker + self-recording console. | Avoids visual clutter and aligns with HOD operational scope. |

---

## 6. Principal UI Design Assessment & Design System Compliance

### 6.1 Strengths to Adopt as Visual Reference
* **Split 5/7 Layout Architecture**: Form on left (5 cols) and KPI metrics + Feed on right (7 cols) creates an efficient, responsive workspace.
* **Top Metric Cards**: 3 high-level counters (`Total Recorded`, `FDPs & Workshops`, `Publications`) provide immediate analytical visibility.
* **Feed Card Items**: Structured item cards displaying category badges, department badges, staff names, duration, and key learnings.

### 6.2 Legacy / Non-Compliant Patterns in Principal UI to Eliminate
1. **Material Symbols CDN**: Principal UI uses `<span class="material-symbols-rounded">school</span>` and `add_circle`.  
   $\rightarrow$ **MANDATORY FIX:** Replace 100% with pure **Lucide vector icons** (`award`, `book-open`, `file-text`, `plus-circle`, `calendar`, `trash-2`, `refresh-cw`).
2. **Fixed Form Fields**: Principal UI uses fixed static inputs (Organizer, Duration, Start Date, Desc) which do not suit complex types like Book Publications (ISBN, Publisher) or Syllabus Gaps (Curriculum Gap, Action Taken).  
   $\rightarrow$ **MANDATORY FIX:** Integrate the 8 rich dynamic JSON schemas from `lecturer_professional_activities.blade.php` into the clean CampusLynk white form container.
3. **Typography Standard**: Some sub-labels in Principal UI use `text-xs` ($\le 12\text{px}$) without high contrast.  
   $\rightarrow$ **MANDATORY FIX:** Strict $\ge 14\text{px}$ (`text-sm`) standard for all form inputs, labels, and table items, and $\ge 12\text{px}$ for secondary badges. Zero font shadows or glow.

---

## 7. Component Mapping

| Current UI Element | Target CampusLynk Component | Styling & Token Specification |
|:---|:---|:---|
| **Header Banner** | `<x-ui.card>` / Topbar | `#FFFFFF` card, `border-slate-200/80`, `shadow-xs`, `#FAFAFB` backdrop |
| **Academic Year & Dept Selects** | `<x-ui.select>` | `bg-white border-slate-200 text-slate-900 rounded-xl px-3.5 py-2 text-sm` |
| **Metric Summary Cards** | `Card.Metric.v1` | `bg-white border-slate-200/80 p-4 rounded-2xl text-center shadow-xs` |
| **Activity Form Inputs** | `<x-ui.input>` & `<x-ui.select>` | `bg-white border-slate-200 text-slate-900 rounded-xl text-sm focus:border-blue-600` |
| **Save Action Button** | `<x-ui.button variant="primary">` | `bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-xs` |
| **Category Badges** | `<x-ui.badge>` | Semantic color pills (`FDP` in indigo, `Publication` in emerald, `Gap` in amber) |
| **Delete Action Button** | `<button>` (Destructive) | `p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition` |
| **Feedback Alerts** | `<x-ui.alert>` | `bg-emerald-50 text-emerald-800 border-emerald-200 rounded-xl p-3 text-sm` |

---

## 8. HOD Preservation Contract

During the upcoming UI migration of `#panelProf_activities`:

### Stable DOM IDs
- `panelProf_activities` (Master panel container)
- `profActAyFilter` (Academic Year selector)
- `profActDeptFilter` (Department filter)
- `profActType` (Activity category dropdown)
- `profActDynamicFields` (Dynamic schema container)
- `profActivityForm` (Entry form element)
- `profActAlert` (Validation feedback alert)
- `profActTotalCount` (Total records counter)
- `profActFdpCount` (FDPs counter)
- `profActPubCount` (Publications counter)
- `profActRegistryCount` (Feed summary text)
- `profActListContainer` (Feed list container)

### Stable JavaScript Functions
- `loadProfActivities()`
- `submitProfActivity(e)`
- `deleteProfActivity(id)`
- `toggleProfActFields(type)`
- `handleHodSidebarNav('prof_activities')`

---

## 9. Recommended Migration Strategy

### Selected Strategy: **OPTION A (Enhanced Visual Reference + Dynamic Schema Integration)**

1. **Adopt Principal Visual Layout**:
   - Embed `#panelProf_activities` directly into `resources/views/hod_dashboard.blade.php` before `#panelProfile`.
   - Implement the 5/7 responsive split grid (Form on left, 3 Metric Cards + Activity Feed on right).
2. **Incorporate Rich Dynamic Schemas**:
   - Enhance the form with client-side dynamic schema switching (`toggleProfActFields`) supporting all 8 academic activity types with exact JSON payloads.
3. **Department Pre-Scoping**:
   - Set `#profActDeptFilter` to pre-select the HOD's active branch (`{{ $activeBranch }}`), allowing HODs to immediately view all departmental staff activities while retaining full multi-department auditing if needed.
4. **Sidebar Synchronization**:
   - Update `config/navigation/hod.php` so `prof_activities` triggers `onclick => "handleHodSidebarNav('prof_activities')"` instead of navigating to an external page.

---

## 10. Final Boundary Classifications

### CAN REUSE DIRECTLY
* Backend APIs: `GET /api/staff/professional-activities/fetch`, `POST /staff/professional-activities/save`, `POST /staff/professional-activities/delete/{id}`.
* Data model: `staff_professional_activities` table structure and JSON `details` schema.
* Principal 5/7 responsive split layout blueprint.
* 8 activity categories and their metadata structures.

### MUST REMAIN HOD-SPECIFIC
* Pre-scoping of department filter to the HOD's branch (`{{ $activeBranch }}`).
* Department header badge: `{{ $activeBranch }} Department · Faculty Professional Activities`.
* HOD dashboard single-workspace routing via `handleHodSidebarNav('prof_activities')`.

### MUST NOT TOUCH
* `routes/web.php` (All required endpoints are already active and functional).
* Database tables and migrations (`staff_professional_activities`, `staff_profiles`).
* Controller classes (`StaffLeaveController`, `AdminController`, `ExecutiveControlDeskController`).
* Other HOD dashboard panels (`batches`, `directory`, `subjects`, `audit`, `sf_attendance`, `leave_ledger`, `profile`).

### RECOMMENDED NEXT IMPLEMENTATION
In the next phase, migrate `#panelProf_activities` into `resources/views/hod_dashboard.blade.php`, update `config/navigation/hod.php`, build Vite assets with `npm.cmd run build`, and verify the complete HOD professional activities workflow.
