// ============================================================================
// CARMEL LINX - CENTRAL ROUTING & DISPATCH ENGINE (Code.js)
// ============================================================================

const SPREADSHEET_ID = "CARMEL_SPREADSHEET_MOCK"; // Placeholder or loaded dynamically in GAS

/**
 * Serves the initial landing page
 */
function doGet(e) {
  initializeDatabase();
  let page = (e && e.parameter && e.parameter.page) || 'Login';
  let templateName = 'Login';
  
  const pageLower = page.toLowerCase();
  if (pageLower === 'student') {
    templateName = 'Student_Exam';
  } else if (pageLower === 'faculty') {
    templateName = 'Faculty_Dashboard';
  } else if (pageLower === 'hod') {
    templateName = 'HOD_Dashboard';
  } else if (pageLower === 'tutor') {
    templateName = 'Tutor_Dashboard';
  } else if (pageLower === 'admin') {
    templateName = 'Admin_Dashboard';
  } else if (pageLower === 'principal') {
    templateName = 'Principal_Dashboard';
  }
  
  return HtmlService.createTemplateFromFile(templateName)
    .evaluate()
    .setTitle('Carmel Linx - OBE Portal')
    .addMetaTag('viewport', 'width=device-width, initial-scale=1')
    .setXFrameOptionsMode(HtmlService.XFrameOptionsMode.ALLOWALL);
}

/**
 * Standard GAS helper to include separate files (CSS/JS) inside HTML templates
 */
function include(filename) {
  return HtmlService.createHtmlOutputFromFile(filename).getContent();
}

// ============================================================================
// STUDENT EXAM CONTROLLER
// ============================================================================

/**
 * Fetches active tests assigned to a student's Classroom_ID based on current time
 */
