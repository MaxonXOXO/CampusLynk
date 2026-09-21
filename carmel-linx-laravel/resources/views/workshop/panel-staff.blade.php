<div id="panelStaff" class="hidden space-y-6">
        
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-[10px] font-black text-slate-200">Workshop Staff — Trade Instructors (All Branches)</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Manage approval, suspension, password reset, and deletion of Trade Instructor accounts across all departments.</p>
          </div>
          <button onclick="openRegisterStaffModal()" class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl text-[10px] font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-blue-500/10 text-[10px] text-xs">
            <span class="material-symbols-rounded text-[10px] text-sm">person_add</span> Register Instructor
          </button>
        </div>

        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search Staff</label>
            <input type="text" id="staffSearch" oninput="loadStaff()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="Name or Mobile No...">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="staffStatus" onchange="loadStaff()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-4">Profile</th>
                  <th class="p-4">Mobile ID</th>
                  <th class="p-4">Branch</th>
                  <th class="p-4">Designation</th>
                  <th class="p-4">Status</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="staffTableBody">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
