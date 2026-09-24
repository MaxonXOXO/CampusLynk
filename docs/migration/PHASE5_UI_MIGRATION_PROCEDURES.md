# CampusLynk Autonomous Migration — Phase 5 UI/UX Reconciliation Procedures

**Document Version:** 1.0.0  
**Target Branch:** `migration-alpha`  
**Reference Legacy Commit:** `legacy-academic-platform/main` @ `70b11740`  
**Scope:** Reconcile late frontend design and interactive features across Virtual Classrooms (Theory, Lab, Drawing, Practicum) without regressing CampusLynk's modernized Blade component architecture.

---

## 1. Architectural Guardrails
1. **Master Shell Preservation:**
   - Decomposed views (e.g. `r26_practicum`, `r21_drawing`) MUST NOT be overwritten with monolithic legacy code.
   - All interactive components must be grafted cleanly into either the appropriate layout template or dedicated partials.
2. **Global Component Reuse:**
   - Leverage `<x-ui.*>` components (`<x-ui.badge>`, `<x-ui.modal>`, `<x-ui.button>`) where applicable.
3. **Responsive & High-Density Standards:**
   - Adhere to the compact evaluation table guidelines (text-xs / 0.72rem, 22px inputs, high contrast) optimized for classes of 50–60 students.
4. **Debounced Autosave & User Feedback:**
   - Mark and grade entries must implement 800ms debouncing, clear visual state indicators (saving/saved badge), and graceful error fallbacks.

---

## 2. Granular Migration Slices

### Slice 1: Virtual Classroom Theory (`r26/virtual_classroom_theory.blade.php`)
- **Objective:** Reconcile SBTE Grade selection, synchronized 60M scaling, lesson plan row deletion, and tab navigation enhancements.
- **Tasks:**
  1. **SBTE Grade & Mark Entry Dual Mode:**
     - Add `<select class="ese-grade-select">` with options `S`, `A`, `B`, `C`, `D`, `E`, `F`, `FE` to the ESE evaluation table.
     - Implement `SBTE_GRADE_SCALE` mapping:
       - `S` = 57.0 (95%), `A` = 51.0 (85%), `B` = 45.0 (75%), `C` = 39.0 (65%), `D` = 33.0 (55%), `E` = 27.0 (45%), `F`/`FE` = 0.0.
     - Add bidirectional scaling event listeners (`calculateEseRowFromGrade`, `calculateEseRowFromMarks`).
     - Wire up debounced autosave (`saveEseMarks(true)`) targeting `/api/r26/classroom/{subjectId}/ese-marks/bulk-update` with payload `{ marks, grades, entry_mode }`.
  2. **Tab Title Alignment:**
     - Update tab header from "Continuous Attendance" to "Attendance & Subject Log" with direct link to the common class register.
  3. **Custom Lesson Plan Row Deletion:**
     - Add trash icon button (`deleteLessonPlanRow`) with modal confirmation on non-default syllabus rows calling `DELETE /api/r26/classroom/{subjectId}/lesson-plans/{planId}`.
  4. **QP Action Button Distinction:**
     - Decouple status display pill (`"Status: Draft"`) from the primary action button (`"Build QP"`).

### Slice 2: Virtual Lab Practical (`virtual_classroom_practical.blade.php`)
- **Objective:** Reconcile lab batch split modal, multi-experiment selection, stacked past logs, and in-place sync.
- **Tasks:**
  1. **Lab Batch Setup Integration:**
     - Integrate `@include('partials.lab_batch_setup_modal')` and `@include('partials.lab_batch_setup_scripts')` into the classroom top navigation and action toolbar.
  2. **Multi-Experiment Badges:**
     - Add multi-experiment selection chip list to the attendance and evaluation modals for handling parallel experiments in split lab hours.
  3. **Stacked Past Log Cards:**
     - Integrate accordion/stacked card UI for browsing and verifying past conducted experiments and attendance logs.
  4. **In-Place Sync Log Dates:**
     - Implement AJAX refresh button to trigger `POST /api/classroom-practical/{batchSubjectId}/sync-lesson-plan-dates` and dynamically refresh planner dates without reloading the full page.

### Slice 3: Virtual Drawing Hall (`r26_drawing` & `r21_drawing`)
- **Objective:** Reconcile dynamic exercise modal, fullscreen toggle, and high-density evaluation tables.
- **Tasks:**
  1. **Dynamic Exercise Management:**
     - Add `#addExerciseModal` trigger and form allowing instructors to define custom drawing sheets/exercises with title, CO mapping, and max marks.
  2. **Fullscreen Viewport Toggle:**
     - Add `#toggleFullscreenBtn` with screen expansion handler for grading complex engineering sheets.
  3. **High-Density Compact Typography:**
     - Apply universal compact table styling (`0.72rem` font size, `22px` inputs, high-contrast register/roll number badges) to avoid horizontal overflow on 50–60 student sheets.

### Slice 4: R26 Practicum (`r26_practicum`)
- **Objective:** Reconcile Table 2.2 and Table 3.1 evaluator modals and basic science integration.
- **Tasks:**
  1. **Table 2.2 & Table 3.1 Evaluator Modals:**
     - Integrate fullscreen evaluator modal triggers and mark entry matrices into `r26_practicum/partials/tab-lab-eval.blade.php` and `partials/modal-ese.blade.php`.
     - Implement debounced autosave handlers for CE 40M and ESE 60M mark sheets.
  2. **Basic Science Practicum Integration:**
     - Verify navigation routing between program core practicum and `virtual_classroom_basic_science_practicum.blade.php`.

### Slice 5: Full Pre-Merge Smoke Test & Wave 6 Closeout
- **Objective:** Final end-to-end verification and formal completion sign-off.
- **Tasks:**
  1. Run complete PHPUnit test suite (`php artisan test`).
  2. Validate Blade syntax compilation across all modified views (`php artisan view:cache` or custom blade linter).
  3. Execute automated route parity verification.
  4. Dispatch final Wave 6 Closeout report to the ChatGPT Architect via the bridge.

---

## 3. Verification & Acceptance Criteria
- Every modified view compiles with zero Blade syntax errors.
- All autosave calls return HTTP 200 with `{ success: true }`.
- PHPUnit regression suite maintains 100% pass rate with zero failures.
- Zero monolithic rollbacks: CampusLynk layout shells and partials remain cleanly isolated.
