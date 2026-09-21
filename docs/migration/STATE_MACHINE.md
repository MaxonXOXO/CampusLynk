# Migration State Machine Specification

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Active Specification  
> **Version:** 0.2.0  

This document defines the authoritative, deterministic lifecycle of migration features and migration units. No state transition may occur without satisfying the explicit rules, actors, and evidence requirements defined herein.

---

## 1. Lifecycle Overview

A migration unit progresses through a strictly sequential primary pipeline, with explicit branching for analysis blocks, implementation/verification failures, repairs, and human escalations.

```text
                     ┌───────────────┐
                     │  NOT_STARTED  │
                     └───────┬───────┘
                             │
                             ▼
                     ┌───────────────┐         ┌───────────┐
                     │   ANALYZING   ├────────►│  BLOCKED  │
                     └───────┬───────┘         └─────┬─────┘
                             │                       │
                             ▼                       ▼
                     ┌───────────────┐         ┌───────────┐
                     │   ANALYZED    │         │ ESCALATED │
                     └───────┬───────┘         └─────▲─────┘
                             │                       │
                             ▼                       │
                     ┌───────────────┐               │
                     │     READY     │               │
                     └───────┬───────┘               │
                             │                       │
                             ▼                       │
                     ┌───────────────┐               │
           ┌────────►│ IMPLEMENTING  │               │
           │         └───────┬───────┘               │
           │                 │                       │
           │                 ▼                       │
           │         ┌───────────────┐               │
           │         │  IMPLEMENTED  │               │
           │         └───────┬───────┘               │
           │                 │                       │
           │                 ▼                       │
           │         ┌───────────────┐               │
           │         │   VERIFYING   │               │
           │         └───────┬───────┘               │
           │                 │                       │
     ┌─────┴─────┐     ┌─────┴─────┐                 │
     │ REPAIRING │◄────┤  FAILED   ├─────────────────┘ (attempts >= max_attempts)
     └───────────┘     └─────┬─────┘
                             │
                             ▼ (on PASS)
                     ┌───────────────┐
                     │   VERIFIED    │
                     └───────┬───────┘
                             │
                             ▼
                     ┌───────────────┐
                     │ CHECKPOINTED  │
                     └───────┬───────┘
                             │
                             ▼
                     ┌───────────────┐
                     │   COMPLETED   │
                     └───────────────┘
```

### 1.1 Terminal & Paused States
- **`COMPLETED`:** Terminal success. All acceptance criteria satisfied, verified, committed, and recorded in state.
- **`CANCELLED`:** Terminal discard. The unit is deemed unnecessary or deprecated by human decision.
- **`DEFERRED`:** Paused. The unit is intentionally postponed to a later phase (e.g. low-priority supplementary modules).
- **`BLOCKED`:** Paused pending prerequisite resolution (e.g. missing upstream migration unit).
- **`ESCALATED`:** Paused awaiting explicit human review and resolution.

---

## 2. State Definitions

| State | Category | Description |
| :--- | :--- | :--- |
| `NOT_STARTED` | Initial | The unit is registered in `STATE.json` and `FEATURE_MATRIX.md` but work has not begun. |
| `ANALYZING` | Active | The Scanner and Architect agents are inspecting legacy reference code, target code, and dependencies. |
| `ANALYZED` | Milestone | Scanner and Architect have generated the Implementation Specification and mapped all requirements. |
| `BLOCKED` | Paused | Analysis reveals an unresolved upstream dependency, missing schema, or external roadblock. |
| `READY` | Milestone | Prerequisites are resolved, specification is approved by Supervisor, and the unit is queued for implementation. |
| `IMPLEMENTING` | Active | The Implementer agent is modifying/creating code in the target repository strictly within the specification scope. |
| `IMPLEMENTED` | Milestone | Code changes are complete; files, routes, and tests have been written and self-checked. |
| `VERIFYING` | Active | The Verifier agent is running automated tests, inspecting diffs, checking routes, and evaluating DoD criteria. |
| `VERIFIED` | Milestone | Verifier has issued a definitive `PASS` verdict with verifiable execution logs. |
| `FAILED` | Active Failure | Either implementation broke invariants, or verification returned a `FAIL` verdict. |
| `REPAIRING` | Active Recovery | Architect and Implementer are analyzing failure diagnostics to prepare a targeted fix (within attempt limits). |
| `ESCALATED` | Paused | Automated recovery exhausted (`attempts >= max_attempts`), or a forbidden/destructive operation was requested. |
| `CHECKPOINTED` | Milestone | A Git commit adhering to `CHECKPOINT_PROTOCOL.md` has been created on the migration branch. |
| `COMPLETED` | Terminal Success | State machine has updated `STATE.json` and `FEATURE_MATRIX.md` with commit SHA and audit record. |
| `DEFERRED` | Paused | Unit is postponed to a future milestone by architectural or human decision. |
| `CANCELLED` | Terminal Discard | Unit is permanently cancelled by human decision. |

