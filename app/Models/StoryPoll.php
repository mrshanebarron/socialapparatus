<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $story_id
 * @property string $question
 * @property array<array-key, mixed> $options
 * @property bool $allow_multiple
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Story $story
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StoryPollVote> $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll whereAllowMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll whereStoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryPoll whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoryPoll extends Model
{
    protected $fillable = ['story_id', 'question', 'options', 'allow_multiple', 'ends_at'];

    protected $casts = [
        'options' => 'array',
        'allow_multiple' => 'boolean',
        'ends_at' => 'datetime',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(StoryPollVote::class);
    }

    public function vote(User $user, int $optionIndex): ?StoryPollVote
    {
        if ($this->ends_at && $this->ends_at->isPast()) return null;
        if (!$this->allow_multiple && $this->votes()->where('user_id', $user->id)->exists()) return null;

        return $this->votes()->create([
            'user_id' => $user->id,
            'option_index' => $optionIndex,
        ]);
    }

    public function getResults(): array
    {
        $results = [];
        $totalVotes = $this->votes()->count();

        foreach ($this->options as $index => $option) {
            $votes = $this->votes()->where('option_index', $index)->count();
            $results[] = [
                'option' => $option,
                'votes' => $votes,
                'percentage' => $totalVotes > 0 ? round(($votes / $totalVotes) * 100) : 0,
            ];
        }

        return $results;
    }
}
