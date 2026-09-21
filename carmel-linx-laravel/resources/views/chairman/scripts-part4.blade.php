      }
    }

    function filterEventsByCategory(cat) {
      activeEventCategoryFilter = cat;
      updateCategoryFilterTabsUI();
      renderTodayEventsModalList();
    }

    function updateCategoryFilterTabsUI() {
      const tabs = document.querySelectorAll('.evt-cat-tab');
      tabs.forEach(tab => {
        const tabCat = tab.id.replace('evtCatTab_', '');
        if (tabCat === activeEventCategoryFilter) {
          tab.className = 'evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-sky-500/20 text-sky-300 border border-sky-500/40 shadow-sm';
        } else {
          tab.className = 'evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-slate-600 cursor-pointer';
        }
      });
    }

    function renderTodayEventsModalList() {
      const container = document.getElementById('modalEventsListContainer');
      if (!container) return;

      const events = allTodayEventsCache || [];
      const catFilter = activeEventCategoryFilter || 'ALL';

      const filtered = catFilter === 'ALL' 
        ? events 
        : events.filter(ev => (ev.organizer || 'College') === catFilter);

      const showingEl = document.getElementById('modalShowingCount');
      if (showingEl) showingEl.innerText = filtered.length;

      if (filtered.length === 0) {
        container.innerHTML = `
          <div class="p-8 text-center text-slate-500 bg-slate-950/40 rounded-xl border border-slate-800/60">
            <span class="material-symbols-rounded text-3xl block text-slate-600 mb-2">event_busy</span>
            <span class="font-bold text-xs text-slate-400">No scheduled events found under ${catFilter === 'ALL' ? 'today' : '\'' + catFilter + '\''} category.</span>
          </div>
        `;
        return;
      }

      container.innerHTML = filtered.map(ev => {
        const org = ev.organizer || 'College';
        let badgeClass = 'bg-sky-500/10 text-sky-400 border-sky-500/20';
        let iconName   = 'school';

        if (org === 'Departments') {
          badgeClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
          iconName   = 'domain';
        } else if (org === 'NSS') {
          badgeClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
          iconName   = 'volunteer_activism';
        } else if (org === 'NCC') {
          badgeClass = 'bg-rose-500/10 text-rose-400 border-rose-500/20';
          iconName   = 'military_tech';
        } else if (org === 'IEDC') {
          badgeClass = 'bg-purple-500/10 text-purple-400 border-purple-500/20';
          iconName   = 'lightbulb';
        } else if (org === 'Placement Cell') {
          badgeClass = 'bg-teal-500/10 text-teal-400 border-teal-500/20';
          iconName   = 'work';
        } else if (org === 'Others') {
          badgeClass = 'bg-slate-800 text-slate-300 border-slate-700';
          iconName   = 'event';
        }

        return `
          <div class="bg-slate-950/60 border border-slate-800/80 hover:border-slate-700 p-4 rounded-xl transition flex flex-col md:flex-row justify-between md:items-center gap-4">
            <div class="space-y-1.5 flex-1 min-w-0">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black border uppercase tracking-wider ${badgeClass} flex items-center gap-1 shrink-0">
                  <span class="material-symbols-rounded text-xs">${iconName}</span>
                  ${org}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-300 border border-slate-700 shrink-0">
                  ${ev.type || 'Event'}
                </span>
                ${ev.branch && ev.branch !== 'ALL' ? `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-slate-800 text-sky-400 border border-slate-700 shrink-0">${ev.branch}</span>` : ''}
              </div>
              <h4 class="font-bold text-slate-100 text-sm sm:text-base leading-snug pt-0.5 break-words">${ev.title}</h4>
            </div>
            <div class="shrink-0 space-y-1 md:text-right text-xs text-slate-400 border-t md:border-t-0 border-slate-800/60 pt-2.5 md:pt-0">
              <div class="flex items-center md:justify-end gap-1.5 font-mono text-slate-200 font-semibold text-xs">
                <span class="material-symbols-rounded text-sm text-sky-400">schedule</span>
                ${ev.time || '09:30 AM - 04:30 PM'}
              </div>
              <div class="flex items-center md:justify-end gap-1.5 text-slate-400 text-xs">
                <span class="material-symbols-rounded text-sm text-amber-400">location_on</span>
                ${ev.venue || 'Campus Main Grounds'}
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    document.addEventListener('DOMContentLoaded', initTheme);
  </script>

  <!-- TODAY'S EVENTS LIST MODAL BY CATEGORIES -->
  <div id="todayEventsModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4 md:p-6 overflow-y-auto">
    <div class="bg-slate-900 border border-slate-700/80 rounded-2xl max-w-6xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
      <!-- Header -->
      <div class="p-4 sm:p-5 border-b border-slate-800 flex items-center justify-between bg-slate-950/60">
        <div class="flex items-center gap-3">
          <div class="p-2.5 bg-sky-500/10 text-sky-400 rounded-xl border border-sky-500/20 flex items-center justify-center">
            <span class="material-symbols-rounded text-xl">event_available</span>
          </div>
          <div>
            <h3 class="text-base font-black text-slate-100 flex items-center gap-2">
              Today's Campus &amp; Academic Events
              <span id="modalEventsTotalBadge" class="px-2 py-0.5 rounded-full text-[10px] font-black bg-sky-500/20 text-sky-300 border border-sky-500/30">0 Total</span>
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">Categorized by Departments, College, NSS, NCC, IEDC, Placement Cell &amp; Others</p>
          </div>
        </div>
        <button onclick="closeTodayEventsModal()" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <!-- Category Filter Tabs Bar -->
      <div class="p-3.5 bg-slate-950/40 border-b border-slate-800 overflow-x-auto scrollbar-hidden flex items-center gap-2">
        <button onclick="filterEventsByCategory('ALL')" id="evtCatTab_ALL" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-sky-500/20 text-sky-300 border border-sky-500/40">
          <span>All Events</span>
          <span id="evtCnt_ALL" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-sky-500/30 text-sky-200">0</span>
        </button>
        <button onclick="filterEventsByCategory('Departments')" id="evtCatTab_Departments" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-amber-500/40">
          <span class="w-2 h-2 rounded-full bg-amber-400"></span>
          <span>Departments</span>
          <span id="evtCnt_Departments" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('College')" id="evtCatTab_College" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-sky-500/40">
          <span class="w-2 h-2 rounded-full bg-sky-400"></span>
          <span>College / Academic</span>
          <span id="evtCnt_College" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('NSS')" id="evtCatTab_NSS" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-emerald-500/40">
          <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
          <span>NSS</span>
          <span id="evtCnt_NSS" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('NCC')" id="evtCatTab_NCC" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-rose-500/40">
          <span class="w-2 h-2 rounded-full bg-rose-400"></span>
          <span>NCC</span>
          <span id="evtCnt_NCC" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('IEDC')" id="evtCatTab_IEDC" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-purple-500/40">
          <span class="w-2 h-2 rounded-full bg-purple-400"></span>
          <span>IEDC</span>
          <span id="evtCnt_IEDC" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('Placement Cell')" id="evtCatTab_Placement Cell" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-teal-500/40">
          <span class="w-2 h-2 rounded-full bg-teal-400"></span>
          <span>Placement Cell</span>
          <span id="evtCnt_Placement Cell" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
        <button onclick="filterEventsByCategory('Others')" id="evtCatTab_Others" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-slate-800/80 text-slate-300 border border-slate-700 hover:border-slate-500">
          <span class="w-2 h-2 rounded-full bg-slate-400"></span>
          <span>Others</span>
          <span id="evtCnt_Others" class="px-1.5 py-0.2 text-[9px] font-black rounded-full bg-slate-700 text-slate-300">0</span>
        </button>
      </div>

      <!-- Modal Events List Container -->
      <div class="p-5 overflow-y-auto flex-grow space-y-3" id="modalEventsListContainer">
        <!-- Loaded dynamically -->
      </div>

      <!-- Footer Summary Badges -->
      <div class="p-4 border-t border-slate-800 bg-slate-950/60 flex items-center justify-between flex-wrap gap-2 text-xs">
        <div class="text-slate-400 text-[11px] font-medium">
          Displaying <span id="modalShowingCount" class="text-slate-200 font-bold">0</span> scheduled event(s)
        </div>
        <button onclick="closeTodayEventsModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl text-xs transition border border-slate-700 cursor-pointer">
          Close Window
        </button>
      </div>
    </div>
  </div>
</body>
</html>
