# CampusLynk Autonomous Migration System — Structured Communication Protocol

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Active Protocol Specification  
> **Version:** 1.0.0  
> **Layer:** Protocol / Control Plane (Sits above the Chrome CDP Bridge)  

---

## 1. Architectural Role & Layering

The communication system operates in three distinct, strictly decoupled layers:

```
┌─────────────────────────────────────────────────────────────┐
│                 Migration Orchestrator                      │
│   - State Machine & Control Plane (STATE.json)              │
│   - Execution Mutex & Lock Management                       │
│   - Code & Test Runner Execution                            │
└──────────────────────────────┬──────────────────────────────┘
                               │ Structured Protocol Invariant
                               ▼
┌─────────────────────────────────────────────────────────────┐
│              Structured Communication Protocol               │
│   - REQUEST_SCHEMA.json & RESPONSE_SCHEMA.json              │
│   - Deterministic Correlation (message_id / in_reply_to)    │
│   - Fail-Closed Validation & Idempotency Rules              │
└──────────────────────────────┬──────────────────────────────┘
                               │ Raw JSON Text / Markdown Block
                               ▼
┌─────────────────────────────────────────────────────────────┐
│              Chrome CDP Transport Bridge                    │
│   - scripts/migration/chat-bridge/test-bridge.ps1           │
│   - Native .NET WebSocket on ws://127.0.0.1:9222            │
│   - DOM Composer Injection & Stop-Button Polling            │
└──────────────────────────────┬──────────────────────────────┘
                               │ Browser WebSocket
                               ▼
┌─────────────────────────────────────────────────────────────┐
│             ChatGPT Architect / Supervisor                  │
│   - High-Level Architectural Authority                      │
│   - Review, Plan Approval, Repair Directives                │
└─────────────────────────────────────────────────────────────┘
```

The **Transport Bridge** is solely responsible for byte transmission. It does **not** interpret, validate, or make decisions on migration actions.

The **Protocol Layer** enforces machine-readable structure, validates schema compliance, checks correlation, and guarantees fail-closed behavior before the Orchestrator acts on any directive.

---

## 2. Message Envelope Specification

Every message traversing the protocol MUST be encapsulated in a deterministic envelope.

### 2.1 Request Envelope (Orchestrator $\to$ ChatGPT)

```json
{
  "protocol_version": "1.0",
  "message_id": "msg_req_20260921_180000_001",
  "run_id": "run_20260921_01",
  "timestamp": "2026-09-21T18:00:00Z",
  "sender": "orchestrator",
  "recipient": "architect",
  "message_type": "ARCHITECTURE_REVIEW",
  "unit_id": "M1.1",
  "stage": "ANALYZING",
  "payload": { ... }
}
```

#### Envelope Fields:

| Field | Type | Required? | Constraint / Format | Description |
| :--- | :--- | :---: | :--- | :--- |
| `protocol_version` | String | **Required** | Must be `"1.0"` | Identifies the protocol specification version. |
| `message_id` | String | **Required** | Pattern: `^msg_[a-zA-Z0-9_-]+$` | Globally unique message identifier. |
| `run_id` | String | **Required** | Pattern: `^run_[a-zA-Z0-9_-]+$` | Unique identifier of the active migration run. |
| `timestamp` | String | **Required** | ISO-8601 UTC (`date-time`) | Exact UTC timestamp of message generation. |
| `sender` | String | **Required** | Enum: `["orchestrator"]` | Author of the message. |
| `recipient` | String | **Required** | Enum: `["architect"]` | Target consumer of the message. |
| `message_type` | String | **Required** | Enum (see `MESSAGE_TYPES.md`) | Categorizes the review or query being requested. |
| `unit_id` | String | **Required** | Pattern: `^M\d+\.\d+$` | Target migration unit identifier (e.g. `M1.1`). |
| `stage` | String | **Required** | Enum (valid state machine state) | Current lifecycle state of the target unit. |
| `payload` | Object | **Required** | Validated against `REQUEST_SCHEMA.json` | The stage-specific context and explicit question. |

---

### 2.2 Response Envelope (ChatGPT $\to$ Orchestrator)

```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_20260921_180005_001",
  "in_reply_to": "msg_req_20260921_180000_001",
  "run_id": "run_20260921_01",
  "unit_id": "M1.1",
  "timestamp": "2026-09-21T18:00:05Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "PLAN_APPROVED",
  "payload": { ... }
}
```

#### Envelope Fields:

