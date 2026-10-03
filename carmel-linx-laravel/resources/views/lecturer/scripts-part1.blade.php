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
        { id: 'lab_copo', btn: 'tabLabCoPo', content: 'labCoPoMappingContent' },
        { id: 'course_attainment', btn: 'tabCourseAttainment', content: 'courseAttainmentContent' }
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

      if (tabName === 'planner') {
        if (typeof window.autoResizeAllPlanTextareas === 'function') {
          setTimeout(window.autoResizeAllPlanTextareas, 50);
        }
      } else if (tabName === 'reports') {
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
      } else if (tabName === 'course_attainment') {
        loadCourseAttainment();
      }
    }

    /* --- Course Attainment & ESE Marks Evaluation (R-2021 & R-2026) --- */
    function loadCourseAttainment() {
      const workspace = document.getElementById('courseAttainmentWorkspace');
      if (!workspace || !currentSubjectId) return;

      workspace.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
          <div class="w-8 h-8 border-3 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-3"></div>
          <p class="text-sm font-bold text-slate-600">Calculating Course Attainment Metrics...</p>
        </div>
      `;

      const isR26 = window.currentSyllabusRevision === '2026' || (window.currentVirtualRevision && window.currentVirtualRevision.includes('2026'));
      const attainmentUrl = isR26 ? `/api/r26/classroom/${currentSubjectId}/attainment-summary` : `/api/classroom/${currentSubjectId}/attainment-summary`;

      fetch(attainmentUrl)
        .then(res => res.json())
        .then(data => {
          if (data.status !== 'SUCCESS') {
            workspace.innerHTML = `
              <div class="p-6 text-center text-rose-600 font-bold bg-rose-50 border border-rose-200 rounded-2xl">
                Failed to load attainment data.
              </div>
            `;
            return;
          }

          const summary = data.summary || {};
          const matrix = data.matrix || [];

          let rowsHtml = matrix.map(row => {
            const attained = row.attained;
            const badgeBg = attained 
              ? 'bg-emerald-50 text-emerald-700 border-emerald-200' 
              : 'bg-rose-50 text-rose-700 border-rose-200';

            return `
              <tr class="border-b border-slate-100 hover:bg-slate-50/60 transition-premium">
                <td class="p-3.5 font-bold text-blue-700 text-xs">${row.co}</td>
                <td class="p-3.5 text-center text-slate-700 text-xs font-mono font-semibold">${row.direct_percent}%</td>
                <td class="p-3.5 text-center text-slate-700 text-xs font-mono font-semibold">${row.indirect_percent}% <span class="text-[10px] text-slate-400">(${row.indirect_rating}/3)</span></td>
                <td class="p-3.5 text-center text-emerald-700 text-xs font-bold font-mono">${row.overall_percent}%</td>
                <td class="p-3.5 text-center text-slate-500 text-xs font-mono">${row.target_benchmark}%</td>
                <td class="p-3.5 text-center text-slate-700 text-xs font-bold">${row.attainment_level}</td>
                <td class="p-3.5 text-center">
                  <span class="px-2.5 py-1 text-[11px] font-extrabold rounded-lg border ${badgeBg}">
                    ${attained ? 'ATTAINED ✓' : 'NOT MET'}
                  </span>
                </td>
              </tr>
            `;
          }).join('');

          workspace.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Direct Attainment (80%)</div>
                <div class="text-2xl font-black text-blue-700 mt-1">${summary.direct_attainment_percent}%</div>
                <div class="text-[11px] text-slate-400 mt-1">Formative Tasks & ESE Letter Grades</div>
              </div>
              <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Indirect Attainment (20%)</div>
                <div class="text-2xl font-black text-teal-700 mt-1">${summary.indirect_attainment_percent}%</div>
                <div class="text-[11px] text-slate-400 mt-1">Course Exit Survey Feedback</div>
              </div>
              <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Overall Attainment Level</div>
                <div class="text-2xl font-black text-emerald-700 mt-1">${summary.overall_attainment_level} (${summary.overall_attainment_percent}%)</div>
                <div class="text-[11px] text-emerald-600 font-bold mt-1">Target Student Benchmark: ${summary.target_benchmark}%</div>
              </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
              <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center flex-wrap gap-2">
                <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Course Outcome Attainment Summary Matrix</h5>
                <div class="flex items-center gap-3">
                  <span class="text-xs text-slate-500">Target Student Benchmark: <strong class="text-emerald-700 font-mono">${summary.target_benchmark}%</strong></span>
                  <button onclick="loadCourseAttainment()" title="Recalculate Attainment" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-premium flex items-center gap-1 cursor-pointer shadow-2xs">
                    <x-ui.icon name="refresh" class="w-3.5 h-3.5" /> Recalculate
                  </button>
                </div>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                  <thead>
                    <tr class="bg-slate-50/70 text-[11px] font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">
                      <th class="p-3.5">Course Outcome</th>
                      <th class="p-3.5 text-center">Direct Attainment (80%)</th>
                      <th class="p-3.5 text-center">Indirect Exit Survey (20%)</th>
                      <th class="p-3.5 text-center">Overall CO Attainment</th>
                      <th class="p-3.5 text-center">Target Benchmark</th>
                      <th class="p-3.5 text-center">Attainment Level</th>
                      <th class="p-3.5 text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${rowsHtml}
                  </tbody>
                </table>
              </div>
            </div>
          `;
        })
        .catch(err => {
          workspace.innerHTML = `
            <div class="p-6 text-center text-rose-600 font-bold bg-rose-50 border border-rose-200 rounded-2xl">
              Error connecting to server.
            </div>
          `;
        });
    }

    function openEseMarksModal() {
      if (!currentSubjectId) {
        alert("Please select a subject first.");
        return;
      }
      const modal = document.getElementById('modalEseMarks');
      if (modal) modal.classList.remove('hidden');

      const tbody = document.getElementById('eseMarksTableBody');
      tbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-400 font-bold">Loading student records...</td></tr>';

      const isR26 = window.currentSyllabusRevision === '2026' || (window.currentVirtualRevision && window.currentVirtualRevision.includes('2026'));
      const eseApiUrl = isR26 ? `/api/r26/classroom/${currentSubjectId}/ese-marks` : `/api/classroom/${currentSubjectId}/ese-marks`;

      fetch(eseApiUrl)
        .then(res => res.json())
        .then(data => {
          if (data.status !== 'SUCCESS') {
            tbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-rose-600 font-bold">Failed to load ESE records.</td></tr>';
            return;
          }

          const cfg = data.config || {};
          const defaultMax = isR26 ? 60 : 75;
          document.getElementById('eseEntryMode').value = cfg.entry_mode || 'dual';
          document.getElementById('eseMaxMarks').value = cfg.max_marks || defaultMax;
          document.getElementById('eseThresholdPercent').value = cfg.ese_threshold_percent || cfg.target_threshold_percent || 50;
          document.getElementById('eseThresholdGrade').value = cfg.ese_threshold_grade || cfg.target_grade || 'D';
          document.getElementById('cieThresholdPercent').value = cfg.cie_threshold_percent || 50;
          const targetVal = cfg.target_student_percent || cfg.level3_percent || 70;
          document.getElementById('targetStudentPercent').value = targetVal;
          if (document.getElementById('inputLevel3Percent')) document.getElementById('inputLevel3Percent').value = cfg.level3_percent || targetVal;
          if (document.getElementById('inputLevel2Percent')) document.getElementById('inputLevel2Percent').value = cfg.level2_percent || Math.max(0, targetVal - 10);
          if (document.getElementById('inputLevel1Percent')) document.getElementById('inputLevel1Percent').value = cfg.level1_percent || Math.max(0, targetVal - 20);

          renderEseStudentRows(data.students || [], 'dual', cfg.max_marks || defaultMax);
          updateEseSummaryStats(data.summary);
        })
        .catch(err => {
          tbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-rose-600 font-bold">Error connecting to server.</td></tr>';
        });
    }

    function closeEseMarksModal() {
      const modal = document.getElementById('modalEseMarks');
      if (modal) modal.classList.add('hidden');
    }

    function saveEseMarks() {
      const isR26 = window.currentSyllabusRevision === '2026' || (window.currentVirtualRevision && window.currentVirtualRevision.includes('2026'));
      const mode = 'dual';
      const maxMarks = parseFloat(document.getElementById('eseMaxMarks').value || (isR26 ? 60 : 75));
      const eseThresholdGrade = document.getElementById('eseThresholdGrade').value;
      const eseThresholdPercent = parseFloat(document.getElementById('eseThresholdPercent').value || 50);
      const cieThresholdPercent = parseFloat(document.getElementById('cieThresholdPercent').value || 50);
      const targetStudentPercent = parseFloat(document.getElementById('targetStudentPercent').value || 70);
      const level3Percent = parseFloat(document.getElementById('inputLevel3Percent')?.value || targetStudentPercent);
      const level2Percent = parseFloat(document.getElementById('inputLevel2Percent')?.value || Math.max(0, targetStudentPercent - 10));
      const level1Percent = parseFloat(document.getElementById('inputLevel1Percent')?.value || Math.max(0, targetStudentPercent - 20));

      const gradeSelects = document.querySelectorAll('.ese-grade-select');
      const marks = {};
      const grades = {};
      gradeSelects.forEach(sel => {
        const reg = sel.getAttribute('data-reg');
        if (reg) {
          const markInp = document.querySelector(`.ese-mark-input[data-reg="${reg}"]`);
          if (markInp && markInp.value.trim() !== '') {
            marks[reg] = markInp.value.trim();
          }
          if (sel.value.trim() !== '') {
            grades[reg] = sel.value.trim();
          }
        }
      });

      const payload = {
        entry_mode: mode,
        max_marks: maxMarks,
        ese_threshold_grade: eseThresholdGrade,
        ese_threshold_percent: eseThresholdPercent,
        cie_threshold_percent: cieThresholdPercent,
        target_student_percent: targetStudentPercent,
        level3_percent: level3Percent,
        level2_percent: level2Percent,
        level1_percent: level1Percent,
        marks: marks,
        grades: grades
      };

      const saveEseApiUrl = isR26 
        ? `/api/r26/classroom/${currentSubjectId}/ese-marks/bulk-update` 
        : `/api/classroom/${currentSubjectId}/ese-marks/bulk-update`;

      fetch(saveEseApiUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message || 'Threshold settings & student evaluation records updated successfully.');
        closeEseMarksModal();
        loadCourseAttainment();
      })
      .catch(err => {
        alert('Failed to save ESE records.');
      });
    }

    function renderEseStudentRows(students, mode, maxMarks) {
      const tbody = document.getElementById('eseMarksTableBody');
      if (!Array.isArray(students) || students.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-400 font-bold">No students registered in this batch.</td></tr>';
        return;
      }

      let html = '';
      students.forEach(s => {
        const reg = s.reg_no || s.sbte_reg_no;
        const markVal = s.ese_marks !== null && s.ese_marks !== undefined ? s.ese_marks : null;
        const gradeVal = s.ese_grade ? s.ese_grade.trim().toUpperCase() : '';

        const inputHtml = `
          <div class="flex items-center justify-center gap-1.5 flex-nowrap">
            <div class="flex items-center gap-1">
              <input type="number" step="0.5" min="0" max="${maxMarks}" placeholder="Marks" data-reg="${reg}" class="ese-mark-input w-20 bg-white border border-slate-200 text-blue-700 font-mono font-bold text-center px-2 py-1.5 rounded-lg outline-none focus:border-blue-500 text-xs shadow-2xs" value="${markVal !== null ? markVal : ''}" onfocus="this.select()" oninput="onEseMarkChange('${reg}')" onkeydown="handleEseMarkKeyDown(event, this)">
              <span class="text-[10px] text-slate-400 font-bold">/${maxMarks}</span>
            </div>
            <select data-reg="${reg}" onchange="onEseGradeChange('${reg}')" class="ese-val-input ese-grade-select bg-white border border-slate-200 text-teal-700 font-bold text-center w-36 px-2 py-1.5 rounded-lg outline-none focus:border-teal-500 cursor-pointer text-xs shadow-2xs">
              <option value="" ${!gradeVal ? 'selected' : ''} class="text-slate-400 font-normal">-- Grade --</option>
              <option value="S" ${gradeVal === 'S' ? 'selected' : ''}>S (90%+ | 10 GP)</option>
              <option value="A" ${gradeVal === 'A' ? 'selected' : ''}>A (80%-89% | 9 GP)</option>
              <option value="B" ${gradeVal === 'B' ? 'selected' : ''}>B (70%-79% | 8 GP)</option>
              <option value="C" ${gradeVal === 'C' ? 'selected' : ''}>C (60%-69% | 7 GP)</option>
              <option value="D" ${gradeVal === 'D' ? 'selected' : ''}>D (50%-59% | 6 GP)</option>
              <option value="E" ${gradeVal === 'E' || gradeVal === 'P' ? 'selected' : ''}>E (40%-49% | 5 GP)</option>
              <option value="F" ${gradeVal === 'F' ? 'selected' : ''}>F (&lt;40% Fail | 0 GP)</option>
              <option value="FE" ${gradeVal === 'FE' ? 'selected' : ''}>FE (Absent | 0 GP)</option>
            </select>
          </div>
        `;

        html += `
          <tr class="hover:bg-slate-50 transition-premium">
            <td class="p-3 font-mono font-bold text-slate-800 text-center">${s.roll_no || '-'}</td>
            <td class="p-3 font-mono text-slate-500">${reg || '-'}</td>
            <td class="p-3 font-bold text-slate-900">${s.name}</td>
            <td class="p-3 text-center">${inputHtml}</td>
            <td class="p-3 text-center" id="status_cell_${reg}">
              <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 text-slate-600 border border-slate-200">PENDING</span>
            </td>
          </tr>
        `;
      });

      tbody.innerHTML = html;
      recalculateEseStats();
    }

    function updateEseSummaryStats(summary) {
      if (!summary) return;
      document.getElementById('statTotalStudents').innerText = summary.total_students || 0;
      document.getElementById('statAppearedStudents').innerText = summary.appeared_count || 0;
      document.getElementById('statMetTargetStudents').innerText = `${summary.met_target_count || 0} (${summary.met_target_percent || 0}%)`;
      
      const lvlEl = document.getElementById('statAttainmentLevel');
      if (lvlEl) {
        lvlEl.innerText = summary.attainment_level_text || (`Level ${summary.attainment_level || 0}`);
        if (summary.level_class) {
          lvlEl.className = `text-sm font-black ${summary.level_class}`;
        }
      }
    }

    function handleEseMarkKeyDown(event, inputElem) {
      if (event.key === 'Enter' || event.key === 'Tab') {
        event.preventDefault();
        const currentRow = inputElem.closest('tr');
        if (!currentRow) return;

        if (event.shiftKey) {
          const prevRow = currentRow.previousElementSibling;
          if (prevRow) {
            const targetInput = prevRow.querySelector('.ese-mark-input');
            if (targetInput) {
              targetInput.focus();
              targetInput.select();
            }
          }
        } else {
          const nextRow = currentRow.nextElementSibling;
          if (nextRow) {
            const targetInput = nextRow.querySelector('.ese-mark-input');
            if (targetInput) {
              targetInput.focus();
              targetInput.select();
            }
          }
        }
      }
    }
    window.handleEseMarkKeyDown = handleEseMarkKeyDown;

    function onEseMarkChange(reg) {
      const maxMarks = parseFloat(document.getElementById('eseMaxMarks').value || 75);
      const markInp = document.querySelector(`.ese-mark-input[data-reg="${reg}"]`);
      const gradeSel = document.querySelector(`.ese-grade-select[data-reg="${reg}"]`);
      if (!markInp || !gradeSel) return;
      const rawVal = markInp.value.trim();
      if (rawVal === '') {
        gradeSel.value = '';
      } else {
        const mark = parseFloat(rawVal);
        if (!isNaN(mark) && maxMarks > 0) {
          const pct = (mark / maxMarks) * 100.0;
          let g = 'F';
          if (pct >= 90.0) g = 'S';
          else if (pct >= 80.0) g = 'A';
          else if (pct >= 70.0) g = 'B';
          else if (pct >= 60.0) g = 'C';
          else if (pct >= 50.0) g = 'D';
          else if (pct >= 40.0) g = 'E';
          gradeSel.value = g;
        }
      }
      recalculateEseStats();
    }

    function onEseGradeChange(reg) {
      const maxMarks = parseFloat(document.getElementById('eseMaxMarks').value || 75);
      const markInp = document.querySelector(`.ese-mark-input[data-reg="${reg}"]`);
      const gradeSel = document.querySelector(`.ese-grade-select[data-reg="${reg}"]`);
      if (!markInp || !gradeSel) return;
      const g = gradeSel.value.trim().toUpperCase();
      if (!g) {
        markInp.value = '';
      } else if (g === 'FE' || g === 'F') {
        markInp.value = '0';
      } else {
        const midpoints = { 'S': 0.95, 'A': 0.85, 'B': 0.75, 'C': 0.65, 'D': 0.55, 'E': 0.45 };
        const ratio = midpoints[g] || 0.45;
        markInp.value = (ratio * maxMarks).toFixed(1);
      }
      recalculateEseStats();
    }

    function recalculateEseStats(fromTarget = false) {
      const maxMarks = parseFloat(document.getElementById('eseMaxMarks').value || 75);
      const eseThresholdGrade = document.getElementById('eseThresholdGrade').value || 'D';
      const eseTargetPct = parseFloat(document.getElementById('eseThresholdPercent').value || 50);
      const targetStudentPct = parseFloat(document.getElementById('targetStudentPercent').value || 70);

      const elL3Input = document.getElementById('inputLevel3Percent');
      const elL2Input = document.getElementById('inputLevel2Percent');
      const elL1Input = document.getElementById('inputLevel1Percent');

      if (fromTarget && elL3Input && elL2Input && elL1Input) {
        elL3Input.value = targetStudentPct;
        elL2Input.value = Math.max(0, targetStudentPct - 10);
        elL1Input.value = Math.max(0, targetStudentPct - 20);
      }

      const lvl3Val = elL3Input ? parseFloat(elL3Input.value || targetStudentPct) : targetStudentPct;
      const lvl2Val = elL2Input ? parseFloat(elL2Input.value || (targetStudentPct - 10)) : Math.max(0, targetStudentPct - 10);
      const lvl1Val = elL1Input ? parseFloat(elL1Input.value || (targetStudentPct - 20)) : Math.max(0, targetStudentPct - 20);

      // Official SBTE Kerala Polytechnic Grading Scale (10 Grade Points max)
      const SBTE_GRADE_POINTS = {
        'S': 10, 'A': 9, 'B': 8, 'C': 7, 'D': 6, 'E': 5, 'F': 0, 'FE': 0
      };
      const minRequiredPoints = SBTE_GRADE_POINTS[eseThresholdGrade] || 6;

      const gradeSelects = document.querySelectorAll('.ese-grade-select');
      const totalStudents = gradeSelects.length;
      let appeared = 0;
      let metTarget = 0;

      gradeSelects.forEach(sel => {
        const reg = sel.getAttribute('data-reg');
        const val = sel.value.trim().toUpperCase();
        const markInp = document.querySelector(`.ese-mark-input[data-reg="${reg}"]`);
        const markVal = markInp && markInp.value.trim() !== '' ? parseFloat(markInp.value.trim()) : null;
        const statusCell = document.getElementById(`status_cell_${reg}`);
        
        let isMet = false;
        let isPending = false;
        let isAbsent = false;

        if (!val && markVal === null) {
          isPending = true;
        } else if (val === 'FE') {
          isAbsent = true;
          appeared++;
        } else {
          appeared++;
          const studentPoints = SBTE_GRADE_POINTS[val] || 0;
          const markPct = markVal !== null && maxMarks > 0 ? (markVal / maxMarks) * 100 : 0;
          if ((studentPoints >= 5 && studentPoints >= minRequiredPoints) || markPct >= eseTargetPct) {
            isMet = true;
            metTarget++;
          }
        }

        if (statusCell) {
          if (isPending) {
            statusCell.innerHTML = '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 text-slate-500 border border-slate-200">NOT ENTERED</span>';
          } else if (isAbsent) {
            statusCell.innerHTML = '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-amber-50 text-amber-700 border border-amber-200">ABSENT</span>';
          } else if (isMet) {
            statusCell.innerHTML = '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-50 text-emerald-700 border border-emerald-200">ATTAINED ✓</span>';
          } else {
            statusCell.innerHTML = '<span class="px-2 py-0.5 text-[10px] font-bold rounded bg-rose-50 text-rose-700 border border-rose-200">NOT MET</span>';
          }
        }
      });

      const metPercent = appeared > 0 ? ((metTarget / appeared) * 100).toFixed(1) : 0;
      let levelText = appeared === 0 ? 'Pending Evaluation' : 'Level 0 (Nil)';
      let levelClass = appeared === 0 ? 'text-slate-500' : 'text-rose-600';

      if (appeared > 0) {
        if (parseFloat(metPercent) >= lvl3Val) {
          levelText = `Level 3 (High - ${metPercent}%)`;
          levelClass = 'text-emerald-700';
        } else if (parseFloat(metPercent) >= lvl2Val) {
          levelText = `Level 2 (Moderate - ${metPercent}%)`;
          levelClass = 'text-amber-700';
        } else if (parseFloat(metPercent) >= lvl1Val) {
          levelText = `Level 1 (Low - ${metPercent}%)`;
          levelClass = 'text-blue-700';
        }
      }

      updateEseSummaryStats({
        total_students: totalStudents,
        appeared_count: appeared,
        met_target_count: metTarget,
        met_target_percent: metPercent,
        attainment_level_text: levelText,
        level_class: levelClass
      });
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
      const attainWs = document.getElementById('courseAttainmentWorkspace');
      if (attainWs) {
        attainWs.innerHTML = `
          <div class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
            <div class="w-6 h-6 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin mb-3"></div>
            <p class="text-xs font-bold text-slate-600">Select Course Attainment tab to calculate metrics...</p>
          </div>
        `;
      }
      document.getElementById('activeSyllabusCard').classList.add('hidden');
      // Note: vcSubjectInfo is set by openClassroom immediately - don't hide it during load
      document.getElementById('parseStatusBadge').innerText = 'Syncing...';
      document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-2.5 py-1 rounded-md bg-blue-900/30 text-blue-400 border border-blue-500/30';

      return fetch(`/api/classroom/${subjectId}/details`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS' && data.data) {
