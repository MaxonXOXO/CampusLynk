<x-layouts.app-shell 
    title="CampusLynk - Faculty Workload & Timetables" 
    topbarTitle="Workload & Timetables" 
    activeNav="report_centre">

  <div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Breadcrumb Context Navigation -->
    <div class="flex items-center gap-2 text-sm text-slate-500">
      <a href="/dashboard/hod?panel=report_centre" class="hover:text-blue-600 font-medium transition-colors flex items-center gap-1.5 no-underline">
        <i data-lucide="bar-chart-3" class="w-4 h-4 text-slate-400"></i>
        <span>Report Centre</span>
      </a>
      <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i>
      <span class="text-slate-900 font-semibold">Faculty Workload &amp; Timetables</span>
    </div>

    <!-- Header & Operational Overview Card -->
    <div class="bg-white border border-slate-200/80 p-5 rounded-2xl shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div class="flex items-center gap-3.5">
        <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
          <i data-lucide="briefcase" class="w-6 h-6 text-blue-600"></i>
        </div>
        <div>
          <h3 class="text-base font-bold text-slate-900">Faculty Workload &amp; Timetables</h3>
          <p class="text-xs text-slate-500 mt-0.5">Faculty workload, batch timetables, and consolidated clash review.</p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="/dashboard/hod?panel=report_centre" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-medium text-xs border border-slate-200 rounded-xl shadow-2xs transition-all duration-200 flex items-center gap-1.5 no-underline cursor-pointer">
          <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
          <span>Back to Report Centre</span>
        </a>
      </div>
    </div>

    <!-- Two-Column Workspace: Faculty Workload & Individual Timetable -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
      
      <!-- Section 1: Department Faculty Workload Card -->
      <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
              <i data-lucide="briefcase" class="w-5 h-5 text-amber-600"></i>
            </div>
          </div>
          <div>
            <h4 class="text-base font-bold text-slate-900">1. Department Faculty Workload</h4>
            <p class="text-sm text-slate-500 leading-relaxed mt-1">
              Review the department's weekly theory and laboratory workload for faculty commencement and academic planning.
            </p>
          </div>
        </div>
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
          <span class="text-xs font-medium text-slate-400">Commencement Format</span>
          <a href="/hod/workload-report/print" target="_blank" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-sm rounded-xl shadow-xs transition-all duration-200 flex items-center gap-2 no-underline cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Print Workload Report</span>
          </a>
        </div>
      </div>

      <!-- Section 2: Individual Batch Timetable Card -->
      <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between space-y-4">
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100">
              <i data-lucide="calendar-days" class="w-5 h-5 text-purple-600"></i>
            </div>
          </div>
          <div>
            <h4 class="text-base font-bold text-slate-900">2. Individual Batch Timetable</h4>
            <p class="text-sm text-slate-500 leading-relaxed mt-1">
              Select any department batch and semester to preview and print its finalized A4 landscape weekly timetable.
            </p>
          </div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Classroom</label>
              <select id="singleBatchSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer transition-colors">
                @foreach ($batches as $b)
                  <option value="{{ $b->classroom_id }}">{{ $b->classroom_id }}</option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Semester</label>
              <select id="singleSemSelect" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer transition-colors">
                <option value="1">Semester 1</option>
                <option value="2">Semester 2</option>
                <option value="3" selected>Semester 3</option>
                <option value="4">Semester 4</option>
                <option value="5">Semester 5</option>
                <option value="6">Semester 6</option>
              </select>
            </div>
          </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
          <span class="text-xs font-medium text-slate-400">A4 Landscape Grid</span>
          <button type="button" onclick="printSingleTimetable()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-sm rounded-xl shadow-xs transition-all duration-200 flex items-center gap-2 cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Print Timetable</span>
          </button>
        </div>
      </div>

    </div>

    <!-- Section 3: Semester Consolidated Timetable (Clash Audit) -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs hover:shadow-md transition-all duration-200 space-y-5">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
            <i data-lucide="layout-grid" class="w-5 h-5 text-emerald-600"></i>
          </div>
          <div>
            <h4 class="text-base font-bold text-slate-900">3. Semester Consolidated Timetable</h4>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Select up to 3 active classes for departmental clash audit</p>
          </div>
        </div>
      </div>

      <p class="text-sm text-slate-600 leading-relaxed max-w-4xl">
        Pick exactly 2 or 3 active classes to compile a consolidated semester timetable sheet. It places schedules side-by-side per period, ideal for monitoring department clash reviews.
      </p>

      <form id="consolidatedForm" action="/hod/consolidated-timetable/print" method="GET" target="_blank" onsubmit="return validateConsolidatedForm(event)" class="space-y-5 pt-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5">
          @forelse ($batches as $b)
            <label class="flex items-center gap-3.5 p-3.5 bg-slate-50/50 hover:bg-blue-50/30 border border-slate-200/80 hover:border-blue-300 rounded-xl transition-all duration-200 cursor-pointer select-none">
              <input type="checkbox" name="batches[]" value="{{ $b->classroom_id }}" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 bg-white accent-blue-600 batch-checkbox" />
              <div>
                <span class="text-sm font-bold text-slate-900 block">{{ $b->classroom_id }}</span>
                <span class="text-xs text-slate-500">Admission Year: {{ $b->batch_year }}</span>
              </div>
            </label>
          @empty
            <div class="col-span-full p-6 text-center text-slate-400 italic bg-slate-50 rounded-xl border border-slate-200">
              No batches created for this department.
            </div>
          @endforelse
        </div>

        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <span class="text-sm font-medium text-slate-500" id="selectionStatus">Select batches to begin (Max 3)</span>
          <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium text-sm rounded-xl shadow-xs transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Generate Consolidated Sheet</span>
          </button>
        </div>
      </form>
    </div>

  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Max 3 validation for consolidated checkboxes
      const checkboxes = document.querySelectorAll('.batch-checkbox');
      const selectionStatus = document.getElementById('selectionStatus');

      checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
          const checkedCount = document.querySelectorAll('.batch-checkbox:checked').length;
          if (checkedCount > 3) {
            cb.checked = false;
            alert('You can select a maximum of 3 batches for consolidated view.');
            return;
          }
          updateSelectionStatus();
        });
      });

      if (window.lucide) {
        lucide.createIcons();
      }
    });

    function updateSelectionStatus() {
      const selectionStatus = document.getElementById('selectionStatus');
      const checkedCount = document.querySelectorAll('.batch-checkbox:checked').length;
      if (selectionStatus) {
        selectionStatus.innerText = `${checkedCount} of 3 batches selected`;
      }
    }
    window.updateSelectionStatus = updateSelectionStatus;

    function validateConsolidatedForm(e) {
      const checkedCount = document.querySelectorAll('.batch-checkbox:checked').length;
      if (checkedCount < 2) {
        alert('Please select at least 2 batches to generate a consolidated timetable.');
        if (e && e.preventDefault) e.preventDefault();
        return false;
      }
      return true;
    }
    window.validateConsolidatedForm = validateConsolidatedForm;

    // Individual Timetable printing logic
    function printSingleTimetable() {
      const batchEl = document.getElementById('singleBatchSelect');
      const semEl = document.getElementById('singleSemSelect');
      if (!batchEl || !semEl) return;

      const classroomId = batchEl.value;
      const sem = semEl.value;
      if (!classroomId) {
        alert('No batch selected.');
        return;
      }

      // Fetch subjects and timetable then trigger printing window
      Promise.all([
        fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/subjects?semester=${sem}`).then(r => r.json()),
        fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/timetable`).then(r => r.json())
      ])
      .then(([subData, ttData]) => {
        if (subData.status !== 'SUCCESS' || ttData.status !== 'SUCCESS') {
          throw new Error('Failed to load batch specifications.');
        }

        const allocatedSubjects = subData.subjects || [];
        const timetableData = ttData.timetable || {};
        
        triggerPrintTimetableWindow(classroomId, sem, allocatedSubjects, timetableData);
      })
      .catch(err => {
        alert('Error preparing printout: ' + err.message);
      });
    }
    window.printSingleTimetable = printSingleTimetable;

    function triggerPrintTimetableWindow(classroomId, sem, allocatedSubjects, timetableData) {
      const printWindow = window.open('', '_blank');
      const days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
      let rowsHtml = '';
      const scheduledSubjects = new Set();

      function slotsEqual(slotA, slotB) {
        if (!slotA || !slotB) return false;
        return slotA.subject === slotB.subject;
      }

      days.forEach((day, index) => {
        const dayData = timetableData[day] || {};
        const s1 = dayData[1] || { subject: '', staff: '' };
        const s2 = dayData[2] || { subject: '', staff: '' };
        const s3 = dayData[3] || { subject: '', staff: '' };
        const s4 = dayData[4] || { subject: '', staff: '' };
        const s5 = dayData[5] || { subject: '', staff: '' };
        const s6 = dayData[6] || { subject: '', staff: '' };

        // Collect scheduled subject codes
        [s1, s2, s3, s4, s5, s6].forEach(s => {
          if (s.subject) scheduledSubjects.add(s.subject);
        });

        let cellsHtml = `<td class="p-4 text-center font-bold bg-gray-100 day-cell">${day}</td>`;

        // Forenoon
        if (s1.subject && slotsEqual(s1, s2) && slotsEqual(s2, s3)) {
          cellsHtml += renderPrintCell(s1, 3);
        } else if (s1.subject && slotsEqual(s1, s2)) {
          cellsHtml += renderPrintCell(s1, 2);
          cellsHtml += renderPrintCell(s3, 1);
        } else if (s2.subject && slotsEqual(s2, s3)) {
          cellsHtml += renderPrintCell(s1, 1);
          cellsHtml += renderPrintCell(s2, 2);
        } else {
          cellsHtml += renderPrintCell(s1, 1);
          cellsHtml += renderPrintCell(s2, 1);
          cellsHtml += renderPrintCell(s3, 1);
        }

        // Lunch Break (merged vertically)
        if (index === 0) {
          cellsHtml += `<td rowspan="5" class="p-4 text-center font-black lunch-cell text-base" style="writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg); letter-spacing: 5px; vertical-align: middle; min-width: 50px;">LUNCH BREAK</td>`;
        }

        // Afternoon
        if (s4.subject && slotsEqual(s4, s5) && slotsEqual(s5, s6)) {
          cellsHtml += renderPrintCell(s4, 3);
        } else if (s4.subject && slotsEqual(s4, s5)) {
          cellsHtml += renderPrintCell(s4, 2);
          cellsHtml += renderPrintCell(s6, 1);
        } else if (s5.subject && slotsEqual(s5, s6)) {
          cellsHtml += renderPrintCell(s4, 1);
          cellsHtml += renderPrintCell(s5, 2);
        } else {
          cellsHtml += renderPrintCell(s4, 1);
          cellsHtml += renderPrintCell(s5, 1);
          cellsHtml += renderPrintCell(s6, 1);
        }

        rowsHtml += `<tr class="border-b border-slate-800/40 print-row">${cellsHtml}</tr>`;
      });

      function renderPrintCell(slot, colspan = 1) {
        const colspanAttr = colspan > 1 ? `colspan="${colspan}"` : '';
        if (!slot.subject) {
          return `<td ${colspanAttr} class="p-4 text-center free-period">-- Free --</td>`;
        }
        
        const matchedSub = allocatedSubjects.find(s => s.subject_code === slot.subject);
        let subjectName = matchedSub ? matchedSub.subject_name : '';
        let staffDisplay = '';
        if (matchedSub && matchedSub.staff && matchedSub.staff.length > 0) {
          staffDisplay = matchedSub.staff.map(s => s.name).join(', ');
        } else {
          staffDisplay = slot.staff || 'N/A';
        }

        return `
          <td ${colspanAttr} class="p-4 text-center">
            <div style="font-weight: 850; font-size: 15px;">${slot.subject}</div>
            <div style="font-weight: 600; font-size: 12px; margin-top: 2px;">${subjectName}</div>
            <div style="font-size: 11px; margin-top: 2px;">${staffDisplay}</div>
          </td>
        `;
      }

      // Build Legend/Abbreviations List
      let legendHtml = '';
      scheduledSubjects.forEach(code => {
        const sub = allocatedSubjects.find(s => s.subject_code === code);
        const name = sub ? sub.subject_name : 'Unknown Subject';
        let staffDisplay = '';
        if (sub && sub.staff && sub.staff.length > 0) {
          staffDisplay = sub.staff.map(s => s.name).join(', ');
        }
        legendHtml += `
          <div class="flex gap-2 text-sm py-1.5 border-b legend-item">
            <span class="font-mono font-bold w-24 legend-code">${code}</span>
            <span class="flex-grow font-semibold">${name}</span>
            <span class="legend-staff font-medium">(${staffDisplay || 'No staff assigned'})</span>
          </div>
        `;
      });

      if (!legendHtml) {
        legendHtml = '<p class="text-sm text-gray-500 italic">No subjects scheduled.</p>';
      }

      // Department Full Name Mapping
      const deptNames = {
        "EL": "Electronics Engineering",
        "CS": "Computer Engineering",
        "CT": "Computer Engineering",
        "ME": "Mechanical Engineering",
        "EE": "Electrical & Electronics Engineering",
        "EEE": "Electrical & Electronics Engineering",
        "CE": "Civil Engineering",
        "AU": "Automobile Engineering",
        "CH": "Chemical Engineering"
      };
      const deptShort = classroomId.split('_')[0];
      const fullDept = deptNames[deptShort.toUpperCase()] || deptShort;
      const currentYear = new Date().getFullYear();

      printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
          <title>Timetable - ${classroomId}</title>
          <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
          <style>
            /* Screen (Dark Mode) Styles */
            body {
              font-family: Arial, sans-serif;
              padding: 30px;
              background-color: #0b0f19;
              color: #f1f5f9;
            }
            .header-border {
              border-color: #1e293b;
            }
            .meta-val {
              color: #ffffff;
            }
            .meta-lbl {
              color: #94a3b8;
            }
            table {
              border-collapse: collapse;
              width: 100%;
              border: 2px solid #1e293b;
              background-color: #0f172a;
            }
            th {
              background-color: #1e293b;
              color: #f1f5f9;
              border: 1px solid #334155;
              padding: 12px;
              text-align: center;
            }
            td {
              border: 1px solid #334155;
              padding: 12px;
              text-align: center;
              vertical-align: middle;
            }
            .day-cell {
              background-color: #1e293b;
              font-weight: bold;
              color: #ffffff;
            }
            .lunch-cell {
              background-color: #090d16;
              color: #64748b;
              font-weight: 900;
            }
            .legend-box {
              background-color: #0f172a;
              border: 1px solid #1e293b;
            }
            .legend-title {
              color: #ffffff;
            }
            .legend-item {
              border-color: #1e293b;
              color: #cbd5e1;
            }
            .legend-code {
              color: #ffffff;
            }
            .legend-staff {
              color: #94a3b8;
            }
            .free-period {
              color: #475569;
              font-style: italic;
            }

            /* Print (Light Mode) Styles */
            @media print {
              .no-print {
                display: none;
              }
              @page {
                size: A4 landscape;
                margin: 0.5cm;
              }
              body {
                background-color: #ffffff;
                color: #000000;
                padding: 0;
                margin: 0;
              }
              table {
                background-color: #ffffff;
                border: 2px solid #000000 !important;
              }
              th, td {
                border: 2px solid #000000 !important;
                color: #000000 !important;
                background-color: #ffffff !important;
                padding: 6px !important;
              }
              .day-cell {
                background-color: #f3f4f6 !important;
              }
              .lunch-cell {
                background-color: #e5e7eb !important;
              }
              .legend-box {
                background-color: #ffffff !important;
                border: 1px solid #000000 !important;
                margin-top: 10px !important;
                padding: 8px !important;
              }
              .legend-title, .legend-item, .legend-code, .legend-staff {
                color: #000000 !important;
              }
              .free-period {
                color: #9ca3af !important;
              }
            }
          </style>
          <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
        <body>
          <div class="max-w-6xl mx-auto space-y-6">
            
            <!-- Centered Header Section -->
            <div class="border-b pb-4 text-center relative header-border">
              <h1 class="text-lg font-bold meta-lbl uppercase tracking-widest text-slate-400">Carmel Polytechnic College</h1>
              <h2 class="text-2xl font-black text-white mt-1">Weekly Class Timetable</h2>
              
              <div class="flex justify-center gap-12 mt-4 text-sm meta-lbl">
                <div>Department: <strong class="meta-val">${fullDept}</strong></div>
                <div>Batch: <strong class="meta-val">${classroomId}</strong></div>
                <div>Semester: <strong class="meta-val">Semester ${sem}</strong></div>
                <div>Assessment Year: <strong class="meta-val">${currentYear}</strong></div>
              </div>

              <div class="no-print absolute top-0 right-0 flex gap-2">
                <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-bold text-sm shadow transition duration-200">
                  Print Timetable
                </button>
                <button onclick="window.close()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-bold text-sm shadow transition duration-200">
                  Close Preview
                </button>
              </div>
            </div>
            
            <!-- Timetable Grid -->
            <table class="w-full text-left border">
              <thead>
                <tr class="text-slate-400 font-bold border-b header-border">
                  <th class="p-3 text-center w-24">Day</th>
                  <th class="p-3 text-center">Period 1<br><span class="text-xs font-normal meta-lbl">09:00 - 10:00</span></th>
                  <th class="p-3 text-center">Period 2<br><span class="text-xs font-normal meta-lbl">10:00 - 11:00</span></th>
                  <th class="p-3 text-center">Period 3<br><span class="text-xs font-normal meta-lbl">11:10 - 12:10</span></th>
                  <th class="p-3 text-center w-16">Lunch</th>
                  <th class="p-3 text-center">Period 4<br><span class="text-xs font-normal meta-lbl">01:00 - 02:00</span></th>
                  <th class="p-3 text-center">Period 5<br><span class="text-xs font-normal meta-lbl">02:00 - 03:00</span></th>
                  <th class="p-3 text-center">Period 6<br><span class="text-xs font-normal meta-lbl">03:00 - 04:00</span></th>
                </tr>
              </thead>
              <tbody>
                ${rowsHtml}
              </tbody>
            </table>
            
            <!-- Subject Legend / Abbreviations -->
            <div class="mt-6 p-4 rounded-xl border legend-box">
              <h3 class="text-sm font-bold legend-title mb-2 uppercase tracking-wider text-center">Subject Legend & Abbreviations</h3>
              <div class="space-y-1">
                ${legendHtml}
              </div>
            </div>
            
          </div>
        </body>
        </html>
      `);
      printWindow.document.close();
    }
    window.triggerPrintTimetableWindow = triggerPrintTimetableWindow;
  </script>

</x-layouts.app-shell>
