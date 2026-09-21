# Migration Unit Specification Standard

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Active Specification  
> **Version:** 0.2.0  

This document defines the specification standard for **Migration Units**, the atomic execution building blocks of the migration system.

---

## 1. Concepts: Feature vs. Migration Unit

To maintain system stability, prevent regression, and ensure verifiable outcomes, the migration system enforces a strict two-tier hierarchy:

```text
┌────────────────────────────────────────────────────────┐
│ FEATURE: R21 Major Project (r21_major_project)         │
│ High-level domain capability consisting of multiple    │
│ controllers, views, database tables, and print reports.│
└──────────────────────────┬─────────────────────────────┘
                           │
         ┌─────────────────┼─────────────────┐
         ▼                 ▼                 ▼
   ┌───────────┐     ┌───────────┐     ┌───────────┐
   │ UNIT M2.1 │     │ UNIT M2.2 │     │ UNIT M2.3 │
   │ Schema &  │     │ Backend   │     │ Frontend  │
   │ Models    │     │ Controller│     │ Workspace │
   │ Parity    │     │ & Routes  │     │ & Layout  │
   └───────────┘     └───────────┘     └───────────┘
```

- **Feature:** A domain-level functional module (e.g., *R21 Major Project*, *HOD Program Attainment*, *Carmie AI Assistant*). A feature is never migrated in a single unmanageable step.
- **Migration Unit:** The smallest independently implementable, testable, and verifiable vertical slice of work (e.g., *M2.1: R21 Major Project Schema & Models Parity*). Each unit must independently satisfy the `MIGRATION_DEFINITION_OF_DONE.md`.

---

## 2. Standard Schema (YAML / JSON)

Every migration unit specification must conform to the following schema:

```yaml
# Unique identifier (Convention: M<Phase>.<UnitNumber>)
id: M2.1

# Parent feature identifier (from FEATURE_MATRIX.md)
feature: r21_major_project

# Descriptive title
title: R21 Major Project Schema and Eloquent Models Parity

# Lifecycle state (must be a valid STATE_MACHINE.md state)
state: NOT_STARTED

# Priority level: critical | high | medium | low
priority: critical

# Upstream unit IDs that must be in COMPLETED state before this unit starts
dependencies:
  - M1.1

# Explicit list of legacy reference files to inspect (READ-ONLY)
legacy_sources:
  - app/Models/R21MajorProjectCourseFile.php
  - app/Models/R21MajorProjectEvaluation.php
  - database/migrations/2026_09_12_000001_create_r21_major_project_tables.php

# Explicit list of files permitted to be created or modified in target
target_files:
  created:
    - app/Models/R21MajorProjectCourseFile.php
    - app/Models/R21MajorProjectEvaluation.php
    - database/migrations/2026_09_12_000001_create_r21_major_project_tables.php
  modified: []

# Database requirements
database:
  required: true
  tables:
    - r21_major_project_course_files
    - r21_major_project_evaluations
  verification_query: "SHOW TABLES LIKE 'r21_major_project_%'"

# New or modified route endpoints
routes: []

# Required roles or permissions
permissions:
  roles:
    - HOD
    - Lecturer
  middleware:
    - auth
    - role:HOD,Lecturer

# Verifiable acceptance criteria
acceptance_criteria:
  - "Migration file 2026_09_12_000001 matches legacy schema definition."
  - "Eloquent models R21MajorProjectCourseFile and R21MajorProjectEvaluation define correct casts and relations."
  - "Model relationships to User, Classroom, and BatchSubject resolve without error."

# Automated verification requirements
verification:
  tests:
    - tests/Unit/Models/R21MajorProjectModelsTest.php
  commands:
    - "php artisan test --filter=R21MajorProjectModelsTest"

# Rollback plan if verification fails
rollback:
  strategy: git_revert
  files_to_remove:
    - app/Models/R21MajorProjectCourseFile.php
    - app/Models/R21MajorProjectEvaluation.php
    - database/migrations/2026_09_12_000001_create_r21_major_project_tables.php

# Execution tracking
attempts: 0
max_attempts: 3

# Timestamps (ISO 8601)
created_at: "2026-09-21T17:40:00Z"
updated_at: null
completed_at: null

# Checkpoint commits
implementation_commit: null
verification_commit: null

# Detailed failure logs for repair loops
failure_history: []
```

---

## 3. Field Definitions & Validation Rules

