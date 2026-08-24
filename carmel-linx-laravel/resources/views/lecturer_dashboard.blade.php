<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - Faculty Portal</title>
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

  <!-- Flatpickr for Date/Time selection -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <!-- SheetJS for client-side Excel parse & generation -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  
  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
    @media (max-width: 640px) {
      .mobile-sem-btn {
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding-top: 0.625rem !important;
        padding-bottom: 0.625rem !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
      }
      .mobile-sem-btn span:first-child {
        font-size: 12px !important;
        margin-bottom: 0.125rem !important;
      }
      .mobile-sem-btn span:last-child {
        font-size: 14px !important;
      }
      #mobileSeminarNotificationsContainer h4,
      #seminarNotificationsContainer h4 {
        font-size: 16px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }

    /* CampusLynk Modern Virtual Classroom Panel Styles */
    #panelClassroom {
      background-color: #FAFAFB !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1.5rem !important;
      padding: 1.5rem !important;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05) !important;
    }

    #panelClassroom, 
    #panelClassroom button,
    #panelClassroom select,
    #panelClassroom input,
    #panelClassroom table,
    #panelClassroom th,
    #panelClassroom td,
    #panelClassroom div,
    #panelClassroom p,
    #panelClassroom h3,
    #panelClassroom h4,
    #panelClassroom h5,
    #panelClassroom span {
      font-size: 14px;
    }
    
    #panelClassroom h3#vcTitle,
    #panelClassroom h3#vcTitle span {
      font-size: 18px !important;
      font-weight: 700 !important;
    }
    
    #panelClassroom #vcSubtitle,
    #panelClassroom #vcSubtitle span {
      font-size: 14px !important;
    }
    
    #panelClassroom #vcViewStudentsBtn,
    #panelClassroom #vcViewStudentsBtn span {
      font-size: 14px !important;
      font-weight: 600 !important;
    }

    #panelClassroom .classroom-tab-btn {
      font-size: 14px !important;
      font-weight: 600 !important;
      padding: 0.5rem 0.875rem !important;
      border-radius: 0.75rem !important;
      transition: all 0.15s ease !important;
    }

    #panelClassroom h4, 
    #panelClassroom h5 {
      font-size: 16px !important;
      font-weight: 700 !important;
    }

    /* Manual mark entry table title, names, and internal grid data font sizes */
    #manualMarksWrapper table th,
    #manualMarksWrapper table td,
    #manualMarksWrapper input,
    #manualMarksWrapper span {
      font-size: 14px !important;
    }
    
    #manualMarksWrapper table td {
      padding: 12px 10px !important;
    }

    /* Flatpickr date picker light theme styling */
    .flatpickr-calendar {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1rem !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-month,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .flatpickr-weekday,
    .flatpickr-calendar .flatpickr-days .flatpickr-day {
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.prevMonthDay:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.nextMonthDay:hover {
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day.selected {
      background: #2563eb !important;
      color: #ffffff !important;
    }
    .flatpickr-calendar .flatpickr-current-month span.cur-month,
    .flatpickr-calendar .numInputWrapper span,
    .flatpickr-calendar input.numInput {
      color: #0f172a !important;
      font-weight: 600 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
      color: #2563eb !important;
      fill: #2563eb !important;
    }

    /* Mobile styles for Virtual Classroom assessment mark entry */
    @media (max-width: 767px) {
      .co-mark, .summ-mark,
      #manualMarksWrapper input,
      #markEntryTbody input,
      #summativeMarkEntryTbody input {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        color: #0f172a !important;
      }

      /* Transform assignment mark entry tables into list cards on mobile view */
      #markEntryTbody,
      #markEntryTbody tr,
      #markEntryTbody td,
      #manualMarksWrapper tbody,
      #manualMarksWrapper tr,
      #manualMarksWrapper td {
        display: block !important;
      }
      
      #panelClassroom table thead {
        display: none !important;
      }
      
      #markEntryTbody tr,
      #manualMarksWrapper table tr {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.65rem !important;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05) !important;
      }
      
      #markEntryTbody td,
      #manualMarksWrapper td {
        padding: 0.35rem 0 !important;
        border: none !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        text-align: left !important;
        font-size: 14px !important;
      }
      
      #markEntryTbody td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(5)::before { content: "CO1 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(6)::before { content: "CO2 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(7)::before { content: "CO3 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(8)::before { content: "CO4 (10): "; font-weight: bold; color: #2563eb; }
      
      #manualMarksWrapper td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(5)::before { content: "CO1: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(6)::before { content: "CO2: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(7)::before { content: "CO3: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(8)::before { content: "CO4: "; font-weight: bold; color: #2563eb; }
      
      #markEntryTbody td div.relative,
      #manualMarksWrapper td div.relative {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
      }
      #markEntryTbody td input,
      #manualMarksWrapper td input {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
        text-align: center !important;
      }

      #mobileSeminarNotificationsContainer h5,
      #seminarNotificationsContainer h5 {
        font-size: 15px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }
  </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen font-sans antialiased sidebar-preload">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Master Application Shell -->
  <div class="flex min-h-screen bg-[#FAFAFB]">

    <!-- Global Sidebar Navigation Component -->
    <x-layout.sidebar role="faculty" active="my_batches" />

    <!-- Main Viewport Container -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#FAFAFB]">
      
      <!-- Global Topbar Header Component -->
      <x-layout.topbar title="My Batches" subtitle="Assigned classes and teaching workload." />

      <!-- Scrollable Main Workspace -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
      
      <!-- PANEL 1: DASHBOARD (BATCH CARDS) -->
      <div id="panelDashboard" class="space-y-6">
        
        <!-- Seminar Presentations Today dynamic notifications section -->
        <div id="seminarNotificationsContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <!-- Populated dynamically -->
        </div>

        <div id="assignedClassroomHeader" class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white border border-slate-200 p-5 rounded-2xl gap-4 shadow-sm">
          <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900">My Assigned Batches &amp; Classrooms</h3>
            <p class="text-xs text-slate-500 mt-0.5">Select a subject to enter the virtual classroom for syllabus coverage, assignments and assessments.</p>
          </div>
          <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shadow-2xs self-stretch sm:self-auto">
            <button onclick="setDashboardBatchFilter('active')" id="btnFilterActive" class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-semibold bg-white text-blue-600 shadow-sm transition-all cursor-pointer">
              Active Batches
            </button>
            <button onclick="setDashboardBatchFilter('historical')" id="btnFilterHistorical" class="flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 transition-all cursor-pointer">
              Archived Batches
            </button>
          </div>
        </div>
        
        <div id="lecturerBatchGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="col-span-full py-16 text-center text-slate-400 font-semibold text-sm">
            <div class="w-8 h-8 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
            <span>Loading assigned batches &amp; classrooms...</span>
          </div>
        </div>
      </div>

      <!-- PANEL: VIRTUAL CLASSROOM (R2021 THEORY) -->
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

      <!-- PANEL 2: SECURITY LOG / MY PROFILE -->
      <div id="panelSecurity" class="hidden space-y-6 animate-fade-in">
        @include('partials.staff_profile_panel', ['hideAuditLog' => true])
      </div>

      <!-- PANEL: MOBILE SEMINAR EVALUATION WORKSPACE -->
      <!-- PANEL: MOBILE SEMINAR EVALUATION -->
      <div id="panelMobileSeminar" class="hidden fade-up">

        <!-- Header — NO Sign Out here, sidebar already has it -->
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-700/60">
          <button onclick="switchPanel('dashboard')" class="p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 transition-premium cursor-pointer shrink-0">
            <x-ui.icon name="science" class="w-5 h-5 text-slate-200" />
          </button>
          <div>
            <h3 class="text-lg font-black text-white flex items-center gap-2 leading-tight">
              <x-ui.icon name="co_present" class="w-5 h-5 text-blue-400" /> Virtual Seminar Room
            </h3>
            <p class="text-sm text-slate-400 mt-0.5">Evaluate student seminar presentations for today.</p>
          </div>
        </div>

        <!-- Seminar Presentations Today dynamic notifications section (Mobile Panel) -->
        <div id="mobileSeminarNotificationsContainer" class="hidden grid grid-cols-1 gap-3 mb-5">
          <!-- Populated dynamically -->
        </div>

        <!-- Mobile toast -->
        <div id="mobileSemToast" class="hidden mb-4 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2"></div>

        <!-- Step 1: Pending Invitations -->
        <div id="mobileSemStep1" class="space-y-4">

          <!-- Pending Invitations -->
          <div class="bg-white border border-amber-600/30 rounded-2xl overflow-hidden shadow-lg">
            <div class="px-5 py-4 border-b border-amber-600/20 flex items-center gap-3 bg-amber-950/20">
              <x-ui.icon name="science" class="w-5 h-5 text-amber-400" />
              <h4 class="text-base font-black text-amber-200">Pending Invitations</h4>
            </div>
            <div id="mobilePendingInvitationsList" class="p-4 space-y-3">
              <div class="text-sm text-slate-400 text-center py-4">Loading...</div>
            </div>
          </div>

          <!-- Accepted / Start Evaluation -->
          <div class="bg-white border border-emerald-700/30 rounded-2xl overflow-hidden shadow-lg">
            <div class="px-5 py-4 border-b border-emerald-700/20 flex items-center gap-3 bg-emerald-950/20">
              <x-ui.icon name="how_to_reg" class="w-5 h-5 text-emerald-400" />
              <h4 class="text-base font-black text-emerald-200">Attending Seminars</h4>
            </div>
            <div class="p-4 space-y-3">
              <div id="mobileSemAttendingList" class="space-y-2">
                <div class="text-sm text-slate-400 text-center py-4">No accepted seminars yet.</div>
              </div>
            </div>
          </div>

        </div>

        <!-- Step 2: Evaluation Form (shown when a student is selected) -->
        <div id="mobileSemStep2" class="hidden space-y-4">

          <!-- Student Info Card -->
          <div class="bg-gradient-to-br from-blue-950/80 to-indigo-950/80 border border-blue-600/40 rounded-2xl p-5 shadow-xl shadow-blue-900/20">
            <div class="flex items-start justify-between gap-3">
              <div class="flex-1 min-w-0">
                <div id="mobSemStudentName" class="text-xl font-black text-white leading-tight">-</div>
                <div class="text-sm text-slate-700 mt-1">SBTE Reg: <span id="mobSemSbteRegV2" class="font-mono text-blue-300 font-bold">-</span></div>
                <div class="mt-3 bg-blue-950/60 border border-blue-800/40 rounded-xl px-4 py-3">
                  <div class="text-xs text-blue-400 uppercase tracking-wider font-bold mb-1">Seminar Topic</div>
                  <div id="mobSemTopicV2" class="text-base font-bold text-white leading-snug">-</div>
                </div>
              </div>
              <!-- Live Score Ring -->
              <div class="shrink-0 flex flex-col items-center">
                <div class="relative w-20 h-20">
                  <svg class="w-20 h-20 -rotate-90" viewBox="0 0 64 64">
                    <circle cx="32" cy="32" r="26" fill="none" stroke="#1e293b" stroke-width="6"/>
                    <circle id="mobScoreRingCircle" cx="32" cy="32" r="26" fill="none" stroke="#3b82f6" stroke-width="6"
                      stroke-dasharray="163.36" stroke-dashoffset="163.36" stroke-linecap="round"
                      style="transition: stroke-dashoffset 0.4s ease, stroke 0.3s ease"/>
                  </svg>
                  <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span id="mobSemRingScore" class="text-lg font-black text-white leading-none">0</span>
                    <span class="text-xs text-slate-400 leading-none mt-0.5">/75</span>
                  </div>
                </div>
                <span class="text-xs text-slate-400 mt-1.5 font-bold uppercase tracking-wide">Your Score</span>
              </div>
            </div>
          </div>

          <!-- Evaluation Criteria Form -->
          <form id="mobileSeminarForm" onsubmit="submitMobileSeminarEvaluation(event)" class="space-y-3">

            <!-- Relevance -->
            <div class="bg-slate-900/70 border border-slate-700/70 rounded-2xl p-5 shadow-md">
              <div class="flex justify-between items-center mb-3">
                <div>
                  <div class="text-base font-bold text-slate-100">Relevance</div>
                  <div class="text-xs text-slate-400 mt-0.5">Topic alignment & suitability</div>
                </div>
                <div class="bg-slate-800 border border-slate-600 rounded-xl px-3 py-2 flex items-center gap-1 shadow-inner">
                  <input type="number" step="0.5" min="0" max="7.5" id="mobSemRelevance" required
                    oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                    class="w-14 bg-transparent text-white font-black text-lg text-right outline-none" placeholder="0">
                  <span class="text-slate-400 text-sm font-bold">/7.5</span>
                </div>
              </div>
              <input type="range" min="0" max="7.5" step="0.5" value="0"
                oninput="document.getElementById('mobSemRelevance').value=this.value; calcMobSemTotal()"
                class="w-full h-3 rounded-full accent-blue-500 bg-slate-700 cursor-pointer">
            </div>

            <!-- Literature -->
            <div class="bg-slate-900/70 border border-slate-700/70 rounded-2xl p-5 shadow-md">
              <div class="flex justify-between items-center mb-3">
                <div>
                  <div class="text-base font-bold text-slate-100">Literature Survey</div>
                  <div class="text-xs text-slate-400 mt-0.5">Depth of research & references</div>
                </div>
                <div class="bg-slate-800 border border-slate-600 rounded-xl px-3 py-2 flex items-center gap-1 shadow-inner">
                  <input type="number" step="0.5" min="0" max="7.5" id="mobSemLiterature" required
                    oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                    class="w-14 bg-transparent text-white font-black text-lg text-right outline-none" placeholder="0">
                  <span class="text-slate-400 text-sm font-bold">/7.5</span>
                </div>
              </div>
              <input type="range" min="0" max="7.5" step="0.5" value="0"
                oninput="document.getElementById('mobSemLiterature').value=this.value; calcMobSemTotal()"
                class="w-full h-3 rounded-full accent-indigo-500 bg-slate-700 cursor-pointer">
            </div>

            <!-- Presentation (largest weight) -->
            <div class="bg-slate-900/70 border border-blue-600/40 rounded-2xl p-5 shadow-md">
              <div class="flex justify-between items-center mb-3">
                <div>
                  <div class="text-base font-bold text-blue-300">Presentation Quality</div>
                  <div class="text-xs text-slate-400 mt-0.5">Clarity, structure & delivery — highest weight</div>
                </div>
                <div class="bg-slate-800 border border-blue-700/50 rounded-xl px-3 py-2 flex items-center gap-1 shadow-inner">
                  <input type="number" step="0.5" min="0" max="37.5" id="mobSemPresentation" required
                    oninput="clampMobSem(this,37.5); calcMobSemTotal()"
                    class="w-16 bg-transparent text-blue-300 font-black text-lg text-right outline-none" placeholder="0">
                  <span class="text-slate-400 text-sm font-bold">/37.5</span>
                </div>
              </div>
              <input type="range" min="0" max="37.5" step="0.5" value="0"
                oninput="document.getElementById('mobSemPresentation').value=this.value; calcMobSemTotal()"
                class="w-full h-3 rounded-full accent-blue-400 bg-slate-700 cursor-pointer">
            </div>

            <!-- Last 3 criteria in a row -->
            <div class="grid grid-cols-3 gap-3">
              <!-- Interaction -->
              <div class="bg-slate-900/70 border border-purple-700/30 rounded-2xl p-3.5 flex flex-col items-center gap-2 shadow-md">
                <div class="text-xs font-black text-purple-300 uppercase tracking-wide text-center">Interaction</div>
                <div class="text-xs text-slate-400 text-center">Q&A</div>
                <input type="number" step="0.5" min="0" max="7.5" id="mobSemInteraction" required
                  oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                  class="w-full bg-slate-800 border border-purple-700/40 rounded-xl px-2 py-2.5 text-white font-black text-base text-center outline-none focus:border-purple-400 transition-premium">
                <div class="text-xs text-slate-500 font-bold">max 7.5</div>
              </div>
              <!-- Report -->
              <div class="bg-slate-900/70 border border-teal-700/30 rounded-2xl p-3.5 flex flex-col items-center gap-2 shadow-md">
                <div class="text-xs font-black text-teal-300 uppercase tracking-wide text-center">Report</div>
                <div class="text-xs text-slate-400 text-center">Written</div>
                <input type="number" step="0.5" min="0" max="7.5" id="mobSemReport" required
                  oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                  class="w-full bg-slate-800 border border-teal-700/40 rounded-xl px-2 py-2.5 text-white font-black text-base text-center outline-none focus:border-teal-400 transition-premium">
                <div class="text-xs text-slate-500 font-bold">max 7.5</div>
              </div>
              <!-- Attendance -->
              <div class="bg-slate-900/70 border border-emerald-700/30 rounded-2xl p-3.5 flex flex-col items-center gap-2 shadow-md">
                <div class="text-xs font-black text-emerald-300 uppercase tracking-wide text-center">Attendance</div>
                <div class="text-xs text-slate-400 text-center">Presence</div>
                <input type="number" step="0.5" min="0" max="7.5" id="mobSemAttendance" required
                  oninput="clampMobSem(this,7.5); calcMobSemTotal()"
                  class="w-full bg-slate-800 border border-emerald-700/40 rounded-xl px-2 py-2.5 text-white font-black text-base text-center outline-none focus:border-emerald-400 transition-premium">
                <div class="text-xs text-slate-500 font-bold">max 7.5</div>
              </div>
            </div>

            <!-- Total + Submit -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-950 border border-slate-600/60 rounded-2xl p-5 flex items-center justify-between gap-4 shadow-xl">
              <div>
                <div class="text-sm text-slate-400 font-bold uppercase tracking-wider mb-1">Total Score</div>
                <div class="text-xl font-black text-slate-500" id="mobSemTotalDisplay">
                  <span id="mobSemTotalNum" class="text-blue-400">0.00</span> / 75
                </div>
                <!-- keep old ID for backward compat -->
                <div id="mobSemTotalScoreLabel" class="hidden"></div>
              </div>
              <button type="submit" id="mobSemSubmitBtn"
                class="px-6 py-4 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white rounded-xl font-black text-base shadow-lg shadow-blue-500/30 transition-premium cursor-pointer flex items-center gap-2">
                <x-ui.icon name="save" class="w-5 h-5" /> Save
              </button>
            </div>

            <button type="button" onclick="backToSeminarList()" class="w-full py-3.5 text-slate-700 text-sm font-bold flex items-center justify-center gap-2 cursor-pointer hover:text-white transition-premium border border-slate-700/50 rounded-xl hover:bg-slate-800/50">
              <x-ui.icon name="science" class="w-4 h-4" /> Back to Seminar List
            </button>

          </form>
        </div>

      </div>
    </main>
  </div>
