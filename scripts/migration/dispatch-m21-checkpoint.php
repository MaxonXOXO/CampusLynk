<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.1-CHECKPOINT-001 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$detailedReport = <<<REPORT
# M2.1-CHECKPOINT-001 VERIFICATION & CHECKPOINT REPORT

## 1. Unit Overview
- Task ID: M2.1-CHECKPOINT-001
- Unit ID: M2.1
- Feature ID: r21_major_project
- Title: R21 Major Project Backend Controller and Evaluation Endpoints
- Status: VERIFIED (Slice 1 & Slice 2 both implementation-approved by ChatGPT)
- Objective: Finalize and verify M2.1 checkpoint evidence before Git commit and persistent state transition.

## 2. Complete M2.1 Artifact Inventory (Whitelisted Files Only)
1. carmel-linx-laravel/database/migrations/2026_09_12_000001_create_r21_major_project_tables.php (Slice 1)
2. carmel-linx-laravel/app/Models/R21MajorProjectCourseFile.php (Slice 1)
3. carmel-linx-laravel/app/Models/R21MajorProjectEvaluation.php (Slice 1)
4. carmel-linx-laravel/tests/Unit/R21MajorProjectModelsTest.php (Slice 1)
5. carmel-linx-laravel/app/Services/AttainmentService.php (Slice 2)
6. carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php (Slice 2)
7. carmel-linx-laravel/routes/web.php (Slice 2)
8. carmel-linx-laravel/tests/Feature/R21MajorProjectControllerTest.php (Slice 2)

## 3. Automated Verification Evidence
- PHP Syntax Checks (0 errors across all 8 files):
  - php -l database/migrations/2026_09_12_000001_create_r21_major_project_tables.php -> PASS
  - php -l app/Models/R21MajorProjectCourseFile.php -> PASS
  - php -l app/Models/R21MajorProjectEvaluation.php -> PASS
  - php -l app/Services/AttainmentService.php -> PASS
  - php -l app/Http/Controllers/R21VirtualClassroomMajorProjectController.php -> PASS
  - php -l routes/web.php -> PASS
  - php -l tests/Unit/R21MajorProjectModelsTest.php -> PASS
  - php -l tests/Feature/R21MajorProjectControllerTest.php -> PASS
- Artisan Route List:
  - `php artisan route:list --path=r21/classroom/project` -> 11 routes mapped to R21VirtualClassroomMajorProjectController
- Focused Test Suites:
  - `php artisan test --filter=R21MajorProjectModelsTest` -> PASS (5 tests, 61 assertions)
  - `php artisan test --filter=R21MajorProjectControllerTest` -> PASS (11 tests, 85 assertions)
- Full Project Test Suite:
  - `php artisan test` -> PASS (24 tests, 165 assertions, 0 failures, 0 regressions)
  - M1.1 AuditLogTest (6 tests, 17 assertions) continues to pass 100%.

## 4. Safety & Scope Invariants
- Whitelist Compliance: Exactly the 8 approved files exist for M2.1.
- Untouched Critical Files: `app/Models/User.php` is completely untouched.
- Control Plane Files: `STATE.json` and `FEATURE_MATRIX.md` will only be updated following your explicit checkpoint authorization.
- Git Status: Working directory is clean of unapproved changes.
- Destructive Operations: Zero DB wipes, fresh migrations, drops, or truncates.

