import os
import re

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8", errors="replace") as f:
    content = f.read()

# 1. Profile: EL â€¢ Student
content = re.sub(r'EL [^\s]+ Student', r'EL &bull; Student', content)
content = re.sub(r'{{ session\(\'userBranch\'\) }} [^\s]+ Student', r"{{ session('userBranch') }} &bull; Student", content)

# 2. CGPA JS Junk
content = re.sub(r"overall\.cgpa \|\| '[^']+'", r"overall.cgpa || '-'", content)

# 3. SGPA JS Junk
content = re.sub(r"sgpaHtml = `<strong[^>]*>[^<]+</strong>`", r"sgpaHtml = `<strong class=\"text-slate-200\">-</strong>`", content)

# 4. Points JS Junk
content = re.sub(r"pointsHtml = `<strong[^>]*>[^<]+</strong>`", r"pointsHtml = `<strong class=\"text-slate-200\">-</strong>`", content)

# 5. HTML placeholders
# Sometimes they are written directly in HTML before JS loads
content = re.sub(r'id="overallCgpa">[^<]+</h3>', r'id="overallCgpa">-</h3>', content)
content = re.sub(r'id="overallActivityPoints">[^<]+</h3>', r'id="overallActivityPoints">-</h3>', content)
content = re.sub(r'<strong class="text-slate-200" id="headerSemValue">[^<]+</strong>', r'<strong class="text-slate-200" id="headerSemValue">-</strong>', content)

# 6. Branch / Batch / Sem top header
content = re.sub(r'session\(\'userBranch\', \'[^\']+\'\)', r"session('userBranch', '-')", content)
content = re.sub(r'session\(\'classroomId\', \'[^\']+\'\)', r"session('classroomId', '-')", content)


with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Junk chars completely sanitized")
