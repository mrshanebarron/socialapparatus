<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property int|null $event_ticket_type_id
 * @property int $position
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $offered_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\EventTicketType|null $ticketType
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist whereEventTicketTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist whereOfferedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventWaitlist whereUserId($value)
 * @mixin \Eloquent
 */
class EventWaitlist extends Model
{
    protected $table = 'event_waitlist';

    protected $fillable = [
        'event_id', 'user_id', 'event_ticket_type_id', 'position',
        'status', 'offered_at', 'expires_at'
    ];

    protected $casts = [
        'offered_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(EventTicketType::class, 'event_ticket_type_id');
    }
}
