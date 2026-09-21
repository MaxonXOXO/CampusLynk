<?php

/**
 * CampusLynk Autonomous Migration System — Protocol Validator
 *
 * Deterministically validates protocol schemas, example message fixtures,
 * correlation mechanics, and fail-closed negative test cases.
 *
 * Usage:
 *   php scripts/migration/validate-protocol.php
 */

declare(strict_types=1);

$baseDir = dirname(__DIR__, 2);
$protocolDir = $baseDir . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'migration' . DIRECTORY_SEPARATOR . 'protocol';

$reqSchemaFile = $protocolDir . DIRECTORY_SEPARATOR . 'REQUEST_SCHEMA.json';
$respSchemaFile = $protocolDir . DIRECTORY_SEPARATOR . 'RESPONSE_SCHEMA.json';
$examplesFile = $protocolDir . DIRECTORY_SEPARATOR . 'EXAMPLES.md';

$errors = [];
$passes = 0;

echo "===============================================================\n";
echo " CampusLynk Migration Protocol Validator\n";
echo "===============================================================\n";
echo "Protocol Directory: $protocolDir\n\n";

// -----------------------------------------------------------------------------
// 1. Load Schemas
// -----------------------------------------------------------------------------
echo "[1/4] Loading JSON Schemas...\n";

if (!file_exists($reqSchemaFile)) {
    echo "  [FAIL] Missing REQUEST_SCHEMA.json\n";
    exit(1);
}
if (!file_exists($respSchemaFile)) {
    echo "  [FAIL] Missing RESPONSE_SCHEMA.json\n";
    exit(1);
}

