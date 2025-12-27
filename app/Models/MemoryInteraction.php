<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $memory_id
 * @property int $user_id
 * @property string $type
 * @property string|null $reaction_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Memory $memory
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryInteraction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryInteraction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryInteraction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryInteraction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryInteraction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryInteraction whereMemoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryInteraction whereReactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryInteraction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryInteraction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemoryInteraction whereUserId($value)
 * @mixin \Eloquent
 */
class MemoryInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'memory_id',
        'user_id',
        'type',
        'reaction_type',
    ];

    public function memory(): BelongsTo
    {
        return $this->belongsTo(Memory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
