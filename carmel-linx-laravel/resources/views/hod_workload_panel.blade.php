<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Workload & Timetable Control Panel - Carmel Linx</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,600,1,0" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    .transition-premium {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    body, button, select, input, textarea, table, th, td, div, p, span, a {
      font-size: 14px !important;
    }
    h1, h2, h3, h4, h5, h6 {
      font-size: 16px !important;
      font-weight: 800 !important;
    }
    .card-gradient {
      background: linear-gradient(135deg, rgba(30, 41, 59, 0.4) 0%, rgba(15, 23, 42, 0.6) 100%);
    }
  </style>
</head>
<body class="bg-slate-950 text-slate-300 min-h-screen flex flex-col relative overflow-x-hidden selection:bg-amber-500/30">

  <!-- Header -->
  <header class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-40 shadow-2xl">
    <div class="px-6 h-16 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <a href="/hod/report-centre" class="flex items-center gap-2 px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl font-bold transition-premium no-underline">
          <span class="material-symbols-rounded text-base">arrow_back</span>
          <span class="text-sm">Back to Report Centre</span>
        </a>
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 text-white font-black rounded-lg w-8 h-8 flex items-center justify-center text-sm shadow-lg shadow-amber-500/20">WP</div>
        <div>
          <h1 class="font-extrabold text-slate-100 tracking-wide text-sm">Workload & Timetable Reports</h1>
          <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $department }} Department</p>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-grow p-6 lg:p-10 max-w-5xl mx-auto w-full space-y-8">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      
      <!-- Card 1: Department Workload -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-6 space-y-4 hover:border-amber-500/30 transition-premium flex flex-col justify-between">
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <div class="p-3 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-xl">
              <span class="material-symbols-rounded text-2xl">pending_actions</span>
            </div>
            <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Ready</span>
          </div>
          <h3 class="text-white text-base font-black">1. Department Faculty Workload</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Generate the official weekly engaged hours report for all lecturers and demonstrators in the department, calculated dynamically from active timetables.
          </p>
        </div>
        <div class="pt-6 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-sm text-slate-500">Commencement Format</span>
          <a href="/hod/workload-report/print" target="_blank" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold rounded-xl transition-premium text-sm shadow-lg shadow-amber-500/15">
            Print Workload Report
          </a>
        </div>
      </div>

      <!-- Card 2: Batch Timetable Printer -->
      <div class="card-gradient border border-slate-800/80 rounded-2xl p-6 space-y-4 hover:border-violet-500/30 transition-premium flex flex-col justify-between">
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <div class="p-3 bg-violet-500/10 border border-violet-500/20 text-violet-400 rounded-xl">
              <span class="material-symbols-rounded text-2xl">calendar_today</span>
            </div>
            <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Active</span>
          </div>
          <h3 class="text-white text-base font-black">2. Individual Batch Timetable</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            Select any department batch and semester to preview and print its finalized A4 landscape weekly timetable.
          </p>
          
          <div class="grid grid-cols-2 gap-4 pt-2">
            <div class="space-y-1">
              <label class="text-xs font-bold text-slate-400 uppercase">Select Classroom</label>
              <select id="singleBatchSelect" class="w-full bg-slate-900 border border-slate-850 rounded-xl p-2.5 text-sm text-white focus:border-violet-500 outline-none">
                @foreach ($batches as $b)
                  <option value="{{ $b->classroom_id }}">{{ $b->classroom_id }}</option>
                @endforeach
              </select>
            </div>
            <div class="space-y-1">
              <label class="text-xs font-bold text-slate-400 uppercase">Select Semester</label>
              <select id="singleSemSelect" class="w-full bg-slate-900 border border-slate-850 rounded-xl p-2.5 text-sm text-white focus:border-violet-500 outline-none">
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
        <div class="pt-6 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-sm text-slate-500">A4 Landscape Grid</span>
          <button onclick="printSingleTimetable()" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-xl transition-premium text-sm cursor-pointer shadow-lg shadow-violet-600/15">
            Print Timetable
          </button>
        </div>
      </div>

    </div>

    <!-- Card 3: Consolidated Timetable (3 Batches) -->
    <div class="card-gradient border border-slate-800/80 rounded-3xl p-8 space-y-6 hover:border-emerald-500/30 transition-premium shadow-xl">
      <div class="space-y-2">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl">
              <span class="material-symbols-rounded text-2xl">dashboard_customize</span>
            </div>
            <div>
              <h3 class="text-white text-base font-black">3. Semester Consolidated Timetable</h3>
              <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Select up to 3 active classes</p>
            </div>
          </div>
          <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-500/10 text-green-400 border border-green-500/20">Clash Audit</span>
        </div>
        <p class="text-slate-400 text-sm leading-relaxed max-w-3xl">
          Pick exactly 2 or 3 active classes to compile a consolidated semester timetable sheet. It places schedules side-by-side per period, ideal for monitoring department clash reviews.
        </p>
      </div>

      <form id="consolidatedForm" action="/hod/consolidated-timetable/print" method="GET" target="_blank" onsubmit="return validateConsolidatedForm(event)" class="space-y-6 pt-2">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          @forelse ($batches as $b)
            <label class="flex items-center gap-3 p-4 bg-slate-900/40 border border-slate-850 hover:border-emerald-500/30 rounded-2xl transition-premium cursor-pointer select-none">
              <input type="checkbox" name="batches[]" value="{{ $b->classroom_id }}" class="w-4 h-4 rounded border-slate-800 text-emerald-600 focus:ring-emerald-500 bg-slate-950 accent-emerald-500 batch-checkbox" />
              <div>
                <span class="text-sm font-bold text-slate-200 block">{{ $b->classroom_id }}</span>
                <span class="text-xs text-slate-500">Admission Year: {{ $b->batch_year }}</span>
              </div>
            </label>
          @empty
            <div class="col-span-full p-6 text-center text-slate-500 italic">No batches created for this department.</div>
          @endforelse
        </div>

        <div class="pt-6 border-t border-slate-800/60 flex items-center justify-between">
          <span class="text-sm text-slate-500" id="selectionStatus">Select batches to begin (Max 3)</span>
          <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-premium text-sm cursor-pointer shadow-lg shadow-emerald-600/15">
            Generate Consolidated Sheet
          </button>
        </div>
      </form>
    </div>

  </main>

  <script>
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

    function updateSelectionStatus() {
      const checkedCount = document.querySelectorAll('.batch-checkbox:checked').length;
      selectionStatus.innerText = `${checkedCount} of 3 batches selected`;
    }

    function validateConsolidatedForm(e) {
      const checkedCount = document.querySelectorAll('.batch-checkbox:checked').length;
      if (checkedCount < 2) {
        alert('Please select at least 2 batches to generate a consolidated timetable.');
        e.preventDefault();
        return false;
      }
      return true;
    }

    // Individual Timetable printing logic
    function printSingleTimetable() {
      const classroomId = document.getElementById('singleBatchSelect').value;
      const sem = document.getElementById('singleSemSelect').value;
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
        "ME": "Mechanical Engineering",
        "EE": "Electrical & Electronics Engineering",
        "CE": "Civil Engineering",
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
  </script>
</body>
</html>