$reqSchema = json_decode(file_get_contents($reqSchemaFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "  [FAIL] REQUEST_SCHEMA.json is invalid JSON: " . json_last_error_msg() . "\n";
    exit(1);
}

$respSchema = json_decode(file_get_contents($respSchemaFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "  [FAIL] RESPONSE_SCHEMA.json is invalid JSON: " . json_last_error_msg() . "\n";
    exit(1);
}

echo "  [OK] REQUEST_SCHEMA.json loaded and valid.\n";
echo "  [OK] RESPONSE_SCHEMA.json loaded and valid.\n\n";

// -----------------------------------------------------------------------------
// 2. Deterministic JSON Schema Validator
// -----------------------------------------------------------------------------
function validateAgainstSchema(mixed $data, array $schema, string $path = ''): array {
    $errs = [];

    // Type check
    if (isset($schema['type'])) {
        $types = is_array($schema['type']) ? $schema['type'] : [$schema['type']];
        $typeMatched = false;
        foreach ($types as $t) {
            if ($t === 'object' && is_array($data) && (empty($data) || array_keys($data) !== range(0, count($data) - 1))) $typeMatched = true;
            elseif ($t === 'array' && is_array($data) && (empty($data) || array_keys($data) === range(0, count($data) - 1))) $typeMatched = true;
            elseif ($t === 'string' && is_string($data)) $typeMatched = true;
            elseif ($t === 'boolean' && is_bool($data)) $typeMatched = true;
            elseif ($t === 'integer' && is_int($data)) $typeMatched = true;
            elseif ($t === 'null' && is_null($data)) $typeMatched = true;
        }
        if (!$typeMatched) {
            $errs[] = ($path ?: 'root') . " expected type [" . implode('|', $types) . "], got " . gettype($data);
            return $errs;
        }
    }

    // Required fields (for objects)
    if (isset($schema['required']) && is_array($data)) {
        foreach ($schema['required'] as $req) {
            if (!array_key_exists($req, $data)) {
                $errs[] = ($path ? "$path." : "") . "$req is required";
            }
        }
    }

    // Enum check
    if (isset($schema['enum'])) {
        if (!in_array($data, $schema['enum'], true)) {
            $errs[] = ($path ?: 'root') . " value '" . (is_string($data) ? $data : json_encode($data)) . "' not in enum [" . implode(', ', array_map('json_encode', $schema['enum'])) . "]";
        }
    }

    // Pattern check
    if (isset($schema['pattern']) && is_string($data)) {
        if (@preg_match('/' . $schema['pattern'] . '/', $data) !== 1) {
            $errs[] = ($path ?: 'root') . " value '$data' does not match pattern /{$schema['pattern']}/";
        }
    }

    // MinLength check
    if (isset($schema['minLength']) && is_string($data)) {
        if (mb_strlen($data) < $schema['minLength']) {
            $errs[] = ($path ?: 'root') . " length must be >= {$schema['minLength']}";
        }
    }

    // Properties check (for objects)
    if (isset($schema['properties']) && is_array($data)) {
        foreach ($schema['properties'] as $prop => $subSchema) {
            if (array_key_exists($prop, $data)) {
                $subErrs = validateAgainstSchema($data[$prop], $subSchema, ($path ? "$path." : "") . $prop);
                $errs = array_merge($errs, $subErrs);
            }
        }
    }

    // AdditionalProperties check
    if (isset($schema['additionalProperties']) && $schema['additionalProperties'] === false && is_array($data)) {
        $allowedProps = isset($schema['properties']) ? array_keys($schema['properties']) : [];
        foreach (array_keys($data) as $key) {
            if (!in_array($key, $allowedProps, true)) {
                $errs[] = ($path ? "$path." : "") . "$key is an unauthorized property (additionalProperties: false)";
            }
        }
    }

    // Items check (for arrays)
    if (isset($schema['items']) && is_array($data)) {
        foreach ($data as $idx => $item) {
            $subErrs = validateAgainstSchema($item, $schema['items'], ($path ? "$path" : "") . "[$idx]");
            $errs = array_merge($errs, $subErrs);
        }
    }

    return $errs;
}

// -----------------------------------------------------------------------------
// 3. Correlation Validator
// -----------------------------------------------------------------------------
function validateCorrelation(array $req, array $resp): array {
    $errs = [];

    if (!isset($resp['in_reply_to']) || $resp['in_reply_to'] !== ($req['message_id'] ?? null)) {
        $errs[] = "Correlation error: response 'in_reply_to' ('" . ($resp['in_reply_to'] ?? 'MISSING') . "') does not match request 'message_id' ('" . ($req['message_id'] ?? 'MISSING') . "')";
    }

    if (!isset($resp['run_id']) || $resp['run_id'] !== ($req['run_id'] ?? null)) {
        $errs[] = "Correlation error: response 'run_id' ('" . ($resp['run_id'] ?? 'MISSING') . "') does not match request 'run_id' ('" . ($req['run_id'] ?? 'MISSING') . "')";
    }

    if (!isset($resp['unit_id']) || $resp['unit_id'] !== ($req['unit_id'] ?? null)) {
        $errs[] = "Correlation error: response 'unit_id' ('" . ($resp['unit_id'] ?? 'MISSING') . "') does not match request 'unit_id' ('" . ($req['unit_id'] ?? 'MISSING') . "')";
    }

    // Matrix check
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
        $errs[] = "Matrix error: unknown request message_type '$msgType'";
    } elseif (!in_array($decision, $matrix[$msgType], true)) {
        $errs[] = "Matrix error: decision '$decision' is illegal for message_type '$msgType' (allowed: " . implode(', ', $matrix[$msgType]) . ")";
    }

    return $errs;
}

// -----------------------------------------------------------------------------
// 4. Extract and Validate Examples from EXAMPLES.md
// -----------------------------------------------------------------------------
echo "[2/4] Validating Positive Example Fixtures (EXAMPLES.md)...\n";

if (!file_exists($examplesFile)) {
    echo "  [FAIL] Missing EXAMPLES.md\n";
    exit(1);
}

$examplesContent = file_get_contents($examplesFile);
preg_match_all('/```json\s*(\{[\s\S]*?\})\s*```/', $examplesContent, $matches);

$extractedJsonBlocks = $matches[1] ?? [];
echo "  Found " . count($extractedJsonBlocks) . " JSON blocks in EXAMPLES.md.\n";

// Map known fixtures by message_id
$requests = [];
$responses = [];
$malformedFixtures = [];

foreach ($extractedJsonBlocks as $block) {
    $decoded = json_decode($block, true);
    if (!$decoded || !isset($decoded['message_id'])) {
        continue;
    }

    if (strpos($decoded['message_id'], '99E') !== false) {
        $malformedFixtures[$decoded['message_id']] = $decoded;
    } elseif (isset($decoded['sender']) && $decoded['sender'] === 'orchestrator') {
        $requests[$decoded['message_id']] = $decoded;
    } elseif (isset($decoded['sender']) && $decoded['sender'] === 'architect') {
        $responses[$decoded['message_id']] = $decoded;
    }
}

// Test Positive Requests
foreach ($requests as $id => $req) {
    $schemaErrors = validateAgainstSchema($req, $reqSchema);
    if (empty($schemaErrors)) {
        echo "  [PASS] Request $id conforms to REQUEST_SCHEMA.json\n";
        $passes++;
    } else {
        echo "  [FAIL] Request $id failed schema validation:\n";
        foreach ($schemaErrors as $e) echo "         - $e\n";
        $errors[] = "Request $id schema validation";
    }
}

// Test Positive Responses
foreach ($responses as $id => $resp) {
    $schemaErrors = validateAgainstSchema($resp, $respSchema);
    if (empty($schemaErrors)) {
        echo "  [PASS] Response $id conforms to RESPONSE_SCHEMA.json\n";
        $passes++;
    } else {
        echo "  [FAIL] Response $id failed schema validation:\n";
        foreach ($schemaErrors as $e) echo "         - $e\n";
        $errors[] = "Response $id schema validation";
    }
}

// Verify Example E Malformed Fixture Fails Closed
foreach ($malformedFixtures as $id => $malformed) {
    $schemaErrors = validateAgainstSchema($malformed, $respSchema);
    if (!empty($schemaErrors)) {
        echo "  [PASS] Example E Malformed Fixture $id demonstrably FAILED CLOSED against RESPONSE_SCHEMA.json (" . count($schemaErrors) . " schema violations caught)\n";
        $passes++;
    } else {
        echo "  [FAIL] Example E Malformed Fixture $id unexpectedly passed schema validation!\n";
        $errors[] = "Malformed fixture $id should fail schema validation";
    }
}

// Test Correlation for paired examples (A, B, C, D, F)
$pairs = [
    'Example A'            => ['msg_req_20260921_01A', 'msg_resp_20260921_01A'],
    'Example B'            => ['msg_req_20260921_02B', 'msg_resp_20260921_02B'],
    'Example C'            => ['msg_req_20260921_03C', 'msg_resp_20260921_03C'],
    'Example D'            => ['msg_req_20260921_04D', 'msg_resp_20260921_04D'],
    'Example F (Dispatch)' => ['msg_req_20260921_05F', 'msg_resp_20260921_05F'],
    'Example F (Report)'   => ['msg_req_20260921_06F', 'msg_resp_20260921_06F'],
];

echo "\n[3/4] Validating Correlation & Decision Matrix on Positive Pairs...\n";
foreach ($pairs as $label => [$reqId, $respId]) {
    if (!isset($requests[$reqId]) || !isset($responses[$respId])) {
        echo "  [FAIL] $label: missing request ($reqId) or response ($respId)\n";
        $errors[] = "$label missing fixture";
        continue;
    }

    $corrErrors = validateCorrelation($requests[$reqId], $responses[$respId]);
    if (empty($corrErrors)) {
        echo "  [PASS] $label ($reqId <-> $respId): Correlation & Matrix Valid\n";
        $passes++;
    } else {
        echo "  [FAIL] $label ($reqId <-> $respId) correlation failed:\n";
        foreach ($corrErrors as $e) echo "         - $e\n";
        $errors[] = "$label correlation";
    }
}

// -----------------------------------------------------------------------------
// 5. Negative Test Suite (Fail-Closed Verification)
// -----------------------------------------------------------------------------
echo "\n[4/4] Executing Negative Test Suite (Enforcing FAIL-CLOSED Semantics)...\n";

$baseReq = $requests['msg_req_20260921_01A'];
$baseResp = $responses['msg_resp_20260921_01A'];

$negativeTests = [
    'N1: Malformed / Syntax Error JSON' => function () {
        $corruptJson = "{ protocol_version: 1.0, unquoted_key }";
        $decoded = json_decode($corruptJson, true);
        return [
            'expected_fail' => true,
            'passed' => ($decoded === null && json_last_error() !== JSON_ERROR_NONE),
            'detail' => 'json_decode returned syntax error: ' . json_last_error_msg()
        ];
    },

    'N2: Missing Required Envelope Field (message_id)' => function () use ($baseReq, $reqSchema) {
        $mutated = $baseReq;
        unset($mutated['message_id']);
        $errs = validateAgainstSchema($mutated, $reqSchema);
        return [
            'expected_fail' => true,
            'passed' => in_array('message_id is required', $errs, true),
            'detail' => implode('; ', $errs)
        ];
    },

    'N3: Invalid Decision Enum ("PROCEED")' => function () use ($baseResp, $respSchema) {
        $mutated = $baseResp;
        $mutated['decision'] = 'PROCEED';
        $errs = validateAgainstSchema($mutated, $respSchema);
        return [
            'expected_fail' => true,
            'passed' => !empty($errs) && strpos(implode(' ', $errs), 'not in enum') !== false,
            'detail' => implode('; ', $errs)
        ];
    },

    'N4: Correlation Mismatch (in_reply_to != request.message_id)' => function () use ($baseReq, $baseResp) {
        $mutated = $baseResp;
        $mutated['in_reply_to'] = 'msg_req_DIFFERENT_999';
        $errs = validateCorrelation($baseReq, $mutated);
        return [
            'expected_fail' => true,
            'passed' => !empty($errs) && strpos($errs[0], 'Correlation error') !== false,
            'detail' => implode('; ', $errs)
        ];
    },

    'N5: Cross-Unit State Pollution (unit_id mismatch)' => function () use ($baseReq, $baseResp) {
        $mutated = $baseResp;
        $mutated['unit_id'] = 'M9.9';
        $errs = validateCorrelation($baseReq, $mutated);
        return [
            'expected_fail' => true,
            'passed' => !empty($errs) && strpos(implode(' ', $errs), "does not match request 'unit_id'") !== false,
            'detail' => implode('; ', $errs)
        ];
    },

    'N6: Cross-Run Pollution (run_id mismatch)' => function () use ($baseReq, $baseResp) {
        $mutated = $baseResp;
        $mutated['run_id'] = 'run_OTHER_RUN_02';
        $errs = validateCorrelation($baseReq, $mutated);
        return [
            'expected_fail' => true,
            'passed' => !empty($errs) && strpos(implode(' ', $errs), "does not match request 'run_id'") !== false,
            'detail' => implode('; ', $errs)
        ];
    },

    'N7: Stage-Decision Incompatibility (VERIFICATION_APPROVED for ARCHITECTURE_REVIEW)' => function () use ($baseReq, $baseResp) {
        $mutated = $baseResp;
        $mutated['decision'] = 'VERIFICATION_APPROVED';
        $errs = validateCorrelation($baseReq, $mutated);
        return [
            'expected_fail' => true,
            'passed' => !empty($errs) && strpos(implode(' ', $errs), 'Matrix error') !== false,
            'detail' => implode('; ', $errs)
        ];
    },

    'N8: Missing state_transition in Payload' => function () use ($baseResp, $respSchema) {
        $mutated = $baseResp;
        unset($mutated['payload']['state_transition']);
        $errs = validateAgainstSchema($mutated, $respSchema);
        return [
            'expected_fail' => true,
            'passed' => in_array('payload.state_transition is required', $errs, true),
            'detail' => implode('; ', $errs)
        ];
    },

    'N9: Additional Unauthorized Field (additionalProperties: false)' => function () use ($baseResp, $respSchema) {
        $mutated = $baseResp;
        $mutated['hacked_bypass'] = true;
        $errs = validateAgainstSchema($mutated, $respSchema);
        return [
            'expected_fail' => true,
            'passed' => in_array('hacked_bypass is an unauthorized property (additionalProperties: false)', $errs, true),
            'detail' => implode('; ', $errs)
        ];
    }
];

foreach ($negativeTests as $name => $testFn) {
    $res = $testFn();
    if ($res['passed']) {
        echo "  [PASS] $name -> Demonstrably FAILED CLOSED ({$res['detail']})\n";
        $passes++;
    } else {
        echo "  [FAIL] $name -> Did NOT fail closed as expected!\n";
        $errors[] = "Negative test $name";
    }
}

// -----------------------------------------------------------------------------
// Summary
// -----------------------------------------------------------------------------
echo "\n===============================================================\n";
echo " Protocol Validation Summary\n";
echo "===============================================================\n";
echo "Total Checks Passed: $passes\n";
echo "Total Errors:        " . count($errors) . "\n";

if (empty($errors)) {
    echo "\nPROTOCOL VALIDATION: PASS\n";
    echo "Fail-closed safety invariants are strictly enforced.\n";
    exit(0);
} else {
    echo "\nPROTOCOL VALIDATION: FAIL\n";
    foreach ($errors as $e) echo " - $e\n";
    exit(1);
}
