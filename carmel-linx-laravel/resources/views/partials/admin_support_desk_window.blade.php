<!-- Admin Support Desk Live Remote Window Component (Beta) -->

<!-- Header Trigger Button (Pill in Top Header) -->
<div id="adminSupportDeskBtnContainer" class="flex items-center gap-2">
  <button onclick="toggleAdminSupportDeskDrawer()" class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-blue-500/40 bg-blue-500/10 hover:bg-blue-500/20 text-blue-300 font-bold text-xs transition-premium cursor-pointer shadow-md">
    <span class="material-symbols-rounded text-base text-blue-400">desktop_windows</span>
    <span>Support Desk</span>
    <span id="adminPendingSupportBadge" class="hidden px-1.5 py-0.2 bg-rose-600 text-white rounded-full text-[10px] font-black animate-pulse">0</span>
  </button>
</div>

<!-- Admin Support Drawer / Request List Modal -->
<div id="adminSupportListModal" class="hidden fixed inset-0 z-[9999] bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 relative">
    <button onclick="toggleAdminSupportDeskDrawer()" class="absolute top-4 right-4 text-slate-400 hover:text-white cursor-pointer">
      <span class="material-symbols-rounded">close</span>
    </button>

    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-blue-400">
        <span class="material-symbols-rounded text-xl">headset_mic</span>
      </div>
      <div>
        <h3 class="text-base font-bold text-white leading-tight">Live Remote Support Desk</h3>
        <p class="text-xs text-slate-400">Dhanush.A • Dept. of Electronics</p>
      </div>
    </div>

    <div id="adminSupportQueueContainer" class="space-y-2 max-h-60 overflow-y-auto">
      <div class="text-center py-6 text-xs text-slate-400">
        <span class="material-symbols-rounded text-3xl block text-slate-600 mb-1">cell_tower</span>
        No active support requests from staff members at the moment.
      </div>
    </div>
  </div>
</div>

<!-- Admin Floating Live Video Player & Laser Control Window -->
<div id="adminLiveVideoWindow" class="hidden fixed bottom-6 right-6 z-[99999] w-[460px] max-w-[95vw] bg-slate-900 border-2 border-blue-500/60 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.8)] overflow-hidden transition-all duration-300">
  
  <!-- Window Top Bar -->
  <div class="bg-slate-950 px-4 py-2.5 border-b border-slate-800 flex items-center justify-between select-none cursor-move" id="adminVideoWindowHeader">
    <div class="flex items-center gap-2">
      <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-ping"></span>
      <span class="font-bold text-xs text-white" id="adminVideoStaffTitle">Live Stream — Staff Member</span>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="toggleVideoFullscreen()" class="text-slate-400 hover:text-white text-xs cursor-pointer" title="Toggle Fullscreen">
        <span class="material-symbols-rounded text-base">fullscreen</span>
      </button>
      <button onclick="endAdminSupportSession()" class="text-rose-400 hover:text-rose-300 text-xs cursor-pointer" title="End Session">
        <span class="material-symbols-rounded text-base">close</span>
      </button>
    </div>
  </div>

  <!-- Video Stream Canvas & Laser Pointer Trigger Area -->
  <div class="relative bg-black w-full aspect-video flex items-center justify-center overflow-hidden group cursor-crosshair" id="adminVideoWrapper" onclick="sendLaserPointerCoords(event)">
    <video id="adminRemoteVideo" autoplay playsinline class="w-full h-full object-contain"></video>

    <!-- Pointer Instruction Overlay on Hover -->
    <div class="absolute bottom-2 left-2 right-2 bg-slate-950/80 backdrop-blur-sm border border-slate-800 rounded-lg px-3 py-1.5 text-[10px] text-slate-300 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
      <span class="flex items-center gap-1">
        <span class="material-symbols-rounded text-xs text-red-400">touch_app</span> Click anywhere to place Laser Pointer on Staff Screen
      </span>
      <span class="text-blue-400 font-bold">WebRTC P2P</span>
    </div>
  </div>

  <!-- Window Bottom Toolbar -->
  <div class="p-3 bg-slate-950 border-t border-slate-800 flex items-center justify-between text-xs">
    <div class="flex items-center gap-2">
      <button onclick="sendLaserPointerCenter()" class="px-2.5 py-1 bg-red-950/80 hover:bg-red-900 border border-red-500/50 text-red-300 rounded-lg font-bold text-[11px] flex items-center gap-1 transition-premium cursor-pointer">
        <span class="material-symbols-rounded text-xs">ads_click</span> Highlight Center
      </button>
    </div>
    <button onclick="endAdminSupportSession()" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-lg transition-premium cursor-pointer shadow-md flex items-center gap-1">
      <span class="material-symbols-rounded text-xs">stop</span> Disconnect Stream
    </button>
  </div>
</div>

<script>
window.AdminSupport = {
  sessionId: null,
  peerConnection: null,
  pollInterval: null,
  signalPollInterval: null,
  lastSignalId: null
};

