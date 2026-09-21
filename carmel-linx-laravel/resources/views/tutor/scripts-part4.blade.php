              <tr>
                <td>${idx + 1}</td>
                <td>${s.name}</td>
                <td>${s.reg_no}</td>
                <td>${s.admission_year || 'N/A'}</td>
                <td>${s.semester || 'S1'}</td>
                <td>${s.academic_status}</td>
                <td>${remark}</td>
              </tr>
            `;
          });

          let discontinuedSection = '';
          if (discontinuedList.length > 0) {
            discontinuedSection = `
              <div style="margin-top: 30px;">
                <h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #334155; padding-bottom: 5px; margin-bottom: 10px; color: #1e293b;">
                  Discontinued / TC Issued Students (Prior to ${targetSem})
                </h3>
                <table class="report-table">
                  <thead>
                    <tr>
                      <th style="width: 5%;">No.</th>
                      <th>Student Name</th>
                      <th style="width: 15%;">Register No</th>
                      <th style="width: 12%;">Adm Year</th>
                      <th style="width: 8%;">Sem</th>
                      <th style="width: 15%;">Enrolled Status</th>
                      <th style="width: 25%;">Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    ${discontinuedRows}
                  </tbody>
                </table>
              </div>
            `;
          }

          const html = `
            <!DOCTYPE html>
            <html>
            <head>
              <title>Class Register - ${data.classroomId} (${targetSem})</title>
              <style>
                @media print {
                  @page {
                    size: A4 landscape;
                    margin: 1.5cm;
                  }
                  body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                  }
                }
                body {
                  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
                  color: #0f172a;
                  margin: 0;
                  padding: 10px;
                  background-color: #fff;
                }
                .header-container {
                  text-align: center;
                  border-bottom: 3px double #000;
                  padding-bottom: 12px;
                  margin-bottom: 20px;
                }
                .college-name {
                  font-size: 20px;
                  font-weight: 900;
                  letter-spacing: 1px;
                  margin: 0;
                  color: #000;
                }
                .dept-name {
                  font-size: 13px;
                  font-weight: bold;
                  margin: 4px 0 0 0;
                  color: #334155;
                  letter-spacing: 0.5px;
                }
                .report-title {
                  font-size: 15px;
                  font-weight: 800;
                  margin: 8px 0 0 0;
                  text-transform: uppercase;
                  color: #000;
                  background: #f1f5f9;
                  display: inline-block;
                  padding: 4px 16px;
                  border-radius: 4px;
                }
                .meta-grid {
                  display: grid;
                  grid-template-columns: repeat(4, 1fr);
                  gap: 10px;
                  margin-bottom: 20px;
                  font-size: 12px;
                  background-color: #f8fafc;
                  border: 1px solid #e2e8f0;
                  padding: 12px;
                  border-radius: 8px;
                }
                .meta-item {
                  display: flex;
                  flex-direction: column;
                }
                .meta-label {
                  font-weight: bold;
                  color: #64748b;
                  text-transform: uppercase;
                  font-size: 9px;
                  margin-bottom: 2px;
                }
                .meta-value {
                  font-weight: bold;
                  color: #0f172a;
                  font-size: 12px;
                }
                .report-table {
                  width: 100%;
                  border-collapse: collapse;
                  margin-top: 10px;
                  font-size: 12px;
                }
                .report-table th, .report-table td {
                  border: 1px solid #cbd5e1;
                  padding: 8px 10px;
                  text-align: left;
                }
                .report-table th {
                  background-color: #f1f5f9;
                  font-weight: bold;
                  color: #1e293b;
                  text-transform: uppercase;
                  font-size: 10px;
                }
                .report-table tr:nth-child(even) {
                  background-color: #f8fafc;
                }
                .footer-signatures {
                  margin-top: 50px;
                  display: flex;
                  justify-content: space-between;
                  font-size: 12px;
                  font-weight: bold;
                  padding: 0 20px;
                }
                .sig-line {
                  border-top: 1.5px solid #000;
                  width: 200px;
                  text-align: center;
                  padding-top: 5px;
                  margin-top: 40px;
                }
              </style>
            </head>
            <body>
              <div class="header-container" style="position: relative;">
                <div style="position: absolute; right: 0; top: 0; font-size: 11px; font-weight: bold; color: #475569;">
                  Print Date: ${printDate}
                </div>
                <div class="college-name">CARMEL POLYTECHNIC COLLEGE, ALAPPUZHA</div>
                <div class="dept-name">DEPARTMENT OF ${branchName} ENGINEERING</div>
                <div class="report-title">Class Register - Admission ${batchYear}</div>
              </div>

              <div class="meta-grid">
                <div class="meta-item">
                  <span class="meta-label">Classroom ID</span>
                  <span class="meta-value">${data.classroomId}</span>
                </div>
                <div class="meta-item">
                  <span class="meta-label">Academic Semester</span>
                  <span class="meta-value">${targetSem}</span>
                </div>
                <div class="meta-item">
                  <span class="meta-label">Class Tutor</span>
                  <span class="meta-value">${tutorName}</span>
                </div>
                <div class="meta-item">
                  <span class="meta-label">Class Mentor</span>
                  <span class="meta-value">${mentorName}</span>
                </div>
              </div>

              <table class="report-table">
                <thead>
                  <tr>
                    <th style="width: 5%;">Roll No.</th>
                    <th>Student Name</th>
                    <th style="width: 15%;">Register No</th>
                    <th style="width: 15%;">SBTE Exam No</th>
                    <th style="width: 10%;">Adm Year</th>
                    <th style="width: 8%;">Sem</th>
                    <th style="width: 12%;">Enrolled Status</th>
                    <th style="width: 25%;">Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  ${activeRows}
                </tbody>
              </table>

              ${discontinuedSection}

              <div class="footer-signatures">
                <div class="sig-line">Class Tutor</div>
                <div class="sig-line">Class Mentor</div>
                <div class="sig-line">Head of Department</div>
              </div>

              <script>
                window.onload = function() {
                  window.print();
                };
              <\/script>
            </body>
            </html>
          `;

          printWindow.document.open();
          printWindow.document.write(html);
          printWindow.document.close();
        })
        .catch(err => {
          if (indicator) indicator.classList.add('hidden');
          console.error(err);
          alert('Error preparing print preview.');
        });
    }
  
  </script>

  @include('partials.support_desk_overlay')
</body>
</html>