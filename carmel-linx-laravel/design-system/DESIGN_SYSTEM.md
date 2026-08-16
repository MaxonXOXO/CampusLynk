# CampusLynk Design System Specification
**Version:** 1.0.0  
**Platform:** Academic Management System (AMS)  
**Brand Motto:** *Connect. Manage. Empower.* — A unified institutional management suite to simplify operations and enhance campus life.

---

## 1. Brand Identity & Overview
**CampusLynk** is an enterprise-grade Academic Management System (AMS) engineered for higher education institutions, polytechnic colleges, and universities. The design language delivers a clean, unified, high-trust, and performance-driven experience built upon crisp geometry, solid typography, consistent spacing, and functional hierarchy.

---

## 2. Color Palette & Semantic Tokens

### 2.1 Primary Brand Tones
| Token | Hex Value | Usage |
|:---|:---|:---|
| **Primary 50** | `#EEF4FF` | Active sidebar item background, selected table row highlight, light badge tints |
| **Primary 100** | `#D0E7FF` | Hover state for light accents, subtle boundary rings, progress tracks |
| **Primary 500** | `#2563EB` | **Base Brand Blue**: Primary CTAs, active tab indicators, focus rings, links |
| **Primary 600** | `#1D4EDB` | Primary button hover state, interactive icon active state |
| **Primary 700** | `#1E40AF` | Pressed button states, high-contrast dark text links |

### 2.2 Neutral Surface & Typography Scale
| Token | Hex Value | Usage |
|:---|:---|:---|
| **Neutral 50** | `#FAFAFB` | Global application page background (`bg-app`), table header backgrounds |
| **Neutral 100** | `#F3F4F6` | Input background (disabled/muted), card secondary areas, chip backgrounds |
| **Neutral 200** | `#E5E7EB` | Primary border stroke for cards, tables, inputs, dividers (`border-slate-200`) |
| **Neutral 300** | `#D1D5DB` | Placeholder text, secondary icons, inactive borders |
| **Neutral 700** | `#374151` | Secondary headings, subtitle descriptions, table cell secondary values |
| **Neutral 900** | `#111827` | Primary headings, table data values, high-contrast body text |

### 2.3 Semantic & Status Colors
| State | Base Color | Light Surface Tint | Text/Icon Tint | Meaning |
|:---|:---|:---|:---|:---|
| **Success** | `#22C55E` | `#DCFCE7` | `#166534` | Approved, Completed, Active, Attainment Met |
| **Warning** | `#F59E0B` | `#FEF3C7` | `#92400E` | Pending, Review Required, In Progress |
| **Error** | `#EF4444` | `#FEE2E2` | `#991B1B` | Disapproved, Overdue, Cancelled, F-Grade |
| **Info** | `#2563EB` | `#DBEAFE` | `#1E40AF` | Informational Notice, System Updates, Draft |

---

## 3. Typography System

* **Primary Font Family:** `'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif`
* **Weights:** `300 (Light)`, `400 (Regular)`, `500 (Medium)`, `600 (SemiBold)`, `700 (Bold)`

### 3.1 Type Scale
| Level | Font Size | Line Height | Weight | Tailwind Equivalent | Primary Usage |
|:---|:---|:---|:---|:---|:---|
| **H1** | `32px` | `40px` | SemiBold (`600`) | `text-3xl font-semibold leading-10` | Page Titles, Key Milestone Headers |
| **H2** | `24px` | `32px` | SemiBold (`600`) | `text-2xl font-semibold leading-8` | Section Titles, Modal Headers, Card Group Titles |
| **H3** | `20px` | `28px` | Medium (`500`) | `text-xl font-medium leading-7` | Card Titles, Drawer Headers, Sub-sections |
| **Body** | `16px` | `24px` | Regular (`400`) | `text-base font-normal leading-6` | Standard Data Values, Descriptions, Table Content |
| **Small** | `14px` | `20px` | Regular (`400`) | `text-sm font-normal leading-5` | Form Labels, Input Values, Table Headers, Filter Chips |
| **Caption** | `12px` | `16px` | Regular (`400`) | `text-xs font-normal leading-4` | Micro-badges, helper hints, timestamps only |

> **Font Size Policy:** The minimum standard font size for form inputs, table data, and interactive controls is `14px (text-sm)` or `16px (text-base)`. Extremely small font sizes (`9px`, `10px`, `11px`) are strictly prohibited.

---

## 4. Iconography Standards
* **Style:** Modern Line Style with clean rounded joints.
* **Stroke Width:** `2px` uniform stroke.
* **Size Grid:** 
  * Small: `16px` (Inline actions, chips, status dots)
  * Medium: `20px` (Buttons, form input icons, table actions)
  * Large: `24px` (Sidebar navigation, topbar icons, card metric icons)
  * Hero: `32px` (Empty states, modal alert icons)
