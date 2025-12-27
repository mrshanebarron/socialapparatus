<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $icon
 * @property int $sort_order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Soundbite> $soundbites
 * @property-read int|null $soundbites_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SoundbiteCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function soundbites(): HasMany
    {
        return $this->hasMany(Soundbite::class, 'category_id');
    }
}
