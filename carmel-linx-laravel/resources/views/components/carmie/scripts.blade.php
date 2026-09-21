<!-- CARMIE CLIENT-SIDE ENGINE SCRIPT -->
<script>
  let carmieChatOpen = false;
  let carmieHistory = [];

  const CARMIE_AVATAR_HTML = `
    <div class="w-7 h-7 rounded-full overflow-hidden shrink-0 shadow-sm border border-rose-400/40 bg-slate-900 p-0.5">
      <svg viewBox="0 0 100 100" class="w-full h-full select-none" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" r="48" fill="url(#carmieAvatarBgGrad)"/>
        <path d="M22 96 C24 80 34 75 44 77 L50 82 L56 77 C66 75 76 80 78 96 Z" fill="#3730a3"/>
        <path d="M44 77 C40 82 46 87 50 82 C54 87 60 82 56 77 Z" fill="#ffffff"/>
        <rect x="44" y="66" width="12" height="13" rx="4" fill="#fcd5c0"/>
        <path d="M27 48 C23 60 25 76 30 83 C34 79 38 74 40 70 C60 70 62 74 70 83 C75 76 77 60 73 48 C70 32 30 32 27 48 Z" fill="url(#carmieHairGrad)"/>
        <ellipse cx="50" cy="52" rx="19" ry="21" fill="url(#carmieSkinGrad)"/>
        <circle cx="37" cy="57" r="4.5" fill="#f43f5e" opacity="0.35"/>
        <circle cx="63" cy="57" r="4.5" fill="#f43f5e" opacity="0.35"/>
        <path d="M37 49 Q41.5 45.5 45 49.5" stroke="#24100c" stroke-width="2.5" stroke-linecap="round" fill="none"/>
        <path d="M55 49.5 Q58.5 45.5 63 49" stroke="#24100c" stroke-width="2.5" stroke-linecap="round" fill="none"/>
        <path d="M36 43 Q41 39.5 45 42" stroke="#4a2018" stroke-width="1.8" stroke-linecap="round" fill="none"/>
        <path d="M55 42 Q59 39.5 64 43" stroke="#4a2018" stroke-width="1.8" stroke-linecap="round" fill="none"/>
        <circle cx="50" cy="53.5" r="1.2" fill="#e29d82"/>
        <path d="M43.5 59 Q50 66.5 56.5 59" stroke="#e11d48" stroke-width="2.2" stroke-linecap="round" fill="#ffffff"/>
        <rect x="33" y="44" width="13.5" height="10" rx="3.5" stroke="#fbbf24" stroke-width="1.6" fill="rgba(255,255,255,0.2)"/>
        <rect x="53.5" y="44" width="13.5" height="10" rx="3.5" stroke="#fbbf24" stroke-width="1.6" fill="rgba(255,255,255,0.2)"/>
        <path d="M46.5 48 L53.5 48" stroke="#fbbf24" stroke-width="1.6"/>
        <path d="M29 44 C29 30 37 21 50 21 C63 21 71 30 71 44 C67 37 60 33 53 34 C44 35 38 41 33 42 C30 42 29 43 29 44 Z" fill="url(#carmieHairGrad)"/>
        <circle cx="66" cy="32" r="3.2" fill="#fb7185"/>
        <circle cx="66" cy="32" r="1.3" fill="#fef08a"/>
      </svg>
    </div>
  `;

  function toggleCarmieChat() {
    carmieChatOpen = !carmieChatOpen;
    const modal = document.getElementById('carmieChatModal');
    const fabAvatar = document.getElementById('carmieFabAvatar');
    const fabClose = document.getElementById('carmieFabClose');
    const greeting = document.getElementById('carmieGreetingBubble');

    if (greeting) greeting.classList.add('hidden');

    if (carmieChatOpen) {
      if (modal) modal.classList.remove('hidden');
      if (fabAvatar) fabAvatar.classList.add('hidden');
      if (fabClose) fabClose.classList.remove('hidden');
      setTimeout(() => {
        const inp = document.getElementById('carmieInput');
        if (inp) inp.focus();
      }, 100);
    } else {
      if (modal) modal.classList.add('hidden');
      if (fabAvatar) fabAvatar.classList.remove('hidden');
      if (fabClose) fabClose.classList.add('hidden');
    }
  }

  function dismissCarmieGreeting(e) {
    if (e) e.stopPropagation();
    const g = document.getElementById('carmieGreetingBubble');
    if (g) g.remove();
    localStorage.setItem('carmie_greeting_dismissed', 'true');
  }

  function sendCarmieQuickPrompt(text) {
    const inp = document.getElementById('carmieInput');
    if (inp) {
      inp.value = text;
      handleCarmieSubmit(new Event('submit'));
    }
  }

  function clearCarmieHistory() {
    carmieHistory = [];
    const container = document.getElementById('carmieMessages');
    if (container) {
      container.innerHTML = `
        <div class="flex items-start gap-2.5">
          ${CARMIE_AVATAR_HTML}
          <div class="max-w-[85%] bg-slate-800/80 border border-slate-700/80 rounded-2xl rounded-tl-sm p-3 text-slate-200 leading-relaxed shadow-sm">
            <p>Chat cleared! What would you like to explore next? 🌸</p>
          </div>
        </div>
      `;
    }
  }

  function appendCarmieMessage(sender, text, actionLabel = null, actionRoute = null, source = null) {
    const container = document.getElementById('carmieMessages');
    if (!container) return;

    const row = document.createElement('div');
    row.className = sender === 'user' ? 'flex items-start justify-end gap-2.5' : 'flex items-start gap-2.5';

    let bubbleHtml = '';

    if (sender === 'user') {
      bubbleHtml = `
        <div class="max-w-[85%] bg-gradient-to-r from-rose-600 via-purple-600 to-indigo-600 text-white rounded-2xl rounded-tr-sm p-3 shadow-md text-xs leading-relaxed">
          ${escapeHtml(text)}
        </div>
        <div class="w-7 h-7 rounded-full bg-slate-800 border border-slate-600 text-slate-200 flex items-center justify-center shrink-0 text-xs font-bold shadow-sm">
          <span class="material-symbols-rounded text-sm">person</span>
        </div>
      `;
    } else {
      let formatted = formatCarmieMarkdown(text);
      let actionHtml = '';
      if (actionLabel && actionRoute && actionRoute !== '#') {
        actionHtml = `
          <div class="pt-2 mt-2 border-t border-slate-700/60">
            <a href="${actionRoute}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-600/20 hover:bg-rose-600/35 text-rose-200 border border-rose-500/30 text-[11px] font-bold transition-all shadow-xs">
              <span>✨ ${escapeHtml(actionLabel)}</span>
              <span class="material-symbols-rounded text-xs">arrow_forward</span>
            </a>
          </div>
        `;
      }

      let sourceBadge = `<span class="inline-block mt-2 text-[9.5px] font-mono text-emerald-400/80 bg-emerald-950/40 px-1.5 py-0.2 rounded border border-emerald-800/40">Verified Carmel-linx Guide ⚡</span>`;

      bubbleHtml = `
        ${CARMIE_AVATAR_HTML}
        <div class="max-w-[85%] bg-slate-800/90 border border-slate-700/80 rounded-2xl rounded-tl-sm p-3.5 text-slate-200 leading-relaxed shadow-sm space-y-2">
          <div class="carmie-rendered-markdown leading-relaxed text-slate-200">${formatted}</div>
          ${actionHtml}
          ${sourceBadge}
        </div>
      `;
    }

    row.innerHTML = bubbleHtml;
    container.appendChild(row);
    container.scrollTop = container.scrollHeight;
  }

  function formatCarmieMarkdown(text) {
    if (!text) return '';
    let t = text;

    t = t.replace(/^### (.*$)/gim, '<h4 class="font-bold text-rose-300 text-xs mt-1 mb-1">$1</h4>');
    t = t.replace(/^## (.*$)/gim, '<h3 class="font-bold text-white text-xs mt-1 mb-1">$1</h3>');
    t = t.replace(/\*\*(.*?)\*\*/gim, '<strong class="font-bold text-white">$1</strong>');
    t = t.replace(/\*(.*?)\*/gim, '<em class="text-slate-300 italic">$1</em>');
    t = t.replace(/^• (.*$)/gim, '<div class="flex items-start gap-1.5 my-1"><span class="text-rose-400 font-bold shrink-0 mt-0.5">•</span><span>$1</span></div>');
    t = t.replace(/^(\d+)\. (.*$)/gim, '<div class="flex items-start gap-1.5 my-1"><span class="text-rose-400 font-mono font-bold shrink-0">$1.</span><span>$2</span></div>');
    t = t.replace(/\n\n/g, '<div class="h-1.5"></div>');

    return t;
  }

  let activeCarmieCategory = 'all';

  const CARMIE_CHIP_SETS = {
    'all': [
      { label: '🚀 Project 2021 (75 CIA + 50 ESE)', query: 'How is Revision 2021 Major Project evaluated with 75 CIA and 50 ESE?', color: 'blue' },
      { label: '🎤 Seminar 2021 (75 CIA Only)', query: 'How does Revision 2021 Seminar two-faculty evaluation work for 75 CIA?', color: 'blue' },
      { label: '📐 Drawing 2021 (Lab Criteria)', query: 'How does Revision 2021 Drawing class practical evaluation work?', color: 'blue' },
      { label: '📊 Online Exit Survey & Attainment', query: 'How do HOD and Tutor run online exit surveys and generate program attainment?', color: 'purple' },
      { label: '🎯 CO-PO Autosave in Rev 2026', query: 'How does CO-PO matrix autosave work in Revision 2026 theory?', color: 'emerald' },
      { label: '📅 Attendance & Subject Log', query: 'How do I submit hourly attendance and subject log?', color: 'slate' },
      { label: '💡 What can I do here?', query: 'Where am I and what can I do on this page?', color: 'rose' }
    ],
    '2021': [
      { label: '🚀 Project 2021 (75 CIA & 50 ESE)', query: 'How is Revision 2021 Major Project evaluated with 75 CIA and 50 ESE?', color: 'blue' },
      { label: '🎤 Seminar 2021 (75 CIA Only)', query: 'How does Revision 2021 Seminar two-faculty evaluation work for 75 CIA?', color: 'blue' },
      { label: '📐 Drawing 2021 (Lab Criteria)', query: 'How does Revision 2021 Drawing class practical evaluation work?', color: 'blue' },
      { label: '📑 Print Group-Wise Breakdown', query: 'How do I print the Group-Wise Breakdown for separate filing in Major Project?', color: 'blue' },
      { label: '👥 Group Common CIA & ESE (60M / 27.5M)', query: 'How to use 1-click group common scoring in Major Project 2021?', color: 'blue' },
      { label: '🔬 Lab 2021 (37.5 Formative + Tests)', query: 'Explain Revision 2021 Lab 75 CIA split-up with rough record and fair record', color: 'blue' }
    ],
    '2026': [
      { label: '🎯 CO-PO Autosave in Rev 2026', query: 'How does CO-PO matrix autosave work in Revision 2026 theory?', color: 'emerald' },
      { label: '📝 Theory 40 CIE Breakdown', query: 'Explain the Revision 2026 theory 40 CIE marks breakdown', color: 'emerald' },
      { label: '📚 Table 2.2 Self-Learning', query: 'How to configure Table 2.2 self-learning marks in Rev 2026?', color: 'emerald' },
      { label: '🔬 Practicum 90-Hour Workspace', query: 'How does Revision 2026 Practicum 90-Hour combined workspace operate?', color: 'emerald' },
      { label: '📁 Course File 16-Item Checklist', query: 'How do I prepare the course file checklist for HOD approval?', color: 'slate' }
    ],
    'attainment': [
      { label: '📊 Run Online Exit Survey & Attainment', query: 'How do HOD and Tutor run online exit surveys and generate program attainment?', color: 'purple' },
      { label: '📈 80% Direct + 20% Indirect Formula', query: 'How is the 80% Direct + 20% Indirect Attainment formula calculated in Carmel-Linx?', color: 'purple' },
      { label: '📁 NBA Criterion 3 Compliance Dossier', query: 'How to export the NBA Criterion 3 attainment reports for department audit?', color: 'purple' },
      { label: '🎯 Universal 5-Classroom Attainment', query: 'How does attainment work across Theory, Lab, Seminar, Project, and Drawing?', color: 'purple' }
    ]
  };

  function setCarmieCategory(cat) {
    activeCarmieCategory = cat;
    const tabs = ['all', '2021', '2026', 'attainment'];
    tabs.forEach(t => {
      const btn = document.getElementById('carmieTab_' + t);
      if (!btn) return;
      if (t === cat) {
        btn.className = "carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-white bg-rose-600 shadow-xs flex items-center gap-1 cursor-pointer shrink-0";
      } else {
        btn.className = "carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-200 hover:bg-slate-800/80 flex items-center gap-1 cursor-pointer shrink-0";
      }
    });

    renderCarmieChips(cat);
  }

  function renderCarmieChips(cat) {
    const container = document.getElementById('carmieChipsContainer');
    if (!container) return;

    const chips = CARMIE_CHIP_SETS[cat] || CARMIE_CHIP_SETS['all'];
    let html = '';

    chips.forEach(chip => {
      let colorClass = "bg-slate-800 hover:bg-slate-700 text-slate-300 border-slate-700";
      if (chip.color === 'rose') {
        colorClass = "bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border-rose-500/30";
      } else if (chip.color === 'blue') {
        colorClass = "bg-sky-500/10 hover:bg-sky-500/20 text-sky-300 border-sky-500/30";
      } else if (chip.color === 'emerald') {
        colorClass = "bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border-emerald-500/30";
      } else if (chip.color === 'purple') {
        colorClass = "bg-purple-500/10 hover:bg-purple-500/20 text-purple-300 border-purple-500/30";
      }

      const escapedLabel = escapeHtml(chip.label);
      const escapedQuery = chip.query.replace(/'/g, "\\'");
      html += `<button type="button" onclick="sendCarmieQuickPrompt('${escapedQuery}')" class="carmie-chip whitespace-nowrap text-[10.5px] font-semibold ${colorClass} border px-2.5 py-1 rounded-full transition-all cursor-pointer shrink-0">${escapedLabel}</button>`;
    });

    container.innerHTML = html;
  }

  function escapeHtml(string) {
    const el = document.createElement('div');
    el.innerText = string || '';
    return el.innerHTML;
  }

  function handleCarmieSubmit(e) {
    if (e) e.preventDefault();
    const input = document.getElementById('carmieInput');
    const submitBtn = document.getElementById('carmieSubmitBtn');
    const typingIndicator = document.getElementById('carmieTypingIndicator');

    if (!input || !input.value.trim()) return;

    const query = input.value.trim();
    input.value = '';

    appendCarmieMessage('user', query);

    if (typingIndicator) typingIndicator.classList.remove('hidden');
    if (submitBtn) submitBtn.disabled = true;

    const currentUrl = window.location.pathname + window.location.search;
    let subjectCode = '';
    const codeEl = document.querySelector('[data-subject-code]');
    if (codeEl) subjectCode = codeEl.getAttribute('data-subject-code');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    fetch('/api/carmie/ask', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        query: query,
        current_url: currentUrl,
        subject_code: subjectCode,
        category: activeCarmieCategory
      })
    })
    .then(res => res.json())
    .then(data => {
      if (typingIndicator) typingIndicator.classList.add('hidden');
      if (submitBtn) submitBtn.disabled = false;

      if (data.status === 'SUCCESS' || data.reply) {
        appendCarmieMessage('carmie', data.reply, data.action_label, data.action_route, data.source);
      } else {
        appendCarmieMessage('carmie', "Sorry, I had trouble processing that. Could you try asking in a slightly different way?");
      }
    })
    .catch(err => {
      if (typingIndicator) typingIndicator.classList.add('hidden');
      if (submitBtn) submitBtn.disabled = false;
      appendCarmieMessage('carmie', "Network error connecting to Carmie: " + err.message);
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    renderCarmieChips('all');

    if (localStorage.getItem('carmie_greeting_dismissed') === 'true') {
      const g = document.getElementById('carmieGreetingBubble');
      if (g) g.remove();
    } else {
      setTimeout(() => {
        dismissCarmieGreeting();
      }, 9000);
    }
  });
</script>
