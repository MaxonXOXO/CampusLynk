{{-- CampusLynk Staff Birthday Celebration Modal --}}
<div id="staffBirthdayModalOverlay" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4 transition-opacity duration-300">
    <div class="relative w-full max-w-lg bg-gradient-to-b from-slate-900 via-indigo-950/90 to-slate-900 border-2 border-amber-500/40 rounded-3xl p-6 text-white shadow-2xl shadow-amber-500/20 text-center max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeStaffBirthdayModal()" class="absolute top-4 right-4 w-9 h-9 rounded-full bg-slate-800/80 hover:bg-rose-600/80 text-slate-300 hover:text-white flex items-center justify-center text-lg transition" title="Close">
            ✕
        </button>

        <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-amber-500/15 border border-amber-500/30 rounded-full text-xs font-bold text-amber-300 uppercase tracking-widest mb-2">
            🎂 Today's Celebrations · <span id="bdayDateLabel">TODAY</span>
        </div>

        <h3 class="text-2xl font-black bg-gradient-to-r from-amber-200 via-amber-400 to-yellow-200 bg-clip-text text-transparent uppercase tracking-tight">
            Happy Birthday! 🎉
        </h3>
        <p class="text-xs text-slate-400 mt-1">Join the campus in wishing our faculty and staff colleagues!</p>

        {{-- Celebrants Grid --}}
        <div id="bdayCelebrantsList" class="flex flex-wrap items-center justify-center gap-4 my-4">
            <!-- Populated dynamically -->
        </div>

        {{-- Reaction and Wish Box --}}
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-4 text-left mt-4">
            <h5 class="text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Send Your Greeting</h5>

            <div class="flex items-center justify-center gap-2 mb-3">
                <button type="button" onclick="selectBdayEmoji('🎉')" class="bday-emoji-btn w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-lg flex items-center justify-center transition">🎉</button>
                <button type="button" onclick="selectBdayEmoji('🎂')" class="bday-emoji-btn w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-lg flex items-center justify-center transition">🎂</button>
                <button type="button" onclick="selectBdayEmoji('🎈')" class="bday-emoji-btn w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-lg flex items-center justify-center transition">🎈</button>
                <button type="button" onclick="selectBdayEmoji('🎁')" class="bday-emoji-btn w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-lg flex items-center justify-center transition">🎁</button>
                <button type="button" onclick="selectBdayEmoji('❤️')" class="bday-emoji-btn w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-lg flex items-center justify-center transition">❤️</button>
                <button type="button" onclick="selectBdayEmoji('👏')" class="bday-emoji-btn w-10 h-10 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-lg flex items-center justify-center transition">👏</button>
            </div>

            <input type="hidden" id="bdaySelectedCelebrant" value="">
            <input type="hidden" id="bdaySelectedEmoji" value="🎉">

            <div class="flex gap-2">
                <input type="text" id="bdayCustomMessage" placeholder="Write a warm wish... (e.g. Wishing you great joy and health!)" class="flex-1 bg-slate-950 border border-slate-700 rounded-xl px-3.5 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-400">
                <button type="button" onclick="submitStaffBirthdayWish()" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-400 hover:to-yellow-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-amber-500/25 transition shrink-0">
                    Send 🚀
                </button>
            </div>
        </div>

        {{-- Recent Wishes Stream --}}
        <div class="mt-4 text-left">
            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Recent Campus Wishes</h5>
            <div id="bdayWishesStream" class="space-y-2 max-h-36 overflow-y-auto pr-1">
                <!-- Populated dynamically -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initStaffBirthdayCelebration();
});

let currentBirthdayData = null;

function initStaffBirthdayCelebration() {
    fetch('/api/staff/birthdays/today')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS' && data.has_birthdays && data.celebrants.length > 0) {
                currentBirthdayData = data;
                renderStaffBirthdays(data);

                const dismissedToday = localStorage.getItem('bday_modal_dismissed_' + data.date_label);
                if (!dismissedToday && !data.has_wished) {
                    setTimeout(() => {
                        const overlay = document.getElementById('staffBirthdayModalOverlay');
                        if (overlay) overlay.classList.remove('hidden'), overlay.classList.add('flex');
                    }, 1200);
                }
            }
        })
        .catch(err => console.debug('Birthday check bypassed:', err));
}

