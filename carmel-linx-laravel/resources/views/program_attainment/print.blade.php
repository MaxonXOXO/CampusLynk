<x-layouts.report-layout title="NBA Criterion 3 Program Attainment — {{ $classroomId }}">
    {{-- Institutional Document Header --}}
    <div class="text-center pb-4 border-b-2 border-slate-900 mb-6">
        <h1 class="text-xl font-bold uppercase tracking-wider text-slate-900">Carmel Polytechnic College, Alappuzha</h1>
        <p class="text-xs text-slate-600">Department of {{ $branch ?? 'Engineering' }}</p>
        <h2 class="text-sm font-bold uppercase mt-2 text-slate-800 underline">NBA Criterion 3 — Program Outcomes (POs) and Program Specific Outcomes (PSOs) Attainment Report</h2>
        <div class="mt-2 flex justify-center gap-6 text-xs text-slate-700 font-medium">
            <span><strong>Cohort ID:</strong> {{ $classroomId }}</span>
            <span><strong>Batch Year:</strong> {{ $classroom->batch_year ?? 'N/A' }}</span>
            <span><strong>Regulation:</strong> {{ $revision }}</span>
            <span><strong>Date Generated:</strong> {{ date('d-m-Y') }}</span>
        </div>
    </div>

    {{-- Executive Summary Table --}}
    <div class="mb-6">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-2">1. Consolidated PO & PSO Attainment Summary (80% Direct + 20% Indirect)</h3>
        <table class="w-full border-collapse border border-slate-400 text-xs">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold">
                    <th class="border border-slate-400 p-2 text-left">Outcome</th>
                    <th class="border border-slate-400 p-2 text-left">Outcome Title</th>
                    <th class="border border-slate-400 p-2 text-center">Target</th>
                    <th class="border border-slate-400 p-2 text-center">Direct (80%)</th>
                    <th class="border border-slate-400 p-2 text-center">Indirect (20%)</th>
                    <th class="border border-slate-400 p-2 text-center">Final Attained</th>
                    <th class="border border-slate-400 p-2 text-center">Gap</th>
                    <th class="border border-slate-400 p-2 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
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
                    <tr>
                        <td class="border border-slate-400 p-1.5 font-bold">{{ $key }}</td>
                        <td class="border border-slate-400 p-1.5">{{ $title }}</td>
                        <td class="border border-slate-400 p-1.5 text-center">{{ number_format($target, 2) }}</td>
                        <td class="border border-slate-400 p-1.5 text-center">{{ number_format($direct, 2) }}</td>
                        <td class="border border-slate-400 p-1.5 text-center">{{ number_format($indirect, 2) }}</td>
                        <td class="border border-slate-400 p-1.5 text-center font-bold">{{ number_format($achieved, 2) }}</td>
                        <td class="border border-slate-400 p-1.5 text-center font-bold {{ $isMet ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $gap >= 0 ? '+' . number_format($gap, 2) : number_format($gap, 2) }}
                        </td>
                        <td class="border border-slate-400 p-1.5 text-center font-bold {{ $isMet ? 'text-emerald-700' : 'text-rose-700' }}">
                            {{ $isMet ? 'MET' : 'NOT MET' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Course Matrix Breakdown --}}
    <div class="mb-6 page-break-before">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-2">2. Course-Level Direct PO/PSO Contribution Matrix</h3>
        <table class="w-full border-collapse border border-slate-400 text-[10px]">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold">
                    <th class="border border-slate-400 p-1 text-left">Course Code & Name</th>
                    <th class="border border-slate-400 p-1 text-center">Sem</th>
                    @foreach($allPoKeys as $poKey)
                        <th class="border border-slate-400 p-1 text-center font-mono">{{ $poKey }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($coursesMatrix as $item)
                    <tr>
                        <td class="border border-slate-400 p-1 font-medium">
                            {{ $item['subject']->subject_code }} — {{ $item['subject']->subject_name }}
                        </td>
                        <td class="border border-slate-400 p-1 text-center">{{ $item['subject']->semester ?? '-' }}</td>
                        @foreach($allPoKeys as $poKey)
                            @php
                                $val = $item['po_contributions'][$poKey]['attainment'] ?? null;
                            @endphp
                            <td class="border border-slate-400 p-1 text-center font-mono">
                                {{ $val !== null ? number_format($val, 2) : '-' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-slate-100 font-bold text-slate-900">
                    <td colspan="2" class="border border-slate-400 p-1 text-right uppercase">Direct Average:</td>
                    @foreach($allPoKeys as $poKey)
                        <td class="border border-slate-400 p-1 text-center font-mono">
                            {{ number_format($directPo[$poKey] ?? 0, 2) }}
                        </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Statutory Signatures Block --}}
    <div class="mt-12 pt-6 grid grid-cols-3 gap-8 text-center text-xs border-t border-slate-300">
        <div>
            <div class="h-12"></div>
            <p class="font-bold uppercase text-slate-800">NBA Coordinator</p>
            <p class="text-[10px] text-slate-500">Signature & Date</p>
        </div>
        <div>
            <div class="h-12"></div>
            <p class="font-bold uppercase text-slate-800">Head of Department</p>
            <p class="text-[10px] text-slate-500">Department of {{ $branch ?? 'Engineering' }}</p>
        </div>
        <div>
            <div class="h-12"></div>
            <p class="font-bold uppercase text-slate-800">Principal</p>
            <p class="text-[10px] text-slate-500">Carmel Polytechnic College</p>
        </div>
    </div>
</x-layouts.report-layout>
