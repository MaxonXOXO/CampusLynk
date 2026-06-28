import os

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

content = content.replace("Ã¢Â€Â”", "-")

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Fixed double-encoded junk characters")
