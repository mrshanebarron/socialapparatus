<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $watch_party_id
 * @property int $user_id
 * @property string $status
 * @property bool $is_host
 * @property bool $can_control
 * @property \Illuminate\Support\Carbon|null $joined_at
 * @property \Illuminate\Support\Carbon|null $left_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\WatchParty $watchParty
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant whereCanControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant whereIsHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant whereJoinedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant whereLeftAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyParticipant whereWatchPartyId($value)
 * @mixin \Eloquent
 */
class WatchPartyParticipant extends Model
{
    use HasFactory;

    protected $fillable = ['watch_party_id', 'user_id', 'status', 'is_host', 'can_control', 'joined_at', 'left_at'];

    protected $casts = ['is_host' => 'boolean', 'can_control' => 'boolean', 'joined_at' => 'datetime', 'left_at' => 'datetime'];

    public function watchParty(): BelongsTo
    {
        return $this->belongsTo(WatchParty::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
