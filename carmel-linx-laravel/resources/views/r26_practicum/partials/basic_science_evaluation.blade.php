{{-- resources/views/r26_practicum/partials/basic_science_evaluation.blade.php --}}
<div id="subtab-evaluation" class="tab-panel space-y-6">
    <!-- Header: Basic Science Practicum Scheme (40M CIA + 60M ESE) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                    🔬
                </span>
                <div>
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span>Basic Science Practicum Evaluation Desk</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">40M CIA + 60M ESE</span>
                    </h3>
                    <p class="text-slate-500 text-xs mt-0.5">
                        SBTE Revision 2026 Examination Standards • Continuous Lab Assessment & Internal Practical Scheme
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="#lab-subcontent-ese" onclick="document.getElementById('lab-subcontent-ese').scrollIntoView({behavior: 'smooth'})" class="px-3.5 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-bold transition-colors">
                    View 40M Breakdown ↓
                </a>
            </div>
        </div>

        <!-- 5 CIA Breakdown Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mt-4">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                <div class="text-blue-600 font-bold text-[11px] uppercase tracking-wider">1. Attendance</div>
                <div class="text-xl font-black text-slate-900 font-mono mt-1">5 Marks <span class="text-xs text-slate-400 font-normal">/ 5 CIA</span></div>
                <p class="text-slate-500 text-[11px] mt-1 leading-tight">Continuous attendance over 90-hour semester.</p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                <div class="text-purple-600 font-bold text-[11px] uppercase tracking-wider">2. Self Learning</div>
                <div class="text-xl font-black text-slate-900 font-mono mt-1">5 Marks <span class="text-xs text-slate-400 font-normal">/ 5 CIA</span></div>
                <p class="text-slate-500 text-[11px] mt-1 leading-tight">Assignments, quizzes & microprojects.</p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                <div class="text-indigo-600 font-bold text-[11px] uppercase tracking-wider">3. Series Written</div>
                <div class="text-xl font-black text-slate-900 font-mono mt-1">10 Marks <span class="text-xs text-slate-400 font-normal">/ 20 Scaled</span></div>
                <p class="text-slate-500 text-[11px] mt-1 leading-tight">2 centralized 1.5-hr written tests.</p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                <div class="text-sky-600 font-bold text-[11px] uppercase tracking-wider">4. Continuous Lab (CE)</div>
                <div class="text-xl font-black text-slate-900 font-mono mt-1">10 Marks <span class="text-xs text-slate-400 font-normal">/ 50 Scaled</span></div>
                <p class="text-slate-500 text-[11px] mt-1 leading-tight">Table 2.2 continuous experiment rubrics.</p>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                <div class="text-amber-600 font-bold text-[11px] uppercase tracking-wider">5. Practical Tests</div>
                <div class="text-xl font-black text-slate-900 font-mono mt-1">10 Marks <span class="text-xs text-slate-400 font-normal">/ 40 Scaled</span></div>
                <p class="text-slate-500 text-[11px] mt-1 leading-tight">Table 3.1 practical series exams.</p>
            </div>
        </div>
    </div>

    <!-- Continuous Lab Work Evaluator (Table 2.2) Desk -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Table 2.2: Continuous Evaluation of Lab Work (50M Scale)</h4>
                <p class="text-slate-500 text-xs">Evaluated across preparation, setup, performance, observation, viva, and record submission.</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openTable22EvaluatorModal()" class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">
                    ⚡ Fullscreen Evaluator
                </button>
            </div>
        </div>

        <!-- Inline Editable Table with Debounced Autosave -->
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-3 py-2.5">Roll</th>
                        <th class="px-3 py-2.5">Student Name</th>
                        <th class="px-3 py-2.5 text-center">Prep (10)</th>
                        <th class="px-3 py-2.5 text-center">Setup (10)</th>
                        <th class="px-3 py-2.5 text-center">Perform (10)</th>
                        <th class="px-3 py-2.5 text-center">Viva (10)</th>
                        <th class="px-3 py-2.5 text-center">Record (10)</th>
                        <th class="px-3 py-2.5 text-center bg-blue-50/50">Total (50)</th>
                        <th class="px-3 py-2.5 text-center bg-emerald-50/50">Scaled (10M)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($studentResults as $s)
                    <tr class="hover:bg-slate-50/70 transition-colors" data-reg="{{ $s['reg_no'] }}">
                        <td class="px-3 py-2 font-mono text-slate-500">#{{ $s['roll_no'] }}</td>
                        <td class="px-3 py-2 font-bold text-slate-800">{{ $s['name'] }}</td>
                        <td class="px-2 py-1.5 text-center">
                            <input type="number" min="0" max="10" step="0.5" value="{{ $experimentEvals[$s['reg_no']]['prep'] ?? 8 }}" onchange="debouncedSaveCe(this, '{{ $s['reg_no'] }}', 'prep')" class="w-14 text-center px-1.5 py-1 rounded border border-slate-200 font-mono text-xs font-bold text-slate-800 focus:border-blue-500">
                        </td>
                        <td class="px-2 py-1.5 text-center">
                            <input type="number" min="0" max="10" step="0.5" value="{{ $experimentEvals[$s['reg_no']]['setup'] ?? 8 }}" onchange="debouncedSaveCe(this, '{{ $s['reg_no'] }}', 'setup')" class="w-14 text-center px-1.5 py-1 rounded border border-slate-200 font-mono text-xs font-bold text-slate-800 focus:border-blue-500">
                        </td>
                        <td class="px-2 py-1.5 text-center">
                            <input type="number" min="0" max="10" step="0.5" value="{{ $experimentEvals[$s['reg_no']]['perform'] ?? 8.5 }}" onchange="debouncedSaveCe(this, '{{ $s['reg_no'] }}', 'perform')" class="w-14 text-center px-1.5 py-1 rounded border border-slate-200 font-mono text-xs font-bold text-slate-800 focus:border-blue-500">
                        </td>
                        <td class="px-2 py-1.5 text-center">
                            <input type="number" min="0" max="10" step="0.5" value="{{ $experimentEvals[$s['reg_no']]['viva'] ?? 8 }}" onchange="debouncedSaveCe(this, '{{ $s['reg_no'] }}', 'viva')" class="w-14 text-center px-1.5 py-1 rounded border border-slate-200 font-mono text-xs font-bold text-slate-800 focus:border-blue-500">
                        </td>
                        <td class="px-2 py-1.5 text-center">
                            <input type="number" min="0" max="10" step="0.5" value="{{ $experimentEvals[$s['reg_no']]['record'] ?? 9 }}" onchange="debouncedSaveCe(this, '{{ $s['reg_no'] }}', 'record')" class="w-14 text-center px-1.5 py-1 rounded border border-slate-200 font-mono text-xs font-bold text-slate-800 focus:border-blue-500">
                        </td>
                        <td class="px-3 py-2 text-center font-bold text-blue-700 bg-blue-50/30 font-mono ce-total">41.5</td>
                        <td class="px-3 py-2 text-center font-black text-emerald-700 bg-emerald-50/30 font-mono ce-scaled">8.3</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Practical Series Tests (Table 3.1) Desk -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Table 3.1: Practical Series Examinations (CA2 &amp; CA3)</h4>
                <p class="text-slate-500 text-xs">Conducted across Series 1 and Series 2; average converted to 10 CIA Marks.</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openSeriesPrModal()" class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">
                    ⚡ Enter Series Test Marks
                </button>
            </div>
        </div>
    </div>

    <!-- ESE Scheme Breakdown Anchor -->
    <div id="lab-subcontent-ese" class="bg-gradient-to-br from-blue-50/50 via-white to-slate-50 rounded-2xl border border-blue-200/70 p-5 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <span>End Semester Exam (ESE) Theory Scheme (60 Marks)</span>
                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-xs font-bold font-mono">60M External</span>
            </h4>
            <button type="button" onclick="openEseTheoryModal()" class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">
                🏆 Enter Theory ESE Grades
            </button>
        </div>
        <p class="text-slate-600 text-xs leading-relaxed">
            Under SBTE Kerala Revision 2026, Basic Science Practicum subjects participate in a centralized written Theory End Semester Examination of 60 marks, while laboratory practical competencies are evaluated 100% continuously through internal assessments totaling 40 CIA marks.
        </p>
    </div>
</div>

<script>
let ceSaveTimeout = null;
function debouncedSaveCe(inputEl, regNo, field) {
    clearTimeout(ceSaveTimeout);
    ceSaveTimeout = setTimeout(() => {
        const row = inputEl.closest('tr');
        const inputs = row.querySelectorAll('input[type="number"]');
        let total = 0;
        inputs.forEach(inp => total += parseFloat(inp.value || 0));
        row.querySelector('.ce-total').textContent = total.toFixed(1);
        row.querySelector('.ce-scaled').textContent = (total / 5).toFixed(1);
        
        // Auto-save via AJAX
        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id ?? 0 }}/save-ce-mark', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                reg_no: regNo,
                field: field,
                value: inputEl.value
            })
        }).catch(err => console.error('AutoSave failed:', err));
    }, 600);
}

function openTable22EvaluatorModal() {
    const m = document.getElementById('experiment-eval-modal');
    if (m) m.classList.remove('hidden');
}
</script>
