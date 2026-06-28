import os

with open('resources/views/student_mentoring_scripts.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_reload = """        if (resData.status === "SUCCESS") {
          closeLeaveModal();
          if (window.TARGET_REG_NO) {
            loadMentoringDiary(window.TARGET_REG_NO);
          } else {
            loadStudentMentoringDiary();
          }
        } else alert("Error: " + resData.message);"""

new_reload = """        if (resData.status === "SUCCESS") {
          closeLeaveModal();
          loadStudentMentoringDiary(); // Reload UI
        } else alert("Error: " + resData.message);"""

content = content.replace(old_reload, new_reload)

with open('resources/views/student_mentoring_scripts.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Reload logic reverted")
