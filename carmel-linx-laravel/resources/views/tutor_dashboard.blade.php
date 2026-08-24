<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - Tutor Desk</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Modern Typography (Poppins) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Pre-Paint Synchronous Sidebar State Hydration (Anti-FOUC) -->
  <script>
    (function() {
      try {
        var isCollapsed = localStorage.getItem('campuslynk_sidebar_collapsed') === 'true' || 
                          document.cookie.indexOf('campuslynk_sidebar_collapsed=true') !== -1;
        if (isCollapsed && window.innerWidth >= 1024) {
          document.documentElement.classList.add('sidebar-is-collapsed');
        }
      } catch(e) {}
    })();
  </script>

  <!-- Vite Assets -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .custom-scrollbar::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }
    .tutor-tab-btn { cursor: pointer !important; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
  </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen font-sans antialiased sidebar-preload">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  @php
    $initialPanel = request('panel', request('tab', 'roster'));
    $activeTab = in_array($initialPanel, ['mentoring', 'rollNumbers', 'leaveApproval', 'activity', 'audit', 'profile', 'security']) ? $initialPanel : 'roster';
    if ($activeTab === 'security') $activeTab = 'profile';

    $tabTitles = [
      'roster' => 'Supervised Class Roster',
      'rollNumbers' => 'Assign Class Roll Numbers',
      'mentoring' => 'Mentoring Batches & Splitter',
      'leaveApproval' => 'Leave Approval & Reports',
      'activity' => 'Activity Points Verification',
      'audit' => 'Classroom Audit Trail',
      'profile' => 'My Profile & Security Settings',
    ];
    $currentTitle = $tabTitles[$activeTab] ?? 'Supervised Class Roster';
  @endphp

  <!-- Master Application Shell -->
  <div class="flex min-h-screen bg-[#FAFAFB]">

    <!-- Global Sidebar Navigation Component -->
    <x-layout.sidebar role="tutor" :active="$activeTab === 'mentoring' ? 'my_mentoring' : 'tutor_console'" />

    <!-- Main Viewport Container -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#FAFAFB]">
      
      <!-- Global Topbar Header Component -->
      <x-layout.topbar :title="$currentTitle" subtitle="Supervised student directory, mentoring batches & class management." />

      <!-- Scrollable Main Workspace -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- Top Status Alert Banner -->
        <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all"></div>

        <!-- Syncing Loading Indicator -->
        <div id="loadingIndicator" class="hidden items-center gap-2 text-slate-500 text-xs py-1.5 px-3.5 bg-blue-50 border border-blue-200 rounded-xl w-fit">
          <div class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
          <span class="font-medium text-blue-700">Syncing classroom data...</span>
        </div>

        <!-- Tutor Primary Navigation Tabs -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-2 shadow-xs flex items-center gap-1.5 overflow-x-auto custom-scrollbar">
          <button type="button" id="navRoster" onclick="switchPanel('roster')" class="tutor-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap {{ $activeTab === 'roster' ? 'bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <x-ui.icon name="users" class="w-4 h-4" />
            <span>Class Roster</span>
          </button>
          
          <button type="button" id="navRollNumbers" onclick="switchPanel('rollNumbers')" class="tutor-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap {{ $activeTab === 'rollNumbers' ? 'bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <x-ui.icon name="format_list_numbered" class="w-4 h-4" />
            <span>Roll Numbers</span>
          </button>

          <button type="button" id="navMentoring" onclick="switchPanel('mentoring')" class="tutor-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap {{ $activeTab === 'mentoring' ? 'bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <x-ui.icon name="heart-handshake" class="w-4 h-4" />
            <span>Mentoring Batches</span>
          </button>

          <button type="button" id="navLeaveApproval" onclick="switchPanel('leaveApproval')" class="tutor-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap {{ $activeTab === 'leaveApproval' ? 'bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <x-ui.icon name="approval" class="w-4 h-4" />
            <span>Leave Approvals</span>
          </button>

          <button type="button" id="navActivity" onclick="switchPanel('activity')" class="tutor-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap {{ $activeTab === 'activity' ? 'bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <x-ui.icon name="verified" class="w-4 h-4" />
            <span>Activity Points</span>
          </button>

          <button type="button" id="navAudit" onclick="switchPanel('audit')" class="tutor-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap {{ $activeTab === 'audit' ? 'bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <x-ui.icon name="receipt_long" class="w-4 h-4" />
            <span>Audit Trail</span>
          </button>
        </div>

        <h1 id="panelTitle" class="sr-only">{{ $currentTitle }}</h1>

        <!-- PANEL 1: ROSTER -->
        <div id="panelRoster" class="{{ $activeTab === 'roster' ? '' : 'hidden' }} space-y-6">
          
          <!-- Directory Header & Filter Card -->
          <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
              <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
                  <x-ui.icon name="users" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                  <h3 id="supervisedClassroomTitle" class="font-bold text-slate-900 text-base sm:text-lg">Supervised Classroom Directory</h3>
                  <p class="text-xs text-slate-500 mt-0.5">Manage and review lifecycle states of students in your assigned classroom.</p>
                </div>
              </div>
            </div>

            <!-- Filters Console -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
              <!-- Search input -->
              <div>
                <label class="block text-slate-600 font-semibold mb-1.5 text-xs">Search Student</label>
                <div class="relative">
                  <input type="text" id="filterSearch" oninput="debouncedLoadUsers()" class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-3.5 py-2.5 text-slate-900 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all placeholder:text-slate-400" placeholder="Name, Register No, Mobile...">
                  <div class="absolute left-3.5 top-3 text-slate-400 pointer-events-none">
                    <x-ui.icon name="search" class="w-4 h-4" />
                  </div>
                </div>
              </div>
              
              <!-- Status select -->
              <div>
                <label class="block text-slate-600 font-semibold mb-1.5 text-xs">Account Status</label>
                <select id="filterStatus" onchange="loadUsers()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all cursor-pointer">
                  <option value="">All Statuses</option>
                  <option value="Approved">Approved</option>
                  <option value="Pending">Pending</option>
                  <option value="Suspended">Suspended</option>
                </select>
              </div>

              <!-- Print Report Selector & Button -->
              <div>
                <label class="block text-slate-600 font-semibold mb-1.5 text-xs">Class Register Report</label>
                <div class="flex gap-2">
                  <select id="printSemesterSelect" class="flex-1 bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 text-sm focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all cursor-pointer">
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3" selected>S3</option>
                    <option value="S4">S4</option>
                    <option value="S5">S5</option>
                    <option value="S6">S6</option>
                  </select>
                  <button type="button" onclick="printClassRegister()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm flex items-center gap-1.5 transition-all cursor-pointer shadow-xs shrink-0">
                    <x-ui.icon name="print" class="w-4 h-4" />
                    <span>Print</span>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Users Table Grid -->
          <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto max-h-[560px] custom-scrollbar">
              <table class="w-full text-left border-collapse text-sm">
                <thead class="sticky top-0 z-10">
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider whitespace-nowrap">
                    <th class="p-3.5 pl-5">Student</th>
                    <th class="p-3.5">Reg No</th>
                    <th class="p-3.5">SBTE Reg No / Edit</th>
                    <th class="p-3.5">Branch</th>
                    <th class="p-3.5">Sem</th>
                    <th class="p-3.5">Role</th>
                    <th class="p-3.5">Account Status</th>
                    <th class="p-3.5">Enrolled Status</th>
                    <th class="p-3.5 pr-5 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="usersTableBody" class="divide-y divide-slate-100">
                  <tr><td colspan="9" class="p-8 text-center text-slate-500 font-medium">Loading classroom students...</td></tr>
                </tbody>
              </table>
            </div>
          </div>

        </div> <!-- End panelRoster -->

        <!-- PANEL: STUDENT ROLL NUMBERS -->
        <div id="panelRollNumbers" class="{{ $activeTab === 'rollNumbers' ? '' : 'hidden' }} space-y-6">
          <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
              <div>
                <h3 class="font-bold text-slate-900 text-base sm:text-lg">Assign Class Roll Numbers</h3>
                <p class="text-xs text-slate-500 mt-0.5">Set the serial roll numbers for students in your supervised classroom.</p>
              </div>
              <div class="flex items-center gap-2.5 flex-wrap">
                <button type="button" onclick="autoFillRollNumbers()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-sm flex items-center gap-2 transition-all cursor-pointer shadow-2xs">
                  <x-ui.icon name="auto_awesome" class="w-4 h-4 text-amber-500" />
                  <span>Auto-Fill (A-Z)</span>
                </button>
                <button type="button" onclick="saveRollNumbers()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm flex items-center gap-2 cursor-pointer transition-all shadow-xs">
                  <x-ui.icon name="save" class="w-4 h-4" />
                  <span>Save Roll Numbers</span>
                </button>
              </div>
            </div>
            
            <div class="overflow-x-auto border border-slate-200/80 rounded-xl bg-white">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="bg-slate-50 text-slate-600 border-b border-slate-200 uppercase tracking-wider text-xs font-semibold">
                    <th class="p-3.5 pl-5 w-16 text-center">No.</th>
                    <th class="p-3.5 w-40">Reg No</th>
                    <th class="p-3.5 w-48">SBTE Exam No</th>
                    <th class="p-3.5">Student Name</th>
                    <th class="p-3.5 pr-5 w-36 text-center">Roll Number</th>
                  </tr>
                </thead>
                <tbody id="tutorRollNumberList" class="divide-y divide-slate-100">
                  <tr><td colspan="5" class="p-8 text-center text-slate-500 font-medium">Loading roll numbers...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- PANEL: AUDIT TRAIL -->
        <div id="panelAudit" class="{{ $activeTab === 'audit' ? '' : 'hidden' }} space-y-6">
          <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
              <div>
                <h3 class="font-bold text-slate-900 text-base sm:text-lg">Classroom Audit Trail</h3>
                <p class="text-xs text-slate-500 mt-0.5">Lifecycle events, password resets, and approval actions involving students in your classroom.</p>
              </div>
              <button type="button" onclick="loadAuditTrail()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center gap-2 shadow-xs">
                <x-ui.icon name="sync" class="w-4 h-4" />
                <span>Refresh Log</span>
              </button>
            </div>

            <div class="overflow-x-auto custom-scrollbar border border-slate-200/80 rounded-xl">
              <table class="w-full text-left border-collapse text-xs">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                    <th class="p-3.5 pl-4">Timestamp</th>
                    <th class="p-3.5">Actor</th>
                    <th class="p-3.5">Target Student (ID)</th>
                    <th class="p-3.5">Action</th>
                    <th class="p-3.5">IP Address</th>
                    <th class="p-3.5 pr-4">Details</th>
                  </tr>
                </thead>
                <tbody id="auditTableBody" class="divide-y divide-slate-100">
                  <tr><td colspan="6" class="p-6 text-center text-slate-500 font-medium">Querying classroom audit logs...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- PANEL: MY PROFILE -->
        <div id="panelProfile" class="{{ $activeTab === 'profile' ? '' : 'hidden' }} space-y-6">
          @include('partials.staff_profile_panel', ['hideAuditLog' => true])
        </div>

        <!-- PANEL: MENTORING BATCHES -->
        <div id="panelMentoring" class="{{ $activeTab === 'mentoring' ? '' : 'hidden' }} space-y-6">
          <!-- Header Controls -->
          <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs flex flex-wrap items-center justify-between gap-4">
            <div>
              <h3 class="font-bold text-slate-900 text-base sm:text-lg">Mentoring Batches &amp; Splitter</h3>
              <p class="text-xs text-slate-500 mt-0.5">Split students between yourself and the second mentor.</p>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
              <select id="mentorClassroomSelect" onchange="loadMentoringData()" class="bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 text-sm font-medium focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all cursor-pointer">
                <option value="">Loading classrooms...</option>
              </select>
              <button type="button" onclick="loadMentoringData()" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs">
                <x-ui.icon name="sync" class="w-4 h-4 text-slate-500" />
                <span>Refresh</span>
              </button>
              <button type="button" onclick="generateBacklogReport()" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/80 rounded-xl text-sm font-semibold transition-all cursor-pointer flex items-center gap-2 shadow-2xs">
                <x-ui.icon name="summarize" class="w-4 h-4 text-blue-600" />
                <span>Backlog Report</span>
              </button>
            </div>
          </div>

          <!-- Collapsible Batch Assignment Panel -->
          <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
            <div onclick="toggleBatchAssignment()" class="p-4 bg-slate-50/60 border-b border-slate-200/80 flex justify-between items-center cursor-pointer hover:bg-slate-100/70 transition-colors">
              <div class="flex items-center gap-2.5">
                <span id="batchAssignIcon" class="transition-transform duration-300">
                  <x-ui.icon name="chevron-down" class="w-4 h-4 text-blue-600" />
                </span>
                <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">Batch Assignment &amp; Mentorship Splitter Settings</h4>
              </div>
              <span class="text-xs text-slate-500 font-medium hidden sm:inline">Click to configure Batch A &amp; B assignments / unassigned students</span>
            </div>
            
            <div id="batchAssignmentContent" class="hidden p-6 border-t border-slate-100">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Unassigned Students -->
                <div class="bg-white border border-slate-200/80 rounded-2xl shadow-2xs flex flex-col overflow-hidden">
                  <div class="p-4 border-b border-slate-100 bg-amber-50/40 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                      <x-ui.icon name="person_off" class="w-4 h-4 text-amber-600" />
                      <div>
                        <h4 class="font-bold text-xs text-slate-900 uppercase">Unassigned Students</h4>
                        <p class="text-xs text-slate-500">Students without a mentor.</p>
                      </div>
                    </div>
                    <span id="unassignedCountBadge" class="bg-amber-100 text-amber-800 border border-amber-200 px-2 py-0.5 rounded-full font-bold text-xs">0</span>
                  </div>
                  <div class="flex-grow max-h-[300px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left text-xs">
                      <tbody id="unassignedList" class="divide-y divide-slate-100">
                        <tr><td class="p-4 text-center text-slate-500">Select a classroom to view.</td></tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Mentors Split View -->
                <div class="space-y-6">
                  <!-- Mentor A (Tutor) -->
                  <div class="bg-white border border-slate-200/80 rounded-2xl shadow-2xs flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-sky-50/40 flex justify-between items-center">
                      <div class="flex items-center gap-2">
                        <x-ui.icon name="person_pin" class="w-4 h-4 text-sky-600" />
                        <div>
                          <h4 class="font-bold text-xs text-sky-900 uppercase">Batch A (Tutor)</h4>
                          <p id="mentorAInfo" class="text-xs text-slate-500">Loading...</p>
                        </div>
                      </div>
                      <span id="batchACountBadge" class="bg-sky-100 text-sky-800 border border-sky-200 px-2 py-0.5 rounded-full font-bold text-xs">0</span>
                    </div>
                    <div class="flex-grow max-h-[180px] overflow-y-auto custom-scrollbar">
                      <table class="w-full text-left text-xs">
                        <tbody id="batchAList" class="divide-y divide-slate-100"></tbody>
                      </table>
                    </div>
                  </div>

                  <!-- Mentor B -->
                  <div class="bg-white border border-slate-200/80 rounded-2xl shadow-2xs flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-emerald-50/40 flex justify-between items-center">
                      <div class="flex items-center gap-2">
                        <x-ui.icon name="supervisor_account" class="w-4 h-4 text-emerald-600" />
                        <div>
                          <h4 class="font-bold text-xs text-emerald-900 uppercase">Batch B (Mentor)</h4>
                          <p id="mentorBInfo" class="text-xs text-slate-500">Loading...</p>
                        </div>
                      </div>
                      <span id="batchBCountBadge" class="bg-emerald-100 text-emerald-800 border border-emerald-200 px-2 py-0.5 rounded-full font-bold text-xs">0</span>
                    </div>
                    <div class="flex-grow max-h-[180px] overflow-y-auto custom-scrollbar">
                      <table class="w-full text-left text-xs">
                        <tbody id="batchBList" class="divide-y divide-slate-100"></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Mentoring Caseload Data View -->
          <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                  <x-ui.icon name="school" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                  <h3 class="text-base font-bold text-slate-900">Mentoring Caseload (Data View)</h3>
                  <p class="text-xs text-slate-500">Tutors see the full class; Mentors see only their assigned batch.</p>
                </div>
              </div>
              <div>
                <span class="bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full font-semibold border border-blue-200">📱 Mobile Parent Portal SMS Enabled</span>
              </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-xs uppercase tracking-wider">
                    <th class="p-3.5 pl-4">Student</th>
                    <th class="p-3.5">Reg No</th>
                    <th class="p-3.5">Batch Assigned</th>
                    <th class="p-3.5">Diary Logs</th>
                    <th class="p-3.5 pr-4 text-right">Actions</th>
                  </tr>
                </thead>
                <tbody id="myMentoringStudentsList" class="divide-y divide-slate-100">
                  <tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">Select a classroom to view caseload.</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- PANEL: ACTIVITY POINTS VERIFICATION -->
        <div id="panelActivity" class="{{ $activeTab === 'activity' ? '' : 'hidden' }} space-y-6">
          <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-5">
              <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
                  <x-ui.icon name="verified" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                  <h3 class="font-bold text-slate-900 text-base sm:text-lg">Activity Points Verification</h3>
                  <p class="text-xs text-slate-500 mt-0.5">Review and verify extracurricular claims submitted by students in your batch.</p>
                </div>
              </div>
              <button type="button" onclick="loadActivityClaims()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-sm font-semibold transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer">
                <x-ui.icon name="refresh" class="w-4 h-4 text-slate-500" />
                <span>Refresh Claims</span>
              </button>
            </div>

            <div id="activityContent" class="overflow-x-auto rounded-xl border border-slate-200/80">
              <table class="w-full text-left text-sm border-collapse whitespace-nowrap">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider text-xs">
                    <th class="p-3.5 pl-4">Submitted On</th>
                    <th class="p-3.5">Student</th>
                    <th class="p-3.5">Segment</th>
                    <th class="p-3.5">Activity &amp; Level</th>
                    <th class="p-3.5">Evidence</th>
                    <th class="p-3.5 text-center">Claimed</th>
                    <th class="p-3.5 pr-4 text-center">Action</th>
                  </tr>
                </thead>
                <tbody id="tutorActivityTableBody" class="divide-y divide-slate-100">
                  <tr><td colspan="7" class="p-6 text-center text-slate-500 font-medium">Loading claims...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- PANEL: LEAVE APPROVAL & REPORTS -->
        <div id="panelLeaveApproval" class="{{ $activeTab === 'leaveApproval' ? '' : 'hidden' }} space-y-6">
          <!-- Header -->
          <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs flex flex-wrap items-center justify-between gap-4">
            <div>
              <h3 class="font-bold text-slate-900 text-base sm:text-lg">Leave Approval &amp; Student Reports</h3>
              <p class="text-xs text-slate-500 mt-0.5">Review leave applications from students and view classroom reports.</p>
            </div>
            <div class="flex items-center gap-2.5">
              <select id="leaveClassroomSelect" onchange="loadClassroomLeaves()" class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-slate-900 text-sm font-semibold focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-all cursor-pointer">
                <option value="">Loading classrooms...</option>
              </select>
              <button type="button" onclick="loadClassroomLeaves()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center gap-2 shadow-2xs">
                <x-ui.icon name="sync" class="w-4 h-4 text-slate-500" />
                <span>Refresh</span>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Leave Table (col-span-2) -->
            <div class="xl:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <x-ui.icon name="approval" class="w-4 h-4 text-blue-600" />
                <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">Pending &amp; Recent Leaves</h4>
              </div>
              <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs border-collapse whitespace-nowrap">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                      <th class="p-3 pl-4">Student</th>
                      <th class="p-3">Semester</th>
                      <th class="p-3">Date</th>
                      <th class="p-3">Days</th>
                      <th class="p-3">Reason</th>
                      <th class="p-3">Status</th>
                      <th class="p-3 pr-4 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="classroomLeavesTableBody" class="divide-y divide-slate-100 text-slate-700">
                    <tr><td colspan="7" class="p-6 text-center text-slate-500 font-medium">Select a classroom to load leaves.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Mentorship Reports Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5 flex flex-col justify-between">
              <div>
                <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                  <x-ui.icon name="summarize" class="w-4 h-4 text-emerald-600" />
                  <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">Classroom Reports</h4>
                </div>
                <p class="text-slate-500 text-xs mt-2">Generate summary reports of student records for parents or administration.</p>
                <div class="space-y-4 mt-5">
                  <div>
                    <label class="block text-slate-600 font-semibold mb-1.5 text-xs">Select Student</label>
                    <select id="reportStudentSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-slate-900 text-sm outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 cursor-pointer">
                      <option value="">Select student...</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="space-y-2.5 pt-2">
                <button type="button" onclick="printStudentFullDiary()" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all cursor-pointer text-xs flex items-center justify-center gap-2 shadow-xs">
                  <x-ui.icon name="print" class="w-4 h-4" />
                  <span>Print Student Diary Report</span>
                </button>
                <button type="button" onclick="printStudentLeaveReport()" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition-all cursor-pointer text-xs flex items-center justify-center gap-2 shadow-xs">
                  <x-ui.icon name="summarize" class="w-4 h-4" />
                  <span>Print Student Leave Report</span>
                </button>
                <button type="button" onclick="printCondonationReport()" class="w-full py-2.5 bg-white hover:bg-rose-50 text-rose-700 border border-rose-200 rounded-xl font-semibold transition-all cursor-pointer text-xs flex items-center justify-center gap-2 shadow-2xs">
                  <x-ui.icon name="gavel" class="w-4 h-4 text-rose-600" />
                  <span>Print Condonation &amp; Shortage Report</span>
                </button>
              </div>
            </div>
          </div>
        </div>

      </main>
    </div>
  </div>

  @include('mentoring_diary_modal')

  <!-- BACKLOG REPORT MODAL -->
  <div id="backlogReportModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4 transition-all overflow-y-auto">
    <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-4xl p-6 shadow-2xl space-y-5 my-8 relative">
      <div class="flex justify-between items-center border-b border-slate-100 pb-4 sticky top-0 bg-white z-10">
        <div>
          <h2 class="text-lg font-bold text-slate-900">Backlog Report</h2>
          <p class="text-xs text-slate-500 mt-0.5">Students with and without backlogs over the 3-year diploma.</p>
        </div>
        <button type="button" onclick="document.getElementById('backlogReportModal').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer rounded-lg hover:bg-slate-100">
          <x-ui.icon name="close" class="w-5 h-5" />
        </button>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Without Backlog -->
        <div class="bg-white rounded-xl border border-emerald-200 overflow-hidden flex flex-col h-[480px]">
          <div class="bg-emerald-50 p-3.5 border-b border-emerald-100 flex justify-between items-center sticky top-0">
            <h3 class="text-xs font-bold text-emerald-800 flex items-center gap-2">
              <x-ui.icon name="check_circle" class="w-4 h-4 text-emerald-600" />
              <span>Completed Without Backlog</span>
            </h3>
            <span id="noBacklogCount" class="bg-emerald-600 text-white px-2 py-0.5 rounded-full text-xs font-bold">0</span>
          </div>
          <div class="overflow-y-auto flex-grow p-2 custom-scrollbar">
            <table class="w-full text-left text-xs">
              <tbody id="noBacklogList" class="divide-y divide-slate-100">
                <tr><td class="p-4 text-center text-slate-500 font-medium">Generating report...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- With Backlog -->
        <div class="bg-white rounded-xl border border-rose-200 overflow-hidden flex flex-col h-[480px]">
          <div class="bg-rose-50 p-3.5 border-b border-rose-100 flex justify-between items-center sticky top-0">
            <h3 class="text-xs font-bold text-rose-800 flex items-center gap-2">
              <x-ui.icon name="warning" class="w-4 h-4 text-rose-600" />
              <span>With Backlog</span>
            </h3>
            <span id="withBacklogCount" class="bg-rose-600 text-white px-2 py-0.5 rounded-full text-xs font-bold">0</span>
          </div>
          <div class="overflow-y-auto flex-grow p-2 custom-scrollbar">
            <table class="w-full text-left text-xs">
              <tbody id="withBacklogList" class="divide-y divide-slate-100">
                <tr><td class="p-4 text-center text-slate-500 font-medium">Generating report...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <div class="flex justify-end pt-3 border-t border-slate-100">
        <button type="button" onclick="document.getElementById('backlogReportModal').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-all cursor-pointer">
          Close Report
        </button>
      </div>
    </div>
  </div>

  <!-- PASSWORD RESET MODAL -->
  <div id="passwordModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
          <x-ui.icon name="lock_reset" class="w-4 h-4 text-blue-600" />
          <span>Password Reset</span>
        </h3>
        <button type="button" onclick="closePasswordModal()" class="p-1 text-slate-400 hover:text-slate-600 cursor-pointer rounded-lg hover:bg-slate-100">
          <x-ui.icon name="close" class="w-4 h-4" />
        </button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-500">
          Set a new password for <span id="pwdResetName" class="font-bold text-slate-800"></span> (<span id="pwdResetId" class="text-blue-600 font-mono font-semibold"></span>).
        </p>
        <div>
          <label class="block text-xs text-slate-600 font-semibold mb-1.5 uppercase tracking-wider">New Password</label>
          <input type="text" id="newPasswordInput" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all placeholder:text-slate-400" placeholder="Minimum 4 characters">
        </div>
      </div>

      <div id="pwdAlert" class="hidden p-3 rounded-xl text-xs font-semibold border"></div>

      <div class="flex gap-3 pt-2">
        <button type="button" onclick="closePasswordModal()" class="flex-1 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-xs transition-all cursor-pointer">Cancel</button>
        <button type="button" onclick="submitPasswordReset()" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs transition-all cursor-pointer shadow-xs">Save Changes</button>
      </div>
    </div>
  </div>

  <!-- AUDIT LOG MODAL FOR SINGLE STUDENT -->
  <div id="auditModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-2xl p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
          <x-ui.icon name="receipt_long" class="w-4 h-4 text-blue-600" />
          <span>Profile Audit Trail</span>
        </h3>
        <button type="button" onclick="closeAuditModal()" class="p-1 text-slate-400 hover:text-slate-600 cursor-pointer rounded-lg hover:bg-slate-100">
          <x-ui.icon name="close" class="w-4 h-4" />
        </button>
      </div>

      <div class="space-y-3">
        <p class="text-xs text-slate-500">
          History log for <span id="auditProfileName" class="font-bold text-slate-800"></span> (<span id="auditProfileId" class="text-blue-600 font-mono font-semibold"></span>).
        </p>

        <div class="max-h-[300px] overflow-y-auto custom-scrollbar border border-slate-200 rounded-xl">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                <th class="p-3">Time</th>
                <th class="p-3">Actor</th>
                <th class="p-3">Action</th>
                <th class="p-3">Details</th>
              </tr>
            </thead>
            <tbody id="modalAuditTableBody" class="divide-y divide-slate-100">
              <tr><td colspan="4" class="p-4 text-center text-slate-500">Loading audit history...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="flex justify-end pt-2 border-t border-slate-100">
        <button type="button" onclick="closeAuditModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all cursor-pointer">
          Close
        </button>
      </div>
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

      loadSupervisedClassroomHeader();

      if (activePanel === 'roster') loadUsers();
      if (activePanel === 'audit') loadAuditTrail();
      if (activePanel === 'profile') loadSelfSecurityLogs();
      if (activePanel === 'mentoring') {
        switchPanel('mentoring'); // Ensures UI is updated
      }
    });

    function loadSupervisedClassroomHeader() {
      fetch('/api/tutor/classroom/{{ session('userId') }}')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            window.supervisedClassroomId = data.classroomId;
            window.supervisedBatchYear = data.batchYear;
            window.supervisedCurrentSemester = data.currentSemester || 1;
            window.supervisedIsClassTutor = data.isClassTutor;
            window.supervisedTutorName = data.tutorName || 'Not Assigned';
            window.supervisedMentorName = data.mentorName || 'Not Assigned';

            const titleEl = document.getElementById('supervisedClassroomTitle');
            if (titleEl) {
              titleEl.innerText = `Supervised Classroom Directory — ${data.classroomId} (Semester S-${data.currentSemester || 1})`;
            }
            
            const printSemSelect = document.getElementById('printSemesterSelect');
            if (printSemSelect && data.currentSemester) {
              printSemSelect.value = 'S' + data.currentSemester;
            }
          }
        });
    }

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    const loadedPanels = {};

    function switchPanel(panelId, forceRefresh = false) {
      activePanel = panelId;
      
      const panels = ['roster', 'rollNumbers', 'audit', 'profile', 'mentoring', 'activity', 'leaveApproval'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) {
            nav.className = "tutor-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs cursor-pointer";
          }
        } else {
          if (el) el.classList.add('hidden');
          if (nav) {
            nav.className = "tutor-tab-btn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition-all whitespace-nowrap text-slate-600 hover:text-slate-900 hover:bg-slate-50 border border-transparent cursor-pointer";
          }
        }
      });

      const titles = {
        'roster': 'Supervised Class Roster',
        'rollNumbers': 'Assign Class Roll Numbers',
        'audit': 'Classroom Audit Trail',
        'profile': 'My Tutor Profile',
        'mentoring': 'Mentoring Batches & Splitter',
        'activity': 'Activity Points Verification',
        'leaveApproval': 'Leave Approval & Mentorship Reports'
      };
      
      const titleEl = document.getElementById('panelTitle') || document.querySelector('.topbar-title') || document.querySelector('header h1');
      if (titleEl) titleEl.innerText = titles[panelId] || 'Tutor Console';

      try {
        const url = new URL(window.location);
        url.searchParams.set('panel', panelId);
        url.searchParams.delete('tab');
        window.history.replaceState({}, '', url);
      } catch(e) {}

      if (!loadedPanels[panelId] || forceRefresh) {
        loadedPanels[panelId] = true;
        if (panelId === 'roster') loadUsers();
        if (panelId === 'rollNumbers') loadTutorStudents();
        if (panelId === 'audit') loadAuditTrail();
        if (panelId === 'profile') loadSelfSecurityLogs();
        if (panelId === 'mentoring') initMentoringPanel();
        if (panelId === 'activity') loadActivityClaims();
        if (panelId === 'leaveApproval') loadClassroomLeaves();
      }
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

    function toggleRoster() {
      const content = document.getElementById('rosterContent');
      const icon = document.getElementById('rosterIcon');
      content.classList.toggle('hidden');
      icon.classList.toggle('rotate-180');
    }

    let userSearchTimer = null;
    function debouncedLoadUsers() {
      clearTimeout(userSearchTimer);
      userSearchTimer = setTimeout(loadUsers, 250);
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
            <td colspan="9" class="p-8 text-center text-slate-500 font-medium font-sans">
              No classroom students found.
            </td>
          </tr>
        `;
        return;
      }

      users.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-100 hover:bg-slate-50/80 transition-all whitespace-nowrap";

        let statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pending</span>`;
        if (user.status === 'Approved') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Approved</span>`;
        } else if (user.status === 'Suspended') {
          statusBadge = `<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">Suspended</span>`;
        }

        let toggleButton = '';
        if (user.status === 'Pending') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 rounded-lg text-xs font-semibold text-white transition-all cursor-pointer shadow-2xs">
              Approve
            </button>
          `;
        } else if (user.status === 'Approved') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Suspended')" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg text-xs font-semibold text-amber-700 transition-all cursor-pointer">
              Suspend
            </button>
          `;
        } else if (user.status === 'Suspended') {
          toggleButton = `
            <button onclick="changeStatus('${user.id}', '${user.type}', 'Approved')" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-xs font-semibold text-emerald-700 transition-all cursor-pointer">
              Activate
            </button>
          `;
        }

        const initials = user.name ? user.name.split(' ').map(n => n[0]).filter(Boolean).slice(0, 2).join('').toUpperCase() : 'ST';
        const avatarHtml = user.photo_url 
          ? `<img src="${user.photo_url}" class="w-9 h-9 rounded-xl object-cover border border-slate-200 shadow-2xs shrink-0">` 
          : `<div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 font-bold text-xs flex items-center justify-center border border-blue-200 shadow-2xs shrink-0">${initials}</div>`;

        tr.innerHTML = `
          <td class="p-3.5 pl-5 flex items-center gap-3">
            ${avatarHtml}
            <div>
              <span class="font-bold text-slate-900 block text-sm">${user.name}</span>
              <span class="text-xs text-slate-500 block font-normal">${user.email || 'No email provided'}</span>
            </div>
          </td>
          <td class="p-3.5 font-mono font-semibold text-slate-600 text-xs">${user.id}</td>
          <td class="p-3.5">
            <button onclick="editSbteRegNo('${user.id}', '${user.sbte_reg_no || ''}')" class="text-blue-600 hover:text-blue-700 font-semibold font-mono text-xs hover:underline cursor-pointer" title="Click to Edit SBTE No">
              ${user.sbte_reg_no || '[Add SBTE No]'}
            </button>
          </td>
          <td class="p-3.5"><span class="font-bold font-mono text-xs bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md border border-slate-200">${user.branch}</span></td>
          <td class="p-3.5">
            ${user.type === 'student' ? `
              <button onclick="editStudentSemester('${user.id}', '${user.semester || 'S1'}')" class="text-blue-600 hover:text-blue-700 font-semibold text-xs hover:underline cursor-pointer" title="Click to Edit Semester">
                ${user.semester || 'S1'}
              </button>
            ` : '<span class="text-slate-500 font-medium text-xs">N/A</span>'}
          </td>
          <td class="p-3.5 text-xs text-slate-600 font-medium">${user.role}</td>
          <td class="p-3.5 text-xs">${statusBadge}</td>
          <td class="p-3.5">
            ${user.type === 'student' ? `
              <select onchange="updateAcademicStatusDirectly('${user.id}', this.value)" class="bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1 text-xs font-semibold outline-none focus:border-blue-600 focus:bg-white cursor-pointer ${
                user.academic_status === 'Active' ? 'text-emerald-700 font-semibold' :
                user.academic_status === 'Discontinued' ? 'text-amber-700 font-semibold' :
                'text-rose-700 font-semibold'
              }">
                <option value="Active" ${user.academic_status === 'Active' ? 'selected' : ''}>Active</option>
                <option value="Discontinued" ${user.academic_status === 'Discontinued' ? 'selected' : ''}>Discontinued</option>
                <option value="TC Issued" ${user.academic_status === 'TC Issued' ? 'selected' : ''}>TC Issued</option>
              </select>
            ` : '<span class="text-slate-500 font-medium text-xs">N/A</span>'}
          </td>
          <td class="p-3.5 pr-5 text-right space-x-1.5 text-xs">
            ${toggleButton}
            <button onclick="triggerPasswordReset('${user.id}', '${user.type}', '${user.name}')" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-lg text-xs font-semibold transition-all cursor-pointer">
              Reset Pwd
            </button>
            <button onclick="viewUserAudit('${user.id}', '${user.name}')" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-semibold transition-all cursor-pointer" title="View Audit Trail">
              Audit
            </button>
            <button onclick="confirmDeleteUser('${user.id}', '${user.type}', '${user.name}')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-semibold transition-all cursor-pointer" title="Delete Student">
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

    function editSbteRegNo(regNo, currentVal) {
      let newSbte = prompt("Enter new SBTE Registration Number for " + regNo + ":", currentVal);
      if (newSbte === null) return;
      
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      
      fetch(`/api/student/update/${regNo}`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ sbte_reg_no: newSbte })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('SBTE Register Number updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => indicator.classList.add('hidden'));
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
              tr.className = "border-b border-slate-100 hover:bg-slate-50/80 transition-all";
              
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3.5 pl-4 text-slate-500 font-mono text-xs">${date}</td>
                <td class="p-3.5 font-bold text-slate-900 text-xs">${log.performed_by_name || 'System'}<br><span class="text-xs text-slate-500 font-mono">${log.performed_by || ''}</span></td>
                <td class="p-3.5 font-bold text-slate-900 text-xs">${log.target_name || '-'}<br><span class="text-xs text-blue-600 font-mono">${log.target_id || ''}</span></td>
                <td class="p-3.5"><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">${log.action}</span></td>
                <td class="p-3.5 font-mono text-slate-500 text-xs">${log.ip_address || '-'}</td>
                <td class="p-3.5 pr-4 text-slate-700 font-sans text-xs leading-relaxed">${log.details || ''}</td>
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
                <td class="p-3 text-slate-500 font-mono text-xs">${date}</td>
                <td class="p-3 font-semibold text-slate-900 text-xs">${log.performed_by_name || 'System'}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-700 text-xs">${log.details || ''}</td>
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
                <td class="p-3 text-slate-500 font-mono text-xs">${date}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-700 text-xs">${log.details || ''}</td>
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


    // ==========================================
    // ACTIVITY POINTS LOGIC
    // ==========================================
    
    let rosterExpanded = false;
    function toggleRoster() {
      const content = document.getElementById('rosterContent');
      const icon = document.getElementById('rosterIcon');
      if (rosterExpanded) {
        content.style.maxHeight = '0px';
        content.style.opacity = '0';
        content.style.overflow = 'hidden';
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.style.maxHeight = '1000px';
        content.style.opacity = '1';
        icon.style.transform = 'rotate(0deg)';
        setTimeout(() => content.style.overflow = 'visible', 300);
      }
      rosterExpanded = !rosterExpanded;
    }

    let activityExpanded = false;
    function toggleActivity() {
      const content = document.getElementById('activityContent');
      const icon = document.getElementById('activityIcon');
      if (activityExpanded) {
        content.style.maxHeight = '0px';
        content.style.opacity = '0';
        content.style.overflow = 'hidden';
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.style.maxHeight = '1000px';
        content.style.opacity = '1';
        icon.style.transform = 'rotate(0deg)';
        setTimeout(() => content.style.overflow = 'visible', 300);
      }
      activityExpanded = !activityExpanded;
    }

    function loadActivityClaims() {
      fetch('/api/tutor/activity-points')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const tbody = document.getElementById('tutorActivityTableBody');
            let html = '';
            
            data.claims.forEach(c => {
              let actionsHtml = '';
              let submittedDate = c.created_at ? new Date(c.created_at) : null;
              let submittedHtml = submittedDate 
                ? `<span class="block text-xs font-bold text-slate-900">${submittedDate.toLocaleDateString()}</span><span class="block text-xs text-slate-500">${submittedDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>`
                : `<span class="text-xs text-slate-500">N/A</span>`;

              if (c.status === 'Pending') {
                actionsHtml = `
                  <div class="flex items-center justify-center gap-2">
                    <input type="number" id="award_${c.id}" min="0" max="${c.points_claimed}" value="${c.points_claimed}" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-xs text-slate-900 font-semibold focus:bg-white focus:border-blue-600 outline-none">
                    <button onclick="verifyClaim('${c.id}', 'Verified')" class="px-2 py-1 bg-teal-600 hover:bg-teal-500 rounded text-white text-xs font-bold">Approve</button>
                    <button onclick="verifyClaim('${c.id}', 'Rejected')" class="px-2 py-1 bg-red-950 text-red-400 border border-red-900 rounded text-xs font-bold">Reject</button>
                  </div>
                `;
              } else {
                let verifiedDateStr = c.verified_at ? new Date(c.verified_at).toLocaleDateString() : '';
                let noteHtml = '';
                if (c.status === 'Rejected' && c.rejection_note) {
                  noteHtml = `<div class="mt-1 text-xs text-rose-400/80 leading-tight bg-rose-950/30 p-1 rounded border border-rose-900/30 text-left">Note: ${c.rejection_note}</div>`;
                }
                actionsHtml = `
                  <div class="flex flex-col items-center">
                    <span class="font-bold ${c.status === 'Verified' ? 'text-emerald-600' : 'text-red-400'}">${c.status} (${c.points_awarded} pts)</span>
                    ${verifiedDateStr ? `<span class="text-xs text-slate-500 mt-0.5">On: ${verifiedDateStr}</span>` : ''}
                    ${noteHtml}
                  </div>
                `;
              }

              html += `
                <tr class="hover:bg-slate-900/50 transition-premium">
                  <td class="p-3">${submittedHtml}</td>
                  <td class="p-3">
                    <span class="font-bold text-slate-900 block text-xs">${c.student.name}</span>
                    <span class="text-xs text-slate-500 font-mono">${c.reg_no}</span>
                  </td>
                  <td class="p-3 text-xs font-semibold text-slate-600">${c.activity_segment}</td>
                  <td class="p-3">
                    <span class="block text-xs text-slate-700">${c.activity_name}</span>
                    <span class="block text-xs text-slate-500">${c.level}</span>
                  </td>
                  <td class="p-3 text-xs text-slate-500 whitespace-normal min-w-[150px]">${c.document_reference || 'N/A'}</td>
                  <td class="p-3 text-center text-xs font-bold text-slate-900">${c.points_claimed}</td>
                  <td class="p-3 text-center">${actionsHtml}</td>
                </tr>
              `;
            });
            
            if (data.claims.length === 0) {
              html = `<tr><td colspan="6" class="p-6 text-center text-slate-500 text-xs">No pending activity claims found for your classroom.</td></tr>`;
            }
            
            tbody.innerHTML = html;
          }
        });
    }

    function verifyClaim(id, status) {
      let awarded = 0;
      let note = '';
      if (status === 'Verified') {
        awarded = document.getElementById(`award_${id}`).value;
      } else if (status === 'Rejected') {
        note = prompt("Enter a reason for rejection (optional):");
        if (note === null) return; // User cancelled
      }
      
      fetch(`/api/tutor/activity-points/${id}/verify`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ status: status, points_awarded: awarded, rejection_note: note })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage(`Claim marked as ${status}.`);
          loadActivityClaims();
        } else {
          showGlobalMessage(data.message, true);
        }
      });
    }

    // ==========================================
    // MENTORING BATCHES LOGIC
    // ==========================================

    let mentoringDataCache = null;
    let selectedMentoringClassroomId = null;

    function ensureMentoringClassroomsLoaded(callback) {
      const select = document.getElementById('mentorClassroomSelect');
      const leaveSelect = document.getElementById('leaveClassroomSelect');
      
      if (select && select.options.length > 0 && select.value !== "" && select.value !== "Loading...") {
        if (callback) callback();
        return;
      }
      
      select.innerHTML = '<option value="">Loading...</option>';
      if (leaveSelect) leaveSelect.innerHTML = '<option value="">Loading...</option>';
      
      fetch('/api/mentoring/my-batches')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            select.innerHTML = '';
            if (leaveSelect) leaveSelect.innerHTML = '';
            if (data.batches.length === 0) {
              select.innerHTML = '<option value="">No mentored classrooms</option>';
              if (leaveSelect) leaveSelect.innerHTML = '<option value="">No mentored classrooms</option>';
              return;
            }

            data.batches.forEach(b => {
              const opt = document.createElement('option');
              opt.value = b.classroom_id;
              const isGraduated = (b.current_semester || 1) > 6;
              opt.innerText = `${b.classroom_id} (Admission ${b.batch_year})${isGraduated ? ' (Graduated)' : ''}`;
              select.appendChild(opt);

              if (leaveSelect) {
                const opt2 = opt.cloneNode(true);
                leaveSelect.appendChild(opt2);
              }
            });
            
            selectedMentoringClassroomId = select.value;
            if (callback) callback();
          } else {
            select.innerHTML = '<option value="">Failed to load</option>';
          }
        })
        .catch(() => {
          select.innerHTML = '<option value="">Error</option>';
        });
    }

    function initMentoringPanel() {
      ensureMentoringClassroomsLoaded(() => {
        loadMentoringData();
      });
    }

    function generateBacklogReport() {
      if (!selectedMentoringClassroomId) {
        showGlobalMessage("Please select a classroom first.", true);
        return;
      }
      
      const modal = document.getElementById('backlogReportModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      
      document.getElementById('noBacklogList').innerHTML = '<tr><td class="p-4 text-center text-slate-500">Loading data...</td></tr>';
      document.getElementById('withBacklogList').innerHTML = '<tr><td class="p-4 text-center text-slate-500">Loading data...</td></tr>';
      document.getElementById('noBacklogCount').innerText = '0';
      document.getElementById('withBacklogCount').innerText = '0';
      
      fetch(`/api/mentoring/backlog-report/${selectedMentoringClassroomId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const noBacklogs = data.no_backlogs || [];
            const withBacklogs = data.with_backlogs || [];
            
            document.getElementById('noBacklogCount').innerText = noBacklogs.length;
            document.getElementById('withBacklogCount').innerText = withBacklogs.length;
            
            let noHtml = '';
            if (noBacklogs.length === 0) {
              noHtml = '<tr><td class="p-4 text-center text-slate-500">No students found.</td></tr>';
            } else {
              noBacklogs.forEach(s => {
                noHtml += `
                  <tr class="hover:bg-slate-900/30 transition-premium">
                    <td class="p-3">
                      <div class="font-bold text-slate-200 text-xs">${s.name}</div>
                      <div class="text-xs text-slate-500 font-mono">${s.reg_no}</div>
                    </td>
                  </tr>
                `;
              });
            }
            document.getElementById('noBacklogList').innerHTML = noHtml;
            
            let withHtml = '';
            if (withBacklogs.length === 0) {
              withHtml = '<tr><td class="p-4 text-center text-slate-500">No students found.</td></tr>';
            } else {
              withBacklogs.forEach(s => {
                withHtml += `
                  <tr class="hover:bg-slate-900/30 transition-premium">
                    <td class="p-3">
                      <div class="font-bold text-slate-200 text-xs">${s.name}</div>
                      <div class="text-xs text-slate-500 font-mono">${s.reg_no}</div>
                    </td>
                    <td class="p-3 text-right">
                      <span class="bg-rose-900/40 text-rose-400 px-2 py-1 rounded text-xs font-bold border border-rose-800/50">${s.backlog_count} Backlogs</span>
                    </td>
                  </tr>
                `;
              });
            }
            document.getElementById('withBacklogList').innerHTML = withHtml;
          } else {
            document.getElementById('noBacklogList').innerHTML = `<tr><td class="p-4 text-center text-red-500">Error: ${data.message}</td></tr>`;
            document.getElementById('withBacklogList').innerHTML = `<tr><td class="p-4 text-center text-red-500">Error: ${data.message}</td></tr>`;
          }
        })
        .catch(err => {
          console.error(err);
          document.getElementById('noBacklogList').innerHTML = '<tr><td class="p-4 text-center text-red-500">Failed to load data.</td></tr>';
          document.getElementById('withBacklogList').innerHTML = '<tr><td class="p-4 text-center text-red-500">Failed to load data.</td></tr>';
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
            <button onclick="assignStudentBatch('${regNo}', 'A')" class="px-2 py-1 bg-sky-600 hover:bg-sky-500 text-white rounded text-xs font-bold mr-1">To A</button>
            <button onclick="assignStudentBatch('${regNo}', 'B')" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded text-xs font-bold">To B</button>
          `;
        } else if (currentBatch === 'A') {
          return `<button onclick="assignStudentBatch('${regNo}', 'B')" class="px-2 py-1 border border-emerald-600 text-emerald-400 hover:bg-emerald-950 rounded text-xs font-bold">Move to B</button>`;
        } else if (currentBatch === 'B') {
          return `<button onclick="assignStudentBatch('${regNo}', 'A')" class="px-2 py-1 border border-sky-600 text-sky-400 hover:bg-sky-950 rounded text-xs font-bold">Move to A</button>`;
        }
      };

      // Unassigned List
      unassignedList.innerHTML = '';
      if (data.unassigned.length === 0) unassignedList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">No unassigned students.</td></tr>';
      data.unassigned.forEach(s => {
        unassignedList.innerHTML += `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80">
            <td class="p-3 font-bold text-slate-900">${s.name}</td>
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
          <tr class="border-b border-sky-100 hover:bg-sky-50/60">
            <td class="p-3 font-bold text-sky-900">${s.name}</td>
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
          <tr class="border-b border-emerald-100 hover:bg-emerald-50/60">
            <td class="p-3 font-bold text-emerald-900">${s.name}</td>
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
            <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all">
              <td class="p-3.5 pl-4 font-bold text-slate-900 text-sm">${s.name}</td>
              <td class="p-3.5 font-mono text-slate-600 font-semibold text-xs">${s.reg_no}</td>
              <td class="p-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border ${s.batch_label === 'A' ? 'bg-sky-50 text-sky-700 border-sky-200' : (s.batch_label === 'B' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200')}">
                  ${batchName}
                </span>
              </td>
              <td class="p-3.5">
                <span class="text-xs font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                  ${s.diary_count || 0} entries
                </span>
              </td>
              <td class="p-3.5 pr-4 text-right whitespace-nowrap space-x-1.5">
                <a href="sms:${s.guardian_mobile || s.phone || ''}?body=${encodeURIComponent('Carmel Poly: View your ward (' + s.name + ') live attendance & status portal: ' + window.location.origin + '/parent/dashboard/' + s.reg_no)}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold border border-slate-200 transition-all no-underline shadow-2xs cursor-pointer" title="Send SMS Link to Parent">
                  <span>📱</span><span>SMS Portal</span>
                </a>
                <a href="/tutor/mentoring-diary/${s.reg_no}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all no-underline shadow-xs cursor-pointer">
                  <span>View Diary</span>
                </a>
              </td>
            </tr>
          `;
        });
      }

      // Populate reportStudentSelect
      const reportStudentSelect = document.getElementById('reportStudentSelect');
      if (reportStudentSelect) {
        reportStudentSelect.innerHTML = '<option value="">Select student...</option>';
        const allStudents = [...data.batch_a, ...data.batch_b, ...data.unassigned];
        allStudents.forEach(s => {
          const opt = document.createElement('option');
          opt.value = s.reg_no;
          opt.innerText = `${s.name} (${s.reg_no})`;
          reportStudentSelect.appendChild(opt);
        });
      }
    }

    function viewStudentDiary(regNo, name) { window.location.href = '/tutor/mentoring-diary/' + regNo; }

    function closeDiaryModal() { closeFullMentoringDiaryModal(); }

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

    function toggleBatchAssignment() {
      const content = document.getElementById('batchAssignmentContent');
      const icon = document.getElementById('batchAssignIcon');
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.classList.add('hidden');
        icon.style.transform = '';
      }
    }

    // --- LEAVE APPROVAL & REPORTS TAB LOGIC ---
    let selectedLeaveClassroomId = '';

    function loadClassroomLeaves() {
      const selectEl = document.getElementById('leaveClassroomSelect');
      if (!selectEl || selectEl.options.length === 0 || selectEl.value === "" || selectEl.value === "Loading...") {
        ensureMentoringClassroomsLoaded(() => {
          loadClassroomLeaves();
        });
        return;
      }
      const classroomId = selectEl.value || selectedMentoringClassroomId;
      if (!classroomId) return;
      selectedLeaveClassroomId = classroomId;

      fetch(`/api/mentoring/classroom/${classroomId}/leaves`, {
        headers: getHeaders()
      })
      .then(res => res.json())
      .then(resData => {
        const tbody = document.getElementById('classroomLeavesTableBody');
        tbody.innerHTML = '';
        if (resData.status === 'SUCCESS' && resData.data.length > 0) {
          resData.data.forEach(lv => {
            const statColor = lv.status === 'Approved' ? 'text-green-400' : (lv.status === 'Rejected' ? 'text-red-400' : 'text-amber-400');
            const parentInformed = lv.parent_informed ? '<span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded text-[10px]">Informed</span>' : '';
            
            let actionHtml = '';
            if (lv.status === 'Pending') {
              actionHtml = `
                <button onclick="tutorApproveLeave(${lv.id}, 'Approved')" class="px-2 py-1 bg-green-700/30 text-green-400 hover:bg-green-600 hover:text-white rounded text-[10px] font-bold mr-1 transition-premium cursor-pointer">Approve</button>
                <button onclick="tutorApproveLeave(${lv.id}, 'Rejected')" class="px-2 py-1 bg-red-700/30 text-red-400 hover:bg-red-600 hover:text-white rounded text-[10px] font-bold transition-premium cursor-pointer">Reject</button>
              `;
            } else {
              actionHtml = `<span class="text-xs text-slate-500 font-bold">${lv.status}</span>`;
            }

            tbody.innerHTML += `
              <tr class="border-b border-slate-100 hover:bg-slate-50/80">
                <td class="p-3 font-bold text-slate-900">${lv.student_name} <span class="text-[10px] text-slate-500 font-mono block">${lv.reg_no}</span></td>
                <td class="p-3 text-slate-900 font-semibold">${lv.semester}</td>
                <td class="p-3 text-slate-700 text-xs">${lv.leave_date}</td>
                <td class="p-3 text-slate-900 font-semibold">${lv.no_of_days} day(s) ${parentInformed}</td>
                <td class="p-3 max-w-[150px] truncate" title="${lv.reason || ''}">${lv.reason || '-'}</td>
                <td class="p-3 font-bold ${statColor}">${lv.status}</td>
                <td class="p-3 text-right whitespace-nowrap">${actionHtml}</td>
              </tr>
            `;
          });
        } else {
          tbody.innerHTML = '<tr><td colspan="7" class="p-6 text-center text-slate-500">No leave records found for this classroom.</td></tr>';
        }

        // Populate reportStudentSelect dropdown for this classroom
        const reportStudentSelect = document.getElementById('reportStudentSelect');
        if (reportStudentSelect) {
          reportStudentSelect.innerHTML = '<option value="">Select student...</option>';
          if (resData.status === 'SUCCESS' && resData.students) {
            resData.students.forEach(s => {
              const opt = document.createElement('option');
              opt.value = s.reg_no;
              opt.innerText = `${s.name} (${s.reg_no})`;
              reportStudentSelect.appendChild(opt);
            });
          }
        }
      });
    }

    function tutorApproveLeave(leaveId, decision) {
      if (!confirm('Are you sure you want to ' + decision.toLowerCase() + ' this leave request?')) return;
      fetch('/api/mentoring/leave/approve', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ id: leaveId, status: decision })
      })
      .then(res => res.json())
      .then(resData => {
        if (resData.status === 'SUCCESS') {
          loadClassroomLeaves();
        } else {
          alert('Error: ' + resData.message);
        }
      });
    }

    function printStudentFullDiary() {
      const regNo = document.getElementById('reportStudentSelect').value;
      if (!regNo) {
        alert('Please select a student.');
        return;
      }
      window.open(`/diary/${regNo}/print`, '_blank');
    }

    function printStudentLeaveReport() {
      const regNo = document.getElementById('reportStudentSelect').value;
      if (!regNo) {
        alert('Please select a student.');
        return;
      }
      window.open(`/diary/${regNo}/leave-report`, '_blank');
    }

    function printCondonationReport() {
      const selectEl = document.getElementById('leaveClassroomSelect');
      const classroomId = selectEl.value || selectedMentoringClassroomId;
      if (!classroomId) {
        alert('Please select a classroom first.');
        return;
      }
      window.open(`/classroom/${classroomId}/condonation-report`, '_blank');
    }

    function loadTutorStudents() {
      const list = document.getElementById('tutorRollNumberList');
      list.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">Loading students...</td></tr>';
      fetch('/api/tutor/attendance/students')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            let html = '';
            if (data.students.length === 0) {
              list.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">No students in your classroom.</td></tr>';
              return;
            }
            data.students.forEach((s, idx) => {
              html += `
                <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all student-roll-row" data-reg="${s.reg_no}">
                  <td class="p-3.5 pl-5 text-center font-bold text-slate-500 text-xs">${idx+1}</td>
                  <td class="p-3.5 font-mono font-semibold text-slate-600 text-xs">${s.reg_no}</td>
                  <td class="p-3.5 font-mono font-semibold text-blue-600 text-xs">${s.sbte_reg_no || '-'}</td>
                  <td class="p-3.5 font-bold text-slate-900 text-sm">${s.name}</td>
                  <td class="p-2.5 pr-5 text-center">
                    <input type="number" class="w-24 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-center font-bold text-slate-900 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 roll-no-input text-sm outline-none transition-all shadow-2xs" value="${s.roll_no || ''}" min="1" placeholder="-">
                  </td>
                </tr>
              `;
            });
            list.innerHTML = html;
          } else {
            list.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-red-400">${data.message || 'Failed to load students.'}</td></tr>`;
          }
        });
    }

    function autoFillRollNumbers() {
      const rows = Array.from(document.querySelectorAll('.student-roll-row'));
      if (rows.length === 0) return;

      // Sort rows alphabetically by student name
      rows.sort((a, b) => {
        const nameA = a.querySelector('td:nth-child(4)').innerText.trim().toLowerCase();
        const nameB = b.querySelector('td:nth-child(4)').innerText.trim().toLowerCase();
        return nameA.localeCompare(nameB);
      });

      // Update the roll number inputs sequentially on screen
      rows.forEach((row, index) => {
        const input = row.querySelector('.roll-no-input');
        if (input) {
          input.value = index + 1;
        }
      });
      
      showGlobalMessage('Roll numbers auto-filled alphabetically (1 to ' + rows.length + '). Review and click Save.');
    }

    function saveRollNumbers() {
      const rows = document.querySelectorAll('.student-roll-row');
      const rollNumbers = [];
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const rollNoVal = row.querySelector('.roll-no-input').value.trim();
        rollNumbers.push({
          reg_no: regNo,
          roll_no: rollNoVal ? parseInt(rollNoVal) : null
        });
      });

      fetch('/api/tutor/attendance/roll-numbers', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ roll_numbers: rollNumbers })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage(data.message);
          loadTutorStudents();
        } else {
          showGlobalMessage(data.message || "Failed to update roll numbers.", true);
        }
      })
      .catch(err => {
        console.error(err);
        showGlobalMessage("Error saving roll numbers.", true);
      });
    }

    function printClassRegister() {
      const semSelect = document.getElementById('printSemesterSelect');
      if (!semSelect) return;
      const targetSem = semSelect.value; // e.g. "S3"
      const targetSemNum = parseInt(targetSem.replace('S', ''));

      const tutorMobile = "{{ session('userId') }}";
      
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch(`/api/tutor/classroom/${tutorMobile}`)
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status !== 'SUCCESS') {
            alert('Failed to retrieve classroom data: ' + data.message);
            return;
          }

          const students = data.students || [];
          
          // 1. Group students
          const activeList = [];
          const discontinuedList = [];

          students.forEach(s => {
            const isInactive = s.academic_status === 'Discontinued' || s.academic_status === 'TC Issued';
            const studentSemNum = parseInt(String(s.semester || 'S1').replace('S', ''));

            if (isInactive && studentSemNum < targetSemNum) {
              // Discontinued in a prior semester
              discontinuedList.push(s);
            } else if (!isInactive) {
              // Active student in target semester
              activeList.push(s);
            }
          });

          // 2. Sort active students alphabetically by name
          activeList.sort((a, b) => a.name.localeCompare(b.name));

          // 3. Build Print HTML
          const printWindow = window.open('', '_blank');
          if (!printWindow) {
            alert('Popup blocker blocked the print preview. Please allow popups.');
            return;
          }

          const branchName = "{{ session('userBranch') }}".toUpperCase();
          const batchYear = window.supervisedBatchYear || data.batchYear || 'N/A';
          const batchEnd = parseInt(batchYear) ? parseInt(batchYear) + 3 : 'N/A';
          const tutorName = window.supervisedTutorName || data.tutorName || 'Not Assigned';
          const mentorName = window.supervisedMentorName || data.mentorName || 'Not Assigned';

          const printDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

          let activeRows = '';
          activeList.forEach((s, idx) => {
            const isLateral = (s.reg_no && s.reg_no.toUpperCase().endsWith('L')) || (s.sbte_reg_no && s.sbte_reg_no.toUpperCase().endsWith('L'));
            let remark = s.status_notes || '-';
            if (isLateral) {
              remark = remark !== '-' ? 'Lateral Entry; ' + remark : 'Lateral Entry';
            }
            activeRows += `
              <tr>
                <td>${idx + 1}</td>
                <td>${s.name}</td>
                <td>${s.reg_no}</td>
                <td>${s.sbte_reg_no || '-'}</td>
                <td>${s.admission_year || 'N/A'}</td>
                <td>${s.semester || 'S1'}</td>
                <td>${s.academic_status || 'Active'}</td>
                <td>${remark}</td>
              </tr>
            `;
          });

          if (activeList.length === 0) {
            activeRows = `<tr><td colspan="8" style="text-align:center; padding:15px; color:#555;">No active students in this semester.</td></tr>`;
          }

          let discontinuedRows = '';
          discontinuedList.forEach((s, idx) => {
            const leftSem = s.semester || 'S1';
            const isLateral = (s.reg_no && s.reg_no.toUpperCase().endsWith('L')) || (s.sbte_reg_no && s.sbte_reg_no.toUpperCase().endsWith('L'));
            let remark = s.status_notes || '-';
            if (isLateral) {
              remark = remark !== '-' ? 'Lateral Entry; ' + remark : 'Lateral Entry';
            }
            discontinuedRows += `
              <tr>
                <td>${idx + 1}</td>
                <td>${s.name}</td>
                <td>${s.reg_no}</td>
                <td>${s.admission_year || 'N/A'}</td>
                <td>${s.semester || 'S1'}</td>
                <td>${s.academic_status}</td>
                <td>${remark}</td>
              </tr>
            `;
          });

          let discontinuedSection = '';
          if (discontinuedList.length > 0) {
            discontinuedSection = `
              <div style="margin-top: 30px;">
                <h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #334155; padding-bottom: 5px; margin-bottom: 10px; color: #1e293b;">
                  Discontinued / TC Issued Students (Prior to ${targetSem})
                </h3>
                <table class="report-table">
                  <thead>
                    <tr>
                      <th style="width: 5%;">No.</th>
                      <th>Student Name</th>
                      <th style="width: 15%;">Register No</th>
                      <th style="width: 12%;">Adm Year</th>
                      <th style="width: 8%;">Sem</th>
                      <th style="width: 15%;">Enrolled Status</th>
                      <th style="width: 25%;">Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${discontinuedRows}
                  </tbody>
                </table>
              </div>
            `;
          }

          const html = `
            <!DOCTYPE html>
            <html>
            <head>
              <title>Class Register - ${data.classroomId} (${targetSem})</title>
              <style>
                @media print {
                  @page {
                    size: A4 landscape;
                    margin: 1.5cm;
                  }
                  body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                  }
                }
                body {
                  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
                  color: #0f172a;
                  margin: 0;
                  padding: 10px;
                  background-color: #fff;
                }
                .header-container {
                  text-align: center;
                  border-bottom: 3px double #000;
                  padding-bottom: 12px;
                  margin-bottom: 20px;
                }
                .college-name {
                  font-size: 20px;
                  font-weight: 900;
                  letter-spacing: 1px;
                  margin: 0;
                  color: #000;
                }
                .dept-name {
                  font-size: 13px;
                  font-weight: bold;
                  margin: 4px 0 0 0;
                  color: #334155;
                  letter-spacing: 0.5px;
                }
                .report-title {
                  font-size: 15px;
                  font-weight: 800;
                  margin: 8px 0 0 0;
                  text-transform: uppercase;
                  color: #000;
                  background: #f1f5f9;
                  display: inline-block;
                  padding: 4px 16px;
                  border-radius: 4px;
                }
                .meta-grid {
                  display: grid;
                  grid-template-columns: repeat(4, 1fr);
                  gap: 10px;
                  margin-bottom: 20px;
                  font-size: 12px;
                  background-color: #f8fafc;
                  border: 1px solid #e2e8f0;
                  padding: 12px;
                  border-radius: 8px;
                }
                .meta-item {
                  display: flex;
                  flex-direction: column;
                }
                .meta-label {
                  font-weight: bold;
                  color: #64748b;
                  text-transform: uppercase;
                  font-size: 9px;
                  margin-bottom: 2px;
                }
                .meta-value {
                  font-weight: bold;
                  color: #0f172a;
                  font-size: 12px;
                }
                .report-table {
                  width: 100%;
                  border-collapse: collapse;
                  margin-top: 10px;
                  font-size: 12px;
                }
                .report-table th, .report-table td {
                  border: 1px solid #cbd5e1;
                  padding: 8px 10px;
                  text-align: left;
                }
                .report-table th {
                  background-color: #f1f5f9;
                  font-weight: bold;
                  color: #1e293b;
                  text-transform: uppercase;
                  font-size: 10px;
                }
                .report-table tr:nth-child(even) {
                  background-color: #f8fafc;
                }
                .footer-signatures {
                  margin-top: 50px;
                  display: flex;
                  justify-content: space-between;
                  font-size: 12px;
                  font-weight: bold;
                  padding: 0 20px;
                }
                .sig-line {
                  border-top: 1.5px solid #000;
                  width: 200px;
                  text-align: center;
                  padding-top: 5px;
                  margin-top: 40px;
                }
              </style>
            </head>
            <body>
              <div class="header-container" style="position: relative;">
                <div style="position: absolute; right: 0; top: 0; font-size: 11px; font-weight: bold; color: #475569;">
                  Print Date: ${printDate}
                </div>
                <div class="college-name">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</div>
                <div class="dept-name">DEPARTMENT OF ${branchName} ENGINEERING</div>
                <div class="report-title">Class Register - Admission ${batchYear}</div>
              </div>

              <div class="meta-grid">
                <div class="meta-item">
                  <span class="meta-label">Classroom ID</span>
                  <span class="meta-value">${data.classroomId}</span>
                </div>
                <div class="meta-item">
                  <span class="meta-label">Academic Semester</span>
                  <span class="meta-value">${targetSem}</span>
                </div>
                <div class="meta-item">
                  <span class="meta-label">Class Tutor</span>
                  <span class="meta-value">${tutorName}</span>
                </div>
                <div class="meta-item">
                  <span class="meta-label">Class Mentor</span>
                  <span class="meta-value">${mentorName}</span>
                </div>
              </div>

              <table class="report-table">
                <thead>
                  <tr>
                    <th style="width: 5%;">Roll No.</th>
                    <th>Student Name</th>
                    <th style="width: 15%;">Register No</th>
                    <th style="width: 15%;">SBTE Exam No</th>
                    <th style="width: 10%;">Adm Year</th>
                    <th style="width: 8%;">Sem</th>
                    <th style="width: 12%;">Enrolled Status</th>
                    <th style="width: 25%;">Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  ${activeRows}
                </tbody>
              </table>

              ${discontinuedSection}

              <div class="footer-signatures">
                <div class="sig-line">Class Tutor</div>
                <div class="sig-line">Class Mentor</div>
                <div class="sig-line">Head of Department</div>
              </div>

              <script>
                window.onload = function() {
                  window.print();
                };
              <\/script>
            </body>
            </html>
          `;

          printWindow.document.open();
          printWindow.document.write(html);
          printWindow.document.close();
        })
        .catch(err => {
          if (indicator) indicator.classList.add('hidden');
          console.error(err);
          alert('Error preparing print preview.');
        });
    }
  
  </script>

  @include('partials.support_desk_overlay')
</body>
</html>