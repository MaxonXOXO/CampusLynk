<x-layouts.report-layout 
    title="SBTE Academic Audit Part C - {{ $academicYear }}" 
    orientation="portrait" 
    documentNo="SBTE/AUDIT/{{ $academicYear }}">

    @include('reports.partials.header', [
        'title' => 'DIRECTORATE OF TECHNICAL EDUCATION (GOVT. OF KERALA)',
        'subtitle' => 'Academic Audit (Diploma Programs) - Part C Program Details',
        'department' => $department,
        'academicYear' => $academicYear,
        'semester' => null,
        'documentNo' => "SBTE/AUDIT/{$academicYear}"
    ])

    <div class="space-y-4">
      <div class="space-y-2">
        <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider">1. Program & NBA Details</h3>
        <table class="w-full border-collapse border border-slate-200 text-left text-xs">
          <tbody>
            <tr class="border-b border-slate-200">
              <td class="p-2 font-bold text-slate-700 bg-slate-50 w-48">Name of Program:</td>
              <td class="p-2 text-slate-900">{{ $department }}</td>
            </tr>
            <tr class="border-b border-slate-200">
              <td class="p-2 font-bold text-slate-700 bg-slate-50">Name of HOD:</td>
              <td class="p-2 text-slate-900">{{ $auditData['professional_activities']['hod_name'] ?? 'Not Specified' }}</td>
            </tr>
            <tr class="border-b border-slate-200">
              <td class="p-2 font-bold text-slate-700 bg-slate-50">No. of Faculty / Lab staff:</td>
              <td class="p-2 text-slate-900">{{ $auditData['professional_activities']['faculty_count'] ?? '0' }}</td>
            </tr>
            <tr class="border-b border-slate-200">
              <td class="p-2 font-bold text-slate-700 bg-slate-50">NBA Status:</td>
              <td class="p-2 font-bold text-slate-900">{{ !empty($auditData['nba_accredited']) ? 'Accredited' : 'Not Accredited / Eligible' }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="space-y-2">
        <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider">2. Evaluative Criteria Marks Ledger</h3>
        <table class="w-full border-collapse border border-slate-200 text-left text-xs">
          <thead>
            <tr class="bg-indigo-950 text-white font-bold">
              <th class="p-2 w-12 text-center">No.</th>
              <th class="p-2 w-48">Audit Aspect</th>
              <th class="p-2">Academic Parameter & Data Record</th>
              <th class="p-2 w-28 text-center">Audit Score</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            <tr class="border-b border-slate-200">
              <td class="p-2 text-center font-bold">1</td>
              <td class="p-2 font-semibold">Sanctioned Intake & Enrollment</td>
              <td class="p-2">
                <table class="w-full text-center border-collapse text-[11px]">
                  <thead>
                    <tr class="bg-slate-100 font-bold">
                      <th class="p-1">Year</th>
                      <th class="p-1">Sanctioned</th>
                      <th class="p-1">Actual</th>
                      <th class="p-1">LET</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $enroll = $auditData['enrollment'] ?? []; @endphp
                    @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
                      <tr>
                        <td class="p-1 font-bold">{{ $y }}</td>
                        <td class="p-1">{{ $enroll[$y]['sanctioned'] ?? '-' }}</td>
                        <td class="p-1">{{ $enroll[$y]['actual'] ?? '-' }}</td>
                        <td class="p-1">{{ $enroll[$y]['let'] ?? '-' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </td>
              <td class="p-2 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 10</td>
            </tr>
            <tr class="border-b border-slate-200">
              <td class="p-2 text-center font-bold">2</td>
              <td class="p-2 font-semibold">Success Rate without Backlog</td>
              <td class="p-2">
                <table class="w-full text-center border-collapse text-[11px]">
                  <thead>
                    <tr class="bg-slate-100 font-bold">
                      <th class="p-1">Sem</th>
                      <th class="p-1">CAY (Pass/Reg)</th>
                      <th class="p-1">CAY-1 (Pass/Reg)</th>
                      <th class="p-1">CAY-2 (Pass/Reg)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $pnb = $auditData['perf_no_backlog'] ?? []; @endphp
                    @for($s = 1; $s <= 6; $s++)
                      <tr>
                        <td class="p-1 font-bold">S{{ $s }}</td>
                        <td class="p-1">{{ $pnb[$s]['CAY']['pass'] ?? '-' }}/{{ $pnb[$s]['CAY']['reg'] ?? '-' }}</td>
                        <td class="p-1">{{ $pnb[$s]['CAY-1']['pass'] ?? '-' }}/{{ $pnb[$s]['CAY-1']['reg'] ?? '-' }}</td>
                        <td class="p-1">{{ $pnb[$s]['CAY-2']['pass'] ?? '-' }}/{{ $pnb[$s]['CAY-2']['reg'] ?? '-' }}</td>
                      </tr>
                    @endfor
                  </tbody>
                </table>
              </td>
              <td class="p-2 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 25</td>
            </tr>
            <tr class="border-b border-slate-200">
              <td class="p-2 text-center font-bold">3</td>
              <td class="p-2 font-semibold">Success Rate with Backlog</td>
              <td class="p-2">
                <table class="w-full text-center border-collapse text-[11px]">
                  <thead>
                    <tr class="bg-slate-100 font-bold">
                      <th class="p-1">Sem</th>
                      <th class="p-1">CAY (Pass/Reg)</th>
                      <th class="p-1">CAY-1 (Pass/Reg)</th>
                      <th class="p-1">CAY-2 (Pass/Reg)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $pwb = $auditData['perf_with_backlog'] ?? []; @endphp
                    @for($s = 1; $s <= 6; $s++)
                      <tr>
                        <td class="p-1 font-bold">S{{ $s }}</td>
                        <td class="p-1">{{ $pwb[$s]['CAY']['pass'] ?? '-' }}/{{ $pwb[$s]['CAY']['reg'] ?? '-' }}</td>
                        <td class="p-1">{{ $pwb[$s]['CAY-1']['pass'] ?? '-' }}/{{ $pwb[$s]['CAY-1']['reg'] ?? '-' }}</td>
                        <td class="p-1">{{ $pwb[$s]['CAY-2']['pass'] ?? '-' }}/{{ $pwb[$s]['CAY-2']['reg'] ?? '-' }}</td>
                      </tr>
                    @endfor
                  </tbody>
                </table>
              </td>
              <td class="p-2 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 15</td>
            </tr>
            <tr class="border-b border-slate-200">
              <td class="p-2 text-center font-bold">4</td>
              <td class="p-2 font-semibold">Placement & Higher Studies</td>
              <td class="p-2">
                <table class="w-full text-center border-collapse text-[11px]">
                  <thead>
                    <tr class="bg-slate-100 font-bold">
                      <th class="p-1">Year</th>
                      <th class="p-1">Final Grads</th>
                      <th class="p-1">Placed</th>
                      <th class="p-1">Higher Ed</th>
                      <th class="p-1">Entrepreneurs</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $place = $auditData['placement'] ?? []; @endphp
                    @foreach(['CAY', 'CAY-1', 'CAY-2'] as $y)
                      <tr>
                        <td class="p-1 font-bold">{{ $y }}</td>
                        <td class="p-1">{{ $place[$y]['final_year'] ?? '-' }}</td>
                        <td class="p-1">{{ $place[$y]['placed'] ?? '-' }}</td>
                        <td class="p-1">{{ $place[$y]['higher_studies'] ?? '-' }}</td>
                        <td class="p-1">{{ $place[$y]['entrepreneur'] ?? '-' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </td>
              <td class="p-2 text-center bg-slate-50 italic text-slate-400 font-bold">______ / 20</td>
            </tr>

            {{-- Include Criteria 7 to 16 partial --}}
            @include('hod.partials.sbte_audit_criteria_tables')
          </tbody>
        </table>
      </div>
    </div>

    @include('reports.partials.signatures', [
        'facultyLabel' => 'Head of Department (HOD)',
        'hodLabel' => 'Academic Auditor / Expert',
        'principalLabel' => 'Principal'
    ])
</x-layouts.report-layout>
