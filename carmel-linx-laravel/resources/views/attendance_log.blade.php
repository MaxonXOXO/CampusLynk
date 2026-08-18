<x-layouts.faculty-shell title="Class Attendance Log" subtitle="Daily attendance and lesson tracking." activeNav="attendance_log">

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

  <div class="max-w-2xl mx-auto w-full space-y-6">

    <!-- Notification Banner -->
    <div id="globalAlert" class="hidden px-4 py-3 rounded-xl text-sm font-semibold text-center border shadow-xs transition-all"></div>

    <!-- CLASS SELECTOR CARD -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
      <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
        <span class="material-symbols-rounded text-blue-600 text-xl">school</span>
        <h2 class="font-bold text-sm text-slate-900">Select Batch &amp; Subject</h2>
      </div>

      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Class Subject / Batch</label>
        <select id="subjectSelect" onchange="onSubjectChange()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 cursor-pointer shadow-2xs">
          <option value="" disabled selected>-- Choose Subject --</option>
        </select>
      </div>
    </div>

    <!-- SUB-BATCH SELECTOR CARD (LABS ONLY) -->
    <div id="subBatchCard" class="hidden bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
      <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
        <span class="material-symbols-rounded text-blue-600 text-xl">splitscreen</span>
        <h2 class="font-bold text-sm text-slate-900">Lab Sub-Batch Partitioning</h2>
      </div>
      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Select Lab Sub-Batch</label>
        <div class="grid grid-cols-3 gap-3">
          <label class="cursor-pointer">
            <input type="radio" name="subBatchSelect" value="Whole" checked onchange="filterStudentsByBatch()" class="sr-only peer">
            <div class="p-3 text-center rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-slate-100 transition-all select-none shadow-2xs">
              Whole Class
            </div>
          </label>
          <label class="cursor-pointer">
            <input type="radio" name="subBatchSelect" value="1" onchange="filterStudentsByBatch()" class="sr-only peer">
            <div id="batch1Text" class="p-3 text-center rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-slate-100 transition-all select-none shadow-2xs">
              Batch 1
            </div>
          </label>
          <label class="cursor-pointer">
            <input type="radio" name="subBatchSelect" value="2" onchange="filterStudentsByBatch()" class="sr-only peer">
            <div id="batch2Text" class="p-3 text-center rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-slate-100 transition-all select-none shadow-2xs">
              Batch 2
            </div>
          </label>
        </div>
      </div>
    </div>

    <!-- DAILY CLASS LOG DETAILS -->
    <div id="classLogCard" class="hidden bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
      <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
        <span class="material-symbols-rounded text-blue-600 text-xl">edit_note</span>
        <h2 class="font-bold text-sm text-slate-900">Class Log Details</h2>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date</label>
          <input type="date" id="logDate" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 shadow-2xs" value="{{ date('Y-m-d') }}">
        </div>
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-1.5">Period / Hour (Select multiple if Lab)</label>
          <div class="flex flex-wrap gap-2">
            @for ($p = 1; $p <= 7; $p++)
              <label class="cursor-pointer">
                <input type="checkbox" name="logPeriods" value="{{ $p }}" class="sr-only peer">
                <div class="px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm font-semibold text-slate-700 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 hover:bg-slate-100 transition-all select-none shadow-2xs">
                  P{{ $p }}
                </div>
              </label>
            @endfor
          </div>
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Syllabus / Lesson Plan Topic</label>
        <select id="lessonPlanSelect" onchange="onLessonPlanChange()" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 cursor-pointer shadow-2xs">
          <option value="">-- Manual Entry --</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Topics Covered (Editable)</label>
        <textarea id="topicsCovered" rows="3" placeholder="Describe the topics covered in class today..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 resize-none shadow-2xs"></textarea>
      </div>
    </div>

    <!-- ATTENDANCE ENTRY PANEL -->
    <div id="attendanceCard" class="hidden bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2">
          <span class="material-symbols-rounded text-blue-600 text-xl">fact_check</span>
          <h2 class="font-bold text-sm text-slate-900">Attendance Panel</h2>
        </div>
        <!-- Mode Switch -->
        <div class="flex bg-slate-100 border border-slate-200 rounded-lg p-0.5">
          <button onclick="switchMode('list')" id="btnModeList" class="px-3 py-1 text-xs font-bold rounded-md bg-white text-blue-600 shadow-2xs transition-all cursor-pointer">List</button>
          <button onclick="switchMode('grid')" id="btnModeGrid" class="px-3 py-1 text-xs font-semibold rounded-md text-slate-600 hover:text-slate-900 transition-all cursor-pointer">Grid</button>
        </div>
      </div>

      <!-- MODE 1: LIST VIEW -->
      <div id="attendanceModeList" class="space-y-3">
        <div class="flex justify-between items-center mb-2">
          <span class="text-sm text-slate-600 font-semibold" id="studentCountLabel">Total Students: 0</span>
          <button onclick="toggleAllCheckboxes()" id="btnCheckAll" class="text-xs font-bold text-blue-600 hover:text-blue-700 cursor-pointer">Mark All Present</button>
        </div>
        
        <div class="max-h-[360px] overflow-y-auto border border-slate-200 rounded-xl bg-slate-50/50">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-100/80 text-slate-600 border-b border-slate-200 text-xs font-bold sticky top-0">
                <th class="p-3 w-16 text-center">Roll No</th>
                <th class="p-3">Name</th>
                <th class="p-3 w-20 text-center">Present</th>
              </tr>
            </thead>
            <tbody id="studentListContainer" class="divide-y divide-slate-100">
              <!-- Rendered via JS -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- MODE 2: GRID VIEW (Roll numbers only) -->
      <div id="attendanceModeGrid" class="hidden space-y-4">
        <div class="flex justify-between items-center">
          <p class="text-xs text-slate-500 font-medium">Tap buttons to toggle <strong class="text-rose-600">Absent (Red)</strong> / <strong class="text-emerald-600">Present (Green)</strong>.</p>
          <button onclick="toggleAllGrid(true)" class="text-xs font-bold text-blue-600 hover:text-blue-700 cursor-pointer">Reset Present</button>
        </div>

        <div class="grid grid-cols-5 gap-2.5 p-1" id="studentGridContainer">
          <!-- Rendered via JS -->
        </div>
      </div>

      <!-- ACTION BUTTONS -->
      <div class="pt-4 border-t border-slate-100">
        <button onclick="saveAttendanceAndLog()" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm shadow-blue-500/20 transition-premium cursor-pointer">
          <span class="material-symbols-rounded text-lg">check_circle</span> Save Log &amp; Attendance
        </button>
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
        banner.className = "px-4 py-3 rounded-xl text-sm font-semibold text-center border bg-rose-50 text-rose-700 border-rose-200 block shadow-xs";
      } else {
        banner.className = "px-4 py-3 rounded-xl text-sm font-semibold text-center border bg-emerald-50 text-emerald-700 border-emerald-200 block shadow-xs";
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

    function onSubjectChange() {
      const subjectId = document.getElementById('subjectSelect').value;
      if (!subjectId) return;

      // Show cards
      document.getElementById('classLogCard').classList.remove('hidden');
      document.getElementById('attendanceCard').classList.remove('hidden');

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
            document.getElementById('studentCountLabel').innerText = `Total Students: ${filtered.length}`;

            // Reset present state (all present by default)
            currentStudents.forEach(s => s.present = true);

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
        btnList.className = "px-3 py-1 text-xs font-bold rounded-md bg-white text-blue-600 shadow-2xs transition-all cursor-pointer";
        btnGrid.className = "px-3 py-1 text-xs font-semibold rounded-md text-slate-600 hover:text-slate-900 transition-all cursor-pointer";
        divList.classList.remove('hidden');
        divGrid.classList.add('hidden');
        renderList();
      } else {
        btnGrid.className = "px-3 py-1 text-xs font-bold rounded-md bg-white text-blue-600 shadow-2xs transition-all cursor-pointer";
        btnList.className = "px-3 py-1 text-xs font-semibold rounded-md text-slate-600 hover:text-slate-900 transition-all cursor-pointer";
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
      document.getElementById('studentCountLabel').innerText = `Total Students: ${filtered.length}`;
      renderList();
      renderGrid();
    }

    function renderList() {
      const container = document.getElementById('studentListContainer');
      container.innerHTML = '';

      const filtered = getFilteredStudents();
      if (filtered.length === 0) {
        container.innerHTML = '<tr><td colspan="3" class="p-6 text-center text-slate-400 text-xs">No students registered in this class.</td></tr>';
        return;
      }

      filtered.forEach((student, index) => {
        const tr = document.createElement('tr');
        tr.className = "hover:bg-slate-50 transition-premium";
        tr.innerHTML = `
          <td class="p-3 text-center font-semibold font-mono text-slate-500 text-xs">${student.roll_no || index + 1}</td>
          <td class="p-3 font-semibold text-slate-900 text-sm">${student.name}</td>
          <td class="p-3 text-center">
            <input type="checkbox" onchange="toggleStudentPresent('${student.reg_no}', this.checked)" ${student.present ? 'checked' : ''} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
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
        container.innerHTML = '<div class="col-span-full p-6 text-center text-slate-400 text-xs">No students registered.</div>';
        return;
      }

      filtered.forEach((student, index) => {
        const roll = student.roll_no || index + 1;
        const btn = document.createElement('button');
        btn.onclick = () => {
          student.present = !student.present;
          renderGrid();
        };
        
        if (student.present) {
          btn.className = "py-2.5 rounded-xl font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs text-center cursor-pointer hover:bg-emerald-100 transition-premium shadow-2xs";
        } else {
          btn.className = "py-2.5 rounded-xl font-bold bg-rose-50 text-rose-700 border border-rose-200 text-xs text-center cursor-pointer hover:bg-rose-100 transition-premium shadow-2xs";
        }
        btn.innerText = roll;
        container.appendChild(btn);
      });
    }

    function toggleStudentPresent(regNo, isPresent) {
      const student = currentStudents.find(s => s.reg_no === regNo);
      if (student) {
        student.present = isPresent;
      }
    }

    function toggleAllCheckboxes() {
      isAllChecked = !isAllChecked;
      const filtered = getFilteredStudents();
      filtered.forEach(s => s.present = isAllChecked);
      document.getElementById('btnCheckAll').innerText = isAllChecked ? "Mark All Absent" : "Mark All Present";
      renderList();
    }

    function toggleAllGrid(isPresent) {
      const filtered = getFilteredStudents();
      filtered.forEach(s => s.present = isPresent);
      renderGrid();
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
