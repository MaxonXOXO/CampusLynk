import re

with open("resources/views/student_mentoring_scripts.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

# Make all fetches have Accept: application/json
content = re.sub(
    r'"Content-Type": "application/json", "X-CSRF-TOKEN"',
    '"Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN"',
    content
)

# If it's already there from previous replace, it will be duplicated, so let's clean it up
content = content.replace('"Accept": "application/json", "Accept": "application/json"', '"Accept": "application/json"')

with open("resources/views/student_mentoring_scripts.blade.php", "w", encoding="utf-8") as f:
    f.write(content)

print("Headers fixed")
