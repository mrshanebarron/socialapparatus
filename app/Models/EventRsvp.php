<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRsvp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRsvp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRsvp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRsvp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRsvp whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRsvp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRsvp whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRsvp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventRsvp whereUserId($value)
 * @mixin \Eloquent
 */
class EventRsvp extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
