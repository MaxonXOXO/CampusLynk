          if (statusEl) {
            statusEl.className = "text-sm font-bold mt-2 text-green-400";
            statusEl.innerText = "Photo updated successfully!";
          }

          const photoUrl = data.photo_url + '?t=' + new Date().getTime();
          document.querySelectorAll('#staffProfileImg, #sidebarStaffImg, #sidebarAvatarContainer img, aside img.rounded-full').forEach(img => {
            img.src = photoUrl;
          });

          if (statusEl) {
            setTimeout(() => statusEl.classList.add('hidden'), 3000);
          }
        } else {
          if (statusEl) {
            statusEl.className = "text-sm font-bold mt-2 text-rose-400";
            statusEl.innerText = data.message || "Upload failed.";
          }
        }
      })
      .catch(err => {
        console.error('Upload error:', err);
        if (statusEl) {
          statusEl.className = "text-sm font-bold mt-2 text-rose-400";
          statusEl.innerText = "Error uploading photo. Please check file format and size.";
        }
      });
    }

    function loadSecurityLogs() {
      const tbody = document.getElementById('securityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">Querying security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500">No profile action logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-100 text-sm hover:bg-slate-50/70";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-red-400 font-bold">Error querying logs.</td></tr>`;
        });
    }
  
      function deleteOnlineTest(testId, subjectId) {
        if (!confirm("Are you sure you want to delete this online test? This will permanently remove all student attempts and records associated with it.")) return;
        fetch(`/api/classroom/online-tests/${testId}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            loadActiveOnlineTests(subjectId);
          } else {
            alert(data.message || "Failed to delete test.");
          }
        });
      }

      function printOnlineTest(testId) {
        fetch(`/api/classroom/online-tests/${testId}/key`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
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
            const testName = data.test_name || 'Online Test';
            const totalQ = data.total || 0;

            let html = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Online MCQ Test - ${testName}</title>
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
    <div style="margin-top:6px;"><span class="exam-title">&nbsp;${testName} &ndash; Answer Key&nbsp;</span></div>
    <div class="meta-row">
      <span><strong>Total Questions:</strong> ${totalQ}</span>
    </div>
  </div>`;

            data.details.forEach((q, i) => {
              html += `<div class="q-block">
                <div class="q-text">${i+1}. ${q.q} &nbsp; <em>[${q.co}]</em></div>
                <ul class="options">`;
              q.options.forEach(opt => {
                let isCorrect = (opt === q.correct_ans);
                if (isCorrect) {
                  html += `<li><strong>${opt} &nbsp; &#10004;</strong></li>`;
                } else {
                  html += `<li>${opt}</li>`;
                }
              });
              html += `</ul></div>`;
            });

            html += `</body></html>`;
            let pw = window.open('', '_blank', 'width=800,height=600');
            pw.document.write(html);
            pw.document.close();
            pw.focus();
            setTimeout(() => { pw.print(); }, 500);
          } else {
            alert(data.message);
          }
        });
      }

      function showVcStudentsList() {
        const badge = document.getElementById('vcModalBatchBadge');
        if (badge) {
          badge.innerText = `${window.currentVirtualBatchId || ''} (S-${window.currentVirtualSemester || 1})`;
        }
        let html = '';
        if (window.currentVirtualStudents && window.currentVirtualStudents.length > 0) {
          html = `
            <table class="w-full text-left text-sm border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 text-xs font-bold uppercase tracking-wider">
                  <th class="p-3.5 font-bold w-12 text-center">No.</th>
                  <th class="p-3.5 font-bold w-20 text-center">Roll No</th>
                  <th class="p-3.5 font-bold w-32 text-center">SBTE Reg No</th>
                  <th class="p-3.5 font-bold w-32 text-center">Admission No</th>
                  <th class="p-3.5 font-bold">Student Name</th>
                  <th class="p-3.5 font-bold w-48">Remarks</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
          `;
          window.currentVirtualStudents.forEach((s, idx) => {
            html += `
              <tr class="hover:bg-slate-50/80 transition-colors text-sm text-slate-800">
                <td class="p-3 text-center text-slate-700 font-bold">${idx + 1}</td>
                <td class="p-3 text-center font-mono text-slate-900 font-bold">${s.roll_no || '-'}</td>
                <td class="p-3 text-center font-mono text-slate-600 text-xs font-semibold">${s.sbte_reg_no || '-'}</td>
                <td class="p-3 text-center font-mono text-slate-600 text-xs font-semibold">${s.reg_no}</td>
                <td class="p-3 font-bold text-slate-900 max-w-[220px] whitespace-normal break-words">${s.name}</td>
                <td class="p-2.5"><input type="text" placeholder="Add remark..." class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 shadow-2xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none"></td>
              </tr>
            `;
          });
          html += `</tbody></table>`;
        } else {
          html = '<p class="text-sm font-bold text-slate-500 text-center py-6">No students enrolled in this classroom.</p>';
        }
        document.getElementById('vcStudentsListContent').innerHTML = html;
        document.getElementById('vcStudentsModal').classList.remove('hidden');
      }

      function closeVcStudentsList() {
        document.getElementById('vcStudentsModal').classList.add('hidden');
      }

      function printVcStudentsList() {
        if (!window.currentVirtualStudents || window.currentVirtualStudents.length === 0) {
            alert("No students to print.");
            return;
        }

        const deptMap = {
          'EL': 'ELECTRONICS ENGINEERING',
          'CS': 'COMPUTER SCIENCE AND ENGINEERING',
          'CE': 'CIVIL ENGINEERING',
          'ME': 'MECHANICAL ENGINEERING',
          'EE': 'ELECTRICAL AND ELECTRONICS ENGINEERING',
          'IT': 'INFORMATION TECHNOLOGY',
          'ECE': 'ELECTRONICS AND COMMUNICATION ENGINEERING'
        };
        const branchCode = "{{ session('userBranch', '') }}";
        const deptName = deptMap[branchCode.toUpperCase()] || branchCode;
        const batchName = document.getElementById('vcSubtitle') ? document.getElementById('vcSubtitle').innerText.replace('Batch:', '').trim() : '';
        const revision = window.currentSyllabusRevision || '2021';
