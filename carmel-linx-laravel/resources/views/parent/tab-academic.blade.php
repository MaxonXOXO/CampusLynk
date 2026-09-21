            <!-- Tab Content 2: Ward Academic Status & Performance -->
            <div id="tab-academic" class="tab-pane d-none">
                <!-- Academic Overview Card -->
                <div class="app-card">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.95rem;">
                            <i class="fa-solid fa-award me-1 text-warning"></i> Ward Academic Status
                        </h6>
                        <span class="badge bg-success badge-app">
                            <i class="fa-solid fa-circle-check me-1"></i>{{ !empty($academicStatus) ? $academicStatus : 'Regular (Active)' }}
                        </span>
                    </div>

                    <div class="row g-2 text-center mb-3">
                        <div class="col-4">
                            <div class="p-2 rounded bg-dark border border-secondary border-opacity-25">
                                <small class="text-secondary d-block" style="font-size: 0.68rem;">CGPA Score</small>
                                <strong class="text-warning fs-5 fw-extrabold">{{ number_format($cgpa ?? 0.00, 2) }}</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-dark border border-secondary border-opacity-25">
                                <small class="text-secondary d-block" style="font-size: 0.68rem;">Current SGPA</small>
                                <strong class="text-cyan fs-5 fw-extrabold">{{ number_format($sgpa ?? 0.00, 2) }}</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-dark border border-secondary border-opacity-25">
                                <small class="text-secondary d-block" style="font-size: 0.68rem;">Activity Points</small>
                                <strong class="text-emerald-400 fs-5 fw-extrabold" style="color: #10b981;">{{ $activityPoints ?? 0 }} <span style="font-size: 0.7rem;">/ 100</span></strong>
                            </div>
                        </div>
                    </div>

                    @if(!empty($statusNotes))
                    <div class="p-2.5 rounded bg-dark border border-cyan border-opacity-25 mb-2">
                        <small class="text-cyan fw-bold d-block mb-1" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-info-circle me-1"></i> Academic Progress Note:
                        </small>
                        <p class="text-slate-200 mb-0" style="font-size: 0.8rem;">
                            {{ $statusNotes }}
                        </p>
                    </div>
                    @endif

                    <div class="p-2 rounded bg-success bg-opacity-10 border border-success border-opacity-25 d-flex align-items-center justify-content-between">
                        <span class="text-white small" style="font-size: 0.78rem;">
                            <i class="fa-solid fa-shield-check text-success me-1"></i> SBTE Examination Status
                        </span>
                        <span class="badge bg-success text-white badge-app">Eligible</span>
                    </div>
                </div>

                <!-- Subject-wise Internal Scores & CIE Evaluation -->
                <div class="app-card">
                    <h6 class="fw-bold text-cyan mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-book-open me-1"></i> Subject Internal & CIE Marks (Sem {{ $student->semester }})
                    </h6>

                    @forelse($subjectAcademicPerformance as $subj)
                    <div class="p-3 rounded bg-dark border border-secondary border-opacity-25 mb-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong class="text-white d-block" style="font-size: 0.88rem;">{{ $subj->subject_name }}</strong>
                                <span class="badge bg-secondary badge-app font-monospace me-1">{{ $subj->subject_code }}</span>
                                <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app">{{ $subj->subject_type }} ({{ $subj->credits }} Credits)</span>
                            </div>
                            @if($subj->board_grade)
                            <span class="badge bg-warning text-dark fw-bold badge-app fs-6">Grade: {{ $subj->board_grade }}</span>
                            @elseif($subj->total_internal)
                            <span class="badge bg-emerald text-white fw-bold badge-app" style="background-color: #10b981;">CIE: {{ $subj->total_internal }} Marks</span>
                            @endif
                        </div>

                        <!-- Series & Internal Marks Row -->
                        <div class="row g-2 mt-1 text-center">
                            <div class="col-3">
                                <div class="p-1.5 rounded bg-slate-900 border border-secondary border-opacity-25">
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">Series 1</small>
                                    <strong class="text-cyan" style="font-size: 0.78rem;">{{ $subj->series1 !== null ? $subj->series1 . ' / 20' : 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-1.5 rounded bg-slate-900 border border-secondary border-opacity-25">
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">Series 2</small>
                                    <strong class="text-cyan" style="font-size: 0.78rem;">{{ $subj->series2 !== null ? $subj->series2 . ' / 20' : 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-1.5 rounded bg-slate-900 border border-secondary border-opacity-25">
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">Assg 1</small>
                                    <strong class="text-warning" style="font-size: 0.78rem;">{{ $subj->assignment1 !== null ? $subj->assignment1 . ' / 10' : 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="p-1.5 rounded bg-slate-900 border border-secondary border-opacity-25">
                                    <small class="text-secondary d-block" style="font-size: 0.65rem;">Assg 2</small>
                                    <strong class="text-warning" style="font-size: 0.78rem;">{{ $subj->assignment2 !== null ? $subj->assignment2 . ' / 10' : 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-secondary text-center my-3 small">No subject performance records uploaded yet for this semester.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tab Content 3: Assignments & Tests -->
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

            <!-- Tab Content 4: Remarks & Comments -->
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
