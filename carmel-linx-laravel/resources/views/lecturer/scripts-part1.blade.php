  <script>
    // Self-executing theme preference loader to run immediately and prevent flashing dark theme
    (function() {
      const savedTheme = localStorage.getItem('theme-preference');
      if (savedTheme === 'light') {
        document.body.classList.add('light-theme');
        window.addEventListener('DOMContentLoaded', () => {
          const icon = document.getElementById('themeToggleIcon');
          const text = document.getElementById('themeToggleText');
          if (icon) icon.innerText = 'dark_mode';
          if (text) text.innerText = 'Dark Mode';
        });
      }
    })();

    function toggleTheme() {
      const body = document.body;
      const isLight = body.classList.toggle('light-theme');
      localStorage.setItem('theme-preference', isLight ? 'light' : 'dark');
      
      const icon = document.getElementById('themeToggleIcon');
      const text = document.getElementById('themeToggleText');
      if (isLight) {
        if (icon) icon.innerText = 'dark_mode';
        if (text) text.innerText = 'Dark Mode';
      } else {
        if (icon) icon.innerText = 'light_mode';
        if (text) text.innerText = 'Light Mode';
      }
    }

    let activePanel = "{{ $isSecurityPanel ? 'security' : 'dashboard' }}";

    document.addEventListener("DOMContentLoaded", () => {
      if (sessionStorage.getItem('openClassroomFromHOD') === 'true') {
        sessionStorage.removeItem('openClassroomFromHOD');
        // Instantly force load active batches list
        switchPanel('dashboard');
      }
      const urlParams = new URLSearchParams(window.location.search);
      const subjectId = urlParams.get('subject_id');
      const subjectName = urlParams.get('subject_name');
      const classroomId = urlParams.get('classroom_id');

      if (subjectId) {
        openClassroom(classroomId, subjectId, subjectName);
      } else if (activePanel === 'dashboard') {
        loadLecturerBatches();
      }
      if (activePanel === 'security') loadSecurityLogs();
      checkTodaySeminars();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'security', 'classroom', 'mobileSeminar'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const navMap = {
        'dashboard': 'my_batches',
        'security': 'profile',
        'classroom': 'my_batches',
        'mobileSeminar': 'my_batches'
      };
      if (typeof window.selectSidebarNav === 'function') {
        window.selectSidebarNav(navMap[panelId] || panelId);
      }

      const titles = {
        'dashboard': 'My Batches',
        'security': 'My Profile',
        'classroom': 'Virtual Classroom',
        'mobileSeminar': 'Seminar Evaluation'
      };
      
      const titleEl = document.getElementById('panelTitle') || document.querySelector('.topbar-title') || document.querySelector('header h1');
      if (titleEl) titleEl.innerText = titles[panelId] || 'My Batches';

      if (panelId === 'security') loadSecurityLogs();
      if (panelId === 'dashboard') loadLecturerBatches();
    }

    let currentDashboardFilter = 'active';

    function setDashboardBatchFilter(status) {
      currentDashboardFilter = status;
      
      const activeBtn = document.getElementById('btnFilterActive');
      const historicalBtn = document.getElementById('btnFilterHistorical');

      if (status === 'active') {
        if (activeBtn) activeBtn.className = 'flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-semibold bg-white text-blue-600 shadow-sm transition-all cursor-pointer';
        if (historicalBtn) historicalBtn.className = 'flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 transition-all cursor-pointer';
      } else {
        if (activeBtn) activeBtn.className = 'flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 transition-all cursor-pointer';
        if (historicalBtn) historicalBtn.className = 'flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-semibold bg-white text-blue-600 shadow-sm transition-all cursor-pointer';
      }

      loadLecturerBatches();
    }

    function loadLecturerBatches() {
      const grid = document.getElementById('lecturerBatchGrid');
      grid.innerHTML = `
        <div class="col-span-full py-16 text-center text-slate-400 font-semibold text-sm">
          <div class="w-8 h-8 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
          <span>Loading assigned batches &amp; classrooms...</span>
        </div>
      `;

      fetch(`/api/lecturer/my-batches?status=${currentDashboardFilter}`, {
        headers: { 'Content-Type': 'application/json' }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          renderBatchCards(data.batches);
        } else {
          grid.innerHTML = `<div class="col-span-full p-6 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">${data.message || 'Failed to load batches.'}</div>`;
        }
      })
      .catch(() => {
        grid.innerHTML = `<div class="col-span-full p-6 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">Error communicating with server while loading batches.</div>`;
      });
    }

    function renderBatchCards(batches) {
      const grid = document.getElementById('lecturerBatchGrid');
      grid.innerHTML = '';

      if (batches.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full bg-white border border-slate-200 p-12 rounded-3xl text-center shadow-sm max-w-xl mx-auto space-y-3">
            <div class="w-14 h-14 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto">
              <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </div>
            <h4 class="font-bold text-slate-900 text-base">No batches found</h4>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">You do not have any assigned classes or subjects under the <strong>${currentDashboardFilter === 'active' ? 'Active' : 'Archived'}</strong> filter.</p>
          </div>
        `;
        return;
      }

      batches.forEach(b => {
        let rolesHtml = '';
        b.roles.forEach(r => {
          let badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
          if (r === 'Tutor') badgeClass = 'bg-sky-50 text-sky-700 border-sky-200';
          if (r === 'Mentor') badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
          if (r === 'Subject Staff') badgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200';
          if (r === 'Executive Supervision') badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
          rolesHtml += `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold border ${badgeClass}">${r}</span>`;
        });

        const isGraduated = (b.current_semester || 1) > 6;
        const semBadge = isGraduated
          ? `<span class="px-2.5 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full font-bold text-xs flex items-center gap-1"><svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>Graduated</span>`
          : `<span class="px-2.5 py-0.5 bg-blue-50 border border-blue-200 text-blue-700 rounded-full font-bold text-xs font-mono">S-${b.current_semester || 1}</span>`;

        let subjectsHtml = '';
        if (b.subjects && b.subjects.length > 0) {
          subjectsHtml = b.subjects.map(s => {
            let topicsPct = s.total_topics > 0 ? Math.round((s.covered_topics / s.total_topics) * 100) : 0;
            let hoursPct  = s.total_hours  > 0 ? Math.round((s.engaged_hours  / s.total_hours)  * 100) : 0;
            let barPct    = topicsPct || hoursPct;
            let barColor  = barPct >= 80 ? 'from-emerald-500 to-teal-500' : barPct >= 50 ? 'from-blue-600 to-indigo-600' : 'from-violet-600 to-purple-600';

            const safeName = escapeQuotes(s.name);
            const safeCode = escapeQuotes(s.code);
            const revision = s.syllabus_revision_code || 'REV2021';

            return `
              <div onclick="openClassroom('${b.classroom_id}', '${s.id}', '${safeName}', '${safeCode}', '${revision}', '${s.type}')" class="w-full p-4 bg-slate-50 hover:bg-blue-50/50 border border-slate-200 hover:border-blue-300 rounded-2xl transition-all cursor-pointer group flex flex-col gap-2.5 shadow-2xs">
                <div class="flex justify-between items-start gap-2">
                  <div class="flex-1 min-w-0">
                    <h5 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug break-words">${s.name}</h5>
                    <div class="text-xs text-slate-500 font-mono mt-0.5 flex items-center gap-1.5 flex-wrap">
                      <span>Sem ${s.semester}</span>
                      <span>•</span>
                      <span>${s.type}</span>
                      <span>•</span>
                      <span class="font-semibold text-slate-700">${s.code}</span>
                    </div>
                  </div>
                  <div class="w-7 h-7 rounded-lg bg-white group-hover:bg-blue-600 group-hover:text-white text-slate-400 border border-slate-200 group-hover:border-blue-600 flex items-center justify-center transition-all shrink-0 shadow-2xs">
                    <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                  </div>
                </div>
                
                <!-- Progress bar -->
                <div class="flex items-center gap-2.5 pt-1 border-t border-slate-100">
                  <div class="flex-1 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-gradient-to-r ${barColor} h-1.5 rounded-full transition-all duration-500" style="width: ${barPct}%"></div>
                  </div>
                  <span class="text-xs font-bold text-slate-600 font-mono shrink-0">${s.engaged_hours}/${s.total_hours} hrs</span>
                </div>
              </div>
            `;
          }).join('');
        } else {
          subjectsHtml = `<div class="text-xs text-slate-400 italic py-4 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">No subjects assigned in this batch.</div>`;
        }

        const card = document.createElement('div');
        card.className = "bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col h-[460px]";
        card.innerHTML = `
          <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col gap-3 shrink-0">
            <div class="flex justify-between items-start gap-2">
              <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                  <h4 class="font-bold text-slate-900 text-base">Admission ${b.batch_year}</h4>
                  ${semBadge}
                </div>
                <span class="inline-block px-2.5 py-0.5 bg-white border border-slate-200 rounded-lg font-mono text-xs font-bold text-slate-700">${b.classroom_id}</span>
              </div>
              <div class="flex flex-col items-end gap-1.5">
                <div class="flex flex-wrap gap-1 justify-end">${rolesHtml}</div>
                <span class="flex items-center gap-1 text-xs font-semibold text-slate-500">
                  <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                  <span>${b.student_count || 0} students</span>
                </span>
              </div>
            </div>
          </div>

          <div class="p-5 flex-1 flex flex-col min-h-0 bg-white space-y-3">
            <div class="flex items-center justify-between pb-1 shrink-0">
              <h5 class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                <span>Assigned Subjects</span>
              </h5>
              <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">${(b.subjects || []).length} Total</span>
            </div>
            <div class="space-y-2.5 overflow-y-auto flex-1 pr-1.5 scrollbar-hidden">
              ${subjectsHtml}
            </div>
          </div>
        `;
        grid.appendChild(card);
      });

      if (window.initLucide) window.initLucide();
    }

    function escapeQuotes(str) {
      if (!str) return '';
      return String(str).replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    let currentSubjectId = null;
    window.currentVirtualBatchId = '';
    window.currentVirtualSemester = '';

    function openClassroom(batchId, subjectId, subjectName, subjectCode, revision = 'REV2021', type = 'Theory') {
      if (revision === 'REV2026') {
        const sNameLower = (subjectName || '').toLowerCase();
        const sTypeLower = (type || '').toLowerCase();
        if (sNameLower.includes('health') || sNameLower.includes('physical') || sTypeLower.includes('health') || sTypeLower.includes('physical')) {
          window.open(`/r26/classroom/health-physical/${subjectId}`, '_blank');
          return;
        } else if (sTypeLower.includes('drawing') || sNameLower.includes('drawing') || sNameLower.includes('graphics') || sNameLower.includes('cad')) {
          window.open(`/r26/classroom/drawing/${subjectId}`, '_blank');
          return;
        } else if (type.includes('Practicum')) {
          window.open(`/r26/classroom/practicum/${subjectId}`, '_blank');
          return;
        } else if (type.includes('Theory')) {
          window.open(`/r26/classroom/theory/${subjectId}`, '_blank');
          return;
        } else if (type.includes('Practical') || type.includes('Lab')) {
          window.open(`/r26/classroom/practical/${subjectId}`, '_blank');
          return;
        }
      }
      currentSubjectId = subjectId;
      window.currentVirtualBatchId = batchId;
      document.getElementById('vcTitle').innerText = subjectName || 'Virtual Classroom';
      let latText = '';
      if (batchId.includes('_LET')) {
        latText = ' <span class="bg-purple-900/60 border border-purple-500/50 text-purple-300 font-extrabold text-xs px-2.5 py-1 rounded-full shadow-inner ml-2">LATERAL ENTRY (LET)</span>';
      }
      document.getElementById('vcSubtitle').innerHTML = `Batch: ${batchId}${latText}`;
      // Show subject name and code immediately near the upload button (before API loads)
      const vcSubName = document.getElementById('vcSubjectName');
      const vcSubCode = document.getElementById('vcSubjectCode');
      const vcSubInfo = document.getElementById('vcSubjectInfo');
      if (vcSubName) vcSubName.innerText = subjectName || '';
      if (vcSubCode) vcSubCode.innerText = subjectCode || '';
      if (vcSubInfo) vcSubInfo.style.display = (subjectName || subjectCode) ? 'flex' : 'none';
      switchPanel('classroom');
      loadCourseDetails(subjectId);
    }

    function handleSyllabusUpload(input) {
      if (!input.files || input.files.length === 0) return;
      if (!currentSubjectId) return;

      const file = input.files[0];
      const formData = new FormData();
      formData.append('syllabus_file', file);
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

      document.getElementById('syllabusUploadBox').classList.add('hidden');
      document.getElementById('syllabusUploadProgress').classList.remove('hidden');
      document.getElementById('parseStatusBadge').innerText = 'Extracting...';
      document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs whitespace-nowrap';

      fetch(`/api/classroom/${currentSubjectId}/syllabus`, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
      })
      .then(res => {
        if (!res.ok) throw new Error('Server error: ' + res.status);
        return res.json();
      })
      .then(data => {
        document.getElementById('syllabusUploadBox').classList.remove('hidden');
        document.getElementById('syllabusUploadProgress').classList.add('hidden');
        // Reset file input so same file can be re-uploaded
        document.getElementById('syllabusFileInput').value = '';
        if (data.status === 'SUCCESS') {
          document.getElementById('parseStatusBadge').innerText = 'Parsed & Synced ✓';
          document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs whitespace-nowrap';
          // Reload course details — it will auto-switch to Course Structure tab
          loadCourseDetails(currentSubjectId);
        } else {
          alert(data.message || 'Upload failed.');
          document.getElementById('parseStatusBadge').innerText = 'Upload Failed';
          document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 border border-rose-200/80 shadow-2xs whitespace-nowrap';
        }
      })
      .catch(err => {
        document.getElementById('syllabusUploadBox').classList.remove('hidden');
        document.getElementById('syllabusUploadProgress').classList.add('hidden');
        document.getElementById('syllabusFileInput').value = '';
        document.getElementById('parseStatusBadge').innerText = 'Upload Error';
        document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 border border-rose-200/80 shadow-2xs whitespace-nowrap';
        alert('Failed to upload syllabus: ' + err.message);
      });
    }

    function toggleClassroomTab(tabName) {
      const tabs = [
        { id: 'structure', btn: 'tabStructure', content: 'courseStructureContent' },
        { id: 'planner', btn: 'tabPlanner', content: 'coursePlannerContent' },
        { id: 'assessment', btn: 'tabAssessment', content: 'formativeAssessmentContent' },
        { id: 'summative', btn: 'tabSummative', content: 'summativeAssessmentContent' },
        { id: 'reports', btn: 'tabReports', content: 'classReportsContent' },
        { id: 'qbank', btn: 'tabQBank', content: 'questionBankContent' },
        { id: 'survey', btn: 'tabSurvey', content: 'midSemesterSurveyContent' },
        { id: 'exit_survey', btn: 'tabExitSurvey', content: 'courseExitSurveyContent' },
        { id: 'seminar_evaluation', btn: 'tabSeminar', content: 'seminarEvaluationContent' },
        { id: 'lab_evaluation', btn: 'tabLab', content: 'labEvaluationContent' },
        { id: 'lab_copo', btn: 'tabLabCoPo', content: 'labCoPoMappingContent' }
      ];

      tabs.forEach(t => {
        const btn = document.getElementById(t.btn);
        const content = document.getElementById(t.content);
        
        if (t.id === tabName) {
          if (btn) {
            btn.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-200/80', 'shadow-2xs');
            btn.classList.remove('border-transparent', 'text-slate-600', 'hover:bg-slate-50');
          }
          if (content) {
            content.classList.remove('hidden');
            if (t.id !== 'structure') content.classList.add('flex');
          }
        } else {
          if (btn) {
            btn.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-200/80', 'shadow-2xs');
            btn.classList.add('border-transparent', 'text-slate-600', 'hover:bg-slate-50');
          }
          if (content) {
            content.classList.add('hidden');
            if (t.id !== 'structure') content.classList.remove('flex');
          }
        }
      });

      if (tabName === 'reports') {
        fetchClassReports();
      } else if (tabName === 'qbank') {
        fetchQuestionBank(currentSubjectId);
      } else if (tabName === 'survey') {
        fetchSurveyResults(currentSubjectId);
      } else if (tabName === 'exit_survey') {
        fetchExitSurveyResults(currentSubjectId);
      } else if (tabName === 'seminar_evaluation') {
        fetchSeminarEvaluations();
      } else if (tabName === 'lab_evaluation') {
        fetchPracticalEvaluations();
      } else if (tabName === 'lab_copo') {
        fetchPracticalCoPoMapping();
      }
    }

    let classReportsData = null;
    let activeReportType = 'attendance_log';
    let currentDeadlines = {};
    let currentQuestions = {};
    let currentSummativeTests = {};
    let currentSubjectName = '';
    let currentSubjectCode = '';
    let currentSubjectSemester = '';
    let currentSubjectAcademicYear = '';
    let currentSubjectClassroomId = '';

    function loadCourseDetails(subjectId) {
      currentSubjectId = subjectId;
      document.getElementById('courseStructureContent').innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-[10px] font-bold text-slate-400">Loading course data...</p>
        </div>
      `;
      document.getElementById('coursePlannerContent').innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-[10px] font-bold text-slate-400">Loading planner...</p>
        </div>
      `;
      document.getElementById('surveyWorkspace').innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Loading survey details...</p>
        </div>
      `;
      document.getElementById('activeSyllabusCard').classList.add('hidden');
      // Note: vcSubjectInfo is set by openClassroom immediately - don't hide it during load
      document.getElementById('parseStatusBadge').innerText = 'Syncing...';
      document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-2.5 py-1 rounded-md bg-blue-900/30 text-blue-400 border border-blue-500/30';

      return fetch(`/api/classroom/${subjectId}/details`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS' && data.data) {
