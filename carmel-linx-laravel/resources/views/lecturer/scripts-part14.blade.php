            `;
            pendingList.appendChild(card);
          });
        }

        // Accepted / Attending
        const accepted = todaySeminarsData.filter(s => s.accepted);
        if (accepted.length === 0) {
          attendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">No accepted seminars yet. Accept an invitation above.</div>';
        } else {
          attendingList.innerHTML = '';
          accepted.forEach(s => {
            const card = document.createElement('div');
            card.className = 'bg-white border border-emerald-700/20 rounded-xl p-4 flex items-center justify-between gap-3';
            card.innerHTML = `
              <div class="min-w-0">
                <div class="font-bold text-white text-sm truncate">${s.student_name}</div>
                <div class="text-xs text-slate-600 mt-0.5 truncate">${s.topic || '-'}</div>
              </div>
              <button onclick="openMobSemEvaluation('${s.reg_no}')" class="shrink-0 px-4 py-2 bg-emerald-700/80 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg> Evaluate
              </button>
            `;
            attendingList.appendChild(card);
          });
        }
      })
      .catch(() => {
        pendingList.innerHTML = '<div class="text-xs text-red-400 py-2">Failed to load. Try again.</div>';
      });
    }

    function acceptMobileInvitation(seminarRegId) {
      fetch('/api/lecturer/seminar/accept', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ seminar_registration_id: seminarRegId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showMobileSemToast('Invitation accepted! You can now evaluate this student.', 'success');
          refreshMobileSeminarsList();
          checkTodaySeminars();
        } else {
          showMobileSemToast(data.message || 'Failed to accept.', 'error');
        }
      })
      .catch(() => showMobileSemToast('Network error. Try again.', 'error'));
    }

    function openMobSemEvaluation(regNo) {
      const seminar = todaySeminarsData.find(s => s.reg_no === regNo);
      if (!seminar) return;
      mobSemCurrentRegNo = regNo;
      currentSubjectId = seminar.batch_subject_id;

      // Populate student card
      document.getElementById('mobSemStudentName').innerText = seminar.student_name || '-';
      document.getElementById('mobSemSbteRegV2').innerText = seminar.sbte_reg_no || '-';
      document.getElementById('mobSemTopicV2').innerText = seminar.topic || '-';

      // Reset form
      ['mobSemRelevance','mobSemLiterature','mobSemPresentation','mobSemInteraction','mobSemReport','mobSemAttendance']
        .forEach(id => { document.getElementById(id).value = ''; });
      // Reset sliders
      document.querySelectorAll('#mobileSeminarForm input[type=range]').forEach(r => r.value = 0);
      calcMobSemTotal();

      // Switch to step 2
      document.getElementById('mobileSemStep1').classList.add('hidden');
      document.getElementById('mobileSemStep2').classList.remove('hidden');

      // Load existing evaluation
      fetch(`/api/classroom/${seminar.batch_subject_id}/seminar/evaluations`)
      .then(r => r.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          const stud = res.data.find(s => s.reg_no === regNo);
          const me = stud ? stud.my_evaluation : null;
          if (me) {
            document.getElementById('mobSemRelevance').value = me.relevance;
            document.getElementById('mobSemLiterature').value = me.literature;
            document.getElementById('mobSemPresentation').value = me.presentation;
            document.getElementById('mobSemInteraction').value = me.interaction;
            document.getElementById('mobSemReport').value = me.report;
            document.getElementById('mobSemAttendance').value = me.attendance;
            // Sync sliders
            const sliders = document.querySelectorAll('#mobileSeminarForm input[type=range]');
            const vals = [me.relevance, me.literature, me.presentation];
            sliders.forEach((sl, i) => { if (vals[i] !== undefined) sl.value = vals[i]; });
            calcMobSemTotal();
          }
        }
      });
    }

    function calcMobSemTotal() {
      const relevance = parseFloat(document.getElementById('mobSemRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('mobSemLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('mobSemPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('mobSemInteraction').value) || 0;
      const report = parseFloat(document.getElementById('mobSemReport').value) || 0;
      const attendance = parseFloat(document.getElementById('mobSemAttendance').value) || 0;
      const total = relevance + literature + presentation + interaction + report + attendance;
      const pct = total / 75;

      // Update number display
      const numEl = document.getElementById('mobSemTotalNum');
      if (numEl) {
        numEl.innerText = total.toFixed(0);
        numEl.style.color = total >= 60 ? '#34d399' : total >= 45 ? '#60a5fa' : total >= 30 ? '#fbbf24' : '#f87171';
      }

      // Update score ring
      const circle = document.getElementById('mobScoreRingCircle');
      if (circle) {
        const circumference = 163.36;
        circle.style.strokeDashoffset = circumference * (1 - pct);
        circle.style.stroke = total >= 60 ? '#34d399' : total >= 45 ? '#3b82f6' : total >= 30 ? '#f59e0b' : '#ef4444';
      }
      const ringScore = document.getElementById('mobSemRingScore');
      if (ringScore) ringScore.innerText = total.toFixed(0);

      // Compat: old label
      const oldLabel = document.getElementById('mobSemTotalScoreLabel');
      if (oldLabel) oldLabel.innerText = `${total.toFixed(0)} / 75`;
    }

    // Keep old name as alias for compat
    function calculateMobileSeminarTotal() { calcMobSemTotal(); }

    function submitMobileSeminarEvaluation(e) {
      e.preventDefault();
      const regNo = mobSemCurrentRegNo;
      if (!regNo) return;
      const seminar = todaySeminarsData.find(s => s.reg_no === regNo);
      if (!seminar) return;

      const relevance = parseFloat(document.getElementById('mobSemRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('mobSemLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('mobSemPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('mobSemInteraction').value) || 0;
      const report = parseFloat(document.getElementById('mobSemReport').value) || 0;
      const attendance = parseFloat(document.getElementById('mobSemAttendance').value) || 0;

      const btn = document.getElementById('mobSemSubmitBtn');
      btn.disabled = true;
      btn.innerHTML = '<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> Saving...';

      fetch(`/api/classroom/${seminar.batch_subject_id}/seminar/evaluate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ reg_no: regNo, relevance, literature, presentation, interaction, report, attendance })
      })
      .then(res => res.json())
      .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save';
        if (res.status === 'SUCCESS') {
          showMobileSemToast(`Evaluation saved! Avg score: ${res.average_score} / 75`, 'success');
          // Silently refresh the desktop seminar table so marks appear without page reload
          if (typeof fetchSeminarEvaluations === 'function') {
            try {
              fetchSeminarEvaluations();
            } catch (err) {
              console.warn("Silent background table refresh failed:", err);
            }
          }
          setTimeout(() => backToSeminarList(), 1500);
        } else {
          showMobileSemToast(res.message || 'Failed to save.', 'error');
        }
      })
      .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save';
        showMobileSemToast('Network error. Please try again.', 'error');
      });
    }

    // Legacy: keep old handler names as aliases for compat with any inline onclick
    function handleMobileSemStudentChange() {}
    function refreshMobileSeminarsList_old() { refreshMobileSeminarsList(); }

    // ==========================================
    // VIRTUAL LAB WORKSPACE MODALS (REVISION 2021)
    // ==========================================
    const dynamicLabModalsHtml = `
      <!-- Student Lab Modal -->
      <div id="studentLabModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden justify-center items-center p-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-6xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">
          <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <div>
              <h3 id="labModalStudentName" class="text-base font-bold text-slate-900">Student Evaluation</h3>
              <p id="labModalStudentReg" class="text-xs font-semibold text-slate-500 font-mono"></p>
            </div>
            <button onclick="closeStudentLabModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer text-sm font-bold">
              ✕
            </button>
          </div>
          <div class="px-6 py-2.5 bg-white border-b border-slate-200 flex gap-4 text-xs font-bold">
            <button onclick="switchLabModalTab(\'exp\')" id="labTabBtn_exp" class="py-2 border-b-2 border-blue-600 text-blue-600 px-1 transition-premium font-semibold">Experiments (37.5)</button>
            <button onclick="switchLabModalTab(\'test\')" id="labTabBtn_test" class="py-2 border-b-2 border-transparent text-slate-500 hover:text-slate-800 px-1 transition-premium font-semibold">Model Tests (15)</button>
            <button onclick="switchLabModalTab(\'project\')" id="labTabBtn_project" class="py-2 border-b-2 border-transparent text-slate-500 hover:text-slate-800 px-1 transition-premium font-semibold">Micro-Project &amp; Attendance (22.5)</button>
            <button onclick="switchLabModalTab(\'board\')" id="labTabBtn_board" class="py-2 border-b-2 border-transparent text-slate-500 hover:text-slate-800 px-1 transition-premium font-bold text-blue-600">Board Exam (50)</button>
          </div>
          
          <div class="flex-grow overflow-y-auto p-6 space-y-6">
            <!-- TAB: EXPERIMENTS -->
            <div id="labModalTab_exp" class="space-y-5">
              <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
                  <div class="space-y-1">
                    <label class="block text-sm font-bold text-slate-900 uppercase tracking-wider">Select Lab Experiment</label>
                    <p class="text-xs text-slate-500 font-medium">Choose an experiment to grade or view scores.</p>
                  </div>
                  <select id="labModalExpSelect" onchange="changeActiveExperiment()" class="bg-white border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm font-bold focus:border-blue-500 outline-none w-full sm:w-80 shadow-2xs">
                    <!-- Dynamic experiment options -->
                  </select>
                </div>

                <!-- Common sliders for the selected experiment -->
                <div id="activeExperimentContainer" class="hidden space-y-4">
                  <div class="flex justify-between items-center bg-white px-4 py-3 rounded-xl border border-slate-200 shadow-2xs">
                    <span class="text-sm font-bold text-slate-900" id="activeExpTitle">Experiment Details</span>
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold uppercase font-mono" id="activeExpCo">CO Map</span>
                  </div>
                  
                  <!-- Responsive horizontal grid for Desktop, stacks nicely on Mobile -->
                  <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    
                    <!-- Prereq -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800 whitespace-nowrap">Prerequisites</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="active_exp_prerequisite" 
                          oninput="syncSlider(\'active_exp_prerequisite\',\'active_exp_prerequisite_slider\',7.5); updateTempExpMark(\'prerequisite\', this.value)"
                          class="w-14 bg-slate-50 border border-slate-200 rounded-lg py-1 text-center font-bold text-slate-900 text-sm focus:border-blue-500 outline-none">
                      </div>
                      <div class="space-y-1">
                        <input type="range" id="active_exp_prerequisite_slider" min="0" max="7.5" step="0.1" value="0"
                          oninput="document.getElementById(\'active_exp_prerequisite\').value = this.value; updateTempExpMark(\'prerequisite\', this.value)"
                          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold">
                          <span>0</span>
                          <span>Max 7.5</span>
                        </div>
                      </div>
                    </div>

                    <!-- Work Done -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800 whitespace-nowrap">Work Done</span>
                        <input type="number" step="0.1" min="0" max="10" id="active_exp_execution" 
                          oninput="syncSlider(\'active_exp_execution\',\'active_exp_execution_slider\',10); updateTempExpMark(\'execution\', this.value)"
                          class="w-14 bg-slate-50 border border-slate-200 rounded-lg py-1 text-center font-bold text-slate-900 text-sm focus:border-blue-500 outline-none">
                      </div>
                      <div class="space-y-1">
                        <input type="range" id="active_exp_execution_slider" min="0" max="10" step="0.1" value="0"
                          oninput="document.getElementById(\'active_exp_execution\').value = this.value; updateTempExpMark(\'execution\', this.value)"
                          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold">
                          <span>0</span>
                          <span>Max 10</span>
                        </div>
                      </div>
                    </div>

                    <!-- Result -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800 whitespace-nowrap">Result</span>
                        <input type="number" step="0.1" min="0" max="5" id="active_exp_output" 
                          oninput="syncSlider(\'active_exp_output\',\'active_exp_output_slider\',5); updateTempExpMark(\'output\', this.value)"
                          class="w-14 bg-slate-50 border border-slate-200 rounded-lg py-1 text-center font-bold text-slate-900 text-sm focus:border-blue-500 outline-none">
                      </div>
                      <div class="space-y-1">
                        <input type="range" id="active_exp_output_slider" min="0" max="5" step="0.1" value="0"
                          oninput="document.getElementById(\'active_exp_output\').value = this.value; updateTempExpMark(\'output\', this.value)"
                          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold">
                          <span>0</span>
                          <span>Max 5</span>
                        </div>
                      </div>
                    </div>

                    <!-- Rough Record -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800 whitespace-nowrap">Rough Record</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="active_exp_rough_record" 
                          oninput="syncSlider(\'active_exp_rough_record\',\'active_exp_rough_record_slider\',7.5); updateTempExpMark(\'rough_record\', this.value)"
                          class="w-14 bg-slate-50 border border-slate-200 rounded-lg py-1 text-center font-bold text-slate-900 text-sm focus:border-blue-500 outline-none">
                      </div>
                      <div class="space-y-1">
                        <input type="range" id="active_exp_rough_record_slider" min="0" max="7.5" step="0.1" value="0"
                          oninput="document.getElementById(\'active_exp_rough_record\').value = this.value; updateTempExpMark(\'rough_record\', this.value)"
                          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold">
                          <span>0</span>
                          <span>Max 7.5</span>
                        </div>
                      </div>
                    </div>

                    <!-- Fair Record -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800 whitespace-nowrap">Fair Record</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="active_exp_fair_record" 
                          oninput="syncSlider(\'active_exp_fair_record\',\'active_exp_fair_record_slider\',7.5); updateTempExpMark(\'fair_record\', this.value)"
                          class="w-14 bg-slate-50 border border-slate-200 rounded-lg py-1 text-center font-bold text-slate-900 text-sm focus:border-blue-500 outline-none">
                      </div>
                      <div class="space-y-1">
                        <input type="range" id="active_exp_fair_record_slider" min="0" max="7.5" step="0.1" value="0"
                          oninput="document.getElementById(\'active_exp_fair_record\').value = this.value; updateTempExpMark(\'fair_record\', this.value)"
                          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold">
                          <span>0</span>
                          <span>Max 7.5</span>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>

                <div id="noActiveExperimentMsg" class="p-4 text-center text-slate-500 font-medium text-sm">
                  Please select an experiment from the dropdown above to view or modify grades.
                </div>
              </div>
            </div>

            <!-- TAB: TESTS -->
            <div id="labModalTab_test" class="space-y-4 hidden">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Test 1 -->
                <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4">
                  <div class="border-b border-slate-200 pb-2.5 flex justify-between items-center">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Model Test 1 (CO1 &amp; CO2)</h4>
                    <span class="text-xs font-bold text-blue-600 font-mono" id="labModalT1Sum">0.0 / 15</span>
                  </div>
                  <div class="space-y-4">
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800">CO1 Score (Max 7.5)</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="labScore_t1_co1" oninput="syncSlider(\'labScore_t1_co1\',\'labScore_t1_co1_slider\',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                      </div>
                      <input type="range" id="labScore_t1_co1_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById(\'labScore_t1_co1\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800">CO2 Score (Max 7.5)</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="labScore_t1_co2" oninput="syncSlider(\'labScore_t1_co2\',\'labScore_t1_co2_slider\',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                      </div>
                      <input type="range" id="labScore_t1_co2_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById(\'labScore_t1_co2\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                    </div>
                  </div>
                </div>
                <!-- Test 2 -->
                <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4">
                  <div class="border-b border-slate-200 pb-2.5 flex justify-between items-center">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Model Test 2 (CO3 &amp; CO4)</h4>
                    <span class="text-xs font-bold text-blue-600 font-mono" id="labModalT2Sum">0.0 / 15</span>
                  </div>
                  <div class="space-y-4">
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800">CO3 Score (Max 7.5)</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="labScore_t2_co3" oninput="syncSlider(\'labScore_t2_co3\',\'labScore_t2_co3_slider\',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                      </div>
                      <input type="range" id="labScore_t2_co3_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById(\'labScore_t2_co3\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800">CO4 Score (Max 7.5)</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="labScore_t2_co4" oninput="syncSlider(\'labScore_t2_co4\',\'labScore_t2_co4_slider\',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                      </div>
                      <input type="range" id="labScore_t2_co4_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById(\'labScore_t2_co4\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- TAB: PROJECT & ATTENDANCE -->
            <div id="labModalTab_project" class="space-y-4 hidden">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4 flex flex-col justify-between">
                  <div>
                    <h4 class="text-xs font-bold text-slate-700 border-b border-slate-200 pb-2 uppercase tracking-wider mb-3">Open-Ended Project / Micro-Project</h4>
                    <label class="text-xs font-bold text-slate-600 uppercase block mb-1.5">Project Topic Description</label>
                    <input type="text" id="labScore_projectTopic" placeholder="Enter assigned micro-project topic..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-medium text-slate-900 focus:border-blue-500 outline-none mb-4 shadow-2xs">
                  </div>
                  <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                    <div class="flex justify-between items-center text-sm font-bold">
                      <span class="text-slate-800">Project Mark (Max 7.5)</span>
                      <input type="number" step="0.1" min="0" max="7.5" id="labScore_projectMark" oninput="syncSlider(\'labScore_projectMark\',\'labScore_projectMark_slider\',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                    </div>
                    <input type="range" id="labScore_projectMark_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById(\'labScore_projectMark\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                  </div>
                </div>
                <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4 flex flex-col justify-between">
                  <div>
                    <h4 class="text-xs font-bold text-slate-700 border-b border-slate-200 pb-2 uppercase tracking-wider mb-3">Attendance Scoring</h4>
                    <div class="flex justify-between items-center mb-3">
                      <span class="text-xs text-slate-600 font-semibold">Class Attendance Percentage:</span>
                      <span class="text-sm font-bold text-slate-900 font-mono" id="labModalStudentAttPct">0%</span>
                    </div>
                  </div>
                  <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                    <div class="flex justify-between items-center text-sm font-bold">
                      <span class="text-slate-800">Attendance Mark (Max 15)</span>
                      <input type="number" step="0.1" min="0" max="15" id="labScore_attendanceMark" oninput="syncSlider(\'labScore_attendanceMark\',\'labScore_attendanceMark_slider\',15); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                    </div>
                    <input type="range" id="labScore_attendanceMark_slider" min="0" max="15" step="0.1" value="0" oninput="document.getElementById(\'labScore_attendanceMark\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                  </div>
                </div>
              </div>
            </div>

            <!-- TAB: BOARD EXAM -->
            <div id="labModalTab_board" class="space-y-4 hidden">
              <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4 max-w-md mx-auto">
                <h4 class="text-xs font-bold text-slate-700 border-b border-slate-200 pb-2 uppercase tracking-wider">External Board Examination</h4>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                  <div class="flex justify-between items-center text-sm font-bold">
                    <span class="text-slate-800">Board Exam Mark (Max 50)</span>
                    <input type="number" step="0.5" min="0" max="50" id="labScore_boardExam" oninput="syncSlider(\'labScore_boardExam\',\'labScore_boardExam_slider\',50); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none" placeholder="0.0">
                  </div>
                  <input type="range" id="labScore_boardExam_slider" min="0" max="50" step="0.5" value="0" oninput="document.getElementById(\'labScore_boardExam\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom bar -->
          <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
            <div class="text-xs text-slate-600 font-semibold flex gap-4">
              <div>Lab Work Avg: <span class="text-slate-900 font-mono font-bold" id="labModalLabelExp">0.0</span></div>
              <div>Model Test: <span class="text-slate-900 font-mono font-bold" id="labModalLabelTest">0.0</span></div>
              <div>Internal CA: <span class="text-emerald-700 font-bold font-mono text-sm" id="labModalLabelInternals">0.0 / 75</span></div>
            </div>
            <button onclick="saveStudentLabEvaluation()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-xs">
              Save Evaluation
            </button>
          </div>
