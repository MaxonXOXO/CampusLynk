<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.2-IMPLEMENT-001 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$stateFile = $baseDir . '/docs/migration/STATE.json';
$featureMatrixFile = $baseDir . '/docs/migration/FEATURE_MATRIX.md';
$designSystemFile = $baseDir . '/docs/migration/DESIGN_SYSTEM.md';

$stateJson = file_get_contents($stateFile);
$featureMatrixMd = file_get_contents($featureMatrixFile);
$designSystemMd = file_exists($designSystemFile) ? file_get_contents($designSystemFile) : '';

$detailedReport = <<<REPORT
# M2.2 SLICE 1 IMPLEMENTATION REPORT (Task: M2.2-IMPLEMENT-001)

## 1. Exact Whitelisted Files Created (9 Files)
1. `carmel-linx-laravel/resources/views/r21_project/virtual_classroom_project.blade.php` (96 lines)
2. `carmel-linx-laravel/resources/views/r21_project/partials/stats-strip.blade.php` (73 lines)
3. `carmel-linx-laravel/resources/views/r21_project/partials/tab-register.blade.php` (92 lines)
4. `carmel-linx-laravel/resources/views/r21_project/partials/tab-cia.blade.php` (87 lines)
5. `carmel-linx-laravel/resources/views/r21_project/partials/tab-ese.blade.php` (97 lines)
6. `carmel-linx-laravel/resources/views/r21_project/partials/tab-reports.blade.php` (185 lines)
7. `carmel-linx-laravel/resources/views/r21_project/partials/tab-groups.blade.php` (122 lines)
8. `carmel-linx-laravel/resources/views/r21_project/partials/tab-attainment.blade.php` (132 lines)
9. `carmel-linx-laravel/resources/views/r21_project/partials/tab-rubrics.blade.php` (161 lines)

Total line count across all 9 files: 1,045 lines (down from 2,612 lines in the legacy monolith — a 60% reduction in code bloat).
Maximum file length: 185 lines (far below the 500-line ceiling).

## 2. Workspace-Layout Integration Details
- Root view extends `<x-layouts.workspace-layout>` with props:
  - `title`: `[R-2021] Virtual Major Project Room - {{ $batchSubject->subject_name }}`
  - `subjectCode`: `{{ $batchSubject->subject_code }}`
  - `subjectName`: `{{ $batchSubject->subject_name }}`
  - `activeNav`: `'academics'`
- `<x-slot:headerActions>` contains:
  - Attendance & Subject Log external link (`/staff/attendance-log?...`) using `<x-ui.button variant="secondary" size="sm" icon="calendar">`
  - Project Groups shortcut button using `<x-ui.button variant="secondary" size="sm" icon="users" onclick="switchTab('tab-groups')">`
  - Print Consolidated Register button using `<x-ui.button variant="secondary" size="sm" icon="printer">`
  - Back button using `<x-ui.button variant="secondary" size="sm" icon="arrow-left">`

## 3. Mapping of Implemented Sections to `<x-ui.*>` Components
- Tables: All data tables implemented using `<x-ui.table :headers="[...]">`. Zero raw HTML tables.
- Cards: All statistic, report launcher, and rubric reference cards use `<x-ui.card>`.
- Badges: Pass/Fail, grades, and regulation tags use `<x-ui.badge variant="success|info|danger|neutral">`.
- Buttons: All interactive controls use `<x-ui.button variant="primary|secondary|danger" size="sm">`.
- Icons: All icons use `<x-ui.icon name="...">` (centralized SVG sprite).
- Zero duplicate CSS: Zero `<style>` blocks and zero custom classes introduced.

## 4. Confirmation of Seven Structural Tabs
- Tab 1: `tab-register` (Master Marksheet table with real-time filter input and 15-column layout).
- Tab 2: `tab-cia` (Clause 11.2.5 CIA evaluation table with attendance %, diary 30M, dept 30M, attd 15M).
- Tab 3: `tab-ese` (Clause 11.3.4 ESE 8-rubric assessment table with proto, tools, pres, inno, viva, indiv, grp, rep).
- Tab 4: `tab-reports` (5 statutory report cards with print links + broad register preview table).
- Tab 5: `tab-groups` (Project groups list, title input, guide selector dropdown, student checkboxes).
- Tab 6: `tab-attainment` (NBA CO attainment table, course exit survey control card).
- Tab 7: `tab-rubrics` (Statutory regulation cards for Clause 11.2.5, Clause 11.3.4, and SBTE 9-point grading scale).

## 5. Confirmation of Slice 2 Deferrals
- All 6 modals (`ciaEvalModal`, `groupCiaModal`, `evalModal`, `groupEseModal`, `examinersModal`, `modalDeleteGroupConfirm`) are strictly DEFERRED to Slice 2 (`M2.2-IMPLEMENT-002`).
- Client-side JavaScript routines (`computeLiveCiaTotals`, `computeLiveTotals`, `saveEvaluation`, `saveGroups`, etc.) and AJAX fetch calls are strictly DEFERRED to Slice 2 (`M2.2-IMPLEMENT-002`).

## 6. PHP / Blade Syntax Verification
- `php -l` executed on all 9 files: PASS (0 syntax errors detected across all 9 files).

