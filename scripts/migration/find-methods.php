<?php
$lines = file('d:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/ClassroomController.php');
foreach ($lines as $i => $l) {
    if (preg_match('/function\s+(updatePracticalExperimentDate|saveBulkPracticalEvaluations|syncLessonPlanDatesFromLogs|printTheoryFinalResults|printTheoryClassRoster|printTheoryClassLog)/', $l, $m)) {
        echo ($i + 1) . ': ' . $m[1] . PHP_EOL;
    }
}
