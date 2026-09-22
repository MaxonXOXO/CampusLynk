    async function openQpPreviewModal(seriesNo, coTag, mode) {
        _currentSeries = seriesNo;
        _currentCo     = coTag;
        _activeQpTab   = 'qp';
        switchQpEditorTab('qp');
        const statusEl = document.getElementById('qp-gen-status');
        statusEl.classList.remove('hidden');
        statusEl.style.color = '#94a3b8';
 
        const isPractical = seriesNo.indexOf('Practical') !== -1;
        _currentPattern = isPractical ? 'practical_series' : QP_PATTERN;
 
        const modal = document.getElementById('qp-preview-modal');
        modal.classList.remove('hidden');
        document.getElementById('qp-modal-title').textContent = `Series Exam QP — ${seriesNo} (${coTag}) | ${_currentPattern === 'practical_series' ? 'Practical Rubrics (Table 3.1)' : (_currentPattern === 'table_4_2_design' ? 'Table 4.2 Design' : 'Table 4.1 Standard')}`;
 
        document.getElementById('qp-editor-body').innerHTML = '<div class="text-slate-400 text-sm p-8 text-center animate-pulse">⚡ Loading questions…</div>';
 
        if (mode === 'ai') {
            statusEl.innerHTML = `⚡ Fetching AI/Bank questions for <strong>${seriesNo}</strong>...`;
            try {
                const res = await fetch(`/api/r26/classroom/practicum/${SUBJECT_ID}/series-qp/generate/${encodeURIComponent(seriesNo)}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF }
                });
                const data = await res.json();
                if (data.status === 'SUCCESS') {
                    _draftQp = data.qp_data;
                    _currentPattern = data.pattern_type;
                    statusEl.innerHTML = `<span style="color:#4ade80">${data.message}</span>`;
                    renderQpEditor(_draftQp, _currentPattern);
                } else {
                    document.getElementById('qp-editor-body').innerHTML = `<div class="text-red-400 p-6">${data.message}</div>`;
                }
            } catch(e) {
                document.getElementById('qp-editor-body').innerHTML = `<div class="text-red-400 p-6">Network Error: ${e.message}</div>`;
            }
        } else {
            // Manual entry — blank template
            statusEl.innerHTML = `✏ Manual mode — fill in questions for <strong>${seriesNo}</strong>`;
            _draftQp = buildEmptyQpTemplate(_currentPattern, coTag);
            renderQpEditor(_draftQp, _currentPattern);
        }
    }
 
    function buildEmptyQpTemplate(pattern, coTag) {
        if (pattern === 'practical_series') {
            return {
                part_a: [
                    {q_no:'1', text:'Perform identification, testing, and troubleshooting of electronic components.', marks:40, co:coTag, bloom:'Apply', choice_group:'Answer any ONE', scheme_key:'1. Writeup & Procedure: 10 Marks\n2. Setup & Execution: 10 Marks\n3. Observation & Result: 10 Marks\n4. Viva Voce: 5 Marks\n5. Record Completion: 5 Marks', answer_key:'Expected components list, test procedure and values.'},
                    {q_no:'2', text:'Construct and test the given resistor/diode circuit on breadboard and verify output.', marks:40, co:coTag, bloom:'Apply', choice_group:'Answer any ONE', scheme_key:'1. Writeup & Procedure: 10 Marks\n2. Setup & Execution: 10 Marks\n3. Observation & Result: 10 Marks\n4. Viva Voce: 5 Marks\n5. Record Completion: 5 Marks', answer_key:'Expected schematic connections and measured readings.'}
                ]
            };
        } else if (pattern === 'table_4_2_design') {
            return {
                part_a: Array.from({length:6}, (_,i) => ({q_no:String(i+1), text:'', marks:5, co:coTag, bloom:'Understand', scheme_key:'', answer_key:''})),
                part_b: [
                    {q_no:'7(a)', text:'', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 1', scheme_key:'', answer_key:''},
                    {q_no:'7(b)', text:'OR: ', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 1', scheme_key:'', answer_key:''},
                    {q_no:'8(a)', text:'', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 2', scheme_key:'', answer_key:''},
                    {q_no:'8(b)', text:'OR: ', marks:10, co:coTag, bloom:'Analyze', choice_group:'Set 2', scheme_key:'', answer_key:''},
                ]
            };
        } else {
            // Single CO Test: 2×1M + 3×3M + 3×7M (answer any 2) = 25M
            return {
                part_a: [
                    {q_no:'1', text:'', marks:1, co:coTag, bloom:'Remember', scheme_key:'', answer_key:''},
                    {q_no:'2', text:'', marks:1, co:coTag, bloom:'Remember', scheme_key:'', answer_key:''},
                ],
                part_b: [
                    {q_no:'3', text:'', marks:3, co:coTag, bloom:'Understand', scheme_key:'', answer_key:''},
                    {q_no:'4', text:'', marks:3, co:coTag, bloom:'Understand', scheme_key:'', answer_key:''},
                    {q_no:'5', text:'', marks:3, co:coTag, bloom:'Apply', scheme_key:'', answer_key:''},
                ],
                part_c: [
                    {q_no:'6', text:'', marks:7, co:coTag, bloom:'Analyze', choice_group:'Answer any 2 of 3', scheme_key:'', answer_key:''},
                    {q_no:'7', text:'', marks:7, co:coTag, bloom:'Analyze', choice_group:'Answer any 2 of 3', scheme_key:'', answer_key:''},
                    {q_no:'8', text:'', marks:7, co:coTag, bloom:'Analyze', choice_group:'Answer any 2 of 3', scheme_key:'', answer_key:''},
                ]
            };
        }
    }
 
    let _activeQpTab = 'qp';
    function switchQpEditorTab(tab) {
        _activeQpTab = tab;
        ['qp', 'scheme', 'key'].forEach(t => {
            const el = document.getElementById('qp-editor-tab-' + t);
            if (el) {
                if (t === tab) el.classList.remove('hidden');
                else el.classList.add('hidden');
            }
            const btn = document.getElementById('qp-edit-btn-' + t);
            if (btn) {
                btn.className = t === tab
                    ? "px-4 py-2 text-xs font-bold rounded-lg bg-indigo-600 text-white transition-all"
                    : "px-4 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:bg-slate-750 text-slate-700 transition-all";
            }
        });
    }
 
    function syncQpQuestionTexts(partKey, idx, val) {
        const schemeText = document.getElementById(`scheme-qtxt-${partKey}-${idx}`);
        const keyText = document.getElementById(`key-qtxt-${partKey}-${idx}`);
        if (schemeText) schemeText.innerText = val;
        if (keyText) keyText.innerText = val;
    }
 
    function syncQpQuestionMarks(partKey, idx, val) {
        const schemeMarks = document.getElementById(`scheme-qmarks-${partKey}-${idx}`);
        const keyMarks = document.getElementById(`key-qmarks-${partKey}-${idx}`);
        if (schemeMarks) schemeMarks.innerText = val + 'M';
        if (keyMarks) keyMarks.innerText = val + 'M';
    }
 
    function renderQpEditor(qpData, pattern) {
        const container = document.getElementById('qp-editor-body');
        const parts = pattern === 'practical_series'
            ? [['part_a', 'PART A — Practical Tasks (Answer any ONE task - 40 Marks)', '40']]
            : (pattern === 'table_4_2_design'
                ? [['part_a','PART A — Answer ALL (6 × 5M = 30M)','5'],['part_b','PART B — Answer ONE per Set (10M each)','10']]
                : [['part_a','PART A — Answer ALL (2 × 1M = 2M)','1'],['part_b','PART B — Answer ALL (3 × 3M = 9M)','3'],['part_c','PART C — Answer ANY 2 of 3 (7M each = 14M)','7']]);
 
        let htmlQp = '';
        let htmlScheme = '';
        let htmlKey = '';

        for (const [partKey, partLabel, defaultMark] of parts) {
            const rows = qpData[partKey] || [];
            
            // 1. Question Paper Tab
            htmlQp += `<div class="mb-4">
                <div class="flex items-center justify-between bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-t-xl border-t border-x border-slate-200">
                    <span class="font-bold text-indigo-300 text-sm">${partLabel}</span>
                    <button onclick="addQpRow('${partKey}','${defaultMark}')" class="text-xs px-2.5 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition-all">+ Add Question</button>
                </div>
                <div class="border border-slate-200 rounded-b-xl overflow-hidden bg-slate-50">
                    <table class="w-full text-sm" id="tbl-qp-${partKey}">
                        <thead class="bg-slate-850 text-slate-400 text-xs">
                            <tr>
                                <th class="p-2 w-14 text-center">Q.No</th>
                                <th class="p-2">Question Text</th>
                                <th class="p-2 w-28 text-center">Bloom (BT)</th>
                                <th class="p-2 w-14 text-center">Marks</th>
                                <th class="p-2 w-28 text-center">Choice Group</th>
                                <th class="p-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>`;
            rows.forEach((q, idx) => {
                htmlQp += `<tr class="border-b border-slate-200 hover:bg-white border border-slate-200 text-slate-700 hover:bg-slate-50/40" data-part="${partKey}" data-idx="${idx}">
                    <td class="p-2"><input type="text" value="${q.q_no||''}" onchange="updateQpField('${partKey}',${idx},'q_no',this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-white font-mono text-center"></td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'text',this.value); syncQpQuestionTexts('${partKey}',${idx},this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-white resize-y" placeholder="Type question here…">${q.text||''}</textarea></td>
                    <td class="p-2"><select onchange="updateQpField('${partKey}',${idx},'bloom',this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-white">
                        ${['Remember','Understand','Apply','Analyze','Evaluate','Create'].map(l=>`<option ${q.bloom===l?'selected':''}>${l}</option>`).join('')}
                    </select></td>
                    <td class="p-2"><input type="number" min="1" max="30" value="${q.marks||defaultMark}" onchange="updateQpField('${partKey}',${idx},'marks',parseInt(this.value)); syncQpQuestionMarks('${partKey}',${idx},parseInt(this.value))" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-amber-300 font-bold text-center"></td>
                    <td class="p-2"><input type="text" value="${q.choice_group||''}" placeholder="e.g. Set A" onchange="updateQpField('${partKey}',${idx},'choice_group',this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-purple-300"></td>
                    <td class="p-2 text-center"><button onclick="removeQpRow('${partKey}',${idx})" class="text-red-400 hover:text-red-300 text-xs font-bold">✕</button></td>
                </tr>`;
            });
            htmlQp += `</tbody></table></div></div>`;

            // 2. Evaluation Scheme Tab
            htmlScheme += `<div class="mb-4">
                <div class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-t-xl border-t border-x border-slate-200">
                    <span class="font-bold text-emerald-600 text-sm">${partLabel} — Scheme</span>
                </div>
                <div class="border border-slate-200 rounded-b-xl overflow-hidden bg-slate-50">
                    <table class="w-full text-sm" id="tbl-scheme-${partKey}">
                        <thead class="bg-slate-850 text-slate-400 text-xs">
                            <tr>
                                <th class="p-2 w-14 text-center">Q.No</th>
                                <th class="p-2 w-1/2">Question Text</th>
                                <th class="p-2 w-1/2">Evaluation Scheme (Key Points / Mark Split)</th>
                                <th class="p-2 w-14 text-center">Marks</th>
                            </tr>
                        </thead>
                        <tbody>`;
            rows.forEach((q, idx) => {
                htmlScheme += `<tr class="border-b border-slate-200 hover:bg-white border border-slate-200 text-slate-700 hover:bg-slate-50/40">
                    <td class="p-2 text-center text-slate-400 font-mono text-xs">${q.q_no||''}</td>
                    <td class="p-2 text-xs text-slate-700 bg-white/20 max-w-xs truncate" id="scheme-qtxt-${partKey}-${idx}">${q.text||''}</td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'scheme_key',this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-emerald-300 resize-y" placeholder="Marking scheme guidelines…">${q.scheme_key||''}</textarea></td>
                    <td class="p-2 text-center text-amber-300 font-bold text-xs" id="scheme-qmarks-${partKey}-${idx}">${q.marks||defaultMark}M</td>
                </tr>`;
            });
            htmlScheme += `</tbody></table></div></div>`;

            // 3. Answer Key Tab
            htmlKey += `<div class="mb-4">
                <div class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-t-xl border-t border-x border-slate-200">
                    <span class="font-bold text-blue-700 text-sm">${partLabel} — Answer Key</span>
                </div>
                <div class="border border-slate-200 rounded-b-xl overflow-hidden bg-slate-50">
                    <table class="w-full text-sm" id="tbl-key-${partKey}">
                        <thead class="bg-slate-850 text-slate-400 text-xs">
                            <tr>
                                <th class="p-2 w-14 text-center">Q.No</th>
                                <th class="p-2 w-1/2">Question Text</th>
                                <th class="p-2 w-1/2">Model Answer / Key Details</th>
                                <th class="p-2 w-14 text-center">Marks</th>
                            </tr>
                        </thead>
                        <tbody>`;
            rows.forEach((q, idx) => {
                htmlKey += `<tr class="border-b border-slate-200 hover:bg-white border border-slate-200 text-slate-700 hover:bg-slate-50/40">
                    <td class="p-2 text-center text-slate-400 font-mono text-xs">${q.q_no||''}</td>
                    <td class="p-2 text-xs text-slate-700 bg-white/20 max-w-xs truncate" id="key-qtxt-${partKey}-${idx}">${q.text||''}</td>
                    <td class="p-2"><textarea rows="3" onchange="updateQpField('${partKey}',${idx},'answer_key',this.value)" class="w-full bg-white border border-slate-200 rounded px-1.5 py-1 text-xs text-blue-300 resize-y" placeholder="Model answer text…">${q.answer_key||''}</textarea></td>
                    <td class="p-2 text-center text-amber-300 font-bold text-xs" id="key-qmarks-${partKey}-${idx}">${q.marks||defaultMark}M</td>
                </tr>`;
            });
            htmlKey += `</tbody></table></div></div>`;
        }

        container.innerHTML = `
            <div id="qp-editor-tab-qp" class="${_activeQpTab === 'qp' ? '' : 'hidden'}">${htmlQp}</div>
            <div id="qp-editor-tab-scheme" class="${_activeQpTab === 'scheme' ? '' : 'hidden'}">${htmlScheme}</div>
            <div id="qp-editor-tab-key" class="${_activeQpTab === 'key' ? '' : 'hidden'}">${htmlKey}</div>
        `;
    }

    function updateQpField(part, idx, field, value) {
        if (!_draftQp[part]) return;
        _draftQp[part][idx][field] = value;
    }

    function addQpRow(partKey, defaultMark) {
        if (!_draftQp[partKey]) _draftQp[partKey] = [];
        const idx = _draftQp[partKey].length + 1;
        _draftQp[partKey].push({q_no: String(idx), text: '', marks: parseInt(defaultMark), co: _currentCo, bloom: 'Understand', scheme_key: '', answer_key: ''});
        renderQpEditor(_draftQp, _currentPattern);
    }
 
    function removeQpRow(partKey, idx) {
        if (!_draftQp[partKey]) return;
        _draftQp[partKey].splice(idx, 1);
        renderQpEditor(_draftQp, _currentPattern);
    }
 
    function closeQpModal() {
        document.getElementById('qp-preview-modal').classList.add('hidden');
    }
 
    async function saveQpFromModal() {
        const statusEl = document.getElementById('qp-gen-status');
        const saveBtn  = document.getElementById('qp-save-btn');
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving…';
 
        try {
            const res = await fetch(`/api/r26/classroom/practicum/${SUBJECT_ID}/series-qp/save/${encodeURIComponent(_currentSeries)}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({
                    co_tag: _currentCo,
                    pattern_type: _currentPattern,
                    qp_data: _draftQp,
                    scheme_data: _draftQp,
                    answer_key: _draftQp,
                })
            });
            const data = await res.json();
            if (data.status === 'SUCCESS') {
                statusEl.innerHTML = `✅ <strong>${_currentSeries}</strong> QP saved to Question Bank!`;
                statusEl.style.color = '#4ade80';
                statusEl.classList.remove('hidden');
                closeQpModal();
                setTimeout(() => location.reload(), 1200);
            } else {
                saveBtn.disabled = false;
                saveBtn.textContent = '💾 Save & Add to Question Bank';
                alert('Error: ' + data.message);
            }
        } catch(e) {
            saveBtn.disabled = false;
            saveBtn.textContent = '💾 Save & Add to Question Bank';
            alert('Network error: ' + e.message);
        }
    }

    // =====================================================================
    // ESE Theory Grade Modal System
    // =====================================================================
    const eseGradesState = {};
    studentsList.forEach(s => {
        eseGradesState[s.reg_no] = {
            ese_theory_grade: s.ese_theory_grade === '-' ? '' : s.ese_theory_grade,
            theory_absent: (s.ese_theory_grade === 'FE')
        };
    });

