
    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">Querying classroom audit logs...</td></tr>`;

      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">No classroom audit logs found.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-100 hover:bg-slate-50/80 transition-all";
              
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3.5 pl-4 text-slate-500 font-mono text-xs">${date}</td>
                <td class="p-3.5 font-bold text-slate-900 text-xs">${log.performed_by_name || 'System'}<br><span class="text-xs text-slate-500 font-mono">${log.performed_by || ''}</span></td>
                <td class="p-3.5 font-bold text-slate-900 text-xs">${log.target_name || '-'}<br><span class="text-xs text-blue-600 font-mono">${log.target_id || ''}</span></td>
                <td class="p-3.5"><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">${log.action}</span></td>
                <td class="p-3.5 font-mono text-slate-500 text-xs">${log.ip_address || '-'}</td>
                <td class="p-3.5 pr-4 text-slate-700 font-sans text-xs leading-relaxed">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Error loading logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Request failed.</td></tr>`;
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
              tr.className = "border-b border-slate-800/40 text-xs";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-500 font-mono text-xs">${date}</td>
                <td class="p-3 font-semibold text-slate-900 text-xs">${log.performed_by_name || 'System'}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-700 text-xs">${log.details || ''}</td>
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
            showGlobalMessage('Student profile deleted successfully.');
            loadUsers();
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to delete student profile.', true);
        });
      }
    }


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
              tr.className = "border-b border-slate-800 text-xs";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-500 font-mono text-xs">${date}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-700 text-xs">${log.details || ''}</td>
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


    // ==========================================
    // ACTIVITY POINTS LOGIC
    // ==========================================
    
    let rosterExpanded = false;
    function toggleRoster() {
      const content = document.getElementById('rosterContent');
      const icon = document.getElementById('rosterIcon');
      if (rosterExpanded) {
        content.style.maxHeight = '0px';
        content.style.opacity = '0';
        content.style.overflow = 'hidden';
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.style.maxHeight = '1000px';
        content.style.opacity = '1';
        icon.style.transform = 'rotate(0deg)';
        setTimeout(() => content.style.overflow = 'visible', 300);
      }
      rosterExpanded = !rosterExpanded;
    }

    let activityExpanded = false;
    function toggleActivity() {
      const content = document.getElementById('activityContent');
      const icon = document.getElementById('activityIcon');
      if (activityExpanded) {
        content.style.maxHeight = '0px';
        content.style.opacity = '0';
        content.style.overflow = 'hidden';
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.style.maxHeight = '1000px';
        content.style.opacity = '1';
        icon.style.transform = 'rotate(0deg)';
        setTimeout(() => content.style.overflow = 'visible', 300);
      }
      activityExpanded = !activityExpanded;
    }

    function loadActivityClaims() {
      fetch('/api/tutor/activity-points')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const tbody = document.getElementById('tutorActivityTableBody');
            let html = '';
            
            data.claims.forEach(c => {
              let actionsHtml = '';
              let submittedDate = c.created_at ? new Date(c.created_at) : null;
              let submittedHtml = submittedDate 
                ? `<span class="block text-xs font-bold text-slate-900">${submittedDate.toLocaleDateString()}</span><span class="block text-xs text-slate-500">${submittedDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>`
                : `<span class="text-xs text-slate-500">N/A</span>`;

              if (c.status === 'Pending') {
                actionsHtml = `
                  <div class="flex items-center justify-center gap-2">
                    <input type="number" id="award_${c.id}" min="0" max="${c.points_claimed}" value="${c.points_claimed}" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-xs text-slate-900 font-semibold focus:bg-white focus:border-blue-600 outline-none">
                    <button onclick="verifyClaim('${c.id}', 'Verified')" class="px-2 py-1 bg-teal-600 hover:bg-teal-500 rounded text-white text-xs font-bold">Approve</button>
                    <button onclick="verifyClaim('${c.id}', 'Rejected')" class="px-2 py-1 bg-red-950 text-red-400 border border-red-900 rounded text-xs font-bold">Reject</button>
                  </div>
                `;
              } else {
                let verifiedDateStr = c.verified_at ? new Date(c.verified_at).toLocaleDateString() : '';
                let noteHtml = '';
                if (c.status === 'Rejected' && c.rejection_note) {
                  noteHtml = `<div class="mt-1 text-xs text-rose-400/80 leading-tight bg-rose-950/30 p-1 rounded border border-rose-900/30 text-left">Note: ${c.rejection_note}</div>`;
                }
                actionsHtml = `
                  <div class="flex flex-col items-center">
                    <span class="font-bold ${c.status === 'Verified' ? 'text-emerald-600' : 'text-red-400'}">${c.status} (${c.points_awarded} pts)</span>
                    ${verifiedDateStr ? `<span class="text-xs text-slate-500 mt-0.5">On: ${verifiedDateStr}</span>` : ''}
                    ${noteHtml}
                  </div>
                `;
              }

              html += `
                <tr class="hover:bg-slate-900/50 transition-premium">
                  <td class="p-3">${submittedHtml}</td>
                  <td class="p-3">
                    <span class="font-bold text-slate-900 block text-xs">${c.student.name}</span>
                    <span class="text-xs text-slate-500 font-mono">${c.reg_no}</span>
                  </td>
                  <td class="p-3 text-xs font-semibold text-slate-600">${c.activity_segment}</td>
                  <td class="p-3">
                    <span class="block text-xs text-slate-700">${c.activity_name}</span>
                    <span class="block text-xs text-slate-500">${c.level}</span>
                  </td>
                  <td class="p-3 text-xs text-slate-500 whitespace-normal min-w-[150px]">${c.document_reference || 'N/A'}</td>
                  <td class="p-3 text-center text-xs font-bold text-slate-900">${c.points_claimed}</td>
                  <td class="p-3 text-center">${actionsHtml}</td>
                </tr>
              `;
            });
            
            if (data.claims.length === 0) {
              html = `<tr><td colspan="6" class="p-6 text-center text-slate-500 text-xs">No pending activity claims found for your classroom.</td></tr>`;
            }
            
            tbody.innerHTML = html;
          }
        });
    }

    function verifyClaim(id, status) {
      let awarded = 0;
      let note = '';
      if (status === 'Verified') {
        awarded = document.getElementById(`award_${id}`).value;
      } else if (status === 'Rejected') {
        note = prompt("Enter a reason for rejection (optional):");
        if (note === null) return; // User cancelled
      }
      
      fetch(`/api/tutor/activity-points/${id}/verify`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ status: status, points_awarded: awarded, rejection_note: note })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage(`Claim marked as ${status}.`);
          loadActivityClaims();
        } else {
          showGlobalMessage(data.message, true);
        }
      });
    }

    // ==========================================
    // MENTORING BATCHES LOGIC
    // ==========================================

    let mentoringDataCache = null;
    let selectedMentoringClassroomId = null;

    function ensureMentoringClassroomsLoaded(callback) {
      const select = document.getElementById('mentorClassroomSelect');
      const leaveSelect = document.getElementById('leaveClassroomSelect');
      
      if (select && select.options.length > 0 && select.value !== "" && select.value !== "Loading...") {
        if (callback) callback();
        return;
      }
      
      select.innerHTML = '<option value="">Loading...</option>';
      if (leaveSelect) leaveSelect.innerHTML = '<option value="">Loading...</option>';
      
      fetch('/api/mentoring/my-batches')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            select.innerHTML = '';
            if (leaveSelect) leaveSelect.innerHTML = '';
            if (data.batches.length === 0) {
              select.innerHTML = '<option value="">No mentored classrooms</option>';
              if (leaveSelect) leaveSelect.innerHTML = '<option value="">No mentored classrooms</option>';
              return;
            }

            data.batches.forEach(b => {
              const opt = document.createElement('option');
              opt.value = b.classroom_id;
              const isGraduated = (b.current_semester || 1) > 6;
              opt.innerText = `${b.classroom_id} (Admission ${b.batch_year})${isGraduated ? ' (Graduated)' : ''}`;
              select.appendChild(opt);

              if (leaveSelect) {
                const opt2 = opt.cloneNode(true);
                leaveSelect.appendChild(opt2);
              }
            });
            
            selectedMentoringClassroomId = select.value;
            if (callback) callback();
          } else {
            select.innerHTML = '<option value="">Failed to load</option>';
          }
        })
        .catch(() => {
          select.innerHTML = '<option value="">Error</option>';
        });
    }

    function initMentoringPanel() {
      ensureMentoringClassroomsLoaded(() => {
        loadMentoringData();
      });
    }

    function generateBacklogReport() {
      if (!selectedMentoringClassroomId) {
        showGlobalMessage("Please select a classroom first.", true);
        return;
      }
      
      const modal = document.getElementById('backlogReportModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      
      document.getElementById('noBacklogList').innerHTML = '<tr><td class="p-4 text-center text-slate-500">Loading data...</td></tr>';
      document.getElementById('withBacklogList').innerHTML = '<tr><td class="p-4 text-center text-slate-500">Loading data...</td></tr>';
      document.getElementById('noBacklogCount').innerText = '0';
      document.getElementById('withBacklogCount').innerText = '0';
      
      fetch(`/api/mentoring/backlog-report/${selectedMentoringClassroomId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const noBacklogs = data.no_backlogs || [];
            const withBacklogs = data.with_backlogs || [];
            
            document.getElementById('noBacklogCount').innerText = noBacklogs.length;
            document.getElementById('withBacklogCount').innerText = withBacklogs.length;
            
            let noHtml = '';
            if (noBacklogs.length === 0) {
              noHtml = '<tr><td class="p-4 text-center text-slate-500">No students found.</td></tr>';
            } else {
              noBacklogs.forEach(s => {
                noHtml += `
                  <tr class="hover:bg-slate-900/30 transition-premium">
                    <td class="p-3">
                      <div class="font-bold text-slate-200 text-xs">${s.name}</div>
                      <div class="text-xs text-slate-500 font-mono">${s.reg_no}</div>
                    </td>
                  </tr>
                `;
              });
            }
            document.getElementById('noBacklogList').innerHTML = noHtml;
            
            let withHtml = '';
            if (withBacklogs.length === 0) {
              withHtml = '<tr><td class="p-4 text-center text-slate-500">No students found.</td></tr>';
            } else {
              withBacklogs.forEach(s => {
                withHtml += `
                  <tr class="hover:bg-slate-900/30 transition-premium">
                    <td class="p-3">
                      <div class="font-bold text-slate-200 text-xs">${s.name}</div>
                      <div class="text-xs text-slate-500 font-mono">${s.reg_no}</div>
                    </td>
                    <td class="p-3 text-right">
                      <span class="bg-rose-900/40 text-rose-400 px-2 py-1 rounded text-xs font-bold border border-rose-800/50">${s.backlog_count} Backlogs</span>
                    </td>
                  </tr>
                `;
              });
            }
            document.getElementById('withBacklogList').innerHTML = withHtml;
          } else {
            document.getElementById('noBacklogList').innerHTML = `<tr><td class="p-4 text-center text-red-500">Error: ${data.message}</td></tr>`;
            document.getElementById('withBacklogList').innerHTML = `<tr><td class="p-4 text-center text-red-500">Error: ${data.message}</td></tr>`;
          }
        })
        .catch(err => {
          console.error(err);
          document.getElementById('noBacklogList').innerHTML = '<tr><td class="p-4 text-center text-red-500">Failed to load data.</td></tr>';
          document.getElementById('withBacklogList').innerHTML = '<tr><td class="p-4 text-center text-red-500">Failed to load data.</td></tr>';
        });
    }

    function loadMentoringData() {
      const select = document.getElementById('mentorClassroomSelect');
      selectedMentoringClassroomId = select.value;
      if (!selectedMentoringClassroomId) return;

      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch(`/api/mentoring/report/${selectedMentoringClassroomId}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            mentoringDataCache = data;
            renderMentoringUI(data);
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to load mentoring data.', true);
        });
    }

    function renderMentoringUI(data) {
      document.getElementById('mentorAInfo').innerText = data.mentor1.name + ' (' + data.mentor1.mobile + ')';
      document.getElementById('mentorBInfo').innerText = data.mentor2.name + ' (' + data.mentor2.mobile + ')';

      const unassignedList = document.getElementById('unassignedList');
      const batchAList = document.getElementById('batchAList');
      const batchBList = document.getElementById('batchBList');
      const myList = document.getElementById('myMentoringStudentsList');

      document.getElementById('unassignedCountBadge').innerText = data.unassigned.length;
      document.getElementById('batchACountBadge').innerText = data.batch_a.length;
      document.getElementById('batchBCountBadge').innerText = data.batch_b.length;
