        const res = await fetch(`/api/admin/users?${query}`);
        const data = await res.json();

        if (data.users && Array.isArray(data.users)) {
          userDirectoryData = data.users;
          userDirectoryPage = 1;
          renderUserDirectoryTable();
        } else {
          userDirectoryData = [];
          renderUserDirectoryTable();
        }
      } catch (err) {
        if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-500 font-medium">Failed to load directory.</td></tr>`;
      }
    }

    function changeUserPageSize(val) {
      userDirectoryPageSize = val === 'all' ? 999999 : parseInt(val, 10);
      userDirectoryPage = 1;
      renderUserDirectoryTable();
    }

    function changeUserPage(newPage) {
      const totalPages = Math.ceil(userDirectoryData.length / userDirectoryPageSize) || 1;
      if (newPage < 1 || newPage > totalPages) return;
      userDirectoryPage = newPage;
      renderUserDirectoryTable();
    }

    function renderUserDirectoryTable() {
      const tbody = document.getElementById('userTableBody');
      const infoEl = document.getElementById('userPaginationInfo');
      const navEl = document.getElementById('userPaginationNav');
      if (!tbody) return;

      const total = userDirectoryData.length;
      if (total === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400 font-medium">No matching user accounts found.</td></tr>`;
        if (infoEl) infoEl.innerText = `Showing 0 of 0 accounts`;
        if (navEl) navEl.innerHTML = '';
        return;
      }

      const totalPages = Math.ceil(total / userDirectoryPageSize);
      if (userDirectoryPage > totalPages) userDirectoryPage = totalPages;
      if (userDirectoryPage < 1) userDirectoryPage = 1;

      const startIdx = (userDirectoryPage - 1) * userDirectoryPageSize;
      const endIdx = Math.min(startIdx + userDirectoryPageSize, total);
      const pageSlice = userDirectoryData.slice(startIdx, endIdx);

      if (infoEl) {
        infoEl.innerText = `Showing ${startIdx + 1} - ${endIdx} of ${total} accounts`;
      }

      // Generate Pagination Navigation Buttons
      if (navEl) {
        if (totalPages <= 1) {
          navEl.innerHTML = '';
        } else {
          let navHtml = `
            <button onclick="changeUserPage(${userDirectoryPage - 1})" ${userDirectoryPage === 1 ? 'disabled' : ''} class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-semibold ${userDirectoryPage === 1 ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-white text-slate-700 hover:bg-slate-50 cursor-pointer shadow-2xs'}">
              Prev
            </button>
          `;

          // Show window of page numbers
          let startPage = Math.max(1, userDirectoryPage - 2);
          let endPage = Math.min(totalPages, startPage + 4);
          if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
          }

          if (startPage > 1) {
            navHtml += `<button onclick="changeUserPage(1)" class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-semibold bg-white text-slate-700 hover:bg-slate-50 cursor-pointer shadow-2xs">1</button>`;
            if (startPage > 2) navHtml += `<span class="px-1 text-slate-400 text-xs">...</span>`;
          }

          for (let p = startPage; p <= endPage; p++) {
            if (p === userDirectoryPage) {
              navHtml += `<button class="px-2.5 py-1 rounded-lg bg-blue-600 border border-blue-600 text-xs font-bold text-white shadow-2xs">${p}</button>`;
            } else {
              navHtml += `<button onclick="changeUserPage(${p})" class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-semibold bg-white text-slate-700 hover:bg-slate-50 cursor-pointer shadow-2xs">${p}</button>`;
            }
          }

          if (endPage < totalPages) {
            if (endPage < totalPages - 1) navHtml += `<span class="px-1 text-slate-400 text-xs">...</span>`;
            navHtml += `<button onclick="changeUserPage(${totalPages})" class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-semibold bg-white text-slate-700 hover:bg-slate-50 cursor-pointer shadow-2xs">${totalPages}</button>`;
          }

          navHtml += `
            <button onclick="changeUserPage(${userDirectoryPage + 1})" ${userDirectoryPage === totalPages ? 'disabled' : ''} class="px-2.5 py-1 rounded-lg border border-slate-200 text-xs font-semibold ${userDirectoryPage === totalPages ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-white text-slate-700 hover:bg-slate-50 cursor-pointer shadow-2xs'}">
              Next
            </button>
          `;
          navEl.innerHTML = navHtml;
        }
      }

      // Render table rows
      tbody.innerHTML = pageSlice.map(u => {
        const uid = u.id || u.mobile_no || u.register_no || '-';
        const uname = u.name || 'User';
        const uemail = u.email || 'No email registered';
        const ubranch = u.branch || 'General';
        const urole = u.role || u.designation || 'Staff';
        const utype = u.type || (urole === 'Student' ? 'student' : 'staff');
        const ustatus = u.status || 'Pending';
        const uphoto = u.photo_url || '';
        const initials = uname.split(' ').map(n => n[0]).filter(Boolean).slice(0, 2).join('').toUpperCase() || 'U';

        // Fast avatar rendering (inline initial badge with lazy image overlay)
        let avatarHtml = `
          <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-bold text-xs flex items-center justify-center shrink-0 border border-slate-300 shadow-2xs">
            ${initials}
          </div>
        `;
        if (uphoto && (uphoto.startsWith('/') || uphoto.startsWith('http'))) {
          avatarHtml = `
            <div class="relative w-8 h-8 rounded-full shrink-0">
              <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-bold text-xs flex items-center justify-center border border-slate-300 shadow-2xs">
                ${initials}
              </div>
              <img src="${uphoto}" loading="lazy" decoding="async" class="absolute inset-0 w-8 h-8 rounded-full object-cover border border-slate-200 shadow-2xs" onerror="this.remove()">
            </div>
          `;
        }

        // Status Badge Styling
        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>`;
        if (ustatus === 'Approved') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Approved</span>`;
        } else if (ustatus === 'Suspended') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">Suspended</span>`;
        }

        // Action Options depending on current status
        let toggleButton = '';
        if (canManageUsers) {
          if (ustatus === 'Pending') {
            toggleButton = `
              <button onclick="changeStatus('${uid}', '${utype}', 'Approved')" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-xs font-bold text-white transition cursor-pointer shadow-2xs">
                Approve
              </button>
            `;
          } else if (ustatus === 'Approved') {
            toggleButton = `
              <button onclick="changeStatus('${uid}', '${utype}', 'Suspended')" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-600 border border-amber-300 text-amber-700 hover:text-white rounded-lg text-xs font-bold transition cursor-pointer shadow-2xs">
                Suspend
              </button>
            `;
          } else if (ustatus === 'Suspended') {
            toggleButton = `
              <button onclick="changeStatus('${uid}', '${utype}', 'Approved')" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 rounded-lg text-xs font-bold text-white transition cursor-pointer shadow-2xs">
                Activate
              </button>
            `;
          }
        }

        // Role Designation selector (for Staff members only; Super Admin and Principal can change roles)
        const normalizedRole = urole.replace(/ /g, '_');
        let roleCol = `<span class="text-sm font-semibold text-slate-700">${urole.replace(/_/g, ' ')}</span>`;
        if (utype === 'staff' && canChangeRoles) {
          if ((normalizedRole === 'Super_Admin' || normalizedRole === 'SuperAdmin') && !isSuperAdmin) {
            roleCol = `<span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-200">Super Admin</span>`;
          } else {
            roleCol = `
              <select onchange="updateDesignation('${uid}', this.value)" class="bg-white border border-slate-200 rounded-lg px-2.5 py-1 text-sm text-slate-800 outline-none cursor-pointer max-w-[200px] truncate focus:border-blue-500 font-medium shadow-2xs">
                ${isSuperAdmin ? `<option value="Super_Admin" ${normalizedRole === 'Super_Admin' ? 'selected' : ''}>Super Admin</option>` : ''}
                <option value="Admin" ${normalizedRole === 'Admin' ? 'selected' : ''}>Admin</option>
                <option value="Principal" ${normalizedRole === 'Principal' ? 'selected' : ''}>Principal</option>
                <option value="HOD" ${normalizedRole === 'HOD' ? 'selected' : ''}>Head of Department (HOD)</option>
                <option value="Academic_Coordinator" ${normalizedRole === 'Academic_Coordinator' ? 'selected' : ''}>Academic Coordinator</option>
                <option value="Gen_Dept_Coordinator_Aided" ${normalizedRole === 'Gen_Dept_Coordinator_Aided' ? 'selected' : ''}>Gen Dept Coordinator Aided</option>
                <option value="Gen_Dept_Coordinator_Self_Finance" ${normalizedRole === 'Gen_Dept_Coordinator_Self_Finance' ? 'selected' : ''}>Gen Dept Coordinator Self Finance</option>
                <option value="Lecturer" ${normalizedRole === 'Lecturer' ? 'selected' : ''}>Lecturer</option>
                <option value="Demonstrator" ${normalizedRole === 'Demonstrator' ? 'selected' : ''}>Demonstrator</option>
                <option value="Physical_Instructor" ${normalizedRole === 'Physical_Instructor' ? 'selected' : ''}>Physical Instructor</option>
                <option value="Trade_Instructor" ${normalizedRole === 'Trade_Instructor' ? 'selected' : ''}>Trade Instructor</option>
                <option value="Tradesman" ${normalizedRole === 'Tradesman' ? 'selected' : ''}>Tradesman</option>
                <option value="Laboratory_Assistant" ${normalizedRole === 'Laboratory_Assistant' ? 'selected' : ''}>Laboratory Assistant</option>
                <option value="Workshop_Instructor" ${normalizedRole === 'Workshop_Instructor' ? 'selected' : ''}>Workshop Instructor</option>
                <option value="Workshop_Superintendent" ${normalizedRole === 'Workshop_Superintendent' ? 'selected' : ''}>Workshop Superintendent</option>
              </select>
            `;
          }
        }

        let idColumnHtml = `<span class="font-mono font-bold text-slate-700 text-sm">${uid}</span>`;
        if (utype === 'staff' && canManageUsers) {
          idColumnHtml = `
            <a href="javascript:void(0)" 
               onclick="openEditStaffModal('${uid}', '${uname.replace(/'/g, "\\'")}', '${uemail.replace(/'/g, "\\'")}', '${ubranch}', '${urole}')" 
               class="text-blue-600 hover:text-blue-700 underline font-mono font-bold text-sm transition" 
               title="Modify profile for ${uname}">
              ${uid}
            </a>
          `;
        }

        let actionsColHtml = '';
        if (canManageUsers) {
          if ((normalizedRole === 'Super_Admin' || normalizedRole === 'SuperAdmin') && !isSuperAdmin) {
            actionsColHtml = `
              <div class="flex items-center justify-end gap-1.5 text-xs text-slate-400">
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">Protected</span>
              </div>
            `;
          } else {
            actionsColHtml = `
              <div class="flex items-center justify-end gap-1.5 flex-wrap">
                ${toggleButton}
                <button onclick="triggerPasswordReset('${uid}', '${utype}', '${uname.replace(/'/g, "\\'")}')" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 rounded-lg text-xs font-bold transition cursor-pointer shadow-2xs">
                  Reset Pwd
                </button>
                <button onclick="openAuditModal('${uid}', '${uname.replace(/'/g, "\\'")}')" class="px-2.5 py-1 bg-slate-100 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 border border-slate-200 text-slate-700 rounded-lg text-xs font-bold transition cursor-pointer shadow-2xs" title="View Audit Trail">
                  Audit
                </button>
                <button onclick="confirmDeleteUser('${uid}', '${utype}', '${uname.replace(/'/g, "\\'")}')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-600 hover:text-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold transition cursor-pointer shadow-2xs" title="Delete User">
                  Delete
                </button>
              </div>
            `;
          }
        } else {
          actionsColHtml = `
            <div class="flex items-center justify-end gap-1.5 text-xs text-slate-400">
              <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">Read-only</span>
            </div>
          `;
        }

        return `
          <tr class="hover:bg-slate-50/70 transition-colors border-b border-slate-100">
            <td class="py-3 px-4 flex items-center gap-3">
              ${avatarHtml}
              <div class="min-w-0 overflow-hidden">
                <span class="font-bold text-slate-900 block text-sm truncate max-w-[180px] lg:max-w-[240px]">${uname}</span>
                <span class="text-xs text-slate-500 block truncate max-w-[180px] lg:max-w-[240px]">${uemail}</span>
              </div>
            </td>
            <td class="py-3 px-4 font-mono text-sm shrink-0">${idColumnHtml}</td>
            <td class="py-3 px-4"><span class="font-bold font-mono text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200">${ubranch}</span></td>
            <td class="py-3 px-4">${roleCol}</td>
            <td class="py-3 px-4 text-center">${statusBadge}</td>
            <td class="py-3 px-4 text-right">
              ${actionsColHtml}
            </td>
          </tr>
        `;
      }).join('');
    }

    async function changeStatus(userId, userType, newStatus) {
      try {
        const res = await fetch('/api/admin/users/status', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ identifier: userId, status: newStatus, userType })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          loadUsers();
          loadExecutiveMetrics();
        } else {
          alert(data.message || 'Status update failed.');
        }
      } catch (err) {
        alert('Server error updating user status.');
      }
    }

    async function updateDesignation(userId, newRole) {
      try {
        const res = await fetch('/api/admin/user/change-role', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ userId, newRole })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          loadUsers();
        } else {
          alert(data.message || 'Failed to change designation.');
        }
      } catch (err) {
        alert('Failed to change staff designation.');
      }
    }

    // 3. PROFESSIONAL ACTIVITIES LOADER
    async function loadProfActivities() {
      const ay = document.getElementById('profActAyFilter')?.value || '';
      const dept = document.getElementById('profActDeptFilter')?.value || '';
      const container = document.getElementById('profActListContainer');
      const ayLabel = document.getElementById('profActAyLabel');
      if (ayLabel) ayLabel.innerText = ay;

      if (container) container.innerHTML = `<div class="p-8 text-center text-slate-400 text-sm">Loading activity records...</div>`;

      try {
        const query = new URLSearchParams({ academic_year: ay, department: dept }).toString();
        const res = await fetch(`/api/staff/professional-activities/fetch?${query}`);
        const data = await res.json();

        if (data.status === 'SUCCESS' && data.records) {
          document.getElementById('profActTotalCount').innerText = data.records.length;
          const fdpCount = data.records.filter(r => r.activity_type?.includes('fdp') || r.activity_type?.includes('workshop')).length;
          const pubCount = data.records.filter(r => r.activity_type?.includes('publication') || r.activity_type?.includes('book')).length;
          document.getElementById('profActFdpCount').innerText = fdpCount;
          document.getElementById('profActPubCount').innerText = pubCount;
          document.getElementById('profActRegistryCount').innerText = `${data.records.length} records in AY ${ay}`;

          if (data.records.length > 0) {
            container.innerHTML = data.records.map(r => {
              const details = r.details || {};
              return `
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-start justify-between gap-4 hover:border-blue-300 transition-all">
                  <div class="space-y-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                      <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded text-xs font-bold uppercase">${(r.activity_type || 'Activity').replace('_', ' ')}</span>
                      <span class="px-2 py-0.5 bg-slate-200 text-slate-700 rounded text-xs font-semibold">${r.department || 'General'}</span>
                      <span class="text-xs text-slate-500 font-medium">• ${r.staff_name || 'Faculty'} (${r.designation || 'Lecturer'})</span>
                    </div>
                    <h5 class="font-bold text-slate-900 text-sm">${details.title || details.topic || 'Professional Activity'}</h5>
                    <p class="text-xs text-slate-600 leading-relaxed">${details.description || details.organizer || 'Organized program / workshop'}</p>
                    <div class="text-xs text-slate-500 flex items-center gap-3 pt-1 flex-wrap">
                      ${details.start_date ? `<span class="font-semibold text-blue-700">📅 Start Date: <strong>${details.start_date}</strong></span><span>•</span>` : ''}
                      <span><strong>Duration:</strong> ${details.duration || '-'}</span>
                      <span>•</span>
                      <span><strong>Organizer:</strong> ${details.organizer || '-'}</span>
                    </div>
                  </div>
                  <button onclick="deleteProfActivity(${r.id})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition shrink-0" title="Delete record">
                    <span class="material-symbols-rounded text-base">delete</span>
                  </button>
                </div>
              `;
            }).join('');
          } else {
            container.innerHTML = `<div class="p-8 text-center text-slate-400 text-sm">No professional activity records found for AY ${ay}.</div>`;
          }
        }
      } catch (err) {
        if (container) container.innerHTML = `<div class="p-8 text-center text-rose-500 text-sm">Failed to load professional activities.</div>`;
      }
    }

    async function submitProfActivity(e) {
      e.preventDefault();
      const alertEl = document.getElementById('profActAlert');
      const ay = document.getElementById('profActAyFilter')?.value || '2025-2026';
      const type = document.getElementById('profActType').value;
      const title = document.getElementById('profActTitle').value;
      const organizer = document.getElementById('profActOrganizer').value;
      const duration = document.getElementById('profActDuration').value;
      const startDate = document.getElementById('profActStartDate')?.value || '';
      const description = document.getElementById('profActDesc').value;

      try {
        await fetch('/staff/professional-activities/save', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({
            academic_year: ay,
            activity_type: type,
            details: { title, organizer, duration, start_date: startDate, description }
          })
        });

        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
        alertEl.innerText = 'Professional activity recorded successfully!';
        document.getElementById('profActivityForm').reset();
        loadProfActivities();
        setTimeout(() => alertEl.classList.add('hidden'), 3000);
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Error saving activity record.';
      }
    }

    async function deleteProfActivity(id) {
      if (!confirm('Are you sure you want to delete this activity record?')) return;
      try {
        await fetch(`/staff/professional-activities/delete/${id}`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        loadProfActivities();
      } catch (err) {
        alert('Failed to delete activity.');
      }
    }

    // 4. MASTER LEAVE LEDGER LOADER
    async function loadLeaveLedger() {
      const dept = document.getElementById('leaveLedgerDept')?.value || '';
      const status = document.getElementById('leaveLedgerStatus')?.value || '';
      const tbody = document.getElementById('leaveLedgerTableBody');
      if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">Loading leave applications...</td></tr>`;

      try {
        const query = new URLSearchParams({ department: dept, status }).toString();
        const res = await fetch(`/api/staff/leave/reports-data?${query}`);
        const data = await res.json();

        if (data.status === 'SUCCESS' && data.leaves) {
          const sm = data.summary || {};
          document.getElementById('leaveKpiTotal').innerText = (sm.TOTAL_DAYS || 0).toFixed(1);
          document.getElementById('leaveKpiCL').innerText = (sm.CL || 0).toFixed(1);
          document.getElementById('leaveKpiCCL').innerText = (sm.CCL || 0).toFixed(1);
          document.getElementById('leaveKpiDL').innerText = (sm.DL || 0).toFixed(1);
          document.getElementById('leaveKpiML').innerText = (sm.ML || 0).toFixed(1);
          document.getElementById('leaveKpiLOP').innerText = (sm.LOP || 0).toFixed(1);

          if (data.leaves.length > 0) {
            tbody.innerHTML = data.leaves.map(l => `
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="py-3 px-4">
                  <div>
                    <span class="font-bold text-slate-900 block">${l.staff_name}</span>
                    <span class="text-xs text-slate-500">${l.department} • <strong class="font-mono text-blue-600">${l.leave_code}</strong></span>
                  </div>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">${l.leave_type}</span>
                </td>
                <td class="py-3 px-4">
                  <span class="text-xs font-semibold text-slate-800 block">${l.from_date} to ${l.to_date}</span>
                  <span class="text-xs text-slate-500 font-bold">${l.total_days} Day(s) (${l.session_type || 'Full Day'})</span>
                </td>
                <td class="py-3 px-4 text-xs text-slate-600 max-w-xs truncate">${l.reason || '-'}</td>
                <td class="py-3 px-4 text-center">
                  <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${l.overall_status === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (l.overall_status === 'Rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200')}">
                    ${l.overall_status?.replace('_', ' ') || 'Pending'}
                  </span>
                </td>
                <td class="py-3 px-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    ${l.overall_status !== 'Approved' && l.overall_status !== 'Rejected' ? `
                      <button onclick="processLeaveApproval(${l.id}, 'Approve')" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition" title="Approve">
                        Approve
                      </button>
                      <button onclick="processLeaveApproval(${l.id}, 'Reject')" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold transition" title="Reject">
                        Reject
                      </button>
