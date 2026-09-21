<div id="panelProfile" class="hidden space-y-6">
          <div class="max-w-3xl mx-auto space-y-6">
            
            <!-- Profile Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-center gap-5">
              <div class="relative group shrink-0">
                <div id="studentAvatarWrapper" class="w-20 h-20 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 flex items-center justify-center shadow-sm relative">
                  @if(session('userPhoto'))
                    <img id="studentProfileImg" src="{{ session('userPhoto') }}" class="w-full h-full object-cover">
                  @else
                    <div id="studentProfilePlaceholder" class="w-full h-full bg-slate-900 flex items-center justify-center font-bold text-2xl text-white">
                      {{ strtoupper(substr(session('userName','S'), 0, 2)) }}
                    </div>
                  @endif
                </div>
                <label for="photoUploadInput" class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer rounded-2xl text-white text-xs font-semibold text-center gap-1 p-1">
                  <i data-lucide="camera" class="w-4 h-4"></i>
                  <span>Change</span>
                </label>
                <input type="file" id="photoUploadInput" accept="image/*" class="hidden" onchange="handlePhotoUpload(event)">
              </div>

              <div class="text-center sm:text-left flex-1 min-w-0">
                <h2 class="text-lg font-bold text-slate-900 truncate">{{ session('userName') }}</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ session('userId') }} • {{ session('userBranch') }}</p>
                <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Active Student
                </div>
                <div id="photoUploadStatus" class="text-xs font-semibold mt-2 hidden"></div>
              </div>
            </div>

            <!-- Academic Metadata Grid -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
              <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 pb-3">Institutional Record</h3>
              <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                  <dt class="text-xs font-medium text-slate-500">Registration Number</dt>
                  <dd class="text-sm font-semibold font-mono text-slate-900 mt-0.5">{{ session('userId') }}</dd>
                </div>
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                  <dt class="text-xs font-medium text-slate-500">Department / Branch</dt>
                  <dd class="text-sm font-semibold text-slate-900 mt-0.5">{{ session('userBranch') }}</dd>
                </div>
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                  <dt class="text-xs font-medium text-slate-500">Classroom Identifier</dt>
                  <dd class="text-sm font-semibold text-slate-900 mt-0.5">{{ session('classroomId', '-') }}</dd>
                </div>
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                  <dt class="text-xs font-medium text-slate-500">Curriculum Standard</dt>
                  <dd class="text-sm font-semibold text-blue-700 mt-0.5">Revision 2026 (R2026)</dd>
                </div>
              </dl>
            </div>

            <!-- SBTE Register Number Section -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-3">
              <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <i data-lucide="award" class="w-4 h-4 text-blue-600"></i>
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">SBTE Examination Register Number</h3>
              </div>
              @php $sbteNo = session('sbteRegNo', ''); @endphp
              @if($sbteNo)
                <div class="p-4 bg-emerald-50 border border-emerald-200/60 rounded-xl flex items-center justify-between">
                  <div>
                    <p class="text-xs text-emerald-800 font-medium">Confirmed Number</p>
                    <p class="text-base font-bold font-mono text-emerald-950 mt-0.5">{{ $sbteNo }}</p>
                  </div>
                  <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                </div>
              @else
                <p class="text-xs text-slate-500">Enter your assigned SBTE Diploma Examination Register Number:</p>
                <div class="flex gap-2 pt-1">
                  <input type="text" id="sbteRegNoInput" placeholder="e.g. 25EL001" class="flex-1 min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm font-mono text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none">
                  <button type="button" onclick="updateSbteRegNo()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm">Save</button>
                </div>
                <div id="sbteAlert" class="hidden p-3 rounded-xl text-xs font-semibold border mt-2"></div>
              @endif
            </div>

            <!-- Change Password Section -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
              <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-100 pb-3">Security & Password</h3>
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-slate-700 mb-1">Current Password</label>
                  <input type="password" id="oldPwd" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none" placeholder="Enter current password">
                </div>
                <div>
                  <label class="block text-xs font-medium text-slate-700 mb-1">New Password</label>
                  <input type="password" id="newPwd" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none" placeholder="At least 6 characters">
                </div>
                <div id="pwdAlert" class="hidden p-3 rounded-xl text-xs font-semibold border"></div>
                <button type="button" onclick="changePassword()" class="w-full min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm">Update Password</button>
              </div>
            </div>

          </div>
        </div>
