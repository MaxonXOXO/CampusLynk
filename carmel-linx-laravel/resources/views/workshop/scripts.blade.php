    let activePanel = 'overview';
    let selectedUserForReset = null;

    document.addEventListener("DOMContentLoaded", () => {
      loadStats();
    });

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['overview', 'staff', 'students', 'audit', 'security'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-[10px] flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-[10px] flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'overview': 'Workshop Overview',
        'staff': 'Workshop Staff â Trade Instructors',
        'students': 'Student Workshop Roster',
        'audit': 'Cross-Branch Audit Trail',
        'security': 'My Profile & Security'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'staff') loadStaff();
      if (panelId === 'students') loadStudents();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'security') loadSelfSecurityLogs();
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border-red-900 block shadow-sm";
      } else {
        alert.className = "p-4 rounded-xl text-[10px] font-bold bg-green-950/40 text-green-400 border-green-900 block shadow-sm";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    function loadStats() {
      fetch('/api/admin/stats')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('statTotalStudents').innerText = data.stats.totalStudents;
            document.getElementById('statPending').innerText = data.stats.pendingApprovals;
          }
        });

      // Count workshop staff (Trade Instructors)
      fetch('/api/admin/users?role=Trade_Instructor')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('statWorkshopStaff').innerText = data.users.length;
          }
        });
    }

    function loadStaff() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      const search = document.getElementById('staffSearch').value;
      const status = document.getElementById('staffStatus').value;

      fetch(`/api/admin/users?search=${encodeURIComponent(search)}&role=Trade_Instructor&status=${status}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') renderStaffTable(data.users);
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    function renderStaffTable(users) {
      const tbody = document.getElementById('staffTableBody');
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-medium">No Trade Instructors found.</td></tr>`;
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";

        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
        if (user.status === 'Approved') statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
        else if (user.status === 'Suspended') statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;

        let toggleButton = '';
        if (user.status === 'Pending') {
          toggleButton = `<button onclick="changeStatus('${user.id}', 'staff', 'Approved')" class="px-2 py-1 bg-green-600 hover:bg-green-700 rounded text-[10px] font-bold text-white transition-premium cursor-pointer">Approve</button>`;
        } else if (user.status === 'Approved') {
          toggleButton = `<button onclick="changeStatus('${user.id}', 'staff', 'Suspended')" class="px-2 py-1 bg-red-950 hover:bg-red-900 border border-red-800 rounded text-[10px] font-bold text-red-300 transition-premium cursor-pointer">Suspend</button>`;
        } else if (user.status === 'Suspended') {
          toggleButton = `<button onclick="changeStatus('${user.id}', 'staff', 'Approved')" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 rounded text-[10px] font-bold text-white transition-premium cursor-pointer">Activate</button>`;
        }

        tr.innerHTML = `
          <td class="p-4 flex items-center gap-3">
            <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-800 shadow">
            <div>
              <span class="font-bold text-slate-100 block">${user.name}</span>
              <span class="text-[10px] text-slate-500 block">${user.email}</span>
            </div>
          </td>
          <td class="p-4 font-mono font-bold text-slate-300">${user.id}</td>
          <td class="p-4"><span class="font-bold font-mono text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
          <td class="p-4 text-slate-300 text-[10px]">${user.role}</td>
          <td class="p-4">${statusBadge}</td>
          <td class="p-4 text-right space-x-1">
            ${toggleButton}
            <button onclick="triggerPasswordReset('${user.id}', 'staff', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded text-[10px] font-bold transition-premium cursor-pointer">Reset Pwd</button>
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-blue-900 border border-slate-800 text-slate-300 rounded text-[10px] font-bold transition-premium cursor-pointer">Audit</button>
            <button onclick="confirmDeleteUser('${user.id}', 'staff', '${user.name}')" class="px-2 py-1 bg-red-950/40 hover:bg-red-900 border border-red-900/60 text-red-400 rounded text-[10px] font-bold transition-premium cursor-pointer">Delete</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function loadStudents() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      const search = document.getElementById('studentSearch').value;
      const branch = document.getElementById('studentBranch').value;
      const status = document.getElementById('studentStatus').value;

      fetch(`/api/admin/users?search=${encodeURIComponent(search)}&role=student&branch=${branch}&status=${status}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') renderStudentTable(data.users);
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    function renderStudentTable(users) {
      const tbody = document.getElementById('studentTableBody');
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500 font-medium">No students found.</td></tr>`;
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";

        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
        if (user.status === 'Approved') statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
        else if (user.status === 'Suspended') statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;

        tr.innerHTML = `
          <td class="p-4 flex items-center gap-3">
            <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-800 shadow">
            <div>
              <span class="font-bold text-slate-100 block">${user.name}</span>
              <span class="text-[10px] text-slate-500 block">${user.email}</span>
            </div>
          </td>
          <td class="p-4 font-mono font-bold text-slate-300">${user.id}</td>
          <td class="p-4"><span class="font-bold font-mono text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
          <td class="p-4">${statusBadge}</td>
          <td class="p-4 text-right space-x-1">
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-blue-900 border border-slate-800 text-slate-300 rounded text-[10px] font-bold transition-premium cursor-pointer">Audit</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
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
          showGlobalMessage('Status updated successfully.');
          if (activePanel === 'staff') loadStaff();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => { indicator.classList.add('hidden'); showGlobalMessage('Failed to update status.', true); });
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
      document.getElementById('passwordModal').classList.add('hidden');
      document.getElementById('passwordModal').classList.remove('flex');
      selectedUserForReset = null;
    }

    function submitPasswordReset() {
      const pwd = document.getElementById('newPasswordInput').value.trim();
      const pwdAlert = document.getElementById('pwdAlert');
      if (pwd.length < 4) {
        pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Password must be at least 4 characters long.";
        pwdAlert.classList.remove('hidden');
        return;
      }
      fetch('/api/admin/user/reset-password', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ userId: selectedUserForReset.userId, userType: selectedUserForReset.userType, newPassword: pwd })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') { showGlobalMessage('Password reset successfully.'); closePasswordModal(); }
        else { pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block"; pwdAlert.innerText = data.message; pwdAlert.classList.remove('hidden'); }
      })
      .catch(() => { pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block"; pwdAlert.innerText = "Request failed."; pwdAlert.classList.remove('hidden'); });
    }

    function confirmDeleteUser(userId, userType, userName) {
      if (confirm(`Permanently delete profile of ${userName} (${userId})? This cannot be undone.`)) {
        const indicator = document.getElementById('loadingIndicator');
        indicator.classList.remove('hidden');
        fetch('/api/admin/user/delete', { method: 'POST', headers: getHeaders(), body: JSON.stringify({ targetId: userId, userType }) })
          .then(res => res.json())
          .then(data => {
            indicator.classList.add('hidden');
            if (data.status === 'SUCCESS') { showGlobalMessage('Profile deleted successfully.'); if (activePanel === 'staff') loadStaff(); }
            else showGlobalMessage(data.message, true);
          })
          .catch(() => { indicator.classList.add('hidden'); showGlobalMessage('Failed to delete.', true); });
      }
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
            if (data.logs.length === 0) { tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-500">No profile history events found.</td></tr>`; return; }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-[10px]";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `<td class="p-3 text-slate-400 font-mono">${date}</td><td class="p-3 font-semibold text-slate-300">${log.performed_by_name || 'System'}</td><td class="p-3"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td><td class="p-3 text-slate-300">${log.details || ''}</td>`;
              tbody.appendChild(tr);
            });
          } else tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Error loading.</td></tr>`;
        })
        .catch(() => tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Failed.</td></tr>`);
    }

    function closeAuditModal() {
      document.getElementById('auditModal').classList.add('hidden');
      document.getElementById('auditModal').classList.remove('flex');
    }

    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">Querying audit logs...</td></tr>`;
      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) { tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">No audit logs found.</td></tr>`; return; }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `<td class="p-4 text-slate-400 font-mono">${date}</td><td class="p-4 font-bold text-slate-300">${log.performed_by_name || 'System'}<br><span class="text-[10px] text-slate-500 font-mono">${log.performed_by || ''}</span></td><td class="p-4 font-bold text-white">${log.target_name}<br><span class="text-[10px] text-blue-400 font-mono">${log.target_id}</span></td><td class="p-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td><td class="p-4 font-mono text-slate-400">${log.ip_address || '-'}</td><td class="p-4 text-slate-300">${log.details || ''}</td>`;
              tbody.appendChild(tr);
            });
          } else tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Error loading logs.</td></tr>`;
        })
        .catch(() => tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Request failed.</td></tr>`);
    }

    function loadSelfSecurityLogs() {
      const tbody = document.getElementById('selfSecurityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">Querying security logs...</td></tr>`;
      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) { tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">No profile action logs recorded.</td></tr>`; return; }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800 text-[10px]";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `<td class="p-3 text-slate-400 font-mono">${date}</td><td class="p-3"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td><td class="p-3 text-slate-300">${log.details || ''}</td>`;
              tbody.appendChild(tr);
            });
          } else tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
        })
        .catch(() => tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Error querying logs.</td></tr>`);
    }

    function openRegisterStaffModal() {
      document.getElementById('registerStaffForm').reset();
      document.getElementById('regStaffAlert').classList.add('hidden');
      const modal = document.getElementById('registerStaffModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeRegisterStaffModal() {
      document.getElementById('registerStaffModal').classList.add('hidden');
      document.getElementById('registerStaffModal').classList.remove('flex');
    }

    function handleRegisterStaff(e) {
      e.preventDefault();
      const alert = document.getElementById('regStaffAlert');
      const spinner = document.getElementById('regStaffSpinner');
      alert.classList.add('hidden');
      spinner.classList.remove('hidden');

      const formData = new FormData();
      formData.append('name', document.getElementById('regStaffName').value);
      formData.append('email', document.getElementById('regStaffEmail').value);
      formData.append('mobileNo', document.getElementById('regStaffMobile').value);
      formData.append('branch', document.getElementById('regStaffBranch').value);
      formData.append('designation', 'Trade_Instructor');
      formData.append('password', document.getElementById('regStaffPassword').value);

      fetch('/register/staff', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl text-[10px] font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "Trade Instructor registered successfully.";
          alert.classList.remove('hidden');
          setTimeout(() => { closeRegisterStaffModal(); loadStaff(); }, 1500);
        } else {
          alert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Request failed.";
        alert.classList.remove('hidden');
      });
    }
  </script>
  @include('partials.support_desk_overlay')
</body>
</html>
