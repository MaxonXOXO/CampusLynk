# CampusLynk Phase 0 — Global UI Foundation Hardening Report

**Phase:** Phase 0 — Foundation Only  
**Execution Date:** August 20, 2026  
**Repository:** `MaxonXOXO/CampusLynk` (`carmel-linx-laravel`)  
**Scope:** Global form baseline, control strategies, token compliance, layout verification, and asset hardening.

---

## 1. Changes Made

1. **Conservative Global Form CSS Baseline**:
   - Implemented a baseline in `resources/css/app.css` for standard text `<input>`, native `<select>`, and `<textarea>` controls.
   - Enforced inherited Poppins typography, a minimum $\ge 14\text{px}$ (`text-sm`) typography floor, `#ffffff` surface, `#e2e8f0` (Slate 200) border, `12px` (`rounded-xl`) corner radii, `#0f172a` text color, blue focus ring, and disabled states.
   - Explicitly excluded `<button>` and specialized inputs (checkbox, radio, file, range, color, hidden).
2. **Select & Input Strategy Formalization**:
   - Codified Rule 7 in `design-system/AI_RULES.md` and `carmel-linx-laravel/design-system/AI_RULES.md` clarifying `<x-ui.select>` vs. native `<select>` vs. JavaScript-generated selects.
   - Updated label typography in `<x-ui.select>` from `text-xs` to `text-sm font-medium` to comply with the 14px minimum font standard.
3. **CDN Guardrails & Canonical Pipeline Protection**:
   - Codified Rule 8 in `AI_RULES.md` mandating that all new layouts and migrated screens inherit styling strictly through the canonical Vite pipeline (`@vite(['resources/css/app.css', 'resources/js/app.js'])`).
   - Prohibited runtime Tailwind CDN, Lucide CDN, FontAwesome CDN, and duplicate Google Fonts link tags in migrated views.
4. **Layout Duplicate Safety Inspection**:
   - Scanned all 166 views for references to `resources/views/layouts/` vs. `resources/views/components/layouts/`.
   - Verified that `<x-layouts.faculty-shell>` and other layout components resolve exclusively to `resources/views/components/layouts/`.

---

## 2. Files Modified

