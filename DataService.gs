// ============================================================================
// CARMEL LINX - SCALE-READY RELATIONAL DATABASE ACCESS LAYER (DataService.js)
// ============================================================================

// Schema Definitions for Global Tables (Active Spreadsheet)
const GLOBAL_SHEETS = {
  "Staff_Profiles": ["Mobile_No", "Name", "Email", "Branch", "Designation", "Password", "Photo_Drive_Link", "Account_Status"],
  "Class_Management": ["Classroom_ID", "Branch", "Batch_Year", "Tutor_Mobile_No", "Mentor_Mobile_No"],
  "Database_Registry": ["Registry_Key", "Spreadsheet_ID", "Folder_ID"],
  "PO_Config": ["PO_ID", "PO_Name", "Description"],
  "Branch_Config": ["Branch_Code", "Vision", "Mission", "PEOs", "PSOs"],
  "Institution_Config": ["Config_Key", "Config_Value"],
  "Staff_Branch_Assignment": ["Assignment_ID", "Staff_Mobile", "Branch_Code"],
  "Students_Registry_Lookup": ["Reg_No", "Adm_No", "Email", "Password", "Classroom_ID", "Status"]
};

// Schema Definitions for Cumulative Branch Tables (Branch Question Bank Spreadsheet)
const BRANCH_SHEETS = {
  "Question_Bank": ["Subject_Code", "Question_ID", "Type", "Question_Text", "Options", "Correct_Answer", "CO_Tag", "Marks"],
  "Syllabus_Registry": ["Subject_Code", "Revision_Year", "Subject_Name", "CO_Count"],
  "Model_Exams_Configs": ["Exam_ID", "Subject_Code", "Exam_Name", "Config_JSON"]
};

// Schema Definitions for Batch-Specific Tables (1 Spreadsheet per Batch)
const BATCH_SHEETS = {
  "Students": ["Reg_No", "Adm_No", "Name", "Email", "Password", "Phone", "Branch", "Admission_Year", "Admission_Type", "Photo_Drive_Link", "Classroom_ID", "Status", "SBTE_Reg_No", "Mentor_Mobile_No"],
  "Subject_Faculty_Mapping": ["Mapping_ID", "Classroom_ID", "Subject_Code", "Subject_Name", "Faculty_Mobile_No", "Course_Type", "Attainment_Threshold"],
  "Attendance_Logs": ["Log_ID", "Reg_No", "Subject_Code", "Date", "Hour", "Status", "Logged_By"],
  "Class_Logs": ["Log_ID", "Subject_Code", "Date", "Hour", "Topic_Covered", "Remarks", "Logged_By"],
  "Lesson_Plans": ["Plan_ID", "Subject_Code", "Unit_No", "Topic", "Planned_Hours", "Status"],
  "Academic_Marks": ["Mark_ID", "Reg_No", "Subject_Code", "Category", "CO_Tag", "Max_Marks", "Marks_Obtained", "Entered_By", "Timestamp"],
  "Test_Config": ["Test_ID", "Subject_Code", "Classroom_ID", "Test_Name", "Start_Time", "End_Time", "Duration", "Selected_COs", "MCQ_Count", "Descriptive_Count", "Target_Percentage", "Pass_Threshold", "Is_Active"],
  "Student_Responses": ["Response_ID", "Reg_No", "Test_ID", "Question_ID", "Selected_Option", "Descriptive_Text", "Marks_Obtained", "Evaluated_By", "Status"],
  "Tutor_Diary": ["Diary_ID", "Reg_No", "Date", "Category", "Discussion_Notes", "Action_Taken", "Remarks", "Logged_By"],
  "Extracurricular_Activities": ["Activity_ID", "Reg_No", "Club", "Activity_Name", "Participation_Details", "Points", "Approved_By", "Timestamp"],
  "Placements_&_Training": ["Record_ID", "Reg_No", "Type", "Company_Or_Institution", "Details", "Date", "Status"],
  "Surveys_&_Feedback": ["Survey_ID", "Reg_No", "Subject_Code", "Survey_Type", "Responses_JSON", "Timestamp"]
};

