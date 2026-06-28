
function switchStudentMentoringTab(tabId) {
  document.querySelectorAll('.smd-content-pane').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('.smd-tab').forEach(el => {
    el.classList.remove('bg-slate-800/80', 'text-blue-400');
    el.classList.add('text-slate-400');
  });

  document.getElementById(tabId).classList.remove('hidden');
  const btn = document.getElementById('tabBtn_' + tabId);
  btn.classList.remove('text-slate-400');
  btn.classList.add('bg-slate-800/80', 'text-blue-400');
}

function loadStudentMentoringDiary() {
  fetch('/api/student/mentoring/diary', {
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'SUCCESS') {
      populateMentoringUI(data.data);
    }
  })
  .catch(err => console.error(err));
}

function populateMentoringUI(data) {
  // Populate Personal Info
  if (data.profile) {
    document.getElementById('smd_annual_income').value = data.profile.annual_income || '';
    document.getElementById('smd_residential_status').value = data.profile.residential_status || 'Day Scholar';
    document.getElementById('smd_scholarships').value = data.profile.scholarships || '';
    document.getElementById('smd_fee_waiver').checked = data.profile.is_fee_waiver == 1;
    document.getElementById('smd_guardian_name').value = data.profile.guardian_name || '';
    document.getElementById('smd_guardian_relationship').value = data.profile.guardian_relationship || '';
    document.getElementById('smd_guardian_mobile').value = data.profile.guardian_mobile || '';
    document.getElementById('smd_guardian_address').value = data.profile.guardian_address || '';
  }

    // Store syllabus list globally for dropdowns
    window.smdSyllabusList = data.syllabus_list || {};
    window.smdAcademicsList = data.academics || {};

    // Reset dropdown to default and render empty list or Sem 1
    const semSelect = document.getElementById('smdBoardSemSelect');
    if (semSelect) {
      semSelect.value = "";
      renderStudentBoardExams();
    }

    // Populate Board Exams (Summary Table)
    const bList = document.getElementById('smdBoardList');
    if (bList) {
      bList.innerHTML = '';
      if (data.board) {
        data.board.forEach(b => {
          addBoardRow(b.semester, b.sgpa, b.cgpa, b.activity_points, b.id);
        });
      }
    }

  // Populate Family
  const fList = document.getElementById('smdFamilyList');
  fList.innerHTML = '';
  if (data.family) {
    data.family.forEach(f => {
      addFamilyRow(f.name, f.relationship, f.education, f.occupation, f.contact_no, f.id);
    });
  }

  // Populate Education
  const eList = document.getElementById('smdEducationList');
  eList.innerHTML = '';
  if (data.education) {
    data.education.forEach(e => {
      addEducationRow(e.course, e.institution, e.year_of_completion, e.total_percentage, e.id);
    });
  }

  // Populate Extracurricular
  const exList = document.getElementById('smdExtraList');
  exList.innerHTML = '';
  let totalPts = 0;
  let splitPts = {};
  if (data.extracurricular && data.extracurricular.length > 0) {
    data.extracurricular.forEach(ex => {
      if (ex.status === 'Verified') {
        let pts = parseFloat(ex.points_awarded) || 0;
        totalPts += pts;
        let seg = ex.segment || 'General';
        splitPts[seg] = (splitPts[seg] || 0) + pts;
      }
      addExtraRow(ex);
    });
  } else {
    exList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-600">No extra-curricular records.</td></tr>';
  }
  
  // Update progress bar
  document.getElementById('studentTotalActivityPoints').innerText = totalPts;
  document.getElementById('studentActivityProgressBar').style.width = Math.min(100, totalPts) + '%';
  let splitHtml = '';
  if (Object.keys(splitPts).length > 0) {
    for (const [seg, pts] of Object.entries(splitPts)) {
      splitHtml += `
        <div class="flex justify-between items-center py-1">
          <span class="text-xs text-slate-300">${seg}</span>
          <span class="text-xs font-bold text-emerald-400">${pts}</span>
        </div>`;
    }
  } else {
    splitHtml = '<div class="text-xs text-slate-500 py-1">No verified points yet.</div>';
  }
  document.getElementById('studentActivitySplitList').innerHTML = splitHtml;

  // Populate Meetings
  const mList = document.getElementById('smdMeetingsList');
  mList.innerHTML = '';
  if (data.meetings && data.meetings.length > 0) {
    data.meetings.forEach(m => {
      mList.innerHTML += `
        <div class="bg-slate-900/50 p-4 rounded-xl border border-slate-800">
          <div class="flex justify-between items-start mb-2">
            <span class="text-xs bg-slate-800 text-slate-300 px-2 py-0.5 rounded font-bold">${m.date}</span>
            <span class="text-xs text-blue-400 font-bold uppercase tracking-wider">${m.category}</span>
          </div>
          <p class="text-xs text-slate-300">${m.discussion_notes}</p>
        </div>
      `;
    });
  } else {
    mList.innerHTML = '<p class="text-xs text-slate-500">No meeting logs found.</p>';
  }
}

