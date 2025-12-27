<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $watch_party_id
 * @property int $inviter_id
 * @property int $invitee_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $invitee
 * @property-read \App\Models\User $inviter
 * @property-read \App\Models\WatchParty $watchParty
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyInvitation whereInviteeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyInvitation whereInviterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyInvitation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyInvitation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WatchPartyInvitation whereWatchPartyId($value)
 * @mixin \Eloquent
 */
class WatchPartyInvitation extends Model
{
    use HasFactory;

    protected $fillable = ['watch_party_id', 'inviter_id', 'invitee_id', 'status'];

    public function watchParty(): BelongsTo
    {
        return $this->belongsTo(WatchParty::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invitee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }
}
