import os

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Replace using unicode escapes if needed, but let's just replace all non-ascii characters that are commonly junk
import re
# We know the junk characters are â€” (which is \xe2\x80\x94 in UTF-8 if read as CP1252, or similar)
# Let's just find "EL " and see what follows it.
match = re.search(r'EL\s+([^\s]+)\s+Student', content)
if match:
    print("Found profile junk:", repr(match.group(1)))

match2 = re.search(r"overall.cgpa \|\| '([^']+)'", content)
if match2:
    print("Found CGPA JS junk:", repr(match2.group(1)))
    
match3 = re.search(r"SGPA:.*?<strong[^>]*>([^<]+)</strong>", content)
if match3:
    print("Found SGPA HTML junk:", repr(match3.group(1)))
    
match4 = re.search(r"sgpaHtml = `<strong[^>]*>([^<]+)</strong>`", content)
if match4:
    print("Found SGPA JS junk:", repr(match4.group(1)))
