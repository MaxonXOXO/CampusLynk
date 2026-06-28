import os

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

content = content.replace(
    '<div><p class="font-black text-slate-500 uppercase tracking-widest">',
    '<div><p class="font-black text-slate-500 uppercase tracking-widest text-[10px]">'
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Added text-[10px] to stat card labels")