* **Icon Set Standard:** Lucide Icons / Google Material Symbols (Rounded line format).

---

## 5. Spacing, Elevation & Corner Radius

### 5.1 Corner Radius Scale
* `4px (rounded)`: Micro tags, progress indicators.
* `8px (rounded-lg)`: Tabs, segment controls, dropdown menu items.
* `12px (rounded-xl)`: Form inputs, buttons, icon action squares.
* `16px (rounded-2xl)`: KPI data cards, table containers, slide-out panels.
* `20px (rounded-2xl)`: Global modals, dialog boxes.
* `9999px (rounded-full)`: Badges, status chips, user avatars, pill search bars.

### 5.2 Elevation & Shadows
* **Card Elevation:** `0 1px 3px 0 rgba(17, 24, 39, 0.05), 0 1px 2px -1px rgba(17, 24, 39, 0.05)` accompanied by a crisp `1px solid #E5E7EB` border.
* **Dropdown Elevation:** `0 10px 15px -3px rgba(17, 24, 39, 0.1), 0 4px 6px -4px rgba(17, 24, 39, 0.08)`.
* **Modal Elevation:** `0 20px 25px -5px rgba(17, 24, 39, 0.12), 0 8px 10px -6px rgba(17, 24, 39, 0.08)`.
* **Prohibition:** Absolutely NO glowing neon borders, NO text-shadows, and NO blur artifacts.

---

## 6. Layout Grid & Structural Shell (AppShell)

### 6.1 Desktop Layout (> 1024px)
* **Sidebar:** Fixed left navigation with a width of `260px` (`w-64`), containing Logo, grouped navigation links, active indicator in `#EEF4FF` with `#2563EB` text, and footer user profile preview.
* **Topbar:** Height `70px` (`h-[70px]`), fixed header featuring Global Search (`max-w-md`), Theme Toggle, Notifications with unread badge count, and User Profile Pill with avatar and designation.
* **Main Canvas:** Full fluid width with maximum container constraint `1600px`, padding `px-8 py-6`.

### 6.2 Tablet Layout (768px - 1023px)
* **Sidebar:** Auto-collapses to `76px` icon-only bar or slide-over drawer with backdrop overlay.
* **Topbar:** Compact search bar and grouped action icons.
* **Main Canvas:** `px-6 py-4`, KPI cards reflow to 2-column grid.

### 6.3 Mobile Layout (< 768px)
* **Sidebar:** Off-canvas drawer sliding from the left upon hamburger trigger.
* **Topbar:** Compact `56px` height with logo mark, search toggle icon, notification bell, and user avatar.
* **Main Canvas:** `px-4 py-4`, KPI cards stack into 1-column grid. Tables wrap with smooth horizontal scroll container (`overflow-x-auto`).

---

## 7. Accessibility Standards (WCAG 2.1 AA Compliance)
1. **Contrast Ratio:** All primary text (`#111827`) and secondary labels (`#374151`) achieve a minimum contrast ratio of `7:1` against white and light neutral backgrounds (`#FAFAFB`).
2. **Focus Indicators:** Interactive components must display an explicit focus ring (`ring-2 ring-blue-500 ring-offset-2`) when navigated via keyboard.
3. **Touch Targets:** All clickable interactive elements (buttons, icons, menu links, pagination items) have a minimum dimension of `44px × 44px`.
4. **Semantic HTML:** Strict usage of `<main>`, `<nav>`, `<header>`, `<section>`, `<article>`, `<table>`, `<button>`, and `<input>` with corresponding `aria-label`, `aria-expanded`, and `aria-describedby` attributes.

---

## 8. Animation & Transition Standards
* **Standard Duration:** `150ms` (Micro-actions), `200ms` (Hover states), `300ms` (Modals & Drawers).
* **Easing Function:** `cubic-bezier(0.4, 0, 0.2, 1)` (`transition-all duration-200 ease-in-out`).
* **Hover Scale Effects:** Maximum `translate-y-[-1px]` or subtle shadow elevation on cards. No erratic bouncy motion.

---

## 9. Migration Mapping Rules
| Legacy Pattern | CampusLynk V1 Standard Component |
|:---|:---|
| Custom unstyled inline modals | `<x-modal.v1>` (Standard centered backdrop, header, body, footer) |
| HTML tables with inconsistent border/padding | `<x-data-table.v1>` (Sticky header, hover row, badge cell, pagination) |
| Text input with varying heights/fonts | `<x-input.v1>` (Height 44px, text-sm/14px, icon prefix/suffix) |
| Hardcoded colored spans / alerts | `<x-badge.v1>` / `<x-alert.v1>` (Standard palette tokens) |
| Fragmented dashboard headers | `<x-topbar.v1>` + `<x-sidebar.v1>` in `<x-app-shell.v1>` |
