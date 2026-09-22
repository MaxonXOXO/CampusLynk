{{-- Tab: Direct Attainment Across All Cohort Courses --}}
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-white">Course-Level PO/PSO Contributions Matrix</h3>
            <p class="text-xs text-slate-400">Direct attainment calculated from continuous assessment and end semester grades weighted by CO-PO articulation matrices.</p>
        </div>
        <span class="rounded-lg bg-indigo-500/10 px-2.5 py-1 text-[11px] font-semibold text-indigo-400 border border-indigo-500/20">
            Formula: PO_k = Σ(CO_i × W_ik) / Σ(W_ik)
        </span>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-white/10 bg-slate-900/60 backdrop-blur">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-800/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                <tr>
                    <th class="p-3">Course</th>
                    <th class="p-3">Sem</th>
                    <th class="p-3">CO1</th>
                    <th class="p-3">CO2</th>
                    <th class="p-3">CO3</th>
                    <th class="p-3">CO4</th>
                    @foreach($allPoKeys as $poKey)
                        <th class="p-2 text-center font-mono {{ str_starts_with($poKey, 'PSO') ? 'text-teal-400' : 'text-indigo-400' }}">{{ $poKey }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-slate-300">
                @forelse($coursesMatrix as $item)
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="p-3 font-medium text-white">
                            <span class="block">{{ $item['subject']->subject_name }}</span>
                            <span class="text-[10px] text-slate-500 font-mono">{{ $item['subject']->subject_code }}</span>
                        </td>
                        <td class="p-3 font-mono text-slate-400">{{ $item['subject']->semester ?? '-' }}</td>
                        <td class="p-3 font-mono text-slate-300">{{ number_format($item['co_attainments']['CO1'] ?? 0, 2) }}</td>
                        <td class="p-3 font-mono text-slate-300">{{ number_format($item['co_attainments']['CO2'] ?? 0, 2) }}</td>
                        <td class="p-3 font-mono text-slate-300">{{ number_format($item['co_attainments']['CO3'] ?? 0, 2) }}</td>
                        <td class="p-3 font-mono text-slate-300">{{ number_format($item['co_attainments']['CO4'] ?? 0, 2) }}</td>
                        @foreach($allPoKeys as $poKey)
                            @php
                                $val = $item['po_contributions'][$poKey]['attainment'] ?? null;
                            @endphp
                            <td class="p-2 text-center font-mono">
                                @if($val !== null)
                                    <span class="rounded px-1.5 py-0.5 text-[11px] {{ $val >= 2.0 ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-800 text-slate-300' }}">
                                        {{ number_format($val, 2) }}
                                    </span>
                                @else
                                    <span class="text-slate-600">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 6 + count($allPoKeys) }}" class="p-6 text-center text-slate-500">
                            No course records found for this cohort.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="border-t border-white/10 bg-slate-950/60 font-semibold text-white">
                <tr>
                    <td colspan="6" class="p-3 text-right uppercase tracking-wider text-xs text-indigo-400">
                        Direct PO/PSO Average (80% Weight):
                    </td>
                    @foreach($allPoKeys as $poKey)
                        <td class="p-2 text-center font-mono text-xs {{ str_starts_with($poKey, 'PSO') ? 'text-teal-400' : 'text-indigo-300' }}">
                            {{ number_format($directPo[$poKey] ?? 0, 2) }}
                        </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
</div>
