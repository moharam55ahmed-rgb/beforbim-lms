<?php

namespace App\Modules\Setting\Services;

use App\Modules\Setting\Models\CmsSetting;
use Illuminate\Support\Facades\Cache;

class CmsSettingService
{
    protected const CACHE_PREFIX = 'cms_setting_';

    protected const CACHE_ALL_PUBLIC = 'cms_settings_public';

    protected const CACHE_TTL = 3600; // 1 hour

    /**
     * Get a single setting by key with caching.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = self::CACHE_PREFIX.$key;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($key, $default) {
            return CmsSetting::get($key, $default);
        });
    }

    /**
     * Set or update a setting value.
     */
    public function set(string $key, mixed $value, string $group = 'general', string $type = 'string', bool $isPublic = false, ?string $description = null): CmsSetting
    {
        $setting = CmsSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'group' => $group,
                'type' => $type,
                'is_public' => $isPublic,
                'description' => $description,
            ]
        );

        Cache::forget(self::CACHE_PREFIX.$key);
        Cache::forget(self::CACHE_ALL_PUBLIC);
        Cache::forget('cms_settings_group_'.$group);

        return $setting;
    }

    /**
     * Get all settings grouped by their group identifier.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getAllGrouped(): array
    {
        $settings = CmsSetting::all();
        $grouped = [];

        foreach ($settings as $setting) {
            $grouped[$setting->group][$setting->key] = [
                'id' => $setting->id,
                'key' => $setting->key,
                'value' => $this->castValue($setting),
                'raw_value' => $setting->value,
                'type' => $setting->type,
                'description' => $setting->description,
                'is_public' => $setting->is_public,
            ];
        }

        return $grouped;
    }

    /**
     * Get all public settings (cached).
     *
     * @return array<string, mixed>
     */
    public function getPublicSettings(): array
    {
        return Cache::remember(self::CACHE_ALL_PUBLIC, self::CACHE_TTL, function () {
            $publicSettings = CmsSetting::where('is_public', true)->get();
            $result = [];

            foreach ($publicSettings as $setting) {
                $result[$setting->key] = $this->castValue($setting);
            }

            return $result;
        });
    }

    /**
     * Bulk update settings by array key => value.
     *
     * @param  array<string, mixed>  $settings
     */
    public function bulkUpdate(array $settings): void
    {
        foreach ($settings as $key => $value) {
            CmsSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : (string) $value]
            );

            Cache::forget(self::CACHE_PREFIX.$key);
        }

        Cache::forget(self::CACHE_ALL_PUBLIC);
    }

    /**
     * Cast setting value according to type definition.
     */
    protected function castValue(CmsSetting $setting): mixed
    {
        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }
}
