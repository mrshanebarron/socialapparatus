<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $group_id
 * @property int|null $page_id
 * @property string $content
 * @property array<array-key, mixed>|null $media
 * @property string $privacy
 * @property array<array-key, mixed>|null $privacy_settings
 * @property \Illuminate\Support\Carbon $scheduled_for
 * @property string $timezone
 * @property string $status
 * @property int|null $published_post_id
 * @property string|null $failure_reason
 * @property int $retry_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Group|null $group
 * @property-read \App\Models\Page|null $page
 * @property-read \App\Models\User $user
 * @method static Builder<static>|ScheduledPost due()
 * @method static Builder<static>|ScheduledPost newModelQuery()
 * @method static Builder<static>|ScheduledPost newQuery()
 * @method static Builder<static>|ScheduledPost query()
 * @method static Builder<static>|ScheduledPost scheduled()
 * @method static Builder<static>|ScheduledPost whereContent($value)
 * @method static Builder<static>|ScheduledPost whereCreatedAt($value)
 * @method static Builder<static>|ScheduledPost whereFailureReason($value)
 * @method static Builder<static>|ScheduledPost whereGroupId($value)
 * @method static Builder<static>|ScheduledPost whereId($value)
 * @method static Builder<static>|ScheduledPost whereMedia($value)
 * @method static Builder<static>|ScheduledPost wherePageId($value)
 * @method static Builder<static>|ScheduledPost wherePrivacy($value)
 * @method static Builder<static>|ScheduledPost wherePrivacySettings($value)
 * @method static Builder<static>|ScheduledPost wherePublishedPostId($value)
 * @method static Builder<static>|ScheduledPost whereRetryCount($value)
 * @method static Builder<static>|ScheduledPost whereScheduledFor($value)
 * @method static Builder<static>|ScheduledPost whereStatus($value)
 * @method static Builder<static>|ScheduledPost whereTimezone($value)
 * @method static Builder<static>|ScheduledPost whereUpdatedAt($value)
 * @method static Builder<static>|ScheduledPost whereUserId($value)
 * @mixin \Eloquent
 */
class ScheduledPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'group_id', 'page_id', 'content', 'media', 'privacy', 'privacy_settings',
        'scheduled_for', 'timezone', 'status', 'published_post_id', 'failure_reason', 'retry_count',
    ];

    protected $casts = ['media' => 'array', 'privacy_settings' => 'array', 'scheduled_for' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeDue(Builder $query): Builder
    {
        return $query->scheduled()->where('scheduled_for', '<=', now());
    }
}
