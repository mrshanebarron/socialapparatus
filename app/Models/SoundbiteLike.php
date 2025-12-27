<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $soundbite_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Soundbite $soundbite
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteLike query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteLike whereSoundbiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteLike whereUserId($value)
 * @mixin \Eloquent
 */
class SoundbiteLike extends Model
{
    use HasFactory;

    protected $fillable = ['soundbite_id', 'user_id'];

    public function soundbite(): BelongsTo
    {
        return $this->belongsTo(Soundbite::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
