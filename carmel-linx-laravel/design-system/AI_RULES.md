# CampusLynk AI Engineering & UI Migration Rules
**Status:** MANDATORY & NON-NEGOTIABLE  
**Scope:** All AI coding assistants, code generation tasks, UI refactoring, and feature additions across CampusLynk AMS Platform.

---

## 1. Mandatory Prompt Preamble
Every task, UI refactoring prompt, or feature implementation on the CampusLynk AMS Platform MUST begin with the following instructions:

```markdown
Read these files first:
- /design-system/DESIGN_SYSTEM.md
- /design-system/PAGE_ARCHETYPES.md
- /design-system/COMPONENT_LIBRARY.md
- /design-system/COMPONENT_FREEZE.md
- /design-system/AI_RULES.md
- /design-system/ai_design_manifest.md

You MUST follow these documents.
Do not create new design patterns.
Do not create new colors.
Do not create new components.
Preserve all business logic.
Preserve all routes.
Preserve all functionality.
Migrate strictly one page per implementation cycle.
```

---

## 2. Reorganized Phased Migration Pipeline

All page migrations must follow the strict granular sequence below:

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                 CAMPUSLYNK REORGANIZED MIGRATION PIPELINE                   │
├───────────┬─────────────┬─────────────┬───────────┬───────────┬─────────────┤
│ Phase 1   │ Phase 2A    │ Phase 2B    │ Phase 2C  │ Phase 2D  │ Phase 2E    │
│ Auth      │ Student     │ Lecturer    │ HOD       │ Tutor     │ Admin       │
│ Gateway   │ Dashboard   │ Dashboard   │ Dashboard │ Dashboard │ Dashboard   │
├───────────┼─────────────┼─────────────┼───────────┼───────────┼─────────────┤
│ Phase 2F  │ Phase 3     │ Phase 4     │ Phase 5   │ Phase 6   │             │
│ Principal │ Mentoring & │ Interactive │ Tables &  │ Printable │             │
│ Dashboard │ 360° Dossier│ Workspaces  │ Registries│ A4 Reports│             │
└───────────┴─────────────┴─────────────┴───────────┴───────────┴─────────────┘
```

1. **Phase 1**: Authentication & Startup Experience (Login, Recovery, Session Expiry, Access Denied) — *Completed*
2. **Phase 2A**: Student Dashboard (`student_dashboard.blade.php`)
3. **Phase 2B**: Lecturer Dashboard (`lecturer_dashboard.blade.php`)
4. **Phase 2C**: HOD Dashboard (`hod_dashboard.blade.php`)
5. **Phase 2D**: Tutor Dashboard (`tutor_dashboard.blade.php`)
6. **Phase 2E**: Admin Dashboard (`admin_dashboard.blade.php`)
7. **Phase 2F**: Principal Dashboard (`principal_dashboard.blade.php`)
8. **Phase 3**: Student Mentoring & 360° Dossiers (`student_mentoring_diary_full.blade.php`, `tutor_student_diary_full.blade.php`)
9. **Phase 4**: Interactive Workspaces & Virtual Classrooms (`r26/virtual_classroom_theory.blade.php`, Practicum, Practical, Drawing)
10. **Phase 5**: Data Tables & Registry Hubs (`admin_show_users_table.blade.php`, attendance logs, leave balances)
11. **Phase 6**: Printable Reports & Accreditation Documents (CIE marksheet, NBA attainment, lesson plans)

---

## 3. Strict Page-by-Page Migration Policy

* **Single Page Isolation**: **Only ONE page may be migrated during a single implementation cycle.**
* **Batch Migration Prohibited**: Bulk migrating multiple screens simultaneously is strictly forbidden.
* **Mandatory 6-Step Migration Cycle**:
  1. **Analyze Page**: Document all route endpoints, form field names, AJAX URLs, session dependencies, and interactive state logic.
  2. **Implement Migration**: Assemble using frozen components from `COMPONENT_FREEZE.md` and design tokens from `colors.json`.
  3. **Run Migration Audit**: Fill out and verify `/migration/migration-audit-template.md`.
  4. **Run Responsive Testing**: Verify single responsive continuum at Desktop (1440px), Tablet (768px), and Mobile (375px).
  5. **Run Accessibility Testing**: Verify WCAG 2.1 AA contrast ratios, minimum `14px (text-sm)` typography, and keyboard focus states.
  6. **Verify Functionality**: Test live with `npx vite build` and browser validation. Only after all checks pass may the next page be initiated.

---

## 4. Component Freeze & Anti-Proliferation Policy

* **Locked Registry**: All 16 components defined in `COMPONENT_FREEZE.md` (`Button.v1`, `Input.v1`, `Select.v1`, `Badge.v1`, `Chip.v1`, `Alert.v1`, `Progress.v1`, `Card.v1`, `Table.v1`, `Tabs.v1`, `Modal.v1`, `Pagination.v1`, `Sidebar.v1`, `TopBar.v1`, `UserMenu.v1`, `Search.v1`) are permanently locked.
* **Prohibited Component Creation**: Ad-hoc component creation, localized inline styling, or component forks are prohibited.
* **Automatic Rejection List**: Any generated code containing unapproved names like `Button.v2`, `EnhancedButton`, `TableEnhanced`, `CustomCard`, `TemporaryComponent`, `ExperimentalButton` must be rejected immediately.

---

## 5. Core Non-Negotiable Rules

### Rule 1: Zero New Colors (Strict 70/15/10/5 Token Adherence)
* **Level 1 (70%)**: Neutral canvas `#FAFAFB`, white surfaces `#FFFFFF`, borders `#E5E7EB`, headings `#0F172A`, text `#334155`.
* **Level 2 (15%)**: Low-emphasis blue hover `#F8FAFC`, selected row `#EEF4FF`, soft focus ring `rgba(37,99,235,0.10)`.
* **Level 3 (10%)**: Medium-emphasis blue vector icons, active nav item text.
* **Level 4 (5%)**: Full-strength blue `#2563EB` **strictly reserved for Primary Action CTAs**.

