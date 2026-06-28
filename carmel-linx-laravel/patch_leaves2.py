import re

with open('resources/views/student_mentoring_scripts.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

bad_leaves = r"// Populate Leaves[\s\S]*?No leave records\.</td\></tr\>';\s*\}\s*\}"
content = re.sub(bad_leaves, "", content)

leaves_population = r"""    // Populate Leaves
    const lList = document.getElementById('smdLeavesTable');
    if (lList) {
      lList.innerHTML = '';
      if (data.leaves && data.leaves.length > 0) {
        data.leaves.forEach(lv => {
          const tr = document.createElement('tr');
          tr.className = 'border-b border-slate-800/40';
          const statColor = lv.status === 'Approved' ? 'text-green-400' : (lv.status === 'Rejected' ? 'text-red-400' : 'text-amber-400');
          const safeLv = JSON.stringify(lv).replace(/'/g, "&apos;").replace(/\"/g, "&quot;");
          const parentInformedHtml = lv.parent_informed ? '<span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded text-[10px]">Informed</span>' : '<span class="px-2 py-0.5 bg-slate-700 text-slate-400 rounded text-[10px]">No</span>';
          
          tr.innerHTML = 
            <td class="p-3"></td>
            <td class="p-3"></td>
            <td class="p-3 max-w-[200px] truncate" title=""></td>
            <td class="p-3"></td>
            <td class="p-3 font-bold "></td>
            <td class="p-3 text-right">
              <button onclick='editLeave()' class="text-indigo-400 hover:text-indigo-300 text-xs cursor-pointer mr-2"><span class="material-symbols-rounded text-sm">edit</span></button>
            </td>
          ;
          lList.appendChild(tr);
        });
      } else {
        lList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-500">No leave records.</td></tr>';
      }
    }
"""

content = content.replace("// Populate Meetings", leaves_population + "\n    // Populate Meetings")

with open('resources/views/student_mentoring_scripts.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Done!')
