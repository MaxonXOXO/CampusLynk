# CampusLynk UI Component Freeze Policy
**Status:** ACTIVE & STRICTLY ENFORCED  
**Effective Date:** Post Phase 1 Completion  
**Scope:** All UI development, refactoring, and migration tasks across CampusLynk AMS Platform.

---

## 1. Purpose & Authority
The Component Freeze Policy guarantees architectural stability and visual uniformity across all 74+ application views. Individual views must strictly assemble interfaces using the frozen component registry without introducing ad-hoc, inline, or unapproved component variations.

---

## 2. Frozen Component Registry (v1)

The following 16 core UI and Layout components are officially **LOCKED**:

| Component Identifier | File Location | Status |
|:---|:---|:---|
| **Button.v1** | `resources/views/components/ui/button.blade.php` | 🔒 LOCKED |
| **Input.v1** | `resources/views/components/ui/input.blade.php` | 🔒 LOCKED |
| **Select.v1** | `resources/views/components/ui/select.blade.php` | 🔒 LOCKED |
| **Badge.v1** | `resources/views/components/ui/badge.blade.php` | 🔒 LOCKED |
| **Chip.v1** | `resources/views/components/ui/chip.blade.php` | 🔒 LOCKED |
| **Alert.v1** | `resources/views/components/ui/alert.blade.php` | 🔒 LOCKED |
| **Progress.v1** | `resources/views/components/ui/progress.blade.php` | 🔒 LOCKED |
| **Card.v1** | `resources/views/components/ui/card.blade.php` | 🔒 LOCKED |
| **Table.v1** | `resources/views/components/ui/table.blade.php` | 🔒 LOCKED |
| **Tabs.v1** | `resources/views/components/ui/tabs.blade.php` | 🔒 LOCKED |
| **Modal.v1** | `resources/views/components/ui/modal.blade.php` | 🔒 LOCKED |
| **Pagination.v1** | `resources/views/components/ui/pagination.blade.php` | 🔒 LOCKED |
| **Sidebar.v1** | `resources/views/components/layout/sidebar.blade.php` | 🔒 LOCKED |
| **TopBar.v1** | `resources/views/components/layout/topbar.blade.php` | 🔒 LOCKED |
| **UserMenu.v1** | `resources/views/components/layout/user-menu.blade.php` | 🔒 LOCKED |
| **Search.v1** | `resources/views/components/ui/search.blade.php` | 🔒 LOCKED |

---

## 3. Modification Governance Process

No modification to any locked component is permitted without executing the **4-Document Governance Protocol**.

Any proposed component enhancement requires simultaneous, synchronized updates across:
1. `DESIGN_SYSTEM.md` (Update token specs, states, and visual definitions)
2. `COMPONENT_LIBRARY.md` (Update component API, props, and code examples)
3. `AI_RULES.md` (Update rules governing component usage)
4. `COMPONENT_FREEZE.md` (Record approval, version bump, and change rationale)

---

## 4. Prohibited Behaviors & Automatic Rejection Rules

1. **Ad-Hoc Component Creation**: Creating custom wrappers or localized replacements inside view files is strictly prohibited.
2. **Unapproved Component Variants**: Creating component variants outside the approved library is forbidden.
3. **Blacklisted Naming Conventions**: The following naming patterns indicate unapproved component forks and **MUST BE REJECTED AUTOMATICALLY**:
   - `Button.v2`
   - `EnhancedButton`
   - `TableEnhanced`
   - `CustomCard`
   - `TemporaryComponent`
   - `ExperimentalButton`
   - `*Custom*` / `*Temp*` / `*V2*`
4. **Inline Style Overrides**: Overriding frozen component styles using arbitrary CSS classes or inline style attributes (`style="..."`) is strictly disallowed.
