<?php

/**
 * CampusLynk Migration Control Plane — Dispatch Supervisor Next Action Decision
 *
 * Transmits the authoritative migration context to ChatGPT (Supervisor/Architect)
 * to determine the next migration action following M1.1 completion.
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
$currentCommit = trim(shell_exec('git -C ' . escapeshellarg($baseDir) . ' rev-parse HEAD') ?? '06f68cc61082081c4e598bfe6a61d6b1b6222f0d');

$completedUnits = [];
$remainingUnits = [];

foreach ($state['units'] as $uId => $uData) {
    if (($uData['state'] ?? '') === 'COMPLETED') {
        $completedUnits[] = $uId . ' (' . ($uData['title'] ?? '') . ')';
    } else {
        $remainingUnits[] = $uId . ' (' . ($uData['title'] ?? '') . ') [deps: ' . implode(', ', $uData['dependencies'] ?? []) . ']';
    }
}

$completedUnitsStr = empty($completedUnits) ? 'None' : implode("\n- ", $completedUnits);
$remainingUnitsStr = empty($remainingUnits) ? 'None' : implode("\n- ", $remainingUnits);

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT STATUS & REPOSITORY STATE
- Branch: {$state['branch']['name']}
- Base: {$state['branch']['base']}
- Current Commit: {$currentCommit}
- System Phase: {$state['system']['phase']}
- Active Execution State: {$state['current_execution']['state']}
- Units Completed: {$state['metrics']['units_completed']} / {$state['metrics']['units_total']}

## COMPLETED UNITS:
- {$completedUnitsStr}

## REMAINING UNITS:
- {$remainingUnitsStr}

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

## 3. DEPENDENCY_GRAPH.md
```markdown
{$depGraphMd}
```

---

## 4. MIGRATION_UNIT_SPEC.md
```markdown
{$unitSpecMd}
```

---

## 5. SUPERVISOR_PROTOCOL.md
```markdown
{$supervisorProtoMd}
```

---

## 6. CHECKPOINT_PROTOCOL.md
```markdown
{$checkpointProtoMd}
```

---

## 7. DESIGN_SYSTEM.md (MANDATORY ARCHITECTURE & DESIGN SYSTEM)
```markdown
{$designSystemMd}
```

---

## 8. AI_MIGRATION_RULES.md
```markdown
{$aiRulesMd}
```

---

# DIRECTIVE FOR SUPERVISOR

M1.1 is COMPLETED.

Determine the next migration action.

Do not assume that the next action is simply "implement M1.2".
Inspect the migration control-plane context and determine the correct next task and decomposition.

## MANDATORY ARCHITECTURAL DIRECTIVE:
Every migration decision, implementation plan, and task dispatch MUST strictly consider existing CampusLynk global components (`<x-ui.*>`) and Master Shell layouts (`<x-layouts.*>`) as defined in `DESIGN_SYSTEM.md` to prevent duplication:
- Virtual classrooms/studios MUST mount inside `<x-layouts.workspace-layout>`.
- Staff dashboards MUST mount inside `<x-layouts.app-shell>` or `<x-layouts.faculty-shell>`.
- Print reports MUST mount inside `<x-layouts.report-layout>`.
- UI elements MUST reuse `<x-ui.*>` components (`button`, `card`, `modal`, `table`, `badge`, `tabs`, `alert`, `input`, `select`, `icon`).
- NEVER duplicate components, create custom modal dialogs, or write raw table markup when global components exist.
- Monolithic legacy views (>500 lines) MUST be decomposed into modular Blade partials and components.

Return either:

TASK_DISPATCH
or
ESCALATE
or
ABORT
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'ARCHITECTURE_REVIEW',
    'M1.1',
    'COMPLETED',
    [
        'unit_title' => 'M1.1 Completed — Supervisor Next Migration Action Decision',
        'objective' => 'Inspect the migration control-plane context and determine the correct next task and decomposition following M1.1 completion.',
        'explicit_request' => "M1.1 is COMPLETED. Determine the next migration action. Do not assume that the next action is simply 'implement M1.2'. Inspect the migration control-plane context and determine the correct next task and decomposition. Return either TASK_DISPATCH, ESCALATE, or ABORT.",
        'context' => [
            'dependencies' => [],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ]
        ]
    ],
    $runId
);

echo "Dispatching Supervisor Next Action Decision request to ChatGPT via CDP bridge...\n";
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
