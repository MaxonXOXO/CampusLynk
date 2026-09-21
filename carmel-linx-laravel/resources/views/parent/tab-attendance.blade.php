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
                    <div class="timeline-item {{ $pNum === 7 ? 'special-hour border-start border-4 border-purple' : strtolower(str_replace(' ', '-', $pData['status'])) }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge {{ $pNum === 7 ? 'bg-dark text-cyan border border-cyan' : 'bg-secondary' }}" style="font-size: 0.68rem;">P{{ $pNum }}</span>
                                    @if(isset($pData['time_slot']))
                                    <span class="text-secondary font-monospace" style="font-size: 0.72rem;">
                                        <i class="fa-regular fa-clock me-1 text-cyan"></i>{{ $pData['time_slot'] }}
                                    </span>
                                    @endif
                                </div>
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

                <!-- Leave Applications & History Report -->
                <div class="app-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.9rem;">
                            <i class="fa-solid fa-file-signature me-1 text-warning"></i> Ward Leave History & Applications
                        </h6>
                        <span class="badge bg-warning text-dark badge-app">
                            {{ count($leaveRecords ?? []) }} {{ count($leaveRecords ?? []) == 1 ? 'Record' : 'Records' }}
                        </span>
                    </div>

                    @forelse($leaveRecords as $leave)
                    <div class="p-2.5 rounded bg-dark border border-secondary border-opacity-25 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-white" style="font-size: 0.82rem;">
                                    <i class="fa-regular fa-calendar-minus me-1 text-warning"></i>{{ \Carbon\Carbon::parse($leave->leave_date)->format('d M Y') }}
                                </span>
                                <span class="badge bg-secondary" style="font-size: 0.65rem;">{{ $leave->no_of_days }} {{ $leave->no_of_days == 1 ? 'Day' : 'Days' }}</span>
                            </div>
                            <small class="text-secondary d-block" style="font-size: 0.72rem;">Reason: {{ $leave->reason }}</small>
                            <small class="text-slate-400 d-block mt-0.5" style="font-size: 0.68rem;">
                                {{ !empty($leave->parent_informed) ? '✓ Parent Informed Tutor' : 'Parent Not Informed' }}
                            </small>
                        </div>
                        <div class="text-end">
                            @if(strtolower($leave->status) === 'approved')
                                <span class="badge bg-success text-white badge-app">Approved</span>
                            @elseif(strtolower($leave->status) === 'rejected')
                                <span class="badge bg-danger text-white badge-app">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark badge-app">Pending</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-secondary bg-dark rounded border border-secondary border-opacity-25 small">
                        <i class="fa-solid fa-circle-check text-success me-1"></i> No leave applications submitted for this student.
                    </div>
                    @endforelse
                </div>

            </div>

