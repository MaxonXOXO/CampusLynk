<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SupportDeskController extends Controller
{
    /**
     * Staff requests live remote support assist.
     */
    public function requestAssist(Request $request)
    {
        $userId = Session::get('userId');
        $userName = Session::get('userName') ?: 'Staff Member';
        $userRole = Session::get('userRole') ?: 'Staff';
        $userBranch = Session::get('userBranch') ?: 'General';

        $sessionId = 'SUP-' . strtoupper(Str::random(8));

        $requests = Cache::get('active_support_sessions', []);

        // Filter out old expired sessions (> 30 mins)
        $now = time();
        $requests = array_filter($requests, function ($sess) use ($now) {
            return ($now - ($sess['updated_at'] ?? 0)) < 1800;
        });

        $requests[$sessionId] = [
            'session_id' => $sessionId,
            'user_id' => $userId,
            'user_name' => $userName,
            'user_role' => $userRole,
            'user_branch' => $userBranch,
            'status' => 'pending', // pending, active, ended
            'created_at' => $now,
            'updated_at' => $now,
        ];

        Cache::put('active_support_sessions', $requests, 1800);

        return response()->json([
            'status' => 'SUCCESS',
            'session_id' => $sessionId,
            'message' => 'Support request submitted. Waiting for Admin connection...'
        ]);
    }

    /**
     * Admin polls active support sessions.
     */
    public function getActiveSessions()
    {
        $requests = Cache::get('active_support_sessions', []);
        $now = time();

        $active = [];
        foreach ($requests as $sess) {
            if (($now - ($sess['updated_at'] ?? 0)) < 1800 && $sess['status'] !== 'ended') {
                $active[] = $sess;
            }
        }

        return response()->json([
            'status' => 'SUCCESS',
            'sessions' => array_values($active)
        ]);
    }

    /**
     * Admin accepts a support session.
     */
    public function acceptSession(Request $request)
    {
        $sessionId = $request->input('session_id');
        $requests = Cache::get('active_support_sessions', []);

        if (!isset($requests[$sessionId])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Session not found or expired.'], 404);
        }

        $requests[$sessionId]['status'] = 'active';
        $requests[$sessionId]['updated_at'] = time();
        $requests[$sessionId]['admin_name'] = Session::get('userName') ?: 'Dhanush.A (Support)';

        Cache::put('active_support_sessions', $requests, 1800);

        return response()->json([
            'status' => 'SUCCESS',
            'session' => $requests[$sessionId]
        ]);
    }

    /**
     * Post signal (offer, answer, candidate, laser pointer coordinates).
     */
    public function postSignal(Request $request)
    {
        $sessionId = $request->input('session_id');
        $type = $request->input('type'); // offer, answer, candidate, laser_pointer, ping
        $payload = $request->input('payload');
        $sender = $request->input('sender'); // staff or admin

        if (!$sessionId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Session ID required.'], 400);
        }

        // Touch session updated_at timestamp
        $requests = Cache::get('active_support_sessions', []);
        if (isset($requests[$sessionId])) {
            $requests[$sessionId]['updated_at'] = time();
            Cache::put('active_support_sessions', $requests, 1800);
        }

        $signalsKey = "support_signals_" . $sessionId;
        $signals = Cache::get($signalsKey, []);

        $newSignal = [
            'id' => microtime(true) . '_' . Str::random(4),
            'type' => $type,
            'sender' => $sender,
            'payload' => $payload,
            'timestamp' => microtime(true)
        ];

        $signals[] = $newSignal;

        // Keep last 50 signals
        if (count($signals) > 50) {
            $signals = array_slice($signals, -50);
        }

        Cache::put($signalsKey, $signals, 1800);

        return response()->json(['status' => 'SUCCESS', 'signal_id' => $newSignal['id']]);
    }

    /**
     * Poll signals for a session.
     */
    public function getSignals(Request $request, $sessionId)
    {
        $lastId = $request->input('last_id');
        $signalsKey = "support_signals_" . $sessionId;
        $signals = Cache::get($signalsKey, []);

        $newSignals = [];
        $foundLast = empty($lastId);

        foreach ($signals as $sig) {
            if ($foundLast) {
                $newSignals[] = $sig;
            } else if ($sig['id'] === $lastId) {
                $foundLast = true;
            }
        }

        // If lastId was purged or not found, return all available signals
        if (!$foundLast) {
            $newSignals = $signals;
        }

        $requests = Cache::get('active_support_sessions', []);
        $sessionStatus = $requests[$sessionId]['status'] ?? 'ended';

        return response()->json([
            'status' => 'SUCCESS',
            'session_status' => $sessionStatus,
            'signals' => $newSignals
        ]);
    }

    /**
     * Terminate support session.
     */
    public function endSession(Request $request)
    {
        $sessionId = $request->input('session_id');
        $requests = Cache::get('active_support_sessions', []);

        if (isset($requests[$sessionId])) {
            $requests[$sessionId]['status'] = 'ended';
            $requests[$sessionId]['updated_at'] = time();
            Cache::put('active_support_sessions', $requests, 1800);
        }

        Cache::forget("support_signals_" . $sessionId);

        return response()->json(['status' => 'SUCCESS', 'message' => 'Session ended.']);
    }
}
