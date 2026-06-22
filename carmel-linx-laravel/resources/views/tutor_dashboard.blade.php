<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Tutor Dashboard</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
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
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row overflow-hidden">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <div class="p-6 border-b border-slate-800/60 flex items-center gap-3">
      <div class="bg-gradient-to-br from-blue-500 to-sky-600 text-white font-black rounded-xl w-10 h-10 flex items-center justify-center text-lg shadow-lg shadow-blue-500/20">CL</div>
      <div>
        <h2 class="font-extrabold text-sm tracking-wide">Carmel Linx</h2>
        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Tutor Panel</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-bold text-xs block truncate text-slate-200">{{ session('userName') }}</span>
        <span class="text-[9px] font-bold text-green-400 block uppercase tracking-wider">{{ session('userBranch') }} Tutor</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navRoster" onclick="switchPanel('roster')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500">
        <span class="material-symbols-rounded text-lg">group</span> Supervised Class Roster
      </button>
      <button id="navAudit" onclick="switchPanel('audit')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">receipt_long</span> Class Audit Trail
      </button>
      <button id="navProfile" onclick="switchPanel('profile')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">settings</span> My Profile
      </button>
      <button id="navMentoring" onclick="switchPanel('mentoring')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">diversity_3</span> Mentoring Batches
      </button>

      @php
        $role = session('userRole');
        $backLink = '/dashboard/lecturer';
        if ($role === 'HOD') $backLink = '/dashboard/hod';
        if ($role === 'Demonstrator') $backLink = '/dashboard/demonstrator';
        if ($role === 'Trade_Instructor') $backLink = '/dashboard/tradeinstructor';
        if ($role === 'Workshop_Superintendent') $backLink = '/dashboard/workshop';
        if ($role === 'Gen_Dept_Coordinator_Aided') $backLink = '/dashboard/general-coordinator-aided';
        if ($role === 'Gen_Dept_Coordinator_Self_Finance') $backLink = '/dashboard/general-coordinator-sf';
      @endphp
      <a href="{{ $backLink }}" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-sky-400 hover:bg-sky-900/30 cursor-pointer no-underline block mt-4 border border-sky-900/50">
        <span class="material-symbols-rounded text-lg">arrow_back</span> Back to Staff Console
      </a>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80">
      <a href="/logout" class="w-full py-3 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-xs flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="text-lg font-extrabold text-slate-100 tracking-tight">Supervised Class Roster</h1>
      <div id="loadingIndicator" class="hidden items-center gap-2 text-xs text-slate-400">
        <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
        <span>Syncing...</span>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl text-xs font-bold transition-premium border"></div>

      <!-- PANEL 1: ROSTER -->
      <div id="panelRoster" class="space-y-6">
        
        <!-- Directory Header -->
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-xs font-black text-slate-200">Supervised Classroom Directory</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Manage and review lifecycle states of students in your assigned classroom.</p>
          </div>
          <button onclick="openRegisterModal()" class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-blue-500/10">
            <span class="material-symbols-rounded text-sm">person_add</span> Register Student
          </button>
        </div>

        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Search input -->
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search Student</label>
            <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Name, Register No, Mobile...">
          </div>
          <!-- Status select -->
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="filterStatus" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
        </div>

        <!-- Users Table Grid -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-4">Profile</th>
                  <th class="p-4">Mobile / Reg No</th>
                  <th class="p-4">Branch</th>
                  <th class="p-4">Role Designation</th>
                  <th class="p-4">Account Status</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <!-- User rows render dynamically via JS -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 2: AUDIT TRAIL -->
      <div id="panelAudit" class="hidden space-y-6">
        <!-- Audit Logs Controls -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">Classroom Audit Trail</h3>
            <p class="text-xs text-slate-400 mt-1">Lifecycle events, password resets, and approval actions involving students in your classroom.</p>
          </div>
          <button onclick="loadAuditTrail()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[11px] font-bold transition-premium cursor-pointer flex items-center gap-2">
            <span class="material-symbols-rounded text-sm">sync</span> Refresh Log
          </button>
        </div>

        <!-- Audit Table -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-4">Timestamp</th>
                  <th class="p-4">Actor</th>
                  <th class="p-4">Target Student (ID)</th>
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

      <!-- PANEL 3: MY PROFILE -->
      <div id="panelProfile" class="hidden space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Profile Card -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl space-y-4">
            <div class="flex flex-col items-center text-center space-y-3">
              <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100' }}" class="w-24 h-24 rounded-full border border-slate-700 object-cover shadow-lg">
              <div>
                <h3 class="text-base font-black text-white">{{ session('userName') }}</h3>
                <span class="text-xs font-bold text-green-400 uppercase tracking-wider">{{ session('userBranch') }} Class Tutor</span>
              </div>
            </div>
            <div class="border-t border-slate-800/60 pt-4 space-y-2.5 text-xs">
              <div class="flex justify-between">
                <span class="text-slate-400">Mobile ID:</span>
                <span class="font-bold text-slate-200">{{ session('userId') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Branch:</span>
                <span class="font-bold text-slate-200">{{ session('userBranch') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Role Designation:</span>
                <span class="font-bold text-slate-200">Class Tutor / Mentor</span>
              </div>
            </div>
          </div>

          <!-- Self Security Logs -->
          <div class="lg:col-span-2 bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl flex flex-col">
            <h3 class="text-sm font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2">
              <span class="material-symbols-rounded text-blue-400 text-lg">security</span> My Security Log
            </h3>
            <div class="flex-grow max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-850 rounded-xl">
              <table class="w-full text-left text-xs border-collapse">
                <thead>
                  <tr class="bg-slate-900/40 border-b border-slate-800 text-slate-400 font-bold">
                    <th class="p-3">Time</th>
                    <th class="p-3">Action</th>
                    <th class="p-3">Details</th>
                  </tr>
                </thead>
                <tbody id="selfSecurityLogsTable">
                  <!-- Load logs specific to Tutor -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- PANEL 4: MENTORING BATCHES -->
      <div id="panelMentoring" class="hidden space-y-6">
        <!-- Dashboard Header -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">Mentoring Batches & Splitter</h3>
            <p class="text-xs text-slate-400 mt-1">Split students between yourself and the second mentor.</p>
          </div>
          <div class="flex items-center gap-2">
            <select id="mentorClassroomSelect" onchange="loadMentoringData()" class="bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white outline-none">
              <option value="">Loading classrooms...</option>
            </select>
            <button onclick="loadMentoringData()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-[11px] font-bold transition-premium cursor-pointer flex items-center gap-2">
              <span class="material-symbols-rounded text-sm">sync</span> Refresh
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Unassigned Students -->
          <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl flex flex-col overflow-hidden">
            <div class="p-4 border-b border-slate-800/60 bg-slate-900/40 flex justify-between items-center">
              <div>
                <h4 class="font-black text-sm text-slate-200 flex items-center gap-2"><span class="material-symbols-rounded text-amber-400 text-base">person_off</span> Unassigned Students</h4>
                <p class="text-[10px] text-slate-500">Students without a mentor.</p>
              </div>
              <span id="unassignedCountBadge" class="bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded font-bold text-xs">0</span>
            </div>
            <div class="flex-grow max-h-[500px] overflow-y-auto scrollbar-hidden">
              <table class="w-full text-left text-xs">
                <tbody id="unassignedList">
                  <tr><td class="p-4 text-center text-slate-500">Select a classroom to view.</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Mentors Split View -->
          <div class="space-y-6">
            <!-- Mentor A (Tutor) -->
            <div class="bg-slate-950/30 border border-sky-900/40 rounded-2xl flex flex-col overflow-hidden">
              <div class="p-4 border-b border-sky-900/60 bg-sky-950/20 flex justify-between items-center">
                <div>
                  <h4 class="font-black text-sm text-sky-400 flex items-center gap-2"><span class="material-symbols-rounded text-base">person_pin</span> Batch A (Tutor)</h4>
                  <p id="mentorAInfo" class="text-[10px] text-slate-400">Loading...</p>
                </div>
                <span id="batchACountBadge" class="bg-sky-500/20 text-sky-400 px-2 py-0.5 rounded font-bold text-xs">0</span>
              </div>
              <div class="flex-grow max-h-[220px] overflow-y-auto scrollbar-hidden">
                <table class="w-full text-left text-xs">
                  <tbody id="batchAList"></tbody>
                </table>
              </div>
            </div>

            <!-- Mentor B -->
            <div class="bg-slate-950/30 border border-emerald-900/40 rounded-2xl flex flex-col overflow-hidden">
              <div class="p-4 border-b border-emerald-900/60 bg-emerald-950/20 flex justify-between items-center">
                <div>
                  <h4 class="font-black text-sm text-emerald-400 flex items-center gap-2"><span class="material-symbols-rounded text-base">supervisor_account</span> Batch B (Mentor)</h4>
                  <p id="mentorBInfo" class="text-[10px] text-slate-400">Loading...</p>
                </div>
                <span id="batchBCountBadge" class="bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded font-bold text-xs">0</span>
              </div>
              <div class="flex-grow max-h-[220px] overflow-y-auto scrollbar-hidden">
                <table class="w-full text-left text-xs">
                  <tbody id="batchBList"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Mentoring Caseload -->
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl space-y-4">
          <div class="flex items-center gap-3 border-b border-slate-800/60 pb-3 justify-between">
            <div class="flex items-center gap-3">
              <span class="material-symbols-rounded text-indigo-400 text-xl">school</span>
              <h3 class="text-base font-black text-slate-200">Mentoring Caseload (Data View)</h3>
            </div>
            <p class="text-[10px] text-slate-400">Tutors see the full class; Mentors see only their batch.</p>
          </div>
          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold">
                  <th class="p-3">Student</th>
                  <th class="p-3">Reg No</th>
                  <th class="p-3">Batch Assigned</th>
                  <th class="p-3">Diary Logs</th>
                  <th class="p-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="myMentoringStudentsList">
                <tr><td colspan="5" class="p-4 text-center text-slate-500">Select a classroom to view.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">lock_reset</span> Password Reset
        </h3>
        <button onclick="closePasswordModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-400">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-200"></span> (<span id="pwdResetId" class="text-blue-400 font-mono"></span>).
        </p>
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>

      <div class="flex gap-3 pt-2">
        <button onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Cancel</button>
        <button onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE STUDENT -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">receipt_long</span> Profile Audit Trail
        </h3>
        <button onclick="closeAuditModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-400">
          History log for <span id="auditProfileName" class="font-bold text-slate-200"></span> (<span id="auditProfileId" class="text-blue-400 font-mono"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-slate-955/80 border-b border-slate-800 text-slate-400 font-bold">
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
        <button onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Close Window</button>
      </div>
    </div>
  </div>

  <!-- REGISTER MODAL -->
  <div id="registerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">person_add</span> Direct Register Student
        </h3>
        <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>
      <form id="directRegisterForm" onsubmit="handleDirectRegister(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="directRegName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address</label>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
              <input type="text" id="directRegStudentBranch" readonly class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-400 focus:outline-none" value="{{ session('userBranch') }}">
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Adm Year</label>
              <input type="number" id="directRegStudentYear" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none" value="2026">
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Semester</label>
              <select id="directRegStudentSem" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
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

        <!-- Password -->
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Password</label>
          <input type="text" id="directRegPassword" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none" placeholder="e.g. 12345">
        </div>

        <div id="directRegAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer flex items-center justify-center gap-1.5">
            <span>Register Profile</span>
            <div id="directRegSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let activePanel = "roster";
    let selectedUserForReset = null;

    document.addEventListener("DOMContentLoaded", () => {
      // Check if routed directly to mentoring
      if (sessionStorage.getItem('openMentoring') === 'true') {
        sessionStorage.removeItem('openMentoring');
        activePanel = 'mentoring';
      }

      if (activePanel === 'roster') loadUsers();
      if (activePanel === 'audit') loadAuditTrail();
      if (activePanel === 'profile') loadSelfSecurityLogs();
      if (activePanel === 'mentoring') {
        switchPanel('mentoring'); // Ensures UI is updated
      }
    });

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      
      const panels = ['roster', 'audit', 'profile', 'mentoring'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'roster': 'Supervised Class Roster',
        'audit': 'Classroom Audit Trail',
        'profile': 'My Tutor Profile',
        'mentoring': 'Mentoring Batches'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'roster') loadUsers();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'profile') loadSelfSecurityLogs();
      if (panelId === 'mentoring') initMentoringPanel();
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border-red-900 block shadow-sm";
      } else {
        alert.className = "p-4 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border-green-900 block shadow-sm";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      const search = document.getElementById('filterSearch').value;
      const status = document.getElementById('filterStatus').value;

      const url = `/api/admin/users?search=${encodeURIComponent(search)}&role=student&status=${status}`;

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

    function renderUsersGrid(users) {
      const tbody = document.getElementById('usersTableBody');
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="6" class="p-8 text-center text-slate-500 font-medium font-sans">
              No classroom students found.
            </td>
          </tr>
        `;
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";

        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
        if (user.status === 'Approved') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
        } else if (user.status === 'Suspended') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;
        }

        let toggleButton = '';
        if (user.status === 'Pending') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2 py-1 bg-green-600 hover:bg-green-700 rounded text-[10px] font-bold text-white transition-premium cursor-pointer">
              Approve
            </button>
          `;
        } else if (user.status === 'Approved') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Suspended')" class="px-2 py-1 bg-red-950 hover:bg-red-900 border border-red-800 rounded text-[10px] font-bold text-red-300 transition-premium cursor-pointer">
              Suspend
            </button>
          `;
        } else if (user.status === 'Suspended') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 rounded text-[10px] font-bold text-white transition-premium cursor-pointer">
              Activate
            </button>
          `;
        }

        tr.innerHTML = `
          <td class="p-4 flex items-center gap-3">
            <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-800 shadow">
            <div>
              <span class="font-bold text-slate-100 block">${user.name}</span>
              <span class="text-[10px] text-slate-500 block">${user.email}</span>
            </div>
          </td>
          <td class="p-4 font-mono font-bold text-slate-300">${user.id}</td>
          <td class="p-4"><span class="font-bold font-mono text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
          <td class="p-4">${user.role}</td>
          <td class="p-4">${statusBadge}</td>
          <td class="p-4 text-right space-x-1">
            ${toggleButton}
            <button onclick="triggerPasswordReset('${user.id}', '${user.type}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded text-[10px] font-bold transition-premium cursor-pointer">
              Reset Pwd
            </button>
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-blue-900 border border-slate-800 text-slate-300 rounded text-[10px] font-bold transition-premium cursor-pointer" title="View Audit Trail">
              Audit
            </button>
            <button onclick="confirmDeleteUser('${user.id}', '${user.type}', '${user.name}')" class="px-2 py-1 bg-red-950/40 hover:bg-red-900 border border-red-900/60 text-red-400 rounded text-[10px] font-bold transition-premium cursor-pointer" title="Delete Student">
              Delete
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

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
          showGlobalMessage('Student status updated successfully.');
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

    function submitPasswordReset() {
      const pwd = document.getElementById('newPasswordInput').value.trim();
      const pwdAlert = document.getElementById('pwdAlert');
      
      if (pwd.length < 4) {
        pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
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
          pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          pwdAlert.innerText = data.message;
          pwdAlert.classList.remove('hidden');
        }
      })
      .catch(() => {
        pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Request failed.";
        pwdAlert.classList.remove('hidden');
      });
    }

    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">Querying classroom audit logs...</td></tr>`;

      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">No classroom audit logs found.</td></tr>`;
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
              tr.className = "border-b border-slate-800/40 text-xs";
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
            showGlobalMessage('Student profile deleted successfully.');
            loadUsers();
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to delete student profile.', true);
        });
      }
    }

    function openRegisterModal() {
      document.getElementById('directRegisterForm').reset();
      document.getElementById('directRegAlert').classList.add('hidden');
      
      const modal = document.getElementById('registerModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeRegisterModal() {
      const modal = document.getElementById('registerModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function handleDirectRegister(e) {
      e.preventDefault();
      const alert = document.getElementById('directRegAlert');
      const spinner = document.getElementById('directRegSpinner');
      
      alert.classList.add('hidden');
      spinner.classList.remove('hidden');

      const formData = new FormData();
      formData.append('name', document.getElementById('directRegName').value);
      formData.append('email', document.getElementById('directRegEmail').value);
      formData.append('password', document.getElementById('directRegPassword').value);
      formData.append('regNo', document.getElementById('directRegStudentId').value);
      formData.append('admNo', document.getElementById('directRegStudentAdm').value);
      formData.append('branch', document.getElementById('directRegStudentBranch').value);
      formData.append('admissionYear', document.getElementById('directRegStudentYear').value);
      formData.append('admissionType', 'Regular');

      fetch('/register/student', {
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
          alert.className = "p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "Student registered successfully.";
          alert.classList.remove('hidden');
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
          }, 1500);
        } else {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Request failed.";
        alert.classList.remove('hidden');
      });
    }

    function loadSelfSecurityLogs() {
      const tbody = document.getElementById('selfSecurityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">Querying security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">No profile action logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800 text-xs";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Error querying logs.</td></tr>`;
    // ==========================================
    // MENTORING BATCHES LOGIC
    // ==========================================

    let mentoringDataCache = null;
    let selectedMentoringClassroomId = null;

    function initMentoringPanel() {
      const select = document.getElementById('mentorClassroomSelect');
      select.innerHTML = '<option value="">Loading...</option>';
      
      fetch('/api/mentoring/my-batches')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            select.innerHTML = '';
            if (data.batches.length === 0) {
              select.innerHTML = '<option value="">No mentored classrooms</option>';
              document.getElementById('unassignedList').innerHTML = `<tr><td class="p-4 text-center text-slate-500">You are not assigned as a Mentor to any classroom.</td></tr>`;
              return;
            }

            data.batches.forEach(b => {
              const opt = document.createElement('option');
              opt.value = b.classroom_id;
              opt.innerText = `${b.classroom_id} (Admission ${b.batch_year})`;
              select.appendChild(opt);
            });
            
            selectedMentoringClassroomId = select.value;
            loadMentoringData();
          } else {
            select.innerHTML = '<option value="">Failed to load</option>';
          }
        })
        .catch(() => {
          select.innerHTML = '<option value="">Error</option>';
        });
    }

    function loadMentoringData() {
      const select = document.getElementById('mentorClassroomSelect');
      selectedMentoringClassroomId = select.value;
      if (!selectedMentoringClassroomId) return;

      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch(`/api/mentoring/report/${selectedMentoringClassroomId}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            mentoringDataCache = data;
            renderMentoringUI(data);
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to load mentoring data.', true);
        });
    }

    function renderMentoringUI(data) {
      document.getElementById('mentorAInfo').innerText = data.mentor1.name + ' (' + data.mentor1.mobile + ')';
      document.getElementById('mentorBInfo').innerText = data.mentor2.name + ' (' + data.mentor2.mobile + ')';

      const unassignedList = document.getElementById('unassignedList');
      const batchAList = document.getElementById('batchAList');
      const batchBList = document.getElementById('batchBList');
      const myList = document.getElementById('myMentoringStudentsList');

      document.getElementById('unassignedCountBadge').innerText = data.unassigned.length;
      document.getElementById('batchACountBadge').innerText = data.batch_a.length;
      document.getElementById('batchBCountBadge').innerText = data.batch_b.length;

      // Check if current user is Tutor (Mentor 1)
      const isTutor = (data.mentor1.mobile == '{{ session('userId') }}');
      const isMentor2 = (data.mentor2.mobile == '{{ session('userId') }}');

      // Helper to create assignment buttons
      const getActionButtons = (regNo, currentBatch) => {
        if (!isTutor) return ''; // Only Tutor can reassign
        
        if (currentBatch === null) {
          return `
            <button onclick="assignStudentBatch('${regNo}', 'A')" class="px-2 py-1 bg-sky-600 hover:bg-sky-500 text-white rounded text-[10px] font-bold mr-1">To A</button>
            <button onclick="assignStudentBatch('${regNo}', 'B')" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded text-[10px] font-bold">To B</button>
          `;
        } else if (currentBatch === 'A') {
          return `<button onclick="assignStudentBatch('${regNo}', 'B')" class="px-2 py-1 border border-emerald-600 text-emerald-400 hover:bg-emerald-950 rounded text-[10px] font-bold">Move to B</button>`;
        } else if (currentBatch === 'B') {
          return `<button onclick="assignStudentBatch('${regNo}', 'A')" class="px-2 py-1 border border-sky-600 text-sky-400 hover:bg-sky-950 rounded text-[10px] font-bold">Move to A</button>`;
        }
      };

      // Unassigned List
      unassignedList.innerHTML = '';
      if (data.unassigned.length === 0) unassignedList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">No unassigned students.</td></tr>';
      data.unassigned.forEach(s => {
        unassignedList.innerHTML += `
          <tr class="border-b border-slate-800/40 hover:bg-slate-800/40">
            <td class="p-3 font-bold text-slate-200">${s.name}</td>
            <td class="p-3 font-mono text-slate-500">${s.reg_no}</td>
            <td class="p-3 text-right whitespace-nowrap">${getActionButtons(s.reg_no, null)}</td>
          </tr>
        `;
      });

      // Batch A List
      batchAList.innerHTML = '';
      if (data.batch_a.length === 0) batchAList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">Empty batch.</td></tr>';
      data.batch_a.forEach(s => {
        batchAList.innerHTML += `
          <tr class="border-b border-sky-900/40 hover:bg-sky-900/20">
            <td class="p-3 font-bold text-sky-100">${s.name}</td>
            <td class="p-3 font-mono text-sky-500">${s.reg_no}</td>
            <td class="p-3 text-right whitespace-nowrap">${getActionButtons(s.reg_no, 'A')}</td>
          </tr>
        `;
      });

      // Batch B List
      batchBList.innerHTML = '';
      if (data.batch_b.length === 0) batchBList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">Empty batch.</td></tr>';
      data.batch_b.forEach(s => {
        batchBList.innerHTML += `
          <tr class="border-b border-emerald-900/40 hover:bg-emerald-900/20">
            <td class="p-3 font-bold text-emerald-100">${s.name}</td>
            <td class="p-3 font-mono text-emerald-500">${s.reg_no}</td>
            <td class="p-3 text-right whitespace-nowrap">${getActionButtons(s.reg_no, 'B')}</td>
          </tr>
        `;
      });

      // Mentoring Caseload
      myList.innerHTML = '';
      let myStudents = [];
      if (isTutor) {
        // Tutor sees everyone
        myStudents = [...data.batch_a, ...data.batch_b, ...data.unassigned];
      } else if (isMentor2) {
        // Mentor 2 sees only Batch B
        myStudents = data.batch_b;
      }
      
      if (myStudents.length === 0) {
        myList.innerHTML = '<tr><td colspan="5" class="p-4 text-center text-slate-500">You have no students in your caseload.</td></tr>';
      } else {
        myStudents.forEach(s => {
          let batchName = s.batch_label ? `Batch ${s.batch_label}` : 'Unassigned';
          let batchColor = s.batch_label === 'A' ? 'sky' : (s.batch_label === 'B' ? 'emerald' : 'amber');
          
          myList.innerHTML += `
            <tr class="border-b border-slate-800/40 hover:bg-slate-800/20">
              <td class="p-3 font-bold text-slate-200">${s.name}</td>
              <td class="p-3 font-mono text-slate-400">${s.reg_no}</td>
              <td class="p-3">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-${batchColor}-500/10 text-${batchColor}-400 border border-${batchColor}-500/20">
                  ${batchName}
                </span>
              </td>
              <td class="p-3 font-bold text-slate-300">
                ${s.diary_count || 0} entries
              </td>
              <td class="p-3 text-right">
                <button onclick="viewStudentDiary('${s.reg_no}', '${s.name}')" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-[11px] font-bold transition-premium cursor-pointer shadow-md">
                  View Data
                </button>
              </td>
            </tr>
          `;
        });
      }
    }

    function viewStudentDiary(regNo, name) {
      document.getElementById('diaryModalName').innerText = name;
      document.getElementById('diaryModalReg').innerText = regNo;
      const tbody = document.getElementById('diaryTableBody');
      tbody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-slate-500">Loading diary entries...</td></tr>';
      
      const modal = document.getElementById('diaryModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      fetch(`/api/mentoring/diary/${regNo}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = '';
            if (data.entries.length === 0) {
              tbody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-slate-500">No diary entries recorded for this student yet.</td></tr>';
              return;
            }
            data.entries.forEach(entry => {
              const tr = document.createElement('tr');
              tr.className = 'border-b border-slate-800/40 text-xs';
              
              let statusBadge = entry.approval_status === 'Approved' 
                ? `<span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`
                : `<span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">${entry.approval_status}</span>`;

              tr.innerHTML = `
                <td class="p-3 font-mono text-slate-400">${entry.date}</td>
                <td class="p-3 font-bold text-slate-300">${entry.category}<br><span class="text-[9px] font-mono text-slate-500">By: ${entry.logged_by_name}</span></td>
                <td class="p-3 text-slate-300">
                  <div class="mb-1"><strong class="text-slate-500">Notes:</strong> ${entry.discussion_notes}</div>
                  ${entry.action_taken ? `<div><strong class="text-slate-500">Action:</strong> ${entry.action_taken}</div>` : ''}
                </td>
                <td class="p-3 text-right">${statusBadge}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-red-400">Failed to load entries.</td></tr>';
          }
        })
        .catch(() => {
          tbody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-red-400">Error loading entries.</td></tr>';
        });
    }

    function closeDiaryModal() {
      const modal = document.getElementById('diaryModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function assignStudentBatch(regNo, batchLabel) {
      fetch('/api/mentoring/assign-batch', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          classroom_id: selectedMentoringClassroomId,
          reg_no: regNo,
          batch_label: batchLabel
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          loadMentoringData(); // Refresh UI
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => showGlobalMessage('Failed to assign student.', true));
    }
  </script>
</body>
</html>

