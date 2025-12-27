<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $series_id
 * @property string $content_type
 * @property int $content_id
 * @property int $episode_number
 * @property string|null $custom_title
 * @property string|null $custom_description
 * @property int|null $duration_seconds
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $content
 * @property-read \App\Models\Series $series
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem whereContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem whereCustomDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem whereCustomTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem whereDurationSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem whereEpisodeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeriesItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeriesItem extends Model
{
    protected $fillable = [
        'series_id', 'content_type', 'content_id', 'episode_number',
        'custom_title', 'custom_description', 'duration_seconds'
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function content(): MorphTo
    {
        return $this->morphTo();
    }
}
