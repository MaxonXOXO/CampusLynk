<x-layouts.auth-layout title="CampusLynk - Access Restricted">
    <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-xl space-y-6 text-center">
        <!-- Error Badge -->
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 font-bold mx-auto border border-rose-200/60">
            <i data-lucide="shield-alert" class="w-6 h-6 text-rose-600"></i>
        </div>

        <div>
            <span class="px-3 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-800 border border-rose-200/60">
                HTTP 403 • Authorization Restricted
            </span>
            <h1 class="text-2xl font-bold text-slate-900 mt-3">Access Restricted</h1>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                You do not possess the required institutional authorization matrix to access this academic resource or administrative console.
            </p>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-left space-y-1.5 text-xs text-slate-600">
            <p class="font-semibold text-slate-800">Recommended Steps:</p>
            <p>1. Verify you are signed in with the authorized role (Student / Lecturer / HOD).</p>
            <p>2. Contact your institutional Superadmin for role permission elevation.</p>
        </div>

        <div class="space-y-2.5 pt-2">
            <x-ui.button variant="primary" class="w-full" onclick="window.history.back()">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                <span>Return to Previous Screen</span>
            </x-ui.button>

            <a href="/modern/login" class="block w-full text-center py-2 text-xs font-medium text-slate-600 hover:text-blue-600 hover:underline">
                Sign in with Different Account
            </a>
        </div>
    </div>
</x-layouts.auth-layout>
