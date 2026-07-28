<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Portal — {{ $student->name }} ({{ $student->reg_no }})</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0b0f19;
            --card-bg: rgba(15, 23, 42, 0.88);
            --border-color: rgba(255, 255, 255, 0.1);
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
        }

        body {
            background-color: var(--bg-dark);
            color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            min-height: 100vh;
        }

        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(14px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
            margin-bottom: 20px;
        }

        .badge-cyan { background: rgba(6, 182, 212, 0.15); color: #38bdf8; border: 1px solid rgba(6, 182, 212, 0.3); }
        .badge-emerald { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge-amber { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge-rose { background: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3); }

        .table-custom {
            color: #e2e8f0;
            margin-bottom: 0;
        }
        .table-custom th {
            background-color: rgba(30, 41, 59, 0.8);
            color: #94a3b8;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-color);
        }
        .table-custom td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 10px 12px;
            font-size: 0.85rem;
        }

        .period-card {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 10px 14px;
            transition: all 0.2s ease;
        }
        .period-card:hover {
            border-color: var(--accent-cyan);
        }

        .avatar-circle {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent-cyan);
        }

        .btn-compact {
            padding: 4px 10px;
            font-size: 0.75rem;
            border-radius: 6px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-dark bg-slate-900 border-bottom border-secondary border-opacity-25 py-2">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand d-flex align-items-center gap-2 font-bold text-white fs-6" href="#">
                <i class="fa-solid fa-graduation-cap text-cyan fs-5"></i>
                <span>Carmel Polytechnic College — Parent Portal</span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="sms:{{ $student->guardian_mobile ?: $student->phone }}?body={{ urlencode('Carmel Poly: Access ward portal link: ' . $smsShareUrl) }}" class="btn btn-sm btn-outline-info btn-compact">
                    <i class="fa-solid fa-comment-sms me-1"></i> Send SMS Link
                </a>
                <a href="/parent" class="btn btn-sm btn-outline-danger btn-compact">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Exit
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container-fluid px-3 px-md-4 py-3">

        <!-- Top Profile Banner -->
        <div class="glass-card p-3">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        @if($student->photo_url)
                            <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="avatar-circle">
                        @else
                            <div class="avatar-circle bg-dark text-cyan d-flex align-items-center justify-content-center fs-4 font-bold">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <h4 class="fw-bold mb-0 text-white">{{ $student->name }}</h4>
                                <span class="badge badge-cyan px-2 py-0.5">Reg No: {{ $student->reg_no }}</span>
                                <span class="badge badge-purple px-2 py-0.5">Sem {{ $student->semester }} ({{ $student->branch }})</span>
                            </div>
                            <p class="text-secondary small mb-0">
                                Classroom: <strong>{{ $student->classroom_id }}</strong> | 
                                Guardian: <strong>{{ $student->guardian_name ?: 'Guardian' }}</strong> ({{ $student->guardian_mobile ?: $student->phone }}) |
                                Class Advisor: <strong>{{ $tutor->name ?? 'Faculty Advisor' }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Overall Attendance Stat Badge -->
                <div class="col-md-4">
                    <div class="p-3 rounded-3 text-center" style="background: rgba(15, 23, 42, 0.7); border: 1px solid var(--border-color);">
                        <span class="text-secondary uppercase text-[11px] fw-bold d-block">Overall Semester Attendance</span>
                        <div class="d-flex align-items-center justify-content-center gap-2 mt-1">
                            <span class="fs-2 fw-extrabold {{ $overallAttendancePct >= 75 ? 'text-success' : ($overallAttendancePct >= 65 ? 'text-warning' : 'text-danger') }}">
                                {{ number_format($overallAttendancePct, 1) }}%
                            </span>
                            <span class="badge {{ $overallAttendancePct >= 75 ? 'badge-emerald' : ($overallAttendancePct >= 65 ? 'badge-amber' : 'badge-rose') }}">
                                {{ $overallAttendancePct >= 75 ? 'Good Standing' : ($overallAttendancePct >= 65 ? 'Condonation Warning' : 'Low Attendance Alert') }}
                            </span>
                        </div>
                        <small class="text-muted d-block mt-1">Total Classes Attended: {{ $totalAttendedClasses }} / {{ $totalConductedClasses }} Conducted</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            
            <!-- Left Column: Daily Hour-Wise Attendance Grid -->
            <div class="col-lg-7">
                <div class="glass-card p-3">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                        <h6 class="fw-bold mb-0 text-cyan">
                            <i class="fa-solid fa-clock me-2"></i>Today's Hour-Wise Attendance Status ({{ \Carbon\Carbon::now()->format('D, d M Y') }})
                        </h6>
                        <span class="badge bg-dark border border-secondary text-light">Periods 1 – 7</span>
                    </div>

                    <div class="row g-2">
                        @foreach($hourlyStatus as $pNum => $pData)
                        <div class="col-md-6 col-12">
                            <div class="period-card d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="badge bg-secondary me-1">P{{ $pNum }}</span>
                                    <strong class="text-white">{{ $pData['subject_name'] }}</strong>
                                    <small class="text-secondary d-block mt-0.5" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-book-open me-1"></i>{{ $pData['topic'] }}
                                    </small>
                                </div>
                                <span class="badge {{ $pData['badge_class'] }} px-2.5 py-1">
                                    {{ $pData['status'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Today's Assignments & Pending Tasks -->
                <div class="glass-card p-3">
                    <h6 class="fw-bold mb-3 text-warning border-bottom border-secondary border-opacity-25 pb-2">
                        <i class="fa-solid fa-list-check me-2"></i>Assignments & Scheduled Submissions
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-custom text-start align-middle">
                            <thead>
                                <tr>
                                    <th>Subject / Task</th>
                                    <th>Submission Title</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignments as $asgn)
                                <tr>
                                    <td class="fw-semibold text-info">{{ $asgn->subject_code ?? 'Subject' }}</td>
                                    <td class="text-white">{{ $asgn->title }}</td>
                                    <td>{{ \Carbon\Carbon::parse($asgn->due_date)->format('d M Y') }}</td>
                                    <td><span class="badge badge-amber">Pending Submission</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No pending assignments due today.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Tests & Staff/Tutor Remarks Feed -->
            <div class="col-lg-5">

                <!-- Scheduled Tests & Exam Dates -->
                <div class="glass-card p-3">
                    <h6 class="fw-bold mb-3 text-emerald border-bottom border-secondary border-opacity-25 pb-2">
                        <i class="fa-solid fa-file-pen me-2"></i>Scheduled Lab & Practical Tests
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle">
                            <thead>
                                <tr>
                                    <th>Test Identifier</th>
                                    <th>Date</th>
                                    <th>Max Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($practicalTests as $test)
                                <tr>
                                    <td class="fw-semibold text-white">{{ $test->test_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($test->test_date)->format('d M Y') }}</td>
                                    <td class="fw-bold text-cyan">{{ $test->max_marks }}M</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No practical series tests scheduled today.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tutor Remarks & Staff Comments Feed -->
                <div class="glass-card p-3">
                    <h6 class="fw-bold mb-3 text-purple border-bottom border-secondary border-opacity-25 pb-2">
                        <i class="fa-solid fa-comments me-2"></i>Tutor Remarks & Mentoring Comments
                    </h6>

                    <div class="space-y-3">
                        @forelse($mentoringNotes as $note)
                        <div class="p-2.5 rounded bg-dark border border-secondary border-opacity-25 mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <strong class="text-info" style="font-size: 0.8rem;">
                                    <i class="fa-solid fa-user-tie me-1"></i>{{ $note->faculty_name ?? 'Class Advisor' }}
                                </strong>
                                <small class="text-muted" style="font-size: 0.72rem;">
                                    {{ \Carbon\Carbon::parse($note->created_at)->format('d M Y, h:i A') }}
                                </small>
                            </div>
                            <p class="text-slate-300 mb-0 small" style="font-size: 0.82rem;">
                                {{ $note->comments ?? 'Regular academic counseling and attendance review conducted.' }}
                            </p>
                        </div>
                        @empty
                        <div class="p-3 text-center text-muted bg-dark rounded border border-secondary border-opacity-25">
                            <i class="fa-solid fa-check-circle me-1 text-success"></i> No critical remarks posted. Student is performing satisfactorily.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Share Link Box -->
                <div class="glass-card p-3 text-center">
                    <span class="text-secondary small fw-semibold d-block mb-2"><i class="fa-solid fa-share-nodes me-1"></i> Share Access Link via SMS / Copy</span>
                    <div class="input-group input-group-sm">
                        <input type="text" id="shareUrlInput" class="form-control bg-dark text-light border-secondary" value="{{ $smsShareUrl }}" readonly>
                        <button class="btn btn-outline-info" type="button" onclick="copyShareUrl()">
                            <i class="fa-solid fa-copy me-1"></i> Copy
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyShareUrl() {
            const input = document.getElementById('shareUrlInput');
            input.select();
            document.execCommand('copy');
            alert('Parent Access Link copied to clipboard!');
        }
    </script>
</body>
</html>
