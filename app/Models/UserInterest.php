<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $interest
 * @property int $score
 * @property string|null $source
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInterest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInterest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInterest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInterest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInterest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInterest whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInterest whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInterest whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInterest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserInterest whereUserId($value)
 * @mixin \Eloquent
 */
class UserInterest extends Model
{
    protected $fillable = ['user_id', 'interest', 'score', 'source'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
