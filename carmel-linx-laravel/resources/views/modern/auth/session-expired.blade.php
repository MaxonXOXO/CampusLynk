<x-layouts.auth-layout title="CampusLynk - Session Expired">
    <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-xl space-y-6 text-center">
        <!-- Icon -->
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-50 text-amber-700 font-bold mx-auto border border-amber-200/60">
            <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
        </div>

        <div>
            <span class="px-3 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200/60">
                Security Timeout
            </span>
            <h1 class="text-2xl font-bold text-slate-900 mt-3">Session Expired</h1>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                For institutional security and exam integrity standards, your active session has automatically timed out due to inactivity.
            </p>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-left space-y-1 text-xs text-slate-600">
            <p class="font-semibold text-slate-800">Security Checkpoint:</p>
            <p>Your unsaved draft work was preserved locally. Please sign back in to continue where you left off.</p>
        </div>

        <div class="pt-2">
            <a href="/modern/login" class="group inline-flex items-center justify-center font-medium rounded-xl text-sm bg-blue-600 hover:bg-blue-700 text-white min-h-[44px] px-5 py-2.5 shadow-sm transition-all w-full">
                <span>Sign Back In</span>
                <i data-lucide="arrow-right" class="w-4 h-4 ml-2 animate-hover-arrow-right"></i>
            </a>
        </div>
    </div>
</x-layouts.auth-layout>
