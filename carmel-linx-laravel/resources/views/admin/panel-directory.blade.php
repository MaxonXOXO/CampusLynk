<div id="panelDirectory" class="hidden space-y-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-3 shadow-sm">
            <div>
              <h3 class="text-lg font-bold text-slate-900">Registered Institutional Accounts</h3>
              <p class="text-xs text-slate-500 mt-0.5">Filter, search, audit, and manage profile lifecycle states across all departments.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
              @if(in_array(strtolower(session('userRole', '')), ['super_admin', 'superadmin']))
              <a href="/superadmin/show-users" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-semibold transition-all flex items-center gap-2 shadow-sm text-sm no-underline">
                <span class="material-symbols-rounded text-lg">key</span>
                <span>User Credentials</span>
              </a>
              @endif
              @if(in_array(strtolower(session('userRole', '')), ['super_admin', 'superadmin', 'admin', 'principal']))
              <button onclick="openRegisterModal()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all cursor-pointer flex items-center gap-2 shadow-sm text-sm">
                <span class="material-symbols-rounded text-lg">person_add</span>
                <span>Register User</span>
              </button>
              @endif
            </div>
          </div>

          <!-- Filters Console -->
          <div class="bg-white border border-slate-200 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 shadow-sm">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Search User</label>
              <input type="text" id="filterSearch" oninput="onUserSearchInput()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" placeholder="Name, Register No, Mobile...">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Branch Code</label>
              <select id="filterBranch" onchange="loadUsers()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                <option value="">All Branches</option>
                <option value="EL">Electronics Engineering (EL)</option>
                <option value="ME">Mechanical Engineering (ME)</option>
                <option value="CE">Civil Engineering (CE)</option>
                <option value="EEE">Electrical Engineering (EEE)</option>
                <option value="CT">Computer Engineering (CT)</option>
                <option value="AU">Automobile Engineering (AU)</option>
                <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
                <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
                <option value="GEN">General Science (GEN)</option>
                <option value="Administration">Administration</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Designation / Role</label>
              <select id="filterRole" onchange="loadUsers()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
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
                <option value="Physical_Instructor">Physical Instructors</option>
                <option value="Trade_Instructor">Trade Instructors</option>
                <option value="Tradesman">Tradesmen</option>
                <option value="Laboratory_Assistant">Laboratory Assistants</option>
                <option value="Workshop_Instructor">Workshop Instructors</option>
                <option value="Workshop_Superintendent">Workshop Superintendent</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Account Status</label>
              <select id="filterStatus" onchange="loadUsers()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                <option value="">All Statuses</option>
                <option value="Pending">Pending Approval</option>
                <option value="Approved">Approved / Active</option>
                <option value="Suspended">Suspended</option>
              </select>
            </div>
          </div>

          <!-- Accounts Table -->
          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-xs">
                    <th class="py-3.5 px-4">Profile</th>
                    <th class="py-3.5 px-4">Mobile / Reg No</th>
                    <th class="py-3.5 px-4">Branch</th>
                    <th class="py-3.5 px-4">Role Designation</th>
                    <th class="py-3.5 px-4 text-center">Account Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-slate-100 text-slate-800 font-medium">
                  <tr><td colspan="6" class="p-8 text-center text-slate-400">Loading directory accounts...</td></tr>
                </tbody>
              </table>
            </div>
            
            <!-- Pagination Controls Bar -->
            <div id="userPaginationBar" class="border-t border-slate-200 px-5 py-3.5 bg-slate-50/70 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-slate-600">
              <div class="flex items-center gap-2">
                <span id="userPaginationInfo" class="font-medium text-slate-700">Showing 0 of 0 accounts</span>
                <span class="text-slate-300">|</span>
                <div class="flex items-center gap-1.5">
                  <label class="text-xs font-semibold text-slate-500 uppercase">Per page:</label>
                  <select id="userPageSize" onchange="changeUserPageSize(this.value)" class="bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 cursor-pointer">
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">All</option>
                  </select>
                </div>
              </div>
              <div id="userPaginationNav" class="flex items-center gap-1">
                <!-- Dynamically generated pagination buttons -->
              </div>
            </div>
          </div>
        </div>
