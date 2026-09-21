<div id="panelDashboard" class="space-y-6">
        
        <!-- Metrics Grid (Top Row - 5 KPI Cards) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
          <!-- Total Staff -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-3 rounded-xl flex items-center gap-2.5 shadow-sm hover:border-amber-500/50 transition">
            <div class="bg-amber-500/10 text-amber-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded text-lg">badge</span></div>
            <div class="min-w-0">
              <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block truncate">Total Staff</span>
              <span id="statTotalStaff" class="font-black text-white text-lg leading-tight block">0</span>
            </div>
          </div>
          <!-- Total Students -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-3 rounded-xl flex items-center gap-2.5 shadow-sm hover:border-amber-500/50 transition">
            <div class="bg-sky-500/10 text-sky-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded text-lg">school</span></div>
            <div class="min-w-0">
              <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block truncate">Total Students</span>
              <span id="statTotalStudents" class="font-black text-white text-lg leading-tight block">0</span>
            </div>
          </div>
          <!-- Pending Approvals -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-3 rounded-xl flex items-center gap-2.5 shadow-sm hover:border-amber-500/50 transition">
            <div class="bg-blue-500/10 text-blue-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded text-lg">pending_actions</span></div>
            <div class="min-w-0">
              <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block truncate">Pending Approvals</span>
              <span id="statPendingApprovals" class="font-black text-white text-lg leading-tight block">0</span>
            </div>
          </div>
          <!-- Classrooms -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-3 rounded-xl flex items-center gap-2.5 shadow-sm hover:border-amber-500/50 transition">
            <div class="bg-emerald-500/10 text-emerald-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded text-lg">meeting_room</span></div>
            <div class="min-w-0">
              <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block truncate">Classrooms</span>
              <span id="statTotalClassrooms" class="font-black text-white text-lg leading-tight block">0</span>
            </div>
          </div>
          <!-- Academic Pass Rate (Moved to Top Row!) -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-3 rounded-xl flex items-center gap-2.5 shadow-sm hover:border-amber-500/50 transition">
            <div class="bg-indigo-500/10 text-indigo-400 p-2 rounded-lg shrink-0"><span class="material-symbols-rounded text-lg">insights</span></div>
            <div class="min-w-0">
              <span class="text-[10px] text-slate-400 uppercase font-extrabold tracking-wider block truncate">Academic Pass Rate</span>
              <span id="execAcademicPassRate" class="font-black text-indigo-300 text-lg leading-tight block">91.4% Overall</span>
            </div>
          </div>
        </div>

        <!-- EXECUTIVE DAILY OPERATIONAL STATUS ROW (Compact 3 Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
          <!-- Daily Staff Leave Snapshot Card -->
          <div class="bg-slate-900/50 border border-slate-800/80 p-3.5 rounded-xl flex flex-col justify-between shadow-lg shadow-slate-950/30 hover:border-slate-700/70 transition-all duration-200 relative">
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
              <span class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                <span class="p-1 bg-amber-500/10 text-amber-400 rounded-lg flex items-center justify-center shrink-0">
                  <span class="material-symbols-rounded text-xs">event_busy</span>
                </span> Staff On Leave Today
              </span>
              <span id="execStaffLeaveTotal" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500/10 text-amber-400 border border-amber-500/20 shadow-sm">0 Active</span>
            </div>
            
            <!-- All Leave Types in Single Row Grid with Hover Tooltip Popups -->
            <div class="grid grid-cols-6 gap-1 text-xs w-full">
              <!-- CL Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-amber-500/40 transition block text-center truncate shadow-inner">
                  CL: <strong id="execLeaveCL" class="text-amber-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full left-0 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-amber-400">Casual Leave (CL)</span>
                    <span id="popupCountCL" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListCL" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on CL today</span>
                  </div>
                </div>
              </div>

              <!-- CCL Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-amber-500/40 transition block text-center truncate shadow-inner">
                  CCL: <strong id="execLeaveCCL" class="text-amber-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full left-0 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-amber-400">Compensatory CL (CCL)</span>
                    <span id="popupCountCCL" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListCCL" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on CCL today</span>
                  </div>
                </div>
              </div>

              <!-- DL Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-sky-500/40 transition block text-center truncate shadow-inner">
                  DL: <strong id="execLeaveDL" class="text-sky-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-sky-400">Duty Leave (DL)</span>
                    <span id="popupCountDL" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListDL" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on DL today</span>
                  </div>
                </div>
              </div>

              <!-- ML Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-rose-500/40 transition block text-center truncate shadow-inner">
                  ML: <strong id="execLeaveML" class="text-rose-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-rose-400">Medical Leave (ML)</span>
                    <span id="popupCountML" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListML" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on ML today</span>
                  </div>
                </div>
              </div>

              <!-- LOP Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-purple-500/40 transition block text-center truncate shadow-inner">
                  LOP: <strong id="execLeaveLOP" class="text-purple-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full right-0 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-purple-400">Loss of Pay (LOP)</span>
                    <span id="popupCountLOP" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListLOP" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on LOP today</span>
                  </div>
                </div>
              </div>

              <!-- OTHERS Badge -->
              <div class="group relative">
                <span class="px-1 py-0.5 bg-slate-950/90 border border-slate-800/90 rounded-md text-slate-300 text-[10px] font-medium cursor-pointer hover:bg-slate-800 hover:border-emerald-500/40 transition block text-center truncate shadow-inner">
                  Oth: <strong id="execLeaveOTHERS" class="text-emerald-400">0</strong>
                </span>
                <div class="pointer-events-none absolute bottom-full right-0 mb-1.5 hidden group-hover:block w-48 bg-slate-900 border border-slate-700/80 rounded-xl shadow-2xl p-2.5 z-50 text-[11px] text-left">
                  <div class="font-bold text-slate-200 border-b border-slate-800 pb-1 mb-1 flex items-center justify-between">
                    <span class="text-emerald-400">Other Leaves</span>
                    <span id="popupCountOTHERS" class="text-[9px] text-slate-400 font-mono">0 Staff</span>
                  </div>
                  <div id="popupListOTHERS" class="space-y-1 max-h-36 overflow-y-auto text-slate-300">
                    <span class="text-slate-500 italic block text-[10px]">No staff on other leaves today</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Daily Student Attendance Rate Card -->
          <div class="bg-slate-900/50 border border-slate-800/80 p-3.5 rounded-xl flex flex-col justify-between shadow-lg shadow-slate-950/30 hover:border-slate-700/70 transition-all duration-200">
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
              <span class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                <span class="p-1 bg-sky-500/10 text-sky-400 rounded-lg flex items-center justify-center shrink-0">
                  <span class="material-symbols-rounded text-xs">how_to_reg</span>
                </span> Student Attendance
              </span>
              <span id="execStudentAttPct" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-sm">94.8% Active</span>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-300">
              <span class="text-slate-400 text-[11px]">Real-Time Campus Ratio</span>
              <span class="font-bold text-slate-200 text-[11px]">Institution Average</span>
            </div>
          </div>

          <!-- Today's Campus & Academic Events Card -->
          <div onclick="openTodayEventsModal()" class="bg-slate-900/50 border border-slate-800/80 p-3.5 rounded-xl flex flex-col justify-between shadow-lg shadow-slate-950/30 hover:border-sky-500/40 hover:bg-slate-900/70 transition-all duration-200 cursor-pointer group">
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
              <span class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                <span class="p-1 bg-sky-500/10 text-sky-400 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-sky-500/20">
                  <span class="material-symbols-rounded text-xs">calendar_month</span>
                </span> Today's Events
              </span>
              <span id="execEventsCountBadge" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-sm group-hover:border-sky-400">Scheduled</span>
            </div>
            <div id="execTodayEventsList" class="space-y-1 text-xs text-slate-300 overflow-hidden">
              <div class="flex items-center gap-1.5 truncate">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 shrink-0"></span>
                <span class="truncate font-medium text-[11px]">SITTTR Academic Schedule</span>
              </div>
              <div class="flex items-center gap-1.5 truncate">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                <span class="truncate text-slate-400 text-[11px]">Department CIA Audits</span>
              </div>
            </div>
            <div class="mt-2 pt-1 border-t border-slate-800/40 flex items-center justify-between text-[10px] text-sky-400 font-bold group-hover:text-sky-300">
              <span>View events by categories</span>
              <span class="material-symbols-rounded text-xs group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
            </div>
          </div>
        </div>

        <!-- Executive Dashboard Actions & Broadcast Desks (3 Cards Row) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <!-- Department HOD Dashboard Overrides Card -->
          <div class="bg-slate-900/50 border border-slate-800/80 p-4 rounded-2xl flex flex-col justify-between shadow-xl shadow-slate-950/40 hover:border-slate-700/70 transition-all duration-300">
            <div>
              <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
                <h3 class="font-black text-slate-200 flex items-center gap-2 text-sm">
                  <span class="p-1 bg-amber-500/10 text-amber-400 rounded-lg flex items-center justify-center shrink-0">
                    <span class="material-symbols-rounded text-sm">domain</span>
                  </span> HOD Console Supervision
                </h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 shadow-sm">Direct Supervision</span>
              </div>
              <p class="text-[11px] text-slate-400 leading-tight mb-3">
                Directly inspect and supervise the HOD Control Desk for any department branch.
              </p>
              <!-- 8 Compact Branch Buttons Grid -->
              <div class="grid grid-cols-4 gap-2">
                <a href="/dashboard/principal/department/EL" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-amber-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-amber-400 group-hover:scale-110 transition-premium">settings_input_component</span>
                  <span class="font-extrabold text-xs text-slate-200">EL</span>
                </a>
                <a href="/dashboard/principal/department/ME" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-emerald-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-emerald-400 group-hover:scale-110 transition-premium">precision_manufacturing</span>
                  <span class="font-extrabold text-xs text-slate-200">ME</span>
                </a>
                <a href="/dashboard/principal/department/CE" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-pink-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-pink-400 group-hover:scale-110 transition-premium">domain</span>
                  <span class="font-extrabold text-xs text-slate-200">CE</span>
                </a>
                <a href="/dashboard/principal/department/EEE" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-rose-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-rose-400 group-hover:scale-110 transition-premium">bolt</span>
                  <span class="font-extrabold text-xs text-slate-200">EEE</span>
                </a>
                <a href="/dashboard/principal/department/CT" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-purple-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-purple-400 group-hover:scale-110 transition-premium">computer</span>
                  <span class="font-extrabold text-xs text-slate-200">CT</span>
                </a>
                <a href="/dashboard/principal/department/AU" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-indigo-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-indigo-400 group-hover:scale-110 transition-premium">directions_car</span>
                  <span class="font-extrabold text-xs text-slate-200">AU</span>
                </a>
                <a href="/dashboard/principal/department/GEN_AIDED" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-teal-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-teal-400 group-hover:scale-110 transition-premium">calculate</span>
                  <span class="font-extrabold text-xs text-slate-200">Gen-A</span>
                </a>
                <a href="/dashboard/principal/department/GEN_SF" class="no-underline p-2 bg-slate-950/90 border border-slate-800/90 hover:border-cyan-500/60 hover:bg-slate-900 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-inner">
                  <span class="material-symbols-rounded text-lg text-cyan-400 group-hover:scale-110 transition-premium">functions</span>
                  <span class="font-extrabold text-xs text-slate-200">Gen-SF</span>
                </a>
              </div>
            </div>
          </div>

          <!-- Institutional Flash Notice Broadcast Desk Card -->
          <div class="bg-slate-900/50 border border-slate-800/80 p-4 rounded-2xl flex flex-col justify-between shadow-xl shadow-slate-950/40 hover:border-slate-700/70 transition-all duration-300">
            <div>
              <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
                <h3 class="font-black text-slate-200 flex items-center gap-2 text-sm">
                  <span class="p-1 bg-sky-500/10 text-sky-400 rounded-lg flex items-center justify-center shrink-0">
                    <span class="material-symbols-rounded text-sm">campaign</span>
                  </span> Flash Notice Broadcast Desk
                </h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20 shadow-sm">Executive Broadcast</span>
              </div>
              <p class="text-[11px] text-slate-400 leading-tight mb-3">
                Instantly broadcast official notices or circulars with attachments to staff and students immediately or on schedule.
              </p>
              <div class="grid grid-cols-3 gap-2 mb-3 text-center">
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="flashNoticeStatSent" class="block font-black text-slate-200 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Broadcasted</span>
                </div>
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="flashNoticeStatSched" class="block font-black text-amber-400 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Scheduled</span>
                </div>
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="flashNoticeStatUrgent" class="block font-black text-rose-400 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Urgent</span>
                </div>
              </div>
            </div>
            <div class="flex flex-wrap gap-2 pt-1">
              <button onclick="openFlashNoticeModal()" class="flex-1 px-3 py-1.5 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 rounded-lg font-bold text-slate-950 transition-premium cursor-pointer text-xs flex items-center justify-center gap-1 shadow-md">
                <span class="material-symbols-rounded text-sm">send</span> Broadcast Notice
              </button>
              <button onclick="openFlashNoticeHistoryModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg font-bold text-slate-300 transition-premium cursor-pointer text-xs flex items-center justify-center gap-1 border border-slate-700">
                <span class="material-symbols-rounded text-sm">history</span> Log
              </button>
            </div>
          </div>

          <!-- College Targeted Event Scheduler Desk Card (NEW MODULE) -->
          <div class="bg-slate-900/50 border border-slate-800/80 p-4 rounded-2xl flex flex-col justify-between shadow-xl shadow-slate-950/40 hover:border-slate-700/70 transition-all duration-300">
            <div>
              <div class="flex items-center justify-between border-b border-slate-800/80 pb-2 mb-2">
                <h3 class="font-black text-slate-200 flex items-center gap-2 text-sm">
                  <span class="p-1 bg-emerald-500/10 text-emerald-400 rounded-lg flex items-center justify-center shrink-0">
                    <x-ui.icon name="event_available" class="w-4 h-4" />
                  </span> College Event Scheduler
                </h3>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-sm">Targeted Dispatch</span>
              </div>
              <p class="text-[11px] text-slate-400 leading-tight mb-3">
                Schedule institutional events for College, Depts, Staff, Students, or Special Groups (Placement, NSS/NCC, Sports).
              </p>
              <div class="grid grid-cols-3 gap-2 mb-3 text-center">
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="principalEventStatCollege" class="block font-black text-emerald-400 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Campus Wide</span>
                </div>
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="principalEventStatDept" class="block font-black text-sky-400 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Dept/Staff</span>
                </div>
                <div class="p-1.5 bg-slate-950/90 rounded-xl border border-slate-800/90 shadow-inner">
                  <span id="principalEventStatSpecial" class="block font-black text-purple-400 text-sm">0</span>
                  <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Special Groups</span>
                </div>
              </div>
            </div>
            <div class="flex flex-wrap gap-2 pt-1">
              <button onclick="openPrincipalScheduleEventModal()" class="flex-1 px-3 py-1.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 rounded-lg font-bold text-white transition-premium cursor-pointer text-xs flex items-center justify-center gap-1 shadow-md">
                <span class="material-symbols-rounded text-sm">edit_calendar</span> Schedule Event
              </button>
              <button onclick="openPrincipalScheduleEventHistoryModal()" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg font-bold text-slate-300 transition-premium cursor-pointer text-xs flex items-center justify-center gap-1 border border-slate-700">
                <span class="material-symbols-rounded text-sm">view_list</span> Event Log
              </button>
            </div>
          </div>
        </div>

        <!-- EXECUTIVE OPTION 2: COMPACT 3-SEMESTER ACADEMIC PASS MATRIX -->
        <details class="group bg-slate-900/50 border border-slate-800/80 rounded-2xl shadow-xl shadow-slate-950/40 hover:border-slate-700/70 transition-all duration-300">
          <summary class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 sm:p-5 cursor-pointer select-none list-none [&::-webkit-details-marker]:hidden gap-3">
            <div class="flex items-center gap-3">
              <span class="p-1.5 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-symbols-rounded text-sm">analytics</span>
              </span>
              <div>
                <div class="flex items-center gap-2 flex-wrap">
                  <h3 class="font-black text-slate-200 text-sm">Previous Semester Branch Academic Pass Matrix</h3>
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-300 border border-amber-500/20">3 Semesters per Dept</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5">Department semester pass percentages (S1/S3/S5 or S2/S4/S6) uploaded by HODs.</p>
              </div>
            </div>
            <div class="flex items-center gap-2.5 self-end sm:self-auto shrink-0">
              <div class="flex items-center gap-1.5 px-3 py-1 bg-slate-800/80 hover:bg-slate-700/80 rounded-lg border border-slate-700/80 text-slate-300 text-xs font-bold transition">
                <span class="group-open:hidden">Expand Matrix</span>
                <span class="hidden group-open:inline">Fold Matrix</span>
                <span class="material-symbols-rounded text-base transition-transform duration-200 group-open:rotate-180">expand_more</span>
              </div>
            </div>
          </summary>

          <div class="p-4 sm:p-5 pt-0 space-y-3 border-t border-slate-800/40">
            <!-- Ultra-Compact High-Density Table -->
            <div class="overflow-x-auto pt-3">
              <table class="w-full text-left border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-900/80 text-slate-400 font-bold uppercase tracking-wider text-[10px] border-b border-slate-800">
                    <th class="py-2 px-3">Branch Code &amp; Name</th>
                    <th class="py-2 px-3 text-center">Sem 1 / 2</th>
                    <th class="py-2 px-3 text-center">Sem 3 / 4</th>
                    <th class="py-2 px-3 text-center">Sem 5 / 6</th>
                    <th class="py-2 px-3 text-center">Dept Avg</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 font-medium">
                  <!-- EL -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-amber-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-amber-500/10 border border-amber-500/30 rounded text-[10px] font-mono">EL</span>
                      <span class="text-slate-200 text-xs">Electronics Engg</span>
                    </td>
                    <td id="sem_EL_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">91.6%</td>
                    <td id="sem_EL_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">89.5%</td>
                    <td id="sem_EL_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">92.7%</td>
                    <td id="sem_EL_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">91.3%</td>
                  </tr>

                  <!-- ME -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-emerald-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-emerald-500/10 border border-emerald-500/30 rounded text-[10px] font-mono">ME</span>
                      <span class="text-slate-200 text-xs">Mechanical Engg</span>
                    </td>
                    <td id="sem_ME_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">87.1%</td>
                    <td id="sem_ME_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">88.3%</td>
                    <td id="sem_ME_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">87.9%</td>
                    <td id="sem_ME_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">87.8%</td>
                  </tr>

                  <!-- CE -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-pink-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-pink-500/10 border border-pink-500/30 rounded text-[10px] font-mono">CE</span>
                      <span class="text-slate-200 text-xs">Civil Engg</span>
                    </td>
                    <td id="sem_CE_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">89.6%</td>
                    <td id="sem_CE_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">91.0%</td>
                    <td id="sem_CE_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">89.1%</td>
                    <td id="sem_CE_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">89.9%</td>
                  </tr>

                  <!-- EEE -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-rose-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-rose-500/10 border border-rose-500/30 rounded text-[10px] font-mono">EEE</span>
                      <span class="text-slate-200 text-xs">Electrical Engg</span>
                    </td>
                    <td id="sem_EEE_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">90.9%</td>
                    <td id="sem_EEE_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">90.7%</td>
                    <td id="sem_EEE_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">92.3%</td>
                    <td id="sem_EEE_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">91.3%</td>
                  </tr>

                  <!-- CT -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-purple-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-purple-500/10 border border-purple-500/30 rounded text-[10px] font-mono">CT</span>
                      <span class="text-slate-200 text-xs">Computer Engg</span>
                    </td>
                    <td id="sem_CT_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">93.7%</td>
                    <td id="sem_CT_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">95.1%</td>
                    <td id="sem_CT_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">95.0%</td>
                    <td id="sem_CT_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">94.6%</td>
                  </tr>

                  <!-- AU -->
                  <tr class="hover:bg-slate-900/40 transition-colors">
                    <td class="py-2 px-3 font-bold text-indigo-400 flex items-center gap-2">
                      <span class="px-1.5 py-0.5 bg-indigo-500/10 border border-indigo-500/30 rounded text-[10px] font-mono">AU</span>
                      <span class="text-slate-200 text-xs">Automobile Engg</span>
                    </td>
                    <td id="sem_AU_S1" class="py-2 px-3 text-center font-mono font-bold text-slate-300">88.0%</td>
                    <td id="sem_AU_S3" class="py-2 px-3 text-center font-mono font-bold text-slate-300">87.5%</td>
                    <td id="sem_AU_S5" class="py-2 px-3 text-center font-mono font-bold text-slate-300">89.1%</td>
                    <td id="sem_AU_avg" class="py-2 px-3 text-center font-mono font-black text-emerald-400 bg-emerald-500/5">88.2%</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Secondary Compliance Indicators Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 border-t border-slate-800/60">
              <div class="p-2.5 bg-slate-900/60 border border-slate-800/80 rounded-xl flex items-center gap-2.5">
                <span class="material-symbols-rounded text-indigo-400 text-lg">workspace_premium</span>
                <span class="text-xs text-slate-400">Faculty FDPs &amp; Workshops: <strong id="execFdpCount" class="text-white font-bold">12 Verified</strong></span>
              </div>
              <div class="p-2.5 bg-slate-900/60 border border-slate-800/80 rounded-xl flex items-center gap-2.5">
                <span class="material-symbols-rounded text-emerald-400 text-lg">assignment_turned_in</span>
                <span class="text-xs text-slate-400">NBA Attainment Average: <strong id="execCoPoAvg" class="text-white font-bold">88.5% CO-PO</strong></span>
              </div>
            </div>
          </div>
        </details>
      </div>
