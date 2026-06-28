import os

# 1. Update MentoringController.php
with open('app/Http/Controllers/MentoringController.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_val = """        $request->validate([
            'semester'   => 'required|integer',
            'leave_date' => 'required|string',
            'reason'     => 'required|string'
        ]);

        $data = [
            'reg_no'          => strtoupper($regNo),
            'semester'        => $request->semester,
            'leave_date'      => $request->leave_date,
            'reason'          => $request->reason,"""

new_val = """        $request->validate([
            'semester'   => 'required|integer',
            'leave_date' => 'required|string',
            'no_of_days' => 'required|string',
            'reason'     => 'required|string'
        ]);

        $data = [
            'reg_no'          => strtoupper($regNo),
            'semester'        => $request->semester,
            'leave_date'      => $request->leave_date,
            'no_of_days'      => $request->no_of_days,
            'reason'          => $request->reason,"""

content = content.replace(old_val, new_val)
with open('app/Http/Controllers/MentoringController.php', 'w', encoding='utf-8') as f:
    f.write(content)

# 2. Update student_mentoring_diary_full.blade.php
with open('resources/views/student_mentoring_diary_full.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_html = """                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">To Date</label>
                  <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
              </div>
              <div>
                <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Reason</label>"""

new_html = """                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">To Date</label>
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
with open('resources/views/student_mentoring_diary_full.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)


# 3. Update mentoring_diary_modal.blade.php
with open('resources/views/mentoring_diary_modal.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_modal = """                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">To Date (Optional)</label>
                  <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
            </div>
            <div>
              <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Reason</label>"""

new_modal = """                <div>
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

content = content.replace(old_modal, new_modal)

old_edit_tutor = """    if(document.getElementById("leaveDateFrom")) {
        let dates = (lv.leave_date || "").split(" to ");"""
new_edit_tutor = """    if(document.getElementById("leaveDays")) document.getElementById("leaveDays").value = lv.no_of_days || "";
    if(document.getElementById("leaveDateFrom")) {
        let dates = (lv.leave_date || "").split(" to ");"""

content = content.replace(old_edit_tutor, new_edit_tutor)

old_save_tutor = """      leave_date: (() => {
        let from = document.getElementById("leaveDateFrom").value;
        let to = document.getElementById("leaveDateTo") ? document.getElementById("leaveDateTo").value : "";
        return to ? from + " to " + to : from;
      })(),
      reason: document.getElementById("leaveReason").value,"""
new_save_tutor = """      leave_date: (() => {
        let from = document.getElementById("leaveDateFrom").value;
        let to = document.getElementById("leaveDateTo") ? document.getElementById("leaveDateTo").value : "";
        return to ? from + " to " + to : from;
      })(),
      no_of_days: document.getElementById("leaveDays").value,
      reason: document.getElementById("leaveReason").value,"""

content = content.replace(old_save_tutor, new_save_tutor)
with open('resources/views/mentoring_diary_modal.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)


# 4. Update student_mentoring_scripts.blade.php
with open('resources/views/student_mentoring_scripts.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_row = """<td class="p-3">${lv.leave_date || '-'}</td>"""
new_row = """<td class="p-3">${lv.leave_date || '-'} ${lv.no_of_days ? '(<b class="text-slate-300">' + lv.no_of_days + ' Days</b>)' : ''}</td>"""

content = content.replace(old_row, new_row)

old_js_save = """        leave_date: (() => {
          let from = document.getElementById("leaveDateFrom").value;
          let to = document.getElementById("leaveDateTo") ? document.getElementById("leaveDateTo").value : "";
          return to ? from + " to " + to : from;
        })(),
        reason: document.getElementById("leaveReason").value,"""
new_js_save = """        leave_date: (() => {
          let from = document.getElementById("leaveDateFrom").value;
          let to = document.getElementById("leaveDateTo") ? document.getElementById("leaveDateTo").value : "";
          return to ? from + " to " + to : from;
        })(),
        no_of_days: document.getElementById("leaveDays").value,
        reason: document.getElementById("leaveReason").value,"""

content = content.replace(old_js_save, new_js_save)

old_js_edit = """      if(document.getElementById("leaveDateFrom")) {
          let dates = (lv.leave_date || "").split(" to ");"""
new_js_edit = """      if(document.getElementById("leaveDays")) document.getElementById("leaveDays").value = lv.no_of_days || "";
      if(document.getElementById("leaveDateFrom")) {
          let dates = (lv.leave_date || "").split(" to ");"""

content = content.replace(old_js_edit, new_js_edit)

with open('resources/views/student_mentoring_scripts.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("All updates successful")
