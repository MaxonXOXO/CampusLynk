import os

path = "app/Http/Controllers/DataController.php"
with open(path, "r", encoding="utf-8") as f:
    lines = f.readlines()

in_func = False
for i, line in enumerate(lines):
    if "function getAcademicReport" in line:
        in_func = True
    
    if in_func:
        print(f"{i}: {line.rstrip()}")
        if "return response()->json" in line and "$data" in line:
            break
