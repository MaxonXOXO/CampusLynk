import os

filepath = 'resources/views/lecturer_dashboard.blade.php'
with open(filepath, 'r') as f:
    content = f.read()

# 1. Fix the buttons in loadActiveOnlineTests
find_buttons = """<button onclick="generateOnlineTestReport('\')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 transition-premium">
                      <span class="material-symbols-rounded text-[11px]">download</span> Download Report PDF
                    </button>"""
replace_buttons = """<div class="grid grid-cols-2 gap-2 mt-2">
                      <button onclick="generateOnlineTestReport('\')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Download Results">
                        <span class="material-symbols-rounded text-[11px]">download</span> Report
                      </button>
                      <button onclick="printOnlineTest('\')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Print Question Paper with Answers">
                        <span class="material-symbols-rounded text-[11px]">print</span> Print Q & A
                      </button>
                      <button onclick="deleteOnlineTest('\', '\')" class="col-span-2 w-full py-1 bg-red-900/50 hover:bg-red-800/80 text-red-300 rounded border border-red-800/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Delete Test">
                        <span class="material-symbols-rounded text-[11px]">delete</span> Delete
                      </button>
                    </div>"""
content = content.replace(find_buttons, replace_buttons)

# 2. Append the missing Javascript functions right before </script>
missing_functions = """
      function deleteOnlineTest(testId, subjectId) {
        if (!confirm("Are you sure you want to delete this online test? This will permanently remove all student attempts and records associated with it.")) return;
        fetch(/api/classroom/online-tests/\, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            loadActiveOnlineTests(subjectId);
          } else {
            alert(data.message || "Failed to delete test.");
          }
        });
      }

      function printOnlineTest(testId) {
        fetch(/api/classroom/online-tests/\/key)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            let html = <html><head><title>Test Answer Key</title><style>body{font-family:sans-serif;padding:20px;font-size:14px;}</style></head><body>;
            html += <h2>Online Test Answer Key</h2>;
            data.data.forEach((q, i) => {
              html += <div><p><strong>Q\. \</strong> <em>[\]</em></p>;
              html += <ul>;
              q.options.forEach(opt => {
                let isCorrect = (opt === q.correct_ans);
                html += <li style="\">\</li>;
              });
              html += </ul></div><hr>;
            });
            html += </body></html>;
            let pw = window.open('', '_blank', 'width=800,height=600');
            pw.document.write(html);
            pw.document.close();
            pw.focus();
            setTimeout(() => { pw.print(); }, 500);
          } else {
            alert(data.message);
          }
        });
      }
"""

# Insert right before the last closing script tag
last_script_index = content.rfind('</script>')
if last_script_index != -1:
    content = content[:last_script_index] + missing_functions + content[last_script_index:]

with open(filepath, 'w') as f:
    f.write(content)
print("Done")
