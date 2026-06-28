import os

path = "resources/views/student_mentoring_scripts.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

old_func_start = "  function renderDiaryAcademicProgress(academics) {"
old_func_end   = "  function loadStudentMentoringDiary() {"

start_idx = content.find(old_func_start)
end_idx   = content.find(old_func_end)

new_func = r"""  function renderDiaryAcademicProgress(academics) {
    const container = document.getElementById('smdAcademicReport');
    if (!container) return;

    if (!academics || Object.keys(academics).length === 0) {
      container.innerHTML = '<p class="text-slate-500 text-xs text-center py-8">No internal marks available yet.</p>';
      return;
    }

    function parseVal(v) {
      if (v === null || v === undefined || v === '--') return 0;
      const n = parseFloat(v);
      return isNaN(n) ? 0 : n;
    }
    function calcTotal(sub) {
      // sub has: tests: {CO1,CO2,CO3,CO4}, assignments: {CO1,...}, mcq: {CO1,...}
      let t = 0;
      ['CO1','CO2','CO3','CO4'].forEach(co => {
        t += parseVal(sub.tests && sub.tests[co]);
        t += parseVal(sub.assignments && sub.assignments[co]);
        t += parseVal(sub.mcq && sub.mcq[co]);
      });
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

    let html = '';
    const semesters = Object.keys(academics).sort((a,b) => parseInt(a) - parseInt(b));

    semesters.forEach(sem => {
      const subjects = academics[sem];
      if (!subjects || subjects.length === 0) return;

      let hasAnyData = false;
      let rows = '';
      subjects.forEach(sub => {
        const tm = calcTotal(sub);
        const hasMarks = tm > 0;
        if (hasMarks) hasAnyData = true;
        const tmDisplay = hasMarks ? tm.toFixed(1) : '-';
        const { grade, cls: gradeCls } = hasMarks ? getGrade(tm) : { grade: '-', cls: 'text-slate-500' };
        const att = sub.attendance !== undefined && sub.attendance !== '--' ? sub.attendance : '-';
        const attColor = (att !== '-' && parseFloat(att) < 75) ? 'text-rose-400 font-black' : 'text-emerald-400';
        const attDisplay = att !== '-' ? att + '%' : '-';

        rows += `<tr class="border-b border-slate-800/50 hover:bg-slate-900/30 transition-premium">
          <td class="p-2 whitespace-nowrap">
            <div class="font-black text-slate-200 text-xs">${sub.subject_code}</div>
            <div class="text-[10px] text-slate-500 mt-0.5">${sub.subject_name}</div>
          </td>
          <td class="p-2 text-center"><span class="text-sm font-mono font-black text-amber-300">${tmDisplay}</span></td>
          <td class="p-2 text-center border-l border-slate-800"><span class="text-sm font-black ${gradeCls}">${grade}</span></td>
          <td class="p-2 text-center border-l border-slate-800"><span class="text-xs font-black ${attColor}">${attDisplay}</span></td>
        </tr>`;
      });

      html += `
        <div class="mb-5">
          <h5 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Semester ${sem}</h5>
          <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/80 border-b border-slate-800 text-[10px] uppercase tracking-wider font-black text-slate-500">
                  <th class="p-2">Subject</th>
                  <th class="p-2 text-center text-amber-500">Int. Marks</th>
                  <th class="p-2 text-center border-l border-slate-800">Grade</th>
                  <th class="p-2 text-center border-l border-slate-800">Attend.</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-800/30">${rows}</tbody>
            </table>
          </div>
        </div>`;
    });

    container.innerHTML = html + '<p class="text-[10px] text-slate-600 mt-1 px-1">* Internal marks based on assignments, written tests and online tests. Board exam marks are entered under the Board Exams tab.</p>';
  }

"""

if start_idx != -1 and end_idx != -1:
    content = content[:start_idx] + new_func + content[end_idx:]
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print("Updated renderDiaryAcademicProgress to handle mentoring diary academics structure")
else:
    print("ERROR: Could not find markers")
    print("start found:", start_idx != -1)
    print("end found:", end_idx != -1)
