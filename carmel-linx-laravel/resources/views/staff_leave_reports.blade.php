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
        }
        .card-custom {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }
        .table-dark-custom {
            color: #e2e8f0;
        }
        .table-dark-custom th {
            background-color: #0f172a;
            color: #38bdf8;
            font-weight: 700;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }
        .table-dark-custom td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }
    </style>
</head>
<body class="p-3 p-md-4">
    <div class="container-fluid max-w-7xl">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h4 class="fw-bold text-white mb-0">
                    <i class="fa-solid fa-file-invoice-dollar text-info me-2"></i> Staff Leave Master Ledger & Report Center
                </h4>
                <small class="text-secondary">Multi-stage approval audit trail and department leave reports</small>
            </div>
            <div>
                <a href="/staff/mobile" class="btn btn-outline-light btn-sm rounded-pill px-3 me-2">
                    <i class="fa-solid fa-mobile-screen me-1"></i> Staff Mobile
                </a>
                <button onclick="window.print()" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-print me-1"></i> Print Ledger
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card-custom p-3 mb-4">
            <form method="GET" action="/staff/leave/reports" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold mb-1">Department</label>
                    <select name="department" class="form-select bg-dark text-white border-secondary">
                        <option value="">All Departments</option>
                        <option value="General" {{ request('department') == 'General' ? 'selected' : '' }}>General Science</option>
                        <option value="Civil" {{ request('department') == 'Civil' ? 'selected' : '' }}>Civil Engineering</option>
                        <option value="Mechanical" {{ request('department') == 'Mechanical' ? 'selected' : '' }}>Mechanical Engineering</option>
                        <option value="Electrical" {{ request('department') == 'Electrical' ? 'selected' : '' }}>Electrical Engineering</option>
                        <option value="Automobile" {{ request('department') == 'Automobile' ? 'selected' : '' }}>Automobile Engineering</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-secondary small fw-bold mb-1">Leave Type</label>
                    <select name="leave_type" class="form-select bg-dark text-white border-secondary">
                        <option value="">All Leave Types</option>
                        <option value="Casual Leave" {{ request('leave_type') == 'Casual Leave' ? 'selected' : '' }}>Casual Leave (CL)</option>
                        <option value="Duty Leave" {{ request('leave_type') == 'Duty Leave' ? 'selected' : '' }}>Duty Leave (DL)</option>
                        <option value="Medical Leave" {{ request('leave_type') == 'Medical Leave' ? 'selected' : '' }}>Medical Leave (ML)</option>
                        <option value="Loss of Pay" {{ request('leave_type') == 'Loss of Pay' ? 'selected' : '' }}>Loss of Pay (LOP)</option>
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
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-info text-dark font-bold w-100">
                        <i class="fa-solid fa-filter me-1"></i> Apply Filters
                    </button>
                    <a href="/staff/leave/reports" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>

        <!-- Ledger Table -->
        <div class="card-custom p-3">
            <div class="table-responsive">
                <table class="table table-dark-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Staff Member</th>
                            <th>Dept & Role</th>
                            <th>Type</th>
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
                                    <strong class="text-white d-block">{{ $leave->staff_name }}</strong>
                                    <small class="text-secondary font-mono">{{ $leave->staff_mobile }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $leave->department }}</span>
                                    <small class="text-slate-400 d-block">{{ $leave->designation }}</small>
                                </td>
                                <td class="fw-bold text-info">{{ $leave->leave_type }}</td>
                                <td>
                                    <small class="d-block">{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }} to {{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</small>
                                    <span class="badge bg-opacity-20 bg-warning text-warning">{{ $leave->session_type }}</span>
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
                </table>
            </div>
        </div>
    </div>
</body>
</html>
