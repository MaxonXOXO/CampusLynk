{{-- Academic Calendar Month Block (Two Column: Left Date List, Right Mini Grid) --}}
@php
  $mNum      = $monthNum[$mName];
  $mYear     = resolveYear($mNum, $cal->semester, $startYear, $endYear);
  $daysInM   = cal_days_in_month(CAL_GREGORIAN, $mNum, $mYear);

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
  $startDow = (int)date('w', mktime(0,0,0,$mNum,1,$mYear));
@endphp

<div class="month-block break-inside-avoid mb-4">
  <div class="month-bar flex justify-between items-center bg-indigo-950 text-white px-3 py-1.5 rounded-t-lg">
    <span class="font-bold text-xs uppercase tracking-wider">{{ $mName }}</span>
    <span class="text-xs text-indigo-200 font-mono">{{ $mYear }}</span>
  </div>

  <div class="month-body flex border border-t-0 border-slate-300 text-xs">
    {{-- LEFT: Activities table --}}
    <div class="left-col w-[58%] border-r border-slate-300">
      <table class="w-full text-[11px]">
        <thead class="bg-slate-100 border-b border-slate-300">
          <tr>
            <th class="p-1 w-7 text-center">Dt</th>
            <th class="p-1 w-8">Day</th>
            <th class="p-1 text-left">Activity / Description</th>
            <th class="p-1 w-14 text-center">Type</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @php $hasAny = false; @endphp
          @for($d = 1; $d <= $daysInM; $d++)
            @php
              $act = $actByDate[$d] ?? null;
              if (!$act) continue;
              $dow   = (int)date('N', mktime(0,0,0,$mNum,$d,$mYear));
              $isSun = ($dow === 7);
              $dName = $dayNames[$dow];
              $type  = $act['type'] ?? 'Academic';
              $cls   = evCls($type, $isSun);
              $hasAny = true;
            @endphp
            <tr class="{{ $cls }}">
              <td class="p-1 text-center font-bold">{{ $d }}</td>
              <td class="p-1 text-slate-600">{{ $dName }}</td>
              <td class="p-1 text-slate-900 leading-tight">{{ $act['activity'] }}</td>
              <td class="p-1 text-center">
                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full
                  @if($type==='Holiday') bg-amber-100 text-amber-900 border border-amber-300
                  @elseif($type==='Exam') bg-yellow-100 text-yellow-900 border border-yellow-300
                  @elseif($type==='Event') bg-emerald-100 text-emerald-900 border border-emerald-300
                  @elseif($type==='Department') bg-purple-100 text-purple-900 border border-purple-300
                  @elseif($type==='Academic') bg-blue-100 text-blue-900 border border-blue-300
                  @else bg-slate-100 text-slate-700 border border-slate-300
                  @endif
                ">{{ $type }}</span>
              </td>
            </tr>
          @endfor
          @if(!$hasAny)
            <tr><td colspan="4" class="p-3 text-center text-slate-400 italic">No activities added for this month.</td></tr>
          @endif
        </tbody>
      </table>
    </div>

    {{-- RIGHT: Mini grid calendar --}}
    <div class="right-col w-[42%] p-2 bg-slate-50 flex flex-col justify-between">
      <div>
        <div class="text-[10px] font-bold text-center text-indigo-950 uppercase mb-1">{{ $mName }} {{ $mYear }}</div>
        <table class="w-full text-[10px] text-center border-collapse">
          <thead>
            <tr class="border-b border-slate-300 text-[9px] text-slate-500 font-bold">
              <th class="p-0.5 text-rose-600">S</th>
              <th class="p-0.5">M</th>
              <th class="p-0.5">T</th>
              <th class="p-0.5">W</th>
              <th class="p-0.5">T</th>
              <th class="p-0.5">F</th>
              <th class="p-0.5">S</th>
            </tr>
          </thead>
          <tbody>
            @php
              $totalCells = $startDow + $daysInM;
              $rows = ceil($totalCells / 7);
            @endphp
            @for($row = 0; $row < $rows; $row++)
              <tr>
                @for($col = 0; $col < 7; $col++)
                  @php
                    $cellIdx = $row * 7 + $col;
                    $d2      = $cellIdx - $startDow + 1;
                    $isBefore = ($d2 < 1);
                    $isAfter  = ($d2 > $daysInM);
                    $gcls     = 'text-slate-700';
                    if (!$isBefore && !$isAfter) {
                      $dow2   = (int)date('N', mktime(0,0,0,$mNum,$d2,$mYear));
                      $isSun2 = ($dow2 === 7);
                      $act2   = $actByDate[$d2] ?? null;
                      $gcls   = gcCls($act2['type'] ?? null, $isSun2);
                    }
                  @endphp
                  @if($isBefore || $isAfter)
                    <td class="p-0.5 text-slate-200">·</td>
                  @else
                    <td class="p-0.5 font-medium">
                      <span class="inline-block w-4 h-4 rounded {{ $gcls }}">{{ $d2 }}</span>
                    </td>
                  @endif
                @endfor
              </tr>
            @endfor
          </tbody>
        </table>
      </div>

      <div class="mt-2 pt-1 border-t border-slate-200 flex justify-between text-[10px] text-slate-600 font-mono">
        <span>Sun: <strong>{{ $sunCount }}</strong></span>
        <span>Hol: <strong>{{ $holCount }}</strong></span>
        <span class="text-indigo-900 font-bold">Work: {{ $workDays }}</span>
      </div>
    </div>
  </div>
</div>
