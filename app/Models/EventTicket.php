<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $event_id
 * @property int $event_ticket_type_id
 * @property int $user_id
 * @property string $ticket_code
 * @property string|null $qr_code
 * @property string $status
 * @property string|null $attendee_name
 * @property string|null $attendee_email
 * @property \Illuminate\Support\Carbon|null $checked_in_at
 * @property int|null $checked_in_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $checkedInBy
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\EventTicketType $ticketType
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereAttendeeEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereAttendeeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereCheckedInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereCheckedInBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereEventTicketTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereQrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereTicketCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereUserId($value)
 * @mixin \Eloquent
 */
class EventTicket extends Model
{
    protected $fillable = [
        'event_id', 'event_ticket_type_id', 'user_id', 'ticket_code', 'qr_code',
        'status', 'attendee_name', 'attendee_email', 'checked_in_at', 'checked_in_by'
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ticket) {
            $ticket->ticket_code = $ticket->ticket_code ?? strtoupper(Str::random(12));
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(EventTicketType::class, 'event_ticket_type_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function checkIn(User $checkedInBy): bool
    {
        if ($this->status !== 'valid') return false;

        $this->update([
            'status' => 'used',
            'checked_in_at' => now(),
            'checked_in_by' => $checkedInBy->id,
        ]);

        return true;
    }
}
