import os

with open('resources/views/tutor_student_diary_full.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_html = """                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Date</label>
                  <input type="text" id="leaveDate" placeholder="e.g. 10/10/24 - 15/10/24" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
              </div>
              <div>
                <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Reason</label>"""

new_html = """                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">From Date</label>
                  <input type="date" id="leaveDateFrom" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">To Date (Optional)</label>
                  <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">No. of Days</label>
                  <input type="number" step="0.5" id="leaveDays" placeholder="e.g. 1, 0.5" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
              </div>
              <div>
                <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Reason</label>"""

content = content.replace(old_html, new_html)

with open('resources/views/tutor_student_diary_full.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Tutor full view updated")
