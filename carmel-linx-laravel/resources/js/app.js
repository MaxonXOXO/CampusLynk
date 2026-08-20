import './bootstrap';
import '../css/app.css';
import { createIcons, icons } from 'lucide';

// Initialize Lucide Icons globally
export function initLucide() {
  createIcons({ icons });
}

// Live AI System Status initialization
export function initAiStatus() {
  const badge = document.getElementById('aiStatusBadge');
  if (!badge) return;

  fetch('/api/system/ai-status')
    .then(res => res.json())
    .then(data => {
      if (data.status === 'SUCCESS') {
        badge.classList.remove('hidden');
        if (data.ai_generation_enabled) {
          badge.innerHTML = `<span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-semibold flex items-center gap-1.5 shadow-2xs"><span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span> AI Active</span>`;
        } else {
          badge.innerHTML = `<span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-xs font-semibold flex items-center gap-1.5 shadow-2xs" title="Gemini AI is deactivated to save API credits. Lesson plans, descriptive questions, and MCQs are generated from local databases and question banks."><span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span> AI Offline (Local DB)</span>`;
        }
      }
    })
    .catch(() => {});
}

if (typeof window !== 'undefined') {
  document.addEventListener('DOMContentLoaded', () => {
    initLucide();
    initAiStatus();
  });
  window.initLucide = initLucide;
  window.initAiStatus = initAiStatus;
}

// Modular Architecture Imports
import './components/index.js';
import './layouts/index.js';
import './utilities/index.js';
import './forms/index.js';
import './tables/index.js';
import './charts/index.js';

console.log('[CampusLynk] Lucide Icon System and UI pipeline initialized.');
