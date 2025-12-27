<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $cover_image
 * @property string $content_type
 * @property string $privacy
 * @property bool $is_featured
 * @property-read int|null $items_count
 * @property int $views_count
 * @property-read int|null $followers_count
 * @property bool $auto_play
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $followers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SeriesItem> $items
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SeriesProgress> $progress
 * @property-read int|null $progress_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereAutoPlay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereFollowersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereItemsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series wherePrivacy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Series whereViewsCount($value)
 * @mixin \Eloquent
 */
class Series extends Model
{
    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'cover_image',
        'content_type', 'privacy', 'is_featured', 'items_count',
        'views_count', 'followers_count', 'auto_play'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'auto_play' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SeriesItem::class)->orderBy('episode_number');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'series_followers')
            ->withPivot('notify_new_episodes')
            ->withTimestamps();
    }

    public function progress(): HasMany
    {
        return $this->hasMany(SeriesProgress::class);
    }

    public function getProgressFor(User $user): ?SeriesProgress
    {
        return $this->progress()->where('user_id', $user->id)->first();
    }
}
