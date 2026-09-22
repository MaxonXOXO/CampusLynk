{{-- Tab: Program Specific Outcomes (PSO1 to PSO3) Attainment Analysis --}}
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-white">Program Specific Outcomes (PSO1 – PSO3) Evaluation</h3>
            <p class="text-xs text-slate-400">Domain-specific competencies defined for {{ $branch }} department.</p>
        </div>
    </div>

    <div class="space-y-3">
        @foreach($psoList as $key => $pso)
            @php
                $target = $gapAnalysis[$key]['target'] ?? 2.0;
                $achieved = $gapAnalysis[$key]['achieved'] ?? 0.0;
                $gap = $gapAnalysis[$key]['gap'] ?? 0.0;
                $isMet = $gapAnalysis[$key]['is_met'] ?? false;
                $direct = $finalPo[$key]['direct'] ?? 0.0;
                $indirect = $finalPo[$key]['indirect'] ?? 0.0;
            @endphp
            <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 backdrop-blur hover:border-white/20 transition">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div class="space-y-1 max-w-xl">
                        <div class="flex items-center gap-2">
                            <span class="rounded-lg bg-teal-500/20 px-2 py-0.5 text-xs font-bold text-teal-400 font-mono">{{ $key }}</span>
                            <h4 class="text-xs font-bold text-white">{{ $pso['title'] }}</h4>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">{{ $pso['desc'] }}</p>
                    </div>

                    <div class="flex items-center gap-4 bg-slate-950/40 rounded-xl p-3 border border-white/5">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Target</span>
                            <input type="number" step="0.1" min="1.0" max="3.0" name="po_targets[{{ $key }}]" value="{{ number_format($target, 1) }}" class="po-target-input w-16 rounded border border-white/10 bg-slate-900 px-1.5 py-0.5 text-xs font-mono text-white text-center focus:border-teal-500 focus:outline-none">
                        </div>
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Direct (80%)</span>
                            <span class="font-mono text-xs text-slate-300 font-semibold">{{ number_format($direct, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Indirect (20%)</span>
                            <span class="font-mono text-xs text-slate-300 font-semibold">{{ number_format($indirect, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Achieved</span>
                            <span class="font-mono text-xs font-bold text-white">{{ number_format($achieved, 2) }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-slate-500 block">Gap</span>
                            <span class="font-mono text-xs font-bold {{ $isMet ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $gap >= 0 ? '+' . number_format($gap, 2) : number_format($gap, 2) }}
                            </span>
                        </div>
                        <div>
                            @if($isMet)
                                <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-[10px] font-bold text-emerald-400 border border-emerald-500/20">MET</span>
                            @else
                                <span class="rounded-full bg-rose-500/10 px-2 py-0.5 text-[10px] font-bold text-rose-400 border border-rose-500/20">NOT MET</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