// Toggle Drawer
function toggleAdminSupportDeskDrawer() {
  const modal = document.getElementById('adminSupportListModal');
  modal.classList.toggle('hidden');
  if (!modal.classList.contains('hidden')) {
    fetchAdminSupportQueue();
  }
}

// Poll Active Pending Support Sessions for Admin
function startAdminSupportQueuePolling() {
  setInterval(fetchAdminSupportQueue, 3000);
}

async function fetchAdminSupportQueue() {
  try {
    const res = await fetch('/api/support/sessions');
    const data = await res.json();
    const queue = data.sessions || [];

    const badge = document.getElementById('adminPendingSupportBadge');
    const container = document.getElementById('adminSupportQueueContainer');

    const pending = queue.filter(s => s.status === 'pending');
    if (pending.length > 0) {
      badge.classList.remove('hidden');
      badge.innerText = pending.length;
    } else {
      badge.classList.add('hidden');
    }

    if (!container) return;

    if (queue.length === 0) {
      container.innerHTML = `
        <div class="text-center py-6 text-xs text-slate-400">
          <span class="material-symbols-rounded text-3xl block text-slate-600 mb-1">cell_tower</span>
          No active support requests from staff members at the moment.
        </div>`;
      return;
    }

    container.innerHTML = queue.map(sess => `
      <div class="p-3 bg-slate-950 border border-slate-800 rounded-xl flex items-center justify-between">
        <div>
          <div class="font-bold text-sm text-white">${sess.user_name}</div>
          <div class="text-xs text-slate-400">${sess.user_role} • ${sess.user_branch}</div>
        </div>
        <button onclick="acceptAdminSupportSession('${sess.session_id}', '${sess.user_name}')" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-lg transition-premium cursor-pointer shadow-md flex items-center gap-1">
          <span class="material-symbols-rounded text-sm">desktop_windows</span> Connect Stream
        </button>
      </div>
    `).join('');

  } catch (err) {
    console.error('Failed to fetch support queue:', err);
  }
}

// Admin Accepts Session & Begins WebRTC PeerConnection
async function acceptAdminSupportSession(sessionId, staffName) {
  try {
    const res = await fetch('/api/support/accept', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ session_id: sessionId })
    });

    const data = await res.json();
    if (data.status !== 'SUCCESS') {
      alert('Could not accept session: ' + data.message);
      return;
    }

    AdminSupport.sessionId = sessionId;
    document.getElementById('adminSupportListModal').classList.add('hidden');
    document.getElementById('adminLiveVideoWindow').classList.remove('hidden');
    document.getElementById('adminVideoStaffTitle').innerText = `Live Stream — ${staffName}`;

    // Initialize WebRTC PeerConnection
    const pc = new RTCPeerConnection(SupportDesk.rtcConfig);
    AdminSupport.peerConnection = pc;

    pc.ontrack = (event) => {
      const video = document.getElementById('adminRemoteVideo');
      if (video && event.streams[0]) {
        video.srcObject = event.streams[0];
      }
    };

    pc.onicecandidate = (event) => {
      if (event.candidate) {
        postSupportSignal('candidate', event.candidate, 'admin');
      }
    };

    // Notify staff that Admin has accepted the session
    postSupportSignal('admin_accepted', { accepted: true }, 'admin');

    // Start polling signals as Admin
    startSignalPolling('admin');

  } catch (err) {
    console.error('Accept session error:', err);
  }
}

// Send Laser Pointer Coordinates when Admin clicks video stream
function sendLaserPointerCoords(event) {
  const video = document.getElementById('adminRemoteVideo');
  if (!video || !AdminSupport.sessionId) return;

  const rect = video.getBoundingClientRect();
  const x = event.clientX - rect.left;
  const y = event.clientY - rect.top;

  const xPercent = (x / rect.width) * 100;
  const yPercent = (y / rect.height) * 100;

  postSupportSignal('laser_pointer', { x: xPercent, y: yPercent }, 'admin');
}

function sendLaserPointerCenter() {
  postSupportSignal('laser_pointer', { x: 50, y: 50 }, 'admin');
}

function toggleVideoFullscreen() {
  const win = document.getElementById('adminLiveVideoWindow');
  win.classList.toggle('w-[460px]');
  win.classList.toggle('w-full');
  win.classList.toggle('h-full');
  win.classList.toggle('bottom-0');
  win.classList.toggle('right-0');
}

function endAdminSupportSession() {
  if (AdminSupport.peerConnection) {
    AdminSupport.peerConnection.close();
    AdminSupport.peerConnection = null;
  }

  if (AdminSupport.sessionId) {
    fetch('/api/support/end', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ session_id: AdminSupport.sessionId })
    }).catch(() => {});
  }

  document.getElementById('adminLiveVideoWindow').classList.add('hidden');
  AdminSupport.sessionId = null;
}

// Start Polling Queue on Page Load
document.addEventListener('DOMContentLoaded', () => {
  startAdminSupportQueuePolling();
});
</script>
