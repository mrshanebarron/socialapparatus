<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $content_type
 * @property int $content_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $views
 * @property int $unique_views
 * @property int $reactions
 * @property int $comments
 * @property int $shares
 * @property int $saves
 * @property int $clicks
 * @property numeric $engagement_rate
 * @property int $reach
 * @property int $impressions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $content
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereClicks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereEngagementRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereImpressions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereReach($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereReactions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereSaves($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereShares($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereUniqueViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContentAnalytic whereViews($value)
 * @mixin \Eloquent
 */
class ContentAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_type', 'content_id', 'date', 'views', 'unique_views', 'reactions',
        'comments', 'shares', 'saves', 'clicks', 'engagement_rate', 'reach', 'impressions',
    ];

    protected $casts = ['date' => 'date', 'engagement_rate' => 'decimal:2'];

    public function content(): MorphTo
    {
        return $this->morphTo();
    }
}
