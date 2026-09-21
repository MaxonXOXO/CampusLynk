# CampusLynk Autonomous Migration System (Control Plane)

> **Status:** Specification & Foundation Bootstrap  
> **Target Repository:** `MaxonXOXO/CampusLynk` (`d:\CampusLynk\CampusLynk`)  
> **Legacy Reference Repository:** `MaxonXOXO/academic-platform` (`d:\CampusLynk\legacy-academic-platform`)  
> **Current Active Branch:** `migration-alpha` (Branched from `main` @ `efefd640076c33e84adcb18a6e787f42bed108d8`)  

---

## 1. Purpose & Scope

The **CampusLynk Autonomous Migration System** is an engineering framework designed to systematically, reliably, and autonomously port missing academic and operational features from the legacy platform (`academic-platform`) into the modernized target application (`CampusLynk`).

- **CampusLynk is the Target Application:** All ported functionality must natively conform to CampusLynk's modernized design system, Master Shell layouts (`faculty-shell`, `app-shell`, `workspace-layout`), atomic Blade components, local Vite/Tailwind asset pipeline, and role separation architecture.
- **`academic-platform` is the Legacy Behavioral Reference:** The legacy codebase is an authoritative reference for domain workflows, formulas, rubrics, and business rules, but its internal architecture, monolithic Blade templates, and layout conventions are legacy.
- **Legacy Code is Read-Only Reference Material:** No code in the legacy repository may be modified, executed as a runtime dependency, or directly copy-pasted without architectural translation.
- **CampusLynk Architecture Governs All Implementation:** Legacy code structures (e.g. monolithic 7,000-line Blade files, inline styles, duplicate navigation bars) must NOT be imported. Features must be rebuilt on CampusLynk's design system tokens and component hierarchy.

---

## 2. Core Migration Principles

1. **Small, Independently Verifiable Units (Vertical Slices):**
   Migration does not happen in bulk. Each feature or capability is divided into discrete, bounded migration units containing schema requirements, models, controllers, routes, views, and verification tests.
2. **Persistent Migration State:**
   The status, dependencies, and lifecycle of every migration unit are tracked persistently in `docs/migration/STATE.json` and `docs/migration/FEATURE_MATRIX.md`.
3. **Git Commits as Checkpoints:**
   Each completed and verified migration unit produces an atomic Git commit on `migration-alpha`. Commits represent clean rollback points and verifiable milestones.
4. **Autonomous Agent Pipeline (Future Engine):**
   The autonomous migration execution will follow a strict five-role pipeline:
   $$\text{Scanner} \longrightarrow \text{Architect} \longrightarrow \text{Implementer} \longrightarrow \text{Verifier} \longrightarrow \text{Supervisor}$$
5. **Reserved Human Intervention:**
   Autonomous agents operate within strict guardrails. Human escalation is strictly required for:
   - Requirements ambiguity or behavioral contradictions between legacy and target.
   - Destructive database or file operations.
   - Repeated verification or implementation failures (loop detection).
   - Significant architectural decisions not covered by existing Architecture Decision Records (ADRs).

---

## 3. Directory Structure

```text
docs/migration/
├── README.md                          # Migration system overview and operational context
├── AI_MIGRATION_RULES.md              # Immutable operating rules for autonomous agents
├── MIGRATION_DEFINITION_OF_DONE.md    # Strict completion criteria for each migration unit
├── ARCHITECTURE.md                    # Planned autonomous multi-agent migration architecture
├── STATE.json                         # Machine-readable current migration control plane state
├── FEATURE_MATRIX.md                  # Comprehensive feature inventory and porting status
└── DECISIONS.md                       # Architecture Decision Records (ADRs)
```

> **Note on Implementation Status:** This repository branch (`migration-alpha`) establishes the specification, rules, and control plane. Autonomous agent runners, task queues, and executors will be built upon this foundation. No feature code has been migrated yet.
