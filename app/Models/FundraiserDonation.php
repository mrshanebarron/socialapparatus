<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $fundraiser_id
 * @property int|null $user_id
 * @property string|null $donor_name
 * @property numeric $amount
 * @property string $currency
 * @property string|null $message
 * @property bool $is_anonymous
 * @property string|null $payment_provider
 * @property string|null $payment_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fundraiser $fundraiser
 * @property-read string $display_name
 * @property-read string $formatted_amount
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereDonorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereFundraiserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation wherePaymentProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FundraiserDonation whereUserId($value)
 * @mixin \Eloquent
 */
class FundraiserDonation extends Model
{
    protected $fillable = [
        'fundraiser_id',
        'user_id',
        'donor_name',
        'amount',
        'currency',
        'message',
        'is_anonymous',
        'payment_provider',
        'payment_id',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
    ];

    public function fundraiser(): BelongsTo
    {
        return $this->belongsTo(Fundraiser::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }
        return $this->donor_name ?? $this->user?->name ?? 'Anonymous';
    }

    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 0);
    }

    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
        $this->fundraiser->addDonation($this);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
