# CampusLynk Migration Protocol — Message Types & Decision Semantics

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Active Protocol Specification  
> **Version:** 1.0.0  
> **Layer:** Protocol Layer (Sits above Chrome CDP Transport Bridge)  

This document defines the formal vocabulary and decision semantics for messages exchanged between the **Migration Orchestrator** and the **ChatGPT Architect/Supervisor**.

---

## 1. Overview & Compatibility Matrix

Every message type dispatched by the Orchestrator expects a strictly bounded set of valid decision responses from the Architect. Returning an out-of-matrix decision triggers an immediate **FAIL-CLOSED** condition.

| Orchestrator Message Type | Valid Stage(s) | Permitted Architect Decisions | Authorized Target State(s) |
| :--- | :--- | :--- | :--- |
| `ARCHITECTURE_REVIEW` | `ANALYZING` | `PLAN_APPROVED`<br>`REVISION_REQUIRED`<br>`ESCALATE`<br>`ABORT` | `READY`<br>`ANALYZING`<br>`ESCALATED`<br>`FAILED` |
| `IMPLEMENTATION_REVIEW`| `IMPLEMENTED` | `IMPLEMENTATION_APPROVED`<br>`REPAIR_REQUIRED`<br>`REPLAN_REQUIRED`<br>`ESCALATE`<br>`ABORT` | `VERIFYING`<br>`REPAIRING`<br>`ANALYZING`<br>`ESCALATED`<br>`FAILED` |
| `VERIFICATION_REVIEW` | `VERIFYING` | `VERIFICATION_APPROVED`<br>`REPAIR_REQUIRED`<br>`ESCALATE`<br>`ABORT` | `VERIFIED`<br>`REPAIRING`<br>`ESCALATED`<br>`FAILED` |
| `REPAIR_REVIEW` | `REPAIRING` | `PLAN_APPROVED`<br>`REVISION_REQUIRED`<br>`ESCALATE`<br>`ABORT` | `IMPLEMENTING`<br>`REPAIRING`<br>`ESCALATED`<br>`FAILED` |
| `ESCALATION_REVIEW` | `ESCALATED` | `PLAN_APPROVED`<br>`REPLAN_REQUIRED`<br>`ABORT`<br>`ESCALATE` | `READY`<br>`ANALYZING`<br>`FAILED`<br>`ESCALATED` |
| `FINAL_UNIT_REVIEW` | `VERIFIED` | `VERIFICATION_APPROVED`<br>`ESCALATE`<br>`ABORT` | `CHECKPOINTED`<br>`ESCALATED`<br>`FAILED` |
| `TASK_REPORT` | Any active stage | `TASK_DISPATCH`<br>`PLAN_APPROVED`<br>`REVISION_REQUIRED`<br>`REPAIR_REQUIRED`<br>`ESCALATE`<br>`ABORT` | Target state determined by ChatGPT decision |

---

## 2. Orchestrator $\to$ ChatGPT Message Types

### 2.1 `ARCHITECTURE_REVIEW`
- **When Sent:** When a unit's upstream dependencies are satisfied, initial reference scanning is complete, and an implementation specification has been drafted.
- **Stage:** `ANALYZING`
- **Payload Requirements:**
  - `context.dependencies`: List of all upstream unit IDs and their completion status.
  - `context.allowed_files`: Whitelist of files the implementer will be permitted to touch.
  - `context.scanner_findings`: Reference code analysis, database tables, and route definitions.
  - `context.architecture_plan`: Step-by-step implementation strategy.
  - `explicit_request`: "Review the proposed architecture plan and file scope. Confirm adherence to AI_MIGRATION_RULES.md."
- **Expected Outcome:** `PLAN_APPROVED` (moves to `READY`) or `REVISION_REQUIRED` (stays in `ANALYZING`).

