<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $badge_id
 * @property \Illuminate\Support\Carbon $earned_at
 * @property int $is_displayed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Badge $badge
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereBadgeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereEarnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereIsDisplayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserBadge whereUserId($value)
 * @mixin \Eloquent
 */
class UserBadge extends Model
{
    protected $fillable = ['user_id', 'badge_id', 'earned_at', 'displayed'];

    protected $casts = [
        'earned_at' => 'datetime',
        'displayed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }
}
