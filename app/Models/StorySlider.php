<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $story_id
 * @property string $question
 * @property string $emoji
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StorySliderResponse> $responses
 * @property-read int|null $responses_count
 * @property-read \App\Models\Story $story
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySlider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySlider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySlider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySlider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySlider whereEmoji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySlider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySlider whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySlider whereStoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySlider whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StorySlider extends Model
{
    protected $fillable = ['story_id', 'question', 'emoji'];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(StorySliderResponse::class);
    }

    public function getAverageValue(): float
    {
        return $this->responses()->avg('value') ?? 0;
    }
}
