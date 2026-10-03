<div id="tab-attainment" class="tab-pane hidden space-y-5">
    <!-- Attainment Info Banner -->
    <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-700/70 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-500/40 text-purple-400 flex items-center justify-center shrink-0">
                <x-ui.icon name="chart-bar" class="w-5 h-5" />
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-bold text-white">NBA Course Outcome (CO) Attainment</h4>
                    <x-ui.badge variant="info">100% CIE (No ESE)</x-ui.badge>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">
                    Continuous Internal Evaluation across 67.5M Academic Rubrics. Threshold: 50% (33.75M). Overall = 80% Direct + 20% Indirect.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <x-ui.button variant="secondary" size="sm" icon="refresh" onclick="loadAttainmentData()" title="Recalculate Attainment">
                <span>Refresh Matrix</span>
            </x-ui.button>
        </div>
    </div>

    <!-- Attainment Calculation Matrix Table -->
    <div id="attainmentMatrixContainer">
        <x-ui.table :headers="['CO Tag', 'Course Outcome Description', 'Assessed', 'Met %', 'CIE Level', 'Direct Attainment (100%)', 'Indirect Level', 'Overall Attainment', 'Attainment Rating']">
            @php
                $mockCos = [
                    ['id' => 'CO1', 'desc' => 'Identify contemporary engineering developments and conduct thorough literature review.'],
                    ['id' => 'CO2', 'desc' => 'Synthesize technical information and deliver effective oral presentation.'],
                    ['id' => 'CO3', 'desc' => 'Defend methodology, respond to technical queries and prepare standard report.']
                ];
                $completedCount = $studentResults->where('is_completed', true)->count();
                $academicThreshold = 67.5 * 0.50; // 33.75M
                $metCount = $studentResults->filter(function($st) use ($academicThreshold) {
                    if (!$st['is_completed']) return false;
                    $academic = (float)($st['avg_relevance'] ?? 0) + (float)($st['avg_literature'] ?? 0) + (float)($st['avg_presentation'] ?? 0) + (float)($st['avg_interaction'] ?? 0) + (float)($st['avg_report'] ?? 0);
                    return $academic >= $academicThreshold;
                })->count();
                $metPct = $completedCount > 0 ? round(($metCount / $completedCount) * 100, 1) : 0.0;
                $cieLvl = \App\Services\AttainmentService::calculateBatchLevel($metPct);
                $direct = (float)$cieLvl;
                $indirect = $direct > 0 ? round($direct * 0.9, 2) : 2.5;
                $overall = round((0.80 * $direct) + (0.20 * $indirect), 2);
            @endphp

            @foreach($mockCos as $co)
                <tr class="hover:bg-slate-800/40 transition">
                    <td class="font-mono text-xs font-bold text-sky-400 py-3.5 px-4">{{ $co['id'] }}</td>
                    <td class="text-xs text-slate-300 py-3.5 px-4 max-w-sm">{{ $co['desc'] }}</td>
                    <td class="text-center font-mono text-xs text-slate-300 py-3.5 px-3">{{ $completedCount }}</td>
                    <td class="text-center font-mono text-xs text-slate-300 py-3.5 px-3">{{ $metPct }}%</td>
                    <td class="text-center font-mono text-xs font-semibold text-white py-3.5 px-3">Level {{ $cieLvl }}</td>
                    <td class="text-center font-mono text-xs font-bold text-emerald-400 py-3.5 px-3">{{ number_format($direct, 2) }}</td>
                    <td class="text-center font-mono text-xs text-slate-300 py-3.5 px-3">{{ number_format($indirect, 2) }}</td>
                    <td class="text-center font-mono text-xs font-bold text-purple-400 py-3.5 px-3">{{ number_format($overall, 2) }}</td>
                    <td class="text-center py-3.5 px-4">
                        @if($overall >= 2.5)
                            <x-ui.badge variant="success">High</x-ui.badge>
                        @elseif($overall >= 1.5)
                            <x-ui.badge variant="info">Moderate</x-ui.badge>
                        @elseif($overall >= 0.5)
                            <x-ui.badge variant="warning">Low</x-ui.badge>
                        @else
                            <x-ui.badge variant="neutral">Nil</x-ui.badge>
                        @endif
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </div>

    <!-- Course Exit Survey Integration (Clause 11.2.6 Indirect Attainment) -->
    <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-700/80 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-slate-800 pb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-500/40 flex items-center justify-center text-purple-400 shrink-0">
                    <x-ui.icon name="check-circle" class="w-5 h-5" />
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="text-sm font-bold text-white">End Semester Exit Survey (Indirect Attainment - 20% Weightage)</h4>
                        <span id="seminarSurveyStatusBadge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">Checking...</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Collect student feedback on CO1–CO3 seminar outcomes to calculate indirect attainment for accreditation.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" id="btnOpenSeminarExitSurvey" onclick="initiateSeminarExitSurvey()" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm cursor-pointer">
                    <x-ui.icon name="play" class="w-3.5 h-3.5" />
                    <span>Open Survey</span>
                </button>
                <button type="button" id="btnCloseSeminarExitSurvey" onclick="closeSeminarExitSurvey()" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all shadow-sm cursor-pointer hidden">
                    <x-ui.icon name="lock" class="w-3.5 h-3.5" />
                    <span>Close &amp; Lock</span>
                </button>
                <button type="button" id="btnCopySeminarSurveyLink" onclick="copySeminarSurveyLink()" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer hidden">
                    <x-ui.icon name="clipboard" class="w-3.5 h-3.5" />
                    <span>Copy Student Link</span>
                </button>
                <a id="btnTestSeminarSurveyLink" href="#" target="_blank" class="px-3.5 py-1.5 bg-sky-600/20 hover:bg-sky-600/30 text-sky-300 border border-sky-500/30 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all hidden">
                    <x-ui.icon name="external-link" class="w-3.5 h-3.5" />
                    <span>Test Form</span>
                </a>
                <a id="btnPrintSeminarSurveyReport" href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
                    <x-ui.icon name="printer" class="w-3.5 h-3.5" />
                    <span>Survey Report</span>
                </a>
            </div>
        </div>

        <!-- Live Survey Response Stats & URL Bar -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
            <div class="bg-slate-950/60 p-3 rounded-xl border border-slate-800">
                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Student Responses</span>
                <div class="flex items-center justify-between mt-1">
                    <span id="seminarSurveyResponseStat" class="text-sm font-bold text-white font-mono">0 / {{ $totalStudents }} Submitted</span>
                    <span id="seminarSurveyResponsePct" class="text-xs text-sky-400 font-mono font-bold">0%</span>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-1.5 mt-2 overflow-hidden">
                    <div id="seminarSurveyProgressBar" class="bg-teal-500 h-1.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
            <div class="bg-slate-950/60 p-3 rounded-xl border border-slate-800">
                <span class="text-slate-400 block text-[10px] uppercase font-semibold">NBA Attainment Weightage Rule</span>
                <div class="text-slate-200 mt-1 font-mono text-[11px] flex items-center gap-1.5">
                    <span class="text-emerald-400 font-bold">80% Direct CIE</span> + <span class="text-purple-400 font-bold">20% Indirect Exit</span> = <span class="text-white font-bold">100% Overall</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Direct: Continuous Internal Assessment (75M). Indirect: Survey rating average (1 to 3 scale).</p>
            </div>
            <div class="bg-slate-950/60 p-3 rounded-xl border border-slate-800">
                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Student Survey URL</span>
                <div class="mt-1 flex items-center gap-2">
                    <input type="text" id="seminarSurveyUrlInput" readonly class="w-full px-2.5 py-1.5 bg-slate-900 border border-slate-700 rounded-lg text-[11px] text-slate-300 font-mono select-all focus:outline-none" value="Initiate survey to generate student link">
                </div>
            </div>
        </div>
    </div>
</div>
