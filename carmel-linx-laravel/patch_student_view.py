import re

with open('resources/views/student_mentoring_diary_full.blade.php', 'r', encoding='utf-8') as f:
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

# Remove the Status dropdown from student modal completely
# and add the Parent Informed checkbox instead
old_status_div = '''<div>
                <label class="block font-bold text-slate-400 mb-1 text-[10px] text-xs">Status</label>
                <select id="leaveStatus" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-white focus:border-indigo-500 text-sm">
                  <option value="Pending">Pending</option>
                  <option value="Approved">Approved</option>
                  <option value="Rejected">Rejected</option>
                </select>
              </div>'''
              
new_parent_div = '''<div>
                <label class="flex items-center space-x-2 text-slate-400 font-bold text-sm cursor-pointer mt-4">
                  <input type="checkbox" id="leaveParent" class="rounded bg-slate-950 border-slate-800 text-indigo-500">
                  <span>Parent / Guardian Informed</span>
                </label>
                <input type="hidden" id="leaveStatus" value="Pending">
              </div>'''

content = content.replace(old_status_div, new_parent_div)

with open('resources/views/student_mentoring_diary_full.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Done modifying student view!')
