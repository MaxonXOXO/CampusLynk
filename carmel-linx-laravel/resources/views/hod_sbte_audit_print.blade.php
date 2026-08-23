<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SBTE Academic Audit Part C - {{ $academicYear }}</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;850&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #ffffff;
      color: #0f172a;
    }
    /* Enforce compact 10.5px print font standard for tabular A4 data */
    body, table, td, th {
      font-size: 10.5px !important;
    }
    @media print {
      body {
        background-color: #ffffff;
        color: #000000;
        margin: 0;
        padding: 0;
      }
      .no-print {
        display: none !important;
      }
      @page {
        size: A4 portrait;
        margin: 0.5cm;
      }
      .page-break {
        page-break-before: always;
      }
    }
  </style>
</head>
<body class="bg-slate-50 min-h-screen p-4 md:p-8">

  <!-- Print Actions Bar -->
  <div class="max-w-3xl mx-auto mb-6 p-4 bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center justify-between no-print">
    <div class="flex items-center gap-3">
      <div>
        <h3 class="font-bold text-slate-800 text-sm">Print SITTTR Academic Audit</h3>
        <p class="text-xs text-slate-500">Set layout to Portrait, Margins: Narrow, Paper: A4</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="window.print()" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-sm shadow-sm">
        Print Sheet
      </button>
      <button onclick="window.close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition flex items-center gap-2 cursor-pointer text-sm border border-slate-200">
        Close Preview
      </button>
    </div>
  </div>

  <!-- A4 Printable Sheet Container -->
  <div class="max-w-3xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm print:border-0 print:p-0 print:shadow-none space-y-6">
    
    <!-- Heading Banner -->
    <div class="border-b border-slate-200 pb-4 flex justify-between items-start">
      <div>
        <h1 class="text-slate-900 font-black tracking-tight text-lg">
          DIRECTORATE OF TECHNICAL EDUCATION (Govt. of Kerala)
        </h1>
        <p class="text-slate-650 font-bold text-xs uppercase tracking-wider mt-0.5">
          Academic Audit (Diploma programs) - Part C Program Details
        </p>
        <p class="text-slate-550 text-xs mt-0.5 font-bold">
          Academic Year: <span class="text-slate-800">{{ $academicYear }}</span> | Branch: <span class="text-slate-800">{{ $department }}</span>
        </p>
      </div>
      <div class="text-right">
        <span class="inline-block px-3 py-1 bg-slate-100 border border-slate-200 rounded-lg text-slate-700 font-mono text-xs">
          Date Compiled: {{ $currentDate }}
        </span>
      </div>
    </div>

    <!-- Criterion 1 & 2 -->
    <div class="space-y-2">
      <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">1. Program & NBA Details</h3>
      <table class="w-full border-collapse border border-slate-200 text-left">
        <tbody>
          <tr class="border-b border-slate-200">
            <td class="p-2.5 font-bold text-slate-700 bg-slate-50 w-48">Name of Program:</td>
            <td class="p-2.5 text-slate-900">{{ $department }}</td>
          </tr>
          <tr class="border-b border-slate-200">
            <td class="p-2.5 font-bold text-slate-700 bg-slate-50">Name of HOD:</td>
            <td class="p-2.5 text-slate-900">{{ $auditData['professional_activities']['hod_name'] ?? 'Not Specified' }}</td>
          </tr>
          <tr class="border-b border-slate-200">
            <td class="p-2.5 font-bold text-slate-700 bg-slate-50">No. of Faculty / Lab staff:</td>
            <td class="p-2.5 text-slate-900">{{ $auditData['professional_activities']['faculty_count'] ?? '0' }}</td>
          </tr>
          <tr>
            <td class="p-2.5 font-bold text-slate-700 bg-slate-50">NBA Accredited? (Criterion 1)</td>
            <td class="p-2.5 text-slate-900 font-bold">{{ ($auditData['nba_accredited'] ?? false) ? 'YES' : 'NO' }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- 2. Enrollment Table -->
    <div class="space-y-2">
      <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">2. Enrollment</h3>
      <table class="w-full border-collapse border border-slate-200 text-center">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200 font-bold text-slate-700">
            <th class="p-2 text-left">Year</th>
            <th class="p-2">Approved Intake</th>
            <th class="p-2">Enrolled</th>
            <th class="p-2">Present Strength</th>
          </tr>
        </thead>
        <tbody>
          @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
            <tr class="border-b border-slate-150">
              <td class="p-2 text-left font-bold text-slate-800">{{ $y }}</td>
              <td class="p-2">{{ $auditData['enrollment'][$y]['intake'] ?? '-' }}</td>
              <td class="p-2">{{ $auditData['enrollment'][$y]['enrolled'] ?? '-' }}</td>
              <td class="p-2">{{ $auditData['enrollment'][$y]['present'] ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- 3. Academic performance without backlog -->
    <div class="space-y-2">
      <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">3. Academic Performance (Without Backlog)</h3>
      <table class="w-full border-collapse border border-slate-200 text-center">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200 font-bold text-slate-700">
            <th class="p-2 text-left">Semester</th>
            <th class="p-2">CAY (Reg / Pass)</th>
            <th class="p-2">CAY-1 (Reg / Pass)</th>
            <th class="p-2">CAY-2 (Reg / Pass)</th>
          </tr>
        </thead>
        <tbody>
          @for($s = 1; $s <= 6; $s++)
            <tr class="border-b border-slate-150">
              <td class="p-2 text-left font-bold text-slate-800">S{{ $s }}</td>
              @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
                <td class="p-2">
                  {{ $auditData['perf_no_backlog'][$s][$y]['reg'] ?? '-' }} / {{ $auditData['perf_no_backlog'][$s][$y]['pass'] ?? '-' }}
                </td>
              @endforeach
            </tr>
          @endfor
        </tbody>
      </table>
    </div>

    <div class="page-break"></div>

    <!-- 4. Academic performance with backlog -->
    <div class="space-y-2">
      <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">4. Academic Performance (With Backlog)</h3>
      <table class="w-full border-collapse border border-slate-200 text-center">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200 font-bold text-slate-700">
            <th class="p-2 text-left">Semester</th>
            <th class="p-2">CAY (Reg / Pass)</th>
            <th class="p-2">CAY-1 (Reg / Pass)</th>
            <th class="p-2">CAY-2 (Reg / Pass)</th>
          </tr>
        </thead>
        <tbody>
          @for($s = 1; $s <= 6; $s++)
            <tr class="border-b border-slate-150">
              <td class="p-2 text-left font-bold text-slate-800">S{{ $s }}</td>
              @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
                <td class="p-2">
                  {{ $auditData['perf_with_backlog'][$s][$y]['reg'] ?? '-' }} / {{ $auditData['perf_with_backlog'][$s][$y]['pass'] ?? '-' }}
                </td>
              @endforeach
            </tr>
          @endfor
        </tbody>
      </table>
    </div>

    <!-- 5. Placement Table -->
    <div class="space-y-2 mt-6">
      <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">5. Placement</h3>
      <table class="w-full border-collapse border border-slate-200 text-center">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200 font-bold text-slate-700">
            <th class="p-2 text-left">Batch Year</th>
            <th class="p-2">Admitted</th>
            <th class="p-2">Placed</th>
            <th class="p-2">Higher Ed</th>
            <th class="p-2">Entrepreneurs</th>
          </tr>
        </thead>
        <tbody>
          @foreach(['CAY-1', 'CAY-2', 'CAY-3'] as $b)
            <tr class="border-b border-slate-150">
              <td class="p-2 text-left font-bold text-slate-800">{{ $b }}</td>
              <td class="p-2">{{ $auditData['placement'][$b]['admitted'] ?? '-' }}</td>
              <td class="p-2">{{ $auditData['placement'][$b]['placed'] ?? '-' }}</td>
              <td class="p-2">{{ $auditData['placement'][$b]['higher_ed'] ?? '-' }}</td>
              <td class="p-2">{{ $auditData['placement'][$b]['entrepreneurs'] ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Criteria 6 to 16 List -->
    <div class="space-y-2 mt-6">
      <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wider">6-16. Quality Audits & Infrastructure Assessment</h3>
      <table class="w-full border-collapse border border-slate-200 text-left">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200 font-bold text-slate-700">
            <th class="p-2.5 w-12">No.</th>
            <th class="p-2.5 w-64">Assessment Criterion</th>
            <th class="p-2.5">Academic Audit Compliance Details / Remarks</th>
            <th class="p-2.5 w-32 text-center bg-slate-100">Audit Marks</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b border-slate-150">
            <td class="p-2.5 font-bold">6</td>
            <td class="p-2.5 font-medium">Professional Activities</td>
            <td class="p-2.5 space-y-1">
              @if(isset($auditData['professional_activities']['societies']) && count(array_filter($auditData['professional_activities']['societies'])) > 0)
                <strong>Societies & Chapters:</strong>
                <ul class="list-disc pl-4 space-y-0.5">
                  @foreach($auditData['professional_activities']['societies'] as $soc)
                    @if(!empty($soc)) <li>{{ $soc }}</li> @endif
                  @endforeach
                </ul>
              @endif
              @if(isset($auditData['professional_activities']['publications']) && count(array_filter($auditData['professional_activities']['publications'])) > 0)
                <strong class="block mt-2">Publications & Newsletters:</strong>
                <ul class="list-disc pl-4 space-y-0.5">
                  @foreach($auditData['professional_activities']['publications'] as $pub)
                    @if(!empty($pub)) <li>{{ $pub }}</li> @endif
                  @endforeach
                </ul>
              @endif
            </td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 5</td>
          </tr>
          <tr class="border-b border-slate-150">
            <td class="p-2.5 font-bold">7</td>
            <td class="p-2.5 font-medium">Student Faculty Ratio (SFR)</td>
            <td class="p-2.5 font-mono text-slate-800">CAY: {{ $auditData['sfr']['CAY'] ?? '-' }} | CAY-1: {{ $auditData['sfr']['CAY-1'] ?? '-' }}</td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 25</td>
          </tr>
          <tr class="border-b border-slate-150">
            <td class="p-2.5 font-bold">8</td>
            <td class="p-2.5 font-medium">Infrastructure of Program</td>
            <td class="p-2.5">
              <table class="w-full text-left border-collapse text-[10px]">
                <thead>
                  <tr class="border-b border-slate-200 bg-slate-50 font-bold text-slate-700">
                    <th class="p-1">Item</th>
                    <th class="p-1 text-center">No.</th>
                    <th class="p-1 text-center">Area</th>
                    <th class="p-1 text-center">Adequacy</th>
                    <th class="p-1 text-center">Ambience</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $infra = $auditData['infrastructure'] ?? [];
                    $infraItems = ['Classrooms', 'Smart classrooms', 'Laboratories', 'Computer Lab', 'Cabin for HoD', 'Faculty room', 'Others'];
                  @endphp
                  @foreach($infraItems as $item)
                    @if(isset($infra[$item]))
                      <tr class="border-b border-slate-100">
                        <td class="p-1 font-bold">{{ $item }}</td>
                        <td class="p-1 text-center">{{ $infra[$item]['number'] ?? '-' }}</td>
                        <td class="p-1 text-center">{{ $infra[$item]['area'] ?? '-' }}</td>
                        <td class="p-1 text-center">{{ $infra[$item]['adequacy'] ?? '-' }}</td>
                        <td class="p-1 text-center">{{ $infra[$item]['ambience'] ?? '-' }}</td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 10</td>
          </tr>
          <tr class="border-b border-slate-150">
            <td class="p-2.5 font-bold">9</td>
            <td class="p-2.5 font-medium">Vision, Mission, PEOs, & PSOs</td>
            <td class="p-2.5 space-y-1">
              @if(!empty($auditData['vision_mission']['vision'])) <p><strong>Vision:</strong> {{ $auditData['vision_mission']['vision'] }}</p> @endif
              @if(!empty($auditData['vision_mission']['mission'])) <p><strong>Mission:</strong> {{ $auditData['vision_mission']['mission'] }}</p> @endif
              @if(!empty($auditData['vision_mission']['peos'])) <p><strong>PEOs:</strong> {{ $auditData['vision_mission']['peos'] }}</p> @endif
              @if(!empty($auditData['vision_mission']['psos'])) <p><strong>PSOs:</strong> {{ $auditData['vision_mission']['psos'] }}</p> @endif
              <p class="text-slate-550 italic mt-1">Dissemination Remarks: {{ $auditData['vision_mission']['remarks'] ?? '-' }}</p>
            </td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 10</td>
          </tr>
          <tr class="border-b border-slate-150">
            <td class="p-2.5 font-bold">10</td>
            <td class="p-2.5 font-medium">Teaching - Learning Process</td>
            <td class="p-2.5">
              <table class="w-full text-left border-collapse text-[10px]">
                <thead>
                  <tr class="border-b border-slate-200 bg-slate-50 font-bold text-slate-700">
                    <th class="p-1">Criterion Process</th>
                    <th class="p-1 text-center w-16">Status</th>
                    <th class="p-1">HOD Remarks / Details</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $tl = $auditData['teaching_learning'] ?? [];
                    $tlItems = [
                      'gaps' => 'Curricular Gaps Identified (PO/PSO)',
                      'weak_bright' => 'Support for Weak/Bright Students',
                      'calendar' => 'Adherence to Academic Calendar',
                      'internal_tests' => 'Quality Checks for Internal Tests',
                      'labs' => 'Laboratory Syllabus Conduct',
                      'projects' => 'Student Projects Monitoring',
                      'industry' => 'Industry Linkages & Visits',
                      'co_curricular' => 'Co-curricular Connections'
                    ];
                  @endphp
                  @foreach($tlItems as $key => $label)
                    @if(isset($tl[$key]))
                      <tr class="border-b border-slate-100">
                        <td class="p-1 font-bold">{{ $label }}</td>
                        <td class="p-1 text-center font-bold text-slate-800">{{ $tl[$key]['status'] ?? '-' }}</td>
                        <td class="p-1 text-slate-700">{{ $tl[$key]['remarks'] ?? '-' }}</td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 80</td>
          </tr>
          <tr class="border-b border-slate-150">
            <td class="p-2.5 font-bold">11</td>
            <td class="p-2.5 font-medium">Course Files (Attainments)</td>
            <td class="p-2.5">
              <table class="w-full text-left border-collapse text-[10px]">
                <thead>
                  <tr class="border-b border-slate-200 bg-slate-50 font-bold text-slate-700">
                    <th class="p-1">Batch Category</th>
                    <th class="p-1 text-center">No. of Courses</th>
                    <th class="p-1 text-center">Completed Files</th>
                    <th class="p-1 text-center">PO Attainment?</th>
                    <th class="p-1 text-center">PSO Attainment?</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $cf = $auditData['course_files'] ?? [];
                    $batches = [
                      'CAY-3' => 'CAY-3',
                      'CAY-2' => 'CAY-2',
                      'CAY-1' => 'CAY-1'
                    ];
                  @endphp
                  @foreach($batches as $key => $label)
                    @if(isset($cf[$key]))
                      @php
                        $revYear = $cf[$key]['rev_year'] ?? ($key === 'CAY-1' ? '21' : '15');
                        $fullLabel = "$label (Rev $revYear)";
                      @endphp
                      <tr class="border-b border-slate-100">
                        <td class="p-1 font-bold">{{ $fullLabel }}</td>
                        <td class="p-1 text-center">{{ $cf[$key]['courses'] ?? '-' }}</td>
                        <td class="p-1 text-center">{{ $cf[$key]['completed'] ?? '-' }}</td>
                        <td class="p-1 text-center">{{ $cf[$key]['po_attained'] ?? '-' }}</td>
                        <td class="p-1 text-center">{{ $cf[$key]['pso_attained'] ?? '-' }}</td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 30</td>
          </tr>
          <tr class="border-b border-slate-150">
            <td class="p-2.5 font-bold">12</td>
            <td class="p-2.5 font-medium">Faculty Training Participation</td>
            <td class="p-2.5">
              <table class="w-full text-left border-collapse text-[10px]">
                <thead>
                  <tr class="border-b border-slate-200 bg-slate-50 font-bold text-slate-700">
                    <th class="p-1">Faculty Name</th>
                    <th class="p-1">Designation</th>
                    <th class="p-1">FDP Title</th>
                    <th class="p-1 text-center">Duration</th>
                    <th class="p-1">Venue</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $ftRows = $auditData['faculty_training'] ?? [];
                  @endphp
                  @foreach($ftRows as $row)
                    @if(is_array($row) && isset($row['name']))
                      <tr class="border-b border-slate-100">
                        <td class="p-1 font-bold">{{ $row['name'] }}</td>
                        <td class="p-1">{{ $row['designation'] ?? '-' }}</td>
                        <td class="p-1">{{ $row['title'] ?? '-' }}</td>
                        <td class="p-1 text-center">{{ $row['duration'] ?? '-' }} days</td>
                        <td class="p-1">{{ $row['venue'] ?? '-' }}</td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 10</td>
          </tr>
          <tr class="border-b border-slate-150">
            <td class="p-2.5 font-bold">13</td>
            <td class="p-2.5 font-medium">FDPs conducted in past 3 years</td>
            <td class="p-2.5">
              <table class="w-full text-left border-collapse text-[10px]">
                <thead>
                  <tr class="border-b border-slate-200 bg-slate-50 font-bold text-slate-700">
                    <th class="p-1">Title of FDP</th>
                    <th class="p-1 text-center">Attended</th>
                    <th class="p-1 text-center">Date From</th>
                    <th class="p-1">Funding Agency</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $fdpRows = $auditData['fdp_conducted'] ?? [];
                  @endphp
                  @foreach($fdpRows as $row)
                    @if(is_array($row) && isset($row['title']))
                      <tr class="border-b border-slate-100">
                        <td class="p-1 font-bold">{{ $row['title'] }}</td>
                        <td class="p-1 text-center">{{ $row['attended'] ?? '-' }}</td>
                        <td class="p-1 text-center">{{ $row['date_from'] ?? '-' }}</td>
                        <td class="p-1">{{ $row['funding'] ?? '-' }}</td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 10</td>
          </tr>
          <tr class="border-b border-slate-150">
            <td class="p-2.5 font-bold">14</td>
            <td class="p-2.5 font-medium">Consultancy & Testing</td>
            <td class="p-2.5">
              <table class="w-full text-left border-collapse text-[10px]">
                <thead>
                  <tr class="border-b border-slate-200 bg-slate-50 font-bold text-slate-700">
                    <th class="p-1">Name of Project/Work</th>
                    <th class="p-1">Date</th>
                    <th class="p-1">Fund Generated</th>
                    <th class="p-1">Faculty Involved</th>
                    <th class="p-1">Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $consultRows = $auditData['consultancy'] ?? [];
                    if (is_array($consultRows) && !empty($consultRows) && !isset($consultRows[0])) {
                        $consultRows = [[
                            'name' => 'Legacy Record',
                            'date' => '-',
                            'fund' => '-',
                            'faculty' => '-',
                            'remarks' => $consultRows['remarks'] ?? ''
                        ]];
                    }
                  @endphp
                  @if(empty($consultRows))
                    <tr>
                      <td colspan="5" class="p-1 text-slate-400 italic text-center">No records added.</td>
                    </tr>
                  @else
                    @foreach($consultRows as $row)
                      @if(is_array($row) && isset($row['name']))
                        <tr class="border-b border-slate-100">
                          <td class="p-1 font-bold">{{ $row['name'] }}</td>
                          <td class="p-1">{{ $row['date'] ?? '-' }}</td>
                          <td class="p-1">{{ $row['fund'] ?? '-' }}</td>
                          <td class="p-1">{{ $row['faculty'] ?? '-' }}</td>
                          <td class="p-1">{{ $row['remarks'] ?? '-' }}</td>
                        </tr>
                      @endif
                    @endforeach
                  @endif
                </tbody>
              </table>
            </td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 10</td>
          </tr>
          <tr class="border-b border-slate-150">
            <td class="p-2.5 font-bold">15</td>
            <td class="p-2.5 font-medium">Remarkable Achievements</td>
            <td class="p-2.5">
              <table class="w-full text-left border-collapse text-[10px]">
                <thead>
                  <tr class="border-b border-slate-200 bg-slate-50 font-bold text-slate-700">
                    <th class="p-1 text-center w-8">No.</th>
                    <th class="p-1 w-24">Faculty / Student</th>
                    <th class="p-1">Name</th>
                    <th class="p-1">Achievement</th>
                    <th class="p-1">Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $achRows = $auditData['achievements'] ?? [];
                    if (is_array($achRows) && !empty($achRows) && !isset($achRows[0])) {
                        $achRows = [[
                            'category' => 'Faculty',
                            'name' => 'Legacy Record',
                            'achievement' => $achRows['remarks'] ?? '',
                            'remarks' => '-'
                        ]];
                    }
                  @endphp
                  @if(empty($achRows))
                    <tr>
                      <td colspan="5" class="p-1 text-slate-400 italic text-center">No records added.</td>
                    </tr>
                  @else
                    @foreach($achRows as $index => $row)
                      @if(is_array($row) && isset($row['name']))
                        <tr class="border-b border-slate-100">
                          <td class="p-1 text-center font-bold text-slate-500">{{ $index + 1 }}</td>
                          <td class="p-1">{{ $row['category'] ?? '-' }}</td>
                          <td class="p-1 font-bold">{{ $row['name'] }}</td>
                          <td class="p-1">{{ $row['achievement'] ?? '-' }}</td>
                          <td class="p-1">{{ $row['remarks'] ?? '-' }}</td>
                        </tr>
                      @endif
                    @endforeach
                  @endif
                </tbody>
              </table>
            </td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 5</td>
          </tr>
          <tr>
            <td class="p-2.5 font-bold">16</td>
            <td class="p-2.5 font-medium">General Remarks of Inspection Team</td>
            <td class="p-2.5 text-slate-400 italic">To be filled by Chairman / Expert Committee Members</td>
            <td class="p-2.5 text-center bg-slate-50 italic text-slate-400 font-bold">Evaluated</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Signatures Panel -->
    <div class="mt-16 grid grid-cols-2 gap-8 border-t border-slate-200 pt-8">
      <div class="text-center">
        <div class="h-10"></div>
        <p class="font-bold text-slate-800 text-xs">Head of the Department (HOD)</p>
        <p class="text-[10px] text-slate-500 mt-0.5">{{ $department }} Department</p>
      </div>
      <div class="text-center">
        <div class="h-10"></div>
        <p class="font-bold text-slate-800 text-xs">Principal Seal / Sign</p>
        <p class="text-[10px] text-slate-500 mt-0.5">Carmel Polytechnic College</p>
      </div>
    </div>

  </div>

</body>
</html>