function renderStaffBirthdays(data) {
    document.getElementById('bdayDateLabel').textContent = data.date_label || 'TODAY';
    const celebrantsList = document.getElementById('bdayCelebrantsList');
    celebrantsList.innerHTML = '';

    data.celebrants.forEach((c, idx) => {
        if (idx === 0) document.getElementById('bdaySelectedCelebrant').value = c.mobile_no;

        const card = document.createElement('div');
        card.className = 'flex flex-col items-center cursor-pointer p-2 rounded-2xl hover:bg-slate-800/50 transition';
        card.onclick = () => {
            document.getElementById('bdaySelectedCelebrant').value = c.mobile_no;
            document.querySelectorAll('#bdayCelebrantsList img').forEach(img => img.classList.remove('ring-4', 'ring-amber-400'));
            card.querySelector('img').classList.add('ring-4', 'ring-amber-400');
        };

        const activeRing = idx === 0 ? 'ring-4 ring-amber-400' : '';
        card.innerHTML = `
            <img src="${c.photo_url}" alt="${c.name}" class="w-16 h-16 rounded-2xl object-cover border-2 border-amber-400 shadow-lg ${activeRing}">
            <p class="text-xs font-bold text-amber-200 mt-2 max-w-[100px] truncate">${c.name}</p>
            <p class="text-[10px] text-slate-400 max-w-[100px] truncate">${c.designation}</p>
        `;
        celebrantsList.appendChild(card);
    });

    renderWishesStream(data.wishes);
}

function renderWishesStream(wishes) {
    const stream = document.getElementById('bdayWishesStream');
    stream.innerHTML = '';

    if (!wishes || wishes.length === 0) {
        stream.innerHTML = '<p class="text-xs text-slate-500 italic">No wishes sent yet today. Be the first to wish!</p>';
        return;
    }

    wishes.forEach(w => {
        const item = document.createElement('div');
        item.className = 'flex items-center gap-2 p-2 bg-slate-950/60 rounded-xl border border-slate-800 text-xs';
        item.innerHTML = `
            <span class="text-base">${w.emoji || '🎂'}</span>
            <div class="min-w-0 flex-1">
                <span class="font-bold text-slate-200">${w.sender_name}</span>
                ${w.message ? `<span class="text-slate-400 ml-1 truncate">"${w.message}"</span>` : ''}
            </div>
            <span class="text-[10px] text-slate-500 shrink-0">${w.time}</span>
        `;
        stream.appendChild(item);
    });
}

function selectBdayEmoji(emoji) {
    document.getElementById('bdaySelectedEmoji').value = emoji;
    document.querySelectorAll('.bday-emoji-btn').forEach(btn => {
        if (btn.textContent.trim() === emoji) {
            btn.classList.add('bg-amber-500/20', 'border-amber-400');
        } else {
            btn.classList.remove('bg-amber-500/20', 'border-amber-400');
        }
    });
}

function submitStaffBirthdayWish() {
    const celebrantMobile = document.getElementById('bdaySelectedCelebrant').value;
    const emoji = document.getElementById('bdaySelectedEmoji').value || '🎉';
    const message = document.getElementById('bdayCustomMessage').value;

    if (!celebrantMobile) return;

    fetch('/api/staff/birthdays/wish', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            celebrant_mobile_no: celebrantMobile,
            emoji: emoji,
            message: message
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'SUCCESS') {
            document.getElementById('bdayCustomMessage').value = '';
            renderWishesStream(data.wishes);
            alert('🎉 Your birthday wish was sent!');
        } else {
            alert(data.message || 'Could not send wish.');
        }
    })
    .catch(err => console.debug('Wish submission failed:', err));
}

function closeStaffBirthdayModal() {
    const overlay = document.getElementById('staffBirthdayModalOverlay');
    if (overlay) overlay.classList.add('hidden'), overlay.classList.remove('flex');
    if (currentBirthdayData && currentBirthdayData.date_label) {
        localStorage.setItem('bday_modal_dismissed_' + currentBirthdayData.date_label, 'true');
    }
}
</script>
