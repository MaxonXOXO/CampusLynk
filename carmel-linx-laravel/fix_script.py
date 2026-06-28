import re

with open('resources/views/student_mentoring_scripts.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Remove the incorrectly injected addExtraRow inside addFamilyRow
bad_pattern = r"function addFamilyRow\(name='', rel='', edu='', occ='', con=''\) \{\s*function addExtraRow\(ex\) \{[\s\S]*?document\.getElementById\('smdExtraList'\)\.appendChild\(tr\);\s*\}\s*const tr = document\.createElement\('tr'\);"
content = re.sub(bad_pattern, "function addFamilyRow(name='', rel='', edu='', occ='', con='', id='') {\n  const tr = document.createElement('tr');", content)

# Now fix the old addExtraRow
old_add_extra = r"function addExtraRow\(sem='', act='', ach='', status='Pending', id=''\) \{[\s\S]*?document\.getElementById\('smdExtraList'\)\.appendChild\(tr\);\s*\}"

new_add_extra = """function addExtraRow(ex) {
    if (!ex || typeof ex !== 'object') return;
    const tr = document.createElement('tr');
    tr.className = 'border-b border-slate-800/40 extra-row';
    const statusColor = ex.status === 'Verified' ? 'text-green-400' : (ex.status === 'Rejected' ? 'text-red-400' : 'text-amber-400');
    
    // HTML Escaping helper to safely output JSON
    const safeEx = JSON.stringify(ex).replace(/'/g, "&apos;").replace(/\"/g, "&quot;");
    
    tr.innerHTML = 
      <td class="p-3 text-white text-center"></td>
      <td class="p-3 text-white truncate max-w-[200px]" title=""></td>
      <td class="p-3 text-white"></td>
      <td class="p-3 font-bold text-blue-400"></td>
      <td class="p-3 font-bold "></td>
      <td class="p-3 text-right">
        <button onclick='editStudentActivity()' class="text-indigo-400 hover:text-indigo-300 text-xs cursor-pointer mr-2"><span class="material-symbols-rounded text-sm">edit</span></button>
      </td>
    ;
    document.getElementById('smdExtraList').appendChild(tr);
  }"""

content = re.sub(old_add_extra, new_add_extra, content)

with open('resources/views/student_mentoring_scripts.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Done!')
