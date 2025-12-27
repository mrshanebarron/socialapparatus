<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $body
 * @property string|null $featured_image
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property string $visibility
 * @property bool $comments_enabled
 * @property int $views_count
 * @property-read int|null $likes_count
 * @property-read int|null $comments_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read string|null $featured_image_url
 * @property-read int $reading_time
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Like> $likes
 * @property-read \App\Models\User $user
 * @method static Builder<static>|Article drafts()
 * @method static Builder<static>|Article newModelQuery()
 * @method static Builder<static>|Article newQuery()
 * @method static Builder<static>|Article onlyTrashed()
 * @method static Builder<static>|Article published()
 * @method static Builder<static>|Article query()
 * @method static Builder<static>|Article visibleTo(?\App\Models\User $user)
 * @method static Builder<static>|Article whereBody($value)
 * @method static Builder<static>|Article whereCommentsCount($value)
 * @method static Builder<static>|Article whereCommentsEnabled($value)
 * @method static Builder<static>|Article whereCreatedAt($value)
 * @method static Builder<static>|Article whereDeletedAt($value)
 * @method static Builder<static>|Article whereExcerpt($value)
 * @method static Builder<static>|Article whereFeaturedImage($value)
 * @method static Builder<static>|Article whereId($value)
 * @method static Builder<static>|Article whereLikesCount($value)
 * @method static Builder<static>|Article wherePublishedAt($value)
 * @method static Builder<static>|Article whereSlug($value)
 * @method static Builder<static>|Article whereStatus($value)
 * @method static Builder<static>|Article whereTitle($value)
 * @method static Builder<static>|Article whereUpdatedAt($value)
 * @method static Builder<static>|Article whereUserId($value)
 * @method static Builder<static>|Article whereViewsCount($value)
 * @method static Builder<static>|Article whereVisibility($value)
 * @method static Builder<static>|Article withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Article withoutTrashed()
 * @mixin \Eloquent
 */
class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'body',
        'featured_image',
        'status',
        'published_at',
        'visibility',
        'comments_enabled',
        'views_count',
        'likes_count',
        'comments_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'comments_enabled' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title) . '-' . Str::random(6);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeDrafts(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        return $query->where(function ($q) use ($user) {
            $q->where('visibility', 'public')
              ->where('status', 'published')
              ->where('published_at', '<=', now());

            if ($user) {
                $q->orWhere('user_id', $user->id)
                  ->orWhere(function ($q2) use ($user) {
                      $q2->where('visibility', 'friends')
                         ->where('status', 'published')
                         ->where('published_at', '<=', now())
                         ->whereHas('user', function ($q3) use ($user) {
                             $q3->whereHas('sentConnections', function ($q4) use ($user) {
                                 $q4->friends()->accepted()->where('target_id', $user->id);
                             })->orWhereHas('receivedConnections', function ($q4) use ($user) {
                                 $q4->friends()->accepted()->where('user_id', $user->id);
                             });
                         });
                  });
            }
        });
    }

    public function isVisibleTo(?User $user): bool
    {
        if ($this->status !== 'published' || $this->published_at > now()) {
            return $user && $this->user_id === $user->id;
        }

        if ($this->visibility === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($this->user_id === $user->id) {
            return true;
        }

        if ($this->visibility === 'friends') {
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

    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublish(): void
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->body));
        return max(1, ceil($words / 200)); // 200 words per minute
    }
}
