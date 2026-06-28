<?php
$path = 'resources/views/course_files_dashboard.blade.php';
$content = file_get_contents($path);

// Find the first defaultChecklist at the top of the file
$badScript = '    <script>
        const defaultChecklist = [
            \'Class Time table (current semester Program timetable)\',
            \'Faculty Workload\',
            \'Student List with register numbers\',
            \'Course Syllabus with Recommended Books (SITTTR)\',
            \'Course information sheet\',
            \'Course outcomes\',
            \'Academic calender\',
            \'Course Plan\',
            \'Course log and Attendance from TEAMS\',
            \'Internal Exam Question Papers CO 1,2,3,4 with mark splitup / Scheme\',
            \'Internal Examination Result Analysis NBA\',
            \'Weaker student coaching schedule and proof (if)\',
            \'Teaching and Learning Methods Proof - handouts, capsule notes etc.\',
            \'Assignment questions with rubrics\',
            \'CE Report (SBTE - common)\',
            \'Grade Sheet - Proof of CO evaluations\',
            \'External Exam Question Papers / Question bank\',
            \'SBTE examination result\',
            \'Attainment of Course Outcome (CO) Co-Po-PsoO map\',
            \'Attainment of PO/PSO report\',
            \'Mid semester survey & report\',
            \'End semester / Course exit survey & report\',
            \'Internal Examination sample answer scripts\',
            \'Assignment sample scripts\',
            \'Others\'
        ];';

$content = str_replace($badScript, '    <script>', $content);
file_put_contents($path, $content);
echo "Removed duplicate const defaultChecklist.\n";
