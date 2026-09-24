<?php
$f1 = 'd:/CampusLynk/legacy-academic-platform/carmel-linx-laravel/app/Http/Controllers/ClassroomController.php';
$f2 = 'd:/CampusLynk/CampusLynk/carmel-linx-laravel/app/Http/Controllers/ClassroomController.php';

preg_match_all('/public\s+function\s+([a-zA-Z0-9_]+)/', file_get_contents($f1), $m1);
preg_match_all('/public\s+function\s+([a-zA-Z0-9_]+)/', file_get_contents($f2), $m2);

$methods1 = $m1[1];
$methods2 = $m2[1];

$diff = array_diff($methods1, $methods2);
echo "Methods in legacy but missing in CampusLynk ClassroomController:\n";
foreach ($diff as $m) {
    echo " - " . $m . "\n";
}
