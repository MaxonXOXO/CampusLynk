<x-layouts.report-layout 
    title="Practical Experiments Conducted Log — {{ $batchSubject->subject_name }}" 
    orientation="portrait" 
    :documentNo="'SBTE-R21-' . $batchSubject->subject_code . '-EXP'">

    <!-- Institutional Header -->
    <div class="text-center pb-3 border-b-2 border-slate-900 mb-4">
        <h1 class="text-lg font-bold uppercase tracking-wider text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <p class="text-xs text-slate-600 font-medium">Department of {{ $fullDepartment }}</p>
        <h2 class="text-sm font-bold uppercase mt-1 text-slate-800 underline">Practical Experiments Conducted &amp; Session Log Report (Revision 2021)</h2>
    </div>

    <!-- Meta Information Grid -->
    <div class="grid grid-cols-2 gap-4 rounded border border-slate-300 p-3 text-xs mb-4 bg-slate-50">
        <div class="space-y-1">
            <div><strong>Batch / Class:</strong> <span class="font-semibold text-slate-900">{{ $cleanedBatch }}</span></div>
            <div><strong>Course / Subject:</strong> <span class="font-semibold text-slate-900">{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</span></div>
        </div>
        <div class="space-y-1 text-right">
            <div><strong>Semester:</strong> <span class="font-semibold text-slate-900">Semester {{ $batchSubject->semester ?? '-' }}</span></div>
            <div><strong>Report Date:</strong> <span class="font-mono">{{ date('d-m-Y') }}</span></div>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-4 gap-3 mb-5">
        <div class="p-2.5 rounded border border-slate-300 bg-slate-50 text-center">
            <div class="text-base font-bold text-blue-700 font-mono">{{ $conductedCount }}</div>
            <div class="text-[9px] uppercase font-bold text-slate-500 tracking-wider">Experiments Conducted</div>
        </div>
        <div class="p-2.5 rounded border border-slate-300 bg-slate-50 text-center">
            <div class="text-base font-bold text-slate-700 font-mono">{{ $totalExperiments }}</div>
            <div class="text-[9px] uppercase font-bold text-slate-500 tracking-wider">Total Syllabus Exps</div>
        </div>
        <div class="p-2.5 rounded border border-slate-300 bg-slate-50 text-center">
            <div class="text-base font-bold text-emerald-700 font-mono">{{ $coveragePct }}%</div>
            <div class="text-[9px] uppercase font-bold text-slate-500 tracking-wider">Syllabus Coverage</div>
        </div>
        <div class="p-2.5 rounded border border-slate-300 bg-slate-50 text-center">
            <div class="text-base font-bold text-indigo-700 font-mono">{{ $actualLabHours }} hrs</div>
            <div class="text-[9px] uppercase font-bold text-slate-500 tracking-wider">Lab Hours Covered</div>
        </div>
    </div>

    <!-- Experiments Conducted Table -->
    <div class="mb-6 overflow-hidden">
        <table class="w-full border-collapse border border-slate-400 text-xs">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold uppercase text-[9.5px]">
                    <th class="border border-slate-400 p-1.5 text-center w-8">Sl.</th>
                    <th class="border border-slate-400 p-1.5 text-center w-14">Exp No</th>
                    <th class="border border-slate-400 p-1.5 text-left">Title &amp; Topics Covered</th>
                    <th class="border border-slate-400 p-1.5 text-center w-12">CO</th>
                    <th class="border border-slate-400 p-1.5 text-center w-16">Batch</th>
                    <th class="border border-slate-400 p-1.5 text-center w-24">Date</th>
                    <th class="border border-slate-400 p-1.5 text-center w-20">Hours</th>
                    <th class="border border-slate-400 p-1.5 text-center w-24">Attended</th>
                    <th class="border border-slate-400 p-1.5 text-center w-10">Abs</th>
                    <th class="border border-slate-400 p-1.5 text-left w-36">Absentee Rolls</th>
                </tr>
            </thead>
            <tbody>
                @php $currentBatchHeader = null; @endphp
                @forelse($conductedDetails as $idx => $exp)
                    @php
                        $thisBatch = $exp['batch'] ?? 'Practical Session';
                        $formattedDate = '-';
                        if (!empty($exp['date']) && $exp['date'] !== 'Conducted') {
                            $ts = strtotime($exp['date']);
                            $formattedDate = $ts ? date('d-m-Y', $ts) : $exp['date'];
                        } elseif ($exp['date'] === 'Conducted') {
                            $formattedDate = 'Conducted';
                        }
                        $abCount = $exp['absent_count'] ?? max(0, ($exp['total_count'] ?? 0) - ($exp['present_count'] ?? 0));
                        $abRolls = $exp['absent_roll_nos'] ?? '-';
                        $attPct = isset($exp['attendance_pct']) ? ($exp['attendance_pct'] . '%') : '-';
                    @endphp

                    @if($currentBatchHeader !== $thisBatch)
                        @php $currentBatchHeader = $thisBatch; @endphp
                        <tr class="bg-slate-100 font-bold text-slate-800 text-[10px] uppercase tracking-wide">
                            <td colspan="10" class="border border-slate-400 p-1.5 bg-slate-200">
                                {{ $thisBatch }} — Practical Experiments &amp; Conducted Log Sessions
                            </td>
                        </tr>
                    @endif
                    <tr class="hover:bg-slate-50">
                        <td class="border border-slate-400 p-1.5 text-center font-bold text-slate-600">{{ $idx + 1 }}</td>
                        <td class="border border-slate-400 p-1.5 text-center font-bold font-mono text-blue-800">{{ $exp['experiment_no'] }}</td>
                        <td class="border border-slate-400 p-1.5 font-medium text-slate-900">{{ $exp['title'] }}</td>
                        <td class="border border-slate-400 p-1.5 text-center">
                            <span class="px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-800 text-[10px] font-bold">{{ $exp['co_tag'] ?? 'CO1' }}</span>
                        </td>
                        <td class="border border-slate-400 p-1.5 text-center">
                            <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-medium">{{ $exp['batch'] }}</span>
                        </td>
                        <td class="border border-slate-400 p-1.5 text-center font-mono text-[10.5px] font-bold text-slate-800">{{ $formattedDate }}</td>
                        <td class="border border-slate-400 p-1.5 text-center text-[10px] text-slate-600">{{ $exp['hours_text'] }}</td>
                        <td class="border border-slate-400 p-1.5 text-center text-[10.5px]">
                            <span class="font-bold text-emerald-800">{{ $exp['present_count'] }}</span> / {{ $exp['total_count'] }}
                            <span class="text-blue-700 text-[9.5px] font-mono block">({{ $attPct }})</span>
                        </td>
                        <td class="border border-slate-400 p-1.5 text-center font-bold text-[10.5px] {{ $abCount > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                            {{ $abCount }}
                        </td>
                        <td class="border border-slate-400 p-1.5 font-mono text-[10px] {{ $abCount > 0 ? 'text-rose-700 font-semibold' : 'text-slate-400' }}">
                            {{ $abRolls }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="border border-slate-400 p-4 text-center text-slate-500 italic">
                            No conducted practical experiments or sessions logged yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Institutional Signatures Footer -->
    <div class="grid grid-cols-3 gap-6 pt-12 mt-8 text-center text-xs font-bold text-slate-800 border-t border-slate-300 page-break-inside-avoid">
        <div>Name &amp; Signature of Faculty In-Charge</div>
        <div>Name &amp; Signature of Lab Coordinator</div>
        <div>Head of Department</div>
    </div>

</x-layouts.report-layout>
