<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $watch_party_id
 * @property int $user_id
 * @property string $message
 * @property int|null $video_timestamp
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\WatchParty $watchParty
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage whereVideoTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyMessage whereWatchPartyId($value)
 * @mixin \Eloquent
 */
class WatchPartyMessage extends Model
{
    use HasFactory;

    protected $fillable = ['watch_party_id', 'user_id', 'message', 'video_timestamp', 'type'];

    public function watchParty(): BelongsTo
    {
        return $this->belongsTo(WatchParty::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
