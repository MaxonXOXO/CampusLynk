# Supervisor Decision Logic & Protocol

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Active Protocol  
> **Version:** 0.2.0  

This document defines the deterministic decision rules executed by the **Supervisor** to orchestrate migration units without human oversight, except where escalation is mandatory.

---

## 1. Decision Logic Engine

The Supervisor executes the following decision tree in an event-driven loop:

```text
[EVENT: Unit Evaluation / State Tick]
  │
  ├─► Is any unit currently in IMPLEMENTING or VERIFYING?
  │     YES ──► Maintain active lock. Do not start new units.
  │
  ├─► Current Unit = NOT_STARTED:
  │     ├── Are all dependencies in COMPLETED state?
  │     │     NO  ──► Set state = BLOCKED. Reason: "Unresolved upstream dependencies".
  │     │     YES ──► Set state = ANALYZING. Dispatch to Scanner and Architect.
  │
  ├─► Current Unit = ANALYZING:
  │     ├── Did Scanner/Architect discover unresolved external dependencies?
  │     │     YES ──► Set state = BLOCKED.
  │     ├── Did Architect identify ambiguous behavior or architectural conflict?
  │     │     YES ──► Set state = ESCALATED. Reason: "Architectural ambiguity".
  │     ├── Is Implementation Specification valid and complete?
  │     │     NO  ──► Remain in ANALYZING (request specification refinement).
  │     │     YES ──► Set state = READY.
  │
  ├─► Current Unit = READY:
  │     ├── Is target working tree clean on branch 'migration-alpha'?
  │     │     NO  ──► Set state = ESCALATED. Reason: "Dirty working tree before implementation".
  │     │     YES ──► Increment attempts counter. Set state = IMPLEMENTING. Dispatch to Implementer.
  │
  ├─► Current Unit = IMPLEMENTING:
  │     ├── Did Implementer report fatal syntax crash or unhandled exception?
  │     │     YES ──► Set state = FAILED.
  │     ├── Were any files outside the approved 'allowed_files' whitelist modified?
  │     │     YES ──► Set state = FAILED. Reason: "Unauthorized scope expansion".
  │     ├── Is Implementation Manifest complete with required files and tests?
  │     │     YES ──► Set state = IMPLEMENTED. Transition immediately to VERIFYING.
  │
  ├─► Current Unit = VERIFYING:
  │     ├── Did Verifier return PASS with valid test and route evidence?
  │     │     YES ──► Set state = VERIFIED. Trigger CHECKPOINT_PROTOCOL.
  │     ├── Did Verifier return FAIL?
  │     │     YES ──► Set state = FAILED.
  │     ├── Did Verifier return BLOCKED?
  │     │     YES ──► Set state = BLOCKED.
  │
  ├─► Current Unit = FAILED:
  │     ├── Does the failure require a destructive DB operation?
  │     │     YES ──► Set state = ESCALATED. Reason: "Destructive DB operation requested".
  │     ├── Is attempts < max_attempts (default: 3)?
  │     │     YES ──► Set state = REPAIRING. Dispatch failure diagnostic to Architect/Implementer.
  │     │     NO  ──► Set state = ESCALATED. Reason: "Max retry attempts exhausted".
  │
  ├─► Current Unit = REPAIRING:
  │     ├── Is targeted repair plan prepared?
  │     │     YES ──► Set state = IMPLEMENTING.
  │
  ├─► Current Unit = VERIFIED:
  │     ├── Has Git checkpoint commit been created following CHECKPOINT_PROTOCOL?
  │     │     YES ──► Set state = CHECKPOINTED.
  │
  ├─► Current Unit = CHECKPOINTED:
  │     ├── Has STATE.json and FEATURE_MATRIX.md been updated with commit SHA and timestamps?
  │     │     YES ──► Set state = COMPLETED. Release lock. Select next eligible unit.
```

---

## 2. Deterministic Rule Definitions

### Rule 1: Dependency Gate
A unit $U$ cannot transition from `NOT_STARTED` to `ANALYZING` unless:
$$\forall d \in \text{dependencies}(U), \quad \text{state}(d) == \text{COMPLETED}$$

### Rule 2: Anti-Looping Circuit Breaker
Every unit specification defines `max_attempts` (default: `3`).
$$\text{IF } \text{state} == \text{FAILED} \land \text{attempts} \ge \text{max\_attempts} \implies \text{state} \leftarrow \text{ESCALATED}$$
The Supervisor is mathematically prevented from transitioning to `REPAIRING` once the attempt limit is reached.

### Rule 3: Strict Scope Enforcement
Before transitioning from `IMPLEMENTING` to `IMPLEMENTED`, the Supervisor compares the Git diff:
$$\text{changed\_files}(\text{git diff}) \subseteq \text{allowed\_files}(\text{Implementation Specification})$$
If any file outside the whitelist is changed, the unit transitions immediately to `FAILED` with diagnostic `"Scope violation: modified unauthorized files"`.

### Rule 4: Destructive Operation Prohibition
If any agent suggests executing `migrate:fresh`, `db:wipe`, `DROP TABLE`, or `TRUNCATE`:
$$\text{Action} = \text{DESTRUCTIVE} \implies \text{state} \leftarrow \text{ESCALATED}$$
Execution is halted immediately, and human intervention is required.

---

## 3. Concurrency & Locking Specification

1. **Global Execution Mutex:** Only one migration unit may hold the `active_execution` lock at any given time.
2. **Lock Acquisition:**
   ```json
   "current_execution": {
     "feature": "r21_major_project",
     "unit": "M2.1",
     "state": "IMPLEMENTING",
     "agent": "Implementer",
     "started_at": "2026-09-21T18:00:00Z"
   }
   ```
3. **Lock Release:** The lock is set to `IDLE` only when a unit reaches `COMPLETED`, `ESCALATED`, `CANCELLED`, or `DEFERRED`.
