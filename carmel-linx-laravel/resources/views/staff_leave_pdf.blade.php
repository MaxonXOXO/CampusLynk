<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Leave Application - {{ $leave->leave_code }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            font-size: 13px;
            line-height: 1.5;
            background: #fff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-subtitle {
            font-size: 13px;
            color: #475569;
            font-weight: 600;
        }
        .doc-title-badge {
            display: inline-block;
            background: #0f172a;
            color: #ffffff;
            padding: 6px 16px;
            font-size: 14px;
            font-weight: 800;
            border-radius: 4px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-grid td {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            font-size: 12.5px;
        }
        .info-grid .label {
            background-color: #f8fafc;
            font-weight: 700;
            color: #334155;
            width: 25%;
        }
        .section-header {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
            border-left: 4px solid #2563eb;
            padding-left: 10px;
            margin-top: 25px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-custom th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 700;
            font-size: 12px;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #0f172a;
        }
        .table-custom td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            font-size: 12px;
        }
        .stamp-box {
            border: 2px dashed #94a3b8;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            background: #fafafa;
            min-height: 110px;
        }
        .stamp-title {
            font-weight: 800;
            font-size: 11px;
            color: #475569;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .badge-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .badge-approved { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-pending { background: #fef9c3; color: #854d0e; border: 1px solid #fde047; }
        .badge-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <!-- Printable Header / Actions -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
        <button onclick="window.close(); if(!window.closed){ window.history.back(); }" style="background: #475569; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 6px; cursor: pointer;">
            ✖ Close Window
        </button>
        <button onclick="window.print()" style="background: #2563eb; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 6px; cursor: pointer;">
            🖨️ Print / Download PDF
        </button>
    </div>

    <!-- Institutional Header -->
    <table class="header-table">
        <tr>
            <td style="width: 70px; vertical-align: middle;">
                <div style="width: 55px; height: 55px; background: #0f172a; color: white; border-radius: 10px; font-weight: 900; font-size: 22px; display: flex; align-items: center; justify-content: center; text-align: center; line-height: 55px;">
                    CL
                </div>
            </td>
            <td style="vertical-align: middle;">
                <div class="header-title">Carmel Polytechnic College</div>
                <div class="header-subtitle">Carmel Linx Academic Portal & Staff Governance</div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
                <div style="font-size: 12px; font-weight: 700; color: #64748b;">APPLICATION NO:</div>
                <div style="font-size: 15px; font-weight: 800; color: #0f172a;">{{ $leave->leave_code }}</div>
            </td>
        </tr>
    </table>

    <div style="text-align: center;">
        <div class="doc-title-badge">Staff Leave Application Form</div>
    </div>

    <!-- Staff Information -->
    <div class="section-header">1. Applicant Staff Profile</div>
    <table class="info-grid">
        <tr>
            <td class="label">Staff Name:</td>
            <td style="font-weight: 700;">{{ $leave->staff_name }}</td>
            <td class="label">Staff ID / Mobile:</td>
            <td style="font-family: monospace;">{{ $leave->staff_mobile }}</td>
        </tr>
        <tr>
            <td class="label">Designation:</td>
            <td>{{ $leave->designation }}</td>
            <td class="label">Department:</td>
            <td>{{ $leave->department }}</td>
        </tr>
    </table>

    <!-- Leave Details -->
    <div class="section-header">2. Leave Schedule & Reason</div>
    <table class="info-grid">
        <tr>
            <td class="label">Leave Type:</td>
            <td style="font-weight: 700; color: #2563eb;">{{ $leave->leave_type }}</td>
            <td class="label">Session Type:</td>
            <td><strong style="color: #d97706;">{{ $leave->session_type }}</strong></td>
        </tr>
        <tr>
            <td class="label">From Date:</td>
            <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d-M-Y (l)') }}</td>
            <td class="label">To Date:</td>
            <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d-M-Y (l)') }}</td>
        </tr>
        @if($leave->ccl_date || str_contains($leave->leave_type, 'Compensatory') || str_contains($leave->leave_type, 'CCL'))
        <tr>
            <td class="label">CCL Date (Duty Worked):</td>
            <td colspan="3"><strong style="color: #0d9488;">{{ $leave->ccl_date ? \Carbon\Carbon::parse($leave->ccl_date)->format('d-M-Y (l)') : 'N/A' }}</strong></td>
        </tr>
        @endif
        <tr>
            <td class="label">Total Working Days:</td>
            <td colspan="3"><strong style="font-size: 14px;">{{ number_format($leave->total_days, 1) }} Day(s)</strong></td>
        </tr>
        <tr>
            <td class="label">Reason for Absence:</td>
            <td colspan="3" style="font-style: italic;">{{ $leave->reason }}</td>
        </tr>
    </table>

    <!-- Work Arrangement -->
    <div class="section-header">3. Work Arrangement & Substitution Details</div>
    @if(!empty($leave->work_arrangement) && is_array($leave->work_arrangement) && count($leave->work_arrangement) > 0)
        <table class="table-custom">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Period / Hour</th>
                    <th>Classroom / Batch</th>
                    <th>Substitute Staff Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leave->work_arrangement as $index => $arr)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $arr['date'] ?? '-' }}</td>
                        <td>{{ $arr['period'] ?? '-' }}</td>
                        <td>{{ $arr['classroom'] ?? '-' }}</td>
                        <td><strong>{{ $arr['substitute_name'] ?? 'Assigned Staff' }}</strong></td>
                        <td><span class="badge-status badge-approved">Confirmed</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #64748b; font-style: italic;">
            No specific period class substitute arrangements required for this duration.
        </div>
    @endif

    <!-- Approval Hierarchy Verification Box -->
    <div class="section-header">4. Multi-Stage Approval Verification & Signatures</div>
    
    <table style="width: 100%; border-collapse: separate; border-spacing: 10px; margin-top: 10px;">
        <tr>
            <!-- Staff Signature -->
            <td style="width: 25%; vertical-align: top;">
                <div class="stamp-box">
                    <div class="stamp-title">Staff Signature</div>
                    <div style="font-weight: 700; font-size: 12px; margin-top: 8px;">{{ $leave->staff_name }}</div>
                    <div style="font-size: 10px; color: #64748b; margin-top: 4px;">Date: {{ $leave->submitted_at ? \Carbon\Carbon::parse($leave->submitted_at)->format('d-M-Y H:i') : '-' }}</div>
                    <div style="font-size: 8px; font-family: monospace; color: #94a3b8; word-break: break-all; margin-top: 6px;">
                        HASH: {{ substr($leave->staff_signature_hash ?? 'N/A', 0, 16) }}...
                    </div>
                </div>
            </td>

            <!-- HOD Stamp -->
            <td style="width: 25%; vertical-align: top;">
                <div class="stamp-box">
                    <div class="stamp-title">Stage 1: HOD</div>
                    <div style="margin-top: 6px;">
                        @if($leave->hod_status === 'Approved')
                            <span class="badge-status badge-approved">APPROVED</span>
                        @elseif($leave->hod_status === 'Rejected')
                            <span class="badge-status badge-rejected">REJECTED</span>
                        @else
                            <span class="badge-status badge-pending">PENDING</span>
                        @endif
                    </div>
                    <div style="font-weight: 700; font-size: 11px; margin-top: 6px;">{{ $leave->hod_name ?? 'Head of Dept' }}</div>
                    <div style="font-size: 10px; color: #64748b;">{{ $leave->hod_action_at ? \Carbon\Carbon::parse($leave->hod_action_at)->format('d-M-Y H:i') : '-' }}</div>
                    @if($leave->hod_remarks)
                        <div style="font-size: 10px; font-style: italic; color: #475569; margin-top: 4px;">"{{ $leave->hod_remarks }}"</div>
                    @endif
                </div>
            </td>

            <!-- Academic Coordinator Stamp -->
            <td style="width: 25%; vertical-align: top;">
                <div class="stamp-box">
                    <div class="stamp-title">Stage 2: Academic Coord.</div>
                    <div style="margin-top: 6px;">
                        @if($leave->coordinator_status === 'Approved')
                            <span class="badge-status badge-approved">APPROVED</span>
                        @elseif($leave->coordinator_status === 'Rejected')
                            <span class="badge-status badge-rejected">REJECTED</span>
                        @else
                            <span class="badge-status badge-pending">PENDING</span>
                        @endif
                    </div>
                    <div style="font-weight: 700; font-size: 11px; margin-top: 6px;">{{ $leave->coordinator_name ?? 'Coordinator (SF)' }}</div>
                    <div style="font-size: 10px; color: #64748b;">{{ $leave->coordinator_action_at ? \Carbon\Carbon::parse($leave->coordinator_action_at)->format('d-M-Y H:i') : '-' }}</div>
                    @if($leave->coordinator_remarks)
                        <div style="font-size: 10px; font-style: italic; color: #475569; margin-top: 4px;">"{{ $leave->coordinator_remarks }}"</div>
                    @endif
                </div>
            </td>

            <!-- Principal Stamp -->
            <td style="width: 25%; vertical-align: top;">
                <div class="stamp-box">
                    <div class="stamp-title">Stage 3: Principal</div>
                    <div style="margin-top: 6px;">
                        @if($leave->principal_status === 'Approved')
                            <span class="badge-status badge-approved">APPROVED</span>
                        @elseif($leave->principal_status === 'Rejected')
                            <span class="badge-status badge-rejected">REJECTED</span>
                        @else
                            <span class="badge-status badge-pending">PENDING</span>
                        @endif
                    </div>
                    <div style="font-weight: 700; font-size: 11px; margin-top: 6px;">{{ $leave->principal_name ?? 'Principal' }}</div>
                    <div style="font-size: 10px; color: #64748b;">{{ $leave->principal_action_at ? \Carbon\Carbon::parse($leave->principal_action_at)->format('d-M-Y H:i') : '-' }}</div>
                    @if($leave->principal_remarks)
                        <div style="font-size: 10px; font-style: italic; color: #475569; margin-top: 4px;">"{{ $leave->principal_remarks }}"</div>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer System Audit -->
    <div style="margin-top: 30px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 10px; color: #94a3b8; text-align: center;">
        Generated automatically by Carmel Linx Academic Platform & Portal Governance System &bull; Document Code: {{ $leave->leave_code }} &bull; Print Date: {{ date('d-M-Y H:i:s') }}
    </div>

</body>
</html>
