<div class="space-y-6">
  <!-- Top Banner / Header -->
  <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex items-center justify-between">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
        <i data-lucide="user-cog" class="w-6 h-6"></i>
      </div>
      <div>
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">My Profile & Security Settings</h2>
        <p class="text-sm text-slate-500 mt-0.5">Manage your personal account credentials, profile avatar, and view security activity logs.</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 {{ ($hideAuditLog ?? false) ? 'lg:grid-cols-2' : 'lg:grid-cols-3' }} gap-6">
    <!-- Profile & Photo Upload Card -->
    <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs space-y-5 flex flex-col justify-between">
      <div>
        <h4 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
          <i data-lucide="user" class="w-4 h-4 text-blue-600"></i>
          <span>Personal Details</span>
        </h4>

        <div class="flex flex-col items-center text-center space-y-3">
          <div class="relative group cursor-pointer" title="Click to change profile picture">
            <div id="staffAvatarWrapper" class="w-24 h-24 rounded-full overflow-hidden border-2 border-slate-200 bg-slate-100 flex items-center justify-center shadow-md relative transition-all group-hover:border-blue-600">
              <img id="staffProfileImg" src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-full h-full object-cover">
            </div>
            <label for="staffPhotoUploadInput" class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer rounded-full text-white text-xs font-semibold text-center gap-1 p-1">
              <i data-lucide="camera" class="w-5 h-5"></i>
              <span class="text-[11px]">Change Photo</span>
            </label>
            <input type="file" id="staffPhotoUploadInput" accept="image/*" class="hidden" onchange="handleStaffPhotoUpload(event)">
          </div>
          <div id="staffPhotoUploadStatus" class="text-xs font-semibold mt-1 text-emerald-600 hidden"></div>
          <div>
            <h3 class="font-bold text-slate-900 text-base leading-tight">{{ session('userName') }}</h3>
            <span class="font-semibold text-blue-700 uppercase tracking-wider text-xs block mt-1.5 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-100 inline-block">{{ session('userBranch') }} {{ session('userRole') }}</span>
          </div>
        </div>

        <div class="border-t border-slate-100 pt-4 mt-5 space-y-3 text-sm">
          <div class="flex justify-between items-center">
            <span class="text-slate-500">Mobile / Account ID:</span>
            <span class="font-mono font-semibold text-slate-900">{{ session('userId') }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-slate-500">Department / Branch:</span>
            <span class="font-semibold text-slate-900">{{ session('userBranch') ?: 'Institutional' }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-slate-500">Role Designation:</span>
            <span class="font-semibold text-slate-900">{{ session('userRole') }}</span>
          </div>
        </div>
      </div>

      <div class="pt-3">
        <label for="staffPhotoUploadInput" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl font-semibold text-xs transition-colors cursor-pointer flex items-center justify-center gap-2">
          <i data-lucide="upload" class="w-4 h-4"></i>
          <span>Upload New Profile Photo</span>
        </label>
      </div>
    </div>

    <!-- Password Change Card -->
    <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs space-y-4">
      <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2 text-sm">
        <i data-lucide="shield-check" class="w-4 h-4 text-blue-600"></i>
        <span>Security Credentials</span>
      </h4>

      <form id="staffPasswordChangeForm" onsubmit="handleStaffPasswordChange(event)" class="space-y-4">
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Current Password</label>
          <div class="relative">
            <input type="password" id="staffCurrentPassword" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all placeholder:text-slate-400" placeholder="Enter current password">
            <button type="button" onclick="togglePasswordInputVisibility('staffCurrentPassword', this)" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 cursor-pointer">
              <i data-lucide="eye" class="w-4 h-4"></i>
            </button>
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">New Password</label>
          <div class="relative">
            <input type="password" id="staffNewPassword" required minlength="4" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all placeholder:text-slate-400" placeholder="Enter new password (min 4 chars)">
            <button type="button" onclick="togglePasswordInputVisibility('staffNewPassword', this)" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 cursor-pointer">
              <i data-lucide="eye" class="w-4 h-4"></i>
            </button>
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Confirm New Password</label>
          <div class="relative">
            <input type="password" id="staffConfirmPassword" required minlength="4" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all placeholder:text-slate-400" placeholder="Re-enter new password">
            <button type="button" onclick="togglePasswordInputVisibility('staffConfirmPassword', this)" class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 cursor-pointer">
              <i data-lucide="eye" class="w-4 h-4"></i>
            </button>
          </div>
        </div>

        <div id="staffPasswordAlert" class="hidden p-3 rounded-xl text-xs font-semibold border"></div>

        <button type="submit" id="btnSaveStaffPassword" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-all cursor-pointer shadow-xs flex items-center justify-center gap-2">
          <i data-lucide="key" class="w-4 h-4"></i>
          <span>Update Password</span>
        </button>
      </form>
    </div>

    @if(!($hideAuditLog ?? false))
    <!-- Security Audit Logs Card -->
    <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs flex flex-col space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h4 class="font-bold text-slate-900 flex items-center gap-2 text-sm">
          <i data-lucide="history" class="w-4 h-4 text-blue-600"></i>
          <span>Security Audit Log</span>
        </h4>
        <button type="button" onclick="loadSelfSecurityLogs()" class="text-xs text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1 cursor-pointer">
          <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
          <span>Refresh</span>
        </button>
      </div>

      <div class="flex-grow max-h-[320px] overflow-y-auto custom-scrollbar border border-slate-200 rounded-xl">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider text-[10px]">
              <th class="p-3">Time</th>
              <th class="p-3">Action</th>
              <th class="p-3">Details</th>
            </tr>
          </thead>
          <tbody id="selfSecurityLogsTable">
            <tr><td colspan="3" class="p-4 text-center text-slate-500 font-medium">Querying account logs...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </div>
</div>

<script>
  function togglePasswordInputVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isPwd = input.type === 'password';
    input.type = isPwd ? 'text' : 'password';
    btn.innerHTML = isPwd ? `<i data-lucide="eye-off" class="w-4 h-4"></i>` : `<i data-lucide="eye" class="w-4 h-4"></i>`;
    if (window.initLucide) window.initLucide();
  }

  function handleStaffPhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('photo', file);

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const statusEl = document.getElementById('staffPhotoUploadStatus');

    if (statusEl) {
      statusEl.classList.remove('hidden');
      statusEl.className = 'text-xs font-semibold mt-1 text-amber-600 block';
      statusEl.innerText = 'Uploading photo...';
    }

    fetch('/api/staff/update-photo', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken || ''
      },
      body: formData
    })
    .then(async res => {
      const data = await res.json().catch(() => ({ status: 'ERROR', message: 'Invalid response from server.' }));
      if (res.ok && data.status === 'SUCCESS') {
        const photoUrl = data.photo_url + '?t=' + new Date().getTime();
        document.querySelectorAll('#staffProfileImg, #sidebarAvatarContainer img, aside img.rounded-full, #sidebarStaffImg').forEach(img => {
          img.src = photoUrl;
        });

        if (statusEl) {
          statusEl.className = 'text-xs font-semibold mt-1 text-emerald-600 block';
          statusEl.innerText = 'Photo updated successfully!';
          setTimeout(() => statusEl.classList.add('hidden'), 4000);
        }

        if (typeof showGlobalMessage === 'function') {
          showGlobalMessage('Profile photo updated successfully!');
        }
      } else {
        if (statusEl) {
          statusEl.className = 'text-xs font-semibold mt-1 text-rose-600 block';
          statusEl.innerText = data.message || 'Photo upload failed.';
        }
      }
    })
    .catch(err => {
      console.error('Photo upload error:', err);
      if (statusEl) {
        statusEl.className = 'text-xs font-semibold mt-1 text-rose-600 block';
        statusEl.innerText = 'Error uploading photo. Please check file format and size.';
      }
    });
  }

  function handleStaffPasswordChange(event) {
    event.preventDefault();

    const oldPassword = document.getElementById('staffCurrentPassword').value.trim();
    const newPassword = document.getElementById('staffNewPassword').value.trim();
    const confirmPassword = document.getElementById('staffConfirmPassword').value.trim();
    const alertEl = document.getElementById('staffPasswordAlert');
    const btn = document.getElementById('btnSaveStaffPassword');

    if (newPassword !== confirmPassword) {
      if (alertEl) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-rose-50 text-rose-700 border-rose-200 block';
        alertEl.innerText = 'New password and confirmation password do not match.';
      }
      return;
    }

    if (newPassword.length < 4) {
      if (alertEl) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-rose-50 text-rose-700 border-rose-200 block';
        alertEl.innerText = 'New password must be at least 4 characters long.';
      }
      return;
    }

    if (btn) btn.disabled = true;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('/api/staff/change-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken || ''
      },
      body: JSON.stringify({
        oldPassword: oldPassword,
        newPassword: newPassword
      })
    })
    .then(res => res.json())
    .then(data => {
      if (btn) btn.disabled = false;
      if (data.status === 'SUCCESS') {
        if (alertEl) {
          alertEl.classList.remove('hidden');
          alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-emerald-50 text-emerald-700 border-emerald-200 block';
          alertEl.innerText = 'Password updated successfully!';
        }
        document.getElementById('staffPasswordChangeForm').reset();
        loadSelfSecurityLogs();

        if (typeof showGlobalMessage === 'function') {
          showGlobalMessage('Password updated successfully!');
        }
      } else {
        if (alertEl) {
          alertEl.classList.remove('hidden');
          alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-rose-50 text-rose-700 border-rose-200 block';
          alertEl.innerText = data.message || 'Failed to change password.';
        }
      }
    })
    .catch(() => {
      if (btn) btn.disabled = false;
      if (alertEl) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-rose-50 text-rose-700 border-rose-200 block';
        alertEl.innerText = 'Network error updating password.';
      }
    });
  }

  function loadSelfSecurityLogs() {
    const tbody = document.getElementById('selfSecurityLogsTable') || document.getElementById('securityLogsTable');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500 font-medium">Querying security log records...</td></tr>`;

    fetch('/api/audit-logs?targetId={{ session("userId") }}')
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          tbody.innerHTML = "";
          if (!data.logs || data.logs.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500 font-medium">No account security logs found.</td></tr>`;
            return;
          }
          data.logs.forEach(log => {
            const tr = document.createElement('tr');
            tr.className = "border-b border-slate-100 text-xs hover:bg-slate-50/60 transition-colors";
            const date = new Date(log.created_at).toLocaleString();
            tr.innerHTML = `
              <td class="p-3 text-slate-500 font-mono text-[11px] whitespace-nowrap">${date}</td>
              <td class="p-3"><span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">${log.action}</span></td>
              <td class="p-3 text-slate-700 text-xs">${log.details || '—'}</td>
            `;
            tbody.appendChild(tr);
          });
          if (window.initLucide) window.initLucide();
        } else {
          tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-rose-600 font-semibold">Failed to load logs.</td></tr>`;
        }
      })
      .catch(() => {
        tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-rose-600 font-semibold">Error querying logs.</td></tr>`;
      });
  }

  window.addEventListener('pageshow', function (event) {
    if (event.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
      fetch('/api/system/session-check', { method: 'GET', cache: 'no-store' })
        .then(r => r.json())
        .then(data => {
          if (!data || data.status !== 'ACTIVE') {
            window.location.replace('/');
          }
        })
        .catch(() => {
          window.location.replace('/');
        });
    }
  });

  document.addEventListener('DOMContentLoaded', () => {
    if (window.initLucide) window.initLucide();
  });
</script>
