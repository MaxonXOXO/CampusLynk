import os

with open("resources/views/student_mentoring_scripts.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

old_payload = """    const payload = {
        activity_id: document.getElementById("studentActivityId").value,
        semester: document.getElementById("studentActivitySemester").value,"""

new_payload = """    const payload = {
        reg_no: window.TARGET_REG_NO || '',
        activity_id: document.getElementById("studentActivityId").value,
        semester: document.getElementById("studentActivitySemester").value,"""

if old_payload in content:
    content = content.replace(old_payload, new_payload)
    with open("resources/views/student_mentoring_scripts.blade.php", "w", encoding="utf-8") as f:
        f.write(content)
    print("Fixed extra-curricular payload")
else:
    print("Could not find extra-curricular payload")
