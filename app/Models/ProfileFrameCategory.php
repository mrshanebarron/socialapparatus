<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $sort_order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProfileFrame> $frames
 * @property-read int|null $frames_count
 * @method static Builder<static>|ProfileFrameCategory active()
 * @method static Builder<static>|ProfileFrameCategory newModelQuery()
 * @method static Builder<static>|ProfileFrameCategory newQuery()
 * @method static Builder<static>|ProfileFrameCategory query()
 * @method static Builder<static>|ProfileFrameCategory whereCreatedAt($value)
 * @method static Builder<static>|ProfileFrameCategory whereDescription($value)
 * @method static Builder<static>|ProfileFrameCategory whereId($value)
 * @method static Builder<static>|ProfileFrameCategory whereIsActive($value)
 * @method static Builder<static>|ProfileFrameCategory whereName($value)
 * @method static Builder<static>|ProfileFrameCategory whereSlug($value)
 * @method static Builder<static>|ProfileFrameCategory whereSortOrder($value)
 * @method static Builder<static>|ProfileFrameCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProfileFrameCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function frames(): HasMany
    {
        return $this->hasMany(ProfileFrame::class, 'category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
