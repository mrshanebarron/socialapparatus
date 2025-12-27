<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $sender_id
 * @property int $recipient_id
 * @property string|null $tippable_type
 * @property int|null $tippable_id
 * @property numeric $amount
 * @property string $currency
 * @property string|null $message
 * @property bool $is_anonymous
 * @property string $payment_status
 * @property string|null $payment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $recipient
 * @property-read \App\Models\User $sender
 * @property-read Model|\Eloquent|null $tippable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereTippableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereTippableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tip extends Model
{
    protected $fillable = [
        'sender_id', 'recipient_id', 'tippable_type', 'tippable_id',
        'amount', 'currency', 'message', 'is_anonymous', 'payment_status', 'payment_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function tippable(): MorphTo
    {
        return $this->morphTo();
    }
}
