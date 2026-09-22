    function openSeriesPracticalModal() {
        document.getElementById('series-practical-modal').classList.remove('hidden');
        const selectStudent = document.getElementById('series-pr-student-select');
        if (selectStudent && selectStudent.value) {
            loadSeriesPrStudent(selectStudent.value);
        }
    }
 
    function closeSeriesPracticalModal() {
        document.getElementById('series-practical-modal').classList.add('hidden');
    }
 
    function onSeriesPrTestChange(test) {
        const selectStudent = document.getElementById('series-pr-student-select');
        if (selectStudent && selectStudent.value) {
            loadSeriesPrStudent(selectStudent.value);
        }
    }
 
    function loadSeriesPrStudent(regNo) {
        const student = studentsList.find(s => s.reg_no === regNo);
        if (!student) return;
 
        const test = document.getElementById('series-pr-test-select').value;
        if (!test) return;
 
        const state = seriesPracticalEvalsState[regNo][test];
        const container = document.getElementById('series-pr-rubrics-container');
 
        const criteria = [
            { label: '1. Write-up / Procedure (Aim, Circuit/Flowchart, Stepwise procedure)', key: 'writeup_procedure', max: 10, step: 0.5 },
            { label: '2. Experiment Setup & Execution (Connections, Handling, Accuracy)', key: 'setup_execution', max: 10, step: 0.5 },
            { label: '3. Observation & Result / Output (Tabulation, Calculations, Outcome)', key: 'observation_result', max: 8, step: 0.5 },
            { label: '4. Viva Voce (Conceptual understanding, Theory knowledge)', key: 'viva_voce', max: 8, step: 0.5 },
            { label: '5. Record (Completion & neatness, Faculty certification)', key: 'record_completion', max: 4, step: 0.5 }
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
                        <span class="text-indigo-700 font-mono font-bold text-sm bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200" id="series-pr-val-badge-${c.key}">${val.toFixed(1)}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="adjustSeriesPrVal('${c.key}', -${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-black text-slate-700 flex items-center justify-center transition-colors border border-slate-300">-</button>
                        <input type="range" min="0" max="${c.max}" step="${c.step}" value="${val}" id="series-pr-slider-${c.key}" oninput="syncSeriesPrSlider('${c.key}', this.value, ${c.max})" class="flex-1 accent-indigo-600 h-2 rounded-lg cursor-pointer">
                        <button type="button" onclick="adjustSeriesPrVal('${c.key}', ${c.step}, ${c.max})" class="w-8 h-8 rounded-lg bg-slate-200 hover:bg-slate-300 font-black text-slate-700 flex items-center justify-center transition-colors border border-slate-300">+</button>
                    </div>
                </div>
            `;
        });
 
        html += '</div>';
 
        container.innerHTML = html;
        updateSeriesPrLiveDisplay(regNo, test);
    }
 
    function syncSeriesPrSlider(key, val, max) {
        const num = parseFloat(val) || 0;
        const regNo = document.getElementById('series-pr-student-select').value;
        const test = document.getElementById('series-pr-test-select').value;
        if (!regNo || !test) return;
 
        seriesPracticalEvalsState[regNo][test][key] = num;
 
        const badge = document.getElementById(`series-pr-val-badge-${key}`);
        if (badge) badge.innerText = num.toFixed(1);
 
        updateSeriesPrLiveDisplay(regNo, test);
    }
 
    function adjustSeriesPrVal(key, delta, max) {
        const slider = document.getElementById(`series-pr-slider-${key}`);
        if (!slider) return;
 
        let current = parseFloat(slider.value) || 0;
        let next = Math.max(0, Math.min(max, current + delta));
        slider.value = next;
        syncSeriesPrSlider(key, next, max);
    }
 
    function updateSeriesPrLiveDisplay(regNo, test) {
        const state = seriesPracticalEvalsState[regNo][test];
        if (!state) return;
 
        const total = (state.writeup_procedure || 0) +
                      (state.setup_execution || 0) +
                      (state.observation_result || 0) +
                      (state.viva_voce || 0) +
                      (state.record_completion || 0);
 
        state.total_score_40 = total;
 
        const cia = Math.round(((total / 40.0) * 10.0) * 2) / 2;
 
        document.getElementById('series-pr-live-total').innerText = `${total.toFixed(1)} / 40.0 M`;
        document.getElementById('series-pr-live-cia').innerText = `${cia.toFixed(1)} / 10.0 M`;
    }
 
    function prevSeriesPrStudent() {
        const sel = document.getElementById('series-pr-student-select');
        if (!sel || sel.selectedIndex <= 0) return;
        sel.selectedIndex--;
        loadSeriesPrStudent(sel.value);
    }
 
    function nextSeriesPrStudent() {
        const sel = document.getElementById('series-pr-student-select');
        if (!sel || sel.selectedIndex >= sel.options.length - 1) return;
        sel.selectedIndex++;
        loadSeriesPrStudent(sel.value);
    }
 
    function saveAndNextSeriesPrStudent() {
        const sel = document.getElementById('series-pr-student-select');
        const regNo = sel.value;
        const test = document.getElementById('series-pr-test-select').value;
        if (!regNo || !test) return;
 
        const state = seriesPracticalEvalsState[regNo][test];
        const dbSeriesName = (test === 'Series 1') ? 'Test 1 (CO1+CO2)' : 'Test 2 (CO3+CO4)';
        const bsId = {{ $batchSubject->id }};
 
        fetch(`/api/r26/classroom/practicum/${bsId}/evaluate/series-practical`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({
                series_no: dbSeriesName,
                marks_data: [{
                    reg_no: regNo,
                    writeup_procedure: state.writeup_procedure,
                    setup_execution: state.setup_execution,
                    observation_result: state.observation_result,
                    viva_voce: state.viva_voce,
                    record_completion: state.record_completion,
                    is_absent: state.is_absent
                }]
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                nextSeriesPrStudent();
            } else {
                alert('Auto-save error: ' + data.message);
            }
        });
    }
 
    function saveAllSeriesPrMarks() {
        const marksData = [];
        const test = document.getElementById('series-pr-test-select').value;
        if (!test) return;
 
        const dbSeriesName = (test === 'Series 1') ? 'Test 1 (CO1+CO2)' : 'Test 2 (CO3+CO4)';
 
        Object.keys(seriesPracticalEvalsState).forEach(regNo => {
            const state = seriesPracticalEvalsState[regNo][test];
            if (state) {
                marksData.push({
                    reg_no: regNo,
                    writeup_procedure: state.writeup_procedure,
                    setup_execution: state.setup_execution,
                    observation_result: state.observation_result,
                    viva_voce: state.viva_voce,
                    record_completion: state.record_completion,
                    is_absent: state.is_absent
                });
            }
        });
 
        Swal.fire({
            title: 'Saving Series Test Marks...',
            text: `Saving scores for ${dbSeriesName}`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
 
        fetch('/api/r26/classroom/practicum/{{ $batchSubject->id }}/evaluate/series-practical', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ series_no: dbSeriesName, marks_data: marksData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                closeSeriesPracticalModal();
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
        const eseSplitupState = {};
        
        studentsList.forEach(st => {
            const currentTotal = parseFloat(st.ese_practical || 0);
            if (currentTotal > 0) {
                const factor = currentTotal / 40.0;
                eseSplitupState[st.reg_no] = {
                    writeup: Math.round(10 * factor * 2) / 2,
                    setup: Math.round(10 * factor * 2) / 2,
                    result: Math.round(8 * factor * 2) / 2,
                    viva: Math.round(8 * factor * 2) / 2,
                    record: Math.round(4 * factor * 2) / 2
                };
            } else {
                eseSplitupState[st.reg_no] = { writeup: 0, setup: 0, result: 0, viva: 0, record: 0 };
            }
        });