/**
 * Robust folder lookup/creation that safely falls back to a chainable mock object in simulator sandbox.
 */
function getDriveFolder(parentFolder, folderName) {
  try {
    let folderIterator = parentFolder ? parentFolder.getFoldersByName(folderName) : DriveApp.getFoldersByName(folderName);
    if (folderIterator && folderIterator.hasNext()) {
      return folderIterator.next();
    }
    return parentFolder ? parentFolder.createFolder(folderName) : DriveApp.createFolder(folderName);
  } catch (err) {
    Logger.log("Drive folder access mocked/fallback used: " + err.toString());
    return {
      getFoldersByName: () => ({ hasNext: () => false }),
      createFolder: (name) => getDriveFolder(null, name),
      getId: () => "MOCK_FOLDER_ID_" + folderName.toUpperCase(),
      addFile: () => {},
      removeFile: () => {}
    };
  }
}

/**
 * Dynamically resolves the shared Cumulative Branch Question Bank spreadsheet.
 */
function getBranchQuestionBankId(branchCode) {
  try {
    const cleanBranch = branchCode.toString().trim().toUpperCase();
    const registryKey = cleanBranch + "_QB";
    
    // Check Registry first
    const ss = SpreadsheetApp.getActiveSpreadsheet();
    const regSheet = ss.getSheetByName("Database_Registry");
    if (regSheet) {
      const data = regSheet.getDataRange().getValues();
      for (let i = 1; i < data.length; i++) {
        if (data[i][0] && data[i][0].toString().trim().toUpperCase() === registryKey) {
          return data[i][1];
        }
      }
    }
    
    // Initialize a new branch question bank
    let mainFolder = getDriveFolder(null, "Carmel Linx Exam Portal Files");
    let branchFolder = getDriveFolder(mainFolder, cleanBranch);
    
    let qbFileId = "";
    try {
      let qbSs = SpreadsheetApp.create(cleanBranch + "_Question_Bank");
      let file = DriveApp.getFileById(qbSs.getId());
      branchFolder.addFile(file);
      try { DriveApp.getRootFolder().removeFile(file); } catch(e) {}
      qbFileId = qbSs.getId();
    } catch(e) {
      // Simulator/Mock fallback
      qbFileId = SpreadsheetApp.getActiveSpreadsheet().getId();
    }
    
    // Register sheets inside the QB spreadsheet
    const targetSs = SpreadsheetApp.openById(qbFileId);
    for (let sheetName in BRANCH_SHEETS) {
      let sheet = targetSs.getSheetByName(sheetName);
      if (!sheet) {
        sheet = targetSs.insertSheet(sheetName);
        sheet.appendRow(BRANCH_SHEETS[sheetName]);
        sheet.getRange(1, 1, 1, BRANCH_SHEETS[sheetName].length).setFontWeight("bold");
      }
    }
    // Delete default Sheet1 if exists
    try {
      let defSheet = targetSs.getSheetByName("Sheet1");
      if (defSheet) targetSs.deleteSheet(defSheet);
    } catch(e) {}
    
    // Save to Database_Registry
    appendObjectToSheetDirect("Database_Registry", {
      Registry_Key: registryKey,
      Spreadsheet_ID: qbFileId,
      Folder_ID: branchFolder.getId()
    });
    
    return qbFileId;
  } catch (err) {
    Logger.log("Error in getBranchQuestionBankId: " + err.toString());
    return SpreadsheetApp.getActiveSpreadsheet().getId();
  }
}

/**
 * Dynamically resolves or initializes a batch-specific database spreadsheet file.
 */
