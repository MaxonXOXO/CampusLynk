{{-- Tab: Executive Summary & Action Plan --}}
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-white">Consolidated NBA Criterion 3 Summary Table</h3>
            <p class="text-xs text-slate-400">Final PO & PSO attainment record comparing target vs achieved values.</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-900/60 backdrop-blur">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-800/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                <tr>
                    <th class="p-3">Outcome</th>
                    <th class="p-3">Title</th>
                    <th class="p-3 text-center">Target</th>
                    <th class="p-3 text-center">Direct (80%)</th>
                    <th class="p-3 text-center">Indirect (20%)</th>
                    <th class="p-3 text-center">Final Attainment</th>
                    <th class="p-3 text-center">Gap</th>
                    <th class="p-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-slate-300 font-mono">
                @foreach($allPoKeys as $key)
                    @php
                        $target = $gapAnalysis[$key]['target'] ?? 2.0;
                        $achieved = $gapAnalysis[$key]['achieved'] ?? 0.0;
                        $gap = $gapAnalysis[$key]['gap'] ?? 0.0;
                        $isMet = $gapAnalysis[$key]['is_met'] ?? false;
                        $direct = $finalPo[$key]['direct'] ?? 0.0;
                        $indirect = $finalPo[$key]['indirect'] ?? 0.0;
                        $title = $poList[$key]['title'] ?? ($psoList[$key]['title'] ?? '');
                    @endphp
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="p-3 font-bold {{ str_starts_with($key, 'PSO') ? 'text-teal-400' : 'text-indigo-400' }}">{{ $key }}</td>
                        <td class="p-3 font-sans text-slate-300">{{ $title }}</td>
                        <td class="p-3 text-center text-slate-400">{{ number_format($target, 2) }}</td>
                        <td class="p-3 text-center text-slate-300">{{ number_format($direct, 2) }}</td>
                        <td class="p-3 text-center text-slate-300">{{ number_format($indirect, 2) }}</td>
                        <td class="p-3 text-center font-bold text-white">{{ number_format($achieved, 2) }}</td>
                        <td class="p-3 text-center font-bold {{ $isMet ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $gap >= 0 ? '+' . number_format($gap, 2) : number_format($gap, 2) }}
                        </td>
                        <td class="p-3 text-center">
                            @if($isMet)
                                <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-[10px] font-sans font-bold text-emerald-400 border border-emerald-500/20">MET</span>
                            @else
                                <span class="rounded-full bg-rose-500/10 px-2 py-0.5 text-[10px] font-sans font-bold text-rose-400 border border-rose-500/20">NOT MET</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Action Plan for Continuous Improvement --}}
    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-5 backdrop-blur space-y-3">
        <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-indigo-400 text-lg">edit_note</span>
            <h4 class="text-sm font-bold text-white">Action Plan for Non-Attained Outcomes & Continuous Improvement</h4>
        </div>
        <p class="text-xs text-slate-400">Specify corrective academic interventions, pedagogical enhancements, or remedial workshops for outcomes that did not achieve target thresholds.</p>
        <textarea id="program-action-plans" rows="4" placeholder="Enter continuous improvement action plan for NBA accreditation compliance..." class="w-full rounded-xl border border-white/10 bg-slate-950/60 p-3 text-xs text-slate-200 placeholder-slate-600 focus:border-indigo-500 focus:outline-none font-sans">{{ is_array($programRecord->action_plans) ? json_encode($programRecord->action_plans, JSON_PRETTY_PRINT) : ($programRecord->action_plans ?? '') }}</textarea>
    </div>
</div>
