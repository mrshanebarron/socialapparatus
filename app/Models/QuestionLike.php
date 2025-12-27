<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $question_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Question $question
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionLike query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionLike whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuestionLike whereUserId($value)
 * @mixin \Eloquent
 */
class QuestionLike extends Model
{
    protected $fillable = ['question_id', 'user_id'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
