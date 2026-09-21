                      </div>
                    </div>

                    <!-- Action Taken Form -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">SAR Criterion 2 Action Plan Notes</h4>
                      
                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Improvements Noted by Faculty</label>
                        <textarea id="improvementsNoted" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 text-xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium shadow-2xs transition-all" rows="2" placeholder="e.g. Remedial classes identified for weak students, changing lecture pace...">${survey.improvements_noted || ''}</textarea>
                      </div>

                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Corrective Action Taken (Faculty Member)</label>
                        <textarea id="correctiveAction" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 text-xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium shadow-2xs transition-all" rows="2" placeholder="e.g. Incorporated PPT slides, allocated extra laboratory session...">${survey.action_taken || ''}</textarea>
                      </div>

                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Action Taken Notes (Class Tutor Remarks)</label>
                        <textarea id="actionTakenByTutor" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 text-xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium shadow-2xs transition-all" rows="2" placeholder="Tutor remarks on student feedback and faculty actions...">${survey.action_taken_by_tutor || ''}</textarea>
                      </div>

                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Action Taken Remarks (Head of Department / HOD)</label>
                        <textarea id="actionTakenByHod" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 text-xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium shadow-2xs transition-all" rows="2" placeholder="HOD remarks or corrective endorsement...">${survey.action_taken_by_hod || ''}</textarea>
                      </div>

                      <div class="flex justify-between items-center pt-2">
                        <button onclick="saveSurveyActionNotes(${subjectId})" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold border border-blue-500/30 transition-premium shadow cursor-pointer">
                          Save Notes
                        </button>
                        <a href="/classroom/${subjectId}/survey/report" target="_blank" class="px-4 py-2 bg-teal-50 hover:bg-teal-100 border border-teal-200 text-teal-700 shadow-2xs rounded-xl text-xs font-bold transition-premium no-underline flex items-center gap-1.5 cursor-pointer">
                          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Survey Report
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              `;

              // Initialize result chart
              setTimeout(() => renderSurveyChart(averages), 100);
            }
          } else {
            alert(res.message || "Failed to load survey results.");
          }
        })
        .catch(err => {
          console.error(err);
          workspace.innerHTML = `<div class="text-sm font-bold text-slate-500 py-10 text-center">Failed to fetch survey. Network error.</div>`;
        });
    }

    function initiateMidSemSurvey(subjectId) {
      if (!confirm("Are you sure you want to initiate the Mid-Semester Survey? This will notify all enrolled students.")) return;
      fetch(`/api/classroom/${subjectId}/survey/initiate`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }

    function closeMidSemSurvey(subjectId) {
      if (!confirm("Are you sure you want to close and finalize this survey? No further responses will be accepted.")) return;
      fetch(`/api/classroom/${subjectId}/survey/close`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }

    function saveSurveyActionNotes(subjectId) {
      const imp = document.getElementById('improvementsNoted').value;
      const act = document.getElementById('correctiveAction').value;
      const tut = document.getElementById('actionTakenByTutor').value;
      const hod = document.getElementById('actionTakenByHod').value;

      fetch(`/api/classroom/${subjectId}/survey/save-notes`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
          improvements_noted: imp, 
          action_taken: act,
          action_taken_by_tutor: tut,
          action_taken_by_hod: hod
        })
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
      });
    }

    function renderSurveyChart(averages) {
      const ctx = document.getElementById('surveyResultChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Pace', 'Clarity', 'Interaction', 'Practicality', 'Evaluation'],
          datasets: [{
            label: 'Avg Score (Out of 3)',
            data: [averages.pace, averages.clarity, averages.interaction, averages.practicality, averages.evaluation],
            backgroundColor: [
              'rgba(20, 184, 166, 0.2)',
              'rgba(14, 165, 233, 0.2)',
              'rgba(99, 102, 241, 0.2)',
              'rgba(168, 85, 247, 0.2)',
              'rgba(236, 72, 153, 0.2)'
            ],
            borderColor: [
              '#14b8a6',
              '#0ea5e9',
              '#6366f1',
              '#a855f7',
              '#ec4899'
            ],
            borderWidth: 2,
            borderRadius: 8
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            x: {
              min: 0,
              max: 3,
              ticks: { stepSize: 1, color: '#94a3b8' },
              grid: { color: 'rgba(51, 65, 85, 0.2)' }
            },
            y: {
              ticks: { color: '#94a3b8' },
              grid: { display: false }
            }
          }
        }
      });
    }

    // Course Exit Survey JS methods
    function fetchExitSurveyResults(subjectId) {
      const workspace = document.getElementById('exitSurveyWorkspace');
      const headerActions = document.getElementById('exitSurveyHeaderActions');
      headerActions.innerHTML = '';

      fetch(`/api/classroom/${subjectId}/course-exit/results`)
        .then(res => res.json())
        .then(res => {
          if (res.status === 'INACTIVE') {
            workspace.innerHTML = `
              <div class="bg-white border border-slate-200/80 rounded-2xl p-6 text-center max-w-xl mx-auto space-y-4">
                <div class="h-12 w-12 rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-700 font-bold flex items-center justify-center mx-auto mb-2 animate-pulse">
                  <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                </div>
                <h4 class="text-base font-bold text-slate-900">Initiate Course Exit Survey</h4>
                <p class="text-sm text-slate-600 leading-relaxed">
                  Conducted at the end of the semester, this exit survey maps directly to Course Outcomes (CO1 to CO4) using 10 specific attainment questions. Attainments are rated on a Low (1), Medium (2), and High (3) scale.
                </p>
                <button onclick="initiateExitSurvey(${subjectId})" class="px-5 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-sm font-bold border border-teal-500/30 transition-premium shadow-lg shadow-teal-500/10 cursor-pointer">
                  Start Course Exit Survey
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
                      <p class="text-xs text-slate-600 leading-relaxed mt-1">Students can now submit exit responses mapping to COs via their dashboard.</p>
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
                      <h4 class="text-sm font-bold text-slate-700">Course Outcome Attainment mapping</h4>
                      <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        To calculate final attainment averages and view the printable Course Exit Report, you must close the active survey.
                      </p>
                    </div>
                    <div class="pt-6 border-t border-slate-200/80 flex justify-end">
                      <button onclick="closeExitSurvey(${subjectId})" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 shadow-2xs rounded-xl text-sm font-bold transition-premium cursor-pointer">
                        Close & Finalize Exit Survey
                      </button>
                    </div>
                  </div>
                </div>
              `;
            } else {
              // Completed survey: show results breakdown
              const averages = res.data.averages;
              const attainments = res.data.attainment_percentages;

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
                        <span class="text-teal-700 font-bold font-black text-base">${total > 0 ? Math.round((responded / total) * 100) : 0}%</span>
                      </div>
                    </div>

                    <!-- Average Score Card -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">CO Averages (Scale 1-3)</h4>
                      <div class="space-y-3 text-xs font-semibold">
                        <div class="flex justify-between">
                          <span class="text-slate-600">CO1 Average score</span>
                          <span class="text-teal-700 font-bold">${averages.CO1} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">CO2 Average score</span>
                          <span class="text-teal-700 font-bold">${averages.CO2} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">CO3 Average score</span>
                          <span class="text-teal-700 font-bold">${averages.CO3} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">CO4 Average score</span>
                          <span class="text-teal-700 font-bold">${averages.CO4} / 3</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Attainments and Print Action -->
                  <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">Indirect CO Attainment Levels</h4>
                      <p class="text-xs text-slate-600 leading-relaxed">Attainment is computed as: <code>(CO Average / 3) * 100</code></p>
                      
                      <div class="space-y-4 pt-2">
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800">CO1 Attainment</span>
                            <span class="text-teal-700 font-bold">${attainments.CO1}%</span>
                          </div>
                          <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO1}%"></div>
                          </div>
                        </div>
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800">CO2 Attainment</span>
                            <span class="text-teal-700 font-bold">${attainments.CO2}%</span>
                          </div>
                          <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO2}%"></div>
                          </div>
                        </div>
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800">CO3 Attainment</span>
                            <span class="text-teal-700 font-bold">${attainments.CO3}%</span>
                          </div>
                          <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO3}%"></div>
                          </div>
                        </div>
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800">CO4 Attainment</span>
                            <span class="text-teal-700 font-bold">${attainments.CO4}%</span>
                          </div>
                          <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO4}%"></div>
                          </div>
                        </div>
                      </div>

                      <div class="flex justify-end items-center pt-6 border-t border-slate-200/80">
                        <a href="/classroom/${subjectId}/course-exit/report" target="_blank" class="px-5 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-1.5 cursor-pointer shadow-md shadow-teal-600/10">
                          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Course Exit Report
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              `;
            }
          } else {
            alert(res.message || "Failed to load exit survey results.");
          }
        })
        .catch(err => {
          console.error(err);
          workspace.innerHTML = `<div class="text-sm font-bold text-slate-500 py-10 text-center">Failed to fetch exit survey. Network error.</div>`;
        });
    }

    function initiateExitSurvey(subjectId) {
      if (!confirm("Are you sure you want to initiate the Course Exit Survey? This will notify all enrolled students.")) return;
      fetch(`/api/classroom/${subjectId}/course-exit/initiate`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchExitSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }

    function closeExitSurvey(subjectId) {
      if (!confirm("Are you sure you want to close and finalize this Course Exit Survey? No further responses will be accepted.")) return;
      fetch(`/api/classroom/${subjectId}/course-exit/close`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchExitSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }
</script>

<!-- Edit Assignment Questions Modal -->
<div id="editQuestionsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[100] hidden flex items-center justify-center p-4">
  <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-4xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
      <div>
        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
          <x-ui.icon name="edit" class="w-5 h-5 text-blue-600" /> Manually Edit Questions (<span id="editQuestionsCoBadge" class="text-blue-700 font-mono font-bold"></span>)
        </h3>
        <p class="text-xs text-slate-600 mt-0.5">Define one or more descriptive questions for this Course Outcome. Total marks must equal exactly 20.</p>
      </div>
      <button onclick="closeEditQuestionsModal()" class="text-slate-400 hover:text-slate-700 transition-colors cursor-pointer">
        <x-ui.icon name="close" class="w-5 h-5" />
      </button>
    </div>
    
    <div class="p-6 overflow-y-auto space-y-4 flex-1 bg-white">
      <div id="editQuestionsFieldsContainer" class="space-y-4">
        <!-- Dyn fields -->
      </div>
      
      <button type="button" onclick="addManualQuestionField()" class="w-full py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 rounded-xl text-xs font-bold transition-premium flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
        <x-ui.icon name="science" class="w-4 h-4 text-blue-600" /> Add Question
      </button>
    </div>
    
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
      <div class="text-xs font-bold text-slate-700">
        Total Marks: <span id="editQuestionsTotalMarks" class="text-emerald-700 text-sm font-black">0</span> / 20
      </div>
      <div class="flex gap-2">
        <button type="button" onclick="closeEditQuestionsModal()" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold shadow-2xs transition-premium cursor-pointer">Cancel</button>
        <button type="button" onclick="saveManualQuestions()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium shadow-xs cursor-pointer flex items-center gap-1.5">
          <x-ui.icon name="save" class="w-4 h-4" /> Save Questions
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Virtual Classroom Students Modal -->
<div id="vcStudentsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[100] hidden flex items-center justify-center p-4">
  <div class="bg-white border border-slate-200/80 w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
      <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2 flex-wrap">
        <x-ui.icon name="groups" class="w-6 h-6 text-blue-600 flex-shrink-0" /> Enrolled Students
        <span id="vcModalBatchBadge" class="text-xs font-mono font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-md ml-2 flex-shrink-0"></span>
      </h3>
      <div class="flex items-center gap-3">
        <button onclick="printVcStudentsList()" class="text-xs font-bold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl flex items-center gap-1.5 shadow-2xs transition-premium cursor-pointer">
          <x-ui.icon name="print" class="w-4 h-4 text-slate-600" /> Print List
        </button>
        <button onclick="closeVcStudentsList()" class="text-slate-400 hover:text-slate-700 transition-colors ml-2 cursor-pointer">
          <x-ui.icon name="close" class="w-5 h-5" />
        </button>
      </div>
    </div>
    <div class="p-0 overflow-y-auto custom-scrollbar flex-1 bg-white">
      <div id="vcStudentsListContent"></div>
    </div>
  </div>
</div>

<!-- Seminar Evaluation Pop-up Modal -->
<div id="seminarEvaluationModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[110] hidden flex items-center justify-center p-4">
  <div class="bg-white border border-slate-200/80 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col">
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
      <h3 class="text-base font-bold text-slate-900">Evaluate Seminar Presentation</h3>
      <button onclick="closeSeminarEvaluationModal()" class="text-slate-400 hover:text-slate-700 transition-colors cursor-pointer">
        <x-ui.icon name="close" class="w-4 h-4" />
      </button>
    </div>
