<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property string|null $collection
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Post $post
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedPost query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedPost whereCollection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedPost wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SavedPost whereUserId($value)
 * @mixin \Eloquent
 */
class SavedPost extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'collection',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public static function toggle(User $user, Post $post, ?string $collection = null): bool
    {
        $saved = self::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($saved) {
            $saved->delete();
            return false; // Unsaved
        }

        self::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'collection' => $collection,
        ]);

        return true; // Saved
    }
}
