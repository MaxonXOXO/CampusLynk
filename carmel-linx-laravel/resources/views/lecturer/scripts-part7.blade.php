            </table>`;
        }
        if (data.part_b && data.part_b.q_count > 0) {
          bodyHtml += `
            <h4 style="text-align:center;font-weight:bold;margin:12px 0 4px; font-size:12px;">PART B &nbsp;<small style="font-weight:normal;font-size:11px;">(${data.part_b.q_count} × ${data.part_b.marks_per_q} = ${data.part_b.total_marks} Marks)</small></h4>
            <p style="text-align:center;font-style:italic;font-size:11px;margin:0 0 6px;">Answer all questions.</p>
            <table style="width:100%;border-collapse:collapse;font-size:12px;border:1px solid #000; margin-bottom:10px;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:45px; text-align:center;">Q.No.</th>
                  <th style="border:1px solid #000; padding:4px; text-align:left;">Question</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Module Outcome</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Cognitive Level</th>
                </tr>
              </thead>
              <tbody>${buildRows(data.part_b)}</tbody>
            </table>`;
        }
        if (data.part_c && data.part_c.q_count > 0) {
          bodyHtml += `
            <h4 style="text-align:center;font-weight:bold;margin:12px 0 4px; font-size:12px;">PART C &nbsp;<small style="font-weight:normal;font-size:11px;">(${data.part_c.q_count} × ${data.part_c.marks_per_q} = ${data.part_c.total_marks} Marks)</small></h4>
            <p style="text-align:center;font-style:italic;font-size:11px;margin:0 0 6px;">Answer all questions.</p>
            <table style="width:100%;border-collapse:collapse;font-size:12px;border:1px solid #000; margin-bottom:10px;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:45px; text-align:center;">Q.No.</th>
                  <th style="border:1px solid #000; padding:4px; text-align:left;">Question</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Module Outcome</th>
                  <th style="border:1px solid #000; padding:4px; width:120px; text-align:center;">Cognitive Level</th>
                </tr>
              </thead>
              <tbody>${buildRows(data.part_c)}</tbody>
            </table>`;
        }

        // Calculate Cognitive Level wise Question Analysis
        let counts = {
          A: { R: 0, U: 0, A: 0, total: 0, marksPerQ: data.part_a?.marks_per_q || 1 },
          B: { R: 0, U: 0, A: 0, total: 0, marksPerQ: data.part_b?.marks_per_q || 3 },
          C: { R: 0, U: 0, A: 0, total: 0, marksPerQ: data.part_c?.marks_per_q || 7 }
        };

        if (data.part_a && data.part_a.questions) {
          data.part_a.questions.forEach(q => {
            let lvl = (q.level || 'R').toUpperCase()[0];
            if (counts.A[lvl] !== undefined) counts.A[lvl]++;
            counts.A.total++;
          });
        }
        if (data.part_b && data.part_b.questions) {
          data.part_b.questions.forEach(q => {
            let lvl = (q.level || 'U').toUpperCase()[0];
            if (counts.B[lvl] !== undefined) counts.B[lvl]++;
            counts.B.total++;
          });
        }
        if (data.part_c && data.part_c.questions) {
          data.part_c.questions.forEach(q => {
            let lvl = (q.level || 'A').toUpperCase()[0];
            if (counts.C[lvl] !== undefined) counts.C[lvl]++;
            counts.C.total++;
          });
        }

        let rMarks = (counts.A.R * counts.A.marksPerQ) + (counts.B.R * counts.B.marksPerQ) + (counts.C.R * counts.C.marksPerQ);
        let uMarks = (counts.A.U * counts.A.marksPerQ) + (counts.B.U * counts.B.marksPerQ) + (counts.C.U * counts.C.marksPerQ);
        let aMarks = (counts.A.A * counts.A.marksPerQ) + (counts.B.A * counts.B.marksPerQ) + (counts.C.A * counts.C.marksPerQ);
        let totalCalculatedMarks = rMarks + uMarks + aMarks;

        let cognitiveTableHtml = `
          <div style="margin-top:15px; page-break-inside: avoid;">
            <h4 style="text-align:center; font-weight:bold; margin-bottom:6px; text-decoration: underline; font-size:12px;">Cognitive level wise Question Analysis</h4>
            <table style="width:100%; border:1px solid #000; border-collapse:collapse; font-size:11px; text-align:center;">
              <thead>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; text-align:left;" rowspan="2"></th>
                  <th style="border:1px solid #000; padding:4px;" colspan="3">Cognitive Level</th>
                  <th style="border:1px solid #000; padding:4px;" rowspan="2">No. of Questions</th>
                </tr>
                <tr style="background:#f2f2f2;">
                  <th style="border:1px solid #000; padding:4px; width:150px;">Remember</th>
                  <th style="border:1px solid #000; padding:4px; width:150px;">Understand</th>
                  <th style="border:1px solid #000; padding:4px; width:150px;">Apply</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="border:1px solid #000; padding:4px; text-align:left; font-weight:bold;">Part A (${counts.A.marksPerQ} mark${counts.A.marksPerQ > 1 ? 's' : ''})</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.A.R || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.A.U || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.A.A || '0'}</td>
                  <td style="border:1px solid #000; padding:4px; font-weight:bold;">${counts.A.total || '0'}</td>
                </tr>
                <tr>
                  <td style="border:1px solid #000; padding:4px; text-align:left; font-weight:bold;">Part B (${counts.B.marksPerQ} marks)</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.B.R || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.B.U || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.B.A || '0'}</td>
                  <td style="border:1px solid #000; padding:4px; font-weight:bold;">${counts.B.total || '0'}</td>
                </tr>
                <tr>
                  <td style="border:1px solid #000; padding:4px; text-align:left; font-weight:bold;">Part C (${counts.C.marksPerQ} marks)</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.C.R || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.C.U || '0'}</td>
                  <td style="border:1px solid #000; padding:4px;">${counts.C.A || '0'}</td>
                  <td style="border:1px solid #000; padding:4px; font-weight:bold;">${counts.C.total || '0'}</td>
                </tr>
                <tr style="background-color:#fafafa; font-weight:bold;">
                  <td style="border:1px solid #000; padding:4px; text-align:left;">Marks</td>
                  <td style="border:1px solid #000; padding:4px;">${rMarks}</td>
                  <td style="border:1px solid #000; padding:4px;">${uMarks}</td>
                  <td style="border:1px solid #000; padding:4px;">${aMarks}</td>
                  <td style="border:1px solid #000; padding:4px;">Total Marks = ${totalCalculatedMarks}</td>
                </tr>
              </tbody>
            </table>
          </div>
        `;

        let signatureBlockHtml = `
          <div style="margin-top: 25px; display: flex; justify-content: space-between; font-size: 11px; page-break-inside: avoid; border-top: 1px dashed #000; padding-top: 8px;">
            <div><strong>Prepared By:</strong> ${lecturerName} (Course Coordinator)</div>
            <div><strong>Verified By:</strong> Faculty Name (Module Coordinator)</div>
            <div><strong>Approved By:</strong> HOD</div>
          </div>
        `;

        // Build Scheme of Valuation Rows dynamically
        const buildSchemeRows = () => {
          let rowsHtml = '';
          const processPart = (part, partLabel) => {
            if (!part || !part.questions || part.questions.length === 0) return;
            rowsHtml += `
              <tr style="background: #f2f2f2; font-weight: bold;">
                <td colspan="5" style="border: 1px solid #000; padding: 6px; text-align: left; text-transform: uppercase;">${partLabel}</td>
              </tr>
            `;
            part.questions.forEach((q, i) => {
              let geminiInfo = getGeminiInfo(q.q);
              let rubric = (geminiInfo && geminiInfo.rubric) ? geminiInfo.rubric : (q.rubric || []);
              let answers = (geminiInfo && geminiInfo.ans) ? geminiInfo.ans : (q.ans || []);

              if (rubric.length === 0) {
                let marks = q.marks || 1;
                if (marks <= 2) rubric = [{desc: 'Correct answer / explanation', mark: marks}];
                else rubric = [{desc: 'Key definition / concept', mark: 1}, {desc: 'Correct steps & final answer', mark: marks - 1}];
              }
              let rSpan = rubric.length;

              let answersHtml = '';
              if (answers && answers.length > 0) {
                answersHtml = `<div style="margin-bottom: 6px; font-size: 11px; color: #333;">
                  <strong>Expected Answer Key / Suggestions:</strong>
                  <ul style="margin: 2px 0 4px 14px; padding: 0; list-style-type: disc;">
                    ${answers.map(pt => `<li>${pt}</li>`).join('')}
                  </ul>
                </div>`;
              }

              rubric.forEach((r, rIdx) => {
                rowsHtml += `<tr>`;
                if (rIdx === 0) {
                  rowsHtml += `<td rowspan="${rSpan}" style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold;">${i + 1}</td>`;
                }
                
                let cellContent = '';
                if (rIdx === 0 && answersHtml) {
                  cellContent += answersHtml + `<div style="margin-top: 6px; border-top: 1px dashed #ccc; padding-top: 4px; font-weight: bold; font-size: 11px;">Scoring Indicator Split-up:</div>`;
                }
                cellContent += `<div style="padding-left: 6px; font-size: 11px;">&bull; ${r.desc}</div>`;

                rowsHtml += `
                  <td style="border: 1px solid #000; padding: 6px; vertical-align: top; text-align: left;">${cellContent}</td>
                  <td style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: top; font-weight: bold;">${r.mark}</td>
                `;
                if (rIdx === 0) {
                  rowsHtml += `
                    <td rowspan="${rSpan}" style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold;">${q.marks}</td>
                    <td rowspan="${rSpan}" style="border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; font-weight: bold;">${q.marks}</td>
                  `;
                }
                rowsHtml += `</tr>`;
              });
            });
          };
          processPart(data.part_a, 'Part A');
          processPart(data.part_b, 'Part B');
          processPart(data.part_c, 'Part C');
          return rowsHtml;
        };

        const schemeTableHtml = `
          <div style="page-break-before: always; padding-top: 20px;">
            <div class="header">
              <div class="college-name">CARMEL POLYTECHNIC COLLEGE</div>
              <div class="dept-name">Department of ${deptName}</div>
              <div class="subject-info">${subjectName ? subjectName : 'Subject'} ${subjectCode ? '&nbsp;&mdash;&nbsp;<strong>' + subjectCode + '</strong>' : ''}</div>
              <div style="margin-top:6px;"><span class="exam-title">&nbsp;${coTag} &ndash; SCHEME OF VALUATION&nbsp;</span></div>
              <div class="meta-row" style="margin-top: 8px; font-size: 11px;">
                <span><strong>Semester:</strong> Sem ${currentSubjectSemester}</span>
                <span><strong>Batch:</strong> ${currentSubjectClassroomId.replace(/^[A-Z]+_/, '').replace(/_/g, ' - ')}</span>
                <span><strong>Academic Year:</strong> ${currentSubjectAcademicYear}</span>
              </div>
              <div class="meta-row" style="margin-top: 4px; font-size: 11px;">
                <span><strong>Time:</strong> 1.5 Hours</span>
                <span><strong>Date:</strong> ${examDate}</span>
                <span><strong>Max Marks:</strong> ${totalMarks}</span>
              </div>
            </div>
            <table style="width: 100%; border: 1px solid #000; border-collapse: collapse; font-size: 12px;">
              <thead>
                <tr style="background: #f2f2f2; font-weight: bold;">
                  <th style="border: 1px solid #000; padding: 6px; width: 60px; text-align: center;">Q. No.</th>
                  <th style="border: 1px solid #000; padding: 6px; text-align: left;">Scoring Indicators</th>
                  <th style="border: 1px solid #000; padding: 6px; width: 70px; text-align: center;">Split Up</th>
                  <th style="border: 1px solid #000; padding: 6px; width: 70px; text-align: center;">Sub Total</th>
                  <th style="border: 1px solid #000; padding: 6px; width: 70px; text-align: center;">Total</th>
                </tr>
              </thead>
              <tbody>
                ${buildSchemeRows()}
              </tbody>
            </table>
          </div>
        `;

        const fullHtml = `<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Question Paper - ${coTag}</title>
  <style>
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .transition-premium {
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
    @media (max-width: 640px) {
      .mobile-sem-btn {
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding-top: 0.625rem !important;
        padding-bottom: 0.625rem !important;
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
      }
      .mobile-sem-btn span:first-child {
        font-size: 12px !important;
        margin-bottom: 0.125rem !important;
      }
      .mobile-sem-btn span:last-child {
        font-size: 14px !important;
      }
      #mobileSeminarNotificationsContainer h4,
      #seminarNotificationsContainer h4 {
        font-size: 16px !important;
      }
      #mobileSeminarNotificationsContainer p,
      #seminarNotificationsContainer p {
        font-size: 14px !important;
      }
    }

    /* CampusLynk Modern Virtual Classroom Panel Styles */
    #panelClassroom {
      background-color: #FAFAFB !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1.5rem !important;
      padding: 1.5rem !important;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.05) !important;
    }

    #panelClassroom, 
    #panelClassroom button,
    #panelClassroom select,
    #panelClassroom input,
    #panelClassroom table,
    #panelClassroom th,
    #panelClassroom td,
    #panelClassroom div,
    #panelClassroom p,
    #panelClassroom h3,
    #panelClassroom h4,
    #panelClassroom h5,
    #panelClassroom span {
      font-size: 14px;
    }
    
    #panelClassroom h3#vcTitle,
    #panelClassroom h3#vcTitle span {
      font-size: 18px !important;
      font-weight: 700 !important;
    }
    
    #panelClassroom #vcSubtitle,
    #panelClassroom #vcSubtitle span {
      font-size: 14px !important;
    }
    
    #panelClassroom #vcViewStudentsBtn,
    #panelClassroom #vcViewStudentsBtn span {
      font-size: 14px !important;
      font-weight: 600 !important;
    }

    #panelClassroom .classroom-tab-btn {
      font-size: 14px !important;
      font-weight: 600 !important;
      padding: 0.5rem 0.875rem !important;
      border-radius: 0.75rem !important;
      transition: all 0.15s ease !important;
    }

    #panelClassroom h4, 
    #panelClassroom h5 {
      font-size: 16px !important;
      font-weight: 700 !important;
    }

    /* Manual mark entry table title, names, and internal grid data font sizes */
    #manualMarksWrapper table th,
    #manualMarksWrapper table td,
    #manualMarksWrapper input,
    #manualMarksWrapper span {
      font-size: 14px !important;
    }
    
    #manualMarksWrapper table td {
      padding: 12px 10px !important;
    }

    /* Flatpickr date picker light theme styling */
    .flatpickr-calendar {
      background: #ffffff !important;
      border: 1px solid #e2e8f0 !important;
      border-radius: 1rem !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-month,
    .flatpickr-calendar .flatpickr-weekdays,
    .flatpickr-calendar .flatpickr-weekday,
    .flatpickr-calendar .flatpickr-days .flatpickr-day {
      color: #0f172a !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.prevMonthDay:hover,
    .flatpickr-calendar .flatpickr-days .flatpickr-day.nextMonthDay:hover {
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
    .flatpickr-calendar .flatpickr-days .flatpickr-day.selected {
      background: #2563eb !important;
      color: #ffffff !important;
    }
    .flatpickr-calendar .flatpickr-current-month span.cur-month,
    .flatpickr-calendar .numInputWrapper span,
    .flatpickr-calendar input.numInput {
      color: #0f172a !important;
      font-weight: 600 !important;
    }
    .flatpickr-calendar .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-calendar .flatpickr-months .flatpickr-next-month {
      color: #2563eb !important;
      fill: #2563eb !important;
    }

    /* Mobile styles for Virtual Classroom assessment mark entry */
    @media (max-width: 767px) {
      .co-mark, .summ-mark,
      #manualMarksWrapper input,
      #markEntryTbody input,
      #summativeMarkEntryTbody input {
        font-size: 16px !important;
        padding: 0.6rem !important;
        min-height: 44px !important;
        background-color: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.5rem !important;
        color: #0f172a !important;
      }

      /* Transform assignment mark entry tables into list cards on mobile view */
      #markEntryTbody,
      #markEntryTbody tr,
      #markEntryTbody td,
      #manualMarksWrapper tbody,
      #manualMarksWrapper tr,
      #manualMarksWrapper td {
        display: block !important;
      }
      
      #panelClassroom table thead {
        display: none !important;
      }
      
      #markEntryTbody tr,
      #manualMarksWrapper table tr {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.25rem !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 0.65rem !important;
        box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05) !important;
      }
      
      #markEntryTbody td,
      #manualMarksWrapper td {
        padding: 0.35rem 0 !important;
        border: none !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        text-align: left !important;
        font-size: 14px !important;
      }
      
      #markEntryTbody td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #markEntryTbody td:nth-child(5)::before { content: "CO1 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(6)::before { content: "CO2 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(7)::before { content: "CO3 (10): "; font-weight: bold; color: #2563eb; }
      #markEntryTbody td:nth-child(8)::before { content: "CO4 (10): "; font-weight: bold; color: #2563eb; }
      
      #manualMarksWrapper td:nth-child(1)::before { content: "S.No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(2)::before { content: "Student Name: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(3)::before { content: "Admission No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(4)::before { content: "SBTE Reg No: "; font-weight: bold; color: #64748b; }
      #manualMarksWrapper td:nth-child(5)::before { content: "CO1: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(6)::before { content: "CO2: "; font-weight: bold; color: #2563eb; }
      #manualMarksWrapper td:nth-child(7)::before { content: "CO3: "; font-weight: bold; color: #2563eb; }
