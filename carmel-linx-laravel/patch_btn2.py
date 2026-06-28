import re

with open("resources/views/tutor_student_diary_full.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

# Just append the button after the openDiscModal button using regex
content = re.sub(
    r'(<button onclick="openDiscModal\(\)".*?</button>)',
    r'\1\n              <button onclick="openSessionModal()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-1 text-[10px] text-xs">\n                <span class="material-symbols-rounded text-sm">groups</span> Record Session\n              </button>',
    content,
    flags=re.DOTALL
)

with open("resources/views/tutor_student_diary_full.blade.php", "w", encoding="utf-8") as f:
    f.write(content)

print("Added session button via regex")
