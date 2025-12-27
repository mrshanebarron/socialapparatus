<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property string $slug
 * @property string|null $category
 * @property string|null $description
 * @property string|null $avatar
 * @property string|null $cover_image
 * @property string|null $website
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property array<array-key, mixed>|null $hours
 * @property-read int|null $followers_count
 * @property int $likes_count
 * @property bool $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Connection> $followers
 * @property-read string $avatar_url
 * @property-read string|null $cover_image_url
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereFollowersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereWebsite($value)
 * @mixin \Eloquent
 */
class Page extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'category',
        'description',
        'avatar',
        'cover_image',
        'website',
        'email',
        'phone',
        'address',
        'hours',
        'followers_count',
        'likes_count',
        'is_verified',
    ];

    protected $casts = [
        'hours' => 'array',
        'is_verified' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (!$page->slug) {
                $page->slug = Str::slug($page->name);
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function followers(): MorphMany
    {
        return $this->morphMany(Connection::class, 'connectable');
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }

        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=6366f1&color=fff";
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->cover_image) {
            return Storage::url($this->cover_image);
        }
        return null;
    }

    public function isFollowedBy(User $user): bool
    {
        return $this->followers()
            ->where('user_id', $user->id)
            ->where('type', 'follow')
            ->exists();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public const CATEGORIES = [
        'Local Business',
        'Company',
        'Brand',
        'Artist',
        'Musician',
        'Public Figure',
        'Entertainment',
        'Sports',
        'Community',
        'Non-Profit',
        'Restaurant',
        'Website',
        'Blog',
        'Product',
        'Other',
    ];
}
