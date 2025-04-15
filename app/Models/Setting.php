<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('settings');
        });

        static::deleted(function () {
            Cache::forget('settings');
        });
    }

    /**
     * Get a setting value by key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $settings = self::getAllSettings();

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  string|null  $group
     * @return void
     */
    public static function set($key, $value, $group = null)
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget('settings');
    }

    /**
     * Get all settings as a key-value array.
     *
     * @return array
     */
    public static function getAllSettings()
    {
        return Cache::rememberForever('settings', function () {
            return self::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get all settings in a specific group.
     *
     * @param  string  $group
     * @return array
     */
    public static function getGroup($group)
    {
        return self::where('group', $group)
            ->pluck('value', 'key')
            ->toArray();
    }
}