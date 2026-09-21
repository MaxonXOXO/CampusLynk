        <!-- PANEL 2: SF STAFF DIRECTORY -->
        <div id="panelDirectory" class="hidden space-y-6">
          <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-3 gap-4">
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search Staff</label>
              <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs" placeholder="Search name or mobile...">
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Department Filter</label>
              <select id="filterBranch" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs">
                <option value="">All SF Departments (EL, AU, CT, GEN SF)</option>
                <option value="EL">Electronics (EL)</option>
                <option value="AU">Automobile (AU)</option>
                <option value="CT">Computer Engineering (CT)</option>
                <option value="GEN_SF">General SF (GEN_SF)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Role Designation</label>
              <select id="filterRole" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs">
                <option value="">All Roles</option>
                <option value="HOD">HOD</option>
                <option value="Lecturer">Lecturer</option>
                <option value="Demonstrator">Demonstrator</option>
                <option value="Trade_Instructor">Trade Instructor</option>
              </select>
            </div>
          </div>

          <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                  <th class="p-4">Profile</th>
                  <th class="p-4">Mobile ID</th>
                  <th class="p-4">Branch</th>
                  <th class="p-4">Designation</th>
                  <th class="p-4 text-right">Account Status</th>
                </tr>
              </thead>
              <tbody id="usersTableBody" class="divide-y divide-slate-800/40">
              </tbody>
            </table>
          </div>
        </div>

