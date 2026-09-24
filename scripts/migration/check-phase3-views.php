<?php
$legacyBase = 'd:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/resources/views/';
$targetBase = 'd:/CampusLynk/CampusLynk/carmel-linx-laravel/resources/views/';

$views = [
    'classroom_practical_experiments_print.blade.php',
    'classroom_practical_final_results_print.blade.php',
    'classroom_practical_series_print.blade.php',
    'classroom_practical_student_report_print.blade.php',
    'classroom_subject_log_print.blade.php',
    'classroom_theory_final_results_print.blade.php',
    'classroom_theory_roster_print.blade.php',
    'tutor/attendance_consolidated_print.blade.php',
    'tutor/progress_report_card_print.blade.php',
    'tutor/progress_report_consolidated_print.blade.php',
    'partials/lab_batch_setup_modal.blade.php'
];

foreach ($views as $v) {
    $legPath = $legacyBase . $v;
    $tgtPath = $targetBase . $v;
    $legCount = file_exists($legPath) ? count(file($legPath)) : 'MISSING';
    $tgtCount = file_exists($tgtPath) ? count(file($tgtPath)) : 'MISSING';
    echo sprintf("%-55s | Legacy: %-8s | Target: %-8s\n", $v, $legCount, $tgtCount);
}
