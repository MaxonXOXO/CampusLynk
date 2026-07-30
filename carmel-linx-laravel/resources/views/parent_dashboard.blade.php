<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Parent Portal — {{ $student->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --app-bg: #090d16;
            --card-bg: rgba(17, 24, 39, 0.95);
            --card-border: rgba(255, 255, 255, 0.08);
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --accent-purple: #8b5cf6;
        }

        body {
            background-color: var(--app-bg);
            color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.88rem;
            min-height: 100vh;
            padding-bottom: 75px; /* Space for bottom nav */
            -webkit-tap-highlight-color: transparent;
        }

        .mobile-container {
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
            background-color: var(--app-bg);
            position: relative;
        }

        /* Mobile Header */
        .mobile-header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 12px 16px;
        }

        /* App Cards */
        .app-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 16px;
            margin-bottom: 14px;
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.5);
        }

        /* Hero Attendance Circle */
        .attendance-dial {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 4px solid var(--accent-emerald);
            background: rgba(16, 185, 129, 0.08);
            margin: 0 auto;
        }
        .attendance-dial.warning {
            border-color: var(--accent-amber);
            background: rgba(245, 158, 11, 0.08);
        }
        .attendance-dial.danger {
            border-color: var(--accent-rose);
            background: rgba(244, 63, 94, 0.08);
        }

        /* Hour Timeline Card */
        .timeline-item {
            background: rgba(30, 41, 59, 0.6);
            border-left: 4px solid var(--accent-cyan);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 8px;
        }
        .timeline-item.present { border-left-color: var(--accent-emerald); }
        .timeline-item.absent { border-left-color: var(--accent-rose); }
        .timeline-item.not-marked { border-left-color: #64748b; }

        /* Bottom Mobile Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            background: rgba(15, 23, 42, 0.98);
            backdrop-filter: blur(16px);
            border-top: 1px solid var(--card-border);
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            z-index: 1000;
        }
        .nav-link-mobile {
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.68rem;
            font-weight: 600;
            gap: 3px;
        }
        .nav-link-mobile.active {
            color: var(--accent-cyan);
        }
        .nav-link-mobile i {
            font-size: 1.2rem;
        }

        .badge-app {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 8px;
            font-weight: 700;
        }

        .avatar-mobile {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid var(--accent-cyan);
            object-fit: cover;
        }
    </style>
</head>
<body>

    <div class="mobile-container">

        <!-- Top App Bar -->
        <div class="mobile-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-cyan fs-4"></i>
                <div>
                    <h6 class="fw-bold mb-0 text-white" style="font-size: 0.95rem;">Carmel Parent App</h6>
                    <small class="text-secondary" style="font-size: 0.72rem;">Academic Monitoring Portal</small>
                </div>
            </div>
            <a href="/parent" class="btn btn-sm btn-outline-danger px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                <i class="fa-solid fa-power-off"></i>
            </a>
        </div>

        <!-- Scrollable Content View -->
        <div class="p-3">

            <!-- Student Profile Header Card -->
            <div class="app-card">
                <div class="d-flex align-items-center gap-3">
                    @if($student->photo_url)
                        <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="avatar-mobile">
                    @else
                        <div class="avatar-mobile bg-dark text-cyan d-flex align-items-center justify-content-center fw-bold fs-5">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="fw-extrabold text-white mb-0" style="font-size: 1rem;">{{ $student->name }}</h6>
                        <div class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                            <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app">Reg: {{ $student->reg_no }}</span>
                            <span class="badge bg-purple bg-opacity-20 text-purple badge-app">Sem {{ $student->semester }} ({{ $student->branch }})</span>
                        </div>
                    </div>
                </div>

                @if($tutor && $tutor->mobile_no)
                <div class="mt-3 pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                    <span class="text-secondary small" style="font-size: 0.78rem;">Advisor: <strong>{{ $tutor->name }}</strong></span>
                    <a href="tel:{{ $tutor->mobile_no }}" class="btn btn-sm btn-success px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-phone me-1"></i> Call Advisor
                    </a>
                </div>
                @endif
            </div>

            <!-- Tab Content 1: Today's Attendance & Hero Gauge -->
            <div id="tab-attendance" class="tab-pane active">
                
                <!-- Hero Attendance Gauge -->
                <div class="app-card text-center">
                    <span class="text-secondary uppercase text-[11px] fw-bold d-block mb-2">Overall Attendance Percentage</span>
                    <div class="attendance-dial {{ $overallAttendancePct >= 75 ? '' : ($overallAttendancePct >= 65 ? 'warning' : 'danger') }}">
                        <span class="fw-extrabold fs-4 {{ $overallAttendancePct >= 75 ? 'text-emerald-400' : ($overallAttendancePct >= 65 ? 'text-amber-400' : 'text-rose-400') }}">
                            {{ number_format($overallAttendancePct, 1) }}%
                        </span>
                    </div>
                    <div class="mt-2">
                        <span class="badge {{ $overallAttendancePct >= 75 ? 'bg-success' : ($overallAttendancePct >= 65 ? 'bg-warning text-dark' : 'bg-danger') }} badge-app">
                            {{ $overallAttendancePct >= 75 ? 'Good Standing (Eligible)' : ($overallAttendancePct >= 65 ? 'Warning: Low Attendance' : 'Critical: Condonation Alert') }}
                        </span>
                    </div>
                    <small class="text-secondary d-block mt-2" style="font-size: 0.75rem;">
                        Attended: <strong>{{ $totalAttendedClasses }}</strong> / Total Conducted: <strong>{{ $totalConductedClasses }}</strong> Hours
                    </small>
                </div>

                <!-- Today's Hour-Wise Attendance Grid -->
                <div class="app-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.9rem;">
                            <i class="fa-solid fa-calendar-day me-1 text-cyan"></i> Today's Schedule (Periods 1–6 + Special 7th Hour)
                        </h6>
                        <small class="text-secondary" style="font-size: 0.72rem;">{{ \Carbon\Carbon::now()->format('d M Y') }}</small>
                    </div>

                    @foreach($hourlyStatus as $pNum => $pData)
                    <div class="timeline-item {{ strtolower(str_replace(' ', '-', $pData['status'])) }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="badge bg-secondary me-1" style="font-size: 0.68rem;">P{{ $pNum }}</span>
                                <strong class="text-white" style="font-size: 0.85rem;">{{ $pData['subject_name'] }}</strong>
                                <small class="text-secondary d-block mt-0.5" style="font-size: 0.72rem;">
                                    {{ $pData['topic'] }}
                                </small>
                            </div>
                            <span class="badge {{ $pData['badge_class'] }} badge-app">
                                {{ $pData['status'] }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>

            <!-- Tab Content 2: Assignments & Tests -->
            <div id="tab-tasks" class="tab-pane d-none">
                <!-- Assignments Card -->
                <div class="app-card">
                    <h6 class="fw-bold text-warning mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-list-check me-1"></i> Assignments & Submissions
                    </h6>
                    @forelse($assignments as $asgn)
                    <div class="p-2.5 rounded bg-dark border border-secondary border-opacity-25 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-info" style="font-size: 0.82rem;">{{ $asgn->subject_code ?? 'Subject' }}</strong>
                            <span class="badge bg-warning text-dark badge-app">Pending</span>
                        </div>
                        <p class="text-white mb-1 fw-semibold" style="font-size: 0.85rem;">{{ $asgn->title }}</p>
                        <small class="text-secondary" style="font-size: 0.72rem;">
                            Due: {{ \Carbon\Carbon::parse($asgn->due_date)->format('d M Y') }}
                        </small>
                    </div>
                    @empty
                    <p class="text-secondary text-center my-3 small">No pending assignments listed for today.</p>
                    @endforelse
                </div>

                <!-- Practical Series Tests -->
                <div class="app-card">
                    <h6 class="fw-bold text-emerald mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-file-pen me-1"></i> Scheduled Practical Tests
                    </h6>
                    @forelse($practicalTests as $test)
                    <div class="p-2.5 rounded bg-dark border border-secondary border-opacity-25 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="text-white d-block" style="font-size: 0.85rem;">{{ $test->test_name }}</strong>
                            <small class="text-secondary" style="font-size: 0.72rem;">Date: {{ $test->test_date }}</small>
                        </div>
                        <span class="badge bg-cyan text-dark badge-app">{{ $test->max_marks }} Marks</span>
                    </div>
                    @empty
                    <p class="text-secondary text-center my-3 small">No test evaluations scheduled today.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tab Content 3: Remarks & Comments -->
            <div id="tab-remarks" class="tab-pane d-none">
                <div class="app-card">
                    <h6 class="fw-bold text-purple mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-comments me-1"></i> Tutor & Faculty Remarks
                    </h6>
                    @forelse($mentoringNotes as $note)
                    <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong class="text-cyan" style="font-size: 0.8rem;">{{ $note->faculty_name ?? 'Faculty Advisor' }}</strong>
                            <small class="text-secondary" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($note->created_at)->format('d M Y') }}</small>
                        </div>
                        <p class="text-slate-200 mb-0" style="font-size: 0.82rem;">
                            {{ $note->comments ?? 'Academic guidance session conducted.' }}
                        </p>
                    </div>
                    @empty
                    <div class="p-3 text-center text-secondary bg-dark rounded border border-secondary border-opacity-25 small">
                        <i class="fa-solid fa-circle-check text-success me-1"></i> No critical remarks. Student academic progress is satisfactory.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Share SMS Link Box -->
            <div class="app-card text-center">
                <small class="text-secondary fw-semibold d-block mb-2" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-share-nodes me-1"></i> Share Access Link via SMS
                </small>
                <div class="input-group input-group-sm">
                    <input type="text" id="smsLinkInput" class="form-control bg-dark text-light border-secondary" value="{{ $smsShareUrl }}" readonly style="font-size: 0.75rem;">
                    <a href="sms:{{ $student->guardian_mobile ?: $student->phone }}?body={{ urlencode('Carmel Poly: View ward status: ' . $smsShareUrl) }}" class="btn btn-cyan text-dark font-bold">
                        <i class="fa-solid fa-paper-plane me-1"></i> SMS
                    </a>
                </div>
            </div>

        </div>

        <!-- Bottom Mobile Navigation Bar -->
        <div class="bottom-nav">
            <a href="#" class="nav-link-mobile active" onclick="switchTab(event, 'tab-attendance')">
                <i class="fa-solid fa-clock"></i>
                <span>Attendance</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchTab(event, 'tab-tasks')">
                <i class="fa-solid fa-list-check"></i>
                <span>Tasks & Tests</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchTab(event, 'tab-remarks')">
                <i class="fa-solid fa-comments"></i>
                <span>Remarks</span>
            </a>
        </div>

    </div>

    <!-- Script for Tab Switching -->
    <script>
        function switchTab(e, tabId) {
            e.preventDefault();
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.nav-link-mobile').forEach(el => el.classList.remove('active'));

            document.getElementById(tabId).classList.remove('d-none');
            e.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>
