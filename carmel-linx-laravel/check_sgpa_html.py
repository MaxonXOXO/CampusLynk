import os

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

import re
matches = re.findall(r"sgpaHtml = .*?;", content)
print("sgpaHtml:", set(matches))

matches2 = re.findall(r"pointsHtml = .*?;", content)
print("pointsHtml:", set(matches2))
