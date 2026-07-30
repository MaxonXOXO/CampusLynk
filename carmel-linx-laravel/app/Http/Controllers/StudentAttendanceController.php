<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StudentAttendanceController extends Controller
{
    /**
     * Show Mobile-Friendly Student Attendance Review Page.
     */
    public function showStudentAttendance()
    {
        if (Session::get('userRole') !== 'Student') {
            return redirect('/');
        }

        $regNo = Session::get('userId');
        $student = DB::table('students')->where('reg_no', $regNo)->orWhere('adm_no', $regNo)->first();

        if (!$student) {
            return redirect('/dashboard/student')->with('error', 'Student profile not found.');
        }

        // Classroom & Tutor details
        $classroom = DB::table('class_management')->where('classroom_id', $student->classroom_id)->first();
        $tutor = null;
        if ($classroom && $classroom->tutor_mobile_no) {
            $tutor = DB::table('staff_profiles')->where('mobile_no', $classroom->tutor_mobile_no)->first();
        }

        // Batch Subjects
        $batchSubjects = DB::table('batch_subjects')
            ->where('classroom_id', $student->classroom_id)
            ->orderBy('subject_code', 'asc')
            ->get();

        $subjectIds = $batchSubjects->pluck('id')->toArray();

        // 6-Hour Period Timings Definition
        $periodTimings = [
            1 => '9:00 AM – 10:00 AM',
            2 => '10:00 AM – 11:00 AM',
            3 => '11:10 AM – 12:10 PM',
            4 => '1:00 PM – 2:00 PM',
            5 => '2:00 PM – 3:00 PM',
            6 => '3:00 PM – 4:00 PM',
            7 => 'Special / Extra Class'
        ];

        // Today's Hour-Wise Attendance Grid
        $todayDate = now()->toDateString();
        $todayLogs = DB::table('class_logs_attendance')
            ->whereIn('batch_subject_id', $subjectIds)
            ->where('date', $todayDate)
            ->orderBy('period', 'asc')
            ->get();

        $hourlyStatus = [];
        for ($p = 1; $p <= 6; $p++) {
            $hourlyStatus[$p] = [
                'period' => $p,
                'time_slot' => $periodTimings[$p],
                'status' => 'Not Marked',
                'subject_name' => 'Free Period',
                'subject_code' => '',
                'topic' => '',
                'badge_class' => 'bg-slate-800 text-slate-400 border border-slate-700'
            ];
        }

        // Period 7: Special / Remedial Hour
        $hourlyStatus[7] = [
            'period' => 7,
            'time_slot' => $periodTimings[7],
            'status' => 'Not Scheduled',
            'subject_name' => 'Special Hour (Remedial / Extra Class)',
            'subject_code' => 'P7',
            'topic' => 'Special / Remedial Session',
            'badge_class' => 'bg-slate-950 text-slate-500 border border-slate-800'
        ];

        foreach ($todayLogs as $log) {
            $period = (int)$log->period;
            if ($period >= 1 && $period <= 7) {
                $subj = $batchSubjects->firstWhere('id', $log->batch_subject_id);
                $pList = json_decode($log->present_students ?? '[]', true) ?: [];
                $aList = json_decode($log->absent_students ?? '[]', true) ?: [];

                $statusText = 'Absent';
                $badgeClass = 'bg-rose-500/20 text-rose-400 border border-rose-500/30';

                if (in_array($student->reg_no, $pList)) {
                    $statusText = 'Present';
                    $badgeClass = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                } elseif (in_array($student->reg_no, $aList)) {
                    $statusText = 'Absent';
                    $badgeClass = 'bg-rose-500/20 text-rose-400 border border-rose-500/30';
                }

                $hourlyStatus[$period] = [
                    'period' => $period,
                    'time_slot' => $periodTimings[$period],
                    'status' => $statusText,
                    'subject_name' => ($period === 7 ? '[Special 7th Hour] ' : '') . ($subj->subject_name ?? 'Class Session'),
                    'subject_code' => $subj->subject_code ?? '',
                    'topic' => $log->topics_covered ?? ($period === 7 ? 'Remedial / Extra Class' : 'Regular Session'),
                    'badge_class' => $badgeClass
                ];
            }
        }

        // Overall Attendance Calculation (Strictly Periods 1 to 6)
        $totalConductedClasses = 0;
        $totalAttendedClasses = 0;

        $subjectStats = [];
        foreach ($batchSubjects as $subj) {
            $logs = DB::table('class_logs_attendance')
                ->where('batch_subject_id', $subj->id)
                ->where('period', '<=', 6) // Standard 6-Hour Academic Day
                ->get(['present_students', 'absent_students']);

            $subjConducted = 0;
            $subjAttended = 0;

            foreach ($logs as $l) {
                $pList = json_decode($l->present_students ?? '[]', true) ?: [];
                $aList = json_decode($l->absent_students ?? '[]', true) ?: [];

                if (in_array($student->reg_no, $pList) || in_array($student->reg_no, $aList)) {
                    $subjConducted++;
                    $totalConductedClasses++;
                    if (in_array($student->reg_no, $pList)) {
                        $subjAttended++;
                        $totalAttendedClasses++;
                    }
                }
            }

            $subjPct = $subjConducted > 0 ? round(($subjAttended / $subjConducted) * 100, 1) : 100.0;
            $subjectStats[] = [
                'subject_code' => $subj->subject_code,
                'subject_name' => $subj->subject_name,
                'conducted' => $subjConducted,
                'attended' => $subjAttended,
                'percentage' => $subjPct
            ];
        }

        $overallAttendancePct = $totalConductedClasses > 0 
            ? round(($totalAttendedClasses / $totalConductedClasses) * 100, 1) 
            : 100.0;

        return view('student_attendance', compact(
            'student',
            'classroom',
            'tutor',
            'hourlyStatus',
            'totalConductedClasses',
            'totalAttendedClasses',
            'overallAttendancePct',
            'subjectStats',
            'periodTimings'
        ));
    }
}
