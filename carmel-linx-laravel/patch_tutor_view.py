import re

with open('resources/views/tutor_student_diary_full.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update the table headers for Leave
content = content.replace(
    '''<th class="p-3">Semester</th>
                  <th class="p-3">Date</th>
                  <th class="p-3">Reason</th>
                  <th class="p-3">Status</th>
                  <th class="p-3 text-right">Actions</th>''',
    '''<th class="p-3">Semester</th>
                  <th class="p-3">Date Range</th>
                  <th class="p-3">Reason</th>
                  <th class="p-3">Parent Informed</th>
                  <th class="p-3">Status</th>
                  <th class="p-3 text-right">Actions</th>'''
)

# 2. Update Modal fields for Leave
old_modal_date = '''<input type="date" id="leaveDate" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">'''
new_modal_date = '''<input type="text" id="leaveDate" placeholder="e.g. 10/10/24 - 15/10/24" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">'''
content = content.replace(old_modal_date, new_modal_date)

# In tutor modal, KEEP the status dropdown, but add the Parent Informed checkbox before it
parent_informed_html = '''<div>
                <label class="flex items-center space-x-2 text-slate-400 font-bold text-sm cursor-pointer mb-2">
                  <input type="checkbox" id="leaveParent" class="rounded bg-slate-950 border-slate-800 text-indigo-500">
                  <span>Parent / Guardian Informed</span>
                </label>
              </div>'''

# Let's insert parent_informed_html before the reason field
old_reason_div = '''<div>
                <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Reason</label>'''
new_reason_div = parent_informed_html + "\n              " + old_reason_div

content = content.replace(old_reason_div, new_reason_div)

with open('resources/views/tutor_student_diary_full.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Done modifying tutor view!')
