{{-- Modal: Lab Batch A/B Auto-Split & Assignment --}}
<div id="modal-batch-split" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm transition-opacity">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-2xl rounded-2xl border border-white/10 bg-slate-900/95 p-6 shadow-2xl backdrop-blur-xl">
            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/20 text-indigo-400">
                        <span class="material-symbols-rounded">call_split</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Lab Batch A / B Splitting</h3>
                        <p class="text-xs text-slate-400">Configure practical laboratory cohort divisions for rotating experiments</p>
                    </div>
                </div>
                <button type="button" onclick="closeBatchSplitModal()" class="rounded-lg p-1 text-slate-400 hover:bg-white/10 hover:text-white">
                    <span class="material-symbols-rounded">close</span>
                </button>
            </div>

            {{-- Form Body --}}
            <div class="mt-4 space-y-4">
                <div class="space-y-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">Splitting Strategy</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex items-center gap-2 rounded-xl border border-white/10 bg-slate-950/40 p-3 cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="split_method" value="half" checked onchange="toggleCutoffInput()" class="text-indigo-600 focus:ring-0">
                            <div>
                                <span class="block text-xs font-bold text-white">50 / 50 Half</span>
                                <span class="text-[10px] text-slate-500">First half & second half</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 rounded-xl border border-white/10 bg-slate-950/40 p-3 cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="split_method" value="cutoff" onchange="toggleCutoffInput()" class="text-indigo-600 focus:ring-0">
                            <div>
                                <span class="block text-xs font-bold text-white">Roll Cutoff</span>
                                <span class="text-[10px] text-slate-500">Up to roll number</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-2 rounded-xl border border-white/10 bg-slate-950/40 p-3 cursor-pointer hover:border-indigo-500 transition">
                            <input type="radio" name="split_method" value="alternating" onchange="toggleCutoffInput()" class="text-indigo-600 focus:ring-0">
                            <div>
                                <span class="block text-xs font-bold text-white">Alternating</span>
                                <span class="text-[10px] text-slate-500">Odd vs Even roll</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="cutoff-container" class="hidden space-y-1">
                    <label class="block text-xs font-medium text-slate-300">Cutoff Roll Number (Last Roll for Batch A)</label>
                    <input type="number" id="split-cutoff-val" min="1" max="100" placeholder="e.g. 30" class="w-full rounded-xl border border-white/10 bg-slate-950/60 p-2.5 text-xs text-white focus:border-indigo-500 focus:outline-none">
                </div>

                <div id="split-alert" class="hidden rounded-xl p-3 text-xs"></div>
            </div>

            {{-- Footer --}}
            <div class="mt-6 flex items-center justify-end gap-3 border-t border-white/10 pt-4">
                <button type="button" onclick="closeBatchSplitModal()" class="rounded-xl px-4 py-2 text-xs font-semibold text-slate-400 hover:bg-white/10 hover:text-white transition">
                    Cancel
                </button>
                <button type="button" id="btn-apply-split" onclick="executeAutoSplit()" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500 transition">
                    <span class="material-symbols-rounded text-sm">auto_fix_high</span>
                    <span>Apply Auto-Split</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openBatchSplitModal() {
    document.getElementById('modal-batch-split').classList.remove('hidden');
}

function closeBatchSplitModal() {
    document.getElementById('modal-batch-split').classList.add('hidden');
}

function toggleCutoffInput() {
    const method = document.querySelector('input[name="split_method"]:checked')?.value;
    const cutoffCont = document.getElementById('cutoff-container');
    if (method === 'cutoff') {
        cutoffCont.classList.remove('hidden');
    } else {
        cutoffCont.classList.add('hidden');
    }
}

function executeAutoSplit() {
    const subjectId = window.activeSubjectId || '{{ $subjectId ?? "" }}';
    if (!subjectId) {
        alert('Subject ID is missing from active context.');
        return;
    }

    const method = document.querySelector('input[name="split_method"]:checked')?.value || 'half';
    const cutoff = document.getElementById('split-cutoff-val')?.value || null;
    const btn = document.getElementById('btn-apply-split');
    const alertBox = document.getElementById('split-alert');

    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-rounded animate-spin text-sm">progress_activity</span> Splitting...';

    fetch(`/api/classroom/practical/${subjectId}/lab-batch/auto-split`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            method: method,
            cutoff: cutoff ? parseInt(cutoff) : null
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-sm">auto_fix_high</span> Apply Auto-Split';

        if (data.status === 'SUCCESS') {
            alertBox.className = 'rounded-xl p-3 text-xs bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
            alertBox.innerText = data.message;
            alertBox.classList.remove('hidden');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            alertBox.className = 'rounded-xl p-3 text-xs bg-rose-500/20 text-rose-300 border border-rose-500/30';
            alertBox.innerText = data.message || 'Auto-split failed.';
            alertBox.classList.remove('hidden');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-sm">auto_fix_high</span> Apply Auto-Split';
        alertBox.className = 'rounded-xl p-3 text-xs bg-rose-500/20 text-rose-300 border border-rose-500/30';
        alertBox.innerText = 'Network error: ' + err.message;
        alertBox.classList.remove('hidden');
    });
}
</script>
