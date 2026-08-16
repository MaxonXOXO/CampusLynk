<x-layouts.auth-layout title="CampusLynk - Authentication Failed">
    <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-xl space-y-6 text-center">
        <!-- Error Icon -->
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 font-bold mx-auto border border-rose-200/60">
            <i data-lucide="alert-octagon" class="w-6 h-6 text-rose-600"></i>
        </div>

        <div>
            <span class="px-3 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-800 border border-rose-200/60">
                Verification Failed
            </span>
            <h1 class="text-2xl font-bold text-slate-900 mt-3">Authentication Error</h1>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                The credentials supplied could not be verified against the institutional identity directory.
            </p>
        </div>

        <div class="space-y-3 text-left">
            <x-ui.alert variant="error" title="Potential Causes:">
                <ul class="list-disc list-inside space-y-1 text-xs opacity-90 mt-1">
                    <li>Incorrect register number or faculty mobile number.</li>
                    <li>Account password was recently reset or expired.</li>
                    <li>User account is currently inactive in the department roster.</li>
                </ul>
            </x-ui.alert>
        </div>

        <div class="space-y-2.5 pt-2">
            <a href="/modern/login" class="group inline-flex items-center justify-center font-medium rounded-xl text-sm bg-blue-600 hover:bg-blue-700 text-white min-h-[44px] px-5 py-2.5 shadow-sm transition-all w-full">
                <span>Try Signing In Again</span>
                <i data-lucide="arrow-right" class="w-4 h-4 ml-2 animate-hover-arrow-right"></i>
            </a>

            <a href="/modern/forgot-password" class="block w-full text-center py-2 text-xs font-medium text-slate-600 hover:text-blue-600 hover:underline">
                Recover Account Credentials
            </a>
        </div>
    </div>
</x-layouts.auth-layout>
