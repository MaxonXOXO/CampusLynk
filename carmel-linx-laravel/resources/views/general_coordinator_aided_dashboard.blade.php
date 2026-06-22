<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - General Coordinator (Aided) Dashboard</title>
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
      <div class="bg-gradient-to-br from-blue-500 to-sky-600 text-white font-black rounded-xl w-10 h-10 flex items-center justify-center text-lg shadow-lg shadow-blue-500/20">CL</div>
      <div>
        <h2 class="font-extrabold text-sm tracking-wide">Carmel Linx</h2>
        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Coordinator (Aided)</span>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-bold text-xs block truncate text-slate-200">{{ session('userName') }}</span>
        <span class="text-[9px] font-bold text-blue-400 block uppercase tracking-wider">General Aided Coord</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500">
        <span class="material-symbols-rounded text-lg">dashboard</span> Overview
      </button>
      <button id="navDirectory" onclick="switchPanel('directory')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">group</span> User Directory
      </button>

      @php
        $mobileNo = session('userId');
        $isTutor = \App\Models\ClassManagement::where('tutor_mobile_no', $mobileNo)->exists();
        $isMentor = \App\Models\ClassManagement::where('mentor_mobile_no', $mobileNo)->exists();
      @endphp

      @if($isTutor)
      <a href="/dashboard/tutor" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-sky-400 hover:bg-sky-900/30 cursor-pointer no-underline block">
        <span class="material-symbols-rounded text-lg">admin_panel_settings</span> Tutor Console
      </a>
      @endif

      @if($isTutor || $isMentor)
      <a href="/dashboard/tutor" onclick="sessionStorage.setItem('openMentoring', 'true')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-emerald-400 hover:bg-emerald-900/30 cursor-pointer no-underline block mt-4">
        <span class="material-symbols-rounded text-lg">diversity_3</span> My Mentoring
      </a>
      @endif

    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80">
      <a href="/logout" class="w-full py-3 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-xs flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="text-lg font-extrabold text-slate-100 tracking-tight">Overview</h1>
      <div id="loadingIndicator" class="hidden items-center gap-2 text-xs text-slate-400">
        <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
        <span>Syncing...</span>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- PANEL 1: OVERVIEW -->
      <div id="panelDashboard" class="space-y-6">
        <div class="bg-slate-950/40 border border-slate-800/60 p-8 rounded-2xl text-center shadow-sm max-w-2xl mx-auto">
          <span class="material-symbols-rounded text-5xl text-blue-400 block mb-3">verified_user</span>
          <h3 class="font-black text-slate-200 text-lg">General Department Coordinator (Aided) Console Connected</h3>
          <p class="text-slate-400 text-sm mt-2 font-medium">
            Welcome! As the selected Aided General Department Coordinator, you have HOD-like coordinates over academic staff assigned to General Department Aided.
          </p>
        </div>
      </div>

      <!-- PANEL 2: DIRECTORY -->
      <div id="panelDirectory" class="hidden space-y-6">
        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search User</label>
            <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none" placeholder="Search staff name...">
          </div>
          <div>
            <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Role Designation</label>
            <select id="filterRole" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-xs text-white focus:border-blue-500 outline-none">
              <option value="">All Roles</option>
              <option value="Lecturer">Lecturer</option>
              <option value="Demonstrator">Demonstrator</option>
              <option value="Trade_Instructor">Trade Instructor</option>
            </select>
          </div>
        </div>

        <!-- Users Table -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
                <th class="p-4">Profile</th>
                <th class="p-4">Mobile</th>
                <th class="p-4">Branch</th>
                <th class="p-4">Role Designation</th>
                <th class="p-4 text-right">Account Status</th>
              </tr>
            </thead>
            <tbody id="usersTableBody">
              <!-- Dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </main>

  <script>
    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      if (activePanel === 'directory') loadUsers();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'directory'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      document.getElementById('panelTitle').innerText = panelId === 'dashboard' ? 'Overview' : 'User Directory';
      if (panelId === 'directory') loadUsers();
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      const search = document.getElementById('filterSearch').value;
      const role = document.getElementById('filterRole').value;

      fetch(`/api/admin/users?search=${encodeURIComponent(search)}&branch=GEN_AIDED&role=${role}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';
            if (data.users.length === 0) {
              tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-slate-500">No staff found.</td></tr>';
              return;
            }
            data.users.forEach(user => {
              const tr = document.createElement('tr');
              tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30';
              tr.innerHTML = `
                <td class="p-4 flex items-center gap-3">
                  <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-800 shadow">
                  <div>
                    <span class="font-bold text-slate-100 block">${user.name}</span>
                    <span class="text-[10px] text-slate-500 block">${user.email}</span>
                  </div>
                </td>
                <td class="p-4 font-mono text-slate-300">${user.id}</td>
                <td class="p-4"><span class="font-bold font-mono text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
                <td class="p-4 text-slate-300">${user.role}</td>
                <td class="p-4 text-right"><span class="px-2 py-0.5 rounded-full text-[10px] bg-green-500/10 text-green-400 border border-green-500/20">${user.status}</span></td>
              `;
              tbody.appendChild(tr);
            });
          }
        })
        .catch(() => indicator.classList.add('hidden'));
    }
  </script>
</body>
</html>
