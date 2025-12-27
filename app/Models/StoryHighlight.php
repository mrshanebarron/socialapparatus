<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $cover_image
 * @property int $position
 * @property-read int|null $stories_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Story> $stories
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight whereCoverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight whereStoriesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryHighlight whereUserId($value)
 * @mixin \Eloquent
 */
class StoryHighlight extends Model
{
    protected $fillable = ['user_id', 'title', 'cover_image', 'position', 'stories_count'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stories(): BelongsToMany
    {
        return $this->belongsToMany(Story::class, 'story_highlight_items')
            ->withPivot('position')
            ->orderByPivot('position')
            ->withTimestamps();
    }

    public function addStory(Story $story): void
    {
        $maxPosition = $this->stories()->max('story_highlight_items.position') ?? 0;
        $this->stories()->attach($story->id, ['position' => $maxPosition + 1]);
        $this->increment('stories_count');
    }
}
