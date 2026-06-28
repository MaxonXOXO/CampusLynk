import os
import re

path = "resources/views/hod_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Fix JS nav classes (reduce text-base to text-sm)
content = content.replace(
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-base flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";',
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";'
)

content = content.replace(
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-base flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";',
    'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";'
)

# Fix HTML nav buttons: they currently have `text-sm` and `text-base` in the string.
# We want to replace `text-sm ... text-base` with just `text-sm`.
# Example: class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer text-base"
content = re.sub(r'id="nav(.*?)"(.*?)text-sm(.*?)text-base"', r'id="nav\1"\2text-sm\3"', content)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated hod_dashboard.blade.php nav fonts to text-sm")
