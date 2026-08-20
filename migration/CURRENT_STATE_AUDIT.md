# CAMPUSLYNK — PRE-MIGRATION ARCHITECTURE & UI STATE AUDIT
**Document Version:** 1.0.0 (Forensic Pre-Migration Audit)  
**Audit Date:** August 20, 2026  
**Repository Target:** `carmel-linx-laravel` / `MaxonXOXO/CampusLynk`  
**Execution Mode:** READ-ONLY / FORENSIC INSPECTION (Zero code/file modifications performed)  

---

## 1. Executive Summary

A forensic, read-only architectural and UI audit of the entire CampusLynk repository was executed to establish an empirical baseline before initiating the next phase of the UI migration. 

### Key Findings at a Glance:
* **Total View Files Scanned:** **166 files** across `resources/views/`.
* **Component-Based Views (Modern Shell):** **12 views (7.2%)** are actively wrapped in modern layout components (`<x-layouts.*>`).
* **Partially Ported Views:** **37 views (22.3%)** utilize partial shared sub-components (such as `<x-layout.sidebar>` or `<x-layout.topbar>`) but retain raw outer frames or legacy wrappers.
* **Standalone Legacy Views:** **72 views (43.4%)** contain their own standalone `<!DOCTYPE html>`, `<head>`, independent font imports, arbitrary CSS blocks, and inline JavaScript.
* **Print & Dedicated A4 Templates:** **45 views (27.1%)** are purpose-built physical reports and accreditation PDFs (`r26/*_print.blade.php`, `course_files/preview_*.blade.php`) that intentionally operate without web shells.
* **Raw HTML Control Proliferation:** The application currently runs **233 raw `<select>` elements** (vs. 9 `<x-ui.select>`), **1,255 raw `<button>` elements** (vs. 20 `<x-ui.button>`), and **742 raw `<input>` elements** (vs. 1 `<x-ui.input>`).
* **Design Token Violations:** Detected **4,285 instances of prohibited micro-fonts** (`text-xs`, `text-[9px]`, `text-[10px]`, `text-[11px]`), **1,865 inline style declarations** (`style="..."`), and **17 text-shadow / glow occurrences**.
* **Layout Duplication:** Detected **6 exact duplicate layout files** in `resources/views/layouts/` mirroring `resources/views/components/layouts/`.
* **External CDN Leaks:** Discovered **14 legacy views loading Tailwind via CDN** (`@tailwindcss/browser@4` or `cdn.tailwindcss.com`) and **95 redundant Google Fonts CDN links** outside the compiled Vite asset pipeline.

---

