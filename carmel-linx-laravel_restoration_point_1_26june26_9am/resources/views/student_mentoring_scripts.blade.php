<script>
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
          <span class="text-[10px] text-slate-300">${seg}</span>
          <span class="text-xs font-bold text-emerald-400">${pts}</span>
        </div>`;
    }
  } else {
    splitHtml = '<div class="text-[9px] text-slate-500 py-1">No verified points yet.</div>';
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
            <span class="text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded font-bold">${m.date}</span>
            <span class="text-[10px] text-blue-400 font-bold uppercase tracking-wider">${m.category}</span>
          </div>
          <p class="text-xs text-slate-300">${m.discussion_notes}</p>
        </div>
      `;
    });
  } else {
    mList.innerHTML = '<p class="text-xs text-slate-500">No meeting logs found.</p>';
  }
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
    <td class="p-2 text-right"><button onclick="this.closest('tr').remove()" class="text-red-400 hover:text-red-300 text-lg cursor-pointer">&times;</button></td>
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
    <td class="p-2 text-right"><button onclick="this.closest('tr').remove()" class="text-red-400 hover:text-red-300 text-lg cursor-pointer">&times;</button></td>
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
    <td class="p-2 text-right"><button onclick="this.closest('tr').remove()" class="text-red-400 hover:text-red-300 text-lg cursor-pointer">&times;</button></td>
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
    <td class="p-2 text-right"><button onclick="this.closest('tr').remove()" class="text-red-400 hover:text-red-300 text-lg cursor-pointer">&times;</button></td>
  `;
  document.getElementById('smdBoardList').appendChild(tr);
}

function saveStudentMentoringData() {
      const boardGrades = [];
    document.querySelectorAll('.board-grade-select').forEach(sel => {
      if (sel.value) {
        boardGrades.push({
          semester: sel.getAttribute('data-sem'),
          subject_code: sel.getAttribute('data-code'),
          grade: sel.value
        });
      }
    });

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
    })).filter(b => b.semester !== '')
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
</script>
