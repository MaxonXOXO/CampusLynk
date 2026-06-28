import os

filepath = 'resources/views/lecturer_dashboard.blade.php'
with open(filepath, 'r') as f:
    content = f.read()

# 1. Fix co_targets
content = content.replace('data.data.co_targets', 'data.data.cos')

# 2. Add loadActiveOnlineTests inside loadCourseDetails
find_load = """renderSummativeAssessment(data.data.cos, data.data.students || []);"""
replace_load = """renderSummativeAssessment(data.data.cos, data.data.students || []);
          loadActiveOnlineTests(subjectId);"""
content = content.replace(find_load, replace_load)

# 3. Add q_count to form
find_q_count = """                    <label class="block text-[9px] text-slate-500 font-bold mt-2 mb-1 uppercase">Duration (Minutes)</label>
                    <input type="number" id="online_test_duration" value="30" min="5" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                  </div>
                </div>"""
replace_q_count = """                    <label class="block text-[9px] text-slate-500 font-bold mt-2 mb-1 uppercase">Duration (Minutes)</label>
                    <input type="number" id="online_test_duration" value="30" min="5" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                  </div>
                </div>
                
                <div class="mb-4">
                  <label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">Number of Questions</label>
                  <input type="number" id="online_test_q_count" value="10" min="1" max="50" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500">
                </div>"""
content = content.replace(find_q_count, replace_q_count)

# 4. Add Custom Name
find_custom_name = """                  <div>
                    <label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">End Time (Deadline)</label>
                    <input type="text" id="online_test_end" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="Select Date & Time">
                  </div>
                </div>"""
replace_custom_name = """                  <div>
                    <label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">End Time (Deadline)</label>
                    <input type="text" id="online_test_end" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="Select Date & Time">
                  </div>
                </div>
                
                <div class="mb-4">
                  <label class="block text-[9px] text-slate-500 font-bold mb-1 uppercase">Custom Test ID/Name (Optional)</label>
                  <input type="text" id="online_test_name" class="w-full bg-slate-950 border border-slate-700/50 rounded px-2 py-1.5 text-[10px] text-slate-200 outline-none focus:border-purple-500" placeholder="e.g. Midterm Test 1">
                </div>"""
content = content.replace(find_custom_name, replace_custom_name)

# 5. Fix publishOnlineTest JS
find_btn_html = """<button onclick="publishOnlineTest('\')" class="w-full py-2 bg-purple-600/80 hover:bg-purple-500 text-white rounded-lg text-xs font-bold transition-premium flex items-center justify-center gap-2">"""
replace_btn_html = """<button id="btnPublishOnlineTest" onclick="publishOnlineTest('\')" class="w-full py-2 bg-purple-600/80 hover:bg-purple-500 text-white rounded-lg text-xs font-bold transition-premium flex items-center justify-center gap-2">"""
content = content.replace(find_btn_html, replace_btn_html)

find_js_start = """    function publishOnlineTest(subjectId) {
        const selectElement = document.getElementById('online_test_cos');
        const selectedCos = Array.from(selectElement.selectedOptions).map(opt => opt.value);
        const attempts = document.getElementById('online_test_attempts').value;
        const duration = document.getElementById('online_test_duration').value;
        const start = document.getElementById('online_test_start').value;
        const end = document.getElementById('online_test_end').value;"""
replace_js_start = """    function publishOnlineTest(subjectId) {
        const customName = document.getElementById('online_test_name') ? document.getElementById('online_test_name').value : '';
        const btn = document.getElementById('btnPublishOnlineTest');
        const selectElement = document.getElementById('online_test_cos');
        const selectedCos = Array.from(selectElement.selectedOptions).map(opt => opt.value);
        const attempts = document.getElementById('online_test_attempts').value;
        const duration = document.getElementById('online_test_duration').value;
        const q_count = document.getElementById('online_test_q_count') ? document.getElementById('online_test_q_count').value : 10;
        const start = document.getElementById('online_test_start').value;
        const end = document.getElementById('online_test_end').value;"""
content = content.replace(find_js_start, replace_js_start)

find_fetch_call = """        fetch(/api/classroom/\/publish-online-test, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ cos: selectedCos, attempts, duration, start, end })
        })"""
replace_fetch_call = """        if (btn) { btn.disabled = true; btn.innerHTML = 'Generating...'; btn.classList.add('opacity-50', 'cursor-not-allowed'); }
        fetch(/api/classroom/\/publish-online-test, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ cos: selectedCos, attempts, duration, q_count, start, end, custom_name: customName })
        })"""
content = content.replace(find_fetch_call, replace_fetch_call)

find_success_alert = """          if (data.status === 'SUCCESS') {
            alert("Online Test successfully published!");
            selectElement.selectedIndex = -1;
            if (document.getElementById('online_test_start')._flatpickr) document.getElementById('online_test_start')._flatpickr.clear();
            if (document.getElementById('online_test_end')._flatpickr) document.getElementById('online_test_end')._flatpickr.clear();
          }"""
replace_success_alert = """          if (btn) { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-rounded text-sm">rocket_launch</span> Generate & Publish to Students'; btn.classList.remove('opacity-50', 'cursor-not-allowed'); }
          if (data.status === 'SUCCESS') {
            alert("Online Test successfully published!");
            loadActiveOnlineTests(subjectId);
            selectElement.selectedIndex = -1;
            if (document.getElementById('online_test_name')) document.getElementById('online_test_name').value = '';
            if (document.getElementById('online_test_start')._flatpickr) document.getElementById('online_test_start')._flatpickr.clear();
            if (document.getElementById('online_test_end')._flatpickr) document.getElementById('online_test_end')._flatpickr.clear();
          }"""
content = content.replace(find_success_alert, replace_success_alert)

# 6. Update Active Tests UI (buttons)
find_active_test_buttons = """                  <button onclick="generateOnlineTestReport('\')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 transition-premium">
                    <span class="material-symbols-rounded text-[11px]">download</span> Download Report PDF
                  </button>
                  <button onclick="viewOnlineTestKey('\')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 transition-premium">
                    <span class="material-symbols-rounded text-[11px]">key</span> Key
                  </button>
                  <button onclick="deleteOnlineTest('\', '\')" class="w-full py-1 bg-red-900/50 hover:bg-red-800/80 text-red-300 rounded border border-red-800/50 flex items-center justify-center gap-1 transition-premium">
                    <span class="material-symbols-rounded text-[11px]">delete</span> Delete
                  </button>"""
replace_active_test_buttons = """                  <div class="grid grid-cols-2 gap-2 mt-2">
                    <button onclick="generateOnlineTestReport('\')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Download Results">
                      <span class="material-symbols-rounded text-[11px]">download</span> Report
                    </button>
                    <button onclick="printOnlineTest('\')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Print Question Paper">
                      <span class="material-symbols-rounded text-[11px]">print</span> Print Qp
                    </button>
                    <button onclick="deleteOnlineTest('\', '\')" class="col-span-2 w-full py-1 bg-red-900/50 hover:bg-red-800/80 text-red-300 rounded border border-red-800/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Delete Test">
                      <span class="material-symbols-rounded text-[11px]">delete</span> Delete
                    </button>
                  </div>"""
content = content.replace(find_active_test_buttons, replace_active_test_buttons)

with open(filepath, 'w') as f:
    f.write(content)
print("Done")