function renderStudentBoardExams() {
  const semSelect = document.getElementById('smdBoardSemSelect');
  const sbList = document.getElementById('smdSubjectBoardList');
  if (!semSelect || !sbList) return;

  const sem = semSelect.value;
  sbList.innerHTML = '';

  if (!sem) {
    sbList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-500">Select a semester to view subjects.</td></tr>';
    return;
  }

  // Save current UI state back to window.smdAcademicsList before switching
  syncBoardExamsToGlobal();

  const academics = window.smdAcademicsList || {};
  const subjects = academics[sem];

  if (!subjects || subjects.length === 0) {
    sbList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-500">No subjects found for this semester.</td></tr>';
    return;
  }

  subjects.forEach(sub => {
    let br = sub.board_result || {};
    sbList.innerHTML += `
      <tr class="border-b border-slate-800/40 hover:bg-slate-900/50 board-grade-row transition-premium" data-sem="${sem}" data-code="${sub.subject_code}">
        <td class="p-3 text-slate-300 font-bold bg-slate-900/40 font-mono text-sm tracking-wider">${sub.subject_code}</td>
        <td class="p-3 text-slate-200 text-xs font-bold uppercase truncate max-w-[200px]" title="${sub.subject_name}">${sub.subject_name}</td>
        <td class="p-2"><input type="month" class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-xs text-white bg-exam-my focus:border-blue-500 outline-none transition-premium" value="${br.exam_month_year || ''}"></td>
        <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-xs text-white bg-grade uppercase font-bold text-center focus:border-blue-500 outline-none transition-premium" value="${br.grade || ''}" placeholder="e.g. A+"></td>
        <td class="p-2">
          <select class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-xs text-white bg-pass font-bold focus:border-blue-500 outline-none transition-premium">
            <option value="1" ${br.passed == 1 || br.passed === undefined ? 'selected' : ''} class="text-emerald-400">Yes</option>
            <option value="0" ${br.passed == 0 && br.passed !== undefined ? 'selected' : ''} class="text-red-400">No</option>
          </select>
        </td>
        <td class="p-2"><input type="number" class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-xs text-white font-bold bg-chances text-center focus:border-blue-500 outline-none transition-premium" value="${br.chances_taken || 1}" min="1"></td>
      </tr>
    `;
  });
}

function syncBoardExamsToGlobal() {
  const sbList = document.getElementById('smdSubjectBoardList');
  if (!sbList) return;
  const rows = sbList.querySelectorAll('.board-grade-row');
  if (rows.length === 0) return;

  const sem = rows[0].getAttribute('data-sem');
  if (!sem || !window.smdAcademicsList[sem]) return;

  rows.forEach(row => {
    let code = row.getAttribute('data-code');
    let sub = window.smdAcademicsList[sem].find(s => s.subject_code === code);
    if (sub) {
      sub.board_result = {
        exam_month_year: row.querySelector('.bg-exam-my').value,
        grade: row.querySelector('.bg-grade').value,
        passed: row.querySelector('.bg-pass').value,
        chances_taken: row.querySelector('.bg-chances').value || 1
      };
    }
  });
}

