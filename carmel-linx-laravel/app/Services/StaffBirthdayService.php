<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\StaffBirthdayWish;
use App\Models\StaffProfile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class StaffBirthdayService
{
    /**
     * Get active staff members celebrating their birthday on a given date (default today).
     */
    public function getCelebrants(?Carbon $date = null): Collection
    {
        $targetDate = $date ?? Carbon::today();
        $month = $targetDate->month;
        $day = $targetDate->day;

        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $query = StaffProfile::where(function ($q) {
                $q->where('account_status', 'Approved')
                  ->orWhere('account_status', 'APPROVED');
            })
            ->whereNotNull('dob');

        if ($driver === 'sqlite') {
            $query->whereRaw("cast(strftime('%m', dob) as integer) = ?", [(int)$month])
                  ->whereRaw("cast(strftime('%d', dob) as integer) = ?", [(int)$day]);
        } else {
            $query->whereRaw('MONTH(dob) = ?', [(int)$month])
                  ->whereRaw('DAY(dob) = ?', [(int)$day]);
        }

        return $query->select('mobile_no', 'name', 'designation', 'branch', 'photo_url', 'dob')
            ->get()
            ->map(function ($staff) {
                return [
                    'mobile_no' => $staff->mobile_no,
                    'name' => $staff->name,
                    'designation' => str_replace('_', ' ', (string)$staff->designation),
                    'branch' => $staff->branch,
                    'photo_url' => $staff->photo_url ?: '/storage/avatars/default.png',
                    'dob' => $staff->dob,
                ];
            });
    }

    /**
     * Get full birthday celebration payload for a date including wishes and reactions.
     */
    public function getBirthdayOverview(?string $currentUserId = null, ?Carbon $date = null): array
    {
        $targetDate = $date ?? Carbon::today();
        $dateStr = $targetDate->toDateString();
        $celebrants = $this->getCelebrants($targetDate);

        if ($celebrants->isEmpty()) {
            return [
                'has_birthdays' => false,
                'celebrants' => [],
                'reactions' => [],
                'wishes' => [],
                'has_wished' => false,
            ];
        }

        $celebrantMobiles = $celebrants->pluck('mobile_no')->toArray();

        $wishes = StaffBirthdayWish::forDate($dateStr)
            ->whereIn('celebrant_mobile_no', $celebrantMobiles)
            ->orderBy('id', 'desc')
            ->get();

        $reactions = [
            '🎉' => 0,
            '🎂' => 0,
            '🎈' => 0,
            '🎁' => 0,
            '❤️' => 0,
            '👏' => 0,
        ];

        foreach ($wishes as $w) {
            if (!empty($w->emoji) && isset($reactions[$w->emoji])) {
                $reactions[$w->emoji]++;
            }
        }

        $hasWished = false;
        if ($currentUserId) {
            $hasWished = StaffBirthdayWish::forDate($dateStr)
                ->where('sender_mobile_no', $currentUserId)
                ->exists();
        }

        return [
            'has_birthdays' => true,
            'date_label' => strtoupper($targetDate->format('F d')),
            'celebrants' => $celebrants->values()->all(),
            'reactions' => $reactions,
            'wishes' => $wishes->map(function ($w) {
                return [
                    'id' => $w->id,
                    'sender_name' => $w->sender_name,
                    'emoji' => $w->emoji,
                    'message' => $w->message,
                    'time' => $w->created_at ? $w->created_at->format('h:i A') : '',
                ];
            })->values()->all(),
            'has_wished' => $hasWished,
            'current_user_id' => $currentUserId,
        ];
    }

    /**
     * Record a birthday wish and dispatch push notification.
     * Enforces idempotency per sender, celebrant, and date.
     */
    public function sendWish(
        string $senderMobile,
        string $senderName,
        string $celebrantMobile,
        ?string $emoji,
        ?string $message,
        ?string $ipAddress = null,
        ?Carbon $date = null
    ): array {
        $targetDate = $date ?? Carbon::today();
        $dateStr = $targetDate->toDateString();

        // 1. Verify celebrant exists in staff_profiles and has birthday on targetDate
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        $celebrantQuery = StaffProfile::where('mobile_no', $celebrantMobile)
            ->whereNotNull('dob');

        if ($driver === 'sqlite') {
            $celebrantQuery->whereRaw("cast(strftime('%m', dob) as integer) = ?", [(int)$targetDate->month])
                           ->whereRaw("cast(strftime('%d', dob) as integer) = ?", [(int)$targetDate->day]);
        } else {
            $celebrantQuery->whereRaw('MONTH(dob) = ?', [(int)$targetDate->month])
                           ->whereRaw('DAY(dob) = ?', [(int)$targetDate->day]);
        }

        $celebrant = $celebrantQuery->first();

        if (!$celebrant) {
            return [
                'success' => false,
                'status' => 'NOT_ELIGIBLE',
                'message' => 'The selected staff member does not have a registered birthday today.',
            ];
        }

        // 2. Idempotent recording: firstOrCreate on unique tuple
        $wish = StaffBirthdayWish::firstOrCreate(
            [
                'wish_date' => $dateStr,
                'celebrant_mobile_no' => $celebrantMobile,
                'sender_mobile_no' => $senderMobile,
            ],
            [
                'sender_name' => $senderName,
                'emoji' => $emoji,
                'message' => $message ?: null,
            ]
        );

        // 3. Log to AuditLog if available
        if (class_exists(AuditLog::class)) {
            try {
                AuditLog::create([
                    'performed_by' => $senderMobile,
                    'performed_by_name' => $senderName,
                    'target_id' => $celebrantMobile,
                    'target_name' => 'Staff Birthday Wish',
                    'action' => 'Birthday Wish Sent',
                    'details' => "Sent birthday wish to {$celebrantMobile}" . ($emoji ? " with emoji {$emoji}" : ''),
                    'ip_address' => $ipAddress,
                ]);
            } catch (\Throwable $e) {
                Log::warning('AuditLog creation bypassed for birthday wish: ' . $e->getMessage());
            }
        }

        // 4. Dispatch push notification via M8.1 PushNotificationService
        $pushTitle = "🎂 Birthday Wish from {$senderName}!";
        $pushBody = $message ? "{$senderName}: \"{$message}\"" : "{$senderName} sent you a {$emoji} birthday wish!";
        $sentCount = PushNotificationService::notifyUser(
            $celebrantMobile,
            $pushTitle,
            $pushBody,
            '/dashboard/staff/mobile',
            'carmel-birthday-wish'
        );

        return [
            'success' => true,
            'status' => 'SUCCESS',
            'wish' => $wish,
            'push_dispatched' => $sentCount > 0,
            'dispatched_count' => $sentCount,
        ];
    }
}
