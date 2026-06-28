<?php
$request = Illuminate\Http\Request::create('/api/admin/users?role=student', 'GET');
session()->put('userId', '9100000001');
session()->put('userRole', 'Lecturer');
session()->put('userBranch', 'EL');
$controller = new \App\Http\Controllers\DataController();
echo json_encode($controller->getUsersList($request)->getData());
