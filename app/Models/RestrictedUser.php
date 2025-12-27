<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $restricted_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $restrictedUser
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestrictedUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestrictedUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestrictedUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestrictedUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestrictedUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestrictedUser whereRestrictedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestrictedUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestrictedUser whereUserId($value)
 * @mixin \Eloquent
 */
class RestrictedUser extends Model
{
    protected $fillable = ['user_id', 'restricted_user_id', 'reason'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restrictedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restricted_user_id');
    }
}
