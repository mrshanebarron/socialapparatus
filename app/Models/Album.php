<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $cover_photo
 * @property string $privacy
 * @property-read int|null $media_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_collaborative
 * @property string $collaboration_type
 * @property int $collaborator_count
 * @property int|null $max_collaborators
 * @property-read string|null $cover_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $media
 * @property-read \App\Models\User $user
 * @method static Builder<static>|Album newModelQuery()
 * @method static Builder<static>|Album newQuery()
 * @method static Builder<static>|Album query()
 * @method static Builder<static>|Album visibleTo(?\App\Models\User $user)
 * @method static Builder<static>|Album whereCollaborationType($value)
 * @method static Builder<static>|Album whereCollaboratorCount($value)
 * @method static Builder<static>|Album whereCoverPhoto($value)
 * @method static Builder<static>|Album whereCreatedAt($value)
 * @method static Builder<static>|Album whereDescription($value)
 * @method static Builder<static>|Album whereId($value)
 * @method static Builder<static>|Album whereIsCollaborative($value)
 * @method static Builder<static>|Album whereMaxCollaborators($value)
 * @method static Builder<static>|Album whereMediaCount($value)
 * @method static Builder<static>|Album whereName($value)
 * @method static Builder<static>|Album wherePrivacy($value)
 * @method static Builder<static>|Album whereSlug($value)
 * @method static Builder<static>|Album whereUpdatedAt($value)
 * @method static Builder<static>|Album whereUserId($value)
 * @mixin \Eloquent
 */
class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'cover_photo',
        'privacy',
        'media_count',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->name) . '-' . Str::random(6);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
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

    public function getCoverPhotoUrlAttribute(): ?string
    {
        if ($this->cover_photo) {
            return asset('storage/' . $this->cover_photo);
        }

        $firstMedia = $this->media()->where('type', 'image')->first();
        return $firstMedia ? asset('storage/' . $firstMedia->thumbnail ?? $firstMedia->path) : null;
    }
}
