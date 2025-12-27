<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $key
 * @property array<array-key, mixed> $value
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardCache newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardCache newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardCache query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardCache whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardCache whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardCache whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardCache whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardCache whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardCache whereValue($value)
 * @mixin \Eloquent
 */
class DashboardCache extends Model
{
    use HasFactory;

    protected $table = 'dashboard_cache';

    protected $fillable = ['key', 'value', 'expires_at'];

    protected $casts = ['value' => 'array', 'expires_at' => 'datetime'];

    public static function get(string $key, $default = null)
    {
        $cache = self::where('key', $key)->where('expires_at', '>', now())->first();
        return $cache ? $cache->value : $default;
    }

    public static function put(string $key, $value, int $minutes = 5): self
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value, 'expires_at' => now()->addMinutes($minutes)]);
    }
}
