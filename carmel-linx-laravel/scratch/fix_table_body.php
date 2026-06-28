<?php

$filePath = "c:\\Users\\fotonlabz\\Desktop\\Test Portal\\carmel-linx-laravel\\resources\\views\\student_dashboard.blade.php";
$content = file_get_contents($filePath);

// Fix the table ID and column count
$oldJS = "            // Claims Table
            const tbody = document.getElementById('studentActivityTableBody');
            if (data.claims && data.claims.length > 0) {
              let html = '';
              data.claims.forEach(c => {
                let statusClass = 'text-amber-400 bg-amber-500/10 border-amber-500/20';
                if (c.status === 'Verified') statusClass = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
                if (c.status === 'Rejected') statusClass = 'text-rose-400 bg-rose-500/10 border-rose-500/20';
                
                html += `
                  <tr class=\"hover:bg-slate-900/50 transition-colors\">
                    <td class=\"p-3 text-[11px] font-bold text-slate-300\">\${c.activity_segment}</td>
                    <td class=\"p-3\">
                      <span class=\"block text-[11px] text-slate-300\">\${c.activity_name}</span>
                      <span class=\"block text-[9px] text-slate-500\">\${c.level}</span>
                    </td>
                    <td class=\"p-3\">
                      \${c.document_reference ? `<a href=\"\${c.document_reference}\" target=\"_blank\" class=\"text-blue-400 hover:text-blue-300 text-[10px] underline flex items-center gap-1\"><span class=\"material-symbols-rounded text-[12px]\">link</span> View</a>` : '<span class=\"text-[10px] text-slate-600\">None</span>'}
                    </td>
                    <td class=\"p-3 text-center text-xs font-bold text-slate-300\">\${c.points_claimed}</td>
                    <td class=\"p-3 text-center text-xs font-bold \${c.status === 'Verified' ? 'text-emerald-400' : 'text-slate-500'}\">\${c.status === 'Verified' ? c.points_awarded : '--'}</td>
                    <td class=\"p-3 text-center\">
                      <span class=\"px-2 py-0.5 rounded border text-[9px] font-bold uppercase tracking-wider \${statusClass}\">\${c.status}</span>
                    </td>
                  </tr>
                `;
              });
              tbody.innerHTML = html;
            } else {
              tbody.innerHTML = `<tr><td colspan=\"6\" class=\"p-6 text-center text-slate-500 text-xs\">No activity claims submitted yet.</td></tr>`;
            }";


$newJS = "            // Claims Table
            const tbody = document.getElementById('activityClaimsTableBody');
            if (data.claims && data.claims.length > 0) {
              let html = '';
              data.claims.forEach(c => {
                let statusClass = 'text-amber-400 bg-amber-500/10 border-amber-500/20';
                if (c.status === 'Verified') statusClass = 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
                if (c.status === 'Rejected') statusClass = 'text-rose-400 bg-rose-500/10 border-rose-500/20';
                
                let dateStr = c.created_at ? new Date(c.created_at).toLocaleDateString() : 'N/A';
                
                html += `
                  <tr class=\"hover:bg-slate-900/50 transition-colors border-b border-slate-800/40\">
                    <td class=\"p-3 text-[10px] text-slate-400\">\${dateStr}</td>
                    <td class=\"p-3 text-[11px] font-bold text-slate-300\">\${c.activity_segment}</td>
                    <td class=\"p-3 text-[11px] text-slate-300\">\${c.activity_name}</td>
                    <td class=\"p-3 text-[10px] text-slate-400\">\${c.level}</td>
                    <td class=\"p-3\">
                      \${c.document_reference ? `<a href=\"\${c.document_reference}\" target=\"_blank\" class=\"text-blue-400 hover:text-blue-300 text-[10px] underline flex items-center gap-1\"><span class=\"material-symbols-rounded text-[12px]\">link</span> View</a>` : '<span class=\"text-[10px] text-slate-600\">None</span>'}
                    </td>
                    <td class=\"p-3 text-center text-xs font-bold text-slate-300\">\${c.points_claimed}</td>
                    <td class=\"p-3 text-center text-xs font-bold \${c.status === 'Verified' ? 'text-emerald-400' : 'text-slate-500'}\">\${c.status === 'Verified' ? c.points_awarded : '--'}</td>
                    <td class=\"p-3 text-right\">
                      <span class=\"px-2 py-0.5 rounded border text-[9px] font-bold uppercase tracking-wider \${statusClass}\">\${c.status}</span>
                    </td>
                  </tr>
                `;
              });
              tbody.innerHTML = html;
            } else {
              tbody.innerHTML = `<tr><td colspan=\"8\" class=\"p-6 text-center text-slate-500 text-xs\">No activity claims submitted yet.</td></tr>`;
            }";

$content = str_replace($oldJS, $newJS, $content);
file_put_contents($filePath, $content);
echo "Fixed JS table body ID and column count.\n";
