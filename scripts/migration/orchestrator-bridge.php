<?php

/**
 * CampusLynk Autonomous Migration System — Orchestrator Communication Bridge
 *
 * Thin, deterministic communication controller between the local environment
 * and the ChatGPT Architect/Supervisor.
 *
 * ARCHITECTURAL PRINCIPLE:
 * ChatGPT is the sole decision-maker (Architect/Supervisor).
 * This controller handles ONLY:
 * - Request envelope packaging
 * - Transport via Chrome CDP bridge
 * - JSON extraction from markdown
 * - Schema validation (RESPONSE_SCHEMA.json)
 * - 4-point correlation enforcement
 * - Isolated runtime persistence (docs/migration/runtime/)
 * - Fail-closed safety guardrails
 *
 * This controller NEVER decides migration units, steps, or approvals.
 *
 * Usage:
 *   php scripts/migration/orchestrator-bridge.php --help
 *   php scripts/migration/orchestrator-bridge.php --shadow
 */

declare(strict_types=1);

class OrchestratorBridge
{
    private string $baseDir;
    private string $protocolDir;
    private string $runtimeDir;
    private string $bridgeScript;
    private string $bridgeOutput;
    private array $reqSchema;
    private array $respSchema;

    public function __construct(?string $baseDir = null)
    {
        $this->baseDir = $baseDir ?? dirname(__DIR__, 2);
        $this->protocolDir = $this->baseDir . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'migration' . DIRECTORY_SEPARATOR . 'protocol';
        $this->runtimeDir = $this->baseDir . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'migration' . DIRECTORY_SEPARATOR . 'runtime';
        $this->bridgeScript = $this->baseDir . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'migration' . DIRECTORY_SEPARATOR . 'chat-bridge' . DIRECTORY_SEPARATOR . 'test-bridge.ps1';
        $this->bridgeOutput = $this->baseDir . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'migration' . DIRECTORY_SEPARATOR . 'chat-bridge' . DIRECTORY_SEPARATOR . 'test-output.txt';

        $this->loadSchemas();
    }

