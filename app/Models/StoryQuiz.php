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
 * @property int $correct_option_index
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StoryQuizAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Story $story
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuiz query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuiz whereCorrectOptionIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuiz whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuiz whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuiz whereStoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuiz whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoryQuiz extends Model
{
    protected $fillable = ['story_id', 'question', 'options', 'correct_option_index'];

    protected $casts = [
        'options' => 'array',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(StoryQuizAnswer::class);
    }

    public function answer(User $user, int $optionIndex): StoryQuizAnswer
    {
        return $this->answers()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'selected_option_index' => $optionIndex,
                'is_correct' => $optionIndex === $this->correct_option_index,
            ]
        );
    }
}
