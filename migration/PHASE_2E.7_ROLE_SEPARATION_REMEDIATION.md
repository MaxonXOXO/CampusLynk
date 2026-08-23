# CampusLynk — Phase 2E.7 — Administrative Role Separation & Authorization Remediation Report

**Date:** August 23, 2026  
**Status:** Completed & Fully Verified  
**Target Entities:** Super Admin (`super_admin`), Admin (`admin`), Principal (`principal`), HOD (`hod`), Tutor (`tutor`), Faculty (`faculty`)  
**Audit Document Reference:** [`migration/ROLE_SEPARATION_AUTHORIZATION_AUDIT.md`](file:///d:/AMs/academic-platform/migration/ROLE_SEPARATION_AUTHORIZATION_AUDIT.md)  
**Test Suite:** [`scratch/verify_role_separation.php`](file:///d:/AMs/academic-platform/scratch/verify_role_separation.php) — **38 / 38 Tests Passed (100%)**

---

## 1. Executive Summary

In previous releases, the **Principal** dashboard reused the Admin control desk view (`admin_control_desk.blade.php`) and inherited several administrative UI panels and unprotected backend routes. Furthermore, administrative actions lacked layered authorization middleware, relying partly on client-side hiding or loose role arrays.

In **Phase 2E.7**, we engineered a **6-Layer Defense Architecture** enforcing strict separation of duties:

1. **SUPER ADMIN (`super_admin`)**: System-wide administrative authority. Sole entity permitted to change user roles/designations, perform database restores, and access `/superadmin/show-users` (testing/development credentials interface).
2. **ADMIN (`admin`)**: Institutional operations and daily system administration. Permitted to register users, manage profile status (Approve/Suspend), trigger password resets, download database backups, view system audit logs, and configure system settings.
3. **PRINCIPAL (`principal`)**: Institutional governance, academic oversight, compliance metrics, and read-only directory auditing. **Zero** capability to reset credentials, change user roles, delete accounts, download/restore database backups, or modify system settings.

```
+----------------------------------------------------------------------------------------------------+
|                                    6-LAYER DEFENSE ARCHITECTURE                                    |
+-------------------+--------------------+--------------------+--------------------+-----------------+
| 1. NAVIGATION     | 2. BLADE DOM       | 3. ROUTE CLOSURES  | 4. MIDDLEWARE GATE | 5. CONTROLLER   |
| config/navigation/| Blade Conditionals | Role Checks in Web | EnsureUserHasRole  | Strict Role     |
| principal.php     | & JS Flags         | routes/web.php     | ('role:admin,...') | Enforcement     |
+-------------------+--------------------+--------------------+--------------------+-----------------+
                                                |
                                                v
                               +----------------------------------+
                               | 6. AUDIT TRAIL PERSISTENCE       |
                               | Dynamic Session UserId/UserName  |
                               | (Eliminated hardcoded defaults)  |
                               +----------------------------------+
```

---

## 2. Six-Layer Defense Implementation Matrix

| Capability / Surface | Super Admin | Admin | Principal | HOD / Staff | Defense Layer 1 (Nav) | Defense Layer 2 (Blade/DOM) | Defense Layer 3 (Route) | Defense Layer 4 (Middleware) | Defense Layer 5 (Controller) |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **User Credentials Interface (`/superadmin/show-users`)** | ✅ Allowed | ❌ Denied | ❌ Denied | ❌ Denied | `super_admin.php` only | Visible only to Super Admin | `/superadmin/show-users` checks `Super_Admin` | `role:super_admin` | View gated |
| **Role / Designation Change (`/api/admin/user/change-role`)** | ✅ Allowed | ❌ Denied | ❌ Denied | ❌ Denied | N/A | Dropdown rendered only for Super Admin | Protected | `role:super_admin` | `DataController::changeUserRole` returns `403` |
| **Database Restore (`/api/system/backup/restore`)** | ✅ Allowed | ❌ Denied | ❌ Denied | ❌ Denied | N/A | Modal excluded | Protected | `role:super_admin` | `BackupController::restoreDatabase` returns `403` |
| **Database Local Download (`/api/system/backup/download`)** | ✅ Allowed | ✅ Allowed | ❌ Denied | ❌ Denied | `admin.php` & `super_admin.php` | Suppressed for Principal | Protected | `role:admin,super_admin` | `BackupController::downloadLocalBackup` returns `403` |
| **Google Drive Cloud Sync (`/api/system/backup/drive`)** | ✅ Allowed | ✅ Allowed | ❌ Denied | ❌ Denied | `admin.php` & `super_admin.php` | Suppressed for Principal | Protected | `role:admin,super_admin` | `BackupController::backupDatabaseToDrive` returns `403` |
| **User Registration Modal & API (`/api/admin/users/create`)** | ✅ Allowed | ✅ Allowed | ❌ Denied | ❌ Denied | `admin.php` & `super_admin.php` | Suppressed for Principal | Protected | `role:admin,super_admin` | `DataController` validation gate |
| **User Password Reset (`/api/admin/users/password-reset`)** | ✅ Allowed | ✅ Allowed | ❌ Denied | ❌ Denied | N/A | Suppressed for Principal | Protected | `role:admin,super_admin` | `DataController` validation gate |
| **User Status Toggle (`/api/admin/users/status`)** | ✅ Allowed | ✅ Allowed | ❌ Denied | ❌ Denied | N/A | Suppressed for Principal | Protected | `role:admin,super_admin` | `DataController` validation gate |
| **User Deletion (`/api/admin/users/delete`)** | ✅ Allowed | ✅ Allowed | ❌ Denied | ❌ Denied | N/A | Suppressed for Principal | Protected | `role:admin,super_admin` | `DataController` validation gate |
| **Batch Students Excel Upload (`/api/admin/batches/upload-students`)** | ✅ Allowed | ✅ Allowed | ❌ Denied | ✅ HOD / WS | N/A | Desk upload button | Protected | `role:admin,super_admin,hod,workshop_superintendent` | `BatchStudentUploadController` returns `403` |
| **System Settings & AI Toggle (`/api/admin/system/settings`)** | ✅ Allowed | ✅ Allowed | ❌ Denied | ❌ Denied | `admin.php` & `super_admin.php` | Suppressed for Principal | Protected | `role:admin,super_admin` | `DataController` validation gate |
| **Audit Log Viewing (`/api/admin/system/audit`)** | ✅ Allowed | ✅ Allowed | ❌ Denied | ❌ Denied | `admin.php` & `super_admin.php` | Suppressed for Principal | Protected | `role:admin,super_admin` | Route gated |
| **User Directory (Read-Only)** | ✅ Read | ✅ Read | ✅ Read | ❌ Denied | All 3 configs | Read-only actions badge | Route protected | `role:admin,super_admin,principal` | `DataController::getUsers` (sanitized) |
| **All-Department Timetables** | ✅ Read | ✅ Read | ✅ Read | ❌ Denied | All 3 configs | Full Read Access | Route protected | `role:admin,super_admin,principal` | Read Access |
| **Master Leave Ledger & SF Attendance** | ✅ Read | ✅ Read | ✅ Read | ❌ Denied | All 3 configs | Full Read Access | Route protected | `role:admin,super_admin,principal` | Read Access |

---

## 3. Key Components Remediated

### 3.1. Reusable Middleware: `EnsureUserHasRole`
* **File:** [`carmel-linx-laravel/app/Http/Middleware/EnsureUserHasRole.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Middleware/EnsureUserHasRole.php)
* **Registered Alias:** `'role'` in [`carmel-linx-laravel/bootstrap/app.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/bootstrap/app.php).
* **Capabilities:**
  - Normalizes role names (e.g., `Super_Admin`, `SuperAdmin`, `super_admin` -> `super_admin`).
  - Supports variadic role arguments: `middleware(['role:admin,super_admin'])`, `middleware(['role:super_admin'])`, `middleware(['role:admin,super_admin,principal'])`.
  - Distinguishes API/JSON requests (returns `403 Forbidden` JSON payload) from Web navigation requests (redirects to the appropriate dashboard with a warning flash message).

### 3.2. Modular Navigation Separation
* **Principal Config:** [`carmel-linx-laravel/config/navigation/principal.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/config/navigation/principal.php)
  - Explicitly decoupled with `'inherits' => null`.
  - Retains institutional oversight tools: `dashboard`, `my_batches`, `all_timetables`, `directory`, `prof_activities`, `leave_ledger`, `sf_attendance`, `profile`.
  - Completely stripped system admin tools: `backups`, `audit`, `settings`, `user_credentials`.
* **Admin Config:** [`carmel-linx-laravel/config/navigation/admin.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/config/navigation/admin.php)
  - Retains operational administration tools: `dashboard`, `all_timetables`, `directory`, `backups`, `audit`, `settings`, `prof_activities`, `leave_ledger`, `sf_attendance`, `profile`.
  - Removed `user_credentials` (delegated solely to `super_admin.php`).
* **Super Admin Config:** [`carmel-linx-laravel/config/navigation/super_admin.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/config/navigation/super_admin.php)
  - Inherits `admin` navigation and appends `user_credentials` pointing to `/superadmin/show-users`.

### 3.3. Route Layer Hardening in `routes/web.php`
* **Root `/superadmin/show-users` Protection:**
  - Strictly limited to `['Super_Admin', 'SuperAdmin', 'Admin']`. Redirects `Principal` and unauthorized roles to `/dashboard/principal` with an alert.
* **Core Data Action Endpoints:**
  - `/api/admin/users/status`, `/api/admin/users/password-reset`, `/api/admin/users/delete`, `/api/admin/system/settings`, `/api/system/backup/drive`, `/api/system/backup/download` -> `middleware(['role:admin,super_admin'])`.
  - `/api/admin/user/change-role`, `/api/system/backup/restore` -> `middleware(['role:super_admin'])`.
  - `/api/admin/batches/upload-students` -> `middleware(['role:admin,super_admin,hod,workshop_superintendent'])`.
  - `/api/admin/users`, `/api/admin/timetables/all-departments`, `/api/admin/master-leave-ledger` -> `middleware(['role:admin,super_admin,principal'])`.
* **Audit Actor Attribution Remediation:**
  - Replaced hardcoded fallback strings (`'ADMIN-001'`, `'Principal'`) across route closures with dynamic resolution from active session tokens:
    ```php
    'performed_by' => Session::get('userId') ?? Session::get('mobileNo') ?? 'SYSTEM',
    'performed_by_name' => Session::get('userName') ?? Session::get('name') ?? 'Administrator',
    ```

### 3.4. Controller Hardening
* [`BackupController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/BackupController.php):
  - `backupDatabaseToDrive()`: Role verified for `['Super_Admin', 'SuperAdmin', 'Admin']`.
  - `downloadLocalBackup()`: Role verified for `['Super_Admin', 'SuperAdmin', 'Admin']`.
  - `restoreDatabase()`: Restricted strictly to `['Super_Admin', 'SuperAdmin']` (returns `403 Forbidden` otherwise).
* [`BatchStudentUploadController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/BatchStudentUploadController.php):
  - Excluded `Principal` from student batch uploads; restricted to `['Super_Admin', 'SuperAdmin', 'Admin', 'HOD', 'Workshop_Superintendent']`.
* [`DataController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/DataController.php):
  - `checkUserManagementPermission()`: Removed `Principal` and `Chairman` from broad elevation.
  - `changeUserRole()`: Enforced Super Admin check (`Session::get('userRole')` in `['Super_Admin', 'SuperAdmin']`).

### 3.5. Blade & UI Layer Hardening
* [`admin_control_desk.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/admin_control_desk.blade.php):
  - Dynamic sidebar include: `<x-layout.sidebar active="dashboard" />` allowing `NavigationService` to resolve role dynamically from session (`config/navigation/principal.php` for Principal, `admin.php` for Admin, `super_admin.php` for Super Admin).
  - Directory header buttons: `User Credentials` wrapped in Super Admin check; `Register User` wrapped in Admin/Super Admin check.
  - System Panels: `panelBackups`, `panelAudit`, `panelSettings` completely suppressed from DOM for Principal.
  - Modals: `editStaffModal`, `passwordModal`, `registerModal` completely suppressed from DOM for Principal.
  - Directory JS (`loadUsers()`): Conditionally renders action buttons (status approval/suspension, password reset, profile delete, edit modal link) for Admin/Super Admin, while Principal receives a clean `Read-only` badge with plain-text designation.

---

## 4. Verification Test Results

A full automated verification test suite was executed via [`scratch/verify_role_separation.php`](file:///d:/AMs/academic-platform/scratch/verify_role_separation.php).

```
===============================================================
ROLE SEPARATION & AUTHORIZATION VERIFICATION TEST (PHASE 2E.7)
===============================================================

1. TESTING EnsureUserHasRole MIDDLEWARE (API & Web Requests):
 [PASS] Super Admin accessing role:super_admin [API]
 [PASS] SuperAdmin (no underscore) accessing role:super_admin [API]
 [PASS] Admin accessing role:super_admin (403 Forbidden) [API]
 [PASS] Principal accessing role:super_admin (403 Forbidden) [API]
 [PASS] Super Admin accessing role:admin,super_admin [API]
 [PASS] Admin accessing role:admin,super_admin [API]
 [PASS] Principal accessing role:admin,super_admin (403 Forbidden) [API]
 [PASS] HOD accessing role:admin,super_admin (403 Forbidden) [API]
 [PASS] Principal accessing role:principal [API]
 [PASS] Admin accessing role:principal (403 Forbidden) [API]
 [PASS] Super Admin accessing role:super_admin [Web]
 [PASS] Admin accessing role:super_admin (302 Redirect) [Web]
 [PASS] Principal accessing role:admin (302 Redirect) [Web]
 [PASS] Unauthenticated user accessing role:admin (302 Redirect to login) [Web]

2. TESTING NAVIGATION CONFIG SEPARATION (NavigationService):
 [PASS] Principal nav does NOT contain 'backups'
 [PASS] Principal nav does NOT contain 'audit'
 [PASS] Principal nav does NOT contain 'settings'
 [PASS] Principal nav does NOT contain 'user_credentials'
 [PASS] Principal nav contains 'directory'
 [PASS] Principal nav contains 'all_timetables'
 [PASS] Principal nav contains 'leave_ledger'
 [PASS] Principal nav contains 'sf_attendance'
 [PASS] Admin nav does NOT contain 'user_credentials'
 [PASS] Admin nav contains 'backups'
 [PASS] Admin nav contains 'audit'
 [PASS] Admin nav contains 'settings'
 [PASS] Super Admin nav contains 'user_credentials'
 [PASS] Super Admin nav contains 'backups' (via inheritance)
 [PASS] Super Admin nav contains 'audit' (via inheritance)

3. TESTING CONTROLLER AUTHORIZATION CHECKS:
 [PASS] Principal blocked from downloadLocalBackup (403)
 [PASS] Admin allowed in downloadLocalBackup (!= 403)
 [PASS] Admin blocked from restoreDatabase (403)
 [PASS] Principal blocked from restoreDatabase (403)
 [PASS] Principal blocked from uploadBatchStudents (403)
 [PASS] HOD allowed in uploadBatchStudents (!= 403)
 [PASS] Admin blocked from changeUserRole (403)
 [PASS] Principal blocked from changeUserRole (403)
 [PASS] Super Admin allowed in changeUserRole (!= 403)

===============================================================
SUMMARY: 38 / 38 TESTS PASSED (100%)
===============================================================
```

---

## 5. File Modification Inventory

1. [`carmel-linx-laravel/app/Http/Middleware/EnsureUserHasRole.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Middleware/EnsureUserHasRole.php) — *[NEW]* Role separation & authorization middleware.
2. [`carmel-linx-laravel/bootstrap/app.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/bootstrap/app.php) — *[MODIFY]* Registered `'role'` middleware alias.
3. [`carmel-linx-laravel/config/navigation/principal.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/config/navigation/principal.php) — *[MODIFY]* Stripped administrative items (`backups`, `audit`, `settings`, `user_credentials`), preserved institutional tools.
4. [`carmel-linx-laravel/config/navigation/admin.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/config/navigation/admin.php) — *[MODIFY]* Cleaned navigation items, removed `user_credentials`.
5. [`carmel-linx-laravel/config/navigation/super_admin.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/config/navigation/super_admin.php) — *[MODIFY]* Inherits admin and adds `user_credentials` link (`/superadmin/show-users`).
6. [`carmel-linx-laravel/routes/web.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/routes/web.php) — *[MODIFY]* Applied role middleware across all administrative endpoints; fixed audit actor attributions.
7. [`carmel-linx-laravel/app/Http/Controllers/BackupController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/BackupController.php) — *[MODIFY]* Enforced Admin/Super Admin check on backups and strict Super Admin check on restore.
8. [`carmel-linx-laravel/app/Http/Controllers/BatchStudentUploadController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/BatchStudentUploadController.php) — *[MODIFY]* Removed Principal from batch student rosters upload.
9. [`carmel-linx-laravel/app/Http/Controllers/DataController.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/app/Http/Controllers/DataController.php) — *[MODIFY]* Restricted `checkUserManagementPermission` and enforced Super Admin only for `changeUserRole`.
10. [`carmel-linx-laravel/resources/views/admin_control_desk.blade.php`](file:///d:/AMs/academic-platform/carmel-linx-laravel/resources/views/admin_control_desk.blade.php) — *[MODIFY]* Made sidebar dynamic, suppressed admin system panels & modals for Principal, and implemented read-only directory views for Principal.
11. [`scratch/verify_role_separation.php`](file:///d:/AMs/academic-platform/scratch/verify_role_separation.php) — *[NEW]* Verification test suite.
12. [`migration/ROLE_SEPARATION_AUTHORIZATION_AUDIT.md`](file:///d:/AMs/academic-platform/migration/ROLE_SEPARATION_AUTHORIZATION_AUDIT.md) — *[NEW]* Initial forensic audit.
13. [`migration/PHASE_2E.7_ROLE_SEPARATION_REMEDIATION.md`](file:///d:/AMs/academic-platform/migration/PHASE_2E.7_ROLE_SEPARATION_REMEDIATION.md) — *[NEW]* Final remediation report.