    private function loadSchemas(): void
    {
        $reqSchemaPath = $this->protocolDir . DIRECTORY_SEPARATOR . 'REQUEST_SCHEMA.json';
        $respSchemaPath = $this->protocolDir . DIRECTORY_SEPARATOR . 'RESPONSE_SCHEMA.json';

        if (!file_exists($reqSchemaPath) || !file_exists($respSchemaPath)) {
            throw new RuntimeException("Protocol schemas missing in {$this->protocolDir}");
        }

        $this->reqSchema = json_decode(file_get_contents($reqSchemaPath), true, 512, JSON_THROW_ON_ERROR);
        $this->respSchema = json_decode(file_get_contents($respSchemaPath), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Constructs a valid, schema-conforming request envelope.
     */
    public function createRequestEnvelope(
        string $messageType,
        string $unitId,
        string $stage,
        array $payload,
        ?string $runId = null
    ): array {
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        $entropy = substr(md5(uniqid((string)mt_rand(), true)), 0, 8);
        $msgId = 'msg_req_' . gmdate('Ymd_His') . '_' . $entropy;
        $activeRunId = $runId ?? ('run_' . gmdate('Ymd') . '_01');

        $envelope = [
            'protocol_version' => '1.0',
            'message_id' => $msgId,
            'run_id' => $activeRunId,
            'timestamp' => $timestamp,
            'sender' => 'orchestrator',
            'recipient' => 'architect',
            'message_type' => $messageType,
            'unit_id' => $unitId,
            'stage' => $stage,
            'payload' => $payload
        ];

        // Validate outbound envelope
        $errors = $this->validateSchema($envelope, $this->reqSchema);
        if (!empty($errors)) {
            throw new InvalidArgumentException("Outbound request fails REQUEST_SCHEMA: " . implode('; ', $errors));
        }

        return $envelope;
    }

    /**
     * Formats an envelope as markdown text with schema instructions suitable for ChatGPT prompt injection.
     */
    public function formatForPrompt(array $envelope, ?string $additionalContext = null): string
    {
        $json = json_encode($envelope, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $expectedFormat = ($envelope['message_type'] === 'TASK_REPORT')
            ? <<<TEMPLATE
Your response MUST be wrapped in a ```json ... ``` code block conforming to RESPONSE_SCHEMA.json:
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_$(Get-Date)_001",
  "in_reply_to": "{$envelope['message_id']}",
  "run_id": "{$envelope['run_id']}",
  "unit_id": "{$envelope['unit_id']}",
  "timestamp": "2026-09-21T18:47:00Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "PLAN_APPROVED",
  "payload": {
    "reason": "<Detailed architectural evaluation of the task report>",
    "state_transition": {
      "target_state": "READY",
      "authorized": true
    }
  }
}
```
TEMPLATE
            : <<<TEMPLATE
Your response MUST be wrapped in a ```json ... ``` code block conforming to RESPONSE_SCHEMA.json:
```json
{
  "protocol_version": "1.0",
  "message_id": "msg_resp_$(Get-Date)_001",
  "in_reply_to": "{$envelope['message_id']}",
  "run_id": "{$envelope['run_id']}",
  "unit_id": "{$envelope['unit_id']}",
  "timestamp": "2026-09-21T18:45:00Z",
  "sender": "architect",
  "recipient": "orchestrator",
  "decision": "TASK_DISPATCH",
  "payload": {
    "reason": "<Reason for task dispatch>",
    "state_transition": {
      "target_state": "ANALYZING",
      "authorized": true
    },
    "task_dispatch": {
      "task_id": "SHADOW-M1.1-001",
      "unit_id": "{$envelope['unit_id']}",
      "objective": "Inspect the existing M1.1 migration scope and determine what the first concrete implementation task should be.",
      "instructions": [
        "Inspect the existing migration control-plane information.",
        "Inspect the relevant target/legacy context necessary to understand M1.1.",
        "Do NOT modify any application files, migrations, models, or database.",
        "Return a TASK_REPORT only."
      ],
      "constraints": [
        "DO NOT modify any application files.",
        "DO NOT modify database schema.",
        "DO NOT execute migrations."
      ],
      "expected_report": "What M1.1 currently requires, first concrete implementation task, relevant files discovered, relevant legacy/target differences, risks or ambiguities, recommended next action.",
      "stop_conditions": [
        "Do not make any repository modifications.",
        "Stop after producing the report."
      ]
    }
  }
}
```
TEMPLATE;

        $prompt = "CAMPUSLYNK MIGRATION ORCHESTRATOR REQUEST\n\n" .
                  "Unit: {$envelope['unit_id']} | Stage: {$envelope['stage']} | Type: {$envelope['message_type']}\n\n";

        $prompt .= "MANDATORY ARCHITECTURAL & DESIGN DIRECTIVE:\n" .
                   "Every migration decision, implementation specification, and task dispatch MUST strictly consider existing CampusLynk global components and design language to prevent duplication:\n" .
                   "1. Master Shells: Virtual classrooms/studios MUST mount inside <x-layouts.workspace-layout>; staff dashboards inside <x-layouts.app-shell> or <x-layouts.faculty-shell>; print views inside <x-layouts.report-layout>.\n" .
                   "2. Global UI Components: All UI elements MUST reuse <x-ui.*> components (<x-ui.button>, <x-ui.card>, <x-ui.modal>, <x-ui.table>, <x-ui.badge>, <x-ui.tabs>, <x-ui.alert>, <x-ui.input>, <x-ui.select>, <x-ui.icon>).\n" .
                   "3. Anti-Duplication: NEVER create ad-hoc button styles, custom modal dialogs, or raw table markup when global components exist.\n" .
                   "4. Decomposition: Monolithic legacy views (>500 lines) MUST be decomposed into modular Blade partials.\n\n";

        if ($additionalContext !== null && trim($additionalContext) !== '') {
            $prompt .= $additionalContext . "\n\n";
        }

        $prompt .= "Request Envelope:\n```json\n{$json}\n```\n\n" .
                   "Response Requirement (STRICT RESPONSE_SCHEMA.json compliance — payload has additionalProperties: false; do NOT invent keys like 'next_directive'):\n{$expectedFormat}";

        return $prompt;
    }

    /**
     * Dispatches a request through the CDP bridge and retrieves/validates the response.
     */
    public function send(array $requestEnvelope, int $timeoutSeconds = 60, bool $shadow = false, ?string $additionalContext = null): array
    {
        $reqJson = json_encode($requestEnvelope, JSON_UNESCAPED_SLASHES);
        $prompt = $this->formatForPrompt($requestEnvelope, $additionalContext);

        if ($shadow) {
            echo "  [SHADOW MODE] Simulated dispatch of request: {$requestEnvelope['message_id']}\n";
            return [
                'success' => true,
                'shadow' => true,
                'request' => $requestEnvelope,
                'status' => 'DISPATCH_SIMULATED'
            ];
        }

        // 1. Dispatch via PowerShell CDP bridge using a temporary prompt file
        if (!is_dir($this->runtimeDir)) {
            @mkdir($this->runtimeDir, 0755, true);
        }
        $promptFile = $this->runtimeDir . DIRECTORY_SEPARATOR . 'active_prompt.txt';
        file_put_contents($promptFile, $prompt);

        $cmd = sprintf(
            'powershell -ExecutionPolicy Bypass -Command "$msg = [System.IO.File]::ReadAllText(\'%s\', [System.Text.Encoding]::UTF8); & \'%s\' -Message $msg -TimeoutSeconds %d"',
            addslashes($promptFile),
            addslashes($this->bridgeScript),
            $timeoutSeconds
        );

        $output = [];
        $exitCode = 0;
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($this->bridgeOutput)) {
            $this->logExchange($requestEnvelope, null, 'TRANSPORT_ERROR', "Bridge exited with code $exitCode");
            return [
                'success' => false,
                'error' => "Transport bridge failure (Exit code: $exitCode)",
                'fail_closed' => true
            ];
        }

        $rawResponse = file_get_contents($this->bridgeOutput);

        // 2. Extract structured JSON block from markdown
        $parsedResponse = $this->extractJsonBlock($rawResponse);
        if ($parsedResponse === null) {
            $this->logExchange($requestEnvelope, ['raw' => $rawResponse], 'SYNTAX_ERROR', 'Failed to extract JSON code block');
            return [
                'success' => false,
                'error' => 'Malformed response: No valid JSON code block found in assistant response',
                'fail_closed' => true
            ];
        }

        // 3. Schema validation
        $schemaErrors = $this->validateSchema($parsedResponse, $this->respSchema);
        if (!empty($schemaErrors)) {
            $this->logExchange($requestEnvelope, $parsedResponse, 'SCHEMA_ERROR', implode('; ', $schemaErrors));
            return [
                'success' => false,
                'error' => 'Schema validation failed: ' . implode('; ', $schemaErrors),
                'fail_closed' => true
            ];
        }

        // 4. Correlation check
        $corrErrors = $this->validateCorrelation($requestEnvelope, $parsedResponse);
        if (!empty($corrErrors)) {
            $this->logExchange($requestEnvelope, $parsedResponse, 'CORRELATION_ERROR', implode('; ', $corrErrors));
            return [
                'success' => false,
                'error' => 'Correlation check failed: ' . implode('; ', $corrErrors),
                'fail_closed' => true
            ];
        }

        // 5. Persist successful exchange in runtime log
        $this->logExchange($requestEnvelope, $parsedResponse, 'VALIDATED', 'Exchange completed and verified');

        return [
            'success' => true,
            'response' => $parsedResponse,
            'decision' => $parsedResponse['decision'],
            'payload' => $parsedResponse['payload']
        ];
    }

    /**
     * Extracts JSON from ```json ... ``` code blocks, HTML pre/code blocks, or outermost JSON objects.
     */
    public function extractJsonBlock(string $text): ?array
    {
        // 1. Check for fenced code block ```json { ... } ```
        if (preg_match('/```(?:json)?\s*(\{[\s\S]*?\})\s*```/i', $text, $matches)) {
            $decoded = json_decode($matches[1], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        // 2. Check for outermost JSON object { ... } (handles browser innerText with 'JSON\n{...}')
        if (preg_match('/(\{[\s\S]*\})/i', $text, $matches)) {
            $decoded = json_decode($matches[1], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        // 3. Fallback: entire text is raw JSON
        $decoded = json_decode(trim($text), true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return null;
    }

    /**
     * Validates an associative array against a JSON schema definition.
     */
    public function validateSchema(mixed $data, array $schema, string $path = ''): array
    {
        $errs = [];

        if (isset($schema['type'])) {
            $types = is_array($schema['type']) ? $schema['type'] : [$schema['type']];
            $matched = false;
            foreach ($types as $t) {
                if ($t === 'object' && is_array($data) && (empty($data) || array_keys($data) !== range(0, count($data) - 1))) $matched = true;
                elseif ($t === 'array' && is_array($data) && (empty($data) || array_keys($data) === range(0, count($data) - 1))) $matched = true;
                elseif ($t === 'string' && is_string($data)) $matched = true;
                elseif ($t === 'boolean' && is_bool($data)) $matched = true;
                elseif ($t === 'integer' && is_int($data)) $matched = true;
                elseif ($t === 'null' && is_null($data)) $matched = true;
            }
            if (!$matched) {
                $errs[] = ($path ?: 'root') . " expected [" . implode('|', $types) . "], got " . gettype($data);
                return $errs;
            }
        }

        if (isset($schema['required']) && is_array($data)) {
            foreach ($schema['required'] as $req) {
                if (!array_key_exists($req, $data)) {
                    $errs[] = ($path ? "$path." : "") . "$req is required";
                }
            }
        }

        if (isset($schema['enum'])) {
            if (!in_array($data, $schema['enum'], true)) {
                $errs[] = ($path ?: 'root') . " value '" . (is_string($data) ? $data : json_encode($data)) . "' not in enum";
            }
        }

        if (isset($schema['pattern']) && is_string($data)) {
            if (@preg_match('/' . $schema['pattern'] . '/', $data) !== 1) {
                $errs[] = ($path ?: 'root') . " does not match pattern /{$schema['pattern']}/";
            }
        }

        if (isset($schema['properties']) && is_array($data)) {
            foreach ($schema['properties'] as $prop => $sub) {
                if (array_key_exists($prop, $data)) {
                    $errs = array_merge($errs, $this->validateSchema($data[$prop], $sub, ($path ? "$path." : "") . $prop));
                }
            }
        }

        if (isset($schema['additionalProperties']) && $schema['additionalProperties'] === false && is_array($data)) {
            $allowed = isset($schema['properties']) ? array_keys($schema['properties']) : [];
            foreach (array_keys($data) as $k) {
                if (!in_array($k, $allowed, true)) {
                    $errs[] = ($path ? "$path." : "") . "$k is an unauthorized property";
                }
            }
        }

        if (isset($schema['items']) && is_array($data)) {
            foreach ($data as $i => $item) {
                $errs = array_merge($errs, $this->validateSchema($item, $schema['items'], ($path ?: '') . "[$i]"));
            }
        }

        return $errs;
    }

    /**
     * Enforces the 4-point correlation check and decision compatibility matrix.
     */
    public function validateCorrelation(array $req, array $resp): array
    {
        $errs = [];

        if (!isset($resp['in_reply_to']) || $resp['in_reply_to'] !== ($req['message_id'] ?? null)) {
            $errs[] = "in_reply_to mismatch: expected '{$req['message_id']}', got '" . ($resp['in_reply_to'] ?? 'NULL') . "'";
        }

        if (!isset($resp['run_id']) || $resp['run_id'] !== ($req['run_id'] ?? null)) {
            $errs[] = "run_id mismatch: expected '{$req['run_id']}', got '" . ($resp['run_id'] ?? 'NULL') . "'";
        }

        $expectedUnit = $req['unit_id'] ?? null;
        $actualUnit = $resp['unit_id'] ?? null;
        $dispatchedUnit = $resp['payload']['task_dispatch']['unit_id'] ?? null;

        if ($actualUnit === null || ($actualUnit !== $expectedUnit && $actualUnit !== $dispatchedUnit)) {
            $errs[] = "unit_id mismatch: expected '{$expectedUnit}', got '" . ($actualUnit ?? 'NULL') . "'";
        }

        // Decision matrix check
        $matrix = [
            'ARCHITECTURE_REVIEW'   => ['PLAN_APPROVED', 'REVISION_REQUIRED', 'TASK_DISPATCH', 'ESCALATE', 'ABORT'],
            'IMPLEMENTATION_REVIEW' => ['IMPLEMENTATION_APPROVED', 'REPAIR_REQUIRED', 'REPLAN_REQUIRED', 'TASK_DISPATCH', 'ESCALATE', 'ABORT'],
            'VERIFICATION_REVIEW'   => ['VERIFICATION_APPROVED', 'REPAIR_REQUIRED', 'TASK_DISPATCH', 'ESCALATE', 'ABORT'],
            'REPAIR_REVIEW'         => ['PLAN_APPROVED', 'REVISION_REQUIRED', 'TASK_DISPATCH', 'ESCALATE', 'ABORT'],
            'ESCALATION_REVIEW'     => ['PLAN_APPROVED', 'REPLAN_REQUIRED', 'ABORT', 'ESCALATE'],
            'FINAL_UNIT_REVIEW'     => ['VERIFICATION_APPROVED', 'ESCALATE', 'ABORT'],
            'TASK_REPORT'           => ['TASK_DISPATCH', 'PLAN_APPROVED', 'REVISION_REQUIRED', 'REPAIR_REQUIRED', 'ESCALATE', 'ABORT']
        ];

        $msgType = $req['message_type'] ?? '';
        $decision = $resp['decision'] ?? '';

        if (!isset($matrix[$msgType])) {
            $errs[] = "Unknown message_type '$msgType'";
        } elseif (!in_array($decision, $matrix[$msgType], true)) {
            $errs[] = "Decision '$decision' is not permitted for message_type '$msgType'";
        }

        return $errs;
    }

    /**
     * Persists an exchange to the isolated runtime directory.
     */
    private function logExchange(array $req, ?array $resp, string $status, string $detail): void
    {
        if (!is_dir($this->runtimeDir)) {
            @mkdir($this->runtimeDir, 0755, true);
        }

        $timestamp = gmdate('Ymd_His');
        $msgId = $req['message_id'] ?? 'unknown';
        $logFile = $this->runtimeDir . DIRECTORY_SEPARATOR . "exchange_{$timestamp}_{$msgId}.json";

        $record = [
            'timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'status' => $status,
            'detail' => $detail,
            'request' => $req,
            'response' => $resp
        ];

        file_put_contents($logFile, json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

// -----------------------------------------------------------------------------
// CLI Runner (Shadow Mode & Self-Test)
// -----------------------------------------------------------------------------
if (php_sapi_name() === 'cli' && isset($argv[0]) && realpath($argv[0]) === __FILE__) {
    echo "===============================================================\n";
    echo " CampusLynk Orchestrator Communication Bridge\n";
    echo "===============================================================\n";

    $opts = getopt('', ['shadow', 'help']);

    if (isset($opts['help'])) {
        echo "Usage:\n";
        echo "  php scripts/migration/orchestrator-bridge.php --shadow   Run shadow-mode self-test\n";
        echo "  php scripts/migration/orchestrator-bridge.php --help     Show this help message\n";
        exit(0);
    }

    try {
        $bridge = new OrchestratorBridge();
        echo "[OK] Controller initialized. Schemas loaded.\n";

        if (isset($opts['shadow'])) {
            echo "\n--- Running Shadow Mode Self-Test ---\n";

            // Synthetic test request
            $req = $bridge->createRequestEnvelope(
                'ARCHITECTURE_REVIEW',
                'M1.1',
                'ANALYZING',
                [
                    'unit_title' => 'Audit Logging Infrastructure',
                    'objective' => 'Shadow mode probe of communication controller.',
                    'explicit_request' => 'Confirm communication channel readiness.',
                    'context' => [
                        'dependencies' => [],
                        'allowed_files' => ['app/Models/AuditLog.php']
                    ]
                ]
            );

            echo "[OK] Request envelope generated: {$req['message_id']}\n";
            $res = $bridge->send($req, 10, true);

            echo "[OK] Shadow dispatch result: " . json_encode($res) . "\n";
            echo "\nSHADOW MODE TEST: PASS\n";
            exit(0);
        }

        echo "\nController is ready for orchestrator integration.\n";
        echo "Run with --shadow to perform a non-invasive synthetic test.\n";
        exit(0);

    } catch (Throwable $e) {
        echo "[FAIL] " . $e->getMessage() . "\n";
        exit(1);
    }
}
