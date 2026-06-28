import os
import re

path = "resources/views/tutor_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Add text-sm to nav buttons if they don't have it
content = re.sub(
    r'<button id="nav(.*?)"(.*?)class="(.*?)"',
    lambda m: f'<button id="nav{m.group(1)}"{m.group(2)}class="{m.group(3)} text-sm"' if 'text-sm' not in m.group(3) else m.group(0),
    content
)

content = re.sub(
    r'Back to Staff Console(.*?)class="(.*?)"',
    lambda m: f'Back to Staff Console{m.group(1)}class="{m.group(2)} text-sm"' if 'text-sm' not in m.group(2) else m.group(0),
    content
)


with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Added text-sm to tutor HTML nav links")
