<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Remedial Sessions Workspace - Carmel Linx</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,600,1,0" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .transition-premium { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }

    /* Clean overrides to enlarge fonts for readability across Remedial Sessions workspace */
    body, button, select, input, textarea, table, th, td, div, p, span, a {
        font-size: 13px !important;
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-size: 1.15rem !important;
        font-weight: 800 !important;
    }

    button, .btn {
        font-size: 13px !important;
        font-weight: bold !important;
    }
    
    /* Headers specific adjustments */
    header h1 {
        font-size: 1.25rem !important;
        font-weight: 900 !important;
    }
    
    header p {
        font-size: 10px !important;
        font-weight: bold !important;
        letter-spacing: 0.1em !important;
    }
  </style>
</head>
<body class="bg-slate-950 text-slate-300 min-h-screen flex flex-col relative overflow-x-hidden selection:bg-purple-500/30">

  <!-- Fixed Top Header -->
  <header class="bg-slate-900 border-b border-slate-800/80 sticky top-0 z-40 shadow-2xl">
    <div class="px-6 h-16 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <a href="/dashboard/lecturer" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-slate-300 transition-premium">
          <span class="material-symbols-rounded text-[10px]">arrow_back</span>
        </a>
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 text-white font-black rounded-lg w-8 h-8 flex items-center justify-center text-[10px] shadow-lg shadow-purple-500/20">RS</div>
        <div>
          <h1 class="font-extrabold text-[10px] text-white tracking-wide">Remedial Sessions</h1>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Coaching & Diagnostics</p>
        </div>
      </div>
    </div>
  </header>

  <main class="flex-grow p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-8">

    <!-- Dashboard Tabs -->
    <div class="flex gap-4 border-b border-slate-800/60 pb-4">
      <button onclick="switchTab('roomsList')" id="tab_roomsList" class="px-6 py-2 rounded-xl text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20 transition-premium">Active Rooms</button>
      <button onclick="switchTab('createRoom')" id="tab_createRoom" class="px-6 py-2 rounded-xl text-[10px] font-bold bg-slate-900 text-slate-400 border border-slate-800 hover:bg-slate-800 transition-premium">Create New Room</button>
    </div>

    <!-- Active Rooms Panel -->
    <div id="panel_roomsList" class="space-y-6">
      <div id="roomsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Loaded via JS -->
      </div>
    </div>

    <!-- Create Room Panel -->
    <div id="panel_createRoom" class="hidden space-y-6">
      <div class="bg-slate-900/50 border border-slate-800/60 rounded-2xl p-6 shadow-xl">
        <h2 class="text-[10px] font-black text-white mb-4">Step 1: Select Subject</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="col-span-2">
            <select id="subjectSelect" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-4 py-3 text-[10px] text-white focus:border-purple-500 outline-none">
              <option value="">Select a Subject...</option>
            </select>
          </div>
          <button onclick="fetchStudentPerformance()" class="bg-purple-600 hover:bg-purple-500 text-white rounded-xl font-bold text-[10px] px-6 py-3 transition-premium shadow-lg shadow-purple-500/20">Analyze Performance</button>
        </div>
        
        <div id="performanceSection" class="hidden space-y-4">
          <div class="flex items-center justify-between mt-8 mb-4">
            <h2 class="text-[10px] font-black text-white">Step 2: Identify Weak Students</h2>
            <div class="flex items-center gap-3">
              <label class="text-[10px] text-slate-400">Auto-Select Below Marks:</label>
              <input type="number" id="thresholdMark" value="20" class="w-20 bg-slate-950 border border-slate-700/60 rounded-lg px-3 py-1 text-[10px] text-white outline-none">
              <button onclick="applyThreshold()" class="bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg px-3 py-1.5 text-[10px] font-bold transition-premium">Apply</button>
            </div>
          </div>
          
          <div class="overflow-x-auto rounded-xl border border-slate-800/60">
            <table class="w-full text-left text-[10px] border-collapse">
              <thead>
                <tr class="bg-slate-950 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-4 w-12 text-center">
                    <input type="checkbox" id="selectAllStudents" onchange="toggleAllStudents()" class="w-4 h-4 text-purple-500 rounded bg-slate-900 border-slate-700">
                  </th>
                  <th class="p-4">Reg No</th>
                  <th class="p-4">Name</th>
                  <th class="p-4 text-right">Total Marks</th>
                </tr>
              </thead>
              <tbody id="performanceTableBody" class="divide-y divide-slate-800/40">
              </tbody>
            </table>
          </div>

          <div class="flex justify-end pt-4">
            <button onclick="provisionRoom()" class="bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-[10px] px-8 py-3 transition-premium shadow-lg shadow-emerald-500/20">Provision Remedial Room</button>
          </div>
        </div>
      </div>
    </div>

  </main>

  <!-- View Room Modal -->
  <div id="roomModal" class="hidden fixed inset-0 z-50 bg-slate-950 flex flex-col">
    <div class="h-16 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-6 shrink-0 shadow-lg">
      <div class="flex items-center gap-3">
        <span class="material-symbols-rounded text-purple-400 text-base">school</span>
        <div>
          <h3 id="modalRoomTitle" class="font-bold text-[10px] text-white leading-tight">Remedial Class Room</h3>
          <span id="modalRoomSub" class="text-[10px] text-slate-400 font-mono block">Batch | Semester | Assessment Year</span>
        </div>
      </div>
      <button onclick="closeRoomModal()" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-xl font-bold text-[10px] transition-premium">Close</button>
    </div>
    
    <div class="flex-grow overflow-y-auto p-6 md:p-10">
      <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Foldable Students Panel -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
          <div onclick="toggleStudents()" class="p-4 flex items-center justify-between cursor-pointer hover:bg-slate-800/50 transition-premium">
            <h4 class="font-black text-white text-[10px] flex items-center gap-2"><span class="material-symbols-rounded text-purple-400">group</span> Enrolled Students</h4>
            <span id="studentsIcon" class="material-symbols-rounded text-slate-400 transition-transform">expand_more</span>
          </div>
          <div id="studentsContent" class="hidden border-t border-slate-800/60 p-4 bg-slate-950/30">
            <ul id="roomStudentsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-[10px]">
            </ul>
          </div>
        </div>

        <!-- Room Tabs -->
        <div class="flex gap-4 border-b border-slate-800/60 pb-4">
          <button onclick="switchRoomTab('logs')" id="rtab_logs" class="px-5 py-2 rounded-xl text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 transition-premium">Session Logs</button>
          <button onclick="switchRoomTab('assessments')" id="rtab_assessments" class="px-5 py-2 rounded-xl text-[10px] font-bold bg-slate-900 text-slate-400 border border-slate-800 hover:bg-slate-800 transition-premium">Assessments</button>
        </div>

        <!-- Session Logs Panel -->
        <div id="rpanel_logs" class="space-y-6">
          <div class="flex justify-between items-center">
            <h4 class="font-black text-white text-[10px]">Class Logs</h4>
            <button onclick="toggleLogForm()" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-premium flex items-center gap-1 shadow-lg shadow-blue-500/20"><span class="material-symbols-rounded text-[10px]">add</span> New Log</button>
          </div>

          <div id="logFormContainer" class="hidden bg-slate-900 border border-slate-800 rounded-2xl p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Date</label>
                <input type="date" id="logDate" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-3 py-2 text-[10px] text-white outline-none">
              </div>
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Start Time</label>
                <input type="time" id="logStartTime" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-3 py-2 text-[10px] text-white outline-none">
              </div>
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Duration (Mins)</label>
                <input type="number" id="logDuration" value="60" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-3 py-2 text-[10px] text-white outline-none">
              </div>
              <div class="col-span-1 md:col-span-3">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Topic Covered</label>
                <input type="text" id="logTopic" placeholder="e.g. Kirchhoff's Laws Revision" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-3 py-2 text-[10px] text-white outline-none">
              </div>
            </div>
            
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Attendance (Check Present)</label>
            <div id="logAttendanceGrid" class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-4 max-h-40 overflow-y-auto bg-slate-950/50 p-3 rounded-lg border border-slate-800">
            </div>

            <button onclick="saveLog()" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-[10px] py-2.5 transition-premium shadow-lg shadow-emerald-500/20">Save Session Log</button>
          </div>

          <div class="overflow-hidden rounded-xl border border-slate-800/60 shadow-sm">
            <table class="w-full text-left text-[10px] border-collapse">
              <thead>
                <tr class="bg-slate-950 border-b border-slate-800/60 text-slate-400 font-bold">
                  <th class="p-3 w-8"></th>
                  <th class="p-3">Date</th>
                  <th class="p-3">Start Time</th>
                  <th class="p-3">Duration</th>
                  <th class="p-3 w-1/3">Topic</th>
                  <th class="p-3 text-right">Attendance</th>
                </tr>
              </thead>
              <tbody id="roomLogsList" class="divide-y divide-slate-800/40">
                <!-- Loaded via JS -->
              </tbody>
            </table>
          </div>
        </div>

        <!-- Assessments Panel -->
        <div id="rpanel_assessments" class="hidden space-y-6">
          <div class="flex justify-between items-center">
            <h4 class="font-black text-white text-[10px]">Remedial Assessments</h4>
            <button onclick="toggleAssessmentForm()" class="bg-amber-600 hover:bg-amber-500 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-premium flex items-center gap-1 shadow-lg shadow-amber-500/20"><span class="material-symbols-rounded text-[10px]">add</span> Create Test</button>
          </div>

          <div id="assessmentFormContainer" class="hidden bg-slate-900 border border-slate-800 rounded-2xl p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Type</label>
                <select id="assessType" onchange="toggleAssessFormFields()" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-3 py-2 text-[10px] text-white outline-none">
                  <option value="Written Test">Written Test (with COs)</option>
                  <option value="Online Test">Online Test (Linked)</option>
                  <option value="Assignment">Assignment (Manual Entry)</option>
                </select>
              </div>
              
              <div id="assessLinkContainer" class="hidden col-span-1 md:col-span-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Link Online Test</label>
                <select id="assessLinkTest" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-3 py-2 text-[10px] text-white outline-none">
                  <option value="">Select Test to Link...</option>
                </select>
              </div>

              <div id="assessMaxMarksContainer" class="col-span-1 md:col-span-2">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Max Marks (If Assignment)</label>
                <input type="number" id="assessMaxMarks" value="20" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-3 py-2 text-[10px] text-white outline-none">
              </div>

              <div class="col-span-1 md:col-span-3">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Test Title</label>
                <input type="text" id="assessTitle" placeholder="e.g. Weekly Improvement Test 1" class="w-full bg-slate-950 border border-slate-700/60 rounded-xl px-3 py-2 text-[10px] text-white outline-none">
              </div>

              <div id="assessCOContainer" class="col-span-1 md:col-span-3 bg-slate-950/50 p-4 rounded-xl border border-slate-800">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Define CO Max Marks (Leave blank if not applicable)</label>
                <div class="grid grid-cols-5 gap-3">
                  <div>
                    <span class="text-[10px] text-slate-500 font-bold block mb-1">CO1</span>
                    <input type="number" id="co1_marks" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-[10px] text-white outline-none text-center" placeholder="-">
                  </div>
                  <div>
                    <span class="text-[10px] text-slate-500 font-bold block mb-1">CO2</span>
                    <input type="number" id="co2_marks" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-[10px] text-white outline-none text-center" placeholder="-">
                  </div>
                  <div>
                    <span class="text-[10px] text-slate-500 font-bold block mb-1">CO3</span>
                    <input type="number" id="co3_marks" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-[10px] text-white outline-none text-center" placeholder="-">
                  </div>
                  <div>
                    <span class="text-[10px] text-slate-500 font-bold block mb-1">CO4</span>
                    <input type="number" id="co4_marks" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-[10px] text-white outline-none text-center" placeholder="-">
                  </div>
                  <div>
                    <span class="text-[10px] text-slate-500 font-bold block mb-1">CO5</span>
                    <input type="number" id="co5_marks" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2 py-1 text-[10px] text-white outline-none text-center" placeholder="-">
                  </div>
                </div>
              </div>
            </div>
            <button onclick="saveAssessment()" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-[10px] py-2.5 transition-premium shadow-lg shadow-emerald-500/20">Create Assessment</button>
          </div>

          <!-- Gradebook View -->
          <div id="gradebookContainer" class="hidden bg-slate-900 border border-slate-800 rounded-2xl p-5 mb-6">
            <div class="flex items-center justify-between mb-4">
              <div>
                <h4 id="gradebookTitle" class="font-black text-amber-400 text-[10px]">Enter Scores</h4>
                <p id="gradebookSub" class="text-[10px] text-slate-400 font-mono"></p>
              </div>
              <div class="flex items-center gap-3">
                <button id="btnPrintRemedialReport" onclick="printRemedialReport()" class="bg-purple-600/20 text-purple-400 hover:bg-purple-500 hover:text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-premium cursor-pointer">Print Report</button>
                <button id="btnSyncScores" onclick="syncOnlineScores()" class="hidden bg-blue-600/20 text-blue-400 hover:bg-blue-500 hover:text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-premium">Sync Online Scores</button>
                <button onclick="closeGradebook()" class="text-slate-500 hover:text-white transition-colors"><span class="material-symbols-rounded">close</span></button>
              </div>
            </div>
            
            <div class="overflow-x-auto rounded-xl border border-slate-800/60 mb-4">
              <table class="w-full text-left text-[10px] border-collapse">
                <thead>
                  <tr id="gradebookTableHead" class="bg-slate-950 border-b border-slate-800/60 text-slate-400 font-bold">
                    <!-- Dynamic -->
                  </tr>
                </thead>
                <tbody id="gradebookTableBody" class="divide-y divide-slate-800/40">
                </tbody>
              </table>
            </div>

            <button id="btnSaveScores" onclick="saveScores()" class="w-full bg-purple-600 hover:bg-purple-500 text-white rounded-xl font-bold text-[10px] py-2.5 transition-premium shadow-lg shadow-purple-500/20">Save All Scores</button>
          </div>

          <div id="assessmentsList" class="space-y-4">
            <!-- Loaded via JS -->
          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    let assignedSubjects = [];
    let currentStudentPerformance = [];
    let currentRoomId = null;
    let currentRoomStudents = [];

    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    };

    window.onload = () => {
      loadAssignedSubjects();
      loadRooms();
    };

    function switchTab(tabId) {
      document.getElementById('panel_roomsList').classList.add('hidden');
      document.getElementById('panel_createRoom').classList.add('hidden');
      
      document.getElementById('tab_roomsList').className = "px-6 py-2 rounded-xl text-[10px] font-bold bg-slate-900 text-slate-400 border border-slate-800 hover:bg-slate-800 transition-premium";
      document.getElementById('tab_createRoom').className = "px-6 py-2 rounded-xl text-[10px] font-bold bg-slate-900 text-slate-400 border border-slate-800 hover:bg-slate-800 transition-premium";

      document.getElementById('panel_' + tabId).classList.remove('hidden');
      document.getElementById('tab_' + tabId).className = "px-6 py-2 rounded-xl text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20 transition-premium";
    }

    function loadAssignedSubjects() {
      fetch('/api/remedial/assigned-subjects')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            assignedSubjects = data.subjects;
            const select = document.getElementById('subjectSelect');
            let html = '<option value="">Select a Subject...</option>';
            data.subjects.forEach((s, idx) => {
              html += `<option value="${idx}">${s.subject_code} - ${s.subject_name} (${s.batch_name})</option>`;
            });
            select.innerHTML = html;
          }
        });
    }

    function fetchStudentPerformance() {
      const idx = document.getElementById('subjectSelect').value;
      if (idx === '') return alert('Select a subject first');
      const subj = assignedSubjects[idx];

      fetch(`/api/remedial/student-performance?classroom_id=${subj.classroom_id}&subject_code=${subj.subject_code}`)
        .then(res => res.json())
        .then(data => {
          if(data.status === 'SUCCESS') {
            currentStudentPerformance = data.students;
            renderPerformanceGrid();
            document.getElementById('performanceSection').classList.remove('hidden');
          }
        });
    }

    function renderPerformanceGrid() {
      const tbody = document.getElementById('performanceTableBody');
      let html = '';
      currentStudentPerformance.forEach((s, i) => {
        html += `
          <tr class="hover:bg-slate-900/50 transition-colors">
            <td class="p-4 text-center">
              <input type="checkbox" value="${s.reg_no}" class="student-checkbox w-4 h-4 text-purple-500 rounded bg-slate-900 border-slate-700">
            </td>
            <td class="p-4 text-[10px] text-slate-400 font-mono">${s.reg_no}</td>
            <td class="p-4 text-[10px] font-bold text-slate-200">${s.name}</td>
            <td class="p-4 text-right text-[10px] font-black ${s.total_marks < 20 ? 'text-rose-400' : 'text-emerald-400'}">${s.total_marks}</td>
          </tr>
        `;
      });
      tbody.innerHTML = html;
    }

    function toggleAllStudents() {
      const isChecked = document.getElementById('selectAllStudents').checked;
      document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = isChecked);
    }

    function applyThreshold() {
      const threshold = parseFloat(document.getElementById('thresholdMark').value) || 0;
      document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
      currentStudentPerformance.forEach((s, i) => {
        if (s.total_marks < threshold) {
          const cb = document.querySelector(`.student-checkbox[value="${s.reg_no}"]`);
          if(cb) cb.checked = true;
        }
      });
    }

    function provisionRoom() {
      const idx = document.getElementById('subjectSelect').value;
      if (idx === '') return alert('Select a subject');
      const subj = assignedSubjects[idx];

      const selected = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => cb.value);
      if (selected.length === 0) return alert('Select at least one student.');

      fetch('/api/remedial/rooms', {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({
          classroom_id: subj.classroom_id,
          subject_code: subj.subject_code,
          students: selected
        })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          alert('Room Provisioned!');
          loadRooms();
          switchTab('roomsList');
        }
      });
    }

    function loadRooms() {
      fetch('/api/remedial/rooms')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const container = document.getElementById('roomsContainer');
            if (data.rooms.length === 0) {
              container.innerHTML = `<div class="col-span-full py-10 text-center text-slate-500 font-bold text-[10px]">No active remedial rooms.</div>`;
              return;
            }

            let html = '';
            data.rooms.forEach(r => {
              html += `
                <div class="bg-slate-900/50 border border-slate-800/60 rounded-2xl p-5 hover:border-purple-500/50 transition-premium cursor-pointer" onclick="openRoom('${r.room_id}')">
                  <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-full bg-purple-500/10 text-purple-400 flex items-center justify-center font-black text-[10px] border border-purple-500/20">${r.student_count}</div>
                    <span class="px-2 py-0.5 rounded border text-[10px] font-bold uppercase tracking-wider text-emerald-400 bg-emerald-500/10 border-emerald-500/20">${r.status}</span>
                  </div>
                  <h3 class="font-black text-white text-[10px]">${r.subject_code}</h3>
                  <p class="text-[10px] text-slate-400 font-bold mt-1 line-clamp-1">${r.subject_name}</p>
                  <p class="text-[10px] text-slate-500 font-mono mt-3">${r.batch_name}</p>
                </div>
              `;
            });
            container.innerHTML = html;
          }
        });
    }

    let currentAvailableTests = [];

    function openRoom(roomId) {
      currentRoomId = roomId;
      document.getElementById('logFormContainer').classList.add('hidden');
      document.getElementById('assessmentFormContainer').classList.add('hidden');
      document.getElementById('gradebookContainer').classList.add('hidden');
      switchRoomTab('logs');
      
      fetch(`/api/remedial/rooms/${roomId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const r = data.room;
            document.getElementById('modalRoomTitle').innerText = `Remedial Class Room - ${r.subject_code} ${r.subject_name}`;
            document.getElementById('modalRoomSub').innerText = `${r.batch_name} | Semester: ${r.semester} | Assessment Year: ${r.batch_year}`;
            currentRoomStudents = r.students;
            currentAvailableTests = r.available_tests || [];

            // Populate Test Dropdown
            let testHtml = '<option value="">Select Test to Link...</option>';
            currentAvailableTests.forEach(t => {
              testHtml += `<option value="${t.test_id}">${t.test_name} (${t.duration}m)</option>`;
            });
            document.getElementById('assessLinkTest').innerHTML = testHtml;

            // Render Students
            let sHtml = '';
            r.students.forEach(s => {
              sHtml += `<li class="p-3 bg-slate-900 border border-slate-800 rounded-xl flex justify-between items-center hover:border-purple-500/30 transition-premium">
                <div>
                  <p class="font-bold text-slate-300 text-[10px]">${s.name}</p>
                  <p class="text-[10px] font-mono text-slate-500">${s.reg_no}</p>
                </div>
                <button onclick="confirmRemoveStudent(this, '${s.reg_no}')" class="text-[10px] font-bold text-rose-500 hover:text-white hover:bg-rose-500 px-2 py-1 rounded transition-premium">Remove</button>
              </li>`;
            });
            document.getElementById('roomStudentsList').innerHTML = sHtml;

            // Render Logs (Foldable Table)
            let lHtml = '';
            if (r.logs.length === 0) lHtml = '<tr><td colspan="6" class="p-4 text-center text-slate-500 text-[10px]">No sessions logged yet.</td></tr>';
            r.logs.forEach((l, idx) => {
              let attCount = (l.attendance_data || []).length;
              lHtml += `
                <tr class="hover:bg-slate-900/50 transition-colors cursor-pointer" onclick="toggleLogDetails(${idx})">
                  <td class="p-3 w-8 text-center text-slate-500"><span id="logIcon_${idx}" class="material-symbols-rounded text-[10px] transition-transform">expand_more</span></td>
                  <td class="p-3 font-bold text-blue-400">${l.session_date}</td>
                  <td class="p-3 text-slate-300">${l.start_time || '--:--'}</td>
                  <td class="p-3 text-slate-400">${l.duration_minutes}m</td>
                  <td class="p-3 text-slate-300 truncate max-w-[150px]" title="${l.topic_covered}">${l.topic_covered || 'No topic specified'}</td>
                  <td class="p-3 text-right"><span class="text-[10px] text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded font-bold">${attCount} Present</span></td>
                </tr>
                <tr id="logDetails_${idx}" class="hidden bg-slate-950/50">
                  <td colspan="6" class="p-4 border-t border-slate-800/60">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Students Present:</p>
                    <div class="flex flex-wrap gap-2">
                      ${(l.attendance_data||[]).map(reg => {
                        let st = r.students.find(s => s.reg_no === reg);
                        let nameToShow = st ? st.name : reg;
                        return `<span class="px-2 py-1 bg-slate-900 border border-slate-700 rounded text-[10px] text-slate-300 font-medium">${nameToShow}</span>`;
                      }).join('')}
                    </div>
                  </td>
                </tr>
              `;
            });
            document.getElementById('roomLogsList').innerHTML = lHtml;

            // Prep Attendance form
            let attHtml = '';
            r.students.forEach(s => {
              attHtml += `<label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" value="${s.reg_no}" class="log-att-checkbox w-3 h-3 text-emerald-500 rounded bg-slate-900 border-slate-700" checked><span class="text-[10px] text-slate-400">${s.reg_no} - ${s.name}</span></label>`;
            });
            document.getElementById('logAttendanceGrid').innerHTML = attHtml;
            document.getElementById('logDate').valueAsDate = new Date();

            loadAssessments();

            document.getElementById('roomModal').classList.remove('hidden');
          }
        });
    }

    function toggleLogDetails(idx) {
      const el = document.getElementById(`logDetails_${idx}`);
      const icon = document.getElementById(`logIcon_${idx}`);
      if(el.classList.contains('hidden')){
        el.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
      } else {
        el.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
      }
    }

    function toggleStudents() {
      const content = document.getElementById('studentsContent');
      const icon = document.getElementById('studentsIcon');
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
      }
    }

    function switchRoomTab(tabId) {
      document.getElementById('rpanel_logs').classList.add('hidden');
      document.getElementById('rpanel_assessments').classList.add('hidden');
      
      document.getElementById('rtab_logs').className = "px-5 py-2 rounded-xl text-[10px] font-bold bg-slate-900 text-slate-400 border border-slate-800 hover:bg-slate-800 transition-premium";
      document.getElementById('rtab_assessments').className = "px-5 py-2 rounded-xl text-[10px] font-bold bg-slate-900 text-slate-400 border border-slate-800 hover:bg-slate-800 transition-premium";

      document.getElementById('rpanel_' + tabId).classList.remove('hidden');
      
      let tabColor = tabId === 'logs' ? 'blue' : 'amber';
      document.getElementById('rtab_' + tabId).className = `px-5 py-2 rounded-xl text-[10px] font-bold bg-${tabColor}-500/10 text-${tabColor}-400 border border-${tabColor}-500/20 transition-premium`;
    }

    function closeRoomModal() {
      document.getElementById('roomModal').classList.add('hidden');
    }

    function confirmRemoveStudent(btn, regNo) {
      if (btn.innerText === "Remove") {
        btn.innerText = "Confirm?";
        btn.classList.add('bg-rose-600', 'text-white');
        setTimeout(() => {
          if (btn && btn.innerText === "Confirm?") {
            btn.innerText = "Remove";
            btn.classList.remove('bg-rose-600', 'text-white');
          }
        }, 3000);
      } else {
        removeStudent(regNo);
      }
    }

    function removeStudent(regNo) {
      fetch(`/api/remedial/rooms/${currentRoomId}/students`, {
        method: 'DELETE',
        headers: headers,
        body: JSON.stringify({ reg_no: regNo })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') openRoom(currentRoomId);
      });
    }

    function toggleLogForm() {
      document.getElementById('logFormContainer').classList.toggle('hidden');
    }

    function saveLog() {
      const date = document.getElementById('logDate').value;
      const start = document.getElementById('logStartTime').value;
      const duration = document.getElementById('logDuration').value;
      const topic = document.getElementById('logTopic').value;
      const att = Array.from(document.querySelectorAll('.log-att-checkbox:checked')).map(cb => cb.value);

      if (!date || !topic) return alert('Date and Topic are required.');

      fetch(`/api/remedial/rooms/${currentRoomId}/logs`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({
          session_date: date,
          start_time: start,
          duration_minutes: duration,
          topic_covered: topic,
          attendance: att
        })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          openRoom(currentRoomId);
        }
      });
    }

    let currentAssessments = [];
    let currentAssessmentId = null;

    function loadAssessments() {
      fetch(`/api/remedial/rooms/${currentRoomId}/assessments`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentAssessments = data.assessments;
            const container = document.getElementById('assessmentsList');
            let html = '';
            if (currentAssessments.length === 0) html = '<p class="text-slate-500 text-[10px]">No assessments created yet.</p>';
            
            currentAssessments.forEach((a, idx) => {
              let gradedCount = (a.scores || []).length;
              html += `
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 flex justify-between items-center hover:border-amber-500/30 transition-premium">
                  <div>
                    <span class="px-2 py-0.5 rounded border text-[10px] font-bold uppercase tracking-wider text-amber-400 bg-amber-500/10 border-amber-500/20 mb-2 inline-block">${a.type}</span>
                    <h5 class="text-[10px] font-bold text-white">${a.title}</h5>
                    <p class="text-[10px] text-slate-400 font-mono mt-1">Max Marks: ${a.max_marks} | Graded: ${gradedCount}/${currentRoomStudents.length}</p>
                  </div>
                  <button onclick="openGradebook(${idx})" class="bg-slate-800 hover:bg-slate-700 text-white rounded-lg font-bold text-[10px] px-4 py-2 transition-premium shadow-lg shadow-slate-950/50">Enter Marks</button>
                </div>
              `;
            });
            container.innerHTML = html;
          }
        });
    }

    function toggleAssessmentForm() {
      document.getElementById('assessmentFormContainer').classList.toggle('hidden');
      toggleAssessFormFields();
    }

    function toggleAssessFormFields() {
      const type = document.getElementById('assessType').value;
      const coCont = document.getElementById('assessCOContainer');
      const linkCont = document.getElementById('assessLinkContainer');
      const marksCont = document.getElementById('assessMaxMarksContainer');

      if (type === 'Online Test') {
        coCont.classList.add('hidden');
        linkCont.classList.remove('hidden');
        marksCont.classList.add('hidden');
      } else if (type === 'Written Test') {
        coCont.classList.remove('hidden');
        linkCont.classList.add('hidden');
        marksCont.classList.remove('hidden');
      } else {
        coCont.classList.add('hidden');
        linkCont.classList.add('hidden');
        marksCont.classList.remove('hidden');
      }
    }

    function saveAssessment() {
      const type = document.getElementById('assessType').value;
      const marks = document.getElementById('assessMaxMarks').value;
      const title = document.getElementById('assessTitle').value;
      const link = document.getElementById('assessLinkTest').value;

      if (!title) return alert('Title is required.');

      let coStructure = null;
      if (type === 'Written Test') {
        coStructure = {};
        let hasCo = false;
        ['co1','co2','co3','co4','co5'].forEach(co => {
          let v = document.getElementById(co+'_marks').value;
          if(v) { coStructure[co.toUpperCase()] = parseFloat(v); hasCo = true; }
        });
        if(!hasCo) coStructure = null;
      }

      fetch(`/api/remedial/rooms/${currentRoomId}/assessments`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({ 
          type: type, 
          max_marks: type === 'Online Test' ? 100 : marks, 
          title: title,
          linked_test_id: type === 'Online Test' ? link : null,
          co_structure: coStructure
        })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          document.getElementById('assessTitle').value = '';
          document.getElementById('assessmentFormContainer').classList.add('hidden');
          loadAssessments();
        }
      });
    }

    function openGradebook(idx) {
      const a = currentAssessments[idx];
      currentAssessmentId = a.assessment_id;
      
      document.getElementById('gradebookTitle').innerText = a.title;
      document.getElementById('gradebookSub').innerText = `${a.type} - Max Marks: ${a.max_marks}`;
      
      const isOnline = a.type === 'Online Test';
      const hasCOs = a.co_structure && Object.keys(a.co_structure).length > 0;
      
      // Controls
      if (isOnline) {
        document.getElementById('btnSyncScores').classList.remove('hidden');
        document.getElementById('btnSaveScores').classList.add('hidden');
      } else {
        document.getElementById('btnSyncScores').classList.add('hidden');
        document.getElementById('btnSaveScores').classList.remove('hidden');
      }

      // Headers
      let headHtml = '<th class="p-3 w-12">S.No.</th><th class="p-3">Name</th><th class="p-3 w-28">Admission No</th><th class="p-3 w-32">SBTE Reg No</th>';
      if (hasCOs && !isOnline) {
        Object.keys(a.co_structure).forEach(co => {
          headHtml += `<th class="p-3 w-16 text-center">${co} (${a.co_structure[co]})</th>`;
        });
      }
      headHtml += `<th class="p-3 w-24 text-right">Total Score</th>`;
      document.getElementById('gradebookTableHead').innerHTML = headHtml;

      // Build Score Map for fast lookup
      let scoreMap = {};
      if(a.scores) a.scores.forEach(s => { scoreMap[s.reg_no] = { score: s.score, cos: s.co_scores || {} }; });

      let bodyHtml = '';
      currentRoomStudents.forEach((s, index) => {
        let sc = scoreMap[s.reg_no] || { score: '', cos: {} };
        
        bodyHtml += `<tr class="hover:bg-slate-900/50 transition-colors">
            <td class="p-3 text-[10px] text-slate-500 font-bold">${index + 1}</td>
            <td class="p-3 text-[10px] font-bold text-slate-200">${s.name}</td>
            <td class="p-3 text-[10px] text-slate-400 font-mono">${s.reg_no}</td>
            <td class="p-3 text-[10px] text-slate-400 font-mono">${s.sbte_reg_no || '-'}</td>`;
        
        if (hasCOs && !isOnline) {
          Object.keys(a.co_structure).forEach(co => {
            let val = sc.cos[co] !== undefined ? sc.cos[co] : '';
            bodyHtml += `<td class="p-3 text-center"><input type="number" data-reg="${s.reg_no}" data-co="${co}" value="${val}" max="${a.co_structure[co]}" class="co-input w-12 bg-slate-950 border border-slate-700/60 rounded px-1 py-1 text-[10px] text-white outline-none focus:border-amber-500 text-center"></td>`;
          });
        }

        let inputAttr = isOnline ? 'disabled' : '';
        let classStr = isOnline ? 'w-20 bg-slate-800 text-emerald-400 font-bold border-transparent' : 'score-input w-20 bg-slate-950 border border-slate-700/60 focus:border-amber-500';

        bodyHtml += `<td class="p-3 text-right">
              <input type="number" data-reg="${s.reg_no}" value="${sc.score}" max="${a.max_marks}" class="${classStr} rounded-lg px-3 py-1.5 text-[10px] text-white outline-none text-center" ${inputAttr}>
            </td>
          </tr>`;
      });
      
      document.getElementById('gradebookTableBody').innerHTML = bodyHtml;
      document.getElementById('gradebookContainer').classList.remove('hidden');
    }

    function closeGradebook() {
      document.getElementById('gradebookContainer').classList.add('hidden');
      currentAssessmentId = null;
    }

    function syncOnlineScores() {
      if(!currentAssessmentId) return;
      document.getElementById('btnSyncScores').innerText = "Syncing...";
      
      fetch(`/api/remedial/rooms/${currentRoomId}/assessments/${currentAssessmentId}/sync`, {
        method: 'POST',
        headers: headers
      })
      .then(res => res.json())
      .then(data => {
        document.getElementById('btnSyncScores').innerText = "Sync Online Scores";
        if(data.status === 'SUCCESS') {
          loadAssessments();
          setTimeout(() => openGradebook(currentAssessments.findIndex(a => a.assessment_id === currentAssessmentId)), 500);
        } else {
          alert(data.message || 'Error syncing');
        }
      });
    }

    function saveScores() {
      if(!currentAssessmentId) return;
      
      let payloadMap = {};
      
      // Collect Total Scores
      document.querySelectorAll('.score-input').forEach(inp => {
        if(inp.value !== '') {
          let reg = inp.getAttribute('data-reg');
          if(!payloadMap[reg]) payloadMap[reg] = { reg_no: reg, co_scores: {} };
          payloadMap[reg].score = parseFloat(inp.value);
        }
      });

      // Collect CO Scores
      document.querySelectorAll('.co-input').forEach(inp => {
        if(inp.value !== '') {
          let reg = inp.getAttribute('data-reg');
          let co = inp.getAttribute('data-co');
          if(!payloadMap[reg]) payloadMap[reg] = { reg_no: reg, co_scores: {}, score: 0 };
          payloadMap[reg].co_scores[co] = parseFloat(inp.value);
        }
      });

      let payload = Object.values(payloadMap);

      fetch(`/api/remedial/rooms/${currentRoomId}/assessments/${currentAssessmentId}/scores`, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify({ scores: payload })
      })
      .then(res => res.json())
      .then(data => {
        if(data.status === 'SUCCESS') {
          alert('Scores Saved!');
          closeGradebook();
          loadAssessments();
        }
      });
    }

    function printRemedialReport() {
      if (!currentRoomId || !currentAssessmentId) return;
      window.open(`/remedial/rooms/${currentRoomId}/assessments/${currentAssessmentId}/report`, '_blank');
    }
  </script>
</body>
</html>
