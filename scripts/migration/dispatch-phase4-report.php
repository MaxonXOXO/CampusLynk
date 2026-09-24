<?php

/**
 * CampusLynk Migration Control Plane — Dispatch Phase 4 Task Report to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_02';

$phase4ReportContext = <<<'CONTEXT'
# TASK REPORT: M10.1-PHASE4 — ATTAINMENT ENGINE, SBTE BULK IMPORT & CARMIE AI COMPLETE

## 1. EXECUTIVE SUMMARY
Phase 4 of Wave 6 (Backwards Parity Reconciliation) has been successfully executed in strict conformance with the Architect's directive. All three major components—HOD Program Attainment, SBTE Teams Attendance PDF Import, and Carmie AI Query Model & Gemini integration—have been restored, reconciled, and verified.

## 2. RECONCILED ARTIFACTS & IMPLEMENTATION
1. **HOD Program Attainment Engine**:
   - `resources/views/hod/program_attainment_dashboard.blade.php`: Forwarder to modern `<x-layouts.dashboard-layout>` view (`program_attainment.index`) with 5 dedicated tabs (Direct Course Matrix 80%, Indirect Surveys 20%, PO1–11, PSO1–3, Summary & Action Plan).
   - `resources/views/hod/program_attainment_print.blade.php`: Forwarder to `<x-layouts.report-layout>` view (`program_attainment.print`) for NBA Criterion 3 statutory compliance report.
   - Verified `ProgramAttainmentController` config saving, gap analysis, and OBE attainment computations.

2. **SBTE Bulk Import & Teams PDF Parser**:
   - `app/Services/parse_sbte_subject_log.py`: Restored Teams attendance PDF parser using `pypdf` with multiline session extraction and period reconciliation.
   - `resources/views/partials/sbte_bulk_import_modal.blade.php`: Restored legacy forwarder to modernized `partials.sbte_subject_log_import_modal` with dark glassmorphism and PDF/raw text dual mode.
   - Verified `SbteSubjectLogImportController` and import services.

3. **Carmie AI Knowledge Model & Gemini Flash Integration**:
   - `database/data/carmie_query_model.json`: Synced question model / query memory log for continuous learning.
   - `app/Http/Controllers/CarmieAssistantController.php`: Restored `queryGemini()` integration with Google Gemini Flash, ground-truth system prompt for Revision 2021 & Revision 2026 syllabus isolation, and automatic query logging via `CarmiePlaybookService::logUserQuestion()`.

## 3. VERIFICATION
- PHP Syntax / Linting: `php -l` passed with zero errors across all modified files.
- Dedicated Feature Tests: `CarmieAssistantTest`, `SbteSubjectLogImportTest`, and `ProgramAttainmentControllerTest` passed with 16/16 tests, 117 assertions.
- Full Regression Suite: Complete regression suite passed (`171 tests, 992 assertions, 0 failures, 12.6s, 100% pass`).

## 4. HANDOFF STATE FOR M10.1-PHASE5 (FINAL CLOSEOUT)
Phase 4 is complete. Phase 5 is the final phase:
- Complete final route audit & harmonization in `routes/web.php`.
- Full pre-merge regression verification.
- Final migration wave completion report and closeout.

Please evaluate this Task Report, issue the approval for Phase 4, and dispatch the TASK_DISPATCH for Phase 5 (Final Closeout).
CONTEXT;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M10.1',
    'IMPLEMENTED',
    [
        'unit_title' => 'Wave 6: Backwards Migration Parity & Reconciliation (M10.1)',
        'objective' => 'Submit Phase 4 Attainment Engine, SBTE Bulk Import & Carmie AI report and request Phase 5 Final Closeout Dispatch.',
        'explicit_request' => 'Phase 4 Attainment Engine, SBTE Import & Carmie AI is complete with all tests passing (171 tests, 992 assertions). Please evaluate and issue TASK_DISPATCH for Phase 5 (Route Harmonization & Final Closeout).',
        'context' => [
            'dependencies' => ['M10.1-PHASE1', 'M10.1-PHASE2', 'M10.1-PHASE3'],
            'allowed_files' => [
                'resources/views/hod/program_attainment_dashboard.blade.php',
                'resources/views/hod/program_attainment_print.blade.php',
                'resources/views/partials/sbte_bulk_import_modal.blade.php',
                'app/Services/parse_sbte_subject_log.py',
                'database/data/carmie_query_model.json',
                'app/Http/Controllers/CarmieAssistantController.php'
            ],
            'task_report' => [
                'task_id' => 'M10.1-PHASE4',
                'unit_id' => 'M10.1',
                'status' => 'COMPLETED',
                'summary' => 'HOD Program Attainment forwarders created; SBTE PDF parser and modal forwarder restored; carmie_query_model.json synced; queryGemini added to CarmieAssistantController; 171 tests passing with 992 assertions.',
                'changes' => [
                    'resources/views/hod/program_attainment_dashboard.blade.php',
                    'resources/views/hod/program_attainment_print.blade.php',
                    'resources/views/partials/sbte_bulk_import_modal.blade.php',
                    'app/Services/parse_sbte_subject_log.py',
                    'database/data/carmie_query_model.json',
                    'app/Http/Controllers/CarmieAssistantController.php'
                ],
                'tests' => [
                    'suite' => 'PHPUnit',
                    'passed' => 171,
                    'assertions' => 992,
                    'failures' => 0
                ],
                'issues' => [],
                'git' => [
                    'branch' => 'migration-alpha',
                    'head' => '8ae50886'
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching Phase 4 Task Report to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 60, false, $phase4ReportContext);

echo "\n--- Bridge Response Result ---\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if (!empty($result['success'])) {
    echo "\n[SUCCESS] Received Phase 5 directive from ChatGPT!\n";
    exit(0);
} else {
    echo "\n[FAIL] Bridge request failed.\n";
    exit(1);
}
