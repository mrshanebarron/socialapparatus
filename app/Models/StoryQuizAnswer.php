<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $story_quiz_id
 * @property int $user_id
 * @property int $selected_option_index
 * @property bool $is_correct
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\StoryQuiz $storyQuiz
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuizAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuizAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuizAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuizAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuizAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuizAnswer whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuizAnswer whereSelectedOptionIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuizAnswer whereStoryQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuizAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuizAnswer whereUserId($value)
 * @mixin \Eloquent
 */
class StoryQuizAnswer extends Model
{
    protected $fillable = ['story_quiz_id', 'user_id', 'selected_option_index', 'is_correct'];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function storyQuiz(): BelongsTo
    {
        return $this->belongsTo(StoryQuiz::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
