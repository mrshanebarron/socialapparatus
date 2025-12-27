<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $issued_by
 * @property int|null $moderation_queue_id
 * @property string $type
 * @property string $message
 * @property int $severity
 * @property bool $acknowledged
 * @property \Illuminate\Support\Carbon|null $acknowledged_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $issuedBy
 * @property-read \App\Models\ModerationQueue|null $moderationQueue
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereAcknowledged($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereAcknowledgedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereIssuedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereModerationQueueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserWarning whereUserId($value)
 * @mixin \Eloquent
 */
class UserWarning extends Model
{
    protected $fillable = [
        'user_id', 'issued_by', 'moderation_queue_id', 'type', 'message',
        'severity', 'acknowledged', 'acknowledged_at', 'expires_at'
    ];

    protected $casts = [
        'acknowledged' => 'boolean',
        'acknowledged_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function moderationQueue(): BelongsTo
    {
        return $this->belongsTo(ModerationQueue::class);
    }

    public function isActive(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        return true;
    }
}
