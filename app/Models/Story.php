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
 * @property string $type
 * @property string|null $media_path
 * @property string|null $text_content
 * @property string|null $background_color
 * @property string|null $font_style
 * @property-read int|null $views_count
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_archived
 * @property int $save_to_archive
 * @property-read string|null $media_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StoryPoll> $polls
 * @property-read int|null $polls_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StoryQuestion> $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StoryQuiz> $quizzes
 * @property-read int|null $quizzes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StorySlider> $sliders
 * @property-read int|null $sliders_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StoryView> $views
 * @method static Builder<static>|Story active()
 * @method static Builder<static>|Story expired()
 * @method static Builder<static>|Story newModelQuery()
 * @method static Builder<static>|Story newQuery()
 * @method static Builder<static>|Story query()
 * @method static Builder<static>|Story whereBackgroundColor($value)
 * @method static Builder<static>|Story whereCreatedAt($value)
 * @method static Builder<static>|Story whereExpiresAt($value)
 * @method static Builder<static>|Story whereFontStyle($value)
 * @method static Builder<static>|Story whereId($value)
 * @method static Builder<static>|Story whereIsArchived($value)
 * @method static Builder<static>|Story whereMediaPath($value)
 * @method static Builder<static>|Story whereSaveToArchive($value)
 * @method static Builder<static>|Story whereTextContent($value)
 * @method static Builder<static>|Story whereType($value)
 * @method static Builder<static>|Story whereUpdatedAt($value)
 * @method static Builder<static>|Story whereUserId($value)
 * @method static Builder<static>|Story whereViewsCount($value)
 * @mixin \Eloquent
 */
class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'media_path',
        'text_content',
        'background_color',
        'font_style',
        'views_count',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(StoryView::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }

    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    public function isActive(): bool
    {
        return !$this->isExpired();
    }

    public function hasBeenViewedBy(User $user): bool
    {
        return $this->views()->where('user_id', $user->id)->exists();
    }

    public function markAsViewedBy(User $user): void
    {
        if (!$this->hasBeenViewedBy($user) && $user->id !== $this->user_id) {
            $this->views()->create(['user_id' => $user->id]);
            $this->increment('views_count');
        }
    }

    public function getMediaUrlAttribute(): ?string
    {
        return $this->media_path ? Storage::url($this->media_path) : null;
    }

    public static function createTextStory(User $user, string $text, string $backgroundColor = '#4F46E5', string $fontStyle = 'normal'): self
    {
        return self::create([
            'user_id' => $user->id,
            'type' => 'text',
            'text_content' => $text,
            'background_color' => $backgroundColor,
            'font_style' => $fontStyle,
            'expires_at' => now()->addHours(24),
        ]);
    }

    public static function createMediaStory(User $user, string $mediaPath, string $type = 'image'): self
    {
        return self::create([
            'user_id' => $user->id,
            'type' => $type,
            'media_path' => $mediaPath,
            'expires_at' => now()->addHours(24),
        ]);
    }

    // Interactive features relationships
    public function polls(): HasMany
    {
        return $this->hasMany(StoryPoll::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(StoryQuestion::class);
    }

    public function sliders(): HasMany
    {
        return $this->hasMany(StorySlider::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(StoryQuiz::class);
    }
}
