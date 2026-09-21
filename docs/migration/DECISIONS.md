# Architecture Decision Records (ADRs)

> **Context:** CampusLynk Autonomous Migration System  
> This document records foundational architectural decisions. Future agents and human contributors must preserve these decisions.

---

### ADR-001: CampusLynk as the Primary Target Architecture
- **Status:** Accepted
- **Context:** The legacy codebase (`academic-platform`) contains valuable domain logic but utilizes monolithic templates and duplicate layouts. CampusLynk has established a modernized Master Shell kit, design tokens, and role-based consoles.
- **Decision:** CampusLynk remains the sole implementation and architecture target. No legacy layouts, monolithic headers, or CDN assets may be imported.

---

### ADR-002: Legacy Repository as Read-Only Reference Source
- **Status:** Accepted
- **Context:** Code must be ported from `academic-platform` into `CampusLynk`.
- **Decision:** The legacy repository is strictly an authoritative behavioral and reference source. It will never be modified, used as a Git submodule, or included as a runtime dependency.

---

### ADR-003: Independently Verifiable Vertical Slices
- **Status:** Accepted
- **Context:** Big-bang migrations introduce high regression risks and untraceable bugs.
- **Decision:** Migration proceeds feature-by-feature via small, bounded vertical slices containing schema, models, services, controllers, routes, Blade partials, and test suites.

---

### ADR-004: Git Commits as Migration Checkpoints
- **Status:** Accepted
- **Context:** Long-running autonomous operations require clean rollback and audit points.
- **Decision:** Every successfully verified migration unit produces an atomic Git checkpoint commit on the migration branch.

---

### ADR-005: Feature Flags for Incomplete or Phased Functionality
- **Status:** Accepted
- **Context:** Incomplete features merged into development branches can destabilize existing workflows.
- **Decision:** Newly ported, high-risk, or auxiliary features must be guarded with configuration or database feature flags (e.g. `FEATURE_CARMIE_AI`, `FEATURE_R21_PROJECT`) until fully stabilized.

---

### ADR-006: Prohibition of Agent Self-Certification
- **Status:** Accepted
- **Context:** Generative models can hallucinate completeness without executing real checks.
- **Decision:** Agents cannot mark a migration unit complete simply by visual inspection or declaration. Passing automated tests, route verification outputs, and strict DoD satisfaction are required.

---

### ADR-007: Mandatory Human Escalation
- **Status:** Accepted
- **Context:** Autonomous agents must not make unilateral destructive choices or enter infinite repair loops.
- **Decision:** Human escalation is strictly required for destructive database/file operations, behavioral ambiguities, and when automated repair fails after three (3) consecutive attempts.
