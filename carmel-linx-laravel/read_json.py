import json
with open("check_report.json", "r") as f:
    data = json.load(f)
print("Keys:", data.keys())
print("Overall:", data.get("overall"))
if "semesters" in data and len(data["semesters"]) > 0:
    sem = data["semesters"][0]
    print("Semester 1 keys:", sem.keys())
    if "subjects" in sem and len(sem["subjects"]) > 0:
        print("Subject 1 keys:", sem["subjects"][0].keys())
        print("Subject 1 sample:", sem["subjects"][0])
