<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $category_id
 * @property string|null $title
 * @property string|null $description
 * @property string $audio_path
 * @property string|null $waveform_path
 * @property string|null $cover_image
 * @property int $duration_seconds
 * @property string|null $transcript
 * @property string $transcript_status
 * @property string $privacy
 * @property-read int|null $plays_count
 * @property-read int|null $likes_count
 * @property int $comments_count
 * @property int $shares_count
 * @property bool $allow_comments
 * @property bool $allow_duets
 * @property bool $is_featured
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SoundbiteCategory|null $category
 * @property-read string|null $audio_url
 * @property-read string $formatted_duration
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SoundbiteLike> $likes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SoundbitePlay> $plays
 * @property-read \App\Models\User $user
 * @method static Builder<static>|Soundbite newModelQuery()
 * @method static Builder<static>|Soundbite newQuery()
 * @method static Builder<static>|Soundbite public()
 * @method static Builder<static>|Soundbite query()
 * @method static Builder<static>|Soundbite whereAllowComments($value)
 * @method static Builder<static>|Soundbite whereAllowDuets($value)
 * @method static Builder<static>|Soundbite whereAudioPath($value)
 * @method static Builder<static>|Soundbite whereCategoryId($value)
 * @method static Builder<static>|Soundbite whereCommentsCount($value)
 * @method static Builder<static>|Soundbite whereCoverImage($value)
 * @method static Builder<static>|Soundbite whereCreatedAt($value)
 * @method static Builder<static>|Soundbite whereDescription($value)
 * @method static Builder<static>|Soundbite whereDurationSeconds($value)
 * @method static Builder<static>|Soundbite whereId($value)
 * @method static Builder<static>|Soundbite whereIsFeatured($value)
 * @method static Builder<static>|Soundbite whereLikesCount($value)
 * @method static Builder<static>|Soundbite wherePlaysCount($value)
 * @method static Builder<static>|Soundbite wherePrivacy($value)
 * @method static Builder<static>|Soundbite whereSharesCount($value)
 * @method static Builder<static>|Soundbite whereTitle($value)
 * @method static Builder<static>|Soundbite whereTranscript($value)
 * @method static Builder<static>|Soundbite whereTranscriptStatus($value)
 * @method static Builder<static>|Soundbite whereUpdatedAt($value)
 * @method static Builder<static>|Soundbite whereUserId($value)
 * @method static Builder<static>|Soundbite whereWaveformPath($value)
 * @mixin \Eloquent
 */
class Soundbite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'description', 'audio_path', 'waveform_path',
        'cover_image', 'duration_seconds', 'transcript', 'transcript_status', 'privacy',
        'plays_count', 'likes_count', 'comments_count', 'shares_count', 'allow_comments',
        'allow_duets', 'is_featured',
    ];

    protected $casts = ['allow_comments' => 'boolean', 'allow_duets' => 'boolean', 'is_featured' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SoundbiteCategory::class, 'category_id');
    }

    public function plays(): HasMany
    {
        return $this->hasMany(SoundbitePlay::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(SoundbiteLike::class);
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('privacy', 'public');
    }

    public function getAudioUrlAttribute(): ?string
    {
        return $this->audio_path ? Storage::url($this->audio_path) : null;
    }

    public function getFormattedDurationAttribute(): string
    {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
