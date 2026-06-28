import os

with open('app/Http/Controllers/MentoringController.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_func = '''    public function saveLeaveRecord(Request $request)
    {
        $mobileNo = Session::get('userId');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $request->validate([
            'reg_no'     => 'required|string',
            'semester'   => 'required|integer',
            'leave_date' => 'required|string',
            'reason'     => 'required|string',
            'status'     => 'required|string'
        ]);

        $data = [
            'reg_no'          => strtoupper($request->reg_no),
            'semester'        => $request->semester,
            'leave_date'      => $request->leave_date,
            'reason'          => $request->reason,
            'parent_informed' => $request->has('parent_informed') ? $request->parent_informed : false,
            'status'          => $request->status,
            'approved_by'     => $mobileNo,
        ];'''

new_func = '''    public function saveLeaveRecord(Request $request)
    {
        $mobileNo = Session::get('userId');
        $role = Session::get('userRole');
        if (!$mobileNo) return response()->json(['status' => 'ERROR', 'message' => 'Not authenticated.'], 401);

        $regNo = $request->input('reg_no');
        if ($role === 'Student') {
            $regNo = $mobileNo;
        } else {
            if (!$regNo) {
                return response()->json(['status' => 'ERROR', 'message' => 'Registration number is required.'], 400);
            }
        }

        $request->validate([
            'semester'   => 'required|integer',
            'leave_date' => 'required|string',
            'reason'     => 'required|string'
        ]);

        $data = [
            'reg_no'          => strtoupper($regNo),
            'semester'        => $request->semester,
            'leave_date'      => $request->leave_date,
            'reason'          => $request->reason,
            'parent_informed' => $request->has('parent_informed') ? $request->parent_informed : false,
            'status'          => $request->input('status', 'Pending'),
            'approved_by'     => ($role === 'Student') ? null : $mobileNo,
        ];'''

content = content.replace(old_func, new_func)

with open('app/Http/Controllers/MentoringController.php', 'w', encoding='utf-8') as f:
    f.write(content)

print("MentoringController saveLeaveRecord updated")
