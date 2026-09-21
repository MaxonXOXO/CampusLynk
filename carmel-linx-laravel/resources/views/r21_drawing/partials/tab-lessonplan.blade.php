<div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-5">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <x-ui.icon name="calendar" class="w-5 h-5 text-blue-600" />
                <span>Course Delivery &amp; Drawing Hall Lesson Plan (60 Hours)</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Session-wise distribution of drawing hall exercises and theoretical demonstrations across 4 curriculum modules.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/r21/classroom/drawing/{{ $batchSubject->id }}/print/lesson-plan" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors shadow-2xs no-underline">
                <x-ui.icon name="printer" class="w-4 h-4 text-slate-500" />
                <span>Print Lesson Plan</span>
            </a>
        </div>
    </div>

    <!-- Lesson Plan Table -->
    <div class="overflow-x-auto border border-slate-200 rounded-xl shadow-2xs">
        <table class="w-full text-xs text-left text-slate-700 divide-y divide-slate-200" id="r21LessonPlanTable">
            <thead class="bg-slate-50 text-slate-600 uppercase font-semibold text-[11px]">
                <tr>
                    <th scope="col" class="py-3 px-3 w-12 text-center">Day</th>
                    <th scope="col" class="py-3 px-3 w-28">Planned Date</th>
                    <th scope="col" class="py-3 px-3 w-16 text-center">Hours</th>
                    <th scope="col" class="py-3 px-3 min-w-[240px]">Topics / Exercises Planned</th>
                    <th scope="col" class="py-3 px-3 w-20 text-center">Module</th>
                    <th scope="col" class="py-3 px-3 w-16 text-center">CO</th>
                    <th scope="col" class="py-3 px-3 w-32">Pedagogy</th>
                    <th scope="col" class="py-3 px-3 w-24 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($lessonPlans as $plan)
                    @php
                        $mod = $plan->module ?? '1';
                        $co = $plan->co_mapping ?? $plan->co_id ?? 'CO1';
                        $status = $plan->status ?? 'Completed';
                        $statusClass = $status === 'Completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                            ($status === 'In Progress' ? 'bg-blue-50 text-blue-700 border-blue-200' :
                            'bg-slate-50 text-slate-600 border-slate-200');
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-2.5 px-3 text-center font-bold text-slate-800">{{ $plan->day_no }}</td>
                        <td class="py-2.5 px-3 font-mono text-slate-600">
                            {{ $plan->planned_date ? \Carbon\Carbon::parse($plan->planned_date)->format('d-m-Y') : 'Day ' . $plan->day_no }}
                        </td>
                        <td class="py-2.5 px-3 text-center font-semibold text-slate-700">{{ $plan->hours ?? 3 }}</td>
                        <td class="py-2.5 px-3 font-medium text-slate-900">{{ $plan->topics ?? $plan->topic_name }}</td>
                        <td class="py-2.5 px-3 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-md font-semibold text-[10px] bg-slate-100 text-slate-700 border border-slate-200">
                                Mod {{ $mod }}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-md font-bold text-[10px] bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $co }}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-slate-600">{{ $plan->pedagogy ?? 'Demonstration & Practice' }}</td>
                        <td class="py-2.5 px-3 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-full font-bold text-[10px] border {{ $statusClass }}">
                                {{ $status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">
                            <x-ui.icon name="calendar" class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                            <p>No lesson plan entries generated yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