</div>

  <script>
    // Self-executing theme preference loader to run immediately and prevent flashing dark theme
    (function() {
      const savedTheme = localStorage.getItem('theme-preference');
      if (savedTheme === 'light') {
        document.body.classList.add('light-theme');
        window.addEventListener('DOMContentLoaded', () => {
          const icon = document.getElementById('themeToggleIcon');
          const text = document.getElementById('themeToggleText');
          if (icon) icon.innerText = 'dark_mode';
          if (text) text.innerText = 'Dark Mode';
        });
      }
    })();

    function toggleTheme() {
      const body = document.body;
      const isLight = body.classList.toggle('light-theme');
      localStorage.setItem('theme-preference', isLight ? 'light' : 'dark');
      
      const icon = document.getElementById('themeToggleIcon');
      const text = document.getElementById('themeToggleText');
      if (isLight) {
        if (icon) icon.innerText = 'dark_mode';
        if (text) text.innerText = 'Dark Mode';
      } else {
        if (icon) icon.innerText = 'light_mode';
        if (text) text.innerText = 'Light Mode';
      }
    }

    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      if (sessionStorage.getItem('openClassroomFromHOD') === 'true') {
        sessionStorage.removeItem('openClassroomFromHOD');
        // Instantly force load active batches list
        switchPanel('dashboard');
      }
      const urlParams = new URLSearchParams(window.location.search);
      const subjectId = urlParams.get('subject_id');
      const subjectName = urlParams.get('subject_name');
      const classroomId = urlParams.get('classroom_id');
      const panelParam = urlParams.get('panel') || urlParams.get('tab');

      if (panelParam === 'security' || panelParam === 'profile') {
        switchPanel('security');
      } else if (subjectId) {
        openClassroom(classroomId, subjectId, subjectName);
      } else {
        loadLecturerBatches();
      }
      if (activePanel === 'security') loadSecurityLogs();
      checkTodaySeminars();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'security', 'classroom', 'mobileSeminar'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const navMap = {
        'dashboard': 'my_batches',
        'security': 'profile',
        'classroom': 'my_batches',
        'mobileSeminar': 'my_batches'
      };
      if (typeof window.selectSidebarNav === 'function') {
        window.selectSidebarNav(navMap[panelId] || panelId);
      }

      const titles = {
        'dashboard': 'My Batches',
        'security': 'My Profile',
        'classroom': 'Virtual Classroom',
        'mobileSeminar': 'Seminar Evaluation'
      };
      
      const titleEl = document.getElementById('panelTitle') || document.querySelector('.topbar-title') || document.querySelector('header h1');
      if (titleEl) titleEl.innerText = titles[panelId] || 'My Batches';

      if (panelId === 'security') loadSecurityLogs();
      if (panelId === 'dashboard') loadLecturerBatches();
    }

    let currentDashboardFilter = 'active';

    function setDashboardBatchFilter(status) {
      currentDashboardFilter = status;
      
      const activeBtn = document.getElementById('btnFilterActive');
      const historicalBtn = document.getElementById('btnFilterHistorical');

      if (status === 'active') {
        if (activeBtn) activeBtn.className = 'flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-semibold bg-white text-blue-600 shadow-sm transition-all cursor-pointer';
        if (historicalBtn) historicalBtn.className = 'flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 transition-all cursor-pointer';
      } else {
        if (activeBtn) activeBtn.className = 'flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 transition-all cursor-pointer';
        if (historicalBtn) historicalBtn.className = 'flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-semibold bg-white text-blue-600 shadow-sm transition-all cursor-pointer';
      }

      loadLecturerBatches();
    }

    function loadLecturerBatches() {
      const grid = document.getElementById('lecturerBatchGrid');
      grid.innerHTML = `
        <div class="col-span-full py-16 text-center text-slate-400 font-semibold text-sm">
          <div class="w-8 h-8 border-2 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
          <span>Loading assigned batches &amp; classrooms...</span>
        </div>
      `;

      fetch(`/api/lecturer/my-batches?status=${currentDashboardFilter}`, {
        headers: { 'Content-Type': 'application/json' }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          renderBatchCards(data.batches);
        } else {
          grid.innerHTML = `<div class="col-span-full p-6 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">${data.message || 'Failed to load batches.'}</div>`;
        }
      })
      .catch(() => {
        grid.innerHTML = `<div class="col-span-full p-6 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-semibold">Error communicating with server while loading batches.</div>`;
      });
    }

    function renderBatchCards(batches) {
      const grid = document.getElementById('lecturerBatchGrid');
      grid.innerHTML = '';

      if (batches.length === 0) {
        grid.innerHTML = `
          <div class="col-span-full bg-white border border-slate-200 p-12 rounded-3xl text-center shadow-sm max-w-xl mx-auto space-y-3">
            <div class="w-14 h-14 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto">
              <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </div>
            <h4 class="font-bold text-slate-900 text-base">No batches found</h4>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">You do not have any assigned classes or subjects under the <strong>${currentDashboardFilter === 'active' ? 'Active' : 'Archived'}</strong> filter.</p>
          </div>
        `;
        return;
      }

      batches.forEach(b => {
        let rolesHtml = '';
        b.roles.forEach(r => {
          let badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
          if (r === 'Tutor') badgeClass = 'bg-sky-50 text-sky-700 border-sky-200';
          if (r === 'Mentor') badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
          if (r === 'Subject Staff') badgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200';
          if (r === 'Executive Supervision') badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
          rolesHtml += `<span class="px-2.5 py-0.5 rounded-full text-xs font-bold border ${badgeClass}">${r}</span>`;
        });

        const isGraduated = (b.current_semester || 1) > 6;
        const semBadge = isGraduated
          ? `<span class="px-2.5 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-full font-bold text-xs flex items-center gap-1"><svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>Graduated</span>`
          : `<span class="px-2.5 py-0.5 bg-blue-50 border border-blue-200 text-blue-700 rounded-full font-bold text-xs font-mono">S-${b.current_semester || 1}</span>`;

        let subjectsHtml = '';
        if (b.subjects && b.subjects.length > 0) {
          subjectsHtml = b.subjects.map(s => {
            let topicsPct = s.total_topics > 0 ? Math.round((s.covered_topics / s.total_topics) * 100) : 0;
            let hoursPct  = s.total_hours  > 0 ? Math.round((s.engaged_hours  / s.total_hours)  * 100) : 0;
            let barPct    = topicsPct || hoursPct;
            let barColor  = barPct >= 80 ? 'from-emerald-500 to-teal-500' : barPct >= 50 ? 'from-blue-600 to-indigo-600' : 'from-violet-600 to-purple-600';

            const safeName = escapeQuotes(s.name);
            const safeCode = escapeQuotes(s.code);
            const revision = s.syllabus_revision_code || 'REV2021';

            return `
              <div onclick="openClassroom('${b.classroom_id}', '${s.id}', '${safeName}', '${safeCode}', '${revision}', '${s.type}')" class="w-full p-4 bg-slate-50 hover:bg-blue-50/50 border border-slate-200 hover:border-blue-300 rounded-2xl transition-all cursor-pointer group flex flex-col gap-2.5 shadow-2xs">
                <div class="flex justify-between items-start gap-2">
                  <div class="flex-1 min-w-0">
                    <h5 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug break-words">${s.name}</h5>
                    <div class="text-xs text-slate-500 font-mono mt-0.5 flex items-center gap-1.5 flex-wrap">
                      <span>Sem ${s.semester}</span>
                      <span>•</span>
                      <span>${s.type}</span>
                      <span>•</span>
                      <span class="font-semibold text-slate-700">${s.code}</span>
                    </div>
                  </div>
                  <div class="w-7 h-7 rounded-lg bg-white group-hover:bg-blue-600 group-hover:text-white text-slate-400 border border-slate-200 group-hover:border-blue-600 flex items-center justify-center transition-all shrink-0 shadow-2xs">
                    <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                  </div>
                </div>
                
                <!-- Progress bar -->
                <div class="flex items-center gap-2.5 pt-1 border-t border-slate-100">
                  <div class="flex-1 bg-slate-200 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-gradient-to-r ${barColor} h-1.5 rounded-full transition-all duration-500" style="width: ${barPct}%"></div>
                  </div>
                  <span class="text-xs font-bold text-slate-600 font-mono shrink-0">${s.engaged_hours}/${s.total_hours} hrs</span>
                </div>
              </div>
            `;
          }).join('');
        } else {
          subjectsHtml = `<div class="text-xs text-slate-400 italic py-4 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">No subjects assigned in this batch.</div>`;
        }

        const card = document.createElement('div');
        card.className = "bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col h-[460px]";
        card.innerHTML = `
          <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col gap-3 shrink-0">
            <div class="flex justify-between items-start gap-2">
              <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                  <h4 class="font-bold text-slate-900 text-base">Admission ${b.batch_year}</h4>
                  ${semBadge}
                </div>
                <span class="inline-block px-2.5 py-0.5 bg-white border border-slate-200 rounded-lg font-mono text-xs font-bold text-slate-700">${b.classroom_id}</span>
              </div>
              <div class="flex flex-col items-end gap-1.5">
                <div class="flex flex-wrap gap-1 justify-end">${rolesHtml}</div>
                <span class="flex items-center gap-1 text-xs font-semibold text-slate-500">
                  <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                  <span>${b.student_count || 0} students</span>
                </span>
              </div>
            </div>
          </div>

          <div class="p-5 flex-1 flex flex-col min-h-0 bg-white space-y-3">
            <div class="flex items-center justify-between pb-1 shrink-0">
              <h5 class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                <span>Assigned Subjects</span>
              </h5>
              <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">${(b.subjects || []).length} Total</span>
            </div>
            <div class="space-y-2.5 overflow-y-auto flex-1 pr-1.5 scrollbar-hidden">
              ${subjectsHtml}
            </div>
          </div>
        `;
        grid.appendChild(card);
      });

      if (window.initLucide) window.initLucide();
    }

    function escapeQuotes(str) {
      if (!str) return '';
      return String(str).replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    let currentSubjectId = null;
    window.currentVirtualBatchId = '';
    window.currentVirtualSemester = '';

    function openClassroom(batchId, subjectId, subjectName, subjectCode, revision = 'REV2021', type = 'Theory') {
      if (revision === 'REV2026') {
        const sNameLower = (subjectName || '').toLowerCase();
        const sTypeLower = (type || '').toLowerCase();
        if (sNameLower.includes('health') || sNameLower.includes('physical') || sTypeLower.includes('health') || sTypeLower.includes('physical')) {
          window.open(`/r26/classroom/health-physical/${subjectId}`, '_blank');
          return;
        } else if (sTypeLower.includes('drawing') || sNameLower.includes('drawing') || sNameLower.includes('graphics') || sNameLower.includes('cad')) {
          window.open(`/r26/classroom/drawing/${subjectId}`, '_blank');
          return;
        } else if (type.includes('Practicum')) {
          window.open(`/r26/classroom/practicum/${subjectId}`, '_blank');
          return;
        } else if (type.includes('Theory')) {
          window.open(`/r26/classroom/theory/${subjectId}`, '_blank');
          return;
        } else if (type.includes('Practical') || type.includes('Lab')) {
          window.open(`/r26/classroom/practical/${subjectId}`, '_blank');
          return;
        }
      }
      currentSubjectId = subjectId;
      window.currentVirtualBatchId = batchId;
      document.getElementById('vcTitle').innerText = subjectName || 'Virtual Classroom';
      let latText = '';
      if (batchId.includes('_LET')) {
        latText = ' <span class="bg-purple-900/60 border border-purple-500/50 text-purple-300 font-extrabold text-xs px-2.5 py-1 rounded-full shadow-inner ml-2">LATERAL ENTRY (LET)</span>';
      }
      document.getElementById('vcSubtitle').innerHTML = `Batch: ${batchId}${latText}`;
      // Show subject name and code immediately near the upload button (before API loads)
      const vcSubName = document.getElementById('vcSubjectName');
      const vcSubCode = document.getElementById('vcSubjectCode');
      const vcSubInfo = document.getElementById('vcSubjectInfo');
      if (vcSubName) vcSubName.innerText = subjectName || '';
      if (vcSubCode) vcSubCode.innerText = subjectCode || '';
      if (vcSubInfo) vcSubInfo.style.display = (subjectName || subjectCode) ? 'flex' : 'none';
      switchPanel('classroom');
      loadCourseDetails(subjectId);
    }

    function handleSyllabusUpload(input) {
      if (!input.files || input.files.length === 0) return;
      if (!currentSubjectId) return;

      const file = input.files[0];
      const formData = new FormData();
      formData.append('syllabus_file', file);
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

      document.getElementById('syllabusUploadBox').classList.add('hidden');
      document.getElementById('syllabusUploadProgress').classList.remove('hidden');
      document.getElementById('parseStatusBadge').innerText = 'Extracting...';
      document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs whitespace-nowrap';

      fetch(`/api/classroom/${currentSubjectId}/syllabus`, {
        method: 'POST',
        credentials: 'same-origin',
        body: formData
      })
      .then(res => {
        if (!res.ok) throw new Error('Server error: ' + res.status);
        return res.json();
      })
      .then(data => {
        document.getElementById('syllabusUploadBox').classList.remove('hidden');
        document.getElementById('syllabusUploadProgress').classList.add('hidden');
        // Reset file input so same file can be re-uploaded
        document.getElementById('syllabusFileInput').value = '';
        if (data.status === 'SUCCESS') {
          document.getElementById('parseStatusBadge').innerText = 'Parsed & Synced ✓';
          document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs whitespace-nowrap';
          // Reload course details — it will auto-switch to Course Structure tab
          loadCourseDetails(currentSubjectId);
        } else {
          alert(data.message || 'Upload failed.');
          document.getElementById('parseStatusBadge').innerText = 'Upload Failed';
          document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 border border-rose-200/80 shadow-2xs whitespace-nowrap';
        }
      })
      .catch(err => {
        document.getElementById('syllabusUploadBox').classList.remove('hidden');
        document.getElementById('syllabusUploadProgress').classList.add('hidden');
        document.getElementById('syllabusFileInput').value = '';
        document.getElementById('parseStatusBadge').innerText = 'Upload Error';
        document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 border border-rose-200/80 shadow-2xs whitespace-nowrap';
        alert('Failed to upload syllabus: ' + err.message);
      });
    }

    function toggleClassroomTab(tabName) {
      const tabs = [
        { id: 'structure', btn: 'tabStructure', content: 'courseStructureContent' },
        { id: 'planner', btn: 'tabPlanner', content: 'coursePlannerContent' },
        { id: 'assessment', btn: 'tabAssessment', content: 'formativeAssessmentContent' },
        { id: 'summative', btn: 'tabSummative', content: 'summativeAssessmentContent' },
        { id: 'reports', btn: 'tabReports', content: 'classReportsContent' },
        { id: 'qbank', btn: 'tabQBank', content: 'questionBankContent' },
        { id: 'survey', btn: 'tabSurvey', content: 'midSemesterSurveyContent' },
        { id: 'exit_survey', btn: 'tabExitSurvey', content: 'courseExitSurveyContent' },
        { id: 'seminar_evaluation', btn: 'tabSeminar', content: 'seminarEvaluationContent' },
        { id: 'lab_evaluation', btn: 'tabLab', content: 'labEvaluationContent' },
        { id: 'lab_copo', btn: 'tabLabCoPo', content: 'labCoPoMappingContent' }
      ];

      tabs.forEach(t => {
        const btn = document.getElementById(t.btn);
        const content = document.getElementById(t.content);
        
        if (t.id === tabName) {
          if (btn) {
            btn.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-200/80', 'shadow-2xs');
            btn.classList.remove('border-transparent', 'text-slate-600', 'hover:bg-slate-50');
          }
          if (content) {
            content.classList.remove('hidden');
            if (t.id !== 'structure') content.classList.add('flex');
          }
        } else {
          if (btn) {
            btn.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-200/80', 'shadow-2xs');
            btn.classList.add('border-transparent', 'text-slate-600', 'hover:bg-slate-50');
          }
          if (content) {
            content.classList.add('hidden');
            if (t.id !== 'structure') content.classList.remove('flex');
          }
        }
      });

      if (tabName === 'reports') {
        fetchClassReports();
      } else if (tabName === 'qbank') {
        fetchQuestionBank(currentSubjectId);
      } else if (tabName === 'survey') {
        fetchSurveyResults(currentSubjectId);
      } else if (tabName === 'exit_survey') {
        fetchExitSurveyResults(currentSubjectId);
      } else if (tabName === 'seminar_evaluation') {
        fetchSeminarEvaluations();
      } else if (tabName === 'lab_evaluation') {
        fetchPracticalEvaluations();
      } else if (tabName === 'lab_copo') {
        fetchPracticalCoPoMapping();
      }
    }

    let classReportsData = null;
    let activeReportType = 'attendance_log';
    let currentDeadlines = {};
    let currentQuestions = {};
    let currentSummativeTests = {};
    let currentSubjectName = '';
    let currentSubjectCode = '';
    let currentSubjectSemester = '';
    let currentSubjectAcademicYear = '';
    let currentSubjectClassroomId = '';

    function loadCourseDetails(subjectId) {
      currentSubjectId = subjectId;
      document.getElementById('courseStructureContent').innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-[10px] font-bold text-slate-400">Loading course data...</p>
        </div>
      `;
      document.getElementById('coursePlannerContent').innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-[10px] font-bold text-slate-400">Loading planner...</p>
        </div>
      `;
      document.getElementById('surveyWorkspace').innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Loading survey details...</p>
        </div>
      `;
      document.getElementById('activeSyllabusCard').classList.add('hidden');
      // Note: vcSubjectInfo is set by openClassroom immediately - don't hide it during load
      document.getElementById('parseStatusBadge').innerText = 'Syncing...';
      document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-2.5 py-1 rounded-md bg-blue-900/30 text-blue-400 border border-blue-500/30';

      return fetch(`/api/classroom/${subjectId}/details`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS' && data.data) {
          currentDeadlines = data.data.assignment_deadlines || {};
          currentQuestions = data.data.assignment_questions || {};
          currentSummativeTests = data.data.summative_manual_tests || {};
          currentSubjectName = data.data.subject_name || '';
          currentSubjectCode = data.data.subject_code || '';
          const vcSubName = document.getElementById('vcSubjectName');
          const vcSubCode = document.getElementById('vcSubjectCode');
          const vcSubInfo = document.getElementById('vcSubjectInfo');
          const vcPropHours = document.getElementById('vcSyllabusProposedHours');
          if (vcSubName) vcSubName.innerText = currentSubjectName;
          if (vcSubCode) vcSubCode.innerText = currentSubjectCode;
          if (vcPropHours) {
              const pHours = data.data.proposed_total_hours || 60;
              vcPropHours.innerText = `Proposed Hours: ${pHours} hrs (+2 tests)`;
          }
          if (vcSubInfo) vcSubInfo.style.display = (currentSubjectName || currentSubjectCode) ? 'flex' : 'none';
          currentSubjectSemester = data.data.semester || '';
          currentSubjectAcademicYear = data.data.academic_year || '';
          currentSubjectClassroomId = data.data.classroom_id || '';
          window.currentSyllabusRevision = data.data.syllabus_revision || '2021';
          window.currentVirtualStudents = data.data.students || [];
          window.currentVirtualSemester = data.data.semester || '';
          window.currentProposedTotalHours = data.data.proposed_total_hours || 60;
          
          renderCourseStructure(data.data.cos, data.data.modules, data.data.textbooks, data.data.copo);
          renderCoursePlanner(data.data.lesson_plans);
          renderFormativeAssessment(data.data.students || []);
          renderSummativeAssessment(data.data.cos, data.data.students || []);
          loadActiveOnlineTests(subjectId);
          
          // Always render the formative questions section (show prompt if none generated yet)
          renderAIQuestionsList(currentQuestions, subjectId);

          const subjectTypeRaw = (data.data.subject_type || '').toLowerCase();
          const isSeminar = subjectTypeRaw === 'seminar';
          const isPractical = subjectTypeRaw === 'practical' || subjectTypeRaw === 'lab' || subjectTypeRaw.includes('lab') || subjectTypeRaw.includes('practical') || subjectTypeRaw.includes('practicum');
          window.isCurrentSubjectPractical = isPractical;

          const tabSeminar = document.getElementById('tabSeminar');
          const tabLab = document.getElementById('tabLab');
          const tabLabCoPo = document.getElementById('tabLabCoPo');
          const tabStructure = document.getElementById('tabStructure');
          const tabPlanner = document.getElementById('tabPlanner');
          const tabAssessment = document.getElementById('tabAssessment');
          const tabSummative = document.getElementById('tabSummative');
          const tabReports = document.getElementById('tabReports');
          const pRepActions = document.getElementById('practicalReportsActions');

          if (isSeminar) {
            document.getElementById('panelTitle').innerText = 'Virtual Seminar Room';
            document.getElementById('vcTitle').innerHTML = `<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h20"/><path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/><path d="m7 21 5-5 5 5"/></svg> Virtual Seminar Room`;
            if (tabSeminar) tabSeminar.classList.remove('hidden');
            if (tabLab) tabLab.classList.add('hidden');
            if (tabLabCoPo) tabLabCoPo.classList.add('hidden');
            if (tabStructure) tabStructure.classList.add('hidden');
            if (tabPlanner) tabPlanner.classList.add('hidden');
            if (tabAssessment) tabAssessment.classList.add('hidden');
            if (tabSummative) tabSummative.classList.add('hidden');
            if (pRepActions) pRepActions.classList.add('hidden');
            toggleClassroomTab('seminar_evaluation');
          } else if (isPractical) {
            document.getElementById('panelTitle').innerText = 'Virtual Lab Workspace';
            document.getElementById('vcTitle').innerHTML = `<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.31L4.75 20.46A1 1 0 0 0 5.64 22h12.72a1 1 0 0 0 .89-1.54L14 9.31V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/></svg> Virtual Lab Workspace`;
            if (tabSeminar) tabSeminar.classList.add('hidden');
            if (tabLab) tabLab.classList.remove('hidden');
            if (tabLabCoPo) tabLabCoPo.classList.remove('hidden');
            if (tabStructure) tabStructure.classList.remove('hidden');
            if (tabPlanner) tabPlanner.classList.remove('hidden');
            if (tabAssessment) tabAssessment.classList.add('hidden');
            if (tabSummative) tabSummative.classList.add('hidden');
            if (tabReports) tabReports.classList.remove('hidden');
            if (pRepActions) {
              pRepActions.classList.remove('hidden');
              pRepActions.classList.add('flex');
              document.getElementById('pRepBtnRegister').href = `/classroom/${subjectId}/practical-report/print?type=register`;
              document.getElementById('pRepBtnAttendance').href = `/classroom/${subjectId}/practical-report/print?type=attendance`;
              document.getElementById('pRepBtnExperiments').href = `/classroom/${subjectId}/practical-report/print?type=experiments`;
              document.getElementById('pRepBtnPlanner').href = `/classroom/${subjectId}/practical-report/print?type=planner`;
              document.getElementById('pRepBtnProjects').href = `/classroom/${subjectId}/practical-report/print?type=projects`;
            }
            toggleClassroomTab('lab_evaluation');
          } else {
            document.getElementById('panelTitle').innerText = 'Virtual Classroom';
            document.getElementById('vcTitle').innerHTML = `<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> Virtual Classroom`;
            if (tabSeminar) tabSeminar.classList.add('hidden');
            if (tabLab) tabLab.classList.add('hidden');
            if (tabLabCoPo) tabLabCoPo.classList.add('hidden');
            if (tabStructure) tabStructure.classList.remove('hidden');
            if (tabPlanner) tabPlanner.classList.remove('hidden');
            if (tabAssessment) tabAssessment.classList.remove('hidden');
            if (tabSummative) tabSummative.classList.remove('hidden');
            if (pRepActions) pRepActions.classList.add('hidden');
            toggleClassroomTab('structure');
          }

          // Update vcTitle to include subject name for regular classrooms
          if (!isSeminar && !isPractical) {
            document.getElementById('vcTitle').innerHTML = `<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> ${currentSubjectName || 'Virtual Classroom'}`;
          }

          if (data.data.syllabus_pdf_path) {
            document.getElementById('activeSyllabusCard').classList.remove('hidden');
            const dlBtn = document.getElementById('downloadSyllabusBtn');
            if (dlBtn) dlBtn.dataset.url = `/api/classroom/${subjectId}/syllabus/download`;
            document.getElementById('parseStatusBadge').innerText = 'Parsed & Synced';
            document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs whitespace-nowrap';
          } else {
            document.getElementById('parseStatusBadge').innerText = 'Waiting for upload';
            document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap shadow-2xs';
          }
        } else {
          document.getElementById('parseStatusBadge').innerText = 'Waiting for upload';
          document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap shadow-2xs';
          document.getElementById('courseStructureContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              </div>
              <p class="text-sm font-bold text-slate-400">No syllabus loaded.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus PDF to automatically populate Course Outcomes, Modules, and Textbooks.</p>
            </div>
          `;
          document.getElementById('coursePlannerContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              </div>
              <p class="text-sm font-bold text-slate-400">Planner not generated.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to automatically generate the lesson plan.</p>
            </div>
          `;
          document.getElementById('formativeAssessmentContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
              </div>
              <p class="text-sm font-bold text-slate-400">Formative Assessment Inactive.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate formative assessment tasks and mark entry.</p>
            </div>
          `;
          document.getElementById('summativeAssessmentContent').innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
              <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              </div>
              <p class="text-sm font-bold text-slate-400">Summative Assessment Inactive.</p>
              <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate written test configuration and mark entry.</p>
            </div>
          `;
          const qbContent = document.getElementById('questionBankContent');
          if (qbContent) {
              qbContent.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500 h-full">
                  <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
                  </div>
                  <p class="text-sm font-bold text-slate-400">Question Bank Inactive.</p>
                  <p class="text-sm mt-1.5 max-w-xs text-slate-500 leading-relaxed">Upload a syllabus to activate the question bank pooling.</p>
                </div>
              `;
          }
        }
      })
      .catch(err => {
        console.error('[loadCourseDetails] Error:', err);
        document.getElementById('parseStatusBadge').innerText = 'Load Error';
        document.getElementById('parseStatusBadge').className = 'text-xs font-bold px-2.5 py-1 rounded-md bg-red-900/30 text-red-400 border border-red-500/30';
        document.getElementById('courseStructureContent').innerHTML = `
          <div class="flex flex-col items-center justify-center py-16 text-center h-full">
            <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            <p class="text-sm font-bold text-red-400">Failed to load course data</p>
            <p class="text-xs text-slate-500 mt-1.5 max-w-xs">${err.message}</p>
            <button onclick="loadCourseDetails(currentSubjectId)" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-xl cursor-pointer transition-premium">
              Retry
            </button>
          </div>
        `;
      });
    }

    /**
     * Opens the syllabus PDF by opening the controller URL via a hidden anchor click.
     * This keeps the session cookie active (same-origin) and avoids popup blockers.
     */
    function downloadSyllabusPDF() {
      const btn = document.getElementById('downloadSyllabusBtn');
      if (!btn) return;
      const url = btn.dataset.url;
      if (!url) { alert('No syllabus attached to this subject yet.'); return; }
      // Use a hidden anchor element — same-origin navigation, no popup blocker
      const a = document.createElement('a');
      a.href = url;
      a.target = '_blank';
      a.rel = 'noopener noreferrer';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }

    // CO colour palette for lesson planner badges
    const CO_COLORS = {
      'CO1': 'bg-blue-50 text-blue-700 border-blue-200',
      'CO2': 'bg-violet-50 text-violet-700 border-violet-200',
      'CO3': 'bg-emerald-50 text-emerald-700 border-emerald-200',
      'CO4': 'bg-amber-50 text-amber-700 border-amber-200',
      'CO5': 'bg-rose-50 text-rose-700 border-rose-200',
      'CO6': 'bg-cyan-50 text-cyan-700 border-cyan-200',
    };

    function renderCoursePlanner(lessonPlans) {
      const container = document.getElementById('coursePlannerContent');
      if (!container) return;

      // ── Empty state ──────────────────────────────────────────────────────────
      if (!lessonPlans || lessonPlans.length === 0) {
        let emptyIcon  = window.isCurrentSubjectPractical ? 'science' : 'event_note';
        let emptyColor = window.isCurrentSubjectPractical ? 'text-teal-400' : 'text-sky-400';
        let genBtn = window.isCurrentSubjectPractical
          ? `<button onclick="openGeneratePlannerModal()" class="px-4 py-2 bg-gradient-to-r from-teal-600 to-emerald-500 hover:from-teal-500 hover:to-emerald-400 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-teal-900/20">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg> Auto-Generate (Lab)
             </button>`
          : `<button onclick="regenerateLessonPlan()" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-lg shadow-blue-900/20">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg> Generate Lesson Plan
             </button>`;
        let loadBtn = `<button onclick="loadLessonPlanTemplate()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-bold transition-premium cursor-pointer border border-slate-700/50 flex items-center gap-1.5">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Load Template
            </button>`;
        container.innerHTML = `
          <div class="flex flex-col items-center justify-center py-16 text-center h-full">
            <div class="bg-slate-50 p-4 rounded-full mb-4 border border-slate-200">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
            </div>
            <p class="text-sm font-bold text-slate-400">No Lesson Plan Generated Yet</p>
            <p class="text-xs mt-1.5 max-w-xs text-slate-500 leading-relaxed mb-6">
              Generate a smart plan based on Course Outcomes and Modules, or load a saved template.
            </p>
            <div class="flex items-center gap-3 flex-wrap justify-center">
              ${genBtn}
              ${loadBtn}
            </div>
          </div>
        `;
        return;
      }

      // ── Populated state ──────────────────────────────────────────────────────
      let totalHours = lessonPlans.reduce((sum, lp) => sum + (lp.allocated_hours || 0), 0);
      let testDays   = lessonPlans.filter(lp => (lp.pedagogy || '').toLowerCase() === 'test').length;
      let lectureDays = lessonPlans.length - testDays;
      let proposedVal = window.currentProposedTotalHours || 60;

      // Header buttons
      let practicalRegenBtn = window.isCurrentSubjectPractical
        ? `<button onclick="openGeneratePlannerModal()" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs">
             <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 2v7.31L4.75 20.46A1 1 0 0 0 5.64 22h12.72a1 1 0 0 0 .89-1.54L14 9.31V2"/><path d="M8.5 2h7"/><path d="M7 16h10"/></svg> Regenerate (Lab)
           </button>` : '';

      let html = `
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4 pb-3 border-b border-slate-200">
          <div>
            <h4 class="text-sm font-bold text-slate-800">Lesson Planner</h4>
            <p class="text-xs text-slate-500 mt-0.5">${lectureDays} lecture days · ${testDays} test days · ${totalHours} total hours (Syllabus Proposed: ${proposedVal} hours) · Click any topic to edit inline</p>
          </div>
          <div class="flex items-center gap-2 flex-wrap">
            ${practicalRegenBtn}
            <button onclick="regenerateLessonPlan()" id="btnRegenPlan" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs" title="Re-generate all lesson plans from stored syllabus data">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg> Regenerate
            </button>
            <button onclick="saveLessonPlanChanges()" id="btnSavePlan" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save Changes
            </button>
            <button onclick="saveLessonPlanAsTemplate()" id="btnSavePlanTemplate" class="px-3 py-1.5 bg-violet-50 hover:bg-violet-100 text-violet-700 border border-violet-200 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs" title="Save as reusable template for other batches with the same subject">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/><line x1="12" x2="12" y1="7" y2="13"/><line x1="9" x2="15" y1="10" y2="10"/></svg> Save as Template
            </button>
            <button onclick="loadLessonPlanTemplate()" class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs" title="Load previously saved template">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Load Template
            </button>
            <a href="/classroom/${currentSubjectId}/lesson-plan/print" target="_blank" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer flex items-center gap-1 shadow-xs" title="Print Lesson Plan (A4)">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Plan
            </a>
          </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-xs">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]" id="lessonPlanTable">
              <thead>
                <tr class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-200">
                  <th class="p-3 w-10 text-center">#</th>
                  <th class="p-3 w-8 text-center">CO</th>
                  <th class="p-3">Topic / Content <span class="text-slate-600 normal-case font-normal">(editable)</span></th>
                  <th class="p-3 w-32">Proposed Date</th>
                  <th class="p-3 w-32">Actual Date</th>
                  <th class="p-3 w-24 text-center">Hrs</th>
                  <th class="p-3 w-28">Pedagogy</th>
                  <th class="p-3 w-36">Remarks</th>
                </tr>
              </thead>
              <tbody>
      `;

      lessonPlans.forEach((lp, index) => {
        let co        = lp.co_id || '';
        let coColor   = CO_COLORS[co] || 'bg-slate-100 text-slate-700 border-slate-200';
        let coBadge   = co ? `<span class="px-1.5 py-0.5 rounded border text-[10px] font-bold ${coColor}">${co}</span>` : `<span class="text-slate-700 text-[10px]">—</span>`;
        let proposed  = lp.proposed_date || '';
        let pedagogy  = lp.pedagogy || 'Lecture';
        let remarks   = (lp.remarks || '').replace(/"/g, '&quot;');
        let topic     = (lp.topic_content || '').replace(/"/g, '&quot;');
        let dayNo     = lp.day_no || (index + 1);
        let isTest    = pedagogy.toLowerCase() === 'test';
        let rowBg     = isTest ? 'bg-slate-100/90 border-b border-slate-200 hover:bg-slate-100' : 'bg-white border-b border-slate-100 hover:bg-slate-50/80';
        let actual    = lp.actual_date
          ? `<span class="text-emerald-400 font-mono text-[10px]">${lp.actual_date}</span>`
          : `<span class="text-slate-700 text-[10px]">—</span>`;

        html += `
          <tr class="border-b ${rowBg} last:border-0 hover:bg-slate-50/80 transition-colors" data-lp-id="${lp.id}">
            <td class="p-2 text-center text-xs font-bold text-slate-700">${dayNo}</td>
            <td class="p-2 text-center">${coBadge}</td>
            <td class="p-2">
              <input type="text" value="${topic}" data-field="topic"
                class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2.5 py-1.5 text-slate-900 font-medium text-xs shadow-2xs outline-none transition-all placeholder:text-slate-400"
                placeholder="Enter topic..."
                onchange="markPlanDirty(${lp.id})">
            </td>
            <td class="p-2">
              <input type="date" value="${proposed}" data-field="proposed_date"
                class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2 py-1.5 text-slate-800 text-xs font-mono shadow-2xs outline-none transition-all"
                onchange="markPlanDirty(${lp.id}); autoSavePlanRow(${lp.id}, this.closest('tr'))">
            </td>
            <td class="p-2 text-center">${actual}</td>
            <td class="p-2 text-center text-xs font-bold font-mono text-slate-700">${lp.allocated_hours || 1}</td>
            <td class="p-2">
              <input type="text" value="${pedagogy}" data-field="pedagogy"
                class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2.5 py-1.5 text-slate-800 text-xs shadow-2xs outline-none transition-all placeholder:text-slate-400"
                placeholder="Lecture, Demo, Lab..."
                onchange="markPlanDirty(${lp.id})">
            </td>
            <td class="p-2">
              <input type="text" value="${remarks}" data-field="remarks"
                class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-lg px-2.5 py-1.5 text-slate-800 text-xs shadow-2xs outline-none transition-all placeholder:text-slate-400"
                placeholder="Add remarks..."
                onchange="markPlanDirty(${lp.id})">
            </td>
          </tr>
        `;
      });

      html += `
              </tbody>
            </table>
          </div>
        </div>

        <div id="planSaveStatusBar" class="hidden mt-3 px-4 py-2.5 bg-amber-900/20 border border-amber-500/20 rounded-xl flex items-center gap-3 text-xs font-bold text-amber-400">
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
          <span>You have unsaved changes.</span>
          <button onclick="saveLessonPlanChanges()" class="ml-auto px-3 py-1 bg-emerald-700 hover:bg-emerald-600 text-white rounded-lg cursor-pointer transition-premium">
            Save Now
          </button>
        </div>
      `;

      container.innerHTML = html;
    }

    // Track which rows have been edited
    window._dirtyPlanRows = new Set();

    function markPlanDirty(lpId) {
      window._dirtyPlanRows.add(lpId);
      const bar = document.getElementById('planSaveStatusBar');
      if (bar) { bar.classList.remove('hidden'); bar.classList.add('flex'); }
    }

    // Auto-save a single row immediately (for date changes)
    function autoSavePlanRow(lpId, row) {
      if (!row) return;
      const rowData = collectPlanRow(lpId, row);
      if (!rowData) return;
      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/bulk-update`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ rows: [rowData] })
      }).then(r => r.json()).then(d => {
        if (d.status === 'SUCCESS') window._dirtyPlanRows.delete(lpId);
      }).catch(e => console.error('Auto-save failed:', e));
    }

    function collectPlanRow(lpId, row) {
      if (!row) {
        row = document.querySelector(`#lessonPlanTable tr[data-lp-id="${lpId}"]`);
        if (!row) return null;
      }
      return {
        id:            lpId,
        topic_content: row.querySelector('[data-field="topic"]')?.value          || '',
        proposed_date: row.querySelector('[data-field="proposed_date"]')?.value  || null,
        pedagogy:      row.querySelector('[data-field="pedagogy"]')?.value        || 'Lecture',
        remarks:       row.querySelector('[data-field="remarks"]')?.value         || '',
      };
    }

    function saveLessonPlanChanges() {
      const btn = document.getElementById('btnSavePlan');
      const rows = [];
      document.querySelectorAll('#lessonPlanTable tbody tr[data-lp-id]').forEach(row => {
        const lpId = parseInt(row.getAttribute('data-lp-id'));
        const data = collectPlanRow(lpId, row);
        if (data) rows.push(data);
      });
      if (rows.length === 0) { alert('Nothing to save.'); return; }
      if (btn) { btn.disabled = true; btn.innerHTML = '<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> Saving...'; }

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/bulk-update`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ rows })
      }).then(r => r.json()).then(d => {
        if (d.status === 'SUCCESS') {
          window._dirtyPlanRows.clear();
          const bar = document.getElementById('planSaveStatusBar');
          if (bar) { bar.classList.add('hidden'); bar.classList.remove('flex'); }
          if (btn) btn.innerHTML = '<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> Saved!';
          setTimeout(() => { if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save Changes'; } }, 2500);
        } else {
          alert(d.message || 'Save failed.');
          if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save Changes'; }
        }
      }).catch(e => {
        alert('Save failed: ' + e.message);
        if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save Changes'; }
      });
    }

    function regenerateLessonPlan() {
      if (!confirm('This will delete the current lesson plan and regenerate it from the stored syllabus data.\n\nAny manually entered dates and remarks will be lost.\n\nContinue?')) return;
      const btn = document.getElementById('btnRegenPlan');
      if (btn) { btn.disabled = true; btn.innerHTML = '<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> Generating...'; }

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/regenerate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({})
      }).then(r => r.json()).then(d => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg> Regenerate'; }
        if (d.status === 'SUCCESS') {
          renderCoursePlanner(d.data);
          toggleClassroomTab('planner');
        } else {
          alert(d.message || 'Regeneration failed.');
        }
      }).catch(e => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg> Regenerate'; }
        alert('Error: ' + e.message);
      });
    }

    function saveLessonPlanAsTemplate() {
      if (!confirm('Save the current lesson plan as a reusable template for all future batches of this subject?\n\nThis will overwrite any previously saved template for this subject code.')) return;
      const btn = document.getElementById('btnSavePlanTemplate');
      if (btn) { btn.disabled = true; }

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/save-as-template`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({})
      }).then(r => r.json()).then(d => {
        if (btn) { btn.disabled = false; }
        alert(d.status === 'SUCCESS' ? '✓ ' + d.message : '✗ ' + (d.message || 'Save failed.'));
      }).catch(e => {
        if (btn) { btn.disabled = false; }
        alert('Error: ' + e.message);
      });
    }

    function loadLessonPlanTemplate() {
      if (!confirm('Load the saved template for this subject?\n\nThis will replace the current lesson plan with the template. Existing proposed dates will be cleared.')) return;

      fetch(`/api/classroom/${currentSubjectId}/lesson-plans/load-template`, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      }).then(r => r.json()).then(d => {
        if (d.status === 'SUCCESS') {
          renderCoursePlanner(d.data);
          toggleClassroomTab('planner');
        } else {
          alert(d.message || 'No template found for this subject.');
        }
      }).catch(e => alert('Error: ' + e.message));
    }

    // Wire up updateProposedDate (was a stub) — now handled by autoSavePlanRow via onchange
    function updateProposedDate(lpId, dateValue) {
      const row = document.querySelector(`#lessonPlanTable tr[data-lp-id="${lpId}"]`);
      autoSavePlanRow(lpId, row);
    }


    function renderFormativeAssessment(students) {
      let html = `
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-sm text-slate-500 mt-1">Generate AI questions for each CO and record 10-mark evaluations.</p>
          </div>
          <div class="flex items-center gap-2">
            <button onclick="printAssignmentReport('${currentSubjectId}')" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-blue-500/10 cursor-pointer">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Assignment Report
            </button>
            <button onclick="generateAIQuestions('${currentSubjectId}')" class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-blue-900/20 cursor-pointer">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg> AI Generate Questions
            </button>
            <button onclick="generateAIQuestions('${currentSubjectId}', null, 'bank')" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-500 hover:from-indigo-500 hover:to-violet-400 text-white rounded-xl text-sm font-bold transition-premium flex items-center gap-2 shadow-lg shadow-indigo-900/20 cursor-pointer">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg> Pull from Question Bank
            </button>
          </div>
        </div>

        <div id="aiQuestionsContainer" class="grid-cols-1 md:grid-cols-2 gap-4 mb-6" style="display:none;"></div>

        <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-xs">
          <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="font-bold text-sm text-slate-800 flex items-center gap-2 tracking-wide uppercase">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg> Enter Assignment Marks
            </div>
            <button onclick="saveAssignmentMarks('${currentSubjectId}')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer">
              Save Marks
            </button>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
              <thead>
                <tr class="bg-slate-50 text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
                  <th class="p-3 w-12">S.No.</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 w-28">Admission No</th>
                  <th class="p-3 w-32">SBTE Reg No</th>
                  <th class="p-3 text-center w-20">CO1 (20)</th>
                  <th class="p-3 text-center w-20">CO2 (20)</th>
                  <th class="p-3 text-center w-20">CO3 (20)</th>
                  <th class="p-3 text-center w-20">CO4 (20)</th>
                </tr>
              </thead>
              <tbody id="markEntryTbody">
      `;

      if (students && students.length > 0) {
        students.forEach((student, index) => {
          let m = student.assignment_marks || {};
          let sub = student.assignment_submissions || {};

          const getInputHtml = (co, val) => {
            let isSubmitted = (sub[co] === 'Submitted');
            let isGraded = val !== null && val !== '';
            let styleClasses = "co-mark w-full bg-white border border-slate-200 rounded-lg px-2 py-2 text-slate-900 text-base font-bold shadow-2xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-center ";
            let indicator = "";

            if (isGraded) {
              styleClasses += "border-slate-200 focus:border-blue-500";
            } else if (isSubmitted) {
              // Highlight input field with an amber border and a pulsing indicator dot
              styleClasses += "border-amber-500/70 bg-amber-950/20 focus:border-amber-400";
              indicator = `<span class="absolute right-2 top-1.5 flex h-2 w-2" title="Submitted by student"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span></span>`;
            } else {
              styleClasses += "border-slate-700/60 focus:border-blue-400";
            }

            return `
              <div class="relative">
                <input type="number" step="1" max="20" min="0" value="${val !== null ? Math.round(val) : ''}" 
                       class="${styleClasses}" data-co="${co}">
                ${indicator}
              </div>
            `;
          };

          html += `
            <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/80 transition-colors text-slate-800" data-reg="${student.reg_no}">
              <td class="px-4 py-4 text-slate-700 font-bold text-base text-center">${index + 1}</td>
              <td class="px-4 py-4 font-bold text-slate-900 text-base tracking-wide">${student.name}</td>
              <td class="px-4 py-4 font-mono text-slate-700 text-sm font-semibold">${student.reg_no}</td>
              <td class="px-4 py-4 font-mono text-slate-700 text-sm font-semibold">${student.sbte_reg_no || '-'}</td>
              <td class="px-3 py-3">${getInputHtml('CO1', m.CO1)}</td>
              <td class="px-3 py-3">${getInputHtml('CO2', m.CO2)}</td>
              <td class="px-3 py-3">${getInputHtml('CO3', m.CO3)}</td>
              <td class="px-3 py-3">${getInputHtml('CO4', m.CO4)}</td>
            </tr>
          `;
        });
      } else {
        html += `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm font-bold">No students found in this classroom.</td></tr>`;
      }

      html += `</tbody></table></div></div>`;
      document.getElementById('formativeAssessmentContent').innerHTML = html;
    }

    function renderAIQuestionsList(questionsData, subjectId) {
      const container = document.getElementById('aiQuestionsContainer');
      container.style.display = 'grid';
      let html = '';
      
      // Show empty-state prompt if no questions have been generated yet
      if (!questionsData || Object.keys(questionsData).length === 0) {
        container.innerHTML = `
          <div class="col-span-full flex flex-col items-center justify-center py-12 text-center bg-slate-50/60 border border-dashed border-slate-300 rounded-2xl shadow-2xs">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
            <p class="font-bold text-slate-800 text-sm mb-1">No Assignment Questions Yet</p>
            <p class="text-xs text-slate-600 mb-4">Click <strong>AI Generate Questions</strong> above to generate questions for all Course Outcomes using Gemini AI.</p>
          </div>
        `;
        return;
      }

      for (const [co, qs] of Object.entries(questionsData)) {
        let qList = qs.map(q => {
          let qText = typeof q === 'object' ? q.question : q;
          let bt = typeof q === 'object' ? q.bt_level : null;
          let marksVal = typeof q === 'object' ? q.marks : null;
          
          let cog = '';
          if (bt) {
            let color = bt.toLowerCase() === 'remember' ? 'text-blue-400' : (bt.toLowerCase() === 'apply' ? 'text-emerald-400' : 'text-indigo-400');
            cog = ` <span class="${color} font-bold">[${bt}]</span>`;
          } else {
            let lower = qText.toLowerCase();
            if (!lower.includes('[remember]') && !lower.includes('[u]') && !lower.includes('[a]') && !lower.includes('[r]') && !lower.includes('cognitive')) {
              if (lower.includes('define') || lower.includes('list') || lower.includes('what is') || lower.includes('state') || lower.includes('name')) {
                cog = ' <span class="text-blue-400 font-bold">[Remember - R]</span>';
              } else if (lower.includes('design') || lower.includes('solve') || lower.includes('calculate') || lower.includes('write') || lower.includes('implement') || lower.includes('apply') || lower.includes('draw')) {
                cog = ' <span class="text-emerald-400 font-bold">[Apply - A]</span>';
              } else {
                cog = ' <span class="text-indigo-400 font-bold">[Understand - U]</span>';
              }
            }
          }
          let marksText = marksVal ? ` <span class="text-slate-500 font-bold">(${marksVal} Marks)</span>` : '';
          return `<li class="text-sm text-slate-800 mb-2 leading-relaxed font-medium">${qText}${cog}${marksText}</li>`;
        }).join('');
        let schedule = currentDeadlines[co] || { start: '', due: '', locked: false };
        if (typeof schedule === 'string') schedule = { start: '', due: schedule, locked: false }; // Legacy fallback
        
        let isLocked = schedule.locked;
        let lockStr = isLocked ? `<svg class="w-3.5 h-3.5 inline text-amber-500 ml-1" title="Locked" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>` : '';
        let disabledAttr = isLocked ? 'disabled' : '';
        let regenBtn = isLocked ? '' : `
                <button onclick="generateAIQuestions('${subjectId}', '${co}', 'ai')" class="p-1.5 rounded-lg bg-white hover:bg-blue-50 text-slate-700 hover:text-blue-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Generate via AI (Gemini)">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
                </button>
                <button onclick="generateAIQuestions('${subjectId}', '${co}', 'bank')" class="p-1.5 rounded-lg bg-white hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Pull from Question Bank Pool">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
                </button>
        `;
        let editBtn = isLocked ? '' : `
                <button onclick="openEditQuestionsModal('${subjectId}', '${co}')" class="p-1.5 rounded-lg bg-white hover:bg-amber-50 text-slate-700 hover:text-amber-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Manually Edit Questions">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                </button>
        `;
        let lockBtn = isLocked ? '' : `
                <button onclick="toggleAssignmentLock('${subjectId}', '${co}')" class="p-1.5 rounded-lg bg-white hover:bg-amber-50 text-slate-700 hover:text-amber-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Lock & Finalize">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </button>
        `;
        let printBtn = `
                <button onclick="printAssignmentPaperAndRubrics('${subjectId}', '${co}')" class="p-1.5 rounded-lg bg-white hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Print Assignment & Rubrics">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                </button>
        `;

        html += `
          <div class="bg-white border border-slate-200/80 p-5 rounded-2xl relative overflow-hidden group shadow-xs ${isLocked ? 'ring-1 ring-amber-500/30' : ''}">
            <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-3 relative z-10">
              <h5 class="text-sm font-bold text-slate-900 flex items-center gap-1">
                <span class="px-2 py-0.5 rounded-md bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold mr-1">${co}</span> Assignment ${lockStr}
              </h5>
              <div class="flex items-center gap-2">
                <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">
                  <span class="text-xs text-slate-600 font-bold uppercase">Start</span>
                  <input type="date" value="${schedule.start || ''}" ${disabledAttr} class="bg-white text-xs text-slate-800 font-mono outline-none w-24 rounded border border-slate-200 px-1 py-0.5 shadow-2xs" onchange="updateAssignmentSchedule('${subjectId}', '${co}', 'start', this.value)">
                </div>
                <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200 shadow-2xs">
                  <span class="text-xs text-slate-600 font-bold uppercase">Due</span>
                  <input type="date" value="${schedule.due || ''}" ${disabledAttr} class="bg-white text-xs text-slate-800 font-mono outline-none w-24 rounded border border-slate-200 px-1 py-0.5 shadow-2xs" onchange="updateAssignmentSchedule('${subjectId}', '${co}', 'due', this.value)">
                </div>
                ${regenBtn}
                ${editBtn}
                ${lockBtn}
                ${printBtn}
              </div>
            </div>
            
            <ul id="questions-list-${co}" class="list-none m-0 p-0 relative z-10 min-h-[60px] divide-y divide-slate-100">${qList}</ul>
          </div>
        `;
      }
      document.getElementById('aiQuestionsContainer').innerHTML = html;
    }

    function generateAIQuestions(subjectId, coTag = null, mode = 'ai') {
      const qContainer = document.getElementById('aiQuestionsContainer');
      if (!coTag) {
        qContainer.style.display = 'grid';
        qContainer.innerHTML = `<div class="col-span-full text-center py-10 text-sm font-bold text-blue-400 animate-pulse flex flex-col items-center gap-3"><div class="w-8 h-8 border-2 border-blue-500/40 border-t-blue-400 rounded-full animate-spin"></div>Generating AI questions for all Course Outcomes...</div>`;
      } else {
        qContainer.style.display = 'grid';
        const ul = document.getElementById(`questions-list-${coTag}`);
        if(ul) ul.innerHTML = `<li class="text-sm text-blue-400 animate-pulse">Generating via Gemini AI...</li>`;
      }
      
      let url = `/api/classroom/${subjectId}/generate-questions?_t=${Date.now()}&generation_mode=${mode}`;
      if (coTag) url += `&co_tag=${coTag}`;

      fetch(url)
      .then(res => {
        if (!res.ok) throw new Error('Server error: ' + res.status);
        return res.json();
      })
      .then(data => {
        if (data.status === 'SUCCESS') {
          if (!coTag) {
             currentQuestions = data.data;
             renderAIQuestionsList(currentQuestions, subjectId);
          } else {
             currentQuestions[coTag] = data.data[coTag];
             const ul = document.getElementById(`questions-list-${coTag}`);
             if (ul && data.data[coTag]) {
               ul.innerHTML = data.data[coTag].map(q => `<li class="text-sm text-slate-400 mb-1 leading-relaxed">${q}</li>`).join('');
             }
          }
        } else {
           qContainer.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900/40 rounded-xl text-sm font-bold">${data.message || 'Generation failed.'}</div>`;
        }
      })
      .catch(err => {
        console.error('AI Generate Error:', err);
        qContainer.innerHTML = `<div class="col-span-full p-4 bg-red-950/40 text-red-400 border border-red-900/40 rounded-xl text-sm font-bold">Generation failed: ${err.message}. Check your API key and internet connection.</div>`;
      });
    }

    function updateAssignmentSchedule(subjectId, coTag, type, dateValue) {
      let payload = { co_tag: coTag };
      if (type === 'start') payload.start_date = dateValue;
      if (type === 'due') payload.due_date = dateValue;
      
      fetch(`/api/classroom/${subjectId}/save-assignment-deadline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
           if(!currentDeadlines[coTag] || typeof currentDeadlines[coTag] === 'string') currentDeadlines[coTag] = {start:'', due:'', locked:false};
           if (type === 'start') currentDeadlines[coTag].start = dateValue;
           if (type === 'due') currentDeadlines[coTag].due = dateValue;
           console.log(`Schedule for ${coTag} updated.`);
        } else {
           alert(data.message);
        }
      });
    }

    function toggleAssignmentLock(subjectId, coTag) {
      if(!confirm(`Are you sure you want to lock ${coTag} questions? This cannot be easily undone.`)) return;
      
      fetch(`/api/classroom/${subjectId}/save-assignment-deadline`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, is_locked: true })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
           if(!currentDeadlines[coTag] || typeof currentDeadlines[coTag] === 'string') currentDeadlines[coTag] = {start:'', due:'', locked:false};
           currentDeadlines[coTag].locked = true;
           renderAIQuestionsList(currentQuestions, subjectId);
        } else {
           alert(data.message);
        }
      });
    }

    let currentEditCo = '';
    let currentEditSubjectId = '';

    function openEditQuestionsModal(subjectId, coTag) {
      currentEditCo = coTag;
      currentEditSubjectId = subjectId;
      document.getElementById('editQuestionsCoBadge').innerText = coTag;
      
      const container = document.getElementById('editQuestionsFieldsContainer');
      container.innerHTML = '';

      let qs = currentQuestions[coTag] || [];
      if (qs.length === 0) {
        addManualQuestionField();
      } else {
        qs.forEach(q => {
          let qText = typeof q === 'object' ? q.question : q;
          let bt = typeof q === 'object' ? q.bt_level : 'Understand';
          let marksVal = typeof q === 'object' ? q.marks : 5;
          addManualQuestionField(qText, bt, marksVal);
        });
      }

      updateEditQuestionsTotalMarks();
      
      const modal = document.getElementById('editQuestionsModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeEditQuestionsModal() {
      const modal = document.getElementById('editQuestionsModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function addManualQuestionField(question = '', btLevel = 'Understand', marks = 5) {
      const container = document.getElementById('editQuestionsFieldsContainer');
      const div = document.createElement('div');
      div.className = "p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3 relative question-field-row shadow-2xs";
      
      div.innerHTML = `
        <div class="flex justify-between items-center">
          <span class="text-xs font-bold text-slate-700 uppercase tracking-wide">Question</span>
          <button type="button" onclick="this.closest('.question-field-row').remove(); updateEditQuestionsTotalMarks();" class="text-rose-500 hover:text-rose-700 cursor-pointer transition-colors">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
          </button>
        </div>
        <div>
          <textarea class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-slate-900 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs resize-y q-text" rows="2" placeholder="Type question description..." required>${question}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-xs font-bold text-slate-600 uppercase block mb-1">BT Level</label>
            <select class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-800 focus:border-blue-500 shadow-2xs outline-none q-bt">
              <option value="Remember" ${btLevel === 'Remember' ? 'selected' : ''}>Remember</option>
              <option value="Understand" ${btLevel === 'Understand' ? 'selected' : ''}>Understand</option>
              <option value="Apply" ${btLevel === 'Apply' ? 'selected' : ''}>Apply</option>
            </select>
          </div>
          <div>
            <label class="text-xs font-bold text-slate-600 uppercase block mb-1">Marks</label>
            <input type="number" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-900 focus:border-blue-500 shadow-2xs outline-none q-marks" value="${marks}" min="1" max="20" onchange="updateEditQuestionsTotalMarks()" required>
          </div>
        </div>
      `;
      container.appendChild(div);
      updateEditQuestionsTotalMarks();
    }

    function updateEditQuestionsTotalMarks() {
      let sum = 0;
      const inputs = document.querySelectorAll('#editQuestionsFieldsContainer .q-marks');
      inputs.forEach(input => {
        sum += parseInt(input.value || 0);
      });
      document.getElementById('editQuestionsTotalMarks').innerText = sum;
    }

    function saveManualQuestions() {
      const rows = document.querySelectorAll('#editQuestionsFieldsContainer .question-field-row');
      let questions = [];
      let totalMarks = 0;

      rows.forEach(row => {
        const text = row.querySelector('.q-text').value.trim();
        const bt = row.querySelector('.q-bt').value;
        const marks = parseInt(row.querySelector('.q-marks').value || 0);
        
        if (text) {
          questions.push({
            question: text,
            bt_level: bt,
            marks: marks
          });
          totalMarks += marks;
        }
      });

      if (questions.length === 0) {
        alert("Please add at least one question.");
        return;
      }

      if (totalMarks !== 20) {
        if (!confirm(`Warning: Total marks allocated is ${totalMarks}. The target is exactly 20 marks. Do you want to proceed anyway?`)) {
          return;
        }
      }

      fetch(`/api/classroom/${currentEditSubjectId}/save-assignment-questions`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
          co_tag: currentEditCo,
          questions: questions
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentQuestions[currentEditCo] = questions;
          renderAIQuestionsList(currentQuestions, currentEditSubjectId);
          closeEditQuestionsModal();
          alert("Questions saved successfully.");
        } else {
          alert(data.message);
        }
      })
      .catch(() => alert("Failed to save assignment questions."));
    }

    function saveAssignmentMarks(subjectId) {
      let marksPayload = [];
      const rows = document.querySelectorAll('#markEntryTbody tr[data-reg]');
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const inputs = row.querySelectorAll('.co-mark');
        inputs.forEach(input => {
          if (input.value !== '') {
            marksPayload.push({
              reg_no: regNo,
              co_tag: input.getAttribute('data-co'),
              marks_obtained: input.value
            });
          }
        });
      });

      if (marksPayload.length === 0) {
        alert("No marks entered.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/save-assignment-marks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ marks: marksPayload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') alert("Marks successfully saved!");
        else alert(data.message || "Failed to save marks.");
      });
    }

    function updateProposedDate(lessonPlanId, dateValue) {
        console.log("Updating lesson plan", lessonPlanId, "with date", dateValue);
    }

    function renderCourseStructure(cos, modules, textbooks, copo) {
      // Filter out empty/blank COs and modules to show only populated ones
      if (cos && Array.isArray(cos)) {
        cos = cos.filter(co => co && co.description && co.description.trim() !== '' && co.description.trim() !== 'null');
      }
      if (modules && Array.isArray(modules)) {
        modules = modules.filter(m => m && m.content && m.content.trim() !== '' && m.content.trim() !== 'null');
      }

      // Debug: log what we received
      console.log('[renderCourseStructure] cos:', cos ? cos.length : 'null', '| modules:', modules ? modules.length : 'null', '| textbooks:', textbooks ? textbooks.length : 'null', '| copo keys:', copo ? Object.keys(copo).length : 'null');
      const container = document.getElementById('courseStructureContent');
      if (!container) { console.error('[renderCourseStructure] courseStructureContent not found!'); return; }

      let html = `
        <div class="flex items-center justify-between gap-3 mb-5 p-4 bg-white border border-slate-200/80 rounded-2xl shadow-xs">
          <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            </span>
            <div>
              <h3 class="font-bold text-slate-900 text-sm">Course Structure &amp; Syllabus Elements</h3>
              <p class="text-xs text-slate-500">Extracted syllabus specifications and mapping matrices</p>
            </div>
          </div>
          <div class="flex flex-wrap gap-2 text-xs font-bold">
            <span class="bg-blue-50 border border-blue-200 text-blue-700 px-2.5 py-1 rounded-lg">${cos ? cos.length : 0} COs</span>
            <span class="bg-purple-50 border border-purple-200 text-purple-700 px-2.5 py-1 rounded-lg">${modules ? modules.length : 0} Modules</span>
            <span class="bg-amber-50 border border-amber-200 text-amber-700 px-2.5 py-1 rounded-lg">${textbooks ? textbooks.length : 0} Textbooks</span>
          </div>
        </div>
      `;

      if (cos && cos.length > 0) {
        let cosList = cos.map(co => {
          let cog = (co.cognitive_level || 'Apply').toLowerCase();
          let badgeClasses = 'bg-emerald-50 text-emerald-700 border-emerald-200';
          if (cog.includes('understand')) badgeClasses = 'bg-blue-50 text-blue-700 border-blue-200';
          else if (cog.includes('analy') || cog.includes('eval')) badgeClasses = 'bg-purple-50 text-purple-700 border-purple-200';

          return `
          <div class="p-4 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:border-blue-300 transition-all space-y-2">
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-700 font-bold font-mono text-xs border border-blue-200">${co.id}</span>
                ${co.duration ? `<span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-semibold text-xs border border-slate-200">${co.duration} hrs</span>` : ''}
              </div>
              <span class="px-2 py-0.5 rounded-md font-semibold text-xs border ${badgeClasses}">${co.cognitive_level || 'Apply'}</span>
            </div>
            <p class="text-sm font-medium text-slate-800 leading-relaxed">${co.description}</p>
          </div>
        `}).join('');

        html += `
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs mb-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200/80">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </span>
                <h3 class="font-bold text-slate-900 text-sm">Course Outcomes (COs)</h3>
              </div>
              <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">${cos.length} Outcomes</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              ${cosList}
            </div>
          </div>
        `;
      }

      if (copo && Object.keys(copo).length > 0) {
        let copoList = Object.keys(copo).map(coKey => {
            let mapping = copo[coKey];
            let poCells = '';
            for(let i = 1; i <= 12; i++) {
                let val = mapping['PO' + i] || '-';
                let cellClass = 'text-slate-400 font-normal';
                if (val == '3') cellClass = 'font-bold text-emerald-700 bg-emerald-50/60';
                else if (val == '2') cellClass = 'font-bold text-blue-700 bg-blue-50/60';
                else if (val == '1') cellClass = 'font-semibold text-slate-700 bg-slate-50';
                poCells += `<td class="p-2.5 text-center font-mono text-sm ${cellClass}">${val}</td>`;
            }
            return `
              <tr class="hover:bg-slate-50/80 transition-all">
                <td class="p-3 text-left font-bold text-blue-700 pl-4 font-mono">${coKey}</td>
                ${poCells}
              </tr>
            `;
        }).join('');
        
        let poHeaders = '';
        for(let i=1; i<=12; i++) {
            poHeaders += `<th class="p-2.5 text-center font-mono text-xs">PO${i}</th>`;
        }

        html += `
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs mb-5 space-y-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center text-sm font-bold border border-indigo-200/80">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/></svg>
                </span>
                <div>
                  <h3 class="font-bold text-slate-900 text-sm">CO-PO Articulation Matrix</h3>
                  <p class="text-xs text-slate-500">Mapping correlation: 3 = High, 2 = Medium, 1 = Low</p>
                </div>
              </div>
              <div class="flex items-center gap-3 text-xs font-medium text-slate-600">
                <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold flex items-center justify-center text-[10px]">3</span> High</span>
                <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-blue-100 text-blue-800 border border-blue-300 font-bold flex items-center justify-center text-[10px]">2</span> Med</span>
                <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-slate-100 text-slate-700 border border-slate-300 font-bold flex items-center justify-center text-[10px]">1</span> Low</span>
              </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse text-sm">
                  <thead>
                    <tr class="bg-slate-50 text-slate-700 font-bold text-xs uppercase border-b border-slate-200">
                      <th class="p-3 text-left pl-4 w-20">CO</th>
                      ${poHeaders}
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    ${copoList}
                  </tbody>
                </table>
            </div>
          </div>
        `;
      }

      // Render Modules section
      if (modules && modules.length > 0) {
        let modulesList = modules.map((m, idx) => `
          <div class="p-4 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:border-purple-300 transition-all space-y-2">
            <div class="flex items-center justify-between gap-2 border-b border-slate-200/60 pb-2">
              <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-200 text-xs font-bold">Module ${m.module_id || (idx + 1)}</span>
                <span>${m.title || 'Unit ' + (m.module_id || (idx + 1))}</span>
              </h4>
              ${m.hours ? `<span class="px-2.5 py-0.5 rounded-md bg-white border border-slate-200 text-slate-700 font-bold text-xs font-mono shadow-2xs">${m.hours} Hours</span>` : ''}
            </div>
            <p class="text-sm font-normal text-slate-700 leading-relaxed whitespace-pre-line pt-1">${m.content || ''}</p>
          </div>
        `).join('');

        html += `
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs mb-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold border border-purple-200/80">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 2v20"/><path d="M10 2v10l3-2.5 3 2.5V2"/></svg>
                </span>
                <h3 class="font-bold text-slate-900 text-sm">Course Modules / Units</h3>
              </div>
              <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">${modules.length} Modules</span>
            </div>
            <div class="space-y-3">
              ${modulesList}
            </div>
          </div>
        `;
      }

      // Render Textbooks section
      if (textbooks && textbooks.length > 0) {
        let textbooksList = textbooks.map((tb, idx) => `
          <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50/60 border border-slate-200/80 hover:border-amber-300 transition-all">
            <span class="flex-shrink-0 w-6 h-6 rounded-lg bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-700 text-xs font-bold mt-0.5">${idx + 1}</span>
            <p class="text-sm font-medium text-slate-800 leading-relaxed">${tb}</p>
          </div>
        `).join('');

        html += `
          <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs mb-5 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
              <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-bold border border-amber-200/80">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </span>
                <h3 class="font-bold text-slate-900 text-sm">Textbooks &amp; References</h3>
              </div>
              <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg border border-slate-200">${textbooks.length} Books</span>
            </div>
            <div class="space-y-2.5">
              ${textbooksList}
            </div>
          </div>
        `;
      }

      if (html === '') {
        html = `<div class="p-6 text-center text-sm text-slate-500 border border-dashed border-slate-200 rounded-2xl bg-white">Could not extract structured data. The syllabus might have an unparseable format.</div>`;
      }

      console.log('[renderCourseStructure] Writing HTML to courseStructureContent, length:', html.length);
      container.innerHTML = html;
    }

    function renderSummativeAssessment(cos, students) {
      let html = `
        <div class="flex items-center justify-between mb-4 no-print">
          <div>
            <h4 class="text-base font-bold text-slate-900">Summative Assessment (Manual Tests)</h4>
            <p class="text-sm text-slate-600 mt-0.5">Configure and generate precise Cognitive Level based question papers for each CO.</p>
          </div>
        </div>
      `;

      // Build the marks entry table FIRST so it's at the top
      let marksEntryHtml = `
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs no-print mb-6">
          <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors" onclick="document.getElementById('manualMarksWrapper').classList.toggle('hidden'); const icon = document.getElementById('marksToggleIcon'); if (icon) icon.classList.toggle('rotate-180');">
            <div class="font-bold text-sm text-slate-800 flex items-center gap-2 tracking-wider uppercase">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg> Enter Manual Marks
              <svg id="marksToggleIcon" class="w-4 h-4 text-slate-500 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
            <div class="flex items-center gap-2">
              <button onclick="event.stopPropagation(); printSummativeReport('${currentSubjectId}')" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer shadow-xs">
                Print Written Report
              </button>
              <button onclick="event.stopPropagation(); saveSummativeMarks('${currentSubjectId}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition-premium cursor-pointer shadow-xs">
                Save Written Marks
              </button>
            </div>
          </div>
          <div id="manualMarksWrapper" class="hidden overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
              <thead>
                <tr class="bg-slate-50 text-xs font-bold text-slate-700 uppercase tracking-wider border-b border-slate-200">
                  <th class="p-3 w-12">S.No.</th>
                  <th class="p-3">Student Name</th>
                  <th class="p-3 w-28">Admission No</th>
                  <th class="p-3 w-32">SBTE Reg No</th>
                  <th class="p-3 text-center w-20">CO1</th>
                  <th class="p-3 text-center w-20">CO2</th>
                  <th class="p-3 text-center w-20">CO3</th>
                  <th class="p-3 text-center w-20">CO4</th>
                </tr>
              </thead>
              <tbody id="summativeMarkEntryTbody">
      `;

      if (students && students.length > 0) {
        students.forEach((student, index) => {
          let sm = student.summative_marks || {};
          marksEntryHtml += `
            <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/80 transition-colors text-sm text-slate-800" data-reg="${student.reg_no}">
              <td class="p-3 text-slate-700 font-bold">${index + 1}</td>
              <td class="p-3 font-bold text-slate-900">${student.name}</td>
              <td class="p-3 font-mono text-slate-600">${student.reg_no}</td>
              <td class="p-3 font-mono text-slate-600">${student.sbte_reg_no || '-'}</td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO1 !== null ? Math.round(sm.CO1) : ''}" placeholder="-" class="summ-mark w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm shadow-2xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-center" data-co="CO1"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO2 !== null ? Math.round(sm.CO2) : ''}" placeholder="-" class="summ-mark w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm shadow-2xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-center" data-co="CO2"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO3 !== null ? Math.round(sm.CO3) : ''}" placeholder="-" class="summ-mark w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm shadow-2xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-center" data-co="CO3"></td>
              <td class="p-3"><input type="number" step="1" min="0" value="${sm.CO4 !== null ? Math.round(sm.CO4) : ''}" placeholder="-" class="summ-mark w-full bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm shadow-2xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-center" data-co="CO4"></td>
            </tr>
          `;
        });
      } else {
        marksEntryHtml += `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm font-bold">No students found.</td></tr>`;
      }
      marksEntryHtml += `</tbody></table></div></div>`;

      html += marksEntryHtml;

      html += `
        <div id="summativePapersContainer" class="flex flex-col gap-6 mb-6 no-print">
      `;

      if (cos && cos.length > 0) {
        cos.forEach(co => {
          let testData = currentSummativeTests[co.id] || null;
          let generatedContent = '';
          
          if (testData) {
            let partAStr = testData.part_a ? testData.part_a.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-700 font-bold mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';
            let partBStr = testData.part_b ? testData.part_b.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-700 font-bold mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';
            let partCStr = testData.part_c ? testData.part_c.questions.map(q => `<li class="mb-1.5"><span class="font-mono text-sm text-emerald-700 font-bold mr-1">[${q.level}]</span> ${q.q} <span class="float-right text-sm text-slate-500">(${q.marks})</span></li>`).join('') : '';

            generatedContent = `
              <div class="mt-4 pt-4 border-t border-slate-200" id="paper-${co.id}">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-sm font-bold text-emerald-700 uppercase tracking-widest">Generated Question Paper</span>
                  <div class="flex items-center gap-2">
                    <button onclick="printSummativePaper('${co.id}', ${testData.total_marks})" class="flex items-center gap-1.5 text-sm bg-blue-50 hover:bg-blue-600 border border-blue-200 hover:border-blue-600 px-3 py-1.5 rounded-lg text-blue-700 hover:text-white transition-premium cursor-pointer shadow-2xs">
                      <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Q Paper
                    </button>
                    <button onclick="printAnswerKey('${co.id}', ${testData.total_marks})" class="flex items-center gap-1.5 text-sm bg-amber-50 hover:bg-amber-600 border border-amber-200 hover:border-amber-600 px-3 py-1.5 rounded-lg text-amber-700 hover:text-white transition-premium cursor-pointer shadow-2xs">
                      <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> Print Answer Key
                    </button>
                  </div>
                </div>
                <div class="text-sm text-slate-800 bg-slate-50/70 p-5 rounded-xl border border-slate-200 shadow-2xs">
                  ${partAStr ? `<div class="font-bold mb-1.5 text-slate-700">PART A (Short Answers)</div><ul class="list-decimal pl-5 mb-4">${partAStr}</ul>` : ''}
                  ${partBStr ? `<div class="font-bold mb-1.5 text-slate-700">PART B (Medium Answers)</div><ul class="list-decimal pl-5 mb-4">${partBStr}</ul>` : ''}
                  ${partCStr ? `<div class="font-bold mb-1.5 text-slate-700">PART C (Long Answers)</div><ul class="list-decimal pl-5 mb-2">${partCStr}</ul>` : ''}
                </div>
              </div>
            `;
          }

          let isLocked = testData && testData.is_locked ? true : false;
          let disabledAttr = isLocked ? 'disabled' : '';
          let lockStr = isLocked ? `<svg class="w-3.5 h-3.5 inline text-amber-500 ml-1" title="Locked" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>` : '';
          let dateStr = testData && testData.date_of_exam ? testData.date_of_exam : '';

          let qA = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qA : (testData?.part_a?.q_count || '');
          let mA = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mA : (testData?.part_a?.marks_per_q || '');
          let qB = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qB : (testData?.part_b?.q_count || '');
          let mB = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mB : (testData?.part_b?.marks_per_q || '');
          let qC = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].qC : (testData?.part_c?.q_count || '');
          let mC = tempSummativePatterns[co.id] ? tempSummativePatterns[co.id].mC : (testData?.part_c?.marks_per_q || '');

          let lockBtn = isLocked || !testData ? '' : `
            <button onclick="lockSummativeTest('${currentSubjectId}', '${co.id}')" class="p-1.5 rounded-lg bg-white hover:bg-amber-50 text-slate-700 hover:text-amber-700 border border-slate-200 shadow-2xs transition-all cursor-pointer" title="Lock & Finalize">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </button>
          `;

          let genBtn = isLocked ? '' : `
              <button id="gen_btn_${co.id}" onclick="generateSummativePaper('${currentSubjectId}', '${co.id}')" class="w-full py-2.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white border border-blue-200 hover:border-blue-600 rounded-xl text-sm font-bold transition-premium mt-3 cursor-pointer shadow-2xs">
                ${testData ? 'Regenerate Question Paper' : 'Generate AI Question Paper'}
              </button>
          `;
          
          let dateInputStr = `
            <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
              <span class="text-xs text-slate-600 font-bold uppercase flex items-center gap-1"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>Date</span>
              <input type="date" id="summ_date_${co.id}" value="${dateStr}" ${disabledAttr} onchange="saveSummativeConfig('${currentSubjectId}', '${co.id}')" class="bg-white text-sm text-slate-800 font-mono outline-none w-[110px] px-2 py-0.5 rounded border border-slate-200 focus:border-blue-500 shadow-2xs">
            </div>
          `;

          html += `
            <div id="summ_card_${co.id}" class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs relative ${isLocked ? 'ring-1 ring-amber-500/30' : ''}">
              <div class="flex items-center justify-between mb-4 border-b border-slate-200 pb-3 cursor-pointer hover:opacity-80 transition-premium" onclick="document.getElementById('co_body_${co.id}').classList.toggle('hidden'); const icon = document.getElementById('co_icon_' + co.id); if (icon) icon.classList.toggle('rotate-180');">
                <h5 class="text-sm font-bold text-slate-900 flex items-center gap-1">
                  <svg id="co_icon_${co.id}" class="w-4 h-4 text-slate-500 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                  ${co.id} Written Test ${lockStr}
                </h5>
                <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                  ${dateInputStr}
                  ${lockBtn}
                </div>
              </div>
 
              <div id="co_body_${co.id}" class="hidden pt-2">
 
              <div class="flex items-center gap-4 mb-4 mt-1 text-sm font-bold text-slate-700 bg-slate-50 p-2 rounded-xl border border-slate-200 w-max shadow-2xs">
                 <label class="flex items-center gap-1.5 cursor-pointer hover:text-blue-600 transition-premium">
                   <input type="radio" name="summ_mode_${co.id}" value="ai" ${(!testData || !testData.manual_mode) ? 'checked' : ''} onchange="toggleSummativeMode('${co.id}')" class="text-blue-600 focus:ring-blue-500 bg-white border-slate-300" ${disabledAttr}>
                   AI Generation
                 </label>
                 <label class="flex items-center gap-1.5 cursor-pointer hover:text-emerald-600 transition-premium">
                   <input type="radio" name="summ_mode_${co.id}" value="manual" ${(testData && testData.manual_mode) ? 'checked' : ''} onchange="toggleSummativeMode('${co.id}')" class="text-emerald-600 focus:ring-emerald-500 bg-white border-slate-300" ${disabledAttr}>
                   Manual Entry
                 </label>
              </div>
              
              <div class="space-y-3 mb-4">
                <div class="flex items-center gap-3 text-xs text-slate-600 font-bold mb-1"><span class="w-24 shrink-0 whitespace-nowrap">Part</span><span class="flex-1 text-center">Q. Count</span><span class="w-4"></span><span class="flex-1 text-center">Marks/Q</span></div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-xs text-slate-800 font-bold w-24 shrink-0 whitespace-nowrap">PART A</span>
                  <input type="number" id="summ_q_A_${co.id}" value="${qA}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                  <span class="text-slate-400 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_A_${co.id}" value="${mA}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-xs text-slate-800 font-bold w-24 shrink-0 whitespace-nowrap">PART B</span>
                  <input type="number" id="summ_q_B_${co.id}" value="${qB}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                  <span class="text-slate-400 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_B_${co.id}" value="${mB}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div class="flex items-center justify-between gap-3">
                  <span class="text-xs text-slate-800 font-bold w-24 shrink-0 whitespace-nowrap">PART C</span>
                  <input type="number" id="summ_q_C_${co.id}" value="${qC}" placeholder="Qty" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                  <span class="text-slate-400 text-sm font-bold">x</span>
                  <input type="number" id="summ_m_C_${co.id}" value="${mC}" placeholder="Marks" ${disabledAttr} oninput="syncSummativeInputs('${co.id}')" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-bold text-slate-900 text-center shadow-2xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
              </div>

              <div class="flex items-center justify-between mb-4 border-t border-slate-100 pt-3">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-600 hover:text-slate-900 transition-colors font-medium">
                  <input type="checkbox" id="sync_pattern_${co.id}" ${disabledAttr} onchange="if(this.checked) applySummativePatternToAll('${co.id}')" class="rounded border-slate-300 bg-white text-blue-600 focus:ring-blue-500/30">
                  <span>Apply pattern to all COs</span>
                </label>
                <div class="text-xs font-bold text-slate-700 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                  Total Marks: <span id="summ_total_${co.id}" class="${testData ? 'text-emerald-600 font-black' : 'text-blue-600 font-black'}">${testData ? testData.total_marks : '0'}</span>
                </div>
              </div>
              
              ${genBtn}

              <div id="manual_form_wrapper_${co.id}"></div>

              ${generatedContent}
              </div> <!-- close co_body -->
            </div>
          `;
        });
      }

      html += `</div>`;

      // Online MCQ Test Setup (Collapsible)
      let onlineTestHtml = `
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs no-print mb-6">
          <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors" onclick="document.getElementById('onlineTestWrapper').classList.toggle('hidden'); const icon = document.getElementById('onlineTestIcon'); if (icon) icon.classList.toggle('rotate-180');">
            <div class="font-bold text-sm text-slate-800 flex items-center gap-2 tracking-wider uppercase">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg> Online MCQ Tests Setup
              <svg id="onlineTestIcon" class="w-4 h-4 text-slate-500 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
          </div>
          <div id="onlineTestWrapper" class="hidden p-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Configuration Form -->
              <div class="col-span-2 bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <h5 class="text-sm font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2.5">Publish New Online Test</h5>
                <div class="grid grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">Target COs (Multiple)</label>
                    <select id="online_test_cos" multiple class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-800 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 h-[104px] shadow-2xs">
                      ${cos ? cos.map(co => `<option value="${co.id}">${co.id}</option>`).join('') : ''}
                    </select>
                  </div>
                  <div class="space-y-3">
                    <div>
                      <label class="block text-xs text-slate-600 font-bold mb-1 uppercase">Max Attempts</label>
                      <input type="number" id="online_test_attempts" value="1" min="1" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 shadow-2xs">
                    </div>
                    <div>
                      <label class="block text-xs text-slate-600 font-bold mb-1 uppercase">Duration (Minutes)</label>
                      <input type="number" id="online_test_duration" value="30" min="5" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 shadow-2xs">
                    </div>
                  </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">Number of Questions</label>
                    <input type="number" id="online_test_q_count" value="10" min="1" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-800 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 shadow-2xs">
                  </div>
                  <div>
                    <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">Generation Mode</label>
                    <select id="online_test_gen_mode" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium text-slate-800 outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 shadow-2xs">
                      <option value="bank">Mode B: Question Bank Pool</option>
                      <option value="ai">Mode A: AI Generator (Gemini)</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                  <div>
                    <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">Start Time</label>
                    <input type="text" id="online_test_start" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-800 outline-none focus:border-purple-500 shadow-2xs" placeholder="Select Date & Time">
                  </div>
                  <div>
                    <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">End Time (Deadline)</label>
                    <input type="text" id="online_test_end" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-800 outline-none focus:border-purple-500 shadow-2xs" placeholder="Select Date & Time">
                  </div>
                </div>
                
                <div class="mb-5">
                  <label class="block text-xs text-slate-600 font-bold mb-1.5 uppercase">Custom Test ID/Name (Optional)</label>
                  <input type="text" id="online_test_name" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-800 outline-none focus:border-purple-500 shadow-2xs" placeholder="e.g. Midterm Test 1">
                </div>
                <button onclick="publishOnlineTest('${currentSubjectId}')" class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition-premium flex items-center justify-center gap-2 shadow-xs cursor-pointer">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg> Generate & Publish to Students
                </button>
              </div>
              
              <!-- Active Tests Dashboard -->
              <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 shadow-2xs flex flex-col">
                <h5 class="text-xs font-bold text-slate-800 mb-3 border-b border-slate-200 pb-2">Active Online Tests</h5>
                <div id="activeOnlineTestsList" class="space-y-2 text-xs text-slate-600 flex-1">
                   <div class="p-4 bg-white border border-slate-200 rounded-xl text-center border-dashed text-slate-500 font-medium">No active online tests found.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;

      html += onlineTestHtml;

      html += `
        <div id="printableExamArea" class="hidden no-print"></div>
      `;

      document.getElementById('summativeAssessmentContent').innerHTML = html;

      // Automatically spawn manual fields for any saved manual papers on load
      if (cos && cos.length > 0) {
         cos.forEach(co => {
             let testData = currentSummativeTests[co.id] || null;
             if (testData && testData.manual_mode) {
                 spawnManualFields(co.id);
                 // Adjust button styling to show it's manual save
                 const btn = document.getElementById(`gen_btn_${co.id}`);
                 if (btn) {
                     btn.innerText = 'Save Custom Questions';
                     btn.classList.replace('bg-blue-600/20', 'bg-emerald-600/20');
                     btn.classList.replace('hover:bg-blue-600', 'hover:bg-emerald-600');
                     btn.classList.replace('border-blue-500/30', 'border-emerald-500/30');
                     btn.classList.replace('text-blue-400', 'text-emerald-400');
                 }
             }
         });
      }

      // Initialize Flatpickr
      if (typeof flatpickr !== 'undefined') {
        flatpickr("#online_test_start", { 
          enableTime: true, 
          dateFormat: "Y-m-d H:i", 
          time_24hr: false, 
          minDate: "today" 
        });
        flatpickr("#online_test_end", { 
          enableTime: true, 
          dateFormat: "Y-m-d H:i", 
          time_24hr: false, 
          minDate: "today" 
        });
      }
    }

    function syncSummativeInputs(sourceCoId) {
      calcSummativeTotal(sourceCoId);
      if(document.getElementById(`sync_pattern_${sourceCoId}`)?.checked) {
         applySummativePatternToAll(sourceCoId);
         
         // Trigger spawn for all COs in manual mode
         document.querySelectorAll('[id^="summ_card_"]').forEach(card => {
             const coId = card.id.replace('summ_card_', '');
             const isManual = document.querySelector(`input[name="summ_mode_${coId}"]:checked`)?.value === 'manual';
             if (isManual) {
                 spawnManualFields(coId);
             }
         });
      } else {
         const isManual = document.querySelector(`input[name="summ_mode_${sourceCoId}"]:checked`)?.value === 'manual';
         if (isManual) {
             spawnManualFields(sourceCoId);
         }
      }
    }

    function calcSummativeTotal(coId) {
      let total = 0;
      ['A', 'B', 'C'].forEach(p => {
        let q = parseInt(document.getElementById(`summ_q_${p}_${coId}`).value) || 0;
        let m = parseInt(document.getElementById(`summ_m_${p}_${coId}`).value) || 0;
        total += (q * m);
      });
      const tEl = document.getElementById(`summ_total_${coId}`);
      if (tEl) {
        tEl.innerText = total;
        tEl.classList.remove('text-emerald-400');
        tEl.classList.add('text-blue-400');
      }
    }

    function applySummativePatternToAll(sourceCoId) {
      const qA = document.getElementById(`summ_q_A_${sourceCoId}`).value;
      const mA = document.getElementById(`summ_m_A_${sourceCoId}`).value;
      const qB = document.getElementById(`summ_q_B_${sourceCoId}`).value;
      const mB = document.getElementById(`summ_m_B_${sourceCoId}`).value;
      const qC = document.getElementById(`summ_q_C_${sourceCoId}`).value;
      const mC = document.getElementById(`summ_m_C_${sourceCoId}`).value;

      document.querySelectorAll('[id^="summ_q_A_"]').forEach(el => { if(el.id !== `summ_q_A_${sourceCoId}`) el.value = qA; });
      document.querySelectorAll('[id^="summ_m_A_"]').forEach(el => { if(el.id !== `summ_m_A_${sourceCoId}`) el.value = mA; });
      document.querySelectorAll('[id^="summ_q_B_"]').forEach(el => { if(el.id !== `summ_q_B_${sourceCoId}`) el.value = qB; });
      document.querySelectorAll('[id^="summ_m_B_"]').forEach(el => { if(el.id !== `summ_m_B_${sourceCoId}`) el.value = mB; });
      document.querySelectorAll('[id^="summ_q_C_"]').forEach(el => { if(el.id !== `summ_q_C_${sourceCoId}`) el.value = qC; });
      document.querySelectorAll('[id^="summ_m_C_"]').forEach(el => { if(el.id !== `summ_m_C_${sourceCoId}`) el.value = mC; });

      // Uncheck all other checkboxes to avoid conflict
      document.querySelectorAll('[id^="sync_pattern_"]').forEach(el => {
         if(el.id !== `sync_pattern_${sourceCoId}`) el.checked = false;
         
         // Trigger recalculation on all modified cards
         let c_id = el.id.replace('sync_pattern_', '');
         calcSummativeTotal(c_id);
      });
    }

    function saveSummativeConfig(subjectId, coTag) {
      let dateValue = document.getElementById(`summ_date_${coTag}`).value;
      fetch(`/api/classroom/${subjectId}/save-summative-config`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, date_of_exam: dateValue })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') console.log('Saved date');
      });
    }

    function lockSummativeTest(subjectId, coTag) {
      if(!confirm(`Are you sure you want to lock ${coTag} test? This cannot be easily undone.`)) return;
      fetch(`/api/classroom/${subjectId}/save-summative-config`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, is_locked: true })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') loadCourseDetails(subjectId);
        else alert(data.message);
      });
    }

    let tempSummativePatterns = {};

    function saveSummativePatterns() {
       document.querySelectorAll('[id^="summ_q_A_"]').forEach(el => {
          let coTag = el.id.replace('summ_q_A_', '');
          tempSummativePatterns[coTag] = {
             qA: document.getElementById(`summ_q_A_${coTag}`)?.value || '',
             mA: document.getElementById(`summ_m_A_${coTag}`)?.value || '',
             qB: document.getElementById(`summ_q_B_${coTag}`)?.value || '',
             mB: document.getElementById(`summ_m_B_${coTag}`)?.value || '',
             qC: document.getElementById(`summ_q_C_${coTag}`)?.value || '',
             mC: document.getElementById(`summ_m_C_${coTag}`)?.value || '',
          };
       });
    }

    function toggleSummativeMode(coId) {
       const isManual = document.querySelector(`input[name="summ_mode_${coId}"]:checked`).value === 'manual';
       const btn = document.getElementById(`gen_btn_${coId}`);
       if(btn) {
          if(isManual) {
             btn.innerText = 'Save Custom Questions';
             btn.classList.replace('bg-blue-600/20', 'bg-emerald-600/20');
             btn.classList.replace('hover:bg-blue-600', 'hover:bg-emerald-600');
             btn.classList.replace('border-blue-500/30', 'border-emerald-500/30');
             btn.classList.replace('text-blue-400', 'text-emerald-400');
             
             // Instantly spawn manual question fields
             spawnManualFields(coId);
          } else {
             btn.innerText = 'Generate AI Question Paper';
             btn.classList.replace('bg-emerald-600/20', 'bg-blue-600/20');
             btn.classList.replace('hover:bg-emerald-600', 'hover:bg-blue-600');
             btn.classList.replace('border-emerald-500/30', 'border-blue-500/30');
             btn.classList.replace('text-emerald-400', 'text-blue-400');

             // Remove manual entry fields if switching back to AI
             const wrapper = document.getElementById(`manual_form_wrapper_${coId}`);
             if (wrapper) {
                 wrapper.innerHTML = '';
             }
          }
       }
    }

    function spawnManualFields(coTag) {
      let qA = parseInt(document.getElementById(`summ_q_A_${coTag}`).value) || 0;
      let qB = parseInt(document.getElementById(`summ_q_B_${coTag}`).value) || 0;
      let qC = parseInt(document.getElementById(`summ_q_C_${coTag}`).value) || 0;

      let testData = currentSummativeTests[coTag] || null;
      let savedA = (testData && testData.manual_mode) ? (testData.part_a?.questions || []) : [];
      let savedB = (testData && testData.manual_mode) ? (testData.part_b?.questions || []) : [];
      let savedC = (testData && testData.manual_mode) ? (testData.part_c?.questions || []) : [];

      let html = `<div id="manual_form_${coTag}" class="mt-4 pt-4 border-t border-slate-100">`;
      html += `<div class="text-sm text-slate-800 bg-slate-50/70 p-5 rounded-2xl border border-slate-200 shadow-xs space-y-4">`;
      
      const buildFields = (count, partName, prefix, savedQuestions) => {
         let fHtml = '';
         if(count > 0) fHtml += `<div class="font-bold text-slate-800 border-b border-slate-200 pb-2 text-xs uppercase tracking-wider">${partName}</div><div class="space-y-3 mt-3">`;
         for(let i=0; i<count; i++) {
            let qText = savedQuestions && savedQuestions[i] ? savedQuestions[i].q : '';
            let qLvl = savedQuestions && savedQuestions[i] ? savedQuestions[i].level : 'U';
            fHtml += `
              <div class="flex gap-3 items-start">
                 <span class="text-slate-500 mt-2 font-mono text-xs font-bold">${i+1}.</span>
                 <textarea id="man_q_${prefix}_${coTag}_${i}" class="w-full bg-white border border-slate-200 rounded-lg p-2.5 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 text-sm shadow-2xs placeholder:text-slate-400" rows="2" placeholder="Enter question ${i+1}...">${qText}</textarea>
                 <select id="man_lvl_${prefix}_${coTag}_${i}" class="bg-white border border-slate-200 rounded-lg p-2 text-slate-800 font-medium text-xs w-36 outline-none focus:border-blue-500 shadow-2xs mt-0.5">
                    <option value="U" ${qLvl === 'U' ? 'selected' : ''}>U (Understand)</option>
                    <option value="R" ${qLvl === 'R' ? 'selected' : ''}>R (Remember)</option>
                    <option value="A" ${qLvl === 'A' ? 'selected' : ''}>A (Apply)</option>
                 </select>
              </div>
            `;
         }
         if(count > 0) fHtml += `</div>`;
         return fHtml;
      };

      html += buildFields(qA, 'PART A', 'A', savedA);
      html += buildFields(qB, 'PART B', 'B', savedB);
      html += buildFields(qC, 'PART C', 'C', savedC);
      html += `</div></div>`;

      let wrapper = document.getElementById(`manual_form_wrapper_${coTag}`);
      if (wrapper) {
          wrapper.innerHTML = html;
      }
      
      const btn = document.getElementById(`gen_btn_${coTag}`);
      if (btn) btn.innerText = 'Save Custom Questions';
    }

    function saveManualSummativePaper(subjectId, coTag) {
      let qA = parseInt(document.getElementById(`summ_q_A_${coTag}`).value) || 0;
      let mA = parseInt(document.getElementById(`summ_m_A_${coTag}`).value) || 0;
      let qB = parseInt(document.getElementById(`summ_q_B_${coTag}`).value) || 0;
      let mB = parseInt(document.getElementById(`summ_m_B_${coTag}`).value) || 0;
      let qC = parseInt(document.getElementById(`summ_q_C_${coTag}`).value) || 0;
      let mC = parseInt(document.getElementById(`summ_m_C_${coTag}`).value) || 0;

      let gather = (count, marks, prefix) => {
         let questions = [];
         for(let i=0; i<count; i++) {
            let elQ = document.getElementById(`man_q_${prefix}_${coTag}_${i}`);
            let elL = document.getElementById(`man_lvl_${prefix}_${coTag}_${i}`);
            if(elQ) questions.push({ q: elQ.value, level: elL.value, marks: marks });
         }
         return { q_count: count, marks_per_q: marks, total_marks: count * marks, questions: questions };
      };

      saveSummativePatterns();

      fetch(`/api/classroom/${subjectId}/generate-summative-paper`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ 
           co_tag: coTag, 
           manual_mode: true,
           manual_part_a: gather(qA, mA, 'A'),
           manual_part_b: gather(qB, mB, 'B'),
           manual_part_c: gather(qC, mC, 'C')
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentSummativeTests[coTag] = data.data;
          loadCourseDetails(subjectId);
        } else alert(data.message);
      });
    }

    function generateSummativePaper(subjectId, coTag) {
      const isManual = document.querySelector(`input[name="summ_mode_${coTag}"]:checked`).value === 'manual';
      
      if(isManual) {
         if (document.getElementById(`manual_form_${coTag}`)) {
             saveManualSummativePaper(subjectId, coTag);
         } else {
             spawnManualFields(coTag);
         }
         return;
      }

      saveSummativePatterns();

      let partA = { q_count: document.getElementById(`summ_q_A_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_A_${coTag}`).value };
      let partB = { q_count: document.getElementById(`summ_q_B_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_B_${coTag}`).value };
      let partC = { q_count: document.getElementById(`summ_q_C_${coTag}`).value, marks_per_q: document.getElementById(`summ_m_C_${coTag}`).value };

      fetch(`/api/classroom/${subjectId}/generate-summative-paper`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ co_tag: coTag, part_a: partA, part_b: partB, part_c: partC })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentSummativeTests[coTag] = data.data;
          // Soft re-render the whole summative tab using existing data
          // We don't have cos/students cached globally in a variable cleanly, so let's reload.
          loadCourseDetails(subjectId);
        } else alert(data.message);
      });
    }

    function loadActiveOnlineTests(subjectId) {
      fetch(`/api/classroom/${subjectId}/active-online-tests`)
        .then(res => res.json())
        .then(data => {
          let listDiv = document.getElementById('activeOnlineTestsList');
          if (!listDiv) return;
          if (data.status === 'SUCCESS' && data.data && data.data.length > 0) {
            let html = '';
            data.data.forEach(t => {
              html += `
                <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 mb-3 shadow-2xs">
                  <div class="flex justify-between items-start mb-1.5">
                    <h6 class="font-bold text-purple-700 text-xs">${t.test_name}</h6>
                    <span class="bg-purple-50 text-purple-700 border border-purple-200 px-2 py-0.5 rounded-md text-xs font-bold">${t.duration} Mins</span>
                  </div>
                  <div class="text-xs text-slate-600 mb-3 leading-relaxed">
                    Start: ${t.start_time ? new Date(t.start_time).toLocaleString() : 'Now'}<br>
                    Live Students: <span class="text-emerald-700 font-bold">${t.student_count || 0}</span> | Completed: <span class="text-blue-700 font-bold">${t.completed_count || 0}</span>
                  </div>
                  <div class="grid grid-cols-2 gap-2 mt-2">
                      <button onclick="generateOnlineTestReport('${t.test_id}')" class="w-full py-1.5 bg-white hover:bg-slate-50 text-slate-700 rounded-lg border border-slate-200 flex items-center justify-center gap-1 text-xs font-semibold shadow-2xs transition-premium cursor-pointer" title="Download Results">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg> Report
                      </button>
                      <button onclick="printOnlineTest('${t.test_id}')" class="w-full py-1.5 bg-white hover:bg-slate-50 text-slate-700 rounded-lg border border-slate-200 flex items-center justify-center gap-1 text-xs font-semibold shadow-2xs transition-premium cursor-pointer" title="Print Question Paper with Answers">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Q&A
                      </button>
                      <button onclick="deleteOnlineTest('${t.test_id}', '${subjectId}')" class="col-span-2 w-full py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg border border-rose-200 flex items-center justify-center gap-1 text-xs font-semibold shadow-2xs transition-premium cursor-pointer" title="Delete Test">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg> Delete
                      </button>
                    </div>
                </div>
              `;
            });
            listDiv.innerHTML = html;
          } else {
            listDiv.innerHTML = `<div class="p-4 bg-white border border-slate-200 rounded-xl text-center border-dashed text-slate-500 font-medium text-xs">No active online tests found.</div>`;
          }
        });
    }

    function publishOnlineTest(subjectId) {
      const selectElement = document.getElementById('online_test_cos');
      const selectedCos = Array.from(selectElement.selectedOptions).map(opt => opt.value);
      const attempts = document.getElementById('online_test_attempts').value;
      const duration = document.getElementById('online_test_duration').value;
      const start = document.getElementById('online_test_start').value;
      const end = document.getElementById('online_test_end').value;
      const q_count = document.getElementById('online_test_q_count').value;
      const gen_mode = document.getElementById('online_test_gen_mode').value;

      if (selectedCos.length === 0) {
        alert("Please select at least one CO.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/publish-online-test`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ cos: selectedCos, attempts, duration, start, end, q_count, generation_mode: gen_mode })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert("Online Test successfully published!");
          loadActiveOnlineTests(subjectId);
          
          // Clear inputs
          selectElement.selectedIndex = -1;
          if (document.getElementById('online_test_start')._flatpickr) document.getElementById('online_test_start')._flatpickr.clear();
          if (document.getElementById('online_test_end')._flatpickr) document.getElementById('online_test_end')._flatpickr.clear();
        } else {
          alert(data.message || "Failed to publish test.");
        }
      });
    }

    function generateOnlineTestReport(testId) {
      fetch(`/api/test-engine/report/${testId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const test = data.test_info;
            const attempts = data.report;
            
            let tableRows = '';
            if(attempts && attempts.length > 0) {
              attempts.forEach(a => {
                 let start = new Date(a.start_time);
                 let end = new Date(a.end_time);
                 let timeTakenStr = '-';
                 if(a.start_time && a.end_time) {
                    let diffMs = end - start;
                    let diffMins = Math.floor(diffMs / 60000);
                    let diffSecs = Math.floor((diffMs % 60000) / 1000);
                    timeTakenStr = `${diffMins}m ${diffSecs}s`;
                 }
                 
                 tableRows += `
                   <tr>
                     <td style="padding: 8px; border: 1px solid #ddd; font-family: monospace;">${a.reg_no}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">${a.name}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${a.attempt_number}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${timeTakenStr}</td>
                     <td style="padding: 8px; border: 1px solid #ddd; text-align: center; font-weight: bold; font-size: 14px;">${a.total_score}</td>
                   </tr>
                 `;
              });
            } else {
              tableRows = `<tr><td colspan="5" style="padding: 16px; text-align: center; border: 1px solid #ddd;">No completed attempts yet.</td></tr>`;
            }

            const html = `<!DOCTYPE html>
            <html>
            <head>
              <title>${test.test_name} - Report</title>
              <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
    @media (max-width: 640px) {
      .mobile-sem-btn {
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding-top: 0.625rem !important;
        padding-bottom: 0.625rem !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
      }
      .mobile-sem-btn span:first-child {
        font-size: 12px !important;
        margin-bottom: 0.125rem !important;
      }
      .mobile-sem-btn span:last-child {
        font-size: 14px !important;
      }
      #mobileSeminarNotificationsContainer h4,
      #seminarNotificationsContainer h4 {
        font-size: 16px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }

    /* CampusLynk Modern Virtual Classroom Panel Styles */
    #panelClassroom {
      background-color: #FAFAFB !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1.5rem !important;
      padding: 1.5rem !important;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05) !important;
    }

    #panelClassroom, 
    #panelClassroom button,
    #panelClassroom select,
    #panelClassroom input,
    #panelClassroom table,
    #panelClassroom th,
    #panelClassroom td,
    #panelClassroom div,
    #panelClassroom p,
    #panelClassroom h3,
    #panelClassroom h4,
    #panelClassroom h5,
    #panelClassroom span {
      font-size: 14px;
    }
    
    #panelClassroom h3#vcTitle,
    #panelClassroom h3#vcTitle span {
      font-size: 18px !important;
      font-weight: 700 !important;
    }
    
    #panelClassroom #vcSubtitle,
    #panelClassroom #vcSubtitle span {
      font-size: 14px !important;
    }
    
    #panelClassroom #vcViewStudentsBtn,
    #panelClassroom #vcViewStudentsBtn span {
      font-size: 14px !important;
      font-weight: 600 !important;
    }

    #panelClassroom .classroom-tab-btn {
      font-size: 14px !important;
      font-weight: 600 !important;
      padding: 0.5rem 0.875rem !important;
      border-radius: 0.75rem !important;
      transition: all 0.15s ease !important;
    }

    #panelClassroom h4, 
    #panelClassroom h5 {
      font-size: 16px !important;
      font-weight: 700 !important;
    }

    /* Manual mark entry table title, names, and internal grid data font sizes */
    #manualMarksWrapper table th,
    #manualMarksWrapper table td,
    #manualMarksWrapper input,
    #manualMarksWrapper span {
      font-size: 14px !important;
    }
    
    #manualMarksWrapper table td {
      padding: 12px 10px !important;
    }

    /* Flatpickr date picker light theme styling */
    .flatpickr-calendar {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1rem !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-month,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .flatpickr-weekday,
    .flatpickr-calendar .flatpickr-days .flatpickr-day {
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.prevMonthDay:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.nextMonthDay:hover {
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day.selected {
      background: #2563eb !important;
      color: #ffffff !important;
    }
    .flatpickr-calendar .flatpickr-current-month span.cur-month,
    .flatpickr-calendar .numInputWrapper span,
    .flatpickr-calendar input.numInput {
      color: #0f172a !important;
      font-weight: 600 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
      color: #2563eb !important;
      fill: #2563eb !important;
    }

    /* Mobile styles for Virtual Classroom assessment mark entry */
    @media (max-width: 767px) {
      .co-mark, .summ-mark,
      #manualMarksWrapper input,
      #markEntryTbody input,
      #summativeMarkEntryTbody input {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        color: #0f172a !important;
      }

      /* Transform assignment mark entry tables into list cards on mobile view */
      #markEntryTbody,
      #markEntryTbody tr,
      #markEntryTbody td,
      #manualMarksWrapper tbody,
      #manualMarksWrapper tr,
      #manualMarksWrapper td {
        display: block !important;
      }
      
      #panelClassroom table thead {
        display: none !important;
      }
      
      #markEntryTbody tr,
      #manualMarksWrapper table tr {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.65rem !important;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05) !important;
      }
      
      #markEntryTbody td,
      #manualMarksWrapper td {
        padding: 0.35rem 0 !important;
        border: none !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        text-align: left !important;
        font-size: 14px !important;
      }
      
      #markEntryTbody td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(5)::before { content: "CO1 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(6)::before { content: "CO2 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(7)::before { content: "CO3 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(8)::before { content: "CO4 (10): "; font-weight: bold; color: #2563eb; }
      
      #manualMarksWrapper td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(5)::before { content: "CO1: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(6)::before { content: "CO2: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(7)::before { content: "CO3: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(8)::before { content: "CO4: "; font-weight: bold; color: #2563eb; }
      
      #markEntryTbody td div.relative,
      #manualMarksWrapper td div.relative {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
      }
      #markEntryTbody td input,
      #manualMarksWrapper td input {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
        text-align: center !important;
      }

      #mobileSeminarNotificationsContainer h5,
      #seminarNotificationsContainer h5 {
        font-size: 15px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }
  </style>
            </head>
            <body>
              <div style="text-align: center;">
                <h2>Online Test Evaluation Report</h2>
                <div class="meta">
                  <strong>Test Name:</strong> ${test.test_name} <br>
                  <strong>Subject Code:</strong> ${test.subject_code} <br>
                  <strong>Total MCQs:</strong> ${test.mcq_count} | <strong>Duration:</strong> ${test.duration} Mins<br>
                  <strong>Generated On:</strong> ${new Date().toLocaleString()}
                </div>
              </div>
              
              <table>
                <thead>
                  <tr>
                    <th>Reg No</th>
                    <th>Student Name</th>
                    <th class="center">Attempts Used</th>
                    <th class="center">Time Taken</th>
                    <th class="center">Marks Obtained</th>
                  </tr>
                </thead>
                <tbody>
                  ${tableRows}
                </tbody>
              </table>
              <script>
                window.onload = () => { window.print(); window.close(); }
              <\/script>
            </body>
            </html>`;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(html);
            printWindow.document.close();
          } else {
            alert(data.message || "Failed to generate report.");
          }
        });
    }

    function saveSummativeMarks(subjectId) {
      let marksPayload = [];
      const rows = document.querySelectorAll('#summativeMarkEntryTbody tr[data-reg]');
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const inputs = row.querySelectorAll('.summ-mark');
        inputs.forEach(input => {
          if (input.value !== '') {
            marksPayload.push({
              reg_no: regNo,
              co_tag: input.getAttribute('data-co'),
              marks_obtained: input.value
            });
          }
        });
      });

      if (marksPayload.length === 0) {
        alert("No marks entered.");
        return;
      }

      fetch(`/api/classroom/${subjectId}/save-written-test-marks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ marks: marksPayload })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') alert("Written Marks successfully saved!");
        else alert(data.message || "Failed to save marks.");
      });
    }

    function printAssignmentReport(subjectId) {
      window.open(`/classroom/${subjectId}/assignment-report`, '_blank');
    }

    function printAssignmentPaperAndRubrics(subjectId, coTag) {
      window.open(`/classroom/${subjectId}/assignment-print/${coTag}`, '_blank');
    }

    function printSummativeReport(subjectId) {
      window.open(`/classroom/${subjectId}/summative-report`, '_blank');
    }
    function printSummativePaper(coTag, totalMarks) {
      const data = currentSummativeTests[coTag];
      if(!data) return;

      const deptMap = {
        'EL': 'ELECTRONICS ENGINEERING',
        'CS': 'COMPUTER SCIENCE AND ENGINEERING',
        'CE': 'CIVIL ENGINEERING',
        'ME': 'MECHANICAL ENGINEERING',
        'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
        'IT': 'INFORMATION TECHNOLOGY',
        'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
      };
      const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
      const lecturerName = "{{ session('userName', 'Faculty Name') }}";
      const subjectName = currentSubjectName;
      const subjectCode = currentSubjectCode;
      const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
      const examDate = data.date_of_exam
        ? new Date(data.date_of_exam).toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'})
        : 'TBA';

      let questionsToSolve = [];
      const collectQuestions = (part) => {
        if (part && part.questions) {
          part.questions.forEach(q => {
            questionsToSolve.push({ q: q.q, marks: q.marks });
          });
        }
      };
      collectQuestions(data.part_a);
      collectQuestions(data.part_b);
      collectQuestions(data.part_c);

      const proceedWithPrint = (geminiData) => {
        const getGeminiInfo = (qText) => {
          if (!geminiData) return null;
          return geminiData.find(item => item.q === qText || item.q.includes(qText) || qText.includes(item.q));
        };

        const buildRows = (part) => {
          if (!part || !part.q_count || !part.questions) return '';
          return part.questions.map((q, i) => {
            let lvl = q.level || '';
            if (lvl === 'R') lvl = 'Remember';
            else if (lvl === 'U') lvl = 'Understand';
            else if (lvl === 'A') lvl = 'Apply';
            return `<tr>
              <td style="border: 1px solid #000; padding: 4px; text-align: center; vertical-align: top;">${i+1}</td>
              <td style="border: 1px solid #000; padding: 4px; vertical-align: top;">${q.q}</td>
              <td style="border: 1px solid #000; padding: 4px; text-align: center; vertical-align: top;">${coTag}</td>
              <td style="border: 1px solid #000; padding: 4px; text-align: center; vertical-align: top;">${lvl}</td>
            </tr>`;
          }).join('');
        };

        let bodyHtml = '';

        if (data.part_a && data.part_a.q_count > 0) {
          bodyHtml += `
            <h4 style="text-align:center;font-weight:bold;margin:10px 0 4px; font-size:12px;">PART A &nbsp;<small style="font-weight:normal;font-size:11px;">(${data.part_a.q_count} × ${data.part_a.marks_per_q} = ${data.part_a.total_marks} Marks)</small></h4>
            <p style="text-align:center;font-style:italic;font-size:11px;margin:0 0 6px;">Answer all questions.</p>
            <table style="width:100%;border-collapse:collapse;font-size:12px;border:1px solid #000; margin-bottom:10px;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:45px; text-align:center;">Q.No.</th>
                  <th style="border:1px solid #000; padding:4px; text-align:left;">Question</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Module Outcome</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Cognitive Level</th>
                </tr>
              </thead>
              <tbody>${buildRows(data.part_a)}</tbody>
            </table>`;
        }
        if (data.part_b && data.part_b.q_count > 0) {
          bodyHtml += `
            <h4 style="text-align:center;font-weight:bold;margin:12px 0 4px; font-size:12px;">PART B &nbsp;<small style="font-weight:normal;font-size:11px;">(${data.part_b.q_count} × ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
            <p style="text-align:center;font-style:italic;font-size:11px;margin:0 0 6px;">Answer all questions.</p>
            <table style="width:100%;border-collapse:collapse;font-size:12px;border:1px solid #000; margin-bottom:10px;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:45px; text-align:center;">Q.No.</th>
                  <th style="border:1px solid #000; padding:4px; text-align:left;">Question</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Module Outcome</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Cognitive Level</th>
                </tr>
              </thead>
              <tbody>${buildRows(data.part_b)}</tbody>
            </table>`;
        }
        if (data.part_c && data.part_c.q_count > 0) {
          bodyHtml += `
            <h4 style="text-align:center;font-weight:bold;margin:12px 0 4px; font-size:12px;">PART C &nbsp;<small style="font-weight:normal;font-size:11px;">(${data.part_c.q_count} × ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
            <p style="text-align:center;font-style:italic;font-size:11px;margin:0 0 6px;">Answer all questions.</p>
            <table style="width:100%;border-collapse:collapse;font-size:12px;border:1px solid #000; margin-bottom:10px;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:45px; text-align:center;">Q.No.</th>
                  <th style="border:1px solid #000; padding:4px; text-align:left;">Question</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Module Outcome</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Cognitive Level</th>
                </tr>
              </thead>
              <tbody>${buildRows(data.part_c)}</tbody>
            </table>`;
        }

        // Calculate Cognitive Level wise Question Analysis
        let counts = {
          A: { R: 0, U: 0, A: 0, total: 0, marksPerQ: data.part_a?.marks_per_q || 1 },
          B: { R: 0, U: 0, A: 0, total: 0, marksPerQ: data.part_b?.marks_per_q || 3 },
          C: { R: 0, U: 0, A: 0, total: 0, marksPerQ: data.part_c?.marks_per_q || 7 }
        };

        if (data.part_a && data.part_a.questions) {
          data.part_a.questions.forEach(q => {
            let lvl = (q.level || 'R').toUpperCase()[0];
            if (counts.A[lvl] !== undefined) counts.A[lvl]++;
            counts.A.total++;
          });
        }
        if (data.part_b && data.part_b.questions) {
          data.part_b.questions.forEach(q => {
            let lvl = (q.level || 'U').toUpperCase()[0];
            if (counts.B[lvl] !== undefined) counts.B[lvl]++;
            counts.B.total++;
          });
        }
        if (data.part_c && data.part_c.questions) {
          data.part_c.questions.forEach(q => {
            let lvl = (q.level || 'A').toUpperCase()[0];
            if (counts.C[lvl] !== undefined) counts.C[lvl]++;
            counts.C.total++;
          });
        }

        let rMarks = (counts.A.R * counts.A.marksPerQ) + (counts.B.R * counts.B.marksPerQ) + (counts.C.R * counts.C.marksPerQ);
        let uMarks = (counts.A.U * counts.A.marksPerQ) + (counts.B.U * counts.B.marksPerQ) + (counts.C.U * counts.C.marksPerQ);
        let aMarks = (counts.A.A * counts.A.marksPerQ) + (counts.B.A * counts.B.marksPerQ) + (counts.C.A * counts.C.marksPerQ);
        let totalCalculatedMarks = rMarks + uMarks + aMarks;

        let cognitiveTableHtml = `
          <div style="margin-top:15px; page-break-inside: avoid;">
            <h4 style="text-align:center; font-weight:bold; margin-bottom:6px; text-decoration: underline; font-size:12px;">Cognitive level wise Question Analysis</h4>
            <table style="width:100%; border:1px solid #000; border-collapse:collapse; font-size:11px; text-align:center;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; text-align:left;" rowspan="2"></th>
                  <th style="border:1px solid #000; padding:4px;" colspan="3">Cognitive Level</th>
                  <th style="border:1px solid #000; padding:4px;" rowspan="2">No. of Questions</th>
                </tr>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:150px;">Remember</th>
                  <th style="border:1px solid #000; padding:4px; width:150px;">Understand</th>
                  <th style="border:1px solid #000; padding:4px; width:150px;">Apply</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="border:1px solid #000; padding:4px; text-align:left; font-weight:bold;">Part A (${counts.A.marksPerQ} mark${counts.A.marksPerQ > 1 ? 's' : ''})</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.A.R || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.A.U || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.A.A || '0'}</td>
                  <td style="border:1px solid #000; padding:4px; font-weight:bold;">${counts.A.total || '0'}</td>
                </tr>
                <tr>
                  <td style="border:1px solid #000; padding:4px; text-align:left; font-weight:bold;">Part B (${counts.B.marksPerQ} marks)</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.B.R || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.B.U || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.B.A || '0'}</td>
                  <td style="border:1px solid #000; padding:4px; font-weight:bold;">${counts.B.total || '0'}</td>
                </tr>
                <tr>
                  <td style="border:1px solid #000; padding:4px; text-align:left; font-weight:bold;">Part C (${counts.C.marksPerQ} marks)</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.C.R || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.C.U || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.C.A || '0'}</td>
                  <td style="border:1px solid #000; padding:4px; font-weight:bold;">${counts.C.total || '0'}</td>
                </tr>
                <tr style="background-color:#fafafa; font-weight:bold;">
                  <td style="border:1px solid #000; padding:4px; text-align:left;">Marks</td>
                  <td style="border:1px solid #000; padding:4px;">${rMarks}</td>
                  <td style="border:1px solid #000; padding:4px;">${uMarks}</td>
                  <td style="border:1px solid #000; padding:4px;">${aMarks}</td>
                  <td style="border:1px solid #000; padding:4px;">Total Marks = ${totalCalculatedMarks}</td>
                </tr>
              </tbody>
            </table>
          </div>
        `;

        let signatureBlockHtml = `
          <div style="margin-top: 25px; display: flex; justify-content: space-between; font-size: 11px; page-break-inside: avoid; border-top: 1px dashed #000; padding-top: 8px;">
            <div><strong>Prepared By:</strong> ${lecturerName} (Course Coordinator)</div>
            <div><strong>Verified By:</strong> Faculty Name (Module Coordinator)</div>
            <div><strong>Approved By:</strong> HOD</div>
          </div>
        `;

        // Build Scheme of Valuation Rows dynamically
        const buildSchemeRows = () => {
          let rowsHtml = '';
          const processPart = (part, partLabel) => {
            if (!part || !part.questions || part.questions.length === 0) return;
            rowsHtml += `
              <tr style="background: #f2f2f2; font-weight: bold;">
                <td colspan="5" style="border: 1px solid #000; padding: 6px; text-align: left; text-transform: uppercase;">${partLabel}</td>
              </tr>
            `;
            part.questions.forEach((q, i) => {
              let geminiInfo = getGeminiInfo(q.q);
              let rubric = (geminiInfo && geminiInfo.rubric) ? geminiInfo.rubric : (q.rubric || []);
              let answers = (geminiInfo && geminiInfo.ans) ? geminiInfo.ans : (q.ans || []);

              if (rubric.length === 0) {
                let marks = q.marks || 1;
                if (marks <= 2) rubric = [{desc: 'Correct answer / explanation', mark: marks}];
                else rubric = [{desc: 'Key definition / concept', mark: 1}, {desc: 'Correct steps & final answer', mark: marks - 1}];
              }
              let rSpan = rubric.length;

              let answersHtml = '';
              if (answers && answers.length > 0) {
                answersHtml = `<div style="margin-bottom: 6px; font-size: 11px; color: #333;">
                  <strong>Expected Answer Key / Suggestions:</strong>
                  <ul style="margin: 2px 0 4px 14px; padding: 0; list-style-type: disc;">
                    ${answers.map(pt => `<li>${pt}</li>`).join('')}
                  </ul>
                </div>`;
              }

              rubric.forEach((r, rIdx) => {
                rowsHtml += `<tr>`;
                if (rIdx === 0) {
                  rowsHtml += `<td rowspan="${rSpan}" style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold;">${i + 1}</td>`;
                }
                
                let cellContent = '';
                if (rIdx === 0 && answersHtml) {
                  cellContent += answersHtml + `<div style="margin-top: 6px; border-top: 1px dashed #ccc; padding-top: 4px; font-weight: bold; font-size: 11px;">Scoring Indicator Split-up:</div>`;
                }
                cellContent += `<div style="padding-left: 6px; font-size: 11px;">&bull; ${r.desc}</div>`;

                rowsHtml += `
                  <td style="border: 1px solid #000; padding: 6px; vertical-align: top; text-align: left;">${cellContent}</td>
                  <td style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: top; font-weight: bold;">${r.mark}</td>
                `;
                if (rIdx === 0) {
                  rowsHtml += `
                    <td rowspan="${rSpan}" style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold;">${q.marks}</td>
                    <td rowspan="${rSpan}" style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold;">${q.marks}</td>
                  `;
                }
                rowsHtml += `</tr>`;
              });
            });
          };
          processPart(data.part_a, 'Part A');
          processPart(data.part_b, 'Part B');
          processPart(data.part_c, 'Part C');
          return rowsHtml;
        };

        const schemeTableHtml = `
          <div style="page-break-before: always; padding-top: 20px;">
            <div class="header">
              <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
              <div class="dept-name">Department of ${deptName}</div>
              <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
              <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; SCHEME OF VALUATION&nbsp;</span></div>
              <div class="meta-row" style="margin-top: 8px; font-size: 11px;">
                <span><strong>Semester:</strong> Sem ${currentSubjectSemester}</span>
                <span><strong>Batch:</strong> ${currentSubjectClassroomId.replace(/^[A-Z]+_/, '').replace(/_/g, ' - ')}</span>
                <span><strong>Academic Year:</strong> ${currentSubjectAcademicYear}</span>
              </div>
              <div class="meta-row" style="margin-top: 4px; font-size: 11px;">
                <span><strong>Time:</strong> 1.5 Hours</span>
                <span><strong>Date:</strong> ${examDate}</span>
                <span><strong>Max Marks:</strong> ${totalMarks}</span>
              </div>
            </div>
            <table style="width: 100%; border: 1px solid #000; border-collapse: collapse; font-size: 12px;">
              <thead>
                <tr style="background: #f2f2f2; font-weight: bold;">
                  <th style="border: 1px solid #000; padding: 6px; width: 60px; text-align: center;">Q. No.</th>
                  <th style="border: 1px solid #000; padding: 6px; text-align: left;">Scoring Indicators</th>
                  <th style="border: 1px solid #000; padding: 6px; width: 70px; text-align: center;">Split Up</th>
                  <th style="border: 1px solid #000; padding: 6px; width: 70px; text-align: center;">Sub Total</th>
                  <th style="border: 1px solid #000; padding: 6px; width: 70px; text-align: center;">Total</th>
                </tr>
              </thead>
              <tbody>
                ${buildSchemeRows()}
              </tbody>
            </table>
          </div>
        `;

        const fullHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Question Paper - ${coTag}</title>
  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
    @media (max-width: 640px) {
      .mobile-sem-btn {
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding-top: 0.625rem !important;
        padding-bottom: 0.625rem !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
      }
      .mobile-sem-btn span:first-child {
        font-size: 12px !important;
        margin-bottom: 0.125rem !important;
      }
      .mobile-sem-btn span:last-child {
        font-size: 14px !important;
      }
      #mobileSeminarNotificationsContainer h4,
      #seminarNotificationsContainer h4 {
        font-size: 16px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }

    /* CampusLynk Modern Virtual Classroom Panel Styles */
    #panelClassroom {
      background-color: #FAFAFB !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1.5rem !important;
      padding: 1.5rem !important;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05) !important;
    }

    #panelClassroom, 
    #panelClassroom button,
    #panelClassroom select,
    #panelClassroom input,
    #panelClassroom table,
    #panelClassroom th,
    #panelClassroom td,
    #panelClassroom div,
    #panelClassroom p,
    #panelClassroom h3,
    #panelClassroom h4,
    #panelClassroom h5,
    #panelClassroom span {
      font-size: 14px;
    }
    
    #panelClassroom h3#vcTitle,
    #panelClassroom h3#vcTitle span {
      font-size: 18px !important;
      font-weight: 700 !important;
    }
    
    #panelClassroom #vcSubtitle,
    #panelClassroom #vcSubtitle span {
      font-size: 14px !important;
    }
    
    #panelClassroom #vcViewStudentsBtn,
    #panelClassroom #vcViewStudentsBtn span {
      font-size: 14px !important;
      font-weight: 600 !important;
    }

    #panelClassroom .classroom-tab-btn {
      font-size: 14px !important;
      font-weight: 600 !important;
      padding: 0.5rem 0.875rem !important;
      border-radius: 0.75rem !important;
      transition: all 0.15s ease !important;
    }

    #panelClassroom h4, 
    #panelClassroom h5 {
      font-size: 16px !important;
      font-weight: 700 !important;
    }

    /* Manual mark entry table title, names, and internal grid data font sizes */
    #manualMarksWrapper table th,
    #manualMarksWrapper table td,
    #manualMarksWrapper input,
    #manualMarksWrapper span {
      font-size: 14px !important;
    }
    
    #manualMarksWrapper table td {
      padding: 12px 10px !important;
    }

    /* Flatpickr date picker light theme styling */
    .flatpickr-calendar {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1rem !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-month,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .flatpickr-weekday,
    .flatpickr-calendar .flatpickr-days .flatpickr-day {
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.prevMonthDay:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.nextMonthDay:hover {
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day.selected {
      background: #2563eb !important;
      color: #ffffff !important;
    }
    .flatpickr-calendar .flatpickr-current-month span.cur-month,
    .flatpickr-calendar .numInputWrapper span,
    .flatpickr-calendar input.numInput {
      color: #0f172a !important;
      font-weight: 600 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
      color: #2563eb !important;
      fill: #2563eb !important;
    }

    /* Mobile styles for Virtual Classroom assessment mark entry */
    @media (max-width: 767px) {
      .co-mark, .summ-mark,
      #manualMarksWrapper input,
      #markEntryTbody input,
      #summativeMarkEntryTbody input {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        color: #0f172a !important;
      }

      /* Transform assignment mark entry tables into list cards on mobile view */
      #markEntryTbody,
      #markEntryTbody tr,
      #markEntryTbody td,
      #manualMarksWrapper tbody,
      #manualMarksWrapper tr,
      #manualMarksWrapper td {
        display: block !important;
      }
      
      #panelClassroom table thead {
        display: none !important;
      }
      
      #markEntryTbody tr,
      #manualMarksWrapper table tr {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.65rem !important;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05) !important;
      }
      
      #markEntryTbody td,
      #manualMarksWrapper td {
        padding: 0.35rem 0 !important;
        border: none !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        text-align: left !important;
        font-size: 14px !important;
      }
      
      #markEntryTbody td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(5)::before { content: "CO1 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(6)::before { content: "CO2 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(7)::before { content: "CO3 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(8)::before { content: "CO4 (10): "; font-weight: bold; color: #2563eb; }
      
      #manualMarksWrapper td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(5)::before { content: "CO1: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(6)::before { content: "CO2: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(7)::before { content: "CO3: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(8)::before { content: "CO4: "; font-weight: bold; color: #2563eb; }
      
      #markEntryTbody td div.relative,
      #manualMarksWrapper td div.relative {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
      }
      #markEntryTbody td input,
      #manualMarksWrapper td input {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
        text-align: center !important;
      }

      #mobileSeminarNotificationsContainer h5,
      #seminarNotificationsContainer h5 {
        font-size: 15px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; Written Test&nbsp;</span></div>
    <div class="meta-row" style="margin-top: 8px; font-size: 11px;">
      <span><strong>Semester:</strong> Sem ${currentSubjectSemester}</span>
      <span><strong>Batch:</strong> ${currentSubjectClassroomId.replace(/^[A-Z]+_/, '').replace(/_/g, ' - ')}</span>
      <span><strong>Academic Year:</strong> ${currentSubjectAcademicYear}</span>
    </div>
    <div class="meta-row" style="margin-top: 4px; font-size: 11px;">
      <span><strong>Time:</strong> 1.5 Hours</span>
      <span><strong>Date:</strong> ${examDate}</span>
      <span><strong>Max Marks:</strong> ${totalMarks}</span>
    </div>
  </div>
  ${bodyHtml}
  ${cognitiveTableHtml}
  ${signatureBlockHtml}
  ${schemeTableHtml}
