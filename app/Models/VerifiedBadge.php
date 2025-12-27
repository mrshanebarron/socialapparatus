<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $verification_request_id
 * @property string $badge_type
 * @property string $badge_color
 * @property string $badge_icon
 * @property string|null $display_name
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $granted_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property int|null $granted_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $granter
 * @property-read \App\Models\User $user
 * @property-read \App\Models\VerificationRequest|null $verificationRequest
 * @method static Builder<static>|VerifiedBadge active()
 * @method static Builder<static>|VerifiedBadge newModelQuery()
 * @method static Builder<static>|VerifiedBadge newQuery()
 * @method static Builder<static>|VerifiedBadge query()
 * @method static Builder<static>|VerifiedBadge whereBadgeColor($value)
 * @method static Builder<static>|VerifiedBadge whereBadgeIcon($value)
 * @method static Builder<static>|VerifiedBadge whereBadgeType($value)
 * @method static Builder<static>|VerifiedBadge whereCreatedAt($value)
 * @method static Builder<static>|VerifiedBadge whereDisplayName($value)
 * @method static Builder<static>|VerifiedBadge whereExpiresAt($value)
 * @method static Builder<static>|VerifiedBadge whereGrantedAt($value)
 * @method static Builder<static>|VerifiedBadge whereGrantedBy($value)
 * @method static Builder<static>|VerifiedBadge whereId($value)
 * @method static Builder<static>|VerifiedBadge whereIsActive($value)
 * @method static Builder<static>|VerifiedBadge whereUpdatedAt($value)
 * @method static Builder<static>|VerifiedBadge whereUserId($value)
 * @method static Builder<static>|VerifiedBadge whereVerificationRequestId($value)
 * @mixin \Eloquent
 */
class VerifiedBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'verification_request_id', 'badge_type', 'badge_color', 'badge_icon',
        'display_name', 'is_active', 'granted_at', 'expires_at', 'granted_by',
    ];

    protected $casts = ['is_active' => 'boolean', 'granted_at' => 'datetime', 'expires_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verificationRequest(): BelongsTo
    {
        return $this->belongsTo(VerificationRequest::class);
    }

    public function granter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
