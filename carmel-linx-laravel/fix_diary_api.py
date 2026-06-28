import os

# Fix 1: The studentViewDiary returns top-level keys but populateMentoringUI
# expects data.data wrapper. Fix the API response to wrap in "data" key.
path = "app/Http/Controllers/MentoringController.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Find the studentViewDiary return statement and wrap in "data"
old_return = """              return response()->json([
                  'status' => 'SUCCESS',
                  'student' => $student,
                  'family' => $family,
                  'education' => $education,
                  'fees' => $fees,
                  'extracurricular' => $extracurricular,
                  'meetings' => $meetings,
                  'leaves' => $leaves,
                  'disciplinary' => $disciplinary,
                  'board' => $board,
                  'academics' => $academics
              ]);"""

new_return = """              return response()->json([
                  'status' => 'SUCCESS',
                  'data' => [
                      'profile'        => $student,
                      'family'         => $family,
                      'education'      => $education,
                      'fees'           => $fees,
                      'extracurricular'=> $extracurricular,
                      'meetings'       => $meetings,
                      'leaves'         => $leaves ?? [],
                      'disciplinary'   => $disciplinary ?? [],
                      'board'          => $board,
                      'academics'      => $academics,
                      'syllabus_list'  => [],
                  ]
              ]);"""

if old_return in content:
    content = content.replace(old_return, new_return)
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print("Fixed studentViewDiary to wrap in data key")
else:
    print("Could not find the old return - checking...")
    # Find nearby text
    idx = content.find("'academics' => $academics")
    print("Found academics at index:", idx)
    print("Context:", repr(content[idx-200:idx+200]))
