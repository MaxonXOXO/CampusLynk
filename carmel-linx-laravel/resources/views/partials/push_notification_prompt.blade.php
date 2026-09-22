{{-- CampusLynk Web Push Notification Banner & Permission Handler --}}
<div id="campusPushBanner" class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-lg bg-slate-900/95 backdrop-blur-md border border-sky-500/40 rounded-2xl p-4 text-white shadow-2xl shadow-sky-950/50 hidden transition-all duration-300">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-sky-500/20 border border-sky-500/40 flex items-center justify-center text-xl text-sky-400 shrink-0">
                🔔
            </div>
            <div class="min-w-0">
                <h4 class="text-sm font-bold text-slate-100 leading-tight">Enable Instant Notifications</h4>
                <p class="text-xs text-slate-400 mt-0.5 line-clamp-2">Get instant alerts for circulars, exam schedules, and institutional announcements.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button type="button" onclick="dismissCampusPushBanner()" class="text-slate-400 hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-800 text-sm transition" title="Dismiss">
                ✕
            </button>
            <button type="button" onclick="enableCampusPushNotifications()" class="px-3.5 py-1.5 bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-sky-500/25 transition whitespace-nowrap">
                Enable 🚀
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    checkCampusPushStatus();
});

function checkCampusPushStatus() {
    if (!('Notification' in window) || !('serviceWorker' in navigator)) return;

    if (Notification.permission === 'default' && !localStorage.getItem('campuslynk_push_prompt_dismissed')) {
        setTimeout(() => {
            const banner = document.getElementById('campusPushBanner');
            if (banner) banner.classList.remove('hidden');
        }, 1500);
    } else if (Notification.permission === 'granted') {
        registerAndSubscribePush(false);
    }
}

function dismissCampusPushBanner() {
    const banner = document.getElementById('campusPushBanner');
    if (banner) banner.classList.add('hidden');
    localStorage.setItem('campuslynk_push_prompt_dismissed', 'true');
}

function enableCampusPushNotifications() {
    if (!('Notification' in window)) {
        alert('Push notifications are not supported by this browser.');
        return;
    }

    Notification.requestPermission().then(permission => {
        dismissCampusPushBanner();
        if (permission === 'granted') {
            registerAndSubscribePush(true);
        }
    });
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function registerAndSubscribePush(showSuccessNotice = false) {
    if (!('serviceWorker' in navigator)) return;

    fetch('/api/notifications/vapid-key')
        .then(res => {
            if (!res.ok) throw new Error('VAPID key unavailable');
            return res.json();
        })
        .then(keyData => {
            const vapidPublicKey = keyData.publicKey;
            if (!vapidPublicKey) return;

            return navigator.serviceWorker.register('/sw.js')
                .then(reg => {
                    return reg.pushManager.getSubscription().then(existingSub => {
                        if (existingSub) return existingSub;

                        const applicationServerKey = urlBase64ToUint8Array(vapidPublicKey);
                        return reg.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: applicationServerKey
                        });
                    });
                })
                .then(sub => {
                    if (!sub || !sub.endpoint) return;

                    let p256dh = null;
                    let auth = null;
                    if (sub.getKey) {
                        const rawP256 = sub.getKey('p256dh');
                        const rawAuth = sub.getKey('auth');
                        p256dh = rawP256 ? btoa(String.fromCharCode.apply(null, new Uint8Array(rawP256))) : null;
                        auth = rawAuth ? btoa(String.fromCharCode.apply(null, new Uint8Array(rawAuth))) : null;
                    }

                    const deviceType = /Android|iPhone|iPad|Mobile/i.test(navigator.userAgent) ? 'mobile' : 'desktop';

                    return fetch('/api/notifications/subscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            endpoint: sub.endpoint,
                            p256dh_key: p256dh,
                            auth_key: auth,
                            device_type: deviceType
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'SUCCESS' && showSuccessNotice) {
                            alert('🎉 Web Push Notifications activated for this device!');
                        }
                    });
                });
        })
        .catch(err => {
            // Silently log; does not block UI
            console.debug('Push subscription sync notice:', err.message);
        });
}
</script>
