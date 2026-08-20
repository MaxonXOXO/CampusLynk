# HOD DASHBOARD SHELL MIGRATION BASELINE & PRESERVATION CONTRACT

**Source Target:** `resources/views/hod_dashboard.blade.php` (4,217 lines)  
**Audit & Baseline Timestamp:** August 20, 2026  
**Migration Phase:** Phase 2C.1 — Outer Shell Migration Only  
**Preservation Standard:** 100% Zero-Loss of Business Logic, DOM Bindings, and AJAX Workflows  

---

## 1. Blade Variables & View Parameters

| Variable Name | Origin / Purpose | Preserved Value / Context |
|:---|:---|:---|
| `$activeBranch` | `$branchOverride ?? session('userBranch')` | Active Department / Branch (e.g., 'EL', 'ME', 'CE', 'CT') |
| `$isPrincipalMode` | `isset($isPrincipalView) && $isPrincipalView` | Flag indicating Principal Executive overlay viewing HOD console |

---

## 2. Master Panel Identifiers

The HOD application operates across 5 core asynchronous functional panels:

| Panel DOM ID | Purpose | Trigger Function | Active Default |
|:---|:---|:---|:---:|
| `panelBatches` | Batch Management, semester upgrade, graduation, period slot timetable | `switchPanel('batches')` | ✅ **Default (true)** |
| `panelDirectory` | Department Staff & Student user account lifecycle management | `switchPanel('directory')` | ❌ Hidden |
| `panelSubjects` | Department curriculum, subject codes, and faculty staff allocation | `switchPanel('subjects')` | ❌ Hidden |
| `panelAudit` | Department-wide chronological administrative action log | `switchPanel('audit')` | ❌ Hidden |
| `panelProfile` | Personal HOD account credentials & security logs | `switchPanel('profile')` | ❌ Hidden |

---

## 3. Master Modal Identifiers (16 Modals)

| Modal DOM ID | Purpose | Trigger Open Function | Trigger Close Function |
|:---|:---|:---|:---|
| `createBatchModal` | Create new academic batch | `openCreateBatchModal()` | `closeCreateBatchModal()` |
| `batchDetailModal` | Master batch drawer (Students, Subjects, Timetable, Tutor/Mentor) | `openBatchDetail(classroomId)` | `closeBatchDetailModal()` |
| `subjectModal` | Create / Edit Department Subject | `openSubjectModal()` / `openEditSubjectModal()` | `closeSubjectModal()` |
| `assignStaffModal` | Allocate teaching faculty to subject | `openAssignStaffModal()` | `closeAssignStaffModal()` |
| `registerModal` | Direct Student/Staff registration | `openRegisterModal()` | `closeRegisterModal()` |
| `passwordModal` | User password reset dialog | `triggerPasswordReset(userId, name)` | `closePasswordModal()` |
| `auditModal` | Single user audit trail history | `viewUserAudit(userId, name)` | `closeAuditModal()` |
| `modalSubjectsTableBody` | Table container in batch subjects modal | Dynamically loaded | N/A |
| `modalAuditTableBody` | Table container in audit modal | Dynamically loaded | N/A |
| `modalFormSubjectBatch` | Batch selector inside subject modal | Form input | N/A |
| `modalFormSubjectSemester` | Semester selector inside subject modal | Form input | N/A |
| `modalSubjectSemester` | Target semester select | Form input | N/A |
| `modalEditSubjectId` | Hidden subject ID holder for edits | Form input | N/A |
| `subjectModalTitle` | Title container of subject modal | `openSubjectModal()` | N/A |
| `subjectModalIcon` | Icon container of subject modal | `openSubjectModal()` | N/A |
| `subjectModalTitleText` | Text label of subject modal | `openSubjectModal()` | N/A |

---

## 4. Complete DOM ID Preservation Registry (145 IDs)

