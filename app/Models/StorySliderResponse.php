<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $story_slider_id
 * @property int $user_id
 * @property int $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\StorySlider $storySlider
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySliderResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySliderResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySliderResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySliderResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySliderResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySliderResponse whereStorySliderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySliderResponse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySliderResponse whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StorySliderResponse whereValue($value)
 * @mixin \Eloquent
 */
class StorySliderResponse extends Model
{
    protected $fillable = ['story_slider_id', 'user_id', 'value'];

    public function storySlider(): BelongsTo
    {
        return $this->belongsTo(StorySlider::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
