<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int|null $asker_id
 * @property int $recipient_id
 * @property string $question
 * @property string|null $answer
 * @property bool $is_anonymous
 * @property bool $is_public
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $answered_at
 * @property-read int|null $likes_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $asker
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $likes
 * @property-read \App\Models\User $recipient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereAnsweredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereAskerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereLikesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereRecipientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Question whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Question extends Model
{
    protected $fillable = [
        'asker_id', 'recipient_id', 'question', 'answer',
        'is_anonymous', 'is_public', 'status',
        'answered_at', 'likes_count'
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_public' => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function asker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asker_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'question_likes')
            ->withTimestamps();
    }
}
