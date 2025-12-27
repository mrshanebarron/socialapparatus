<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $profile_frame_id
 * @property \Illuminate\Support\Carbon $purchased_at
 * @property int $coins_spent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProfileFrame $frame
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedFrame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedFrame newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedFrame query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedFrame whereCoinsSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedFrame whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedFrame whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedFrame whereProfileFrameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedFrame wherePurchasedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedFrame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserOwnedFrame whereUserId($value)
 * @mixin \Eloquent
 */
class UserOwnedFrame extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'profile_frame_id', 'purchased_at', 'coins_spent'];

    protected $casts = ['purchased_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function frame(): BelongsTo
    {
        return $this->belongsTo(ProfileFrame::class, 'profile_frame_id');
    }
}
