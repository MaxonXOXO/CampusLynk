<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusLynk - Institutional Sign In</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FAFAFB] flex flex-col justify-between font-['Poppins'] antialiased">
    <!-- Top Header Navigation (Clean Neutral Surface) -->
    <header class="h-[70px] bg-white border-b border-slate-200 px-6 sm:px-12 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xl shadow-sm">
                C
            </div>
            <div>
                <h1 class="text-base font-bold text-slate-900 leading-tight">CampusLynk</h1>
                <p class="text-[10px] text-slate-500 font-semibold tracking-wider uppercase">AMS Platform</p>
            </div>
        </div>

        <div class="hidden sm:flex items-center gap-3">
            <span class="text-xs font-semibold text-slate-600">Carmel Polytechnic College</span>
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-medium text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">Systems Active</span>
        </div>
    </header>

    <!-- Main Content Container: Refined 2-Column Desktop Grid -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-8 lg:p-12">
        <div class="w-full max-w-5xl bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
            
            <!-- Left Info Panel (Calm, Muted Enterprise Slate) -->
            <div class="lg:col-span-5 bg-slate-900 text-white p-8 lg:p-10 flex flex-col justify-between relative">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                        <i data-lucide="graduation-cap" class="w-3.5 h-3.5 text-blue-400"></i>
                        Academic Management Suite
                    </span>

                    <h2 class="text-2xl font-bold leading-snug text-white">
                        Institutional Governance & Learning Operations.
                    </h2>

                    <p class="text-xs text-slate-400 font-normal leading-relaxed">
                        Access course files, series examinations, continuous internal evaluations (CIE), and NBA attainment metrics under the Revision 2026 standard.
                    </p>
                </div>

                <!-- Feature Highlights (Subtle Dark Cards) -->
                <div class="space-y-2.5 pt-6">
                    <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-slate-700 flex items-center justify-center text-slate-300">
                            <i data-lucide="award" class="w-3.5 h-3.5 text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-200">R2026 Grading Standard</p>
                            <p class="text-[11px] text-slate-400">Strict S, A, B, C, D, E, F calculation scale</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-800/80 border border-slate-700/80 flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-slate-700 flex items-center justify-center text-slate-300">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-200">Enterprise Security</p>
                            <p class="text-[11px] text-slate-400">Biometric & role-based authentication matrix</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Tagline -->
                <div class="pt-6 border-t border-slate-800 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>CampusLynk v2.0</span>
                    <span>Connect. Manage. Empower.</span>
                </div>
            </div>

            <!-- Right Login Form Area (Primary Visual Focus) -->
            <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">
                <div class="max-w-md w-full mx-auto space-y-6">
                    
                    <!-- Welcome Heading -->
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">Sign in to your account</h2>
                        <p class="text-sm text-slate-500 mt-1">Select your institutional role and enter your credentials.</p>
                    </div>

                    <!-- Role Switcher Tabs (Colored Active Highlight vs Non-Colored Inactive) -->
                    <div class="grid grid-cols-2 p-1.5 bg-slate-100/90 rounded-2xl border border-slate-200/60 gap-1" id="role-selector">
                        <button 
                            type="button" 
                            onclick="switchRole('student', this)" 
                            id="tab-student" 
                            class="py-2.5 px-3 text-xs font-medium rounded-xl text-slate-600 transition-all hover:text-slate-900 focus:outline-none focus:ring-0 select-none flex items-center justify-center gap-2"
                        >
                            <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                            <span>Student Portal</span>
                        </button>
                        <button 
                            type="button" 
                            onclick="switchRole('staff', this)" 
                            id="tab-staff" 
                            class="py-2.5 px-3 text-xs font-semibold rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20 transition-all focus:outline-none focus:ring-0 select-none flex items-center justify-center gap-2"
                        >
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                            <span>Faculty / Staff Desk</span>
                        </button>
                    </div>

                    <!-- Session Error Alert if present -->
                    @if(session('error'))
                        <x-ui.alert variant="error" title="Authentication Error">
                            {{ session('error') }}
                        </x-ui.alert>
                    @endif

                    <!-- Sign In Form -->
                    <form action="/login" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="user_type" id="user_type" value="staff">

                        <!-- Username Input -->
                        <div class="space-y-1.5">
                            <label for="username" class="block text-sm font-medium text-slate-700">Username / Mobile / Register No</label>
                            <div class="relative rounded-xl">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username" 
                                    placeholder="Enter your faculty mobile / username..." 
                                    required 
                                    class="w-full min-h-[44px] pl-10 pr-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-900 placeholder:text-slate-400 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none text-sm transition-all"
                                />
                            </div>
                        </div>

                        <!-- Password Input with Reveal Toggle -->
                        <div class="space-y-1.5">
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                            <div class="relative rounded-xl">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="lock" class="w-4 h-4"></i>
                                </div>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    placeholder="Enter your password..." 
                                    required 
                                    class="w-full min-h-[44px] pl-10 pr-12 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-900 placeholder:text-slate-400 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none text-sm transition-all"
                                />
                                <button 
                                    type="button" 
                                    onclick="togglePasswordVisibility()" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" 
                                    title="Toggle password visibility"
                                >
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-xs font-medium text-slate-600">Remember Me</span>
                            </label>
                            <a href="/modern/forgot-password" class="text-xs font-medium text-slate-600 hover:text-blue-600 hover:underline">
                                Forgot Password?
                            </a>
                        </div>

                        <!-- Primary CTA Button (Level 4: Strong Blue Reserved for Action) -->
                        <div class="pt-2">
                            <x-ui.button type="submit" variant="primary" class="group w-full">
                                <span>Sign In to Account</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 ml-2 animate-hover-arrow-right"></i>
                            </x-ui.button>
                        </div>
                    </form>

                    <!-- Additional Diagnostic Link -->
                    <div class="pt-4 border-t border-slate-100 text-center space-y-2">
                        <p class="text-xs text-slate-500">
                            Having trouble accessing your account? 
                            <a href="/modern/auth-error" class="font-medium text-slate-700 hover:text-blue-600 hover:underline">System Diagnostic</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Footer (Neutral Subdued) -->
    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-200/60 bg-white">
        <p>© 2026 CampusLynk AMS • Carmel Polytechnic College • All Rights Reserved</p>
    </footer>

    <!-- Interactive Script with Smooth Tab Switch -->
    <script>
        function switchRole(role, btn) {
            if (btn) btn.blur();
            const studentTab = document.getElementById('tab-student');
            const staffTab = document.getElementById('tab-staff');
            const userTypeInput = document.getElementById('user_type');
            const usernameInput = document.getElementById('username');

            const activeClasses = "py-2.5 px-3 text-xs font-semibold rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20 transition-all focus:outline-none focus:ring-0 select-none flex items-center justify-center gap-2";
            const inactiveClasses = "py-2.5 px-3 text-xs font-medium rounded-xl text-slate-600 transition-all hover:text-slate-900 focus:outline-none focus:ring-0 select-none flex items-center justify-center gap-2";

            if (role === 'student') {
                studentTab.className = activeClasses;
                staffTab.className = inactiveClasses;
                userTypeInput.value = 'student';
                usernameInput.placeholder = "Enter Student Register No (e.g., 2401001)...";
            } else {
                staffTab.className = activeClasses;
                studentTab.className = inactiveClasses;
                userTypeInput.value = 'staff';
                usernameInput.placeholder = "Enter Faculty Mobile / Username...";
            }

            if (window.initLucide) window.initLucide();
        }

        function togglePasswordVisibility() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
