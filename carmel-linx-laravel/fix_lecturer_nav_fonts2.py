import os
import re

path = "resources/views/lecturer_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Fix JS nav classes (bump text-sm to text-base)
content = re.sub(
    r'if \(nav\) nav\.className = "w-full text-left px-4 py-2\.5 rounded-r-xl rounded-l-none font-bold text-sm(.*?)"',
    r'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-base\1"',
    content
)

content = re.sub(
    r'if \(nav\) nav\.className = "w-full text-left px-4 py-2\.5 rounded-xl font-bold text-sm(.*?)"',
    r'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-base\1"',
    content
)

# Fix HTML nav buttons (replace text-sm with text-base)
# Find buttons like <button id="navBatches" ... class="... text-sm ...">
content = re.sub(r'id="nav(.*?)"(.*?)text-sm(.*?)', r'id="nav\1"\2text-base\3', content)

# My Batches Title
content = re.sub(r'<h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-lg">My Batches</h1>', r'<h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-2xl">My Batches</h1>', content)
content = re.sub(r'<h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-sm">My Batches</h1>', r'<h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-2xl">My Batches</h1>', content)
content = re.sub(r'<h1 id="panelTitle" class="(.*?)">', r'<h1 id="panelTitle" class="font-extrabold text-slate-100 tracking-tight text-2xl">', content)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated lecturer_dashboard.blade.php fonts to text-base")
