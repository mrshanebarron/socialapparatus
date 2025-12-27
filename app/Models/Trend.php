<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $mentions_count
 * @property int $hourly_count
 * @property int $daily_count
 * @property string|null $category
 * @property array<array-key, mixed>|null $related_hashtags
 * @property \Illuminate\Support\Carbon|null $trending_since
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereDailyCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereHourlyCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereMentionsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereRelatedHashtags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereTrendingSince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Trend whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Trend extends Model
{
    protected $fillable = [
        'name', 'type', 'mentions_count',
        'hourly_count', 'daily_count', 'category',
        'related_hashtags', 'trending_since'
    ];

    protected $casts = [
        'related_hashtags' => 'array',
        'trending_since' => 'datetime',
    ];
}
