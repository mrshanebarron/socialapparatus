<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $album_id
 * @property string|null $mediable_type
 * @property int|null $mediable_id
 * @property string $filename
 * @property string $original_filename
 * @property string $path
 * @property string $disk
 * @property string $mime_type
 * @property int $size
 * @property string $type
 * @property int|null $width
 * @property int|null $height
 * @property int|null $duration
 * @property string|null $thumbnail
 * @property string|null $caption
 * @property array<array-key, mixed>|null $metadata
 * @property string $privacy
 * @property-read int|null $likes_count
 * @property-read int|null $comments_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Album|null $album
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PhotoTag> $approvedTags
 * @property-read int|null $approved_tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read string $size_for_humans
 * @property-read string $thumbnail_url
 * @property-read string $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Like> $likes
 * @property-read Model|\Eloquent|null $mediable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PhotoTag> $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 * @method static Builder<static>|Media images()
 * @method static Builder<static>|Media newModelQuery()
 * @method static Builder<static>|Media newQuery()
 * @method static Builder<static>|Media query()
 * @method static Builder<static>|Media videos()
 * @method static Builder<static>|Media visibleTo(?\App\Models\User $user)
 * @method static Builder<static>|Media whereAlbumId($value)
 * @method static Builder<static>|Media whereCaption($value)
 * @method static Builder<static>|Media whereCommentsCount($value)
 * @method static Builder<static>|Media whereCreatedAt($value)
 * @method static Builder<static>|Media whereDisk($value)
 * @method static Builder<static>|Media whereDuration($value)
 * @method static Builder<static>|Media whereFilename($value)
 * @method static Builder<static>|Media whereHeight($value)
 * @method static Builder<static>|Media whereId($value)
 * @method static Builder<static>|Media whereLikesCount($value)
 * @method static Builder<static>|Media whereMediableId($value)
 * @method static Builder<static>|Media whereMediableType($value)
 * @method static Builder<static>|Media whereMetadata($value)
 * @method static Builder<static>|Media whereMimeType($value)
 * @method static Builder<static>|Media whereOriginalFilename($value)
 * @method static Builder<static>|Media wherePath($value)
 * @method static Builder<static>|Media wherePrivacy($value)
 * @method static Builder<static>|Media whereSize($value)
 * @method static Builder<static>|Media whereThumbnail($value)
 * @method static Builder<static>|Media whereType($value)
 * @method static Builder<static>|Media whereUpdatedAt($value)
 * @method static Builder<static>|Media whereUserId($value)
 * @method static Builder<static>|Media whereWidth($value)
 * @mixin \Eloquent
 */
class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'album_id',
        'mediable_type',
        'mediable_id',
        'filename',
        'original_filename',
        'path',
        'disk',
        'mime_type',
        'size',
        'type',
        'width',
        'height',
        'duration',
        'thumbnail',
        'caption',
        'metadata',
        'privacy',
        'likes_count',
        'comments_count',
    ];

    protected $casts = [
        'metadata' => 'array',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'duration' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tags(): HasMany
    {
        return $this->hasMany(PhotoTag::class);
    }

    public function approvedTags(): HasMany
    {
        return $this->hasMany(PhotoTag::class)->where('status', 'approved');
    }

    public function tagUser(User $taggedUser, User $taggedBy, float $x, float $y): PhotoTag
    {
        return $this->tags()->create([
            'user_id' => $taggedUser->id,
            'tagged_by_id' => $taggedBy->id,
            'x_position' => $x,
            'y_position' => $y,
            'status' => $taggedUser->id === $taggedBy->id ? 'approved' : 'pending',
        ]);
    }

    public function removeTag(User $user): void
    {
        $this->tags()->where('user_id', $user->id)->delete();
    }

    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            $q->where('privacy', 'public');

            if ($user) {
                $q->orWhere('user_id', $user->id)
                  ->orWhere(function ($q2) use ($user) {
                      $q2->where('privacy', 'friends')
                         ->whereHas('user', function ($q3) use ($user) {
                             $q3->whereHas('friends', function ($q4) use ($user) {
                                 $q4->where('friend_id', $user->id);
                             });
                         });
                  });
            }
        });
    }

    public function scopeImages(Builder $query): Builder
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos(Builder $query): Builder
    {
        return $query->where('type', 'video');
    }

    public function isVisibleTo(?User $user): bool
    {
        if ($this->privacy === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($this->user_id === $user->id) {
            return true;
        }

        if ($this->privacy === 'friends') {
            return $this->user->isFriendsWith($user);
        }

        return false;
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function toggleLike(User $user): void
    {
        if ($this->isLikedBy($user)) {
            $this->likes()->where('user_id', $user->id)->delete();
            $this->decrement('likes_count');
        } else {
            $this->likes()->create(['user_id' => $user->id]);
            $this->increment('likes_count');
        }
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? Storage::disk($this->disk)->url($this->thumbnail)
            : $this->url;
    }

    public function getSizeForHumansAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    public function isAudio(): bool
    {
        return $this->type === 'audio';
    }
}
