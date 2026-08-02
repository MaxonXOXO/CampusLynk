<?php
// Carmel Linx User Directory & Password Inspector
// Run with: php show_users.php

$host = '127.0.0.1';
$db   = 'carmel_linx_db';
$user = 'carmel_user';
$pass = 'carmel_pass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "=========================================================================================\n";
    echo "                              CARMEL LINX USER DIRECTORY                                 \n";
    echo "=========================================================================================\n\n";

    // 1. STAFF & ADMIN PROFILES
    echo "--- STAFF, HOD & ADMIN ACCOUNTS (Table: staff_profiles) ---\n";
    $staff = $pdo->query("SELECT mobile_no, name, designation, branch, password, account_status FROM staff_profiles ORDER BY designation, name")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($staff)) {
        echo "No staff accounts found.\n";
    } else {
        printf("%-15s | %-25s | %-22s | %-10s | %-15s | %-10s\n", "Mobile / ID", "Name", "Designation", "Branch", "Password", "Status");
        echo str_repeat("-", 105) . "\n";
        foreach ($staff as $s) {
            printf("%-15s | %-25s | %-22s | %-10s | %-15s | %-10s\n", 
                $s['mobile_no'], 
                substr($s['name'], 0, 25), 
                $s['designation'], 
                $s['branch'], 
                $s['password'], 
                $s['account_status']
            );
        }
    }

    echo "\n";

    // 2. STUDENT PROFILES
    echo "--- STUDENT ACCOUNTS (Table: students) ---\n";
    $students = $pdo->query("SELECT reg_no, adm_no, name, branch, semester, password, status FROM students ORDER BY branch, reg_no")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($students)) {
        echo "No student accounts found yet. (Ready for new registrations)\n";
    } else {
        printf("%-15s | %-12s | %-25s | %-8s | %-5s | %-15s | %-10s\n", "Reg No", "Adm No", "Name", "Branch", "Sem", "Password", "Status");
        echo str_repeat("-", 100) . "\n";
        foreach ($students as $st) {
            printf("%-15s | %-12s | %-25s | %-8s | %-5s | %-15s | %-10s\n", 
                $st['reg_no'], 
                $st['adm_no'], 
                substr($st['name'], 0, 25), 
                $st['branch'], 
                "S".$st['semester'], 
                $st['password'], 
                $st['status']
            );
        }
    }

    echo "\n=========================================================================================\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
