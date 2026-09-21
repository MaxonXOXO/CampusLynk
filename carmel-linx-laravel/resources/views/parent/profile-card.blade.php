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
                            <span class="badge badge-reg badge-app">Reg: {{ $student->reg_no }}</span>
                            <span class="badge badge-sem badge-app">Sem {{ $student->semester }} ({{ $student->branch }})</span>
                            <span class="badge badge-status badge-app">
                                <i class="fa-solid fa-graduation-cap me-1"></i>{{ !empty($academicStatus) ? $academicStatus : 'Regular (Active)' }}
                            </span>
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

