import os

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

content = content.replace("â€”", "-")

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Fixed junk characters globally in student dashboard")
