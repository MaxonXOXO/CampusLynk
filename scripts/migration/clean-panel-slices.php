<?php

declare(strict_types=1);

$baseViews = 'd:/CampusLynk/CampusLynk/carmel-linx-laravel/resources/views/';

// Clean a panel file by trimming everything after the closing </div> of the panel
function cleanPanelFile(string $path, string $panelId): void {
    if (!file_exists($path)) return;
    $content = file_get_contents($path);

    // Find the opening of the panel
    $openPos = strpos($content, '<div id="' . $panelId . '"');
    if ($openPos === false) return;

    // Find all <div> and </div> after openPos
    $sub = substr($content, $openPos);
    $depth = 0;
    $len = strlen($sub);
    $inTag = false;
    $endPos = -1;

    // Match tags
    preg_match_all('/<\/?div\b[^>]*>/i', $sub, $matches, PREG_OFFSET_CAPTURE);
    foreach ($matches[0] as $match) {
        $tag = $match[0];
        $offset = $match[1];
        if (str_starts_with($tag, '</')) {
            $depth--;
            if ($depth === 0) {
                $endPos = $offset + strlen($tag);
                break;
            }
        } else {
            $depth++;
        }
    }

    if ($endPos !== -1) {
        $cleanContent = trim(substr($sub, 0, $endPos));
        file_put_contents($path, $cleanContent . "\n");
        echo "  [CLEANED] " . basename(dirname($path)) . "/" . basename($path) . " (" . count(file($path)) . " lines)\n";
    }
}

// 1. Admin panels
cleanPanelFile($baseViews . 'admin/panel-dashboard.blade.php', 'panelDashboard');
cleanPanelFile($baseViews . 'admin/panel-timetables.blade.php', 'panelAll_timetables');
cleanPanelFile($baseViews . 'admin/panel-directory.blade.php', 'panelDirectory');
cleanPanelFile($baseViews . 'admin/panel-backups.blade.php', 'panelBackups');
cleanPanelFile($baseViews . 'admin/panel-audit.blade.php', 'panelAudit');
cleanPanelFile($baseViews . 'admin/panel-settings.blade.php', 'panelSettings');
cleanPanelFile($baseViews . 'admin/panel-prof-activities.blade.php', 'panelProf_activities');
cleanPanelFile($baseViews . 'admin/panel-leave-ledger.blade.php', 'panelLeave_ledger');
cleanPanelFile($baseViews . 'admin/panel-sf-attendance.blade.php', 'panelSf_attendance');
cleanPanelFile($baseViews . 'admin/panel-profile.blade.php', 'panelProfile');

// 2. Chairman panels
cleanPanelFile($baseViews . 'chairman/panel-dashboard.blade.php', 'panelDashboard');
cleanPanelFile($baseViews . 'chairman/panel-directory.blade.php', 'panelDirectory');
cleanPanelFile($baseViews . 'chairman/panel-audit.blade.php', 'panelAudit');

// 3. HOD panels
cleanPanelFile($baseViews . 'hod/panel-directory.blade.php', 'panelDirectory');
cleanPanelFile($baseViews . 'hod/panel-batches.blade.php', 'panelBatches');
cleanPanelFile($baseViews . 'hod/panel-subjects.blade.php', 'panelSubjects');
cleanPanelFile($baseViews . 'hod/panel-audit.blade.php', 'panelAudit');
cleanPanelFile($baseViews . 'hod/panel-leave-ledger.blade.php', 'panelLeave_ledger');
cleanPanelFile($baseViews . 'hod/panel-prof-activities.blade.php', 'panelProf_activities');
cleanPanelFile($baseViews . 'hod/panel-profile.blade.php', 'panelProfile');
cleanPanelFile($baseViews . 'hod/panel-report-centre.blade.php', 'panelReport_centre');

// 4. Lecturer panels
cleanPanelFile($baseViews . 'lecturer/panel-dashboard.blade.php', 'panelDashboard');
cleanPanelFile($baseViews . 'lecturer/panel-classroom.blade.php', 'panelClassroom');
cleanPanelFile($baseViews . 'lecturer/panel-security.blade.php', 'panelSecurity');
cleanPanelFile($baseViews . 'lecturer/panel-mobile-seminar.blade.php', 'panelMobileSeminar');

// 5. Tutor panels
cleanPanelFile($baseViews . 'tutor/panel-roster.blade.php', 'panelRoster');
cleanPanelFile($baseViews . 'tutor/panel-roll-numbers.blade.php', 'panelRollNumbers');
cleanPanelFile($baseViews . 'tutor/panel-audit.blade.php', 'panelAudit');
cleanPanelFile($baseViews . 'tutor/panel-profile.blade.php', 'panelProfile');
cleanPanelFile($baseViews . 'tutor/panel-mentoring.blade.php', 'panelMentoring');
cleanPanelFile($baseViews . 'tutor/panel-activity.blade.php', 'panelActivity');
cleanPanelFile($baseViews . 'tutor/panel-leave-approval.blade.php', 'panelLeaveApproval');

// 6. Workshop panels
cleanPanelFile($baseViews . 'workshop/panel-overview.blade.php', 'panelOverview');
cleanPanelFile($baseViews . 'workshop/panel-staff.blade.php', 'panelStaff');
cleanPanelFile($baseViews . 'workshop/panel-students.blade.php', 'panelStudents');
cleanPanelFile($baseViews . 'workshop/panel-audit.blade.php', 'panelAudit');
cleanPanelFile($baseViews . 'workshop/panel-security.blade.php', 'panelSecurity');

// 7. Student panels
cleanPanelFile($baseViews . 'student/panel-exams.blade.php', 'panelExams');
cleanPanelFile($baseViews . 'student/panel-marks.blade.php', 'panelMarks');
cleanPanelFile($baseViews . 'student/panel-profile.blade.php', 'panelProfile');
cleanPanelFile($baseViews . 'student/panel-mentoring.blade.php', 'panelMentoring');
cleanPanelFile($baseViews . 'student/panel-activity.blade.php', 'panelActivity');
cleanPanelFile($baseViews . 'student/panel-seminar.blade.php', 'panelSeminar');
cleanPanelFile($baseViews . 'student/panel-attendance.blade.php', 'panelAttendance');
cleanPanelFile($baseViews . 'student/panel-mock-test.blade.php', 'panelMock_test');

echo "[SUCCESS] Panels cleaned.\n";
