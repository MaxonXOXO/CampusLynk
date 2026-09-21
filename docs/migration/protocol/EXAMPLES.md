# CampusLynk Migration Protocol — Concrete Examples

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Reference Specification & Test Fixtures  
> **Version:** 1.0.0  

This document provides realistic, schema-valid examples of protocol interactions across the migration lifecycle. These fixtures are used directly by `scripts/migration/validate-protocol.php`.

---

## Example A: M1.1 Architecture Review $\longrightarrow$ `PLAN_APPROVED`

### Scenario
The Orchestrator has scanned the legacy `campuslynk_legacy` reference for `M1.1` (Audit Logs) and prepared the implementation specification. It requests architectural approval from ChatGPT.

### Request Payload (`msg_req_20260921_01A`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_req_20260921_01A",
  "run_id": "run_20260921_01",
  "timestamp": "2026-09-21T18:00:00Z",
  "sender": "orchestrator",
  "recipient": "architect",
  "message_type": "ARCHITECTURE_REVIEW",
  "unit_id": "M1.1",
  "stage": "ANALYZING",
  "payload": {
    "unit_title": "Audit Logging Infrastructure",
    "objective": "Port legacy audit_logs schema and model into target repository with read-only reference guarantees.",
    "explicit_request": "Review the proposed architecture plan and file scope. Confirm adherence to AI_MIGRATION_RULES.md and authorize transition to READY.",
    "context": {
      "dependencies": [],
      "allowed_files": [
        "app/Models/AuditLog.php",
        "database/migrations/2026_09_21_000001_create_audit_logs_table.php",
        "tests/Unit/AuditLogTest.php"
      ],
      "scanner_findings": {
        "legacy_table": "audit_logs",
        "columns": ["id", "user_id", "action", "ip_address", "user_agent", "created_at"],
        "soft_deletes": false
      },
      "architecture_plan": {
        "migration_strategy": "Create audit_logs table with indexed user_id and timestamp.",
        "model_strategy": "Define Eloquent model with fillable attributes and immutable timestamps.",
        "test_strategy": "Unit test model creation, fillable protection, and relationship to User."
      }
    }
  }
}
```

### Response Payload (`msg_resp_20260921_01A`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_20260921_01A",
  "in_reply_to": "msg_req_20260921_01A",
  "run_id": "run_20260921_01",
  "unit_id": "M1.1",
  "timestamp": "2026-09-21T18:00:08Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "PLAN_APPROVED",
  "payload": {
    "reason": "Specification for M1.1 is strictly bounded, adheres to allowed_files, and introduces no destructive database operations.",
    "state_transition": {
      "target_state": "READY",
      "authorized": true
    },
    "required_changes": [],
    "implementation_instructions": [
      "Ensure App/Models/AuditLog.php enforces $guarded = ['id'].",
      "Ensure migration indexes 'created_at' for log query performance."
    ],
    "verification_requirements": [
      "php artisan test --filter=AuditLogTest",
      "Verify audit_logs table created cleanly without foreign key lock issues."
    ],
    "risk_flags": []
  }
}
```

---

## Example B: M1.1 Architecture Review $\longrightarrow$ `REVISION_REQUIRED`

### Scenario
The Orchestrator submitted an initial plan that mistakenly included modifying `app/Models/User.php` (which is outside M1.1 scope) and omitted unit test coverage.

