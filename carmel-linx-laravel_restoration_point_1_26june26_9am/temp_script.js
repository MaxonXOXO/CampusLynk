
    let activePanel = "roster";
    let selectedUserForReset = null;
    let currentSemester = 1;
    let currentStudentsData = [];
    let currentSubjectsData = [];
    let selectedStudentRegNo = null;

    function switchSemester(sem) {
      currentSemester = sem;
      // Update UI for buttons
      for(let i=1; i<=6; i++) {
        let btn = document.getElementById('semBtn'+i);
        if(i === sem) {
          btn.className = "px-5 py-2 rounded-lg text-xs font-bold transition-premium border bg-sky-600 border-sky-500 text-white shadow-lg shadow-sky-500/20";
        } else {
          btn.className = "px-5 py-2 rounded-lg text-xs font-bold transition-premium border bg-slate-900 border-slate-800 text-slate-400 hover:bg-slate-800 hover:text-white";
        }
      }
      loadUsers();
    }    document.addEventListener("DOMContentLoaded", () => {
      // Check if routed directly to mentoring
      if (sessionStorage.getItem('openMentoring') === 'true') {
        sessionStorage.removeItem('openMentoring');
        activePanel = 'mentoring';
      }

      if (activePanel === 'roster') loadUsers();
      if (activePanel === 'audit') loadAuditTrail();
      if (activePanel === 'profile') loadSelfSecurityLogs();
      if (activePanel === 'mentoring') {
        switchPanel('mentoring'); // Ensures UI is updated
      }
    });

    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function switchPanel(panelId) {
      activePanel = panelId;
      
      const panels = ['roster', 'audit', 'profile', 'mentoring'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      const titles = {
        'roster': 'Supervised Class Roster',
        'audit': 'Classroom Audit Trail',
        'profile': 'My Tutor Profile',
        'mentoring': 'Mentoring Batches'
      };
      document.getElementById('panelTitle').innerText = titles[panelId];

      if (panelId === 'roster') loadUsers();
      if (panelId === 'audit') loadAuditTrail();
      if (panelId === 'profile') loadSelfSecurityLogs();
      if (panelId === 'mentoring') initMentoringPanel();
    }

    function showGlobalMessage(msg, isError = false) {
      const alert = document.getElementById('globalAlert');
      alert.classList.remove('hidden');
      if (isError) {
        alert.className = "p-4 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border-red-900 block shadow-sm";
      } else {
        alert.className = "p-4 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border-green-900 block shadow-sm";
      }
      alert.innerText = msg;
      setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch(`/api/tutor/semester-data?semester=${currentSemester}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            currentStudentsData = data.students;
            currentSubjectsData = data.subjects;
            renderUsersGrid();
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to fetch semester data.', true);
        });
    }

    function renderUsersGrid() {
      const tbody = document.getElementById('usersTableBody');
      const thead = document.getElementById('usersTableHeader');
      const searchStr = document.getElementById('filterSearch').value.toLowerCase();
      const statusFilter = document.getElementById('filterStatus').value;

      // Render Dynamic Header
      let headerHTML = `
        <tr class="bg-slate-900/60 border-b border-slate-800/60 text-slate-400 font-bold">
          <th class="p-4">Profile</th>
          <th class="p-4">Reg No</th>
      `;
      currentSubjectsData.forEach(sub => {
        headerHTML += `<th class="p-4" title="${sub.name}">${sub.code}</th>`;
      });
      headerHTML += `
          <th class="p-4">SGPA</th>
          <th class="p-4">Att %</th>
          <th class="p-4">Act Pts</th>
          <th class="p-4 text-right">Actions</th>
        </tr>
      `;
      thead.innerHTML = headerHTML;

      tbody.innerHTML = "";

      let filtered = currentStudentsData.filter(u => {
        const matchSearch = u.name.toLowerCase().includes(searchStr) || u.reg_no.toLowerCase().includes(searchStr);
        const matchStatus = statusFilter === "" || u.status === statusFilter;
        return matchSearch && matchStatus;
      });

      if (filtered.length === 0) {
        tbody.innerHTML = `
          <tr>
            <td colspan="${5 + currentSubjectsData.length}" class="p-8 text-center text-slate-500 font-medium font-sans">
              No classroom students found for this semester filter.
            </td>
          </tr>
        `;
        return;
      }

      filtered.forEach(user => {
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium cursor-pointer";
        tr.onclick = (e) => {
          // don't trigger modal if they clicked an action button
          if(e.target.closest('button')) return;
          openStudentProfileModal(user.reg_no);
        };

        let rowHTML = `
          <td class="p-4">
            <div class="flex items-center gap-3">
              <img src="${user.photo_url || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100'}" class="w-8 h-8 rounded-full border border-slate-700 object-cover shadow-sm">
              <span class="font-bold text-slate-200">${user.name}</span>
            </div>
          </td>
          <td class="p-4 font-mono text-slate-400">${user.reg_no}</td>
        `;

        currentSubjectsData.forEach(sub => {
          let grade = user.subjects[sub.code] || '-';
          let gradeClass = grade === 'F' ? 'text-red-400 font-bold' : 'text-slate-300';
          rowHTML += `<td class="p-4 ${gradeClass}">${grade}</td>`;
        });

        rowHTML += `
          <td class="p-4 font-bold text-slate-200">${user.sgpa}</td>
          <td class="p-4 font-bold text-slate-200">${user.attendance}%</td>
          <td class="p-4 font-bold text-slate-200">${user.activity_points}</td>
          <td class="p-4 text-right">
            <button onclick="triggerPasswordReset('${user.reg_no}', 'student', '${user.name}')" class="px-2 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded text-[10px] font-bold transition-premium">
              Reset Pwd
            </button>
          </td>
        `;
        tr.innerHTML = rowHTML;
        tbody.appendChild(tr);
      });
    }

    function openStudentProfileModal(regNo) {
      selectedStudentRegNo = regNo;
      document.getElementById('spName').innerText = "Loading...";
      document.getElementById('spRegNo').innerText = regNo;
      document.getElementById('spSemestersBody').innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-500">Fetching records...</td></tr>`;
      document.getElementById('spPlacementDetails').innerText = "Loading...";
      document.getElementById('spRemarks').value = "";
      document.getElementById('spRemarksAlert').classList.add('hidden');

      const modal = document.getElementById('studentProfileModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      fetch(`/api/tutor/student-profile/${regNo}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            const student = data.student;
            document.getElementById('spName').innerText = student.name;
            document.getElementById('spPhoto').src = student.photo_url || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100';
            
            document.getElementById('spPlacementDetails').innerHTML = student.placement_details ? 
              student.placement_details.replace(/\n/g, '<br>') : 
              '<span class="text-slate-500 italic">No placement records available yet.</span>';
            
            document.getElementById('spRemarks').value = student.higher_studies_remark || "";

            let semHtml = "";
            for(let i=1; i<=6; i++) {
              let semData = data.semesters.find(s => s.semester === i);
              if (semData) {
                semHtml += `
                  <tr class="border-b border-slate-800/40 hover:bg-slate-800/30">
                    <td class="p-2 font-bold text-slate-300">S${i}</td>
                    <td class="p-2 text-slate-300 font-mono">${semData.sgpa}</td>
                    <td class="p-2 text-sky-400 font-mono">${semData.cgpa || '-'}</td>
                    <td class="p-2 text-slate-300">${semData.activity_points}</td>
                  </tr>
                `;
              } else {
                semHtml += `
                  <tr class="border-b border-slate-800/40 opacity-50">
                    <td class="p-2 font-bold text-slate-500">S${i}</td>
                    <td class="p-2 text-slate-600">-</td>
                    <td class="p-2 text-slate-600">-</td>
                    <td class="p-2 text-slate-600">-</td>
                  </tr>
                `;
              }
            }
            document.getElementById('spSemestersBody').innerHTML = semHtml;
          }
        });
    }

    function closeStudentProfileModal() {
      const modal = document.getElementById('studentProfileModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function saveStudentRemarks() {
      if (!selectedStudentRegNo) return;
      const remarks = document.getElementById('spRemarks').value;
      const alertBox = document.getElementById('spRemarksAlert');

      fetch(`/api/tutor/student-profile/${selectedStudentRegNo}/remarks`, {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ higher_studies_remark: remarks })
      })
      .then(res => res.json())
      .then(data => {
        alertBox.classList.remove('hidden');
        if(data.status === 'SUCCESS') {
          alertBox.className = "mt-2 p-2 rounded-lg text-xs font-bold border border-green-900 bg-green-950/40 text-green-400 block";
          alertBox.innerText = "Remarks saved successfully!";
          setTimeout(() => alertBox.classList.add('hidden'), 3000);
        } else {
          alertBox.className = "mt-2 p-2 rounded-lg text-xs font-bold border border-red-900 bg-red-950/40 text-red-400 block";
          alertBox.innerText = data.message;
        }
      });
    }

    function changeStatus(userId, userType, newStatus) {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/admin/user/toggle-status', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ userId, userType, newStatus })
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Student status updated successfully.');
          loadUsers();
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => {
        indicator.classList.add('hidden');
        showGlobalMessage('Failed to update status.', true);
      });
    }

    function triggerPasswordReset(userId, userType, userName) {
      selectedUserForReset = { userId, userType };
      document.getElementById('pwdResetName').innerText = userName;
      document.getElementById('pwdResetId').innerText = userId;
      document.getElementById('newPasswordInput').value = "";
      document.getElementById('pwdAlert').classList.add('hidden');
      
      const modal = document.getElementById('passwordModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closePasswordModal() {
      const modal = document.getElementById('passwordModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      selectedUserForReset = null;
    }

    function submitPasswordReset() {
      const pwd = document.getElementById('newPasswordInput').value.trim();
      const pwdAlert = document.getElementById('pwdAlert');
      
      if (pwd.length < 4) {
        pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Password must be at least 4 characters long.";
        pwdAlert.classList.remove('hidden');
        return;
      }

      fetch('/api/admin/user/reset-password', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          userId: selectedUserForReset.userId,
          userType: selectedUserForReset.userType,
          newPassword: pwd
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Password reset successfully.');
          closePasswordModal();
        } else {
          pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          pwdAlert.innerText = data.message;
          pwdAlert.classList.remove('hidden');
        }
      })
      .catch(() => {
        pwdAlert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        pwdAlert.innerText = "Request failed.";
        pwdAlert.classList.remove('hidden');
      });
    }

    let selectedUserForAcadStatus = null;
    function openAcademicStatusModal(userId, userName, currentStatus, notes) {
      selectedUserForAcadStatus = userId;
      document.getElementById('acadStatusName').innerText = userName;
      document.getElementById('acadStatusId').innerText = userId;
      document.getElementById('acadStatusSelect').value = currentStatus || 'Active';
      document.getElementById('acadStatusNotes').value = notes || '';
      document.getElementById('acadStatusAlert').classList.add('hidden');
      
      const modal = document.getElementById('academicStatusModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeAcademicStatusModal() {
      const modal = document.getElementById('academicStatusModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      selectedUserForAcadStatus = null;
    }

    function submitAcademicStatus() {
      const status = document.getElementById('acadStatusSelect').value;
      const notes = document.getElementById('acadStatusNotes').value;
      const alert = document.getElementById('acadStatusAlert');

      fetch('/api/admin/user/academic-status', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({
          userId: selectedUserForAcadStatus,
          academicStatus: status,
          statusNotes: notes
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'SUCCESS') {
          showGlobalMessage('Academic status updated successfully.');
          closeAcademicStatusModal();
          loadUsers();
        } else {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900 block";
        alert.innerText = "Request failed.";
        alert.classList.remove('hidden');
      });
    }

    let currentPromotionStudents = [];

    function promoteBatch() {
      // Open the modal instead of direct confirm
      const modal = document.getElementById('semesterPromotionModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      
      const tbody = document.getElementById('promoTableBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-500">Loading students...</td></tr>`;

      fetch('/api/tutor/active-students', { headers: getHeaders() })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            currentPromotionStudents = data.data;
            tbody.innerHTML = '';
            if (currentPromotionStudents.length === 0) {
              tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-500">No active students found in your batch.</td></tr>`;
              return;
            }

            currentPromotionStudents.forEach(student => {
              tbody.innerHTML += `
                <tr class="border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium" data-reg="${student.reg_no}">
                  <td class="p-3 font-mono">${student.reg_no}</td>
                  <td class="p-3 font-bold">${student.name}</td>
                  <td class="p-3">
                    <select class="promo-action w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-purple-500/50">
                      <option value="Promote">Promote to Next Sem</option>
                      <option value="Discontinued">Discontinued</option>
                      <option value="Semester Drop">Semester Drop</option>
                    </select>
                  </td>
                  <td class="p-3">
                    <input type="text" class="promo-remarks w-full bg-slate-900/80 border border-slate-700/60 rounded px-2 py-1 text-slate-300 text-[10px] focus:outline-none focus:border-purple-500/50" placeholder="e.g. TC Issued (Optional)">
                  </td>
                </tr>
              `;
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-red-500">Failed to load students.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-red-500">Network error.</td></tr>`;
        });
    }

    function closeSemesterPromotionModal() {
      const modal = document.getElementById('semesterPromotionModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function submitSemesterPromotion() {
      const rows = document.querySelectorAll('#promoTableBody tr[data-reg]');
      if(rows.length === 0) return;

      if(!confirm("Are you sure you want to finalize this promotion? The classroom semester will increment, and any students marked as Discontinued/Drop will be updated.")) return;

      const payload = {
        promotions: []
      };

      rows.forEach(row => {
        const regNo = row.getAttribute('data-reg');
        const action = row.querySelector('.promo-action').value;
        const remarks = row.querySelector('.promo-remarks').value;
        
        payload.promotions.push({
          reg_no: regNo,
          action: action,
          remarks: remarks
        });
      });

      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch('/api/tutor/submit-promotion', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          ...getHeaders()
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        indicator.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          showGlobalMessage(`Promotion successful! New Semester: ${data.new_semester}`);
          closeSemesterPromotionModal();
          loadUsers(); // refresh tutor grid
        } else {
          showGlobalMessage(data.message, true);
        }
      })
      .catch(() => {
        indicator.classList.add('hidden');
        showGlobalMessage('Failed to submit promotion.', true);
      });
    }

    function loadAuditTrail() {
      const tbody = document.getElementById('auditTableBody');
      tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">Querying classroom audit logs...</td></tr>`;

      fetch('/api/audit-logs')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-slate-500 font-bold">No classroom audit logs found.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 hover:bg-slate-900/30 transition-premium";
              
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-4 text-slate-400 font-mono">${date}</td>
                <td class="p-4 font-bold text-slate-300">${log.performed_by_name || 'System'}<br><span class="text-[10px] text-slate-500 font-mono">${log.performed_by || ''}</span></td>
                <td class="p-4 font-bold text-white">${log.target_name}<br><span class="text-[10px] text-blue-400 font-mono">${log.target_id}</span></td>
                <td class="p-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-4 font-mono text-slate-400">${log.ip_address || '-'}</td>
                <td class="p-4 text-slate-300 font-sans leading-relaxed">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Error loading logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-red-400 font-bold">Request failed.</td></tr>`;
        });
    }

    function viewUserAudit(userId, userName) {
      document.getElementById('auditProfileName').innerText = userName;
      document.getElementById('auditProfileId').innerText = userId;
      
      const tbody = document.getElementById('modalAuditTableBody');
      tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-500">Retrieving profile logs...</td></tr>`;

      const modal = document.getElementById('auditModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      fetch(`/api/audit-logs?targetId=${userId}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-slate-500">No profile history events found.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800/40 text-xs";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3 font-semibold text-slate-300">${log.performed_by_name || 'System'}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Error loading.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="4" class="p-6 text-center text-red-400 font-bold">Failed.</td></tr>`;
        });
    }

    function closeAuditModal() {
      const modal = document.getElementById('auditModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function confirmDeleteUser(userId, userType, userName) {
      if (confirm(`Are you absolutely sure you want to permanently delete the profile of ${userName} (${userId})? This action will remove all database credentials.`)) {
        const indicator = document.getElementById('loadingIndicator');
        indicator.classList.remove('hidden');

        fetch('/api/admin/user/delete', {
          method: 'POST',
          headers: getHeaders(),
          body: JSON.stringify({ targetId: userId, userType })
        })
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            showGlobalMessage('Student profile deleted successfully.');
            loadUsers();
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to delete student profile.', true);
        });
      }
    }

    function openRegisterModal() {
      document.getElementById('directRegisterForm').reset();
      document.getElementById('directRegAlert').classList.add('hidden');
      
      const modal = document.getElementById('registerModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeRegisterModal() {
      const modal = document.getElementById('registerModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function handleDirectRegister(e) {
      e.preventDefault();
      const alert = document.getElementById('directRegAlert');
      const spinner = document.getElementById('directRegSpinner');
      
      alert.classList.add('hidden');
      spinner.classList.remove('hidden');

      const formData = new FormData();
      formData.append('name', document.getElementById('directRegName').value);
      formData.append('email', document.getElementById('directRegEmail').value);
      formData.append('password', document.getElementById('directRegPassword').value);
      formData.append('regNo', document.getElementById('directRegStudentId').value);
      formData.append('admNo', document.getElementById('directRegStudentAdm').value);
      formData.append('branch', document.getElementById('directRegStudentBranch').value);
      formData.append('admissionYear', document.getElementById('directRegStudentYear').value);
      formData.append('admissionType', 'Regular');

      fetch('/register/student', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        spinner.classList.add('hidden');
        if (data.status === 'SUCCESS') {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alert.innerText = "Student registered successfully.";
          alert.classList.remove('hidden');
          setTimeout(() => {
            closeRegisterModal();
            loadUsers();
          }, 1500);
        } else {
          alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
          alert.innerText = data.message;
          alert.classList.remove('hidden');
        }
      })
      .catch(() => {
        spinner.classList.add('hidden');
        alert.className = "p-3 rounded-xl text-xs font-bold bg-red-950/40 text-red-400 border border-red-900/60 block";
        alert.innerText = "Request failed.";
        alert.classList.remove('hidden');
      });
    }

    function loadSelfSecurityLogs() {
      const tbody = document.getElementById('selfSecurityLogsTable');
      tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">Querying security logs...</td></tr>`;

      fetch(`/api/audit-logs?targetId=123`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            tbody.innerHTML = "";
            if (data.logs.length === 0) {
              tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-slate-500">No profile action logs recorded.</td></tr>`;
              return;
            }
            data.logs.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = "border-b border-slate-800 text-xs";
              const date = new Date(log.created_at).toLocaleString();
              tr.innerHTML = `
                <td class="p-3 text-slate-400 font-mono">${date}</td>
                <td class="p-3"><span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                <td class="p-3 text-slate-300">${log.details || ''}</td>
              `;
              tbody.appendChild(tr);
            });
          } else {
            tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Failed to load logs.</td></tr>`;
          }
        })
        .catch(() => {
          tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-red-400 font-bold">Error querying logs.</td></tr>`;
    // ==========================================
    // MENTORING BATCHES LOGIC
    // ==========================================

    let mentoringDataCache = null;
    let selectedMentoringClassroomId = null;

    function initMentoringPanel() {
      const select = document.getElementById('mentorClassroomSelect');
      select.innerHTML = '<option value="">Loading...</option>';
      
      fetch('/api/mentoring/my-batches')
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            select.innerHTML = '';
            if (data.batches.length === 0) {
              select.innerHTML = '<option value="">No mentored classrooms</option>';
              document.getElementById('unassignedList').innerHTML = `<tr><td class="p-4 text-center text-slate-500">You are not assigned as a Mentor to any classroom.</td></tr>`;
              return;
            }

            data.batches.forEach(b => {
              const opt = document.createElement('option');
              opt.value = b.classroom_id;
              opt.innerText = `${b.classroom_id} (Admission ${b.batch_year})`;
              select.appendChild(opt);
            });
            
            selectedMentoringClassroomId = select.value;
            loadMentoringData();
          } else {
            select.innerHTML = '<option value="">Failed to load</option>';
          }
        })
        .catch(() => {
          select.innerHTML = '<option value="">Error</option>';
        });
    }

    function loadMentoringData() {
      const select = document.getElementById('mentorClassroomSelect');
      selectedMentoringClassroomId = select.value;
      if (!selectedMentoringClassroomId) return;

      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');

      fetch(`/api/mentoring/report/${selectedMentoringClassroomId}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            mentoringDataCache = data;
            renderMentoringUI(data);
          } else {
            showGlobalMessage(data.message, true);
          }
        })
        .catch(() => {
          indicator.classList.add('hidden');
          showGlobalMessage('Failed to load mentoring data.', true);
        });
    }

    function renderMentoringUI(data) {
      document.getElementById('mentorAInfo').innerText = data.mentor1.name + ' (' + data.mentor1.mobile + ')';
      document.getElementById('mentorBInfo').innerText = data.mentor2.name + ' (' + data.mentor2.mobile + ')';

      const unassignedList = document.getElementById('unassignedList');
      const batchAList = document.getElementById('batchAList');
      const batchBList = document.getElementById('batchBList');
      const myList = document.getElementById('myMentoringStudentsList');

      document.getElementById('unassignedCountBadge').innerText = data.unassigned.length;
      document.getElementById('batchACountBadge').innerText = data.batch_a.length;
      document.getElementById('batchBCountBadge').innerText = data.batch_b.length;

      // Check if current user is Tutor (Mentor 1)
      const isTutor = (data.mentor1.mobile == '123');
      const isMentor2 = (data.mentor2.mobile == '123');

      // Helper to create assignment buttons
      const getActionButtons = (regNo, currentBatch) => {
        if (!isTutor) return ''; // Only Tutor can reassign
        
        if (currentBatch === null) {
          return `
            <button onclick="assignStudentBatch('${regNo}', 'A')" class="px-2 py-1 bg-sky-600 hover:bg-sky-500 text-white rounded text-[10px] font-bold mr-1">To A</button>
            <button onclick="assignStudentBatch('${regNo}', 'B')" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-500 text-white rounded text-[10px] font-bold">To B</button>
          `;
        } else if (currentBatch === 'A') {
          return `<button onclick="assignStudentBatch('${regNo}', 'B')" class="px-2 py-1 border border-emerald-600 text-emerald-400 hover:bg-emerald-950 rounded text-[10px] font-bold">Move to B</button>`;
        } else if (currentBatch === 'B') {
          return `<button onclick="assignStudentBatch('${regNo}', 'A')" class="px-2 py-1 border border-sky-600 text-sky-400 hover:bg-sky-950 rounded text-[10px] font-bold">Move to A</button>`;
        }
      };

      // Unassigned List
      unassignedList.innerHTML = '';
      if (data.unassigned.length === 0) unassignedList.innerHTML = '<tr><td class="p-4 text-center text-slate-500">No unassigned students.</td></tr>';
      data.unassigned.forEach(s => {
        unassignedList.innerHTML += `
          <tr class="border-b border-slate-800/40 hover:bg-slate-800/40">
            <td class="p-3 font-bold text-slate-200">${s.name}</td>
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
          <tr class="border-b border-sky-900/40 hover:bg-sky-900/20">
            <td class="p-3 font-bold text-sky-100">${s.name}</td>
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
          <tr class="border-b border-emerald-900/40 hover:bg-emerald-900/20">
            <td class="p-3 font-bold text-emerald-100">${s.name}</td>
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
            <tr class="border-b border-slate-800/40 hover:bg-slate-800/20">
              <td class="p-3 font-bold text-slate-200">${s.name}</td>
              <td class="p-3 font-mono text-slate-400">${s.reg_no}</td>
              <td class="p-3">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-${batchColor}-500/10 text-${batchColor}-400 border border-${batchColor}-500/20">
                  ${batchName}
                </span>
              </td>
              <td class="p-3 font-bold text-slate-300">
                ${s.diary_count || 0} entries
              </td>
              <td class="p-3 text-right">
                <button onclick="viewStudentDiary('${s.reg_no}', '${s.name}')" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-[11px] font-bold transition-premium cursor-pointer shadow-md">
                  View Data
                </button>
              </td>
            </tr>
          `;
        });
      }
    }

    function viewStudentDiary(regNo, name) { openFullMentoringDiaryModal(regNo, name); }

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
  
