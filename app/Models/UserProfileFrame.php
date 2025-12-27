<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $profile_frame_id
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $applied_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProfileFrame $frame
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame whereAppliedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame whereProfileFrameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserProfileFrame whereUserId($value)
 * @mixin \Eloquent
 */
class UserProfileFrame extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'profile_frame_id', 'is_active', 'applied_at', 'expires_at'];

    protected $casts = ['is_active' => 'boolean', 'applied_at' => 'datetime', 'expires_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function frame(): BelongsTo
    {
        return $this->belongsTo(ProfileFrame::class, 'profile_frame_id');
    }
}
