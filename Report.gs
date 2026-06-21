// ============================================================================
// CARMEL LINX - OBE ATTAINMENT & REPORT GENERATION SERVICE (Report.gs)
// ============================================================================

/**
 * Calculates the average percentage score a student has achieved for each CO in a subject.
 * Combines:
 * 1. Online Quizzes (Student_Responses)
 * 2. Academic Marks (Series Tests, Assignments, Labs, Seminar, Projects, Drawings)
 * Returns percentages (0-100) for CO1, CO2, CO3, CO4.
 */
function getStudentBestCOPercentages(regNo, subjectCode) {
  try {
    const responses = getSheetRowsAsObjects("Student_Responses");
    const questionBank = getSheetRowsAsObjects("Question_Bank");
    const testConfigs = getSheetRowsAsObjects("Test_Config");
    const academicMarks = getSheetRowsAsObjects("Academic_Marks");
    
    // 1. ONLINE QUIZ CALCULATIONS PER CO
    const subjectTests = testConfigs.filter(t => t.Subject_Code === subjectCode);
    const subjectTestIds = subjectTests.map(t => t.Test_ID);
    const studentQuizResp = responses.filter(r => r.Reg_No === regNo && subjectTestIds.includes(r.Test_ID));
    
    const quizSums = { CO1: 0, CO2: 0, CO3: 0, CO4: 0 };
    const quizPossibles = { CO1: 0, CO2: 0, CO3: 0, CO4: 0 };
    
    studentQuizResp.forEach(resp => {
      const q = questionBank.find(qb => qb.Question_ID === resp.Question_ID);
      if (q && quizSums[q.CO_Tag] !== undefined) {
        const marks = parseFloat(resp.Marks_Obtained) || 0;
        const maxMark = q.Type === "MCQ" ? 1 : 10;
        quizSums[q.CO_Tag] += marks;
        quizPossibles[q.CO_Tag] += maxMark;
      }
    });
    
    // Convert to percentages
    const quizPcts = { CO1: -1, CO2: -1, CO3: -1, CO4: -1 };
    for (let co in quizPcts) {
      if (quizPossibles[co] > 0) {
        quizPcts[co] = Math.round((quizSums[co] / quizPossibles[co]) * 100);
      }
    }
    
    // 2. ACADEMIC MARKS CALCULATIONS PER CO (Series, Assignments, Labs, Drawings, Projects, Seminars)
    const studentAcMarks = academicMarks.filter(m => m.Reg_No === regNo && m.Subject_Code === subjectCode);
    
    // Group academic marks by CO
    const acSums = { CO1: 0, CO2: 0, CO3: 0, CO4: 0 };
    const acPossibles = { CO1: 0, CO2: 0, CO3: 0, CO4: 0 };
    
    studentAcMarks.forEach(m => {
      const co = m.CO_Tag;
      if (acSums[co] !== undefined) {
        const marks = parseFloat(m.Marks_Obtained) || 0;
        const max = parseFloat(m.Max_Marks) || 50;
        acSums[co] += marks;
        acPossibles[co] += max;
      }
    });
    
    const acPcts = { CO1: -1, CO2: -1, CO3: -1, CO4: -1 };
    for (let co in acPcts) {
      if (acPossibles[co] > 0) {
        acPcts[co] = Math.round((acSums[co] / acPossibles[co]) * 100);
      }
    }
    
    // 3. COMBINE COMPONENTS (QUIZ & ACADEMIC)
    const combinedPcts = { CO1: 0, CO2: 0, CO3: 0, CO4: 0 };
    const coAttemptsCount = { CO1: 0, CO2: 0, CO3: 0, CO4: 0 };
    
    for (let co in combinedPcts) {
      const components = [];
      if (quizPcts[co] !== -1) {
        components.push(quizPcts[co]);
        coAttemptsCount[co]++;
      }
      if (acPcts[co] !== -1) {
        components.push(acPcts[co]);
        coAttemptsCount[co]++;
      }
      
      if (components.length > 0) {
        // Take average of the active components
        const sum = components.reduce((a, b) => a + b, 0);
        combinedPcts[co] = Math.round(sum / components.length);
      } else {
        combinedPcts[co] = 0; // Default if no evaluations are entered yet
      }
    }
    
    return {
      percentages: combinedPcts,
      attempts: coAttemptsCount,
      quizPercentages: quizPcts,
      academicPercentages: acPcts
    };
  } catch (err) {
    Logger.log("Error calculating best CO scores: " + err.toString());
    return {
      percentages: { CO1: 0, CO2: 0, CO3: 0, CO4: 0 },
      attempts: { CO1: 0, CO2: 0, CO3: 0, CO4: 0 }
    };
  }
}

