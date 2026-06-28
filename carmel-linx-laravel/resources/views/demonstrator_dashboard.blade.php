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
      <div class="bg-gradient-to-br from-blue-500 to-sky-600 text-white font-black rounded-xl w-10 h-10 flex items-center justify-center shadow-lg shadow-blue-500/20 text-lg">CL</div>
      <div>
        <h2 class="font-extrabold text-[10px] tracking-wide text-sm">Carmel Linx</h2>
        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Demonstrator Console</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-bold text-[10px] block truncate text-slate-200 text-[10px] text-xs">{{ session('userName') }}</span>
        <span class="text-[10px] font-bold text-teal-400 block uppercase tracking-wider">{{ session('userBranch') }} Demonstrator</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-[10px] flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 text-[10px] text-xs">
        <span class="material-symbols-rounded text-lg">edit_note</span> Lab Workspaces
      </button>

      @php
        $mobileNo = session('userId');
        $isTutor = \App\Models\ClassManagement::where('tutor_mobile_no', $mobileNo)->exists();
        $isMentor = \App\Models\ClassManagement::where('mentor_mobile_no', $mobileNo)->exists();
      @endphp

      @if($isTutor)
      <a href="/dashboard/tutor" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-[10px] flex items-center gap-3 transition-premium text-sky-400 hover:bg-sky-900/30 cursor-pointer no-underline block text-[10px] text-xs">
        <span class="material-symbols-rounded text-lg">admin_panel_settings</span> Tutor Console
      </a>
      @endif

      @if($isTutor || $isMentor)
      <a href="/dashboard/tutor" onclick="sessionStorage.setItem('openMentoring', 'true')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-[10px] flex items-center gap-3 transition-premium text-emerald-400 hover:bg-emerald-900/30 cursor-pointer no-underline block text-[10px] text-xs">
        <span class="material-symbols-rounded text-lg">diversity_3</span> My Mentoring
      </a>
      @endif

      <a href="/course-files" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-[10px] flex items-center gap-3 transition-premium text-amber-400 hover:bg-amber-900/30 cursor-pointer no-underline block mt-2 text-[10px] text-xs">
        <span class="material-symbols-rounded text-lg">folder_special</span> Course Files
      </a>

      <a href="/remedial-sessions" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-[10px] flex items-center gap-3 transition-premium text-purple-400 hover:bg-purple-900/30 hover:text-purple-300 cursor-pointer no-underline block mt-2">
        <span class="material-symbols-rounded text-xs">health_and_safety</span> Remedial Sessions
      </a>

      <button id="navSecurity" onclick="switchPanel('security')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-[10px] flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer mt-4">
        <span class="material-symbols-rounded text-xs">security</span> My Security Log
      </button>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80">
      <a href="{{ url('/logout') }}" class="w-full py-3 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-[10px] flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium">
        <span class="material-symbols-rounded text-[10px]">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="text-xs font-extrabold text-slate-100 tracking-tight">Lab Workspaces</h1>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- PANEL 1: DASHBOARD -->
      <div id="panelDashboard" class="space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-8 rounded-2xl text-center shadow-sm max-w-2xl mx-auto">
          <span class="material-symbols-rounded text-5xl text-blue-500 block mb-3">science</span>
          <h3 class="font-black text-slate-200 text-xs">Demonstrator Console Connected</h3>
          <p class="text-slate-400 text-[10px] mt-2 font-medium">
            This workspace is ready. You can manage lab practical records, evaluate student lab outcomes, and publish experiment sheets.
          </p>
        </div>
      </div>

      <!-- PANEL 2: SECURITY LOG -->
      <div id="panelSecurity" class="hidden space-y-6">
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
          <h3 class="text-[10px] font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-400 text-xs">security</span> My Profile Security Audit trail
          </h3>
          <div class="overflow-x-auto scrollbar-hidden border border-slate-800 rounded-xl">
            <table class="w-full text-left text-[10px] border-collapse">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold">
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
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-[10px] flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-[10px] flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
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
              tr.className = "border-b border-slate-800/40 text-[10px] hover:bg-slate-900/20";
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
  </script>
</body>
</html>
