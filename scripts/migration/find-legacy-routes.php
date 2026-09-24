<?php
$content = file_get_contents('d:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/routes/web.php');

$searchTerms = [
    'printSeriesReport',
    'printFinalResults',
    'printExperimentsLog',
    'printStudentReport',
    'printTheoryFinalResults',
    'printTheoryClassRoster',
    'printTheoryClassLog',
    'updatePracticalExperimentDate',
    'saveBulkPracticalEvaluations',
    'syncLessonPlanDatesFromLogs',
    'lab-batch-setup',
    'batch-setup',
    'batch_setup'
];

foreach ($searchTerms as $term) {
    preg_match_all('/.*' . preg_quote($term, '/') . '.*/i', $content, $matches);
    echo "=== Search: $term ===\n";
    foreach ($matches[0] as $line) {
        echo trim($line) . "\n";
    }
}