| File Path | Description of Changes |
|:---|:---|
| [`resources/css/app.css`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/css/app.css) | Added conservative `@layer base` form rules for inputs, native selects, textareas, placeholders, focus, and disabled states. |
| [`resources/views/components/ui/select.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/components/ui/select.blade.php) | Updated label styling from `text-xs font-semibold` to `text-sm font-medium` to meet $\ge 14\text{px}$ standard. |
| [`design-system/AI_RULES.md`](file:///d:/AMs/academic-platform/design-system/AI_RULES.md) | Added Rule 7 (Control Strategy & Component Governance) and Rule 8 (CDN Guardrails). |
| [`carmel-linx-laravel/design-system/AI_RULES.md`](file:///d:/AMs/academic-platform/carmel-linx-laravel/design-system/AI_RULES.md) | Added Rule 7 and Rule 8 synchronized with root design system. |

---

## 3. Global CSS Rules Added (`resources/css/app.css`)

```css
@layer base {
  /* Standard Form Text Inputs, Selects, and Textareas */
  input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="range"]):not([type="color"]):not([type="button"]):not([type="submit"]):not([type="reset"]):not([type="hidden"]),
  select:not([multiple]),
  textarea {
    font-family: inherit;
    font-size: 0.875rem; /* 14px minimum UI typography floor */
    line-height: 1.25rem;
    color: #0f172a;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem; /* 12px (rounded-xl) */
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  /* Placeholder Styling */
  input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="range"]):not([type="color"]):not([type="button"]):not([type="submit"]):not([type="reset"]):not([type="hidden"])::placeholder,
  textarea::placeholder {
    color: #94a3b8;
  }

  /* Focus States */
  input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="range"]):not([type="color"]):not([type="button"]):not([type="submit"]):not([type="reset"]):not([type="hidden"]):focus,
  select:not([multiple]):focus,
  textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
  }

  /* Disabled States */
  input:not([type="checkbox"]):not([type="radio"]):not([type="file"]):not([type="range"]):not([type="color"]):not([type="button"]):not([type="submit"]):not([type="reset"]):not([type="hidden"]):disabled,
  select:not([multiple]):disabled,
  textarea:disabled {
    background-color: #f1f5f9;
    color: #94a3b8;
    border-color: #e2e8f0;
    cursor: not-allowed;
    opacity: 0.8;
  }

  /* Native Select Custom Chevron & Padding */
  select:not([multiple]) {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem 1rem;
    padding-right: 2.5rem !important;
    cursor: pointer;
  }

  select:not([multiple]):hover {
    border-color: #94a3b8;
  }
}
```

---

## 4. Component Rules Established

All newly migrated views and feature additions must conform to the 16 frozen v1 components:
* **Buttons**: Standardized on `<x-ui.button>` or explicit design system button utility classes (`btn-primary`, `btn-secondary`). Raw `<button>` tags are permitted for specialized interactive elements (tab pills, table row triggers, close buttons).
* **Inputs**: `<x-ui.input>` for standardized form fields.
* **Selects**: `<x-ui.select>` for rich searchable/styled dropdowns; native `<select>` for browser-default popups.
* **Badges / Chips**: `<x-ui.badge>` / `<x-ui.chip>`.
* **Cards / Panels**: `<x-ui.card>` or structured token containers (`bg-white border border-slate-200 rounded-2xl p-6 shadow-xs`).
* **Tables**: `<x-ui.table>` or tokenized data table markup.
* **Modals**: `<x-ui.modal>`.
* **Tabs**: `<x-ui.tabs>`.
* **Alerts**: `<x-ui.alert>`.
* **Search**: `<x-ui.search>`.

---

## 5. Select Strategy

* **A. Native `<select>`**: Allowed when native browser behavior is intentionally required or inside dedicated A4 print reports. Automatically inherits the global 14px typography, Poppins font, custom SVG chevron, and slate borders from `app.css`.
* **B. `<x-ui.select>`**: Preferred for standardized Blade forms, filter bars, settings, and new migrated views.
* **C. JavaScript-Generated Selects**: Dynamic `<select>` elements appended via `document.createElement` or `innerHTML` in client-side scripts remain functionally untouched and automatically receive the global base styling without breaking event listeners.

---

## 6. Input & Textarea Strategy

* **`<x-ui.input>` State Support**:
  - `default`: Full support (`bg-white border border-slate-200 text-slate-900 rounded-xl min-h-[44px] text-sm`).
  - `focus`: Full support (`focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10`).
  - `error`: Full support (`border-rose-400 focus:border-rose-500` + error message).
  - `disabled`: Full support (`disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed`).
  - `readonly`: Full support via attribute pass-through.
  - `placeholder`: Full support (`placeholder:text-slate-400`).
  - `icon`: Full support (left icon slot with `pl-10` padding).
  - `required`: Full support via attribute pass-through.
* **Textarea Strategy**:
  - Native `<textarea>` elements automatically receive the global base styling (14px typography, rounded-xl radius, slate-200 border, blue focus ring).
  - Adding `<x-ui.textarea>` is not required for Phase 0 since native textareas inherit the complete token floor cleanly.

---

## 7. Typography Strategy

* **Global Visual Floor**: All standard form controls, table data, and descriptions inherit the minimum 14px (`text-sm`) typography floor.
* **Preservation of Legacy Micro-Usages**: Unmigrated legacy views are not mass-edited, preserving compact chart badges, print document footnotes, and legacy matrix tables from accidental layout breaks.

---

## 8. CDN Guardrails

* **Canonical Pipeline**: All migrated views MUST inherit assets via Vite:
  ```blade
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  ```
* **Prohibited CDN Imports in Migrated Screens**:
  - Runtime Tailwind CDN (`@tailwindcss/browser@4` or `cdn.tailwindcss.com`)
  - Runtime Lucide CDN (`unpkg.com/lucide@latest`)
  - FontAwesome CDN (`cdnjs.cloudflare.com/.../font-awesome`)
  - Duplicate Google Fonts link tags outside master shells
  - Duplicate Chart.js CDN links
* **Legacy Isolation**: Legacy standalone screens retain their existing scripts until their dedicated migration phase.

---

## 9. Duplicate Layout Safety Findings

* **Layout Discovery**:
  - `resources/views/layouts/` contains 6 exact duplicate files mirroring `resources/views/components/layouts/`:
    1. `app-shell.blade.php`
    2. `auth-layout.blade.php`
    3. `dashboard-layout.blade.php`
    4. `faculty-shell.blade.php`
    5. `report-layout.blade.php`
    6. `workspace-layout.blade.php`
* **Safety Audit**:
  - A repository-wide search confirmed **zero active references** to `resources/views/layouts/` via `@extends` or `view('layouts.*')`.
  - All modern views invoke the component syntax `<x-layouts.*>`, which Laravel exclusively resolves from `resources/views/components/layouts/`.
  - **Action**: In accordance with Phase 0 rules, these 6 files are documented as **safe cleanup candidates** and remain untouched during Phase 0.

---

## 10. Verification Results

1. **Vite Production Compilation**:
   - Command: `npm.cmd run build`
   - Result: **SUCCESS** (0 errors, 1,837 modules transformed in 8.64s).
   - Bundled Assets: `public/build/assets/app-DbgUoBVh.css` (248.5 kB), `public/build/assets/app-DRlf5hEb.js` (441.5 kB).
2. **Target Modern View Smoke Tests**:
   - `/login` (`login.blade.php`): **SUCCESS** (17,952 bytes rendered)
   - `/dashboard/principal` (`admin_control_desk.blade.php`): **SUCCESS** (288,031 bytes rendered)
   - `/dashboard/student` (`student_dashboard.blade.php`): **SUCCESS** (201,431 bytes rendered)
   - `/dashboard/lecturer` (`lecturer_dashboard.blade.php`): **SUCCESS** (467,286 bytes rendered)
   - `/staff/attendance-log` (`attendance_log.blade.php`): **SUCCESS** (74,071 bytes rendered)
   - `/remedial-sessions` (`remedial_dashboard.blade.php`): **SUCCESS** (105,352 bytes rendered)
   - `/course-files` (`course_files_dashboard.blade.php`): **SUCCESS** (98,690 bytes rendered)

---

## 11. Remaining Risks & Pre-Migration Notes

1. **HOD JavaScript Monolith**: `hod_dashboard.blade.php` contains ~2,400 lines of inline JavaScript managing 70+ functions. The migration must preserve all DOM element IDs and AJAX handlers.
2. **Tailwind CDN Removal in HOD**: When migrating `hod_dashboard.blade.php`, removing `@tailwindcss/browser@4` and connecting to the compiled Vite pipeline requires validating that all Tailwind utility classes exist in `resources/css/app.css`.

---

NEXT RECOMMENDED PHASE

"Begin HOD Dashboard migration as an isolated UI migration,
starting with the global shell and dashboard structure before
touching individual HOD panels."
