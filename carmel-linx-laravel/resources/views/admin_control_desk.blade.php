<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - Executive Control Desk</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Modern Typography (Poppins) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Google Icons & Lucide Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- Leaflet Map CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  
  <!-- Vite Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    /* Interactive Hover Tooltip for Staff On Leave */
    .group:hover .group-hover\:block {
      display: block !important;
    }
  </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen font-sans antialiased overflow-hidden">
  
  <!-- Master Application Shell -->
  <div class="flex h-screen overflow-hidden bg-[#FAFAFB]">
    
    <!-- Unified Master Sidebar (Deep Grayish-Blue #0F172A) -->
    <x-layout.sidebar role="admin" active="dashboard" />

    <!-- Main Viewport Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#FAFAFB]">
      
      <!-- Unified Master TopBar (Dynamic Search + Notifications + Profile) -->
      <x-layout.topbar title="Dashboard Overview" subtitle="Campus-wide institutional metrics, faculty compliance, and administrative controls." />

      <!-- Scrollable Main Workspace -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <!-- Global Alert Notification Banner -->
        <div id="globalAlert" class="hidden p-4 rounded-2xl text-sm font-semibold transition-all border shadow-sm"></div>

        <!-- ========================================================================= -->
        <!-- 1. PANEL: DASHBOARD OVERVIEW (PREVIOUS EXACT DESIGN & LAYOUT RESTORED)     -->
        <!-- ========================================================================= -->
        <div id="panelDashboard" class="space-y-6">
          
          <!-- Metrics Grid (Top Row - 5 KPI Cards) -->
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
            
            <!-- Total Staff -->
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-slate-300 transition-all">
              <div class="bg-blue-50 text-blue-600 p-2.5 rounded-xl shrink-0">
                <span class="material-symbols-rounded text-xl">badge</span>
              </div>
              <div class="min-w-0">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider block truncate">Total Staff</span>
                <span id="statTotalStaff" class="font-bold text-slate-900 text-xl leading-tight block">0</span>
              </div>
            </div>

            <!-- Total Students -->
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-slate-300 transition-all">
              <div class="bg-sky-50 text-sky-600 p-2.5 rounded-xl shrink-0">
                <span class="material-symbols-rounded text-xl">school</span>
              </div>
              <div class="min-w-0">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider block truncate">Total Students</span>
                <span id="statTotalStudents" class="font-bold text-slate-900 text-xl leading-tight block">0</span>
              </div>
            </div>

            <!-- Pending Approvals -->
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-slate-300 transition-all">
              <div class="bg-amber-50 text-amber-600 p-2.5 rounded-xl shrink-0">
                <span class="material-symbols-rounded text-xl">pending_actions</span>
              </div>
              <div class="min-w-0">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider block truncate">Pending Approvals</span>
                <span id="statPendingApprovals" class="font-bold text-slate-900 text-xl leading-tight block">0</span>
              </div>
            </div>

            <!-- Classrooms -->
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-slate-300 transition-all">
              <div class="bg-emerald-50 text-emerald-600 p-2.5 rounded-xl shrink-0">
                <span class="material-symbols-rounded text-xl">meeting_room</span>
              </div>
              <div class="min-w-0">
                <span class="text-xs text-slate-500 uppercase font-bold tracking-wider block truncate">Classrooms</span>
                <span id="statTotalClassrooms" class="font-bold text-slate-900 text-xl leading-tight block">0</span>
              </div>
            </div>

            <!-- Academic Pass Rate -->
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl flex items-center gap-3 shadow-sm hover:border-slate-300 transition-all col-span-2 sm:col-span-1">
              <div class="bg-indigo-50 text-indigo-600 p-2.5 rounded-xl shrink-0">
                <span class="material-symbols-rounded text-xl">insights</span>
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
                    <span class="material-symbols-rounded text-sm">event_busy</span>
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
                    <span class="material-symbols-rounded text-sm">how_to_reg</span>
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
                    <span class="material-symbols-rounded text-sm">calendar_month</span>
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
                <span class="material-symbols-rounded text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
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
                      <span class="material-symbols-rounded text-sm">admin_panel_settings</span>
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
                    <span class="material-symbols-rounded text-xl text-amber-500 group-hover:scale-110 transition-transform">settings_input_component</span>
                    <span class="font-bold text-xs text-slate-800">EL</span>
                  </a>
                  <a href="/dashboard/principal/department/ME" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <span class="material-symbols-rounded text-xl text-emerald-500 group-hover:scale-110 transition-transform">precision_manufacturing</span>
                    <span class="font-bold text-xs text-slate-800">ME</span>
                  </a>
                  <a href="/dashboard/principal/department/CE" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-pink-500 hover:bg-pink-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <span class="material-symbols-rounded text-xl text-pink-500 group-hover:scale-110 transition-transform">domain</span>
                    <span class="font-bold text-xs text-slate-800">CE</span>
                  </a>
                  <a href="/dashboard/principal/department/EEE" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-rose-500 hover:bg-rose-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <span class="material-symbols-rounded text-xl text-rose-500 group-hover:scale-110 transition-transform">bolt</span>
                    <span class="font-bold text-xs text-slate-800">EEE</span>
                  </a>
                  <a href="/dashboard/principal/department/CT" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-purple-500 hover:bg-purple-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <span class="material-symbols-rounded text-xl text-purple-500 group-hover:scale-110 transition-transform">computer</span>
                    <span class="font-bold text-xs text-slate-800">CT</span>
                  </a>
                  <a href="/dashboard/principal/department/AU" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <span class="material-symbols-rounded text-xl text-indigo-500 group-hover:scale-110 transition-transform">directions_car</span>
                    <span class="font-bold text-xs text-slate-800">AU</span>
                  </a>
                  <a href="/dashboard/principal/department/GEN_AIDED" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-teal-500 hover:bg-teal-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <span class="material-symbols-rounded text-xl text-teal-500 group-hover:scale-110 transition-transform">calculate</span>
                    <span class="font-bold text-xs text-slate-800">Gen-A</span>
                  </a>
                  <a href="/dashboard/principal/department/GEN_SF" class="no-underline p-2 bg-slate-50 border border-slate-200 hover:border-cyan-500 hover:bg-cyan-50/40 rounded-xl text-center transition-all duration-200 group flex flex-col items-center justify-center gap-1 cursor-pointer shadow-2xs">
                    <span class="material-symbols-rounded text-xl text-cyan-500 group-hover:scale-110 transition-transform">functions</span>
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
                      <span class="material-symbols-rounded text-sm">campaign</span>
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
                  <span class="material-symbols-rounded text-base">send</span> Broadcast Notice
                </button>
                <button onclick="openFlashNoticeHistoryModal()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold text-slate-700 transition cursor-pointer text-xs flex items-center justify-center gap-1.5 border border-slate-200">
                  <span class="material-symbols-rounded text-base">history</span> Log
                </button>
              </div>
            </div>

            <!-- College Targeted Event Scheduler Desk Card -->
            <div class="bg-white border border-slate-200 p-5 rounded-2xl flex flex-col justify-between shadow-sm hover:border-slate-300 transition-all duration-300">
              <div>
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5 mb-2.5">
                  <h3 class="font-bold text-slate-900 flex items-center gap-2 text-sm">
                    <span class="p-1 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center shrink-0">
                      <span class="material-symbols-rounded text-sm">event_available</span>
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
                  <span class="material-symbols-rounded text-base">edit_calendar</span> Schedule Event
                </button>
                <button onclick="openPrincipalScheduleEventHistoryModal()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold text-slate-700 transition cursor-pointer text-xs flex items-center justify-center gap-1.5 border border-slate-200">
                  <span class="material-symbols-rounded text-base">view_list</span> Event Log
                </button>
              </div>
            </div>

          </div>

          <!-- EXECUTIVE OPTION 2: COMPACT 3-SEMESTER ACADEMIC PASS MATRIX -->
          <details class="group bg-white border border-slate-200 rounded-2xl shadow-sm hover:border-slate-300 transition-all duration-300" open>
            <summary class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 sm:p-5 cursor-pointer select-none list-none [&::-webkit-details-marker]:hidden gap-3 border-b border-slate-100">
              <div class="flex items-center gap-3">
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                  <span class="material-symbols-rounded text-lg">analytics</span>
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
                  <span class="material-symbols-rounded text-sm">print</span> Board Report A4
                </a>
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 hover:bg-slate-100 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold transition">
                  <span class="group-open:hidden">Expand Matrix</span>
                  <span class="hidden group-open:inline">Fold Matrix</span>
                  <span class="material-symbols-rounded text-base transition-transform duration-200 group-open:rotate-180">expand_more</span>
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

        <!-- ========================================================================= -->
        <!-- PANEL: ALL-DEPARTMENT TIMETABLES & LIVE CLASS SCHEDULES                   -->
        <!-- ========================================================================= -->
        <div id="panelAll_timetables" class="hidden space-y-6">
          
          <!-- Header Bar -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-4 shadow-sm">
            <div>
              <div class="flex items-center gap-2.5">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                  <span class="material-symbols-rounded text-xl">calendar_month</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900">All-Department Master Timetables</h3>
              </div>
              <p class="text-sm text-slate-500 mt-1">Live period schedules, classroom allocations, and faculty assignments across all branches.</p>
            </div>
            
            <div class="flex items-center gap-2.5 flex-wrap">
              <!-- Active Day Order Indicator -->
              <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Active Day: <strong id="ttActiveDayOrderBadge" class="text-blue-700 font-bold">Day 1</strong></span>
              </div>
            </div>
          </div>

          <!-- Filter & Controls Bar -->
          <div class="bg-white border border-slate-200 p-5 rounded-2xl space-y-4 shadow-sm">
            
            <!-- Department Selection Tabs -->
            <div>
              <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Select Department</label>
              <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hidden" id="ttDeptFilterContainer">
                <button type="button" onclick="filterTtDepartment('ALL')" id="ttDeptBtn_ALL" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-bold transition-all shrink-0 bg-blue-600 text-white shadow-sm">All Departments</button>
                <button type="button" onclick="filterTtDepartment('EL')" id="ttDeptBtn_EL" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Electronics (EL)</button>
                <button type="button" onclick="filterTtDepartment('ME')" id="ttDeptBtn_ME" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Mechanical (ME)</button>
                <button type="button" onclick="filterTtDepartment('CE')" id="ttDeptBtn_CE" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Civil (CE)</button>
                <button type="button" onclick="filterTtDepartment('EEE')" id="ttDeptBtn_EEE" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Electrical (EEE)</button>
                <button type="button" onclick="filterTtDepartment('CT')" id="ttDeptBtn_CT" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Computer (CT)</button>
                <button type="button" onclick="filterTtDepartment('AU')" id="ttDeptBtn_AU" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Automobile (AU)</button>
                <button type="button" onclick="filterTtDepartment('GEN_AIDED')" id="ttDeptBtn_GEN_AIDED" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">General Aided</button>
                <button type="button" onclick="filterTtDepartment('GEN_SF')" id="ttDeptBtn_GEN_SF" class="tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">General SF</button>
              </div>
            </div>

            <!-- Day & Semester Filter Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
              <!-- Day Order Selector Tabs -->
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Schedule Day View</label>
                <div class="grid grid-cols-5 gap-1.5" id="ttDayFilterContainer">
                  <button type="button" onclick="filterTtDay('Day 1')" id="ttDayBtn_Day1" class="tt-day-btn py-2 px-2 rounded-xl text-xs font-bold transition-all text-center bg-blue-600 text-white shadow-sm">Day 1 (Mon)</button>
                  <button type="button" onclick="filterTtDay('Day 2')" id="ttDayBtn_Day2" class="tt-day-btn py-2 px-2 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Day 2 (Tue)</button>
                  <button type="button" onclick="filterTtDay('Day 3')" id="ttDayBtn_Day3" class="tt-day-btn py-2 px-2 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Day 3 (Wed)</button>
                  <button type="button" onclick="filterTtDay('Day 4')" id="ttDayBtn_Day4" class="tt-day-btn py-2 px-2 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Day 4 (Thu)</button>
                  <button type="button" onclick="filterTtDay('Day 5')" id="ttDayBtn_Day5" class="tt-day-btn py-2 px-2 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">Day 5 (Fri)</button>
                </div>
              </div>

              <!-- Semester Filter Tabs -->
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Filter Semester</label>
                <div class="grid grid-cols-7 gap-1.5" id="ttSemFilterContainer">
                  <button type="button" onclick="filterTtSem('ALL')" id="ttSemBtn_ALL" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-bold transition-all text-center bg-blue-600 text-white shadow-sm">All</button>
                  <button type="button" onclick="filterTtSem('S1')" id="ttSemBtn_S1" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S1</button>
                  <button type="button" onclick="filterTtSem('S2')" id="ttSemBtn_S2" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S2</button>
                  <button type="button" onclick="filterTtSem('S3')" id="ttSemBtn_S3" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S3</button>
                  <button type="button" onclick="filterTtSem('S4')" id="ttSemBtn_S4" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S4</button>
                  <button type="button" onclick="filterTtSem('S5')" id="ttSemBtn_S5" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S5</button>
                  <button type="button" onclick="filterTtSem('S6')" id="ttSemBtn_S6" class="tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300">S6</button>
                </div>
              </div>
            </div>

          </div>

          <!-- Timetable Period Grid Header -->
          <div class="bg-slate-100 border border-slate-200 px-5 py-3 rounded-xl flex items-center justify-between text-xs font-bold text-slate-600 uppercase tracking-wider">
            <span class="flex items-center gap-2">
              <span class="material-symbols-rounded text-sm text-blue-600">schedule</span>
              <span>Class Periods &amp; Time Slots (9:00 AM - 4:00 PM)</span>
            </span>
            <span id="ttTotalBatchesFound" class="font-mono text-blue-700 normal-case font-bold">Loading schedules...</span>
          </div>

          <!-- Dynamic Timetable Batches Container -->
          <div id="ttBatchesListContainer" class="space-y-4">
            <!-- Loaded dynamically via loadAllDepartmentTimetables() -->
            <div class="bg-white border border-slate-200 p-8 rounded-2xl text-center text-slate-400">
              <span class="material-symbols-rounded text-4xl block text-slate-300 mb-2">hourglass_empty</span>
              <span class="text-sm font-semibold text-slate-600">Loading department timetables...</span>
            </div>
          </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 2. PANEL: USER ACCOUNTS DIRECTORY -->
        <!-- ========================================================================= -->
        <div id="panelDirectory" class="hidden space-y-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-3 shadow-sm">
            <div>
              <h3 class="text-lg font-bold text-slate-900">Registered Institutional Accounts</h3>
              <p class="text-xs text-slate-500 mt-0.5">Filter, search, audit, and manage profile lifecycle states across all departments.</p>
            </div>
            <button onclick="openRegisterModal()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all cursor-pointer flex items-center gap-2 shadow-sm text-sm">
              <span class="material-symbols-rounded text-lg">person_add</span>
              <span>Register User</span>
            </button>
          </div>

          <!-- Filters Console -->
          <div class="bg-white border border-slate-200 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 shadow-sm">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Search User</label>
              <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all" placeholder="Name, Register No, Mobile...">
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
              <table class="w-full text-left border-collapse text-xs sm:text-sm">
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
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 3. PANEL: DRIVE BACKUPS -->
        <!-- ========================================================================= -->
        <div id="panelBackups" class="hidden space-y-6">
          <div class="flex items-center gap-3 bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
              <span class="material-symbols-rounded text-2xl">cloud_sync</span>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 text-lg">Google Drive Sync Desk</h3>
              <p class="text-xs text-slate-500 mt-0.5">Compile a complete .sql schema and table rows database dump to save locally and sync immediately to your institutional Google Drive backup folder.</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-sm">
              <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                  <span class="material-symbols-rounded text-2xl">cloud</span>
                </div>
                <div>
                  <h4 class="font-bold text-slate-900 text-base">Google Drive Cloud Sync</h4>
                  <p class="text-xs text-slate-500">Automated Institutional Cloud Backup</p>
                </div>
              </div>
              <p class="text-xs text-slate-600 leading-relaxed">
                Connect and sync the entire Carmel Linx database dump directly into the official Google Drive archive with version tracking.
              </p>
              <button onclick="triggerDriveBackup()" id="btnSyncDrive" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                <span class="material-symbols-rounded text-base">sync</span>
                <span>Backup to Google Drive Now</span>
              </button>
              <div id="driveBackupAlert" class="hidden p-3 rounded-xl text-xs font-semibold border"></div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-sm">
              <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                  <span class="material-symbols-rounded text-2xl">download</span>
                </div>
                <div>
                  <h4 class="font-bold text-slate-900 text-base">Direct SQL File Download</h4>
                  <p class="text-xs text-slate-500">Local MySQL Dump (.sql)</p>
                </div>
              </div>
              <p class="text-xs text-slate-600 leading-relaxed">
                Download an immediate plain-text SQL schema and data export for offline retention, emergency recovery, or local test staging.
              </p>
              <a href="/api/system/backup/download" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-sm text-center no-underline">
                <span class="material-symbols-rounded text-base">file_download</span>
                <span>Download SQL File</span>
              </a>
            </div>
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 4. PANEL: SYSTEM AUDIT TRAIL -->
        <!-- ========================================================================= -->
        <div id="panelAudit" class="hidden space-y-6">
          <div class="bg-white border border-slate-200 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4 shadow-sm">
            <div>
              <h3 class="font-bold text-slate-900 text-lg">System Audit Trail</h3>
              <p class="text-xs text-slate-500 mt-0.5">Lifecycle events, password resets, status changes, and registration logs recorded across the platform.</p>
            </div>
            <button onclick="loadAuditTrail()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-xs transition-all flex items-center gap-2 cursor-pointer border border-slate-200">
              <span class="material-symbols-rounded text-sm">sync</span>
              <span>Refresh Audit Log</span>
            </button>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-xs">
                    <th class="py-3 px-4">Timestamp</th>
                    <th class="py-3 px-4">Actor</th>
                    <th class="py-3 px-4">Target User (ID)</th>
                    <th class="py-3 px-4">Action</th>
                    <th class="py-3 px-4">IP Address</th>
                    <th class="py-3 px-4">Details</th>
                  </tr>
                </thead>
                <tbody id="auditTableBody" class="divide-y divide-slate-100 text-slate-800">
                  <tr><td colspan="6" class="p-8 text-center text-slate-400">Loading audit trail...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 5. PANEL: SYSTEM SETTINGS -->
        <!-- ========================================================================= -->
        <div id="panelSettings" class="hidden space-y-6">
          <div class="flex items-center gap-3 bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
              <span class="material-symbols-rounded text-2xl">settings_suggest</span>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 text-lg">System Settings &amp; AI Controls</h3>
              <p class="text-xs text-slate-500 mt-0.5">Configure global AI integrations, syllabus parsing engine, and local fallbacks.</p>
            </div>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-6 shadow-sm">
            <div class="flex items-center justify-between p-5 bg-slate-50 border border-slate-200 rounded-xl gap-4">
              <div class="space-y-1">
                <h4 class="font-bold text-slate-900 text-base flex items-center gap-2">
                  <span class="material-symbols-rounded text-indigo-600 text-xl">auto_awesome</span>
                  <span>Gemini AI Integration Engine</span>
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed max-w-3xl">
                  Toggle Gemini AI integration across the portal. When deactivated (Offline Mode), all syllabus planners, MCQs, and question generation operations will read strictly from local databases and question banks to save API credit costs.
                </p>
              </div>
              <div class="shrink-0 flex items-center">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" id="settingAiEnabled" class="sr-only peer" onchange="saveSystemSettings()">
                  <div class="w-12 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
              </div>
            </div>
            <div id="settingsSaveAlert" class="hidden p-4 rounded-xl font-semibold border text-sm"></div>
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 6. PANEL: PROFESSIONAL ACTIVITIES (INTEGRATED SINGLE-PAGE VIEW) -->
        <!-- ========================================================================= -->
        <div id="panelProf_activities" class="hidden space-y-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-3 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-rounded text-2xl">school</span>
              </div>
              <div>
                <h3 class="text-lg font-bold text-slate-900">Faculty Academic &amp; Professional Activities</h3>
                <p class="text-xs text-slate-500 mt-0.5">FDP certifications, publications, guided projects, industrial trainings, and syllabus gap records.</p>
              </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
              <select id="profActAyFilter" onchange="loadProfActivities()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
                @php
                  $sYear = 2020;
                  $eYear = date('Y') + 3;
                @endphp
                @for($y = $eYear; $y >= $sYear; $y--)
                  @php $yr = $y . '-' . ($y + 1); @endphp
                  <option value="{{ $yr }}" {{ $yr === (date('Y') . '-' . (date('Y') + 1)) ? 'selected' : '' }}>AY {{ $yr }}</option>
                @endfor
              </select>

              <select id="profActDeptFilter" onchange="loadProfActivities()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
                <option value="">All Departments</option>
                <option value="EL">Electronics (EL)</option>
                <option value="ME">Mechanical (ME)</option>
                <option value="CE">Civil (CE)</option>
                <option value="EEE">Electrical (EEE)</option>
                <option value="CT">Computer (CT)</option>
                <option value="AU">Automobile (AU)</option>
                <option value="GEN_AIDED">Gen Aided</option>
                <option value="GEN_SF">Gen SF</option>
              </select>

              <button onclick="loadProfActivities()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200" title="Refresh">
                <span class="material-symbols-rounded text-sm">sync</span>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
              <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <span class="material-symbols-rounded text-emerald-600 text-base">add_circle</span>
                  <span>Record New Faculty Activity</span>
                </h4>
                <span class="text-xs text-slate-400 font-mono" id="profActAyLabel">AY {{ date('Y') }}-{{ date('Y') + 1 }}</span>
              </div>

              <form id="profActivityForm" onsubmit="submitProfActivity(event)" class="space-y-3.5">
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Activity Category</label>
                  <select id="profActType" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="fdp_attended">Faculty Development Program (FDP) / Training</option>
                    <option value="workshop_attended">Technical Workshop / Hands-on BootCamp</option>
                    <option value="course_attended">Online Certification / MOOC / NPTEL Course</option>
                    <option value="project_guided">Student Major / Minor Project Guided</option>
                    <option value="seminar_guided">Student Technical Seminar Guided</option>
                    <option value="publication">Journal / Conference Research Publication</option>
                    <option value="book_published">Authored Book / Book Chapter</option>
                    <option value="gap_in_syllabus">Curriculum Gap / Industrial Bridge Topic</option>
                  </select>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Activity Title / Topic</label>
                  <input type="text" id="profActTitle" required placeholder="e.g. Advanced Embedded IoT Systems FDP" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Organizing Body</label>
                    <input type="text" id="profActOrganizer" required placeholder="e.g. DTE / NITTTR" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Duration / Days</label>
                    <input type="text" id="profActDuration" required placeholder="e.g. 5 Days / 40 Hrs" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Start Date</label>
                    <input type="date" id="profActStartDate" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Description / Key Learnings</label>
                  <textarea id="profActDesc" rows="2" placeholder="Brief summary of the program coverage and implementation in curriculum..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 resize-none"></textarea>
                </div>

                <div id="profActAlert" class="hidden p-3 rounded-xl font-semibold border text-xs"></div>

                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                  <span class="material-symbols-rounded text-base">check</span>
                  <span>Save Activity Record</span>
                </button>
              </form>
            </div>

            <div class="lg:col-span-7 space-y-4">
              <div class="grid grid-cols-3 gap-3">
                <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-sm text-center">
                  <span class="text-xs text-slate-500 font-semibold block">Total Recorded</span>
                  <span id="profActTotalCount" class="text-xl font-bold text-slate-900 block mt-0.5">0</span>
                </div>
                <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-sm text-center">
                  <span class="text-xs text-slate-500 font-semibold block">FDPs &amp; Workshops</span>
                  <span id="profActFdpCount" class="text-xl font-bold text-indigo-600 block mt-0.5">0</span>
                </div>
                <div class="bg-white border border-slate-200 p-3.5 rounded-2xl shadow-sm text-center">
                  <span class="text-xs text-slate-500 font-semibold block">Publications</span>
                  <span id="profActPubCount" class="text-xl font-bold text-emerald-600 block mt-0.5">0</span>
                </div>
              </div>

              <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                  <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span class="material-symbols-rounded text-blue-600 text-base">format_list_bulleted</span>
                    <span>Verified Activities Registry</span>
                  </h4>
                  <span id="profActRegistryCount" class="text-xs text-slate-500">0 records</span>
                </div>

                <div id="profActListContainer" class="space-y-3 max-h-[500px] overflow-y-auto custom-scrollbar">
                  <div class="p-8 text-center text-slate-400 text-sm">Loading activity records...</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 7. PANEL: MASTER LEAVE LEDGER (INTEGRATED SINGLE-PAGE VIEW) -->
        <!-- ========================================================================= -->
        <div id="panelLeave_ledger" class="hidden space-y-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-3 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-rounded text-2xl">event_note</span>
              </div>
              <div>
                <h3 class="text-lg font-bold text-slate-900">All-Department Staff Leave Master Ledger</h3>
                <p class="text-xs text-slate-500 mt-0.5">Multi-stage leave approval trail, departmental balances, and official leave orders.</p>
              </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
              <select id="leaveLedgerDept" onchange="loadLeaveLedger()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
                <option value="">All Departments</option>
                <option value="EL">Electronics (EL)</option>
                <option value="ME">Mechanical (ME)</option>
                <option value="CE">Civil (CE)</option>
                <option value="EEE">Electrical (EEE)</option>
                <option value="CT">Computer (CT)</option>
                <option value="AU">Automobile (AU)</option>
                <option value="GEN_AIDED">Gen Aided</option>
                <option value="GEN_SF">Gen SF</option>
              </select>

              <select id="leaveLedgerStatus" onchange="loadLeaveLedger()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
                <option value="">All Statuses</option>
                <option value="Pending_Principal">Pending Principal Approval</option>
                <option value="Approved">Approved</option>
                <option value="Pending_HOD">Pending HOD</option>
                <option value="Pending_Coordinator">Pending Coordinator</option>
                <option value="Rejected">Rejected</option>
              </select>

              <button onclick="loadLeaveLedger()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200" title="Refresh">
                <span class="material-symbols-rounded text-sm">sync</span>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Total Days</span>
              <span id="leaveKpiTotal" class="text-lg font-bold text-slate-900 block mt-0.5">0.0</span>
            </div>
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Casual (CL)</span>
              <span id="leaveKpiCL" class="text-lg font-bold text-blue-600 block mt-0.5">0.0</span>
            </div>
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Comp (CCL)</span>
              <span id="leaveKpiCCL" class="text-lg font-bold text-amber-600 block mt-0.5">0.0</span>
            </div>
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Duty (DL)</span>
              <span id="leaveKpiDL" class="text-lg font-bold text-indigo-600 block mt-0.5">0.0</span>
            </div>
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Medical (ML)</span>
              <span id="leaveKpiML" class="text-lg font-bold text-emerald-600 block mt-0.5">0.0</span>
            </div>
            <div class="bg-white border border-slate-200 p-3.5 rounded-2xl text-center shadow-sm">
              <span class="text-xs text-slate-500 font-semibold uppercase block">Loss of Pay</span>
              <span id="leaveKpiLOP" class="text-lg font-bold text-rose-600 block mt-0.5">0.0</span>
            </div>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-xs">
                    <th class="py-3.5 px-4">Application &amp; Staff</th>
                    <th class="py-3.5 px-4">Leave Type</th>
                    <th class="py-3.5 px-4">Duration &amp; Dates</th>
                    <th class="py-3.5 px-4">Reason &amp; Duty Arrangement</th>
                    <th class="py-3.5 px-4 text-center">Multi-Stage Status</th>
                    <th class="py-3.5 px-4 text-right">Executive Actions</th>
                  </tr>
                </thead>
                <tbody id="leaveLedgerTableBody" class="divide-y divide-slate-100 text-slate-800">
                  <tr><td colspan="6" class="p-8 text-center text-slate-400">Loading leave ledger applications...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 8. PANEL: SF STAFF ATTENDANCE (INTEGRATED SINGLE-PAGE VIEW) -->
        <!-- ========================================================================= -->
        <div id="panelSf_attendance" class="hidden space-y-6">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-3 shadow-sm">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-rounded text-2xl">how_to_reg</span>
              </div>
              <div>
                <h3 class="text-lg font-bold text-slate-900">SF Staff Biometric &amp; GPS Attendance Ledger</h3>
                <p class="text-xs text-slate-500 mt-0.5">Self-Financing faculty face verification punches, campus geofence compliance, and time logs.</p>
              </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
              <input type="date" id="sfAttStartDate" onchange="loadSfAttendance()" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs" value="{{ now()->startOfMonth()->format('Y-m-d') }}">
              <input type="date" id="sfAttEndDate" onchange="loadSfAttendance()" class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs" value="{{ now()->format('Y-m-d') }}">
              
              <button onclick="openGeofenceModal()" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all shadow-sm cursor-pointer">
                <span class="material-symbols-rounded text-sm">location_on</span>
                <span>Campus GPS Setup</span>
              </button>

              <button onclick="loadSfAttendance()" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200" title="Refresh">
                <span class="material-symbols-rounded text-sm">sync</span>
              </button>
            </div>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto custom-scrollbar">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-xs">
                    <th class="py-3.5 px-4">Staff Member</th>
                    <th class="py-3.5 px-4">Punch Date</th>
                    <th class="py-3.5 px-4">Morning IN-Time</th>
                    <th class="py-3.5 px-4">Evening OUT-Time</th>
                    <th class="py-3.5 px-4 text-center">Compliance Status</th>
                    <th class="py-3.5 px-4 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="sfAttendanceTableBody" class="divide-y divide-slate-100 text-slate-800">
                  <tr><td colspan="6" class="p-8 text-center text-slate-400">Loading SF attendance logs...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 9. PANEL: EXECUTIVE PROFILE -->
        <!-- ========================================================================= -->
        <div id="panelProfile" class="hidden space-y-6">
          <div class="flex items-center gap-3 bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
              <span class="material-symbols-rounded text-2xl">manage_accounts</span>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 text-lg">Executive Profile &amp; Account Security</h3>
              <p class="text-xs text-slate-500 mt-0.5">Manage administrative credentials, official contact channels, and system security.</p>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
              <div class="flex flex-col items-center text-center space-y-3">
                <div class="relative group cursor-pointer" onclick="document.getElementById('profileInputPhoto').click()" title="Click to change profile picture">
                  <div class="w-24 h-24 rounded-full bg-blue-600 text-white font-bold text-2xl flex items-center justify-center shadow-md overflow-hidden border-4 border-slate-50 relative">
                    <img id="profileAvatarImg" src="" alt="Avatar" class="w-full h-full object-cover hidden">
                    <span id="profileAvatarInitial">P</span>
                    <div class="absolute inset-0 bg-black/40 text-white flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                      <span class="material-symbols-rounded text-xl">photo_camera</span>
                      <span class="text-[10px] font-semibold">Change</span>
                    </div>
                  </div>
                  <div class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center text-white" title="Verified Account">
                    <span class="material-symbols-rounded text-xs">check</span>
                  </div>
                </div>
                <div>
                  <h4 id="profileDisplayName" class="font-bold text-slate-900 text-lg">Fr. Antony Varghese CMI</h4>
                  <p id="profileDisplayRole" class="text-xs text-blue-600 font-semibold">Principal &amp; Institutional Head</p>
                  <p class="text-xs text-slate-400 font-medium mt-0.5">Carmel Polytechnic College</p>
                </div>
              </div>

              <div class="border-t border-slate-100 pt-4 space-y-3 text-sm">
                <div class="flex justify-between items-center text-xs">
                  <span class="text-slate-500">Executive ID:</span>
                  <span id="profileDisplayId" class="font-mono font-bold text-slate-800">9946847236</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                  <span class="text-slate-500">Official Email:</span>
                  <span id="profileDisplayEmail" class="font-semibold text-slate-800">principal@carmelpoly.in</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                  <span class="text-slate-500">Authority Scope:</span>
                  <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded font-semibold text-xs">Full System Administration</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                  <span class="text-slate-500">Portal Version:</span>
                  <span class="font-mono text-slate-600">CampusLynk v2.6.4 (R2026 Ready)</span>
                </div>
              </div>
            </div>

            <div class="lg:col-span-7 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
              <div>
                <h4 class="font-bold text-slate-900 text-base flex items-center gap-2">
                  <span class="material-symbols-rounded text-blue-600 text-base">edit</span>
                  <span>Update Account Credentials</span>
                </h4>
                <p class="text-xs text-slate-500 mt-0.5">Modify administrator details and official notification endpoints.</p>
              </div>

              <form id="profileUpdateForm" onsubmit="submitProfileUpdate(event)" class="space-y-4" enctype="multipart/form-data">
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Full Legal Name</label>
                  <input type="text" id="profileInputName" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Official Email Address</label>
                  <input type="email" id="profileInputEmail" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Profile Picture</label>
                  <input type="file" id="profileInputPhoto" accept="image/*" onchange="previewProfilePhoto(this)" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl">
                  <p class="text-[11px] text-slate-400 mt-1">Upload a JPG, PNG, or WEBP portrait photo (Max 2MB).</p>
                </div>

                <div id="profileUpdateAlert" class="hidden p-3 rounded-xl font-semibold border text-xs"></div>

                <button type="submit" id="profileSaveBtn" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                  <span class="material-symbols-rounded text-base">save</span>
                  <span>Save Profile Updates</span>
                </button>
              </form>

              <div class="border-t border-slate-100 pt-5">
                <h4 class="font-bold text-slate-900 text-base flex items-center gap-2 mb-2">
                  <span class="material-symbols-rounded text-amber-600 text-base">lock_reset</span>
                  <span>Change Master Password</span>
                </h4>
                <p class="text-xs text-slate-500 mb-4">Set a strong confidential passphrase for executive portal access.</p>

                <button type="button" onclick="openPasswordModal('ADMIN-001')" class="px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl text-sm font-semibold flex items-center gap-2 transition cursor-pointer">
                  <span class="material-symbols-rounded text-base">key</span>
                  <span>Update Password Now</span>
                </button>
              </div>
            </div>
          </div>
        </div>

      </main>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODALS & DRAWERS -->
  <!-- ========================================================================= -->

  <!-- 1. EDIT STAFF MODAL -->
  <div id="editStaffModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 sm:p-8 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-600">edit</span>
          <span>Edit Staff Details</span>
        </h3>
        <button onclick="closeEditStaffModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <form id="editStaffForm" onsubmit="submitStaffEdit(event)" class="space-y-4">
        <input type="hidden" id="editStaffMobile">
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
          <input type="text" id="editStaffName" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
          <input type="email" id="editStaffEmail" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Department Branch</label>
          <select id="editStaffBranch" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            <option value="EL">Electronics Engineering (EL)</option>
            <option value="ME">Mechanical Engineering (ME)</option>
            <option value="CE">Civil Engineering (CE)</option>
            <option value="EEE">Electrical &amp; Electronics Engineering (EEE)</option>
            <option value="CT">Computer Engineering (CT)</option>
            <option value="AU">Automobile Engineering (AU)</option>
            <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
            <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
            <option value="Admin">Administration</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Designation Role</label>
          <select id="editStaffDesig" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            <option value="Principal">Principal</option>
            <option value="HOD">Head of Department (HOD)</option>
            <option value="Academic_Coordinator">Academic Coordinator (Self-Financing)</option>
            <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
            <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
            <option value="Lecturer">Lecturer</option>
            <option value="Demonstrator">Demonstrator</option>
            <option value="Physical_Instructor">Physical Instructor</option>
            <option value="Trade_Instructor">Trade Instructor</option>
            <option value="Tradesman">Tradesman</option>
            <option value="Laboratory_Assistant">Laboratory Assistant</option>
            <option value="Workshop_Instructor">Workshop Instructor</option>
            <option value="Workshop_Superintendent">Workshop Superintendent</option>
            <option value="Super_Admin">Super Admin</option>
            <option value="Chairman">Chairman</option>
            <option value="Admin">Admin</option>
          </select>
        </div>

        <div id="editStaffAlert" class="hidden p-3 rounded-xl font-semibold border text-sm"></div>

        <div class="flex gap-3 pt-3 border-t border-slate-100">
          <button type="button" onclick="closeEditStaffModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-slate-700 transition-all text-sm">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all text-sm flex items-center justify-center gap-1.5 shadow-sm">
            <span>Save Details</span>
            <div id="editStaffSpinner" class="hidden w-4 h-4 border-2 border-slate-200 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 2. PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-sm p-6 sm:p-8 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-600">lock_reset</span>
          <span>Password Reset</span>
        </h3>
        <button onclick="closePasswordModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <p class="text-xs text-slate-500">Set a new password for <strong id="pwTargetId" class="text-slate-800"></strong>.</p>

      <form id="passwordResetForm" onsubmit="submitPasswordReset(event)" class="space-y-4">
        <input type="hidden" id="pwResetMobile">
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">New Password</label>
          <input type="password" id="pwResetNew" required minlength="4" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
        </div>

        <div id="passwordResetAlert" class="hidden p-3 rounded-xl font-semibold border text-sm"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-slate-700 transition-all text-sm">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all text-sm">Reset Password</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 3. PROFILE AUDIT MODAL -->
  <div id="auditModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-2xl p-6 sm:p-8 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-600">receipt_long</span>
          <span>Profile Audit Trail</span>
        </h3>
        <button onclick="closeAuditModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <p class="text-xs text-slate-500">History log for <strong id="auditTargetUser" class="text-slate-800"></strong>.</p>

      <div class="max-h-80 overflow-y-auto custom-scrollbar border border-slate-100 rounded-xl">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-semibold">
            <tr>
              <th class="p-3">Time</th>
              <th class="p-3">Actor</th>
              <th class="p-3">Action</th>
              <th class="p-3">Details</th>
            </tr>
          </thead>
          <tbody id="userAuditBody" class="divide-y divide-slate-100 text-slate-800">
            <tr><td colspan="4" class="p-4 text-center text-slate-400">Loading audit history...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- 4. REGISTER NEW PROFILE MODAL (RESTORED FULL FIELDS) -->
  <div id="registerModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-600">person_add</span>
          <span>Register New Profile</span>
        </h3>
        <button onclick="closeRegisterModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <form id="registerUserForm" onsubmit="submitNewUser(event)" class="space-y-3.5 text-xs">
        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">User Type <span class="text-rose-500">*</span></label>
          <select id="regType" onchange="toggleRegFields()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 font-semibold">
            <option value="Student" selected>Student Profile</option>
            <option value="Staff">Faculty / Staff Profile</option>
          </select>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Full Name <span class="text-rose-500">*</span></label>
            <input type="text" id="regName" required placeholder="e.g. Rahul K" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address <span class="text-rose-500">*</span></label>
            <input type="email" id="regEmail" required placeholder="name@carmelpoly.edu.in" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
        </div>

        <!-- Student Specific Fields -->
        <div id="regStudentSpecific" class="space-y-3">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Register No <span class="text-slate-400 font-normal">(Optional)</span></label>
              <input type="text" id="regRegisterNo" placeholder="e.g. 25EL1001" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 uppercase">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Admission No <span class="text-rose-500">*</span></label>
              <input type="text" id="regAdmNo" placeholder="e.g. ADM25EL01" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 uppercase">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Branch <span class="text-rose-500">*</span></label>
              <select id="regStudentBranch" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                <option value="EL">EL</option>
                <option value="ME">ME</option>
                <option value="CE">CE</option>
                <option value="EEE">EEE</option>
                <option value="CT">CT</option>
                <option value="AU">AU</option>
                <option value="GEN_AIDED">GEN_AIDED</option>
                <option value="GEN_SF">GEN_SF</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Adm Year <span class="text-rose-500">*</span></label>
              <input type="number" id="regAdmYear" value="{{ date('Y') }}" min="2020" max="2035" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 font-bold">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Semester <span class="text-rose-500">*</span></label>
              <select id="regSemester" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3" selected>S3</option>
                <option value="S4">S4</option>
                <option value="S5">S5</option>
                <option value="S6">S6</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Staff Specific Fields -->
        <div id="regStaffSpecific" class="space-y-3 hidden">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Mobile / Staff ID <span class="text-rose-500">*</span></label>
              <input type="text" id="regStaffMobile" placeholder="e.g. 9876543210" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Department Branch <span class="text-rose-500">*</span></label>
              <select id="regStaffBranch" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                <option value="EL">Electronics Engineering (EL)</option>
                <option value="ME">Mechanical Engineering (ME)</option>
                <option value="CE">Civil Engineering (CE)</option>
                <option value="EEE">Electrical &amp; Electronics Engineering (EEE)</option>
                <option value="CT">Computer Engineering (CT)</option>
                <option value="AU">Automobile Engineering (AU)</option>
                <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
                <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Designation <span class="text-rose-500">*</span></label>
            <select id="regDesignation" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
              <option value="Lecturer">Lecturer</option>
              <option value="HOD">Head of Department (HOD)</option>
              <option value="Academic_Coordinator">Academic Coordinator</option>
              <option value="Demonstrator">Demonstrator</option>
              <option value="Physical_Instructor">Physical Instructor</option>
              <option value="Trade_Instructor">Trade Instructor</option>
              <option value="Workshop_Superintendent">Workshop Superintendent</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password <span class="text-rose-500">*</span></label>
          <input type="password" id="regPassword" required minlength="4" placeholder="e.g. 12345" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
        </div>

        <div id="registerAlert" class="hidden p-3 rounded-xl font-semibold border text-sm"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-slate-700 transition-all text-sm">Cancel</button>
          <button type="submit" id="regSubmitBtn" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all text-sm flex items-center justify-center gap-1.5 shadow-sm">
            <span class="material-symbols-rounded text-base">person_add</span>
            <span>Register Profile</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 5. FLASH NOTICE BROADCAST MODAL -->
  <div id="flashNoticeModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-2xl p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="p-2 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
            <span class="material-symbols-rounded text-xl">campaign</span>
          </div>
          <div>
            <h3 class="font-bold text-slate-900 text-base">Broadcast Executive Flash Notice</h3>
            <p class="text-xs text-slate-500">Immediate directive broadcast across student, staff &amp; department feeds</p>
          </div>
        </div>
        <button onclick="closeFlashNoticeModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <form id="flashNoticeForm" onsubmit="submitFlashNotice(event)" class="space-y-4 text-xs" enctype="multipart/form-data">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Notice Title / Subject <span class="text-rose-500">*</span></label>
            <input type="text" id="fnTitle" name="title" required placeholder="e.g. Special Working Day & Exam Valuation Notice" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Priority / Type <span class="text-rose-500">*</span></label>
            <select id="fnPriority" name="priority" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
              <option value="Normal">Normal Announcement</option>
              <option value="Urgent">Urgent Flash Warning</option>
              <option value="Circular">Official Circular</option>
            </select>
          </div>
        </div>

        <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
          <span class="block text-slate-700 font-bold text-xs uppercase tracking-wider flex items-center gap-1.5">
            <span class="material-symbols-rounded text-sky-600 text-sm">groups</span> Target Audience &amp; Scope
          </span>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block text-slate-600 mb-1 font-semibold">Recipient Group</label>
              <select id="fnTargetAudience" name="target_audience" onchange="toggleNoticeTargetFields()" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL_CAMPUS">🌐 ALL Campus (Staff &amp; Students)</option>
                <option value="STAFF_ALL">👨‍🏫 Staff - All Departments</option>
                <option value="STAFF_DEPT">🏫 Staff - Specific Department</option>
                <option value="STUDENTS_ALL">🎓 Students - All Batches</option>
                <option value="STUDENTS_DEPT_SEM">📚 Students - Dept &amp; Semester</option>
              </select>
            </div>
            <div id="fnDeptWrapper">
              <label class="block text-slate-600 mb-1 font-semibold">Department Branch</label>
              <select id="fnTargetDepartment" name="target_department" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL">All Departments</option>
                <option value="EL">Electronics Engg (EL)</option>
                <option value="ME">Mechanical Engg (ME)</option>
                <option value="CE">Civil Engg (CE)</option>
                <option value="EEE">Electrical Engg (EEE)</option>
                <option value="CT">Computer Engg (CT)</option>
                <option value="AU">Automobile Engg (AU)</option>
                <option value="GEN_AIDED">General Aided</option>
                <option value="GEN_SF">General SF</option>
              </select>
            </div>
            <div id="fnSemWrapper">
              <label class="block text-slate-600 mb-1 font-semibold">Semester Level</label>
              <select id="fnTargetSemester" name="target_semester" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL">All Semesters (S1 to S6)</option>
                <option value="1">Semester 1 (S1)</option>
                <option value="2">Semester 2 (S2)</option>
                <option value="3">Semester 3 (S3)</option>
                <option value="4">Semester 4 (S4)</option>
                <option value="5">Semester 5 (S5)</option>
                <option value="6">Semester 6 (S6)</option>
              </select>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Notice Description / Content <span class="text-rose-500">*</span></label>
          <textarea id="fnContent" name="content" required rows="3" placeholder="Enter detailed notice message, instructions, or official directive text..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 leading-relaxed"></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
          <!-- Left: Attach Image or PDF -->
          <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
              <span class="material-symbols-rounded text-amber-600 text-sm">attach_file</span>
              <span>Attach Image or PDF <span class="text-slate-400 font-normal lowercase">(Optional)</span></span>
            </label>
            <input type="file" id="fnAttachment" name="attachment" accept=".jpg,.jpeg,.png,.webp,.pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            <p class="text-[10px] text-slate-400">Supports JPG, PNG, WEBP images or PDF files (Max 10MB).</p>
          </div>

          <!-- Right: Dispatch Timing -->
          <div class="space-y-1.5">
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
              <span class="material-symbols-rounded text-emerald-600 text-sm">schedule</span>
              <span>Dispatch Timing</span>
            </label>
            <div class="flex items-center gap-4 pt-1">
              <label class="inline-flex items-center gap-1.5 cursor-pointer select-none text-xs font-bold text-slate-700">
                <input type="radio" name="dispatch_type" value="immediate" checked onchange="toggleNoticeDispatchTiming()" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                <span>⚡ Immediate Now</span>
              </label>
              <label class="inline-flex items-center gap-1.5 cursor-pointer select-none text-xs font-bold text-amber-600">
                <input type="radio" name="dispatch_type" value="scheduled" onchange="toggleNoticeDispatchTiming()" class="w-4 h-4 text-amber-600 focus:ring-amber-500">
                <span>⏰ Scheduled</span>
              </label>
            </div>
            <div id="fnScheduledWrapper" style="display:none;" class="pt-1">
              <input type="datetime-local" id="fnScheduledAt" name="scheduled_at" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs text-slate-900 outline-none focus:border-blue-500">
            </div>
          </div>
        </div>

        <div id="flashNoticeAlert" class="hidden p-3 rounded-xl font-semibold border text-sm"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeFlashNoticeModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-slate-700 transition-all text-sm">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all text-sm flex items-center justify-center gap-1.5 shadow-sm">
            <span class="material-symbols-rounded text-base">send</span>
            <span>Broadcast Notice</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 6. FLASH NOTICE HISTORY MODAL -->
  <div id="flashNoticeHistoryModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-3xl p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-600">history</span>
          <span>Flash Notice Broadcast History</span>
        </h3>
        <button onclick="closeFlashNoticeHistoryModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <div class="overflow-x-auto custom-scrollbar border border-slate-100 rounded-xl">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-semibold">
            <tr>
              <th class="p-3">Date</th>
              <th class="p-3">Title &amp; Priority</th>
              <th class="p-3">Target Scope</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody id="flashNoticeHistoryBody" class="divide-y divide-slate-100 text-slate-800">
            <tr><td colspan="4" class="p-4 text-center text-slate-400">Loading broadcast history...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- 7. PRINCIPAL SCHEDULE EVENT MODAL -->
  <div id="principalScheduleEventModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-2xl p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
            <span class="material-symbols-rounded text-xl">event_available</span>
          </div>
          <div>
            <h3 class="font-bold text-slate-900 text-base">Schedule College Institutional Event</h3>
            <p class="text-xs text-slate-500">Target College, Department, Staff, Students, or Special Groups</p>
          </div>
        </div>
        <button onclick="closePrincipalScheduleEventModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <form id="principalScheduleEventForm" onsubmit="submitPrincipalScheduleEvent(event)" class="space-y-3.5 text-xs" enctype="multipart/form-data">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Event Title <span class="text-rose-500">*</span></label>
            <input type="text" id="peTitle" name="title" required placeholder="e.g., Annual Sports Meet 2026 / Placement Drive" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Event Category <span class="text-rose-500">*</span></label>
            <select id="peCategory" name="event_category" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
              <option value="Academic">Academic Schedule</option>
              <option value="Exam">Examinations</option>
              <option value="Cultural">Cultural / Fest</option>
              <option value="Sports">Sports &amp; Athletics</option>
              <option value="Workshop">Workshop / Seminar</option>
              <option value="Holiday">Holiday / Campus Event</option>
              <option value="Meeting">Institutional Meeting / Ceremony</option>
              <option value="Other">Other Special Event</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Event Date <span class="text-rose-500">*</span></label>
            <input type="date" id="peDate" name="event_date" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Start Time</label>
            <input type="time" id="peStartTime" name="start_time" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">End Time</label>
            <input type="time" id="peEndTime" name="end_time" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
        </div>

        <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
          <span class="block text-slate-700 font-bold text-xs uppercase tracking-wider flex items-center gap-1.5">
            <span class="material-symbols-rounded text-emerald-600 text-sm">groups</span> Target Scope &amp; Audience
          </span>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-slate-600 mb-1 font-semibold">Target Audience</label>
              <select id="peTargetAudience" name="target_audience" onchange="togglePrincipalEventTargetFields()" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL_CAMPUS">🌐 College Wide (All Staff &amp; Students)</option>
                <option value="DEPT_SPECIFIC">🏫 Department Specific</option>
                <option value="STAFF_ONLY">👨‍🏫 Staff Only</option>
                <option value="STUDENTS_ONLY">🎓 Students Only</option>
                <option value="SPECIAL_GROUP">⭐ Special Group</option>
              </select>
            </div>
            <div id="peDeptWrapper" style="display:none;">
              <label class="block text-slate-600 mb-1 font-semibold">Target Department</label>
              <select id="peTargetDepartment" name="target_department" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL">All Departments</option>
                <option value="EL">Electronics Engg (EL)</option>
                <option value="ME">Mechanical Engg (ME)</option>
                <option value="CE">Civil Engg (CE)</option>
                <option value="EEE">Electrical Engg (EEE)</option>
                <option value="CT">Computer Engg (CT)</option>
                <option value="AU">Automobile Engg (AU)</option>
                <option value="GEN_AIDED">General Aided</option>
                <option value="GEN_SF">General SF</option>
              </select>
            </div>
            <div id="peSemWrapper" style="display:none;">
              <label class="block text-slate-600 mb-1 font-semibold">Semester Level</label>
              <select id="peTargetSemester" name="target_semester" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL">All Semesters (S1 to S6)</option>
                <option value="S1">Semester 1 (S1)</option>
                <option value="S2">Semester 2 (S2)</option>
                <option value="S3">Semester 3 (S3)</option>
                <option value="S4">Semester 4 (S4)</option>
                <option value="S5">Semester 5 (S5)</option>
                <option value="S6">Semester 6 (S6)</option>
              </select>
            </div>
            <div id="peSpecialGroupWrapper" style="display:none;">
              <label class="block text-slate-600 mb-1 font-semibold">Special Group</label>
              <select id="peSpecialGroupName" name="special_group_name" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="Placement Cell">Placement &amp; Training Cell</option>
                <option value="NSS / NCC">NSS / NCC Units</option>
                <option value="Sports Council">Sports &amp; Athletics Council</option>
                <option value="Student Council">Student Council</option>
                <option value="IEDC Cell">IEDC Incubation Cell</option>
              </select>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Venue / Location</label>
          <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <input type="text" id="peVenue" name="venue" placeholder="e.g., Main Auditorium / Seminar Hall" class="flex-1 w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            <div class="flex items-center gap-4 shrink-0 pt-1 sm:pt-0">
              <label class="inline-flex items-center gap-1.5 cursor-pointer select-none text-xs font-semibold text-slate-700">
                <input type="checkbox" id="peIsFullDay" name="is_full_day" value="1" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span>Full Day Event</span>
              </label>
              <label class="inline-flex items-center gap-1.5 cursor-pointer select-none text-xs font-bold text-amber-600">
                <input type="checkbox" id="peRequiresRsvp" name="requires_rsvp" value="1" class="w-4 h-4 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                <span>RSVP / Attendance Required</span>
              </label>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Event Description &amp; Details</label>
          <textarea id="peDescription" name="description" rows="2" placeholder="Enter details about event objectives, schedule, guest speakers, instructions..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 resize-none leading-relaxed"></textarea>
        </div>

        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
            <span class="material-symbols-rounded text-emerald-600 text-sm">attach_file</span>
            <span>Attach Flyer / Document <span class="text-slate-400 font-normal lowercase">(optional PDF or image)</span></span>
          </label>
          <input type="file" id="peAttachment" name="attachment" accept=".pdf,.png,.jpg,.jpeg,.webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closePrincipalScheduleEventModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-slate-700 transition-all text-sm">Cancel</button>
          <button type="submit" id="peSubmitBtn" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition-all text-sm flex items-center justify-center gap-1.5 shadow-sm">
            <span class="material-symbols-rounded text-base">event_available</span>
            <span>Schedule &amp; Broadcast Event</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 8. PRINCIPAL SCHEDULED EVENTS HISTORY MODAL -->
  <div id="principalScheduleEventHistoryModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-4xl p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <span class="material-symbols-rounded text-emerald-600">event_available</span>
          <span>Scheduled Events Audit Log</span>
        </h3>
        <button onclick="closePrincipalScheduleEventHistoryModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><span class="material-symbols-rounded">close</span></button>
      </div>

      <div class="overflow-x-auto custom-scrollbar border border-slate-100 rounded-xl">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-semibold">
            <tr>
              <th class="p-3">Date &amp; Time</th>
              <th class="p-3">Title &amp; Category</th>
              <th class="p-3">Target Scope</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody id="principalEventHistoryBody" class="divide-y divide-slate-100 text-slate-800">
            <tr><td colspan="4" class="p-4 text-center text-slate-400">Loading events...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- 9. TODAY'S EVENTS LIST MODAL BY CATEGORIES (FROM PREVIOUS DESIGN) -->
  <div id="todayEventsModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4 md:p-6 overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-5xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
      <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-3">
          <div class="p-2.5 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">event_available</span>
          </div>
          <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
              Today's Campus &amp; Academic Events
              <span id="modalEventsTotalBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">0 Total</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Categorized by Departments, College, NSS, NCC, IEDC, Placement Cell &amp; Others</p>
          </div>
        </div>
        <button onclick="closeTodayEventsModal()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <!-- Category Filter Tabs Bar -->
      <div class="p-3.5 bg-slate-50/80 border-b border-slate-100 overflow-x-auto custom-scrollbar flex items-center gap-2">
        <button onclick="filterEventsByCategory('ALL')" id="evtCatTab_ALL" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-sky-50 text-sky-700 border border-sky-200 shadow-2xs">
          <span>All Events</span>
          <span id="evtCnt_ALL" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-sky-100 text-sky-800">0</span>
        </button>
        <button onclick="filterEventsByCategory('Departments')" id="evtCatTab_Departments" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-amber-400">
          <span class="w-2 h-2 rounded-full bg-amber-500"></span>
          <span>Departments</span>
          <span id="evtCnt_Departments" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700">0</span>
        </button>
        <button onclick="filterEventsByCategory('College')" id="evtCatTab_College" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-sky-400">
          <span class="w-2 h-2 rounded-full bg-sky-500"></span>
          <span>College / Academic</span>
          <span id="evtCnt_College" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700">0</span>
        </button>
        <button onclick="filterEventsByCategory('NSS')" id="evtCatTab_NSS" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-emerald-400">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          <span>NSS</span>
          <span id="evtCnt_NSS" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700">0</span>
        </button>
        <button onclick="filterEventsByCategory('NCC')" id="evtCatTab_NCC" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-rose-400">
          <span class="w-2 h-2 rounded-full bg-rose-500"></span>
          <span>NCC</span>
          <span id="evtCnt_NCC" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700">0</span>
        </button>
        <button onclick="filterEventsByCategory('Placement Cell')" id="evtCatTab_Placement Cell" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-teal-400">
          <span class="w-2 h-2 rounded-full bg-teal-500"></span>
          <span>Placement Cell</span>
          <span id="evtCnt_Placement Cell" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700">0</span>
        </button>
      </div>

      <div class="p-5 overflow-y-auto flex-grow space-y-3 custom-scrollbar" id="modalEventsListContainer">
        <!-- Events injected via JS -->
      </div>

      <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
        <span class="text-slate-500">Displaying <strong id="modalShowingCount" class="text-slate-900">0</strong> scheduled event(s)</span>
        <button onclick="closeTodayEventsModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-xl text-xs transition">Close Window</button>
      </div>
    </div>
  </div>

  <!-- 10. CAMPUS GEOFENCE SETUP MODAL (PORTED INTERACTIVE MAP & CONTROLS) -->
  <div id="geofenceModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4 md:p-6 overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-5xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
      
      <!-- Header -->
      <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-3">
          <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">location_on</span>
          </div>
          <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
              Campus GPS &amp; Google Map Setup
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Define centroid coordinates, geofence radius circle, and device GPS accuracy limit.</p>
          </div>
        </div>
        <button onclick="closeGeofenceModal()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <!-- 2-Column Responsive Body -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 p-6 overflow-y-auto custom-scrollbar flex-grow">
        
        <!-- Left Column: Parameters & Config -->
        <div class="lg:col-span-5 space-y-4">
          <div class="flex items-center gap-2.5 pb-2 border-b border-slate-100">
            <span class="material-symbols-rounded text-emerald-600 text-lg">tune</span>
            <div>
              <h4 class="font-bold text-slate-900 text-sm">Campus Geofence Config</h4>
              <p class="text-[11px] text-slate-500">Define centroid coordinates and radius for staff punching.</p>
            </div>
          </div>

          <!-- Capture Current Location Button -->
          <button type="button" onclick="captureCurrentGPS()" class="w-full py-2.5 px-4 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition cursor-pointer shadow-2xs">
            <span class="material-symbols-rounded text-base">my_location</span>
            <span>Capture My Current Location as Centroid</span>
          </button>

          <form id="geofenceForm" onsubmit="submitGeofenceSetup(event)" class="space-y-3.5 text-xs">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                <span class="material-symbols-rounded text-xs text-slate-400">account_balance</span>
                <span>Campus Name <span class="text-rose-500">*</span></span>
              </label>
              <input type="text" id="geoCampusName" name="campus_name" value="Carmel polytechnic College Campus punapra" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                <span class="material-symbols-rounded text-xs text-slate-400">north</span>
                <span>Centroid Latitude (°N) <span class="text-rose-500">*</span></span>
              </label>
              <input type="number" step="any" id="geoLat" name="centroid_lat" value="9.43727187" onchange="updateMapFromInputs()" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-mono outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                <span class="material-symbols-rounded text-xs text-slate-400">east</span>
                <span>Centroid Longitude (°E) <span class="text-rose-500">*</span></span>
              </label>
              <input type="number" step="any" id="geoLng" name="centroid_lng" value="76.34358649" onchange="updateMapFromInputs()" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-mono outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1 flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs text-slate-400">straighten</span>
                  <span>Radius (Meters) <span class="text-rose-500">*</span></span>
                </label>
                <input type="number" id="geoRadius" name="radius_meters" value="110" min="10" max="5000" oninput="updateCircleRadius()" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-bold outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1 flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs text-slate-400">adjust</span>
                  <span>Max Accuracy <span class="text-rose-500">*</span></span>
                </label>
                <input type="number" id="geoAccuracy" name="max_accuracy_meters" value="100" min="5" max="500" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-bold outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
              </div>
            </div>

            <div id="geofenceAlert" class="hidden p-3 rounded-xl font-semibold border text-xs"></div>

            <button type="submit" id="geoSaveBtn" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm">
              <span class="material-symbols-rounded text-base">save</span>
              <span>Save GPS Location Setup</span>
            </button>
          </form>
        </div>

        <!-- Right Column: Interactive Map Preview & Pinpoint -->
        <div class="lg:col-span-7 flex flex-col space-y-3">
          <div class="flex items-center gap-2.5 pb-2 border-b border-slate-100">
            <span class="material-symbols-rounded text-blue-600 text-lg">public</span>
            <div>
              <h4 class="font-bold text-slate-900 text-sm">Interactive Map Preview &amp; Pinpoint</h4>
              <p class="text-[11px] text-slate-500">Drag the marker or click on map to position campus center.</p>
            </div>
          </div>

          <!-- Instructions & Coords display -->
          <div class="flex items-center justify-between gap-2 p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs flex-wrap">
            <span class="text-slate-600 flex items-center gap-1.5 font-medium">
              <span class="material-symbols-rounded text-sm text-blue-600">touch_app</span>
              <span>Drag pin or click map to move pin</span>
            </span>
            <span id="geoCoordDisplay" class="font-mono font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">
              9.43727187, 76.34358649
            </span>
          </div>

          <!-- Leaflet Map Canvas Container -->
          <div id="geofenceMapContainer" class="w-full h-80 sm:h-96 rounded-2xl border border-slate-200 overflow-hidden shadow-inner relative z-0 bg-slate-100"></div>

          <!-- Open in Google Maps External Link -->
          <a id="geoBtnGmapsLink" href="https://www.google.com/maps?q=9.43727187,76.34358649" target="_blank" class="w-full py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl font-semibold text-xs flex items-center justify-center gap-2 transition cursor-pointer">
            <span class="material-symbols-rounded text-base text-rose-500">map</span>
            <span>Open Coordinates in Google Maps</span>
            <span class="material-symbols-rounded text-xs text-slate-400">open_in_new</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MAIN JAVASCRIPT CONTROLLERS -->
  <!-- ========================================================================= -->
  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    let allTodayEventsCache = [];
    let activeEventCategoryFilter = 'ALL';

    document.addEventListener('DOMContentLoaded', () => {
      if (window.lucide) window.lucide.createIcons();

      const urlParams = new URLSearchParams(window.location.search);
      const tab = urlParams.get('tab') || 'dashboard';
      switchPanel(tab);

      loadExecutiveMetrics();
      loadComplianceData();
      loadFlashNoticeStats();
      loadPrincipalEventStats();
      loadProfileDetails();
    });

    function switchPanel(panelId) {
      const panels = [
        'dashboard', 'all_timetables', 'directory', 'backups', 'audit', 'settings', 
        'prof_activities', 'leave_ledger', 'sf_attendance', 'profile'
      ];
      
      panels.forEach(p => {
        let elId = 'panel' + p.charAt(0).toUpperCase() + p.slice(1);
        if (p === 'all_timetables') elId = 'panelAll_timetables';
        const el = document.getElementById(elId);
        if (el) {
          if (p === panelId) {
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        }
      });

      const titleMap = {
        'dashboard': { title: 'Dashboard Overview', subtitle: 'Campus-wide institutional metrics, faculty compliance, and administrative controls.' },
        'all_timetables': { title: 'All-Department Timetables & Schedules', subtitle: 'Live period schedules, classroom allocations, and faculty assignments across all branches.' },
        'directory': { title: 'User Accounts Directory', subtitle: 'Search, audit, activate, and manage institutional staff and student profiles.' },
        'backups': { title: 'Drive Backups & System Sync', subtitle: 'Automated Google Drive cloud backups and offline SQL dump exports.' },
        'audit': { title: 'System Security Audit Trail', subtitle: 'Detailed chronological lifecycle logs, password resets, and access records.' },
        'settings': { title: 'System Settings & AI Controls', subtitle: 'Configure Gemini AI engine integration and institutional parameters.' },
        'prof_activities': { title: 'Faculty Professional Activities', subtitle: 'FDPs, research publications, guided projects, and curriculum enhancements.' },
        'leave_ledger': { title: 'All-Department Master Leave Ledger', subtitle: 'Multi-stage leave approval trail, departmental balances, and official leave orders.' },
        'sf_attendance': { title: 'SF Staff Attendance Master Log', subtitle: 'Self-Financing faculty face verification punches, campus geofence compliance, and time logs.' },
        'profile': { title: 'Executive Profile & Account Security', subtitle: 'Manage administrative credentials, official contact channels, and system security.' }
      };

      const meta = titleMap[panelId] || titleMap['dashboard'];
      const topTitle = document.querySelector('header h1');
      const topSub = document.querySelector('header p');
      if (topTitle) topTitle.innerText = meta.title;
      if (topSub) topSub.innerText = meta.subtitle;

      if (typeof selectSidebarNav === 'function') {
        selectSidebarNav(panelId);
      }

      if (window.lucide) window.lucide.createIcons();

      if (panelId === 'all_timetables') loadAllDepartmentTimetables();
      if (panelId === 'directory') loadUsers();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'prof_activities') loadProfActivities();
      if (panelId === 'leave_ledger') loadLeaveLedger();
      if (panelId === 'sf_attendance') loadSfAttendance();
      if (panelId === 'profile') loadProfileDetails();
    }

    function handleAdminSidebarNav(panelId) {
      switchPanel(panelId);
    }

    // -------------------------------------------------------------------------
    // ALL-DEPARTMENT MASTER TIMETABLES LOGIC
    // -------------------------------------------------------------------------
    let allDeptTimetableCache = null;
    let selectedTtDept = 'ALL';
    let selectedTtDay = 'Day 1';
    let selectedTtSem = 'ALL';

    async function loadAllDepartmentTimetables() {
      const container = document.getElementById('ttBatchesListContainer');
      if (container && (!allDeptTimetableCache || !allDeptTimetableCache.timetables || allDeptTimetableCache.timetables.length === 0)) {
        container.innerHTML = `
          <div class="bg-white border border-slate-200 p-8 rounded-2xl text-center text-slate-400">
            <span class="material-symbols-rounded text-4xl block text-blue-500 animate-spin mb-2">sync</span>
            <span class="text-sm font-semibold text-slate-700">Loading master department timetables...</span>
          </div>
        `;
      }

      try {
        const res = await fetch('/api/admin/timetables/all-departments');
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          allDeptTimetableCache = data;
          if (data.active_day_order) {
            const badge = document.getElementById('ttActiveDayOrderBadge');
            if (badge) badge.innerText = data.active_day_order;
            if (!selectedTtDay || selectedTtDay === 'Day 1') {
              selectedTtDay = data.active_day_order;
              updateDayFilterTabsUI();
            }
          }
          renderAllDepartmentTimetablesUI();
        } else {
          if (container) {
            container.innerHTML = `
              <div class="bg-white border border-rose-200 p-8 rounded-2xl text-center text-rose-500">
                <span class="material-symbols-rounded text-4xl block mb-2">error</span>
                <span class="text-sm font-semibold">${data.message || 'Failed to load timetables.'}</span>
              </div>
            `;
          }
        }
      } catch (err) {
        if (container) {
          container.innerHTML = `
            <div class="bg-white border border-slate-200 p-8 rounded-2xl text-center text-slate-400">
              <span class="material-symbols-rounded text-4xl block text-slate-400 mb-2">cloud_off</span>
              <span class="text-sm font-semibold text-slate-600">Failed to connect to timetable service: ${err.message}</span>
            </div>
          `;
        }
      }
    }

    function filterTtDepartment(dept) {
      selectedTtDept = dept;
      document.querySelectorAll('.tt-dept-btn').forEach(btn => {
        if (btn.id === 'ttDeptBtn_' + dept) {
          btn.className = 'tt-dept-btn px-4 py-2 rounded-xl text-sm font-bold transition-all shrink-0 bg-blue-600 text-white shadow-sm';
        } else {
          btn.className = 'tt-dept-btn px-4 py-2 rounded-xl text-sm font-semibold transition-all shrink-0 bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300';
        }
      });
      renderAllDepartmentTimetablesUI();
    }

    function filterTtDay(day) {
      selectedTtDay = day;
      updateDayFilterTabsUI();
      renderAllDepartmentTimetablesUI();
    }

    function updateDayFilterTabsUI() {
      const dayMap = { 'Day 1': 'Day1', 'Day 2': 'Day2', 'Day 3': 'Day3', 'Day 4': 'Day4', 'Day 5': 'Day5' };
      document.querySelectorAll('.tt-day-btn').forEach(btn => {
        const idKey = dayMap[selectedTtDay] || 'Day1';
        if (btn.id === 'ttDayBtn_' + idKey) {
          btn.className = 'tt-day-btn py-2 px-2 rounded-xl text-xs font-bold transition-all text-center bg-blue-600 text-white shadow-sm';
        } else {
          btn.className = 'tt-day-btn py-2 px-2 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300';
        }
      });
    }

    function filterTtSem(sem) {
      selectedTtSem = sem;
      document.querySelectorAll('.tt-sem-btn').forEach(btn => {
        if (btn.id === 'ttSemBtn_' + sem) {
          btn.className = 'tt-sem-btn py-2 px-1 rounded-xl text-xs font-bold transition-all text-center bg-blue-600 text-white shadow-sm';
        } else {
          btn.className = 'tt-sem-btn py-2 px-1 rounded-xl text-xs font-semibold transition-all text-center bg-slate-50 text-slate-700 border border-slate-200 hover:border-slate-300';
        }
      });
      renderAllDepartmentTimetablesUI();
    }

    function renderAllDepartmentTimetablesUI() {
      const container = document.getElementById('ttBatchesListContainer');
      if (!container || !allDeptTimetableCache) return;

      const rawBatches = allDeptTimetableCache.timetables || [];
      const periodTimings = allDeptTimetableCache.period_timings || {
        1: '09:00 - 10:00 AM', 2: '10:00 - 11:00 AM', 3: '11:10 - 12:10 PM',
        4: '01:00 - 02:00 PM', 5: '02:00 - 03:00 PM', 6: '03:00 - 04:00 PM'
      };

      const dayKey = selectedTtDay || 'Day 1';

      // Filter by department and semester
      const filtered = rawBatches.filter(b => {
        const matchesDept = (selectedTtDept === 'ALL') || (b.branch === selectedTtDept) || (b.classroom_id.startsWith(selectedTtDept + '_'));
        const matchesSem  = (selectedTtSem === 'ALL') || (b.semester === selectedTtSem);
        return matchesDept && matchesSem;
      });

      const countEl = document.getElementById('ttTotalBatchesFound');
      if (countEl) {
        countEl.innerText = `${filtered.length} Classroom Batches (${dayKey})`;
      }

      if (filtered.length === 0) {
        container.innerHTML = `
          <div class="bg-white border border-slate-200 p-8 rounded-2xl text-center text-slate-400">
            <span class="material-symbols-rounded text-4xl block text-slate-300 mb-2">event_busy</span>
            <span class="text-sm font-semibold text-slate-600">No classroom timetables found matching selected filters (${selectedTtDept} - ${selectedTtSem}).</span>
          </div>
        `;
        return;
      }

      container.innerHTML = filtered.map(b => {
        const daySchedule = (b.schedule && b.schedule[dayKey]) ? b.schedule[dayKey] : {};
        
        let periodsHtml = '';
        for (let p = 1; p <= 6; p++) {
          const slot = daySchedule[p] || {
            period: p,
            time: periodTimings[p] || '',
            subject_code: 'FREE',
            subject_name: 'Free Period / Library',
            type: 'Free',
            staff_name: '-'
          };

          const isFree = slot.subject_code === 'FREE' || slot.type === 'Free';
          const isLab = slot.type === 'Practical' || slot.type === 'Lab' || slot.type === 'Practicum';
          
          let cardBg = 'bg-white border-slate-200';
          let badgeBg = 'bg-blue-50 text-blue-700 border-blue-200';
          if (isLab) {
            badgeBg = 'bg-purple-50 text-purple-700 border-purple-200';
          } else if (isFree) {
            cardBg = 'bg-slate-50/70 border-slate-200 opacity-75';
            badgeBg = 'bg-slate-100 text-slate-500 border-slate-200';
          }

          periodsHtml += `
            <div class="${cardBg} border rounded-xl p-3 flex flex-col justify-between shadow-2xs hover:border-blue-300 transition-all">
              <div>
                <div class="flex items-center justify-between gap-1 mb-1.5">
                  <span class="text-[11px] font-mono font-bold text-slate-500">Period ${p}</span>
                  <span class="text-[10px] font-mono font-medium text-slate-400">${slot.time || periodTimings[p] || ''}</span>
                </div>
                <div class="mb-1">
                  <span class="px-2 py-0.5 rounded-md text-[11px] font-bold border uppercase tracking-wider ${badgeBg}">
                    ${slot.subject_code}
                  </span>
                </div>
                <p class="text-xs font-semibold text-slate-800 line-clamp-2 leading-tight mt-1" title="${slot.subject_name}">
                  ${slot.subject_name}
                </p>
              </div>
              <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center gap-1.5 text-xs text-slate-600">
                <span class="material-symbols-rounded text-xs text-slate-400">person</span>
                <span class="truncate font-medium text-[11px] text-slate-700">${slot.staff_name || 'Faculty'}</span>
              </div>
            </div>
          `;
        }

        return `
          <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3.5 hover:border-slate-300 transition-all">
            <!-- Batch Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm border border-blue-100 shrink-0">
                  ${b.semester}
                </div>
                <div>
                  <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span>${b.classroom_id}</span>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">${b.branch_name || b.branch}</span>
                  </h4>
                  <p class="text-xs text-slate-500 mt-0.5">Semester ${b.semester} • Batch Year: ${b.batch_year} • ${b.subjects_count || 0} Assigned Courses</p>
                </div>
              </div>
            </div>

            <!-- 6 Periods Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
              ${periodsHtml}
            </div>
          </div>
        `;
      }).join('');
    }

    // 1. EXECUTIVE METRICS & DASHBOARD OVERVIEW DATA LOADER (RESTORED PREVIOUS LOGIC)
    async function loadExecutiveMetrics() {
      try {
        const res = await fetch('/api/admin/executive-kpis');
        if (!res.ok) return;
        const data = await res.json();

        // 5 KPI Top Cards
        if (data.total_staff !== undefined) document.getElementById('statTotalStaff').innerText = data.total_staff;
        if (data.total_students !== undefined) document.getElementById('statTotalStudents').innerText = data.total_students;
        if (data.pending_approvals !== undefined) document.getElementById('statPendingApprovals').innerText = data.pending_approvals;
        if (data.total_classrooms !== undefined) document.getElementById('statTotalClassrooms').innerText = data.total_classrooms;
        if (data.academic_pass_rate !== undefined) document.getElementById('execAcademicPassRate').innerText = `${data.academic_pass_rate}% Overall`;

        // Daily Staff Leave Snapshot & Interactive Hover Tooltips
        if (data.leave_breakdown) {
          const lb = data.leave_breakdown;
          document.getElementById('execStaffLeaveTotal').innerText = `${lb.total_on_leave || 0} Active`;
          if (document.getElementById('execLeaveCL')) document.getElementById('execLeaveCL').innerText = lb.CL || 0;
          if (document.getElementById('execLeaveCCL')) document.getElementById('execLeaveCCL').innerText = lb.CCL || 0;
          if (document.getElementById('execLeaveDL')) document.getElementById('execLeaveDL').innerText = lb.DL || 0;
          if (document.getElementById('execLeaveML')) document.getElementById('execLeaveML').innerText = lb.ML || 0;
          if (document.getElementById('execLeaveLOP')) document.getElementById('execLeaveLOP').innerText = lb.LOP || 0;
          if (document.getElementById('execLeaveOTHERS')) document.getElementById('execLeaveOTHERS').innerText = lb.OTHERS || 0;

          // Populate hover popup lists with staff names & department
          if (lb.staff_by_type) {
            ['CL', 'CCL', 'DL', 'ML', 'LOP', 'OTHERS'].forEach(t => {
              const listEl = document.getElementById(`popupList${t}`);
              const countEl = document.getElementById(`popupCount${t}`);
              const staffArr = lb.staff_by_type[t] || [];

              if (countEl) countEl.innerText = `${staffArr.length} Staff`;

              if (listEl) {
                if (staffArr.length > 0) {
                  listEl.innerHTML = staffArr.map(s => `
                    <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-1 text-xs">
                      <span class="font-semibold text-slate-800 truncate">${s.name}</span>
                      <span class="text-[10px] text-blue-600 font-mono shrink-0">${s.dept}</span>
                    </div>
                  `).join('');
                } else {
                  listEl.innerHTML = `<span class="text-slate-400 italic block text-xs">No staff on ${t} today</span>`;
                }
              }
            });
          }
        }

        // Today's Events
        if (data.today_events && data.today_events.length > 0) {
          allTodayEventsCache = data.today_events;
          const badge = document.getElementById('execEventsCountBadge');
          if (badge) badge.innerText = `${data.today_events.length} Scheduled`;

          const modalTotalBadge = document.getElementById('modalEventsTotalBadge');
          if (modalTotalBadge) modalTotalBadge.innerText = `${data.today_events.length} Total`;
          
          const listContainer = document.getElementById('execTodayEventsList');
          if (listContainer) {
            listContainer.innerHTML = data.today_events.slice(0, 2).map(ev => `
              <div class="flex items-center gap-2 truncate text-xs">
                <span class="w-2 h-2 rounded-full ${ev.type === 'Holiday' ? 'bg-amber-500' : (ev.type === 'Exam' ? 'bg-rose-500' : 'bg-sky-500')} shrink-0"></span>
                <span class="truncate font-semibold text-slate-800" title="${ev.title}">${ev.title}</span>
              </div>
            `).join('');
          }

          const counts = data.event_counts || {};
          const total = data.today_events.length;
          if (document.getElementById('evtCnt_ALL')) document.getElementById('evtCnt_ALL').innerText = total;

          ['Departments', 'College', 'NSS', 'NCC', 'IEDC', 'Placement Cell', 'Others'].forEach(cat => {
            const cntEl = document.getElementById(`evtCnt_${cat}`);
            if (cntEl) cntEl.innerText = counts[cat] || 0;
          });
        }
      } catch (err) {
        console.error('KPI fetch error:', err);
      }
    }

    async function loadComplianceData() {
      try {
        const res = await fetch('/api/admin/executive-compliance');
        if (!res.ok) return;
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          if (document.getElementById('execFdpCount')) document.getElementById('execFdpCount').innerText = `${data.total_fdps || 12} Verified`;
          if (data.matrix) {
            data.matrix.forEach(row => {
              const el1 = document.getElementById(`sem_${row.code}_S1`);
              const el3 = document.getElementById(`sem_${row.code}_S3`);
              const el5 = document.getElementById(`sem_${row.code}_S5`);
              const elAvg = document.getElementById(`sem_${row.code}_avg`);
              if (el1) el1.innerText = `${row.sem_s1}%`;
              if (el3) el3.innerText = `${row.sem_s3}%`;
              if (el5) el5.innerText = `${row.sem_s5}%`;
              if (elAvg) elAvg.innerText = `${row.avg_pct}%`;
            });
          }
        }
      } catch (err) {
        console.error('Compliance error:', err);
      }
    }

    async function loadFlashNoticeStats() {
      try {
        const res = await fetch('/api/admin/flash-notices');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.stats) {
          if (document.getElementById('flashNoticeStatSent')) document.getElementById('flashNoticeStatSent').innerText = data.stats.total_sent || 0;
          if (document.getElementById('flashNoticeStatSched')) document.getElementById('flashNoticeStatSched').innerText = data.stats.scheduled_count || 0;
          if (document.getElementById('flashNoticeStatUrgent')) document.getElementById('flashNoticeStatUrgent').innerText = data.stats.urgent_count || 0;
        }
      } catch (err) {}
    }

    async function loadPrincipalEventStats() {
      try {
        const res = await fetch('/api/principal/events');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.stats) {
          if (document.getElementById('principalEventStatCollege')) document.getElementById('principalEventStatCollege').innerText = data.stats.college_wide || 0;
          if (document.getElementById('principalEventStatDept')) document.getElementById('principalEventStatDept').innerText = ((data.stats.dept_specific || 0) + (data.stats.staff_only || 0));
          if (document.getElementById('principalEventStatSpecial')) document.getElementById('principalEventStatSpecial').innerText = data.stats.special_groups || 0;
        }
      } catch (err) {}
    }

    // 2. USER ACCOUNTS DIRECTORY LOADER
    async function loadUsers() {
      const search = document.getElementById('filterSearch')?.value || '';
      const branch = document.getElementById('filterBranch')?.value || '';
      const role = document.getElementById('filterRole')?.value || '';
      const status = document.getElementById('filterStatus')?.value || '';

      const tbody = document.getElementById('userTableBody');
      if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">Filtering accounts...</td></tr>`;

      try {
        const query = new URLSearchParams({ search, branch, role, status }).toString();
        const res = await fetch(`/api/admin/users?${query}`);
        const data = await res.json();

        if (data.users && data.users.length > 0) {
          tbody.innerHTML = data.users.map(u => {
            const uid = u.id || u.mobile_no || u.register_no || '-';
            const uname = u.name || 'User';
            const uemail = u.email || 'No email registered';
            const ubranch = u.branch || 'General';
            const urole = u.role || u.designation || 'Staff';
            const utype = u.type || (urole === 'Student' ? 'student' : 'staff');
            const ustatus = u.status || 'Pending';
            const uphoto = u.photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(uname)}&background=0F172A&color=fff`;

            // Status Badge Styling
            let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>`;
            if (ustatus === 'Approved') {
              statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Approved</span>`;
            } else if (ustatus === 'Suspended') {
              statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">Suspended</span>`;
            }

            // Action Options depending on current status
            let toggleButton = '';
            if (ustatus === 'Pending') {
              toggleButton = `
                <button onclick="changeStatus('${uid}', '${utype}', 'Approved')" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-xs font-bold text-white transition cursor-pointer shadow-2xs">
                  Approve
                </button>
              `;
            } else if (ustatus === 'Approved') {
              toggleButton = `
                <button onclick="changeStatus('${uid}', '${utype}', 'Suspended')" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-600 border border-amber-300 text-amber-700 hover:text-white rounded-lg text-xs font-bold transition cursor-pointer shadow-2xs">
                  Suspend
                </button>
              `;
            } else if (ustatus === 'Suspended') {
              toggleButton = `
                <button onclick="changeStatus('${uid}', '${utype}', 'Approved')" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 rounded-lg text-xs font-bold text-white transition cursor-pointer shadow-2xs">
                  Activate
                </button>
              `;
            }

            // Role Designation selector (for Staff members only)
            let roleCol = `<span class="text-xs font-semibold text-slate-600">${urole}</span>`;
            if (utype === 'staff') {
              roleCol = `
                <select onchange="updateDesignation('${uid}', this.value)" class="bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs text-slate-800 outline-none cursor-pointer max-w-[170px] truncate focus:border-blue-500 font-medium">
                  <option value="Super_Admin" ${urole === 'Super_Admin' ? 'selected' : ''}>Super Admin</option>
                  <option value="Admin" ${urole === 'Admin' ? 'selected' : ''}>Admin</option>
                  <option value="Principal" ${urole === 'Principal' ? 'selected' : ''}>Principal</option>
                  <option value="HOD" ${urole === 'HOD' ? 'selected' : ''}>HOD</option>
                  <option value="Academic_Coordinator" ${urole === 'Academic_Coordinator' ? 'selected' : ''}>Academic Coordinator</option>
                  <option value="Gen_Dept_Coordinator_Aided" ${urole === 'Gen_Dept_Coordinator_Aided' ? 'selected' : ''}>Gen Dept Coordinator Aided</option>
                  <option value="Gen_Dept_Coordinator_Self_Finance" ${urole === 'Gen_Dept_Coordinator_Self_Finance' ? 'selected' : ''}>Gen Dept Coordinator Self Finance</option>
                  <option value="Tutor" ${urole === 'Tutor' ? 'selected' : ''}>Tutor</option>
                  <option value="Lecturer" ${urole === 'Lecturer' ? 'selected' : ''}>Lecturer</option>
                  <option value="Demonstrator" ${urole === 'Demonstrator' ? 'selected' : ''}>Demonstrator</option>
                  <option value="Physical_Instructor" ${urole === 'Physical_Instructor' || urole === 'Physical Instructor' ? 'selected' : ''}>Physical Instructor</option>
                  <option value="Trade_Instructor" ${urole === 'Trade_Instructor' ? 'selected' : ''}>Trade Instructor</option>
                  <option value="Tradesman" ${urole === 'Tradesman' ? 'selected' : ''}>Tradesman</option>
                  <option value="Laboratory_Assistant" ${urole === 'Laboratory_Assistant' ? 'selected' : ''}>Laboratory Assistant</option>
                  <option value="Workshop_Instructor" ${urole === 'Workshop_Instructor' ? 'selected' : ''}>Workshop Instructor</option>
                  <option value="Workshop_Superintendent" ${urole === 'Workshop_Superintendent' ? 'selected' : ''}>Workshop Superintendent</option>
                </select>
              `;
            }

            let idColumnHtml = `<span class="font-mono font-bold text-slate-700 text-xs">${uid}</span>`;
            if (utype === 'staff') {
              idColumnHtml = `
                <a href="javascript:void(0)" 
                   onclick="openEditStaffModal('${uid}', '${uname.replace(/'/g, "\\'")}', '${uemail.replace(/'/g, "\\'")}', '${ubranch}', '${urole}')" 
                   class="text-blue-600 hover:text-blue-700 underline font-mono font-bold text-xs transition" 
                   title="Modify profile for ${uname}">
                  ${uid}
                </a>
              `;
            }

            return `
              <tr class="hover:bg-slate-50/70 transition-colors border-b border-slate-100">
                <td class="py-3 px-4 flex items-center gap-3">
                  <img src="${uphoto}" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(uname)}&background=0F172A&color=fff'" class="w-8 h-8 rounded-full object-cover border border-slate-200 shadow-2xs shrink-0">
                  <div class="min-w-0 overflow-hidden">
                    <span class="font-bold text-slate-900 block text-xs sm:text-sm truncate max-w-[150px] lg:max-w-[200px]">${uname}</span>
                    <span class="text-[11px] text-slate-500 block truncate max-w-[150px] lg:max-w-[200px]">${uemail}</span>
                  </div>
                </td>
                <td class="py-3 px-4 font-mono text-xs shrink-0">${idColumnHtml}</td>
                <td class="py-3 px-4"><span class="font-bold font-mono text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200">${ubranch}</span></td>
                <td class="py-3 px-4">${roleCol}</td>
                <td class="py-3 px-4 text-center">${statusBadge}</td>
                <td class="py-3 px-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    ${toggleButton}
                    <button onclick="triggerPasswordReset('${uid}', '${utype}', '${uname.replace(/'/g, "\\'")}')" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 rounded-lg text-xs font-bold transition cursor-pointer shadow-2xs">
                      Reset Pwd
                    </button>
                    <button onclick="openAuditModal('${uid}', '${uname.replace(/'/g, "\\'")}')" class="px-2.5 py-1 bg-slate-100 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 border border-slate-200 text-slate-700 rounded-lg text-xs font-bold transition cursor-pointer shadow-2xs" title="View Audit Trail">
                      Audit
                    </button>
                    <button onclick="confirmDeleteUser('${uid}', '${utype}', '${uname.replace(/'/g, "\\'")}')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-600 hover:text-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold transition cursor-pointer shadow-2xs" title="Delete User">
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            `;
          }).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">No matching user accounts found.</td></tr>`;
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-500">Failed to load directory.</td></tr>`;
      }
    }

    async function changeStatus(userId, userType, newStatus) {
      try {
        const res = await fetch('/api/admin/users/status', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ identifier: userId, status: newStatus, userType })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          loadUsers();
          loadExecutiveMetrics();
        } else {
          alert(data.message || 'Status update failed.');
        }
      } catch (err) {
        alert('Server error updating user status.');
      }
    }

    async function updateDesignation(userId, newRole) {
      try {
        const res = await fetch('/api/admin/user/change-role', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ userId, newRole })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          loadUsers();
        } else {
          alert(data.message || 'Failed to change designation.');
        }
      } catch (err) {
        alert('Failed to change staff designation.');
      }
    }

    // 3. PROFESSIONAL ACTIVITIES LOADER
    async function loadProfActivities() {
      const ay = document.getElementById('profActAyFilter')?.value || '';
      const dept = document.getElementById('profActDeptFilter')?.value || '';
      const container = document.getElementById('profActListContainer');
      const ayLabel = document.getElementById('profActAyLabel');
      if (ayLabel) ayLabel.innerText = ay;

      if (container) container.innerHTML = `<div class="p-8 text-center text-slate-400 text-sm">Loading activity records...</div>`;

      try {
        const query = new URLSearchParams({ academic_year: ay, department: dept }).toString();
        const res = await fetch(`/api/staff/professional-activities/fetch?${query}`);
        const data = await res.json();

        if (data.status === 'SUCCESS' && data.records) {
          document.getElementById('profActTotalCount').innerText = data.records.length;
          const fdpCount = data.records.filter(r => r.activity_type?.includes('fdp') || r.activity_type?.includes('workshop')).length;
          const pubCount = data.records.filter(r => r.activity_type?.includes('publication') || r.activity_type?.includes('book')).length;
          document.getElementById('profActFdpCount').innerText = fdpCount;
          document.getElementById('profActPubCount').innerText = pubCount;
          document.getElementById('profActRegistryCount').innerText = `${data.records.length} records in AY ${ay}`;

          if (data.records.length > 0) {
            container.innerHTML = data.records.map(r => {
              const details = r.details || {};
              return `
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-start justify-between gap-4 hover:border-blue-300 transition-all">
                  <div class="space-y-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                      <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded text-xs font-bold uppercase">${(r.activity_type || 'Activity').replace('_', ' ')}</span>
                      <span class="px-2 py-0.5 bg-slate-200 text-slate-700 rounded text-xs font-semibold">${r.department || 'General'}</span>
                      <span class="text-xs text-slate-500 font-medium">• ${r.staff_name || 'Faculty'} (${r.designation || 'Lecturer'})</span>
                    </div>
                    <h5 class="font-bold text-slate-900 text-sm">${details.title || details.topic || 'Professional Activity'}</h5>
                    <p class="text-xs text-slate-600 leading-relaxed">${details.description || details.organizer || 'Organized program / workshop'}</p>
                    <div class="text-xs text-slate-500 flex items-center gap-3 pt-1 flex-wrap">
                      ${details.start_date ? `<span class="font-semibold text-blue-700">📅 Start Date: <strong>${details.start_date}</strong></span><span>•</span>` : ''}
                      <span><strong>Duration:</strong> ${details.duration || '-'}</span>
                      <span>•</span>
                      <span><strong>Organizer:</strong> ${details.organizer || '-'}</span>
                    </div>
                  </div>
                  <button onclick="deleteProfActivity(${r.id})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition shrink-0" title="Delete record">
                    <span class="material-symbols-rounded text-base">delete</span>
                  </button>
                </div>
              `;
            }).join('');
          } else {
            container.innerHTML = `<div class="p-8 text-center text-slate-400 text-sm">No professional activity records found for AY ${ay}.</div>`;
          }
        }
      } catch (err) {
        if (container) container.innerHTML = `<div class="p-8 text-center text-rose-500 text-sm">Failed to load professional activities.</div>`;
      }
    }

    async function submitProfActivity(e) {
      e.preventDefault();
      const alertEl = document.getElementById('profActAlert');
      const ay = document.getElementById('profActAyFilter')?.value || '2025-2026';
      const type = document.getElementById('profActType').value;
      const title = document.getElementById('profActTitle').value;
      const organizer = document.getElementById('profActOrganizer').value;
      const duration = document.getElementById('profActDuration').value;
      const startDate = document.getElementById('profActStartDate')?.value || '';
      const description = document.getElementById('profActDesc').value;

      try {
        await fetch('/staff/professional-activities/save', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({
            academic_year: ay,
            activity_type: type,
            details: { title, organizer, duration, start_date: startDate, description }
          })
        });

        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
        alertEl.innerText = 'Professional activity recorded successfully!';
        document.getElementById('profActivityForm').reset();
        loadProfActivities();
        setTimeout(() => alertEl.classList.add('hidden'), 3000);
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Error saving activity record.';
      }
    }

    async function deleteProfActivity(id) {
      if (!confirm('Are you sure you want to delete this activity record?')) return;
      try {
        await fetch(`/staff/professional-activities/delete/${id}`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        loadProfActivities();
      } catch (err) {
        alert('Failed to delete activity.');
      }
    }

    // 4. MASTER LEAVE LEDGER LOADER
    async function loadLeaveLedger() {
      const dept = document.getElementById('leaveLedgerDept')?.value || '';
      const status = document.getElementById('leaveLedgerStatus')?.value || '';
      const tbody = document.getElementById('leaveLedgerTableBody');
      if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">Loading leave applications...</td></tr>`;

      try {
        const query = new URLSearchParams({ department: dept, status }).toString();
        const res = await fetch(`/api/staff/leave/reports-data?${query}`);
        const data = await res.json();

        if (data.status === 'SUCCESS' && data.leaves) {
          const sm = data.summary || {};
          document.getElementById('leaveKpiTotal').innerText = (sm.TOTAL_DAYS || 0).toFixed(1);
          document.getElementById('leaveKpiCL').innerText = (sm.CL || 0).toFixed(1);
          document.getElementById('leaveKpiCCL').innerText = (sm.CCL || 0).toFixed(1);
          document.getElementById('leaveKpiDL').innerText = (sm.DL || 0).toFixed(1);
          document.getElementById('leaveKpiML').innerText = (sm.ML || 0).toFixed(1);
          document.getElementById('leaveKpiLOP').innerText = (sm.LOP || 0).toFixed(1);

          if (data.leaves.length > 0) {
            tbody.innerHTML = data.leaves.map(l => `
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="py-3 px-4">
                  <div>
                    <span class="font-bold text-slate-900 block">${l.staff_name}</span>
                    <span class="text-xs text-slate-500">${l.department} • <strong class="font-mono text-blue-600">${l.leave_code}</strong></span>
                  </div>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2.5 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">${l.leave_type}</span>
                </td>
                <td class="py-3 px-4">
                  <span class="text-xs font-semibold text-slate-800 block">${l.from_date} to ${l.to_date}</span>
                  <span class="text-xs text-slate-500 font-bold">${l.total_days} Day(s) (${l.session_type || 'Full Day'})</span>
                </td>
                <td class="py-3 px-4 text-xs text-slate-600 max-w-xs truncate">${l.reason || '-'}</td>
                <td class="py-3 px-4 text-center">
                  <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${l.overall_status === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (l.overall_status === 'Rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200')}">
                    ${l.overall_status?.replace('_', ' ') || 'Pending'}
                  </span>
                </td>
                <td class="py-3 px-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    ${l.overall_status !== 'Approved' && l.overall_status !== 'Rejected' ? `
                      <button onclick="processLeaveApproval(${l.id}, 'Approve')" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition" title="Approve">
                        Approve
                      </button>
                      <button onclick="processLeaveApproval(${l.id}, 'Reject')" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold transition" title="Reject">
                        Reject
                      </button>
                    ` : ''}
                    <a href="/staff/leave/${l.id}/pdf" target="_blank" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Print PDF Application">
                      <span class="material-symbols-rounded text-base">print</span>
                    </a>
                  </div>
                </td>
              </tr>
            `).join('');
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">No staff leave records found.</td></tr>`;
          }
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-500">Failed to load leave records.</td></tr>`;
      }
    }

    async function processLeaveApproval(id, decision) {
      const remarks = prompt(`Enter optional remarks for ${decision.toLowerCase()}ing leave application:`, decision === 'Approve' ? 'Approved by Principal' : 'Rejected');
      if (remarks === null) return;

      try {
        const res = await fetch('/api/staff/leave/process-approval', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({
            leave_id: id,
            stage: 'Principal',
            decision: decision,
            remarks: remarks
          })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          loadLeaveLedger();
        } else {
          alert(data.message || 'Error processing leave decision.');
        }
      } catch (err) {
        alert('Server error updating leave.');
      }
    }

    // 5. SF STAFF ATTENDANCE LOADER
    async function loadSfAttendance() {
      const startDate = document.getElementById('sfAttStartDate')?.value || '';
      const endDate = document.getElementById('sfAttEndDate')?.value || '';
      const tbody = document.getElementById('sfAttendanceTableBody');
      if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">Loading SF attendance logs...</td></tr>`;

      try {
        const query = new URLSearchParams({ start_date: startDate, end_date: endDate }).toString();
        const res = await fetch(`/api/sf-attendance/data?${query}`);
        const data = await res.json();

        if (data.status === 'SUCCESS' && data.punches) {
          if (data.punches.length > 0) {
            tbody.innerHTML = data.punches.map(p => `
              <tr class="hover:bg-slate-50/70 transition-colors">
                <td class="py-3 px-4">
                  <span class="font-bold text-slate-900 block">${p.staff_name || p.staff_id}</span>
                  <span class="text-xs text-slate-500 font-mono">${p.staff_id}</span>
                </td>
                <td class="py-3 px-4 text-xs font-semibold text-slate-700">${p.punch_date}</td>
                <td class="py-3 px-4">
                  <span class="text-xs font-bold text-emerald-700 block">${p.in_time || '--:--'}</span>
                  <span class="text-xs text-slate-500">${p.in_premises_status || 'Geofence Verified'}</span>
                </td>
                <td class="py-3 px-4">
                  <span class="text-xs font-bold text-indigo-700 block">${p.out_time || '--:--'}</span>
                  <span class="text-xs text-slate-500">${p.out_premises_status || '-'}</span>
                </td>
                <td class="py-3 px-4 text-center">
                  <span class="px-2.5 py-1 rounded-full text-xs font-semibold ${p.punch_status?.includes('COMPLETED') || p.punch_status?.includes('PRESENT') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">
                    ${p.punch_status || 'PRESENT'}
                  </span>
                </td>
                <td class="py-3 px-4 text-right">
                  <div class="flex items-center justify-end gap-1.5">
                    <button onclick="deleteSfPunch(${p.id})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Punch Entry">
                      <span class="material-symbols-rounded text-base">delete</span>
                    </button>
                  </div>
                </td>
              </tr>
            `).join('');
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">No SF attendance punches found for this period.</td></tr>`;
          }
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-500">Failed to load SF attendance logs.</td></tr>`;
      }
    }

    async function deleteSfPunch(id) {
      if (!confirm('Are you sure you want to delete this punch entry?')) return;
      try {
        await fetch(`/sf-attendance/delete-punch/${id}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        loadSfAttendance();
      } catch (err) {
        alert('Failed to delete punch.');
      }
    }

    // 6. EXECUTIVE PROFILE LOADER & UPDATES
    async function loadProfileDetails() {
      try {
        const res = await fetch('/api/executive/profile/details');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.user) {
          const u = data.user;
          document.getElementById('profileDisplayName').innerText = u.name;
          document.getElementById('profileDisplayId').innerText = u.user_id;
          document.getElementById('profileDisplayEmail').innerText = u.email;
          
          const avatarImg = document.getElementById('profileAvatarImg');
          const avatarInitial = document.getElementById('profileAvatarInitial');
          if (u.photo_url) {
            avatarImg.src = u.photo_url;
            avatarImg.classList.remove('hidden');
            avatarInitial.classList.add('hidden');
          } else {
            avatarImg.classList.add('hidden');
            avatarInitial.classList.remove('hidden');
            avatarInitial.innerText = (u.name || 'P').charAt(0);
          }
          
          document.getElementById('profileInputName').value = u.name;
          document.getElementById('profileInputEmail').value = u.email;
        }
      } catch (err) {}
    }

    function previewProfilePhoto(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const avatarImg = document.getElementById('profileAvatarImg');
          const avatarInitial = document.getElementById('profileAvatarInitial');
          avatarImg.src = e.target.result;
          avatarImg.classList.remove('hidden');
          avatarInitial.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    async function submitProfileUpdate(e) {
      e.preventDefault();
      const name = document.getElementById('profileInputName').value;
      const email = document.getElementById('profileInputEmail').value;
      const photoInput = document.getElementById('profileInputPhoto');
      const alertEl = document.getElementById('profileUpdateAlert');
      const btn = document.getElementById('profileSaveBtn');

      btn.disabled = true;
      btn.innerHTML = `<span class="material-symbols-rounded animate-spin text-base">sync</span><span>Saving...</span>`;

      try {
        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        if (photoInput && photoInput.files && photoInput.files[0]) {
          formData.append('photo', photoInput.files[0]);
        }

        const res = await fetch('/api/executive/profile/update', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: formData
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = data.message || 'Profile details updated!';
          loadProfileDetails();
        } else {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Failed to update profile.';
        }
        setTimeout(() => alertEl.classList.add('hidden'), 4000);
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Failed to update profile.';
      } finally {
        btn.disabled = false;
        btn.innerHTML = `<span class="material-symbols-rounded text-base">save</span><span>Save Profile Updates</span>`;
      }
    }

    // 7. TODAY'S EVENTS MODAL LOGIC
    function openTodayEventsModal() {
      activeEventCategoryFilter = 'ALL';
      updateCategoryFilterTabsUI();
      renderTodayEventsModalList();

      const modal = document.getElementById('todayEventsModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function closeTodayEventsModal() {
      const modal = document.getElementById('todayEventsModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    function filterEventsByCategory(cat) {
      activeEventCategoryFilter = cat;
      updateCategoryFilterTabsUI();
      renderTodayEventsModalList();
    }

    function updateCategoryFilterTabsUI() {
      const tabs = document.querySelectorAll('.evt-cat-tab');
      tabs.forEach(tab => {
        const tabCat = tab.id.replace('evtCatTab_', '');
        if (tabCat === activeEventCategoryFilter) {
          tab.className = 'evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-sky-50 text-sky-700 border border-sky-200 shadow-2xs';
        } else {
          tab.className = 'evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-slate-300 cursor-pointer';
        }
      });
    }

    function renderTodayEventsModalList() {
      const container = document.getElementById('modalEventsListContainer');
      if (!container) return;

      const events = allTodayEventsCache || [];
      const catFilter = activeEventCategoryFilter || 'ALL';

      const filtered = catFilter === 'ALL' 
        ? events 
        : events.filter(ev => (ev.organizer || 'College') === catFilter);

      const showingEl = document.getElementById('modalShowingCount');
      if (showingEl) showingEl.innerText = filtered.length;

      if (filtered.length === 0) {
        container.innerHTML = `
          <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-2xl border border-slate-200">
            <span class="material-symbols-rounded text-3xl block text-slate-400 mb-1.5">event_busy</span>
            <span class="font-semibold text-xs text-slate-600">No scheduled events found under ${catFilter === 'ALL' ? 'today' : '\'' + catFilter + '\''} category.</span>
          </div>
        `;
        return;
      }

      container.innerHTML = filtered.map(ev => {
        const org = ev.organizer || 'College';
        return `
          <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl transition flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div class="space-y-1.5 flex-1 min-w-0">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border uppercase tracking-wider bg-sky-50 text-sky-700 border-sky-200 flex items-center gap-1 shrink-0">
                  <span class="material-symbols-rounded text-xs">school</span>
                  ${org}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white text-slate-700 border border-slate-200 shrink-0">
                  ${ev.type || 'Event'}
                </span>
              </div>
              <h4 class="font-bold text-slate-900 text-sm leading-snug break-words">${ev.title}</h4>
            </div>
            <div class="shrink-0 space-y-1 md:text-right text-xs text-slate-500 border-t md:border-t-0 border-slate-200 pt-2 md:pt-0">
              <div class="flex items-center md:justify-end gap-1.5 font-mono text-slate-800 font-semibold text-xs">
                <span class="material-symbols-rounded text-sm text-sky-600">schedule</span>
                ${ev.time || '09:30 AM - 04:30 PM'}
              </div>
              <div class="flex items-center md:justify-end gap-1.5 text-slate-500 text-xs">
                <span class="material-symbols-rounded text-sm text-amber-500">location_on</span>
                ${ev.venue || 'Campus Main Auditorium'}
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    // 8. MODAL HANDLERS FOR FLASH NOTICE, EVENTS, AND PASSWORDS
    function openRegisterModal() {
      document.getElementById('registerModal').classList.remove('hidden');
      document.getElementById('registerModal').classList.add('flex');
    }
    function closeRegisterModal() {
      document.getElementById('registerModal').classList.add('hidden');
      document.getElementById('registerModal').classList.remove('flex');
    }

    function toggleRegFields() {
      const type = document.getElementById('regType').value;
      const studentDiv = document.getElementById('regStudentSpecific');
      const staffDiv = document.getElementById('regStaffSpecific');
      if (type === 'Student') {
        if (studentDiv) studentDiv.classList.remove('hidden');
        if (staffDiv) staffDiv.classList.add('hidden');
      } else {
        if (studentDiv) studentDiv.classList.add('hidden');
        if (staffDiv) staffDiv.classList.remove('hidden');
      }
    }

    async function submitNewUser(e) {
      e.preventDefault();
      const type = document.getElementById('regType').value;
      const name = document.getElementById('regName').value;
      const email = document.getElementById('regEmail').value;
      const password = document.getElementById('regPassword').value;
      const alertEl = document.getElementById('registerAlert');
      const submitBtn = document.getElementById('regSubmitBtn');

      let url = '/register/staff';
      let bodyData = {};

      if (type === 'Student') {
        url = '/register/student';
        const admNo = document.getElementById('regAdmNo').value;
        const regNo = document.getElementById('regRegisterNo').value;
        const branch = document.getElementById('regStudentBranch').value;
        const admYear = document.getElementById('regAdmYear').value;
        const semester = document.getElementById('regSemester').value;

        bodyData = {
          name,
          email,
          admNo,
          branch,
          admissionYear: parseInt(admYear) || new Date().getFullYear(),
          admissionType: 'Regular',
          semester: semester,
          password: password,
          sbteRegNo: regNo || ''
        };
      } else {
        url = '/register/staff';
        const mobileNo = document.getElementById('regStaffMobile').value;
        const branch = document.getElementById('regStaffBranch').value;
        const designation = document.getElementById('regDesignation').value;

        bodyData = {
          name,
          email,
          mobileNo,
          branch,
          designation,
          password
        };
      }

      submitBtn.disabled = true;
      submitBtn.innerHTML = `<span class="material-symbols-rounded animate-spin text-base">sync</span><span>Creating...</span>`;

      try {
        const res = await fetch(url, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify(bodyData)
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS' || res.ok) {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = data.message || 'User account created successfully!';
          document.getElementById('registerUserForm').reset();
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
          }, 1500);
        } else {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Error creating account.';
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Server error.';
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = `<span class="material-symbols-rounded text-base">person_add</span><span>Register Profile</span>`;
      }
    }

    // Edit Staff Modal Handlers
    function openEditStaffModal(mobileNo, name, email, branch, designation) {
      document.getElementById('editStaffMobile').value = mobileNo;
      document.getElementById('editStaffName').value = name;
      document.getElementById('editStaffEmail').value = email;
      document.getElementById('editStaffBranch').value = branch;
      if (document.getElementById('editStaffDesig')) document.getElementById('editStaffDesig').value = designation;
      const alertEl = document.getElementById('editStaffAlert');
      if (alertEl) alertEl.classList.add('hidden');

      const modal = document.getElementById('editStaffModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function closeEditStaffModal() {
      const modal = document.getElementById('editStaffModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    }

    async function submitStaffEdit(e) {
      e.preventDefault();
      const mobileNo = document.getElementById('editStaffMobile').value;
      const name = document.getElementById('editStaffName').value.trim();
      const email = document.getElementById('editStaffEmail').value.trim();
      const branch = document.getElementById('editStaffBranch').value;
      const designation = document.getElementById('editStaffDesig').value;

      const alertEl = document.getElementById('editStaffAlert');
      const spinner = document.getElementById('editStaffSpinner');
      if (alertEl) alertEl.classList.add('hidden');
      if (spinner) spinner.classList.remove('hidden');

      try {
        const res = await fetch(`/api/admin/user/update-staff/${mobileNo}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ name, email, branch, designation })
        });
        const data = await res.json();
        if (spinner) spinner.classList.add('hidden');

        if (data.status === 'SUCCESS') {
          if (alertEl) {
            alertEl.className = "p-3 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 block text-xs font-semibold";
            alertEl.innerText = "Staff profile updated successfully!";
            alertEl.classList.remove('hidden');
          }
          setTimeout(() => {
            closeEditStaffModal();
            loadUsers();
          }, 1000);
        } else {
          if (alertEl) {
            alertEl.className = "p-3 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 block text-xs font-semibold";
            alertEl.innerText = data.message || "Failed to update profile.";
            alertEl.classList.remove('hidden');
          }
        }
      } catch (err) {
        if (spinner) spinner.classList.add('hidden');
        if (alertEl) {
          alertEl.className = "p-3 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 block text-xs font-semibold";
          alertEl.innerText = "Server connection error.";
          alertEl.classList.remove('hidden');
        }
      }
    }

    // Password Reset Handlers
    let selectedUserForReset = null;
    function triggerPasswordReset(userId, userType, userName) {
      selectedUserForReset = { userId, userType };
      const elUser = document.getElementById('pwTargetId');
      if (elUser) elUser.innerText = `${userName} (${userId})`;
      const inp = document.getElementById('pwResetNew');
      if (inp) inp.value = '';
      const alertEl = document.getElementById('passwordResetAlert');
      if (alertEl) alertEl.classList.add('hidden');
      const modal = document.getElementById('passwordModal');
      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    }

    function openPasswordModal(mobileNo) {
      triggerPasswordReset(mobileNo, 'staff', mobileNo);
    }

    function closePasswordModal() {
      document.getElementById('passwordModal').classList.add('hidden');
      document.getElementById('passwordModal').classList.remove('flex');
      selectedUserForReset = null;
    }

    async function submitPasswordReset(e) {
      e.preventDefault();
      const targetId = selectedUserForReset ? selectedUserForReset.userId : document.getElementById('pwResetMobile')?.value;
      const newPw = document.getElementById('pwResetNew')?.value.trim();
      const alertEl = document.getElementById('passwordResetAlert');

      if (!newPw || newPw.length < 4) {
        if (alertEl) {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200 block';
          alertEl.innerText = 'Password must be at least 4 characters long.';
          alertEl.classList.remove('hidden');
        }
        return;
      }

      try {
        const res = await fetch('/api/admin/users/reset-password', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ identifier: targetId, new_password: newPw })
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = 'Password reset successfully!';
          setTimeout(() => closePasswordModal(), 1200);
        } else {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Reset failed.';
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Server error resetting password.';
      }
    }

    // Confirm Delete User Handler
    async function confirmDeleteUser(userId, userType, userName) {
      if (!confirm(`Are you absolutely sure you want to permanently delete the profile of ${userName} (${userId})? This action will remove all database credentials.`)) return;
      try {
        const res = await fetch('/api/admin/user/delete', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ userId, userType })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          loadUsers();
          loadExecutiveMetrics();
        } else {
          alert(data.message || 'Failed to delete user.');
        }
      } catch (err) {
        alert('Server error deleting user.');
      }
    }

    async function openAuditModal(userId, name) {
      document.getElementById('auditTargetUser').innerText = `${name} (${userId})`;
      document.getElementById('auditModal').classList.remove('hidden');
      document.getElementById('auditModal').classList.add('flex');
      
      const tbody = document.getElementById('userAuditBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">Loading audit history...</td></tr>`;

      try {
        const res = await fetch(`/api/admin/users/audit/${userId}`);
        const data = await res.json();
        if (data.audit && data.audit.length > 0) {
          tbody.innerHTML = data.audit.map(a => `
            <tr>
              <td class="p-3 text-slate-500">${a.timestamp || a.created_at}</td>
              <td class="p-3 font-semibold text-slate-800">${a.actor_id || 'System'}</td>
              <td class="p-3"><span class="px-2 py-0.5 rounded text-xs font-bold bg-blue-50 text-blue-700">${a.action}</span></td>
              <td class="p-3 text-slate-600">${a.details || '-'}</td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">No audit records for this user.</td></tr>`;
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-rose-500">Failed to load audit.</td></tr>`;
      }
    }
    function closeAuditModal() {
      document.getElementById('auditModal').classList.add('hidden');
      document.getElementById('auditModal').classList.remove('flex');
    }

    function openFlashNoticeModal() {
      document.getElementById('flashNoticeModal').classList.remove('hidden');
      document.getElementById('flashNoticeModal').classList.add('flex');
    }
    function closeFlashNoticeModal() {
      document.getElementById('flashNoticeModal').classList.add('hidden');
      document.getElementById('flashNoticeModal').classList.remove('flex');
    }

    async function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400 font-medium">Loading audit trail logs...</td></tr>`;

      try {
        const res = await fetch('/api/audit-logs');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.logs && data.logs.length > 0) {
          tbody.innerHTML = data.logs.map(log => `
            <tr class="hover:bg-slate-50/70 transition-colors border-b border-slate-100 text-xs">
              <td class="py-3 px-4 font-mono text-slate-500 shrink-0">${log.created_at || log.timestamp || '-'}</td>
              <td class="py-3 px-4 font-semibold text-slate-800">${log.performed_by_name || log.performed_by || 'System'}</td>
              <td class="py-3 px-4 font-mono text-slate-700">${log.target_name ? `${log.target_name} (${log.target_id || '-'})` : (log.target_id || '-')}</td>
              <td class="py-3 px-4">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold ${log.action === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (log.action === 'Registered' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-700 border border-slate-200')}">
                  ${log.action}
                </span>
              </td>
              <td class="py-3 px-4 font-mono text-slate-500">${log.ip_address || '127.0.0.1'}</td>
              <td class="py-3 px-4 text-slate-600 max-w-xs truncate">${log.details || '-'}</td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-400">No audit log entries recorded.</td></tr>`;
        }
      } catch (err) {
        if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-500">Failed to load audit trail.</td></tr>`;
      }
    }

    function toggleNoticeTargetFields() {
      const scope = document.getElementById('fnTargetAudience')?.value;
      const deptWrapper = document.getElementById('fnDeptWrapper');
      const semWrapper = document.getElementById('fnSemWrapper');
      if (deptWrapper) deptWrapper.style.display = (scope === 'STAFF_DEPT' || scope === 'STUDENTS_DEPT_SEM') ? 'block' : 'block';
      if (semWrapper) semWrapper.style.display = (scope === 'STUDENTS_DEPT_SEM') ? 'block' : 'block';
    }

    function toggleNoticeDispatchTiming() {
      const dispatchType = document.querySelector('input[name="dispatch_type"]:checked')?.value;
      const scheduledWrapper = document.getElementById('fnScheduledWrapper');
      if (scheduledWrapper) {
        scheduledWrapper.style.display = dispatchType === 'scheduled' ? 'block' : 'none';
      }
    }

    async function submitFlashNotice(e) {
      e.preventDefault();
      const form = document.getElementById('flashNoticeForm');
      const formData = new FormData(form);
      const alertEl = document.getElementById('flashNoticeAlert');
      const btn = form.querySelector('button[type="submit"]');

      if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div><span>Broadcasting...</span>`;
      }

      try {
        const res = await fetch('/api/admin/flash-notices/broadcast', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: formData
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = data.message || 'Flash notice broadcasted successfully!';
          form.reset();
          toggleNoticeDispatchTiming();
          loadFlashNoticeStats();
          setTimeout(() => closeFlashNoticeModal(), 1500);
        } else {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Failed to broadcast notice.';
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Failed to broadcast notice.';
      } finally {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = `<span class="material-symbols-rounded text-base">send</span><span>Broadcast Notice</span>`;
        }
      }
    }

    async function openFlashNoticeHistoryModal() {
      document.getElementById('flashNoticeHistoryModal').classList.remove('hidden');
      document.getElementById('flashNoticeHistoryModal').classList.add('flex');
      const tbody = document.getElementById('flashNoticeHistoryBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">Loading broadcast history...</td></tr>`;

      try {
        const res = await fetch('/api/admin/flash-notices');
        const data = await res.json();
        if (data.notices && data.notices.length > 0) {
          tbody.innerHTML = data.notices.map(n => `
            <tr>
              <td class="p-3 text-slate-500">${n.created_at?.slice(0, 10) || '-'}</td>
              <td class="p-3">
                <span class="font-semibold text-slate-900 block">${n.title}</span>
                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold ${n.priority === 'Urgent' ? 'bg-rose-50 text-rose-700' : 'bg-slate-100 text-slate-700'}">${n.priority || 'Normal'}</span>
              </td>
              <td class="p-3"><span class="px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-xs font-semibold">${n.target_audience}</span></td>
              <td class="p-3 text-right">
                <button onclick="revokeFlashNotice(${n.id})" class="px-2 py-1 bg-rose-50 text-rose-600 rounded text-xs font-semibold hover:bg-rose-100">Revoke</button>
              </td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">No active broadcast notices.</td></tr>`;
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-rose-500">Failed to load history.</td></tr>`;
      }
    }
    function closeFlashNoticeHistoryModal() {
      document.getElementById('flashNoticeHistoryModal').classList.add('hidden');
      document.getElementById('flashNoticeHistoryModal').classList.remove('flex');
    }

    async function revokeFlashNotice(id) {
      if (!confirm('Revoke this flash notice broadcast?')) return;
      try {
        await fetch(`/api/admin/flash-notices/revoke/${id}`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        openFlashNoticeHistoryModal();
        loadFlashNoticeStats();
      } catch (err) {
        alert('Failed to revoke notice.');
      }
    }

    function openPrincipalScheduleEventModal() {
      document.getElementById('principalScheduleEventModal').classList.remove('hidden');
      document.getElementById('principalScheduleEventModal').classList.add('flex');
    }
    function closePrincipalScheduleEventModal() {
      document.getElementById('principalScheduleEventModal').classList.add('hidden');
      document.getElementById('principalScheduleEventModal').classList.remove('flex');
    }

    function togglePrincipalEventTargetFields() {
      const scope = document.getElementById('peTargetAudience')?.value;
      const deptWrapper = document.getElementById('peDeptWrapper');
      const semWrapper = document.getElementById('peSemWrapper');
      const specialGroupWrapper = document.getElementById('peSpecialGroupWrapper');

      if (deptWrapper) deptWrapper.style.display = (scope === 'DEPT_SPECIFIC' || scope === 'STUDENTS_ONLY') ? 'block' : 'none';
      if (semWrapper) semWrapper.style.display = (scope === 'STUDENTS_ONLY') ? 'block' : 'none';
      if (specialGroupWrapper) specialGroupWrapper.style.display = (scope === 'SPECIAL_GROUP') ? 'block' : 'none';
    }

    async function submitPrincipalScheduleEvent(e) {
      e.preventDefault();
      const form = document.getElementById('principalScheduleEventForm');
      const formData = new FormData(form);
      const btn = document.getElementById('peSubmitBtn');
      btn.disabled = true;
      btn.innerHTML = `<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div><span>Scheduling...</span>`;

      try {
        const res = await fetch('/api/principal/events/schedule', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: formData
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          form.reset();
          loadExecutiveMetrics();
          loadPrincipalEventStats();
          closePrincipalScheduleEventModal();
          alert(data.message || 'Campus event scheduled & broadcasted successfully!');
        } else {
          alert('Error: ' + (data.message || 'Failed to schedule event.'));
        }
      } catch (err) {
        alert('Failed to schedule event.');
      } finally {
        btn.disabled = false;
        btn.innerHTML = `<span class="material-symbols-rounded text-base">event_available</span><span>Schedule &amp; Broadcast Event</span>`;
      }
    }

    async function openPrincipalScheduleEventHistoryModal() {
      document.getElementById('principalScheduleEventHistoryModal').classList.remove('hidden');
      document.getElementById('principalScheduleEventHistoryModal').classList.add('flex');
      const tbody = document.getElementById('principalEventHistoryBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">Loading events...</td></tr>`;

      try {
        const res = await fetch('/api/principal/events');
        const data = await res.json();
        if (data.events && data.events.length > 0) {
          tbody.innerHTML = data.events.map(ev => `
            <tr>
              <td class="p-3 text-slate-500 font-mono">${ev.event_date}</td>
              <td class="p-3">
                <span class="font-semibold text-slate-900 block">${ev.title}</span>
                <span class="px-1.5 py-0.2 text-[10px] rounded font-semibold bg-emerald-50 text-emerald-700">${ev.event_category || 'Event'}</span>
              </td>
              <td class="p-3"><span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded text-xs font-semibold">${ev.target_audience}</span></td>
              <td class="p-3 text-right">
                <button onclick="deletePrincipalEvent(${ev.id})" class="p-1 text-slate-400 hover:text-rose-600 rounded"><span class="material-symbols-rounded text-base">delete</span></button>
              </td>
            </tr>
          `).join('');
        } else {
          tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400">No scheduled events found.</td></tr>`;
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-rose-500">Failed to load events.</td></tr>`;
      }
    }
    function closePrincipalScheduleEventHistoryModal() {
      document.getElementById('principalScheduleEventHistoryModal').classList.add('hidden');
      document.getElementById('principalScheduleEventHistoryModal').classList.remove('flex');
    }

    async function deletePrincipalEvent(id) {
      if (!confirm('Delete this scheduled event?')) return;
      try {
        await fetch(`/api/principal/events/${id}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        openPrincipalScheduleEventHistoryModal();
        loadExecutiveMetrics();
        loadPrincipalEventStats();
      } catch (err) {
        alert('Failed to delete event.');
      }
    }

    // 10. CAMPUS GEOFENCE INTERACTIVE MAP & PINPOINT CONTROLLER
    let geofenceMap = null;
    let geofenceMarker = null;
    let geofenceCircle = null;

    async function openGeofenceModal() {
      const modal = document.getElementById('geofenceModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      try {
        const res = await fetch('/api/admin/geofence/settings');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.geofence) {
          const g = data.geofence;
          document.getElementById('geoCampusName').value = g.campus_name || 'Carmel polytechnic College Campus punapra';
          document.getElementById('geoLat').value = g.centroid_lat;
          document.getElementById('geoLng').value = g.centroid_lng;
          document.getElementById('geoRadius').value = g.radius_meters || 110;
          document.getElementById('geoAccuracy').value = g.max_accuracy_meters || 100;
          document.getElementById('geoCoordDisplay').innerText = `${parseFloat(g.centroid_lat).toFixed(8)}, ${parseFloat(g.centroid_lng).toFixed(8)}`;
          document.getElementById('geoBtnGmapsLink').href = `https://www.google.com/maps?q=${g.centroid_lat},${g.centroid_lng}`;
        }
      } catch (err) {}

      initOrUpdateGeofenceMap();
    }

    function closeGeofenceModal() {
      const modal = document.getElementById('geofenceModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function initOrUpdateGeofenceMap() {
      const lat = parseFloat(document.getElementById('geoLat').value) || 9.43727187;
      const lng = parseFloat(document.getElementById('geoLng').value) || 76.34358649;
      const radius = parseInt(document.getElementById('geoRadius').value) || 110;

      setTimeout(() => {
        const container = document.getElementById('geofenceMapContainer');
        if (!container) return;

        if (!geofenceMap) {
          geofenceMap = L.map('geofenceMapContainer').setView([lat, lng], 16);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
          }).addTo(geofenceMap);

          geofenceMarker = L.marker([lat, lng], { draggable: true }).addTo(geofenceMap);
          geofenceCircle = L.circle([lat, lng], {
            color: '#2563eb',
            fillColor: '#3b82f6',
            fillOpacity: 0.25,
            radius: radius
          }).addTo(geofenceMap);

          geofenceMarker.on('dragend', function(e) {
            const pos = geofenceMarker.getLatLng();
            updateGeofenceCoordinates(pos.lat, pos.lng);
          });

          geofenceMap.on('click', function(e) {
            geofenceMarker.setLatLng(e.latlng);
            updateGeofenceCoordinates(e.latlng.lat, e.latlng.lng);
          });
        } else {
          geofenceMap.setView([lat, lng], 16);
          geofenceMarker.setLatLng([lat, lng]);
          geofenceCircle.setLatLng([lat, lng]);
          geofenceCircle.setRadius(radius);
          geofenceMap.invalidateSize();
        }
      }, 150);
    }

    function updateGeofenceCoordinates(lat, lng) {
      const formattedLat = parseFloat(lat).toFixed(8);
      const formattedLng = parseFloat(lng).toFixed(8);

      document.getElementById('geoLat').value = formattedLat;
      document.getElementById('geoLng').value = formattedLng;
      document.getElementById('geoCoordDisplay').innerText = `${formattedLat}, ${formattedLng}`;

      if (geofenceCircle) geofenceCircle.setLatLng([lat, lng]);

      const gmapsBtn = document.getElementById('geoBtnGmapsLink');
      if (gmapsBtn) {
        gmapsBtn.href = `https://www.google.com/maps?q=${formattedLat},${formattedLng}`;
      }
    }

    function updateMapFromInputs() {
      const lat = parseFloat(document.getElementById('geoLat').value);
      const lng = parseFloat(document.getElementById('geoLng').value);

      if (!isNaN(lat) && !isNaN(lng)) {
        if (geofenceMarker) geofenceMarker.setLatLng([lat, lng]);
        if (geofenceCircle) geofenceCircle.setLatLng([lat, lng]);
        if (geofenceMap) geofenceMap.panTo([lat, lng]);
        document.getElementById('geoCoordDisplay').innerText = `${lat.toFixed(8)}, ${lng.toFixed(8)}`;
        document.getElementById('geoBtnGmapsLink').href = `https://www.google.com/maps?q=${lat},${lng}`;
      }
    }

    function updateCircleRadius() {
      const rad = parseInt(document.getElementById('geoRadius').value);
      if (geofenceCircle && !isNaN(rad) && rad > 0) {
        geofenceCircle.setRadius(rad);
      }
    }

    function captureCurrentGPS() {
      if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition((pos) => {
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;
          updateGeofenceCoordinates(lat, lng);
          if (geofenceMap) geofenceMap.setView([lat, lng], 17);
        }, (err) => {
          alert("Unable to fetch current GPS coordinates. Please grant location access.");
        }, { enableHighAccuracy: true });
      } else {
        alert("Geolocation is not supported by your browser.");
      }
    }

    async function submitGeofenceSetup(e) {
      e.preventDefault();
      const campus_name = document.getElementById('geoCampusName').value;
      const centroid_lat = document.getElementById('geoLat').value;
      const centroid_lng = document.getElementById('geoLng').value;
      const radius_meters = document.getElementById('geoRadius').value;
      const max_accuracy_meters = document.getElementById('geoAccuracy').value;
      const alertEl = document.getElementById('geofenceAlert');
      const saveBtn = document.getElementById('geoSaveBtn');

      saveBtn.disabled = true;
      saveBtn.innerHTML = `<span class="material-symbols-rounded animate-spin text-base">sync</span><span>Saving...</span>`;

      try {
        const res = await fetch('/sf-attendance/geofence-setup', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ campus_name, centroid_lat, centroid_lng, radius_meters, max_accuracy_meters })
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS' || res.ok) {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = data.message || 'Campus GPS Geofence saved successfully!';
          setTimeout(() => closeGeofenceModal(), 1500);
        } else {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Failed to update geofence.';
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Failed to update geofence.';
      } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = `<span class="material-symbols-rounded text-base">save</span><span>Save GPS Location Setup</span>`;
      }
    }

    async function triggerDriveBackup() {
      const btn = document.getElementById('btnSyncDrive');
      const alertEl = document.getElementById('driveBackupAlert');
      btn.disabled = true;
      btn.innerHTML = `<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div><span>Backing up to Google Drive...</span>`;

      try {
        const res = await fetch('/api/system/backup/google-drive', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = 'Google Drive database backup completed successfully!';
        } else {
          alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-amber-50 text-amber-700 border-amber-200';
          alertEl.innerText = data.message || 'Backup synced with warnings.';
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Network error during Google Drive backup.';
      } finally {
        btn.disabled = false;
        btn.innerHTML = `<span class="material-symbols-rounded text-base">sync</span><span>Backup to Google Drive Now</span>`;
      }
    }

    async function saveSystemSettings() {
      const enabled = document.getElementById('settingAiEnabled').checked;
      const alertEl = document.getElementById('settingsSaveAlert');
      try {
        const res = await fetch('/api/admin/settings/ai-toggle', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ ai_enabled: enabled })
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-4 rounded-xl font-semibold border text-sm bg-emerald-50 text-emerald-700 border-emerald-200';
        alertEl.innerText = `Gemini AI Integration Engine ${enabled ? 'Enabled' : 'Disabled (Offline Mode Active)'}.`;
        setTimeout(() => alertEl.classList.add('hidden'), 3000);
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-4 rounded-xl font-semibold border text-sm bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Failed to update system setting.';
      }
    }
  </script>
</body>
</html>
