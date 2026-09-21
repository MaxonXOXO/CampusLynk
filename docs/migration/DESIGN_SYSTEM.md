# CampusLynk Design System & Architecture Specification

> **Status:** MANDATORY & ENFORCED  
> **Audience:** ChatGPT Architect/Supervisor, Autonomous Migration Agents, Implementer, Verifier  
> **Source of Truth for UI Migration:** `carmel-linx-laravel/resources/views/components/`

---

## 1. Core Architectural Mandate

To prevent component duplication, UI fragmentation, and regression to legacy monolithic patterns:

$$\text{All New UI} \implies \text{Existing CampusLynk Global Components \& Design Tokens}$$

1. **Zero Raw HTML Duplication:** Never create raw HTML buttons (`<button class="...">`), raw modals, ad-hoc tables, or custom card styles when an equivalent `<x-ui.*>` component exists.
2. **Master Shell Mandatory:** All migrated views must be mounted inside one of CampusLynk's Master Shell layouts (`<x-layouts.workspace-layout>`, `<x-layouts.app-shell>`, `<x-layouts.faculty-shell>`, `<x-layouts.report-layout>`).
3. **Decompose Monolithic Views:** Legacy views exceeding 500 lines must never be copied verbatim. Decompose them into modular Blade partials and atomic UI components.
4. **Design Token Consistency:** Adhere strictly to the slate neutral palette, action blue (`blue-600`), and semantic indicators (`rose-600` danger, `emerald-600` success, `amber-500` warning).

---

## 2. Master Shell Layouts (`<x-layouts.*>`)

All user-facing views must extend one of the following Master Shells:

### 2.1 Workspace Layout (`<x-layouts.workspace-layout>`)
- **Path:** `resources/views/components/layouts/workspace-layout.blade.php`
- **Use Case:** Virtual classrooms, grading desks, evaluation workspaces (e.g. R21 Major Project, R21 Seminar, R21 Drawing, R26 Practicum).
- **Props:**
  - `title` (string): Page title for document `<title>`.
  - `subjectCode` (string): Subject code badge (e.g. `"6009"`).
  - `subjectName` (string): Subject/course name header.
  - `activeNav` (string): Active navigation tab (default: `'academics'`).
- **Slots:**
  - `headerActions`: Action buttons in the workspace header banner (e.g. Save, Export, Print).
  - `slot`: Main workspace canvas.
- **Example Usage:**
  ```blade
  <x-layouts.workspace-layout 
      title="R21 Major Project Workspace"
      subjectCode="6009"
      subjectName="Major Project - VI Sem"
      activeNav="academics">
      
      <x-slot:headerActions>
          <x-ui.button variant="secondary" icon="printer" onclick="window.print()">Print Report</x-ui.button>
          <x-ui.button variant="primary" icon="check" id="btn-save-eval">Save Evaluation</x-ui.button>
      </x-slot:headerActions>

      <!-- Decomposed workspace sections -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          ...
      </div>
  </x-layouts.workspace-layout>
  ```

### 2.2 App Shell (`<x-layouts.app-shell>`)
- **Path:** `resources/views/components/layouts/app-shell.blade.php`
- **Use Case:** Standard administrative dashboards, coordinator consoles, tutor portals.
- **Props:** `title`, `activeNav`.
- **Features:** Responsive sidebar navigation, global topbar, notification center, user profile menu.

### 2.3 Faculty Shell (`<x-layouts.faculty-shell>`)
- **Path:** `resources/views/components/layouts/faculty-shell.blade.php`
- **Use Case:** Lecturer, HOD, and demonstrator workspace dashboards.

### 2.4 Report Layout (`<x-layouts.report-layout>`)
- **Path:** `resources/views/components/layouts/report-layout.blade.php`
- **Use Case:** Official institutional A4 print reports, marks registers, consolidated progress cards.

---

## 3. Global UI Component Library (`<x-ui.*>`)

All interactive elements must use the following standard components from `resources/views/components/ui/`:

