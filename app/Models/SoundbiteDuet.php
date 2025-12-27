<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $original_soundbite_id
 * @property int $response_soundbite_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Soundbite $original
 * @property-read \App\Models\Soundbite $response
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteDuet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteDuet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteDuet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteDuet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteDuet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteDuet whereOriginalSoundbiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteDuet whereResponseSoundbiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SoundbiteDuet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SoundbiteDuet extends Model
{
    use HasFactory;

    protected $fillable = ['original_soundbite_id', 'response_soundbite_id'];

    public function original(): BelongsTo
    {
        return $this->belongsTo(Soundbite::class, 'original_soundbite_id');
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(Soundbite::class, 'response_soundbite_id');
    }
}
