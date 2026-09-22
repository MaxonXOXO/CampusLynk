            <div id="lab-subcontent-roster" class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold border border-emerald-200/80">
                            <span class="material-symbols-rounded text-base">science</span>
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Practical Experiments Roster (3-Hour Session Blocks)</h3>
                            <p class="text-slate-500 text-xs mt-0.5">All practical topics structured into 3-hour lab sessions as per Revision 2026 guidelines.</p>
                        </div>
                    </div>
                    <button onclick="printSubtabReport('Practical Experiments Roster Report', 'lab-subcontent-roster')" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all no-print flex items-center gap-1.5 shadow-2xs cursor-pointer">
                        <span class="material-symbols-rounded text-sm">print</span>
                        <span>Print Report</span>
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-slate-700 font-bold text-xs uppercase">
                                <th class="p-3 pl-4 w-32">Session Code</th>
                                <th class="p-3">Experiment Title</th>
                                <th class="p-3 w-32">Mapped CO</th>
                                <th class="p-3 w-44">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse(($practicumCourseFile->parsed_experiments ?? []) as $exp)
                            <tr class="hover:bg-slate-50/80 transition-all">
                                <td class="p-3 pl-4 font-bold font-mono text-emerald-700">
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 border border-emerald-200 text-xs">{{ $exp['experiment_no'] }}</span>
                                </td>
                                <td class="p-3 text-slate-900 font-medium leading-snug">{{ $exp['title'] }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 font-semibold border border-purple-200 text-xs font-mono">{{ $exp['co_id'] }}</span>
                                </td>
                                <td class="p-3 text-slate-600 font-medium text-xs">
                                    <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-700 rounded-md font-semibold">{{ $exp['hours'] ?? 3 }} Hours (1 Session)</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-slate-500 text-sm italic">No lab experiments extracted yet.</td>
                            </tr>
                            @endforelse
                <!-- Subtab 2: Lab Planner View -->
