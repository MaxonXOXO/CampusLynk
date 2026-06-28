<?php

$content = file_get_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\student_mentoring_scripts.blade.php");

$oldAddExtraRow = 'function addExtraRow(sem=\'\', act=\'\', ach=\'\', status=\'Pending\', id=\'\') {
  const tr = document.createElement(\'tr\');
  tr.className = \'border-b border-slate-800/40 extra-row\';
  tr.innerHTML = `
    <td class="p-2"><input type="number" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white ex-sem" value="${sem}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white ex-act" value="${act}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white ex-ach" value="${ach}"></td>
    <td class="p-2 text-slate-400 text-xs">${status}</td>
    <td class="p-2 text-right"><button onclick="this.closest(\'tr\').remove()" class="text-red-400 hover:text-red-300 text-lg cursor-pointer">&times;</button></td>
  `;
  document.getElementById(\'smdExtraList\').appendChild(tr);
}';

$newAddExtraRow = 'function addExtraRow(ex) {
  const tr = document.createElement(\'tr\');
  tr.className = \'border-b border-slate-800/40 extra-row\';
  const statusBadge = ex.status === \'Verified\' ? \'bg-green-900/30 text-green-400\' : \'bg-amber-900/30 text-amber-400\';
  tr.innerHTML = `
    <td class="p-3 font-bold text-slate-300">${ex.semester || 1}</td>
    <td class="p-3">
      <div class="font-bold text-slate-200">${ex.activity_name || \'N/A\'}</div>
      <div class="text-[10px] text-slate-500">${ex.segment || \'General\'} | Level: ${ex.level || \'Participation\'}</div>
    </td>
    <td class="p-3 text-center">
      <span class="text-[10px] text-slate-400">Claimed: </span><span class="font-bold text-white">${ex.points_claimed || 0}</span><br>
      <span class="text-[10px] text-slate-400">Awarded: </span><span class="font-bold text-amber-400">${ex.points_awarded || 0}</span>
    </td>
    <td class="p-3">
      <span class="px-2 py-0.5 rounded text-[10px] font-bold ${statusBadge}">${ex.status || \'Pending\'}</span>
    </td>
    <td class="p-3 text-right">
      <button onclick=\'editStudentActivity(${JSON.stringify(ex).replace(/\'/g, "&apos;")})\' class="text-indigo-400 hover:text-white transition-premium cursor-pointer"><span class="material-symbols-rounded text-sm">edit</span></button>
    </td>
  `;
  document.getElementById(\'smdExtraList\').appendChild(tr);
}';

$content = str_replace($oldAddExtraRow, $newAddExtraRow, $content);

// Update populateMentoringUI for Extra Curricular and the progress bar
$oldPopulate = '  // Populate Extracurricular
  const exList = document.getElementById(\'smdExtraList\');
  exList.innerHTML = \'\';
  if (data.extracurricular) {
    data.extracurricular.forEach(ex => {
      addExtraRow(ex.semester, ex.activity_name, ex.achievement, ex.status, ex.id);
    });
  }';

$newPopulate = '  // Populate Extracurricular
  const exList = document.getElementById(\'smdExtraList\');
  exList.innerHTML = \'\';
  let totalPts = 0;
  let splitPts = {};
  if (data.extracurricular && data.extracurricular.length > 0) {
    data.extracurricular.forEach(ex => {
      if (ex.status === \'Verified\') {
        let pts = parseFloat(ex.points_awarded) || 0;
        totalPts += pts;
        let seg = ex.segment || \'General\';
        splitPts[seg] = (splitPts[seg] || 0) + pts;
      }
      addExtraRow(ex);
    });
  } else {
    exList.innerHTML = \'<tr><td colspan="6" class="p-6 text-center text-slate-600">No extra-curricular records.</td></tr>\';
  }
  
  // Update progress bar
  document.getElementById(\'studentTotalActivityPoints\').innerText = totalPts;
  document.getElementById(\'studentActivityProgressBar\').style.width = Math.min(100, totalPts) + \'%\';
  let splitHtml = \'\';
  if (Object.keys(splitPts).length > 0) {
    for (const [seg, pts] of Object.entries(splitPts)) {
      splitHtml += `
        <div class="flex justify-between items-center py-1">
          <span class="text-[10px] text-slate-300">${seg}</span>
          <span class="text-xs font-bold text-emerald-400">${pts}</span>
        </div>`;
    }
  } else {
    splitHtml = \'<div class="text-[9px] text-slate-500 py-1">No verified points yet.</div>\';
  }
  document.getElementById(\'studentActivitySplitList\').innerHTML = splitHtml;';

$content = str_replace($oldPopulate, $newPopulate, $content);

$modalScripts = '

function openStudentActivityModal() {
    document.getElementById("studentActivityId").value = "";
    document.getElementById("studentActivityForm").reset();
    document.getElementById("studentActivityModalTitle").innerText = "Add Activity";
    document.getElementById("addStudentActivityModal").classList.remove("hidden");
    document.getElementById("addStudentActivityModal").classList.add("flex");
}

function editStudentActivity(act) {
    document.getElementById("studentActivityId").value = act.activity_id || "";
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
            "X-CSRF-TOKEN": document.querySelector(\'meta[name="csrf-token"]\').content
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
';

$content .= $modalScripts;

file_put_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\student_mentoring_scripts.blade.php", $content);
echo "Updated student_mentoring_scripts.blade.php\n";
