<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $domain
 * @property numeric $trust_score
 * @property int $times_shared
 * @property int $times_reported
 * @property bool $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder<static>|DomainTrustScore newModelQuery()
 * @method static Builder<static>|DomainTrustScore newQuery()
 * @method static Builder<static>|DomainTrustScore query()
 * @method static Builder<static>|DomainTrustScore trusted()
 * @method static Builder<static>|DomainTrustScore whereCreatedAt($value)
 * @method static Builder<static>|DomainTrustScore whereDomain($value)
 * @method static Builder<static>|DomainTrustScore whereId($value)
 * @method static Builder<static>|DomainTrustScore whereIsVerified($value)
 * @method static Builder<static>|DomainTrustScore whereTimesReported($value)
 * @method static Builder<static>|DomainTrustScore whereTimesShared($value)
 * @method static Builder<static>|DomainTrustScore whereTrustScore($value)
 * @method static Builder<static>|DomainTrustScore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DomainTrustScore extends Model
{
    use HasFactory;

    protected $fillable = ['domain', 'trust_score', 'times_shared', 'times_reported', 'is_verified'];

    protected $casts = ['trust_score' => 'decimal:2', 'is_verified' => 'boolean'];

    public function scopeTrusted(Builder $query): Builder
    {
        return $query->where('trust_score', '>=', 0.7);
    }

    public static function getForUrl(string $url): self
    {
        $domain = parse_url($url, PHP_URL_HOST);
        return self::firstOrCreate(['domain' => strtolower($domain)], ['trust_score' => 0.5]);
    }
}
