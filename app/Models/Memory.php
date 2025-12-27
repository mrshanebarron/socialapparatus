<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $user_id
 * @property string $memorable_type
 * @property int $memorable_id
 * @property \Illuminate\Support\Carbon $memory_date
 * @property int $years_ago
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $notified_at
 * @property \Illuminate\Support\Carbon|null $shared_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MemoryInteraction> $interactions
 * @property-read int|null $interactions_count
 * @property-read Model|\Eloquent $memorable
 * @property-read \App\Models\User $user
 * @method static Builder<static>|Memory forToday()
 * @method static Builder<static>|Memory newModelQuery()
 * @method static Builder<static>|Memory newQuery()
 * @method static Builder<static>|Memory pending()
 * @method static Builder<static>|Memory query()
 * @method static Builder<static>|Memory whereCreatedAt($value)
 * @method static Builder<static>|Memory whereId($value)
 * @method static Builder<static>|Memory whereMemorableId($value)
 * @method static Builder<static>|Memory whereMemorableType($value)
 * @method static Builder<static>|Memory whereMemoryDate($value)
 * @method static Builder<static>|Memory whereNotifiedAt($value)
 * @method static Builder<static>|Memory whereSharedAt($value)
 * @method static Builder<static>|Memory whereStatus($value)
 * @method static Builder<static>|Memory whereUpdatedAt($value)
 * @method static Builder<static>|Memory whereUserId($value)
 * @method static Builder<static>|Memory whereYearsAgo($value)
 * @mixin \Eloquent
 */
class Memory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'memorable_type',
        'memorable_id',
        'memory_date',
        'years_ago',
        'status',
        'notified_at',
        'shared_at',
    ];

    protected $casts = [
        'memory_date' => 'date',
        'notified_at' => 'datetime',
        'shared_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function memorable(): MorphTo
    {
        return $this->morphTo();
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(MemoryInteraction::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeForToday(Builder $query): Builder
    {
        return $query->whereMonth('memory_date', now()->month)
                     ->whereDay('memory_date', now()->day);
    }

    public function share(): void
    {
        $this->update(['status' => 'shared', 'shared_at' => now()]);
    }

    public function hide(): void
    {
        $this->update(['status' => 'hidden']);
    }

    public function dismiss(): void
    {
        $this->update(['status' => 'dismissed']);
    }
}
