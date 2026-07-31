<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Academic Coordinator Dashboard (Self-Financing)</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <style>
    @media (max-width: 1440px) {
      html, body {
        font-size: 13px !important;
      }
      .p-6 { padding: 1rem !important; }
      .p-8 { padding: 1.25rem !important; }
      .gap-6 { gap: 1rem !important; }
      .gap-8 { gap: 1.25rem !important; }
      .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      .text-nowrap { white-space: nowrap !important; }
    }
    .font-extrabold, .font-black { font-weight: 700 !important; }
    input, select, textarea { font-size: 0.875rem !important; }
    .text-lg { font-size: 1.05rem !important; }
    .text-base { font-size: 0.875rem !important; }
    nav.space-y-1\.5 > :not([hidden]) ~ :not([hidden]) { margin-top: 0.125rem !important; }
    nav.space-y-1\.5 a, nav.space-y-1\.5 button {
      padding-top: 0.375rem !important;
      padding-bottom: 0.375rem !important;
    }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }

    /* MOBILE-SPECIFIC SIDEBAR & CARD FIXES */
    @media (max-width: 767px) {
      html, body { font-size: 14px !important; }
      p, span, a, button, input, select, textarea, td, th { font-size: 13px !important; }
      h1, .text-2xl { font-size: 18px !important; }
      h2, .text-xl { font-size: 16px !important; }
      h3, .text-lg { font-size: 15px !important; }

      aside {
        width: 100% !important;
        position: relative !important;
        border-right: none !important;
        border-bottom: 1px solid #1e293b !important;
        flex-direction: column !important;
        align-items: stretch !important;
        padding: 0.75rem 1rem !important;
        gap: 0.5rem !important;
      }
      
      aside > div.border-b {
        display: flex !important;
        border-bottom: none !important;
        padding: 0 !important;
        margin: 0 !important;
        align-items: center !important;
        justify-content: space-between !important;
      }
      aside > div.border-b img { width: 2rem !important; height: 2rem !important; }
      aside > div.border-b h2 { font-size: 16px !important; font-weight: 900 !important; }

      aside nav {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 0.4rem !important;
        width: 100% !important;
        padding: 0.25rem 0 !important;
        margin: 0 !important;
        background: transparent !important;
        border: none !important;
      }
      
      aside nav a, aside nav button {
        padding: 0.5rem 0.5rem !important;
        margin: 0 !important;
        border-radius: 0.5rem !important;
        font-size: 12px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.25rem !important;
        white-space: nowrap !important;
        width: 100% !important;
        border-left: none !important;
      }
      
      #sidebarAvatarContainer { display: none !important; }
    }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row md:overflow-hidden overflow-y-auto">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Sidebar Navigation -->
  <aside class="w-full md:w-64 bg-slate-950 text-white flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <div class="p-5 border-b border-slate-800/60 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="{{ asset('logo.jpg') }}" class="w-10 h-10 rounded-xl object-cover shadow-lg border border-slate-800/60">
        <div>
          <h2 class="font-black tracking-tight leading-tight" style="font-size:1.1rem;background:linear-gradient(to right,#38bdf8,#818cf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Carmel Linx</h2>
          <span class="text-xs text-indigo-400 font-bold uppercase tracking-widest">Academic Coordinator</span>
        </div>
      </div>
      <!-- Sign Out for Mobile -->
      <div class="md:hidden">
        <a href="{{ url('/logout') }}" class="px-2.5 py-1 bg-red-950/60 hover:bg-red-900 border border-red-800/60 text-red-300 rounded-lg text-xs font-bold flex items-center gap-1 no-underline">
          <span class="material-symbols-rounded text-sm">logout</span> Exit
        </a>
      </div>
    </div>

    <!-- Active Profile Info -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40 flex items-center gap-3" id="sidebarAvatarContainer">
      <img src="{{ session('userPhoto') ?: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150' }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
      <div class="overflow-hidden">
        <span class="font-black text-base block truncate text-white leading-tight">{{ session('userName') }}</span>
        <span class="text-xs font-bold text-indigo-400 block uppercase tracking-wider">Self-Financing Coordinator</span>
      </div>
    </div>

    <!-- Navigation Menus -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navDashboard" onclick="switchPanel('dashboard')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 text-xs">
        <span class="material-symbols-rounded text-lg">dashboard</span> Overview & Approvals
      </button>
      
      <button id="navDirectory" onclick="switchPanel('directory')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">group</span> SF Staff Directory
      </button>

      <a href="/staff/leave/reports" target="_blank" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-emerald-400 hover:bg-emerald-900/30 cursor-pointer no-underline block text-xs">
        <span class="material-symbols-rounded text-lg">event_note</span> Staff Leave Reports & Ledger
      </a>

      <button id="navSecurity" onclick="switchPanel('security')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer md:mt-4">
        <span class="material-symbols-rounded text-lg">security</span> Security Log
      </button>
    </nav>

    <!-- Logout for Desktop -->
    <div class="p-4 border-t border-slate-800/80 hidden md:block">
      <a href="{{ url('/logout') }}" class="w-full py-3 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium text-xs">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Workspace -->
  <main class="flex-grow flex flex-col overflow-hidden relative">
    
    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <div class="flex items-center gap-3">
        <h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">Academic Coordinator Overview</h1>
        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">Self-Financing Stream</span>
      </div>
      <div id="loadingIndicator" class="hidden items-center gap-2 text-slate-400 text-xs">
        <div class="w-4 h-4 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin"></div>
        <span>Syncing...</span>
      </div>
    </header>

    <!-- Panel Container -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-6">
      
      <!-- Alert Banner -->
      <div id="globalAlert" class="hidden p-4 rounded-xl font-bold transition-premium border text-xs"></div>

      <!-- PANEL 1: OVERVIEW & PENDING LEAVE APPROVALS -->
      <div id="panelDashboard" class="space-y-6">
        
        <!-- Metrics Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-amber-500/10 text-amber-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">approval</span></div>
            <div>
              <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Pending SF Approvals</span>
              <span id="statPendingLeave" class="text-xl font-black text-white mt-0.5">0</span>
            </div>
          </div>

          <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-indigo-500/10 text-indigo-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">account_tree</span></div>
            <div>
              <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Supervised Stream</span>
              <span class="text-sm font-black text-indigo-300 mt-0.5 block">EL • AU • CT • GEN SF</span>
            </div>
          </div>

          <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-emerald-500/10 text-emerald-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">event_note</span></div>
            <div>
              <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Master Ledger</span>
              <a href="/staff/leave/reports" target="_blank" class="text-xs font-bold text-emerald-400 hover:underline flex items-center gap-1 mt-0.5">
                View & Print <span class="material-symbols-rounded text-xs">open_in_new</span>
              </a>
            </div>
          </div>

          <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4 shadow-sm">
            <div class="bg-purple-500/10 text-purple-400 p-3 rounded-xl"><span class="material-symbols-rounded text-2xl">smartphone</span></div>
            <div>
              <span class="text-xs text-slate-400 uppercase font-bold tracking-wider block">Mobile Portal</span>
              <a href="/staff/mobile" target="_blank" class="text-xs font-bold text-purple-400 hover:underline flex items-center gap-1 mt-0.5">
                Open Portal <span class="material-symbols-rounded text-xs">open_in_new</span>
              </a>
            </div>
          </div>
        </div>

        <!-- Pending Leave Applications Section -->
        <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl p-6 space-y-4">
          <div class="flex justify-between items-center border-b border-slate-800/60 pb-3">
            <div>
              <h3 class="font-black text-slate-100 text-base flex items-center gap-2">
                <span class="material-symbols-rounded text-amber-400 text-lg">pending_actions</span>
                Staff Leave Applications Pending Academic Coordinator Approval
              </h3>
              <p class="text-xs text-slate-400 mt-0.5">Stage 2 of 3-tier hierarchy (HOD Approved → <strong>Academic Coordinator</strong> → Principal) for Self-Financing departments (EL, AU, CT, GEN SF).</p>
            </div>
            <button onclick="loadPendingApprovals()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition-premium cursor-pointer flex items-center gap-1.5">
              <span class="material-symbols-rounded text-sm">sync</span> Refresh Queue
            </button>
          </div>

          <div class="overflow-x-auto scrollbar-hidden">
            <table class="w-full text-left text-xs border-collapse whitespace-nowrap">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                  <th class="p-3">Staff Member</th>
                  <th class="p-3">Dept</th>
                  <th class="p-3">Leave Category</th>
                  <th class="p-3">Date(s) Needed</th>
                  <th class="p-3">Session</th>
                  <th class="p-3">Reason & Work Arrangement</th>
                  <th class="p-3">HOD Stage</th>
                  <th class="p-3 text-right">Actions</th>
                </tr>
              </thead>
              <tbody id="pendingLeaveTableBody" class="divide-y divide-slate-800/40 text-slate-300">
                <tr><td colspan="8" class="p-6 text-center text-slate-500 font-bold">Loading pending leave applications...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Supervised Department Quick Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-slate-950/30 border border-slate-800/40 p-5 rounded-2xl space-y-2">
            <div class="flex justify-between items-center">
              <span class="font-bold text-slate-200 text-sm">Electronics (EL)</span>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Self-Financing</span>
            </div>
            <p class="text-xs text-slate-400">3-Tier Approval Path Active (HOD → Coordinator → Principal)</p>
          </div>

          <div class="bg-slate-950/30 border border-slate-800/40 p-5 rounded-2xl space-y-2">
            <div class="flex justify-between items-center">
              <span class="font-bold text-slate-200 text-sm">Automobile (AU)</span>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Self-Financing</span>
            </div>
            <p class="text-xs text-slate-400">3-Tier Approval Path Active (HOD → Coordinator → Principal)</p>
          </div>

          <div class="bg-slate-950/30 border border-slate-800/40 p-5 rounded-2xl space-y-2">
            <div class="flex justify-between items-center">
              <span class="font-bold text-slate-200 text-sm">Computer (CT) & GEN SF</span>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">Self-Financing</span>
            </div>
            <p class="text-xs text-slate-400">3-Tier Approval Path Active (HOD → Coordinator → Principal)</p>
          </div>
        </div>

      </div>

      <!-- PANEL 2: SF STAFF DIRECTORY -->
      <div id="panelDirectory" class="hidden space-y-6">
        <!-- Filters Console -->
        <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Search Staff</label>
            <input type="text" id="filterSearch" oninput="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs" placeholder="Search name or mobile...">
          </div>
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Department Filter</label>
            <select id="filterBranch" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs">
              <option value="">All SF Departments (EL, AU, CT, GEN SF)</option>
              <option value="EL">Electronics (EL)</option>
              <option value="AU">Automobile (AU)</option>
              <option value="CT">Computer Engineering (CT)</option>
              <option value="GEN_SF">General SF (GEN_SF)</option>
            </select>
          </div>
          <div>
            <label class="block text-xs text-slate-400 font-bold uppercase tracking-wider mb-1.5">Role Designation</label>
            <select id="filterRole" onchange="loadUsers()" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2 text-white focus:border-blue-500 outline-none text-xs">
              <option value="">All Roles</option>
              <option value="HOD">HOD</option>
              <option value="Lecturer">Lecturer</option>
              <option value="Demonstrator">Demonstrator</option>
              <option value="Trade_Instructor">Trade Instructor</option>
            </select>
          </div>
        </div>

        <!-- Users Table -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl overflow-hidden">
          <table class="w-full text-left border-collapse text-xs">
            <thead>
              <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                <th class="p-4">Profile</th>
                <th class="p-4">Mobile ID</th>
                <th class="p-4">Branch</th>
                <th class="p-4">Designation</th>
                <th class="p-4 text-right">Account Status</th>
              </tr>
            </thead>
            <tbody id="usersTableBody" class="divide-y divide-slate-800/40">
              <!-- Dynamically populated -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- PANEL 3: SECURITY LOG -->
      <div id="panelSecurity" class="hidden space-y-6">
        <div class="bg-slate-950/30 border border-slate-800/40 p-6 rounded-2xl">
          <h3 class="font-black text-slate-200 border-b border-slate-800/60 pb-3 mb-4 flex items-center gap-2 text-sm">
            <span class="material-symbols-rounded text-blue-400 text-lg">security</span> My Profile Security Audit Trail
          </h3>
          <div class="overflow-x-auto scrollbar-hidden border border-slate-800 rounded-xl">
            <table class="w-full text-left border-collapse text-xs">
              <thead>
                <tr class="bg-slate-900/60 border-b border-slate-800 text-slate-400 font-bold">
                  <th class="p-4">Time</th>
                  <th class="p-4">Action</th>
                  <th class="p-4">Details</th>
                </tr>
              </thead>
              <tbody id="selfSecurityLogsTable">
                <!-- Loaded dynamically -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- REJECTION REMARKS MODAL -->
  <div id="rejectModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-black text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-rose-400 text-lg">cancel</span> Reject Leave Application
        </h3>
        <button onclick="closeRejectModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-lg">close</span></button>
      </div>

      <input type="hidden" id="rejectLeaveId">
      <div class="space-y-3">
        <p class="text-xs text-slate-400">Please enter rejection remarks for this leave request:</p>
        <textarea id="rejectRemarksInput" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl p-3 text-xs text-white outline-none focus:border-rose-500" placeholder="Specify reason for rejection..."></textarea>
      </div>

      <div class="flex gap-3 pt-2">
        <button onclick="closeRejectModal()" class="flex-1 py-2.5 border border-slate-800 hover:bg-slate-800 rounded-xl font-bold text-xs text-slate-300 transition-premium cursor-pointer">Cancel</button>
        <button onclick="submitRejection()" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer">Reject Leave</button>
      </div>
    </div>
  </div>

  <script>
    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      loadPendingApprovals();
    });

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl font-bold bg-rose-950/40 text-rose-400 border-rose-900 block shadow-sm text-xs";
      } else {
        alert.className = "p-4 rounded-xl font-bold bg-emerald-950/40 text-emerald-400 border-emerald-900 block shadow-sm text-xs";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'directory', 'security'];
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

      const titles = {
        'dashboard': 'Academic Coordinator Overview',
        'directory': 'Self-Financing Staff Directory',
        'security': 'My Profile Security Log'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'dashboard') loadPendingApprovals();
      if (panelId === 'directory') loadUsers();
      if (panelId === 'security') loadSelfSecurityLogs();
    }

    function loadPendingApprovals() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/staff/leave/pending-approvals')
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            document.getElementById('statPendingLeave').innerText = data.approvals.length;
            renderPendingTable(data.approvals);
          }
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    function renderPendingTable(items) {
      const tbody = document.getElementById('pendingLeaveTableBody');
      tbody.innerHTML = '';

      if (items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="p-8 text-center text-slate-500 font-bold">No pending leave applications requiring Academic Coordinator approval.</td></tr>`;
        return;
      }

      items.forEach(req => {
        const tr = document.createElement('tr');
        tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium';

        let datesText = req.start_date;
        if (req.end_date && req.end_date !== req.start_date) {
          datesText += ` to ${req.end_date}`;
        }
        if (req.ccl_date) {
          datesText += `<br><span class="text-[10px] text-amber-400 font-mono">CCL Date: ${req.ccl_date}</span>`;
        }

        let sessionBadge = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${req.session}</span>`;

        tr.innerHTML = `
          <td class="p-3 font-bold text-slate-100">
            ${req.staff_name}
            <span class="block text-[10px] font-normal text-slate-400">${req.designation}</span>
          </td>
          <td class="p-3"><span class="px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700">${req.department}</span></td>
          <td class="p-3"><span class="px-2 py-0.5 rounded font-bold text-[10px] bg-purple-500/10 text-purple-300 border border-purple-500/20">${req.leave_category} (${req.total_days}d)</span></td>
          <td class="p-3 font-mono text-slate-300 text-xs">${datesText}</td>
          <td class="p-3">${sessionBadge}</td>
          <td class="p-3 max-w-xs truncate">
            <span class="text-slate-200 block truncate" title="${req.reason}">${req.reason}</span>
            <span class="text-[10px] text-slate-400 block">${req.work_arrangement_status || 'Arrangement done'}</span>
          </td>
          <td class="p-3">
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Approved by ${req.hod_name || 'HOD'}</span>
          </td>
          <td class="p-3 text-right space-x-1">
            <button onclick="approveLeave(${req.id})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs transition-premium cursor-pointer shadow-sm">
              Approve
            </button>
            <button onclick="openRejectModal(${req.id})" class="px-3 py-1.5 bg-rose-950/50 hover:bg-rose-900 border border-rose-800 text-rose-300 rounded-lg font-bold text-xs transition-premium cursor-pointer shadow-sm">
              Reject
            </button>
            <a href="/staff/leave/${req.id}/pdf" target="_blank" class="px-2 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-xs transition-premium no-underline inline-flex items-center gap-1">
              <span class="material-symbols-rounded text-xs">picture_as_pdf</span> PDF
            </a>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function approveLeave(leaveId) {
      if (!confirm("Are you sure you want to approve this leave application? It will move to Principal for final approval.")) return;

      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/staff/leave/process-approval', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          leave_id: leaveId,
          stage: 'Coordinator',
          action: 'Approved',
          remarks: 'Approved by Academic Coordinator'
        })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Leave request successfully approved!');
          loadPendingApprovals();
        } else {
          showGlobalMessage(data.message || 'Approval failed.', true);
        }
      })
      .catch(() => {
        indicator.classList.add('hidden');
        showGlobalMessage('Network error processing approval.', true);
      });
    }

    function openRejectModal(leaveId) {
      document.getElementById('rejectLeaveId').value = leaveId;
      document.getElementById('rejectRemarksInput').value = '';
      document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
      document.getElementById('rejectModal').classList.add('hidden');
    }

    function submitRejection() {
      const leaveId = document.getElementById('rejectLeaveId').value;
      const remarks = document.getElementById('rejectRemarksInput').value.trim();

      if (!remarks) {
        alert("Please enter rejection remarks.");
        return;
      }

      closeRejectModal();
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/staff/leave/process-approval', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          leave_id: leaveId,
          stage: 'Coordinator',
          action: 'Rejected',
          remarks: remarks
        })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Leave request rejected.');
          loadPendingApprovals();
        } else {
          showGlobalMessage(data.message || 'Rejection failed.', true);
        }
      })
      .catch(() => {
        indicator.classList.add('hidden');
        showGlobalMessage('Network error processing rejection.', true);
      });
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      const search = document.getElementById('filterSearch').value;
      const branch = document.getElementById('filterBranch').value;
      const role = document.getElementById('filterRole').value;

      let url = `/api/admin/users?search=${encodeURIComponent(search)}&role=${role}`;
      if (branch) {
        url += `&branch=${branch}`;
      }

      fetch(url)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';
            // Filter SF stream if no branch filter selected
            const sfDepts = ['EL', 'AU', 'CT', 'GEN_SF', 'SF'];
            const filteredUsers = branch ? data.users : data.users.filter(u => sfDepts.includes(u.branch?.toUpperCase()));

            if (filteredUsers.length === 0) {
              tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-slate-500 font-bold">No Self-Financing staff members found.</td></tr>';
              return;
            }
            filteredUsers.forEach(user => {
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
                <td class="p-4 font-mono text-slate-300 font-bold">${user.id}</td>
                <td class="p-4"><span class="font-bold font-mono text-xs bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
                <td class="p-4 text-slate-300 font-medium">${user.role}</td>
                <td class="p-4 text-right"><span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">${user.status}</span></td>
              `;
              tbody.appendChild(tr);
            });
          }
        })
        .catch(() => indicator.classList.add('hidden'));
    }

    function loadSelfSecurityLogs() {
      const tbody = document.getElementById('selfSecurityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">Loading security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500 font-bold">No profile security logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-xs hover:bg-slate-900/20";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          }
        });
    }
  </script>
</body>
</html>