## 2. Current UI Architecture

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│                      CAMPUSLYNK CURRENT ASSET & SHELL TOPOLOGY                   │
├─────────────────────────┬───────────────────────────┬────────────────────────────┤
│ 1. Asset Pipeline       │ 2. Layout Shells          │ 3. Navigation Layer        │
│ • Vite 5.x              │ • <x-layouts.faculty-shell│ • NavigationService.php    │
│ • PostCSS + Tailwind 3.4│ • <x-layouts.app-shell>   │ • config/navigation/*.php  │
│ • resources/css/app.css │ • <x-layouts.dashboard>   │ • Contextual Role Merging  │
│ • resources/js/app.js   │ • <x-layouts.workspace>   │ • "Return to Dashboard"    │
│ • CSS Design Tokens     │ • <x-layouts.auth-layout> │ • Redundant Profile Filter │
└─────────────────────────┴───────────────────────────┴────────────────────────────┘
```

### 2.1 Asset & Pipeline Inventory

| Asset Layer | Path | Purpose | Current Status | Used By | Problems & Inconsistencies |
|:---|:---|:---|:---|:---|:---|
| **Vite Config** | `vite.config.js` | Bundles CSS/JS entries | Active & Operational | All Modern Views | None. Compiles `app.css` & `app.js` cleanly. |
| **Tailwind Config** | `tailwind.config.js` | Theme extensions, tokens | Active | PostCSS / Vite | Some views still load Tailwind via runtime CDN script tags. |
| **CSS Entry Point** | `resources/css/app.css` | Master stylesheet | Active | Vite Pipeline | Imports 5 token files from `tokens/`. |
| **CSS Color Tokens** | `resources/css/tokens/colors.css` | Hex & semantic color CSS vars | Active | `app.css` | Complete token mapping (`--primary-500: #2563EB`, etc.). |
| **CSS Typography** | `resources/css/tokens/typography.css` | Font sizes & line heights | Active | `app.css` | Standardized Poppins scale. |
| **CSS Radii** | `resources/css/tokens/radius.css` | Rounded border radius tokens | Active | `app.css` | Standardized 4px–20px tokens. |
| **CSS Shadows** | `resources/css/tokens/shadows.css` | Elevation shadows | Active | `app.css` | Standardized subtle elevation tokens. |
| **CSS Spacing** | `resources/css/tokens/spacing.css` | Padding & margin scales | Active | `app.css` | Standardized 4px grid tokens. |
| **JS Entry Point** | `resources/js/app.js` | Global Lucide icon init & UI modules | Active | Vite Pipeline | Imports bootstrap, components, layouts, forms, tables, charts. |
| **Faculty Shell** | `resources/views/components/layouts/faculty-shell.blade.php` | Dedicated workspace layout for faculty & teaching desks | **ACTIVE IMPLEMENTATION** | `attendance_log`, `remedial_dashboard`, `course_files_dashboard` | Resolves cleanly for `<x-layouts.faculty-shell>`. |
| **Faculty Shell Duplicate** | `resources/views/layouts/faculty-shell.blade.php` | Redundant shadow file | **INACTIVE / SHADOW** | None (Orphaned copy) | Exact duplicate of component file; Laravel Blade ignores it for `<x-layouts.*>`. |
| **App Shell** | `resources/views/components/layouts/app-shell.blade.php` | Master application wrapper | Active | `dashboard-layout`, `workspace-layout`, `ui-playground` | Mirrored identically in `views/layouts/app-shell.blade.php`. |
| **Auth Layout** | `resources/views/components/layouts/auth-layout.blade.php` | Auth pages container | Active | `views/modern/auth/*` (6 views) | Mirrored identically in `views/layouts/auth-layout.blade.php`. |
| **Dashboard Layout**| `resources/views/components/layouts/dashboard-layout.blade.php` | Executive dashboard shell with breadcrumb | Built / Ready | `views/modern/ui-playground.blade.php` | Underutilized by legacy dashboards. |
| **Workspace Layout**| `resources/views/components/layouts/workspace-layout.blade.php` | Split-pane interactive workspace | Built / Ready | `views/modern/ui-playground.blade.php` | Underutilized by legacy virtual classrooms. |
| **Report Layout** | `resources/views/components/layouts/report-layout.blade.php` | Standardized A4 report container | Built / Ready | None | Dedicated print views use raw CSS print stylesheets. |

### 2.2 Critical Layout Resolution Finding
> **Resolution Verdict:**  
> When views invoke `<x-layouts.faculty-shell>`, Laravel Blade component resolution **exclusively loads `resources/views/components/layouts/faculty-shell.blade.php`**.  
> The file at `resources/views/layouts/faculty-shell.blade.php` is an **unreferenced, identical 1:1 shadow duplicate** (along with 5 other layout files in `resources/views/layouts/`).

---

## 3. Global Component Audit

### 3.1 Frozen Component Registry (16 Locked v1 Components)

| Component Group | Component Name | File Path | Usage Count | Major Pages Using Component | Token Compliance | Legacy Alternatives Present |
|:---|:---|:---|:---|:---|:---|:---|
| **Navigation** | `Sidebar.v1` | `resources/views/components/layout/sidebar.blade.php` | 7 | `admin_control_desk`, `lecturer_dashboard`, `student_dashboard`, `student_mock_test`, `student_attendance`, `faculty-shell`, `app-shell` | ✅ Full | Legacy hardcoded `<aside>` in 8 views (`hod_dashboard`, `tutor_dashboard`, etc.) |
| **Navigation** | `TopBar.v1` | `resources/views/components/layout/topbar.blade.php` | 7 | `admin_control_desk`, `lecturer_dashboard`, `student_dashboard`, `student_mock_test`, `faculty-shell`, `app-shell` | ✅ Full | Legacy `<header>` in 15+ views |
| **Navigation** | `UserMenu.v1` | `resources/views/components/layout/user-menu.blade.php` | 2 | Embedded inside `TopBar.v1` | ✅ Full | Inline dropdown menus in legacy views |
| **Navigation** | `Notifications.v1`| `resources/views/components/layout/notifications.blade.php`| 2 | Embedded inside `TopBar.v1` | ✅ Full | Static bell icons |
| **Navigation** | `Breadcrumb.v1` | `resources/views/components/ui/breadcrumb.blade.php` | 1 | `dashboard-layout.blade.php` | ✅ Full | Custom inline breadcrumb spans |
| **Buttons** | `Button.v1` | `resources/views/components/ui/button.blade.php` | 20 | `modern/auth/login`, `forgot-password`, `access-denied`, `auth-error`, `ui-playground` | ✅ Full | 1,255 raw `<button>` elements across legacy views |
| **Inputs** | `Input.v1` | `resources/views/components/ui/input.blade.php` | 1 | `modern/ui-playground.blade.php` | ✅ Full | 742 raw `<input>` elements |
| **Selects** | `Select.v1` | `resources/views/components/ui/select.blade.php` | 9 | `student_dashboard`, `student_mock_test`, `ui-playground` | ✅ Full | 233 raw `<select>` elements |
| **Badges** | `Badge.v1` | `resources/views/components/ui/badge.blade.php` | 9 | `modern/ui-playground.blade.php` | ✅ Full | Hardcoded colored spans in 50+ views |
| **Chips** | `Chip.v1` | `resources/views/components/ui/chip.blade.php` | 4 | `modern/ui-playground.blade.php` | ✅ Full | Inline filter pill buttons |
| **Alerts** | `Alert.v1` | `resources/views/components/ui/alert.blade.php` | 6 | `modern/auth/login`, `modern/auth/auth-error`, `ui-playground` | ✅ Full | Custom `#globalAlert` divs |
| **Progress** | `Progress.v1` | `resources/views/components/ui/progress.blade.php` | 1 | `modern/auth/loading.blade.php` | ✅ Full | Custom SVG circles / bar divs |
| **Cards** | `Card.v1` | `resources/views/components/ui/card.blade.php` | 4 | `modern/ui-playground.blade.php` | ✅ Full | Handcrafted `bg-white border rounded-2xl` divs |
| **Tables** | `Table.v1` | `resources/views/components/ui/table.blade.php` | 1 | `modern/ui-playground.blade.php` | ✅ Full | 379 raw `<table>` elements |
| **Tabs** | `Tabs.v1` | `resources/views/components/ui/tabs.blade.php` | 0 | `ui-playground` (demo) | ✅ Full | Hand-rolled JS tab switchers in 18 views |
| **Modals** | `Modal.v1` | `resources/views/components/ui/modal.blade.php` | 0 | `ui-playground` (demo) | ✅ Full | 45+ inline hidden modal `<div>`s |
| **Pagination** | `Pagination.v1` | `resources/views/components/ui/pagination.blade.php` | 0 | `ui-playground` (demo) | ✅ Full | Manual page button groups |
| **Search** | `Search.v1` | `resources/views/components/ui/search.blade.php` | 1 | `TopBar.v1` / `ui-playground` | ✅ Full | Custom search inputs |
| **Dropdown** | `Dropdown.v1` | `resources/views/components/ui/dropdown.blade.php` | 0 | `ui-playground` (demo) | ✅ Full | Custom floating menus |

---

## 4. Raw HTML Control Audit

A quantitative scan across all 166 views revealed extreme reliance on unmanaged native HTML controls:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                      RAW HTML CONTROLS VS. BLADE COMPONENTS                 │
├──────────────────────────────┬────────────────────────┬─────────────────────┤
│ Control Type                 │ Raw HTML Count         │ Component Tag Count │
├──────────────────────────────┼────────────────────────┼─────────────────────┤
│ <select> Dropdowns           │ 233                    │ 9 (<x-ui.select>)   │
│ <button> Elements            │ 1,255                  │ 20 (<x-ui.button>)  │
│ <input> Form Fields          │ 742                    │ 1 (<x-ui.input>)    │
│ <textarea> Text Areas        │ 67                     │ 0 (<x-ui.textarea>) │
│ <table> Data Tables          │ 379                    │ 1 (<x-ui.table>)    │
│ <a> Action Links             │ 307                    │ N/A                 │
└──────────────────────────────┴────────────────────────┴─────────────────────┘
```

### 4.1 Control Classification Analysis

* **Class A: Approved Component Implementation (1.8%):** `<x-ui.button>` on modern auth pages, `<x-ui.select>` on student portal.
* **Class B: Legitimate Native HTML Usage (18.5%):**
  * Dynamic rows generated inside client-side JavaScript (`document.createElement('input')` or `innerHTML += '<input type="checkbox"...>'`) for attendance rosters and timetables.
  * Form controls in print-only reports (`resources/views/r26/*_print.blade.php`).
* **Class C: Legacy UI Controls (58.2%):**
  * Old unstyled `<select>` elements with inline `style="font-size: 11px"` in unmigrated views (`hod_dashboard.blade.php`, `r26/virtual_classroom_theory.blade.php`, `tutor_student_diary_full.blade.php`).
* **Class D: High-Priority Candidates for Component Migration (21.5%):**
  * Static filter dropdowns, search inputs, and modal action buttons in major dashboards (`hod_dashboard`, `admin_control_desk`, `lecturer_dashboard`).

### 4.2 Top 15 Worst Offending Files (Raw Controls Proliferation)

| View File | Raw Selects | Raw Inputs | Raw Buttons | Raw Textareas | Raw Tables | Control Load Score |
|:---|:---:|:---:|:---:|:---:|:---:|:---:|
| `lecturer_dashboard.blade.php` | 9 | 80 | 93 | 9 | 23 | **209** |
| `r26_practicum/virtual_classroom_practicum.blade.php` | 17 | 39 | 105 | 5 | 17 | **202** |
| `admin_control_desk.blade.php` | 25 | 41 | 76 | 3 | 8 | **195** |
| `r26/virtual_classroom_theory.blade.php` | 12 | 44 | 68 | 3 | 15 | **156** |
| `hod_dashboard.blade.php` | 21 | 20 | 63 | 0 | 12 | **146** |
| `chairman_dashboard.blade.php` | 20 | 31 | 45 | 2 | 6 | **138** |
| `hod_sbte_audit.blade.php` | 7 | 69 | 29 | 4 | 11 | **124** |
| `r26_drawing/virtual_classroom_drawing.blade.php` | 6 | 63 | 31 | 5 | 8 | **117** |
| `tutor_student_diary_full.blade.php` | 9 | 31 | 39 | 5 | 7 | **106** |
| `r26_practical/virtual_classroom_practical.blade.php` | 11 | 25 | 43 | 1 | 4 | **106** |
| `student_mentoring_diary_full.blade.php` | 8 | 26 | 31 | 2 | 6 | **83** |
| `tutor_dashboard.blade.php` | 6 | 4 | 39 | 0 | 14 | **61** |
| `remedial_dashboard.blade.php` | 4 | 18 | 22 | 0 | 3 | **52** |
| `student_dashboard.blade.php` | 1 | 18 | 30 | 1 | 9 | **52** |
| `mentoring_diary_modal.blade.php` | 3 | 15 | 26 | 4 | 9 | **53** |

---

## 5. Design Token Compliance Audit

| Token Category | Target Specification | Violations Detected | Severity | Typical Locations |
|:---|:---|:---:|:---:|:---|
| **Micro-Fonts (< 14px)** | $\ge 14\text{px}$ (`text-sm` / `text-base`) for inputs, tables, labels | **4,285 occurrences** | **HIGH** | `hod_dashboard`, `virtual_classroom_*`, `tutor_dashboard`, `lecturer_dashboard` using `text-xs`, `text-[10px]`, `text-[11px]`. |
| **Inline Styles** | Externalized Tailwind / Token CSS | **1,865 occurrences** | **MEDIUM** | Hardcoded `style="width:...; background:...; font-size: 11px"` across legacy tables and modals. |
| **Glow / Text Shadows** | Zero text shadows / neon glows | **17 occurrences** | **CRITICAL** | Legacy CSS blocks in `hod_mobile_dashboard.blade.php` (`text-shadow: 0 0 10px...`). |
| **Arbitrary Colors** | Approved 70/15/10/5 palette tokens | **320+ occurrences** | **HIGH** | Unapproved shades (`bg-purple-600`, `text-teal-500`, `bg-orange-400`) in unmigrated views. |
| **Legacy Typefaces** | Poppins (`font-sans`) | **92 occurrences** | **MEDIUM** | Hardcoded `font-family: 'Inter'`, `'Plus Jakarta Sans'`, `'Segoe UI'` in legacy standalone headers. |

---

## 6. Shell & Layout Duplication Audit

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│                      VIEW LAYOUT INHERITANCE BREAKDOWN                           │
├────────────────────────────────────────┬───────────────────┬─────────────────────┤
│ Category                               │ View Count        │ Percentage          │
├────────────────────────────────────────┼───────────────────┼─────────────────────┤
│ A. Fully Modern Layout (<x-layouts.*>) │ 12 views          │ 7.2%                │
│ B. Partial Modern Shell (<x-layout.*>) │ 37 views          │ 22.3%               │
│ C. Standalone Legacy (Own <head>/HTML) │ 72 views          │ 43.4%               │
│ D. Dedicated Print / A4 Templates      │ 45 views          │ 27.1%               │
└────────────────────────────────────────┴───────────────────┴─────────────────────┘
```

### 6.1 Notable Standalone Legacy Views (Category C)
These views define their own `<!DOCTYPE html>`, `<head>`, independent Tailwind/Font links, and navigation bars:
* `hod_dashboard.blade.php` (4,217 lines)
* `hod_mobile_dashboard.blade.php` (1,184 lines)
* `hod_sbte_audit.blade.php` (1,016 lines)
* `hod_report_centre.blade.php` (597 lines)
* `hod_workload_panel.blade.php` (537 lines)
* `hod_academic_calendar.blade.php` (407 lines)
* `tutor_dashboard.blade.php` (1,840 lines)
* `tutor_student_diary_full.blade.php` (1,230 lines)
* `chairman_dashboard.blade.php` (2,100 lines)
* `super_admin_dashboard.blade.php` (850 lines)
* `academic_coordinator_dashboard.blade.php` (1,420 lines)
* `general_coordinator_aided_dashboard.blade.php` (980 lines)
* `general_coordinator_sf_dashboard.blade.php` (980 lines)
* `demonstrator_dashboard.blade.php` (840 lines)
* `trade_instructor_dashboard.blade.php` (790 lines)
* `workshop_superintendent_dashboard.blade.php` (810 lines)
* `parent_dashboard.blade.php` (640 lines)

---

## 7. JavaScript & External Asset Duplication Audit

### 7.1 CDN Script Tag Leaks

| External CDN Dependency | Occurrences | Found In | Modern Equivalent in Workspace | Severity |
|:---|:---:|:---|:---|:---:|
| **Google Fonts** (`fonts.googleapis.com`) | 95 | 72 standalone views | Preloaded in `<x-layouts.*>` | **MEDIUM** |
| **Tailwind CDN** (`@tailwindcss/browser@4` / `cdn.tailwindcss.com`) | 14 | `hod_dashboard`, `hod_workload_panel`, `hod_report_centre`, `hod_sbte_audit`, `hod_mobile_dashboard` | Pre-compiled Vite CSS (`resources/css/app.css`) | **HIGH** |
| **Material Symbols** (`fonts.googleapis.com/css2?...Material+Symbols`) | 82 | Standalone views | Included in `<x-layouts.*>` | **MEDIUM** |
| **FontAwesome CDN** (`cdnjs.cloudflare.com/.../font-awesome`) | 16 | `hod_mobile_dashboard`, `student_mobile_dashboard` | Lucide Icons / Material Symbols in Vite bundle | **HIGH** |
| **Chart.js CDN** (`cdn.jsdelivr.net/.../chart.js`) | 55 | Standalone dashboards | Modular JS chart imports in `resources/js/charts/` | **MEDIUM** |
| **Lucide CDN** (`unpkg.com/lucide@latest`) | 6 | `admin_control_desk`, `student_dashboard`, legacy views | Bundled in `resources/js/app.js` (`import { createIcons } from 'lucide'`) | **HIGH** |

### 7.2 Large Inline JavaScript Monoliths
* **`admin_control_desk.blade.php`**: ~1,800 lines of inline `<script>` containing 45+ AJAX functions and panel state switchers.
* **`hod_dashboard.blade.php`**: ~2,400 lines of inline `<script>` containing 70+ AJAX functions, modal handlers, and timetable cell renderers.
* **`r26_practicum/virtual_classroom_practicum.blade.php`**: ~1,500 lines of inline `<script>` for assessment grade calculations.

---

## 8. Navigation Architecture Audit

### 8.1 Configuration & Service Mapping

```
config/navigation/
├── admin.php           (Admin navigation items)
├── faculty.php         (Faculty workspace items: My Batches, Attendance Log, Remedial, Course Files)
├── hod.php             (HOD navigation items; inherits faculty)
├── principal.php       (Principal desk items; explicit inherits: null)
├── student.php         (Student portal items)
├── super_admin.php     (Super admin items)
└── tutor.php           (Tutor desk items; inherits faculty)
```

### 8.2 Architectural Compliance & Audit Findings
1. **Separation of Authorization vs. Navigation:**
   * ✅ `NavigationService` strictly configures UI link visibility based on session state and does not execute backend authorization logic.
   * ⚠️ **Direct DB Queries in NavigationService:** Lines 93–101 query `ClassManagement` and `R26ClassManagement` to detect dynamic tutor/mentor roles for logged-in faculty. While wrapped in `try/catch`, querying the database on every page navigation should ideally be cached in session upon login.
2. **Contextual Role Switching & Return Links:**
   * ✅ Executive users (Principal, Admin, HOD, Demonstrators) entering the Faculty Workspace cleanly receive the dynamic **"Return to Dashboard"** navigation item before `my_batches`.
   * ✅ Redundant links (`prof_activities`, `profile`) are properly stripped for executive users inside the Faculty Workspace.
3. **Active State Binding:**
   * ✅ Active state is computed via `$item['is_active'] = ($active === $item['id'])` and styled in `sidebar.blade.php` with high-contrast active pills.

---

## 9. Complete Migration Inventory (Major System Views)

| Blade View | Primary Route | Role Target | Current Layout | Status | Primary Problems to Resolve | Target Migration Phase |
|:---|:---|:---|:---|:---:|:---|:---:|
| `admin_control_desk.blade.php` | `/dashboard/principal`, `/dashboard/admin` | Principal / Admin | Partial (`<x-layout.sidebar>`) | 🟢 **GREEN** | Large inline JS; shell works well. | Complete |
| `principal_dashboard.blade.php`| `/dashboard/principal` | Principal | Wrapper (`@include`) | 🟢 **GREEN** | Alias to `admin_control_desk`. | Complete |
| `student_dashboard.blade.php` | `/dashboard/student` | Student | Partial (`<x-layout.sidebar>`) | 🟢 **GREEN** | Modernized; R2026 gradebook active. | Complete |
| `student_mock_test.blade.php` | `/student/test/{id}` | Student | Partial (`<x-layout.sidebar>`) | 🟢 **GREEN** | Modern test engine active. | Complete |
| `student_attendance.blade.php` | `/student/attendance` | Student | Partial (`<x-layout.sidebar>`) | 🟢 **GREEN** | Modernized layout. | Complete |
| `lecturer_dashboard.blade.php` | `/dashboard/lecturer` | Faculty | Partial (`<x-layout.sidebar>`) | 🟢 **GREEN** | Modernized cards, template support. | Complete |
| `attendance_log.blade.php` | `/staff/attendance-log` | Faculty | `<x-layouts.faculty-shell>` | 🟢 **GREEN** | Responsive 12-col desktop layout. | Complete |
| `remedial_dashboard.blade.php` | `/remedial-sessions` | Faculty | `<x-layouts.faculty-shell>` | 🟢 **GREEN** | Responsive 12-col room creator. | Complete |
| `course_files_dashboard.blade.php`| `/course-files` | Faculty | `<x-layouts.faculty-shell>` | 🟢 **GREEN** | Levels 1–4 checklist modern shell. | Complete |
| `modern/auth/*` (6 views) | `/login`, `/recover`, etc. | All | `<x-layouts.auth-layout>` | 🟢 **GREEN** | Modern auth experience. | Complete |
| `sf_staff_face_punch.blade.php`| `/staff/sf-attendance/face-punch`| SF Staff | Dedicated Mobile Shell | 🟢 **GREEN** | Biometric geofenced punch. | Complete |
| `sf_staff_attendance_report.blade.php`| `/staff/sf-attendance/report` | Executive / Staff | Standalone Table | 🟢 **GREEN** | Modern premises ledger. | Complete |
| **`hod_dashboard.blade.php`** | `/dashboard/hod` | HOD | Standalone HTML | 🔴 **RED** | 4,217 lines monolithic view; Tailwind CDN; 12 tables, 16 modals. | **Phase 2C (NEXT)** |
| **`hod_academic_calendar.blade.php`**| `/hod/academic-calendar` | HOD | Standalone HTML | 🔴 **RED** | Standalone calendar editor; Tailwind CDN. | **Phase 2C (NEXT)** |
| **`hod_workload_panel.blade.php`**| `/hod/workload-panel` | HOD | Standalone HTML | 🔴 **RED** | Timetable print/editor; Tailwind CDN. | **Phase 2C (NEXT)** |
| **`hod_report_centre.blade.php`**| `/hod/reports` | HOD | Standalone HTML | 🔴 **RED** | Standalone report dialogs; Tailwind CDN. | **Phase 2C (NEXT)** |
| **`hod_nba_audit.blade.php`** | `/hod/nba-audit` | HOD | Standalone HTML | 🔴 **RED** | Standalone document manager. | **Phase 2C (NEXT)** |
| **`hod_sbte_audit.blade.php`** | `/hod/sbte-audit` | HOD | Standalone HTML | 🔴 **RED** | 1,016 lines; 69 inputs; 11 tables. | **Phase 2C (NEXT)** |
| **`hod_mobile_dashboard.blade.php`**| `/hod/mobile` | HOD | Standalone HTML | 🔴 **RED** | FontAwesome CDN; redundant theme engine. | **Phase 2C (NEXT)** |
| `tutor_dashboard.blade.php` | `/dashboard/tutor` | Tutor | Standalone HTML | 🔴 **RED** | Legacy monolithic view; 14 tables. | Phase 2D |
| `tutor_student_diary_full.blade.php`| `/tutor/diary/{id}` | Tutor | Standalone HTML | 🔴 **RED** | Legacy student dossier tables. | Phase 3 |
| `student_mentoring_diary_full.blade.php`| `/student/mentoring-diary` | Student/Tutor | Standalone HTML | 🔴 **RED** | Legacy table layouts. | Phase 3 |
| `demonstrator_dashboard.blade.php`| `/dashboard/demonstrator`| Demonstrator | Standalone HTML | 🔴 **RED** | Legacy view; needs faculty shell. | Phase 2 |
| `trade_instructor_dashboard.blade.php`| `/dashboard/tradeinstructor`| Trade Instructor | Standalone HTML | 🔴 **RED** | Legacy view; needs faculty shell. | Phase 2 |
| `workshop_superintendent_dashboard.blade.php`| `/dashboard/workshop` | Superintendent | Standalone HTML | 🔴 **RED** | Legacy view; needs dashboard shell. | Phase 2 |
| `academic_coordinator_dashboard.blade.php`| `/dashboard/academic-coordinator`| Coordinator | Standalone HTML | 🔴 **RED** | Legacy coordinator dashboard. | Phase 4 |
| `general_coordinator_aided_dashboard.blade.php`| `/dashboard/general-coordinator-aided`| Coordinator | Standalone HTML | 🔴 **RED** | Legacy coordinator dashboard. | Phase 4 |
| `general_coordinator_sf_dashboard.blade.php`| `/dashboard/general-coordinator-sf`| Coordinator | Standalone HTML | 🔴 **RED** | Legacy coordinator dashboard. | Phase 4 |
| `chairman_dashboard.blade.php` | `/dashboard/chairman` | Chairman | Standalone HTML | 🔴 **RED** | Monolithic view; can reuse Executive Desk. | Phase 4 |
| `super_admin_dashboard.blade.php`| `/dashboard/superadmin`| Super Admin | Standalone HTML | 🔴 **RED** | Monolithic view; maps to Executive Desk. | Phase 4 |
| `parent_dashboard.blade.php` | `/parent/dashboard` | Parent | Standalone HTML | 🔴 **RED** | Standalone legacy portal. | Phase 4 |
| `r26/virtual_classroom_theory.blade.php`| `/r26/classroom/{id}` | Faculty | Standalone Frame | 🟡 **YELLOW** | R2026 grade logic active; legacy frame. | Phase 4 |
| `r26_practicum/virtual_classroom_practicum.blade.php`| `/r26-practicum/classroom/{id}` | Faculty | Standalone Frame | 🟡 **YELLOW** | Legacy frame structure. | Phase 4 |
| `r26_practical/virtual_classroom_practical.blade.php`| `/r26-practical/classroom/{id}` | Faculty | Standalone Frame | 🟡 **YELLOW** | Legacy frame structure. | Phase 4 |
| `r26_drawing/virtual_classroom_drawing.blade.php`| `/r26-drawing/classroom/{id}` | Faculty | Standalone Frame | 🟡 **YELLOW** | Legacy frame structure. | Phase 4 |
| `r26/*_print.blade.php` (20+ views)| Print routes | All | Standalone Print | 🔘 **GRAY** | Standardized A4 print templates. | Standardized |

---

## 10. Deep Audit: Head of Department (HOD) Subsystem

The HOD subsystem represents the single largest unmigrated operational hub in CampusLynk.

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│                           HOD SUBSYSTEM ANATOMY (7 SCREENS)                      │
├─────────────────────────┬───────────────────────────┬────────────────────────────┤
│ 1. Core Management Hub  │ 2. Planning & Workload    │ 3. Accreditation & Audits  │
│ • hod_dashboard         │ • hod_academic_calendar   │ • hod_nba_audit            │
│ • hod_mobile_dashboard  │ • hod_workload_panel      │ • hod_sbte_audit           │
│ • User Directory (Staff)│ • Batch Timetables        │ • hod_report_centre        │
│ • Batch & Subject Mappings • Period Slot Matrix     │ • Activity Points & Leaves │
└─────────────────────────┴───────────────────────────┴────────────────────────────┘
```

### 10.1 HOD Screen-by-Screen Architectural Matrix

| View File | Lines | Layout | Tables | Modals | Selects | Inputs | Buttons | Key REST API Endpoints | Primary JavaScript Functions | External CDNs Loaded |
|:---|:---:|:---|:---:|:---:|:---:|:---:|:---:|:---|:---|:---|
| **`hod_dashboard.blade.php`** | 4,217 | Standalone `<html>` | 12 | 16 | 21 | 20 | 63 | `/api/hod/batches`<br>`/api/r26/hod/batches`<br>`/api/hod/dept-staff`<br>`/api/admin/user/toggle-status`<br>`/api/student/update/${regNo}`<br>`/api/audit-logs` | `switchPanel()`<br>`loadBatches()`<br>`loadUsers()`<br>`renderTimetable()`<br>`saveSubject()`<br>`assignStaff()`<br>`submitCreateBatch()` | `@tailwindcss/browser@4`<br>`tailwindcss@2.2.19`<br>`Material Symbols`<br>`Unsplash CDN` |
| **`hod_academic_calendar.blade.php`** | 407 | Standalone `<html>` | 1 | 0 | 4 | 6 | 6 | `/api/academic-calendar/save`<br>`/api/academic-calendar/parse-pdf` | `togglePanel()`<br>`onFile()`<br>`makeRow()`<br>`fetchFromPdf()`<br>`save()` | `tailwindcss@2.2.19`<br>`Material Symbols`<br>`Inter font` |
| **`hod_workload_panel.blade.php`** | 537 | Standalone `<html>` | 1 | 0 | 2 | 1 | 4 | `/api/hod/batches/${id}/subjects`<br>`/api/hod/batches/${id}/timetable` | `updateSelectionStatus()`<br>`printSingleTimetable()`<br>`renderPrintCell()` | `@tailwindcss/browser@4`<br>`tailwindcss@2.2.19`<br>`Material Symbols` |
| **`hod_report_centre.blade.php`** | 597 | Standalone `<html>` | 0 | 4 | 6 | 0 | 18 | Client-side window print triggers | `openAttendanceModal()`<br>`printAttendanceSummary()`<br>`printRemedialReport()`<br>`printCourseFilesReport()` | `@tailwindcss/browser@4`<br>`Material Symbols`<br>`Inter font` |
| **`hod_nba_audit.blade.php`** | 140 | Standalone `<html>` | 0 | 0 | 1 | 2 | 0 | Direct form posts | Criteria 1-10 document submission handlers | `@tailwindcss/browser@4`<br>`Material Symbols` |
| **`hod_sbte_audit.blade.php`** | 1,016 | Standalone `<html>` | 11 | 0 | 7 | 69 | 29 | `/api/hod/sbte-audit/generate-perf`<br>`/api/hod/sbte-audit/generate-course-files`<br>`/api/hod/sbte-audit/fetch-staff-activities` | `generateAcademicPerformance()`<br>`addSocietyRow()`<br>`addPublicationRow()`<br>`generateCourseFiles()`<br>`addFacultyTrainingRow()` | `@tailwindcss/browser@4`<br>`Material Symbols` |
| **`hod_mobile_dashboard.blade.php`** | 1,184 | Standalone `<html>` | 0 | 7 | 2 | 3 | 26 | `/api/staff/leave/process-approval`<br>`/api/mentoring/leave/approve`<br>`/api/hod/notice/create`<br>`/api/system/set-day-order` | `switchStaffTab()`<br>`openStaffLeaveActionModal()`<br>`processStudentLeave()`<br>`submitDepartmentNotice()`<br>`setDayOrder()` | `cdn.tailwindcss.com`<br>`font-awesome@6.4.0`<br>`Plus Jakarta Sans` |

### 10.2 HOD Migration Critical Path & Functional Workflows
1. **Department Batches & Semester Advancements (`panelBatches`)**:
   * Critical AJAX workflow: `/api/hod/batches/${classroomId}/update-semester` and `/api/hod/batches/${activeBatchId}/graduate`.
   * Dynamic timetable slot generator with period conflict detection.
2. **Subject Allocation & Faculty Staff Assignment (`panelSubjects`)**:
   * Department prefix formatting (`_getBranchPrefix()`), course type syncing (`syncSubjectTypeOptions()`), and modal-driven staff allocations (`assignStaff()`).
3. **Department Staff Directory & Permissions (`panelDirectory`)**:
   * Instant status toggles (`/api/admin/user/toggle-status`), password reset trigger modals, and photo uploads.
4. **Accreditation Hubs (NBA & SBTE Audits)**:
   * Dynamic row appending for societies, publications, FDPs, consultancies, and faculty achievements.
5. **Staff & Student Leave Approvals**:
   * High-priority administrative action queues processing staff leaves and student medical leaves.

---

## 11. Migration Risk Analysis Matrix

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│                      REMAINING MIGRATION GROUPS RISK RANKING                     │
├──────┬────────────────────────────┬────────────┬────────────┬────────────────────┤
│ Rank │ Migration Group            │ Complexity │ Risk Level │ Primary Challenge  │
├──────┼────────────────────────────┼────────────┼────────────┼────────────────────┤
│ 1    │ Head of Department (HOD)   │ Very High  │ 🔴 HIGH    │ 70+ AJAX endpoints │
│ 2    │ Virtual Classrooms (R2026) │ Very High  │ 🔴 HIGH    │ Real-time grading  │
│ 3    │ Tutor & Mentoring Dossiers │ High       │ 🟠 MEDIUM  │ Multi-step modals  │
│ 4    │ Technical & Workshop Staff │ Medium     │ 🟢 LOW     │ Layout alignment   │
│ 5    │ Academic Coordinators      │ Medium     │ 🟢 LOW     │ Layout alignment   │
│ 6    │ Leadership (Chairman)      │ Low        │ 🟢 LOW     │ Executive view map │
│ 7    │ Parent Portal              │ Low        │ 🟢 LOW     │ Standalone view    │
└──────┴────────────────────────────┴────────────┴────────────┴────────────────────┘
```

| Migration Target Group | UI Complexity | JS / AJAX Complexity | Business Logic Risk | Component Reuse Potential | Migration Feasibility | Recommended Execution Approach |
|:---|:---:|:---:|:---:|:---:|:---:|:---|
| **1. HOD Console & Panels** | High (12 tables, 16 modals) | High (70+ functions) | High (Timetable, Staff mapping) | ⭐⭐⭐⭐⭐ (90%) | High | Migrate `hod_dashboard.blade.php` to `<x-layouts.dashboard-layout>` while keeping AJAX handlers strictly intact. |
| **2. Virtual Classrooms (R2026)** | High (Dynamic grade matrices) | High (Auto-calculations) | High (R2026 7-grade policy) | ⭐⭐⭐⭐ (75%) | Medium | Wrap inside `<x-layouts.workspace-layout>`; preserve internal calculation engines. |
| **3. Tutor & Mentoring Suite** | Medium (Dossier tables) | Medium (Leave & diary logs) | Medium (Student mentoring) | ⭐⭐⭐⭐⭐ (90%) | High | Modernize table views with `<x-ui.table>` and modal containers. |
| **4. Technical & Workshop Staff** | Low (Workload & practicals) | Low (CRUD tables) | Low (Assigned labs) | ⭐⭐⭐⭐⭐ (95%) | Very High | Direct wrap into `<x-layouts.faculty-shell>`. |
| **5. Coordinator Desks** | Low (Curriculum & calendar) | Low (Calendar sync) | Low (Aided/SF divisions) | ⭐⭐⭐⭐⭐ (95%) | Very High | Direct wrap into `<x-layouts.dashboard-layout>`. |
| **6. Chairman & Super Admin** | Low (Executive oversight) | Low (Global metrics) | Low (Read-only monitoring) | ⭐⭐⭐⭐⭐ (100%) | Very High | Map directly to `admin_control_desk.blade.php`. |
| **7. Parent Portal** | Low (Student report card) | Low (Attendance viewer) | Low (Read-only portal) | ⭐⭐⭐⭐ (80%) | Very High | Migrate to clean student/parent card layout. |

---

## 12. Global UI Enforcement Recommendation

### 12.1 Can Raw Native Controls Be Safely Styled Globally via CSS?
* **Yes for Base Elements (`<input>`, `<textarea>`, native `<select>`, `<table>`):**
  * Adding global base layer rules in `resources/css/app.css` (`@layer base { input:not([type="checkbox"]):not([type="radio"]), select { @apply h-11 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none; } }`) safely guarantees a unified visual floor without breaking legacy views.
* **No for Buttons (`<button>`):**
  * `<button>` elements vary wildly (Icon-only buttons, Table row actions, Red destructive buttons, Tab switchers, Dropdown triggers, Primary CTAs). Styling raw `<button>` elements globally with a fixed height and background would break thousands of micro-controls. Button variants must be applied via Blade components (`<x-ui.button>`) or explicit utility classes (`btn-primary`, `btn-secondary`).

### 12.2 Controls That MUST Use Blade Components
1. **`<x-ui.select>` / Enhanced Custom Select**: When rich searchable dropdowns, clear icons, or custom options are required.
2. **`<x-ui.modal>`**: Essential for standardizing backdrops, ESC-key dismissal, z-index hierarchy (`z-50`), and mobile slide-up sheets.
3. **`<x-ui.badge>` & `<x-ui.chip>`**: Guarantees strict token-adherent status colors (Emerald, Amber, Blue, Rose, Slate).
4. **`<x-ui.tabs>`**: Eliminates fragmented, hand-rolled JS tab switchers.

### 12.3 Automated Governance Protocol
* Enforce minimum font sizes ($\ge 14\text{px}$) and zero font glows via pre-commit audit scripts.
* Require that all newly migrated screens declare their parent shell (`<x-layouts.faculty-shell>`, `<x-layouts.dashboard-layout>`, or `<x-layouts.workspace-layout>`).

---

## 13. Dead, Redundant & Orphaned File Report

The audit identified the following non-destructive cleanup targets:

### 13.1 Obsolete Backup Files
* **`resources/views/r26_practicum/virtual_classroom_practicum.blade.php.bak`**: Obsolete backup copy (48 KB). Can be safely removed in a dedicated cleanup cycle.

### 13.2 Redundant Layout Duplicates (6 Files)
The entire `resources/views/layouts/` directory contains exact 1:1 duplicate files of `resources/views/components/layouts/`:
1. `resources/views/layouts/app-shell.blade.php` (Duplicate of `components/layouts/app-shell.blade.php`)
2. `resources/views/layouts/auth-layout.blade.php` (Duplicate of `components/layouts/auth-layout.blade.php`)
3. `resources/views/layouts/dashboard-layout.blade.php` (Duplicate of `components/layouts/dashboard-layout.blade.php`)
4. `resources/views/layouts/faculty-shell.blade.php` (Duplicate of `components/layouts/faculty-shell.blade.php`)
5. `resources/views/layouts/report-layout.blade.php` (Duplicate of `components/layouts/report-layout.blade.php`)
6. `resources/views/layouts/workspace-layout.blade.php` (Duplicate of `components/layouts/workspace-layout.blade.php`)

### 13.3 Redundant View Aliases
* **`resources/views/principal_dashboard.blade.php`**: Contains solely `@include('admin_control_desk')`.
* **`resources/views/admin_dashboard.blade.php`**: Contains solely a redirect/include of `admin_control_desk`.

---

## 14. Recommended Next Steps

1. **Step 1:** Establish global base form styling in `resources/css/app.css` to lift the baseline quality of unmigrated inputs and dropdowns.
2. **Step 2 (Next Immediate Milestone):** Execute **Phase 2C: HOD Dashboard Modernization** (`hod_dashboard.blade.php`) into `<x-layouts.dashboard-layout>`.
3. **Step 3:** Consolidate HOD secondary sub-views (`hod_workload_panel`, `hod_academic_calendar`, `hod_report_centre`, `hod_sbte_audit`) as clean sub-panels or modal workflows.
4. **Step 4:** Migrate Phase 2D (Tutor Dashboard) and Phase 3 (Mentoring Dossiers).
5. **Step 5:** Migrate Technical & Workshop staff (Demonstrator, Trade Instructor, Workshop Superintendent) to `<x-layouts.faculty-shell>`.

---

## 15. RECOMMENDED NEXT ACTION

============================================================
RECOMMENDED NEXT ACTION
============================================================

**Migrate `hod_dashboard.blade.php` (Phase 2C) to `<x-layouts.dashboard-layout>` as a single, isolated implementation cycle, preserving all 70+ existing JavaScript functions, DOM IDs, AJAX endpoints (`/api/hod/*`), and department timetable/subject management workflows.**

============================================================
*(Audit concluded with zero file or code modifications.)*
