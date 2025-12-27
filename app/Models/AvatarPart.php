<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $slug
 * @property string $image_path
 * @property string|null $thumbnail_path
 * @property bool $is_premium
 * @property int $coin_cost
 * @property array<array-key, mixed>|null $color_options
 * @property bool $is_default
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AvatarCategory $category
 * @property-read string|null $image_url
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereCoinCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereColorOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereIsPremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereThumbnailPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarPart whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AvatarPart extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'image_path', 'thumbnail_path',
        'is_premium', 'coin_cost', 'color_options', 'is_default', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_premium' => 'boolean', 'is_default' => 'boolean', 'is_active' => 'boolean', 'color_options' => 'array'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AvatarCategory::class, 'category_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}
