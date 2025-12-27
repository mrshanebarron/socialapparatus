<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $story_question_id
 * @property int|null $user_id
 * @property string $response
 * @property bool $is_anonymous
 * @property bool $is_featured
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\StoryQuestion $storyQuestion
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse whereStoryQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestionResponse whereUserId($value)
 * @mixin \Eloquent
 */
class StoryQuestionResponse extends Model
{
    protected $fillable = ['story_question_id', 'user_id', 'response', 'is_anonymous', 'is_featured'];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function storyQuestion(): BelongsTo
    {
        return $this->belongsTo(StoryQuestion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
