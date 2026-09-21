<div id="panelClassroom" class="hidden space-y-5">
        
        <!-- Header Card -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
          <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200/80 shrink-0">
              <x-ui.icon name="book" class="w-5 h-5 text-blue-600" />
            </div>
            <div>
              <h3 id="vcTitle" class="text-lg font-bold text-slate-900 leading-snug">Virtual Classroom</h3>
              <p id="vcSubtitle" class="text-xs sm:text-sm text-slate-500 font-mono mt-0.5">Loading...</p>
            </div>
          </div>
          <div class="flex items-center gap-2.5">
            <button id="vcViewStudentsBtn" onclick="showVcStudentsList()" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shadow-2xs border border-slate-200">
              <x-ui.icon name="groups" class="w-4 h-4 text-blue-600" /> View Students
            </button>
            @if(session('userRole') === 'Demonstrator')
              <a href="/dashboard/demonstrator" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shadow-2xs border border-slate-200 no-underline">
                <x-ui.icon name="arrow_back" class="w-4 h-4 text-slate-600" /> Back to Console
              </a>
            @else
              <button onclick="switchPanel('dashboard')" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition cursor-pointer flex items-center gap-1.5 shadow-2xs border border-slate-200">
                <x-ui.icon name="arrow_back" class="w-4 h-4 text-slate-600" /> Back to Dashboard
              </button>
            @endif
          </div>
        </div>

        <!-- Top Banner: Course File Actions -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
             <!-- Syllabus Setup Card -->
             <div class="flex-grow bg-white border border-slate-200/80 p-4 rounded-2xl relative overflow-hidden group flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-4">
                  <div id="syllabusUploadBox" class="border-2 border-dashed border-slate-300 rounded-xl px-4 py-2.5 text-center hover:border-blue-500 hover:bg-blue-50/50 transition cursor-pointer relative z-10 flex items-center gap-3" onclick="document.getElementById('syllabusFileInput').click()">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200/80">
                      <svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M9 11h6"/></svg>
                    </div>
                    <div class="text-left">
                      <p class="text-xs font-bold text-slate-800">Upload Syllabus PDF</p>
                      <p class="text-xs text-slate-400">PDF • Max 10MB</p>
                    </div>
                    <input type="file" id="syllabusFileInput" class="hidden" accept="application/pdf" onchange="handleSyllabusUpload(this)">
                  </div>
                  
                  <div id="syllabusUploadProgress" class="hidden relative z-10 flex-col justify-center min-w-[220px]">
                    <div class="flex justify-between text-xs font-bold text-blue-700 mb-1">
                      <span>Extracting Academic Structure...</span>
                      <span id="syllabusProgressText" class="animate-pulse font-mono">Processing</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 border border-slate-200 overflow-hidden">
                      <div class="bg-blue-600 h-1.5 rounded-full w-full animate-pulse"></div>
                    </div>
                  </div>

                  <div id="vcSubjectInfo" style="display:none" class="flex-col justify-center border-l border-slate-200 pl-4 relative z-10">
                    <span id="vcSubjectName" class="text-sm font-bold text-slate-900 leading-tight"></span>
                    <div class="flex items-center gap-2 mt-0.5">
                      <span id="vcSubjectCode" class="text-xs font-semibold text-blue-700 font-mono"></span>
                      <span id="vcSyllabusProposedHours" class="text-xs font-bold text-emerald-700 whitespace-nowrap"></span>
                    </div>
                  </div>
                </div>
                 <span id="parseStatusBadge" class="text-xs font-bold px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap shadow-2xs">Waiting for upload</span>
             </div>

             <!-- Download Active Syllabus Card -->
             <div id="activeSyllabusCard" class="hidden bg-white border border-slate-200/80 p-4 rounded-2xl flex items-center gap-3 transition min-w-[250px] shadow-xs">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div class="flex-grow">
                  <h4 class="text-sm font-bold text-slate-900 leading-tight">Active Syllabus</h4>
                  <p class="text-xs text-slate-500 mt-0.5">Parsed &amp; synced</p>
                </div>
                <button id="downloadSyllabusBtn" onclick="downloadSyllabusPDF()" title="View / Download Syllabus PDF" class="text-slate-600 hover:text-blue-700 transition bg-slate-50 hover:bg-blue-50 p-2 rounded-xl border border-slate-200 hover:border-blue-300 cursor-pointer shadow-2xs">
                   <svg class="w-4 h-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                </button>
             </div>
        </div>
        
         <!-- Toggle Buttons Navigation Strip -->
         <div class="bg-white border border-slate-200/80 p-2 rounded-2xl flex flex-wrap items-center gap-2 mb-4 shadow-xs">
             <button onclick="toggleClassroomTab('structure')" id="tabStructure" class="classroom-tab-btn flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs cursor-pointer">
               <x-ui.icon name="account_tree" class="w-4 h-4" /> Course Structure
             </button>
             <button onclick="toggleClassroomTab('planner')" id="tabPlanner" class="classroom-tab-btn flex items-center gap-1.5 text-slate-600 hover:bg-slate-50 border border-transparent cursor-pointer">
               <x-ui.icon name="calendar_month" class="w-4 h-4" /> Lesson Planner
             </button>
             <button onclick="toggleClassroomTab('assessment')" id="tabAssessment" class="classroom-tab-btn flex items-center gap-1.5 text-slate-600 hover:bg-slate-50 border border-transparent cursor-pointer">
               <x-ui.icon name="assignment_turned_in" class="w-4 h-4" /> Formative Assessment
             </button>
             <button onclick="toggleClassroomTab('summative')" id="tabSummative" class="classroom-tab-btn flex items-center gap-1.5 text-slate-600 hover:bg-slate-50 border border-transparent cursor-pointer">
               <x-ui.icon name="school" class="w-4 h-4" /> Summative Assessment
             </button>
             <button onclick="toggleClassroomTab('reports')" id="tabReports" class="classroom-tab-btn flex items-center gap-1.5 text-slate-600 hover:bg-slate-50 border border-transparent cursor-pointer">
               <x-ui.icon name="assessment" class="w-4 h-4" /> Reports
             </button>
             <button onclick="toggleClassroomTab('qbank')" id="tabQBank" class="classroom-tab-btn flex items-center gap-1.5 text-slate-600 hover:bg-slate-50 border border-transparent cursor-pointer">
               <x-ui.icon name="database" class="w-4 h-4" /> Question Bank
             </button>
             <button onclick="toggleClassroomTab('survey')" id="tabSurvey" class="classroom-tab-btn flex items-center gap-1.5 text-slate-600 hover:bg-slate-50 border border-transparent cursor-pointer">
               <x-ui.icon name="rate_review" class="w-4 h-4" /> Mid-Sem Survey
             </button>
             <button onclick="toggleClassroomTab('exit_survey')" id="tabExitSurvey" class="classroom-tab-btn flex items-center gap-1.5 text-slate-600 hover:bg-slate-50 border border-transparent cursor-pointer">
               <x-ui.icon name="check_circle" class="w-4 h-4" /> Course Exit Survey
             </button>
             <button onclick="toggleClassroomTab('seminar_evaluation')" id="tabSeminar" class="hidden classroom-tab-btn flex items-center gap-1.5 text-slate-600 hover:bg-slate-50 border border-transparent cursor-pointer">
               <x-ui.icon name="co_present" class="w-4 h-4" /> Seminar Evaluation
             </button>
             <button onclick="toggleClassroomTab('lab_evaluation')" id="tabLab" class="hidden classroom-tab-btn flex items-center gap-1.5 text-slate-600 hover:bg-slate-50 border border-transparent cursor-pointer">
               <x-ui.icon name="science" class="w-4 h-4" /> Lab Evaluation
             </button>
         </div>

        <!-- Parsed Data View (Full Width) -->
        <div class="bg-white border border-slate-200/80 p-6 rounded-2xl min-h-[400px] flex flex-col w-full shadow-xs">
            <div id="courseStructureContent" class="space-y-6 flex-grow overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                  <x-ui.icon name="science" class="w-5 h-5 text-slate-600" />
                </div>
                <p class="text-sm font-bold text-slate-700">No syllabus loaded.</p>
                <p class="text-sm mt-1.5 max-w-xs text-slate-400 leading-relaxed">Upload a syllabus PDF to automatically populate Course Outcomes, Modules, and Textbooks.</p>
              </div>
            </div>
            
            <div id="coursePlannerContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                  <x-ui.icon name="science" class="w-5 h-5 text-slate-600" />
                </div>
                <p class="text-sm font-bold text-slate-700">Planner not generated.</p>
                <p class="text-sm mt-1.5 max-w-xs text-slate-400 leading-relaxed">Upload a syllabus to automatically generate the lesson plan.</p>
              </div>
            </div>

            <div id="formativeAssessmentContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                  <x-ui.icon name="quiz" class="w-5 h-5 text-slate-600" />
                </div>
                <p class="text-sm font-bold text-slate-700">No students or COs available.</p>
                <p class="text-sm mt-1.5 max-w-xs text-slate-400 leading-relaxed">Upload a syllabus to activate formative assessment tasks.</p>
              </div>
            </div>

            <div id="summativeAssessmentContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10">
              <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                  <x-ui.icon name="school" class="w-5 h-5 text-slate-600" />
                </div>
                <p class="text-sm font-bold text-slate-700">Loading summative assessments...</p>
              </div>
            </div>

            <div id="classReportsContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-wrap gap-3">
                <button onclick="loadClassReport('attendance_log')" id="btnReportLog" class="px-4 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-xs cursor-pointer transition-premium">
                  Class Attendance Log
                </button>
                <button onclick="loadClassReport('subject_log')" id="btnReportSubject" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-50 border border-slate-200 rounded-xl font-bold text-sm shadow-2xs cursor-pointer transition-premium">
                  Class Subject Log
                </button>
                <button onclick="loadClassReport('summary_matrix')" id="btnReportMatrix" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-50 border border-slate-200 rounded-xl font-bold text-sm shadow-2xs cursor-pointer transition-premium">
                  Attendance Matrix
                </button>
              </div>

              <div id="classroomReportWorkspace" class="pt-4 overflow-x-auto">
                <div class="text-sm font-bold text-slate-400 py-10 text-center">No reports loaded. Please select a report type above.</div>
              </div>
            </div>

            <!-- Question Bank Panel -->
            <div id="questionBankContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-4">
                <div>
                  <h4 class="text-sm font-bold text-slate-800">Shared Question Bank Pool</h4>
                  <p class="text-sm text-slate-400 mt-1">Manage and import MCQ or Descriptive questions for this subject code. These questions are pooled across all batches.</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                  <button onclick="downloadExcelTemplate()" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-sm font-bold transition-premium flex items-center gap-1.5 shadow-xs cursor-pointer">
                    <x-ui.icon name="download" class="w-4 h-4" /> Download Excel Template
                  </button>
                  <button onclick="document.getElementById('qbankFileInput').click()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-xs">
                    <x-ui.icon name="science" class="w-4 h-4" /> Upload Filled Excel
                  </button>
                  <input type="file" id="qbankFileInput" class="hidden" accept=".xlsx,.xls,.csv" onchange="handleQBankUpload(this)">
                </div>
              </div>

              <!-- Question Bank View -->
              <div class="bg-white border border-slate-200/80 rounded-xl p-6 shadow-xs">
                <div class="space-y-6" id="qbankCoGroups">
                  <div class="text-sm font-bold text-slate-400 py-10 text-center">Loading Question Bank...</div>
                </div>
              </div>
            </div>

            <!-- Mid Semester Survey Panel (SAR Criterion 2) -->
            <div id="midSemesterSurveyContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-4">
                <div>
                  <h4 class="text-sm font-bold text-slate-800">Mid-Semester Survey Evaluation (SAR Criterion 2)</h4>
                  <p class="text-sm text-slate-400 mt-1">Conduct real-time Teaching-Learning process evaluation to identify learning difficulties and plan immediate corrective actions.</p>
                </div>
                <div class="flex items-center gap-3 font-semibold text-sm" id="surveyHeaderActions">
                  <!-- Rendered dynamically -->
                </div>
              </div>

              <!-- Main Workspace for Survey -->
              <div id="surveyWorkspace" class="space-y-6">
                <!-- Rendered dynamically (Initiate Screen / Live Panel / Results Panel) -->
              </div>
            </div>

            <!-- Course Exit Survey Panel (Indirect CO Attainment) -->
            <div id="courseExitSurveyContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-4">
                <div>
                  <h4 class="text-sm font-bold text-slate-800">Course Exit Survey (Indirect CO Attainment)</h4>
                  <p class="text-sm text-slate-400 mt-1">Evaluates indirect Course Outcome (CO) attainment parameters at semester-end for NBA course file accreditation.</p>
                </div>
                <div class="flex items-center gap-3 font-semibold text-sm" id="exitSurveyHeaderActions">
                  <!-- Rendered dynamically -->
                </div>
              </div>

              <!-- Main Workspace for Exit Survey -->
              <div id="exitSurveyWorkspace" class="space-y-6">
                <!-- Rendered dynamically (Initiate Screen / Live Panel / Results Panel) -->
              </div>
            </div>

            <!-- Seminar Evaluation Workspace -->
            <div id="seminarEvaluationContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-4">
                <div>
                  <h4 class="text-sm font-bold text-slate-900">Seminar Evaluation (Revision 2021)</h4>
                  <p class="text-sm text-slate-400 mt-1">Grade student seminars based on CIA criteria. Multiple assessors' scores will be averaged to formulate the final mark.</p>
                </div>
                <div class="flex items-center gap-2">
                  <button onclick="fetchSeminarEvaluations()" title="Sync latest evaluations" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-700 hover:text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-md border border-slate-700/60">
                    <x-ui.icon name="refresh" class="w-4 h-4" /> Refresh
                  </button>
                  <a id="printSeminarReportBtn" href="#" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-1.5 cursor-pointer shadow-md">
                    <x-ui.icon name="print" class="w-4 h-4" /> Print Seminar Report
                  </a>
                </div>
              </div>

              <!-- Students List with Split Evaluation Details -->
              <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse">
                    <thead>
                      <tr class="border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider text-xs bg-white">
                        <th class="p-3">Roll No</th>
                        <th class="p-3">Student Name</th>
                        <th class="p-3">Topic</th>
                        <th class="p-3">Guide</th>
                        <th class="p-3 text-center">Presentation Date</th>
                        <th class="p-3 text-center">Relevance (7.5)</th>
                        <th class="p-3 text-center">Literature (7.5)</th>
                        <th class="p-3 text-center">Presentation (37.5)</th>
                        <th class="p-3 text-center">Interaction (7.5)</th>
                        <th class="p-3 text-center">Report (7.5)</th>
                        <th class="p-3 text-center">Attendance (7.5)</th>
                        <th class="p-3 text-center">My Total (75)</th>
                        <th class="p-3 text-center text-teal-400">Class Average (75)</th>
                        <th class="p-3 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody id="seminarEvaluationsTableBody" class="divide-y divide-slate-100">
                      <tr>
                        <td colspan="14" class="p-8 text-center text-slate-400 font-bold text-sm">Loading evaluations...</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Lab Evaluation Workspace (Revision 2021) -->
            <div id="labEvaluationContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 border-b border-slate-200 pb-5">
                <div class="max-w-xl shrink-0">
                  <h4 class="text-base font-bold text-slate-900 tracking-wide">Practical / Lab Evaluation Register</h4>
                  <p class="text-sm text-slate-400 mt-1.5 leading-relaxed">Grade day-to-day experiments (37.5), model tests (15),<br>micro-projects (7.5), and board exam marks (50).</p>
                </div>
                <div class="flex items-center gap-3 w-full lg:w-auto overflow-x-auto whitespace-nowrap pb-1 lg:pb-0 scrollbar-none">
                  <div class="flex items-center gap-2.5 bg-white border border-slate-200 px-3.5 py-2 rounded-xl shadow-2xs focus-within:border-blue-500 transition-all shrink-0">
                    <span class="text-sm font-bold text-slate-400 uppercase tracking-wider">Batch Filter:</span>
                    <select id="labBatchFilterSelect" onchange="filterLabGridByBatch()" class="bg-transparent border-0 text-slate-800 font-bold text-sm outline-none cursor-pointer">
                      <option value="combined" class="bg-white text-slate-800">Combined (Full Class)</option>
                      <option value="1" class="bg-white text-slate-800">Lab Batch 1 (First 50%)</option>
                      <option value="2" class="bg-white text-slate-800">Lab Batch 2 (Second 50%)</option>
                    </select>
                  </div>
                  <button onclick="openManageExperimentsModal()" class="px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl text-sm font-bold transition-premium flex items-center gap-2 cursor-pointer shadow-md shrink-0">
                    <x-ui.icon name="science" class="w-4 h-4 text-teal-400" /> Manage Experiments
                  </button>
                  <button onclick="openManageTestsModal()" class="px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 text-slate-700 rounded-xl text-sm font-bold transition-premium flex items-center gap-2 cursor-pointer shadow-md shrink-0">
                    <x-ui.icon name="assignment_turned_in" class="w-4 h-4 text-blue-400" /> Configure Tests
                  </button>
                  <a id="printLabReportBtn" href="#" target="_blank" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-lg shadow-blue-500/15 shrink-0">
                    <x-ui.icon name="print" class="w-4 h-4" /> Print Register
                  </a>
                </div>
              </div>

              <!-- Lab Statistics Widgets -->
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Card 1: Avg Internal Mark -->
                <div class="bg-white border border-teal-200/80 hover:border-teal-300 p-5 rounded-2xl shadow-xs transition-all duration-300 group flex flex-col justify-between">
                  <div class="flex justify-between items-start">
                    <span class="text-sm font-bold text-teal-400 uppercase tracking-wider">Avg Internal Mark</span>
                    <x-ui.icon name="science" class="w-5 h-5 text-teal-450 bg-teal-500/10 p-2 rounded-xl" />
                  </div>
                  <div class="text-2xl font-black text-slate-900 mt-4 tracking-tight" id="statLabAvgInternal">0.00 / 75</div>
                </div>

                <!-- Card 2: Avg Board Exam Mark -->
                <div class="bg-white border border-blue-200/80 hover:border-blue-300 p-5 rounded-2xl shadow-xs transition-all duration-300 group flex flex-col justify-between">
                  <div class="flex justify-between items-start">
                    <span class="text-sm font-bold text-blue-400 uppercase tracking-wider">Avg Board Exam Mark</span>
                    <x-ui.icon name="school" class="w-5 h-5 text-blue-450 bg-blue-500/10 p-2 rounded-xl" />
                  </div>
                  <div class="text-2xl font-black text-slate-900 mt-4 tracking-tight" id="statLabAvgBoard">0.00 / 50</div>
                </div>

                <!-- Card 3: Pass Percentage -->
                <div class="bg-white border border-emerald-200/80 hover:border-emerald-300 p-5 rounded-2xl shadow-xs transition-all duration-300 group flex flex-col justify-between">
                  <div class="flex justify-between items-start">
                    <span class="text-sm font-bold text-emerald-400 uppercase tracking-wider">Pass Percentage</span>
                    <x-ui.icon name="science" class="w-5 h-5 text-emerald-450 bg-emerald-500/10 p-2 rounded-xl" />
                  </div>
                  <div class="text-2xl font-black text-slate-900 mt-4 tracking-tight" id="statLabPassPercent">0%</div>
                </div>

                <!-- Card 4: Total Experiments -->
                <div class="bg-white border border-purple-200/80 hover:border-purple-300 p-5 rounded-2xl shadow-xs transition-all duration-300 group flex flex-col justify-between">
                  <div class="flex justify-between items-start">
                    <span class="text-sm font-bold text-purple-400 uppercase tracking-wider">Total Experiments</span>
                    <x-ui.icon name="science" class="w-5 h-5 text-purple-450 bg-purple-500/10 p-2 rounded-xl" />
                  </div>
                  <div class="text-2xl font-black text-slate-900 mt-4 tracking-tight" id="statLabTotalExps">0</div>
                </div>
              </div>

              <!-- Practical Sub-Reports Quick Access -->
              <div id="practicalReportsActions" class="hidden flex-wrap gap-3.5 p-5 bg-slate-50 border border-slate-200 rounded-2xl items-center shadow-xs">
                <span class="text-sm font-black text-slate-700 uppercase tracking-wider mr-2 flex items-center gap-2">
                  <x-ui.icon name="science" class="w-4 h-4 text-blue-400" />
                  Practical Reports (A4 Landscape):
                </span>
                <div class="flex flex-wrap gap-2.5">
                  <a id="pRepBtnRegister" target="_blank" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-2xs">
                    <x-ui.icon name="grid_on" class="w-4 h-4 text-teal-400" /> Consolidated Register
                  </a>
                  <a id="pRepBtnAttendance" target="_blank" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-2xs">
                    <x-ui.icon name="science" class="w-4 h-4 text-emerald-400" /> Attendance Log
                  </a>
                  <a id="pRepBtnExperiments" target="_blank" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-2xs">
                    <x-ui.icon name="science" class="w-4 h-4 text-amber-400" /> Experiments List
                  </a>
                  <a id="pRepBtnPlanner" target="_blank" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-2xs">
                    <x-ui.icon name="calendar_today" class="w-4 h-4 text-purple-400" /> Lesson Planner
                  </a>
                  <a id="pRepBtnProjects" target="_blank" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-premium no-underline flex items-center gap-2 cursor-pointer shadow-2xs">
                    <x-ui.icon name="assignment" class="w-4 h-4 text-rose-400" /> Open-Ended Projects
                  </a>
                </div>
              </div>

              <!-- Lab Evaluation Student Grid -->
              <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse min-w-[1100px]">
                    <thead>
                      <tr class="border-b border-slate-200 text-slate-700 font-bold uppercase tracking-wider text-sm bg-slate-50">
                        <th class="p-4 w-20 text-center">Roll No</th>
                        <th class="p-4">Student Name</th>
                        <th class="p-4 text-center">Graded Exps</th>
                        <th class="p-4 text-center">Exp Avg (37.5)</th>
                        <th class="p-4 text-center">Test 1 (15)</th>
                        <th class="p-4 text-center">Test 2 (15)</th>
                        <th class="p-4 text-center">Test Avg (15)</th>
                        <th class="p-4 text-center">Project (7.5)</th>
                        <th class="p-4 text-center">Attendance (15)</th>
                        <th class="p-4 text-center text-teal-400 bg-teal-500/5">Total CA (75)</th>
                        <th class="p-4 text-center text-blue-400 bg-blue-500/5">Board Exam (50)</th>
                        <th class="p-4 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody id="labEvaluationsTableBody" class="divide-y divide-slate-100">
                      <tr>
                        <td colspan="12" class="p-8 text-center text-slate-400 font-bold text-sm">Loading students...</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Lab CO-PO Articulation Matrix Workspace -->
            <div id="labCoPoMappingContent" class="hidden flex-col h-full overflow-y-auto pr-2 pb-10 space-y-6">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-4">
                <div>
                  <h4 class="text-sm font-bold text-slate-900">CO-PO &amp; CO-PSO Mapping Articulation Matrix</h4>
                  <p class="text-sm text-slate-400 mt-1">Map each Course Outcome (CO1 - CO4) to Program Outcomes (PO1 - PO11) and Program Specific Outcomes (PSO1 - PSO3) on a scale of 1 to 3.</p>
                </div>
                <button onclick="saveCoPoMappingMatrix()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-md">
                  <x-ui.icon name="save" class="w-4 h-4" /> Save Matrix
                </button>
              </div>

              <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse min-w-[900px] text-xs">
                    <thead>
                      <tr class="bg-white border-b border-slate-200 text-slate-700 font-bold uppercase">
                        <th class="p-3 w-16">CO</th>
                        <th class="p-3">Course Outcome Statement</th>
                        <!-- POs -->
                        <th class="p-1 text-center w-12">PO1</th>
                        <th class="p-1 text-center w-12">PO2</th>
                        <th class="p-1 text-center w-12">PO3</th>
                        <th class="p-1 text-center w-12">PO4</th>
                        <th class="p-1 text-center w-12">PO5</th>
                        <th class="p-1 text-center w-12">PO6</th>
                        <th class="p-1 text-center w-12">PO7</th>
                        <th class="p-1 text-center w-12">PO8</th>
                        <th class="p-1 text-center w-12">PO9</th>
                        <th class="p-1 text-center w-12">PO10</th>
                        <th class="p-1 text-center w-12">PO11</th>
                        <!-- PSOs -->
                        <th class="p-1 text-center w-12 text-blue-300">PSO1</th>
                        <th class="p-1 text-center w-12 text-blue-300">PSO2</th>
                        <th class="p-1 text-center w-12 text-blue-300">PSO3</th>
                      </tr>
                    </thead>
                    <tbody id="labCoPoMappingTbody" class="divide-y divide-slate-850">
                      <tr>
                        <td colspan="16" class="p-8 text-center text-slate-400 font-bold">Loading articulation matrix...</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div>