```
aiStatusBadge, assignMentorAlert, assignMentorSpinner, assignStaffAlert, assignStaffForm,
assignStaffModal, assignStaffSpinner, assignSubjectId, assignSubjectName, assignTutorAlert,
assignTutorSpinner, auditModal, auditProfileId, auditProfileName, auditTableBody, batchAdmYear,
batchAdmYearContainer, batchCardsGrid, batchDetailModal, batchDetailSubtitle, batchDetailTitle,
batchEmptyState, batchGlobalAlert, batchIdPreview, batchMentorContainer, batchMentorSelect,
batchRosterTableBody, batchStartSemesterContainer, batchStartSemesterSelect, batchTab_students,
batchTab_subjects, batchTab_timetable, batchTab_tutorMentor, batchTutorContainer, batchTutorSelect,
batchTypeSelect, btnCancelTimetable, btnDeleteBatch, btnEditTimetable, btnGraduateBatch,
btnHodFilterActive, btnHodFilterHistorical, btnSaveTimetable, createBatchAlert, createBatchModal,
createBatchSpinner, detailMentorSelect, detailTutorSelect, directRegAdmType, directRegAlert,
directRegEmail, directRegName, directRegPassword, directRegSpinner, directRegStaffBranch,
directRegStaffDesig, directRegStaffMobile, directRegStudentAdm, directRegStudentBranch,
directRegStudentId, directRegStudentSem, directRegStudentYear, directRegisterForm, directStaffFields,
directStudentFields, displaySubjectBatch, displaySubjectSemester, filterRole, filterSearch,
filterStatus, globalAlert, loadingIndicator, mentorCurrentDisplay, modalAuditTableBody,
modalEditSubjectId, modalFormSubjectBatch, modalFormSubjectSemester, modalSubjectSemester,
modalSubjectsTableBody, navAudit, navBatches, navDirectory, navProfile, navSubjects,
newPasswordInput, panelAudit, panelBatches, panelDirectory, panelProfile, panelSubjects,
panelTitle, passwordModal, popupAllottedHours, popupAssignmentStatus, popupCompletedHours,
popupEndSemStatus, popupMcqStatus, popupMidSemStatus, popupSubjCode, popupSubjName,
popupWrittenTestStatus, pwdAlert, pwdResetId, pwdResetName, regType, registerModal,
rosterCountBadge, semHistBtn_${s}, semHistContent, seminarNotificationsContainer,
sidebarAvatarContainer, sidebarStaffImg, staffBranchFilter, staffCheckboxList, subjectAlert,
subjectBatchSelect, subjectBatchSemRow, subjectCode, subjectCodePrefix, subjectCodeRaw,
subjectForm, subjectModal, subjectModalIcon, subjectModalTitle, subjectModalTitleText,
subjectName, subjectProgressPopup, subjectRevisionYear, subjectSemesterSelect, subjectSpinner,
subjectSubmitBtn, subjectSubmitLabel, subjectType, subjectsTableBody, tabBtn_semesterHistory,
tabBtn_students, tabBtn_subjects, tabBtn_timetable, tabBtn_tutorMentor, timetableDisplayArea,
timetableDisplayBody, timetableEditArea, timetableEditBody, tutorCurrentDisplay, usersTableBody.
```

---

## 5. Complete JavaScript Functions Registry (84 Functions)

```javascript
_applyCodePrefix(val)
_doCreateSubject(payload)
_doUpdateSubject(id, payload)
_ensureSemesterHistoryPanel(modalEl, s)
_getBranchPrefix()
_renderSemesterSnapshot(data, sem, classroomId)
assignStaff(subjectId, subjectName)
changeBatchSemesterPrompt(classroomId, currentSem)
changeStatus(userId, newStatus)
checkTodaySeminars()
closeAssignStaffModal()
closeAuditModal()
closeBatchDetailModal()
closeCreateBatchModal()
closePasswordModal()
closeRegisterModal()
closeSubjectModal()
confirmDeleteBatch(classroomId)
confirmDeleteUser(userId, name)
confirmGraduateBatch(classroomId)
deleteSubject(subjectId)
doDeleteBatch(classroomId)
doGraduateBatch(classroomId)
editStudentBatch(regNo, currentClassroomId)
editStudentSemester(regNo, currentSem)
formatStatus(status)
getHeaders()
handleAdmTypeChange()
handleDirectRegister(e)
handleStaffPhotoUpload(e)
hideSubjectProgressPopup()
loadAuditTrail()
loadBatchRoster(classroomId)
loadBatches()
loadBatchesForSubjects()
loadDeptStaffCache()
loadModalSubjects(classroomId, sem)
loadSelfSecurityLogs()
loadSemesterSnapshot(classroomId, semester)
loadSubjects()
loadTimetable(classroomId)
loadUsers()
openAssignStaffModal(subjectId, subjectName)
openAssignStaffModalFromModal(subjectId, subjectName)
openBatchDetail(classroomId)
openCreateBatchModal()
openEditSubjectModal(subjectId, subjJsonStr)
openRegisterModal()
openStudentDiary(studentId, classroomId)
openSubjectModal()
openSubjectModalFromDetail(classroomId, sem)
populateStaffDropdowns()
positionSubjectProgressPopup(triggerEl)
printTimetable(classroomId)
renderAssignStaffList(allStaff, assignedStaff)
renderBatchCard(batch)
renderPrintCell(entry)
renderTimetable(entries, allSubjects, allStaff)
renderTimetableDisplayCell(entry)
renderTimetableEditCell(day, period, entry, allSubjects, allStaff)
renderUsersGrid(users)
saveSubject(e)
showBatchMessage(type, msg)
showGlobalMessage(type, msg)
showSubjectProgressPopup(triggerEl, data)
slotsEqual(s1, s2)
submitAssignMentor()
submitAssignTutor()
submitCreateBatch(e)
submitPasswordReset(e)
submitTimetable(classroomId)
switchBatchTab(tabName)
switchBatchTabOriginalRef(tabName)
switchPanel(panelId)
syncSubjectTypeOptions(revYear, preselectedValue)
toggleBatchCreationLetView(show)
toggleDirectRegisterFields()
toggleTimetableEdit(enable)
triggerPasswordReset(userId, name)
updateAcademicStatusDirectly(regNo, status)
updateBatchPreview()
updateTimetableStaffDropdown(day, period)
viewUserAudit(userId, name)
```

