<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentoring Diary - {{ $student->reg_no }}</title>
    <!-- Use standard fonts to ensure reliable printing without external requests if offline -->
    <style>
        :root {
            --primary: #1e3a8a;
            --border: #cbd5e1;
            --bg-light: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            background-color: #f1f5f9;
            color: var(--text-main);
            line-height: 1.5;
            font-size: 12px;
        }

        /* 
         * A4 Print Container
         */
        .a4-container {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm;
            margin: 20px auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            position: relative;
        }

        /* Print Controls (Hidden on Print) */
        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            z-index: 50;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        /* Typography & Structure */
        .header {
            text-align: center;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: var(--primary);
            font-size: 24px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .header h3 {
            color: var(--text-muted);
            font-size: 14px;
        }

        .section-title {
            background: var(--primary);
            color: white;
            padding: 5px 10px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-radius: 4px;
            display: flex;
            align-items: center;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid var(--border);
            padding: 6px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: var(--bg-light);
            color: var(--text-main);
            font-weight: 600;
            width: 35%;
        }

        /* Profile Layout */
        .profile-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .profile-photo {
            width: 35mm;
            height: 45mm;
            border: 2px solid var(--border);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-light);
            color: var(--text-muted);
            font-size: 10px;
            text-align: center;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-table {
            flex-grow: 1;
        }

        /* Helpers */
        .page-break {
            page-break-before: always;
        }
        
        .avoid-break {
            page-break-inside: avoid;
        }

        /* Print Media Queries */
        @media print {
            body {
                background: white;
            }
            .a4-container {
                margin: 0;
                padding: 0;
                width: 100%;
                min-height: auto;
                box-shadow: none;
                border: none;
            }
            .print-controls {
                display: none !important;
            }
            @page {
                size: A4;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Print Controls -->
    <div class="print-controls">
        <button onclick="window.print()" class="btn-print">🖨 Print / Save PDF</button>
        <button onclick="window.close()" class="btn-print" style="background:#475569;">✖ Close</button>
    </div>

    <!-- PAGE 1: Personal Profile, Family, Prior Education -->
    @php
        $branchNames = [
            'EL' => 'Electronics Engineering',
            'ME' => 'Mechanical Engineering',
            'CE' => 'Civil Engineering',
            'EEE' => 'Electrical & Electronics Engineering',
            'CT' => 'Computer Engineering',
            'AU' => 'Automobile Engineering'
        ];
        $fullBranchName = $branchNames[$student->branch] ?? $student->branch;
    @endphp
    
    <div class="a4-container">
        <div class="header">
            <h1 style="text-transform: uppercase; font-size: 26px;">Carmel Polytechnic College</h1>
            <h2 style="font-size: 18px; color: #0f172a; margin-bottom: 5px;">Department of {{ $fullBranchName }}</h2>
            <h3 style="margin-bottom: 15px;">Admission Year: {{ $student->admission_year }} &nbsp;|&nbsp; Batch: {{ $student->classroom_id }}</h3>
            
            <div style="margin: 15px 0; border-top: 1px dashed #cbd5e1;"></div>
            
            <h2 style="color: #1e3a8a; font-size: 20px; text-transform: uppercase;">Student Mentoring Diary</h2>
            <p style="color: #64748b; font-size: 13px;">Comprehensive Academic & Personal Profile</p>
        </div>

        <div class="section-title avoid-break">I. Personal Profile</div>
        
        <div class="profile-container avoid-break">
            <div class="profile-photo">
                @if($student->photo_url)
                    <img src="{{ $student->photo_url }}" alt="Student Photo">
                @else
                    <span>Affix<br>Passport<br>Size<br>Photo</span>
                @endif
            </div>
            
            <div class="profile-table">
                <table>
                    <tbody>
                        <tr>
                            <th>Student Name</th>
                            <td colspan="3"><strong>{{ $student->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Login ID / Reg No</th>
                            <td>{{ $student->reg_no }}</td>
                            <th>SBTE Exam Reg No</th>
                            <td>{{ $student->sbte_reg_no ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Admission Year</th>
                            <td>{{ $student->admission_year ?? '-' }}</td>
                            <th>Admission Type</th>
                            <td>{{ $student->admission_type ?? '-' }} (Regular / LET)</td>
                        </tr>
                        <tr>
                            <th>Branch</th>
                            <td>{{ $student->branch ?? '-' }}</td>
                            <th>Gender</th>
                            <td>{{ $extended_profile->gender ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $student->email ?? '-' }}</td>
                            <th>Mobile</th>
                            <td>{{ $student->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Religion & Caste</th>
                            <td>{{ $extended_profile->religion ?? '-' }}, {{ $extended_profile->caste ?? '-' }}</td>
                            <th>Special Category / Reservation</th>
                            <td>{{ $extended_profile->special_category ?? '-' }} / {{ $extended_profile->reservation ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Quota (NCC/ITI/etc)</th>
                            <td>{{ $extended_profile->quota ?? '-' }}</td>
                            <th>Physically Disabled?</th>
                            <td>
                                {{ ($extended_profile->is_physically_disabled ?? false) ? 'Yes' : 'No' }}
                                @if(!empty($extended_profile->disability_category))
                                    ({{ $extended_profile->disability_category }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Residential Status</th>
                            <td>{{ $student->residential_status ?? '-' }} (Hostel/Day Scholar)</td>
                            <th>Vehicle Pass Holder?</th>
                            <td>
                                {{ ($extended_profile->has_vehicle_pass ?? false) ? 'Yes' : 'No' }}
                                @if(!empty($extended_profile->vehicle_pass_id))
                                    (ID: {{ $extended_profile->vehicle_pass_id }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Communication Address</th>
                            <td colspan="3">{{ $extended_profile->communication_address ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section-title avoid-break">II. Family Details</div>
        <table class="avoid-break">
            <tbody>
                <tr>
                    <th>Guardian Name & Relation</th>
                    <td>{{ $student->guardian_name ?? '-' }} ({{ $student->guardian_relationship ?? '-' }})</td>
                    <th>Guardian Occupation</th>
                    <td>{{ $extended_profile->guardian_occupation ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Guardian Mobile</th>
                    <td>{{ $student->guardian_mobile ?? '-' }}</td>
                    <th>Monthly Family Income</th>
                    <td>Rs. {{ $extended_profile->monthly_family_income ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
        
        @if(count((array)$family) > 0)
        <table class="avoid-break" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width:25%;">Family Member Name</th>
                    <th style="width:20%;">Relation</th>
                    <th style="width:25%;">Occupation</th>
                    <th style="width:30%;">Contact No</th>
                </tr>
            </thead>
            <tbody>
                @foreach($family as $f)
                <tr>
                    <td>{{ $f->name }}</td>
                    <td>{{ $f->relationship }}</td>
                    <td>{{ $f->occupation }}</td>
                    <td>{{ $f->contact_no }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="section-title avoid-break">III. Prior Education</div>
        <table class="avoid-break">
            <thead>
                <tr>
                    <th>Course / Degree</th>
                    <th>Institution</th>
                    <th>Year of Passing</th>
                    <th>Percentage / Grade</th>
                </tr>
            </thead>
            <tbody>
                @if(count((array)$education) > 0)
                    @foreach($education as $edu)
                      <tr>
                          <td>{{ $edu->course ?? '-' }}</td>
                          <td>{{ $edu->institution ?? '-' }}</td>
                          <td>{{ $edu->year_of_completion ?? '-' }}</td>
                          <td>{{ $edu->total_percentage ?? '-' }}</td>
                      </tr>
                    @endforeach
                @else
                    <tr><td colspan="4" style="text-align:center; color:#94a3b8;">No prior education records found.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- PAGE 2: Academic Progress & Board Exams -->
    <div class="a4-container page-break">
        <div class="section-title avoid-break">IV. Academic Progress Report</div>
        
        <table class="avoid-break">
            <thead>
                <tr>
                    <th>Semester</th>
                    <th>SGPA</th>
                    <th>Activity Points</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($board) && count((array)$board) > 0)
                    @foreach($board as $sem)
                    <tr>
                        <td>Semester {{ $sem->semester }}</td>
                        <td><strong>{{ $sem->sgpa ?? '-' }}</strong></td>
                        <td>{{ $sem->activity_points ?? '-' }}</td>
                        <td>{{ $sem->remarks ?? '-' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="4" style="text-align:center; color:#94a3b8;">No academic progress recorded yet.</td></tr>
                @endif
            </tbody>
        </table>

        <!-- We can also add a placeholder for overall CGPA if we calculate it -->
        <div style="text-align: right; margin-bottom: 20px; padding: 10px; background: #f8fafc; border: 1px solid #cbd5e1;">
            <strong>Total CGPA:</strong> ________________
        </div>

        <div class="section-title avoid-break">V. Board Exam Results</div>
        
        @if(isset($academics) && count((array)$academics) > 0)
            @foreach($academics as $semNumber => $subjects)
                <h4 style="margin-top: 15px; margin-bottom: 5px; color: var(--primary);">Semester {{ $semNumber }}</h4>
                <table class="avoid-break">
                    <thead>
                        <tr>
                            <th style="width:15%">Subject Code</th>
                            <th style="width:40%">Subject Name</th>
                            <th style="width:15%">Internal Mark</th>
                            <th style="width:15%">Result Grade</th>
                            <th style="width:15%">Pass/Fail/Chance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subj)
                        <tr>
                            <td>{{ $subj->subject_code }}</td>
                            <td>{{ $subj->subject_name }}</td>
                            <td>{{ $subj->total_internal_score ?? '-' }} / {{ $subj->internal_max ?? '50' }}</td>
                            <td><strong>{{ $subj->board_grade ?? 'Not Published' }}</strong></td>
                            <td>
                                @if(isset($subj->board_grade) && in_array($subj->board_grade, ['F', 'Absent']))
                                    <span style="color:#ef4444;">Fail</span>
                                @elseif(isset($subj->board_grade))
                                    <span style="color:#22c55e;">Pass</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @else
            <p style="text-align:center; color:#94a3b8; padding: 20px; border: 1px dashed #cbd5e1;">No board exam results recorded yet.</p>
        @endif
    </div>

    <!-- PAGE 3: Extracurricular Activities -->
    <div class="a4-container page-break">
        <div class="section-title avoid-break">VI. Extracurricular Activities & Activity Points</div>
        <table class="avoid-break">
            <thead>
                <tr>
                    <th style="width:10%">Sem</th>
                    <th style="width:20%">Activity Segment</th>
                    <th style="width:40%">Description</th>
                    <th style="width:15%">Points Claimed</th>
                    <th style="width:15%">Status</th>
                </tr>
            </thead>
            <tbody>
                @if(count((array)$extracurricular) > 0)
                    @foreach($extracurricular as $act)
                    <tr>
                        <td>{{ $act->semester ?? '-' }}</td>
                        <td>{{ $act->activity_segment ?? '-' }}</td>
                        <td>{{ $act->activity_name ?? '-' }}</td>
                        <td><strong>{{ $act->points_claimed ?? '-' }}</strong></td>
                        <td>{{ $act->status ?? '-' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="5" style="text-align:center; color:#94a3b8;">No extracurricular activities recorded.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- PAGE 4: Mentoring Meetings Log -->
    <div class="a4-container page-break">
        <div class="section-title avoid-break">VII. Mentoring Meetings Log</div>
        <table class="avoid-break">
            <thead>
                <tr>
                    <th style="width:15%">Date</th>
                    <th style="width:45%">Discussion Notes / Topics</th>
                    <th style="width:25%">Remarks / Action Needed</th>
                    <th style="width:15%">Logged By</th>
                </tr>
            </thead>
            <tbody>
                @if(count((array)$meetings) > 0)
                    @foreach($meetings as $meeting)
                    <tr>
                        <td>{{ date('d-m-Y', strtotime($meeting->date)) }}</td>
                        <td>{{ $meeting->discussion_notes ?? '-' }}</td>
                        <td>{{ $meeting->remarks ?? '-' }}</td>
                        <td>{{ $meeting->logged_by_name ?? 'Mentor' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="4" style="text-align:center; color:#94a3b8;">No mentoring meetings recorded yet.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- PAGE 5: Placement & Training -->
    <div class="a4-container page-break">
        <div class="section-title avoid-break">VIII. Placement and Training Details</div>
        
        <table class="avoid-break">
            <tbody>
                <tr>
                    <th>Placement Status / Company Details</th>
                    <td>{{ $student->placement_details ?? 'No placement records found.' }}</td>
                </tr>
                <tr>
                    <th>Higher Studies Remark</th>
                    <td>{{ $student->higher_studies_remark ?? 'No records found.' }}</td>
                </tr>
                <tr>
                    <th>Scholarships Availed</th>
                    <td>{{ $student->scholarships ?? 'No records found.' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Signatures Section -->
        <div style="margin-top: 100px; display: flex; justify-content: space-between; text-align: center; font-weight: bold; padding: 0 40px;">
            <div>
                <p>___________________________</p>
                <p style="margin-top: 5px;">Signature of Student</p>
            </div>
            <div>
                <p>___________________________</p>
                <p style="margin-top: 5px;">Signature of Mentor</p>
            </div>
            <div>
                <p>___________________________</p>
                <p style="margin-top: 5px;">Signature of HOD</p>
            </div>
        </div>
    </div>


    <!-- PAGE: Leave Records -->
    <div class="a4-container">
        <div class="header">
            <h2>Leave Records</h2>
        </div>
        @if(isset($leaves) && count($leaves) > 0)
        <table class="data-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 15%;">Semester</th>
                    <th style="width: 20%;">Date</th>
                    <th style="width: 45%;">Reason</th>
                    <th style="width: 20%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $lv)
                <tr>
                    <td style="text-align: center;">Sem {{ $lv->semester ?? $lv['semester'] ?? '-' }}</td>
                    <td style="text-align: center;">{{ $lv->leave_date ?? $lv['leave_date'] ?? '-' }}</td>
                    <td>{{ $lv->reason ?? $lv['reason'] ?? '-' }}</td>
                    <td style="text-align: center;">{{ $lv->status ?? $lv['status'] ?? 'Pending' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="margin-top: 15px; font-style: italic; color: #64748b;">No leave records found.</p>
        @endif
    </div>

    <!-- PAGE: Disciplinary Actions -->
    <div class="a4-container">
        <div class="header">
            <h2>Disciplinary Actions</h2>
        </div>
        @if(isset($disciplinary) && count($disciplinary) > 0)
        <table class="data-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th style="width: 20%;">Date</th>
                    <th style="width: 40%;">Description</th>
                    <th style="width: 40%;">Action Taken</th>
                </tr>
            </thead>
            <tbody>
                @foreach($disciplinary as $d)
                <tr>
                    <td style="text-align: center;">{{ $d->date ?? $d['date'] ?? '-' }}</td>
                    <td>{{ $d->description ?? $d['description'] ?? '-' }}</td>
                    <td>{{ $d->action_taken ?? $d['action_taken'] ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="margin-top: 15px; font-style: italic; color: #64748b;">No disciplinary actions found.</p>
        @endif
    </div>

</body>
</html>
