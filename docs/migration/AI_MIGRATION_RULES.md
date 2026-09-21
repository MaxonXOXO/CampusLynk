# Permanent AI Migration Rules

> **Status:** MANDATORY & ENFORCED  
> Every autonomous agent, human engineer, or automated system contributing to the CampusLynk migration must strictly comply with these rules. No exceptions are permitted without an approved Architecture Decision Record (ADR).

---

## 1. Repository Rules

1. **Protected `main` Branch:** Never modify, commit to, rebase, or reset `main` directly. All migration work must occur within isolated branches (e.g., `migration-alpha` or dedicated migration unit sub-branches).
2. **Preserve History:** Never rewrite, rebase, squash, or reset unrelated project Git history. Commits on migration branches must be atomic, descriptive, and forward-moving.
3. **Strict Unit Isolation:** Never modify unrelated features, routes, controllers, or views while executing a migration unit. Scope creep is strictly prohibited.
4. **No Unwarranted Refactoring:** Never perform broad refactoring of existing, working CampusLynk modules unless explicitly declared in the unit migration specification.
5. **Legacy is Read-Only Reference:** The legacy repository (`academic-platform`) is strictly a behavioral and reference source. It must NEVER be treated as a Git submodule, package dependency, or runtime inclusion in CampusLynk.

---

## 2. Architecture & Design Rules

1. **Preserve CampusLynk Architecture:** The target application's design system, layout kit, and coding standards supersede legacy patterns in all scenarios.
2. **Master Shell Reuse:** All newly migrated user-facing pages must be mounted inside CampusLynk's Master Shell layouts:
   - `<x-layouts.workspace-layout>` for virtual classrooms, grading desks, and evaluation workspaces.
   - `<x-layouts.faculty-shell>` or `<x-layouts.app-shell>` for faculty/staff consoles and dashboards.
3. **No Legacy Navigation Ingestion:** Never copy legacy top navigation bars, monolithic headers, legacy sidebar templates, or hardcoded link arrays. All navigation must integrate with `NavigationService` and `config/navigation/`.
4. **No Monolithic Blade Views:** Do not copy monolithic legacy Blade files (e.g. 2,000–7,000 lines). Break down large views into clean, maintainable partials and atomic UI components (`<x-ui.button>`, `<x-ui.badge>`, `<x-ui.modal>`, `<x-ui.table>`, etc.).
5. **Extract Business Logic:** Extract heavy calculation logic (attainment scoring, mark normalization, attendance sync) from controllers into dedicated Service classes under `app/Services/`.
6. **Preserve Domain Behavior:** Preserve the mathematical rules, grading scales, assessment rubrics, and SBTE compliance behavior of the legacy application unless a migration specification explicitly updates them.

---

## 3. Database & Persistence Rules

1. **Never Test on Production:** Never execute automated tests or unverified migration runs against a production database.
2. **Zero `migrate:fresh` in Production:** Never run `php artisan migrate:fresh`, `db:wipe`, or drop tables in any shared, staging, or production database.
3. **No Destructive SQL:** Never execute destructive SQL statements (`DROP TABLE`, `TRUNCATE`, `DROP COLUMN`) without explicit human escalation and recorded authorization.
4. **Immutable Historical Migrations:** Never modify an already-executed historical migration file to fix schema issues. Always write a new, sequential migration file.
5. **Explicit Schema Verification:** Always inspect and verify the physical database schema (`SHOW TABLES`, `DESCRIBE <table>`) before writing Eloquent models, relationships, or queries. Do not assume columns exist.
6. **Seeders & Fixtures:** Provide isolated database seeders or test factories for newly migrated models to ensure automated verification without polluting persistent data.

---

## 4. Autonomous Agent Execution Rules

1. **One Bounded Migration Unit at a Time:** An agent must never work on multiple vertical slices simultaneously. Complete, verify, and commit Unit $N$ before starting Unit $N+1$.
2. **Inspect Before Modifying:** Always inspect both the legacy reference code and the existing CampusLynk target code before writing a single line of code.
3. **Plan Before Implementation:** Generate a concise, structured implementation plan detailing files to create, files to modify, routes to register, and verification steps.
4. **Test After Implementation:** Every migration unit must be verified with automated tests (PHPUnit/Pest), endpoint verification, or deterministic CLI inspection.
5. **No Self-Certification Without Evidence:** An agent cannot declare a migration unit complete without providing verifiable evidence (test execution outputs, HTTP status codes, route check outputs).
6. **Stop on Ambiguity:** If requirements, database types, or role permissions are unclear or conflicting, STOP and request human clarification.
7. **Stop on Unrelated Regressions:** If existing CampusLynk features or tests fail unexpectedly as a side effect, STOP immediately, isolate the regression, and resolve it before proceeding.
8. **Loop Prevention & Escalation:** If an implementation or test cycle fails more than three (3) consecutive attempts, the agent must STOP and escalate to human review rather than continuing to loop indefinitely.
