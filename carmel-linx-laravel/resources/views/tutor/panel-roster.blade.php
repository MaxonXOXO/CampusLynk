<div id="panelRoster" class="{{ $activeTab === 'roster' ? '' : 'hidden' }} space-y-6">
          
          <!-- Directory Header & Filter Card -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
              <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
                  <x-ui.icon name="users" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                  <h3 id="supervisedClassroomTitle" class="font-bold text-slate-900 text-base sm:text-lg">Supervised Classroom Directory</h3>
                  <p class="text-xs text-slate-500 mt-0.5">Manage and review lifecycle states of students in your assigned classroom.</p>
                </div>
              </div>
            </div>

            <!-- Filters Console -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
              <!-- Search input -->
              <div>
                <label class="block text-slate-600 font-semibold mb-1.5 text-xs">Search Student</label>
                <div class="relative">
                  <input type="text" id="filterSearch" oninput="debouncedLoadUsers()" class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-3.5 py-2.5 text-slate-900 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all placeholder:text-slate-400" placeholder="Name, Register No, Mobile...">
                  <div class="absolute left-3.5 top-3 text-slate-400 pointer-events-none">
                    <x-ui.icon name="search" class="w-4 h-4" />
                  </div>
                </div>
              </div>
              
              <!-- Status select -->
              <div>
                <label class="block text-slate-600 font-semibold mb-1.5 text-xs">Account Status</label>
                <select id="filterStatus" onchange="loadUsers()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all cursor-pointer">
                  <option value="">All Statuses</option>
                  <option value="Approved">Approved</option>
                  <option value="Pending">Pending</option>
                  <option value="Suspended">Suspended</option>
                </select>
              </div>

              <!-- Print Report Selector & Button -->
              <div>
                <label class="block text-slate-600 font-semibold mb-1.5 text-xs">Class Register Report</label>
                <div class="flex gap-2">
                  <select id="printSemesterSelect" class="flex-1 bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all cursor-pointer">
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3" selected>S3</option>
                    <option value="S4">S4</option>
                    <option value="S5">S5</option>
                    <option value="S6">S6</option>
                  </select>
                  <button type="button" onclick="printClassRegister()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm flex items-center gap-1.5 transition-all cursor-pointer shadow-xs shrink-0">
                    <x-ui.icon name="print" class="w-4 h-4" />
                    <span>Print</span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Users Table Grid -->
          <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto max-h-[560px] custom-scrollbar">
              <table class="w-full text-left border-collapse text-sm">
                <thead class="sticky top-0 z-10">
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider whitespace-nowrap">
                    <th class="p-3.5 pl-5">Student</th>
                    <th class="p-3.5">Reg No</th>
                    <th class="p-3.5">SBTE Reg No / Edit</th>
                    <th class="p-3.5">Branch</th>
                    <th class="p-3.5">Sem</th>
                    <th class="p-3.5">Role</th>
                    <th class="p-3.5">Account Status</th>
                    <th class="p-3.5">Enrolled Status</th>
                    <th class="p-3.5 pr-5 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="usersTableBody" class="divide-y divide-slate-100">
                  <tr><td colspan="9" class="p-8 text-center text-slate-500 font-medium">Loading classroom students...</td></tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
