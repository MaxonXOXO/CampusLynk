  <script>
    let academicReportLoaded = false;
    let mentoringLoaded = false;
    let attendanceLoaded = false;
    let mockSubjectsLoaded = false;
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

    function switchPanel(panelId) {
      window.history.replaceState({}, '', '?tab=' + panelId);

      const panels = ['exams', 'marks', 'profile', 'mentoring', 'activity', 'seminar', 'attendance', 'mock_test'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        if (el) {
          if (id === panelId) {
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        }
      });

      const titles = { 
        exams: 'Works To Do', 
        marks: 'Academic Stats & CIE Marks', 
        profile: 'My Student Profile', 
        mentoring: 'Mentoring Diary',
        activity: 'Activity Points Portfolio', 
        seminar: 'My Seminar Details',
        attendance: 'Attendance Review',
        mock_test: 'Practice Test Engine'
      };
      const subtitles = { 
        exams: 'Manage your pending assignments, series examinations and learning schedule.', 
        marks: 'Your semester-wise continuous internal evaluation records.', 
        profile: 'Your verified institutional identity and account settings.',
        mentoring: 'Comprehensive student profile and periodic mentor-mentee interaction records.',
        activity: 'Track and claim your extracurricular points towards graduation.',
        seminar: 'Register presentation topic and view guide evaluations.',
        attendance: 'Real-time daily periods, subject-wise attendance trajectory, and leave records.',
        mock_test: 'Timed syllabus practice assessments and instant competency scoring.'
      };

      if (titles[panelId]) document.getElementById('panelTitle').innerText = titles[panelId];
      if (subtitles[panelId]) document.getElementById('panelSubtitle').innerText = subtitles[panelId];

      if (panelId === 'activity') {
        loadActivityPoints();
      } else if (panelId === 'seminar') {
        loadSeminarRegistration();
      } else if (panelId === 'mentoring') {
        if (!mentoringLoaded) loadStudentMentoringData();
      } else if (panelId === 'attendance') {
        if (!attendanceLoaded) loadStudentAttendanceData();
      } else if (panelId === 'mock_test') {
        if (!mockSubjectsLoaded) loadMockSubjects();
      }

      if (window.initLucide) window.initLucide();
    }

    document.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      const tab = urlParams.get('tab');
      if (tab && ['exams', 'marks', 'profile', 'mentoring', 'activity', 'seminar', 'attendance', 'mock_test'].includes(tab)) {
        switchPanel(tab);
        if (typeof selectSidebarNav === 'function') selectSidebarNav(tab);
      }
      loadStudentTests();
      if (!academicReportLoaded) loadAcademicReport();
      loadPreClassVlmBanner();
    });

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
       document.getElementById('statOverallDone').innerText = currentTaskStats.online_tests_submitted + currentTaskStats.assignments_submitted + currentTaskStats.written_tests_submitted;
    }

    function loadAcademicReport() {
      fetch('/api/student/academic-report')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            academicReportLoaded = true;
            academicData = data;
            const overall = data.overall || {};
            const cgpaVal = parseFloat(overall.cgpa) || 0;
            const activityPointsVal = parseInt(overall.activity_points) || 0;

            document.getElementById('overallCgpa').innerText = cgpaVal > 0 ? cgpaVal.toFixed(2) : '0.00';
            document.getElementById('diplomaClassification').innerText = overall.classification || '--';

            // CGPA gauge
            const cgpaPercent = Math.min(1.0, Math.max(0.0, cgpaVal / 10.0));
            document.getElementById('cgpaGaugeProgress').style.strokeDashoffset = 251.2 - (cgpaPercent * 251.2);

            // Activity Points gauge
            const activityPercent = Math.min(1.0, Math.max(0.0, activityPointsVal / 160.0));
            document.getElementById('activityGaugeProgress').style.strokeDashoffset = 251.2 - (activityPercent * 251.2);
            document.getElementById('overallActivityPoints').innerText = activityPointsVal;

            // Attendance gauge
            const attendance = data.current_sem_attendance || { total_hours: 0, present_hours: 0, percentage: 0 };
            const attendancePct = parseFloat(attendance.percentage) || 0;
            document.getElementById('overallAttendancePct').innerText = attendancePct + '%';
            document.getElementById('attendanceHoursDetail').innerText = `${attendance.present_hours} / ${attendance.total_hours}`;
            document.getElementById('attendanceGaugeProgress').style.strokeDashoffset = 251.2 - ((attendancePct / 100.0) * 251.2);

            document.getElementById('headerSemValue').innerText = 'Semester ' + (overall.current_semester || '1');
            currentActiveSem = overall.current_semester || 1;

            if (data.stats) updateStatsHeader(data.stats, null);
            renderActiveTasks(data.active_tasks || [], data.active_surveys || []);
            renderCgpaChart(data.semesters || []);
            renderSemesterTabs(data.semesters || []);
            renderGodTable(currentActiveSem);
            renderSubjectProgress(data.subject_progress || []);
          }
        })
        .catch(err => console.error('Academic report fetch error:', err));
    }

    function renderSubjectProgress(progressList) {
      const container = document.getElementById('subjectProgressGrid');
      if (!container) return;

      if (!progressList || progressList.length === 0) {
        container.innerHTML = `<div class="col-span-full py-6 text-center text-slate-400 font-medium text-xs">No active semester subject progress logs recorded yet.</div>`;
        return;
      }

      container.innerHTML = progressList.map(item => `
        <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 space-y-2">
          <div class="flex items-start justify-between gap-2">
            <div>
              <p class="font-bold text-slate-900 text-xs">${item.subject_code} - ${item.subject_name}</p>
              <p class="text-[11px] text-slate-500">${item.staff_name || 'Faculty Assigned'}</p>
            </div>
            <span class="text-xs font-bold text-blue-700">${item.percentage}%</span>
          </div>
          <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
            <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: ${item.percentage}%"></div>
          </div>
          <div class="flex justify-between text-[10px] text-slate-500 font-medium">
            <span>Sessions: ${item.completed_sessions} taught</span>
            <span>Target: ${item.total_sessions} hrs</span>
          </div>
        </div>
      `).join('');
    }

    function renderActiveTasks(tasks, surveys) {
      const tasksContainer = document.getElementById('studentActiveTasksContainer');
      const assignmentsSection = document.getElementById('assignmentsSection');

      if (tasks && tasks.length > 0) {
        tasksContainer.innerHTML = tasks.map((t, idx) => `
          <div class="p-4 bg-white border border-slate-200 rounded-xl space-y-2 shadow-sm">
            <div class="flex items-center justify-between">
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">${t.type} • ${t.co_tag || 'Module'}</span>
              <span class="text-xs text-slate-500 font-medium font-mono">${t.deadline ? 'Due: ' + new Date(t.deadline).toLocaleDateString() : ''}</span>
            </div>
            <p class="font-bold text-slate-900 text-xs">${t.subject_code} - ${t.subject}</p>
            <p class="text-xs text-slate-600">${t.title || 'Continuous Internal Evaluation Assignment'}</p>
          </div>
        `).join('');
        assignmentsSection.classList.remove('hidden');
      } else {
        tasksContainer.innerHTML = `<div class="py-6 text-center text-slate-400 text-xs font-medium">No pending written assignments.</div>`;
      }
    }

    function renderCgpaChart(semesters) {
      const ctx = document.getElementById('cgpaChart');
      if (!ctx) return;
      if (cgpaChartInstance) cgpaChartInstance.destroy();

      const labels = semesters.map(s => 'S' + s.sem_no);
      const data = semesters.map(s => parseFloat(s.sgpa) || 0);

      cgpaChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels.length ? labels : ['S1'],
          datasets: [{
            data: data.length ? data : [0],
            borderColor: '#2563EB',
            backgroundColor: 'rgba(37, 99, 235, 0.08)',
            borderWidth: 2,
            pointBackgroundColor: '#2563EB',
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            y: { min: 0, max: 10, ticks: { font: { family: 'Poppins', size: 10 } } },
            x: { ticks: { font: { family: 'Poppins', size: 10 } } }
          }
        }
      });
    }

    function renderSemesterTabs(semesters) {
      const container = document.getElementById('semesterTabsContainer');
      if (!container) return;

      container.innerHTML = (semesters || [{ sem_no: 1 }]).map(s => `
        <button type="button" onclick="renderGodTable(${s.sem_no})" class="px-3 py-1 text-xs font-semibold rounded-lg transition-all ${s.sem_no === currentActiveSem ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'}">
          Semester ${s.sem_no}
        </button>
      `).join('');
    }

    function renderGodTable(semNo) {
      currentActiveSem = semNo;
      const container = document.getElementById('academicReportContent');
      if (!container || !academicData) return;

      const semData = (academicData.semesters || []).find(s => s.sem_no === semNo);
      const subjects = semData ? semData.subjects || [] : [];

      if (subjects.length === 0) {
        container.innerHTML = `<div class="p-8 text-center text-slate-400 bg-white border border-slate-200 rounded-2xl text-xs font-medium">No marksheet entries registered for Semester ${semNo}.</div>`;
        return;
      }

      container.innerHTML = `
        <div class="w-full bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 text-xs font-semibold uppercase tracking-wider">
                  <th class="py-3.5 px-4">Subject Code & Name</th>
                  <th class="py-3.5 px-4 text-center">Series 1</th>
                  <th class="py-3.5 px-4 text-center">Series 2</th>
                  <th class="py-3.5 px-4 text-center">Assignment</th>
                  <th class="py-3.5 px-4 text-center">CIE Total</th>
                  <th class="py-3.5 px-4 text-center">Grade</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                ${subjects.map(sub => `
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3.5 px-4">
                      <p class="font-semibold text-slate-900 text-xs">${sub.code} - ${sub.name}</p>
                      <p class="text-[11px] text-slate-500 font-medium">${sub.type || 'Theory'}</p>
                    </td>
                    <td class="py-3.5 px-4 text-center font-mono text-xs font-semibold">${sub.series_1 ?? '-'}</td>
                    <td class="py-3.5 px-4 text-center font-mono text-xs font-semibold">${sub.series_2 ?? '-'}</td>
                    <td class="py-3.5 px-4 text-center font-mono text-xs font-semibold">${sub.assignment ?? '-'}</td>
                    <td class="py-3.5 px-4 text-center font-bold text-blue-700 text-xs">${sub.cie_total ?? '-'}</td>
                    <td class="py-3.5 px-4 text-center">
                      <span class="px-2.5 py-0.5 rounded-full text-xs font-bold ${sub.grade === 'S' || sub.grade === 'A' ? 'bg-emerald-50 text-emerald-800' : (sub.grade === 'F' ? 'bg-rose-50 text-rose-800' : 'bg-blue-50 text-blue-800')}">${sub.grade || '--'}</span>
                    </td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        </div>
      `;
    }

    function loadStudentTests() {
      fetch('/student/tests/active')
        .then(res => res.json())
        .then(data => {
          const list = document.getElementById('studentActiveTestsList');
          const section = document.getElementById('mcqTestsSection');
          if (data && data.tests && data.tests.length > 0) {
            list.innerHTML = data.tests.map(t => `
              <div class="p-4 bg-white border border-slate-200 rounded-xl space-y-2 shadow-sm">
                <div class="flex items-center justify-between">
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/60">Live Assessment</span>
                  <span class="text-xs text-slate-500 font-medium">${t.duration} mins</span>
                </div>
                <p class="font-bold text-slate-900 text-xs">${t.title}</p>
                <a href="/student/test/${t.id}" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700">Start Practice <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
              </div>
            `).join('');
            section.classList.remove('hidden');
          } else {
            list.innerHTML = `<div class="py-6 text-center text-slate-400 text-xs font-medium">No live MCQ tests currently scheduled.</div>`;
          }
          if (window.initLucide) window.initLucide();
        })
        .catch(() => {});
    }

    function loadActivityPoints() {
      fetch('/student/activity-points')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('verifiedActivityTotal').innerText = data.total || 0;
            const tb = document.getElementById('activityClaimsTableBody');
            if (tb && data.claims) {
              tb.innerHTML = data.claims.map(c => `
                <tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3 px-4 text-xs font-medium text-slate-600">${c.date || '-'}</td>
                  <td class="py-3 px-4 text-xs font-semibold text-slate-900">${c.segment}</td>
                  <td class="py-3 px-4 text-xs text-slate-700">${c.name}</td>
                  <td class="py-3 px-4 text-xs text-slate-600">${c.level}</td>
                  <td class="py-3 px-4 text-xs text-slate-500">${c.evidence || '-'}</td>
                  <td class="py-3 px-4 text-center text-xs font-semibold">${c.claimed}</td>
                  <td class="py-3 px-4 text-center text-xs font-bold text-blue-700">${c.awarded || 0}</td>
                  <td class="py-3 px-4 text-right">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold ${c.status === 'Approved' ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800'}">${c.status}</span>
                  </td>
                </tr>
              `).join('');
            }
          }
        })
        .catch(() => {});
    }

    function loadSeminarRegistration() {
      fetch('/student/seminar/details')
        .then(res => res.json())
        .then(data => {
          if (data && data.status === 'SUCCESS' && data.registered) {
            document.getElementById('seminarStatusBanner').classList.remove('hidden');
            document.getElementById('semStatusTopic').innerText = data.topic || '-';
            document.getElementById('semStatusGuide').innerText = data.guide_name || '-';
            document.getElementById('semStatusDate').innerText = data.date || '-';
            document.getElementById('semStatusScore').innerText = (data.score || '-') + ' / 75';
            document.getElementById('seminarFormCard').classList.add('hidden');
          }
        })
        .catch(() => {});
    }

    // ==========================================
    // MENTORING DIARY INTERACTIVE CONTROLLER
    // ==========================================
    function switchStudentMentoringTab(tabId) {
      document.querySelectorAll('.smd-content-pane').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.smd-tab').forEach(el => {
        el.className = "smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center";
      });

      const targetPane = document.getElementById(tabId);
      const targetBtn = document.getElementById('tabBtn_' + tabId);
      if (targetPane) targetPane.classList.remove('hidden');
      if (targetBtn) targetBtn.className = "smd-tab py-2 px-2.5 text-xs font-semibold rounded-xl bg-blue-600 text-white shadow-sm transition-all text-center";
      if (window.initLucide) window.initLucide();
    }

    function loadStudentMentoringData() {
      fetch('/api/student/mentoring/data')
        .then(res => res.json())
        .then(data => {
          if (data && data.status === 'SUCCESS') {
            mentoringLoaded = true;
            
            // Profile & Socio-economic
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

            // Family members
            const fList = document.getElementById('smdFamilyList');
            if (fList && data.family) {
              if (data.family.length === 0) {
                fList.innerHTML = `<tr><td colspan="6" class="p-6 text-center text-slate-400">No family members registered. Click "+ Add Member" to register.</td></tr>`;
              } else {
                fList.innerHTML = data.family.map(f => `
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3 px-4 font-semibold text-slate-900 text-xs">${f.name}</td>
                    <td class="py-3 px-4 text-slate-700 text-xs">${f.relationship}</td>
                    <td class="py-3 px-4 text-slate-600 text-xs">${f.education || '-'}</td>
                    <td class="py-3 px-4 text-slate-600 text-xs">${f.occupation || '-'}</td>
                    <td class="py-3 px-4 font-mono text-slate-600 text-xs">${f.contact_no || '-'}</td>
                    <td class="py-3 px-4 text-center">
                      <button type="button" onclick="deleteFamilyRow(this, ${f.id})" class="text-rose-600 hover:text-rose-700 text-xs font-semibold">Delete</button>
                    </td>
                  </tr>
                `).join('');
              }
            }

            // Prior Qualifications
            const eList = document.getElementById('smdEducationList');
            if (eList && data.education) {
              if (data.education.length === 0) {
                eList.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-slate-400">No prior education records found. Click "+ Add Qualification" to register.</td></tr>`;
              } else {
                eList.innerHTML = data.education.map(e => `
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3 px-4 font-semibold text-slate-900 text-xs">${e.course}</td>
                    <td class="py-3 px-4 text-slate-700 text-xs">${e.institution}</td>
                    <td class="py-3 px-4 text-slate-600 text-xs">${e.year_of_completion}</td>
                    <td class="py-3 px-4 text-slate-900 font-semibold text-xs">${e.total_percentage}%</td>
                    <td class="py-3 px-4 text-center">
                      <button type="button" onclick="deleteEducationRow(this, ${e.id})" class="text-rose-600 hover:text-rose-700 text-xs font-semibold">Delete</button>
                    </td>
                  </tr>
                `).join('');
              }
            }

            // Extracurricular
            const exList = document.getElementById('smdExtraList');
            if (exList && data.extracurricular) {
              if (data.extracurricular.length === 0) {
                exList.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-slate-400">No extracurricular logs.</td></tr>`;
              } else {
                exList.innerHTML = data.extracurricular.map(ex => `
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3 px-4 font-semibold text-slate-900 text-xs">${ex.activity_name || ex.name}</td>
                    <td class="py-3 px-4 text-slate-600 text-xs">${ex.level}</td>