</body>
</html>`;

        const pw = window.open('', '_blank', 'width=900,height=700');
        pw.document.write(fullHtml);
        pw.document.close();
        pw.focus();
        setTimeout(() => { pw.print(); }, 400);
      };

      proceedWithPrint(null);
    }

    function printAnswerKey(coTag, totalMarks) {
      const data = currentSummativeTests[coTag];
      if(!data) return;

      const deptMap = {
        'EL': 'ELECTRONICS ENGINEERING',
        'CS': 'COMPUTER SCIENCE AND ENGINEERING',
        'CE': 'CIVIL ENGINEERING',
        'ME': 'MECHANICAL ENGINEERING',
        'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
        'IT': 'INFORMATION TECHNOLOGY',
        'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
      };
      const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
      const subjectName = currentSubjectName;
      const subjectCode = currentSubjectCode;
      const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
      const examDate = data.date_of_exam
        ? new Date(data.date_of_exam).toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'})
        : 'TBA';

      const buildRubricHtml = (rubric, marks) => {
        // Fallback for older generated papers that don't have a rubric saved
        if (!rubric || rubric.length === 0) {
            if (marks <= 2) rubric = [{desc: 'Correct definition / answer', mark: marks}];
            else if (marks <= 4) rubric = [{desc: 'Key definition / concept', mark: 1}, {desc: 'Explanation / relevant points', mark: marks - 1}];
            else rubric = [{desc: 'Definition / Concept statement', mark: 1}, {desc: 'Explanation with supporting points', mark: Math.floor(marks/2)}, {desc: 'Diagram / Application', mark: marks - Math.floor(marks/2) - 1}];
        }

        return `<table style="width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 4px; background: #fafafa;">
          ${rubric.map(r => `<tr>
            <td style="padding: 3px 6px; border: 1px solid #ddd;">${r.desc}</td>
            <td style="padding: 3px 6px; text-align: center; width: 50px; border: 1px solid #ddd; font-weight: bold; color: #444;">${r.mark}</td>
          </tr>`).join('')}
        </table>`;
      };

      const buildRows = (part) => {
        if (!part || !part.q_count || !part.questions) return '';
        return part.questions.map((q, i) => {
          let ansHtml = '';
          if (q.ans && q.ans.length > 0) {
            ansHtml = `<div style="margin-bottom: 8px; font-size: 12px; color: #333;">
              <ul style="margin: 0; padding-left: 16px;">
                ${q.ans.map(a => `<li style="margin-bottom: 3px;">${a}</li>`).join('')}
              </ul>
            </div>`;
          }
          
          return `<tr>
            <td style="width: 40px; text-align: center; vertical-align: top; padding: 10px 5px; border: 1px solid #000; font-weight: bold;">${i+1}</td>
            <td style="vertical-align: top; padding: 10px; border: 1px solid #000;">
              <div style="font-weight: 500; margin-bottom: 6px; font-size: 13px;">${q.q}</div>
              ${ansHtml}
              <div style="font-size: 11px; font-weight: bold; color: #555; margin-bottom: 2px; margin-top: 6px;">Marking Scheme / Answer Pointers:</div>
              ${buildRubricHtml(q.rubric, q.marks)}
            </td>
            <td style="width: 80px; text-align: center; vertical-align: middle; padding: 10px 5px; border: 1px solid #000; font-size: 14px; font-weight: bold;">${q.marks}</td>
            <td style="width: 60px; text-align: center; vertical-align: middle; padding: 10px 5px; border: 1px solid #000; font-size: 11px;">[${q.level}]</td>
          </tr>`;
        }).join('');
      };

      let bodyHtml = '';

      const tableHeader = `
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
          <thead>
            <tr>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 40px;">Q.No</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee;">Question & Expected Answer Key</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 80px;">Marks</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 60px;">Level</th>
            </tr>
          </thead>
          <tbody>
      `;

      if (data.part_a && data.part_a.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART A <small style="font-weight:normal; font-size:12px;">(${data.part_a.q_count} Ã ${data.part_a.marks_per_q} = ${data.part_a.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_a)}</tbody></table>`;
      }
      if (data.part_b && data.part_b.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART B <small style="font-weight:normal; font-size:12px;">(${data.part_b.q_count} Ã ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_b)}</tbody></table>`;
      }
      if (data.part_c && data.part_c.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART C <small style="font-weight:normal; font-size:12px;">(${data.part_c.q_count} Ã ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_c)}</tbody></table>`;
      }

      const fullHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Answer Key - ${coTag}</title>
  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
    @media (max-width: 640px) {
      .mobile-sem-btn {
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding-top: 0.625rem !important;
        padding-bottom: 0.625rem !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
      }
      .mobile-sem-btn span:first-child {
        font-size: 12px !important;
        margin-bottom: 0.125rem !important;
      }
      .mobile-sem-btn span:last-child {
        font-size: 14px !important;
      }
      #mobileSeminarNotificationsContainer h4,
      #seminarNotificationsContainer h4 {
        font-size: 16px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }

    /* CampusLynk Modern Virtual Classroom Panel Styles */
    #panelClassroom {
      background-color: #FAFAFB !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1.5rem !important;
      padding: 1.5rem !important;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05) !important;
    }

    #panelClassroom, 
    #panelClassroom button,
    #panelClassroom select,
    #panelClassroom input,
    #panelClassroom table,
    #panelClassroom th,
    #panelClassroom td,
    #panelClassroom div,
    #panelClassroom p,
    #panelClassroom h3,
    #panelClassroom h4,
    #panelClassroom h5,
    #panelClassroom span {
      font-size: 14px;
    }
    
    #panelClassroom h3#vcTitle,
    #panelClassroom h3#vcTitle span {
      font-size: 18px !important;
      font-weight: 700 !important;
    }
    
    #panelClassroom #vcSubtitle,
    #panelClassroom #vcSubtitle span {
      font-size: 14px !important;
    }
    
    #panelClassroom #vcViewStudentsBtn,
    #panelClassroom #vcViewStudentsBtn span {
      font-size: 14px !important;
      font-weight: 600 !important;
    }

    #panelClassroom .classroom-tab-btn {
      font-size: 14px !important;
      font-weight: 600 !important;
      padding: 0.5rem 0.875rem !important;
      border-radius: 0.75rem !important;
      transition: all 0.15s ease !important;
    }

    #panelClassroom h4, 
    #panelClassroom h5 {
      font-size: 16px !important;
      font-weight: 700 !important;
    }

    /* Manual mark entry table title, names, and internal grid data font sizes */
    #manualMarksWrapper table th,
    #manualMarksWrapper table td,
    #manualMarksWrapper input,
    #manualMarksWrapper span {
      font-size: 14px !important;
    }
    
    #manualMarksWrapper table td {
      padding: 12px 10px !important;
    }

    /* Flatpickr date picker light theme styling */
    .flatpickr-calendar {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1rem !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-month,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .flatpickr-weekday,
    .flatpickr-calendar .flatpickr-days .flatpickr-day {
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.prevMonthDay:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.nextMonthDay:hover {
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day.selected {
      background: #2563eb !important;
      color: #ffffff !important;
    }
    .flatpickr-calendar .flatpickr-current-month span.cur-month,
    .flatpickr-calendar .numInputWrapper span,
    .flatpickr-calendar input.numInput {
      color: #0f172a !important;
      font-weight: 600 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
      color: #2563eb !important;
      fill: #2563eb !important;
    }

    /* Mobile styles for Virtual Classroom assessment mark entry */
    @media (max-width: 767px) {
      .co-mark, .summ-mark,
      #manualMarksWrapper input,
      #markEntryTbody input,
      #summativeMarkEntryTbody input {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        color: #0f172a !important;
      }

      /* Transform assignment mark entry tables into list cards on mobile view */
      #markEntryTbody,
      #markEntryTbody tr,
      #markEntryTbody td,
      #manualMarksWrapper tbody,
      #manualMarksWrapper tr,
      #manualMarksWrapper td {
        display: block !important;
      }
      
      #panelClassroom table thead {
        display: none !important;
      }
      
      #markEntryTbody tr,
      #manualMarksWrapper table tr {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.65rem !important;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05) !important;
      }
      
      #markEntryTbody td,
      #manualMarksWrapper td {
        padding: 0.35rem 0 !important;
        border: none !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        text-align: left !important;
        font-size: 14px !important;
      }
      
      #markEntryTbody td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(5)::before { content: "CO1 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(6)::before { content: "CO2 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(7)::before { content: "CO3 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(8)::before { content: "CO4 (10): "; font-weight: bold; color: #2563eb; }
      
      #manualMarksWrapper td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(5)::before { content: "CO1: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(6)::before { content: "CO2: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(7)::before { content: "CO3: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(8)::before { content: "CO4: "; font-weight: bold; color: #2563eb; }
      
      #markEntryTbody td div.relative,
      #manualMarksWrapper td div.relative {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
      }
      #markEntryTbody td input,
      #manualMarksWrapper td input {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
        text-align: center !important;
      }

      #mobileSeminarNotificationsContainer h5,
      #seminarNotificationsContainer h5 {
        font-size: 15px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; ANSWER KEY & RUBRIC&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Time:</strong> 1.5 Hours</span>
      <span><strong>Date:</strong> ${examDate}</span>
      <span><strong>Max Marks:</strong> ${totalMarks}</span>
    </div>
  </div>
  ${bodyHtml}
