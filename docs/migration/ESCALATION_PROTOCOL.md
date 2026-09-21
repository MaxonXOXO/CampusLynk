# Human Escalation Protocol

> **Context:** CampusLynk Autonomous Migration System  
> **Status:** Active Protocol  
> **Version:** 0.2.0  

This document defines the triggers, data schemas, and lifecycle for human escalation when autonomous agents encounter conditions requiring human decision-making.

---

## 1. Escalation Trigger Categories

The Supervisor must immediately halt automated processing and escalate to human review upon encountering any of the following triggers:

| Trigger Code | Category | Description | Severity |
| :--- | :--- | :--- | :---: |
| `DESTRUCTIVE_DATABASE_OPERATION` | Safety | An operation requests `DROP TABLE`, `TRUNCATE`, `migrate:fresh`, or column removal. | 🔴 Critical |
| `ARCHITECTURAL_CONFLICT` | Architecture | A proposed implementation violates `AI_MIGRATION_RULES.md` or conflicts with existing Master Shell designs. | 🟠 High |
| `AMBIGUOUS_LEGACY_BEHAVIOR` | Domain | Legacy code contains conflicting, contradictory, or undocumented business/grading logic. | 🟠 High |
| `SECURITY_SENSITIVE_CHANGE` | Security | Modification touches authentication tokens, password hashing, user role assignment, or session cookies. | 🔴 Critical |
| `PRODUCTION_DATA_REQUIRED` | Data | Feature requires external API keys (e.g. OpenAI key for Carmie) or production data dumps not present locally. | 🟡 Medium |
| `REPEATED_FAILURE` | Reliability | A migration unit reaches `max_attempts` (default: 3) without achieving a Verifier `PASS`. | 🟠 High |
| `UNRESOLVED_DEPENDENCY` | Dependency | A required upstream dependency is missing, cyclic, or marked `UNKNOWN`. | 🟡 Medium |
| `SCOPE_EXPANSION` | Scope | An agent attempts to modify files outside the approved specification whitelist. | 🟠 High |

---

## 2. Escalation Record Schema

Every escalation event is assigned a unique ID and appended to the `escalations` array in `STATE.json`:

```yaml
id: ESC-2026-001
unit: M2.1
reason: REPEATED_FAILURE
evidence:
  attempt_count: 3
  last_test_output: "Failed asserting that true matches expected false in R21MajorProjectModelsTest.php:42"
  diagnostic: "Foreign key constraint fails on batch_subjects_id during model creation."
attempts: 3
recommended_action: "Review batch_subjects foreign key schema in active database or adjust model relationship."
status: RAISED # RAISED | ACKNOWLEDGED | RESOLVED | REJECTED
created_at: "2026-09-21T18:15:00Z"
resolved_at: null
resolution_notes: null
```

---

## 3. Escalation Lifecycle

```text
  ┌──────────┐
  │  RAISED  │ ◄── Triggered by Supervisor
  └────┬─────┘
       │
       ▼
┌──────────────┐
│ ACKNOWLEDGED │ ◄── Human reviewer inspects diagnostic
└──────┬───────┘
       │
       ├───► [RESOLVED] ──► Action taken (e.g. schema adjusted, spec refined)
       │          │
       │          ▼
       │     [RESUMED]  ──► Supervisor resets attempts = 0, state = READY / ANALYZING
       │
       └───► [REJECTED] ──► Unit marked DEFERRED or CANCELLED
```

---

## 4. State-Preserving Resume Protocol

When an escalation is resolved by human review:
1. The human reviewer edits the escalation record in `STATE.json`:
   - Set `status: "RESOLVED"`
   - Set `resolved_at: "<ISO 8601 timestamp>"`
   - Add `resolution_notes: "<Explanation of resolution>"`
2. The human reviewer adjusts the target unit's state:
   - Reset `attempts: 0`
   - Set `state: "READY"` (if code/spec is ready) or `state: "ANALYZING"` (if re-analysis is required).
3. The Supervisor reads the updated `STATE.json` on next tick and resumes execution seamlessly without loss of previous checkpoint history.
