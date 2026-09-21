                <td class="p-3.5"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">${log.action}</span></td>
                <td class="p-3.5 font-mono text-slate-500 text-xs">${log.ip_address || '—'}</td>
                <td class="p-3.5 text-slate-600 text-sm leading-relaxed">${log.details || '—'}</td>
              `;
              tbody.appendChild(tr);
            });
            if (window.initLucide) window.initLucide();
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-600 font-semibold text-sm">Error loading audit logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-600 font-semibold text-sm">Request failed.</td></tr>`;
        });
    }

    function viewUserAudit(userId, userName) {
      document.getElementById('auditProfileName').innerText = userName;
      document.getElementById('auditProfileId').innerText = userId;
      
      const tbody = document.getElementById('modalAuditTableBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-500">Retrieving profile logs...</td></tr>`;

      const modal = document.getElementById('auditModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      fetch(`/api/audit-logs?targetId=${userId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-500">No profile history events found.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-sm";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3 font-semibold text-slate-300">${log.performed_by_name || 'System'}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-sm font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Error loading.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Failed.</td></tr>`;
        });
    }

    function closeAuditModal() {
      const modal = document.getElementById('auditModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function confirmDeleteUser(userId, userType, userName) {
      if (confirm(`Are you absolutely sure you want to permanently delete the profile of ${userName} (${userId})? This action will remove all database credentials.`)) {
        const indicator = document.getElementById('loadingIndicator');
        indicator.classList.remove('hidden');

        fetch('/api/admin/user/delete', {
          method: 'POST',
          headers: getHeaders(),
          body: JSON.stringify({ targetId: userId, userType })
        })
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            showGlobalMessage('Profile deleted successfully.');
            loadUsers();
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to delete profile.', true);
        });
      }
    }

    function openRegisterModal() {
      document.getElementById('directRegisterForm').reset();
      document.getElementById('directRegAlert').classList.add('hidden');
      toggleDirectRegisterFields('student');
      
      const modal = document.getElementById('registerModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeRegisterModal() {
      const modal = document.getElementById('registerModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function toggleDirectRegisterFields(type) {
      const sFields = document.getElementById('directStudentFields');
      const fFields = document.getElementById('directStaffFields');
      if (type === 'student') {
        sFields.classList.remove('hidden');
        fFields.classList.add('hidden');
      } else {
        fFields.classList.remove('hidden');
        sFields.classList.add('hidden');
      }
    }

    function handleAdmTypeChange() {
      const admType = document.getElementById('directRegAdmType').value;
      const regNoInput = document.getElementById('directRegStudentId');
      if (admType === 'LET') {
        if (!regNoInput.value.startsWith('L')) {
          regNoInput.value = 'L' + regNoInput.value;
        }
        document.getElementById('directRegStudentSem').value = 'S3';
      } else {
        if (regNoInput.value.startsWith('L')) {
          regNoInput.value = regNoInput.value.substring(1);
        }
        document.getElementById('directRegStudentSem').value = 'S1';
      }
    }

    function handleDirectRegister(e) {
      e.preventDefault();
      const alert = document.getElementById('directRegAlert');
      const spinner = document.getElementById('directRegSpinner');
      
      alert.classList.add('hidden');
      spinner.classList.remove('hidden');

      const type = document.getElementById('regType').value;
      const formData = new FormData();
      formData.append('name', document.getElementById('directRegName').value);
      formData.append('email', document.getElementById('directRegEmail').value);
      formData.append('password', document.getElementById('directRegPassword').value);

      let url = '/register/student';
      if (type === 'student') {
        formData.append('regNo', document.getElementById('directRegStudentId').value);
        formData.append('admNo', document.getElementById('directRegStudentAdm').value);
        formData.append('branch', document.getElementById('directRegStudentBranch').value);
        formData.append('admissionYear', document.getElementById('directRegStudentYear').value);
        formData.append('admissionType', document.getElementById('directRegAdmType').value);
      } else {
        url = '/register/staff';
        formData.append('mobileNo', document.getElementById('directRegStaffMobile').value);
        formData.append('branch', document.getElementById('directRegStaffBranch').value);
        formData.append('designation', document.getElementById('directRegStaffDesig').value);
      }

      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl text-sm font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 block";
          alert.innerText = "User registered successfully.";
          alert.classList.remove('hidden');
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
          }, 1500);
        } else {
          alert.className = "p-3 rounded-xl text-sm font-semibold bg-rose-50 text-rose-800 border border-rose-200 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl text-sm font-semibold bg-rose-50 text-rose-800 border border-rose-200 block";
        alert.innerText = "Request failed.";
        alert.classList.remove('hidden');
      });
    }

    // =========================================================================
    // BATCH MANAGEMENT FUNCTIONS
    // =========================================================================

    function loadDeptStaffCache() {
      fetch('/api/hod/dept-staff')
        .then(r => r.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            deptStaffCache = data.staff;
          }
        })
        .catch(() => {});
    }

    function populateStaffDropdowns() {
      const selectors = ['#batchTutorSelect', '#batchMentorSelect', '#detailTutorSelect', '#detailMentorSelect'];
      selectors.forEach(sel => {
        const el = document.querySelector(sel);
        if (!el) return;
        const firstOpt = el.options[0];
        el.innerHTML = '';
        el.appendChild(firstOpt.cloneNode(true));
        deptStaffCache.forEach(s => {
          const opt = document.createElement('option');
          opt.value = s.mobile_no;
          opt.textContent = `${s.name} (${s.designation.replace(/_/g,' ')})`;
          el.appendChild(opt);
        });
      });
    }

    function showBatchMessage(msg, isError = false) {
      showGlobalMessage(msg, isError);
    }

    let currentBatchFilter = 'active';

    function loadBatches(status = 'active') {
      currentBatchFilter = status;
      const grid = document.getElementById('batchCardsGrid');
      const empty = document.getElementById('batchEmptyState');
      grid.innerHTML = `
        <div class="col-span-full flex items-center justify-center py-16 text-sm">
          <div class="flex items-center gap-3 text-slate-600 text-sm font-medium bg-white px-5 py-3 rounded-2xl border border-slate-200 shadow-xs">
            <div class="w-4 h-4 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin"></div>
            <span>Loading department batches...</span>
          </div>
        </div>
      `;
      empty.classList.add('hidden');

      // Update toggle UI with Design System pill styling
      const btnActive = document.getElementById('btnHodFilterActive');
      const btnHist = document.getElementById('btnHodFilterHistorical');
      if (status === 'active') {
        if (btnActive) btnActive.className = 'px-3.5 py-1.5 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-xs border border-slate-200/60 cursor-pointer';
        if (btnHist) btnHist.className = 'px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-600 hover:text-slate-900 cursor-pointer';
      } else {
        if (btnHist) btnHist.className = 'px-3.5 py-1.5 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-xs border border-slate-200/60 cursor-pointer';
        if (btnActive) btnActive.className = 'px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-600 hover:text-slate-900 cursor-pointer';
      }

      const p1 = fetch(`/api/hod/batches?status=${status}`).then(r => r.json()).catch(() => ({status: 'ERROR', batches: []}));
      const p2 = fetch(`/api/r26/hod/batches?status=${status}`).then(r => r.json()).catch(() => ({status: 'ERROR', batches: []}));

      Promise.all([p1, p2])
        .then(([res1, res2]) => {
          grid.innerHTML = '';
          let b1 = (res1.status === 'SUCCESS' && Array.isArray(res1.batches)) ? res1.batches : [];
          let b2 = (res2.status === 'SUCCESS' && Array.isArray(res2.batches)) ? res2.batches : [];
          
          let combined = b1.concat(b2);
          
          // sort by batch_year desc, then classroom_id asc
          combined.sort((x, y) => {
            if (y.batch_year !== x.batch_year) {
              return y.batch_year - x.batch_year;
            }
            return x.classroom_id.localeCompare(y.classroom_id);
          });

          if (combined.length === 0) {
            empty.classList.remove('hidden');
            return;
          }
          combined.forEach(batch => renderBatchCard(batch));
          if (window.initLucide) window.initLucide();
        })
        .catch(() => {
          grid.innerHTML = `<div class="col-span-full p-8 text-center text-rose-600 font-semibold text-sm bg-white rounded-2xl border border-rose-100 shadow-xs">Failed to load department batches.</div>`;
        });
    }

    function renderBatchCard(batch) {
      const grid = document.getElementById('batchCardsGrid');
      
      const isLetBatch = batch.classroom_id.includes('_LET');
      const isR26 = batch.is_r26 || batch.batch_year === 2026;

      const card = document.createElement('div');
      card.className = isR26
        ? `bg-white border-2 border-emerald-500/80 rounded-2xl p-6 transition-all hover:shadow-md flex flex-col justify-between min-h-[220px] w-full relative shadow-xs`
        : `bg-white border border-slate-200/90 rounded-2xl p-6 transition-all hover:shadow-md hover:border-slate-300 flex flex-col justify-between min-h-[220px] w-full relative shadow-xs`;

      const tutorHtml = batch.tutor_name
        ? `<div class="flex items-center gap-2.5 text-sm"><div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0"><i data-lucide="user-check" class="w-4 h-4 text-blue-600"></i></div><div class="min-w-0"><span class="text-xs text-slate-400 block font-medium">Class Tutor</span><span class="text-slate-900 font-semibold truncate block text-sm" title="${batch.tutor_name}">${batch.tutor_name}</span></div></div>`
        : `<div class="flex items-center gap-2.5 text-sm"><div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0"><i data-lucide="user-x" class="w-4 h-4 text-slate-400"></i></div><div><span class="text-xs text-slate-400 block font-medium">Class Tutor</span><span class="text-slate-400 italic text-sm">Not assigned</span></div></div>`;

      const mentorHtml = batch.mentor_name
        ? `<div class="flex items-center gap-2.5 text-sm"><div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0"><i data-lucide="heart-handshake" class="w-4 h-4 text-emerald-600"></i></div><div class="min-w-0"><span class="text-xs text-slate-400 block font-medium">Class Mentor</span><span class="text-slate-900 font-semibold truncate block text-sm" title="${batch.mentor_name}">${batch.mentor_name}</span></div></div>`
        : `<div class="flex items-center gap-2.5 text-sm"><div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0"><i data-lucide="user-x" class="w-4 h-4 text-slate-400"></i></div><div><span class="text-xs text-slate-400 block font-medium">Class Mentor</span><span class="text-slate-400 italic text-sm">Not assigned</span></div></div>`;

      card.innerHTML = `
        <div class="space-y-4">
          <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2">
              <span class="px-2.5 py-1 border rounded-lg font-mono text-sm font-bold bg-slate-100 text-slate-800 border-slate-200/80 whitespace-nowrap">${batch.classroom_id}</span>
              ${batch.classroom_id.includes('_LET') ? `<span class="bg-purple-50 border border-purple-200 text-purple-700 font-bold text-xs px-2.5 py-0.5 rounded uppercase select-none whitespace-nowrap">LET</span>` : ''}
              ${isR26 ? `<span class="bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-xs px-2.5 py-0.5 rounded uppercase select-none tracking-wide whitespace-nowrap">Revision 2026</span>` : ''}
            </div>
            <div class="shrink-0">
              ${(batch.current_semester || 1) > 6
                ? `<span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold text-sm tracking-wide flex items-center gap-1 select-none whitespace-nowrap"><i data-lucide="graduation-cap" class="w-4 h-4 text-emerald-600"></i>Graduated</span>`
                : `<span onclick="event.stopPropagation(); changeBatchSemesterPrompt('${batch.classroom_id}', ${batch.current_semester || 1})" class="px-3 py-1 bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 rounded-xl font-bold text-sm tracking-wide cursor-pointer shadow-2xs select-none transition-colors whitespace-nowrap" title="Click to Change Batch Semester">Semester ${batch.current_semester || 1}</span>`
              }
            </div>
          </div>
          
          <div>
            <h4 class="font-bold text-lg text-slate-900">Admission ${batch.batch_year}${isLetBatch ? ' (LET)' : ''}</h4>
            <p class="text-sm text-slate-500">${batch.batch_year} – ${batch.batch_year + 3} ${isLetBatch ? 'Lateral Entry ' : ''}Batch</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 border-t border-slate-100 pt-3.5">
            ${tutorHtml}
            ${mentorHtml}
          </div>
        </div>

        <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-4">
          <div class="flex items-baseline gap-1.5">
            <span class="text-xl font-bold text-slate-900">${batch.student_count}</span>
            <span class="text-sm text-slate-500 font-medium">students enrolled</span>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="openBatchDetail(${JSON.stringify(batch).replace(/"/g, '&quot;')})" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition-colors cursor-pointer shadow-xs">
              <i data-lucide="settings" class="w-4 h-4 text-slate-300"></i>
              <span>Manage Batch</span>
            </button>
          </div>
        </div>
      `;

      grid.appendChild(card);
    }

    function changeBatchSemesterPrompt(classroomId, currentSem) {
      let newSemStr = prompt("Enter active Semester (1-8) for batch " + classroomId + ":", currentSem);
      if (newSemStr === null) return;
      let newSem = parseInt(newSemStr);
      if (isNaN(newSem) || newSem < 1 || newSem > 8) {
        alert("Invalid semester! Please enter a number between 1 and 8.");
        return;
      }

      fetch(`/api/hod/batches/${classroomId}/update-semester`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ current_semester: newSem })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Batch current semester updated successfully.');
          loadBatches(currentBatchFilter);
        } else {
          showGlobalMessage(data.message, true);
        }
      });
    }

    function toggleBatchCreationLetView() {
      const isLet = document.getElementById('batchTypeSelect').value === 'LET';
      const startSemesterContainer = document.getElementById('batchStartSemesterContainer');
      const tutorContainer = document.getElementById('batchTutorContainer');
      const mentorContainer = document.getElementById('batchMentorContainer');

      if (isLet) {
        startSemesterContainer.classList.add('hidden');
        tutorContainer.classList.add('hidden');
        mentorContainer.classList.add('hidden');
      } else {
        startSemesterContainer.classList.remove('hidden');
        tutorContainer.classList.remove('hidden');
        mentorContainer.classList.remove('hidden');
      }
    }

    function openCreateBatchModal() {
      document.getElementById('createBatchAlert').classList.add('hidden');
      document.getElementById('batchAdmYear').value = new Date().getFullYear();
      document.getElementById('batchTypeSelect').value = 'Regular';
      toggleBatchCreationLetView();
      updateBatchPreview();
      // Refresh staff cache then populate dropdowns
      fetch('/api/hod/dept-staff')
        .then(r => r.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            deptStaffCache = data.staff;
            populateStaffDropdowns();
          }
        });
      const modal = document.getElementById('createBatchModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeCreateBatchModal() {
      const modal = document.getElementById('createBatchModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function updateBatchPreview() {
      const isLet = document.getElementById('batchTypeSelect').value === 'LET';
      const year = parseInt(document.getElementById('batchAdmYear').value) || new Date().getFullYear();
      const branch = '{{ session("userBranch") }}';
      if (isLet) {
        const baseYear = year - 1;
        document.getElementById('batchIdPreview').innerText = `${branch}_${baseYear}_${baseYear + 3}_LET`;
      } else {
        document.getElementById('batchIdPreview').innerText = `${branch}_${year}_${year + 3}`;
      }
    }

    function submitCreateBatch() {
      const spinner = document.getElementById('createBatchSpinner');
      const alertEl = document.getElementById('createBatchAlert');
      const isLet = document.getElementById('batchTypeSelect').value === 'LET';
      const year = document.getElementById('batchAdmYear').value;

      if (!year) {
        alertEl.className = 'p-3 rounded-xl text-sm font-semibold bg-rose-50 text-rose-800 border border-rose-200 block';
        alertEl.innerText = 'Please enter an admission year.';
        alertEl.classList.remove('hidden');
        return;
      }

      let payload = {
        is_lateral_entry: isLet,
        admission_year: parseInt(year)
      };

      if (!isLet) {
