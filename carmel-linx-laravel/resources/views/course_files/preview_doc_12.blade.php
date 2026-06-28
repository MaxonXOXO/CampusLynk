@php
    $room = $room ?? null;
    $initialMarks = $initialMarks ?? [];
@endphp

<div class="print-container text-black font-serif text-[10px] bg-white" style="width: 210mm; margin: 0 auto; line-height: 1.5;">

    <!-- ==================== PAGE 1: COVER PAGE ==================== -->
    <div class="print-page relative flex flex-col justify-center items-center" style="height: 297mm; page-break-after: always; padding: 20mm;">
        <!-- Border -->
        <div class="absolute inset-8 border-4 border-double border-gray-800"></div>

        <div class="text-center z-10 space-y-12 w-full max-w-lg">
            <div>
                <h1 class="text-base font-black uppercase tracking-widest text-gray-900 border-b-2 border-gray-900 pb-4 mb-4">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
                @php
                    $branchCode = $cf->batchSubject->classroom->branch ?? '';
                    $fullBranch = match($branchCode) {
                        'CT' => 'Computer Engineering',
                        'EL' => 'Electronics Engineering',
                        'ME' => 'Mechanical Engineering',
                        'AU' => 'Automobile Engineering',
                        'CE' => 'Civil Engineering',
                        'EE' => 'Electrical & Electronics Engineering',
                        default => $branchCode ?: 'General'
                    };
                @endphp
                <p class="text-xs font-bold text-gray-600 uppercase">Department of {{ $fullBranch }}</p>
                <p class="text-md text-gray-500 font-mono mt-2">Academic Year: {{ $cf->academic_year ?? 'N/A' }} | Semester: {{ $cf->batchSubject->semester ?? 'N/A' }}</p>
            </div>

            <div class="py-12">
                <p class="text-gray-500 font-bold uppercase tracking-widest mb-4">Course File Document 12</p>
                <h2 class="text-5xl font-black text-gray-900 uppercase">Remedial Class<br>Report</h2>
            </div>

            <div class="bg-gray-100 p-6 rounded border border-gray-300 shadow-inner text-left space-y-4">
                <div class="grid grid-cols-3 gap-4 border-b border-gray-300 pb-2">
                    <span class="font-bold text-gray-600">Subject:</span>
                    <span class="col-span-2 font-mono font-bold text-gray-900">{{ $cf->batchSubject->subject_code ?? 'N/A' }} - {{ $cf->batchSubject->subject_name ?? 'N/A' }}</span>
                </div>
                <div class="grid grid-cols-3 gap-4 border-b border-gray-300 pb-2">
                    <span class="font-bold text-gray-600">Batch:</span>
                    <span class="col-span-2 font-mono text-gray-900">{{ $cf->batchSubject->classroom_id ?? 'N/A' }}</span>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <span class="font-bold text-gray-600">Faculty:</span>
                    <span class="col-span-2 text-gray-900 uppercase font-bold">{{ session('userName') ?? 'N/A' }}</span>
                </div>
            </div>

            @if(!$room)
                <div class="mt-8 p-4 bg-red-100 border border-red-400 text-red-700 font-bold rounded">
                    NO REMEDIAL ROOM DATA FOUND FOR THIS SUBJECT & BATCH.<br>
                    <span class="text-xs font-normal">Please go to Remedial Sessions and provision a room first.</span>
                </div>
            @endif
        </div>
    </div>

    @if($room)
    <!-- ==================== PAGE 2: STUDENTS & LOGS ==================== -->
    <div class="print-page" style="height: 297mm; page-break-after: always; padding: 15mm; box-sizing: border-box;">
        <h3 class="text-sm font-bold uppercase border-b-2 border-gray-800 pb-2 mb-6 text-center">Part A: Enrolled Students & Session Logs</h3>

        <!-- Section A: Enrolled Students -->
        <h4 class="text-md font-bold mb-3 text-gray-800">1. List of Enrolled Weak Students</h4>
        <div class="mb-8">
            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-400 p-2 w-16 text-center">Sl No</th>
                        <th class="border border-gray-400 p-2 w-32">Register No</th>
                        <th class="border border-gray-400 p-2">Student Name</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($room->students as $idx => $student)
                        <tr>
                            <td class="border border-gray-400 p-2 text-center">{{ $idx + 1 }}</td>
                            <td class="border border-gray-400 p-2 font-mono">{{ $student->reg_no }}</td>
                            <td class="border border-gray-400 p-2">{{ $student->profile->name ?? 'Unknown' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border border-gray-400 p-4 text-center italic text-gray-500">No students enrolled.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <p class="text-xs text-gray-500 mt-2 text-right">Total Enrolled: <b>{{ count($room->students) }}</b></p>
        </div>

        <!-- Section B: Session Logs -->
        <h4 class="text-md font-bold mb-3 text-gray-800">2. Coaching Session Logs</h4>
        <div>
            <table class="w-full text-[11px] border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-400 p-2 w-12 text-center">#</th>
                        <th class="border border-gray-400 p-2 w-24">Date</th>
                        <th class="border border-gray-400 p-2 w-24">Time / Dur</th>
                        <th class="border border-gray-400 p-2">Topic Covered</th>
                        <th class="border border-gray-400 p-2 w-24 text-center">Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($room->logs as $idx => $log)
                        @php
                            $attCount = is_array($log->attendance_data) ? count($log->attendance_data) : 0;
                            $total = count($room->students);
                        @endphp
                        <tr>
                            <td class="border border-gray-400 p-2 text-center font-bold">{{ $idx + 1 }}</td>
                            <td class="border border-gray-400 p-2">{{ \Carbon\Carbon::parse($log->session_date)->format('d-M-Y') }}</td>
                            <td class="border border-gray-400 p-2">{{ $log->start_time ?? '--:--' }}<br><span class="text-[9px] text-gray-500">({{ $log->duration_minutes }}m)</span></td>
                            <td class="border border-gray-400 p-2">{{ $log->topic_covered }}</td>
                            <td class="border border-gray-400 p-2 text-center">{{ $attCount }} / {{ $total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-gray-400 p-4 text-center italic text-gray-500">No sessions logged yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== PAGE 3: CONSOLIDATED MARKS ==================== -->
    <div class="print-page" style="height: 297mm; page-break-after: always; padding: 15mm; box-sizing: border-box;">
        <h3 class="text-sm font-bold uppercase border-b-2 border-gray-800 pb-2 mb-6 text-center">Part B: Assessment & Improvement Matrix</h3>

        <!-- Section C: Consolidated Marks -->
        <h4 class="text-md font-bold mb-3 text-gray-800">3. Consolidated Remedial Assessments</h4>
        <div class="mb-8">
            <table class="w-full text-[10px] border-collapse text-center">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-400 p-2 text-left w-24">Reg No</th>
                        <th class="border border-gray-400 p-2 text-left">Name</th>
                        @foreach($room->assessments as $assess)
                            <th class="border border-gray-400 p-1 w-16">
                                {{ substr($assess->title, 0, 15) }}..<br>
                                <span class="font-normal text-[8px]">Max: {{ $assess->max_marks }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($room->students as $student)
                        <tr>
                            <td class="border border-gray-400 p-2 text-left font-mono">{{ $student->reg_no }}</td>
                            <td class="border border-gray-400 p-2 text-left">{{ $student->profile->name ?? 'Unknown' }}</td>
                            @foreach($room->assessments as $assess)
                                @php
                                    $scoreObj = $assess->scores->where('reg_no', $student->reg_no)->first();
                                    $score = $scoreObj ? $scoreObj->score : '-';
                                @endphp
                                <td class="border border-gray-400 p-2 font-bold {{ is_numeric($score) && $score < ($assess->max_marks * 0.4) ? 'text-red-600' : '' }}">
                                    {{ $score }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    @if(count($room->students) == 0)
                        <tr><td colspan="{{ 2 + count($room->assessments) }}" class="border border-gray-400 p-4 text-center italic">No students enrolled.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Section D: Improvement Analysis -->
        <h4 class="text-md font-bold mb-3 text-gray-800">4. Overall Improvement Matrix</h4>
        <p class="text-xs text-gray-600 mb-2 italic">Comparison of Internal Exam marks prior to remedial coaching against aggregate remedial assessment performance.</p>
        <div>
            <table class="w-full text-xs border-collapse text-center">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-400 p-2 text-left">Reg No</th>
                        <th class="border border-gray-400 p-2 text-left">Name</th>
                        <th class="border border-gray-400 p-2 w-24">Initial Marks<br><span class="text-[9px] font-normal">(Internal Exam)</span></th>
                        <th class="border border-gray-400 p-2 w-24">Remedial Avg<br><span class="text-[9px] font-normal">(% Score)</span></th>
                        <th class="border border-gray-400 p-2 w-24">Improvement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($room->students as $student)
                        @php
                            $initial = isset($initialMarks[$student->reg_no]) ? $initialMarks[$student->reg_no] : 0;
                            
                            // Calc Remedial Avg %
                            $totalScore = 0;
                            $totalMax = 0;
                            foreach($room->assessments as $assess) {
                                $scoreObj = $assess->scores->where('reg_no', $student->reg_no)->first();
                                if ($scoreObj && is_numeric($scoreObj->score)) {
                                    $totalScore += $scoreObj->score;
                                    $totalMax += $assess->max_marks;
                                }
                            }
                            $remAvg = $totalMax > 0 ? round(($totalScore / $totalMax) * 100, 1) : 0;
                            
                            // Dummy calculation for Initial % assuming out of 50 for visuals
                            $initPct = min(100, round(($initial / 50) * 100, 1)); 
                            
                            $growth = $remAvg - $initPct;
                        @endphp
                        <tr>
                            <td class="border border-gray-400 p-2 text-left font-mono">{{ $student->reg_no }}</td>
                            <td class="border border-gray-400 p-2 text-left">{{ $student->name }}</td>
                            <td class="border border-gray-400 p-2">{{ $initial }} <span class="text-gray-400 text-[10px]">({{ $initPct }}%)</span></td>
                            <td class="border border-gray-400 p-2 font-bold">{{ $remAvg }}%</td>
                            <td class="border border-gray-400 p-2 font-bold {{ $growth > 0 ? 'text-green-600' : ($growth < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                {{ $growth > 0 ? '+' : '' }}{{ $growth }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== PAGE 4: ATTACHMENTS ==================== -->
    <div class="print-page flex flex-col items-center justify-center" style="height: 297mm; padding: 20mm; box-sizing: border-box;">
        <div class="w-full h-full border-4 border-dashed border-gray-300 rounded-3xl flex flex-col items-center justify-center bg-gray-50/50 relative">
            <h2 class="text-base font-black text-gray-400 uppercase tracking-widest text-center">Attach Evidence Here</h2>
            <p class="text-gray-400 text-center mt-4 max-w-md">
                Please attach hardcopies of the remedial written test specimens, assignment sheets, and any other activity proofs related to this class.
            </p>
            
            <div class="absolute bottom-12 left-12 right-12 flex justify-between text-gray-400 font-bold text-[10px] uppercase border-t-2 border-gray-200 pt-4">
                <span>Subject: {{ $cf->batchSubject->subject_code ?? 'N/A' }}</span>
                <span>Faculty Signature: _________________</span>
            </div>
        </div>
    </div>

    @endif
</div>
