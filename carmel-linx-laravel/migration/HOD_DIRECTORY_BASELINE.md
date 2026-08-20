# HOD FACULTY & USER DIRECTORY PANEL — FORENSIC BASELINE CONTRACT

**Target Panel:** `panelDirectory` (Department Registered Accounts)  
**Target View:** `resources/views/hod_dashboard.blade.php`  
**Execution Date:** August 20, 2026  
**Audit Purpose:** Complete preservation contract of all DOM elements, data fields, REST API endpoints, JavaScript handlers, and modal interfaces in the HOD Directory panel before Phase 2C.3 UI modernization.

---

## 1. Information Architecture & DOM Inventory

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│                         HOD USER DIRECTORY PANEL (#panelDirectory)               │
├──────────────────────────────────────────────────────────────────────────────────┤
│ 1. Directory Header                                                              │
│    • Title: Department Registered Accounts ({{ $activeBranch }})                 │
│    • Description: Search, audit, and manage profile lifecycle states             │
│    • Action: "Register User" (openRegisterModal())                               │
├──────────────────────────────────────────────────────────────────────────────────┤
│ 2. Filters Console                                                               │
│    • Search Input (#filterSearch): Text filter by Name, Reg No, Mobile           │
│    • Role Designation (#filterRole): Select filter by staff/student designation  │
│    • Account Status (#filterStatus): Select filter by Approved/Pending/Suspended │
│    • Search Button: Triggers loadUsers()                                         │
├──────────────────────────────────────────────────────────────────────────────────┤
│ 3. Users Data Table Container                                                    │
│    • Table Head Columns:                                                         │
│      1. Profile (Avatar, Name, Email)                                            │
│      2. Mobile / Reg No (User ID)                                                │
│      3. Branch (Branch Code Badge)                                               │
│      4. Registered Sem (Semester & Move Batch triggers for students)             │
│      5. Role Designation (Role / Designation Name)                               │
│      6. Account Status (Approved / Pending / Suspended Badge)                    │
│      7. Enrollment Status (Active / Discontinued / TC Issued Select for students)│
│      8. Actions (Approve/Suspend/Activate, Reset Pwd, Audit, Delete)             │
│    • Table Body Target: #usersTableBody                                          │
├──────────────────────────────────────────────────────────────────────────────────┤
│ 4. Associated Modals                                                             │
│    • #registerModal: Direct user profile creation (Student & Staff)              │
│    • #passwordModal: Administrative password reset modal                         │
│    • #auditModal: Profile history & audit log viewer                             │
└──────────────────────────────────────────────────────────────────────────────────┘
```

---

## 2. Exhaustive DOM ID Preservation Matrix

| DOM ID | Category | Function / Role | Preserved |
|:---|:---|:---|:---:|
| `panelDirectory` | Container | Main panel wrapper for User Directory | ✅ YES |
| `filterSearch` | Input | Search query text input | ✅ YES |
| `filterRole` | Select | Role designation filter dropdown | ✅ YES |
| `filterStatus` | Select | Account lifecycle status filter dropdown | ✅ YES |
| `usersTableBody` | Table Body | Dynamic insertion point for user table rows | ✅ YES |
| `passwordModal` | Modal | Password reset dialog container | ✅ YES |
| `pwdResetName` | Text Span | Injected user name for password reset | ✅ YES |
| `pwdResetId` | Text Span | Injected user ID for password reset | ✅ YES |
| `newPasswordInput` | Input | New password entry field | ✅ YES |
| `pwdAlert` | Alert Box | Validation feedback for password reset | ✅ YES |
| `auditModal` | Modal | Single-user audit trail modal | ✅ YES |
| `auditProfileName`| Text Span | Injected target user name for audit view | ✅ YES |
| `auditProfileId` | Text Span | Injected target user ID for audit view | ✅ YES |
| `modalAuditTableBody` | Table Body| Insertion point for profile audit log rows | ✅ YES |
| `registerModal` | Modal | Direct registration dialog container | ✅ YES |
| `directRegisterForm` | Form | Form element for user registration | ✅ YES |
| `regType` | Select | User type switcher (`student` vs `staff`) | ✅ YES |
| `directRegName` | Input | Full name input | ✅ YES |
| `directRegEmail` | Input | Email address input | ✅ YES |
| `directRegPassword` | Input | Auto-generated / custom password | ✅ YES |
| `directStudentFields` | Container | Student-specific registration fields | ✅ YES |
| `directRegAdmType` | Select | Admission type (`Regular` vs `LET`) | ✅ YES |
| `directRegStudentYear` | Input | Admission year number | ✅ YES |
| `directRegStudentId` | Input | Student Register Number | ✅ YES |
| `directRegStudentAdm` | Input | Student Admission Number | ✅ YES |
| `directRegStudentBranch` | Input | Readonly Branch Code | ✅ YES |
| `directRegStudentSem` | Select | Semester selection (`S1` - `S6`) | ✅ YES |
| `directStaffFields` | Container | Staff-specific registration fields | ✅ YES |
| `directRegStaffMobile` | Input | Staff Mobile Number (Login ID) | ✅ YES |
| `directRegStaffDesig` | Select | Staff Designation dropdown | ✅ YES |
| `directRegAlert` | Alert Box | Registration error / success message box | ✅ YES |
| `directRegSpinner` | Indicator | Loading spinner during registration AJAX | ✅ YES |

---

## 3. JavaScript Functions Preservation Matrix

| Function Name | Purpose | AJAX Endpoint / Interaction |
|:---|:---|:---|
| `loadUsers()` | Fetches filtered user directory | `GET /api/admin/users?search=...&role=...&status=...` |
| `renderUsersGrid(users)` | Renders user rows into `usersTableBody` | DOM dynamic generation |
| `changeStatus(userId, userType, newStatus)` | Toggles approval/suspension status | `POST /api/admin/user/toggle-status` |
| `editStudentSemester(regNo, currentSem)` | Updates student registered semester | `POST /api/student/update/${regNo}` |
| `editStudentBatch(regNo, currentBatch)` | Reassigns student to different classroom | `POST /api/student/update/${regNo}` |
| `updateAcademicStatusDirectly(regNo, newVal)` | Updates student enrollment status | `POST /api/student/update/${regNo}` |
| `triggerPasswordReset(userId, userType, userName)` | Opens password reset modal | Modal open (`#passwordModal`) |
| `closePasswordModal()` | Closes password reset modal | Modal close |
| `submitPasswordReset()` | Submits new password | `POST /api/admin/user/reset-password` |
| `viewUserAudit(userId, userName)` | Retrieves profile audit logs | `GET /api/audit-logs?targetId=${userId}` |
| `closeAuditModal()` | Closes audit trail modal | Modal close |
| `confirmDeleteUser(userId, userType, userName)` | Permanently removes user account | `POST /api/admin/user/delete` |
| `openRegisterModal()` | Opens direct registration modal | Modal open (`#registerModal`) |
| `closeRegisterModal()` | Closes direct registration modal | Modal close |
| `toggleDirectRegisterFields(type)` | Switches student vs staff form fields | Form dynamic show/hide |
| `handleAdmTypeChange()` | Auto-prefixes `L` for LET admission | Form field auto-update |
| `handleDirectRegister(e)` | Submits new user registration | `POST /register/student` or `/register/staff` |

---

## 4. Design System Compliance Plan
* **Color Balance**: 70% neutral (`#FFFFFF`, `#FAFAFB`), 15% primary blue (`#2563EB`), 10% semantic status (`#059669` Approved, `#E11D48` Suspended), 5% alert accents (`#D97706` Pending).
* **Typography**: Minimum $\ge 14\text{px}$ across all cells, dropdowns, buttons, and inputs. Zero text glow / shadow.
* **Layout**: Standardized Data Table card with high-contrast sticky header, smooth row hover, and responsive horizontal scroll on mobile viewports.
