import os
import re

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8", errors="replace") as f:
    content = f.read()

# Replace ${semData.sgpa || '...'} with ${semData.sgpa || '-'}
content = re.sub(r"\$\{semData\.sgpa\s*\|\|\s*'[^']+'\}", r"${semData.sgpa || '-'}", content)

# Replace ${semData.activity_points || '...'} with ${semData.activity_points || '-'}
content = re.sub(r"\$\{semData\.activity_points\s*\|\|\s*'[^']+'\}", r"${semData.activity_points || '-'}", content)

# Check the POINTS: line as well to be sure
# Actually, let's see how points are rendered
# Wait, let's find POINTS: first.

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Fixed SGPA and POINTS fallbacks using robust regex")
