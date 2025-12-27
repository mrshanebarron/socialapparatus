<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $story_poll_id
 * @property int $user_id
 * @property int $option_index
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\StoryPoll $storyPoll
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPollVote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPollVote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPollVote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPollVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPollVote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPollVote whereOptionIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPollVote whereStoryPollId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPollVote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPollVote whereUserId($value)
 * @mixin \Eloquent
 */
class StoryPollVote extends Model
{
    protected $fillable = ['story_poll_id', 'user_id', 'option_index'];

    public function storyPoll(): BelongsTo
    {
        return $this->belongsTo(StoryPoll::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
