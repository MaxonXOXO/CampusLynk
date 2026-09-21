                  tr.innerHTML = `
                    <td class="p-4 flex items-center gap-3">
                      <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-800 shadow">
                      <div>
                        <span class="font-bold text-slate-100 block">${user.name}</span>
                        <span class="text-[10px] text-slate-500 block">${user.email}</span>
                      </div>
                    </td>
                    <td class="p-4 font-mono text-slate-300 font-bold">${user.id}</td>
                    <td class="p-4"><span class="font-bold font-mono text-xs bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
                    <td class="p-4 text-slate-300 font-medium">${user.role}</td>
                    <td class="p-4 text-right"><span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">${user.status}</span></td>
                  `;
                  tbody.appendChild(tr);
                });
              }
            }

            // Mobile render
            if (mobileContainer) {
              mobileContainer.innerHTML = '';
              if (filteredUsers.length === 0) {
                mobileContainer.innerHTML = '<small class="text-secondary d-block py-2">No Self-Financing staff members found.</small>';
              } else {
                let html = '';
                filteredUsers.forEach(user => {
                  html += `
                    <div class="p-2.5 rounded-3 border border-secondary border-opacity-20 bg-slate-900 mb-2 d-flex align-items-center justify-content-between">
                      <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                        <div class="overflow-hidden">
                          <strong class="text-white d-block text-truncate small">${user.name}</strong>
                          <small class="text-secondary d-block" style="font-size:0.7rem;">${user.id} &bull; ${user.role}</small>
                        </div>
                      </div>
                      <span class="badge bg-secondary bg-opacity-20 text-light font-mono small">${user.branch}</span>
                    </div>
                  `;
                });
                mobileContainer.innerHTML = html;
              }
            }

          }
        })
        .catch(() => { if (indicator) indicator.classList.add('hidden'); });
    }

    function loadSelfSecurityLogs() {
      const tbody = document.getElementById('selfSecurityLogsTable');
      const mobileContainer = document.getElementById('mobileSecurityLogsContainer');

      fetch(`/api/audit-logs?targetId={{ session('userId') }}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === 'SUCCESS') {
            if (tbody) {
              tbody.innerHTML = "";
              if (data.logs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3" class="p-6 text-center text-slate-500 font-bold">No profile security logs recorded.</td></tr>`;
              } else {
                data.logs.forEach(log => {
                  const tr = document.createElement('tr');
                  tr.className = "border-b border-slate-800/40 text-xs hover:bg-slate-900/20";
                  const date = new Date(log.created_at).toLocaleString();
                  tr.innerHTML = `
                    <td class="p-4 text-slate-400 font-mono">${date}</td>
                    <td class="p-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">${log.action}</span></td>
                    <td class="p-4 text-slate-300">${log.details || ''}</td>
                  `;
                  tbody.appendChild(tr);
                });
              }
            }

            if (mobileContainer) {
              mobileContainer.innerHTML = "";
              if (data.logs.length === 0) {
                mobileContainer.innerHTML = `<small class="text-secondary d-block py-2">No security audit logs recorded.</small>`;
              } else {
                let html = '';
                data.logs.forEach(log => {
                  const date = new Date(log.created_at).toLocaleDateString();
                  html += `
                    <div class="p-2 rounded-3 border border-secondary border-opacity-20 bg-slate-900 mb-2">
                      <div class="d-flex justify-content-between text-secondary mb-1" style="font-size:0.7rem;">
                        <span class="font-mono">${date}</span>
                        <span class="badge bg-info text-dark" style="font-size:0.65rem;">${log.action}</span>
                      </div>
                      <small class="text-slate-300 d-block" style="font-size:0.75rem;">${log.details || ''}</small>
                    </div>
                  `;
                });
                mobileContainer.innerHTML = html;
              }
            }

          }
        });
    }
  </script>
</body>
</html>
