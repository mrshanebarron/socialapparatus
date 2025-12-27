<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $post_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Post $post
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HiddenPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HiddenPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HiddenPost query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HiddenPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HiddenPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HiddenPost wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HiddenPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HiddenPost whereUserId($value)
 * @mixin \Eloquent
 */
class HiddenPost extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public static function hide(User $user, Post $post): self
    {
        return self::firstOrCreate([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public static function unhide(User $user, Post $post): bool
    {
        return self::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->delete() > 0;
    }

    public static function isHidden(User $user, Post $post): bool
    {
        return self::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->exists();
    }
}
