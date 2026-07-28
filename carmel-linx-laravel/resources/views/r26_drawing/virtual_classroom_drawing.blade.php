<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }}) - Virtual Drawing Hall (R2026)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0b0f19;
            --bg-secondary: #111827;
            --bg-card: #1f2937;
            --bg-card-hover: #374151;
            --border-color: #374151;
            --accent-cyan: #06b6d4;
            --accent-blue: #3b82f6;
            --accent-indigo: #6366f1;
            --accent-purple: #8b5cf6;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        .navbar-custom {
            background-color: rgba(17, 24, 39, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
        }

        .glass-card {
            background: rgba(31, 41, 55, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(6, 182, 212, 0.4);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
        }

        .stat-card {
            padding: 1.25rem;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(31, 41, 55, 0.8) 0%, rgba(17, 24, 39, 0.9) 100%);
            border: 1px solid var(--border-color);
        }

        .nav-tabs-custom {
            border-bottom: 1px solid var(--border-color);
            gap: 0.5rem;
        }

        .nav-tabs-custom .nav-link {
            color: var(--text-muted);
            border: 1px solid transparent;
            border-radius: 8px 8px 0 0;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-tabs-custom .nav-link:hover {
            color: var(--text-main);
            background: rgba(55, 65, 81, 0.4);
        }

        .nav-tabs-custom .nav-link.active {
            color: #fff;
            background: var(--bg-card);
            border-color: var(--border-color) var(--border-color) transparent;
            border-top: 3px solid var(--accent-cyan);
        }

        .table-custom {
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .table-custom th {
            background-color: #111827;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }

        .table-custom td {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            vertical-align: middle;
        }

        .form-control-custom {
            background-color: #111827;
            border: 1px solid var(--border-color);
            color: #fff;
            border-radius: 6px;
        }

        .form-control-custom:focus {
            background-color: #1f2937;
            border-color: var(--accent-cyan);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(6, 182, 212, 0.25);
        }

        .badge-cyan { background-color: rgba(6, 182, 212, 0.15); color: var(--accent-cyan); border: 1px solid var(--accent-cyan); }
        .badge-emerald { background-color: rgba(16, 185, 129, 0.15); color: var(--accent-emerald); border: 1px solid var(--accent-emerald); }
        .badge-amber { background-color: rgba(245, 158, 11, 0.15); color: var(--accent-amber); border: 1px solid var(--accent-amber); }
        .badge-rose { background-color: rgba(244, 63, 94, 0.15); color: var(--accent-rose); border: 1px solid var(--accent-rose); }
        .badge-purple { background-color: rgba(139, 92, 246, 0.15); color: var(--accent-purple); border: 1px solid var(--accent-purple); }

        .rubric-input {
            width: 70px;
            text-align: center;
            font-weight: 600;
        }

        .btn-cyan {
            background-color: var(--accent-cyan);
            color: #000;
            font-weight: 600;
            border: none;
        }
        .btn-cyan:hover {
            background-color: #22d3ee;
            color: #000;
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-3">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/dashboard/lecturer">
                <i class="fa-solid fa-drafting-compass text-info fs-3"></i>
                <div>
                    <span class="fs-5 fw-bold brand-font">Carmel Linx</span>
                    <span class="badge badge-cyan ms-2">R2026 Drawing Hall</span>
                </div>
            </a>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-bold">{{ $batchSubject->subject_name }} ({{ $batchSubject->subject_code }})</div>
                    <small class="text-muted">{{ $classroom->classroom_id }} | Sem {{ $classroom->current_semester ?? 'I' }}</small>
                </div>
                <a href="/dashboard/lecturer" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Dashboard</a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container-fluid px-4 py-4">

        <!-- Top Banner -->
        <div class="glass-card p-4 mb-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge badge-cyan"><i class="fa-solid fa-flask me-1"></i> R2026 Lab Paper</span>
                        <span class="badge badge-purple"><i class="fa-solid fa-laptop-code me-1"></i> CAD & Drafting</span>
                        <span class="badge badge-emerald"><i class="fa-solid fa-clock me-1"></i> 45 Contact Hours</span>
                    </div>
                    <h2 class="fw-bold mb-1">{{ $drawingCourseFile->course_title ?? $batchSubject->subject_name }}</h2>
                    <p class="text-muted mb-0">Course Code: <strong>{{ $drawingCourseFile->course_code ?? $batchSubject->subject_code }}</strong> | Scheme L:T:P:R: <strong>{{ $drawingCourseFile->teaching_scheme }}</strong> | Credits: <strong>{{ $drawingCourseFile->credits }}</strong></p>
                </div>
                <div class="col-lg-5">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="stat-card text-center">
                                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">Continuous Internal Evaluation</small>
                                <span class="fs-3 fw-bold text-info">60 Marks</span>
                                <small class="d-block text-muted" style="font-size: 0.75rem;">Att (5) + CE (30) + CA (15) + OEE (10)</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card text-center">
                                <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.7rem;">End Semester Exam</small>
                                <span class="fs-3 fw-bold text-warning">40 Marks</span>
                                <small class="d-block text-muted" style="font-size: 0.75rem;">3-Hour Board CAD Practical</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-tabs-custom mb-4" id="drawingHallTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="tab-syllabus-link" data-bs-toggle="tab" data-bs-target="#tab-syllabus" type="button"><i class="fa-solid fa-file-pdf me-2 text-info"></i>Syllabus & Parser</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-lessonplan-link" data-bs-toggle="tab" data-bs-target="#tab-lessonplan" type="button"><i class="fa-solid fa-calendar-days me-2 text-warning"></i>Lesson Plan</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-ce-link" data-bs-toggle="tab" data-bs-target="#tab-ce" type="button"><i class="fa-solid fa-pen-ruler me-2 text-success"></i>Continuous Eval (CE 30M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-ca-link" data-bs-toggle="tab" data-bs-target="#tab-ca" type="button"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Practical Tests (15M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-oee-link" data-bs-toggle="tab" data-bs-target="#tab-oee" type="button"><i class="fa-solid fa-lightbulb me-2 text-amber"></i>Open-Ended (10M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-ese-link" data-bs-toggle="tab" data-bs-target="#tab-ese" type="button"><i class="fa-solid fa-desktop me-2 text-danger"></i>End Sem CAD Exam (40M)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-cie-link" data-bs-toggle="tab" data-bs-target="#tab-cie" type="button"><i class="fa-solid fa-chart-pie me-2 text-purple"></i>Consolidated CIE & Reports</button>
            </li>
        </ul>

        <!-- Tab Contents -->
        <div class="tab-content" id="drawingHallTabContent">

            <!-- TAB 1: SYLLABUS & PARSER -->
            <div class="tab-pane fade show active" id="tab-syllabus" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-cloud-arrow-up me-2 text-info"></i>Upload Drawing Syllabus PDF</h5>
                            <form id="uploadSyllabusForm" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Select Syllabus PDF File</label>
                                    <input type="file" class="form-control form-control-custom" name="syllabus_file" accept=".pdf" required>
                                </div>
                                <button type="submit" class="btn btn-cyan w-100" id="uploadBtn">
                                    <i class="fa-solid fa-gears me-1"></i> Parse & Extract Syllabus
                                </button>
                            </form>
                            <div class="mt-3 p-3 rounded" style="background: rgba(6,182,212,0.1); border: 1px dashed var(--accent-cyan);">
                                <small class="text-info d-block fw-semibold mb-1"><i class="fa-solid fa-circle-info me-1"></i> Auto Extraction Support</small>
                                <small class="text-muted">Parses Course Title, Code, L:T:P:R, Credits, CO1-CO4 descriptions, Bloom's levels, CO-PO Matrix, and Drawing Exercises automatically.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="glass-card p-4 mb-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-bullseye me-2 text-info"></i>Course Outcomes (COs)</h5>
                            <div class="row g-3">
                                @foreach($drawingCourseFile->parsed_cos ?? [] as $co)
                                <div class="col-md-6">
                                    <div class="p-3 rounded" style="background: #111827; border: 1px solid var(--border-color);">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge badge-cyan fw-bold">{{ $co['id'] }}</span>
                                            <span class="badge badge-purple">{{ $co['cognitive_level'] ?? 'Apply' }}</span>
                                        </div>
                                        <p class="small mb-0 text-light">{{ $co['description'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- CO-PO Matrix -->
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-table-cells me-2 text-warning"></i>CO-PO Articulation Matrix</h5>
                            <div class="table-responsive">
                                <table class="table table-custom table-bordered text-center align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>CO Tag</th>
                                            @for($p=1; $p<=11; $p++) <th>PO{{ $p }}</th> @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                        <tr>
                                            <th class="text-info">{{ $coTag }}</th>
                                            @for($p=1; $p<=11; $p++)
                                                @php $val = $mappings[$coTag]["PO{$p}"] ?? '-'; @endphp
                                                <td class="{{ $val != '-' ? 'fw-bold text-success' : 'text-muted' }}">{{ $val }}</td>
                                            @endfor
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: LESSON PLAN -->
            <div class="tab-pane fade" id="tab-lessonplan" role="tabpanel">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-list-check me-2 text-warning"></i>45-Hour Drawing Lab Lesson Plan</h5>
                            <small class="text-muted">Sequenced practical sessions covering Manual Drawing & CAD Drafting</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Hour</th>
                                    <th>Topic & Exercise Content</th>
                                    <th>Mapped CO</th>
                                    <th>Taxonomy</th>
                                    <th>Pedagogy</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lessonPlans as $lp)
                                <tr>
                                    <td class="fw-bold text-center text-info">#{{ $lp->day_no }}</td>
                                    <td>{{ $lp->topic_content }}</td>
                                    <td><span class="badge badge-cyan">{{ $lp->co_tag }}</span></td>
                                    <td><span class="badge badge-purple">{{ $lp->taxonomy }}</span></td>
                                    <td><small class="text-muted">{{ $lp->pedagogy }}</small></td>
                                    <td>
                                        <span class="badge {{ $lp->status === 'Completed' ? 'badge-emerald' : 'badge-amber' }}">
                                            {{ $lp->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: CONTINUOUS EVALUATION (CE - 30 MARKS) -->
            <div class="tab-pane fade" id="tab-ce" role="tabpanel">
                <div class="glass-card p-4">
                    <div class="row align-items-center g-3 mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-pen-ruler me-2 text-success"></i>Continuous Practical Evaluation (CE)</h5>
                            <small class="text-muted">Rubric scoring out of 50 per slot -> Converted to 30 CIE Marks</small>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-control-custom" id="ceExerciseSelect">
                                @foreach($drawingCourseFile->parsed_exercises ?? [] as $ex)
                                <option value="{{ $ex['exercise_no'] }}">{{ $ex['exercise_no'] }}: {{ $ex['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-cyan w-100" id="saveCeBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save CE</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="ceTable">
                            <thead>
                                <tr>
                                    <th>Roll</th>
                                    <th>Reg No</th>
                                    <th>Student Name</th>
                                    <th title="Attendance & Pre-lab (10)">Prep (10)</th>
                                    <th title="Setup & Procedure (10)">Setup (10)</th>
                                    <th title="Observation & Recording (5)">Obs (5)</th>
                                    <th title="Analysis & Dimensioning (10)">Anal (10)</th>
                                    <th title="Viva Voce (10)">Viva (10)</th>
                                    <th title="Workmanship & Line Quality (5)">Work (5)</th>
                                    <th>Total (50)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                @php
                                    $stEval = isset($slotEvals[$st->reg_no]) ? $slotEvals[$st->reg_no]->first() : null;
                                @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="fw-bold text-center">{{ $st->roll_no }}</td>
                                    <td><small class="text-muted">{{ $st->reg_no }}</small></td>
                                    <td class="fw-semibold">{{ $st->name }}</td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input p1" value="{{ $stEval->prep_punctuality ?? 0 }}" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input p2" value="{{ $stEval->setup_procedure ?? 0 }}" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input p3" value="{{ $stEval->observation_recording ?? 0 }}" max="5" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input p4" value="{{ $stEval->analysis_interpretation ?? 0 }}" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input p5" value="{{ $stEval->viva_voce ?? 0 }}" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input p6" value="{{ $stEval->workmanship_discipline ?? 0 }}" max="5" min="0" step="0.5"></td>
                                    <td class="fw-bold text-info total-50">{{ $stEval->total_score_50 ?? '0.00' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 4: PRACTICAL TESTS (CA1 & CA2 - 15 MARKS) -->
            <div class="tab-pane fade" id="tab-ca" role="tabpanel">
                <div class="glass-card p-4">
                    <div class="row align-items-center g-3 mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Practical Series Tests (CA1 & CA2)</h5>
                            <small class="text-muted">CA1 (Descriptive - Wk 7) & CA2 (CAD Practical - Wk 14) out of 40 each -> Converted to 15 CIE Marks</small>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-control-custom" id="caTestSelect">
                                <option value="CA1">CA1: Descriptive Drawing Test (Module I & II - Max 40)</option>
                                <option value="CA2">CA2: CAD Practical Test (Module III & IV - Max 40)</option>
                            </select>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-cyan w-100" id="saveCaBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save Test</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="caTable">
                            <thead>
                                <tr>
                                    <th>Roll</th>
                                    <th>Reg No</th>
                                    <th>Student Name</th>
                                    <th>Procedure (10)</th>
                                    <th>Execution (10)</th>
                                    <th>Output (8)</th>
                                    <th>Viva (8)</th>
                                    <th>Record (4)</th>
                                    <th>Total (40)</th>
                                    <th>Absent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="fw-bold text-center">{{ $st->roll_no }}</td>
                                    <td><small class="text-muted">{{ $st->reg_no }}</small></td>
                                    <td class="fw-semibold">{{ $st->name }}</td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input ca-w" value="0" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input ca-s" value="0" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input ca-o" value="0" max="8" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input ca-v" value="0" max="8" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input ca-r" value="0" max="4" min="0" step="0.5"></td>
                                    <td class="fw-bold text-warning ca-total-40">0.00</td>
                                    <td><input type="checkbox" class="form-check-input ca-absent"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 5: OPEN-ENDED EXPERIMENT (OEE - 10 MARKS) -->
            <div class="tab-pane fade" id="tab-oee" role="tabpanel">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-lightbulb me-2 text-amber"></i>Open-Ended Experiment (OEE)</h5>
                            <small class="text-muted">Drawing sheets from Modules I & II evaluated as Open-Ended Project (Max 50 -> 10 CIE Marks)</small>
                        </div>
                        <button class="btn btn-cyan" id="saveOeeBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save OEE</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="oeeTable">
                            <thead>
                                <tr>
                                    <th>Roll</th>
                                    <th>Reg No</th>
                                    <th>Student Name</th>
                                    <th>Originality (10)</th>
                                    <th>Objectives (10)</th>
                                    <th>Execution (10)</th>
                                    <th>Analysis (10)</th>
                                    <th>Teamwork (10)</th>
                                    <th>Total (50)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                @php $stOee = $oeeEvals[$st->reg_no] ?? null; @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="fw-bold text-center">{{ $st->roll_no }}</td>
                                    <td><small class="text-muted">{{ $st->reg_no }}</small></td>
                                    <td class="fw-semibold">{{ $st->name }}</td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input oee-m1" value="{{ $stOee->originality_relevance ?? 0 }}" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input oee-m2" value="{{ $stOee->objectives_plan ?? 0 }}" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input oee-m3" value="{{ $stOee->execution_recording ?? 0 }}" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input oee-m4" value="{{ $stOee->analysis_presentation ?? 0 }}" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input oee-m5" value="{{ $stOee->teamwork_innovation ?? 0 }}" max="10" min="0" step="0.5"></td>
                                    <td class="fw-bold text-amber oee-total-50">{{ $stOee->total_score_50 ?? '0.00' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 6: END SEMESTER EXAM (ESE - 40 MARKS) -->
            <div class="tab-pane fade" id="tab-ese" role="tabpanel">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-desktop me-2 text-danger"></i>End Semester CAD Practical Exam (ESE)</h5>
                            <small class="text-muted">Board Examination in CAD: Part A MCQ (10) + Part B CAD (18) + Part C Viva (8) + Part D Record (4) = 40 Marks</small>
                        </div>
                        <button class="btn btn-cyan" id="saveEseBtn"><i class="fa-solid fa-floppy-disk me-1"></i> Save ESE Marks</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0" id="eseTable">
                            <thead>
                                <tr>
                                    <th>Roll</th>
                                    <th>Reg No</th>
                                    <th>Student Name</th>
                                    <th>Part A: MCQ (10)</th>
                                    <th>Part B: CAD (18)</th>
                                    <th>Part C: Viva (8)</th>
                                    <th>Part D: Record (4)</th>
                                    <th>Total ESE (40)</th>
                                    <th>Absent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $st)
                                @php $stEse = $eseMarks[$st->reg_no] ?? null; @endphp
                                <tr data-reg-no="{{ $st->reg_no }}">
                                    <td class="fw-bold text-center">{{ $st->roll_no }}</td>
                                    <td><small class="text-muted">{{ $st->reg_no }}</small></td>
                                    <td class="fw-semibold">{{ $st->name }}</td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input ese-pa" value="{{ $stEse->part_a_mcq ?? 0 }}" max="10" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input ese-pb" value="{{ $stEse->part_b_cad ?? 0 }}" max="18" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input ese-pc" value="{{ $stEse->part_c_viva ?? 0 }}" max="8" min="0" step="0.5"></td>
                                    <td><input type="number" class="form-control form-control-custom rubric-input ese-pd" value="{{ $stEse->part_d_record ?? 0 }}" max="4" min="0" step="0.5"></td>
                                    <td class="fw-bold text-danger ese-total-40">{{ $stEse->total_ese_40 ?? '0.00' }}</td>
                                    <td><input type="checkbox" class="form-check-input ese-absent" {{ ($stEse && $stEse->is_absent) ? 'checked' : '' }}></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 7: CONSOLIDATED CIE, SURVEYS & REPORTS -->
            <div class="tab-pane fade" id="tab-cie" role="tabpanel">
                <div class="glass-card p-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-chart-pie me-2 text-purple"></i>Consolidated Course Score Sheet</h5>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Roll</th>
                                    <th>Reg No</th>
                                    <th>Student Name</th>
                                    <th>Att (5)</th>
                                    <th>CE (30)</th>
                                    <th>Tests (15)</th>
                                    <th>OEE (10)</th>
                                    <th>Total CIE (60)</th>
                                    <th>ESE CAD (40)</th>
                                    <th>Total Marks (100)</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentResults as $r)
                                <tr>
                                    <td class="fw-bold text-center">{{ $r['roll_no'] }}</td>
                                    <td><small class="text-muted">{{ $r['reg_no'] }}</small></td>
                                    <td class="fw-semibold">{{ $r['name'] }}</td>
                                    <td>{{ $r['att_marks'] }}</td>
                                    <td>{{ $r['ce_marks'] }}</td>
                                    <td>{{ $r['practical_test_marks'] }}</td>
                                    <td>{{ $r['oee_marks'] }}</td>
                                    <td class="fw-bold text-info">{{ $r['total_cie_marks'] }}</td>
                                    <td class="fw-bold text-warning">{{ $r['total_ese'] }}</td>
                                    <td class="fw-bold fs-6 text-light">{{ $r['total_course_marks'] }}</td>
                                    <td>
                                        <span class="badge {{ $r['is_passed'] ? 'badge-emerald' : 'badge-rose' }}">
                                            {{ $r['is_passed'] ? 'PASSED' : 'FAILED' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Surveys & CO-PO Attainment -->
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-poll me-2 text-info"></i>Indirect Attainment via Surveys</h5>
                            <div class="p-3 rounded mb-3" style="background: #111827; border: 1px solid var(--border-color);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Course Exit Survey</span>
                                    <span class="badge {{ $exitSurvey ? 'badge-emerald' : 'badge-amber' }}">{{ $exitSurvey ? 'Active / Conducted' : 'Not Initiated' }}</span>
                                </div>
                                <small class="text-muted d-block mt-1">Responses Collected: {{ $exitSurveyResponses->count() }}</small>
                            </div>
                            <div class="p-3 rounded" style="background: #111827; border: 1px solid var(--border-color);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Mid-Semester Survey</span>
                                    <span class="badge {{ $midSemSurvey ? 'badge-emerald' : 'badge-amber' }}">{{ $midSemSurvey ? 'Active / Conducted' : 'Not Initiated' }}</span>
                                </div>
                                <small class="text-muted d-block mt-1">Responses Collected: {{ $midSemResponses->count() }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3"><i class="fa-solid fa-award me-2 text-warning"></i>CO Attainment Levels (80% Direct + 20% Indirect)</h5>
                            <div class="table-responsive">
                                <table class="table table-custom table-bordered text-center align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>CO Tag</th>
                                            <th>Direct (80%)</th>
                                            <th>Indirect (20%)</th>
                                            <th>Final Attainment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(['CO1', 'CO2', 'CO3', 'CO4'] as $coTag)
                                        <tr>
                                            <th class="text-info">{{ $coTag }}</th>
                                            <td>{{ $directStats[$coTag]['level'] ?? 0.0 }}</td>
                                            <td>{{ $indirectStats[$coTag]['level'] ?? 0.0 }}</td>
                                            <td class="fw-bold text-success">{{ $combinedStats[$coTag] ?? 0.0 }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subjectId = {{ $batchSubject->id }};

        // Upload Syllabus PDF
        document.getElementById('uploadSyllabusForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = document.getElementById('uploadBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Parsing PDF...';

            try {
                const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/syllabus`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await res.json();
                if(data.status === 'SUCCESS') {
                    alert('Syllabus uploaded and parsed successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(err) {
                alert('Parsing failed: ' + err.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-gears me-1"></i> Parse & Extract Syllabus';
            }
        });

        // Auto sum for CE
        document.querySelectorAll('#ceTable tbody tr').forEach(tr => {
            tr.querySelectorAll('.rubric-input').forEach(input => {
                input.addEventListener('input', () => {
                    let sum = 0;
                    tr.querySelectorAll('.rubric-input').forEach(inp => sum += parseFloat(inp.value || 0));
                    tr.querySelector('.total-50').textContent = sum.toFixed(2);
                });
            });
        });

        // Save CE
        document.getElementById('saveCeBtn').addEventListener('click', async () => {
            const exNo = document.getElementById('ceExerciseSelect').value;
            const marksData = [];
            document.querySelectorAll('#ceTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    prep_punctuality: tr.querySelector('.p1').value,
                    setup_procedure: tr.querySelector('.p2').value,
                    observation_recording: tr.querySelector('.p3').value,
                    analysis_interpretation: tr.querySelector('.p4').value,
                    viva_voce: tr.querySelector('.p5').value,
                    workmanship_discipline: tr.querySelector('.p6').value
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/slot`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ exercise_no: exNo, marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });

        // Save Practical Test (CA1 & CA2)
        document.getElementById('saveCaBtn').addEventListener('click', async () => {
            const testNo = document.getElementById('caTestSelect').value;
            const marksData = [];
            document.querySelectorAll('#caTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    writeup_procedure: tr.querySelector('.ca-w').value,
                    setup_execution: tr.querySelector('.ca-s').value,
                    observation_result: tr.querySelector('.ca-o').value,
                    viva_voce: tr.querySelector('.ca-v').value,
                    record_completion: tr.querySelector('.ca-r').value,
                    is_absent: tr.querySelector('.ca-absent').checked ? 1 : 0
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/practical-test`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ test_no: testNo, marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });

        // Save OEE
        document.getElementById('saveOeeBtn').addEventListener('click', async () => {
            const marksData = [];
            document.querySelectorAll('#oeeTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    originality_relevance: tr.querySelector('.oee-m1').value,
                    objectives_plan: tr.querySelector('.oee-m2').value,
                    execution_recording: tr.querySelector('.oee-m3').value,
                    analysis_presentation: tr.querySelector('.oee-m4').value,
                    teamwork_innovation: tr.querySelector('.oee-m5').value
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/oee`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });

        // Save ESE
        document.getElementById('saveEseBtn').addEventListener('click', async () => {
            const marksData = [];
            document.querySelectorAll('#eseTable tbody tr').forEach(tr => {
                marksData.push({
                    reg_no: tr.dataset.regNo,
                    part_a_mcq: tr.querySelector('.ese-pa').value,
                    part_b_cad: tr.querySelector('.ese-pb').value,
                    part_c_viva: tr.querySelector('.ese-pc').value,
                    part_d_record: tr.querySelector('.ese-pd').value,
                    is_absent: tr.querySelector('.ese-absent').checked ? 1 : 0
                });
            });

            const res = await fetch(`/api/r26/classroom/drawing/${subjectId}/evaluate/ese`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ marks_data: marksData })
            });
            const data = await res.json();
            alert(data.message);
        });
    </script>
</body>
</html>
