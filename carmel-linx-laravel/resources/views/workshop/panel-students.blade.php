<div id="panelStudents" class="hidden space-y-6">

        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-[10px] font-black text-slate-200 text-[10px] text-xs">Student Workshop Roster (All Branches)</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">View and manage students assigned to workshop activities across all branches.</p>
          </div>
        </div>

        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search Student</label>
            <input type="text" id="studentSearch" oninput="loadStudents()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="Name, Register No...">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch Filter</label>
            <select id="studentBranch" onchange="loadStudents()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="">All Branches</option>
              <option value="EL">Electronics (EL)</option>
              <option value="ME">Mechanical (ME)</option>
              <option value="CE">Civil (CE)</option>
              <option value="EEE">Electrical (EEE)</option>
              <option value="CT">Computer (CT)</option>
              <option value="AU">Automobile (AU)</option>
            </select>
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="studentStatus" onchange="loadStudents()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
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
                  <th class="p-4">Register No</th>
                  <th class="p-4">Branch</th>
                  <th class="p-4">Status</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="studentTableBody">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
