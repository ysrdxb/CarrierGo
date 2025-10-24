<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Session;

class SettingService
{
    public static function getSettings()
    {
        return Setting::first();
    }

    public static function fillSession()
    {
        try {
            $setting = self::getSettings();
            if ($setting) {
                foreach ($setting->toArray() as $key => $value) {
                    Session::put($key, $value);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('SettingService::fillSession() error: ' . $e->getMessage(), [
                'exception' => get_class($e),
            ]);
            // Continue without settings rather than crash
        }
    }
    
}