### Rule 2: Minimum Font Size Standard
* **NEVER** use tiny micro-fonts (`text-xs`, `text-[10px]`, `text-[11px]`, `text-[9px]`) for inputs, form labels, descriptions, or table cell data.
* **ALWAYS** use at least `text-sm (14px)` or `text-base (16px)` for data entry, table records, inputs, dropdowns, and form labels.

### Rule 3: Zero Text Glow & Neon Effects
* **NEVER** use `text-shadow`, glowing neon CSS, or glowing card borders anywhere in the platform.

### Rule 4: Total Business Logic & Backend Preservation
* **NEVER** delete or alter route endpoints, controller bindings, query parameters, API payload keys, database schemas, or form field names (`name="..."`, `id="..."`, CSRF tokens).

### Rule 5: Revision 2026 (R2026) Official Grading Standard
* **ALWAYS** enforce the official 7-grade scale: `S, A, B, C, D, E, F`. Never use non-standard grades like `A+`, `B+`, or `O`.

### Rule 6: Local-First Development & Git Safety
* **NEVER** execute `git commit`, `git add`, or `git push` unless explicitly instructed.

### Rule 7: Control Strategy & Component Governance
* **New UI Select Policy**: New and migrated UI must use `<x-ui.select>` for standardized Blade forms, filters, search bars, and configuration panels unless native browser select behavior is explicitly required.
* **Native Select Usage**: Native `<select>` is permitted where native browser popups are intentionally required or inside dedicated print reports.
* **JavaScript-Generated Controls**: Do NOT automatically rewrite or break dynamic `document.createElement` / `innerHTML` form controls in client-side scripts.
* **Component Mapping for New UI**:
  - Buttons: `<x-ui.button>` or explicit design system button utility classes
  - Inputs: `<x-ui.input>`
  - Selects: `<x-ui.select>`
  - Badges & Chips: `<x-ui.badge>` / `<x-ui.chip>`
  - Cards & Panels: `<x-ui.card>` or structured token containers
  - Data Tables: `<x-ui.table>` or tokenized data table markup
  - Modals: `<x-ui.modal>`
  - Tabs: `<x-ui.tabs>`
  - Alerts & Notices: `<x-ui.alert>`
  - Search: `<x-ui.search>`

### Rule 8: CDN Guardrails & Canonical Asset Pipeline
* **Vite is Canonical**: All new layouts and migrated views MUST inherit assets exclusively through the compiled Vite pipeline (`@vite(['resources/css/app.css', 'resources/js/app.js'])`).
* **Prohibited CDN Imports**: New and migrated views **MUST NOT** load:
  - Runtime Tailwind CDN (`@tailwindcss/browser@4` or `cdn.tailwindcss.com`)
  - Runtime Lucide CDN (`unpkg.com/lucide@latest`)
  - FontAwesome CDN (`cdnjs.cloudflare.com/.../font-awesome`)
  - Duplicate Google Fonts link tags outside master shells
  - Duplicate Chart.js CDN links

