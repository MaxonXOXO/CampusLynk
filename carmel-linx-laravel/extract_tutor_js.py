import re

with open('resources/views/tutor_student_diary_full.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Extract script tags
matches = re.findall(r'<script>(.*?)</script>', content, re.DOTALL)
js = "\n".join(matches)

# Remove blade directives like {{ ... }}
js = re.sub(r'\{\{.*?\}\}', '""', js)

with open('temp_tutor_script.js', 'w', encoding='utf-8') as f:
    f.write(js)
