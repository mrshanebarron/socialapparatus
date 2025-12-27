<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $session_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $device_type
 * @property string|null $browser
 * @property string|null $platform
 * @property string|null $location
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property bool $is_current
 * @property int $is_trusted
 * @property string|null $last_activity_at
 * @property \Illuminate\Support\Carbon|null $logged_out_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereIsCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereIsTrusted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereLastActivityAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereLoggedOutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereUserId($value)
 * @mixin \Eloquent
 */
class LoginSession extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'ip_address', 'user_agent',
        'device_type', 'device_name', 'browser', 'os',
        'location', 'country', 'city',
        'is_current', 'last_active_at', 'logged_out_at'
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'last_active_at' => 'datetime',
        'logged_out_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
