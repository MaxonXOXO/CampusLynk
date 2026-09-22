{{-- Tab: Lab Batch Roster (Batch A & Batch B) --}}
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h3 class="text-sm font-bold text-white">Laboratory Batch Assignment</h3>
            <p class="text-xs text-slate-400">Manage student laboratory batch division (Batch A & Batch B) for rotational experiments.</p>
        </div>
        <button type="button" onclick="openBatchSplitModal()" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 transition">
            <span class="material-symbols-rounded text-sm">call_split</span>
            <span>Configure / Split Batches</span>
        </button>
    </div>

    {{-- Batch Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Batch A Container --}}
        <div class="rounded-2xl border border-indigo-500/20 bg-slate-900/60 p-4 backdrop-blur space-y-3">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-400 font-bold text-xs font-mono">A</span>
                    <h4 class="text-xs font-bold text-white">Batch A Students</h4>
                </div>
                <span id="batch-a-badge-count" class="rounded-full bg-indigo-500/10 px-2 py-0.5 text-[11px] font-mono text-indigo-400 border border-indigo-500/20 font-bold">
                    Batch A
                </span>
            </div>
            <div class="max-h-80 overflow-y-auto rounded-xl border border-white/5 bg-slate-950/40">
                <table class="w-full text-left text-xs">
                    <thead class="sticky top-0 bg-slate-800/90 text-[10px] uppercase text-slate-400 backdrop-blur">
                        <tr>
                            <th class="p-2 w-12 text-center">Roll</th>
                            <th class="p-2">Reg No</th>
                            <th class="p-2">Name</th>
                        </tr>
                    </thead>
                    <tbody id="batch-a-tbody" class="divide-y divide-white/5 text-slate-300">
                        <tr><td colspan="3" class="p-4 text-center text-slate-500">Loading Batch A...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Batch B Container --}}
        <div class="rounded-2xl border border-teal-500/20 bg-slate-900/60 p-4 backdrop-blur space-y-3">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-teal-500/20 text-teal-400 font-bold text-xs font-mono">B</span>
                    <h4 class="text-xs font-bold text-white">Batch B Students</h4>
                </div>
                <span id="batch-b-badge-count" class="rounded-full bg-teal-500/10 px-2 py-0.5 text-[11px] font-mono text-teal-400 border border-teal-500/20 font-bold">
                    Batch B
                </span>
            </div>
            <div class="max-h-80 overflow-y-auto rounded-xl border border-white/5 bg-slate-950/40">
                <table class="w-full text-left text-xs">
                    <thead class="sticky top-0 bg-slate-800/90 text-[10px] uppercase text-slate-400 backdrop-blur">
                        <tr>
                            <th class="p-2 w-12 text-center">Roll</th>
                            <th class="p-2">Reg No</th>
                            <th class="p-2">Name</th>
                        </tr>
                    </thead>
                    <tbody id="batch-b-tbody" class="divide-y divide-white/5 text-slate-300">
                        <tr><td colspan="3" class="p-4 text-center text-slate-500">Loading Batch B...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('r26_practicum.partials.modal-batch-split')

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadLabBatchRosterData();
});

function loadLabBatchRosterData() {
    const subjectId = window.activeSubjectId || '{{ $subjectId ?? "" }}';
    if (!subjectId) return;

    fetch(`/api/classroom/practical/${subjectId}/lab-batch/roster`)
        .then(res => res.json())
        .then(data => {
            if (data.status !== 'SUCCESS') return;

            const batchATbody = document.getElementById('batch-a-tbody');
            const batchBTbody = document.getElementById('batch-b-tbody');
            batchATbody.innerHTML = '';
            batchBTbody.innerHTML = '';

            let countA = 0;
            let countB = 0;

            (data.roster || []).forEach(st => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="p-2 text-center font-mono text-slate-500">${st.roll_no || '-'}</td>
                    <td class="p-2 font-mono text-slate-400">${st.reg_no}</td>
                    <td class="p-2 font-medium text-white">${st.name}</td>
                `;

                if (st.lab_batch === 'Batch B') {
                    batchBTbody.appendChild(tr);
                    countB++;
                } else {
                    batchATbody.appendChild(tr);
                    countA++;
                }
            });

            document.getElementById('batch-a-badge-count').innerText = `${countA} Students`;
            document.getElementById('batch-b-badge-count').innerText = `${countB} Students`;
        })
        .catch(err => {
            console.error('Error loading lab batch roster:', err);
        });
}
</script>
