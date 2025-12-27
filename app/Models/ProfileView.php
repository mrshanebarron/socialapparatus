<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $profile_id
 * @property int|null $viewer_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Profile $profile
 * @property-read \App\Models\User|null $viewer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileView query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileView whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileView whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileView whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProfileView whereViewerId($value)
 * @mixin \Eloquent
 */
class ProfileView extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'viewer_id',
        'ip_address',
        'user_agent',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }
}
