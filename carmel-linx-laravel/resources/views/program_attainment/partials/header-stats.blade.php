{{-- Program Attainment Header & Stats --}}
<div class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-indigo-400">
                <span>NBA Criterion 3</span>
                <span>•</span>
                <span>{{ $revision }}</span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Program Attainment & Outcome Analytics</h1>
            <p class="text-xs text-slate-400">
                Cohort: <span class="text-white font-mono font-medium">{{ $classroomId }}</span> | Branch: <span class="text-white font-medium">{{ $branch }}</span> (Batch {{ $classroom->batch_year ?? 'N/A' }})
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/hod/program-attainment/{{ $classroomId }}/print" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-slate-800/80 px-3.5 py-2 text-xs font-semibold text-slate-200 hover:bg-white/10 hover:text-white transition">
                <span class="material-symbols-rounded text-sm">print</span>
                <span>Print Statutory Report</span>
            </a>
            <button type="button" onclick="saveProgramAttainmentConfig()" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 transition">
                <span class="material-symbols-rounded text-sm">save</span>
                <span>Save Targets & Surveys</span>
            </button>
        </div>
    </div>

    {{-- Stats Cards Grid --}}
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 backdrop-blur">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium uppercase tracking-wider text-slate-400">Total Courses</span>
                <span class="material-symbols-rounded text-slate-500 text-lg">menu_book</span>
            </div>
            <p class="mt-2 text-2xl font-black text-white">{{ count($subjects) }}</p>
            <span class="text-[10px] text-slate-500">Evaluated in cohort</span>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 backdrop-blur">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium uppercase tracking-wider text-slate-400">Direct PO Avg</span>
                <span class="material-symbols-rounded text-indigo-400 text-lg">trending_up</span>
            </div>
            @php
                $avgDirect = count($directPo) ? round(array_sum($directPo) / count($directPo), 2) : 0;
            @endphp
            <p class="mt-2 text-2xl font-black text-indigo-400 font-mono">{{ number_format($avgDirect, 2) }}</p>
            <span class="text-[10px] text-slate-500">80% Contribution weight</span>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 backdrop-blur">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium uppercase tracking-wider text-slate-400">Indirect Survey Avg</span>
                <span class="material-symbols-rounded text-teal-400 text-lg">fact_check</span>
            </div>
            @php
                $avgIndirect = count($indirectSurveys) ? round(array_sum($indirectSurveys) / count($indirectSurveys), 2) : 2.50;
            @endphp
            <p class="mt-2 text-2xl font-black text-teal-400 font-mono">{{ number_format($avgIndirect, 2) }}</p>
            <span class="text-[10px] text-slate-500">20% Contribution weight</span>
        </div>

        <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 backdrop-blur">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium uppercase tracking-wider text-slate-400">Compliance Met</span>
                <span class="material-symbols-rounded text-emerald-400 text-lg">verified</span>
            </div>
            @php
                $metCount = count(array_filter($gapAnalysis, fn($g) => $g['is_met']));
                $totalOutcomes = count($gapAnalysis);
            @endphp
            <p class="mt-2 text-2xl font-black text-emerald-400 font-mono">{{ $metCount }} / {{ $totalOutcomes }}</p>
            <span class="text-[10px] text-slate-500">Target thresholds achieved</span>
        </div>
    </div>
</div>
