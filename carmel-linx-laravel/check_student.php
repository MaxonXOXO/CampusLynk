<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

use App\Models\Student;
use Illuminate\Support\Facades\DB;

// Check the student record
$s = Student::where('reg_no', '25EL1001')->first();
if ($s) {
    echo "=== STUDENT FOUND ===\n";
    echo "Name:       " . $s->name . "\n";
    echo "Reg No:     " . $s->reg_no . "\n";
    echo "Status:     " . $s->status . "\n";
    echo "Password:   " . $s->password . "\n";
    echo "Branch:     " . $s->branch . "\n";
    echo "Classroom:  " . $s->classroom_id . "\n";
} else {
    echo "STUDENT NOT FOUND - 25EL1001 does not exist in the students table.\n";
    echo "\nAll students in DB:\n";
    $all = Student::select('reg_no','name','status','password')->get();
    foreach ($all as $st) {
        echo "  {$st->reg_no} | {$st->name} | status: {$st->status} | pwd: {$st->password}\n";
    }
}

// Also check the route for /dashboard/student
echo "\n=== students table columns ===\n";
$cols = DB::select('DESCRIBE students');
foreach ($cols as $c) {
    echo "  {$c->Field} ({$c->Type}) - Default: {$c->Default}\n";
}
