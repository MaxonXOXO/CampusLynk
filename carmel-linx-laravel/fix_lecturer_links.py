import os
import re

path = "resources/views/lecturer_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Replace trailing junk sizes in all anchor tags in the sidebar
content = re.sub(
    r'<a href="/dashboard/tutor" class="(.*?)text-\[10px\](.*?)text-\[10px\] text-xs">',
    r'<a href="/dashboard/tutor" class="\1text-sm\2">',
    content
)
content = re.sub(
    r'<a href="/dashboard/tutor"(.*?)onclick="sessionStorage(.*?)class="(.*?)text-\[10px\](.*?)text-\[10px\] text-xs">',
    r'<a href="/dashboard/tutor"\1onclick="sessionStorage\2class="\3text-sm\4">',
    content
)
content = re.sub(
    r'<a href="/course-files" class="(.*?)text-\[10px\](.*?)text-\[10px\] text-xs">',
    r'<a href="/course-files" class="\1text-sm\2">',
    content
)
content = re.sub(
    r'<a href="/remedial-sessions" class="(.*?)text-\[10px\](.*?)">',
    r'<a href="/remedial-sessions" class="\1text-sm\2">',
    content
)

# And if there are any that just have one text-[10px] remaining:
content = re.sub(r'href="/dashboard/tutor" class="(.*?)text-\[10px\](.*?)">', r'href="/dashboard/tutor" class="\1text-sm\2">', content)
content = re.sub(r'href="/course-files" class="(.*?)text-\[10px\](.*?)">', r'href="/course-files" class="\1text-sm\2">', content)
content = re.sub(r'href="/dashboard/tutor"(.*?)onclick="sessionStorage(.*?)class="(.*?)text-\[10px\](.*?)">', r'href="/dashboard/tutor"\1onclick="sessionStorage\2class="\3text-sm\4">', content)
content = re.sub(r'href="/remedial-sessions" class="(.*?)text-\[10px\](.*?)">', r'href="/remedial-sessions" class="\1text-sm\2">', content)

# Just to be absolutely sure no trailing classes override it, remove `text-xs` from those tags
content = re.sub(r'<a href="/dashboard/tutor" class="(.*?)text-sm(.*?)text-xs(.*?)">', r'<a href="/dashboard/tutor" class="\1text-sm\2\3">', content)
content = re.sub(r'<a href="/course-files" class="(.*?)text-sm(.*?)text-xs(.*?)">', r'<a href="/course-files" class="\1text-sm\2\3">', content)
content = re.sub(r'<a href="/dashboard/tutor"(.*?)onclick="sessionStorage(.*?)class="(.*?)text-sm(.*?)text-xs(.*?)">', r'<a href="/dashboard/tutor"\1onclick="sessionStorage\2class="\3text-sm\4\5">', content)
content = re.sub(r'<a href="/remedial-sessions" class="(.*?)text-sm(.*?)text-xs(.*?)">', r'<a href="/remedial-sessions" class="\1text-sm\2\3">', content)

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated lecturer link fonts")
