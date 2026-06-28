import re

with open('resources/views/student_mentoring_scripts.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Extract script tags
matches = re.findall(r'<script>(.*?)</script>', content, re.DOTALL)
js = "\n".join(matches)

# Remove blade directives like {{ ... }}
js = re.sub(r'\{\{.*?\}\}', '""', js)

with open('temp_script.js', 'w', encoding='utf-8') as f:
    f.write(js)
