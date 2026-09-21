<div id="panelProfile" class="hidden space-y-6">
          <div class="flex items-center gap-3 bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
              <span class="material-symbols-rounded text-2xl">manage_accounts</span>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 text-lg">Executive Profile &amp; Account Security</h3>
              <p class="text-xs text-slate-500 mt-0.5">Manage administrative credentials, official contact channels, and system security.</p>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
              <div class="flex flex-col items-center text-center space-y-3">
                <div class="relative group cursor-pointer" onclick="document.getElementById('profileInputPhoto').click()" title="Click to change profile picture">
                  <div class="w-24 h-24 rounded-full bg-blue-600 text-white font-bold text-2xl flex items-center justify-center shadow-md overflow-hidden border-4 border-slate-50 relative">
                    <img id="profileAvatarImg" src="" alt="Avatar" class="w-full h-full object-cover hidden">
                    <span id="profileAvatarInitial">P</span>
                    <div class="absolute inset-0 bg-black/40 text-white flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                      <span class="material-symbols-rounded text-xl">photo_camera</span>
                      <span class="text-[10px] font-semibold">Change</span>
                    </div>
                  </div>
                  <div class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center text-white" title="Verified Account">
                    <span class="material-symbols-rounded text-xs">check</span>
                  </div>
                </div>
                <div>
                  <h4 id="profileDisplayName" class="font-bold text-slate-900 text-lg">Fr. Antony Varghese CMI</h4>
                  <p id="profileDisplayRole" class="text-xs text-blue-600 font-semibold">Principal &amp; Institutional Head</p>
                  <p class="text-xs text-slate-400 font-medium mt-0.5">Carmel Polytechnic College</p>
                </div>
              </div>

              <div class="border-t border-slate-100 pt-4 space-y-3 text-sm">
                <div class="flex justify-between items-center text-xs">
                  <span class="text-slate-500">Executive ID:</span>
                  <span id="profileDisplayId" class="font-mono font-bold text-slate-800">9946847236</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                  <span class="text-slate-500">Official Email:</span>
                  <span id="profileDisplayEmail" class="font-semibold text-slate-800">principal@carmelpoly.in</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                  <span class="text-slate-500">Authority Scope:</span>
                  <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded font-semibold text-xs">Full System Administration</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                  <span class="text-slate-500">Portal Version:</span>
                  <span class="font-mono text-slate-600">CampusLynk v2.6.4 (R2026 Ready)</span>
                </div>
              </div>
            </div>

            <div class="lg:col-span-7 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
              <div>
                <h4 class="font-bold text-slate-900 text-base flex items-center gap-2">
                  <span class="material-symbols-rounded text-blue-600 text-base">edit</span>
                  <span>Update Account Credentials</span>
                </h4>
                <p class="text-xs text-slate-500 mt-0.5">Modify administrator details and official notification endpoints.</p>
              </div>

              <form id="profileUpdateForm" onsubmit="submitProfileUpdate(event)" class="space-y-4" enctype="multipart/form-data">
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Full Legal Name</label>
                  <input type="text" id="profileInputName" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Official Email Address</label>
                  <input type="email" id="profileInputEmail" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Profile Picture</label>
                  <input type="file" id="profileInputPhoto" accept="image/*" onchange="previewProfilePhoto(this)" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl">
                  <p class="text-[11px] text-slate-400 mt-1">Upload a JPG, PNG, or WEBP portrait photo (Max 2MB).</p>
                </div>

                <div id="profileUpdateAlert" class="hidden p-3 rounded-xl font-semibold border text-xs"></div>

                <button type="submit" id="profileSaveBtn" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                  <span class="material-symbols-rounded text-base">save</span>
                  <span>Save Profile Updates</span>
                </button>
              </form>

              <div class="border-t border-slate-100 pt-5">
                <h4 class="font-bold text-slate-900 text-base flex items-center gap-2 mb-2">
                  <span class="material-symbols-rounded text-amber-600 text-base">lock_reset</span>
                  <span>Change Master Password</span>
                </h4>
                <p class="text-xs text-slate-500 mb-4">Set a strong confidential passphrase for executive portal access.</p>

                <button type="button" onclick="openPasswordModal('ADMIN-001')" class="px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl text-sm font-semibold flex items-center gap-2 transition cursor-pointer">
                  <span class="material-symbols-rounded text-base">key</span>
                  <span>Update Password Now</span>
                </button>
              </div>
            </div>
          </div>
        </div>
