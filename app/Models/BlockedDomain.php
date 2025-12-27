<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $domain
 * @property string|null $reason
 * @property int|null $blocked_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $blocker
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlockedDomain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlockedDomain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlockedDomain query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlockedDomain whereBlockedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlockedDomain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlockedDomain whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlockedDomain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlockedDomain whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BlockedDomain whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BlockedDomain extends Model
{
    use HasFactory;

    protected $fillable = ['domain', 'reason', 'blocked_by'];

    public function blocker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public static function isBlocked(string $url): bool
    {
        $domain = parse_url($url, PHP_URL_HOST);
        return $domain ? self::where('domain', $domain)->exists() : false;
    }
}
