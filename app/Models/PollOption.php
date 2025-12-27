<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $poll_id
 * @property string $text
 * @property-read int|null $votes_count
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $percentage
 * @property-read \App\Models\Poll $poll
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PollVote> $votes
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption wherePollId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption whereVotesCount($value)
 * @mixin \Eloquent
 */
class PollOption extends Model
{
    protected $fillable = [
        'poll_id',
        'text',
        'votes_count',
        'order',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function getPercentageAttribute(): float
    {
        if ($this->poll->total_votes === 0) {
            return 0;
        }

        return round(($this->votes_count / $this->poll->total_votes) * 100, 1);
    }
}
