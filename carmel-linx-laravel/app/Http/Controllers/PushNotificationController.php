<?php

namespace App\Http\Controllers;

use App\Services\PushNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
}
