@php
    $reportTitle = 'Seminar Presentation Schedule & Topic Log';
@endphp

@include('r21_seminar.partials.print.header')

<!-- Presentation Schedule Table -->
<div class="border border-slate-900 rounded overflow-hidden">
    <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-200 border-b border-slate-900">
            <tr>
                <th class="py-2.5 px-2 text-center border-r border-slate-400 w-10">Sl</th>
                <th class="py-2.5 px-3 border-r border-slate-400 w-28">Reg No</th>
                <th class="py-2.5 px-3 border-r border-slate-400 w-44">Student Name</th>
                <th class="py-2.5 px-3 border-r border-slate-400 w-32 text-center">Presentation Date</th>
                <th class="py-2.5 px-3 border-r border-slate-400">Approved Seminar Topic</th>
                <th class="py-2.5 px-3 border-r border-slate-400 w-44">Faculty Guide</th>
                <th class="py-2.5 px-2 text-center w-24">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-300">
            @forelse($students as $index => $st)
                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50' }}">
                    <td class="text-center font-mono py-2 px-2 border-r border-slate-300">{{ $st['roll_no'] ?? ($index + 1) }}</td>
                    <td class="font-mono text-xs font-bold py-2 px-3 border-r border-slate-300">{{ $st['sbte_reg_no'] ?? $st['reg_no'] }}</td>
                    <td class="font-bold text-slate-900 py-2 px-3 border-r border-slate-300">{{ $st['name'] }}</td>
                    <td class="text-center font-mono text-xs py-2 px-3 border-r border-slate-300">
                        {{ $st['presentation_date'] ?? '—' }}
                    </td>
                    <td class="py-2 px-3 border-r border-slate-300 text-xs">
                        <span class="font-medium text-slate-900">{{ $st['topic'] ?? '—' }}</span>
                    </td>
                    <td class="py-2 px-3 border-r border-slate-300 text-xs text-slate-700">
                        {{ $st['guide_name'] ?? '—' }}
                    </td>
                    <td class="text-center text-xs font-semibold py-2 px-2">
                        @if($st['status'] === 'Completed')
                            <span class="text-emerald-700 font-bold">Completed</span>
                        @elseif($st['status'] === 'Scheduled')
                            <span class="text-sky-700 font-bold">Scheduled</span>
                        @else
                            <span class="text-slate-500">Pending</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-4 text-center text-slate-500">No seminar schedule entries found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Schedule Summary -->
<div class="mt-4 p-3 border border-slate-300 rounded bg-slate-50 text-xs flex justify-between items-center">
    <div class="flex items-center gap-6">
        <span>Total Candidates: <strong>{{ $totalStudents }}</strong></span>
        <span>Completed Presentations: <strong class="text-emerald-700">{{ $completedCount }}</strong></span>
        <span>Pending Presentations: <strong class="text-amber-700">{{ $totalStudents - $completedCount }}</strong></span>
    </div>
    <div class="text-slate-600 italic text-[11px]">
        Official Department Seminar Schedule • SBTE Revision 2021
    </div>
</div>

@include('r21_seminar.partials.print.signatures')
