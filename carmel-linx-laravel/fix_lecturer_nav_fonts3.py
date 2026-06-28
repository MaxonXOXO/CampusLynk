import os
import re

path = "resources/views/lecturer_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Change JS nav.className from text-base to text-sm
content = content.replace(
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-base flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";',
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";'
)
content = content.replace(
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-base flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";',
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";'
)

# Fix the HTML nav classes (change text-base to text-sm, and remove any trailing text-[10px])
content = re.sub(
    r'<button id="navDashboard" (.*?) class="(.*?)text-base(.*?)text-\[10px\]\s*">',
    r'<button id="navDashboard" \1 class="\2text-sm\3">',
    content
)
content = re.sub(
    r'<button id="navSecurity" (.*?) class="(.*?)text-base(.*?)">',
    r'<button id="navSecurity" \1 class="\2text-sm\3">',
    content
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated lecturer nav fonts to text-sm and stripped text-[10px]")
