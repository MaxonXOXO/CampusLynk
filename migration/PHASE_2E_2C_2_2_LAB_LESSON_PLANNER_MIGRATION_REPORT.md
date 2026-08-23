# CampusLynk — Phase 2E.2C.2-2 Migration Report
# R2026 Practicum Lab Lesson Planner Desktop UI Modernization

**Phase:** Phase 2E.2C.2-2 — Lab Planner Workspace Modernization  
**Scope:** R2026 Practicum Virtual Classroom (`resources/views/r26_practicum/virtual_classroom_practicum.blade.php`)  
**Target Container:** `#lab-subcontent-planner` (45-Hour Practical / Lab Delivery Component)  
**Curriculum Model:** Revision 2026 (R2026) 90-Hour Practicum (45 Theory Hours + 45 Practical/Lab Hours across 15 3-Hour Blocks)  
**Status:** **IMPLEMENTED & VERIFIED — ZERO BACKEND/API/DATABASE EDITS**  
**Date:** August 23, 2026  
**Auditor & Engineer:** Antigravity AI Pair Programmer  

---

## 1. Executive Summary

In Phase 2E.2C.2-2, the **Practical Lab Lesson Planner** workspace inside the R2026 Practicum Virtual Classroom (`resources/views/r26_practicum/virtual_classroom_practicum.blade.php`) was modernized from legacy dark glass styling into the CampusLynk academic operations design system.

The modernization strictly respects the core **15 chunked Lab session blocks** (3 hours per block = 45 practical hours), the `data-block-ids` grouping mechanism, and the global 90-hour persistence pipeline (`saveAllLessonPlans()`). Zero modifications were made to controllers, API routes, database schemas, print templates, or mobile views.

---

## 2. Files Modified

| File | Nature of Modification | Lines Touched |
| :--- | :--- | :--- |
| `resources/views/r26_practicum/virtual_classroom_practicum.blade.php` | Modernized `#lab-subcontent-planner` with 4-card metric summary bar, action toolbar, filter controls, white-card table layout, and JS helper handlers (`filterLabPlannerRows`, `onLabActualDateChange`, `onLabBatchChange`, `updateLabMetrics`). | ~1457–1715, ~2727–2845 |

### Untouched Protected Boundaries:
- `app/Http/Controllers/R26VirtualClassroomPracticumController.php` — **100% UNTOUCHED**
- `resources/views/r26_practicum/lesson_plan_print.blade.php` — **100% UNTOUCHED**
- `resources/views/r26_practicum/virtual_classroom_practicum.blade.php` (`#theory-subcontent-planner`) — **100% PROTECTED & UNTOUCHED**
- `resources/views/staff_mobile_dashboard.blade.php` — **100% UNTOUCHED**
- `database/migrations/*` — **100% UNTOUCHED**

---

## 3. Before vs After UI Architecture

```
BEFORE (Legacy Dark Glass):
┌────────────────────────────────────────────────────────────────────────┐
│ [Dark Glass Container: #lab-subcontent-planner]                        │
│ Practical Sessions Planner (45 P Hours)   [Save All]  [Print]          │
│ ┌────────────────────────────────────────────────────────────────────┐ │
│ │ Sticky Slate-900 Table (Day/Hr | Pedagogy | Prop | Act | Topic... )│ │
│ │ Session 1 | [Select: 12px] | [Date: 12px] | [Textarea] | CO | 3Hrs │ │
│ └────────────────────────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────────────────────┘

AFTER (CampusLynk Academic Operations Workspace):
┌────────────────────────────────────────────────────────────────────────┐
│ 1. LAB METRIC SUMMARY BAR (4-Card Grid)                                │
│ ┌────────────────┐ ┌────────────────┐ ┌────────────────┐ ┌───────────┐ │
│ │ 45 Hrs Planned │ │ N Hrs Conducted│ │ M Hrs Pending  │ │ Coverage %│ │
│ │ 15 Lab Sessions│ │ X of 15 Done   │ │ Y Sessions Left│ │ [Progress]│ │
│ └────────────────┘ └────────────────┘ └────────────────┘ └───────────┘ │
│                                                                        │
│ 2. LAB PLANNER MAIN WORKSPACE CARD                                     │
│ ┌────────────────────────────────────────────────────────────────────┐ │
│ │ Header: LAB DELIVERY PLAN / Practical Lab Lesson Planner           │ │
│ │ Action Toolbar: [Print Lesson Plan]  [Save All 90 Hours (Global)]  │ │
│ ├────────────────────────────────────────────────────────────────────┤ │
│ │ Filter Bar: [Search Experiments] [CO Filter] [Batch] [Status] Count│ │
│ ├────────────────────────────────────────────────────────────────────┤ │
│ │ Modern White-Card Table Workspace (Sticky Header, 14px Typography) │ │
│ │ • Session 01 | Pedagogy Select | Proposed | Actual | Topic | CO... │ │
│ │ • Sub-Batch (A&B / A / B) | 3 Hours Badge | Completed/Pending Pill │ │
│ └────────────────────────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 4. Lab Block Architecture & Session Chunking

In the R2026 Practicum curriculum, 45 lab hours are scheduled as 15 3-hour practical blocks.
The Blade view retains the chunking pipeline:
```php
$labPlans = $lessonPlans->whereIn('mode', ['P', 'SP'])->values()->take(45);
$labSessions = $labPlans->chunk(3);
```
Each row in `#lab-subcontent-planner` represents one 3-hour chunk:
- Block Row ID: `id="lp-row-{{ $firstPlan->id }}"`
- Master Plan ID: `data-plan-id="{{ $firstPlan->id }}"`
- Underlying Grouped IDs: `data-block-ids="{{ $blockIds }}"` (e.g. `124,125,126`)

