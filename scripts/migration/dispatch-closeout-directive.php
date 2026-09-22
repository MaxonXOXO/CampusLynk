<?php

/**
 * CampusLynk Migration Control Plane — Dispatch Final Closeout Directive Request to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$closeoutContext = <<<'CONTEXT'
# AUTHORITATIVE MIGRATION CONTROL-PLANE: PROJECT CLOSEOUT REQUEST

## 1. POST-AUDIT VERIFICATION ACCEPTANCE
The Architect / Supervisor has formally accepted Phase 1 and Phase 2 post-audit verification (Decision: PLAN_APPROVED, Target State: READY):
- 28 defined units with 27 active completed and M8.3 intentionally deferred
- 162 legacy views accounted for (138 transformed/modernized, 23 retained compatibility, 1 deferred)
- 45/46 controllers accounted for with sole omission matching deferred M8.3
- 89/88 models with StaffBirthdayWish as the documented addition; User.php invariant confirmed (no User model introduced or modified)
- 8 target services versus 5 legacy services (+NavigationService, +SbteSubjectLogImportService, +StaffBirthdayService)
- 112 target migrations versus 116 legacy migrations with clean consolidation
- 163 passing tests with 960 assertions and zero failures (9.57s)
- Architectural transformation substantiated across dashboards, virtual classrooms, statutory reports, global UI components, and domain services.

## 2. CLOSEOUT ITEMS FOR ARCHITECT DIRECTIVE
The Architect noted two closeout concerns:
1. **Uncommitted Wave 4/5 Working-Tree Files:**
   - 8 test files (`CentralizedPrintReportsTest`, `LabBatchSplitTest`, `ProgramAttainmentControllerTest`, `PushNotificationTest`, `SbteSubjectLogImportTest`, `StaffBirthdayTest`, `TutorProgressReportsTest`, etc.)
   - 4 controllers (`ProgramAttainmentController`, `PushNotificationController`, `SbteSubjectLogImportController`, `StaffBirthdayController`)
   - 3 models (`ProgramAttainment`, `PushSubscription`, `StaffBirthdayWish`)
   - 3 domain services (`PushNotificationService`, `SbteSubjectLogImportService`, `StaffBirthdayService`)
   - 4 database migrations (`2026_09_10_232359`, `2026_09_22_110000`, `2026_09_22_120000`, `2026_09_22_120001`)
   - Decomposed view partials in `resources/views/hod/partials/`, `reports/`, `program_attainment/`, `r26_practicum/partials/`, `tutor/reports/`
   - *Request:* Authorize staging and committing these verified, passing artifacts to `migration-alpha`.

2. **Archival Course File CDN References:**
   - 9 static preview templates in `resources/views/course_files/preview_doc_*` reference `cdn.tailwindcss.com`.
   - *Request:* Confirm these remain acceptable as static archival compatibility templates or specify if a cleanup pass is required before merge.

3. **Final Project Closeout & Merge Readiness:**
   - Authorize final checkpoint commit and transition of `STATE.json` to `PROJECT_COMPLETED` / `MERGE_READY`.

Please provide your formal architectural directive and decision (TASK_DISPATCH or PLAN_APPROVED) to finalize the migration closeout.
CONTEXT;

$envelope = $bridge->createRequestEnvelope(
    'FINAL_UNIT_REVIEW',
    'M9.4',
    'VERIFIED',
    [
        'unit_title' => 'CampusLynk Migration Final Project Closeout & Merge Readiness (M9.4)',
        'objective' => 'Request Architect directive for final project closeout: committing verified Wave 4/5 working-tree artifacts on migration-alpha, addressing archival CDN dependencies, and authorizing merge readiness to main.',
        'explicit_request' => "Phase 1 & 2 post-audit verification is accepted (PLAN_APPROVED). All 27 active units are verified, 163 tests passing with 960 assertions and 0 failures. Requesting Architect directive on: 1) Authorization to commit verified Wave 4/5 working-tree files on migration-alpha; 2) Confirmation that archival CDN references in course_files/preview_doc_* are accepted as static legacy artifacts; 3) Final closeout checkpoint commit and merge readiness to main. Please issue the formal closeout directive.",
        'context' => [
            'dependencies' => ['M9.3'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ]
        ]
    ],
    $runId
);

echo "Dispatching Final Closeout Directive Request to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 90, false, $closeoutContext);

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
