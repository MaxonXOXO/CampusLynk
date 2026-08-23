<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CampusLynk - System User Credentials &amp; Directory</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Fonts: Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Material Symbols -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

  <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
  <!-- Vite Asset Pipeline -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    @media print {
      body {
        background: white !important;
        color: black !important;
        padding: 0 !important;
      }
      .no-print {
        display: none !important;
      }
      .print-border {
        border: 1px solid #e2e8f0 !important;
        box-shadow: none !important;
      }
      table {
        font-size: 11px !important;
      }
    }
  </style>
</head>
<body class="bg-[#FAFAFB] text-slate-900 min-h-screen p-4 sm:p-6 lg:p-8 font-sans antialiased">

  <div class="max-w-7xl mx-auto space-y-6">
    
    <!-- Top Header & Navigation Bar -->
    <div class="bg-white border border-slate-200 p-5 sm:p-6 rounded-3xl flex flex-wrap items-center justify-between gap-4 shadow-sm">
      <div class="flex items-center gap-3.5">
        <img src="{{ asset('logo.svg') }}" alt="CampusLynk Logo" class="w-12 h-12 object-contain rounded-2xl bg-slate-900 p-2 shadow-sm shrink-0" />
        <div>
          <div class="flex items-center gap-2">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">System User Directory &amp; Credentials</h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">Super Admin</span>
          </div>
          <p class="text-xs text-slate-500 mt-0.5">Live view of registered login IDs, names, roles, and plaintext passwords across the institutional database.</p>
        </div>
      </div>

      <div class="flex items-center gap-2.5 flex-wrap no-print">
        <button onclick="window.print()" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-semibold text-sm transition-all flex items-center gap-2 cursor-pointer border border-slate-200 shadow-2xs">
          <span class="material-symbols-rounded text-lg text-slate-500">print</span>
          <span>Print Directory</span>
        </button>
        <a href="/dashboard/superadmin" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-all flex items-center gap-2 no-underline shadow-sm">
          <span class="material-symbols-rounded text-lg">arrow_back</span>
          <span>Control Desk</span>
        </a>
        <a href="{{ url('/logout') }}" onclick="return confirm('Are you sure you want to sign out of the system?')" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl font-semibold text-sm transition-all flex items-center gap-2 no-underline border border-rose-200/60">
          <span class="material-symbols-rounded text-lg">logout</span>
          <span>Sign Out</span>
        </a>
      </div>
    </div>

    <!-- Search & Filter Controls Bar -->
    <div class="bg-white border border-slate-200 p-4 sm:p-5 rounded-2xl flex flex-wrap items-center justify-between gap-4 shadow-sm no-print">
      <div class="flex items-center gap-2 w-full md:w-96 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm">
        <span class="material-symbols-rounded text-slate-400 text-lg">search</span>
        <input 
          type="text" 
          id="searchInput" 
          onkeyup="filterTables()" 
          placeholder="Search by name, mobile ID, register no, branch..." 
          class="w-full bg-transparent text-slate-900 outline-none text-sm placeholder:text-slate-400"
        >
      </div>

      <div class="flex items-center gap-2 flex-wrap">
        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Quick Navigation:</span>
        <a href="#staffSection" class="px-3.5 py-2 bg-slate-50 hover:bg-slate-100 rounded-xl text-xs font-bold text-slate-700 no-underline border border-slate-200 flex items-center gap-1.5 transition">
          <span class="material-symbols-rounded text-base text-blue-600">badge</span>
          <span>Staff Accounts ({{ count($staff) }})</span>
        </a>
        <a href="#studentSection" class="px-3.5 py-2 bg-slate-50 hover:bg-slate-100 rounded-xl text-xs font-bold text-slate-700 no-underline border border-slate-200 flex items-center gap-1.5 transition">
          <span class="material-symbols-rounded text-base text-sky-600">school</span>
          <span>Student Accounts ({{ count($students) }})</span>
        </a>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. STAFF PROFILES TABLE                                                   -->
    <!-- ========================================================================= -->
    <div id="staffSection" class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 space-y-4 shadow-sm print-border">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
            <span class="material-symbols-rounded text-xl">badge</span>
          </div>
          <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">
              Staff, Faculty &amp; Executive Credentials
            </h2>
            <span class="text-xs font-mono text-slate-400">Database Table: `staff_profiles`</span>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-xs font-semibold px-3 py-1 bg-slate-100 text-slate-700 rounded-full border border-slate-200">
            Total: {{ count($staff) }} records
          </span>
        </div>
      </div>

      <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left text-sm border-collapse" id="staffTable">
          <thead>
            <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 text-xs uppercase tracking-wider">
              <th class="py-3 px-4">Mobile (Login ID)</th>
              <th class="py-3 px-4">Full Name</th>
              <th class="py-3 px-4">Designation / Role</th>
              <th class="py-3 px-4">Branch</th>
              <th class="py-3 px-4">Institutional Email</th>
              <th class="py-3 px-4">Password</th>
              <th class="py-3 px-4 text-center">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-800">
            @forelse($staff as $s)
            <tr class="hover:bg-slate-50/80 transition-colors">
              <td class="py-3 px-4 font-mono font-bold text-blue-600">{{ $s->mobile_no }}</td>
              <td class="py-3 px-4 font-semibold text-slate-900">{{ $s->name }}</td>
              <td class="py-3 px-4">
                <span class="px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                  {{ str_replace('_', ' ', $s->designation) }}
                </span>
              </td>
              <td class="py-3 px-4 font-semibold text-slate-600">{{ $s->branch ?: 'General' }}</td>
              <td class="py-3 px-4 text-xs font-mono text-slate-500">{{ $s->email ?: 'N/A' }}</td>
              <td class="py-3 px-4">
                <span class="font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 select-all">
                  {{ $s->password }}
                </span>
              </td>
              <td class="py-3 px-4 text-center">
                @if(strtolower($s->account_status ?? '') === 'approved')
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">Approved</span>
                @elseif(strtolower($s->account_status ?? '') === 'suspended')
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">Suspended</span>
                @else
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">{{ $s->account_status ?: 'Pending' }}</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="py-8 text-center text-slate-400 text-sm">No staff accounts registered in database.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. STUDENT PROFILES TABLE                                                 -->
    <!-- ========================================================================= -->
    <div id="studentSection" class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 space-y-4 shadow-sm print-border">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-100 gap-2">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold">
            <span class="material-symbols-rounded text-xl">school</span>
          </div>
          <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">
              Student Accounts &amp; Access Passwords
            </h2>
            <span class="text-xs font-mono text-slate-400">Database Table: `students`</span>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-xs font-semibold px-3 py-1 bg-slate-100 text-slate-700 rounded-full border border-slate-200">
            Total: {{ count($students) }} records
          </span>
        </div>
      </div>

      <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left text-sm border-collapse" id="studentTable">
          <thead>
            <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200 text-xs uppercase tracking-wider">
              <th class="py-3 px-4">Register No</th>
              <th class="py-3 px-4">Admission No</th>
              <th class="py-3 px-4">Student Name</th>
              <th class="py-3 px-4">Branch</th>
              <th class="py-3 px-4 text-center">Semester</th>
              <th class="py-3 px-4">Email</th>
              <th class="py-3 px-4">Password</th>
              <th class="py-3 px-4 text-center">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-800">
            @forelse($students as $st)
            <tr class="hover:bg-slate-50/80 transition-colors">
              <td class="py-3 px-4 font-mono font-bold text-sky-600">{{ $st->reg_no }}</td>
              <td class="py-3 px-4 font-mono text-slate-600">{{ $st->adm_no ?: 'N/A' }}</td>
              <td class="py-3 px-4 font-semibold text-slate-900">{{ $st->name }}</td>
              <td class="py-3 px-4 font-semibold text-slate-600">{{ $st->branch }}</td>
              <td class="py-3 px-4 text-center">
                <span class="px-2 py-0.5 bg-slate-100 rounded text-xs font-bold text-slate-700">S{{ $st->semester }}</span>
              </td>
              <td class="py-3 px-4 text-xs font-mono text-slate-500">{{ $st->email ?: 'N/A' }}</td>
              <td class="py-3 px-4">
                <span class="font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 select-all">
                  {{ $st->password }}
                </span>
              </td>
              <td class="py-3 px-4 text-center">
                @if(strtolower($st->status ?? '') === 'approved')
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">Approved</span>
                @else
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">{{ $st->status ?: 'Pending' }}</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="py-8 text-center text-slate-400 text-sm">No student accounts registered in database.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script>
    function filterTables() {
      const q = document.getElementById('searchInput').value.toLowerCase().trim();
      
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

    // Prevent back-button viewing after logout
    window.addEventListener('pageshow', function (event) {
      if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
        window.location.reload(true);
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      if (window.lucide) window.lucide.createIcons();
    });
  </script>
</body>
</html>
