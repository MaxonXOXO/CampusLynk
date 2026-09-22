<?php

namespace App\Services;

use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationService
{
    /**
     * Store or update push subscription for a user device idempotently.
     */
    public static function subscribe(string $userId, string $role, string $endpoint, ?string $p256dhKey, ?string $authKey, string $deviceType = 'mobile'): PushSubscription
    {
        return PushSubscription::updateOrCreate(
            [
                'endpoint' => $endpoint,
            ],
            [
                'user_id' => $userId,
                'role' => strtolower($role),
                'p256dh_key' => $p256dhKey,
                'auth_key' => $authKey,
                'device_type' => $deviceType,
            ]
        );
    }

    /**
     * Dispatch notification payload to subscribers using Web Push (RFC 8291 / RFC 8292).
     */
    public static function dispatchPayload($subscriptions, string $title, string $body, string $url = '/', string $tag = 'carmel-alert'): int
    {
        $payload = json_encode([
            'title' => $title,
            'body' => $body,
            'url' => $url,
            'tag' => $tag,
            'timestamp' => time(),
        ]);

        $publicKey = config('services.vapid.public_key');
        $privateKey = config('services.vapid.private_key');
        $subject = config('services.vapid.subject', 'mailto:admin@carmelpolytechnic.edu.in');

        if (empty($publicKey) || empty($privateKey)) {
            Log::warning('Web Push dispatch aborted: VAPID keys are not configured.');
            return 0;
        }

        $auth = [
            'VAPID' => [
                'subject' => $subject,
                'publicKey' => $publicKey,
                'privateKey' => $privateKey,
            ],
        ];

        try {
            $webPush = new WebPush($auth, ['timeout' => 3]);
            $webPush->setDefaultOptions(['TTL' => 86400]);

            $validCount = 0;
            foreach ($subscriptions as $sub) {
                if (empty($sub->endpoint)) {
                    continue;
                }

                $subscription = Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->p256dh_key,
                    'authToken' => $sub->auth_key,
                ]);

                $webPush->queueNotification($subscription, $payload);
                $validCount++;
            }

            if ($validCount === 0) {
                return 0;
            }

            $sentCount = 0;
            foreach ($webPush->flush() as $report) {
                $endpoint = $report->getRequest()->getUri()->__toString();

                if ($report->isSuccess()) {
                    $sentCount++;
                } else {
                    Log::warning("Web Push dispatch failed for endpoint [{$endpoint}]: {$report->getReason()}");

                    // Prune expired or invalid subscriptions (HTTP 404 / 410 Gone)
                    if ($report->isSubscriptionExpired()) {
                        PushSubscription::where('endpoint', $endpoint)->delete();
                        Log::info("Pruned expired push subscription endpoint: [{$endpoint}]");
                    }
                }
            }

            return $sentCount;
        } catch (\Throwable $e) {
            Log::error('Web Push Notification Dispatch Exception: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Send notification to a specific user by ID.
     */
    public static function notifyUser(string $userId, string $title, string $body, string $url = '/', string $tag = 'carmel-user-alert'): int
    {
        $subscriptions = PushSubscription::forUser($userId)->get();
        return self::dispatchPayload($subscriptions, $title, $body, $url, $tag);
    }

    /**
     * Send notification to a role (e.g. 'staff' or 'student').
     */
    public static function notifyRole(string $role, string $title, string $body, string $url = '/', string $tag = 'carmel-role-alert'): int
    {
        $subscriptions = PushSubscription::forRole($role)->get();
        return self::dispatchPayload($subscriptions, $title, $body, $url, $tag);
    }

    /**
     * Send notification to all registered push subscribers.
     */
    public static function notifyAll(string $title, string $body, string $url = '/', string $tag = 'carmel-broadcast-alert'): int
    {
        $subscriptions = PushSubscription::all();
        return self::dispatchPayload($subscriptions, $title, $body, $url, $tag);
    }
}
