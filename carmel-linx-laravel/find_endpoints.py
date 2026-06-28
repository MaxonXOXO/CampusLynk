import os
import re

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

matches = re.findall(r"fetch\('([^']+)'", content)
print("Fetch endpoints:", set(matches))
