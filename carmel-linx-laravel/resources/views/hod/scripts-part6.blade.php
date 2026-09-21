        });
    }

    // =========================================================================
    // END BATCH MANAGEMENT FUNCTIONS
    // =========================================================================

    // =========================================================================
    // SUBJECT ALLOCATION FUNCTIONS
    // =========================================================================
    let allCollegeStaffCache = [];

    function loadSubjects() {
      const batchSelect = document.getElementById('subjectBatchSelect');
      const semSelect = document.getElementById('subjectSemesterSelect');
      const classroomId = batchSelect.value;
      const semester = semSelect.value;
      
      const tbody = document.getElementById('subjectsTableBody');
      if (!classroomId) {
        tbody.innerHTML = `<tr><td colspan="5" class="p-12 text-center text-slate-500 font-medium text-sm">Select a batch above to view its allocated subjects.</td></tr>`;
        return;
      }

      tbody.innerHTML = `<tr><td colspan="5" class="p-12 text-center text-slate-500 font-medium text-sm"><div class="w-4 h-4 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin mx-auto mb-2"></div>Loading subjects...</td></tr>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/subjects?semester=${semester}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            allCollegeStaffCache = data.all_staff || [];
            tbody.innerHTML = '';
            if (data.subjects.length === 0) {
              tbody.innerHTML = `
                <tr>
                  <td colspan="5" class="p-12 text-center text-slate-500 font-medium text-sm">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                      <i data-lucide="book-open" class="w-5 h-5"></i>
                    </div>
                    <p class="font-semibold text-slate-800">No subjects allocated yet</p>
                    <p class="text-xs text-slate-400 mt-0.5">Click "Add Subject" to map a curriculum subject to this semester.</p>
                  </td>
                </tr>
              `;
              if (window.initLucide) window.initLucide();
              return;
            }

            data.subjects.forEach(subj => {
              let staffList = subj.staff.map(s => `<span class="inline-flex items-center gap-1.5 mr-2 mb-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 text-xs font-semibold border border-slate-200"><i data-lucide="user" class="w-3 h-3 text-slate-500"></i>${s.name} <span class="text-slate-500 font-mono">(${s.branch})</span></span>`).join('');
              if (subj.staff.length === 0) staffList = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">Unassigned</span>`;
              
              const currentStaffIds = subj.staff.map(s => s.mobile_no).join(',');

              const tr = document.createElement('tr');
              tr.className = 'border-b border-slate-100 hover:bg-slate-50/70 transition-colors';
              tr.innerHTML = `
                <td class="p-4 font-mono text-slate-900 font-bold text-sm whitespace-nowrap">${subj.subject_code}</td>
                <td class="p-4 font-semibold text-slate-900 text-sm">${subj.subject_name}</td>
                <td class="p-4 text-sm"><span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200">${subj.subject_type}</span></td>
                <td class="p-4">${staffList}</td>
                <td class="p-4 text-right space-x-1.5 text-sm whitespace-nowrap">
                  <button onclick="openEditSubjectModal(${JSON.stringify(subj).replace(/"/g, '&quot;')})" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                    Edit
                  </button>
                  <button onclick="openAssignStaffModal(this, ${subj.id}, '${currentStaffIds}')" data-subject-name="${subj.subject_name.replace(/"/g, '&quot;')}" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                    Assign Staff
                  </button>
                  <button onclick="deleteSubject(${subj.id})" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                    Delete
                  </button>
                </td>
              `;
              tbody.appendChild(tr);
            });
            if (window.initLucide) window.initLucide();
          } else {
            tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-rose-600 font-semibold text-sm">Failed to load subjects.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-rose-600 font-semibold text-sm">Error fetching subjects.</td></tr>`;
        });
    }

    // ---- Branch prefix helpers ----
    function _getBranchPrefix() {
      const modalBatch = document.getElementById('modalFormSubjectBatch');
      const displayBatch = document.getElementById('displaySubjectBatch');
      const batchSelect = document.getElementById('subjectBatchSelect');
      
      let val = '';
      if (modalBatch && modalBatch.value) {
        val = modalBatch.value;
      } else if (displayBatch && displayBatch.innerText) {
        val = displayBatch.innerText;
      } else if (batchSelect && batchSelect.value) {
        val = batchSelect.value;
      }
      
      if (!val) return '';
      // classroom_id format: EL_2026_2029 or EL
      return (val.split('_')[0] || '').toUpperCase();
    }

    function _applyCodePrefix(isRev2026) {
      const prefixEl  = document.getElementById('subjectCodePrefix');
      const rawInput  = document.getElementById('subjectCodeRaw');
      const hiddenEl  = document.getElementById('subjectCode');
      if (!prefixEl || !rawInput || !hiddenEl) return;

      if (isRev2026) {
        const prefix = _getBranchPrefix();
        prefixEl.innerText = prefix + '-';
        prefixEl.classList.remove('hidden');
        prefixEl.classList.add('flex');
        rawInput.placeholder = 'e.g. 1008';
        // Sync hidden field
        const raw = rawInput.value.trim();
        hiddenEl.value = raw ? (prefix + '-' + raw) : '';
      } else {
        prefixEl.classList.add('hidden');
        prefixEl.classList.remove('flex');
        rawInput.placeholder = 'e.g. ENG101';
        hiddenEl.value = rawInput.value.trim();
      }
    }

    // Keep hidden field in sync whenever the user types
    document.addEventListener('DOMContentLoaded', function() {
      const rawInput = document.getElementById('subjectCodeRaw');
      if (rawInput) {
        rawInput.addEventListener('input', function() {
          const isRev2026 = (document.getElementById('subjectRevisionYear') || {}).value === 'REV2026';
          _applyCodePrefix(isRev2026);
        });
      }
      // Also re-sync when revision changes
      const revEl = document.getElementById('subjectRevisionYear');
      if (revEl) {
        revEl.addEventListener('change', function() {
          _applyCodePrefix(this.value === 'REV2026');
        });
      }
    });
    // ---- End branch prefix helpers ----

    function openSubjectModal() {
      try {
        const batchSelect = document.getElementById('subjectBatchSelect');
        const semSelect = document.getElementById('subjectSemesterSelect');
        if (!batchSelect || !batchSelect.value) {
          alert("Please select a target batch first.");
          return;
        }
        
        const formEl = document.getElementById('subjectForm');
        if (formEl) formEl.reset();

        // Reset raw code input & hidden field
        const rawInput = document.getElementById('subjectCodeRaw');
        if (rawInput) rawInput.value = '';
        const hiddenCode = document.getElementById('subjectCode');
        if (hiddenCode) hiddenCode.value = '';
        
        const alertEl = document.getElementById('subjectAlert');
        if (alertEl) alertEl.classList.add('hidden');

        const modalBatch = document.getElementById('modalFormSubjectBatch');
        if (modalBatch) modalBatch.value = batchSelect.value;
        
        const displayBatch = document.getElementById('displaySubjectBatch');
        if (displayBatch) displayBatch.innerText = batchSelect.value;
        
        const modalSem = document.getElementById('modalFormSubjectSemester');
        if (modalSem) modalSem.value = semSelect.value;
        
        const displaySem = document.getElementById('displaySubjectSemester');
        if (displaySem && semSelect) {
          displaySem.innerText = semSelect.options[semSelect.selectedIndex].text;
        }
        
        const modal = document.getElementById('subjectModal');
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        }

        const revisionSelect = document.getElementById('subjectRevisionYear');
        if (revisionSelect) {
          if (batchSelect.value.includes('2026') || batchSelect.value.includes('REV2026')) {
            revisionSelect.value = 'REV2026';
          } else {
            revisionSelect.value = 'REV2021';
          }
          syncSubjectTypeOptions(revisionSelect.value);
          _applyCodePrefix(revisionSelect.value === 'REV2026');
        }
      } catch (err) {
        alert("Error opening subject modal: " + err.message);
        console.error('[openSubjectModal] Error:', err);
      }
    }

    function closeSubjectModal() {
      const modal = document.getElementById('subjectModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
      // Reset to Add mode so next open starts fresh
      const editIdEl = document.getElementById('modalEditSubjectId');
      if (editIdEl) editIdEl.value = '';
      const iconEl = document.getElementById('subjectModalIcon');
      if (iconEl) { iconEl.innerText = 'add_box'; iconEl.className = 'material-symbols-rounded text-emerald-400 text-xs'; }
      const titleEl = document.getElementById('subjectModalTitleText');
      if (titleEl) titleEl.innerText = 'Add Curriculum Subject';
      const labelEl = document.getElementById('subjectSubmitLabel');
      if (labelEl) labelEl.innerText = 'Add Subject';
      const btnEl = document.getElementById('subjectSubmitBtn');
      if (btnEl) btnEl.className = 'flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5';
    }

    /**
     * Opens the subject modal in EDIT mode, pre-filling existing values.
     * @param {object|string} subjData - The subject object (or JSON string) from the table row.
     */
    function openEditSubjectModal(subjData) {
      try {
        const subj = (typeof subjData === 'string') ? JSON.parse(subjData) : subjData;

        // Switch modal UI to Edit mode
        document.getElementById('subjectModalIcon').innerText = 'edit';
        document.getElementById('subjectModalIcon').className = 'material-symbols-rounded text-amber-400 text-xs';
        document.getElementById('subjectModalTitleText').innerText = 'Edit Subject Details';
        document.getElementById('subjectSubmitLabel').innerText = 'Save Changes';
        document.getElementById('subjectSubmitBtn').className = 'flex-1 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5';

        // Store the subject ID
        document.getElementById('modalEditSubjectId').value = subj.id;

        // Set batch/semester context immediately (so prefix helper can read it)
        const modalBatch = document.getElementById('modalFormSubjectBatch');
        if (modalBatch) modalBatch.value = subj.classroom_id || '';
        const modalSem = document.getElementById('modalFormSubjectSemester');
        if (modalSem) modalSem.value = subj.semester || '';

        const displayBatch = document.getElementById('displaySubjectBatch');
        if (displayBatch) displayBatch.innerText = subj.classroom_id || '';
        const displaySem = document.getElementById('displaySubjectSemester');
        if (displaySem) displaySem.innerText = subj.semester ? 'S' + subj.semester : '';

        // Pre-fill fields
        const revEl = document.getElementById('subjectRevisionYear');
        if (revEl && subj.syllabus_revision_code) {
          revEl.value = subj.syllabus_revision_code;
        }
        
        const isRev2026Edit = (revEl ? revEl.value : '') === 'REV2026';
        const rawInput = document.getElementById('subjectCodeRaw');
        const hiddenCode = document.getElementById('subjectCode');
        
        if (isRev2026Edit) {
          // Extract the prefix and code (e.g. "EL-1008" -> "1008")
          const storedCode = subj.subject_code || '';
          const dashIndex = storedCode.indexOf('-');
          if (rawInput) {
            rawInput.value = dashIndex !== -1 ? storedCode.substring(dashIndex + 1) : storedCode;
          }
          if (hiddenCode) {
            hiddenCode.value = storedCode;
          }
        } else {
          if (rawInput) {
            rawInput.value = subj.subject_code || '';
          }
          if (hiddenCode) {
            hiddenCode.value = subj.subject_code || '';
          }
        }
        
        // Sync badge UI prefix display (reads displayBatch or modalBatch now)
        _applyCodePrefix(isRev2026Edit);
        
        document.getElementById('subjectName').value = subj.subject_name || '';
        syncSubjectTypeOptions(revEl ? revEl.value : 'REV2021', subj.subject_type || 'Theory');

        // Clear any previous alert
        const alertEl = document.getElementById('subjectAlert');
        if (alertEl) alertEl.classList.add('hidden');

        const modal = document.getElementById('subjectModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      } catch (err) {
        alert('Error opening edit modal: ' + err.message);
        console.error('[openEditSubjectModal]', err);
      }
    }

    function saveSubject(e) {
      e.preventDefault();
      
      // Ensure prefix gets synchronized from the fields right before submit
      const isRev2026 = (document.getElementById('subjectRevisionYear') || {}).value === 'REV2026';
      _applyCodePrefix(isRev2026);

      const editId = document.getElementById('modalEditSubjectId').value;
      if (editId) {
        // EDIT mode
        _doUpdateSubject(editId);
      } else {
        // ADD mode
        _doCreateSubject();
      }
    }

    function _doCreateSubject() {
      const spinner = document.getElementById('subjectSpinner');
      const alertEl = document.getElementById('subjectAlert');
      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      const payload = {
        classroom_id: document.getElementById('modalFormSubjectBatch').value,
        semester: document.getElementById('modalFormSubjectSemester').value,
        subject_code: document.getElementById('subjectCode').value,
        subject_name: document.getElementById('subjectName').value,
        subject_type: document.getElementById('subjectType').value,
        syllabus_revision_code: document.getElementById('subjectRevisionYear').value
      };

      fetch('/api/hod/batches/subjects/create', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          closeSubjectModal();
          loadSubjects();
          loadModalSubjects();
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
          alertEl.innerText = data.message;
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
        alertEl.innerText = 'Request failed.';
      });
    }

    function _doUpdateSubject(subjectId) {
      const spinner = document.getElementById('subjectSpinner');
      const alertEl = document.getElementById('subjectAlert');
      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      const payload = {
        subject_code: document.getElementById('subjectCode').value,
        subject_name: document.getElementById('subjectName').value,
        subject_type: document.getElementById('subjectType').value,
        syllabus_revision_code: document.getElementById('subjectRevisionYear').value
      };

      fetch(`/api/hod/batches/subjects/${subjectId}`, {
        method: 'PUT',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          closeSubjectModal();
          loadSubjects();
          loadModalSubjects();
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
          alertEl.innerText = data.message;
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
        alertEl.innerText = 'Request failed.';
      });
    }

    function deleteSubject(subjectId) {
      if(!confirm("Are you sure you want to delete this subject? This will also remove any staff assignments for it.")) return;
      
      fetch(`/api/hod/batches/subjects/${subjectId}`, {
        method: 'DELETE',
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          loadSubjects();
          if (typeof loadModalSubjects === 'function') loadModalSubjects();
        }
        else alert(data.message);
      })
      .catch(() => alert('Failed to delete subject.'));
    }

    let currentAssignStaffIds = [];

    function openAssignStaffModal(btn, subjectId, currentStaffIds) {
      try {
        console.log('[openAssignStaffModal] subjectId:', subjectId, 'currentStaffIds:', currentStaffIds);
        const subjectName = btn.getAttribute('data-subject-name');
        
        const idEl = document.getElementById('assignSubjectId');
        if (idEl) idEl.value = subjectId;
        
        const nameEl = document.getElementById('assignSubjectName');
        if (nameEl) nameEl.innerText = subjectName;
        
        const filterEl = document.getElementById('staffBranchFilter');
        if (filterEl) {
          filterEl.value = window.branchOverride || "{{ session('userBranch') }}" || "";
        }
        
        currentAssignStaffIds = currentStaffIds ? currentStaffIds.split(',') : [];
        
        renderAssignStaffList();

        const alertEl = document.getElementById('assignStaffAlert');
        if (alertEl) alertEl.classList.add('hidden');
        
        const modal = document.getElementById('assignStaffModal');
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        }
      } catch (err) {
        alert("Error opening assign staff modal: " + err.message);
        console.error('[openAssignStaffModal] Error:', err);
      }
    }

    function closeAssignStaffModal() {
      const modal = document.getElementById('assignStaffModal');
      modal.classList.add('hidden');