function getActiveExamsForStudent(studentRegNo) {
  try {
    const students = getSheetRowsAsObjects("Students");
    const student = students.find(s => s.Reg_No === studentRegNo);
    if (!student) return { status: "ERROR", message: "Student not found." };
    
    const testConfigs = getSheetRowsAsObjects("Test_Config");
    const responses = getSheetRowsAsObjects("Student_Responses");
    const now = new Date();
    
    // Filter tests assigned to this classroom that are currently active
    const activeExams = testConfigs.filter(test => {
      if (test.Classroom_ID !== student.Classroom_ID || !test.Is_Active) return false;
      
      const startTime = new Date(test.Start_Time);
      const endTime = new Date(test.End_Time);
      
      return now >= startTime && now <= endTime;
    });
    
    // Check which exams the student has already submitted
    const formattedExams = activeExams.map(test => {
      const alreadySubmitted = responses.some(r => r.Reg_No === studentRegNo && r.Test_ID === test.Test_ID);
      
      return {
        testId: test.Test_ID,
        subjectCode: test.Subject_Code,
        testName: test.Test_Name,
        duration: test.Duration,
        submitted: alreadySubmitted,
        selectedCOs: JSON.parse(test.Selected_COs || "[]")
      };
    });
    
    return { status: "SUCCESS", exams: formattedExams };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Shuffles an array in place
 */
function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array;
}

/**
 * Starts an exam, logs the entry, and returns randomized questions WITHOUT answer keys
 */
function loadExamQuestions(regNo, testId) {
  try {
    const testConfigs = getSheetRowsAsObjects("Test_Config");
    const test = testConfigs.find(t => t.Test_ID === testId);
    if (!test) return { status: "ERROR", message: "Test configuration not found." };
    
    // Log exam startup
    logExamAction(regNo, testId, "Login", "Exam window opened by student.");
    
    const questionBank = getSheetRowsAsObjects("Question_Bank");
    const selectedCOs = JSON.parse(test.Selected_COs || "[]");
    
    // Filter questions tagged with the subject code and correct COs
    const filteredQuestions = questionBank.filter(q => 
      q.Subject_Code === test.Subject_Code && 
      selectedCOs.includes(q.CO_Tag)
    );
    
    // Separate MCQ vs Descriptive
    let mcqs = filteredQuestions.filter(q => q.Type === "MCQ");
    let descriptives = filteredQuestions.filter(q => q.Type === "Descriptive");
    
    // Shuffle MCQ order
    mcqs = shuffleArray(mcqs);
    
    // Select required count
    const mcqCount = parseInt(test.MCQ_Count) || mcqs.length;
    const descCount = parseInt(test.Descriptive_Count) || descriptives.length;
    
    const selectedMCQs = mcqs.slice(0, mcqCount);
    const selectedDesc = descriptives.slice(0, descCount);
    
    // Strip answers to prevent client source inspecting
    const clientQuestions = [...selectedMCQs, ...selectedDesc].map(q => {
      const options = q.Type === "MCQ" ? JSON.parse(q.Options || "[]") : [];
      return {
        questionId: q.Question_ID,
        type: q.Type,
        questionText: q.Question_Text,
        options: options,
        coTag: q.CO_Tag
      };
    });
    
    return {
      status: "SUCCESS",
      testId: testId,
      testName: test.Test_Name,
      duration: test.Duration,
      questions: clientQuestions
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Evaluates MCQ answers, triggers AI evaluation for descriptive responses, and stores logs
 */
function submitStudentExam(regNo, testId, answersGrid) {
  try {
    const testConfigs = getSheetRowsAsObjects("Test_Config");
    const test = testConfigs.find(t => t.Test_ID === testId);
    if (!test) return { status: "ERROR", message: "Test config matching " + testId + " lost." };
    
    const questionBank = getSheetRowsAsObjects("Question_Bank");
    const nowStr = new Date().toISOString();
    
    let mcqCorrectCount = 0;
    let mcqTotalCount = 0;
    let processedAnswersCount = 0;
    
    answersGrid.forEach(ans => {
      const q = questionBank.find(qb => qb.Question_ID === ans.questionId);
      if (!q) return;
      
      let marksObtained = 0;
      let evalStatus = "Auto-Graded";
      let evalBy = "System";
      let descFeedback = "";
      
      if (q.Type === "MCQ") {
        mcqTotalCount++;
        const isCorrect = q.Correct_Answer.trim().toUpperCase() === ans.selectedOption.trim().toUpperCase();
        if (isCorrect) {
          marksObtained = 1; // 1 point per MCQ
          mcqCorrectCount++;
        }
      } else {
        // Descriptive answer AI grading
        evalStatus = "AI-Graded";
        evalBy = "Gemini AI";
        
        // Grade using AI
        const aiResult = evaluateAnswerWithAI(q.Question_Text, q.Correct_Answer, ans.descriptiveText);
        marksObtained = aiResult.marks; // returns score out of 10
        descFeedback = aiResult.feedback;
      }
      
      // Save Response to database
      const responseEntry = {
        Response_ID: "RESP_" + Date.now() + "_" + Math.floor(1000 + Math.random() * 9000),
        Reg_No: regNo,
        Test_ID: testId,
        Question_ID: ans.questionId,
        Selected_Option: q.Type === "MCQ" ? ans.selectedOption : "",
        Descriptive_Text: q.Type === "Descriptive" ? ans.descriptiveText : "",
        Marks_Obtained: marksObtained.toString(),
        Evaluated_By: evalBy,
        Status: evalStatus
      };
      
      appendObjectToSheet("Student_Responses", responseEntry);
      processedAnswersCount++;
    });
    
    // Log submission success
    logExamAction(regNo, testId, "Manual_Submit", `Submitted ${processedAnswersCount} answers. MCQ Correct: ${mcqCorrectCount}/${mcqTotalCount}`);
    
    // Automated emailing trigger
    try {
      emailStudentExamReport(regNo, testId);
    } catch (eMailErr) {
      Logger.log("Email reporting failed: " + eMailErr.toString());
    }
    
    return {
      status: "SUCCESS",
      message: "Test submitted successfully!",
      mcqCorrect: mcqCorrectCount,
      mcqTotal: mcqTotalCount
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Logs cheating activities like switching windows/tabs during an exam
 */
function logTabSwitchCheat(regNo, testId, warningIndex) {
  return logExamAction(regNo, testId, "Tab_Switch_Warning", `Warning #${warningIndex}: Student switched tabs/windows.`);
}

// ============================================================================
// FACULTY MANAGEMENT CONTROLLER
// ============================================================================

/**
 * Fetches unique classes and subjects assigned to a specific faculty member
 */
function getFacultyAssignedClasses(facultyMobile) {
  try {
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping");
    const facultyMappings = mappings.filter(m => m.Faculty_Mobile_No === facultyMobile);
    
    return { status: "SUCCESS", mappings: facultyMappings };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Schedules a new online test
 */
function scheduleNewExam(examConfig) {
  try {
    const testConfigs = getSheetRowsAsObjects("Test_Config");
    const duplicate = testConfigs.find(t => t.Test_ID === examConfig.testId);
    if (duplicate) {
      return { status: "ERROR", message: "A test with this ID already exists." };
    }
    
    const newTest = {
      Test_ID: examConfig.testId,
      Subject_Code: examConfig.subjectCode,
      Classroom_ID: examConfig.classroomId,
      Test_Name: examConfig.testName,
      Start_Time: examConfig.startTime,
      End_Time: examConfig.endTime,
      Duration: examConfig.duration,
      Selected_COs: JSON.stringify(examConfig.selectedCOs),
      MCQ_Count: examConfig.mcqCount.toString(),
      Descriptive_Count: examConfig.descriptiveCount.toString(),
      Target_Percentage: examConfig.targetPct.toString(),
      Pass_Threshold: examConfig.passThreshold.toString(),
      Is_Active: true
    };
    
    appendObjectToSheet("Test_Config", newTest);
    return { status: "SUCCESS", message: "Test scheduled successfully!" };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches all scheduled exams for subjects assigned to a faculty
 */
function getFacultyExams(facultyMobile) {
  try {
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping");
    const facultyMappings = mappings.filter(m => m.Faculty_Mobile_No === facultyMobile);
    const assignedSubjects = facultyMappings.map(m => m.Subject_Code);
    
    const testConfigs = getSheetRowsAsObjects("Test_Config");
    const facultyExams = testConfigs.filter(t => assignedSubjects.includes(t.Subject_Code));
    
    return { status: "SUCCESS", exams: facultyExams };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Saves manual Series Test Marks for a classroom
 */
function saveClassSeriesMarks(classroomId, subjectCode, seriesName, coTag, marksGrid, facultyMobile) {
  try {
    const existingMarks = getSheetRowsAsObjects("Series_Test_Marks");
    let inserted = 0;
    let updated = 0;
    
    marksGrid.forEach(m => {
      // Check if entry already exists to Upsert
      const duplicate = existingMarks.find(em => 
        em.Reg_No === m.regNo && 
        em.Classroom_ID === classroomId && 
        em.Subject_Code === subjectCode && 
        em.Series_Exam_Name === seriesName && 
        em.CO_Tag === coTag
      );
      
      const markObj = {
        Reg_No: m.regNo,
        Classroom_ID: classroomId,
        Subject_Code: subjectCode,
        Series_Exam_Name: seriesName,
        CO_Tag: coTag,
        Max_Marks: "50", // Standard max marks
        Marks_Obtained: m.marks.toString(),
        Entered_By: facultyMobile,
        Timestamp: new Date().toISOString()
      };
      
      if (duplicate) {
        // Update
        updateObjectInSheet("Series_Test_Marks", "Mark_ID", duplicate.Mark_ID, {
          Marks_Obtained: m.marks.toString(),
          Entered_By: facultyMobile,
          Timestamp: new Date().toISOString()
        });
        updated++;
      } else {
        // Insert
        markObj.Mark_ID = "M_" + Date.now() + "_" + Math.floor(1000 + Math.random() * 9000);
        appendObjectToSheet("Series_Test_Marks", markObj);
        inserted++;
      }
    });
    
    return { status: "SUCCESS", message: `Marks updated: ${updated}, inserted: ${inserted}` };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetch descriptive responses pending manual review / override
 */
function getPendingDescEvaluations(facultyMobile) {
  try {
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping");
    const facultyMappings = mappings.filter(m => m.Faculty_Mobile_No === facultyMobile);
    const assignedSubjects = facultyMappings.map(m => m.Subject_Code);
    
    const testConfigs = getSheetRowsAsObjects("Test_Config");
    const assignedTests = testConfigs.filter(t => assignedSubjects.includes(t.Subject_Code)).map(t => t.Test_ID);
    
    const responses = getSheetRowsAsObjects("Student_Responses");
    const pendingResponses = responses.filter(r => 
      assignedTests.includes(r.Test_ID) && 
      r.Status === "AI-Graded" // Lets faculty review and modify AI scores
    );
    
    const questionBank = getSheetRowsAsObjects("Question_Bank");
    const students = getSheetRowsAsObjects("Students");
    
    const formatted = pendingResponses.map(r => {
      const q = questionBank.find(qb => qb.Question_ID === r.Question_ID);
      const student = students.find(s => s.Reg_No === r.Reg_No);
      
      return {
        responseId: r.Response_ID,
        studentName: student ? student.Name : r.Reg_No,
        regNo: r.Reg_No,
        testId: r.Test_ID,
        questionText: q ? q.Question_Text : "Unmapped Question",
        correctAnswer: q ? q.Correct_Answer : "-",
        studentAnswer: r.Descriptive_Text,
        currentScore: r.Marks_Obtained
      };
    });
    
    return { status: "SUCCESS", evaluations: formatted };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Update score manually for a student response
 */
function updateResponseScore(responseId, newScore, facultyMobile) {
  try {
    return updateObjectInSheet("Student_Responses", "Response_ID", responseId, {
      Marks_Obtained: newScore.toString(),
      Evaluated_By: "Faculty (" + facultyMobile + ")",
      Status: "Manually-Graded"
    });
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Adds a new question to the subject question bank
 */
function addQuestionToBank(questionData) {
  try {
    const branchCode = extractBranchFromClassroom(questionData.subjectCode);
    const qId = "Q_" + Date.now() + "_" + Math.floor(1000 + Math.random() * 9000);
    
    const newQ = {
      Subject_Code: questionData.subjectCode,
      Question_ID: qId,
      Type: questionData.type,
      Question_Text: questionData.questionText,
      Options: JSON.stringify(questionData.options || []),
      Correct_Answer: questionData.correctAnswer || "",
      CO_Tag: questionData.coTag || "CO1",
      Marks: questionData.marks ? questionData.marks.toString() : (questionData.type === "MCQ" ? "2" : "5")
    };
    
    appendObjectToSheet("Question_Bank", newQ, branchCode);
    return { status: "SUCCESS", questionId: qId };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches all questions in the bank for a specific subject
 */
function getSubjectQuestionBank(subjectCode) {
  try {
    const branchCode = extractBranchFromClassroom(subjectCode);
    const qb = getSheetRowsAsObjects("Question_Bank", branchCode);
    const filtered = qb.filter(q => q.Subject_Code === subjectCode);
    return { status: "SUCCESS", questions: filtered };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

// ============================================================================
// HOD CONTROLLER
// ============================================================================

/**
 * Fetches all student records matching HOD's branch
 */
function getHODStudentRoster(branchCode) {
  try {
    const students = getSheetRowsAsObjects("Students").filter(s => s.Branch === branchCode);
    return { status: "SUCCESS", roster: students };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches all class allocations in the department
 */
function getHODClassroomAllocations(branchCode) {
  try {
    const classes = getSheetRowsAsObjects("Class_Management").filter(c => c.Branch === branchCode);
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping");
    const staff = getSheetRowsAsObjects("Staff_Profiles");
    
    const formatted = classes.map(c => {
      const tutor = staff.find(s => s.Mobile_No === c.Tutor_Mobile_No);
      const mentor = staff.find(s => s.Mobile_No === c.Mentor_Mobile_No);
      const subjects = mappings.filter(m => m.Classroom_ID === c.Classroom_ID).map(m => {
        const fac = staff.find(s => s.Mobile_No === m.Faculty_Mobile_No);
        return {
          code: m.Subject_Code,
          name: m.Subject_Name,
          facultyName: fac ? fac.Name : m.Faculty_Mobile_No
        };
      });
      
      return {
        classroomId: c.Classroom_ID,
        batchYear: c.Batch_Year,
        tutorMobile: c.Tutor_Mobile_No || "",
        mentorMobile: c.Mentor_Mobile_No || "",
        tutorName: tutor ? tutor.Name : "Unassigned",
        mentorName: mentor ? mentor.Name : "Unassigned",
        subjects: subjects
      };
    });
    
    return { status: "SUCCESS", classes: formatted };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Saves tutor/mentor allocations to a classroom
 */
function allocateTutors(classroomId, tutorMobile, mentorMobile) {
  try {
    const classes = getSheetRowsAsObjects("Class_Management");
    const exist = classes.find(c => c.Classroom_ID === classroomId);
    
    if (exist) {
      return updateObjectInSheet("Class_Management", "Classroom_ID", classroomId, {
        Tutor_Mobile_No: tutorMobile,
        Mentor_Mobile_No: mentorMobile
      });
    } else {
      return { status: "ERROR", message: "Classroom not registered yet." };
    }
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Maps a faculty to a subject classroom
 */
function mapFacultySubject(classroomId, subjectCode, subjectName, facultyMobile, courseType) {
  try {
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping");
    const duplicate = mappings.find(m => 
      m.Classroom_ID === classroomId && 
      m.Subject_Code === subjectCode && 
      m.Faculty_Mobile_No === facultyMobile
    );
    
    if (duplicate) {
      return { status: "ERROR", message: "This faculty assignment already exists." };
    }
    
    const newMapping = {
      Mapping_ID: "MAP_" + Date.now() + "_" + Math.floor(100 + Math.random() * 900),
      Classroom_ID: classroomId,
      Subject_Code: subjectCode,
      Subject_Name: subjectName,
      Faculty_Mobile_No: facultyMobile,
      Course_Type: courseType || "Theory",
      Attainment_Threshold: "50"
    };
    
    appendObjectToSheet("Subject_Faculty_Mapping", newMapping);
    return { status: "SUCCESS", message: "Faculty successfully mapped to subject!" };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Creates a new classroom container
 */
function createClassroom(branch, batchYear, semester) {
  try {
    const cleanBranch = branch.trim().toUpperCase();
    const cleanBatch = batchYear.replace("-", "_").trim();
    const classroomId = `${cleanBranch}_${cleanBatch}_${semester.trim().toUpperCase()}`;
    
    const classes = getSheetRowsAsObjects("Class_Management");
    const exist = classes.find(c => c.Classroom_ID === classroomId);
    
    if (exist) {
      return { status: "ERROR", message: "Classroom group already exists." };
    }
    
    // Dynamically initialize branch/batch folders and database spreadsheet file on Google Drive
    getBatchSpreadsheetId(cleanBranch, cleanBatch);
    
    const newClass = {
      Classroom_ID: classroomId,
      Branch: cleanBranch,
      Batch_Year: batchYear.trim(),
      Tutor_Mobile_No: "",
      Mentor_Mobile_No: ""
    };
    
    appendObjectToSheet("Class_Management", newClass);
    return { status: "SUCCESS", classroomId: classroomId };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

// ============================================================================
// TUTOR / MENTOR CONTROLLER
// ============================================================================

/**
 * Fetches all students under tutor's classroom supervision
 */
function getTutorClassroomRoster(tutorMobile) {
  try {
    const classes = getSheetRowsAsObjects("Class_Management");
    let tutorClass = classes.find(c => c.Tutor_Mobile_No === tutorMobile);
    let isMentorOnly = false;
    
    if (!tutorClass) {
      // Check if they are a Mentor in any class
      for (let c of classes) {
        const students = getSheetRowsAsObjects("Students", c.Classroom_ID);
        const hasAssigned = students.some(s => s.Mentor_Mobile_No === tutorMobile);
        if (hasAssigned) {
          tutorClass = c;
          isMentorOnly = true;
          break;
        }
      }
    }
    
    if (!tutorClass) {
      return { status: "ERROR", message: "You are not currently assigned to supervise any classroom." };
    }
    
    let students = getSheetRowsAsObjects("Students", tutorClass.Classroom_ID).filter(s => s.Classroom_ID === tutorClass.Classroom_ID);
    
    if (isMentorOnly) {
      students = students.filter(s => s.Mentor_Mobile_No === tutorMobile);
    }
    
    // Get unique subjects mapped to this classroom
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping", tutorClass.Classroom_ID).filter(m => m.Classroom_ID === tutorClass.Classroom_ID);
    const subjects = mappings.map(m => ({ code: m.Subject_Code, name: m.Subject_Name }));
    
    return {
      status: "SUCCESS",
      classroomId: tutorClass.Classroom_ID,
      batchYear: tutorClass.Batch_Year,
      isClassTutor: !isMentorOnly,
      students: students,
      subjects: subjects
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Checks if a staff member is assigned as a Tutor or Mentor.
 */
function checkStaffSupervisionStatus(staffMobile) {
  try {
    const classes = getSheetRowsAsObjects("Class_Management");
    const isTutor = classes.some(c => c.Tutor_Mobile_No === staffMobile);
    if (isTutor) return { status: "SUCCESS", isSupervisor: true };
    
    for (let c of classes) {
      const students = getSheetRowsAsObjects("Students", c.Classroom_ID);
      const isMentor = students.some(s => s.Mentor_Mobile_No === staffMobile);
      if (isMentor) return { status: "SUCCESS", isSupervisor: true };
    }
    
    return { status: "SUCCESS", isSupervisor: false };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Saves manual mentor assignments to students inside a classroom.
 */
function assignMentorToStudents(classroomId, mentorMobile, selectedRegNos) {
  try {
    const students = getSheetRowsAsObjects("Students", classroomId);
    
    for (let s of students) {
      const reg = s.Reg_No;
      const isSelected = selectedRegNos.includes(reg);
      const currentMentor = s.Mentor_Mobile_No || "";
      
      if (isSelected) {
        if (currentMentor !== mentorMobile) {
          updateObjectInSheet("Students", "Reg_No", reg, { Mentor_Mobile_No: mentorMobile }, classroomId);
        }
      } else {
        if (currentMentor === mentorMobile) {
          updateObjectInSheet("Students", "Reg_No", reg, { Mentor_Mobile_No: "" }, classroomId);
        }
      }
    }
    
    return { status: "SUCCESS", message: "Mentor assignments updated successfully." };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

// ============================================================================
// SUPER ADMIN & SYSTEM-WIDE CONTROLLER
// ============================================================================

/**
 * Get all accounts pending administrator approvals
 */
function getPendingAccounts() {
  try {
    const students = getSheetRowsAsObjects("Students_Registry_Lookup").filter(s => s.Status === "Pending");
    const staff = getSheetRowsAsObjects("Staff_Profiles").filter(s => s.Account_Status === "Pending");
    const allStaff = getSheetRowsAsObjects("Staff_Profiles").filter(s => s.Account_Status === "Approved");
    
    // Map full profiles for pending students
    const detailedStudents = students.map(s => {
      const batchStudents = getSheetRowsAsObjects("Students", s.Classroom_ID);
      const profile = batchStudents.find(bs => bs.Reg_No.toUpperCase() === s.Reg_No.toUpperCase()) || {};
      return {
        Reg_No: s.Reg_No,
        Adm_No: s.Adm_No,
        Name: profile.Name || "Student",
        Email: s.Email,
        Branch: profile.Branch || extractBranchFromClassroom(s.Classroom_ID),
        Classroom_ID: s.Classroom_ID,
        Photo_Drive_Link: profile.Photo_Drive_Link || "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150",
        Status: s.Status
      };
    });
    
    return {
      status: "SUCCESS",
      students: detailedStudents,
      staff: staff,
      allApprovedStaff: allStaff
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetch latest 200 system activity logs
 */
function getSystemLogs() {
  try {
    const logs = getSheetRowsAsObjects("Test_Logs");
    // Sort reverse chronological
    logs.sort((a, b) => new Date(b.Timestamp) - new Date(a.Timestamp));
    return { status: "SUCCESS", logs: logs.slice(0, 200) };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetch staff records registered in HOD's branch, including mapped inter-branch faculty
 */
function getHODStaffRoster(branchCode) {
  try {
    const staff = getSheetRowsAsObjects("Staff_Profiles");
    const nativeStaff = staff.filter(s => s.Branch === branchCode);
    
    // Check inter-branch mappings
    const assignments = getSheetRowsAsObjects("Staff_Branch_Assignment");
    const assignedMobiles = assignments
      .filter(a => a.Branch_Code && a.Branch_Code.toUpperCase() === branchCode.toUpperCase())
      .map(a => a.Staff_Mobile);
    
    const interStaff = staff.filter(s => assignedMobiles.indexOf(s.Mobile_No) !== -1);
    
    const combined = [...nativeStaff, ...interStaff];
    const unique = [];
    const seen = {};
    combined.forEach(s => {
      if (!seen[s.Mobile_No]) {
        seen[s.Mobile_No] = true;
        unique.push(s);
      }
    });
    
    return { status: "SUCCESS", staff: unique };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Initializes a new student batch, creating folders & spreadsheet setup in Drive
 */
function initializeBatchDatabase(branchCode, batchYear) {
  try {
    const cleanBranch = branchCode.toString().trim().toUpperCase();
    const cleanBatch = batchYear.toString().trim();
    
    // Create the batch spreadsheet file dynamically on Google Drive (triggers getBatchSpreadsheetId)
    const ssId = getBatchSpreadsheetId(cleanBranch, cleanBatch);
    const classroomId = `${cleanBranch}_${cleanBatch}`;
    
    // Register in global Class_Management
    const classes = getSheetRowsAsObjects("Class_Management");
    const exists = classes.some(c => c.Classroom_ID.toUpperCase() === classroomId.toUpperCase());
    
    if (!exists) {
      appendObjectToSheet("Class_Management", {
        Classroom_ID: classroomId,
        Branch: cleanBranch,
        Batch_Year: cleanBatch,
        Tutor_Mobile_No: "",
        Mentor_Mobile_No: ""
      });
    }
    
    return { 
      status: "SUCCESS", 
      message: `Batch database spreadsheet EL_${cleanBatch} created and registered successfully on Google Drive.` 
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * HOD Action: Assigns Tutor and Mentor to a classroom batch
 */
function assignTutorAndMentor(classroomId, tutorMobile, mentorMobile) {
  try {
    return updateObjectInSheet("Class_Management", "Classroom_ID", classroomId, {
      Tutor_Mobile_No: tutorMobile,
      Mentor_Mobile_No: mentorMobile
    });
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Admin/HOD Action: Link an inter-branch faculty member to teach in a branch
 */
function assignStaffToBranch(staffMobile, branchCode) {
  try {
    const cleanBranch = branchCode.toUpperCase();
    const assignments = getSheetRowsAsObjects("Staff_Branch_Assignment");
    const duplicate = assignments.some(a => a.Staff_Mobile === staffMobile && a.Branch_Code.toUpperCase() === cleanBranch);
    if (duplicate) {
      return { status: "ERROR", message: "This staff member is already assigned to this branch." };
    }
    
    const newAssignment = {
      Assignment_ID: "ASSIGN_" + Date.now(),
      Staff_Mobile: staffMobile,
      Branch_Code: cleanBranch
    };
    return appendObjectToSheet("Staff_Branch_Assignment", newAssignment);
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Admin/HOD Action: Remove an inter-branch faculty assignment
 */
function removeStaffFromBranch(staffMobile, branchCode) {
  try {
    const assignments = getSheetRowsAsObjects("Staff_Branch_Assignment");
    const record = assignments.find(a => a.Staff_Mobile === staffMobile && a.Branch_Code.toUpperCase() === branchCode.toUpperCase());
    if (!record) {
      return { status: "ERROR", message: "Assignment not found." };
    }
    return deleteObjectFromSheet("Staff_Branch_Assignment", "Assignment_ID", record.Assignment_ID);
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Returns approved students inside a classroom container
 */
function getStudentsInClass(classroomId) {
  try {
    const students = getSheetRowsAsObjects("Students", classroomId).filter(s => s.Classroom_ID === classroomId && s.Status === "Approved");
    return { status: "SUCCESS", students: students };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches institutional configurations (Vision, Mission, etc.)
 */
function getInstitutionConfig() {
  try {
    const rows = getSheetRowsAsObjects("Institution_Config");
    const config = {};
    rows.forEach(r => {
      if (r.Config_Key) config[r.Config_Key] = r.Config_Value;
    });
    return { status: "SUCCESS", config: config };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Saves or updates institutional configurations (Vision, Mission, etc.)
 */
function saveInstitutionConfig(config) {
  try {
    for (let key in config) {
      const success = updateObjectInSheet("Institution_Config", "Config_Key", key, { Config_Value: config[key] });
      if (success.count === 0) {
        appendObjectToSheet("Institution_Config", { Config_Key: key, Config_Value: config[key] });
      }
    }
    return { status: "SUCCESS", message: "Institution Config updated successfully." };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches all defined Program Outcome (PO) descriptions
 */
function getPOConfigs() {
  try {
    const rows = getSheetRowsAsObjects("PO_Config");
    return { status: "SUCCESS", pos: rows };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Updates description benchmarks for PO1 - PO12
 */
function savePOConfigs(configs) {
  try {
    configs.forEach(po => {
      const success = updateObjectInSheet("PO_Config", "PO_ID", po.PO_ID, { Description: po.Description });
      if (success.count === 0) {
        appendObjectToSheet("PO_Config", { PO_ID: po.PO_ID, PO_Name: po.PO_ID, Description: po.Description });
      }
    });
    return { status: "SUCCESS", message: "Program Outcomes descriptions saved." };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches specific branchVision, Mission, PEOs, PSOs
 */
function getBranchConfig(branchCode) {
  try {
    const rows = getSheetRowsAsObjects("Branch_Config");
    const config = rows.find(r => r.Branch_Code === branchCode) || { Branch_Code: branchCode, Vision: "", Mission: "", PEOs: "[]", PSOs: "[]" };
    return { status: "SUCCESS", config: config };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Saves Vision, Mission, PEOs, and PSOs for a department
 */
function saveBranchConfig(branchCode, configData) {
  try {
    const rows = getSheetRowsAsObjects("Branch_Config");
    const exist = rows.find(r => r.Branch_Code === branchCode);
    const updateData = {
      Vision: configData.Vision,
      Mission: configData.Mission,
      PEOs: typeof configData.PEOs === 'string' ? configData.PEOs : JSON.stringify(configData.PEOs || []),
      PSOs: typeof configData.PSOs === 'string' ? configData.PSOs : JSON.stringify(configData.PSOs || [])
    };
    
    if (exist) {
      updateObjectInSheet("Branch_Config", "Branch_Code", branchCode, updateData);
    } else {
      updateData.Branch_Code = branchCode;
      appendObjectToSheet("Branch_Config", updateData);
    }
    return { status: "SUCCESS", message: "Branch OBE Setup updated successfully." };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Saves unified assessment marks (Series, Assignments, Labs, Drawings, Projects, Seminars)
 */
function saveAcademicMarks(classroomId, subjectCode, courseType, assessmentCategory, assessmentName, coTag, maxMarks, marksGrid, facultyMobile) {
  try {
    const existing = getSheetRowsAsObjects("Academic_Marks");
    let inserted = 0;
    let updated = 0;
    
    marksGrid.forEach(m => {
      const duplicate = existing.find(em => 
        em.Reg_No === m.regNo &&
        em.Classroom_ID === classroomId &&
        em.Subject_Code === subjectCode &&
        em.Assessment_Category === assessmentCategory &&
        em.Assessment_Name === assessmentName &&
        em.CO_Tag === coTag
      );
      
      const markObj = {
        Reg_No: m.regNo,
        Classroom_ID: classroomId,
        Subject_Code: subjectCode,
        Course_Type: courseType,
        Assessment_Category: assessmentCategory,
        Assessment_Name: assessmentName,
        CO_Tag: coTag,
        Max_Marks: maxMarks.toString(),
        Marks_Obtained: m.marks.toString(),
        Entered_By: facultyMobile,
        Timestamp: new Date().toISOString()
      };
      
      if (duplicate) {
        updateObjectInSheet("Academic_Marks", "Mark_ID", duplicate.Mark_ID, {
          Marks_Obtained: m.marks.toString(),
          Entered_By: facultyMobile,
          Timestamp: new Date().toISOString()
        });
        updated++;
      } else {
        markObj.Mark_ID = "AM_" + Date.now() + "_" + Math.floor(1000 + Math.random() * 9000);
        appendObjectToSheet("Academic_Marks", markObj);
        inserted++;
      }
    });
    return { status: "SUCCESS", message: `Academic Marks updated: ${updated}, inserted: ${inserted}` };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Logs a completed class hour topic and batch uploads student attendance logs
 */
function saveAttendanceAndClassLog(classroomId, subjectCode, date, hour, topicCovered, remarks, attendanceGrid, lessonPlanPlanId) {
  try {
    const logObj = {
      Log_ID: "CLOG_" + Date.now() + "_" + Math.floor(100 + Math.random() * 900),
      Classroom_ID: classroomId,
      Subject_Code: subjectCode,
      Date: date,
      Hour: hour,
      Topic_Covered: topicCovered,
      Remarks: remarks || ""
    };
    appendObjectToSheet("Class_Logs", logObj);
    
    attendanceGrid.forEach(att => {
      const attObj = {
        Log_ID: "ATT_" + Date.now() + "_" + Math.floor(1000 + Math.random() * 9000),
        Reg_No: att.regNo,
        Classroom_ID: classroomId,
        Subject_Code: subjectCode,
        Date: date,
        Hour: hour,
        Status: att.status
      };
      appendObjectToSheet("Attendance_Logs", attObj);
    });
    
    if (lessonPlanPlanId) {
      updateObjectInSheet("Lesson_Plans", "Plan_ID", lessonPlanPlanId, { Status: "Completed" });
    }
    
    return { status: "SUCCESS", message: "Class log and student attendance registered." };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Compiles student specific attendance stats and session details
 */
function getStudentAttendanceAndLogs(regNo, classroomId) {
  try {
    if (!classroomId && regNo) {
      const student = getSheetRowsAsObjects("Students").find(s => s.Reg_No === regNo);
      if (student) {
        classroomId = student.Classroom_ID;
      }
    }
    const attendance = getSheetRowsAsObjects("Attendance_Logs").filter(a => a.Reg_No === regNo);
    const logs = getSheetRowsAsObjects("Class_Logs").filter(l => l.Classroom_ID === classroomId);
    
    const subjectMetrics = {};
    attendance.forEach(a => {
      if (!subjectMetrics[a.Subject_Code]) {
        subjectMetrics[a.Subject_Code] = { present: 0, total: 0 };
      }
      subjectMetrics[a.Subject_Code].total++;
      if (a.Status === "Present") {
        subjectMetrics[a.Subject_Code].present++;
      }
    });
    
    return {
      status: "SUCCESS",
      metrics: subjectMetrics,
      logs: logs
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Compiles a comprehensive student progress portfolio including subject attendance,
 * class log topics, and outcome scores.
 */
function getStudentAcademicProgress(regNo) {
  try {
    const students = getSheetRowsAsObjects("Students");
    const student = students.find(s => s.Reg_No === regNo);
    if (!student) return { status: "ERROR", message: "Student not found." };
    
    const classroomId = student.Classroom_ID;
    
    // 1. Get subjects mapped to classroom
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping").filter(m => m.Classroom_ID === classroomId);
    
    // 2. Fetch attendance logs and class logs
    const attendance = getSheetRowsAsObjects("Attendance_Logs").filter(a => a.Reg_No === regNo);
    const classLogs = getSheetRowsAsObjects("Class_Logs").filter(l => l.Classroom_ID === classroomId);
    
    // Compile attendance metrics per subject
    const subjectMetrics = {};
    mappings.forEach(m => {
      subjectMetrics[m.Subject_Code] = { present: 0, total: 0, name: m.Subject_Name, type: m.Course_Type || "Theory" };
    });
    
    attendance.forEach(a => {
      if (subjectMetrics[a.Subject_Code]) {
        subjectMetrics[a.Subject_Code].total++;
        if (a.Status === "Present") {
          subjectMetrics[a.Subject_Code].present++;
        }
      }
    });
    
    // 3. Compile outcome/score data for each subject
    const subjectScores = {};
    mappings.forEach(m => {
      const ptmReport = getStudentPTMReport(regNo, m.Subject_Code);
      if (ptmReport.status === "SUCCESS") {
        subjectScores[m.Subject_Code] = {
          onlineQuiz: ptmReport.onlineQuiz,
          academicMarks: ptmReport.academicMarks,
          coPercentages: ptmReport.coPercentages
        };
      } else {
        subjectScores[m.Subject_Code] = {
          onlineQuiz: { CO1: 0, CO2: 0, CO3: 0, CO4: 0 },
          academicMarks: [],
          coPercentages: { CO1: 0, CO2: 0, CO3: 0, CO4: 0 }
        };
      }
    });
    
    return {
      status: "SUCCESS",
      classroomId: classroomId,
      branch: student.Branch,
      semester: student.Semester,
      student: {
        name: student.Name,
        regNo: student.Reg_No,
        photo: student.Photo_Drive_Link
      },
      metrics: subjectMetrics,
      logs: classLogs,
      scores: subjectScores,
      attendance: attendance
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Saves custom CO-to-PO weights (0-3 correlation scale) for a subject
 */
function saveCOPOMappings(classroomId, subjectCode, mappings) {
  try {
    const existing = getSheetRowsAsObjects("CO_PO_Mapping").filter(m => 
      m.Classroom_ID === classroomId && m.Subject_Code === subjectCode
    );
    
    mappings.forEach(mapObj => {
      const duplicate = existing.find(em => em.CO === mapObj.CO);
      const updateData = {
        PO1: mapObj.PO1.toString(), PO2: mapObj.PO2.toString(), PO3: mapObj.PO3.toString(),
        PO4: mapObj.PO4.toString(), PO5: mapObj.PO5.toString(), PO6: mapObj.PO6.toString(),
        PO7: mapObj.PO7.toString(), PO8: mapObj.PO8.toString(), PO9: mapObj.PO9.toString(),
        PO10: mapObj.PO10.toString(), PO11: mapObj.PO11.toString(), PO12: mapObj.PO12.toString()
      };
      
      if (duplicate) {
        updateObjectInSheet("CO_PO_Mapping", "Mapping_ID", duplicate.Mapping_ID, updateData);
      } else {
        const newMap = {
          Mapping_ID: "COPOMAP_" + Date.now() + "_" + Math.floor(100 + Math.random() * 900),
          Classroom_ID: classroomId,
          Subject_Code: subjectCode,
          CO: mapObj.CO,
          ...updateData
        };
        appendObjectToSheet("CO_PO_Mapping", newMap);
      }
    });
    return { status: "SUCCESS", message: "CO-PO Mapping config saved." };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches mapped CO-PO weights
 */
function getCOPOMappings(classroomId, subjectCode) {
  try {
    const rows = getSheetRowsAsObjects("CO_PO_Mapping").filter(m => 
      m.Classroom_ID === classroomId && m.Subject_Code === subjectCode
    );
    return { status: "SUCCESS", mappings: rows };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches custom subject outcome attainment thresholds
 */
function getSubjectAttainmentThreshold(classroomId, subjectCode) {
  try {
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping");
    const match = mappings.find(m => m.Classroom_ID === classroomId && m.Subject_Code === subjectCode);
    const threshold = match ? (parseFloat(match.Attainment_Threshold) || 50) : 50;
    return { status: "SUCCESS", threshold: threshold };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Saves custom subject outcome attainment thresholds
 */
function updateSubjectAttainmentThreshold(classroomId, subjectCode, threshold) {
  try {
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping");
    const match = mappings.find(m => m.Classroom_ID === classroomId && m.Subject_Code === subjectCode);
    if (match) {
      updateObjectInSheet("Subject_Faculty_Mapping", "Mapping_ID", match.Mapping_ID, { Attainment_Threshold: threshold.toString() });
      return { status: "SUCCESS", message: "Attainment threshold updated." };
    }
    return { status: "ERROR", message: "Subject allocation not found." };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches planned syllabus topics
 */
function getLessonPlans(classroomId, subjectCode) {
  try {
    const rows = getSheetRowsAsObjects("Lesson_Plans").filter(l => 
      l.Classroom_ID === classroomId && l.Subject_Code === subjectCode
    );
    return { status: "SUCCESS", plans: rows };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Appends planned syllabus topic row
 */
function saveLessonPlan(planData) {
  try {
    const planObj = {
      Plan_ID: "LP_" + Date.now() + "_" + Math.floor(100 + Math.random() * 900),
      Classroom_ID: planData.classroomId,
      Subject_Code: planData.subjectCode,
      Unit_No: planData.unitNo,
      Topic: planData.topic,
      Planned_Hours: planData.hours.toString(),
      Status: "Planned"
    };
    appendObjectToSheet("Lesson_Plans", planObj);
    return { status: "SUCCESS", message: "Lesson plan row added." };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Records counseling remark / meeting notes inside Tutor Diary
 */
function saveDiaryEntry(entry) {
  try {
    const diaryObj = {
      Diary_ID: "DIARY_" + Date.now() + "_" + Math.floor(100 + Math.random() * 900),
      Classroom_ID: entry.classroomId,
      Reg_No: entry.regNo,
      Date: entry.date || new Date().toISOString().substring(0, 10),
      Category: entry.category,
      Discussion_Notes: entry.notes,
      Action_Taken: entry.actionTaken || "",
      Remarks: entry.remarks || "",
      Logged_By: entry.loggedBy
    };
    appendObjectToSheet("Tutor_Diary", diaryObj);
    return { status: "SUCCESS", message: "Mentoring diary entry saved." };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches behavioral counseling history
 */
function getDiaryEntries(classroomId, regNo) {
  try {
    let rows = getSheetRowsAsObjects("Tutor_Diary");
    if (classroomId) {
      rows = rows.filter(r => r.Classroom_ID === classroomId);
    }
    if (regNo) {
      rows = rows.filter(r => r.Reg_No === regNo);
    }
    return { status: "SUCCESS", entries: rows };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Batch imports an array of questions into the bank
 */
function importQuestions(questionsArray) {
  try {
    let imported = 0;
    questionsArray.forEach(q => {
      const qId = "Q_" + Date.now() + "_" + Math.floor(1000 + Math.random() * 9000 + imported);
      const newQ = {
        Subject_Code: q.Subject_Code,
        Question_ID: qId,
        Type: q.Type,
        Question_Text: q.Question_Text,
        Options: typeof q.Options === "string" ? q.Options : JSON.stringify(q.Options || []),
        Correct_Answer: q.Correct_Answer,
        CO_Tag: q.CO_Tag,
        Marks: q.Marks ? q.Marks.toString() : (q.Type === "MCQ" ? "2" : "5")
      };
      const branchCode = extractBranchFromClassroom(q.Subject_Code);
      appendObjectToSheet("Question_Bank", newQ, branchCode);
      imported++;
    });
    return { status: "SUCCESS", message: `${imported} questions successfully added to the bank.` };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Saves a new model exam configuration to cumulative branch sheet.
 */
function saveModelExamConfig(subjectCode, examName, configObj) {
  try {
    const branchCode = extractBranchFromClassroom(subjectCode);
    const examId = "MEXAM_" + Date.now();
    const newConfig = {
      Exam_ID: examId,
      Subject_Code: subjectCode,
      Exam_Name: examName,
      Config_JSON: JSON.stringify(configObj)
    };
    appendObjectToSheet("Model_Exams_Configs", newConfig, branchCode);
    return { status: "SUCCESS", message: "Model Exam Configuration saved successfully!", examId: examId };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches model exam configurations for a subject.
 */
function getModelExamConfigs(subjectCode) {
  try {
    const branchCode = extractBranchFromClassroom(subjectCode);
    const configs = getSheetRowsAsObjects("Model_Exams_Configs", branchCode);
    const filtered = configs.filter(c => c.Subject_Code === subjectCode);
    return { status: "SUCCESS", configs: filtered };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetches practice model exams configured for a student's enrolled subjects.
 */
function getPracticeExamsForStudent(studentRegNo) {
  try {
    const studentsLookup = getSheetRowsAsObjects("Students_Registry_Lookup");
    const student = studentsLookup.find(s => s.Reg_No.toUpperCase() === studentRegNo.toUpperCase() || s.Adm_No.toUpperCase() === studentRegNo.toUpperCase());
    if (!student) return { status: "ERROR", message: "Student not found in registry." };
    const branchCode = extractBranchFromClassroom(student.Classroom_ID);
    
    const configs = getSheetRowsAsObjects("Model_Exams_Configs", branchCode);
    const batchSubjects = getSheetRowsAsObjects("Subject_Faculty_Mapping", student.Classroom_ID);
    const assignedSubjectCodes = batchSubjects.map(s => s.Subject_Code);
    
    const practiceExams = configs.filter(c => assignedSubjectCodes.includes(c.Subject_Code));
    
    return {
      status: "SUCCESS",
      exams: practiceExams.map(c => ({
        examId: c.Exam_ID,
        examName: c.Exam_Name,
        subjectCode: c.Subject_Code
      }))
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Generates questions for a practice model exam by querying the Question Bank.
 */
function loadPracticeExamQuestions(regNo, examId) {
  try {
    const studentsLookup = getSheetRowsAsObjects("Students_Registry_Lookup");
    const student = studentsLookup.find(s => s.Reg_No.toUpperCase() === regNo.toUpperCase() || s.Adm_No.toUpperCase() === regNo.toUpperCase());
    if (!student) return { status: "ERROR", message: "Student lookup not found." };
    const branchCode = extractBranchFromClassroom(student.Classroom_ID);
    
    const configs = getSheetRowsAsObjects("Model_Exams_Configs", branchCode);
    const config = configs.find(c => c.Exam_ID === examId);
    if (!config) return { status: "ERROR", message: "Model exam configuration not found." };
    
    const configObj = JSON.parse(config.Config_JSON);
    const qb = getSheetRowsAsObjects("Question_Bank", branchCode).filter(q => q.Subject_Code === config.Subject_Code);
    
    const paperQuestions = [];
    const sections = ["PartA", "PartB", "PartC"];
    
    sections.forEach(section => {
      const sectConf = configObj[section];
      if (sectConf && sectConf.count > 0) {
        const targetMarks = sectConf.marks.toString();
        const allowedCOs = sectConf.coTags || ["CO1", "CO2", "CO3", "CO4"];
        
        let candidates = qb.filter(q => 
          q.Marks.toString() === targetMarks && 
          allowedCOs.includes(q.CO_Tag)
        );
        
        candidates = shuffleArray(candidates);
        const selected = candidates.slice(0, parseInt(sectConf.count));
        
        selected.forEach(q => {
          paperQuestions.push({
            section: section,
            questionId: q.Question_ID,
            type: q.Type,
            questionText: q.Question_Text,
            options: q.Type === "MCQ" ? JSON.parse(q.Options || "[]") : [],
            coTag: q.CO_Tag,
            marks: q.Marks
          });
        });
      }
    });
    
    return {
      status: "SUCCESS",
      examName: config.Exam_Name,
      subjectCode: config.Subject_Code,
      questions: paperQuestions
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Grades descriptive and MCQ responses instantly for practice tests and returns feedback.
 */
function submitPracticeExam(regNo, examId, answersGrid) {
  try {
    const studentsLookup = getSheetRowsAsObjects("Students_Registry_Lookup");
    const student = studentsLookup.find(s => s.Reg_No.toUpperCase() === regNo.toUpperCase() || s.Adm_No.toUpperCase() === regNo.toUpperCase());
    if (!student) return { status: "ERROR", message: "Student not found." };
    const branchCode = extractBranchFromClassroom(student.Classroom_ID);
    
    const questionBank = getSheetRowsAsObjects("Question_Bank", branchCode);
    const gradedAnswers = [];
    let totalScore = 0;
    let maxPossibleScore = 0;
    
    answersGrid.forEach(ans => {
      const q = questionBank.find(qb => qb.Question_ID === ans.questionId);
      if (!q) return;
      
      const qMarks = parseInt(q.Marks) || (q.Type === "MCQ" ? 2 : 5);
      maxPossibleScore += qMarks;
      
      let obtained = 0;
      let feedback = "";
      
      if (q.Type === "MCQ") {
        const isCorrect = q.Correct_Answer.trim().toUpperCase() === ans.selectedOption.trim().toUpperCase();
        if (isCorrect) {
          obtained = qMarks;
          feedback = "Correct MCQ Answer!";
        } else {
          feedback = `Incorrect. The correct option is: ${q.Correct_Answer}`;
        }
      } else {
        const aiResult = evaluateAnswerWithAI(q.Question_Text, q.Correct_Answer, ans.descriptiveText);
        obtained = Math.round((aiResult.marks / 10) * qMarks);
        feedback = aiResult.feedback;
      }
      
      totalScore += obtained;
      gradedAnswers.push({
        questionText: q.Question_Text,
        type: q.Type,
        obtained: obtained,
        maxMarks: qMarks,
        feedback: feedback,
        studentAnswer: q.Type === "MCQ" ? ans.selectedOption : ans.descriptiveText
      });
    });
    
    return {
      status: "SUCCESS",
      score: totalScore,
      maxScore: maxPossibleScore,
      details: gradedAnswers
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * NSS, NCC, Sports, Physical Ed, and IEDC activity point logging.
 */
function logExtracurricularActivity(activityData) {
  try {
    const regNo = activityData.regNo.trim().toUpperCase();
    const studentsLookup = getSheetRowsAsObjects("Students_Registry_Lookup");
    const student = studentsLookup.find(s => s.Reg_No.toUpperCase() === regNo || s.Adm_No.toUpperCase() === regNo);
    if (!student) return { status: "ERROR", message: "Student register/admission number not found." };
    
    const activityObj = {
      Activity_ID: "ACT_" + Date.now(),
      Reg_No: student.Reg_No,
      Club: activityData.club,
      Activity_Name: activityData.activityName,
      Participation_Details: activityData.details || "",
      Points: activityData.points ? activityData.points.toString() : "0",
      Approved_By: activityData.loggedBy,
      Timestamp: new Date().toISOString()
    };
    
    appendObjectToSheet("Extracurricular_Activities", activityObj, student.Classroom_ID);
    return { status: "SUCCESS", message: `Extracurricular point logged successfully for ${student.Reg_No}!` };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Placements cell internship, seminar, and selection records.
 */
function logPlacementRecord(placementData) {
  try {
    const regNo = placementData.regNo.trim().toUpperCase();
    const studentsLookup = getSheetRowsAsObjects("Students_Registry_Lookup");
    const student = studentsLookup.find(s => s.Reg_No.toUpperCase() === regNo || s.Adm_No.toUpperCase() === regNo);
    if (!student) return { status: "ERROR", message: "Student register/admission number not found." };
    
    const recordObj = {
      Record_ID: "PLACE_" + Date.now(),
      Reg_No: student.Reg_No,
      Type: placementData.type,
      Company_Or_Institution: placementData.company,
      Details: placementData.details || "",
      Date: placementData.date || new Date().toISOString().substring(0, 10),
      Status: placementData.status || "Selected"
    };
    
    appendObjectToSheet("Placements_&_Training", recordObj, student.Classroom_ID);
    return { status: "SUCCESS", message: `Placement/Training record registered for ${student.Reg_No}!` };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Fetch placement records and extracurricular points for student dashboards.
 */
function getStudentSpecialActivities(regNo) {
  try {
    const studentsLookup = getSheetRowsAsObjects("Students_Registry_Lookup");
    const student = studentsLookup.find(s => s.Reg_No.toUpperCase() === regNo.toUpperCase() || s.Adm_No.toUpperCase() === regNo.toUpperCase());
    if (!student) return { status: "ERROR", message: "Student not found." };
    
    const activities = getSheetRowsAsObjects("Extracurricular_Activities", student.Classroom_ID).filter(a => a.Reg_No.toUpperCase() === student.Reg_No.toUpperCase());
    const placements = getSheetRowsAsObjects("Placements_&_Training", student.Classroom_ID).filter(p => p.Reg_No.toUpperCase() === student.Reg_No.toUpperCase());
    
    return {
      status: "SUCCESS",
      activities: activities,
      placements: placements
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Admin utility to wipe caching properties and refresh schemas
 */
function forceReinitializeDatabase() {
  try {
    PropertiesService.getScriptProperties().deleteProperty("DB_INITIALIZED");
    initializeDatabase();
    return { status: "SUCCESS", message: "Database re-initialized and standard configurations re-populated!" };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Helper to return the script's published web app URL.
 */
function getScriptUrl() {
  try {
    return ScriptApp.getService().getUrl();
  } catch (err) {
    // Return empty or fallback for local mock
    return "";
  }
}

/**
 * Fetches all college-wide data for the Principal Dashboard.
 */
function getPrincipalDashboardData() {
  try {
    const pending = getPendingAccounts();
    const logs = getSystemLogs();
    
    // Get all classrooms
    const classes = getSheetRowsAsObjects("Class_Management");
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping");
    const staff = getSheetRowsAsObjects("Staff_Profiles");
    
    // Format classrooms with tutors and mapped subjects
    const formattedClasses = classes.map(c => {
      const tutor = staff.find(s => s.Mobile_No === c.Tutor_Mobile_No);
      const mentor = staff.find(s => s.Mobile_No === c.Mentor_Mobile_No);
      const subjects = mappings.filter(m => m.Classroom_ID === c.Classroom_ID).map(m => {
        const fac = staff.find(s => s.Mobile_No === m.Faculty_Mobile_No);
        return {
          code: m.Subject_Code,
          name: m.Subject_Name,
          facultyName: fac ? fac.Name : m.Faculty_Mobile_No,
          courseType: m.Course_Type || "Theory"
        };
      });
      
      return {
        classroomId: c.Classroom_ID,
        branch: c.Branch,
        batchYear: c.Batch_Year,
        tutorMobile: c.Tutor_Mobile_No || "",
        mentorMobile: c.Mentor_Mobile_No || "",
        tutorName: tutor ? tutor.Name : "Unassigned",
        mentorName: mentor ? mentor.Name : "Unassigned",
        subjects: subjects
      };
    });
    
    return {
      status: "SUCCESS",
      pendingStudents: pending.students || [],
      pendingStaff: pending.staff || [],
      allApprovedStaff: pending.allApprovedStaff || [],
      logs: logs.logs || [],
      classes: formattedClasses
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}


