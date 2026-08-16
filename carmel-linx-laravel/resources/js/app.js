import './bootstrap';
import '../css/app.css';
import { createIcons, icons } from 'lucide';

// Initialize Lucide Icons globally
export function initLucide() {
  createIcons({ icons });
}

if (typeof window !== 'undefined') {
  document.addEventListener('DOMContentLoaded', () => {
    initLucide();
  });
  window.initLucide = initLucide;
}

// Modular Architecture Imports
import './components/index.js';
import './layouts/index.js';
import './utilities/index.js';
import './forms/index.js';
import './tables/index.js';
import './charts/index.js';

console.log('[CampusLynk] Lucide Icon System and UI pipeline initialized.');
