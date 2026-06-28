import os

with open("resources/views/student_mentoring_scripts.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

old_save = """  function saveMentoringSession(e) {
    e.preventDefault();
    const data = {
      id: document.getElementById("sessionId").value,
      reg_no: window.TARGET_REG_NO || '',
      semester: document.getElementById("sessionSem").value,
      date: document.getElementById("sessionDate").value,
      discussion_points: document.getElementById("sessionDiscussion").value,
      action_items: document.getElementById("sessionAction").value
    };
    fetch("/api/mentoring/session/save", {"""

new_save = """  function saveMentoringSession(e) {
    e.preventDefault();
    const data = {
      reg_no: window.TARGET_REG_NO || '',
      date: document.getElementById("sessionDate").value,
      category: 'Mentoring',
      discussion_notes: document.getElementById("sessionDiscussion").value,
      action_taken: document.getElementById("sessionAction").value
    };
    fetch("/api/mentoring/diary/add", {"""

if old_save in content:
    content = content.replace(old_save, new_save)
    with open("resources/views/student_mentoring_scripts.blade.php", "w", encoding="utf-8") as f:
        f.write(content)
    print("Fixed saveMentoringSession payload and URL")
else:
    print("Could not find saveMentoringSession")
