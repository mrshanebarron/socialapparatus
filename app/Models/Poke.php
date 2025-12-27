<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $poker_id
 * @property int $poked_id
 * @property bool $is_seen
 * @property \Illuminate\Support\Carbon|null $poked_back_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $poked
 * @property-read \App\Models\User $poker
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poke newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poke newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poke query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poke whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poke whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poke whereIsSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poke wherePokedBackAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poke wherePokedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poke wherePokerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poke whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Poke extends Model
{
    protected $fillable = ['poker_id', 'poked_id', 'is_seen', 'poked_back_at'];

    protected $casts = [
        'is_seen' => 'boolean',
        'poked_back_at' => 'datetime',
    ];

    public function poker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'poker_id');
    }

    public function poked(): BelongsTo
    {
        return $this->belongsTo(User::class, 'poked_id');
    }
}
