<?php

$filePath = "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\app\\Http\\Controllers\\MentoringController.php";
$content = file_get_contents($filePath);

// Patch studentSaveExtraCurricular
$old1 = "        \$request->validate([
            'segment' => 'required|string',
            'activity_name'    => 'required|string',
            'level'            => 'required|string',
            'points_claimed'   => 'required|integer'
        ]);

        \$data = [
            'reg_no'           => strtoupper(\$regNo),
            'activity_segment' => \$request->segment,
            'activity_name'    => \$request->activity_name,
            'level'            => \$request->level,
            'points_claimed'   => \$request->points_claimed,
            'status'           => 'Pending',
        ];";

$new1 = "        \$request->validate([
            'semester'         => 'required|integer',
            'segment'          => 'required|string',
            'activity_name'    => 'required|string',
            'level'            => 'required|string',
            'points_claimed'   => 'required|integer'
        ]);

        \$data = [
            'reg_no'           => strtoupper(\$regNo),
            'semester'         => \$request->semester,
            'activity_segment' => \$request->segment,
            'activity_name'    => \$request->activity_name,
            'level'            => \$request->level,
            'points_claimed'   => \$request->points_claimed,
            'status'           => 'Pending',
        ];";
        
$content = str_replace($old1, $new1, $content);

// Patch saveExtraCurricular (mentor save)
$old2 = "        \$request->validate([
            'reg_no'           => 'required|string',
            'activity_segment' => 'required|string',
            'activity_name'    => 'required|string',
            'level'            => 'required|string',
            'points_claimed'   => 'required|integer',
            'points_awarded'   => 'required|integer',
            'status'           => 'required|string'
        ]);

        \$data = [
            'reg_no'           => strtoupper(\$request->reg_no),
            'activity_segment' => \$request->activity_segment,
            'activity_name'    => \$request->activity_name,
            'level'            => \$request->level,
            'points_claimed'   => \$request->points_claimed,
            'points_awarded'   => \$request->points_awarded,
            'status'           => \$request->status,
            'verified_by'      => \$mobileNo,
        ];";

$new2 = "        \$request->validate([
            'reg_no'           => 'required|string',
            'semester'         => 'required|integer',
            'activity_segment' => 'required|string',
            'activity_name'    => 'required|string',
            'level'            => 'required|string',
            'points_claimed'   => 'required|integer',
            'points_awarded'   => 'required|integer',
            'status'           => 'required|string'
        ]);

        \$data = [
            'reg_no'           => strtoupper(\$request->reg_no),
            'semester'         => \$request->semester,
            'activity_segment' => \$request->activity_segment,
            'activity_name'    => \$request->activity_name,
            'level'            => \$request->level,
            'points_claimed'   => \$request->points_claimed,
            'points_awarded'   => \$request->points_awarded,
            'status'           => \$request->status,
            'verified_by'      => \$mobileNo,
        ];";

$content = str_replace($old2, $new2, $content);

file_put_contents($filePath, $content);
echo "Updated MentoringController with semester logic.\n";
