@php
  $activeBranch = $branchOverride ?? session('userBranch');
  $isPrincipalMode = isset($isPrincipalView) && $isPrincipalView;
  $initialPanel = request()->query('panel', request()->query('tab', $activePanel ?? 'batches'));
  if (!in_array($initialPanel, ['batches', 'directory', 'subjects', 'audit', 'leave_ledger', 'prof_activities', 'profile'])) {
    $initialPanel = 'batches';
  }
@endphp
<x-layouts.app-shell 
    :title="'CampusLynk - ' . ($isPrincipalMode ? 'Principal View' : 'HOD Console')" 
    :topbarTitle="'Batch & Class Management'" 
    :topbarSubtitle="'Department management, academics, faculty and reporting'" 
    :activeNav="$initialPanel"
>
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
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.05);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: rgba(99, 102, 241, 0.3);
      border-radius: 99px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: rgba(99, 102, 241, 0.5);
    }
    .table-responsive {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }
  </style>

  <!-- Global Alert Banner -->
  <div id="globalAlert" class="hidden p-4 rounded-xl text-sm font-semibold transition-all border mb-6"></div>

  <div class="space-y-6">

      <!-- PANEL 1: USER DIRECTORY -->
      <div id="panelDirectory" class="{{ $initialPanel === 'directory' ? '' : 'hidden' }} space-y-6">
        
        <!-- Directory Header -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100 uppercase tracking-wider">{{ $activeBranch }} Department</span>
              <span class="text-xs text-slate-400">·</span>
              <span class="text-xs text-slate-500 font-medium">User Directory</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Department Registered Accounts</h2>
            <p class="text-sm text-slate-500 mt-0.5">Filter, search, audit, and manage profile lifecycle states for students and staff in your branch.</p>
          </div>
          <button 
            type="button" 
            onclick="openRegisterModal()" 
            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-xs shrink-0 cursor-pointer"
          >
            <i data-lucide="user-plus" class="w-4 h-4 text-white"></i>
            <span>Register User</span>
          </button>
        </div>

        <!-- Filters Console -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
          <!-- Search input -->
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Search User</label>
            <input 
              type="text" 
              id="filterSearch" 
              class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all placeholder:text-slate-400" 
              placeholder="Name, Register No, Mobile..."
            >
          </div>
          <!-- Role filter -->
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Designation / Role</label>
            <select 
              id="filterRole" 
              class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none transition-all cursor-pointer"
            >
              <option value="">All Roles</option>
              <option value="student">Students Only</option>
              <option value="Lecturer">Lecturers Only</option>
              <option value="Demonstrator">Demonstrators Only</option>
              <option value="Physical_Instructor">Physical Instructors Only</option>
              <option value="Trade_Instructor">Trade Instructors Only</option>
              <option value="Tradesman">Tradesman Only</option>
              <option value="Laboratory_Assistant">Laboratory Assistants Only</option>
              <option value="Workshop_Instructor">Workshop Instructors Only</option>
            </select>
          </div>
          <!-- Status select -->
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Account Status</label>
            <select 
              id="filterStatus" 
              class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none transition-all cursor-pointer"
            >
              <option value="">All Statuses</option>
              <option value="Approved">Approved</option>
              <option value="Pending">Pending</option>
              <option value="Suspended">Suspended</option>
            </select>
          </div>
          <!-- Search Button -->
          <div>
            <button 
              type="button" 
              onclick="loadUsers()" 
              class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition-all cursor-pointer flex items-center justify-center gap-2 h-[38px] shadow-xs"
            >
              <i data-lucide="search" class="w-4 h-4 text-white"></i>
              <span>Load Directory</span>
            </button>
          </div>
        </div>

        <!-- Users Table Grid -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
          <div class="max-h-[calc(100vh-320px)] overflow-auto custom-scrollbar">
            <table class="min-w-[1100px] w-full text-left border-collapse text-sm">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-4">Profile</th>
                  <th class="p-4">Mobile / Reg No</th>
                  <th class="p-4">Branch</th>
                  <th class="p-4">Registered Sem</th>
                  <th class="p-4">Role Designation</th>
                  <th class="p-4">Account Status</th>
                  <th class="p-4">Enrollment Status</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <tr><td colspan="8" class="p-12 text-center text-slate-500 font-medium text-sm">Use the filters above and click "Load Directory" to view accounts.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL 2: BATCH MANAGEMENT (HOD OVERVIEW) -->
      <div id="panelBatches" class="{{ $initialPanel === 'batches' ? '' : 'hidden' }} space-y-6">

        <!-- Seminar Presentations Today dynamic notifications section -->
        <div id="seminarNotificationsContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-2">
          <!-- Populated dynamically -->
        </div>

        <!-- Panel Header -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100 uppercase tracking-wider">{{ $activeBranch }} Department</span>
              <span class="text-xs text-slate-400">·</span>
              <span class="text-xs text-slate-500 font-medium">Academic Console</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Batch & Classroom Management</h2>
            <p class="text-sm text-slate-500 mt-0.5">Manage admission-year batches, class tutors, batch mentors, and semester progression.</p>
          </div>
          <div class="flex items-center gap-3 shrink-0 flex-wrap sm:flex-nowrap">
            <!-- Active / Historical Filter Pills -->
            <div class="inline-flex p-1 bg-slate-100/80 border border-slate-200/60 rounded-xl">
              <button 
                id="btnHodFilterActive" 
                type="button" 
                onclick="loadBatches('active')" 
                class="px-3.5 py-1.5 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-xs border border-slate-200/60 cursor-pointer"
              >
                Current Batches
              </button>
              <button 
                id="btnHodFilterHistorical" 
                type="button" 
                onclick="loadBatches('historical')" 
                class="px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-600 hover:text-slate-900 cursor-pointer"
              >
                Previous Batches
              </button>
            </div>

            <!-- Primary Action CTA -->
            <button 
              type="button" 
              onclick="openCreateBatchModal()" 
              class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-xs shrink-0 cursor-pointer"
            >
              <i data-lucide="plus-circle" class="w-4 h-4 text-white"></i>
              <span>Create Batch</span>
            </button>
          </div>
        </div>

        <!-- Batch Alert -->
        <div id="batchGlobalAlert" class="hidden p-4 rounded-xl text-sm font-semibold border mb-4"></div>

        <!-- Batch Cards Grid -->
        <div id="batchCardsGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- rendered by JS -->
        </div>

        <!-- Empty state -->
        <div id="batchEmptyState" class="hidden bg-white border border-slate-200/80 rounded-2xl p-12 text-center shadow-xs">
          <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
            <i data-lucide="folder-open" class="w-6 h-6 text-slate-400"></i>
          </div>
          <h4 class="text-base font-bold text-slate-800">No batches found</h4>
          <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">No admission year batches exist for this filter. Click "Create Batch" to initialize your first department batch.</p>
        </div>

      </div>

      <!-- PANEL: SUBJECT ALLOCATION -->
      <div id="panelSubjects" class="{{ $initialPanel === 'subjects' ? '' : 'hidden' }} space-y-6">
        
        <!-- Header Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100 uppercase tracking-wider">{{ $activeBranch }} Department</span>
              <span class="text-xs text-slate-400">·</span>
              <span class="text-xs text-slate-500 font-medium">Curriculum Management</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Subject & Staff Allocation</h2>
            <p class="text-sm text-slate-500 mt-0.5">Map curriculum subjects to batches per semester and assign staff across departments.</p>
          </div>
          <button 
            type="button" 
            onclick="openSubjectModal()" 
            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition-all shadow-xs shrink-0 cursor-pointer"
          >
            <i data-lucide="plus-circle" class="w-4 h-4 text-white"></i>
            <span>Add Subject</span>
          </button>
        </div>

        <!-- Filters & Action Bar -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Target Batch</label>
            <select 
              id="subjectBatchSelect" 
              onchange="loadSubjects()" 
              class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none transition-all cursor-pointer font-medium"
            >
              <option value="">-- Choose a Classroom --</option>
              <!-- Loaded via JS -->
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Semester</label>
            <select 
              id="subjectSemesterSelect" 
              onchange="loadSubjects()" 
              class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none transition-all cursor-pointer font-medium"
            >
              <option value="1" selected>Semester 1</option>
              <option value="2">Semester 2</option>
              <option value="3">Semester 3</option>
              <option value="4">Semester 4</option>
              <option value="5">Semester 5</option>
              <option value="6">Semester 6</option>
            </select>
          </div>
        </div>

        <!-- Data Table Container -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
          <div class="max-h-[calc(100vh-320px)] overflow-auto custom-scrollbar">
            <table class="min-w-[900px] w-full text-left border-collapse text-sm">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-4">Subject Code</th>
                  <th class="p-4">Subject Name</th>
                  <th class="p-4">Type</th>
                  <th class="p-4">Assigned Staff</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="subjectsTableBody">
                <tr><td colspan="5" class="p-12 text-center text-slate-500 font-medium text-sm">Select a batch above to view its allocated subjects.</td></tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- PANEL 3: AUDIT TRAIL -->
      <div id="panelAudit" class="{{ $initialPanel === 'audit' ? '' : 'hidden' }} space-y-6">
        <!-- Audit Logs Controls -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100 uppercase tracking-wider">{{ session('userBranch') }} Branch</span>
              <span class="text-xs text-slate-400">·</span>
              <span class="text-xs text-slate-500 font-medium">Security & Integrity</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Department Audit Trail</h2>
            <p class="text-sm text-slate-500 mt-0.5">Lifecycle events, status updates, registrations, and actions performed within the {{ session('userBranch') }} branch.</p>
          </div>
          <button 
            type="button" 
            onclick="loadAuditTrail()" 
            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition-all shadow-xs shrink-0 cursor-pointer"
          >
            <i data-lucide="refresh-cw" class="w-4 h-4 text-white"></i>
            <span>Refresh Log</span>
          </button>
        </div>

        <!-- Audit Table Container -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
          <div class="max-h-[calc(100vh-320px)] overflow-auto custom-scrollbar">
            <table class="min-w-[1000px] w-full text-left border-collapse text-sm">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-4">Timestamp</th>
                  <th class="p-4">Actor</th>
                  <th class="p-4">Target User (ID)</th>
                  <th class="p-4">Action</th>
                  <th class="p-4">IP Address</th>
                  <th class="p-4">Details</th>
                </tr>
              </thead>
              <tbody id="auditTableBody">
                <tr><td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">Click "Refresh Log" to query audit records.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- PANEL: STAFF LEAVE MASTER LEDGER -->
      <div id="panelLeave_ledger" class="{{ $initialPanel === 'leave_ledger' ? '' : 'hidden' }} space-y-6">
        
        <!-- Header & Filters Card -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shrink-0">
              <i data-lucide="calendar-range" class="w-6 h-6 text-rose-600"></i>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                  <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                  {{ $activeBranch }} Department · Leave Ledger
                </span>
              </div>
              <h3 class="text-base font-bold text-slate-900 mt-1">Staff Leave Master Ledger &amp; Report Center</h3>
              <p class="text-xs text-slate-500 mt-0.5">Multi-stage approval audit trail, departmental leave balances, and official leave orders.</p>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2.5">
            <select id="leaveLedgerYear" onchange="loadLeaveLedger()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
              @foreach([date('Y'), date('Y')-1, date('Y')-2] as $yr)
                <option value="{{ $yr }}">{{ $yr }} - {{ $yr+1 }}</option>
              @endforeach
            </select>

            <select id="leaveLedgerDept" onchange="loadLeaveLedger()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
              <option value="{{ $activeBranch }}">{{ $activeBranch }} Department</option>
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

            <select id="leaveLedgerStatus" onchange="loadLeaveLedger()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
              <option value="">All Statuses</option>
              <option value="Pending_HOD">Pending HOD Recommendation</option>
              <option value="Pending_Coordinator">Pending Coordinator</option>
              <option value="Pending_Principal">Pending Principal Approval</option>
              <option value="Approved">Final Approved</option>
              <option value="Rejected">Rejected</option>
            </select>

            <button type="button" onclick="loadLeaveLedger()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200 flex items-center gap-2 text-sm font-semibold" title="Refresh Leave Ledger">
              <i data-lucide="refresh-cw" class="w-4 h-4 text-slate-600"></i>
              <span>Refresh</span>
            </button>
          </div>
        </div>

        <!-- 6 KPI Metric Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">Total Days</span>
            <span id="leaveKpiTotal" class="text-xl font-bold text-slate-900 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">Days in Selection</span>
          </div>
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-blue-600 font-bold uppercase tracking-wider block">Casual (CL)</span>
            <span id="leaveKpiCL" class="text-xl font-bold text-blue-600 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">Casual Leave</span>
          </div>
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-amber-600 font-bold uppercase tracking-wider block">Comp (CCL)</span>
            <span id="leaveKpiCCL" class="text-xl font-bold text-amber-600 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">Compensatory</span>
          </div>
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-indigo-600 font-bold uppercase tracking-wider block">Duty (DL)</span>
            <span id="leaveKpiDL" class="text-xl font-bold text-indigo-600 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">Duty Leave</span>
          </div>
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-emerald-600 font-bold uppercase tracking-wider block">Medical (ML)</span>
            <span id="leaveKpiML" class="text-xl font-bold text-emerald-600 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">Medical Leave</span>
          </div>
          <div class="bg-white border border-slate-200/80 p-4 rounded-2xl text-center shadow-xs">
            <span class="text-xs text-rose-600 font-bold uppercase tracking-wider block">Loss of Pay</span>
            <span id="leaveKpiLOP" class="text-xl font-bold text-rose-600 block mt-1">0.0</span>
            <span class="text-[11px] text-slate-400 font-medium">LOP Leave</span>
          </div>
        </div>

        <!-- Master Leave Table Container -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
          <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse text-sm">
              <thead>
                <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-xs">
                  <th class="py-3.5 px-4">Application &amp; Staff</th>
                  <th class="py-3.5 px-4">Leave Type</th>
                  <th class="py-3.5 px-4">Duration &amp; Dates</th>
                  <th class="py-3.5 px-4">Reason &amp; Duty Arrangement</th>
                  <th class="py-3.5 px-4 text-center">Multi-Stage Status</th>
                  <th class="py-3.5 px-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="leaveLedgerTableBody" class="divide-y divide-slate-100 text-slate-800">
                <tr><td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">Loading leave ledger applications...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- PANEL: PROFESSIONAL ACTIVITIES (5/7 WORKSPACE ARCHETYPE) -->
      <div id="panelProf_activities" class="{{ $initialPanel === 'prof_activities' ? '' : 'hidden' }} space-y-6">
        
        <!-- Header & Filters Toolbar Card -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
              <i data-lucide="award" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/80">
                  <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                  {{ $activeBranch }} Department · Faculty Professional Activities
                </span>
              </div>
              <h3 class="text-base font-bold text-slate-900 mt-1">Professional Activities</h3>
              <p class="text-xs text-slate-500 mt-0.5">Faculty development, publications, workshops, projects, and academic contributions.</p>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2.5">
            <select id="profActAyFilter" onchange="loadProfActivities()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
              @php
                $sYear = 2020;
                $eYear = date('Y') + 3;
              @endphp
              @for($y = $eYear; $y >= $sYear; $y--)
                @php $yr = $y . '-' . ($y + 1); @endphp
                <option value="{{ $yr }}" {{ $yr === (date('Y') . '-' . (date('Y') + 1)) ? 'selected' : '' }}>AY {{ $yr }}</option>
              @endfor
            </select>

            <select id="profActDeptFilter" onchange="loadProfActivities()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-blue-500 shadow-2xs">
              <option value="{{ $activeBranch }}">{{ $activeBranch }} Department</option>
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

            <button type="button" onclick="loadProfActivities()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition cursor-pointer border border-slate-200 flex items-center gap-2 text-sm font-semibold" title="Refresh Activities">
              <i data-lucide="refresh-cw" class="w-4 h-4 text-slate-600"></i>
              <span>Refresh</span>
            </button>
          </div>
        </div>

        <!-- 5/7 Split Workspace -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          
          <!-- Left 5 Columns: Activity Entry Form -->
          <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>Record Professional Activity</span>
              </h4>
              <span class="text-xs text-slate-400 font-mono" id="profActAyLabel">AY {{ date('Y') }}-{{ date('Y') + 1 }}</span>
            </div>

            <form id="profActivityForm" onsubmit="submitProfActivity(event)" class="space-y-4">
              <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Activity Category <span class="text-rose-500">*</span></label>
                <select id="profActType" onchange="toggleProfActFields(this.value)" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all font-medium">
                  <option value="fdp_attended">Faculty Development Program (FDP) / Training</option>
                  <option value="workshop_attended">Technical Workshop / Hands-on BootCamp</option>
                  <option value="course_attended">Online Certification / MOOC / NPTEL Course</option>
                  <option value="gap_in_syllabus">Curricular Gap Identified in Syllabus</option>
                  <option value="project_guided">Student Major / Minor Project Guided</option>
                  <option value="seminar_guided">Student Technical Seminar Guided</option>
                  <option value="publication">Research Paper / Journal Publication</option>
                  <option value="book_published">Book Published (with ISBN)</option>
                </select>
              </div>

              <!-- Dynamic Schema Fields Container -->
              <div id="profActDynamicFields" class="space-y-3 pt-1">
                <!-- Rendered dynamically by JS toggleProfActFields -->
              </div>

              <div id="profActAlert" class="hidden p-3 rounded-xl font-semibold border text-sm"></div>

              <button type="submit" id="btnSaveProfAct" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                <i data-lucide="save" class="w-4 h-4 text-white"></i>
                <span>Save Activity Record</span>
              </button>
            </form>
          </div>

          <!-- Right 7 Columns: KPI Metrics & Activity Feed -->
          <div class="lg:col-span-7 space-y-4">
            
            <!-- 3 Metric KPI Summary Cards -->
            <div class="grid grid-cols-3 gap-3">
              <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-xs text-center">
                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">Total Recorded</span>
                <span id="profActTotalCount" class="text-xl font-bold text-slate-900 block mt-1">0</span>
                <span class="text-[11px] text-slate-400 font-medium">Department Items</span>
              </div>
              <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-xs text-center">
                <span class="text-xs text-indigo-600 font-bold uppercase tracking-wider block">FDPs &amp; Workshops</span>
                <span id="profActFdpCount" class="text-xl font-bold text-indigo-600 block mt-1">0</span>
                <span class="text-[11px] text-slate-400 font-medium">Training Programs</span>
              </div>
              <div class="bg-white border border-slate-200/80 p-4 rounded-2xl shadow-xs text-center">
                <span class="text-xs text-emerald-600 font-bold uppercase tracking-wider block">Publications &amp; Books</span>
                <span id="profActPubCount" class="text-xl font-bold text-emerald-600 block mt-1">0</span>
                <span class="text-[11px] text-slate-400 font-medium">Research &amp; Text</span>
              </div>
            </div>

            <!-- Activity Registry Feed Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs space-y-3.5">
              <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <i data-lucide="award" class="w-4 h-4 text-blue-600"></i>
                  <span>Professional Activity Registry</span>
                </h4>
                <span id="profActRegistryCount" class="text-xs text-slate-500 font-medium">0 records in AY</span>
              </div>

              <!-- List Container -->
              <div id="profActListContainer" class="space-y-3 max-h-[560px] overflow-y-auto custom-scrollbar">
                <div class="p-8 text-center text-slate-400 text-sm">Loading activity records...</div>
              </div>
            </div>

          </div>

        </div>

      </div>

      <!-- PANEL 4: MY PROFILE -->
      <div id="panelProfile" class="{{ $initialPanel === 'profile' ? '' : 'hidden' }} space-y-6">
        @include('partials.staff_profile_panel', ['hideAuditLog' => true])
      </div>

    </div>

  <!-- CREATE BATCH MODAL -->
  <div id="createBatchModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-5">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="school" class="w-5 h-5 text-blue-600"></i>
          <span>Create New Batch</span>
        </h3>
        <button type="button" onclick="closeCreateBatchModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="space-y-4">
        <!-- Admission Year -->
        <div id="batchAdmYearContainer">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admission Year</label>
          <input type="number" id="batchAdmYear" min="2000" max="2100" value="2026"
            oninput="updateBatchPreview()"
            class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
        </div>

        <!-- Batch Type -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Batch Type</label>
          <select id="batchTypeSelect" onchange="toggleBatchCreationLetView(); updateBatchPreview();" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none cursor-pointer">
            <option value="Regular" selected>Regular (Default 3-Year Batch)</option>
            <option value="LET">Lateral Entry (LET Batch - Copy Tutor/Mentor, Starts S3)</option>
          </select>
        </div>

        <!-- Preview -->
        <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-3.5 flex items-center gap-3">
          <i data-lucide="info" class="w-4 h-4 text-blue-600 shrink-0"></i>
          <div>
            <p class="text-xs text-slate-600 font-medium">Classroom ID that will be created:</p>
            <p id="batchIdPreview" class="font-mono font-bold text-blue-700 text-sm">{{ session('userBranch') }}_2025_2028</p>
          </div>
        </div>

        <!-- Starting Semester -->
        <div id="batchStartSemesterContainer">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Starting Semester</label>
          <select id="batchStartSemesterSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value="1" selected>Semester 1 (S1)</option>
            <option value="2">Semester 2 (S2)</option>
            <option value="3">Semester 3 (S3)</option>
            <option value="4">Semester 4 (S4)</option>
            <option value="5">Semester 5 (S5)</option>
            <option value="6">Semester 6 (S6)</option>
          </select>
        </div>

        <!-- Optional Tutor -->
        <div id="batchTutorContainer">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Assign Tutor (Optional)</label>
          <select id="batchTutorSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value=""> Select Tutor (optional) </option>
          </select>
        </div>

        <!-- Optional Mentor -->
        <div id="batchMentorContainer">
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Assign Mentor (Optional)</label>
          <select id="batchMentorSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value="">Select Mentor (optional) </option>
          </select>
        </div>
      </div>

      <div id="createBatchAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

      <div class="flex gap-3 pt-2">
        <button type="button" onclick="closeCreateBatchModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Cancel</button>
        <button type="button" onclick="submitCreateBatch()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-2 shadow-xs">
          <span>Create Batch</span>
          <div id="createBatchSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
        </button>
      </div>
    </div>
  </div>
      </div>
    </div>
  </div>

  <!-- BATCH DETAIL MODAL -->
  <div id="batchDetailModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-7xl shadow-2xl flex flex-col max-h-[95vh]">
      <!-- Modal Header -->
      <div class="flex justify-between items-center border-b border-slate-100 p-5 flex-shrink-0">
        <div>
          <h3 id="batchDetailTitle" class="font-bold text-slate-900 text-base">Batch Detail</h3>
          <p id="batchDetailSubtitle" class="text-xs text-slate-500 mt-0.5">Manage tutor, mentor, subjects, and enrolled students</p>
        </div>
        <div class="flex items-center gap-2">
          <!-- Graduate / Archive Batch button (NEW - purely additive) -->
          <button id="btnGraduateBatch" onclick="confirmGraduateBatch()" class="hidden px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-xl text-xs font-semibold transition-colors cursor-pointer flex items-center gap-1.5">
            <i data-lucide="graduation-cap" class="w-4 h-4"></i>
            <span>Graduate / Archive</span>
          </button>
          <!-- Delete Batch button -->
          <button id="btnDeleteBatch" onclick="confirmDeleteBatch()" class="hidden px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold transition-colors cursor-pointer flex items-center gap-1.5">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
            <span>Delete Batch</span>
          </button>
          <button type="button" onclick="closeBatchDetailModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>
      </div>

      <!-- Tabs Navigation -->
      <div class="flex border-b border-slate-100 px-5 pt-3 gap-6 overflow-x-auto">
         <button onclick="switchBatchTab('tutorMentor')" id="tabBtn_tutorMentor" class="pb-3 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 transition-colors cursor-pointer whitespace-nowrap">Tutor &amp; Mentor</button>
         <button onclick="switchBatchTab('timetable')" id="tabBtn_timetable" class="pb-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-colors cursor-pointer whitespace-nowrap">Time Table</button>
         <button onclick="switchBatchTab('semesterHistory')" id="tabBtn_semesterHistory" class="pb-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-colors cursor-pointer whitespace-nowrap">Semester History</button>
      </div>

      <div class="flex-grow overflow-y-auto p-5 relative">
        <!-- Tab: Tutor & Mentor -->
        <div id="batchTab_tutorMentor" class="block space-y-4">

        <!-- Assignment Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

          <!-- Tutor Card -->
          <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-3">
            <div class="flex items-center gap-2">
              <i data-lucide="user-check" class="w-4 h-4 text-blue-600"></i>
              <h4 class="font-bold text-slate-900 text-sm">Class Tutor</h4>
            </div>
            <div id="tutorCurrentDisplay" class="text-sm text-slate-600 font-medium">Not assigned</div>
            <div class="space-y-2">
              <select id="detailTutorSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                <option value="">- None (Remove) -</option>
              </select>
              <button onclick="submitAssignTutor()" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                <span>Update Tutor</span>
                <div id="assignTutorSpinner" class="hidden w-3 h-3 border-2 border-blue-200 border-t-white rounded-full animate-spin"></div>
              </button>
            </div>
            <div id="assignTutorAlert" class="hidden p-2 rounded-lg text-sm font-bold border"></div>
          </div>

          <!-- Mentor Card -->
          <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 space-y-3">
            <div class="flex items-center gap-2">
              <i data-lucide="users" class="w-4 h-4 text-emerald-600"></i>
              <h4 class="font-bold text-slate-900 text-sm">Class Mentor</h4>
            </div>
            <div id="mentorCurrentDisplay" class="text-sm text-slate-600 font-medium">Not assigned</div>
            <div class="space-y-2">
              <select id="detailMentorSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                <option value="">- None (Remove) -</option>
              </select>
              <button onclick="submitAssignMentor()" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                <span>Update Mentor</span>
                <div id="assignMentorSpinner" class="hidden w-3 h-3 border-2 border-emerald-200 border-t-white rounded-full animate-spin"></div>
              </button>
            </div>
            <div id="assignMentorAlert" class="hidden p-2 rounded-lg text-sm font-bold border"></div>
          </div>
        </div>
        </div>

      <!-- Tab: Time Table -->
      <div id="batchTab_timetable" class="hidden space-y-4">
        <div class="flex justify-between items-center bg-slate-50 border border-slate-200 p-4 rounded-xl">
          <div>
            <h4 class="text-sm font-bold text-slate-900">Batch Weekly Timetable</h4>
            <p class="text-xs text-slate-500">Configure weekly lecture and lab hours. 3 periods forenoon, 3 periods afternoon.</p>
          </div>
          <div class="flex gap-2">
            <button onclick="printTimetable()" class="px-3.5 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl font-semibold text-xs transition-colors cursor-pointer flex items-center gap-1.5">
              <i data-lucide="printer" class="w-4 h-4"></i>
              <span>Print</span>
            </button>
            <button id="btnEditTimetable" onclick="toggleTimetableEdit(true)" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs transition-colors cursor-pointer flex items-center gap-1.5 shadow-xs">
              <i data-lucide="edit-3" class="w-4 h-4"></i>
              <span>Edit Timetable</span>
            </button>
            <button id="btnCancelTimetable" onclick="toggleTimetableEdit(false)" class="hidden px-3.5 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-xl font-semibold text-xs transition-colors cursor-pointer">
              Cancel
            </button>
            <button id="btnSaveTimetable" onclick="submitTimetable()" class="hidden px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-xs transition-colors cursor-pointer flex items-center gap-1.5 shadow-xs">
              <i data-lucide="save" class="w-4 h-4"></i>
              <span>Save Changes</span>
            </button>
          </div>
        </div>

        <!-- View Mode -->
        <div id="timetableDisplayArea" class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                <th class="p-3 text-center w-24">Day</th>
                <th class="p-3 text-center">Period 1<br><span class="text-[11px] text-slate-500 font-normal">09:00 - 10:00</span></th>
                <th class="p-3 text-center">Period 2<br><span class="text-[11px] text-slate-500 font-normal">10:00 - 11:00</span></th>
                <th class="p-3 text-center">Period 3<br><span class="text-[11px] text-slate-500 font-normal">11:10 - 12:10</span></th>
                <th class="p-3 text-center bg-slate-100/60 w-16 text-slate-500">Lunch</th>
                <th class="p-3 text-center">Period 4<br><span class="text-[11px] text-slate-500 font-normal">01:00 - 02:00</span></th>
                <th class="p-3 text-center">Period 5<br><span class="text-[11px] text-slate-500 font-normal">02:00 - 03:00</span></th>
                <th class="p-3 text-center">Period 6<br><span class="text-[11px] text-slate-500 font-normal">03:00 - 04:00</span></th>
              </tr>
            </thead>
            <tbody id="timetableDisplayBody">
              <!-- Rendered by JS -->
            </tbody>
          </table>
        </div>
      </div>
      </div> <!-- Close flex-grow container -->
    </div>
  </div>

        <!-- Edit Mode (Form Grid) -->
        <div id="timetableEditArea" class="hidden bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-x-auto">
          <table class="w-full text-left text-sm border-collapse min-w-[800px]">
            <thead>
              <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                <th class="p-3 text-center w-24">Day</th>
                <th class="p-3 text-center">Period 1</th>
                <th class="p-3 text-center">Period 2</th>
                <th class="p-3 text-center">Period 3</th>
                <th class="p-3 text-center bg-slate-900/20 w-16">Lunch</th>
                <th class="p-3 text-center">Period 4</th>
                <th class="p-3 text-center">Period 5</th>
                <th class="p-3 text-center">Period 6</th>
              </tr>
            </thead>
            <tbody id="timetableEditBody">
              <!-- Rendered by JS -->
            </tbody>
          </table>
        </div>
      </div>
      </div> <!-- Close flex-grow container -->
    </div>
  </div>

  <!-- ============================================================ -->
  <!-- NEW: SEMESTER HISTORY TAB PANEL (purely additive, no existing code changed) -->
  <!-- This panel is part of the batchDetailModal flex-grow area but rendered as a hidden sibling -->
  <!-- Note: Panel is injected via JS into the flex-grow container on tab switch -->
  <!-- ============================================================ -->

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="key-round" class="w-5 h-5 text-blue-600"></i>
          <span>Password Reset</span>
        </h3>
        <button type="button" onclick="closePasswordModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="space-y-3">
        <p class="text-sm text-slate-600">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-900"></span> (<span id="pwdResetId" class="text-blue-600 font-mono"></span>).
        </p>
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

      <div class="flex gap-3 pt-2">
        <button type="button" onclick="closePasswordModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Cancel</button>
        <button type="button" onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE PROFILE -->
  <div id="auditModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>
          <span>Profile Audit Trail</span>
        </h3>
        <button type="button" onclick="closeAuditModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="space-y-3">
        <p class="text-sm text-slate-600">
          History log for <span id="auditProfileName" class="font-bold text-slate-900"></span> (<span id="auditProfileId" class="text-blue-600 font-mono"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto scrollbar-hidden border border-slate-200 rounded-xl">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
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
        <button type="button" onclick="closeAuditModal()" class="w-full py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Close Window</button>
      </div>
    </div>
  </div>

  <!-- DIRECT REGISTRATION MODAL -->
  <div id="registerModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="user-plus" class="w-5 h-5 text-blue-600"></i>
          <span>Register New Profile</span>
        </h3>
        <button type="button" onclick="closeRegisterModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="directRegisterForm" onsubmit="handleDirectRegister(event)" class="space-y-4 max-h-[400px] overflow-y-auto pr-2 scrollbar-hidden">
        <!-- Type Selection -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">User Type</label>
          <select id="regType" onchange="toggleDirectRegisterFields(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value="student">Student Profile</option>
            <option value="staff">Staff Profile</option>
          </select>
        </div>

        <!-- Common Fields -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="directRegName" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="directRegEmail" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="name@carmelpoly.edu.in">
          </div>
        </div>

        <!-- Student-Specific Fields -->
        <div id="directStudentFields" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admission Type</label>
              <select id="directRegAdmType" onchange="handleAdmTypeChange()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                <option value="Regular">Regular</option>
                <option value="LET">Lateral Entry (LET)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Adm Year</label>
              <input type="number" id="directRegStudentYear" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" value="2026">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Register No</label>
              <input type="text" id="directRegStudentId" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="e.g. 25EL1001">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Admission No</label>
              <input type="text" id="directRegStudentAdm" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="e.g. ADM25EL01">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Branch</label>
              <input type="text" id="directRegStudentBranch" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-500 focus:outline-none cursor-not-allowed" value="{{ $activeBranch }}">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Semester</label>
              <select id="directRegStudentSem" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
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
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
              <input type="text" id="directRegStaffMobile" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="10-digit number">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Designation</label>
              <select id="directRegStaffDesig" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
                <option value="Lecturer" selected>Lecturer</option>
                <option value="Demonstrator">Demonstrator</option>
                <option value="Physical_Instructor">Physical Instructor</option>
                <option value="Trade_Instructor">Trade Instructor</option>
                <option value="Tradesman">Tradesman</option>
                <option value="Laboratory_Assistant">Laboratory Assistant</option>
                <option value="Workshop_Instructor">Workshop Instructor</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Branch</label>
            <input type="text" id="directRegStaffBranch" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-500 focus:outline-none cursor-not-allowed" value="{{ $activeBranch }}">
          </div>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Password</label>
          <input type="text" id="directRegPassword" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="e.g. 12345">
        </div>

        <div id="directRegAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRegisterModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-2">
            <span>Register Profile</span>
            <div id="directRegSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>
  <!-- SUBJECT MODAL (Add + Edit mode) -->
  <div id="subjectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 id="subjectModalTitle" class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="book-open" class="w-5 h-5 text-emerald-600"></i>
          <span id="subjectModalTitleText">Add Curriculum Subject</span>
        </h3>
        <button type="button" onclick="closeSubjectModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="subjectForm" onsubmit="saveSubject(event)" class="space-y-4">
        <!-- Hidden: tracks which mode we are in. Empty = Add, filled = Edit (holds subject ID) -->
        <input type="hidden" id="modalEditSubjectId" value="">
        <input type="hidden" id="modalFormSubjectBatch">
        <input type="hidden" id="modalFormSubjectSemester">

        <div id="subjectBatchSemRow" class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl mb-2 flex justify-between items-center text-sm">
          <span class="text-slate-600 font-medium">Target Batch: <span id="displaySubjectBatch" class="font-bold text-slate-900"></span></span>
          <span class="text-slate-600 font-medium">Semester: <span id="displaySubjectSemester" class="font-bold text-slate-900"></span></span>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Subject Code</label>
            <div class="flex items-stretch rounded-xl overflow-hidden border border-slate-200 focus-within:border-blue-600 bg-white">
              <span id="subjectCodePrefix" class="hidden items-center px-3 bg-slate-100 text-blue-700 font-bold font-mono text-xs border-r border-slate-200 select-none whitespace-nowrap"></span>
              <input type="text" id="subjectCodeRaw" class="flex-1 bg-transparent px-3 py-2 text-sm text-slate-900 outline-none" placeholder="e.g. ENG101">
            </div>
            <!-- Keep hidden field to maintain integration with save handlers -->
            <input type="hidden" id="subjectCode">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Subject Type</label>
            <select id="subjectType" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
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
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Subject Name</label>
          <input type="text" id="subjectName" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none" placeholder="e.g. Engineering Mathematics">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Syllabus Revision</label>
          <select id="subjectRevisionYear" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
            <option value="REV2026">REV2026 (Current)</option>
            <option value="REV2021">REV2021</option>
            <option value="REV2015">REV2015</option>
            <option value="REV2010">REV2010</option>
          </select>
        </div>

        <div id="subjectAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeSubjectModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Cancel</button>
          <button type="submit" id="subjectSubmitBtn" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-2 shadow-xs">
            <span id="subjectSubmitLabel">Add Subject</span>
            <div id="subjectSpinner" class="hidden w-4 h-4 border-2 border-slate-300 border-t-white rounded-full animate-spin"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ASSIGN STAFF MODAL -->
  <div id="assignStaffModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-lg p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <i data-lucide="user-plus" class="w-5 h-5 text-blue-600"></i>
          <span>Assign Teaching Staff</span>
        </h3>
        <button type="button" onclick="closeAssignStaffModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="assignStaffForm" onsubmit="assignStaff(event)" class="space-y-4">
        <input type="hidden" id="assignSubjectId">
        
        <p class="text-sm text-slate-600">Select one or more staff members to assign to <strong id="assignSubjectName" class="text-slate-900 font-bold"></strong>.</p>
        
        <div>
          <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Branch Filter (For Inter-Department)</label>
          <select id="staffBranchFilter" onchange="renderAssignStaffList()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 focus:border-blue-600 outline-none">
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

        <div class="max-h-[300px] overflow-y-auto custom-scrollbar border border-slate-200 rounded-xl p-2 space-y-1" id="staffCheckboxList">
          <!-- Populated by JS -->
        </div>

        <div id="assignStaffAlert" class="hidden p-3 rounded-xl text-sm font-bold border"></div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeAssignStaffModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-sm text-slate-700 transition-colors cursor-pointer">Cancel</button>
          <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-colors cursor-pointer flex items-center justify-center gap-2 shadow-xs">
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    window.isPrincipalView = @json($isPrincipalMode);
    window.branchOverride = @json($activeBranch);

    if (window.isPrincipalView && window.branchOverride) {
      const originalFetch = window.fetch;
      window.fetch = function(input, init) {
        let url = typeof input === 'string' ? input : input.url;
        if (url.startsWith('/api/')) {
          const separator = url.includes('?') ? '&' : '?';
          url = `${url}${separator}branch=${window.branchOverride}`;
        }
        if (typeof input === 'string') {
          return originalFetch(url, init);
        } else {
          const newRequest = new Request(url, input);
          return originalFetch(newRequest, init);
        }
      };
    }

    let activePanel = @json($initialPanel);
    let selectedUserForReset = null;
    let activeBatchId = null;
    let deptStaffCache = [];

    function handleHodSidebarNav(panelId) {
      switchPanel(panelId);
      if (typeof selectSidebarNav === 'function') {
        selectSidebarNav(panelId);
      }
      try {
        const url = new URL(window.location);
        url.searchParams.set('panel', panelId);
        url.searchParams.delete('tab');
        window.history.replaceState({}, '', url);
      } catch (e) {}
    }
    window.handleHodSidebarNav = handleHodSidebarNav;

    function syncSubjectTypeOptions(revision, preselectedValue = null) {
      const typeSelect = document.getElementById('subjectType');
      if (!typeSelect) return;

      const r21Options = [
        { value: "Theory", text: "Theory" },
        { value: "Practical / Lab", text: "Practical / Lab" },
        { value: "Practicum", text: "Practicum" },
        { value: "Project Based Theory", text: "Project Based Theory" },
        { value: "Seminar", text: "Seminar" },
        { value: "Project", text: "Project" }
      ];

      const r26Options = [
        { value: "Theory Courses", text: "Theory Courses" },
        { value: "Project Based Learning", text: "Project Based Learning (PBL)" },
        { value: "Drawing Courses", text: "Drawing Courses" },
        { value: "Practicum Courses", text: "Practicum Courses" },
        { value: "Practicum Courses under Basic Science & Humanities category", text: "Practicum Courses (Basic Science & Humanities)" },
        { value: "Laboratory/Workshop Courses", text: "Laboratory/Workshop Courses" },
        { value: "Major Project-Phase II", text: "Major Project-Phase II" },
        { value: "Seminar / Minor Project / Major Project-Phase I", text: "Seminar / Minor Project / Major Project-Phase I" },
        { value: "Summer Internship/ Digital 101 Course (Skill Enhancement Course)", text: "Summer Internship/ Digital 101 Course" }
      ];

      typeSelect.innerHTML = '';
      const opts = (revision === 'REV2026') ? r26Options : r21Options;
      opts.forEach(opt => {
        const o = document.createElement('option');
        o.value = opt.value;
        o.textContent = opt.text;
        typeSelect.appendChild(o);
      });

      if (preselectedValue) {
        typeSelect.value = preselectedValue;
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      const urlParams = new URLSearchParams(window.location.search);
      const urlTab = urlParams.get('tab') || urlParams.get('panel') || (window.location.hash ? window.location.hash.replace('#', '') : null);
      if (urlTab && ['batches', 'directory', 'subjects', 'audit', 'leave_ledger', 'prof_activities', 'profile'].includes(urlTab)) {
        activePanel = urlTab;
      }
      switchPanel(activePanel);
      // Pre-load dept staff for batch modals
      loadDeptStaffCache();
      checkTodaySeminars();

      const revEl = document.getElementById('subjectRevisionYear');
      if (revEl) {
        revEl.addEventListener('change', function() {
          syncSubjectTypeOptions(this.value);
        });
      }
    });

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function switchPanel(panelId) {
      if (!panelId) panelId = 'batches';
      activePanel = panelId;
      const panels = ['batches', 'directory', 'subjects', 'audit', 'leave_ledger', 'prof_activities', 'profile'];
      
      panels.forEach(id => {
        let elId = 'panel' + id.charAt(0).toUpperCase() + id.slice(1);
        if (id === 'leave_ledger') elId = 'panelLeave_ledger';
        if (id === 'prof_activities') elId = 'panelProf_activities';
        const el = document.getElementById(elId);
        if (el) {
          if (id === panelId) {
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        }
      });

      if (typeof selectSidebarNav === 'function') {
        selectSidebarNav(panelId);
      }

      const titles = {
        'batches': { title: 'Batch & Class Management', subtitle: 'Manage admission-year batches, class tutors, batch mentors, and semester progression.' },
        'directory': { title: 'User Accounts Directory', subtitle: 'Filter, search, audit, and manage profile lifecycle states for students and staff in your branch.' },
        'subjects': { title: 'Subject & Staff Allocation', subtitle: 'Map curriculum subjects to batches per semester and assign staff across departments.' },
        'audit': { title: 'Department Audit Trail', subtitle: 'Lifecycle events, status updates, registrations, and actions performed within the branch.' },
        'leave_ledger': { title: 'Staff Leave Master Ledger & Report Center', subtitle: 'Multi-stage approval audit trail, departmental leave balances, and official leave orders.' },
        'prof_activities': { title: 'Professional Activities', subtitle: 'Faculty development, publications, workshops, projects, and academic contributions.' },
        'profile': { title: 'My Profile & Security Settings', subtitle: 'Manage your personal account credentials, profile avatar, and view security activity logs.' }
      };

      const info = titles[panelId] || { title: 'Overview', subtitle: '' };
      const titleEl = document.getElementById('panelTitle');
      const subtitleEl = document.getElementById('panelSubtitle');
      if (titleEl) titleEl.innerText = info.title;
      if (subtitleEl) subtitleEl.innerText = info.subtitle;

      if (panelId === 'batches') loadBatches();
      if (panelId === 'directory') loadUsers();
      if (panelId === 'subjects') loadBatchesForSubjects();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'leave_ledger') loadLeaveLedger();
      if (panelId === 'prof_activities') {
        loadProfActivities();
        toggleProfActFields('fdp_attended');
      }
      if (panelId === 'profile') loadSelfSecurityLogs();

      if (window.initLucide) window.initLucide();
    }
    window.switchPanel = switchPanel;

    function loadBatchesForSubjects() {
      // Just populate the dropdown if it's empty
      const select = document.getElementById('subjectBatchSelect');
      if (select && select.options.length > 1) {
        // Already loaded, just refresh the subjects table
        loadSubjects();
        return;
      }
      
      const p1 = fetch('/api/hod/batches').then(res => res.json()).catch(() => ({status: 'ERROR', batches: []}));
      const p2 = fetch('/api/r26/hod/batches').then(res => res.json()).catch(() => ({status: 'ERROR', batches: []}));

      Promise.all([p1, p2])
        .then(([res1, res2]) => {
          select.innerHTML = '<option value="">-- Choose a Classroom --</option>';
          let b1 = (res1.status === 'SUCCESS' && Array.isArray(res1.batches)) ? res1.batches : [];
          let b2 = (res2.status === 'SUCCESS' && Array.isArray(res2.batches)) ? res2.batches : [];
          let combined = b1.concat(b2);
          
          combined.sort((x, y) => y.batch_year - x.batch_year);

          combined.forEach(b => {
            select.innerHTML += `<option value="${b.classroom_id}">${b.classroom_id} (Year ${b.batch_year})${b.is_r26 || b.batch_year === 2026 ? ' [REV2026]' : ''}</option>`;
          });
        });
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border-red-900 block shadow-sm";
      } else {
        alert.className = "p-4 rounded-xl text-sm font-bold bg-green-950/40 text-green-400 border-green-900 block shadow-sm";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      const search = document.getElementById('filterSearch')?.value || '';
      const role = document.getElementById('filterRole')?.value || '';
      const status = document.getElementById('filterStatus')?.value || '';
      const branch = '{{ $activeBranch }}';

      const url = `/api/admin/users?search=${encodeURIComponent(search)}&role=${role}&status=${status}&branch=${encodeURIComponent(branch)}`;

      fetch(url)
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            renderUsersGrid(data.users);
          }
        })
        .catch(() => {
          if (indicator) indicator.classList.add('hidden');
        });
    }

    function renderUsersGrid(users) {
      const tbody = document.getElementById('usersTableBody');
      tbody.innerHTML = "";

      if (users.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="8" class="p-12 text-center text-slate-500 font-medium text-sm">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                <i data-lucide="users" class="w-5 h-5"></i>
              </div>
              <p class="font-semibold text-slate-800">No matching profiles found</p>
              <p class="text-xs text-slate-400 mt-0.5">Try adjusting your search filters above.</p>
            </td>
          </tr>
        `;
        if (window.initLucide) window.initLucide();
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-100 hover:bg-slate-50/70 transition-colors";

        let statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>`;
        if (user.status === 'Approved') {
          statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Approved</span>`;
        } else if (user.status === 'Suspended') {
          statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">Suspended</span>`;
        }

        let toggleButton = '';
        if (user.id !== "{{ session('userId') }}") {
          if (user.status === 'Pending') {
            toggleButton = `
              <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                Approve
              </button>
            `;
          } else if (user.status === 'Approved') {
            toggleButton = `
              <button onclick="changeStatus('${user.id}', '${user.type}', 'Suspended')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                Suspend
              </button>
            `;
          } else if (user.status === 'Suspended') {
            toggleButton = `
              <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                Activate
              </button>
            `;
          }
        }

        let roleCol = user.role;

        tr.innerHTML = `
          <td class="p-3.5 flex items-center gap-3">
            <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-9 h-9 rounded-full object-cover border border-slate-200 shadow-2xs shrink-0">
            <div class="min-w-0">
              <span class="font-semibold text-slate-900 block text-sm truncate">${user.name}</span>
              <span class="text-xs text-slate-500 block truncate">${user.email}</span>
            </div>
          </td>
          <td class="p-3.5 font-mono font-medium text-slate-700 text-sm whitespace-nowrap">${user.id}</td>
          <td class="p-3.5"><span class="font-mono font-semibold text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md border border-slate-200">${user.branch}</span></td>
          <td class="p-3.5">
            ${user.type === 'student' ? `
              <button onclick="editStudentSemester('${user.id}', '${user.semester || 'S1'}')" class="text-blue-600 hover:text-blue-800 font-semibold text-sm cursor-pointer underline" title="Click to Edit Semester">
                ${user.semester || 'S1'}
              </button>
              <button onclick="editStudentBatch('${user.id}', '${user.classroom_id || ''}')" class="text-slate-600 hover:text-slate-900 font-medium text-xs ml-2 px-1.5 py-0.5 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-md cursor-pointer transition-colors" title="Move Batch">
                Move
              </button>
            ` : '<span class="text-slate-400 font-medium text-sm">—</span>'}
          </td>
          <td class="p-3.5 text-sm text-slate-700 font-medium whitespace-nowrap">${roleCol}</td>
          <td class="p-3.5 text-sm">${statusBadge}</td>
          <td class="p-3.5">
            ${user.type === 'student' ? `
              <select onchange="updateAcademicStatusDirectly('${user.id}', this.value)" class="bg-white border rounded-lg px-2.5 py-1 text-sm outline-none focus:border-blue-600 font-semibold cursor-pointer transition-colors ${
                user.academic_status === 'Active' ? 'text-emerald-700 bg-emerald-50/50 border-emerald-200' :
                user.academic_status === 'Discontinued' ? 'text-amber-700 bg-amber-50/50 border-amber-200' :
                'text-rose-700 bg-rose-50/50 border-rose-200'
              }">
                <option value="Active" ${user.academic_status === 'Active' ? 'selected' : ''}>Active</option>
                <option value="Discontinued" ${user.academic_status === 'Discontinued' ? 'selected' : ''}>Discontinued</option>
                <option value="TC Issued" ${user.academic_status === 'TC Issued' ? 'selected' : ''}>TC Issued</option>
              </select>
            ` : '<span class="text-slate-400 font-medium text-sm">—</span>'}
          </td>
          <td class="p-3.5 text-right space-x-1.5 text-sm whitespace-nowrap">
            ${toggleButton}
            <button onclick="triggerPasswordReset('${user.id}', '${user.type}', '${user.name}')" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
              Reset Pwd
            </button>
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold transition-colors cursor-pointer" title="View Audit Trail">
              Audit
            </button>
            ${user.id !== "{{ session('userId') }}" ? `
            <button onclick="confirmDeleteUser('${user.id}', '${user.type}', '${user.name}')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer" title="Delete User">
              Delete
            </button>` : ''}
          </td>
        `;
        tbody.appendChild(tr);
      });
      if (window.initLucide) window.initLucide();
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

    function editStudentSemester(regNo, currentSem) {
      let newSemStr = prompt("Enter new Semester (1-6) for student " + regNo + ":", currentSem.replace('S', ''));
      if (newSemStr === null) return;
      let newSem = parseInt(newSemStr);
      if (isNaN(newSem) || newSem < 1 || newSem > 6) {
        alert("Invalid semester! Please enter a number between 1 and 6.");
        return;
      }
      
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      
      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ semester: newSem })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student semester updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => indicator.classList.add('hidden'));
    }

    function editStudentBatch(regNo, currentBatch) {
      let newBatch = prompt("Enter new Classroom ID (Batch) for student " + regNo + ":", currentBatch || '');
      if (newBatch === null) return;
      newBatch = newBatch.trim();
      if (!newBatch) return;

      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ classroom_id: newBatch })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student batch updated successfully.');
          loadUsers();
          if (typeof activeBatchId !== 'undefined' && activeBatchId) {
             loadBatchRoster(activeBatchId);
          }
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
      });
    }

    function updateAcademicStatusDirectly(regNo, newVal) {
      let note = prompt("Enter remarks / reason for changing enrollment status to " + newVal + " (optional):");
      if (note === null) {
        loadUsers(); // User clicked cancel, refresh to restore dropdown selection
        return;
      }

      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ academic_status: newVal, status_notes: note })
      })
      .then(res => res.json())
      .then(data => {
        if (indicator) indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student enrollment status updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
          loadUsers(); // refresh to reset selection
        }
      })
      .catch(() => {
        if (indicator) indicator.classList.add('hidden');
        loadUsers();
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
        pwdAlert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block";
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
          pwdAlert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          pwdAlert.innerText = data.message;
          pwdAlert.classList.remove('hidden');
        }
      })
      .catch(() => {
        pwdAlert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Request failed.";
        pwdAlert.classList.remove('hidden');
      });
    }

    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm"><div class="w-4 h-4 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin mx-auto mb-2"></div>Querying department audit logs...</td></tr>`;

      fetch('/api/audit-logs?branch={{ $activeBranch }}')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `
                <tr>
                  <td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                      <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <p class="font-semibold text-slate-800">No department audit logs found</p>
                    <p class="text-xs text-slate-400 mt-0.5">No administrative activity records exist for this branch yet.</p>
                  </td>
                </tr>
              `;
              if (window.initLucide) window.initLucide();
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-100 hover:bg-slate-50/70 transition-colors";
              
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3.5 text-slate-500 font-mono text-xs whitespace-nowrap">${date}</td>
                <td class="p-3.5 font-medium">
                  <span class="font-semibold text-slate-900 text-sm block">${log.performed_by_name || 'System'}</span>
                  <span class="text-xs text-slate-500 font-mono">${log.performed_by || ''}</span>
                </td>
                <td class="p-3.5 font-medium">
                  <span class="font-semibold text-slate-900 text-sm block">${log.target_name || '—'}</span>
                  <span class="text-xs text-blue-600 font-mono">${log.target_id || ''}</span>
                </td>
                <td class="p-3.5"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">${log.action}</span></td>
                <td class="p-3.5 font-mono text-slate-500 text-xs">${log.ip_address || '—'}</td>
                <td class="p-3.5 text-slate-600 text-sm leading-relaxed">${log.details || '—'}</td>
              `;
              tbody.appendChild(tr);
            });
            if (window.initLucide) window.initLucide();
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-600 font-semibold text-sm">Error loading audit logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-rose-600 font-semibold text-sm">Request failed.</td></tr>`;
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
              tr.className = "border-b border-slate-800/40 text-sm";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3 font-semibold text-slate-300">${log.performed_by_name || 'System'}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-sm font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
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
          alert.className = "p-3 rounded-xl text-sm font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "User registered successfully.";
          alert.classList.remove('hidden');
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
          }, 1500);
        } else {
          alert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
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
        ? 'p-4 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block'
        : 'p-4 rounded-xl text-sm font-bold bg-green-950/40 text-green-400 border border-green-900 block';
      el.innerText = msg;
      el.classList.remove('hidden');
      setTimeout(() => el.classList.add('hidden'), 5000);
    }

    let currentBatchFilter = 'active';

    function loadBatches(status = 'active') {
      currentBatchFilter = status;
      const grid = document.getElementById('batchCardsGrid');
      const empty = document.getElementById('batchEmptyState');
      grid.innerHTML = `
        <div class="col-span-full flex items-center justify-center py-16 text-sm">
          <div class="flex items-center gap-3 text-slate-600 text-sm font-medium bg-white px-5 py-3 rounded-2xl border border-slate-200 shadow-xs">
            <div class="w-4 h-4 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin"></div>
            <span>Loading department batches...</span>
          </div>
        </div>
      `;
      empty.classList.add('hidden');

      // Update toggle UI with Design System pill styling
      const btnActive = document.getElementById('btnHodFilterActive');
      const btnHist = document.getElementById('btnHodFilterHistorical');
      if (status === 'active') {
        if (btnActive) btnActive.className = 'px-3.5 py-1.5 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-xs border border-slate-200/60 cursor-pointer';
        if (btnHist) btnHist.className = 'px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-600 hover:text-slate-900 cursor-pointer';
      } else {
        if (btnHist) btnHist.className = 'px-3.5 py-1.5 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-xs border border-slate-200/60 cursor-pointer';
        if (btnActive) btnActive.className = 'px-3.5 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-600 hover:text-slate-900 cursor-pointer';
      }

      const p1 = fetch(`/api/hod/batches?status=${status}`).then(r => r.json()).catch(() => ({status: 'ERROR', batches: []}));
      const p2 = fetch(`/api/r26/hod/batches?status=${status}`).then(r => r.json()).catch(() => ({status: 'ERROR', batches: []}));

      Promise.all([p1, p2])
        .then(([res1, res2]) => {
          grid.innerHTML = '';
          let b1 = (res1.status === 'SUCCESS' && Array.isArray(res1.batches)) ? res1.batches : [];
          let b2 = (res2.status === 'SUCCESS' && Array.isArray(res2.batches)) ? res2.batches : [];
          
          let combined = b1.concat(b2);
          
          // sort by batch_year desc, then classroom_id asc
          combined.sort((x, y) => {
            if (y.batch_year !== x.batch_year) {
              return y.batch_year - x.batch_year;
            }
            return x.classroom_id.localeCompare(y.classroom_id);
          });

          if (combined.length === 0) {
            empty.classList.remove('hidden');
            return;
          }
          combined.forEach(batch => renderBatchCard(batch));
          if (window.initLucide) window.initLucide();
        })
        .catch(() => {
          grid.innerHTML = `<div class="col-span-full p-8 text-center text-rose-600 font-semibold text-sm bg-white rounded-2xl border border-rose-100 shadow-xs">Failed to load department batches.</div>`;
        });
    }

    function renderBatchCard(batch) {
      const grid = document.getElementById('batchCardsGrid');
      
      const isLetBatch = batch.classroom_id.includes('_LET');
      const isR26 = batch.is_r26 || batch.batch_year === 2026;

      const card = document.createElement('div');
      card.className = isR26
        ? `bg-white border-2 border-emerald-500/80 rounded-2xl p-6 transition-all hover:shadow-md flex flex-col justify-between min-h-[220px] w-full relative shadow-xs`
        : `bg-white border border-slate-200/90 rounded-2xl p-6 transition-all hover:shadow-md hover:border-slate-300 flex flex-col justify-between min-h-[220px] w-full relative shadow-xs`;

      const tutorHtml = batch.tutor_name
        ? `<div class="flex items-center gap-2.5 text-sm"><div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0"><i data-lucide="user-check" class="w-4 h-4 text-blue-600"></i></div><div class="min-w-0"><span class="text-xs text-slate-400 block font-medium">Class Tutor</span><span class="text-slate-900 font-semibold truncate block text-sm" title="${batch.tutor_name}">${batch.tutor_name}</span></div></div>`
        : `<div class="flex items-center gap-2.5 text-sm"><div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0"><i data-lucide="user-x" class="w-4 h-4 text-slate-400"></i></div><div><span class="text-xs text-slate-400 block font-medium">Class Tutor</span><span class="text-slate-400 italic text-sm">Not assigned</span></div></div>`;

      const mentorHtml = batch.mentor_name
        ? `<div class="flex items-center gap-2.5 text-sm"><div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0"><i data-lucide="heart-handshake" class="w-4 h-4 text-emerald-600"></i></div><div class="min-w-0"><span class="text-xs text-slate-400 block font-medium">Class Mentor</span><span class="text-slate-900 font-semibold truncate block text-sm" title="${batch.mentor_name}">${batch.mentor_name}</span></div></div>`
        : `<div class="flex items-center gap-2.5 text-sm"><div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0"><i data-lucide="user-x" class="w-4 h-4 text-slate-400"></i></div><div><span class="text-xs text-slate-400 block font-medium">Class Mentor</span><span class="text-slate-400 italic text-sm">Not assigned</span></div></div>`;

      card.innerHTML = `
        <div class="space-y-4">
          <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2">
              <span class="px-2.5 py-1 border rounded-lg font-mono text-sm font-bold bg-slate-100 text-slate-800 border-slate-200/80 whitespace-nowrap">${batch.classroom_id}</span>
              ${batch.classroom_id.includes('_LET') ? `<span class="bg-purple-50 border border-purple-200 text-purple-700 font-bold text-xs px-2.5 py-0.5 rounded uppercase select-none whitespace-nowrap">LET</span>` : ''}
              ${isR26 ? `<span class="bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-xs px-2.5 py-0.5 rounded uppercase select-none tracking-wide whitespace-nowrap">Revision 2026</span>` : ''}
            </div>
            <div class="shrink-0">
              ${(batch.current_semester || 1) > 6
                ? `<span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold text-sm tracking-wide flex items-center gap-1 select-none whitespace-nowrap"><i data-lucide="graduation-cap" class="w-4 h-4 text-emerald-600"></i>Graduated</span>`
                : `<span onclick="event.stopPropagation(); changeBatchSemesterPrompt('${batch.classroom_id}', ${batch.current_semester || 1})" class="px-3 py-1 bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 rounded-xl font-bold text-sm tracking-wide cursor-pointer shadow-2xs select-none transition-colors whitespace-nowrap" title="Click to Change Batch Semester">Semester ${batch.current_semester || 1}</span>`
              }
            </div>
          </div>
          
          <div>
            <h4 class="font-bold text-lg text-slate-900">Admission ${batch.batch_year}${isLetBatch ? ' (LET)' : ''}</h4>
            <p class="text-sm text-slate-500">${batch.batch_year} – ${batch.batch_year + 3} ${isLetBatch ? 'Lateral Entry ' : ''}Batch</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 border-t border-slate-100 pt-3.5">
            ${tutorHtml}
            ${mentorHtml}
          </div>
        </div>

        <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-4">
          <div class="flex items-baseline gap-1.5">
            <span class="text-xl font-bold text-slate-900">${batch.student_count}</span>
            <span class="text-sm text-slate-500 font-medium">students enrolled</span>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="openBatchDetail(${JSON.stringify(batch).replace(/"/g, '&quot;')})" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition-colors cursor-pointer shadow-xs">
              <i data-lucide="settings" class="w-4 h-4 text-slate-300"></i>
              <span>Manage Batch</span>
            </button>
          </div>
        </div>
      `;

      grid.appendChild(card);
    }

    function changeBatchSemesterPrompt(classroomId, currentSem) {
      let newSemStr = prompt("Enter active Semester (1-8) for batch " + classroomId + ":", currentSem);
      if (newSemStr === null) return;
      let newSem = parseInt(newSemStr);
      if (isNaN(newSem) || newSem < 1 || newSem > 8) {
        alert("Invalid semester! Please enter a number between 1 and 8.");
        return;
      }

      fetch(`/api/hod/batches/${classroomId}/update-semester`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ current_semester: newSem })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Batch current semester updated successfully.');
          loadBatches(currentBatchFilter);
        } else {
          showGlobalMessage(data.message, true);
        }
      });
    }

    function toggleBatchCreationLetView() {
      const isLet = document.getElementById('batchTypeSelect').value === 'LET';
      const startSemesterContainer = document.getElementById('batchStartSemesterContainer');
      const tutorContainer = document.getElementById('batchTutorContainer');
      const mentorContainer = document.getElementById('batchMentorContainer');

      if (isLet) {
        startSemesterContainer.classList.add('hidden');
        tutorContainer.classList.add('hidden');
        mentorContainer.classList.add('hidden');
      } else {
        startSemesterContainer.classList.remove('hidden');
        tutorContainer.classList.remove('hidden');
        mentorContainer.classList.remove('hidden');
      }
    }

    function openCreateBatchModal() {
      document.getElementById('createBatchAlert').classList.add('hidden');
      document.getElementById('batchAdmYear').value = new Date().getFullYear();
      document.getElementById('batchTypeSelect').value = 'Regular';
      toggleBatchCreationLetView();
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
      const isLet = document.getElementById('batchTypeSelect').value === 'LET';
      const year = parseInt(document.getElementById('batchAdmYear').value) || new Date().getFullYear();
      const branch = '{{ session("userBranch") }}';
      if (isLet) {
        const baseYear = year - 1;
        document.getElementById('batchIdPreview').innerText = `${branch}_${baseYear}_${baseYear + 3}_LET`;
      } else {
        document.getElementById('batchIdPreview').innerText = `${branch}_${year}_${year + 3}`;
      }
    }

    function submitCreateBatch() {
      const spinner = document.getElementById('createBatchSpinner');
      const alertEl = document.getElementById('createBatchAlert');
      const isLet = document.getElementById('batchTypeSelect').value === 'LET';
      const year = document.getElementById('batchAdmYear').value;

      if (!year) {
        alertEl.className = 'p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Please enter an admission year.';
        alertEl.classList.remove('hidden');
        return;
      }

      let payload = {
        is_lateral_entry: isLet,
        admission_year: parseInt(year)
      };

      if (!isLet) {
        const tutor = document.getElementById('batchTutorSelect').value;
        const mentor = document.getElementById('batchMentorSelect').value;
        const semester = document.getElementById('batchStartSemesterSelect').value;
        payload.tutor_mobile_no = tutor || null;
        payload.mentor_mobile_no = mentor || null;
        payload.current_semester = parseInt(semester);
      }

      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      const url = (parseInt(year) === 2026) ? '/api/r26/hod/batches' : '/api/hod/batches';
      fetch(url, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl text-sm font-bold bg-green-950/40 text-green-400 border border-green-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
          setTimeout(() => {
            closeCreateBatchModal();
            loadBatches();
          }, 1800);
        } else {
          alertEl.className = 'p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-3 rounded-xl text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Request failed.';
        alertEl.classList.remove('hidden');
      });
    }

    function openBatchDetail(batch) {
      activeBatchId = batch.classroom_id;
      switchBatchTab('tutorMentor'); // Reset to default tab

      document.getElementById('batchDetailTitle').innerText = `Batch ${batch.classroom_id}`;
      document.getElementById('batchDetailSubtitle').innerText = `Admission ${batch.batch_year} · ${batch.batch_year} - ${batch.batch_year + 3} Batch`;

      // Show current tutor/mentor
      document.getElementById('tutorCurrentDisplay').innerHTML = batch.tutor_name
        ? `<span class="font-bold text-sky-300">${batch.tutor_name}</span> <span class="text-slate-600 text-sm">(${batch.tutor_mobile_no})</span>`
        : '<span class="italic text-slate-600">Not assigned yet</span>';

      document.getElementById('mentorCurrentDisplay').innerHTML = batch.mentor_name
        ? `<span class="font-bold text-emerald-300">${batch.mentor_name}</span> <span class="text-slate-600 text-sm">(${batch.mentor_mobile_no})</span>`
        : '<span class="italic text-slate-600">Not assigned yet</span>';

      // Clear alerts
      document.getElementById('assignTutorAlert').classList.add('hidden');
      document.getElementById('assignMentorAlert').classList.add('hidden');

      // Populate dropdowns
      populateStaffDropdowns();

      // Pre-select current tutor/mentor
      if (batch.tutor_mobile_no) document.getElementById('detailTutorSelect').value = batch.tutor_mobile_no;
      if (batch.mentor_mobile_no) document.getElementById('detailMentorSelect').value = batch.mentor_mobile_no;

      // Show Graduate button ONLY for S6 batches (final semester)
      const graduateBtn = document.getElementById('btnGraduateBatch');
      if (graduateBtn) {
        if ((batch.current_semester || 1) === 6) {
          graduateBtn.classList.remove('hidden');
        } else {
          graduateBtn.classList.add('hidden');
        }
      }

      // Always show Delete Batch button for HOD
      const deleteBtn = document.getElementById('btnDeleteBatch');
      if (deleteBtn) deleteBtn.classList.remove('hidden');

      const modal = document.getElementById('batchDetailModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeBatchDetailModal() {
      const modal = document.getElementById('batchDetailModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      activeBatchId = null;
      // Hide graduate & delete buttons on close
      const graduateBtn = document.getElementById('btnGraduateBatch');
      if (graduateBtn) graduateBtn.classList.add('hidden');
      const deleteBtn = document.getElementById('btnDeleteBatch');
      if (deleteBtn) deleteBtn.classList.add('hidden');
    }

    // ============================================================
    // NEW: Graduate / Archive Batch — purely additive
    // ============================================================
    function confirmGraduateBatch() {
      if (!activeBatchId) return;
      const title = document.getElementById('batchDetailTitle').innerText;
      const confirmed = confirm(
        `Graduate / Archive Batch: ${title}\n\n` +
        `This will:\n` +
        `  • Set the batch status to Graduated (moves to Previous Batches)\n` +
        `  • Mark all Active students as Graduated\n\n` +
        `All historical data (attendance, marks, subjects) will remain accessible\n` +
        `in the Semester History tab.\n\n` +
        `Proceed?`
      );
      if (confirmed) doGraduateBatch();
    }

    function doGraduateBatch() {
      if (!activeBatchId) return;
      const btn = document.getElementById('btnGraduateBatch');
      if (btn) { btn.disabled = true; btn.innerText = 'Archiving...'; }

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/graduate`, {
        method: 'PUT',
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">school</span> Graduate / Archive Batch'; }
        if (data.status === 'SUCCESS') {
          showGlobalMessage(`Batch graduated successfully. ${data.students_graduated} student(s) marked as Graduated.`);
          closeBatchDetailModal();
          loadBatches('historical'); // switch to Previous Batches so HOD sees the card there
        } else {
          alert(data.message || 'Failed to graduate batch.');
        }
      })
      .catch(() => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">school</span> Graduate / Archive Batch'; }
        alert('Request failed. Please try again.');
      });
    }
    // ============================================================
    // END: Graduate / Archive Batch
    // ============================================================

    // ============================================================
    // DELETE BATCH
    // ============================================================
    function confirmDeleteBatch() {
      if (!activeBatchId) return;
      const title = document.getElementById('batchDetailTitle').innerText;
      const confirmed = confirm(
        `⚠️ DELETE BATCH: ${title}\n\n` +
        `This will PERMANENTLY delete:\n` +
        `  • The batch record\n` +
        `  • All allocated subjects\n` +
        `  • All staff assignments for this batch\n\n` +
        `NOTE: Batches with enrolled students CANNOT be deleted.\n\n` +
        `This action CANNOT be undone. Proceed?`
      );
      if (confirmed) doDeleteBatch();
    }

    function doDeleteBatch() {
      if (!activeBatchId) return;
      const btn = document.getElementById('btnDeleteBatch');
      if (btn) { btn.disabled = true; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">hourglass_empty</span> Deleting...'; }

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}`, {
        method: 'DELETE',
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">delete_forever</span> Delete Batch'; }
        if (data.status === 'SUCCESS') {
          showGlobalMessage(data.message || 'Batch deleted successfully.');
          closeBatchDetailModal();
          loadBatches();
        } else {
          alert(data.message || 'Failed to delete batch.');
        }
      })
      .catch(() => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded" style="font-size:15px">delete_forever</span> Delete Batch'; }
        alert('Request failed. Please try again.');
      });
    }
    // ============================================================
    // END: Delete Batch
    // ============================================================

    // ============================================================
    // NEW: SEMESTER HISTORY TAB — purely additive, no existing functions modified
    // ============================================================

    function _ensureSemesterHistoryPanel() {
      const flexContainer = document.querySelector('#batchDetailModal .flex-grow.overflow-y-auto');
      if (!flexContainer) return;
      let panel = document.getElementById('batchTab_semesterHistory');
      if (!panel) {
        panel = document.createElement('div');
        panel.id = 'batchTab_semesterHistory';
        panel.className = 'space-y-5';
        panel.innerHTML = `
          <!-- Semester Selector -->
          <div class="flex items-center gap-2 flex-wrap">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-1">Select Semester:</span>
            ${[1,2,3,4,5,6].map(s => `
              <button id="semHistBtn_${s}" onclick="loadSemesterSnapshot(activeBatchId, ${s})"
                class="px-3.5 py-1.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:border-blue-600 hover:text-blue-600 transition-colors cursor-pointer bg-white shadow-2xs">
                Semester ${s}
              </button>
            `).join('')}
          </div>

          <!-- Content area -->
          <div id="semHistContent">
            <div class="p-10 text-center text-slate-500 text-sm">Select a semester above to view its academic data.</div>
          </div>
        `;
        flexContainer.appendChild(panel);
      }
      panel.classList.remove('hidden');
    }

    function loadSemesterSnapshot(classroomId, semester) {
      if (!classroomId) return;

      // Highlight active semester button
      for (let s = 1; s <= 6; s++) {
        const btn = document.getElementById('semHistBtn_' + s);
        if (btn) {
          btn.className = s === semester
            ? 'px-3.5 py-1.5 rounded-xl text-sm font-semibold border border-blue-600 text-blue-600 transition-colors cursor-pointer bg-blue-50/60 shadow-2xs'
            : 'px-3.5 py-1.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:border-blue-600 hover:text-blue-600 transition-colors cursor-pointer bg-white shadow-2xs';
        }
      }

      const content = document.getElementById('semHistContent');
      content.innerHTML = `<div class="p-10 text-center text-slate-500 text-sm flex items-center justify-center gap-3"><div class="w-4 h-4 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin"></div> Loading Semester ${semester} data...</div>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/semester/${semester}/snapshot`, {
        headers: getHeaders()
      })
      .then(r => r.json())
      .then(data => {
        if (data.status !== 'SUCCESS') {
          content.innerHTML = `<div class="p-8 text-center text-rose-600 font-semibold text-sm">${data.message || 'Failed to load semester data.'}</div>`;
          return;
        }
        _renderSemesterSnapshot(data, semester);
      })
      .catch(() => {
        content.innerHTML = `<div class="p-8 text-center text-rose-600 font-semibold text-sm">Error fetching semester data.</div>`;
      });
    }

    function _renderSemesterSnapshot(data, semester) {
      const content = document.getElementById('semHistContent');

      // ---- Section 1: Subjects & Staff Log ----
      let subjectsHtml = '';
      if (data.subjects && data.subjects.length > 0) {
        const rows = data.subjects.map(s => `
          <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">
            <td class="p-3 font-mono text-slate-900 font-bold text-sm">${s.subject_code}</td>
            <td class="p-3 font-semibold text-slate-900 text-sm">${s.subject_name}</td>
            <td class="p-3 text-slate-600 text-sm">${s.subject_type}</td>
            <td class="p-3 text-sm">${s.staff.length > 0 ? s.staff.map(n => `<span class="block text-slate-900 font-medium">${n}</span>`).join('') : '<span class="text-rose-600 font-semibold">Unassigned</span>'}</td>
            <td class="p-3 text-center text-sm font-bold text-blue-600">${s.classes_conducted}</td>
            <td class="p-3 text-center text-sm ${s.course_file_status === 'Submitted' ? 'text-emerald-600' : 'text-amber-600'} font-semibold">${s.course_file_status}</td>
          </tr>
        `).join('');
        subjectsHtml = `
          <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs mb-4">
            <div class="p-3.5 bg-slate-50 border-b border-slate-200/80 flex items-center gap-2">
              <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
              <span class="font-bold text-slate-900 text-sm">Subjects &amp; Staff Log — Semester ${semester}</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead><tr class="bg-slate-50/90 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-3">Code</th><th class="p-3">Subject</th><th class="p-3">Type</th>
                  <th class="p-3">Assigned Staff</th><th class="p-3 text-center">Classes Taken</th><th class="p-3 text-center">Course File</th>
                </tr></thead>
                <tbody>${rows}</tbody>
              </table>
            </div>
          </div>`;
      } else {
        subjectsHtml = `<div class="p-6 bg-slate-50 border border-slate-200/80 rounded-2xl text-slate-500 text-sm italic mb-4">No subjects found for Semester ${semester}.</div>`;
      }

      // ---- Section 2: Student Attendance ----
      let attendanceHtml = '';
      if (data.students && data.students.length > 0) {
        const rows = data.students.map(s => {
          const pct = s.overall_attendance_percent ?? '—';
          const pctClass = pct === '—' ? 'text-slate-500' : (pct >= 75 ? 'text-emerald-600' : (pct >= 60 ? 'text-amber-600' : 'text-rose-600'));
          const bySubj = s.subject_attendance && s.subject_attendance.length > 0
            ? s.subject_attendance.map(a => `<span class="text-xs text-slate-600">${a.subject_code}: <span class="font-bold ${a.percent >= 75 ? 'text-emerald-600' : a.percent >= 60 ? 'text-amber-600' : 'text-rose-600'}">${a.percent}%</span></span>`).join(' &nbsp;|&nbsp; ')
            : '<span class="text-slate-400 text-xs">No logs</span>';
          return `
            <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">
              <td class="p-3 text-slate-600 text-sm font-mono">${s.roll_no || '—'}</td>
              <td class="p-3 font-semibold text-slate-900 text-sm">${s.name}</td>
              <td class="p-3 text-center font-bold text-sm ${pctClass}">${pct !== '—' ? pct + '%' : '—'}</td>
              <td class="p-3 text-sm">${bySubj}</td>
              <td class="p-3 text-center"><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${
                s.academic_status === 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600'
              }">${s.academic_status}</span></td>
            </tr>`;
        }).join('');
        attendanceHtml = `
          <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs mb-4">
            <div class="p-3.5 bg-slate-50 border-b border-slate-200/80 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
                <span class="font-bold text-slate-900 text-sm">Student Attendance — Semester ${semester}</span>
              </div>
              <span class="text-xs text-slate-500 font-medium">${data.students.length} students</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead><tr class="bg-slate-50/90 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-3">Roll No</th><th class="p-3">Name</th>
                  <th class="p-3 text-center">Overall %</th><th class="p-3">Subject-wise</th><th class="p-3 text-center">Status</th>
                </tr></thead>
                <tbody>${rows}</tbody>
              </table>
            </div>
          </div>`;
      } else {
        attendanceHtml = `<div class="p-6 bg-slate-50 border border-slate-200/80 rounded-2xl text-slate-500 text-sm italic mb-4">No student data found for Semester ${semester}.</div>`;
      }

      // ---- Section 3: Board Results / Marks ----
      let marksHtml = '';
      if (data.board_results && data.board_results.length > 0) {
        const rows = data.board_results.map(s => `
          <tr class="border-b border-slate-100 hover:bg-slate-50/70 transition-colors">
            <td class="p-3 text-slate-600 text-sm font-mono">${s.roll_no || '—'}</td>
            <td class="p-3 font-semibold text-slate-900 text-sm">${s.name}</td>
            <td class="p-3 text-center font-semibold text-sm ${s.result === 'Pass' ? 'text-emerald-600' : s.result === 'Fail' ? 'text-rose-600' : 'text-slate-500'}">${s.result || '—'}</td>
            <td class="p-3 text-center font-bold text-sm text-amber-600">${s.sgpa || '—'}</td>
            <td class="p-3 text-center text-slate-700 text-sm">${s.board_marks || '—'}</td>
          </tr>
        `).join('');
        marksHtml = `
          <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="p-3.5 bg-slate-50 border-b border-slate-200/80 flex items-center gap-2">
              <i data-lucide="award" class="w-4 h-4 text-amber-600"></i>
              <span class="font-bold text-slate-900 text-sm">Board Results — Semester ${semester}</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead><tr class="bg-slate-50/90 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                  <th class="p-3">Roll No</th><th class="p-3">Name</th>
                  <th class="p-3 text-center">Result</th><th class="p-3 text-center">SGPA</th><th class="p-3 text-center">Board Marks</th>
                </tr></thead>
                <tbody>${rows}</tbody>
              </table>
            </div>
          </div>`;
      } else {
        marksHtml = `<div class="p-6 bg-slate-50 border border-slate-200/80 rounded-2xl text-slate-500 text-sm italic">Board results not yet entered for Semester ${semester}.</div>`;
      }

      content.innerHTML = subjectsHtml + attendanceHtml + marksHtml;
      if (window.initLucide) window.initLucide();
    }

    function switchBatchTab(tab) {
      const tabs = ['tutorMentor', 'timetable', 'semesterHistory'];
      tabs.forEach(t => {
        const el = document.getElementById('batchTab_' + t);
        const btn = document.getElementById('tabBtn_' + t);
        if (el) {
          el.classList.add('hidden');
          el.classList.remove('block');
        }
        if (btn) {
          btn.className = "pb-3 text-sm font-semibold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-colors cursor-pointer whitespace-nowrap";
        }
      });

      const targetBtn = document.getElementById('tabBtn_' + tab);
      if (targetBtn) {
        targetBtn.className = "pb-3 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 transition-colors cursor-pointer whitespace-nowrap";
      }

      if (tab === 'semesterHistory') {
        _ensureSemesterHistoryPanel();
      } else {
        const semPanel = document.getElementById('batchTab_semesterHistory');
        if (semPanel) semPanel.classList.add('hidden');
        const targetEl = document.getElementById('batchTab_' + tab);
        if (targetEl) {
          targetEl.classList.remove('hidden');
          targetEl.classList.add('block');
        }
      }

      if (tab === 'timetable') {
        loadTimetable();
      }
    }


    let currentTimetableData = {};
    let currentAllocatedSubjects = [];

    function loadTimetable() {
      if (!activeBatchId) return;
      
      const sem = document.getElementById('modalSubjectSemester') ? document.getElementById('modalSubjectSemester').value : 1;
      
      const displayBody = document.getElementById('timetableDisplayBody');
      if (displayBody) displayBody.innerHTML = '<tr><td colspan="8" class="p-8 text-center text-slate-500">Loading timetable...</td></tr>';
      
      toggleTimetableEdit(false);

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/subjects?semester=${sem}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentAllocatedSubjects = data.subjects || [];
            return fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/timetable`);
          } else {
            throw new Error(data.message || 'Failed to load batch subjects');
          }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentTimetableData = data.timetable || {};
            renderTimetable();
          } else {
            throw new Error(data.message || 'Failed to load timetable');
          }
        })
        .catch(err => {
          if (displayBody) displayBody.innerHTML = `<tr><td colspan="8" class="p-8 text-center text-red-400">Error: ${err.message}</td></tr>`;
        });
    }

    function slotsEqual(slotA, slotB) {
      if (!slotA || !slotB) return false;
      return slotA.subject === slotB.subject;
    }

    function renderTimetable() {
      const displayBody = document.getElementById('timetableDisplayBody');
      const editBody = document.getElementById('timetableEditBody');
      if (!displayBody || !editBody) return;

      displayBody.innerHTML = '';
      editBody.innerHTML = '';

      const days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
      days.forEach((day, index) => {
        const dayData = currentTimetableData[day] || {};
        
        // 1. Render Display Row with cell merging (colspan)
        const trDisp = document.createElement('tr');
        trDisp.className = 'border-b border-slate-800/40 hover:bg-slate-900/10 transition-premium';
        
        let dispCellsHtml = `<td class="p-4 text-center font-bold text-slate-200 bg-slate-900/40">${day}</td>`;
        
        const s1 = dayData[1] || { subject: '', staff: '' };
        const s2 = dayData[2] || { subject: '', staff: '' };
        const s3 = dayData[3] || { subject: '', staff: '' };
        const s4 = dayData[4] || { subject: '', staff: '' };
        const s5 = dayData[5] || { subject: '', staff: '' };
        const s6 = dayData[6] || { subject: '', staff: '' };

        // Forenoon continuous slots (1, 2, 3) merging logic
        if (s1.subject && slotsEqual(s1, s2) && slotsEqual(s2, s3)) {
          dispCellsHtml += renderTimetableDisplayCell(s1, 3);
        } else if (s1.subject && slotsEqual(s1, s2)) {
          dispCellsHtml += renderTimetableDisplayCell(s1, 2);
          dispCellsHtml += renderTimetableDisplayCell(s3, 1);
        } else if (s2.subject && slotsEqual(s2, s3)) {
          dispCellsHtml += renderTimetableDisplayCell(s1, 1);
          dispCellsHtml += renderTimetableDisplayCell(s2, 2);
        } else {
          dispCellsHtml += renderTimetableDisplayCell(s1, 1);
          dispCellsHtml += renderTimetableDisplayCell(s2, 1);
          dispCellsHtml += renderTimetableDisplayCell(s3, 1);
        }
        
        // Lunch Break Column (merged vertically)
        if (index === 0) {
          dispCellsHtml += `<td rowspan="5" class="p-4 text-center bg-slate-950/60 font-bold text-slate-500 text-sm align-middle select-none border-l border-r border-slate-800/40" style="writing-mode: vertical-rl; transform: rotate(180deg); letter-spacing: 4px; text-orientation: mixed; vertical-align: middle;">LUNCH BREAK</td>`;
        }
        
        // Afternoon continuous slots (4, 5, 6) merging logic
        if (s4.subject && slotsEqual(s4, s5) && slotsEqual(s5, s6)) {
          dispCellsHtml += renderTimetableDisplayCell(s4, 3);
        } else if (s4.subject && slotsEqual(s4, s5)) {
          dispCellsHtml += renderTimetableDisplayCell(s4, 2);
          dispCellsHtml += renderTimetableDisplayCell(s6, 1);
        } else if (s5.subject && slotsEqual(s5, s6)) {
          dispCellsHtml += renderTimetableDisplayCell(s4, 1);
          dispCellsHtml += renderTimetableDisplayCell(s5, 2);
        } else {
          dispCellsHtml += renderTimetableDisplayCell(s4, 1);
          dispCellsHtml += renderTimetableDisplayCell(s5, 1);
          dispCellsHtml += renderTimetableDisplayCell(s6, 1);
        }
        
        trDisp.innerHTML = dispCellsHtml;
        displayBody.appendChild(trDisp);

        // 2. Render Edit Row (always unmerged for individual slot selection)
        const trEdit = document.createElement('tr');
        trEdit.className = 'border-b border-slate-800/40';
        
        let editCellsHtml = `<td class="p-3 text-center font-bold text-slate-300 bg-slate-900/40">${day}</td>`;
        
        // Forenoon hours (1, 2, 3)
        for (let h = 1; h <= 3; h++) {
          const slot = dayData[h] || { subject: '', staff: '' };
          editCellsHtml += renderTimetableEditCell(day, h, slot);
        }
        
        // Lunch Break Column (merged vertically)
        if (index === 0) {
          editCellsHtml += `<td rowspan="5" class="p-3 text-center bg-slate-950/60 text-slate-600 font-bold text-sm align-middle select-none border-l border-r border-slate-850" style="writing-mode: vertical-rl; transform: rotate(180deg); letter-spacing: 4px; text-orientation: mixed; vertical-align: middle;">LUNCH BREAK</td>`;
        }
        
        // Afternoon hours (4, 5, 6)
        for (let h = 4; h <= 6; h++) {
          const slot = dayData[h] || { subject: '', staff: '' };
          editCellsHtml += renderTimetableEditCell(day, h, slot);
        }
        
        trEdit.innerHTML = editCellsHtml;
        editBody.appendChild(trEdit);
      });
    }

    function renderTimetableDisplayCell(slot, colspan = 1) {
      const colspanAttr = colspan > 1 ? `colspan="${colspan}"` : '';
      if (!slot.subject) {
        return `<td ${colspanAttr} class="p-4 text-center text-slate-600 italic text-sm">-- Free Period --</td>`;
      }

      // Automatically pull ALL staff members assigned to this subject (for labs/multi-lecturer classes)
      const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === slot.subject);
      let staffDisplay = '';
      if (matchedSub && matchedSub.staff && matchedSub.staff.length > 0) {
        staffDisplay = matchedSub.staff.map(s => s.name).join(', ');
      } else {
        staffDisplay = slot.staff || 'N/A';
      }

      return `
        <td ${colspanAttr} class="p-4 text-center space-y-1">
          <div class="font-extrabold text-slate-100 text-base leading-snug">${slot.subject}</div>
          <div class="text-slate-400 text-sm">${staffDisplay}</div>
        </td>
      `;
    }

    function renderTimetableEditCell(day, hour, slot) {
      let subOptions = `<option value="">-- Free Period --</option>`;
      currentAllocatedSubjects.forEach(sub => {
        const isSelected = sub.subject_code === slot.subject ? 'selected' : '';
        subOptions += `<option value="${sub.subject_code}" ${isSelected}>${sub.subject_code} - ${sub.subject_name}</option>`;
      });

      let staffOptions = `<option value="">-- No Staff --</option>`;
      const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === slot.subject);
      if (matchedSub && matchedSub.staff) {
        matchedSub.staff.forEach(st => {
          const isSelected = st.name === slot.staff ? 'selected' : '';
          staffOptions += `<option value="${st.name}" ${isSelected}>${st.name}</option>`;
        });
      }

      return `
        <td class="p-2 w-44">
          <div class="space-y-1.5">
            <select onchange="updateTimetableStaffDropdown(this)" data-day="${day}" data-hour="${hour}" class="w-full bg-slate-900 border border-slate-800 rounded-lg p-1.5 text-sm text-white focus:border-violet-500 outline-none select-subject">
              ${subOptions}
            </select>
            <select data-day="${day}" data-hour="${hour}" class="w-full bg-slate-950 border border-slate-850 rounded-lg p-1 text-sm text-slate-300 focus:border-violet-500 outline-none select-staff">
              ${staffOptions}
            </select>
          </div>
        </td>
      `;
    }    function printTimetable() {
      if (!activeBatchId) return;

      const sem = document.getElementById('modalSubjectSemester') ? document.getElementById('modalSubjectSemester').value : 1;
      const dept = activeBatchId ? activeBatchId.split('_')[0] : '{{ session("userBranch") }}';
      const currentYear = new Date().getFullYear();

      // Convert department codes to full names
      const deptNames = {
        "EL": "Electronics Engineering",
        "CS": "Computer Engineering",
        "ME": "Mechanical Engineering",
        "EE": "Electrical & Electronics Engineering",
        "CE": "Civil Engineering",
        "CH": "Chemical Engineering"
      };
      const fullDept = deptNames[dept.toUpperCase()] || dept;

      const printWindow = window.open('', '_blank');
      const days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
      let rowsHtml = '';
      const scheduledSubjects = new Set();

      days.forEach((day, index) => {
        const dayData = currentTimetableData[day] || {};
        const s1 = dayData[1] || { subject: '', staff: '' };
        const s2 = dayData[2] || { subject: '', staff: '' };
        const s3 = dayData[3] || { subject: '', staff: '' };
        const s4 = dayData[4] || { subject: '', staff: '' };
        const s5 = dayData[5] || { subject: '', staff: '' };
        const s6 = dayData[6] || { subject: '', staff: '' };

        // Collect scheduled subject codes
        [s1, s2, s3, s4, s5, s6].forEach(s => {
          if (s.subject) scheduledSubjects.add(s.subject);
        });

        let cellsHtml = `<td class="p-4 text-center font-bold bg-gray-100 day-cell">${day}</td>`;

        // Forenoon
        if (s1.subject && slotsEqual(s1, s2) && slotsEqual(s2, s3)) {
          cellsHtml += renderPrintCell(s1, 3);
        } else if (s1.subject && slotsEqual(s1, s2)) {
          cellsHtml += renderPrintCell(s1, 2);
          cellsHtml += renderPrintCell(s3, 1);
        } else if (s2.subject && slotsEqual(s2, s3)) {
          cellsHtml += renderPrintCell(s1, 1);
          cellsHtml += renderPrintCell(s2, 2);
        } else {
          cellsHtml += renderPrintCell(s1, 1);
          cellsHtml += renderPrintCell(s2, 1);
          cellsHtml += renderPrintCell(s3, 1);
        }

        // Lunch Break (merged vertically)
        if (index === 0) {
          cellsHtml += `<td rowspan="5" class="p-4 text-center font-black lunch-cell text-base" style="writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg); letter-spacing: 5px; vertical-align: middle; min-width: 50px;">LUNCH BREAK</td>`;
        }

        // Afternoon
        if (s4.subject && slotsEqual(s4, s5) && slotsEqual(s5, s6)) {
          cellsHtml += renderPrintCell(s4, 3);
        } else if (s4.subject && slotsEqual(s4, s5)) {
          cellsHtml += renderPrintCell(s4, 2);
          cellsHtml += renderPrintCell(s6, 1);
        } else if (s5.subject && slotsEqual(s5, s6)) {
          cellsHtml += renderPrintCell(s4, 1);
          cellsHtml += renderPrintCell(s5, 2);
        } else {
          cellsHtml += renderPrintCell(s4, 1);
          cellsHtml += renderPrintCell(s5, 1);
          cellsHtml += renderPrintCell(s6, 1);
        }

        rowsHtml += `<tr class="border-b border-slate-800/40 print-row">${cellsHtml}</tr>`;
      });

      function renderPrintCell(slot, colspan = 1) {
        const colspanAttr = colspan > 1 ? `colspan="${colspan}"` : '';
        if (!slot.subject) {
          return `<td ${colspanAttr} class="p-4 text-center free-period">-- Free --</td>`;
        }
        
        const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === slot.subject);
        let subjectName = matchedSub ? matchedSub.subject_name : '';
        let staffDisplay = '';
        if (matchedSub && matchedSub.staff && matchedSub.staff.length > 0) {
          staffDisplay = matchedSub.staff.map(s => s.name).join(', ');
        } else {
          staffDisplay = slot.staff || 'N/A';
        }

        return `
          <td ${colspanAttr} class="p-4 text-center">
            <div style="font-weight: 850; font-size: 15px;">${slot.subject}</div>
            <div style="font-weight: 600; font-size: 12px; margin-top: 2px;">${subjectName}</div>
            <div style="font-size: 11px; margin-top: 2px;">${staffDisplay}</div>
          </td>
        `;
      }

      // Build Legend/Abbreviations List
      let legendHtml = '';
      scheduledSubjects.forEach(code => {
        const sub = currentAllocatedSubjects.find(s => s.subject_code === code);
        const name = sub ? sub.subject_name : 'Unknown Subject';
        let staffDisplay = '';
        if (sub && sub.staff && sub.staff.length > 0) {
          staffDisplay = sub.staff.map(s => s.name).join(', ');
        }
        legendHtml += `
          <div class="flex gap-2 text-sm py-1.5 border-b legend-item">
            <span class="font-mono font-bold w-24 legend-code">${code}</span>
            <span class="flex-grow font-semibold">${name}</span>
            <span class="legend-staff font-medium">(${staffDisplay || 'No staff assigned'})</span>
          </div>
        `;
      });

      if (!legendHtml) {
        legendHtml = '<p class="text-sm text-gray-500 italic">No subjects scheduled.</p>';
      }

      printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
          <title>Timetable - ${activeBatchId}</title>
          <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
          <style>
            /* Screen (Dark Mode) Styles */
            body {
              font-family: Arial, sans-serif;
              padding: 30px;
              background-color: #0b0f19;
              color: #f1f5f9;
            }
            .header-border {
              border-color: #1e293b;
            }
            .meta-val {
              color: #ffffff;
            }
            .meta-lbl {
              color: #94a3b8;
            }
            table {
              border-collapse: collapse;
              width: 100%;
              border: 2px solid #1e293b;
              background-color: #0f172a;
            }
            th {
              background-color: #1e293b;
              color: #f1f5f9;
              border: 1px solid #334155;
              padding: 12px;
              text-align: center;
            }
            td {
              border: 1px solid #334155;
              padding: 12px;
              text-align: center;
              vertical-align: middle;
            }
            .day-cell {
              background-color: #1e293b;
              font-weight: bold;
              color: #ffffff;
            }
            .lunch-cell {
              background-color: #090d16;
              color: #64748b;
              font-weight: 900;
            }
            .legend-box {
              background-color: #0f172a;
              border: 1px solid #1e293b;
            }
            .legend-title {
              color: #ffffff;
            }
            .legend-item {
              border-color: #1e293b;
              color: #cbd5e1;
            }
            .legend-code {
              color: #ffffff;
            }
            .legend-staff {
              color: #94a3b8;
            }
            .free-period {
              color: #475569;
              font-style: italic;
            }

            /* Print (Light Mode) Styles */
            @media print {
              .no-print {
                display: none;
              }
              @page {
                size: A4 landscape;
                margin: 0.5cm;
              }
              body {
                background-color: #ffffff;
                color: #000000;
                padding: 0;
                margin: 0;
              }
              table {
                background-color: #ffffff;
                border: 2px solid #000000 !important;
              }
              th, td {
                border: 2px solid #000000 !important;
                color: #000000 !important;
                background-color: #ffffff !important;
                padding: 6px !important;
              }
              .day-cell {
                background-color: #f3f4f6 !important;
              }
              .lunch-cell {
                background-color: #e5e7eb !important;
              }
              .legend-box {
                background-color: #ffffff !important;
                border: 1px solid #000000 !important;
                margin-top: 10px !important;
                padding: 8px !important;
              }
              .legend-title, .legend-item, .legend-code, .legend-staff {
                color: #000000 !important;
              }
              .free-period {
                color: #9ca3af !important;
              }
            }
          </style>
        </head>
        <body>
          <div class="max-w-6xl mx-auto space-y-6">
            
            <!-- Centered Header Section -->
            <div class="border-b pb-4 text-center relative header-border">
              <h1 class="text-lg font-bold meta-lbl uppercase tracking-widest text-slate-400">Carmel Polytechnic College</h1>
              <h2 class="text-2xl font-black text-white mt-1">Weekly Class Timetable</h2>
              
              <div class="flex justify-center gap-12 mt-4 text-sm meta-lbl">
                <div>Department: <strong class="meta-val">${fullDept}</strong></div>
                <div>Batch: <strong class="meta-val">${activeBatchId}</strong></div>
                <div>Semester: <strong class="meta-val">Semester ${sem}</strong></div>
                <div>Assessment Year: <strong class="meta-val">${currentYear}</strong></div>
              </div>

              <div class="no-print absolute top-0 right-0 flex gap-2">
                <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-sm shadow transition duration-200">
                  Print Timetable
                </button>
                <button onclick="window.close()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-sm shadow transition duration-200">
                  Close Preview
                </button>
              </div>
            </div>
            
            <!-- Timetable Grid -->
            <table class="w-full text-left border">
              <thead>
                <tr class="text-slate-400 font-bold border-b header-border">
                  <th class="p-3 text-center w-24">Day</th>
                  <th class="p-3 text-center">Period 1<br><span class="text-xs font-normal meta-lbl">09:00 - 10:00</span></th>
                  <th class="p-3 text-center">Period 2<br><span class="text-xs font-normal meta-lbl">10:00 - 11:00</span></th>
                  <th class="p-3 text-center">Period 3<br><span class="text-xs font-normal meta-lbl">11:10 - 12:10</span></th>
                  <th class="p-3 text-center w-16">Lunch</th>
                  <th class="p-3 text-center">Period 4<br><span class="text-xs font-normal meta-lbl">01:00 - 02:00</span></th>
                  <th class="p-3 text-center">Period 5<br><span class="text-xs font-normal meta-lbl">02:00 - 03:00</span></th>
                  <th class="p-3 text-center">Period 6<br><span class="text-xs font-normal meta-lbl">03:00 - 04:00</span></th>
                </tr>
              </thead>
              <tbody>
                ${rowsHtml}
              </tbody>
            </table>
            
            <!-- Subject Legend / Abbreviations -->
            <div class="mt-6 p-4 rounded-xl border legend-box">
              <h3 class="text-sm font-bold legend-title mb-2 uppercase tracking-wider text-center">Subject Legend & Abbreviations</h3>
              <div class="space-y-1">
                ${legendHtml}
              </div>
            </div>
            
          </div>
        </body>
        </html>
      `);
      printWindow.document.close();
    }

    function updateTimetableStaffDropdown(subjectSelect) {
      const subjectCode = subjectSelect.value;
      const cell = subjectSelect.closest('td');
      const staffSelect = cell.querySelector('.select-staff');
      if (!staffSelect) return;

      staffSelect.innerHTML = `<option value="">-- No Staff --</option>`;
      if (!subjectCode) return;

      const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === subjectCode);
      if (matchedSub && matchedSub.staff) {
        matchedSub.staff.forEach(st => {
          const opt = document.createElement('option');
          opt.value = st.name;
          opt.textContent = st.name;
          staffSelect.appendChild(opt);
        });
      }
    }

    function toggleTimetableEdit(isEdit) {
      const displayArea = document.getElementById('timetableDisplayArea');
      const editArea = document.getElementById('timetableEditArea');
      const btnEdit = document.getElementById('btnEditTimetable');
      const btnCancel = document.getElementById('btnCancelTimetable');
      const btnSave = document.getElementById('btnSaveTimetable');

      if (isEdit) {
        if (displayArea) displayArea.classList.add('hidden');
        if (editArea) editArea.classList.remove('hidden');
        if (btnEdit) btnEdit.classList.add('hidden');
        if (btnCancel) btnCancel.classList.remove('hidden');
        if (btnSave) btnSave.classList.remove('hidden');
      } else {
        if (displayArea) displayArea.classList.remove('hidden');
        if (editArea) editArea.classList.add('hidden');
        if (btnEdit) btnEdit.classList.remove('hidden');
        if (btnCancel) btnCancel.classList.add('hidden');
        if (btnSave) btnSave.classList.add('hidden');
      }
    }

    function submitTimetable() {
      if (!activeBatchId) return;

      const payload = {};
      const days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
      
      days.forEach(day => {
        payload[day] = {};
      });

      const editArea = document.getElementById('timetableEditBody');
      if (!editArea) return;

      const subjectSelects = editArea.querySelectorAll('.select-subject');
      subjectSelects.forEach(sel => {
        const day = sel.getAttribute('data-day');
        const hour = sel.getAttribute('data-hour');
        const subject = sel.value;
        
        const cell = sel.closest('td');
        const staffSel = cell.querySelector('.select-staff');
        const staff = staffSel ? staffSel.value : '';

        if (day && hour) {
          payload[day][hour] = { subject, staff };
        }
      });

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/timetable`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Timetable saved successfully!');
          loadTimetable();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(err => {
        alert('Network Error: ' + err.message);
      });
    }

    function loadModalSubjects() {
      if (!activeBatchId) return;
      const sem = document.getElementById('modalSubjectSemester').value;
      const tbody = document.getElementById('modalSubjectsTableBody');
      tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-slate-500">Loading subjects...</td></tr>`;

      fetch(`/api/hod/batches/${encodeURIComponent(activeBatchId)}/subjects?semester=${sem}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            allCollegeStaffCache = data.all_staff || [];
            tbody.innerHTML = '';
            if (data.subjects.length === 0) {
              tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-slate-500">No subjects allocated for this semester yet.</td></tr>`;
              return;
            }

            data.subjects.forEach(subj => {
              let staffList = subj.staff.map(s => `<span class="block text-sm text-slate-400"><span class="font-bold text-slate-300">${s.name}</span> (${s.branch})</span>`).join('');
              if (subj.staff.length === 0) staffList = `<span class="text-red-400 text-sm font-bold">Unassigned</span>`;
              
              let courseFileBadge = subj.course_file_status === 'Submitted' 
                ? '<span class="px-2 py-0.5 rounded text-sm font-bold bg-green-500/10 text-green-400 border border-green-500/20">Submitted</span>'
                : '<span class="px-2 py-0.5 rounded text-sm font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>';

              const currentStaffIds = subj.staff.map(s => s.mobile_no).join(',');

              const tr = document.createElement('tr');
              tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium cursor-help';
              tr.innerHTML = `
                <td class="p-4 font-mono text-slate-300 font-bold">${subj.subject_code}</td>
                <td class="p-4 font-mono text-slate-500 text-sm">${subj.syllabus_revision_code || '2021'}</td>
                <td class="p-4 font-bold text-slate-200">${subj.subject_name}</td>
                <td class="p-4 text-slate-400 text-sm">${subj.subject_type}</td>
                <td class="p-4">${staffList}</td>
                <td class="p-4">${courseFileBadge}</td>
                <td class="p-4 text-right space-x-1.5">
                  <button onclick="openAssignStaffModalFromModal(event, this, ${subj.id}, '${currentStaffIds}')" data-subject-name="${subj.subject_name.replace(/"/g, '&quot;')}" class="px-2.5 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 rounded-lg text-sm font-bold transition-premium border border-blue-500/20 cursor-pointer">Assign Staff</button>
                  <button onclick="deleteSubject(${subj.id})" class="px-2.5 py-1.5 bg-red-950/40 hover:bg-red-900 border border-red-900/60 text-red-400 rounded-lg text-sm font-bold transition-premium cursor-pointer" title="Delete Subject">
                    Delete
                  </button>
                </td>
              `;
              
              // Progress popup event listeners
              tr.addEventListener('mouseenter', (e) => {
                showSubjectProgressPopup(subj, e);
              });
              tr.addEventListener('mousemove', (e) => {
                positionSubjectProgressPopup(e);
              });
              tr.addEventListener('mouseleave', () => {
                hideSubjectProgressPopup();
              });
              tr.addEventListener('click', (e) => {
                e.stopPropagation();
                showSubjectProgressPopup(subj, e, true);
              });
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-red-400">Failed to load subjects.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-red-400">Error fetching subjects.</td></tr>`;
        });
    }

    function openSubjectModalFromDetail() {
      const sem = document.getElementById('modalSubjectSemester').value;
      
      document.getElementById('subjectForm').reset();
      document.getElementById('subjectAlert').classList.add('hidden');

      document.getElementById('modalFormSubjectBatch').value = activeBatchId;
      document.getElementById('displaySubjectBatch').innerText = activeBatchId;
      document.getElementById('modalFormSubjectSemester').value = sem;
      document.getElementById('displaySubjectSemester').innerText = 'Semester ' + sem;
      
      const modal = document.getElementById('subjectModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
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
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-green-950/40 text-green-400 border border-green-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
          document.getElementById('tutorCurrentDisplay').innerHTML = data.tutor_name 
            ? `<span class="font-bold text-sky-300">${data.tutor_name}</span>`
            : '<span class="italic text-slate-600">Not assigned</span>';
          loadBatches();
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
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
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-green-950/40 text-green-400 border border-green-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
          document.getElementById('mentorCurrentDisplay').innerHTML = data.mentor_name
            ? `<span class="font-bold text-emerald-300">${data.mentor_name}</span>`
            : '<span class="italic text-slate-600">Not assigned</span>';
          loadBatches();
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
          alertEl.innerText = data.message;
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Request failed.';
        alertEl.classList.remove('hidden');
      });
    }

    function loadBatchRoster(classroomId) {
      const tbody = document.getElementById('batchRosterTableBody');
      const countBadge = document.getElementById('rosterCountBadge');
      tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm">Loading students...</td></tr>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/students`)
        .then(r => r.json())
        .then(data => {
          tbody.innerHTML = '';
          if (data.status !== 'SUCCESS' || data.students.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-slate-600 text-sm font-bold">No students enrolled in this batch yet.</td></tr>`;
            countBadge.innerText = '0';
            return;
          }
          countBadge.innerText = data.students.length;
          data.students.forEach(s => {
            let statusBadge = `<span class="px-2 py-0.5 rounded-full text-sm font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
            if (s.status === 'Approved') statusBadge = `<span class="px-2 py-0.5 rounded-full text-sm font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
            else if (s.status === 'Suspended') statusBadge = `<span class="px-2 py-0.5 rounded-full text-sm font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;

            const admTypeBadge = s.admission_type === 'LET'
              ? `<span class="px-1.5 py-0.5 rounded text-sm font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">LET</span>`
              : `<span class="px-1.5 py-0.5 rounded text-sm font-bold bg-slate-700 text-slate-400">Regular</span>`;
              
            const sbteBadge = s.sbte_reg_no ? `<span class="font-mono text-slate-300 font-bold">${s.sbte_reg_no}</span>` : `<span class="text-sm text-slate-500 italic">Pending</span>`;

            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/20 transition-premium';
            tr.innerHTML = `
              <td class="p-3 font-bold text-slate-200">${s.name}</td>
              <td class="p-3 font-mono text-slate-400">${s.reg_no}</td>
              <td class="p-3 font-mono text-slate-500">${s.adm_no}</td>
              <td class="p-3">${sbteBadge}</td>
              <td class="p-3">${admTypeBadge}</td>
              <td class="p-3 font-bold text-indigo-400 font-mono">S${s.semester || '1'}</td>
              <td class="p-3">${statusBadge}</td>
              <td class="p-3 text-right space-x-1">
                <button onclick="openStudentDiary('${s.reg_no}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-teal-500/10 hover:bg-teal-500/20 border border-teal-500/20 text-teal-400 rounded-lg text-sm font-bold transition-premium cursor-pointer">
                  <span class="material-symbols-rounded text-sm">menu_book</span> Diary
                </button>
                <button onclick="editStudentBatch('${s.reg_no}', '${classroomId}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 text-violet-400 rounded-lg text-sm font-bold transition-premium cursor-pointer">
                  <span class="material-symbols-rounded text-sm">swap_horiz</span> Move
                </button>
              </td>
            `;
            tbody.appendChild(tr);
          });
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-red-400 font-bold text-sm">Failed to load students.</td></tr>`;
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
        tbody.innerHTML = `<tr><td colspan="5" class="p-12 text-center text-slate-500 font-medium text-sm">Select a batch above to view its allocated subjects.</td></tr>`;
        return;
      }

      tbody.innerHTML = `<tr><td colspan="5" class="p-12 text-center text-slate-500 font-medium text-sm"><div class="w-4 h-4 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin mx-auto mb-2"></div>Loading subjects...</td></tr>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/subjects?semester=${semester}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            allCollegeStaffCache = data.all_staff || [];
            tbody.innerHTML = '';
            if (data.subjects.length === 0) {
              tbody.innerHTML = `
                <tr>
                  <td colspan="5" class="p-12 text-center text-slate-500 font-medium text-sm">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                      <i data-lucide="book-open" class="w-5 h-5"></i>
                    </div>
                    <p class="font-semibold text-slate-800">No subjects allocated yet</p>
                    <p class="text-xs text-slate-400 mt-0.5">Click "Add Subject" to map a curriculum subject to this semester.</p>
                  </td>
                </tr>
              `;
              if (window.initLucide) window.initLucide();
              return;
            }

            data.subjects.forEach(subj => {
              let staffList = subj.staff.map(s => `<span class="inline-flex items-center gap-1.5 mr-2 mb-1 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 text-xs font-semibold border border-slate-200"><i data-lucide="user" class="w-3 h-3 text-slate-500"></i>${s.name} <span class="text-slate-500 font-mono">(${s.branch})</span></span>`).join('');
              if (subj.staff.length === 0) staffList = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">Unassigned</span>`;
              
              const currentStaffIds = subj.staff.map(s => s.mobile_no).join(',');

              const tr = document.createElement('tr');
              tr.className = 'border-b border-slate-100 hover:bg-slate-50/70 transition-colors';
              tr.innerHTML = `
                <td class="p-4 font-mono text-slate-900 font-bold text-sm whitespace-nowrap">${subj.subject_code}</td>
                <td class="p-4 font-semibold text-slate-900 text-sm">${subj.subject_name}</td>
                <td class="p-4 text-sm"><span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200">${subj.subject_type}</span></td>
                <td class="p-4">${staffList}</td>
                <td class="p-4 text-right space-x-1.5 text-sm whitespace-nowrap">
                  <button onclick="openEditSubjectModal(${JSON.stringify(subj).replace(/"/g, '&quot;')})" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                    Edit
                  </button>
                  <button onclick="openAssignStaffModal(this, ${subj.id}, '${currentStaffIds}')" data-subject-name="${subj.subject_name.replace(/"/g, '&quot;')}" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                    Assign Staff
                  </button>
                  <button onclick="deleteSubject(${subj.id})" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 rounded-lg text-xs font-semibold transition-colors cursor-pointer">
                    Delete
                  </button>
                </td>
              `;
              tbody.appendChild(tr);
            });
            if (window.initLucide) window.initLucide();
          } else {
            tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-rose-600 font-semibold text-sm">Failed to load subjects.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-rose-600 font-semibold text-sm">Error fetching subjects.</td></tr>`;
        });
    }

    // ---- Branch prefix helpers ----
    function _getBranchPrefix() {
      const modalBatch = document.getElementById('modalFormSubjectBatch');
      const displayBatch = document.getElementById('displaySubjectBatch');
      const batchSelect = document.getElementById('subjectBatchSelect');
      
      let val = '';
      if (modalBatch && modalBatch.value) {
        val = modalBatch.value;
      } else if (displayBatch && displayBatch.innerText) {
        val = displayBatch.innerText;
      } else if (batchSelect && batchSelect.value) {
        val = batchSelect.value;
      }
      
      if (!val) return '';
      // classroom_id format: EL_2026_2029 or EL
      return (val.split('_')[0] || '').toUpperCase();
    }

    function _applyCodePrefix(isRev2026) {
      const prefixEl  = document.getElementById('subjectCodePrefix');
      const rawInput  = document.getElementById('subjectCodeRaw');
      const hiddenEl  = document.getElementById('subjectCode');
      if (!prefixEl || !rawInput || !hiddenEl) return;

      if (isRev2026) {
        const prefix = _getBranchPrefix();
        prefixEl.innerText = prefix + '-';
        prefixEl.classList.remove('hidden');
        prefixEl.classList.add('flex');
        rawInput.placeholder = 'e.g. 1008';
        // Sync hidden field
        const raw = rawInput.value.trim();
        hiddenEl.value = raw ? (prefix + '-' + raw) : '';
      } else {
        prefixEl.classList.add('hidden');
        prefixEl.classList.remove('flex');
        rawInput.placeholder = 'e.g. ENG101';
        hiddenEl.value = rawInput.value.trim();
      }
    }

    // Keep hidden field in sync whenever the user types
    document.addEventListener('DOMContentLoaded', function() {
      const rawInput = document.getElementById('subjectCodeRaw');
      if (rawInput) {
        rawInput.addEventListener('input', function() {
          const isRev2026 = (document.getElementById('subjectRevisionYear') || {}).value === 'REV2026';
          _applyCodePrefix(isRev2026);
        });
      }
      // Also re-sync when revision changes
      const revEl = document.getElementById('subjectRevisionYear');
      if (revEl) {
        revEl.addEventListener('change', function() {
          _applyCodePrefix(this.value === 'REV2026');
        });
      }
    });
    // ---- End branch prefix helpers ----

    function openSubjectModal() {
      try {
        const batchSelect = document.getElementById('subjectBatchSelect');
        const semSelect = document.getElementById('subjectSemesterSelect');
        if (!batchSelect || !batchSelect.value) {
          alert("Please select a target batch first.");
          return;
        }
        
        const formEl = document.getElementById('subjectForm');
        if (formEl) formEl.reset();

        // Reset raw code input & hidden field
        const rawInput = document.getElementById('subjectCodeRaw');
        if (rawInput) rawInput.value = '';
        const hiddenCode = document.getElementById('subjectCode');
        if (hiddenCode) hiddenCode.value = '';
        
        const alertEl = document.getElementById('subjectAlert');
        if (alertEl) alertEl.classList.add('hidden');

        const modalBatch = document.getElementById('modalFormSubjectBatch');
        if (modalBatch) modalBatch.value = batchSelect.value;
        
        const displayBatch = document.getElementById('displaySubjectBatch');
        if (displayBatch) displayBatch.innerText = batchSelect.value;
        
        const modalSem = document.getElementById('modalFormSubjectSemester');
        if (modalSem) modalSem.value = semSelect.value;
        
        const displaySem = document.getElementById('displaySubjectSemester');
        if (displaySem && semSelect) {
          displaySem.innerText = semSelect.options[semSelect.selectedIndex].text;
        }
        
        const modal = document.getElementById('subjectModal');
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        }

        const revisionSelect = document.getElementById('subjectRevisionYear');
        if (revisionSelect) {
          if (batchSelect.value.includes('2026') || batchSelect.value.includes('REV2026')) {
            revisionSelect.value = 'REV2026';
          } else {
            revisionSelect.value = 'REV2021';
          }
          syncSubjectTypeOptions(revisionSelect.value);
          _applyCodePrefix(revisionSelect.value === 'REV2026');
        }
      } catch (err) {
        alert("Error opening subject modal: " + err.message);
        console.error('[openSubjectModal] Error:', err);
      }
    }

    function closeSubjectModal() {
      const modal = document.getElementById('subjectModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
      // Reset to Add mode so next open starts fresh
      const editIdEl = document.getElementById('modalEditSubjectId');
      if (editIdEl) editIdEl.value = '';
      const iconEl = document.getElementById('subjectModalIcon');
      if (iconEl) { iconEl.innerText = 'add_box'; iconEl.className = 'material-symbols-rounded text-emerald-400 text-xs'; }
      const titleEl = document.getElementById('subjectModalTitleText');
      if (titleEl) titleEl.innerText = 'Add Curriculum Subject';
      const labelEl = document.getElementById('subjectSubmitLabel');
      if (labelEl) labelEl.innerText = 'Add Subject';
      const btnEl = document.getElementById('subjectSubmitBtn');
      if (btnEl) btnEl.className = 'flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5';
    }

    /**
     * Opens the subject modal in EDIT mode, pre-filling existing values.
     * @param {object|string} subjData - The subject object (or JSON string) from the table row.
     */
    function openEditSubjectModal(subjData) {
      try {
        const subj = (typeof subjData === 'string') ? JSON.parse(subjData) : subjData;

        // Switch modal UI to Edit mode
        document.getElementById('subjectModalIcon').innerText = 'edit';
        document.getElementById('subjectModalIcon').className = 'material-symbols-rounded text-amber-400 text-xs';
        document.getElementById('subjectModalTitleText').innerText = 'Edit Subject Details';
        document.getElementById('subjectSubmitLabel').innerText = 'Save Changes';
        document.getElementById('subjectSubmitBtn').className = 'flex-1 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold text-sm transition-premium cursor-pointer flex items-center justify-center gap-1.5';

        // Store the subject ID
        document.getElementById('modalEditSubjectId').value = subj.id;

        // Set batch/semester context immediately (so prefix helper can read it)
        const modalBatch = document.getElementById('modalFormSubjectBatch');
        if (modalBatch) modalBatch.value = subj.classroom_id || '';
        const modalSem = document.getElementById('modalFormSubjectSemester');
        if (modalSem) modalSem.value = subj.semester || '';

        const displayBatch = document.getElementById('displaySubjectBatch');
        if (displayBatch) displayBatch.innerText = subj.classroom_id || '';
        const displaySem = document.getElementById('displaySubjectSemester');
        if (displaySem) displaySem.innerText = subj.semester ? 'S' + subj.semester : '';

        // Pre-fill fields
        const revEl = document.getElementById('subjectRevisionYear');
        if (revEl && subj.syllabus_revision_code) {
          revEl.value = subj.syllabus_revision_code;
        }
        
        const isRev2026Edit = (revEl ? revEl.value : '') === 'REV2026';
        const rawInput = document.getElementById('subjectCodeRaw');
        const hiddenCode = document.getElementById('subjectCode');
        
        if (isRev2026Edit) {
          // Extract the prefix and code (e.g. "EL-1008" -> "1008")
          const storedCode = subj.subject_code || '';
          const dashIndex = storedCode.indexOf('-');
          if (rawInput) {
            rawInput.value = dashIndex !== -1 ? storedCode.substring(dashIndex + 1) : storedCode;
          }
          if (hiddenCode) {
            hiddenCode.value = storedCode;
          }
        } else {
          if (rawInput) {
            rawInput.value = subj.subject_code || '';
          }
          if (hiddenCode) {
            hiddenCode.value = subj.subject_code || '';
          }
        }
        
        // Sync badge UI prefix display (reads displayBatch or modalBatch now)
        _applyCodePrefix(isRev2026Edit);
        
        document.getElementById('subjectName').value = subj.subject_name || '';
        syncSubjectTypeOptions(revEl ? revEl.value : 'REV2021', subj.subject_type || 'Theory');

        // Clear any previous alert
        const alertEl = document.getElementById('subjectAlert');
        if (alertEl) alertEl.classList.add('hidden');

        const modal = document.getElementById('subjectModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      } catch (err) {
        alert('Error opening edit modal: ' + err.message);
        console.error('[openEditSubjectModal]', err);
      }
    }

    function saveSubject(e) {
      e.preventDefault();
      
      // Ensure prefix gets synchronized from the fields right before submit
      const isRev2026 = (document.getElementById('subjectRevisionYear') || {}).value === 'REV2026';
      _applyCodePrefix(isRev2026);

      const editId = document.getElementById('modalEditSubjectId').value;
      if (editId) {
        // EDIT mode
        _doUpdateSubject(editId);
      } else {
        // ADD mode
        _doCreateSubject();
      }
    }

    function _doCreateSubject() {
      const spinner = document.getElementById('subjectSpinner');
      const alertEl = document.getElementById('subjectAlert');
      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      const payload = {
        classroom_id: document.getElementById('modalFormSubjectBatch').value,
        semester: document.getElementById('modalFormSubjectSemester').value,
        subject_code: document.getElementById('subjectCode').value,
        subject_name: document.getElementById('subjectName').value,
        subject_type: document.getElementById('subjectType').value,
        syllabus_revision_code: document.getElementById('subjectRevisionYear').value
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
          loadSubjects();
          loadModalSubjects();
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
          alertEl.innerText = data.message;
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
        alertEl.innerText = 'Request failed.';
      });
    }

    function _doUpdateSubject(subjectId) {
      const spinner = document.getElementById('subjectSpinner');
      const alertEl = document.getElementById('subjectAlert');
      spinner.classList.remove('hidden');
      alertEl.classList.add('hidden');

      const payload = {
        subject_code: document.getElementById('subjectCode').value,
        subject_name: document.getElementById('subjectName').value,
        subject_type: document.getElementById('subjectType').value,
        syllabus_revision_code: document.getElementById('subjectRevisionYear').value
      };

      fetch(`/api/hod/batches/subjects/${subjectId}`, {
        method: 'PUT',
        headers: getHeaders(),
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          closeSubjectModal();
          loadSubjects();
          loadModalSubjects();
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
          alertEl.innerText = data.message;
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
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
        if(data.status === 'SUCCESS') {
          loadSubjects();
          if (typeof loadModalSubjects === 'function') loadModalSubjects();
        }
        else alert(data.message);
      })
      .catch(() => alert('Failed to delete subject.'));
    }

    let currentAssignStaffIds = [];

    function openAssignStaffModal(btn, subjectId, currentStaffIds) {
      try {
        console.log('[openAssignStaffModal] subjectId:', subjectId, 'currentStaffIds:', currentStaffIds);
        const subjectName = btn.getAttribute('data-subject-name');
        
        const idEl = document.getElementById('assignSubjectId');
        if (idEl) idEl.value = subjectId;
        
        const nameEl = document.getElementById('assignSubjectName');
        if (nameEl) nameEl.innerText = subjectName;
        
        const filterEl = document.getElementById('staffBranchFilter');
        if (filterEl) {
          filterEl.value = window.branchOverride || "{{ session('userBranch') }}" || "";
        }
        
        currentAssignStaffIds = currentStaffIds ? currentStaffIds.split(',') : [];
        
        renderAssignStaffList();

        const alertEl = document.getElementById('assignStaffAlert');
        if (alertEl) alertEl.classList.add('hidden');
        
        const modal = document.getElementById('assignStaffModal');
        if (modal) {
          modal.classList.remove('hidden');
          modal.classList.add('flex');
        }
      } catch (err) {
        alert("Error opening assign staff modal: " + err.message);
        console.error('[openAssignStaffModal] Error:', err);
      }
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
        container.innerHTML = '<div class="p-3 text-slate-500 text-sm text-center">No staff found for this branch.</div>';
        return;
      }

      filteredStaff.forEach(staff => {
        const isChecked = currentAssignStaffIds.includes(staff.mobile_no) ? 'checked' : '';
        const div = document.createElement('label');
        div.className = 'flex items-center gap-3 p-2 hover:bg-slate-800/40 rounded-lg cursor-pointer transition-premium border border-transparent hover:border-slate-700/50';
        div.innerHTML = `
          <input type="checkbox" name="assignStaffCb" value="${staff.mobile_no}" class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-blue-500" ${isChecked}>
          <div class="flex-grow flex justify-between items-center">
            <span class="text-sm font-bold text-slate-200">${staff.name}</span>
            <span class="text-sm text-slate-500 font-mono">${staff.branch} - ${staff.designation}</span>
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
          loadModalSubjects(); // refresh modal
        } else {
          alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
          alertEl.innerText = data.message;
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block mt-3';
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
              tr.className = "border-b border-slate-800 text-sm";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-sm font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
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

    // =========================================================================
    // SUBJECT PROGRESS POPUP CARD LOGIC
    // =========================================================================
    let persistentPopupActive = false;

    function showSubjectProgressPopup(subj, event, isClick = false) {
      if (isClick) {
        persistentPopupActive = !persistentPopupActive;
      }
      
      const popup = document.getElementById('subjectProgressPopup');
      document.getElementById('popupSubjName').innerText = subj.subject_name;
      document.getElementById('popupSubjCode').innerText = subj.subject_code;
      document.getElementById('popupAllottedHours').innerText = (subj.total_hours_allotted || 0) + ' hrs';
      document.getElementById('popupCompletedHours').innerText = (subj.hours_completed || 0) + ' hrs';
      
      // Format Status Colors
      const formatStatus = (elId, status) => {
        const el = document.getElementById(elId);
        el.innerText = status || 'Not Initiated';
        if (!status || status === 'Not Initiated') {
          el.className = 'font-bold text-slate-500';
        } else if (status === 'Pending') {
          el.className = 'font-bold text-amber-400';
        } else {
          el.className = 'font-bold text-green-400';
        }
      };

      formatStatus('popupAssignmentStatus', subj.assignment_initiated);
      formatStatus('popupWrittenTestStatus', subj.written_test_initiated);
      formatStatus('popupMcqStatus', subj.mcq_status);
      formatStatus('popupMidSemStatus', subj.mid_sem_survey_status);
      formatStatus('popupEndSemStatus', subj.end_sem_survey_status);
      
      popup.classList.remove('hidden');
      positionSubjectProgressPopup(event);
    }

    function positionSubjectProgressPopup(event) {
      const popup = document.getElementById('subjectProgressPopup');
      let top = event.clientY + 15;
      let left = event.clientX + 15;
      
      const popupWidth = 288;
      const popupHeight = 240;
      
      if (left + popupWidth > window.innerWidth) {
        left = event.clientX - popupWidth - 15;
      }
      if (top + popupHeight > window.innerHeight) {
        top = event.clientY - popupHeight - 15;
      }
      
      popup.style.top = top + 'px';
      popup.style.left = left + 'px';
    }

    function hideSubjectProgressPopup() {
      if (!persistentPopupActive) {
        const popup = document.getElementById('subjectProgressPopup');
        popup.classList.add('hidden');
      }
    }
    
    // Clear persistent state on closures or transitions
    const originalCloseBatchDetailModal = closeBatchDetailModal;
    closeBatchDetailModal = function() {
      persistentPopupActive = false;
      hideSubjectProgressPopup();
      if (typeof originalCloseBatchDetailModal === 'function') {
        originalCloseBatchDetailModal();
      }
    };

    const originalSwitchBatchTab = switchBatchTab;
    switchBatchTab = function(tab) {
      persistentPopupActive = false;
      hideSubjectProgressPopup();
      if (typeof originalSwitchBatchTab === 'function') {
        originalSwitchBatchTab(tab);
      }
    };

    document.addEventListener('click', (e) => {
      const popup = document.getElementById('subjectProgressPopup');
      if (persistentPopupActive && !e.target.closest('tr')) {
        persistentPopupActive = false;
        popup.classList.add('hidden');
      }
    });

    function openStudentDiary(regNo) {
      window.open('/tutor/mentoring-diary/' + regNo, '_blank');
    }

    function openAssignStaffModalFromModal(event, btn, subjectId, currentStaffIds) {
      if (event) event.stopPropagation();
      openAssignStaffModal(btn, subjectId, currentStaffIds);
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      statusEl.classList.remove('hidden');
      statusEl.className = "text-sm font-bold mt-2 text-blue-400";
      statusEl.innerText = "Uploading photo...";

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
          statusEl.innerText = "Photo updated successfully!";

          // Update main profile picture
          const imgEl = document.getElementById('staffProfileImg');
          if (imgEl) {
            imgEl.src = data.photo_url;
          }

          // Update sidebar picture
          const sidebarImg = document.getElementById('sidebarStaffImg');
          if (sidebarImg) {
            sidebarImg.src = data.photo_url;
          }

          setTimeout(() => statusEl.classList.add('hidden'), 3000);
        } else {
          statusEl.className = "text-sm font-bold mt-2 text-rose-400";
          statusEl.innerText = data.message || "Upload failed.";
        }
      })
      .catch(() => {
        statusEl.className = "text-sm font-bold mt-2 text-rose-400";
        statusEl.innerText = "Network error. Please try again.";
      });
    }

    function checkTodaySeminars() {
      fetch('/api/lecturer/today-seminars')
      .then(res => res.json())
      .then(res => {
        const container = document.getElementById('seminarNotificationsContainer');
        if (!container) return;
        container.innerHTML = '';

        if (res.status === 'SUCCESS' && res.data.length > 0) {
          // Group by classroom_id
          const groups = {};
          res.data.forEach(item => {
            const cid = item.classroom_id || 'Unknown_Classroom';
            if (!groups[cid]) {
              groups[cid] = [];
            }
            groups[cid].push(item);
          });

          // Render a card for each group
          Object.keys(groups).forEach(cid => {
            const items = groups[cid];
            const first = items[0];
            const count = items.length;

            const card = document.createElement('div');
            card.className = "p-4 bg-amber-50 border border-amber-200/80 hover:border-amber-300 rounded-2xl flex items-center justify-between shadow-2xs hover:shadow-xs transition-all cursor-pointer group";
            card.onclick = () => {
              window.location.href = `/dashboard/lecturer?subject_id=${first.batch_subject_id}&subject_name=${encodeURIComponent(first.subject_name || 'Seminar')}&classroom_id=${encodeURIComponent(cid)}`;
            };

            card.innerHTML = `
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                  <i data-lucide="presentation" class="w-5 h-5 text-amber-700"></i>
                </div>
                <div class="min-w-0">
                  <h5 class="text-sm font-bold text-amber-900 group-hover:text-amber-950 transition-colors truncate">Seminar Presentations Today (${count})</h5>
                  <p class="text-xs text-amber-700 mt-0.5 truncate">${cid} · ${first.subject_name || 'Seminar'}</p>
                </div>
              </div>
              <i data-lucide="chevron-right" class="w-4 h-4 text-amber-500 group-hover:text-amber-700 transition-colors shrink-0"></i>
            `;
            container.appendChild(card);
          });

          container.classList.remove('hidden');
          if (window.initLucide) window.initLucide();
        } else {
          container.classList.add('hidden');
        }
      })
      .catch(err => console.error('Failed to load today seminars:', err));
    }

    // =========================================================================
    // STAFF LEAVE MASTER LEDGER HANDLERS
    // =========================================================================
    async function loadLeaveLedger() {
      const dept = document.getElementById('leaveLedgerDept')?.value || '';
      const year = document.getElementById('leaveLedgerYear')?.value || '';
      const status = document.getElementById('leaveLedgerStatus')?.value || '';
      const tbody = document.getElementById('leaveLedgerTableBody');
      if (!tbody) return;

      tbody.innerHTML = `<tr><td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">
        <div class="inline-flex items-center gap-2 text-slate-500">
          <div class="w-4 h-4 border-2 border-rose-600 border-t-transparent rounded-full animate-spin"></div>
          <span>Loading staff leave records...</span>
        </div>
      </td></tr>`;

      try {
        const query = new URLSearchParams();
        if (dept) query.set('department', dept);
        if (year) query.set('academic_year', year);
        if (status) query.set('status', status);

        const res = await fetch(`/api/staff/leave/reports-data?${query.toString()}`);
        const data = await res.json();

        if (data.status === 'SUCCESS' && data.leaves) {
          const sm = data.summary || {};
          const kTotal = document.getElementById('leaveKpiTotal');
          const kCL = document.getElementById('leaveKpiCL');
          const kCCL = document.getElementById('leaveKpiCCL');
          const kDL = document.getElementById('leaveKpiDL');
          const kML = document.getElementById('leaveKpiML');
          const kLOP = document.getElementById('leaveKpiLOP');

          if (kTotal) kTotal.innerText = (sm.TOTAL_DAYS || 0).toFixed(1);
          if (kCL) kCL.innerText = (sm.CL || 0).toFixed(1);
          if (kCCL) kCCL.innerText = (sm.CCL || 0).toFixed(1);
          if (kDL) kDL.innerText = (sm.DL || 0).toFixed(1);
          if (kML) kML.innerText = (sm.ML || 0).toFixed(1);
          if (kLOP) kLOP.innerText = (sm.LOP || 0).toFixed(1);

          if (data.leaves.length > 0) {
            tbody.innerHTML = data.leaves.map(l => {
              const staffName = l.staff_name || 'Staff Member';
              const initial = staffName.charAt(0).toUpperCase();
              
              let badgeColor = 'bg-amber-50 text-amber-700 border-amber-200/80';
              let badgeDot = 'bg-amber-500';
              if (l.overall_status === 'Approved') {
                badgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-200/80';
                badgeDot = 'bg-emerald-500';
              } else if (l.overall_status === 'Rejected') {
                badgeColor = 'bg-rose-50 text-rose-700 border-rose-200/80';
                badgeDot = 'bg-rose-500';
              }

              const statusBadge = `
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold ${badgeColor} border">
                  <span class="w-1.5 h-1.5 rounded-full ${badgeDot}"></span>
                  ${l.overall_status?.replace('_', ' ') || 'Pending'}
                </span>
              `;

              const canApprove = (l.overall_status === 'Pending_HOD' || (!l.overall_status?.includes('Approved') && !l.overall_status?.includes('Rejected')));

              return `
                <tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="p-4">
                    <div class="flex items-center gap-3">
                      <div class="w-9 h-9 rounded-full bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-700 font-bold text-sm shrink-0">
                        ${initial}
                      </div>
                      <div>
                        <span class="font-bold text-slate-900 block text-sm leading-tight">${staffName}</span>
                        <span class="text-xs text-slate-500 font-mono mt-0.5 block">${l.department} • <strong class="text-blue-600">${l.leave_code || ('SLV-' + l.id)}</strong></span>
                      </div>
                    </div>
                  </td>
                  <td class="p-4">
                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200/80">
                      ${l.leave_type || 'Casual Leave'}
                    </span>
                  </td>
                  <td class="p-4">
                    <span class="text-sm font-semibold text-slate-900 block">${l.from_date} to ${l.to_date}</span>
                    <span class="text-xs text-slate-500 font-medium mt-0.5 inline-block px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200/60">${l.total_days} Day(s) (${l.session_type || 'Full Day'})</span>
                  </td>
                  <td class="p-4 text-xs text-slate-600 max-w-xs">
                    <span class="line-clamp-2">${l.reason || '-'}</span>
                  </td>
                  <td class="p-4 text-center">
                    ${statusBadge}
                  </td>
                  <td class="p-4 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                      ${canApprove ? `
                        <button 
                          type="button" 
                          onclick="processLeaveApproval(${l.id}, 'Approve')" 
                          class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition cursor-pointer shadow-xs" 
                          title="Recommend/Approve Leave"
                        >
                          Approve
                        </button>
                        <button 
                          type="button" 
                          onclick="processLeaveApproval(${l.id}, 'Reject')" 
                          class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition cursor-pointer shadow-xs" 
                          title="Reject Leave"
                        >
                          Reject
                        </button>
                      ` : ''}
                      <a 
                        href="/staff/leave/${l.id}/pdf" 
                        target="_blank" 
                        class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition cursor-pointer" 
                        title="Print Official Leave PDF Application"
                      >
                        <i data-lucide="printer" class="w-4 h-4"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              `;
            }).join('');
            if (window.initLucide) window.initLucide();
          } else {
            tbody.innerHTML = `
              <tr>
                <td colspan="6" class="p-12 text-center text-slate-500 font-medium text-sm">
                  <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="folder-open" class="w-6 h-6 text-slate-400"></i>
                  </div>
                  <h4 class="text-base font-bold text-slate-800">No staff leave records found</h4>
                  <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">No leave applications matching the selected criteria.</p>
                </td>
              </tr>
            `;
            if (window.initLucide) window.initLucide();
          }
        }
      } catch (err) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-12 text-center text-rose-600 font-semibold text-sm">Failed to load leave records. Please check network connection.</td></tr>`;
      }
    }

    async function processLeaveApproval(id, decision) {
      const remarks = prompt(`Enter optional remarks for ${decision.toLowerCase()}ing leave application:`, decision === 'Approve' ? 'Recommended/Approved by HOD' : 'Rejected');
      if (remarks === null) return;

      try {
        const res = await fetch('/api/staff/leave/process-approval', {
          method: 'POST',
          headers: getHeaders(),
          body: JSON.stringify({
            leave_id: id,
            stage: 'HOD',
            decision: decision,
            remarks: remarks
          })
        });
        const data = await res.json();
        if (data.status === 'SUCCESS') {
          showGlobalMessage(`Leave application ${decision.toLowerCase()}d successfully.`, false);
          loadLeaveLedger();
        } else {
          showGlobalMessage(data.message || 'Error processing leave decision.', true);
        }
      } catch (err) {
        showGlobalMessage('Network error updating leave decision.', true);
      }
    }

    // =========================================================================
    // PROFESSIONAL ACTIVITIES HANDLERS (CAMPUSLYNK 5/7 ARCHETYPE)
    // =========================================================================
    const profActSchemas = {
      fdp_attended: [
        { label: 'Title of FDP / Training Program', name: 'title', type: 'text', placeholder: 'e.g. Advanced Laravel & Microservices', fullWidth: true, required: true },
        { label: 'Duration (Days / Hours)', name: 'duration', type: 'text', placeholder: 'e.g. 5 Days / 40 Hrs', required: true },
        { label: 'Start Date', name: 'date', type: 'date', required: false },
        { label: 'Organizing Venue / Institution', name: 'venue', type: 'text', placeholder: 'e.g. Carmel Polytechnic / NITTTR', fullWidth: true, required: true }
      ],
      workshop_attended: [
        { label: 'Title of Workshop / BootCamp', name: 'title', type: 'text', placeholder: 'e.g. IoT Systems & Embedded Networks', fullWidth: true, required: true },
        { label: 'Duration (Days / Hours)', name: 'duration', type: 'text', placeholder: 'e.g. 2 Days', required: true },
        { label: 'Date', name: 'date', type: 'date', required: false },
        { label: 'Organizing Body / Venue', name: 'venue', type: 'text', placeholder: 'e.g. Govt Polytechnic College', fullWidth: true, required: true }
      ],
      course_attended: [
        { label: 'Course / Certification Title', name: 'title', type: 'text', placeholder: 'e.g. NPTEL Data Structures & Algorithms', fullWidth: true, required: true },
        { label: 'Duration', name: 'duration', type: 'text', placeholder: 'e.g. 8 Weeks', required: true },
        { label: 'Platform / Certifying Body', name: 'venue', type: 'text', placeholder: 'e.g. NPTEL / Swayam / Coursera', required: true }
      ],
      gap_in_syllabus: [
        { label: 'Subject Name & Code', name: 'subject', type: 'text', placeholder: 'e.g. Computer Networks (CN-302)', fullWidth: true, required: true },
        { label: 'Identified Curricular Gap Details', name: 'gap_details', type: 'textarea', placeholder: 'Identify details where syllabus falls short of industrial expectations...', fullWidth: true, required: true },
        { label: 'Action Taken / Bridge Course Plan', name: 'action_taken', type: 'text', placeholder: 'e.g. Conducted a 3-hour hands-on seminar on IPv6 Routing', fullWidth: true, required: true }
      ],
      project_guided: [
        { label: 'Project Title', name: 'title', type: 'text', placeholder: 'e.g. Smart Campus Face Recognition System', fullWidth: true, required: true },
        { label: 'Batch / Academic Year', name: 'batch', type: 'text', placeholder: 'e.g. 2023-2026 Batch', required: true },
        { label: 'Student Names', name: 'students', type: 'text', placeholder: 'e.g. Arjun, Vishnu, Rahul', required: true }
      ],
      seminar_guided: [
        { label: 'Seminar Topic', name: 'title', type: 'text', placeholder: 'e.g. Introduction to Quantum Computing & Cryptography', fullWidth: true, required: true },
        { label: 'Student Name', name: 'students', type: 'text', placeholder: 'e.g. Anjali Nair', required: true },
        { label: 'Date Presented', name: 'date', type: 'date', required: false }
      ],
      publication: [
        { label: 'Paper / Research Title', name: 'title', type: 'text', placeholder: 'e.g. AI-driven Automated Grading Engines', fullWidth: true, required: true },
        { label: 'Journal / Conference Name', name: 'journal', type: 'text', placeholder: 'e.g. International Journal of Engineering & Tech', required: true },
        { label: 'Publication Year', name: 'year', type: 'number', placeholder: 'e.g. 2026', required: true }
      ],
      book_published: [
        { label: 'Book Title', name: 'title', type: 'text', placeholder: 'e.g. Fundamentals of Embedded Systems & C', fullWidth: true, required: true },
        { label: 'Publisher Name', name: 'publisher', type: 'text', placeholder: 'e.g. Pearson India', required: true },
        { label: 'ISBN Number', name: 'isbn', type: 'text', placeholder: 'e.g. 978-3-16-148410-0', required: true },
        { label: 'Year of Publication', name: 'year', type: 'number', placeholder: 'e.g. 2025', required: true }
      ]
    };

    function toggleProfActFields(type) {
      const container = document.getElementById('profActDynamicFields');
      if (!container) return;
      container.innerHTML = '';
      
      const fields = profActSchemas[type] || [];
      fields.forEach(f => {
        const wrap = document.createElement('div');
        wrap.className = f.fullWidth ? 'space-y-1' : 'space-y-1';
        
        const label = document.createElement('label');
        label.className = 'block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1';
        label.innerHTML = `${f.label} ${f.required ? '<span class="text-rose-500">*</span>' : ''}`;
        wrap.appendChild(label);
        
        if (f.type === 'textarea') {
          const textarea = document.createElement('textarea');
          textarea.name = `details[${f.name}]`;
          textarea.id = `field_${f.name}`;
          textarea.placeholder = f.placeholder;
          textarea.rows = 2;
          textarea.className = 'w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all resize-none';
          if (f.required) textarea.required = true;
          wrap.appendChild(textarea);
        } else {
          const input = document.createElement('input');
          input.type = f.type;
          input.name = `details[${f.name}]`;
          input.id = `field_${f.name}`;
          input.placeholder = f.placeholder;
          input.className = 'w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all';
          if (f.required) input.required = true;
          wrap.appendChild(input);
        }
        
        container.appendChild(wrap);
      });
    }
    window.toggleProfActFields = toggleProfActFields;

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
          const totalCountEl = document.getElementById('profActTotalCount');
          const fdpCountEl = document.getElementById('profActFdpCount');
          const pubCountEl = document.getElementById('profActPubCount');
          const regCountEl = document.getElementById('profActRegistryCount');

          if (totalCountEl) totalCountEl.innerText = data.records.length;
          const fdpCount = data.records.filter(r => r.activity_type?.includes('fdp') || r.activity_type?.includes('workshop') || r.activity_type?.includes('course')).length;
          const pubCount = data.records.filter(r => r.activity_type?.includes('publication') || r.activity_type?.includes('book')).length;
          if (fdpCountEl) fdpCountEl.innerText = fdpCount;
          if (pubCountEl) pubCountEl.innerText = pubCount;
          if (regCountEl) regCountEl.innerText = `${data.records.length} records in AY ${ay}`;

          if (data.records.length > 0) {
            container.innerHTML = data.records.map(r => {
              const details = r.details || {};
              const actTypeFormatted = (r.activity_type || 'Activity').replace(/_/g, ' ');
              const canDelete = (r.lecturer_mobile_no === window.currentUserId) || ('{{ session('userId') }}' === r.lecturer_mobile_no);

              let detailsSnippet = '';
              if (r.activity_type === 'gap_in_syllabus') {
                detailsSnippet = `
                  <div class="text-xs text-slate-600 space-y-0.5 mt-1">
                    <div><strong class="text-slate-700">Subject:</strong> ${escapeHtml(details.subject || '-')}</div>
                    <div><strong class="text-slate-700">Identified Gap:</strong> ${escapeHtml(details.gap_details || '-')}</div>
                    <div><strong class="text-slate-700">Action Plan:</strong> ${escapeHtml(details.action_taken || '-')}</div>
                  </div>
                `;
              } else if (r.activity_type === 'project_guided' || r.activity_type === 'seminar_guided') {
                detailsSnippet = `
                  <div class="text-xs text-slate-500 flex items-center gap-2.5 pt-1 flex-wrap font-medium">
                    ${details.batch ? `<span><strong>Batch:</strong> ${escapeHtml(details.batch)}</span><span>•</span>` : ''}
                    <span><strong>Students:</strong> ${escapeHtml(details.students || '-')}</span>
                    ${details.date ? `<span>•</span><span><strong>Date:</strong> ${escapeHtml(details.date)}</span>` : ''}
                  </div>
                `;
              } else if (r.activity_type === 'publication' || r.activity_type === 'book_published') {
                detailsSnippet = `
                  <div class="text-xs text-slate-500 flex items-center gap-2.5 pt-1 flex-wrap font-medium">
                    ${details.journal ? `<span><strong>Journal:</strong> ${escapeHtml(details.journal)}</span><span>•</span>` : ''}
                    ${details.publisher ? `<span><strong>Publisher:</strong> ${escapeHtml(details.publisher)}</span><span>•</span>` : ''}
                    ${details.isbn ? `<span><strong>ISBN:</strong> ${escapeHtml(details.isbn)}</span><span>•</span>` : ''}
                    <span><strong>Year:</strong> ${escapeHtml(details.year || '-')}</span>
                  </div>
                `;
              } else {
                detailsSnippet = `
                  <div class="text-xs text-slate-500 flex items-center gap-2.5 pt-1 flex-wrap font-medium">
                    ${details.duration ? `<span><strong>Duration:</strong> ${escapeHtml(details.duration)}</span><span>•</span>` : ''}
                    ${details.venue ? `<span><strong>Venue/Platform:</strong> ${escapeHtml(details.venue)}</span><span>•</span>` : ''}
                    ${details.date ? `<span><strong>Date:</strong> ${escapeHtml(details.date)}</span>` : ''}
                  </div>
                `;
              }

              return `
                <div class="p-4 bg-white border border-slate-200/80 rounded-2xl flex items-start justify-between gap-4 hover:border-slate-300 transition-all shadow-2xs">
                  <div class="space-y-1 min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                      <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-200/80 rounded-full text-xs font-bold uppercase tracking-wider">${escapeHtml(actTypeFormatted)}</span>
                      <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-xs font-semibold">${escapeHtml(r.department || 'General')}</span>
                      <span class="text-xs text-slate-500 font-medium">• ${escapeHtml(r.staff_name || 'Faculty')} (${escapeHtml(r.designation || 'Lecturer')})</span>
                    </div>
                    <h5 class="font-bold text-slate-900 text-sm mt-1">${escapeHtml(details.title || details.subject || 'Professional Activity')}</h5>
                    ${detailsSnippet}
                  </div>
                  ${canDelete ? `
                    <button type="button" onclick="deleteProfActivity(${r.id})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition cursor-pointer shrink-0" title="Delete record">
                      <i data-lucide="trash-2" class="w-4 h-4 text-rose-500"></i>
                    </button>
                  ` : ''}
                </div>
              `;
            }).join('');
          } else {
            container.innerHTML = `
              <div class="p-12 text-center text-slate-400 text-sm space-y-2">
                <i data-lucide="award" class="w-8 h-8 text-slate-300 mx-auto block"></i>
                <p>No professional activity records found for AY ${escapeHtml(ay)}.</p>
                <p class="text-xs text-slate-400">Use the form on the left to record new faculty activities.</p>
              </div>
            `;
          }
          if (window.initLucide) window.initLucide();
        }
      } catch (err) {
        if (container) container.innerHTML = `<div class="p-8 text-center text-rose-500 text-sm">Failed to load professional activities.</div>`;
      }
    }
    window.loadProfActivities = loadProfActivities;

    async function submitProfActivity(e) {
      e.preventDefault();
      const alertEl = document.getElementById('profActAlert');
      const ay = document.getElementById('profActAyFilter')?.value || '{{ date('Y') }}-{{ date('Y') + 1 }}';
      const type = document.getElementById('profActType').value;
      const form = document.getElementById('profActivityForm');

      // Build details object dynamically from input schema
      const details = {};
      const inputs = form.querySelectorAll('[name^="details["]');
      inputs.forEach(inp => {
        const match = inp.name.match(/details\[(.*?)\]/);
        if (match && match[1]) {
          details[match[1]] = inp.value;
        }
      });

      const btn = document.getElementById('btnSaveProfAct');
      if (btn) btn.disabled = true;

      try {
        const res = await fetch('/staff/professional-activities/save', {
          method: 'POST',
          headers: getHeaders(),
          body: JSON.stringify({
            academic_year: ay,
            activity_type: type,
            details: details
          })
        });

        if (res.ok) {
          alertEl.classList.remove('hidden');
          alertEl.className = 'p-3 rounded-xl font-semibold border text-sm bg-emerald-50 text-emerald-800 border-emerald-200';
          alertEl.innerText = 'Professional activity recorded successfully!';
          form.reset();
          toggleProfActFields(type);
          loadProfActivities();
          setTimeout(() => alertEl.classList.add('hidden'), 3500);
        } else {
          throw new Error('Server returned error response');
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-sm bg-rose-50 text-rose-800 border-rose-200';
        alertEl.innerText = 'Error saving activity record. Please check inputs.';
      } finally {
        if (btn) btn.disabled = false;
      }
    }
    window.submitProfActivity = submitProfActivity;

    async function deleteProfActivity(id) {
      if (!confirm('Are you sure you want to delete this activity record?')) return;
      try {
        const res = await fetch(`/staff/professional-activities/delete/${id}`, {
          method: 'POST',
          headers: getHeaders()
        });
        if (res.ok) {
          showGlobalMessage('Activity deleted successfully.', false);
          loadProfActivities();
        } else {
          showGlobalMessage('Failed to delete activity record.', true);
        }
      } catch (err) {
        showGlobalMessage('Network error deleting activity.', true);
      }
    }
    window.deleteProfActivity = deleteProfActivity;
  </script>

  <!-- SUBJECT PROGRESS POPUP CARD -->
  <div id="subjectProgressPopup" class="fixed hidden bg-white border border-slate-200 rounded-2xl p-4 shadow-xl z-[60] w-72 pointer-events-none transition-all flex flex-col gap-3">
    <div class="flex justify-between items-center border-b border-slate-100 pb-2">
      <h4 id="popupSubjName" class="font-bold text-sm text-slate-900 truncate w-48">Subject Name</h4>
      <span id="popupSubjCode" class="font-mono text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md">ENG101</span>
    </div>
    <div class="space-y-2 text-xs">
      <div class="flex justify-between items-center">
        <span class="text-slate-500">Allotted Hours:</span>
        <span id="popupAllottedHours" class="font-semibold text-slate-900">0 hrs</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">Completed Hours:</span>
        <span id="popupCompletedHours" class="font-semibold text-slate-900">0 hrs</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">Assignment Initiated:</span>
        <span id="popupAssignmentStatus" class="font-semibold text-slate-400">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">Written Test Initiated:</span>
        <span id="popupWrittenTestStatus" class="font-semibold text-slate-400">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">MCQ Status:</span>
        <span id="popupMcqStatus" class="font-semibold text-slate-400">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">Mid-Sem Survey:</span>
        <span id="popupMidSemStatus" class="font-semibold text-slate-400">Not Initiated</span>
      </div>
      <div class="flex justify-between items-center">
        <span class="text-slate-500">End-Sem Survey:</span>
        <span id="popupEndSemStatus" class="font-semibold text-slate-400">Not Initiated</span>
      </div>
    </div>
  </div>

  @include('mentoring_diary_modal')
  @include('partials.support_desk_overlay')

</x-layouts.app-shell>
