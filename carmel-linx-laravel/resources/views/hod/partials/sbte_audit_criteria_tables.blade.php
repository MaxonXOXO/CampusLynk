{{-- Criteria 7 to 16 for SBTE Academic Audit Part C --}}
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
  <td class="p-2.5 space-y-1 text-xs">
    @if(!empty($auditData['vision_mission']['vision'])) <p><strong>Vision:</strong> {{ $auditData['vision_mission']['vision'] }}</p> @endif
    @if(!empty($auditData['vision_mission']['mission'])) <p><strong>Mission:</strong> {{ $auditData['vision_mission']['mission'] }}</p> @endif
    @if(!empty($auditData['vision_mission']['peos'])) <p><strong>PEOs:</strong> {{ $auditData['vision_mission']['peos'] }}</p> @endif
    @if(!empty($auditData['vision_mission']['psos'])) <p><strong>PSOs:</strong> {{ $auditData['vision_mission']['psos'] }}</p> @endif
    <p class="text-slate-500 italic mt-1">Dissemination Remarks: {{ $auditData['vision_mission']['remarks'] ?? '-' }}</p>
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
