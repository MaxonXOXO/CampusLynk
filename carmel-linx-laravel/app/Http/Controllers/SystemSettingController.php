<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SystemSettingController extends Controller
{
    /**
     * Helper check: Is AI generation enabled?
     * Default fallback is true if the setting record does not exist.
     */
    public static function isAiEnabled(): bool
    {
        return Cache::remember('system_setting_ai_generation_enabled', 600, function () {
            $val = DB::table('system_settings')
                ->where('key', 'ai_generation_enabled')
                ->value('value');
            
            return $val !== '0';
        });
    }

    /**
     * Retrieve system settings payload.
     */
    public function getSettings()
    {
        $settings = DB::table('system_settings')->get()->keyBy('key');

        return response()->json([
            'status' => 'SUCCESS',
            'settings' => [
                'ai_generation_enabled' => ($settings->get('ai_generation_enabled')->value ?? '1') === '1'
            ]
        ]);
    }

    /**
     * Save/update system settings.
     */
    public function saveSettings(Request $request)
    {
        $aiEnabled = $request->input('ai_generation_enabled', $request->input('ai_enabled'));
        if ($aiEnabled === null) {
            return response()->json(['status' => 'ERROR', 'message' => 'Missing AI status parameter.'], 422);
        }

        $val = filter_var($aiEnabled, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';

        DB::table('system_settings')->updateOrInsert(
            ['key' => 'ai_generation_enabled'],
            ['value' => $val, 'updated_at' => now()]
        );

        // Clear the cache
        Cache::forget('system_setting_ai_generation_enabled');

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'System settings updated successfully.',
            'ai_generation_enabled' => $val === '1'
        ]);
    }

    /**
     * Retrieve public status of AI generation.
     */
    public function getAiStatus()
    {
        return response()->json([
            'status' => 'SUCCESS',
            'ai_generation_enabled' => self::isAiEnabled()
        ]);
    }
}
