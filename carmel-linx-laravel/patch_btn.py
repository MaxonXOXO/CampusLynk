import os

with open("resources/views/tutor_student_diary_full.blade.php", "r", encoding="utf-8") as f:
    content = f.read()

old_btn = """              <button onclick="openDiscModal()" class="px-3 py-1.5 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white border border-red-500/30 rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-1 text-[10px] text-xs">
                <span class="material-symbols-rounded text-sm">warning</span> Record Incident
              </button>"""

new_btn = """              <button onclick="openDiscModal()" class="px-3 py-1.5 bg-red-600/20 text-red-400 hover:bg-red-600 hover:text-white border border-red-500/30 rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-1 text-[10px] text-xs">
                <span class="material-symbols-rounded text-sm">warning</span> Record Incident
              </button>
              <button onclick="openSessionModal()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold transition-premium cursor-pointer flex items-center gap-1 text-[10px] text-xs">
                <span class="material-symbols-rounded text-sm">groups</span> Record Session
              </button>"""

if old_btn in content:
    content = content.replace(old_btn, new_btn)
    with open("resources/views/tutor_student_diary_full.blade.php", "w", encoding="utf-8") as f:
        f.write(content)
    print("Added session button")
else:
    print("Could not find button block")