### 2.2 `IMPLEMENTATION_REVIEW`
- **When Sent:** After the Implementer has created/modified code within the permitted scope, before initiating full verification runs.
- **Stage:** `IMPLEMENTED`
- **Payload Requirements:**
  - `context.allowed_files`: Whitelist of permitted files.
  - `context.diff_summary`: Unified diff or file change summary.
  - `context.implementation_report`: Manifest of created/modified files, routes added, and unit tests written.
  - `explicit_request`: "Review implementation diff against the approved plan and scope. Authorize progression to automated verification."
- **Expected Outcome:** `IMPLEMENTATION_APPROVED` (moves to `VERIFYING`) or `REPAIR_REQUIRED` (moves to `REPAIRING`).

### 2.3 `VERIFICATION_REVIEW`
- **When Sent:** After automated test suites, route registrations, and Definition of Done (DoD) checks have executed.
- **Stage:** `VERIFYING`
- **Payload Requirements:**
  - `context.verification_report`: Test runner output, assertion counts, route validation results, and DoD checklist.
  - `explicit_request`: "Evaluate test evidence and verify that all acceptance criteria are satisfied without regression."
- **Expected Outcome:** `VERIFICATION_APPROVED` (moves to `VERIFIED`) or `REPAIR_REQUIRED` (moves to `REPAIRING`).

### 2.4 `REPAIR_REVIEW`
- **When Sent:** When an implementation or verification failure occurs and a targeted repair plan has been formulated.
- **Stage:** `REPAIRING`
- **Payload Requirements:**
  - `context.failure_diagnostic`: Stack traces, test failures, or compiler diagnostics.
  - `context.architecture_plan`: Targeted fix proposal addressing the root cause.
  - `explicit_request`: "Review the failure diagnostic and repair plan. Authorize implementation of the fix."
- **Expected Outcome:** `PLAN_APPROVED` (moves to `IMPLEMENTING`) or `REVISION_REQUIRED` / `ESCALATE`.

### 2.5 `ESCALATION_REVIEW`
- **When Sent:** When automated retries have exceeded `max_attempts`, an architectural ambiguity is detected, or a human operator has provided input.
- **Stage:** `ESCALATED`
- **Payload Requirements:**
  - `context.failure_diagnostic`: Escalation history, reason for blockage, and human/architect notes.
  - `explicit_request`: "Provide architectural resolution for this escalation or maintain escalation lock."
- **Expected Outcome:** `PLAN_APPROVED` (resumes to `READY`/`ANALYZING`) or `ESCALATE` (remains locked).

### 2.6 `FINAL_UNIT_REVIEW`
- **When Sent:** When a unit is verified and ready for git checkpointing and state recording.
- **Stage:** `VERIFIED`
- **Payload Requirements:**
  - `context.verification_report`: Comprehensive verification sign-off summary.
  - `explicit_request`: "Authorize Git checkpoint creation and completion recording in STATE.json."
- **Expected Outcome:** `VERIFICATION_APPROVED` (triggers transition to `CHECKPOINTED`).

### 2.7 `TASK_REPORT`
- **When Sent:** Dispatched by the local worker (Antigravity) via the Orchestrator after completing an assigned task.
- **Stage:** Any active migration stage (`ANALYZING`, `READY`, `IMPLEMENTING`, `VERIFYING`, `REPAIRING`, `ESCALATED`).
- **Payload Requirements:**
  - `context.task_report.task_id`: Unique identifier of the completed task.
  - `context.task_report.unit_id`: Target migration unit.
  - `context.task_report.status`: `COMPLETED`, `FAILED`, or `BLOCKED`.
  - `context.task_report.summary`: Concise narrative of what was executed.
  - `context.task_report.changes`: List of modified files or created components.
  - `context.task_report.tests`: Test execution results, assertion counts, or lint status.
  - `context.task_report.issues`: Any blockers, ambiguities, or warnings encountered.
  - `context.task_report.git`: Git working tree status or branch details.
  - `explicit_request`: "Review task execution report and issue next TASK_DISPATCH or decision directive."
