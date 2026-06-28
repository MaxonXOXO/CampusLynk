import os

path = "app/Http/Controllers/DataController.php"
with open(path, "r", encoding="utf-8") as f:
    lines = f.readlines()

in_func = False
depth = 0
result = []
for i, line in enumerate(lines):
    if "function getAcademicReport" in line:
        in_func = True
    if in_func:
        result.append(f"{i+1}: {line.rstrip()}")
        depth += line.count("{") - line.count("}")
        if depth < 0 or (depth == 0 and in_func and i > 0 and "}" in line):
            break

print("\n".join(result[:120]))
