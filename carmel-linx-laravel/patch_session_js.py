import os

with open("resources/views/student_mentoring_scripts.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

js_code = """
  // Mentor Meeting Functions
  function openSessionModal() {
    if(document.getElementById("sessionForm")) document.getElementById("sessionForm").reset();
    if(document.getElementById("sessionId")) document.getElementById("sessionId").value = "";
    if(document.getElementById("sessionModalTitle")) document.getElementById("sessionModalTitle").innerText = "Record Mentoring Session";
    if(document.getElementById("addSessionModal")) {
      document.getElementById("addSessionModal").classList.remove("hidden");
      document.getElementById("addSessionModal").classList.add("flex");
    }
  }
  function editSession(s) {
    if(document.getElementById("sessionId")) document.getElementById("sessionId").value = s.id || "";
    if(document.getElementById("sessionSem")) document.getElementById("sessionSem").value = s.semester || 1;
    if(document.getElementById("sessionDate")) document.getElementById("sessionDate").value = s.date || "";
    if(document.getElementById("sessionDiscussion")) document.getElementById("sessionDiscussion").value = s.discussion_points || "";
    if(document.getElementById("sessionAction")) document.getElementById("sessionAction").value = s.action_items || "";
    if(document.getElementById("sessionModalTitle")) document.getElementById("sessionModalTitle").innerText = "Edit Mentoring Session";
    if(document.getElementById("addSessionModal")) {
      document.getElementById("addSessionModal").classList.remove("hidden");
      document.getElementById("addSessionModal").classList.add("flex");
    }
  }
  function closeSessionModal() {
    if(document.getElementById("addSessionModal")) {
      document.getElementById("addSessionModal").classList.add("hidden");
      document.getElementById("addSessionModal").classList.remove("flex");
    }
  }
  function saveMentoringSession(e) {
    e.preventDefault();
    const data = {
      id: document.getElementById("sessionId").value,
      reg_no: window.TARGET_REG_NO || '',
      semester: document.getElementById("sessionSem").value,
      date: document.getElementById("sessionDate").value,
      discussion_points: document.getElementById("sessionDiscussion").value,
      action_items: document.getElementById("sessionAction").value
    };
    fetch("/api/mentoring/session/save", {
      method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify(data)
    }).then(async res => {
        if (!res.ok) {
            const text = await res.text();
            try {
                const errJson = JSON.parse(text);
                throw new Error(errJson.message || "Server Error");
            } catch(ex) {
                throw new Error("HTTP " + res.status + ": " + text.substring(0, 50));
            }
        }
        return res.json();
    }).then(resData => {
      if (resData.status === "SUCCESS") {
        closeSessionModal();
        loadStudentMentoringDiary();
      } else alert("Error: " + resData.message);
    }).catch(err => {
        alert("Save Error: " + err.message);
    });
  }
"""

if "saveMentoringSession" not in content:
    content = content.replace("</script>", js_code + "\n</script>")
    with open("resources/views/student_mentoring_scripts.blade.php", "w", encoding="utf-8") as f:
        f.write(content)
    print("Added Mentor Meeting JS")
else:
    print("Mentor Meeting JS already exists")
