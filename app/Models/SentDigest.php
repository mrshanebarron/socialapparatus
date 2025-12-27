<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property \Illuminate\Support\Carbon $period_start
 * @property \Illuminate\Support\Carbon $period_end
 * @property array<array-key, mixed>|null $content_summary
 * @property-read int|null $items_count
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property \Illuminate\Support\Carbon|null $opened_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DigestItem> $items
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest whereContentSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest whereItemsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest whereOpenedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest wherePeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest wherePeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentDigest whereUserId($value)
 * @mixin \Eloquent
 */
class SentDigest extends Model
{
    protected $fillable = [
        'user_id', 'type', 'period_start', 'period_end', 'content_summary',
        'items_count', 'status', 'sent_at', 'opened_at'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'content_summary' => 'array',
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DigestItem::class);
    }
}
