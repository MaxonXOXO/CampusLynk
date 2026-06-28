import os

path = "resources/views/student_mentoring_scripts.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Find where the diary data is processed after fetch
# We need to hook into after data is loaded to populate smdAcademicReport
# Find where window.smdAcademicsList is set
target = "window.smdAcademicsList = data.academics || {};"

new_code = r"""window.smdAcademicsList = data.academics || {};

      // Populate Internal Academic Progress tab in mentoring diary
      renderDiaryAcademicProgress(data.academics || {});"""

content = content.replace(target, new_code)

# Now add the renderDiaryAcademicProgress function before loadStudentMentoringDiary
new_func = r"""
  function renderDiaryAcademicProgress(academics) {
    const container = document.getElementById('smdAcademicReport');
    if (!container) return;

    if (!academics || Object.keys(academics).length === 0) {
      container.innerHTML = '<p class="text-slate-500 text-xs text-center py-8">No internal marks available yet. Marks will appear once entered by your faculty.</p>';
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

    let html = '';
    const semesters = Object.keys(academics).sort((a,b) => a - b);

    semesters.forEach(sem => {
      const subjects = academics[sem];
      if (!subjects || subjects.length === 0) return;

      let rows = '';
      subjects.forEach(sub => {
        const tm = calcTotal(sub);
        const hasMarks = tm > 0;
        const tmDisplay = hasMarks ? tm.toFixed(1) : '-';
        const { grade, cls: gradeCls } = hasMarks ? getGrade(tm) : { grade: '-', cls: 'text-slate-500' };
        const attPct = sub.attendance_percentage !== undefined ? sub.attendance_percentage : '-';
        const attColor = (attPct !== '-' && attPct < 75) ? 'text-rose-400 font-black' : 'text-emerald-400';

        rows += `<tr class="border-b border-slate-800/50 hover:bg-slate-900/30 transition-premium">
          <td class="p-2 whitespace-nowrap">
            <div class="font-black text-slate-200 text-xs">${sub.subject_code}</div>
            <div class="text-[10px] text-slate-500 mt-0.5">${sub.subject_name}</div>
          </td>
          <td class="p-2 text-center"><span class="text-sm font-mono font-black text-amber-300">${tmDisplay}</span></td>
          <td class="p-2 text-center border-l border-slate-800"><span class="text-sm font-black ${gradeCls}">${grade}</span></td>
          <td class="p-2 text-center border-l border-slate-800"><span class="text-xs font-black ${attColor}">${attPct !== '-' ? attPct + '%' : '-'}</span></td>
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

    container.innerHTML = html + '<p class="text-[10px] text-slate-600 mt-1 px-1">* Internal marks based on assignments, written tests and online tests. Board exam marks are entered separately under Board Exams tab.</p>';
  }

"""

# Insert before loadStudentMentoringDiary
insert_point = "function loadStudentMentoringDiary() {"
content = content.replace(insert_point, new_func + insert_point)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Added renderDiaryAcademicProgress to student_mentoring_scripts.blade.php")
