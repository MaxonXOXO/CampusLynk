import os
import re

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

content = content.replace("sgpa || '-'", "sgpa || '0'")
content = content.replace("overall.cgpa || '-'", "overall.cgpa || '0'")

# And in HTML blocks for the default values
content = content.replace('id="overallCgpa">-</h3>', 'id="overallCgpa">0</h3>')
content = content.replace('id="overallActivityPoints">-</h3>', 'id="overallActivityPoints">0</h3>')

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated SGPA and CGPA defaults to 0")
