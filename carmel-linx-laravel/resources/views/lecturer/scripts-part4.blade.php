      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentQuestions[currentEditCo] = questions;
          renderAIQuestionsList(currentQuestions, currentEditSubjectId);
          closeEditQuestionsModal();
          alert("Questions saved successfully.");
        } else {
          alert(data.message);
        }
      })
      .catch(() => alert("Failed to save assignment questions."));
    }

    function saveAssignmentMarks(subjectId) {
      let marksPayload = [];
      const rows = document.querySelectorAll('#markEntryTbody tr[data-reg]');
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const inputs = row.querySelectorAll('.co-mark');
        inputs.forEach(input => {
          if (input.value !== '') {
            marksPayload.push({
              reg_no: regNo,
              co_tag: input.getAttribute('data-co'),
              marks_obtained: input.value
            });
          }
        });
      });

      if (marksPayload.length === 0) {
        alert("No marks entered.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/save-assignment-marks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ marks: marksPayload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') alert("Marks successfully saved!");
        else alert(data.message || "Failed to save marks.");
      });
    }

    function updateProposedDate(lessonPlanId, dateValue) {
        console.log("Updating lesson plan", lessonPlanId, "with date", dateValue);
    }

    function renderCourseStructure(cos, modules, textbooks, copo) {
      // Filter out empty/blank COs and modules to show only populated ones
      if (cos && Array.isArray(cos)) {
        cos = cos.filter(co => co && co.description && co.description.trim() !== '' && co.description.trim() !== 'null');
      }
      if (modules && Array.isArray(modules)) {
        modules = modules.filter(m => m && m.content && m.content.trim() !== '' && m.content.trim() !== 'null');
      }

      // Debug: log what we received
      console.log('[renderCourseStructure] cos:', cos ? cos.length : 'null', '| modules:', modules ? modules.length : 'null', '| textbooks:', textbooks ? textbooks.length : 'null', '| copo keys:', copo ? Object.keys(copo).length : 'null');
      const container = document.getElementById('courseStructureContent');
      if (!container) { console.error('[renderCourseStructure] courseStructureContent not found!'); return; }

      let html = `
        <div class="flex items-center justify-between gap-3 mb-5 p-4 bg-white border border-slate-200/80 rounded-2xl shadow-xs">
          <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </span>
            <div>
              <h3 class="font-bold text-slate-900 text-sm">Course Structure &amp; Syllabus Elements</h3>
              <p class="text-xs text-slate-500">Extracted syllabus specifications and mapping matrices</p>
            </div>
          </div>
          <div class="flex flex-wrap gap-2 text-xs font-bold">
            <span class="bg-blue-50 border border-blue-200 text-blue-700 px-2.5 py-1 rounded-lg">${cos ? cos.length : 0} COs</span>
            <span class="bg-purple-50 border border-purple-200 text-purple-700 px-2.5 py-1 rounded-lg">${modules ? modules.length : 0} Modules</span>
            <span class="bg-amber-50 border border-amber-200 text-amber-700 px-2.5 py-1 rounded-lg">${textbooks ? textbooks.length : 0} Textbooks</span>
          </div>
        </div>
      `;

      if (cos && cos.length > 0) {
        let cosList = cos.map(co => {
          let cog = (co.cognitive_level || 'Apply').toLowerCase();
          let badgeClasses = 'bg-emerald-50 text-emerald-700 border-emerald-200';
          if (cog.includes('understand')) badgeClasses = 'bg-blue-50 text-blue-700 border-blue-200';
          else if (cog.includes('analy') || cog.includes('eval')) badgeClasses = 'bg-purple-50 text-purple-700 border-purple-200';

          return `
          <div class="p-4 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:border-blue-300 transition-all space-y-2">
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-bold font-mono text-xs border border-blue-200">${co.id}</span>
                ${co.duration ? `<span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-semibold text-xs border border-slate-200">${co.duration} hrs</span>` : ''}
              </div>
              <span class="px-2 py-0.5 rounded-md font-semibold text-xs border ${badgeClasses}">${co.cognitive_level || 'Apply'}</span>
            </div>
            <p class="text-sm font-medium text-slate-800 leading-relaxed">${co.description}</p>
          </div>
        `}).join('');

        html += `
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs mb-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </span>
                <h3 class="font-bold text-slate-900 text-sm">Course Outcomes (COs)</h3>
              </div>
              <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">${cos.length} Outcomes</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              ${cosList}
            </div>
          </div>
        `;
      }

      if (copo && Object.keys(copo).length > 0) {
        let copoList = Object.keys(copo).map(coKey => {
            let mapping = copo[coKey];
            let poCells = '';
            for(let i = 1; i <= 12; i++) {
                let val = mapping['PO' + i] || '-';
                let cellClass = 'text-slate-400 font-normal';
                if (val == '3') cellClass = 'font-bold text-emerald-700 bg-emerald-50/60';
                else if (val == '2') cellClass = 'font-bold text-blue-700 bg-blue-50/60';
                else if (val == '1') cellClass = 'font-semibold text-slate-700 bg-slate-50';
                poCells += `<td class="p-2.5 text-center font-mono text-sm ${cellClass}">${val}</td>`;
            }
            return `
              <tr class="hover:bg-slate-50/80 transition-all">
                <td class="p-3 text-left font-bold text-blue-700 pl-4 font-mono">${coKey}</td>
                ${poCells}
              </tr>
            `;
        }).join('');
        
        let poHeaders = '';
        for(let i=1; i<=12; i++) {
            poHeaders += `<th class="p-2.5 text-center font-mono text-xs">PO${i}</th>`;
        }

        html += `
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs mb-5 space-y-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center text-sm font-bold border border-indigo-200/80">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/></svg>
                </span>
                <div>
                  <h3 class="font-bold text-slate-900 text-sm">CO-PO Articulation Matrix</h3>
                  <p class="text-xs text-slate-500">Mapping correlation: 3 = High, 2 = Medium, 1 = Low</p>
                </div>
              </div>
              <div class="flex items-center gap-3 text-xs font-medium text-slate-600">
                <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold flex items-center justify-center text-[10px]">3</span> High</span>
                <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-blue-100 text-blue-800 border border-blue-300 font-bold flex items-center justify-center text-[10px]">2</span> Med</span>
                <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-slate-100 text-slate-700 border border-slate-300 font-bold flex items-center justify-center text-[10px]">1</span> Low</span>
              </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse text-sm">
                  <thead>
                    <tr class="bg-slate-50 text-slate-700 font-bold text-xs uppercase border-b border-slate-200">
                      <th class="p-3 text-left pl-4 w-20">CO</th>
                      ${poHeaders}
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    ${copoList}
                  </tbody>
                </table>
            </div>
          </div>
        `;
      }

      // Render Modules section
      if (modules && modules.length > 0) {
        let modulesList = modules.map((m, idx) => `
          <div class="p-4 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:border-purple-300 transition-all space-y-2">
            <div class="flex items-center justify-between gap-2 border-b border-slate-200/60 pb-2">
              <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-200 text-xs font-bold">Module ${m.module_id || (idx + 1)}</span>
                <span>${m.title || 'Unit ' + (m.module_id || (idx + 1))}</span>
              </h4>
              ${m.hours ? `<span class="px-2.5 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 font-bold text-xs font-mono shadow-2xs">${m.hours} Hours</span>` : ''}
            </div>
            <p class="text-sm font-normal text-slate-700 leading-relaxed whitespace-pre-line pt-1">${m.content || ''}</p>
          </div>
        `).join('');

        html += `
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs mb-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold border border-purple-200/80">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 2v20"/><path d="M10 2v10l3-2.5 3 2.5V2"/></svg>
                </span>
                <h3 class="font-bold text-slate-900 text-sm">Course Modules / Units</h3>
              </div>
              <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">${modules.length} Modules</span>
            </div>
            <div class="space-y-3">
              ${modulesList}
            </div>
          </div>
        `;
      }

      // Render Textbooks section
      if (textbooks && textbooks.length > 0) {
        let textbooksList = textbooks.map((tb, idx) => `
          <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:border-amber-300 transition-all">
            <span class="flex-shrink-0 w-6 h-6 rounded-lg bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-700 text-xs font-bold mt-0.5">${idx + 1}</span>
            <p class="text-sm font-medium text-slate-800 leading-relaxed">${tb}</p>
          </div>
        `).join('');

        html += `
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs mb-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-bold border border-amber-200/80">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </span>
                <h3 class="font-bold text-slate-900 text-sm">Textbooks &amp; References</h3>
              </div>
              <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">${textbooks.length} Books</span>
            </div>
            <div class="space-y-2.5">
              ${textbooksList}
            </div>
          </div>
        `;
      }

      if (html === '') {
        html = `<div class="p-6 text-center text-sm text-slate-500 border border-dashed border-slate-200 rounded-2xl bg-white">Could not extract structured data. The syllabus might have an unparseable format.</div>`;
      }

      console.log('[renderCourseStructure] Writing HTML to courseStructureContent, length:', html.length);
      container.innerHTML = html;
    }

    function renderSummativeAssessment(cos, students) {
      let html = `
        <div class="flex items-center justify-between mb-4 no-print">
          <div>
            <h4 class="text-base font-bold text-slate-900">Summative Assessment (Manual Tests)</h4>
            <p class="text-sm text-slate-600 mt-0.5">Configure and generate precise Cognitive Level based question papers for each CO.</p>
          </div>
        </div>
      `;

      // Build the marks entry table FIRST so it's at the top
      let marksEntryHtml = `
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs no-print mb-6">
          <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors" onclick="document.getElementById('manualMarksWrapper').classList.toggle('hidden'); const icon = document.getElementById('marksToggleIcon'); if (icon) icon.classList.toggle('rotate-180');">
            <div class="font-bold text-sm text-slate-800 flex items-center gap-2 tracking-wider uppercase">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg> Enter Manual Marks
              <svg id="marksToggleIcon" class="w-4 h-4 text-slate-500 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
            <div class="flex items-center gap-2">
              <button onclick="event.stopPropagation(); printSummativeReport('${currentSubjectId}')" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer shadow-xs">
                Print Written Report
              </button>
              <button onclick="event.stopPropagation(); saveSummativeMarks('${currentSubjectId}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer shadow-xs">
                Save Written Marks
              </button>
            </div>
          </div>
          <div id="manualMarksWrapper" class="hidden overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
              <thead>
                <tr class="bg-slate-50 text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
                  <th class="p-3 w-12">S.No.</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 w-28">Admission No</th>
                  <th class="p-3 w-32">SBTE Reg No</th>
                  <th class="p-3 text-center w-20">CO1</th>
                  <th class="p-3 text-center w-20">CO2</th>
                  <th class="p-3 text-center w-20">CO3</th>
                  <th class="p-3 text-center w-20">CO4</th>
                </tr>
              </thead>
              <tbody id="summativeMarkEntryTbody">
      `;

      if (students && students.length > 0) {
        students.forEach((student, index) => {
          let sm = student.summative_marks || {};
          marksEntryHtml += `
            <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/80 transition-colors text-sm text-slate-800" data-reg="${student.reg_no}">
              <td class="p-3 text-slate-700 font-bold">${index + 1}</td>
              <td class="p-3 font-bold text-slate-900">${student.name}</td>
              <td class="p-3 font-mono text-slate-600">${student.reg_no}</td>
              <td class="p-3 font-mono text-slate-600">${student.sbte_reg_no || '-'}</td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO1 !== null ? Math.round(sm.CO1) : ''}" placeholder="-" class="summ-mark w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm shadow-2xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-center" data-co="CO1"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO2 !== null ? Math.round(sm.CO2) : ''}" placeholder="-" class="summ-mark w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm shadow-2xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-center" data-co="CO2"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO3 !== null ? Math.round(sm.CO3) : ''}" placeholder="-" class="summ-mark w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm shadow-2xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-center" data-co="CO3"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO4 !== null ? Math.round(sm.CO4) : ''}" placeholder="-" class="summ-mark w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm shadow-2xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-center" data-co="CO4"></td>
            </tr>
          `;
        });
      } else {
        marksEntryHtml += `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm font-bold">No students found.</td></tr>`;
      }
      marksEntryHtml += `</tbody></table></div></div>`;

      html += marksEntryHtml;

      html += `
        <div id="summativePapersContainer" class="flex flex-col gap-6 mb-6 no-print">
      `;

      if (cos && cos.length > 0) {
        cos.forEach(co => {
          let testData = currentSummativeTests[co.id] || null;
          let generatedContent = '';
          
          if (testData) {
            let partAStr = testData.part_a ? testData.part_a.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-700 font-bold mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';
            let partBStr = testData.part_b ? testData.part_b.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-700 font-bold mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';
            let partCStr = testData.part_c ? testData.part_c.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-700 font-bold mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';

            generatedContent = `
              <div class="mt-4 pt-4 border-t border-slate-200" id="paper-${co.id}">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-sm font-bold text-emerald-700 uppercase tracking-widest">Generated Question Paper</span>
                  <div class="flex items-center gap-2">
                    <button onclick="printSummativePaper('${co.id}', ${testData.total_marks})" class="flex items-center gap-1.5 text-sm bg-blue-50 hover:bg-blue-600 border border-blue-200 hover:border-blue-600 px-3 py-1.5 rounded-lg text-blue-700 hover:text-white transition-premium cursor-pointer shadow-2xs">
                      <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Q Paper
                    </button>
                    <button onclick="printAnswerKey('${co.id}', ${testData.total_marks})" class="flex items-center gap-1.5 text-sm bg-amber-50 hover:bg-amber-600 border border-amber-200 hover:border-amber-600 px-3 py-1.5 rounded-lg text-amber-700 hover:text-white transition-premium cursor-pointer shadow-2xs">
                      <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> Print Answer Key
                    </button>
                  </div>
                </div>
                <div class="text-sm text-slate-800 bg-slate-50/70 p-5 rounded-xl border border-slate-200 shadow-2xs">
                  ${partAStr ? `<div class="font-bold mb-1.5 text-slate-700">PART A (Short Answers)</div><ul class="list-decimal pl-5 mb-4">${partAStr}</ul>` : ''}
                  ${partBStr ? `<div class="font-bold mb-1.5 text-slate-700">PART B (Medium Answers)</div><ul class="list-decimal pl-5 mb-4">${partBStr}</ul>` : ''}
                  ${partCStr ? `<div class="font-bold mb-1.5 text-slate-700">PART C (Long Answers)</div><ul class="list-decimal pl-5 mb-2">${partCStr}</ul>` : ''}
                </div>
              </div>
            `;
          }

          let isLocked = testData && testData.is_locked ? true : false;
          let disabledAttr = isLocked ? 'disabled' : '';
          let lockStr = isLocked ? `<svg class="w-3.5 h-3.5 inline text-amber-500 ml-1" title="Locked" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>` : '';
          let dateStr = testData && testData.date_of_exam ? testData.date_of_exam : '';

          let qA = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qA : (testData?.part_a?.q_count || '');
          let mA = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mA : (testData?.part_a?.marks_per_q || '');
          let qB = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qB : (testData?.part_b?.q_count || '');
          let mB = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mB : (testData?.part_b?.marks_per_q || '');
          let qC = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qC : (testData?.part_c?.q_count || '');
          let mC = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mC : (testData?.part_c?.marks_per_q || '');

          let lockBtn = isLocked || !testData ? '' : `
            <button onclick="lockSummativeTest('${currentSubjectId}', '${co.id}')" class="p-1.5 rounded-lg bg-white hover:bg-amber-50 text-slate-700 hover:text-amber-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Lock & Finalize">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </button>
          `;

          let genBtn = isLocked ? '' : `
              <button id="gen_btn_${co.id}" onclick="generateSummativePaper('${currentSubjectId}', '${co.id}')" class="w-full py-2.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white border border-blue-200 hover:border-blue-600 rounded-xl text-sm font-bold transition-premium mt-3 cursor-pointer shadow-2xs">
                ${testData ? 'Regenerate Question Paper' : 'Generate AI Question Paper'}
              </button>
          `;
          
          let dateInputStr = `
            <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
              <span class="text-xs text-slate-600 font-bold uppercase flex items-center gap-1"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>Date</span>
              <input type="date" id="summ_date_${co.id}" value="${dateStr}" ${disabledAttr} onchange="saveSummativeConfig('${currentSubjectId}', '${co.id}')" class="bg-white text-sm text-slate-800 font-mono outline-none w-[110px] px-2 py-0.5 rounded border border-slate-200 focus:border-blue-500 shadow-2xs">
            </div>
          `;

          html += `
            <div id="summ_card_${co.id}" class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs relative ${isLocked ? 'ring-1 ring-amber-500/30' : ''}">
              <div class="flex items-center justify-between mb-4 border-b border-slate-200 pb-3 cursor-pointer hover:opacity-80 transition-premium" onclick="document.getElementById('co_body_${co.id}').classList.toggle('hidden'); const icon = document.getElementById('co_icon_' + co.id); if (icon) icon.classList.toggle('rotate-180');">
                <h5 class="text-sm font-bold text-slate-900 flex items-center gap-1">
                  <svg id="co_icon_${co.id}" class="w-4 h-4 text-slate-500 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                  ${co.id} Written Test ${lockStr}
                </h5>
                <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                  ${dateInputStr}
                  ${lockBtn}
                </div>
              </div>
 
              <div id="co_body_${co.id}" class="hidden pt-2">
 
              <div class="flex items-center gap-4 mb-4 mt-1 text-sm font-bold text-slate-700 bg-slate-50 p-2 rounded-xl border border-slate-200 w-max shadow-2xs">
                 <label class="flex items-center gap-1.5 cursor-pointer hover:text-blue-600 transition-premium">
                   <input type="radio" name="summ_mode_${co.id}" value="ai" ${(!testData || !testData.manual_mode) ? 'checked' : ''} onchange="toggleSummativeMode('${co.id}')" class="text-blue-600 focus:ring-blue-500 bg-white border-slate-300" ${disabledAttr}>
                   AI Generation
                 </label>
                 <label class="flex items-center gap-1.5 cursor-pointer hover:text-emerald-600 transition-premium">
                   <input type="radio" name="summ_mode_${co.id}" value="manual" ${(testData && testData.manual_mode) ? 'checked' : ''} onchange="toggleSummativeMode('${co.id}')" class="text-emerald-600 focus:ring-emerald-500 bg-white border-slate-300" ${disabledAttr}>
                   Manual Entry
                 </label>
              </div>
              
              <div class="space-y-3 mb-4">
                <div class="flex items-center gap-3 text-xs text-slate-600 font-bold mb-1"><span class="w-24 shrink-0 whitespace-nowrap">Part</span><span class="flex-1 text-center">Q. Count</span><span class="w-4"></span><span class="flex-1 text-center">Marks/Q</span></div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-xs text-slate-800 font-bold w-24 shrink-0 whitespace-nowrap">PART A</span>
                  <input type="number" id="summ_q_A_${co.id}" value="${qA}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                  <span class="text-slate-400 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_A_${co.id}" value="${mA}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-xs text-slate-800 font-bold w-24 shrink-0 whitespace-nowrap">PART B</span>
                  <input type="number" id="summ_q_B_${co.id}" value="${qB}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                  <span class="text-slate-400 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_B_${co.id}" value="${mB}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-xs text-slate-800 font-bold w-24 shrink-0 whitespace-nowrap">PART C</span>
                  <input type="number" id="summ_q_C_${co.id}" value="${qC}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                  <span class="text-slate-400 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_C_${co.id}" value="${mC}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
              </div>

              <div class="flex items-center justify-between mb-4 border-t border-slate-100 pt-3">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-600 hover:text-slate-900 transition-colors font-medium">
                  <input type="checkbox" id="sync_pattern_${co.id}" ${disabledAttr} onchange="if(this.checked) applySummativePatternToAll('${co.id}')" class="rounded border-slate-300 bg-white text-blue-600 focus:ring-blue-500/30">
                  <span>Apply pattern to all COs</span>
                </label>
                <div class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                  Total Marks: <span id="summ_total_${co.id}" class="${testData ? 'text-emerald-600 font-black' : 'text-blue-600 font-black'}">${testData ? testData.total_marks : '0'}</span>
                </div>
              </div>
              
              ${genBtn}

              <div id="manual_form_wrapper_${co.id}"></div>

              ${generatedContent}
              </div> <!-- close co_body -->
            </div>
