<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $series_id
 * @property int $user_id
 * @property int|null $current_item_id
 * @property int $current_position_seconds
 * @property int $items_completed
 * @property bool $is_completed
 * @property \Illuminate\Support\Carbon|null $last_watched_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SeriesItem|null $currentItem
 * @property-read \App\Models\Series $series
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress whereCurrentItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress whereCurrentPositionSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress whereIsCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress whereItemsCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress whereLastWatchedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesProgress whereUserId($value)
 * @mixin \Eloquent
 */
class SeriesProgress extends Model
{
    protected $table = 'series_progress';

    protected $fillable = [
        'series_id', 'user_id', 'current_item_id', 'current_position_seconds',
        'items_completed', 'is_completed', 'last_watched_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'last_watched_at' => 'datetime',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentItem(): BelongsTo
    {
        return $this->belongsTo(SeriesItem::class, 'current_item_id');
    }
}
