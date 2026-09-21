  <script>

    let activePanel = "roster";
    let selectedUserForReset = null;

    document.addEventListener("DOMContentLoaded", () => {
      // Check if routed directly to mentoring
      if (sessionStorage.getItem('openMentoring') === 'true') {
        sessionStorage.removeItem('openMentoring');
        activePanel = 'mentoring';
      }

      loadSupervisedClassroomHeader();

      if (activePanel === 'roster') loadUsers();
      if (activePanel === 'audit') loadAuditTrail();
      if (activePanel === 'profile') loadSelfSecurityLogs();
      if (activePanel === 'mentoring') {
        switchPanel('mentoring'); // Ensures UI is updated
      }
    });

    function loadSupervisedClassroomHeader() {
      fetch('/api/tutor/classroom/{{ session('userId') }}')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            window.supervisedClassroomId = data.classroomId;
            window.supervisedBatchYear = data.batchYear;
            window.supervisedCurrentSemester = data.currentSemester || 1;
            window.supervisedIsClassTutor = data.isClassTutor;
            window.supervisedTutorName = data.tutorName || 'Not Assigned';
            window.supervisedMentorName = data.mentorName || 'Not Assigned';

            const titleEl = document.getElementById('supervisedClassroomTitle');
            if (titleEl) {
              titleEl.innerText = `Supervised Classroom Directory — ${data.classroomId} (Semester S-${data.currentSemester || 1})`;
            }
            
            const printSemSelect = document.getElementById('printSemesterSelect');
            if (printSemSelect && data.currentSemester) {
              printSemSelect.value = 'S' + data.currentSemester;
            }
          }
        });
    }

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    const loadedPanels = {};

    function switchPanel(panelId, forceRefresh = false) {
      activePanel = panelId;
      
      const panels = ['roster', 'rollNumbers', 'audit', 'profile', 'mentoring', 'activity', 'leaveApproval'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) {
            nav.className = "tutor-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs cursor-pointer";
          }
        } else {
          if (el) el.classList.add('hidden');
          if (nav) {
            nav.className = "tutor-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap text-slate-600 hover:text-slate-900 hover:bg-slate-50 border border-transparent cursor-pointer";
          }
        }
      });

      const titles = {
        'roster': 'Supervised Class Roster',
        'rollNumbers': 'Assign Class Roll Numbers',
        'audit': 'Classroom Audit Trail',
        'profile': 'My Tutor Profile',
        'mentoring': 'Mentoring Batches & Splitter',
        'activity': 'Activity Points Verification',
        'leaveApproval': 'Leave Approval & Mentorship Reports'
      };
      
      const titleEl = document.getElementById('panelTitle') || document.querySelector('.topbar-title') || document.querySelector('header h1');
      if (titleEl) titleEl.innerText = titles[panelId] || 'Tutor Console';

      try {
        const url = new URL(window.location);
        url.searchParams.set('panel', panelId);
        url.searchParams.delete('tab');
        window.history.replaceState({}, '', url);
      } catch(e) {}

      if (!loadedPanels[panelId] || forceRefresh) {
        loadedPanels[panelId] = true;
        if (panelId === 'roster') loadUsers();
        if (panelId === 'rollNumbers') loadTutorStudents();
        if (panelId === 'audit') loadAuditTrail();
        if (panelId === 'profile') loadSelfSecurityLogs();
        if (panelId === 'mentoring') initMentoringPanel();
        if (panelId === 'activity') loadActivityClaims();
        if (panelId === 'leaveApproval') loadClassroomLeaves();
      }
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border-red-900 block shadow-sm";
      } else {
        alert.className = "p-4 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border-green-900 block shadow-sm";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    function toggleRoster() {
      const content = document.getElementById('rosterContent');
      const icon = document.getElementById('rosterIcon');
      content.classList.toggle('hidden');
      icon.classList.toggle('rotate-180');
    }

    let userSearchTimer = null;
    function debouncedLoadUsers() {
      clearTimeout(userSearchTimer);
      userSearchTimer = setTimeout(loadUsers, 250);
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      const search = document.getElementById('filterSearch').value;
      const status = document.getElementById('filterStatus').value;

      const url = `/api/admin/users?search=${encodeURIComponent(search)}&role=student&status=${status}`;

      fetch(url)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            renderUsersGrid(data.users);
          }
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    let currentRosterLimit = 25;
    let allRosterUsers = [];

    function renderUsersGrid(users, limit = 25) {
      allRosterUsers = users;
      currentRosterLimit = limit;
      const tbody = document.getElementById('usersTableBody');
      tbody.innerHTML = "";

      const displayedUsers = users.slice(0, currentRosterLimit);

      if (users.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="9" class="p-8 text-center text-slate-500 font-medium font-sans">
              No classroom students found.
            </td>
          </tr>
        `;
        return;
      }

      displayedUsers.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-100 hover:bg-slate-50/80 transition-all whitespace-nowrap";

        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>`;
        if (user.status === 'Approved') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Approved</span>`;
        } else if (user.status === 'Suspended') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">Suspended</span>`;
        }

        let toggleButton = '';
        if (user.status === 'Pending') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-xs font-semibold text-white transition-all cursor-pointer shadow-2xs">
              Approve
            </button>
          `;
        } else if (user.status === 'Approved') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Suspended')" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg text-xs font-semibold text-amber-700 transition-all cursor-pointer">
              Suspend
            </button>
          `;
        } else if (user.status === 'Suspended') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-xs font-semibold text-emerald-700 transition-all cursor-pointer">
              Activate
            </button>
          `;
        }

        const initials = user.name ? user.name.split(' ').map(n => n[0]).filter(Boolean).slice(0, 2).join('').toUpperCase() : 'ST';
        const avatarHtml = user.photo_url 
          ? `<img src="${user.photo_url}" class="w-9 h-9 rounded-xl object-cover border border-slate-200 shadow-2xs shrink-0">` 
          : `<div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 font-bold text-xs flex items-center justify-center border border-blue-200 shadow-2xs shrink-0">${initials}</div>`;

        tr.innerHTML = `
          <td class="p-3.5 pl-5 flex items-center gap-3">
            ${avatarHtml}
            <div>
              <span class="font-bold text-slate-900 block text-sm">${user.name}</span>
              <span class="text-xs text-slate-500 block font-normal">${user.email || 'No email provided'}</span>
            </div>
          </td>
          <td class="p-3.5 font-mono font-semibold text-slate-600 text-xs">${user.id}</td>
          <td class="p-3.5">
            <button onclick="editSbteRegNo('${user.id}', '${user.sbte_reg_no || ''}')" class="text-blue-600 hover:text-blue-700 font-semibold font-mono text-xs hover:underline cursor-pointer" title="Click to Edit SBTE No">
              ${user.sbte_reg_no || '[Add SBTE No]'}
            </button>
          </td>
          <td class="p-3.5"><span class="font-bold font-mono text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md border border-slate-200">${user.branch}</span></td>
          <td class="p-3.5">
            ${user.type === 'student' ? `
              <button onclick="editStudentSemester('${user.id}', '${user.semester || 'S1'}')" class="text-blue-600 hover:text-blue-700 font-semibold text-xs hover:underline cursor-pointer" title="Click to Edit Semester">
                ${user.semester || 'S1'}
              </button>
            ` : '<span class="text-slate-500 font-medium text-xs">N/A</span>'}
          </td>
          <td class="p-3.5 text-xs text-slate-600 font-medium">${user.role}</td>
          <td class="p-3.5 text-xs">${statusBadge}</td>
          <td class="p-3.5">
            ${user.type === 'student' ? `
              <select onchange="updateAcademicStatusDirectly('${user.id}', this.value)" class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1 text-xs font-semibold outline-none focus:border-blue-600 focus:bg-white cursor-pointer ${
                user.academic_status === 'Active' ? 'text-emerald-700 font-semibold' :
                user.academic_status === 'Discontinued' ? 'text-amber-700 font-semibold' :
                'text-rose-700 font-semibold'
              }">
                <option value="Active" ${user.academic_status === 'Active' ? 'selected' : ''}>Active</option>
                <option value="Discontinued" ${user.academic_status === 'Discontinued' ? 'selected' : ''}>Discontinued</option>
                <option value="TC Issued" ${user.academic_status === 'TC Issued' ? 'selected' : ''}>TC Issued</option>
              </select>
            ` : '<span class="text-slate-500 font-medium text-xs">N/A</span>'}
          </td>
          <td class="p-3.5 pr-5 text-right space-x-1.5 text-xs">
            ${toggleButton}
            <button onclick="triggerPasswordReset('${user.id}', '${user.type}', '${user.name}')" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg text-xs font-semibold transition-all cursor-pointer">
              Reset Pwd
            </button>
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold transition-all cursor-pointer" title="View Audit Trail">
              Audit
            </button>
            <button onclick="confirmDeleteUser('${user.id}', '${user.type}', '${user.name}')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-semibold transition-all cursor-pointer" title="Delete Student">
              Delete
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });

      if (allRosterUsers.length > currentRosterLimit) {
        const loadMoreTr = document.createElement('tr');
        loadMoreTr.id = "rosterLoadMoreRow";
        loadMoreTr.className = "bg-slate-50/50 border-b border-slate-100";
        loadMoreTr.innerHTML = `
          <td colspan="9" class="p-3 text-center">
            <button type="button" onclick="renderUsersGrid(allRosterUsers, currentRosterLimit + 25)" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition-all cursor-pointer inline-flex items-center gap-1.5">
              <span>Show More Students</span>
              <span class="text-slate-400 font-normal">(${currentRosterLimit} of ${allRosterUsers.length})</span>
            </button>
          </td>
        `;
        tbody.appendChild(loadMoreTr);
      }
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
          showGlobalMessage('Student status updated successfully.');
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

    function editSbteRegNo(regNo, currentVal) {
      let newSbte = prompt("Enter new SBTE Registration Number for " + regNo + ":", currentVal);
      if (newSbte === null) return;
      
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      
      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ sbte_reg_no: newSbte })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('SBTE Register Number updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => indicator.classList.add('hidden'));
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
        pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
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
          pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          pwdAlert.innerText = data.message;
          pwdAlert.classList.remove('hidden');
        }
      })
      .catch(() => {
        pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Request failed.";
        pwdAlert.classList.remove('hidden');
      });
    }
