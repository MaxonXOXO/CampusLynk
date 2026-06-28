import os

with open('resources/views/tutor_student_diary_full.blade.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

new_lines = []
i = 0
while i < len(lines):
    line = lines[i]
    if '<label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Date</label>' in line:
        if '<input type="text" id="leaveDate"' in lines[i+1]:
            # Replace these two lines with the new fields
            new_lines.append('                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">From Date</label>\n')
            new_lines.append('                  <input type="date" id="leaveDateFrom" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">\n')
            new_lines.append('                </div>\n')
            new_lines.append('              </div>\n')
            new_lines.append('              <div class="grid grid-cols-2 gap-4">\n')
            new_lines.append('                <div>\n')
            new_lines.append('                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">To Date (Optional)</label>\n')
            new_lines.append('                  <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">\n')
            new_lines.append('                </div>\n')
            new_lines.append('                <div>\n')
            new_lines.append('                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">No. of Days</label>\n')
            new_lines.append('                  <input type="number" step="0.5" id="leaveDays" placeholder="e.g. 1, 0.5" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">\n')
            i += 2  # Skip the next line as well
            continue
    new_lines.append(line)
    i += 1

with open('resources/views/tutor_student_diary_full.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(new_lines)

print("Tutor full view fixed")
