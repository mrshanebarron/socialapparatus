<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $moderatable_type
 * @property int $moderatable_id
 * @property string $reason
 * @property string|null $details
 * @property string $status
 * @property string $priority
 * @property int $report_count
 * @property int|null $assigned_to
 * @property int|null $moderated_by
 * @property string|null $moderator_notes
 * @property string|null $action_taken
 * @property \Illuminate\Support\Carbon|null $moderated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $assignedTo
 * @property-read Model|\Eloquent $moderatable
 * @property-read \App\Models\User|null $moderatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue byPriority()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereActionTaken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereModeratableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereModeratableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereModeratedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereModeratedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereModeratorNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereReportCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ModerationQueue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ModerationQueue extends Model
{
    protected $table = 'moderation_queue';

    protected $fillable = [
        'moderatable_type', 'moderatable_id', 'reason', 'details', 'status',
        'priority', 'report_count', 'assigned_to', 'moderated_by',
        'moderator_notes', 'action_taken', 'moderated_at'
    ];

    protected $casts = [
        'moderated_at' => 'datetime',
    ];

    public function moderatable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function moderatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByPriority($query)
    {
        return $query->orderByRaw("CASE priority WHEN 'urgent' THEN 1 WHEN 'high' THEN 2 WHEN 'normal' THEN 3 ELSE 4 END");
    }
}
