<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $login_session_id
 * @property string $type
 * @property string $message
 * @property bool $is_read
 * @property int $email_sent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LoginSession $loginSession
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert whereEmailSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert whereLoginSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginAlert whereUserId($value)
 * @mixin \Eloquent
 */
class LoginAlert extends Model
{
    protected $fillable = [
        'user_id', 'login_session_id', 'type',
        'message', 'is_read', 'action_taken'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function loginSession(): BelongsTo
    {
        return $this->belongsTo(LoginSession::class);
    }
}
