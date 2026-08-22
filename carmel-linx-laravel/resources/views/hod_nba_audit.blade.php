@php
  $criteriaNames = [
    1 => 'Vision, Mission & Program Educational Objectives',
    2 => 'Program Curriculum & Teaching-Learning Process',
    3 => 'Course Outcomes & Program Outcomes (CO-PO)',
    4 => "Students' Performance & Success Rate",
    5 => 'Faculty Information & Contributions',
    6 => 'Facilities & Technical Support',
    7 => 'Continuous Improvement',
    8 => 'First Year Academics',
    9 => 'Student Support Systems & Governance'
  ];

  $totalDocs = 0;
  $verifiedCount = 0;
  $uploadedCount = 0;
  $pendingCount = 0;

  for ($i = 1; $i <= 9; $i++) {
    if (isset($documents[$i])) {
      foreach ($documents[$i] as $doc) {
        $totalDocs++;
        if ($doc->status === 'Verified') {
          $verifiedCount++;
        } elseif ($doc->status === 'Uploaded') {
          $uploadedCount++;
        } else {
          $pendingCount++;
        }
      }
    }
  }
@endphp

<x-layouts.app-shell 
    title="CampusLynk - NBA Criteria Accreditation" 
    topbarTitle="NBA Criteria Accreditation" 
    topbarSubtitle="NBA Self Assessment Report document compliance and accreditation readiness."
    activeNav="report_centre">

  <style>
    .criteria-card.hidden-filter {
      display: none;
    }
    .filter-btn.active {
      background-color: #2563eb;
      color: #ffffff;
      border-color: #2563eb;
    }
    .filter-btn:not(.active) {
      background-color: #ffffff;
      color: #475569;
      border-color: #e2e8f0;
    }
    .filter-btn:not(.active):hover {
      background-color: #f8fafc;
      color: #0f172a;
    }
  </style>

  <div class="space-y-6 max-w-7xl mx-auto pb-12">
    
    <!-- Breadcrumb Navigation -->
    <div class="flex items-center gap-2 text-sm text-slate-500">
      <a href="/dashboard/hod?panel=report_centre" class="hover:text-blue-600 font-medium transition-colors flex items-center gap-1.5 no-underline">
        <i data-lucide="bar-chart-3" class="w-4 h-4 text-slate-400"></i>
        <span>Report Centre</span>
      </a>
      <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i>
      <span class="text-slate-900 font-semibold">NBA Criteria Accreditation</span>
    </div>

    @if(session('success'))
      <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 p-4 rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-xs">
        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if(session('error'))
      <div class="bg-rose-50 border border-rose-200 text-rose-900 p-4 rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-xs">
        <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 shrink-0"></i>
        <span>{{ session('error') }}</span>
      </div>
    @endif

    <!-- Header & Hero Panel -->
    <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
          <i data-lucide="shield-check" class="w-6 h-6 text-blue-600"></i>
        </div>
        <div>
          <h3 class="text-base font-bold text-slate-900">NBA Criteria Accreditation Folders</h3>
          <p class="text-xs text-slate-500 mt-0.5">Manage, upload, and verify accreditation documents across Criteria 1 to 9 for the Self Assessment Report.</p>
        </div>
      </div>

      <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 shrink-0">
        <!-- Academic Year Switcher Form -->
        <form method="GET" action="/hod/nba-audit" class="flex items-center gap-2">
          <div class="relative">
            <select name="academic_year" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-sm font-bold text-slate-800 outline-none cursor-pointer hover:bg-slate-100 transition-colors focus:border-blue-600">
              <option value="2025-2026" {{ $academicYear === '2025-2026' ? 'selected' : '' }}>AY 2025–2026</option>
              <option value="2026-2027" {{ $academicYear === '2026-2027' ? 'selected' : '' }}>AY 2026–2027</option>
              <option value="2024-2025" {{ $academicYear === '2024-2025' ? 'selected' : '' }}>AY 2024–2025</option>
            </select>
          </div>
        </form>

        <a href="/hod/nba-audit/print?academic_year={{ urlencode($academicYear) }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-xs flex items-center gap-2 no-underline cursor-pointer">
          <i data-lucide="printer" class="w-4 h-4"></i>
          <span>Print Audit Sheet</span>
        </a>

        <a href="/dashboard/hod?panel=report_centre" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-medium text-xs border border-slate-200 rounded-xl shadow-2xs transition-all duration-200 flex items-center gap-1.5 no-underline cursor-pointer">
          <i data-lucide="arrow-left" class="w-3.5 h-3.5 text-slate-500"></i>
          <span>Back to Report Centre</span>
        </a>
      </div>
    </div>

    <!-- Overview Metrics Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      <div class="bg-white border border-slate-200/80 rounded-2xl p-4.5 shadow-xs flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 border border-blue-100">
          <i data-lucide="folder" class="w-5 h-5"></i>
        </div>
        <div>
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Criteria Folders</span>
          <h4 class="text-lg font-bold text-slate-900 mt-0.5">9 Folders</h4>
        </div>
      </div>

      <div class="bg-white border border-slate-200/80 rounded-2xl p-4.5 shadow-xs flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-slate-50 text-slate-700 flex items-center justify-center shrink-0 border border-slate-200">
          <i data-lucide="file-text" class="w-5 h-5"></i>
        </div>
        <div>
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Documents</span>
          <h4 class="text-lg font-bold text-slate-900 mt-0.5">{{ $totalDocs }} Files</h4>
        </div>
      </div>

      <div class="bg-white border border-slate-200/80 rounded-2xl p-4.5 shadow-xs flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200">
          <i data-lucide="check-circle-2" class="w-5 h-5"></i>
        </div>
        <div>
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Uploaded / Ready</span>
          <h4 class="text-lg font-bold text-emerald-700 mt-0.5">{{ $uploadedCount + $verifiedCount }} Uploaded</h4>
        </div>
      </div>

      <div class="bg-white border border-slate-200/80 rounded-2xl p-4.5 shadow-xs flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200">
          <i data-lucide="clock" class="w-5 h-5"></i>
        </div>
        <div>
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pending Audit</span>
          <h4 class="text-lg font-bold text-slate-800 mt-0.5">{{ $pendingCount }} Missing</h4>
        </div>
      </div>
    </div>

    <!-- Quick Segment Filter Navigation -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-2 border border-slate-200/80 rounded-2xl shadow-xs">
      <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
        <button type="button" onclick="filterCriteria('all')" id="filter_all" class="filter-btn active px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
          <span>All Criteria (1–9)</span>
        </button>
        <button type="button" onclick="filterCriteria('group1')" id="filter_group1" class="filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
          <span>Criteria 1–3: Curriculum & Outcomes</span>
        </button>
        <button type="button" onclick="filterCriteria('group2')" id="filter_group2" class="filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
          <span>Criteria 4–6: Students & Faculty</span>
        </button>
        <button type="button" onclick="filterCriteria('group3')" id="filter_group3" class="filter-btn px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
          <span>Criteria 7–9: Improvement & Governance</span>
        </button>
      </div>

      <div class="text-xs font-bold text-slate-400 px-3">
        Self Assessment Report
      </div>
    </div>

    <!-- 9 Criteria Folders Workspace Stack -->
    <div class="space-y-5" id="criteriaContainer">
      @for($i = 1; $i <= 9; $i++)
        @php
          $folderName = $criteriaNames[$i] ?? "Criteria $i";
          $folderDocs = $documents[$i] ?? collect();
          $groupClass = ($i <= 3) ? 'group1' : (($i <= 6) ? 'group2' : 'group3');
          $uploadedInFolder = $folderDocs->whereIn('status', ['Uploaded', 'Verified'])->count();
        @endphp

        <div class="criteria-card bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden transition-all duration-200" data-group="{{ $groupClass }}">
          
          <!-- Folder Header -->
          <div class="bg-slate-50/80 px-6 py-4.5 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3.5">
              <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 font-black text-sm flex items-center justify-center border border-blue-100 shrink-0">
                0{{ $i }}
              </div>
              <div>
                <h4 class="text-base font-bold text-slate-900">Criteria {{ $i }}: {{ $folderName }}</h4>
                <p class="text-xs text-slate-500 font-medium mt-0.5">National Board of Accreditation &bull; Self Assessment Report (SAR)</p>
              </div>
            </div>

            <div class="flex items-center gap-2">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $uploadedInFolder === $folderDocs->count() && $folderDocs->count() > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $uploadedInFolder === $folderDocs->count() && $folderDocs->count() > 0 ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                {{ $uploadedInFolder }} of {{ $folderDocs->count() }} Attached
              </span>
            </div>
          </div>

          <!-- Folder Document Items -->
          <div class="p-6 divide-y divide-slate-100">
            @if(isset($documents[$i]) && count($documents[$i]) > 0)
              @foreach($documents[$i] as $doc)
                <div class="py-4.5 first:pt-0 last:pb-0 flex flex-col md:flex-row md:items-center justify-between gap-4">
                  <div class="space-y-2">
                    <div class="flex items-center gap-2.5">
                      <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 flex items-center justify-center shrink-0 border border-slate-200">
                        <i data-lucide="file-text" class="w-4 h-4 text-slate-500"></i>
                      </div>
                      <div>
                        <p class="text-sm font-bold text-slate-900">{{ $doc->document_name }}</p>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Academic Year: {{ $doc->academic_year }}</p>
                      </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pl-10.5">
                      @if($doc->status === 'Verified')
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-lg">
                          <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-600"></i>
                          <span>Verified by Auditor</span>
                        </span>
                      @elseif($doc->status === 'Uploaded')
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-lg">
                          <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                          <span>Uploaded (Audit Pending)</span>
                        </span>
                      @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200 px-2.5 py-1 rounded-lg">
                          <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-slate-400"></i>
                          <span>Missing / Pending Upload</span>
                        </span>
                      @endif

                      @if($doc->file_path)
                        <a href="{{ $doc->file_path }}" target="_blank" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 no-underline">
                          <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                          <span>View Document</span>
                        </a>
                      @endif
                    </div>
                  </div>

                  <!-- Native Form File Upload Trigger -->
                  <div class="w-full md:w-auto pl-10.5 md:pl-0">
                    <form method="POST" action="/hod/nba-audit/upload" enctype="multipart/form-data" class="flex items-center gap-2">
                      @csrf
                      <input type="hidden" name="id" value="{{ $doc->id }}">
                      <label class="cursor-pointer px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs border border-slate-200 hover:border-slate-300 rounded-xl transition-all shadow-2xs flex items-center justify-center gap-2">
                        <i data-lucide="upload-cloud" class="w-4 h-4 text-blue-600"></i>
                        <span>{{ $doc->file_path ? 'Replace Document' : 'Upload Document' }}</span>
                        <input type="file" name="file" accept=".pdf,image/*" onchange="this.form.submit()" class="hidden">
                      </label>
                    </form>
                  </div>
                </div>
              @endforeach
            @else
              <p class="text-slate-400 italic text-sm p-4">No compliance files registered for this criteria folder.</p>
            @endif
          </div>

        </div>
      @endfor
    </div>

  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      if (window.lucide) {
        lucide.createIcons();
      }
    });

    function filterCriteria(group) {
      document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
      const activeBtn = document.getElementById('filter_' + group);
      if (activeBtn) activeBtn.classList.add('active');

      const cards = document.querySelectorAll('.criteria-card');
      cards.forEach(card => {
        if (group === 'all' || card.getAttribute('data-group') === group) {
          card.classList.remove('hidden-filter');
        } else {
          card.classList.add('hidden-filter');
        }
      });

      if (window.lucide) {
        lucide.createIcons();
      }
    }
    window.filterCriteria = filterCriteria;
  </script>

</x-layouts.app-shell>
