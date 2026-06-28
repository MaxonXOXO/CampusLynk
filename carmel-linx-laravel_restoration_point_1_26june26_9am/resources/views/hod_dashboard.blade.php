<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - HOD Dashboard</title>
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
<body class="bg-slate-900 text-slate-100 h-screen w-full flex flex-col md:flex-row overflow-hidden">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <div class="p-6 border-b border-slate-800/60 flex items-center gap-3">
      <div class="bg-gradient-to-br from-blue-500 to-sky-600 text-white font-black rounded-xl w-10 h-10 flex items-center justify-center text-lg shadow-lg shadow-blue-500/20">CL</div>
      <div>
        <h2 class="font-extrabold text-sm tracking-wide">Carmel Linx</h2>
        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">HOD Console</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-bold text-xs block truncate text-slate-200">{{ session('userName') }}</span>
        <span class="text-[9px] font-bold text-blue-400 block uppercase tracking-wider">{{ session('userBranch') }} HOD</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDirectory" onclick="switchPanel('directory')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">group</span> User Directory
      </button>
      <button id="navBatches" onclick="switchPanel('batches')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500">
        <span class="material-symbols-rounded text-lg">school</span> Batch Management
      </button>
      <button id="navSubjects" onclick="switchPanel('subjects')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">library_books</span> Subject Allocation
      </button>
      <button id="navAudit" onclick="switchPanel('audit')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">receipt_long</span> Department Audit Trail
      </button>
      <button id="navProfile" onclick="switchPanel('profile')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">settings</span> My Profile
      </button>
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
      <h1 id="panelTitle" class="text-lg font-extrabold text-slate-100 tracking-tight">Batch & Class Management</h1>
      <div id="loadingIndicator" class="hidden items-center gap-2 text-xs text-slate-400">
        <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
        <span>Syncing...</span>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl text-xs font-bold transition-premium border"></div>

      <!-- PANEL 1: USER DIRECTORY -->
      <div id="panelDirectory" class="hidden space-y-6">
        
        <!-- Directory Header -->
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-xs font-black text-slate-200">Department Registered Accounts ({{ session('userBranch') }})</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Filter, search, audit, and manage profile lifecycle states for students and staff in your branch.</p>
          </div>
          <button onclick="openRegisterModal()" class="px-4 py-2.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-blue-500/10">
            <span class="material-symbols-rounded text-sm">person_add</span> Register User
          </button>
        </div>

        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
          <!-- Search input -->
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search User</label>
            <input type="text" id="filterSearch" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Name, Register No, Mobile...">
          </div>
          <!-- Role filter -->
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Designation / Role</label>
            <select id="filterRole" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
              <option value="">All Roles</option>
              <option value="student">Students Only</option>
              <option value="Lecturer">Lecturers Only</option>
              <option value="Demonstrator">Demonstrators Only</option>
              <option value="Trade_Instructor">Trade Instructors Only</option>
              <option value="Tradesman">Tradesman Only</option>
              <option value="Laboratory_Assistant">Laboratory Assistants Only</option>
              <option value="Workshop_Instructor">Workshop Instructors Only</option>
            </select>
          </div>
          <!-- Status select -->
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Account Status</label>
            <select id="filterStatus" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
          <!-- Search Button -->
          <div>
            <button onclick="loadUsers()" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center justify-center gap-2 h-[38px]">
              <span class="material-symbols-rounded text-sm">search</span> Load Directory
            </button>
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
                <tr><td colspan="6" class="p-8 text-center text-slate-500 font-medium text-xs">Use the filters and click "Load Directory" to view accounts.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 2: BATCH MANAGEMENT -->
      <div id="panelBatches" class="space-y-6">

        <!-- Panel Header -->
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl flex-wrap gap-4">
          <div>
            <h3 class="text-xs font-black text-slate-200">Batch & Class Management ({{ session('userBranch') }})</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Create admission-year batches, assign a Tutor (class teacher) and Mentor for each batch. Students auto-assign on registration.</p>
          </div>
          <div class="flex items-center gap-4">
            <div class="flex bg-slate-900 rounded-xl p-1 border border-slate-800">
              <button id="btnHodFilterActive" onclick="loadBatches('active')" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-premium bg-violet-600/20 text-violet-400">Current Batches</button>
              <button id="btnHodFilterHistorical" onclick="loadBatches('historical')" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-premium text-slate-500 hover:text-slate-300">Previous Batches</button>
            </div>
            <button onclick="openCreateBatchModal()" class="px-4 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-violet-500/10">
              <span class="material-symbols-rounded text-sm">add</span> Create Batch
            </button>
          </div>
        </div>

        <!-- Batch Alert -->
        <div id="batchGlobalAlert" class="hidden p-4 rounded-xl text-xs font-bold border"></div>

        <!-- Batch Cards Grid -->
        <div id="batchCardsGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
          <!-- rendered by JS -->
        </div>

        <!-- Empty state -->
        <div id="batchEmptyState" class="hidden flex flex-col items-center justify-center py-16 text-center">
          <span class="material-symbols-rounded text-5xl text-slate-700 mb-3">folder_open</span>
          <p class="text-slate-500 font-bold text-sm">No batches created yet.</p>
          <p class="text-slate-600 text-xs mt-1">Click "Create Batch" to set up your first admission year cohort.</p>
        </div>

      </div>

      <!-- PANEL: SUBJECT ALLOCATION -->
      <div id="panelSubjects" class="hidden space-y-6">
        <div class="flex justify-between items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl">
          <div>
            <h3 class="text-xs font-black text-slate-200">Subject & Staff Allocation</h3>
            <p class="text-[10px] text-slate-400 mt-0.5">Map curriculum subjects to batches per semester and assign staff across departments.</p>
          </div>
          <button onclick="openSubjectModal()" class="px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-emerald-500/10">
            <span class="material-symbols-rounded text-sm">add_box</span> Add Subject
          </button>
        </div>

        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Select Target Batch</label>
            <select id="subjectBatchSelect" onchange="loadSubjects()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
              <option value="">-- Choose a Classroom --</option>
              <!-- Loaded via JS -->
            </select>
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Select Semester</label>
            <select id="subjectSemesterSelect" onchange="loadSubjects()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
              <option value="1">Semester 1</option>
              <option value="2">Semester 2</option>
              <option value="3" selected>Semester 3</option>
              <option value="4">Semester 4</option>
              <option value="5">Semester 5</option>
              <option value="6">Semester 6</option>
            </select>
          </div>
        </div>

        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                <th class="p-4">Subject Code</th>
                <th class="p-4">Subject Name</th>
                <th class="p-4">Type</th>
                <th class="p-4">Assigned Staff</th>
                <th class="p-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="subjectsTableBody">
              <tr><td colspan="5" class="p-8 text-center text-slate-500">Select a batch to view its subjects.</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- PANEL 3: AUDIT TRAIL -->
      <div id="panelAudit" class="hidden space-y-6">
        <!-- Audit Logs Controls -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4">
          <div>
            <h3 class="font-black text-slate-200 text-sm">Department Audit Trail</h3>
            <p class="text-xs text-slate-400 mt-1">Lifecycle events, status updates, registrations, and actions performed within the {{ session('userBranch') }} branch.</p>
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

      <!-- PANEL 3: MY PROFILE -->
      <div id="panelProfile" class="hidden space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Profile Card -->
          <div class="bg-slate-950/40 border border-slate-800/60 p-6 rounded-2xl space-y-4">
            <div class="flex flex-col items-center text-center space-y-3">
              <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-24 h-24 rounded-full border border-slate-700 object-cover shadow-lg">
              <div>
                <h3 class="text-base font-black text-white">{{ session('userName') }}</h3>
                <span class="text-xs font-bold text-blue-400 uppercase tracking-wider">{{ session('userBranch') }} Department HOD</span>
              </div>
            </div>
            <div class="border-t border-slate-800/60 pt-4 space-y-2.5 text-xs">
              <div class="flex justify-between">
                <span class="text-slate-400">Mobile ID:</span>
                <span class="font-bold text-slate-200">{{ session('userId') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Branch Code:</span>
                <span class="font-bold text-slate-200">{{ session('userBranch') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Role:</span>
                <span class="font-bold text-slate-200">Head of Department (HOD)</span>
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
                  <!-- Load logs specific to HOD -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- CREATE BATCH MODAL -->
  <div id="createBatchModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-violet-400 text-lg">school</span> Create New Batch
        </h3>
        <button onclick="closeCreateBatchModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="space-y-4">
        <!-- Admission Year -->
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Admission Year</label>
          <input type="number" id="batchAdmYear" min="2000" max="2100" value="2025"
            oninput="updateBatchPreview()"
            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-violet-500 focus:ring-1 focus:ring-violet-500 outline-none">
        </div>

        <!-- Preview -->
        <div class="bg-slate-950/60 border border-slate-800/60 rounded-xl p-3 flex items-center gap-3">
          <span class="material-symbols-rounded text-violet-400 text-base">info</span>
          <div>
            <p class="text-[10px] text-slate-400">Classroom ID that will be created:</p>
            <p id="batchIdPreview" class="font-mono font-bold text-violet-300 text-sm">{{ session('userBranch') }}_2025_2028</p>
          </div>
        </div>

        <!-- Optional Tutor -->
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Assign Tutor (Optional)</label>
          <select id="batchTutorSelect" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-violet-500 outline-none">
            <option value="">— Select Tutor (optional) —</option>
          </select>
        </div>

        <!-- Optional Mentor -->
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Assign Mentor (Optional)</label>
          <select id="batchMentorSelect" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-violet-500 outline-none">
            <option value="">— Select Mentor (optional) —</option>
          </select>
        </div>
      </div>

      <div id="createBatchAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>

      <div class="flex gap-3 pt-2">
        <button onclick="closeCreateBatchModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Cancel</button>
        <button onclick="submitCreateBatch()" class="flex-1 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 hover:from-violet-600 hover:to-purple-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer flex items-center justify-center gap-1.5">
          <span>Create Batch</span>
          <div id="createBatchSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
        </button>
      </div>
    </div>
  </div>

  <!-- BATCH DETAIL MODAL -->
  <div id="batchDetailModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-3xl shadow-2xl flex flex-col max-h-[90vh]">
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b border-slate-800 p-5 flex-shrink-0">
        <div>
          <h3 id="batchDetailTitle" class="font-black text-slate-100 text-sm">Batch Detail</h3>
          <p id="batchDetailSubtitle" class="text-[10px] text-slate-400 mt-0.5">Manage tutor, mentor, and enrolled students</p>
        </div>
        <button onclick="closeBatchDetailModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <div class="flex-grow overflow-y-auto p-5 space-y-5">

        <!-- Assignment Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

          <!-- Tutor Card -->
          <div class="bg-slate-950/60 border border-slate-800/60 rounded-2xl p-4 space-y-3">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-sky-400 text-lg">person_pin</span>
              <h4 class="font-black text-slate-200 text-xs">Class Tutor</h4>
            </div>
            <div id="tutorCurrentDisplay" class="text-xs text-slate-400">Not assigned</div>
            <div class="space-y-2">
              <select id="detailTutorSelect" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:border-sky-500 outline-none">
                <option value="">— None (Remove) —</option>
              </select>
              <button onclick="submitAssignTutor()" class="w-full py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer flex items-center justify-center gap-1.5">
                <span class="material-symbols-rounded text-sm">how_to_reg</span> Update Tutor
                <div id="assignTutorSpinner" class="hidden w-3 h-3 border-2 border-sky-200 border-t-white rounded-full animate-spin"></div>
              </button>
            </div>
            <div id="assignTutorAlert" class="hidden p-2 rounded-lg text-[10px] font-bold border"></div>
          </div>

          <!-- Mentor Card -->
          <div class="bg-slate-950/60 border border-slate-800/60 rounded-2xl p-4 space-y-3">
            <div class="flex items-center gap-2">
              <span class="material-symbols-rounded text-emerald-400 text-lg">supervisor_account</span>
              <h4 class="font-black text-slate-200 text-xs">Class Mentor</h4>
            </div>
            <div id="mentorCurrentDisplay" class="text-xs text-slate-400">Not assigned</div>
            <div class="space-y-2">
              <select id="detailMentorSelect" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:border-emerald-500 outline-none">
                <option value="">— None (Remove) —</option>
              </select>
              <button onclick="submitAssignMentor()" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer flex items-center justify-center gap-1.5">
                <span class="material-symbols-rounded text-sm">group_add</span> Update Mentor
                <div id="assignMentorSpinner" class="hidden w-3 h-3 border-2 border-emerald-200 border-t-white rounded-full animate-spin"></div>
              </button>
            </div>
            <div id="assignMentorAlert" class="hidden p-2 rounded-lg text-[10px] font-bold border"></div>
          </div>
        </div>

        <!-- Students Roster -->
        <div class="bg-slate-950/40 border border-slate-800/40 rounded-2xl overflow-hidden">
          <div class="p-4 border-b border-slate-800/60 flex items-center justify-between">
            <h4 class="font-black text-slate-200 text-xs flex items-center gap-2">
              <span class="material-symbols-rounded text-slate-400 text-base">groups</span>
              Enrolled Students
              <span id="rosterCountBadge" class="px-2 py-0.5 bg-slate-800 text-slate-400 rounded-full text-[10px] font-mono">0</span>
            </h4>
          </div>
          <div class="overflow-x-auto max-h-[280px] overflow-y-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold sticky top-0">
                  <th class="p-3">Name</th>
                  <th class="p-3">Reg No</th>
                  <th class="p-3">Adm No</th>
                  <th class="p-3">Type</th>
                  <th class="p-3">Status</th>
                </tr>
              </thead>
              <tbody id="batchRosterTableBody">
                <tr><td colspan="5" class="p-6 text-center text-slate-500">Loading...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

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



  <!-- AUDIT LOG MODAL FOR SINGLE PROFILE -->
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

  <!-- DIRECT REGISTRATION MODAL -->
  <div id="registerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">person_add</span> Register New Profile
        </h3>
        <button onclick="closeRegisterModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="directRegisterForm" onsubmit="handleDirectRegister(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <!-- Type Selection -->
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">User Type</label>
          <select id="regType" onchange="toggleDirectRegisterFields(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
            <option value="student">Student Profile</option>
            <option value="staff">Staff Profile</option>
          </select>
        </div>

        <!-- Common Fields -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="directRegName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="directRegEmail" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>

        <!-- Student-Specific Fields -->
        <div id="directStudentFields" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Admission Type</label>
              <select id="directRegAdmType" onchange="handleAdmTypeChange()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
                <option value="Regular">Regular</option>
                <option value="LET">Lateral Entry (LET)</option>
              </select>
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Adm Year</label>
              <input type="number" id="directRegStudentYear" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none" value="2026">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Register No</label>
              <input type="text" id="directRegStudentId" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none" placeholder="e.g. 25EL1001">
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Admission No</label>
              <input type="text" id="directRegStudentAdm" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none" placeholder="e.g. ADM25EL01">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
              <input type="text" id="directRegStudentBranch" readonly class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-400 focus:outline-none" value="{{ session('userBranch') }}">
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

        <!-- Staff-Specific Fields -->
        <div id="directStaffFields" class="space-y-4 hidden">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
              <input type="text" id="directRegStaffMobile" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none" placeholder="10-digit number">
            </div>
            <div>
              <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Designation</label>
              <select id="directRegStaffDesig" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
                <option value="Lecturer" selected>Lecturer</option>
                <option value="Demonstrator">Demonstrator</option>
                <option value="Trade_Instructor">Trade Instructor</option>
                <option value="Tradesman">Tradesman</option>
                <option value="Laboratory_Assistant">Laboratory Assistant</option>
                <option value="Workshop_Instructor">Workshop Instructor</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch</label>
            <input type="text" id="directRegStaffBranch" readonly class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-400 focus:outline-none" value="{{ session('userBranch') }}">
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
  <!-- SUBJECT MODAL -->
  <div id="subjectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-emerald-400 text-lg">add_box</span> Add Curriculum Subject
        </h3>
        <button onclick="closeSubjectModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="subjectForm" onsubmit="createSubject(event)" class="space-y-4">
        <input type="hidden" id="modalSubjectBatch">
        <input type="hidden" id="modalSubjectSemester">
        
        <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl mb-2 flex justify-between items-center text-xs">
          <span class="text-slate-400">Target Batch: <span id="displaySubjectBatch" class="font-bold text-slate-200"></span></span>
          <span class="text-slate-400">Semester: <span id="displaySubjectSemester" class="font-bold text-slate-200"></span></span>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Subject Code</label>
            <input type="text" id="subjectCode" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-emerald-500 outline-none" placeholder="e.g. ENG101">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Subject Type</label>
            <select id="subjectType" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-emerald-500 outline-none">
              <option value="Theory">Theory</option>
              <option value="Practical / Lab">Practical / Lab</option>
              <option value="Practicum">Practicum</option>
              <option value="Project Based Theory">Project Based Theory</option>
              <option value="Seminar">Seminar</option>
              <option value="Project">Project</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Subject Name</label>
          <input type="text" id="subjectName" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-emerald-500 outline-none" placeholder="e.g. Engineering Mathematics">
        </div>

        <div id="subjectAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeSubjectModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer flex items-center justify-center gap-1.5">
            <span>Add Subject</span>
            <div id="subjectSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ASSIGN STAFF MODAL -->
  <div id="assignStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-lg">group_add</span> Assign Teaching Staff
        </h3>
        <button onclick="closeAssignStaffModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <form id="assignStaffForm" onsubmit="assignStaff(event)" class="space-y-4">
        <input type="hidden" id="assignSubjectId">
        
        <p class="text-xs text-slate-400">Select one or more staff members to assign to <strong id="assignSubjectName" class="text-slate-200"></strong>.</p>
        
        <div>
          <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Branch Filter (For Inter-Department)</label>
          <select id="staffBranchFilter" onchange="renderAssignStaffList()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
            <option value="">All Branches</option>
            <option value="EL">Electronics (EL)</option>
            <option value="ME">Mechanical (ME)</option>
            <option value="CE">Civil (CE)</option>
            <option value="EEE">Electrical (EEE)</option>
            <option value="CT">Computer (CT)</option>
            <option value="AU">Automobile (AU)</option>
            <option value="GEN_AIDED">General (Aided)</option>
            <option value="GEN_SF">General (SF)</option>
          </select>
        </div>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-800/60 rounded-xl p-2 space-y-1" id="staffCheckboxList">
          <!-- Populated by JS -->
        </div>

        <div id="assignStaffAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeAssignStaffModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer flex items-center justify-center gap-1.5">
            <span>Save Assignments</span>
            <div id="assignStaffSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let activePanel = "batches";
    let selectedUserForReset = null;
    let activeBatchId = null;
    let deptStaffCache = [];

    document.addEventListener("DOMContentLoaded", () => {
      switchPanel(activePanel);
      // Pre-load dept staff for batch modals
      loadDeptStaffCache();
    });
    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['directory', 'batches', 'subjects', 'audit', 'profile'];
      
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
        'directory': 'User Accounts Directory',
        'batches': 'Batch & Class Management',
        'subjects': 'Curriculum & Staff Allocation',
        'audit': 'Department Audit Trail',
        'profile': 'My HOD Profile'
      };
      document.getElementById('panelTitle').innerText = titles[panelId] || 'Overview';

      // if (panelId === 'directory') loadUsers(); // Optional auto-load removed to prevent crowding
      if (panelId === 'batches') loadBatches();
      if (panelId === 'subjects') loadBatchesForSubjects();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'profile') loadSelfSecurityLogs();
    }

    function loadBatchesForSubjects() {
      // Just populate the dropdown if it's empty
      const select = document.getElementById('subjectBatchSelect');
      if (select && select.options.length > 1) {
        // Already loaded, just refresh the subjects table
        loadSubjects();
        return;
      }
      fetch('/api/hod/batches')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            select.innerHTML = '<option value="">-- Choose a Classroom --</option>';
            data.batches.forEach(b => {
              select.innerHTML += `<option value="${b.classroom_id}">${b.classroom_id} (Year ${b.batch_year})</option>`;
            });
          }
        });
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
      const role = document.getElementById('filterRole').value;
      const status = document.getElementById('filterStatus').value;

      const url = `/api/admin/users?search=${encodeURIComponent(search)}&role=${role}&status=${status}`;

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
              No matching registered profiles found.
            </td>
          </tr>
        `;
        return;
      }

      users.forEach(user => {
        // Prevent listing self or other HODs if needed (handled by backend, but safe-check)
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";

        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
        if (user.status === 'Approved') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
        } else if (user.status === 'Suspended') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;
        }

        let toggleButton = '';
        if (user.id !== "{{ session('userId') }}") {
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
        }

        let roleCol = user.role;
        // HOD can't promote role designations in general, we just display it.

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
          <td class="p-4">${roleCol}</td>
          <td class="p-4">${statusBadge}</td>
          <td class="p-4 text-right space-x-1">
            ${toggleButton}
            <button onclick="triggerPasswordReset('${user.id}', '${user.type}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded text-[10px] font-bold transition-premium cursor-pointer">
              Reset Pwd
            </button>
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-blue-900 border border-slate-800 text-slate-300 rounded text-[10px] font-bold transition-premium cursor-pointer" title="View Audit Trail">
              Audit
            </button>
            ${user.id !== "{{ session('userId') }}" ? `
            <button onclick="confirmDeleteUser('${user.id}', '${user.type}', '${user.name}')" class="px-2 py-1 bg-red-950/40 hover:bg-red-900 border border-red-900/60 text-red-400 rounded text-[10px] font-bold transition-premium cursor-pointer" title="Delete User">
              Delete
            </button>` : ''}
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
      tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">Querying department audit logs...</td></tr>`;

      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">No department audit logs found.</td></tr>`;
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

    function handleAdmTypeChange() {
      const admType = document.getElementById('directRegAdmType').value;
      const regNoInput = document.getElementById('directRegStudentId');
      if (admType === 'LET') {
        if (!regNoInput.value.startsWith('L')) {
          regNoInput.value = 'L' + regNoInput.value;
        }
        document.getElementById('directRegStudentSem').value = 'S3';
      } else {
        if (regNoInput.value.startsWith('L')) {
          regNoInput.value = regNoInput.value.substring(1);
        }
        document.getElementById('directRegStudentSem').value = 'S1';
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
        formData.append('admissionType', document.getElementById('directRegAdmType').value);
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
          alert.className = "p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "User registered successfully.";
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

    // =========================================================================
    // BATCH MANAGEMENT FUNCTIONS
    // =========================================================================

    function loadDeptStaffCache() {
      fetch('/api/hod/dept-staff')
        .then(r => r.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            deptStaffCache = data.staff;
          }
        })
        .catch(() => {});
    }

    function populateStaffDropdowns() {
      const selectors = ['#batchTutorSelect', '#batchMentorSelect', '#detailTutorSelect', '#detailMentorSelect'];
      selectors.forEach(sel => {
        const el = document.querySelector(sel);
        if (!el) return;
        const firstOpt = el.options[0];
        el.innerHTML = '';
        el.appendChild(firstOpt.cloneNode(true));
        deptStaffCache.forEach(s => {
          const opt = document.createElement('option');
          opt.value = s.mobile_no;
          opt.textContent = `${s.name} (${s.designation.replace(/_/g,' ')})`;
          el.appendChild(opt);
        });
      });
    }

    function showBatchMessage(msg, isError = false) {
      const el = document.getElementById('batchGlobalAlert');
      el.className = isError
        ? 'p-4 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block'
        : 'p-4 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900 block';
      el.innerText = msg;
      el.classList.remove('hidden');
      setTimeout(() => el.classList.add('hidden'), 5000);
    }

    function loadBatches(status = 'active') {
      const grid = document.getElementById('batchCardsGrid');
      const empty = document.getElementById('batchEmptyState');
      grid.innerHTML = `
        <div class="col-span-full flex items-center justify-center py-12">
          <div class="flex items-center gap-3 text-slate-500 text-xs font-bold">
            <div class="w-5 h-5 border-2 border-slate-700 border-t-violet-400 rounded-full animate-spin"></div>
            Loading batches...
          </div>
        </div>
      `;
      empty.classList.add('hidden');

      // Update toggle UI
      if (status === 'active') {
        document.getElementById('btnHodFilterActive').className = 'px-4 py-1.5 rounded-lg text-xs font-bold transition-premium bg-violet-600/20 text-violet-400';
        document.getElementById('btnHodFilterHistorical').className = 'px-4 py-1.5 rounded-lg text-xs font-bold transition-premium text-slate-500 hover:text-slate-300';
      } else {
        document.getElementById('btnHodFilterHistorical').className = 'px-4 py-1.5 rounded-lg text-xs font-bold transition-premium bg-slate-800 text-slate-300';
        document.getElementById('btnHodFilterActive').className = 'px-4 py-1.5 rounded-lg text-xs font-bold transition-premium text-slate-500 hover:text-slate-300';
      }

      fetch(`/api/hod/batches?status=${status}`)
        .then(r => r.json())
        .then(data => {
          grid.innerHTML = '';
          if (data.status !== 'SUCCESS' || data.batches.length === 0) {
            empty.classList.remove('hidden');
            return;
          }
          data.batches.forEach(batch => renderBatchCard(batch));
        })
        .catch(() => {
          grid.innerHTML = `<div class="col-span-full p-8 text-center text-red-400 font-bold text-xs">Failed to load batches.</div>`;
        });
    }

    function renderBatchCard(batch) {
      const grid = document.getElementById('batchCardsGrid');
      const card = document.createElement('div');
      card.className = 'bg-slate-950/50 border border-slate-800/60 rounded-2xl p-5 space-y-4 hover:border-violet-500/30 transition-premium cursor-pointer group';
      card.onclick = () => openBatchDetail(batch);

      const tutorHtml = batch.tutor_name
        ? `<div class="flex items-center gap-1.5"><span class="material-symbols-rounded text-sky-400 text-sm">person_pin</span><span class="text-slate-300">${batch.tutor_name}</span></div>`
        : `<div class="flex items-center gap-1.5"><span class="material-symbols-rounded text-slate-600 text-sm">person_off</span><span class="text-slate-600 italic">No tutor assigned</span></div>`;

      const mentorHtml = batch.mentor_name
        ? `<div class="flex items-center gap-1.5"><span class="material-symbols-rounded text-emerald-400 text-sm">supervisor_account</span><span class="text-slate-300">${batch.mentor_name}</span></div>`
        : `<div class="flex items-center gap-1.5"><span class="material-symbols-rounded text-slate-600 text-sm">person_off</span><span class="text-slate-600 italic">No mentor assigned</span></div>`;

      card.innerHTML = `
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2 py-0.5 bg-violet-500/10 text-violet-400 border border-violet-500/20 rounded-lg font-mono text-[10px] font-bold">${batch.classroom_id}</span>
            </div>
            <h4 class="font-black text-slate-100 text-sm">Admission ${batch.batch_year}</h4>
            <p class="text-[10px] text-slate-500">${batch.batch_year} – ${batch.batch_year + 3} Cohort</p>
          </div>
          <div class="text-right">
            <span class="text-2xl font-black text-slate-200">${batch.student_count}</span>
            <p class="text-[10px] text-slate-500">students</p>
          </div>
        </div>
        <div class="border-t border-slate-800/60 pt-3 space-y-1.5 text-xs">
          ${tutorHtml}
          ${mentorHtml}
        </div>
        <div class="text-[10px] text-violet-400 font-bold group-hover:text-violet-300 transition-premium flex items-center gap-1">
          <span class="material-symbols-rounded text-sm">open_in_new</span> Manage Batch
        </div>
      `;
      grid.appendChild(card);
    }

    function openCreateBatchModal() {
      document.getElementById('createBatchAlert').classList.add('hidden');
      document.getElementById('batchAdmYear').value = new Date().getFullYear();
      updateBatchPreview();
      // Refresh staff cache then populate dropdowns
      fetch('/api/hod/dept-staff')
        .then(r => r.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            deptStaffCache = data.staff;
            populateStaffDropdowns();
          }
        });
      const modal = document.getElementById('createBatchModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeCreateBatchModal() {
      const modal = document.getElementById('createBatchModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function updateBatchPreview() {
      const year = parseInt(document.getElementById('batchAdmYear').value) || new Date().getFullYear();
      const branch = '{{ session("userBranch") }}';
      document.getElementById('batchIdPreview').innerText = `${branch}_${year}_${year + 3}`;
    }

    function submitCreateBatch() {
      const spinner = document.getElementById('createBatchSpinner');
      const alertEl = document.getElementById('createBatchAlert');
      const year = document.getElementById('batchAdmYear').value;
      const tutor = document.getElementById('batchTutorSelect').value;
      const mentor = document.getElementById('batchMentorSelect').value;

      if (!year) {
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Please enter an admission year.';
        alertEl.classList.remove('hidden');
        return;
      }

      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      fetch('/api/hod/batches', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          admission_year: parseInt(year),
          tutor_mobile_no: tutor || null,
          mentor_mobile_no: mentor || null
        })
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
          setTimeout(() => {
            closeCreateBatchModal();
            loadBatches();
          }, 1800);
        } else {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Request failed.';
        alertEl.classList.remove('hidden');
      });
    }

    function openBatchDetail(batch) {
      activeBatchId = batch.classroom_id;

      document.getElementById('batchDetailTitle').innerText = `Batch ${batch.classroom_id}`;
      document.getElementById('batchDetailSubtitle').innerText = `Admission ${batch.batch_year} · ${batch.batch_year}–${batch.batch_year + 3} Cohort`;

      // Show current tutor/mentor
      document.getElementById('tutorCurrentDisplay').innerHTML = batch.tutor_name
        ? `<span class="font-bold text-sky-300">${batch.tutor_name}</span> <span class="text-slate-600 text-[10px]">(${batch.tutor_mobile_no})</span>`
        : '<span class="italic text-slate-600">Not assigned yet</span>';

      document.getElementById('mentorCurrentDisplay').innerHTML = batch.mentor_name
        ? `<span class="font-bold text-emerald-300">${batch.mentor_name}</span> <span class="text-slate-600 text-[10px]">(${batch.mentor_mobile_no})</span>`
        : '<span class="italic text-slate-600">Not assigned yet</span>';

      // Clear alerts
      document.getElementById('assignTutorAlert').classList.add('hidden');
      document.getElementById('assignMentorAlert').classList.add('hidden');

      // Populate dropdowns
      populateStaffDropdowns();

      // Pre-select current tutor/mentor
      if (batch.tutor_mobile_no) document.getElementById('detailTutorSelect').value = batch.tutor_mobile_no;
      if (batch.mentor_mobile_no) document.getElementById('detailMentorSelect').value = batch.mentor_mobile_no;

      // Load roster
      loadBatchRoster(batch.classroom_id);

      const modal = document.getElementById('batchDetailModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeBatchDetailModal() {
      const modal = document.getElementById('batchDetailModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      activeBatchId = null;
    }

    function submitAssignTutor() {
      const spinner = document.getElementById('assignTutorSpinner');
      const alertEl = document.getElementById('assignTutorAlert');
      const mobile = document.getElementById('detailTutorSelect').value;

      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      fetch('/api/hod/batches/assign-tutor', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ classroom_id: activeBatchId, tutor_mobile_no: mobile })
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-2 rounded-lg text-[10px] font-bold bg-green-950/40 text-green-400 border border-green-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
          document.getElementById('tutorCurrentDisplay').innerHTML = data.tutor_name 
            ? `<span class="font-bold text-sky-300">${data.tutor_name}</span>`
            : '<span class="italic text-slate-600">Not assigned</span>';
          loadBatches();
        } else {
          alertEl.className = 'p-2 rounded-lg text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Request failed.';
        alertEl.classList.remove('hidden');
      });
    }

    function submitAssignMentor() {
      const spinner = document.getElementById('assignMentorSpinner');
      const alertEl = document.getElementById('assignMentorAlert');
      const mobile = document.getElementById('detailMentorSelect').value;

      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      fetch('/api/hod/batches/assign-mentor', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ classroom_id: activeBatchId, mentor_mobile_no: mobile })
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-2 rounded-lg text-[10px] font-bold bg-green-950/40 text-green-400 border border-green-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
          document.getElementById('mentorCurrentDisplay').innerHTML = data.mentor_name
            ? `<span class="font-bold text-emerald-300">${data.mentor_name}</span>`
            : '<span class="italic text-slate-600">Not assigned</span>';
          loadBatches();
        } else {
          alertEl.className = 'p-2 rounded-lg text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Request failed.';
        alertEl.classList.remove('hidden');
      });
    }

    function loadBatchRoster(classroomId) {
      const tbody = document.getElementById('batchRosterTableBody');
      const countBadge = document.getElementById('rosterCountBadge');
      tbody.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-slate-500 text-xs">Loading students...</td></tr>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/students`)
        .then(r => r.json())
        .then(data => {
          tbody.innerHTML = '';
          if (data.status !== 'SUCCESS' || data.students.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-slate-600 text-xs font-bold">No students enrolled in this batch yet.</td></tr>`;
            countBadge.innerText = '0';
            return;
          }
          countBadge.innerText = data.students.length;
          data.students.forEach(s => {
            let statusBadge = `<span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
            if (s.status === 'Approved') statusBadge = `<span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
            else if (s.status === 'Suspended') statusBadge = `<span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;

            const admTypeBadge = s.admission_type === 'LET'
              ? `<span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">LET</span>`
              : `<span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-700 text-slate-400">Regular</span>`;

            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/20 transition-premium';
            tr.innerHTML = `
              <td class="p-3 font-bold text-slate-200">${s.name}</td>
              <td class="p-3 font-mono text-slate-400">${s.reg_no}</td>
              <td class="p-3 font-mono text-slate-500">${s.adm_no}</td>
              <td class="p-3">${admTypeBadge}</td>
              <td class="p-3">${statusBadge}</td>
            `;
            tbody.appendChild(tr);
          });
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-red-400 font-bold text-xs">Failed to load students.</td></tr>`;
        });
    }

    // =========================================================================
    // END BATCH MANAGEMENT FUNCTIONS
    // =========================================================================

    // =========================================================================
    // SUBJECT ALLOCATION FUNCTIONS
    // =========================================================================
    let allCollegeStaffCache = [];

    function loadSubjects() {
      const batchSelect = document.getElementById('subjectBatchSelect');
      const semSelect = document.getElementById('subjectSemesterSelect');
      const classroomId = batchSelect.value;
      const semester = semSelect.value;
      
      const tbody = document.getElementById('subjectsTableBody');
      if (!classroomId) {
        tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500">Select a batch to view its subjects.</td></tr>`;
        return;
      }

      tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500">Loading subjects...</td></tr>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/subjects?semester=${semester}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            allCollegeStaffCache = data.all_staff || [];
            tbody.innerHTML = '';
            if (data.subjects.length === 0) {
              tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-500">No subjects allocated for this semester yet.</td></tr>`;
              return;
            }

            data.subjects.forEach(subj => {
              let staffList = subj.staff.map(s => `<span class="block text-[10px] text-slate-400"><span class="font-bold text-slate-300">${s.name}</span> (${s.branch})</span>`).join('');
              if (subj.staff.length === 0) staffList = `<span class="text-red-400 text-[10px] font-bold">Unassigned</span>`;
              
              const currentStaffIds = subj.staff.map(s => s.mobile_no).join(',');

              const tr = document.createElement('tr');
              tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium';
              tr.innerHTML = `
                <td class="p-4 font-mono text-slate-300 font-bold">${subj.subject_code}</td>
                <td class="p-4 font-bold text-slate-200">${subj.subject_name}</td>
                <td class="p-4 text-slate-400 text-xs">${subj.subject_type}</td>
                <td class="p-4">${staffList}</td>
                <td class="p-4 text-right space-x-2">
                  <button onclick="openAssignStaffModal(${subj.id}, '${subj.subject_name.replace(/'/g, "\\'")}', '${currentStaffIds}')" class="px-2.5 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 rounded-lg text-[10px] font-bold transition-premium border border-blue-500/20">Assign Staff</button>
                  <button onclick="deleteSubject(${subj.id})" class="px-2.5 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg text-[10px] font-bold transition-premium border border-red-500/20">Delete</button>
                </td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-red-400">Failed to load subjects.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-red-400">Error fetching subjects.</td></tr>`;
        });
    }

    function openSubjectModal() {
      const batchSelect = document.getElementById('subjectBatchSelect');
      const semSelect = document.getElementById('subjectSemesterSelect');
      if (!batchSelect.value) {
        alert("Please select a target batch first.");
        return;
      }
      
      document.getElementById('modalSubjectBatch').value = batchSelect.value;
      document.getElementById('displaySubjectBatch').innerText = batchSelect.value;
      document.getElementById('modalSubjectSemester').value = semSelect.value;
      document.getElementById('displaySubjectSemester').innerText = semSelect.options[semSelect.selectedIndex].text;
      
      document.getElementById('subjectForm').reset();
      document.getElementById('subjectAlert').classList.add('hidden');
      
      const modal = document.getElementById('subjectModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeSubjectModal() {
      const modal = document.getElementById('subjectModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function createSubject(e) {
      e.preventDefault();
      const spinner = document.getElementById('subjectSpinner');
      const alertEl = document.getElementById('subjectAlert');
      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      const payload = {
        classroom_id: document.getElementById('modalSubjectBatch').value,
        semester: document.getElementById('modalSubjectSemester').value,
        subject_code: document.getElementById('subjectCode').value,
        subject_name: document.getElementById('subjectName').value,
        subject_type: document.getElementById('subjectType').value
      };

      fetch('/api/hod/batches/subjects/create', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          closeSubjectModal();
          loadSubjects(); // refresh
        } else {
          alertEl.className = 'p-2 rounded-lg text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
          alertEl.innerText = data.message;
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
        alertEl.innerText = 'Request failed.';
      });
    }

    function deleteSubject(subjectId) {
      if(!confirm("Are you sure you want to delete this subject? This will also remove any staff assignments for it.")) return;
      
      fetch(`/api/hod/batches/subjects/${subjectId}`, {
        method: 'DELETE',
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if(data.status === 'SUCCESS') loadSubjects();
        else alert(data.message);
      })
      .catch(() => alert('Failed to delete subject.'));
    }

    let currentAssignStaffIds = [];

    function openAssignStaffModal(subjectId, subjectName, currentStaffIds) {
      document.getElementById('assignSubjectId').value = subjectId;
      document.getElementById('assignSubjectName').innerText = subjectName;
      document.getElementById('staffBranchFilter').value = "{{ session('userBranch') }}";
      
      currentAssignStaffIds = currentStaffIds ? currentStaffIds.split(',') : [];
      
      renderAssignStaffList();

      document.getElementById('assignStaffAlert').classList.add('hidden');
      const modal = document.getElementById('assignStaffModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeAssignStaffModal() {
      const modal = document.getElementById('assignStaffModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function renderAssignStaffList() {
      const container = document.getElementById('staffCheckboxList');
      const branchFilter = document.getElementById('staffBranchFilter').value;
      
      container.innerHTML = '';
      
      let filteredStaff = allCollegeStaffCache;
      if (branchFilter) {
        filteredStaff = filteredStaff.filter(s => s.branch === branchFilter);
      }

      if (filteredStaff.length === 0) {
        container.innerHTML = '<div class="p-3 text-slate-500 text-xs text-center">No staff found for this branch.</div>';
        return;
      }

      filteredStaff.forEach(staff => {
        const isChecked = currentAssignStaffIds.includes(staff.mobile_no) ? 'checked' : '';
        const div = document.createElement('label');
        div.className = 'flex items-center gap-3 p-2 hover:bg-slate-800/40 rounded-lg cursor-pointer transition-premium border border-transparent hover:border-slate-700/50';
        div.innerHTML = `
          <input type="checkbox" name="assignStaffCb" value="${staff.mobile_no}" class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-blue-500" ${isChecked}>
          <div class="flex-grow flex justify-between items-center">
            <span class="text-xs font-bold text-slate-200">${staff.name}</span>
            <span class="text-[9px] text-slate-500 font-mono">${staff.branch} - ${staff.designation}</span>
          </div>
        `;
        container.appendChild(div);
      });
    }

    function assignStaff(e) {
      e.preventDefault();
      const subjectId = document.getElementById('assignSubjectId').value;
      const checkboxes = document.querySelectorAll('input[name="assignStaffCb"]:checked');
      const staffNos = Array.from(checkboxes).map(cb => cb.value);

      const spinner = document.getElementById('assignStaffSpinner');
      const alertEl = document.getElementById('assignStaffAlert');
      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      fetch(`/api/hod/batches/subjects/${subjectId}/assign-staff`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ staff_mobile_nos: staffNos })
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          closeAssignStaffModal();
          loadSubjects(); // refresh
        } else {
          alertEl.className = 'p-2 rounded-lg text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
          alertEl.innerText = data.message;
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-[10px] font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
        alertEl.innerText = 'Request failed.';
      });
    }

    // =========================================================================
    // END SUBJECT ALLOCATION
    // =========================================================================

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
        });
    }
  </script>
</body>
</html>
