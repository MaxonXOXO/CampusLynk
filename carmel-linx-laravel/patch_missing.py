import os

with open('resources/views/student_mentoring_diary_full.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_html = """                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">To Date</label>
                    <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                  </div>
              </div>"""

new_html = """                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">To Date (Optional)</label>
                    <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                  </div>
                  <div>
                    <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">No. of Days</label>
                    <input type="number" step="0.5" id="leaveDays" placeholder="e.g. 1, 0.5" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                  </div>
              </div>"""

content = content.replace(old_html, new_html)

with open('resources/views/student_mentoring_diary_full.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed missing leaveDays in student view")
