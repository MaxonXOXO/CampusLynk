import os

path = "app/Http/Controllers/MentoringController.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Find the studentViewDiary return block by looking for unique pattern
idx = content.find("'academics' => $academics\r\n              ]);")
print("Pattern 1 idx:", idx)

idx2 = content.find("'academics' => $academics\n              ]);")
print("Pattern 2 idx:", idx2)

# Try to find it differently
import re
matches = list(re.finditer(r"'status' => 'SUCCESS',\s*'student' => \$student", content))
print("Matches found:", len(matches))
for m in matches:
    print("At:", m.start(), content[m.start():m.start()+100])
