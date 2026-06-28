import re

with open("resources/views/student_mentoring_scripts.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

# Replace editLeave
old_edit_leave = """  function editLeave(lv) {
    if(document.getElementById("leaveId")) document.getElementById("leaveId").value = lv.id || "";
    if(document.getElementById("leaveSem")) document.getElementById("leaveSem").value = lv.semester || 1;
    if(document.getElementById("leaveDate")) document.getElementById("leaveDate").value = lv.leave_date || "";
    if(document.getElementById("leaveReason")) document.getElementById("leaveReason").value = lv.reason || "";
    if(document.getElementById("leaveStatus")) document.getElementById("leaveStatus").value = lv.status || "Pending";
    if(document.getElementById("leaveParent")) document.getElementById("leaveParent").checked = lv.parent_informed ? true : false;
    if(document.getElementById("leaveModalTitle")) document.getElementById("leaveModalTitle").innerText = "Edit Leave Record";
    if(document.getElementById("addLeaveModal")) {
      document.getElementById("addLeaveModal").classList.remove("hidden");
      document.getElementById("addLeaveModal").classList.add("flex");
    }
  }"""

new_edit_leave = """  function editLeave(lv) {
    if(document.getElementById("leaveId")) document.getElementById("leaveId").value = lv.id || "";
    if(document.getElementById("leaveSem")) document.getElementById("leaveSem").value = lv.semester || 1;
    
    if(document.getElementById("leaveDays")) document.getElementById("leaveDays").value = lv.no_of_days || "";
    
    if(document.getElementById("leaveDateFrom")) {
        let dates = (lv.leave_date || "").split(" to ");
        document.getElementById("leaveDateFrom").value = dates[0] || "";
        if(document.getElementById("leaveDateTo") && dates.length > 1) {
            document.getElementById("leaveDateTo").value = dates[1] || "";
        }
    }
    
    if(document.getElementById("leaveReason")) document.getElementById("leaveReason").value = lv.reason || "";
    if(document.getElementById("leaveStatus")) document.getElementById("leaveStatus").value = lv.status || "Pending";
    if(document.getElementById("leaveParent")) document.getElementById("leaveParent").checked = lv.parent_informed ? true : false;
    if(document.getElementById("leaveModalTitle")) document.getElementById("leaveModalTitle").innerText = "Edit Leave Record";
    if(document.getElementById("addLeaveModal")) {
      document.getElementById("addLeaveModal").classList.remove("hidden");
      document.getElementById("addLeaveModal").classList.add("flex");
    }
  }"""
if old_edit_leave in content:
    content = content.replace(old_edit_leave, new_edit_leave)
else:
    print("Could not find editLeave block")

# Replace saveLeave
old_save_leave = """  function saveLeave(e) {
    e.preventDefault();
    const data = {
      id: document.getElementById("leaveId").value,
      reg_no: window.TARGET_REG_NO || '',
      semester: document.getElementById("leaveSem").value,
      leave_date: (() => {
        let from = document.getElementById("leaveDateFrom").value;
        let to = document.getElementById("leaveDateTo") ? document.getElementById("leaveDateTo").value : "";
        return to ? from + " to " + to : from;
      })(),
      reason: document.getElementById("leaveReason").value,
      status: document.getElementById("leaveStatus").value,
      parent_informed: document.getElementById("leaveParent").checked
    };
    fetch("/api/mentoring/leave/save", {
      method: "POST", headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify(data)
    }).then(res => res.json()).then(resData => {
      if (resData.status === "SUCCESS") {
        closeLeaveModal();
        loadStudentMentoringDiary(); // Reload UI
      } else alert("Error: " + resData.message);
    });
  }"""

new_save_leave = """  function saveLeave(e) {
    e.preventDefault();
    const data = {
      id: document.getElementById("leaveId").value,
      reg_no: window.TARGET_REG_NO || '',
      semester: document.getElementById("leaveSem").value,
      leave_date: (() => {
        let from = document.getElementById("leaveDateFrom").value;
        let to = document.getElementById("leaveDateTo") ? document.getElementById("leaveDateTo").value : "";
        return to ? from + " to " + to : from;
      })(),
      no_of_days: document.getElementById("leaveDays") ? document.getElementById("leaveDays").value : "",
      reason: document.getElementById("leaveReason").value,
      status: document.getElementById("leaveStatus").value,
      parent_informed: document.getElementById("leaveParent").checked
    };
    fetch("/api/mentoring/leave/save", {
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
        closeLeaveModal();
        loadStudentMentoringDiary(); // Reload UI
      } else alert("Error: " + resData.message);
    }).catch(err => {
        alert("Save Error: " + err.message);
    });
  }"""
if old_save_leave in content:
    content = content.replace(old_save_leave, new_save_leave)
else:
    print("Could not find saveLeave block")


with open("resources/views/student_mentoring_scripts.blade.php", "w", encoding="utf-8") as f:
    f.write(content)

print("Fixed editLeave and saveLeave")
