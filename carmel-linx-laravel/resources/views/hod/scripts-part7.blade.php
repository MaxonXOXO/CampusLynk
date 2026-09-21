      modal.classList.remove('flex');
    }

    function renderAssignStaffList() {
      const container = document.getElementById('staffCheckboxList');
      const branchFilter = document.getElementById('staffBranchFilter').value;
      
      container.innerHTML = '';
      
      let filteredStaff = allCollegeStaffCache;
      if (branchFilter) {
        filteredStaff = filteredStaff.filter(s => s.branch === branchFilter);
      }

      if (filteredStaff.length === 0) {
        container.innerHTML = '<div class="p-3 text-slate-500 text-sm text-center">No staff found for this branch.</div>';
        return;
      }

      filteredStaff.forEach(staff => {
        const isChecked = currentAssignStaffIds.includes(staff.mobile_no) ? 'checked' : '';
        const div = document.createElement('label');
        div.className = 'flex items-center gap-3 p-2 hover:bg-slate-800/40 rounded-lg cursor-pointer transition-premium border border-transparent hover:border-slate-700/50';
        div.innerHTML = `
          <input type="checkbox" name="assignStaffCb" value="${staff.mobile_no}" class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-blue-500" ${isChecked}>
          <div class="flex-grow flex justify-between items-center">
            <span class="text-sm font-bold text-slate-200">${staff.name}</span>
            <span class="text-sm text-slate-500 font-mono">${staff.branch} - ${staff.designation}</span>
          </div>
        `;
        container.appendChild(div);
      });
    }

    function assignStaff(e) {
      e.preventDefault();
      const subjectId = document.getElementById('assignSubjectId').value;
      const checkboxes = document.querySelectorAll('input[name="assignStaffCb"]:checked');
      const staffNos = Array.from(checkboxes).map(cb => cb.value);

      const spinner = document.getElementById('assignStaffSpinner');
      const alertEl = document.getElementById('assignStaffAlert');
      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      fetch(`/api/hod/batches/subjects/${subjectId}/assign-staff`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ staff_mobile_nos: staffNos })
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          closeAssignStaffModal();
          loadSubjects(); // refresh
          loadModalSubjects(); // refresh modal
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

    // =========================================================================
    // END SUBJECT ALLOCATION
    // =========================================================================

    function loadSelfSecurityLogs() {
      const tbody = document.getElementById('selfSecurityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">Querying security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">No profile action logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800 text-sm";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-sm font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Error querying logs.</td></tr>`;
        });
    }

    // =========================================================================
    // SUBJECT PROGRESS POPUP CARD LOGIC
    // =========================================================================
    let persistentPopupActive = false;

    function showSubjectProgressPopup(subj, event, isClick = false) {
      if (isClick) {
        persistentPopupActive = !persistentPopupActive;
      }
      
      const popup = document.getElementById('subjectProgressPopup');
      document.getElementById('popupSubjName').innerText = subj.subject_name;
      document.getElementById('popupSubjCode').innerText = subj.subject_code;
      document.getElementById('popupAllottedHours').innerText = (subj.total_hours_allotted || 0) + ' hrs';
      document.getElementById('popupCompletedHours').innerText = (subj.hours_completed || 0) + ' hrs';
      
      // Format Status Colors
      const formatStatus = (elId, status) => {
        const el = document.getElementById(elId);
        el.innerText = status || 'Not Initiated';
        if (!status || status === 'Not Initiated') {
          el.className = 'font-bold text-slate-500';
        } else if (status === 'Pending') {
          el.className = 'font-bold text-amber-400';
        } else {
          el.className = 'font-bold text-green-400';
        }
      };

      formatStatus('popupAssignmentStatus', subj.assignment_initiated);
      formatStatus('popupWrittenTestStatus', subj.written_test_initiated);
      formatStatus('popupMcqStatus', subj.mcq_status);
      formatStatus('popupMidSemStatus', subj.mid_sem_survey_status);
      formatStatus('popupEndSemStatus', subj.end_sem_survey_status);
      
      popup.classList.remove('hidden');
      positionSubjectProgressPopup(event);
    }

    function positionSubjectProgressPopup(event) {
      const popup = document.getElementById('subjectProgressPopup');
      let top = event.clientY + 15;
      let left = event.clientX + 15;
      
      const popupWidth = 288;
      const popupHeight = 240;
      
      if (left + popupWidth > window.innerWidth) {
        left = event.clientX - popupWidth - 15;
      }
      if (top + popupHeight > window.innerHeight) {
        top = event.clientY - popupHeight - 15;
      }
      
      popup.style.top = top + 'px';
      popup.style.left = left + 'px';
    }

    function hideSubjectProgressPopup() {
      if (!persistentPopupActive) {
        const popup = document.getElementById('subjectProgressPopup');
        popup.classList.add('hidden');
      }
    }
    
    // Clear persistent state on closures or transitions
    const originalCloseBatchDetailModal = closeBatchDetailModal;
    closeBatchDetailModal = function() {
      persistentPopupActive = false;
      hideSubjectProgressPopup();
      if (typeof originalCloseBatchDetailModal === 'function') {
        originalCloseBatchDetailModal();
      }
    };

    const originalSwitchBatchTab = switchBatchTab;
    switchBatchTab = function(tab) {
      persistentPopupActive = false;
      hideSubjectProgressPopup();
      if (typeof originalSwitchBatchTab === 'function') {
        originalSwitchBatchTab(tab);
      }
    };

    document.addEventListener('click', (e) => {
      const popup = document.getElementById('subjectProgressPopup');
      if (persistentPopupActive && !e.target.closest('tr')) {
        persistentPopupActive = false;
        popup.classList.add('hidden');
      }
    });

    function openStudentDiary(regNo) {
      window.open('/tutor/mentoring-diary/' + regNo, '_blank');
    }

    function openAssignStaffModalFromModal(event, btn, subjectId, currentStaffIds) {
      if (event) event.stopPropagation();
      openAssignStaffModal(btn, subjectId, currentStaffIds);
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      statusEl.classList.remove('hidden');
      statusEl.className = "text-sm font-bold mt-2 text-blue-400";
      statusEl.innerText = "Uploading photo...";

      const formData = new FormData();
      formData.append('photo', file);

      fetch('/api/staff/profile/upload-photo', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          statusEl.className = "text-sm font-bold mt-2 text-green-400";
          statusEl.innerText = "Photo updated successfully!";

          // Update main profile picture
          const imgEl = document.getElementById('staffProfileImg');
          if (imgEl) {
            imgEl.src = data.photo_url;
          }

          // Update sidebar picture
          const sidebarImg = document.getElementById('sidebarStaffImg');
          if (sidebarImg) {
            sidebarImg.src = data.photo_url;
          }

          setTimeout(() => statusEl.classList.add('hidden'), 3000);
        } else {
          statusEl.className = "text-sm font-bold mt-2 text-rose-400";
          statusEl.innerText = data.message || "Upload failed.";
        }
      })
      .catch(() => {
        statusEl.className = "text-sm font-bold mt-2 text-rose-400";
        statusEl.innerText = "Network error. Please try again.";
      });
    }

    function checkTodaySeminars() {
      fetch('/api/lecturer/today-seminars')
      .then(res => res.json())
      .then(res => {
        const container = document.getElementById('seminarNotificationsContainer');
        if (!container) return;
        container.innerHTML = '';

        if (res.status === 'SUCCESS' && res.data.length > 0) {
          // Group by classroom_id
          const groups = {};
          res.data.forEach(item => {
            const cid = item.classroom_id || 'Unknown_Classroom';
            if (!groups[cid]) {
              groups[cid] = [];
            }
            groups[cid].push(item);
          });

          // Render a card for each group
          Object.keys(groups).forEach(cid => {
            const items = groups[cid];
            const first = items[0];
            const count = items.length;

            const card = document.createElement('div');
            card.className = "p-4 bg-amber-50 border border-amber-200/80 hover:border-amber-300 rounded-2xl flex items-center justify-between shadow-2xs hover:shadow-xs transition-all cursor-pointer group";
            card.onclick = () => {
              window.location.href = `/dashboard/lecturer?subject_id=${first.batch_subject_id}&subject_name=${encodeURIComponent(first.subject_name || 'Seminar')}&classroom_id=${encodeURIComponent(cid)}`;
            };

            card.innerHTML = `
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                  <i data-lucide="presentation" class="w-5 h-5 text-amber-700"></i>
                </div>
                <div class="min-w-0">
                  <h5 class="text-sm font-bold text-amber-900 group-hover:text-amber-950 transition-colors truncate">Seminar Presentations Today (${count})</h5>
                  <p class="text-xs text-amber-700 mt-0.5 truncate">${cid} · ${first.subject_name || 'Seminar'}</p>
                </div>
              </div>
              <i data-lucide="chevron-right" class="w-4 h-4 text-amber-500 group-hover:text-amber-700 transition-colors shrink-0"></i>
            `;
            container.appendChild(card);
          });

          container.classList.remove('hidden');
          if (window.initLucide) window.initLucide();
        } else {
          container.classList.add('hidden');
        }
      })
      .catch(err => console.error('Failed to load today seminars:', err));
    }

    // =========================================================================
    // STAFF LEAVE MASTER LEDGER HANDLERS
    // =========================================================================
    async function loadLeaveLedger() {
      const dept = document.getElementById('leaveLedgerDept')?.value || '';
      const year = document.getElementById('leaveLedgerYear')?.value || '';
      const status = document.getElementById('leaveLedgerStatus')?.value || '';
      const tbody = document.getElementById('leaveLedgerTableBody');
      if (!tbody) return;

      tbody.innerHTML = `<tr><td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">
        <div class="inline-flex items-center gap-2 text-slate-500">
          <div class="w-4 h-4 border-2 border-rose-600 border-t-transparent rounded-full animate-spin"></div>
          <span>Loading staff leave records...</span>
        </div>
      </td></tr>`;

      try {
        const query = new URLSearchParams();
        if (dept) query.set('department', dept);
        if (year) query.set('academic_year', year);
        if (status) query.set('status', status);

        const res = await fetch(`/api/staff/leave/reports-data?${query.toString()}`);
        const data = await res.json();

        if (data.status === 'SUCCESS' && data.leaves) {
          const sm = data.summary || {};
          const kTotal = document.getElementById('leaveKpiTotal');
          const kCL = document.getElementById('leaveKpiCL');
          const kCCL = document.getElementById('leaveKpiCCL');
          const kDL = document.getElementById('leaveKpiDL');
          const kML = document.getElementById('leaveKpiML');
          const kLOP = document.getElementById('leaveKpiLOP');

          if (kTotal) kTotal.innerText = (sm.TOTAL_DAYS || 0).toFixed(1);
          if (kCL) kCL.innerText = (sm.CL || 0).toFixed(1);
          if (kCCL) kCCL.innerText = (sm.CCL || 0).toFixed(1);
          if (kDL) kDL.innerText = (sm.DL || 0).toFixed(1);
          if (kML) kML.innerText = (sm.ML || 0).toFixed(1);
          if (kLOP) kLOP.innerText = (sm.LOP || 0).toFixed(1);

          if (data.leaves.length > 0) {
            tbody.innerHTML = data.leaves.map(l => {
              const staffName = l.staff_name || 'Staff Member';
              const initial = staffName.charAt(0).toUpperCase();
              
              let badgeColor = 'bg-amber-50 text-amber-700 border-amber-200/80';
              let badgeDot = 'bg-amber-500';
              if (l.overall_status === 'Approved') {
                badgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-200/80';
                badgeDot = 'bg-emerald-500';
              } else if (l.overall_status === 'Rejected') {
                badgeColor = 'bg-rose-50 text-rose-700 border-rose-200/80';
                badgeDot = 'bg-rose-500';
              }

              const statusBadge = `
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold ${badgeColor} border">
                  <span class="w-1.5 h-1.5 rounded-full ${badgeDot}"></span>
                  ${l.overall_status?.replace('_', ' ') || 'Pending'}
                </span>
              `;

              const canApprove = (l.overall_status === 'Pending_HOD' || (!l.overall_status?.includes('Approved') && !l.overall_status?.includes('Rejected')));

              return `
                <tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="p-4">
                    <div class="flex items-center gap-3">
                      <div class="w-9 h-9 rounded-full bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-700 font-bold text-sm shrink-0">
                        ${initial}
                      </div>
                      <div>
                        <span class="font-bold text-slate-900 block text-sm leading-tight">${staffName}</span>
                        <span class="text-xs text-slate-500 font-mono mt-0.5 block">${l.department} • <strong class="text-blue-600">${l.leave_code || ('SLV-' + l.id)}</strong></span>
                      </div>
                    </div>
                  </td>
                  <td class="p-4">
                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200/80">
                      ${l.leave_type || 'Casual Leave'}
                    </span>
                  </td>
                  <td class="p-4">
                    <span class="text-sm font-semibold text-slate-900 block">${l.from_date} to ${l.to_date}</span>
                    <span class="text-xs text-slate-500 font-medium mt-0.5 inline-block px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200/60">${l.total_days} Day(s) (${l.session_type || 'Full Day'})</span>
                  </td>
                  <td class="p-4 text-xs text-slate-600 max-w-xs">
                    <span class="line-clamp-2">${l.reason || '-'}</span>
                  </td>
                  <td class="p-4 text-center">
                    ${statusBadge}
                  </td>
                  <td class="p-4 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                      ${canApprove ? `
                        <button 
                          type="button" 
                          onclick="processLeaveApproval(${l.id}, 'Approve')" 
                          class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition cursor-pointer shadow-xs" 
                          title="Recommend/Approve Leave"
                        >
                          Approve
                        </button>
                        <button 
                          type="button" 
                          onclick="processLeaveApproval(${l.id}, 'Reject')" 
                          class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition cursor-pointer shadow-xs" 
                          title="Reject Leave"
                        >
                          Reject
                        </button>
                      ` : ''}
                      <a 
                        href="/staff/leave/${l.id}/pdf" 
                        target="_blank" 
                        class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition cursor-pointer" 
                        title="Print Official Leave PDF Application"
                      >
                        <i data-lucide="printer" class="w-4 h-4"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              `;
            }).join('');
            if (window.initLucide) window.initLucide();
          } else {
            tbody.innerHTML = `
              <tr>
                <td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">
                  <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="folder-open" class="w-6 h-6 text-slate-400"></i>
                  </div>
                  <h4 class="text-base font-bold text-slate-800">No staff leave records found</h4>
                  <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">No leave applications matching the selected criteria.</p>
                </td>
              </tr>
