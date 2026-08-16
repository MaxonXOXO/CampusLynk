<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - Student Portal</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Fonts: Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Chart.js CDN for Analytics Gauges & Trends -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Vite Asset Pipeline -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FAFAFB] text-slate-800 flex flex-col antialiased font-['Poppins']">

  <div class="flex h-screen overflow-hidden bg-[#FAFAFB]">
    
    <!-- Unified Standalone Sidebar Navigation (Student Role) -->
    <x-layout.sidebar role="student" active="exams" />

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#FAFAFB]">
      
      <!-- Master TopBar Component -->
      <x-layout.topbar />

      <!-- Scrollable Main View Container -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- PRE-CLASS ACADEMIC READINESS & LEARNING VAULT ALERT BANNER -->
        <div id="vlmPreClassAlertBanner" class="hidden bg-white border border-amber-200/80 rounded-2xl p-5 shadow-sm relative overflow-hidden transition-all">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 relative z-10">
            <div class="flex items-start gap-3.5">
              <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center shrink-0">
                <i data-lucide="bell-ring" class="w-5 h-5 animate-hover-bell"></i>
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200/60 text-[10px] font-semibold uppercase tracking-wider">
                    Pre-Class Evening Readiness
                  </span>
                  <span id="vlmAlertTargetDate" class="text-xs text-slate-500 font-medium"></span>
                </div>
                <h3 id="vlmAlertTitle" class="font-bold text-slate-900 text-sm mt-1"></h3>
                <p id="vlmAlertInstruction" class="text-xs text-slate-600 mt-0.5 max-w-2xl"></p>
              </div>
            </div>

            <div class="flex items-center gap-2.5 shrink-0 w-full md:w-auto justify-end">
              <button type="button" onclick="openVlmVaultModal()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl text-xs transition-all shadow-sm flex items-center gap-1.5">
                <i data-lucide="folder" class="w-3.5 h-3.5"></i>
                <span>Learning Vault</span>
              </button>
              <button type="button" onclick="acknowledgeVlmNotice()" id="btnAckVlm" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition-all border border-slate-200 flex items-center gap-1.5">
                <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span>Acknowledge</span>
              </button>
            </div>
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 1. PANEL: WORKS TO DO / ACTIVE EXAMS (Default Active) -->
        <!-- ========================================================================= -->
        <div id="panelExams" class="space-y-6">
          
          <!-- KPI Summary Cards (Neutral 70% Base, High-Contrast Metrics) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Assignments Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <i data-lucide="file-text" class="w-5 h-5"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Assignments</p>
                <div class="flex justify-between items-baseline mt-1">
                  <span class="text-xs text-slate-500">Active: <strong class="text-slate-900 font-bold text-base" id="statActiveAssign">0</strong></span>
                  <span class="text-xs text-slate-500">Done: <strong class="text-emerald-700 font-bold text-base" id="statAssignDone">0</strong></span>
                </div>
              </div>
            </div>

            <!-- Written Tests Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <i data-lucide="file-edit" class="w-5 h-5"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Written Tests</p>
                <div class="flex justify-between items-baseline mt-1">
                  <span class="text-xs text-slate-500">Active: <strong class="text-slate-900 font-bold text-base" id="statWrittenTests">0</strong></span>
                  <span class="text-xs text-slate-500">Done: <strong class="text-blue-700 font-bold text-base" id="statWrittenTestsDone">0</strong></span>
                </div>
              </div>
            </div>

            <!-- MCQ Tests Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <i data-lucide="help-circle" class="w-5 h-5"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Online MCQ</p>
                <div class="flex justify-between items-baseline mt-1">
                  <span class="text-xs text-slate-500">Active: <strong class="text-slate-900 font-bold text-base" id="statActiveTests">0</strong></span>
                  <span class="text-xs text-slate-500">Done: <strong class="text-emerald-700 font-bold text-base" id="statTestsDone">0</strong></span>
                </div>
              </div>
            </div>

            <!-- Overall Tasks Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                <i data-lucide="list-checks" class="w-5 h-5"></i>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Overall Tasks</p>
                <div class="flex justify-between items-baseline mt-1">
                  <span class="text-xs text-slate-500">Pending: <strong class="text-rose-600 font-bold text-base" id="statPendingTotal">0</strong></span>
                  <span class="text-xs text-slate-500">Total Done: <strong class="text-slate-900 font-bold text-base" id="statOverallDone">0</strong></span>
                </div>
              </div>
            </div>

          </div>

          <!-- Pending Tasks Section -->
          <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
              <div>
                <h2 class="text-base font-bold text-slate-900">Pending Academic Work</h2>
                <p class="text-xs text-slate-500 mt-0.5">Active continuous evaluations, assignments, and series test submissions.</p>
              </div>
            </div>

            <div id="pendingGridContainer" class="grid grid-cols-1 gap-6">
              <!-- Active Surveys Container -->
              <div id="studentSurveysContainer" class="col-span-full hidden"></div>

              <!-- Column 1: Online MCQ Tests -->
              <div id="mcqTestsSection" class="hidden space-y-3">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                  <i data-lucide="help-circle" class="w-4 h-4 text-blue-600"></i>
                  <span>Online MCQ Assessments</span>
                </h3>
                <div id="studentActiveTestsList" class="flex flex-col gap-3">
                  <div class="py-8 text-center text-slate-400 font-medium text-xs">Loading active tests...</div>
                </div>
              </div>

              <!-- Column 2: Assignments & Written Tests -->
              <div id="assignmentsSection" class="hidden space-y-3">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                  <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i>
                  <span>Assignments & Written Tasks</span>
                </h3>
                <div id="studentActiveTasksContainer" class="flex flex-col gap-3">
                  <div class="py-8 text-center text-slate-400 font-medium text-xs">Loading active tasks...</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Subject Syllabus Progress Cards -->
          <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3">
              <h2 class="text-base font-bold text-slate-900">Semester Course Progress</h2>
              <p id="subjectProgressSubtitle" class="text-xs text-slate-500 mt-0.5">Completed class hours and syllabus coverage logged by course faculty.</p>
            </div>
            
            <div id="subjectProgressGrid" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 pt-2">
              <div class="col-span-full py-8 text-center text-slate-400 font-medium text-xs">Loading course syllabus coverage...</div>
            </div>
          </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 2. PANEL: ACADEMIC STATS & CIE MARKS -->
        <!-- ========================================================================= -->
        <div id="panelMarks" class="hidden space-y-6">
          
          <!-- Gauges & Trend Grid -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Cumulative GPA Gauge -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center">
              <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Cumulative GPA</span>
              <div class="relative w-28 h-28 flex items-center justify-center my-3">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                  <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent" />
                  <circle id="cgpaGaugeProgress" cx="50" cy="50" r="40" stroke="#2563EB" stroke-width="8" fill="transparent"
                          stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                </svg>
                <div class="absolute flex flex-col items-center leading-none">
                  <span id="overallCgpa" class="text-2xl font-bold text-slate-900">0.00</span>
                  <span class="text-[10px] text-slate-400 font-medium mt-1 uppercase">Max 10.0</span>
                </div>
              </div>
              <div class="text-xs text-slate-500 font-medium">
                Classification: <span id="diplomaClassification" class="text-blue-700 font-semibold">--</span>
              </div>
            </div>

            <!-- Activity Points Gauge -->
            <div id="activityPointsCard" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center">
              <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Activity Points</span>
              <div class="relative w-28 h-28 flex items-center justify-center my-3">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                  <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent" />
                  <circle id="activityGaugeProgress" cx="50" cy="50" r="40" stroke="#10B981" stroke-width="8" fill="transparent"
                          stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                </svg>
                <div class="absolute flex flex-col items-center leading-none">
                  <span id="overallActivityPoints" class="text-2xl font-bold text-slate-900">0</span>
                  <span class="text-[10px] text-slate-400 font-medium mt-1 uppercase">Max 160</span>
                </div>
              </div>
              <div class="text-xs text-slate-500 font-medium">
                Min Required: <span class="text-emerald-700 font-semibold">60 Points</span>
              </div>
            </div>

            <!-- Overall Attendance Gauge -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center">
              <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Semester Attendance</span>
              <div class="relative w-28 h-28 flex items-center justify-center my-3">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                  <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent" />
                  <circle id="attendanceGaugeProgress" cx="50" cy="50" r="40" stroke="#10B981" stroke-width="8" fill="transparent"
                          stroke-dasharray="251.2" stroke-dashoffset="251.2" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                </svg>
                <div class="absolute flex flex-col items-center leading-none">
                  <span id="overallAttendancePct" class="text-2xl font-bold text-slate-900">0%</span>
                  <span class="text-[10px] text-slate-400 font-medium mt-1 uppercase">Current Sem</span>
                </div>
              </div>
              <div class="text-xs text-slate-500 font-medium">
                Present Hours: <span id="attendanceHoursDetail" class="text-slate-900 font-semibold">0 / 0</span>
              </div>
            </div>

            <!-- SGPA Trend Chart -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col justify-between items-center text-center">
              <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">SGPA Performance</span>
              <div class="w-full h-28 flex items-center justify-center my-2">
                <canvas id="cgpaChart"></canvas>
              </div>
              <div class="text-xs text-slate-400 font-medium">Semester-wise Trend</div>
            </div>

          </div>

          <!-- Semester Selection Tabs -->
          <div class="bg-white border border-slate-200 rounded-2xl p-3 shadow-sm flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-2">Select Semester:</span>
            <div id="semesterTabsContainer" class="flex gap-1.5 overflow-x-auto"></div>
          </div>

          <!-- Academic Marks Report (God Table) -->
          <div id="academicReportContent" class="space-y-4">
            <div class="text-slate-400 text-center p-8 bg-white border border-slate-200 rounded-2xl shadow-sm text-xs font-medium">Loading marksheet & evaluation records...</div>
          </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 3. PANEL: MY PROFILE -->
        <!-- ========================================================================= -->
        <div id="panelProfile" class="hidden space-y-6">
          <div class="max-w-3xl mx-auto space-y-6">
            
            <!-- Profile Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col sm:flex-row items-center gap-5">
              <div class="relative group shrink-0">
                <div id="studentAvatarWrapper" class="w-20 h-20 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 flex items-center justify-center shadow-sm relative">
                  @if(session('userPhoto'))
                    <img id="studentProfileImg" src="{{ session('userPhoto') }}" class="w-full h-full object-cover">
                  @else
                    <div id="studentProfilePlaceholder" class="w-full h-full bg-slate-900 flex items-center justify-center font-bold text-2xl text-white">
                      {{ strtoupper(substr(session('userName','S'), 0, 2)) }}
                    </div>
                  @endif
                </div>
                <label for="photoUploadInput" class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer rounded-2xl text-white text-xs font-semibold text-center gap-1 p-1">
                  <i data-lucide="camera" class="w-4 h-4"></i>
                  <span>Change</span>
                </label>
                <input type="file" id="photoUploadInput" accept="image/*" class="hidden" onchange="handlePhotoUpload(event)">
              </div>

              <div class="text-center sm:text-left flex-1 min-w-0">
                <h2 class="text-lg font-bold text-slate-900 truncate">{{ session('userName') }}</h2>
                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ session('userId') }} • {{ session('userBranch') }}</p>
                <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  Active Student
                </div>
                <div id="photoUploadStatus" class="text-xs font-semibold mt-2 hidden"></div>
              </div>
            </div>

            <!-- Academic Metadata Grid -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
              <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 pb-3">Institutional Record</h3>
              <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                  <dt class="text-xs font-medium text-slate-500">Registration Number</dt>
                  <dd class="text-sm font-semibold font-mono text-slate-900 mt-0.5">{{ session('userId') }}</dd>
                </div>
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                  <dt class="text-xs font-medium text-slate-500">Department / Branch</dt>
                  <dd class="text-sm font-semibold text-slate-900 mt-0.5">{{ session('userBranch') }}</dd>
                </div>
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                  <dt class="text-xs font-medium text-slate-500">Classroom Identifier</dt>
                  <dd class="text-sm font-semibold text-slate-900 mt-0.5">{{ session('classroomId', '-') }}</dd>
                </div>
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                  <dt class="text-xs font-medium text-slate-500">Curriculum Standard</dt>
                  <dd class="text-sm font-semibold text-blue-700 mt-0.5">Revision 2026 (R2026)</dd>
                </div>
              </dl>
            </div>

            <!-- SBTE Register Number Section -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-3">
              <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                <i data-lucide="award" class="w-4 h-4 text-blue-600"></i>
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">SBTE Examination Register Number</h3>
              </div>
              @php $sbteNo = session('sbteRegNo', ''); @endphp
              @if($sbteNo)
                <div class="p-4 bg-emerald-50 border border-emerald-200/60 rounded-xl flex items-center justify-between">
                  <div>
                    <p class="text-xs text-emerald-800 font-medium">Confirmed Number</p>
                    <p class="text-base font-bold font-mono text-emerald-950 mt-0.5">{{ $sbteNo }}</p>
                  </div>
                  <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                </div>
              @else
                <p class="text-xs text-slate-500">Enter your assigned SBTE Diploma Examination Register Number:</p>
                <div class="flex gap-2 pt-1">
                  <input type="text" id="sbteRegNoInput" placeholder="e.g. 25EL001" class="flex-1 min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm font-mono text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none">
                  <button type="button" onclick="updateSbteRegNo()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm">Save</button>
                </div>
                <div id="sbteAlert" class="hidden p-3 rounded-xl text-xs font-semibold border mt-2"></div>
              @endif
            </div>

            <!-- Change Password Section -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
              <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-slate-100 pb-3">Security & Password</h3>
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-slate-700 mb-1">Current Password</label>
                  <input type="password" id="oldPwd" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none" placeholder="Enter current password">
                </div>
                <div>
                  <label class="block text-xs font-medium text-slate-700 mb-1">New Password</label>
                  <input type="password" id="newPwd" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none" placeholder="At least 6 characters">
                </div>
                <div id="pwdAlert" class="hidden p-3 rounded-xl text-xs font-semibold border"></div>
                <button type="button" onclick="changePassword()" class="w-full min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm">Update Password</button>
              </div>
            </div>

          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 4. PANEL: MENTORING DIARY (Clean Integrated 360° Portfolio) -->
        <!-- ========================================================================= -->
        <div id="panelMentoring" class="hidden space-y-6">
          <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            
            <!-- Mentoring Header Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
              <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                  <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
                  <span>Student Mentoring Diary</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">360° student portfolio verified and monitored by faculty mentors.</p>
              </div>
              <div class="flex items-center gap-2">
                <button type="button" onclick="downloadMentoringPdf()" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5">
                  <i data-lucide="download" class="w-3.5 h-3.5"></i>
                  <span>Export PDF</span>
                </button>
                <button type="button" onclick="saveStudentMentoringData()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm flex items-center gap-1.5">
                  <i data-lucide="save" class="w-3.5 h-3.5"></i>
                  <span>Save Changes</span>
                </button>
              </div>
            </div>

            <!-- Mentoring Sub-tabs Navigation -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-1.5 p-1.5 bg-slate-100/90 rounded-2xl border border-slate-200/60" id="smdSubTabs">
              <button type="button" onclick="switchStudentMentoringTab('smdProfile')" id="tabBtn_smdProfile" class="smd-tab py-2 px-2.5 text-xs font-semibold rounded-xl bg-blue-600 text-white shadow-sm transition-all text-center">
                Personal
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdFamily')" id="tabBtn_smdFamily" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Family
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdEducation')" id="tabBtn_smdEducation" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Education
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdAcademic')" id="tabBtn_smdAcademic" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Academics
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdBoard')" id="tabBtn_smdBoard" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Board Exams
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdExtra')" id="tabBtn_smdExtra" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Activities
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdMeetings')" id="tabBtn_smdMeetings" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Meetings
              </button>
            </div>

            <!-- Mentoring Content Panes -->
            <div class="pt-2">
              
              <!-- 1. Personal & Guardian Details Pane -->
              <div id="smdProfile" class="smd-content-pane space-y-6">
                <div>
                  <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Socio-Economic & Residential Profile</h3>
                  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
                    <div>
                      <label class="block text-xs font-semibold text-slate-700 mb-1">Annual Family Income</label>
                      <input type="text" id="smd_annual_income" placeholder="e.g. ₹2,50,000" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none shadow-sm">
                    </div>
                    <div>
                      <x-ui.select id="smd_residential_status" name="residential_status" label="Residential Status" :options="['Day Scholar'=>'Day Scholar', 'Hosteller'=>'Hosteller']" value="Day Scholar" />
                    </div>
                    <div>
                      <label class="block text-xs font-semibold text-slate-700 mb-1">Scholarships Received</label>
                      <input type="text" id="smd_scholarships" placeholder="e.g. E-Grantz / NSP" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none shadow-sm">
                    </div>
                    <div>
                      <span class="block text-xs font-semibold text-slate-700 mb-1">Special Category</span>
                      <label for="smd_fee_waiver" class="flex items-center gap-2.5 min-h-[44px] px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100/80 transition-colors select-none shadow-sm">
                        <input type="checkbox" id="smd_fee_waiver" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-medium text-slate-800">Fee Waiver Beneficiary</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="border-t border-slate-100 pt-5">
                  <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Guardian Information</h3>
                  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                      <label class="block text-xs font-medium text-slate-700 mb-1">Guardian Name</label>
                      <input type="text" id="smd_guardian_name" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-slate-700 mb-1">Relationship</label>
                      <input type="text" id="smd_guardian_relationship" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-slate-700 mb-1">Contact Mobile</label>
                      <input type="text" id="smd_guardian_mobile" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none">
                    </div>
                    <div class="sm:col-span-3">
                      <label class="block text-xs font-medium text-slate-700 mb-1">Permanent Residential Address</label>
                      <textarea id="smd_guardian_address" rows="2" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none resize-none"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- 2. Family Details Pane -->
              <div id="smdFamily" class="smd-content-pane hidden space-y-4">
                <div class="flex items-center justify-between">
                  <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Family Members Roster</h3>
                  <button type="button" onclick="addFamilyRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all flex items-center gap-1">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Add Member</span>
                  </button>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                  <table class="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Member Name</th>
                        <th class="py-3 px-4">Relationship</th>
                        <th class="py-3 px-4">Education</th>
                        <th class="py-3 px-4">Occupation</th>
                        <th class="py-3 px-4">Contact No</th>
                        <th class="py-3 px-4 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody id="smdFamilyList" class="divide-y divide-slate-100 text-slate-800">
                      <tr><td colspan="6" class="p-6 text-center text-slate-400">Loading family records...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- 3. Prior Education Pane -->
              <div id="smdEducation" class="smd-content-pane hidden space-y-4">
                <div class="flex items-center justify-between">
                  <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Prior Academic Qualifications</h3>
                  <button type="button" onclick="addEducationRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all flex items-center gap-1">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Add Qualification</span>
                  </button>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                  <table class="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Examination / Course</th>
                        <th class="py-3 px-4">Institution / Board</th>
                        <th class="py-3 px-4">Year of Passing</th>
                        <th class="py-3 px-4">Percentage / CGPA</th>
                        <th class="py-3 px-4 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody id="smdEducationList" class="divide-y divide-slate-100 text-slate-800">
                      <tr><td colspan="5" class="p-6 text-center text-slate-400">Loading qualification records...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- 4. Academic Progress Pane -->
              <div id="smdAcademic" class="smd-content-pane hidden space-y-4">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Internal Academic Records & Attendance</h3>
                <div id="smdAcademicReport" class="space-y-4">
                  <div class="p-6 text-center text-slate-400 text-xs font-medium">Loading semester academic trajectory...</div>
                </div>
              </div>

              <!-- 5. Board Exams Pane -->
              <div id="smdBoard" class="smd-content-pane hidden space-y-4">
                <div class="flex items-center justify-between">
                  <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">SBTE Board Examination Results</h3>
                  <button type="button" onclick="addBoardRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all flex items-center gap-1">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Add Result</span>
                  </button>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                  <table class="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Semester</th>
                        <th class="py-3 px-4 text-center">SGPA</th>
                        <th class="py-3 px-4 text-center">CGPA</th>
                        <th class="py-3 px-4 text-center">Activity Points</th>
                        <th class="py-3 px-4 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody id="smdBoardList" class="divide-y divide-slate-100 text-slate-800">
                      <tr><td colspan="5" class="p-6 text-center text-slate-400">Loading board exam records...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- 6. Extracurricular Pane -->
              <div id="smdExtra" class="smd-content-pane hidden space-y-4">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Extracurricular Activity Claims</h3>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                  <table class="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Event Name</th>
                        <th class="py-3 px-4">Level</th>
                        <th class="py-3 px-4">Prize / Participation</th>
                        <th class="py-3 px-4 text-center">Awarded Points</th>
                        <th class="py-3 px-4 text-right">Status</th>
                      </tr>
                    </thead>
                    <tbody id="smdExtraList" class="divide-y divide-slate-100 text-slate-800">
                      <tr><td colspan="5" class="p-6 text-center text-slate-400">Loading extracurricular records...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- 7. Mentor Meetings Pane -->
              <div id="smdMeetings" class="smd-content-pane hidden space-y-4">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Mentor-Mentee Interaction Logs</h3>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                  <table class="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Meeting Date</th>
                        <th class="py-3 px-4">Discussion Summary</th>
                        <th class="py-3 px-4">Faculty Mentor Remarks</th>
                        <th class="py-3 px-4">Action Taken</th>
                      </tr>
                    </thead>
                    <tbody id="smdMeetingsList" class="divide-y divide-slate-100 text-slate-800">
                      <tr><td colspan="4" class="p-6 text-center text-slate-400">Loading meeting records...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>

          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 5. PANEL: ACTIVITY POINTS -->
        <!-- ========================================================================= -->
        @php
          $isLet = session('userAdmissionType') === 'LET';
          $activityGoal = $isLet ? 40 : 60;
        @endphp
        <div id="panelActivity" class="hidden space-y-6">
          <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-b border-slate-100 pb-6">
              <div class="md:col-span-2 space-y-3">
                <h3 class="text-sm font-bold text-slate-900">Activity Points Goal Tracker</h3>
                <p class="text-xs text-slate-500">Required graduation quota: {{ $activityGoal }} points under SBTE regulations.</p>
                <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                  <div id="activityProgressBar" class="h-full bg-blue-600 transition-all duration-1000 ease-out" style="width: 0%"></div>
                </div>
                <div class="flex justify-between text-xs font-semibold text-slate-400">
                  <span>0</span>
                  <span>Target: {{ $activityGoal }} Points</span>
                </div>
              </div>

              <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/60 flex flex-col justify-between">
                <div>
                  <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Verified Total</span>
                  <span class="text-2xl font-bold text-slate-900 block mt-1" id="verifiedActivityTotal">0</span>
                </div>
                <div class="mt-3 border-t border-slate-200/60 pt-2" id="activitySplitList"></div>
              </div>
            </div>

            <!-- Submit New Claim Form -->
            <div class="space-y-4">
              <h3 class="text-sm font-bold text-slate-900">Submit New Extracurricular Claim</h3>
              
              <form id="activityClaimForm" onsubmit="submitActivityClaim(event)" class="bg-slate-50 border border-slate-200/80 p-4 rounded-xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3">
                <div class="lg:col-span-1">
                  <x-ui.select name="semester" label="Semester" :options="['1'=>'Sem 1', '2'=>'Sem 2', '3'=>'Sem 3', '4'=>'Sem 4', '5'=>'Sem 5', '6'=>'Sem 6']" value="1" />
                </div>
                <div class="lg:col-span-1">
                  <x-ui.select name="activity_segment" label="Segment" placeholder="Select..." :options="['NCC'=>'NCC', 'NSS'=>'NSS', 'Sports & Games'=>'Sports & Games', 'Cultural Activities'=>'Cultural Activities', 'Professional Self Initiatives'=>'Prof. Self Initiatives', 'Entrepreneurship and Innovation'=>'Innovation', 'Leadership & Management'=>'Leadership', 'Disaster Management'=>'Disaster Mgmt']" />
                </div>
                <div class="lg:col-span-1">
                  <label class="block text-xs font-medium text-slate-700 mb-1">Activity Name</label>
                  <input type="text" name="activity_name" required placeholder="e.g. Arts 1st Prize" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 outline-none">
                </div>
                <div class="lg:col-span-1">
                  <x-ui.select name="level" label="Level" placeholder="Level..." :options="['Level I - College'=>'Level I - College', 'Level II - Zonal'=>'Level II - Zonal', 'Level III - State/Univ'=>'Level III - State', 'Level IV - National'=>'Level IV - National', 'Level V - International'=>'Level V - International']" />
                </div>
                <div class="lg:col-span-1">
                  <label class="block text-xs font-medium text-slate-700 mb-1">Points Claimed</label>
                  <input type="number" name="points_claimed" required min="1" max="50" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 outline-none">
                </div>
                <div class="lg:col-span-1 flex flex-col justify-end">
                  <button type="submit" class="w-full min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs transition-all shadow-sm">Submit Claim</button>
                </div>
                <div class="lg:col-span-6">
                  <label class="block text-xs font-medium text-slate-700 mb-1">Document Evidence (Describe physical or digital proof submitted to tutor)</label>
                  <input type="text" name="document_reference" placeholder="e.g. State Level Merit Certificate Hardcopy" class="w-full min-h-[40px] px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-900 outline-none">
                </div>
              </form>
            </div>

            <!-- Activity Claims Table -->
            <div class="overflow-x-auto rounded-xl border border-slate-200">
              <table class="w-full text-left text-xs border-collapse">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                    <th class="py-3 px-4">Submitted On</th>
                    <th class="py-3 px-4">Segment</th>
                    <th class="py-3 px-4">Activity</th>
                    <th class="py-3 px-4">Level</th>
                    <th class="py-3 px-4">Evidence</th>
                    <th class="py-3 px-4 text-center">Claimed</th>
                    <th class="py-3 px-4 text-center">Awarded</th>
                    <th class="py-3 px-4 text-right">Status</th>
                  </tr>
                </thead>
                <tbody id="activityClaimsTableBody" class="divide-y divide-slate-100 text-slate-800"></tbody>
              </table>
            </div>

          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 6. PANEL: MY SEMINAR -->
        <!-- ========================================================================= -->
        <div id="panelSeminar" class="hidden space-y-6">
          <div class="max-w-3xl mx-auto space-y-5">
            
            <div id="seminarToast" class="hidden p-3.5 rounded-xl text-xs font-semibold border"></div>

            <!-- Status Banner when Registered -->
            <div id="seminarStatusBanner" class="hidden bg-emerald-50 border border-emerald-200/80 rounded-2xl p-5 shadow-sm">
              <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                  <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                  <div>
                    <p id="semStatusBadgeTitle" class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Seminar Registered</p>
                    <p id="semStatusTopic" class="text-slate-900 font-bold text-sm mt-0.5">-</p>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs text-slate-600">
                      <span>Guide: <strong id="semStatusGuide" class="text-slate-900">-</strong></span>
                      <span>Date: <strong id="semStatusDate" class="text-slate-900">-</strong></span>
                      <span>Avg Score: <strong id="semStatusScore" class="text-blue-700 font-bold">- / 75</strong></span>
                    </div>
                  </div>
                </div>
                <button type="button" onclick="showSeminarEditForm()" class="px-3.5 py-1.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                  Edit Details
                </button>
              </div>
            </div>

            <!-- Seminar Form Card -->
            <div id="seminarFormCard" class="bg-white border border-slate-200 p-6 rounded-2xl shadow-sm space-y-4">
              <div class="border-b border-slate-100 pb-3">
                <h3 id="semFormTitle" class="font-bold text-slate-900 text-base flex items-center gap-2">
                  <i data-lucide="presentation" class="w-4 h-4 text-blue-600"></i>
                  <span>Register Seminar Details</span>
                </h3>
                <p class="text-xs text-slate-500 mt-1">Specify your technical presentation topic, proposed date, and assign a faculty advisor.</p>
              </div>

              <form id="seminarRegistrationForm" onsubmit="submitSeminarRegistration(event)" class="space-y-4 pt-1">
                <input type="hidden" id="semRegSubject">
                <div>
                  <label class="block text-xs font-semibold text-slate-700 mb-1">Seminar Topic</label>
                  <input type="text" id="semRegTopic" required placeholder="e.g. Transformer Neural Networks in Autonomous Vehicles"
                    class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Proposed Presentation Date</label>
                    <input type="date" id="semRegDate" required
                      class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none">
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Seminar Guide / Faculty</label>
                    <select id="semRegGuide" required
                      class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none">
                      <option value="">Loading guides...</option>
                    </select>
                  </div>
                </div>
                <div class="pt-3 flex items-center justify-between">
                  <button type="button" id="semCancelEditBtn" onclick="cancelSeminarEdit()" class="hidden px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                    Cancel
                  </button>
                  <button type="submit" id="semSubmitBtn" class="ml-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm">
                    Save Registration
                  </button>
                </div>
              </form>
            </div>

          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 7. PANEL: ATTENDANCE REVIEW -->
        <!-- ========================================================================= -->
        <div id="panelAttendance" class="hidden space-y-6">
          
          <!-- Attendance Loader -->
          <div id="attendanceLoader" class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
            <div class="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin mb-3"></div>
            <p class="text-xs font-semibold text-slate-500">Loading attendance records and real-time period logs...</p>
          </div>

          <!-- Attendance Content Container -->
          <div id="attendanceContent" class="hidden space-y-6">
            
            <!-- Top KPI Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              
              <!-- Cumulative Attendance Card -->
              <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between items-center text-center">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Overall Semester Attendance</span>
                
                <div class="relative w-32 h-32 flex items-center justify-center my-3">
                  <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent" />
                    <circle id="attGaugeCircle" cx="50" cy="50" r="40" stroke="#10B981" stroke-width="8" fill="transparent"
                            stroke-dasharray="251.2" stroke-dashoffset="0" stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                  </svg>
                  <div class="absolute flex flex-col items-center leading-none">
                    <span class="text-2xl font-bold text-slate-900" id="attOverallPctText">100%</span>
                    <span class="text-[10px] text-slate-400 font-medium mt-1 uppercase">Target 75%</span>
                  </div>
                </div>

                <div class="text-xs text-slate-500 font-medium">
                  Status: 
                  <span class="font-bold text-emerald-700" id="attEligibilityBadge">
                    Satisfactory & Eligible
                  </span>
                </div>
              </div>

              <!-- Hours Statistics Card -->
              <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between space-y-4">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 pb-2">Academic Hours Summary</h3>
                
                <div class="space-y-3">
                  <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                    <span class="text-xs text-slate-600 font-medium">Conducted Sessions</span>
                    <span class="text-sm font-bold font-mono text-slate-900" id="attTotalConducted">0 Hours</span>
                  </div>
                  <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/60 border border-emerald-200/60">
                    <span class="text-xs text-emerald-800 font-medium">Attended Sessions</span>
                    <span class="text-sm font-bold font-mono text-emerald-950" id="attTotalAttended">0 Hours</span>
                  </div>
                  <div class="flex items-center justify-between p-3 rounded-xl bg-rose-50/60 border border-rose-200/60">
                    <span class="text-xs text-rose-800 font-medium">Absent Sessions</span>
                    <span class="text-sm font-bold font-mono text-rose-950" id="attTotalAbsent">0 Hours</span>
                  </div>
                </div>

                <div class="text-[11px] text-slate-400">Calculated across standard periods 1 to 6.</div>
              </div>

              <!-- Class & Faculty Tutor Card -->
              <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-between space-y-4">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 pb-2">Institutional Advisor</h3>
                
                <div class="space-y-3">
                  <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                    <p class="text-xs font-medium text-slate-500">Classroom Identifier</p>
                    <p class="text-sm font-bold text-slate-900 mt-0.5" id="attClassroomId">-</p>
                  </div>
                  <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200/60">
                    <p class="text-xs font-medium text-slate-500">Class Tutor</p>
                    <p class="text-sm font-bold text-slate-900 mt-0.5" id="attTutorName">-</p>
                    <p class="text-[11px] text-slate-500 font-mono mt-0.5" id="attTutorContact"></p>
                  </div>
                </div>

                <div class="text-[11px] text-slate-400">Regular contact tutor for condonation and leaves.</div>
              </div>

            </div>

            <!-- Today's Hour-Wise Attendance Grid -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                <div>
                  <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-blue-600"></i>
                    <span>Today's Period Attendance Timeline</span>
                  </h2>
                  <p class="text-xs text-slate-500 mt-0.5" id="attTodayDateLabel">Today • Hourly live attendance logs</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                  <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                  Live Log Active
                </span>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3 pt-2" id="attHourlyGrid"></div>
            </div>

            <!-- Subject-Wise Attendance Breakdown Table -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
              <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                  <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
                  <span>Subject-Wise Attendance Distribution</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Continuous attendance records and minimum 75% examination eligibility thresholds.</p>
              </div>

              <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                      <th class="py-3 px-4">Subject Code & Name</th>
                      <th class="py-3 px-4 text-center">Conducted</th>
                      <th class="py-3 px-4 text-center">Attended</th>
                      <th class="py-3 px-4 text-center">Percentage</th>
                      <th class="py-3 px-4 text-right">Eligibility Status</th>
                    </tr>
                  </thead>
                  <tbody id="attSubjectStatsList" class="divide-y divide-slate-100 text-slate-800">
                    <tr><td colspan="5" class="p-6 text-center text-slate-400">Loading subject statistics...</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Leave & Absence Records Table -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
              <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                  <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i>
                  <span>Student Leave & Absence Log</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Registered medical leaves, duty leaves, and tutor condonation records.</p>
              </div>

              <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-xs border-collapse">
                  <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                      <th class="py-3 px-4">Leave Date</th>
                      <th class="py-3 px-4">Type / Reason</th>
                      <th class="py-3 px-4">Period Range</th>
                      <th class="py-3 px-4 text-right">Verification Status</th>
                    </tr>
                  </thead>
                  <tbody id="attLeaveRecordsList" class="divide-y divide-slate-100 text-slate-800">
                    <tr><td colspan="4" class="p-6 text-center text-slate-400">No formal leave requests recorded.</td></tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 8. PANEL: PRACTICE TEST & ASSESSMENT HUB -->
        <!-- ========================================================================= -->
        <div id="panelMock_test" class="hidden space-y-6">
          
          <!-- 1. Setup Section -->
          <section id="mockSetupSection" class="space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
              
              <div class="border-b border-slate-100 pb-4">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200/60 text-blue-700 text-xs font-semibold uppercase tracking-wider mb-2">
                  <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                  <span>Practice Session Setup</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Select Subject & Test Scope</h2>
                <p class="text-xs text-slate-500 mt-1">Take self-assessment quizzes generated from your syllabus. Daily quota: 1 attempt per subject.</p>
              </div>

              <!-- Setup Loader -->
              <div id="mockSetupLoader" class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
                <div class="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin mb-3"></div>
                <p class="text-xs font-semibold text-slate-500">Loading semester subjects & syllabus modules...</p>
              </div>

              <!-- Setup Form -->
              <form id="mockSetupForm" class="space-y-6 hidden" onsubmit="event.preventDefault(); initiateMockTest();">
                
                <!-- Subject Selection Cards Grid -->
                <div>
                  <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Available Semester Subjects</label>
                  <div id="mockSubjectGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5"></div>
                  <input type="hidden" id="mockSelectedSubject" required>
                </div>

                <!-- Test Parameters Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-5">
                  <div>
                    <x-ui.select id="mockQuestionCount" name="mock_question_count" label="Question Count" :options="['10'=>'10 Questions (Quick Practice ~ 10 mins)', '15'=>'15 Questions (Standard Evaluation ~ 15 mins)', '20'=>'20 Questions (Full Series Prep ~ 20 mins)']" value="15" />
                  </div>
                  <div>
                    <x-ui.select id="mockModuleScope" name="mock_module_scope" label="Syllabus Scope" :options="['all'=>'All Modules (Comprehensive Syllabus)', 'CO1'=>'CO1 Module Focus', 'CO2'=>'CO2 Module Focus', 'CO3'=>'CO3 Module Focus', 'CO4'=>'CO4 Module Focus']" value="all" />
                  </div>
                </div>

                <div class="pt-3 flex justify-end">
                  <button type="submit" id="btnStartMockTest" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                    <i data-lucide="play" class="w-4 h-4"></i>
                    <span>Launch Practice Test</span>
                  </button>
                </div>

              </form>

            </div>
          </section>

          <!-- 2. Active Test Examination Section -->
          <section id="mockExamSection" class="hidden space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
              
              <!-- Test Header with Live Timer -->
              <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                  <h3 id="mockActiveSubjectTitle" class="text-base font-bold text-slate-900">Subject Practice Test</h3>
                  <p id="mockActiveQuestionCounter" class="text-xs text-slate-500 mt-0.5">Question 1 of 15</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-200/80 rounded-xl text-blue-700 font-mono text-sm font-bold shadow-2xs">
                  <i data-lucide="timer" class="w-4 h-4 text-blue-600"></i>
                  <span id="mockTestTimer">15:00</span>
                </div>
              </div>

              <!-- Question Navigator Dots -->
              <div class="flex flex-wrap gap-2 p-3 bg-slate-50 rounded-xl border border-slate-200/60" id="mockQuestionNavigator"></div>

              <!-- Current Question Box -->
              <div id="mockCurrentQuestionBox" class="space-y-4 pt-2">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                  <span class="text-xs font-semibold text-blue-700 uppercase tracking-wider" id="mockQBadge">Question 1</span>
                  <p class="text-sm font-semibold text-slate-900 mt-1" id="mockQText">Question text loading...</p>
                </div>

                <!-- Options List -->
                <div class="space-y-2.5" id="mockOptionsContainer"></div>
              </div>

              <!-- Navigation Controls -->
              <div class="flex items-center justify-between border-t border-slate-100 pt-5">
                <button type="button" onclick="navigateMockPrevQuestion()" id="btnMockPrevQ" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                  Previous
                </button>
                <div class="flex gap-2">
                  <button type="button" onclick="navigateMockNextQuestion()" id="btnMockNextQ" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all">
                    Next Question
                  </button>
                  <button type="button" onclick="submitMockFullTest()" id="btnMockSubmitTest" class="hidden px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm">
                    Submit Test
                  </button>
                </div>
              </div>

            </div>
          </section>

          <!-- 3. Score Report Section -->
          <section id="mockResultSection" class="hidden space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6 text-center">
              
              <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 mx-auto">
                <i data-lucide="award" class="w-8 h-8"></i>
              </div>

              <div>
                <h2 class="text-2xl font-bold text-slate-900">Practice Session Completed!</h2>
                <p class="text-xs text-slate-500 mt-1" id="mockResultSubjectSubtitle">Assessment results and competency breakdown</p>
              </div>

              <!-- Score Pill -->
              <div class="max-w-xs mx-auto p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Final Test Score</span>
                <p class="text-3xl font-bold text-blue-700" id="mockFinalScoreText">0 / 15</p>
                <p class="text-xs font-semibold text-emerald-700" id="mockFinalPercentageText">0% Proficiency</p>
              </div>

              <!-- Detailed Answer Review List -->
              <div class="text-left space-y-3 pt-4 border-t border-slate-100" id="mockDetailedReviewList"></div>

              <div class="pt-4 flex justify-center">
                <button type="button" onclick="resetMockPracticeTest()" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm">
                  Back to Practice Hub
                </button>
              </div>

            </div>
          </section>

        </div>

      </main>
    </div>
  </div>

  <!-- Study Materials Vault Modal -->
  <div id="vlmVaultModal" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2">
          <i data-lucide="folder-archive" class="w-5 h-5 text-blue-600"></i>
          <h3 class="text-base font-bold text-slate-900">Study Materials & Learning Vault</h3>
        </div>
        <button onclick="closeVlmVaultModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><i data-lucide="x" class="w-5 h-5"></i></button>
      </div>
      <div id="vlmVaultContent" class="space-y-3 max-h-96 overflow-y-auto">
        <p class="text-xs text-slate-500">Access course lecture notes, question banks, and pre-class videos uploaded by your faculty.</p>
      </div>
    </div>
  </div>

  <!-- Interactive Dashboard Controller Scripts -->
  <script>
    let academicReportLoaded = false;
    let mentoringLoaded = false;
    let attendanceLoaded = false;
    let mockSubjectsLoaded = false;
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

    function switchPanel(panelId) {
      window.history.replaceState({}, '', '?tab=' + panelId);

      const panels = ['exams', 'marks', 'profile', 'mentoring', 'activity', 'seminar', 'attendance', 'mock_test'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        if (el) {
          if (id === panelId) {
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        }
      });

      const titles = { 
        exams: 'Works To Do', 
        marks: 'Academic Stats & CIE Marks', 
        profile: 'My Student Profile', 
        mentoring: 'Mentoring Diary',
        activity: 'Activity Points Portfolio', 
        seminar: 'My Seminar Details',
        attendance: 'Attendance Review',
        mock_test: 'Practice Test Engine'
      };
      const subtitles = { 
        exams: 'Manage your pending assignments, series examinations and learning schedule.', 
        marks: 'Your semester-wise continuous internal evaluation records.', 
        profile: 'Your verified institutional identity and account settings.',
        mentoring: 'Comprehensive student profile and periodic mentor-mentee interaction records.',
        activity: 'Track and claim your extracurricular points towards graduation.',
        seminar: 'Register presentation topic and view guide evaluations.',
        attendance: 'Real-time daily periods, subject-wise attendance trajectory, and leave records.',
        mock_test: 'Timed syllabus practice assessments and instant competency scoring.'
      };

      if (titles[panelId]) document.getElementById('panelTitle').innerText = titles[panelId];
      if (subtitles[panelId]) document.getElementById('panelSubtitle').innerText = subtitles[panelId];

      if (panelId === 'activity') {
        loadActivityPoints();
      } else if (panelId === 'seminar') {
        loadSeminarRegistration();
      } else if (panelId === 'mentoring') {
        if (!mentoringLoaded) loadStudentMentoringData();
      } else if (panelId === 'attendance') {
        if (!attendanceLoaded) loadStudentAttendanceData();
      } else if (panelId === 'mock_test') {
        if (!mockSubjectsLoaded) loadMockSubjects();
      }

      if (window.initLucide) window.initLucide();
    }

    document.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      const tab = urlParams.get('tab');
      if (tab && ['exams', 'marks', 'profile', 'mentoring', 'activity', 'seminar', 'attendance', 'mock_test'].includes(tab)) {
        switchPanel(tab);
        if (typeof selectSidebarNav === 'function') selectSidebarNav(tab);
      }
      loadStudentTests();
      if (!academicReportLoaded) loadAcademicReport();
      loadPreClassVlmBanner();
    });

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
       document.getElementById('statOverallDone').innerText = currentTaskStats.online_tests_submitted + currentTaskStats.assignments_submitted + currentTaskStats.written_tests_submitted;
    }

    function loadAcademicReport() {
      fetch('/api/student/academic-report')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            academicReportLoaded = true;
            academicData = data;
            const overall = data.overall || {};
            const cgpaVal = parseFloat(overall.cgpa) || 0;
            const activityPointsVal = parseInt(overall.activity_points) || 0;

            document.getElementById('overallCgpa').innerText = cgpaVal > 0 ? cgpaVal.toFixed(2) : '0.00';
            document.getElementById('diplomaClassification').innerText = overall.classification || '--';

            // CGPA gauge
            const cgpaPercent = Math.min(1.0, Math.max(0.0, cgpaVal / 10.0));
            document.getElementById('cgpaGaugeProgress').style.strokeDashoffset = 251.2 - (cgpaPercent * 251.2);

            // Activity Points gauge
            const activityPercent = Math.min(1.0, Math.max(0.0, activityPointsVal / 160.0));
            document.getElementById('activityGaugeProgress').style.strokeDashoffset = 251.2 - (activityPercent * 251.2);
            document.getElementById('overallActivityPoints').innerText = activityPointsVal;

            // Attendance gauge
            const attendance = data.current_sem_attendance || { total_hours: 0, present_hours: 0, percentage: 0 };
            const attendancePct = parseFloat(attendance.percentage) || 0;
            document.getElementById('overallAttendancePct').innerText = attendancePct + '%';
            document.getElementById('attendanceHoursDetail').innerText = `${attendance.present_hours} / ${attendance.total_hours}`;
            document.getElementById('attendanceGaugeProgress').style.strokeDashoffset = 251.2 - ((attendancePct / 100.0) * 251.2);

            document.getElementById('headerSemValue').innerText = 'Semester ' + (overall.current_semester || '1');
            currentActiveSem = overall.current_semester || 1;

            if (data.stats) updateStatsHeader(data.stats, null);
            renderActiveTasks(data.active_tasks || [], data.active_surveys || []);
            renderCgpaChart(data.semesters || []);
            renderSemesterTabs(data.semesters || []);
            renderGodTable(currentActiveSem);
            renderSubjectProgress(data.subject_progress || []);
          }
        })
        .catch(err => console.error('Academic report fetch error:', err));
    }

    function renderSubjectProgress(progressList) {
      const container = document.getElementById('subjectProgressGrid');
      if (!container) return;

      if (!progressList || progressList.length === 0) {
        container.innerHTML = `<div class="col-span-full py-6 text-center text-slate-400 font-medium text-xs">No active semester subject progress logs recorded yet.</div>`;
        return;
      }

      container.innerHTML = progressList.map(item => `
        <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/50 space-y-2">
          <div class="flex items-start justify-between gap-2">
            <div>
              <p class="font-bold text-slate-900 text-xs">${item.subject_code} - ${item.subject_name}</p>
              <p class="text-[11px] text-slate-500">${item.staff_name || 'Faculty Assigned'}</p>
            </div>
            <span class="text-xs font-bold text-blue-700">${item.percentage}%</span>
          </div>
          <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
            <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: ${item.percentage}%"></div>
          </div>
          <div class="flex justify-between text-[10px] text-slate-500 font-medium">
            <span>Sessions: ${item.completed_sessions} taught</span>
            <span>Target: ${item.total_sessions} hrs</span>
          </div>
        </div>
      `).join('');
    }

    function renderActiveTasks(tasks, surveys) {
      const tasksContainer = document.getElementById('studentActiveTasksContainer');
      const assignmentsSection = document.getElementById('assignmentsSection');

      if (tasks && tasks.length > 0) {
        tasksContainer.innerHTML = tasks.map((t, idx) => `
          <div class="p-4 bg-white border border-slate-200 rounded-xl space-y-2 shadow-sm">
            <div class="flex items-center justify-between">
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">${t.type} • ${t.co_tag || 'Module'}</span>
              <span class="text-xs text-slate-500 font-medium font-mono">${t.deadline ? 'Due: ' + new Date(t.deadline).toLocaleDateString() : ''}</span>
            </div>
            <p class="font-bold text-slate-900 text-xs">${t.subject_code} - ${t.subject}</p>
            <p class="text-xs text-slate-600">${t.title || 'Continuous Internal Evaluation Assignment'}</p>
          </div>
        `).join('');
        assignmentsSection.classList.remove('hidden');
      } else {
        tasksContainer.innerHTML = `<div class="py-6 text-center text-slate-400 text-xs font-medium">No pending written assignments.</div>`;
      }
    }

    function renderCgpaChart(semesters) {
      const ctx = document.getElementById('cgpaChart');
      if (!ctx) return;
      if (cgpaChartInstance) cgpaChartInstance.destroy();

      const labels = semesters.map(s => 'S' + s.sem_no);
      const data = semesters.map(s => parseFloat(s.sgpa) || 0);

      cgpaChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels.length ? labels : ['S1'],
          datasets: [{
            data: data.length ? data : [0],
            borderColor: '#2563EB',
            backgroundColor: 'rgba(37, 99, 235, 0.08)',
            borderWidth: 2,
            pointBackgroundColor: '#2563EB',
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            y: { min: 0, max: 10, ticks: { font: { family: 'Poppins', size: 10 } } },
            x: { ticks: { font: { family: 'Poppins', size: 10 } } }
          }
        }
      });
    }

    function renderSemesterTabs(semesters) {
      const container = document.getElementById('semesterTabsContainer');
      if (!container) return;

      container.innerHTML = (semesters || [{ sem_no: 1 }]).map(s => `
        <button type="button" onclick="renderGodTable(${s.sem_no})" class="px-3 py-1 text-xs font-semibold rounded-lg transition-all ${s.sem_no === currentActiveSem ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'}">
          Semester ${s.sem_no}
        </button>
      `).join('');
    }

    function renderGodTable(semNo) {
      currentActiveSem = semNo;
      const container = document.getElementById('academicReportContent');
      if (!container || !academicData) return;

      const semData = (academicData.semesters || []).find(s => s.sem_no === semNo);
      const subjects = semData ? semData.subjects || [] : [];

      if (subjects.length === 0) {
        container.innerHTML = `<div class="p-8 text-center text-slate-400 bg-white border border-slate-200 rounded-2xl text-xs font-medium">No marksheet entries registered for Semester ${semNo}.</div>`;
        return;
      }

      container.innerHTML = `
        <div class="w-full bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 text-xs font-semibold uppercase tracking-wider">
                  <th class="py-3.5 px-4">Subject Code & Name</th>
                  <th class="py-3.5 px-4 text-center">Series 1</th>
                  <th class="py-3.5 px-4 text-center">Series 2</th>
                  <th class="py-3.5 px-4 text-center">Assignment</th>
                  <th class="py-3.5 px-4 text-center">CIE Total</th>
                  <th class="py-3.5 px-4 text-center">Grade</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                ${subjects.map(sub => `
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3.5 px-4">
                      <p class="font-semibold text-slate-900 text-xs">${sub.code} - ${sub.name}</p>
                      <p class="text-[11px] text-slate-500 font-medium">${sub.type || 'Theory'}</p>
                    </td>
                    <td class="py-3.5 px-4 text-center font-mono text-xs font-semibold">${sub.series_1 ?? '-'}</td>
                    <td class="py-3.5 px-4 text-center font-mono text-xs font-semibold">${sub.series_2 ?? '-'}</td>
                    <td class="py-3.5 px-4 text-center font-mono text-xs font-semibold">${sub.assignment ?? '-'}</td>
                    <td class="py-3.5 px-4 text-center font-bold text-blue-700 text-xs">${sub.cie_total ?? '-'}</td>
                    <td class="py-3.5 px-4 text-center">
                      <span class="px-2.5 py-0.5 rounded-full text-xs font-bold ${sub.grade === 'S' || sub.grade === 'A' ? 'bg-emerald-50 text-emerald-800' : (sub.grade === 'F' ? 'bg-rose-50 text-rose-800' : 'bg-blue-50 text-blue-800')}">${sub.grade || '--'}</span>
                    </td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        </div>
      `;
    }

    function loadStudentTests() {
      fetch('/student/tests/active')
        .then(res => res.json())
        .then(data => {
          const list = document.getElementById('studentActiveTestsList');
          const section = document.getElementById('mcqTestsSection');
          if (data && data.tests && data.tests.length > 0) {
            list.innerHTML = data.tests.map(t => `
              <div class="p-4 bg-white border border-slate-200 rounded-xl space-y-2 shadow-sm">
                <div class="flex items-center justify-between">
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/60">Live Assessment</span>
                  <span class="text-xs text-slate-500 font-medium">${t.duration} mins</span>
                </div>
                <p class="font-bold text-slate-900 text-xs">${t.title}</p>
                <a href="/student/test/${t.id}" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700">Start Practice <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
              </div>
            `).join('');
            section.classList.remove('hidden');
          } else {
            list.innerHTML = `<div class="py-6 text-center text-slate-400 text-xs font-medium">No live MCQ tests currently scheduled.</div>`;
          }
          if (window.initLucide) window.initLucide();
        })
        .catch(() => {});
    }

    function loadActivityPoints() {
      fetch('/student/activity-points')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            document.getElementById('verifiedActivityTotal').innerText = data.total || 0;
            const tb = document.getElementById('activityClaimsTableBody');
            if (tb && data.claims) {
              tb.innerHTML = data.claims.map(c => `
                <tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3 px-4 text-xs font-medium text-slate-600">${c.date || '-'}</td>
                  <td class="py-3 px-4 text-xs font-semibold text-slate-900">${c.segment}</td>
                  <td class="py-3 px-4 text-xs text-slate-700">${c.name}</td>
                  <td class="py-3 px-4 text-xs text-slate-600">${c.level}</td>
                  <td class="py-3 px-4 text-xs text-slate-500">${c.evidence || '-'}</td>
                  <td class="py-3 px-4 text-center text-xs font-semibold">${c.claimed}</td>
                  <td class="py-3 px-4 text-center text-xs font-bold text-blue-700">${c.awarded || 0}</td>
                  <td class="py-3 px-4 text-right">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold ${c.status === 'Approved' ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800'}">${c.status}</span>
                  </td>
                </tr>
              `).join('');
            }
          }
        })
        .catch(() => {});
    }

    function loadSeminarRegistration() {
      fetch('/student/seminar/details')
        .then(res => res.json())
        .then(data => {
          if (data && data.status === 'SUCCESS' && data.registered) {
            document.getElementById('seminarStatusBanner').classList.remove('hidden');
            document.getElementById('semStatusTopic').innerText = data.topic || '-';
            document.getElementById('semStatusGuide').innerText = data.guide_name || '-';
            document.getElementById('semStatusDate').innerText = data.date || '-';
            document.getElementById('semStatusScore').innerText = (data.score || '-') + ' / 75';
            document.getElementById('seminarFormCard').classList.add('hidden');
          }
        })
        .catch(() => {});
    }

    // ==========================================
    // MENTORING DIARY INTERACTIVE CONTROLLER
    // ==========================================
    function switchStudentMentoringTab(tabId) {
      document.querySelectorAll('.smd-content-pane').forEach(el => el.classList.add('hidden'));
      document.querySelectorAll('.smd-tab').forEach(el => {
        el.className = "smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center";
      });

      const targetPane = document.getElementById(tabId);
      const targetBtn = document.getElementById('tabBtn_' + tabId);
      if (targetPane) targetPane.classList.remove('hidden');
      if (targetBtn) targetBtn.className = "smd-tab py-2 px-2.5 text-xs font-semibold rounded-xl bg-blue-600 text-white shadow-sm transition-all text-center";
      if (window.initLucide) window.initLucide();
    }

    function loadStudentMentoringData() {
      fetch('/api/student/mentoring/data')
        .then(res => res.json())
        .then(data => {
          if (data && data.status === 'SUCCESS') {
            mentoringLoaded = true;
            
            // Profile & Socio-economic
            if (data.profile) {
              document.getElementById('smd_annual_income').value = data.profile.annual_income || '';
              document.getElementById('smd_residential_status').value = data.profile.residential_status || 'Day Scholar';
              document.getElementById('smd_scholarships').value = data.profile.scholarships || '';
              document.getElementById('smd_fee_waiver').checked = data.profile.is_fee_waiver == 1;
              document.getElementById('smd_guardian_name').value = data.profile.guardian_name || '';
              document.getElementById('smd_guardian_relationship').value = data.profile.guardian_relationship || '';
              document.getElementById('smd_guardian_mobile').value = data.profile.guardian_mobile || '';
              document.getElementById('smd_guardian_address').value = data.profile.guardian_address || '';
            }

            // Family members
            const fList = document.getElementById('smdFamilyList');
            if (fList && data.family) {
              if (data.family.length === 0) {
                fList.innerHTML = `<tr><td colspan="6" class="p-6 text-center text-slate-400">No family members registered. Click "+ Add Member" to register.</td></tr>`;
              } else {
                fList.innerHTML = data.family.map(f => `
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3 px-4 font-semibold text-slate-900 text-xs">${f.name}</td>
                    <td class="py-3 px-4 text-slate-700 text-xs">${f.relationship}</td>
                    <td class="py-3 px-4 text-slate-600 text-xs">${f.education || '-'}</td>
                    <td class="py-3 px-4 text-slate-600 text-xs">${f.occupation || '-'}</td>
                    <td class="py-3 px-4 font-mono text-slate-600 text-xs">${f.contact_no || '-'}</td>
                    <td class="py-3 px-4 text-center">
                      <button type="button" onclick="deleteFamilyRow(this, ${f.id})" class="text-rose-600 hover:text-rose-700 text-xs font-semibold">Delete</button>
                    </td>
                  </tr>
                `).join('');
              }
            }

            // Prior Qualifications
            const eList = document.getElementById('smdEducationList');
            if (eList && data.education) {
              if (data.education.length === 0) {
                eList.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-slate-400">No prior education records found. Click "+ Add Qualification" to register.</td></tr>`;
              } else {
                eList.innerHTML = data.education.map(e => `
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3 px-4 font-semibold text-slate-900 text-xs">${e.course}</td>
                    <td class="py-3 px-4 text-slate-700 text-xs">${e.institution}</td>
                    <td class="py-3 px-4 text-slate-600 text-xs">${e.year_of_completion}</td>
                    <td class="py-3 px-4 text-slate-900 font-semibold text-xs">${e.total_percentage}%</td>
                    <td class="py-3 px-4 text-center">
                      <button type="button" onclick="deleteEducationRow(this, ${e.id})" class="text-rose-600 hover:text-rose-700 text-xs font-semibold">Delete</button>
                    </td>
                  </tr>
                `).join('');
              }
            }

            // Extracurricular
            const exList = document.getElementById('smdExtraList');
            if (exList && data.extracurricular) {
              if (data.extracurricular.length === 0) {
                exList.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-slate-400">No extracurricular logs.</td></tr>`;
              } else {
                exList.innerHTML = data.extracurricular.map(ex => `
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3 px-4 font-semibold text-slate-900 text-xs">${ex.activity_name || ex.name}</td>
                    <td class="py-3 px-4 text-slate-600 text-xs">${ex.level}</td>
                    <td class="py-3 px-4 text-slate-700 text-xs">${ex.prize || 'Participated'}</td>
                    <td class="py-3 px-4 text-center text-xs font-bold text-blue-700">${ex.points_awarded || 0}</td>
                    <td class="py-3 px-4 text-right">
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold ${ex.status === 'Verified' || ex.status === 'Approved' ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800'}">${ex.status}</span>
                    </td>
                  </tr>
                `).join('');
              }
            }

            // Mentor Meetings
            const mList = document.getElementById('smdMeetingsList');
            if (mList && data.meetings) {
              if (data.meetings.length === 0) {
                mList.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-400">No mentor meetings recorded yet.</td></tr>`;
              } else {
                mList.innerHTML = data.meetings.map(m => `
                  <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-3 px-4 text-xs font-semibold text-slate-900">${m.meeting_date || '-'}</td>
                    <td class="py-3 px-4 text-xs text-slate-700">${m.discussion_points || '-'}</td>
                    <td class="py-3 px-4 text-xs text-slate-600">${m.mentor_remarks || '-'}</td>
                    <td class="py-3 px-4 text-xs text-blue-700 font-medium">${m.action_taken || '-'}</td>
                  </tr>
                `).join('');
              }
            }
          }
        })
        .catch(err => console.error('Mentoring data fetch error:', err));
    }

    function saveStudentMentoringData() {
      const payload = {
        annual_income: document.getElementById('smd_annual_income').value,
        residential_status: document.getElementById('smd_residential_status').value,
        scholarships: document.getElementById('smd_scholarships').value,
        is_fee_waiver: document.getElementById('smd_fee_waiver').checked ? 1 : 0,
        guardian_name: document.getElementById('smd_guardian_name').value,
        guardian_relationship: document.getElementById('smd_guardian_relationship').value,
        guardian_mobile: document.getElementById('smd_guardian_mobile').value,
        guardian_address: document.getElementById('smd_guardian_address').value
      };

      fetch('/api/student/mentoring/save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(d => {
        alert(d.message || "Mentoring profile details saved successfully.");
      })
      .catch(() => alert("Error saving mentoring details."));
    }

    function downloadMentoringPdf() {
      window.open('/student/mentoring/pdf', '_blank');
    }

    function loadPreClassVlmBanner() {
      fetch('/api/student/pre-class-prep-alert')
        .then(res => res.json())
        .then(data => {
          if (data && data.active) {
            document.getElementById('vlmAlertTitle').innerText = data.title || 'Evening Study Materials Available';
            document.getElementById('vlmAlertInstruction').innerText = data.instruction || 'Review lesson objectives before tomorrow morning classroom session.';
            document.getElementById('vlmAlertTargetDate').innerText = data.target_date || '';
            document.getElementById('vlmPreClassAlertBanner').classList.remove('hidden');
          }
        })
        .catch(() => {});
    }

    function openVlmVaultModal() {
      document.getElementById('vlmVaultModal').classList.remove('hidden');
      if (window.initLucide) window.initLucide();
    }
    function closeVlmVaultModal() {
      document.getElementById('vlmVaultModal').classList.add('hidden');
    }

    function changePassword() {
      const oldPwd = document.getElementById('oldPwd').value;
      const newPwd = document.getElementById('newPwd').value;
      const alertEl = document.getElementById('pwdAlert');
      if (!oldPwd || !newPwd) {
        alertEl.className = "p-3 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200/60 block";
        alertEl.innerText = "Please provide both old and new password.";
        alertEl.classList.remove('hidden');
        return;
      }
      fetch('/student/update-password', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ old_password: oldPwd, new_password: newPwd })
      })
      .then(res => res.json())
      .then(d => {
        alertEl.className = "p-3 rounded-xl text-xs font-semibold " + (d.status === 'SUCCESS' ? "bg-emerald-50 text-emerald-800 border border-emerald-200/60 block" : "bg-rose-50 text-rose-800 border border-rose-200/60 block");
        alertEl.innerText = d.message || "Password updated successfully.";
        alertEl.classList.remove('hidden');
      });
    }

    function updateSbteRegNo() {
      const val = document.getElementById('sbteRegNoInput').value.trim();
      const alertEl = document.getElementById('sbteAlert');
      if (!val) return;
      fetch('/student/update-sbte-reg-no', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ sbte_reg_no: val })
      })
      .then(res => res.json())
      .then(d => {
        alertEl.className = "p-3 rounded-xl text-xs font-semibold " + (d.status === 'SUCCESS' ? "bg-emerald-50 text-emerald-800 border border-emerald-200/60 block" : "bg-rose-50 text-rose-800 border border-rose-200/60 block");
        alertEl.innerText = d.message || "SBTE Register Number saved.";
        alertEl.classList.remove('hidden');
      });
    }

    function handlePhotoUpload(e) {
      const file = e.target.files[0];
      if (!file) return;
      const statusEl = document.getElementById('photoUploadStatus');
      statusEl.className = "text-xs font-semibold text-blue-600 block";
      statusEl.innerText = "Uploading photo...";
      statusEl.classList.remove('hidden');

      const fd = new FormData();
      fd.append('photo', file);
      fetch('/student/upload-photo', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: fd
      })
      .then(res => res.json())
      .then(d => {
        if (d.status === 'SUCCESS') {
          statusEl.className = "text-xs font-semibold text-emerald-600 block";
          statusEl.innerText = "Profile photo updated.";
          if (d.photo_url) {
            const img = document.getElementById('studentProfileImg');
            if (img) img.src = d.photo_url;
          }
        } else {
          statusEl.className = "text-xs font-semibold text-rose-600 block";
          statusEl.innerText = d.message || "Failed to upload photo.";
        }
      });
    }

    function submitActivityClaim(e) {
      e.preventDefault();
      const form = document.getElementById('activityClaimForm');
      const fd = new FormData(form);
      fetch('/student/activity-points/submit', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: fd
      })
      .then(res => res.json())
      .then(d => {
        alert(d.message || "Activity claim submitted to faculty tutor.");
        form.reset();
        loadActivityPoints();
      });
    }

    function submitSeminarRegistration(e) {
      e.preventDefault();
      const topic = document.getElementById('semRegTopic').value;
      const date = document.getElementById('semRegDate').value;
      const guide = document.getElementById('semRegGuide').value;

      fetch('/student/seminar/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ topic, presentation_date: date, guide_id: guide })
      })
      .then(res => res.json())
      .then(d => {
        const toast = document.getElementById('seminarToast');
        toast.className = "p-3.5 rounded-xl text-xs font-semibold " + (d.status === 'SUCCESS' ? "bg-emerald-50 text-emerald-800 border border-emerald-200/60 block" : "bg-rose-50 text-rose-800 border border-rose-200/60 block");
        toast.innerText = d.message || "Seminar details registered.";
        toast.classList.remove('hidden');
        loadSeminarRegistration();
      });
    }

    // =========================================================================
    // ATTENDANCE REVIEW CONTROLLER
    // =========================================================================
    function loadStudentAttendanceData() {
      fetch('/api/student/attendance/data')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            attendanceLoaded = true;
            document.getElementById('attendanceLoader').classList.add('hidden');
            document.getElementById('attendanceContent').classList.remove('hidden');

            const pct = data.overall_percentage || 100;
            document.getElementById('attOverallPctText').innerText = pct + '%';
            
            const circle = document.getElementById('attGaugeCircle');
            const offset = 251.2 - ((pct / 100) * 251.2);
            circle.style.strokeDashoffset = offset;
            circle.setAttribute('stroke', pct >= 75 ? '#10B981' : (pct >= 65 ? '#F59E0B' : '#EF4444'));

            const badge = document.getElementById('attEligibilityBadge');
            badge.innerText = pct >= 75 ? 'Satisfactory & Eligible' : 'Shortage Alert (Condonation Required)';
            badge.className = 'font-bold ' + (pct >= 75 ? 'text-emerald-700' : 'text-rose-700');

            document.getElementById('attTotalConducted').innerText = (data.total_conducted || 0) + ' Hours';
            document.getElementById('attTotalAttended').innerText = (data.total_attended || 0) + ' Hours';
            document.getElementById('attTotalAbsent').innerText = Math.max(0, (data.total_conducted || 0) - (data.total_attended || 0)) + ' Hours';

            if (data.classroom) document.getElementById('attClassroomId').innerText = data.classroom.classroom_id || '-';
            if (data.tutor) {
              document.getElementById('attTutorName').innerText = data.tutor.name || 'Department Faculty Assigned';
              if (data.tutor.mobile_no) document.getElementById('attTutorContact').innerText = 'Contact: ' + data.tutor.mobile_no;
            }

            // Render hourly grid
            const hourlyGrid = document.getElementById('attHourlyGrid');
            if (data.hourly_status && data.hourly_status.length > 0) {
              hourlyGrid.innerHTML = data.hourly_status.map(p => `
                <div class="p-3.5 rounded-xl border ${p.status === 'Present' ? 'bg-emerald-50/50 border-emerald-200/80' : (p.status === 'Absent' ? 'bg-rose-50/50 border-rose-200/80' : 'bg-slate-50 border-slate-200/80')} flex flex-col justify-between space-y-2">
                  <div>
                    <div class="flex items-center justify-between">
                      <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        ${p.period === 7 ? 'Hour 7' : 'Hour ' + p.period}
                      </span>
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${p.status === 'Present' ? 'bg-emerald-100 text-emerald-800' : (p.status === 'Absent' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-600')}">
                        ${p.status}
                      </span>
                    </div>
                    <p class="text-xs font-bold text-slate-900 mt-2 line-clamp-1" title="${p.subject_name}">
                      ${p.subject_name}
                    </p>
                    <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1" title="${p.topic}">
                      ${p.topic || 'Session'}
                    </p>
                  </div>
                  <div class="text-[10px] font-mono text-slate-400 border-t border-slate-200/60 pt-2">
                    ${p.time_slot}
                  </div>
                </div>
              `).join('');
            }

            // Render subject stats
            const subTbody = document.getElementById('attSubjectStatsList');
            if (data.subject_stats && data.subject_stats.length > 0) {
              subTbody.innerHTML = data.subject_stats.map(sub => `
                <tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3.5 px-4">
                    <p class="font-semibold text-slate-900 text-xs">${sub.subject_code} - ${sub.subject_name}</p>
                  </td>
                  <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-700">${sub.conducted}</td>
                  <td class="py-3.5 px-4 text-center font-mono font-bold text-blue-700">${sub.attended}</td>
                  <td class="py-3.5 px-4 text-center font-bold font-mono ${sub.percentage >= 75 ? 'text-emerald-700' : 'text-rose-600'}">
                    ${sub.percentage}%
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${sub.percentage >= 75 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/60' : 'bg-rose-50 text-rose-800 border border-rose-200/60'}">
                      ${sub.percentage >= 75 ? 'Eligible' : 'Shortage (<75%)'}
                    </span>
                  </td>
                </tr>
              `).join('');
            } else {
              subTbody.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-400">No subject attendance logs recorded.</td></tr>';
            }

            // Render leave records
            const leaveTbody = document.getElementById('attLeaveRecordsList');
            if (data.leave_records && data.leave_records.length > 0) {
              leaveTbody.innerHTML = data.leave_records.map(leave => `
                <tr class="hover:bg-slate-50/70 transition-colors">
                  <td class="py-3.5 px-4 font-mono font-semibold text-slate-900">${leave.leave_date}</td>
                  <td class="py-3.5 px-4 text-slate-700">${leave.reason || 'Medical / Personal Leave'}</td>
                  <td class="py-3.5 px-4 text-slate-600">${leave.period_range || 'Full Day'}</td>
                  <td class="py-3.5 px-4 text-right">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${leave.status === 'Approved' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/60' : 'bg-amber-50 text-amber-800 border border-amber-200/60'}">
                      ${leave.status || 'Verified by Tutor'}
                    </span>
                  </td>
                </tr>
              `).join('');
            } else {
              leaveTbody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-slate-400">No formal leave requests recorded for this semester.</td></tr>';
            }

            if (window.initLucide) window.initLucide();
          }
        })
        .catch(() => {
          document.getElementById('attendanceLoader').innerHTML = '<p class="text-xs text-rose-600 font-semibold">Error loading attendance logs.</p>';
        });
    }

    // =========================================================================
    // PRACTICE TEST ENGINE CONTROLLER
    // =========================================================================
    let mockQuestions = [];
    let mockStudentAnswers = {};
    let mockCurrentIdx = 0;
    let mockTimerInterval = null;
    let mockRemainingSeconds = 900;

    function loadMockSubjects() {
      fetch('/api/student/mock-test/subjects')
        .then(res => res.json())
        .then(d => {
          mockSubjectsLoaded = true;
          document.getElementById('mockSetupLoader').classList.add('hidden');
          document.getElementById('mockSetupForm').classList.remove('hidden');

          const grid = document.getElementById('mockSubjectGrid');
          const subjects = (d.data && d.data.subjects) ? d.data.subjects : [];
          if (subjects.length > 0) {
            grid.innerHTML = subjects.map(s => `
              <div onclick="selectMockSubjectCard('${s.subject_code}', this)" class="mock-sub-card p-4 rounded-xl border border-slate-200 hover:border-blue-500 cursor-pointer transition-all bg-white hover:bg-blue-50/30 flex items-start justify-between">
                <div>
                  <p class="text-xs font-bold text-slate-900">${s.subject_code}</p>
                  <p class="text-xs text-slate-600 mt-0.5 line-clamp-1">${s.subject_name}</p>
                  <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-[10px] font-semibold ${s.already_attempted_today ? 'bg-amber-50 text-amber-800' : 'bg-emerald-50 text-emerald-800'}">
                    ${s.already_attempted_today ? 'Attempted Today' : 'Available'}
                  </span>
                </div>
                <i data-lucide="circle" class="w-4 h-4 text-slate-300 mock-card-check"></i>
              </div>
            `).join('');
            if (window.initLucide) window.initLucide();
          } else {
            grid.innerHTML = '<div class="col-span-full py-8 text-center text-slate-400 text-xs font-medium">No registered subjects available for practice test.</div>';
          }
        })
        .catch(() => {
          document.getElementById('mockSetupLoader').innerHTML = '<p class="text-xs text-rose-600 font-semibold">Error loading practice subjects.</p>';
        });
    }

    function selectMockSubjectCard(code, el) {
      document.querySelectorAll('.mock-sub-card').forEach(c => {
        c.classList.remove('border-blue-600', 'bg-blue-50/50');
        const ic = c.querySelector('.mock-card-check');
        if (ic) ic.setAttribute('data-lucide', 'circle');
      });
      el.classList.add('border-blue-600', 'bg-blue-50/50');
      const ic = el.querySelector('.mock-card-check');
      if (ic) ic.setAttribute('data-lucide', 'check-circle-2');
      document.getElementById('mockSelectedSubject').value = code;
      if (window.initLucide) window.initLucide();
    }

    function initiateMockTest() {
      const subject = document.getElementById('mockSelectedSubject').value;
      const count = parseInt(document.getElementById('mockQuestionCount').value) || 15;
      const coTag = document.getElementById('mockModuleScope').value || 'all';

      if (!subject) {
        alert('Please select a subject to start practice test.');
        return;
      }

      const btn = document.getElementById('btnStartMockTest');
      btn.disabled = true;
      btn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Generating test...';

      fetch('/api/student/mock-test/start', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ subject_code: subject, co_tag: coTag === 'all' ? 'All' : coTag, num_questions: count })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="play" class="w-4 h-4"></i><span>Launch Practice Test</span>';

        if (data.status === 'SUCCESS' && data.data && data.data.questions && data.data.questions.length > 0) {
          mockQuestions = data.data.questions;
          mockStudentAnswers = {};
          mockCurrentIdx = 0;
          mockRemainingSeconds = count * 60;

          document.getElementById('mockSetupSection').classList.add('hidden');
          document.getElementById('mockExamSection').classList.remove('hidden');
          document.getElementById('mockActiveSubjectTitle').innerText = subject + ' Practice Assessment';

          renderMockQuestionNavigator();
          displayMockCurrentQuestion();
          startMockTestTimer();
          if (window.initLucide) window.initLucide();
        } else {
          alert(data.message || 'Unable to generate practice questions.');
        }
      })
      .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<span>Launch Practice Test</span>';
        alert('Server error generating test.');
      });
    }

    function renderMockQuestionNavigator() {
      const container = document.getElementById('mockQuestionNavigator');
      container.innerHTML = mockQuestions.map((_, i) => `
        <button type="button" onclick="jumpToMockQuestion(${i})" id="mockNavDot-${i}" class="w-8 h-8 rounded-lg text-xs font-semibold border transition-all ${i === 0 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'}">
          ${i + 1}
        </button>
      `).join('');
    }

    function displayMockCurrentQuestion() {
      const q = mockQuestions[mockCurrentIdx];
      if (!q) return;

      document.getElementById('mockActiveQuestionCounter').innerText = `Question ${mockCurrentIdx + 1} of ${mockQuestions.length}`;
      document.getElementById('mockQBadge').innerText = `Question ${mockCurrentIdx + 1} (${q.co_tag || 'Syllabus Topic'})`;
      document.getElementById('mockQText').innerText = q.question_text || q.question;

      const optsBox = document.getElementById('mockOptionsContainer');
      const selectedOpt = mockStudentAnswers[mockCurrentIdx];
      const options = q.options || [];

      optsBox.innerHTML = options.map((opt, optIdx) => `
        <label onclick="recordMockAnswer(${mockCurrentIdx}, '${opt}')" class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all ${selectedOpt === opt ? 'bg-blue-50 border-blue-600 text-blue-900 font-semibold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'}">
          <input type="radio" name="mockOptRadio" value="${opt}" ${selectedOpt === opt ? 'checked' : ''} class="w-4 h-4 text-blue-600 border-slate-300">
          <span class="text-xs">${opt}</span>
        </label>
      `).join('');

      document.getElementById('btnMockPrevQ').disabled = (mockCurrentIdx === 0);
      if (mockCurrentIdx === mockQuestions.length - 1) {
        document.getElementById('btnMockNextQ').classList.add('hidden');
        document.getElementById('btnMockSubmitTest').classList.remove('hidden');
      } else {
        document.getElementById('btnMockNextQ').classList.remove('hidden');
        document.getElementById('btnMockSubmitTest').classList.add('hidden');
      }

      mockQuestions.forEach((_, i) => {
        const dot = document.getElementById(`mockNavDot-${i}`);
        if (!dot) return;
        if (i === mockCurrentIdx) {
          dot.className = "w-8 h-8 rounded-lg text-xs font-semibold border bg-blue-600 text-white border-blue-600 shadow-xs";
        } else if (mockStudentAnswers[i] !== undefined) {
          dot.className = "w-8 h-8 rounded-lg text-xs font-semibold border bg-emerald-50 text-emerald-800 border-emerald-300";
        } else {
          dot.className = "w-8 h-8 rounded-lg text-xs font-semibold border bg-white text-slate-700 border-slate-200 hover:bg-slate-100";
        }
      });
    }

    function recordMockAnswer(qIdx, opt) {
      mockStudentAnswers[qIdx] = opt;
      displayMockCurrentQuestion();
    }

    function jumpToMockQuestion(idx) {
      mockCurrentIdx = idx;
      displayMockCurrentQuestion();
    }

    function navigateMockNextQuestion() {
      if (mockCurrentIdx < mockQuestions.length - 1) {
        mockCurrentIdx++;
        displayMockCurrentQuestion();
      }
    }

    function navigateMockPrevQuestion() {
      if (mockCurrentIdx > 0) {
        mockCurrentIdx--;
        displayMockCurrentQuestion();
      }
    }

    function startMockTestTimer() {
      clearInterval(mockTimerInterval);
      mockTimerInterval = setInterval(() => {
        mockRemainingSeconds--;
        const mins = Math.floor(mockRemainingSeconds / 60);
        const secs = mockRemainingSeconds % 60;
        document.getElementById('mockTestTimer').innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        if (mockRemainingSeconds <= 0) {
          clearInterval(mockTimerInterval);
          alert('Time up! Submitting practice test automatically.');
          submitMockFullTest();
        }
      }, 1000);
    }

    function submitMockFullTest() {
      if (!confirm('Are you sure you want to submit your practice test?')) return;
      clearInterval(mockTimerInterval);

      let correctCount = 0;
      mockQuestions.forEach((q, idx) => {
        if (mockStudentAnswers[idx] && mockStudentAnswers[idx].trim().toLowerCase() === (q.correct_answer || '').trim().toLowerCase()) {
          correctCount++;
        }
      });

      document.getElementById('mockExamSection').classList.add('hidden');
      document.getElementById('mockResultSection').classList.remove('hidden');

      const total = mockQuestions.length;
      const pct = Math.round((correctCount / total) * 100);

      document.getElementById('mockFinalScoreText').innerText = `${correctCount} / ${total}`;
      document.getElementById('mockFinalPercentageText').innerText = `${pct}% Proficiency`;

      const reviewBox = document.getElementById('mockDetailedReviewList');
      reviewBox.innerHTML = mockQuestions.map((q, idx) => {
        const ans = mockStudentAnswers[idx];
        const isCorrect = ans && (ans.trim().toLowerCase() === (q.correct_answer || '').trim().toLowerCase());
        return `
          <div class="p-4 rounded-xl border ${isCorrect ? 'bg-emerald-50/40 border-emerald-200' : 'bg-rose-50/40 border-rose-200'} space-y-2">
            <p class="text-xs font-bold text-slate-900">Q${idx + 1}: ${q.question_text || q.question}</p>
            <p class="text-[11px] text-slate-600">Your Answer: <strong class="${isCorrect ? 'text-emerald-700' : 'text-rose-700'}">${ans || 'Not Answered'}</strong></p>
            ${!isCorrect ? `<p class="text-[11px] text-emerald-700">Correct Answer: <strong>${q.correct_answer}</strong></p>` : ''}
          </div>
        `;
      }).join('');
      if (window.initLucide) window.initLucide();
    }

    function resetMockPracticeTest() {
      document.getElementById('mockResultSection').classList.add('hidden');
      document.getElementById('mockSetupSection').classList.remove('hidden');
      loadMockSubjects();
    }
  </script>
</body>
</html>