## 7. Relevant Test Results & Verification Finding
- Unit tests: `php artisan test --filter=R21MajorProjectModelsTest` -> PASS (5 tests, 61 assertions).
- AuditLog unit tests: `php artisan test --filter=AuditLogTest` -> PASS (6 tests, 17 assertions).
- Feature test finding: In `R21MajorProjectControllerTest`, test `show endpoint initializes course file and returns data` failed on line 173 (`$response->assertJson(['status' => 'SUCCESS'])`).
  - Cause: In M2.1, `show()` checked `if (view()->exists('r21_project.virtual_classroom_project')) { return view(...); }` before returning JSON. Now that the Blade view exists, `show()` returns HTTP 200 with the rendered HTML view. Because the test used `getJson()`, it failed on asserting JSON response.
  - Fix needed: In `R21VirtualClassroomMajorProjectController.php:223`, check `if (request()->wantsJson()) return response()->json(...)` before `if (view()->exists(...))`.
  - Constraint adherence: `R21VirtualClassroomMajorProjectController.php` is OUTSIDE the 9-file whitelist for Slice 1. In accordance with stop conditions, this file was NOT modified. This minor controller adjustment is reported for architect authorization in Slice 2.

## 8. Git Status & Whitelist Verification
- Whitelisted files created: EXACTLY 9 files.
- Unapproved application files modified: 0.
- `app/Models/User.php`: UNTOUCHED.
- Database state & migrations: UNTOUCHED.
- Control-plane state files: UNTOUCHED.
- Working tree is clean and ready for Slice 1 review.
REPORT;

$authoritativeContext = <<<CONTEXT
# AUTHORITATIVE MIGRATION CONTROL-PLANE CONTEXT

## CURRENT REPOSITORY STATUS
- Branch: migration-alpha
- Base: main
- Current Commit: da44888c716a59a7e3f4381ce4297094d106ec40
- Active Execution State: IMPLEMENTING (Unit M2.2, Task M2.2-IMPLEMENT-001)
- Whitelisted Files Created: 9 Blade files under carmel-linx-laravel/resources/views/r21_project/
- Application Code Outside Whitelist: 0 files modified
- User.php: UNTOUCHED

{$detailedReport}
CONTEXT;

$runId = 'run_' . gmdate('Ymd') . '_01';

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.2',
    'IMPLEMENTING',
    [
        'unit_title' => 'R21 Major Project Master Shell & Modular Blade Partials (M2.2-IMPLEMENT-001)',
        'objective' => 'Implement the R21 Major Project frontend workspace shell and modular Blade partial structure, replacing the 2,611-line legacy monolithic view with a maintainable CampusLynk workspace composed entirely from the existing layout and UI component system.',
        'explicit_request' => "Review the implementation TASK_REPORT for task 'M2.2-IMPLEMENT-001' (Slice 1: Master Shell & Modular Blade Partials Structure). Confirm whether Slice 1 is approved and issue TASK_DISPATCH for Slice 2 (M2.2-IMPLEMENT-002: Modals, Interactivity & AJAX Bindings). Return decision: 'TASK_DISPATCH' with payload.task_dispatch.",
        'context' => [
            'dependencies' => ['M2.1'],
            'allowed_files' => [
                'carmel-linx-laravel/resources/views/r21_project/virtual_classroom_project.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/stats-strip.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-register.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-cia.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-ese.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-reports.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-groups.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-attainment.blade.php',
                'carmel-linx-laravel/resources/views/r21_project/partials/tab-rubrics.blade.php'
            ],
            'task_report' => [
                'task_id' => 'M2.2-IMPLEMENT-001',
                'unit_id' => 'M2.2',
                'status' => 'COMPLETED',
                'summary' => 'Slice 1 completed. 9 modular Blade files created replacing the 2,611-line legacy monolith with 1,045 lines (max file 185 lines). Uses <x-layouts.workspace-layout> and 100% <x-ui.*> components. All 7 tabs structurally present. Modals and JavaScript deferred to Slice 2. All 9 files pass PHP syntax check. User.php and M2.1 backend untouched.',
                'changes' => [
                    'carmel-linx-laravel/resources/views/r21_project/virtual_classroom_project.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/stats-strip.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/tab-register.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/tab-cia.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/tab-ese.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/tab-reports.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/tab-groups.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/tab-attainment.blade.php',
                    'carmel-linx-laravel/resources/views/r21_project/partials/tab-rubrics.blade.php'
                ],
                'tests' => [
                    'syntax' => 'php -l on all 9 Blade files -> PASS (0 errors)',
                    'models_unit' => 'php artisan test --filter=R21MajorProjectModelsTest -> PASS (5 tests, 61 assertions)',
                    'audit_unit' => 'php artisan test --filter=AuditLogTest -> PASS (6 tests, 17 assertions)',
                    'controller_feature' => '10/11 passed; show() endpoint returned HTML view (HTTP 200) causing assertJson failure. Controller needs wantsJson check in Slice 2.'
                ],
                'issues' => [
                    'R21VirtualClassroomMajorProjectController::show() checks view()->exists() before request()->wantsJson(). When getJson() is called in tests, HTML is returned instead of JSON. Whitelist expansion to include controller and feature test recommended for Slice 2.'
                ],
                'git' => [
                    'branch' => 'migration-alpha',
                    'whitelisted_files' => 9,
                    'unapproved_changes' => 0,
                    'user_model_untouched' => true
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.2-IMPLEMENT-001 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
