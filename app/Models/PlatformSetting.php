<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match (true) {
            $setting->value === 'true' => true,
            $setting->value === 'false' => false,
            is_numeric($setting->value) => (float) $setting->value,
            default => $setting->value,
        };
    }

    public static function set(string $key, mixed $value): static
    {
        $castValue = match (true) {
            is_bool($value) => $value ? 'true' : 'false',
            default => (string) $value,
        };

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $castValue],
        );
    }
}
