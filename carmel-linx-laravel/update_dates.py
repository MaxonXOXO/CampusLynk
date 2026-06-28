import re
import sys

def modify_student_html():
    with open('resources/views/student_mentoring_diary_full.blade.php', 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Replace the single date input with from and to date pickers
    old_date = '''<div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Date</label>
                  <input type="text" id="leaveDate" placeholder="e.g. 10/10/24 - 15/10/24" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>'''
    
    new_dates = '''<div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">From Date</label>
                  <input type="date" id="leaveDateFrom" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">To Date</label>
                  <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>'''

    if old_date in content:
        content = content.replace(old_date, new_dates)
    else:
        # Fallback in case of subtle differences
        content = re.sub(r'<div>\s*<label[^>]*>Date</label>\s*<input[^>]*id="leaveDate"[^>]*>\s*</div>', new_dates, content)

    # 2. Completely remove Status dropdown from student and just put a hidden input
    # It might still be a select since my last patch failed.
    old_status = '''<div>
                <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Status</label>
                <select id="leaveStatus" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                  <option value="Pending">Pending</option>
                  <option value="Approved">Approved</option>
                  <option value="Rejected">Rejected</option>
                </select>
              </div>'''
    new_status = '''<input type="hidden" id="leaveStatus" value="Pending">'''
    
    if old_status in content:
        content = content.replace(old_status, new_status)
    else:
        content = re.sub(r'<div>\s*<label[^>]*>Status</label>\s*<select id="leaveStatus"[\s\S]*?</select>\s*</div>', new_status, content)

    with open('resources/views/student_mentoring_diary_full.blade.php', 'w', encoding='utf-8') as f:
        f.write(content)

def modify_mentor_html():
    with open('resources/views/mentoring_diary_modal.blade.php', 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Replace date with from and to (but keep Status)
    # The tutor modal has: <input type="text" id="leaveDate" placeholder="e.g. 10/10/24 - 15/10/24"...>
    old_date = '''<div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Date</label>
                  <input type="text" id="leaveDate" placeholder="e.g. 10/10/24 - 15/10/24" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>'''
    
    new_dates = '''<div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">From Date</label>
                  <input type="date" id="leaveDateFrom" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">To Date (Optional)</label>
                  <input type="date" id="leaveDateTo" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                </div>'''
    
    if old_date in content:
        content = content.replace(old_date, new_dates)
    else:
        content = re.sub(r'<div>\s*<label[^>]*>Date</label>\s*<input[^>]*id="leaveDate"[^>]*>\s*</div>', new_dates, content)
        
    with open('resources/views/mentoring_diary_modal.blade.php', 'w', encoding='utf-8') as f:
        f.write(content)

def modify_js():
    with open('resources/views/student_mentoring_scripts.blade.php', 'r', encoding='utf-8') as f:
        content = f.read()

    # Update saveLeave
    old_save = '''      semester: document.getElementById("leaveSem").value,
      leave_date: document.getElementById("leaveDate").value,
      reason: document.getElementById("leaveReason").value,'''
    
    new_save = '''      semester: document.getElementById("leaveSem").value,
      leave_date: (() => {
        let from = document.getElementById("leaveDateFrom").value;
        let to = document.getElementById("leaveDateTo") ? document.getElementById("leaveDateTo").value : "";
        return to ? from + " to " + to : from;
      })(),
      reason: document.getElementById("leaveReason").value,'''
    
    content = content.replace(old_save, new_save)

    # Update editLeave
    old_edit = '''    function editLeave(lv) {
      if(document.getElementById("leaveId")) document.getElementById("leaveId").value = lv.id || "";
      if(document.getElementById("leaveSem")) document.getElementById("leaveSem").value = lv.semester || "";
      if(document.getElementById("leaveDate")) document.getElementById("leaveDate").value = lv.leave_date || "";
      if(document.getElementById("leaveReason")) document.getElementById("leaveReason").value = lv.reason || "";'''
    
    new_edit = '''    function editLeave(lv) {
      if(document.getElementById("leaveId")) document.getElementById("leaveId").value = lv.id || "";
      if(document.getElementById("leaveSem")) document.getElementById("leaveSem").value = lv.semester || "";
      if(document.getElementById("leaveDateFrom")) {
          let dates = (lv.leave_date || "").split(" to ");
          document.getElementById("leaveDateFrom").value = dates[0] || "";
          if(document.getElementById("leaveDateTo")) {
              document.getElementById("leaveDateTo").value = dates[1] || "";
          }
      }
      if(document.getElementById("leaveReason")) document.getElementById("leaveReason").value = lv.reason || "";'''

    content = content.replace(old_edit, new_edit)
    
    with open('resources/views/student_mentoring_scripts.blade.php', 'w', encoding='utf-8') as f:
        f.write(content)

modify_student_html()
modify_mentor_html()
modify_js()
print("Updates applied")
