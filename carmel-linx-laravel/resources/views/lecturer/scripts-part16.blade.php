      let gradedExpsCount = 0;

      labExperimentsData.forEach(exp => {
        const val = tempStudentExpMarks[exp.id] || {};
        const prereq = parseFloat(val.prerequisite);
        const exec = parseFloat(val.execution);
        const out = parseFloat(val.output);
        const rough = parseFloat(val.rough_record);
        const fair = parseFloat(val.fair_record);

        if (!isNaN(prereq) && !isNaN(exec) && !isNaN(out) && !isNaN(rough) && !isNaN(fair)) {
          totalGradedSum += (prereq + exec + out + rough + fair);
          gradedExpsCount++;
        }
      });

      const expAvg = gradedExpsCount > 0 ? (totalGradedSum / gradedExpsCount) : 0;
      document.getElementById('labModalLabelExp').innerText = expAvg.toFixed(2);

      // Model test
      const t1_co1 = parseFloat(document.getElementById('labScore_t1_co1').value) || 0;
      const t1_co2 = parseFloat(document.getElementById('labScore_t1_co2').value) || 0;
      const t2_co3 = parseFloat(document.getElementById('labScore_t2_co3').value) || 0;
      const t2_co4 = parseFloat(document.getElementById('labScore_t2_co4').value) || 0;

      const t1Total = t1_co1 + t1_co2;
      const t2Total = t2_co3 + t2_co4;
      const testsAvg = (t1Total + t2Total) / 2;

      document.getElementById('labModalT1Sum').innerText = `${t1Total.toFixed(1)} / 15`;
      document.getElementById('labModalT2Sum').innerText = `${t2Total.toFixed(1)} / 15`;
      document.getElementById('labModalLabelTest').innerText = testsAvg.toFixed(2);

      // Project & Attendance
      const projectMark = parseFloat(document.getElementById('labScore_projectMark').value) || 0;
      const attMark = parseFloat(document.getElementById('labScore_attendanceMark').value) || 0;

      const totalCA = expAvg + testsAvg + projectMark + attMark;
      document.getElementById('labModalLabelInternals').innerText = `${totalCA.toFixed(2)} / 75`;
    }

    function saveStudentLabEvaluation() {
      const regNo = gradingStudentReg;
      if (!regNo) return;

      const projectTopic = document.getElementById('labScore_projectTopic').value;
      const projectMark = document.getElementById('labScore_projectMark').value;
      const attMark = document.getElementById('labScore_attendanceMark').value;
      const boardExamMark = document.getElementById('labScore_boardExam').value;

      // Tests
      const tests = {
        'Test 1': {
          'CO1': document.getElementById('labScore_t1_co1').value,
          'CO2': document.getElementById('labScore_t1_co2').value
        },
        'Test 2': {
          'CO3': document.getElementById('labScore_t2_co3').value,
          'CO4': document.getElementById('labScore_t2_co4').value
        }
      };

      fetch(`/api/classroom/${currentSubjectId}/practical/evaluate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
          reg_no: regNo,
          open_ended_project_topic: projectTopic,
          micro_project: projectMark,
          attendance_marks: attMark,
          board_exam_marks: boardExamMark,
          tests,
          experiments: tempStudentExpMarks
        })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          alert('Evaluation saved successfully.');
          closeStudentLabModal();
          fetchPracticalEvaluations();
        } else {
          alert(res.message);
        }
      })
      .catch(() => alert('Failed to save student evaluation.'));
    }

    // Manage Experiments Modal Controllers
    function openManageExperimentsModal() {
      // Check if databank has previous data
      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/databank`)
      .then(res => res.json())
      .then(res => {
        const importBtn = document.getElementById('btnImportDatabank');
        if (res.status === 'SUCCESS' && res.has_data) {
          importBtn.classList.remove('hidden');
        } else {
          importBtn.classList.add('hidden');
        }
      });

      renderManageExperimentsList();

      const modal = document.getElementById('manageExperimentsModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeManageExperimentsModal() {
      const modal = document.getElementById('manageExperimentsModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function renderManageExperimentsList() {
      const tbody = document.getElementById('manageExpsTableBody');
      tbody.innerHTML = '';

      if (labExperimentsData.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="4" class="p-6 text-center text-slate-500 font-bold">
              No experiments set up yet. Create experiments using the form above.
            </td>
          </tr>
        `;
        return;
      }

      labExperimentsData.forEach(exp => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-100 hover:bg-slate-50/70";
        tr.innerHTML = `
          <td class="p-3 text-center font-bold text-slate-455 font-mono">${exp.experiment_no}</td>
          <td class="p-3 text-slate-200 font-medium text-sm whitespace-pre-wrap leading-relaxed">${exp.title}</td>
          <td class="p-3 text-center font-bold text-blue-400">${exp.co_tag}</td>
          <td class="p-3 text-center whitespace-nowrap space-x-2">
            <button onclick="editExperiment(${exp.id}, '${exp.experiment_no}', '${exp.title.replace(/'/g, "\\'")}', '${exp.co_tag}')" class="px-2.5 py-1 bg-slate-800 text-slate-300 hover:text-white rounded font-bold cursor-pointer">Edit</button>
            <button onclick="deleteExperiment(${exp.id})" class="px-2.5 py-1 bg-red-950/40 text-red-400 hover:text-red-300 rounded font-bold cursor-pointer border border-red-900/30">Delete</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function savePracticalExperiment(event) {
      event.preventDefault();
      const expId = document.getElementById('expEditId').value;
      const no = document.getElementById('expFormNo').value;
      const title = document.getElementById('expFormTitle').value;
      const co = document.getElementById('expFormCo').value;

      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/save`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ id: expId, experiment_no: no, title: title, co_tag: co })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          // Reset form
          document.getElementById('expEditId').value = '';
          document.getElementById('expFormNo').value = '';
          document.getElementById('expFormTitle').value = '';
          document.getElementById('btnSaveExp').innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Add Experiment';

          alert("Experiment successfully saved!");
          fetchPracticalEvaluations();
          setTimeout(() => renderManageExperimentsList(), 300);
        } else {
          alert(res.message);
        }
      })
      .catch(() => alert('Failed to save experiment.'));
    }

    function editExperiment(id, no, title, co) {
      document.getElementById('expEditId').value = id;
      document.getElementById('expFormNo').value = no;
      document.getElementById('expFormTitle').value = title;
      document.getElementById('expFormCo').value = co;
      document.getElementById('btnSaveExp').innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Update';
    }

    function deleteExperiment(id) {
      if (!confirm('Are you sure you want to delete this experiment? All graded marks for this experiment will be permanently deleted!')) return;

      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
        fetchPracticalEvaluations();
        setTimeout(() => renderManageExperimentsList(), 300);
      });
    }

    function importFromDatabank() {
      if (!confirm('This will import the standard list of experiments configured for this subject code. Existing student grades for existing matching experiment numbers will not be modified. Proceed?')) return;

      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/import`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
        fetchPracticalEvaluations();
        setTimeout(() => renderManageExperimentsList(), 300);
      })
      .catch(() => alert('Import failed.'));
    }

    // Manage Tests modal
    function openManageTestsModal() {
      document.getElementById('designTestName').value = 'Test 1';
      renderTestQuestionsFields();

      const modal = document.getElementById('manageTestsModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeManageTestsModal() {
      const modal = document.getElementById('manageTestsModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function renderTestQuestionsFields() {
      const activeTestDesign = document.getElementById('designTestName').value;
      const container = document.getElementById('testQuestionsFieldsContainer');
      container.innerHTML = '';

      const test = labTestsData.find(t => t.test_name === activeTestDesign);
      const existingQ = test ? test.questions : {};

      const cos = activeTestDesign === 'Test 1' ? ['CO1', 'CO2'] : ['CO3', 'CO4'];

      cos.forEach(co => {
        const coQ = existingQ[co] || ['', ''];
        const card = document.createElement('div');
        card.className = "bg-white border border-slate-200 p-5 rounded-2xl space-y-3 shadow-2xs";
        card.innerHTML = `
          <h4 class="text-sm font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
            <span class="px-2.5 py-0.5 bg-blue-500/10 text-blue-400 rounded text-xs">${co}</span> Questions (Choice of 1 out of 2)
          </h4>
          <div class="space-y-3">
            <div>
              <label class="text-xs font-bold text-slate-400 uppercase block mb-1">Option A (7.5 Marks)</label>
              <textarea name="q_${co}_0" placeholder="Enter question description..." required rows="2" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 font-medium text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs resize-y">${coQ[0] || ''}</textarea>
            </div>
            <div>
              <label class="text-xs font-bold text-slate-400 uppercase block mb-1">Option B (7.5 Marks)</label>
              <textarea name="q_${co}_1" placeholder="Enter question description..." required rows="2" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 font-medium text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs resize-y">${coQ[1] || ''}</textarea>
            </div>
          </div>
        `;
        container.appendChild(card);
      });
    }

    function savePracticalTestQuestions(event) {
      event.preventDefault();
      const testName = document.getElementById('designTestName').value;
      const cos = testName === 'Test 1' ? ['CO1', 'CO2'] : ['CO3', 'CO4'];

      const questions = {};
      cos.forEach(co => {
        const q0 = document.querySelector(`textarea[name="q_${co}_0"]`).value;
        const q1 = document.querySelector(`textarea[name="q_${co}_1"]`).value;
        questions[co] = [q0, q1];
      });

      fetch(`/api/classroom/${currentSubjectId}/practical/tests/save`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ test_name: testName, questions })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          alert('Test config saved successfully.');
          fetch(`/api/classroom/${currentSubjectId}/practical/evaluations`)
          .then(r => r.json())
          .then(innerRes => {
            if (innerRes.status === 'SUCCESS') {
              labTestsData = innerRes.tests || [];
              closeManageTestsModal();
            }
          });
        } else {
          alert(res.message);
        }
      })
      .catch(() => alert('Failed to save test configuration.'));
    }

    // Auto-Generate planner
    function openGeneratePlannerModal() {
      const modal = document.getElementById('generatePlannerModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeGeneratePlannerModal() {
      const modal = document.getElementById('generatePlannerModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function generatePlannerFromExperiments(event) {
      event.preventDefault();
      const session_type = document.getElementById('genPlannerBatchMode').value;
      const allocated_hours = document.getElementById('genPlannerHours').value;

      fetch(`/api/classroom/${currentSubjectId}/practical/lesson-plans/generate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ session_type, allocated_hours })
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
        if (res.status === 'SUCCESS') {
          closeGeneratePlannerModal();
          loadCourseDetails(currentSubjectId);
        }
      })
      .catch(() => alert('Failed to generate lesson planner.'));
    }

    // CO-PO Matrix
    function fetchPracticalCoPoMapping() {
      const tbody = document.getElementById('labCoPoMappingTbody');
      tbody.innerHTML = '<tr><td colspan="16" class="p-8 text-center text-slate-500 font-bold text-xs animate-pulse">Loading Articulation Matrix...</td></tr>';

      fetch(`/api/classroom/${currentSubjectId}/practical/copo-mapping`)
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          tbody.innerHTML = '';
          const matrix = res.mapping || {};
          const descriptions = {
            'CO1': 'Formulate solutions for laboratory tasks using theoretical principles and prerequisites.',
            'CO2': 'Conduct structured experiments, verify outputs, and log observations accurately.',
            'CO3': 'Analyze experimental results, troubleshoot errors, and draw logical conclusions.',
            'CO4': 'Demonstrate open-ended problem solving ability and technical documentation skills.'
          };

          ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
            const tr = document.createElement('tr');
            tr.className = "border-b border-slate-100 hover:bg-slate-50/70 text-slate-700 font-medium";
            
            let cells = `<td class="p-3 font-bold text-blue-400 whitespace-nowrap">${co}</td>`;
            cells += `<td class="p-3 text-slate-350 leading-relaxed font-bold text-xs">${descriptions[co]}</td>`;

            // PO1 to PO11 inputs
            for (let i = 1; i <= 11; i++) {
              const val = matrix[co] && matrix[co]['PO' + i] ? matrix[co]['PO' + i] : '';
              cells += `<td class="p-1"><input type="number" min="1" max="3" value="${val}" class="w-10 bg-white border border-slate-200 rounded-lg px-1.5 py-1 text-center font-bold text-emerald-700 focus:border-blue-500 outline-none text-sm shadow-2xs" data-co="${co}" data-target="PO${i}"></td>`;
            }

            // PSO1 to PSO3 inputs
            for (let i = 1; i <= 3; i++) {
              const val = matrix[co] && matrix[co]['PSO' + i] ? matrix[co]['PSO' + i] : '';
              cells += `<td class="p-1"><input type="number" min="1" max="3" value="${val}" class="w-10 bg-white border border-slate-200 rounded-lg px-1.5 py-1 text-center font-bold text-blue-700 focus:border-blue-500 outline-none text-sm shadow-2xs" data-co="${co}" data-target="PSO${i}"></td>`;
            }

            tr.innerHTML = cells;
            tbody.appendChild(tr);
          });
        }
      })
      .catch(() => {
        tbody.innerHTML = '<tr><td colspan="16" class="p-8 text-center text-red-400 font-bold text-xs">Failed to load articulation matrix.</td></tr>';
      });
    }

    function saveCoPoMappingMatrix() {
      const inputs = document.querySelectorAll('#labCoPoMappingTbody input[data-co]');
      const mapping = {
        'CO1': {}, 'CO2': {}, 'CO3': {}, 'CO4': {}
      };

      inputs.forEach(input => {
        const co = input.getAttribute('data-co');
        const target = input.getAttribute('data-target');
        const val = input.value ? parseInt(input.value) : null;
        if (val) {
          mapping[co][target] = val;
        }
      });

      fetch(`/api/classroom/${currentSubjectId}/practical/copo-mapping/save`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ mapping })
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
      })
      .catch(() => alert('Failed to save mapping matrix.'));
    }

    // Live AI Status Indicator for Faculty
    document.addEventListener("DOMContentLoaded", () => {
      fetch('/api/system/ai-status')
        .then(res => res.json())
        .then(data => {
          const badge = document.getElementById('aiStatusBadge');
          if (badge && data.status === 'SUCCESS') {
            badge.classList.remove('hidden');
            if (data.ai_generation_enabled) {
              badge.innerHTML = `<span class="px-2.5 py-1.5 bg-emerald-950/40 text-emerald-400 border border-emerald-900/60 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping shrink-0"></span> AI Active</span>`;
            } else {
              badge.innerHTML = `<span class="px-2.5 py-1.5 bg-amber-950/40 text-amber-400 border border-amber-900/60 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm" title="Gemini AI is deactivated to save API credits. Lesson plans, descriptive questions, and MCQs are generated from local databases and question banks."><span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span> AI Offline (Local DB)</span>`;
            }
          }
        })
        .catch(err => console.error("Failed to load system AI status:", err));

      requestAnimationFrame(function() {
        document.body.classList.remove('sidebar-preload');
      });
    });
  
  window.initLucide = function() {
    if (window.lucide && typeof window.lucide.createIcons === "function") {
      window.lucide.createIcons();
    }
  };

  document.addEventListener("DOMContentLoaded", () => {
    window.initLucide();
  });
</script>

  @include('partials.support_desk_overlay')
</body>
</html>