- **Expected Outcome:** `TASK_DISPATCH`, `PLAN_APPROVED`, `REVISION_REQUIRED`, `REPAIR_REQUIRED`, `ESCALATE`, or `ABORT`.

---

## 3. ChatGPT $\to$ Orchestrator Decision Semantics

Every response decision carries strict, unambiguous semantics that govern what the Orchestrator is legally permitted to execute:

### 3.1 `PLAN_APPROVED`
- **Semantic Meaning:** The proposed plan, architecture, or repair is formally approved.
- **Orchestrator Action:**
  - If in `ANALYZING`: Authorizes transition to `READY`.
  - If in `REPAIRING`: Authorizes transition to `IMPLEMENTING`.
- **Constraint:** Implementation MUST NOT exceed `context.allowed_files` or introduce unapproved dependencies.

### 3.2 `REVISION_REQUIRED`
- **Semantic Meaning:** The submitted plan or repair has defects, omissions, or scope violations.
- **Orchestrator Action:**
  - Remains in current state (`ANALYZING` or `REPAIRING`).
  - Incorporates `payload.required_changes` into the plan.
  - Re-submits an updated review.
- **Constraint:** Implementation MUST NOT begin while in this state.

### 3.3 `IMPLEMENTATION_APPROVED`
- **Semantic Meaning:** The code diff matches the approved plan, adheres to the file whitelist, and is ready for automated testing.
- **Orchestrator Action:**
  - Authorizes transition from `IMPLEMENTED` to `VERIFYING`.
  - Dispatches test suite execution.

### 3.4 `VERIFICATION_APPROVED`
- **Semantic Meaning:** All verification evidence is valid, test runs are green, routes respond as expected, and DoD is satisfied.
- **Orchestrator Action:**
  - If in `VERIFYING`: Authorizes transition to `VERIFIED`.
  - If in `VERIFIED`: Authorizes Git checkpoint commit (`CHECKPOINTED`) and updates `STATE.json` (`COMPLETED`).

### 3.5 `REPAIR_REQUIRED`
- **Semantic Meaning:** Verification failed or implementation defects were detected. Targeted repair is required.
- **Orchestrator Action:**
  - Transitions unit to `FAILED` and then `REPAIRING` (if `attempts < max_attempts`).
  - Increments attempt counter.
  - Applies `payload.required_changes` and prepares repair plan.

### 3.6 `REPLAN_REQUIRED`
- **Semantic Meaning:** The current implementation strategy is fundamentally flawed or invalid. Repairing within the existing plan is impossible.
- **Orchestrator Action:**
  - Transitions unit back to `ANALYZING`.
  - Discards existing implementation diff.
  - Re-initiates architecture planning from scratch.

### 3.7 `ESCALATE`
- **Semantic Meaning:** Autonomous execution cannot proceed safely. A human architectural decision or intervention is required.
- **Orchestrator Action:**
  - Sets unit state to `ESCALATED`.
  - Releases active execution lock to `IDLE`.
  - Logs `payload.escalation` details to `docs/migration/ESCALATION_PROTOCOL.md`.
  - Halts all automated progress on this unit.

### 3.8 `ABORT`
- **Semantic Meaning:** A catastrophic condition, unrecoverable data integrity threat, or destructive command has been detected.
- **Orchestrator Action:**
  - Immediately halts the entire migration run.
  - Sets active state to `FAILED` / `ABORTED`.
  - Discards uncommitted working changes.
  - Fails closed immediately.

### 3.9 `TASK_DISPATCH`
- **Semantic Meaning:** ChatGPT has evaluated the previous report or state and is dispatching a concrete, bounded task for Antigravity to execute.
- **Orchestrator Action:**
  - Validates `payload.task_dispatch` against `RESPONSE_SCHEMA.json`.
  - Persists dispatch in runtime logs.
  - Hands off the task to Antigravity without modifying migration state or deciding steps locally.
- **Constraint:** The task must contain explicit `objective`, `instructions`, `constraints`, `expected_report`, and `stop_conditions`. The local controller must not alter these instructions.
