<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Demonstrator Dashboard</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    /* Universal typography fix to avoid screen text spreading/bleeding on super bold weights */
    .font-extrabold, .font-black {
      font-weight: 700 !important;
    }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scrollbar-hidden::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hidden {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row overflow-hidden">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <div class="p-6 border-b border-slate-800/60 flex items-center gap-3">
      <div class="bg-gradient-to-br from-blue-500 to-sky-600 text-white font-bold rounded-xl w-10 h-10 flex items-center justify-center shadow-lg text-lg">CL</div>
      <div>
        <h2 class="font-bold text-sm tracking-wide">Carmel Linx</h2>
        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Demonstrator Console</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-bold text-sm block truncate text-slate-200">{{ session('userName') }}</span>
        <span class="text-xs font-bold text-teal-400 block uppercase tracking-wider">{{ session('userBranch') }} Demonstrator</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium bg-blue-600 text-white shadow-md cursor-pointer">
        <span class="material-symbols-rounded text-lg">edit_note</span> Lab Workspaces
      </button>

      @php
        $mobileNo = session('userId');
        $isTutor = \App\Models\ClassManagement::where('tutor_mobile_no', $mobileNo)->exists();
        $isMentor = \App\Models\ClassManagement::where('mentor_mobile_no', $mobileNo)->exists();
      @endphp

      @if($isTutor)
      <a href="/dashboard/tutor" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-300 hover:bg-slate-800 hover:text-white cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">admin_panel_settings</span> Tutor Console
      </a>
      @endif

      @if($isTutor || $isMentor)
      <a href="/dashboard/tutor" onclick="sessionStorage.setItem('openMentoring', 'true')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-emerald-400 hover:bg-emerald-950/40 cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">diversity_3</span> My Mentoring
      </a>
      @endif

      <a href="/course-files" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-amber-400 hover:bg-amber-950/40 cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">folder_special</span> Course Files
      </a>

      <a href="/remedial-sessions" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-purple-400 hover:bg-purple-950/40 cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">health_and_safety</span> Remedial Sessions
      </a>

      <a href="/staff/attendance-log" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-rose-400 hover:bg-rose-950/40 cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-lg">co_present</span> Log & Attendance
      </a>

      <a href="/staff/professional-activities" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-indigo-400 hover:bg-indigo-950/40 cursor-pointer no-underline block">
         <span class="material-symbols-rounded text-lg">school</span> Academic Activities
      </a>

      <button id="navSecurity" onclick="switchPanel('security')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mt-4">
        <span class="material-symbols-rounded text-lg">security</span> My Security Log
      </button>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80">
      <a href="{{ url('/logout') }}" class="w-full py-3 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium">
        <span class="material-symbols-rounded text-sm">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="text-base font-bold text-slate-100 tracking-tight">Lab Workspaces</h1>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- PANEL 1: DASHBOARD -->
      <div id="panelDashboard" class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-slate-950/30 border border-slate-800/40 p-4 rounded-2xl gap-4">
          <div>
            <h3 class="text-base font-bold text-slate-200">My Assigned Lab Workspaces</h3>
            <p class="text-sm text-slate-400 mt-0.5">Select a lab subject to enter the shared virtual classroom to manage experiment logs and evaluation.</p>
          </div>
        </div>

        @php
          $grouped = $assignments->groupBy('classroom_id');
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @forelse($grouped as $classroomId => $subjects)
            @php
              $first = $subjects->first();
            @endphp
            <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl overflow-hidden flex flex-col transition-premium hover:shadow-xl hover:shadow-black/50 hover:border-slate-700/60">
              <!-- Card Header -->
              <div class="p-4 border-b border-slate-800/60 bg-slate-900/40 flex justify-between items-start">
                <div>
                  <h4 class="font-black text-slate-200 text-xl tracking-tight">{{ $classroomId }}</h4>
                  <div class="text-sm text-slate-400 font-mono mt-0.5">{{ $first->branch }} • Year {{ $first->batch_year }}</div>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-blue-500/10 text-blue-300 border border-blue-500/20 text-xs font-bold">Lab Staff</span>
              </div>
              
              <!-- Card Body (Subjects List) -->
              <div class="p-4 space-y-3 flex-grow">
                @foreach($subjects as $s)
                  <a href="/dashboard/lecturer?subject_id={{ $s->subject_id }}&subject_name={{ urlencode($s->subject_name) }}&classroom_id={{ urlencode($s->classroom_id) }}" class="w-full text-left px-3.5 py-2.5 bg-slate-900/60 hover:bg-slate-800 border border-slate-800/60 hover:border-blue-500/50 rounded-xl transition-premium cursor-pointer group flex justify-between items-center no-underline">
                    <div>
                      <div class="text-sm font-bold text-slate-200 group-hover:text-blue-400 transition-premium">{{ $s->subject_name }}</div>
                      <div class="text-sm text-slate-350 font-mono mt-0.5">Sem {{ $s->semester }} • {{ $s->subject_type }} • {{ $s->subject_code }}</div>
                    </div>
                    <span class="material-symbols-rounded text-slate-500 group-hover:text-blue-500 text-sm transition-premium">open_in_new</span>
                  </a>
                @endforeach
              </div>
            </div>
          @empty
            <div class="col-span-full bg-slate-950/40 border border-slate-800/60 p-8 rounded-2xl text-center shadow-sm max-w-2xl mx-auto w-full">
              <span class="material-symbols-rounded text-5xl text-blue-500 block mb-3">science</span>
              <h3 class="font-bold text-slate-200 text-base">No Lab Assignments</h3>
              <p class="text-slate-400 text-sm mt-2 font-medium leading-relaxed">
                No lab subjects have been assigned to you by the HOD for the active semester.
              </p>
            </div>
          @endforelse
        </div>
      </div>

      <!-- PANEL 2: SECURITY LOG -->
      <div id="panelSecurity" class="hidden space-y-6">
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
          <h3 class="text-sm font-bold text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-400 text-sm">security</span> My Profile Security Audit trail
          </h3>
          <div class="overflow-x-auto scrollbar-hidden border border-slate-800 rounded-xl">
            <table class="w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-350 font-bold">
                  <th class="p-4">Time</th>
                  <th class="p-4">Action</th>
                  <th class="p-4">Details</th>
                </tr>
              </thead>
              <tbody id="securityLogsTable">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </main>

  <script>
    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      if (activePanel === 'security') loadSecurityLogs();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'security'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium bg-blue-600 text-white shadow-md cursor-pointer";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-300 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'dashboard': 'Lab Workspaces',
        'security': 'My Profile Security Log'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'security') loadSecurityLogs();
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
              tr.className = "border-b border-slate-800/40 text-sm hover:bg-slate-900/20";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4"><span class="px-2 py-0.5 rounded text-xs font-bold bg-blue-500/10 text-blue-300 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 text-slate-350">${log.details || ''}</td>
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
  </script>
</body>
</html>
