<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int|null $moderation_queue_id
 * @property int $moderator_id
 * @property string $target_type
 * @property int $target_id
 * @property int|null $target_user_id
 * @property string $action
 * @property string|null $reason
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ModerationQueue|null $moderationQueue
 * @property-read \App\Models\User $moderator
 * @property-read Model|\Eloquent $target
 * @property-read \App\Models\User|null $targetUser
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereModerationQueueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereModeratorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereTargetUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationAction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModerationAction extends Model
{
    protected $fillable = [
        'moderation_queue_id', 'moderator_id', 'target_type', 'target_id',
        'target_user_id', 'action', 'reason', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function moderationQueue(): BelongsTo
    {
        return $this->belongsTo(ModerationQueue::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
