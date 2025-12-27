<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $friend_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $friend
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CloseFriend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CloseFriend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CloseFriend query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CloseFriend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CloseFriend whereFriendId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CloseFriend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CloseFriend whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CloseFriend whereUserId($value)
 * @mixin \Eloquent
 */
class CloseFriend extends Model
{
    protected $fillable = ['user_id', 'friend_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
