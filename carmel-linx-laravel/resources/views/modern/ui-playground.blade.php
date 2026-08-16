<x-layouts.app-shell title="CampusLynk - UI Component Playground" activeNav="dashboard">
    <div class="space-y-10 max-w-6xl mx-auto">
        
        <!-- Header Banner with calm color hierarchy -->
        <div class="border-b border-slate-200 pb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                    Design System v1.0.0 • Balanced Color Hierarchy
                </div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Component Verification Playground</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Official state, accessibility, and micro-interaction verification suite calibrated for 70/15/10/5 color balance.
                </p>
            </div>

            <!-- Quick Action Links -->
            <div class="flex items-center gap-3">
                <a href="/modern/login" class="group inline-flex items-center justify-center font-medium rounded-xl text-sm bg-blue-600 hover:bg-blue-700 text-white min-h-[44px] px-5 py-2.5 shadow-sm transition-all">
                    <span>Modern Login</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2 animate-hover-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- 1. MICRO-INTERACTIONS & ANIMATED ICONS (itsHover Standards) -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-5 h-5 text-slate-700"></i>
                    <span>1. itsHover Animated Micro-Interactions</span>
                </h2>
                <span class="text-xs font-medium text-slate-400">Pure CSS • Token-based keyframes</span>
            </div>

            <x-ui.card>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    
                    <!-- 1. Plug Connected Interaction -->
                    <div class="group p-5 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-2xl transition-all cursor-pointer">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                                <i data-lucide="unplug" class="w-4 h-4 animate-hover-plug text-slate-700"></i>
                            </div>
                            <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200/60">Connected</span>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900">Biometric Geofence</h3>
                        <p class="text-xs text-slate-500 mt-1">Hover to trigger plug docking animation.</p>
                    </div>

                    <!-- 2. Bell Ringing Alert -->
                    <div class="group p-5 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-2xl transition-all cursor-pointer">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                                <i data-lucide="bell" class="w-4 h-4 animate-hover-bell text-slate-700"></i>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900">Notice Broadcast</h3>
                        <p class="text-xs text-slate-500 mt-1">Hover to trigger bell swinging micro-action.</p>
                    </div>

                    <!-- 3. Download Slide Indicator -->
                    <div class="group p-5 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-2xl transition-all cursor-pointer">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                                <i data-lucide="download" class="w-4 h-4 animate-hover-download text-slate-700"></i>
                            </div>
                            <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 border border-slate-200">PDF / A4</span>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900">CIE Marksheet Export</h3>
                        <p class="text-xs text-slate-500 mt-1">Hover to preview vertical arrow bounce.</p>
                    </div>

                    <!-- 4. Security Shield Pulse -->
                    <div class="group p-5 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-300 rounded-2xl transition-all cursor-pointer">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                                <i data-lucide="shield-check" class="w-4 h-4 animate-hover-shield text-slate-700"></i>
                            </div>
                            <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200/60">Verified</span>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900">R2026 Audit Trail</h3>
                        <p class="text-xs text-slate-500 mt-1">Hover to preview security shield pulse.</p>
                    </div>

                </div>
            </x-ui.card>
        </section>

        <!-- 2. BUTTONS (Button.v1 - Action vs Neutral Hierarchy) -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="mouse-pointer-click" class="w-5 h-5 text-slate-700"></i>
                    <span>2. Buttons</span>
                    <span class="text-xs font-normal text-slate-500">(&lt;x-ui.button /&gt;)</span>
                </h2>
                <span class="text-xs font-medium text-slate-400">Level 4 CTA vs Level 1 Neutral</span>
            </div>

            <x-ui.card>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <!-- Primary Buttons (Level 4: 5% Dominance) -->
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Primary Actions (5%)</p>
                        <div class="flex flex-col gap-2.5">
                            <x-ui.button variant="primary">
                                Default Primary
                            </x-ui.button>
                            
                            <x-ui.button variant="primary" class="group">
                                <i data-lucide="download" class="w-4 h-4 mr-2 animate-hover-download"></i>
                                Download Course File
                            </x-ui.button>
                            
                            <x-ui.button variant="primary" :loading="true">
                                Generating QP...
                            </x-ui.button>
                            
                            <x-ui.button variant="primary" :disabled="true">
                                <i data-lucide="lock" class="w-4 h-4 mr-2"></i>
                                Locked State
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Secondary Buttons (Level 1: Neutral Calm Surface) -->
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Secondary Actions (Neutral)</p>
                        <div class="flex flex-col gap-2.5">
                            <x-ui.button variant="secondary">
                                Secondary Button
                            </x-ui.button>
                            
                            <x-ui.button variant="secondary" class="group">
                                <i data-lucide="printer" class="w-4 h-4 mr-2 text-slate-500 group-hover:text-slate-900 transition-colors"></i>
                                Print Marksheet
                            </x-ui.button>
                            
                            <x-ui.button variant="secondary" :disabled="true">
                                Disabled Secondary
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Tertiary & Destructive -->
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tertiary & Destructive</p>
                        <div class="flex flex-col gap-2.5">
                            <x-ui.button variant="tertiary" class="group">
                                <i data-lucide="eye" class="w-4 h-4 mr-2 text-slate-500 group-hover:text-blue-600 transition-colors"></i>
                                View Dossier
                            </x-ui.button>
                            
                            <x-ui.button variant="danger" class="group">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                Revoke Notice
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Icon Action Squares (44×44px Neutral Elevation) -->
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Square Actions (44×44px)</p>
                        <div class="flex flex-wrap gap-2.5">
                            <x-ui.button variant="icon" title="Add Student">
                                <i data-lucide="plus" class="w-4 h-4 text-slate-600"></i>
                            </x-ui.button>
                            
                            <x-ui.button variant="icon" title="Filter Roster">
                                <i data-lucide="sliders-horizontal" class="w-4 h-4 text-slate-600"></i>
                            </x-ui.button>
                            
                            <x-ui.button variant="icon" title="Refresh Feed" class="group">
                                <i data-lucide="refresh-cw" class="w-4 h-4 text-slate-600 animate-hover-refresh"></i>
                            </x-ui.button>
                            
                            <x-ui.button variant="icon" title="Configure Settings" class="group">
                                <i data-lucide="settings" class="w-4 h-4 text-slate-600 animate-hover-spin"></i>
                            </x-ui.button>
                        </div>
                    </div>

                </div>
            </x-ui.card>
        </section>

        <!-- 3. INPUT FIELDS & SELECT CONTROLS -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="form-input" class="w-5 h-5 text-slate-700"></i>
                    <span>3. Form Controls</span>
                    <span class="text-xs font-normal text-slate-500">(&lt;x-ui.input /&gt;, &lt;x-ui.select /&gt;)</span>
                </h2>
                <span class="text-xs font-medium text-slate-400">Soft Focus Rings • 14px font</span>
            </div>

            <x-ui.card>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <x-ui.input 
                        label="Student Registration Number" 
                        placeholder="e.g. 2401001" 
                    />

                    <!-- Input with Prefix Icon -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Faculty Identity (Email / Phone)</label>
                        <div class="relative rounded-xl">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <input 
                                type="text" 
                                class="w-full min-h-[44px] pl-10 pr-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-900 placeholder:text-slate-400 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none text-sm transition-all"
                                value="gautham.d@carmel.edu.in"
                            />
                        </div>
                    </div>

                    <!-- Password with Toggle Reveal -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Institutional Password</label>
                        <div class="relative rounded-xl">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <input 
                                type="password" 
                                id="demo-password"
                                class="w-full min-h-[44px] pl-10 pr-11 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-900 placeholder:text-slate-400 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none text-sm transition-all"
                                value="SecurePassword@2026"
                            />
                            <button 
                                type="button" 
                                onclick="const p = document.getElementById('demo-password'); p.type = p.type === 'password' ? 'text' : 'password';"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600"
                                title="Toggle visibility"
                            >
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Validation Error State -->
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Course Code Validation</label>
                        <div class="relative rounded-xl">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-rose-500">
                                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            </div>
                            <input 
                                type="text" 
                                class="w-full min-h-[44px] pl-10 pr-4 py-2.5 bg-white border border-rose-400 text-slate-900 rounded-xl focus:border-rose-500 focus:ring-2 focus:ring-rose-500/10 outline-none text-sm transition-all"
                                value="INVALID-CODE"
                            />
                        </div>
                        <p class="text-xs text-rose-600 font-medium">Must follow Revision 2026 scheme format (e.g. 26CS101).</p>
                    </div>

                    <x-ui.select 
                        label="Department Selection" 
                        name="department" 
                        value="cse" 
                        :options="[
                            '' => 'Choose Academic Department',
                            'cse' => 'Computer Science & Engineering',
                            'ece' => 'Electronics & Communication',
                            'mech' => 'Mechanical Engineering',
                            'civil' => 'Civil Engineering'
                        ]" 
                    />

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Global Search Input</label>
                        <x-ui.search placeholder="Search students, faculty, subjects..." />
                    </div>

                </div>
            </x-ui.card>
        </section>

        <!-- 4. BADGES & FILTER CHIPS -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="tags" class="w-5 h-5 text-slate-700"></i>
                    <span>4. Badges & Chips</span>
                    <span class="text-xs font-normal text-slate-500">(&lt;x-ui.badge /&gt;, &lt;x-ui.chip /&gt;)</span>
                </h2>
            </div>

            <x-ui.card>
                <div class="space-y-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">Refined Subtle Semantic Badges</p>
                        <div class="flex flex-wrap gap-3">
                            <x-ui.badge variant="active">Active</x-ui.badge>
                            <x-ui.badge variant="pending">Pending Approval</x-ui.badge>
                            <x-ui.badge variant="completed">Attainment Met</x-ui.badge>
                            <x-ui.badge variant="on_hold">On Hold</x-ui.badge>
                            <x-ui.badge variant="cancelled">Revoked</x-ui.badge>
                            <x-ui.badge variant="draft">Draft Scheme</x-ui.badge>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">Interactive Filter Chips</p>
                        <div class="flex flex-wrap gap-2.5">
                            <x-ui.chip>
                                <i data-lucide="book" class="w-3.5 h-3.5 text-slate-400 mr-1.5 inline"></i>
                                B.Tech CSE
                            </x-ui.chip>
                            <x-ui.chip>
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400 mr-1.5 inline"></i>
                                Semester 6
                            </x-ui.chip>
                            <x-ui.chip>
                                <i data-lucide="home" class="w-3.5 h-3.5 text-slate-400 mr-1.5 inline"></i>
                                Hostel Resident
                            </x-ui.chip>
                            <x-ui.chip>
                                <i data-lucide="users" class="w-3.5 h-3.5 text-slate-400 mr-1.5 inline"></i>
                                2024-2028 Batch
                            </x-ui.chip>
                            <button type="button" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors shadow-sm">
                                <i data-lucide="plus" class="w-3.5 h-3.5 text-slate-500"></i>
                                Add Filter
                            </button>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </section>

        <!-- 5. FEEDBACK & ALERTS -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="bell-ring" class="w-5 h-5 text-slate-700"></i>
                    <span>5. Feedback & Alerts</span>
                    <span class="text-xs font-normal text-slate-500">(&lt;x-ui.alert /&gt;)</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.alert variant="success" title="CIE Series Marks Approved">
                    Continuous Internal Evaluation marks for Module 1 & 2 have been locked by HOD.
                </x-ui.alert>

                <x-ui.alert variant="warning" title="Syllabus Review Required">
                    Module 4 course outcomes require mapping verification before Series Exam 2.
                </x-ui.alert>

                <x-ui.alert variant="error" title="Attendance Below Minimum Threshold">
                    12 students have attendance below 75% for the current semester cycle.
                </x-ui.alert>

                <x-ui.alert variant="info" title="System Synchronization Window">
                    Scheduled database backup and R2026 course file optimization will run tonight at 02:00 AM.
                </x-ui.alert>
            </div>
        </section>

        <!-- 6. KPI METRICS & DATA TABLE (Increased Breathing Room & Neutral Balance) -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <i data-lucide="table-2" class="w-5 h-5 text-slate-700"></i>
                    <span>6. KPI Cards & Data Tables</span>
                    <span class="text-xs font-normal text-slate-500">(&lt;x-ui.card /&gt;, &lt;x-ui.table /&gt;)</span>
                </h2>
            </div>

            <!-- KPI Metric Cards (Increased Breathing Room & Subdued Icons) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-slate-500">Total Enrolled</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <i data-lucide="users" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 mb-2">2,348</div>
                    <div class="flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                        <span>+5.4%</span> <span class="text-slate-400 font-normal">vs last month</span>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-slate-500">Faculty Members</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 mb-2">156</div>
                    <div class="flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                        <span>+2.1%</span> <span class="text-slate-400 font-normal">vs last month</span>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-slate-500">Attendance Today</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <i data-lucide="calendar-check-2" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 mb-2">92.6%</div>
                    <div class="flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                        <span>+3.7%</span> <span class="text-slate-400 font-normal">this month</span>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-slate-500">Pending Approvals</span>
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-slate-900 mb-2">12</div>
                    <div class="flex items-center gap-1.5 text-xs font-medium text-rose-700">
                        <i data-lucide="trending-down" class="w-3.5 h-3.5"></i>
                        <span>-2</span> <span class="text-slate-400 font-normal">this month</span>
                    </div>
                </div>

            </div>

            <!-- Standard Table with High Contrast Headers -->
            <x-ui.table :headers="['Student ID', 'Student Name', 'Program / Branch', 'Status', 'Attendance', 'Actions']">
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-4 px-6 font-medium text-slate-600">STU2026-001</td>
                    <td class="py-4 px-6 font-semibold text-slate-900">Alex Johnson</td>
                    <td class="py-4 px-6 text-slate-600">B.Tech Computer Science</td>
                    <td class="py-4 px-6"><x-ui.badge variant="active">Active</x-ui.badge></td>
                    <td class="py-4 px-6 font-medium text-slate-900">94.2%</td>
                    <td class="py-4 px-6">
                        <x-ui.button variant="tertiary" class="group">
                            <span>Dossier</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 ml-1 text-slate-400 group-hover:text-blue-600 transition-colors"></i>
                        </x-ui.button>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-4 px-6 font-medium text-slate-600">STU2026-002</td>
                    <td class="py-4 px-6 font-semibold text-slate-900">Maria Davis</td>
                    <td class="py-4 px-6 text-slate-600">B.Tech Electronics</td>
                    <td class="py-4 px-6"><x-ui.badge variant="pending">Pending</x-ui.badge></td>
                    <td class="py-4 px-6 font-medium text-slate-900">88.5%</td>
                    <td class="py-4 px-6">
                        <x-ui.button variant="tertiary" class="group">
                            <span>Dossier</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 ml-1 text-slate-400 group-hover:text-blue-600 transition-colors"></i>
                        </x-ui.button>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="py-4 px-6 font-medium text-slate-600">STU2026-003</td>
                    <td class="py-4 px-6 font-semibold text-slate-900">Ethan Wilson</td>
                    <td class="py-4 px-6 text-slate-600">B.Tech Mechanical</td>
                    <td class="py-4 px-6"><x-ui.badge variant="on_hold">On Hold</x-ui.badge></td>
                    <td class="py-4 px-6 font-medium text-slate-900">65.0%</td>
                    <td class="py-4 px-6">
                        <x-ui.button variant="tertiary" class="group">
                            <span>Dossier</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5 ml-1 text-slate-400 group-hover:text-blue-600 transition-colors"></i>
                        </x-ui.button>
                    </td>
                </tr>
            </x-ui.table>
        </section>

    </div>
</x-layouts.app-shell>
