                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    
                    <!-- Left 2 Cols: Theory Modules -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                                        <span class="material-symbols-rounded text-base">collections_bookmark</span>
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-base">Theory Modules (45 Hours)</h3>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">
                                    {{ count($practicumCourseFile->parsed_modules ?? []) }} Modules
                                </span>
                            </div>

                            <div class="space-y-3">
                                @forelse(($practicumCourseFile->parsed_modules ?? []) as $mod)
                                <div class="bg-slate-50/60 border border-slate-200/80 rounded-xl p-4 space-y-2 hover:border-blue-300 transition-all">
                                    <div class="flex items-center justify-between gap-2 border-b border-slate-200/60 pb-2">
                                        <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold">Module {{ $mod['module_id'] }}</span>
                                            <span>{{ $mod['title'] ?? 'Unit ' . $mod['module_id'] }}</span>
                                        </h4>
                                        <span class="px-2.5 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 font-bold text-xs font-mono shadow-2xs">
                                            {{ $mod['hours'] ?? 15 }} Lecture Hours
                                        </span>
                                    </div>
                                    <p class="text-sm font-normal text-slate-700 leading-relaxed whitespace-pre-line pt-1">{{ $mod['content'] }}</p>
                                </div>
                                @empty
                                <div class="text-center py-6 text-slate-500 text-sm italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    No theory modules extracted yet.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Right Col: Lab Experiments Summary -->
                    <div class="space-y-4">
                        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold border border-emerald-200/80">
                                        <span class="material-symbols-rounded text-base">science</span>
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-base">Lab Experiments (45h)</h3>
                                </div>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200">
                                    45 P Hours
                                </span>
                            </div>

                            <div class="space-y-2.5 max-h-[560px] overflow-y-auto pr-1">
                                @forelse(($practicumCourseFile->parsed_experiments ?? []) as $exp)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 hover:border-emerald-300 transition-all">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <span class="font-bold font-mono text-emerald-700 text-xs px-2 py-0.5 rounded bg-emerald-50 border border-emerald-200">{{ $exp['experiment_no'] }}</span>
                                        <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 text-xs font-semibold border border-purple-200">{{ $exp['co_id'] }}</span>
                                    </div>
                                    <p class="text-slate-800 text-sm font-medium leading-snug">{{ $exp['title'] }}</p>
                                    <div class="text-slate-500 text-xs mt-1.5 font-semibold flex items-center gap-1">
                                        <span class="material-symbols-rounded text-xs text-slate-400">schedule</span>
                                        <span>{{ $exp['hours'] ?? 3 }} Hours Session</span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-6 text-slate-500 text-sm italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    No experiments configured yet.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CO-PO Articulation Matrix Table -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center text-sm font-bold border border-indigo-200/80">
                                <span class="material-symbols-rounded text-base">grid_on</span>
                            </span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Course Articulation Matrix (CO-PO Mapping)</h3>
                                <p class="text-xs text-slate-500">Mapping strengths: 3 = High, 2 = Medium, 1 = Low</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="printSubtabReport('Theory Modules & CO-PO Matrix Report', 'theory-subcontent-overview')" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs transition-all flex items-center gap-1.5 shadow-2xs no-print cursor-pointer">
                                <span class="material-symbols-rounded text-sm">print</span>
                                <span>Print Matrix</span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-center border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold text-xs uppercase">
                                    <th class="p-3 text-left w-24 pl-4">CO</th>
                                    <th class="p-3 text-left">Course Outcome Description</th>
                                    @for($p = 1; $p <= 11; $p++)
                                    <th class="p-2.5 w-12 font-mono">PO{{ $p }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse(($practicumCourseFile->parsed_cos ?? []) as $co)
                                <tr class="hover:bg-slate-50/80 transition-all">
                                    <td class="p-3 text-left font-bold text-blue-700 pl-4 font-mono">{{ $co['id'] }}</td>
                                    <td class="p-3 text-left text-slate-800 font-medium leading-relaxed">{{ $co['description'] }}</td>
                                    @for($p = 1; $p <= 11; $p++)
                                        @php
                                            $val = $mappings[$co['id']]['PO' . $p] ?? '-';
                                            $cellClass = 'text-slate-400 font-normal';
                                            if ($val == '3') $cellClass = 'font-bold text-emerald-700 bg-emerald-50/60';
                                            elseif ($val == '2') $cellClass = 'font-bold text-blue-700 bg-blue-50/60';
                                            elseif ($val == '1') $cellClass = 'font-semibold text-slate-700 bg-slate-50';
                                        @endphp
                                        <td class="p-2.5 font-mono {{ $cellClass }}">
                                            {{ $val }}
                                        </td>
                                    @endfor
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="text-center py-6 text-slate-500 text-sm italic">No outcomes extracted yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

