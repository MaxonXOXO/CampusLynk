          currentDeadlines = data.data.assignment_deadlines || {};
          currentQuestions = data.data.assignment_questions || {};
          currentSummativeTests = data.data.summative_manual_tests || {};
          currentSubjectName = data.data.subject_name || '';
          currentSubjectCode = data.data.subject_code || '';
          const vcSubName = document.getElementById('vcSubjectName');
          const vcSubCode = document.getElementById('vcSubjectCode');
          const vcSubInfo = document.getElementById('vcSubjectInfo');
          const vcPropHours = document.getElementById('vcSyllabusProposedHours');
          if (vcSubName) vcSubName.innerText = currentSubjectName;
          if (vcSubCode) vcSubCode.innerText = currentSubjectCode;
          if (vcPropHours) {
              const pHours = data.data.proposed_total_hours || 60;
              vcPropHours.innerText = `Proposed Hours: ${pHours} hrs (+2 tests)`;
          }
          if (vcSubInfo) vcSubInfo.style.display = (currentSubjectName || currentSubjectCode) ? 'flex' : 'none';
          currentSubjectSemester = data.data.semester || '';
          currentSubjectAcademicYear = data.data.academic_year || '';
          currentSubjectClassroomId = data.data.classroom_id || '';
          window.currentSyllabusRevision = data.data.syllabus_revision || '2021';
          window.currentVirtualStudents = data.data.students || [];
          window.currentVirtualSemester = data.data.semester || '';
          window.currentProposedTotalHours = data.data.proposed_total_hours || 60;
          
          renderCourseStructure(data.data.cos, data.data.modules, data.data.textbooks, data.data.copo);
          renderCoursePlanner(data.data.lesson_plans);
          renderFormativeAssessment(data.data.students || []);
          renderSummativeAssessment(data.data.cos, data.data.students || []);
          loadActiveOnlineTests(subjectId);
          
          // Always render the formative questions section (show prompt if none generated yet)
          renderAIQuestionsList(currentQuestions, subjectId);

          const subjectTypeRaw = (data.data.subject_type || '').toLowerCase();
          const isSeminar = subjectTypeRaw === 'seminar';
          const isPractical = subjectTypeRaw === 'practical' || subjectTypeRaw === 'lab' || subjectTypeRaw.includes('lab') || subjectTypeRaw.includes('practical') || subjectTypeRaw.includes('practicum');
          window.isCurrentSubjectPractical = isPractical;

          const tabSeminar = document.getElementById('tabSeminar');
          const tabLab = document.getElementById('tabLab');
          const tabLabCoPo = document.getElementById('tabLabCoPo');
          const tabStructure = document.getElementById('tabStructure');
          const tabPlanner = document.getElementById('tabPlanner');
          const tabAssessment = document.getElementById('tabAssessment');
          const tabSummative = document.getElementById('tabSummative');
          const tabReports = document.getElementById('tabReports');
          const pRepActions = document.getElementById('practicalReportsActions');

          if (isSeminar) {
            document.getElementById('panelTitle').innerText = 'Virtual Seminar Room';
            document.getElementById('vcTitle').innerHTML = `<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h20"/><path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/><path d="m7 21 5-5 5 5"/></svg> Virtual Seminar Room`;
            if (tabSeminar) tabSeminar.classList.remove('hidden');
            if (tabLab) tabLab.classList.add('hidden');
            if (tabLabCoPo) tabLabCoPo.classList.add('hidden');
            if (tabStructure) tabStructure.classList.add('hidden');
            if (tabPlanner) tabPlanner.classList.add('hidden');
            if (tabAssessment) tabAssessment.classList.add('hidden');
            if (tabSummative) tabSummative.classList.add('hidden');
            if (pRepActions) pRepActions.classList.add('hidden');
            toggleClassroomTab('seminar_evaluation');
          } else if (isPractical) {
            document.getElementById('panelTitle').innerText = 'Virtual Lab Workspace';
            document.getElementById('vcTitle').innerHTML = `<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.31L4.75 20.46A1 1 0 0 0 5.64 22h12.72a1 1 0 0 0 .89-1.54L14 9.31V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/></svg> Virtual Lab Workspace`;
            if (tabSeminar) tabSeminar.classList.add('hidden');
            if (tabLab) tabLab.classList.remove('hidden');
            if (tabLabCoPo) tabLabCoPo.classList.remove('hidden');
            if (tabStructure) tabStructure.classList.remove('hidden');
            if (tabPlanner) tabPlanner.classList.remove('hidden');
            if (tabAssessment) tabAssessment.classList.add('hidden');
            if (tabSummative) tabSummative.classList.add('hidden');
            if (tabReports) tabReports.classList.remove('hidden');
            if (pRepActions) {
              pRepActions.classList.remove('hidden');
              pRepActions.classList.add('flex');
              document.getElementById('pRepBtnRegister').href = `/classroom/${subjectId}/practical-report/print?type=register`;
              document.getElementById('pRepBtnAttendance').href = `/classroom/${subjectId}/practical-report/print?type=attendance`;
              document.getElementById('pRepBtnExperiments').href = `/classroom/${subjectId}/practical-report/print?type=experiments`;
              document.getElementById('pRepBtnPlanner').href = `/classroom/${subjectId}/practical-report/print?type=planner`;
              document.getElementById('pRepBtnProjects').href = `/classroom/${subjectId}/practical-report/print?type=projects`;
            }
            toggleClassroomTab('lab_evaluation');
          } else {
            document.getElementById('panelTitle').innerText = 'Virtual Classroom';
            document.getElementById('vcTitle').innerHTML = `<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> Virtual Classroom`;
            if (tabSeminar) tabSeminar.classList.add('hidden');
            if (tabLab) tabLab.classList.add('hidden');
            if (tabLabCoPo) tabLabCoPo.classList.add('hidden');
            if (tabStructure) tabStructure.classList.remove('hidden');
            if (tabPlanner) tabPlanner.classList.remove('hidden');
            if (tabAssessment) tabAssessment.classList.remove('hidden');
            if (tabSummative) tabSummative.classList.remove('hidden');
            if (pRepActions) pRepActions.classList.add('hidden');
            toggleClassroomTab('structure');
          }

          // Update vcTitle to include subject name for regular classrooms
          if (!isSeminar && !isPractical) {
            document.getElementById('vcTitle').innerHTML = `<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> ${currentSubjectName || 'Virtual Classroom'}`;
          }

          if (data.data.syllabus_pdf_path) {
            document.getElementById('activeSyllabusCard').classList.remove('hidden');
            const dlBtn = document.getElementById('downloadSyllabusBtn');
            if (dlBtn) dlBtn.dataset.url = `/api/classroom/${subjectId}/syllabus/download`;
            document.getElementById('parseStatusBadge').innerText = 'Parsed & Synced';
            document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs whitespace-nowrap';
          } else {
            document.getElementById('parseStatusBadge').innerText = 'Waiting for upload';
            document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap shadow-2xs';
          }
        } else {
          document.getElementById('parseStatusBadge').innerText = 'Waiting for upload';
          document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap shadow-2xs';
          document.getElementById('courseStructureContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              </div>
              <p class="text-sm font-bold text-slate-400">No syllabus loaded.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus PDF to automatically populate Course Outcomes, Modules, and Textbooks.</p>
            </div>
          `;
          document.getElementById('coursePlannerContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              </div>
              <p class="text-sm font-bold text-slate-400">Planner not generated.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to automatically generate the lesson plan.</p>
            </div>
          `;
          document.getElementById('formativeAssessmentContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
              </div>
              <p class="text-sm font-bold text-slate-400">Formative Assessment Inactive.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate formative assessment tasks and mark entry.</p>
            </div>
          `;
          document.getElementById('summativeAssessmentContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              </div>
              <p class="text-sm font-bold text-slate-400">Summative Assessment Inactive.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate written test configuration and mark entry.</p>
            </div>
          `;
          const qbContent = document.getElementById('questionBankContent');
          if (qbContent) {
              qbContent.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                  <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
                  </div>
                  <p class="text-sm font-bold text-slate-400">Question Bank Inactive.</p>
                  <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate the question bank pooling.</p>
                </div>
              `;
          }
        }
      })
      .catch(err => {
        console.error('[loadCourseDetails] Error:', err);
        document.getElementById('parseStatusBadge').innerText = 'Load Error';
        document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-2.5 py-1 rounded-md bg-red-900/30 text-red-400 border border-red-500/30';
        document.getElementById('courseStructureContent').innerHTML = `
          <div class="flex flex-col items-center justify-center py-16 text-center h-full">
            <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            <p class="text-sm font-bold text-red-400">Failed to load course data</p>
            <p class="text-xs text-slate-500 mt-1.5 max-w-xs">${err.message}</p>
            <button onclick="loadCourseDetails(currentSubjectId)" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl cursor-pointer transition-premium">
              Retry
            </button>
          </div>
        `;
      });
    }

    /**
     * Opens the syllabus PDF by opening the controller URL via a hidden anchor click.
     * This keeps the session cookie active (same-origin) and avoids popup blockers.
     */
    function downloadSyllabusPDF() {
      const btn = document.getElementById('downloadSyllabusBtn');
      if (!btn) return;
      const url = btn.dataset.url;
      if (!url) { alert('No syllabus attached to this subject yet.'); return; }
      // Use a hidden anchor element — same-origin navigation, no popup blocker
      const a = document.createElement('a');
      a.href = url;
      a.target = '_blank';
      a.rel = 'noopener noreferrer';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }

    // CO colour palette for lesson planner badges
    const CO_COLORS = {
      'CO1': 'bg-blue-50 text-blue-700 border-blue-200',
      'CO2': 'bg-violet-50 text-violet-700 border-violet-200',
      'CO3': 'bg-emerald-50 text-emerald-700 border-emerald-200',
      'CO4': 'bg-amber-50 text-amber-700 border-amber-200',
      'CO5': 'bg-rose-50 text-rose-700 border-rose-200',
      'CO6': 'bg-cyan-50 text-cyan-700 border-cyan-200',
    };

        function renderCoursePlanner(lessonPlans) {
      const container = document.getElementById('coursePlannerContent');
      if (!container) return;

      // ── Empty state ──────────────────────────────────────────────────────────
      if (!lessonPlans || lessonPlans.length === 0) {
        let emptyIcon  = window.isCurrentSubjectPractical ? 'science' : 'event_note';
        let emptyColor = window.isCurrentSubjectPractical ? 'text-teal-400' : 'text-sky-400';
        let genBtn = window.isCurrentSubjectPractical
          ? `<button onclick="openGeneratePlannerModal()" class="px-4 py-2 bg-gradient-to-r from-teal-600 to-emerald-500 hover:from-teal-500 hover:to-emerald-400 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-teal-900/20">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg> Auto-Generate (Lab)
             </button>`
          : `<button onclick="regenerateLessonPlan()" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-blue-900/20">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg> Generate Lesson Plan
             </button>`;
        let loadBtn = `<button onclick="loadLessonPlanTemplate()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-premium cursor-pointer border border-slate-700/50 flex items-center gap-1.5">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Load Template
            </button>`;
        container.innerHTML = `
          <div class="flex flex-col items-center justify-center py-16 text-center h-full">
            <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
            </div>
            <p class="text-sm font-bold text-slate-400">No Lesson Plan Generated Yet</p>
            <p class="text-xs mt-1.5 max-w-xs text-slate-500 leading-relaxed mb-6">
              Generate a smart plan based on Course Outcomes and Modules, or load a saved template.
            </p>
            <div class="flex items-center gap-3 flex-wrap justify-center">
              ${genBtn}
              ${loadBtn}
            </div>
          </div>
        `;
        return;
      }

      // ── Populated state ──────────────────────────────────────────────────────
      let totalHours = lessonPlans.reduce((sum, lp) => sum + (parseInt(lp.allocated_hours) || 1), 0);
      let testDays   = lessonPlans.filter(lp => (lp.pedagogy || '').toLowerCase() === 'test').length;
      let lectureDays = lessonPlans.length - testDays;
      let proposedVal = window.currentProposedTotalHours || 60;

      // Header buttons
      let practicalRegenBtn = window.isCurrentSubjectPractical
        ? `<button onclick="openGeneratePlannerModal()" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs">
             <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.31L4.75 20.46A1 1 0 0 0 5.64 22h12.72a1 1 0 0 0 .89-1.54L14 9.31V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/></svg> Regenerate (Lab)
           </button>` : '';

      let html = `
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4 pb-3 border-b border-slate-200">
          <div>
            <h4 class="text-sm font-bold text-slate-800">Lesson Planner</h4>
            <p id="plannerSubtitle" class="text-xs text-slate-500 mt-0.5">${lectureDays} lecture days · ${testDays} test days · ${totalHours} total hours (Syllabus Proposed: ${proposedVal} hours) · Auto-growing content textareas</p>
          </div>
          <div class="flex items-center gap-2 flex-wrap">
            ${practicalRegenBtn}
            <button onclick="regenerateLessonPlan()" id="btnRegenPlan" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs" title="Re-generate all lesson plans from stored syllabus data">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg> Regenerate
            </button>
            <button onclick="saveLessonPlanChanges()" id="btnSavePlan" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save Changes
            </button>
            <button onclick="syncDatesFromLogData()" id="btnSyncDates" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs" title="Cross-sync actual conducted dates from class attendance logs">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg> Sync Dates from Log Data
            </button>
            <button onclick="saveLessonPlanAsTemplate()" id="btnSavePlanTemplate" class="px-3 py-1.5 bg-violet-600 hover:bg-violet-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs" title="Save as reusable template for other batches with the same subject">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/><line x1="12" x2="12" y1="7" y2="13"/><line x1="9" x2="15" y1="10" y2="10"/></svg> Save as Template
            </button>
            <button onclick="loadLessonPlanTemplate()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs" title="Load previously saved template">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Load Template
            </button>
            <a href="/classroom/${currentSubjectId}/lesson-plan/print" target="_blank" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs" title="Print Lesson Plan (A4)">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Plan
            </a>
          </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-xs">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1050px]" id="lessonPlanTable">
              <thead>
                <tr class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                  <th class="p-3 w-10 text-center">#</th>
                  <th class="p-3 w-24 text-center">CO</th>
                  <th class="p-3">Topic / Content <span class="text-slate-400 normal-case font-normal">(auto-grow)</span></th>
                  <th class="p-3 w-36 text-center">Proposed Date</th>
                  <th class="p-3 w-36 text-center">Actual Date</th>
                  <th class="p-3 w-20 text-center">Hrs</th>
                  <th class="p-3 w-28 text-center">Pedagogy</th>
                  <th class="p-3 w-36">Remarks</th>
                  <th class="p-3 w-12 text-center">Action</th>
                </tr>
              </thead>
              <tbody>
      `;

      const coOptionsList = ['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6', '--'];

      lessonPlans.forEach((lp, index) => {
        let co        = lp.co_id || '--';
        let proposed  = lp.proposed_date || '';
        let actual    = lp.actual_date || '';
        let pedagogy  = lp.pedagogy || 'Lecture';
        let remarks   = (lp.remarks || '').replace(/"/g, '&quot;');
        let topic     = (lp.topic_content || '').replace(/"/g, '&quot;');
        let dayNo     = lp.day_no || (index + 1);
        let isTest    = (pedagogy || '').toLowerCase() === 'test';
        let rowBg     = isTest ? 'bg-slate-100/90 border-b border-slate-200 hover:bg-slate-100' : 'bg-white border-b border-slate-100 hover:bg-slate-50/80';

        let coSelectOptions = coOptionsList.map(opt => `<option value="${opt}" ${co === opt ? 'selected' : ''}>${opt}</option>`).join('');

        let pedagogyOptions = ['Lecture', 'Test', 'Lab', 'Demo', 'Tutorial', 'Assignment', 'Revision', 'Group Discussion', 'Seminar'].map(ped => {
          return `<option value="${ped}" ${pedagogy.toLowerCase() === ped.toLowerCase() ? 'selected' : ''}>${ped}</option>`;
        }).join('');

        html += `
          <tr class="border-b ${rowBg} last:border-0 hover:bg-slate-50/80 transition-colors" data-lp-id="${lp.id}">
            <td class="p-2 text-center text-xs font-bold text-slate-700" data-field="day_no">${dayNo}</td>
            <td class="p-2 text-center">
              <select data-field="co_id" class="w-full bg-white border border-slate-200 hover:border-slate-300 rounded-lg px-2 py-1.5 text-xs font-bold text-blue-700 outline-none transition-all cursor-pointer shadow-2xs" onchange="markPlanDirty(${lp.id})">
                ${coSelectOptions}
              </select>
            </td>
            <td class="p-2">
              <textarea data-field="topic" rows="1"
                class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2.5 py-2 text-slate-900 font-medium text-xs shadow-2xs outline-none transition-all placeholder:text-slate-400 resize-none overflow-hidden min-h-[38px] box-border leading-relaxed"
                style="min-height: 38px;"
                placeholder="Enter topic..."
                oninput="autoResizeTopicTextarea(this); markPlanDirty(${lp.id})">${topic}</textarea>
            </td>
            <td class="p-2">
              <input type="date" value="${proposed}" data-field="proposed_date"
                class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2 py-1.5 text-slate-800 text-xs font-mono shadow-2xs outline-none transition-all"
                onchange="markPlanDirty(${lp.id}); autoSavePlanRow(${lp.id}, this.closest('tr'))">
            </td>
            <td class="p-2">
              <input type="date" value="${actual}" data-field="actual_date"
                class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-lg px-2 py-1.5 text-emerald-700 font-bold text-xs font-mono shadow-2xs outline-none transition-all"
                onchange="markPlanDirty(${lp.id}); autoSavePlanRow(${lp.id}, this.closest('tr'))">
            </td>
            <td class="p-2 text-center">
              <input type="number" min="1" max="10" value="${lp.allocated_hours || 1}" data-field="allocated_hours"
                class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2 py-1.5 text-slate-900 text-center font-mono font-bold text-xs shadow-2xs outline-none transition-all"
                onchange="markPlanDirty(${lp.id}); recalculatePlanHours()">
            </td>
            <td class="p-2">
              <select data-field="pedagogy" class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2 py-1.5 text-slate-800 text-xs shadow-2xs outline-none transition-all cursor-pointer" onchange="markPlanDirty(${lp.id})">
                ${pedagogyOptions}
              </select>
            </td>
            <td class="p-2">
              <input type="text" value="${remarks}" data-field="remarks"
                class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2.5 py-1.5 text-slate-800 text-xs shadow-2xs outline-none transition-all placeholder:text-slate-400"
                placeholder="Add remarks..."
                onchange="markPlanDirty(${lp.id})">
            </td>
            <td class="p-2 text-center">
              <button type="button" onclick="deleteLessonPlanRow(${lp.id}, this)" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1.5 rounded-lg transition-colors cursor-pointer" title="Delete Row">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
              </button>
            </td>
          </tr>
        `;
      });

      html += `
              </tbody>
            </table>
          </div>
        </div>

        <!-- Bottom Controls Bar (Add Row & Save Options) -->
        <div class="flex flex-wrap items-center justify-between gap-4 mt-4 pt-2">
          <div>
            <button type="button" onclick="addLessonPlanRow()" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs cursor-pointer">
              <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v8"/><path d="M8 12h8"/></svg> Add Row
            </button>
          </div>
          <div class="flex items-center gap-3">
            <button type="button" onclick="saveLessonPlanChanges()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save
            </button>
            <button type="button" onclick="saveLessonPlanChanges()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Save Now
            </button>
          </div>
        </div>

        <div id="planSaveStatusBar" class="hidden mt-3 px-4 py-2.5 bg-amber-900/20 border border-amber-500/20 rounded-xl flex items-center gap-3 text-xs font-bold text-amber-400">
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
          <span>You have unsaved changes.</span>
          <button onclick="saveLessonPlanChanges()" class="ml-auto px-3 py-1 bg-emerald-700 hover:bg-emerald-600 text-white rounded-lg cursor-pointer transition-premium">
            Save Now
          </button>
        </div>
      `;

      container.innerHTML = html;
      // Auto-size all textareas only if container is currently visible
      const plannerContainer = document.getElementById('coursePlannerContent');
      if (plannerContainer && !plannerContainer.classList.contains('hidden') && plannerContainer.offsetParent !== null) {
        setTimeout(autoResizeAllPlanTextareas, 50);
      }
    }

    // -------------------------------------------------------------
    // Topic Auto-Resize & Sizing Helpers
    // -------------------------------------------------------------
    function autoResizeTopicTextarea(ta) {
      if (!ta) return;
      if (ta.offsetParent === null) {
        // Element is in a hidden tab container - clear any forced height so it falls back to min-height
        ta.style.height = '';
        return;
      }
      ta.style.height = 'auto';
      const targetHeight = Math.max(38, ta.scrollHeight);
      ta.style.height = targetHeight + 'px';
    }
    window.autoResizeTopicTextarea = autoResizeTopicTextarea;

    function autoResizeAllPlanTextareas() {
      const container = document.getElementById('coursePlannerContent');
      if (!container || container.classList.contains('hidden') || container.offsetParent === null) {
        return;
      }
      const textareas = container.querySelectorAll('textarea[data-field="topic"]');
      textareas.forEach(ta => {
        autoResizeTopicTextarea(ta);
      });
    }
    window.autoResizeAllPlanTextareas = autoResizeAllPlanTextareas;

    window.addEventListener('resize', () => {
      if (typeof window.autoResizeAllPlanTextareas === 'function') {
        window.autoResizeAllPlanTextareas();
      }
    });

    // -------------------------------------------------------------
    // Lesson Plan Helpers: Add Row, Delete Row, Sync Dates, Live Recalc
    // -------------------------------------------------------------
    let _tempNewRowCounter = 1;

    function addLessonPlanRow() {
      const tbody = document.querySelector('#lessonPlanTable tbody');
      if (!tbody) return;

      const currentRows = tbody.querySelectorAll('tr[data-lp-id]');
      const dayNo = currentRows.length + 1;
      const tempId = 'new_' + (_tempNewRowCounter++);

      const tr = document.createElement('tr');
      tr.className = "border-b bg-white border-slate-100 hover:bg-slate-50/80 transition-colors";
      tr.setAttribute('data-lp-id', tempId);

      const coOptionsList = ['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6', '--'];
      let coSelectOptions = coOptionsList.map(opt => `<option value="${opt}" ${opt === 'CO1' ? 'selected' : ''}>${opt}</option>`).join('');

      let pedagogyOptions = ['Lecture', 'Test', 'Lab', 'Demo', 'Tutorial', 'Assignment', 'Revision', 'Group Discussion', 'Seminar'].map(ped => {
        return `<option value="${ped}" ${ped === 'Lecture' ? 'selected' : ''}>${ped}</option>`;
      }).join('');

      tr.innerHTML = `
        <td class="p-2 text-center text-xs font-bold text-slate-700" data-field="day_no">${dayNo}</td>
        <td class="p-2 text-center">
          <select data-field="co_id" class="w-full bg-white border border-slate-200 hover:border-slate-300 rounded-lg px-2 py-1.5 text-xs font-bold text-blue-700 outline-none transition-all cursor-pointer shadow-2xs" onchange="markPlanDirty('${tempId}')">
            ${coSelectOptions}
          </select>
        </td>
        <td class="p-2">
          <textarea data-field="topic" rows="1"
            class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2.5 py-2 text-slate-900 font-medium text-xs shadow-2xs outline-none transition-all placeholder:text-slate-400 resize-none overflow-hidden min-h-[38px] box-border leading-relaxed"
            style="min-height: 38px;"
            placeholder="Enter topic..."
            oninput="autoResizeTopicTextarea(this); markPlanDirty('${tempId}')"></textarea>
        </td>
        <td class="p-2">
          <input type="date" value="" data-field="proposed_date"
            class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2 py-1.5 text-slate-800 text-xs font-mono shadow-2xs outline-none transition-all"
            onchange="markPlanDirty('${tempId}')">
        </td>
        <td class="p-2">
          <input type="date" value="" data-field="actual_date"
            class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 rounded-lg px-2 py-1.5 text-emerald-700 font-bold text-xs font-mono shadow-2xs outline-none transition-all"
            onchange="markPlanDirty('${tempId}')">
        </td>
        <td class="p-2 text-center">
          <input type="number" min="1" max="10" value="1" data-field="allocated_hours"
            class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2 py-1.5 text-slate-900 text-center font-mono font-bold text-xs shadow-2xs outline-none transition-all"
            onchange="markPlanDirty('${tempId}'); recalculatePlanHours()">
        </td>
        <td class="p-2">
          <select data-field="pedagogy" class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2 py-1.5 text-slate-800 text-xs shadow-2xs outline-none transition-all cursor-pointer" onchange="markPlanDirty('${tempId}')">
            ${pedagogyOptions}
          </select>
        </td>
        <td class="p-2">
          <input type="text" value="" data-field="remarks"
            class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2.5 py-1.5 text-slate-800 text-xs shadow-2xs outline-none transition-all placeholder:text-slate-400"
            placeholder="Add remarks..."
            onchange="markPlanDirty('${tempId}')">
        </td>
        <td class="p-2 text-center">
          <button type="button" onclick="deleteLessonPlanRow('${tempId}', this)" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-1.5 rounded-lg transition-colors cursor-pointer" title="Delete Row">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          </button>
        </td>
      `;

      tbody.appendChild(tr);
      const newTa = tr.querySelector('textarea[data-field="topic"]');
      if (newTa) {
        autoResizeTopicTextarea(newTa);
        newTa.focus();
      }
      markPlanDirty(tempId);
      recalculatePlanHours();
    }

    function deleteLessonPlanRow(lpId, btn) {
      if (!confirm('Are you sure you want to delete this lesson plan row?')) return;
      const row = btn.closest('tr');

      if (String(lpId).startsWith('new_') || !lpId || isNaN(lpId)) {
        if (row) row.remove();
        recalculatePlanHours();
        renumberPlanRows();
        return;
      }

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/${lpId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(res => res.json())
      .then(d => {
        if (d.status === 'SUCCESS') {
          if (row) row.remove();
          recalculatePlanHours();
          renumberPlanRows();
        } else {
          alert(d.message || 'Failed to delete row.');
        }
      })
      .catch(err => {
        alert('Error deleting row: ' + err.message);
      });
    }

    function renumberPlanRows() {
      const rows = document.querySelectorAll('#lessonPlanTable tbody tr[data-lp-id]');
      rows.forEach((r, idx) => {
        const dayCell = r.querySelector('[data-field="day_no"]');
        if (dayCell) dayCell.innerText = idx + 1;
      });
    }

    function recalculatePlanHours() {
      const rows = document.querySelectorAll('#lessonPlanTable tbody tr[data-lp-id]');
      let totalHrs = 0;
      let testDays = 0;

      rows.forEach(r => {
        const hrs = parseInt(r.querySelector('[data-field="allocated_hours"]')?.value || 1);
        totalHrs += (isNaN(hrs) ? 1 : hrs);
        const ped = (r.querySelector('[data-field="pedagogy"]')?.value || '').toLowerCase();
        if (ped === 'test') testDays++;
      });

      let lectureDays = rows.length - testDays;
      let proposedVal = window.currentProposedTotalHours || 60;

      const sub = document.getElementById('plannerSubtitle');
      if (sub) {
        sub.innerText = `${lectureDays} lecture days · ${testDays} test days · ${totalHrs} total hours (Syllabus Proposed: ${proposedVal} hours) · Auto-growing content textareas`;
      }
    }

    function syncDatesFromLogData() {
      const btn = document.getElementById('btnSyncDates');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<svg class="w-4 h-4 animate-spin inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10"/></svg> Syncing...`;
      }

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/sync-dates`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
      .then(res => res.json())
      .then(d => {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = `<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg> Sync Dates from Log Data`;
        }
        alert(d.message || 'Synced dates successfully.');
        loadCourseDetails(currentSubjectId).then(() => {
          toggleClassroomTab('planner');
        });
      })
      .catch(err => {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = `<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg> Sync Dates from Log Data`;
        }
        alert('Sync failed: ' + err.message);
      });
    }

    // Track which rows have been edited
    window._dirtyPlanRows = new Set();

    function markPlanDirty(lpId) {
      window._dirtyPlanRows.add(lpId);
      const bar = document.getElementById('planSaveStatusBar');
      if (bar) { bar.classList.remove('hidden'); bar.classList.add('flex'); }
    }

    // Auto-save a single row immediately (for date changes)
    function autoSavePlanRow(lpId, row) {
      if (!row) return;
      const rowData = collectPlanRow(lpId, row);
      if (!rowData) return;
      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/bulk-update`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ rows: [rowData] })
      }).then(r => r.json()).then(d => {
        if (d.status === 'SUCCESS') window._dirtyPlanRows.delete(lpId);
      }).catch(e => console.error('Auto-save failed:', e));
    }

    function collectPlanRow(lpId, row) {
      if (!row) {
        row = document.querySelector(`#lessonPlanTable tr[data-lp-id="${lpId}"]`);
        if (!row) return null;
      }
      const rawId = isNaN(parseInt(lpId)) ? null : parseInt(lpId);
      return {
        id:            rawId,
        day_no:        parseInt(row.querySelector('[data-field="day_no"]')?.innerText || 1),
        co_id:         row.querySelector('[data-field="co_id"]')?.value          || null,
        topic_content: row.querySelector('[data-field="topic"]')?.value          || '',
        proposed_date: row.querySelector('[data-field="proposed_date"]')?.value  || null,
        actual_date:   row.querySelector('[data-field="actual_date"]')?.value    || null,
        allocated_hours: parseInt(row.querySelector('[data-field="allocated_hours"]')?.value || 1),
        pedagogy:      row.querySelector('[data-field="pedagogy"]')?.value        || 'Lecture',
        remarks:       row.querySelector('[data-field="remarks"]')?.value         || '',
      };
    }

    function saveLessonPlanChanges() {
      const btn = document.getElementById('btnSavePlan');
      const rows = [];
      document.querySelectorAll('#lessonPlanTable tbody tr[data-lp-id]').forEach(row => {
        const lpId = row.getAttribute('data-lp-id');
        const data = collectPlanRow(lpId, row);
        if (data) rows.push(data);
      });
      if (rows.length === 0) { alert('Nothing to save.'); return; }
      if (btn) { btn.disabled = true; btn.innerHTML = '<svg class="w-4 h-4 inline-block animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10"/></svg> Saving...'; }

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/bulk-update`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ rows })
      }).then(r => r.json()).then(d => {
        if (d.status === 'SUCCESS') {
          window._dirtyPlanRows.clear();
          const bar = document.getElementById('planSaveStatusBar');
          if (bar) { bar.classList.add('hidden'); bar.classList.remove('flex'); }
          if (btn) btn.innerHTML = '<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg> Saved!';
          setTimeout(() => { if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save Changes'; } }, 2500);

          // If there were temporary newly added rows, reload course details so they get permanent IDs
          const hasNewRows = rows.some(r => r.id === null);
          if (hasNewRows) {
            loadCourseDetails(currentSubjectId).then(() => {
              toggleClassroomTab('planner');
            });
          }
        } else {
          alert(d.message || 'Save failed.');
          if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save Changes'; }
        }
      }).catch(e => {
        alert('Save failed: ' + e.message);
        if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save Changes'; }
      });
    }

    function regenerateLessonPlan() {
      if (!confirm('This will delete the current lesson plan and regenerate it from the stored syllabus data.\n\nAny manually entered dates and remarks will be lost.\n\nContinue?')) return;
      const btn = document.getElementById('btnRegenPlan');
      if (btn) { btn.disabled = true; btn.innerHTML = '<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> Generating...'; }

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/regenerate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({})
      }).then(r => r.json()).then(d => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg> Regenerate'; }
        if (d.status === 'SUCCESS') {
          renderCoursePlanner(d.data);
          toggleClassroomTab('planner');
