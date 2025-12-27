<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property string $remind_before
 * @property \Illuminate\Support\Carbon $remind_at
 * @property bool $is_sent
 * @property string $notification_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder whereIsSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder whereNotificationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder whereRemindAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder whereRemindBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReminder whereUserId($value)
 * @mixin \Eloquent
 */
class EventReminder extends Model
{
    protected $fillable = [
        'event_id', 'user_id', 'remind_before',
        'remind_at', 'notification_type', 'is_sent'
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'is_sent' => 'boolean',
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
