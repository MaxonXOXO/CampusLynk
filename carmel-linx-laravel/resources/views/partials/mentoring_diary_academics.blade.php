{{-- Sections IV to VII: Academic Progress, Board Exams, Extracurricular, Meetings --}}
<div class="page-break pt-6">
    <div class="bg-indigo-950 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-lg mb-3">IV. Academic Progress Report</div>
    
    <table class="w-full text-xs border border-slate-300 mb-4">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-2 border border-slate-300">Semester</th>
                <th class="p-2 border border-slate-300">SGPA</th>
                <th class="p-2 border border-slate-300">Activity Points</th>
                <th class="p-2 border border-slate-300">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($board) && count((array)$board) > 0)
                @foreach($board as $sem)
                <tr>
                    <td class="p-2 border border-slate-300">Semester {{ $sem->semester }}</td>
                    <td class="p-2 border border-slate-300 font-bold font-mono">{{ $sem->sgpa ?? '-' }}</td>
                    <td class="p-2 border border-slate-300 font-mono">{{ $sem->activity_points ?? '-' }}</td>
                    <td class="p-2 border border-slate-300">{{ $sem->remarks ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="4" class="p-3 text-center text-slate-400">No academic progress recorded yet.</td></tr>
            @endif
        </tbody>
    </table>

    <div class="bg-indigo-950 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-lg mb-3 mt-6">V. Board Exam Results</div>
    @if(isset($academics) && count((array)$academics) > 0)
        @foreach($academics as $semNumber => $subjects)
            <div class="font-bold text-xs text-indigo-900 mb-1">Semester {{ $semNumber }}</div>
            <table class="w-full text-xs border border-slate-300 mb-3">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="p-1.5 border border-slate-300 w-24">Subject Code</th>
                        <th class="p-1.5 border border-slate-300">Subject Name</th>
                        <th class="p-1.5 border border-slate-300 w-28 text-center">Internal Mark</th>
                        <th class="p-1.5 border border-slate-300 w-24 text-center">Grade</th>
                        <th class="p-1.5 border border-slate-300 w-24 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subj)
                    <tr>
                        <td class="p-1.5 border border-slate-300 font-mono">{{ $subj->subject_code }}</td>
                        <td class="p-1.5 border border-slate-300">{{ $subj->subject_name }}</td>
                        <td class="p-1.5 border border-slate-300 text-center font-mono">{{ $subj->total_internal_score ?? '-' }} / {{ $subj->internal_max ?? '50' }}</td>
                        <td class="p-1.5 border border-slate-300 text-center font-bold">{{ $subj->board_grade ?? '-' }}</td>
                        <td class="p-1.5 border border-slate-300 text-center font-bold">
                            @if(isset($subj->board_grade) && in_array($subj->board_grade, ['F', 'Absent']))
                                <span class="text-rose-600">Fail</span>
                            @elseif(isset($subj->board_grade))
                                <span class="text-emerald-600">Pass</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @else
        <div class="text-center text-slate-400 py-3 border border-dashed border-slate-300 rounded-lg text-xs mb-4">No board exam results recorded yet.</div>
    @endif
</div>

<div class="page-break pt-6">
    <div class="bg-indigo-950 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-lg mb-3">VI. Extracurricular Activities & Activity Points</div>
    <table class="w-full text-xs border border-slate-300 mb-6">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-1.5 border border-slate-300 w-12 text-center">Sem</th>
                <th class="p-1.5 border border-slate-300 w-36">Segment</th>
                <th class="p-1.5 border border-slate-300">Description</th>
                <th class="p-1.5 border border-slate-300 w-24 text-center">Points</th>
                <th class="p-1.5 border border-slate-300 w-24 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @if(count((array)$extracurricular) > 0)
                @foreach($extracurricular as $act)
                <tr>
                    <td class="p-1.5 border border-slate-300 text-center font-mono">{{ $act->semester ?? '-' }}</td>
                    <td class="p-1.5 border border-slate-300">{{ $act->activity_segment ?? '-' }}</td>
                    <td class="p-1.5 border border-slate-300">{{ $act->activity_name ?? '-' }}</td>
                    <td class="p-1.5 border border-slate-300 text-center font-bold font-mono">{{ $act->points_claimed ?? '-' }}</td>
                    <td class="p-1.5 border border-slate-300 text-center">{{ $act->status ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="5" class="p-3 text-center text-slate-400">No extracurricular activities recorded.</td></tr>
            @endif
        </tbody>
    </table>

    <div class="bg-indigo-950 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-lg mb-3">VII. Mentoring Meetings Log</div>
    <table class="w-full text-xs border border-slate-300 mb-4">
        <thead class="bg-slate-100">
            <tr>
                <th class="p-1.5 border border-slate-300 w-24">Date</th>
                <th class="p-1.5 border border-slate-300">Discussion Notes / Topics</th>
                <th class="p-1.5 border border-slate-300 w-44">Remarks / Action Needed</th>
                <th class="p-1.5 border border-slate-300 w-28 text-center">Logged By</th>
            </tr>
        </thead>
        <tbody>
            @if(count((array)$meetings) > 0)
                @foreach($meetings as $meeting)
                <tr>
                    <td class="p-1.5 border border-slate-300 font-mono">{{ date('d-m-Y', strtotime($meeting->date)) }}</td>
                    <td class="p-1.5 border border-slate-300">{{ $meeting->discussion_notes ?? '-' }}</td>
                    <td class="p-1.5 border border-slate-300">{{ $meeting->remarks ?? '-' }}</td>
                    <td class="p-1.5 border border-slate-300 text-center">{{ $meeting->logged_by_name ?? 'Mentor' }}</td>
                </tr>
                @endforeach
            @else
                <tr><td colspan="4" class="p-3 text-center text-slate-400">No mentoring meetings recorded yet.</td></tr>
            @endif
        </tbody>
    </table>
</div>
