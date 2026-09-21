# Git Checkpoint & State Commitment Protocol

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Active Protocol  
> **Version:** 0.2.0  

This document defines the strict checkpointing workflow that turns verified code into an immutable, recorded Git commit and updates the persistent migration state.

---

## 1. Checkpoint Workflow

A migration unit cannot be marked `COMPLETED` merely because code has been written. It must progress through the complete seven-stage checkpoint pipeline:

```text
    ┌───────────────┐
    │   IMPLEMENT   │ ◄── Implementer modifies code in target repo
    └───────┬───────┘
            │
            ▼
    ┌───────────────┐
    │    VERIFY     │ ◄── Verifier executes tests, route checks, DoD review
    └───────┬───────┘
            │
            ▼
    ┌───────────────┐
    │     PASS      │ ◄── Verifier issues verified PASS evidence
    └───────┬───────┘
            │
            ▼
    ┌───────────────┐
    │  REVIEW DIFF  │ ◄── Supervisor confirms only whitelisted files modified
    └───────┬───────┘
            │
            ▼
┌───────────────────────┐
│ CREATE GIT CHECKPOINT │ ◄── Supervisor stages whitelisted files and commits
└───────────┬───────────┘
            │
            ▼
    ┌───────────────┐
    │ RECORD COMMIT │ ◄── Commit SHA captured from git rev-parse HEAD
    └───────┬───────┘
            │
            ▼
    ┌───────────────┐
    │ UPDATE STATE  │ ◄── STATE.json and FEATURE_MATRIX.md updated to COMPLETED
    └───────────────┘
```

---

## 2. Commit Message Convention

All checkpoint commits must strictly follow the conventional commit structure:

```text
migration(<unit-id>): <short imperative description>

- Feature: <feature_id>
- Unit: <unit_id> (<unit_title>)
- Verification: PASS (<test_count> tests, <assertion_count> assertions)
- Evidence: <test_class_or_command_output_reference>
```

### Examples
```text
migration(M1.1): add database schema parity migrations and core models

- Feature: database_schema
- Unit: M1.1 (Database Schema Migrations and Eloquent Models Parity)
- Verification: PASS (10 migrations verified, 8 models verified)
- Evidence: tests/Unit/MigrationSchemaParityTest.php
```

```text
migration(M2.1): implement R21 major project controller and evaluation endpoints

- Feature: r21_major_project
- Unit: M2.1 (R21 Major Project Backend Controller and Evaluation Endpoints)
- Verification: PASS (6 tests, 18 assertions)
- Evidence: tests/Feature/R21MajorProjectControllerTest.php
```

---

## 3. Rollback & Revert Strategy

If a regression is discovered downstream after a unit has been checkpointed:
1. **Isolated Unit Revert:** The Supervisor or human reviewer executes:
   ```bash
   git revert <commit-sha> --no-edit -m "revert(migration): rollback unit <unit-id> due to <reason>"
   ```
2. **State Adjustment:**
   - The unit's state in `STATE.json` is moved from `COMPLETED` to `FAILED` or `ANALYZING`.
   - An audit trail entry documenting the revert SHA and rationale is appended to `history`.
   - Dependent downstream units are recursively set to `BLOCKED`.
