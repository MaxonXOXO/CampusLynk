import os
import re
import subprocess

with open('resources/views/student_mentoring_scripts.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace blade tags with empty strings for js parsing
content = re.sub(r'\{\{.*?\}\}', '""', content)
content = re.sub(r'\{!!.*?!!\}', '""', content)

with open('temp.js', 'w', encoding='utf-8') as f:
    f.write(content)

result = subprocess.run(['node', '-c', 'temp.js'], capture_output=True, text=True)
print("Return code:", result.returncode)
print("Stdout:", result.stdout)
print("Stderr:", result.stderr)
