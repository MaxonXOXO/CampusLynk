import os

with open('routes/web.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_route = "Route::post('/api/mentoring/diary/approve', [MentoringController::class, 'approveDiaryEntry']);"
new_route = "Route::post('/api/mentoring/diary/approve', [MentoringController::class, 'approveDiaryEntry']);\n    Route::post('/api/mentoring/leave/save', [MentoringController::class, 'saveLeaveRecord']);"

content = content.replace(old_route, new_route)

with open('routes/web.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("Route added")
