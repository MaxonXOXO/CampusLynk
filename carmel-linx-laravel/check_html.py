with open('resources/views/student_mentoring_diary_full.blade.php', 'r', encoding='utf-8') as f:
    student_content = f.read()

with open('resources/views/tutor_student_diary_full.blade.php', 'r', encoding='utf-8') as f:
    tutor_content = f.read()

print("Student view has 'leaveStatus':", 'leaveStatus' in student_content)
print("Tutor view has 'leaveStatus':", 'leaveStatus' in tutor_content)
print("Student view has 'leaveParent':", 'leaveParent' in student_content)
print("Tutor view has 'leaveParent':", 'leaveParent' in tutor_content)
