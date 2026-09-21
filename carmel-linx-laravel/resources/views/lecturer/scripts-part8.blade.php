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
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; Written Test&nbsp;</span></div>
    <div class="meta-row" style="margin-top: 8px; font-size: 11px;">
      <span><strong>Semester:</strong> Sem ${currentSubjectSemester}</span>
      <span><strong>Batch:</strong> ${currentSubjectClassroomId.replace(/^[A-Z]+_/, '').replace(/_/g, ' - ')}</span>
      <span><strong>Academic Year:</strong> ${currentSubjectAcademicYear}</span>
    </div>
    <div class="meta-row" style="margin-top: 4px; font-size: 11px;">
      <span><strong>Time:</strong> 1.5 Hours</span>
      <span><strong>Date:</strong> ${examDate}</span>
      <span><strong>Max Marks:</strong> ${totalMarks}</span>
    </div>
  </div>
  ${bodyHtml}
  ${cognitiveTableHtml}
  ${signatureBlockHtml}
  ${schemeTableHtml}
</body>
</html>`;

        const pw = window.open('', '_blank', 'width=900,height=700');
        pw.document.write(fullHtml);
        pw.document.close();
        pw.focus();
        setTimeout(() => { pw.print(); }, 400);
      };

      proceedWithPrint(null);
    }

    function printAnswerKey(coTag, totalMarks) {
      const data = currentSummativeTests[coTag];
      if(!data) return;

      const deptMap = {
        'EL': 'ELECTRONICS ENGINEERING',
        'CS': 'COMPUTER SCIENCE AND ENGINEERING',
        'CE': 'CIVIL ENGINEERING',
        'ME': 'MECHANICAL ENGINEERING',
        'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
        'IT': 'INFORMATION TECHNOLOGY',
        'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
      };
      const sessionBranch = "{{ session('userBranch', 'ENGINEERING') }}";
      const subjectName = currentSubjectName;
      const subjectCode = currentSubjectCode;
      const deptName = deptMap[sessionBranch.toUpperCase()] || sessionBranch;
      const examDate = data.date_of_exam
        ? new Date(data.date_of_exam).toLocaleDateString('en-IN', {day:'2-digit', month:'long', year:'numeric'})
        : 'TBA';

      const buildRubricHtml = (rubric, marks) => {
        // Fallback for older generated papers that don't have a rubric saved
        if (!rubric || rubric.length === 0) {
            if (marks <= 2) rubric = [{desc: 'Correct definition / answer', mark: marks}];
            else if (marks <= 4) rubric = [{desc: 'Key definition / concept', mark: 1}, {desc: 'Explanation / relevant points', mark: marks - 1}];
            else rubric = [{desc: 'Definition / Concept statement', mark: 1}, {desc: 'Explanation with supporting points', mark: Math.floor(marks/2)}, {desc: 'Diagram / Application', mark: marks - Math.floor(marks/2) - 1}];
        }

        return `<table style="width: 100%; border-collapse: collapse; font-size: 11px; margin-top: 4px; background: #fafafa;">
          ${rubric.map(r => `<tr>
            <td style="padding: 3px 6px; border: 1px solid #ddd;">${r.desc}</td>
            <td style="padding: 3px 6px; text-align: center; width: 50px; border: 1px solid #ddd; font-weight: bold; color: #444;">${r.mark}</td>
          </tr>`).join('')}
        </table>`;
      };

      const buildRows = (part) => {
        if (!part || !part.q_count || !part.questions) return '';
        return part.questions.map((q, i) => {
          let ansHtml = '';
          if (q.ans && q.ans.length > 0) {
            ansHtml = `<div style="margin-bottom: 8px; font-size: 12px; color: #333;">
              <ul style="margin: 0; padding-left: 16px;">
                ${q.ans.map(a => `<li style="margin-bottom: 3px;">${a}</li>`).join('')}
              </ul>
            </div>`;
          }
          
          return `<tr>
            <td style="width: 40px; text-align: center; vertical-align: top; padding: 10px 5px; border: 1px solid #000; font-weight: bold;">${i+1}</td>
            <td style="vertical-align: top; padding: 10px; border: 1px solid #000;">
              <div style="font-weight: 500; margin-bottom: 6px; font-size: 13px;">${q.q}</div>
              ${ansHtml}
              <div style="font-size: 11px; font-weight: bold; color: #555; margin-bottom: 2px; margin-top: 6px;">Marking Scheme / Answer Pointers:</div>
              ${buildRubricHtml(q.rubric, q.marks)}
            </td>
            <td style="width: 80px; text-align: center; vertical-align: middle; padding: 10px 5px; border: 1px solid #000; font-size: 14px; font-weight: bold;">${q.marks}</td>
            <td style="width: 60px; text-align: center; vertical-align: middle; padding: 10px 5px; border: 1px solid #000; font-size: 11px;">[${q.level}]</td>
          </tr>`;
        }).join('');
      };

      let bodyHtml = '';

      const tableHeader = `
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
          <thead>
            <tr>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 40px;">Q.No</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee;">Question & Expected Answer Key</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 80px;">Marks</th>
              <th style="padding: 8px; border: 1px solid #000; background: #eee; width: 60px;">Level</th>
            </tr>
          </thead>
          <tbody>
      `;

      if (data.part_a && data.part_a.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART A <small style="font-weight:normal; font-size:12px;">(${data.part_a.q_count} Ã ${data.part_a.marks_per_q} = ${data.part_a.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_a)}</tbody></table>`;
      }
      if (data.part_b && data.part_b.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART B <small style="font-weight:normal; font-size:12px;">(${data.part_b.q_count} Ã ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_b)}</tbody></table>`;
      }
      if (data.part_c && data.part_c.q_count > 0) {
        bodyHtml += `
          <h4 style="font-weight:bold; margin: 15px 0 8px; text-transform: uppercase; border-bottom: 2px solid #000; display: inline-block;">PART C <small style="font-weight:normal; font-size:12px;">(${data.part_c.q_count} Ã ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
          ${tableHeader}${buildRows(data.part_c)}</tbody></table>`;
      }

      const fullHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Answer Key - ${coTag}</title>
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
  <div class="header">
    <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
    <div class="dept-name">Department of ${deptName}</div>
    <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; ANSWER KEY & RUBRIC&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Time:</strong> 1.5 Hours</span>
      <span><strong>Date:</strong> ${examDate}</span>
      <span><strong>Max Marks:</strong> ${totalMarks}</span>
    </div>
  </div>
  ${bodyHtml}
</body>
</html>`;

      const pw = window.open('', '_blank', 'width=900,height=700');
      pw.document.write(fullHtml);
      pw.document.close();
      pw.focus();
      setTimeout(() => { pw.print(); }, 400);
    }

    function handleStaffPhotoUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      const statusEl = document.getElementById('staffPhotoUploadStatus');
      if (statusEl) {
        statusEl.classList.remove('hidden');
        statusEl.className = "text-sm font-bold mt-2 text-blue-400";
        statusEl.innerText = "Uploading photo...";
      }

      const formData = new FormData();
      formData.append('photo', file);

      fetch('/api/staff/profile/upload-photo', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(async res => {
        const data = await res.json().catch(() => ({ status: 'ERROR', message: 'Invalid server response.' }));
        if (res.ok && data.status === 'SUCCESS') {
