<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $story_id
 * @property string $prompt
 * @property bool $allow_anonymous
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StoryQuestionResponse> $responses
 * @property-read int|null $responses_count
 * @property-read \App\Models\Story $story
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestion whereAllowAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestion wherePrompt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestion whereStoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoryQuestion extends Model
{
    protected $fillable = ['story_id', 'prompt', 'allow_anonymous'];

    protected $casts = [
        'allow_anonymous' => 'boolean',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(StoryQuestionResponse::class);
    }
}
