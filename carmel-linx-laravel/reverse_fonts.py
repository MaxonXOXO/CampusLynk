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
                if c == "text-[11px]": return "text-[9px]"
                if c == "text-xs": return "text-[10px]"
                if c == "text-sm": return "text-xs"
                if c == "text-base": return "text-sm"
                if c == "text-lg": return "text-base"
                if c == "text-xl": return "text-lg"
                if c == "text-2xl": return "text-xl"
                if c == "text-3xl": return "text-2xl"
                return c

            new_content = re.sub(r"\btext-(?:\[11px\]|xs|sm|base|lg|xl|2xl|3xl)\b", repl, content)
            
            if new_content != content:
                with open(path, "w", encoding="utf-8") as f:
                    f.write(new_content)

print("Done")
