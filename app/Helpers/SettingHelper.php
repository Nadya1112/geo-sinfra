<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingHelper
{
    public static function get($key, $default = null)
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
            try {
                $setting = Setting::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            } catch (\Exception $e) {
                return $default;
            }
        });
    }

    public static function clearCache()
    {
        $settings = Setting::all();
        foreach ($settings as $setting) {
            Cache::forget('setting_' . $setting->key);
        }
    }
}
