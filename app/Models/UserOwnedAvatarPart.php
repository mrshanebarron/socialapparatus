<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $avatar_part_id
 * @property int $coins_spent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AvatarPart $part
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedAvatarPart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedAvatarPart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedAvatarPart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedAvatarPart whereAvatarPartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedAvatarPart whereCoinsSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedAvatarPart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedAvatarPart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedAvatarPart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedAvatarPart whereUserId($value)
 * @mixin \Eloquent
 */
class UserOwnedAvatarPart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'avatar_part_id', 'coins_spent'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(AvatarPart::class, 'avatar_part_id');
    }
}
