<?php

/**
 * CampusLynk Migration Control Plane — Dispatch M2.1-ANALYZE-001 TASK_REPORT to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_01';

$detailedReport = <<<REPORT
# M2.1 READ-ONLY ARCHITECTURAL & SCOPE ANALYSIS REPORT (Task: M2.1-ANALYZE-001)

## 1. M2.1 Scope Definition
- Feature: R21 Major Project (r21_major_project)
- Unit: M2.1 - R21 Major Project Backend Controller and Evaluation Endpoints
- Regulation: SBTE Kerala Regulation Clause 11.2.5 (CIA 75M) & 11.3.4 (ESE 50M) -> Total 125M (Ratio 3:2)

## 2. Legacy & Target Files Discovered
- Legacy Controller: app/Http/Controllers/R21VirtualClassroomMajorProjectController.php (1,436 lines)
- Legacy Models:
  - app/Models/R21MajorProjectCourseFile.php (38 lines)
  - app/Models/R21MajorProjectEvaluation.php (63 lines)
- Legacy Migration: database/migrations/2026_09_12_000001_create_r21_major_project_tables.php (88 lines)
- Legacy Supporting Service: app/Services/AttainmentService.php (378 lines)
- Legacy Views (M2.2 & M2.3):
  - resources/views/r21_project/virtual_classroom_project.blade.php (2,611 lines)
  - resources/views/r21_project/print_project_report.blade.php (1,025 lines)
- Target Status:
  - Models, Migration, Controller, and AttainmentService are currently MISSING in target.
  - Zero R21 Major Project routes currently registered in target routes/web.php.

## 3. Controller Methods & Route Endpoints Required (11 Endpoints)
1. GET  /r21/classroom/project/{subjectId} -> show()
2. POST /r21/classroom/project/{subjectId}/syllabus -> uploadSyllabus()
3. POST /r21/classroom/project/{subjectId}/save-groups -> saveGroups()
4. POST /r21/classroom/project/{subjectId}/save-evaluation -> saveEvaluation()
5. GET  /r21/classroom/project/{subjectId}/ese-marks -> getEseMarks()
6. POST /r21/classroom/project/{subjectId}/ese-marks/bulk-update -> bulkUpdateEseMarks()
7. GET  /r21/classroom/project/{subjectId}/attainment-summary -> getAttainmentSummary()
8. POST /r21/classroom/project/{subjectId}/examiners -> saveExaminers()
9. POST /r21/classroom/project/{subjectId}/group-ese -> saveGroupEse()
10. POST /r21/classroom/project/{subjectId}/group-cia -> saveGroupCia()
11. GET  /r21/classroom/project/{subjectId}/report/print -> printReport()

## 4. Coupling & Hidden Dependency Findings
- M4.1 AttendanceController Coupling: ZERO hard backend code coupling. The controller queries the student_attendance table directly (DB::table('student_attendance')->where('subject_code', ...)). The view only contains a standard navigation link (<a href="/staff/attendance-log?...">).
- AttainmentService Dependency: The controller calls AttainmentService for default ESE config, grade conversions (S/A/B/C/D/E/F), and CO-PO attainment calculations. AttainmentService is self-contained and must be introduced alongside or prior to the controller.
- User.php Coupling: ZERO modifications needed for User.php. Models link to Student (reg_no) and BatchSubject (id).

## 5. Architectural Modernization & Safety
- Authentication/Authorization: Replace legacy Session::get('userId') with auth()->user() and role middleware (HOD, Lecturer, Guide, Examiner).
- Request Validation: Replace raw request reads with explicit Laravel validation for mark bounds (Formative <= 30, Summative <= 30, Attendance <= 15; ESE components totaling <= 50).
- Error Handling: Use proper HTTP status codes (404, 422, 500) and JSON error envelopes for all AJAX endpoints.

## 6. Proposed Allowed Files Whitelist for M2.1
- carmel-linx-laravel/database/migrations/2026_09_12_000001_create_r21_major_project_tables.php
- carmel-linx-laravel/app/Models/R21MajorProjectCourseFile.php
- carmel-linx-laravel/app/Models/R21MajorProjectEvaluation.php
- carmel-linx-laravel/app/Services/AttainmentService.php
- carmel-linx-laravel/app/Http/Controllers/R21VirtualClassroomMajorProjectController.php
- carmel-linx-laravel/routes/web.php
- carmel-linx-laravel/tests/Unit/R21MajorProjectModelsTest.php
- carmel-linx-laravel/tests/Feature/R21MajorProjectControllerTest.php

## 7. Recommended Decomposition & Next Action
- Slice 1 (M2.1-IMPLEMENT-001): Schema & Eloquent Models Parity
  - Port migration 2026_09_12_000001 and models R21MajorProjectCourseFile and R21MajorProjectEvaluation.
  - Dedicated unit test: tests/Unit/R21MajorProjectModelsTest.php.
- Slice 2 (M2.1-IMPLEMENT-002): AttainmentService & Controller Endpoints
  - Port AttainmentService, R21VirtualClassroomMajorProjectController, and register routes in web.php.
  - Dedicated feature test: tests/Feature/R21MajorProjectControllerTest.php.
REPORT;

$envelope = $bridge->createRequestEnvelope(
    'TASK_REPORT',
    'M2.1',
    'ANALYZING',
    [
        'unit_title' => 'R21 Major Project Backend Controller and Evaluation Endpoints (M2.1)',
        'objective' => 'Perform a read-only architectural and migration-scope analysis of the R21 Major Project Backend Controller and Evaluation Endpoints unit and determine the exact first implementation slice.',
        'explicit_request' => "Review the read-only architectural analysis and scope definition for M2.1 (task 'M2.1-ANALYZE-001'). Provide architectural evaluation, approve the plan or issue the first implementation dispatch (M2.1-IMPLEMENT-001).",
        'context' => [
            'dependencies' => ['M1.1'],
            'allowed_files' => [
                'docs/migration/STATE.json',
                'docs/migration/FEATURE_MATRIX.md'
            ],
            'task_report' => [
                'task_id' => 'M2.1-ANALYZE-001',
                'unit_id' => 'M2.1',
                'status' => 'COMPLETED',
                'summary' => 'Read-only architectural analysis of M2.1 completed. Discovered legacy files, target gaps, 11 controller endpoints, 2 database tables, AttainmentService dependency, and confirmed ZERO coupling with AttendanceController. Proposed 2-slice decomposition with M2.1-IMPLEMENT-001 as first concrete task.',
                'changes' => [],
                'tests' => [
                    'read_only_audit' => 'PASSED (0 repository files modified, 0 database modifications)'
                ],
                'issues' => [],
                'git' => [
                    'branch' => 'migration-alpha',
                    'tracked_diff' => 'EMPTY',
                    'untracked_files' => 'NONE',
                    'commit' => '06f68cc61082081c4e598bfe6a61d6b1b6222f0d'
                ]
            ]
        ]
    ],
    $runId
);

echo "Dispatching M2.1-ANALYZE-001 TASK_REPORT to ChatGPT via CDP bridge...\n";
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
