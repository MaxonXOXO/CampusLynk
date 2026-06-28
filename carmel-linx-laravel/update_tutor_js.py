import os

with open('resources/views/mentoring_diary_modal.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Update editLeave
old_edit = '''    document.getElementById("leaveId").value = lv.id || "";
    document.getElementById("leaveSem").value = lv.semester || "";
    document.getElementById("leaveDate").value = lv.leave_date || "";'''

new_edit = '''    document.getElementById("leaveId").value = lv.id || "";
    document.getElementById("leaveSem").value = lv.semester || "";
    if(document.getElementById("leaveDateFrom")) {
        let dates = (lv.leave_date || "").split(" to ");
        document.getElementById("leaveDateFrom").value = dates[0] || "";
        if(document.getElementById("leaveDateTo")) {
            document.getElementById("leaveDateTo").value = dates[1] || "";
        }
    }'''

content = content.replace(old_edit, new_edit)

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

with open('resources/views/mentoring_diary_modal.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Tutor JS updated")