| Field | Type | Required? | Constraint / Format | Description |
| :--- | :--- | :---: | :--- | :--- |
| `protocol_version` | String | **Required** | Must be `"1.0"` | Must match request protocol version. |
| `message_id` | String | **Required** | Pattern: `^msg_[a-zA-Z0-9_-]+$` | Globally unique response identifier. |
| `in_reply_to` | String | **Required** | Pattern: `^msg_[a-zA-Z0-9_-]+$` | **Must exactly match** the request's `message_id`. |
| `run_id` | String | **Required** | Pattern: `^run_[a-zA-Z0-9_-]+$` | **Must exactly match** the request's `run_id`. |
| `unit_id` | String | **Required** | Pattern: `^M\d+\.\d+$` | **Must exactly match** the request's `unit_id`. |
| `timestamp` | String | **Required** | ISO-8601 UTC (`date-time`) | Exact UTC timestamp of response generation. |
| `sender` | String | **Required** | Enum: `["architect"]` | Author of the response. |
| `recipient` | String | **Required** | Enum: `["orchestrator"]` | Destination of the response. |
| `decision` | String | **Required** | Enum (see `MESSAGE_TYPES.md`) | The formal architectural verdict. |
| `payload` | Object | **Required** | Validated against `RESPONSE_SCHEMA.json` | Detailed reasoning, instructions, and transitions. |

---

## 3. Correlation Mechanics

To ensure that the Orchestrator never acts on a stale, cross-talk, or misdirected response, the Orchestrator executes a 4-point correlation check on every received payload:

```text
[RECEIVED RESPONSE]
         │
         ├── Check 1: in_reply_to == active_request.message_id ?
         │     NO ──► FAIL CLOSED (Reject: Uncorrelated / Orphan response)
         │
         ├── Check 2: run_id == active_request.run_id ?
         │     NO ──► FAIL CLOSED (Reject: Cross-run pollution)
         │
         ├── Check 3: unit_id == active_request.unit_id ?
         │     NO ──► FAIL CLOSED (Reject: Cross-unit state pollution)
         │
         ├── Check 4: decision ∈ permitted_decisions(active_request.message_type, active_request.stage) ?
         │     NO ──► FAIL CLOSED (Reject: Out-of-matrix decision)
         │
         ▼
[ACCEPT RESPONSE FOR PARSING & VALIDATION]
```

If **any** of the 4 checks fail, the response is discarded, no state transition occurs, no code is written, and the error is logged as a protocol violation.

---

## 4. Idempotency & Retry Guarantees

### 4.1 Duplicate Request Dispatched
- If the Orchestrator dispatches the same `message_id` twice (e.g. after a transport reconnect), the Architect response with `in_reply_to: message_id` is processed once.
- Subsequent identical responses are acknowledged as duplicates and discarded without re-triggering execution.

### 4.2 Duplicate Response Received
- The Orchestrator maintains an in-memory and on-disk ledger of processed `message_id` values.
- If a response with an already-processed `message_id` arrives, it is logged and ignored.

### 4.3 Bridge Timeout
- If the bridge times out (e.g. 45s without completion):
  - The Orchestrator does **NOT** assume failure of the unit.
  - The unit remains in its current state.
  - The active `message_id` is expired.
  - A retry may be initiated with a new `message_id` and identical payload.

### 4.4 Orchestrator Restart
- If the Orchestrator restarts mid-execution, it reads `STATE.json`.
- It reconstructs the active `run_id` and pending unit.
- Any un-correlated responses lingering in the bridge output buffer are wiped.

---

## 5. Dual-Mode Representation (Human + Machine)

To support both seamless machine automation and high-clarity human review, all ChatGPT responses returned via the bridge must be wrapped in a fenced code block:

````markdown
### Architectural Assessment
The implementation plan for M1.1 correctly identifies all legacy schema columns and adheres strictly to the read-only boundary of the legacy repository. No destructive operations are present.

```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_20260921_180005_001",
  "in_reply_to": "msg_req_20260921_180000_001",
  "run_id": "run_20260921_01",
  "unit_id": "M1.1",
  "timestamp": "2026-09-21T18:00:05Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "PLAN_APPROVED",
  "payload": {
    "reason": "Specification satisfies all architectural rules and file whitelists.",
    "state_transition": {
      "target_state": "READY",
      "authorized": true
    },
    "required_changes": [],
    "implementation_instructions": [
      "Ensure App/Models/AuditLog.php uses strict type hints.",
      "Verify fillable properties match legacy schema."
    ],
    "verification_requirements": [
      "Run php artisan test --filter=AuditLogTest",
      "Ensure 0 warnings and 0 failures"
    ],
    "risk_flags": []
  }
}
```
````

The local parser extracts the ```` ```json ... ``` ```` block, parses it deterministically, and validates it against `RESPONSE_SCHEMA.json`. The outer markdown prose is retained in logs for human inspection.
