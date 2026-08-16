<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Review — {{ $student->name }} | CampusLynk</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FAFAFB] text-slate-800 flex flex-col antialiased font-['Poppins']">

    <div class="flex h-screen overflow-hidden bg-[#FAFAFB]">
        
        <!-- Master Sidebar Navigation (Student Role, active: attendance) -->
        <x-layout.sidebar role="student" active="attendance" />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#FAFAFB]">
            
            <!-- Master TopBar Component -->
            <x-layout.topbar title="Attendance Review" subtitle="Real-time daily periods, subject-wise attendance trajectory, and leave records." />

            <!-- Scrollable Main View Area -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">

                <!-- Top KPI Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Cumulative Attendance Card -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between items-center text-center">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Overall Semester Attendance</span>
                        
                        <div class="relative w-32 h-32 flex items-center justify-center my-3">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent" />
                                <circle cx="50" cy="50" r="40" stroke="{{ $overallAttendancePct >= 75 ? '#10B981' : ($overallAttendancePct >= 65 ? '#F59E0B' : '#EF4444') }}" stroke-width="8" fill="transparent"
                                        stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (($overallAttendancePct / 100) * 251.2) }}" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                            </svg>
                            <div class="absolute flex flex-col items-center leading-none">
                                <span class="text-2xl font-bold text-slate-900">{{ $overallAttendancePct }}%</span>
                                <span class="text-[10px] text-slate-400 font-medium mt-1 uppercase">Target 75%</span>
                            </div>
                        </div>

                        <div class="text-xs text-slate-500 font-medium">
                            Status: 
                            <span class="font-bold {{ $overallAttendancePct >= 75 ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $overallAttendancePct >= 75 ? 'Satisfactory & Eligible' : 'Shortage Alert (Condonation Required)' }}
                            </span>
                        </div>
                    </div>

                    <!-- Hours Statistics Card -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between space-y-4">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 pb-2">Academic Hours Summary</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                                <span class="text-xs text-slate-600 font-medium">Conducted Sessions</span>
                                <span class="text-sm font-bold font-mono text-slate-900">{{ $totalConductedClasses }} Hours</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/60 border border-emerald-200/60">
                                <span class="text-xs text-emerald-800 font-medium">Attended Sessions</span>
                                <span class="text-sm font-bold font-mono text-emerald-950">{{ $totalAttendedClasses }} Hours</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-rose-50/60 border border-rose-200/60">
                                <span class="text-xs text-rose-800 font-medium">Absent Sessions</span>
                                <span class="text-sm font-bold font-mono text-rose-950">{{ max(0, $totalConductedClasses - $totalAttendedClasses) }} Hours</span>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-400">Calculated across standard periods 1 to 6.</div>
                    </div>

                    <!-- Class & Faculty Tutor Card -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between space-y-4">
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 pb-2">Institutional Advisor</h3>
                        
                        <div class="space-y-3">
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                                <p class="text-xs font-medium text-slate-500">Classroom Identifier</p>
                                <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $classroom ? $classroom->classroom_id : $student->classroom_id }}</p>
                            </div>
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                                <p class="text-xs font-medium text-slate-500">Class Tutor</p>
                                <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $tutor ? $tutor->name : 'Department Faculty Assigned' }}</p>
                                @if($tutor && $tutor->mobile_no)
                                    <p class="text-[11px] text-slate-500 font-mono mt-0.5">Contact: {{ $tutor->mobile_no }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-400">Regular contact tutor for condonation and leaves.</div>
                    </div>

                </div>

                <!-- Today's Hour-Wise Attendance Grid -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                        <div>
                            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                <i data-lucide="clock" class="w-4 h-4 text-blue-600"></i>
                                <span>Today's Period Attendance Timeline</span>
                            </h2>
                            <p class="text-xs text-slate-500 mt-0.5">{{ now()->format('l, d F Y') }} • Hourly live attendance logs</p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                            Live Log Active
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3 pt-2">
                        @foreach($hourlyStatus as $p)
                            <div class="p-3.5 rounded-xl border {{ $p['status'] === 'Present' ? 'bg-emerald-50/50 border-emerald-200/80' : ($p['status'] === 'Absent' ? 'bg-rose-50/50 border-rose-200/80' : 'bg-slate-50 border-slate-200/80') }} flex flex-col justify-between space-y-2">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                            {{ $p['period'] === 7 ? 'Hour 7' : 'Hour ' . $p['period'] }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $p['status'] === 'Present' ? 'bg-emerald-100 text-emerald-800' : ($p['status'] === 'Absent' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-600') }}">
                                            {{ $p['status'] }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-900 mt-2 line-clamp-1" title="{{ $p['subject_name'] }}">
                                        {{ $p['subject_name'] }}
                                    </p>
                                    <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1" title="{{ $p['topic'] }}">
                                        {{ $p['topic'] ?: 'Session' }}
                                    </p>
                                </div>
                                <div class="text-[10px] font-mono text-slate-400 border-t border-slate-200/60 pt-2">
                                    {{ $p['time_slot'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Subject-Wise Attendance Breakdown Table -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
                            <span>Subject-Wise Attendance Distribution</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Continuous attendance records and minimum 75% examination eligibility thresholds.</p>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                                    <th class="py-3 px-4">Subject Code & Name</th>
                                    <th class="py-3 px-4 text-center">Conducted</th>
                                    <th class="py-3 px-4 text-center">Attended</th>
                                    <th class="py-3 px-4 text-center">Percentage</th>
                                    <th class="py-3 px-4 text-right">Eligibility Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-800">
                                @forelse($subjectStats as $sub)
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-3.5 px-4">
                                            <p class="font-semibold text-slate-900 text-xs">{{ $sub['subject_code'] }} - {{ $sub['subject_name'] }}</p>
                                        </td>
                                        <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-700">{{ $sub['conducted'] }}</td>
                                        <td class="py-3.5 px-4 text-center font-mono font-bold text-blue-700">{{ $sub['attended'] }}</td>
                                        <td class="py-3.5 px-4 text-center font-bold font-mono {{ $sub['percentage'] >= 75 ? 'text-emerald-700' : 'text-rose-600' }}">
                                            {{ $sub['percentage'] }}%
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $sub['percentage'] >= 75 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/60' : 'bg-rose-50 text-rose-800 border border-rose-200/60' }}">
                                                {{ $sub['percentage'] >= 75 ? 'Eligible' : 'Shortage (<75%)' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-6 text-center text-slate-400">No subject attendance logs available yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Leave & Absence Records Table -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i>
                            <span>Student Leave & Absence Log</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Registered medical leaves, duty leaves, and tutor condonation records.</p>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                                    <th class="py-3 px-4">Leave Date</th>
                                    <th class="py-3 px-4">Type / Reason</th>
                                    <th class="py-3 px-4">Period Range</th>
                                    <th class="py-3 px-4 text-right">Verification Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-800">
                                @forelse($leaveRecords ?? [] as $leave)
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-3.5 px-4 font-mono font-semibold text-slate-900">{{ $leave->leave_date }}</td>
                                        <td class="py-3.5 px-4 text-slate-700">{{ $leave->reason ?? 'Medical / Personal Leave' }}</td>
                                        <td class="py-3.5 px-4 text-slate-600">{{ $leave->period_range ?? 'Full Day' }}</td>
                                        <td class="py-3.5 px-4 text-right">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $leave->status === 'Approved' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/60' : 'bg-amber-50 text-amber-800 border border-amber-200/60' }}">
                                                {{ $leave->status ?? 'Verified by Tutor' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="p-6 text-center text-slate-400">No formal leave requests recorded for this semester.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

        </div>
    </div>

</body>
</html>
