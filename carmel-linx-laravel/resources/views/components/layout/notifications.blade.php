@props([
    'unreadCount' => 0
])

<div class="relative" id="notificationCenterWrapper">
    <!-- Notification Bell Button -->
    <button type="button" 
            id="notificationBellBtn" 
            onclick="toggleNotificationCenter(event)" 
            class="group w-10 h-10 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 flex items-center justify-center shadow-sm transition-all relative focus:outline-none focus:ring-2 focus:ring-blue-500" 
            title="Notification Center" 
            aria-expanded="false" 
            aria-haspopup="true">
        <i data-lucide="bell" class="w-4 h-4 text-slate-600 group-hover:text-blue-600 animate-hover-bell transition-colors"></i>
        
        <!-- Live Unread Badge Indicator -->
        <span id="topbarNotificationBadge" 
              class="hidden absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white shadow-sm transition-transform scale-100">
            0
        </span>
    </button>

    <!-- Notification Center Dropdown Panel -->
    <div id="notificationDropdown" 
         class="hidden absolute right-0 mt-2.5 w-80 sm:w-96 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-slate-200/80 z-50 overflow-hidden transform origin-top-right transition-all duration-200 animate-in fade-in slide-in-from-top-2">
        
        <!-- Dropdown Header -->
        <div class="px-4 py-3 bg-slate-50/80 border-b border-slate-200/80 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-slate-900 tracking-tight">Notifications</span>
                <span id="notificationHeaderBadge" class="hidden px-2 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700">
                    0 new
                </span>
            </div>
            <button type="button" 
                    onclick="markAllNotificationsAsRead(event)" 
                    class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors p-1 rounded hover:bg-blue-50">
                Mark all as read
            </button>
        </div>

        <!-- Optional Web Push Quick Enable Card -->
        <div id="notificationPushQuickCard" class="hidden px-4 py-2.5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 min-w-0">
                <span class="text-base shrink-0">🔔</span>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-800 leading-tight">Instant Alerts</p>
                    <p class="text-[11px] text-slate-500 truncate">Enable desktop push notices</p>
                </div>
            </div>
            <button type="button" 
                    onclick="enablePushFromCenter(event)" 
                    class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold rounded-lg shadow-sm transition shrink-0">
                Turn On
            </button>
        </div>

        <!-- Scrollable Notification Feed -->
        <div id="notificationFeedList" class="max-h-[360px] overflow-y-auto divide-y divide-slate-100 overscroll-contain">
            <!-- Loading Skeleton -->
            <div id="notificationLoadingState" class="p-6 text-center text-slate-400 text-xs">
                <div class="w-5 h-5 border-2 border-slate-300 border-t-blue-600 rounded-full animate-spin mx-auto mb-2"></div>
                Checking for alerts...
            </div>
        </div>

        <!-- Empty State -->
        <div id="notificationEmptyState" class="hidden p-8 text-center">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <i data-lucide="check-check" class="w-6 h-6 text-emerald-500"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800">You're all caught up!</h4>
            <p class="text-xs text-slate-500 mt-1 max-w-[200px] mx-auto">No pending circulars, exam notices, or birthday wishes right now.</p>
        </div>

        <!-- Dropdown Footer -->
        <div class="px-4 py-2 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
            <span class="flex items-center gap-1.5 font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                System Alerts Active
            </span>
            <span class="text-slate-400">Carmel Linx</span>
        </div>
    </div>
</div>

<script>
let campusNotifications = [];

document.addEventListener('DOMContentLoaded', function () {
    fetchNotificationFeed();
    checkPushNotificationReadiness();

    // Close notification dropdown when clicking outside
    document.addEventListener('click', function (e) {
        const wrapper = document.getElementById('notificationCenterWrapper');
        const dropdown = document.getElementById('notificationDropdown');
        if (wrapper && dropdown && !wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
            const btn = document.getElementById('notificationBellBtn');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        }
    });
});

function toggleNotificationCenter(e) {
    if (e) e.stopPropagation();
    const dropdown = document.getElementById('notificationDropdown');
    const btn = document.getElementById('notificationBellBtn');
    if (!dropdown) return;

    const isHidden = dropdown.classList.contains('hidden');
    if (isHidden) {
        dropdown.classList.remove('hidden');
        if (btn) btn.setAttribute('aria-expanded', 'true');
        if (window.lucide) window.lucide.createIcons();
    } else {
        dropdown.classList.add('hidden');
        if (btn) btn.setAttribute('aria-expanded', 'false');
    }
}

function checkPushNotificationReadiness() {
    if (!('Notification' in window)) return;
    const quickCard = document.getElementById('notificationPushQuickCard');
    if (quickCard && Notification.permission === 'default') {
        quickCard.classList.remove('hidden');
    }
}

