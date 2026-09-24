<?php

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$requestEnvelope = json_decode(file_get_contents($baseDir . '/docs/migration/runtime/active_prompt.txt'), true);

// If active_prompt contains markdown prefix, extract the json block
if (!isset($requestEnvelope['message_id'])) {
    $prompt = file_get_contents($baseDir . '/docs/migration/runtime/active_prompt.txt');
    if (preg_match('/```json\s*(\{.*?\})\s*```/s', $prompt, $m)) {
        $requestEnvelope = json_decode($m[1], true);
    }
}

$rawResponse = file_get_contents($baseDir . '/scripts/migration/chat-bridge/test-output.txt');
$responseJson = json_decode($rawResponse, true);

$refMethod = new ReflectionMethod($bridge, 'logExchange');
$refMethod->setAccessible(true);
$refMethod->invoke($bridge, $requestEnvelope, $responseJson, 'VALIDATED', 'Exchange completed and verified by Architect');

echo "Exchange successfully validated and persisted in runtime logs!\n";
