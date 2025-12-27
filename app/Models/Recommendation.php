<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $recommendable_type
 * @property int $recommendable_id
 * @property string $type
 * @property numeric $score
 * @property string|null $reason
 * @property bool $is_dismissed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $recommendable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation whereIsDismissed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation whereRecommendableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation whereRecommendableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recommendation whereUserId($value)
 * @mixin \Eloquent
 */
class Recommendation extends Model
{
    protected $fillable = [
        'user_id', 'recommendable_type', 'recommendable_id',
        'type', 'score', 'reason', 'is_dismissed'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'is_dismissed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recommendable(): MorphTo
    {
        return $this->morphTo();
    }
}
