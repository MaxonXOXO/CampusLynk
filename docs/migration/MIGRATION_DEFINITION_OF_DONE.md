# Migration Definition of Done (DoD)

> **Principle:** Visual accessibility is **NOT** completion.  
> A feature is only considered "Done" when it is fully integrated into CampusLynk's architectural system, properly secured, thoroughly tested, and accompanied by verifiable execution evidence.

---

## 1. Migration Unit Completion Checklist

Every migration unit must satisfy all items in this checklist before it can be marked as complete in `STATE.json` and committed to the migration branch:

- [ ] **Correct migration unit identified:** Unit is explicitly defined in `FEATURE_MATRIX.md` with bounded scope.
- [ ] **Legacy behavior inspected:** All relevant legacy controllers, models, views, and data structures have been read and understood.
- [ ] **CampusLynk architecture inspected:** Target layouts (`workspace-layout`, `faculty-shell`), design tokens, and existing components identified for reuse.
- [ ] **Dependencies identified:** Downstream and upstream dependencies (models, services, auth roles, database tables) are mapped.
- [ ] **Database requirements verified:** Required migrations, tables, columns, indexes, and constraints exist and match expectations in `carmel_linx_db`.
- [ ] **Authorization requirements verified:** Role-based middleware (`RoleMiddleware`, gate definitions, staff profile validation) is correctly applied.
- [ ] **Implementation completed:** Models, controllers, services, and views are cleanly implemented without legacy anti-patterns.
- [ ] **Validation implemented:** Form requests or request validation rules prevent malformed input, missing fields, or unauthorized mark updates.
- [ ] **Relevant tests added:** Unit or feature tests written covering key happy paths and boundary/error cases.
- [ ] **Existing tests remain passing:** The existing test suite runs with zero unexpected regressions.
- [ ] **Routes verified:** All newly defined routes resolve correctly via `php artisan route:list` with correct HTTP verbs and middleware.
- [ ] **Models/relationships verified:** Eloquent relationships (`belongsTo`, `hasMany`, etc.) and casts are tested and functioning.
- [ ] **UI uses CampusLynk architecture:** Blade views use `<x-layouts.*>`, `<x-ui.*>` components, local Tailwind classes, and Lucide icons without external CDN dependencies or raw inline `<style>` tags.
- [ ] **Print/export behavior verified where applicable:** If the feature involves A4 reports, registers, or PDF/CSV export, formatting and styling are verified.
- [ ] **No unrelated files changed without justification:** `git status` and `git diff` reveal zero unintended modifications to unrelated files.
- [ ] **Diff reviewed:** Full patch is scrutinized for performance pitfalls, memory leaks, security vulnerabilities, or dead code.
- [ ] **Verification evidence recorded:** Automated test outputs, HTTP status responses, or screenshots logged in the unit audit record.
- [ ] **Git checkpoint created:** Changes committed with an atomic, descriptive commit message following Conventional Commits format (`feat(unit): description`).
- [ ] **Migration state updated:** `docs/migration/STATE.json` updated with unit completion status, timestamp, and commit SHA.

---

## 2. Why "Visually Accessible" Is Not Sufficient

A page rendering without a 500 error does **not** signify a completed migration. Common failure modes that render a feature incomplete despite passing visual inspection:

1. **Hardcoded Mock Data:** The view appears populated, but data is mocked or static rather than bound to Eloquent models.
2. **Missing Input Validation:** Submitting empty or out-of-range marks causes unhandled database exceptions.
3. **Broken Authorization:** An unauthorized faculty member or student can access a confidential assessment screen.
4. **Missing Export/Print CSS:** A dashboard looks correct on a browser window, but produces illegible, unpaginated output when printed to A4.
5. **Architectural Contamination:** A legacy monolithic script is pasted directly, bringing CDN dependencies and conflicting global styles into CampusLynk.

---

## 3. Verification Artifacts

Every completed migration unit must yield verifiable artifacts:
- **Git Commit SHA:** A specific commit on `migration-alpha`.
- **Test Results Log:** Output of test command execution (`php artisan test --filter=<TestClass>`).
- **Route Proof:** Route listing output confirming registration and middleware binding.
