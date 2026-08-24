<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - Student Mentoring Diary</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Modern Typography (Poppins) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
  </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen font-sans antialiased sidebar-preload">
  <script> window.TARGET_REG_NO = "{{ $studentRegNo }}"; </script>

  @php
    $student = $student ?? \App\Models\Student::where('reg_no', strtoupper($studentRegNo))->first();
    $isLet = ($student->admission_type ?? '') === 'LET' || session('userAdmissionType') === 'LET';
    $activityGoal = $isLet ? 40 : 60;
    $userRole = session('userRole', 'Tutor');
    $dashboardUrl = match($userRole) {
        'Super_Admin', 'SuperAdmin' => '/dashboard/superadmin',
        'Principal'   => '/dashboard/principal',
        'HOD'         => '/dashboard/hod',
        'Admin'       => '/dashboard/admin',
        'Demonstrator'=> '/dashboard/demonstrator',
        default       => '/dashboard/tutor',
    };
    $studentName = $student->name ?? 'Student Record';
    $studentBranch = $student->branch ?? 'N/A';
    $studentBatch = $student && $student->admission_year ? ('Batch ' . $student->admission_year . ' (' . ($student->semester ?? 'S1') . ')') : 'N/A';
    $initials = strtoupper(substr($studentName, 0, 2));
  @endphp

  <!-- Master Application Shell -->
  <div class="flex min-h-screen bg-[#FAFAFB]">

    <!-- Global Sidebar Navigation Component -->
    <x-layout.sidebar role="tutor" active="tutor_console" />

    <!-- Main Viewport Container -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#FAFAFB]">
      
      <!-- Global Topbar Header Component -->
      <x-layout.topbar title="Student Mentoring Diary" subtitle="Full academic, attendance, and mentoring lifecycle record." />

      <!-- Scrollable Main Workspace -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- Top Status Alert Banner -->
        <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all shadow-2xs"></div>

        <!-- Student Profile & Action Header Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
              <div class="shrink-0">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 font-bold text-base flex items-center justify-center border border-blue-200 shadow-xs">
                  {{ $initials }}
                </div>
              </div>
              <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                  <h2 class="text-base font-bold text-slate-900">{{ $studentName }}</h2>
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Active Student</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-slate-500 font-medium mt-1 flex-wrap">
                  <span>Reg No: <strong class="text-blue-600 font-mono font-semibold">{{ $studentRegNo }}</strong></span>
                  <span>•</span>
                  <span>Branch: <strong class="text-slate-800 font-semibold">{{ $studentBranch }}</strong></span>
                  <span>•</span>
                  <span>Batch: <strong class="text-slate-800 font-semibold">{{ $studentBatch }}</strong></span>
                </div>
              </div>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center gap-2.5 flex-wrap">
              <a href="{{ $dashboardUrl }}" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-xs transition-all flex items-center gap-1.5 shadow-2xs no-underline">
                <x-ui.icon name="arrow_back" class="w-4 h-4 text-slate-500" />
                <span>Back to Dashboard</span>
              </a>
              <button type="button" onclick="downloadMentoringPdf()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-xs transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs">
                <x-ui.icon name="print" class="w-4 h-4 text-slate-500" />
                <span>Download PDF</span>
              </button>
              <button type="button" onclick="saveStudentMentoringData()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs transition-all cursor-pointer flex items-center gap-1.5 shadow-xs">
                <x-ui.icon name="save" class="w-4 h-4" />
                <span>Save Changes</span>
              </button>
            </div>
          </div>
        </div>

        <!-- 2-Column Mentoring Workspace Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          
          <!-- Left Navigation Tab Strip (lg:col-span-1) -->
          <div class="lg:col-span-1 bg-white border border-slate-200/80 rounded-2xl p-3 shadow-xs space-y-1 self-start">
            <button type="button" onclick="switchStudentMentoringTab('smdProfile')" id="tabBtn_smdProfile" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs flex items-center gap-2.5 cursor-pointer">
              <x-ui.icon name="user" class="w-4 h-4" />
              <span>Personal Info</span>
            </button>
            <button type="button" onclick="switchStudentMentoringTab('smdFamily')" id="tabBtn_smdFamily" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center gap-2.5 cursor-pointer">
              <x-ui.icon name="users" class="w-4 h-4" />
              <span>Family Details</span>
            </button>
            <button type="button" onclick="switchStudentMentoringTab('smdEducation')" id="tabBtn_smdEducation" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center gap-2.5 cursor-pointer">
              <x-ui.icon name="book_open" class="w-4 h-4" />
              <span>Prior Education</span>
            </button>
            <button type="button" onclick="switchStudentMentoringTab('smdAcademic')" id="tabBtn_smdAcademic" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center gap-2.5 cursor-pointer">
              <x-ui.icon name="school" class="w-4 h-4" />
              <span>Academic Progress</span>
            </button>
            <button type="button" onclick="switchStudentMentoringTab('smdBoard')" id="tabBtn_smdBoard" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center gap-2.5 cursor-pointer">
              <x-ui.icon name="award" class="w-4 h-4" />
              <span>Board Exams</span>
            </button>
            <button type="button" onclick="switchStudentMentoringTab('smdExtra')" id="tabBtn_smdExtra" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center gap-2.5 cursor-pointer">
              <x-ui.icon name="sparkles" class="w-4 h-4" />
              <span>Extracurricular</span>
            </button>
            <button type="button" onclick="switchStudentMentoringTab('smdLeave')" id="tabBtn_smdLeave" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center gap-2.5 cursor-pointer">
              <x-ui.icon name="calendar" class="w-4 h-4" />
              <span>Leave Records</span>
            </button>
            <button type="button" onclick="switchStudentMentoringTab('smdDiscipline')" id="tabBtn_smdDiscipline" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center gap-2.5 cursor-pointer">
              <x-ui.icon name="gavel" class="w-4 h-4" />
              <span>Disciplinary Actions</span>
            </button>
            <button type="button" onclick="switchStudentMentoringTab('smdMeetings')" id="tabBtn_smdMeetings" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center gap-2.5 cursor-pointer">
              <x-ui.icon name="message" class="w-4 h-4" />
              <span>Mentor Meetings</span>
            </button>
          </div>

          <!-- Right Content Viewport (lg:col-span-3) -->
          <div class="lg:col-span-3 space-y-6">

            <!-- TAB 1: Personal Info -->
            <div id="tab_smdProfile" class="smd-content-pane bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-6">
              <div class="border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-900 text-sm">Personal &amp; Guardian Details</h4>
                <p class="text-xs text-slate-500 mt-0.5">Contact details, annual family income, and residential status.</p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-slate-600 font-semibold mb-1 text-xs">Annual Income</label>
                  <input type="text" id="smdAnnualIncome" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all placeholder:text-slate-400" placeholder="e.g. ₹2,00,000">
                </div>
                <div>
                  <label class="block text-slate-600 font-semibold mb-1 text-xs">Residential Status</label>
                  <select id="smdResidentialStatus" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all cursor-pointer">
                    <option value="Day Scholar">Day Scholar</option>
                    <option value="Hostel">Hostel</option>
                    <option value="Paying Guest">Paying Guest</option>
                  </select>
                </div>
                <div>
                  <label class="block text-slate-600 font-semibold mb-1 text-xs">Scholarships / Concessions</label>
                  <input type="text" id="smdScholarships" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all placeholder:text-slate-400" placeholder="e.g. E-Grantz">
                </div>
                <div class="flex items-center gap-2 pt-6">
                  <input type="checkbox" id="smdFeeWaiver" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                  <label for="smdFeeWaiver" class="text-xs font-semibold text-slate-700 cursor-pointer">Tuition Fee Waiver Student</label>
                </div>
              </div>

              <div class="border-t border-slate-100 pt-5 space-y-4">
                <h5 class="font-bold text-xs text-slate-900 uppercase tracking-wider">Guardian Information</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-slate-600 font-semibold mb-1 text-xs">Guardian Name</label>
                    <input type="text" id="smdGuardianName" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all placeholder:text-slate-400">
                  </div>
                  <div>
                    <label class="block text-slate-600 font-semibold mb-1 text-xs">Relationship</label>
                    <input type="text" id="smdGuardianRelation" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all placeholder:text-slate-400">
                  </div>
                  <div>
                    <label class="block text-slate-600 font-semibold mb-1 text-xs">Mobile Number</label>
                    <input type="text" id="smdGuardianMobile" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all placeholder:text-slate-400">
                  </div>
                  <div>
                    <label class="block text-slate-600 font-semibold mb-1 text-xs">Permanent Address</label>
                    <textarea id="smdPermanentAddress" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all placeholder:text-slate-400"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <!-- TAB 2: Family Details -->
            <div id="tab_smdFamily" class="smd-content-pane hidden bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div>
                  <h4 class="font-bold text-slate-900 text-sm">Family Members</h4>
                  <p class="text-xs text-slate-500 mt-0.5">Parents, siblings, and dependents.</p>
                </div>
                <button type="button" onclick="addFamilyRow()" class="px-3.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/80 rounded-xl text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs">
                  <x-ui.icon name="plus" class="w-4 h-4 text-blue-600" />
                  <span>Add Member</span>
                </button>
              </div>
              <div class="overflow-x-auto border border-slate-200/80 rounded-xl">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                      <th class="p-3 pl-4">Name</th>
                      <th class="p-3">Relationship</th>
                      <th class="p-3">Age</th>
                      <th class="p-3">Occupation</th>
                      <th class="p-3 pr-4 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="familyTableBody" class="divide-y divide-slate-100 text-slate-800">
                    <tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">No family members registered.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- TAB 3: Prior Education -->
            <div id="tab_smdEducation" class="smd-content-pane hidden bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div>
                  <h4 class="font-bold text-slate-900 text-sm">Prior Education History</h4>
                  <p class="text-xs text-slate-500 mt-0.5">SSLC, Plus Two, VHSE, or previous qualifications.</p>
                </div>
                <button type="button" onclick="addEducationRow()" class="px-3.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/80 rounded-xl text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs">
                  <x-ui.icon name="plus" class="w-4 h-4 text-blue-600" />
                  <span>Add Record</span>
                </button>
              </div>
              <div class="overflow-x-auto border border-slate-200/80 rounded-xl">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                      <th class="p-3 pl-4">Course</th>
                      <th class="p-3">Institution</th>
                      <th class="p-3">Year</th>
                      <th class="p-3">Percentage / CGPA</th>
                      <th class="p-3 pr-4 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="educationTableBody" class="divide-y divide-slate-100 text-slate-800">
                    <tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">No prior education records found.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- TAB 4: Academic Progress -->
            <div id="tab_smdAcademic" class="smd-content-pane hidden bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-900 text-sm">Academic Progression</h4>
                <p class="text-xs text-slate-500 mt-0.5">Internal exam marks, assignments, and credit progression across semesters.</p>
              </div>
              <div id="academicProgressContainer" class="space-y-4">
                <p class="p-6 text-center text-slate-500 text-xs font-medium">Loading progression logs...</p>
              </div>
            </div>

            <!-- TAB 5: Board Exams -->
            <div id="tab_smdBoard" class="smd-content-pane hidden bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-900 text-sm">SBTE Board Examination Results</h4>
                <p class="text-xs text-slate-500 mt-0.5">Official board marks, semester GPA, and backlog tracking.</p>
              </div>
              <div id="boardExamResultsContainer" class="space-y-4">
                <p class="p-6 text-center text-slate-500 text-xs font-medium">Loading board examination records...</p>
              </div>
            </div>

            <!-- TAB 6: Extracurricular -->
            <div id="tab_smdExtra" class="smd-content-pane hidden bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div>
                  <h4 class="font-bold text-slate-900 text-sm">Extracurricular Activity Claims</h4>
                  <p class="text-xs text-slate-500 mt-0.5">Points achieved towards diploma requirement (Goal: {{ $activityGoal }} pts).</p>
                </div>
                <button type="button" onclick="openStudentActivityModal()" class="px-3.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/80 rounded-xl text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs">
                  <x-ui.icon name="plus" class="w-4 h-4 text-blue-600" />
                  <span>Log Activity</span>
                </button>
              </div>
              <div class="overflow-x-auto border border-slate-200/80 rounded-xl">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                      <th class="p-3 pl-4">Activity</th>
                      <th class="p-3">Level</th>
                      <th class="p-3">Points</th>
                      <th class="p-3">Status</th>
                      <th class="p-3 pr-4 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="studentActivityTableBody" class="divide-y divide-slate-100 text-slate-800">
                    <tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">No extracurricular activities logged.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- TAB 7: Leave Records -->
            <div id="tab_smdLeave" class="smd-content-pane hidden bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-900 text-sm">Leave History</h4>
                <p class="text-xs text-slate-500 mt-0.5">Approved and taken leaves during the academic semester.</p>
              </div>
              <div class="overflow-x-auto border border-slate-200/80 rounded-xl">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                      <th class="p-3 pl-4">Dates</th>
                      <th class="p-3">Days</th>
                      <th class="p-3">Reason</th>
                      <th class="p-3 pr-4">Status</th>
                    </tr>
                  </thead>
                  <tbody id="studentLeaveTableBody" class="divide-y divide-slate-100 text-slate-800">
                    <tr><td colspan="4" class="p-6 text-center text-slate-500 font-medium">No leave records recorded.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- TAB 8: Disciplinary Actions -->
            <div id="tab_smdDiscipline" class="smd-content-pane hidden bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div>
                  <h4 class="font-bold text-slate-900 text-sm">Disciplinary Incidents</h4>
                  <p class="text-xs text-slate-500 mt-0.5">Recorded institutional warnings and actions.</p>
                </div>
                <button type="button" onclick="openDiscModal()" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 rounded-xl text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs">
                  <x-ui.icon name="warning" class="w-4 h-4 text-rose-600" />
                  <span>Record Incident</span>
                </button>
              </div>
              <div class="overflow-x-auto border border-slate-200/80 rounded-xl">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                      <th class="p-3 pl-4">Date</th>
                      <th class="p-3">Incident</th>
                      <th class="p-3">Action Taken</th>
                      <th class="p-3 pr-4 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="studentDiscTableBody" class="divide-y divide-slate-100 text-slate-800">
                    <tr><td colspan="4" class="p-6 text-center text-slate-500 font-medium">No disciplinary incidents recorded.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- TAB 9: Mentor Meetings -->
            <div id="tab_smdMeetings" class="smd-content-pane hidden bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div>
                  <h4 class="font-bold text-slate-900 text-sm">Mentoring Sessions &amp; Follow-ups</h4>
                  <p class="text-xs text-slate-500 mt-0.5">Notes from 1-on-1 counseling meetings.</p>
                </div>
                <button type="button" onclick="openSessionModal()" class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200/80 rounded-xl text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs">
                  <x-ui.icon name="plus" class="w-4 h-4 text-emerald-600" />
                  <span>Log Meeting</span>
                </button>
              </div>
              <div class="overflow-x-auto border border-slate-200/80 rounded-xl">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                      <th class="p-3 pl-4">Date</th>
                      <th class="p-3">Discussion / Issues</th>
                      <th class="p-3">Action Recommended</th>
                      <th class="p-3 pr-4 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody id="studentSessionTableBody" class="divide-y divide-slate-100 text-slate-800">
                    <tr><td colspan="4" class="p-6 text-center text-slate-500 font-medium">No mentoring sessions recorded.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>

      </main>
    </div>
  </div>

  <!-- Session Modal -->
  <div id="sessionModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="text-sm font-bold text-slate-900">Log Mentoring Meeting</h3>
        <button type="button" onclick="closeSessionModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">✕</button>
      </div>
      <div class="space-y-3">
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Session Date</label>
          <input type="date" id="sessDate" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Discussion Notes / Challenges</label>
          <textarea id="sessNotes" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600" placeholder="Student academic focus, attendance, personal concerns..."></textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Action Plan / Follow-up Advice</label>
          <textarea id="sessAction" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600" placeholder="Recommended steps, remedial sessions..."></textarea>
        </div>
      </div>
      <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" onclick="closeSessionModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Cancel</button>
        <button type="button" onclick="saveSessionLog()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold">Save Session</button>
      </div>
    </div>
  </div>

  <!-- Disciplinary Modal -->
  <div id="discModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white border border-slate-200 rounded-2xl p-6 w-full max-w-md shadow-xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="text-sm font-bold text-slate-900">Record Disciplinary Incident</h3>
        <button type="button" onclick="closeDiscModal()" class="text-slate-400 hover:text-slate-600 cursor-pointer">✕</button>
      </div>
      <div class="space-y-3">
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Incident Date</label>
          <input type="date" id="discDate" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600">
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Incident Description</label>
          <textarea id="discNotes" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600" placeholder="Description of infraction or misconduct..."></textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Action Taken / Resolution</label>
          <input type="text" id="discAction" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:bg-white focus:border-blue-600" placeholder="e.g. Warning issued, Parent notified">
        </div>
      </div>
      <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
        <button type="button" onclick="closeDiscModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Cancel</button>
        <button type="button" onclick="saveDisciplinaryAction()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold">Record Action</button>
      </div>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    let diaryData = null;

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function switchStudentMentoringTab(tabId) {
      document.querySelectorAll('.smd-content-pane').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.smd-tab').forEach(el => {
        el.className = 'w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all text-slate-600 hover:text-slate-900 hover:bg-slate-50 flex items-center gap-2.5 cursor-pointer';
      });

      const pane = document.getElementById('tab_' + tabId) || document.getElementById(tabId);
      if (pane) pane.classList.remove('hidden');

      const btn = document.getElementById('tabBtn_' + tabId);
      if (btn) {
        btn.className = 'w-full text-left px-4 py-2.5 rounded-xl font-semibold text-xs smd-tab transition-all bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs flex items-center gap-2.5 cursor-pointer';
      }
    }

    function showGlobalAlert(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      alert.classList.remove('hidden');
      alert.className = isError 
        ? "p-4 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200 block shadow-2xs"
        : "p-4 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200 block shadow-2xs";
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 4000);
    }

    function loadFullStudentDiary() {
      fetch(`/api/mentoring/full-diary/${window.TARGET_REG_NO}`)
        .then(res => res.json())
        .then(res => {
          if (res.status === 'SUCCESS' && res.data) {
            diaryData = res.data;
            hydrateDiaryFields(res.data);
          }
        })
        .catch(err => {
          console.error("Diary load error:", err);
        });
    }

    function hydrateDiaryFields(d) {
      const p = d.profile || d.extended_profile || {};
      
      // 1. Personal & Guardian
      document.getElementById('smdAnnualIncome').value = p.annual_income || '';
      document.getElementById('smdResidentialStatus').value = p.residential_status || 'Day Scholar';
      document.getElementById('smdScholarships').value = p.scholarships || '';
      document.getElementById('smdFeeWaiver').checked = p.is_fee_waiver == 1;
      document.getElementById('smdGuardianName').value = p.guardian_name || '';
      document.getElementById('smdGuardianRelation').value = p.guardian_relationship || '';
      document.getElementById('smdGuardianMobile').value = p.guardian_mobile || '';
      document.getElementById('smdPermanentAddress').value = p.guardian_address || '';

      // 2. Family Details
      const fBody = document.getElementById('familyTableBody');
      if (d.family && d.family.length > 0) {
        fBody.innerHTML = d.family.map((f, idx) => `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all family-row">
            <td class="p-3 pl-4 font-semibold text-slate-900 text-xs"><input type="text" value="${f.name || ''}" class="w-full bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 fam-name"></td>
            <td class="p-3 text-slate-700 text-xs"><input type="text" value="${f.relationship || ''}" class="w-full bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 fam-rel"></td>
            <td class="p-3 text-slate-700 text-xs"><input type="number" value="${f.age || ''}" class="w-16 bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 fam-age"></td>
            <td class="p-3 text-slate-700 text-xs"><input type="text" value="${f.occupation || ''}" class="w-full bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 fam-occ"></td>
            <td class="p-3 pr-4 text-right"><button type="button" onclick="this.closest('tr').remove()" class="text-rose-600 hover:text-rose-700 text-xs font-semibold cursor-pointer">Remove</button></td>
          </tr>
        `).join('');
      } else {
        fBody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">No family members registered.</td></tr>';
      }

      // 3. Education
      const eBody = document.getElementById('educationTableBody');
      if (d.education && d.education.length > 0) {
        eBody.innerHTML = d.education.map((e, idx) => `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all edu-row">
            <td class="p-3 pl-4 font-semibold text-slate-900 text-xs"><input type="text" value="${e.course || ''}" class="w-full bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 edu-course"></td>
            <td class="p-3 text-slate-700 text-xs"><input type="text" value="${e.institution || ''}" class="w-full bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 edu-inst"></td>
            <td class="p-3 text-slate-700 text-xs"><input type="number" value="${e.year_of_passing || ''}" class="w-20 bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 edu-year"></td>
            <td class="p-3 text-slate-700 text-xs"><input type="text" value="${e.percentage || ''}" class="w-24 bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 edu-pct"></td>
            <td class="p-3 pr-4 text-right"><button type="button" onclick="this.closest('tr').remove()" class="text-rose-600 hover:text-rose-700 text-xs font-semibold cursor-pointer">Remove</button></td>
          </tr>
        `).join('');
      } else {
        eBody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">No prior education records found.</td></tr>';
      }

      // 4. Academic Progress
      const acadCont = document.getElementById('academicProgressContainer');
      if (d.academics && Object.keys(d.academics).length > 0) {
        let aHtml = '';
        for (const [sem, subjects] of Object.entries(d.academics)) {
          aHtml += `
            <div class="border border-slate-200/80 rounded-xl overflow-hidden mb-4 shadow-2xs">
              <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-200 flex justify-between items-center">
                <span class="font-bold text-slate-900 text-xs">Semester ${sem} Progress</span>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">${subjects.length} Subjects</span>
              </div>
              <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-white border-b border-slate-100 text-slate-600 font-semibold uppercase tracking-wider">
                      <th class="p-3 pl-4">Subject</th>
                      <th class="p-3 text-center">Type</th>
                      <th class="p-3 text-center">Tests (CO1-CO4)</th>
                      <th class="p-3 text-center">Assignments</th>
                      <th class="p-3 pr-4 text-center">Internal Mark</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 text-slate-800">
                    ${subjects.map(s => `
                      <tr class="hover:bg-slate-50/50">
                        <td class="p-3 pl-4"><span class="font-semibold text-slate-900 block">${s.subject_name}</span><span class="font-mono text-slate-500 text-xs">${s.subject_code}</span></td>
                        <td class="p-3 text-center"><span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">${s.type}</span></td>
                        <td class="p-3 text-center font-mono text-xs">${Object.values(s.tests || {}).join(' | ')}</td>
                        <td class="p-3 text-center font-mono text-xs">${Object.values(s.assignments || {}).join(' | ')}</td>
                        <td class="p-3 pr-4 text-center font-bold text-blue-600 text-xs">${s.internal_mark || '--'}</td>
                      </tr>
                    `).join('')}
                  </tbody>
                </table>
              </div>
            </div>
          `;
        }
        acadCont.innerHTML = aHtml;
      } else {
        acadCont.innerHTML = '<p class="p-6 text-center text-slate-500 text-xs font-medium">No progression logs recorded yet.</p>';
      }

      // 5. Board Exams
      const boardCont = document.getElementById('boardExamResultsContainer');
      if (d.board && d.board.length > 0) {
        boardCont.innerHTML = `
          <div class="overflow-x-auto border border-slate-200/80 rounded-xl">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider">
                  <th class="p-3 pl-4">Semester</th>
                  <th class="p-3">GPA / Result</th>
                  <th class="p-3">Backlogs</th>
                  <th class="p-3 pr-4">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-slate-800">
                ${d.board.map(b => `
                  <tr>
                    <td class="p-3 pl-4 font-bold text-slate-900">Semester ${b.semester}</td>
                    <td class="p-3 font-semibold text-blue-600">${b.gpa || b.result || '--'}</td>
                    <td class="p-3">${b.backlogs || 0}</td>
                    <td class="p-3 pr-4"><span class="px-2 py-0.5 rounded-full text-xs font-semibold ${b.status === 'Passed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">${b.status || 'Appeared'}</span></td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;
      } else {
        boardCont.innerHTML = '<p class="p-6 text-center text-slate-500 text-xs font-medium">No board examination records found.</p>';
      }

      // 6. Extracurricular
      const actBody = document.getElementById('studentActivityTableBody');
      if (d.extracurricular && d.extracurricular.length > 0) {
        actBody.innerHTML = d.extracurricular.map(a => `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all">
            <td class="p-3 pl-4 font-semibold text-slate-900 text-xs">${a.activity_name || a.category}</td>
            <td class="p-3 text-slate-700 text-xs">${a.level || 'Institutional'}</td>
            <td class="p-3 font-bold text-blue-600 text-xs">${a.points_claimed || a.points || 0} pts</td>
            <td class="p-3"><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${a.status === 'Approved' || a.status === 'Verified' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">${a.status || 'Pending'}</span></td>
            <td class="p-3 pr-4 text-right text-xs font-medium text-slate-500">${a.created_at ? new Date(a.created_at).toLocaleDateString() : '-'}</td>
          </tr>
        `).join('');
      } else {
        actBody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">No extracurricular activities logged.</td></tr>';
      }

      // 7. Leaves
      const lBody = document.getElementById('studentLeaveTableBody');
      if (d.leaves && d.leaves.length > 0) {
        lBody.innerHTML = d.leaves.map(l => `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all">
            <td class="p-3 pl-4 font-semibold text-slate-900 text-xs">${l.leave_date || (l.from_date + ' to ' + l.to_date)}</td>
            <td class="p-3 font-semibold text-slate-700 text-xs">${l.no_of_days || 1} day(s)</td>
            <td class="p-3 text-slate-700 text-xs">${l.reason || 'Medical / Personal'}</td>
            <td class="p-3 pr-4"><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${l.status === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">${l.status || 'Approved'}</span></td>
          </tr>
        `).join('');
      } else {
        lBody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-slate-500 font-medium">No leave records recorded.</td></tr>';
      }

      // 8. Disciplinary
      const discBody = document.getElementById('studentDiscTableBody');
      if (d.disciplinary && d.disciplinary.length > 0) {
        discBody.innerHTML = d.disciplinary.map(dc => `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all">
            <td class="p-3 pl-4 font-mono text-slate-500 text-xs">${dc.date}</td>
            <td class="p-3 font-semibold text-slate-900 text-xs">${dc.incident_description || dc.notes}</td>
            <td class="p-3 text-slate-700 text-xs">${dc.action_taken || 'Warning'}</td>
            <td class="p-3 pr-4 text-right"><button type="button" onclick="deleteDisciplinaryAction('${dc.id}')" class="text-rose-600 hover:text-rose-700 text-xs font-semibold cursor-pointer">Delete</button></td>
          </tr>
        `).join('');
      } else {
        discBody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-slate-500 font-medium">No disciplinary incidents recorded.</td></tr>';
      }

      // 9. Meetings
      const sessBody = document.getElementById('studentSessionTableBody');
      if (d.meetings && d.meetings.length > 0) {
        sessBody.innerHTML = d.meetings.map(m => `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all">
            <td class="p-3 pl-4 font-mono text-slate-500 text-xs">${m.date}</td>
            <td class="p-3 text-slate-900 text-xs font-medium leading-relaxed">${m.discussion_notes}</td>
            <td class="p-3 text-slate-700 text-xs leading-relaxed">${m.action_taken || '-'}</td>
            <td class="p-3 pr-4 text-right"><button type="button" onclick="deleteSessionLog('${m.diary_id}')" class="text-rose-600 hover:text-rose-700 text-xs font-semibold cursor-pointer">Delete</button></td>
          </tr>
        `).join('');
      } else {
        sessBody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-slate-500 font-medium">No mentoring sessions recorded.</td></tr>';
      }
    }

    function addFamilyRow() {
      const tbody = document.getElementById('familyTableBody');
      if (tbody.innerText.includes('No family members')) tbody.innerHTML = '';
      const tr = document.createElement('tr');
      tr.className = "border-b border-slate-100 hover:bg-slate-50/80 transition-all family-row";
      tr.innerHTML = `
        <td class="p-3 pl-4 font-semibold text-slate-900 text-xs"><input type="text" placeholder="Full name" class="w-full bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 fam-name"></td>
        <td class="p-3 text-slate-700 text-xs"><input type="text" placeholder="Relationship" class="w-full bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 fam-rel"></td>
        <td class="p-3 text-slate-700 text-xs"><input type="number" placeholder="Age" class="w-16 bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 fam-age"></td>
        <td class="p-3 text-slate-700 text-xs"><input type="text" placeholder="Occupation" class="w-full bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 fam-occ"></td>
        <td class="p-3 pr-4 text-right"><button type="button" onclick="this.closest('tr').remove()" class="text-rose-600 hover:text-rose-700 text-xs font-semibold cursor-pointer">Remove</button></td>
      `;
      tbody.appendChild(tr);
    }

    function addEducationRow() {
      const tbody = document.getElementById('educationTableBody');
      if (tbody.innerText.includes('No prior education')) tbody.innerHTML = '';
      const tr = document.createElement('tr');
      tr.className = "border-b border-slate-100 hover:bg-slate-50/80 transition-all edu-row";
      tr.innerHTML = `
        <td class="p-3 pl-4 font-semibold text-slate-900 text-xs"><input type="text" placeholder="e.g. SSLC / Plus Two" class="w-full bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 edu-course"></td>
        <td class="p-3 text-slate-700 text-xs"><input type="text" placeholder="School / Board" class="w-full bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 edu-inst"></td>
        <td class="p-3 text-slate-700 text-xs"><input type="number" placeholder="Passing Year" class="w-20 bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 edu-year"></td>
        <td class="p-3 text-slate-700 text-xs"><input type="text" placeholder="Percentage / CGPA" class="w-24 bg-transparent outline-none border-b border-dashed border-slate-300 focus:border-blue-600 edu-pct"></td>
        <td class="p-3 pr-4 text-right"><button type="button" onclick="this.closest('tr').remove()" class="text-rose-600 hover:text-rose-700 text-xs font-semibold cursor-pointer">Remove</button></td>
      `;
      tbody.appendChild(tr);
    }

    function saveStudentMentoringData() {
      const profile = {
        annual_income: document.getElementById('smdAnnualIncome').value,
        residential_status: document.getElementById('smdResidentialStatus').value,
        scholarships: document.getElementById('smdScholarships').value,
        is_fee_waiver: document.getElementById('smdFeeWaiver').checked ? 1 : 0,
        guardian_name: document.getElementById('smdGuardianName').value,
        guardian_relationship: document.getElementById('smdGuardianRelation').value,
        guardian_mobile: document.getElementById('smdGuardianMobile').value,
        guardian_address: document.getElementById('smdPermanentAddress').value
      };

      const family = [];
      document.querySelectorAll('.family-row').forEach(row => {
        const name = row.querySelector('.fam-name').value.trim();
        if (name) {
          family.push({
            name: name,
            relationship: row.querySelector('.fam-rel').value.trim(),
            age: row.querySelector('.fam-age').value.trim(),
            occupation: row.querySelector('.fam-occ').value.trim()
          });
        }
      });

      const education = [];
      document.querySelectorAll('.edu-row').forEach(row => {
        const course = row.querySelector('.edu-course').value.trim();
        if (course) {
          education.push({
            course: course,
            institution: row.querySelector('.edu-inst').value.trim(),
            year_of_passing: row.querySelector('.edu-year').value.trim(),
            percentage: row.querySelector('.edu-pct').value.trim()
          });
        }
      });

      fetch('/api/student/mentoring/save-all', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          reg_no: window.TARGET_REG_NO,
          profile: profile,
          family: family,
          education: education
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalAlert("Student mentoring diary saved successfully.");
          loadFullStudentDiary();
        } else {
          showGlobalAlert(data.message || "Failed to save mentoring data.", true);
        }
      })
      .catch(() => showGlobalAlert("Network error saving diary.", true));
    }

    function openSessionModal() {
      const modal = document.getElementById('sessionModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeSessionModal() {
      const modal = document.getElementById('sessionModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      document.getElementById('sessNotes').value = '';
      document.getElementById('sessAction').value = '';
    }

    function saveSessionLog() {
      const date = document.getElementById('sessDate').value;
      const notes = document.getElementById('sessNotes').value.trim();
      const action = document.getElementById('sessAction').value.trim();

      if (!notes) {
        alert("Please enter discussion notes.");
        return;
      }

      fetch('/api/mentoring/diary/add', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          reg_no: window.TARGET_REG_NO,
          date: date,
          discussion_notes: notes,
          action_taken: action,
          category: 'Academic'
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          closeSessionModal();
          showGlobalAlert("Mentoring meeting logged successfully.");
          loadFullStudentDiary();
        } else {
          alert(data.message || "Failed to log meeting.");
        }
      })
      .catch(() => alert("Network error logging meeting."));
    }

    function deleteSessionLog(diaryId) {
      if (!confirm("Are you sure you want to delete this meeting log?")) return;
      fetch('/api/mentoring/diary/delete', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ diary_id: diaryId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalAlert("Meeting log deleted.");
          loadFullStudentDiary();
        }
      });
    }

    function openDiscModal() {
      const modal = document.getElementById('discModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeDiscModal() {
      const modal = document.getElementById('discModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      document.getElementById('discNotes').value = '';
      document.getElementById('discAction').value = '';
    }

    function saveDisciplinaryAction() {
      const date = document.getElementById('discDate').value;
      const notes = document.getElementById('discNotes').value.trim();
      const action = document.getElementById('discAction').value.trim();

      if (!notes) {
        alert("Please enter incident notes.");
        return;
      }

      fetch('/api/mentoring/disciplinary/save', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          reg_no: window.TARGET_REG_NO,
          date: date,
          incident_description: notes,
          action_taken: action
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          closeDiscModal();
          showGlobalAlert("Disciplinary incident recorded.");
          loadFullStudentDiary();
        }
      });
    }

    function deleteDisciplinaryAction(discId) {
      if (!confirm("Delete this disciplinary record?")) return;
      fetch('/api/mentoring/disciplinary/delete', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ id: discId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalAlert("Disciplinary record deleted.");
          loadFullStudentDiary();
        }
      });
    }

    function downloadMentoringPdf() {
      window.open(`/diary/${window.TARGET_REG_NO}/print`, '_blank');
    }

    document.addEventListener('DOMContentLoaded', () => {
      loadFullStudentDiary();
    });
  </script>

  @include('partials.support_desk_overlay')
</body>
</html>