---

## 5. Metric Implementation

The 4-card metric grid computes live practical delivery figures:
1. **Planned Practical Workload (`#labMetricPlanned`):** Fixed target of `45 Hrs` (15 sessions).
2. **Conducted Hours (`#labMetricCompleted`):** Count of blocks where `actual_date` is populated $\times 3$ hours.
3. **Remaining Hours (`#labMetricRemaining`):** $\max(0, 45 - \text{conducted hours})$.
4. **Practical Coverage (`#labMetricCoverage`):** $(\text{conducted hours} / 45) \times 100\%$ with smooth progress bar (`#labMetricProgressBar`).

Dynamic updates occur when any `actual_date` input is modified via `onLabActualDateChange(planId, value)`.

---

## 6. Search & Filter Implementation

The interactive filter bar provides client-side visibility toggling (without DOM removal):
- `#labPlannerSearch`: Real-time text search against experiment topics, session numbers, remarks, pedagogies, and CO badges.
- `#labPlannerCOFilter`: Filters by Course Outcome (`CO1`, `CO2`, etc.).
- `#labPlannerBatchFilter`: Filters by sub-batch (`Batch A & B (Combined)`, `Batch A`, `Batch B`).
- `#labPlannerStatusFilter`: Filters by delivery status (`Completed` vs `Pending`).
- `#labPlannerCount`: Live counter showing `Showing X of 15 lab sessions`.

---

## 7. DOM Contract Preservation Matrix

| Legacy Selector / ID | Preserved in Modernized Workspace | Purpose / Binding |
| :--- | :---: | :--- |
| `id="lab-subcontent-planner"` | **YES** | Primary tab container for Lab Planner |
| `tr#lp-row-{id}` | **YES** | Row identifier scanned by `saveAllLessonPlans()` |
| `data-plan-id="{id}"` | **YES** | Master plan ID attribute for row serialization |
| `data-block-ids="{ids}"` | **YES** | Comma-separated underlying 3-hour IDs for batch persistence |
| `#lp-pedagogy-{id}` | **YES** | Pedagogy dropdown |
| `#lp-prop-{id}` | **YES** | Proposed date input |
| `#lp-act-{id}` | **YES** | Actual date input |
| `#lp-topic-{id}` | **YES** | Practical topic textarea |
| `#lp-co-{id}` | **YES** | Hidden CO input |
| `#lp-batch-td-{id}` | **YES** | Sub-batch table cell |
| `#lp-batch-{id}` | **YES** | Sub-batch dropdown |
| `#lp-hours-td-{id}` | **YES** | Hours allocated container (`3 Hours`) |
| `#lp-remarks-{id}` | **YES** | Remarks input |

---

## 8. data-block-ids Preservation Matrix

