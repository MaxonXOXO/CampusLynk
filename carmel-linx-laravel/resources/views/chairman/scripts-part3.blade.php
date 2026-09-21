          <thead>
            <tr class="bg-slate-950 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
              <th class="py-2.5 px-3">Date &amp; Time</th>
              <th class="py-2.5 px-3">Title &amp; Type</th>
              <th class="py-2.5 px-3">Target Scope</th>
              <th class="py-2.5 px-3">Attachment</th>
              <th class="py-2.5 px-3 text-right">Action</th>
            </tr>
          </thead>
          <tbody id="flashNoticeHistoryBody" class="divide-y divide-slate-800/60 font-medium text-slate-300">
            <tr>
              <td colspan="5" class="py-6 text-center text-slate-500">Loading broadcast history...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- PRINCIPAL SCHEDULE EVENT MODAL -->
  <div id="principalScheduleEventModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 space-y-5 shadow-2xl relative text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
            <x-ui.icon name="event_available" class="w-5 h-5" />
          </div>
          <div>
            <h3 class="font-extrabold text-slate-100 text-base">Schedule College Institutional Event</h3>
            <p class="text-xs text-slate-400">Target College, Department, Staff, Students, or Special Groups</p>
          </div>
        </div>
        <button onclick="closePrincipalScheduleEventModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
          <x-ui.icon name="x" class="w-4 h-4" />
        </button>
      </div>

      <form id="principalScheduleEventForm" onsubmit="submitPrincipalScheduleEvent(event)" class="space-y-4 text-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-slate-300 font-bold mb-1">Event Title <span class="text-rose-400">*</span></label>
            <input type="text" id="peTitle" name="title" required placeholder="e.g., Annual Sports Meet 2026 / Placement Drive" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
          </div>
          <div>
            <label class="block text-slate-300 font-bold mb-1">Event Category <span class="text-rose-400">*</span></label>
            <select id="peCategory" name="event_category" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
              <option value="Academic">Academic Schedule</option>
              <option value="Exam">Examination &amp; Audit</option>
              <option value="Meeting">Executive Meeting</option>
              <option value="Cultural">Cultural Event</option>
              <option value="Sports">Sports Meet</option>
              <option value="Workshop">Workshop &amp; FDP</option>
              <option value="Holiday">Official Holiday</option>
              <option value="Other">Other Event</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div>
            <label class="block text-slate-300 font-bold mb-1">Event Date <span class="text-rose-400">*</span></label>
            <input type="date" id="peDate" name="event_date" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
          </div>
          <div>
            <label class="block text-slate-300 font-bold mb-1">Start Time</label>
            <input type="time" id="peStartTime" name="start_time" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
          </div>
          <div>
            <label class="block text-slate-300 font-bold mb-1">End Time</label>
            <input type="time" id="peEndTime" name="end_time" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
          </div>
        </div>

        <div class="p-3.5 bg-slate-950/60 border border-slate-800 rounded-xl space-y-3">
          <span class="block text-slate-200 font-bold text-[11px] uppercase tracking-wider flex items-center gap-1.5">
            <x-ui.icon name="users" class="w-4 h-4 text-emerald-400" /> Target Scope &amp; Audience
          </span>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-slate-400 mb-1 font-semibold">Target Audience</label>
              <select id="peTargetAudience" name="target_audience" onchange="togglePrincipalEventTargetFields()" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-emerald-500 font-medium">
                <option value="ALL_CAMPUS">🌐 College Wide (All Staff &amp; Students)</option>
                <option value="DEPT_SPECIFIC">🏫 Department Specific</option>
                <option value="STAFF_ONLY">👨‍🏫 Staff Only</option>
                <option value="STUDENTS_ONLY">🎓 Students Only</option>
                <option value="SPECIAL_GROUP">⭐ Special Group</option>
              </select>
            </div>

            <div id="peDeptWrapper" style="display:none;">
              <label class="block text-slate-400 mb-1 font-semibold">Target Department</label>
              <select id="peTargetDepartment" name="target_department" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-emerald-500 font-medium">
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

            <div id="peSemWrapper" style="display:none;">
              <label class="block text-slate-400 mb-1 font-semibold">Semester Level</label>
              <select id="peTargetSemester" name="target_semester" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-emerald-500 font-medium">
                <option value="ALL">All Semesters (S1 to S6)</option>
                <option value="S1">Semester 1 (S1)</option>
                <option value="S2">Semester 2 (S2)</option>
                <option value="S3">Semester 3 (S3)</option>
                <option value="S4">Semester 4 (S4)</option>
                <option value="S5">Semester 5 (S5)</option>
                <option value="S6">Semester 6 (S6)</option>
              </select>
            </div>

            <div id="peRoleWrapper" style="display:none;">
              <label class="block text-slate-400 mb-1 font-semibold">Staff Designation</label>
              <select id="peTargetRole" name="target_role" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-emerald-500 font-medium">
                <option value="ALL">All Staff</option>
                <option value="HOD">HODs Only</option>
                <option value="Lecturer">Lecturers</option>
                <option value="Demonstrator">Demonstrators</option>
                <option value="Trade_Instructor">Trade Instructors</option>
              </select>
            </div>

            <div id="peSpecialGroupWrapper" style="display:none;">
              <label class="block text-slate-400 mb-1 font-semibold">Special Group Name</label>
              <select id="peSpecialGroupName" name="special_group_name" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-2.5 py-1.5 text-slate-200 focus:outline-none focus:border-emerald-500 font-medium">
                <option value="Placement Cell">Placement &amp; Training Cell</option>
                <option value="NSS / NCC">NSS / NCC Units</option>
                <option value="Sports Council">Sports &amp; Athletics Council</option>
                <option value="IQAC & Audit">IQAC &amp; Quality Audit Team</option>
                <option value="Anti-Ragging Cell">Anti-Ragging &amp; Disciplinary Cell</option>
                <option value="Student Council">Student Union Council</option>
                <option value="Alumni Association">Alumni Association</option>
              </select>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-300 font-bold mb-1">Venue / Location</label>
            <input type="text" id="peVenue" name="venue" placeholder="e.g., Main Auditorium / Seminar Hall" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium">
          </div>
          <div class="flex items-center gap-4 pt-4">
            <label class="flex items-center gap-1.5 text-slate-200 cursor-pointer">
              <input type="checkbox" id="peIsFullDay" name="is_full_day" value="1" class="accent-emerald-500 w-4 h-4 rounded">
              <span class="font-bold text-slate-300">Full Day Event</span>
            </label>
            <label class="flex items-center gap-1.5 text-slate-200 cursor-pointer">
              <input type="checkbox" id="peRequiresRsvp" name="requires_rsvp" value="1" class="accent-amber-500 w-4 h-4 rounded">
              <span class="font-bold text-amber-400">RSVP / Attendance Required</span>
            </label>
          </div>
        </div>

        <div>
          <label class="block text-slate-300 font-bold mb-1">Event Description &amp; Details</label>
          <textarea id="peDescription" name="description" rows="3" placeholder="Enter details about event objectives, schedule, guest speakers, instructions..." class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-slate-100 focus:outline-none focus:border-emerald-500 font-medium leading-relaxed"></textarea>
        </div>

        <div>
          <label class="block text-slate-300 font-bold mb-1 flex items-center gap-1">
            <x-ui.icon name="link" class="w-4 h-4 text-emerald-400" /> Attach Flyer / Document <span class="text-slate-500 font-normal">(Optional PDF or Image)</span>
          </label>
          <input type="file" id="peAttachment" name="attachment" accept="image/jpeg,image/png,image/webp,application/pdf" class="w-full text-slate-300 bg-slate-950 border border-slate-700 rounded-xl px-2.5 py-1.5 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-500/20 file:text-emerald-300 hover:file:bg-emerald-500/30">
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
          <button type="button" onclick="closePrincipalScheduleEventModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-xl transition">Cancel</button>
          <button type="submit" id="peSubmitBtn" class="px-5 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl transition flex items-center gap-1.5 shadow-lg">
            <x-ui.icon name="event_available" class="w-4 h-4" /> Schedule &amp; Broadcast Event
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- PRINCIPAL EVENT HISTORY LOG MODAL -->
  <div id="principalScheduleEventHistoryModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto p-6 space-y-4 shadow-2xl relative text-left">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <h3 class="font-extrabold text-slate-100 text-base flex items-center gap-2">
          <x-ui.icon name="event_available" class="w-5 h-5 text-emerald-400" /> Scheduled Events Audit Log
        </h3>
        <button onclick="closePrincipalScheduleEventHistoryModal()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition">
          <x-ui.icon name="x" class="w-4 h-4" />
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-950 text-slate-400 uppercase text-[10px] font-bold border-b border-slate-800">
              <th class="py-2.5 px-3">Date &amp; Time</th>
              <th class="py-2.5 px-3">Title &amp; Category</th>
              <th class="py-2.5 px-3">Target Scope</th>
              <th class="py-2.5 px-3 text-center">RSVP</th>
              <th class="py-2.5 px-3">Attachment</th>
              <th class="py-2.5 px-3 text-right">Action</th>
            </tr>
          </thead>
          <tbody id="principalEventHistoryBody" class="divide-y divide-slate-800/60 font-medium text-slate-300">
            <tr>
              <td colspan="6" class="py-6 text-center text-slate-500">Loading events...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- EXECUTIVE PROFILE SETTINGS MODAL -->
  <div id="executiveProfileModal" class="fixed inset-0 bg-black/70 backdrop-blur-md z-50 hidden items-center justify-center p-4 transition-all duration-300">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-5 relative">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="p-2 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center">
            <span class="material-symbols-rounded text-lg">manage_accounts</span>
          </div>
          <div>
            <h3 class="font-extrabold text-slate-100 text-base">Executive Profile Settings</h3>
            <p class="text-xs text-slate-400">Update account credentials, login ID, and profile picture</p>
          </div>
        </div>
        <button onclick="closeExecutiveProfileModal()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <div id="execProfileAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>

      <form id="execProfileForm" onsubmit="saveExecutiveProfile(event)" class="space-y-4">
        <div class="flex items-center gap-4 p-3 bg-slate-950/60 border border-slate-800/80 rounded-2xl">
          <div class="relative group shrink-0">
            <img id="execModalAvatarPrev" src="/storage/avatars/default.png" onerror="this.src='/storage/avatars/default.png'" class="w-16 h-16 rounded-2xl object-cover border-2 border-amber-500/40 shadow-md">
            <label for="execModalPhotoInput" class="absolute inset-0 bg-black/60 rounded-2xl opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-bold cursor-pointer transition">
              <span class="material-symbols-rounded text-sm">photo_camera</span>
            </label>
          </div>
          <div class="flex-grow space-y-1">
            <span class="text-xs font-bold text-slate-200 block">Profile Picture</span>
            <p class="text-[11px] text-slate-400">PNG, JPG or GIF (Max 2MB)</p>
            <input type="file" id="execModalPhotoInput" accept="image/*" class="hidden" onchange="previewExecAvatar(this)">
            <button type="button" onclick="document.getElementById('execModalPhotoInput').click()" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-amber-300 rounded-lg text-[11px] font-bold border border-slate-700 transition cursor-pointer">
              Choose New Photo
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
          <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Full Name</label>
            <input type="text" id="execModalName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
          </div>
          <div>
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Login ID / Mobile No.</label>
            <input type="text" id="execModalMobile" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white font-mono outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
          </div>
          <div class="sm:col-span-2">
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Email Address</label>
            <input type="email" id="execModalEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition">
          </div>
          <div class="sm:col-span-2">
            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">New Password (Leave blank to keep unchanged)</label>
            <input type="password" id="execModalPassword" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 transition" placeholder="Minimum 4 characters">
          </div>
        </div>

        <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-800">
          <button type="button" onclick="closeExecutiveProfileModal()" class="px-4 py-2 border border-slate-800 hover:bg-slate-800 text-slate-300 rounded-xl font-bold text-xs transition cursor-pointer">
            Cancel
          </button>
          <button type="submit" id="execProfileSubmitBtn" class="px-5 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black rounded-xl text-xs transition shadow-lg shadow-amber-500/20 cursor-pointer flex items-center gap-1.5">
            <span class="material-symbols-rounded text-sm">save</span> Save Profile Settings
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openExecutiveProfileModal() {
      const modal = document.getElementById('executiveProfileModal');
      const alertBox = document.getElementById('execProfileAlert');
      if (alertBox) alertBox.classList.add('hidden');

      fetch('/api/executive/profile/details')
        .then(r => r.json())
        .then(res => {
          if (res.status === 'SUCCESS' && res.data) {
            document.getElementById('execModalName').value = res.data.name || '';
            document.getElementById('execModalMobile').value = res.data.mobile_no || '';
            document.getElementById('execModalEmail').value = res.data.email || '';
            document.getElementById('execModalPassword').value = '';
            if (res.data.photo_url) {
              document.getElementById('execModalAvatarPrev').src = res.data.photo_url;
            }
          }
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        })
        .catch(err => {
          console.error(err);
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        });
    }

    function closeExecutiveProfileModal() {
      const modal = document.getElementById('executiveProfileModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function previewExecAvatar(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('execModalAvatarPrev').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function saveExecutiveProfile(e) {
      e.preventDefault();
      const alertBox = document.getElementById('execProfileAlert');
      const btn = document.getElementById('execProfileSubmitBtn');
      btn.disabled = true;
      btn.innerText = 'Saving...';

      const formData = new FormData();
      formData.append('name', document.getElementById('execModalName').value.trim());
      formData.append('mobile_no', document.getElementById('execModalMobile').value.trim());
      formData.append('email', document.getElementById('execModalEmail').value.trim());
      
      const pwd = document.getElementById('execModalPassword').value.trim();
      if (pwd) {
        formData.append('new_password', pwd);
      }

      const fileInput = document.getElementById('execModalPhotoInput');
      if (fileInput && fileInput.files[0]) {
        formData.append('photo', fileInput.files[0]);
      }

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      fetch('/api/executive/profile/update', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken || ''
        },
        body: formData
      })
      .then(r => r.json())
      .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-sm">save</span> Save Profile Settings';
        
        alertBox.classList.remove('hidden');
        if (res.status === 'SUCCESS') {
          alertBox.className = 'p-3 rounded-xl text-xs font-bold border bg-emerald-500/10 text-emerald-300 border-emerald-500/30 mb-3';
          alertBox.innerText = res.message || 'Profile settings updated successfully!';
          setTimeout(() => {
            location.reload();
          }, 1200);
        } else {
          alertBox.className = 'p-3 rounded-xl text-xs font-bold border bg-rose-500/10 text-rose-300 border-rose-500/30 mb-3';
          alertBox.innerText = res.message || 'Failed to update profile settings.';
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-sm">save</span> Save Profile Settings';
        alertBox.classList.remove('hidden');
        alertBox.className = 'p-3 rounded-xl text-xs font-bold border bg-rose-500/10 text-rose-300 border-rose-500/30 mb-3';
        alertBox.innerText = 'Network error: ' + err.message;
      });
    }

    /* Theme Toggle Logic */
    function initTheme() {
      const savedTheme = localStorage.getItem('carmel_theme') || 'dark';
      if (savedTheme === 'light') {
        document.body.classList.add('light-theme');
        updateThemeToggleUI('light');
      } else {
        document.body.classList.remove('light-theme');
        updateThemeToggleUI('dark');
      }
    }

    function toggleTheme() {
      const isLight = document.body.classList.toggle('light-theme');
      const theme = isLight ? 'light' : 'dark';
      localStorage.setItem('carmel_theme', theme);
      updateThemeToggleUI(theme);
    }

    function updateThemeToggleUI(theme) {
      const icon = document.getElementById('themeToggleIcon');
      const text = document.getElementById('themeToggleText');
      const btn = document.getElementById('themeToggleBtn');
      if (!icon || !btn) return;
      if (theme === 'light') {
        icon.innerText = 'dark_mode';
        icon.className = 'material-symbols-rounded text-base text-indigo-600';
        if (text) text.innerText = 'Dark Mode';
        btn.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-300 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-premium cursor-pointer shadow-sm';
      } else {
        icon.innerText = 'light_mode';
        icon.className = 'material-symbols-rounded text-base text-amber-400';
        if (text) text.innerText = 'Light Mode';
        btn.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 font-bold text-xs transition-premium cursor-pointer shadow-sm';
      }
    }

    // =========================================================================
    // TODAY'S EVENTS MODAL LOGIC
    // =========================================================================
    let allTodayEventsCache = [];
    let todayEventCountsCache = {};
    let activeEventCategoryFilter = 'ALL';

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
