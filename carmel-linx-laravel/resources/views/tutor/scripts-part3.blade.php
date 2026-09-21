
      // Check if current user is Tutor (Mentor 1)
      const isTutor = (data.mentor1.mobile == '{{ session('userId') }}');
      const isMentor2 = (data.mentor2.mobile == '{{ session('userId') }}');

      // Helper to create assignment buttons
      const getActionButtons = (regNo, currentBatch) => {
        if (!isTutor) return ''; // Only Tutor can reassign
        
        if (currentBatch === null) {
          return `
            <button onclick="assignStudentBatch('${regNo}', 'A')" class="px-2 py-1 bg-sky-600 hover:bg-sky-500 text-white rounded text-xs font-bold mr-1">To A</button>
            <button onclick="assignStudentBatch('${regNo}', 'B')" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded text-xs font-bold">To B</button>
          `;
        } else if (currentBatch === 'A') {
          return `<button onclick="assignStudentBatch('${regNo}', 'B')" class="px-2 py-1 border border-emerald-600 text-emerald-400 hover:bg-emerald-950 rounded text-xs font-bold">Move to B</button>`;
        } else if (currentBatch === 'B') {
          return `<button onclick="assignStudentBatch('${regNo}', 'A')" class="px-2 py-1 border border-sky-600 text-sky-400 hover:bg-sky-950 rounded text-xs font-bold">Move to A</button>`;
        }
      };

      // Unassigned List
      unassignedList.innerHTML = '';
      if (data.unassigned.length === 0) unassignedList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">No unassigned students.</td></tr>';
      data.unassigned.forEach(s => {
        unassignedList.innerHTML += `
          <tr class="border-b border-slate-100 hover:bg-slate-50/80">
            <td class="p-3 font-bold text-slate-900">${s.name}</td>
            <td class="p-3 font-mono text-slate-500">${s.reg_no}</td>
            <td class="p-3 text-right whitespace-nowrap">${getActionButtons(s.reg_no, null)}</td>
          </tr>
        `;
      });

      // Batch A List
      batchAList.innerHTML = '';
      if (data.batch_a.length === 0) batchAList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">Empty batch.</td></tr>';
      data.batch_a.forEach(s => {
        batchAList.innerHTML += `
          <tr class="border-b border-sky-100 hover:bg-sky-50/60">
            <td class="p-3 font-bold text-sky-900">${s.name}</td>
            <td class="p-3 font-mono text-sky-500">${s.reg_no}</td>
            <td class="p-3 text-right whitespace-nowrap">${getActionButtons(s.reg_no, 'A')}</td>
          </tr>
        `;
      });

      // Batch B List
      batchBList.innerHTML = '';
      if (data.batch_b.length === 0) batchBList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">Empty batch.</td></tr>';
      data.batch_b.forEach(s => {
        batchBList.innerHTML += `
          <tr class="border-b border-emerald-100 hover:bg-emerald-50/60">
            <td class="p-3 font-bold text-emerald-900">${s.name}</td>
            <td class="p-3 font-mono text-emerald-500">${s.reg_no}</td>
            <td class="p-3 text-right whitespace-nowrap">${getActionButtons(s.reg_no, 'B')}</td>
          </tr>
        `;
      });

      // Mentoring Caseload
      myList.innerHTML = '';
      let myStudents = [];
      if (isTutor) {
        // Tutor sees everyone
        myStudents = [...data.batch_a, ...data.batch_b, ...data.unassigned];
      } else if (isMentor2) {
        // Mentor 2 sees only Batch B
        myStudents = data.batch_b;
      }
      
      if (myStudents.length === 0) {
        myList.innerHTML = '<tr><td colspan="5" class="p-4 text-center text-slate-500">You have no students in your caseload.</td></tr>';
      } else {
        myStudents.forEach(s => {
          let batchName = s.batch_label ? `Batch ${s.batch_label}` : 'Unassigned';
          let batchColor = s.batch_label === 'A' ? 'sky' : (s.batch_label === 'B' ? 'emerald' : 'amber');
          
          myList.innerHTML += `
            <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all">
              <td class="p-3.5 pl-4 font-bold text-slate-900 text-sm">${s.name}</td>
              <td class="p-3.5 font-mono text-slate-600 font-semibold text-xs">${s.reg_no}</td>
              <td class="p-3.5">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border ${s.batch_label === 'A' ? 'bg-sky-50 text-sky-700 border-sky-200' : (s.batch_label === 'B' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200')}">
                  ${batchName}
                </span>
              </td>
              <td class="p-3.5">
                <span class="text-xs font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                  ${s.diary_count || 0} entries
                </span>
              </td>
              <td class="p-3.5 pr-4 text-right whitespace-nowrap space-x-1.5">
                <a href="sms:${s.guardian_mobile || s.phone || ''}?body=${encodeURIComponent('Carmel Poly: View your ward (' + s.name + ') live attendance & status portal: ' + window.location.origin + '/parent/dashboard/' + s.reg_no)}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold border border-slate-200 transition-all no-underline shadow-2xs cursor-pointer" title="Send SMS Link to Parent">
                  <svg class="w-3.5 h-3.5 text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg><span>SMS Portal</span>
                </a>
                <a href="/tutor/mentoring-diary/${s.reg_no}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all no-underline shadow-xs cursor-pointer">
                  <span>View Diary</span>
                </a>
              </td>
            </tr>
          `;
        });
      }

      // Populate reportStudentSelect
      const reportStudentSelect = document.getElementById('reportStudentSelect');
      if (reportStudentSelect) {
        reportStudentSelect.innerHTML = '<option value="">Select student...</option>';
        const allStudents = [...data.batch_a, ...data.batch_b, ...data.unassigned];
        allStudents.forEach(s => {
          const opt = document.createElement('option');
          opt.value = s.reg_no;
          opt.innerText = `${s.name} (${s.reg_no})`;
          reportStudentSelect.appendChild(opt);
        });
      }
    }

    function viewStudentDiary(regNo, name) { window.location.href = '/tutor/mentoring-diary/' + regNo; }

    function closeDiaryModal() { closeFullMentoringDiaryModal(); }

    function assignStudentBatch(regNo, batchLabel) {
      fetch('/api/mentoring/assign-batch', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          classroom_id: selectedMentoringClassroomId,
          reg_no: regNo,
          batch_label: batchLabel
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          loadMentoringData(); // Refresh UI
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => showGlobalMessage('Failed to assign student.', true));
    }

    function toggleBatchAssignment() {
      const content = document.getElementById('batchAssignmentContent');
      const icon = document.getElementById('batchAssignIcon');
      if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
      } else {
        content.classList.add('hidden');
        icon.style.transform = '';
      }
    }

    // --- LEAVE APPROVAL & REPORTS TAB LOGIC ---
    let selectedLeaveClassroomId = '';

    function loadClassroomLeaves() {
      const selectEl = document.getElementById('leaveClassroomSelect');
      if (!selectEl || selectEl.options.length === 0 || selectEl.value === "" || selectEl.value === "Loading...") {
        ensureMentoringClassroomsLoaded(() => {
          loadClassroomLeaves();
        });
        return;
      }
      const classroomId = selectEl.value || selectedMentoringClassroomId;
      if (!classroomId) return;
      selectedLeaveClassroomId = classroomId;

      fetch(`/api/mentoring/classroom/${classroomId}/leaves`, {
        headers: getHeaders()
      })
      .then(res => res.json())
      .then(resData => {
        const tbody = document.getElementById('classroomLeavesTableBody');
        tbody.innerHTML = '';
        if (resData.status === 'SUCCESS' && resData.data.length > 0) {
          resData.data.forEach(lv => {
            const statColor = lv.status === 'Approved' ? 'text-green-400' : (lv.status === 'Rejected' ? 'text-red-400' : 'text-amber-400');
            const parentInformed = lv.parent_informed ? '<span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded text-[10px]">Informed</span>' : '';
            
            let actionHtml = '';
            if (lv.status === 'Pending') {
              actionHtml = `
                <button onclick="tutorApproveLeave(${lv.id}, 'Approved')" class="px-2 py-1 bg-green-700/30 text-green-400 hover:bg-green-600 hover:text-white rounded text-[10px] font-bold mr-1 transition-premium cursor-pointer">Approve</button>
                <button onclick="tutorApproveLeave(${lv.id}, 'Rejected')" class="px-2 py-1 bg-red-700/30 text-red-400 hover:bg-red-600 hover:text-white rounded text-[10px] font-bold transition-premium cursor-pointer">Reject</button>
              `;
            } else {
              actionHtml = `<span class="text-xs text-slate-500 font-bold">${lv.status}</span>`;
            }

            tbody.innerHTML += `
              <tr class="border-b border-slate-100 hover:bg-slate-50/80">
                <td class="p-3 font-bold text-slate-900">${lv.student_name} <span class="text-[10px] text-slate-500 font-mono block">${lv.reg_no}</span></td>
                <td class="p-3 text-slate-900 font-semibold">${lv.semester}</td>
                <td class="p-3 text-slate-700 text-xs">${lv.leave_date}</td>
                <td class="p-3 text-slate-900 font-semibold">${lv.no_of_days} day(s) ${parentInformed}</td>
                <td class="p-3 max-w-[150px] truncate" title="${lv.reason || ''}">${lv.reason || '-'}</td>
                <td class="p-3 font-bold ${statColor}">${lv.status}</td>
                <td class="p-3 text-right whitespace-nowrap">${actionHtml}</td>
              </tr>
            `;
          });
        } else {
          tbody.innerHTML = '<tr><td colspan="7" class="p-6 text-center text-slate-500">No leave records found for this classroom.</td></tr>';
        }

        // Populate reportStudentSelect dropdown for this classroom
        const reportStudentSelect = document.getElementById('reportStudentSelect');
        if (reportStudentSelect) {
          reportStudentSelect.innerHTML = '<option value="">Select student...</option>';
          if (resData.status === 'SUCCESS' && resData.students) {
            resData.students.forEach(s => {
              const opt = document.createElement('option');
              opt.value = s.reg_no;
              opt.innerText = `${s.name} (${s.reg_no})`;
              reportStudentSelect.appendChild(opt);
            });
          }
        }
      });
    }

    function tutorApproveLeave(leaveId, decision) {
      if (!confirm('Are you sure you want to ' + decision.toLowerCase() + ' this leave request?')) return;
      fetch('/api/mentoring/leave/approve', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ id: leaveId, status: decision })
      })
      .then(res => res.json())
      .then(resData => {
        if (resData.status === 'SUCCESS') {
          loadClassroomLeaves();
        } else {
          alert('Error: ' + resData.message);
        }
      });
    }

    function printStudentFullDiary() {
      const regNo = document.getElementById('reportStudentSelect').value;
      if (!regNo) {
        alert('Please select a student.');
        return;
      }
      window.open(`/diary/${regNo}/print`, '_blank');
    }

    function printStudentLeaveReport() {
      const regNo = document.getElementById('reportStudentSelect').value;
      if (!regNo) {
        alert('Please select a student.');
        return;
      }
      window.open(`/diary/${regNo}/leave-report`, '_blank');
    }

    function printCondonationReport() {
      const selectEl = document.getElementById('leaveClassroomSelect');
      const classroomId = selectEl.value || selectedMentoringClassroomId;
      if (!classroomId) {
        alert('Please select a classroom first.');
        return;
      }
      window.open(`/classroom/${classroomId}/condonation-report`, '_blank');
    }

    function loadTutorStudents() {
      const list = document.getElementById('tutorRollNumberList');
      list.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">Loading students...</td></tr>';
      fetch('/api/tutor/attendance/students')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            let html = '';
            if (data.students.length === 0) {
              list.innerHTML = '<tr><td colspan="5" class="p-6 text-center text-slate-500 font-medium">No students in your classroom.</td></tr>';
              return;
            }
            data.students.forEach((s, idx) => {
              html += `
                <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition-all student-roll-row" data-reg="${s.reg_no}">
                  <td class="p-3.5 pl-5 text-center font-bold text-slate-500 text-xs">${idx+1}</td>
                  <td class="p-3.5 font-mono font-semibold text-slate-600 text-xs">${s.reg_no}</td>
                  <td class="p-3.5 font-mono font-semibold text-blue-600 text-xs">${s.sbte_reg_no || '-'}</td>
                  <td class="p-3.5 font-bold text-slate-900 text-sm">${s.name}</td>
                  <td class="p-2.5 pr-5 text-center">
                    <input type="number" class="w-24 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-center font-bold text-slate-900 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 roll-no-input text-sm outline-none transition-all shadow-2xs" value="${s.roll_no || ''}" min="1" placeholder="-">
                  </td>
                </tr>
              `;
            });
            list.innerHTML = html;
          } else {
            list.innerHTML = `<tr><td colspan="5" class="p-6 text-center text-red-400">${data.message || 'Failed to load students.'}</td></tr>`;
          }
        });
    }

    function autoFillRollNumbers() {
      const rows = Array.from(document.querySelectorAll('.student-roll-row'));
      if (rows.length === 0) return;

      // Sort rows alphabetically by student name
      rows.sort((a, b) => {
        const nameA = a.querySelector('td:nth-child(4)').innerText.trim().toLowerCase();
        const nameB = b.querySelector('td:nth-child(4)').innerText.trim().toLowerCase();
        return nameA.localeCompare(nameB);
      });

      // Update the roll number inputs sequentially on screen
      rows.forEach((row, index) => {
        const input = row.querySelector('.roll-no-input');
        if (input) {
          input.value = index + 1;
        }
      });
      
      showGlobalMessage('Roll numbers auto-filled alphabetically (1 to ' + rows.length + '). Review and click Save.');
    }

    function saveRollNumbers() {
      const rows = document.querySelectorAll('.student-roll-row');
      const rollNumbers = [];
      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const rollNoVal = row.querySelector('.roll-no-input').value.trim();
        rollNumbers.push({
          reg_no: regNo,
          roll_no: rollNoVal ? parseInt(rollNoVal) : null
        });
      });

      fetch('/api/tutor/attendance/roll-numbers', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ roll_numbers: rollNumbers })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage(data.message);
          loadTutorStudents();
        } else {
          showGlobalMessage(data.message || "Failed to update roll numbers.", true);
        }
      })
      .catch(err => {
        console.error(err);
        showGlobalMessage("Error saving roll numbers.", true);
      });
    }

    function printClassRegister() {
      const semSelect = document.getElementById('printSemesterSelect');
      if (!semSelect) return;
      const targetSem = semSelect.value; // e.g. "S3"
      const targetSemNum = parseInt(targetSem.replace('S', ''));

      const tutorMobile = "{{ session('userId') }}";
      
      const indicator = document.getElementById('loadingIndicator');
      if (indicator) indicator.classList.remove('hidden');

      fetch(`/api/tutor/classroom/${tutorMobile}`)
        .then(res => res.json())
        .then(data => {
          if (indicator) indicator.classList.add('hidden');
          if (data.status !== 'SUCCESS') {
            alert('Failed to retrieve classroom data: ' + data.message);
            return;
          }

          const students = data.students || [];
          
          // 1. Group students
          const activeList = [];
          const discontinuedList = [];

          students.forEach(s => {
            const isInactive = s.academic_status === 'Discontinued' || s.academic_status === 'TC Issued';
            const studentSemNum = parseInt(String(s.semester || 'S1').replace('S', ''));

            if (isInactive && studentSemNum < targetSemNum) {
              // Discontinued in a prior semester
              discontinuedList.push(s);
            } else if (!isInactive) {
              // Active student in target semester
              activeList.push(s);
            }
          });

          // 2. Sort active students alphabetically by name
          activeList.sort((a, b) => a.name.localeCompare(b.name));

          // 3. Build Print HTML
          const printWindow = window.open('', '_blank');
          if (!printWindow) {
            alert('Popup blocker blocked the print preview. Please allow popups.');
            return;
          }

          const branchName = "{{ session('userBranch') }}".toUpperCase();
          const batchYear = window.supervisedBatchYear || data.batchYear || 'N/A';
          const batchEnd = parseInt(batchYear) ? parseInt(batchYear) + 3 : 'N/A';
          const tutorName = window.supervisedTutorName || data.tutorName || 'Not Assigned';
          const mentorName = window.supervisedMentorName || data.mentorName || 'Not Assigned';

          const printDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

          let activeRows = '';
          activeList.forEach((s, idx) => {
            const isLateral = (s.reg_no && s.reg_no.toUpperCase().endsWith('L')) || (s.sbte_reg_no && s.sbte_reg_no.toUpperCase().endsWith('L'));
            let remark = s.status_notes || '-';
            if (isLateral) {
              remark = remark !== '-' ? 'Lateral Entry; ' + remark : 'Lateral Entry';
            }
            activeRows += `
              <tr>
                <td>${idx + 1}</td>
                <td>${s.name}</td>
                <td>${s.reg_no}</td>
                <td>${s.sbte_reg_no || '-'}</td>
                <td>${s.admission_year || 'N/A'}</td>
                <td>${s.semester || 'S1'}</td>
                <td>${s.academic_status || 'Active'}</td>
                <td>${remark}</td>
              </tr>
            `;
          });

          if (activeList.length === 0) {
            activeRows = `<tr><td colspan="8" style="text-align:center; padding:15px; color:#555;">No active students in this semester.</td></tr>`;
          }

          let discontinuedRows = '';
          discontinuedList.forEach((s, idx) => {
            const leftSem = s.semester || 'S1';
            const isLateral = (s.reg_no && s.reg_no.toUpperCase().endsWith('L')) || (s.sbte_reg_no && s.sbte_reg_no.toUpperCase().endsWith('L'));
            let remark = s.status_notes || '-';
            if (isLateral) {
              remark = remark !== '-' ? 'Lateral Entry; ' + remark : 'Lateral Entry';
            }
            discontinuedRows += `
