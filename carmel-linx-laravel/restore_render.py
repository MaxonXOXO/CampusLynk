import os

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Find and replace renderGodTable with the restored original detailed table
start_marker = "    function renderGodTable(semId) {"
end_marker   = "    function updateSbteRegNo() {"

start_idx = content.find(start_marker)
end_idx   = content.find(end_marker)

if start_idx == -1 or end_idx == -1:
    print("Could not find markers!")
else:
    original_func = r"""    function renderGodTable(semId) {
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

      let rows = '';
      semData.subjects.forEach(sub => {
        const trClass = "border-b border-slate-800/50 hover:bg-slate-900/30 transition-premium";
        rows += `
          <tr class="${trClass}">
            <td class="p-4 whitespace-nowrap">
              <div class="font-black text-slate-200 text-xs">${sub.subject_code}</div>
              <div class="text-xs text-slate-500 font-bold truncate max-w-[150px]" title="${sub.subject_name}">${sub.subject_name}</div>
            </td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-300">${sub.CO1 !== null ? sub.CO1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-300 bg-slate-950/20">${sub.CO2 !== null ? sub.CO2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-300">${sub.CO3 !== null ? sub.CO3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-slate-300 bg-slate-950/20">${sub.CO4 !== null ? sub.CO4 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400 border-l border-slate-800">${sub.Assg1 !== null ? sub.Assg1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400">${sub.Assg2 !== null ? sub.Assg2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400">${sub.Assg3 !== null ? sub.Assg3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-bold text-blue-400">${sub.Assg4 !== null ? sub.Assg4 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400 border-l border-slate-800">${sub.WT1 !== null ? sub.WT1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400">${sub.WT2 !== null ? sub.WT2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400">${sub.WT3 !== null ? sub.WT3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-emerald-400">${sub.WT4 !== null ? sub.WT4 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400 border-l border-slate-800">${sub.OT1 !== null ? sub.OT1 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400">${sub.OT2 !== null ? sub.OT2 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400">${sub.OT3 !== null ? sub.OT3 : '-'}</td>
            <td class="p-4 text-center text-xs font-mono font-black text-purple-400">${sub.OT4 !== null ? sub.OT4 : '-'}</td>
            <td class="p-4 text-center text-xs font-black border-l border-slate-800 ${sub.attendance_percentage < 75 ? 'text-rose-400' : 'text-slate-300'}">
              ${sub.attendance_percentage}%
            </td>
          </tr>
        `;
      });

      container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
          <div class="flex gap-4">
            <div class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 flex items-center gap-2 shadow-inner">
              <span class="material-symbols-rounded text-slate-400 text-xs">stars</span>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">SGPA:</span>
              <span class="text-xs font-black text-white">${semData.sgpa || '-'}</span>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 flex items-center gap-2 shadow-inner">
              <span class="material-symbols-rounded text-slate-400 text-xs">local_activity</span>
              <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Points:</span>
              <span class="text-xs font-black text-white">${semData.activity_points || '-'}</span>
            </div>
          </div>
        </div>

        <div class="bg-slate-950/40 border border-slate-800/60 rounded-2xl overflow-x-auto shadow-2xl">
          <table class="w-full text-left border-collapse min-w-[1200px]">
            <thead>
              <tr class="bg-slate-900/80 border-b border-slate-800 text-xs uppercase tracking-wider font-black text-slate-400">
                <th class="p-4 font-black">Subject</th>
                <th class="p-4 text-center" colspan="4">Sum COs</th>
                <th class="p-4 text-center border-l border-slate-800 text-blue-400" colspan="4">Assignments</th>
                <th class="p-4 text-center border-l border-slate-800 text-emerald-400" colspan="4">Written Tests</th>
                <th class="p-4 text-center border-l border-slate-800 text-purple-400" colspan="4">Online Tests</th>
                <th class="p-4 text-center border-l border-slate-800">Attend.</th>
              </tr>
              <tr class="bg-slate-900/40 border-b border-slate-800/50 text-xs uppercase font-bold text-slate-500">
                <th class="p-2"></th>
                <th class="p-2 text-center w-10 border-l border-slate-800/50">C1</th><th class="p-2 text-center w-10 bg-slate-950/20">C2</th><th class="p-2 text-center w-10">C3</th><th class="p-2 text-center w-10 bg-slate-950/20">C4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">A1</th><th class="p-2 text-center w-10">A2</th><th class="p-2 text-center w-10">A3</th><th class="p-2 text-center w-10">A4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">W1</th><th class="p-2 text-center w-10">W2</th><th class="p-2 text-center w-10">W3</th><th class="p-2 text-center w-10">W4</th>
                <th class="p-2 text-center w-10 border-l border-slate-800">O1</th><th class="p-2 text-center w-10">O2</th><th class="p-2 text-center w-10">O3</th><th class="p-2 text-center w-10">O4</th>
                <th class="p-2 text-center w-16 border-l border-slate-800">%</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/30">
              ${rows}
            </tbody>
          </table>
        </div>
      `;
    }

"""
    content = content[:start_idx] + original_func + content[end_idx:]
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print("Restored original renderGodTable in Academic Stats page!")