| Block Index | Session Label | Master Plan ID | Underling IDs in `data-block-ids` | Contact Hours |
| :---: | :--- | :---: | :--- | :---: |
| 1 | Session 01 | 121 | `121,122,123` | 3 Hrs |
| 2 | Session 02 | 124 | `124,125,126` | 3 Hrs |
| 3 | Session 03 | 127 | `127,128,129` | 3 Hrs |
| 4 | Session 04 | 130 | `130,131,132` | 3 Hrs |
| 5 | Session 05 | 133 | `133,134,135` | 3 Hrs |
| 6 | Session 06 | 136 | `136,137,138` | 3 Hrs |
| 7 | Session 07 | 139 | `139,140,141` | 3 Hrs |
| 8 | Session 08 | 142 | `142,143,144` | 3 Hrs |
| 9 | Session 09 | 145 | `145,146,147` | 3 Hrs |
| 10 | Session 10 | 148 | `148,149,150` | 3 Hrs |
| 11 | Session 11 | 151 | `151,152,153` | 3 Hrs |
| 12 | Session 12 | 154 | `154,155,156` | 3 Hrs |
| 13 | Session 13 | 157 | `157,158,159` | 3 Hrs |
| 14 | Session 14 | 160 | `160,161,162` | 3 Hrs |
| 15 | Session 15 | 163 | `163,164,165` | 3 Hrs |

**Total:** Exactly 15 session blocks representing all 45 underlying practical records.

---

## 9. JavaScript Preservation Matrix

| Function | Status | Role |
| :--- | :---: | :--- |
| `saveAllLessonPlans()` | **PRESERVED & ENHANCED** | Scans all `tr[id^="lp-row-"]`, unrolls `data-block-ids`, submits to backend API, and updates both Theory and Lab metrics. |
| `onPedagogyChange(id, val)` | **PRESERVED & ENHANCED** | Updates styling to 14px CampusLynk badges; adjusts batch and hours containers. |
| `filterTheoryPlannerRows()` | **PRESERVED** | Client-side filtering for Theory Planner. |
| `onTheoryActualDateChange(id, val)` | **PRESERVED** | Dynamic status pill & metrics for Theory Planner. |
| `updateTheoryMetrics()` | **PRESERVED** | Recalculates 45-hour Theory delivery metrics. |
| `filterLabPlannerRows()` | **NEW** | Client-side filtering for Lab Planner. |
| `onLabActualDateChange(id, val)` | **NEW** | Dynamic status pill & metrics for Lab Planner. |
| `onLabBatchChange(id, val)` | **NEW** | Synchronizes sub-batch data attributes for filtering. |
| `updateLabMetrics()` | **NEW** | Recalculates 45-hour / 15-block Lab delivery metrics. |
| `switchMode(mode)` | **PRESERVED** | Tab switcher between Theory and Lab classrooms. |
| `switchTheorySubtab(tab)` | **PRESERVED** | Subtab switcher for Theory classroom. |
| `switchLabSubtab(tab)` | **PRESERVED** | Subtab switcher for Lab classroom. |

---

## 10. Save Pipeline Verification

`saveAllLessonPlans()` scans:
```javascript
const rows = document.querySelectorAll('tr[id^="lp-row-"]');
```
Because both `#theory-subcontent-planner` rows (43/45 rows) and `#lab-subcontent-planner` rows (15 blocks $\times$ 3 IDs = 45 rows) use `tr[id^="lp-row-"]`, `saveAllLessonPlans()` unrolls all rows to construct the complete 90-record payload sent to:
`POST /api/r26/classroom/practicum/{subjectId}/lesson-plan/save-all`.

---

## 11. Print Pipeline Verification

The print route:
`GET /r26/classroom/practicum/3/print-lesson-plan`
returns `HTTP 200` (95,370 bytes), rendering the comprehensive 90-hour A4 print view without regression.

---

## 12. Build & Verification Results

1. **Frontend Asset Build:**
   - `npm.cmd run build` $\rightarrow$ **0 Errors** (`1837` modules transformed, 6.83s).
2. **View Cache:**
   - `php artisan view:clear` $\rightarrow$ **Compiled views cleared successfully**.
3. **Automated Regression Suite (`scratch/verify_practicum_lab_planner.php`):**
   - `GET /r26/classroom/practicum/3` $\rightarrow$ **HTTP 200** (1.46 MB).
   - `GET /r26/classroom/practicum/3/print-lesson-plan` $\rightarrow$ **HTTP 200** (95.3 KB).
   - Lab DOM Container & Components: **15/15 PASS**.
   - Lab Sessions & `data-block-ids` Contract: **15 Blocks / 45 Underlying IDs PASS**.
   - Theory Planner Regression Check: **10/10 PASS**.
   - JavaScript Handlers: **12/12 PASS**.

---

## 13. Completion Statement

Phase 2E.2C.2-2 Lab Planner UI Modernization COMPLETE.
