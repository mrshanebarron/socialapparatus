<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property bool $enabled
 * @property string $frequency
 * @property string $day_of_week
 * @property string $preferred_time
 * @property string $timezone
 * @property bool $include_trending
 * @property bool $include_friend_activity
 * @property bool $include_group_activity
 * @property bool $include_events
 * @property bool $include_memories
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereDayOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereIncludeEvents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereIncludeFriendActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereIncludeGroupActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereIncludeMemories($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereIncludeTrending($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference wherePreferredTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestPreference whereUserId($value)
 * @mixin \Eloquent
 */
class DigestPreference extends Model
{
    protected $fillable = [
        'user_id', 'enabled', 'frequency', 'day_of_week', 'preferred_time',
        'timezone', 'include_trending', 'include_friend_activity',
        'include_group_activity', 'include_events', 'include_memories'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'include_trending' => 'boolean',
        'include_friend_activity' => 'boolean',
        'include_group_activity' => 'boolean',
        'include_events' => 'boolean',
        'include_memories' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
