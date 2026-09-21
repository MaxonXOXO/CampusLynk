<div id="panelDirectory" class="hidden space-y-6">
        
        <!-- Directory Header -->
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-base font-bold text-slate-200">Personnel Accounts Registry</h3>
            <p class="text-xs text-slate-400 mt-0.5">View personnel profiles, designations, and account status across all departments.</p>
          </div>
        </div>

        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Search input -->
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search User</label>
            <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-amber-500 outline-none" placeholder="Name, Register No, Mobile...">
          </div>
          <!-- Branch select -->
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch Code</label>
            <select id="filterBranch" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-amber-500 outline-none">
              <option value="">All Branches</option>
              <option value="EL">Electronics Engineering (EL)</option>
              <option value="ME">Mechanical Engineering (ME)</option>
              <option value="CE">Civil Engineering (CE)</option>
              <option value="EEE">Electrical Engineering (EEE)</option>
              <option value="CT">Computer Engineering (CT)</option>
              <option value="AU">Automobile Engineering (AU)</option>
              <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
              <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
              <option value="Administration">Administration</option>
            </select>
          </div>
          <!-- Role filter -->
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Designation / Role</label>
            <select id="filterRole" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-amber-500 outline-none">
              <option value="">All Roles</option>
              <option value="student">Students Only</option>
              <option value="Super_Admin">Super Admin</option>
              <option value="Chairman">Chairman</option>
              <option value="Admin">Admin</option>
              <option value="Principal">Principal</option>
              <option value="HOD">Head of Department (HOD)</option>
              <option value="Academic_Coordinator">Academic Coordinator (Self-Financing)</option>
              <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
              <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
              <option value="Lecturer">Lecturers</option>
              <option value="Demonstrator">Demonstrators</option>
              <option value="Trade_Instructor">Trade Instructors</option>
            </select>
          </div>
          <!-- Status select -->
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="filterStatus" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-amber-500 outline-none">
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
        </div>

        <!-- Users Table Grid -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="max-h-[calc(100vh-320px)] overflow-y-auto overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-xs md:text-sm">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-2.5 md:p-3">Profile</th>
                  <th class="p-2.5 md:p-3">Mobile / Reg No</th>
                  <th class="p-2.5 md:p-3">Branch</th>
                  <th class="p-2.5 md:p-3">Role Designation</th>
                  <th class="p-2.5 md:p-3">Account Status</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
