<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.2 Next Action / Task Dispatch Request to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$stateFile = $baseDir . '/docs/migration/STATE.json';
$featureMatrixFile = $baseDir . '/docs/migration/FEATURE_MATRIX.md';
$unitSpecFile = $baseDir . '/docs/migration/MIGRATION_UNIT_SPEC.md';
$depGraphFile = $baseDir . '/docs/migration/DEPENDENCY_GRAPH.md';
$checkpointProtoFile = $baseDir . '/docs/migration/CHECKPOINT_PROTOCOL.md';
$supervisorProtoFile = $baseDir . '/docs/migration/SUPERVISOR_PROTOCOL.md';
$designSystemFile = $baseDir . '/docs/migration/DESIGN_SYSTEM.md';
$aiRulesFile = $baseDir . '/docs/migration/AI_MIGRATION_RULES.md';

$stateJson = file_get_contents($stateFile);
$featureMatrixMd = file_get_contents($featureMatrixFile);
$unitSpecMd = file_get_contents($unitSpecFile);
$depGraphMd = file_get_contents($depGraphFile);
$checkpointProtoMd = file_get_contents($checkpointProtoFile);
$supervisorProtoMd = file_get_contents($supervisorProtoFile);
$designSystemMd = file_exists($designSystemFile) ? file_get_contents($designSystemFile) : '';
$aiRulesMd = file_exists($aiRulesFile) ? file_get_contents($aiRulesFile) : '';

$state = json_decode($stateJson, true);
$currentCommit = trim(shell_exec('git -C ' . escapeshellarg($baseDir) . ' rev-parse HEAD') ?? 'da44888c716a59a7e3f4381ce4297094d106ec40');

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: {$state['branch']['name']}
- Base: {$state['branch']['base']}
- Current Commit: {$currentCommit}
- Active Execution State: IDLE
- Completed Units:
  - M1.1: Database Schema Migrations and Eloquent Models Parity (commit 06f68cc61082081c4e598bfe6a61d6b1b6222f0d)
  - M2.1: R21 Major Project Backend Controller and Evaluation Endpoints (commit da44888c716a59a7e3f4381ce4297094d106ec40)
- Test Suite Status: 24 tests passed (165 assertions), 0 failures, 0 regressions.

---

## 1. STATE.json
```json
{$stateJson}
```

---

## 2. FEATURE_MATRIX.md
```markdown
{$featureMatrixMd}
```

---

## 3. DESIGN_SYSTEM.md (MANDATORY ARCHITECTURE & DESIGN SYSTEM)
```markdown
{$designSystemMd}
```

---

## 4. DEPENDENCY_GRAPH.md
```markdown
{$depGraphMd}
```

---

## 5. MIGRATION_UNIT_SPEC.md (M2.2 SPECIFICATION)
```markdown
{$unitSpecMd}
```

---

# DIRECTIVE FOR SUPERVISOR:
M2.1 is COMPLETED and checkpointed under commit `da44888c716a59a7e3f4381ce4297094d106ec40`.
The dependency gate for unit M2.2 (R21 Major Project Frontend Workspace Layout & UI Modernization) is now fully satisfied.

Please evaluate M2.2 and issue the formal TASK_DISPATCH for M2.2.

## CRITICAL MANDATE: DESIGN SYSTEM COMPLIANCE & ELIMINATING UI DUPLICATION
Every migration decision and implementation instruction for M2.2 MUST enforce the CampusLynk Design System (`DESIGN_SYSTEM.md`):
1. **Master Shell**: The virtual classroom workspace MUST be mounted inside `<x-layouts.workspace-layout>` (or `<x-layouts.app-shell>` for faculty dashboards).
2. **Global Components**: Reuse `<x-ui.*>` components (`button`, `card`, `modal`, `table`, `badge`, `tabs`, `alert`, `input`, `select`, `icon`). DO NOT create custom modal dialogs, duplicate button styles, inline CSS classes, or raw unstyled HTML tables.
3. **Decompose Monoliths**: The legacy 2,611-line view (`virtual_classroom_project.blade.php`) MUST NOT be copied as a monolithic file. It must be decomposed into clean, modular Blade partials and components.
4. **Anti-Duplication**: Eliminating component and UI duplication is the primary goal of this migration along with the modern UI overhaul.

Return decision: 'TASK_DISPATCH' with payload.task_dispatch.
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'ARCHITECTURE_REVIEW',
    'M2.2',
    'NOT_STARTED',
    [
        'unit_title' => 'R21 Major Project Frontend Workspace Layout & UI Modernization (M2.2)',
        'objective' => 'Evaluate unit M2.2 following M2.1 completion and issue the initial task dispatch (e.g. M2.2-ANALYZE-001 or M2.2-IMPLEMENT-001) with strict design-system and anti-duplication constraints.',
        'explicit_request' => "M2.1 is COMPLETED and checkpointed (commit da44888c). All dependencies for M2.2 are satisfied. Please evaluate M2.2 and issue the formal TASK_DISPATCH for M2.2. Every task must consider existing CampusLynk global shells (<x-layouts.workspace-layout>) and components (<x-ui.*>) to avoid duplication. Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M2.1'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.2 Task Dispatch Request to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 90, false, $authoritativeContext);

echo "\n--- Bridge Response Result ---\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if (!empty($result['success'])) {
    echo "\n[SUCCESS] Received validated supervisor directive from ChatGPT!\n";
    echo "Decision: " . ($result['decision'] ?? 'UNKNOWN') . "\n";
    exit(0);
} else {
    echo "\n[FAIL] Bridge request failed or rejected by fail-closed guardrails.\n";
    exit(1);
}
