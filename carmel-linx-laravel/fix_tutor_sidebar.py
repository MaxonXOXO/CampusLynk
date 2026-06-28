import os
import re

path = "resources/views/tutor_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Strip out old text-[10px] and text-xs classes from the nav HTML
content = re.sub(r'id="nav(.*?)"(.*?)text-\[10px\](.*?)', r'id="nav\1"\2', content)
content = re.sub(r'id="nav(.*?)"(.*?)text-xs(.*?)', r'id="nav\1"\2', content)
content = re.sub(r'Back to Staff Console(.*?)text-\[10px\](.*?)', r'Back to Staff Console\1', content)
content = re.sub(r'Back to Staff Console(.*?)text-xs(.*?)', r'Back to Staff Console\1', content)

# 2. Update HTML default classes to use text-sm and new colors
content = content.replace(
    'id="navRoster" onclick="switchPanel(\'roster\')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500 "',
    'id="navRoster" onclick="switchPanel(\'roster\')" class="w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500"'
)

content = content.replace(
    'id="navAudit" onclick="switchPanel(\'audit\')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer "',
    'id="navAudit" onclick="switchPanel(\'audit\')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium cursor-pointer text-amber-400 hover:bg-amber-900/30 hover:text-amber-300"'
)

content = content.replace(
    'id="navProfile" onclick="switchPanel(\'profile\')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer "',
    'id="navProfile" onclick="switchPanel(\'profile\')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium cursor-pointer text-slate-400 hover:bg-slate-800 hover:text-white"'
)

content = content.replace(
    'id="navMentoring" onclick="switchPanel(\'mentoring\')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer "',
    'id="navMentoring" onclick="switchPanel(\'mentoring\')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium cursor-pointer text-emerald-400 hover:bg-emerald-900/30 hover:text-emerald-300"'
)

content = content.replace(
    'id="navActivity" onclick="switchPanel(\'activity\')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer "',
    'id="navActivity" onclick="switchPanel(\'activity\')" class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium cursor-pointer text-purple-400 hover:bg-purple-900/30 hover:text-purple-300"'
)

# Fix back link
content = re.sub(
    r'<a href="\{\{ \$backLink \}\}" class="(.*?)text-sky-400(.*?)block mt-4 border border-sky-900/50\s*">',
    r'<a href="{{ $backLink }}" class="\1text-sm text-sky-400\2block mt-4 border border-sky-900/50">',
    content
)

# 3. Update JS logic for switchPanel
old_js = """        panels.forEach(id => {
          const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
          const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
          
          if (id === panelId) {
            if (el) el.classList.remove('hidden');
            if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
          } else {
            if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
            if (el) el.classList.add('hidden');
          }
        });"""

new_js = """        const inactiveColors = {
          'roster': 'text-cyan-400 hover:bg-cyan-900/30 hover:text-cyan-300',
          'audit': 'text-amber-400 hover:bg-amber-900/30 hover:text-amber-300',
          'profile': 'text-slate-400 hover:bg-slate-800 hover:text-white',
          'mentoring': 'text-emerald-400 hover:bg-emerald-900/30 hover:text-emerald-300',
          'activity': 'text-purple-400 hover:bg-purple-900/30 hover:text-purple-300'
        };
        panels.forEach(id => {
          const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
          const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
          
          if (id === panelId) {
            if (el) el.classList.remove('hidden');
            if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm flex items-center gap-3 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
          } else {
            if (nav) nav.className = `w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-3 transition-premium cursor-pointer ${inactiveColors[id]}`;
            if (el) el.classList.add('hidden');
          }
        });"""

content = content.replace(old_js, new_js)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated tutor dashboard sidebar colors and fonts")