### Request Payload (`msg_req_20260921_02B`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_req_20260921_02B",
  "run_id": "run_20260921_01",
  "timestamp": "2026-09-21T18:05:00Z",
  "sender": "orchestrator",
  "recipient": "architect",
  "message_type": "ARCHITECTURE_REVIEW",
  "unit_id": "M1.1",
  "stage": "ANALYZING",
  "payload": {
    "unit_title": "Audit Logging Infrastructure",
    "objective": "Port legacy audit logs and inject relationship directly into User model.",
    "explicit_request": "Review architecture plan and authorize transition to READY.",
    "context": {
      "dependencies": [],
      "allowed_files": [
        "app/Models/AuditLog.php",
        "app/Models/User.php",
        "database/migrations/2026_09_21_000001_create_audit_logs_table.php"
      ],
      "architecture_plan": {
        "model_strategy": "Add auditLogs() hasMany relation directly to app/Models/User.php."
      }
    }
  }
}
```

### Response Payload (`msg_resp_20260921_02B`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_20260921_02B",
  "in_reply_to": "msg_req_20260921_02B",
  "run_id": "run_20260921_01",
  "unit_id": "M1.1",
  "timestamp": "2026-09-21T18:05:10Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "REVISION_REQUIRED",
  "payload": {
    "reason": "Scope violation: app/Models/User.php cannot be modified in M1.1 (reserved for User Management feature). Tests/Unit/AuditLogTest.php is also missing from allowed_files.",
    "state_transition": {
      "target_state": "ANALYZING",
      "authorized": false
    },
    "required_changes": [
      "Remove app/Models/User.php from allowed_files whitelist.",
      "Add tests/Unit/AuditLogTest.php to allowed_files whitelist.",
      "Document test cases in architecture_plan before resubmitting."
    ],
    "risk_flags": [
      "Unauthorized scope expansion into core User model."
    ]
  }
}
```

---

## Example C: Implementation Report $\longrightarrow$ `REPAIR_REQUIRED`

### Scenario
The Implementer finished writing code for `M1.1`, but the implementation diff revealed a syntax error and a missing migration down method.

### Request Payload (`msg_req_20260921_03C`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_req_20260921_03C",
  "run_id": "run_20260921_01",
  "timestamp": "2026-09-21T18:15:00Z",
  "sender": "orchestrator",
  "recipient": "architect",
  "message_type": "IMPLEMENTATION_REVIEW",
  "unit_id": "M1.1",
  "stage": "IMPLEMENTED",
  "payload": {
    "unit_title": "Audit Logging Infrastructure",
    "objective": "Verify implementation diff against specification prior to automated test execution.",
    "explicit_request": "Review implementation diff and authorize transition to VERIFYING.",
    "context": {
      "dependencies": [],
      "allowed_files": [
        "app/Models/AuditLog.php",
        "database/migrations/2026_09_21_000001_create_audit_logs_table.php",
        "tests/Unit/AuditLogTest.php"
      ],
      "diff_summary": "Created AuditLog model, migration file, and unit test.",
      "implementation_report": {
        "files_created": [
          "app/Models/AuditLog.php",
          "database/migrations/2026_09_21_000001_create_audit_logs_table.php",
          "tests/Unit/AuditLogTest.php"
        ],
        "syntax_check": "FAILED: Parse error in migration: unexpected token '{' on line 24."
      }
    }
  }
}
```

### Response Payload (`msg_resp_20260921_03C`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_20260921_03C",
  "in_reply_to": "msg_req_20260921_03C",
  "run_id": "run_20260921_01",
  "unit_id": "M1.1",
  "timestamp": "2026-09-21T18:15:12Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "REPAIR_REQUIRED",
  "payload": {
    "reason": "Implementation contains syntax error in migration file on line 24. Automated verification cannot proceed.",
    "state_transition": {
      "target_state": "REPAIRING",
      "authorized": true
    },
    "required_changes": [
      "Fix syntax error on line 24 of database/migrations/2026_09_21_000001_create_audit_logs_table.php.",
      "Ensure down() method drops the table cleanly."
    ],
    "risk_flags": [
      "Migration syntax failure prevents schema provisioning."
    ]
  }
}
```

---

## Example D: Verification Report $\longrightarrow$ `ESCALATE`

### Scenario
Automated verification ran 3 times, but `AuditLogTest` continuously fails due to an upstream database transaction deadlock. Maximum retry attempts are exhausted.

