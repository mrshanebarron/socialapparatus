<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $sort_order
 * @property bool $is_required
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AvatarPart> $parts
 * @property-read int|null $parts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarCategory whereIsRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarCategory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AvatarCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'sort_order', 'is_required'];

    protected $casts = ['is_required' => 'boolean'];

    public function parts(): HasMany
    {
        return $this->hasMany(AvatarPart::class, 'category_id');
    }
}
