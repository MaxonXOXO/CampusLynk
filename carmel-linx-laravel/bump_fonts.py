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
                if c == "text-[9px]": return "text-[11px]"
                if c == "text-[10px]": return "text-xs"
                if c == "text-[11px]": return "text-sm"
                if c == "text-[12px]": return "text-sm"
                if c == "text-xs": return "text-sm"
                if c == "text-sm": return "text-base"
                if c == "text-base": return "text-lg"
                if c == "text-lg": return "text-xl"
                if c == "text-xl": return "text-2xl"
                if c == "text-2xl": return "text-3xl"
                return c

            new_content = re.sub(r"\btext-(?:\[9px\]|\[10px\]|\[11px\]|\[12px\]|xs|sm|base|lg|xl|2xl)\b", repl, content)
            
            if new_content != content:
                with open(path, "w", encoding="utf-8") as f:
                    f.write(new_content)

print("Done")
