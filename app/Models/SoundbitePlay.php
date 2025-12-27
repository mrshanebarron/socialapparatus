<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $soundbite_id
 * @property int|null $user_id
 * @property int $listened_seconds
 * @property bool $completed
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Soundbite $soundbite
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay whereListenedSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay whereSoundbiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbitePlay whereUserId($value)
 * @mixin \Eloquent
 */
class SoundbitePlay extends Model
{
    use HasFactory;

    protected $fillable = ['soundbite_id', 'user_id', 'listened_seconds', 'completed', 'ip_address'];

    protected $casts = ['completed' => 'boolean'];

    public function soundbite(): BelongsTo
    {
        return $this->belongsTo(Soundbite::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
