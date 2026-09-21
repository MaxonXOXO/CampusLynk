                    ` : ''}
                    <a href="/staff/leave/${l.id}/pdf" target="_blank" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Print PDF Application">
                      <span class="material-symbols-rounded text-base">print</span>
                    </a>
                  </div>
                </td>
              </tr>
            `).join('');
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">No staff leave records found.</td></tr>`;
          }
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-500">Failed to load leave records.</td></tr>`;
      }
    }

    async function processLeaveApproval(id, decision) {
      const remarks = prompt(`Enter optional remarks for ${decision.toLowerCase()}ing leave application:`, decision === 'Approve' ? 'Approved by Principal' : 'Rejected');
      if (remarks === null) return;

      try {
        const res = await fetch('/api/staff/leave/process-approval', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({
            leave_id: id,
            stage: 'Principal',
            decision: decision,
            remarks: remarks
          })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          loadLeaveLedger();
        } else {
          alert(data.message || 'Error processing leave decision.');
        }
      } catch (err) {
        alert('Server error updating leave.');
      }
    }

    // 5. SF STAFF ATTENDANCE LOADER
    async function loadSfAttendance() {
      const startDate = document.getElementById('sfAttStartDate')?.value || '';
      const endDate = document.getElementById('sfAttEndDate')?.value || '';
      const tbody = document.getElementById('sfAttendanceTableBody');
      if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">Loading SF attendance logs...</td></tr>`;

      try {
        const query = new URLSearchParams({ start_date: startDate, end_date: endDate }).toString();
        const res = await fetch(`/api/sf-attendance/data?${query}`);
        const data = await res.json();

        if (data.status === 'SUCCESS' && data.punches) {
          if (data.punches.length > 0) {
            tbody.innerHTML = data.punches.map(p => `
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="py-3 px-4">
                  <span class="font-bold text-slate-900 block">${p.staff_name || p.staff_id}</span>
                  <span class="text-xs text-slate-500 font-mono">${p.staff_id}</span>
                </td>
                <td class="py-3 px-4 text-xs font-semibold text-slate-700">${p.punch_date}</td>
                <td class="py-3 px-4">
                  <span class="text-xs font-bold text-emerald-700 block">${p.in_time || '--:--'}</span>
                  <span class="text-xs text-slate-500">${p.in_premises_status || 'Geofence Verified'}</span>
                </td>
                <td class="py-3 px-4">
                  <span class="text-xs font-bold text-indigo-700 block">${p.out_time || '--:--'}</span>
                  <span class="text-xs text-slate-500">${p.out_premises_status || '-'}</span>
                </td>
                <td class="py-3 px-4 text-center">
                  <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${p.punch_status?.includes('COMPLETED') || p.punch_status?.includes('PRESENT') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">
                    ${p.punch_status || 'PRESENT'}
                  </span>
                </td>
                <td class="py-3 px-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button onclick="deleteSfPunch(${p.id})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Punch Entry">
                      <span class="material-symbols-rounded text-base">delete</span>
                    </button>
                  </div>
                </td>
              </tr>
            `).join('');
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">No SF attendance punches found for this period.</td></tr>`;
          }
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-500">Failed to load SF attendance logs.</td></tr>`;
      }
    }

    async function deleteSfPunch(id) {
      if (!confirm('Are you sure you want to delete this punch entry?')) return;
      try {
        await fetch(`/sf-attendance/delete-punch/${id}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        loadSfAttendance();
      } catch (err) {
        alert('Failed to delete punch.');
      }
    }

    // 6. EXECUTIVE PROFILE LOADER & UPDATES
    async function loadProfileDetails() {
      try {
        const res = await fetch('/api/executive/profile/details');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.user) {
          const u = data.user;
          document.getElementById('profileDisplayName').innerText = u.name;
          document.getElementById('profileDisplayId').innerText = u.user_id;
          document.getElementById('profileDisplayEmail').innerText = u.email;
          
          const avatarImg = document.getElementById('profileAvatarImg');
          const avatarInitial = document.getElementById('profileAvatarInitial');
          if (u.photo_url) {
            avatarImg.src = u.photo_url;
            avatarImg.classList.remove('hidden');
            avatarInitial.classList.add('hidden');
          } else {
            avatarImg.classList.add('hidden');
            avatarInitial.classList.remove('hidden');
            avatarInitial.innerText = (u.name || 'P').charAt(0);
          }
          
          document.getElementById('profileInputName').value = u.name;
          document.getElementById('profileInputEmail').value = u.email;
        }
      } catch (err) {}
    }

    function previewProfilePhoto(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const avatarImg = document.getElementById('profileAvatarImg');
          const avatarInitial = document.getElementById('profileAvatarInitial');
          avatarImg.src = e.target.result;
          avatarImg.classList.remove('hidden');
          avatarInitial.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    async function submitProfileUpdate(e) {
      e.preventDefault();
      const name = document.getElementById('profileInputName').value;
      const email = document.getElementById('profileInputEmail').value;
      const photoInput = document.getElementById('profileInputPhoto');
      const alertEl = document.getElementById('profileUpdateAlert');
      const btn = document.getElementById('profileSaveBtn');

      btn.disabled = true;
      btn.innerHTML = `<span class="material-symbols-rounded animate-spin text-base">sync</span><span>Saving...</span>`;

      try {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        if (photoInput && photoInput.files && photoInput.files[0]) {
          formData.append('photo', photoInput.files[0]);
        }

        const res = await fetch('/api/executive/profile/update', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: formData
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = data.message || 'Profile details updated!';
          loadProfileDetails();
        } else {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Failed to update profile.';
        }
        setTimeout(() => alertEl.classList.add('hidden'), 4000);
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Failed to update profile.';
      } finally {
        btn.disabled = false;
        btn.innerHTML = `<span class="material-symbols-rounded text-base">save</span><span>Save Profile Updates</span>`;
      }
    }

    // 7. TODAY'S EVENTS MODAL LOGIC
    function openTodayEventsModal() {
      activeEventCategoryFilter = 'ALL';
      updateCategoryFilterTabsUI();
      renderTodayEventsModalList();

      const modal = document.getElementById('todayEventsModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function closeTodayEventsModal() {
      const modal = document.getElementById('todayEventsModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function filterEventsByCategory(cat) {
      activeEventCategoryFilter = cat;
      updateCategoryFilterTabsUI();
      renderTodayEventsModalList();
    }

    function updateCategoryFilterTabsUI() {
      const tabs = document.querySelectorAll('.evt-cat-tab');
      tabs.forEach(tab => {
        const tabCat = tab.id.replace('evtCatTab_', '');
        if (tabCat === activeEventCategoryFilter) {
          tab.className = 'evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-sky-50 text-sky-700 border border-sky-200 shadow-2xs';
        } else {
          tab.className = 'evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-slate-300 cursor-pointer';
        }
      });
    }

    function renderTodayEventsModalList() {
      const container = document.getElementById('modalEventsListContainer');
      if (!container) return;

      const events = allTodayEventsCache || [];
      const catFilter = activeEventCategoryFilter || 'ALL';

      const filtered = catFilter === 'ALL' 
        ? events 
        : events.filter(ev => (ev.organizer || 'College') === catFilter);

      const showingEl = document.getElementById('modalShowingCount');
      if (showingEl) showingEl.innerText = filtered.length;

      if (filtered.length === 0) {
        container.innerHTML = `
          <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-2xl border border-slate-200">
            <span class="material-symbols-rounded text-3xl block text-slate-400 mb-1.5">event_busy</span>
            <span class="font-semibold text-xs text-slate-600">No scheduled events found under ${catFilter === 'ALL' ? 'today' : '\'' + catFilter + '\''} category.</span>
          </div>
        `;
        return;
      }

      container.innerHTML = filtered.map(ev => {
        const org = ev.organizer || 'College';
        return `
          <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl transition flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div class="space-y-1.5 flex-1 min-w-0">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border uppercase tracking-wider bg-sky-50 text-sky-700 border-sky-200 flex items-center gap-1 shrink-0">
                  <span class="material-symbols-rounded text-xs">school</span>
                  ${org}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white text-slate-700 border border-slate-200 shrink-0">
                  ${ev.type || 'Event'}
                </span>
              </div>
              <h4 class="font-bold text-slate-900 text-sm leading-snug break-words">${ev.title}</h4>
            </div>
            <div class="shrink-0 space-y-1 md:text-right text-xs text-slate-500 border-t md:border-t-0 border-slate-200 pt-2 md:pt-0">
              <div class="flex items-center md:justify-end gap-1.5 font-mono text-slate-800 font-semibold text-xs">
                <span class="material-symbols-rounded text-sm text-sky-600">schedule</span>
                ${ev.time || '09:30 AM - 04:30 PM'}
              </div>
              <div class="flex items-center md:justify-end gap-1.5 text-slate-500 text-xs">
                <span class="material-symbols-rounded text-sm text-amber-500">location_on</span>
                ${ev.venue || 'Campus Main Auditorium'}
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    // 8. MODAL HANDLERS FOR FLASH NOTICE, EVENTS, AND PASSWORDS
    function openRegisterModal() {
      document.getElementById('registerModal').classList.remove('hidden');
      document.getElementById('registerModal').classList.add('flex');
    }
    function closeRegisterModal() {
      document.getElementById('registerModal').classList.add('hidden');
      document.getElementById('registerModal').classList.remove('flex');
    }

    function toggleRegFields() {
      const type = document.getElementById('regType').value;
      const studentDiv = document.getElementById('regStudentSpecific');
      const staffDiv = document.getElementById('regStaffSpecific');
      if (type === 'Student') {
        if (studentDiv) studentDiv.classList.remove('hidden');
        if (staffDiv) staffDiv.classList.add('hidden');
      } else {
        if (studentDiv) studentDiv.classList.add('hidden');
        if (staffDiv) staffDiv.classList.remove('hidden');
      }
    }

    async function submitNewUser(e) {
      e.preventDefault();
      const type = document.getElementById('regType').value;
      const name = document.getElementById('regName').value;
      const email = document.getElementById('regEmail').value;
      const password = document.getElementById('regPassword').value;
      const alertEl = document.getElementById('registerAlert');
      const submitBtn = document.getElementById('regSubmitBtn');

      let url = '/register/staff';
      let bodyData = {};

      if (type === 'Student') {
        url = '/register/student';
        const admNo = document.getElementById('regAdmNo').value;
        const regNo = document.getElementById('regRegisterNo').value;
        const branch = document.getElementById('regStudentBranch').value;
        const admYear = document.getElementById('regAdmYear').value;
        const semester = document.getElementById('regSemester').value;

        bodyData = {
          name,
          email,
          admNo,
          branch,
          admissionYear: parseInt(admYear) || new Date().getFullYear(),
          admissionType: 'Regular',
          semester: semester,
          password: password,
          sbteRegNo: regNo || ''
        };
      } else {
        url = '/register/staff';
        const mobileNo = document.getElementById('regStaffMobile').value;
        const branch = document.getElementById('regStaffBranch').value;
        const designation = document.getElementById('regDesignation').value;

        bodyData = {
          name,
          email,
          mobileNo,
          branch,
          designation,
          password
        };
      }

      submitBtn.disabled = true;
      submitBtn.innerHTML = `<span class="material-symbols-rounded animate-spin text-base">sync</span><span>Creating...</span>`;

      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify(bodyData)
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS' || res.ok) {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = data.message || 'User account created successfully!';
          document.getElementById('registerUserForm').reset();
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
          }, 1500);
        } else {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Error creating account.';
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Server error.';
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<span class="material-symbols-rounded text-base">person_add</span><span>Register Profile</span>`;
      }
    }

    // Edit Staff Modal Handlers
    function openEditStaffModal(mobileNo, name, email, branch, designation) {
      document.getElementById('editStaffMobile').value = mobileNo;
      document.getElementById('editStaffName').value = name;
      document.getElementById('editStaffEmail').value = email;
      document.getElementById('editStaffBranch').value = branch;
      if (document.getElementById('editStaffDesig')) document.getElementById('editStaffDesig').value = designation;
      const alertEl = document.getElementById('editStaffAlert');
      if (alertEl) alertEl.classList.add('hidden');

      const modal = document.getElementById('editStaffModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function closeEditStaffModal() {
      const modal = document.getElementById('editStaffModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    async function submitStaffEdit(e) {
      e.preventDefault();
      const mobileNo = document.getElementById('editStaffMobile').value;
      const name = document.getElementById('editStaffName').value.trim();
      const email = document.getElementById('editStaffEmail').value.trim();
      const branch = document.getElementById('editStaffBranch').value;
      const designation = document.getElementById('editStaffDesig').value;

      const alertEl = document.getElementById('editStaffAlert');
      const spinner = document.getElementById('editStaffSpinner');
      if (alertEl) alertEl.classList.add('hidden');
      if (spinner) spinner.classList.remove('hidden');

      try {
        const res = await fetch(`/api/admin/user/update-staff/${mobileNo}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ name, email, branch, designation })
        });
        const data = await res.json();
        if (spinner) spinner.classList.add('hidden');

        if (data.status === 'SUCCESS') {
          if (alertEl) {
            alertEl.className = "p-3 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 block text-xs font-semibold";
            alertEl.innerText = "Staff profile updated successfully!";
            alertEl.classList.remove('hidden');
          }
          setTimeout(() => {
            closeEditStaffModal();
            loadUsers();
          }, 1000);
