<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $coins
 * @property int $bonus_coins
 * @property numeric $price
 * @property string $currency
 * @property bool $is_featured
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage whereBonusCoins($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage whereCoins($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinPackage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CoinPackage extends Model
{
    protected $fillable = ['name', 'coins', 'bonus_coins', 'price', 'currency', 'is_featured', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function totalCoins(): int
    {
        return $this->coins + $this->bonus_coins;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
