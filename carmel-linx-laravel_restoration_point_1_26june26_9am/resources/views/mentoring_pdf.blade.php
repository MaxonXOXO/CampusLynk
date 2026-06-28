<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mentoring Diary</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1, h2, h3 { color: #333; }
        .section { margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Mentoring Diary</h1>
    <div class="section">
        <h2>Student Profile</h2>
        <p><strong>Name:</strong> {{ $student['name'] ?? 'N/A' }}</p>
        <p><strong>Register No:</strong> {{ $student['reg_no'] ?? 'N/A' }}</p>
        <p><strong>Branch:</strong> {{ $student['branch'] ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ $student['status'] ?? 'N/A' }}</p>
        <p><strong>Verified:</strong> {{ $student['profile_verified_at'] ? 'Yes (' . $student['profile_verified_at'] . ')' : 'No' }}</p>
    </div>

    <div class="section">
        <h2>Mentor Meetings</h2>
        @if(count($meetings) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Discussion / Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($meetings as $m)
                        <tr>
                            <td>{{ $m['date'] }}</td>
                            <td>{{ $m['category'] }}</td>
                            <td>
                                <strong>Notes:</strong> {{ $m['discussion_notes'] ?? 'N/A' }}<br>
                                <strong>Action:</strong> {{ $m['action_taken'] ?? 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No meeting records found.</p>
        @endif
    </div>

    <div class="section">
        <h2>Extra-Curricular Activities</h2>
        @if(count($extracurricular) > 0)
            <table>
                <thead>
                    <tr>
                        <th>Semester</th>
                        <th>Activity Name</th>
                        <th>Points</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($extracurricular as $e)
                        <tr>
                            <td>{{ $e['semester'] }}</td>
                            <td>{{ $e['activity_name'] }} ({{ $e['segment'] }})</td>
                            <td>Claimed: {{ $e['points_claimed'] }}, Awarded: {{ $e['points_awarded'] }}</td>
                            <td>{{ $e['status'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No extra-curricular records found.</p>
        @endif
    </div>

</body>
</html>
