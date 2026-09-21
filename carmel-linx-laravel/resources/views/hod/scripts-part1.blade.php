  <script>
    window.isPrincipalView = @json($isPrincipalMode);
    window.branchOverride = @json($activeBranch);

    if (window.isPrincipalView && window.branchOverride) {
      const originalFetch = window.fetch;
      window.fetch = function(input, init) {
        let url = typeof input === 'string' ? input : input.url;
        if (url.startsWith('/api/')) {
          const separator = url.includes('?') ? '&' : '?';
          url = `${url}${separator}branch=${window.branchOverride}`;
        }
        if (typeof input === 'string') {
          return originalFetch(url, init);
        } else {
          const newRequest = new Request(url, input);
          return originalFetch(newRequest, init);
        }
      };
    }

    let activePanel = @json($initialPanel);
    let selectedUserForReset = null;
    let activeBatchId = null;
    let deptStaffCache = [];

    function handleHodSidebarNav(panelId) {
      switchPanel(panelId);
      if (typeof selectSidebarNav === 'function') {
        selectSidebarNav(panelId);
      }
      try {
        const url = new URL(window.location);
        url.searchParams.set('panel', panelId);
        url.searchParams.delete('tab');
        window.history.replaceState({}, '', url);
      } catch (e) {}
    }
    window.handleHodSidebarNav = handleHodSidebarNav;

    function syncSubjectTypeOptions(revision, preselectedValue = null) {
      const typeSelect = document.getElementById('subjectType');
      if (!typeSelect) return;

      const r21Options = [
        { value: "Theory", text: "Theory" },
        { value: "Practical / Lab", text: "Practical / Lab" },
        { value: "Practicum", text: "Practicum" },
        { value: "Project Based Theory", text: "Project Based Theory" },
        { value: "Seminar", text: "Seminar" },
        { value: "Project", text: "Project" }
      ];

      const r26Options = [
        { value: "Theory Courses", text: "Theory Courses" },
        { value: "Project Based Learning", text: "Project Based Learning (PBL)" },
        { value: "Drawing Courses", text: "Drawing Courses" },
        { value: "Practicum Courses", text: "Practicum Courses" },
        { value: "Practicum Courses under Basic Science & Humanities category", text: "Practicum Courses (Basic Science & Humanities)" },
        { value: "Laboratory/Workshop Courses", text: "Laboratory/Workshop Courses" },
        { value: "Major Project-Phase II", text: "Major Project-Phase II" },
        { value: "Seminar / Minor Project / Major Project-Phase I", text: "Seminar / Minor Project / Major Project-Phase I" },
        { value: "Summer Internship/ Digital 101 Course (Skill Enhancement Course)", text: "Summer Internship/ Digital 101 Course" }
      ];

      typeSelect.innerHTML = '';
      const opts = (revision === 'REV2026') ? r26Options : r21Options;
      opts.forEach(opt => {
        const o = document.createElement('option');
        o.value = opt.value;
        o.textContent = opt.text;
        typeSelect.appendChild(o);
      });

      if (preselectedValue) {
        typeSelect.value = preselectedValue;
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      const urlParams = new URLSearchParams(window.location.search);
      const urlTab = urlParams.get('tab') || urlParams.get('panel') || (window.location.hash ? window.location.hash.replace('#', '') : null);
      if (urlTab && ['batches', 'directory', 'subjects', 'audit', 'leave_ledger', 'prof_activities', 'report_centre', 'profile'].includes(urlTab)) {
        activePanel = urlTab;
      }
      switchPanel(activePanel);
      // Pre-load dept staff for batch modals
      loadDeptStaffCache();
      checkTodaySeminars();

      const revEl = document.getElementById('subjectRevisionYear');
      if (revEl) {
        revEl.addEventListener('change', function() {
          syncSubjectTypeOptions(this.value);
        });
      }
    });

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function switchPanel(panelId) {
      if (!panelId) panelId = 'batches';
      activePanel = panelId;
      const panels = ['batches', 'directory', 'subjects', 'audit', 'leave_ledger', 'prof_activities', 'report_centre', 'profile'];
      
      panels.forEach(id => {
        let elId = 'panel' + id.charAt(0).toUpperCase() + id.slice(1);
        if (id === 'leave_ledger') elId = 'panelLeave_ledger';
        if (id === 'prof_activities') elId = 'panelProf_activities';
        if (id === 'report_centre') elId = 'panelReport_centre';
        const el = document.getElementById(elId);
        if (el) {
          if (id === panelId) {
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        }
      });

      if (typeof selectSidebarNav === 'function') {
        selectSidebarNav(panelId);
      }

      const titles = {
        'batches': { title: 'Batch & Class Management', subtitle: 'Manage admission-year batches, class tutors, batch mentors, and semester progression.' },
        'directory': { title: 'User Accounts Directory', subtitle: 'Filter, search, audit, and manage profile lifecycle states for students and staff in your branch.' },
        'subjects': { title: 'Subject & Staff Allocation', subtitle: 'Map curriculum subjects to batches per semester and assign staff across departments.' },
        'audit': { title: 'Department Audit Trail', subtitle: 'Lifecycle events, status updates, registrations, and actions performed within the branch.' },
        'report_centre': { title: 'Report Centre', subtitle: 'Centralized academic, faculty, compliance, and accreditation reporting workspace.' },
        'leave_ledger': { title: 'Staff Leave Master Ledger & Report Center', subtitle: 'Multi-stage approval audit trail, departmental leave balances, and official leave orders.' },
        'prof_activities': { title: 'Professional Activities', subtitle: 'Faculty development, publications, workshops, projects, and academic contributions.' },
        'profile': { title: 'My Profile & Security Settings', subtitle: 'Manage your personal account credentials, profile avatar, and view security activity logs.' }
      };

      const info = titles[panelId] || { title: 'Overview', subtitle: '' };
      const titleEl = document.getElementById('panelTitle');
      const subtitleEl = document.getElementById('panelSubtitle');
      if (titleEl) titleEl.innerText = info.title;
      if (subtitleEl) subtitleEl.innerText = info.subtitle;

      if (panelId === 'batches') loadBatches();
      if (panelId === 'directory') loadUsers();
      if (panelId === 'subjects') loadBatchesForSubjects();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'leave_ledger') loadLeaveLedger();
      if (panelId === 'prof_activities') {
        loadProfActivities();
        toggleProfActFields('fdp_attended');
      }
      if (panelId === 'profile') loadSelfSecurityLogs();

      if (window.initLucide) window.initLucide();
    }
    window.switchPanel = switchPanel;

    // ═════════════════════════════════════════════════════════════════════════
    // REPORT CENTRE MODAL HANDLERS
    // ═════════════════════════════════════════════════════════════════════════
    function openAttendanceModal() {
      const modal = document.getElementById('attendanceModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        if (window.initLucide) window.initLucide();
      }
    }
    window.openAttendanceModal = openAttendanceModal;

    function closeAttendanceModal() {
      const modal = document.getElementById('attendanceModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }
    window.closeAttendanceModal = closeAttendanceModal;

    function printAttendanceSummary() {
      const batchEl = document.getElementById('selectAttendanceBatch');
      const typeEl = document.getElementById('selectAttendanceReportType');
      const batchId = batchEl ? batchEl.value : '';
      const reportType = typeEl ? typeEl.value : 'coverage';
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeAttendanceModal();
      window.open('/hod/attendance-summary/print?classroom_id=' + encodeURIComponent(batchId) + '&report_type=' + encodeURIComponent(reportType), '_blank');
    }
    window.printAttendanceSummary = printAttendanceSummary;

    function openRemedialModal() {
      const modal = document.getElementById('remedialModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        if (window.initLucide) window.initLucide();
      }
    }
    window.openRemedialModal = openRemedialModal;

    function closeRemedialModal() {
      const modal = document.getElementById('remedialModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }
    window.closeRemedialModal = closeRemedialModal;

    function printRemedialReport() {
      const batchEl = document.getElementById('selectRemedialBatch');
      const batchId = batchEl ? batchEl.value : '';
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeRemedialModal();
      window.open('/hod/remedial-report/print?classroom_id=' + encodeURIComponent(batchId), '_blank');
    }
    window.printRemedialReport = printRemedialReport;

    function openCourseFilesModal() {
      const modal = document.getElementById('courseFilesModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        if (window.initLucide) window.initLucide();
      }
    }
    window.openCourseFilesModal = openCourseFilesModal;

    function closeCourseFilesModal() {
      const modal = document.getElementById('courseFilesModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }
    window.closeCourseFilesModal = closeCourseFilesModal;

    function printCourseFilesReport() {
      const batchEl = document.getElementById('selectCourseFilesBatch');
      const batchId = batchEl ? batchEl.value : '';
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeCourseFilesModal();
      window.open('/hod/course-files-report/print?classroom_id=' + encodeURIComponent(batchId), '_blank');
    }
    window.printCourseFilesReport = printCourseFilesReport;

    function openActivityPointsModal() {
      const modal = document.getElementById('activityPointsModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        if (window.initLucide) window.initLucide();
      }
    }
    window.openActivityPointsModal = openActivityPointsModal;

    function closeActivityPointsModal() {
      const modal = document.getElementById('activityPointsModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }
    window.closeActivityPointsModal = closeActivityPointsModal;

    function printActivityPointsReport() {
      const batchEl = document.getElementById('selectActivityBatch');
      const semEl = document.getElementById('selectActivitySemester');
      const batchId = batchEl ? batchEl.value : '';
      const sem = semEl ? semEl.value : 'all';
      if (!batchId) {
        alert('Please select a batch.');
        return;
      }
      closeActivityPointsModal();
      window.open('/hod/activity-points-report/print?classroom_id=' + encodeURIComponent(batchId) + '&semester=' + encodeURIComponent(sem), '_blank');
    }
    window.printActivityPointsReport = printActivityPointsReport;

    function loadBatchesForSubjects() {
      // Just populate the dropdown if it's empty
      const select = document.getElementById('subjectBatchSelect');
      if (select && select.options.length > 1) {
        // Already loaded, just refresh the subjects table
        loadSubjects();
        return;
      }
      
      const p1 = fetch('/api/hod/batches').then(res => res.json()).catch(() => ({status: 'ERROR', batches: []}));
      const p2 = fetch('/api/r26/hod/batches').then(res => res.json()).catch(() => ({status: 'ERROR', batches: []}));

      Promise.all([p1, p2])
        .then(([res1, res2]) => {
          select.innerHTML = '<option value="">-- Choose a Classroom --</option>';
          let b1 = (res1.status === 'SUCCESS' && Array.isArray(res1.batches)) ? res1.batches : [];
          let b2 = (res2.status === 'SUCCESS' && Array.isArray(res2.batches)) ? res2.batches : [];
          let combined = b1.concat(b2);
          
          combined.sort((x, y) => y.batch_year - x.batch_year);

          combined.forEach(b => {
            select.innerHTML += `<option value="${b.classroom_id}">${b.classroom_id} (Year ${b.batch_year})${b.is_r26 || b.batch_year === 2026 ? ' [REV2026]' : ''}</option>`;
          });
        });
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      if (!alert) return;
      
      const isSuccess = !isError;
      alert.className = `fixed top-6 right-6 z-[9999] max-w-md w-full shadow-2xl rounded-2xl p-4 flex items-start gap-3.5 border backdrop-blur-md transition-all duration-300 transform translate-y-0 opacity-100 ${
        isSuccess 
          ? 'bg-white/95 border-emerald-200 shadow-emerald-950/10 text-slate-800 ring-1 ring-emerald-500/10' 
          : 'bg-white/95 border-rose-200 shadow-rose-950/10 text-slate-800 ring-1 ring-rose-500/10'
      }`;

      alert.innerHTML = `
        <div class="w-9 h-9 rounded-xl ${isSuccess ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-rose-500 shadow-rose-500/20'} text-white flex items-center justify-center flex-shrink-0 shadow-sm">
          ${isSuccess 
            ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>'
            : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>'
          }
        </div>
        <div class="flex-1 min-w-0 pt-0.5">
          <h5 class="text-sm font-bold ${isSuccess ? 'text-slate-900' : 'text-rose-950'} leading-tight">
            ${isSuccess ? 'Success' : 'Notice / Error'}
          </h5>
          <p class="text-sm font-medium text-slate-600 mt-0.5 leading-relaxed">${msg}</p>
        </div>
        <button type="button" onclick="document.getElementById('globalAlert').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition cursor-pointer" title="Dismiss">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      `;

      alert.classList.remove('hidden');

      if (window._globalAlertTimeout) clearTimeout(window._globalAlertTimeout);
      window._globalAlertTimeout = setTimeout(() => {
        alert.classList.add('hidden');
      }, 5000);
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      const search = document.getElementById('filterSearch')?.value || '';
      const role = document.getElementById('filterRole')?.value || '';
      const status = document.getElementById('filterStatus')?.value || '';
      const branch = '{{ $activeBranch }}';

      const url = `/api/admin/users?search=${encodeURIComponent(search)}&role=${role}&status=${status}&branch=${encodeURIComponent(branch)}`;

      fetch(url)
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            renderUsersGrid(data.users);
          }
        })
        .catch(() => {
          if (indicator) indicator.classList.add('hidden');
        });
    }

    function renderUsersGrid(users) {
      const tbody = document.getElementById('usersTableBody');
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="8" class="p-12 text-center text-slate-500 font-medium text-sm">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                <i data-lucide="users" class="w-5 h-5"></i>
              </div>
              <p class="font-semibold text-slate-800">No matching profiles found</p>
              <p class="text-xs text-slate-400 mt-0.5">Try adjusting your search filters above.</p>
            </td>
          </tr>
        `;
        if (window.initLucide) window.initLucide();
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-100 hover:bg-slate-50/70 transition-colors";

        let statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>`;
        if (user.status === 'Approved') {
          statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Approved</span>`;
        } else if (user.status === 'Suspended') {
          statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">Suspended</span>`;
        }

        let toggleButton = '';
        if (user.id !== "{{ session('userId') }}") {
          if (user.status === 'Pending') {
            toggleButton = `
              <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                Approve
              </button>
            `;
          } else if (user.status === 'Approved') {
            toggleButton = `
              <button onclick="changeStatus('${user.id}', '${user.type}', 'Suspended')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                Suspend
              </button>
            `;
          } else if (user.status === 'Suspended') {
            toggleButton = `
              <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                Activate
              </button>
            `;
          }
        }

        let roleCol = user.role;

        tr.innerHTML = `
          <td class="p-3.5 flex items-center gap-3">
            <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-9 h-9 rounded-full object-cover border border-slate-200 shadow-2xs shrink-0">
            <div class="min-w-0">
              <span class="font-semibold text-slate-900 block text-sm truncate">${user.name}</span>
              <span class="text-xs text-slate-500 block truncate">${user.email}</span>
            </div>
          </td>
          <td class="p-3.5 font-mono font-medium text-slate-700 text-sm whitespace-nowrap">${user.id}</td>
          <td class="p-3.5"><span class="font-mono font-semibold text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md border border-slate-200">${user.branch}</span></td>
          <td class="p-3.5">
            ${user.type === 'student' ? `
              <button onclick="editStudentSemester('${user.id}', '${user.semester || 'S1'}')" class="text-blue-600 hover:text-blue-800 font-semibold text-sm cursor-pointer underline" title="Click to Edit Semester">
                ${user.semester || 'S1'}