</body>
</html>`;

      const pw = window.open('', '_blank', 'width=900,height=700');
      pw.document.write(fullHtml);
      pw.document.close();
      pw.focus();
      setTimeout(() => { pw.print(); }, 400);
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      if (statusEl) {
        statusEl.classList.remove('hidden');
        statusEl.className = "text-sm font-bold mt-2 text-blue-400";
        statusEl.innerText = "Uploading photo...";
      }

      const formData = new FormData();
      formData.append('photo', file);

      fetch('/api/staff/profile/upload-photo', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(async res => {
        const data = await res.json().catch(() => ({ status: 'ERROR', message: 'Invalid server response.' }));
        if (res.ok && data.status === 'SUCCESS') {
          if (statusEl) {
            statusEl.className = "text-sm font-bold mt-2 text-green-400";
            statusEl.innerText = "Photo updated successfully!";
          }

          const photoUrl = data.photo_url + '?t=' + new Date().getTime();
          document.querySelectorAll('#staffProfileImg, #sidebarStaffImg, #sidebarAvatarContainer img, aside img.rounded-full').forEach(img => {
            img.src = photoUrl;
          });

          if (statusEl) {
            setTimeout(() => statusEl.classList.add('hidden'), 3000);
          }
        } else {
          if (statusEl) {
            statusEl.className = "text-sm font-bold mt-2 text-rose-400";
            statusEl.innerText = data.message || "Upload failed.";
          }
        }
      })
      .catch(err => {
        console.error('Upload error:', err);
        if (statusEl) {
          statusEl.className = "text-sm font-bold mt-2 text-rose-400";
          statusEl.innerText = "Error uploading photo. Please check file format and size.";
        }
      });
    }

    function loadSecurityLogs() {
      const tbody = document.getElementById('securityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">Querying security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">No profile action logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-100 text-sm hover:bg-slate-50/70";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Error querying logs.</td></tr>`;
        });
    }
  
      function deleteOnlineTest(testId, subjectId) {
        if (!confirm("Are you sure you want to delete this online test? This will permanently remove all student attempts and records associated with it.")) return;
        fetch(`/api/classroom/online-tests/${testId}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            loadActiveOnlineTests(subjectId);
          } else {
            alert(data.message || "Failed to delete test.");
          }
        });
      }

      function printOnlineTest(testId) {
        fetch(`/api/classroom/online-tests/${testId}/key`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const deptMap = {
              'EL': 'ELECTRONICS ENGINEERING',
              'CS': 'COMPUTER SCIENCE AND ENGINEERING',
              'CE': 'CIVIL ENGINEERING',
              'ME': 'MECHANICAL ENGINEERING',
              'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
              'IT': 'INFORMATION TECHNOLOGY',
              'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
            };
            const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
            const subjectName = currentSubjectName;
            const subjectCode = currentSubjectCode;
            const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
            const testName = data.test_name || 'Online Test';
            const totalQ = data.total || 0;

            let html = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Online MCQ Test - ${testName}</title>
  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
    @media (max-width: 640px) {
      .mobile-sem-btn {
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding-top: 0.625rem !important;
        padding-bottom: 0.625rem !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
      }
      .mobile-sem-btn span:first-child {
        font-size: 12px !important;
        margin-bottom: 0.125rem !important;
      }
      .mobile-sem-btn span:last-child {
        font-size: 14px !important;
      }
      #mobileSeminarNotificationsContainer h4,
      #seminarNotificationsContainer h4 {
        font-size: 16px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }

    /* CampusLynk Modern Virtual Classroom Panel Styles */
    #panelClassroom {
      background-color: #FAFAFB !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1.5rem !important;
      padding: 1.5rem !important;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05) !important;
    }

    #panelClassroom, 
    #panelClassroom button,
    #panelClassroom select,
    #panelClassroom input,
    #panelClassroom table,
    #panelClassroom th,
    #panelClassroom td,
    #panelClassroom div,
    #panelClassroom p,
    #panelClassroom h3,
    #panelClassroom h4,
    #panelClassroom h5,
    #panelClassroom span {
      font-size: 14px;
    }
    
    #panelClassroom h3#vcTitle,
    #panelClassroom h3#vcTitle span {
      font-size: 18px !important;
      font-weight: 700 !important;
    }
    
    #panelClassroom #vcSubtitle,
    #panelClassroom #vcSubtitle span {
      font-size: 14px !important;
    }
    
    #panelClassroom #vcViewStudentsBtn,
    #panelClassroom #vcViewStudentsBtn span {
      font-size: 14px !important;
      font-weight: 600 !important;
    }

    #panelClassroom .classroom-tab-btn {
      font-size: 14px !important;
      font-weight: 600 !important;
      padding: 0.5rem 0.875rem !important;
      border-radius: 0.75rem !important;
      transition: all 0.15s ease !important;
    }

    #panelClassroom h4, 
    #panelClassroom h5 {
      font-size: 16px !important;
      font-weight: 700 !important;
    }

    /* Manual mark entry table title, names, and internal grid data font sizes */
    #manualMarksWrapper table th,
    #manualMarksWrapper table td,
    #manualMarksWrapper input,
    #manualMarksWrapper span {
      font-size: 14px !important;
    }
    
    #manualMarksWrapper table td {
      padding: 12px 10px !important;
    }

    /* Flatpickr date picker light theme styling */
    .flatpickr-calendar {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1rem !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-month,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .flatpickr-weekday,
    .flatpickr-calendar .flatpickr-days .flatpickr-day {
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.prevMonthDay:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.nextMonthDay:hover {
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day.selected {
      background: #2563eb !important;
      color: #ffffff !important;
    }
    .flatpickr-calendar .flatpickr-current-month span.cur-month,
    .flatpickr-calendar .numInputWrapper span,
    .flatpickr-calendar input.numInput {
      color: #0f172a !important;
      font-weight: 600 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
      color: #2563eb !important;
      fill: #2563eb !important;
    }

    /* Mobile styles for Virtual Classroom assessment mark entry */
    @media (max-width: 767px) {
      .co-mark, .summ-mark,
      #manualMarksWrapper input,
      #markEntryTbody input,
      #summativeMarkEntryTbody input {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        color: #0f172a !important;
      }

      /* Transform assignment mark entry tables into list cards on mobile view */
      #markEntryTbody,
      #markEntryTbody tr,
      #markEntryTbody td,
      #manualMarksWrapper tbody,
      #manualMarksWrapper tr,
      #manualMarksWrapper td {
        display: block !important;
      }
      
      #panelClassroom table thead {
        display: none !important;
      }
      
      #markEntryTbody tr,
      #manualMarksWrapper table tr {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.65rem !important;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05) !important;
      }
      
      #markEntryTbody td,
      #manualMarksWrapper td {
        padding: 0.35rem 0 !important;
        border: none !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        text-align: left !important;
        font-size: 14px !important;
      }
      
      #markEntryTbody td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(5)::before { content: "CO1 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(6)::before { content: "CO2 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(7)::before { content: "CO3 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(8)::before { content: "CO4 (10): "; font-weight: bold; color: #2563eb; }
      
      #manualMarksWrapper td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(5)::before { content: "CO1: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(6)::before { content: "CO2: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(7)::before { content: "CO3: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(8)::before { content: "CO4: "; font-weight: bold; color: #2563eb; }
      
      #markEntryTbody td div.relative,
      #manualMarksWrapper td div.relative {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
      }
      #markEntryTbody td input,
      #manualMarksWrapper td input {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
        text-align: center !important;
      }

      #mobileSeminarNotificationsContainer h5,
      #seminarNotificationsContainer h5 {
        font-size: 15px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${testName} &ndash; Answer Key&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Total Questions:</strong> ${totalQ}</span>
    </div>
  </div>`;

            data.details.forEach((q, i) => {
              html += `<div class="q-block">
                <div class="q-text">${i+1}. ${q.q} &nbsp; <em>[${q.co}]</em></div>
                <ul class="options">`;
              q.options.forEach(opt => {
                let isCorrect = (opt === q.correct_ans);
                if (isCorrect) {
                  html += `<li><strong>${opt} &nbsp; &#10004;</strong></li>`;
                } else {
                  html += `<li>${opt}</li>`;
                }
              });
              html += `</ul></div>`;
            });

            html += `</body></html>`;
            let pw = window.open('', '_blank', 'width=800,height=600');
            pw.document.write(html);
            pw.document.close();
            pw.focus();
            setTimeout(() => { pw.print(); }, 500);
          } else {
            alert(data.message);
          }
        });
      }

      function showVcStudentsList() {
        const badge = document.getElementById('vcModalBatchBadge');
        if (badge) {
          badge.innerText = `${window.currentVirtualBatchId || ''} (S-${window.currentVirtualSemester || 1})`;
        }
        let html = '';
        if (window.currentVirtualStudents && window.currentVirtualStudents.length > 0) {
          html = `
            <table class="w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider">
                  <th class="p-3.5 font-bold w-12 text-center">No.</th>
                  <th class="p-3.5 font-bold w-20 text-center">Roll No</th>
                  <th class="p-3.5 font-bold w-32 text-center">SBTE Reg No</th>
                  <th class="p-3.5 font-bold w-32 text-center">Admission No</th>
                  <th class="p-3.5 font-bold">Student Name</th>
                  <th class="p-3.5 font-bold w-48">Remarks</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
          `;
          window.currentVirtualStudents.forEach((s, idx) => {
            html += `
              <tr class="hover:bg-slate-50/80 transition-colors text-sm text-slate-800">
                <td class="p-3 text-center text-slate-700 font-bold">${idx + 1}</td>
                <td class="p-3 text-center font-mono text-slate-900 font-bold">${s.roll_no || '-'}</td>
                <td class="p-3 text-center font-mono text-slate-600 text-xs font-semibold">${s.sbte_reg_no || '-'}</td>
                <td class="p-3 text-center font-mono text-slate-600 text-xs font-semibold">${s.reg_no}</td>
                <td class="p-3 font-bold text-slate-900 max-w-[220px] whitespace-normal break-words">${s.name}</td>
                <td class="p-2.5"><input type="text" placeholder="Add remark..." class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 shadow-2xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none"></td>
              </tr>
            `;
          });
          html += `</tbody></table>`;
        } else {
          html = '<p class="text-sm font-bold text-slate-500 text-center py-6">No students enrolled in this classroom.</p>';
        }
        document.getElementById('vcStudentsListContent').innerHTML = html;
        document.getElementById('vcStudentsModal').classList.remove('hidden');
      }

      function closeVcStudentsList() {
        document.getElementById('vcStudentsModal').classList.add('hidden');
      }

      function printVcStudentsList() {
        if (!window.currentVirtualStudents || window.currentVirtualStudents.length === 0) {
            alert("No students to print.");
            return;
        }

        const deptMap = {
          'EL': 'ELECTRONICS ENGINEERING',
          'CS': 'COMPUTER SCIENCE AND ENGINEERING',
          'CE': 'CIVIL ENGINEERING',
          'ME': 'MECHANICAL ENGINEERING',
          'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
          'IT': 'INFORMATION TECHNOLOGY',
          'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
        };
        const branchCode = "{{ session('userBranch', '') }}";
        const deptName = deptMap[branchCode.toUpperCase()] || branchCode;
        const batchName = document.getElementById('vcSubtitle') ? document.getElementById('vcSubtitle').innerText.replace('Batch:', '').trim() : '';
        const revision = window.currentSyllabusRevision || '2021';

        let printHtml = `
          <html>
            <head>
              <title>Classroom Students List</title>
              <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
    @media (max-width: 640px) {
      .mobile-sem-btn {
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding-top: 0.625rem !important;
        padding-bottom: 0.625rem !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
      }
      .mobile-sem-btn span:first-child {
        font-size: 12px !important;
        margin-bottom: 0.125rem !important;
      }
      .mobile-sem-btn span:last-child {
        font-size: 14px !important;
      }
      #mobileSeminarNotificationsContainer h4,
      #seminarNotificationsContainer h4 {
        font-size: 16px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }

    /* CampusLynk Modern Virtual Classroom Panel Styles */
    #panelClassroom {
      background-color: #FAFAFB !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1.5rem !important;
      padding: 1.5rem !important;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05) !important;
    }

    #panelClassroom, 
    #panelClassroom button,
    #panelClassroom select,
    #panelClassroom input,
    #panelClassroom table,
    #panelClassroom th,
    #panelClassroom td,
    #panelClassroom div,
    #panelClassroom p,
    #panelClassroom h3,
    #panelClassroom h4,
    #panelClassroom h5,
    #panelClassroom span {
      font-size: 14px;
    }
    
    #panelClassroom h3#vcTitle,
    #panelClassroom h3#vcTitle span {
      font-size: 18px !important;
      font-weight: 700 !important;
    }
    
    #panelClassroom #vcSubtitle,
    #panelClassroom #vcSubtitle span {
      font-size: 14px !important;
    }
    
    #panelClassroom #vcViewStudentsBtn,
    #panelClassroom #vcViewStudentsBtn span {
      font-size: 14px !important;
      font-weight: 600 !important;
    }

    #panelClassroom .classroom-tab-btn {
      font-size: 14px !important;
      font-weight: 600 !important;
      padding: 0.5rem 0.875rem !important;
      border-radius: 0.75rem !important;
      transition: all 0.15s ease !important;
    }

    #panelClassroom h4, 
    #panelClassroom h5 {
      font-size: 16px !important;
      font-weight: 700 !important;
    }

    /* Manual mark entry table title, names, and internal grid data font sizes */
    #manualMarksWrapper table th,
    #manualMarksWrapper table td,
    #manualMarksWrapper input,
    #manualMarksWrapper span {
      font-size: 14px !important;
    }
    
    #manualMarksWrapper table td {
      padding: 12px 10px !important;
    }

    /* Flatpickr date picker light theme styling */
    .flatpickr-calendar {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1rem !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-month,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .flatpickr-weekday,
    .flatpickr-calendar .flatpickr-days .flatpickr-day {
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.prevMonthDay:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.nextMonthDay:hover {
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day.selected {
      background: #2563eb !important;
      color: #ffffff !important;
    }
    .flatpickr-calendar .flatpickr-current-month span.cur-month,
    .flatpickr-calendar .numInputWrapper span,
    .flatpickr-calendar input.numInput {
      color: #0f172a !important;
      font-weight: 600 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
      color: #2563eb !important;
      fill: #2563eb !important;
    }

    /* Mobile styles for Virtual Classroom assessment mark entry */
    @media (max-width: 767px) {
      .co-mark, .summ-mark,
      #manualMarksWrapper input,
      #markEntryTbody input,
      #summativeMarkEntryTbody input {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        color: #0f172a !important;
      }

      /* Transform assignment mark entry tables into list cards on mobile view */
      #markEntryTbody,
      #markEntryTbody tr,
      #markEntryTbody td,
      #manualMarksWrapper tbody,
      #manualMarksWrapper tr,
      #manualMarksWrapper td {
        display: block !important;
      }
      
      #panelClassroom table thead {
        display: none !important;
      }
      
      #markEntryTbody tr,
      #manualMarksWrapper table tr {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.65rem !important;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05) !important;
      }
      
      #markEntryTbody td,
      #manualMarksWrapper td {
        padding: 0.35rem 0 !important;
        border: none !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        text-align: left !important;
        font-size: 14px !important;
      }
      
      #markEntryTbody td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(5)::before { content: "CO1 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(6)::before { content: "CO2 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(7)::before { content: "CO3 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(8)::before { content: "CO4 (10): "; font-weight: bold; color: #2563eb; }
      
      #manualMarksWrapper td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(5)::before { content: "CO1: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(6)::before { content: "CO2: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(7)::before { content: "CO3: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(8)::before { content: "CO4: "; font-weight: bold; color: #2563eb; }
      
      #markEntryTbody td div.relative,
      #manualMarksWrapper td div.relative {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
      }
      #markEntryTbody td input,
      #manualMarksWrapper td input {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
        text-align: center !important;
      }

      #mobileSeminarNotificationsContainer h5,
      #seminarNotificationsContainer h5 {
        font-size: 15px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }
  </style>
            </head>
              <body>
                <div class="header-container">
                  <h1 class="college-name">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
                  <h2 class="dept-name">DEPARTMENT OF ${deptName}</h2>
                  <div class="doc-title">Enrolled Students Register</div>
                </div>

                <div class="meta-grid">
                  <div class="meta-item"><span class="meta-label">Batch:</span> ${batchName}</div>
                  <div class="meta-item" style="text-align: right;"><span class="meta-label">Syllabus Revision:</span> ${revision}</div>
                  <div class="meta-item"><span class="meta-label">Subject Code:</span> ${currentSubjectCode}</div>
                  <div class="meta-item" style="text-align: right;"><span class="meta-label">Subject Name:</span> ${currentSubjectName}</div>
                </div>

                <table>
                  <thead>
                    <tr>
                      <th class="text-center" style="width: 40px;">No.</th>
                      <th class="text-center" style="width: 80px;">Roll No</th>
                      <th class="text-center" style="width: 120px;">SBTE Reg No</th>
                      <th class="text-center" style="width: 120px;">Admission No</th>
                      <th>Student Name</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
          `;

          window.currentVirtualStudents.forEach((s, idx) => {
            printHtml += `
              <tr>
                <td class="text-center font-mono">${idx + 1}</td>
                <td class="text-center font-mono">${s.roll_no || '-'}</td>
                <td class="text-center font-mono">${s.sbte_reg_no || '-'}</td>
                <td class="text-center font-mono">${s.reg_no}</td>
                <td style="font-weight: 600;">${s.name}</td>
                <td></td>
              </tr>
            `;
          });

          printHtml += `
                  </tbody>
                </table>
                <script>
                  setTimeout(() => { window.print(); window.close(); }, 500);
                <\/script>
              </body>
            </html>
          `;

          let printWin = window.open('', '_blank');
          printWin.document.write(printHtml);
          printWin.document.close();
      }

    function fetchClassReports() {
      if (!currentSubjectId) return;
      const workspace = document.getElementById('classroomReportWorkspace');
      workspace.innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Loading reports...</p>
        </div>
      `;

      fetch(`/api/staff/attendance/subjects/${currentSubjectId}/reports`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            classReportsData = data;
            renderActiveReport();
          } else {
            workspace.innerHTML = `<div class="text-sm font-bold text-red-400 py-10 text-center">${data.message || 'Failed to load reports.'}</div>`;
          }
        })
        .catch(err => {
          console.error(err);
          workspace.innerHTML = '<div class="text-sm font-bold text-red-400 py-10 text-center">Error loading reports.</div>';
        });
    }

    function loadClassReport(type) {
      activeReportType = type;
      
      const buttons = [
        { id: 'attendance_log', btn: 'btnReportLog' },
        { id: 'subject_log', btn: 'btnReportSubject' },
        { id: 'summary_matrix', btn: 'btnReportMatrix' }
      ];

      buttons.forEach(b => {
        const el = document.getElementById(b.btn);
        if (!el) return;
        if (b.id === type) {
          el.className = "px-4 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-xs cursor-pointer transition-premium";
        } else {
          el.className = "px-4 py-2 bg-white text-slate-700 hover:bg-slate-50 border border-slate-200 rounded-xl font-bold text-sm shadow-2xs cursor-pointer transition-premium";
        }
      });

      renderActiveReport();
    }

    function renderActiveReport() {
      if (!classReportsData) return;
      const workspace = document.getElementById('classroomReportWorkspace');
      workspace.innerHTML = '';

      if (activeReportType === 'attendance_log') {
        renderAttendanceLogReport(workspace);
      } else if (activeReportType === 'subject_log') {
        renderSubjectLogReport(workspace);
      } else if (activeReportType === 'summary_matrix') {
        renderSummaryMatrixReport(workspace);
      }
    }

    function renderAttendanceLogReport(container) {
      const logs = classReportsData.logs || [];
      if (logs.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No attendance logs recorded yet for this subject.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-200/80 rounded-xl bg-white shadow-xs">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-50 text-slate-700 border-b border-slate-200 uppercase tracking-wider text-xs font-bold">
                <th class="p-4">Date</th>
                <th class="p-4 text-center">Period</th>
                <th class="p-4">Topics Covered</th>
                <th class="p-4 text-center">Present</th>
                <th class="p-4 text-center">Absent</th>
              </tr>
            </thead>
            <tbody>
      `;

      logs.forEach(log => {
        html += `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-colors text-slate-800">
            <td class="p-4 font-mono font-bold text-slate-800">${log.date}</td>
            <td class="p-4 text-center font-bold text-slate-400">P${log.period}</td>
            <td class="p-4 text-slate-800">${log.topics_covered || '-'}</td>
            <td class="p-4 text-center font-bold text-emerald-400">${log.present_count}</td>
            <td class="p-4 text-center font-bold text-rose-400">${log.absent_count}</td>
          </tr>
        `;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function renderSubjectLogReport(container) {
      const logs = classReportsData.logs || [];
      if (logs.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No class logs recorded yet for this subject.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-200/80 rounded-xl bg-white shadow-xs">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-50 text-slate-700 border-b border-slate-200 uppercase tracking-wider text-xs font-bold">
                <th class="p-4">Completed Date</th>
                <th class="p-4 text-center">Period</th>
                <th class="p-4">Lesson Plan Reference</th>
                <th class="p-4">Topics Covered</th>
              </tr>
            </thead>
            <tbody>
      `;

      logs.forEach(log => {
        html += `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-colors text-slate-800">
            <td class="p-4 font-mono font-bold text-slate-800">${log.date}</td>
            <td class="p-4 text-center font-bold text-slate-400">P${log.period}</td>
            <td class="p-4 text-slate-400 font-bold">${log.lesson_plan_id ? 'LP ID: ' + log.lesson_plan_id : 'Manual Entry'}</td>
            <td class="p-4 text-slate-800">${log.topics_covered || '-'}</td>
          </tr>
        `;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function renderSummaryMatrixReport(container) {
      const dates = classReportsData.dates || [];
      const matrix = classReportsData.matrix || [];

      if (dates.length === 0 || matrix.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No attendance summary available.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-200/80 rounded-xl bg-white shadow-xs max-h-[500px]">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-50 text-slate-700 border-b border-slate-200 uppercase tracking-wider text-xs font-bold sticky top-0 z-10">
                <th class="p-4 bg-slate-50 text-slate-700 w-16 text-center sticky left-0 z-20">Roll</th>
                <th class="p-4 bg-slate-50 text-slate-700 w-44 sticky left-16 z-20">Name</th>
      `;

      dates.forEach(d => {
        const shortDate = d.date.substring(5);
        html += `<th class="p-4 text-center min-w-[70px]">${shortDate}<br><span class="text-[10px] text-slate-500">P${d.period}</span></th>`;
      });

      html += `
              </tr>
            </thead>
            <tbody>
      `;

      matrix.forEach(row => {
        html += `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-colors text-slate-800">
            <td class="p-4 text-center font-bold text-slate-600 bg-white sticky left-0 z-10">${row.roll_no || '-'}</td>
            <td class="p-4 font-bold text-slate-900 bg-white sticky left-16 z-10 truncate max-w-[176px]">${row.name}</td>
        `;

        dates.forEach(d => {
          const key = d.date + ' | P' + d.period;
          const status = row.attendance[key] || '-';
          let cellClass = 'text-slate-500';
          if (status === 'P') cellClass = 'text-emerald-400 font-bold';
          if (status === 'A') cellClass = 'text-rose-400 font-bold';

          html += `<td class="p-4 text-center ${cellClass}">${status}</td>`;
        });

        html += `</tr>`;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function fetchQuestionBank(subjectId) {
      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-10">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Loading Question Bank...</p>
        </div>
      `;

      fetch(`/api/classroom/${subjectId}/question-bank`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          renderQuestionBank(data.questions);
        } else {
          container.innerHTML = `<div class="text-sm text-rose-400 py-6 text-center font-bold">Failed to load questions.</div>`;
        }
      })
      .catch(err => {
        container.innerHTML = `<div class="text-sm text-rose-400 py-6 text-center font-bold">Error loading questions.</div>`;
      });
    }

    function renderQuestionBank(questions) {
      const container = document.getElementById('qbankCoGroups');
      if (!questions || questions.length === 0) {
        container.innerHTML = `
          <div class="text-center py-12 text-slate-400 space-y-4 max-w-md mx-auto">
            <div class="bg-white p-4 rounded-full border border-slate-200 shadow-2xs inline-block">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
            </div>
            <p class="text-sm font-bold text-slate-700">No questions in this subject's pool.</p>
            <p class="text-sm text-slate-500">You can download the template CSV, fill it with questions, and upload it. Alternatively, seed the pool instantly with high-quality questions using AI.</p>
            <div class="pt-2">
              <button onclick="seedQuestionBankWithAi(currentSubjectId)" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold transition-premium cursor-pointer shadow-md flex items-center gap-1.5 mx-auto">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg> Seed Pool via AI
              </button>
            </div>
          </div>
        `;
        return;
      }

      const markGroups = {
        '1 Mark Questions': [],
        '3 Mark Questions': [],
        '7 Mark Questions': [],
        'Other Marks': []
      };

      questions.forEach(q => {
        const marks = parseInt(q.marks || 0);
        if (marks === 1) {
          markGroups['1 Mark Questions'].push(q);
        } else if (marks === 3) {
          markGroups['3 Mark Questions'].push(q);
        } else if (marks === 7) {
          markGroups['7 Mark Questions'].push(q);
        } else {
          markGroups['Other Marks'].push(q);
        }
      });

      let html = '';
      Object.keys(markGroups).forEach(groupName => {
        const qList = markGroups[groupName];
        if (qList.length === 0) return;

        html += `
          <div class="border border-slate-200/80 rounded-xl overflow-hidden bg-white shadow-xs mb-6">
            <div class="bg-slate-50/70 p-4 flex justify-between items-center border-b border-slate-200">
              <div class="flex items-center gap-2">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span class="text-sm font-bold text-slate-900">${groupName}</span>
              </div>
              <span class="text-xs text-slate-600 font-bold bg-white border border-slate-200 px-2.5 py-1 rounded-lg shadow-2xs">${qList.length} Questions</span>
            </div>
            <div class="p-0 overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="border-b border-slate-200 text-slate-700 text-xs font-bold bg-slate-50 uppercase tracking-wider">
                    <th class="py-2.5 px-4 font-bold w-12 text-center">#</th>
                    <th class="py-2.5 px-4 font-bold">Question Text</th>
                    <th class="py-2.5 px-4 font-bold w-20 text-center">CO Tag</th>
                    <th class="py-2.5 px-4 font-bold w-28 text-center">Cognitive</th>
                    <th class="py-2.5 px-4 font-bold w-28 text-center">Type</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-800">
        `;

        qList.forEach((q, index) => {
          const typeStr = q.type === 'MCQ' ? `MCQ (Ans: ${q.correct_answer || 'N/A'})` : 'Descriptive';
          html += `
            <tr class="hover:bg-slate-50/80 transition-colors text-sm">
              <td class="py-3 px-4 text-center text-slate-500 font-mono">${index + 1}</td>
              <td class="py-3 px-4 font-semibold text-slate-900">
                <div>${q.question_text}</div>
                ${q.type === 'MCQ' && q.options ? renderCompactOptions(q.options, q.correct_answer) : ''}
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 font-mono text-[11px] font-bold">${q.co_tag || 'CO1'}</span>
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 text-[11px] font-bold">${q.cognitive_level || 'Understand'}</span>
              </td>
              <td class="py-3 px-4 text-center">
                <span class="px-2.5 py-1 rounded-lg ${q.type === 'MCQ' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-slate-100 text-slate-700 border border-slate-200'} text-xs font-semibold shadow-2xs">${typeStr}</span>
              </td>
            </tr>
          `;
        });

        html += `
                </tbody>
              </table>
            </div>
          </div>
        `;
      });

      if (!html) {
        html = `<div class="text-sm text-slate-500 py-8 text-center">No grouped questions found.</div>`;
      }

      container.innerHTML = html;
    }

    function renderCompactOptions(optionsStr, correctAns) {
      const options = typeof optionsStr === 'string' ? JSON_decode_safe(optionsStr) : optionsStr;
      if (!options || options.length === 0) return '';
      const labels = ['A', 'B', 'C', 'D'];
      let optHtml = '<div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-xs text-slate-500 font-medium pl-2 border-l border-slate-200">';
      options.forEach((opt, idx) => {
        if (!opt) return;
        const isCorrect = correctAns === labels[idx] || correctAns === opt;
        const colorClass = isCorrect ? 'text-emerald-400 font-bold' : 'text-slate-500';
        optHtml += `<span class="${colorClass}"><b class="opacity-60">${labels[idx]}:</b> ${opt}</span>`;
      });
      optHtml += '</div>';
      return optHtml;
    }

    function JSON_decode_safe(str) {
      try {
        return JSON.parse(str);
      } catch (e) {
        return [];
      }
    }

    function downloadExcelTemplate() {
      const headers = [
        ['Type', 'Marks', 'Cognitive Level', 'CO Tag', 'Question Text', 'Option A', 'Option B', 'Option C', 'Option D', 'Correct Answer'],
        ['MCQ', '1', 'Remember', 'CO1', 'What is the correct definition of an embedded system?', 'A general purpose computer system', 'A specialized computer system designed for specific control functions', 'A computer system with no hardware', 'A system only used in gaming consoles', 'B'],
        ['Descriptive', '5', 'Understand', 'CO2', 'Explain the differences between RISC and CISC architectures in embedded processors.', '', '', '', '', '']
      ];
      const ws = XLSX.utils.aoa_to_sheet(headers);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, "Questions Template");
      XLSX.writeFile(wb, "Question_Bank_Template.xlsx");
    }

    function handleQBankUpload(input) {
      if (!input.files || input.files.length === 0) return;
      const file = input.files[0];

      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-10">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Parsing Excel file...</p>
        </div>
      `;

      const reader = new FileReader();
      reader.onload = function(e) {
        try {
          const data = new Uint8Array(e.target.result);
          const workbook = XLSX.read(data, { type: 'array' });
          const firstSheetName = workbook.SheetNames[0];
          const worksheet = workbook.Sheets[firstSheetName];
          const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
          
          if (rows.length < 2) {
            alert('Excel file is empty or missing data rows.');
            fetchQuestionBank(currentSubjectId);
            input.value = '';
            return;
          }

          container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10">
              <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
              <p class="text-sm font-bold text-slate-400">Uploading and saving questions to pool...</p>
            </div>
          `;

          fetch(`/api/classroom/${currentSubjectId}/question-bank/upload-json`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ rows: rows })
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'SUCCESS') {
              alert(data.message || 'Questions imported successfully!');
            } else {
              alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
            fetchQuestionBank(currentSubjectId);
            input.value = '';
          })
          .catch(err => {
            alert('Upload failed: server error');
            fetchQuestionBank(currentSubjectId);
            input.value = '';
          });

        } catch (err) {
          alert('Error reading Excel file: ' + err.message);
          fetchQuestionBank(currentSubjectId);
          input.value = '';
        }
      };
      
      reader.readAsArrayBuffer(file);
    }

    function seedQuestionBankWithAi(subjectId) {
      const container = document.getElementById('qbankCoGroups');
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12">
          <div class="w-8 h-8 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">AI is generating structured exam questions for all COs...</p>
        </div>
      `;

      fetch(`/api/classroom/${subjectId}/question-bank/seed-ai`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
        } else {
          alert('Failed to seed: ' + data.message);
        }
        fetchQuestionBank(subjectId);
      })
      .catch(err => {
        alert('Server error seeding question bank.');
        fetchQuestionBank(subjectId);
      });
    }

    // Mid-Semester Survey Functionality (SAR Criterion 2)
    function fetchSurveyResults(subjectId) {
      const workspace = document.getElementById('surveyWorkspace');
      const headerActions = document.getElementById('surveyHeaderActions');
      headerActions.innerHTML = '';

      fetch(`/api/classroom/${subjectId}/survey/results`)
        .then(res => res.json())
        .then(res => {
          if (res.status === 'INACTIVE') {
            workspace.innerHTML = `
              <div class="bg-white border border-slate-200/80 rounded-2xl p-6 text-center max-w-xl mx-auto space-y-4">
                <div class="h-12 w-12 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mx-auto mb-2 animate-pulse">
                  <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h4 class="text-base font-bold text-slate-900">Initiate Mid-Semester Feedback Survey</h4>
                <p class="text-sm text-slate-600 leading-relaxed">
                  Conducted around the 7th–9th week of the semester, this evaluates the teaching-learning process in real-time. It gathers student feedback on 5 criteria: Pace, Clarity, Interaction, Practicality, and Evaluation.
                </p>
                <button onclick="initiateMidSemSurvey(${subjectId})" class="px-5 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold border border-blue-500/30 transition-premium shadow-lg shadow-blue-500/10 cursor-pointer">
                  Start Mid-Semester Survey
                </button>
              </div>
            `;
          } else if (res.status === 'SUCCESS') {
            const survey = res.data.survey;
            const total = res.data.total_students;
            const responded = res.data.responded_count;

            if (survey.status === 'Active') {
              workspace.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <!-- Live stats card -->
                  <div class="bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between space-y-4">
                    <div>
                      <span class="text-teal-700 font-bold font-bold uppercase tracking-widest text-[10px] block mb-1">Live Status</span>
                      <h4 class="text-base font-bold text-slate-900">Survey Active</h4>
                      <p class="text-xs text-slate-600 leading-relaxed mt-1">Students can now see and submit feedback from their dashboard task list.</p>
                    </div>
                    <div class="border-t border-slate-200/80 pt-4">
                      <div class="flex justify-between text-sm font-bold mb-1">
                        <span class="text-slate-600 font-semibold">Participation:</span>
                        <span class="text-slate-900 font-bold">${responded} / ${total}</span>
                      </div>
                      <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                        <div class="bg-teal-500 h-2 rounded-full" style="width: ${total > 0 ? (responded / total) * 100 : 0}%"></div>
                      </div>
                    </div>
                  </div>

                  <!-- Quick instructions card -->
                  <div class="bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between col-span-2">
                    <div>
                      <h4 class="text-sm font-bold text-slate-700">Evaluating Criterion 2 (SAR)</h4>
                      <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        To finalize results, draw graphs, and register action plan notes, you must close the active survey. Encourage students to participate before closing.
                      </p>
                    </div>
                    <div class="pt-6 border-t border-slate-200/80 flex justify-end">
                      <button onclick="closeMidSemSurvey(${subjectId})" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 shadow-2xs rounded-xl text-sm font-bold transition-premium cursor-pointer">
                        Close & Finalize Survey
                      </button>
                    </div>
                  </div>
                </div>
              `;
            } else {
              // Completed survey: show results + chart + notes
              const averages = res.data.averages;
              
              workspace.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                  <!-- Stats overview -->
                  <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">Participation Details</h4>
                      <div class="grid grid-cols-2 gap-4 text-xs font-semibold">
                        <div>
                          <span class="text-slate-500 block">Class Strength</span>
                          <span class="text-slate-900 font-bold text-sm">${total}</span>
                        </div>
                        <div>
                          <span class="text-slate-500 block">Responded</span>
                          <span class="text-slate-900 font-bold text-sm">${responded}</span>
                        </div>
                      </div>
                      <div class="pt-3 border-t border-slate-100">
                        <span class="text-slate-500 block text-xs">Response Rate</span>
                        <span class="text-emerald-700 font-bold font-black text-base">${total > 0 ? Math.round((responded / total) * 100) : 0}%</span>
                      </div>
                    </div>

                    <!-- Average Score Card -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">Average Scores Breakdown</h4>
                      <div class="space-y-3 text-xs font-semibold">
                        <div class="flex justify-between">
                          <span class="text-slate-600">Pace of delivery</span>
                          <span class="text-teal-700 font-bold">${averages.pace} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">Concept clarity</span>
                          <span class="text-teal-700 font-bold">${averages.clarity} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">Interactive lectures</span>
                          <span class="text-teal-700 font-bold">${averages.interaction} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">Lab practicality</span>
                          <span class="text-teal-700 font-bold">${averages.practicality} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">Prompt evaluation</span>
                          <span class="text-teal-700 font-bold">${averages.evaluation} / 3</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Charts and Action Plan Notes -->
                  <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6">
                      <h4 class="text-sm font-black text-slate-900 mb-4">Feedback Chart (Teaching-Learning Process)</h4>
                      <div class="h-64 relative">
                        <canvas id="surveyResultChart"></canvas>
                      </div>
                    </div>

                    <!-- Action Taken Form -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">SAR Criterion 2 Action Plan Notes</h4>
                      
                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Improvements Noted by Faculty</label>
                        <textarea id="improvementsNoted" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 text-xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium shadow-2xs transition-all" rows="2" placeholder="e.g. Remedial classes identified for weak students, changing lecture pace...">${survey.improvements_noted || ''}</textarea>
                      </div>

                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Corrective Action Taken (Faculty Member)</label>
                        <textarea id="correctiveAction" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 text-xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium shadow-2xs transition-all" rows="2" placeholder="e.g. Incorporated PPT slides, allocated extra laboratory session...">${survey.action_taken || ''}</textarea>
                      </div>

                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Action Taken Notes (Class Tutor Remarks)</label>
                        <textarea id="actionTakenByTutor" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 text-xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium shadow-2xs transition-all" rows="2" placeholder="Tutor remarks on student feedback and faculty actions...">${survey.action_taken_by_tutor || ''}</textarea>
                      </div>

                      <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Action Taken Remarks (Head of Department / HOD)</label>
                        <textarea id="actionTakenByHod" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-slate-900 text-xs focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 font-medium shadow-2xs transition-all" rows="2" placeholder="HOD remarks or corrective endorsement...">${survey.action_taken_by_hod || ''}</textarea>
                      </div>

                      <div class="flex justify-between items-center pt-2">
                        <button onclick="saveSurveyActionNotes(${subjectId})" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-bold border border-blue-500/30 transition-premium shadow cursor-pointer">
                          Save Notes
                        </button>
                        <a href="/classroom/${subjectId}/survey/report" target="_blank" class="px-4 py-2 bg-teal-50 hover:bg-teal-100 border border-teal-200 text-teal-700 shadow-2xs rounded-xl text-xs font-bold transition-premium no-underline flex items-center gap-1.5 cursor-pointer">
                          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Survey Report
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              `;

              // Initialize result chart
              setTimeout(() => renderSurveyChart(averages), 100);
            }
          } else {
            alert(res.message || "Failed to load survey results.");
          }
        })
        .catch(err => {
          console.error(err);
          workspace.innerHTML = `<div class="text-sm font-bold text-slate-500 py-10 text-center">Failed to fetch survey. Network error.</div>`;
        });
    }

    function initiateMidSemSurvey(subjectId) {
      if (!confirm("Are you sure you want to initiate the Mid-Semester Survey? This will notify all enrolled students.")) return;
      fetch(`/api/classroom/${subjectId}/survey/initiate`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }

    function closeMidSemSurvey(subjectId) {
      if (!confirm("Are you sure you want to close and finalize this survey? No further responses will be accepted.")) return;
      fetch(`/api/classroom/${subjectId}/survey/close`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }

    function saveSurveyActionNotes(subjectId) {
      const imp = document.getElementById('improvementsNoted').value;
      const act = document.getElementById('correctiveAction').value;
      const tut = document.getElementById('actionTakenByTutor').value;
      const hod = document.getElementById('actionTakenByHod').value;

      fetch(`/api/classroom/${subjectId}/survey/save-notes`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
          improvements_noted: imp, 
          action_taken: act,
          action_taken_by_tutor: tut,
          action_taken_by_hod: hod
        })
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
      });
    }

    function renderSurveyChart(averages) {
      const ctx = document.getElementById('surveyResultChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Pace', 'Clarity', 'Interaction', 'Practicality', 'Evaluation'],
          datasets: [{
            label: 'Avg Score (Out of 3)',
            data: [averages.pace, averages.clarity, averages.interaction, averages.practicality, averages.evaluation],
            backgroundColor: [
              'rgba(20, 184, 166, 0.2)',
              'rgba(14, 165, 233, 0.2)',
              'rgba(99, 102, 241, 0.2)',
              'rgba(168, 85, 247, 0.2)',
              'rgba(236, 72, 153, 0.2)'
            ],
            borderColor: [
              '#14b8a6',
              '#0ea5e9',
              '#6366f1',
              '#a855f7',
              '#ec4899'
            ],
            borderWidth: 2,
            borderRadius: 8
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            x: {
              min: 0,
              max: 3,
              ticks: { stepSize: 1, color: '#94a3b8' },
              grid: { color: 'rgba(51, 65, 85, 0.2)' }
            },
            y: {
              ticks: { color: '#94a3b8' },
              grid: { display: false }
            }
          }
        }
      });
    }

    // Course Exit Survey JS methods
    function fetchExitSurveyResults(subjectId) {
      const workspace = document.getElementById('exitSurveyWorkspace');
      const headerActions = document.getElementById('exitSurveyHeaderActions');
      headerActions.innerHTML = '';

      fetch(`/api/classroom/${subjectId}/course-exit/results`)
        .then(res => res.json())
        .then(res => {
          if (res.status === 'INACTIVE') {
            workspace.innerHTML = `
              <div class="bg-white border border-slate-200/80 rounded-2xl p-6 text-center max-w-xl mx-auto space-y-4">
                <div class="h-12 w-12 rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-700 font-bold flex items-center justify-center mx-auto mb-2 animate-pulse">
                  <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                </div>
                <h4 class="text-base font-bold text-slate-900">Initiate Course Exit Survey</h4>
                <p class="text-sm text-slate-600 leading-relaxed">
                  Conducted at the end of the semester, this exit survey maps directly to Course Outcomes (CO1 to CO4) using 10 specific attainment questions. Attainments are rated on a Low (1), Medium (2), and High (3) scale.
                </p>
                <button onclick="initiateExitSurvey(${subjectId})" class="px-5 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-sm font-bold border border-teal-500/30 transition-premium shadow-lg shadow-teal-500/10 cursor-pointer">
                  Start Course Exit Survey
                </button>
              </div>
            `;
          } else if (res.status === 'SUCCESS') {
            const survey = res.data.survey;
            const total = res.data.total_students;
            const responded = res.data.responded_count;

            if (survey.status === 'Active') {
              workspace.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <!-- Live stats card -->
                  <div class="bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between space-y-4">
                    <div>
                      <span class="text-teal-700 font-bold font-bold uppercase tracking-widest text-[10px] block mb-1">Live Status</span>
                      <h4 class="text-base font-bold text-slate-900">Survey Active</h4>
                      <p class="text-xs text-slate-600 leading-relaxed mt-1">Students can now submit exit responses mapping to COs via their dashboard.</p>
                    </div>
                    <div class="border-t border-slate-200/80 pt-4">
                      <div class="flex justify-between text-sm font-bold mb-1">
                        <span class="text-slate-600 font-semibold">Participation:</span>
                        <span class="text-slate-900 font-bold">${responded} / ${total}</span>
                      </div>
                      <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                        <div class="bg-teal-500 h-2 rounded-full" style="width: ${total > 0 ? (responded / total) * 100 : 0}%"></div>
                      </div>
                    </div>
                  </div>

                  <!-- Quick instructions card -->
                  <div class="bg-white border border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between col-span-2">
                    <div>
                      <h4 class="text-sm font-bold text-slate-700">Course Outcome Attainment mapping</h4>
                      <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        To calculate final attainment averages and view the printable Course Exit Report, you must close the active survey.
                      </p>
                    </div>
                    <div class="pt-6 border-t border-slate-200/80 flex justify-end">
                      <button onclick="closeExitSurvey(${subjectId})" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 shadow-2xs rounded-xl text-sm font-bold transition-premium cursor-pointer">
                        Close & Finalize Exit Survey
                      </button>
                    </div>
                  </div>
                </div>
              `;
            } else {
              // Completed survey: show results breakdown
              const averages = res.data.averages;
              const attainments = res.data.attainment_percentages;

              workspace.innerHTML = `
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                  <!-- Stats overview -->
                  <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">Participation Details</h4>
                      <div class="grid grid-cols-2 gap-4 text-xs font-semibold">
                        <div>
                          <span class="text-slate-500 block">Class Strength</span>
                          <span class="text-slate-900 font-bold text-sm">${total}</span>
                        </div>
                        <div>
                          <span class="text-slate-500 block">Responded</span>
                          <span class="text-slate-900 font-bold text-sm">${responded}</span>
                        </div>
                      </div>
                      <div class="pt-3 border-t border-slate-100">
                        <span class="text-slate-500 block text-xs">Response Rate</span>
                        <span class="text-teal-700 font-bold font-black text-base">${total > 0 ? Math.round((responded / total) * 100) : 0}%</span>
                      </div>
                    </div>

                    <!-- Average Score Card -->
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">CO Averages (Scale 1-3)</h4>
                      <div class="space-y-3 text-xs font-semibold">
                        <div class="flex justify-between">
                          <span class="text-slate-600">CO1 Average score</span>
                          <span class="text-teal-700 font-bold">${averages.CO1} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">CO2 Average score</span>
                          <span class="text-teal-700 font-bold">${averages.CO2} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">CO3 Average score</span>
                          <span class="text-teal-700 font-bold">${averages.CO3} / 3</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-slate-600">CO4 Average score</span>
                          <span class="text-teal-700 font-bold">${averages.CO4} / 3</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Attainments and Print Action -->
                  <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4">
                      <h4 class="text-sm font-black text-slate-900">Indirect CO Attainment Levels</h4>
                      <p class="text-xs text-slate-600 leading-relaxed">Attainment is computed as: <code>(CO Average / 3) * 100</code></p>
                      
                      <div class="space-y-4 pt-2">
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800">CO1 Attainment</span>
                            <span class="text-teal-700 font-bold">${attainments.CO1}%</span>
                          </div>
                          <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO1}%"></div>
                          </div>
                        </div>
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800">CO2 Attainment</span>
                            <span class="text-teal-700 font-bold">${attainments.CO2}%</span>
                          </div>
                          <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO2}%"></div>
                          </div>
                        </div>
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800">CO3 Attainment</span>
                            <span class="text-teal-700 font-bold">${attainments.CO3}%</span>
                          </div>
                          <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO3}%"></div>
                          </div>
                        </div>
                        <div>
                          <div class="flex justify-between text-xs font-bold mb-1">
                            <span class="text-slate-800">CO4 Attainment</span>
                            <span class="text-teal-700 font-bold">${attainments.CO4}%</span>
                          </div>
                          <div class="w-full bg-slate-100 rounded-full h-2 border border-slate-200">
                            <div class="bg-teal-500 h-2 rounded-full" style="width: ${attainments.CO4}%"></div>
                          </div>
                        </div>
                      </div>

                      <div class="flex justify-end items-center pt-6 border-t border-slate-200/80">
                        <a href="/classroom/${subjectId}/course-exit/report" target="_blank" class="px-5 py-3 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-sm font-bold transition-premium no-underline flex items-center gap-1.5 cursor-pointer shadow-md shadow-teal-600/10">
                          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg> Print Course Exit Report
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              `;
            }
          } else {
            alert(res.message || "Failed to load exit survey results.");
          }
        })
        .catch(err => {
          console.error(err);
          workspace.innerHTML = `<div class="text-sm font-bold text-slate-500 py-10 text-center">Failed to fetch exit survey. Network error.</div>`;
        });
    }

    function initiateExitSurvey(subjectId) {
      if (!confirm("Are you sure you want to initiate the Course Exit Survey? This will notify all enrolled students.")) return;
      fetch(`/api/classroom/${subjectId}/course-exit/initiate`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchExitSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }

    function closeExitSurvey(subjectId) {
      if (!confirm("Are you sure you want to close and finalize this Course Exit Survey? No further responses will be accepted.")) return;
      fetch(`/api/classroom/${subjectId}/course-exit/close`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert(data.message);
          fetchExitSurveyResults(subjectId);
        } else {
          alert(data.message);
        }
      });
    }
</script>

<!-- Edit Assignment Questions Modal -->
<div id="editQuestionsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[100] hidden flex items-center justify-center p-4">
  <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-4xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
      <div>
        <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
          <x-ui.icon name="edit" class="w-5 h-5 text-blue-600" /> Manually Edit Questions (<span id="editQuestionsCoBadge" class="text-blue-700 font-mono font-bold"></span>)
        </h3>
        <p class="text-xs text-slate-600 mt-0.5">Define one or more descriptive questions for this Course Outcome. Total marks must equal exactly 20.</p>
      </div>
      <button onclick="closeEditQuestionsModal()" class="text-slate-400 hover:text-slate-700 transition-colors cursor-pointer">
        <x-ui.icon name="close" class="w-5 h-5" />
      </button>
    </div>
    
    <div class="p-6 overflow-y-auto space-y-4 flex-1 bg-white">
      <div id="editQuestionsFieldsContainer" class="space-y-4">
        <!-- Dyn fields -->
      </div>
      
      <button type="button" onclick="addManualQuestionField()" class="w-full py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 rounded-xl text-xs font-bold transition-premium flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
        <x-ui.icon name="science" class="w-4 h-4 text-blue-600" /> Add Question
      </button>
    </div>
    
    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
      <div class="text-xs font-bold text-slate-700">
        Total Marks: <span id="editQuestionsTotalMarks" class="text-emerald-700 text-sm font-black">0</span> / 20
      </div>
      <div class="flex gap-2">
        <button type="button" onclick="closeEditQuestionsModal()" class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold shadow-2xs transition-premium cursor-pointer">Cancel</button>
        <button type="button" onclick="saveManualQuestions()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium shadow-xs cursor-pointer flex items-center gap-1.5">
          <x-ui.icon name="save" class="w-4 h-4" /> Save Questions
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Virtual Classroom Students Modal -->
<div id="vcStudentsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[100] hidden flex items-center justify-center p-4">
  <div class="bg-white border border-slate-200/80 w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[80vh]">
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
      <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2 flex-wrap">
        <x-ui.icon name="groups" class="w-6 h-6 text-blue-600 flex-shrink-0" /> Enrolled Students
        <span id="vcModalBatchBadge" class="text-xs font-mono font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-md ml-2 flex-shrink-0"></span>
      </h3>
      <div class="flex items-center gap-3">
        <button onclick="printVcStudentsList()" class="text-xs font-bold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 px-3.5 py-2 rounded-xl flex items-center gap-1.5 shadow-2xs transition-premium cursor-pointer">
          <x-ui.icon name="print" class="w-4 h-4 text-slate-600" /> Print List
        </button>
        <button onclick="closeVcStudentsList()" class="text-slate-400 hover:text-slate-700 transition-colors ml-2 cursor-pointer">
          <x-ui.icon name="close" class="w-5 h-5" />
        </button>
      </div>
    </div>
    <div class="p-0 overflow-y-auto custom-scrollbar flex-1 bg-white">
      <div id="vcStudentsListContent"></div>
    </div>
  </div>
</div>

<!-- Seminar Evaluation Pop-up Modal -->
<div id="seminarEvaluationModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-[110] hidden flex items-center justify-center p-4">
  <div class="bg-white border border-slate-200/80 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col">
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
      <h3 class="text-base font-bold text-slate-900">Evaluate Seminar Presentation</h3>
      <button onclick="closeSeminarEvaluationModal()" class="text-slate-400 hover:text-slate-700 transition-colors cursor-pointer">
        <x-ui.icon name="close" class="w-4 h-4" />
      </button>
    </div>
    <form id="seminarEvaluationForm" onsubmit="submitSeminarEvaluation(event)" class="p-5 space-y-4 max-h-[80vh] overflow-y-auto">
      <div>
        <label class="block text-xs text-slate-600 font-bold uppercase tracking-wider mb-1">Student</label>
        <div id="semStudentName" class="text-base font-bold text-slate-900"></div>
        <input type="hidden" id="semStudentRegNo">
      </div>

      <!-- Relevance Slider & Input -->
      <div class="bg-slate-50 border border-slate-200 p-3.5 rounded-xl space-y-2 shadow-2xs">
        <div class="flex justify-between items-center">
          <label class="block text-xs font-bold text-slate-800">Relevance (Max 7.5)</label>
          <input type="number" step="0.1" min="0" max="7.5" id="semRelevance" required
            oninput="syncSlider('semRelevance','semRelevanceSlider',7.5); calculateSeminarTotal()"
            class="w-16 bg-white border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
        </div>
        <input type="range" id="semRelevanceSlider" min="0" max="7.5" step="0.1" value="0"
          oninput="document.getElementById('semRelevance').value = this.value; calculateSeminarTotal()"
          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
      </div>

      <!-- Literature Survey Slider & Input -->
      <div class="bg-slate-50 border border-slate-200 p-3.5 rounded-xl space-y-2 shadow-2xs">
        <div class="flex justify-between items-center">
          <label class="block text-xs font-bold text-slate-800">Literature Survey (Max 7.5)</label>
          <input type="number" step="0.1" min="0" max="7.5" id="semLiterature" required
            oninput="syncSlider('semLiterature','semLiteratureSlider',7.5); calculateSeminarTotal()"
            class="w-16 bg-white border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
        </div>
        <input type="range" id="semLiteratureSlider" min="0" max="7.5" step="0.1" value="0"
          oninput="document.getElementById('semLiterature').value = this.value; calculateSeminarTotal()"
          class="w-full h-2 rounded-full accent-indigo-600 bg-slate-200 cursor-pointer">
      </div>

      <!-- Presentation Slider & Input -->
      <div class="bg-slate-50 border border-slate-200 p-3.5 rounded-xl space-y-2 shadow-2xs">
        <div class="flex justify-between items-center">
          <label class="block text-xs font-bold text-slate-800">Presentation Quality (Max 37.5)</label>
          <input type="number" step="0.5" min="0" max="37.5" id="semPresentation" required
            oninput="syncSlider('semPresentation','semPresentationSlider',37.5); calculateSeminarTotal()"
            class="w-16 bg-white border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
        </div>
        <input type="range" id="semPresentationSlider" min="0" max="37.5" step="0.5" value="0"
          oninput="document.getElementById('semPresentation').value = this.value; calculateSeminarTotal()"
          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
      </div>

      <!-- Compact 3 Column Input Grid -->
      <div class="grid grid-cols-3 gap-3">
        <!-- Interaction -->
        <div class="bg-slate-50 border border-slate-200 p-2.5 rounded-xl text-center space-y-1.5 shadow-2xs">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Interaction</label>
          <input type="number" step="0.5" min="0" max="7.5" id="semInteraction" required
            oninput="syncSlider(this.id,null,7.5); calculateSeminarTotal()"
            class="w-full bg-white border border-slate-200 rounded-lg px-1.5 py-1.5 text-xs font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
          <div class="text-[10px] text-slate-500 font-medium">max 7.5</div>
        </div>

        <!-- Report -->
        <div class="bg-slate-50 border border-slate-200 p-2.5 rounded-xl text-center space-y-1.5 shadow-2xs">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Report</label>
          <input type="number" step="0.5" min="0" max="7.5" id="semReport" required
            oninput="syncSlider(this.id,null,7.5); calculateSeminarTotal()"
            class="w-full bg-white border border-slate-200 rounded-lg px-1.5 py-1.5 text-xs font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
          <div class="text-[10px] text-slate-500 font-medium">max 7.5</div>
        </div>

        <!-- Attendance -->
        <div class="bg-slate-50 border border-slate-200 p-2.5 rounded-xl text-center space-y-1.5 shadow-2xs">
          <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Attendance</label>
          <input type="number" step="0.5" min="0" max="7.5" id="semAttendance" required
            oninput="syncSlider(this.id,null,7.5); calculateSeminarTotal()"
            class="w-full bg-white border border-slate-200 rounded-lg px-1.5 py-1.5 text-xs font-bold text-slate-900 text-center focus:border-blue-500 shadow-2xs outline-none">
          <div class="text-[10px] text-slate-500 font-medium">max 7.5</div>
        </div>
      </div>

      <!-- Total Score Banner -->
      <div class="pt-4 border-t border-slate-200 flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-200 shadow-2xs">
        <div>
          <span class="text-xs text-slate-600 font-bold uppercase tracking-wider">Total Score:</span>
          <span id="semTotalScoreLabel" class="text-xl font-black text-blue-600 ml-2">0.00 / 75</span>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-xs transition-premium cursor-pointer">
          Save Evaluation
        </button>
      </div>
    </form>
  </div>
</div>

<script>
    function syncSlider(inputId, sliderId, max) {
      const input = document.getElementById(inputId);
      if (!input) return;
      let val = parseFloat(input.value);
      if (isNaN(val)) val = 0;
      if (val > max) val = max;
      if (val < 0) val = 0;
      input.value = val;
      if (sliderId) {
        const slider = document.getElementById(sliderId);
        if (slider) slider.value = val;
      }
    }

    let activeSeminarData = [];

    function fetchSeminarEvaluations() {
      const tbody = document.getElementById('seminarEvaluationsTableBody');
      tbody.innerHTML = '<tr><td colspan="14" class="p-8 text-center text-slate-500 font-bold text-xs animate-pulse">Loading evaluations data...</td></tr>';
      
      const printBtn = document.getElementById('printSeminarReportBtn');
      if (printBtn) {
        printBtn.href = `/classroom/${currentSubjectId}/seminar-report`;
      }

      fetch(`/api/classroom/${currentSubjectId}/seminar/evaluations`)
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          activeSeminarData = res.data;
          renderSeminarEvaluations();
        } else {
          tbody.innerHTML = `<tr><td colspan="14" class="p-8 text-center text-red-400 font-bold text-xs">${res.message}</td></tr>`;
        }
      })
      .catch(err => {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="14" class="p-8 text-center text-red-400 font-bold text-xs">Failed to load seminar evaluations.</td></tr>';
      });
    }

    function renderSeminarEvaluations() {
      const tbody = document.getElementById('seminarEvaluationsTableBody');
      tbody.innerHTML = '';

      if (activeSeminarData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="14" class="p-8 text-center text-slate-500 italic text-xs">No students enrolled in this batch.</td></tr>';
        return;
      }

      activeSeminarData.forEach(student => {
        const me = student.my_evaluation;
        const row = document.createElement('tr');
        row.className = 'border-b border-slate-100 hover:bg-slate-50/70 text-sm font-semibold text-slate-800';
        
        row.innerHTML = `
          <td class="p-3 font-mono">${student.roll_no || '-'}</td>
          <td class="p-3 font-extrabold text-white">${student.name}</td>
          <td class="p-3 font-medium max-w-[200px] truncate" title="${student.topic || '-'}">${student.topic || '<span class="text-slate-600 italic">Not Registered</span>'}</td>
          <td class="p-3 text-slate-600">${student.guide_name || '-'}</td>
          <td class="p-3 text-center">${student.presentation_date || '-'}</td>
          <td class="p-3 text-center">${me ? me.relevance : '-'}</td>
          <td class="p-3 text-center">${me ? me.literature : '-'}</td>
          <td class="p-3 text-center">${me ? me.presentation : '-'}</td>
          <td class="p-3 text-center">${me ? me.interaction : '-'}</td>
          <td class="p-3 text-center">${me ? me.report : '-'}</td>
          <td class="p-3 text-center">${me ? me.attendance : '-'}</td>
          <td class="p-3 text-center font-bold text-slate-900">${me ? me.total_score : '-'}</td>
          <td class="p-3 text-center font-bold text-teal-700 font-bold">${student.average_score} <span class="text-[10px] text-slate-500 font-normal">(${student.evaluators_count} assessors)</span></td>
          <td class="p-3 text-center">
            <button onclick="openSeminarEvaluationModal('${student.reg_no}')" class="px-3 py-1.5 bg-blue-500/10 hover:bg-blue-500 text-blue-400 hover:text-white rounded-lg font-bold text-[11px] transition-premium cursor-pointer border border-blue-500/25">
              ${me ? 'Modify' : 'Evaluate'}
            </button>
          </td>
        `;
        tbody.appendChild(row);
      });
    }

    function openSeminarEvaluationModal(regNo) {
      const student = activeSeminarData.find(s => s.reg_no === regNo);
      if (!student) return;

      document.getElementById('semStudentName').innerText = `${student.name} (${student.reg_no})`;
      document.getElementById('semStudentRegNo').value = regNo;

      const me = student.my_evaluation;
      document.getElementById('semRelevance').value = me ? me.relevance : '';
      document.getElementById('semLiterature').value = me ? me.literature : '';
      document.getElementById('semPresentation').value = me ? me.presentation : '';
      
      document.getElementById('semRelevanceSlider').value = me ? me.relevance : 0;
      document.getElementById('semLiteratureSlider').value = me ? me.literature : 0;
      document.getElementById('semPresentationSlider').value = me ? me.presentation : 0;

      document.getElementById('semInteraction').value = me ? me.interaction : '';
      document.getElementById('semReport').value = me ? me.report : '';
      document.getElementById('semAttendance').value = me ? me.attendance : '';

      calculateSeminarTotal();
      document.getElementById('seminarEvaluationModal').classList.remove('hidden');
      document.getElementById('seminarEvaluationModal').classList.add('flex');
    }

    function closeSeminarEvaluationModal() {
      document.getElementById('seminarEvaluationModal').classList.add('hidden');
      document.getElementById('seminarEvaluationModal').classList.remove('flex');
    }

    function calculateSeminarTotal() {
      const relevance = parseFloat(document.getElementById('semRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('semLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('semPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('semInteraction').value) || 0;
      const report = parseFloat(document.getElementById('semReport').value) || 0;
      const attendance = parseFloat(document.getElementById('semAttendance').value) || 0;

      const total = relevance + literature + presentation + interaction + report + attendance;
      document.getElementById('semTotalScoreLabel').innerText = `${total.toFixed(0)} / 75`;
    }

    function submitSeminarEvaluation(e) {
      e.preventDefault();
      const regNo = document.getElementById('semStudentRegNo').value;
      const relevance = parseFloat(document.getElementById('semRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('semLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('semPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('semInteraction').value) || 0;
      const report = parseFloat(document.getElementById('semReport').value) || 0;
      const attendance = parseFloat(document.getElementById('semAttendance').value) || 0;

      fetch(`/api/classroom/${currentSubjectId}/seminar/evaluate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          reg_no: regNo,
          relevance: relevance,
          literature: literature,
          presentation: presentation,
          interaction: interaction,
          report: report,
          attendance: attendance
        })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          alert('Seminar evaluation saved successfully!');
          closeSeminarEvaluationModal();
          fetchSeminarEvaluations();
        } else {
          alert(res.message);
        }
      })
      .catch(err => {
        console.error(err);
        alert('Failed to save seminar evaluation.');
      });
    }

    let todaySeminarsData = [];
    let mobSemCurrentRegNo = null;

    function clampMobSem(input, max) {
      const v = parseFloat(input.value);
      if (!isNaN(v) && v > max) input.value = max;
      if (!isNaN(v) && v < 0) input.value = 0;
      // Sync range slider sibling if present
      const sliders = input.closest('.bg-slate-100\/40, .bg-slate-100\/40.border')?.querySelectorAll('input[type=range]');
      if (sliders && sliders.length) sliders[0].value = input.value;
    }

    function showMobileSemToast(msg, type = 'success') {
      const toast = document.getElementById('mobileSemToast');
      if (!toast) return;
      
      const isSuccess = (type === 'success');
      const isWarning = (type === 'warning');
      
      toast.className = `mb-4 px-4 py-3.5 rounded-2xl text-sm font-semibold flex items-center justify-between gap-3 shadow-md transition-all border ${
        isSuccess 
          ? 'bg-white/95 border-emerald-200 text-slate-900 shadow-emerald-950/10 ring-1 ring-emerald-500/10' 
          : isWarning
            ? 'bg-white/95 border-amber-200 text-slate-900 shadow-amber-950/10 ring-1 ring-amber-500/10'
            : 'bg-white/95 border-rose-200 text-slate-900 shadow-rose-950/10 ring-1 ring-rose-500/10'
      }`;
      
      const badgeClass = isSuccess ? 'bg-emerald-50 text-emerald-600 border border-emerald-200/80' : isWarning ? 'bg-amber-50 text-amber-600 border border-amber-200/80' : 'bg-rose-50 text-rose-600 border border-rose-200/80';
      const iconSvg = isSuccess 
        ? '<svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>'
        : isWarning
          ? '<svg class="w-4 h-4 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'
          : '<svg class="w-4 h-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';

      toast.innerHTML = `
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 ${badgeClass}">
            ${iconSvg}
          </div>
          <span class="text-slate-900 font-semibold text-sm leading-snug">${msg}</span>
        </div>
        <button type="button" onclick="this.closest(\'#mobileSemToast\').classList.add(\'hidden\')" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer text-sm font-bold shrink-0">✕</button>
      `;
      
      toast.classList.remove('hidden');
      setTimeout(() => toast.classList.add('hidden'), 5000);
    }

    function checkTodaySeminars() {
      fetch('/api/lecturer/today-seminars')
      .then(res => res.json())
      .then(res => {
        const container = document.getElementById('seminarNotificationsContainer');
        const mobContainer = document.getElementById('mobileSeminarNotificationsContainer');
        
        if (container) container.innerHTML = '';
        if (mobContainer) mobContainer.innerHTML = '';

        if (res.status === 'SUCCESS' && res.data.length > 0) {
          todaySeminarsData = res.data;

          // Group by classroom_id
          const groups = {};
          todaySeminarsData.forEach(item => {
            const cid = item.classroom_id || 'Unknown_Classroom';
            if (!groups[cid]) {
              groups[cid] = [];
            }
            groups[cid].push(item);
          });

          // Render cards
          Object.keys(groups).forEach(cid => {
            const items = groups[cid];
            const first = items[0];
            const count = items.length;

            // Desktop card
            if (container) {
              const card = document.createElement('div');
              card.className = "p-4 bg-gradient-to-br from-amber-500/20 via-orange-600/15 to-violet-950/40 border border-amber-500/40 hover:border-amber-400/80 rounded-2xl flex items-center justify-between shadow-[0_0_15px_rgba(245,158,11,0.1)] hover:shadow-[0_0_20px_rgba(245,158,11,0.2)] transition-premium cursor-pointer group relative overflow-hidden";
              card.onclick = () => {
                if (window.innerWidth < 768) {
                  openMobileSeminarEvaluation();
                } else {
                  openClassroom(cid, first.batch_subject_id, first.subject_name || 'Seminar');
                }
              };
              card.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                  <div class="bg-amber-500/10 p-2 rounded-xl text-amber-400 group-hover:bg-amber-500 group-hover:text-black transition-premium">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h20"/><path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3"/><path d="m7 21 5-5 5 5"/></svg>
                  </div>
                  <div class="min-w-0">
                    <h5 class="text-xs font-black text-amber-300 group-hover:text-white transition-premium truncate">Seminar Day (${count})</h5>
                    <p class="text-[11px] text-slate-600 mt-0.5 truncate">${cid} · ${first.subject_name || 'Seminar'}</p>
                  </div>
                </div>
                <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              `;
              container.appendChild(card);
            }

            // Mobile card
            if (mobContainer) {
              const cardMob = document.createElement('div');
              cardMob.className = "p-4 bg-gradient-to-br from-amber-500/20 via-orange-600/15 to-violet-950/40 border border-amber-500/40 hover:border-amber-400/80 rounded-2xl flex items-center justify-between shadow-[0_0_15px_rgba(245,158,11,0.1)] transition-premium cursor-pointer group relative overflow-hidden";
              cardMob.onclick = () => {
                openMobileSeminarEvaluation();
              };
              cardMob.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                  <div class="bg-amber-500/10 p-2 rounded-xl text-amber-400 group-hover:bg-amber-500 group-hover:text-black transition-premium">
                    <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                  </div>
                  <div class="min-w-0">
                    <h5 class="text-xs font-black text-amber-300 group-hover:text-white transition-premium truncate">Active Seminar Day (${count})</h5>
                    <p class="text-[11px] text-slate-600 mt-0.5 truncate">${cid} · ${first.subject_name || 'Seminar'}</p>
                  </div>
                </div>
                <svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              `;
              mobContainer.appendChild(cardMob);
            }
          });

          if (container) container.classList.remove('hidden');
          if (mobContainer) mobContainer.classList.remove('hidden');
        } else {
          if (container) container.classList.add('hidden');
          if (mobContainer) mobContainer.classList.add('hidden');
        }
      })
      .catch(err => console.error('Failed to load today seminars:', err));
    }

    function goToVirtualSeminarClassroom() {
      // deprecated but kept as safe fallback
    }

    function openMobileSeminarEvaluation() {
      switchPanel('mobileSeminar');
      mobSemCurrentRegNo = null;
      document.getElementById('mobileSemStep1').classList.remove('hidden');
      document.getElementById('mobileSemStep2').classList.add('hidden');
      refreshMobileSeminarsList();
    }

    function backToSeminarList() {
      mobSemCurrentRegNo = null;
      document.getElementById('mobileSemStep1').classList.remove('hidden');
      document.getElementById('mobileSemStep2').classList.add('hidden');
    }

    function refreshMobileSeminarsList() {
      const pendingList = document.getElementById('mobilePendingInvitationsList');
      const attendingList = document.getElementById('mobileSemAttendingList');
      pendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">Loading...</div>';
      attendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">Loading...</div>';

      fetch('/api/lecturer/today-seminars')
      .then(res => res.json())
      .then(res => {
        if (res.status !== 'SUCCESS') { pendingList.innerHTML = '<div class="text-xs text-red-400 py-2">Failed to load.</div>'; return; }
        todaySeminarsData = res.data;

        // Pending invitations
        const pending = todaySeminarsData.filter(s => !s.accepted);
        if (pending.length === 0) {
          pendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">No pending invitations today.</div>';
        } else {
          pendingList.innerHTML = '';
          pending.forEach(s => {
            const card = document.createElement('div');
            card.className = 'bg-white border border-amber-700/30 rounded-xl p-4 space-y-3';
            card.innerHTML = `
              <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                  <div class="font-extrabold text-white text-sm truncate">${s.student_name}</div>
                  <div class="text-[10px] font-mono text-slate-600">${s.sbte_reg_no || '-'}</div>
                </div>
                <span class="shrink-0 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-900/60 text-amber-400 border border-amber-700/40">Pending</span>
              </div>
              <div class="bg-slate-100/60 rounded-lg px-3 py-2">
                <div class="text-[10px] text-slate-500 uppercase tracking-wide">Topic</div>
                <div class="text-xs text-white font-semibold mt-0.5 leading-snug">${s.topic || '-'}</div>
              </div>
              <div class="text-[10px] text-slate-500">Guide: <span class="text-slate-800">${s.guide_name || '-'}</span></div>
              <div class="grid grid-cols-2 gap-2">
                <button onclick="acceptMobileInvitation(${s.id})" class="py-2.5 bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center justify-center gap-1">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg> Accept
                </button>
                <button onclick="openMobSemEvaluation('${s.reg_no}')" class="py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-900 rounded-xl text-xs font-bold border border-slate-700 transition-premium cursor-pointer flex items-center justify-center gap-1">
                  <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Evaluate
                </button>
              </div>
            `;
            pendingList.appendChild(card);
          });
        }

        // Accepted / Attending
        const accepted = todaySeminarsData.filter(s => s.accepted);
        if (accepted.length === 0) {
          attendingList.innerHTML = '<div class="text-xs text-slate-500 text-center py-3">No accepted seminars yet. Accept an invitation above.</div>';
        } else {
          attendingList.innerHTML = '';
          accepted.forEach(s => {
            const card = document.createElement('div');
            card.className = 'bg-white border border-emerald-700/20 rounded-xl p-4 flex items-center justify-between gap-3';
            card.innerHTML = `
              <div class="min-w-0">
                <div class="font-bold text-white text-sm truncate">${s.student_name}</div>
                <div class="text-xs text-slate-600 mt-0.5 truncate">${s.topic || '-'}</div>
              </div>
              <button onclick="openMobSemEvaluation('${s.reg_no}')" class="shrink-0 px-4 py-2 bg-emerald-700/80 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg> Evaluate
              </button>
            `;
            attendingList.appendChild(card);
          });
        }
      })
      .catch(() => {
        pendingList.innerHTML = '<div class="text-xs text-red-400 py-2">Failed to load. Try again.</div>';
      });
    }

    function acceptMobileInvitation(seminarRegId) {
      fetch('/api/lecturer/seminar/accept', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ seminar_registration_id: seminarRegId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showMobileSemToast('Invitation accepted! You can now evaluate this student.', 'success');
          refreshMobileSeminarsList();
          checkTodaySeminars();
        } else {
          showMobileSemToast(data.message || 'Failed to accept.', 'error');
        }
      })
      .catch(() => showMobileSemToast('Network error. Try again.', 'error'));
    }

    function openMobSemEvaluation(regNo) {
      const seminar = todaySeminarsData.find(s => s.reg_no === regNo);
      if (!seminar) return;
      mobSemCurrentRegNo = regNo;
      currentSubjectId = seminar.batch_subject_id;

      // Populate student card
      document.getElementById('mobSemStudentName').innerText = seminar.student_name || '-';
      document.getElementById('mobSemSbteRegV2').innerText = seminar.sbte_reg_no || '-';
      document.getElementById('mobSemTopicV2').innerText = seminar.topic || '-';

      // Reset form
      ['mobSemRelevance','mobSemLiterature','mobSemPresentation','mobSemInteraction','mobSemReport','mobSemAttendance']
        .forEach(id => { document.getElementById(id).value = ''; });
      // Reset sliders
      document.querySelectorAll('#mobileSeminarForm input[type=range]').forEach(r => r.value = 0);
      calcMobSemTotal();

      // Switch to step 2
      document.getElementById('mobileSemStep1').classList.add('hidden');
      document.getElementById('mobileSemStep2').classList.remove('hidden');

      // Load existing evaluation
      fetch(`/api/classroom/${seminar.batch_subject_id}/seminar/evaluations`)
      .then(r => r.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          const stud = res.data.find(s => s.reg_no === regNo);
          const me = stud ? stud.my_evaluation : null;
          if (me) {
            document.getElementById('mobSemRelevance').value = me.relevance;
            document.getElementById('mobSemLiterature').value = me.literature;
            document.getElementById('mobSemPresentation').value = me.presentation;
            document.getElementById('mobSemInteraction').value = me.interaction;
            document.getElementById('mobSemReport').value = me.report;
            document.getElementById('mobSemAttendance').value = me.attendance;
            // Sync sliders
            const sliders = document.querySelectorAll('#mobileSeminarForm input[type=range]');
            const vals = [me.relevance, me.literature, me.presentation];
            sliders.forEach((sl, i) => { if (vals[i] !== undefined) sl.value = vals[i]; });
            calcMobSemTotal();
          }
        }
      });
    }

    function calcMobSemTotal() {
      const relevance = parseFloat(document.getElementById('mobSemRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('mobSemLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('mobSemPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('mobSemInteraction').value) || 0;
      const report = parseFloat(document.getElementById('mobSemReport').value) || 0;
      const attendance = parseFloat(document.getElementById('mobSemAttendance').value) || 0;
      const total = relevance + literature + presentation + interaction + report + attendance;
      const pct = total / 75;

      // Update number display
      const numEl = document.getElementById('mobSemTotalNum');
      if (numEl) {
        numEl.innerText = total.toFixed(0);
        numEl.style.color = total >= 60 ? '#34d399' : total >= 45 ? '#60a5fa' : total >= 30 ? '#fbbf24' : '#f87171';
      }

      // Update score ring
      const circle = document.getElementById('mobScoreRingCircle');
      if (circle) {
        const circumference = 163.36;
        circle.style.strokeDashoffset = circumference * (1 - pct);
        circle.style.stroke = total >= 60 ? '#34d399' : total >= 45 ? '#3b82f6' : total >= 30 ? '#f59e0b' : '#ef4444';
      }
      const ringScore = document.getElementById('mobSemRingScore');
      if (ringScore) ringScore.innerText = total.toFixed(0);

      // Compat: old label
      const oldLabel = document.getElementById('mobSemTotalScoreLabel');
      if (oldLabel) oldLabel.innerText = `${total.toFixed(0)} / 75`;
    }

    // Keep old name as alias for compat
    function calculateMobileSeminarTotal() { calcMobSemTotal(); }

    function submitMobileSeminarEvaluation(e) {
      e.preventDefault();
      const regNo = mobSemCurrentRegNo;
      if (!regNo) return;
      const seminar = todaySeminarsData.find(s => s.reg_no === regNo);
      if (!seminar) return;

      const relevance = parseFloat(document.getElementById('mobSemRelevance').value) || 0;
      const literature = parseFloat(document.getElementById('mobSemLiterature').value) || 0;
      const presentation = parseFloat(document.getElementById('mobSemPresentation').value) || 0;
      const interaction = parseFloat(document.getElementById('mobSemInteraction').value) || 0;
      const report = parseFloat(document.getElementById('mobSemReport').value) || 0;
      const attendance = parseFloat(document.getElementById('mobSemAttendance').value) || 0;

      const btn = document.getElementById('mobSemSubmitBtn');
      btn.disabled = true;
      btn.innerHTML = '<svg class="w-4 h-4 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg> Saving...';

      fetch(`/api/classroom/${seminar.batch_subject_id}/seminar/evaluate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ reg_no: regNo, relevance, literature, presentation, interaction, report, attendance })
      })
      .then(res => res.json())
      .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save';
        if (res.status === 'SUCCESS') {
          showMobileSemToast(`Evaluation saved! Avg score: ${res.average_score} / 75`, 'success');
          // Silently refresh the desktop seminar table so marks appear without page reload
          if (typeof fetchSeminarEvaluations === 'function') {
            try {
              fetchSeminarEvaluations();
            } catch (err) {
              console.warn("Silent background table refresh failed:", err);
            }
          }
          setTimeout(() => backToSeminarList(), 1500);
        } else {
          showMobileSemToast(res.message || 'Failed to save.', 'error');
        }
      })
      .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Save';
        showMobileSemToast('Network error. Please try again.', 'error');
      });
    }

    // Legacy: keep old handler names as aliases for compat with any inline onclick
    function handleMobileSemStudentChange() {}
    function refreshMobileSeminarsList_old() { refreshMobileSeminarsList(); }

    // ==========================================
    // VIRTUAL LAB WORKSPACE MODALS (REVISION 2021)
    // ==========================================
    const dynamicLabModalsHtml = `
      <!-- Student Lab Modal -->
      <div id="studentLabModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden justify-center items-center p-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-6xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">
          <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <div>
              <h3 id="labModalStudentName" class="text-base font-bold text-slate-900">Student Evaluation</h3>
              <p id="labModalStudentReg" class="text-xs font-semibold text-slate-500 font-mono"></p>
            </div>
            <button onclick="closeStudentLabModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer text-sm font-bold">
              ✕
            </button>
          </div>
          <div class="px-6 py-2.5 bg-white border-b border-slate-200 flex gap-4 text-xs font-bold">
            <button onclick="switchLabModalTab(\'exp\')" id="labTabBtn_exp" class="py-2 border-b-2 border-blue-600 text-blue-600 px-1 transition-premium font-semibold">Experiments (37.5)</button>
            <button onclick="switchLabModalTab(\'test\')" id="labTabBtn_test" class="py-2 border-b-2 border-transparent text-slate-500 hover:text-slate-800 px-1 transition-premium font-semibold">Model Tests (15)</button>
            <button onclick="switchLabModalTab(\'project\')" id="labTabBtn_project" class="py-2 border-b-2 border-transparent text-slate-500 hover:text-slate-800 px-1 transition-premium font-semibold">Micro-Project &amp; Attendance (22.5)</button>
            <button onclick="switchLabModalTab(\'board\')" id="labTabBtn_board" class="py-2 border-b-2 border-transparent text-slate-500 hover:text-slate-800 px-1 transition-premium font-bold text-blue-600">Board Exam (50)</button>
          </div>
          
          <div class="flex-grow overflow-y-auto p-6 space-y-6">
            <!-- TAB: EXPERIMENTS -->
            <div id="labModalTab_exp" class="space-y-5">
              <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
                  <div class="space-y-1">
                    <label class="block text-sm font-bold text-slate-900 uppercase tracking-wider">Select Lab Experiment</label>
                    <p class="text-xs text-slate-500 font-medium">Choose an experiment to grade or view scores.</p>
                  </div>
                  <select id="labModalExpSelect" onchange="changeActiveExperiment()" class="bg-white border border-slate-200 text-slate-900 rounded-xl px-4 py-2.5 text-sm font-bold focus:border-blue-500 outline-none w-full sm:w-80 shadow-2xs">
                    <!-- Dynamic experiment options -->
                  </select>
                </div>

                <!-- Common sliders for the selected experiment -->
                <div id="activeExperimentContainer" class="hidden space-y-4">
                  <div class="flex justify-between items-center bg-white px-4 py-3 rounded-xl border border-slate-200 shadow-2xs">
                    <span class="text-sm font-bold text-slate-900" id="activeExpTitle">Experiment Details</span>
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold uppercase font-mono" id="activeExpCo">CO Map</span>
                  </div>
                  
                  <!-- Responsive horizontal grid for Desktop, stacks nicely on Mobile -->
                  <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    
                    <!-- Prereq -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800 whitespace-nowrap">Prerequisites</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="active_exp_prerequisite" 
                          oninput="syncSlider(\'active_exp_prerequisite\',\'active_exp_prerequisite_slider\',7.5); updateTempExpMark(\'prerequisite\', this.value)"
                          class="w-14 bg-slate-50 border border-slate-200 rounded-lg py-1 text-center font-bold text-slate-900 text-sm focus:border-blue-500 outline-none">
                      </div>
                      <div class="space-y-1">
                        <input type="range" id="active_exp_prerequisite_slider" min="0" max="7.5" step="0.1" value="0"
                          oninput="document.getElementById(\'active_exp_prerequisite\').value = this.value; updateTempExpMark(\'prerequisite\', this.value)"
                          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold">
                          <span>0</span>
                          <span>Max 7.5</span>
                        </div>
                      </div>
                    </div>

                    <!-- Work Done -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800 whitespace-nowrap">Work Done</span>
                        <input type="number" step="0.1" min="0" max="10" id="active_exp_execution" 
                          oninput="syncSlider(\'active_exp_execution\',\'active_exp_execution_slider\',10); updateTempExpMark(\'execution\', this.value)"
                          class="w-14 bg-slate-50 border border-slate-200 rounded-lg py-1 text-center font-bold text-slate-900 text-sm focus:border-blue-500 outline-none">
                      </div>
                      <div class="space-y-1">
                        <input type="range" id="active_exp_execution_slider" min="0" max="10" step="0.1" value="0"
                          oninput="document.getElementById(\'active_exp_execution\').value = this.value; updateTempExpMark(\'execution\', this.value)"
                          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold">
                          <span>0</span>
                          <span>Max 10</span>
                        </div>
                      </div>
                    </div>

                    <!-- Result -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800 whitespace-nowrap">Result</span>
                        <input type="number" step="0.1" min="0" max="5" id="active_exp_output" 
                          oninput="syncSlider(\'active_exp_output\',\'active_exp_output_slider\',5); updateTempExpMark(\'output\', this.value)"
                          class="w-14 bg-slate-50 border border-slate-200 rounded-lg py-1 text-center font-bold text-slate-900 text-sm focus:border-blue-500 outline-none">
                      </div>
                      <div class="space-y-1">
                        <input type="range" id="active_exp_output_slider" min="0" max="5" step="0.1" value="0"
                          oninput="document.getElementById(\'active_exp_output\').value = this.value; updateTempExpMark(\'output\', this.value)"
                          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold">
                          <span>0</span>
                          <span>Max 5</span>
                        </div>
                      </div>
                    </div>

                    <!-- Rough Record -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800 whitespace-nowrap">Rough Record</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="active_exp_rough_record" 
                          oninput="syncSlider(\'active_exp_rough_record\',\'active_exp_rough_record_slider\',7.5); updateTempExpMark(\'rough_record\', this.value)"
                          class="w-14 bg-slate-50 border border-slate-200 rounded-lg py-1 text-center font-bold text-slate-900 text-sm focus:border-blue-500 outline-none">
                      </div>
                      <div class="space-y-1">
                        <input type="range" id="active_exp_rough_record_slider" min="0" max="7.5" step="0.1" value="0"
                          oninput="document.getElementById(\'active_exp_rough_record\').value = this.value; updateTempExpMark(\'rough_record\', this.value)"
                          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold">
                          <span>0</span>
                          <span>Max 7.5</span>
                        </div>
                      </div>
                    </div>

                    <!-- Fair Record -->
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-3 flex flex-col justify-between">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800 whitespace-nowrap">Fair Record</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="active_exp_fair_record" 
                          oninput="syncSlider(\'active_exp_fair_record\',\'active_exp_fair_record_slider\',7.5); updateTempExpMark(\'fair_record\', this.value)"
                          class="w-14 bg-slate-50 border border-slate-200 rounded-lg py-1 text-center font-bold text-slate-900 text-sm focus:border-blue-500 outline-none">
                      </div>
                      <div class="space-y-1">
                        <input type="range" id="active_exp_fair_record_slider" min="0" max="7.5" step="0.1" value="0"
                          oninput="document.getElementById(\'active_exp_fair_record\').value = this.value; updateTempExpMark(\'fair_record\', this.value)"
                          class="w-full h-2 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                        <div class="flex justify-between text-xs text-slate-400 font-semibold">
                          <span>0</span>
                          <span>Max 7.5</span>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>

                <div id="noActiveExperimentMsg" class="p-4 text-center text-slate-500 font-medium text-sm">
                  Please select an experiment from the dropdown above to view or modify grades.
                </div>
              </div>
            </div>

            <!-- TAB: TESTS -->
            <div id="labModalTab_test" class="space-y-4 hidden">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Test 1 -->
                <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4">
                  <div class="border-b border-slate-200 pb-2.5 flex justify-between items-center">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Model Test 1 (CO1 &amp; CO2)</h4>
                    <span class="text-xs font-bold text-blue-600 font-mono" id="labModalT1Sum">0.0 / 15</span>
                  </div>
                  <div class="space-y-4">
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800">CO1 Score (Max 7.5)</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="labScore_t1_co1" oninput="syncSlider(\'labScore_t1_co1\',\'labScore_t1_co1_slider\',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                      </div>
                      <input type="range" id="labScore_t1_co1_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById(\'labScore_t1_co1\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800">CO2 Score (Max 7.5)</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="labScore_t1_co2" oninput="syncSlider(\'labScore_t1_co2\',\'labScore_t1_co2_slider\',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                      </div>
                      <input type="range" id="labScore_t1_co2_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById(\'labScore_t1_co2\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                    </div>
                  </div>
                </div>
                <!-- Test 2 -->
                <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4">
                  <div class="border-b border-slate-200 pb-2.5 flex justify-between items-center">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Model Test 2 (CO3 &amp; CO4)</h4>
                    <span class="text-xs font-bold text-blue-600 font-mono" id="labModalT2Sum">0.0 / 15</span>
                  </div>
                  <div class="space-y-4">
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800">CO3 Score (Max 7.5)</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="labScore_t2_co3" oninput="syncSlider(\'labScore_t2_co3\',\'labScore_t2_co3_slider\',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                      </div>
                      <input type="range" id="labScore_t2_co3_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById(\'labScore_t2_co3\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                      <div class="flex justify-between items-center text-sm font-bold">
                        <span class="text-slate-800">CO4 Score (Max 7.5)</span>
                        <input type="number" step="0.1" min="0" max="7.5" id="labScore_t2_co4" oninput="syncSlider(\'labScore_t2_co4\',\'labScore_t2_co4_slider\',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                      </div>
                      <input type="range" id="labScore_t2_co4_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById(\'labScore_t2_co4\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- TAB: PROJECT & ATTENDANCE -->
            <div id="labModalTab_project" class="space-y-4 hidden">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4 flex flex-col justify-between">
                  <div>
                    <h4 class="text-xs font-bold text-slate-700 border-b border-slate-200 pb-2 uppercase tracking-wider mb-3">Open-Ended Project / Micro-Project</h4>
                    <label class="text-xs font-bold text-slate-600 uppercase block mb-1.5">Project Topic Description</label>
                    <input type="text" id="labScore_projectTopic" placeholder="Enter assigned micro-project topic..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-medium text-slate-900 focus:border-blue-500 outline-none mb-4 shadow-2xs">
                  </div>
                  <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                    <div class="flex justify-between items-center text-sm font-bold">
                      <span class="text-slate-800">Project Mark (Max 7.5)</span>
                      <input type="number" step="0.1" min="0" max="7.5" id="labScore_projectMark" oninput="syncSlider(\'labScore_projectMark\',\'labScore_projectMark_slider\',7.5); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                    </div>
                    <input type="range" id="labScore_projectMark_slider" min="0" max="7.5" step="0.1" value="0" oninput="document.getElementById(\'labScore_projectMark\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                  </div>
                </div>
                <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4 flex flex-col justify-between">
                  <div>
                    <h4 class="text-xs font-bold text-slate-700 border-b border-slate-200 pb-2 uppercase tracking-wider mb-3">Attendance Scoring</h4>
                    <div class="flex justify-between items-center mb-3">
                      <span class="text-xs text-slate-600 font-semibold">Class Attendance Percentage:</span>
                      <span class="text-sm font-bold text-slate-900 font-mono" id="labModalStudentAttPct">0%</span>
                    </div>
                  </div>
                  <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                    <div class="flex justify-between items-center text-sm font-bold">
                      <span class="text-slate-800">Attendance Mark (Max 15)</span>
                      <input type="number" step="0.1" min="0" max="15" id="labScore_attendanceMark" oninput="syncSlider(\'labScore_attendanceMark\',\'labScore_attendanceMark_slider\',15); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none">
                    </div>
                    <input type="range" id="labScore_attendanceMark_slider" min="0" max="15" step="0.1" value="0" oninput="document.getElementById(\'labScore_attendanceMark\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                  </div>
                </div>
              </div>
            </div>

            <!-- TAB: BOARD EXAM -->
            <div id="labModalTab_board" class="space-y-4 hidden">
              <div class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4 max-w-md mx-auto">
                <h4 class="text-xs font-bold text-slate-700 border-b border-slate-200 pb-2 uppercase tracking-wider">External Board Examination</h4>
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-2">
                  <div class="flex justify-between items-center text-sm font-bold">
                    <span class="text-slate-800">Board Exam Mark (Max 50)</span>
                    <input type="number" step="0.5" min="0" max="50" id="labScore_boardExam" oninput="syncSlider(\'labScore_boardExam\',\'labScore_boardExam_slider\',50); calcLabModalScores()" class="w-16 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-sm font-bold text-slate-900 text-center focus:border-blue-500 outline-none" placeholder="0.0">
                  </div>
                  <input type="range" id="labScore_boardExam_slider" min="0" max="50" step="0.5" value="0" oninput="document.getElementById(\'labScore_boardExam\').value = this.value; calcLabModalScores()" class="w-full h-1.5 rounded-full accent-blue-600 bg-slate-200 cursor-pointer">
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom bar -->
          <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
            <div class="text-xs text-slate-600 font-semibold flex gap-4">
              <div>Lab Work Avg: <span class="text-slate-900 font-mono font-bold" id="labModalLabelExp">0.0</span></div>
              <div>Model Test: <span class="text-slate-900 font-mono font-bold" id="labModalLabelTest">0.0</span></div>
              <div>Internal CA: <span class="text-emerald-700 font-bold font-mono text-sm" id="labModalLabelInternals">0.0 / 75</span></div>
            </div>
            <button onclick="saveStudentLabEvaluation()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer shadow-xs">
              Save Evaluation
            </button>
          </div>
        </div>
      </div>

      <!-- Manage Experiments Modal -->
      <div id="manageExperimentsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden justify-center items-center p-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-5xl max-h-[85vh] flex flex-col overflow-hidden shadow-2xl">
          <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <div>
              <h3 class="text-base font-bold text-slate-900">Experiments List</h3>
              <p class="text-xs text-slate-500 mt-0.5 font-medium">Setup the experiments syllabus for day-to-day continuous evaluation.</p>
            </div>
            <button onclick="closeManageExperimentsModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer text-sm font-bold">
              ✕
            </button>
          </div>

          <div class="p-6 overflow-y-auto space-y-6 flex-grow">
            <!-- Add Experiment Form -->
            <form onsubmit="savePracticalExperiment(event)" class="bg-slate-50/70 border border-slate-200/80 p-5 rounded-2xl space-y-4">
              <input type="hidden" id="expEditId">
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-1">
                  <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Exp No.</label>
                  <input type="text" id="expFormNo" required placeholder="e.g. 1, 2A" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-900 focus:border-blue-500 outline-none shadow-2xs">
                </div>
                <div class="md:col-span-2">
                  <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Experiment Title / Objective</label>
                  <textarea id="expFormTitle" required placeholder="Enter experiment objective / detailed description..." rows="2" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-medium text-slate-900 focus:border-blue-500 outline-none resize-y shadow-2xs"></textarea>
                </div>
                <div class="md:col-span-1">
                  <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Map CO</label>
                  <select id="expFormCo" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-900 focus:border-blue-500 outline-none cursor-pointer shadow-2xs">
                    <option value="CO1">CO1</option>
                    <option value="CO2">CO2</option>
                    <option value="CO3">CO3</option>
                    <option value="CO4">CO4</option>
                  </select>
                </div>
              </div>
              <div class="flex justify-between items-center pt-2">
                <button type="button" id="btnImportDatabank" onclick="importFromDatabank()" class="hidden px-3.5 py-2 bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 rounded-xl text-xs font-bold transition-premium flex items-center gap-1 cursor-pointer">
                  Import from Databank
                </button>
                <button type="submit" id="btnSaveExp" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium flex items-center gap-1.5 cursor-pointer ml-auto shadow-xs">
                  Add Experiment
                </button>
              </div>
            </form>

            <!-- Experiments List Table -->
            <div class="border border-slate-200/80 rounded-2xl overflow-hidden bg-white shadow-xs">
              <table class="w-full text-left border-collapse text-sm">
                <thead>
                  <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase text-xs">
                    <th class="p-3 w-16 text-center">No.</th>
                    <th class="p-3">Title / Objective</th>
                    <th class="p-3 w-20 text-center">CO</th>
                    <th class="p-3 w-28 text-center">Actions</th>
                  </tr>
                </thead>
                <tbody id="manageExpsTableBody" class="divide-y divide-slate-100">
                  <tr>
                    <td colspan="4" class="p-6 text-center text-slate-500">No experiments set up yet.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Manage Tests Modal -->
      <div id="manageTestsModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden justify-center items-center p-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-xl md:max-w-4xl max-h-[85vh] flex flex-col overflow-hidden shadow-2xl">
          <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <div>
              <h3 class="text-base font-bold text-slate-900">Configure Model Tests Questions</h3>
              <p class="text-xs text-slate-500 mt-0.5 font-medium">Design the question paper scheme for Test 1 and Test 2.</p>
            </div>
            <button onclick="closeManageTestsModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer text-sm font-bold">
              ✕
            </button>
          </div>

          <form onsubmit="savePracticalTestQuestions(event)" class="flex-grow flex flex-col overflow-hidden">
            <div class="p-6 overflow-y-auto space-y-5 flex-grow">
              <div>
                <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Select Model Test</label>
                <select id="designTestName" onchange="renderTestQuestionsFields()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-900 focus:border-blue-500 outline-none cursor-pointer shadow-2xs">
                  <option value="Test 1">Model Test 1 (CO1 &amp; CO2)</option>
                  <option value="Test 2">Model Test 2 (CO3 &amp; CO4)</option>
                </select>
              </div>

              <div id="testQuestionsFieldsContainer" class="space-y-4">
                <!-- Inputs generated dynamically -->
              </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
              <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5 shadow-xs">
                Save Test Config
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Generate Lesson Planner Modal -->
      <div id="generatePlannerModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden justify-center items-center p-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
          <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900">Generate Practical Lesson Plan</h3>
            <button onclick="closeGeneratePlannerModal()" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer text-sm font-bold">
              ✕
            </button>
          </div>
          <form onsubmit="generatePlannerFromExperiments(event)" class="p-6 space-y-4">
            <div>
              <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Lab Batch Session Mode</label>
              <select id="genPlannerBatchMode" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-900 focus:border-blue-500 outline-none cursor-pointer shadow-2xs">
                <option value="combined">Combined / Full Class (1 entry per experiment)</option>
                <option value="separate">Split Batches / Batch 1 &amp; 2 (2 entries per experiment)</option>
              </select>
            </div>
            <div>
              <label class="text-xs font-bold text-slate-700 uppercase block mb-1.5">Allocated Hours per Session</label>
              <input type="number" id="genPlannerHours" value="3" min="1" max="10" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-900 focus:border-blue-500 outline-none shadow-2xs">
            </div>
            <div class="pt-2 flex justify-end gap-2.5">
              <button type="button" onclick="closeGeneratePlannerModal()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold transition-premium cursor-pointer shadow-2xs">Cancel</button>
              <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition-premium cursor-pointer shadow-xs">Generate</button>
            </div>
          </form>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML('beforeend', dynamicLabModalsHtml);

    // ==========================================
    // VIRTUAL LAB WORKSPACE JAVASCRIPT CONTROLLERS
    // ==========================================
    let labStudentsData = [];
    let labExperimentsData = [];
    let labTestsData = [];
    let activeLabModalTab = 'exp';
    let gradingStudentReg = null;

    function fetchPracticalEvaluations() {
      if (!currentSubjectId) return;
      const tbody = document.getElementById('labEvaluationsTableBody');
      tbody.innerHTML = `
        <tr>
          <td colspan="12" class="p-8 text-center text-slate-600 font-bold text-sm">
            <span class="animate-pulse">Loading student evaluation records...</span>
          </td>
        </tr>
      `;

      // Set print button href
      document.getElementById('printLabReportBtn').href = `/classroom/${currentSubjectId}/practical-report/print?type=register`;

      fetch(`/api/classroom/${currentSubjectId}/practical/evaluations`)
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          labStudentsData = res.students || [];
          labExperimentsData = res.experiments || [];
          labTestsData = res.tests || [];
          renderLabEvaluationsTable();
          calculateLabStatistics();
        } else {
          tbody.innerHTML = `<tr><td colspan="12" class="p-8 text-center text-red-400 font-bold text-sm">${res.message}</td></tr>`;
        }
      })
      .catch(err => {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="12" class="p-8 text-center text-red-400 font-bold text-sm">Error syncing lab evaluations.</td></tr>`;
      });
    }

    function renderLabEvaluationsTable() {
      const tbody = document.getElementById('labEvaluationsTableBody');
      tbody.innerHTML = '';

      if (labStudentsData.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="12" class="p-8 text-center text-slate-500 font-bold text-sm">
              No students enrolled in this classroom.
            </td>
          </tr>
        `;
        return;
      }

      labStudentsData.forEach(student => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-100 text-sm hover:bg-slate-50/70";
        tr.setAttribute('data-reg', student.reg_no);
        
        // Count graded experiments for this student
        let gradedCount = 0;
        if (student.experiments_marks) {
          gradedCount = Object.values(student.experiments_marks).filter(m => m !== null).length;
        }

        const expAverage = student.avg_lab_work ? parseFloat(student.avg_lab_work).toFixed(2) : '0.00';
        const t1Total = student.tests['Test 1'].total ? parseFloat(student.tests['Test 1'].total).toFixed(1) : '0.0';
        const t2Total = student.tests['Test 2'].total ? parseFloat(student.tests['Test 2'].total).toFixed(1) : '0.0';
        const testsAvg = student.tests.average ? parseFloat(student.tests.average).toFixed(2) : '0.00';
        const microProjVal = student.micro_project ? parseFloat(student.micro_project).toFixed(1) : '0.0';
        const attendanceVal = student.attendance_marks ? parseFloat(student.attendance_marks).toFixed(1) : '0.0';
        const internalsTotal = student.total_internal ? parseFloat(student.total_internal).toFixed(2) : '0.00';
        const boardMarks = student.board_exam_marks !== null ? parseFloat(student.board_exam_marks).toFixed(1) : 'N/A';

        tr.innerHTML = `
          <td class="p-3 font-mono font-bold text-slate-800 text-nowrap text-sm">${student.roll_no || '-'}</td>
          <td class="p-3 text-sm">
            <button onclick="openStudentLabModal('${student.reg_no}')" class="text-blue-400 hover:text-blue-300 font-bold cursor-pointer text-left block text-sm">
              ${student.name}
            </button>
            <span class="text-xs text-slate-500 block font-mono mt-0.5">${student.reg_no}</span>
          </td>
          <td class="p-3 text-center text-slate-600 font-bold font-mono text-sm">${gradedCount} / ${labExperimentsData.length}</td>
          <td class="p-3 text-center font-mono font-bold text-slate-800 text-sm">${expAverage}</td>
          <td class="p-3 text-center font-mono text-slate-455 text-sm">${t1Total}</td>
          <td class="p-3 text-center font-mono text-slate-455 text-sm">${t2Total}</td>
          <td class="p-3 text-center font-mono text-slate-800 font-bold text-sm">${testsAvg}</td>
          <td class="p-3 text-center font-mono text-slate-455 text-sm">${microProjVal}</td>
          <td class="p-3 text-center font-mono text-sm">
            <div class="inline-flex flex-col items-center">
              <span class="font-bold text-slate-350">${attendanceVal}</span>
              <span class="text-xs text-slate-500 font-bold">${student.attendance_percentage}%</span>
            </div>
          </td>
          <td class="p-3 text-center font-mono font-bold text-teal-700 font-bold text-base">${internalsTotal}</td>
          <td class="p-3 text-center font-mono font-bold text-blue-400 text-base">${boardMarks}</td>
          <td class="p-3 text-center">
            <button onclick="openStudentLabModal('${student.reg_no}')" class="px-3 py-1 bg-slate-800 hover:bg-slate-700 text-slate-800 hover:text-white rounded-lg text-xs font-bold transition-premium cursor-pointer border border-slate-700/50 flex items-center gap-1 mx-auto">
              <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg> Grade
            </button>
          </td>
        `;
        tbody.appendChild(tr);
      });
      filterLabGridByBatch();
    }

    function filterLabGridByBatch() {
      const filterVal = document.getElementById('labBatchFilterSelect').value;
      const tbody = document.getElementById('labEvaluationsTableBody');
      if (!tbody) return;
      const rows = Array.from(tbody.querySelectorAll('tr[data-reg]'));
      if (rows.length === 0) return;
      const total = rows.length;
      const mid = Math.ceil(total / 2);
      rows.forEach((row, idx) => {
        if (filterVal === 'combined') {
          row.classList.remove('hidden');
        } else if (filterVal === '1') {
          if (idx < mid) {
            row.classList.remove('hidden');
          } else {
            row.classList.add('hidden');
          }
        } else if (filterVal === '2') {
          if (idx >= mid) {
            row.classList.remove('hidden');
          } else {
            row.classList.add('hidden');
          }
        }
      });
    }

    function calculateLabStatistics() {
      if (labStudentsData.length === 0) return;
      
      let sumInternals = 0;
      let sumBoard = 0;
      let boardCount = 0;
      let passedCount = 0;

      labStudentsData.forEach(student => {
        sumInternals += parseFloat(student.total_internal || 0);
        if (student.board_exam_marks !== null) {
          sumBoard += parseFloat(student.board_exam_marks);
          boardCount++;

          const totalScore = parseFloat(student.total_internal || 0) + parseFloat(student.board_exam_marks);
          if (totalScore >= 50 && parseFloat(student.board_exam_marks) >= 20) {
            passedCount++;
          }
        }
      });

      const avgInternal = sumInternals / labStudentsData.length;
      const avgBoard = boardCount > 0 ? (sumBoard / boardCount) : 0;
      const passPercent = boardCount > 0 ? ((passedCount / boardCount) * 100) : 0;

      document.getElementById('statLabAvgInternal').innerText = `${avgInternal.toFixed(2)} / 75`;
      document.getElementById('statLabAvgBoard').innerText = boardCount > 0 ? `${avgBoard.toFixed(2)} / 50` : 'N/A';
      document.getElementById('statLabPassPercent').innerText = boardCount > 0 ? `${passPercent.toFixed(1)}%` : 'N/A';
      document.getElementById('statLabTotalExps').innerText = labExperimentsData.length;
    }

    // Modal tabs toggle
    function switchLabModalTab(tabId) {
      activeLabModalTab = tabId;
      ['exp', 'test', 'project', 'board'].forEach(t => {
        const el = document.getElementById('labModalTab_' + t);
        const btn = document.getElementById('labTabBtn_' + t);
        if (t === tabId) {
          el.classList.remove('hidden');
          btn.classList.add('border-blue-500', 'text-blue-400');
          btn.classList.remove('border-transparent', 'text-slate-600');
        } else {
          el.classList.add('hidden');
          btn.classList.remove('border-blue-500', 'text-blue-400');
          btn.classList.add('border-transparent', 'text-slate-400');
        }
      });
    }

    let tempStudentExpMarks = {};

    function openStudentLabModal(regNo) {
      gradingStudentReg = regNo;
      const student = labStudentsData.find(s => s.reg_no === regNo);
      if (!student) return;

      document.getElementById('labModalStudentName').innerText = student.name;
      document.getElementById('labModalStudentReg').innerText = `Register No: ${student.reg_no}`;
      document.getElementById('labModalStudentAttPct').innerText = `${student.attendance_percentage}%`;

      // Set input values
      document.getElementById('labScore_projectTopic').value = student.open_ended_project_topic || '';
      document.getElementById('labScore_projectMark').value = student.micro_project !== null ? student.micro_project : '';
      document.getElementById('labScore_attendanceMark').value = student.attendance_marks !== null ? student.attendance_marks : '';
      document.getElementById('labScore_boardExam').value = student.board_exam_marks !== null ? student.board_exam_marks : '';

      // Set test marks
      document.getElementById('labScore_t1_co1').value = student.tests['Test 1'].CO1 !== null ? student.tests['Test 1'].CO1 : '';
      document.getElementById('labScore_t1_co2').value = student.tests['Test 1'].CO2 !== null ? student.tests['Test 1'].CO2 : '';
      document.getElementById('labScore_t2_co3').value = student.tests['Test 2'].CO3 !== null ? student.tests['Test 2'].CO3 : '';
      document.getElementById('labScore_t2_co4').value = student.tests['Test 2'].CO4 !== null ? student.tests['Test 2'].CO4 : '';

      // Sync other sliders
      syncSlider('labScore_projectMark', 'labScore_projectMark_slider', 7.5);
      syncSlider('labScore_attendanceMark', 'labScore_attendanceMark_slider', 15);
      syncSlider('labScore_boardExam', 'labScore_boardExam_slider', 50);
      syncSlider('labScore_t1_co1', 'labScore_t1_co1_slider', 7.5);
      syncSlider('labScore_t1_co2', 'labScore_t1_co2_slider', 7.5);
      syncSlider('labScore_t2_co3', 'labScore_t2_co3_slider', 7.5);
      syncSlider('labScore_t2_co4', 'labScore_t2_co4_slider', 7.5);

      // Clone experiments marks locally
      tempStudentExpMarks = JSON.parse(JSON.stringify(student.experiments_marks || {}));

      // Populate experiment dropdown select
      const select = document.getElementById('labModalExpSelect');
      select.innerHTML = '';

      if (labExperimentsData.length === 0) {
        const opt = document.createElement('option');
        opt.value = "";
        opt.text = "-- No Experiments Configured --";
        select.appendChild(opt);
      } else {
        const optDefault = document.createElement('option');
        optDefault.value = "";
        optDefault.text = "-- Choose Experiment to Grade --";
        select.appendChild(optDefault);

        labExperimentsData.forEach(exp => {
          const opt = document.createElement('option');
          opt.value = exp.id;
          opt.text = `Exp ${exp.experiment_no}: ${exp.title}`;
          select.appendChild(opt);
        });
      }

      // Hide active container by default until select is chosen
      document.getElementById('activeExperimentContainer').classList.add('hidden');
      document.getElementById('noActiveExperimentMsg').classList.remove('hidden');

      switchLabModalTab('exp');
      calcLabModalScores();
      
      const modal = document.getElementById('studentLabModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function changeActiveExperiment() {
      const expId = document.getElementById('labModalExpSelect').value;
      const container = document.getElementById('activeExperimentContainer');
      const msg = document.getElementById('noActiveExperimentMsg');

      if (!expId) {
        container.classList.add('hidden');
        msg.classList.remove('hidden');
        return;
      }

      container.classList.remove('hidden');
      msg.classList.add('hidden');

      const exp = labExperimentsData.find(e => e.id == expId);
      if (!exp) return;

      document.getElementById('activeExpTitle').innerText = `Exp ${exp.experiment_no}: ${exp.title}`;
      document.getElementById('activeExpCo').innerText = exp.co_tag;

      const marks = tempStudentExpMarks[expId] || {};
      
      const setVal = (key, max) => {
        let val = marks[key];
        if (val === undefined || val === null) val = '';
        document.getElementById(`active_exp_${key}`).value = val;
        syncSlider(`active_exp_${key}`, `active_exp_${key}_slider`, max);
      };

      setVal('prerequisite', 7.5);
      setVal('execution', 10);
      setVal('output', 5);
      setVal('rough_record', 7.5);
      setVal('fair_record', 7.5);
    }

    function updateTempExpMark(key, val) {
      const expId = document.getElementById('labModalExpSelect').value;
      if (!expId) return;

      if (!tempStudentExpMarks[expId]) {
        tempStudentExpMarks[expId] = {};
      }
      tempStudentExpMarks[expId][key] = val;
      calcLabModalScores();
    }

    function closeStudentLabModal() {
      const modal = document.getElementById('studentLabModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function calcLabModalScores() {
      let totalGradedSum = 0;
      let gradedExpsCount = 0;

      labExperimentsData.forEach(exp => {
        const val = tempStudentExpMarks[exp.id] || {};
        const prereq = parseFloat(val.prerequisite);
        const exec = parseFloat(val.execution);
        const out = parseFloat(val.output);
        const rough = parseFloat(val.rough_record);
        const fair = parseFloat(val.fair_record);

        if (!isNaN(prereq) && !isNaN(exec) && !isNaN(out) && !isNaN(rough) && !isNaN(fair)) {
          totalGradedSum += (prereq + exec + out + rough + fair);
          gradedExpsCount++;
        }
      });

      const expAvg = gradedExpsCount > 0 ? (totalGradedSum / gradedExpsCount) : 0;
      document.getElementById('labModalLabelExp').innerText = expAvg.toFixed(2);

      // Model test
      const t1_co1 = parseFloat(document.getElementById('labScore_t1_co1').value) || 0;
      const t1_co2 = parseFloat(document.getElementById('labScore_t1_co2').value) || 0;
      const t2_co3 = parseFloat(document.getElementById('labScore_t2_co3').value) || 0;
      const t2_co4 = parseFloat(document.getElementById('labScore_t2_co4').value) || 0;

      const t1Total = t1_co1 + t1_co2;
      const t2Total = t2_co3 + t2_co4;
      const testsAvg = (t1Total + t2Total) / 2;

      document.getElementById('labModalT1Sum').innerText = `${t1Total.toFixed(1)} / 15`;
      document.getElementById('labModalT2Sum').innerText = `${t2Total.toFixed(1)} / 15`;
      document.getElementById('labModalLabelTest').innerText = testsAvg.toFixed(2);

      // Project & Attendance
      const projectMark = parseFloat(document.getElementById('labScore_projectMark').value) || 0;
      const attMark = parseFloat(document.getElementById('labScore_attendanceMark').value) || 0;

      const totalCA = expAvg + testsAvg + projectMark + attMark;
      document.getElementById('labModalLabelInternals').innerText = `${totalCA.toFixed(2)} / 75`;
    }

    function saveStudentLabEvaluation() {
      const regNo = gradingStudentReg;
      if (!regNo) return;

      const projectTopic = document.getElementById('labScore_projectTopic').value;
      const projectMark = document.getElementById('labScore_projectMark').value;
      const attMark = document.getElementById('labScore_attendanceMark').value;
      const boardExamMark = document.getElementById('labScore_boardExam').value;

      // Tests
      const tests = {
        'Test 1': {
          'CO1': document.getElementById('labScore_t1_co1').value,
          'CO2': document.getElementById('labScore_t1_co2').value
        },
        'Test 2': {
          'CO3': document.getElementById('labScore_t2_co3').value,
          'CO4': document.getElementById('labScore_t2_co4').value
        }
      };

      fetch(`/api/classroom/${currentSubjectId}/practical/evaluate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
          reg_no: regNo,
          open_ended_project_topic: projectTopic,
          micro_project: projectMark,
          attendance_marks: attMark,
          board_exam_marks: boardExamMark,
          tests,
          experiments: tempStudentExpMarks
        })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          alert('Evaluation saved successfully.');
          closeStudentLabModal();
          fetchPracticalEvaluations();
        } else {
          alert(res.message);
        }
      })
      .catch(() => alert('Failed to save student evaluation.'));
    }

    // Manage Experiments Modal Controllers
    function openManageExperimentsModal() {
      // Check if databank has previous data
      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/databank`)
      .then(res => res.json())
      .then(res => {
        const importBtn = document.getElementById('btnImportDatabank');
        if (res.status === 'SUCCESS' && res.has_data) {
          importBtn.classList.remove('hidden');
        } else {
          importBtn.classList.add('hidden');
        }
      });

      renderManageExperimentsList();

      const modal = document.getElementById('manageExperimentsModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeManageExperimentsModal() {
      const modal = document.getElementById('manageExperimentsModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function renderManageExperimentsList() {
      const tbody = document.getElementById('manageExpsTableBody');
      tbody.innerHTML = '';

      if (labExperimentsData.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="4" class="p-6 text-center text-slate-500 font-bold">
              No experiments set up yet. Create experiments using the form above.
            </td>
          </tr>
        `;
        return;
      }

      labExperimentsData.forEach(exp => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-100 hover:bg-slate-50/70";
        tr.innerHTML = `
          <td class="p-3 text-center font-bold text-slate-455 font-mono">${exp.experiment_no}</td>
          <td class="p-3 text-slate-200 font-medium text-sm whitespace-pre-wrap leading-relaxed">${exp.title}</td>
          <td class="p-3 text-center font-bold text-blue-400">${exp.co_tag}</td>
          <td class="p-3 text-center whitespace-nowrap space-x-2">
            <button onclick="editExperiment(${exp.id}, '${exp.experiment_no}', '${exp.title.replace(/'/g, "\\'")}', '${exp.co_tag}')" class="px-2.5 py-1 bg-slate-800 text-slate-300 hover:text-white rounded font-bold cursor-pointer">Edit</button>
            <button onclick="deleteExperiment(${exp.id})" class="px-2.5 py-1 bg-red-950/40 text-red-400 hover:text-red-300 rounded font-bold cursor-pointer border border-red-900/30">Delete</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function savePracticalExperiment(event) {
      event.preventDefault();
      const expId = document.getElementById('expEditId').value;
      const no = document.getElementById('expFormNo').value;
      const title = document.getElementById('expFormTitle').value;
      const co = document.getElementById('expFormCo').value;

      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/save`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ id: expId, experiment_no: no, title: title, co_tag: co })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          // Reset form
          document.getElementById('expEditId').value = '';
          document.getElementById('expFormNo').value = '';
          document.getElementById('expFormTitle').value = '';
          document.getElementById('btnSaveExp').innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg> Add Experiment';

          alert("Experiment successfully saved!");
          fetchPracticalEvaluations();
          setTimeout(() => renderManageExperimentsList(), 300);
        } else {
          alert(res.message);
        }
      })
      .catch(() => alert('Failed to save experiment.'));
    }

    function editExperiment(id, no, title, co) {
      document.getElementById('expEditId').value = id;
      document.getElementById('expFormNo').value = no;
      document.getElementById('expFormTitle').value = title;
      document.getElementById('expFormCo').value = co;
      document.getElementById('btnSaveExp').innerHTML = '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"/><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"/><path d="M7 3v4a1 1 0 0 0 1 1h7"/></svg> Update';
    }

    function deleteExperiment(id) {
      if (!confirm('Are you sure you want to delete this experiment? All graded marks for this experiment will be permanently deleted!')) return;

      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
        fetchPracticalEvaluations();
        setTimeout(() => renderManageExperimentsList(), 300);
      });
    }

    function importFromDatabank() {
      if (!confirm('This will import the standard list of experiments configured for this subject code. Existing student grades for existing matching experiment numbers will not be modified. Proceed?')) return;

      fetch(`/api/classroom/${currentSubjectId}/practical/experiments/import`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
        fetchPracticalEvaluations();
        setTimeout(() => renderManageExperimentsList(), 300);
      })
      .catch(() => alert('Import failed.'));
    }

    // Manage Tests modal
    function openManageTestsModal() {
      document.getElementById('designTestName').value = 'Test 1';
      renderTestQuestionsFields();

      const modal = document.getElementById('manageTestsModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeManageTestsModal() {
      const modal = document.getElementById('manageTestsModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function renderTestQuestionsFields() {
      const activeTestDesign = document.getElementById('designTestName').value;
      const container = document.getElementById('testQuestionsFieldsContainer');
      container.innerHTML = '';

      const test = labTestsData.find(t => t.test_name === activeTestDesign);
      const existingQ = test ? test.questions : {};

      const cos = activeTestDesign === 'Test 1' ? ['CO1', 'CO2'] : ['CO3', 'CO4'];

      cos.forEach(co => {
        const coQ = existingQ[co] || ['', ''];
        const card = document.createElement('div');
        card.className = "bg-white border border-slate-200 p-5 rounded-2xl space-y-3 shadow-2xs";
        card.innerHTML = `
          <h4 class="text-sm font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
            <span class="px-2.5 py-0.5 bg-blue-500/10 text-blue-400 rounded text-xs">${co}</span> Questions (Choice of 1 out of 2)
          </h4>
          <div class="space-y-3">
            <div>
              <label class="text-xs font-bold text-slate-400 uppercase block mb-1">Option A (7.5 Marks)</label>
              <textarea name="q_${co}_0" placeholder="Enter question description..." required rows="2" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 font-medium text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs resize-y">${coQ[0] || ''}</textarea>
            </div>
            <div>
              <label class="text-xs font-bold text-slate-400 uppercase block mb-1">Option B (7.5 Marks)</label>
              <textarea name="q_${co}_1" placeholder="Enter question description..." required rows="2" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 font-medium text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs resize-y">${coQ[1] || ''}</textarea>
            </div>
          </div>
        `;
        container.appendChild(card);
      });
    }

    function savePracticalTestQuestions(event) {
      event.preventDefault();
      const testName = document.getElementById('designTestName').value;
      const cos = testName === 'Test 1' ? ['CO1', 'CO2'] : ['CO3', 'CO4'];

      const questions = {};
      cos.forEach(co => {
        const q0 = document.querySelector(`textarea[name="q_${co}_0"]`).value;
        const q1 = document.querySelector(`textarea[name="q_${co}_1"]`).value;
        questions[co] = [q0, q1];
      });

      fetch(`/api/classroom/${currentSubjectId}/practical/tests/save`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ test_name: testName, questions })
      })
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          alert('Test config saved successfully.');
          fetch(`/api/classroom/${currentSubjectId}/practical/evaluations`)
          .then(r => r.json())
          .then(innerRes => {
            if (innerRes.status === 'SUCCESS') {
              labTestsData = innerRes.tests || [];
              closeManageTestsModal();
            }
          });
        } else {
          alert(res.message);
        }
      })
      .catch(() => alert('Failed to save test configuration.'));
    }

    // Auto-Generate planner
    function openGeneratePlannerModal() {
      const modal = document.getElementById('generatePlannerModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeGeneratePlannerModal() {
      const modal = document.getElementById('generatePlannerModal');
      modal.classList.remove('flex');
      modal.classList.add('hidden');
    }

    function generatePlannerFromExperiments(event) {
      event.preventDefault();
      const session_type = document.getElementById('genPlannerBatchMode').value;
      const allocated_hours = document.getElementById('genPlannerHours').value;

      fetch(`/api/classroom/${currentSubjectId}/practical/lesson-plans/generate`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ session_type, allocated_hours })
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
        if (res.status === 'SUCCESS') {
          closeGeneratePlannerModal();
          loadCourseDetails(currentSubjectId);
        }
      })
      .catch(() => alert('Failed to generate lesson planner.'));
    }

    // CO-PO Matrix
    function fetchPracticalCoPoMapping() {
      const tbody = document.getElementById('labCoPoMappingTbody');
      tbody.innerHTML = '<tr><td colspan="16" class="p-8 text-center text-slate-500 font-bold text-xs animate-pulse">Loading Articulation Matrix...</td></tr>';

      fetch(`/api/classroom/${currentSubjectId}/practical/copo-mapping`)
      .then(res => res.json())
      .then(res => {
        if (res.status === 'SUCCESS') {
          tbody.innerHTML = '';
          const matrix = res.mapping || {};
          const descriptions = {
            'CO1': 'Formulate solutions for laboratory tasks using theoretical principles and prerequisites.',
            'CO2': 'Conduct structured experiments, verify outputs, and log observations accurately.',
            'CO3': 'Analyze experimental results, troubleshoot errors, and draw logical conclusions.',
            'CO4': 'Demonstrate open-ended problem solving ability and technical documentation skills.'
          };

          ['CO1', 'CO2', 'CO3', 'CO4'].forEach(co => {
            const tr = document.createElement('tr');
            tr.className = "border-b border-slate-100 hover:bg-slate-50/70 text-slate-700 font-medium";
            
            let cells = `<td class="p-3 font-bold text-blue-400 whitespace-nowrap">${co}</td>`;
            cells += `<td class="p-3 text-slate-350 leading-relaxed font-bold text-xs">${descriptions[co]}</td>`;

            // PO1 to PO11 inputs
            for (let i = 1; i <= 11; i++) {
              const val = matrix[co] && matrix[co]['PO' + i] ? matrix[co]['PO' + i] : '';
              cells += `<td class="p-1"><input type="number" min="1" max="3" value="${val}" class="w-10 bg-white border border-slate-200 rounded-lg px-1.5 py-1 text-center font-bold text-emerald-700 focus:border-blue-500 outline-none text-sm shadow-2xs" data-co="${co}" data-target="PO${i}"></td>`;
            }

            // PSO1 to PSO3 inputs
            for (let i = 1; i <= 3; i++) {
              const val = matrix[co] && matrix[co]['PSO' + i] ? matrix[co]['PSO' + i] : '';
              cells += `<td class="p-1"><input type="number" min="1" max="3" value="${val}" class="w-10 bg-white border border-slate-200 rounded-lg px-1.5 py-1 text-center font-bold text-blue-700 focus:border-blue-500 outline-none text-sm shadow-2xs" data-co="${co}" data-target="PSO${i}"></td>`;
            }

            tr.innerHTML = cells;
            tbody.appendChild(tr);
          });
        }
      })
      .catch(() => {
        tbody.innerHTML = '<tr><td colspan="16" class="p-8 text-center text-red-400 font-bold text-xs">Failed to load articulation matrix.</td></tr>';
      });
    }

    function saveCoPoMappingMatrix() {
      const inputs = document.querySelectorAll('#labCoPoMappingTbody input[data-co]');
      const mapping = {
        'CO1': {}, 'CO2': {}, 'CO3': {}, 'CO4': {}
      };

      inputs.forEach(input => {
        const co = input.getAttribute('data-co');
        const target = input.getAttribute('data-target');
        const val = input.value ? parseInt(input.value) : null;
        if (val) {
          mapping[co][target] = val;
        }
      });

      fetch(`/api/classroom/${currentSubjectId}/practical/copo-mapping/save`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ mapping })
      })
      .then(res => res.json())
      .then(res => {
        alert(res.message);
      })
      .catch(() => alert('Failed to save mapping matrix.'));
    }

    // Live AI Status Indicator for Faculty
    document.addEventListener("DOMContentLoaded", () => {
      fetch('/api/system/ai-status')
        .then(res => res.json())
        .then(data => {
          const badge = document.getElementById('aiStatusBadge');
          if (badge && data.status === 'SUCCESS') {
            badge.classList.remove('hidden');
            if (data.ai_generation_enabled) {
              badge.innerHTML = `<span class="px-2.5 py-1.5 bg-emerald-950/40 text-emerald-400 border border-emerald-900/60 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping shrink-0"></span> AI Active</span>`;
            } else {
              badge.innerHTML = `<span class="px-2.5 py-1.5 bg-amber-950/40 text-amber-400 border border-amber-900/60 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm" title="Gemini AI is deactivated to save API credits. Lesson plans, descriptive questions, and MCQs are generated from local databases and question banks."><span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span> AI Offline (Local DB)</span>`;
            }
          }
        })
        .catch(err => console.error("Failed to load system AI status:", err));

      requestAnimationFrame(function() {
        document.body.classList.remove('sidebar-preload');
      });
    });
  
  window.initLucide = function() {
    if (window.lucide && typeof window.lucide.createIcons === "function") {
      window.lucide.createIcons();
    }
  };

  document.addEventListener("DOMContentLoaded", () => {
    window.initLucide();
  });
</script>

  @include('partials.support_desk_overlay')
</body>
</html>
