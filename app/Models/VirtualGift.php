<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $image
 * @property string|null $animation
 * @property int $coin_cost
 * @property string $category
 * @property bool $is_animated
 * @property bool $is_premium
 * @property bool $is_active
 * @property int $times_sent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SentGift> $sentGifts
 * @property-read int|null $sent_gifts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift byCategory(string $category)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereAnimation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereCoinCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereIsAnimated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereIsPremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereTimesSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualGift whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VirtualGift extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'animation', 'coin_cost',
        'category', 'is_animated', 'is_premium', 'is_active', 'times_sent'
    ];

    protected $casts = [
        'is_animated' => 'boolean',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function sentGifts(): HasMany
    {
        return $this->hasMany(SentGift::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
