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
        <div id="globalAlert" class="hidden p-4 rounded-xl font-semibold border text-sm transition-all"></div>

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
                    <tr><td colspan="5" class="p-4 text-center text-slate-500">Loading family records...</td></tr>
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
                    <tr><td colspan="5" class="p-4 text-center text-slate-500">Loading education records...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- TAB 4: Academic Progress -->
            <div id="tab_smdAcademic" class="smd-content-pane hidden bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
              <div class="border-b border-slate-100 pb-3">
                <h4 class="font-bold text-slate-900 text-sm">Academic Progression</h4>
                <p class="text-xs text-slate-500 mt-0.5">Internal exam marks, attendance percentages, and credit progression across semesters.</p>
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
                    <tr><td colspan="5" class="p-4 text-center text-slate-500">Loading activity records...</td></tr>
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
                    <tr><td colspan="4" class="p-4 text-center text-slate-500">Loading leave records...</td></tr>
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
                    <tr><td colspan="4" class="p-4 text-center text-slate-500">No disciplinary incidents recorded.</td></tr>
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
                    <tr><td colspan="4" class="p-4 text-center text-slate-500">Loading meeting records...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>

      </main>
    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
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

    function switchPanel(panelId, title) {
      const panels = ['exams', 'marks', 'profile', 'mentoring', 'activity'];
      
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        if (id === panelId) {
          if (el) { el.classList.remove('hidden'); el.classList.add('fade-up'); }
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (el) el.classList.add('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-600 hover:bg-slate-100 hover:text-white cursor-pointer";
        }
      });

      const titles = { exams: 'Works To Do', marks: 'Academic Stats', profile: 'My Profile', mentoring: 'Mentoring Diary', activity: 'Activity Points' };
      const subtitles = { 
        exams: 'Manage your pending assignments and active tests.', 
        marks: 'Your semester-wise academic progress.', 
        profile: 'Your personal and academic details.',
        mentoring: 'Mentoring sessions and student data.',
        activity: 'Track and claim your extracurricular points.'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];
      document.getElementById('panelSubtitle').innerText = subtitles[panelId];

      if (panelId === 'mentoring') {
        if (!mentoringLoaded) loadMentoringDiary();
      } else if (panelId === 'activity') {
        loadActivityPoints();
      }
    }

      document.addEventListener('DOMContentLoaded', () => {
        loadStudentTests();
        if (!academicReportLoaded) loadAcademicReport();
      });

    let academicReportLoaded = false;
    let mentoringLoaded = false;
    let academicData = null;
    let currentActiveSem = 1;
    let cgpaChartInstance = null;
    
    let currentTaskStats = {
       assignments_active: 0,
       assignments_submitted: 0,
       written_tests_active: 0,
       written_tests_submitted: 0,
       online_tests_active: 0,
       online_tests_submitted: 0
    };

    function updateStatsHeader(acStats, tStats) {
       if (acStats) {
          currentTaskStats.assignments_active = acStats.assignments_active || 0;
          currentTaskStats.assignments_submitted = acStats.assignments_submitted || 0;
          currentTaskStats.written_tests_active = acStats.written_tests_active || 0;
          currentTaskStats.written_tests_submitted = acStats.written_tests_submitted || 0;
       }
       if (tStats) {
          currentTaskStats.online_tests_active = tStats.online_tests_active || 0;
          currentTaskStats.online_tests_submitted = tStats.online_tests_submitted || 0;
       }
       document.getElementById('statActiveTests').innerText = currentTaskStats.online_tests_active;
       document.getElementById('statActiveAssign').innerText = currentTaskStats.assignments_active;
       document.getElementById('statWrittenTests').innerText = currentTaskStats.written_tests_active;
       document.getElementById('statTestsDone').innerText = currentTaskStats.online_tests_submitted;
       document.getElementById('statAssignDone').innerText = currentTaskStats.assignments_submitted;
       document.getElementById('statWrittenTestsDone').innerText = currentTaskStats.written_tests_submitted;
       document.getElementById('statPendingTotal').innerText = currentTaskStats.online_tests_active + currentTaskStats.assignments_active + currentTaskStats.written_tests_active;
    }

    function loadAcademicReport() {
      fetch('/api/student/academic-report')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            academicReportLoaded = true;
            academicData = data;
            const overall = data.overall || {};
            document.getElementById('overallCgpa').innerText = overall.cgpa || '0';
            document.getElementById('overallActivityPoints').innerText = overall.activity_points || '0';
            if (overall.current_semester) {
              document.getElementById('headerSemesterText').classList.remove('hidden');
              document.getElementById('headerSemValue').innerText = overall.current_semester;
            }
            currentActiveSem = data.overall.current_semester || 1;

            if (data.stats) updateStatsHeader(data.stats, null);
            renderActiveTasks(data.active_tasks || []);
            renderCgpaChart(data.semesters);
            renderSemesterTabs(data.semesters);
            renderGodTable(currentActiveSem);
          }
        });
    }

    function renderActiveTasks(tasks) {
      const container = document.getElementById('studentActiveTasksContainer');
      if (!tasks || tasks.length === 0) {
        container.innerHTML = `<div class="col-span-full py-12 text-center text-slate-500 font-bold text-xs">No active assignments or tests at the moment.</div>`;
        return;
      }
      
      let html = '';
      tasks.forEach((t, index) => {
        const isExp = t.status === 'Expired' || t.status === 'Completed';
        const stCol = isExp ? 'text-rose-400 bg-rose-500/10 border-rose-500/20' : 'text-teal-400 bg-teal-500/10 border-teal-500/20';
        const icon = t.type === 'Assignment' ? 'assignment' : 'edit_document';

        let qHtml = '';
        if (t.questions && t.questions.length > 0) {
          qHtml = `<div class="mt-4 pt-4 border-t border-slate-200 hidden" id="taskQ_${index}">
            <h4 class="text-xs uppercase font-black text-slate-600 mb-2">Assignment Questions</h4>
            <ul class="space-y-2 text-xs text-slate-700 font-medium list-disc pl-4">
              ${t.questions.map(q => `<li>${q}</li>`).join('')}
            </ul>
          </div>`;
        }

        let actionBtn = '';
        if (t.type === 'Assignment' && !isExp) {
          actionBtn = `<button onclick="markManualTaskSubmitted('${t.subject_code}', '${t.co_tag}', 'Assignment')" class="mt-3 w-full py-2 bg-blue-600/80 hover:bg-blue-500 text-white rounded font-bold text-xs transition-premium">Mark as Submitted</button>`;
        }

        html += `
          <div class="bg-white/80 border border-slate-200/60 rounded-xl overflow-hidden mb-1">
            <!-- Collapsible Header -->
            <div onclick="document.getElementById('co_task_${index}').classList.toggle('hidden'); this.querySelector('.arrow-icon').innerText = document.getElementById('co_task_${index}').classList.contains('hidden') ? 'expand_more' : 'expand_less';" 
                 class="px-4 py-3.5 bg-slate-50/40 hover:bg-slate-50/70 border-b border-slate-200 flex justify-between items-center cursor-pointer transition-premium">
              <div class="flex items-center gap-3">
                <span class="text-blue-600 text-xs font-bold">●</span>
                <div>
                  <h4 class="font-bold text-xs text-slate-800 uppercase">${t.type} - ${t.co_tag}</h4>
                  <p class="text-xs font-black text-purple-400 uppercase tracking-wider mt-0.5">${t.subject_code} - ${t.subject}</p>
                </div>
              </div>
              <span class="text-slate-400 text-xs arrow-icon">▼</span>
            </div>
            <!-- Collapsible Content -->
            <div id="co_task_${index}" class="hidden p-4 bg-slate-50/10 border-t border-slate-100">
              <div class="flex items-center gap-2 mb-3">
                  <span class="px-2 py-0.5 rounded text-xs font-black uppercase tracking-widest ${stCol}">${t.status}</span>
              </div>
              <div class="grid grid-cols-2 gap-4 mb-4 text-xs text-slate-600 font-semibold">
                <div class="space-y-1">
                  <div>Start Date: <span class="text-slate-800 font-bold">${t.start ? new Date(t.start).toLocaleDateString() : '-'}</span></div>
                </div>
                <div class="space-y-1">
                  <div>Deadline: <span class="text-slate-800 font-bold font-mono">${t.deadline ? new Date(t.deadline).toLocaleDateString() : '-'}</span></div>
                </div>
              </div>
              ${qHtml ? `<button onclick="document.getElementById('taskQ_${index}').classList.toggle('hidden')" class="w-full mt-2 py-2 text-xs font-bold text-blue-400 hover:text-blue-300 bg-blue-500/5 rounded-xl transition-premium flex justify-center items-center gap-1">👁 View Questions</button>` : ''}
              ${qHtml}
              ${actionBtn}
            </div>
          </div>
        `;
      });
      container.innerHTML = html;
      container.className = "flex flex-col gap-1 mt-4 mb-6";
    }

    function markManualTaskSubmitted(subjectCode, coTag, category) {
      if (!confirm("Are you sure you want to mark this task as submitted?")) return;
      fetch('/api/student/tasks/submit', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ subject_code: subjectCode, co_tag: coTag, category: category, status: 'Submitted' })
      })
      .then(res => res.json())
      .then(data => {
          if (data.status === 'SUCCESS') {
              alert(data.message);
              loadAcademicReport(); // reload tasks
          } else {
              alert(data.message || "Failed to submit.");
          }
      });
    }

    function renderCgpaChart(semesters) {
      const ctx = document.getElementById('cgpaChart').getContext('2d');
      if (cgpaChartInstance) cgpaChartInstance.destroy();

      const labels = semesters.map(s => `S${s.semester}`);
      const data = semesters.map(s => s.sgpa || 0);

      cgpaChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'SGPA',
            data: data,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#fff',
            pointRadius: 4,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 } } },
            y: { 
              grid: { color: 'rgba(30,41,59,0.5)' }, 
              ticks: { color: '#64748b', font: { size: 10 } },
              min: 0, max: 10
            }
          }
        }
      });
    }

    function renderSemesterTabs(semesters) {
      const container = document.getElementById('semesterTabsContainer');
      let html = '';
      semesters.forEach(s => {
        const isActive = s.semester === currentActiveSem;
        const isCurrent = s.is_current === true;
        const cls = isActive 
          ? 'bg-blue-600/20 text-blue-400 border-blue-500/20' 
          : 'bg-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100 border-transparent';
        const badge = isCurrent ? `<span class="ml-1 text-[8px] bg-teal-500/20 text-teal-400 px-1 py-0.5 rounded font-black">NOW</span>` : '';
        html += `
          <button onclick="renderGodTable(${s.semester})" id="btnSemTab_${s.semester}" class="sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border ${cls}">
            Semester ${s.semester}${badge}
          </button>
        `;
      });
      container.innerHTML = html;
    }

    function renderGodTable(semId) {
      currentActiveSem = semId;
      document.querySelectorAll('.sem-tab').forEach(btn => {
        btn.className = 'sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border bg-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100 border-transparent';
      });
      const actBtn = document.getElementById(`btnSemTab_${semId}`);
      if(actBtn) actBtn.className = 'sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border bg-blue-600/20 text-blue-400 border-blue-500/20';

      const container = document.getElementById('academicReportContent');
      const semData = academicData.semesters.find(s => s.semester == semId);
      if (!semData || !semData.subjects || semData.subjects.length === 0) {
        container.innerHTML = `<div class="py-12 text-center text-slate-500 font-bold text-xs border border-slate-200/50 rounded-2xl bg-white/30">No academic data available for Semester ${semId}.</div>`;
        return;
      }

      let rows = '';
      semData.subjects.forEach(sub => {
        const trClass = "border-b border-slate-200/50 hover:bg-white/30 transition-premium";
        rows += `
          <tr class="${trClass}">
            <td class="p-4 whitespace-nowrap">
              <div class="font-black text-slate-800 text-xs">${sub.subject_code}</div>
              <div class="text-xs text-slate-500 font-bold truncate max-w-[150px]" title="${sub.subject_name}">${sub.subject_name}</div>
            </td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-700">${sub.CO1 !== null ? sub.CO1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-700 bg-slate-50/20">${sub.CO2 !== null ? sub.CO2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-700">${sub.CO3 !== null ? sub.CO3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-700 bg-slate-50/20">${sub.CO4 !== null ? sub.CO4 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400 border-l border-slate-200">${sub.Assg1 !== null ? sub.Assg1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400">${sub.Assg2 !== null ? sub.Assg2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400">${sub.Assg3 !== null ? sub.Assg3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400">${sub.Assg4 !== null ? sub.Assg4 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400 border-l border-slate-200">${sub.WT1 !== null ? sub.WT1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400">${sub.WT2 !== null ? sub.WT2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400">${sub.WT3 !== null ? sub.WT3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400">${sub.WT4 !== null ? sub.WT4 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400 border-l border-slate-200">${sub.OT1 !== null ? sub.OT1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400">${sub.OT2 !== null ? sub.OT2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400">${sub.OT3 !== null ? sub.OT3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400">${sub.OT4 !== null ? sub.OT4 : '-'}</td>
            <td class="p-4 text-center text-xs font-black border-l border-slate-200 ${sub.attendance_percentage < 75 ? 'text-rose-400' : 'text-slate-700'}">
              ${sub.attendance_percentage}%
            </td>
          </tr>
        `;
      });

      container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
          <div class="flex gap-4">
            <div class="bg-white border border-slate-200 rounded-xl px-4 py-2 flex items-center gap-2 shadow-inner">
              <span class="text-amber-500 text-xs">★</span>
              <span class="text-xs text-slate-600 font-bold uppercase tracking-widest">SGPA:</span>
              <span class="text-xs font-black text-white">${semData.sgpa || '-'}</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl px-4 py-2 flex items-center gap-2 shadow-inner">
              <span class="text-blue-500 text-xs">🎫</span>
              <span class="text-xs text-slate-600 font-bold uppercase tracking-widest">Points:</span>
              <span class="text-xs font-black text-white">${semData.activity_points || '-'}</span>
            </div>
          </div>
        </div>

        <div class="bg-slate-50/40 border border-slate-200 rounded-2xl overflow-x-auto shadow-2xl">
          <table class="w-full text-left border-collapse min-w-[1200px]">
            <thead>
              <tr class="bg-white/80 border-b border-slate-200 text-xs uppercase tracking-wider font-black text-slate-600">
                <th class="p-4 font-black">Subject</th>
                <th class="p-4 text-center" colspan="4">Sum COs</th>
                <th class="p-4 text-center border-l border-slate-200 text-blue-400" colspan="4">Assignments</th>
                <th class="p-4 text-center border-l border-slate-200 text-emerald-400" colspan="4">Written Tests</th>
                <th class="p-4 text-center border-l border-slate-200 text-purple-400" colspan="4">Online Tests</th>
                <th class="p-4 text-center border-l border-slate-200">Attend.</th>
              </tr>
              <tr class="bg-white/40 border-b border-slate-200/50 text-xs uppercase font-bold text-slate-500">
                <th class="p-2"></th>
                <th class="p-2 text-center w-10 border-l border-slate-200/50">C1</th><th class="p-2 text-center w-10 bg-slate-50/20">C2</th><th class="p-2 text-center w-10">C3</th><th class="p-2 text-center w-10 bg-slate-50/20">C4</th>
                <th class="p-2 text-center w-10 border-l border-slate-200">A1</th><th class="p-2 text-center w-10">A2</th><th class="p-2 text-center w-10">A3</th><th class="p-2 text-center w-10">A4</th>
                <th class="p-2 text-center w-10 border-l border-slate-200">W1</th><th class="p-2 text-center w-10">W2</th><th class="p-2 text-center w-10">W3</th><th class="p-2 text-center w-10">W4</th>
                <th class="p-2 text-center w-10 border-l border-slate-200">O1</th><th class="p-2 text-center w-10">O2</th><th class="p-2 text-center w-10">O3</th><th class="p-2 text-center w-10">O4</th>
                <th class="p-2 text-center w-16 border-l border-slate-200">%</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/30">
              ${rows}
            </tbody>
          </table>
        </div>
      `;
    }

    function updateSbteRegNo() {
      const val = document.getElementById('sbteRegNoInput').value.trim();
      const alertEl = document.getElementById('sbteAlert');
      if (!val) {
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block';
        alertEl.innerText = 'Please enter your SBTE Register Number.';
        return;
      }
      fetch('/api/student/update-sbte-reg', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ sbteRegNo: val })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block';
          alertEl.innerText = 'SBTE Register Number saved! Reload the page to see it confirmed.';
        } else {
          alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block';
          alertEl.innerText = data.message || 'Failed to save. Please try again.';
        }
      })
      .catch(() => {
        alertEl.className = 'p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block';
        alertEl.innerText = 'Network error. Please try again.';
      });
    }

    function changePassword() {
      const oldPwd = document.getElementById('oldPwd').value.trim();
      const newPwd = document.getElementById('newPwd').value.trim();
      const alert = document.getElementById('pwdAlert');
      if (!oldPwd || !newPwd) {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Please fill in both fields.";
        return;
      }
      if (newPwd.length < 6) {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "New password must be at least 6 characters.";
        return;
      }
      fetch('/api/student/change-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ oldPassword: oldPwd, newPassword: newPwd })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "Password updated successfully.";
          document.getElementById('oldPwd').value = '';
          document.getElementById('newPwd').value = '';
        } else {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message || 'Password change failed.';
        }
      })
      .catch(() => {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = 'Request failed. Please try again.';
      });
    }

    // Init stub stats and load tests
    document.addEventListener('DOMContentLoaded', () => {
      loadStudentTests();
      loadAcademicReport();
    });

    // TEST ENGINE LOGIC
    let currentTestId = null;
    let timerInterval = null;
    let endTimeMs = null;

    function loadStudentTests() {
      fetch('/api/student/online-tests')
        .then(res => res.json())
        .then(data => {
          let container = document.getElementById('studentActiveTestsList');
          if (!container) return;
          if (data.status === 'SUCCESS' && data.tests && data.tests.length > 0) {
            let html = '';
            data.tests.forEach(t => {
              let actionHtml = '';
              if (t.can_take) {
                actionHtml = `<button onclick="startOnlineTest('${t.test_id}')" class="w-full py-2 bg-purple-600/80 hover:bg-purple-500 text-white rounded font-bold text-xs transition-premium">Start Test</button>`;
              } else if (t.status_message && t.status_message.startsWith('Starts')) {
                actionHtml = `<button disabled class="w-full py-2 bg-slate-100 text-slate-700/40 text-slate-600 rounded font-bold text-xs text-center border border-slate-200/50 mb-2 cursor-not-allowed flex items-center justify-center gap-2">🔒 ${t.status_message}</button>`;
              } else if (t.my_attempts > 0) {
                actionHtml = `<div class="w-full py-2 bg-emerald-900/40 text-emerald-400 rounded font-bold text-xs text-center border border-emerald-800/50 mb-2">Best Score: ${t.best_score || 0}</div>`;
              } else {
                actionHtml = `<div class="w-full py-2 bg-slate-100 text-slate-700/40 text-slate-600 rounded font-bold text-xs text-center border border-slate-200/50 mb-2">${t.status_message || 'Expired'}</div>`;
              }

              let hasEnded = false;
              if (t.end_time) {
                let et = new Date(t.end_time.replace(' ', 'T'));
                hasEnded = (new Date() >= et);
              }
              if (hasEnded && t.my_attempts > 0) {
                actionHtml += `<button onclick="viewAnswerKey('${t.test_id}')" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white rounded font-bold text-xs transition-premium">View Answer Key</button>`;
              } else if (t.my_attempts > 0 && !t.can_take) {
                let formattedEndTime = new Date(t.end_time).toLocaleString();
                actionHtml += `<div class="text-xs text-center text-slate-600 font-semibold mt-1 bg-slate-50/30 p-1.5 rounded border border-slate-200/50">Answer key unlocks after test ends: <br/>${formattedEndTime}</div>`;
              }

              html += `
                <div class="bg-white/80 border border-slate-200/60 rounded-xl overflow-hidden mb-1">
                  <!-- Collapsible Header -->
                  <div onclick="document.getElementById('co_exam_${t.test_id}').classList.toggle('hidden'); this.querySelector('.arrow-icon').innerText = document.getElementById('co_exam_${t.test_id}').classList.contains('hidden') ? 'expand_more' : 'expand_less';" 
                       class="px-4 py-3.5 bg-slate-50/40 hover:bg-slate-50/70 border-b border-slate-200 flex justify-between items-center cursor-pointer transition-premium">
                    <div class="flex items-center gap-3">
                      <span class="text-purple-600 text-xs">📝</span>
                      <div>
                        <h4 class="font-bold text-xs text-slate-800">${t.test_name}</h4>
                        <p class="text-xs font-black text-purple-400 uppercase tracking-wider mt-0.5">${t.subject_code} - ${t.subject_name || t.subject_code}</p>
                      </div>
                    </div>
                    <span class="text-slate-400 text-xs arrow-icon">▼</span>
                  </div>
                  <!-- Collapsible Content -->
                  <div id="co_exam_${t.test_id}" class="hidden p-4 bg-slate-50/10 border-t border-slate-100">
                    <div class="grid grid-cols-2 gap-4 mb-4 text-slate-600 font-semibold">
                      <div class="space-y-1">
                        <div>Duration: <span class="text-slate-800 font-bold">${t.duration} Mins</span></div>
                        <div>Total Questions: <span class="text-slate-800 font-bold">${t.mcq_count} MCQs</span></div>
                      </div>
                      <div class="space-y-1">
                        <div>Attempts: <span class="text-slate-800 font-bold">${t.my_attempts}/${t.max_attempts}</span></div>
                        <div>Deadline: <span class="text-slate-800 font-bold font-mono">${t.end_time ? new Date(t.end_time).toLocaleString() : 'No Limit'}</span></div>
                      </div>
                    </div>
                    <div class="mt-3">
                      ${actionHtml}
                    </div>
                  </div>
                </div>
              `;
            });
            container.innerHTML = html;
            container.className = "flex flex-col gap-1 mt-4 mb-6";
          } else {
            container.innerHTML = `<div class="col-span-full p-4 bg-white/60 border border-slate-200 rounded-xl text-center text-xs text-slate-500">No active tests available right now.</div>`;
            container.className = "mt-4 mb-6";
          }

          if (data.stats) updateStatsHeader(null, data.stats);
        });
    }

    function startOnlineTest(testId) {
      if(!confirm("Are you sure you want to start this test? The timer will begin immediately.")) return;
      
      fetch(`/api/student/online-tests/${testId}/start`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentTestId = testId;
          renderTestEngine(data.questions, data.duration);
        } else {
          alert(data.message || "Could not start test.");
        }
      });
    }

    function renderTestEngine(questions, durationMins) {
      document.getElementById('testEngineModal').classList.remove('hidden');

      let html = '<div class="max-w-3xl mx-auto space-y-6 pb-20">';
      questions.forEach((q, idx) => {
        let optionsHtml = '';
        q.options.forEach((opt, oIdx) => {
          optionsHtml += `
            <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-200/50 bg-white/50 cursor-pointer hover:border-purple-500/50 hover:bg-slate-100 transition-premium">
              <input type="radio" name="q_${idx}" value="${opt}" class="w-4 h-4 text-purple-500 bg-slate-50 border-slate-600 focus:ring-purple-600">
              <span class="text-xs text-slate-700">${opt}</span>
            </label>
          `;
        });
        html += `
          <div class="question-container bg-slate-50 border border-slate-200 rounded-xl p-6 shadow-lg">
             <div class="flex items-start gap-4 mb-4">
               <span class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-500/10 text-purple-400 flex items-center justify-center font-black text-xs border border-purple-500/20">${idx+1}</span>
               <h4 class="text-xs font-bold text-slate-900 mt-1">${q.q}</h4>
             </div>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-12">
               ${optionsHtml}
             </div>
          </div>
        `;
      });
      html += '</div>';
      document.getElementById('testQuestionsContainer').innerHTML = html;

      // Start Timer
      endTimeMs = Date.now() + (durationMins * 60 * 1000);
      timerInterval = setInterval(updateTimer, 1000);
      updateTimer();
    }

    function updateTimer() {
      let now = Date.now();
      let diff = endTimeMs - now;
      if (diff <= 0) {
        clearInterval(timerInterval);
        document.getElementById('liveTimer').innerText = "00:00:00";
        alert("Time is up! Auto-submitting your test.");
        submitTest();
        return;
      }
      
      let h = Math.floor(diff / (1000 * 60 * 60));
      let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      let s = Math.floor((diff % (1000 * 60)) / 1000);
      
      document.getElementById('liveTimer').innerText = 
        (h < 10 ? '0'+h : h) + ':' + 
        (m < 10 ? '0'+m : m) + ':' + 
        (s < 10 ? '0'+s : s);
    }

    function cancelTest() {
      if(!confirm("Are you sure? Your progress will be lost.")) return;
      document.getElementById('testEngineModal').classList.add('hidden');
    }

    function submitTest() {
      if(!currentTestId) return;
      document.getElementById('testEngineModal').classList.add('hidden');
      
      const formContainers = document.getElementById('testQuestionsContainer').querySelectorAll('.question-container');
      let answers = {};
      formContainers.forEach((container, idx) => {
        let checked = container.querySelector(`input[name="q_${idx}"]:checked`);
        answers[idx] = checked ? checked.value : null;
      });

      fetch(`/api/student/online-tests/${currentTestId}/submit`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ answers })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          // Hide engine, show result modal
          document.getElementById('testEngineModal').classList.add('hidden');
          document.getElementById('testResultModal').classList.remove('hidden');
          setTimeout(() => document.getElementById('resultModalBox').classList.remove('scale-95'), 50);

          document.getElementById('resultScore').innerText = `${data.summary.score}/${data.summary.total}`;
          document.getElementById('resultPercent').innerText = `${data.summary.percentage}%`;
        } else {
          alert(data.message || "Submission failed.");
        }
      });
    }

      function closeResultModal() {
        document.getElementById('testResultModal').classList.add('hidden');
        loadStudentTests(); // refresh the list
        loadAcademicReport(); // refresh academic stats so new marks show up
      }

    function viewAnswerKey(testId) {
      fetch(`/api/student/online-tests/${testId}/answer-key`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('answerKeyTestName').innerText = data.test_name;
            document.getElementById('answerKeyScoreInfo').innerText = `Best Score: ${data.score}/${data.total} (${data.percentage}%)`;
            
            let html = '<div class="max-w-3xl mx-auto space-y-6 pb-20">';
            data.details.forEach((q, idx) => {
              let optionsHtml = '';
              q.options.forEach((opt, oIdx) => {
                let badgeHtml = '';
                let borderClass = 'border-slate-200/50 bg-white/50';
                
                // Color code options
                if (opt === q.correct_ans) {
                  borderClass = 'border-green-500/50 bg-green-950/20';
                  badgeHtml = '<span class="text-xs bg-green-500/20 text-green-400 font-bold px-2 py-0.5 rounded ml-auto">Correct Answer</span>';
                } else if (opt === q.student_ans) {
                  borderClass = 'border-red-500/50 bg-red-950/20';
                  badgeHtml = '<span class="text-xs bg-red-500/20 text-red-400 font-bold px-2 py-0.5 rounded ml-auto">Your Answer</span>';
                }

                optionsHtml += `
                  <div class="flex items-center gap-3 p-3 rounded-lg border ${borderClass} transition-premium">
                    <span class="text-xs text-slate-700">${opt}</span>
                    ${badgeHtml}
                  </div>
                `;
              });

              let correctBadge = q.is_correct 
                ? '<span class="bg-green-500/10 text-green-400 text-xs font-bold px-2.5 py-1 rounded-full border border-green-500/20 flex items-center gap-1">✓ Correct</span>'
                : `<span class="bg-red-500/10 text-red-400 text-xs font-bold px-2.5 py-1 rounded-full border border-red-500/20 flex items-center gap-1">✕ Incorrect</span>`;

              html += `
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 shadow-lg">
                   <div class="flex items-start justify-between gap-4 mb-4">
                     <div class="flex items-start gap-4">
                       <span class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 text-slate-700 text-slate-600 flex items-center justify-center font-black text-xs border border-slate-200/20">${idx+1}</span>
                       <div>
                         <h4 class="text-xs font-bold text-slate-900 mt-1">${q.q}</h4>
                         <span class="text-xs text-slate-500 font-mono">CO Tag: ${q.co}</span>
                       </div>
                     </div>
                     ${correctBadge}
                   </div>
                   <div class="grid grid-cols-1 gap-3 pl-12">
                     ${optionsHtml}
                   </div>
                </div>
              `;
            });
            html += '</div>';
            
            document.getElementById('answerKeyQuestionsContainer').innerHTML = html;
            document.getElementById('answerKeyModal').classList.remove('hidden');
          } else {
            alert(data.message || "Could not retrieve answer key.");
          }
        });
    }

    function closeAnswerKeyModal() {
      document.getElementById('answerKeyModal').classList.add('hidden');
    }

    function loadActivityPoints() {
      fetch('/api/student/activity-points')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('overallActivityPoints').innerText = data.total_points || 0;
            document.getElementById('verifiedActivityTotal').innerText = data.total_points || 0;
            
            // Progress Bar
            let pts = data.total_points || 0;
            let goal = {{ $activityGoal }};
            let percent = Math.min(100, Math.round((pts / goal) * 100));
            
            const pBar = document.getElementById('activityProgressBar');
            pBar.style.width = percent + '%';
            
            if (percent >= 100) {
              pBar.className = "absolute top-0 left-0 h-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-1000 ease-out";
            } else if (percent >= 50) {
              pBar.className = "absolute top-0 left-0 h-full bg-gradient-to-r from-amber-500 to-orange-400 transition-all duration-1000 ease-out";
            } else {
              pBar.className = "absolute top-0 left-0 h-full bg-gradient-to-r from-red-500 to-rose-400 transition-all duration-1000 ease-out";
            }

            // Split
            let splitHtml = '';
            if (data.split && Object.keys(data.split).length > 0) {
              for (const [segment, pts] of Object.entries(data.split)) {
                splitHtml += `
                  <div class="flex justify-between items-center py-1">
                    <span class="text-xs text-slate-700">${segment}</span>
                    <span class="text-xs font-bold text-emerald-400">${pts}</span>
                  </div>
                `;
              }
            } else {
              splitHtml = '<div class="text-xs text-slate-500 py-1">No verified points yet.</div>';
            }
            document.getElementById('activitySplitList').innerHTML = splitHtml;

            // Claims Table
            const tbody = document.getElementById('activityClaimsTableBody');
            if (data.claims && data.claims.length > 0) {
              let html = '';
              data.claims.forEach(c => {
                let statusClass = 'text-amber-400 bg-amber-500/10 border-amber-500/20';
                if (c.status === 'Verified') statusClass = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
                if (c.status === 'Rejected') statusClass = 'text-rose-400 bg-rose-500/10 border-rose-500/20';
                
                let dateStr = c.created_at ? new Date(c.created_at).toLocaleDateString() : 'N/A';
                let verifiedDateStr = c.verified_at ? new Date(c.verified_at).toLocaleDateString() : '';
                
                let noteHtml = '';
                if (c.status === 'Rejected' && c.rejection_note) {
                  noteHtml = `<div class="mt-1 text-xs text-rose-400/80 leading-tight">Reason: ${c.rejection_note}</div>`;
                }
                if (c.status !== 'Pending' && verifiedDateStr) {
                  noteHtml += `<div class="mt-0.5 text-xs text-slate-500">On: ${verifiedDateStr}</div>`;
                }
                
                html += `
                  <tr class="hover:bg-white/50 transition-colors border-b border-slate-100">
                    <td class="p-3 text-xs text-slate-600">${dateStr}</td>
                    <td class="p-3 text-xs font-bold text-slate-700">${c.activity_segment}</td>
                    <td class="p-3 text-xs text-slate-700">${c.activity_name}</td>
                    <td class="p-3 text-xs text-slate-600">${c.level}</td>
                    <td class="p-3">
                      ${c.document_reference ? `<a href="${c.document_reference}" target="_blank" class="text-blue-400 hover:text-blue-300 text-xs underline flex items-center gap-1">🔗 View</a>` : '<span class="text-xs text-slate-600">None</span>'}
                    </td>
                    <td class="p-3 text-center text-xs font-bold text-slate-700">${c.points_claimed}</td>
                    <td class="p-3 text-center text-xs font-bold ${c.status === 'Verified' ? 'text-emerald-400' : 'text-slate-500'}">${c.status === 'Verified' ? c.points_awarded : '--'}</td>
                    <td class="p-3 text-right max-w-[120px]">
                      <span class="px-2 py-0.5 rounded border text-xs font-bold uppercase tracking-wider ${statusClass} inline-block">${c.status}</span>
                      ${noteHtml}
                    </td>
                  </tr>
                `;
              });
              tbody.innerHTML = html;
            } else {
              tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-xs">No activity claims submitted yet.</td></tr>`;
            }
          }
        });
    }

    function submitActivityClaim(e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());

      fetch('/api/student/activity-points', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(resData => {
        if (resData.status === 'SUCCESS') {
          form.reset();
          loadActivityPoints();
        } else {
          alert(resData.message || 'Failed to submit claim.');
        }
      });
    }
  
  </script>

  @include('partials.support_desk_overlay')
</body>
</html>