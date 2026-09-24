<?php

/**
 * CampusLynk Migration Control Plane — Dispatch Backwards Migration Plan Request to ChatGPT
 */

declare(strict_types=1);

require_once __DIR__ . '/orchestrator-bridge.php';

$baseDir = dirname(__DIR__, 2);
$bridge = new OrchestratorBridge($baseDir);

$runId = 'run_' . gmdate('Ymd') . '_02';

$auditReportContent = file_get_contents($baseDir . '/BACKWARDS_MIGRATION_AUDIT_REPORT.md');
if (!$auditReportContent) {
    // Fallback path in artifact directory if local file not found
    $alt = 'C:/Users/HOME/.gemini/antigravity-ide/brain/40aa05ee-b817-4a67-96d9-f0afaaffd896/BACKWARDS_MIGRATION_AUDIT_REPORT.md';
    if (file_exists($alt)) {
        $auditReportContent = file_get_contents($alt);
    }
}

$backwardsAuditContext = <<<CONTEXT
# CAMPUSLYNK BACKWARDS FORENSIC MIGRATION AUDIT & RECONCILIATION REQUEST

## 1. CONTEXT & BACKGROUND
The Orchestrator / Forensic Worker performed an independent "backwards checking" verification pass by comparing `legacy-academic-platform` (`main` @ `70b11740`) directly against `CampusLynk` (`migration-alpha` @ `8ae50886`).

## 2. KEY AUDIT DISCOVERIES
1. **Parallel Legacy Horizon**: While CampusLynk migrated units M1.1–M9.4 from the Aug 24 base, legacy continued evolving with 45 high-impact feature commits between Aug 24 and Sep 20, 2026.
2. **40 Web Routes Missing in Target**: Including practical lab evaluation endpoints, experiment date syncing, Teams attendance import, and print endpoints.
3. **17 Missing Legacy Views**:
   - `r26_practicum/virtual_classroom_basic_science_practicum.blade.php` (7,366 lines, dedicated Physics/Chemistry 40M CIA / 60M ESE workspace)
   - `hod/program_attainment_dashboard.blade.php` & `hod/program_attainment_print.blade.php` (SBTE Kerala 10-point scale PO/PSO engine)
   - 4 practical print views (`classroom_practical_experiments_print`, `classroom_practical_final_results_print`, `classroom_practical_series_print`, `classroom_practical_student_report_print`)
   - 2 tutor progress print views (`progress_report_card_print`, `progress_report_consolidated_print`)
   - Classroom print templates (`classroom_subject_log_print`, `classroom_theory_final_results_print`, `classroom_theory_roster_print`)
   - `partials/lab_batch_setup_modal.blade.php`, `partials/sbte_bulk_import_modal.blade.php`
4. **4 Missing Database Migrations**:
   - `2026_08_22_000001_add_suspension_fields_to_principal_scheduled_events_table.php`
   - `2026_09_07_140000_add_evaluation_date_to_practical_experiment_marks.php`
   - `2026_09_09_191438_add_lab_batch_config_to_batch_subjects.php`
   - `2026_09_19_030500_add_attainment_settings_to_course_files_table.php`
5. **Functional Discrepancies**:
   - R26 Theory: SBTE grade entry field with 60M scaling (`b367d893`), lesson plan row deletion with confirmation (`b076fff2`).
   - R26 Practicum: Table 2.2 and Table 3.1 debounced autosave inline-editable tables (`2df1a5b6`, `c87874a6`).
   - Virtual Lab 2021: Batch split setup, multi-experiment selection, in-place log dates sync (`dacd8e24`, `ff663935`).
   - Carmie AI: Citations support, `carmie_query_model.json`, and `queryGemini` controller endpoint (`70b11740`).

## 3. OBJECTIVE FOR ARCHITECT
Please review these backwards migration audit findings and:
1. Sketch out the overall reconciliation migration plan (e.g. Wave 6: Backwards Parity Reconciliation).
2. Break down the migration tasks into logical phases (e.g. Phase 1: Database & Migrations, Phase 2: R26 Practicum & Basic Science, Phase 3: Virtual Lab & Statutory Prints, Phase 4: Attainment, Carmie & AI, Phase 5: Route Harmonization & Verification).
3. Issue the first implementation task dispatch directive (`TASK_DISPATCH`) for Phase 1 so the Orchestrator can execute it immediately.

The full audit report has been compiled and is provided below for your detailed review.

---
$auditReportContent
CONTEXT;

$envelope = $bridge->createRequestEnvelope(
    'ARCHITECTURE_REVIEW',
    'M10.1',
    'ANALYZING',
    [
        'unit_title' => 'Wave 6: Backwards Migration Parity & Reconciliation (M10.1)',
        'objective' => 'Review backwards migration forensic audit comparing legacy HEAD (70b11740) against CampusLynk (8ae50886), sketch out the reconciliation plan broken into phased tasks, and dispatch Phase 1 implementation instructions.',
        'explicit_request' => 'Please analyze the backwards migration audit report, sketch out the full reconciliation plan broken into phased tasks, and provide the initial TASK_DISPATCH with implementation instructions for Phase 1 so we can proceed immediately.',
        'context' => [
            'dependencies' => ['M9.4'],
            'allowed_files' => [
                'BACKWARDS_MIGRATION_AUDIT_REPORT.md',
                'docs/migration/STATE.json',
                'routes/web.php',
                'database/migrations/*',
                'app/Http/Controllers/*',
                'resources/views/*'
            ]
        ]
    ],
    $runId
);

echo "Dispatching Backwards Migration Plan Request to ChatGPT via CDP bridge...\n";
echo "Message ID: {$envelope['message_id']}\n";
echo "Run ID:     {$envelope['run_id']}\n";
echo "Unit ID:    {$envelope['unit_id']}\n\n";

$result = $bridge->send($envelope, 120, false, $backwardsAuditContext);

echo "\n--- Bridge Response Result ---\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

if (!empty($result['success'])) {
    echo "\n[SUCCESS] Received validated architect directive from ChatGPT!\n";
    echo "Decision: " . ($result['decision'] ?? 'UNKNOWN') . "\n";
    exit(0);
} else {
    echo "\n[FAIL] Bridge request failed or rejected by fail-closed guardrails.\n";
    exit(1);
}
