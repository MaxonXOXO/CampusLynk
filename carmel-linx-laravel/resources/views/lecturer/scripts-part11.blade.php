      const dates = classReportsData.dates || [];
      const matrix = classReportsData.matrix || [];

      if (dates.length === 0 || matrix.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No attendance summary available.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-200/80 rounded-xl bg-white shadow-xs max-h-[500px]">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-50 text-slate-700 border-b border-slate-200 uppercase tracking-wider text-xs font-bold sticky top-0 z-10">
                <th class="p-4 bg-slate-50 text-slate-700 w-16 text-center sticky left-0 z-20">Roll</th>
                <th class="p-4 bg-slate-50 text-slate-700 w-44 sticky left-16 z-20">Name</th>
      `;

      dates.forEach(d => {
        const shortDate = d.date.substring(5);
        html += `<th class="p-4 text-center min-w-[70px]">${shortDate}<br><span class="text-[10px] text-slate-500">P${d.period}</span></th>`;
      });

      html += `
              </tr>
            </thead>
            <tbody>
      `;

      matrix.forEach(row => {
        html += `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-colors text-slate-800">
            <td class="p-4 text-center font-bold text-slate-600 bg-white sticky left-0 z-10">${row.roll_no || '-'}</td>
            <td class="p-4 font-bold text-slate-900 bg-white sticky left-16 z-10 truncate max-w-[176px]">${row.name}</td>
        `;

        dates.forEach(d => {
          const key = d.date + ' | P' + d.period;
          const status = row.attendance[key] || '-';
          let cellClass = 'text-slate-500';
          if (status === 'P') cellClass = 'text-emerald-400 font-bold';
          if (status === 'A') cellClass = 'text-rose-400 font-bold';

          html += `<td class="p-4 text-center ${cellClass}">${status}</td>`;
        });

        html += `</tr>`;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function fetchQuestionBank(subjectId) {
      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-10">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Loading Question Bank...</p>
        </div>
      `;

      fetch(`/api/classroom/${subjectId}/question-bank`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          renderQuestionBank(data.questions);
        } else {
          container.innerHTML = `<div class="text-sm text-rose-400 py-6 text-center font-bold">Failed to load questions.</div>`;
        }
      })
      .catch(err => {
        container.innerHTML = `<div class="text-sm text-rose-400 py-6 text-center font-bold">Error loading questions.</div>`;
      });
    }

    function renderQuestionBank(questions) {
      const container = document.getElementById('qbankCoGroups');
      if (!questions || questions.length === 0) {
        container.innerHTML = `
          <div class="text-center py-12 text-slate-400 space-y-4 max-w-md mx-auto">
            <div class="bg-white p-4 rounded-full border border-slate-200 shadow-2xs inline-block">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
            </div>
            <p class="text-sm font-bold text-slate-700">No questions in this subject's pool.</p>
            <p class="text-sm text-slate-500">You can download the template CSV, fill it with questions, and upload it. Alternatively, seed the pool instantly with high-quality questions using AI.</p>
            <div class="pt-2">
              <button onclick="seedQuestionBankWithAi(currentSubjectId)" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer shadow-md flex items-center gap-1.5 mx-auto">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg> Seed Pool via AI
              </button>
            </div>
          </div>
        `;
        return;
      }

      const markGroups = {
        '1 Mark Questions': [],
        '3 Mark Questions': [],
        '7 Mark Questions': [],
        'Other Marks': []
      };

      questions.forEach(q => {
        const marks = parseInt(q.marks || 0);
        if (marks === 1) {
          markGroups['1 Mark Questions'].push(q);
        } else if (marks === 3) {
          markGroups['3 Mark Questions'].push(q);
        } else if (marks === 7) {
          markGroups['7 Mark Questions'].push(q);
        } else {
          markGroups['Other Marks'].push(q);
        }
      });

      let html = '';
      Object.keys(markGroups).forEach(groupName => {
        const qList = markGroups[groupName];
        if (qList.length === 0) return;

        html += `
          <div class="border border-slate-200/80 rounded-xl overflow-hidden bg-white shadow-xs mb-6">
            <div class="bg-slate-50/70 p-4 flex justify-between items-center border-b border-slate-200">
              <div class="flex items-center gap-2">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span class="text-sm font-bold text-slate-900">${groupName}</span>
              </div>
              <span class="text-xs text-slate-600 font-bold bg-white border border-slate-200 px-2.5 py-1 rounded-lg shadow-2xs">${qList.length} Questions</span>
            </div>
            <div class="p-0 overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="border-b border-slate-200 text-slate-700 text-xs font-bold bg-slate-50 uppercase tracking-wider">
                    <th class="py-2.5 px-4 font-bold w-12 text-center">#</th>
                    <th class="py-2.5 px-4 font-bold">Question Text</th>
                    <th class="py-2.5 px-4 font-bold w-20 text-center">CO Tag</th>
                    <th class="py-2.5 px-4 font-bold w-28 text-center">Cognitive</th>
                    <th class="py-2.5 px-4 font-bold w-28 text-center">Type</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-800">
        `;

        qList.forEach((q, index) => {
          const typeStr = q.type === 'MCQ' ? `MCQ (Ans: ${q.correct_answer || 'N/A'})` : 'Descriptive';
          html += `
            <tr class="hover:bg-slate-50/80 transition-colors text-sm">
              <td class="py-3 px-4 text-center text-slate-500 font-mono">${index + 1}</td>
              <td class="py-3 px-4 font-semibold text-slate-900">
                <div>${q.question_text}</div>
                ${q.type === 'MCQ' && q.options ? renderCompactOptions(q.options, q.correct_answer) : ''}
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 font-mono text-[11px] font-bold">${q.co_tag || 'CO1'}</span>
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 text-[11px] font-bold">${q.cognitive_level || 'Understand'}</span>
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2.5 py-1 rounded-lg ${q.type === 'MCQ' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-700 border border-slate-200'} text-xs font-semibold shadow-2xs">${typeStr}</span>
              </td>
            </tr>
          `;
        });

        html += `
                </tbody>
              </table>
            </div>
          </div>
        `;
      });

      if (!html) {
        html = `<div class="text-sm text-slate-500 py-8 text-center">No grouped questions found.</div>`;
      }

      container.innerHTML = html;
    }

    function renderCompactOptions(optionsStr, correctAns) {
      const options = typeof optionsStr === 'string' ? JSON_decode_safe(optionsStr) : optionsStr;
      if (!options || options.length === 0) return '';
      const labels = ['A', 'B', 'C', 'D'];
      let optHtml = '<div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-xs text-slate-500 font-medium pl-2 border-l border-slate-200">';
      options.forEach((opt, idx) => {
        if (!opt) return;
        const isCorrect = correctAns === labels[idx] || correctAns === opt;
        const colorClass = isCorrect ? 'text-emerald-400 font-bold' : 'text-slate-500';
        optHtml += `<span class="${colorClass}"><b class="opacity-60">${labels[idx]}:</b> ${opt}</span>`;
      });
      optHtml += '</div>';
      return optHtml;
    }

    function JSON_decode_safe(str) {
      try {
        return JSON.parse(str);
      } catch (e) {
        return [];
      }
    }

    function downloadExcelTemplate() {
      const headers = [
        ['Type', 'Marks', 'Cognitive Level', 'CO Tag', 'Question Text', 'Option A', 'Option B', 'Option C', 'Option D', 'Correct Answer'],
        ['MCQ', '1', 'Remember', 'CO1', 'What is the correct definition of an embedded system?', 'A general purpose computer system', 'A specialized computer system designed for specific control functions', 'A computer system with no hardware', 'A system only used in gaming consoles', 'B'],
        ['Descriptive', '5', 'Understand', 'CO2', 'Explain the differences between RISC and CISC architectures in embedded processors.', '', '', '', '', '']
      ];
      const ws = XLSX.utils.aoa_to_sheet(headers);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Questions Template");
      XLSX.writeFile(wb, "Question_Bank_Template.xlsx");
    }

    function handleQBankUpload(input) {
      if (!input.files || input.files.length === 0) return;
      const file = input.files[0];

      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-10">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Parsing Excel file...</p>
        </div>
      `;

      const reader = new FileReader();
      reader.onload = function(e) {
        try {
          const data = new Uint8Array(e.target.result);
          const workbook = XLSX.read(data, { type: 'array' });
          const firstSheetName = workbook.SheetNames[0];
          const worksheet = workbook.Sheets[firstSheetName];
          const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
          
          if (rows.length < 2) {
            alert('Excel file is empty or missing data rows.');
            fetchQuestionBank(currentSubjectId);
            input.value = '';
            return;
          }

          container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10">
              <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
              <p class="text-sm font-bold text-slate-400">Uploading and saving questions to pool...</p>
            </div>
          `;

          fetch(`/api/classroom/${currentSubjectId}/question-bank/upload-json`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ rows: rows })
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'SUCCESS') {
              alert(data.message || 'Questions imported successfully!');
            } else {
              alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
            fetchQuestionBank(currentSubjectId);
            input.value = '';
          })
          .catch(err => {
            alert('Upload failed: server error');
            fetchQuestionBank(currentSubjectId);
            input.value = '';
          });

        } catch (err) {
          alert('Error reading Excel file: ' + err.message);
          fetchQuestionBank(currentSubjectId);
          input.value = '';
        }
      };
      
      reader.readAsArrayBuffer(file);
    }

    function seedQuestionBankWithAi(subjectId) {
      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">AI is generating structured exam questions for all COs...</p>
        </div>
      `;

      fetch(`/api/classroom/${subjectId}/question-bank/seed-ai`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
        } else {
          alert('Failed to seed: ' + data.message);
        }
        fetchQuestionBank(subjectId);
      })
      .catch(err => {
        alert('Server error seeding question bank.');
        fetchQuestionBank(subjectId);
      });
    }

    // Mid-Semester Survey Functionality (SAR Criterion 2)
    function fetchSurveyResults(subjectId) {
      const workspace = document.getElementById('surveyWorkspace');
      const headerActions = document.getElementById('surveyHeaderActions');
      headerActions.innerHTML = '';

      fetch(`/api/classroom/${subjectId}/survey/results`)
        .then(res => res.json())
        .then(res => {
          if (res.status === 'INACTIVE') {
            workspace.innerHTML = `
              <div class="bg-white border border-slate-200/80 rounded-2xl p-6 text-center max-w-xl mx-auto space-y-4">
                <div class="h-12 w-12 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mx-auto mb-2 animate-pulse">
                  <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h4 class="text-base font-bold text-slate-900">Initiate Mid-Semester Feedback Survey</h4>
                <p class="text-sm text-slate-600 leading-relaxed">
                  Conducted around the 7th–9th week of the semester, this evaluates the teaching-learning process in real-time. It gathers student feedback on 5 criteria: Pace, Clarity, Interaction, Practicality, and Evaluation.
                </p>
                <button onclick="initiateMidSemSurvey(${subjectId})" class="px-5 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold border border-blue-500/30 transition-premium shadow-lg shadow-blue-500/10 cursor-pointer">
                  Start Mid-Semester Survey
                </button>
              </div>
            `;
          } else if (res.status === 'SUCCESS') {
            const survey = res.data.survey;
            const total = res.data.total_students;
            const responded = res.data.responded_count;

            if (survey.status === 'Active') {
              workspace.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <!-- Live stats card -->
                  <div class="bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between space-y-4">
                    <div>
                      <span class="text-teal-700 font-bold font-bold uppercase tracking-widest text-[10px] block mb-1">Live Status</span>
                      <h4 class="text-base font-bold text-slate-900">Survey Active</h4>
                      <p class="text-xs text-slate-600 leading-relaxed mt-1">Students can now see and submit feedback from their dashboard task list.</p>
                    </div>
                    <div class="border-t border-slate-200/80 pt-4">
                      <div class="flex justify-between text-sm font-bold mb-1">
                        <span class="text-slate-600 font-semibold">Participation:</span>
                        <span class="text-slate-900 font-bold">${responded} / ${total}</span>
                      </div>
                      <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                        <div class="bg-teal-500 h-2 rounded-full" style="width: ${total > 0 ? (responded / total) * 100 : 0}%"></div>
                      </div>
                    </div>
                  </div>

                  <!-- Quick instructions card -->
                  <div class="bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between col-span-2">
                    <div>
                      <h4 class="text-sm font-bold text-slate-700">Evaluating Criterion 2 (SAR)</h4>
                      <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        To finalize results, draw graphs, and register action plan notes, you must close the active survey. Encourage students to participate before closing.
                      </p>
                    </div>
                    <div class="pt-6 border-t border-slate-200/80 flex justify-end">
                      <button onclick="closeMidSemSurvey(${subjectId})" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 shadow-2xs rounded-xl text-sm font-bold transition-premium cursor-pointer">
                        Close & Finalize Survey
                      </button>
                    </div>
                  </div>
                </div>
              `;
            } else {
              // Completed survey: show results + chart + notes
              const averages = res.data.averages;
              
              workspace.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                  <!-- Stats overview -->
                  <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">Participation Details</h4>
                      <div class="grid grid-cols-2 gap-4 text-xs font-semibold">
                        <div>
                          <span class="text-slate-500 block">Class Strength</span>
                          <span class="text-slate-900 font-bold text-sm">${total}</span>
                        </div>
                        <div>
                          <span class="text-slate-500 block">Responded</span>
                          <span class="text-slate-900 font-bold text-sm">${responded}</span>
                        </div>
                      </div>
                      <div class="pt-3 border-t border-slate-100">
                        <span class="text-slate-500 block text-xs">Response Rate</span>
                        <span class="text-emerald-700 font-bold font-black text-base">${total > 0 ? Math.round((responded / total) * 100) : 0}%</span>
                      </div>
                    </div>

                    <!-- Average Score Card -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">Average Scores Breakdown</h4>
                      <div class="space-y-3 text-xs font-semibold">
                        <div class="flex justify-between">
                          <span class="text-slate-600">Pace of delivery</span>
                          <span class="text-teal-700 font-bold">${averages.pace} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">Concept clarity</span>
                          <span class="text-teal-700 font-bold">${averages.clarity} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">Interactive lectures</span>
                          <span class="text-teal-700 font-bold">${averages.interaction} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">Lab practicality</span>
                          <span class="text-teal-700 font-bold">${averages.practicality} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">Prompt evaluation</span>
                          <span class="text-teal-700 font-bold">${averages.evaluation} / 3</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Charts and Action Plan Notes -->
                  <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6">
                      <h4 class="text-sm font-black text-slate-900 mb-4">Feedback Chart (Teaching-Learning Process)</h4>
                      <div class="h-64 relative">
                        <canvas id="surveyResultChart"></canvas>
