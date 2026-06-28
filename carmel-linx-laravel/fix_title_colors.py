import os

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

content = content.replace(
    '<p class="font-black text-slate-300 uppercase tracking-widest text-xs">Cumulative GPA</p>',
    '<p class="font-black text-amber-400 uppercase tracking-widest text-xs">Cumulative GPA</p>'
)

content = content.replace(
    '<p class="font-black text-slate-300 uppercase tracking-widest text-xs">Total Activity Points</p>',
    '<p class="font-black text-blue-400 uppercase tracking-widest text-xs">Total Activity Points</p>'
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Updated titles colors")
