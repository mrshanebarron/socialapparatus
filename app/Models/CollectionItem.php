<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $collection_id
 * @property string $collectable_type
 * @property int $collectable_id
 * @property int $added_by
 * @property string|null $note
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $collectable
 * @property-read \App\Models\Collection $collection
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem whereCollectableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem whereCollectableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CollectionItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CollectionItem extends Model
{
    protected $fillable = [
        'collection_id', 'user_id',
        'collectable_type', 'collectable_id',
        'note', 'position'
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function collectable(): MorphTo
    {
        return $this->morphTo();
    }
}
