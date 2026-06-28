import os
import re

path = "app/Http/Controllers/MentoringController.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

old_block = "'status' => 'SUCCESS',\r\n                'student' => $student,\r\n                'family' => $family,\r\n                'education' => $education,\r\n                'fees' => $fees,\r\n                'extracurricular' => $extracurricular,\r\n                'meetings' => $meetings,\r\n                'leaves' => $leaves,\r\n                'disciplinary' => $disciplinary,\r\n                'board' => $board,\r\n                'academics' => $academics\r\n            ]);"

new_block = "'status' => 'SUCCESS',\r\n                'data' => [\r\n                    'profile'         => $student,\r\n                    'family'          => $family,\r\n                    'education'       => $education,\r\n                    'fees'            => $fees,\r\n                    'extracurricular' => $extracurricular,\r\n                    'meetings'        => $meetings,\r\n                    'leaves'          => $leaves ?? [],\r\n                    'disciplinary'    => $disciplinary ?? [],\r\n                    'board'           => $board,\r\n                    'academics'       => $academics,\r\n                    'syllabus_list'   => [],\r\n                ]\r\n            ]);"

if old_block in content:
    content = content.replace(old_block, new_block)
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print("Successfully wrapped studentViewDiary response in data key!")
else:
    print("Block not found - trying LF only")
    old_block_lf = old_block.replace("\r\n", "\n")
    if old_block_lf in content:
        new_block_lf = new_block.replace("\r\n", "\n")
        content = content.replace(old_block_lf, new_block_lf)
        with open(path, "w", encoding="utf-8") as f:
            f.write(content)
        print("Fixed with LF!")
    else:
        print("Still not found")
