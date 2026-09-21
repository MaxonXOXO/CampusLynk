          `;
        });
      }

      html += `</div>`;

      // Online MCQ Test Setup (Collapsible)
      let onlineTestHtml = `
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs no-print mb-6">
          <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors" onclick="document.getElementById('onlineTestWrapper').classList.toggle('hidden'); const icon = document.getElementById('onlineTestIcon'); if (icon) icon.classList.toggle('rotate-180');">
            <div class="font-bold text-sm text-slate-800 flex items-center gap-2 tracking-wider uppercase">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg> Online MCQ Tests Setup
              <svg id="onlineTestIcon" class="w-4 h-4 text-slate-500 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
          </div>
          <div id="onlineTestWrapper" class="hidden p-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Configuration Form -->
              <div class="col-span-2 bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <h5 class="text-sm font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2.5">Publish New Online Test</h5>
                <div class="grid grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">Target COs (Multiple)</label>
                    <select id="online_test_cos" multiple class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 h-[104px] shadow-2xs">
                      ${cos ? cos.map(co => `<option value="${co.id}">${co.id}</option>`).join('') : ''}
                    </select>
                  </div>
                  <div class="space-y-3">
                    <div>
                      <label class="block text-xs text-slate-600 font-bold mb-1 uppercase">Max Attempts</label>
                      <input type="number" id="online_test_attempts" value="1" min="1" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 shadow-2xs">
                    </div>
                    <div>
                      <label class="block text-xs text-slate-600 font-bold mb-1 uppercase">Duration (Minutes)</label>
                      <input type="number" id="online_test_duration" value="30" min="5" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 shadow-2xs">
                    </div>
                  </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">Number of Questions</label>
                    <input type="number" id="online_test_q_count" value="10" min="1" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-800 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 shadow-2xs">
                  </div>
                  <div>
                    <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">Generation Mode</label>
                    <select id="online_test_gen_mode" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium text-slate-800 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 shadow-2xs">
                      <option value="bank">Mode B: Question Bank Pool</option>
                      <option value="ai">Mode A: AI Generator (Gemini)</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">Start Time</label>
                    <input type="text" id="online_test_start" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-800 outline-none focus:border-purple-500 shadow-2xs" placeholder="Select Date & Time">
                  </div>
                  <div>
                    <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">End Time (Deadline)</label>
                    <input type="text" id="online_test_end" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-800 outline-none focus:border-purple-500 shadow-2xs" placeholder="Select Date & Time">
                  </div>
                </div>
                
                <div class="mb-5">
                  <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">Custom Test ID/Name (Optional)</label>
                  <input type="text" id="online_test_name" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-800 outline-none focus:border-purple-500 shadow-2xs" placeholder="e.g. Midterm Test 1">
                </div>
                <button onclick="publishOnlineTest('${currentSubjectId}')" class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition-premium flex items-center justify-center gap-2 shadow-xs cursor-pointer">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg> Generate & Publish to Students
                </button>
              </div>
              
              <!-- Active Tests Dashboard -->
              <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col">
                <h5 class="text-xs font-bold text-slate-800 mb-3 border-b border-slate-200 pb-2">Active Online Tests</h5>
                <div id="activeOnlineTestsList" class="space-y-2 text-xs text-slate-600 flex-1">
                   <div class="p-4 bg-white border border-slate-200 rounded-xl text-center border-dashed text-slate-500 font-medium">No active online tests found.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;

      html += onlineTestHtml;

      html += `
        <div id="printableExamArea" class="hidden no-print"></div>
      `;

      document.getElementById('summativeAssessmentContent').innerHTML = html;

      // Automatically spawn manual fields for any saved manual papers on load
      if (cos && cos.length > 0) {
         cos.forEach(co => {
             let testData = currentSummativeTests[co.id] || null;
             if (testData && testData.manual_mode) {
                 spawnManualFields(co.id);
                 // Adjust button styling to show it's manual save
                 const btn = document.getElementById(`gen_btn_${co.id}`);
                 if (btn) {
                     btn.innerText = 'Save Custom Questions';
                     btn.classList.replace('bg-blue-600/20', 'bg-emerald-600/20');
                     btn.classList.replace('hover:bg-blue-600', 'hover:bg-emerald-600');
                     btn.classList.replace('border-blue-500/30', 'border-emerald-500/30');
                     btn.classList.replace('text-blue-400', 'text-emerald-400');
                 }
             }
         });
      }

      // Initialize Flatpickr
      if (typeof flatpickr !== 'undefined') {
        flatpickr("#online_test_start", { 
          enableTime: true, 
          dateFormat: "Y-m-d H:i", 
          time_24hr: false, 
          minDate: "today" 
        });
        flatpickr("#online_test_end", { 
          enableTime: true, 
          dateFormat: "Y-m-d H:i", 
          time_24hr: false, 
          minDate: "today" 
        });
      }
    }

    function syncSummativeInputs(sourceCoId) {
      calcSummativeTotal(sourceCoId);
      if(document.getElementById(`sync_pattern_${sourceCoId}`)?.checked) {
         applySummativePatternToAll(sourceCoId);
         
         // Trigger spawn for all COs in manual mode
         document.querySelectorAll('[id^="summ_card_"]').forEach(card => {
             const coId = card.id.replace('summ_card_', '');
             const isManual = document.querySelector(`input[name="summ_mode_${coId}"]:checked`)?.value === 'manual';
             if (isManual) {
                 spawnManualFields(coId);
             }
         });
      } else {
         const isManual = document.querySelector(`input[name="summ_mode_${sourceCoId}"]:checked`)?.value === 'manual';
         if (isManual) {
             spawnManualFields(sourceCoId);
         }
      }
    }

    function calcSummativeTotal(coId) {
      let total = 0;
      ['A', 'B', 'C'].forEach(p => {
        let q = parseInt(document.getElementById(`summ_q_${p}_${coId}`).value) || 0;
        let m = parseInt(document.getElementById(`summ_m_${p}_${coId}`).value) || 0;
        total += (q * m);
      });
      const tEl = document.getElementById(`summ_total_${coId}`);
      if (tEl) {
        tEl.innerText = total;
        tEl.classList.remove('text-emerald-400');
        tEl.classList.add('text-blue-400');
      }
    }

    function applySummativePatternToAll(sourceCoId) {
      const qA = document.getElementById(`summ_q_A_${sourceCoId}`).value;
      const mA = document.getElementById(`summ_m_A_${sourceCoId}`).value;
      const qB = document.getElementById(`summ_q_B_${sourceCoId}`).value;
      const mB = document.getElementById(`summ_m_B_${sourceCoId}`).value;
      const qC = document.getElementById(`summ_q_C_${sourceCoId}`).value;
      const mC = document.getElementById(`summ_m_C_${sourceCoId}`).value;

      document.querySelectorAll('[id^="summ_q_A_"]').forEach(el => { if(el.id !== `summ_q_A_${sourceCoId}`) el.value = qA; });
      document.querySelectorAll('[id^="summ_m_A_"]').forEach(el => { if(el.id !== `summ_m_A_${sourceCoId}`) el.value = mA; });
      document.querySelectorAll('[id^="summ_q_B_"]').forEach(el => { if(el.id !== `summ_q_B_${sourceCoId}`) el.value = qB; });
      document.querySelectorAll('[id^="summ_m_B_"]').forEach(el => { if(el.id !== `summ_m_B_${sourceCoId}`) el.value = mB; });
      document.querySelectorAll('[id^="summ_q_C_"]').forEach(el => { if(el.id !== `summ_q_C_${sourceCoId}`) el.value = qC; });
      document.querySelectorAll('[id^="summ_m_C_"]').forEach(el => { if(el.id !== `summ_m_C_${sourceCoId}`) el.value = mC; });

      // Uncheck all other checkboxes to avoid conflict
      document.querySelectorAll('[id^="sync_pattern_"]').forEach(el => {
         if(el.id !== `sync_pattern_${sourceCoId}`) el.checked = false;
         
         // Trigger recalculation on all modified cards
         let c_id = el.id.replace('sync_pattern_', '');
         calcSummativeTotal(c_id);
      });
    }

    function saveSummativeConfig(subjectId, coTag) {
      let dateValue = document.getElementById(`summ_date_${coTag}`).value;
      fetch(`/api/classroom/${subjectId}/save-summative-config`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, date_of_exam: dateValue })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') console.log('Saved date');
      });
    }

    function lockSummativeTest(subjectId, coTag) {
      if(!confirm(`Are you sure you want to lock ${coTag} test? This cannot be easily undone.`)) return;
      fetch(`/api/classroom/${subjectId}/save-summative-config`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, is_locked: true })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') loadCourseDetails(subjectId);
        else alert(data.message);
      });
    }

    let tempSummativePatterns = {};

    function saveSummativePatterns() {
       document.querySelectorAll('[id^="summ_q_A_"]').forEach(el => {
          let coTag = el.id.replace('summ_q_A_', '');
          tempSummativePatterns[coTag] = {
             qA: document.getElementById(`summ_q_A_${coTag}`)?.value || '',
             mA: document.getElementById(`summ_m_A_${coTag}`)?.value || '',
             qB: document.getElementById(`summ_q_B_${coTag}`)?.value || '',
             mB: document.getElementById(`summ_m_B_${coTag}`)?.value || '',
             qC: document.getElementById(`summ_q_C_${coTag}`)?.value || '',
             mC: document.getElementById(`summ_m_C_${coTag}`)?.value || '',
          };
       });
    }

    function toggleSummativeMode(coId) {
       const isManual = document.querySelector(`input[name="summ_mode_${coId}"]:checked`).value === 'manual';
       const btn = document.getElementById(`gen_btn_${coId}`);
       if(btn) {
          if(isManual) {
             btn.innerText = 'Save Custom Questions';
             btn.classList.replace('bg-blue-600/20', 'bg-emerald-600/20');
             btn.classList.replace('hover:bg-blue-600', 'hover:bg-emerald-600');
             btn.classList.replace('border-blue-500/30', 'border-emerald-500/30');
             btn.classList.replace('text-blue-400', 'text-emerald-400');
             
             // Instantly spawn manual question fields
             spawnManualFields(coId);
          } else {
             btn.innerText = 'Generate AI Question Paper';
             btn.classList.replace('bg-emerald-600/20', 'bg-blue-600/20');
             btn.classList.replace('hover:bg-emerald-600', 'hover:bg-blue-600');
             btn.classList.replace('border-emerald-500/30', 'border-blue-500/30');
             btn.classList.replace('text-emerald-400', 'text-blue-400');

             // Remove manual entry fields if switching back to AI
             const wrapper = document.getElementById(`manual_form_wrapper_${coId}`);
             if (wrapper) {
                 wrapper.innerHTML = '';
             }
          }
       }
    }

    function spawnManualFields(coTag) {
      let qA = parseInt(document.getElementById(`summ_q_A_${coTag}`).value) || 0;
      let qB = parseInt(document.getElementById(`summ_q_B_${coTag}`).value) || 0;
      let qC = parseInt(document.getElementById(`summ_q_C_${coTag}`).value) || 0;

      let testData = currentSummativeTests[coTag] || null;
      let savedA = (testData && testData.manual_mode) ? (testData.part_a?.questions || []) : [];
      let savedB = (testData && testData.manual_mode) ? (testData.part_b?.questions || []) : [];
      let savedC = (testData && testData.manual_mode) ? (testData.part_c?.questions || []) : [];

      let html = `<div id="manual_form_${coTag}" class="mt-4 pt-4 border-t border-slate-100">`;
      html += `<div class="text-sm text-slate-800 bg-slate-50/70 p-5 rounded-2xl border border-slate-200 shadow-xs space-y-4">`;
      
      const buildFields = (count, partName, prefix, savedQuestions) => {
         let fHtml = '';
         if(count > 0) fHtml += `<div class="font-bold text-slate-800 border-b border-slate-200 pb-2 text-xs uppercase tracking-wider">${partName}</div><div class="space-y-3 mt-3">`;
         for(let i=0; i<count; i++) {
            let qText = savedQuestions && savedQuestions[i] ? savedQuestions[i].q : '';
            let qLvl = savedQuestions && savedQuestions[i] ? savedQuestions[i].level : 'U';
            fHtml += `
              <div class="flex gap-3 items-start">
                 <span class="text-slate-500 mt-2 font-mono text-xs font-bold">${i+1}.</span>
                 <textarea id="man_q_${prefix}_${coTag}_${i}" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm shadow-2xs placeholder:text-slate-400" rows="2" placeholder="Enter question ${i+1}...">${qText}</textarea>
                 <select id="man_lvl_${prefix}_${coTag}_${i}" class="bg-white border border-slate-200 rounded-lg p-2 text-slate-800 font-medium text-xs w-36 outline-none focus:border-blue-500 shadow-2xs mt-0.5">
                    <option value="U" ${qLvl === 'U' ? 'selected' : ''}>U (Understand)</option>
                    <option value="R" ${qLvl === 'R' ? 'selected' : ''}>R (Remember)</option>
                    <option value="A" ${qLvl === 'A' ? 'selected' : ''}>A (Apply)</option>
                 </select>
              </div>
            `;
         }
         if(count > 0) fHtml += `</div>`;
         return fHtml;
      };

      html += buildFields(qA, 'PART A', 'A', savedA);
      html += buildFields(qB, 'PART B', 'B', savedB);
      html += buildFields(qC, 'PART C', 'C', savedC);
      html += `</div></div>`;

      let wrapper = document.getElementById(`manual_form_wrapper_${coTag}`);
      if (wrapper) {
          wrapper.innerHTML = html;
      }
      
      const btn = document.getElementById(`gen_btn_${coTag}`);
      if (btn) btn.innerText = 'Save Custom Questions';
    }

    function saveManualSummativePaper(subjectId, coTag) {
      let qA = parseInt(document.getElementById(`summ_q_A_${coTag}`).value) || 0;
      let mA = parseInt(document.getElementById(`summ_m_A_${coTag}`).value) || 0;
      let qB = parseInt(document.getElementById(`summ_q_B_${coTag}`).value) || 0;
      let mB = parseInt(document.getElementById(`summ_m_B_${coTag}`).value) || 0;
      let qC = parseInt(document.getElementById(`summ_q_C_${coTag}`).value) || 0;
      let mC = parseInt(document.getElementById(`summ_m_C_${coTag}`).value) || 0;

      let gather = (count, marks, prefix) => {
         let questions = [];
         for(let i=0; i<count; i++) {
            let elQ = document.getElementById(`man_q_${prefix}_${coTag}_${i}`);
            let elL = document.getElementById(`man_lvl_${prefix}_${coTag}_${i}`);
            if(elQ) questions.push({ q: elQ.value, level: elL.value, marks: marks });
         }
         return { q_count: count, marks_per_q: marks, total_marks: count * marks, questions: questions };
      };

      saveSummativePatterns();

      fetch(`/api/classroom/${subjectId}/generate-summative-paper`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ 
           co_tag: coTag, 
           manual_mode: true,
           manual_part_a: gather(qA, mA, 'A'),
           manual_part_b: gather(qB, mB, 'B'),
           manual_part_c: gather(qC, mC, 'C')
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentSummativeTests[coTag] = data.data;
          loadCourseDetails(subjectId);
        } else alert(data.message);
      });
    }

    function generateSummativePaper(subjectId, coTag) {
      const isManual = document.querySelector(`input[name="summ_mode_${coTag}"]:checked`).value === 'manual';
      
      if(isManual) {
         if (document.getElementById(`manual_form_${coTag}`)) {
             saveManualSummativePaper(subjectId, coTag);
         } else {
             spawnManualFields(coTag);
         }
         return;
      }

      saveSummativePatterns();

      let partA = { q_count: document.getElementById(`summ_q_A_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_A_${coTag}`).value };
      let partB = { q_count: document.getElementById(`summ_q_B_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_B_${coTag}`).value };
      let partC = { q_count: document.getElementById(`summ_q_C_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_C_${coTag}`).value };

      fetch(`/api/classroom/${subjectId}/generate-summative-paper`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, part_a: partA, part_b: partB, part_c: partC })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentSummativeTests[coTag] = data.data;
          // Soft re-render the whole summative tab using existing data
          // We don't have cos/students cached globally in a variable cleanly, so let's reload.
          loadCourseDetails(subjectId);
        } else alert(data.message);
      });
    }

    function loadActiveOnlineTests(subjectId) {
      fetch(`/api/classroom/${subjectId}/active-online-tests`)
        .then(res => res.json())
        .then(data => {
          let listDiv = document.getElementById('activeOnlineTestsList');
          if (!listDiv) return;
          if (data.status === 'SUCCESS' && data.data && data.data.length > 0) {
            let html = '';
            data.data.forEach(t => {
              html += `
                <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 mb-3 shadow-2xs">
                  <div class="flex justify-between items-start mb-1.5">
                    <h6 class="font-bold text-purple-700 text-xs">${t.test_name}</h6>
                    <span class="bg-purple-50 text-purple-700 border border-purple-200 px-2 py-0.5 rounded-md text-xs font-bold">${t.duration} Mins</span>
                  </div>
                  <div class="text-xs text-slate-600 mb-3 leading-relaxed">
                    Start: ${t.start_time ? new Date(t.start_time).toLocaleString() : 'Now'}<br>
                    Live Students: <span class="text-emerald-700 font-bold">${t.student_count || 0}</span> | Completed: <span class="text-blue-700 font-bold">${t.completed_count || 0}</span>
                  </div>
                  <div class="grid grid-cols-2 gap-2 mt-2">
                      <button onclick="generateOnlineTestReport('${t.test_id}')" class="w-full py-1.5 bg-white hover:bg-slate-50 text-slate-700 rounded-lg border border-slate-200 flex items-center justify-center gap-1 text-xs font-semibold shadow-2xs transition-premium cursor-pointer" title="Download Results">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Report
                      </button>
                      <button onclick="printOnlineTest('${t.test_id}')" class="w-full py-1.5 bg-white hover:bg-slate-50 text-slate-700 rounded-lg border border-slate-200 flex items-center justify-center gap-1 text-xs font-semibold shadow-2xs transition-premium cursor-pointer" title="Print Question Paper with Answers">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Q&A
                      </button>
                      <button onclick="deleteOnlineTest('${t.test_id}', '${subjectId}')" class="col-span-2 w-full py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg border border-rose-200 flex items-center justify-center gap-1 text-xs font-semibold shadow-2xs transition-premium cursor-pointer" title="Delete Test">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg> Delete
                      </button>
                    </div>
                </div>
              `;
            });
            listDiv.innerHTML = html;
          } else {
            listDiv.innerHTML = `<div class="p-4 bg-white border border-slate-200 rounded-xl text-center border-dashed text-slate-500 font-medium text-xs">No active online tests found.</div>`;
          }
        });
    }

    function publishOnlineTest(subjectId) {
      const selectElement = document.getElementById('online_test_cos');
      const selectedCos = Array.from(selectElement.selectedOptions).map(opt => opt.value);
      const attempts = document.getElementById('online_test_attempts').value;
      const duration = document.getElementById('online_test_duration').value;
      const start = document.getElementById('online_test_start').value;
      const end = document.getElementById('online_test_end').value;
      const q_count = document.getElementById('online_test_q_count').value;
      const gen_mode = document.getElementById('online_test_gen_mode').value;

      if (selectedCos.length === 0) {
        alert("Please select at least one CO.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/publish-online-test`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ cos: selectedCos, attempts, duration, start, end, q_count, generation_mode: gen_mode })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert("Online Test successfully published!");
          loadActiveOnlineTests(subjectId);
          
