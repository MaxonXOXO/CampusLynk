<!-- =========================================================================
     CARMIE - Carmel-linx Campus Guide & Academic Mentor (Component)
     Carmie is a friendly, smart female academic guide for Carmel Polytechnic.
     ========================================================================= -->
<div id="carmieWidgetContainer" class="no-print select-none">

  <!-- SVG AVATAR DEFINITION TEMPLATE -->
  <svg class="hidden">
    <defs>
      <linearGradient id="carmieAvatarBgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="#f43f5e"/>
        <stop offset="50%" stop-color="#a855f7"/>
        <stop offset="100%" stop-color="#6366f1"/>
      </linearGradient>
      <linearGradient id="carmieSkinGrad" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#fff1eb"/>
        <stop offset="100%" stop-color="#fcd5c0"/>
      </linearGradient>
      <linearGradient id="carmieHairGrad" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" stop-color="#4a2018"/>
        <stop offset="100%" stop-color="#24100c"/>
      </linearGradient>
    </defs>
  </svg>

  <!-- FLOATING ACTION BUTTON (BOTTOM-RIGHT) -->
  <div id="carmieFabWrapper" class="fixed bottom-6 right-6 z-[9999] flex items-center gap-3">
    <!-- Initial Greeting Balloon -->
    <div id="carmieGreetingBubble" class="hidden sm:flex items-center gap-2.5 bg-slate-900/95 border border-rose-500/40 text-slate-100 text-xs font-semibold px-3.5 py-2 rounded-2xl shadow-xl shadow-rose-950/40 backdrop-blur-md animate-bounce cursor-pointer" onclick="toggleCarmieChat()">
      <span class="text-base">🌸</span>
      <span>Need help? Ask <strong>Carmie</strong>!</span>
      <button type="button" onclick="dismissCarmieGreeting(event)" class="text-slate-400 hover:text-white ml-1 text-sm leading-none">&times;</button>
    </div>

    <!-- Toggle Button -->
    <button type="button" 
            id="carmieFabBtn"
            onclick="toggleCarmieChat()" 
            aria-label="Ask Carmie"
            class="relative w-14 h-14 rounded-full bg-gradient-to-tr from-rose-500 via-purple-600 to-indigo-600 hover:from-rose-400 hover:to-indigo-500 text-white shadow-xl shadow-rose-500/25 border-2 border-white/30 flex items-center justify-center transition-all duration-300 transform hover:scale-105 active:scale-95 cursor-pointer group overflow-hidden">
      
      <!-- Sparkle pulse ring -->
      <span class="absolute -inset-1 rounded-full bg-gradient-to-r from-rose-500 to-indigo-500 opacity-40 blur-sm group-hover:opacity-75 animate-pulse transition duration-300 pointer-events-none"></span>

      <!-- Carmie Face Avatar (Visible when closed) -->
      <div id="carmieFabAvatar" class="relative z-10 w-full h-full p-0.5 transition-transform duration-300 group-hover:scale-105 flex items-center justify-center">
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

      <!-- Close Icon (Visible when open) -->
      <span id="carmieFabClose" class="hidden material-symbols-rounded text-2xl relative z-10 text-white">close</span>

      <!-- Online Dot Indicator -->
      <span class="absolute top-1 right-1 w-3.5 h-3.5 bg-emerald-400 border-2 border-slate-900 rounded-full z-20 shadow-xs"></span>
    </button>
  </div>

  <!-- CHAT DRAWER / WINDOW -->
  <div id="carmieChatModal" 
       class="hidden fixed bottom-6 right-6 sm:bottom-24 sm:right-6 z-[10000] w-[calc(100vw-32px)] sm:w-[410px] h-[580px] max-h-[calc(100vh-100px)] bg-slate-900/95 border border-slate-700/80 rounded-3xl shadow-2xl shadow-black/80 flex flex-col overflow-hidden backdrop-blur-xl transition-all duration-300 font-sans">
    
    <!-- HEADER -->
    <div class="px-4 py-3.5 bg-slate-950/80 border-b border-slate-800 flex items-center justify-between shrink-0">
      <div class="flex items-center gap-3">
        <div class="relative w-11 h-11 rounded-2xl overflow-hidden shadow-md border border-rose-400/40 shrink-0 bg-slate-900 flex items-center justify-center p-0.5">
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
          <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 border-2 border-slate-900 rounded-full z-20"></span>
        </div>
        <div>
          <div class="flex items-center gap-1.5">
            <h3 class="font-extrabold text-sm text-white tracking-tight">Carmie</h3>
            <span class="text-xs">🌸</span>
            <span id="carmieEngineBadge" class="text-[9.5px] font-mono font-bold px-1.5 py-0.2 rounded bg-rose-500/15 text-rose-300 border border-rose-500/30">
              Campus Mentor
            </span>
          </div>
          <p class="text-[11px] text-slate-400 leading-tight">Carmel-linx Academic Companion</p>
        </div>
      </div>

      <div class="flex items-center gap-1 text-slate-400">
        <button type="button" onclick="clearCarmieHistory()" title="Clear conversation" class="p-1.5 hover:text-slate-200 hover:bg-slate-800/80 rounded-xl transition cursor-pointer">
          <span class="material-symbols-rounded text-lg">delete_sweep</span>
        </button>
        <button type="button" onclick="toggleCarmieChat()" title="Close Carmie" class="p-1.5 hover:text-slate-200 hover:bg-slate-800/80 rounded-xl transition cursor-pointer">
          <span class="material-symbols-rounded text-lg">close</span>
        </button>
      </div>
    </div>

    <!-- MAIN CATEGORY / REVISION TABS -->
    <div class="px-3 pt-2 pb-1.5 bg-slate-950/80 border-b border-slate-800 flex items-center gap-1.5 overflow-x-auto no-scrollbar shrink-0" id="carmieCategoryTabs">
      <button type="button" onclick="setCarmieCategory('all')" id="carmieTab_all" class="carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-white bg-rose-600 shadow-xs flex items-center gap-1 cursor-pointer shrink-0">
        <span>⚡</span><span>Quick Help</span>
      </button>
      <button type="button" onclick="setCarmieCategory('2021')" id="carmieTab_2021" class="carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-200 hover:bg-slate-800/80 flex items-center gap-1 cursor-pointer shrink-0">
        <span>📘</span><span>Rev 2021</span>
      </button>
      <button type="button" onclick="setCarmieCategory('2026')" id="carmieTab_2026" class="carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-200 hover:bg-slate-800/80 flex items-center gap-1 cursor-pointer shrink-0">
        <span>📙</span><span>Rev 2026</span>
      </button>
      <button type="button" onclick="setCarmieCategory('attainment')" id="carmieTab_attainment" class="carmie-cat-tab px-2.5 py-1 text-[10.5px] font-bold rounded-lg transition-all text-slate-400 hover:text-slate-200 hover:bg-slate-800/80 flex items-center gap-1 cursor-pointer shrink-0">
        <span>📊</span><span>Attainment</span>
      </button>
    </div>

    <!-- QUICK STARTER SUGGESTION CHIPS -->
    <div class="px-3.5 py-2 bg-slate-950/40 border-b border-slate-800/60 overflow-x-auto flex items-center gap-1.5 no-scrollbar shrink-0" id="carmieChipsContainer">
    </div>

    <!-- CHAT MESSAGES SCROLL AREA -->
    <div id="carmieMessages" class="flex-1 p-4 overflow-y-auto space-y-3.5 text-xs select-text">
      <!-- Default Welcome Message -->
      <div class="flex items-start gap-2.5">
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
        <div class="max-w-[85%] bg-slate-800/80 border border-slate-700/80 rounded-2xl rounded-tl-sm p-3 text-slate-200 leading-relaxed space-y-1.5 shadow-sm">
          <p>Hi! I'm <strong>Carmie</strong>, your Carmel-linx academic companion! 🌸</p>
          <p class="text-slate-300 text-[11.5px]">
            Ask me anytime you're stuck or need help with assignments, CO-PO matrices, attendance, marks, or reports.
          </p>
          <p class="text-slate-400 text-[10.5px] italic pt-1 border-t border-slate-700/60">
            Click any suggestion chip above or type your question below!
          </p>
        </div>
      </div>
    </div>

    <!-- TYPING INDICATOR -->
    <div id="carmieTypingIndicator" class="hidden px-4 py-2 bg-slate-950/40 text-slate-400 text-xs flex items-center gap-2 shrink-0">
      <span class="material-symbols-rounded text-sm animate-spin text-rose-400">autorenew</span>
      <span class="text-[11px] font-medium text-rose-200">Carmie is preparing your answer... 🌸</span>
    </div>

    <!-- INPUT BAR -->
    <form id="carmieForm" onsubmit="handleCarmieSubmit(event)" class="p-3 bg-slate-950/90 border-t border-slate-800 flex items-center gap-2 shrink-0">
      <input type="text"
             id="carmieInput"
             placeholder="Ask Carmie a question..."
             autocomplete="off"
             class="flex-1 bg-slate-900 border border-slate-700/80 hover:border-slate-600 focus:border-rose-500 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 outline-none transition-colors shadow-inner">
      <button type="submit" 
              id="carmieSubmitBtn"
              class="w-10 h-10 rounded-xl bg-gradient-to-r from-rose-600 via-purple-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white flex items-center justify-center transition-all shadow-md active:scale-95 cursor-pointer shrink-0 disabled:opacity-50">
        <span class="material-symbols-rounded text-lg">send</span>
      </button>
    </form>
  </div>

</div>

@include('components.carmie.scripts')
