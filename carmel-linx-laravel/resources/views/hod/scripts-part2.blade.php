              </button>
              <button onclick="editStudentBatch('${user.id}', '${user.classroom_id || ''}')" class="text-slate-600 hover:text-slate-900 font-medium text-xs ml-2 px-1.5 py-0.5 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-md cursor-pointer transition-colors" title="Move Batch">
                Move
              </button>
            ` : '<span class="text-slate-400 font-medium text-sm">—</span>'}
          </td>
          <td class="p-3.5 text-sm text-slate-700 font-medium whitespace-nowrap">${roleCol}</td>
          <td class="p-3.5 text-sm">${statusBadge}</td>
          <td class="p-3.5">
            ${user.type === 'student' ? `
              <select onchange="updateAcademicStatusDirectly('${user.id}', this.value)" class="bg-white border rounded-lg px-2.5 py-1 text-sm outline-none focus:border-blue-600 font-semibold cursor-pointer transition-colors ${
                user.academic_status === 'Active' ? 'text-emerald-700 bg-emerald-50/50 border-emerald-200' :
                user.academic_status === 'Discontinued' ? 'text-amber-700 bg-amber-50/50 border-amber-200' :
                'text-rose-700 bg-rose-50/50 border-rose-200'
              }">
                <option value="Active" ${user.academic_status === 'Active' ? 'selected' : ''}>Active</option>
                <option value="Discontinued" ${user.academic_status === 'Discontinued' ? 'selected' : ''}>Discontinued</option>
                <option value="TC Issued" ${user.academic_status === 'TC Issued' ? 'selected' : ''}>TC Issued</option>
              </select>
            ` : '<span class="text-slate-400 font-medium text-sm">—</span>'}
          </td>
          <td class="p-3.5 text-right space-x-1.5 text-sm whitespace-nowrap">
            ${toggleButton}
            <button onclick="triggerPasswordReset('${user.id}', '${user.type}', '${user.name}')" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
              Reset Pwd
            </button>
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold transition-colors cursor-pointer" title="View Audit Trail">
              Audit
            </button>
            ${user.id !== "{{ session('userId') }}" ? `
            <button onclick="confirmDeleteUser('${user.id}', '${user.type}', '${user.name}')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer" title="Delete User">
              Delete
            </button>` : ''}
          </td>
        `;
        tbody.appendChild(tr);
      });
      if (window.initLucide) window.initLucide();
    }

    function changeStatus(userId, userType, newStatus) {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/admin/user/toggle-status', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ userId, userType, newStatus })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('User status updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => {
        indicator.classList.add('hidden');
        showGlobalMessage('Failed to update status.', true);
      });
    }

    function editStudentSemester(regNo, currentSem) {
      let newSemStr = prompt("Enter new Semester (1-6) for student " + regNo + ":", currentSem.replace('S', ''));
      if (newSemStr === null) return;
      let newSem = parseInt(newSemStr);
      if (isNaN(newSem) || newSem < 1 || newSem > 6) {
        alert("Invalid semester! Please enter a number between 1 and 6.");
        return;
      }
      
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      
      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ semester: newSem })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student semester updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => indicator.classList.add('hidden'));
    }

    function editStudentBatch(regNo, currentBatch) {
      let newBatch = prompt("Enter new Classroom ID (Batch) for student " + regNo + ":", currentBatch || '');
      if (newBatch === null) return;
      newBatch = newBatch.trim();
      if (!newBatch) return;

      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ classroom_id: newBatch })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student batch updated successfully.');
          loadUsers();
          if (typeof activeBatchId !== 'undefined' && activeBatchId) {
             loadBatchRoster(activeBatchId);
          }
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
      });
    }

    function updateAcademicStatusDirectly(regNo, newVal) {
      let note = prompt("Enter remarks / reason for changing enrollment status to " + newVal + " (optional):");
      if (note === null) {
        loadUsers(); // User clicked cancel, refresh to restore dropdown selection
        return;
      }

      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ academic_status: newVal, status_notes: note })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student enrollment status updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
          loadUsers(); // refresh to reset selection
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
        loadUsers();
      });
    }

    function triggerPasswordReset(userId, userType, userName) {
      selectedUserForReset = { userId, userType };
      document.getElementById('pwdResetName').innerText = userName;
      document.getElementById('pwdResetId').innerText = userId;
      document.getElementById('newPasswordInput').value = "";
      document.getElementById('pwdAlert').classList.add('hidden');
      
      const modal = document.getElementById('passwordModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closePasswordModal() {
      const modal = document.getElementById('passwordModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      selectedUserForReset = null;
    }

    function submitPasswordReset() {
      const pwd = document.getElementById('newPasswordInput').value.trim();
      const pwdAlert = document.getElementById('pwdAlert');
      
      if (pwd.length < 4) {
        pwdAlert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Password must be at least 4 characters long.";
        pwdAlert.classList.remove('hidden');
        return;
      }

      fetch('/api/admin/user/reset-password', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          userId: selectedUserForReset.userId,
          userType: selectedUserForReset.userType,
          newPassword: pwd
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Password reset successfully.');
          closePasswordModal();
        } else {
          pwdAlert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          pwdAlert.innerText = data.message;
          pwdAlert.classList.remove('hidden');
        }
      })
      .catch(() => {
        pwdAlert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Request failed.";
        pwdAlert.classList.remove('hidden');
      });
    }

    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm"><div class="w-4 h-4 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin mx-auto mb-2"></div>Querying department audit logs...</td></tr>`;

      fetch('/api/audit-logs?branch={{ $activeBranch }}')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `
                <tr>
                  <td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                      <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <p class="font-semibold text-slate-800">No department audit logs found</p>
                    <p class="text-xs text-slate-400 mt-0.5">No administrative activity records exist for this branch yet.</p>
                  </td>
                </tr>
              `;
              if (window.initLucide) window.initLucide();
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-100 hover:bg-slate-50/70 transition-colors";
              
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3.5 text-slate-500 font-mono text-xs whitespace-nowrap">${date}</td>
                <td class="p-3.5 font-medium">
                  <span class="font-semibold text-slate-900 text-sm block">${log.performed_by_name || 'System'}</span>
                  <span class="text-xs text-slate-500 font-mono">${log.performed_by || ''}</span>
                </td>
                <td class="p-3.5 font-medium">
                  <span class="font-semibold text-slate-900 text-sm block">${log.target_name || '—'}</span>
                  <span class="text-xs text-blue-600 font-mono">${log.target_id || ''}</span>
                </td>
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
      tbody.innerHTML = `<tr><td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm"><div class="w-4 h-4 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin mx-auto mb-2"></div>Querying department audit logs...</td></tr>`;

      fetch('/api/audit-logs?branch={{ $activeBranch }}')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `
                <tr>
                  <td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                      <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <p class="font-semibold text-slate-800">No department audit logs found</p>
                    <p class="text-xs text-slate-400 mt-0.5">No administrative activity records exist for this branch yet.</p>
                  </td>
                </tr>
              `;
              if (window.initLucide) window.initLucide();
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-100 hover:bg-slate-50/70 transition-colors";
              
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3.5 text-slate-500 font-mono text-xs whitespace-nowrap">${date}</td>
                <td class="p-3.5 font-medium">
                  <span class="font-semibold text-slate-900 text-sm block">${log.performed_by_name || 'System'}</span>
                  <span class="text-xs text-slate-500 font-mono">${log.performed_by || ''}</span>
                </td>
                <td class="p-3.5 font-medium">
                  <span class="font-semibold text-slate-900 text-sm block">${log.target_name || '—'}</span>
                  <span class="text-xs text-blue-600 font-mono">${log.target_id || ''}</span>
                </td>
