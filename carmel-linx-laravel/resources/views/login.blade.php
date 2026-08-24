<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusLynk - Institutional Sign In &amp; Registration</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">
    <!-- Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FAFAFB] flex flex-col justify-between font-['Poppins'] antialiased text-slate-900">

    <!-- Top Header Navigation -->
    <header class="h-[70px] bg-white border-b border-slate-200 px-6 sm:px-12 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-3">
            <img src="{{ asset('logo.svg') }}" alt="CampusLynk Logo" class="w-10 h-10 object-contain rounded-xl shadow-xs border border-slate-100" />
            <div>
                <h1 class="text-base font-bold text-slate-900 leading-tight">CampusLynk</h1>
                <p class="text-xs text-slate-500 font-semibold tracking-wider uppercase">Academic Management Suite</p>
            </div>
        </div>

        <div class="hidden sm:flex items-center gap-3">
            <span class="text-xs font-semibold text-slate-600">Carmel Polytechnic College</span>
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-medium text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200/60">Systems Active</span>
        </div>
    </header>

    <!-- Main Content Container: 2-Column Desktop Card -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-8 lg:p-12">
        <div class="w-full max-w-5xl bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
            
            <!-- Left Info Panel (Ferrofluid Video Background) -->
            <div class="lg:col-span-5 text-white relative overflow-hidden flex flex-col justify-between min-h-[360px]">
                
                <!-- Video Background -->
                <video 
                    autoplay 
                    loop 
                    muted 
                    playsinline
                    class="absolute inset-0 w-full h-full object-cover"
                    poster=""
                >
                    <source src="{{ asset('ferrofluid.webm') }}" type="video/webm">
                </video>

                <!-- Subtle Dark Overlay -->
                <div class="absolute inset-0 bg-black/35"></div>

                <!-- CampusLynk GIF Overlay (screen blend removes blacks) -->
                <div class="absolute inset-0 z-10 flex items-center justify-center pointer-events-none">
                    <img src="{{ asset('campuslynk.gif') }}" alt="CampusLynk" class="w-[70%] max-w-[280px] object-contain drop-shadow-2xl" style="mix-blend-mode: screen;" />
                </div>

                <!-- Top Badge & Bottom Tagline (above everything) -->
                <div class="relative z-20 p-8 lg:p-12 flex flex-col justify-between h-full">
                    <!-- Top Minimal Badge -->
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-white/10 backdrop-blur-md text-white/90 border border-white/20">
                            <i data-lucide="shield" class="w-3.5 h-3.5 text-blue-300"></i>
                            Institutional AMS
                        </span>
                        <span class="text-xs font-semibold text-white/60">v2.0</span>
                    </div>

                    <!-- Spacer -->
                    <div></div>

                    <!-- Footer Tagline -->
                    <div class="pt-6 border-t border-white/15 text-xs text-white/50 flex items-center justify-between">
                        <span>CampusLynk AMS</span>
                        <span>Connect. Manage. Empower.</span>
                    </div>
                </div>
            </div>

            <!-- Right Form Area: Interactive Screens (Sign In / Register Student / Register Staff) -->
            <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">
                <div class="max-w-md w-full mx-auto space-y-6">

                    <!-- Dynamic Alert Message Box -->
                    <div id="authAlert" class="hidden p-4 rounded-xl text-sm font-medium border"></div>

                    <!-- ========================================================================= -->
                    <!-- SCREEN 1: SIGN IN FORM                                                    -->
                    <!-- ========================================================================= -->
                    <div id="screen-signin" class="space-y-6">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Sign in to your account</h2>
                            <p class="text-sm text-slate-500 mt-1">Select your institutional role and enter your credentials.</p>
                        </div>

                        <!-- Role Switcher Tabs for Login (Student vs Faculty/Staff) -->
                        <div class="grid grid-cols-2 p-1.5 bg-slate-100/90 rounded-2xl border border-slate-200/60 gap-1" id="role-selector">
                            <button 
                                type="button" 
                                onclick="switchRole('student')" 
                                id="tab-student" 
                                class="py-2.5 px-3 text-xs font-medium rounded-xl text-slate-600 transition-all hover:text-slate-900 focus:outline-none select-none flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                                <span>Student Portal</span>
                            </button>
                            <button 
                                type="button" 
                                onclick="switchRole('staff')" 
                                id="tab-staff" 
                                class="py-2.5 px-3 text-xs font-semibold rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20 transition-all focus:outline-none select-none flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <i data-lucide="briefcase" class="w-4 h-4"></i>
                                <span>Faculty / Staff Desk</span>
                            </button>
                        </div>

                        <!-- Sign In Form (AJAX Handled) -->
                        <form onsubmit="handleLoginSubmit(event)" class="space-y-4">
                            <!-- Username / Register No Input -->
                            <div class="space-y-1.5">
                                <label for="usernameInput" id="usernameLabel" class="block text-sm font-medium text-slate-700">Faculty Mobile / Username</label>
                                <div class="relative rounded-xl">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        id="usernameInput" 
                                        placeholder="Enter your registered mobile or ID..." 
                                        required 
                                        class="w-full min-h-[44px] pl-10 pr-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-900 placeholder:text-slate-400 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none text-sm transition-all"
                                    />
                                </div>
                            </div>

                            <!-- Password Input with Reveal Toggle -->
                            <div class="space-y-1.5">
                                <label for="passwordInput" class="block text-sm font-medium text-slate-700">Password</label>
                                <div class="relative rounded-xl">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i data-lucide="lock" class="w-4 h-4"></i>
                                    </div>
                                    <input 
                                        type="password" 
                                        id="passwordInput" 
                                        placeholder="Enter your password..." 
                                        required 
                                        class="w-full min-h-[44px] pl-10 pr-12 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-900 placeholder:text-slate-400 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none text-sm transition-all"
                                    />
                                    <button 
                                        type="button" 
                                        onclick="togglePasswordVisibility('passwordInput')" 
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer" 
                                        title="Toggle password visibility"
                                    >
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between pt-1">
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" id="rememberMe" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-xs font-medium text-slate-600">Remember Me</span>
                                </label>
                                <a href="/modern/forgot-password" class="text-xs font-medium text-slate-600 hover:text-blue-600 hover:underline">
                                    Forgot Password?
                                </a>
                            </div>

                            <!-- Primary Sign In CTA Button -->
                            <div class="pt-2">
                                <button 
                                    type="submit" 
                                    id="loginSubmitBtn"
                                    class="group w-full inline-flex items-center justify-center font-medium rounded-xl text-sm bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white min-h-[44px] px-5 py-2.5 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer"
                                >
                                    <span id="loginBtnText">Sign In to Account</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2 animate-hover-arrow-right"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Switch to Registration Footer -->
                        <div class="pt-4 border-t border-slate-100 text-center space-y-2 text-xs text-slate-500">
                            <p>
                                Need an account? 
                                <button type="button" onclick="switchAuthMode('student_reg')" class="font-semibold text-blue-600 hover:underline cursor-pointer">Register as Student</button>
                                <span class="mx-1.5 text-slate-300">•</span>
                                <button type="button" onclick="switchAuthMode('staff_reg')" class="font-semibold text-blue-600 hover:underline cursor-pointer">Register as Staff</button>
                            </p>
                        </div>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- SCREEN 2: REGISTER AS STUDENT FORM                                        -->
                    <!-- ========================================================================= -->
                    <div id="screen-student-reg" class="hidden space-y-5">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">Student Onboarding</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 mt-1">Student Registration</h2>
                            <p class="text-sm text-slate-500">Create your student portal account for academic tracking.</p>
                        </div>

                        <form onsubmit="handleStudentRegisterSubmit(event)" class="space-y-3.5">
                            <!-- Row 1: Admission No & Full Name -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Admission Number <span class="text-rose-500">*</span></label>
                                    <input type="text" id="studAdmNo" required placeholder="e.g. 240101" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                                    <input type="text" id="studName" required placeholder="Full Name as per records" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                            </div>

                            <!-- Row 2: Email & Phone -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                                    <input type="email" id="studEmail" required placeholder="student@example.com" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Phone Number</label>
                                    <input type="tel" id="studPhone" placeholder="10-digit mobile" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                            </div>

                            <!-- Row 3: Department & Admission Year -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Branch / Department <span class="text-rose-500">*</span></label>
                                    <select id="studBranch" required class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer">
                                        <option value="CT">Computer Engineering (CT)</option>
                                        <option value="EL">Electronics Engineering (EL)</option>
                                        <option value="ME">Mechanical Engineering (ME)</option>
                                        <option value="CE">Civil Engineering (CE)</option>
                                        <option value="EEE">Electrical &amp; Electronics (EEE)</option>
                                        <option value="AU">Automobile Engineering (AU)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Admission Year <span class="text-rose-500">*</span></label>
                                    <select id="studAdmYear" required class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer">
                                        <option value="2026">2026</option>
                                        <option value="2025">2025</option>
                                        <option value="2024">2024</option>
                                        <option value="2023">2023</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 4: Admission Type & Semester -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Admission Type <span class="text-rose-500">*</span></label>
                                    <select id="studAdmType" required class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer">
                                        <option value="Regular">Regular</option>
                                        <option value="LET">Lateral Entry (LET)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Current Semester <span class="text-rose-500">*</span></label>
                                    <select id="studSemester" required class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer">
                                        <option value="S1">Semester 1 (S1)</option>
                                        <option value="S2">Semester 2 (S2)</option>
                                        <option value="S3">Semester 3 (S3)</option>
                                        <option value="S4">Semester 4 (S4)</option>
                                        <option value="S5">Semester 5 (S5)</option>
                                        <option value="S6">Semester 6 (S6)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 5: SBTE Reg No (Optional) -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">SBTE Register Number <span class="text-slate-400 font-normal">(Optional)</span></label>
                                <input type="text" id="studSbteRegNo" placeholder="Official State Board Reg Number if issued" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                            </div>

                            <!-- Row 6: Password -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                                    <input type="password" id="studPassword" required placeholder="Choose password" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password <span class="text-rose-500">*</span></label>
                                    <input type="password" id="studPasswordConfirm" required placeholder="Repeat password" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                            </div>

                            <!-- Submit Student Reg Button -->
                            <div class="pt-2">
                                <button 
                                    type="submit" 
                                    id="btnStudentRegSubmit"
                                    class="w-full inline-flex items-center justify-center font-medium rounded-xl text-sm bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white min-h-[44px] px-5 py-2.5 shadow-sm transition-all focus:outline-none cursor-pointer"
                                >
                                    <span>Complete Student Registration</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                                </button>
                            </div>
                        </form>

                        <div class="pt-2 text-center text-xs text-slate-500">
                            Already registered? 
                            <button type="button" onclick="switchAuthMode('signin')" class="font-semibold text-blue-600 hover:underline cursor-pointer">Back to Sign In</button>
                            <span class="mx-1.5 text-slate-300">•</span>
                            <button type="button" onclick="switchAuthMode('staff_reg')" class="font-semibold text-blue-600 hover:underline cursor-pointer">Register as Staff</button>
                        </div>
                    </div>

                    <!-- ========================================================================= -->
                    <!-- SCREEN 3: REGISTER AS STAFF FORM                                          -->
                    <!-- ========================================================================= -->
                    <div id="screen-staff-reg" class="hidden space-y-5">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/60">Faculty &amp; Staff Onboarding</span>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 mt-1">Faculty / Staff Registration</h2>
                            <p class="text-sm text-slate-500">Create your institutional staff desk account.</p>
                        </div>

                        <form onsubmit="handleStaffRegisterSubmit(event)" class="space-y-3.5">
                            <!-- Row 1: Mobile & Full Name -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Mobile Number (Login ID) <span class="text-rose-500">*</span></label>
                                    <input type="tel" id="staffMobile" required placeholder="10-digit mobile number" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                                    <input type="text" id="staffName" required placeholder="e.g. Dr. John Doe" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                            </div>

                            <!-- Row 2: Email & Department -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Institutional Email <span class="text-rose-500">*</span></label>
                                    <input type="email" id="staffEmail" required placeholder="staff@carmelpoly.ac.in" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Department / Branch <span class="text-rose-500">*</span></label>
                                    <select id="staffBranch" required class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer">
                                        <option value="CT">Computer Engineering (CT)</option>
                                        <option value="EL">Electronics Engineering (EL)</option>
                                        <option value="ME">Mechanical Engineering (ME)</option>
                                        <option value="CE">Civil Engineering (CE)</option>
                                        <option value="EEE">Electrical &amp; Electronics (EEE)</option>
                                        <option value="AU">Automobile Engineering (AU)</option>
                                        <option value="GEN_AIDED">General Department (Aided)</option>
                                        <option value="GEN_SF">General Department (Self Finance)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 3: Designation -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Designation / Role <span class="text-rose-500">*</span></label>
                                <select id="staffDesignation" required class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer">
                                    <option value="Lecturer">Lecturer / Faculty</option>
                                    <option value="Tutor">Class Tutor</option>
                                    <option value="Demonstrator">Demonstrator</option>
                                    <option value="Trade_Instructor">Trade Instructor</option>
                                    <option value="Workshop_Superintendent">Workshop Superintendent</option>
                                    <option value="HOD">Head of Department (HOD)</option>
                                    <option value="Academic_Coordinator">Academic Coordinator</option>
                                    <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator (Aided)</option>
                                    <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator (SF)</option>
                                    <option value="Principal">Principal</option>
                                </select>
                            </div>

                            <!-- Row 4: Password -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                                    <input type="password" id="staffPassword" required placeholder="Choose password" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Confirm Password <span class="text-rose-500">*</span></label>
                                    <input type="password" id="staffPasswordConfirm" required placeholder="Repeat password" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                </div>
                            </div>

                            <!-- Submit Staff Reg Button -->
                            <div class="pt-2">
                                <button 
                                    type="submit" 
                                    id="btnStaffRegSubmit"
                                    class="w-full inline-flex items-center justify-center font-medium rounded-xl text-sm bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white min-h-[44px] px-5 py-2.5 shadow-sm transition-all focus:outline-none cursor-pointer"
                                >
                                    <span>Submit Staff Registration</span>
                                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                                </button>
                            </div>
                        </form>

                        <div class="pt-2 text-center text-xs text-slate-500">
                            Already have an account? 
                            <button type="button" onclick="switchAuthMode('signin')" class="font-semibold text-blue-600 hover:underline cursor-pointer">Back to Sign In</button>
                            <span class="mx-1.5 text-slate-300">•</span>
                            <button type="button" onclick="switchAuthMode('student_reg')" class="font-semibold text-blue-600 hover:underline cursor-pointer">Register as Student</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Footer -->
    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-200/60 bg-white">
        <p>© 2026 CampusLynk AMS • Carmel Polytechnic College • All Rights Reserved</p>
    </footer>

    <!-- Interactive Script with Authentication & Registration Logic -->
    <script>
        let currentRole = 'staff';
        let currentAuthMode = 'signin';

        function switchAuthMode(mode) {
            currentAuthMode = mode;
            const alertEl = document.getElementById('authAlert');
            if (alertEl) alertEl.classList.add('hidden');

            const screenSignIn = document.getElementById('screen-signin');
            const screenStudentReg = document.getElementById('screen-student-reg');
            const screenStaffReg = document.getElementById('screen-staff-reg');

            screenSignIn.classList.toggle('hidden', mode !== 'signin');
            screenStudentReg.classList.toggle('hidden', mode !== 'student_reg');
            screenStaffReg.classList.toggle('hidden', mode !== 'staff_reg');

            if (window.lucide) window.lucide.createIcons();
        }

        function switchRole(role) {
            currentRole = role;
            const studentTab = document.getElementById('tab-student');
            const staffTab = document.getElementById('tab-staff');
            const usernameInput = document.getElementById('usernameInput');
            const usernameLabel = document.getElementById('usernameLabel');

            const activeClasses = "py-2.5 px-3 text-xs font-semibold rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20 transition-all focus:outline-none select-none flex items-center justify-center gap-2 cursor-pointer";
            const inactiveClasses = "py-2.5 px-3 text-xs font-medium rounded-xl text-slate-600 transition-all hover:text-slate-900 focus:outline-none select-none flex items-center justify-center gap-2 cursor-pointer";

            if (role === 'student') {
                studentTab.className = activeClasses;
                staffTab.className = inactiveClasses;
                usernameLabel.innerText = "Student Register Number";
                usernameInput.placeholder = "Enter Student Register No (e.g., 2401001)...";
            } else {
                staffTab.className = activeClasses;
                studentTab.className = inactiveClasses;
                usernameLabel.innerText = "Faculty Mobile / Username";
                usernameInput.placeholder = "Enter Faculty Mobile / Username...";
            }

            if (window.lucide) window.lucide.createIcons();
        }

        function togglePasswordVisibility(id) {
            const input = document.getElementById(id);
            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
            }
        }

        function getCsrfToken() {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            return tokenMeta ? tokenMeta.getAttribute('content') : '';
        }

        function showAlert(msg, isSuccess) {
            const alertEl = document.getElementById('authAlert');
            if (!alertEl) return;
            alertEl.className = isSuccess 
                ? "p-3.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/80 block"
                : "p-3.5 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200/80 block";
            alertEl.innerText = msg;
            alertEl.classList.remove('hidden');
        }

        // =========================================================================
        // LOGIN SUBMISSION HANDLER
        // =========================================================================
        function handleLoginSubmit(e) {
            e.preventDefault();

            const alertEl = document.getElementById('authAlert');
            const btnText = document.getElementById('loginBtnText');
            const submitBtn = document.getElementById('loginSubmitBtn');
            const username = document.getElementById('usernameInput').value.trim();
            const password = document.getElementById('passwordInput').value.trim();

            if (!username || !password) {
                showAlert("Please fill in both your registered ID and password.", false);
                return;
            }

            if (alertEl) alertEl.classList.add('hidden');
            btnText.innerText = "Verifying Credentials...";
            submitBtn.disabled = true;

            fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({
                    userId: username,
                    password: password,
                    roleType: currentRole
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    showAlert("Access verified! Redirecting to your dashboard...", true);
                    setTimeout(() => {
                        window.location.href = data.route;
                    }, 500);
                } else {
                    submitBtn.disabled = false;
                    btnText.innerText = "Sign In to Account";
                    showAlert(data.message || "Invalid credentials. Please verify your login details.", false);
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                btnText.innerText = "Sign In to Account";
                showAlert("Unable to connect to institutional server. Please check network.", false);
            });
        }

        // =========================================================================
        // STUDENT REGISTRATION HANDLER
        // =========================================================================
        function handleStudentRegisterSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('btnStudentRegSubmit');
            const admNo = document.getElementById('studAdmNo').value.trim();
            const name = document.getElementById('studName').value.trim();
            const email = document.getElementById('studEmail').value.trim();
            const phone = document.getElementById('studPhone').value.trim();
            const branch = document.getElementById('studBranch').value;
            const admissionYear = document.getElementById('studAdmYear').value;
            const admissionType = document.getElementById('studAdmType').value;
            const semester = document.getElementById('studSemester').value;
            const sbteRegNo = document.getElementById('studSbteRegNo').value.trim();
            const password = document.getElementById('studPassword').value;
            const passwordConfirm = document.getElementById('studPasswordConfirm').value;

            if (password !== passwordConfirm) {
                showAlert("Passwords do not match. Please verify and re-enter.", false);
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span>Registering Student...</span>';

            fetch('/register/student', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({
                    admNo: admNo,
                    name: name,
                    email: email,
                    phone: phone,
                    branch: branch,
                    admissionYear: parseInt(admissionYear),
                    admissionType: admissionType,
                    semester: semester,
                    sbteRegNo: sbteRegNo || null,
                    password: password
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    showAlert(data.message || "Student registration successful! Generated Register No: " + (data.regNo || ''), true);
                    setTimeout(() => {
                        switchAuthMode('signin');
                        switchRole('student');
                        document.getElementById('usernameInput').value = data.regNo || admNo;
                    }, 2000);
                } else {
                    showAlert(data.message || "Registration failed. Please check your inputs.", false);
                }
            })
            .catch(err => {
                showAlert("Server error during registration. Please try again.", false);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<span>Complete Student Registration</span><i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>';
                if (window.lucide) window.lucide.createIcons();
            });
        }

        // =========================================================================
        // STAFF REGISTRATION HANDLER
        // =========================================================================
        function handleStaffRegisterSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('btnStaffRegSubmit');
            const mobileNo = document.getElementById('staffMobile').value.trim();
            const name = document.getElementById('staffName').value.trim();
            const email = document.getElementById('staffEmail').value.trim();
            const branch = document.getElementById('staffBranch').value;
            const designation = document.getElementById('staffDesignation').value;
            const password = document.getElementById('staffPassword').value;
            const passwordConfirm = document.getElementById('staffPasswordConfirm').value;

            if (password !== passwordConfirm) {
                showAlert("Passwords do not match. Please verify and re-enter.", false);
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span>Submitting Staff Registration...</span>';

            fetch('/register/staff', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({
                    mobileNo: mobileNo,
                    name: name,
                    email: email,
                    branch: branch,
                    designation: designation,
                    password: password
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    showAlert(data.message || "Staff registration submitted successfully!", true);
                    setTimeout(() => {
                        switchAuthMode('signin');
                        switchRole('staff');
                        document.getElementById('usernameInput').value = mobileNo;
                    }, 2000);
                } else {
                    showAlert(data.message || "Staff registration failed. Please check your inputs.", false);
                }
            })
            .catch(err => {
                showAlert("Server error during registration. Please try again.", false);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<span>Submit Staff Registration</span><i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>';
                if (window.lucide) window.lucide.createIcons();
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) window.lucide.createIcons();
        });
    </script>
</body>
</html>