function addFamilyRow(name='', rel='', edu='', occ='', con='', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 family-row';
  tr.innerHTML = `
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-name" value="${name}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-rel" value="${rel}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-edu" value="${edu}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-occ" value="${occ}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-con" value="${con}"></td>
    <td class="p-2 text-right"><button onclick="this.closest('tr').remove()" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  document.getElementById('smdFamilyList').appendChild(tr);
}

function addEducationRow(course='', inst='', year='', marks='', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 edu-row';
  tr.innerHTML = `
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-course" value="${course}" placeholder="e.g. SSLC"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-inst" value="${inst}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-year" value="${year}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-marks" value="${marks}"></td>
    <td class="p-2 text-right"><button onclick="this.closest('tr').remove()" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  document.getElementById('smdEducationList').appendChild(tr);
}

function addExtraRow(sem='', act='', ach='', status='Pending', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 extra-row';
  const statusColor = status === 'Approved' ? 'text-green-400' : (status === 'Rejected' ? 'text-red-400' : 'text-amber-400');
  tr.innerHTML = `
    <td class="p-2"><input type="number" class="w-16 bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white ex-sem" value="${sem}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white ex-act" value="${act}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white ex-ach" value="${ach}"></td>
    <td class="p-2 font-bold ${statusColor}">${status}</td>
    <td class="p-2 text-right"><button onclick="this.closest('tr').remove()" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  document.getElementById('smdExtraList').appendChild(tr);
}

function addBoardRow(sem='', sgpa='', cgpa='', act='', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 board-row';
  tr.innerHTML = `
    <td class="p-2"><input type="number" class="w-16 bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-sem" value="${sem}"></td>
    <td class="p-2"><input type="number" step="0.01" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-sgpa" value="${sgpa}"></td>
    <td class="p-2"><input type="number" step="0.01" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-cgpa" value="${cgpa}"></td>
    <td class="p-2"><input type="number" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-act" value="${act}"></td>
    <td class="p-2 text-right"><button onclick="this.closest('tr').remove()" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  const list = document.getElementById('smdBoardList');
  if (list) list.appendChild(tr);
}

