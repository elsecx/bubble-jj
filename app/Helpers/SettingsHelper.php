<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    protected static string $cacheKey = 'settings.all';
    protected static int $cacheSeconds = 0; // 0 for rememberForever

    /**
     * Get settings based on key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $settings = Setting::rememberCache(self::$cacheKey, self::$cacheSeconds, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set or update settings
     *
     * @param string $key
     * @param mixed $value
     * @return Setting
     */
    public static function set(string $key, $value)
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear cache via trait (bootCacheable can also handle this automatically)
        Setting::forgetCache(self::$cacheKey);

        return $setting;
    }

    /**
     * Clear cache manually
     */
    public static function clearCache()
    {
        Setting::forgetCache(self::$cacheKey);
    }
}
