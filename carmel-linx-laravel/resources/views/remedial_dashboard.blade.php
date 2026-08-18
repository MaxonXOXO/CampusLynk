<x-layouts.faculty-shell title="Remedial Sessions" subtitle="Student coaching and performance analysis." activeNav="remedial">

  <div class="space-y-6">

    <!-- Dashboard Header & Action Bar -->
    <div class="flex items-center justify-between bg-white border border-slate-200 p-2.5 rounded-2xl shadow-2xs">
      <div class="flex items-center gap-2">
        <button onclick="switchTab('roomsList')" id="tab_roomsList" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 transition-premium cursor-pointer">
          Active Rooms
        </button>
      </div>
      <div>
        <button onclick="switchTab('createRoom')" id="tab_createRoom" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white shadow-sm shadow-blue-500/20 flex items-center gap-1.5 transition-premium cursor-pointer">
          <span class="material-symbols-rounded text-base">add_circle</span>
          <span>Create New Room</span>
        </button>
      </div>
    </div>

    <!-- Active Rooms Panel -->
    <div id="panel_roomsList" class="space-y-6">
      <div id="roomsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div class="col-span-full py-12 text-center text-slate-400 font-semibold text-sm">
          <div class="w-8 h-8 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
          <span>Loading remedial coaching rooms...</span>
        </div>
      </div>
    </div>

    <!-- Create Room Panel (Desktop 12-Column Responsive Workspace) -->
    <div id="panel_createRoom" class="hidden space-y-6">
      
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- LEFT COLUMN: Configuration & Steps (5 Columns) -->
        <div class="lg:col-span-5 space-y-6">

          <!-- Step 1: Select Subject Card -->
          <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200">
                  <span class="material-symbols-rounded text-lg">school</span>
                </div>
                <div>
                  <h2 class="font-bold text-sm text-slate-900 leading-tight">Subject Selection</h2>
                  <p class="text-xs text-slate-500">Pick class to analyze performance</p>
                </div>
              </div>
              <span class="text-xs font-bold text-blue-600 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-full">Step 1</span>
            </div>

            <div class="space-y-3">
              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Class Subject / Batch</label>
                <select id="subjectSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-premium cursor-pointer shadow-2xs font-medium">
                  <option value="">Select a Subject...</option>
                </select>
              </div>

              <button onclick="fetchStudentPerformance()" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white rounded-xl font-bold text-xs py-3 px-6 transition-premium shadow-sm shadow-blue-500/20 flex items-center justify-center gap-2 cursor-pointer">
                <span class="material-symbols-rounded text-base">analytics</span>
                <span>Analyze Student Performance</span>
              </button>
            </div>
          </div>

          <!-- Step 2: Threshold & Room Provision Controls (Shown after analysis) -->
          <div id="performanceConfigCard" class="hidden bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200">
                  <span class="material-symbols-rounded text-lg">tune</span>
                </div>
                <div>
                  <h2 class="font-bold text-sm text-slate-900 leading-tight">Intervention Criteria</h2>
                  <p class="text-xs text-slate-500">Filter students by score threshold</p>
                </div>
              </div>
              <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">Step 2</span>
            </div>

            <div class="space-y-4">
              <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 space-y-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Auto-Select Threshold Marks</label>
                <div class="flex items-center gap-2">
                  <input type="number" id="thresholdMark" value="20" class="flex-1 bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm text-slate-900 outline-none focus:border-blue-500 text-center font-bold shadow-2xs">
                  <button onclick="applyThreshold()" class="bg-slate-200 hover:bg-slate-300 text-slate-800 rounded-xl px-4 py-2 text-xs font-bold transition-premium cursor-pointer shadow-2xs">
                    Apply Filter
                  </button>
                </div>
                <p class="text-[11px] text-slate-500">Students scoring below this total will be automatically checked for remedial intervention.</p>
              </div>

              <!-- Provision CTA Button -->
              <button onclick="provisionRoom()" class="w-full bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white rounded-xl font-bold text-sm py-3.5 px-6 transition-premium shadow-sm shadow-emerald-500/20 flex items-center justify-center gap-2 cursor-pointer">
                <span class="material-symbols-rounded text-lg">add_task</span>
                <span id="btnProvisionText">Provision Remedial Room</span>
              </button>
            </div>
          </div>

        </div>

        <!-- RIGHT COLUMN: Student Candidate Roster (7 Columns) -->
        <div class="lg:col-span-7 space-y-6">

          <!-- Performance Roster Section (Loaded after analyze) -->
          <div id="performanceSection" class="hidden bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
              <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200">
                  <span class="material-symbols-rounded text-lg">group</span>
                </div>
                <div>
                  <h2 class="font-bold text-sm text-slate-900 leading-tight">Candidate Student Roster</h2>
                  <p class="text-xs text-slate-500" id="selectedStudentSummary">Review and select students for this room</p>
                </div>
              </div>
              <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full" id="selectedCountBadge">
                0 Selected
              </span>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white max-h-[500px] overflow-y-auto">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold text-xs sticky top-0 z-10">
                    <th class="p-3.5 w-12 text-center">
                      <input type="checkbox" id="selectAllStudents" onchange="toggleAllStudents()" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                    </th>
                    <th class="p-3.5">Reg No</th>
                    <th class="p-3.5">Student Name</th>
                    <th class="p-3.5 text-right">Total Marks</th>
                    <th class="p-3.5 text-center w-24">Status</th>
                  </tr>
                </thead>
                <tbody id="performanceTableBody" class="divide-y divide-slate-100">
                  <!-- Rendered via JS -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Empty Prompt Before Analyze -->
          <div id="performanceEmptyPrompt" class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-xs space-y-3">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto border border-blue-200">
              <span class="material-symbols-rounded text-3xl">analytics</span>
            </div>
            <h3 class="font-bold text-slate-900 text-base">Select a Subject to Analyze</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">Choose an assigned subject from the left panel and click "Analyze Student Performance" to query internal assessment records and identify students needing support.</p>
          </div>

        </div>

      </div>

    </div>

  </div>

  <!-- View Room Modal -->
  <div id="roomModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 md:p-6 overflow-hidden">
    <div class="bg-white border border-slate-200 w-full max-w-6xl h-full max-h-[90vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden transition-all duration-200">
      
      <!-- Modal Header -->
      <div class="px-6 py-4 bg-white border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0">
        <div class="flex items-start gap-3">
          <div class="p-2.5 bg-blue-50 text-blue-700 rounded-xl border border-blue-200 shrink-0">
            <span class="material-symbols-rounded text-xl block">school</span>
          </div>
          <div>
            <h3 id="modalRoomTitle" class="font-bold text-base text-slate-900 tracking-tight leading-tight">Remedial Class Room</h3>
            <div id="modalRoomSub" class="mt-1"></div>
          </div>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 self-start md:self-center">
          <!-- Room Status Selector -->
          <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 shadow-2xs">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Status:</span>
            <select id="modalRoomStatus" onchange="updateRoomStatus()" class="bg-transparent text-xs font-bold text-emerald-700 focus:outline-none border-none cursor-pointer">
              <option value="active" class="text-emerald-700">Active</option>
              <option value="archived" class="text-amber-700">Archived</option>
            </select>
          </div>

          <!-- Delete Room Button -->
          <button id="btnDeleteRoom" onclick="confirmDeleteRoom(this)" class="bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 px-3.5 py-2 rounded-xl font-bold text-xs transition-premium flex items-center gap-1.5 cursor-pointer">
            <span class="material-symbols-rounded text-base">delete</span> Delete Room
          </button>

          <!-- Close Button -->
          <button onclick="closeRoomModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3.5 py-2 rounded-xl font-bold text-xs transition-premium border border-slate-200 flex items-center gap-1.5 cursor-pointer">
            <span class="material-symbols-rounded text-base">close</span> Close
          </button>
        </div>
      </div>

      <!-- Modal Body (Scrollable) -->
      <div class="flex-grow overflow-y-auto p-5 md:p-6 space-y-6 bg-slate-50/50">
        <div class="max-w-5xl mx-auto space-y-6">
        
          <!-- Foldable Students Panel -->
          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
            <div onclick="toggleStudents()" class="p-4 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-premium">
              <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-600 text-lg">group</span> Enrolled Students
              </h4>
              <span id="studentsIcon" class="material-symbols-rounded text-slate-400 transition-transform">expand_more</span>
            </div>
            <div id="studentsContent" class="hidden border-t border-slate-100 p-4 bg-slate-50/60 space-y-4">
              <!-- Add Student Form -->
              <div class="flex flex-col sm:flex-row gap-3 bg-white p-3 rounded-xl border border-slate-200 shadow-2xs">
                <div class="flex-grow">
                  <input type="text" id="addStudentRegNo" placeholder="Enter Registration Number (e.g. 23010203)" class="w-full bg-slate-50 border border-slate-200 focus:border-blue-500 rounded-lg px-3.5 py-2 text-sm text-slate-800 outline-none transition-premium">
                </div>
                <button onclick="addStudentToRoom()" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-xs px-6 py-2 transition-premium shadow-sm shadow-blue-500/15 flex items-center justify-center gap-1.5 cursor-pointer">
                  <span class="material-symbols-rounded text-base">person_add</span> Add Student
                </button>
              </div>
              
              <ul id="roomStudentsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-sm pt-2">
              </ul>
            </div>
          </div>

          <!-- Room Tabs -->
          <div class="flex gap-2 border-b border-slate-200 pb-3">
            <button onclick="switchRoomTab('logs')" id="rtab_logs" class="px-4 py-2 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 transition-premium cursor-pointer">Session Logs</button>
            <button onclick="switchRoomTab('assessments')" id="rtab_assessments" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition-premium cursor-pointer">Assessments</button>
          </div>

          <!-- Session Logs Panel -->
          <div id="rpanel_logs" class="space-y-4">
            <div class="flex flex-wrap justify-between items-center gap-3">
              <h4 class="font-bold text-slate-900 text-sm">Class Logs</h4>
              <div class="flex flex-wrap items-center gap-2">
                <button onclick="printRemedialAnalysisReport()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-2xs">
                  <span class="material-symbols-rounded text-sm text-blue-600">analytics</span> Print Analysis
                </button>
                <button onclick="printRemedialAttendanceReport()" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-2xs">
                  <span class="material-symbols-rounded text-sm text-blue-600">print</span> Print Attendance
                </button>
                <button onclick="toggleLogForm()" class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-1.5 rounded-xl text-xs font-bold transition-premium flex items-center gap-1 shadow-sm shadow-blue-500/20 cursor-pointer">
                  <span class="material-symbols-rounded text-base">add</span> New Log
                </button>
              </div>
            </div>

            <div id="logFormContainer" class="hidden bg-white border border-slate-200 rounded-2xl p-5 mb-6 shadow-xs space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Date</label>
                  <input type="date" id="logDate" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-blue-500 outline-none transition-premium shadow-2xs">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Start Time</label>
                  <input type="time" id="logStartTime" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-blue-500 outline-none transition-premium shadow-2xs">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Duration (Mins)</label>
                  <input type="number" id="logDuration" value="60" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-blue-500 outline-none transition-premium shadow-2xs">
                </div>
                <div class="col-span-1 md:col-span-3">
                  <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Topic Covered</label>
                  <input type="text" id="logTopic" placeholder="e.g. Kirchhoff's Laws Revision" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-blue-500 outline-none transition-premium shadow-2xs">
                </div>
              </div>
              
              <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Attendance (Check Present)</label>
                <div id="logAttendanceGrid" class="grid grid-cols-2 md:grid-cols-3 gap-2.5 max-h-40 overflow-y-auto bg-slate-50 p-3 rounded-xl border border-slate-200">
                </div>
              </div>

              <button onclick="saveLog()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs py-3 transition-premium shadow-sm shadow-emerald-500/20 cursor-pointer">Save Session Log</button>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xs">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold text-xs">
                    <th class="p-3 w-8"></th>
                    <th class="p-3">Date</th>
                    <th class="p-3">Start Time</th>
                    <th class="p-3">Duration</th>
                    <th class="p-3 w-1/3">Topic</th>
                    <th class="p-3 text-right">Attendance</th>
                  </tr>
                </thead>
                <tbody id="roomLogsList" class="divide-y divide-slate-100">
                  <!-- Loaded via JS -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Assessments Panel -->
          <div id="rpanel_assessments" class="hidden space-y-4">
            <div class="flex justify-between items-center">
              <h4 class="font-bold text-slate-900 text-sm">Remedial Assessments</h4>
              <button onclick="toggleAssessmentForm()" class="bg-amber-600 hover:bg-amber-700 text-white px-3.5 py-1.5 rounded-xl text-xs font-bold transition-premium flex items-center gap-1 shadow-sm shadow-amber-500/20 cursor-pointer">
                <span class="material-symbols-rounded text-base">add</span> Create Test
              </button>
            </div>

            <div id="assessmentFormContainer" class="hidden bg-white border border-slate-200 rounded-2xl p-5 mb-6 shadow-xs space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Type</label>
                  <select id="assessType" onchange="toggleAssessFormFields()" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-amber-500 outline-none transition-premium shadow-2xs cursor-pointer">
                    <option value="Written Test">Written Test (with COs)</option>
                    <option value="Online Test">Online Test (Linked)</option>
                    <option value="Assignment">Assignment (Manual Entry)</option>
                  </select>
                </div>
                
                <div id="assessLinkContainer" class="hidden col-span-1 md:col-span-2">
                  <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Link Online Test</label>
                  <select id="assessLinkTest" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-amber-500 outline-none transition-premium shadow-2xs cursor-pointer">
                    <option value="">Select Test to Link...</option>
                  </select>
                </div>

                <div id="assessMaxMarksContainer" class="col-span-1 md:col-span-2">
                  <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Max Marks (If Assignment)</label>
                  <input type="number" id="assessMaxMarks" value="20" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-amber-500 outline-none transition-premium shadow-2xs">
                </div>

                <div class="col-span-1 md:col-span-3">
                  <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Test Title</label>
                  <input type="text" id="assessTitle" placeholder="e.g. Weekly Improvement Test 1" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-800 focus:border-amber-500 outline-none transition-premium shadow-2xs">
                </div>

                <div id="assessCOContainer" class="col-span-1 md:col-span-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
                  <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Define CO Max Marks (Leave blank if not applicable)</label>
                  <div class="grid grid-cols-5 gap-3">
                    <div>
                      <span class="text-xs text-slate-600 font-bold block mb-1">CO1</span>
                      <input type="number" id="co1_marks" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs text-slate-800 outline-none focus:border-amber-500 text-center font-bold" placeholder="-">
                    </div>
                    <div>
                      <span class="text-xs text-slate-600 font-bold block mb-1">CO2</span>
                      <input type="number" id="co2_marks" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs text-slate-800 outline-none focus:border-amber-500 text-center font-bold" placeholder="-">
                    </div>
                    <div>
                      <span class="text-xs text-slate-600 font-bold block mb-1">CO3</span>
                      <input type="number" id="co3_marks" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs text-slate-800 outline-none focus:border-amber-500 text-center font-bold" placeholder="-">
                    </div>
                    <div>
                      <span class="text-xs text-slate-600 font-bold block mb-1">CO4</span>
                      <input type="number" id="co4_marks" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs text-slate-800 outline-none focus:border-amber-500 text-center font-bold" placeholder="-">
                    </div>
                    <div>
                      <span class="text-xs text-slate-600 font-bold block mb-1">CO5</span>
                      <input type="number" id="co5_marks" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs text-slate-800 outline-none focus:border-amber-500 text-center font-bold" placeholder="-">
                    </div>
                  </div>
                </div>
              </div>
              <button onclick="saveAssessment()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs py-3 transition-premium shadow-sm shadow-emerald-500/20 cursor-pointer">Create Assessment</button>
            </div>

            <!-- Gradebook View -->
            <div id="gradebookContainer" class="hidden bg-white border border-slate-200 rounded-2xl p-5 mb-6 shadow-xs space-y-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-slate-100">
                <div>
                  <h4 id="gradebookTitle" class="font-bold text-slate-900 text-sm">Enter Scores</h4>
                  <p id="gradebookSub" class="text-xs text-slate-500 font-mono mt-0.5"></p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                  <button id="btnPrintRemedialReport" onclick="printRemedialReport()" class="bg-blue-50 text-blue-700 hover:bg-blue-100 px-3 py-1.5 rounded-xl text-xs font-bold transition-premium cursor-pointer border border-blue-200">Print Report</button>
                  <button id="btnSyncScores" onclick="syncOnlineScores()" class="hidden bg-emerald-50 text-emerald-700 hover:bg-emerald-100 px-3 py-1.5 rounded-xl text-xs font-bold transition-premium border border-emerald-200">Sync Online Scores</button>
                  <button onclick="closeGradebook()" class="text-slate-400 hover:text-slate-700 transition-colors p-1 cursor-pointer"><span class="material-symbols-rounded">close</span></button>
                </div>
              </div>
              
              <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                <table class="w-full text-left text-sm border-collapse">
                  <thead>
                    <tr id="gradebookTableHead" class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold text-xs">
                      <!-- Dynamic -->
                    </tr>
                  </thead>
                  <tbody id="gradebookTableBody" class="divide-y divide-slate-100">
                  </tbody>
                </table>
              </div>

              <button id="btnSaveScores" onclick="saveScores()" class="w-full bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs py-3 transition-premium shadow-sm shadow-blue-500/20 cursor-pointer">Save All Scores</button>
            </div>

            <div id="assessmentsList" class="space-y-4">
              <!-- Loaded via JS -->
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    let assignedSubjects = [];
    let currentStudentPerformance = [];
    let currentRoomId = null;
    let currentRoomStudents = [];

    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    };

    window.onload = () => {
      loadAssignedSubjects();
      loadRooms();
    };

    function switchTab(tabId) {
      document.getElementById('panel_roomsList').classList.add('hidden');
      document.getElementById('panel_createRoom').classList.add('hidden');
      
      const tabRooms = document.getElementById('tab_roomsList');
      const tabCreate = document.getElementById('tab_createRoom');

      if (tabId === 'roomsList') {
        document.getElementById('panel_roomsList').classList.remove('hidden');
        tabRooms.className = "px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 transition-premium cursor-pointer";
        tabCreate.className = "px-4 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white shadow-sm shadow-blue-500/20 flex items-center gap-1.5 transition-premium cursor-pointer";
      } else {
        document.getElementById('panel_createRoom').classList.remove('hidden');
        tabRooms.className = "px-5 py-2.5 rounded-xl text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-premium cursor-pointer";
        tabCreate.className = "px-4 py-2.5 rounded-xl text-xs font-bold bg-blue-700 text-white shadow-sm shadow-blue-600/30 flex items-center gap-1.5 ring-2 ring-blue-400/30 transition-premium cursor-pointer";
      }
    }

    function loadAssignedSubjects() {
      fetch('/api/remedial/assigned-subjects')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            assignedSubjects = data.subjects;
            const select = document.getElementById('subjectSelect');
            let html = '<option value="">Select a Subject...</option>';
            data.subjects.forEach((s, idx) => {
              html += `<option value="${idx}">${s.subject_code} - ${s.subject_name} (${s.batch_name})</option>`;
            });
            select.innerHTML = html;
          }
        });
    }

    function updateSelectedStudentCount() {
      const selected = document.querySelectorAll('.student-checkbox:checked').length;
      const total = currentStudentPerformance.length;
      const badge = document.getElementById('selectedCountBadge');
      const text = document.getElementById('btnProvisionText');
      if (badge) badge.innerText = `${selected} of ${total} Selected`;
      if (text) text.innerText = `Provision Remedial Room (${selected} Students)`;
    }

    function fetchStudentPerformance() {
      const idx = document.getElementById('subjectSelect').value;
      if (idx === '') return alert('Select a subject first');
      const subj = assignedSubjects[idx];

      fetch(`/api/remedial/student-performance?classroom_id=${subj.classroom_id}&subject_code=${subj.subject_code}`)
        .then(res => res.json())
        .then(data => {
          if(data.status === 'SUCCESS') {
            currentStudentPerformance = data.students;
            renderPerformanceGrid();
            document.getElementById('performanceConfigCard').classList.remove('hidden');
            document.getElementById('performanceSection').classList.remove('hidden');
            const emptyPrompt = document.getElementById('performanceEmptyPrompt');
            if (emptyPrompt) emptyPrompt.classList.add('hidden');
            applyThreshold();
          }
        });
    }

    function renderPerformanceGrid() {
      const tbody = document.getElementById('performanceTableBody');
      let html = '';
      currentStudentPerformance.forEach((s, i) => {
        const isLow = s.total_marks < 20;
        html += `
          <tr class="hover:bg-slate-50 transition-colors">
            <td class="p-3.5 text-center">
              <input type="checkbox" value="${s.reg_no}" onchange="updateSelectedStudentCount()" class="student-checkbox w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
            </td>
            <td class="p-3.5 text-xs text-slate-600 font-mono font-bold">${s.reg_no}</td>
            <td class="p-3.5 text-sm font-semibold text-slate-900">${s.name}</td>
            <td class="p-3.5 text-right text-xs font-bold ${isLow ? 'text-rose-600 font-mono' : 'text-emerald-600 font-mono'}">${s.total_marks}</td>
            <td class="p-3.5 text-center">
              <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase ${isLow ? 'text-rose-700 bg-rose-50 border border-rose-200' : 'text-emerald-700 bg-emerald-50 border border-emerald-200'}">
                ${isLow ? 'Priority' : 'Normal'}
              </span>
            </td>
          </tr>
        `;
      });
      tbody.innerHTML = html;
      updateSelectedStudentCount();
    }

    function toggleAllStudents() {
      const isChecked = document.getElementById('selectAllStudents').checked;
      document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = isChecked);
      updateSelectedStudentCount();
    }

    function applyThreshold() {
      const threshold = parseFloat(document.getElementById('thresholdMark').value) || 0;
      document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
      currentStudentPerformance.forEach((s, i) => {
        if (s.total_marks < threshold) {
          const cb = document.querySelector(`.student-checkbox[value="${s.reg_no}"]`);
          if(cb) cb.checked = true;
        }
      });
      updateSelectedStudentCount();
    }

    function provisionRoom() {
      const idx = document.getElementById('subjectSelect').value;
      if (idx === '') return alert('Select a subject');
      const subj = assignedSubjects[idx];

      const selected = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => cb.value);
      if (selected.length === 0) return alert('Select at least one student.');

      fetch('/api/remedial/rooms', {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({
          classroom_id: subj.classroom_id,
          subject_code: subj.subject_code,
          students: selected
        })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          alert('Room Provisioned!');
          loadRooms();
          switchTab('roomsList');
        }
      });
    }

    function loadRooms() {
      fetch('/api/remedial/rooms')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const container = document.getElementById('roomsContainer');
            if (data.rooms.length === 0) {
              container.innerHTML = `<div class="col-span-full py-16 text-center text-slate-400 font-semibold text-sm">No active remedial coaching rooms found.</div>`;
              return;
            }

            let html = '';
            data.rooms.forEach(r => {
              html += `
                <div class="group bg-white border border-slate-200 hover:border-blue-400 rounded-2xl p-5 transition-all duration-200 hover:shadow-md cursor-pointer flex flex-col justify-between" onclick="openRoom('${r.room_id}')">
                  <div>
                    <div class="flex justify-between items-start gap-2 mb-3">
                      <span class="px-2.5 py-0.5 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700 truncate max-w-[170px]" title="${r.department}">${r.department}</span>
                      <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200 inline-flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        ${r.status}
                      </span>
                    </div>

                    <div class="space-y-1 mb-4">
                      <div class="text-xs font-bold text-blue-600 uppercase tracking-wide font-mono">${r.subject_code}</div>
                      <h3 class="font-bold text-slate-900 text-base leading-snug group-hover:text-blue-600 transition-colors">${r.subject_name}</h3>
                    </div>
                  </div>

                  <div class="border-t border-slate-100 pt-4 mt-1 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                      <span class="text-slate-500 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-slate-400 text-sm">person</span>
                        Lecturer:
                      </span>
                      <span class="font-semibold text-slate-800">${r.lecturer_name}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                      <span class="text-slate-500 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-slate-400 text-sm">layers</span>
                        Classroom:
                      </span>
                      <span class="font-semibold text-slate-800 font-mono">${r.batch_name}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                      <span class="text-slate-500 font-medium flex items-center gap-1.5">
                        <span class="material-symbols-rounded text-slate-400 text-sm">group</span>
                        Enrolled:
                      </span>
                      <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                        ${r.student_count} Students
                      </span>
                    </div>
                  </div>
                </div>
              `;
            });
            container.innerHTML = html;
          }
        });
    }

    let currentAvailableTests = [];

    function openRoom(roomId) {
      currentRoomId = roomId;
      document.getElementById('logFormContainer').classList.add('hidden');
      document.getElementById('assessmentFormContainer').classList.add('hidden');
      document.getElementById('gradebookContainer').classList.add('hidden');
      switchRoomTab('logs');
      
      fetch(`/api/remedial/rooms/${roomId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const r = data.room;
            document.getElementById('modalRoomTitle').innerText = `${r.subject_code} - ${r.subject_name}`;
            document.getElementById('modalRoomSub').innerHTML = `
              <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-slate-500">
                <span class="flex items-center gap-1"><span class="material-symbols-rounded text-slate-400 text-sm">domain</span> <strong>Dept:</strong> ${r.department}</span>
                <span class="hidden md:inline text-slate-300">|</span>
                <span class="flex items-center gap-1"><span class="material-symbols-rounded text-slate-400 text-sm">person</span> <strong>Lecturer:</strong> ${r.lecturer_name}</span>
                <span class="hidden md:inline text-slate-300">|</span>
                <span class="flex items-center gap-1"><span class="material-symbols-rounded text-slate-400 text-sm">layers</span> <strong>Batch:</strong> ${r.batch_name} (Sem ${r.semester})</span>
                <span class="hidden md:inline text-slate-300">|</span>
                <span class="flex items-center gap-1"><span class="material-symbols-rounded text-slate-400 text-sm">calendar_today</span> <strong>Year:</strong> ${r.batch_year}</span>
              </div>
            `;
            
            // Set status select value
            const statusSelect = document.getElementById('modalRoomStatus');
            if (statusSelect) {
              statusSelect.value = r.status || 'active';
              if (r.status === 'archived') {
                statusSelect.className = "bg-transparent text-xs font-bold text-amber-700 focus:outline-none border-none cursor-pointer";
              } else {
                statusSelect.className = "bg-transparent text-xs font-bold text-emerald-700 focus:outline-none border-none cursor-pointer";
              }
            }
            currentRoomStudents = r.students;
            currentAvailableTests = r.available_tests || [];

            // Populate Test Dropdown
            let testHtml = '<option value="">Select Test to Link...</option>';
            currentAvailableTests.forEach(t => {
              testHtml += `<option value="${t.test_id}">${t.test_name} (${t.duration}m)</option>`;
            });
            document.getElementById('assessLinkTest').innerHTML = testHtml;

            // Render Students
            let sHtml = '';
            r.students.forEach(s => {
              sHtml += `<li class="p-3 bg-white border border-slate-200 rounded-xl flex justify-between items-center hover:border-blue-300 transition-premium shadow-2xs">
                <div>
                  <p class="font-bold text-slate-900 text-xs">${s.name}</p>
                  <p class="text-xs font-mono text-slate-500 mt-0.5">${s.reg_no}</p>
                </div>
                <button onclick="confirmRemoveStudent(this, '${s.reg_no}')" class="text-xs font-bold text-rose-600 hover:text-white hover:bg-rose-600 px-2.5 py-1 rounded-lg transition-premium cursor-pointer border border-rose-200">Remove</button>
              </li>`;
            });
            document.getElementById('roomStudentsList').innerHTML = sHtml;

            // Render Logs (Foldable Table)
            let lHtml = '';
            if (r.logs.length === 0) lHtml = '<tr><td colspan="6" class="p-4 text-center text-slate-400 text-xs">No sessions logged yet.</td></tr>';
            r.logs.forEach((l, idx) => {
              let attCount = (l.attendance_data || []).length;
              lHtml += `
                <tr class="hover:bg-slate-50 transition-colors cursor-pointer" onclick="toggleLogDetails(${idx})">
                  <td class="p-3 w-8 text-center text-slate-400"><span id="logIcon_${idx}" class="material-symbols-rounded text-sm transition-transform">expand_more</span></td>
                  <td class="p-3 font-semibold text-blue-600 text-xs">${l.session_date}</td>
                  <td class="p-3 text-slate-700 text-xs">${l.start_time || '--:--'}</td>
                  <td class="p-3 text-slate-500 text-xs">${l.duration_minutes}m</td>
                  <td class="p-3 text-slate-800 text-xs truncate max-w-[150px]" title="${l.topic_covered}">${l.topic_covered || 'No topic specified'}</td>
                  <td class="p-3 text-right"><span class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full font-bold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>${attCount} Present</span></td>
                </tr>
                <tr id="logDetails_${idx}" class="hidden bg-slate-50/80">
                  <td colspan="6" class="p-4 border-t border-slate-200">
                    <p class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Students Present:</p>
                    <div class="flex flex-wrap gap-2">
                      ${(l.attendance_data||[]).map(reg => {
                        let st = r.students.find(s => s.reg_no === reg);
                        let nameToShow = st ? st.name : reg;
                        return `<span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 font-semibold shadow-2xs">${nameToShow}</span>`;
                      }).join('')}
                    </div>
                  </td>
                </tr>
              `;
            });
            document.getElementById('roomLogsList').innerHTML = lHtml;

            // Prep Attendance form
            let attHtml = '';
            r.students.forEach(s => {
              attHtml += `<label class="flex items-center gap-2 cursor-pointer bg-white p-2 rounded-lg border border-slate-200 shadow-2xs"><input type="checkbox" value="${s.reg_no}" class="log-att-checkbox w-3.5 h-3.5 text-emerald-600 rounded border-slate-300" checked><span class="text-xs font-semibold text-slate-700">${s.reg_no} - ${s.name}</span></label>`;
            });
            document.getElementById('logAttendanceGrid').innerHTML = attHtml;
            document.getElementById('logDate').valueAsDate = new Date();

            loadAssessments();

            document.getElementById('roomModal').classList.remove('hidden');
          }
        });
    }

    function toggleLogDetails(idx) {
      const el = document.getElementById(`logDetails_${idx}`);
      const icon = document.getElementById(`logIcon_${idx}`);
      if(el.classList.contains('hidden')){
        el.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
      } else {
        el.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
      }
    }

    function toggleStudents() {
      const content = document.getElementById('studentsContent');
      const icon = document.getElementById('studentsIcon');
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
      }
    }

    function switchRoomTab(tabId) {
      document.getElementById('rpanel_logs').classList.add('hidden');
      document.getElementById('rpanel_assessments').classList.add('hidden');
      
      document.getElementById('rtab_logs').className = "px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition-premium cursor-pointer";
      document.getElementById('rtab_assessments').className = "px-4 py-2 rounded-xl text-xs font-semibold bg-white text-slate-600 border border-slate-200 hover:bg-slate-50 transition-premium cursor-pointer";

      document.getElementById('rpanel_' + tabId).classList.remove('hidden');
      document.getElementById('rtab_' + tabId).className = "px-4 py-2 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 transition-premium cursor-pointer";
    }

    function closeRoomModal() {
      document.getElementById('roomModal').classList.add('hidden');
    }

    function confirmRemoveStudent(btn, regNo) {
      if (btn.innerText === "Remove") {
        btn.innerText = "Confirm?";
        btn.classList.add('bg-rose-600', 'text-white');
        setTimeout(() => {
          if (btn && btn.innerText === "Confirm?") {
            btn.innerText = "Remove";
            btn.classList.remove('bg-rose-600', 'text-white');
          }
        }, 3000);
      } else {
        removeStudent(regNo);
      }
    }

    function removeStudent(regNo) {
      fetch(`/api/remedial/rooms/${currentRoomId}/students`, {
        method: 'DELETE',
        headers: headers,
        body: JSON.stringify({ reg_no: regNo })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') openRoom(currentRoomId);
      });
    }

    function toggleLogForm() {
      document.getElementById('logFormContainer').classList.toggle('hidden');
    }

    function saveLog() {
      const date = document.getElementById('logDate').value;
      const start = document.getElementById('logStartTime').value;
      const duration = document.getElementById('logDuration').value;
      const topic = document.getElementById('logTopic').value;
      const att = Array.from(document.querySelectorAll('.log-att-checkbox:checked')).map(cb => cb.value);

      if (!date || !topic) return alert('Date and Topic are required.');

      fetch(`/api/remedial/rooms/${currentRoomId}/logs`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({
          session_date: date,
          start_time: start,
          duration_minutes: duration,
          topic_covered: topic,
          attendance: att
        })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          openRoom(currentRoomId);
        }
      });
    }

    let currentAssessments = [];
    let currentAssessmentId = null;

    function loadAssessments() {
      fetch(`/api/remedial/rooms/${currentRoomId}/assessments`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentAssessments = data.assessments;
            const container = document.getElementById('assessmentsList');
            let html = '';
            if (currentAssessments.length === 0) html = '<p class="text-slate-400 text-xs py-4 text-center">No assessments created yet.</p>';
            
            currentAssessments.forEach((a, idx) => {
              let gradedCount = (a.scores || []).length;
              html += `
                <div class="bg-white border border-slate-200 rounded-2xl p-4 flex justify-between items-center hover:border-blue-300 transition-premium shadow-2xs">
                  <div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider text-amber-700 bg-amber-50 border border-amber-200 mb-2 inline-block">${a.type}</span>
                    <h5 class="text-sm font-bold text-slate-900">${a.title}</h5>
                    <p class="text-xs text-slate-500 font-mono mt-1">Max Marks: ${a.max_marks} | Graded: ${gradedCount}/${currentRoomStudents.length}</p>
                  </div>
                  <button onclick="openGradebook(${idx})" class="bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs px-4 py-2 transition-premium border border-slate-200 shadow-2xs cursor-pointer">Enter Marks</button>
                </div>
              `;
            });
            container.innerHTML = html;
          }
        });
    }

    function toggleAssessmentForm() {
      document.getElementById('assessmentFormContainer').classList.toggle('hidden');
      toggleAssessFormFields();
    }

    function toggleAssessFormFields() {
      const type = document.getElementById('assessType').value;
      const coCont = document.getElementById('assessCOContainer');
      const linkCont = document.getElementById('assessLinkContainer');
      const marksCont = document.getElementById('assessMaxMarksContainer');

      if (type === 'Online Test') {
        coCont.classList.add('hidden');
        linkCont.classList.remove('hidden');
        marksCont.classList.add('hidden');
      } else if (type === 'Written Test') {
        coCont.classList.remove('hidden');
        linkCont.classList.add('hidden');
        marksCont.classList.remove('hidden');
      } else {
        coCont.classList.add('hidden');
        linkCont.classList.add('hidden');
        marksCont.classList.remove('hidden');
      }
    }

    function saveAssessment() {
      const type = document.getElementById('assessType').value;
      const marks = document.getElementById('assessMaxMarks').value;
      const title = document.getElementById('assessTitle').value;
      const link = document.getElementById('assessLinkTest').value;

      if (!title) return alert('Title is required.');

      let coStructure = null;
      if (type === 'Written Test') {
        coStructure = {};
        let hasCo = false;
        ['co1','co2','co3','co4','co5'].forEach(co => {
          let v = document.getElementById(co+'_marks').value;
          if(v) { coStructure[co.toUpperCase()] = parseFloat(v); hasCo = true; }
        });
        if(!hasCo) coStructure = null;
      }

      fetch(`/api/remedial/rooms/${currentRoomId}/assessments`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({ 
          type: type, 
          max_marks: type === 'Online Test' ? 100 : marks, 
          title: title,
          linked_test_id: type === 'Online Test' ? link : null,
          co_structure: coStructure
        })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          document.getElementById('assessTitle').value = '';
          document.getElementById('assessmentFormContainer').classList.add('hidden');
          loadAssessments();
        }
      });
    }

    function openGradebook(idx) {
      const a = currentAssessments[idx];
      currentAssessmentId = a.assessment_id;
      
      document.getElementById('gradebookTitle').innerText = a.title;
      document.getElementById('gradebookSub').innerText = `${a.type} - Max Marks: ${a.max_marks}`;
      
      const isOnline = a.type === 'Online Test';
      const hasCOs = a.co_structure && Object.keys(a.co_structure).length > 0;
      
      // Controls
      if (isOnline) {
        document.getElementById('btnSyncScores').classList.remove('hidden');
        document.getElementById('btnSaveScores').classList.add('hidden');
      } else {
        document.getElementById('btnSyncScores').classList.add('hidden');
        document.getElementById('btnSaveScores').classList.remove('hidden');
      }

      // Headers
      let headHtml = '<th class="p-3 w-12">S.No.</th><th class="p-3">Name</th><th class="p-3 w-28">Admission No</th><th class="p-3 w-32">SBTE Reg No</th>';
      if (hasCOs && !isOnline) {
        Object.keys(a.co_structure).forEach(co => {
          headHtml += `<th class="p-3 w-16 text-center">${co} (${a.co_structure[co]})</th>`;
        });
      }
      headHtml += `<th class="p-3 w-24 text-right">Total Score</th>`;
      document.getElementById('gradebookTableHead').innerHTML = headHtml;

      // Build Score Map for fast lookup
      let scoreMap = {};
      if(a.scores) a.scores.forEach(s => { scoreMap[s.reg_no] = { score: s.score, cos: s.co_scores || {} }; });

      let bodyHtml = '';
      currentRoomStudents.forEach((s, index) => {
        let sc = scoreMap[s.reg_no] || { score: '', cos: {} };
        
        bodyHtml += `<tr class="hover:bg-slate-50 transition-colors">
            <td class="p-3 text-xs text-slate-500 font-semibold">${index + 1}</td>
            <td class="p-3 text-sm font-semibold text-slate-900">${s.name}</td>
            <td class="p-3 text-xs text-slate-500 font-mono">${s.reg_no}</td>
            <td class="p-3 text-xs text-slate-500 font-mono">${s.sbte_reg_no || '-'}</td>`;
        
        if (hasCOs && !isOnline) {
          Object.keys(a.co_structure).forEach(co => {
            let val = sc.cos[co] !== undefined ? sc.cos[co] : '';
            bodyHtml += `<td class="p-3 text-center"><input type="number" data-reg="${s.reg_no}" data-co="${co}" value="${val}" max="${a.co_structure[co]}" class="co-input w-12 bg-white border border-slate-200 rounded px-1 py-1 text-xs text-slate-800 outline-none focus:border-amber-500 text-center font-bold"></td>`;
          });
        }

        let inputAttr = isOnline ? 'disabled' : '';
        let classStr = isOnline ? 'w-20 bg-slate-100 text-emerald-700 font-bold border-transparent' : 'score-input w-20 bg-white border border-slate-200 focus:border-amber-500 font-bold';

        bodyHtml += `<td class="p-3 text-right">
              <input type="number" data-reg="${s.reg_no}" value="${sc.score}" max="${a.max_marks}" class="${classStr} rounded-lg px-3 py-1.5 text-xs text-slate-800 outline-none text-center" ${inputAttr}>
            </td>
          </tr>`;
      });
      
      document.getElementById('gradebookTableBody').innerHTML = bodyHtml;
      document.getElementById('gradebookContainer').classList.remove('hidden');
    }

    function closeGradebook() {
      document.getElementById('gradebookContainer').classList.add('hidden');
      currentAssessmentId = null;
    }

    function syncOnlineScores() {
      if(!currentAssessmentId) return;
      document.getElementById('btnSyncScores').innerText = "Syncing...";
      
      fetch(`/api/remedial/rooms/${currentRoomId}/assessments/${currentAssessmentId}/sync`, {
        method: 'POST',
        headers: headers
      })
      .then(res => res.json())
      .then(data => {
        document.getElementById('btnSyncScores').innerText = "Sync Online Scores";
        if(data.status === 'SUCCESS') {
          loadAssessments();
          setTimeout(() => openGradebook(currentAssessments.findIndex(a => a.assessment_id === currentAssessmentId)), 500);
        } else {
          alert(data.message || 'Error syncing');
        }
      });
    }

    function saveScores() {
      if(!currentAssessmentId) return;
      
      let payloadMap = {};
      
      // Collect Total Scores
      document.querySelectorAll('.score-input').forEach(inp => {
        if(inp.value !== '') {
          let reg = inp.getAttribute('data-reg');
          if(!payloadMap[reg]) payloadMap[reg] = { reg_no: reg, co_scores: {} };
          payloadMap[reg].score = parseFloat(inp.value);
        }
      });

      // Collect CO Scores
      document.querySelectorAll('.co-input').forEach(inp => {
        if(inp.value !== '') {
          let reg = inp.getAttribute('data-reg');
          let co = inp.getAttribute('data-co');
          if(!payloadMap[reg]) payloadMap[reg] = { reg_no: reg, co_scores: {}, score: 0 };
          payloadMap[reg].co_scores[co] = parseFloat(inp.value);
        }
      });

      let payload = Object.values(payloadMap);

      fetch(`/api/remedial/rooms/${currentRoomId}/assessments/${currentAssessmentId}/scores`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({ scores: payload })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          alert('Scores Saved!');
          closeGradebook();
          loadAssessments();
        }
      });
    }

    function printRemedialReport() {
      if (!currentRoomId || !currentAssessmentId) return;
      window.open(`/remedial/rooms/${currentRoomId}/assessments/${currentAssessmentId}/report`, '_blank');
    }

    function printRemedialAttendanceReport() {
      if (!currentRoomId) return;
      window.open(`/remedial/rooms/${currentRoomId}/attendance/report`, '_blank');
    }

    function printRemedialAnalysisReport() {
      if (!currentRoomId) return;
      window.open(`/remedial/rooms/${currentRoomId}/analysis/report`, '_blank');
    }

    function confirmDeleteRoom(btn) {
      if (btn.innerText.includes("Delete Room")) {
        btn.innerHTML = `<span class="material-symbols-rounded text-sm">warning</span> Confirm?`;
        btn.className = "bg-rose-600 hover:bg-rose-500 border border-rose-500 text-white px-3.5 py-2 rounded-xl font-bold text-xs transition-premium flex items-center gap-1.5";
        setTimeout(() => {
          if (btn && btn.innerText.includes("Confirm")) {
            btn.innerHTML = `<span class="material-symbols-rounded text-sm">delete</span> Delete Room`;
            btn.className = "bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 px-3.5 py-2 rounded-xl font-bold text-xs transition-premium flex items-center gap-1.5";
          }
        }, 4000);
      } else {
        deleteRoom();
      }
    }

    function deleteRoom() {
      if (!currentRoomId) return;
      fetch(`/api/remedial/rooms/${currentRoomId}`, {
        method: 'DELETE',
        headers: headers
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert('Room deleted successfully!');
          closeRoomModal();
          loadRooms();
        } else {
          alert(data.message || 'Error deleting room');
        }
      });
    }

    function updateRoomStatus() {
      if (!currentRoomId) return;
      const status = document.getElementById('modalRoomStatus').value;
      fetch(`/api/remedial/rooms/${currentRoomId}/status`, {
        method: 'PATCH',
        headers: headers,
        body: JSON.stringify({ status: status })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          loadRooms();
          const selectEl = document.getElementById('modalRoomStatus');
          if (status === 'archived') {
            selectEl.className = "bg-transparent text-xs font-bold text-amber-700 focus:outline-none border-none cursor-pointer";
          } else {
            selectEl.className = "bg-transparent text-xs font-bold text-emerald-700 focus:outline-none border-none cursor-pointer";
          }
        } else {
          alert(data.message || 'Error updating status');
        }
      });
    }

    function addStudentToRoom() {
      if (!currentRoomId) return;
      const regNo = document.getElementById('addStudentRegNo').value.trim();
      if (!regNo) return alert('Enter a valid registration number');

      fetch(`/api/remedial/rooms/${currentRoomId}/students`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({ reg_no: regNo })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          document.getElementById('addStudentRegNo').value = '';
          openRoom(currentRoomId);
        } else {
          alert(data.message || 'Error adding student');
        }
      });
    }
  </script>
  @endpush
</x-layouts.faculty-shell>
