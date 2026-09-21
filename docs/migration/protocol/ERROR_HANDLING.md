# CampusLynk Migration Protocol — Error Handling & Fail-Closed Guardrails

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Active Specification  
> **Version:** 1.0.0  
> **Guiding Principle:** **FAIL CLOSED**  

---

## 1. The Fail-Closed Mandate

The fundamental invariant of the CampusLynk Migration System is:

$$\text{Any Anomaly} \implies \text{FAIL CLOSED}$$

When **any** protocol error, schema violation, network failure, or semantic inconsistency occurs:

1. **DO NOT IMPLEMENT:** No code files may be created, updated, or deleted.
2. **DO NOT MUTATE STATE:** The unit state in `STATE.json` must remain unchanged (or transition explicitly to `FAILED`/`ESCALATED`).
3. **DO NOT COMMIT:** No git commits, tags, or branch updates may be created.
4. **DO NOT ASSUME:** The Orchestrator must never guess missing fields or apply default approvals.

---

## 2. Failure Classifications & Deterministic Actions

```
┌─────────────────────────┬───────────────────────────────┬───────────────────────────────────────────┐
│ Error Category          │ Detection Point               │ Deterministic Orchestrator Action         │
├─────────────────────────┼───────────────────────────────┼───────────────────────────────────────────┤
│ Transport Timeout       │ Bridge Polling (>45s)         │ Expire message_id. Log timeout.           │
│                         │                               │ Retry once with new ID or pause run.      │
├─────────────────────────┼───────────────────────────────┼───────────────────────────────────────────┤
│ JSON Syntax Error       │ Response Decoding             │ Reject payload. Do not retry parse.       │
│                         │                               │ Transition to ESCALATED with raw snippet. │
├─────────────────────────┼───────────────────────────────┼───────────────────────────────────────────┤
│ Schema Violation        │ JSON Schema Validation        │ Reject payload. Output schema errors.     │
│                         │ (Missing fields, wrong types) │ Transition to ESCALATED.                  │
├─────────────────────────┼───────────────────────────────┼───────────────────────────────────────────┤
│ Correlation Mismatch    │ 4-Point Correlation Check     │ Discard immediately.                      │
│ (in_reply_to, run_id)   │                               │ Log warning. Do not mutate state.         │
├─────────────────────────┼───────────────────────────────┼───────────────────────────────────────────┤
│ Cross-Unit Pollution    │ response.unit_id != active    │ Reject payload. Lock execution to IDLE.   │
├─────────────────────────┼───────────────────────────────┼───────────────────────────────────────────┤
│ Out-of-Matrix Decision  │ Matrix Validation             │ Reject payload. Invalid state transition. │
│                         │ (e.g. APPROVED for FAILED)    │ Transition to ESCALATED.                  │
├─────────────────────────┼───────────────────────────────┼───────────────────────────────────────────┤
│ Stale / Duplicate Msg   │ Processed Message Ledger      │ Acknowledge and discard silently.         │
├─────────────────────────┼───────────────────────────────┼───────────────────────────────────────────┤
│ Scope Violation         │ Diff vs allowed_files         │ Reject. Transition to FAILED.             │
└─────────────────────────┴───────────────────────────────┴───────────────────────────────────────────┘
```

---

## 3. Detailed Failure Scenarios

### 3.1 Scenario: Malformed or Non-JSON Response
- **Trigger:** ChatGPT returns plain text without a JSON code block, or the JSON has syntax errors (e.g. unescaped quotes, truncated brackets).
- **Orchestrator Behavior:**
  1. The regex parser fails to find a valid ` ```json ... ``` ` block, or `json_decode()` returns `NULL` with `json_last_error() != JSON_ERROR_NONE`.
  2. The Orchestrator writes an entry to `docs/migration/ESCALATION_PROTOCOL.md` containing the timestamp, `message_id`, and raw response text.
  3. The unit state is set to `ESCALATED`.
  4. Execution halts. No code is modified.

### 3.2 Scenario: Schema Validation Failure
- **Trigger:** The JSON is well-formed, but missing a required field (e.g. `payload.state_transition`), has an invalid enum (e.g. `"decision": "ALL_GOOD"`), or violates pattern constraints.
- **Orchestrator Behavior:**
  1. The validator rejects the payload with an explicit error list (e.g. `Field 'payload.state_transition' is required`).
  2. The unit state remains frozen in its pre-request state.
  3. The Orchestrator logs the violation and halts automated progression.

### 3.3 Scenario: Correlation Mismatch
- **Trigger:** A response arrives with `in_reply_to: "msg_req_001"`, but the Orchestrator's active request is `"msg_req_002"`, or the `run_id` / `unit_id` does not match the active execution lock.
- **Orchestrator Behavior:**
  1. The response is flagged as **ORPHAN** or **STALE**.
  2. It is immediately purged from the bridge output buffer.
  3. The active request continues waiting until its timeout expires.
  4. No state or file changes occur.

### 3.4 Scenario: Stage-Decision Incompatibility
- **Trigger:** For an `ARCHITECTURE_REVIEW` in stage `ANALYZING`, ChatGPT returns `VERIFICATION_APPROVED`.
- **Orchestrator Behavior:**
  1. The decision matrix check fails (`VERIFICATION_APPROVED` is only permitted for `VERIFICATION_REVIEW` / `FINAL_UNIT_REVIEW`).
  2. The response is rejected with error `Decision 'VERIFICATION_APPROVED' is illegal for stage 'ANALYZING'`.
  3. The unit transitions to `ESCALATED`.

### 3.5 Scenario: Transport Bridge Failure / Disconnect
- **Trigger:** Chrome crashes, port 9222 closes, or the WebSocket drops during transmission.
- **Orchestrator Behavior:**
  1. The bridge reports exit code 1 (`CDP endpoint not reachable` or `WebSocket connection failed`).
  2. The Orchestrator does **NOT** consider the unit failed.
  3. The execution mutex is held.
  4. The system logs a transport fault and requests manual bridge restart.

---

## 4. Recovery & Human Resolution

When a unit is transitioned to `ESCALATED` due to a protocol error:

1. The human operator reviews the escalation log in `docs/migration/ESCALATION_PROTOCOL.md`.
2. The operator resolves the ambiguity (e.g. manually reviewing the architecture plan or restarting the bridge).
3. The operator records the resolution in `STATE.json`:
   ```json
   "escalations": [
     {
       "unit": "M1.1",
       "reason": "Protocol schema failure: invalid decision enum",
       "resolved_at": "2026-09-21T18:30:00Z",
       "resolved_by": "Human",
       "resolution": "Reset state to ANALYZING and re-dispatched"
     }
   ]
   ```
4. The Orchestrator resumes execution safely from the resolved state.
