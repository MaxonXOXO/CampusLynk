import os
import re

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8", errors="replace") as f:
    content = f.read()

content = content.replace("Ã¢Â€Â”", "-")

# Just to be 100% sure about the SGPA line:
content = re.sub(r"\$\{semData.sgpa \|\| 'â€¢â€¢'\}", r"${semData.sgpa || '-'}", content)
content = re.sub(r"\$\{semData.sgpa \|\| 'â€”'\}", r"${semData.sgpa || '-'}", content)
content = re.sub(r"\$\{semData.sgpa \|\| 'Ã¢Â€Â”'\}", r"${semData.sgpa || '-'}", content)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Fixed SGPA junk characters")
