import os
import re

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Find renderGodTable start
start_marker = "    function renderGodTable(semId) {"
end_after = "    function updateSbteRegNo() {"

start_idx = content.find(start_marker)
end_idx   = content.find(end_after)

if start_idx == -1 or end_idx == -1:
    print("Could not find markers!")
else:
    new_func = r"""    function renderGodTable(semId) {
      currentActiveSem = semId;
      document.querySelectorAll('.sem-tab').forEach(btn => {
        btn.className = 'sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border bg-transparent text-slate-500 hover:text-slate-300 hover:bg-slate-800 border-transparent';
      });
      const actBtn = document.getElementById(`btnSemTab_${semId}`);
      if(actBtn) actBtn.className = 'sem-tab px-4 py-2 rounded-lg text-xs font-black transition-premium border bg-blue-600/20 text-blue-400 border-blue-500/20';

      const container = document.getElementById('academicReportContent');
      const semData = academicData.semesters.find(s => s.semester == semId);
      if (!semData || !semData.subjects || semData.subjects.length === 0) {
        container.innerHTML = `<div class="py-12 text-center text-slate-500 font-bold text-xs border border-slate-800/50 rounded-2xl bg-slate-900/30">No academic data available for Semester ${semId}.</div>`;
        return;
      }

      function calcTotal(sub) {
        let t = 0;
        ['CO1','CO2','CO3','CO4'].forEach(co => { if (sub[co] !== null && sub[co] !== undefined) t += parseFloat(sub[co]) || 0; });
        return t;
      }
      function getGrade(t) {
        if (t >= 90) return { grade: 'O',  cls: 'text-emerald-400' };
        if (t >= 80) return { grade: 'A+', cls: 'text-teal-400' };
        if (t >= 70) return { grade: 'A',  cls: 'text-blue-400' };
        if (t >= 60) return { grade: 'B+', cls: 'text-sky-400' };
        if (t >= 50) return { grade: 'B',  cls: 'text-amber-400' };
        if (t >= 40) return { grade: 'C',  cls: 'text-orange-400' };
        return { grade: 'F', cls: 'text-rose-500' };
      }

      let rows = '';
      semData.subjects.forEach(sub => {
        const tm = calcTotal(sub);
        const hasMarks = tm > 0;
        const tmDisplay = hasMarks ? tm.toFixed(1) : '-';
        const { grade, cls: gradeCls } = hasMarks ? getGrade(tm) : { grade: '-', cls: 'text-slate-500' };
        const attColor = sub.attendance_percentage < 75 ? 'text-rose-400 font-black' : 'text-emerald-400';
        rows += `<tr class="border-b border-slate-800/50 hover:bg-slate-900/30 transition-premium">
            <td class="p-3 whitespace-nowrap">
              <div class="font-black text-slate-200 text-xs">${sub.subject_code}</div>
              <div class="text-[10px] text-slate-500 font-bold mt-0.5">${sub.subject_name}</div>
            </td>
            <td class="p-3 text-center">
              <span class="text-sm font-mono font-black text-amber-300">${tmDisplay}</span>
            </td>
            <td class="p-3 text-center border-l border-slate-800">
              <span class="text-base font-black ${gradeCls}">${grade}</span>
            </td>
            <td class="p-3 text-center border-l border-slate-800">
              <span class="text-sm font-black ${attColor}">${sub.attendance_percentage}%</span>
            </td>
          </tr>`;
      });

      const sgpa   = semData.sgpa   || '-';
      const cgpa   = semData.cgpa   || '-';
      const points = semData.activity_points || '-';
      const hasSummary = semData.sgpa !== null && semData.sgpa !== undefined;
      const summaryNote = hasSummary ? '' : `<p class="text-[10px] text-slate-600 mt-1 px-1">SGPA &amp; CGPA will appear once board exam marks are entered in your Mentoring Diary.</p>`;

      container.innerHTML = `
        <div class="grid grid-cols-3 gap-4 mb-5">
          <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 flex flex-col items-center justify-center gap-1 shadow-inner">
            <span class="material-symbols-rounded text-amber-400 text-lg">stars</span>
            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">SGPA</span>
            <span class="text-2xl font-black text-amber-300">${sgpa}</span>
          </div>
          <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 flex flex-col items-center justify-center gap-1 shadow-inner">
            <span class="material-symbols-rounded text-blue-400 text-lg">trending_up</span>
            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">CGPA (Cumulative)</span>
            <span class="text-2xl font-black text-blue-300">${cgpa}</span>
          </div>
          <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-4 flex flex-col items-center justify-center gap-1 shadow-inner">
            <span class="material-symbols-rounded text-purple-400 text-lg">local_activity</span>
            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Activity Points</span>
            <span class="text-2xl font-black text-purple-300">${points}</span>
          </div>
        </div>
        ${summaryNote}
        <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl overflow-x-auto shadow-2xl">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-900/80 border-b border-slate-800 text-xs uppercase tracking-wider font-black text-slate-400">
                <th class="p-3">Subject</th>
                <th class="p-3 text-center text-amber-400">Internal Marks</th>
                <th class="p-3 text-center border-l border-slate-800">Grade</th>
                <th class="p-3 text-center border-l border-slate-800">Attendance</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/30">${rows}</tbody>
          </table>
        </div>
        <p class="text-[10px] text-slate-600 mt-2 px-1">* Internal marks based on assignments, written tests and online tests entered by faculty. Board exam marks not included.</p>
      `;
    }

"""
    content = content[:start_idx] + new_func + content[end_idx:]
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print("renderGodTable fully replaced!")