function saveStudentMentoringData() {
    // Make sure the currently visible semester is synced to the global list before saving
    syncBoardExamsToGlobal();

    const boardGrades = [];
    if (window.smdAcademicsList) {
      for (const sem in window.smdAcademicsList) {
        if (Array.isArray(window.smdAcademicsList[sem])) {
          window.smdAcademicsList[sem].forEach(sub => {
            if (sub.board_result && sub.board_result.grade) {
              boardGrades.push({
                semester: sem,
                subject_code: sub.subject_code,
                exam_month_year: sub.board_result.exam_month_year,
                grade: sub.board_result.grade,
                passed: sub.board_result.passed,
                chances_taken: sub.board_result.chances_taken || 1
              });
            }
          });
        }
      }
    }

    const payload = {
    profile: {
      annual_income: document.getElementById('smd_annual_income').value,
      residential_status: document.getElementById('smd_residential_status').value,
      scholarships: document.getElementById('smd_scholarships').value,
      is_fee_waiver: document.getElementById('smd_fee_waiver').checked ? 1 : 0,
      guardian_name: document.getElementById('smd_guardian_name').value,
      guardian_relationship: document.getElementById('smd_guardian_relationship').value,
      guardian_mobile: document.getElementById('smd_guardian_mobile').value,
      guardian_address: document.getElementById('smd_guardian_address').value
    },
    family: Array.from(document.querySelectorAll('.family-row')).map(row => ({
      name: row.querySelector('.f-name').value,
      relationship: row.querySelector('.f-rel').value,
      education: row.querySelector('.f-edu').value,
      occupation: row.querySelector('.f-occ').value,
      contact_no: row.querySelector('.f-con').value
    })).filter(f => f.name !== ''),
    education: Array.from(document.querySelectorAll('.edu-row')).map(row => ({
      course: row.querySelector('.e-course').value,
      institution: row.querySelector('.e-inst').value,
      year_of_completion: row.querySelector('.e-year').value,
      total_percentage: row.querySelector('.e-marks').value
    })).filter(e => e.course !== ''),
    // Extra-curricular handled via separate popup modal now
    extracurricular: [],
    board: Array.from(document.querySelectorAll('.board-row')).map(row => ({
      semester: row.querySelector('.b-sem').value,
      sgpa: row.querySelector('.b-sgpa').value,
      cgpa: row.querySelector('.b-cgpa').value,
      activity_points: row.querySelector('.b-act').value
    })).filter(b => b.semester !== ''),
    board_grades: boardGrades
  };

  fetch('/api/student/mentoring/save-all', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'SUCCESS') {
      showGlobalAlert('Mentoring diary updated successfully!', 'success');
    } else {
      showGlobalAlert('Failed to save data.', 'error');
    }
  })
  .catch(err => {
    console.error(err);
    showGlobalAlert('Error communicating with server.', 'error');
  });
}

function showGlobalAlert(msg, type) {
  // A simple alert function for the student dashboard
  alert(msg);
}

function downloadMentoringPdf() {
  window.open('/api/student/mentoring/download-pdf', '_blank');
}

function openStudentActivityModal() {
    document.getElementById("studentActivityId").value = "";
    document.getElementById("studentActivityForm").reset();
    document.getElementById("studentActivityModalTitle").innerText = "Add Activity";
    document.getElementById("addStudentActivityModal").classList.remove("hidden");
    document.getElementById("addStudentActivityModal").classList.add("flex");
}

function editStudentActivity(act) {
    document.getElementById("studentActivityId").value = act.activity_id || "";
    document.getElementById("studentActivitySemester").value = act.semester || "1";
    document.getElementById("studentActivitySegment").value = act.segment || "NCC";
    document.getElementById("studentActivityName").value = act.activity_name || "";
    document.getElementById("studentActivityLevel").value = act.level || "";
    document.getElementById("studentActivityPtsClaimed").value = act.points_claimed || 0;
    document.getElementById("studentActivityModalTitle").innerText = "Edit Activity";
    document.getElementById("addStudentActivityModal").classList.remove("hidden");
    document.getElementById("addStudentActivityModal").classList.add("flex");
}

function closeStudentActivityModal() {
    document.getElementById("addStudentActivityModal").classList.add("hidden");
    document.getElementById("addStudentActivityModal").classList.remove("flex");
}

function saveStudentActivity(e) {
    e.preventDefault();
    const payload = {
        activity_id: document.getElementById("studentActivityId").value,
        semester: document.getElementById("studentActivitySemester").value,
        segment: document.getElementById("studentActivitySegment").value,
        activity_name: document.getElementById("studentActivityName").value,
        level: document.getElementById("studentActivityLevel").value,
        points_claimed: document.getElementById("studentActivityPtsClaimed").value,
    };

    fetch("/api/student/mentoring/extra-curricular/save", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(resData => {
        if(resData.status === "SUCCESS") {
            closeStudentActivityModal();
            loadStudentMentoringDiary();
            showGlobalAlert("Activity saved successfully!", "success");
        } else {
            showGlobalAlert(resData.message || "Failed to save activity.", "error");
        }
    })
    .catch(err => {
        console.error(err);
        showGlobalAlert("Error saving activity.", "error");
    });
}


