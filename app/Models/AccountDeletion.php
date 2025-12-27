<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $reason
 * @property string|null $feedback
 * @property \Illuminate\Support\Carbon $scheduled_for
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion whereScheduledFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountDeletion whereUserId($value)
 * @mixin \Eloquent
 */
class AccountDeletion extends Model
{
    protected $fillable = [
        'user_id', 'reason', 'feedback',
        'scheduled_for', 'cancelled_at'
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
