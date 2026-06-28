<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

// Check activity_point_claims table
$pts = DB::table("activity_point_claims")->where("reg_no","25EL1001")->get(["id","points_claimed","points_awarded","status","semester"]);
echo "Activity claims: " . $pts->count() . "\n";
foreach ($pts as $p) {
    echo "  sem=" . $p->semester . " status=" . $p->status . " awarded=" . $p->points_awarded . " claimed=" . $p->points_claimed . "\n";
}

// Check student_semester_summary
$sum = DB::table("student_semester_summary")->where("reg_no","25EL1001")->get();
echo "\nSemester summaries: " . $sum->count() . "\n";
foreach ($sum as $s) {
    echo "  sem=" . $s->semester . " activity_points=" . $s->activity_points . "\n";
}
