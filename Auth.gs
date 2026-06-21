// ============================================================================
// CARMEL LINX - SCALE-READY AUTHENTICATION & SESSION SERVICE (Auth.js)
// ============================================================================

/**
 * Log in a student or staff member.
 */
function loginUser(userId, password, roleType) {
  try {
    const cleanId = userId.toString().trim();
    const cleanPw = password.toString().trim();
    
    if (roleType === "student") {
      const studentsLookup = getSheetRowsAsObjects("Students_Registry_Lookup");
      const student = studentsLookup.find(s => 
        (s.Reg_No.toUpperCase() === cleanId.toUpperCase() || s.Adm_No.toUpperCase() === cleanId.toUpperCase()) && 
        s.Password === cleanPw
      );
      
      if (!student) {
        return { status: "ERROR", message: "Invalid ID/Admission Number or Password." };
      }
      
      if (student.Status && student.Status.toUpperCase() !== "APPROVED") {
        return { status: "ERROR", message: "Your registration is pending approval by your Class Tutor." };
      }
      
      // Pull full profile details from the batch spreadsheet file
      const batchStudents = getSheetRowsAsObjects("Students", student.Classroom_ID);
      const fullProfile = batchStudents.find(s => s.Reg_No.toUpperCase() === student.Reg_No.toUpperCase()) || {};
      
      return {
        status: "SUCCESS",
        role: "Student",
        id: student.Reg_No,
        admNo: student.Adm_No,
        name: fullProfile.Name || student.Name || "Student",
        email: student.Email,
        branch: fullProfile.Branch || extractBranchFromClassroom(student.Classroom_ID),
        semester: fullProfile.Semester || "S1",
        classroomId: student.Classroom_ID,
        photo: fullProfile.Photo_Drive_Link || "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150",
        sbteRegNo: fullProfile.SBTE_Reg_No || ""
      };
    } else {
      // Staff Profile
      const staff = getSheetRowsAsObjects("Staff_Profiles");
      const member = staff.find(s => {
        const mob = getRowProp(s, "Mobile_No");
        const pw = getRowProp(s, "Password");
        if (!mob || !pw) return false;
        const sheetMobile = mob.toString().replace(/[^0-9]/g, '');
        const typedMobile = cleanId.replace(/[^0-9]/g, '');
        return sheetMobile === typedMobile && pw.toString().trim() === cleanPw;
      });
      
      if (!member) {
        return { status: "ERROR", message: "Invalid Mobile Number or Password." };
      }
      
      const accountStatus = getRowProp(member, "Account_Status");
      if (accountStatus && accountStatus.toString().toUpperCase() !== "APPROVED") {
        return { status: "ERROR", message: "Your staff account is pending approval by Super Admin." };
      }
      
      const mobVal = getRowProp(member, "Mobile_No");
      const cleanMobile = mobVal.toString().replace(/[^0-9]/g, '');
      let dispName = getRowProp(member, "Name");
      if (!dispName || dispName.toString().trim() === "" || dispName.toString().trim().toLowerCase() === "null") {
        dispName = getRowProp(member, "Designation") || "Staff Member";
      }
      return {
        status: "SUCCESS",
        role: getRowProp(member, "Designation"),
        id: cleanMobile,
        name: dispName,
        email: getRowProp(member, "Email"),
        branch: getRowProp(member, "Branch"),
        designation: getRowProp(member, "Designation"),
        photo: getRowProp(member, "Photo_Drive_Link")
      };
    }
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Register a new student. Handles Regular vs Lateral Entry (LET) classroom mapping.
 */
function registerStudent(studentData) {
  try {
    const studentsLookup = getSheetRowsAsObjects("Students_Registry_Lookup");
    const regNo = studentData.regNo.trim().toUpperCase();
    const admNo = studentData.admNo.trim().toUpperCase();
    const email = studentData.email.trim();
    
    // Email domain restriction check
    const domainMatch = /@carmelpoly\.(in|edu\.in)$/i.test(email);
    if (!domainMatch) {
      return { status: "ERROR", message: "Student registration requires a college email ID (e.g., student@carmelpoly.in)." };
    }
    
    // Check duplicates in global lookup
    const duplicate = studentsLookup.find(s => s.Reg_No === regNo || s.Adm_No === admNo);
    if (duplicate) {
      return { status: "ERROR", message: "A student with this Register Number or Admission Number already exists." };
    }
    
    // Resolve Classroom ID & Admission Suffix
    const branchCode = studentData.branch.trim().toUpperCase();
    const admYear = parseInt(studentData.admissionYear.trim());
    const isLET = studentData.admissionType === "LET";
    
    // For regular 2025: start year is 2025, batch range 2025-28
    // For LET entering in 2026: they join the 2025 batch, so start year is 2025, batch range 2025-28
    const startYear = isLET ? (admYear - 1) : admYear;
    const endYear = startYear + 3;
    const classroomId = `${branchCode}_${startYear}_${endYear}`;
    
    // Write student credentials to global registry lookup sheet
    const newStudentLookup = {
      Reg_No: regNo,
      Adm_No: admNo,
      Email: email,
      Password: studentData.password.trim(),
      Classroom_ID: classroomId,
      Status: "Pending"
    };
    appendObjectToSheet("Students_Registry_Lookup", newStudentLookup);
    
    // Write full student profile into the partitioned batch database spreadsheet file
    const newStudentFull = {
      Reg_No: regNo,
      Adm_No: admNo,
      Name: studentData.name.trim(),
      Email: email,
      Password: studentData.password.trim(),
      Phone: studentData.phone || "",
      Branch: branchCode,
      Admission_Year: studentData.admissionYear.trim(),
      Admission_Type: studentData.admissionType || "Regular",
      Photo_Drive_Link: studentData.photoUrl || "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150",
      Classroom_ID: classroomId,
      Status: "Pending",
      SBTE_Reg_No: studentData.sbteRegNo || ""
    };
    
    const res = appendObjectToSheet("Students", newStudentFull, classroomId);
    if (res.status === "ERROR") {
      return res;
    }
    
    return { status: "SUCCESS", message: "Registration successful! Pending Class Tutor approval.", classroomId: classroomId };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Register a new staff member. Restricts Principal and Academic Coordinator designation counts.
 */
function registerStaff(staffData) {
  try {
    const email = staffData.email.trim();
    // Email domain restriction check
    const domainMatch = /@carmelpoly\.(in|edu\.in)$/i.test(email);
    if (!domainMatch) {
      return { status: "ERROR", message: "Staff registration requires a college email ID (e.g., user@carmelpoly.in)." };
    }
    
    const staff = getSheetRowsAsObjects("Staff_Profiles");
    const mobileNo = staffData.mobileNo.replace(/[^0-9]/g, '');
    
    // Check duplicates
    const duplicate = staff.find(s => {
      if (!s.Mobile_No) return false;
      return s.Mobile_No.toString().replace(/[^0-9]/g, '') === mobileNo;
    });
    if (duplicate) {
      return { status: "ERROR", message: "A staff profile with this mobile number already exists." };
    }
    
    const designation = staffData.designation.trim();
    
    // Enforce designations count limits
    if (designation === "Principal") {
      const hasPrincipal = staff.some(s => s.Designation === "Principal");
      if (hasPrincipal) {
        return { status: "ERROR", message: "An active or pending Principal profile already exists in the system. Only one Principal is permitted." };
      }
    }
    if (designation === "Academic_Coordinator") {
      const hasCoordinator = staff.some(s => s.Designation === "Academic_Coordinator" && s.Account_Status === "Approved");
      if (hasCoordinator) {
        return { status: "ERROR", message: "An active Academic Coordinator profile already exists in the system." };
      }
    }
    
    const newStaff = {
      Mobile_No: mobileNo,
      Name: staffData.name.trim(),
      Email: email,
      Branch: staffData.branch.trim().toUpperCase(),
      Designation: designation,
      Password: staffData.password.trim(),
      Photo_Drive_Link: staffData.photoUrl || "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150",
      Account_Status: (designation === "Principal") ? "Approved" : "Pending"
    };
    
    appendObjectToSheet("Staff_Profiles", newStaff);
    if (designation === "Principal") {
      return { status: "SUCCESS", message: "Principal registration successful! Account is auto-approved." };
    }
    return { status: "SUCCESS", message: "Staff registration submitted! Pending administrator approval." };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Admin action: approve a pending registration.
 */
function approveAccount(targetId, userType) {
  try {
    if (userType === "student") {
      // Find Classroom ID from registry lookup
      const lookupRows = getSheetRowsAsObjects("Students_Registry_Lookup");
      const target = lookupRows.find(s => s.Reg_No.toUpperCase() === targetId.toUpperCase());
      if (!target) return { status: "ERROR", message: "Student registration lookup not found." };
      
      const classroomId = target.Classroom_ID;
      
      // Update global lookup status
      updateObjectInSheet("Students_Registry_Lookup", "Reg_No", targetId, { Status: "Approved" });
      
      // Update batch-specific students roster sheet
      return updateObjectInSheet("Students", "Reg_No", targetId, { Status: "Approved" }, classroomId);
    } else {
      // Staff profile approval
      const staffRows = getSheetRowsAsObjects("Staff_Profiles");
      const target = staffRows.find(s => s.Mobile_No && s.Mobile_No.toString().trim() === targetId.toString().trim());
      if (!target) return { status: "ERROR", message: "Staff profile not found." };
      
      const designation = target.Designation;
      if (designation === "Principal") {
        const hasPrincipal = staffRows.some(s => s.Designation === "Principal" && s.Account_Status === "Approved");
        if (hasPrincipal) {
          return { status: "ERROR", message: "Another Principal has already been approved. Cannot approve multiple active Principals." };
        }
      }
      if (designation === "Academic_Coordinator") {
        const hasCoordinator = staffRows.some(s => s.Designation === "Academic_Coordinator" && s.Account_Status === "Approved");
        if (hasCoordinator) {
          return { status: "ERROR", message: "Another Academic Coordinator has already been approved. Cannot approve multiple active Coordinators." };
        }
      }
      
      return updateObjectInSheet("Staff_Profiles", "Mobile_No", targetId, { Account_Status: "Approved" });
    }
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Updates an existing student profile (Tutor or Student self-service).
 */
function updateStudentProfile(regNo, updateData) {
  try {
    const lookupRows = getSheetRowsAsObjects("Students_Registry_Lookup");
    const targetLookup = lookupRows.find(s => s.Reg_No.toUpperCase() === regNo.trim().toUpperCase());
    if (!targetLookup) return { status: "ERROR", message: "Student profile registry lookup not found." };
    
    const classroomId = targetLookup.Classroom_ID;
    
    const students = getSheetRowsAsObjects("Students", classroomId);
    const target = students.find(s => s.Reg_No.toUpperCase() === regNo.trim().toUpperCase());
    if (!target) return { status: "ERROR", message: "Student profile not found." };
    
    const fieldsToUpdate = {
      Name: updateData.Name || target.Name,
      Email: updateData.Email || target.Email,
      Password: updateData.Password || target.Password,
      Phone: updateData.Phone || target.Phone || "",
      SBTE_Reg_No: updateData.SBTE_Reg_No || target.SBTE_Reg_No || ""
    };
    
    if (updateData.Photo_Drive_Link) {
      fieldsToUpdate.Photo_Drive_Link = updateData.Photo_Drive_Link;
    }
    
    // Update global lookup credentials
    updateObjectInSheet("Students_Registry_Lookup", "Reg_No", regNo, {
      Email: fieldsToUpdate.Email,
      Password: fieldsToUpdate.Password
    });
    
    // Update batch spreadsheet record
    return updateObjectInSheet("Students", "Reg_No", regNo, fieldsToUpdate, classroomId);
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Deletes a student profile completely.
 */
function deleteStudentProfile(regNo) {
  try {
    const lookupRows = getSheetRowsAsObjects("Students_Registry_Lookup");
    const target = lookupRows.find(s => s.Reg_No.toUpperCase() === regNo.trim().toUpperCase());
    if (!target) return { status: "ERROR", message: "Student registry lookup not found." };
    
    // Delete from global lookup
    deleteObjectFromSheet("Students_Registry_Lookup", "Reg_No", regNo);
    
    // Delete from batch sheet
    return deleteObjectFromSheet("Students", "Reg_No", regNo, target.Classroom_ID);
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Change own password.
 */
function changeUserPassword(userId, roleType, currentPassword, newPassword) {
  try {
    const cleanId = userId.toString().trim();
    const cleanPw = newPassword.toString().trim();
    
    if (roleType === "student") {
      const lookupRows = getSheetRowsAsObjects("Students_Registry_Lookup");
      const profile = lookupRows.find(p => p.Reg_No.toUpperCase() === cleanId.toUpperCase());
      if (!profile || profile.Password !== currentPassword.toString().trim()) {
        return { status: "ERROR", message: "Incorrect current password." };
      }
      
      // Update global lookup
      updateObjectInSheet("Students_Registry_Lookup", "Reg_No", cleanId, { Password: cleanPw });
      
      // Update batch sheet
      return updateObjectInSheet("Students", "Reg_No", cleanId, { Password: cleanPw }, profile.Classroom_ID);
    } else {
      const profiles = getSheetRowsAsObjects("Staff_Profiles");
      const profile = profiles.find(p => p.Mobile_No && p.Mobile_No.toString().trim() === cleanId);
      if (!profile || profile.Password !== currentPassword.toString().trim()) {
        return { status: "ERROR", message: "Incorrect current password." };
      }
      return updateObjectInSheet("Staff_Profiles", "Mobile_No", cleanId, { Password: cleanPw });
    }
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Admin action: reset a student's or staff member's password.
 */
function adminResetPassword(targetId, userType, newPassword) {
  try {
    const cleanId = targetId.toString().trim();
    const cleanPw = newPassword.toString().trim();
    
    if (userType === "student") {
      const lookupRows = getSheetRowsAsObjects("Students_Registry_Lookup");
      const profile = lookupRows.find(p => p.Reg_No.toUpperCase() === cleanId.toUpperCase());
      if (!profile) return { status: "ERROR", message: "Student lookup registry record not found." };
      
      updateObjectInSheet("Students_Registry_Lookup", "Reg_No", cleanId, { Password: cleanPw });
      return updateObjectInSheet("Students", "Reg_No", cleanId, { Password: cleanPw }, profile.Classroom_ID);
    } else {
      return updateObjectInSheet("Staff_Profiles", "Mobile_No", cleanId, { Password: cleanPw });
    }
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Deregisters/Deletes a staff member from the system.
 */
function deregisterStaffMember(mobileNo) {
  try {
    return deleteObjectFromSheet("Staff_Profiles", "Mobile_No", mobileNo);
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

function updateStaffProfileSelf(mobileNo, profileData) {
  try {
    const staff = getSheetRowsAsObjects("Staff_Profiles");
    const cleanMobile = mobileNo.toString().replace(/[^0-9]/g, '');
    const target = staff.find(s => {
      const mob = getRowProp(s, "Mobile_No");
      if (!mob) return false;
      return mob.toString().replace(/[^0-9]/g, '') === cleanMobile;
    });
    if (!target) return { status: "ERROR", message: "Staff profile not found." };
    
    const exactMobileKey = getRowPropKey(target, "Mobile_No") || "Mobile_No";
    const exactMobileVal = getRowProp(target, "Mobile_No");
    
    const fieldsToUpdate = {};
    
    const nameKey = getRowPropKey(target, "Name") || "Name";
    const emailKey = getRowPropKey(target, "Email") || "Email";
    const pwKey = getRowPropKey(target, "Password") || "Password";
    const photoKey = getRowPropKey(target, "Photo_Drive_Link") || "Photo_Drive_Link";
    
    if (profileData.name && profileData.name.toString().trim() !== "" && profileData.name.toString().trim().toLowerCase() !== "null") {
      fieldsToUpdate[nameKey] = profileData.name.toString().trim();
    }
    if (profileData.email && profileData.email.toString().trim() !== "" && profileData.email.toString().trim().toLowerCase() !== "null") {
      fieldsToUpdate[emailKey] = profileData.email.toString().trim();
    }
    if (profileData.password && profileData.password.toString().trim() !== "") {
      fieldsToUpdate[pwKey] = profileData.password.toString().trim();
    }
    
    if (profileData.photoUrl) {
      fieldsToUpdate[photoKey] = profileData.photoUrl;
    }
    
    return updateObjectInSheet("Staff_Profiles", exactMobileKey, exactMobileVal, fieldsToUpdate);
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Creates a student directly and auto-approves them.
 */
function createStudentDirectly(studentData) {
  try {
    const studentsLookup = getSheetRowsAsObjects("Students_Registry_Lookup");
    const regNo = studentData.regNo.trim().toUpperCase();
    const admNo = studentData.admNo.trim().toUpperCase();
    const email = studentData.email.trim();
    
    // Email domain restriction check
    const domainMatch = /@carmelpoly\.(in|edu\.in)$/i.test(email);
    if (!domainMatch) {
      return { status: "ERROR", message: "Student registration requires a college email ID (e.g., student@carmelpoly.in)." };
    }
    
    // Check duplicates in global lookup
    const duplicate = studentsLookup.find(s => s.Reg_No === regNo || s.Adm_No === admNo);
    if (duplicate) {
      return { status: "ERROR", message: "A student with this Register Number or Admission Number already exists." };
    }
    
    // Resolve Classroom ID & Admission Suffix
    const branchCode = studentData.branch.trim().toUpperCase();
    const admYear = parseInt(studentData.admissionYear.trim());
    const isLET = studentData.admissionType === "LET";
    
    const startYear = isLET ? (admYear - 1) : admYear;
    const endYear = startYear + 3;
    const classroomId = `${branchCode}_${startYear}_${endYear}`;
    
    // Write student credentials to global registry lookup sheet with Status = "Approved"
    const newStudentLookup = {
      Reg_No: regNo,
      Adm_No: admNo,
      Email: email,
      Password: studentData.password.trim(),
      Classroom_ID: classroomId,
      Status: "Approved"
    };
    appendObjectToSheet("Students_Registry_Lookup", newStudentLookup);
    
    // Write full student profile into the partitioned batch database spreadsheet file with Status = "Approved"
    const newStudentFull = {
      Reg_No: regNo,
      Adm_No: admNo,
      Name: studentData.name.trim(),
      Email: email,
      Password: studentData.password.trim(),
      Phone: studentData.phone || "",
      Branch: branchCode,
      Admission_Year: studentData.admissionYear.trim(),
      Admission_Type: studentData.admissionType || "Regular",
      Photo_Drive_Link: studentData.photoUrl || "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150",
      Classroom_ID: classroomId,
      Status: "Approved",
      SBTE_Reg_No: studentData.sbteRegNo || "",
      Mentor_Mobile_No: ""
    };
    
    const res = appendObjectToSheet("Students", newStudentFull, classroomId);
    if (res.status === "ERROR") {
      return res;
    }
    
    return { status: "SUCCESS", message: "Student record added and auto-approved successfully!", classroomId: classroomId };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Creates a staff member directly and auto-approves them.
 */
function createStaffDirectly(staffData) {
  try {
    const email = staffData.email.trim();
    const domainMatch = /@carmelpoly\.(in|edu\.in)$/i.test(email);
    if (!domainMatch) {
      return { status: "ERROR", message: "Staff registration requires a college email ID (e.g., user@carmelpoly.in)." };
    }
    
    const staff = getSheetRowsAsObjects("Staff_Profiles");
    const mobileNo = staffData.mobileNo.replace(/[^0-9]/g, '');
    
    const duplicate = staff.find(s => {
      if (!s.Mobile_No) return false;
      return s.Mobile_No.toString().replace(/[^0-9]/g, '') === mobileNo;
    });
    if (duplicate) {
      return { status: "ERROR", message: "A staff profile with this mobile number already exists." };
    }
    
    const designation = staffData.designation.trim();
    
    // Enforce designations count limits
    if (designation === "Principal") {
      const hasPrincipal = staff.some(s => s.Designation === "Principal");
      if (hasPrincipal) {
        return { status: "ERROR", message: "An active or pending Principal profile already exists in the system. Only one Principal is permitted." };
      }
    }
    if (designation === "Academic_Coordinator") {
      const hasCoordinator = staff.some(s => s.Designation === "Academic_Coordinator" && s.Account_Status === "Approved");
      if (hasCoordinator) {
        return { status: "ERROR", message: "An active Academic Coordinator profile already exists in the system." };
      }
    }
    
    const newStaff = {
      Mobile_No: mobileNo,
      Name: staffData.name.trim(),
      Email: email,
      Branch: staffData.branch.trim().toUpperCase(),
      Designation: designation,
      Password: staffData.password.trim(),
      Photo_Drive_Link: staffData.photoUrl || "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150",
      Account_Status: "Approved"
    };
    
    appendObjectToSheet("Staff_Profiles", newStaff);
    logSystemActivity("HOD", `Directly registered & approved staff: ${newStaff.Name} (${newStaff.Designation})`);
    return { status: "SUCCESS", message: `Staff member ${newStaff.Name} created & approved successfully!` };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Log institutional and system activities to Test_Logs.
 */
function logSystemActivity(userType, details) {
  try {
    const logEntry = {
      Log_ID: "LOG_" + Date.now() + "_" + Math.floor(1000 + Math.random() * 9000),
      Reg_No: userType,       // e.g., "Admin", "Tutor", "HOD"
      Test_ID: "SYSTEM",      // Mark as a system event
      Action: "SYSTEM_ACTION",
      Timestamp: new Date().toISOString(),
      Details: details || ""
    };
    appendObjectToSheet("Test_Logs", logEntry);
  } catch (err) {
    Logger.log("Failed to log system activity: " + err.toString());
  }
}

/**
 * Case, space, and underscore insensitive property getter for sheet row objects.
 */
function getRowProp(obj, propName) {
  if (!obj || !propName) return undefined;
  if (obj[propName] !== undefined) return obj[propName];
  const cleanKey = propName.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
  for (let key in obj) {
    if (key.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase() === cleanKey) {
      return obj[key];
    }
  }
  return undefined;
}

/**
 * Get the exact database row object property key matching propName (casing and spaces).
 */
function getRowPropKey(obj, propName) {
  if (!obj || !propName) return null;
  if (obj[propName] !== undefined) return propName;
  const cleanKey = propName.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
  for (let key in obj) {
    if (key.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase() === cleanKey) {
      return key;
    }
  }
  return null;
}