### Request Payload (`msg_req_20260921_04D`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_req_20260921_04D",
  "run_id": "run_20260921_01",
  "timestamp": "2026-09-21T18:30:00Z",
  "sender": "orchestrator",
  "recipient": "architect",
  "message_type": "VERIFICATION_REVIEW",
  "unit_id": "M1.1",
  "stage": "VERIFYING",
  "payload": {
    "unit_title": "Audit Logging Infrastructure",
    "objective": "Evaluate verification test failures and determine next state.",
    "explicit_request": "Evaluate test failure diagnostic. Note that attempts counter has reached 3 of 3.",
    "context": {
      "dependencies": [],
      "allowed_files": [
        "app/Models/AuditLog.php",
        "database/migrations/2026_09_21_000001_create_audit_logs_table.php",
        "tests/Unit/AuditLogTest.php"
      ],
      "failure_diagnostic": {
        "attempts": 3,
        "max_attempts": 3,
        "error": "PDOException: Deadlock found when trying to get lock; try restarting transaction in AuditLogTest.php:42"
      }
    }
  }
}
```

### Response Payload (`msg_resp_20260921_04D`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_20260921_04D",
  "in_reply_to": "msg_req_20260921_04D",
  "run_id": "run_20260921_01",
  "unit_id": "M1.1",
  "timestamp": "2026-09-21T18:30:15Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "ESCALATE",
  "payload": {
    "reason": "Max repair attempts (3/3) exhausted. Persistent database deadlock in test runner requires human review of the testing environment configuration.",
    "state_transition": {
      "target_state": "ESCALATED",
      "authorized": true
    },
    "escalation": {
      "issue": "Persistent PDO deadlock during automated test execution on M1.1.",
      "requires_human_decision": true,
      "suggested_options": [
        "Review database connection isolation level in phpunit.xml.",
        "Verify if another process holds locks on the test database."
      ]
    },
    "risk_flags": [
      "Test environment instability halts autonomous progression."
    ]
  }
}
```

---

## Example E: Malformed Response $\longrightarrow$ `FAIL CLOSED`

### Scenario
ChatGPT returns a response with missing correlation fields (`in_reply_to` missing) and an invalid decision string (`"decision": "PROCEED"`).

### Raw Bridge Output Received
```text
I reviewed the plan and it looks good to go.

```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_20260921_99E",
  "run_id": "run_20260921_01",
  "unit_id": "M1.1",
  "timestamp": "2026-09-21T18:40:00Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "PROCEED",
  "payload": {
    "reason": "Looks good."
  }
}
```
```

### Orchestrator Deterministic Handling (FAIL CLOSED)
```
[FAIL CLOSED DETECTION]
1. Parser extracts JSON code block.
2. Schema Validation FAILS:
   - Missing required field: 'in_reply_to'
   - Missing required field: 'payload.state_transition'
   - Invalid enum for 'decision': 'PROCEED' (Must be one of PLAN_APPROVED, REVISION_REQUIRED, etc.)
3. Correlation Check FAILS:
   - in_reply_to is missing; cannot correlate to active request msg_req_20260921_01A.

[DETERMINISTIC ACTION]
- DO NOT IMPLEMENT: 0 files created or modified.
- DO NOT MUTATE STATE: Unit M1.1 remains strictly in ANALYZING.
- DO NOT COMMIT: No git commits created.
- Log error: "Protocol Error: Malformed response rejected. Failing closed."
- Halts execution.
```

---

## Example F: ChatGPT `TASK_DISPATCH` $\longleftrightarrow$ Antigravity `TASK_REPORT`

### Scenario
ChatGPT acts as the Architect/Supervisor and dispatches an exploratory task to Antigravity to inspect the M1.1 migration scope without making any code changes. Antigravity executes the inspection and returns a structured `TASK_REPORT`.

