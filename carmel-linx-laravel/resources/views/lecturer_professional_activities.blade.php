<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Academic & Professional Activities - Lecturer Console</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen p-6 md:p-12">

  <div class="max-w-6xl mx-auto space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-6">
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
        <a href="{{ $backUrl }}" class="p-2.5 bg-slate-950 border border-slate-850 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl transition cursor-pointer">
          <span class="material-symbols-rounded text-lg">arrow_back</span>
        </a>
        <div>
          <h1 class="text-2xl font-black text-white tracking-tight">Academic & Professional Activities</h1>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5">Record professional training, publications, guided projects and syllabus gaps</p>
        </div>
      </div>
      
      <!-- Academic Year Filter -->
      <form method="GET" action="/staff/professional-activities" class="flex items-center gap-2">
        <select name="academic_year" onchange="this.form.submit()" class="bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-white outline-none text-sm font-extrabold cursor-pointer">
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

    <!-- Alert status -->
    @if(session('success'))
      <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-2xl text-sm font-bold">
        {{ session('success') }}
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <!-- Add Entry Panel -->
      <div class="bg-slate-950 border border-slate-850 rounded-3xl p-6 space-y-6">
        <h2 class="text-sm font-black text-white uppercase tracking-wider border-b border-slate-900 pb-2">Add New Record</h2>
        
        <form method="POST" action="/staff/professional-activities/save" class="space-y-4">
          @csrf
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Start Year</label>
              <select id="ayStartYear" onchange="updateAcademicYearValue()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm font-bold cursor-pointer">
                @php
                  $startYr = 2015;
                  $endYr = date('Y') + 10;
                @endphp
                @for($y = $startYr; $y <= $endYr; $y++)
                  <option value="{{ $y }}" {{ strpos($academicYear, (string)$y) === 0 ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Academic Year (AY)</label>
              <input type="text" name="academic_year" id="ayFullText" readonly class="w-full bg-slate-950 border border-slate-850 rounded-xl px-3 py-2 text-slate-400 outline-none text-sm font-bold" value="{{ $academicYear }}">
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Activity Category</label>
            <select name="activity_type" id="activityTypeSelector" onchange="toggleFormFields(this.value)" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm font-bold cursor-pointer">
              <option value="fdp_attended">FDP Attended</option>
              <option value="workshop_attended">Workshop Attended</option>
              <option value="course_attended">Course/Certification Attended</option>
              <option value="gap_in_syllabus">Curricular Gap Identified in Syllabus</option>
              <option value="project_guided">Student Project Guided</option>
              <option value="seminar_guided">Seminar Guided</option>
              <option value="publication">Research Paper / Journal Publication</option>
              <option value="book_published">Book Published (with ISBN)</option>
            </select>
          </div>

          <!-- Fields Area -->
          <div id="dynamicFieldsContainer" class="space-y-4">
            <!-- Fields populated by JS depending on type -->
          </div>

          <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl transition text-sm cursor-pointer border-none shadow-md">
            Save Record
          </button>
        </form>
      </div>

      <!-- Entries list -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-slate-950 border border-slate-850 rounded-3xl p-6">
          <h2 class="text-sm font-black text-white uppercase tracking-wider border-b border-slate-900 pb-3 mb-4">Recorded Entries for {{ $academicYear }}</h2>
          
          @if($activities->isEmpty())
            <div class="p-8 text-center text-slate-500 text-sm font-medium">
              No professional records found for this Academic Year.
            </div>
          @else
            <div class="divide-y divide-slate-900 space-y-4">
              @foreach($activities as $act)
                <div class="flex items-start justify-between gap-4 pt-4 first:pt-0">
                  <div class="space-y-1">
                    <span class="inline-block px-2.5 py-0.5 bg-blue-500/10 text-blue-400 rounded-full text-[10px] font-black uppercase tracking-wider">
                      {{ str_replace('_', ' ', $act->activity_type) }}
                    </span>
                    
                    <!-- FDP / Workshop details -->
                    @if(in_array($act->activity_type, ['fdp_attended', 'workshop_attended', 'course_attended']))
                      <h3 class="text-sm font-bold text-white">{{ $act->details['title'] ?? 'Untitled Training' }}</h3>
                      <p class="text-xs text-slate-400">Duration: {{ $act->details['duration'] ?? '-' }} days | Venue: {{ $act->details['venue'] ?? '-' }} | Date: {{ $act->details['date'] ?? '-' }}</p>
                    @endif

                    <!-- Syllabus gaps details -->
                    @if($act->activity_type === 'gap_in_syllabus')
                      <h3 class="text-sm font-bold text-white">{{ $act->details['subject'] ?? 'Subject' }}</h3>
                      <p class="text-xs text-slate-400"><strong>Identified Gap:</strong> {{ $act->details['gap_details'] ?? '-' }}</p>
                      <p class="text-xs text-slate-400"><strong>Action Taken/Proposed:</strong> {{ $act->details['action_taken'] ?? '-' }}</p>
                    @endif

                    <!-- Project/Seminar Guided -->
                    @if(in_array($act->activity_type, ['project_guided', 'seminar_guided']))
                      <h3 class="text-sm font-bold text-white">{{ $act->details['title'] ?? 'Title' }}</h3>
                      <p class="text-xs text-slate-400">Students: {{ $act->details['students'] ?? '-' }} | Batch: {{ $act->details['batch'] ?? '-' }}</p>
                    @endif

                    <!-- Publication / Book -->
                    @if($act->activity_type === 'publication')
                      <h3 class="text-sm font-bold text-white">{{ $act->details['title'] ?? 'Paper Title' }}</h3>
                      <p class="text-xs text-slate-400">Journal: {{ $act->details['journal'] ?? '-' }} | Year: {{ $act->details['year'] ?? '-' }}</p>
                    @endif
                    @if($act->activity_type === 'book_published')
                      <h3 class="text-sm font-bold text-white">{{ $act->details['title'] ?? 'Book Title' }}</h3>
                      <p class="text-xs text-slate-400">Publisher: {{ $act->details['publisher'] ?? '-' }} | ISBN: {{ $act->details['isbn'] ?? '-' }} | Year: {{ $act->details['year'] ?? '-' }}</p>
                    @endif
                  </div>
                  
                  <form method="POST" action="/staff/professional-activities/delete/{{ $act->id }}">
                    @csrf
                    <button type="submit" class="p-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-xl transition text-xs font-bold cursor-pointer">
                      Delete
                    </button>
                  </form>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>

    </div>

  </div>

  <script>
    const schemas = {
      fdp_attended: [
        { label: 'Title of the FDP', name: 'title', type: 'text', placeholder: 'e.g. Advanced Laravel Development' },
        { label: 'Duration (in days)', name: 'duration', type: 'number', placeholder: 'e.g. 5' },
        { label: 'Venue', name: 'venue', type: 'text', placeholder: 'e.g. Carmel Polytechnic College' },
        { label: 'Date From', name: 'date', type: 'text', placeholder: 'YYYY-MM-DD' }
      ],
      workshop_attended: [
        { label: 'Title of Workshop', name: 'title', type: 'text', placeholder: 'e.g. IoT Systems & Networking' },
        { label: 'Duration (in days)', name: 'duration', type: 'number', placeholder: 'e.g. 2' },
        { label: 'Venue', name: 'venue', type: 'text', placeholder: 'e.g. Govt Polytechnic College' },
        { label: 'Date From', name: 'date', type: 'text', placeholder: 'YYYY-MM-DD' }
      ],
      course_attended: [
        { label: 'Course/Certification Title', name: 'title', type: 'text', placeholder: 'e.g. NPTEL Data Structures' },
        { label: 'Duration (in weeks/days)', name: 'duration', type: 'text', placeholder: 'e.g. 8 weeks' },
        { label: 'Institution/Platform', name: 'venue', type: 'text', placeholder: 'e.g. NPTEL / Swayam' }
      ],
      gap_in_syllabus: [
        { label: 'Subject Name & Code', name: 'subject', type: 'text', placeholder: 'e.g. Computer Networks (CN-302)' },
        { label: 'Identified Curricular Gap Details', name: 'gap_details', type: 'textarea', placeholder: 'Identify details where syllabus falls short' },
        { label: 'Action Taken / Bridge Action Plan', name: 'action_taken', type: 'text', placeholder: 'e.g. Conducted a 3-hour seminar on IPv6 Routing protocols' }
      ],
      project_guided: [
        { label: 'Project Title', name: 'title', type: 'text', placeholder: 'e.g. Smart Attendance System' },
        { label: 'Batch Category / Year', name: 'batch', type: 'text', placeholder: 'e.g. 2023-2026 Batch' },
        { label: 'Student Names (Comma separated)', name: 'students', type: 'text', placeholder: 'e.g. Arjun, Vishnu, Rahul' }
      ],
      seminar_guided: [
        { label: 'Seminar Topic', name: 'title', type: 'text', placeholder: 'e.g. Introduction to Quantum Computing' },
        { label: 'Student Name', name: 'students', type: 'text', placeholder: 'e.g. Anjali Nair' },
        { label: 'Date Presented', name: 'date', type: 'text', placeholder: 'YYYY-MM-DD' }
      ],
      publication: [
        { label: 'Research Paper / Journal Title', name: 'title', type: 'text', placeholder: 'e.g. AI-driven grading engines in polytechnics' },
        { label: 'Journal/Conference Name', name: 'journal', type: 'text', placeholder: 'e.g. International Journal of Engineering' },
        { label: 'Year of Publication', name: 'year', type: 'number', placeholder: 'e.g. 2026' }
      ],
      book_published: [
        { label: 'Book Title', name: 'title', type: 'text', placeholder: 'e.g. Basic Electronics & Circuits' },
        { label: 'Publisher', name: 'publisher', type: 'text', placeholder: 'e.g. Pearson India' },
        { label: 'ISBN Number', name: 'isbn', type: 'text', placeholder: 'e.g. 978-3-16-148410-0' },
        { label: 'Year of Publication', name: 'year', type: 'number', placeholder: 'e.g. 2025' }
      ]
    };

    function toggleFormFields(type) {
      const container = document.getElementById('dynamicFieldsContainer');
      container.innerHTML = '';
      
      const fields = schemas[type] || [];
      fields.forEach(f => {
        const div = document.createElement('div');
        div.className = 'space-y-1';
        
        const label = document.createElement('label');
        label.className = 'block text-xs font-bold text-slate-400 uppercase';
        label.innerText = f.label;
        div.appendChild(label);
        
        if (f.type === 'textarea') {
          const textarea = document.createElement('textarea');
          textarea.name = `details[${f.name}]`;
          textarea.placeholder = f.placeholder;
          textarea.rows = 3;
          textarea.className = 'w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm';
          textarea.required = true;
          div.appendChild(textarea);
        } else {
          const input = document.createElement('input');
          input.type = f.type;
          input.name = `details[${f.name}]`;
          input.placeholder = f.placeholder;
          input.className = 'w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white outline-none text-sm';
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
