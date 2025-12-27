<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $post_id
 * @property string $question
 * @property bool $allow_multiple
 * @property bool $show_results_before_vote
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property int $total_votes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PollOption> $options
 * @property-read int|null $options_count
 * @property-read \App\Models\Post $post
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PollVote> $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereAllowMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereShowResultsBeforeVote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereTotalVotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Poll extends Model
{
    protected $fillable = [
        'post_id',
        'question',
        'allow_multiple',
        'show_results_before_vote',
        'ends_at',
        'total_votes',
    ];

    protected $casts = [
        'allow_multiple' => 'boolean',
        'show_results_before_vote' => 'boolean',
        'ends_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class)->orderBy('order');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function hasEnded(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    public function hasVoted(User $user): bool
    {
        return $this->votes()->where('user_id', $user->id)->exists();
    }

    public function getUserVotes(User $user): array
    {
        return $this->votes()
            ->where('user_id', $user->id)
            ->pluck('poll_option_id')
            ->toArray();
    }

    public function vote(User $user, array $optionIds): void
    {
        if ($this->hasEnded()) {
            return;
        }

        // Remove existing votes if not multiple choice
        if (!$this->allow_multiple) {
            $existingVotes = $this->votes()->where('user_id', $user->id)->get();
            foreach ($existingVotes as $vote) {
                $vote->option->decrement('votes_count');
                $vote->delete();
            }
            $this->decrement('total_votes', $existingVotes->count());
        }

        foreach ($optionIds as $optionId) {
            $option = $this->options()->find($optionId);
            if (!$option) continue;

            // Check if already voted for this option
            $exists = $this->votes()
                ->where('user_id', $user->id)
                ->where('poll_option_id', $optionId)
                ->exists();

            if (!$exists) {
                PollVote::create([
                    'user_id' => $user->id,
                    'poll_id' => $this->id,
                    'poll_option_id' => $optionId,
                ]);
                $option->increment('votes_count');
                $this->increment('total_votes');
            }
        }
    }

    public static function createWithOptions(Post $post, string $question, array $options, array $settings = []): self
    {
        $poll = self::create([
            'post_id' => $post->id,
            'question' => $question,
            'allow_multiple' => $settings['allow_multiple'] ?? false,
            'show_results_before_vote' => $settings['show_results_before_vote'] ?? true,
            'ends_at' => $settings['ends_at'] ?? null,
        ]);

        foreach ($options as $index => $text) {
            $poll->options()->create([
                'text' => $text,
                'order' => $index,
            ]);
        }

        return $poll;
    }
}
