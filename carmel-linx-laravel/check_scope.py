path = "resources/views/student_mentoring_scripts.blade.php"
with open(path, "r", encoding="utf-8") as f:
    lines = f.readlines()

for i, line in enumerate(lines):
    if "smdAcademicsList" in line or "renderDiaryAcademic" in line or "populateMentoringUI" in line:
        print(f"{i+1}: {line.rstrip()}")
