
        let printHtml = `
          <html>
            <head>
              <title>Classroom Students List</title>
              <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
    @media (max-width: 640px) {
      .mobile-sem-btn {
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding-top: 0.625rem !important;
        padding-bottom: 0.625rem !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
      }
      .mobile-sem-btn span:first-child {
        font-size: 12px !important;
        margin-bottom: 0.125rem !important;
      }
      .mobile-sem-btn span:last-child {
        font-size: 14px !important;
      }
      #mobileSeminarNotificationsContainer h4,
      #seminarNotificationsContainer h4 {
        font-size: 16px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }

    /* CampusLynk Modern Virtual Classroom Panel Styles */
    #panelClassroom {
      background-color: #FAFAFB !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1.5rem !important;
      padding: 1.5rem !important;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05) !important;
    }

    #panelClassroom, 
    #panelClassroom button,
    #panelClassroom select,
    #panelClassroom input,
    #panelClassroom table,
    #panelClassroom th,
    #panelClassroom td,
    #panelClassroom div,
    #panelClassroom p,
    #panelClassroom h3,
    #panelClassroom h4,
    #panelClassroom h5,
    #panelClassroom span {
      font-size: 14px;
    }
    
    #panelClassroom h3#vcTitle,
    #panelClassroom h3#vcTitle span {
      font-size: 18px !important;
      font-weight: 700 !important;
    }
    
    #panelClassroom #vcSubtitle,
    #panelClassroom #vcSubtitle span {
      font-size: 14px !important;
    }
    
    #panelClassroom #vcViewStudentsBtn,
    #panelClassroom #vcViewStudentsBtn span {
      font-size: 14px !important;
      font-weight: 600 !important;
    }

    #panelClassroom .classroom-tab-btn {
      font-size: 14px !important;
      font-weight: 600 !important;
      padding: 0.5rem 0.875rem !important;
      border-radius: 0.75rem !important;
      transition: all 0.15s ease !important;
    }

    #panelClassroom h4, 
    #panelClassroom h5 {
      font-size: 16px !important;
      font-weight: 700 !important;
    }

    /* Manual mark entry table title, names, and internal grid data font sizes */
    #manualMarksWrapper table th,
    #manualMarksWrapper table td,
    #manualMarksWrapper input,
    #manualMarksWrapper span {
      font-size: 14px !important;
    }
    
    #manualMarksWrapper table td {
      padding: 12px 10px !important;
    }

    /* Flatpickr date picker light theme styling */
    .flatpickr-calendar {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1rem !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-month,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .flatpickr-weekday,
    .flatpickr-calendar .flatpickr-days .flatpickr-day {
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.prevMonthDay:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.nextMonthDay:hover {
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day.selected {
      background: #2563eb !important;
      color: #ffffff !important;
    }
    .flatpickr-calendar .flatpickr-current-month span.cur-month,
    .flatpickr-calendar .numInputWrapper span,
    .flatpickr-calendar input.numInput {
      color: #0f172a !important;
      font-weight: 600 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
      color: #2563eb !important;
      fill: #2563eb !important;
    }

    /* Mobile styles for Virtual Classroom assessment mark entry */
    @media (max-width: 767px) {
      .co-mark, .summ-mark,
      #manualMarksWrapper input,
      #markEntryTbody input,
      #summativeMarkEntryTbody input {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        color: #0f172a !important;
      }

      /* Transform assignment mark entry tables into list cards on mobile view */
      #markEntryTbody,
      #markEntryTbody tr,
      #markEntryTbody td,
      #manualMarksWrapper tbody,
      #manualMarksWrapper tr,
      #manualMarksWrapper td {
        display: block !important;
      }
      
      #panelClassroom table thead {
        display: none !important;
      }
      
      #markEntryTbody tr,
      #manualMarksWrapper table tr {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.65rem !important;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05) !important;
      }
      
      #markEntryTbody td,
      #manualMarksWrapper td {
        padding: 0.35rem 0 !important;
        border: none !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        text-align: left !important;
        font-size: 14px !important;
      }
      
      #markEntryTbody td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(5)::before { content: "CO1 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(6)::before { content: "CO2 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(7)::before { content: "CO3 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(8)::before { content: "CO4 (10): "; font-weight: bold; color: #2563eb; }
      
      #manualMarksWrapper td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(5)::before { content: "CO1: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(6)::before { content: "CO2: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(7)::before { content: "CO3: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(8)::before { content: "CO4: "; font-weight: bold; color: #2563eb; }
      
      #markEntryTbody td div.relative,
      #manualMarksWrapper td div.relative {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
      }
      #markEntryTbody td input,
      #manualMarksWrapper td input {
        width: 5.5rem !important;
        margin: 0 0 0 auto !important;
        text-align: center !important;
      }

      #mobileSeminarNotificationsContainer h5,
      #seminarNotificationsContainer h5 {
        font-size: 15px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }
  </style>
            </head>
              <body>
                <div class="header-container">
                  <h1 class="college-name">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</h1>
                  <h2 class="dept-name">DEPARTMENT OF ${deptName}</h2>
                  <div class="doc-title">Enrolled Students Register</div>
                </div>

                <div class="meta-grid">
                  <div class="meta-item"><span class="meta-label">Batch:</span> ${batchName}</div>
                  <div class="meta-item" style="text-align: right;"><span class="meta-label">Syllabus Revision:</span> ${revision}</div>
                  <div class="meta-item"><span class="meta-label">Subject Code:</span> ${currentSubjectCode}</div>
                  <div class="meta-item" style="text-align: right;"><span class="meta-label">Subject Name:</span> ${currentSubjectName}</div>
                </div>

                <table>
                  <thead>
                    <tr>
                      <th class="text-center" style="width: 40px;">No.</th>
                      <th class="text-center" style="width: 80px;">Roll No</th>
                      <th class="text-center" style="width: 120px;">SBTE Reg No</th>
                      <th class="text-center" style="width: 120px;">Admission No</th>
                      <th>Student Name</th>
                      <th>Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
          `;

          window.currentVirtualStudents.forEach((s, idx) => {
            printHtml += `
              <tr>
                <td class="text-center font-mono">${idx + 1}</td>
                <td class="text-center font-mono">${s.roll_no || '-'}</td>
                <td class="text-center font-mono">${s.sbte_reg_no || '-'}</td>
                <td class="text-center font-mono">${s.reg_no}</td>
                <td style="font-weight: 600;">${s.name}</td>
                <td></td>
              </tr>
            `;
          });

          printHtml += `
                  </tbody>
                </table>
                <script>
                  setTimeout(() => { window.print(); window.close(); }, 500);
                <\/script>
              </body>
            </html>
          `;

          let printWin = window.open('', '_blank');
          printWin.document.write(printHtml);
          printWin.document.close();
      }

    function fetchClassReports() {
      if (!currentSubjectId) return;
      const workspace = document.getElementById('classroomReportWorkspace');
      workspace.innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center text-slate-500">
          <div class="w-6 h-6 border-2 border-slate-600 border-t-blue-500 rounded-full animate-spin mb-4"></div>
          <p class="text-sm font-bold text-slate-400">Loading reports...</p>
        </div>
      `;

      fetch(`/api/staff/attendance/subjects/${currentSubjectId}/reports`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            classReportsData = data;
            renderActiveReport();
          } else {
            workspace.innerHTML = `<div class="text-sm font-bold text-red-400 py-10 text-center">${data.message || 'Failed to load reports.'}</div>`;
          }
        })
        .catch(err => {
          console.error(err);
          workspace.innerHTML = '<div class="text-sm font-bold text-red-400 py-10 text-center">Error loading reports.</div>';
        });
    }

    function loadClassReport(type) {
      activeReportType = type;
      
      const buttons = [
        { id: 'attendance_log', btn: 'btnReportLog' },
        { id: 'subject_log', btn: 'btnReportSubject' },
        { id: 'summary_matrix', btn: 'btnReportMatrix' }
      ];

      buttons.forEach(b => {
        const el = document.getElementById(b.btn);
        if (!el) return;
        if (b.id === type) {
          el.className = "px-4 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-xs cursor-pointer transition-premium";
        } else {
          el.className = "px-4 py-2 bg-white text-slate-700 hover:bg-slate-50 border border-slate-200 rounded-xl font-bold text-sm shadow-2xs cursor-pointer transition-premium";
        }
      });

      renderActiveReport();
    }

    function renderActiveReport() {
      if (!classReportsData) return;
      const workspace = document.getElementById('classroomReportWorkspace');
      workspace.innerHTML = '';

      if (activeReportType === 'attendance_log') {
        renderAttendanceLogReport(workspace);
      } else if (activeReportType === 'subject_log') {
        renderSubjectLogReport(workspace);
      } else if (activeReportType === 'summary_matrix') {
        renderSummaryMatrixReport(workspace);
      }
    }

    function renderAttendanceLogReport(container) {
      const logs = classReportsData.logs || [];
      if (logs.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No attendance logs recorded yet for this subject.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-200/80 rounded-xl bg-white shadow-xs">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-50 text-slate-700 border-b border-slate-200 uppercase tracking-wider text-xs font-bold">
                <th class="p-4">Date</th>
                <th class="p-4 text-center">Period</th>
                <th class="p-4">Topics Covered</th>
                <th class="p-4 text-center">Present</th>
                <th class="p-4 text-center">Absent</th>
              </tr>
            </thead>
            <tbody>
      `;

      logs.forEach(log => {
        html += `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-colors text-slate-800">
            <td class="p-4 font-mono font-bold text-slate-800">${log.date}</td>
            <td class="p-4 text-center font-bold text-slate-400">P${log.period}</td>
            <td class="p-4 text-slate-800">${log.topics_covered || '-'}</td>
            <td class="p-4 text-center font-bold text-emerald-400">${log.present_count}</td>
            <td class="p-4 text-center font-bold text-rose-400">${log.absent_count}</td>
          </tr>
        `;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function renderSubjectLogReport(container) {
      const logs = classReportsData.logs || [];
      if (logs.length === 0) {
        container.innerHTML = '<div class="text-sm font-bold text-slate-400 py-10 text-center">No class logs recorded yet for this subject.</div>';
        return;
      }

      let html = `
        <div class="overflow-x-auto border border-slate-200/80 rounded-xl bg-white shadow-xs">
          <table class="w-full text-left text-sm border-collapse">
            <thead>
              <tr class="bg-slate-50 text-slate-700 border-b border-slate-200 uppercase tracking-wider text-xs font-bold">
                <th class="p-4">Completed Date</th>
                <th class="p-4 text-center">Period</th>
                <th class="p-4">Lesson Plan Reference</th>
                <th class="p-4">Topics Covered</th>
              </tr>
            </thead>
            <tbody>
      `;

      logs.forEach(log => {
        html += `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-colors text-slate-800">
            <td class="p-4 font-mono font-bold text-slate-800">${log.date}</td>
            <td class="p-4 text-center font-bold text-slate-400">P${log.period}</td>
            <td class="p-4 text-slate-400 font-bold">${log.lesson_plan_id ? 'LP ID: ' + log.lesson_plan_id : 'Manual Entry'}</td>
            <td class="p-4 text-slate-800">${log.topics_covered || '-'}</td>
          </tr>
        `;
      });

      html += `
            </tbody>
          </table>
        </div>
      `;
      container.innerHTML = html;
    }

    function renderSummaryMatrixReport(container) {
