
  <script>
    let activePanel = 'dashboard';

    document.addEventListener("DOMContentLoaded", () => {
      if (activePanel === 'directory') loadUsers();
    });

    function switchPanel(panelId) {
      activePanel = panelId;
      const panels = ['dashboard', 'directory'];
      panels.forEach(id => {
        const el = document.getElementById('panel' + id.charAt(0).toUpperCase() + id.slice(1));
        const nav = document.getElementById('nav' + id.charAt(0).toUpperCase() + id.slice(1));
        
        if (id === panelId) {
          if (el) el.classList.remove('hidden');
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-r-xl rounded-l-none font-bold text-xs flex items-center gap-2.5 transition-premium bg-blue-500/10 text-blue-400 border-l-2 border-blue-500";
        } else {
          if (nav) nav.className = "w-full text-left px-3.5 py-1.5 rounded-xl font-bold text-xs flex items-center gap-2.5 transition-premium text-slate-400 hover:bg-slate-800 hover:text-white cursor-pointer";
          if (el) el.classList.add('hidden');
        }
      });

      document.getElementById('panelTitle').innerText = panelId === 'dashboard' ? 'Overview' : 'User Directory';
      if (panelId === 'directory') loadUsers();
    }

    function loadUsers() {
      const indicator = document.getElementById('loadingIndicator');
      indicator.classList.remove('hidden');
      const search = document.getElementById('filterSearch').value;
      const role = document.getElementById('filterRole').value;

      fetch(`/api/admin/users?search=${encodeURIComponent(search)}&branch=GEN_SF&role=${role}`)
        .then(res => res.json())
        .then(data => {
          indicator.classList.add('hidden');
          if (data.status === 'SUCCESS') {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';
            if (data.users.length === 0) {
              tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-slate-500">No staff found.</td></tr>';
              return;
            }
            data.users.forEach(user => {
              const tr = document.createElement('tr');
              tr.className = 'border-b border-slate-800/40 hover:bg-slate-900/30';
              tr.innerHTML = `
                <td class="p-4 flex items-center gap-3">
                  <img src="${user.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=80'}" class="w-8 h-8 rounded-full object-cover border border-slate-800 shadow">
                  <div>
                    <span class="font-bold text-slate-100 block">${user.name}</span>
                    <span class="text-[10px] text-slate-500 block">${user.email}</span>
                  </div>
                </td>
                <td class="p-4 font-mono text-slate-300">${user.id}</td>
                <td class="p-4"><span class="font-bold font-mono text-[10px] bg-slate-800 text-slate-300 px-2 py-0.5 rounded border border-slate-700">${user.branch}</span></td>
                <td class="p-4 text-slate-300">${user.role}</td>
                <td class="p-4 text-right"><span class="px-2 py-0.5 rounded-full text-[10px] bg-green-500/10 text-green-400 border border-green-500/20">${user.status}</span></td>
              `;
              tbody.appendChild(tr);
            });
          }
        })
        .catch(() => indicator.classList.add('hidden'));
    }
  </script>
</body>
</html>
