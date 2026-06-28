import os
import subprocess
import re

dir_path = "resources/views"
for root, dirs, files in os.walk(dir_path):
    for file in files:
        if file.endswith(".blade.php"):
            path = os.path.join(root, file)
            git_path = "carmel-linx-laravel/" + path.replace(os.sep, "/")
            
            try:
                orig = subprocess.check_output(["git", "show", f"HEAD:{git_path}"], text=True, encoding="utf-8")
            except Exception as e:
                continue

            with open(path, "r", encoding="utf-8") as f:
                curr = f.read()

            pattern = r"\btext-(?:\[\d+px\]|xs|sm|base|lg|xl|2xl|3xl|4xl|5xl|6xl)\b"
            orig_matches = re.findall(pattern, orig)
            curr_matches = re.findall(pattern, curr)

            if len(orig_matches) == len(curr_matches):
                def repl(match):
                    return orig_matches.pop(0)
                
                new_curr = re.sub(pattern, repl, curr)
                if new_curr != curr:
                    with open(path, "w", encoding="utf-8") as f:
                        f.write(new_curr)
                    print(f"Restored fonts for {path}")
            else:
                pass
