<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public const CACHE_KEY = 'site_settings';

    protected $fillable = ['key', 'value'];

    protected $casts = ['value' => 'array'];

    /** All settings as [key => value], cached until the next save. */
    public static function allCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->pluck('value', 'key')
            ->map(fn ($v) => is_string($v) ? json_decode($v, true) : $v)
            ->all());
    }

    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }
}
