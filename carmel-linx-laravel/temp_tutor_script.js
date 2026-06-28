 window.TARGET_REG_NO = """"; 

    function switchPanel(panelId, title) {
      const panels = ['exams', 'marks', 'profile', 'mentoring', 'activity'];
      
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        if (id === panelId) {
          if (el) { el.classList.remove('hidden'); el.classList.add('fade-up'); }
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (el) el.classList.add('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
        }
      });

      const titles = { exams: 'Works To Do', marks: 'Academic Stats', profile: 'My Profile', mentoring: 'Mentoring Diary', activity: 'Activity Points' };
      const subtitles = { 
        exams: 'Manage your pending assignments and active tests.', 
        marks: 'Your semester-wise academic progress.', 
        profile: 'Your personal and academic details.',
        mentoring: 'Mentoring sessions and student data.',
        activity: 'Track and claim your extracurricular points.'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];
      document.getElementById('panelSubtitle').innerText = subtitles[panelId];

      if (panelId === 'mentoring') {
        if (!mentoringLoaded) loadMentoringDiary();
      } else if (panelId === 'activity') {
        loadActivityPoints();
      }
    }

      document.addEventListener('DOMContentLoaded', () => {
        loadStudentTests();
        if (!academicReportLoaded) loadAcademicReport();
      });

    let academicReportLoaded = false;
    let mentoringLoaded = false;
    let academicData = null;
    let currentActiveSem = 1;
    let cgpaChartInstance = null;
    
    let currentTaskStats = {
       assignments_active: 0,
       assignments_submitted: 0,
       written_tests_active: 0,
       written_tests_submitted: 0,
       online_tests_active: 0,
       online_tests_submitted: 0
    };

    function updateStatsHeader(acStats, tStats) {
       if (acStats) {
          currentTaskStats.assignments_active = acStats.assignments_active || 0;
          currentTaskStats.assignments_submitted = acStats.assignments_submitted || 0;
          currentTaskStats.written_tests_active = acStats.written_tests_active || 0;
          currentTaskStats.written_tests_submitted = acStats.written_tests_submitted || 0;
       }
       if (tStats) {
          currentTaskStats.online_tests_active = tStats.online_tests_active || 0;
          currentTaskStats.online_tests_submitted = tStats.online_tests_submitted || 0;
       }
       document.getElementById('statActiveTests').innerText = currentTaskStats.online_tests_active;
       document.getElementById('statActiveAssign').innerText = currentTaskStats.assignments_active;
       document.getElementById('statWrittenTests').innerText = currentTaskStats.written_tests_active;
       document.getElementById('statTestsDone').innerText = currentTaskStats.online_tests_submitted;
       document.getElementById('statAssignDone').innerText = currentTaskStats.assignments_submitted;
       document.getElementById('statWrittenTestsDone').innerText = currentTaskStats.written_tests_submitted;
       document.getElementById('statPendingTotal').innerText = currentTaskStats.online_tests_active + currentTaskStats.assignments_active + currentTaskStats.written_tests_active;
    }

    function loadAcademicReport() {
      fetch('/api/student/academic-report')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            academicReportLoaded = true;
            academicData = data;
            const overall = data.overall || {};
            document.getElementById('overallCgpa').innerText = overall.cgpa || '0';
            document.getElementById('overallActivityPoints').innerText = overall.activity_points || '0';
            if (overall.current_semester) {
              document.getElementById('headerSemesterText').classList.remove('hidden');
              document.getElementById('headerSemValue').innerText = overall.current_semester;
            }
            currentActiveSem = data.overall.current_semester || 1;

            if (data.stats) updateStatsHeader(data.stats, null);
            renderActiveTasks(data.active_tasks || []);
            renderCgpaChart(data.semesters);
            renderSemesterTabs(data.semesters);
            renderGodTable(currentActiveSem);
          }
        });
    }

    function renderActiveTasks(tasks) {
      const container = document.getElementById('studentActiveTasksContainer');
      if (!tasks || tasks.length === 0) {
        container.innerHTML = `<div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs">No active assignments or tests at the moment.</div>`;
        return;
      }
      
      let html = '';
      tasks.forEach((t, index) => {
        const isExp = t.status === 'Expired' || t.status === 'Completed';
        const stCol = isExp ? 'text-rose-400 bg-rose-500/10 border-rose-500/20' : 'text-teal-400 bg-teal-500/10 border-teal-500/20';
        const icon = t.type === 'Assignment' ? 'assignment' : 'edit_document';

        let qHtml = '';
        if (t.questions && t.questions.length > 0) {
          qHtml = `<div class="mt-4 pt-4 border-t border-slate-800 hidden" id="taskQ_${index}">
            <h4 class="text-xs uppercase font-black text-slate-400 mb-2">Assignment Questions</h4>
            <ul class="space-y-2 text-xs text-slate-300 font-medium list-disc pl-4">
              ${t.questions.map(q => `<li>${q}</li>`).join('')}
            </ul>
          </div>`;
        }

        let actionBtn = '';
        if (t.type === 'Assignment' && !isExp) {
          actionBtn = `<button onclick="markManualTaskSubmitted('${t.subject_code}', '${t.co_tag}', 'Assignment')" class="mt-3 w-full py-2 bg-blue-600/80 hover:bg-blue-500 text-white rounded font-bold text-xs transition-premium">Mark as Submitted</button>`;
        }

        html += `
          <div class="bg-slate-900/80 border border-slate-700/60 rounded-xl overflow-hidden mb-1">
            <!-- Collapsible Header -->
            <div onclick="document.getElementById('co_task_${index}').classList.toggle('hidden'); this.querySelector('.arrow-icon').innerText = document.getElementById('co_task_${index}').classList.contains('hidden') ? 'expand_more' : 'expand_less';" 
                 class="px-4 py-3.5 bg-slate-950/40 hover:bg-slate-950/70 border-b border-slate-800/60 flex justify-between items-center cursor-pointer transition-premium">
              <div class="flex items-center gap-3">
                <span class="material-symbols-rounded text-blue-400 text-xs">${icon}</span>
                <div>
                  <h4 class="font-bold text-xs text-slate-200 uppercase">${t.type} - ${t.co_tag}</h4>
                  <p class="text-xs font-black text-purple-400 uppercase tracking-wider mt-0.5">${t.subject_code} - ${t.subject}</p>
                </div>
              </div>
              <span class="material-symbols-rounded text-slate-500 text-xs arrow-icon">expand_more</span>
            </div>
            <!-- Collapsible Content -->
            <div id="co_task_${index}" class="hidden p-4 bg-slate-950/10 border-t border-slate-800/40">
              <div class="flex items-center gap-2 mb-3">
                  <span class="px-2 py-0.5 rounded text-xs font-black uppercase tracking-widest ${stCol}">${t.status}</span>
              </div>
              <div class="grid grid-cols-2 gap-4 mb-4 text-xs text-slate-400 font-semibold">
                <div class="space-y-1">
                  <div>Start Date: <span class="text-slate-200 font-bold">${t.start ? new Date(t.start).toLocaleDateString() : '-'}</span></div>
                </div>
                <div class="space-y-1">
                  <div>Deadline: <span class="text-slate-200 font-bold font-mono">${t.deadline ? new Date(t.deadline).toLocaleDateString() : '-'}</span></div>
                </div>
              </div>
              ${qHtml ? `<button onclick="document.getElementById('taskQ_${index}').classList.toggle('hidden')" class="w-full mt-2 py-2 text-xs font-bold text-blue-400 hover:text-blue-300 bg-blue-500/5 rounded-xl transition-premium flex justify-center items-center gap-1"><span class="material-symbols-rounded text-xs">visibility</span> View Questions</button>` : ''}
              ${qHtml}
              ${actionBtn}
            </div>
          </div>
        `;
      });
      container.innerHTML = html;
      container.className = "flex flex-col gap-1 mt-4 mb-6";
    }

    function markManualTaskSubmitted(subjectCode, coTag, category) {
      if (!confirm("Are you sure you want to mark this task as submitted?")) return;
      fetch('/api/student/tasks/submit', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ subject_code: subjectCode, co_tag: coTag, category: category, status: 'Submitted' })
      })
      .then(res => res.json())
      .then(data => {
          if (data.status === 'SUCCESS') {
              alert(data.message);
              loadAcademicReport(); // reload tasks
          } else {
              alert(data.message || "Failed to submit.");
          }
      });
    }

    function renderCgpaChart(semesters) {
      const ctx = document.getElementById('cgpaChart').getContext('2d');
      if (cgpaChartInstance) cgpaChartInstance.destroy();

      const labels = semesters.map(s => `S${s.semester}`);
      const data = semesters.map(s => s.sgpa || 0);

      cgpaChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'SGPA',
            data: data,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#fff',
            pointRadius: 4,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 } } },
            y: { 
              grid: { color: 'rgba(30,41,59,0.5)' }, 
              ticks: { color: '#64748b', font: { size: 10 } },
              min: 0, max: 10
            }
          }
        }
      });
    }

    function renderSemesterTabs(semesters) {
      const container = document.getElementById('semesterTabsContainer');
      let html = '';
      semesters.forEach(s => {
        const isActive = s.semester === currentActiveSem;
        const isCurrent = s.is_current === true;
        const cls = isActive 
          ? 'bg-blue-600/20 text-blue-400 border-blue-500/20' 
          : 'bg-transparent text-slate-500 hover:text-slate-300 hover:bg-slate-800 border-transparent';
        const badge = isCurrent ? `<span class="ml-1 text-[8px] bg-teal-500/20 text-teal-400 px-1 py-0.5 rounded font-black">NOW</span>` : '';
        html += `
          <button onclick="renderGodTable(${s.semester})" id="btnSemTab_${s.semester}" class="sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border ${cls}">
            Semester ${s.semester}${badge}
          </button>
        `;
      });
      container.innerHTML = html;
    }

    function renderGodTable(semId) {
      currentActiveSem = semId;
      document.querySelectorAll('.sem-tab').forEach(btn => {
        btn.className = 'sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border bg-transparent text-slate-500 hover:text-slate-300 hover:bg-slate-800 border-transparent';
      });
      const actBtn = document.getElementById(`btnSemTab_${semId}`);
      if(actBtn) actBtn.className = 'sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border bg-blue-600/20 text-blue-400 border-blue-500/20';

      const container = document.getElementById('academicReportContent');
      const semData = academicData.semesters.find(s => s.semester == semId);
      if (!semData || !semData.subjects || semData.subjects.length === 0) {
        container.innerHTML = `<div class="py-12 text-center text-slate-500 font-bold text-xs border border-slate-800/50 rounded-2xl bg-slate-900/30">No academic data available for Semester ${semId}.</div>`;
        return;
      }

      let rows = '';
      semData.subjects.forEach(sub => {
        const trClass = "border-b border-slate-800/50 hover:bg-slate-900/30 transition-premium";
        rows += `
          <tr class="${trClass}">
            <td class="p-4 whitespace-nowrap">
              <div class="font-black text-slate-200 text-xs">${sub.subject_code}</div>
              <div class="text-xs text-slate-500 font-bold truncate max-w-[150px]" title="${sub.subject_name}">${sub.subject_name}</div>
            </td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-300">${sub.CO1 !== null ? sub.CO1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-300 bg-slate-950/20">${sub.CO2 !== null ? sub.CO2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-300">${sub.CO3 !== null ? sub.CO3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-300 bg-slate-950/20">${sub.CO4 !== null ? sub.CO4 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400 border-l border-slate-800">${sub.Assg1 !== null ? sub.Assg1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400">${sub.Assg2 !== null ? sub.Assg2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400">${sub.Assg3 !== null ? sub.Assg3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400">${sub.Assg4 !== null ? sub.Assg4 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400 border-l border-slate-800">${sub.WT1 !== null ? sub.WT1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400">${sub.WT2 !== null ? sub.WT2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400">${sub.WT3 !== null ? sub.WT3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400">${sub.WT4 !== null ? sub.WT4 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400 border-l border-slate-800">${sub.OT1 !== null ? sub.OT1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400">${sub.OT2 !== null ? sub.OT2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400">${sub.OT3 !== null ? sub.OT3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400">${sub.OT4 !== null ? sub.OT4 : '-'}</td>
            <td class="p-4 text-center text-xs font-black border-l border-slate-800 ${sub.attendance_percentage < 75 ? 'text-rose-400' : 'text-slate-300'}">
              ${sub.attendance_percentage}%
            </td>
          </tr>
        `;
      });

      container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
          <div class="flex gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 flex items-center gap-2 shadow-inner">
              <span class="material-symbols-rounded text-slate-400 text-xs">stars</span>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">SGPA:</span>
              <span class="text-xs font-black text-white">${semData.sgpa || '-'}</span>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 flex items-center gap-2 shadow-inner">
              <span class="material-symbols-rounded text-slate-400 text-xs">local_activity</span>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Points:</span>
              <span class="text-xs font-black text-white">${semData.activity_points || '-'}</span>
            </div>
          </div>
        </div>

        <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl overflow-x-auto shadow-2xl">
          <table class="w-full text-left border-collapse min-w-[1200px]">
            <thead>
              <tr class="bg-slate-900/80 border-b border-slate-800 text-xs uppercase tracking-wider font-black text-slate-400">
                <th class="p-4 font-black">Subject</th>
                <th class="p-4 text-center" colspan="4">Sum COs</th>
                <th class="p-4 text-center border-l border-slate-800 text-blue-400" colspan="4">Assignments</th>
                <th class="p-4 text-center border-l border-slate-800 text-emerald-400" colspan="4">Written Tests</th>
                <th class="p-4 text-center border-l border-slate-800 text-purple-400" colspan="4">Online Tests</th>
                <th class="p-4 text-center border-l border-slate-800">Attend.</th>
              </tr>
              <tr class="bg-slate-900/40 border-b border-slate-800/50 text-xs uppercase font-bold text-slate-500">
                <th class="p-2"></th>
                <th class="p-2 text-center w-10 border-l border-slate-800/50">C1</th><th class="p-2 text-center w-10 bg-slate-950/20">C2</th><th class="p-2 text-center w-10">C3</th><th class="p-2 text-center w-10 bg-slate-950/20">C4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">A1</th><th class="p-2 text-center w-10">A2</th><th class="p-2 text-center w-10">A3</th><th class="p-2 text-center w-10">A4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">W1</th><th class="p-2 text-center w-10">W2</th><th class="p-2 text-center w-10">W3</th><th class="p-2 text-center w-10">W4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">O1</th><th class="p-2 text-center w-10">O2</th><th class="p-2 text-center w-10">O3</th><th class="p-2 text-center w-10">O4</th>
                <th class="p-2 text-center w-16 border-l border-slate-800">%</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/30">
              ${rows}
            </tbody>
          </table>
        </div>
      `;
    }

    function updateSbteRegNo() {
      const val = document.getElementById('sbteRegNoInput').value.trim();
      const alertEl = document.getElementById('sbteAlert');
      if (!val) {
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block';
        alertEl.innerText = 'Please enter your SBTE Register Number.';
        return;
      }
      fetch('/api/student/update-sbte-reg', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ sbteRegNo: val })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block';
          alertEl.innerText = 'SBTE Register Number saved! Reload the page to see it confirmed.';
        } else {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block';
          alertEl.innerText = data.message || 'Failed to save. Please try again.';
        }
      })
      .catch(() => {
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block';
        alertEl.innerText = 'Network error. Please try again.';
      });
    }

    function changePassword() {
      const oldPwd = document.getElementById('oldPwd').value.trim();
      const newPwd = document.getElementById('newPwd').value.trim();
      const alert = document.getElementById('pwdAlert');
      if (!oldPwd || !newPwd) {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Please fill in both fields.";
        return;
      }
      if (newPwd.length < 6) {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "New password must be at least 6 characters.";
        return;
      }
      fetch('/api/student/change-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ oldPassword: oldPwd, newPassword: newPwd })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "Password updated successfully.";
          document.getElementById('oldPwd').value = '';
          document.getElementById('newPwd').value = '';
        } else {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message || 'Password change failed.';
        }
      })
      .catch(() => {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = 'Request failed. Please try again.';
      });
    }

    // Init stub stats and load tests
    document.addEventListener('DOMContentLoaded', () => {
      loadStudentTests();
      loadAcademicReport();
    });

    // TEST ENGINE LOGIC
    let currentTestId = null;
    let timerInterval = null;
    let endTimeMs = null;

    function loadStudentTests() {
      fetch('/api/student/online-tests')
        .then(res => res.json())
        .then(data => {
          let container = document.getElementById('studentActiveTestsList');
          if (data.status === 'SUCCESS' && data.tests && data.tests.length > 0) {
            let html = '';
            data.tests.forEach(t => {
              let actionHtml = '';
              if (t.can_take) {
                actionHtml = `<button onclick="startOnlineTest('${t.test_id}')" class="w-full py-2 bg-purple-600/80 hover:bg-purple-500 text-white rounded font-bold text-xs transition-premium">Start Test</button>`;
              } else if (t.status_message && t.status_message.startsWith('Starts')) {
                actionHtml = `<button disabled class="w-full py-2 bg-slate-800/40 text-slate-400 rounded font-bold text-xs text-center border border-slate-700/50 mb-2 cursor-not-allowed flex items-center justify-center gap-2"><span class="material-symbols-rounded text-xs">lock</span> ${t.status_message}</button>`;
              } else if (t.my_attempts > 0) {
                actionHtml = `<div class="w-full py-2 bg-emerald-900/40 text-emerald-400 rounded font-bold text-xs text-center border border-emerald-800/50 mb-2">Best Score: ${t.best_score || 0}</div>`;
              } else {
                actionHtml = `<div class="w-full py-2 bg-slate-800/40 text-slate-400 rounded font-bold text-xs text-center border border-slate-700/50 mb-2">${t.status_message || 'Expired'}</div>`;
              }

              let hasEnded = false;
              if (t.end_time) {
                let et = new Date(t.end_time.replace(' ', 'T'));
                hasEnded = (new Date() >= et);
              }
              if (hasEnded && t.my_attempts > 0) {
                actionHtml += `<button onclick="viewAnswerKey('${t.test_id}')" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white rounded font-bold text-xs transition-premium">View Answer Key</button>`;
              } else if (t.my_attempts > 0 && !t.can_take) {
                let formattedEndTime = new Date(t.end_time).toLocaleString();
                actionHtml += `<div class="text-xs text-center text-slate-400 font-semibold mt-1 bg-slate-950/30 p-1.5 rounded border border-slate-800/50">Answer key unlocks after test ends: <br/>${formattedEndTime}</div>`;
              }

              html += `
                <div class="bg-slate-900/80 border border-slate-700/60 rounded-xl overflow-hidden mb-1">
                  <!-- Collapsible Header -->
                  <div onclick="document.getElementById('co_exam_${t.test_id}').classList.toggle('hidden'); this.querySelector('.arrow-icon').innerText = document.getElementById('co_exam_${t.test_id}').classList.contains('hidden') ? 'expand_more' : 'expand_less';" 
                       class="px-4 py-3.5 bg-slate-950/40 hover:bg-slate-950/70 border-b border-slate-800/60 flex justify-between items-center cursor-pointer transition-premium">
                    <div class="flex items-center gap-3">
                      <span class="material-symbols-rounded text-purple-400 text-xs">quiz</span>
                      <div>
                        <h4 class="font-bold text-xs text-slate-200">${t.test_name}</h4>
                        <p class="text-xs font-black text-purple-400 uppercase tracking-wider mt-0.5">${t.subject_code} - ${t.subject_name || t.subject_code}</p>
                      </div>
                    </div>
                    <span class="material-symbols-rounded text-slate-500 text-xs arrow-icon">expand_more</span>
                  </div>
                  <!-- Collapsible Content -->
                  <div id="co_exam_${t.test_id}" class="hidden p-4 bg-slate-950/10 border-t border-slate-800/40">
                    <div class="grid grid-cols-2 gap-4 mb-4 text-slate-400 font-semibold">
                      <div class="space-y-1">
                        <div>Duration: <span class="text-slate-200 font-bold">${t.duration} Mins</span></div>
                        <div>Total Questions: <span class="text-slate-200 font-bold">${t.mcq_count} MCQs</span></div>
                      </div>
                      <div class="space-y-1">
                        <div>Attempts: <span class="text-slate-200 font-bold">${t.my_attempts}/${t.max_attempts}</span></div>
                        <div>Deadline: <span class="text-slate-200 font-bold font-mono">${t.end_time ? new Date(t.end_time).toLocaleString() : 'No Limit'}</span></div>
                      </div>
                    </div>
                    <div class="mt-3">
                      ${actionHtml}
                    </div>
                  </div>
                </div>
              `;
            });
            container.innerHTML = html;
            container.className = "flex flex-col gap-1 mt-4 mb-6";
          } else {
            container.innerHTML = `<div class="col-span-full p-4 bg-slate-900/60 border border-slate-800/60 rounded-xl text-center text-xs text-slate-500">No active tests available right now.</div>`;
            container.className = "mt-4 mb-6";
          }

          if (data.stats) updateStatsHeader(null, data.stats);
        });
    }

    function startOnlineTest(testId) {
      if(!confirm("Are you sure you want to start this test? The timer will begin immediately.")) return;
      
      fetch(`/api/student/online-tests/${testId}/start`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentTestId = testId;
          renderTestEngine(data.questions, data.duration);
        } else {
          alert(data.message || "Could not start test.");
        }
      });
    }

    function renderTestEngine(questions, durationMins) {
      document.getElementById('testEngineModal').classList.remove('hidden');

      let html = '<div class="max-w-3xl mx-auto space-y-6 pb-20">';
      questions.forEach((q, idx) => {
        let optionsHtml = '';
        q.options.forEach((opt, oIdx) => {
          optionsHtml += `
            <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-700/50 bg-slate-900/50 cursor-pointer hover:border-purple-500/50 hover:bg-slate-800 transition-premium">
              <input type="radio" name="q_${idx}" value="${opt}" class="w-4 h-4 text-purple-500 bg-slate-950 border-slate-600 focus:ring-purple-600">
              <span class="text-xs text-slate-300">${opt}</span>
            </label>
          `;
        });
        html += `
          <div class="question-container bg-slate-950 border border-slate-800 rounded-xl p-6 shadow-lg">
             <div class="flex items-start gap-4 mb-4">
               <span class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-500/10 text-purple-400 flex items-center justify-center font-black text-xs border border-purple-500/20">${idx+1}</span>
               <h4 class="text-xs font-bold text-slate-100 mt-1">${q.q}</h4>
             </div>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-12">
               ${optionsHtml}
             </div>
          </div>
        `;
      });
      html += '</div>';
      document.getElementById('testQuestionsContainer').innerHTML = html;

      // Start Timer
      endTimeMs = Date.now() + (durationMins * 60 * 1000);
      timerInterval = setInterval(updateTimer, 1000);
      updateTimer();
    }

    function updateTimer() {
      let now = Date.now();
      let diff = endTimeMs - now;
      if (diff <= 0) {
        clearInterval(timerInterval);
        document.getElementById('liveTimer').innerText = "00:00:00";
        alert("Time is up! Auto-submitting your test.");
        submitTest();
        return;
      }
      
      let h = Math.floor(diff / (1000 * 60 * 60));
      let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      let s = Math.floor((diff % (1000 * 60)) / 1000);
      
      document.getElementById('liveTimer').innerText = 
        (h < 10 ? '0'+h : h) + ':' + 
        (m < 10 ? '0'+m : m) + ':' + 
        (s < 10 ? '0'+s : s);
    }

    function cancelTest() {
      if(!confirm("Are you sure? Your progress will be lost.")) return;
      document.getElementById('testEngineModal').classList.add('hidden');
    }

    function submitTest() {
      if(!currentTestId) return;
      document.getElementById('testEngineModal').classList.add('hidden');
      
      const formContainers = document.getElementById('testQuestionsContainer').querySelectorAll('.question-container');
      let answers = {};
      formContainers.forEach((container, idx) => {
        let checked = container.querySelector(`input[name="q_${idx}"]:checked`);
        answers[idx] = checked ? checked.value : null;
      });

      fetch(`/api/student/online-tests/${currentTestId}/submit`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ answers })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          // Hide engine, show result modal
          document.getElementById('testEngineModal').classList.add('hidden');
          document.getElementById('testResultModal').classList.remove('hidden');
          setTimeout(() => document.getElementById('resultModalBox').classList.remove('scale-95'), 50);

          document.getElementById('resultScore').innerText = `${data.summary.score}/${data.summary.total}`;
          document.getElementById('resultPercent').innerText = `${data.summary.percentage}%`;
        } else {
          alert(data.message || "Submission failed.");
        }
      });
    }

      function closeResultModal() {
        document.getElementById('testResultModal').classList.add('hidden');
        loadStudentTests(); // refresh the list
        loadAcademicReport(); // refresh academic stats so new marks show up
      }

    function viewAnswerKey(testId) {
      fetch(`/api/student/online-tests/${testId}/answer-key`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('answerKeyTestName').innerText = data.test_name;
            document.getElementById('answerKeyScoreInfo').innerText = `Best Score: ${data.score}/${data.total} (${data.percentage}%)`;
            
            let html = '<div class="max-w-3xl mx-auto space-y-6 pb-20">';
            data.details.forEach((q, idx) => {
              let optionsHtml = '';
              q.options.forEach((opt, oIdx) => {
                let badgeHtml = '';
                let borderClass = 'border-slate-700/50 bg-slate-900/50';
                
                // Color code options
                if (opt === q.correct_ans) {
                  borderClass = 'border-green-500/50 bg-green-950/20';
                  badgeHtml = '<span class="text-xs bg-green-500/20 text-green-400 font-bold px-2 py-0.5 rounded ml-auto">Correct Answer</span>';
                } else if (opt === q.student_ans) {
                  borderClass = 'border-red-500/50 bg-red-950/20';
                  badgeHtml = '<span class="text-xs bg-red-500/20 text-red-400 font-bold px-2 py-0.5 rounded ml-auto">Your Answer</span>';
                }

                optionsHtml += `
                  <div class="flex items-center gap-3 p-3 rounded-lg border ${borderClass} transition-premium">
                    <span class="text-xs text-slate-300">${opt}</span>
                    ${badgeHtml}
                  </div>
                `;
              });

              let correctBadge = q.is_correct 
                ? '<span class="bg-green-500/10 text-green-400 text-xs font-bold px-2.5 py-1 rounded-full border border-green-500/20 flex items-center gap-1"><span class="material-symbols-rounded text-xs">check_circle</span> Correct</span>'
                : `<span class="bg-red-500/10 text-red-400 text-xs font-bold px-2.5 py-1 rounded-full border border-red-500/20 flex items-center gap-1"><span class="material-symbols-rounded text-xs">cancel</span> Incorrect</span>`;

              html += `
                <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 shadow-lg">
                   <div class="flex items-start justify-between gap-4 mb-4">
                     <div class="flex items-start gap-4">
                       <span class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center font-black text-xs border border-slate-700/20">${idx+1}</span>
                       <div>
                         <h4 class="text-xs font-bold text-slate-100 mt-1">${q.q}</h4>
                         <span class="text-xs text-slate-500 font-mono">CO Tag: ${q.co}</span>
                       </div>
                     </div>
                     ${correctBadge}
                   </div>
                   <div class="grid grid-cols-1 gap-3 pl-12">
                     ${optionsHtml}
                   </div>
                </div>
              `;
            });
            html += '</div>';
            
            document.getElementById('answerKeyQuestionsContainer').innerHTML = html;
            document.getElementById('answerKeyModal').classList.remove('hidden');
          } else {
            alert(data.message || "Could not retrieve answer key.");
          }
        });
    }

    function closeAnswerKeyModal() {
      document.getElementById('answerKeyModal').classList.add('hidden');
    }

    function loadActivityPoints() {
      fetch('/api/student/activity-points')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('overallActivityPoints').innerText = data.total_points || 0;
            document.getElementById('verifiedActivityTotal').innerText = data.total_points || 0;
            
            // Progress Bar
            let pts = data.total_points || 0;
            let goal = "";
            let percent = Math.min(100, Math.round((pts / goal) * 100));
            
            const pBar = document.getElementById('activityProgressBar');
            pBar.style.width = percent + '%';
            
            if (percent >= 100) {
              pBar.className = "absolute top-0 left-0 h-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-1000 ease-out";
            } else if (percent >= 50) {
              pBar.className = "absolute top-0 left-0 h-full bg-gradient-to-r from-amber-500 to-orange-400 transition-all duration-1000 ease-out";
            } else {
              pBar.className = "absolute top-0 left-0 h-full bg-gradient-to-r from-red-500 to-rose-400 transition-all duration-1000 ease-out";
            }

            // Split
            let splitHtml = '';
            if (data.split && Object.keys(data.split).length > 0) {
              for (const [segment, pts] of Object.entries(data.split)) {
                splitHtml += `
                  <div class="flex justify-between items-center py-1">
                    <span class="text-xs text-slate-300">${segment}</span>
                    <span class="text-xs font-bold text-emerald-400">${pts}</span>
                  </div>
                `;
              }
            } else {
              splitHtml = '<div class="text-xs text-slate-500 py-1">No verified points yet.</div>';
            }
            document.getElementById('activitySplitList').innerHTML = splitHtml;

            // Claims Table
            const tbody = document.getElementById('activityClaimsTableBody');
            if (data.claims && data.claims.length > 0) {
              let html = '';
              data.claims.forEach(c => {
                let statusClass = 'text-amber-400 bg-amber-500/10 border-amber-500/20';
                if (c.status === 'Verified') statusClass = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
                if (c.status === 'Rejected') statusClass = 'text-rose-400 bg-rose-500/10 border-rose-500/20';
                
                let dateStr = c.created_at ? new Date(c.created_at).toLocaleDateString() : 'N/A';
                let verifiedDateStr = c.verified_at ? new Date(c.verified_at).toLocaleDateString() : '';
                
                let noteHtml = '';
                if (c.status === 'Rejected' && c.rejection_note) {
                  noteHtml = `<div class="mt-1 text-xs text-rose-400/80 leading-tight">Reason: ${c.rejection_note}</div>`;
                }
                if (c.status !== 'Pending' && verifiedDateStr) {
                  noteHtml += `<div class="mt-0.5 text-xs text-slate-500">On: ${verifiedDateStr}</div>`;
                }
                
                html += `
                  <tr class="hover:bg-slate-900/50 transition-colors border-b border-slate-800/40">
                    <td class="p-3 text-xs text-slate-400">${dateStr}</td>
                    <td class="p-3 text-xs font-bold text-slate-300">${c.activity_segment}</td>
                    <td class="p-3 text-xs text-slate-300">${c.activity_name}</td>
                    <td class="p-3 text-xs text-slate-400">${c.level}</td>
                    <td class="p-3">
                      ${c.document_reference ? `<a href="${c.document_reference}" target="_blank" class="text-blue-400 hover:text-blue-300 text-xs underline flex items-center gap-1"><span class="material-symbols-rounded text-[12px]">link</span> View</a>` : '<span class="text-xs text-slate-600">None</span>'}
                    </td>
                    <td class="p-3 text-center text-xs font-bold text-slate-300">${c.points_claimed}</td>
                    <td class="p-3 text-center text-xs font-bold ${c.status === 'Verified' ? 'text-emerald-400' : 'text-slate-500'}">${c.status === 'Verified' ? c.points_awarded : '--'}</td>
                    <td class="p-3 text-right max-w-[120px]">
                      <span class="px-2 py-0.5 rounded border text-xs font-bold uppercase tracking-wider ${statusClass} inline-block">${c.status}</span>
                      ${noteHtml}
                    </td>
                  </tr>
                `;
              });
              tbody.innerHTML = html;
            } else {
              tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-xs">No activity claims submitted yet.</td></tr>`;
            }
          }
        });
    }

    function submitActivityClaim(e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());

      fetch('/api/student/activity-points', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(resData => {
        if (resData.status === 'SUCCESS') {
          form.reset();
          loadActivityPoints();
        } else {
          alert(resData.message || 'Failed to submit claim.');
        }
      });
    }
  

<script>
function switchStudentMentoringTab(tabId) {
  document.querySelectorAll('.smd-content-pane').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('.smd-tab').forEach(el => {
    el.classList.remove('bg-slate-800/80', 'text-blue-400');
    el.classList.add('text-slate-400');
  });

  document.getElementById(tabId).classList.remove('hidden');
  const btn = document.getElementById('tabBtn_' + tabId);
  btn.classList.remove('text-slate-400');
  btn.classList.add('bg-slate-800/80', 'text-blue-400');
}


  let diaryChartInstance = null;

  function renderDiaryAcademicProgress(data) {
    const container = document.getElementById('smdAcademicReport');
    if (!container) return;

    if (!data || !data.academics || Object.keys(data.academics).length === 0) {
      container.innerHTML = '<p class="text-slate-500 text-xs text-center py-8">No academic stats available yet.</p>';
      return;
    }

    // Calculate total activity points
    let activityPoints = 0;
    if (data.extracurricular) {
      data.extracurricular.forEach(ex => {
        if (ex.status === 'Verified') {
          activityPoints += (parseFloat(ex.points_awarded) || 0);
        }
      });
    }

    // Sort semesters ascending for the chart
    const sems = Object.keys(data.academics).sort((a,b) => parseInt(a) - parseInt(b));
    let totalCgpa = 0;
    let chartLabels = [];
    let chartData = [];
    let htmlRows = '';

    // Reverse for UI display so the latest semester is at the top
    const displaySems = [...sems].reverse();

    sems.forEach(sem => {
      let bSummary = null;
      if (data.board) bSummary = data.board.find(b => b.semester == sem);
      let sgpa = bSummary ? parseFloat(bSummary.sgpa) || 0 : 0;
      let cgpa = bSummary ? parseFloat(bSummary.cgpa) || 0 : 0;
      if (cgpa > 0) totalCgpa = cgpa;
      chartLabels.push(`S${sem}`);
      chartData.push(sgpa);
    });

    displaySems.forEach(sem => {
      let bSummary = null;
      if (data.board) bSummary = data.board.find(b => b.semester == sem);
      let sgpa = bSummary ? parseFloat(bSummary.sgpa) || 0 : 0;
      let pts = bSummary ? parseFloat(bSummary.activity_points) || 0 : 0;

      let rows = '';
      const subjects = data.academics[sem] || [];
      if (subjects.length > 0) {
        subjects.forEach(sub => {
           let att = '-';
           let attColor = 'text-emerald-400 font-bold';
           if (sub.attendance_percentage !== undefined && sub.attendance_percentage !== null) {
              att = sub.attendance_percentage + '%';
              attColor = (sub.attendance_percentage < 75) ? 'text-rose-400 font-black' : 'text-emerald-400 font-bold';
           }
           
           rows += `<tr class="border-b border-slate-800/50 hover:bg-slate-900/30 transition-premium">
              <td class="p-2 whitespace-nowrap">
                <div class="font-black text-slate-200 text-xs">${sub.subject_code}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">${sub.subject_name}</div>
              </td>
              <td class="p-2 text-center border-l border-slate-800/50"><span class="text-xs ${attColor}">${att}</span></td>
            </tr>`;
        });
      } else {
        rows = `<tr><td colspan="2" class="p-4 text-center text-xs text-slate-500 font-bold">No subjects available</td></tr>`;
      }

      htmlRows += `
        <details class="mb-4 group bg-slate-900/40 border border-slate-800/60 rounded-xl overflow-hidden shadow-sm">
          <summary class="flex justify-between items-center p-3 cursor-pointer hover:bg-slate-800/50 transition-premium select-none list-none [&::-webkit-details-marker]:hidden">
            <div class="flex items-center gap-3">
              <span class="material-symbols-rounded text-slate-500 group-open:rotate-90 transition-transform">chevron_right</span>
              <h5 class="text-xs font-black text-slate-300 uppercase tracking-widest">Semester ${sem}</h5>
            </div>
            <div class="flex gap-2">
              <div class="bg-slate-950 border border-slate-800 px-2 py-1 rounded flex items-center gap-1.5 shadow-inner">
                <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">SGPA:</span>
                <span class="text-[11px] font-black text-white">${sgpa > 0 ? sgpa : '-'}</span>
              </div>
              <div class="bg-amber-950/20 border border-amber-900/40 px-2 py-1 rounded flex items-center gap-1.5 shadow-inner">
                <span class="text-[10px] text-amber-600 font-bold uppercase tracking-widest">Pts:</span>
                <span class="text-[11px] font-black text-amber-400">${pts > 0 ? pts : '-'}</span>
              </div>
            </div>
          </summary>
          <div class="border-t border-slate-800/50 bg-slate-950/40 overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/80 border-b border-slate-800 text-[10px] uppercase tracking-wider font-black text-slate-500">
                  <th class="p-2 w-3/4">Subject</th>
                  <th class="p-2 w-1/4 text-center border-l border-slate-800/50">Attend.</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-800/30">${rows}</tbody>
            </table>
          </div>
        </details>`;
    });

    const displayCgpa = totalCgpa > 0 ? totalCgpa : '-';
    const displayPts = activityPoints > 0 ? activityPoints : '-';

    let html = `
      <div class="grid grid-cols-2 gap-4 mb-5">
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-xl p-4 flex flex-col items-center justify-center shadow-inner">
          <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Total CGPA</span>
          <span class="text-2xl font-black text-white">${displayCgpa}</span>
        </div>
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-xl p-4 flex flex-col items-center justify-center shadow-inner">
          <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Activity Points</span>
          <span class="text-2xl font-black text-amber-400">${displayPts}</span>
        </div>
      </div>
      <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3 mb-6 h-40 flex justify-center items-center">
        <canvas id="diaryCgpaChart"></canvas>
      </div>
    `;

    container.innerHTML = html + htmlRows;

    // Render Chart for Mentoring Diary
    setTimeout(() => {
      const cvs = document.getElementById('diaryCgpaChart');
      if (cvs) {
        if (diaryChartInstance) diaryChartInstance.destroy();
        const ctx = cvs.getContext('2d');

        diaryChartInstance = new Chart(ctx, {
          type: 'line',
          data: {
            labels: chartLabels,
            datasets: [{
              label: 'SGPA',
              data: chartData,
              borderColor: '#f59e0b',
              backgroundColor: 'rgba(245, 158, 11, 0.1)',
              borderWidth: 2,
              pointBackgroundColor: '#fff',
              fill: true,
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: { beginAtZero: true, max: 10, ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(30, 41, 59, 0.5)' } },
              x: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { display: false } }
            }
          }
        });
      }
    }, 150);
  }

function loadStudentMentoringDiary() {
  fetch('/api/student/mentoring/diary', {
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(res => res.json())
  .then(data => {
    console.log('[Diary API] status:', data.status, 'has data:', !!data.data, 'academics keys:', data.data ? Object.keys(data.data.academics || {}) : 'none');
    if (data.status === 'SUCCESS') {
      populateMentoringUI(data.data || {});
    } else {
      console.error('[Diary API] Error:', data.message);
      const c = document.getElementById('smdAcademicReport');
      if (c) c.innerHTML = '<p class="text-rose-400 text-xs text-center py-8">Could not load diary data. Please refresh.</p>';
    }
  })
  .catch(err => console.error('[Diary API] Fetch error:', err));
}

function populateMentoringUI(data) {
  if (!data) { console.error('[Diary] populateMentoringUI called with null data'); return; }
  console.log('[Diary] populateMentoringUI academics keys:', Object.keys(data.academics || {}));

  // Populate Personal Info
  if (data.profile) {
    document.getElementById('smd_annual_income').value = data.profile.annual_income || '';
    document.getElementById('smd_residential_status').value = data.profile.residential_status || 'Day Scholar';
    document.getElementById('smd_scholarships').value = data.profile.scholarships || '';
    document.getElementById('smd_fee_waiver').checked = data.profile.is_fee_waiver == 1;
    document.getElementById('smd_guardian_name').value = data.profile.guardian_name || '';
    document.getElementById('smd_guardian_relationship').value = data.profile.guardian_relationship || '';
    document.getElementById('smd_guardian_mobile').value = data.profile.guardian_mobile || '';
    document.getElementById('smd_guardian_address').value = data.profile.guardian_address || '';
  }

  // Store syllabus list globally for dropdowns
  window.smdSyllabusList = data.syllabus_list || {};
  window.smdAcademicsList = data.academics || {};

  // Populate Internal Academic Progress tab in mentoring diary
  renderDiaryAcademicProgress(data);

    // Reset dropdown to default and render empty list or Sem 1
    const semSelect = document.getElementById('smdBoardSemSelect');
    if (semSelect) {
      semSelect.value = "1";
      renderStudentBoardExams();
    }

    // Populate Board Exams (Summary Table)
    const bList = document.getElementById('smdBoardList');
    if (bList) {
      bList.innerHTML = '';
      if (data.board) {
        data.board.forEach(b => {
          addBoardRow(b.semester, b.sgpa, b.cgpa, b.activity_points, b.id);
        });
      }
    }

  // Populate Family
  const fList = document.getElementById('smdFamilyList');
  fList.innerHTML = '';
  if (data.family) {
    data.family.forEach(f => {
      addFamilyRow(f.name, f.relationship, f.education, f.occupation, f.contact_no, f.id);
    });
  }

  // Populate Education
  const eList = document.getElementById('smdEducationList');
  eList.innerHTML = '';
  if (data.education) {
    data.education.forEach(e => {
      addEducationRow(e.course, e.institution, e.year_of_completion, e.total_percentage, e.id);
    });
  }

  // Populate Extracurricular
  const exList = document.getElementById('smdExtraList');
  exList.innerHTML = '';
  let totalPts = 0;
  let splitPts = {};
  if (data.extracurricular && data.extracurricular.length > 0) {
    data.extracurricular.forEach(ex => {
      if (ex.status === 'Verified') {
        let pts = parseFloat(ex.points_awarded) || 0;
        totalPts += pts;
        let seg = ex.segment || 'General';
        splitPts[seg] = (splitPts[seg] || 0) + pts;
      }
      addExtraRow(ex);
    });
  } else {
    exList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-600">No extra-curricular records.</td></tr>';
  }
  
  // Update progress bar
  document.getElementById('studentTotalActivityPoints').innerText = totalPts;
  document.getElementById('studentActivityProgressBar').style.width = Math.min(100, totalPts) + '%';
  let splitHtml = '';
  if (Object.keys(splitPts).length > 0) {
    for (const [seg, pts] of Object.entries(splitPts)) {
      splitHtml += `
        <div class="flex justify-between items-center py-1">
          <span class="text-xs text-slate-300">${seg}</span>
          <span class="text-xs font-bold text-emerald-400">${pts}</span>
        </div>`;
    }
  } else {
    splitHtml = '<div class="text-xs text-slate-500 py-1">No verified points yet.</div>';
  }
  document.getElementById('studentActivitySplitList').innerHTML = splitHtml;

  // Populate Meetings
  const mList = document.getElementById('smdMeetingsList');
  mList.innerHTML = '';
  if (data.meetings && data.meetings.length > 0) {
    data.meetings.forEach(m => {
      mList.innerHTML += `
        <div class="bg-slate-900/50 p-4 rounded-xl border border-slate-800">
          <div class="flex justify-between items-start mb-2">
            <span class="text-xs bg-slate-800 text-slate-300 px-2 py-0.5 rounded font-bold">${m.date}</span>
            <span class="text-xs text-blue-400 font-bold uppercase tracking-wider">${m.category}</span>
          </div>
          <p class="text-xs text-slate-300">${m.discussion_notes}</p>
        </div>
      `;
    });
  } else {
    mList.innerHTML = '<p class="text-xs text-slate-500">No meeting logs found.</p>';
  }
}

function renderStudentBoardExams() {
  const semSelect = document.getElementById('smdBoardSemSelect');
  const sbList = document.getElementById('smdSubjectBoardList');
  if (!semSelect || !sbList) return;

  // Save current UI state back to window.smdAcademicsList before switching
  syncBoardExamsToGlobal();

  const sem = semSelect.value;
  sbList.innerHTML = '';

  if (!sem) {
    sbList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-500">Select a semester to view subjects.</td></tr>';
    return;
  }

  const academics = window.smdAcademicsList || {};
  const subjects = academics[sem];

  if (!subjects || subjects.length === 0) {
    sbList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-500">No subjects found for this semester.</td></tr>';
    return;
  }

  subjects.forEach(sub => {
    let br = sub.board_result || {};
    sbList.innerHTML += `
      <tr class="border-b border-slate-800/40 hover:bg-slate-900/50 board-grade-row transition-premium" data-sem="${sem}" data-code="${sub.subject_code}">
        <td class="p-3 text-slate-300 font-bold bg-slate-900/40 font-mono text-[11px] tracking-wider">${sub.subject_code}</td>
        <td class="p-3 text-slate-200 text-[10px] font-bold uppercase truncate max-w-[200px]" title="${sub.subject_name}">${sub.subject_name}</td>
        <td class="p-2"><input type="month" class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-[10px] text-white bg-exam-my focus:border-blue-500 outline-none transition-premium" value="${br.exam_month_year || ''}"></td>
        <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-[10px] text-white bg-grade uppercase font-bold text-center focus:border-blue-500 outline-none transition-premium" value="${br.grade || ''}" placeholder="e.g. A+"></td>
        <td class="p-2">
          <select class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-[10px] text-white bg-pass font-bold focus:border-blue-500 outline-none transition-premium">
            <option value="1" ${br.passed == 1 || br.passed === undefined ? 'selected' : ''} class="text-emerald-400">Yes</option>
            <option value="0" ${br.passed == 0 && br.passed !== undefined ? 'selected' : ''} class="text-red-400">No</option>
          </select>
        </td>
        <td class="p-2"><input type="number" class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-[10px] text-white font-bold bg-chances text-center focus:border-blue-500 outline-none transition-premium" value="${br.chances_taken || 1}" min="1"></td>
      </tr>
    `;
  });
}

function syncBoardExamsToGlobal() {
  const sbList = document.getElementById('smdSubjectBoardList');
  if (!sbList) return;
  const rows = sbList.querySelectorAll('.board-grade-row');
  if (rows.length === 0) return;

  const sem = rows[0].getAttribute('data-sem');
  if (!sem || !window.smdAcademicsList[sem]) return;

  rows.forEach(row => {
    let code = row.getAttribute('data-code');
    let sub = window.smdAcademicsList[sem].find(s => s.subject_code === code);
    if (sub) {
      sub.board_result = {
        exam_month_year: row.querySelector('.bg-exam-my').value,
        grade: row.querySelector('.bg-grade').value,
        passed: row.querySelector('.bg-pass').value,
        chances_taken: row.querySelector('.bg-chances').value || 1
      };
    }
  });
}

function addFamilyRow(name='', rel='', edu='', occ='', con='', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 family-row';
  tr.innerHTML = `
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-name" value="${name}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-rel" value="${rel}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-edu" value="${edu}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-occ" value="${occ}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-con" value="${con}"></td>
    <td class="p-2 text-right"><button onclick="if(confirm('Are you sure you want to remove this row? This action cannot be undone.')) this.closest('tr').remove();" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  document.getElementById('smdFamilyList').appendChild(tr);
}

function addEducationRow(course='', inst='', year='', marks='', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 edu-row';
  tr.innerHTML = `
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-course" value="${course}" placeholder="e.g. SSLC"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-inst" value="${inst}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-year" value="${year}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-marks" value="${marks}"></td>
    <td class="p-2 text-right"><button onclick="if(confirm('Are you sure you want to remove this row? This action cannot be undone.')) this.closest('tr').remove();" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  document.getElementById('smdEducationList').appendChild(tr);
}

function addExtraRow(sem='', act='', ach='', status='Pending', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 extra-row';
  const statusColor = status === 'Approved' ? 'text-green-400' : (status === 'Rejected' ? 'text-red-400' : 'text-amber-400');
  tr.innerHTML = `
    <td class="p-2"><input type="number" class="w-16 bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white ex-sem" value="${sem}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white ex-act" value="${act}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white ex-ach" value="${ach}"></td>
    <td class="p-2 font-bold ${statusColor}">${status}</td>
    <td class="p-2 text-right"><button onclick="if(confirm('Are you sure you want to remove this row? This action cannot be undone.')) this.closest('tr').remove();" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  document.getElementById('smdExtraList').appendChild(tr);
}

function addBoardRow(sem='', sgpa='', cgpa='', act='', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 board-row';
  tr.innerHTML = `
    <td class="p-2"><input type="number" class="w-16 bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-sem" value="${sem}"></td>
    <td class="p-2"><input type="number" step="0.01" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-sgpa" value="${sgpa}"></td>
    <td class="p-2"><input type="number" step="0.01" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-cgpa" value="${cgpa}"></td>
    <td class="p-2"><input type="number" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-act" value="${act}"></td>
    <td class="p-2 text-right"><button onclick="if(confirm('Are you sure you want to remove this row? This action cannot be undone.')) this.closest('tr').remove();" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  const list = document.getElementById('smdBoardList');
  if (list) list.appendChild(tr);
}

function saveStudentMentoringData() {
    // Make sure the currently visible semester is synced to the global list before saving
    syncBoardExamsToGlobal();

    const boardGrades = [];
    if (window.smdAcademicsList) {
      for (const sem in window.smdAcademicsList) {
        if (Array.isArray(window.smdAcademicsList[sem])) {
          window.smdAcademicsList[sem].forEach(sub => {
            if (sub.board_result && sub.board_result.grade) {
              boardGrades.push({
                semester: sem,
                subject_code: sub.subject_code,
                exam_month_year: sub.board_result.exam_month_year,
                grade: sub.board_result.grade,
                passed: sub.board_result.passed,
                chances_taken: sub.board_result.chances_taken || 1
              });
            }
          });
        }
      }
    }

    const payload = {
    profile: {
      annual_income: document.getElementById('smd_annual_income').value,
      residential_status: document.getElementById('smd_residential_status').value,
      scholarships: document.getElementById('smd_scholarships').value,
      is_fee_waiver: document.getElementById('smd_fee_waiver').checked ? 1 : 0,
      guardian_name: document.getElementById('smd_guardian_name').value,
      guardian_relationship: document.getElementById('smd_guardian_relationship').value,
      guardian_mobile: document.getElementById('smd_guardian_mobile').value,
      guardian_address: document.getElementById('smd_guardian_address').value
    },
    family: Array.from(document.querySelectorAll('.family-row')).map(row => ({
      name: row.querySelector('.f-name').value,
      relationship: row.querySelector('.f-rel').value,
      education: row.querySelector('.f-edu').value,
      occupation: row.querySelector('.f-occ').value,
      contact_no: row.querySelector('.f-con').value
    })).filter(f => f.name !== ''),
    education: Array.from(document.querySelectorAll('.edu-row')).map(row => ({
      course: row.querySelector('.e-course').value,
      institution: row.querySelector('.e-inst').value,
      year_of_completion: row.querySelector('.e-year').value,
      total_percentage: row.querySelector('.e-marks').value
    })).filter(e => e.course !== ''),
    // Extra-curricular handled via separate popup modal now
    extracurricular: [],
    board: Array.from(document.querySelectorAll('.board-row')).map(row => ({
      semester: row.querySelector('.b-sem').value,
      sgpa: row.querySelector('.b-sgpa').value,
      cgpa: row.querySelector('.b-cgpa').value,
      activity_points: row.querySelector('.b-act').value
    })).filter(b => b.semester !== ''),
    board_grades: boardGrades
  };

  fetch('/api/student/mentoring/save-all', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'SUCCESS') {
      showGlobalAlert('Mentoring diary updated successfully!', 'success');
    } else {
      showGlobalAlert('Failed to save data.', 'error');
    }
  })
  .catch(err => {
    console.error(err);
    showGlobalAlert('Error communicating with server.', 'error');
  });
}

function showGlobalAlert(msg, type) {
  // A simple alert function for the student dashboard
  alert(msg);
}

function downloadMentoringPdf() {
  const regNo = window.TARGET_REG_NO;
  window.open(`/diary/${regNo}/print`, '_blank');
}

function openStudentActivityModal() {
    document.getElementById("studentActivityId").value = "";
    document.getElementById("studentActivityForm").reset();
    document.getElementById("studentActivityModalTitle").innerText = "Add Activity";
    document.getElementById("addStudentActivityModal").classList.remove("hidden");
    document.getElementById("addStudentActivityModal").classList.add("flex");
}

function editStudentActivity(act) {
    document.getElementById("studentActivityId").value = act.activity_id || "";
    document.getElementById("studentActivitySemester").value = act.semester || "1";
    document.getElementById("studentActivitySegment").value = act.segment || "NCC";
    document.getElementById("studentActivityName").value = act.activity_name || "";
    document.getElementById("studentActivityLevel").value = act.level || "";
    document.getElementById("studentActivityPtsClaimed").value = act.points_claimed || 0;
    document.getElementById("studentActivityModalTitle").innerText = "Edit Activity";
    document.getElementById("addStudentActivityModal").classList.remove("hidden");
    document.getElementById("addStudentActivityModal").classList.add("flex");
}

function closeStudentActivityModal() {
    document.getElementById("addStudentActivityModal").classList.add("hidden");
    document.getElementById("addStudentActivityModal").classList.remove("flex");
}

function saveStudentActivity(e) {
    e.preventDefault();
    const payload = {
        activity_id: document.getElementById("studentActivityId").value,
        semester: document.getElementById("studentActivitySemester").value,
        segment: document.getElementById("studentActivitySegment").value,
        activity_name: document.getElementById("studentActivityName").value,
        level: document.getElementById("studentActivityLevel").value,
        points_claimed: document.getElementById("studentActivityPtsClaimed").value,
    };

    fetch("/api/student/mentoring/extra-curricular/save", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(resData => {
        if(resData.status === "SUCCESS") {
            closeStudentActivityModal();
            loadStudentMentoringDiary();
            showGlobalAlert("Activity saved successfully!", "success");
        } else {
            showGlobalAlert(resData.message || "Failed to save activity.", "error");
        }
    })
    .catch(err => {
        console.error(err);
        showGlobalAlert("Error saving activity.", "error");
    });
}


document.addEventListener('DOMContentLoaded', function() {
    if (typeof loadStudentMentoringDiary === 'function') {
        loadStudentMentoringDiary();
    }
});
