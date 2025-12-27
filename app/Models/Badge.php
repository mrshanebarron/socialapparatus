<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string|null $icon
 * @property string $color
 * @property string $category
 * @property array<array-key, mixed>|null $criteria
 * @property int $points
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereCriteria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Badge extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'color',
        'category', 'criteria', 'points', 'is_secret', 'is_active'
    ];

    protected $casts = [
        'criteria' => 'array',
        'is_secret' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('earned_at', 'displayed')
            ->withTimestamps();
    }
}
