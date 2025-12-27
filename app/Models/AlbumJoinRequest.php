<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $album_id
 * @property int $user_id
 * @property string|null $message
 * @property string $status
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Album $album
 * @property-read \App\Models\User|null $reviewer
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumJoinRequest whereUserId($value)
 * @mixin \Eloquent
 */
class AlbumJoinRequest extends Model
{
    use HasFactory;

    protected $fillable = ['album_id', 'user_id', 'message', 'status', 'reviewed_by', 'reviewed_at'];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
