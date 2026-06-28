import os

with open('resources/views/student_mentoring_scripts.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_fetch = """      fetch("/api/mentoring/leave/save", {
        method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data)
      }).then(res => res.json()).then(resData => {
        if (resData.status === "SUCCESS") {
          closeLeaveModal();
          loadStudentMentoringDiary(); // Reload UI
        } else alert("Error: " + resData.message);
      });"""

new_fetch = """      fetch("/api/mentoring/leave/save", {
        method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data)
      }).then(async res => {
          if (!res.ok) {
              const text = await res.text();
              try {
                  const errJson = JSON.parse(text);
                  throw new Error(errJson.message || "Server Error");
              } catch(e) {
                  throw new Error("HTTP " + res.status + " " + text.substring(0, 50));
              }
          }
          return res.json();
      }).then(resData => {
        if (resData.status === "SUCCESS") {
          closeLeaveModal();
          loadStudentMentoringDiary(); // Reload UI
        } else alert("Error: " + resData.message);
      }).catch(err => {
          alert("Save Error: " + err.message);
      });"""

content = content.replace(old_fetch, new_fetch)

with open('resources/views/student_mentoring_scripts.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Added robust error handling to fetch")
