      <!-- PANEL 2: DIRECTORY -->
      <div id="panelDirectory" class="hidden space-y-6">
        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search User</label>
            <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="Search staff name...">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Role Designation</label>
            <select id="filterRole" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="">All Roles</option>
              <option value="Lecturer">Lecturer</option>
              <option value="Demonstrator">Demonstrator</option>
              <option value="Physical_Instructor">Physical Instructor</option>
              <option value="Trade_Instructor">Trade Instructor</option>
            </select>
          </div>
        </div>

        <!-- Users Table -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
            <thead>
              <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                <th class="p-4">Profile</th>
                <th class="p-4">Mobile</th>
                <th class="p-4">Branch</th>
                <th class="p-4">Role Designation</th>
                <th class="p-4 text-right">Account Status</th>
              </tr>
            </thead>
            <tbody id="usersTableBody">
              <!-- Dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </main>
