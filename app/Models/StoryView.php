<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $story_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Story $story
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryView query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryView whereStoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StoryView whereUserId($value)
 * @mixin \Eloquent
 */
class StoryView extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'user_id',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
