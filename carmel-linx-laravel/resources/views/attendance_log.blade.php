<x-layouts.faculty-shell title="Class Attendance Log" subtitle="Daily attendance, lesson tracking, and syllabus execution." activeNav="attendance_log">

  @php
    $role = session('userRole');
    $backLink = '/dashboard/lecturer';
    if ($role === 'HOD') $backLink = '/dashboard/hod';
    if ($role === 'Demonstrator') $backLink = '/dashboard/demonstrator';
    if ($role === 'Trade_Instructor') $backLink = '/dashboard/tradeinstructor';
    if ($role === 'Workshop_Superintendent') $backLink = '/dashboard/workshop';
    if ($role === 'Principal' || $role === 'Executive') $backLink = '/dashboard/principal';
    if ($role === 'Admin' || $role === 'Super_Admin' || $role === 'SuperAdmin') $backLink = '/dashboard/admin';
  @endphp

  <div class="space-y-6">

    <!-- Notification Banner -->
    <div id="globalAlert" class="hidden px-5 py-3.5 rounded-2xl text-sm font-semibold text-center border shadow-xs transition-all"></div>

    <!-- Responsive Layout: 12-Column Desktop Grid / 1-Column Mobile Stack -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

      <!-- LEFT COLUMN (Desktop: 5 Columns) -->
      <div class="lg:col-span-5 space-y-6">

        <!-- 1. CLASS & SUBJECT SELECTOR -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200">
                <span class="material-symbols-rounded text-lg">school</span>
              </div>
              <div>
                <h2 class="font-bold text-sm text-slate-900 leading-tight">Subject Allocation</h2>
                <p class="text-xs text-slate-500">Select assigned class to take log</p>
              </div>
            </div>
            <span class="text-xs font-bold text-blue-600 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-full">Step 1</span>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assigned Subject / Batch</label>
            <select id="subjectSelect" onchange="onSubjectChange()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 cursor-pointer shadow-2xs font-medium">
              <option value="" disabled selected>-- Choose Subject --</option>
            </select>
          </div>

          <!-- SUB-BATCH SELECTOR (LABS ONLY) -->
          <div id="subBatchCard" class="hidden pt-3 border-t border-slate-100 space-y-2">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Lab Sub-Batch Partitioning</label>
            <div class="grid grid-cols-3 gap-2.5">
              <label class="cursor-pointer">
                <input type="radio" name="subBatchSelect" value="Whole" checked onchange="filterStudentsByBatch()" class="sr-only peer">
                <div class="p-2.5 text-center rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-slate-100 transition-all select-none shadow-2xs">
                  Whole Class
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="subBatchSelect" value="1" onchange="filterStudentsByBatch()" class="sr-only peer">
                <div id="batch1Text" class="p-2.5 text-center rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-slate-100 transition-all select-none shadow-2xs">
                  Batch 1
                </div>
              </label>
              <label class="cursor-pointer">
                <input type="radio" name="subBatchSelect" value="2" onchange="filterStudentsByBatch()" class="sr-only peer">
                <div id="batch2Text" class="p-2.5 text-center rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-slate-100 transition-all select-none shadow-2xs">
                  Batch 2
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- 2. DAILY CLASS LOG DETAILS -->
        <div id="classLogCard" class="hidden bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200">
                <span class="material-symbols-rounded text-lg">edit_note</span>
              </div>
              <div>
                <h2 class="font-bold text-sm text-slate-900 leading-tight">Class Log Details</h2>
                <p class="text-xs text-slate-500">Date, periods &amp; syllabus topics</p>
              </div>
            </div>
            <span class="text-xs font-bold text-blue-600 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-full">Step 2</span>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Log Date</label>
              <input type="date" id="logDate" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs font-medium" value="{{ date('Y-m-d') }}">
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Period / Hour (Select one or multiple)</label>
              <div class="grid grid-cols-7 gap-1.5">
                @for ($p = 1; $p <= 7; $p++)
                  <label class="cursor-pointer">
                    <input type="checkbox" name="logPeriods" value="{{ $p }}" class="sr-only peer">
                    <div class="py-2 text-center rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-slate-100 transition-all select-none shadow-2xs">
                      P{{ $p }}
                    </div>
                  </label>
                @endfor
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lesson Plan / Topic Sync</label>
              <select id="lessonPlanSelect" onchange="onLessonPlanChange()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 cursor-pointer shadow-2xs font-medium">
                <option value="">-- Manual Entry --</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Topics Covered (Editable)</label>
              <textarea id="topicsCovered" rows="3" placeholder="Describe the topics covered in class today..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 resize-none shadow-2xs placeholder-slate-400"></textarea>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN (Desktop: 7 Columns) -->
      <div class="lg:col-span-7 space-y-6">

        <!-- 3. ATTENDANCE ENTRY PANEL -->
        <div id="attendanceCard" class="hidden bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-5">
          <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200">
                <span class="material-symbols-rounded text-lg">fact_check</span>
              </div>
              <div>
                <h2 class="font-bold text-sm text-slate-900 leading-tight">Student Attendance Roster</h2>
                <div class="flex items-center gap-2 mt-0.5">
                  <span class="text-xs text-slate-500 font-semibold" id="studentCountLabel">Total: 0</span>
                  <span class="text-slate-300">•</span>
                  <span class="text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full" id="livePresentLabel">Present: 0</span>
                  <span class="text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full" id="liveAbsentLabel">Absent: 0</span>
                </div>
              </div>
            </div>

            <!-- Mode Switch & Mark All Controls -->
            <div class="flex items-center gap-2">
              <button onclick="toggleAllCheckboxes()" id="btnCheckAll" class="px-3 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl transition-all cursor-pointer shadow-2xs">
                Mark All Present
              </button>

              <div class="flex bg-slate-100 border border-slate-200 rounded-xl p-0.5">
                <button onclick="switchMode('list')" id="btnModeList" class="px-3 py-1 text-xs font-bold rounded-lg bg-white text-blue-600 shadow-2xs transition-all cursor-pointer">List</button>
                <button onclick="switchMode('grid')" id="btnModeGrid" class="px-3 py-1 text-xs font-semibold rounded-lg text-slate-600 hover:text-slate-900 transition-all cursor-pointer">Grid</button>
              </div>
            </div>
          </div>

          <!-- MODE 1: LIST VIEW (Clean Desktop Table) -->
          <div id="attendanceModeList" class="space-y-3">
            <div class="max-h-[520px] overflow-y-auto border border-slate-200 rounded-2xl bg-white shadow-2xs">
              <table class="w-full text-left text-sm border-collapse">
                <thead>
                  <tr class="bg-slate-50 text-slate-600 border-b border-slate-200 text-xs font-bold sticky top-0 z-10">
                    <th class="p-3.5 w-16 text-center">Roll No</th>
                    <th class="p-3.5">Student Name</th>
                    <th class="p-3.5 hidden sm:table-cell">Reg No</th>
                    <th class="p-3.5 w-24 text-center">Status</th>
                  </tr>
                </thead>
                <tbody id="studentListContainer" class="divide-y divide-slate-100">
                  <!-- Rendered via JS -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- MODE 2: GRID VIEW (Roll numbers quick-tap matrix) -->
          <div id="attendanceModeGrid" class="hidden space-y-4">
            <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-200">
              <p class="text-xs text-slate-600 font-medium">Tap buttons to toggle <strong class="text-rose-600">Absent (Red)</strong> / <strong class="text-emerald-600">Present (Green)</strong>.</p>
              <button onclick="toggleAllGrid(true)" class="text-xs font-bold text-blue-600 hover:text-blue-700 cursor-pointer">Reset All Present</button>
            </div>

            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2.5 p-1 max-h-[480px] overflow-y-auto" id="studentGridContainer">
              <!-- Rendered via JS -->
            </div>
          </div>

          <!-- ACTION BUTTONS -->
          <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <a href="{{ $backLink }}" class="w-full sm:w-auto px-5 py-3 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all text-center">
              Cancel &amp; Return
            </a>
            <button onclick="saveAttendanceAndLog()" class="w-full sm:w-auto flex-1 py-3.5 px-8 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm shadow-blue-500/20 transition-premium cursor-pointer">
              <span class="material-symbols-rounded text-lg">check_circle</span> Save Log &amp; Attendance
            </button>
          </div>

        </div>

        <!-- EMPTY STATE (Before Subject Selection) -->
        <div id="emptySelectPrompt" class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-xs space-y-3">
          <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto border border-blue-200">
            <span class="material-symbols-rounded text-3xl">fact_check</span>
          </div>
          <h3 class="font-bold text-slate-900 text-base">Select a Subject to Begin</h3>
          <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">Choose your assigned batch and subject from the left panel to load the student attendance roster and daily lesson log.</p>
        </div>

      </div>

    </div>

  </div>

  @push('scripts')
  <script>
    let activeMode = 'list'; // 'list' or 'grid'
    let currentStudents = [];
    let classroomId = '';
    let isAllChecked = true;

    document.addEventListener('DOMContentLoaded', () => {
      loadSubjects();
    });

    function showMessage(msg, isError = false) {
      const banner = document.getElementById('globalAlert');
      banner.classList.remove('hidden');
      if (isError) {
        banner.className = "px-5 py-3.5 rounded-2xl text-sm font-semibold text-center border bg-rose-50 text-rose-700 border-rose-200 block shadow-xs";
      } else {
        banner.className = "px-5 py-3.5 rounded-2xl text-sm font-semibold text-center border bg-emerald-50 text-emerald-700 border-emerald-200 block shadow-xs";
      }
      banner.innerText = msg;
      window.scrollTo({ top: 0, behavior: 'smooth' });
      setTimeout(() => banner.classList.add('hidden'), 5000);
    }

    function loadSubjects() {
      fetch('/api/staff/attendance/subjects')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const select = document.getElementById('subjectSelect');
            data.subjects.forEach(sub => {
              const opt = document.createElement('option');
              opt.value = sub.id;
              opt.innerText = `${sub.classroom_id} - ${sub.subject_name} (${sub.subject_code})`;
              select.appendChild(opt);
            });
          } else {
            showMessage(data.message || "Failed to load subjects", true);
          }
        });
    }

    function updateLiveCounters() {
      const filtered = getFilteredStudents();
      const presentCount = filtered.filter(s => s.present).length;
      const absentCount = filtered.length - presentCount;
      
      const lblPresent = document.getElementById('livePresentLabel');
      const lblAbsent = document.getElementById('liveAbsentLabel');
      if (lblPresent) lblPresent.innerText = `Present: ${presentCount}`;
      if (lblAbsent) lblAbsent.innerText = `Absent: ${absentCount}`;
    }

    function onSubjectChange() {
      const subjectId = document.getElementById('subjectSelect').value;
      if (!subjectId) return;

      // Show cards & hide empty prompt
      document.getElementById('classLogCard').classList.remove('hidden');
      document.getElementById('attendanceCard').classList.remove('hidden');
      const emptyPrompt = document.getElementById('emptySelectPrompt');
      if (emptyPrompt) emptyPrompt.classList.add('hidden');

      fetch(`/api/staff/attendance/subjects/${subjectId}/details`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentStudents = data.students;
            classroomId = data.classroom_id;

            // Check if Lab or Practical
            const isLab = (data.subject_type && (data.subject_type.toLowerCase().includes('lab') || data.subject_type.toLowerCase().includes('practical') || data.subject_type.toLowerCase().includes('practicum')));
            const subBatchCard = document.getElementById('subBatchCard');
            if (isLab) {
              subBatchCard.classList.remove('hidden');
              const half = Math.ceil(currentStudents.length / 2);
              document.getElementById('batch1Text').innerText = `Batch 1 (1-${half})`;
              document.getElementById('batch2Text').innerText = `Batch 2 (${half + 1}+)`;
            } else {
              subBatchCard.classList.add('hidden');
              const wholeRadio = document.querySelector('input[name="subBatchSelect"][value="Whole"]');
              if (wholeRadio) wholeRadio.checked = true;
            }

            // Load student count
            const filtered = getFilteredStudents();
            document.getElementById('studentCountLabel').innerText = `Total: ${filtered.length}`;

            // Reset present state (all present by default)
            currentStudents.forEach(s => s.present = true);
            updateLiveCounters();

            // Populate Lesson Plans dropdown
            const lpSelect = document.getElementById('lessonPlanSelect');
            lpSelect.innerHTML = '<option value="">-- Manual Entry --</option>';
            data.lesson_plans.forEach(lp => {
              const opt = document.createElement('option');
              opt.value = lp.id;
              opt.innerText = `[${lp.co_id}] ${lp.topic_content} (${lp.status})`;
              lpSelect.appendChild(opt);
            });

            // Reset topics textarea
            document.getElementById('topicsCovered').value = '';

            // Render views
            renderList();
            renderGrid();
          } else {
            showMessage(data.message || "Failed to load subject details", true);
          }
        });
    }

    function onLessonPlanChange() {
      const select = document.getElementById('lessonPlanSelect');
      const selectedOption = select.options[select.selectedIndex];
      if (selectedOption && select.value) {
        // Strip the bracket prefixes
        const text = selectedOption.innerText;
        const topic = text.substring(text.indexOf(']') + 2);
        document.getElementById('topicsCovered').value = topic.replace(/\(Pending\)|\(In Progress\)|\(Completed\)/i, '').trim();
      } else {
        document.getElementById('topicsCovered').value = '';
      }
    }

    function switchMode(mode) {
      activeMode = mode;
      const btnList = document.getElementById('btnModeList');
      const btnGrid = document.getElementById('btnModeGrid');
      const divList = document.getElementById('attendanceModeList');
      const divGrid = document.getElementById('attendanceModeGrid');

      if (mode === 'list') {
        btnList.className = "px-3 py-1 text-xs font-bold rounded-lg bg-white text-blue-600 shadow-2xs transition-all cursor-pointer";
        btnGrid.className = "px-3 py-1 text-xs font-semibold rounded-lg text-slate-600 hover:text-slate-900 transition-all cursor-pointer";
        divList.classList.remove('hidden');
        divGrid.classList.add('hidden');
        renderList();
      } else {
        btnGrid.className = "px-3 py-1 text-xs font-bold rounded-lg bg-white text-blue-600 shadow-2xs transition-all cursor-pointer";
        btnList.className = "px-3 py-1 text-xs font-semibold rounded-lg text-slate-600 hover:text-slate-900 transition-all cursor-pointer";
        divGrid.classList.remove('hidden');
        divList.classList.add('hidden');
        renderGrid();
      }
    }

    function getFilteredStudents() {
      if (document.getElementById('subBatchCard').classList.contains('hidden')) {
        return currentStudents;
      }
      const selectedRadio = document.querySelector('input[name="subBatchSelect"]:checked');
      const val = selectedRadio ? selectedRadio.value : 'Whole';
      if (val === 'Whole') {
        return currentStudents;
      }
      const half = Math.ceil(currentStudents.length / 2);
      if (val === '1') {
        return currentStudents.slice(0, half);
      } else {
        return currentStudents.slice(half);
      }
    }

    function filterStudentsByBatch() {
      const filtered = getFilteredStudents();
      document.getElementById('studentCountLabel').innerText = `Total: ${filtered.length}`;
      updateLiveCounters();
      renderList();
      renderGrid();
    }

    function renderList() {
      const container = document.getElementById('studentListContainer');
      container.innerHTML = '';

      const filtered = getFilteredStudents();
      if (filtered.length === 0) {
        container.innerHTML = '<tr><td colspan="4" class="p-8 text-center text-slate-400 text-xs">No students registered in this class.</td></tr>';
        return;
      }

      filtered.forEach((student, index) => {
        const tr = document.createElement('tr');
        tr.className = "hover:bg-slate-50 transition-premium";
        tr.innerHTML = `
          <td class="p-3.5 text-center font-bold font-mono text-slate-500 text-xs">${student.roll_no || index + 1}</td>
          <td class="p-3.5 font-bold text-slate-900 text-sm">${student.name}</td>
          <td class="p-3.5 hidden sm:table-cell font-mono text-xs text-slate-500">${student.reg_no || '-'}</td>
          <td class="p-3.5 text-center">
            <label class="inline-flex items-center gap-1.5 cursor-pointer">
              <input type="checkbox" onchange="toggleStudentPresent('${student.reg_no}', this.checked)" ${student.present ? 'checked' : ''} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
              <span class="text-xs font-bold ${student.present ? 'text-emerald-700' : 'text-rose-700'}">${student.present ? 'P' : 'A'}</span>
            </label>
          </td>
        `;
        container.appendChild(tr);
      });
    }

    function renderGrid() {
      const container = document.getElementById('studentGridContainer');
      container.innerHTML = '';

      const filtered = getFilteredStudents();
      if (filtered.length === 0) {
        container.innerHTML = '<div class="col-span-full p-8 text-center text-slate-400 text-xs">No students registered.</div>';
        return;
      }

      filtered.forEach((student, index) => {
        const roll = student.roll_no || index + 1;
        const btn = document.createElement('button');
        btn.onclick = () => {
          student.present = !student.present;
          updateLiveCounters();
          renderGrid();
          renderList();
        };
        
        if (student.present) {
          btn.className = "py-3 rounded-xl font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs text-center cursor-pointer hover:bg-emerald-100 transition-premium shadow-2xs flex flex-col items-center justify-center";
          btn.innerHTML = `<span class="text-xs font-mono font-bold">${roll}</span><span class="text-[10px] text-emerald-600 uppercase font-extrabold">P</span>`;
        } else {
          btn.className = "py-3 rounded-xl font-bold bg-rose-50 text-rose-700 border border-rose-200 text-xs text-center cursor-pointer hover:bg-rose-100 transition-premium shadow-2xs flex flex-col items-center justify-center";
          btn.innerHTML = `<span class="text-xs font-mono font-bold">${roll}</span><span class="text-[10px] text-rose-600 uppercase font-extrabold">A</span>`;
        }
        container.appendChild(btn);
      });
    }

    function toggleStudentPresent(regNo, isPresent) {
      const student = currentStudents.find(s => s.reg_no === regNo);
      if (student) {
        student.present = isPresent;
        updateLiveCounters();
        renderList();
        if (activeMode === 'grid') renderGrid();
      }
    }

    function toggleAllCheckboxes() {
      isAllChecked = !isAllChecked;
      const filtered = getFilteredStudents();
      filtered.forEach(s => s.present = isAllChecked);
      document.getElementById('btnCheckAll').innerText = isAllChecked ? "Mark All Absent" : "Mark All Present";
      updateLiveCounters();
      renderList();
      renderGrid();
    }

    function toggleAllGrid(isPresent) {
      const filtered = getFilteredStudents();
      filtered.forEach(s => s.present = isPresent);
      updateLiveCounters();
      renderGrid();
      renderList();
    }

    function saveAttendanceAndLog() {
      const subjectId = document.getElementById('subjectSelect').value;
      const date = document.getElementById('logDate').value;
      
      const checkedPeriods = Array.from(document.querySelectorAll('input[name="logPeriods"]:checked')).map(el => parseInt(el.value));
      const lpId = document.getElementById('lessonPlanSelect').value;
      const topics = document.getElementById('topicsCovered').value.trim();

      if (!subjectId) {
        showMessage("Please select a subject first.", true);
        return;
      }
      if (checkedPeriods.length === 0) {
        showMessage("Please select at least one Period / Hour.", true);
        return;
      }
      if (!topics) {
        showMessage("Please describe the topics covered.", true);
        return;
      }

      const present = [];
      const absent = [];
      const filtered = getFilteredStudents();
      filtered.forEach(s => {
        if (s.present) {
          present.push(s.reg_no);
        } else {
          absent.push(s.reg_no);
        }
      });

      const subBatchVal = document.getElementById('subBatchCard').classList.contains('hidden') ? 'Whole' : document.querySelector('input[name="subBatchSelect"]:checked').value;

      fetch('/api/staff/attendance/save', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          batch_subject_id: subjectId,
          date: date,
          periods: checkedPeriods,
          lesson_plan_id: lpId ? parseInt(lpId) : null,
          topics_covered: topics,
          present_students: present,
          absent_students: absent,
          sub_batch: subBatchVal
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showMessage(data.message, false);
          setTimeout(() => {
            window.location.href = "{{ $backLink }}";
          }, 1500);
        } else {
          showMessage(data.message || "Failed to save attendance log.", true);
        }
      })
      .catch(err => {
        console.error(err);
        showMessage("Error saving log and attendance.", true);
      });
    }
  </script>
  @endpush
</x-layouts.faculty-shell>
