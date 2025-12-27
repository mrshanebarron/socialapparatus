<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $host_id
 * @property int|null $group_id
 * @property string $title
 * @property string|null $description
 * @property string $video_url
 * @property string|null $video_provider
 * @property string|null $video_thumbnail
 * @property string $status
 * @property string $privacy
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 * @property int $current_time_seconds
 * @property bool $is_playing
 * @property int|null $max_participants
 * @property int $participant_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Group|null $group
 * @property-read \App\Models\User $host
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WatchPartyMessage> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WatchPartyParticipant> $participants
 * @property-read int|null $participants_count
 * @method static Builder<static>|WatchParty live()
 * @method static Builder<static>|WatchParty newModelQuery()
 * @method static Builder<static>|WatchParty newQuery()
 * @method static Builder<static>|WatchParty query()
 * @method static Builder<static>|WatchParty whereCreatedAt($value)
 * @method static Builder<static>|WatchParty whereCurrentTimeSeconds($value)
 * @method static Builder<static>|WatchParty whereDescription($value)
 * @method static Builder<static>|WatchParty whereEndedAt($value)
 * @method static Builder<static>|WatchParty whereGroupId($value)
 * @method static Builder<static>|WatchParty whereHostId($value)
 * @method static Builder<static>|WatchParty whereId($value)
 * @method static Builder<static>|WatchParty whereIsPlaying($value)
 * @method static Builder<static>|WatchParty whereMaxParticipants($value)
 * @method static Builder<static>|WatchParty whereParticipantCount($value)
 * @method static Builder<static>|WatchParty wherePrivacy($value)
 * @method static Builder<static>|WatchParty whereScheduledAt($value)
 * @method static Builder<static>|WatchParty whereStartedAt($value)
 * @method static Builder<static>|WatchParty whereStatus($value)
 * @method static Builder<static>|WatchParty whereTitle($value)
 * @method static Builder<static>|WatchParty whereUpdatedAt($value)
 * @method static Builder<static>|WatchParty whereVideoProvider($value)
 * @method static Builder<static>|WatchParty whereVideoThumbnail($value)
 * @method static Builder<static>|WatchParty whereVideoUrl($value)
 * @mixin \Eloquent
 */
class WatchParty extends Model
{
    use HasFactory;

    protected $fillable = [
        'host_id', 'group_id', 'title', 'description', 'video_url', 'video_provider',
        'video_thumbnail', 'status', 'privacy', 'scheduled_at', 'started_at', 'ended_at',
        'current_time_seconds', 'is_playing', 'max_participants', 'participant_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_playing' => 'boolean',
    ];

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(WatchPartyParticipant::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(WatchPartyMessage::class);
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('status', 'live');
    }

    public function start(): void
    {
        $this->update(['status' => 'live', 'started_at' => now(), 'is_playing' => true]);
    }

    public function end(): void
    {
        $this->update(['status' => 'ended', 'ended_at' => now(), 'is_playing' => false]);
    }
}
