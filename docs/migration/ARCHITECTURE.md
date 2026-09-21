# Autonomous Migration System Architecture

> **Notice:** This document serves as the **architectural contract** for the planned autonomous migration system. The agents and orchestrator described herein are specifications to be implemented in subsequent phases.

---

## 1. System Topology & Conceptual Model

The autonomous migration system operates as a stateful, feedback-driven pipeline that ingests legacy domain features, translates them into the target architecture, implements the code changes, verifies functional correctness, and records atomic checkpoints.

```text
                    LEGACY REPOSITORY
                           |
                        Scanner
                           |
                           v
                 Migration Knowledge
                       / State
                           |
                      Supervisor
                           |
                      Task Planner
                           |
                    Migration Unit
                           |
                       Architect
                           |
                  Implementation Spec
                           |
                      Implementer
                           |
                          Diff
                           |
                       Verifier
                      /         \
                   PASS         FAIL
                    |             |
                  Commit        Repair
                    |             |
                    +------<------+
                           |
                       Next Unit
```

---

## 2. Core Autonomous Agent Roles

### 2.1 Scanner (Knowledge & Delta Extraction)
- **Responsibility:** Deeply analyzes the legacy codebase (`academic-platform`), extracting AST syntax trees, database queries, route declarations, form schemas, validation rules, and business logic.
- **Inputs:** Legacy repository files (`app/`, `resources/views/`, `database/migrations/`, `routes/`).
- **Outputs:** Structured behavioral feature specifications, dependency graphs, and schema requirements stored in the Migration Knowledge repository.
- **Constraints:** Read-only access to legacy. Never alters legacy code.

### 2.2 Supervisor (Control Plane & State Orchestration)
- **Responsibility:** Maintains persistent state in `STATE.json`, determines sequence and scheduling of migration units based on dependency trees, detects cycles or regressions, and triggers human escalation.
- **Inputs:** `FEATURE_MATRIX.md`, `STATE.json`, Verifier results.
- **Outputs:** Next actionable Migration Unit dispatched to Task Planner / Architect.
- **Constraints:** Enforces loop-prevention thresholds (maximum 3 repair cycles before stopping).

### 2.3 Architect (Target System Design & Component Mapping)
- **Responsibility:** Translates legacy specifications into clean, modern CampusLynk designs. Maps legacy monolithic views to CampusLynk Master Shells (`workspace-layout`, `faculty-shell`), identifies reusable atomic UI components, defines clean Eloquent model relationships, and designs dedicated Service classes.
- **Inputs:** Migration Unit specification + CampusLynk architecture rules.
- **Outputs:** Comprehensive, file-by-file Implementation Specification (including routes, controllers, services, Blade partials, and test requirements).

### 2.4 Implementer (Code Synthesis & Integration)
- **Responsibility:** Writes the code in the target repository (`CampusLynk/carmel-linx-laravel`) strictly according to the Architect's Implementation Specification.
- **Inputs:** Implementation Specification + CampusLynk codebase.
- **Outputs:** Code changes (diff) across models, migrations, controllers, services, views, routes, and test suites.
- **Constraints:** Adheres strictly to `AI_MIGRATION_RULES.md`. Zero modifications to unrelated files.

### 2.5 Verifier (Automated Testing & Quality Gate)
- **Responsibility:** Validates the implementation against the `MIGRATION_DEFINITION_OF_DONE.md`. Executes automated test suites, performs syntax linting, verifies route registration, inspects Eloquent relationships, and ensures no regressions occurred.
- **Inputs:** Code diff + test suites + database state.
- **Outputs:** Verifier Report with PASS / FAIL determination and failure diagnostics.
- **Actions:**
  - **On PASS:** Dispatches commit approval to Supervisor to create an atomic Git checkpoint.
  - **On FAIL:** Feeds structured diagnostic error traces back to Implementer/Architect for bounded repair.

---

## 3. Persistent State & Storage Model

The migration engine is designed to be fully stateful across restarts and context compactions:
- **`STATE.json`:** Ground-truth machine state documenting current phase, active unit, completed unit history, and health indicators.
- **`FEATURE_MATRIX.md`:** Living documentation of feature status, priority, and dependency linkages.
- **`DECISIONS.md`:** Architectural decision records ensuring design choices remain consistent over long multi-unit migration runs.
- **Git Commits:** Every approved unit produces an immutable commit on `migration-alpha`, enabling instant rollbacks if unexpected issues arise later.