function getBatchSpreadsheetId(branchCode, batchYear) {
  try {
    const cleanBranch = branchCode.toString().trim().toUpperCase();
    const cleanBatch = batchYear.toString().trim();
    const registryKey = `${cleanBranch}_${cleanBatch}`;
    
    // Check Registry
    const ss = SpreadsheetApp.getActiveSpreadsheet();
    const regSheet = ss.getSheetByName("Database_Registry");
    if (regSheet) {
      const data = regSheet.getDataRange().getValues();
      for (let i = 1; i < data.length; i++) {
        if (data[i][0] && data[i][0].toString().trim().toUpperCase() === registryKey.toUpperCase()) {
          return data[i][1];
        }
      }
    }
    
    // Dynamic Directory setup: Carmel Linx Exam Portal Files -> [Branch] -> Batch_[Year]
    let mainFolder = getDriveFolder(null, "Carmel Linx Exam Portal Files");
    let branchFolder = getDriveFolder(mainFolder, cleanBranch);
    let batchFolder = getDriveFolder(branchFolder, "Batch_" + cleanBatch);
    
    let batchFileId = "";
    try {
      let batchSs = SpreadsheetApp.create(`${cleanBranch}_Batch_${cleanBatch}_Database`);
      let file = DriveApp.getFileById(batchSs.getId());
      batchFolder.addFile(file);
      try { DriveApp.getRootFolder().removeFile(file); } catch(e) {}
      batchFileId = batchSs.getId();
    } catch(e) {
      // Simulator/Mock fallback
      batchFileId = SpreadsheetApp.getActiveSpreadsheet().getId();
    }
    
    // Setup sheets inside the Batch Spreadsheet
    const targetSs = SpreadsheetApp.openById(batchFileId);
    for (let sheetName in BATCH_SHEETS) {
      let sheet = targetSs.getSheetByName(sheetName);
      if (!sheet) {
        sheet = targetSs.insertSheet(sheetName);
        sheet.appendRow(BATCH_SHEETS[sheetName]);
        sheet.getRange(1, 1, 1, BATCH_SHEETS[sheetName].length).setFontWeight("bold");
      }
    }
    // Delete default Sheet1 if exists
    try {
      let defSheet = targetSs.getSheetByName("Sheet1");
      if (defSheet) targetSs.deleteSheet(defSheet);
    } catch(e) {}
    
    // Save to Database_Registry
    appendObjectToSheetDirect("Database_Registry", {
      Registry_Key: registryKey,
      Spreadsheet_ID: batchFileId,
      Folder_ID: batchFolder.getId()
    });
    
    return batchFileId;
  } catch (err) {
    Logger.log("Error in getBatchSpreadsheetId: " + err.toString());
    return SpreadsheetApp.getActiveSpreadsheet().getId();
  }
}

/**
 * Extracts branch code (e.g. "EL") from a classroom ID string.
 */
function extractBranchFromClassroom(classroomId) {
  if (!classroomId) return "Admin";
  const match = classroomId.toString().trim().toUpperCase().match(/^([A-Z]+)/);
  return match ? match[1] : "Admin";
}

/**
 * Extracts admission/batch year (e.g. "2025") from a classroom ID string.
 */
function extractBatchFromClassroom(classroomId) {
  if (!classroomId) return "Global";
  const parts = classroomId.toString().split(/[\s_-]+/);
  if (parts.length > 1) {
    // EL_2025_28 -> "2025" or "2025-28"
    // Let's standardise the batch registry keys to branch + admission year.
    return parts[1]; 
  }
  return "Global";
}

/**
 * Central routing resolver that returns a Google Sheet instance dynamically.
 */
function getSheetRef(sheetName, contextId) {
  try {
    // 1. Check if global sheet
    if (GLOBAL_SHEETS[sheetName] !== undefined) {
      return SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
    }
    
    // 2. Check if branch cumulative sheet
    if (BRANCH_SHEETS[sheetName] !== undefined) {
      const branchCode = extractBranchFromClassroom(contextId);
      const qbId = getBranchQuestionBankId(branchCode);
      return SpreadsheetApp.openById(qbId).getSheetByName(sheetName);
    }
    
    // 3. Check if batch-specific sheet
    if (BATCH_SHEETS[sheetName] !== undefined) {
      if (contextId) {
        const branchCode = extractBranchFromClassroom(contextId);
        const batchYear = extractBatchFromClassroom(contextId);
        const batchSsId = getBatchSpreadsheetId(branchCode, batchYear);
        return SpreadsheetApp.openById(batchSsId).getSheetByName(sheetName);
      }
    }
    
    // Fallback
    return SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
  } catch (err) {
    Logger.log(`Error in getSheetRef for sheet ${sheetName} with context ${contextId}: ` + err.toString());
    return SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
  }
}

