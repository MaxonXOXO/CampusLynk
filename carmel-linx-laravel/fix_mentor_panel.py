import os
import re

# ===== FIX 1: student_dashboard.blade.php - SGPA fallback to '-' =====
path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Make sure sgpa and activity_points show '-' when null/0
content = content.replace("${semData.sgpa || '0'}", "${semData.sgpa || '-'}")
content = content.replace("${semData.activity_points || '0'}", "${semData.activity_points || '-'}")

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("[1] Fixed student_dashboard SGPA/Points to show '-' when empty")

# ===== FIX 2: student_mentoring_panel.blade.php =====
path2 = "resources/views/student_mentoring_panel.blade.php"
with open(path2, "r", encoding="utf-8") as f:
    content2 = f.read()

# --- 2a. Uplift sidebar tab buttons from text-[10px] text-xs  to text-xs text-sm
# The sidebar card buttons (Personal Info, Family Details etc.)
# Currently: text-[10px] text-xs  => bump to text-xs
content2 = re.sub(
    r'(smd-tab[^"]*) text-\[10px\] text-xs',
    r'\1 text-sm',
    content2
)
content2 = re.sub(
    r'(smd-tab[^"]*) text-\[10px\]',
    r'\1 text-sm',
    content2
)
content2 = re.sub(
    r'(smd-tab[^"]*) text-xs(?! text-sm)',
    r'\1 text-sm',
    content2
)

# --- 2b. Reduce labels in Additional Personal Info page to text-[10px]
content2 = re.sub(
    r'<label class="block text-slate-400 font-bold uppercase tracking-wider mb-1">',
    '<label class="block text-slate-400 font-bold uppercase tracking-wider mb-1 text-[10px]">',
    content2
)

# --- 2c. Reduce input/select fields to text-[11px]
content2 = re.sub(
    r'(class="w-full bg-slate-900/50[^"]*) text-\[10px\] text-xs"',
    r'\1 text-[11px]"',
    content2
)
content2 = re.sub(
    r'(class="w-full bg-slate-900/50[^"]*) text-\[10px\]"',
    r'\1 text-[11px]"',
    content2
)

with open(path2, "w", encoding="utf-8") as f:
    f.write(content2)

print("[2] Fixed student_mentoring_panel tab fonts and form field sizes")
