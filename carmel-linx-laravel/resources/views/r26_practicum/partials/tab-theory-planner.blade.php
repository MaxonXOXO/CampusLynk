            <div id="theory-subcontent-planner" class="space-y-5 hidden">
                @php
                    $theoryPlans = $lessonPlans->whereIn('mode', ['L', 'ST'])->take(45);
                    $theoryPlannedHours = 45;
                    $theoryCompletedCount = $theoryPlans->filter(function($p) { return !empty($p->actual_date); })->count();
                    $theoryRemainingHours = max(0, 45 - $theoryCompletedCount);
                    $theoryCoveragePct = round(($theoryCompletedCount / 45) * 100);
                    $theoryCoList = $theoryPlans->pluck('co_id')->filter()->unique()->values();
                @endphp

                <!-- 1. THEORY METRIC SUMMARY BAR (4-CARD GRID) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Planned Hours -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-indigo-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-slate-400 font-mono uppercase tracking-wider">Theory Target</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-slate-900 font-heading tracking-tight" id="theoryMetricPlanned">45 <span class="text-xs font-bold text-slate-500">Hrs</span></div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Planned Hours</div>
                            <div class="text-xs text-slate-500 font-medium mt-1">45 scheduled lecture periods</div>
                        </div>
                    </div>

                    <!-- Completed Hours -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-emerald-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60 font-mono">Conducted</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-emerald-700 font-heading tracking-tight" id="theoryMetricCompleted">{{ $theoryCompletedCount }} <span class="text-xs font-bold text-emerald-600/70">Hrs</span></div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Completed Hours</div>
                            <div class="text-xs text-slate-500 font-medium mt-1" id="theoryMetricCompletedSub">{{ $theoryCompletedCount }} sessions conducted</div>
                        </div>
                    </div>

                    <!-- Remaining Hours -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-amber-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60 font-mono">Pending</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-slate-900 font-heading tracking-tight" id="theoryMetricRemaining">{{ $theoryRemainingHours }} <span class="text-xs font-bold text-slate-500">Hrs</span></div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Remaining Hours</div>
                            <div class="text-xs text-slate-500 font-medium mt-1" id="theoryMetricRemainingSub">{{ $theoryRemainingHours }} sessions pending</div>
                        </div>
                    </div>

                    <!-- Syllabus Coverage -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs transition-all hover:border-blue-300">
                        <div class="flex items-center justify-between">
                            <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </span>
                            <span class="text-xs font-bold text-blue-700 font-mono" id="theoryMetricCoverageBadge">{{ $theoryCoveragePct }}%</span>
                        </div>
                        <div class="mt-3">
                            <div class="text-2xl font-black text-blue-700 font-heading tracking-tight" id="theoryMetricCoverage">{{ $theoryCoveragePct }}%</div>
                            <div class="text-xs font-bold uppercase tracking-wider text-slate-500 mt-0.5">Theory Coverage</div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden mt-2">
                                <div id="theoryMetricProgressBar" class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ $theoryCoveragePct }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. THEORY PLANNER MAIN WORKSPACE CARD -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
                    <!-- Action Toolbar Header -->
                    <div class="p-5 sm:px-6 border-b border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100 font-mono">THEORY DELIVERY PLAN</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mt-1">Theory Lesson Planner</h3>
                            <p class="text-slate-500 text-xs mt-0.5">Plan, schedule and track the 45-hour theory component (41 Lecture Hours + 4 Series Tests).</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="/r26/classroom/practicum/{{ $batchSubject->id }}/print-lesson-plan" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-all flex items-center gap-1.5 shadow-2xs no-underline cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                <span>Print Lesson Plan</span>
                            </a>

                            <button type="button" id="btnSaveTheoryPlanner" onclick="saveAllLessonPlans()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Save All 90 Hours</span>
                            </button>
                        </div>
                    </div>

                    <!-- Interactive Filter / Search Bar -->
                    <div class="border-b border-slate-100 bg-slate-50/60 p-3 sm:px-6 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <!-- Search Box -->
                            <div class="relative min-w-[200px] sm:w-64">
                                <input type="text" id="theoryPlannerSearch" oninput="filterTheoryPlannerRows()" placeholder="Search topics, COs or sessions..." class="w-full pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 shadow-2xs">
                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </span>
                            </div>

                            <!-- CO Filter -->
                            <select id="theoryPlannerCOFilter" onchange="filterTheoryPlannerRows()" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                                <option value="ALL">All Outcomes (CO)</option>
                                @foreach($theoryCoList as $co)
                                    <option value="{{ $co }}">{{ $co }}</option>
                                @endforeach
                            </select>

                            <!-- Status Filter -->
                            <select id="theoryPlannerStatusFilter" onchange="filterTheoryPlannerRows()" class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-indigo-500 shadow-2xs cursor-pointer">
                                <option value="ALL">All Statuses</option>
                                <option value="Pending">Pending Only</option>
                                <option value="Completed">Completed Only</option>
                            </select>
                        </div>

                        <!-- Counter -->
                        <span id="theoryPlannerCount" class="text-xs font-bold text-slate-500 ml-auto">Showing {{ $theoryPlans->count() }} of {{ $theoryPlans->count() }} sessions</span>
                    </div>

                    <!-- Table Container -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[960px] lp-table">
                            <thead>
                                <tr class="bg-slate-50/90 text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200 sticky top-0 z-10">
                                    <th class="p-3.5 w-16 text-center"># / Session</th>
                                    <th class="p-3.5 w-36">Pedagogy</th>
                                    <th class="p-3.5 w-36">Proposed Date</th>
                                    <th class="p-3.5 w-36">Actual Date</th>
                                    <th class="p-3.5 min-w-[280px]">Topic & Content Description</th>
                                    <th class="p-3.5 w-20 text-center">CO</th>
                                    <th class="p-3.5 w-32">Students</th>
                                    <th class="p-3.5 w-24 text-center">Hours</th>
                                    <th class="p-3.5 w-24 text-center">Status</th>
                                    <th class="p-3.5 w-36">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-normal">
                                @forelse($theoryPlans as $plan)
                                @php
                                    $isCompleted = !empty($plan->actual_date);
                                    $co = $plan->co_id ?: 'CO1';
                                    $coBadgeClass = match($co) {
                                        'CO1' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'CO2' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'CO3' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'CO4' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'CO5' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'CO6' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <tr id="lp-row-{{ $plan->id }}" data-plan-id="{{ $plan->id }}" data-co="{{ $plan->co_id }}" data-status="{{ $isCompleted ? 'Completed' : 'Pending' }}" class="theory-planner-row hover:bg-slate-50/70 transition-colors {{ $isCompleted ? 'bg-emerald-50/15' : '' }}">
                                    <!-- Session # -->
                                    <td class="p-3 font-mono font-bold text-center text-slate-900 text-sm">
                                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 inline-flex items-center justify-center font-bold text-xs border border-slate-200/80">#{{ str_pad($plan->day_no, 2, '0', STR_PAD_LEFT) }}</span>
                                    </td>

                                    <!-- Pedagogy -->
                                    <td class="p-2.5">
                                        <select id="lp-pedagogy-{{ $plan->id }}" onchange="onPedagogyChange({{ $plan->id }}, this.value); updateTheoryMetrics(); filterTheoryPlannerRows();" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-sm font-medium transition-all outline-none cursor-pointer {{ $plan->mode === 'L' ? 'text-blue-700' : ($plan->mode === 'P' ? 'text-emerald-700' : 'text-purple-700') }}">
                                            <option value="Lecture (L)" {{ ($plan->pedagogy ?? 'Lecture (L)') === 'Lecture (L)' || ($plan->mode === 'L' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Lecture (L)</option>
                                            <option value="Practical Lab (P)" {{ ($plan->pedagogy ?? '') === 'Practical Lab (P)' || ($plan->mode === 'P' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Practical Lab (P)</option>
                                            <option value="Theory Series Exam (ST)" {{ ($plan->pedagogy ?? '') === 'Theory Series Exam (ST)' || ($plan->mode === 'ST' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Theory Series Exam (ST)</option>
                                            <option value="Practical Series Exam (SP)" {{ ($plan->pedagogy ?? '') === 'Practical Series Exam (SP)' || ($plan->mode === 'SP' && !isset($plan->pedagogy)) ? 'selected' : '' }}>Practical Series Exam (SP)</option>
                                            <option value="PPT Presentation" {{ ($plan->pedagogy ?? '') === 'PPT Presentation' ? 'selected' : '' }}>PPT Presentation</option>
                                            <option value="Demonstration" {{ ($plan->pedagogy ?? '') === 'Demonstration' ? 'selected' : '' }}>Demonstration</option>
                                            <option value="Group Activity" {{ ($plan->pedagogy ?? '') === 'Group Activity' ? 'selected' : '' }}>Group Activity</option>
                                        </select>
                                    </td>

                                    <!-- Proposed Date -->
                                    <td class="p-2.5">
                                        <input type="date" id="lp-prop-{{ $plan->id }}" value="{{ $plan->proposed_date }}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-mono transition-all outline-none">
                                    </td>

                                    <!-- Actual Date -->
                                    <td class="p-2.5">
                                        <input type="date" id="lp-act-{{ $plan->id }}" value="{{ $plan->actual_date }}" onchange="onTheoryActualDateChange({{ $plan->id }}, this.value)" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-2.5 py-2 text-slate-800 text-sm font-mono transition-all outline-none">
                                    </td>

                                    <!-- Topic & Content -->
                                    <td class="p-2.5">
                                        <textarea id="lp-topic-{{ $plan->id }}" rows="2" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-3 py-2 text-slate-900 text-sm font-normal transition-all outline-none resize-y leading-snug">{{ $plan->topic_content }}</textarea>
                                    </td>

                                    <!-- CO -->
                                    <td class="p-2.5 text-center">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold border shadow-2xs {{ $coBadgeClass }}">
                                            {{ $plan->co_id ?: 'CO1' }}
                                        </span>
                                        <input type="hidden" id="lp-co-{{ $plan->id }}" value="{{ $plan->co_id ?: 'CO1' }}">
                                    </td>

                                    <!-- Students / Batch -->
                                    <td id="lp-batch-td-{{ $plan->id }}" class="p-2.5">
                                        @if(in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)))
                                            <select id="lp-batch-{{ $plan->id }}" class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 rounded-xl px-2.5 py-2 font-bold text-xs text-emerald-700 outline-none cursor-pointer">
                                                <option value="Batch A & B" {{ ($plan->sub_batch ?? 'Batch A & B') === 'Batch A & B' ? 'selected' : '' }}>Batch A & B (Combined)</option>
                                                <option value="Batch A" {{ ($plan->sub_batch ?? '') === 'Batch A' ? 'selected' : '' }}>Batch A</option>
                                                <option value="Batch B" {{ ($plan->sub_batch ?? '') === 'Batch B' ? 'selected' : '' }}>Batch B</option>
                                            </select>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold text-xs border border-slate-200/80 inline-block">
                                                All Students
                                            </span>
                                            <input type="hidden" id="lp-batch-{{ $plan->id }}" value="All Students">
                                        @endif
                                    </td>

                                    <!-- Hours -->
                                    <td id="lp-hours-td-{{ $plan->id }}" class="p-2.5 text-center font-normal">
                                        @if(in_array($plan->mode, ['P', 'SP']) || (isset($plan->pedagogy) && (stripos($plan->pedagogy, 'Practical') !== false || stripos($plan->pedagogy, 'Lab') !== false)))
                                            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/80 text-xs font-bold">3 Hours</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/80 text-xs font-bold">1 Hour</span>
                                        @endif
                                    </td>

                                    <!-- Status Indicator -->
                                    <td id="lp-status-td-{{ $plan->id }}" class="p-2.5 text-center">
                                        <span id="lp-status-pill-{{ $plan->id }}" class="px-2.5 py-1 rounded-full text-xs font-bold border shadow-2xs {{ $isCompleted ? 'bg-emerald-50 text-emerald-700 border-emerald-200/80' : 'bg-amber-50 text-amber-700 border-amber-200/80' }}">
                                            {{ $isCompleted ? 'Completed' : 'Pending' }}
                                        </span>
                                    </td>

                                    <!-- Remarks -->
                                    <td class="p-2.5">
                                        <input type="text" id="lp-remarks-{{ $plan->id }}" value="{{ $plan->remarks }}" placeholder="Status/Remarks..." class="w-full bg-slate-50/70 hover:bg-white focus:bg-white border border-slate-200/90 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 rounded-xl px-3 py-2 text-slate-800 text-sm font-normal transition-all outline-none">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="p-8 text-center text-slate-400 font-normal">No theory lecture hours scheduled yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

