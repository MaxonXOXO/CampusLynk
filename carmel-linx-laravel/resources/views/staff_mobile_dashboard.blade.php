<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Carmel Linx - Staff Mobile Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --app-bg: #090d16;
            --card-bg: rgba(15, 23, 42, 0.92);
            --card-border: rgba(255, 255, 255, 0.08);
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --accent-purple: #8b5cf6;
            --accent-blue: #3b82f6;
        }

        body {
            background-color: var(--app-bg);
            color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            min-height: 100vh;
            padding-bottom: 120px; /* Ample space to prevent bottom navigation overlap */
            -webkit-tap-highlight-color: transparent;
        }

        .mobile-container {
            max-width: 520px;
            margin: 0 auto;
            min-height: 100vh;
            background-color: var(--app-bg);
            position: relative;
            padding-bottom: 30px;
        }

        /* Mobile Header */
        .mobile-header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 14px 16px;
        }

        /* App Cards */
        .app-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 16px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.4);
            backdrop-filter: blur(12px);
        }

        /* Bottom Mobile Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 520px;
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(20px);
            border-top: 1px solid var(--card-border);
            display: flex;
            justify-content: space-around;
            padding: 10px 4px;
            z-index: 1000;
        }

        .nav-link-mobile {
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.72rem;
            font-weight: 700;
            gap: 4px;
            flex: 1;
            text-align: center;
            transition: all 0.2s ease;
        }

        .nav-link-mobile.active {
            color: var(--accent-cyan);
        }

        .nav-link-mobile i {
            font-size: 1.15rem;
        }

        .badge-app {
            font-size: 0.76rem;
            padding: 4px 8px;
            border-radius: 8px;
            font-weight: 700;
        }

        .avatar-mobile {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 2px solid var(--accent-cyan);
            object-fit: cover;
        }

        /* Stat Mini Card */
        .stat-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            padding: 12px;
        }

        .form-control, .form-select {
            font-size: 0.88rem !important;
            padding: 8px 12px;
            background-color: rgba(15, 23, 42, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-cyan) !important;
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.2) !important;
        }

        .form-label {
            font-size: 0.8rem !important;
            font-weight: 700;
            color: #cbd5e1;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="mobile-container">

        <!-- Mobile Header -->
        <header class="mobile-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('logo.jpg') }}" alt="Logo" style="width: 32px; height: 32px; border-radius: 10px;" class="shadow-sm">
                <div>
                    <h5 class="fw-black mb-0 text-white" style="font-size: 1.15rem; font-weight: 900; letter-spacing: -0.3px; background: linear-gradient(to right, #ffffff, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Carmel Linx</h5>
                    <small class="text-secondary" style="font-size: 0.7rem;">Staff Mobile Portal</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('/logout') }}" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;" title="Sign Out">
                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                </a>
            </div>
        </header>

        <!-- Main Body Content -->
        <div class="p-3">

            <!-- Staff Identity Banner -->
            <div class="app-card border-start border-4 border-info">
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if(!empty($staff->photo_url))
                        <img src="{{ $staff->photo_url }}" alt="{{ $staff->name }}" class="avatar-mobile">
                    @else
                        <div class="avatar-mobile bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center font-black text-white" style="font-size: 1.1rem; background: linear-gradient(135deg, #4f46e5, #7c3aed); display: flex; align-items: center; justify-content: center;">
                            {{ strtoupper(substr($staff->name ?? 'S', 0, 2)) }}
                        </div>
                    @endif
                    <div class="overflow-hidden">
                        <h6 class="fw-bold text-white mb-0 text-truncate" style="font-size: 1.05rem;">{{ $staff->name ?? session('userName') }}</h6>
                        <small class="text-info font-mono font-bold d-block" style="font-size: 0.78rem;">{{ $staff->mobile_no ?? session('userId') }}</small>
                        <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app mt-1">{{ $staff->designation ?? session('userRole') }}</span>
                    </div>
                </div>

                @php
                    $isMentor = (count($classrooms) > 0);
                @endphp

                <div class="row g-2 text-center">
                    @if($isMentor)
                    <div class="col-3">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.65rem; font-weight: 700;">Subjects</span>
                            <strong class="text-white" style="font-size: 1.05rem;">{{ count($assignments) }}</strong>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.65rem; font-weight: 700;">Tutorship</span>
                            <strong class="text-cyan" style="font-size: 1.05rem;">{{ count($classrooms) }}</strong>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.65rem; font-weight: 700;">To-Dos</span>
                            <strong class="text-warning" style="font-size: 1.05rem;">{{ count($todos) }}</strong>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.65rem; font-weight: 700;">Remedial</span>
                            <strong class="text-danger" style="font-size: 1.05rem;">{{ count($remedialRooms) }}</strong>
                        </div>
                    </div>
                    @else
                    <div class="col-4">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.65rem; font-weight: 700;">Subjects</span>
                            <strong class="text-white" style="font-size: 1.05rem;">{{ count($assignments) }}</strong>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.65rem; font-weight: 700;">To-Dos</span>
                            <strong class="text-warning" style="font-size: 1.05rem;">{{ count($todos) }}</strong>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card">
                            <span class="text-secondary uppercase d-block" style="font-size: 0.65rem; font-weight: 700;">Remedial</span>
                            <strong class="text-danger" style="font-size: 1.05rem;">{{ count($remedialRooms) }}</strong>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- TAB PANES -->

            <!-- TAB 1: TODAY'S TIMETABLE & ATTENDANCE -->
            <div id="tab-classes" class="tab-pane fade-in">

                <!-- Timetable Day Order Selection Card -->
                <div class="app-card border-start border-4 border-cyan">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold text-cyan mb-0" style="font-size: 0.95rem;">
                            <i class="fa-solid fa-calendar-day me-1"></i> Timetable & Day Selection
                        </h6>
                        <span id="selectedDayBadge" class="badge bg-cyan bg-opacity-20 text-cyan badge-app">{{ $defaultDayOrder }}</span>
                    </div>

                    <!-- Day Selection Pills (Day 1 to Day 5) -->
                    <div class="d-flex gap-1 overflow-x-auto pb-2 mb-2">
                        @foreach(['Day 1' => 'Mon', 'Day 2' => 'Tue', 'Day 3' => 'Wed', 'Day 4' => 'Thu', 'Day 5' => 'Fri'] as $dKey => $dShort)
                        <button onclick="selectDayOrder('{{ $dKey }}')" 
                                data-day="{{ $dKey }}" 
                                class="btn btn-sm {{ $dKey === $defaultDayOrder ? 'btn-cyan fw-bold text-dark' : 'btn-outline-secondary' }} px-2.5 py-1 rounded-pill day-order-btn flex-fill" style="font-size: 0.72rem; whitespace: nowrap;">
                            {{ $dKey }} <small class="opacity-75">({{ $dShort }})</small>
                        </button>
                        @endforeach
                    </div>

                    <div class="text-secondary mb-3" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-circle-info text-cyan me-1"></i> If a holiday occurred, select the active <strong>Day Order (1-5)</strong> for attendance taking.
                    </div>

                    <!-- Dynamic Timetable Slots Container -->
                    <div id="timetableScheduleContainer">
                        <!-- Populated dynamically via JS -->
                    </div>
                </div>

                <!-- Assigned Subjects & Classes -->
                <div class="app-card">
                    <h6 class="fw-bold text-info mb-3" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-chalkboard-user me-1"></i> Assigned Subjects & Attendance
                    </h6>

                    @forelse($assignments as $subj)
                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div>
                                <strong class="text-white d-block" style="font-size: 0.88rem;">{{ $subj->subject_code }} - {{ $subj->subject_name }}</strong>
                                <small class="text-secondary" style="font-size: 0.75rem;">Batch: <strong class="text-cyan">{{ $subj->classroom_id }}</strong> | Sem {{ $subj->semester }}</small>
                            </div>
                            <span class="badge bg-info bg-opacity-20 text-info badge-app">{{ $subj->subject_type ?? 'Theory' }}</span>
                        </div>
                        <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end">
                            <a href="{{ $desktopUrl }}" class="btn btn-sm btn-info px-3 py-1 rounded-pill fw-bold text-dark" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-clipboard-user me-1"></i> Mark Attendance
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-secondary py-3" style="font-size: 0.8rem;">
                        No direct subject assignments found for this academic session.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- TAB 2: TO-DO WORKS -->
            <div id="tab-todo" class="tab-pane d-none fade-in">
                <div class="app-card">
                    <h6 class="fw-bold text-warning mb-3" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-list-check me-1"></i> Staff Works & Tasks To-Do
                    </h6>

                    <div class="space-y-2">
                        @forelse($todos as $item)
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2 d-flex align-items-start gap-3">
                            <i class="{{ $item->icon }} mt-1" style="font-size: 1.2rem;"></i>
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <strong class="text-white" style="font-size: 0.88rem;">{{ $item->title }}</strong>
                                    <span class="badge {{ $item->badge_class }} badge-app">{{ $item->badge }}</span>
                                </div>
                                <small class="text-secondary d-block" style="font-size: 0.76rem;">{{ $item->subtitle }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-3" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-circle-check d-block mb-1 text-success" style="font-size: 1.2rem;"></i>
                            All staff tasks & works are completed!
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 3: REMEDIAL CLASSES -->
            <div id="tab-remedial" class="tab-pane d-none fade-in">
                <div class="app-card border-start border-4 border-danger">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-danger mb-0" style="font-size: 0.95rem;">
                            <i class="fa-solid fa-kit-medical me-1"></i> Remedial Classes & Support
                        </h6>
                        <a href="/remedial-sessions" class="btn btn-sm btn-outline-danger px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-arrow-right-long me-1"></i> Open Portal
                        </a>
                    </div>

                    <div class="space-y-2">
                        @forelse($remedialRooms as $room)
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div>
                                    <strong class="text-white d-block" style="font-size: 0.88rem;">Room: {{ $room->room_code ?? $room->id }}</strong>
                                    <small class="text-secondary" style="font-size: 0.75rem;">Batch: <strong class="text-cyan">{{ $room->classroom_id ?? 'Academic' }}</strong></small>
                                </div>
                                <span class="badge bg-danger bg-opacity-20 text-danger badge-app">{{ $room->status ?? 'Active' }}</span>
                            </div>
                            <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end gap-2">
                                <a href="/remedial-sessions" class="btn btn-sm btn-danger px-3 py-1 rounded-pill fw-bold text-white" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Log Session & Attendance
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-3" style="font-size: 0.8rem;">
                            No active remedial class rooms currently assigned.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 4: MENTORING & LEAVE APPROVALS (IF MENTOR) -->
            @if($isMentor)
            <div id="tab-mentoring" class="tab-pane d-none fade-in">

                <!-- Pending Student Leave Approvals -->
                <div class="app-card">
                    <h6 class="fw-bold text-warning mb-3" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Student Leave Requests (Pending)
                    </h6>
                    <div id="mobilePendingLeavesList" class="space-y-2">
                        @forelse($pendingLeaves as $leave)
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div>
                                    <strong class="text-white d-block" style="font-size: 0.88rem;">{{ $leave->student_name }}</strong>
                                    <small class="text-cyan font-mono" style="font-size: 0.75rem;">{{ $leave->reg_no }}</small>
                                </div>
                                <span class="badge bg-warning text-dark badge-app">Pending</span>
                            </div>
                            <div class="text-secondary my-1" style="font-size: 0.76rem;">
                                <div><i class="fa-solid fa-calendar me-1 text-warning"></i> Date: <strong class="text-white">{{ \Carbon\Carbon::parse($leave->leave_date)->format('d M Y') }}</strong></div>
                                <div><i class="fa-solid fa-circle-info me-1 text-warning"></i> Reason: <span class="text-white">{{ $leave->reason }}</span></div>
                            </div>
                            <div class="d-flex gap-2 mt-2 pt-2 border-top border-secondary border-opacity-25">
                                <button onclick="processMobileLeave('{{ $leave->id }}', 'Approved')" class="btn btn-sm btn-success flex-fill fw-bold" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-check me-1"></i> Approve
                                </button>
                                <button onclick="processMobileLeave('{{ $leave->id }}', 'Rejected')" class="btn btn-sm btn-outline-danger flex-fill fw-bold" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-xmark me-1"></i> Reject
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-secondary py-3" style="font-size: 0.8rem;">
                            No pending student leave requests requiring review.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Assigned Mentoring Tutorship -->
                <div class="app-card">
                    <h6 class="fw-bold text-cyan mb-3" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-user-graduate me-1"></i> Assigned Tutorship & Mentoring Batches
                    </h6>
                    @forelse($classrooms as $cls)
                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div>
                                <strong class="text-white d-block" style="font-size: 0.88rem;">Batch: {{ $cls->classroom_id }}</strong>
                                <small class="text-secondary" style="font-size: 0.75rem;">Branch: {{ $cls->branch }} | Sem {{ $cls->current_semester }}</small>
                            </div>
                            <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app">Tutor</span>
                        </div>
                        <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end">
                            <a href="/tutor/mentoring-diary/{{ $cls->classroom_id }}" class="btn btn-sm btn-cyan px-3 py-1 rounded-pill fw-bold text-dark" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-book-open me-1"></i> Mentoring Diary
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-secondary py-3" style="font-size: 0.8rem;">
                        No mentoring classrooms currently assigned.
                    </div>
                    @endforelse
                </div>

            </div>
            @endif

            <!-- TAB 5: PROFILE & SECURITY -->
            <div id="tab-profile" class="tab-pane d-none fade-in">
                
                <div class="app-card">
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-id-card me-1 text-info"></i> Staff Profile Overview
                    </h6>
                    <div class="space-y-2 text-secondary" style="font-size: 0.82rem;">
                        <div class="py-1 border-bottom border-secondary border-opacity-25 d-flex justify-content-between">
                            <span>Full Name:</span> <strong class="text-white">{{ $staff->name ?? session('userName') }}</strong>
                        </div>
                        <div class="py-1 border-bottom border-secondary border-opacity-25 d-flex justify-content-between">
                            <span>Mobile ID:</span> <strong class="text-cyan font-mono">{{ $staff->mobile_no ?? session('userId') }}</strong>
                        </div>
                        <div class="py-1 border-bottom border-secondary border-opacity-25 d-flex justify-content-between">
                            <span>Role / Designation:</span> <strong class="text-white">{{ $staff->designation ?? session('userRole') }}</strong>
                        </div>
                        <div class="py-1 d-flex justify-content-between">
                            <span>Department:</span> <strong class="text-white">{{ $staff->department ?? session('userBranch', 'Academic') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Password Reset -->
                <div class="app-card">
                    <h6 class="fw-bold text-warning mb-3" style="font-size: 0.95rem;">
                        <i class="fa-solid fa-key me-1"></i> Change Account Password
                    </h6>
                    <div id="staffPwdAlert" class="small mb-2 d-none font-bold"></div>
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" id="sOldPwd" class="form-control" placeholder="Enter current password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" id="sNewPwd" class="form-control" placeholder="Enter new password (min 6 chars)">
                    </div>
                    <button onclick="updateStaffPassword()" class="btn btn-warning w-100 fw-bold text-dark" style="font-size: 0.82rem;">
                        <i class="fa-solid fa-shield-halved me-1"></i> Update Password
                    </button>
                </div>

            </div>

        </div>

        <!-- Bottom Navigation Bar -->
        <nav class="bottom-nav">
            <a href="#" class="nav-link-mobile active" onclick="switchStaffTab(event, 'tab-classes')">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Classes</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchStaffTab(event, 'tab-todo')">
                <i class="fa-solid fa-list-check"></i>
                <span>To-Do</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchStaffTab(event, 'tab-remedial')">
                <i class="fa-solid fa-kit-medical"></i>
                <span>Remedial</span>
            </a>
            @if($isMentor)
            <a href="#" class="nav-link-mobile" onclick="switchStaffTab(event, 'tab-mentoring')">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Mentoring</span>
            </a>
            @endif
            <a href="#" class="nav-link-mobile" onclick="switchStaffTab(event, 'tab-profile')">
                <i class="fa-solid fa-user-gear"></i>
                <span>Profile</span>
            </a>
        </nav>

    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/bootstrap.bundle.min.js"></script>

    <script>
        const allTimetablesByDay = @json($fullTimetablesByDay);
        const currentDefaultDayOrder = @json($defaultDayOrder);
        const desktopAttendanceUrl = @json($desktopUrl);

        function selectDayOrder(dayKey) {
            // Save universal day order setting for today across all dashboards
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/api/system/set-day-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ day_order: dayKey })
            });

            document.querySelectorAll('.day-order-btn').forEach(btn => {
                if (btn.dataset.day === dayKey) {
                    btn.className = 'btn btn-sm btn-cyan px-2.5 py-1 rounded-pill fw-bold text-dark day-order-btn flex-fill';
                } else {
                    btn.className = 'btn btn-sm btn-outline-secondary px-2.5 py-1 rounded-pill day-order-btn flex-fill';
                }
            });

            const slots = allTimetablesByDay[dayKey] || [];
            const container = document.getElementById('timetableScheduleContainer');
            const badgeLabel = document.getElementById('selectedDayBadge');
            if (badgeLabel) badgeLabel.innerText = dayKey;

            if (!container) return;
            if (slots.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-secondary py-3" style="font-size: 0.82rem;">
                        <i class="fa-solid fa-calendar-xmark d-block mb-1 text-warning" style="font-size: 1.2rem;"></i>
                        No timetable slots scheduled for <strong>${dayKey}</strong>.<br>Use the assigned subjects list below for attendance taking.
                    </div>`;
                return;
            }

            let html = '';
            slots.forEach(st => {
                html += `
                    <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div>
                                <span class="badge bg-info text-dark fw-bold me-1" style="font-size: 0.72rem;">Period ${st.period}</span>
                                <strong class="text-white" style="font-size: 0.88rem;">${st.subject_code}</strong>
                                <small class="text-secondary d-block mt-0.5" style="font-size: 0.75rem;">${st.subject_name || ''} | Batch: <strong class="text-cyan">${st.classroom_id}</strong></small>
                            </div>
                            <a href="${desktopAttendanceUrl}" class="btn btn-sm btn-info px-2.5 py-1 rounded-pill fw-bold text-dark" style="font-size: 0.72rem;">
                                <i class="fa-solid fa-clipboard-user me-1"></i> Attendance
                            </a>
                        </div>
                    </div>`;
            });
            container.innerHTML = html;
        }

        document.addEventListener('DOMContentLoaded', function() {
            selectDayOrder(currentDefaultDayOrder);
        });

        function switchStaffTab(e, tabId) {
            e.preventDefault();
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.nav-link-mobile').forEach(el => el.classList.remove('active'));

            const target = document.getElementById(tabId);
            if (target) target.classList.remove('d-none');
            e.currentTarget.classList.add('active');
        }

        function processMobileLeave(leaveId, decision) {
            if (!confirm(`Are you sure you want to set this leave status to ${decision}?`)) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch('/api/mentoring/leave/action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ leave_id: leaveId, status: decision })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    location.reload();
                } else {
                    alert(data.message || 'Error processing leave request.');
                }
            });
        }

        function updateStaffPassword() {
            const oldPwd = document.getElementById('sOldPwd').value;
            const newPwd = document.getElementById('sNewPwd').value;
            const alertDiv = document.getElementById('staffPwdAlert');

            if (!oldPwd || !newPwd || newPwd.length < 6) {
                alertDiv.className = 'small mb-2 font-bold text-danger';
                alertDiv.innerText = 'Password must be at least 6 characters.';
                alertDiv.classList.remove('d-none');
                return;
            }

            fetch('/api/admin/user/reset-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ old_password: oldPwd, new_password: newPwd })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    alertDiv.className = 'small mb-2 font-bold text-success';
                    alertDiv.innerText = 'Password updated successfully!';
                    alertDiv.classList.remove('d-none');
                } else {
                    alertDiv.className = 'small mb-2 font-bold text-danger';
                    alertDiv.innerText = data.message || 'Error updating password.';
                    alertDiv.classList.remove('d-none');
                }
            });
        }
    </script>
</body>
</html>