const STAFF_STANDARD_MAP = {
  Mobile_No: ["Mobile_No", "Mobile No", "MobileNumber", "Mobile ID", "Mobile_ID", "Phone"],
  Name: ["Name", "Full Name", "Full_Name", "Staff Name", "Staff_Name"],
  Email: ["Email", "Email_Address", "Email Address", "Email_ID", "Email ID"],
  Branch: ["Branch", "Department", "Branch_Code"],
  Designation: ["Designation", "Role"],
  Password: ["Password"],
  Photo_Drive_Link: ["Photo_Drive_Link", "Photo", "Photo Url", "Photo_Url"],
  Account_Status: ["Account_Status", "Account Status", "Status"]
};

const STUDENT_STANDARD_MAP = {
  Reg_No: ["Reg_No", "Reg No", "Register No", "Register_No", "Registration No", "Registration_No"],
  Adm_No: ["Adm_No", "Adm No", "Admission No", "Admission_No"],
  Name: ["Name", "Full Name", "Full_Name", "Student Name", "Student_Name"],
  Email: ["Email", "Email_Address", "Email Address", "Email_ID", "Email ID"],
  Password: ["Password"],
  Branch: ["Branch", "Department"],
  Year_Of_Admission: ["Year_Of_Admission", "Year Of Admission", "Admission Year", "Admission_Year", "Year"],
  Semester: ["Semester", "Sem"],
  Photo_Drive_Link: ["Photo_Drive_Link", "Photo", "Photo Url", "Photo_Url"],
  Classroom_ID: ["Classroom_ID", "Classroom ID", "Classroom_Id", "Classroom Id", "Class ID", "Class_ID"],
  Status: ["Status", "Account_Status", "Account Status"],
  Mentor_Mobile_No: ["Mentor_Mobile_No", "Mentor Mobile", "Mentor Mobile No", "Mentor_Mobile"]
};

const CLASS_STANDARD_MAP = {
  Classroom_ID: ["Classroom_ID", "Classroom ID", "Classroom_Id", "Classroom Id", "Class ID", "Class_ID"],
  Branch: ["Branch", "Department"],
  Batch_Year: ["Batch_Year", "Batch Year", "Batch", "Year"],
  Tutor_Mobile_No: ["Tutor_Mobile_No", "Tutor Mobile", "Tutor Mobile No", "Tutor_Mobile"],
  Mentor_Mobile_No: ["Mentor_Mobile_No", "Mentor Mobile", "Mentor Mobile No", "Mentor_Mobile"]
};

function normalizeKeys(obj, standardMap) {
  if (!obj) return obj;
  const normalized = {};
  
  // Set default empty values for all standard keys
  for (let stdKey in standardMap) {
    normalized[stdKey] = "";
  }
  
  // Map properties from obj to normalized keys
  for (let key in obj) {
    const cleanKey = key.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
    let mapped = false;
    for (let stdKey in standardMap) {
      const matchPatterns = standardMap[stdKey];
      const matched = matchPatterns.some(pat => {
        const cleanPat = pat.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        return cleanKey === cleanPat;
      });
      if (matched) {
        normalized[stdKey] = obj[key];
        mapped = true;
        break;
      }
    }
    // If not in the map, preserve the original property
    if (!mapped) {
      normalized[key] = obj[key];
    }
  }
  return normalized;
}

/**
 * Fetch all rows from a sheet converted into JavaScript Objects based on header keys.
 * Supports context-specific database routing.
 */
