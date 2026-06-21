<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx — Student Portal</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.4s ease both; }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col md:flex-row overflow-hidden">

  <!-- Sidebar -->
  <aside class="w-full md:w-64 bg-slate-950 flex-shrink-0 flex flex-col border-r border-slate-800/80 z-20 shadow-xl">
    <!-- Branding -->
    <div class="p-6 border-b border-slate-800/60 flex items-center gap-3">
      <div class="bg-gradient-to-br from-blue-500 to-sky-600 text-white font-black rounded-xl w-10 h-10 flex items-center justify-center text-lg shadow-lg shadow-blue-500/20">CL</div>
      <div>
        <h2 class="font-extrabold text-sm tracking-wide">Carmel Linx</h2>
        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Student Portal</span>
      </div>
    </div>

    <!-- Profile Card -->
    <div class="p-4 bg-slate-900/40 border-b border-slate-800/40">
      <div class="flex items-center gap-3">
        @if(session('userPhoto'))
          <img src="{{ session('userPhoto') }}" class="w-11 h-11 rounded-full border border-slate-700 object-cover shadow-inner">
        @else
          <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-600 to-sky-700 flex items-center justify-center font-black text-sm shadow">
            {{ strtoupper(substr(session('userName','S'), 0, 2)) }}
          </div>
        @endif
        <div class="overflow-hidden">
          <span class="font-bold text-xs block truncate text-slate-200">{{ session('userName') }}</span>
          <span class="text-[10px] font-bold text-teal-400 block font-mono">{{ session('userId') }}</span>
          <span class="text-[9px] text-slate-500 font-semibold">{{ session('userBranch') }} — Student</span>
        </div>
      </div>
    </div>

    <!-- Nav -->
    <nav class="flex-grow p-4 space-y-1.5">
      <button id="navExams" onclick="switchPanel('exams')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500">
        <span class="material-symbols-rounded text-lg">quiz</span> Active Exams
      </button>
      <button id="navMarks" onclick="switchPanel('marks')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">bar_chart_4_bars</span> My Marks
      </button>
      <button id="navProfile" onclick="switchPanel('profile')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer">
        <span class="material-symbols-rounded text-lg">manage_accounts</span> My Profile
      </button>
    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-slate-800/80">
      <a href="/logout" class="w-full py-3 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-xs flex items-center justify-center gap-2 cursor-pointer no-underline text-center text-slate-300 transition-premium">
        <span class="material-symbols-rounded text-base">logout</span> Sign Out
      </a>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-grow flex flex-col overflow-hidden">

    <!-- Top Header -->
    <header class="h-16 border-b border-slate-800/60 bg-slate-900/60 backdrop-blur-md flex items-center justify-between px-6 md:px-8 z-10">
      <h1 id="panelTitle" class="text-lg font-extrabold text-slate-100 tracking-tight">Active Exams</h1>
      <div class="flex items-center gap-3">
        <span class="text-xs text-slate-500 font-mono hidden md:block">Classroom: <strong class="text-slate-300">{{ session('classroomId', '—') }}</strong></span>
        <span class="text-xs text-slate-500 font-mono hidden md:block">Branch: <strong class="text-slate-300">{{ session('userBranch', '—') }}</strong></span>
      </div>
    </header>

    <!-- Panels -->
    <div class="flex-grow overflow-y-auto p-6 md:p-8">

      <!-- PANEL: ACTIVE EXAMS -->
      <div id="panelExams" class="fade-up">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
          <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4">
            <div class="bg-blue-500/10 text-blue-400 p-3 rounded-xl"><span class="material-symbols-rounded text-xl">quiz</span></div>
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Active Tests</span>
              <span id="statActiveTests" class="text-xl font-black text-white">—</span>
            </div>
          </div>
          <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4">
            <div class="bg-green-500/10 text-green-400 p-3 rounded-xl"><span class="material-symbols-rounded text-xl">check_circle</span></div>
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Submitted</span>
              <span id="statSubmitted" class="text-xl font-black text-white">—</span>
            </div>
          </div>
          <div class="bg-slate-950/40 border border-slate-800/60 p-5 rounded-2xl flex items-center gap-4">
            <div class="bg-amber-500/10 text-amber-400 p-3 rounded-xl"><span class="material-symbols-rounded text-xl">schedule</span></div>
            <div>
              <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider block">Pending</span>
              <span id="statPending" class="text-xl font-black text-white">—</span>
            </div>
          </div>
        </div>

        <!-- Exam Area -->
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-8 text-center fade-up">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-500/10 text-blue-400 mb-4">
            <span class="material-symbols-rounded text-3xl">school</span>
          </div>
          <h3 class="font-black text-slate-200 text-base mb-2">Online Exam Module</h3>
          <p class="text-xs text-slate-400 leading-relaxed max-w-md mx-auto">
            Your active online tests from the <strong class="text-slate-300">{{ session('classroomId', 'your class') }}</strong> will appear here when scheduled by your Tutor or Faculty. Check back when an exam is announced.
          </p>
          <div class="mt-6 p-4 bg-slate-900/60 border border-slate-800/60 rounded-xl text-left max-w-sm mx-auto">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Your Details</p>
            <div class="space-y-1.5">
              <div class="flex justify-between text-xs">
                <span class="text-slate-500">Register No</span>
                <span class="font-mono font-bold text-slate-200">{{ session('userId') }}</span>
              </div>
              <div class="flex justify-between text-xs">
                <span class="text-slate-500">Branch</span>
                <span class="font-bold text-slate-200">{{ session('userBranch') }}</span>
              </div>
              <div class="flex justify-between text-xs">
                <span class="text-slate-500">Classroom</span>
                <span class="font-mono font-bold text-slate-200">{{ session('classroomId', '—') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- PANEL: MY MARKS -->
      <div id="panelMarks" class="hidden fade-up">
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-8 text-center">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-sky-500/10 text-sky-400 mb-4">
            <span class="material-symbols-rounded text-3xl">bar_chart_4_bars</span>
          </div>
          <h3 class="font-black text-slate-200 text-base mb-2">Academic Performance</h3>
          <p class="text-xs text-slate-400 leading-relaxed max-w-md mx-auto">
            Your subject-wise marks, CO attainment levels, and PO-PSO mapping will be visible here after evaluation. This module is under active development.
          </p>
          <div class="mt-6 grid grid-cols-3 gap-4 max-w-sm mx-auto">
            <div class="bg-slate-900/60 border border-slate-800/60 p-4 rounded-xl text-center">
              <span class="text-2xl font-black text-blue-400">—</span>
              <span class="text-[10px] text-slate-500 block mt-1 font-bold">CIA Marks</span>
            </div>
            <div class="bg-slate-900/60 border border-slate-800/60 p-4 rounded-xl text-center">
              <span class="text-2xl font-black text-green-400">—</span>
              <span class="text-[10px] text-slate-500 block mt-1 font-bold">Attendance</span>
            </div>
            <div class="bg-slate-900/60 border border-slate-800/60 p-4 rounded-xl text-center">
              <span class="text-2xl font-black text-amber-400">—</span>
              <span class="text-[10px] text-slate-500 block mt-1 font-bold">CO Level</span>
            </div>
          </div>
        </div>
      </div>

      <!-- PANEL: MY PROFILE -->
      <div id="panelProfile" class="hidden fade-up">
        <div class="max-w-2xl mx-auto space-y-6">

          <!-- Profile Header Card -->
          <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6 flex items-center gap-5">
            @if(session('userPhoto'))
              <img src="{{ session('userPhoto') }}" class="w-20 h-20 rounded-2xl object-cover border border-slate-700 shadow-lg">
            @else
              <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-600 to-sky-700 flex items-center justify-center font-black text-2xl shadow-lg">
                {{ strtoupper(substr(session('userName','S'), 0, 2)) }}
              </div>
            @endif
            <div>
              <h3 class="font-black text-white text-lg">{{ session('userName') }}</h3>
              <p class="text-sm text-slate-400 font-semibold mt-0.5">{{ session('userId') }} · {{ session('userBranch') }}</p>
              <span class="mt-2 inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20">Student</span>
            </div>
          </div>

          <!-- Info Grid -->
          <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6">
            <h3 class="text-xs font-black text-slate-300 uppercase tracking-wider border-b border-slate-800/60 pb-3 mb-4">Academic Information</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="bg-slate-900/40 rounded-xl p-4">
                <dt class="text-[10px] text-slate-400 font-black uppercase tracking-wider">Register Number</dt>
                <dd class="font-mono font-bold text-white mt-1">{{ session('userId') }}</dd>
              </div>
              <div class="bg-slate-900/40 rounded-xl p-4">
                <dt class="text-[10px] text-slate-400 font-black uppercase tracking-wider">Branch</dt>
                <dd class="font-bold text-white mt-1">{{ session('userBranch') }}</dd>
              </div>
              <div class="bg-slate-900/40 rounded-xl p-4">
                <dt class="text-[10px] text-slate-400 font-black uppercase tracking-wider">Classroom ID</dt>
                <dd class="font-mono font-bold text-white mt-1">{{ session('classroomId', '—') }}</dd>
              </div>
              <div class="bg-slate-900/40 rounded-xl p-4">
                <dt class="text-[10px] text-slate-400 font-black uppercase tracking-wider">Role</dt>
                <dd class="font-bold text-teal-400 mt-1">Student</dd>
              </div>
            </dl>
          </div>

          <!-- Change Password Section -->
          <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-6">
            <h3 class="text-xs font-black text-slate-300 uppercase tracking-wider border-b border-slate-800/60 pb-3 mb-4">Change Password</h3>
            <div class="space-y-3">
              <div>
                <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">Current Password</label>
                <input type="password" id="oldPwd" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 outline-none" placeholder="Enter current password">
              </div>
              <div>
                <label class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1.5">New Password</label>
                <input type="password" id="newPwd" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-3 py-2.5 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 outline-none" placeholder="At least 6 characters">
              </div>
              <div id="pwdAlert" class="hidden p-3 rounded-xl text-xs font-bold border"></div>
              <button onclick="changePassword()" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition-premium cursor-pointer">Update Password</button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </main>

  <script>
    function switchPanel(panelId) {
      const panels = ['exams', 'marks', 'profile'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        if (id === panelId) {
          if (el) { el.classList.remove('hidden'); el.classList.add('fade-up'); }
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (el) el.classList.add('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
        }
      });
      const titles = { exams: 'Active Exams', marks: 'My Academic Marks', profile: 'My Profile' };
      document.getElementById('panelTitle').innerText = titles[panelId];
    }

    function changePassword() {
      const oldPwd = document.getElementById('oldPwd').value.trim();
      const newPwd = document.getElementById('newPwd').value.trim();
      const alert = document.getElementById('pwdAlert');
      if (!oldPwd || !newPwd) {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Please fill in both fields.";
        return;
      }
      if (newPwd.length < 6) {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "New password must be at least 6 characters.";
        return;
      }
      fetch('/api/student/change-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ oldPassword: oldPwd, newPassword: newPwd })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "Password updated successfully.";
          document.getElementById('oldPwd').value = '';
          document.getElementById('newPwd').value = '';
        } else {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message || 'Password change failed.';
        }
      })
      .catch(() => {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = 'Request failed. Please try again.';
      });
    }

    // Init stub stats
    document.addEventListener('DOMContentLoaded', () => {
      document.getElementById('statActiveTests').innerText = '0';
      document.getElementById('statSubmitted').innerText = '0';
      document.getElementById('statPending').innerText = '0';
    });
  </script>

</body>
</html>
