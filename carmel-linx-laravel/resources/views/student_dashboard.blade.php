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
        <div class="bg-slate-950/30 border border-slate-800/40 rounded-2xl p-8 text-center fade-up" id="examAreaIdle">
          <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-500/10 text-blue-400 mb-4">
            <span class="material-symbols-rounded text-3xl">school</span>
          </div>
          <h3 class="font-black text-slate-200 text-base mb-2">Online Exam Module</h3>
          <p class="text-xs text-slate-400 leading-relaxed max-w-md mx-auto mb-6">
            Your active online tests from the <strong class="text-slate-300">{{ session('classroomId', 'your class') }}</strong> will appear here when scheduled by your Tutor or Faculty.
          </p>

          <!-- List Active Tests here -->
          <div id="studentActiveTestsList" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-3xl mx-auto text-left">
            <!-- Dynamically populated -->
          </div>
        </div>

        <!-- LIVE TEST ENGINE MODAL (Hidden by default) -->
        <div id="testEngineModal" class="hidden fixed inset-0 z-50 bg-slate-950 flex flex-col">
          <!-- Top Bar -->
          <div class="h-14 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-3">
              <span class="material-symbols-rounded text-purple-500 text-2xl">devices</span>
              <div>
                <h3 id="liveTestName" class="font-bold text-sm text-white leading-tight">Test Name</h3>
                <span class="text-[10px] text-slate-400 font-mono" id="liveTestReg">{{ session('userId') }}</span>
              </div>
            </div>
            <div class="flex items-center gap-4">
              <div class="bg-slate-950 border border-slate-800 px-4 py-1.5 rounded-full flex items-center gap-2 text-sm font-bold shadow-inner">
                <span class="material-symbols-rounded text-red-400 text-lg">timer</span>
                <span id="liveTimer" class="text-red-400 font-mono tracking-widest">00:00:00</span>
              </div>
              <button onclick="submitTest()" class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-1.5 rounded-full font-bold text-xs transition-premium shadow-lg shadow-purple-600/20">Submit Final</button>
            </div>
          </div>

          <!-- Question Area -->
          <div class="flex-grow overflow-y-auto p-6 md:p-12" id="testQuestionsContainer">
             <!-- Render questions here -->
          </div>
        </div>

        <!-- TEST RESULT MODAL (Hidden by default) -->
        <div id="testResultModal" class="hidden fixed inset-0 z-50 bg-slate-900/95 backdrop-blur-sm flex items-center justify-center p-4">
          <div class="bg-slate-950 border border-slate-800 rounded-3xl p-8 max-w-md w-full shadow-2xl text-center transform scale-95 transition-premium" id="resultModalBox">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-400 mb-4 border border-emerald-500/20">
              <span class="material-symbols-rounded text-4xl">verified</span>
            </div>
            <h2 class="text-2xl font-black text-white mb-1">Test Completed!</h2>
            <p class="text-sm text-slate-400 mb-6">Your responses have been saved securely.</p>
            
            <div class="grid grid-cols-2 gap-4 mb-8">
              <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-4">
                <span class="text-[10px] uppercase font-black tracking-wider text-slate-500 block mb-1">Total Score</span>
                <span class="text-3xl font-black text-emerald-400" id="resultScore">0/0</span>
              </div>
              <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-4">
                <span class="text-[10px] uppercase font-black tracking-wider text-slate-500 block mb-1">Percentage</span>
                <span class="text-3xl font-black text-blue-400" id="resultPercent">0%</span>
              </div>
            </div>

            <button onclick="closeResultModal()" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold text-sm transition-premium">Return to Dashboard</button>
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

    // Init stub stats and load tests
    document.addEventListener('DOMContentLoaded', () => {
      loadStudentTests();
    });

    // TEST ENGINE LOGIC
    let currentTestId = null;
    let timerInterval = null;
    let endTimeMs = null;

    function loadStudentTests() {
      fetch('/api/student/online-tests')
        .then(res => res.json())
        .then(data => {
          let statPending = 0;
          let statSubmitted = 0;
          let container = document.getElementById('studentActiveTestsList');
          if (data.status === 'SUCCESS' && data.data.length > 0) {
            let html = '';
            data.data.forEach(t => {
              if (t.my_attempts > 0) statSubmitted++;
              if (t.can_take) statPending++;

              let actionHtml = '';
              if (t.can_take) {
                actionHtml = `<button onclick="startOnlineTest('${t.test_id}')" class="w-full py-2 bg-purple-600/80 hover:bg-purple-500 text-white rounded font-bold text-xs transition-premium">Start Test</button>`;
              } else {
                actionHtml = `<div class="w-full py-2 bg-emerald-900/40 text-emerald-400 rounded font-bold text-xs text-center border border-emerald-800/50">Best Score: ${t.best_score}</div>`;
              }

              html += `
                <div class="bg-slate-900/80 border border-slate-700/60 p-4 rounded-xl flex flex-col justify-between">
                  <div>
                    <h4 class="font-black text-sm text-slate-200 mb-1">${t.test_name}</h4>
                    <p class="text-[10px] text-slate-400 mb-3 font-mono">Subject: ${t.subject_code} | Duration: ${t.duration}m</p>
                    <div class="flex gap-2 mb-4 text-[9px] font-bold">
                      <span class="bg-slate-800 px-2 py-0.5 rounded text-slate-300">MCQs: ${t.mcq_count}</span>
                      <span class="bg-slate-800 px-2 py-0.5 rounded text-slate-300">Attempts: ${t.my_attempts}/${t.max_attempts}</span>
                    </div>
                  </div>
                  ${actionHtml}
                </div>
              `;
            });
            container.innerHTML = html;
          } else {
            container.innerHTML = `<div class="col-span-full p-4 bg-slate-900/60 border border-slate-800/60 rounded-xl text-center text-sm text-slate-500">No active tests available right now.</div>`;
          }

          document.getElementById('statActiveTests').innerText = data.data.length || '0';
          document.getElementById('statPending').innerText = statPending;
          document.getElementById('statSubmitted').innerText = statSubmitted;
        });
    }

    function startOnlineTest(testId) {
      if(!confirm("Are you sure you want to start this test? The timer will begin immediately.")) return;
      
      fetch(`/api/student/online-tests/${testId}/start`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          currentTestId = testId;
          renderTestEngine(data.questions, data.duration);
        } else {
          alert(data.message || "Could not start test.");
        }
      });
    }

    function renderTestEngine(questions, durationMins) {
      document.getElementById('examAreaIdle').classList.add('hidden');
      document.getElementById('testEngineModal').classList.remove('hidden');

      let html = '<div class="max-w-3xl mx-auto space-y-6 pb-20">';
      questions.forEach((q, idx) => {
        let optionsHtml = '';
        q.options.forEach((opt, oIdx) => {
          optionsHtml += `
            <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-700/50 bg-slate-900/50 cursor-pointer hover:border-purple-500/50 hover:bg-slate-800 transition-premium">
              <input type="radio" name="q_${idx}" value="${opt}" class="w-4 h-4 text-purple-500 bg-slate-950 border-slate-600 focus:ring-purple-600">
              <span class="text-sm text-slate-300">${opt}</span>
            </label>
          `;
        });
        html += `
          <div class="bg-slate-950 border border-slate-800 rounded-xl p-6 shadow-lg">
             <div class="flex items-start gap-4 mb-4">
               <span class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-500/10 text-purple-400 flex items-center justify-center font-black text-sm border border-purple-500/20">${idx+1}</span>
               <h4 class="text-base font-bold text-slate-100 mt-1">${q.q}</h4>
             </div>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-12">
               ${optionsHtml}
             </div>
          </div>
        `;
      });
      html += '</div>';
      document.getElementById('testQuestionsContainer').innerHTML = html;

      // Start Timer
      endTimeMs = Date.now() + (durationMins * 60 * 1000);
      timerInterval = setInterval(updateTimer, 1000);
      updateTimer();
    }

    function updateTimer() {
      let now = Date.now();
      let diff = endTimeMs - now;
      if (diff <= 0) {
        clearInterval(timerInterval);
        document.getElementById('liveTimer').innerText = "00:00:00";
        alert("Time is up! Auto-submitting your test.");
        submitTest();
        return;
      }
      
      let h = Math.floor(diff / (1000 * 60 * 60));
      let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      let s = Math.floor((diff % (1000 * 60)) / 1000);
      
      document.getElementById('liveTimer').innerText = 
        (h < 10 ? '0'+h : h) + ':' + 
        (m < 10 ? '0'+m : m) + ':' + 
        (s < 10 ? '0'+s : s);
    }

    function submitTest() {
      if(!currentTestId) return;
      if(timerInterval) clearInterval(timerInterval);

      // Collect answers
      const formContainers = document.getElementById('testQuestionsContainer').querySelectorAll('.bg-slate-950');
      let answers = {};
      formContainers.forEach((container, idx) => {
        let checked = container.querySelector(`input[name="q_${idx}"]:checked`);
        answers[idx] = checked ? checked.value : null;
      });

      fetch(`/api/student/online-tests/${currentTestId}/submit`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ answers })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          // Hide engine, show result modal
          document.getElementById('testEngineModal').classList.add('hidden');
          document.getElementById('testResultModal').classList.remove('hidden');
          setTimeout(() => document.getElementById('resultModalBox').classList.remove('scale-95'), 50);

          document.getElementById('resultScore').innerText = `${data.summary.score}/${data.summary.total}`;
          document.getElementById('resultPercent').innerText = `${data.summary.percentage}%`;
        } else {
          alert(data.message || "Submission failed.");
        }
      });
    }

    function closeResultModal() {
      document.getElementById('testResultModal').classList.add('hidden');
      document.getElementById('examAreaIdle').classList.remove('hidden');
      loadStudentTests(); // refresh the list
    }
  </script>

</body>
</html>
