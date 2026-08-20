# HOD OVERVIEW PANEL — FORENSIC AUDIT REPORT

**Target Screen:** HOD Default Landing / Overview Experience (`panelBatches`)  
**View Target:** `resources/views/hod_dashboard.blade.php`  
**Execution Date:** August 20, 2026  
**Audit Purpose:** Comprehensive forensic inventory of the HOD default landing panel before Phase 2C.2 UI modernization.

---

## 1. Landing Panel Identity & Information Architecture

* **Default Active Panel:** `panelBatches` (Batch & Class Management).
* **DOM Container ID:** `id="panelBatches"`
* **DOM Container State:** Displayed by default on `DOMContentLoaded` via `switchPanel('batches')` and `loadBatches('active')`.

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│                           HOD OVERVIEW LANDING STRUCTURE                         │
├──────────────────────────────────────────────────────────────────────────────────┤
│ 1. Dynamic Notification Stream (#seminarNotificationsContainer)                  │
│    • Today's Seminar Presentations across all department batches                 │
├──────────────────────────────────────────────────────────────────────────────────┤
│ 2. Department Management Header & Action Toolbar                                 │
│    • Title: Batch & Class Management (Branch: {{ $activeBranch }})               │
│    • Description: Admission year batches, Tutor, and Mentor mappings             │
│    • Filter Pills: #btnHodFilterActive ("Current Batches"),                      │
│                    #btnHodFilterHistorical ("Previous Batches")                  │
│    • Primary Action CTA: "Create Batch" (openCreateBatchModal())                 │
├──────────────────────────────────────────────────────────────────────────────────┤
│ 3. Global Batch Alert Banner (#batchGlobalAlert)                                 │
├──────────────────────────────────────────────────────────────────────────────────┤
│ 4. Interactive Batch Cards Grid (#batchCardsGrid)                                │
│    • Responsive 2-column layout (grid-cols-1 lg:grid-cols-2 gap-6)               │
│    • Per-Batch Card Breakdown:                                                   │
│      - Classroom Code & Regulation Badges (R2026, LET, Regular)                  │
│      - Admission Year & Duration                                                 │
│      - Semester Indicator & Fast Promotion Trigger (S-1 .. S-6, Graduated)       │
│      - Assigned Class Tutor slot                                                 │
│      - Assigned Batch Mentor slot                                                │
│      - Enrolled Student Count Metric                                             │
│      - "Manage Batch" Master Drawer Trigger (openBatchDetail)                    │
│      - Active Subjects & Progress Sub-Panel (Code, Staff, Syllabus % Progress)   │
├──────────────────────────────────────────────────────────────────────────────────┤
│ 5. Empty State Container (#batchEmptyState)                                      │
└──────────────────────────────────────────────────────────────────────────────────┘
```

---

## 2. Forensic Component & Control Inventory

### 2.1 Existing DOM IDs in the Overview Panel
1. `panelBatches`: Outer container of the landing overview.
2. `seminarNotificationsContainer`: Populated dynamically by `checkTodaySeminars()`.
3. `btnHodFilterActive`: Toggle button for current active batches.
4. `btnHodFilterHistorical`: Toggle button for archived / graduated batches.
5. `batchGlobalAlert`: Alert message container for batch actions.
6. `batchCardsGrid`: Container where `renderBatchCard()` appends card elements.
7. `batchEmptyState`: Displayed when zero batches match the filter.

### 2.2 Existing Data & REST API Workflows
1. **Fetch Active/Historical Batches:**
   - Endpoint: `GET /api/hod/batches?status=${status}` and `GET /api/r26/hod/batches?status=${status}`.
   - Handled by: `loadBatches(status)`.
   - Response Payload: Array of batch objects with `classroom_id`, `batch_year`, `current_semester`, `student_count`, `tutor_name`, `mentor_name`, `is_r26`, `subjects: [{ subject_code, subject_name, staff_list, progress }]`.
2. **Fetch Today's Seminars:**
   - Endpoint: `GET /api/lecturer/today-seminars`.
   - Handled by: `checkTodaySeminars()`.
3. **Change Batch Semester Prompt:**
   - Endpoint: `POST /api/hod/batches/${classroomId}/update-semester`.
   - Handled by: `changeBatchSemesterPrompt(classroomId, currentSem)`.
4. **Create Batch:**
   - Modal: `createBatchModal` $\rightarrow$ Handled by `submitCreateBatch(e)`.
5. **Manage Batch Drawer:**
   - Modal: `batchDetailModal` $\rightarrow$ Handled by `openBatchDetail(batch)`.

---

## 3. Server-Side Variables Used
* `$activeBranch`: Department identifier (e.g. `'CT'`, `'ME'`, `'CE'`, `'EL'`) resolved via `$branchOverride ?? session('userBranch')`.
* `$isPrincipalMode`: Boolean indicating executive Principal view mode (`isset($isPrincipalView) && $isPrincipalView`).
* `session('userName')`, `session('userRole')`, `session('userPhoto')`.

---

## 4. Current UI Pain Points & Migration Opportunities
1. **Dark/Inconsistent Card Backgrounds**: Legacy batch cards use dark `#020617` (Slate 950) surfaces with harsh green/purple borders and glows (`shadow-[0_0_20px_rgba(16,185,129,0.25)]`), clashing with the modern light design system.
2. **Typography Scale**: Labels use `text-[10px]` and `text-[11px]` inside badges and progress indicators.
3. **Action Button Styling**: Filter toggles and "Create Batch" buttons use legacy gradient classes rather than design system tokens.
4. **Information Density**: Department overview stats (e.g. active batch count, total department strength) are implicit rather than summarized clearly.

---

## 5. Preservation & Modernization Contract
* **Rule 1:** Retain 100% of DOM IDs (`panelBatches`, `seminarNotificationsContainer`, `btnHodFilterActive`, `btnHodFilterHistorical`, `batchGlobalAlert`, `batchCardsGrid`, `batchEmptyState`).
* **Rule 2:** Modernize `renderBatchCard(batch)` in JavaScript to output clean `#FFFFFF` surfaces with subtle `border-slate-200`, crisp token badges, solid high-contrast progress bars, and minimum $\ge 14\text{px}$ typography.
* **Rule 3:** Preserve all onclick event listeners (`changeBatchSemesterPrompt`, `openBatchDetail`, `openCreateBatchModal`, `loadBatches`).
* **Rule 4:** Zero modifications to other HOD panels, modals, controllers, or API contracts.
