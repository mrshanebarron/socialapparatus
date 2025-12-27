<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $board_id
 * @property string $name
 * @property string|null $color
 * @property int $position
 * @property-read int|null $items_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Board $board
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BoardItem> $items
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn whereBoardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn whereItemsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardColumn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BoardColumn extends Model
{
    protected $fillable = ['board_id', 'name', 'color', 'position', 'items_count'];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BoardItem::class)->orderBy('position');
    }
}
