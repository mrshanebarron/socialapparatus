<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $virtual_gift_id
 * @property int $sender_id
 * @property int $recipient_id
 * @property string $giftable_type
 * @property int $giftable_id
 * @property string|null $message
 * @property bool $is_anonymous
 * @property bool $is_seen
 * @property int $coin_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $giftable
 * @property-read \App\Models\User $recipient
 * @property-read \App\Models\User $sender
 * @property-read \App\Models\VirtualGift $virtualGift
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereCoinAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereGiftableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereGiftableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereIsSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentGift whereVirtualGiftId($value)
 * @mixin \Eloquent
 */
class SentGift extends Model
{
    protected $fillable = [
        'virtual_gift_id', 'sender_id', 'recipient_id', 'giftable_type', 'giftable_id',
        'message', 'is_anonymous', 'is_seen', 'coin_amount'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_seen' => 'boolean',
    ];

    public function virtualGift(): BelongsTo
    {
        return $this->belongsTo(VirtualGift::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function giftable(): MorphTo
    {
        return $this->morphTo();
    }
}
