<!-- CARMIE CLIENT-SIDE ENGINE SCRIPT -->
<script>
  let carmieChatOpen = false;
  let carmieHistory = [];

  const CARMIE_AVATAR_HTML = `
    <div class="w-8 h-8 rounded-xl overflow-hidden shrink-0 shadow-sm border border-rose-400/40 bg-slate-900 p-0.5 flex items-center justify-center">
      <img src="{{ asset('carmie_icon.png') }}" 
           alt="Carmie" 
           onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
           class="w-full h-full object-cover rounded-lg select-none pointer-events-none">
      <div class="w-full h-full bg-gradient-to-tr from-rose-500 to-indigo-600 rounded-lg flex items-center justify-center text-white hidden">
        <i data-lucide="bot" class="w-4 h-4"></i>
      </div>
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
      if (window.lucide) window.lucide.createIcons();
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
    try {
      localStorage.setItem('carmie_greeting_dismissed', 'true');
    } catch (e) {}
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
      if (window.lucide) window.lucide.createIcons();
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
        <div class="w-8 h-8 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 flex items-center justify-center shrink-0 text-xs font-bold shadow-sm">
          <i data-lucide="user" class="w-4 h-4 text-slate-300"></i>
        </div>
      `;
    } else {
      let formatted = formatCarmieMarkdown(text);

      let actionHtml = '';
      if (actionLabel && actionRoute && actionRoute !== '#') {
        actionHtml = `
          <div class="pt-2 mt-2 border-t border-slate-700/60">
            <a href="${actionRoute}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-600/20 hover:bg-rose-600/35 text-rose-200 border border-rose-500/30 text-[11px] font-bold transition-all shadow-xs no-underline">
              <span>✨ ${escapeHtml(actionLabel)}</span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
          </div>
        `;
      }

      let sourceBadge = '';
      if (source === 'local_playbook') {
        sourceBadge = `<span class="inline-block mt-2 text-[9.5px] font-mono text-emerald-400/90 bg-emerald-950/50 px-2 py-0.5 rounded-md border border-emerald-800/40">Verified Carmel-linx Guide ⚡</span>`;
      } else if (source === 'gemini_ai') {
        sourceBadge = `<span class="inline-block mt-2 text-[9.5px] font-mono text-rose-300/90 bg-rose-950/50 px-2 py-0.5 rounded-md border border-rose-800/40">Powered by Gemini AI ✨</span>`;
      }

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
    if (window.lucide) window.lucide.createIcons();
  }

  function formatCarmieMarkdown(text) {
    if (!text) return '';
    let t = text;

    // Headers
    t = t.replace(/^### (.*$)/gim, '<h4 class="font-bold text-rose-300 text-xs mt-1.5 mb-1">$1</h4>');
    t = t.replace(/^## (.*$)/gim, '<h3 class="font-bold text-white text-xs mt-1.5 mb-1">$1</h3>');

    // Bold
    t = t.replace(/\*\*(.*?)\*\*/gim, '<strong class="font-bold text-white">$1</strong>');
    
    // Italics
    t = t.replace(/\*(.*?)\*/gim, '<em class="text-slate-300 italic">$1</em>');

    // Bullet points
    t = t.replace(/^• (.*$)/gim, '<div class="flex items-start gap-1.5 my-1"><span class="text-rose-400 font-bold shrink-0 mt-0.5">•</span><span>$1</span></div>');
    t = t.replace(/^(\d+)\. (.*$)/gim, '<div class="flex items-start gap-1.5 my-1"><span class="text-rose-400 font-mono font-bold shrink-0">$1.</span><span>$2</span></div>');

    // Line breaks
    t = t.replace(/\n\n/g, '<div class="h-1.5"></div>');

    return t;
  }

  let activeCarmieCategory = 'all';

  const CARMIE_BASE_CHIPS = {
    'all': [
      { label: '💡 What can I do here?', query: 'Where am I and what are the main features on this page?', color: 'rose' },
      { label: '🎯 CO-PO Matrix & Autosave', query: 'How does CO-PO matrix mapping and autosave work in Revision 2026 theory?', color: 'emerald' },
      { label: '📐 CAD & Drawing Hall', query: 'How does Revision 2021/2026 Drawing class manual & CAD drafting practical evaluation work?', color: 'blue' },
      { label: '🔬 Lab 2021 (75 CIA Split)', query: 'Explain Revision 2021 Lab 75 CIA split-up with rough and fair record', color: 'blue' },
      { label: '🚀 Project 2021 (75 CIA & 50 ESE)', query: 'How is Revision 2021 Major Project evaluated with 75 CIA and 50 ESE?', color: 'blue' },
      { label: '📊 Exit Survey & Attainment', query: 'How do HOD and Tutor run online exit surveys and generate program attainment?', color: 'purple' },
      { label: '📅 Attendance & Subject Log', query: 'How do I submit hourly attendance and subject log in Carmel-Linx?', color: 'slate' }
    ],
    '2021': [
      { label: '🚀 Project 2021 (75 CIA & 50 ESE)', query: 'How is Revision 2021 Major Project evaluated with 75 CIA and 50 ESE?', color: 'blue' },
      { label: '🎤 Seminar 2021 (75 CIA Only)', query: 'How does Revision 2021 Seminar two-faculty evaluation work for 75 CIA?', color: 'blue' },
      { label: '📐 Drawing 2021 (Lab Criteria)', query: 'How does Revision 2021 Drawing class practical evaluation work under lab criteria?', color: 'blue' },
      { label: '📑 Group-Wise Breakdown Print', query: 'How do I print the Group-Wise Breakdown for separate filing in Major Project?', color: 'blue' },
      { label: '👥 1-Click Group Scoring', query: 'How to use 1-click group common scoring in Major Project 2021?', color: 'blue' },
      { label: '🔬 Lab 2021 (37.5 Formative + Tests)', query: 'Explain Revision 2021 Lab 75 CIA split-up with rough record and fair record', color: 'blue' }
    ],
    '2026': [
      { label: '🎯 CO-PO Autosave in Rev 2026', query: 'How does CO-PO matrix autosave work in Revision 2026 theory?', color: 'emerald' },
      { label: '📝 Theory 40 CIE Breakdown', query: 'Explain the Revision 2026 theory 40 CIE marks breakdown', color: 'emerald' },
      { label: '📚 Table 2.2 Self-Learning', query: 'How to configure Table 2.2 self-learning marks in Rev 2026?', color: 'emerald' },
      { label: '🔬 Practicum 90-Hour Workspace', query: 'How does Revision 2026 Practicum 90-Hour combined workspace operate?', color: 'emerald' },
      { label: '📐 CAD 2D Drafting & Plotting', query: 'Explain the 5 drafting rubrics for CAD 2D drafting and plotting under Rev 2026', color: 'emerald' },
      { label: '📁 Course File 16-Item Checklist', query: 'How do I prepare the course file checklist for HOD approval?', color: 'slate' }
    ],
    'attainment': [
      { label: '📊 Run Online Exit Survey & Attainment', query: 'How do HOD and Tutor run online exit surveys and generate program attainment?', color: 'purple' },
      { label: '📈 80% Direct + 20% Indirect Formula', query: 'How is the 80% Direct + 20% Indirect Attainment formula calculated in Carmel-Linx?', color: 'purple' },
      { label: '📁 NBA Criterion 3 Compliance Dossier', query: 'How to export the NBA Criterion 3 attainment reports for department audit?', color: 'purple' },
      { label: '🎯 Universal 5-Classroom Attainment', query: 'How does attainment work across Theory, Lab, Seminar, Project, and Drawing?', color: 'purple' }
    ]
  };

  function getContextualChips(category) {
    let chips = [...(CARMIE_BASE_CHIPS[category] || CARMIE_BASE_CHIPS['all'])];
    const path = window.location.pathname.toLowerCase();

    // Context-sensitive priority insertion at the beginning
    if (path.includes('drawing')) {
      chips.unshift(
        { label: '📐 CAD Drafting & Plotting Rubrics', query: 'How are manual drawing and 2D CAD drafting evaluated across the 5 statutory rubrics?', color: 'blue' },
        { label: '🎯 Drawing Continuous Eval (30M)', query: 'Explain Continuous Evaluation CE 30M for Drawing Hall', color: 'blue' }
      );
    } else if (path.includes('practical') || path.includes('virtual-lab')) {
      chips.unshift(
        { label: '🔬 Table 2.2 Continuous Lab Work (37.5M)', query: 'How to evaluate and sync Table 2.2 Continuous Lab Work with experiment dates?', color: 'blue' },
        { label: '👥 Lab Batch 1 & 2 Roster', query: 'How to assign students to Batch 1 and Batch 2 in Virtual Lab?', color: 'blue' }
      );
    } else if (path.includes('theory')) {
      chips.unshift(
        { label: '📝 R26 Theory 40 CIE Marks', query: 'Explain the Revision 2026 theory 40 CIE marks split-up', color: 'emerald' },
        { label: '📅 Lesson Plan Row Addition', query: 'How do I add custom lesson plan rows and sync attendance dates?', color: 'emerald' }
      );
    } else if (path.includes('practicum')) {
      chips.unshift(
        { label: '🔬 90-Hr Practicum Lab + Theory', query: 'How does the combined 90-Hour Practicum classroom operate?', color: 'emerald' },
        { label: '📊 Table 2.2 & 3.1 Evaluator Modals', query: 'How to enter marks in Table 2.2 and Table 3.1 in R26 Practicum?', color: 'emerald' }
      );
    }

    return chips;
  }

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

    const chips = getContextualChips(cat);
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

    // Collect active context
    const currentUrl = window.location.pathname + window.location.search;
    let subjectCode = '';
    const codeEl = document.querySelector('[data-subject-code]');
    if (codeEl) subjectCode = codeEl.getAttribute('data-subject-code');

    fetch('/api/carmie/ask', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
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

    // Check if greeting was already dismissed previously
    try {
      if (localStorage.getItem('carmie_greeting_dismissed') === 'true') {
        const g = document.getElementById('carmieGreetingBubble');
        if (g) g.remove();
      } else {
        setTimeout(() => {
          dismissCarmieGreeting();
        }, 9000);
      }
    } catch (e) {}

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && carmieChatOpen) {
        toggleCarmieChat();
      }
    });

    // Close when clicking outside
    document.addEventListener('click', function(e) {
      const wrapper = document.getElementById('carmieWidgetContainer');
      const modal = document.getElementById('carmieChatModal');
      if (carmieChatOpen && wrapper && modal && !wrapper.contains(e.target)) {
        toggleCarmieChat();
      }
    });
  });
</script>
