  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const canManageUsers = {{ in_array(strtolower(session('userRole', '')), ['super_admin', 'superadmin', 'admin', 'principal']) ? 'true' : 'false' }};
    const canChangeRoles = {{ in_array(strtolower(session('userRole', '')), ['super_admin', 'superadmin', 'principal']) ? 'true' : 'false' }};
    const isSuperAdmin = {{ in_array(strtolower(session('userRole', '')), ['super_admin', 'superadmin']) ? 'true' : 'false' }};
    let allTodayEventsCache = [];
    let activeEventCategoryFilter = 'ALL';

    document.addEventListener('DOMContentLoaded', () => {
      if (window.lucide) window.lucide.createIcons();

      const urlParams = new URLSearchParams(window.location.search);
      const tab = urlParams.get('tab') || 'dashboard';
      switchPanel(tab);

      loadExecutiveMetrics();
      loadComplianceData();
      loadFlashNoticeStats();
      loadPrincipalEventStats();
      loadProfileDetails();
      loadSystemSettings();
    });

    function switchPanel(panelId) {
      if (!canManageUsers && ['backups', 'audit', 'settings'].includes(panelId)) {
        panelId = 'dashboard';
      }

      const panels = [
        'dashboard', 'all_timetables', 'directory', 'backups', 'audit', 'settings', 
        'prof_activities', 'leave_ledger', 'sf_attendance', 'profile'
      ];
      
      panels.forEach(p => {
        let elId = 'panel' + p.charAt(0).toUpperCase() + p.slice(1);
        if (p === 'all_timetables') elId = 'panelAll_timetables';
        const el = document.getElementById(elId);
        if (el) {
          if (p === panelId) {
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        }
      });

      const titleMap = {
        'dashboard': { title: 'Dashboard Overview', subtitle: 'Campus-wide institutional metrics, faculty compliance, and administrative controls.' },
        'all_timetables': { title: 'All-Department Timetables & Schedules', subtitle: 'Live period schedules, classroom allocations, and faculty assignments across all branches.' },
        'directory': { title: 'User Accounts Directory', subtitle: 'Search, audit, activate, and manage institutional staff and student profiles.' },
        'backups': { title: 'Drive Backups & System Sync', subtitle: 'Automated Google Drive cloud backups and offline SQL dump exports.' },
        'audit': { title: 'System Security Audit Trail', subtitle: 'Detailed chronological lifecycle logs, password resets, and access records.' },
        'settings': { title: 'System Settings & AI Controls', subtitle: 'Configure Gemini AI engine integration and institutional parameters.' },
        'prof_activities': { title: 'Faculty Professional Activities', subtitle: 'FDPs, research publications, guided projects, and curriculum enhancements.' },
        'leave_ledger': { title: 'All-Department Master Leave Ledger', subtitle: 'Multi-stage leave approval trail, departmental balances, and official leave orders.' },
        'sf_attendance': { title: 'SF Staff Attendance Master Log', subtitle: 'Self-Financing faculty face verification punches, campus geofence compliance, and time logs.' },
        'profile': { title: 'Executive Profile & Account Security', subtitle: 'Manage administrative credentials, official contact channels, and system security.' }
      };

      const meta = titleMap[panelId] || titleMap['dashboard'];
      const topTitle = document.querySelector('header h1');
      const topSub = document.querySelector('header p');
      if (topTitle) topTitle.innerText = meta.title;
      if (topSub) topSub.innerText = meta.subtitle;

      if (typeof selectSidebarNav === 'function') {
        selectSidebarNav(panelId);
      }

      if (window.lucide) window.lucide.createIcons();

      if (panelId === 'all_timetables') loadAllDepartmentTimetables();
      if (panelId === 'directory') loadUsers();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'settings') loadSystemSettings();
      if (panelId === 'prof_activities') loadProfActivities();
      if (panelId === 'leave_ledger') loadLeaveLedger();
      if (panelId === 'sf_attendance') loadSfAttendance();
      if (panelId === 'profile') loadProfileDetails();
    }

    function handleAdminSidebarNav(panelId) {
      switchPanel(panelId);
    }

    // -------------------------------------------------------------------------
    // ALL-DEPARTMENT MASTER TIMETABLES LOGIC
    // -------------------------------------------------------------------------
    let allDeptTimetableCache = null;
    let selectedTtDept = 'ALL';
    let selectedTtDay = 'Day 1';
    let selectedTtSem = 'ALL';

    async function loadAllDepartmentTimetables() {
      const container = document.getElementById('ttBatchesListContainer');
      if (container && (!allDeptTimetableCache || !allDeptTimetableCache.timetables || allDeptTimetableCache.timetables.length === 0)) {
        container.innerHTML = `
          <div class="bg-white border border-slate-200 p-8 rounded-2xl text-center text-slate-400">
            <span class="material-symbols-rounded text-4xl block text-blue-500 animate-spin mb-2">sync</span>
            <span class="text-sm font-semibold text-slate-700">Loading master department timetables...</span>
          </div>
        `;
      }

      try {
        const res = await fetch('/api/admin/timetables/all-departments');
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          allDeptTimetableCache = data;
          if (data.active_day_order) {
            const badge = document.getElementById('ttActiveDayOrderBadge');
            if (badge) badge.innerText = data.active_day_order;
            if (!selectedTtDay || selectedTtDay === 'Day 1') {
              selectedTtDay = data.active_day_order;
              updateDayFilterTabsUI();
            }
          }
          renderAllDepartmentTimetablesUI();
        } else {
          if (container) {
            container.innerHTML = `
              <div class="bg-white border border-rose-200 p-8 rounded-2xl text-center text-rose-500">
                <span class="material-symbols-rounded text-4xl block mb-2">error</span>
                <span class="text-sm font-semibold">${data.message || 'Failed to load timetables.'}</span>
              </div>
            `;
          }
        }
      } catch (err) {
        if (container) {
          container.innerHTML = `
            <div class="bg-white border border-slate-200 p-8 rounded-2xl text-center text-slate-400">
              <span class="material-symbols-rounded text-4xl block text-slate-400 mb-2">cloud_off</span>
              <span class="text-sm font-semibold text-slate-600">Failed to connect to timetable service: ${err.message}</span>
            </div>
          `;
        }
      }
    }

    function filterTtDepartment(dept) {
      selectedTtDept = dept;
      document.querySelectorAll('.tt-dept-btn').forEach(btn => {
        if (btn.id === 'ttDeptBtn_' + dept) {
          btn.className = 'tt-dept-btn px-4 py-2 rounded-xl text-sm font-bold transition-all shrink-0 bg-blue-600 text-white shadow-sm';
        } else {
          btn.className = 'tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300';
        }
      });
      renderAllDepartmentTimetablesUI();
    }

    function filterTtDay(day) {
      selectedTtDay = day;
      updateDayFilterTabsUI();
      renderAllDepartmentTimetablesUI();
    }

    function updateDayFilterTabsUI() {
      const dayMap = { 'Day 1': 'Day1', 'Day 2': 'Day2', 'Day 3': 'Day3', 'Day 4': 'Day4', 'Day 5': 'Day5' };
      document.querySelectorAll('.tt-day-btn').forEach(btn => {
        const idKey = dayMap[selectedTtDay] || 'Day1';
        if (btn.id === 'ttDayBtn_' + idKey) {
          btn.className = 'tt-day-btn py-2 px-2 rounded-xl text-xs font-bold transition-all text-center bg-blue-600 text-white shadow-sm';
        } else {
          btn.className = 'tt-day-btn py-2 px-2 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300';
        }
      });
    }

    function filterTtSem(sem) {
      selectedTtSem = sem;
      document.querySelectorAll('.tt-sem-btn').forEach(btn => {
        if (btn.id === 'ttSemBtn_' + sem) {
          btn.className = 'tt-sem-btn py-2 px-1 rounded-xl text-xs font-bold transition-all text-center bg-blue-600 text-white shadow-sm';
        } else {
          btn.className = 'tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300';
        }
      });
      renderAllDepartmentTimetablesUI();
    }

    function renderAllDepartmentTimetablesUI() {
      const container = document.getElementById('ttBatchesListContainer');
      if (!container || !allDeptTimetableCache) return;

      const rawBatches = allDeptTimetableCache.timetables || [];
      const periodTimings = allDeptTimetableCache.period_timings || {
        1: '09:00 - 10:00 AM', 2: '10:00 - 11:00 AM', 3: '11:10 - 12:10 PM',
        4: '01:00 - 02:00 PM', 5: '02:00 - 03:00 PM', 6: '03:00 - 04:00 PM'
      };

      const dayKey = selectedTtDay || 'Day 1';

      // Filter by department and semester
      const filtered = rawBatches.filter(b => {
        const matchesDept = (selectedTtDept === 'ALL') || (b.branch === selectedTtDept) || (b.classroom_id.startsWith(selectedTtDept + '_'));
        const matchesSem  = (selectedTtSem === 'ALL') || (b.semester === selectedTtSem);
        return matchesDept && matchesSem;
      });

      const countEl = document.getElementById('ttTotalBatchesFound');
      if (countEl) {
        countEl.innerText = `${filtered.length} Classroom Batches (${dayKey})`;
      }

      if (filtered.length === 0) {
        container.innerHTML = `
          <div class="bg-white border border-slate-200 p-8 rounded-2xl text-center text-slate-400">
            <span class="material-symbols-rounded text-4xl block text-slate-300 mb-2">event_busy</span>
            <span class="text-sm font-semibold text-slate-600">No classroom timetables found matching selected filters (${selectedTtDept} - ${selectedTtSem}).</span>
          </div>
        `;
        return;
      }

      container.innerHTML = filtered.map(b => {
        const daySchedule = (b.schedule && b.schedule[dayKey]) ? b.schedule[dayKey] : {};
        
        let periodsHtml = '';
        for (let p = 1; p <= 6; p++) {
          const slot = daySchedule[p] || {
            period: p,
            time: periodTimings[p] || '',
            subject_code: 'FREE',
            subject_name: 'Free Period / Library',
            type: 'Free',
            staff_name: '-'
          };

          const isFree = slot.subject_code === 'FREE' || slot.type === 'Free';
          const isLab = slot.type === 'Practical' || slot.type === 'Lab' || slot.type === 'Practicum';
          
          let cardBg = 'bg-white border-slate-200';
          let badgeBg = 'bg-blue-50 text-blue-700 border-blue-200';
          if (isLab) {
            badgeBg = 'bg-purple-50 text-purple-700 border-purple-200';
          } else if (isFree) {
            cardBg = 'bg-slate-50/70 border-slate-200 opacity-75';
            badgeBg = 'bg-slate-100 text-slate-500 border-slate-200';
          }

          periodsHtml += `
            <div class="${cardBg} border rounded-xl p-3 flex flex-col justify-between shadow-2xs hover:border-blue-300 transition-all">
              <div>
                <div class="flex items-center justify-between gap-1 mb-1.5">
                  <span class="text-[11px] font-mono font-bold text-slate-500">Period ${p}</span>
                  <span class="text-[10px] font-mono font-medium text-slate-400">${slot.time || periodTimings[p] || ''}</span>
                </div>
                <div class="mb-1">
                  <span class="px-2 py-0.5 rounded-md text-[11px] font-bold border uppercase tracking-wider ${badgeBg}">
                    ${slot.subject_code}
                  </span>
                </div>
                <p class="text-xs font-semibold text-slate-800 line-clamp-2 leading-tight mt-1" title="${slot.subject_name}">
                  ${slot.subject_name}
                </p>
              </div>
              <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center gap-1.5 text-xs text-slate-600">
                <span class="material-symbols-rounded text-xs text-slate-400">person</span>
                <span class="truncate font-medium text-[11px] text-slate-700">${slot.staff_name || 'Faculty'}</span>
              </div>
            </div>
          `;
        }

        return `
          <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3.5 hover:border-slate-300 transition-all">
            <!-- Batch Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-100 shrink-0">
                  ${b.semester}
                </div>
                <div>
                  <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span>${b.classroom_id}</span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">${b.branch_name || b.branch}</span>
                  </h4>
                  <p class="text-xs text-slate-500 mt-0.5">Semester ${b.semester} • Batch Year: ${b.batch_year} • ${b.subjects_count || 0} Assigned Courses</p>
                </div>
              </div>
            </div>

            <!-- 6 Periods Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
              ${periodsHtml}
            </div>
          </div>
        `;
      }).join('');
    }

    // 1. EXECUTIVE METRICS & DASHBOARD OVERVIEW DATA LOADER (RESTORED PREVIOUS LOGIC)
    async function loadExecutiveMetrics() {
      try {
        const res = await fetch('/api/admin/executive-kpis');
        if (!res.ok) return;
        const data = await res.json();

        // 5 KPI Top Cards
        if (data.total_staff !== undefined) document.getElementById('statTotalStaff').innerText = data.total_staff;
        if (data.total_students !== undefined) document.getElementById('statTotalStudents').innerText = data.total_students;
        if (data.pending_approvals !== undefined) document.getElementById('statPendingApprovals').innerText = data.pending_approvals;
        if (data.total_classrooms !== undefined) document.getElementById('statTotalClassrooms').innerText = data.total_classrooms;
        if (data.academic_pass_rate !== undefined) document.getElementById('execAcademicPassRate').innerText = `${data.academic_pass_rate}% Overall`;

        // Daily Staff Leave Snapshot & Interactive Hover Tooltips
        if (data.leave_breakdown) {
          const lb = data.leave_breakdown;
          document.getElementById('execStaffLeaveTotal').innerText = `${lb.total_on_leave || 0} Active`;
          if (document.getElementById('execLeaveCL')) document.getElementById('execLeaveCL').innerText = lb.CL || 0;
          if (document.getElementById('execLeaveCCL')) document.getElementById('execLeaveCCL').innerText = lb.CCL || 0;
          if (document.getElementById('execLeaveDL')) document.getElementById('execLeaveDL').innerText = lb.DL || 0;
          if (document.getElementById('execLeaveML')) document.getElementById('execLeaveML').innerText = lb.ML || 0;
          if (document.getElementById('execLeaveLOP')) document.getElementById('execLeaveLOP').innerText = lb.LOP || 0;
          if (document.getElementById('execLeaveOTHERS')) document.getElementById('execLeaveOTHERS').innerText = lb.OTHERS || 0;

          // Populate hover popup lists with staff names & department
          if (lb.staff_by_type) {
            ['CL', 'CCL', 'DL', 'ML', 'LOP', 'OTHERS'].forEach(t => {
              const listEl = document.getElementById(`popupList${t}`);
              const countEl = document.getElementById(`popupCount${t}`);
              const staffArr = lb.staff_by_type[t] || [];

              if (countEl) countEl.innerText = `${staffArr.length} Staff`;

              if (listEl) {
                if (staffArr.length > 0) {
                  listEl.innerHTML = staffArr.map(s => `
                    <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-1 text-xs">
                      <span class="font-semibold text-slate-800 truncate">${s.name}</span>
                      <span class="text-[10px] text-blue-600 font-mono shrink-0">${s.dept}</span>
                    </div>
                  `).join('');
                } else {
                  listEl.innerHTML = `<span class="text-slate-400 italic block text-xs">No staff on ${t} today</span>`;
                }
              }
            });
          }
        }

        // Today's Events
        if (data.today_events && data.today_events.length > 0) {
          allTodayEventsCache = data.today_events;
          const badge = document.getElementById('execEventsCountBadge');
          if (badge) badge.innerText = `${data.today_events.length} Scheduled`;

          const modalTotalBadge = document.getElementById('modalEventsTotalBadge');
          if (modalTotalBadge) modalTotalBadge.innerText = `${data.today_events.length} Total`;
          
          const listContainer = document.getElementById('execTodayEventsList');
          if (listContainer) {
            listContainer.innerHTML = data.today_events.slice(0, 2).map(ev => `
              <div class="flex items-center gap-2 truncate text-xs">
                <span class="w-2 h-2 rounded-full ${ev.type === 'Holiday' ? 'bg-amber-500' : (ev.type === 'Exam' ? 'bg-rose-500' : 'bg-sky-500')} shrink-0"></span>
                <span class="truncate font-semibold text-slate-800" title="${ev.title}">${ev.title}</span>
              </div>
            `).join('');
          }

          const counts = data.event_counts || {};
          const total = data.today_events.length;
          if (document.getElementById('evtCnt_ALL')) document.getElementById('evtCnt_ALL').innerText = total;

          ['Departments', 'College', 'NSS', 'NCC', 'IEDC', 'Placement Cell', 'Others'].forEach(cat => {
            const cntEl = document.getElementById(`evtCnt_${cat}`);
            if (cntEl) cntEl.innerText = counts[cat] || 0;
          });
        }
      } catch (err) {
        console.error('KPI fetch error:', err);
      }
    }

    async function loadComplianceData() {
      try {
        const res = await fetch('/api/admin/executive-compliance');
        if (!res.ok) return;
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          if (document.getElementById('execFdpCount')) document.getElementById('execFdpCount').innerText = `${data.total_fdps || 12} Verified`;
          if (data.matrix) {
            data.matrix.forEach(row => {
              const el1 = document.getElementById(`sem_${row.code}_S1`);
              const el3 = document.getElementById(`sem_${row.code}_S3`);
              const el5 = document.getElementById(`sem_${row.code}_S5`);
              const elAvg = document.getElementById(`sem_${row.code}_avg`);
              if (el1) el1.innerText = `${row.sem_s1}%`;
              if (el3) el3.innerText = `${row.sem_s3}%`;
              if (el5) el5.innerText = `${row.sem_s5}%`;
              if (elAvg) elAvg.innerText = `${row.avg_pct}%`;
            });
          }
        }
      } catch (err) {
        console.error('Compliance error:', err);
      }
    }

    async function loadFlashNoticeStats() {
      try {
        const res = await fetch('/api/admin/flash-notices');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.stats) {
          if (document.getElementById('flashNoticeStatSent')) document.getElementById('flashNoticeStatSent').innerText = data.stats.total_sent || 0;
          if (document.getElementById('flashNoticeStatSched')) document.getElementById('flashNoticeStatSched').innerText = data.stats.scheduled_count || 0;
          if (document.getElementById('flashNoticeStatUrgent')) document.getElementById('flashNoticeStatUrgent').innerText = data.stats.urgent_count || 0;
        }
      } catch (err) {}
    }

    async function loadPrincipalEventStats() {
      try {
        const res = await fetch('/api/principal/events');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.stats) {
          if (document.getElementById('principalEventStatCollege')) document.getElementById('principalEventStatCollege').innerText = data.stats.college_wide || 0;
          if (document.getElementById('principalEventStatDept')) document.getElementById('principalEventStatDept').innerText = ((data.stats.dept_specific || 0) + (data.stats.staff_only || 0));
          if (document.getElementById('principalEventStatSpecial')) document.getElementById('principalEventStatSpecial').innerText = data.stats.special_groups || 0;
        }
      } catch (err) {}
    }

    // 2. USER ACCOUNTS DIRECTORY LOADER & PAGINATION CONTROLLER
    let userDirectoryData = [];
    let userDirectoryPage = 1;
    let userDirectoryPageSize = 25;
    let userSearchDebounce = null;

    function onUserSearchInput() {
      clearTimeout(userSearchDebounce);
      userSearchDebounce = setTimeout(() => {
        loadUsers();
      }, 250);
    }

    async function loadUsers() {
      const search = document.getElementById('filterSearch')?.value || '';
      const branch = document.getElementById('filterBranch')?.value || '';
      const role = document.getElementById('filterRole')?.value || '';
      const status = document.getElementById('filterStatus')?.value || '';

      const tbody = document.getElementById('userTableBody');
      if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400 font-medium"><div class="flex items-center justify-center gap-2"><span class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></span><span>Loading directory accounts...</span></div></td></tr>`;

      try {
        const query = new URLSearchParams({ search, branch, role, status }).toString();
