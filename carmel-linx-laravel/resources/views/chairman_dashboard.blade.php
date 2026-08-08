<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Chairman Control Desk</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    html {
      font-size: 90%;
    }
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
    input, select, textarea {
      font-size: 0.875rem !important;
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
      <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg border border-amber-500/30">
      <div>
        <h2 class="font-black tracking-tight leading-tight" style="font-size:1.1rem;background:linear-gradient(to right,#fbbf24,#f59e0b);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Carmel Linx</h2>
        <span class="text-xs text-amber-400 font-bold uppercase tracking-widest">Chairman Desk</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <div class="relative group shrink-0">
        <div id="staffAvatarWrapper" class="w-11 h-11 rounded-full overflow-hidden border border-amber-500/40 bg-slate-800 flex items-center justify-center shadow-inner relative">
          <img id="sidebarStaffImg" src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150' }}" class="w-full h-full object-cover">
        </div>
        <label for="staffPhotoUploadInput" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer rounded-full text-white text-sm font-bold text-center p-0.5">
          <span class="material-symbols-rounded text-sm">photo_camera</span>
        </label>
        <input type="file" id="staffPhotoUploadInput" accept="image/*" class="hidden" onchange="handleStaffPhotoUpload(event)">
      </div>
      <div class="overflow-hidden">
        <span class="font-bold text-sm block truncate text-slate-100">{{ session('userName', 'Chairman') }}</span>
        <span class="text-xs font-bold text-amber-400 block uppercase tracking-wider">Executive Management</span>
        <div id="staffPhotoUploadStatus" class="text-sm font-bold text-green-400 hidden"></div>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-3 space-y-1">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-2.5 transition-premium bg-amber-500/10 text-amber-400 border-l-2 border-amber-500 text-xs">
        <span class="material-symbols-rounded text-base">dashboard</span> Executive Overview
      </button>
      <button id="navDirectory" onclick="switchPanel('directory')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">group</span> Personnel Directory
      </button>
      <button id="navAudit" onclick="switchPanel('audit')" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs">
        <span class="material-symbols-rounded text-base">receipt_long</span> Audit Trail Log
      </button>

      <a href="/staff/leave/reports" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-sky-400 hover:bg-sky-900/30 hover:text-sky-300 cursor-pointer no-underline block text-xs">
         <span class="material-symbols-rounded text-base">event_note</span> All-Dept Master Leave Ledger
      </a>

      <a href="/staff/professional-activities" class="w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-indigo-400 hover:bg-indigo-900/30 hover:text-indigo-300 cursor-pointer no-underline block text-xs">
         <span class="material-symbols-rounded text-base">school</span> Professional Activities
      </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80 space-y-2.5">
      <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to sign out of Chairman Control Desk?')" class="w-full py-2.5 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium text-sm">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <div class="flex items-center gap-3 md:gap-4">
        <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Executive Overview</h1>
        
        <div class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-300 border border-amber-500/20">
          <span class="w-2 h-2 rounded-full bg-amber-400"></span>
          <span>Chairman Desk Connected</span>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div id="loadingIndicator" class="hidden items-center gap-2 text-xs text-slate-400">
          <div class="w-4 h-4 border-2 border-slate-600 border-t-amber-500 rounded-full animate-spin"></div>
          <span>Syncing...</span>
        </div>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl text-xs font-bold transition-premium border"></div>

      <!-- PANEL 1: DASHBOARD OVERVIEW -->
      <div id="panelDashboard" class="space-y-6">
        
        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
          <!-- Total Staff -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl flex items-center gap-3 shadow-sm hover:border-amber-500/50 transition">
            <div class="bg-amber-500/10 text-amber-400 p-2.5 rounded-lg shrink-0"><span class="material-symbols-rounded text-xl">badge</span></div>
            <div class="min-w-0">
              <span class="text-xs text-slate-400 uppercase font-extrabold tracking-wider block truncate">Total Staff</span>
              <span id="statTotalStaff" class="font-black text-white text-xl leading-tight block">0</span>
            </div>
          </div>
          <!-- Total Students -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl flex items-center gap-3 shadow-sm hover:border-amber-500/50 transition">
            <div class="bg-sky-500/10 text-sky-400 p-2.5 rounded-lg shrink-0"><span class="material-symbols-rounded text-xl">school</span></div>
            <div class="min-w-0">
              <span class="text-xs text-slate-400 uppercase font-extrabold tracking-wider block truncate">Total Students</span>
              <span id="statTotalStudents" class="font-black text-white text-xl leading-tight block">0</span>
            </div>
          </div>
          <!-- Pending Approvals -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl flex items-center gap-3 shadow-sm hover:border-amber-500/50 transition">
            <div class="bg-blue-500/10 text-blue-400 p-2.5 rounded-lg shrink-0"><span class="material-symbols-rounded text-xl">pending_actions</span></div>
            <div class="min-w-0">
              <span class="text-xs text-slate-400 uppercase font-extrabold tracking-wider block truncate">Pending Approvals</span>
              <span id="statPendingApprovals" class="font-black text-white text-xl leading-tight block">0</span>
            </div>
          </div>
          <!-- Classrooms -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-4 rounded-xl flex items-center gap-3 shadow-sm hover:border-amber-500/50 transition">
            <div class="bg-emerald-500/10 text-emerald-400 p-2.5 rounded-lg shrink-0"><span class="material-symbols-rounded text-xl">meeting_room</span></div>
            <div class="min-w-0">
              <span class="text-xs text-slate-400 uppercase font-extrabold tracking-wider block truncate">Classrooms</span>
              <span id="statTotalClassrooms" class="font-black text-white text-xl leading-tight block">0</span>
            </div>
          </div>
        </div>

        <!-- Quick Executive Banner -->
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
          <h3 class="font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-base">
            <span class="material-symbols-rounded text-amber-400 text-lg">admin_panel_settings</span> Executive Oversight Desk
          </h3>
          <p class="text-sm text-slate-400 leading-relaxed">
            Welcome, **{{ session('userName', 'Chairman') }}**. As Chairman, you possess full administrative oversight over all academic departments, staff profiles, student records, and institutional ledgers across Carmel Polytechnic College.
          </p>
          <div class="mt-4 flex flex-wrap gap-3">
            <button onclick="switchPanel('directory')" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 rounded-lg font-bold text-slate-950 transition-premium cursor-pointer text-sm">Personnel Directory</button>
            <a href="/staff/leave/reports" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg font-bold text-slate-200 transition-premium cursor-pointer text-sm no-underline flex items-center gap-1.5">
              <span class="material-symbols-rounded text-base text-sky-400">event_note</span> All-Dept Master Leave Ledger
            </a>
          </div>
        </div>

        <!-- Department HOD Console Supervision Links -->
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
          <h3 class="text-sm font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2">
            <span class="material-symbols-rounded text-amber-400 text-lg">domain</span> Department HOD Console Supervision
          </h3>
          <p class="text-sm text-slate-400 mb-4 leading-relaxed">
            Directly inspect and supervise the HOD Control Desk for any department branch.
          </p>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="/dashboard/principal/department/EL" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-amber-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-amber-500 group-hover:scale-110 transition-premium">settings_input_component</span>
              <span class="font-bold text-lg text-slate-200">Electronics</span>
              <span class="text-xs text-slate-400">EL Department</span>
            </a>
            <a href="/dashboard/principal/department/ME" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-emerald-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-emerald-500 group-hover:scale-110 transition-premium">precision_manufacturing</span>
              <span class="font-bold text-lg text-slate-200">Mechanical</span>
              <span class="text-xs text-slate-400">ME Department</span>
            </a>
            <a href="/dashboard/principal/department/CE" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-pink-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-pink-500 group-hover:scale-110 transition-premium">domain</span>
              <span class="font-bold text-lg text-slate-200">Civil</span>
              <span class="text-xs text-slate-400">CE Department</span>
            </a>
            <a href="/dashboard/principal/department/EEE" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-rose-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-rose-500 group-hover:scale-110 transition-premium">bolt</span>
              <span class="font-bold text-lg text-slate-200">Electrical</span>
              <span class="text-xs text-slate-400">EEE Department</span>
            </a>
            <a href="/dashboard/principal/department/CT" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-purple-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-purple-500 group-hover:scale-110 transition-premium">computer</span>
              <span class="font-bold text-lg text-slate-200">Computer</span>
              <span class="text-xs text-slate-400">CT Department</span>
            </a>
            <a href="/dashboard/principal/department/AU" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-indigo-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-indigo-500 group-hover:scale-110 transition-premium">directions_car</span>
              <span class="font-bold text-lg text-slate-200">Automobile</span>
              <span class="text-xs text-slate-400">AU Department</span>
            </a>
            <a href="/dashboard/principal/department/GEN_AIDED" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-teal-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-teal-500 group-hover:scale-110 transition-premium">calculate</span>
              <span class="font-bold text-lg text-slate-200">General Aided</span>
              <span class="text-xs text-slate-400">Gen (Aided)</span>
            </a>
            <a href="/dashboard/principal/department/GEN_SF" class="no-underline p-4 bg-slate-900/60 border border-slate-800 hover:border-cyan-500 rounded-xl text-center transition-premium group flex flex-col items-center justify-center gap-2 cursor-pointer">
              <span class="material-symbols-rounded text-2xl text-cyan-500 group-hover:scale-110 transition-premium">functions</span>
              <span class="font-bold text-lg text-slate-200">General SF</span>
              <span class="text-xs text-slate-400">Gen (SF)</span>
            </a>
          </div>
        </div>
      </div>

      <!-- PANEL 2: PERSONNEL DIRECTORY -->
      <div id="panelDirectory" class="hidden space-y-6">
        
        <!-- Directory Header -->
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-base font-bold text-slate-200">Personnel Accounts Registry</h3>
            <p class="text-xs text-slate-400 mt-0.5">Audit profiles, verify roles, and update account authorization states.</p>
          </div>
          <button onclick="openRegisterModal()" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 rounded-xl font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-amber-500/10 text-sm">
            <span class="material-symbols-rounded text-sm">person_add</span> Register User
          </button>
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
                  <th class="p-2.5 md:p-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 3: AUDIT TRAIL LOG -->
      <div id="panelAudit" class="hidden space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">System Audit Trail Log</h3>
            <p class="text-xs text-slate-400 mt-1">Lifecycle events, password resets, status changes, and registration records.</p>
          </div>
          <button onclick="loadAuditTrail()" class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-slate-950 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-2">
            <span class="material-symbols-rounded text-sm">sync</span> Refresh Log
          </button>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-xs border-collapse">
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
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- EDIT STAFF MODAL -->
  <div id="editStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-lg">edit</span> Edit Staff Details
        </h3>
        <button onclick="closeEditStaffModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="editStaffForm" onsubmit="submitStaffEdit(event)" class="space-y-4">
        <input type="hidden" id="editStaffMobile">
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Full Name</label>
          <input type="text" id="editStaffName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500 text-sm">
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Email Address</label>
          <input type="email" id="editStaffEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500 text-sm">
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Department Branch</label>
          <select id="editStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500 text-sm">
            <option value="EL">Electronics Engineering (EL)</option>
            <option value="ME">Mechanical Engineering (ME)</option>
            <option value="CE">Civil Engineering (CE)</option>
            <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
            <option value="CT">Computer Engineering (CT)</option>
            <option value="AU">Automobile Engineering (AU)</option>
            <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
            <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
            <option value="Administration">Administration</option>
          </select>
        </div>
        <div>
          <label class="block text-slate-400 font-bold uppercase tracking-wider mb-1.5 text-xs">Designation Role</label>
          <select id="editStaffDesig" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500 text-sm">
            <option value="Principal">Principal</option>
            <option value="Chairman">Chairman</option>
            <option value="HOD">Head of Department (HOD)</option>
            <option value="Academic_Coordinator">Academic Coordinator (Self-Financing)</option>
            <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
            <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
            <option value="Lecturer">Lecturer</option>
            <option value="Demonstrator">Demonstrator</option>
            <option value="Trade_Instructor">Trade Instructor</option>
            <option value="Super_Admin">Super Admin</option>
            <option value="Admin">Admin</option>
          </select>
        </div>

        <div id="editStaffAlert" class="hidden p-3 rounded-xl font-bold border text-sm"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeEditStaffModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-sm">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-amber-600 hover:bg-amber-500 text-slate-950 rounded-xl font-bold transition-premium cursor-pointer text-sm flex items-center justify-center gap-1.5">
            <span>Save Details</span>
            <div id="editStaffSpinner" class="hidden w-4 h-4 border-2 border-slate-950 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-lg">lock_reset</span> Password Reset
        </h3>
        <button onclick="closePasswordModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-400">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-200"></span> (<span id="pwdResetId" class="text-amber-400 font-mono"></span>).
        </p>
        <div>
          <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-amber-500 text-xs" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl font-bold border text-xs"></div>

      <div class="flex gap-3 pt-2">
        <button onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-xs">Cancel</button>
        <button onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-amber-600 hover:bg-amber-500 text-slate-950 rounded-xl font-bold transition-premium cursor-pointer text-xs">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE PROFILE -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-lg">receipt_long</span> Profile Audit Trail
        </h3>
        <button onclick="closeAuditModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-400">
          History log for <span id="auditProfileName" class="font-bold text-slate-200"></span> (<span id="auditProfileId" class="text-amber-400 font-mono"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl">
          <table class="w-full text-left text-xs border-collapse">
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
        <button onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-xs">Close Window</button>
      </div>
    </div>
  </div>

  <!-- DIRECT REGISTRATION MODAL -->
  <div id="registerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-lg">person_add</span> Register New Profile
        </h3>
        <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="directRegisterForm" onsubmit="handleDirectRegister(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <div>
          <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">User Type</label>
          <select id="regType" onchange="toggleDirectRegisterFields(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
            <option value="student">Student Profile</option>
            <option value="staff">Staff Profile</option>
          </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="directRegName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
          </div>
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="directRegEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>

        <div id="directStudentFields" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Register No</label>
              <input type="text" id="directRegStudentId" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" placeholder="e.g. 25EL1001">
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Admission No</label>
              <input type="text" id="directRegStudentAdm" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" placeholder="e.g. ADM25EL01">
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
              <select id="directRegStudentBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
                <option value="EL">EL</option>
                <option value="ME">ME</option>
                <option value="CE">CE</option>
                <option value="EEE">EEE</option>
                <option value="CT">CT</option>
                <option value="AU">AU</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Adm Year</label>
              <input type="number" id="directRegStudentYear" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" value="2026">
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Semester</label>
              <select id="directRegStudentSem" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
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

        <div id="directStaffFields" class="space-y-4 hidden">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
              <input type="text" id="directRegStaffMobile" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" placeholder="Mobile / Login ID">
            </div>
            <div>
              <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Designation</label>
              <select id="directRegStaffDesig" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
                <option value="HOD">Head of Department (HOD)</option>
                <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
                <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
                <option value="Lecturer" selected>Lecturer</option>
                <option value="Demonstrator">Demonstrator</option>
                <option value="Trade_Instructor">Trade Instructor</option>
                <option value="Principal">Principal</option>
                <option value="Chairman">Chairman</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
            <select id="directRegStaffBranch" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs">
              <option value="EL">Electronics Engineering (EL)</option>
              <option value="ME">Mechanical Engineering (ME)</option>
              <option value="CE">Civil Engineering (CE)</option>
              <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
              <option value="CT">Computer Engineering (CT)</option>
              <option value="AU">Automobile Engineering (AU)</option>
              <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
              <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
              <option value="Administration">Administration</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Password</label>
          <input type="text" id="directRegPassword" required class="w-full bg-slate-955 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-amber-500 outline-none text-xs" placeholder="e.g. chairman">
        </div>

        <div id="directRegAlert" class="hidden p-3 rounded-xl font-bold border text-xs"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-slate-300 transition-premium cursor-pointer text-xs">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-amber-600 hover:bg-amber-500 text-slate-950 rounded-xl font-bold transition-premium cursor-pointer flex items-center justify-center gap-1.5 text-xs">
            <span>Register Profile</span>
            <div id="directRegSpinner" class="hidden w-4 h-4 border-2 border-slate-950 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let activePanel = "dashboard";
    let selectedUserForReset = null;

    document.addEventListener("DOMContentLoaded", () => {
      loadStats();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'directory', 'audit'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-2.5 transition-premium bg-amber-500/10 text-amber-400 border-l-2 border-amber-500 text-xs";
        } else {
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-xl font-bold flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-xs";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'dashboard': 'Executive Overview',
        'directory': 'Personnel Directory',
        'audit': 'Audit Trail Log'
      };
      document.getElementById('panelTitle').innerText = titles[panelId] || 'Chairman Desk';

      if (panelId === 'directory') loadUsers();
      if (panelId === 'audit') loadAuditTrail();
    }

    function showLoading(show) {
      const el = document.getElementById('loadingIndicator');
      if (el) {
        if (show) el.classList.remove('hidden'); else el.classList.add('hidden');
      }
    }

    function loadStats() {
      showLoading(true);
      fetch('/api/admin/stats')
        .then(res => res.json())
        .then(data => {
          showLoading(false);
          if (data.status === 'SUCCESS') {
            document.getElementById('statTotalStaff').innerText = data.stats.totalStaff;
            document.getElementById('statTotalStudents').innerText = data.stats.totalStudents;
            document.getElementById('statPendingApprovals').innerText = data.stats.pendingApprovals;
            document.getElementById('statTotalClassrooms').innerText = data.stats.totalClassrooms;
          }
        })
        .catch(() => showLoading(false));
    }

    function loadUsers() {
      showLoading(true);
      const search = document.getElementById('filterSearch').value;
      const branch = document.getElementById('filterBranch').value;
      const role = document.getElementById('filterRole').value;
      const status = document.getElementById('filterStatus').value;

      const query = new URLSearchParams({ search, branch, role, status }).toString();
      fetch(`/api/admin/users?${query}`)
        .then(res => res.json())
        .then(data => {
          showLoading(false);
          const tbody = document.getElementById('usersTableBody');
          tbody.innerHTML = "";

          if (data.status === 'SUCCESS' && data.users.length > 0) {
            data.users.forEach(user => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition text-xs";
              
              const statusBadge = user.status === 'Approved' 
                ? `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`
                : (user.status === 'Pending' 
                  ? `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`
                  : `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">${user.status}</span>`);

              const defaultPhoto = user.type === 'student'
                ? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100'
                : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100';

              tr.innerHTML = `
                <td class="p-3 flex items-center gap-3">
                  <img src="${user.photo_url || defaultPhoto}" class="w-8 h-8 rounded-full border border-slate-700 object-cover">
                  <div>
                    <span class="font-bold text-slate-100 block">${user.name}</span>
                    <span class="text-[10px] text-slate-400 block">${user.email}</span>
                  </div>
                </td>
                <td class="p-3 font-mono text-slate-300">${user.id}</td>
                <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">${user.branch || 'N/A'}</span></td>
                <td class="p-3 font-semibold text-amber-400">${user.role}</td>
                <td class="p-3">${statusBadge}</td>
                <td class="p-3 text-right space-x-1">
                  ${user.status !== 'Approved' 
                    ? `<button onclick="toggleUserStatus('${user.id}', '${user.type}', 'Approved')" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-500 text-slate-950 text-[10px] font-bold rounded cursor-pointer">Approve</button>`
                    : `<button onclick="toggleUserStatus('${user.id}', '${user.type}', 'Pending')" class="px-2 py-1 bg-amber-600 hover:bg-amber-500 text-slate-950 text-[10px] font-bold rounded cursor-pointer">Hold</button>`
                  }
                  <button onclick="openPasswordModal('${user.id}', '${user.name}', '${user.type}')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 text-[10px] font-bold rounded border border-slate-700 cursor-pointer">Reset Pwd</button>
                  <button onclick="openAuditModal('${user.id}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 text-[10px] font-bold rounded border border-slate-700 cursor-pointer">Audit</button>
                </td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500">No personnel records found.</td></tr>`;
          }
        })
        .catch(() => showLoading(false));
    }

    function toggleUserStatus(userId, userType, newStatus) {
      showLoading(true);
      fetch('/api/admin/users/toggle-status', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ userId, userType, newStatus })
      })
      .then(res => res.json())
      .then(data => {
        showLoading(false);
        if (data.status === 'SUCCESS') {
          loadUsers();
          loadStats();
        } else {
          alert(data.message || 'Status update failed.');
        }
      })
      .catch(() => showLoading(false));
    }

    function openPasswordModal(id, name, type) {
      selectedUserForReset = { id, name, type };
      document.getElementById('pwdResetName').innerText = name;
      document.getElementById('pwdResetId').innerText = id;
      document.getElementById('newPasswordInput').value = "";
      document.getElementById('pwdAlert').classList.add('hidden');
      document.getElementById('passwordModal').classList.remove('hidden');
      document.getElementById('passwordModal').classList.add('flex');
    }

    function closePasswordModal() {
      document.getElementById('passwordModal').classList.add('hidden');
      document.getElementById('passwordModal').classList.remove('flex');
    }

    function submitPasswordReset() {
      const newPassword = document.getElementById('newPasswordInput').value.trim();
      const alertEl = document.getElementById('pwdAlert');
      if (newPassword.length < 4) {
        alertEl.innerText = "Password must be at least 4 characters long.";
        alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-red-500/10 text-red-400 border-red-500/20";
        alertEl.classList.remove('hidden');
        return;
      }

      showLoading(true);
      fetch('/api/admin/users/reset-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          userId: selectedUserForReset.id,
          userType: selectedUserForReset.type,
          newPassword: newPassword
        })
      })
      .then(res => res.json())
      .then(data => {
        showLoading(false);
        if (data.status === 'SUCCESS') {
          alertEl.innerText = "Password reset successfully!";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-green-500/10 text-green-400 border-green-500/20";
          alertEl.classList.remove('hidden');
          setTimeout(closePasswordModal, 1200);
        } else {
          alertEl.innerText = data.message || "Failed to reset password.";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-red-500/10 text-red-400 border-red-500/20";
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => showLoading(false));
    }

    function openAuditModal(id, name) {
      document.getElementById('auditProfileName').innerText = name;
      document.getElementById('auditProfileId').innerText = id;
      const tbody = document.getElementById('modalAuditTableBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-500">Querying audit logs...</td></tr>`;
      document.getElementById('auditModal').classList.remove('hidden');
      document.getElementById('auditModal').classList.add('flex');

      fetch(`/api/audit-logs?targetId=${id}`)
        .then(res => res.json())
        .then(data => {
          tbody.innerHTML = "";
          if (data.status === 'SUCCESS' && data.logs.length > 0) {
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-xs";
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${new Date(log.created_at).toLocaleString()}</td>
                <td class="p-3 font-bold text-slate-200">${log.performed_by_name || log.performed_by}</td>
                <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-500">No profile audit logs recorded.</td></tr>`;
          }
        });
    }

    function closeAuditModal() {
      document.getElementById('auditModal').classList.add('hidden');
      document.getElementById('auditModal').classList.remove('flex');
    }

    function loadAuditTrail() {
      showLoading(true);
      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          showLoading(false);
          const tbody = document.getElementById('auditTableBody');
          tbody.innerHTML = "";
          if (data.status === 'SUCCESS' && data.logs.length > 0) {
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition text-xs";
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${new Date(log.created_at).toLocaleString()}</td>
                <td class="p-3 font-bold text-slate-200">${log.performed_by_name || log.performed_by}</td>
                <td class="p-3 font-mono text-amber-400">${log.target_id || 'N/A'}</td>
                <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">${log.action}</span></td>
                <td class="p-3 font-mono text-slate-400 text-[10px]">${log.ip_address || '127.0.0.1'}</td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500">No system audit logs found.</td></tr>`;
          }
        })
        .catch(() => showLoading(false));
    }

    function openRegisterModal() {
      document.getElementById('registerModal').classList.remove('hidden');
      document.getElementById('registerModal').classList.add('flex');
    }

    function closeRegisterModal() {
      document.getElementById('registerModal').classList.add('hidden');
      document.getElementById('registerModal').classList.remove('flex');
    }

    function toggleDirectRegisterFields(type) {
      if (type === 'student') {
        document.getElementById('directStudentFields').classList.remove('hidden');
        document.getElementById('directStaffFields').classList.add('hidden');
      } else {
        document.getElementById('directStudentFields').classList.add('hidden');
        document.getElementById('directStaffFields').classList.remove('hidden');
      }
    }

    function handleDirectRegister(e) {
      e.preventDefault();
      const type = document.getElementById('regType').value;
      const name = document.getElementById('directRegName').value;
      const email = document.getElementById('directRegEmail').value;
      const password = document.getElementById('directRegPassword').value;
      const alertEl = document.getElementById('directRegAlert');

      let url = '/register/student';
      let payload = {};

      if (type === 'student') {
        payload = {
          name, email, password,
          reg_no: document.getElementById('directRegStudentId').value,
          adm_no: document.getElementById('directRegStudentAdm').value,
          branch: document.getElementById('directRegStudentBranch').value,
          admission_year: document.getElementById('directRegStudentYear').value,
          semester: document.getElementById('directRegStudentSem').value
        };
      } else {
        url = '/register/staff';
        payload = {
          name, email, password,
          mobile_no: document.getElementById('directRegStaffMobile').value,
          designation: document.getElementById('directRegStaffDesig').value,
          branch: document.getElementById('directRegStaffBranch').value
        };
      }

      showLoading(true);
      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        showLoading(false);
        if (data.status === 'SUCCESS') {
          alertEl.innerText = "Profile registered successfully!";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-green-500/10 text-green-400 border-green-500/20";
          alertEl.classList.remove('hidden');
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
            loadStats();
          }, 1200);
        } else {
          alertEl.innerText = data.message || "Registration failed.";
          alertEl.className = "p-3 rounded-xl font-bold border text-xs bg-red-500/10 text-red-400 border-red-500/20";
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => showLoading(false));
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const formData = new FormData();
      formData.append('photo', file);

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      if (statusEl) {
        statusEl.innerText = 'Uploading...';
        statusEl.classList.remove('hidden');
      }

      fetch('/api/staff/profile/upload-photo', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          if (statusEl) {
            statusEl.innerText = 'Updated!';
            setTimeout(() => statusEl.classList.add('hidden'), 2000);
          }
          if (data.photo_url) {
            document.getElementById('sidebarStaffImg').src = data.photo_url;
          }
        } else {
          if (statusEl) {
            statusEl.innerText = 'Failed';
            statusEl.className = 'text-sm font-bold text-red-400';
          }
        }
      })
      .catch(() => {
        if (statusEl) {
          statusEl.innerText = 'Error';
          statusEl.className = 'text-sm font-bold text-red-400';
        }
      });
    }
  </script>
</body>
</html>
