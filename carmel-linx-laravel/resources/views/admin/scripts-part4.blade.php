        } else {
          if (alertEl) {
            alertEl.className = "p-3 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 block text-xs font-semibold";
            alertEl.innerText = data.message || "Failed to update profile.";
            alertEl.classList.remove('hidden');
          }
        }
      } catch (err) {
        if (spinner) spinner.classList.add('hidden');
        if (alertEl) {
          alertEl.className = "p-3 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 block text-xs font-semibold";
          alertEl.innerText = "Server connection error.";
          alertEl.classList.remove('hidden');
        }
      }
    }

    // Password Reset Handlers
    let selectedUserForReset = null;
    function triggerPasswordReset(userId, userType, userName) {
      selectedUserForReset = { userId, userType };
      const elUser = document.getElementById('pwTargetId');
      if (elUser) elUser.innerText = `${userName} (${userId})`;
      const inp = document.getElementById('pwResetNew');
      if (inp) inp.value = '';
      const alertEl = document.getElementById('passwordResetAlert');
      if (alertEl) alertEl.classList.add('hidden');
      const modal = document.getElementById('passwordModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function openPasswordModal(mobileNo) {
      triggerPasswordReset(mobileNo, 'staff', mobileNo);
    }

    function closePasswordModal() {
      document.getElementById('passwordModal').classList.add('hidden');
      document.getElementById('passwordModal').classList.remove('flex');
      selectedUserForReset = null;
    }

    async function submitPasswordReset(e) {
      e.preventDefault();
      const targetId = selectedUserForReset ? selectedUserForReset.userId : document.getElementById('pwResetMobile')?.value;
      const newPw = document.getElementById('pwResetNew')?.value.trim();
      const alertEl = document.getElementById('passwordResetAlert');

      if (!newPw || newPw.length < 4) {
        if (alertEl) {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200 block';
          alertEl.innerText = 'Password must be at least 4 characters long.';
          alertEl.classList.remove('hidden');
        }
        return;
      }

      try {
        const res = await fetch('/api/admin/users/reset-password', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ identifier: targetId, new_password: newPw })
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = 'Password reset successfully!';
          setTimeout(() => closePasswordModal(), 1200);
        } else {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Reset failed.';
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Server error resetting password.';
      }
    }

    // Confirm Delete User Handler
    async function confirmDeleteUser(userId, userType, userName) {
      if (!confirm(`Are you absolutely sure you want to permanently delete the profile of ${userName} (${userId})? This action will remove all database credentials.`)) return;
      try {
        const res = await fetch('/api/admin/user/delete', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ userId, userType })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          loadUsers();
          loadExecutiveMetrics();
        } else {
          alert(data.message || 'Failed to delete user.');
        }
      } catch (err) {
        alert('Server error deleting user.');
      }
    }

    async function openAuditModal(userId, name) {
      document.getElementById('auditTargetUser').innerText = `${name} (${userId})`;
      document.getElementById('auditModal').classList.remove('hidden');
      document.getElementById('auditModal').classList.add('flex');
      
      const tbody = document.getElementById('userAuditBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400 font-medium">Loading audit history...</td></tr>`;

      try {
        const res = await fetch(`/api/admin/users/audit/${userId}`);
        const data = await res.json();
        if (data.audit && data.audit.length > 0) {
          tbody.innerHTML = data.audit.map(a => `
            <tr class="hover:bg-slate-50/60 transition-colors border-b border-slate-100">
              <td class="p-3 text-slate-500 font-mono whitespace-nowrap text-xs">${a.created_at || a.timestamp || '-'}</td>
              <td class="p-3 font-semibold text-slate-800 text-sm">${a.performed_by_name || a.performed_by || 'System'}</td>
              <td class="p-3">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold ${a.action === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (a.action === 'Registered' ? 'bg-blue-50 text-blue-700 border border-blue-200' : (a.action === 'Suspended' || a.action === 'Deleted' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200'))}">
                  ${a.action}
                </span>
              </td>
              <td class="p-3 text-slate-600 text-sm">${a.details || '-'}</td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400 font-medium">No audit records found for this user.</td></tr>`;
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-rose-500 font-medium">Failed to load audit trail.</td></tr>`;
      }
    }
    function closeAuditModal() {
      document.getElementById('auditModal').classList.add('hidden');
      document.getElementById('auditModal').classList.remove('flex');
    }

    function openFlashNoticeModal() {
      document.getElementById('flashNoticeModal').classList.remove('hidden');
      document.getElementById('flashNoticeModal').classList.add('flex');
    }
    function closeFlashNoticeModal() {
      document.getElementById('flashNoticeModal').classList.add('hidden');
      document.getElementById('flashNoticeModal').classList.remove('flex');
    }

    async function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400 font-medium">Loading audit trail logs...</td></tr>`;

      try {
        const res = await fetch('/api/audit-logs');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.logs && data.logs.length > 0) {
          tbody.innerHTML = data.logs.map(log => `
            <tr class="hover:bg-slate-50/70 transition-colors border-b border-slate-100 text-sm">
              <td class="py-3 px-4 font-mono text-slate-500 shrink-0 text-xs">${log.created_at || log.timestamp || '-'}</td>
              <td class="py-3 px-4 font-semibold text-slate-800">${log.performed_by_name || log.performed_by || 'System'}</td>
              <td class="py-3 px-4 font-mono text-slate-700">${log.target_name ? `${log.target_name} (${log.target_id || '-'})` : (log.target_id || '-')}</td>
              <td class="py-3 px-4">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold ${log.action === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (log.action === 'Registered' ? 'bg-blue-50 text-blue-700 border border-blue-200' : (log.action === 'Suspended' || log.action === 'Deleted' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200'))}">
                  ${log.action}
                </span>
              </td>
              <td class="py-3 px-4 font-mono text-slate-500 text-xs">${log.ip_address || '127.0.0.1'}</td>
              <td class="py-3 px-4 text-slate-600 max-w-xs truncate">${log.details || '-'}</td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400 font-medium">No audit log entries recorded.</td></tr>`;
        }
      } catch (err) {
        if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-500 font-medium">Failed to load audit trail.</td></tr>`;
      }
    }

    function toggleNoticeTargetFields() {
      const scope = document.getElementById('fnTargetAudience')?.value;
      const deptWrapper = document.getElementById('fnDeptWrapper');
      const semWrapper = document.getElementById('fnSemWrapper');
      if (deptWrapper) deptWrapper.style.display = (scope === 'STAFF_DEPT' || scope === 'STUDENTS_DEPT_SEM') ? 'block' : 'block';
      if (semWrapper) semWrapper.style.display = (scope === 'STUDENTS_DEPT_SEM') ? 'block' : 'block';
    }

    function toggleNoticeDispatchTiming() {
      const dispatchType = document.querySelector('input[name="dispatch_type"]:checked')?.value;
      const scheduledWrapper = document.getElementById('fnScheduledWrapper');
      if (scheduledWrapper) {
        scheduledWrapper.style.display = dispatchType === 'scheduled' ? 'block' : 'none';
      }
    }

    async function submitFlashNotice(e) {
      e.preventDefault();
      const form = document.getElementById('flashNoticeForm');
      const formData = new FormData(form);
      const alertEl = document.getElementById('flashNoticeAlert');
      const btn = form.querySelector('button[type="submit"]');

      if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div><span>Broadcasting...</span>`;
      }

      try {
        const res = await fetch('/api/admin/flash-notices/broadcast', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: formData
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = data.message || 'Flash notice broadcasted successfully!';
          form.reset();
          toggleNoticeDispatchTiming();
          loadFlashNoticeStats();
          setTimeout(() => closeFlashNoticeModal(), 1500);
        } else {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Failed to broadcast notice.';
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Failed to broadcast notice.';
      } finally {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = `<span class="material-symbols-rounded text-base">send</span><span>Broadcast Notice</span>`;
        }
      }
    }

    async function openFlashNoticeHistoryModal() {
      document.getElementById('flashNoticeHistoryModal').classList.remove('hidden');
      document.getElementById('flashNoticeHistoryModal').classList.add('flex');
      const tbody = document.getElementById('flashNoticeHistoryBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">Loading broadcast history...</td></tr>`;

      try {
        const res = await fetch('/api/admin/flash-notices');
        const data = await res.json();
        if (data.notices && data.notices.length > 0) {
          tbody.innerHTML = data.notices.map(n => `
            <tr>
              <td class="p-3 text-slate-500">${n.created_at?.slice(0, 10) || '-'}</td>
              <td class="p-3">
                <span class="font-semibold text-slate-900 block">${n.title}</span>
                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold ${n.priority === 'Urgent' ? 'bg-rose-50 text-rose-700' : 'bg-slate-100 text-slate-700'}">${n.priority || 'Normal'}</span>
              </td>
              <td class="p-3"><span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-xs font-semibold">${n.target_audience}</span></td>
              <td class="p-3 text-right">
                <button onclick="revokeFlashNotice(${n.id})" class="px-2 py-1 bg-rose-50 text-rose-600 rounded text-xs font-semibold hover:bg-rose-100">Revoke</button>
              </td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">No active broadcast notices.</td></tr>`;
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-rose-500">Failed to load history.</td></tr>`;
      }
    }
    function closeFlashNoticeHistoryModal() {
      document.getElementById('flashNoticeHistoryModal').classList.add('hidden');
      document.getElementById('flashNoticeHistoryModal').classList.remove('flex');
    }

    async function revokeFlashNotice(id) {
      if (!confirm('Revoke this flash notice broadcast?')) return;
      try {
        await fetch(`/api/admin/flash-notices/revoke/${id}`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        openFlashNoticeHistoryModal();
        loadFlashNoticeStats();
      } catch (err) {
        alert('Failed to revoke notice.');
      }
    }

    function openPrincipalScheduleEventModal() {
      document.getElementById('principalScheduleEventModal').classList.remove('hidden');
      document.getElementById('principalScheduleEventModal').classList.add('flex');
    }
    function closePrincipalScheduleEventModal() {
      document.getElementById('principalScheduleEventModal').classList.add('hidden');
      document.getElementById('principalScheduleEventModal').classList.remove('flex');
    }

    function togglePrincipalEventTargetFields() {
      const scope = document.getElementById('peTargetAudience')?.value;
      const deptWrapper = document.getElementById('peDeptWrapper');
      const semWrapper = document.getElementById('peSemWrapper');
      const specialGroupWrapper = document.getElementById('peSpecialGroupWrapper');

      if (deptWrapper) deptWrapper.style.display = (scope === 'DEPT_SPECIFIC' || scope === 'STUDENTS_ONLY') ? 'block' : 'none';
      if (semWrapper) semWrapper.style.display = (scope === 'STUDENTS_ONLY') ? 'block' : 'none';
      if (specialGroupWrapper) specialGroupWrapper.style.display = (scope === 'SPECIAL_GROUP') ? 'block' : 'none';
    }

    async function submitPrincipalScheduleEvent(e) {
      e.preventDefault();
      const form = document.getElementById('principalScheduleEventForm');
      const formData = new FormData(form);
      const btn = document.getElementById('peSubmitBtn');
      btn.disabled = true;
      btn.innerHTML = `<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div><span>Scheduling...</span>`;

      try {
        const res = await fetch('/api/principal/events/schedule', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: formData
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          form.reset();
          loadExecutiveMetrics();
          loadPrincipalEventStats();
          closePrincipalScheduleEventModal();
          alert(data.message || 'Campus event scheduled & broadcasted successfully!');
        } else {
          alert('Error: ' + (data.message || 'Failed to schedule event.'));
        }
      } catch (err) {
        alert('Failed to schedule event.');
      } finally {
        btn.disabled = false;
        btn.innerHTML = `<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><path d="M21 14V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8"/><path d="M3 10h18"/><path d="m16 20 2 2 4-4"/></svg><span>Schedule &amp; Broadcast Event</span>`;
      }
    }

    async function openPrincipalScheduleEventHistoryModal() {
      document.getElementById('principalScheduleEventHistoryModal').classList.remove('hidden');
      document.getElementById('principalScheduleEventHistoryModal').classList.add('flex');
      const tbody = document.getElementById('principalEventHistoryBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">Loading events...</td></tr>`;

      try {
        const res = await fetch('/api/principal/events');
        const data = await res.json();
        if (data.events && data.events.length > 0) {
          tbody.innerHTML = data.events.map(ev => `
            <tr>
              <td class="p-3 text-slate-500 font-mono">${ev.event_date}</td>
              <td class="p-3">
                <span class="font-semibold text-slate-900 block">${ev.title}</span>
                <span class="px-1.5 py-0.2 text-[10px] rounded font-semibold bg-emerald-50 text-emerald-700">${ev.event_category || 'Event'}</span>
              </td>
              <td class="p-3"><span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded text-xs font-semibold">${ev.target_audience}</span></td>
              <td class="p-3 text-right">
                <button onclick="deletePrincipalEvent(${ev.id})" class="p-1 text-slate-400 hover:text-rose-600 rounded"><span class="material-symbols-rounded text-base">delete</span></button>
              </td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">No scheduled events found.</td></tr>`;
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-rose-500">Failed to load events.</td></tr>`;
      }
    }
    function closePrincipalScheduleEventHistoryModal() {
      document.getElementById('principalScheduleEventHistoryModal').classList.add('hidden');
      document.getElementById('principalScheduleEventHistoryModal').classList.remove('flex');
    }

    async function deletePrincipalEvent(id) {
      if (!confirm('Delete this scheduled event?')) return;
      try {
        await fetch(`/api/principal/events/${id}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        openPrincipalScheduleEventHistoryModal();
        loadExecutiveMetrics();
        loadPrincipalEventStats();
      } catch (err) {
        alert('Failed to delete event.');
      }
    }

    // 10. CAMPUS GEOFENCE INTERACTIVE MAP & PINPOINT CONTROLLER
    let geofenceMap = null;
    let geofenceMarker = null;
    let geofenceCircle = null;

    async function openGeofenceModal() {
      const modal = document.getElementById('geofenceModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      try {
        const res = await fetch('/api/admin/geofence/settings');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.geofence) {
          const g = data.geofence;
          document.getElementById('geoCampusName').value = g.campus_name || 'Carmel polytechnic College Campus punapra';
          document.getElementById('geoLat').value = g.centroid_lat;
          document.getElementById('geoLng').value = g.centroid_lng;
          document.getElementById('geoRadius').value = g.radius_meters || 110;
          document.getElementById('geoAccuracy').value = g.max_accuracy_meters || 100;
          document.getElementById('geoCoordDisplay').innerText = `${parseFloat(g.centroid_lat).toFixed(8)}, ${parseFloat(g.centroid_lng).toFixed(8)}`;
          document.getElementById('geoBtnGmapsLink').href = `https://www.google.com/maps?q=${g.centroid_lat},${g.centroid_lng}`;
        }
      } catch (err) {}

      initOrUpdateGeofenceMap();
    }

    function closeGeofenceModal() {
      const modal = document.getElementById('geofenceModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function initOrUpdateGeofenceMap() {
      const lat = parseFloat(document.getElementById('geoLat').value) || 9.43727187;
      const lng = parseFloat(document.getElementById('geoLng').value) || 76.34358649;
      const radius = parseInt(document.getElementById('geoRadius').value) || 110;

      setTimeout(() => {
        const container = document.getElementById('geofenceMapContainer');
        if (!container) return;

        if (!geofenceMap) {
          geofenceMap = L.map('geofenceMapContainer').setView([lat, lng], 16);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
          }).addTo(geofenceMap);

          geofenceMarker = L.marker([lat, lng], { draggable: true }).addTo(geofenceMap);
          geofenceCircle = L.circle([lat, lng], {
            color: '#2563eb',
            fillColor: '#3b82f6',
            fillOpacity: 0.25,
            radius: radius
          }).addTo(geofenceMap);

          geofenceMarker.on('dragend', function(e) {
            const pos = geofenceMarker.getLatLng();
            updateGeofenceCoordinates(pos.lat, pos.lng);
          });