| Field | Type | Required? | Validation / Description |
| :--- | :--- | :---: | :--- |
| `id` | `string` | **Yes** | Unique key. Regex: `^M[0-9]+\.[0-9]+$` (e.g. `M1.1`, `M2.1`). |
| `feature` | `string` | **Yes** | Must match a valid feature key in `STATE.json` / `FEATURE_MATRIX.md`. |
| `title` | `string` | **Yes** | 10–100 characters describing the vertical slice. |
| `state` | `string` | **Yes** | Must be a valid enum from `STATE_MACHINE.md`. |
| `priority` | `enum` | **Yes** | One of: `critical`, `high`, `medium`, `low`. |
| `dependencies`| `string[]` | **Yes** | Array of unit IDs that must be `COMPLETED` before `ANALYZING`. |
| `legacy_sources` | `string[]` | **Yes** | File paths in legacy repo. Must exist in legacy repository. |
| `target_files` | `object` | **Yes** | Must contain `created` and `modified` arrays. Implementer is restricted to this set. |
| `database` | `object` | **Yes** | Contains `required` boolean, `tables` list, and optional verification query. |
| `routes` | `object[]` | **Yes** | List of route definitions `{ method, uri, name, action }`. |
| `permissions` | `object` | **Yes** | Required roles, gates, and middleware. |
| `acceptance_criteria` | `string[]` | **Yes** | Unambiguous checklist of required functional behaviors. |
| `verification` | `object` | **Yes** | Test file paths and shell commands to execute for proof. |
| `rollback` | `object` | **Yes** | Explicit recovery strategy if changes must be unwound. |
| `attempts` | `integer` | **Yes** | Number of execution attempts. Initial: `0`. |
| `max_attempts` | `integer` | **Yes** | Max permitted attempts before mandatory escalation (Default: `3`). |
| `timestamps` | `datetime` | **Yes** | `created_at`, `updated_at`, `completed_at` in ISO 8601 format. |
| `commits` | `string` | No | Commit hashes on `migration-alpha` when checkpointed. |
| `failure_history` | `object[]` | **Yes** | Array of `{ attempt, timestamp, error, diagnostic }` records. |

---

## 4. Example Unit Specifications

### Example 1: Schema Parity Unit
```yaml
id: M1.1
feature: database_schema
title: Database Migrations and Core Eloquent Models Parity
state: NOT_STARTED
priority: critical
dependencies: []
legacy_sources:
  - database/migrations/2026_09_10_000001_create_r21_drawing_tables.php
  - database/migrations/2026_09_10_232359_create_program_attainments_table.php
  - database/migrations/2026_09_12_000001_create_r21_major_project_tables.php
target_files:
  created:
    - database/migrations/2026_09_10_000001_create_r21_drawing_tables.php
    - database/migrations/2026_09_10_232359_create_program_attainments_table.php
    - database/migrations/2026_09_12_000001_create_r21_major_project_tables.php
    - app/Models/ProgramAttainment.php
    - app/Models/R21DrawingCourseFile.php
    - app/Models/R21MajorProjectEvaluation.php
  modified: []
database:
  required: true
  tables:
    - program_attainments
    - r21_drawing_course_files
    - r21_major_project_evaluations
acceptance_criteria:
  - "All 10 missing migrations copied to target database/migrations/."
  - "All 8 missing Eloquent models created with proper table bindings."
verification:
  tests:
    - tests/Unit/MigrationSchemaParityTest.php
  commands:
    - "php artisan test --filter=MigrationSchemaParityTest"
```

### Example 2: Backend Controller & API Unit
```yaml
id: M2.2
feature: r21_major_project
title: R21 Major Project Controller and Evaluation Endpoints
state: NOT_STARTED
priority: critical
dependencies:
  - M1.1
  - M2.1
legacy_sources:
  - app/Http/Controllers/R21VirtualClassroomMajorProjectController.php
target_files:
  created:
    - app/Http/Controllers/R21VirtualClassroomMajorProjectController.php
  modified:
    - routes/web.php
database:
  required: true
  tables:
    - r21_major_project_evaluations
routes:
  - method: GET
    uri: /r21/classroom/project/{subjectId}
    action: R21VirtualClassroomMajorProjectController@index
  - method: POST
    uri: /r21/classroom/project/{subjectId}/save-evaluation
    action: R21VirtualClassroomMajorProjectController@saveEvaluation
acceptance_criteria:
  - "R21VirtualClassroomMajorProjectController ported with modern validation."
  - "Group CIA and ESE marks save properly with JSON validation."
verification:
  tests:
    - tests/Feature/R21MajorProjectControllerTest.php
  commands:
    - "php artisan test --filter=R21MajorProjectControllerTest"
```