| Component | Tag | Key Props / Variants | Description |
| :--- | :--- | :--- | :--- |
| **Button** | `<x-ui.button>` | `variant`: `primary`, `secondary`, `tertiary`, `icon`, `danger`<br>`icon`, `disabled`, `loading`, `type` | Standard 44px touch-target button with micro-interactions, spinner, and focus rings. |
| **Card** | `<x-ui.card>` | `title`, `subtitle`, `action` slot | Standard white elevated card with `rounded-2xl`, `border-slate-200`, and subtle hover transition. |
| **Modal** | `<x-ui.modal>` | `id` (required), `title`, `maxWidth`<br>`footer` slot | Accessible dialog with backdrop blur (`bg-slate-900/60`), close button, and action footer. |
| **Badge** | `<x-ui.badge>` | `variant`: `success`, `warning`, `danger`, `info`, `neutral` | Semantic indicator badge with pill styling. |
| **Table** | `<x-ui.table>` | — | Standardized data table with styled headers, alternating row hover, and border lines. |
| **Tabs** | `<x-ui.tabs>` | `tabs` array or slot | Tab navigation bar for sub-sections within workspaces. |
| **Alert** | `<x-ui.alert>` | `variant`: `info`, `success`, `warning`, `danger`<br>`title`, `dismissible` | Styled notification and validation alert banners. |
| **Input** | `<x-ui.input>` | `label`, `name`, `type`, `error`, `placeholder`, `disabled` | Form text input with floating or standard label, validation state, and focus glow. |
| **Select** | `<x-ui.select>` | `label`, `name`, `options`, `error`, `placeholder` | Accessible select input with chevron and error feedback. |
| **Icon** | `<x-ui.icon>` | `name` (e.g. `check`, `printer`, `users`, `folder`, `alert-circle`), `size` | Centralized SVG icon system (56KB optimized SVG sprite). |
| **Progress** | `<x-ui.progress>` | `value`, `max`, `variant` | Visual progress bar for completion ratios, attainment percentages. |
| **Dropdown** | `<x-ui.dropdown>` | `trigger`, `align` | Contextual action dropdown menu. |

---

## 4. Anti-Duplication Rules for Autonomous Migration

Every migration decision made by the Supervisor and Architect must strictly enforce:

1. **Check Component Inventory First:** Before proposing or accepting any view implementation, check `resources/views/components/ui/` and `resources/views/components/layouts/`.
2. **Never Create Duplicate CSS:** Do not inject custom `<style>` blocks for tables, buttons, cards, or modals into Blade files. Use existing Tailwind utility tokens and `<x-ui.*>` components.
3. **No Inline Modal Implementations:** Never build custom modal backdrops or inline dialog `<div>` trees with raw vanilla JS. Use `<x-ui.modal id="...">` and trigger via `document.getElementById('modal-id').classList.remove('hidden')`.
4. **Decomposition Pattern:**
   ```text
   Legacy Monolithic View (e.g. 2,611 lines)
               │
               ▼  DECOMPOSE
   ├── resources/views/r21_project/workspace.blade.php (<x-layouts.workspace-layout>)
   ├── resources/views/r21_project/partials/header-banner.blade.php
   ├── resources/views/r21_project/partials/student-eval-table.blade.php (<x-ui.table>)
   ├── resources/views/r21_project/partials/group-allocation-modal.blade.php (<x-ui.modal>)
   ├── resources/views/r21_project/partials/ese-evaluation-modal.blade.php (<x-ui.modal>)
   └── resources/views/r21_project/partials/attainment-summary-card.blade.php (<x-ui.card>)
   ```
5. **Architectural Review Rejection Criteria:** The Supervisor/Architect must issue `REVISION_REQUIRED` if an implementation plan introduces:
   - Duplicate buttons or styling incompatible with `<x-ui.button>`.
   - Raw table structures that ignore `<x-ui.table>`.
   - Missing `<x-layouts.workspace-layout>` wrapper for classroom workspaces.
   - Monolithic Blade files exceeding 500 lines without partial decomposition.
