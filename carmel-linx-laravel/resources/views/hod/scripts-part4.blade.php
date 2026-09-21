        const tutor = document.getElementById('batchTutorSelect').value;
        const mentor = document.getElementById('batchMentorSelect').value;
        const semester = document.getElementById('batchStartSemesterSelect').value;
        payload.tutor_mobile_no = tutor || null;
        payload.mentor_mobile_no = mentor || null;
        payload.current_semester = parseInt(semester);
      }

      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      const url = (parseInt(year) === 2026) ? '/api/r26/hod/batches' : '/api/hod/batches';
      fetch(url, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
          setTimeout(() => {
            closeCreateBatchModal();
            loadBatches();
          }, 1800);
        } else {
          alertEl.className = 'p-3 rounded-xl text-sm font-semibold bg-rose-50 text-rose-800 border border-rose-200 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-3 rounded-xl text-sm font-semibold bg-rose-50 text-rose-800 border border-rose-200 block';
        alertEl.innerText = 'Request failed.';
        alertEl.classList.remove('hidden');
      });
    }

    function openBatchDetail(batch) {
      activeBatchId = batch.classroom_id;
      switchBatchTab('tutorMentor'); // Reset to default tab

      document.getElementById('batchDetailTitle').innerText = `Batch ${batch.classroom_id}`;
      document.getElementById('batchDetailSubtitle').innerText = `Admission ${batch.batch_year} · ${batch.batch_year} - ${batch.batch_year + 3} Batch`;

      // Show current tutor/mentor
      document.getElementById('tutorCurrentDisplay').innerHTML = batch.tutor_name
        ? `<span class="font-bold text-sky-700">${batch.tutor_name}</span> <span class="text-slate-600 text-sm">(${batch.tutor_mobile_no})</span>`
        : '<span class="italic text-slate-500">Not assigned yet</span>';

      document.getElementById('mentorCurrentDisplay').innerHTML = batch.mentor_name
        ? `<span class="font-bold text-emerald-700">${batch.mentor_name}</span> <span class="text-slate-600 text-sm">(${batch.mentor_mobile_no})</span>`
        : '<span class="italic text-slate-500">Not assigned yet</span>';

      // Clear alerts
      document.getElementById('assignTutorAlert').classList.add('hidden');
      document.getElementById('assignMentorAlert').classList.add('hidden');

      // Populate dropdowns
      populateStaffDropdowns();

      // Pre-select current tutor/mentor
      if (batch.tutor_mobile_no) document.getElementById('detailTutorSelect').value = batch.tutor_mobile_no;
      if (batch.mentor_mobile_no) document.getElementById('detailMentorSelect').value = batch.mentor_mobile_no;

      // Show Graduate button ONLY for S6 batches (final semester)
      const graduateBtn = document.getElementById('btnGraduateBatch');
      if (graduateBtn) {
        if ((batch.current_semester || 1) === 6) {
          graduateBtn.classList.remove('hidden');
        } else {
          graduateBtn.classList.add('hidden');
        }
      }

      // Always show Delete Batch button for HOD
      const deleteBtn = document.getElementById('btnDeleteBatch');
      if (deleteBtn) deleteBtn.classList.remove('hidden');

      const modal = document.getElementById('batchDetailModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeBatchDetailModal() {
      const modal = document.getElementById('batchDetailModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      activeBatchId = null;
      // Hide graduate & delete buttons on close
      const graduateBtn = document.getElementById('btnGraduateBatch');
      if (graduateBtn) graduateBtn.classList.add('hidden');
      const deleteBtn = document.getElementById('btnDeleteBatch');
      if (deleteBtn) deleteBtn.classList.add('hidden');
    }

    // ============================================================
    // NEW: Graduate / Archive Batch — purely additive
    // ============================================================
    function confirmGraduateBatch() {
      if (!activeBatchId) return;
      const title = document.getElementById('batchDetailTitle').innerText;
      const confirmed = confirm(
        `Graduate / Archive Batch: ${title}\n\n` +
        `This will:\n` +
        `  • Set the batch status to Graduated (moves to Previous Batches)\n` +
        `  • Mark all Active students as Graduated\n\n` +
        `All historical data (attendance, marks, subjects) will remain accessible\n` +
        `in the Semester History tab.\n\n` +
        `Proceed?`
      );
      if (confirmed) doGraduateBatch();
    }

    function doGraduateBatch() {
      if (!activeBatchId) return;
      const btn = document.getElementById('btnGraduateBatch');
      if (btn) { btn.disabled = true; btn.innerText = 'Archiving...'; }

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/graduate`, {
        method: 'PUT',
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">school</span> Graduate / Archive Batch'; }
        if (data.status === 'SUCCESS') {
          showGlobalMessage(`Batch graduated successfully. ${data.students_graduated} student(s) marked as Graduated.`);
          closeBatchDetailModal();
          loadBatches('historical'); // switch to Previous Batches so HOD sees the card there
        } else {
          alert(data.message || 'Failed to graduate batch.');
        }
      })
      .catch(() => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">school</span> Graduate / Archive Batch'; }
        alert('Request failed. Please try again.');
      });
    }
    // ============================================================
    // END: Graduate / Archive Batch
    // ============================================================

    // ============================================================
    // DELETE BATCH
    // ============================================================
    function confirmDeleteBatch() {
      if (!activeBatchId) return;
      const title = document.getElementById('batchDetailTitle').innerText;
      const confirmed = confirm(
        `⚠️ DELETE BATCH: ${title}\n\n` +
        `This will PERMANENTLY delete:\n` +
        `  • The batch record\n` +
        `  • All allocated subjects\n` +
        `  • All staff assignments for this batch\n\n` +
        `NOTE: Batches with enrolled students CANNOT be deleted.\n\n` +
        `This action CANNOT be undone. Proceed?`
      );
      if (confirmed) doDeleteBatch();
    }

    function doDeleteBatch() {
      if (!activeBatchId) return;
      const btn = document.getElementById('btnDeleteBatch');
      if (btn) { btn.disabled = true; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">hourglass_empty</span> Deleting...'; }

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}`, {
        method: 'DELETE',
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">delete_forever</span> Delete Batch'; }
        if (data.status === 'SUCCESS') {
          showGlobalMessage(data.message || 'Batch deleted successfully.');
          closeBatchDetailModal();
          loadBatches();
        } else {
          alert(data.message || 'Failed to delete batch.');
        }
      })
      .catch(() => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">delete_forever</span> Delete Batch'; }
        alert('Request failed. Please try again.');
      });
    }
    // ============================================================
    // END: Delete Batch
    // ============================================================

    // ============================================================
    // NEW: SEMESTER HISTORY TAB — purely additive, no existing functions modified
    // ============================================================

    function _ensureSemesterHistoryPanel() {
      const flexContainer = document.querySelector('#batchDetailModal .flex-grow.overflow-y-auto');
      if (!flexContainer) return;
      let panel = document.getElementById('batchTab_semesterHistory');
      if (!panel) {
        panel = document.createElement('div');
        panel.id = 'batchTab_semesterHistory';
        panel.className = 'space-y-5';
        panel.innerHTML = `
          <!-- Semester Selector -->
          <div class="flex items-center gap-2 flex-wrap">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-1">Select Semester:</span>
            ${[1,2,3,4,5,6].map(s => `
              <button id="semHistBtn_${s}" onclick="loadSemesterSnapshot(activeBatchId, ${s})"
                class="px-3.5 py-1.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:border-blue-600 hover:text-blue-600 transition-colors cursor-pointer bg-white shadow-2xs">
                Semester ${s}
              </button>
            `).join('')}
          </div>

          <!-- Content area -->
          <div id="semHistContent">
            <div class="p-10 text-center text-slate-500 text-sm">Select a semester above to view its academic data.</div>
          </div>
        `;
        flexContainer.appendChild(panel);
      }
      panel.classList.remove('hidden');
    }

    function loadSemesterSnapshot(classroomId, semester) {
      if (!classroomId) return;

      // Highlight active semester button
      for (let s = 1; s <= 6; s++) {
        const btn = document.getElementById('semHistBtn_' + s);
        if (btn) {
          btn.className = s === semester
            ? 'px-3.5 py-1.5 rounded-xl text-sm font-semibold border border-blue-600 text-blue-600 transition-colors cursor-pointer bg-blue-50/60 shadow-2xs'
            : 'px-3.5 py-1.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:border-blue-600 hover:text-blue-600 transition-colors cursor-pointer bg-white shadow-2xs';
        }
      }

      const content = document.getElementById('semHistContent');
      content.innerHTML = `<div class="p-10 text-center text-slate-500 text-sm flex items-center justify-center gap-3"><div class="w-4 h-4 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin"></div> Loading Semester ${semester} data...</div>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/semester/${semester}/snapshot`, {
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if (data.status !== 'SUCCESS') {
          content.innerHTML = `<div class="p-8 text-center text-rose-600 font-semibold text-sm">${data.message || 'Failed to load semester data.'}</div>`;
          return;
        }
        _renderSemesterSnapshot(data, semester);
      })
      .catch(() => {
        content.innerHTML = `<div class="p-8 text-center text-rose-600 font-semibold text-sm">Error fetching semester data.</div>`;
      });
    }

    function _renderSemesterSnapshot(data, semester) {
      const content = document.getElementById('semHistContent');

      // ---- Section 1: Subjects & Staff Log ----
      let subjectsHtml = '';
      if (data.subjects && data.subjects.length > 0) {
        const rows = data.subjects.map(s => `
          <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">
            <td class="p-3 font-mono text-slate-900 font-bold text-sm">${s.subject_code}</td>
            <td class="p-3 font-semibold text-slate-900 text-sm">${s.subject_name}</td>
            <td class="p-3 text-slate-600 text-sm">${s.subject_type}</td>
            <td class="p-3 text-sm">${s.staff.length > 0 ? s.staff.map(n => `<span class="block text-slate-900 font-medium">${n}</span>`).join('') : '<span class="text-rose-600 font-semibold">Unassigned</span>'}</td>
            <td class="p-3 text-center text-sm font-bold text-blue-600">${s.classes_conducted}</td>
            <td class="p-3 text-center text-sm ${s.course_file_status === 'Submitted' ? 'text-emerald-600' : 'text-amber-600'} font-semibold">${s.course_file_status}</td>
          </tr>
        `).join('');
        subjectsHtml = `
          <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs mb-4">
            <div class="p-3.5 bg-slate-50 border-b border-slate-200/80 flex items-center gap-2">
              <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
              <span class="font-bold text-slate-900 text-sm">Subjects &amp; Staff Log — Semester ${semester}</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead><tr class="bg-slate-50/90 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-3">Code</th><th class="p-3">Subject</th><th class="p-3">Type</th>
                  <th class="p-3">Assigned Staff</th><th class="p-3 text-center">Classes Taken</th><th class="p-3 text-center">Course File</th>
                </tr></thead>
                <tbody>${rows}</tbody>
              </table>
            </div>
          </div>`;
      } else {
        subjectsHtml = `<div class="p-6 bg-slate-50 border border-slate-200/80 rounded-2xl text-slate-500 text-sm italic mb-4">No subjects found for Semester ${semester}.</div>`;
      }

      // ---- Section 2: Student Attendance ----
      let attendanceHtml = '';
      if (data.students && data.students.length > 0) {
        const rows = data.students.map(s => {
          const pct = s.overall_attendance_percent ?? '—';
          const pctClass = pct === '—' ? 'text-slate-500' : (pct >= 75 ? 'text-emerald-600' : (pct >= 60 ? 'text-amber-600' : 'text-rose-600'));
          const bySubj = s.subject_attendance && s.subject_attendance.length > 0
            ? s.subject_attendance.map(a => `<span class="text-xs text-slate-600">${a.subject_code}: <span class="font-bold ${a.percent >= 75 ? 'text-emerald-600' : a.percent >= 60 ? 'text-amber-600' : 'text-rose-600'}">${a.percent}%</span></span>`).join(' &nbsp;|&nbsp; ')
            : '<span class="text-slate-400 text-xs">No logs</span>';
          return `
            <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">
              <td class="p-3 text-slate-600 text-sm font-mono">${s.roll_no || '—'}</td>
              <td class="p-3 font-semibold text-slate-900 text-sm">${s.name}</td>
              <td class="p-3 text-center font-bold text-sm ${pctClass}">${pct !== '—' ? pct + '%' : '—'}</td>
              <td class="p-3 text-sm">${bySubj}</td>
              <td class="p-3 text-center"><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${
                s.academic_status === 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600'
              }">${s.academic_status}</span></td>
            </tr>`;
        }).join('');
        attendanceHtml = `
          <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs mb-4">
            <div class="p-3.5 bg-slate-50 border-b border-slate-200/80 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
                <span class="font-bold text-slate-900 text-sm">Student Attendance — Semester ${semester}</span>
              </div>
              <span class="text-xs text-slate-500 font-medium">${data.students.length} students</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead><tr class="bg-slate-50/90 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-3">Roll No</th><th class="p-3">Name</th>
                  <th class="p-3 text-center">Overall %</th><th class="p-3">Subject-wise</th><th class="p-3 text-center">Status</th>
                </tr></thead>
                <tbody>${rows}</tbody>
              </table>
            </div>
          </div>`;
      } else {
        attendanceHtml = `<div class="p-6 bg-slate-50 border border-slate-200/80 rounded-2xl text-slate-500 text-sm italic mb-4">No student data found for Semester ${semester}.</div>`;
      }

      // ---- Section 3: Board Results / Marks ----
      let marksHtml = '';
      if (data.board_results && data.board_results.length > 0) {
        const rows = data.board_results.map(s => `
          <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">
            <td class="p-3 text-slate-600 text-sm font-mono">${s.roll_no || '—'}</td>
            <td class="p-3 font-semibold text-slate-900 text-sm">${s.name}</td>
            <td class="p-3 text-center font-semibold text-sm ${s.result === 'Pass' ? 'text-emerald-600' : s.result === 'Fail' ? 'text-rose-600' : 'text-slate-500'}">${s.result || '—'}</td>
            <td class="p-3 text-center font-bold text-sm text-amber-600">${s.sgpa || '—'}</td>
            <td class="p-3 text-center text-slate-700 text-sm">${s.board_marks || '—'}</td>
          </tr>
        `).join('');
        marksHtml = `
          <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="p-3.5 bg-slate-50 border-b border-slate-200/80 flex items-center gap-2">
              <i data-lucide="award" class="w-4 h-4 text-amber-600"></i>
              <span class="font-bold text-slate-900 text-sm">Board Results — Semester ${semester}</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead><tr class="bg-slate-50/90 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-3">Roll No</th><th class="p-3">Name</th>
                  <th class="p-3 text-center">Result</th><th class="p-3 text-center">SGPA</th><th class="p-3 text-center">Board Marks</th>
                </tr></thead>
                <tbody>${rows}</tbody>
              </table>
            </div>
          </div>`;
      } else {
        marksHtml = `<div class="p-6 bg-slate-50 border border-slate-200/80 rounded-2xl text-slate-500 text-sm italic">Board results not yet entered for Semester ${semester}.</div>`;
      }

      content.innerHTML = subjectsHtml + attendanceHtml + marksHtml;
      if (window.initLucide) window.initLucide();
    }

    function switchBatchTab(tab) {
      const tabs = ['tutorMentor', 'timetable', 'semesterHistory'];
      tabs.forEach(t => {
        const el = document.getElementById('batchTab_' + t);
        const btn = document.getElementById('tabBtn_' + t);
        if (el) {
          el.classList.add('hidden');
          el.classList.remove('block');
        }
        if (btn) {
          btn.className = "pb-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-colors cursor-pointer whitespace-nowrap";
        }
      });

      const targetBtn = document.getElementById('tabBtn_' + tab);
      if (targetBtn) {
        targetBtn.className = "pb-3 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 transition-colors cursor-pointer whitespace-nowrap";
      }

      if (tab === 'semesterHistory') {
        _ensureSemesterHistoryPanel();
      } else {
        const semPanel = document.getElementById('batchTab_semesterHistory');
        if (semPanel) semPanel.classList.add('hidden');
        const targetEl = document.getElementById('batchTab_' + tab);
        if (targetEl) {
          targetEl.classList.remove('hidden');
          targetEl.classList.add('block');
        }
      }

      if (tab === 'timetable') {
        loadTimetable();
      }
    }


    let currentTimetableData = {};
    let currentAllocatedSubjects = [];

    function loadTimetable() {
      if (!activeBatchId) return;
      
      const sem = document.getElementById('modalSubjectSemester') ? document.getElementById('modalSubjectSemester').value : 1;
      
      const displayBody = document.getElementById('timetableDisplayBody');
      if (displayBody) displayBody.innerHTML = '<tr><td colspan="8" class="p-8 text-center text-slate-500">Loading timetable...</td></tr>';
      
      toggleTimetableEdit(false);

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/subjects?semester=${sem}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentAllocatedSubjects = data.subjects || [];
            return fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/timetable`);
          } else {
            throw new Error(data.message || 'Failed to load batch subjects');
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentTimetableData = data.timetable || {};
            renderTimetable();
          } else {
            throw new Error(data.message || 'Failed to load timetable');
          }
        })
        .catch(err => {
          if (displayBody) displayBody.innerHTML = `<tr><td colspan="8" class="p-8 text-center text-red-400">Error: ${err.message}</td></tr>`;
        });
    }

    function slotsEqual(slotA, slotB) {
