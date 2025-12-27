<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $event_id
 * @property string $name
 * @property string|null $description
 * @property numeric $price
 * @property int|null $quantity
 * @property int $sold_count
 * @property int $max_per_order
 * @property \Illuminate\Support\Carbon|null $sales_start_at
 * @property \Illuminate\Support\Carbon|null $sales_end_at
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventTicket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereMaxPerOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereSalesEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereSalesStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereSoldCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicketType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EventTicketType extends Model
{
    protected $fillable = [
        'event_id', 'name', 'description', 'price', 'quantity',
        'sold_count', 'max_per_order', 'sales_start_at', 'sales_end_at', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sales_start_at' => 'datetime',
        'sales_end_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(EventTicket::class);
    }

    public function isAvailable(): bool
    {
        if (!$this->is_active) return false;
        if ($this->sales_start_at && $this->sales_start_at->isFuture()) return false;
        if ($this->sales_end_at && $this->sales_end_at->isPast()) return false;
        if ($this->quantity && $this->sold_count >= $this->quantity) return false;
        return true;
    }

    public function availableQuantity(): ?int
    {
        return $this->quantity ? $this->quantity - $this->sold_count : null;
    }
}
