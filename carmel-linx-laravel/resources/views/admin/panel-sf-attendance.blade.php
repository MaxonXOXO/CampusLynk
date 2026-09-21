<div id="panelSf_attendance" class="hidden space-y-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-3 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-rounded text-2xl">how_to_reg</span>
              </div>
              <div>
                <h3 class="text-lg font-bold text-slate-900">SF Staff Biometric &amp; GPS Attendance Ledger</h3>
                <p class="text-xs text-slate-500 mt-0.5">Self-Financing faculty face verification punches, campus geofence compliance, and time logs.</p>
              </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
              <input type="date" id="sfAttStartDate" onchange="loadSfAttendance()" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
              <input type="date" id="sfAttEndDate" onchange="loadSfAttendance()" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs" value="{{ now()->format('Y-m-d') }}">
              
              <button onclick="openGeofenceModal()" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all shadow-sm cursor-pointer">
                <span class="material-symbols-rounded text-sm">location_on</span>
                <span>Campus GPS Setup</span>
              </button>

              <button onclick="loadSfAttendance()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200" title="Refresh">
                <span class="material-symbols-rounded text-sm">sync</span>
              </button>
            </div>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-xs">
                    <th class="py-3.5 px-4">Staff Member</th>
                    <th class="py-3.5 px-4">Punch Date</th>
                    <th class="py-3.5 px-4">Morning IN-Time</th>
                    <th class="py-3.5 px-4">Evening OUT-Time</th>
                    <th class="py-3.5 px-4 text-center">Compliance Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="sfAttendanceTableBody" class="divide-y divide-slate-100 text-slate-800">
                  <tr><td colspan="6" class="p-8 text-center text-slate-400">Loading SF attendance logs...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
