import os
import re

dir_path = "resources/views"
for root, dirs, files in os.walk(dir_path):
    for file in files:
        if file.endswith(".blade.php"):
            path = os.path.join(root, file)
            with open(path, "r", encoding="utf-8") as f:
                content = f.read()

            def repl(match):
                c = match.group(0)
                if c == "text-[10px]": return "text-[9px]"
                if c == "text-[11px]": return "text-[10px]"
                if c == "text-[12px]": return "text-[11px]"
                return c

            new_content = re.sub(r"\btext-(?:\[10px\]|\[11px\]|\[12px\])\b", repl, content)
            
            if new_content != content:
                with open(path, "w", encoding="utf-8") as f:
                    f.write(new_content)

print("Done")
