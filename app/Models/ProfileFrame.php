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
 * @property int|null $category_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $image_path
 * @property string|null $thumbnail_path
 * @property bool $is_animated
 * @property bool $is_premium
 * @property int $coin_cost
 * @property \Illuminate\Support\Carbon|null $available_from
 * @property \Illuminate\Support\Carbon|null $available_until
 * @property bool $is_active
 * @property int $times_used
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProfileFrameCategory|null $category
 * @property-read string|null $image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserProfileFrame> $userFrames
 * @property-read int|null $user_frames_count
 * @method static Builder<static>|ProfileFrame active()
 * @method static Builder<static>|ProfileFrame newModelQuery()
 * @method static Builder<static>|ProfileFrame newQuery()
 * @method static Builder<static>|ProfileFrame query()
 * @method static Builder<static>|ProfileFrame whereAvailableFrom($value)
 * @method static Builder<static>|ProfileFrame whereAvailableUntil($value)
 * @method static Builder<static>|ProfileFrame whereCategoryId($value)
 * @method static Builder<static>|ProfileFrame whereCoinCost($value)
 * @method static Builder<static>|ProfileFrame whereCreatedAt($value)
 * @method static Builder<static>|ProfileFrame whereDescription($value)
 * @method static Builder<static>|ProfileFrame whereId($value)
 * @method static Builder<static>|ProfileFrame whereImagePath($value)
 * @method static Builder<static>|ProfileFrame whereIsActive($value)
 * @method static Builder<static>|ProfileFrame whereIsAnimated($value)
 * @method static Builder<static>|ProfileFrame whereIsPremium($value)
 * @method static Builder<static>|ProfileFrame whereName($value)
 * @method static Builder<static>|ProfileFrame whereSlug($value)
 * @method static Builder<static>|ProfileFrame whereThumbnailPath($value)
 * @method static Builder<static>|ProfileFrame whereTimesUsed($value)
 * @method static Builder<static>|ProfileFrame whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProfileFrame extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'image_path', 'thumbnail_path',
        'is_animated', 'is_premium', 'coin_cost', 'available_from', 'available_until',
        'is_active', 'times_used',
    ];

    protected $casts = [
        'is_animated' => 'boolean',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProfileFrameCategory::class, 'category_id');
    }

    public function userFrames(): HasMany
    {
        return $this->hasMany(UserProfileFrame::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}
