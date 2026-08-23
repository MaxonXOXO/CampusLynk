<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Leave Master Report & Ledger - Carmel Linx</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            font-family: system-ui, -apple-system, sans-serif;
            font-size: 0.8rem;
        }
        .card-custom {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }
        .table-dark-custom {
            color: #e2e8f0;
            font-size: 0.75rem;
        }
        .table-dark-custom th {
            background-color: #0f172a;
            color: #38bdf8;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.4rem 0.55rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }
        .table-dark-custom td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
            padding: 0.4rem 0.55rem;
            font-size: 0.75rem;
        }
        .form-select, .form-control {
            font-size: 0.75rem !important;
            padding: 0.3rem 0.55rem;
        }
        .badge {
            font-size: 0.65rem;
            padding: 0.25em 0.5em;
        }
        .btn-sm {
            font-size: 0.72rem;
            padding: 0.2rem 0.55rem;
        }
        @media print {
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
                font-size: 9pt !important;
            }
            .card-custom {
                background: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                color: #000000 !important;
                box-shadow: none !important;
            }
            .table-dark-custom {
                color: #000000 !important;
                font-size: 8.5pt !important;
            }
            .table-dark-custom th {
                background-color: #f1f5f9 !important;
                color: #0f172a !important;
                border-bottom: 2px solid #000000 !important;
            }
            .table-dark-custom td {
                border-bottom: 1px solid #e2e8f0 !important;
            }
            .btn, form, button, .no-print {
                display: none !important;
            }
            .text-white {
                color: #000000 !important;
            }
            .text-secondary, .text-slate-400 {
                color: #475569 !important;
            }
        }
    </style>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body class="p-3 p-md-4">
    <div class="container-fluid max-w-7xl">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h5 class="fw-bold text-white mb-0" style="font-size: 1.1rem;">
                    <i class="fa-solid fa-file-invoice-dollar text-info me-2"></i> Staff Leave Master Ledger & Report Center
                </h5>
                <small class="text-secondary" style="font-size: 0.76rem;">Multi-stage approval audit trail and department leave reports</small>
            </div>
            <div>
                @if(Session::get('userRole') === 'HOD')
                    <a href="/dashboard/hod" class="btn btn-outline-light btn-sm rounded-pill px-3 me-2">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to HOD Console
                    </a>
                @elseif(in_array(Session::get('userRole'), ['Principal', 'Super_Admin', 'Admin']))
                    <a href="/dashboard/superadmin" class="btn btn-outline-light btn-sm rounded-pill px-3 me-2">
                        <i class="fa-solid fa-arrow-left me-1"></i> Control Desk
                    </a>
                @else
                    <a href="/staff/mobile?mode=mobile" class="btn btn-outline-light btn-sm rounded-pill px-3 me-2">
                        <i class="fa-solid fa-calendar-check me-1"></i> My Leave Portal
                    </a>
                @endif
                <button onclick="window.print()" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-print me-1"></i> Print Ledger
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card-custom p-3 mb-4">
            <form method="GET" action="/staff/leave/reports" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label text-secondary small fw-bold mb-1">Academic Year</label>
                    <select name="academic_year" class="form-select bg-dark text-white border-secondary">
                        @foreach([date('Y'), date('Y')-1, date('Y')-2] as $yr)
                            <option value="{{ $yr }}" {{ ($academicYear ?? date('Y')) == $yr ? 'selected' : '' }}>{{ $yr }} - {{ $yr+1 }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold mb-1">Department</label>
                    @if(Session::get('userRole') === 'HOD')
                        <input type="text" class="form-control bg-dark text-info border-secondary fw-bold" value="{{ Session::get('userBranch') }} Department (HOD Ledger)" disabled readonly>
                        <input type="hidden" name="department" value="{{ Session::get('userBranch') }}">
                    @else
                        <select name="department" class="form-select bg-dark text-white border-secondary">
                            <option value="">All Departments</option>
                            <option value="EL" {{ request('department') == 'EL' || request('department') == 'Electronics' ? 'selected' : '' }}>Electronics (EL)</option>
                            <option value="ME" {{ request('department') == 'ME' || request('department') == 'Mechanical' ? 'selected' : '' }}>Mechanical (ME)</option>
                            <option value="CE" {{ request('department') == 'CE' || request('department') == 'Civil' ? 'selected' : '' }}>Civil (CE)</option>
                            <option value="EEE" {{ request('department') == 'EEE' || request('department') == 'Electrical' ? 'selected' : '' }}>Electrical (EEE)</option>
                            <option value="CT" {{ request('department') == 'CT' || request('department') == 'Computer' ? 'selected' : '' }}>Computer (CT)</option>
                            <option value="AU" {{ request('department') == 'AU' || request('department') == 'Automobile' ? 'selected' : '' }}>Automobile (AU)</option>
                            <option value="General" {{ request('department') == 'General' ? 'selected' : '' }}>General Science</option>
                        </select>
                    @endif
                </div>
                <div class="col-md-2">
                    <label class="form-label text-secondary small fw-bold mb-1">Leave Type</label>
                    <select name="leave_type" class="form-select bg-dark text-white border-secondary">
                        <option value="">All Leave Types</option>
                        <option value="Casual Leave" {{ request('leave_type') == 'Casual Leave' ? 'selected' : '' }}>Casual Leave (CL)</option>
                        <option value="Compensatory Casual Leave" {{ request('leave_type') == 'Compensatory Casual Leave' ? 'selected' : '' }}>Compensatory Casual Leave (CCL)</option>
                        <option value="Duty Leave" {{ request('leave_type') == 'Duty Leave' ? 'selected' : '' }}>Duty Leave (DL)</option>
                        <option value="Medical Leave" {{ request('leave_type') == 'Medical Leave' ? 'selected' : '' }}>Medical Leave (ML)</option>
                        <option value="Loss of Pay" {{ request('leave_type') == 'Loss of Pay' ? 'selected' : '' }}>Loss of Pay (LOP)</option>
                        <option value="Special Leave" {{ request('leave_type') == 'Special Leave' ? 'selected' : '' }}>Special Leave (SL)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold mb-1">Overall Status</label>
                    <select name="status" class="form-select bg-dark text-white border-secondary">
                        <option value="">All Statuses</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Final Approved</option>
                        <option value="Pending_HOD" {{ request('status') == 'Pending_HOD' ? 'selected' : '' }}>Pending HOD</option>
                        <option value="Pending_Coordinator" {{ request('status') == 'Pending_Coordinator' ? 'selected' : '' }}>Pending Coordinator</option>
                        <option value="Pending_Principal" {{ request('status') == 'Pending_Principal' ? 'selected' : '' }}>Pending Principal</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-info text-dark font-bold w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    <a href="/staff/leave/reports" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>

        <!-- Ledger Table -->
        <div class="card-custom p-3 mb-4">
            <div class="table-responsive">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Staff Member</th>
                            <th>Dept & Role</th>
                            <th>Type (Category)</th>
                            <th>Duration / Session</th>
                            <th>Days</th>
                            <th>HOD</th>
                            <th>Coord.</th>
                            <th>Principal</th>
                            <th>Overall</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td class="font-mono text-cyan fw-bold">{{ $leave->leave_code }}</td>
                                <td>
                                    <strong class="text-dark d-block" style="color: #0f172a !important;">{{ $leave->staff_name }}</strong>
                                    <small class="text-secondary font-mono">{{ $leave->staff_mobile }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $leave->department }}</span>
                                    <small class="text-slate-400 d-block">{{ $leave->designation }}</small>
                                </td>
                                <td>
                                    <strong class="text-info d-block">{{ $leave->leave_type }}</strong>
                                    @if($leave->ccl_date)
                                        <small class="text-teal-400 font-mono d-block" style="font-size: 0.72rem;">CCL Worked: {{ \Carbon\Carbon::parse($leave->ccl_date)->format('d M Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <small class="d-block">{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }} to {{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</small>
                                    <span class="badge bg-warning text-dark fw-bold">{{ $leave->session_type }}</span>
                                </td>
                                <td class="fw-bold text-white">{{ number_format($leave->total_days, 1) }}</td>
                                <td>
                                    @if($leave->hod_status === 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->hod_status === 'Rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->coordinator_status === 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->coordinator_status === 'Rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif($leave->coordinator_status === 'N/A')
                                        <span class="badge bg-secondary" title="Aided Stream - Not Applicable">N/A (Aided)</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->principal_status === 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($leave->principal_status === 'Rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->overall_status === 'Approved')
                                        <span class="badge bg-success px-2.5 py-1">Final Approved</span>
                                    @elseif($leave->overall_status === 'Rejected')
                                        <span class="badge bg-danger px-2.5 py-1">Rejected</span>
                                    @else
                                        <span class="badge bg-info text-dark px-2.5 py-1">{{ str_replace('_', ' ', $leave->overall_status) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="/staff/leave/{{ $leave->id }}/pdf" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-2.5">
                                        <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-secondary">
                                    No staff leave records found matching the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(isset($summary))
                    <tfoot class="border-top border-secondary">
                        <tr class="fw-bold text-white bg-slate-900">
                            <td colspan="5" class="text-end">TOTAL DAYS IN SELECTION:</td>
                            <td class="text-info font-mono" style="font-size: 1.05rem;">{{ number_format($summary['TOTAL_DAYS'], 1) }}</td>
                            <td colspan="5"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- Academic Year Category Summary Cards -->
        @if(isset($summary))
        <div class="card-custom p-3">
            <h6 class="fw-bold text-white mb-3" style="font-size: 0.88rem;">
                <i class="fa-solid fa-chart-pie text-info me-2"></i> Academic Year {{ $academicYear ?? date('Y') }} Leave Category Summary Totals
            </h6>
            <div class="row g-3 text-center">
                <div class="col-6 col-sm-4 col-md-2">
                    <div class="p-2.5 rounded-3 bg-dark border border-secondary border-opacity-25">
                        <small class="text-secondary fw-bold d-block mb-1" style="font-size: 0.72rem;">CL (Casual Leave)</small>
                        <span class="font-mono fw-bold text-info" style="font-size: 0.88rem;">Total: 15 <small class="text-white">(Taken: {{ number_format($summary['CL'], 1) }})</small></span>
                        <small class="text-slate-400 d-block" style="font-size: 0.68rem;">Days</small>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <div class="p-2.5 rounded-3 bg-dark border border-secondary border-opacity-25">
                        <small class="text-secondary fw-bold d-block mb-1" style="font-size: 0.72rem;">CCL (Compensatory)</small>
                        <span class="font-mono fw-bold text-teal-400" style="font-size: 0.95rem;">{{ number_format($summary['CCL'], 1) }}</span>
                        <small class="text-slate-400 d-block" style="font-size: 0.68rem;">Days</small>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <div class="p-2.5 rounded-3 bg-dark border border-secondary border-opacity-25">
                        <small class="text-secondary fw-bold d-block mb-1" style="font-size: 0.72rem;">DL (Duty Leave)</small>
                        <span class="font-mono fw-bold text-warning" style="font-size: 0.95rem;">{{ number_format($summary['DL'], 1) }}</span>
                        <small class="text-slate-400 d-block" style="font-size: 0.68rem;">Days</small>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <div class="p-2.5 rounded-3 bg-dark border border-secondary border-opacity-25">
                        <small class="text-secondary fw-bold d-block mb-1" style="font-size: 0.72rem;">ML (Medical)</small>
                        <span class="font-mono fw-bold text-primary" style="font-size: 0.95rem;">{{ number_format($summary['ML'], 1) }}</span>
                        <small class="text-slate-400 d-block" style="font-size: 0.68rem;">Days</small>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <div class="p-2.5 rounded-3 bg-dark border border-secondary border-opacity-25">
                        <small class="text-secondary fw-bold d-block mb-1" style="font-size: 0.72rem;">LOP (Loss of Pay)</small>
                        <span class="font-mono fw-bold text-danger" style="font-size: 0.95rem;">{{ number_format($summary['LOP'], 1) }}</span>
                        <small class="text-slate-400 d-block" style="font-size: 0.68rem;">Days</small>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2">
                    <div class="p-2.5 rounded-3 bg-dark border border-secondary border-opacity-25">
                        <small class="text-secondary fw-bold d-block mb-1" style="font-size: 0.72rem;">SL / Others</small>
                        <span class="font-mono fw-bold text-purple-400" style="font-size: 0.95rem;">{{ number_format($summary['SL'] + $summary['OTHERS'], 1) }}</span>
                        <small class="text-slate-400 d-block" style="font-size: 0.68rem;">Days</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