---

## 3. Transition Matrix

Every transition must conform to the following table. Arbitrary or unlisted transitions are strictly forbidden.

| Transition ID | Source State | Target State | Authorized Actor | Required Evidence | Retry Allowed? | Human Approval Required? |
| :---: | :--- | :--- | :--- | :--- | :---: | :---: |
| **T01** | `NOT_STARTED` | `ANALYZING` | Supervisor | Upstream dependencies in `COMPLETED` state | Yes | No |
| **T02** | `ANALYZING` | `ANALYZED` | Architect | Complete Implementation Specification adhering to `MIGRATION_UNIT_SPEC.md` | Yes | No |
| **T03** | `ANALYZING` | `BLOCKED` | Scanner / Architect | Identified missing prerequisite or conflicting dependency | Yes | No |
| **T04** | `BLOCKED` | `ESCALATED` | Supervisor | Unresolvable blocker or missing external input | No | Yes (Notification) |
| **T05** | `ANALYZED` | `READY` | Supervisor | Specification validation passes against `AI_MIGRATION_RULES.md` | Yes | No |
| **T06** | `READY` | `IMPLEMENTING` | Supervisor | Working tree clean, branch is `migration-alpha`, lock acquired | Yes | No |
| **T07** | `IMPLEMENTING` | `IMPLEMENTED` | Implementer | Output manifest (files created/modified, routes, tests added) | Yes | No |
| **T08** | `IMPLEMENTING` | `FAILED` | Implementer / Supervisor | Syntax error, unhandled exception, or scope violation | Yes | No |
| **T09** | `IMPLEMENTED` | `VERIFYING` | Supervisor | Implementation manifest matches specification target files | Yes | No |
| **T10** | `VERIFYING` | `VERIFIED` | Verifier | Test output logs showing 0 failures, route checks, DoD satisfied | No | No |
| **T11** | `VERIFYING` | `FAILED` | Verifier | Failure diagnostic logs (test failures, regression detected, DoD failure) | Yes | No |
| **T12** | `FAILED` | `REPAIRING` | Supervisor | `attempts < max_attempts` (default 3); failure trace recorded | Yes | No |
| **T13** | `FAILED` | `ESCALATED` | Supervisor | `attempts >= max_attempts` OR destructive action required | No | **YES** |
| **T14** | `REPAIRING` | `IMPLEMENTING` | Architect / Implementer | Targeted repair plan addressing specific failure diagnostic | Yes | No |
| **T15** | `ESCALATED` | `ANALYZING` / `READY` | Human | Human resolution record in `STATE.json` with instructions | Yes | **YES** |
| **T16** | `VERIFIED` | `CHECKPOINTED` | Supervisor | Git commit created following `migration(<unit>): <desc>` format | No | No |
| **T17** | `CHECKPOINTED` | `COMPLETED` | Supervisor | `STATE.json` and `FEATURE_MATRIX.md` updated with commit hash | No | No |
| **T18** | Any non-terminal | `DEFERRED` | Supervisor / Human | Documented architectural rationale | N/A | **YES** |
| **T19** | Any non-terminal | `CANCELLED` | Human | Documented cancellation rationale | N/A | **YES** |

---

## 4. Invariant Rules & Guardrails

1. **Strict Linearity:** A unit cannot skip states (e.g. `NOT_STARTED` $\to$ `IMPLEMENTING` or `IMPLEMENTED` $\to$ `CHECKPOINTED` is an immediate fatal violation).
2. **Single-Active-Unit Lock:** The Supervisor may only set one unit to `IMPLEMENTING` or `VERIFYING` at any given time.
3. **No Self-Certification:** The Implementer cannot transition a unit to `VERIFIED`. Only the Verifier can issue a `VERIFIED` state.
4. **Append-Only Audit Trail:** Every transition must append an entry to `history` in `STATE.json` with timestamp, actor, and evidence.
5. **Circuit Breaker (Loop Prevention):** If `attempts` reaches `max_attempts` (default: 3), transition **T13** is mandatory. The Supervisor is forbidden from triggering **T12** again.
