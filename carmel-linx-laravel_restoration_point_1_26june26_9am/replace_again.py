import os

filepath = 'resources/views/lecturer_dashboard.blade.php'
with open(filepath, 'r') as f:
    content = f.read()

find_str = """                  <div class="grid grid-cols-2 gap-2 mt-2">
                      <button onclick="generateOnlineTestReport('')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Download Results">
                        <span class="material-symbols-rounded text-[11px]">download</span> Report
                      </button>
                      <button onclick="printOnlineTest('')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Print Question Paper with Answers">
                        <span class="material-symbols-rounded text-[11px]">print</span> Print Q&A
                      </button>
                      <button onclick="deleteOnlineTest('', '')" class="col-span-2 w-full py-1 bg-red-900/50 hover:bg-red-800/80 text-red-300 rounded border border-red-800/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Delete Test">
                        <span class="material-symbols-rounded text-[11px]">delete</span> Delete
                      </button>
                    </div>"""

replace_str = """                  <div class="grid grid-cols-2 gap-2 mt-2">
                      <button onclick="generateOnlineTestReport('\')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Download Results">
                        <span class="material-symbols-rounded text-[11px]">download</span> Report
                      </button>
                      <button onclick="printOnlineTest('\')" class="w-full py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Print Question Paper with Answers">
                        <span class="material-symbols-rounded text-[11px]">print</span> Print Q&A
                      </button>
                      <button onclick="deleteOnlineTest('\', '\')" class="col-span-2 w-full py-1 bg-red-900/50 hover:bg-red-800/80 text-red-300 rounded border border-red-800/50 flex items-center justify-center gap-1 text-[9px] transition-premium" title="Delete Test">
                        <span class="material-symbols-rounded text-[11px]">delete</span> Delete
                      </button>
                    </div>"""

if find_str in content:
    content = content.replace(find_str, replace_str)
    with open(filepath, 'w') as f:
        f.write(content)
    print("Replaced successfully!")
else:
    print("String not found!")
