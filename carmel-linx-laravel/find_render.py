import os

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    lines = f.readlines()

# Find the renderSemesterTabs / renderSemTable function
for i, line in enumerate(lines):
    if "renderSem" in line or "renderCgpa" in line or "function loadAcademic" in line or "function renderAcademic" in line:
        print(f"{i+1}: {line.rstrip()}")
