<div id="tab-attainment" class="tab-content hidden space-y-6">
    <!-- Attainment Header Strip -->
    <div class="bg-white/5 border border-slate-700/60 rounded-xl p-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-white flex items-center gap-2">
                <x-ui.icon name="chart-bar" class="w-4 h-4 text-emerald-400" />
                <span>NBA Course Outcome (CO) Attainment &amp; Direct/Indirect Analysis</span>
            </h2>
            <div class="text-xs text-slate-400 mt-0.5">
                Formula: Direct Attainment (80%) [CIE 30% + ESE 70%] + Indirect Survey Attainment (20%) = Overall Attainment
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-ui.button 
                variant="secondary" 
                size="sm" 
                icon="refresh-cw"
                onclick="loadAttainmentData()"
                title="Recalculate Attainment">
                <span class="ml-1.5">Recalculate</span>
            </x-ui.button>
        </div>
    </div>

    <!-- Course Exit Survey Control Card -->
    <x-ui.card>
        <div class="space-y-3">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-white uppercase tracking-wider">Course Exit Survey</span>
                        <x-ui.badge variant="info" id="surveyStatusBadge">Indirect Metric (20%)</x-ui.badge>
                    </div>
                    <p class="text-xs text-slate-400 max-w-xl">
                        Collect student feedback on course outcomes. The survey score directly feeds the 20% indirect attainment component required for NBA compliance.
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <x-ui.button 
                        variant="secondary" 
                        size="sm" 
                        icon="link"
                        id="btnCopySurveyLink"
                        onclick="copySurveyLink()"
                        title="Copy student survey submission URL">
                        <span class="ml-1.5">Copy Link</span>
                    </x-ui.button>
                    <x-ui.button 
                        variant="primary" 
                        size="sm" 
                        icon="play"
                        id="btnOpenExitSurvey"
                        onclick="initiateExitSurvey()">
                        <span class="ml-1.5">Initiate Survey</span>
                    </x-ui.button>
                    <x-ui.button 
                        variant="danger" 
                        size="sm" 
                        icon="stop-circle"
                        id="btnCloseExitSurvey"
                        class="hidden"
                        onclick="closeExitSurvey()">
                        <span class="ml-1.5">Close Survey</span>
                    </x-ui.button>
                    <a id="btnTestSurveyLink" href="#" target="_blank" class="hidden text-xs text-blue-400 hover:underline flex items-center gap-1 font-semibold ml-2">
                        <span>Preview Survey</span>
                        <x-ui.icon name="external-link" class="w-3.5 h-3.5" />
                    </a>
                </div>
            </div>

            <!-- Survey Progress & URL Bar -->
            <div class="pt-2 border-t border-slate-700/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                <div class="flex items-center gap-2 flex-grow max-w-md">
                    <span class="text-slate-400 text-[11px] shrink-0">Student Link:</span>
                    <input type="text" id="surveyUrlInput" readonly value="Initiate survey to generate student link" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-[11px] text-slate-300 font-mono">
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-[11px] text-slate-400">
                        Responses: <strong id="surveyResponseStat" class="text-white">0 / {{ count($studentResults) }} Submitted</strong> (<span id="surveyResponsePct" class="text-emerald-400 font-semibold">0%</span>)
                    </div>
                    <div class="w-24 bg-slate-800 rounded-full h-2 overflow-hidden">
                        <div id="surveyProgressBar" class="bg-emerald-500 h-full rounded-full transition-all" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </x-ui.card>

    <!-- CO Attainment Matrix Table -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold text-white uppercase tracking-wider">Course Outcome Attainment Matrix</h3>
            <span class="text-xs text-slate-400">Target Threshold: 60%</span>
        </div>

        @php
            $cos = is_array($courseFile->parsed_cos) ? $courseFile->parsed_cos : (json_decode($courseFile->parsed_cos ?? '[]', true) ?: []);
            if (empty($cos)) {
                $cos = [
                    ['id' => 'CO1', 'description' => 'Identify, formulate and analyze complex engineering problems in the chosen domain.', 'cognitive_level' => 'Analyzing'],
                    ['id' => 'CO2', 'description' => 'Design and develop solutions, prototypes or modern hardware/software systems.', 'cognitive_level' => 'Applying'],
                    ['id' => 'CO3', 'description' => 'Utilize modern engineering tools, techniques and resources for implementation.', 'cognitive_level' => 'Applying'],
                    ['id' => 'CO4', 'description' => 'Demonstrate teamwork, project management, ethics and effective technical communication.', 'cognitive_level' => 'Applying'],
                    ['id' => 'CO5', 'description' => 'Synthesize results, prepare comprehensive technical documentation and report.', 'cognitive_level' => 'Evaluating']
                ];
            }
        @endphp

        <x-ui.table :headers="[
            'CO Tag',
            'Course Outcome Description',
            'Assessed',
            'CIE Met %',
            'CIE Level',
            'ESE Level',
            'Direct (30:70)',
            'Indirect (Survey)',
            'Overall (80:20)',
            'NBA Rating'
        ]">
            @foreach($cos as $co)
                <tr class="hover:bg-slate-800/40 transition-colors" data-co-row data-co-id="{{ $co['id'] ?? '' }}">
                    <td class="py-3 px-4 text-xs font-mono font-bold text-blue-400">{{ $co['id'] ?? 'CO' }}</td>
                    <td class="py-3 px-4 text-xs text-slate-300 max-w-md">{{ $co['description'] ?? 'Course Outcome' }}</td>
                    <td class="py-3 px-4 text-xs text-center">
                        <x-ui.badge variant="success">Yes</x-ui.badge>
                    </td>
                    <td class="py-3 px-4 text-xs font-mono text-right text-slate-200" id="cieMet-{{ $co['id'] ?? '' }}">
                        {{ $evaluatedCount > 0 ? number_format(($passedCount / max(1, $evaluatedCount)) * 100, 1) . '%' : '—' }}
                    </td>
                    <td class="py-3 px-4 text-xs font-mono text-center text-slate-200" id="cieLevel-{{ $co['id'] ?? '' }}">
                        {{ $evaluatedCount > 0 ? '3.0' : '—' }}
                    </td>
                    <td class="py-3 px-4 text-xs font-mono text-center text-slate-200" id="eseLevel-{{ $co['id'] ?? '' }}">
                        {{ $evaluatedCount > 0 ? '3.0' : '—' }}
                    </td>
                    <td class="py-3 px-4 text-xs font-mono font-semibold text-right text-emerald-400" id="directAtt-{{ $co['id'] ?? '' }}">
                        {{ $evaluatedCount > 0 ? '3.00' : '—' }}
                    </td>
                    <td class="py-3 px-4 text-xs font-mono text-right text-sky-400" id="indirectAtt-{{ $co['id'] ?? '' }}">
                        —
                    </td>
                    <td class="py-3 px-4 text-xs font-mono font-extrabold text-right text-indigo-300" id="overallAtt-{{ $co['id'] ?? '' }}">
                        {{ $evaluatedCount > 0 ? '3.00' : '—' }}
                    </td>
                    <td class="py-3 px-4 text-xs text-center">
                        <x-ui.badge variant="success">High (3.0)</x-ui.badge>
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </div>
</div>
