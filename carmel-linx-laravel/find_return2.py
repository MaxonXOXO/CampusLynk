import os

path = "app/Http/Controllers/MentoringController.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

import re

# Find the studentViewDiary return block
match = re.search(r"'status' => 'SUCCESS',\s*\n\s*'student' => \$student,.*?'\);", content, re.DOTALL)
if match:
    print("Found at:", match.start())
    print("Length:", len(match.group()))
    print(match.group()[:500])
else:
    print("Not found with DOTALL")
    # Try to find the block by line numbers
    lines = content.split("\n")
    for i, l in enumerate(lines):
        if "'student' => $student" in l:
            print(f"Line {i}: {l}")
