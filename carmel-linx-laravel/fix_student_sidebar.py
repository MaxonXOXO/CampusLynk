import os
import re

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Update JS switchPanel
content = content.replace(
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";',
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";'
)
content = content.replace(
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";',
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";'
)

# Update HTML sidebar links
# Remove existing sizes
content = re.sub(r'id="nav(.*?)"(.*?)text-\[10px\](.*?)', r'id="nav\1"\2', content)
content = re.sub(r'id="nav(.*?)"(.*?)text-xs(.*?)', r'id="nav\1"\2', content)

# Append text-sm
content = re.sub(
    r'<button id="nav(.*?)"(.*?)class="(.*?)"',
    lambda m: f'<button id="nav{m.group(1)}"{m.group(2)}class="{m.group(3)} text-sm"' if 'text-sm' not in m.group(3) else m.group(0),
    content
)

# Update logout button font if needed
content = re.sub(
    r'<a href="/logout"(.*?)text-\[10px\] text-xs(.*?)',
    r'<a href="/logout"\1text-sm\2',
    content
)


with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated student dashboard sidebar fonts to text-sm")
