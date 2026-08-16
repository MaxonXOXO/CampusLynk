<x-layouts.auth-layout title="CampusLynk - Reset Password">
    <div class="bg-white border border-slate-200 rounded-3xl p-8 sm:p-10 shadow-xl space-y-6">
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 text-slate-700 font-bold mb-1">
                <i data-lucide="lock-keyhole" class="w-5 h-5 text-slate-700"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Reset Your Password</h1>
            <p class="text-xs text-slate-500 font-normal">
                Please enter and confirm your new secure institutional password.
            </p>
        </div>

        <form action="/api/auth/update-password" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ request('token', 'test-token') }}">

            <div class="space-y-1.5">
                <label for="new_password" class="block text-sm font-medium text-slate-700">New Password</label>
                <div class="relative rounded-xl">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="new_password" 
                        placeholder="Enter new password..." 
                        required 
                        class="w-full min-h-[44px] pl-10 pr-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-900 placeholder:text-slate-400 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none text-sm transition-all"
                    />
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm New Password</label>
                <div class="relative rounded-xl">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        placeholder="Confirm new password..." 
                        required 
                        class="w-full min-h-[44px] pl-10 pr-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-900 placeholder:text-slate-400 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none text-sm transition-all"
                    />
                </div>
            </div>

            <!-- Security Requirements (Subtle Card) -->
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1 text-[11px] text-slate-600">
                <p class="font-semibold text-slate-700">Password requirements:</p>
                <div class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> At least 8 characters</div>
                <div class="flex items-center gap-1.5"><i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i> One uppercase letter & one number</div>
            </div>

            <div class="pt-2">
                <x-ui.button type="submit" variant="primary" class="group w-full">
                    <span>Update Password & Sign In</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2 animate-hover-arrow-right"></i>
                </x-ui.button>
            </div>
        </form>

        <div class="pt-4 border-t border-slate-100 text-center">
            <a href="/modern/login" class="text-xs font-medium text-slate-600 hover:text-blue-600 hover:underline inline-flex items-center gap-1.5">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Return to Sign In</span>
            </a>
        </div>
    </div>
</x-layouts.auth-layout>
