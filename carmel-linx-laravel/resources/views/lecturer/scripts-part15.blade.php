        </div>
      </div>

      <!-- Manage Experiments Modal -->
      <div id="manageExperimentsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden justify-center items-center p-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-5xl max-h-[85vh] flex flex-col overflow-hidden shadow-2xl">
          <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <div>
              <h3 class="text-base font-bold text-slate-900">Experiments List</h3>
              <p class="text-xs text-slate-500 mt-0.5 font-medium">Setup the experiments syllabus for day-to-day continuous evaluation.</p>
            </div>
            <button onclick="closeManageExperimentsModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer text-sm font-bold">
              ✕
            </button>
          </div>

          <div class="p-6 overflow-y-auto space-y-6 flex-grow">
            <!-- Add Experiment Form -->
            <form onsubmit="savePracticalExperiment(event)" class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4">
              <input type="hidden" id="expEditId">
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                  <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Exp No.</label>
                  <input type="text" id="expFormNo" required placeholder="e.g. 1, 2A" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-900 focus:border-blue-500 outline-none shadow-2xs">
                </div>
                <div class="md:col-span-2">
                  <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Experiment Title / Objective</label>
                  <textarea id="expFormTitle" required placeholder="Enter experiment objective / detailed description..." rows="2" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-medium text-slate-900 focus:border-blue-500 outline-none resize-y shadow-2xs"></textarea>
                </div>
                <div class="md:col-span-1">
                  <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Map CO</label>
                  <select id="expFormCo" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-900 focus:border-blue-500 outline-none cursor-pointer shadow-2xs">
                    <option value="CO1">CO1</option>
                    <option value="CO2">CO2</option>
                    <option value="CO3">CO3</option>
                    <option value="CO4">CO4</option>
                  </select>
                </div>
              </div>
              <div class="flex justify-between items-center pt-2">
                <button type="button" id="btnImportDatabank" onclick="importFromDatabank()" class="hidden px-3.5 py-2 bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 rounded-xl text-xs font-bold transition-premium flex items-center gap-1 cursor-pointer">
                  Import from Databank
                </button>
                <button type="submit" id="btnSaveExp" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer ml-auto shadow-xs">
                  Add Experiment
                </button>
              </div>
            </form>

            <!-- Experiments List Table -->
            <div class="border border-slate-200/80 rounded-2xl overflow-hidden bg-white shadow-xs">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase text-xs">
                    <th class="p-3 w-16 text-center">No.</th>
                    <th class="p-3">Title / Objective</th>
                    <th class="p-3 w-20 text-center">CO</th>
                    <th class="p-3 w-28 text-center">Actions</th>
                  </tr>
                </thead>
                <tbody id="manageExpsTableBody" class="divide-y divide-slate-100">
                  <tr>
                    <td colspan="4" class="p-6 text-center text-slate-500">No experiments set up yet.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Manage Tests Modal -->
      <div id="manageTestsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden justify-center items-center p-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-xl md:max-w-4xl max-h-[85vh] flex flex-col overflow-hidden shadow-2xl">
          <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <div>
              <h3 class="text-base font-bold text-slate-900">Configure Model Tests Questions</h3>
              <p class="text-xs text-slate-500 mt-0.5 font-medium">Design the question paper scheme for Test 1 and Test 2.</p>
            </div>
            <button onclick="closeManageTestsModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer text-sm font-bold">
              ✕
            </button>
          </div>

          <form onsubmit="savePracticalTestQuestions(event)" class="flex-grow flex flex-col overflow-hidden">
            <div class="p-6 overflow-y-auto space-y-5 flex-grow">
              <div>
                <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Select Model Test</label>
                <select id="designTestName" onchange="renderTestQuestionsFields()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-900 focus:border-blue-500 outline-none cursor-pointer shadow-2xs">
                  <option value="Test 1">Model Test 1 (CO1 &amp; CO2)</option>
                  <option value="Test 2">Model Test 2 (CO3 &amp; CO4)</option>
                </select>
              </div>

              <div id="testQuestionsFieldsContainer" class="space-y-4">
                <!-- Inputs generated dynamically -->
              </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
              <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-xs">
                Save Test Config
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Generate Lesson Planner Modal -->
      <div id="generatePlannerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden justify-center items-center p-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
          <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900">Generate Practical Lesson Plan</h3>
            <button onclick="closeGeneratePlannerModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer text-sm font-bold">
              ✕
            </button>
          </div>
          <form onsubmit="generatePlannerFromExperiments(event)" class="p-6 space-y-4">
            <div>
              <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Lab Batch Session Mode</label>
              <select id="genPlannerBatchMode" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-900 focus:border-blue-500 outline-none cursor-pointer shadow-2xs">
                <option value="combined">Combined / Full Class (1 entry per experiment)</option>
                <option value="separate">Split Batches / Batch 1 &amp; 2 (2 entries per experiment)</option>
              </select>
            </div>
            <div>
              <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Allocated Hours per Session</label>
              <input type="number" id="genPlannerHours" value="3" min="1" max="10" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-900 focus:border-blue-500 outline-none shadow-2xs">
            </div>
            <div class="pt-2 flex justify-end gap-2.5">
              <button type="button" onclick="closeGeneratePlannerModal()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold transition-premium cursor-pointer shadow-2xs">Cancel</button>
              <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer shadow-xs">Generate</button>
            </div>
          </form>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', dynamicLabModalsHtml);

    // ==========================================
    // VIRTUAL LAB WORKSPACE JAVASCRIPT CONTROLLERS
    // ==========================================
    let labStudentsData = [];
    let labExperimentsData = [];
    let labTestsData = [];
    let activeLabModalTab = 'exp';
    let gradingStudentReg = null;

    function fetchPracticalEvaluations() {
      if (!currentSubjectId) return;
      const tbody = document.getElementById('labEvaluationsTableBody');
      tbody.innerHTML = `
        <tr>
          <td colspan="12" class="p-8 text-center text-slate-600 font-bold text-sm">
            <span class="animate-pulse">Loading student evaluation records...</span>
          </td>
        </tr>
      `;

      // Set print button href
      document.getElementById('printLabReportBtn').href = `/classroom/${currentSubjectId}/practical-report/print?type=register`;

      fetch(`/api/classroom/${currentSubjectId}/practical/evaluations`)
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          labStudentsData = res.students || [];
          labExperimentsData = res.experiments || [];
          labTestsData = res.tests || [];
          renderLabEvaluationsTable();
          calculateLabStatistics();
        } else {
          tbody.innerHTML = `<tr><td colspan="12" class="p-8 text-center text-red-400 font-bold text-sm">${res.message}</td></tr>`;
        }
      })
      .catch(err => {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="12" class="p-8 text-center text-red-400 font-bold text-sm">Error syncing lab evaluations.</td></tr>`;
      });
    }

    function renderLabEvaluationsTable() {
      const tbody = document.getElementById('labEvaluationsTableBody');
      tbody.innerHTML = '';

      if (labStudentsData.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="12" class="p-8 text-center text-slate-500 font-bold text-sm">
              No students enrolled in this classroom.
            </td>
          </tr>
        `;
        return;
      }

      labStudentsData.forEach(student => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-100 text-sm hover:bg-slate-50/70";
        tr.setAttribute('data-reg', student.reg_no);
        
        // Count graded experiments for this student
        let gradedCount = 0;
        if (student.experiments_marks) {
          gradedCount = Object.values(student.experiments_marks).filter(m => m !== null).length;
        }

        const expAverage = student.avg_lab_work ? parseFloat(student.avg_lab_work).toFixed(2) : '0.00';
        const t1Total = student.tests['Test 1'].total ? parseFloat(student.tests['Test 1'].total).toFixed(1) : '0.0';
        const t2Total = student.tests['Test 2'].total ? parseFloat(student.tests['Test 2'].total).toFixed(1) : '0.0';
        const testsAvg = student.tests.average ? parseFloat(student.tests.average).toFixed(2) : '0.00';
        const microProjVal = student.micro_project ? parseFloat(student.micro_project).toFixed(1) : '0.0';
        const attendanceVal = student.attendance_marks ? parseFloat(student.attendance_marks).toFixed(1) : '0.0';
        const internalsTotal = student.total_internal ? parseFloat(student.total_internal).toFixed(2) : '0.00';
        const boardMarks = student.board_exam_marks !== null ? parseFloat(student.board_exam_marks).toFixed(1) : 'N/A';

        tr.innerHTML = `
          <td class="p-3 font-mono font-bold text-slate-800 text-nowrap text-sm">${student.roll_no || '-'}</td>
          <td class="p-3 text-sm">
            <button onclick="openStudentLabModal('${student.reg_no}')" class="text-blue-400 hover:text-blue-300 font-bold cursor-pointer text-left block text-sm">
              ${student.name}
            </button>
            <span class="text-xs text-slate-500 block font-mono mt-0.5">${student.reg_no}</span>
          </td>
          <td class="p-3 text-center text-slate-600 font-bold font-mono text-sm">${gradedCount} / ${labExperimentsData.length}</td>
          <td class="p-3 text-center font-mono font-bold text-slate-800 text-sm">${expAverage}</td>
          <td class="p-3 text-center font-mono text-slate-455 text-sm">${t1Total}</td>
          <td class="p-3 text-center font-mono text-slate-455 text-sm">${t2Total}</td>
          <td class="p-3 text-center font-mono text-slate-800 font-bold text-sm">${testsAvg}</td>
          <td class="p-3 text-center font-mono text-slate-455 text-sm">${microProjVal}</td>
          <td class="p-3 text-center font-mono text-sm">
            <div class="inline-flex flex-col items-center">
              <span class="font-bold text-slate-350">${attendanceVal}</span>
              <span class="text-xs text-slate-500 font-bold">${student.attendance_percentage}%</span>
            </div>
          </td>
          <td class="p-3 text-center font-mono font-bold text-teal-700 font-bold text-base">${internalsTotal}</td>
          <td class="p-3 text-center font-mono font-bold text-blue-400 text-base">${boardMarks}</td>
          <td class="p-3 text-center">
            <button onclick="openStudentLabModal('${student.reg_no}')" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-800 hover:text-white rounded-lg text-xs font-bold transition-premium cursor-pointer border border-slate-700/50 flex items-center gap-1 mx-auto">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg> Grade
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
      filterLabGridByBatch();
    }

    function filterLabGridByBatch() {
      const filterVal = document.getElementById('labBatchFilterSelect').value;
      const tbody = document.getElementById('labEvaluationsTableBody');
      if (!tbody) return;
      const rows = Array.from(tbody.querySelectorAll('tr[data-reg]'));
      if (rows.length === 0) return;
      const total = rows.length;
      const mid = Math.ceil(total / 2);
      rows.forEach((row, idx) => {
        if (filterVal === 'combined') {
          row.classList.remove('hidden');
        } else if (filterVal === '1') {
          if (idx < mid) {
            row.classList.remove('hidden');
          } else {
            row.classList.add('hidden');
          }
        } else if (filterVal === '2') {
          if (idx >= mid) {
            row.classList.remove('hidden');
          } else {
            row.classList.add('hidden');
          }
        }
      });
    }

    function calculateLabStatistics() {
      if (labStudentsData.length === 0) return;
      
      let sumInternals = 0;
      let sumBoard = 0;
      let boardCount = 0;
      let passedCount = 0;

      labStudentsData.forEach(student => {
        sumInternals += parseFloat(student.total_internal || 0);
        if (student.board_exam_marks !== null) {
          sumBoard += parseFloat(student.board_exam_marks);
          boardCount++;

          const totalScore = parseFloat(student.total_internal || 0) + parseFloat(student.board_exam_marks);
          if (totalScore >= 50 && parseFloat(student.board_exam_marks) >= 20) {
            passedCount++;
          }
        }
      });

      const avgInternal = sumInternals / labStudentsData.length;
      const avgBoard = boardCount > 0 ? (sumBoard / boardCount) : 0;
      const passPercent = boardCount > 0 ? ((passedCount / boardCount) * 100) : 0;

      document.getElementById('statLabAvgInternal').innerText = `${avgInternal.toFixed(2)} / 75`;
      document.getElementById('statLabAvgBoard').innerText = boardCount > 0 ? `${avgBoard.toFixed(2)} / 50` : 'N/A';
      document.getElementById('statLabPassPercent').innerText = boardCount > 0 ? `${passPercent.toFixed(1)}%` : 'N/A';
      document.getElementById('statLabTotalExps').innerText = labExperimentsData.length;
    }

    // Modal tabs toggle
    function switchLabModalTab(tabId) {
      activeLabModalTab = tabId;
      ['exp', 'test', 'project', 'board'].forEach(t => {
        const el = document.getElementById('labModalTab_' + t);
        const btn = document.getElementById('labTabBtn_' + t);
        if (t === tabId) {
          el.classList.remove('hidden');
          btn.classList.add('border-blue-500', 'text-blue-400');
          btn.classList.remove('border-transparent', 'text-slate-600');
        } else {
          el.classList.add('hidden');
          btn.classList.remove('border-blue-500', 'text-blue-400');
          btn.classList.add('border-transparent', 'text-slate-400');
        }
      });
    }

    let tempStudentExpMarks = {};

    function openStudentLabModal(regNo) {
      gradingStudentReg = regNo;
      const student = labStudentsData.find(s => s.reg_no === regNo);
      if (!student) return;

      document.getElementById('labModalStudentName').innerText = student.name;
      document.getElementById('labModalStudentReg').innerText = `Register No: ${student.reg_no}`;
      document.getElementById('labModalStudentAttPct').innerText = `${student.attendance_percentage}%`;

      // Set input values
      document.getElementById('labScore_projectTopic').value = student.open_ended_project_topic || '';
      document.getElementById('labScore_projectMark').value = student.micro_project !== null ? student.micro_project : '';
      document.getElementById('labScore_attendanceMark').value = student.attendance_marks !== null ? student.attendance_marks : '';
      document.getElementById('labScore_boardExam').value = student.board_exam_marks !== null ? student.board_exam_marks : '';

      // Set test marks
      document.getElementById('labScore_t1_co1').value = student.tests['Test 1'].CO1 !== null ? student.tests['Test 1'].CO1 : '';
      document.getElementById('labScore_t1_co2').value = student.tests['Test 1'].CO2 !== null ? student.tests['Test 1'].CO2 : '';
      document.getElementById('labScore_t2_co3').value = student.tests['Test 2'].CO3 !== null ? student.tests['Test 2'].CO3 : '';
      document.getElementById('labScore_t2_co4').value = student.tests['Test 2'].CO4 !== null ? student.tests['Test 2'].CO4 : '';

      // Sync other sliders
      syncSlider('labScore_projectMark', 'labScore_projectMark_slider', 7.5);
      syncSlider('labScore_attendanceMark', 'labScore_attendanceMark_slider', 15);
      syncSlider('labScore_boardExam', 'labScore_boardExam_slider', 50);
      syncSlider('labScore_t1_co1', 'labScore_t1_co1_slider', 7.5);
      syncSlider('labScore_t1_co2', 'labScore_t1_co2_slider', 7.5);
      syncSlider('labScore_t2_co3', 'labScore_t2_co3_slider', 7.5);
      syncSlider('labScore_t2_co4', 'labScore_t2_co4_slider', 7.5);

      // Clone experiments marks locally
      tempStudentExpMarks = JSON.parse(JSON.stringify(student.experiments_marks || {}));

      // Populate experiment dropdown select
      const select = document.getElementById('labModalExpSelect');
      select.innerHTML = '';

      if (labExperimentsData.length === 0) {
        const opt = document.createElement('option');
        opt.value = "";
        opt.text = "-- No Experiments Configured --";
        select.appendChild(opt);
      } else {
        const optDefault = document.createElement('option');
        optDefault.value = "";
        optDefault.text = "-- Choose Experiment to Grade --";
        select.appendChild(optDefault);

        labExperimentsData.forEach(exp => {
          const opt = document.createElement('option');
          opt.value = exp.id;
          opt.text = `Exp ${exp.experiment_no}: ${exp.title}`;
          select.appendChild(opt);
        });
      }

      // Hide active container by default until select is chosen
      document.getElementById('activeExperimentContainer').classList.add('hidden');
      document.getElementById('noActiveExperimentMsg').classList.remove('hidden');

      switchLabModalTab('exp');
      calcLabModalScores();
      
      const modal = document.getElementById('studentLabModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function changeActiveExperiment() {
      const expId = document.getElementById('labModalExpSelect').value;
      const container = document.getElementById('activeExperimentContainer');
      const msg = document.getElementById('noActiveExperimentMsg');

      if (!expId) {
        container.classList.add('hidden');
        msg.classList.remove('hidden');
        return;
      }

      container.classList.remove('hidden');
      msg.classList.add('hidden');

      const exp = labExperimentsData.find(e => e.id == expId);
      if (!exp) return;

      document.getElementById('activeExpTitle').innerText = `Exp ${exp.experiment_no}: ${exp.title}`;
      document.getElementById('activeExpCo').innerText = exp.co_tag;

      const marks = tempStudentExpMarks[expId] || {};
      
      const setVal = (key, max) => {
        let val = marks[key];
        if (val === undefined || val === null) val = '';
        document.getElementById(`active_exp_${key}`).value = val;
        syncSlider(`active_exp_${key}`, `active_exp_${key}_slider`, max);
      };

      setVal('prerequisite', 7.5);
      setVal('execution', 10);
      setVal('output', 5);
      setVal('rough_record', 7.5);
      setVal('fair_record', 7.5);
    }

    function updateTempExpMark(key, val) {
      const expId = document.getElementById('labModalExpSelect').value;
      if (!expId) return;

      if (!tempStudentExpMarks[expId]) {
        tempStudentExpMarks[expId] = {};
      }
      tempStudentExpMarks[expId][key] = val;
      calcLabModalScores();
    }

    function closeStudentLabModal() {
      const modal = document.getElementById('studentLabModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function calcLabModalScores() {
      let totalGradedSum = 0;
