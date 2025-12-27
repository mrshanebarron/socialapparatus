<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property int $new_users
 * @property int $active_users
 * @property int $returning_users
 * @property int $total_posts
 * @property int $total_comments
 * @property int $total_reactions
 * @property int $total_messages
 * @property int $total_media_uploads
 * @property int $storage_used_bytes
 * @property int $new_groups
 * @property int $new_events
 * @property numeric $avg_session_minutes
 * @property array<array-key, mixed>|null $top_content
 * @property array<array-key, mixed>|null $demographics
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder<static>|PlatformMetric forDate($date)
 * @method static Builder<static>|PlatformMetric lastDays(int $days)
 * @method static Builder<static>|PlatformMetric newModelQuery()
 * @method static Builder<static>|PlatformMetric newQuery()
 * @method static Builder<static>|PlatformMetric query()
 * @method static Builder<static>|PlatformMetric whereActiveUsers($value)
 * @method static Builder<static>|PlatformMetric whereAvgSessionMinutes($value)
 * @method static Builder<static>|PlatformMetric whereCreatedAt($value)
 * @method static Builder<static>|PlatformMetric whereDate($value)
 * @method static Builder<static>|PlatformMetric whereDemographics($value)
 * @method static Builder<static>|PlatformMetric whereId($value)
 * @method static Builder<static>|PlatformMetric whereNewEvents($value)
 * @method static Builder<static>|PlatformMetric whereNewGroups($value)
 * @method static Builder<static>|PlatformMetric whereNewUsers($value)
 * @method static Builder<static>|PlatformMetric whereReturningUsers($value)
 * @method static Builder<static>|PlatformMetric whereStorageUsedBytes($value)
 * @method static Builder<static>|PlatformMetric whereTopContent($value)
 * @method static Builder<static>|PlatformMetric whereTotalComments($value)
 * @method static Builder<static>|PlatformMetric whereTotalMediaUploads($value)
 * @method static Builder<static>|PlatformMetric whereTotalMessages($value)
 * @method static Builder<static>|PlatformMetric whereTotalPosts($value)
 * @method static Builder<static>|PlatformMetric whereTotalReactions($value)
 * @method static Builder<static>|PlatformMetric whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlatformMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'new_users', 'active_users', 'returning_users', 'total_posts', 'total_comments',
        'total_reactions', 'total_messages', 'total_media_uploads', 'storage_used_bytes',
        'new_groups', 'new_events', 'avg_session_minutes', 'top_content', 'demographics',
    ];

    protected $casts = ['date' => 'date', 'top_content' => 'array', 'demographics' => 'array', 'avg_session_minutes' => 'decimal:2'];

    public function scopeForDate(Builder $query, $date): Builder
    {
        return $query->whereDate('date', $date);
    }

    public function scopeLastDays(Builder $query, int $days): Builder
    {
        return $query->where('date', '>=', now()->subDays($days));
    }
}
