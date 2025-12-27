<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $album_id
 * @property int $user_id
 * @property int $invited_by
 * @property string $role
 * @property string $status
 * @property bool $can_add_photos
 * @property bool $can_remove_photos
 * @property bool $can_invite_others
 * @property bool $notifications_enabled
 * @property \Illuminate\Support\Carbon|null $accepted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Album $album
 * @property-read \App\Models\User $inviter
 * @property-read \App\Models\User $user
 * @method static Builder<static>|AlbumCollaborator accepted()
 * @method static Builder<static>|AlbumCollaborator newModelQuery()
 * @method static Builder<static>|AlbumCollaborator newQuery()
 * @method static Builder<static>|AlbumCollaborator query()
 * @method static Builder<static>|AlbumCollaborator whereAcceptedAt($value)
 * @method static Builder<static>|AlbumCollaborator whereAlbumId($value)
 * @method static Builder<static>|AlbumCollaborator whereCanAddPhotos($value)
 * @method static Builder<static>|AlbumCollaborator whereCanInviteOthers($value)
 * @method static Builder<static>|AlbumCollaborator whereCanRemovePhotos($value)
 * @method static Builder<static>|AlbumCollaborator whereCreatedAt($value)
 * @method static Builder<static>|AlbumCollaborator whereId($value)
 * @method static Builder<static>|AlbumCollaborator whereInvitedBy($value)
 * @method static Builder<static>|AlbumCollaborator whereNotificationsEnabled($value)
 * @method static Builder<static>|AlbumCollaborator whereRole($value)
 * @method static Builder<static>|AlbumCollaborator whereStatus($value)
 * @method static Builder<static>|AlbumCollaborator whereUpdatedAt($value)
 * @method static Builder<static>|AlbumCollaborator whereUserId($value)
 * @mixin \Eloquent
 */
class AlbumCollaborator extends Model
{
    use HasFactory;

    protected $fillable = [
        'album_id', 'user_id', 'invited_by', 'role', 'status', 'can_add_photos',
        'can_remove_photos', 'can_invite_others', 'notifications_enabled', 'accepted_at',
    ];

    protected $casts = [
        'can_add_photos' => 'boolean',
        'can_remove_photos' => 'boolean',
        'can_invite_others' => 'boolean',
        'notifications_enabled' => 'boolean',
        'accepted_at' => 'datetime',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', 'accepted');
    }
}
