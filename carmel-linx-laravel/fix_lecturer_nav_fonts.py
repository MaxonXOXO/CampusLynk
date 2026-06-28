import os
import re

path = "resources/views/lecturer_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Fix JS nav classes for lecturer dashboard (bump text-[10px] or text-xs to text-sm)
content = re.sub(
    r'if \(nav\) nav\.className = "w-full text-left px-4 py-2\.5 rounded-r-xl rounded-l-none font-bold text-\[[^\]]+\](.*?)";',
    r'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm\1";',
    content
)
content = re.sub(
    r'if \(nav\) nav\.className = "w-full text-left px-4 py-2\.5 rounded-r-xl rounded-l-none font-bold text-xs(.*?)";',
    r'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-r-xl rounded-l-none font-bold text-sm\1";',
    content
)

content = re.sub(
    r'if \(nav\) nav\.className = "w-full text-left px-4 py-2\.5 rounded-xl font-bold text-\[[^\]]+\](.*?)";',
    r'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm\1";',
    content
)
content = re.sub(
    r'if \(nav\) nav\.className = "w-full text-left px-4 py-2\.5 rounded-xl font-bold text-xs(.*?)";',
    r'if (nav) nav.className = "w-full text-left px-4 py-2.5 rounded-xl font-bold text-sm\1";',
    content
)

# Fix HTML nav buttons (replace text-[10px] with text-sm)
content = re.sub(r'id="nav(.*?)"(.*?)text-\[10px\](.*?)', r'id="nav\1"\2text-sm\3', content)

# Remove any duplicated text-xs if it exists after text-sm
content = re.sub(r'id="nav(.*?)"(.*?)text-sm(.*?)text-xs"', r'id="nav\1"\2text-sm\3"', content)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated lecturer_dashboard.blade.php nav fonts to text-sm")