### Step 1a: Orchestrator Requests Initial Task Assignment (`msg_req_20260921_05F`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_req_20260921_05F",
  "run_id": "run_20260921_01",
  "timestamp": "2026-09-21T18:44:30Z",
  "sender": "orchestrator",
  "recipient": "architect",
  "message_type": "ARCHITECTURE_REVIEW",
  "unit_id": "M1.1",
  "stage": "ANALYZING",
  "payload": {
    "unit_title": "Audit Logging Infrastructure",
    "objective": "Request initial task assignment from Architect for M1.1.",
    "explicit_request": "Issue concrete task instructions for Antigravity worker.",
    "context": {
      "dependencies": [],
      "allowed_files": []
    }
  }
}
```

### Step 1b: ChatGPT Response Dispatches Task (`msg_resp_20260921_05F`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_20260921_05F",
  "in_reply_to": "msg_req_20260921_05F",
  "run_id": "run_20260921_01",
  "unit_id": "M1.1",
  "timestamp": "2026-09-21T18:45:00Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "TASK_DISPATCH",
  "payload": {
    "reason": "Dispatching initial reconnaissance task for M1.1 scope inspection.",
    "state_transition": {
      "target_state": "ANALYZING",
      "authorized": true
    },
    "task_dispatch": {
      "task_id": "task_20260921_01",
      "unit_id": "M1.1",
      "objective": "Inspect M1.1 migration scope. Do not modify anything. Return a structured report.",
      "instructions": [
        "Scan legacy audit_logs schema in campuslynk_legacy.",
        "Verify target codebase has no existing AuditLog model or migration.",
        "Prepare recommended file whitelist and test strategy."
      ],
      "constraints": [
        "DO NOT modify application code.",
        "DO NOT modify database schema.",
        "DO NOT execute migrations."
      ],
      "expected_report": "Structured summary of legacy schema, target repository state, and recommended allowed_files.",
      "stop_conditions": [
        "Legacy schema extracted or confirmed missing.",
        "Target inspection complete."
      ],
      "relevant_files": [
        "campuslynk_legacy/database/migrations/2020_01_01_create_audit_logs.php"
      ],
      "allowed_files": []
    }
  }
}
```

### Step 2a: Antigravity Request Submits Task Report (`msg_req_20260921_06F`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_req_20260921_06F",
  "run_id": "run_20260921_01",
  "timestamp": "2026-09-21T18:46:30Z",
  "sender": "orchestrator",
  "recipient": "architect",
  "message_type": "TASK_REPORT",
  "unit_id": "M1.1",
  "stage": "ANALYZING",
  "payload": {
    "unit_title": "Audit Logging Infrastructure",
    "objective": "Inspect M1.1 migration scope. Do not modify anything. Return a structured report.",
    "explicit_request": "Review task execution report and issue next TASK_DISPATCH or decision directive.",
    "context": {
      "dependencies": [],
      "allowed_files": [],
      "task_report": {
        "task_id": "task_20260921_01",
        "unit_id": "M1.1",
        "status": "COMPLETED",
        "summary": "Inspected legacy audit_logs schema. Identified 6 columns: id, user_id, action, ip_address, user_agent, created_at. Target repository has no conflicting models or migrations. Working tree is clean.",
        "changes": [],
        "tests": {
          "run": false,
          "syntax_check": "PASS"
        },
        "issues": [],
        "git": {
          "branch": "migration-alpha",
          "clean": true
        }
      }
    }
  }
}
```

### Step 2b: ChatGPT Acknowledges Report & Issues Approval (`msg_resp_20260921_06F`)
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_20260921_06F",
  "in_reply_to": "msg_req_20260921_06F",
  "run_id": "run_20260921_01",
  "unit_id": "M1.1",
  "timestamp": "2026-09-21T18:47:00Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "PLAN_APPROVED",
  "payload": {
    "reason": "Task report verified: legacy audit_logs schema confirmed and target tree is clean. Scope is ready for implementation.",
    "state_transition": {
      "target_state": "READY",
      "authorized": true
    }
  }
}
```

