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

    <!-- Course Exit Survey Integration -->
    <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-700/70 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <h5 class="text-xs font-bold text-white uppercase tracking-wider">Indirect Attainment — Course Exit Survey</h5>
            <p class="text-xs text-slate-400 mt-0.5">Collect student feedback regarding seminar learning outcomes to compute indirect attainment ratings.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/classroom/{{ $batchSubject->id }}/course-exit/report" target="_blank">
                <x-ui.button variant="secondary" size="sm" icon="file-text">
                    <span>Survey Report</span>
                </x-ui.button>
            </a>
        </div>
    </div>
</div>