function getSheetRowsAsObjects(sheetName, contextId) {
  try {
    const sheet = getSheetRef(sheetName, contextId);
    if (!sheet) return [];
    
    const data = sheet.getDataRange().getValues();
    if (data.length <= 1) return [];
    
    const headers = data[0];
    const rows = [];
    
    for (let i = 1; i < data.length; i++) {
      const rowObj = {};
      let hasData = false;
      for (let j = 0; j < headers.length; j++) {
        const val = data[i][j];
        rowObj[headers[j]] = val;
        if (val !== "" && val !== null && val !== undefined) {
          hasData = true;
        }
      }
      if (hasData) {
        let normalized = rowObj;
        if (sheetName === "Staff_Profiles") {
          normalized = normalizeKeys(rowObj, STAFF_STANDARD_MAP);
        } else if (sheetName === "Students" || sheetName === "Students_Registry_Lookup") {
          normalized = normalizeKeys(rowObj, STUDENT_STANDARD_MAP);
        } else if (sheetName === "Class_Management") {
          normalized = normalizeKeys(rowObj, CLASS_STANDARD_MAP);
        }
        rows.push(normalized);
      }
    }
    return rows;
  } catch (err) {
    Logger.log(`Error reading sheet ${sheetName} with context ${contextId}: ` + err.toString());
    return [];
  }
}

/**
 * Append a JavaScript Object to a spreadsheet tab, mapping keys to headers.
 */
