<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Academic & Professional Activities - Staff Console</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <style>
    html { font-size: 90%; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.3); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.3); border-radius: 99px; }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen p-4 md:p-6 lg:p-8">

  <div class="max-w-7xl mx-auto space-y-5">
    
    <!-- Top Header Bar -->
    <div class="bg-slate-900/80 border border-slate-800 p-4 md:p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4 shadow-xl">
      <div class="flex items-center gap-3">
        @php
          $role = Session::get('userRole');
          $backUrl = match($role) {
              'Super_Admin' => '/dashboard/superadmin',
              'Admin' => '/dashboard/admin',
              'Principal' => '/dashboard/principal',
              'HOD' => '/dashboard/hod',
              'Gen_Dept_Coordinator_Aided' => '/dashboard/general-coordinator-aided',
              'Gen_Dept_Coordinator_Self_Finance' => '/dashboard/general-coordinator-sf',
              'Lecturer' => '/dashboard/lecturer',
              'Demonstrator' => '/dashboard/demonstrator',
              'Trade_Instructor' => '/dashboard/tradeinstructor',
              'Workshop_Superintendent' => '/dashboard/workshop',
              default => '/'
          };
        @endphp
        <a href="{{ $backUrl }}" class="p-2 bg-slate-950 border border-slate-800 hover:bg-slate-800 text-slate-300 hover:text-white rounded-xl transition cursor-pointer flex items-center justify-center">
          <span class="material-symbols-rounded text-xl">arrow_back</span>
        </a>
        <div>
          <h1 class="text-xl font-extrabold text-white tracking-tight flex items-center gap-2">
            <span class="material-symbols-rounded text-indigo-400 text-2xl">school</span>
            Academic &amp; Professional Activities
          </h1>
          <p class="text-xs text-slate-400 font-medium mt-0.5">Record professional trainings, publications, guided projects, and syllabus gaps</p>
        </div>
      </div>
      
      <!-- Academic Year Filter -->
      <form method="GET" action="/staff/professional-activities" class="flex items-center gap-2">
        <label class="text-xs font-bold text-slate-400 uppercase tracking-wider hidden sm:inline">Academic Year:</label>
        <select name="academic_year" onchange="this.form.submit()" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 text-white outline-none text-xs font-bold cursor-pointer focus:border-indigo-500">
          @php
            $sYear = 2015;
            $eYear = date('Y') + 10;
          @endphp
          @for($y = $sYear; $y <= $eYear; $y++)
            @php $yr = $y . '-' . ($y + 1); @endphp
            <option value="{{ $yr }}" {{ $yr === $academicYear ? 'selected' : '' }}>AY {{ $yr }}</option>
          @endfor
        </select>
      </form>
    </div>

    <!-- Alert Status -->
    @if(session('success'))
      <div class="p-3.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-xl text-xs font-bold flex items-center gap-2">
        <span class="material-symbols-rounded text-base">check_circle</span>
        {{ session('success') }}
      </div>
    @endif

    <!-- Main Desktop Layout Split -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
      
      <!-- Left Panel: Add New Record Form (5 Columns on Desktop) -->
      <div class="lg:col-span-5 bg-slate-900/60 border border-slate-800 rounded-2xl p-5 space-y-4 shadow-lg">
        <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
          <h2 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-2">
            <span class="material-symbols-rounded text-emerald-400 text-base">add_circle</span>
            Add New Activity Record
          </h2>
          <span class="text-[11px] text-slate-400 font-mono">AY {{ $academicYear }}</span>
        </div>
        
        <form method="POST" action="/staff/professional-activities/save" class="space-y-3.5">
          @csrf
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Start Year</label>
              <input type="number" id="ayStartYear" oninput="updateAcademicYearValue()" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 text-white outline-none text-xs font-bold focus:border-indigo-500" min="2010" max="2100" value="{{ explode('-', $academicYear)[0] }}">
            </div>
            <div>
              <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">AY Reference</label>
              <input type="text" name="academic_year" id="ayFullText" readonly class="w-full bg-slate-950/60 border border-slate-850 rounded-xl px-3 py-1.5 text-slate-400 outline-none text-xs font-bold" value="{{ $academicYear }}">
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Activity Category</label>
            <select name="activity_type" id="activityTypeSelector" onchange="toggleFormFields(this.value)" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-xs font-bold cursor-pointer focus:border-indigo-500">
              <option value="fdp_attended">FDP Attended</option>
              <option value="workshop_attended">Workshop Attended</option>
              <option value="course_attended">Course / Certification Attended</option>
              <option value="gap_in_syllabus">Curricular Gap Identified in Syllabus</option>
              <option value="project_guided">Student Project Guided</option>
              <option value="seminar_guided">Seminar Guided</option>
              <option value="publication">Research Paper / Journal Publication</option>
              <option value="book_published">Book Published (with ISBN)</option>
            </select>
          </div>

          <!-- Dynamic Fields Container -->
          <div id="dynamicFieldsContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
            <!-- Rendered via JS -->
          </div>

          <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold rounded-xl transition text-xs cursor-pointer border-none shadow-md flex items-center justify-center gap-1.5 mt-2">
            <span class="material-symbols-rounded text-sm">save</span>
            <span>Save Record</span>
          </button>
        </form>
      </div>

      <!-- Right Panel: Recorded Entries (7 Columns on Desktop) -->
      <div class="lg:col-span-7 bg-slate-900/60 border border-slate-800 rounded-2xl p-5 space-y-4 shadow-lg min-h-[420px]">
        <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
          <h2 class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-400 text-base">format_list_bulleted</span>
            Recorded Entries for {{ $academicYear }}
          </h2>
          <span class="text-xs text-slate-400 font-medium">Total: {{ count($activities) }} items</span>
        </div>
        
        @if($activities->isEmpty())
          <div class="p-12 text-center text-slate-500 text-xs font-medium space-y-2">
            <span class="material-symbols-rounded text-4xl block text-slate-600">assignment_turned_in</span>
            <p>No professional records found for Academic Year {{ $academicYear }}.</p>
            <p class="text-[11px] text-slate-600">Use the form on the left to add your FDPs, publications, or guided projects.</p>
          </div>
        @else
          <div class="max-h-[calc(100vh-250px)] overflow-y-auto pr-1 space-y-3 custom-scrollbar">
            @foreach($activities as $act)
              <div class="p-3.5 bg-slate-950/60 border border-slate-800/80 rounded-xl hover:border-slate-700 transition flex items-start justify-between gap-3 group">
                <div class="space-y-1.5 min-w-0">
                  <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 rounded-md text-[10px] font-black uppercase tracking-wider">
                      {{ str_replace('_', ' ', $act->activity_type) }}
                    </span>
                  </div>
                  
                  <!-- FDP / Workshop details -->
                  @if(in_array($act->activity_type, ['fdp_attended', 'workshop_attended', 'course_attended']))
                    <h3 class="text-xs md:text-sm font-bold text-white leading-tight truncate">{{ $act->details['title'] ?? 'Untitled Training' }}</h3>
                    <div class="flex flex-wrap items-center gap-3 text-[11px] text-slate-400">
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
                    <h3 class="text-xs md:text-sm font-bold text-white leading-tight truncate">{{ $act->details['subject'] ?? 'Subject' }}</h3>
                    <p class="text-[11px] text-slate-300 leading-snug"><strong>Identified Gap:</strong> {{ $act->details['gap_details'] ?? '-' }}</p>
                    <p class="text-[11px] text-slate-400 leading-snug"><strong>Action Taken:</strong> {{ $act->details['action_taken'] ?? '-' }}</p>
                  @endif

                  <!-- Project/Seminar Guided -->
                  @if(in_array($act->activity_type, ['project_guided', 'seminar_guided']))
                    <h3 class="text-xs md:text-sm font-bold text-white leading-tight truncate">{{ $act->details['title'] ?? 'Title' }}</h3>
                    <div class="flex flex-wrap items-center gap-3 text-[11px] text-slate-400">
                      <span><strong>Students:</strong> {{ $act->details['students'] ?? '-' }}</span>
                      @if(!empty($act->details['batch']))
                        <span>•</span>
                        <span><strong>Batch:</strong> {{ $act->details['batch'] }}</span>
                      @endif
                    </div>
                  @endif

                  <!-- Publication / Book -->
                  @if($act->activity_type === 'publication')
                    <h3 class="text-xs md:text-sm font-bold text-white leading-tight truncate">{{ $act->details['title'] ?? 'Paper Title' }}</h3>
                    <div class="flex flex-wrap items-center gap-3 text-[11px] text-slate-400">
                      <span><strong>Journal:</strong> {{ $act->details['journal'] ?? '-' }}</span>
                      <span>•</span>
                      <span><strong>Year:</strong> {{ $act->details['year'] ?? '-' }}</span>
                    </div>
                  @endif
                  @if($act->activity_type === 'book_published')
                    <h3 class="text-xs md:text-sm font-bold text-white leading-tight truncate">{{ $act->details['title'] ?? 'Book Title' }}</h3>
                    <div class="flex flex-wrap items-center gap-3 text-[11px] text-slate-400">
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
                  <button type="submit" onclick="return confirm('Delete this record?')" class="p-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-lg transition text-[11px] font-bold cursor-pointer flex items-center gap-1" title="Delete record">
                    <span class="material-symbols-rounded text-sm">delete</span>
                  </button>
                </form>
              </div>
            @endforeach
          </div>
        @endif
      </div>

    </div>

  </div>

  <script>
    const schemas = {
      fdp_attended: [
        { label: 'Title of FDP', name: 'title', type: 'text', placeholder: 'e.g. Advanced Laravel', fullWidth: true },
        { label: 'Duration (days)', name: 'duration', type: 'number', placeholder: 'e.g. 5' },
        { label: 'Date From', name: 'date', type: 'text', placeholder: 'YYYY-MM-DD' },
        { label: 'Venue / Institution', name: 'venue', type: 'text', placeholder: 'e.g. Carmel Polytechnic', fullWidth: true }
      ],
      workshop_attended: [
        { label: 'Title of Workshop', name: 'title', type: 'text', placeholder: 'e.g. IoT Systems & Networking', fullWidth: true },
        { label: 'Duration (days)', name: 'duration', type: 'number', placeholder: 'e.g. 2' },
        { label: 'Date From', name: 'date', type: 'text', placeholder: 'YYYY-MM-DD' },
        { label: 'Venue / Institution', name: 'venue', type: 'text', placeholder: 'e.g. Govt Polytechnic College', fullWidth: true }
      ],
      course_attended: [
        { label: 'Course Title', name: 'title', type: 'text', placeholder: 'e.g. NPTEL Data Structures', fullWidth: true },
        { label: 'Duration', name: 'duration', type: 'text', placeholder: 'e.g. 8 weeks' },
        { label: 'Platform / Inst.', name: 'venue', type: 'text', placeholder: 'e.g. NPTEL / Swayam' }
      ],
      gap_in_syllabus: [
        { label: 'Subject Name & Code', name: 'subject', type: 'text', placeholder: 'e.g. Computer Networks (CN-302)', fullWidth: true },
        { label: 'Identified Curricular Gap Details', name: 'gap_details', type: 'textarea', placeholder: 'Identify details where syllabus falls short', fullWidth: true },
        { label: 'Action Taken / Bridge Plan', name: 'action_taken', type: 'text', placeholder: 'e.g. Conducted a 3-hour seminar on IPv6', fullWidth: true }
      ],
      project_guided: [
        { label: 'Project Title', name: 'title', type: 'text', placeholder: 'e.g. Smart Attendance System', fullWidth: true },
        { label: 'Batch / Year', name: 'batch', type: 'text', placeholder: 'e.g. 2023-2026 Batch' },
        { label: 'Student Names', name: 'students', type: 'text', placeholder: 'e.g. Arjun, Vishnu, Rahul' }
      ],
      seminar_guided: [
        { label: 'Seminar Topic', name: 'title', type: 'text', placeholder: 'e.g. Introduction to Quantum Computing', fullWidth: true },
        { label: 'Student Name', name: 'students', type: 'text', placeholder: 'e.g. Anjali Nair' },
        { label: 'Date Presented', name: 'date', type: 'text', placeholder: 'YYYY-MM-DD' }
      ],
      publication: [
        { label: 'Paper / Journal Title', name: 'title', type: 'text', placeholder: 'e.g. AI-driven grading engines', fullWidth: true },
        { label: 'Journal / Conference Name', name: 'journal', type: 'text', placeholder: 'e.g. Int. Journal of Engineering' },
        { label: 'Year', name: 'year', type: 'number', placeholder: 'e.g. 2026' }
      ],
      book_published: [
        { label: 'Book Title', name: 'title', type: 'text', placeholder: 'e.g. Basic Electronics & Circuits', fullWidth: true },
        { label: 'Publisher', name: 'publisher', type: 'text', placeholder: 'e.g. Pearson India' },
        { label: 'ISBN Number', name: 'isbn', type: 'text', placeholder: 'e.g. 978-3-16-148410-0' },
        { label: 'Year', name: 'year', type: 'number', placeholder: 'e.g. 2025' }
      ]
    };

    function toggleFormFields(type) {
      const container = document.getElementById('dynamicFieldsContainer');
      container.innerHTML = '';
      
      const fields = schemas[type] || [];
      fields.forEach(f => {
        const div = document.createElement('div');
        div.className = f.fullWidth ? 'col-span-1 sm:col-span-2 space-y-1' : 'space-y-1';
        
        const label = document.createElement('label');
        label.className = 'block text-[11px] font-bold text-slate-400 uppercase tracking-wider';
        label.innerText = f.label;
        div.appendChild(label);
        
        if (f.type === 'textarea') {
          const textarea = document.createElement('textarea');
          textarea.name = `details[${f.name}]`;
          textarea.placeholder = f.placeholder;
          textarea.rows = 2;
          textarea.className = 'w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 text-white outline-none text-xs focus:border-indigo-500';
          textarea.required = true;
          div.appendChild(textarea);
        } else {
          const input = document.createElement('input');
          input.type = f.type;
          input.name = `details[${f.name}]`;
          input.placeholder = f.placeholder;
          input.className = 'w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-1.5 text-white outline-none text-xs focus:border-indigo-500';
          input.required = true;
          div.appendChild(input);
        }
        
        container.appendChild(div);
      });
    }

    // Initialize with first item
    toggleFormFields('fdp_attended');

    function updateAcademicYearValue() {
      const year = parseInt(document.getElementById('ayStartYear').value);
      if (year) {
        document.getElementById('ayFullText').value = `${year}-${year + 1}`;
      }
    }
  </script>

</body>
</html>
