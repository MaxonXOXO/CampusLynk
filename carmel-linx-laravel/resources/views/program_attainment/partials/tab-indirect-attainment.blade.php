{{-- Tab: Indirect Attainment (Surveys & Stakeholder Feedback) --}}
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-white">Indirect Survey Contributions (20% Weight)</h3>
            <p class="text-xs text-slate-400">Aggregated scores from Graduate Exit Surveys, Alumni Surveys, and Employer Stakeholder Feedback.</p>
        </div>
        <span class="rounded-lg bg-teal-500/10 px-2.5 py-1 text-[11px] font-semibold text-teal-400 border border-teal-500/20">
            Scale: 1.0 (Low) to 3.0 (High)
        </span>
    </div>

    <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-5 backdrop-blur space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($allPoKeys as $poKey)
                <div class="rounded-xl border border-white/5 bg-slate-950/40 p-3">
                    <div class="flex items-center justify-between">
                        <label for="indirect_{{ $poKey }}" class="text-xs font-bold {{ str_starts_with($poKey, 'PSO') ? 'text-teal-400' : 'text-indigo-400' }}">
                            {{ $poKey }}
                        </label>
                        <span class="text-[10px] text-slate-500 truncate max-w-[120px]">
                            {{ $poList[$poKey]['title'] ?? ($psoList[$poKey]['title'] ?? '') }}
                        </span>
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <input type="number" step="0.01" min="1.0" max="3.0" id="indirect_{{ $poKey }}" name="indirect_surveys[{{ $poKey }}]" value="{{ number_format($indirectSurveys[$poKey] ?? 2.50, 2) }}" class="indirect-survey-input w-full rounded-lg border border-white/10 bg-slate-900 px-2.5 py-1.5 text-xs font-mono text-white focus:border-teal-500 focus:outline-none">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
