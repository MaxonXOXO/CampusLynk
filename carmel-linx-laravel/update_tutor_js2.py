import os
import re

with open('resources/views/mentoring_diary_modal.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Update editLeave
new_edit = '''    document.getElementById("leaveId").value = lv.id || "";
    document.getElementById("leaveSem").value = lv.semester || 1;
    if(document.getElementById("leaveDateFrom")) {
        let dates = (lv.leave_date || "").split(" to ");
        document.getElementById("leaveDateFrom").value = dates[0] || "";
        if(document.getElementById("leaveDateTo")) {
            document.getElementById("leaveDateTo").value = dates[1] || "";
        }
    }'''

content = re.sub(r'    document.getElementById\("leaveId"\).value = lv.id \|\| "";\s*document.getElementById\("leaveSem"\).value = lv.semester \|\| 1;\s*document.getElementById\("leaveDate"\).value = lv.leave_date \|\| "";', new_edit, content)

with open('resources/views/mentoring_diary_modal.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Tutor JS editLeave updated")
