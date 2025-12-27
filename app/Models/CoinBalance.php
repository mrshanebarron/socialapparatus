<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $balance
 * @property int $lifetime_earned
 * @property int $lifetime_spent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinBalance whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinBalance whereLifetimeEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinBalance whereLifetimeSpent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinBalance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CoinBalance whereUserId($value)
 * @mixin \Eloquent
 */
class CoinBalance extends Model
{
    protected $fillable = ['user_id', 'balance', 'lifetime_earned', 'lifetime_spent'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function credit(int $amount, string $description, $transactionable = null): CoinTransaction
    {
        $this->balance += $amount;
        $this->lifetime_earned += $amount;
        $this->save();

        return $this->user->coinTransactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'transactionable_type' => $transactionable ? get_class($transactionable) : null,
            'transactionable_id' => $transactionable?->id,
        ]);
    }

    public function debit(int $amount, string $description, $transactionable = null): ?CoinTransaction
    {
        if ($this->balance < $amount) return null;

        $this->balance -= $amount;
        $this->lifetime_spent += $amount;
        $this->save();

        return $this->user->coinTransactions()->create([
            'type' => 'debit',
            'amount' => -$amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'transactionable_type' => $transactionable ? get_class($transactionable) : null,
            'transactionable_id' => $transactionable?->id,
        ]);
    }
}
