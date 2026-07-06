<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Academic Calendar – {{ $branch }} Department</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&family=Inter:wght@400;500;600;700;800;900&display=swap">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    * { font-family: 'Inter', sans-serif; }
    body { background: #0b0f1a; color: #e2e8f0; }
    .tp { transition: all 0.22s cubic-bezier(0.4,0,0.2,1); }

    .sem-panel { border: 1px solid #1e293b; background: rgba(15,23,42,0.7); border-radius: 16px; overflow: hidden; }
    .sem-header { display: flex; align-items: center; justify-content: space-between; padding: 0 20px; height: 66px; cursor: pointer; user-select: none; }
    .sem-header:hover { background: rgba(30,41,59,0.5); }
    .sem-body { display: none; border-top: 1px solid #1e293b; padding: 24px 20px; }
    .sem-body.open { display: block; }
    .chevron { transition: transform 0.22s ease; }
    .chevron.open { transform: rotate(180deg); }

    .cal-table { width: 100%; border-collapse: collapse; }
    .cal-table thead th {
      background: #0f172a; color: #64748b; font-size: 11px; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.06em; padding: 9px 10px;
      text-align: left; border-bottom: 1px solid #1e293b;
    }
    .cal-table tbody tr { border-bottom: 1px solid #1a2235; }
    .cal-table tbody tr:last-child { border-bottom: none; }
    .cal-table tbody td { padding: 5px 6px; vertical-align: middle; }
    .finput {
      width: 100%; background: #0f172a; border: 1px solid #334155; border-radius: 8px;
      padding: 7px 10px; font-size: 13px; color: #f1f5f9; outline: none;
    }
    .finput:focus { border-color: #3b82f6; }

    .badge-green { background:#052e16;color:#4ade80;border:1px solid #166534;border-radius:99px;padding:2px 10px;font-size:12px;font-weight:700; }
    .badge-slate { background:#1e293b;color:#64748b;border:1px solid #334155;border-radius:99px;padding:2px 10px;font-size:12px;font-weight:700; }

    .a1{color:#38bdf8;} .b1{border-color:#0ea5e9 !important;background:#061924;} .i1{border-left:4px solid #38bdf8;}
    .a2{color:#34d399;} .b2{border-color:#10b981 !important;background:#031a0e;} .i2{border-left:4px solid #34d399;}
    .a3{color:#fbbf24;} .b3{border-color:#f59e0b !important;background:#1a1000;} .i3{border-left:4px solid #fbbf24;}
    .a4{color:#f87171;} .b4{border-color:#ef4444 !important;background:#1a0000;} .i4{border-left:4px solid #f87171;}
    .a5{color:#c084fc;} .b5{border-color:#a855f7 !important;background:#100a24;} .i5{border-left:4px solid #c084fc;}
    .a6{color:#2dd4bf;} .b6{border-color:#14b8a6 !important;background:#001a18;} .i6{border-left:4px solid #2dd4bf;}

    .upload-zone { border: 2px dashed #334155; border-radius: 12px; padding: 16px; text-align: center; cursor: pointer; transition: border-color 0.2s; }
    .upload-zone:hover { border-color: #3b82f6; }
    .upload-zone.has-file { border-color: #22c55e; background: rgba(34,197,94,0.05); }

    .slabel { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.07em; color: #475569; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .slabel::after { content:''; flex:1; height:1px; background:#1e293b; }

    #globalAlert { position: fixed; top: 74px; left: 50%; transform: translateX(-50%); z-index: 999; min-width: 320px; display: none; padding: 14px 22px; border-radius: 12px; font-weight: 700; font-size: 14px; text-align: center; box-shadow: 0 8px 30px rgba(0,0,0,0.5); }
    #globalAlert.ok  { display:block; background:#052e16; border:1px solid #166534; color:#4ade80; }
    #globalAlert.err { display:block; background:#1f0000; border:1px solid #7f1d1d; color:#fca5a5; }
  </style>
</head>
<body style="min-height:100vh;">

  <!-- Top Bar -->
  <header style="height:60px;border-bottom:1px solid #1e293b;background:rgba(9,11,20,0.94);backdrop-filter:blur(14px);position:sticky;top:0;z-index:30;display:flex;align-items:center;justify-content:space-between;padding:0 28px;">
    <div style="display:flex;align-items:center;gap:14px;">
      <a href="/dashboard/hod" style="display:flex;align-items:center;gap:6px;color:#64748b;text-decoration:none;font-size:13px;font-weight:700;" class="tp">
        <span class="material-symbols-rounded" style="font-size:18px;">arrow_back</span> HOD Console
      </a>
      <span style="color:#1e293b;font-size:20px;">|</span>
      <span class="material-symbols-rounded" style="color:#fbbf24;font-size:20px;">calendar_month</span>
      <h1 style="font-size:15px;font-weight:800;color:#f1f5f9;margin:0;">
        Academic Calendar Planner — <span style="color:#fbbf24;">{{ $branch }}</span> Department
      </h1>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
      <a href="/hod/report-centre" style="font-size:13px;font-weight:700;color:#475569;text-decoration:none;display:flex;align-items:center;gap:4px;" class="tp">
        <span class="material-symbols-rounded" style="font-size:16px;">open_in_new</span> Report Centre
      </a>
    </div>
  </header>

  <div id="globalAlert"></div>

  <main style="max-width:960px;margin:0 auto;padding:24px 16px 60px;">

    <!-- Info -->
    <div style="background:rgba(251,191,36,0.05);border:1px solid rgba(251,191,36,0.15);border-radius:12px;padding:12px 18px;margin-bottom:22px;display:flex;align-items:flex-start;gap:10px;">
      <span class="material-symbols-rounded" style="color:#fbbf24;font-size:18px;margin-top:1px;">info</span>
      <p style="font-size:13px;color:#94a3b8;line-height:1.6;margin:0;">
        Add calendar entries with <strong style="color:#e2e8f0;">Month, Date, Activity and Type</strong> for each semester.
        The <strong style="color:#e2e8f0;">Print A4</strong> output will show all dates of each month in a table — day names, activities, Sundays highlighted,
        and total working days counted per month. Upload the SITTTR PDF as a reference (not printed).
      </p>
    </div>

    @php
      $semNames  = ['I','II','III','IV','V','VI'];
      $allMonths = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    @endphp

    <div style="display:flex;flex-direction:column;gap:10px;">
      @for($sem = 1; $sem <= 6; $sem++)
        @php
          $cal     = $calendars->get($sem);
          $ci      = $sem;
          $entries = $cal ? json_decode($cal->activities ?? '[]', true) : [];
        @endphp

        <div class="sem-panel" id="panel_{{ $sem }}">

          <!-- Header -->
          <div class="sem-header" onclick="togglePanel({{ $sem }})">
            <div style="display:flex;align-items:center;gap:14px;">
              <span style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:10px;border:1.5px solid;font-weight:900;font-size:16px;" class="b{{ $ci }} a{{ $ci }}">
                {{ $semNames[$sem-1] }}
              </span>
              <div>
                <div style="font-weight:800;font-size:15px;color:#f1f5f9;">Semester {{ $semNames[$sem-1] }}</div>
                <div style="font-size:12px;color:#475569;margin-top:2px;">
                  @if($cal)
                    {{ $cal->academic_year }} &bull; {{ count($entries) }} {{ count($entries)==1?'entry':'entries' }}
                  @else
                    Not configured
                  @endif
                </div>
              </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
              @if($cal)
                <span class="badge-green">&#10003; Saved</span>
                <a href="/hod/academic-calendar/{{ $cal->id }}/print" target="_blank" onclick="event.stopPropagation()"
                  style="display:inline-flex;align-items:center;gap:5px;background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.3);color:#60a5fa;border-radius:8px;padding:5px 13px;font-size:13px;font-weight:700;text-decoration:none;" class="tp">
                  <span class="material-symbols-rounded" style="font-size:15px;">print</span> Print A4
                </a>
              @else
                <span class="badge-slate">Not set</span>
              @endif
              <span class="material-symbols-rounded chevron a{{ $ci }}" id="chevron_{{ $sem }}" style="font-size:20px;">expand_more</span>
            </div>
          </div>

          <!-- Body -->
          <div class="sem-body" id="body_{{ $sem }}">

            <!-- Year + PDF -->
            <div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
              <div style="flex:0 0 180px;">
                <label style="display:block;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.07em;color:#475569;margin-bottom:6px;">Academic Year</label>
                <input id="year_{{ $sem }}" type="text" value="{{ $cal->academic_year ?? date('Y').'-'.(date('Y')+1) }}" class="finput" placeholder="e.g. 2024-2025">
              </div>
              <div style="flex:1;min-width:240px;">
                <label style="display:block;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.07em;color:#475569;margin-bottom:6px;">
                  SITTTR Reference PDF (not printed)
                  @if($cal && $cal->pdf_path)
                    &nbsp;<a href="/storage/{{ $cal->pdf_path }}" target="_blank" style="color:#38bdf8;font-size:11px;text-decoration:none;font-weight:700;">&#128196; View PDF</a>
                  @endif
                </label>
                <div class="upload-zone {{ ($cal && $cal->pdf_path) ? 'has-file' : '' }}" id="uz_{{ $sem }}" onclick="document.getElementById('pdf_{{ $sem }}').click()">
                  @if($cal && $cal->pdf_path)
                    <div style="font-size:13px;color:#4ade80;font-weight:700;">&#10003; PDF uploaded — click to replace</div>
                  @else
                    <div style="font-size:13px;color:#475569;font-weight:700;">&#8593; Click to upload SITTTR PDF</div>
                    <div style="font-size:11px;color:#334155;">Reference only — not included in printout</div>
                  @endif
                </div>
                <input type="file" id="pdf_{{ $sem }}" accept=".pdf" style="display:none;" onchange="onFile({{ $sem }})">
              </div>
            </div>

            <!-- Entries Table -->
            <div class="slabel i{{ $ci }}" style="padding-left:10px;">Calendar Entries (Month-wise)</div>
            <div style="overflow-x:auto;border:1px solid #1e293b;border-radius:12px;">
              <table class="cal-table" id="ct_{{ $sem }}">
                <thead>
                  <tr>
                    <th style="width:130px;">Month</th>
                    <th style="width:70px;">Date</th>
                    <th>Activity / Description</th>
                    <th style="width:140px;">Type</th>
                    <th style="width:38px;"></th>
                  </tr>
                </thead>
                <tbody id="cb_{{ $sem }}">
                  @foreach($entries as $e)
                  <tr class="erow">
                    <td>
                      <select class="finput e-month">
                        @foreach($allMonths as $mn)
                          <option value="{{ $mn }}" {{ ($e['month']??'')===$mn?'selected':'' }}>{{ $mn }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      <input type="number" min="1" max="31" value="{{ $e['date'] ?? '' }}" placeholder="1-31" class="finput e-date">
                    </td>
                    <td>
                      <input type="text" value="{{ $e['activity'] ?? '' }}" placeholder="Activity description..." class="finput e-activity">
                    </td>
                    <td>
                      <select class="finput e-type">
                        @foreach(['Academic','Exam','Holiday','Event','Department','Other'] as $t)
                          <option value="{{ $t }}" {{ ($e['type']??'Academic')===$t?'selected':'' }}>{{ $t }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td style="text-align:center;">
                      <button type="button" onclick="this.closest('tr').remove()" style="background:none;border:none;color:#334155;cursor:pointer;padding:4px;" class="tp">
                        <span class="material-symbols-rounded" style="font-size:16px;">close</span>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <!-- Actions -->
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:14px;flex-wrap:wrap;gap:10px;">
              <button type="button" onclick="addRow({{ $sem }})"
                style="display:flex;align-items:center;gap:6px;background:rgba(51,65,85,0.5);border:1px solid #334155;color:#94a3b8;border-radius:9px;padding:7px 14px;font-size:13px;font-weight:700;cursor:pointer;" class="tp">
                <span class="material-symbols-rounded" style="font-size:16px;">add</span> Add Row
              </button>
              <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <button type="button" id="fetchBtn_{{ $sem }}" onclick="fetchFromPdf({{ $sem }})"
                  style="display:flex;align-items:center;gap:5px;background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.35);color:#c084fc;border-radius:9px;padding:7px 15px;font-size:13px;font-weight:700;cursor:pointer;" class="tp">
                  <span class="material-symbols-rounded" style="font-size:15px;">auto_fix_high</span>
                  <span id="fetchLabel_{{ $sem }}">Auto-Fetch from PDF</span>
                </button>
                <button type="button" onclick="prefill({{ $sem }})"
                  style="display:flex;align-items:center;gap:5px;background:rgba(251,191,36,0.07);border:1px solid rgba(251,191,36,0.2);color:#fbbf24;border-radius:9px;padding:7px 14px;font-size:13px;font-weight:700;cursor:pointer;" class="tp">
                  <span class="material-symbols-rounded" style="font-size:15px;">edit_note</span> Manual Template
                </button>
                <button type="button" onclick="save({{ $sem }})"
                  style="display:flex;align-items:center;gap:6px;background:#1d4ed8;border:1px solid #2563eb;color:#fff;border-radius:9px;padding:7px 18px;font-size:13px;font-weight:700;cursor:pointer;" class="tp">
                  <span class="material-symbols-rounded" style="font-size:16px;">save</span> Save Calendar
                </button>
              </div>
            </div>


          </div><!-- /sem-body -->
        </div><!-- /panel -->
      @endfor
    </div>

  </main>

  <script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const ALL_MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const TYPES      = ['Academic','Exam','Holiday','Event','Department','Other'];

    function togglePanel(sem) {
      const b = document.getElementById('body_' + sem);
      const c = document.getElementById('chevron_' + sem);
      const open = b.classList.contains('open');
      document.querySelectorAll('.sem-body').forEach(x => x.classList.remove('open'));
      document.querySelectorAll('.chevron').forEach(x => x.classList.remove('open'));
      if (!open) { b.classList.add('open'); c.classList.add('open'); }
    }

    function onFile(sem) {
      const f = document.getElementById('pdf_' + sem).files[0];
      if (!f) return;
      const z = document.getElementById('uz_' + sem);
      z.classList.add('has-file');
      z.innerHTML = `<div style="font-size:13px;color:#4ade80;font-weight:700;">&#10003; ${f.name}</div><div style="font-size:11px;color:#166534;">Click to change</div>`;
      z.onclick = () => document.getElementById('pdf_' + sem).click();
    }

    function makeRow(sem, month='', date='', activity='', type='Academic') {
      const tr = document.createElement('tr');
      tr.className = 'erow';
      const mOpts = ALL_MONTHS.map(m => `<option value="${m}" ${m===month?'selected':''}>${m}</option>`).join('');
      const tOpts = TYPES.map(t => `<option value="${t}" ${t===type?'selected':''}>${t}</option>`).join('');
      const s = 'width:100%;background:#0f172a;border:1px solid #334155;border-radius:8px;padding:7px 10px;font-size:13px;color:#f1f5f9;outline:none;';
      tr.innerHTML = `
        <td><select class="e-month" style="${s}">${mOpts}</select></td>
        <td><input type="number" min="1" max="31" value="${date}" placeholder="1-31" class="e-date" style="${s}"></td>
        <td><input type="text" value="${activity}" placeholder="Activity description..." class="e-activity" style="${s}"></td>
        <td><select class="e-type" style="${s}">${tOpts}</select></td>
        <td style="text-align:center;">
          <button type="button" onclick="this.closest('tr').remove()" style="background:none;border:none;color:#334155;cursor:pointer;padding:4px;">
            <span class="material-symbols-rounded" style="font-size:16px;">close</span>
          </button>
        </td>`;
      return tr;
    }

    function addRow(sem) {
      const tbody = document.getElementById('cb_' + sem);
      tbody.appendChild(makeRow(sem));
    }

    // Typical template data per semester (odd=Jun-Nov, even=Nov-May)
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
      if (tbody.children.length > 0 && !confirm('Add template rows? Existing rows will remain.')) return;
      (templates[sem] || []).forEach(([m, d, a, t]) => tbody.appendChild(makeRow(sem, m, d, a, t)));
    }

    function save(sem) {
      const year  = document.getElementById('year_' + sem).value.trim();
      const pdf   = document.getElementById('pdf_' + sem);
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
      if (pdf.files[0]) fd.append('pdf', pdf.files[0]);
      fetch('/api/academic-calendar/save', { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF }, body: fd })
        .then(r => r.json())
        .then(d => {
          if (d.status === 'SUCCESS') { alert_ok('Semester ' + sem + ' saved!'); setTimeout(() => location.reload(), 1300); }
          else alert_err(d.message || 'Save failed.');
        })
        .catch(() => alert_err('Network error.'));
    }

    function alert_ok(m) { const el = document.getElementById('globalAlert'); el.className='ok'; el.innerText=m; setTimeout(()=>el.className='',5000); }
    function alert_err(m) { const el = document.getElementById('globalAlert'); el.className='err'; el.innerText=m; setTimeout(()=>el.className='',6000); }

    /* ── Auto-Fetch from PDF via Gemini AI ─────────────────────────────── */
    function fetchFromPdf(sem) {
      const btn   = document.getElementById('fetchBtn_' + sem);
      const label = document.getElementById('fetchLabel_' + sem);
      if (!btn) return;

      // Set loading state
      btn.disabled = true;
      label.innerHTML = '⏳ Reading PDF...';
      btn.style.opacity = '0.7';

      fetch('/api/academic-calendar/parse-pdf', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
        body: JSON.stringify({ semester: sem })
      })
      .then(r => r.json())
      .then(data => {
        btn.disabled = false;
        btn.style.opacity = '1';
        label.textContent = 'Auto-Fetch from PDF';

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

        // Success message
        const skipped = entries.length - added;
        let msg = `✅ AI extracted ${entries.length} entries from PDF — ${added} added`;
        if (skipped > 0) msg += `, ${skipped} skipped (already in table)`;
        alert_ok(msg + '. Review, add custom remarks, then click Save.');
      })
      .catch(err => {
        btn.disabled = false;
        btn.style.opacity = '1';
        label.textContent = 'Auto-Fetch from PDF';
        alert_err('Network error: ' + err.message);
      });
    }
  </script>
</body>
</html>
