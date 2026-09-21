  <script>
    let activePanel = "dashboard";
    let selectedUserForReset = null;

    document.addEventListener("DOMContentLoaded", () => {
      loadStats();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'directory', 'audit'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-2.5 transition-premium bg-amber-500/10 text-amber-400 border-l-2 border-amber-500 text-xs";
        } else {
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-white hover:bg-slate-800 cursor-pointer text-xs";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'dashboard': 'Executive Overview',
        'directory': 'Personnel Directory',
        'audit': 'Audit Trail Log'
      };
      document.getElementById('panelTitle').innerText = titles[panelId] || 'Chairman Desk';

      if (panelId === 'directory') loadUsers();
      if (panelId === 'audit') loadAuditTrail();
    }

    function showLoading(show) {
      const el = document.getElementById('loadingIndicator');
      if (el) {
        if (show) el.classList.remove('hidden'); else el.classList.add('hidden');
      }
    }

    function loadStats() {
      showLoading(true);
      fetch('/api/admin/stats')
        .then(res => res.json())
        .then(data => {
          showLoading(false);
          if (data.status === 'SUCCESS') {
            document.getElementById('statTotalStaff').innerText = data.stats.totalStaff;
            document.getElementById('statTotalStudents').innerText = data.stats.totalStudents;
            document.getElementById('statPendingApprovals').innerText = data.stats.pendingApprovals;
            document.getElementById('statTotalClassrooms').innerText = data.stats.totalClassrooms;
          }
        })
        .catch(() => showLoading(false));
    }

    function loadUsers() {
      showLoading(true);
      const search = document.getElementById('filterSearch').value;
      const branch = document.getElementById('filterBranch').value;
      const role = document.getElementById('filterRole').value;
      const status = document.getElementById('filterStatus').value;

      const query = new URLSearchParams({ search, branch, role, status }).toString();
      fetch(`/api/admin/users?${query}`)
        .then(res => res.json())
        .then(data => {
          showLoading(false);
          const tbody = document.getElementById('usersTableBody');
          tbody.innerHTML = "";

          if (data.status === 'SUCCESS' && data.users.length > 0) {
            data.users.forEach(user => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition text-xs";
              
              const statusBadge = user.status === 'Approved' 
                ? `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`
                : (user.status === 'Pending' 
                  ? `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`
                  : `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">${user.status}</span>`);

              const defaultPhoto = user.type === 'student'
                ? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100'
                : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100';

              tr.innerHTML = `
                <td class="p-3 flex items-center gap-3">
                  <img src="${user.photo_url || defaultPhoto}" class="w-8 h-8 rounded-full border border-slate-700 object-cover">
                  <div>
                    <span class="font-bold text-slate-100 block">${user.name}</span>
                    <span class="text-[10px] text-slate-400 block">${user.email}</span>
                  </div>
                </td>
                <td class="p-3 font-mono text-slate-300">${user.id}</td>
                <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">${user.branch || 'N/A'}</span></td>
                <td class="p-3 font-semibold text-amber-400">${user.role}</td>
                <td class="p-3">${statusBadge}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500">No personnel records found.</td></tr>`;
          }
        })
        .catch(() => showLoading(false));
    }

    function toggleUserStatus(userId, userType, newStatus) {
      showLoading(true);
      fetch('/api/admin/users/toggle-status', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ userId, userType, newStatus })
      })
      .then(res => res.json())
      .then(data => {
        showLoading(false);
        if (data.status === 'SUCCESS') {
          loadUsers();
          loadStats();
        } else {
          alert(data.message || 'Status update failed.');
        }
      })
      .catch(() => showLoading(false));
    }

    function openPasswordModal(id, name, type) {
      selectedUserForReset = { id, name, type };
      document.getElementById('pwdResetName').innerText = name;
      document.getElementById('pwdResetId').innerText = id;
      document.getElementById('newPasswordInput').value = "";
      document.getElementById('pwdAlert').classList.add('hidden');
      document.getElementById('passwordModal').classList.remove('hidden');
      document.getElementById('passwordModal').classList.add('flex');
    }

    function closePasswordModal() {
      document.getElementById('passwordModal').classList.add('hidden');
      document.getElementById('passwordModal').classList.remove('flex');
    }

    function submitPasswordReset() {
      const newPassword = document.getElementById('newPasswordInput').value.trim();
      const alertEl = document.getElementById('pwdAlert');
      if (newPassword.length < 4) {
        alertEl.innerText = "Password must be at least 4 characters long.";
        alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-red-500/10 text-red-400 border-red-500/20";
        alertEl.classList.remove('hidden');
        return;
      }

      showLoading(true);
      fetch('/api/admin/users/reset-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          userId: selectedUserForReset.id,
          userType: selectedUserForReset.type,
          newPassword: newPassword
        })
      })
      .then(res => res.json())
      .then(data => {
        showLoading(false);
        if (data.status === 'SUCCESS') {
          alertEl.innerText = "Password reset successfully!";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-green-500/10 text-green-400 border-green-500/20";
          alertEl.classList.remove('hidden');
          setTimeout(closePasswordModal, 1200);
        } else {
          alertEl.innerText = data.message || "Failed to reset password.";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-red-500/10 text-red-400 border-red-500/20";
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => showLoading(false));
    }

    function openAuditModal(id, name) {
      document.getElementById('auditProfileName').innerText = name;
      document.getElementById('auditProfileId').innerText = id;
      const tbody = document.getElementById('modalAuditTableBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-500">Querying audit logs...</td></tr>`;
      document.getElementById('auditModal').classList.remove('hidden');
      document.getElementById('auditModal').classList.add('flex');

      fetch(`/api/audit-logs?targetId=${id}`)
        .then(res => res.json())
        .then(data => {
          tbody.innerHTML = "";
          if (data.status === 'SUCCESS' && data.logs.length > 0) {
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-xs";
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${new Date(log.created_at).toLocaleString()}</td>
                <td class="p-3 font-bold text-slate-200">${log.performed_by_name || log.performed_by}</td>
                <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-500">No profile audit logs recorded.</td></tr>`;
          }
        });
    }

    function closeAuditModal() {
      document.getElementById('auditModal').classList.add('hidden');
      document.getElementById('auditModal').classList.remove('flex');
    }

    function loadAuditTrail() {
      showLoading(true);
      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          showLoading(false);
          const tbody = document.getElementById('auditTableBody');
          tbody.innerHTML = "";
          if (data.status === 'SUCCESS' && data.logs.length > 0) {
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition text-xs";
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${new Date(log.created_at).toLocaleString()}</td>
                <td class="p-3 font-bold text-slate-200">${log.performed_by_name || log.performed_by}</td>
                <td class="p-3 font-mono text-amber-400">${log.target_id || 'N/A'}</td>
                <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">${log.action}</span></td>
                <td class="p-3 font-mono text-slate-400 text-[10px]">${log.ip_address || '127.0.0.1'}</td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500">No system audit logs found.</td></tr>`;
          }
        })
        .catch(() => showLoading(false));
    }

    function openRegisterModal() {
      document.getElementById('registerModal').classList.remove('hidden');
      document.getElementById('registerModal').classList.add('flex');
    }

    function closeRegisterModal() {
      document.getElementById('registerModal').classList.add('hidden');
      document.getElementById('registerModal').classList.remove('flex');
    }

    function toggleDirectRegisterFields(type) {
      if (type === 'student') {
        document.getElementById('directStudentFields').classList.remove('hidden');
        document.getElementById('directStaffFields').classList.add('hidden');
      } else {
        document.getElementById('directStudentFields').classList.add('hidden');
        document.getElementById('directStaffFields').classList.remove('hidden');
      }
    }

    function handleDirectRegister(e) {
      e.preventDefault();
      const type = document.getElementById('regType').value;
      const name = document.getElementById('directRegName').value;
      const email = document.getElementById('directRegEmail').value;
      const password = document.getElementById('directRegPassword').value;
      const alertEl = document.getElementById('directRegAlert');

      let url = '/register/student';
      let payload = {};

      if (type === 'student') {
        payload = {
          name, email, password,
          reg_no: document.getElementById('directRegStudentId').value,
          adm_no: document.getElementById('directRegStudentAdm').value,
          branch: document.getElementById('directRegStudentBranch').value,
          admission_year: document.getElementById('directRegStudentYear').value,
          semester: document.getElementById('directRegStudentSem').value
        };
      } else {
        url = '/register/staff';
        payload = {
          name, email, password,
          mobile_no: document.getElementById('directRegStaffMobile').value,
          designation: document.getElementById('directRegStaffDesig').value,
          branch: document.getElementById('directRegStaffBranch').value
        };
      }

      showLoading(true);
      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        showLoading(false);
        if (data.status === 'SUCCESS') {
          alertEl.innerText = "Profile registered successfully!";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-green-500/10 text-green-400 border-green-500/20";
          alertEl.classList.remove('hidden');
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
            loadStats();
          }, 1200);
        } else {
          alertEl.innerText = data.message || "Registration failed.";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-red-500/10 text-red-400 border-red-500/20";
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => showLoading(false));
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const formData = new FormData();
      formData.append('photo', file);

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      if (statusEl) {
        statusEl.innerText = 'Uploading...';
        statusEl.classList.remove('hidden');
      }

      fetch('/api/staff/profile/upload-photo', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          if (statusEl) {
            statusEl.innerText = 'Updated!';
            setTimeout(() => statusEl.classList.add('hidden'), 2000);
          }
          if (data.photo_url) {
            document.getElementById('sidebarStaffImg').src = data.photo_url;
          }
        } else {
          if (statusEl) {
            statusEl.innerText = 'Failed';
            statusEl.className = 'text-sm font-bold text-red-400';
          }
        }
      })
      .catch(() => {
        if (statusEl) {
          statusEl.innerText = 'Error';
          statusEl.className = 'text-sm font-bold text-red-400';
        }
      });
    }

    function loadExecutiveMetrics() {
      fetch('/api/admin/executive-kpis')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('execStaffLeaveTotal').innerText = `${data.leave_breakdown.total_on_leave} Active`;
            if (document.getElementById('execLeaveCL')) document.getElementById('execLeaveCL').innerText = data.leave_breakdown.CL || 0;
            if (document.getElementById('execLeaveCCL')) document.getElementById('execLeaveCCL').innerText = data.leave_breakdown.CCL || 0;
            if (document.getElementById('execLeaveDL')) document.getElementById('execLeaveDL').innerText = data.leave_breakdown.DL || 0;
            if (document.getElementById('execLeaveML')) document.getElementById('execLeaveML').innerText = data.leave_breakdown.ML || 0;
            if (document.getElementById('execLeaveLOP')) document.getElementById('execLeaveLOP').innerText = data.leave_breakdown.LOP || 0;
            if (document.getElementById('execLeaveOTHERS')) document.getElementById('execLeaveOTHERS').innerText = data.leave_breakdown.OTHERS || 0;

            // Populate hover popup lists with staff names & department
            if (data.leave_breakdown.staff_by_type) {
              ['CL', 'CCL', 'DL', 'ML', 'LOP', 'OTHERS'].forEach(t => {
                const listEl = document.getElementById(`popupList${t}`);
                const countEl = document.getElementById(`popupCount${t}`);
                const staffArr = data.leave_breakdown.staff_by_type[t] || [];

                if (countEl) countEl.innerText = `${staffArr.length} Staff`;

                if (listEl) {
                  if (staffArr.length > 0) {
                    listEl.innerHTML = staffArr.map(s => `
                      <div class="flex items-center justify-between gap-1 border-b border-slate-800/60 pb-1">
                        <span class="font-bold text-slate-100 truncate text-[10px]">${s.name}</span>
                        <span class="text-[9px] text-sky-400 font-mono shrink-0">${s.dept}</span>
                      </div>
                    `).join('');
                  } else {
                    listEl.innerHTML = `<span class="text-slate-500 italic block text-[10px]">No staff on ${t} today</span>`;
                  }
                }
              });
            }

            if (data.today_events && data.today_events.length > 0) {
              allTodayEventsCache = data.today_events;
              todayEventCountsCache = data.event_counts || {};

              const badge = document.getElementById('execEventsCountBadge');
              if (badge) badge.innerText = `${data.today_events.length} Scheduled`;

              const modalTotalBadge = document.getElementById('modalEventsTotalBadge');
              if (modalTotalBadge) modalTotalBadge.innerText = `${data.today_events.length} Total`;
              
              const listContainer = document.getElementById('execTodayEventsList');
              if (listContainer) {
                listContainer.innerHTML = data.today_events.slice(0, 2).map(ev => `
                  <div class="flex items-center gap-1.5 truncate">
                    <span class="w-1.5 h-1.5 rounded-full ${ev.type === 'Holiday' ? 'bg-amber-400' : (ev.type === 'Exam' ? 'bg-rose-400' : 'bg-sky-400')} shrink-0"></span>
                    <span class="truncate font-medium text-slate-200 text-[11px]" title="${ev.title}">${ev.title}</span>
                  </div>
                `).join('');
              }

              // Update counter badges on modal tabs
              const counts = data.event_counts || {};
              const total = data.today_events.length;
              if (document.getElementById('evtCnt_ALL')) document.getElementById('evtCnt_ALL').innerText = total;

              ['Departments', 'College', 'NSS', 'NCC', 'IEDC', 'Placement Cell', 'Others'].forEach(cat => {
                const cntEl = document.getElementById(`evtCnt_${cat}`);
                if (cntEl) cntEl.innerText = counts[cat] || 0;
              });
            }
          }
        }).catch(() => {});

      fetch('/api/admin/executive-compliance')
        .then(res => res.json())
        .then(data => {
