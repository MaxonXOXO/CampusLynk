import os
import re

path = "resources/views/hod_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Clean up duplicate font sizes
content = re.sub(r'text-\[10px\]\s+text-xs', 'text-sm', content)
content = re.sub(r'text-\[10px\]\s+text-sm', 'text-base', content)

# 1. Sidebar font size a little more large (text-sm -> text-base for sidebar navs)
content = re.sub(r'id="nav(.*?)"(.*?)text-sm', r'id="nav\1"\2text-base', content)
content = re.sub(r'nav.className = "(.*?)text-\[10px\]', r'nav.className = "\1text-base', content)

# 2. Admission 2026 font larger (similar to class management)
content = re.sub(r'<h4 class="font-black text-slate-100 text-\[10px\]">Admission', r'<h4 class="font-black text-slate-100 text-base">Admission', content)

# 3. Other fonts in card next size
content = re.sub(r'text-\[10px\](.*?)batch\.batch_year', r'text-xs\1batch.batch_year', content)
content = re.sub(r'text-\[10px\](.*?)batch\.classroom_id', r'text-xs\1batch.classroom_id', content)
content = re.sub(r'text-\[10px\](.*?)batch\.tutor_name', r'text-xs\1batch.tutor_name', content)

# 4. Batch and class management title
content = re.sub(r'Batch & Class Management \(\{\{', r'Batch & Class Management ({{', content) # Ensure standard text
content = re.sub(r'text-sm">Batch & Class Management', r'text-lg">Batch & Class Management', content)
content = re.sub(r'text-[10px]">Batch & Class Management', r'text-lg">Batch & Class Management', content)

# 5. Create batch button & other buttons
content = content.replace('text-[10px]', 'text-sm')

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated hod_dashboard.blade.php fonts")
