          if (data.status === 'SUCCESS') {
            document.getElementById('execFdpCount').innerText = `${data.total_fdps} Verified`;
            if (data.three_sem_matrix) {
              ['EL', 'ME', 'CE', 'EEE', 'CT', 'AU'].forEach(b => {
                if (data.three_sem_matrix[b]) {
                  const m = data.three_sem_matrix[b];
                  if (m.semesters) {
                    const keys = Object.keys(m.semesters);
                    if (keys[0] && document.getElementById(`sem_${b}_S1`)) document.getElementById(`sem_${b}_S1`).innerText = `${m.semesters[keys[0]]}%`;
                    if (keys[1] && document.getElementById(`sem_${b}_S3`)) document.getElementById(`sem_${b}_S3`).innerText = `${m.semesters[keys[1]]}%`;
                    if (keys[2] && document.getElementById(`sem_${b}_S5`)) document.getElementById(`sem_${b}_S5`).innerText = `${m.semesters[keys[2]]}%`;
                  }
                  if (document.getElementById(`sem_${b}_avg`)) document.getElementById(`sem_${b}_avg`).innerText = `${m.branch_avg}%`;
                }
              });
            }
          }
        }).catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', function() {
      loadExecutiveMetrics();
      loadFlashNoticeStats();
      loadPrincipalEventStats();
    });

    // PRINCIPAL EVENT SCHEDULER DESK FUNCTIONS
    function openPrincipalScheduleEventModal() {
      document.getElementById('principalScheduleEventModal').classList.remove('hidden');
    }

    function closePrincipalScheduleEventModal() {
      document.getElementById('principalScheduleEventModal').classList.add('hidden');
      document.getElementById('principalScheduleEventForm').reset();
      togglePrincipalEventTargetFields();
    }

    function togglePrincipalEventTargetFields() {
      const scope = document.getElementById('peTargetAudience').value;
      const deptWrapper = document.getElementById('peDeptWrapper');
      const semWrapper = document.getElementById('peSemWrapper');
      const roleWrapper = document.getElementById('peRoleWrapper');
      const specialGroupWrapper = document.getElementById('peSpecialGroupWrapper');

      if (deptWrapper) deptWrapper.style.display = (scope === 'DEPT_SPECIFIC' || scope === 'STUDENTS_ONLY') ? 'block' : 'none';
      if (semWrapper) semWrapper.style.display = (scope === 'STUDENTS_ONLY') ? 'block' : 'none';
      if (roleWrapper) roleWrapper.style.display = (scope === 'STAFF_ONLY') ? 'block' : 'none';
      if (specialGroupWrapper) specialGroupWrapper.style.display = (scope === 'SPECIAL_GROUP') ? 'block' : 'none';
    }

    function submitPrincipalScheduleEvent(e) {
      e.preventDefault();
      const btn = document.getElementById('peSubmitBtn');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded animate-spin text-base">sync</span> Scheduling...';

      const formData = new FormData(document.getElementById('principalScheduleEventForm'));

      fetch('/api/principal/events/schedule', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><path d="M21 14V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8"/><path d="M3 10h18"/><path d="m16 20 2 2 4-4"/></svg> Schedule & Broadcast Event';
        if (data.status === 'SUCCESS') {
          alert(data.message);
          closePrincipalScheduleEventModal();
          loadPrincipalEventStats();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><path d="M21 14V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8"/><path d="M3 10h18"/><path d="m16 20 2 2 4-4"/></svg> Schedule & Broadcast Event';
        alert('Failed to schedule event. Please try again.');
      });
    }

    function loadPrincipalEventStats() {
      fetch('/api/principal/events')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS' && data.stats) {
            if (document.getElementById('principalEventStatCollege')) document.getElementById('principalEventStatCollege').innerText = data.stats.college_wide;
            if (document.getElementById('principalEventStatDept')) document.getElementById('principalEventStatDept').innerText = (data.stats.dept_specific + data.stats.staff_only);
            if (document.getElementById('principalEventStatSpecial')) document.getElementById('principalEventStatSpecial').innerText = data.stats.special_groups;
          }
        }).catch(() => {});
    }

    function openPrincipalScheduleEventHistoryModal() {
      document.getElementById('principalScheduleEventHistoryModal').classList.remove('hidden');
      const body = document.getElementById('principalEventHistoryBody');
      body.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-slate-500">Loading events...</td></tr>';

      fetch('/api/principal/events')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS' && data.events.length > 0) {
            body.innerHTML = data.events.map(ev => `
              <tr class="hover:bg-slate-900/50">
                <td class="py-2.5 px-3 font-mono text-[11px] text-slate-400">
                  ${ev.event_date ? ev.event_date.split('T')[0] : ''}<br>
                  <span class="text-[10px] text-emerald-400 font-bold">${ev.start_time || 'All Day'} ${ev.end_time ? '- ' + ev.end_time : ''}</span>
                </td>
                <td class="py-2.5 px-3">
                  <span class="font-bold text-slate-100 block">${ev.title}</span>
                  <span class="px-1.5 py-0.2 text-[9px] rounded font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">${ev.event_category}</span>
                  ${ev.venue ? `<span class="text-[10px] text-slate-400 block mt-0.5">📍 ${ev.venue}</span>` : ''}
                </td>
                <td class="py-2.5 px-3 text-[11px] text-slate-300">
                  <span class="font-mono text-emerald-400 font-bold">${ev.target_audience}</span>
                  ${ev.target_department !== 'ALL' ? `<span class="text-slate-400 block">Dept: ${ev.target_department}</span>` : ''}
                  ${ev.special_group_name ? `<span class="text-purple-300 block font-bold">Group: ${ev.special_group_name}</span>` : ''}
                </td>
                <td class="py-2.5 px-3 text-center">
                  ${ev.requires_rsvp ? '<span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">Yes</span>' : '<span class="text-slate-500 text-[10px]">No</span>'}
                </td>
                <td class="py-2.5 px-3">
                  ${ev.attachment_path ? `<a href="/storage/${ev.attachment_path}" target="_blank" class="text-emerald-400 underline font-mono text-[11px] flex items-center gap-1"><span class="material-symbols-rounded text-xs">attach_file</span> ${ev.attachment_type.toUpperCase()}</a>` : '<span class="text-slate-500 font-mono text-[11px]">None</span>'}
                </td>
                <td class="py-2.5 px-3 text-right">
                  <button onclick="revokePrincipalScheduledEvent(${ev.id})" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded font-bold text-[10px] transition cursor-pointer">Cancel</button>
                </td>
              </tr>
            `).join('');
          } else {
            body.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-slate-500">No scheduled events found.</td></tr>';
          }
        }).catch(() => {
          body.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-rose-400">Failed to load events.</td></tr>';
        });
    }

    function closePrincipalScheduleEventHistoryModal() {
      document.getElementById('principalScheduleEventHistoryModal').classList.add('hidden');
    }

    function revokePrincipalScheduledEvent(id) {
      if (!confirm('Are you sure you want to cancel and delete this scheduled event?')) return;
      fetch(`/api/principal/events/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          openPrincipalScheduleEventHistoryModal();
          loadPrincipalEventStats();
        } else {
          alert('Error: ' + data.message);
        }
      });
    }

    // FLASH NOTICE BROADCAST DESK FUNCTIONS
    function openFlashNoticeModal() {
      document.getElementById('flashNoticeModal').classList.remove('hidden');
    }

    function closeFlashNoticeModal() {
      document.getElementById('flashNoticeModal').classList.add('hidden');
      document.getElementById('flashNoticeForm').reset();
      toggleNoticeTargetFields();
      toggleNoticeScheduleTime();
    }

    function toggleNoticeTargetFields() {
      const scope = document.getElementById('fnTargetAudience').value;
      const deptWrapper = document.getElementById('fnDeptWrapper');
      const semWrapper = document.getElementById('fnSemWrapper');

      if (scope === 'STAFF_DEPT' || scope === 'STUDENTS_DEPT_SEM') {
        deptWrapper.style.display = 'block';
      } else {
        deptWrapper.style.display = 'block';
      }

      if (scope === 'STUDENTS_DEPT_SEM') {
        semWrapper.style.display = 'block';
      } else {
        semWrapper.style.display = 'block';
      }
    }

    function toggleNoticeScheduleTime() {
      const dispatch = document.querySelector('input[name="dispatch_type"]:checked').value;
      const timeWrapper = document.getElementById('fnScheduleTimeWrapper');
      if (dispatch === 'scheduled') {
        timeWrapper.classList.remove('hidden');
      } else {
        timeWrapper.classList.add('hidden');
      }
    }

    function submitFlashNotice(e) {
      e.preventDefault();
      const btn = document.getElementById('fnSubmitBtn');
      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded animate-spin text-base">sync</span> Sending...';

      const formData = new FormData(document.getElementById('flashNoticeForm'));

      fetch('/api/admin/flash-notices/broadcast', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-base">send</span> Broadcast Notice';
        if (data.status === 'SUCCESS') {
          alert(data.message);
          closeFlashNoticeModal();
          loadFlashNoticeStats();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-base">send</span> Broadcast Notice';
        alert('Failed to send notice. Please try again.');
      });
    }

    function loadFlashNoticeStats() {
      fetch('/api/admin/flash-notices')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            if (document.getElementById('flashNoticeStatSent')) document.getElementById('flashNoticeStatSent').innerText = data.stats.total_sent;
            if (document.getElementById('flashNoticeStatSched')) document.getElementById('flashNoticeStatSched').innerText = data.stats.scheduled_count;
            if (document.getElementById('flashNoticeStatUrgent')) document.getElementById('flashNoticeStatUrgent').innerText = data.stats.urgent_count;
          }
        }).catch(() => {});
    }

    function openFlashNoticeHistoryModal() {
      document.getElementById('flashNoticeHistoryModal').classList.remove('hidden');
      const body = document.getElementById('flashNoticeHistoryBody');
      body.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-slate-500">Loading history...</td></tr>';

      fetch('/api/admin/flash-notices')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS' && data.notices.length > 0) {
            body.innerHTML = data.notices.map(n => `
              <tr class="hover:bg-slate-900/50">
                <td class="py-2.5 px-3 font-mono text-[11px] text-slate-400">${new Date(n.created_at).toLocaleString()}</td>
                <td class="py-2.5 px-3">
                  <span class="font-bold text-slate-100 block">${n.title}</span>
                  <span class="px-1.5 py-0.2 text-[9px] rounded font-bold ${n.priority === 'Urgent' ? 'bg-rose-500/20 text-rose-300' : 'bg-slate-800 text-slate-300'}">${n.priority}</span>
                </td>
                <td class="py-2.5 px-3 text-[11px] text-slate-300">
                  <span class="font-mono text-sky-400 font-bold">${n.target_audience}</span>
                  <span class="text-slate-400 block">${n.target_department} | Sem: ${n.target_semester}</span>
                </td>
                <td class="py-2.5 px-3">
                  ${n.attachment_path ? `<a href="/storage/${n.attachment_path}" target="_blank" class="text-sky-400 underline font-mono text-[11px] flex items-center gap-1"><span class="material-symbols-rounded text-xs">attach_file</span> View ${n.attachment_type.toUpperCase()}</a>` : '<span class="text-slate-500 font-mono text-[11px]">None</span>'}
                </td>
                <td class="py-2.5 px-3 text-right">
                  <button onclick="revokeFlashNotice(${n.id})" class="px-2 py-1 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded font-bold text-[10px] transition">Revoke</button>
                </td>
              </tr>
            `).join('');
          } else {
            body.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-slate-500">No broadcast history recorded yet.</td></tr>';
          }
        }).catch(() => {
          body.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-rose-400">Failed to load history.</td></tr>';
        });
    }

    function closeFlashNoticeHistoryModal() {
      document.getElementById('flashNoticeHistoryModal').classList.add('hidden');
    }

    function revokeFlashNotice(id) {
      if (!confirm('Are you sure you want to revoke this notice? It will be deleted permanently.')) return;
      fetch(`/api/admin/flash-notices/revoke/${id}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          openFlashNoticeHistoryModal();
          loadFlashNoticeStats();
        } else {
          alert('Error: ' + data.message);
        }
      });
    }
  </script>

  <!-- FLASH NOTICE BROADCAST MODAL -->
  <div id="flashNoticeModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 space-y-5 shadow-2xl relative text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-xl bg-sky-500/10 border border-sky-500/30 flex items-center justify-center text-sky-400">
            <span class="material-symbols-rounded text-xl">campaign</span>
          </div>
          <div>
            <h3 class="font-extrabold text-slate-100 text-base">Broadcast Executive Flash Notice</h3>
            <p class="text-xs text-slate-400">Dispatch notice to Staff (All/Dept) &amp; Students (All/Dept/Sem)</p>
          </div>
        </div>
        <button onclick="closeFlashNoticeModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
          <span class="material-symbols-rounded text-lg">close</span>
        </button>
      </div>

      <form id="flashNoticeForm" onsubmit="submitFlashNotice(event)" class="space-y-4 text-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-slate-300 font-bold mb-1">Notice Title / Subject <span class="text-rose-400">*</span></label>
            <input type="text" id="fnTitle" name="title" required placeholder="e.g., Special Working Day &amp; Exam Valuation Notice" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-sky-500 font-medium">
          </div>
          <div>
            <label class="block text-slate-300 font-bold mb-1">Priority / Type <span class="text-rose-400">*</span></label>
            <select id="fnPriority" name="priority" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-sky-500 font-medium">
              <option value="Normal">Normal Announcement</option>
              <option value="Urgent">Urgent Flash Warning</option>
              <option value="Circular">Official Circular</option>
            </select>
          </div>
        </div>

        <div class="p-3.5 bg-slate-950/60 border border-slate-800 rounded-xl space-y-3">
          <span class="block text-slate-200 font-bold text-[11px] uppercase tracking-wider flex items-center gap-1.5">
            <span class="material-symbols-rounded text-sky-400 text-sm">groups</span> Target Audience &amp; Scope
          </span>
          
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-slate-400 mb-1 font-semibold">Recipient Group</label>
              <select id="fnTargetAudience" name="target_audience" onchange="toggleNoticeTargetFields()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-sky-500 font-medium">
                <option value="ALL_CAMPUS">🌐 ALL Campus (Staff &amp; Students)</option>
                <option value="STAFF_ALL">👨‍🏫 Staff - All Departments</option>
                <option value="STAFF_DEPT">🏫 Staff - Specific Department</option>
                <option value="STUDENTS_ALL">🎓 Students - All Batches</option>
                <option value="STUDENTS_DEPT_SEM">📚 Students - Dept &amp; Semester</option>
              </select>
            </div>

            <div id="fnDeptWrapper">
              <label class="block text-slate-400 mb-1 font-semibold">Department Branch</label>
              <select id="fnTargetDepartment" name="target_department" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-sky-500 font-medium">
                <option value="ALL">All Departments</option>
                <option value="EL">Electronics Engg (EL)</option>
                <option value="ME">Mechanical Engg (ME)</option>
                <option value="CE">Civil Engg (CE)</option>
                <option value="EEE">Electrical Engg (EEE)</option>
                <option value="CT">Computer Engg (CT)</option>
                <option value="AU">Automobile Engg (AU)</option>
                <option value="GEN_AIDED">General Aided</option>
                <option value="GEN_SF">General SF</option>
              </select>
            </div>

            <div id="fnSemWrapper">
              <label class="block text-slate-400 mb-1 font-semibold">Semester Level</label>
              <select id="fnTargetSemester" name="target_semester" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-sky-500 font-medium">
                <option value="ALL">All Semesters (S1 to S6)</option>
                <option value="1">Semester 1 (S1)</option>
                <option value="2">Semester 2 (S2)</option>
                <option value="3">Semester 3 (S3)</option>
                <option value="4">Semester 4 (S4)</option>
                <option value="5">Semester 5 (S5)</option>
                <option value="6">Semester 6 (S6)</option>
              </select>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-slate-300 font-bold mb-1">Notice Description / Content <span class="text-rose-400">*</span></label>
          <textarea id="fnContent" name="content" required rows="4" placeholder="Enter detailed notice message, instructions, or official directive text..." class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-sky-500 font-medium leading-relaxed"></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-300 font-bold mb-1 flex items-center gap-1">
              <span class="material-symbols-rounded text-amber-400 text-sm">attach_file</span> Attach Image or PDF <span class="text-slate-500 font-normal">(Optional)</span>
            </label>
            <input type="file" id="fnAttachment" name="attachment" accept="image/jpeg,image/png,image/webp,application/pdf" class="w-full text-slate-300 bg-slate-950 border border-slate-700 rounded-xl px-2.5 py-1.5 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-500/20 file:text-sky-300 hover:file:bg-sky-500/30">
            <p class="text-[10px] text-slate-400 mt-1">Supports JPG, PNG, WEBP images or PDF files (Max 10MB).</p>
          </div>

          <div>
            <label class="block text-slate-300 font-bold mb-1 flex items-center gap-1">
              <span class="material-symbols-rounded text-emerald-400 text-sm">schedule</span> Dispatch Timing
            </label>
            <div class="flex items-center gap-2 mb-2">
              <label class="flex items-center gap-1.5 text-slate-200 cursor-pointer">
                <input type="radio" name="dispatch_type" value="immediate" checked onchange="toggleNoticeScheduleTime()" class="accent-sky-500">
                <span class="font-bold text-sky-400">⚡ Immediate Now</span>
              </label>
              <label class="flex items-center gap-1.5 text-slate-200 cursor-pointer ml-2">
                <input type="radio" name="dispatch_type" value="scheduled" onchange="toggleNoticeScheduleTime()" class="accent-amber-500">
                <span class="font-bold text-amber-400">⏰ Scheduled</span>
              </label>
            </div>
            <div id="fnScheduleTimeWrapper" class="hidden">
              <input type="datetime-local" id="fnScheduledAt" name="scheduled_at" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-amber-500 font-mono">
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
          <button type="button" onclick="closeFlashNoticeModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl transition">Cancel</button>
          <button type="submit" id="fnSubmitBtn" class="px-5 py-2 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 text-slate-950 font-bold rounded-xl transition flex items-center gap-1.5 shadow-lg">
            <span class="material-symbols-rounded text-base">send</span> Broadcast Notice
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- FLASH NOTICE HISTORY LOG MODAL -->
  <div id="flashNoticeHistoryModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto p-6 space-y-4 shadow-2xl relative text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <h3 class="font-extrabold text-slate-100 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-sky-400">history</span> Executive Flash Notice Broadcast History
        </h3>
        <button onclick="closeFlashNoticeHistoryModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
          <span class="material-symbols-rounded text-lg">close</span>
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
