<div id="panelOverview" class="space-y-6">
        
        <!-- Info Metric Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-blue-500/10 text-blue-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">handyman</span></div>
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Workshop Staff</span>
              <span id="statWorkshopStaff" class="text-base font-black text-white mt-0.5">—</span>
            </div>
          </div>
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-sky-500/10 text-sky-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">group</span></div>
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Total Students</span>
              <span id="statTotalStudents" class="text-base font-black text-white mt-0.5">—</span>
            </div>
          </div>
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-amber-500/10 text-amber-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">pending_actions</span></div>
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Pending Approvals</span>
              <span id="statPending" class="text-base font-black text-white mt-0.5">—</span>
            </div>
          </div>
        </div>

        <!-- Welcome Panel -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
            <h3 class="text-[10px] font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-sm">
              <span class="material-symbols-rounded text-amber-400 text-lg">factory</span> Workshop Superintendent Desk
            </h3>
            <p class="text-[10px] text-slate-400 leading-relaxed text-[10px] text-xs">
              As <strong class="text-slate-200">{{ session('userName') }}</strong>, you oversee all Mechanical Workshop activities across branches. You can manage Trade Instructor accounts, review student workshop rosters, authorize staff to sections, and view cross-branch audit records.
            </p>
            <div class="mt-4 flex gap-3 flex-wrap">
              <button onclick="switchPanel('staff')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-[10px] font-bold text-white transition-premium cursor-pointer">Manage Workshop Staff</button>
              <button onclick="switchPanel('students')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-[10px] font-bold text-slate-300 transition-premium cursor-pointer">View Student Roster</button>
            </div>
          </div>

          <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
            <h3 class="text-[10px] font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-sm">
              <span class="material-symbols-rounded text-blue-400 text-lg">info</span> Role Scope & Upcoming Features
            </h3>
            <ul class="text-[10px] text-slate-400 space-y-2 list-disc pl-4 leading-relaxed text-[10px] text-xs">
              <li>Cross-branch authority over all <strong class="text-slate-300">Trade Instructors</strong> — approve, suspend, reset passwords, or revoke access.</li>
              <li>Workshop Section Management — create and assign sections for each class batch (coming soon).</li>
              <li>Staff-to-Section allocation — authorize which instructor handles which batch section (coming soon).</li>
              <li>Evaluation & Test Reports from each batch per instructor (coming soon).</li>
              <li>View full cross-branch student roster for workshop tracking.</li>
            </ul>
          </div>
        </div>
      </div>
