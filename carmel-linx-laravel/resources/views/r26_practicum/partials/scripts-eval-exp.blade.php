    <script>
    // =====================================================================
    // Continuous Lab Experiment Evaluation System
    // =====================================================================
    const experimentEvalsDb = @json($experimentEvals);
    const experimentEvalsState = {};
 
    // Initialize state
    studentsList.forEach(s => {
        const regNo = s.reg_no;
        experimentEvalsState[regNo] = {};
        
        // Populate from DB if exists
        const dbList = experimentEvalsDb[regNo] || [];
        dbList.forEach(rec => {
            experimentEvalsState[regNo][rec.experiment_no] = {
                prep_punctuality: parseFloat(rec.prep_punctuality) || 0,
                setup_procedure: parseFloat(rec.setup_procedure) || 0,
                observation_recording: parseFloat(rec.observation_recording) || 0,
                analysis_interpretation: parseFloat(rec.analysis_interpretation) || 0,
                viva_voce: parseFloat(rec.viva_voce) || 0,
                workmanship_discipline: parseFloat(rec.workmanship_discipline) || 0,
                total_score_50: parseFloat(rec.total_score_50) || 0
            };
        });
    });
 
    function openExperimentEvalModal() {
        document.getElementById('experiment-eval-modal').classList.remove('hidden');
        const selectStudent = document.getElementById('eval-student-select');
        if (selectStudent && selectStudent.value) {
            loadExpStudent(selectStudent.value);
        }
    }
 
    function closeExperimentEvalModal() {
        document.getElementById('experiment-eval-modal').classList.add('hidden');
    }
 
    function onEvalExpChange(expNo) {
        const selectStudent = document.getElementById('eval-student-select');
        if (selectStudent && selectStudent.value) {
            loadExpStudent(selectStudent.value);
        }
    }
 
    function loadExpStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;
 
        const expNo = document.getElementById('eval-exp-select').value;
        if (!expNo) return;
 
        // Ensure state exists for this student/experiment
        if (!experimentEvalsState[regNo][expNo]) {
            experimentEvalsState[regNo][expNo] = {
                prep_punctuality: 0,
                setup_procedure: 0,
                observation_recording: 0,
                analysis_interpretation: 0,
                viva_voce: 0,
                workmanship_discipline: 0,
                total_score_50: 0
            };
        }
 
        const state = experimentEvalsState[regNo][expNo];
        const container = document.getElementById('exp-rubrics-container');
 
        const criteria = [
            { label: '1. Prep & Punctuality', key: 'prep_punctuality', max: 10, step: 0.5 },
            { label: '2. Setup & Procedure', key: 'setup_procedure', max: 10, step: 0.5 },
            { label: '3. Observation & Recording', key: 'observation_recording', max: 5, step: 0.5 },
            { label: '4. Analysis & Interpretation', key: 'analysis_interpretation', max: 10, step: 0.5 },
            { label: '5. Viva Voce', key: 'viva_voce', max: 10, step: 0.5 },
            { label: '6. Workmanship & Discipline', key: 'workmanship_discipline', max: 5, step: 0.5 }
        ];
 
        let html = `
            <div class="mb-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Grading Criteria &mdash; ${student.name}</div>
            <div class="space-y-3">
        `;
 
        criteria.forEach(c => {
            const val = state[c.key] || 0;
            html += `
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-2">
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-slate-700">${c.label} <span class="text-slate-400 font-normal">(Max ${c.max})</span></span>
                        <span class="text-emerald-700 font-mono font-bold text-sm bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200" id="exp-val-badge-${c.key}">${val.toFixed(1)}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="adjustExpVal('${c.key}', -${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-black text-slate-700 flex items-center justify-center transition-colors border border-slate-300">-</button>
                        <input type="range" min="0" max="${c.max}" step="${c.step}" value="${val}" id="exp-slider-${c.key}" oninput="syncExpSlider('${c.key}', this.value, ${c.max})" class="flex-1 accent-purple-600 h-2 rounded-lg cursor-pointer">
                        <button type="button" onclick="adjustExpVal('${c.key}', ${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-black text-slate-700 flex items-center justify-center transition-colors border border-slate-300">+</button>
                    </div>
                </div>
            `;
        });
 
        html += '</div>';
 
        container.innerHTML = html;
        updateExpLiveDisplay(regNo, expNo);
    }
 
    function syncExpSlider(key, val, max) {
        const num = parseFloat(val) || 0;
        const regNo = document.getElementById('eval-student-select').value;
        const expNo = document.getElementById('eval-exp-select').value;
        if (!regNo || !expNo) return;
 
        experimentEvalsState[regNo][expNo][key] = num;
 
        const badge = document.getElementById(`exp-val-badge-${key}`);
        if (badge) badge.innerText = num.toFixed(1);
 
        updateExpLiveDisplay(regNo, expNo);
    }
 
    function adjustExpVal(key, delta, max) {
        const slider = document.getElementById(`exp-slider-${key}`);
        if (!slider) return;
 
        let current = parseFloat(slider.value) || 0;
        let next = Math.max(0, Math.min(max, current + delta));
        slider.value = next;
        syncExpSlider(key, next, max);
    }
 
    function updateExpLiveDisplay(regNo, expNo) {
        const state = experimentEvalsState[regNo][expNo];
        if (!state) return;
 
        const total = (state.prep_punctuality || 0) +
                      (state.setup_procedure || 0) +
                      (state.observation_recording || 0) +
                      (state.analysis_interpretation || 0) +
                      (state.viva_voce || 0) +
                      (state.workmanship_discipline || 0);
 
        state.total_score_50 = total;
 
        const cia = Math.round(((total / 50.0) * 10.0) * 2) / 2;
 
        document.getElementById('exp-live-total').innerText = `${total.toFixed(1)} / 50.0 M`;
        document.getElementById('exp-live-cia').innerText = `${cia.toFixed(1)} / 10.0 M`;
    }
 
    function prevExpStudent() {
        const sel = document.getElementById('eval-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadExpStudent(sel.value);
    }
 
    function nextExpStudent() {
        const sel = document.getElementById('eval-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadExpStudent(sel.value);
    }
 
    function saveAndNextExpStudent() {
        const sel = document.getElementById('eval-student-select');
        const regNo = sel.value;
        const expNo = document.getElementById('eval-exp-select').value;
        if (!regNo || !expNo) return;
 
        const state = experimentEvalsState[regNo][expNo];
        const bsId = {{ $batchSubject->id }};
 
        fetch(`/api/r26/classroom/practicum/${bsId}/evaluate/experiment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                experiment_no: expNo,
                marks_data: [{
                    reg_no: regNo,
                    prep_punctuality: state.prep_punctuality,
                    setup_procedure: state.setup_procedure,
                    observation_recording: state.observation_recording,
                    analysis_interpretation: state.analysis_interpretation,
                    viva_voce: state.viva_voce,
                    workmanship_discipline: state.workmanship_discipline,
                    total_score_50: state.total_score_50
                }]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                nextExpStudent();
            } else {
                alert('Auto-save error: ' + data.message);
            }
        });
    }
 
    function saveAllExpMarks() {
        const marksData = [];
        const expNo = document.getElementById('eval-exp-select').value;
        if (!expNo) return;
 
        Object.keys(experimentEvalsState).forEach(regNo => {
            const state = experimentEvalsState[regNo][expNo];
            if (state) {
                marksData.push({
                    reg_no: regNo,
                    prep_punctuality: state.prep_punctuality,
                    setup_procedure: state.setup_procedure,
                    observation_recording: state.observation_recording,
                    analysis_interpretation: state.analysis_interpretation,
                    viva_voce: state.viva_voce,
                    workmanship_discipline: state.workmanship_discipline,
                    total_score_50: state.total_score_50
                });
            }
        });
 
        Swal.fire({
            title: 'Saving Lab Work Marks...',
            text: `Saving scores for ${expNo}`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
 
        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/experiment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ experiment_no: expNo, marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeExperimentEvalModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error', err.message, 'error');
        });
    }
 
    // =====================================================================
    // Practical Series Exams Evaluation System
    // =====================================================================
    const seriesPracticalEvalsDb = @json($seriesPracticalEvals);
    const seriesPracticalEvalsState = {};
 
    studentsList.forEach(s => {
        const regNo = s.reg_no;
        seriesPracticalEvalsState[regNo] = {
            'Series 1': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false },
            'Series 2': { writeup_procedure: 0, setup_execution: 0, observation_result: 0, viva_voce: 0, record_completion: 0, total_score_40: 0, is_absent: false }
        };
 
        const dbList = seriesPracticalEvalsDb[regNo] || [];
        dbList.forEach(rec => {
            const sNo = rec.series_no;
            let mapped = sNo;
            if (sNo === 'Test 1 (CO1+CO2)') mapped = 'Series 1';
            if (sNo === 'Test 2 (CO3+CO4)') mapped = 'Series 2';
 
            if (seriesPracticalEvalsState[regNo][mapped]) {
                seriesPracticalEvalsState[regNo][mapped] = {
                    writeup_procedure: parseFloat(rec.writeup_procedure) || 0,
                    setup_execution: parseFloat(rec.setup_execution) || 0,
                    observation_result: parseFloat(rec.observation_result) || 0,
                    viva_voce: parseFloat(rec.viva_voce) || 0,
                    record_completion: parseFloat(rec.record_completion) || 0,
                    total_score_40: parseFloat(rec.total_score_40) || 0,
                    is_absent: !!rec.is_absent
                };
            }
        });
    });
 
