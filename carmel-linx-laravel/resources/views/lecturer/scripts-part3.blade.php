        } else {
          alert(d.message || 'Regeneration failed.');
        }
      }).catch(e => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg> Regenerate'; }
        alert('Error: ' + e.message);
      });
    }

    function saveLessonPlanAsTemplate() {
      if (!confirm('Save the current lesson plan as a reusable template for all future batches of this subject?\n\nThis will overwrite any previously saved template for this subject code.')) return;
      const btn = document.getElementById('btnSavePlanTemplate');
      if (btn) { btn.disabled = true; }

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/save-as-template`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({})
      }).then(r => r.json()).then(d => {
        if (btn) { btn.disabled = false; }
        alert(d.status === 'SUCCESS' ? '✓ ' + d.message : '✗ ' + (d.message || 'Save failed.'));
      }).catch(e => {
        if (btn) { btn.disabled = false; }
        alert('Error: ' + e.message);
      });
    }

    function loadLessonPlanTemplate() {
      if (!confirm('Load the saved template for this subject?\n\nThis will replace the current lesson plan with the template. Existing proposed dates will be cleared.')) return;

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/load-template`, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      }).then(r => r.json()).then(d => {
        if (d.status === 'SUCCESS') {
          renderCoursePlanner(d.data);
          toggleClassroomTab('planner');
        } else {
          alert(d.message || 'No template found for this subject.');
        }
      }).catch(e => alert('Error: ' + e.message));
    }

    // Wire up updateProposedDate (was a stub) — now handled by autoSavePlanRow via onchange
    function updateProposedDate(lpId, dateValue) {
      const row = document.querySelector(`#lessonPlanTable tr[data-lp-id="${lpId}"]`);
      autoSavePlanRow(lpId, row);
    }


    function renderFormativeAssessment(students) {
      let html = `
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-sm text-slate-500 mt-1">Generate AI questions for each CO and record 10-mark evaluations.</p>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="printAssignmentReport('${currentSubjectId}')" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-blue-500/10 cursor-pointer">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Assignment Report
            </button>
            <button onclick="generateAIQuestions('${currentSubjectId}')" class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-blue-900/20 cursor-pointer">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg> AI Generate Questions
            </button>
            <button onclick="generateAIQuestions('${currentSubjectId}', null, 'bank')" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-500 hover:from-indigo-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-indigo-900/20 cursor-pointer">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg> Pull from Question Bank
            </button>
          </div>
        </div>

        <div id="aiQuestionsContainer" class="grid-cols-1 md:grid-cols-2 gap-4 mb-6" style="display:none;"></div>

        <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-xs">
          <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="font-bold text-sm text-slate-800 flex items-center gap-2 tracking-wide uppercase">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg> Enter Assignment Marks
            </div>
            <button onclick="saveAssignmentMarks('${currentSubjectId}')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer">
              Save Marks
            </button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
              <thead>
                <tr class="bg-slate-50 text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
                  <th class="p-3 w-12">S.No.</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 w-28">Admission No</th>
                  <th class="p-3 w-32">SBTE Reg No</th>
                  <th class="p-3 text-center w-20">CO1 (20)</th>
                  <th class="p-3 text-center w-20">CO2 (20)</th>
                  <th class="p-3 text-center w-20">CO3 (20)</th>
                  <th class="p-3 text-center w-20">CO4 (20)</th>
                </tr>
              </thead>
              <tbody id="markEntryTbody">
      `;

      if (students && students.length > 0) {
        students.forEach((student, index) => {
          let m = student.assignment_marks || {};
          let sub = student.assignment_submissions || {};

          const getInputHtml = (co, val) => {
            let isSubmitted = (sub[co] === 'Submitted');
            let isGraded = val !== null && val !== '';
            let styleClasses = "co-mark w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-slate-900 text-base font-bold shadow-2xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-center ";
            let indicator = "";

            if (isGraded) {
              styleClasses += "border-slate-200 focus:border-blue-500";
            } else if (isSubmitted) {
              // Highlight input field with an amber border and a pulsing indicator dot
              styleClasses += "border-amber-500/70 bg-amber-950/20 focus:border-amber-400";
              indicator = `<span class="absolute right-2 top-1.5 flex h-2 w-2" title="Submitted by student"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span></span>`;
            } else {
              styleClasses += "border-slate-700/60 focus:border-blue-400";
            }

            return `
              <div class="relative">
                <input type="number" step="1" max="20" min="0" value="${val !== null ? Math.round(val) : ''}" 
                       class="${styleClasses}" data-co="${co}">
                ${indicator}
              </div>
            `;
          };

          html += `
            <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/80 transition-colors text-slate-800" data-reg="${student.reg_no}">
              <td class="px-4 py-4 text-slate-700 font-bold text-base text-center">${index + 1}</td>
              <td class="px-4 py-4 font-bold text-slate-900 text-base tracking-wide">${student.name}</td>
              <td class="px-4 py-4 font-mono text-slate-700 text-sm font-semibold">${student.reg_no}</td>
              <td class="px-4 py-4 font-mono text-slate-700 text-sm font-semibold">${student.sbte_reg_no || '-'}</td>
              <td class="px-3 py-3">${getInputHtml('CO1', m.CO1)}</td>
              <td class="px-3 py-3">${getInputHtml('CO2', m.CO2)}</td>
              <td class="px-3 py-3">${getInputHtml('CO3', m.CO3)}</td>
              <td class="px-3 py-3">${getInputHtml('CO4', m.CO4)}</td>
            </tr>
          `;
        });
      } else {
        html += `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm font-bold">No students found in this classroom.</td></tr>`;
      }

      html += `</tbody></table></div></div>`;
      document.getElementById('formativeAssessmentContent').innerHTML = html;
    }

    function renderAIQuestionsList(questionsData, subjectId) {
      const container = document.getElementById('aiQuestionsContainer');
      container.style.display = 'grid';
      let html = '';
      
      // Show empty-state prompt if no questions have been generated yet
      if (!questionsData || Object.keys(questionsData).length === 0) {
        container.innerHTML = `
          <div class="col-span-full flex flex-col items-center justify-center py-12 text-center bg-slate-50/60 border border-dashed border-slate-300 rounded-2xl shadow-2xs">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
            <p class="font-bold text-slate-800 text-sm mb-1">No Assignment Questions Yet</p>
            <p class="text-xs text-slate-600 mb-4">Click <strong>AI Generate Questions</strong> above to generate questions for all Course Outcomes using Gemini AI.</p>
          </div>
        `;
        return;
      }

      for (const [co, qs] of Object.entries(questionsData)) {
        let qList = qs.map(q => {
          let qText = typeof q === 'object' ? q.question : q;
          let bt = typeof q === 'object' ? q.bt_level : null;
          let marksVal = typeof q === 'object' ? q.marks : null;
          
          let cog = '';
          if (bt) {
            let color = bt.toLowerCase() === 'remember' ? 'text-blue-400' : (bt.toLowerCase() === 'apply' ? 'text-emerald-400' : 'text-indigo-400');
            cog = ` <span class="${color} font-bold">[${bt}]</span>`;
          } else {
            let lower = qText.toLowerCase();
            if (!lower.includes('[remember]') && !lower.includes('[u]') && !lower.includes('[a]') && !lower.includes('[r]') && !lower.includes('cognitive')) {
              if (lower.includes('define') || lower.includes('list') || lower.includes('what is') || lower.includes('state') || lower.includes('name')) {
                cog = ' <span class="text-blue-400 font-bold">[Remember - R]</span>';
              } else if (lower.includes('design') || lower.includes('solve') || lower.includes('calculate') || lower.includes('write') || lower.includes('implement') || lower.includes('apply') || lower.includes('draw')) {
                cog = ' <span class="text-emerald-400 font-bold">[Apply - A]</span>';
              } else {
                cog = ' <span class="text-indigo-400 font-bold">[Understand - U]</span>';
              }
            }
          }
          let marksText = marksVal ? ` <span class="text-slate-500 font-bold">(${marksVal} Marks)</span>` : '';
          return `<li class="text-sm text-slate-800 mb-2 leading-relaxed font-medium">${qText}${cog}${marksText}</li>`;
        }).join('');
        let schedule = currentDeadlines[co] || { start: '', due: '', locked: false };
        if (typeof schedule === 'string') schedule = { start: '', due: schedule, locked: false }; // Legacy fallback
        
        let isLocked = schedule.locked;
        let lockStr = isLocked ? `<svg class="w-3.5 h-3.5 inline text-amber-500 ml-1" title="Locked" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>` : '';
        let disabledAttr = isLocked ? 'disabled' : '';
        let regenBtn = isLocked ? '' : `
                <button onclick="generateAIQuestions('${subjectId}', '${co}', 'ai')" class="p-1.5 rounded-lg bg-white hover:bg-blue-50 text-slate-700 hover:text-blue-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Generate via AI (Gemini)">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
                </button>
                <button onclick="generateAIQuestions('${subjectId}', '${co}', 'bank')" class="p-1.5 rounded-lg bg-white hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Pull from Question Bank Pool">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
                </button>
        `;
        let editBtn = isLocked ? '' : `
                <button onclick="openEditQuestionsModal('${subjectId}', '${co}')" class="p-1.5 rounded-lg bg-white hover:bg-amber-50 text-slate-700 hover:text-amber-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Manually Edit Questions">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                </button>
        `;
        let lockBtn = isLocked ? '' : `
                <button onclick="toggleAssignmentLock('${subjectId}', '${co}')" class="p-1.5 rounded-lg bg-white hover:bg-amber-50 text-slate-700 hover:text-amber-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Lock & Finalize">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </button>
        `;
        let printBtn = `
                <button onclick="printAssignmentPaperAndRubrics('${subjectId}', '${co}')" class="p-1.5 rounded-lg bg-white hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Print Assignment & Rubrics">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                </button>
        `;

        html += `
          <div class="bg-white border border-slate-200/80 p-5 rounded-2xl relative overflow-hidden group shadow-xs ${isLocked ? 'ring-1 ring-amber-500/30' : ''}">
            <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3 relative z-10">
              <h5 class="text-sm font-bold text-slate-900 flex items-center gap-1">
                <span class="px-2 py-0.5 rounded-md bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold mr-1">${co}</span> Assignment ${lockStr}
              </h5>
              <div class="flex items-center gap-2">
                <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">
                  <span class="text-xs text-slate-600 font-bold uppercase">Start</span>
                  <input type="date" value="${schedule.start || ''}" ${disabledAttr} class="bg-white text-xs text-slate-800 font-mono outline-none w-24 rounded border border-slate-200 px-1 py-0.5 shadow-2xs" onchange="updateAssignmentSchedule('${subjectId}', '${co}', 'start', this.value)">
                </div>
                <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">
                  <span class="text-xs text-slate-600 font-bold uppercase">Due</span>
                  <input type="date" value="${schedule.due || ''}" ${disabledAttr} class="bg-white text-xs text-slate-800 font-mono outline-none w-24 rounded border border-slate-200 px-1 py-0.5 shadow-2xs" onchange="updateAssignmentSchedule('${subjectId}', '${co}', 'due', this.value)">
                </div>
                ${regenBtn}
                ${editBtn}
                ${lockBtn}
                ${printBtn}
              </div>
            </div>
            
            <ul id="questions-list-${co}" class="list-none m-0 p-0 relative z-10 min-h-[60px] divide-y divide-slate-100">${qList}</ul>
          </div>
        `;
      }
      document.getElementById('aiQuestionsContainer').innerHTML = html;
    }

    function generateAIQuestions(subjectId, coTag = null, mode = 'ai') {
      const qContainer = document.getElementById('aiQuestionsContainer');
      if (!coTag) {
        qContainer.style.display = 'grid';
        qContainer.innerHTML = `<div class="col-span-full text-center py-10 text-sm font-bold text-blue-400 animate-pulse flex flex-col items-center gap-3"><div class="w-8 h-8 border-2 border-blue-500/40 border-t-blue-400 rounded-full animate-spin"></div>Generating AI questions for all Course Outcomes...</div>`;
      } else {
        qContainer.style.display = 'grid';
        const ul = document.getElementById(`questions-list-${coTag}`);
        if(ul) ul.innerHTML = `<li class="text-sm text-blue-400 animate-pulse">Generating via Gemini AI...</li>`;
      }
      
      let url = `/api/classroom/${subjectId}/generate-questions?_t=${Date.now()}&generation_mode=${mode}`;
      if (coTag) url += `&co_tag=${coTag}`;

      fetch(url)
      .then(res => {
        if (!res.ok) throw new Error('Server error: ' + res.status);
        return res.json();
      })
      .then(data => {
        if (data.status === 'SUCCESS') {
          if (!coTag) {
             currentQuestions = data.data;
             renderAIQuestionsList(currentQuestions, subjectId);
          } else {
             currentQuestions[coTag] = data.data[coTag];
             const ul = document.getElementById(`questions-list-${coTag}`);
             if (ul && data.data[coTag]) {
               ul.innerHTML = data.data[coTag].map(q => `<li class="text-sm text-slate-400 mb-1 leading-relaxed">${q}</li>`).join('');
             }
          }
        } else {
           qContainer.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900/40 rounded-xl text-sm font-bold">${data.message || 'Generation failed.'}</div>`;
        }
      })
      .catch(err => {
        console.error('AI Generate Error:', err);
        qContainer.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900/40 rounded-xl text-sm font-bold">Generation failed: ${err.message}. Check your API key and internet connection.</div>`;
      });
    }

    function updateAssignmentSchedule(subjectId, coTag, type, dateValue) {
      let payload = { co_tag: coTag };
      if (type === 'start') payload.start_date = dateValue;
      if (type === 'due') payload.due_date = dateValue;
      
      fetch(`/api/classroom/${subjectId}/save-assignment-deadline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
           if(!currentDeadlines[coTag] || typeof currentDeadlines[coTag] === 'string') currentDeadlines[coTag] = {start:'', due:'', locked:false};
           if (type === 'start') currentDeadlines[coTag].start = dateValue;
           if (type === 'due') currentDeadlines[coTag].due = dateValue;
           console.log(`Schedule for ${coTag} updated.`);
        } else {
           alert(data.message);
        }
      });
    }

    function toggleAssignmentLock(subjectId, coTag) {
      if(!confirm(`Are you sure you want to lock ${coTag} questions? This cannot be easily undone.`)) return;
      
      fetch(`/api/classroom/${subjectId}/save-assignment-deadline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, is_locked: true })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
           if(!currentDeadlines[coTag] || typeof currentDeadlines[coTag] === 'string') currentDeadlines[coTag] = {start:'', due:'', locked:false};
           currentDeadlines[coTag].locked = true;
           renderAIQuestionsList(currentQuestions, subjectId);
        } else {
           alert(data.message);
        }
      });
    }

    let currentEditCo = '';
    let currentEditSubjectId = '';

    function openEditQuestionsModal(subjectId, coTag) {
      currentEditCo = coTag;
      currentEditSubjectId = subjectId;
      document.getElementById('editQuestionsCoBadge').innerText = coTag;
      
      const container = document.getElementById('editQuestionsFieldsContainer');
      container.innerHTML = '';

      let qs = currentQuestions[coTag] || [];
      if (qs.length === 0) {
        addManualQuestionField();
      } else {
        qs.forEach(q => {
          let qText = typeof q === 'object' ? q.question : q;
          let bt = typeof q === 'object' ? q.bt_level : 'Understand';
          let marksVal = typeof q === 'object' ? q.marks : 5;
          addManualQuestionField(qText, bt, marksVal);
        });
      }

      updateEditQuestionsTotalMarks();
      
      const modal = document.getElementById('editQuestionsModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeEditQuestionsModal() {
      const modal = document.getElementById('editQuestionsModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function addManualQuestionField(question = '', btLevel = 'Understand', marks = 5) {
      const container = document.getElementById('editQuestionsFieldsContainer');
      const div = document.createElement('div');
      div.className = "p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3 relative question-field-row shadow-2xs";
      
      div.innerHTML = `
        <div class="flex justify-between items-center">
          <span class="text-xs font-bold text-slate-700 uppercase tracking-wide">Question</span>
          <button type="button" onclick="this.closest('.question-field-row').remove(); updateEditQuestionsTotalMarks();" class="text-rose-500 hover:text-rose-700 cursor-pointer transition-colors">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
          </button>
        </div>
        <div>
          <textarea class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs resize-y q-text" rows="2" placeholder="Type question description..." required>${question}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-xs font-bold text-slate-600 uppercase block mb-1">BT Level</label>
            <select class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-800 focus:border-blue-500 shadow-2xs outline-none q-bt">
              <option value="Remember" ${btLevel === 'Remember' ? 'selected' : ''}>Remember</option>
              <option value="Understand" ${btLevel === 'Understand' ? 'selected' : ''}>Understand</option>
              <option value="Apply" ${btLevel === 'Apply' ? 'selected' : ''}>Apply</option>
            </select>
          </div>
          <div>
            <label class="text-xs font-bold text-slate-600 uppercase block mb-1">Marks</label>
            <input type="number" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-900 focus:border-blue-500 shadow-2xs outline-none q-marks" value="${marks}" min="1" max="20" onchange="updateEditQuestionsTotalMarks()" required>
          </div>
        </div>
      `;
      container.appendChild(div);
      updateEditQuestionsTotalMarks();
    }

    function updateEditQuestionsTotalMarks() {
      let sum = 0;
      const inputs = document.querySelectorAll('#editQuestionsFieldsContainer .q-marks');
      inputs.forEach(input => {
        sum += parseInt(input.value || 0);
      });
      document.getElementById('editQuestionsTotalMarks').innerText = sum;
    }

    function saveManualQuestions() {
      const rows = document.querySelectorAll('#editQuestionsFieldsContainer .question-field-row');
      let questions = [];
      let totalMarks = 0;

      rows.forEach(row => {
        const text = row.querySelector('.q-text').value.trim();
        const bt = row.querySelector('.q-bt').value;
        const marks = parseInt(row.querySelector('.q-marks').value || 0);
        
        if (text) {
          questions.push({
            question: text,
            bt_level: bt,
            marks: marks
          });
          totalMarks += marks;
        }
      });

      if (questions.length === 0) {
        alert("Please add at least one question.");
        return;
      }

      if (totalMarks !== 20) {
        if (!confirm(`Warning: Total marks allocated is ${totalMarks}. The target is exactly 20 marks. Do you want to proceed anyway?`)) {
          return;
        }
      }

      fetch(`/api/classroom/${currentEditSubjectId}/save-assignment-questions`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
          co_tag: currentEditCo,
          questions: questions
        })
      })
