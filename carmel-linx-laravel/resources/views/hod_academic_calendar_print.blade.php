@php
  $semNames   = ['I','II','III','IV','V','VI'];
  $semLabel   = $semNames[$cal->semester - 1] ?? 'I';
  $branchMap  = [
    'EL'  => 'Electronics Engineering',
    'ME'  => 'Mechanical Engineering',
    'CE'  => 'Civil Engineering',
    'EEE' => 'Electrical & Electronics Engineering',
    'CT'  => 'Computer Engineering',
    'AU'  => 'Automobile Engineering',
    'GEN_AIDED' => 'General Department (Aided)',
    'GEN_SF'    => 'General Department (Self Finance)',
  ];
  $branchFull = $branchMap[$branch] ?? $branch;

  $yearParts = explode('-', $cal->academic_year);
  $startYear = (int)($yearParts[0] ?? date('Y'));
  $endYear   = (int)($yearParts[1] ?? ($startYear + 1));

  $monthNum = [
    'January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,
    'July'=>7,'August'=>8,'September'=>9,'October'=>10,'November'=>11,'December'=>12
  ];
  $dayNames = [1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat',7=>'Sun'];

  if (!function_exists('resolveYear')) {
    function resolveYear($mNum, $sem, $sy, $ey) {
      return ($sem % 2 === 1) ? ($mNum >= 6 ? $sy : $ey) : ($mNum >= 11 ? $sy : $ey);
    }
  }

  if (!function_exists('evCls')) {
    function evCls($type, $isSun) {
      if ($isSun) return 'bg-rose-50 text-rose-900';
      return match($type) {
        'Holiday'    => 'bg-amber-50 text-amber-900',
        'Exam'       => 'bg-yellow-50 text-yellow-900 font-semibold',
        'Event'      => 'bg-emerald-50 text-emerald-900',
        'Department' => 'bg-purple-50 text-purple-900',
        'Academic'   => 'bg-blue-50 text-blue-900',
        default      => 'text-slate-900',
      };
    }
  }

  if (!function_exists('gcCls')) {
    function gcCls($type, $isSun) {
      if ($isSun) return 'bg-rose-100 text-rose-800 font-bold';
      return match($type) {
        'Holiday'    => 'bg-amber-100 text-amber-800 font-bold',
        'Exam'       => 'bg-yellow-200 text-yellow-900 font-bold',
        'Event'      => 'bg-emerald-100 text-emerald-800 font-bold',
        'Department' => 'bg-purple-100 text-purple-800 font-bold',
        'Academic'   => 'bg-blue-100 text-blue-800 font-bold',
        default      => 'bg-slate-100 text-slate-800',
      };
    }
  }

  $byMonth = [];
  foreach ($activities as $act) {
    $m = trim($act['month'] ?? '');
    if (!$m || !isset($monthNum[$m])) continue;
    if (!isset($byMonth[$m])) $byMonth[$m] = [];
    $byMonth[$m][] = $act;
  }
  $orderedMonths = array_keys($byMonth);

  $summaryRows = [];
  $grandWork = $grandTotal = 0;
@endphp

<x-layouts.report-layout 
    title="Academic Calendar – Semester {{ $semLabel }} – {{ $branch }}" 
    orientation="portrait" 
    documentNo="{{ $branch }}/CAL/S{{ $semLabel }}">

    @include('reports.partials.header', [
        'title' => "ACADEMIC CALENDAR — SEMESTER {$semLabel}",
        'subtitle' => "Based on SITTTR Academic Schedule · Academic Year: {$cal->academic_year}",
        'department' => $branchFull,
        'academicYear' => $cal->academic_year,
        'semester' => "Semester {$semLabel}",
        'documentNo' => "{$branch}/CAL/S{$semLabel}"
    ])

    {{-- Key Legend --}}
    <div class="flex flex-wrap items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl p-2.5 mb-4 text-xs">
        <span class="font-bold text-slate-700 mr-1 text-[11px]">KEY:</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-rose-100 border border-rose-300"></span> Sunday</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-amber-100 border border-amber-300"></span> Holiday</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-yellow-200 border border-yellow-300"></span> Exam</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-100 border border-emerald-300"></span> Event</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-purple-100 border border-purple-300"></span> Dept. Activity</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-blue-100 border border-blue-300"></span> Academic</span>
    </div>

    @if(count($orderedMonths) === 0)
        <div class="text-center py-12 text-slate-400 italic text-sm">
            No calendar activities added for this semester.
        </div>
    @else
        @foreach($orderedMonths as $mName)
            @php
                $mNum    = $monthNum[$mName];
                $mYear   = resolveYear($mNum, $cal->semester, $startYear, $endYear);
                $daysInM = cal_days_in_month(CAL_GREGORIAN, $mNum, $mYear);

                $actByDate = [];
                foreach ($byMonth[$mName] as $a) {
                    $d = (int)($a['date'] ?? 0);
                    if ($d >= 1 && $d <= $daysInM) $actByDate[$d] = $a;
                }

                $workDays = $sunCount = $holCount = 0;
                for ($d = 1; $d <= $daysInM; $d++) {
                    $dow = (int)date('N', mktime(0,0,0,$mNum,$d,$mYear));
                    if ($dow === 7) { $sunCount++; continue; }
                    $t = $actByDate[$d]['type'] ?? '';
                    if ($t === 'Holiday') { $holCount++; continue; }
                    $workDays++;
                }
                $summaryRows[] = ['month' => "{$mName} {$mYear}", 'total' => $daysInM, 'sun' => $sunCount, 'hol' => $holCount, 'work' => $workDays];
                $grandTotal += $daysInM;
                $grandWork  += $workDays;
            @endphp

            @include('hod.partials.academic_calendar_month_block', [
                'mName' => $mName,
                'cal' => $cal,
                'monthNum' => $monthNum,
                'dayNames' => $dayNames,
                'byMonth' => $byMonth,
                'startYear' => $startYear,
                'endYear' => $endYear
            ])
        @endforeach

        {{-- Working Days Summary Table --}}
        <div class="mt-6 pt-4 border-t border-slate-200 break-inside-avoid">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-2">
                Working Days Summary — Semester {{ $semLabel }}
            </h3>
            <table class="w-full text-xs border border-slate-300 border-collapse">
                <thead class="bg-indigo-950 text-white">
                    <tr>
                        <th class="p-2 text-left">Month</th>
                        <th class="p-2 text-center">Total Days</th>
                        <th class="p-2 text-center">Sundays</th>
                        <th class="p-2 text-center">Holidays</th>
                        <th class="p-2 text-center">Working Days</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($summaryRows as $sr)
                        <tr>
                            <td class="p-2 font-bold">{{ $sr['month'] }}</td>
                            <td class="p-2 text-center font-mono">{{ $sr['total'] }}</td>
                            <td class="p-2 text-center font-mono">{{ $sr['sun'] }}</td>
                            <td class="p-2 text-center font-mono">{{ $sr['hol'] }}</td>
                            <td class="p-2 text-center font-mono font-bold text-indigo-900">{{ $sr['work'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-slate-100 font-bold border-t-2 border-slate-400">
                        <td class="p-2">TOTAL</td>
                        <td class="p-2 text-center font-mono">{{ $grandTotal }}</td>
                        <td class="p-2 text-center font-mono">—</td>
                        <td class="p-2 text-center font-mono">—</td>
                        <td class="p-2 text-center font-mono text-indigo-900">{{ $grandWork }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    @include('reports.partials.signatures', [
        'facultyLabel' => 'Head of Department',
        'hodLabel' => 'Academic Committee',
        'principalLabel' => 'Principal'
    ])
</x-layouts.report-layout>