function appendObjectToSheet(sheetName, obj, contextId) {
  try {
    const sheet = getSheetRef(sheetName, contextId);
    if (!sheet) throw new Error("Sheet '" + sheetName + "' does not exist.");
    
    const data = sheet.getDataRange().getValues();
    const headers = data[0];
    const newRow = headers.map(h => {
      const cleanH = h.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
      const matchedKey = Object.keys(obj).find(k => k.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase() === cleanH);
      return matchedKey !== undefined ? obj[matchedKey] : "";
    });
    
    sheet.appendRow(newRow);
    return { status: "SUCCESS" };
  } catch (err) {
    Logger.log(`Error appending to sheet ${sheetName} with context ${contextId}: ` + err.toString());
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Helper to append directly to the active spreadsheet (avoids registry loops).
 */
function appendObjectToSheetDirect(sheetName, obj) {
  try {
    const sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
    if (!sheet) throw new Error("Sheet '" + sheetName + "' does not exist.");
    const data = sheet.getDataRange().getValues();
    const headers = data[0];
    const newRow = headers.map(h => {
      const cleanH = h.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
      const matchedKey = Object.keys(obj).find(k => k.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase() === cleanH);
      return matchedKey !== undefined ? obj[matchedKey] : "";
    });
    sheet.appendRow(newRow);
    return { status: "SUCCESS" };
  } catch (err) {
    Logger.log("Error in appendObjectToSheetDirect: " + err.toString());
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Update rows matching a specific key-value criteria with properties of updateObj.
 */
function updateObjectInSheet(sheetName, keyColumn, keyValue, updateObj, contextId) {
  try {
    const sheet = getSheetRef(sheetName, contextId);
    if (!sheet) throw new Error("Sheet '" + sheetName + "' does not exist.");
    
    const data = sheet.getDataRange().getValues();
    const headers = data[0];
    const cleanKeyCol = keyColumn.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
    const keyColIdx = headers.findIndex(h => h.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase() === cleanKeyCol);
    if (keyColIdx === -1) throw new Error("Column '" + keyColumn + "' not found in " + sheetName);
    
    let updatedCount = 0;
    const isMobileKey = keyColumn.toUpperCase().includes("MOBILE") || keyColumn.toUpperCase().includes("PHONE");
    const cleanKeyValue = isMobileKey ? keyValue.toString().replace(/[^0-9]/g, '') : keyValue.toString().trim().toUpperCase();
    
    for (let i = 1; i < data.length; i++) {
      if (data[i][keyColIdx]) {
        let match = false;
        const cellValue = data[i][keyColIdx].toString();
        if (isMobileKey) {
          match = cellValue.replace(/[^0-9]/g, '') === cleanKeyValue;
        } else {
          match = cellValue.trim().toUpperCase() === cleanKeyValue;
        }
        if (match) {
          const rowIndex = i + 1;
          for (let key in updateObj) {
            const cleanPropKey = key.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
            const colIdx = headers.findIndex(h => h.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase() === cleanPropKey);
            if (colIdx !== -1) {
              sheet.getRange(rowIndex, colIdx + 1).setValue(updateObj[key]);
            }
          }
          updatedCount++;
        }
      }
    }
    if (updatedCount === 0) {
      return { status: "ERROR", message: "No matching record found to update." };
    }
    return { status: "SUCCESS", count: updatedCount };
  } catch (err) {
    Logger.log(`Error updating sheet ${sheetName} with context ${contextId}: ` + err.toString());
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Delete rows matching a specific key-value pair.
 */
function deleteObjectFromSheet(sheetName, keyColumn, keyValue, contextId) {
  try {
    const sheet = getSheetRef(sheetName, contextId);
    if (!sheet) throw new Error("Sheet '" + sheetName + "' does not exist.");
    
    const data = sheet.getDataRange().getValues();
    const headers = data[0];
    const cleanKeyCol = keyColumn.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
    const keyColIdx = headers.findIndex(h => h.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase() === cleanKeyCol);
    if (keyColIdx === -1) throw new Error("Column '" + keyColumn + "' not found in " + sheetName);
    
    let deletedCount = 0;
    for (let i = data.length - 1; i >= 1; i--) {
      if (data[i][keyColIdx] && data[i][keyColIdx].toString().trim().toUpperCase() === keyValue.toString().trim().toUpperCase()) {
        sheet.deleteRow(i + 1);
        deletedCount++;
      }
    }
    return { status: "SUCCESS", count: deletedCount };
  } catch (err) {
    Logger.log(`Error deleting from sheet ${sheetName} with context ${contextId}: ` + err.toString());
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Write a transaction log for audit trailing.
 */
function logExamAction(regNo, testId, action, details, contextId) {
  const logEntry = {
    Log_ID: "LOG_" + Date.now() + "_" + Math.floor(1000 + Math.random() * 9000),
    Reg_No: regNo,
    Test_ID: testId,
    Action: action,
    Timestamp: new Date().toISOString(),
    Details: details || ""
  };
  return appendObjectToSheet("Test_Logs", logEntry, contextId);
}

/**
 * Automatically sets up the global structure of sheets inside the Active Spreadsheet.
 */
function initializeDatabase() {
  try {
    const props = PropertiesService.getScriptProperties();
    const isInit = props.getProperty("DB_INITIALIZED");
    if (isInit === "true") {
      return; // Cache optimization: skip sheets configuration check on every request
    }

    const ss = SpreadsheetApp.getActiveSpreadsheet();

    // Setup global sheets
    for (let sheetName in GLOBAL_SHEETS) {
      let sheet = ss.getSheetByName(sheetName);
      if (!sheet) {
        sheet = ss.insertSheet(sheetName);
        sheet.appendRow(GLOBAL_SHEETS[sheetName]);
        sheet.getRange(1, 1, 1, GLOBAL_SHEETS[sheetName].length).setFontWeight("bold");
      }
    }

    // Pre-populate POs for Diploma Engineering (NBA standard) if PO_Config is empty
    let poSheet = ss.getSheetByName("PO_Config");
    if (poSheet && poSheet.getLastRow() <= 1) {
      const defaultPOs = [
        ["PO1", "Basic and Discipline Specific Knowledge", "Apply knowledge of basic mathematics, science and engineering core and discipline to solve engineering problems."],
        ["PO2", "Problem Analysis", "Identify and analyze well-defined engineering problems using codified standard methods."],
        ["PO3", "Design/Development of Solutions", "Design solutions for well-defined technical problems and assist with the design of systems components or processes."],
        ["PO4", "Engineering Tools, Experimentation and Testing", "Apply modern engineering tools and appropriate technique to conduct standard tests and measurements."],
        ["PO5", "Engineering Practices for Society, Sustainability and Environment", "Understand engineering solutions in a societal, environmental, and sustainable context."],
        ["PO6", "Project Management", "Use engineering management principles individually, as a team member or a leader in a diverse team."],
        ["PO7", "Life-long Learning", "Ability to analyze individual needs and engage in independent and life-long learning in technological changes."],
        ["PO8", "Engineering Ethics", "Understand professional ethics and responsibilities as a technician."],
        ["PO9", "Individual and Team Work", "Function effectively as an individual, and as a member or leader in diverse teams."],
        ["PO10", "Communication", "Communicate effectively on well-defined engineering activities with the engineering community and with society."],
        ["PO11", "Environment and Sustainability", "Understand the impact of the professional engineering solutions in societal and environmental contexts."],
        ["PO12", "Lifelong Learning", "Recognize the need for, and have the preparation and ability to engage in independent and life-long learning."]
      ];
      defaultPOs.forEach(po => poSheet.appendRow(po));
    }

    // Pre-populate Institution Config if empty
    let instSheet = ss.getSheetByName("Institution_Config");
    if (instSheet && instSheet.getLastRow() <= 1) {
      const defaultInst = [
        ["Institution_Name", "Carmel Polytechnic College"],
        ["Institution_Vision", "To be a premier institution in technical education, training students to become competent engineering professionals with strong moral values."],
        ["Institution_Mission", "Provide quality technical education and practical training. Foster ethical values, leadership qualities, and lifelong learning attitudes to meet global industrial standards."]
      ];
      defaultInst.forEach(conf => instSheet.appendRow(conf));
    }

    // Pre-populate default Database Registry items for local simulator testing
    let regSheet = ss.getSheetByName("Database_Registry");
    if (regSheet && regSheet.getLastRow() <= 1) {
      const defaultRegistry = [
        ["EL_2024", ss.getId(), "MOCK_FOLDER_ID_EL_2024"],
        ["ECE_2024_S3", ss.getId(), "MOCK_FOLDER_ID_ECE_2024_S3"],
        ["EL_QB", ss.getId(), "MOCK_FOLDER_ID_EL_QB"]
      ];
      defaultRegistry.forEach(row => regSheet.appendRow(row));
    }

    // Pre-populate default Students Registry Lookup items for local simulator testing
    let lookupSheet = ss.getSheetByName("Students_Registry_Lookup");
    if (lookupSheet && lookupSheet.getLastRow() <= 1) {
      const defaultLookup = [
        ["REG24EC01", "ADM24EC01", "amal.raj@carmelpoly.edu.in", "password123", "ECE_2024_S3", "Approved"],
        ["REG24EC02", "ADM24EC02", "diya.e@carmelpoly.edu.in", "password123", "ECE_2024_S3", "Approved"],
        ["REG24EC03", "ADM24EC03", "rahul.r@carmelpoly.edu.in", "password123", "ECE_2024_S3", "Approved"]
      ];
      defaultLookup.forEach(row => lookupSheet.appendRow(row));
    }

    // Create Main File Directories on Google Drive
    getDriveFolder(null, "Carmel Linx Exam Portal Files");

    props.setProperty("DB_INITIALIZED", "true");

  } catch(e) {
    Logger.log("DB Auto-Initialization failed: " + e.toString());
  }
}

/**
 * Saves a base64 encoded photo file directly into a Google Drive subfolder, returning the public URL.
 */
function saveFileToDrive(base64Data, folderName, fileName) {
  try {
    if (!base64Data || !base64Data.startsWith("data:image")) {
      return base64Data;
    }
    
    let mainFolder = getDriveFolder(null, "Carmel Linx Exam Portal Files");
    let targetFolder = getDriveFolder(mainFolder, folderName);

    const contentType = base64Data.substring(5, base64Data.indexOf(";"));
    const bytes = Utilities.base64Decode(base64Data.substring(base64Data.indexOf(",") + 1));
    const blob = Utilities.newBlob(bytes, contentType, fileName);

    const file = targetFolder.createFile(blob);
    file.setSharing(DriveApp.Access.ANYONE_WITH_LINK, DriveApp.Permission.VIEW);

    return file.getUrl();
  } catch (err) {
    Logger.log("Failed to write avatar to Drive: " + err.toString());
    return "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150";
  }
}
