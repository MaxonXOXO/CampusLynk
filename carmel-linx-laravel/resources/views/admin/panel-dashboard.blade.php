<div id="panelDashboard" class="space-y-6">
          
          <!-- Metrics Grid (Top Row - 5 KPI Cards) -->
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
            
            <!-- Total Staff -->
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-slate-300 transition-all">
              <div class="bg-blue-50 text-blue-600 p-2.5 rounded-xl shrink-0">
                <x-ui.icon name="badge" class="w-5 h-5 text-blue-600" />
              </div>
              <div class="min-w-0">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider block truncate">Total Staff</span>
                <span id="statTotalStaff" class="font-bold text-slate-900 text-xl leading-tight block">0</span>
              </div>
            </div>

            <!-- Total Students -->
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-slate-300 transition-all">
              <div class="bg-sky-50 text-sky-600 p-2.5 rounded-xl shrink-0">
                <x-ui.icon name="school" class="w-5 h-5 text-sky-600" />
              </div>
              <div class="min-w-0">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider block truncate">Total Students</span>
                <span id="statTotalStudents" class="font-bold text-slate-900 text-xl leading-tight block">0</span>
              </div>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-slate-300 transition-all">
              <div class="bg-amber-50 text-amber-600 p-2.5 rounded-xl shrink-0">
                <x-ui.icon name="pending_actions" class="w-5 h-5 text-amber-600" />
              </div>
              <div class="min-w-0">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider block truncate">Pending Approvals</span>
                <span id="statPendingApprovals" class="font-bold text-slate-900 text-xl leading-tight block">0</span>
              </div>
            </div>

            <!-- Classrooms -->
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-slate-300 transition-all">
              <div class="bg-emerald-50 text-emerald-600 p-2.5 rounded-xl shrink-0">
                <x-ui.icon name="meeting_room" class="w-5 h-5 text-emerald-600" />
              </div>
              <div class="min-w-0">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider block truncate">Classrooms</span>
                <span id="statTotalClassrooms" class="font-bold text-slate-900 text-xl leading-tight block">0</span>
              </div>
            </div>

            <!-- Academic Pass Rate -->
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-slate-300 transition-all col-span-2 sm:col-span-1">
              <div class="bg-indigo-50 text-indigo-600 p-2.5 rounded-xl shrink-0">
                <x-ui.icon name="insights" class="w-5 h-5 text-indigo-600" />
              </div>
              <div class="min-w-0">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider block truncate">Academic Pass Rate</span>
                <span id="execAcademicPassRate" class="font-bold text-indigo-700 text-xl leading-tight block">91.4% Overall</span>
              </div>
            </div>

          </div>

          <!-- EXECUTIVE DAILY OPERATIONAL STATUS ROW (Compact 3 Cards) -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            
            <!-- Daily Staff Leave Snapshot Card -->
            <div class="bg-white border border-slate-200 p-4 rounded-2xl flex flex-col justify-between shadow-sm hover:border-slate-300 transition-all duration-200 relative">
              <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 mb-2.5">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                  <span class="p-1 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center shrink-0">
                    <x-ui.icon name="event_busy" class="w-4 h-4 text-amber-600" />
                  </span> Staff On Leave Today
                </span>
                <span id="execStaffLeaveTotal" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs font-mono">0 Active</span>
              </div>
              
              <!-- All Leave Types in Single Row Grid with Hover Tooltip Popups -->
              <div class="grid grid-cols-6 gap-1.5 text-xs w-full">
                
                <!-- CL Badge -->
                <div class="group relative">
                  <span class="px-1.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 text-xs font-semibold cursor-pointer hover:bg-slate-100 hover:border-amber-400 transition block text-center truncate shadow-2xs">
                    CL: <strong id="execLeaveCL" class="text-amber-600">0</strong>
                  </span>
                  <div class="pointer-events-none absolute bottom-full left-0 mb-2 hidden group-hover:block w-52 bg-white border border-slate-200 rounded-2xl shadow-xl p-3 z-50 text-xs text-left">
                    <div class="font-bold text-slate-900 border-b border-slate-100 pb-1.5 mb-1.5 flex items-center justify-between">
                      <span class="text-amber-600 font-bold">Casual Leave (CL)</span>
                      <span id="popupCountCL" class="text-[11px] text-slate-500 font-mono">0 Staff</span>
                    </div>
                    <div id="popupListCL" class="space-y-1.5 max-h-36 overflow-y-auto custom-scrollbar text-slate-700">
                      <span class="text-slate-400 italic block text-xs">No staff on CL today</span>
                    </div>
                  </div>
                </div>

                <!-- CCL Badge -->
                <div class="group relative">
                  <span class="px-1.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 text-xs font-semibold cursor-pointer hover:bg-slate-100 hover:border-amber-400 transition block text-center truncate shadow-2xs">
                    CCL: <strong id="execLeaveCCL" class="text-amber-600">0</strong>
                  </span>
                  <div class="pointer-events-none absolute bottom-full left-0 mb-2 hidden group-hover:block w-52 bg-white border border-slate-200 rounded-2xl shadow-xl p-3 z-50 text-xs text-left">
                    <div class="font-bold text-slate-900 border-b border-slate-100 pb-1.5 mb-1.5 flex items-center justify-between">
                      <span class="text-amber-600 font-bold">Compensatory CL (CCL)</span>
                      <span id="popupCountCCL" class="text-[11px] text-slate-500 font-mono">0 Staff</span>
                    </div>
                    <div id="popupListCCL" class="space-y-1.5 max-h-36 overflow-y-auto custom-scrollbar text-slate-700">
                      <span class="text-slate-400 italic block text-xs">No staff on CCL today</span>
                    </div>
                  </div>
                </div>

                <!-- DL Badge -->
                <div class="group relative">
                  <span class="px-1.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 text-xs font-semibold cursor-pointer hover:bg-slate-100 hover:border-sky-400 transition block text-center truncate shadow-2xs">
                    DL: <strong id="execLeaveDL" class="text-sky-600">0</strong>
                  </span>
                  <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-52 bg-white border border-slate-200 rounded-2xl shadow-xl p-3 z-50 text-xs text-left">
                    <div class="font-bold text-slate-900 border-b border-slate-100 pb-1.5 mb-1.5 flex items-center justify-between">
                      <span class="text-sky-600 font-bold">Duty Leave (DL)</span>
                      <span id="popupCountDL" class="text-[11px] text-slate-500 font-mono">0 Staff</span>
                    </div>
                    <div id="popupListDL" class="space-y-1.5 max-h-36 overflow-y-auto custom-scrollbar text-slate-700">
                      <span class="text-slate-400 italic block text-xs">No staff on DL today</span>
                    </div>
                  </div>
                </div>

                <!-- ML Badge -->
                <div class="group relative">
                  <span class="px-1.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 text-xs font-semibold cursor-pointer hover:bg-slate-100 hover:border-rose-400 transition block text-center truncate shadow-2xs">
                    ML: <strong id="execLeaveML" class="text-rose-600">0</strong>
                  </span>
                  <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-52 bg-white border border-slate-200 rounded-2xl shadow-xl p-3 z-50 text-xs text-left">
                    <div class="font-bold text-slate-900 border-b border-slate-100 pb-1.5 mb-1.5 flex items-center justify-between">
                      <span class="text-rose-600 font-bold">Medical Leave (ML)</span>
                      <span id="popupCountML" class="text-[11px] text-slate-500 font-mono">0 Staff</span>
                    </div>
                    <div id="popupListML" class="space-y-1.5 max-h-36 overflow-y-auto custom-scrollbar text-slate-700">
                      <span class="text-slate-400 italic block text-xs">No staff on ML today</span>
                    </div>
                  </div>
                </div>

                <!-- LOP Badge -->
                <div class="group relative">
                  <span class="px-1.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 text-xs font-semibold cursor-pointer hover:bg-slate-100 hover:border-purple-400 transition block text-center truncate shadow-2xs">
                    LOP: <strong id="execLeaveLOP" class="text-purple-600">0</strong>
                  </span>
                  <div class="pointer-events-none absolute bottom-full right-0 mb-2 hidden group-hover:block w-52 bg-white border border-slate-200 rounded-2xl shadow-xl p-3 z-50 text-xs text-left">
                    <div class="font-bold text-slate-900 border-b border-slate-100 pb-1.5 mb-1.5 flex items-center justify-between">
                      <span class="text-purple-600 font-bold">Loss of Pay (LOP)</span>
                      <span id="popupCountLOP" class="text-[11px] text-slate-500 font-mono">0 Staff</span>
                    </div>
                    <div id="popupListLOP" class="space-y-1.5 max-h-36 overflow-y-auto custom-scrollbar text-slate-700">
                      <span class="text-slate-400 italic block text-xs">No staff on LOP today</span>
                    </div>
                  </div>
                </div>

                <!-- OTHERS Badge -->
                <div class="group relative">
                  <span class="px-1.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 text-xs font-semibold cursor-pointer hover:bg-slate-100 hover:border-emerald-400 transition block text-center truncate shadow-2xs">
                    Oth: <strong id="execLeaveOTHERS" class="text-emerald-600">0</strong>
                  </span>
                  <div class="pointer-events-none absolute bottom-full right-0 mb-2 hidden group-hover:block w-52 bg-white border border-slate-200 rounded-2xl shadow-xl p-3 z-50 text-xs text-left">
                    <div class="font-bold text-slate-900 border-b border-slate-100 pb-1.5 mb-1.5 flex items-center justify-between">
                      <span class="text-emerald-600 font-bold">Other Leaves</span>
                      <span id="popupCountOTHERS" class="text-[11px] text-slate-500 font-mono">0 Staff</span>
                    </div>
                    <div id="popupListOTHERS" class="space-y-1.5 max-h-36 overflow-y-auto custom-scrollbar text-slate-700">
                      <span class="text-slate-400 italic block text-xs">No staff on other leaves today</span>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- Daily Student Attendance Rate Card -->
            <div class="bg-white border border-slate-200 p-4 rounded-2xl flex flex-col justify-between shadow-sm hover:border-slate-300 transition-all duration-200">
              <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 mb-2.5">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                  <span class="p-1 bg-sky-50 text-sky-600 rounded-lg flex items-center justify-center shrink-0">
                    <x-ui.icon name="how_to_reg" class="w-4 h-4 text-sky-600" />
                  </span> Student Attendance
                </span>
                <span id="execStudentAttPct" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200 shadow-2xs font-mono">94.8% Active</span>
              </div>
              <div class="flex items-center justify-between text-xs text-slate-600 pt-1">
                <span class="text-slate-500">Real-Time Campus Ratio</span>
                <span class="font-bold text-slate-800">Institution Average</span>
              </div>
            </div>

            <!-- Today's Campus & Academic Events Card -->
            <div onclick="openTodayEventsModal()" class="bg-white border border-slate-200 p-4 rounded-2xl flex flex-col justify-between shadow-sm hover:border-blue-300 hover:bg-blue-50/20 transition-all duration-200 cursor-pointer group">
              <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 mb-2.5">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                  <span class="p-1 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-indigo-100">
                    <x-ui.icon name="calendar_month" class="w-4 h-4 text-indigo-600" />
                  </span> Today's Events
                </span>
                <span id="execEventsCountBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-2xs font-mono">Scheduled</span>
              </div>
              <div id="execTodayEventsList" class="space-y-1.5 text-xs text-slate-700 overflow-hidden">
                <div class="flex items-center gap-2 truncate">
                  <span class="w-2 h-2 rounded-full bg-sky-500 shrink-0"></span>
                  <span class="truncate font-semibold text-slate-800">SITTTR Academic Schedule</span>
                </div>
                <div class="flex items-center gap-2 truncate">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                  <span class="truncate text-slate-600">Department CIA Audits</span>
                </div>
              </div>
              <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-blue-600 font-bold group-hover:text-blue-700">
                <span>View events by categories</span>
                <x-ui.icon name="arrow_forward" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
              </div>
            </div>

          </div>

          <!-- Executive Dashboard Actions & Broadcast Desks (3 Cards Row) -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            
            <!-- Department HOD Dashboard Overrides Card -->
            <div class="bg-white border border-slate-200 p-5 rounded-2xl flex flex-col justify-between shadow-sm hover:border-slate-300 transition-all duration-300">
              <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 mb-2.5">
                  <h3 class="font-bold text-slate-900 flex items-center gap-2 text-sm">
                    <span class="p-1 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                      <x-ui.icon name="admin_panel_settings" class="w-4 h-4 text-blue-600" />
                    </span> HOD Dashboard Overrides
                  </h3>
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 shadow-2xs font-mono">Direct Supervision</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed mb-3.5">
                  Directly access and supervise any department HOD console to manage batch allocations &amp; curriculum updates.
                </p>
                <!-- 8 Compact Branch Buttons Grid -->
                <div class="grid grid-cols-4 gap-2">
                  <a href="/dashboard/principal/department/EL" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-amber-500 hover:bg-amber-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <x-ui.icon name="settings_input_component" class="w-5 h-5 text-amber-500 group-hover:scale-110 transition-transform" />
                    <span class="font-bold text-xs text-slate-800">EL</span>
                  </a>
                  <a href="/dashboard/principal/department/ME" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <x-ui.icon name="precision_manufacturing" class="w-5 h-5 text-emerald-500 group-hover:scale-110 transition-transform" />
                    <span class="font-bold text-xs text-slate-800">ME</span>
                  </a>
                  <a href="/dashboard/principal/department/CE" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-pink-500 hover:bg-pink-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <x-ui.icon name="domain" class="w-5 h-5 text-pink-500 group-hover:scale-110 transition-transform" />
                    <span class="font-bold text-xs text-slate-800">CE</span>
                  </a>
                  <a href="/dashboard/principal/department/EEE" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-rose-500 hover:bg-rose-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <x-ui.icon name="bolt" class="w-5 h-5 text-rose-500 group-hover:scale-110 transition-transform" />
                    <span class="font-bold text-xs text-slate-800">EEE</span>
                  </a>
                  <a href="/dashboard/principal/department/CT" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-purple-500 hover:bg-purple-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <x-ui.icon name="computer" class="w-5 h-5 text-purple-500 group-hover:scale-110 transition-transform" />
                    <span class="font-bold text-xs text-slate-800">CT</span>
                  </a>
                  <a href="/dashboard/principal/department/AU" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <x-ui.icon name="directions_car" class="w-5 h-5 text-indigo-500 group-hover:scale-110 transition-transform" />
                    <span class="font-bold text-xs text-slate-800">AU</span>
                  </a>
                  <a href="/dashboard/principal/department/GEN_AIDED" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-teal-500 hover:bg-teal-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <x-ui.icon name="calculate" class="w-5 h-5 text-teal-500 group-hover:scale-110 transition-transform" />
                    <span class="font-bold text-xs text-slate-800">Gen-A</span>
                  </a>
                  <a href="/dashboard/principal/department/GEN_SF" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-cyan-500 hover:bg-cyan-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <x-ui.icon name="functions" class="w-5 h-5 text-cyan-500 group-hover:scale-110 transition-transform" />
                    <span class="font-bold text-xs text-slate-800">Gen-SF</span>
                  </a>
                </div>
              </div>
            </div>

            <!-- Institutional Flash Notice Broadcast Desk Card -->
            <div class="bg-white border border-slate-200 p-5 rounded-2xl flex flex-col justify-between shadow-sm hover:border-slate-300 transition-all duration-300">
              <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 mb-2.5">
                  <h3 class="font-bold text-slate-900 flex items-center gap-2 text-sm">
                    <span class="p-1 bg-sky-50 text-sky-600 rounded-lg flex items-center justify-center shrink-0">
                      <x-ui.icon name="campaign" class="w-4 h-4 text-sky-600" />
                    </span> Flash Notice Broadcast Desk
                  </h3>
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200 shadow-2xs font-mono">Executive Broadcast</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed mb-3.5">
                  Instantly broadcast official notices or circulars with attachments to staff and students immediately or on schedule.
                </p>
                <div class="grid grid-cols-3 gap-2 mb-3.5 text-center">
                  <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 shadow-2xs">
                    <span id="flashNoticeStatSent" class="block font-bold text-slate-900 text-sm">0</span>
                    <span class="text-[10px] text-slate-500 uppercase font-semibold tracking-wider">Broadcasted</span>
                  </div>
                  <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 shadow-2xs">
                    <span id="flashNoticeStatSched" class="block font-bold text-amber-600 text-sm">0</span>
                    <span class="text-[10px] text-slate-500 uppercase font-semibold tracking-wider">Scheduled</span>
                  </div>
                  <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 shadow-2xs">
                    <span id="flashNoticeStatUrgent" class="block font-bold text-rose-600 text-sm">0</span>
                    <span class="text-[10px] text-slate-500 uppercase font-semibold tracking-wider">Urgent</span>
                  </div>
                </div>
              </div>
              <div class="flex flex-wrap gap-2 pt-1 border-t border-slate-100">
                <button onclick="openFlashNoticeModal()" class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl font-semibold text-white transition cursor-pointer text-xs flex items-center justify-center gap-1.5 shadow-sm">
                  <x-ui.icon name="send" class="w-4 h-4" /> Broadcast Notice
                </button>
                <button onclick="openFlashNoticeHistoryModal()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold text-slate-700 transition cursor-pointer text-xs flex items-center justify-center gap-1.5 border border-slate-200">
                  <x-ui.icon name="history" class="w-4 h-4" /> Log
                </button>
              </div>
            </div>

            <!-- College Targeted Event Scheduler Desk Card -->
            <div class="bg-white border border-slate-200 p-5 rounded-2xl flex flex-col justify-between shadow-sm hover:border-slate-300 transition-all duration-300">
              <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 mb-2.5">
                  <h3 class="font-bold text-slate-900 flex items-center gap-2 text-sm">
                    <span class="p-1 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center shrink-0">
                      <x-ui.icon name="event_available" class="w-4 h-4" />
                    </span> College Event Scheduler
                  </h3>
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs font-mono">Targeted Dispatch</span>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed mb-3.5">
                  Schedule institutional events for College, Depts, Staff, Students, or Special Groups (Placement, NSS/NCC, Sports).
                </p>
                <div class="grid grid-cols-3 gap-2 mb-3.5 text-center">
                  <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 shadow-2xs">
                    <span id="principalEventStatCollege" class="block font-bold text-emerald-600 text-sm">0</span>
                    <span class="text-[10px] text-slate-500 uppercase font-semibold tracking-wider">Campus Wide</span>
                  </div>
                  <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 shadow-2xs">
                    <span id="principalEventStatDept" class="block font-bold text-sky-600 text-sm">0</span>
                    <span class="text-[10px] text-slate-500 uppercase font-semibold tracking-wider">Dept/Staff</span>
                  </div>
                  <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 shadow-2xs">
                    <span id="principalEventStatSpecial" class="block font-bold text-purple-600 text-sm">0</span>
                    <span class="text-[10px] text-slate-500 uppercase font-semibold tracking-wider">Special Groups</span>
                  </div>
                </div>
              </div>
              <div class="flex flex-wrap gap-2 pt-1 border-t border-slate-100">
                <button onclick="openPrincipalScheduleEventModal()" class="flex-1 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 rounded-xl font-semibold text-white transition cursor-pointer text-xs flex items-center justify-center gap-1.5 shadow-sm">
                  <x-ui.icon name="edit_calendar" class="w-4 h-4" /> Schedule Event
                </button>
                <button onclick="openPrincipalScheduleEventHistoryModal()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold text-slate-700 transition cursor-pointer text-xs flex items-center justify-center gap-1.5 border border-slate-200">
                  <x-ui.icon name="view_list" class="w-4 h-4" /> Event Log
                </button>
              </div>
            </div>

          </div>

          <!-- EXECUTIVE OPTION 2: COMPACT 3-SEMESTER ACADEMIC PASS MATRIX -->
          <details class="group bg-white border border-slate-200 rounded-2xl shadow-sm hover:border-slate-300 transition-all duration-300" open>
            <summary class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 sm:p-5 cursor-pointer select-none list-none [&::-webkit-details-marker]:hidden gap-3 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                  <x-ui.icon name="analytics" class="w-5 h-5 text-indigo-600" />
                </span>
                <div>
                  <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Previous Semester Branch Academic Pass Matrix</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">3 Semesters per Dept</span>
                  </div>
                  <p class="text-xs text-slate-500 mt-0.5">Department semester pass percentages (S1/S3/S5 or S2/S4/S6) uploaded by HODs.</p>
                </div>
              </div>
              <div class="flex items-center gap-2.5 self-end sm:self-auto shrink-0">
                <a href="/admin/executive-digest/pdf?print=true" target="_blank" onclick="event.stopPropagation()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition border border-slate-200 no-underline flex items-center gap-1.5 shrink-0 shadow-2xs">
                  <x-ui.icon name="print" class="w-4 h-4" /> Board Report A4
                </a>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold transition">
                  <span class="group-open:hidden">Expand Matrix</span>
                  <span class="hidden group-open:inline">Fold Matrix</span>
                  <x-ui.icon name="expand_more" class="w-4 h-4 transition-transform duration-200 group-open:rotate-180" />
                </div>
              </div>
            </summary>

            <div class="p-4 sm:p-5 pt-3 space-y-4">
              <!-- Ultra-Compact High-Density Table -->
              <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                  <thead>
                    <tr class="bg-slate-50 text-slate-700 font-bold uppercase tracking-wider text-xs border-b border-slate-200">
                      <th class="py-2.5 px-3.5">Branch Code &amp; Name</th>
                      <th class="py-2.5 px-3.5 text-center">Sem 1 / 2</th>
                      <th class="py-2.5 px-3.5 text-center">Sem 3 / 4</th>
                      <th class="py-2.5 px-3.5 text-center">Sem 5 / 6</th>
                      <th class="py-2.5 px-3.5 text-center font-bold text-slate-900">Dept Avg</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 font-medium">
                    <!-- EL -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                      <td class="py-2.5 px-3.5 font-bold flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 rounded text-xs font-mono">EL</span>
                        <span class="text-slate-900 text-xs sm:text-sm">Electronics Engg</span>
                      </td>
                      <td id="sem_EL_S1" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">91.6%</td>
                      <td id="sem_EL_S3" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">89.5%</td>
                      <td id="sem_EL_S5" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">92.7%</td>
                      <td id="sem_EL_avg" class="py-2.5 px-3.5 text-center font-mono font-bold text-emerald-700 bg-emerald-50/50">91.3%</td>
                    </tr>

                    <!-- ME -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                      <td class="py-2.5 px-3.5 font-bold flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded text-xs font-mono">ME</span>
                        <span class="text-slate-900 text-xs sm:text-sm">Mechanical Engg</span>
                      </td>
                      <td id="sem_ME_S1" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">87.1%</td>
                      <td id="sem_ME_S3" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">88.3%</td>
                      <td id="sem_ME_S5" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">87.9%</td>
                      <td id="sem_ME_avg" class="py-2.5 px-3.5 text-center font-mono font-bold text-emerald-700 bg-emerald-50/50">87.8%</td>
                    </tr>

                    <!-- CE -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                      <td class="py-2.5 px-3.5 font-bold flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-pink-50 text-pink-800 border border-pink-200 rounded text-xs font-mono">CE</span>
                        <span class="text-slate-900 text-xs sm:text-sm">Civil Engg</span>
                      </td>
                      <td id="sem_CE_S1" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">89.6%</td>
                      <td id="sem_CE_S3" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">91.0%</td>
                      <td id="sem_CE_S5" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">89.1%</td>
                      <td id="sem_CE_avg" class="py-2.5 px-3.5 text-center font-mono font-bold text-emerald-700 bg-emerald-50/50">89.9%</td>
                    </tr>

                    <!-- EEE -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                      <td class="py-2.5 px-3.5 font-bold flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-rose-50 text-rose-800 border border-rose-200 rounded text-xs font-mono">EEE</span>
                        <span class="text-slate-900 text-xs sm:text-sm">Electrical Engg</span>
                      </td>
                      <td id="sem_EEE_S1" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">90.9%</td>
                      <td id="sem_EEE_S3" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">90.7%</td>
                      <td id="sem_EEE_S5" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">92.3%</td>
                      <td id="sem_EEE_avg" class="py-2.5 px-3.5 text-center font-mono font-bold text-emerald-700 bg-emerald-50/50">91.3%</td>
                    </tr>

                    <!-- CT -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                      <td class="py-2.5 px-3.5 font-bold flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-purple-50 text-purple-800 border border-purple-200 rounded text-xs font-mono">CT</span>
                        <span class="text-slate-900 text-xs sm:text-sm">Computer Engg</span>
                      </td>
                      <td id="sem_CT_S1" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">93.7%</td>
                      <td id="sem_CT_S3" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">95.1%</td>
                      <td id="sem_CT_S5" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">95.0%</td>
                      <td id="sem_CT_avg" class="py-2.5 px-3.5 text-center font-mono font-bold text-emerald-700 bg-emerald-50/50">94.6%</td>
                    </tr>

                    <!-- AU -->
                    <tr class="hover:bg-slate-50/70 transition-colors">
                      <td class="py-2.5 px-3.5 font-bold flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-800 border border-indigo-200 rounded text-xs font-mono">AU</span>
                        <span class="text-slate-900 text-xs sm:text-sm">Automobile Engg</span>
                      </td>
                      <td id="sem_AU_S1" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">88.0%</td>
                      <td id="sem_AU_S3" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">87.5%</td>
                      <td id="sem_AU_S5" class="py-2.5 px-3.5 text-center font-mono font-semibold text-slate-700">89.1%</td>
                      <td id="sem_AU_avg" class="py-2.5 px-3.5 text-center font-mono font-bold text-emerald-700 bg-emerald-50/50">88.2%</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Secondary Compliance Indicators Row -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-2.5">
                  <span class="material-symbols-rounded text-indigo-600 text-lg">workspace_premium</span>
                  <span class="text-xs text-slate-600">Faculty FDPs &amp; Workshops: <strong id="execFdpCount" class="text-slate-900 font-bold">12 Verified</strong></span>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-2.5">
                  <span class="material-symbols-rounded text-emerald-600 text-lg">assignment_turned_in</span>
                  <span class="text-xs text-slate-600">NBA Attainment Average: <strong id="execCoPoAvg" class="text-slate-900 font-bold">88.5% CO-PO</strong></span>
                </div>
              </div>
            </div>
          </details>

        </div>
