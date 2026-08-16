<x-layouts.auth-layout title="CampusLynk - Loading Workspace">
    <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-xl space-y-6 text-center">
        <!-- Spinner -->
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-slate-100 text-slate-700 font-bold mx-auto">
            <svg class="animate-spin h-7 w-7 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div>
            <h1 class="text-xl font-bold text-slate-900">Loading Workspace</h1>
            <p class="text-xs text-slate-500 mt-1">
                Synchronizing course files, student rosters & academic calendars...
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="w-full space-y-2 pt-2">
            <x-ui.progress :percentage="68" label="System Initialization" :showValue="true" />
            <p class="text-[11px] text-slate-400">Connecting to Carmel Institutional Server</p>
        </div>
    </div>
</x-layouts.auth-layout>
