<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $poll_id
 * @property int $poll_option_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PollOption $option
 * @property-read \App\Models\Poll $poll
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollVote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollVote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollVote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollVote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollVote wherePollId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollVote wherePollOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollVote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollVote whereUserId($value)
 * @mixin \Eloquent
 */
class PollVote extends Model
{
    protected $fillable = [
        'user_id',
        'poll_id',
        'poll_option_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(PollOption::class, 'poll_option_id');
    }
}
