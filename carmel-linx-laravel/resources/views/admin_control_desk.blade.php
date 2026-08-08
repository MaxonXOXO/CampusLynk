<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Admin Control Desk</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    html {
      font-size: 90%;
    }
    /* Universal typography fix to avoid screen text spreading/bleeding on super bold weights */
    .font-extrabold, .font-black {
      font-weight: 700 !important;
    }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scrollbar-hidden::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hidden {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.3);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(99, 102, 241, 0.3);
      border-radius: 99px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: rgba(99, 102, 241, 0.5);
    }
  </style>

  <style>
    /* Universal Typography & Card Styles standard overrides */
    .font-extrabold, .font-black {
      font-weight: 700 !important;
    }
    input, select, textarea {
      font-size: 0.875rem !important; /* 14px (text-sm) minimum */
    }
    .text-lg {
      font-size: 1.05rem !important;
    }
    .text-base {
      font-size: 0.875rem !important;
    }
    nav.space-y-1\.5 > :not([hidden]) ~ :not([hidden]) {
      margin-top: 0.125rem !important;
    }
    nav.space-y-1\.5 a, nav.space-y-1\.5 button {
      padding-top: 0.375rem !important;
      padding-bottom: 0.375rem !important;
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 h-screen flex flex-col md:flex-row overflow-hidden">

  <meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <div class="p-5 border-b border-slate-800/60 flex items-center gap-3">
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg border border-slate-800/60">
      <div>
        <h2 class="font-black tracking-tight leading-tight" style="font-size:1.1rem;background:linear-gradient(to right,#60a5fa,#38bdf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Carmel Linx</h2>
        <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Control Desk</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <div class="relative group shrink-0">
        <div id="staffAvatarWrapper" class="w-11 h-11 rounded-full overflow-hidden border border-slate-700 bg-slate-800 flex items-center justify-center shadow-inner relative">
          <img id="sidebarStaffImg" src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-full h-full object-cover">
        </div>
        <label for="staffPhotoUploadInput" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer rounded-full text-white text-sm font-bold text-center p-0.5">
          <span class="material-symbols-rounded text-sm">photo_camera</span>
        </label>
        <input type="file" id="staffPhotoUploadInput" accept="image/*" class="hidden" onchange="handleStaffPhotoUpload(event)">
      </div>
      <div class="overflow-hidden">
        <span class="font-bold text-sm block truncate text-slate-200">{{ session('userName') }}</span>
        <span class="text-xs font-bold text-blue-400 block uppercase tracking-wider">{{ str_replace('_', ' ', session('userRole')) }}</span>
        <div id="staffPhotoUploadStatus" class="text-sm font-bold text-green-400 hidden"></div>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-3 space-y-1">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 text-xs">
        <span class="material-symbols-rounded text-base">dashboard</span> Dashboard Overview
      </button>
      <button id="navDirectory" onclick="switchPanel('directory')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">group</span> User Directory
      </button>
      <button id="navBackups" onclick="switchPanel('backups')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">database</span> Drive Backups
      </button>
      <button id="navAudit" onclick="switchPanel('audit')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">receipt_long</span> Audit Trail
      </button>
      <button id="navSettings" onclick="switchPanel('settings')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">settings</span> System Settings
      </button>

      @if(session('userRole') === 'Super_Admin')
      <a href="/superadmin/show-users" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-emerald-400 hover:bg-emerald-950/40 hover:text-emerald-300 cursor-pointer no-underline block text-xs border border-emerald-500/20 bg-emerald-500/5">
         <span class="material-symbols-rounded text-base">key</span> User Credentials Table
      </a>
      @endif

      <a href="/staff/professional-activities" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-indigo-400 hover:bg-indigo-900/30 hover:text-indigo-300 cursor-pointer no-underline block text-xs">
         <span class="material-symbols-rounded text-base">school</span> Professional Activities
      </a>

      <a href="/staff/leave/reports" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-sky-400 hover:bg-sky-900/30 hover:text-sky-300 cursor-pointer no-underline block text-xs">
         <span class="material-symbols-rounded text-base">event_note</span> All-Dept Master Leave Ledger
      </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80 space-y-2.5">
      <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to sign out of SuperAdmin Control Desk?')" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium text-sm">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>

      <!-- Support Badge -->
      <div onclick="openStaffSupportModal()" class="p-2 bg-slate-950/60 hover:bg-slate-900 border border-slate-800/80 rounded-xl text-center select-none cursor-pointer transition-premium" title="Click to Request Remote Support Assist">
        <div class="flex items-center justify-center gap-1 text-[9px] font-bold text-slate-400 uppercase tracking-wider">
          <span class="material-symbols-rounded text-xs text-blue-400">headset_mic</span> Live Assist
        </div>
        <div class="text-[11px] font-black text-slate-200 mt-0.5">Dhanush.A</div>
        <div class="text-[9px] text-slate-400 font-medium">Dept. of Electronics</div>
      </div>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <div class="flex items-center gap-3 md:gap-4">
        <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Dashboard Overview</h1>
        
        <!-- AI System Status Badge -->
        <div id="topAiStatusBadge" onclick="switchPanel('settings')" class="hidden items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800/90 text-slate-300 border border-slate-700 transition-all cursor-pointer group" title="Click to manage AI System Settings">
          <span id="topAiStatusDot" class="w-2 h-2 rounded-full bg-emerald-400"></span>
          <span id="topAiStatusText">AI Active</span>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button onclick="toggleAdminSupportDeskDrawer()" class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-blue-500/40 bg-blue-500/10 hover:bg-blue-500/20 text-blue-300 font-bold text-xs transition-premium cursor-pointer shadow-md" title="Click to open Live Remote Support Desk">
          <span class="material-symbols-rounded text-base text-blue-400">desktop_windows</span>
          <span>Support Desk</span>
          <span id="adminPendingSupportBadge" class="hidden px-1.5 py-0.2 bg-rose-600 text-white rounded-full text-[10px] font-black animate-pulse">0</span>
        </button>
        <div id="loadingIndicator" class="hidden items-center gap-2 text-xs text-slate-400">
          <div class="w-4 h-4 border-2 border-slate-600 border-t-orange-500 rounded-full animate-spin"></div>
          <span>Syncing...</span>
        </div>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl text-[10px] font-bold transition-premium border text-[10px] text-xs"></div>

      <!-- PANEL 1: DASHBOARD OVERVIEW -->
      <div id="panelDashboard" class="space-y-6">
        
        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
          <!-- Total Staff -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl flex items-center gap-3 shadow-sm hover:border-slate-700 transition">
            <div class="bg-blue-500/10 text-blue-400 p-2.5 rounded-lg shrink-0"><span class="material-symbols-rounded text-xl">badge</span></div>
            <div class="min-w-0">
              <span class="text-xs text-slate-400 uppercase font-extrabold tracking-wider block truncate">Total Staff</span>
              <span id="statTotalStaff" class="font-black text-white text-xl leading-tight block">0</span>
            </div>
          </div>
          <!-- Total Students -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl flex items-center gap-3 shadow-sm hover:border-slate-700 transition">
            <div class="bg-sky-500/10 text-sky-400 p-2.5 rounded-lg shrink-0"><span class="material-symbols-rounded text-xl">school</span></div>
            <div class="min-w-0">
              <span class="text-xs text-slate-400 uppercase font-extrabold tracking-wider block truncate">Total Students</span>
              <span id="statTotalStudents" class="font-black text-white text-xl leading-tight block">0</span>
            </div>
          </div>
          <!-- Pending Approvals -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl flex items-center gap-3 shadow-sm hover:border-slate-700 transition">
            <div class="bg-blue-500/10 text-blue-400 p-2.5 rounded-lg shrink-0"><span class="material-symbols-rounded text-xl">pending_actions</span></div>
            <div class="min-w-0">
              <span class="text-xs text-slate-400 uppercase font-extrabold tracking-wider block truncate">Pending Approvals</span>
              <span id="statPendingApprovals" class="font-black text-white text-xl leading-tight block">0</span>
            </div>
          </div>
          <!-- Classrooms -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl flex items-center gap-3 shadow-sm hover:border-slate-700 transition">
            <div class="bg-sky-500/10 text-sky-400 p-2.5 rounded-lg shrink-0"><span class="material-symbols-rounded text-xl">meeting_room</span></div>
            <div class="min-w-0">
              <span class="text-xs text-slate-400 uppercase font-extrabold tracking-wider block truncate">Classrooms</span>
              <span id="statTotalClassrooms" class="font-black text-white text-xl leading-tight block">0</span>
            </div>
          </div>
        </div>

        <!-- Quick Info Panel -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
            <h3 class="font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-base">
              <span class="material-symbols-rounded text-blue-400 text-lg">admin_panel_settings</span> Control Desk Status
            </h3>
            <p class="text-sm text-slate-400 leading-relaxed">
              Welcome to the unified Administrator desk. As **{{ session('userName') }}**, you hold full execution overrides across the database. You can manage passwords, configure designations, review classroom profiles, and sync live table backups.
            </p>
            <div class="mt-4 flex flex-wrap gap-3">
              <button onclick="switchPanel('directory')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg font-bold text-white transition-premium cursor-pointer text-sm">Manage Directory</button>
              @if(session('userRole') === 'Super_Admin')
              <a href="/superadmin/show-users" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 rounded-lg font-bold text-white transition-premium cursor-pointer text-sm no-underline flex items-center gap-1.5">
                <span class="material-symbols-rounded text-base">key</span> Show Users Table View
              </a>
              @endif
              <button onclick="switchPanel('backups')" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg font-bold text-slate-300 transition-premium cursor-pointer text-sm">Backup Database</button>
            </div>
          </div>

          <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl flex flex-col justify-between">
            <div>
              <h3 class="font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-base">
                <span class="material-symbols-rounded text-blue-400 text-lg">info</span> Quick Guidelines
              </h3>
              <ul class="text-sm text-slate-400 space-y-2 list-disc pl-4 leading-relaxed">
                <li>Designations dictate role access: Changing a Lecturer's role to **HOD** promotes them to department supervisor.</li>
                <li>Single Active Principal policy is automatically enforced.</li>
                <li>New staff registrations must be manually **Approved** before they can sign in.</li>
              </ul>
            </div>
          </div>
        </div>



        @if(session('userRole') === 'Principal' || session('userRole') === 'Super_Admin')
        <!-- Department HOD Console Override Links -->
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
          <h3 class="text-sm font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-400 text-lg">admin_panel_settings</span> Department HOD Dashboard Overrides
          </h3>
          <p class="text-sm text-slate-400 mb-4 leading-relaxed">
            Directly access and supervise the HOD Dashboard for any department. This allows you to manage batch allocations, staff mapping, and curriculum updates for that branch.
          </p>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="/dashboard/principal/department/EL" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-amber-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-amber-500 group-hover:scale-110 transition-premium">settings_input_component</span>
              <span class="font-bold text-lg text-slate-200">Electronics</span>
              <span class="text-sm text-slate-400">EL Department</span>
            </a>
            <a href="/dashboard/principal/department/ME" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-emerald-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-emerald-500 group-hover:scale-110 transition-premium">precision_manufacturing</span>
              <span class="font-bold text-lg text-slate-200">Mechanical</span>
              <span class="text-sm text-slate-400">ME Department</span>
            </a>
            <a href="/dashboard/principal/department/CE" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-pink-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-pink-500 group-hover:scale-110 transition-premium">domain</span>
              <span class="font-bold text-lg text-slate-200">Civil</span>
              <span class="text-sm text-slate-400">CE Department</span>
            </a>
            <a href="/dashboard/principal/department/EEE" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-rose-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-rose-500 group-hover:scale-110 transition-premium">bolt</span>
              <span class="font-bold text-lg text-slate-200">Electrical</span>
              <span class="text-sm text-slate-400">EEE Department</span>
            </a>
            <a href="/dashboard/principal/department/CT" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-purple-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-purple-500 group-hover:scale-110 transition-premium">computer</span>
              <span class="font-bold text-lg text-slate-200">Computer</span>
              <span class="text-sm text-slate-400">CT Department</span>
            </a>
            <a href="/dashboard/principal/department/AU" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-indigo-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-indigo-500 group-hover:scale-110 transition-premium">directions_car</span>
              <span class="font-bold text-lg text-slate-200">Automobile</span>
              <span class="text-sm text-slate-400">AU Department</span>
            </a>
            <a href="/dashboard/principal/department/GEN_AIDED" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-teal-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-teal-500 group-hover:scale-110 transition-premium">calculate</span>
              <span class="font-bold text-lg text-slate-200">General Aided</span>
              <span class="text-sm text-slate-400">Gen (Aided)</span>
            </a>
            <a href="/dashboard/principal/department/GEN_SF" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-cyan-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-cyan-500 group-hover:scale-110 transition-premium">functions</span>
              <span class="font-bold text-lg text-slate-200">General SF</span>
              <span class="text-sm text-slate-400">Gen (SF)</span>
            </a>
          </div>
        </div>
        @endif
      </div>

      <!-- PANEL 2: USER DIRECTORY -->
      <div id="panelDirectory" class="hidden space-y-6">
        
        <!-- Directory Header -->
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-base font-bold text-slate-200">Registered Accounts</h3>
            <p class="text-sm text-slate-400 mt-0.5">Filter, search, audit, and manage profile lifecycle states.</p>
          </div>
          <button onclick="openRegisterModal()" class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-blue-500/10 text-sm">
            <span class="material-symbols-rounded text-sm">person_add</span> Register User
          </button>
        </div>

        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Search input -->
          <div>
            <label class="block text-sm text-slate-450 font-bold uppercase tracking-wider mb-1.5">Search User</label>
            <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Name, Register No, Mobile...">
          </div>
          <!-- Branch select -->
          <div>
            <label class="block text-sm text-slate-450 font-bold uppercase tracking-wider mb-1.5">Branch Code</label>
            <select id="filterBranch" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
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
          <!-- Role filter -->
          <div>
            <label class="block text-sm text-slate-450 font-bold uppercase tracking-wider mb-1.5">Designation / Role</label>
            <select id="filterRole" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
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
          <!-- Status select -->
          <div>
            <label class="block text-sm text-slate-450 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="filterStatus" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-sm text-white focus:border-blue-500 outline-none">
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
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-450 font-bold">
                  <th class="p-2.5 md:p-3">Profile</th>
                  <th class="p-2.5 md:p-3">Mobile / Reg No</th>
                  <th class="p-2.5 md:p-3">Branch</th>
                  <th class="p-2.5 md:p-3">Role Designation</th>
                  <th class="p-2.5 md:p-3">Account Status</th>
                  <th class="p-2.5 md:p-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <!-- User rows render dynamically via JS -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 3: DRIVE BACKUPS -->
      <div id="panelBackups" class="hidden space-y-6">
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl max-w-xl mx-auto space-y-5">
          <div class="text-center space-y-2">
            <span class="material-symbols-rounded text-blue-500 text-5xl">cloud_upload</span>
            <h3 class="font-black text-slate-200 text-lg">Google Drive Sync Desk</h3>
            <p class="text-[10px] text-slate-400 leading-relaxed text-[10px] text-xs">
              Compile a complete `.sql` schema and table rows database dump to save locally and sync immediately to your Google Drive backup folder.
            </p>
          </div>

          <div class="border-t border-slate-800/60 pt-4 space-y-3">
            <div class="flex justify-between items-center text-[10px] border-b border-slate-800/30 pb-3 text-[10px] text-xs">
              <span class="text-slate-400 font-medium">MySQL Connection</span>
              <span class="font-bold text-green-400">127.0.0.1 (Active)</span>
            </div>
            <div class="flex justify-between items-center text-[10px] border-b border-slate-800/30 pb-3 text-[10px] text-xs">
              <span class="text-slate-400 font-medium">Backup Target Database</span>
              <span class="font-bold text-slate-200">carmel_linx_db</span>
            </div>
            <div class="flex justify-between items-center text-[10px] pb-1 text-[10px] text-xs">
              <span class="text-slate-400 font-medium">Drive backup target ID</span>
              <span class="font-bold text-slate-400 truncate max-w-[200px]" title="{{ env('GOOGLE_DRIVE_FOLDER_ID') ?: 'Not configured' }}">
                {{ env('GOOGLE_DRIVE_FOLDER_ID') ?: 'Not configured' }}
              </span>
            </div>
          </div>

          <div id="backupAlert" class="hidden p-4 rounded-xl text-[10px] font-bold transition-premium border text-[10px] text-xs"></div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <a href="/api/system/backup/download" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-bold transition-premium flex items-center justify-center gap-2 text-xs no-underline">
              <span class="material-symbols-rounded text-base text-emerald-400">download</span>
              <span>Download Instant SQL File</span>
            </a>

            <button id="btnTriggerBackup" onclick="runBackup()" class="w-full py-3 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl font-bold transition-premium flex items-center justify-center gap-2 shadow-lg shadow-blue-500/15 cursor-pointer text-xs">
              <span id="btnBackupText">Initialize Drive Sync</span>
              <div id="backupSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
            </button>
          </div>
        </div>
      </div>

      <!-- PANEL 4: AUDIT TRAIL -->
      <div id="panelAudit" class="hidden space-y-6">
        <!-- Audit Logs Controls -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-[10px] text-sm">System Audit Trail</h3>
            <p class="text-[10px] text-slate-400 mt-1 text-[10px] text-xs">Lifecycle events, password resets, status changes, and registration records.</p>
          </div>
          <button onclick="loadAuditTrail()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold transition-premium cursor-pointer flex items-center gap-2">
            <span class="material-symbols-rounded text-[10px] text-sm">sync</span> Refresh Log
          </button>
        </div>

        <!-- Audit Table -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-4">Timestamp</th>
                  <th class="p-4">Actor</th>
                  <th class="p-4">Target User (ID)</th>
                  <th class="p-4">Action</th>
                  <th class="p-4">IP Address</th>
                  <th class="p-4">Details</th>
                </tr>
              </thead>
              <tbody id="auditTableBody">
                <!-- Audit logs render dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 5: SYSTEM SETTINGS -->
      <div id="panelSettings" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">System Settings &amp; API Controls</h3>
            <p class="text-xs text-slate-400 mt-1">Configure global API integrations, AI credits saving switches, and local fallbacks.</p>
          </div>
          <button onclick="switchPanel('dashboard')" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl font-bold text-xs transition flex items-center gap-1.5 cursor-pointer border border-slate-700">
            <span class="material-symbols-rounded text-sm">arrow_back</span> Back to Dashboard
          </button>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6 space-y-6">
          <div class="flex items-center justify-between p-4 bg-slate-900/40 border border-slate-800/60 rounded-xl">
            <div class="space-y-1 pr-4">
              <h4 class="font-bold text-slate-200 text-sm flex items-center gap-2">
                <span class="material-symbols-rounded text-indigo-400 text-lg">auto_awesome</span> Gemini AI Generation
              </h4>
              <p class="text-xs text-slate-400 leading-relaxed">
                Toggle Gemini 2.5 Flash AI integration across the portal. When deactivated (Offline Mode), all syllabus planners, MCQs, and question generation operations will read strictly from local databases and question banks to save API credit costs.
              </p>
            </div>
            <div class="shrink-0 flex items-center">
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" id="settingAiEnabled" class="sr-only peer" onchange="saveSystemSettings()">
                <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-300 after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
              </label>
            </div>
          </div>
          
          <div id="settingsSaveAlert" class="hidden p-3 rounded-xl font-bold border text-sm"></div>
        </div>
      </div>

    </div>
  </main>

  <!-- EDIT STAFF MODAL -->
  <div id="editStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">edit</span> Edit Staff Details
        </h3>
        <button onclick="closeEditStaffModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="editStaffForm" onsubmit="submitStaffEdit(event)" class="space-y-4">
        <input type="hidden" id="editStaffMobile">
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Full Name</label>
          <input type="text" id="editStaffName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-blue-500 text-sm">
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Email Address</label>
          <input type="email" id="editStaffEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-blue-500 text-sm">
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Department Branch</label>
          <select id="editStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-blue-500 text-sm">
            <option value="EL">Electronics Engineering (EL)</option>
            <option value="ME">Mechanical Engineering (ME)</option>
            <option value="CE">Civil Engineering (CE)</option>
            <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
            <option value="CT">Computer Engineering (CT)</option>
            <option value="AU">Automobile Engineering (AU)</option>
            <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
            <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
            <option value="Admin">Administration</option>
          </select>
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Designation Role</label>
          <select id="editStaffDesig" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-blue-500 text-sm">
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

        <div id="editStaffAlert" class="hidden p-3 rounded-xl font-bold border text-sm"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeEditStaffModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-sm">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition-premium cursor-pointer text-sm flex items-center justify-center gap-1.5">
            <span>Save Details</span>
            <div id="editStaffSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-[10px] flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-blue-400 text-lg">lock_reset</span> Password Reset
        </h3>
        <button onclick="closePasswordModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-[10px] text-slate-400 text-[10px] text-xs">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-200"></span> (<span id="pwdResetId" class="text-blue-400 font-mono"></span>).
        </p>
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-[10px] text-xs" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl text-[10px] font-bold border text-[10px] text-xs"></div>

      <div class="flex gap-3 pt-2">
        <button onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Cancel</button>
        <button onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[10px] transition-premium cursor-pointer text-[10px] text-xs">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE PROFILE -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-[10px] flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-blue-400 text-lg">receipt_long</span> Profile Audit Trail
        </h3>
        <button onclick="closeAuditModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-[10px] text-slate-400 text-[10px] text-xs">
          History log for <span id="auditProfileName" class="font-bold text-slate-200"></span> (<span id="auditProfileId" class="text-blue-400 font-mono"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl">
          <table class="w-full text-left text-[10px] border-collapse text-[10px] text-xs">
            <thead>
              <tr class="bg-slate-950/80 border-b border-slate-800 text-slate-400 font-bold">
                <th class="p-3">Time</th>
                <th class="p-3">Actor</th>
                <th class="p-3">Action</th>
                <th class="p-3">Details</th>
              </tr>
            </thead>
            <tbody id="modalAuditTableBody">
              <!-- Rendered via JS -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex pt-2">
        <button onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Close Window</button>
      </div>
    </div>
  </div>

  <!-- DIRECT REGISTRATION MODAL -->
  <div id="registerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-[10px] flex items-center gap-2 text-sm">
          <span class="material-symbols-rounded text-blue-400 text-lg">person_add</span> Register New Profile
        </h3>
        <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="directRegisterForm" onsubmit="handleDirectRegister(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <!-- Type Selection -->
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">User Type</label>
          <select id="regType" onchange="toggleDirectRegisterFields(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
            <option value="student">Student Profile</option>
            <option value="staff">Staff Profile</option>
          </select>
        </div>

        <!-- Common Fields -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="directRegName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="directRegEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>

        <!-- Student-Specific Fields -->
        <div id="directStudentFields" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Register No</label>
              <input type="text" id="directRegStudentId" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="e.g. 25EL1001">
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Admission No</label>
              <input type="text" id="directRegStudentAdm" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="e.g. ADM25EL01">
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
              <select id="directRegStudentBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
                <option value="EL">EL</option>
                <option value="ME">ME</option>
                <option value="CE">CE</option>
                <option value="EEE">EEE</option>
                <option value="CT">CT</option>
                <option value="AU">AU</option>
              </select>
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Adm Year</label>
              <input type="number" id="directRegStudentYear" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" value="2026">
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Semester</label>
              <select id="directRegStudentSem" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
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

        <!-- Staff-Specific Fields -->
        <div id="directStaffFields" class="space-y-4 hidden">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
              <input type="text" id="directRegStaffMobile" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="10-digit number">
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Designation</label>
              <select id="directRegStaffDesig" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
                <option value="HOD">Head of Department (HOD)</option>
                <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
                <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
                <option value="Lecturer" selected>Lecturer</option>
                <option value="Demonstrator">Demonstrator</option>
                <option value="Physical_Instructor">Physical Instructor</option>
                <option value="Trade_Instructor">Trade Instructor</option>
                <option value="Tradesman">Tradesman</option>
                <option value="Laboratory_Assistant">Laboratory Assistant</option>
                <option value="Workshop_Instructor">Workshop Instructor</option>
                <option value="Workshop_Superintendent">Workshop Superintendent</option>
                <option value="Principal">Principal</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
            <select id="directRegStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs">
              <option value="EL">Electronics Engineering (EL)</option>
              <option value="ME">Mechanical Engineering (ME)</option>
              <option value="CE">Civil Engineering (CE)</option>
              <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
              <option value="CT">Computer Engineering (CT)</option>
              <option value="AU">Automobile Engineering (AU)</option>
              <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
              <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
              <option value="Admin">Administration</option>
            </select>
          </div>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Password</label>
          <input type="text" id="directRegPassword" required class="w-full bg-slate-955 border border-slate-800 rounded-xl px-3 py-2 text-[10px] text-white focus:border-blue-500 outline-none text-[10px] text-xs" placeholder="e.g. 12345">
        </div>

        <div id="directRegAlert" class="hidden p-3 rounded-xl text-[10px] font-bold border text-[10px] text-xs"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-[10px] text-slate-300 transition-premium cursor-pointer text-[10px] text-xs">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[10px] transition-premium cursor-pointer flex items-center justify-center gap-1.5 text-[10px] text-xs">
            <span>Register Profile</span>
            <div id="directRegSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let activePanel = "dashboard";
    let selectedUserForReset = null;

    // Load initial data on mount
    document.addEventListener("DOMContentLoaded", () => {
      loadStats();
      loadUsers();
      loadSettings(); // Loads AI generation status immediately for top header badge
      if (activePanel === 'audit') loadAuditTrail();
    });



    // CSRF Token Helper
    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    // Switch view panel
    function switchPanel(panelId) {
      activePanel = panelId;
      
      const panels = ['dashboard', 'directory', 'backups', 'audit', 'settings'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      // Update Header Title
      const titles = {
        'dashboard': 'Dashboard Overview',
        'directory': 'User Accounts Directory',
        'backups': 'Database Sync & Backup',
        'audit': 'System Audit Trail',
        'settings': 'System Settings & Controls'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'dashboard') loadStats();
      if (panelId === 'directory') loadUsers();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'settings') loadSettings();
    }

    // Update top header AI status badge
    function updateAiStatusBadge(enabled) {
      const badge = document.getElementById('topAiStatusBadge');
      const dot = document.getElementById('topAiStatusDot');
      const text = document.getElementById('topAiStatusText');
      const checkbox = document.getElementById('settingAiEnabled');

      if (checkbox) checkbox.checked = !!enabled;
      if (!badge || !text) return;

      badge.classList.remove('hidden');
      badge.classList.add('flex');

      if (enabled) {
        badge.className = "flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800/90 text-slate-300 border border-slate-700 transition-all cursor-pointer group";
        if (dot) dot.className = "w-2 h-2 rounded-full bg-emerald-400";
        text.innerText = "AI Active";
      } else {
        badge.className = "flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800/90 text-slate-400 border border-slate-700 transition-all cursor-pointer group";
        if (dot) dot.className = "w-2 h-2 rounded-full bg-slate-500";
        text.innerText = "AI Off";
      }
    }

    // Load settings from backend
    function loadSettings() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch('/api/admin/settings')
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            const isEnabled = !!data.settings.ai_generation_enabled;
            updateAiStatusBadge(isEnabled);
          }
        })
        .catch(() => {
          if (indicator) indicator.classList.add('hidden');
        });
    }

    // Save settings to backend
    function saveSystemSettings() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');
      
      const aiEnabled = document.getElementById('settingAiEnabled').checked;
      const alert = document.getElementById('settingsSaveAlert');
      if (alert) alert.classList.add('hidden');

      fetch('/api/admin/settings', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ ai_generation_enabled: aiEnabled })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          updateAiStatusBadge(aiEnabled);
          if (alert) {
            alert.className = "p-3 rounded-xl bg-green-950/40 text-green-400 border border-green-900/60 block text-xs font-bold";
            alert.innerText = data.message;
            alert.classList.remove('hidden');
            setTimeout(() => alert.classList.add('hidden'), 3000);
          }
        } else {
          if (alert) {
            alert.className = "p-3 rounded-xl bg-red-950/40 text-red-400 border border-red-900/60 block text-xs font-bold";
            alert.innerText = data.message;
            alert.classList.remove('hidden');
          }
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
        if (alert) {
          alert.className = "p-3 rounded-xl bg-red-950/40 text-red-400 border border-red-900/60 block text-xs font-bold";
          alert.innerText = "Failed to save settings.";
          alert.classList.remove('hidden');
        }
      });
    }

    // Display messages
    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border-red-900 block shadow-sm";
      } else {
        alert.className = "p-4 rounded-xl text-[10px] font-bold bg-green-950/40 text-green-400 border-green-900 block shadow-sm";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    // Load Stats
    function loadStats() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/admin/stats')
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            document.getElementById('statTotalStaff').innerText = data.stats.totalStaff;
            document.getElementById('statTotalStudents').innerText = data.stats.totalStudents;
            document.getElementById('statPendingApprovals').innerText = data.stats.pendingApprovals;
            document.getElementById('statTotalClassrooms').innerText = data.stats.totalClassrooms;
          }
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    // Load Users
    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      const search = document.getElementById('filterSearch').value;
      const branch = document.getElementById('filterBranch').value;
      const role = document.getElementById('filterRole').value;
      const status = document.getElementById('filterStatus').value;

      const url = `/api/admin/users?search=${encodeURIComponent(search)}&branch=${branch}&role=${role}&status=${status}`;

      fetch(url)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            renderUsersGrid(data.users);
          }
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    // Render table rows
    function renderUsersGrid(users) {
      const tbody = document.getElementById('usersTableBody');
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="6" class="p-8 text-center text-slate-500 font-medium font-sans">
              No matching registered profiles found.
            </td>
          </tr>
        `;
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";

        // Status Badge Styling
        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-sm font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
        if (user.status === 'Approved') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-sm font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
        } else if (user.status === 'Suspended') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-sm font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;
        }

        // Action Options depending on current status
        let toggleButton = '';
        if (user.status === 'Pending') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2 py-1 bg-green-600 hover:bg-green-700 rounded-lg text-xs font-bold text-white transition-premium cursor-pointer">
              Approve
            </button>
          `;
        } else if (user.status === 'Approved') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Suspended')" class="px-2 py-1 bg-red-950 hover:bg-red-900 border border-red-800 rounded-lg text-xs font-bold text-red-300 transition-premium cursor-pointer">
              Suspend
            </button>
          `;
        } else if (user.status === 'Suspended') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 rounded-lg text-xs font-bold text-white transition-premium cursor-pointer">
              Activate
            </button>
          `;
        }

        // Role Designation selector (for Staff members only)
        let roleCol = `<span class="text-xs font-bold text-slate-300">${user.role}</span>`;
        if (user.type === 'staff') {
          roleCol = `
            <select onchange="updateDesignation('${user.id}', this.value)" class="bg-slate-900 border border-slate-800 rounded-lg px-2 py-1 text-xs text-white outline-none cursor-pointer max-w-[150px] truncate">
              <option value="Super_Admin" ${user.role === 'Super_Admin' ? 'selected' : ''}>Super Admin</option>
              <option value="Admin" ${user.role === 'Admin' ? 'selected' : ''}>Admin</option>
              <option value="Principal" ${user.role === 'Principal' ? 'selected' : ''}>Principal</option>
              <option value="HOD" ${user.role === 'HOD' ? 'selected' : ''}>HOD</option>
              <option value="Gen_Dept_Coordinator_Aided" ${user.role === 'Gen_Dept_Coordinator_Aided' ? 'selected' : ''}>Gen Dept Coordinator Aided</option>
              <option value="Gen_Dept_Coordinator_Self_Finance" ${user.role === 'Gen_Dept_Coordinator_Self_Finance' ? 'selected' : ''}>Gen Dept Coordinator Self Finance</option>
              <option value="Tutor" ${user.role === 'Tutor' ? 'selected' : ''}>Tutor</option>
              <option value="Lecturer" ${user.role === 'Lecturer' ? 'selected' : ''}>Lecturer</option>
              <option value="Demonstrator" ${user.role === 'Demonstrator' ? 'selected' : ''}>Demonstrator</option>
              <option value="Physical_Instructor" ${user.role === 'Physical_Instructor' || user.role === 'Physical Instructor' ? 'selected' : ''}>Physical Instructor</option>
              <option value="Trade_Instructor" ${user.role === 'Trade_Instructor' ? 'selected' : ''}>Trade Instructor</option>
              <option value="Tradesman" ${user.role === 'Tradesman' ? 'selected' : ''}>Tradesman</option>
              <option value="Laboratory_Assistant" ${user.role === 'Laboratory_Assistant' ? 'selected' : ''}>Laboratory Assistant</option>
              <option value="Workshop_Instructor" ${user.role === 'Workshop_Instructor' ? 'selected' : ''}>Workshop Instructor</option>
              <option value="Workshop_Superintendent" ${user.role === 'Workshop_Superintendent' ? 'selected' : ''}>Workshop Superintendent</option>
            </select>
          `;
        }

        let idColumnHtml = `<span class="font-mono font-bold text-slate-300 text-xs">${user.id}</span>`;
        if (user.type === 'staff') {
          idColumnHtml = `
            <a href="javascript:void(0)" 
               onclick="openEditStaffModal('${user.id}', '${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}', '${user.branch}', '${user.role}')" 
               class="text-blue-400 hover:text-blue-300 underline font-mono font-bold text-xs transition-premium" 
               title="Modify details for ${user.name}">
              ${user.id}
            </a>
          `;
        }

        tr.innerHTML = `
          <td class="p-2.5 md:p-3 flex items-center gap-2.5">
            <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-7 h-7 rounded-full object-cover border border-slate-800 shadow shrink-0">
            <div class="min-w-0 overflow-hidden">
              <span class="font-bold text-slate-100 block text-xs md:text-sm truncate max-w-[140px] lg:max-w-[180px]">${user.name}</span>
              <span class="text-[11px] text-slate-500 block truncate max-w-[140px] lg:max-w-[180px]">${user.email}</span>
            </div>
          </td>
          <td class="p-2.5 md:p-3 font-mono text-xs md:text-sm shrink-0">${idColumnHtml}</td>
          <td class="p-2.5 md:p-3"><span class="font-bold font-mono text-xs bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
          <td class="p-2.5 md:p-3">${roleCol}</td>
          <td class="p-2.5 md:p-3">${statusBadge}</td>
          <td class="p-2.5 md:p-3 text-right">
            <div class="flex items-center justify-end gap-1">
              ${toggleButton}
              <button onclick="triggerPasswordReset('${user.id}', '${user.type}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold transition-premium cursor-pointer">
                Reset Pwd
              </button>
              <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-blue-900 border border-slate-800 text-slate-300 rounded-lg text-xs font-bold transition-premium cursor-pointer" title="View Audit Trail">
                Audit
              </button>
              <button onclick="confirmDeleteUser('${user.id}', '${user.type}', '${user.name}')" class="px-2 py-1 bg-red-950/40 hover:bg-red-900 border border-red-900/60 text-red-400 rounded-lg text-xs font-bold transition-premium cursor-pointer" title="Delete User">
                Delete
              </button>
            </div>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    // Toggle User Status AJAX
    function changeStatus(userId, userType, newStatus) {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/admin/user/toggle-status', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ userId, userType, newStatus })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('User status updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => {
        indicator.classList.add('hidden');
        showGlobalMessage('Failed to update status.', true);
      });
    }

    // Update Designation AJAX
    function updateDesignation(userId, newRole) {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/admin/user/change-role', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ userId, newRole })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Staff designation promoted successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => {
        indicator.classList.add('hidden');
        showGlobalMessage('Failed to change staff designation.', true);
      });
    }

    // Password reset modal triggers
    function triggerPasswordReset(userId, userType, userName) {
      selectedUserForReset = { userId, userType };
      document.getElementById('pwdResetName').innerText = userName;
      document.getElementById('pwdResetId').innerText = userId;
      document.getElementById('newPasswordInput').value = "";
      document.getElementById('pwdAlert').classList.add('hidden');
      
      const modal = document.getElementById('passwordModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closePasswordModal() {
      const modal = document.getElementById('passwordModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      selectedUserForReset = null;
    }

    // Edit Staff Modal JS handlers
    function openEditStaffModal(mobileNo, name, email, branch, designation) {
      document.getElementById('editStaffMobile').value = mobileNo;
      document.getElementById('editStaffName').value = name;
      document.getElementById('editStaffEmail').value = email;
      document.getElementById('editStaffBranch').value = branch;
      document.getElementById('editStaffDesig').value = designation;
      document.getElementById('editStaffAlert').classList.add('hidden');

      const modal = document.getElementById('editStaffModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeEditStaffModal() {
      const modal = document.getElementById('editStaffModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function submitStaffEdit(e) {
      e.preventDefault();
      const mobileNo = document.getElementById('editStaffMobile').value;
      const name = document.getElementById('editStaffName').value.trim();
      const email = document.getElementById('editStaffEmail').value.trim();
      const branch = document.getElementById('editStaffBranch').value;
      const designation = document.getElementById('editStaffDesig').value;

      const alert = document.getElementById('editStaffAlert');
      const spinner = document.getElementById('editStaffSpinner');

      alert.classList.add('hidden');
      spinner.classList.remove('hidden');

      fetch(`/api/admin/user/update-staff/${mobileNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ name, email, branch, designation })
      })
      .then(res => res.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl bg-green-950/40 text-green-400 border border-green-900/60 block text-sm";
          alert.innerText = "Staff profile updated successfully!";
          alert.classList.remove('hidden');
          setTimeout(() => {
            closeEditStaffModal();
            loadUsers();
          }, 1000);
        } else {
          alert.className = "p-3 rounded-xl bg-red-950/40 text-red-400 border border-red-900/60 block text-sm";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl bg-red-950/40 text-red-400 border border-red-900/60 block text-sm";
        alert.innerText = "Connection error. Request failed.";
        alert.classList.remove('hidden');
      });
    }

    function submitPasswordReset() {
      const pwd = document.getElementById('newPasswordInput').value.trim();
      const pwdAlert = document.getElementById('pwdAlert');
      
      if (pwd.length < 4) {
        pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Password must be at least 4 characters long.";
        pwdAlert.classList.remove('hidden');
        return;
      }

      fetch('/api/admin/user/reset-password', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          userId: selectedUserForReset.userId,
          userType: selectedUserForReset.userType,
          newPassword: pwd
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Password reset successfully.');
          closePasswordModal();
        } else {
          pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          pwdAlert.innerText = data.message;
          pwdAlert.classList.remove('hidden');
        }
      })
      .catch(() => {
        pwdAlert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Request failed.";
        pwdAlert.classList.remove('hidden');
      });
    }

    // Google Drive Backup AJAX
    function runBackup() {
      const btn = document.getElementById('btnTriggerBackup');
      const spinner = document.getElementById('backupSpinner');
      const text = document.getElementById('btnBackupText');
      const alert = document.getElementById('backupAlert');

      btn.disabled = true;
      spinner.classList.remove('hidden');
      text.innerText = "Syncing SQL dump to Google Drive...";
      alert.classList.add('hidden');

      fetch('/api/system/backup', {
        method: 'POST',
        headers: getHeaders()
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        spinner.classList.add('hidden');
        text.innerText = "Initialize Google Drive Backup";
        
        if (data.status === 'SUCCESS') {
          alert.className = "p-4 rounded-xl text-[10px] font-bold bg-green-950/40 text-green-400 border border-green-900 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        } else {
          alert.className = "p-4 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        btn.disabled = false;
        spinner.classList.add('hidden');
        text.innerText = "Initialize Google Drive Backup";
        alert.className = "p-4 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        alert.innerText = "Google Drive backup failed. Verify API configuration keys.";
        alert.classList.remove('hidden');
      });
    }

    // Load Global Audit Trail
    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">Querying audit logs...</td></tr>`;

      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">No system audit logs found.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";
              
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4 font-bold text-slate-300">${log.performed_by_name || 'System'}<br><span class="text-[10px] text-slate-500 font-mono">${log.performed_by || ''}</span></td>
                <td class="p-4 font-bold text-white">${log.target_name}<br><span class="text-[10px] text-blue-400 font-mono">${log.target_id}</span></td>
                <td class="p-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 font-mono text-slate-400">${log.ip_address || '-'}</td>
                <td class="p-4 text-slate-300 font-sans leading-relaxed">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Error loading logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Request failed.</td></tr>`;
        });
    }

    // View Audit Log Modal for Single Profile
    function viewUserAudit(userId, userName) {
      document.getElementById('auditProfileName').innerText = userName;
      document.getElementById('auditProfileId').innerText = userId;
      
      const tbody = document.getElementById('modalAuditTableBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-500">Retrieving profile logs...</td></tr>`;

      const modal = document.getElementById('auditModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      fetch(`/api/audit-logs?targetId=${userId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-500">No profile history events found.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-[10px]";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3 font-semibold text-slate-300">${log.performed_by_name || 'System'}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Error loading.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Failed.</td></tr>`;
        });
    }

    function closeAuditModal() {
      const modal = document.getElementById('auditModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    // Confirm Delete User
    function confirmDeleteUser(userId, userType, userName) {
      if (confirm(`Are you absolutely sure you want to permanently delete the profile of ${userName} (${userId})? This action will remove all database credentials.`)) {
        const indicator = document.getElementById('loadingIndicator');
        indicator.classList.remove('hidden');

        fetch('/api/admin/user/delete', {
          method: 'POST',
          headers: getHeaders(),
          body: JSON.stringify({ targetId: userId, userType })
        })
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            showGlobalMessage('Profile deleted successfully.');
            loadUsers();
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to delete profile.', true);
        });
      }
    }

    // Register User Modals
    function openRegisterModal() {
      document.getElementById('directRegisterForm').reset();
      document.getElementById('directRegAlert').classList.add('hidden');
      toggleDirectRegisterFields('student');
      
      const modal = document.getElementById('registerModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeRegisterModal() {
      const modal = document.getElementById('registerModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function toggleDirectRegisterFields(type) {
      const sFields = document.getElementById('directStudentFields');
      const fFields = document.getElementById('directStaffFields');
      if (type === 'student') {
        sFields.classList.remove('hidden');
        fFields.classList.add('hidden');
      } else {
        fFields.classList.remove('hidden');
        sFields.classList.add('hidden');
      }
    }

    function handleDirectRegister(e) {
      e.preventDefault();
      const alert = document.getElementById('directRegAlert');
      const spinner = document.getElementById('directRegSpinner');
      
      alert.classList.add('hidden');
      spinner.classList.remove('hidden');

      const type = document.getElementById('regType').value;
      const formData = new FormData();
      formData.append('name', document.getElementById('directRegName').value);
      formData.append('email', document.getElementById('directRegEmail').value);
      formData.append('password', document.getElementById('directRegPassword').value);

      let url = '/register/student';
      if (type === 'student') {
        formData.append('regNo', document.getElementById('directRegStudentId').value);
        formData.append('admNo', document.getElementById('directRegStudentAdm').value);
        formData.append('branch', document.getElementById('directRegStudentBranch').value);
        formData.append('admissionYear', document.getElementById('directRegStudentYear').value);
        formData.append('admissionType', 'Regular');
      } else {
        url = '/register/staff';
        formData.append('mobileNo', document.getElementById('directRegStaffMobile').value);
        formData.append('branch', document.getElementById('directRegStaffBranch').value);
        formData.append('designation', document.getElementById('directRegStaffDesig').value);
      }

      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl text-[10px] font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "User registered successfully.";
          alert.classList.remove('hidden');
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
          }, 1500);
        } else {
          alert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Request failed.";
      });
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      statusEl.classList.remove('hidden');
      statusEl.className = "text-sm font-bold mt-2 text-blue-400";
      statusEl.innerText = "Uploading...";

      const formData = new FormData();
      formData.append('photo', file);

      fetch('/api/staff/profile/upload-photo', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          statusEl.className = "text-sm font-bold mt-2 text-green-400";
          statusEl.innerText = "Updated!";

          // Update sidebar picture
          const sidebarImg = document.getElementById('sidebarStaffImg');
          if (sidebarImg) {
            sidebarImg.src = data.photo_url;
          }

          setTimeout(() => statusEl.classList.add('hidden'), 3000);
        } else {
          statusEl.className = "text-sm font-bold mt-2 text-rose-400";
          statusEl.innerText = data.message || "Failed";
        }
      })
      .catch(() => {
        statusEl.className = "text-sm font-bold mt-2 text-rose-450";
        statusEl.innerText = "Error";
      });
    }

    // Prevent back-button viewing after logout (session out)
    window.addEventListener('pageshow', function (event) {
      if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
        window.location.reload(true);
      }
    });
  </script>
  @include('partials.admin_support_desk_window')
  @include('partials.support_desk_overlay')
</body>
</html>
