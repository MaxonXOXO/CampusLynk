@php
  $semNames  = ['I','II','III','IV','V','VI'];
  $allMonths = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  $configuredCount = $calendars->count();
@endphp

<x-layouts.app-shell 
    title="CampusLynk - Academic Calendar Planner" 
    topbarTitle="Academic Calendar" 
    topbarSubtitle="Plan semester calendars, SITTTR reference dates, academic events, and departmental working days."
    activeNav="report_centre">

  <style>
    .sem-body {
      display: none;
    }
    .sem-body.open {
      display: block;
    }
    .chevron {
      transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .chevron.open {
      transform: rotate(180deg);
    }
    .upload-zone.has-file {
      border-color: #10b981;
      background-color: rgba(16, 185, 129, 0.04);
    }
  </style>

  <!-- Global Toast Notification Banner -->
  <div id="globalAlert" class="fixed top-20 right-6 z-50 hidden max-w-md p-4 rounded-2xl shadow-xl border text-sm font-semibold transition-all duration-300 transform"></div>

  <div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Breadcrumb Navigation -->
    <div class="flex items-center gap-2 text-sm text-slate-500">
      <a href="/dashboard/hod?panel=report_centre" class="hover:text-blue-600 font-medium transition-colors flex items-center gap-1.5 no-underline">
        <i data-lucide="bar-chart-3" class="w-4 h-4 text-slate-400"></i>
        <span>Report Centre</span>
      </a>
      <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i>
      <span class="text-slate-900 font-semibold">Academic Calendar Planner</span>
    </div>

    <!-- Workspace Header & Overview Card -->
    <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shrink-0">
          <i data-lucide="calendar-range" class="w-6 h-6 text-blue-600"></i>
        </div>
        <div>
          <h3 class="text-base font-bold text-slate-900">Academic Calendar Planner</h3>
          <p class="text-xs text-slate-500 mt-0.5">Configure semester milestone schedules, SITTTR reference events, working days, and generate official A4 printables.</p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="/dashboard/hod?panel=report_centre" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-medium text-xs border border-slate-200 rounded-xl shadow-2xs transition-all duration-200 flex items-center gap-1.5 no-underline cursor-pointer">
          <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
          <span>Back to Report Centre</span>
        </a>
      </div>
    </div>

    <!-- Contextual Guidance Card -->
    <div class="bg-blue-50/60 border border-blue-200/80 rounded-2xl p-5 shadow-xs flex items-start gap-3.5">
      <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
        <i data-lucide="info" class="w-5 h-5 text-blue-700"></i>
      </div>
      <div class="space-y-1 text-sm">
        <h4 class="font-bold text-blue-950">How to Use the Semester Academic Calendar Desk</h4>
        <p class="text-blue-800 leading-relaxed text-xs">
          <strong>1.</strong> Select any semester below to expand its workspace. &nbsp;&bull;&nbsp;
          <strong>2.</strong> Attach the state SITTTR PDF reference and click <span class="font-semibold text-purple-700">"Auto-Fetch from PDF"</span> to automatically extract dates and activities via Gemini AI. &nbsp;&bull;&nbsp;
          <strong>3.</strong> Review, add custom departmental events, and click <strong>"Save Calendar"</strong>. &nbsp;&bull;&nbsp;
          <strong>4.</strong> Click <strong>"Print A4"</strong> to generate the formatted monthly breakdown with automated working-day totals and Sunday shading.
        </p>
      </div>
    </div>

    <!-- 6 Semester Accordion Workspace Stack -->
    <div class="space-y-4">
      @for($sem = 1; $sem <= 6; $sem++)
        @php
          $cal     = $calendars->get($sem);
          $entries = $cal ? json_decode($cal->activities ?? '[]', true) : [];
          $semRoman = $semNames[$sem-1];
        @endphp

        <div class="sem-panel bg-white border border-slate-200/80 rounded-2xl shadow-xs transition-all duration-200 overflow-hidden" id="panel_{{ $sem }}">
          
          <!-- Accordion Header -->
          <div class="sem-header p-5 flex items-center justify-between cursor-pointer select-none hover:bg-slate-50/70 transition-colors border-b border-transparent" onclick="togglePanel({{ $sem }})">
            <div class="flex items-center gap-4">
              <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-700 font-black text-base flex items-center justify-center border border-blue-100 shrink-0">
                {{ $semRoman }}
              </div>
              <div>
                <div class="flex items-center gap-2">
                  <h4 class="text-base font-bold text-slate-900">Semester {{ $semRoman }}</h4>
                  @if($cal)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Saved
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                      Not Configured
                    </span>
                  @endif
                </div>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">
                  @if($cal)
                    Academic Year: <span class="font-semibold text-slate-700">{{ $cal->academic_year }}</span> &bull; {{ count($entries) }} {{ count($entries)==1?'entry':'entries' }} mapped
                  @else
                    Click to configure milestones, upload SITTTR reference, and generate calendar
                  @endif
                </p>
              </div>
            </div>

            <div class="flex items-center gap-3">
              @if($cal)
                <a href="/hod/academic-calendar/{{ $cal->id }}/print" target="_blank" onclick="event.stopPropagation()"
                   class="px-3.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold text-xs border border-blue-200 rounded-xl transition-all flex items-center gap-1.5 no-underline shadow-2xs">
                  <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                  <span>Print A4</span>
                </a>
              @endif
              <div class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <i data-lucide="chevron-down" id="chevron_{{ $sem }}" class="chevron w-5 h-5 transition-transform duration-200"></i>
              </div>
            </div>
          </div>

          <!-- Accordion Body -->
          <div class="sem-body border-t border-slate-100 p-6 space-y-6 bg-slate-50/20" id="body_{{ $sem }}">
            
            <!-- Row 1: Academic Year & SITTTR PDF Dropzone -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
              
              <!-- Academic Year Input -->
              <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Academic Year</label>
                <div class="relative">
                  <input id="year_{{ $sem }}" type="text" value="{{ $cal->academic_year ?? date('Y').'-'.(date('Y')+1) }}" 
                         class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-medium focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition-colors" 
                         placeholder="e.g. 2025-2026">
                </div>
                <p class="text-xs text-slate-400 mt-1">Assessment year boundary for term dates.</p>
              </div>

              <!-- SITTTR Reference PDF Dropzone -->
              <div class="md:col-span-2">
                <div class="flex items-center justify-between mb-1.5">
                  <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">
                    SITTTR Reference PDF (Reference Only)
                  </label>
                  @if($cal && $cal->pdf_path)
                    <a href="/storage/{{ $cal->pdf_path }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 no-underline">
                      <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                      <span>View Attached PDF</span>
                    </a>
                  @endif
                </div>

                <div class="upload-zone {{ ($cal && $cal->pdf_path) ? 'has-file' : '' }} border-2 border-dashed border-slate-200 hover:border-blue-400 bg-white rounded-xl p-3.5 text-center cursor-pointer transition-all duration-200" 
                     id="uz_{{ $sem }}" 
                     onclick="document.getElementById('pdf_{{ $sem }}').click()">
                  @if($cal && $cal->pdf_path)
                    <div class="flex items-center justify-center gap-2 text-emerald-700 font-bold text-xs">
                      <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
                      <span>PDF Reference Attached — click to replace file</span>
                    </div>
                  @else
                    <div class="flex items-center justify-center gap-2 text-slate-700 font-semibold text-xs">
                      <i data-lucide="upload-cloud" class="w-4 h-4 text-blue-600"></i>
                      <span>Click to upload SITTTR Academic Calendar PDF</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Used by AI extraction engine to populate calendar events.</p>
                  @endif
                </div>
                <input type="file" id="pdf_{{ $sem }}" accept=".pdf" class="hidden" onchange="onFile({{ $sem }})">
              </div>

            </div>

            <!-- Row 2: Month-wise Calendar Entries Table -->
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div>
                  <h5 class="text-sm font-bold text-slate-900">Month-Wise Calendar Entries</h5>
                  <p class="text-xs text-slate-500">Add or review scheduled academic, examination, holiday, and departmental activities.</p>
                </div>
                <button type="button" onclick="addRow({{ $sem }})" 
                        class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs">
                  <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                  <span>Add Row</span>
                </button>
              </div>

              <div class="bg-white border border-slate-200/80 rounded-xl overflow-hidden shadow-2xs">
                <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse" id="ct_{{ $sem }}">
                    <thead>
                      <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider">
                        <th class="p-3 w-40">Month</th>
                        <th class="p-3 w-28 text-center">Date</th>
                        <th class="p-3">Activity / Event Description</th>
                        <th class="p-3 w-48">Classification</th>
                        <th class="p-3 w-12 text-center"></th>
                      </tr>
                    </thead>
                    <tbody id="cb_{{ $sem }}" class="divide-y divide-slate-100">
                      @foreach($entries as $e)
                        <tr class="erow hover:bg-slate-50/60 transition-colors">
                          <td class="p-2.5">
                            <select class="finput e-month w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
                              @foreach($allMonths as $mn)
                                <option value="{{ $mn }}" {{ ($e['month']??'')===$mn?'selected':'' }}>{{ $mn }}</option>
                              @endforeach
                            </select>
                          </td>
                          <td class="p-2.5">
                            <input type="number" min="1" max="31" value="{{ $e['date'] ?? '' }}" placeholder="1-31" 
                                   class="finput e-date w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 font-medium text-center focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
                          </td>
                          <td class="p-2.5">
                            <input type="text" value="{{ $e['activity'] ?? '' }}" placeholder="Activity description..." 
                                   class="finput e-activity w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none placeholder:text-slate-400">
                          </td>
                          <td class="p-2.5">
                            <select class="finput e-type w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
                              @foreach(['Academic','Exam','Holiday','Event','Department','Other'] as $t)
                                <option value="{{ $t }}" {{ ($e['type']??'Academic')===$t?'selected':'' }}>{{ $t }}</option>
                              @endforeach
                            </select>
                          </td>
                          <td class="p-2.5 text-center">
                            <button type="button" onclick="this.closest('tr').remove()" 
                                    class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" 
                                    title="Remove row">
                              <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Row 3: Action Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
              <button type="button" onclick="addRow({{ $sem }})" 
                      class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm border border-slate-200 rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                <i data-lucide="plus" class="w-4 h-4 text-slate-500"></i>
                <span>Add Row</span>
              </button>

              <div class="flex flex-wrap items-center gap-3">
                <button type="button" id="fetchBtn_{{ $sem }}" onclick="fetchFromPdf({{ $sem }})" 
                        class="px-4 py-2.5 bg-purple-50 hover:bg-purple-100 text-purple-700 font-semibold text-sm border border-purple-200 rounded-xl transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
                  <i data-lucide="sparkles" class="w-4 h-4 text-purple-600"></i>
                  <span id="fetchLabel_{{ $sem }}">Auto-Fetch from PDF</span>
                </button>

                <button type="button" onclick="prefill({{ $sem }})" 
                        class="px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-sm border border-slate-200 rounded-xl transition-all flex items-center gap-2 cursor-pointer shadow-2xs">
                  <i data-lucide="file-text" class="w-4 h-4 text-slate-500"></i>
                  <span>Manual Template</span>
                </button>

                <button type="button" onclick="save({{ $sem }})" 
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm rounded-xl transition-all flex items-center gap-2 cursor-pointer shadow-xs">
                  <i data-lucide="save" class="w-4 h-4"></i>
                  <span>Save Calendar</span>
                </button>
              </div>
            </div>

          </div>
        </div>
      @endfor
    </div>

  </div>

  <script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const ALL_MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const TYPES      = ['Academic','Exam','Holiday','Event','Department','Other'];

    document.addEventListener("DOMContentLoaded", () => {
      if (window.lucide) {
        lucide.createIcons();
      }
    });

    function togglePanel(sem) {
      const b = document.getElementById('body_' + sem);
      const c = document.getElementById('chevron_' + sem);
      if (!b || !c) return;
      const open = b.classList.contains('open');
      document.querySelectorAll('.sem-body').forEach(x => x.classList.remove('open'));
      document.querySelectorAll('.chevron').forEach(x => x.classList.remove('open'));
      if (!open) {
        b.classList.add('open');
        c.classList.add('open');
      }
    }
    window.togglePanel = togglePanel;

    function onFile(sem) {
      const fileInput = document.getElementById('pdf_' + sem);
      if (!fileInput || !fileInput.files[0]) return;
      const f = fileInput.files[0];
      const z = document.getElementById('uz_' + sem);
      if (!z) return;
      z.classList.add('has-file');
      z.innerHTML = `
        <div class="flex items-center justify-center gap-2 text-emerald-700 font-bold text-xs">
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
          <span>${f.name} (Ready to upload)</span>
        </div>
        <p class="text-xs text-emerald-600 mt-0.5">Click to change file</p>
      `;
      z.onclick = () => document.getElementById('pdf_' + sem).click();
    }
    window.onFile = onFile;

    function makeRow(sem, month='', date='', activity='', type='Academic') {
      const tr = document.createElement('tr');
      tr.className = 'erow hover:bg-slate-50/60 transition-colors';
      const mOpts = ALL_MONTHS.map(m => `<option value="${m}" ${m===month?'selected':''}>${m}</option>`).join('');
      const tOpts = TYPES.map(t => `<option value="${t}" ${t===type?'selected':''}>${t}</option>`).join('');
      tr.innerHTML = `
        <td class="p-2.5">
          <select class="finput e-month w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
            ${mOpts}
          </select>
        </td>
        <td class="p-2.5">
          <input type="number" min="1" max="31" value="${date}" placeholder="1-31" class="finput e-date w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 font-medium text-center focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none">
        </td>
        <td class="p-2.5">
          <input type="text" value="${activity}" placeholder="Activity description..." class="finput e-activity w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none placeholder:text-slate-400">
        </td>
        <td class="p-2.5">
          <select class="finput e-type w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-900 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none cursor-pointer">
            ${tOpts}
          </select>
        </td>
        <td class="p-2.5 text-center">
          <button type="button" onclick="this.closest('tr').remove()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors flex items-center justify-center mx-auto cursor-pointer" title="Remove row">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
          </button>
        </td>`;
      return tr;
    }
    window.makeRow = makeRow;

    function addRow(sem) {
      const tbody = document.getElementById('cb_' + sem);
      if (tbody) {
        tbody.appendChild(makeRow(sem));
        if (window.lucide) lucide.createIcons();
      }
    }
    window.addRow = addRow;

    // Standard milestone templates per semester (Odd: Jun–Nov, Even: Nov–May)
    const templates = {
      1: [['June',2,'Classes commence — Induction/Orientation','Academic'],['August',15,'Independence Day','Holiday'],['October',2,'Gandhi Jayanti','Holiday'],['October',1,'Internal Assessment Week begins','Exam']],
      2: [['November',1,'Classes commence','Academic'],['January',26,'Republic Day','Holiday'],['March',1,'Internal Assessment','Exam'],['April',1,'Board Exam begins','Exam']],
      3: [['June',2,'Classes commence','Academic'],['August',15,'Independence Day','Holiday'],['September',1,'Internal Assessment','Exam'],['October',2,'Gandhi Jayanti','Holiday']],
      4: [['November',1,'Classes commence','Academic'],['January',26,'Republic Day','Holiday'],['February',1,'Internal Assessment','Exam'],['April',1,'Board Exam begins','Exam']],
      5: [['June',2,'Classes commence','Academic'],['August',15,'Independence Day','Holiday'],['September',1,'Internal Assessment','Exam'],['November',1,'Board Exam begins','Exam']],
      6: [['November',1,'Classes commence','Academic'],['January',26,'Republic Day','Holiday'],['March',1,'Internal Assessment','Exam'],['April',15,'Board Exam begins','Exam']],
    };

    function prefill(sem) {
      const tbody = document.getElementById('cb_' + sem);
      if (!tbody) return;
      if (tbody.children.length > 0 && !confirm('Add template rows? Existing rows will remain.')) return;
      (templates[sem] || []).forEach(([m, d, a, t]) => tbody.appendChild(makeRow(sem, m, d, a, t)));
      if (window.lucide) lucide.createIcons();
    }
    window.prefill = prefill;

    function save(sem) {
      const yearEl = document.getElementById('year_' + sem);
      const pdfEl  = document.getElementById('pdf_' + sem);
      if (!yearEl) return;

      const year  = yearEl.value.trim();
      const rows  = document.querySelectorAll('#cb_' + sem + ' .erow');
      const acts  = [];

      rows.forEach(tr => {
        const month    = tr.querySelector('.e-month')?.value || '';
        const date     = tr.querySelector('.e-date')?.value || '';
        const activity = tr.querySelector('.e-activity')?.value.trim() || '';
        const type     = tr.querySelector('.e-type')?.value || 'Academic';
        if (month || date || activity) acts.push({ month, date, activity, type });
      });

      const fd = new FormData();
      fd.append('semester', sem);
      fd.append('academic_year', year);
      fd.append('activities', JSON.stringify(acts));
      if (pdfEl && pdfEl.files[0]) fd.append('pdf', pdfEl.files[0]);

      fetch('/api/academic-calendar/save', { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF }, body: fd })
        .then(r => r.json())
        .then(d => {
          if (d.status === 'SUCCESS') { 
            alert_ok('Semester ' + sem + ' calendar saved successfully!'); 
            setTimeout(() => location.reload(), 1200); 
          } else {
            alert_err(d.message || 'Save failed.');
          }
        })
        .catch(() => alert_err('Network communication error.'));
    }
    window.save = save;

    function alert_ok(m) { 
      const el = document.getElementById('globalAlert'); 
      if (!el) return;
      el.className = 'fixed top-20 right-6 z-50 max-w-md p-4 rounded-2xl shadow-xl border bg-emerald-50 border-emerald-200 text-emerald-900 text-sm font-semibold flex items-center gap-2.5 transition-all duration-300';
      el.innerHTML = `<svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>${m}</span>`;
      setTimeout(() => { el.className = 'hidden'; }, 5000); 
    }
    window.alert_ok = alert_ok;

    function alert_err(m) { 
      const el = document.getElementById('globalAlert'); 
      if (!el) return;
      el.className = 'fixed top-20 right-6 z-50 max-w-md p-4 rounded-2xl shadow-xl border bg-rose-50 border-rose-200 text-rose-900 text-sm font-semibold flex items-center gap-2.5 transition-all duration-300';
      el.innerHTML = `<svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>${m}</span>`;
      setTimeout(() => { el.className = 'hidden'; }, 6000); 
    }
    window.alert_err = alert_err;

    /* ── Auto-Fetch from PDF via Gemini AI ─────────────────────────────── */
    function fetchFromPdf(sem) {
      const btn   = document.getElementById('fetchBtn_' + sem);
      const label = document.getElementById('fetchLabel_' + sem);
      if (!btn) return;

      // Set loading state
      btn.disabled = true;
      if (label) label.innerHTML = 'Reading PDF...';
      btn.classList.add('opacity-70', 'cursor-not-allowed');

      fetch('/api/academic-calendar/parse-pdf', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body: JSON.stringify({ semester: sem })
      })
      .then(r => r.json())
      .then(data => {
        btn.disabled = false;
        btn.classList.remove('opacity-70', 'cursor-not-allowed');
        if (label) label.textContent = 'Auto-Fetch from PDF';

        if (data.status !== 'SUCCESS') {
          alert_err('AI Fetch failed: ' + (data.message || 'Unknown error'));
          return;
        }

        const entries = data.entries || [];
        if (entries.length === 0) {
          alert_err('AI found no calendar entries in this PDF. Try entering manually.');
          return;
        }

        // Collect existing month+date keys to avoid duplicates
        const tbody    = document.getElementById('cb_' + sem);
        if (!tbody) return;
        const existing = new Set();
        tbody.querySelectorAll('.erow').forEach(tr => {
          const m = tr.querySelector('.e-month')?.value || '';
          const d = tr.querySelector('.e-date')?.value  || '';
          if (m && d) existing.add(m + '|' + d);
        });

        let added = 0;
        entries.forEach(e => {
          const key = e.month + '|' + e.date;
          if (!existing.has(key)) {
            tbody.appendChild(makeRow(sem, e.month, e.date, e.activity, e.type));
            existing.add(key);
            added++;
          }
        });

        // Success notification
        const skipped = entries.length - added;
        let msg = `AI extracted ${entries.length} entries — ${added} added to table`;
        if (skipped > 0) msg += `, ${skipped} skipped (already in table)`;
        alert_ok(msg + '. Review, add custom remarks, then click Save.');
        if (window.lucide) lucide.createIcons();
      })
      .catch(err => {
        btn.disabled = false;
        btn.classList.remove('opacity-70', 'cursor-not-allowed');
        if (label) label.textContent = 'Auto-Fetch from PDF';
        alert_err('Network communication error: ' + err.message);
      });
    }
    window.fetchFromPdf = fetchFromPdf;
  </script>

</x-layouts.app-shell>
