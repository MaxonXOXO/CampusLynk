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

        .brand-title {
            font-weight: 900 !important;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #38bdf8 0%, #a855f7 50%, #f43f5e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 8px rgba(56, 189, 248, 0.4));
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
@php
    $userRole = session('userRole');
    $desktopUrl = '/dashboard/tutor?mode=desktop';
    if (in_array($userRole, ['Lecturer', 'HOD'])) {
        $desktopUrl = '/dashboard/lecturer?mode=desktop';
    } elseif ($userRole === 'Demonstrator') {
        $desktopUrl = '/dashboard/demonstrator?mode=desktop';
    } elseif ($userRole === 'Trade_Instructor') {
        $desktopUrl = '/dashboard/tradeinstructor?mode=desktop';
    } elseif ($userRole === 'Workshop_Superintendent') {
        $desktopUrl = '/dashboard/workshop?mode=desktop';
    }
@endphp

    <div class="mobile-container">

        <!-- Mobile Header -->
        <header class="mobile-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('logo.jpg') }}" alt="Logo" style="width: 32px; height: 32px; border-radius: 10px;" class="shadow-sm">
                <div>
                    <h5 class="brand-title mb-0" style="font-size: 1.18rem; font-weight: 900 !important;">Carmel Linx</h5>
                    <span class="badge badge-app px-2 py-0.5" style="background-color: rgba(168, 85, 247, 0.2); color: #e9d5ff; border: 1px solid rgba(168, 85, 247, 0.45); font-size: 0.68rem; font-weight: 800; border-radius: 6px;">Staff Mobile Portal</span>
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
            <div class="app-card border-start border-2 border-info">
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
                        <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                            <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app">{{ $staff->designation ?? session('userRole') }}</span>
                        </div>
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
                            <strong style="color: #fb923c; font-size: 1.05rem;">{{ count($remedialRooms) }}</strong>
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
                            <strong style="color: #fb923c; font-size: 1.05rem;">{{ count($remedialRooms) }}</strong>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- TAB PANES -->

            <!-- TAB 1: TODAY'S TIMETABLE & ATTENDANCE -->
            <div id="tab-classes" class="tab-pane fade-in">

                <!-- Timetable Day Order Selection Card -->
                <div class="app-card border-start border-2 border-cyan" style="border: 1px solid rgba(56, 189, 248, 0.3);">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.95rem;">
                            <i class="fa-solid fa-calendar-day me-1 text-cyan"></i> Timetable & Day Selection
                        </h6>
                        <span id="selectedDayBadge" class="badge text-dark fw-black px-3 py-1.5 shadow-sm d-inline-flex align-items-center gap-1.5" style="background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%); font-size: 1.1rem; font-weight: 900; border-radius: 10px; letter-spacing: 0.5px; box-shadow: 0 0 14px rgba(56, 189, 248, 0.4);">
                            <i class="fa-solid fa-calendar-day fs-6"></i> {{ $defaultDayOrder }}
                        </span>
                    </div>

                    <!-- Day Selection Pills (Day 1 to Day 5) -->
                    <div class="d-flex gap-1 overflow-x-auto pb-2 mb-2">
                        @foreach(['Day 1' => 'Mon', 'Day 2' => 'Tue', 'Day 3' => 'Wed', 'Day 4' => 'Thu', 'Day 5' => 'Fri'] as $dKey => $dShort)
                        <button onclick="selectDayOrder('{{ $dKey }}')" 
                                data-day="{{ $dKey }}" 
                                class="btn btn-sm {{ $dKey === $defaultDayOrder ? 'btn-cyan fw-bold text-dark' : 'btn-outline-secondary' }} px-2.5 py-1.5 rounded-pill day-order-btn flex-fill" style="font-size: 0.8rem; font-weight: 700; whitespace: nowrap;">
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
                        <div class="p-3 rounded-3 bg-dark border border-secondary border-opacity-25 mb-2">
                            <div class="d-flex align-items-start gap-3">
                                <i class="{{ $item->icon }} mt-1" style="font-size: 1.25rem;"></i>
                                <div class="flex-fill">
                                    <div class="d-flex align-items-center justify-content-between mb-1 gap-2 flex-wrap">
                                        <strong class="text-white" style="font-size: 0.88rem;">{{ $item->title }}</strong>
                                        <span class="badge {{ $item->badge_class }} badge-app">{{ $item->badge }}</span>
                                    </div>
                                    <small class="text-secondary d-block" style="font-size: 0.76rem;">{{ $item->subtitle }}</small>
                                </div>
                            </div>
                            @if(!empty($item->link) && $item->link !== '#')
                            <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end">
                                <a href="{{ $item->link }}" class="btn btn-sm btn-outline-info px-3 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-arrow-right-long me-1"></i> Open Task / Portal
                                </a>
                            </div>
                            @elseif($item->type === 'leave')
                            <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end">
                                <a href="#" onclick="switchStaffTab(event, 'tab-mentoring')" class="btn btn-sm btn-outline-warning px-3 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-clock-rotate-left me-1"></i> Review Leaves
                                </a>
                            </div>
                            @endif
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
                <div class="app-card border-start border-2" style="border-left-color: #f97316 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold mb-0" style="color: #fb923c; font-size: 0.95rem;">
                            <i class="fa-solid fa-kit-medical me-1"></i> Remedial Classes & Support
                        </h6>
                        <a href="/remedial-sessions" class="btn btn-sm px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem; color: #fb923c; border: 1px solid rgba(249, 115, 22, 0.4); background: rgba(249, 115, 22, 0.1);">
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
                                <span class="badge badge-app" style="background-color: rgba(249, 115, 22, 0.18); color: #fb923c; border: 1px solid rgba(249, 115, 22, 0.35);">{{ $room->status ?? 'Active' }}</span>
                            </div>
                            <div class="mt-2 pt-2 border-top border-secondary border-opacity-25 d-flex justify-content-end gap-2">
                                <a href="/remedial-sessions" class="btn btn-sm px-3 py-1 rounded-pill fw-bold text-white" style="font-size: 0.75rem; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border: none; box-shadow: 0 0 12px rgba(249, 115, 22, 0.3);">
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

            <!-- TAB 6: STAFF LEAVE GOVERNANCE PORTAL -->
            <div id="tab-leave" class="tab-pane d-none fade-in">
                
                <div class="app-card border-start border-2 border-info mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-bold text-white mb-0">Staff Leave Portal</h6>
                            <small class="text-secondary" style="font-size: 0.72rem;">3-Stage Hierarchical Approval Workflow</small>
                        </div>
                        <button type="button" onclick="openStaffLeaveModal()" class="btn btn-sm btn-info fw-bold text-dark rounded-pill px-3" style="background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%); border: none;">
                            <i class="fa-solid fa-paper-plane me-1"></i> Apply Leave
                        </button>
                    </div>
                </div>

                <!-- PENDING APPROVALS BOX (FOR HOD / COORDINATOR / PRINCIPAL) -->
                @if(in_array(session('userRole'), ['HOD', 'Academic_Coordinator', 'Principal', 'Super_Admin', 'Admin']))
                <div class="app-card border-start border-2 border-warning mb-3">
                    <h6 class="fw-bold text-warning mb-2" style="font-size: 0.88rem;">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Pending Staff Leave Approvals
                    </h6>
                    <div id="pendingApprovalsContainer" class="space-y-2">
                        <small class="text-secondary d-block">Loading pending approval queue...</small>
                    </div>
                    @if(in_array(session('userRole'), ['HOD', 'Academic_Coordinator', 'Principal']))
                    <div class="mt-2 text-end">
                        <a href="/staff/leave/reports" class="btn btn-sm btn-outline-warning py-0.5 px-2" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-table-list me-1"></i> View Master Leave Ledger
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                <!-- MY LEAVE APPLICATION HISTORY -->
                <div class="app-card">
                    <h6 class="fw-bold text-white mb-3" style="font-size: 0.88rem;">
                        <i class="fa-solid fa-list-check me-1 text-info"></i> My Leave Applications
                    </h6>
                    <div id="myLeaveHistoryContainer" class="space-y-2">
                        <small class="text-secondary d-block">Loading your leave records...</small>
                    </div>
                </div>

            </div>

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
                    <h6 class="fw-bold mb-3" style="color: #fbbf24; font-size: 0.95rem;">
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
                    <button onclick="updateStaffPassword()" class="btn w-100 fw-bold" style="font-size: 0.84rem; background: linear-gradient(135deg, #fde047 0%, #fbbf24 100%); color: #0f172a; font-weight: 800; border: none; box-shadow: 0 4px 14px rgba(251, 191, 36, 0.35);">
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
            <a href="#" class="nav-link-mobile" onclick="switchStaffTab(event, 'tab-leave')">
                <i class="fa-solid fa-file-signature"></i>
                <span>Leave</span>
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

    <!-- STAFF LEAVE APPLICATION MODAL -->
    <div id="staffLeaveModal" class="modal fade" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.75); backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border border-secondary border-opacity-25 text-white shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom border-secondary border-opacity-25 py-3">
                    <h6 class="modal-title fw-bold text-info" id="staffLeaveModalLabel">
                        <i class="fa-solid fa-paper-plane me-1"></i> Apply Staff Leave
                    </h6>
                    <button type="button" class="btn-close btn-close-white" onclick="closeStaffLeaveModal()"></button>
                </div>
                <div class="modal-body p-3">
                    <div id="staffLeaveAlert" class="alert d-none py-2 px-3 small font-bold mb-3"></div>
                    <form id="staffLeaveForm">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label text-secondary small fw-bold mb-1">Leave Type</label>
                                <select id="slvType" onchange="toggleCclDateField()" class="form-select bg-slate-900 border-secondary border-opacity-50 text-white" style="font-size: 0.85rem;" required>
                                    <option value="Casual Leave">Casual Leave (CL)</option>
                                    <option value="Compensatory Casual Leave">Compensatory Casual Leave (CCL)</option>
                                    <option value="Duty Leave">Duty Leave (DL)</option>
                                    <option value="Medical Leave">Medical Leave (ML)</option>
                                    <option value="Loss of Pay">Loss of Pay (LOP)</option>
                                    <option value="Special Leave">Special Leave</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-secondary small fw-bold mb-1">Session Type</label>
                                <select id="slvSession" class="form-select bg-slate-900 border-secondary border-opacity-50 text-white" style="font-size: 0.85rem;" required>
                                    <option value="Full Day">Full Day</option>
                                    <option value="FN">FN (Forenoon)</option>
                                    <option value="AN">AN (Afternoon)</option>
                                </select>
                            </div>
                        </div>

                        <!-- CCL Date Picker (Visible when CCL is selected) -->
                        <div id="cclDateBox" class="mb-3 d-none">
                            <label class="form-label text-info small fw-bold mb-1">
                                <i class="fa-solid fa-calendar-check me-1"></i> CCL Date (Date Duty Worked)
                            </label>
                            <input type="date" id="slvCclDate" class="form-control bg-slate-900 border-info border-opacity-50 text-white" style="font-size: 0.85rem;">
                            <small class="text-secondary" style="font-size: 0.7rem;">Specify the date on which compensatory duty was performed.</small>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-5">
                                <label class="form-label text-secondary small fw-bold mb-1">From Date</label>
                                <input type="date" id="slvFromDate" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" style="font-size: 0.85rem;" required>
                            </div>
                            <div class="col-5">
                                <label class="form-label text-secondary small fw-bold mb-1">To Date</label>
                                <input type="date" id="slvToDate" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" style="font-size: 0.85rem;" required>
                            </div>
                            <div class="col-2">
                                <label class="form-label text-secondary small fw-bold mb-1">Days</label>
                                <input type="number" step="0.5" min="0.5" id="slvTotalDays" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" value="1" style="font-size: 0.85rem;" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold mb-1">Reason for Leave</label>
                            <textarea id="slvReason" rows="2" class="form-control bg-slate-900 border-secondary border-opacity-50 text-white" placeholder="Provide reason for absence..." style="font-size: 0.85rem;" required></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label text-secondary small fw-bold mb-1">Work Arrangement / Substitutes</label>
                            <div class="p-2 border border-secondary border-opacity-25 rounded-3 bg-slate-950">
                                <div class="row g-1 mb-2">
                                    <div class="col-5">
                                        <input type="text" id="arrClassroom" class="form-control form-control-sm bg-dark text-white" placeholder="Period / Class">
                                    </div>
                                    <div class="col-5">
                                        <input type="text" id="arrSubstitute" class="form-control form-control-sm bg-dark text-white" placeholder="Substitute Staff">
                                    </div>
                                    <div class="col-2">
                                        <button type="button" onclick="addWorkArrangementRow()" class="btn btn-sm btn-info w-100">+</button>
                                    </div>
                                </div>
                                <ul id="arrList" class="list-group list-group-flush small" style="max-height: 100px; overflow-y: auto;"></ul>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill" onclick="closeStaffLeaveModal()">Cancel</button>
                    <button type="button" id="btnSubmitStaffLeave" onclick="submitStaffLeaveRequest()" class="btn btn-sm btn-info px-4 rounded-pill fw-bold text-dark" style="background: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%); border: none;">
                        <i class="fa-solid fa-paper-plane me-1"></i> Submit to HOD
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/bootstrap.bundle.min.js"></script>

    <script>
        const allTimetablesByDay = @json($fullTimetablesByDay);
        const currentDefaultDayOrder = @json($defaultDayOrder);
        const desktopAttendanceUrl = @json($desktopUrl);
        let workArrangementsArray = [];

        function switchStaffTab(e, tabId) {
            e.preventDefault();
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.nav-link-mobile').forEach(el => el.classList.remove('active'));

            const targetPane = document.getElementById(tabId);
            if (targetPane) {
                targetPane.classList.remove('d-none');
            }
            e.currentTarget.classList.add('active');

            if (tabId === 'tab-leave') {
                loadMyLeaveHistory();
                loadPendingApprovals();
            }
        }

        function toggleCclDateField() {
            const type = document.getElementById('slvType').value;
            const cclBox = document.getElementById('cclDateBox');
            if (type === 'Compensatory Casual Leave' || type === 'CCL') {
                cclBox.classList.remove('d-none');
            } else {
                cclBox.classList.add('d-none');
            }
        }

        function openStaffLeaveModal() {
            const modal = document.getElementById('staffLeaveModal');
            if (modal) {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('slvFromDate').value = today;
                document.getElementById('slvToDate').value = today;
                document.getElementById('slvCclDate').value = today;
                document.getElementById('staffLeaveAlert').classList.add('d-none');
                toggleCclDateField();
                workArrangementsArray = [];
                renderWorkArrangements();
                modal.style.display = 'block';
                modal.classList.add('show');
            }
        }

        function closeStaffLeaveModal() {
            const modal = document.getElementById('staffLeaveModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
            }
        }

        function addWorkArrangementRow() {
            const cls = document.getElementById('arrClassroom').value.trim();
            const sub = document.getElementById('arrSubstitute').value.trim();
            if (cls && sub) {
                workArrangementsArray.push({ classroom: cls, substitute_name: sub, date: document.getElementById('slvFromDate').value });
                document.getElementById('arrClassroom').value = '';
                document.getElementById('arrSubstitute').value = '';
                renderWorkArrangements();
            }
        }

        function renderWorkArrangements() {
            const list = document.getElementById('arrList');
            if (!list) return;
            list.innerHTML = '';
            workArrangementsArray.forEach((item, idx) => {
                list.innerHTML += `<li class="list-group-item bg-transparent text-white d-flex justify-content-between align-items-center py-1 px-0 border-secondary border-opacity-25" style="font-size: 0.75rem;">
                    <span><strong>${item.classroom}</strong> &rarr; ${item.substitute_name}</span>
                    <button type="button" onclick="removeWorkArrangementRow(${idx})" class="btn btn-sm btn-link text-danger p-0 ms-2">&times;</button>
                </li>`;
            });
        }

        function removeWorkArrangementRow(idx) {
            workArrangementsArray.splice(idx, 1);
            renderWorkArrangements();
        }

        function submitStaffLeaveRequest() {
            const leaveType = document.getElementById('slvType').value;
            const sessionType = document.getElementById('slvSession').value;
            const fromDate = document.getElementById('slvFromDate').value;
            const toDate = document.getElementById('slvToDate').value;
            const cclDate = document.getElementById('slvCclDate').value;
            const totalDays = parseFloat(document.getElementById('slvTotalDays').value);
            const reason = document.getElementById('slvReason').value.trim();
            const alertBox = document.getElementById('staffLeaveAlert');
            const btn = document.getElementById('btnSubmitStaffLeave');

            if (!fromDate || !toDate || !reason || isNaN(totalDays)) {
                alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                alertBox.innerText = 'Please complete all required fields.';
                alertBox.classList.remove('d-none');
                return;
            }

            if ((leaveType === 'Compensatory Casual Leave' || leaveType === 'CCL') && !cclDate) {
                alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                alertBox.innerText = 'Please specify the CCL Date (Date duty was performed).';
                alertBox.classList.remove('d-none');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Submitting...';

            fetch('/api/staff/leave/apply', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    leave_type: leaveType,
                    session_type: sessionType,
                    from_date: fromDate,
                    to_date: toDate,
                    ccl_date: (leaveType === 'Compensatory Casual Leave' || leaveType === 'CCL') ? cclDate : null,
                    total_days: totalDays,
                    reason: reason,
                    work_arrangement: workArrangementsArray
                })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Submit to HOD';

                if (data.status === 'SUCCESS') {
                    alertBox.className = 'alert alert-success py-2 px-3 small font-bold mb-3';
                    alertBox.innerText = data.message;
                    alertBox.classList.remove('d-none');
                    document.getElementById('staffLeaveForm').reset();
                    setTimeout(() => {
                        closeStaffLeaveModal();
                        loadMyLeaveHistory();
                    }, 1200);
                } else {
                    alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                    alertBox.innerText = data.message || 'Failed to submit leave.';
                    alertBox.classList.remove('d-none');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Submit to HOD';
                alertBox.className = 'alert alert-danger py-2 px-3 small font-bold mb-3';
                alertBox.innerText = 'Network error during leave submission.';
                alertBox.classList.remove('d-none');
            });
        }

        function loadMyLeaveHistory() {
            const container = document.getElementById('myLeaveHistoryContainer');
            if (!container) return;

            fetch('/api/staff/leave/my-history')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS' && data.leaves.length > 0) {
                    let html = '';
                    data.leaves.forEach(item => {
                        let statusBadge = '<span class="badge bg-info text-dark">Pending HOD</span>';
                        if (item.overall_status === 'Approved') statusBadge = '<span class="badge bg-success">Final Approved</span>';
                        else if (item.overall_status === 'Rejected') statusBadge = '<span class="badge bg-danger">Rejected</span>';
                        else if (item.overall_status === 'Pending_Coordinator') statusBadge = '<span class="badge bg-warning text-dark">Pending Coordinator</span>';
                        else if (item.overall_status === 'Pending_Principal') statusBadge = '<span class="badge bg-primary">Pending Principal</span>';

                        const cclText = item.ccl_date ? ` &bull; <span class="text-info font-mono">CCL Date: ${item.ccl_date}</span>` : '';
                        html += `<div class="p-2.5 rounded-3 border border-secondary border-opacity-25 bg-dark mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="font-mono text-cyan fw-bold small">${item.leave_code}</span>
                                ${statusBadge}
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-white small d-block">${item.leave_type} (${item.session_type})</strong>
                                    <small class="text-secondary" style="font-size:0.7rem;">${item.from_date} to ${item.to_date} &bull; ${item.total_days} Day(s)${cclText}</small>
                                </div>
                                <a href="/staff/leave/${item.id}/pdf" target="_blank" class="btn btn-sm btn-outline-info py-0.5 px-2" style="font-size:0.7rem;">
                                    <i class="fa-solid fa-file-pdf"></i> PDF
                                </a>
                            </div>
                        </div>`;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<small class="text-secondary d-block py-2">No leave applications submitted yet.</small>';
                }
            });
        }

        function loadPendingApprovals() {
            const container = document.getElementById('pendingApprovalsContainer');
            if (!container) return;

            fetch('/api/staff/leave/pending-approvals')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS' && data.approvals.length > 0) {
                    let html = '';
                    const stage = data.role === 'HOD' ? 'HOD' : (data.role === 'Principal' ? 'Principal' : 'Coordinator');
                    data.approvals.forEach(item => {
                        const cclText = item.ccl_date ? ` &bull; <span class="text-info font-mono">CCL Date: ${item.ccl_date}</span>` : '';
                        html += `<div class="p-2.5 rounded-3 border border-warning border-opacity-30 bg-slate-900 mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-white small">${item.staff_name} (${item.department})</strong>
                                <span class="badge bg-warning text-dark small">${item.leave_type}</span>
                            </div>
                            <small class="text-secondary d-block mb-1" style="font-size:0.72rem;">
                                ${item.from_date} to ${item.to_date} (${item.session_type}) &bull; ${item.total_days} Day(s)${cclText}
                            </small>
                            <div class="text-slate-300 small italic mb-2" style="font-size:0.75rem;">"${item.reason}"</div>
                            <div class="d-flex gap-2">
                                <button onclick="actionLeaveApproval(${item.id}, '${stage}', 'Approved')" class="btn btn-sm btn-success py-0.5 px-3 flex-grow-1" style="font-size:0.72rem;">
                                    <i class="fa-solid fa-check me-1"></i> Approve
                                </button>
                                <button onclick="actionLeaveApproval(${item.id}, '${stage}', 'Rejected')" class="btn btn-sm btn-outline-danger py-0.5 px-2" style="font-size:0.72rem;">
                                    <i class="fa-solid fa-xmark me-1"></i> Reject
                                </button>
                            </div>
                        </div>`;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<small class="text-secondary d-block py-1">No pending leave requests in your approval queue.</small>';
                }
            });
        }

        function actionLeaveApproval(leaveId, stage, action) {
            const remarks = prompt(`Enter optional remarks for ${action}:`) || '';
            fetch('/api/staff/leave/process-approval', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ leave_id: leaveId, stage: stage, action: action, remarks: remarks })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'SUCCESS') {
                    loadPendingApprovals();
                } else {
                    alert(data.message || 'Error processing approval.');
                }
            });
        }


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
                    btn.className = 'btn btn-sm btn-cyan px-2.5 py-1.5 rounded-pill fw-black text-dark day-order-btn flex-fill shadow-sm';
                    btn.style.fontSize = '0.8rem';
                } else {
                    btn.className = 'btn btn-sm btn-outline-secondary px-2.5 py-1 rounded-pill day-order-btn flex-fill';
                    btn.style.fontSize = '0.75rem';
                }
            });

            const slots = allTimetablesByDay[dayKey] || [];
            const container = document.getElementById('timetableScheduleContainer');
            const badgeLabel = document.getElementById('selectedDayBadge');
            if (badgeLabel) badgeLabel.innerHTML = `<i class="fa-solid fa-calendar-day fs-6 me-1"></i> ${dayKey}`;

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
