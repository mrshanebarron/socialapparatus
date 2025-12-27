<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property int $amount
 * @property int $balance_after
 * @property string $description
 * @property string $transactionable_type
 * @property int $transactionable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $transactionable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction whereBalanceAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction whereTransactionableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction whereTransactionableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinTransaction whereUserId($value)
 * @mixin \Eloquent
 */
class CoinTransaction extends Model
{
    protected $fillable = [
        'user_id', 'type', 'amount', 'balance_after', 'description',
        'transactionable_type', 'transactionable_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }
}
