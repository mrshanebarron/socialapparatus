<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $category_id
 * @property string $title
 * @property string|null $description
 * @property numeric $price
 * @property string $currency
 * @property string $condition
 * @property array<array-key, mixed>|null $images
 * @property string|null $location_name
 * @property numeric|null $location_lat
 * @property numeric|null $location_lng
 * @property string $status
 * @property string $availability
 * @property bool $is_negotiable
 * @property bool $is_shipping_available
 * @property int $views_count
 * @property-read int|null $saves_count
 * @property int $messages_count
 * @property \Illuminate\Support\Carbon|null $featured_until
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\MarketplaceCategory|null $category
 * @property-read string|null $first_image_url
 * @property-read string $formatted_price
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Report> $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SavedPost> $saves
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing nearby(float $lat, float $lng, int $radius = 50)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereFeaturedUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereIsNegotiable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereIsShippingAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereLocationLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereLocationLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereLocationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereMessagesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereSavesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing whereViewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MarketplaceListing withoutTrashed()
 * @mixin \Eloquent
 */
class MarketplaceListing extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'currency',
        'condition',
        'images',
        'location_name',
        'location_lat',
        'location_lng',
        'status',
        'availability',
        'is_negotiable',
        'is_shipping_available',
        'views_count',
        'saves_count',
        'messages_count',
        'featured_until',
        'expires_at',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
        'is_negotiable' => 'boolean',
        'is_shipping_available' => 'boolean',
        'featured_until' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MarketplaceCategory::class, 'category_id');
    }

    public function saves(): MorphMany
    {
        return $this->morphMany(SavedPost::class, 'saveable');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function getFirstImageUrlAttribute(): ?string
    {
        if ($this->images && count($this->images) > 0) {
            return Storage::url($this->images[0]);
        }
        return null;
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function isFeatured(): bool
    {
        return $this->featured_until && $this->featured_until->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function markAsSold(): void
    {
        $this->update(['status' => 'sold']);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeNearby($query, float $lat, float $lng, int $radius = 50)
    {
        // Haversine formula for distance in miles
        return $query->selectRaw("*,
            (3959 * acos(cos(radians(?)) * cos(radians(location_lat)) * cos(radians(location_lng) - radians(?)) + sin(radians(?)) * sin(radians(location_lat)))) AS distance",
            [$lat, $lng, $lat])
            ->having('distance', '<', $radius)
            ->orderBy('distance');
    }

    public const CONDITIONS = [
        'new' => 'New',
        'like_new' => 'Like New',
        'good' => 'Good',
        'fair' => 'Fair',
        'poor' => 'Poor',
    ];
}