function fetchNotificationFeed() {
    fetch('/api/notifications/feed')
        .then(res => {
            if (!res.ok) throw new Error('Feed network response was not ok');
            return res.json();
        })
        .then(data => {
            if (data.status === 'SUCCESS') {
                campusNotifications = data.notifications || [];
                updateNotificationUI(data.unread_count || 0, campusNotifications);
            }
        })
        .catch(err => {
            console.warn('Failed to load notification feed:', err);
            const loading = document.getElementById('notificationLoadingState');
            if (loading) loading.innerHTML = '<span class="text-slate-400">Up to date</span>';
        });
}

function updateNotificationUI(unreadCount, items) {
    const topbarBadge = document.getElementById('topbarNotificationBadge');
    const headerBadge = document.getElementById('notificationHeaderBadge');
    const feedList = document.getElementById('notificationFeedList');
    const emptyState = document.getElementById('notificationEmptyState');

    // Update Badges
    if (unreadCount > 0) {
        if (topbarBadge) {
            topbarBadge.textContent = unreadCount > 9 ? '9+' : unreadCount;
            topbarBadge.classList.remove('hidden');
        }
        if (headerBadge) {
            headerBadge.textContent = `${unreadCount} new`;
            headerBadge.classList.remove('hidden');
        }
    } else {
        if (topbarBadge) topbarBadge.classList.add('hidden');
        if (headerBadge) headerBadge.classList.add('hidden');
    }

    // Render Items
    if (!items || items.length === 0) {
        if (feedList) feedList.innerHTML = '';
        if (emptyState) emptyState.classList.remove('hidden');
        return;
    }

    if (emptyState) emptyState.classList.add('hidden');

    let html = '';
    items.forEach(item => {
        const isUnread = item.unread !== false;
        const iconName = item.icon || (item.type === 'birthday' ? 'cake' : (item.type === 'event' ? 'calendar' : 'bell'));
        
        let bgIcon = 'bg-blue-50 text-blue-600 border-blue-200';
        let badgeColor = 'bg-blue-100 text-blue-700';

        if (item.severity === 'warning') {
            bgIcon = 'bg-amber-50 text-amber-600 border-amber-200';
            badgeColor = 'bg-amber-100 text-amber-800';
        } else if (item.severity === 'success') {
            bgIcon = 'bg-emerald-50 text-emerald-600 border-emerald-200';
            badgeColor = 'bg-emerald-100 text-emerald-800';
        }

        html += `
            <div class="p-3.5 hover:bg-slate-50/80 transition-colors flex items-start gap-3 relative cursor-pointer ${isUnread ? 'bg-blue-50/20' : ''}" 
                 onclick="dismissSingleNotification('${item.id}', '${item.link || '#'}')">
                <div class="w-9 h-9 rounded-xl border ${bgIcon} flex items-center justify-center shrink-0 text-sm">
                    <i data-lucide="${iconName}" class="w-4 h-4"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-1 mb-0.5">
                        <h5 class="text-xs font-bold text-slate-800 truncate">${item.title}</h5>
                        <span class="text-[10px] text-slate-400 shrink-0 font-medium">${item.time || ''}</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-snug line-clamp-2">${item.message}</p>
                    ${item.badge ? `<span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[10px] font-semibold ${badgeColor}">${item.badge}</span>` : ''}
                </div>
                ${isUnread ? '<span class="w-2 h-2 rounded-full bg-blue-600 shrink-0 mt-1.5"></span>' : ''}
            </div>
        `;
    });

    if (feedList) {
        feedList.innerHTML = html;
        if (window.lucide) window.lucide.createIcons();
    }
}

function dismissSingleNotification(id, redirectUrl) {
    fetch('/api/notifications/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ id: id })
    }).finally(() => {
        campusNotifications = campusNotifications.map(n => n.id === id ? { ...n, unread: false } : n);
        const unread = campusNotifications.filter(n => n.unread).length;
        updateNotificationUI(unread, campusNotifications);
        if (redirectUrl && redirectUrl !== '#' && !redirectUrl.startsWith('javascript:')) {
            window.location.href = redirectUrl;
        }
    });
}

function markAllNotificationsAsRead(e) {
    if (e) e.stopPropagation();
    fetch('/api/notifications/mark-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ id: 'all' })
    }).then(() => {
        campusNotifications = campusNotifications.map(n => ({ ...n, unread: false }));
        updateNotificationUI(0, campusNotifications);
    });
}

function enablePushFromCenter(e) {
    if (e) e.stopPropagation();
    if (typeof enableCampusPushNotifications === 'function') {
        enableCampusPushNotifications();
    } else {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                document.getElementById('notificationPushQuickCard')?.classList.add('hidden');
                alert('Web Push Notifications enabled!');
            }
        });
    }
}
</script>
