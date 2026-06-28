import os

with open("routes/web.php", "r", encoding="utf-8") as f:
    content = f.read()

old_routes = """    Route::post('/api/mentoring/leave/save', [MentoringController::class, 'saveLeaveRecord']);"""

new_routes = """    Route::post('/api/mentoring/leave/save', [MentoringController::class, 'saveLeaveRecord']);
    Route::post('/api/mentoring/disciplinary/save', [MentoringController::class, 'saveDisciplinary']);
    Route::post('/api/student/mentoring/extra-curricular/save', [MentoringController::class, 'studentSaveExtraCurricular']);"""

if old_routes in content:
    content = content.replace(old_routes, new_routes)
    with open("routes/web.php", "w", encoding="utf-8") as f:
        f.write(content)
    print("Added disciplinary and extra-curricular routes")
else:
    print("Could not find routes block")
