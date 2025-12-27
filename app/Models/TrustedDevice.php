<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $device_id
 * @property string $device_name
 * @property string|null $device_type
 * @property string|null $browser
 * @property string $trusted_at
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice whereDeviceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice whereTrustedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TrustedDevice whereUserId($value)
 * @mixin \Eloquent
 */
class TrustedDevice extends Model
{
    protected $fillable = [
        'user_id', 'device_hash', 'device_name',
        'device_type', 'browser', 'os',
        'last_used_at', 'trusted_until'
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'trusted_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
