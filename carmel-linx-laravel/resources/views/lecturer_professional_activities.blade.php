<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - Academic &amp; Professional Activities</title>
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
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
  </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen font-sans antialiased sidebar-preload">

  <!-- Master Application Shell -->
  <div class="flex min-h-screen bg-[#FAFAFB]">

    <!-- Global Sidebar Navigation Component -->
    @php
      $role = Session::get('userRole');
      $sidebarRole = match($role) {
          'Super_Admin' => 'super_admin',
          'Admin' => 'admin',
          'Principal' => 'principal',
          'HOD' => 'hod',
          'Gen_Dept_Coordinator_Aided' => 'coordinator_aided',
          'Gen_Dept_Coordinator_Self_Finance' => 'coordinator_sf',
          'Demonstrator' => 'demonstrator',
          'Trade_Instructor' => 'trade_instructor',
          'Workshop_Superintendent' => 'workshop_superintendent',
          default => 'faculty'
      };
    @endphp
    <x-layout.sidebar :role="$sidebarRole" active="prof_activities" />

    <!-- Main Viewport Container -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#FAFAFB]">
      
      <!-- Global Topbar Header Component -->
      <x-layout.topbar title="Academic & Professional Activities" subtitle="Record professional trainings, publications, guided projects, and syllabus gaps." />

      <!-- Scrollable Main Workspace -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
        
        <!-- Header & Academic Year Filter Card -->
        <div class="bg-white border border-slate-200/80 p-5 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xs">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200/80 shrink-0">
              <x-ui.icon name="award" class="w-5 h-5 text-blue-600" />
            </div>
            <div>
              <h3 class="text-base sm:text-lg font-bold text-slate-900">Faculty Portfolio &amp; Activity Log</h3>
              <p class="text-sm text-slate-500 mt-0.5">Continuous professional development, research, and curricular activities.</p>
            </div>
          </div>

          <!-- Academic Year Filter -->
          <form method="GET" action="/staff/professional-activities" class="flex items-center gap-2.5 self-stretch sm:self-auto">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:inline">Academic Year:</label>
            <div class="relative flex-1 sm:flex-none">
              <select name="academic_year" onchange="this.form.submit()" class="w-full sm:w-auto bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 outline-none text-sm font-semibold cursor-pointer focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs transition-colors">
                @php
                  $sYear = 2015;
                  $eYear = date('Y') + 10;
                @endphp
                @for($y = $sYear; $y <= $eYear; $y++)
                  @php $yr = $y . '-' . ($y + 1); @endphp
                  <option value="{{ $yr }}" {{ $yr === $academicYear ? 'selected' : '' }}>Academic Year {{ $yr }}</option>
                @endfor
              </select>
            </div>
          </form>
        </div>

        <!-- Flash Message -->
        @if(session('success'))
          <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-semibold flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                <x-ui.icon name="check_circle" class="w-4 h-4" />
              </div>
              <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 p-1 rounded-lg text-sm font-bold cursor-pointer">✕</button>
          </div>
        @endif

        <!-- Main Workspace Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          
          <!-- Left Panel: Add New Record Form (5 Columns on Desktop) -->
          <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 space-y-5 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
              <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <x-ui.icon name="add_circle" class="w-4 h-4 text-blue-600" />
                <span>Add Activity Record</span>
              </h4>
              <span class="text-xs font-mono font-bold bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-lg">AY {{ $academicYear }}</span>
            </div>
            
            <form method="POST" action="/staff/professional-activities/save" class="space-y-4">
              @csrf
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Start Year</label>
                  <input type="number" id="ayStartYear" oninput="updateAcademicYearValue()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 outline-none text-sm font-semibold focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs" min="2010" max="2100" value="{{ explode('-', $academicYear)[0] }}">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">AY Reference</label>
                  <input type="text" name="academic_year" id="ayFullText" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-slate-600 outline-none text-sm font-mono font-bold shadow-2xs" value="{{ $academicYear }}">
                </div>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Activity Category</label>
                <select name="activity_type" id="activityTypeSelector" onchange="toggleFormFields(this.value)" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 outline-none text-sm font-semibold cursor-pointer focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs">
                  <option value="fdp_attended">Faculty Development Programme (FDP)</option>
                  <option value="workshop_attended">Workshop Attended</option>
                  <option value="course_attended">Course / MOOC / NPTEL Certification</option>
                  <option value="gap_in_syllabus">Curricular Gap Identified in Syllabus</option>
                  <option value="project_guided">Student Project Guided</option>
                  <option value="seminar_guided">Seminar Guided</option>
                  <option value="publication">Research Paper / Journal Publication</option>
                  <option value="book_published">Book Published (with ISBN)</option>
                </select>
              </div>

              <!-- Dynamic Fields Container -->
              <div id="dynamicFieldsContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                <!-- Rendered via JS -->
              </div>

              <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm cursor-pointer shadow-xs flex items-center justify-center gap-2 mt-2">
                <x-ui.icon name="save" class="w-4 h-4" />
                <span>Save Record</span>
              </button>
            </form>
          </div>

          <!-- Right Panel: Recorded Entries (7 Columns on Desktop) -->
          <div class="lg:col-span-7 bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 space-y-5 shadow-xs min-h-[460px] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3.5">
              <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <x-ui.icon name="list_alt" class="w-4 h-4 text-slate-600" />
                <span>Recorded Entries for {{ $academicYear }}</span>
              </h4>
              <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">{{ count($activities) }} entries</span>
            </div>
            
            @if($activities->isEmpty())
              <div class="p-12 text-center text-slate-500 text-sm font-medium space-y-3 flex-1 flex flex-col items-center justify-center">
                <div class="w-12 h-12 bg-slate-50 rounded-2xl border border-slate-200 flex items-center justify-center text-slate-400">
                  <x-ui.icon name="assignment_turned_in" class="w-6 h-6" />
                </div>
                <div>
                  <p class="font-bold text-slate-800">No professional records found.</p>
                  <p class="text-xs text-slate-400 mt-1 max-w-sm">Use the form on the left to record your FDPs, publications, courses, or guided projects for AY {{ $academicYear }}.</p>
                </div>
              </div>
            @else
              <div class="max-h-[calc(100vh-280px)] overflow-y-auto pr-1.5 space-y-3 custom-scrollbar flex-1">
                @foreach($activities as $act)
                  <div class="p-4 bg-slate-50/70 border border-slate-200/80 rounded-xl hover:border-slate-300 hover:bg-white transition-all flex items-start justify-between gap-3 shadow-2xs group">
                    <div class="space-y-2 min-w-0 flex-1">
                      <div class="flex items-center gap-2">
                        @php
                          $badgeStyle = match($act->activity_type) {
                            'fdp_attended' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'workshop_attended' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'course_attended' => 'bg-teal-50 text-teal-700 border-teal-200',
                            'gap_in_syllabus' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'project_guided', 'seminar_guided' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'publication', 'book_published' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                          };
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold uppercase tracking-wider border {{ $badgeStyle }}">
                          {{ str_replace('_', ' ', $act->activity_type) }}
                        </span>
                      </div>
                      
                      <!-- FDP / Workshop details -->
                      @if(in_array($act->activity_type, ['fdp_attended', 'workshop_attended', 'course_attended']))
                        <h5 class="text-sm font-bold text-slate-900 leading-snug break-words">{{ $act->details['title'] ?? 'Untitled Training' }}</h5>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 font-medium">
                          <span><strong>Duration:</strong> {{ $act->details['duration'] ?? '-' }}</span>
                          <span>•</span>
                          <span><strong>Venue:</strong> {{ $act->details['venue'] ?? '-' }}</span>
                          @if(!empty($act->details['date']))
                            <span>•</span>
                            <span><strong>Date:</strong> {{ $act->details['date'] }}</span>
                          @endif
                        </div>
                      @endif

                      <!-- Syllabus gaps details -->
                      @if($act->activity_type === 'gap_in_syllabus')
                        <h5 class="text-sm font-bold text-slate-900 leading-snug break-words">{{ $act->details['subject'] ?? 'Subject' }}</h5>
                        <p class="text-xs text-slate-700 leading-relaxed"><strong>Identified Gap:</strong> {{ $act->details['gap_details'] ?? '-' }}</p>
                        <p class="text-xs text-slate-500 leading-relaxed"><strong>Action Taken:</strong> {{ $act->details['action_taken'] ?? '-' }}</p>
                      @endif

                      <!-- Project/Seminar Guided -->
                      @if(in_array($act->activity_type, ['project_guided', 'seminar_guided']))
                        <h5 class="text-sm font-bold text-slate-900 leading-snug break-words">{{ $act->details['title'] ?? 'Title' }}</h5>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 font-medium">
                          <span><strong>Students:</strong> {{ $act->details['students'] ?? '-' }}</span>
                          @if(!empty($act->details['batch']))
                            <span>•</span>
                            <span><strong>Batch:</strong> {{ $act->details['batch'] }}</span>
                          @endif
                        </div>
                      @endif

                      <!-- Publication / Book -->
                      @if($act->activity_type === 'publication')
                        <h5 class="text-sm font-bold text-slate-900 leading-snug break-words">{{ $act->details['title'] ?? 'Paper Title' }}</h5>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 font-medium">
                          <span><strong>Journal:</strong> {{ $act->details['journal'] ?? '-' }}</span>
                          <span>•</span>
                          <span><strong>Year:</strong> {{ $act->details['year'] ?? '-' }}</span>
                        </div>
                      @endif
                      @if($act->activity_type === 'book_published')
                        <h5 class="text-sm font-bold text-slate-900 leading-snug break-words">{{ $act->details['title'] ?? 'Book Title' }}</h5>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 font-medium">
                          <span><strong>Publisher:</strong> {{ $act->details['publisher'] ?? '-' }}</span>
                          <span>•</span>
                          <span><strong>ISBN:</strong> {{ $act->details['isbn'] ?? '-' }}</span>
                          <span>•</span>
                          <span><strong>Year:</strong> {{ $act->details['year'] ?? '-' }}</span>
                        </div>
                      @endif
                    </div>
                    
                    <form method="POST" action="/staff/professional-activities/delete/{{ $act->id }}" class="shrink-0">
                      @csrf
                      <button type="submit" onclick="return confirm('Delete this record?')" class="p-2 bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-xl transition text-xs font-bold cursor-pointer flex items-center gap-1 shadow-2xs" title="Delete record">
                        <x-ui.icon name="delete" class="w-4 h-4" />
                      </button>
                    </form>
                  </div>
                @endforeach
              </div>
            @endif
          </div>

        </div>

      </main>
    </div>
  </div>

  <script>
    const schemas = {
      fdp_attended: [
        { label: 'Title of FDP', name: 'title', type: 'text', placeholder: 'e.g. Advanced Laravel Framework', fullWidth: true },
        { label: 'Duration (days)', name: 'duration', type: 'number', placeholder: 'e.g. 5' },
        { label: 'Date From', name: 'date', type: 'text', placeholder: 'YYYY-MM-DD' },
        { label: 'Venue / Institution', name: 'venue', type: 'text', placeholder: 'e.g. Carmel Polytechnic College', fullWidth: true }
      ],
      workshop_attended: [
        { label: 'Title of Workshop', name: 'title', type: 'text', placeholder: 'e.g. IoT Systems & Embedded Networks', fullWidth: true },
        { label: 'Duration (days)', name: 'duration', type: 'number', placeholder: 'e.g. 2' },
        { label: 'Date From', name: 'date', type: 'text', placeholder: 'YYYY-MM-DD' },
        { label: 'Venue / Institution', name: 'venue', type: 'text', placeholder: 'e.g. Govt Polytechnic College', fullWidth: true }
      ],
      course_attended: [
        { label: 'Course Title', name: 'title', type: 'text', placeholder: 'e.g. NPTEL Data Structures & Algorithms', fullWidth: true },
        { label: 'Duration', name: 'duration', type: 'text', placeholder: 'e.g. 8 weeks' },
        { label: 'Platform / Inst.', name: 'venue', type: 'text', placeholder: 'e.g. NPTEL / SWAYAM / Coursera' }
      ],
      gap_in_syllabus: [
        { label: 'Subject Name & Code', name: 'subject', type: 'text', placeholder: 'e.g. Computer Networks (CN-302)', fullWidth: true },
        { label: 'Identified Curricular Gap Details', name: 'gap_details', type: 'textarea', placeholder: 'Identify topics where syllabus falls short of industrial standards', fullWidth: true },
        { label: 'Action Taken / Bridge Plan', name: 'action_taken', type: 'text', placeholder: 'e.g. Conducted a 3-hour guest lecture on IPv6', fullWidth: true }
      ],
      project_guided: [
        { label: 'Project Title', name: 'title', type: 'text', placeholder: 'e.g. Smart Autonomous Attendance System', fullWidth: true },
        { label: 'Batch / Year', name: 'batch', type: 'text', placeholder: 'e.g. 2023-2026 Batch' },
        { label: 'Student Names', name: 'students', type: 'text', placeholder: 'e.g. Arjun, Vishnu, Rahul' }
      ],
      seminar_guided: [
        { label: 'Seminar Topic', name: 'title', type: 'text', placeholder: 'e.g. Introduction to Quantum Cryptography', fullWidth: true },
        { label: 'Student Name', name: 'students', type: 'text', placeholder: 'e.g. Anjali Nair' },
        { label: 'Date Presented', name: 'date', type: 'text', placeholder: 'YYYY-MM-DD' }
      ],
      publication: [
        { label: 'Paper / Journal Title', name: 'title', type: 'text', placeholder: 'e.g. AI-driven Automated Grading Engines', fullWidth: true },
        { label: 'Journal / Conference Name', name: 'journal', type: 'text', placeholder: 'e.g. IEEE Int. Journal of Engineering' },
        { label: 'Year', name: 'year', type: 'number', placeholder: 'e.g. 2026' }
      ],
      book_published: [
        { label: 'Book Title', name: 'title', type: 'text', placeholder: 'e.g. Basic Electronics & Microcontrollers', fullWidth: true },
        { label: 'Publisher', name: 'publisher', type: 'text', placeholder: 'e.g. Pearson India' },
        { label: 'ISBN Number', name: 'isbn', type: 'text', placeholder: 'e.g. 978-3-16-148410-0' },
        { label: 'Year', name: 'year', type: 'number', placeholder: 'e.g. 2025' }
      ]
    };

    function toggleFormFields(type) {
      const container = document.getElementById('dynamicFieldsContainer');
      if (!container) return;
      container.innerHTML = '';
      
      const fields = schemas[type] || [];
      fields.forEach(f => {
        const div = document.createElement('div');
        div.className = f.fullWidth ? 'col-span-1 sm:col-span-2 space-y-1.5' : 'space-y-1.5';
        
        const label = document.createElement('label');
        label.className = 'block text-xs font-bold text-slate-700 uppercase tracking-wider';
        label.innerText = f.label;
        div.appendChild(label);
        
        if (f.type === 'textarea') {
          const textarea = document.createElement('textarea');
          textarea.name = `details[${f.name}]`;
          textarea.placeholder = f.placeholder;
          textarea.rows = 2;
          textarea.className = 'w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 text-sm font-medium outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs resize-y';
          textarea.required = true;
          div.appendChild(textarea);
        } else {
          const input = document.createElement('input');
          input.type = f.type;
          input.name = `details[${f.name}]`;
          input.placeholder = f.placeholder;
          input.className = 'w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-slate-900 text-sm font-medium outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs';
          input.required = true;
          div.appendChild(input);
        }
        
        container.appendChild(div);
      });
    }

    function updateAcademicYearValue() {
      const startYearInput = document.getElementById('ayStartYear');
      const fullTextInput = document.getElementById('ayFullText');
      if (!startYearInput || !fullTextInput) return;
      const startYear = parseInt(startYearInput.value);
      if (!isNaN(startYear) && startYear >= 2010 && startYear <= 2100) {
        fullTextInput.value = `${startYear}-${startYear + 1}`;
      }
    }

    // Initialize default fields on page load
    document.addEventListener('DOMContentLoaded', () => {
      const selector = document.getElementById('activityTypeSelector');
      if (selector) {
        toggleFormFields(selector.value);
      }
    });
  </script>
</body>
</html>
