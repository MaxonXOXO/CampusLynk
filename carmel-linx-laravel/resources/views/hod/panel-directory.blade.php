<div id="panelDirectory" class="{{ $initialPanel === 'directory' ? '' : 'hidden' }} space-y-6">
        
        <!-- Directory Header -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100 uppercase tracking-wider">{{ $activeBranch }} Department</span>
              <span class="text-xs text-slate-400">·</span>
              <span class="text-xs text-slate-500 font-medium">User Directory</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Department Registered Accounts</h2>
            <p class="text-sm text-slate-500 mt-0.5">Filter, search, audit, and manage profile lifecycle states for students and staff in your branch.</p>
          </div>
          <button 
            type="button" 
            onclick="openRegisterModal()" 
            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-xs shrink-0 cursor-pointer"
          >
            <i data-lucide="user-plus" class="w-4 h-4 text-white"></i>
            <span>Register User</span>
          </button>
        </div>

        <!-- Filters Console -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
          <!-- Search input -->
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Search User</label>
            <input 
              type="text" 
              id="filterSearch" 
              class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all placeholder:text-slate-400" 
              placeholder="Name, Register No, Mobile..."
            >
          </div>
          <!-- Role filter -->
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Designation / Role</label>
            <select 
              id="filterRole" 
              class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none transition-all cursor-pointer"
            >
              <option value="">All Roles</option>
              <option value="student">Students Only</option>
              <option value="Lecturer">Lecturers Only</option>
              <option value="Demonstrator">Demonstrators Only</option>
              <option value="Physical_Instructor">Physical Instructors Only</option>
              <option value="Trade_Instructor">Trade Instructors Only</option>
              <option value="Tradesman">Tradesman Only</option>
              <option value="Laboratory_Assistant">Laboratory Assistants Only</option>
              <option value="Workshop_Instructor">Workshop Instructors Only</option>
            </select>
          </div>
          <!-- Status select -->
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Account Status</label>
            <select 
              id="filterStatus" 
              class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none transition-all cursor-pointer"
            >
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
          <!-- Search Button -->
          <div>
            <button 
              type="button" 
              onclick="loadUsers()" 
              class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition-all cursor-pointer flex items-center justify-center gap-2 h-[38px] shadow-xs"
            >
              <i data-lucide="search" class="w-4 h-4 text-white"></i>
              <span>Load Directory</span>
            </button>
          </div>
        </div>

        <!-- Users Table Grid -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
          <div class="max-h-[calc(100vh-320px)] overflow-auto custom-scrollbar">
            <table class="min-w-[1100px] w-full text-left border-collapse text-sm">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-4">Profile</th>
                  <th class="p-4">Mobile / Reg No</th>
                  <th class="p-4">Branch</th>
                  <th class="p-4">Registered Sem</th>
                  <th class="p-4">Role Designation</th>
                  <th class="p-4">Account Status</th>
                  <th class="p-4">Enrollment Status</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <tr><td colspan="8" class="p-12 text-center text-slate-500 font-medium text-sm">Use the filters above and click "Load Directory" to view accounts.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
