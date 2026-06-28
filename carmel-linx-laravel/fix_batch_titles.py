import os

# Update Lecturer Dashboard
path = "resources/views/lecturer_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

content = content.replace(
    '<h4 class="font-black text-slate-200 text-[10px] tracking-tight">${b.classroom_id}</h4>',
    '<h4 class="font-black text-slate-200 text-xl tracking-tight">${b.classroom_id}</h4>'
)
content = content.replace(
    '<div class="text-[10px] text-slate-400 font-mono mt-0.5">${b.branch}',
    '<div class="text-sm text-slate-400 font-mono mt-0.5">${b.branch}'
)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)


# Update HOD Dashboard
path2 = "resources/views/hod_dashboard.blade.php"
with open(path2, "r", encoding="utf-8") as f:
    content2 = f.read()

content2 = content2.replace(
    '<span class="px-2 py-0.5 bg-violet-500/10 text-violet-400 border border-violet-500/20 rounded-lg font-mono text-xs font-bold">${batch.classroom_id}</span>',
    '<span class="px-2 py-0.5 bg-violet-500/10 text-violet-400 border border-violet-500/20 rounded-lg font-mono text-base font-bold">${batch.classroom_id}</span>'
)

with open(path2, "w", encoding="utf-8") as f:
    f.write(content2)

print("Updated batch card titles")
