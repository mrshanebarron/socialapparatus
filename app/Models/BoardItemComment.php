<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $board_item_id
 * @property int $user_id
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BoardItem $boardItem
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItemComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItemComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItemComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItemComment whereBoardItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItemComment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItemComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItemComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItemComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BoardItemComment whereUserId($value)
 * @mixin \Eloquent
 */
class BoardItemComment extends Model
{
    protected $fillable = ['board_item_id', 'user_id', 'body'];

    public function boardItem(): BelongsTo
    {
        return $this->belongsTo(BoardItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
