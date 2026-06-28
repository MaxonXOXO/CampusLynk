<?php

$content = file_get_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\app\\Http\\Controllers\\MentoringController.php");

$method = '

    public function studentSaveExtraCurricular(Request $request)
    {
        $regNo = Session::get(\'userId\');
        $role  = Session::get(\'userRole\');
        if ($role !== \'Student\' || !$regNo) {
            return response()->json([\'status\' => \'ERROR\', \'message\' => \'Not authenticated as student.\'], 403);
        }

        $request->validate([
            \'segment\' => \'required|string\',
            \'activity_name\'    => \'required|string\',
            \'level\'            => \'required|string\',
            \'points_claimed\'   => \'required|integer\'
        ]);

        $data = [
            \'reg_no\'           => strtoupper($regNo),
            \'activity_segment\' => $request->segment,
            \'activity_name\'    => $request->activity_name,
            \'level\'            => $request->level,
            \'points_claimed\'   => $request->points_claimed,
            \'status\'           => \'Pending\',
        ];

        if ($request->has(\'activity_id\') && !empty($request->activity_id)) {
            \App\Models\ActivityPointClaim::where(\'id\', $request->activity_id)
                ->where(\'reg_no\', strtoupper($regNo))
                ->update($data);
            $msg = \'Activity updated successfully.\';
        } else {
            \App\Models\ActivityPointClaim::create($data);
            $msg = \'Activity submitted for verification.\';
        }

        return response()->json([\'status\' => \'SUCCESS\', \'message\' => $msg]);
    }
';

$content = str_replace('public function studentViewDiary()', $method . "\n    public function studentViewDiary()", $content);

file_put_contents("c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\app\\Http\\Controllers\\MentoringController.php", $content);
echo "Added studentSaveExtraCurricular\n";