---

## 6. Complete REST API Endpoints Registry (30 Endpoints)

| Method | Endpoint Pattern | Handled In JavaScript Function |
|:---|:---|:---|
| `GET` | `/api/hod/batches?status=${status}` | `loadBatches()` |
| `GET` | `/api/r26/hod/batches?status=${status}` | `loadBatches()` |
| `POST` | `/api/hod/batches` | `submitCreateBatch()` |
| `POST` | `/api/r26/hod/batches` | `submitCreateBatch()` |
| `GET` | `/api/hod/batches/${classroomId}/students` | `loadBatchRoster()` |
| `GET` | `/api/hod/batches/${classroomId}/subjects?semester=${sem}` | `loadModalSubjects()`, `loadSubjects()` |
| `GET` | `/api/hod/batches/${classroomId}/timetable` | `loadTimetable()` |
| `POST` | `/api/hod/batches/${classroomId}/timetable` | `submitTimetable()` |
| `POST` | `/api/hod/batches/${classroomId}/update-semester` | `changeBatchSemesterPrompt()` |
| `POST` | `/api/hod/batches/${classroomId}/graduate` | `doGraduateBatch()` |
| `DELETE` | `/api/hod/batches/${classroomId}` | `doDeleteBatch()` |
| `POST` | `/api/hod/batches/assign-tutor` | `submitAssignTutor()` |
| `POST` | `/api/hod/batches/assign-mentor` | `submitAssignMentor()` |
| `GET` | `/api/hod/batches/${classroomId}/semester/${semester}/snapshot` | `loadSemesterSnapshot()` |
| `GET` | `/api/hod/dept-staff` | `loadDeptStaffCache()` |
| `POST` | `/api/hod/batches/subjects/create` | `_doCreateSubject()` |
| `POST` | `/api/hod/batches/subjects/${subjectId}` | `_doUpdateSubject()` |
| `DELETE` | `/api/hod/batches/subjects/${subjectId}` | `deleteSubject()` |
| `POST` | `/api/hod/batches/subjects/${subjectId}/assign-staff` | `saveStaffAssignment()` |
| `GET` | `/api/admin/users?search=${search}&role=${role}&status=${status}` | `loadUsers()` |
| `POST` | `/api/admin/user/toggle-status` | `changeStatus()` |
| `POST` | `/api/admin/user/reset-password` | `submitPasswordReset()` |
| `POST` | `/api/admin/user/delete` | `confirmDeleteUser()` |
| `POST` | `/api/student/update/${regNo}` | `editStudentBatch()`, `editStudentSemester()`, `updateAcademicStatusDirectly()` |
| `GET` | `/api/audit-logs` | `loadAuditTrail()` |
| `GET` | `/api/audit-logs?targetId=${userId}` | `viewUserAudit()` |
| `POST` | `/api/staff/profile/upload-photo` | `handleStaffPhotoUpload()` |
| `GET` | `/api/lecturer/today-seminars` | `checkTodaySeminars()` |
| `GET` | `/api/system/ai-status` | DOMContentLoaded AI badge init |

---

## 7. Preservation Verdict

This baseline contract is locked. The shell migration will replace ONLY the outer `<html>`, `<head>`, `<body>`, legacy `<aside>`, and legacy `<header>` tags with `<x-layouts.app-shell :title="'CampusLynk - HOD Console'" :activeNav="'hod_console'">`, leaving 100% of the internal panel markup, modal definitions, DOM IDs, and all 84 JavaScript functions untouched.
