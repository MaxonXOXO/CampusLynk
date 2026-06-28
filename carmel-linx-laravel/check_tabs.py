with open('resources/views/student_mentoring_diary_full.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

tabs = ['smdProfile', 'smdFamily', 'smdEducation', 'smdAcademic', 'smdBoard', 'smdExtra', 'smdLeave', 'smdMeetings']
for tab in tabs:
    print(f"{tab}: {'id=\"' + tab + '\"' in content}")
