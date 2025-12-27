<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $target_id
 * @property string $type
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $target
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection accepted()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection follows()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection friends()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Connection whereUserId($value)
 * @mixin \Eloquent
 */
class Connection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_id',
        'type',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeFriends($query)
    {
        return $query->where('type', 'friend');
    }

    public function scopeFollows($query)
    {
        return $query->where('type', 'follow');
    }

    public function accept(): void
    {
        $this->update(['status' => 'accepted']);

        // Update profile counts
        if ($this->type === 'friend') {
            $this->user->profile?->increment('friends_count');
            $this->target->profile?->increment('friends_count');
        } elseif ($this->type === 'follow') {
            $this->user->profile?->increment('following_count');
            $this->target->profile?->increment('followers_count');
        }
    }

    public function decline(): void
    {
        $this->update(['status' => 'declined']);
    }

    public static function removeFriendship(User $user1, User $user2): void
    {
        // Remove both directions of the friendship
        static::where(function ($query) use ($user1, $user2) {
            $query->where('user_id', $user1->id)->where('target_id', $user2->id);
        })->orWhere(function ($query) use ($user1, $user2) {
            $query->where('user_id', $user2->id)->where('target_id', $user1->id);
        })->where('type', 'friend')->delete();

        // Update counts
        $user1->profile?->decrement('friends_count');
        $user2->profile?->decrement('friends_count');
    }

    public static function removeFollow(User $follower, User $followed): void
    {
        static::where('user_id', $follower->id)
            ->where('target_id', $followed->id)
            ->where('type', 'follow')
            ->delete();

        $follower->profile?->decrement('following_count');
        $followed->profile?->decrement('followers_count');
    }
}
