<?php

namespace App\Http\Controllers;

use App\Models\PrincipalScheduledEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PrincipalScheduledEventController extends Controller
{
    /**
     * Schedule a new campus event (Executive Desk).
     */
    public function schedule(Request $request)
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Principal', 'Super_Admin', 'Chairman', 'Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. Only Principal & Executive Desk can schedule events.'], 403);
        }

        $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'event_category'    => 'required|string|in:Academic,Exam,Meeting,Cultural,Sports,Workshop,Holiday,Other',
            'venue'             => 'nullable|string|max:255',
            'event_date'        => 'required|date',
            'start_time'        => 'nullable|string',
            'end_time'          => 'nullable|string',
            'is_full_day'       => 'nullable|boolean',
            'target_audience'   => 'required|string|in:ALL_CAMPUS,DEPT_SPECIFIC,STAFF_ONLY,STUDENTS_ONLY,SPECIAL_GROUP',
            'target_department' => 'nullable|string',
            'target_semester'   => 'nullable|string',
            'target_role'       => 'nullable|string',
            'special_group_name'=> 'nullable|string',
            'requires_rsvp'     => 'nullable|boolean',
            'attachment'        => 'nullable|file|mimes:jpeg,jpg,png,webp,pdf|max:10240',
        ]);

        $attachmentPath = null;
        $attachmentType = 'none';

        if ($request->hasFile('attachment')) {
            $file     = $request->file('attachment');
            $filename = 'event_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $attachmentPath = $file->storeAs('principal_events', $filename, 'public');

            $mime = $file->getClientMimeType();
            if (str_contains($mime, 'pdf')) {
                $attachmentType = 'pdf';
            } elseif (str_contains($mime, 'image')) {
                $attachmentType = 'image';
            } else {
                $attachmentType = 'other';
            }
        }

        $event = PrincipalScheduledEvent::create([
            'title'             => trim($request->input('title')),
            'description'       => trim($request->input('description', '')),
            'event_category'    => $request->input('event_category', 'Academic'),
            'venue'             => trim($request->input('venue', 'Main Auditorium')),
            'event_date'        => $request->input('event_date'),
            'start_time'        => $request->input('start_time'),
            'end_time'          => $request->input('end_time'),
            'is_full_day'       => (bool) $request->input('is_full_day', false),
            'target_audience'   => $request->input('target_audience', 'ALL_CAMPUS'),
            'target_department' => $request->input('target_department', 'ALL'),
            'target_semester'   => $request->input('target_semester', 'ALL'),
            'target_role'       => $request->input('target_role', 'ALL'),
            'special_group_name'=> $request->input('special_group_name'),
            'requires_rsvp'     => (bool) $request->input('requires_rsvp', false),
            'attachment_path'   => $attachmentPath,
            'attachment_type'   => $attachmentType,
            'dispatch_type'     => $request->input('dispatch_type', 'immediate'),
            'scheduled_at'      => $request->input('scheduled_at'),
            'is_published'      => true,
            'created_by'        => Session::get('userName', 'Principal'),
        ]);

        return response()->json([
            'status'  => 'SUCCESS',
            'message' => 'Campus event scheduled successfully for ' . $request->input('target_audience') . '!',
            'event'   => $event,
        ]);
    }

    /**
     * List all scheduled events for Principal Audit Card.
     */
    public function index(Request $request)
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Principal', 'Super_Admin', 'Chairman', 'Admin', 'HOD'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $events = PrincipalScheduledEvent::orderBy('event_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->take(50)
            ->get();

        $stats = [
            'total_events'      => PrincipalScheduledEvent::count(),
            'college_wide'      => PrincipalScheduledEvent::where('target_audience', 'ALL_CAMPUS')->count(),
            'dept_specific'     => PrincipalScheduledEvent::where('target_audience', 'DEPT_SPECIFIC')->count(),
            'staff_only'        => PrincipalScheduledEvent::where('target_audience', 'STAFF_ONLY')->count(),
            'students_only'     => PrincipalScheduledEvent::where('target_audience', 'STUDENTS_ONLY')->count(),
            'special_groups'    => PrincipalScheduledEvent::where('target_audience', 'SPECIAL_GROUP')->count(),
        ];

        return response()->json([
            'status' => 'SUCCESS',
            'stats'  => $stats,
            'events' => $events,
        ]);
    }

    /**
     * Get targeted events feed for students/staff/HOD portals.
     */
    public function feed(Request $request)
    {
        $userDept = $request->query('department', Session::get('userBranch', 'ALL'));
        $userSem  = $request->query('semester', Session::get('userSemester', 'ALL'));
        $userRole = $request->query('role', Session::get('userRole', 'Student'));
        $group    = $request->query('group');

        $query = PrincipalScheduledEvent::where('is_published', true)
            ->where('event_date', '>=', date('Y-m-d'));

        $query->where(function ($q) use ($userDept, $userSem, $userRole, $group) {
            // 1. College-Wide
            $q->where('target_audience', 'ALL_CAMPUS')
              // 2. Department-Specific
              ->orWhere(function ($q2) use ($userDept) {
                  $q2->where('target_audience', 'DEPT_SPECIFIC')
                     ->where(function($q3) use ($userDept) {
                         $q3->where('target_department', 'ALL')
                            ->orWhere('target_department', $userDept);
                     });
              })
              // 3. Staff-Only
              ->orWhere(function ($q2) use ($userRole) {
                  $q2->where('target_audience', 'STAFF_ONLY')
                     ->where(function($q3) use ($userRole) {
                         $q3->where('target_role', 'ALL')
                            ->orWhere('target_role', $userRole);
                     });
              })
              // 4. Students-Only
              ->orWhere(function ($q2) use ($userDept, $userSem) {
                  $q2->where('target_audience', 'STUDENTS_ONLY')
                     ->where(function($q3) use ($userDept) {
                         $q3->where('target_department', 'ALL')
                            ->orWhere('target_department', $userDept);
                     })
                     ->where(function($q4) use ($userSem) {
                         $q4->where('target_semester', 'ALL')
                            ->orWhere('target_semester', $userSem);
                     });
              })
              // 5. Special Group
              ->orWhere(function ($q2) use ($group) {
                  $q2->where('target_audience', 'SPECIAL_GROUP');
                  if ($group) {
                      $q2->where('special_group_name', $group);
                  }
              });
        });

        $events = $query->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(20)
            ->get();

        return response()->json([
            'status' => 'SUCCESS',
            'count'  => count($events),
            'events' => $events,
        ]);
    }

    /**
     * Delete / revoke a scheduled event.
     */
    public function destroy($id)
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Principal', 'Super_Admin', 'Chairman', 'Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 403);
        }

        $event = PrincipalScheduledEvent::find($id);
        if (!$event) {
            return response()->json(['status' => 'ERROR', 'message' => 'Event not found.'], 404);
        }

        if ($event->attachment_path && Storage::disk('public')->exists($event->attachment_path)) {
            Storage::disk('public')->delete($event->attachment_path);
        }

        $event->delete();

        return response()->json(['status' => 'SUCCESS', 'message' => 'Scheduled event cancelled & deleted successfully.']);
    }
}