## 5. Design System & Anti-Duplication Compliance
- Backend implementation is modular, clean, and view-agnostic.
- Ready for M2.2 (Workspace UI) using Master Shell `<x-layouts.workspace-layout>` and global `<x-ui.*>` components.
- Ready for M2.3 (Print Report) using Master Shell `<x-layouts.report-layout>`.
REPORT;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.1',
    'VERIFIED',
    [
        'unit_title' => 'R21 Major Project Backend Controller and Evaluation Endpoints (M2.1-CHECKPOINT-001)',
        'objective' => 'Finalize and verify M2.1 checkpoint evidence before Git commit and persistent state transition.',
        'explicit_request' => "Review the checkpoint TASK_REPORT for task 'M2.1-CHECKPOINT-001'. Confirm whether M2.1 is approved for git commit and state transition in your RESPONSE_SCHEMA JSON block. NOTE: RESPONSE_SCHEMA.json enforces additionalProperties: false on payload. Every migration decision must consider existing CampusLynk global components (<x-ui.*>) and Master Shell layouts (<x-layouts.*>) to prevent duplication. If approving the checkpoint commit and state transition, return decision: 'CHECKPOINT_APPROVED' (or 'PLAN_APPROVED') with state_transition authorized.",
        'context' => [
            'dependencies' => ['M1.1'],
            'allowed_files' => [
                'carmel-linx-laravel/database/migrations/2026_09_12_000001_create_r21_major_project_tables.php',
                'carmel-linx-laravel/app/Models/R21MajorProjectCourseFile.php',
                'carmel-linx-laravel/app/Models/R21MajorProjectEvaluation.php',
                'carmel-linx-laravel/tests/Unit/R21MajorProjectModelsTest.php',
                'carmel-linx-laravel/app/Services/AttainmentService.php',
                'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php',
                'carmel-linx-laravel/routes/web.php',
                'carmel-linx-laravel/tests/Feature/R21MajorProjectControllerTest.php'
            ],
            'task_report' => [
                'task_id' => 'M2.1-CHECKPOINT-001',
                'unit_id' => 'M2.1',
                'status' => 'COMPLETED',
                'summary' => 'M2.1 checkpoint verified. 8 whitelisted artifacts created/modified across Slice 1 and Slice 2. All 16 unit and feature tests pass (146 assertions). Full application test suite passes (24 tests, 165 assertions). Zero regressions. User.php untouched. Ready for checkpoint commit.',
                'changes' => [
                    'carmel-linx-laravel/database/migrations/2026_09_12_000001_create_r21_major_project_tables.php',
                    'carmel-linx-laravel/app/Models/R21MajorProjectCourseFile.php',
                    'carmel-linx-laravel/app/Models/R21MajorProjectEvaluation.php',
                    'carmel-linx-laravel/tests/Unit/R21MajorProjectModelsTest.php',
                    'carmel-linx-laravel/app/Services/AttainmentService.php',
                    'carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php',
                    'carmel-linx-laravel/routes/web.php',
                    'carmel-linx-laravel/tests/Feature/R21MajorProjectControllerTest.php'
                ],
                'tests' => [
                    'focused_unit' => 'php artisan test --filter=R21MajorProjectModelsTest -> PASS (5 tests, 61 assertions)',
                    'focused_feature' => 'php artisan test --filter=R21MajorProjectControllerTest -> PASS (11 tests, 85 assertions)',
                    'full_suite' => 'php artisan test -> PASS (24 tests, 165 assertions in 2.17s)',
                    'syntax' => 'php -l on all 8 files -> PASS (0 errors)',
                    'routes' => 'php artisan route:list --path=r21/classroom/project -> 11 routes verified'
                ],
                'issues' => [],
                'git' => [
                    'branch' => 'migration-alpha',
                    'whitelisted_files' => 8,
                    'unapproved_changes' => 0,
                    'user_model_untouched' => true
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.1-CHECKPOINT-001 TASK_REPORT to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 90, false, $detailedReport);

echo "\n--- Bridge Response Result ---\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if (!empty($result['success'])) {
    echo "\n[SUCCESS] Received validated supervisor response from ChatGPT!\n";
    echo "Decision: " . ($result['decision'] ?? 'UNKNOWN') . "\n";
    exit(0);
} else {
    echo "\n[FAIL] Bridge request failed or rejected by fail-closed guardrails.\n";
    exit(1);
}