/**
 * Compiles a comprehensive PTM Progress Report Card for a student.
 * Combines online tests, manual academic marks, and flags counselor comments.
 */
function getStudentPTMReport(regNo, subjectCode) {
  try {
    const students = getSheetRowsAsObjects("Students");
    const student = students.find(s => s.Reg_No === regNo);
    if (!student) return { status: "ERROR", message: "Student not found." };
    
    const coData = getStudentBestCOPercentages(regNo, subjectCode);
    
    // Fetch unified academic marks for specific display
    const academicMarks = getSheetRowsAsObjects("Academic_Marks").filter(m => 
      m.Reg_No === regNo && m.Subject_Code === subjectCode
    );
    
    // Fetch counseling log comments from Tutor Diary
    const diaryEntries = getSheetRowsAsObjects("Tutor_Diary").filter(d => 
      d.Reg_No === regNo
    );
    
    // Fetch student attendance logs
    const attLogs = getSheetRowsAsObjects("Attendance_Logs").filter(a => 
      a.Reg_No === regNo && a.Subject_Code === subjectCode
    );
    const totalHours = attLogs.length;
    const presentHours = attLogs.filter(a => a.Status === "Present").length;
    const attPct = totalHours > 0 ? Math.round((presentHours / totalHours) * 100) : 100;
    
    return {
      status: "SUCCESS",
      student: {
        regNo: student.Reg_No,
        admNo: student.Adm_No,
        name: student.Name,
        branch: getBranchFullName(student.Branch),
        semester: student.Semester,
        photo: student.Photo_Drive_Link,
        classroom: student.Classroom_ID
      },
      attendance: {
        present: presentHours,
        total: totalHours,
        percentage: attPct
      },
      onlineQuiz: coData.quizPercentages,
      academicMarks: academicMarks,
      counselingLogs: diaryEntries,
      coPercentages: coData.percentages
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Calculates Outcome-Based Education (OBE) Attainment report for a class in a subject.
 * Measures % of students passing a threshold (e.g. 50%) to yield Attainment Levels 1, 2, or 3.
 * Maps calculations to Program Outcomes (POs) as well.
 */
function getSubjectCOAttainment(classroomId, subjectCode, targetThresholdPct) {
  try {
    const students = getSheetRowsAsObjects("Students").filter(s => s.Classroom_ID === classroomId && s.Status === "Approved");
    if (students.length === 0) {
      return { status: "ERROR", message: "No active students registered in classroom " + classroomId };
    }
    
    // Fetch configured threshold from mappings if not provided
    let threshold = parseFloat(targetThresholdPct);
    if (isNaN(threshold)) {
      const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping");
      const match = mappings.find(m => m.Classroom_ID === classroomId && m.Subject_Code === subjectCode);
      threshold = match ? (parseFloat(match.Attainment_Threshold) || 50) : 50;
    }
    
    const totalStudents = students.length;
    const coAttainmentValues = {
      CO1: { passCount: 0, attainmentPct: 0, level: 0 },
      CO2: { passCount: 0, attainmentPct: 0, level: 0 },
      CO3: { passCount: 0, attainmentPct: 0, level: 0 },
      CO4: { passCount: 0, attainmentPct: 0, level: 0 }
    };
    
    // Calculate CO percentages for each student
    const studentGrades = [];
    students.forEach(s => {
      const scoresObj = getStudentBestCOPercentages(s.Reg_No, subjectCode);
      studentGrades.push({
        regNo: s.Reg_No,
        name: s.Name,
        scores: scoresObj.percentages
      });
      
      // Update pass counts
      for (let co in coAttainmentValues) {
        if (scoresObj.percentages[co] >= threshold) {
          coAttainmentValues[co].passCount++;
        }
      }
    });
    
    // Derive attainment level (1, 2, or 3)
    for (let co in coAttainmentValues) {
      const pct = Math.round((coAttainmentValues[co].passCount / totalStudents) * 100);
      coAttainmentValues[co].attainmentPct = pct;
      
      if (pct >= 70) {
        coAttainmentValues[co].level = 3;
      } else if (pct >= 60) {
        coAttainmentValues[co].level = 2;
      } else if (pct >= 50) {
        coAttainmentValues[co].level = 1;
      } else {
        coAttainmentValues[co].level = 0;
      }
    }
    
    // CALCULATE PROGRAM OUTCOMES (PO) ATTAINMENT
    const mappings = getSheetRowsAsObjects("CO_PO_Mapping").filter(m => 
      m.Classroom_ID === classroomId && m.Subject_Code === subjectCode
    );
    
    const poAttainmentSummary = {};
    for (let i = 1; i <= 12; i++) {
      poAttainmentSummary["PO" + i] = { weightedSum: 0, correlationWeightSum: 0, level: 0.0 };
    }
    
    mappings.forEach(mapObj => {
      const co = mapObj.CO;
      const coLevel = coAttainmentValues[co] ? coAttainmentValues[co].level : 0;
      
      for (let i = 1; i <= 12; i++) {
        const poKey = "PO" + i;
        const weight = parseFloat(mapObj[poKey]) || 0; // correlation weight 0 to 3
        if (weight > 0) {
          poAttainmentSummary[poKey].weightedSum += (coLevel * weight);
          poAttainmentSummary[poKey].correlationWeightSum += weight;
        }
      }
    });
    
    // Compute final PO levels (weighted average score)
    const poFinalList = {};
    for (let i = 1; i <= 12; i++) {
      const poKey = "PO" + i;
      const data = poAttainmentSummary[poKey];
      const level = data.correlationWeightSum > 0 ? (data.weightedSum / data.correlationWeightSum) : 0.0;
      poFinalList[poKey] = parseFloat(level.toFixed(2));
    }
    
    return {
      status: "SUCCESS",
      classroomId: classroomId,
      subjectCode: subjectCode,
      totalStudentsCount: totalStudents,
      targetThreshold: threshold,
      attainmentSummary: coAttainmentValues,
      poAttainment: poFinalList,
      studentDetails: studentGrades
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Emails an automated responsive HTML scorecard report directly to the student on submission.
 */
function emailStudentExamReport(regNo, testId) {
  try {
    const students = getSheetRowsAsObjects("Students");
    const student = students.find(s => s.Reg_No === regNo);
    if (!student) return;
    
    const studentEmail = student.Email ? student.Email.trim() : "";
    if (!studentEmail) return;
    
    const testConfigs = getSheetRowsAsObjects("Test_Config");
    const test = testConfigs.find(t => t.Test_ID === testId);
    if (!test) return;
    
    const responses = getSheetRowsAsObjects("Student_Responses");
    const studentResponses = responses.filter(r => r.Reg_No === regNo && r.Test_ID === testId);
    if (studentResponses.length === 0) return;
    
    const questionBank = getSheetRowsAsObjects("Question_Bank");
    
    let totalMCQMarks = 0;
    let totalMCQPossible = 0;
    let totalDescMarks = 0;
    let totalDescPossible = 0;
    let mcqCorrect = 0;
    let mcqTotal = 0;
    
    let tableRowsHtml = "";
    
    studentResponses.forEach(r => {
      const q = questionBank.find(qb => qb.Question_ID === r.Question_ID);
      if (!q) return;
      
      const marks = parseFloat(r.Marks_Obtained) || 0;
      let qType = q.Type;
      let studentAnsText = "";
      let correctAnsText = q.Correct_Answer;
      let maxMarksForQ = 0;
      
      if (qType === "MCQ") {
        maxMarksForQ = 1;
        totalMCQPossible += 1;
        totalMCQMarks += marks;
        mcqTotal++;
        if (marks === 1) mcqCorrect++;
        
        let options = [];
        try { options = JSON.parse(q.Options || "[]"); } catch(e) {}
        
        const selectedIdx = r.Selected_Option.trim().toUpperCase().charCodeAt(0) - 65;
        const correctIdx = q.Correct_Answer.trim().toUpperCase().charCodeAt(0) - 65;
        
        const selectedVal = options[selectedIdx] || r.Selected_Option;
        const correctVal = options[correctIdx] || q.Correct_Answer;
        
        studentAnsText = `Option ${r.Selected_Option}: "${selectedVal}"`;
        correctAnsText = `Option ${q.Correct_Answer}: "${correctVal}"`;
      } else {
        maxMarksForQ = 10;
        totalDescPossible += 10;
        totalDescMarks += marks;
        studentAnsText = r.Descriptive_Text || "(No answer submitted)";
      }
      
      const isCorrectText = (qType === "MCQ") 
        ? (marks === 1 ? `<span style="color:#059669; font-weight:bold;">Correct (+1)</span>` : `<span style="color:#DC2626; font-weight:bold;">Incorrect (0)</span>`)
        : `<span style="color:#2563EB; font-weight:bold;">${marks}/10 Marks</span>`;
        
      tableRowsHtml += `
        <tr style="border-bottom: 1px solid #E2E8F0;">
          <td style="padding: 12px; font-weight: bold; color: #1E293B;">${q.CO_Tag}</td>
          <td style="padding: 12px; color: #334155;">
            <p style="margin: 0; font-weight: bold; color: #1E293B;">${q.Question_Text}</p>
            <p style="margin: 4px 0 0 0; font-size: 11px; color: #64748B;">
              <strong>Your Answer:</strong> ${studentAnsText}<br/>
              <strong>Correct Reference:</strong> ${correctAnsText}
            </p>
          </td>
          <td style="padding: 12px; text-align: center;">${isCorrectText}</td>
        </tr>
      `;
    });
    
    const totalObtained = totalMCQMarks + totalDescMarks;
    const totalPossible = totalMCQPossible + totalDescPossible;
    const percentage = totalPossible > 0 ? Math.round((totalObtained / totalPossible) * 100) : 0;
    
    const passThreshold = parseFloat(test.Pass_Threshold) || 40;
    const isPass = percentage >= passThreshold;
    const statusBadge = isPass 
      ? `<span style="background-color: #DEF7EC; color: #03543F; font-size: 14px; font-weight: 800; padding: 6px 16px; border-radius: 9999px; text-transform: uppercase;">PASS</span>`
      : `<span style="background-color: #FDE8E8; color: #9B1C1C; font-size: 14px; font-weight: 800; padding: 6px 16px; border-radius: 9999px; text-transform: uppercase;">FAIL</span>`;
      
    const emailHtmlBody = `
      <div style="font-family: Arial, sans-serif; background-color: #F8FAFC; padding: 24px; color: #1E293B; max-width: 600px; margin: 0 auto; border-radius: 24px; border: 1px solid #E2E8F0;">
        <div style="text-align: center; border-bottom: 2px solid #E2E8F0; padding-bottom: 16px; margin-bottom: 24px;">
          <h1 style="color: #1E3A8A; font-size: 22px; font-weight: 900; margin: 0;">CARMEL POLYTECHNIC COLLEGE</h1>
          <p style="font-size: 11px; color: #64748B; font-weight: bold; text-transform: uppercase; margin: 4px 0 0 0;">OBE portal - Exam scorecard</p>
        </div>
        <div style="background-color: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 16px; margin-bottom: 24px;">
          <h2 style="font-size: 13px; font-weight: 900; color: #1E293B; margin: 0 0 12px 0; text-transform: uppercase;">Student Details</h2>
          <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <tr><td style="padding: 4px 0; color: #64748B; font-weight: bold;">Name:</td><td style="padding: 4px 0; color: #1E293B; font-weight: bold;">${student.Name}</td></tr>
            <tr><td style="padding: 4px 0; color: #64748B; font-weight: bold;">Reg No:</td><td style="padding: 4px 0; color: #1E293B; font-weight: bold;">${student.Reg_No}</td></tr>
            <tr><td style="padding: 4px 0; color: #64748B; font-weight: bold;">Classroom ID:</td><td style="padding: 4px 0; color: #1E293B; font-weight: bold;">${student.Classroom_ID}</td></tr>
          </table>
        </div>
        <div style="background-color: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 16px; margin-bottom: 24px; text-align: center;">
          <h2 style="font-size: 13px; font-weight: 900; color: #1E293B; margin: 0 0 16px 0; text-transform: uppercase;">Submission Summary</h2>
          <p style="font-size: 15px; font-weight: 800; color: #1E3A8A; margin: 0 0 8px 0;">${test.Test_Name} (${test.Subject_Code})</p>
          <div style="margin: 16px 0;">
            <span style="font-size: 44px; font-weight: 900; color: #2563EB;">${totalObtained}</span>
            <span style="font-size: 16px; color: #64748B; font-weight: bold;"> / ${totalPossible} Marks</span>
          </div>
          <p style="font-size: 13px; font-weight: bold; margin: 0 0 12px 0;">Score Percentage: ${percentage}% | Pass Threshold: ${passThreshold}%</p>
          <div>${statusBadge}</div>
        </div>
        <div style="background-color: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px; padding: 16px; margin-bottom: 24px; overflow-x: auto;">
          <h2 style="font-size: 13px; font-weight: 900; color: #1E293B; margin: 0 0 12px 0; text-transform: uppercase;">Evaluation Breakdown</h2>
          <table style="width: 100%; border-collapse: collapse; font-size: 12px; text-align: left;">
            <thead>
              <tr style="background-color: #F1F5F9; border-bottom: 2px solid #E2E8F0; color: #475569; font-weight: bold;">
                <th style="padding: 8px 12px; width: 40px;">CO</th>
                <th style="padding: 8px 12px;">Question & Answer</th>
                <th style="padding: 8px 12px; text-align: center; width: 90px;">Marks</th>
              </tr>
            </thead>
            <tbody>${tableRowsHtml}</tbody>
          </table>
        </div>
        <div style="text-align: center; font-size: 11px; color: #94A3B8; font-weight: bold; margin-top: 24px;">
          <p style="margin: 0;">Automated scorecard report. Please do not reply directly to this email.</p>
        </div>
      </div>
    `;
    
    MailApp.sendEmail({
      to: studentEmail,
      subject: `Carmel Linx Exam Scorecard: ${test.Test_Name} [${test.Subject_Code}]`,
      htmlBody: emailHtmlBody
    });
    Logger.log(`Automated scorecard email sent to ${studentEmail} for test ${testId}`);
  } catch (err) {
    Logger.log("Error inside emailStudentExamReport: " + err.toString());
  }
}

/**
 * Gathers all datasets for compiling a full Course File printout.
 */
function getCourseFileCompileData(classroomId, subjectCode) {
  try {
    const ss = SpreadsheetApp.getActiveSpreadsheet();
    
    // 1. Vision & Mission (Institution & Branch)
    const instRows = getSheetRowsAsObjects("Institution_Config");
    const instConfig = {};
    instRows.forEach(r => instConfig[r.Config_Key] = r.Config_Value);
    
    // Class metadata (extract branch)
    const classRows = getSheetRowsAsObjects("Class_Management");
    const activeClassObj = classRows.find(c => c.Classroom_ID === classroomId) || { Branch: "Admin" };
    const branchCode = activeClassObj.Branch;
    
    const branchRows = getSheetRowsAsObjects("Branch_Config");
    const branchConfig = branchRows.find(r => r.Branch_Code === branchCode) || { Branch_Code: branchCode, Vision: "-", Mission: "-", PEOs: "[]", PSOs: "[]" };
    
    // 2. Syllabus / Lesson Plan Topics
    const lessonPlans = getSheetRowsAsObjects("Lesson_Plans").filter(l => 
      l.Classroom_ID === classroomId && l.Subject_Code === subjectCode
    );
    
    // 3. Class Log Topic completion list
    const classLogs = getSheetRowsAsObjects("Class_Logs").filter(l => 
      l.Classroom_ID === classroomId && l.Subject_Code === subjectCode
    );
    
    // 4. Attendance Statistics Roster
    const students = getSheetRowsAsObjects("Students").filter(s => 
      s.Classroom_ID === classroomId && s.Status === "Approved"
    );
    
    const attLogs = getSheetRowsAsObjects("Attendance_Logs").filter(a => 
      a.Classroom_ID === classroomId && a.Subject_Code === subjectCode
    );
    
    const attendanceSummary = students.map(s => {
      const sLogs = attLogs.filter(a => a.Reg_No === s.Reg_No);
      const total = sLogs.length;
      const present = sLogs.filter(a => a.Status === "Present").length;
      const pct = total > 0 ? Math.round((present / total) * 100) : 100;
      return {
        regNo: s.Reg_No,
        name: s.Name,
        present: present,
        total: total,
        percentage: pct
      };
    });
    
    // 5. Subject mapping metadata (Threshold and Course Type)
    const mappings = getSheetRowsAsObjects("Subject_Faculty_Mapping");
    const match = mappings.find(m => m.Classroom_ID === classroomId && m.Subject_Code === subjectCode) || {};
    
    // 6. Outcome Attainment Details (CO & PO attainment reports)
    const attainment = getSubjectCOAttainment(classroomId, subjectCode);
    
    return {
      status: "SUCCESS",
      institution: instConfig,
      branch: {
        code: branchCode,
        name: getBranchFullName(branchCode),
        vision: branchConfig.Vision,
        mission: branchConfig.Mission,
        peos: JSON.parse(branchConfig.PEOs || "[]"),
        psos: JSON.parse(branchConfig.PSOs || "[]")
      },
      subject: {
        code: subjectCode,
        name: match.Subject_Name || "Course Name",
        courseType: match.Course_Type || "Theory",
        threshold: match.Attainment_Threshold || "50"
      },
      lessonPlans: lessonPlans,
      classLogs: classLogs,
      attendance: attendanceSummary,
      attainment: attainment.status === "SUCCESS" ? attainment : null
    };
  } catch (err) {
    return { status: "ERROR", message: err.toString() };
  }
}

/**
 * Server-side helper to translate a branch code (abbreviation) to its full title.
 */
function getBranchFullName(branchCode) {
  if (!branchCode) return "";
  const cleanCode = branchCode.toString().trim().toUpperCase();
  const branchMap = {
    "CE": "Civil Engineering",
    "ME": "Mechanical Engineering",
    "EEE": "Electrical & Electronics Engineering",
    "AU": "Automobile Engineering",
    "CT": "Computer Engineering",
    "EL": "Electronics Engineering",
    "ADMIN": "Administration"
  };
  return branchMap[cleanCode] || branchCode;
}
