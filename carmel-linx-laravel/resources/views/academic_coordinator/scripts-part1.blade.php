      <input type="hidden" id="rejectLeaveId">
      <div class="space-y-3">
        <p class="text-xs text-slate-400">Please enter rejection remarks for this leave request:</p>
        <textarea id="rejectRemarksInput" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white outline-none focus:border-rose-500" placeholder="Specify reason for rejection..."></textarea>
      </div>

      <div class="flex gap-3 pt-2">
        <button onclick="closeRejectModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Cancel</button>
        <button onclick="submitRejection()" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer">Reject Leave</button>
      </div>
    </div>
  </div>

  <script>
    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      loadPendingApprovals();
    });

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      const mobileAlert = document.getElementById('mobileGlobalAlert');

      if (alert) {
        alert.classList.remove('hidden');
        alert.className = isError ? "p-4 rounded-xl font-bold bg-rose-950/40 text-rose-400 border-rose-900 block text-xs" : "p-4 rounded-xl font-bold bg-emerald-950/40 text-emerald-400 border-emerald-900 block text-xs";
        alert.innerText = msg;
        setTimeout(() => alert.classList.add('hidden'), 5000);
      }

      if (mobileAlert) {
        mobileAlert.classList.remove('d-none');
        mobileAlert.className = isError ? "alert alert-danger py-2 px-3 mb-3 font-bold text-xs rounded-3" : "alert alert-success py-2 px-3 mb-3 font-bold text-xs rounded-3";
        mobileAlert.innerText = msg;
        setTimeout(() => mobileAlert.classList.add('d-none'), 5000);
      }
    }

    function switchMobileTab(tabId) {
      const tabs = ['approvals', 'directory', 'reports', 'security'];
      tabs.forEach(t => {
        const el = document.getElementById('mobileTab' + t.charAt(0).toUpperCase() + t.slice(1));
        const nav = document.getElementById('mobileNav' + t.charAt(0).toUpperCase() + t.slice(1));
        if (t === tabId) {
          if (el) el.classList.remove('d-none');
          if (nav) nav.classList.add('active');
        } else {
          if (el) el.classList.add('d-none');
          if (nav) nav.classList.remove('active');
        }
      });

      if (tabId === 'approvals') loadPendingApprovals();
      if (tabId === 'directory') loadUsers();
      if (tabId === 'reports') loadLeaveReports();
      if (tabId === 'security') loadSelfSecurityLogs();
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'directory', 'reports', 'security'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'dashboard': 'Academic Coordinator Overview',
        'directory': 'Self-Financing Staff Directory',
        'reports': 'Staff Leave Master Ledger & Reports',
        'security': 'My Profile Security Log'
      };
      if (document.getElementById('panelTitle')) document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'dashboard') loadPendingApprovals();
      if (panelId === 'directory') loadUsers();
      if (panelId === 'reports') loadLeaveReports();
      if (panelId === 'security') loadSelfSecurityLogs();
    }

    function loadPendingApprovals() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch('/api/staff/leave/pending-approvals')
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            if (document.getElementById('statPendingLeave')) document.getElementById('statPendingLeave').innerText = data.approvals.length;
            if (document.getElementById('mobilePendingBadge')) document.getElementById('mobilePendingBadge').innerText = data.approvals.length;
            renderPendingTable(data.approvals);
            renderMobilePendingCards(data.approvals);
          }
        })
        .catch(() => { if (indicator) indicator.classList.add('hidden'); });
    }

    function renderPendingTable(items) {
      const tbody = document.getElementById('pendingLeaveTableBody');
      if (!tbody) return;
      tbody.innerHTML = '';

      if (items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="p-8 text-center text-slate-500 font-bold">No pending leave applications requiring Academic Coordinator approval.</td></tr>`;
        return;
      }

      items.forEach(req => {
        const tr = document.createElement('tr');
        tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium';

        let datesText = req.start_date;
        if (req.end_date && req.end_date !== req.start_date) {
          datesText += ` to ${req.end_date}`;
        }
        if (req.ccl_date) {
          datesText += `<br><span class="text-[10px] text-amber-400 font-mono">CCL Date: ${req.ccl_date}</span>`;
        }

        let sessionBadge = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${req.session}</span>`;

        tr.innerHTML = `
          <td class="p-3 font-bold text-slate-100">
            ${req.staff_name}
            <span class="block text-[10px] font-normal text-slate-400">${req.designation}</span>
          </td>
          <td class="p-3"><span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">${req.department}</span></td>
          <td class="p-3"><span class="px-2 py-0.5 rounded font-bold text-[10px] bg-purple-500/10 text-purple-300 border border-purple-500/20">${req.leave_category} (${req.total_days}d)</span></td>
          <td class="p-3 font-mono text-slate-300 text-xs">${datesText}</td>
          <td class="p-3">${sessionBadge}</td>
          <td class="p-3 max-w-xs truncate">
            <span class="text-slate-200 block truncate" title="${req.reason}">${req.reason}</span>
            <span class="text-[10px] text-slate-400 block">${req.work_arrangement_status || 'Arrangement done'}</span>
          </td>
          <td class="p-3">
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Approved by ${req.hod_name || 'HOD'}</span>
          </td>
          <td class="p-3 text-right space-x-1">
            <button onclick="approveLeave(${req.id})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs transition-premium cursor-pointer shadow-sm">
              Approve
            </button>
            <button onclick="openRejectModal(${req.id})" class="px-3 py-1.5 bg-rose-950/50 hover:bg-rose-900 border border-rose-800 text-rose-300 rounded-lg font-bold text-xs transition-premium cursor-pointer shadow-sm">
              Reject
            </button>
            <a href="/staff/leave/${req.id}/pdf" target="_blank" class="px-2 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-xs transition-premium no-underline inline-flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">picture_as_pdf</span> PDF
            </a>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function renderMobilePendingCards(items) {
      const container = document.getElementById('mobilePendingApprovalsContainer');
      if (!container) return;

      if (items.length === 0) {
        container.innerHTML = `<small class="text-secondary d-block py-2">No pending leave requests in your approval queue.</small>`;
        return;
      }

      let html = '';
      items.forEach(req => {
        const cclText = req.ccl_date ? ` &bull; <span class="text-info font-mono">CCL Date: ${req.ccl_date}</span>` : '';
        html += `
          <div class="p-2.5 rounded-3 border border-warning border-opacity-30 bg-slate-900 mb-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <strong class="text-white small">${req.staff_name} (${req.department})</strong>
              <span class="badge bg-warning text-dark small">${req.leave_category}</span>
            </div>
            <small class="text-secondary d-block mb-1" style="font-size:0.72rem;">
              ${req.start_date} ${req.end_date && req.end_date !== req.start_date ? ' to ' + req.end_date : ''} (${req.session}) &bull; ${req.total_days} Day(s)${cclText}
            </small>
            <div class="text-slate-300 small italic mb-2" style="font-size:0.75rem;">"${req.reason}"</div>
            <div class="d-flex gap-2">
              <button onclick="approveLeave(${req.id})" class="btn btn-sm btn-success py-0.5 px-3 flex-grow-1" style="font-size:0.72rem;">
                <i class="fa-solid fa-check me-1"></i> Approve
              </button>
              <button onclick="openRejectModal(${req.id})" class="btn btn-sm btn-outline-danger py-0.5 px-2" style="font-size:0.72rem;">
                <i class="fa-solid fa-xmark me-1"></i> Reject
              </button>
              <a href="/staff/leave/${req.id}/pdf" target="_blank" class="btn btn-sm btn-outline-light py-0.5 px-2" style="font-size:0.72rem;">
                <i class="fa-solid fa-file-pdf"></i>
              </a>
            </div>
          </div>
        `;
      });
      container.innerHTML = html;
    }

    function approveLeave(leaveId) {
      if (!confirm("Are you sure you want to approve this leave application? It will move to Principal for final approval.")) return;

      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch('/api/staff/leave/process-approval', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          leave_id: leaveId,
          stage: 'Coordinator',
          action: 'Approved',
          remarks: 'Approved by Academic Coordinator'
        })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Leave request successfully approved!');
          loadPendingApprovals();
        } else {
          showGlobalMessage(data.message || 'Approval failed.', true);
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
        showGlobalMessage('Network error processing approval.', true);
      });
    }

    function openRejectModal(leaveId) {
      document.getElementById('rejectLeaveId').value = leaveId;
      document.getElementById('rejectRemarksInput').value = '';
      document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
      document.getElementById('rejectModal').classList.add('hidden');
    }

    function submitRejection() {
      const leaveId = document.getElementById('rejectLeaveId').value;
      const remarks = document.getElementById('rejectRemarksInput').value.trim();

      if (!remarks) {
        alert("Please enter rejection remarks.");
        return;
      }

      closeRejectModal();
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch('/api/staff/leave/process-approval', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          leave_id: leaveId,
          stage: 'Coordinator',
          action: 'Rejected',
          remarks: remarks
        })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Leave request rejected.');
          loadPendingApprovals();
        } else {
          showGlobalMessage(data.message || 'Rejection failed.', true);
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
        showGlobalMessage('Network error processing rejection.', true);
      });
    }

    function loadLeaveReports() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      const branch = document.getElementById('desktopReportBranch')?.value || document.getElementById('mobileReportBranch')?.value || '';
      const category = document.getElementById('desktopReportCategory')?.value || document.getElementById('mobileReportCategory')?.value || '';
      const year = document.getElementById('desktopReportYear')?.value || '2026';

      let url = `/staff/leave/reports?academic_year=${year}`;
      if (branch) url += `&department=${encodeURIComponent(branch)}`;
      if (category) url += `&leave_type=${encodeURIComponent(category)}`;

      fetch(url, {
        headers: { 'Accept': 'application/json' }
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          renderLeaveReports(data.leaves, data.summary);
        }
      })
      .catch(() => { if (indicator) indicator.classList.add('hidden'); });
    }

    function renderLeaveReports(leaves, summary) {
      // Render summary counts
      if (summary) {
        if (document.getElementById('summaryCL')) document.getElementById('summaryCL').innerText = (summary.CL || 0) + 'd';
        if (document.getElementById('summaryCCL')) document.getElementById('summaryCCL').innerText = (summary.CCL || 0) + 'd';
        if (document.getElementById('summaryDL')) document.getElementById('summaryDL').innerText = (summary.DL || 0) + 'd';
        if (document.getElementById('summaryML')) document.getElementById('summaryML').innerText = (summary.ML || 0) + 'd';
        if (document.getElementById('summaryLOP')) document.getElementById('summaryLOP').innerText = (summary.LOP || 0) + 'd';
        if (document.getElementById('summarySL')) document.getElementById('summarySL').innerText = (summary.SL || 0) + 'd';
        if (document.getElementById('summaryTOTAL')) document.getElementById('summaryTOTAL').innerText = (summary.TOTAL_DAYS || 0) + 'd';

        const mobileSummary = document.getElementById('mobileReportSummary');
        if (mobileSummary) {
          mobileSummary.innerHTML = `
            <span class="badge bg-primary bg-opacity-20 text-primary">CL: ${summary.CL || 0}d</span>
            <span class="badge bg-warning bg-opacity-20 text-warning">CCL: ${summary.CCL || 0}d</span>
            <span class="badge bg-info bg-opacity-20 text-info">DL: ${summary.DL || 0}d</span>
            <span class="badge bg-danger bg-opacity-20 text-danger">LOP: ${summary.LOP || 0}d</span>
            <span class="badge bg-success bg-opacity-20 text-success">Total: ${summary.TOTAL_DAYS || 0}d</span>
          `;
        }
      }

      // Filter SF departments if no department filter selected
      const sfDepts = ['EL', 'AU', 'CT', 'GEN_SF', 'SF'];
      const filteredLeaves = leaves.filter(l => sfDepts.includes(l.department?.toUpperCase()));

      // Render Desktop Table
      const tbody = document.getElementById('reportsTableBody');
      if (tbody) {
        tbody.innerHTML = '';
        if (filteredLeaves.length === 0) {
          tbody.innerHTML = '<tr><td colspan="9" class="p-8 text-center text-slate-500 font-bold">No leave report records found for the selected filter.</td></tr>';
        } else {
          filteredLeaves.forEach(l => {
            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30';
            
            let dates = l.from_date;
            if (l.to_date && l.to_date !== l.from_date) dates += ` to ${l.to_date}`;

            let statusBadge = l.overall_status === 'Approved' ? 
              '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Approved</span>' :
              (l.overall_status === 'Rejected' ? 
                '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">Rejected</span>' :
                '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>');

            tr.innerHTML = `
              <td class="p-3 font-bold text-slate-100">${l.staff_name || 'Staff'}</td>
              <td class="p-3"><span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">${l.department}</span></td>
              <td class="p-3"><span class="px-2 py-0.5 rounded font-bold text-[10px] bg-purple-500/10 text-purple-300 border border-purple-500/20">${l.leave_type}</span></td>
              <td class="p-3 font-mono text-slate-300 text-xs">${dates}</td>
              <td class="p-3 font-bold text-slate-200">${l.total_days}d</td>
              <td class="p-3"><span class="text-[10px] font-bold ${l.hod_approval === 'Approved' ? 'text-emerald-400' : 'text-amber-400'}">${l.hod_approval || 'Pending'}</span></td>
              <td class="p-3"><span class="text-[10px] font-bold ${l.coordinator_approval === 'Approved' ? 'text-emerald-400' : 'text-amber-400'}">${l.coordinator_approval || 'Pending'}</span></td>
              <td class="p-3"><span class="text-[10px] font-bold ${l.principal_approval === 'Approved' ? 'text-emerald-400' : 'text-amber-400'}">${l.principal_approval || 'Pending'}</span></td>
              <td class="p-3 text-right space-x-1">
                ${statusBadge}
                <a href="/staff/leave/${l.id}/pdf" target="_blank" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded font-bold text-[10px] no-underline inline-flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs">picture_as_pdf</span> PDF
                </a>
              </td>
            `;
            tbody.appendChild(tr);
          });
        }
      }

      // Render Mobile Cards
      const mobileContainer = document.getElementById('mobileReportsContainer');
      if (mobileContainer) {
        mobileContainer.innerHTML = '';
        if (filteredLeaves.length === 0) {
          mobileContainer.innerHTML = '<small class="text-secondary d-block py-2">No leave report records found.</small>';
        } else {
          let html = '';
          filteredLeaves.forEach(l => {
            let dates = l.from_date;
            if (l.to_date && l.to_date !== l.from_date) dates += ` to ${l.to_date}`;
            const badgeClass = l.overall_status === 'Approved' ? 'bg-success' : (l.overall_status === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark');
            
            html += `
              <div class="p-2.5 rounded-3 border border-secondary border-opacity-20 bg-slate-900 mb-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <strong class="text-white small">${l.staff_name || 'Staff'} (${l.department})</strong>
                  <span class="badge ${badgeClass} small">${l.overall_status}</span>
                </div>
                <small class="text-secondary d-block mb-1" style="font-size:0.72rem;">
                  <span class="badge bg-purple bg-opacity-20 text-purple">${l.leave_type}</span> &bull; ${dates} (${l.total_days}d)
                </small>
                <div class="d-flex justify-content-between align-items-center pt-1 border-top border-secondary border-opacity-10">
                  <small class="text-secondary" style="font-size:0.68rem;">HOD: ${l.hod_approval || 'P'} | Coord: ${l.coordinator_approval || 'P'} | Prin: ${l.principal_approval || 'P'}</small>
                  <a href="/staff/leave/${l.id}/pdf" target="_blank" class="btn btn-sm btn-outline-light py-0.2 px-2" style="font-size:0.68rem;">
                    <i class="fa-solid fa-file-pdf me-1"></i> PDF
                  </a>
                </div>
              </div>
            `;
          });
          mobileContainer.innerHTML = html;
        }
      }
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      const search = (document.getElementById('filterSearch')?.value || document.getElementById('mobileFilterSearch')?.value || '');
      const branch = (document.getElementById('filterBranch')?.value || document.getElementById('mobileFilterBranch')?.value || '');
      const role = (document.getElementById('filterRole')?.value || document.getElementById('mobileFilterRole')?.value || '');

      let url = `/api/admin/users?search=${encodeURIComponent(search)}&role=${role}`;
      if (branch) url += `&branch=${branch}`;

      fetch(url)
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            const tbody = document.getElementById('usersTableBody');
            const mobileContainer = document.getElementById('mobileUsersContainer');
            
            const sfDepts = ['EL', 'AU', 'CT', 'GEN_SF', 'SF'];
            const filteredUsers = branch ? data.users : data.users.filter(u => sfDepts.includes(u.branch?.toUpperCase()));

            // Desktop render
            if (tbody) {
              tbody.innerHTML = '';
              if (filteredUsers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-slate-500 font-bold">No Self-Financing staff members found.</td></tr>';
              } else {
                filteredUsers.forEach(user => {
                  const tr = document.createElement('tr');
                  tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30';
