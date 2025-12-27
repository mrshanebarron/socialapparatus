<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $icon
 * @property int|null $parent_id
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MarketplaceCategory> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MarketplaceListing> $listings
 * @property-read int|null $listings_count
 * @property-read MarketplaceCategory|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MarketplaceCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'parent_id',
        'sort_order',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MarketplaceCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MarketplaceCategory::class, 'parent_id');
    }

    public function listings(): HasMany
    {
        return $this->hasMany(MarketplaceListing::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public const DEFAULT_CATEGORIES = [
        'Vehicles' => ['Cars', 'Motorcycles', 'Boats', 'Parts'],
        'Property Rentals' => ['Apartments', 'Houses', 'Rooms'],
        'Electronics' => ['Phones', 'Computers', 'TVs', 'Gaming'],
        'Clothing' => ['Men', 'Women', 'Kids', 'Shoes'],
        'Home & Garden' => ['Furniture', 'Appliances', 'Tools', 'Decor'],
        'Hobbies' => ['Sports', 'Music', 'Books', 'Collectibles'],
        'Family' => ['Baby & Kids', 'Toys', 'Pet Supplies'],
        'Free Stuff' => [],
    ];
}
