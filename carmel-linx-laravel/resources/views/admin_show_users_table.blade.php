<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - User Credentials Table</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <style>
    html { font-size: 90%; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.3); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.3); border-radius: 99px; }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen p-4 md:p-8">

  <div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Top Header & Navigation -->
    <div class="bg-slate-900/80 border border-slate-800 p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4 shadow-xl">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
          <span class="material-symbols-rounded text-2xl">key</span>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-white tracking-tight">System User Directory & Credentials</h1>
          <p class="text-xs text-slate-400">Live view of registered login IDs, names, roles, and passwords across the system.</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <button onclick="window.print()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl font-bold text-sm transition flex items-center gap-2 cursor-pointer border border-slate-700">
          <span class="material-symbols-rounded text-base">print</span> Print Table
        </button>
        <a href="/dashboard/superadmin" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-sm transition flex items-center gap-2 no-underline shadow-lg shadow-blue-600/20">
          <span class="material-symbols-rounded text-base">arrow_back</span> Back to Control Desk
        </a>
        <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to sign out?')" class="px-4 py-2 bg-rose-950/80 hover:bg-rose-900 text-rose-300 rounded-xl font-bold text-sm transition flex items-center gap-2 no-underline border border-rose-800/60">
          <span class="material-symbols-rounded text-base">logout</span> Sign Out
        </a>
      </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-slate-900/60 border border-slate-800 p-4 rounded-2xl flex flex-wrap items-center justify-between gap-4">
      <div class="flex items-center gap-2 w-full md:w-96 bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm">
        <span class="material-symbols-rounded text-slate-400 text-base">search</span>
        <input type="text" id="searchInput" onkeyup="filterTables()" placeholder="Search by name, Mobile, Reg No, branch..." class="w-full bg-transparent text-white outline-none text-sm placeholder-slate-500">
      </div>

      <div class="flex items-center gap-2">
        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Quick Jump:</span>
        <a href="#staffSection" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-xs font-bold text-slate-300 no-underline border border-slate-700">Staff Accounts ({{ count($staff) }})</a>
        <a href="#studentSection" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 rounded-lg text-xs font-bold text-slate-300 no-underline border border-slate-700">Student Accounts ({{ count($students) }})</a>
      </div>
    </div>

    <!-- 1. STAFF PROFILES TABLE -->
    <div id="staffSection" class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 space-y-4 shadow-lg">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-400 text-xl">badge</span>
          Staff, HOD & Administrative Accounts
          <span class="text-xs font-mono text-slate-400 bg-slate-800 px-2 py-0.5 rounded-full">staff_profiles</span>
        </h2>
        <span class="text-xs text-slate-400 font-medium">Total: {{ count($staff) }} profiles</span>
      </div>

      <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left text-sm border-collapse" id="staffTable">
          <thead>
            <tr class="bg-slate-950/80 text-slate-400 font-bold border-b border-slate-800 text-xs uppercase tracking-wider">
              <th class="p-3">Mobile (Login ID)</th>
              <th class="p-3">Full Name</th>
              <th class="p-3">Designation Role</th>
              <th class="p-3">Branch</th>
              <th class="p-3">Password</th>
              <th class="p-3">Account Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60 text-slate-200">
            @forelse($staff as $s)
            <tr class="hover:bg-slate-800/40 transition">
              <td class="p-3 font-mono font-bold text-blue-400">{{ $s->mobile_no }}</td>
              <td class="p-3 font-bold text-white">{{ $s->name }}</td>
              <td class="p-3">
                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-800 text-blue-300 border border-slate-700">
                  {{ str_replace('_', ' ', $s->designation) }}
                </span>
              </td>
              <td class="p-3 text-slate-300">{{ $s->branch ?: 'N/A' }}</td>
              <td class="p-3 font-mono font-bold text-emerald-400 bg-slate-950/40 px-2 rounded tracking-wide">{{ $s->password }}</td>
              <td class="p-3">
                @if($s->account_status === 'Approved')
                  <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Approved</span>
                @else
                  <span class="px-2 py-0.5 rounded text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">{{ $s->account_status }}</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="p-4 text-center text-slate-500">No staff accounts found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- 2. STUDENT PROFILES TABLE -->
    <div id="studentSection" class="bg-slate-900/60 border border-slate-800 rounded-2xl p-5 space-y-4 shadow-lg">
      <div class="flex items-center justify-between border-b border-slate-800 pb-3">
        <h2 class="text-lg font-bold text-white flex items-center gap-2">
          <span class="material-symbols-rounded text-sky-400 text-xl">school</span>
          Student Accounts
          <span class="text-xs font-mono text-slate-400 bg-slate-800 px-2 py-0.5 rounded-full">students</span>
        </h2>
        <span class="text-xs text-slate-400 font-medium">Total: {{ count($students) }} students</span>
      </div>

      <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left text-sm border-collapse" id="studentTable">
          <thead>
            <tr class="bg-slate-950/80 text-slate-400 font-bold border-b border-slate-800 text-xs uppercase tracking-wider">
              <th class="p-3">Register No</th>
              <th class="p-3">Admission No</th>
              <th class="p-3">Student Name</th>
              <th class="p-3">Branch</th>
              <th class="p-3">Semester</th>
              <th class="p-3">Password</th>
              <th class="p-3">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-800/60 text-slate-200">
            @forelse($students as $st)
            <tr class="hover:bg-slate-800/40 transition">
              <td class="p-3 font-mono font-bold text-sky-400">{{ $st->reg_no }}</td>
              <td class="p-3 font-mono text-slate-400">{{ $st->adm_no ?: 'N/A' }}</td>
              <td class="p-3 font-bold text-white">{{ $st->name }}</td>
              <td class="p-3 text-slate-300">{{ $st->branch }}</td>
              <td class="p-3"><span class="px-2 py-0.5 bg-slate-800 rounded text-xs font-bold text-slate-300">S{{ $st->semester }}</span></td>
              <td class="p-3 font-mono font-bold text-emerald-400 bg-slate-950/40 px-2 rounded tracking-wide">{{ $st->password }}</td>
              <td class="p-3">
                @if($st->status === 'Approved')
                  <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Approved</span>
                @else
                  <span class="px-2 py-0.5 rounded text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">{{ $st->status }}</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="p-6 text-center text-slate-500">
                No student accounts registered yet. Database is clean and ready for beta registrations.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script>
    function filterTables() {
      const q = document.getElementById('searchInput').value.toLowerCase();
      
      // Filter Staff Table
      const staffRows = document.querySelectorAll('#staffTable tbody tr');
      staffRows.forEach(tr => {
        const text = tr.innerText.toLowerCase();
        tr.style.display = text.includes(q) ? '' : 'none';
      });

      // Filter Student Table
      const studentRows = document.querySelectorAll('#studentTable tbody tr');
      studentRows.forEach(tr => {
        const text = tr.innerText.toLowerCase();
        tr.style.display = text.includes(q) ? '' : 'none';
      });
    }
  </script>
</body>
</html>
