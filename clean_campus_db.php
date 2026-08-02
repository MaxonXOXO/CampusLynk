<?php
// Local Campus Server Database Cleaner
// Runs strictly on local MariaDB instance carmel_linx_db

$host = '127.0.0.1';
$db   = 'carmel_linx_db';
$user = 'carmel_user';
$pass = 'carmel_pass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connected to local database: $db\n";

    // Disable foreign key checks for clean truncates
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // System tables to EXCLUDE from truncation
    $excluded_tables = [
        'migrations',
        'syllabus_registry',
        'system_settings',
        'staff_profiles'
    ];

    // Fetch all tables from DB
    $stmt = $pdo->query("SHOW TABLES");
    $all_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Truncating transactional and operational tables...\n";
    $truncated_count = 0;
    foreach ($all_tables as $table) {
        if (!in_array($table, $excluded_tables)) {
            $pdo->exec("TRUNCATE TABLE `$table`;");
            $truncated_count++;
        }
    }
    echo " - Truncated $truncated_count tables successfully.\n";

    // Clean staff_profiles: Keep ONLY Super_Admin, Principal, Admin, and Academic_Coordinator
    echo "Cleaning staff_profiles (Keeping Super_Admin, Principal, Academic_Coordinator, and Admin)...\n";
    $allowed_designations = ['Super_Admin', 'Principal', 'Admin', 'Academic_Coordinator', 'Academic Coordinator'];
    $in_clause = "'" . implode("','", $allowed_designations) . "'";
    
    $stmt = $pdo->prepare("DELETE FROM staff_profiles WHERE designation NOT IN ($in_clause);");
    $stmt->execute();
    $deleted_staff = $stmt->rowCount();
    echo " - Removed non-admin profiles ($deleted_staff rows).\n";

    // Ensure 'Admin' account exists if missing
    $has_admin = $pdo->query("SELECT COUNT(*) FROM staff_profiles WHERE designation = 'Admin'")->fetchColumn();
    if (!$has_admin) {
        $stmt_ins = $pdo->prepare("INSERT INTO staff_profiles (mobile_no, name, email, branch, designation, password, account_status, created_at, updated_at) VALUES ('9000000002', 'System Admin', 'sysadmin@carmelpoly.in', 'Administration', 'Admin', 'admin123', 'Approved', NOW(), NOW())");
        $stmt_ins->execute();
        echo " - Created default Admin profile (Mobile: 9000000002).\n";
    }

    // Ensure all 4 admin accounts have password set to 'admin123'
    $update_stmt = $pdo->prepare("UPDATE staff_profiles SET password = 'admin123', account_status = 'Approved' WHERE designation IN ($in_clause);");
    $update_stmt->execute();
    echo " - Ensured active status & passwords for Super_Admin, Principal, Academic_Coordinator, and Admin.\n";

    // Enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

    echo "\n✅ LOCAL CAMPUS DATABASE CLEANUP & INITIALIZATION COMPLETE!\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}

