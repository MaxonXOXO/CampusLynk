<?php

namespace App\Http\Controllers;

use App\Models\PrincipalScheduledEvent;
use App\Services\PushNotificationService;
use App\Services\StaffBirthdayService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PushNotificationController extends Controller
{
    /**
     * Return public VAPID key for client-side subscription.
     */
    public function getVapidPublicKey(): JsonResponse
    {
        $publicKey = config('services.vapid.public_key');

        if (empty($publicKey)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'VAPID public key is not configured.'
            ], 503);
        }

        return response()->json([
            'status' => 'SUCCESS',
            'publicKey' => $publicKey,
        ]);
    }

    /**
     * Store Web Push Notification Subscription for current session user.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => 'required|string|max:500',
            'p256dh_key' => 'nullable|string',
            'auth_key' => 'nullable|string',
            'device_type' => 'nullable|string|in:mobile,desktop',
        ]);

        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        // Fallback to Laravel Auth if session is empty
        if (!$userId && auth()->check()) {
            $user = auth()->user();
            $userId = $user->mobile_no ?? $user->reg_no ?? (string)$user->id;
            $userRole = $user->role ?? 'staff';
        }

        if (!$userId) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Unauthenticated session.'
            ], 401);
        }

        // Server-authoritative role determination: never trust client-submitted role
        $roleType = (strtolower((string)$userRole) === 'student') ? 'student' : 'staff';

        $endpoint = $request->input('endpoint');
        $p256dhKey = $request->input('p256dh_key');
        $authKey = $request->input('auth_key');
        $deviceType = $request->input('device_type', 'mobile');

        PushNotificationService::subscribe($userId, $roleType, $endpoint, $p256dhKey, $authKey, $deviceType);

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Push notification subscription registered successfully.'
        ]);
    }

    /**
     * Broadcast urgent push notice (Authorized Senders only).
     */
    public function sendBroadcast(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:255',
            'target' => 'required|string|in:all,staff,students',
            'url' => 'nullable|string|max:255',
        ]);

        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId && auth()->check()) {
            $user = auth()->user();
            $userId = $user->mobile_no ?? $user->reg_no ?? (string)$user->id;
            $userRole = $user->role ?? null;
        }

        $authorizedRoles = [
            'Super_Admin',
            'Admin',
            'Principal',
            'Chairman',
            'HOD',
            'Tutor',
            'Lecturer',
            'Academic_Coordinator',
            'Academic Coordinator'
        ];

        if (!$userId || !in_array($userRole, $authorizedRoles, true)) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Unauthorized push sender.'
            ], 403);
        }

        $title = trim($request->input('title'));
        $body = trim($request->input('body'));
        $target = $request->input('target');
        $targetUrl = $request->input('url', '/');

        $sentCount = 0;
        if ($target === 'all') {
            $sentCount = PushNotificationService::notifyAll($title, $body, $targetUrl, 'carmel-broadcast');
        } else {
            $sentCount = PushNotificationService::notifyRole($target, $title, $body, $targetUrl, 'carmel-' . $target);
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => "Push notification dispatched to {$sentCount} device(s)."
        ]);
    }

    /**
     * Get live in-app notifications feed for current user session.
     */
    public function getNotificationFeed(Request $request): JsonResponse
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId && auth()->check()) {
            $user = auth()->user();
            $userId = $user->mobile_no ?? $user->reg_no ?? (string)$user->id;
            $userRole = $user->role ?? 'staff';
        }

        $readIds = Session::get('read_notification_ids', []);

        $notifications = [];

        // 1. Campus Event / Suspension for Today
        try {
            $evtCtrl = app(\App\Http\Controllers\PrincipalScheduledEventController::class);
            $evtRes = $evtCtrl->getTodayCampusEvent($request)->getData(true);
            if (!empty($evtRes['has_event']) && !empty($evtRes['event'])) {
                $todayEvent = (object)$evtRes['event'];
                $evtId = 'event-' . $todayEvent->id;
                $isSuspended = !empty($todayEvent->suppress_timetable);
                $notifications[] = [
                    'id' => $evtId,
                    'type' => 'event',
                    'title' => $todayEvent->title ?? 'Campus Notice',
                    'message' => $isSuspended
                        ? 'Classes suspended (' . ($todayEvent->suspension_type ?? 'Full Day') . ')'
                        : ($todayEvent->description ?: 'Scheduled campus event today.'),
                    'time' => !empty($todayEvent->start_time) ? date('h:i A', strtotime($todayEvent->start_time)) : 'Today',
                    'badge' => $isSuspended ? 'Suspension' : 'Event',
                    'icon' => 'calendar',
                    'severity' => $isSuspended ? 'warning' : 'info',
                    'link' => '/events',
                    'unread' => !in_array($evtId, $readIds, true),
                ];
            }
        } catch (\Throwable $e) {
            // Silently continue
        }

        // 2. Staff Birthday Celebrants for Today
        try {
            $service = app(\App\Services\StaffBirthdayService::class);
            $birthdayRes = $service->getBirthdayOverview($userId);
            if (!empty($birthdayRes['celebrants'])) {
                foreach ($birthdayRes['celebrants'] as $c) {
                    $bId = 'birthday-' . ($c['mobile_no'] ?? $c['name']);
                    $notifications[] = [
                        'id' => $bId,
                        'type' => 'birthday',
                        'title' => '🎉 ' . $c['name'] . "'s Birthday!",
                        'message' => 'Wish ' . $c['name'] . ' (' . ($c['designation'] ?? 'Staff') . ') a wonderful birthday today!',
                        'time' => 'Today',
                        'badge' => 'Celebration',
                        'icon' => 'cake',
                        'severity' => 'success',
                        'link' => '#',
                        'unread' => !in_array($bId, $readIds, true),
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Silently continue
        }

        // 3. Active Online Tests / Examinations (if test_configs table exists)
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('test_configs')) {
                $now = now();
                $activeTests = DB::table('test_configs')
                    ->where('is_active', 1)
                    ->where(function($q) use ($now) {
                        $q->whereNull('end_time')->orWhere('end_time', '>=', $now);
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();

                foreach ($activeTests as $t) {
                    $tId = 'test-' . $t->test_id;
                    $notifications[] = [
                        'id' => $tId,
                        'type' => 'academic',
                        'title' => '📝 ' . ($t->test_name ?: 'Online MCQ Test'),
                        'message' => 'Test active for ' . $t->subject_code . ' (Duration: ' . $t->duration . ' mins).',
                        'time' => $t->start_time ? Carbon::parse($t->start_time)->diffForHumans() : 'Active',
                        'badge' => 'Exam',
                        'icon' => 'clipboard',
                        'severity' => 'info',
                        'link' => '/student/online-test/' . $t->test_id,
                        'unread' => !in_array($tId, $readIds, true),
                    ];
                }
            }
        } catch (\Exception $e) {
            // Silently continue
        }

        $unreadCount = count(array_filter($notifications, fn($n) => $n['unread']));

        return response()->json([
            'status' => 'SUCCESS',
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
            'push_configured' => !empty(config('services.vapid.public_key')),
        ]);
    }

    /**
     * Mark a notification or all notifications as read.
     */
    public function markNotificationRead(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $readIds = Session::get('read_notification_ids', []);

        if ($id === 'all') {
            $feed = $this->getNotificationFeed($request)->getData(true);
            $allIds = array_column($feed['notifications'] ?? [], 'id');
            $readIds = array_unique(array_merge($readIds, $allIds));
        } elseif ($id) {
            $readIds[] = $id;
            $readIds = array_unique($readIds);
        }

        Session::put('read_notification_ids', $readIds);

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Notification state updated.'
        ]);
    }
}
