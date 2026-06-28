import os

path = "resources/views/student_mentoring_scripts.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Replace the direct remove with a confirmation for all row types
old = 'onclick="this.closest(\'tr\').remove()" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button>'
new = 'onclick="if(confirm(\'Are you sure you want to remove this row? This action cannot be undone.\')) this.closest(\'tr\').remove();" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button>'

count = content.count(old)
content = content.replace(old, new)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print(f"Updated {count} remove buttons with confirmation dialog")
