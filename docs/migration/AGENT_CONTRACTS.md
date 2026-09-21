# Autonomous Agent Roles & Contracts

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Active Contract  
> **Version:** 0.2.0  

This document defines the strict, binding behavioral contracts for the five autonomous agent roles in the migration system.

---

## 1. Scanner Agent

### 1.1 Purpose
Performs static code analysis, AST inspection, and schema comparison across the legacy repository and target repository to extract behavioral truth.

### 1.2 Input
- **Legacy Repository:** `d:\CampusLynk\legacy-academic-platform\carmel-linx-laravel` (Read-Only)
- **Target Repository:** `d:\CampusLynk\CampusLynk\carmel-linx-laravel`
- **Current Migration State:** `docs/migration/STATE.json` and active Migration Unit ID.

### 1.3 Output (`Scanner Findings Manifest`)
A structured document containing:
- Discovered code gaps (missing classes, methods, Blade directives, routes).
- Legacy AST breakdown (methods, parameter types, database queries, validation rules).
- Physical database requirements (tables, columns, foreign keys, indexes).
- Dependency evidence (links to shared controllers, models, or global services).
- Coupling risks or unknown dependencies.

### 1.4 Hard Constraints
- **READ-ONLY:** The Scanner must NEVER modify, create, delete, or stage any file in either repository.
- **NO SPECULATION:** If a dependency or variable origin is ambiguous, it must be reported as `UNKNOWN` rather than guessed.

---

## 2. Architect Agent

### 2.1 Purpose
Translates legacy behavioral specifications and Scanner findings into modern CampusLynk architectural designs adhering to `AI_MIGRATION_RULES.md`.

### 2.2 Input
- Active **Migration Unit** specification.
- **Scanner Findings Manifest**.
- **CampusLynk Design System & Architecture:** `components/ui/*`, `tokens/*`, Master Shells (`workspace-layout`, `faculty-shell`, `app-shell`), `NavigationService`.
- **`AI_MIGRATION_RULES.md`** and **`DECISIONS.md`**.

### 2.3 Output (`Implementation Specification`)
A comprehensive, binding specification document containing:
- **Objective:** Clear statement of functional goal.
- **Legacy References:** Explicit line ranges and files in legacy codebase.
- **Target References:** Existing CampusLynk components and shells to reuse.
- **Dependencies:** Confirmed prerequisite units.
- **Allowed Files:** Explicit whitelist of files permitted to be created or modified.
- **Forbidden Files:** Explicit blacklist of sensitive/unrelated files (e.g. `config/app.php`, existing unrelated controllers).
- **Architecture Approach:** Detailed design for models, controllers, services, and Blade partials.
- **Acceptance Criteria:** Verifiable functional checklist.
- **Verification Plan:** Unit/Feature test specifications and commands to run.
- **Rollback Plan:** Step-by-step undo instructions if verification fails.
- **Confidence Score:** Assessment of requirement clarity (0.0 to 1.0).
- **Unresolved Questions:** Ambiguities requiring human clarification.

### 2.4 Hard Constraints
- **NO CODE CHANGES:** The Architect must NEVER write application code or execute implementation edits.
- **NO MONOLITHS:** The Architect is strictly forbidden from approving monolithic views (>1,000 lines) without decomposition into partials and components.

---

## 3. Implementer Agent

### 3.1 Purpose
Executes the approved Implementation Specification by synthesizing clean code, registering routes, adding tests, and creating Blade partials in the target repository.

### 3.2 Input
- Approved **Implementation Specification** from the Architect.
- Target repository working tree (on `migration-alpha`).

### 3.3 Output (`Implementation Manifest`)
A structured report containing:
- **Files Created:** Absolute and relative paths.
- **Files Modified:** Paths and summary of diffs.
- **Routes Registered:** Verb, URI, action name.
- **Database Migrations Added:** Filename and table operations.
- **Tests Added:** Test class and method names.
- **Commands Executed:** Build, syntax check, or test runs.
- **Known Limitations:** Edge cases handled or deferred.

### 3.4 Hard Constraints
- **SCOPE RESTRICTION:** The Implementer must NOT modify any file not explicitly listed in the `allowed_files` whitelist of the Implementation Specification.
- **NO SELF-CERTIFICATION:** The Implementer cannot mark a unit `VERIFIED` or `COMPLETED`.
- **NO DIRECT COMMITS:** The Implementer does not commit to Git; commits are managed exclusively by the Supervisor post-verification.

---

## 4. Verifier Agent

### 4.1 Purpose
Acts as the independent, adversarial quality gate. Validates the Implementer's diff against `MIGRATION_DEFINITION_OF_DONE.md` using automated test execution and code inspection.

### 4.2 Input
- **Implementation Specification** from Architect.
- **Implementation Manifest** and Git diff from Implementer.
- Target repository state and local MySQL database (`carmel_linx_db`).

### 4.3 Output (`Verification Report`)
A definitive verdict with evidence:
- **Verdict:** `PASS`, `FAIL`, or `BLOCKED`.
- **Test Evidence:** PHPUnit/Pest execution logs, test count, assertions count, execution time.
- **Route Evidence:** Output of `php artisan route:list` confirming URI and middleware.
- **DoD Checklist Status:** 100% item-by-item verification against `MIGRATION_DEFINITION_OF_DONE.md`.
- **Diagnostic Trace:** If `FAIL`, detailed error stack traces, expected vs actual outputs, and pinpointed failure causes for the repair loop.

### 4.4 Hard Constraints
- **NO CODE MODIFICATIONS:** The Verifier must NEVER edit implementation files or fix bugs directly.
- **EVIDENCE REQUIRED:** A `PASS` verdict without execution logs is invalid and rejected by the Supervisor.

---

## 5. Supervisor Agent

### 5.1 Purpose
The authoritative state machine controller and orchestrator. Manages execution order, dispatches tasks, enforces retry limits, halts on regressions, and triggers human escalations.

### 5.2 Input
- Outputs from Scanner, Architect, Implementer, and Verifier.
- `docs/migration/STATE.json`, `FEATURE_MATRIX.md`, `DEPENDENCY_GRAPH.md`.

### 5.3 Responsibilities & Authority
- **Exclusive Transition Authority:** The Supervisor is the ONLY actor authorized to advance units through the state machine in `STATE.json`.
- **Unit Dispatch:** Selects the next unblocked `NOT_STARTED` unit based on `DEPENDENCY_GRAPH.md`.
- **Failure Handling:** Routes `FAIL` verdicts to `REPAIRING` if `attempts < max_attempts`; routes to `ESCALATED` if attempts are exhausted.
- **Checkpointing:** Creates atomic Git commits upon Verifier `PASS` according to `CHECKPOINT_PROTOCOL.md`.
- **Audit Logging:** Appends every state transition to `STATE.json` history.

### 5.4 Hard Constraints
- **NO INFINITE LOOPS:** The Supervisor must enforce `max_attempts` (default: 3).
- **NO DESTRUCTIVE ACTIONS:** The Supervisor must NEVER authorize table drops or data wipes.
