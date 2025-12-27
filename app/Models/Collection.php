<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $cover_image
 * @property string $visibility
 * @property string $type
 * @property int $is_collaborative
 * @property-read int|null $items_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $collaborators
 * @property-read int|null $collaborators_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CollectionItem> $items
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereIsCollaborative($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereItemsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Collection whereVisibility($value)
 * @mixin \Eloquent
 */
class Collection extends Model
{
    protected $fillable = [
        'user_id', 'name', 'slug', 'description',
        'cover_image', 'privacy', 'is_default', 'items_count'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CollectionItem::class);
    }

    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'collection_collaborators')
            ->withPivot('role')
            ->withTimestamps();
    }
}
