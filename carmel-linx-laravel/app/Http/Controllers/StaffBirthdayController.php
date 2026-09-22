<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\StaffProfile;
use App\Services\StaffBirthdayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class StaffBirthdayController extends Controller
{
    protected StaffBirthdayService $birthdayService;

    public function __construct(StaffBirthdayService $birthdayService)
    {
        $this->birthdayService = $birthdayService;
    }

    /**
     * Fetch staff members celebrating their birthday today, along with wishes & reactions.
     */
    public function getTodayBirthdays(Request $request): JsonResponse
    {
        $currentUserId = Session::get('userId') ?: Session::get('mobile_no');
        if (!$currentUserId && auth()->check()) {
            $user = auth()->user();
            $currentUserId = $user->mobile_no ?? (string)$user->id;
        }

        try {
            $data = $this->birthdayService->getBirthdayOverview($currentUserId);
            return response()->json(array_merge(['status' => 'SUCCESS'], $data));
        } catch (\Throwable $e) {
            Log::error('Failed to fetch birthday notifications: ' . $e->getMessage());
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to fetch birthday notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a birthday wish (emoji reaction or message) to a celebrant.
     */
    public function sendWish(Request $request): JsonResponse
    {
        $senderMobile = Session::get('userId') ?: Session::get('mobile_no');
        $senderName = Session::get('userName') ?: Session::get('name');

        if (!$senderMobile && auth()->check()) {
            $user = auth()->user();
            $senderMobile = $user->mobile_no ?? (string)$user->id;
            $senderName = $user->name ?? 'Staff Colleague';
        }

        if (!$senderMobile) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Active staff session required to send wishes.'
            ], 401);
        }

        $request->validate([
            'celebrant_mobile_no' => 'required|string',
            'emoji' => 'nullable|string|max:10',
            'message' => 'nullable|string|max:500',
        ]);

        $celebrantMobile = $request->input('celebrant_mobile_no');
        $emoji = $request->input('emoji');
        $message = trim((string)$request->input('message'));

        if (empty($emoji) && empty($message)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Please select an emoji or write a wish message.'
            ], 422);
        }

        try {
            $result = $this->birthdayService->sendWish(
                $senderMobile,
                $senderName ?? 'Staff Member',
                $celebrantMobile,
                $emoji,
                $message,
                $request->ip()
            );

            if (!$result['success']) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => $result['message']
                ], 422);
            }

            // Return refreshed birthday overview
            $data = $this->birthdayService->getBirthdayOverview($senderMobile);
            return response()->json(array_merge([
                'status' => 'SUCCESS',
                'push_dispatched' => $result['push_dispatched'],
            ], $data));
        } catch (\Throwable $e) {
            Log::error('Failed to send wish: ' . $e->getMessage());
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to send wish: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update logged-in staff member's own Date of Birth.
     */
    public function updateSelfDob(Request $request): JsonResponse
    {
        $userId = Session::get('userId') ?: Session::get('mobile_no');
        if (!$userId && auth()->check()) {
            $user = auth()->user();
            $userId = $user->mobile_no ?? (string)$user->id;
        }

        if (!$userId) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Session expired. Please log in.'
            ], 401);
        }

        $request->validate([
            'dob' => 'required|date',
        ]);

        $dob = $request->input('dob');

        try {
            $staff = StaffProfile::where('mobile_no', $userId)->first();
            if (!$staff) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Staff profile not found.'
                ], 404);
            }

            $staff->dob = $dob;
            $staff->save();

            if (class_exists(AuditLog::class)) {
                try {
                    AuditLog::create([
                        'performed_by' => $staff->mobile_no,
                        'performed_by_name' => $staff->name,
                        'target_id' => $staff->mobile_no,
                        'target_name' => $staff->name,
                        'action' => 'DOB Updated',
                        'details' => "Staff updated Date of Birth to {$dob}",
                        'ip_address' => $request->ip(),
                    ]);
                } catch (\Throwable $e) {
                    Log::warning('AuditLog creation bypassed for DOB update: ' . $e->getMessage());
                }
            }

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Date of Birth updated successfully!',
                'dob' => $dob
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Failed to update DOB: ' . $e->getMessage()
            ], 500);
        }
    }
}
