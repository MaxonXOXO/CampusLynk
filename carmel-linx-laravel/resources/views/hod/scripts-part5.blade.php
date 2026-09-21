      if (!slotA || !slotB) return false;
      return slotA.subject === slotB.subject;
    }

    function renderTimetable() {
      const displayBody = document.getElementById('timetableDisplayBody');
      const editBody = document.getElementById('timetableEditBody');
      if (!displayBody || !editBody) return;

      displayBody.innerHTML = '';
      editBody.innerHTML = '';

      const days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
      days.forEach((day, index) => {
        const dayData = currentTimetableData[day] || {};
        
        // 1. Render Display Row with cell merging (colspan)
        const trDisp = document.createElement('tr');
        trDisp.className = 'border-b border-slate-800/40 hover:bg-slate-900/10 transition-premium';
        
        let dispCellsHtml = `<td class="p-4 text-center font-bold text-slate-200 bg-slate-900/40">${day}</td>`;
        
        const s1 = dayData[1] || { subject: '', staff: '' };
        const s2 = dayData[2] || { subject: '', staff: '' };
        const s3 = dayData[3] || { subject: '', staff: '' };
        const s4 = dayData[4] || { subject: '', staff: '' };
        const s5 = dayData[5] || { subject: '', staff: '' };
        const s6 = dayData[6] || { subject: '', staff: '' };

        // Forenoon continuous slots (1, 2, 3) merging logic
        if (s1.subject && slotsEqual(s1, s2) && slotsEqual(s2, s3)) {
          dispCellsHtml += renderTimetableDisplayCell(s1, 3);
        } else if (s1.subject && slotsEqual(s1, s2)) {
          dispCellsHtml += renderTimetableDisplayCell(s1, 2);
          dispCellsHtml += renderTimetableDisplayCell(s3, 1);
        } else if (s2.subject && slotsEqual(s2, s3)) {
          dispCellsHtml += renderTimetableDisplayCell(s1, 1);
          dispCellsHtml += renderTimetableDisplayCell(s2, 2);
        } else {
          dispCellsHtml += renderTimetableDisplayCell(s1, 1);
          dispCellsHtml += renderTimetableDisplayCell(s2, 1);
          dispCellsHtml += renderTimetableDisplayCell(s3, 1);
        }
        
        // Lunch Break Column (merged vertically)
        if (index === 0) {
          dispCellsHtml += `<td rowspan="5" class="p-4 text-center bg-slate-950/60 font-bold text-slate-500 text-sm align-middle select-none border-l border-r border-slate-800/40" style="writing-mode: vertical-rl; transform: rotate(180deg); letter-spacing: 4px; text-orientation: mixed; vertical-align: middle;">LUNCH BREAK</td>`;
        }
        
        // Afternoon continuous slots (4, 5, 6) merging logic
        if (s4.subject && slotsEqual(s4, s5) && slotsEqual(s5, s6)) {
          dispCellsHtml += renderTimetableDisplayCell(s4, 3);
        } else if (s4.subject && slotsEqual(s4, s5)) {
          dispCellsHtml += renderTimetableDisplayCell(s4, 2);
          dispCellsHtml += renderTimetableDisplayCell(s6, 1);
        } else if (s5.subject && slotsEqual(s5, s6)) {
          dispCellsHtml += renderTimetableDisplayCell(s4, 1);
          dispCellsHtml += renderTimetableDisplayCell(s5, 2);
        } else {
          dispCellsHtml += renderTimetableDisplayCell(s4, 1);
          dispCellsHtml += renderTimetableDisplayCell(s5, 1);
          dispCellsHtml += renderTimetableDisplayCell(s6, 1);
        }
        
        trDisp.innerHTML = dispCellsHtml;
        displayBody.appendChild(trDisp);

        // 2. Render Edit Row (always unmerged for individual slot selection)
        const trEdit = document.createElement('tr');
        trEdit.className = 'border-b border-slate-800/40';
        
        let editCellsHtml = `<td class="p-3 text-center font-bold text-slate-300 bg-slate-900/40">${day}</td>`;
        
        // Forenoon hours (1, 2, 3)
        for (let h = 1; h <= 3; h++) {
          const slot = dayData[h] || { subject: '', staff: '' };
          editCellsHtml += renderTimetableEditCell(day, h, slot);
        }
        
        // Lunch Break Column (merged vertically)
        if (index === 0) {
          editCellsHtml += `<td rowspan="5" class="p-3 text-center bg-slate-950/60 text-slate-600 font-bold text-sm align-middle select-none border-l border-r border-slate-850" style="writing-mode: vertical-rl; transform: rotate(180deg); letter-spacing: 4px; text-orientation: mixed; vertical-align: middle;">LUNCH BREAK</td>`;
        }
        
        // Afternoon hours (4, 5, 6)
        for (let h = 4; h <= 6; h++) {
          const slot = dayData[h] || { subject: '', staff: '' };
          editCellsHtml += renderTimetableEditCell(day, h, slot);
        }
        
        trEdit.innerHTML = editCellsHtml;
        editBody.appendChild(trEdit);
      });
    }

    function renderTimetableDisplayCell(slot, colspan = 1) {
      const colspanAttr = colspan > 1 ? `colspan="${colspan}"` : '';
      if (!slot.subject) {
        return `<td ${colspanAttr} class="p-4 text-center text-slate-600 italic text-sm">-- Free Period --</td>`;
      }

      // Automatically pull ALL staff members assigned to this subject (for labs/multi-lecturer classes)
      const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === slot.subject);
      let staffDisplay = '';
      if (matchedSub && matchedSub.staff && matchedSub.staff.length > 0) {
        staffDisplay = matchedSub.staff.map(s => s.name).join(', ');
      } else {
        staffDisplay = slot.staff || 'N/A';
      }

      return `
        <td ${colspanAttr} class="p-4 text-center space-y-1">
          <div class="font-extrabold text-slate-100 text-base leading-snug">${slot.subject}</div>
          <div class="text-slate-400 text-sm">${staffDisplay}</div>
        </td>
      `;
    }

    function renderTimetableEditCell(day, hour, slot) {
      let subOptions = `<option value="">-- Free Period --</option>`;
      currentAllocatedSubjects.forEach(sub => {
        const isSelected = sub.subject_code === slot.subject ? 'selected' : '';
        subOptions += `<option value="${sub.subject_code}" ${isSelected}>${sub.subject_code} - ${sub.subject_name}</option>`;
      });

      let staffOptions = `<option value="">-- No Staff --</option>`;
      const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === slot.subject);
      if (matchedSub && matchedSub.staff) {
        matchedSub.staff.forEach(st => {
          const isSelected = st.name === slot.staff ? 'selected' : '';
          staffOptions += `<option value="${st.name}" ${isSelected}>${st.name}</option>`;
        });
      }

      return `
        <td class="p-2 w-44">
          <div class="space-y-1.5">
            <select onchange="updateTimetableStaffDropdown(this)" data-day="${day}" data-hour="${hour}" class="w-full bg-slate-900 border border-slate-800 rounded-lg p-1.5 text-sm text-white focus:border-violet-500 outline-none select-subject">
              ${subOptions}
            </select>
            <select data-day="${day}" data-hour="${hour}" class="w-full bg-slate-950 border border-slate-850 rounded-lg p-1 text-sm text-slate-300 focus:border-violet-500 outline-none select-staff">
              ${staffOptions}
            </select>
          </div>
        </td>
      `;
    }    function printTimetable() {
      if (!activeBatchId) return;

      const sem = document.getElementById('modalSubjectSemester') ? document.getElementById('modalSubjectSemester').value : 1;
      const dept = activeBatchId ? activeBatchId.split('_')[0] : '{{ session("userBranch") }}';
      const currentYear = new Date().getFullYear();

      // Convert department codes to full names
      const deptNames = {
        "EL": "Electronics Engineering",
        "CS": "Computer Engineering",
        "ME": "Mechanical Engineering",
        "EE": "Electrical & Electronics Engineering",
        "CE": "Civil Engineering",
        "CH": "Chemical Engineering"
      };
      const fullDept = deptNames[dept.toUpperCase()] || dept;

      const printWindow = window.open('', '_blank');
      const days = ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'];
      let rowsHtml = '';
      const scheduledSubjects = new Set();

      days.forEach((day, index) => {
        const dayData = currentTimetableData[day] || {};
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
        
        const matchedSub = currentAllocatedSubjects.find(s => s.subject_code === slot.subject);
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
        const sub = currentAllocatedSubjects.find(s => s.subject_code === code);
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

      printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
          <title>Timetable - ${activeBatchId}</title>
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
          alertEl.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alertEl.className = 'p-2 rounded-lg text-sm font-bold bg-red-950/40 text-red-400 border border-red-900 block';
        alertEl.innerText = 'Request failed.';
        alertEl.classList.remove('hidden');
      });
    }

    function loadBatchRoster(classroomId) {
      const tbody = document.getElementById('batchRosterTableBody');
      const countBadge = document.getElementById('rosterCountBadge');
      tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-slate-500 text-sm">Loading students...</td></tr>`;

      fetch(`/api/hod/batches/${encodeURIComponent(classroomId)}/students`)
        .then(r => r.json())
        .then(data => {
          tbody.innerHTML = '';
          if (data.status !== 'SUCCESS' || data.students.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-slate-600 text-sm font-bold">No students enrolled in this batch yet.</td></tr>`;
            countBadge.innerText = '0';
            return;
          }
          countBadge.innerText = data.students.length;
          data.students.forEach(s => {
            let statusBadge = `<span class="px-2 py-0.5 rounded-full text-sm font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Pending</span>`;
            if (s.status === 'Approved') statusBadge = `<span class="px-2 py-0.5 rounded-full text-sm font-bold bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>`;
            else if (s.status === 'Suspended') statusBadge = `<span class="px-2 py-0.5 rounded-full text-sm font-bold bg-red-500/10 text-red-400 border border-red-500/20">Suspended</span>`;

            const admTypeBadge = s.admission_type === 'LET'
              ? `<span class="px-1.5 py-0.5 rounded text-sm font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">LET</span>`
              : `<span class="px-1.5 py-0.5 rounded text-sm font-bold bg-slate-700 text-slate-400">Regular</span>`;
              
            const sbteBadge = s.sbte_reg_no ? `<span class="font-mono text-slate-300 font-bold">${s.sbte_reg_no}</span>` : `<span class="text-sm text-slate-500 italic">Pending</span>`;

            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/20 transition-premium';
            tr.innerHTML = `
              <td class="p-3 font-bold text-slate-200">${s.name}</td>
              <td class="p-3 font-mono text-slate-400">${s.reg_no}</td>
              <td class="p-3 font-mono text-slate-500">${s.adm_no}</td>
              <td class="p-3">${sbteBadge}</td>
              <td class="p-3">${admTypeBadge}</td>
              <td class="p-3 font-bold text-indigo-400 font-mono">S${s.semester || '1'}</td>
              <td class="p-3">${statusBadge}</td>
              <td class="p-3 text-right space-x-1">
                <button onclick="openStudentDiary('${s.reg_no}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-teal-500/10 hover:bg-teal-500/20 border border-teal-500/20 text-teal-400 rounded-lg text-sm font-bold transition-premium cursor-pointer">
                  <span class="material-symbols-rounded text-sm">menu_book</span> Diary
                </button>
                <button onclick="editStudentBatch('${s.reg_no}', '${classroomId}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-violet-500/10 hover:bg-violet-500/20 border border-violet-500/20 text-violet-400 rounded-lg text-sm font-bold transition-premium cursor-pointer">
                  <span class="material-symbols-rounded text-sm">swap_horiz</span> Move
                </button>
              </td>
            `;
            tbody.appendChild(tr);
          });
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="8" class="p-6 text-center text-red-400 font-bold text-sm">Failed to load students.</td></tr>`;
