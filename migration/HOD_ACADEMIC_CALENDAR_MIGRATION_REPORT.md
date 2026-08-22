# CAMPUSLYNK — HOD ACADEMIC CALENDAR PLANNER
## PHASE 2C.6 — UI MIGRATION REPORT

**Date:** August 22, 2026  
**Status:** COMPLETED & VERIFIED (STRICT UI-ONLY REDESIGN)  
**Target Domain:** Head of Department (HOD) Academic Calendar Planner Sub-Desk  
**Authoritative Baseline:** [`migration/HOD_ACADEMIC_CALENDAR_FORENSIC_AUDIT.md`](file:///d:/AMs/academic-platform/migration/HOD_ACADEMIC_CALENDAR_FORENSIC_AUDIT.md)

---

## 1. Executive Summary

The **HOD Academic Calendar Planner** ([`resources/views/hod_academic_calendar.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_academic_calendar.blade.php)) has been modernized into the **CampusLynk Design System** and integrated with the canonical **`<x-layouts.app-shell>`**.

> [!IMPORTANT]
> **Key Architectural Accomplishment:**  
> *"UI presentation was substantially redesigned rather than performing a 1:1 visual conversion of the legacy interface."*  
> The 6-semester accordion stack has been upgraded into high-density, interactive application modules with clean typography (min 14px), responsive tables, contextual guidance cards, and native Lucide icons, while preserving 100% of the underlying SITTTR PDF parsing and Gemini 1.5 Flash AI extraction pipelines.

---

## 2. Files Modified

| File Path | Description of Changes |
|:---|:---|
| [`resources/views/hod_academic_calendar.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_academic_calendar.blade.php) | 1. Wrapped in `<x-layouts.app-shell>` with topbar navigation and breadcrumb context.<br>2. Removed Tailwind 2.2 CDN, Google Fonts Inter CDN, and Material Symbols CDN.<br>3. Upgraded `#0b0f1a` dark theme to `#FAFAFB` canvas with clean `#FFFFFF` cards.<br>4. Modernized 6 semester accordions with clear collapsed metadata and expanded editors.<br>5. Preserved all critical DOM IDs, class selectors, and 8 JavaScript handlers. |

---

## 3. Files Intentionally Untouched

- **Controller & AI Engine:**
  - [`app/Http/Controllers/AcademicCalendarController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/AcademicCalendarController.php) *(Smalot PDF parser, Gemini 1.5 Flash AI prompt, and store logic 100% untouched)*.
- **Dedicated Print Views:**
  - [`resources/views/hod_academic_calendar_print.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/hod_academic_calendar_print.blade.php) *(A4 Landscape two-column print view 100% preserved)*.
- **Database & Storage:**
  - Database table `academic_calendars`, JSON `activities` structure, and `storage/app/public/academic_calendars/` storage path.
- **Routes:**
  - `GET /hod/academic-calendar`, `POST /api/academic-calendar/save`, `POST /api/academic-calendar/parse-pdf`, `GET /hod/academic-calendar/{id}/print`.

---

## 4. Redesigned Workspace Architecture

```
CampusLynk App Shell (<x-layouts.app-shell>)
│
├── Topbar: "Academic Calendar" (activeNav: report_centre)
│
└── Breadcrumb: Report Centre  /  Academic Calendar Planner
    │
    ├── Header Card: Department Badge, Title, Configuration Metrics, & "Back to Report Centre"
    │
    ├── Guidance Card: 5-Step Workflow Instructions (SITTTR Upload → AI Fetch → Edit → Save → Print)
    │
    └── 6-Semester Accordion Stack (@for $sem = 1..6)
        │
        ├── Collapsed Header: Roman Badge [ I–VI ], Sem Title, Status Badge, Print A4, & Chevron
        │
        └── Expanded Editor:
            ├── Academic Year Input (#year_{sem}) & SITTTR PDF Dropzone (#uz_{sem}, #pdf_{sem})
            ├── Month-Wise Calendar Entries Table (#ct_{sem}, #cb_{sem}, .erow)
            │   ├── Month Selector (.e-month)
            │   ├── Date Number Input (.e-date)
            │   ├── Activity Description (.e-activity)
            │   ├── Category Classification (.e-type: Academic, Exam, Holiday, Event, Dept, Other)
            │   └── Delete Action (trash-2 icon)
            └── Action Toolbar:
                ├── "Add Row" (addRow)
                ├── "Auto-Fetch from PDF" (fetchFromPdf, #fetchBtn_{sem}, #fetchLabel_{sem})
                ├── "Manual Template" (prefill)
                └── "Save Calendar" (save)
```

---

## 5. Preservation Matrix

### Critical DOM Identifiers (100% Preserved):
| DOM Identifier | Element Type | Category | Purpose | Status |
|:---|:---:|:---:|:---|:---:|
| `#globalAlert` | `<div>` | Feedback | Toast notification banner | **PRESERVED** |
| `#panel_{1..6}` | `<div>` | Panel Container | Semester card wrapper | **PRESERVED** |
| `#chevron_{1..6}` | `<i>` | Toggle Indicator | Expansion chevron | **PRESERVED** |
| `#body_{1..6}` | `<div>` | Accordion Body | Expandable semester workspace | **PRESERVED** |
| `#year_{1..6}` | `<input>` | Academic Year | Year boundary input | **PRESERVED** |
| `#uz_{1..6}` | `<div>` | Dropzone | Interactive PDF upload zone | **PRESERVED** |
| `#pdf_{1..6}` | `<input type="file">` | File Input | Hidden PDF file input | **PRESERVED** |
| `#ct_{1..6}` | `<table>` | Table | Calendar entries table | **PRESERVED** |
| `#cb_{1..6}` | `<tbody>` | Table Body | Dynamic row insertion container | **PRESERVED** |
| `#fetchBtn_{1..6}` | `<button>` | AI Trigger | Auto-Fetch button | **PRESERVED** |
| `#fetchLabel_{1..6}` | `<span>` | Dynamic Label | Loading text container | **PRESERVED** |

### Critical JavaScript Handlers (100% Preserved):
| Handler | Trigger | Purpose | Status |
|:---|:---|:---|:---:|
| `togglePanel(sem)` | Header `onclick` | Expands/collapses target accordion | **PRESERVED** |
| `onFile(sem)` | File input `onchange` | Updates dropzone UI upon file selection | **PRESERVED** |
| `makeRow(sem, ...)` | Dynamic constructor | Builds styled table row with 4 inputs | **PRESERVED** |
| `addRow(sem)` | Button `onclick` | Appends blank row to `#cb_{sem}` | **PRESERVED** |
| `prefill(sem)` | Button `onclick` | Populates standardized odd/even semester templates | **PRESERVED** |
| `save(sem)` | Button `onclick` | Serializes form data and sends `POST /api/academic-calendar/save` | **PRESERVED** |
| `fetchFromPdf(sem)` | Button `onclick` | Triggers AI PDF parser and de-duplicates rows | **PRESERVED** |
| `alert_ok(m)` / `alert_err(m)` | Feedback calls | Displays top-right toast notification | **PRESERVED** |

---

## 6. Responsive Design Audit

- **1440px (Desktop):** Full-width 5-column table editor with generous padding and clear action button alignment.
- **768px (Tablet):** Dropzone and year inputs adjust cleanly. Actions wrap into organized clusters with 44px touch targets.
- **375px (Mobile):** Single-column layout with horizontal table scroll container, 14px minimum typography, and zero horizontal page overflow.

---

## 7. Build & Regression Verification

1. **Vite Production Build:**
   - Command: `npm.cmd run build`
   - Output: `✓ 1837 modules transformed. ✓ built in 8.47s` (Exit Code 0).
2. **Laravel Cache Clear:**
   - `php artisan view:clear`, `php artisan route:clear`, `php artisan config:clear` cleared successfully.
3. **Automated Smoke Test (`test_academic_calendar.php`):**
   - View `hod_academic_calendar` compiles and renders cleanly (100,508 bytes).
   - All 11 critical DOM identifier patterns verified across all 6 semesters.
   - All 8 JavaScript functions verified.
   - Both API endpoints verified.
4. **Parent HOD Workspace Navigation:**
   - Seamless two-way navigation between HOD Dashboard (`/dashboard/hod?panel=report_centre`) and Academic Calendar Planner (`/hod/academic-calendar`).

---

## 8. Conclusion

**PHASE 2C.6 UI MIGRATION COMPLETE.**  
The HOD Academic Calendar Planner has been transformed into a first-class CampusLynk sub-desk, eliminating all legacy CDNs and dark UI treatments while preserving 100% of its SITTTR PDF parsing and Gemini AI capabilities.
