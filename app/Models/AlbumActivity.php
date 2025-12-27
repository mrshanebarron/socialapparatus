<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $album_id
 * @property int $user_id
 * @property string $action
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property array<array-key, mixed>|null $metadata
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Album $album
 * @property-read Model|\Eloquent|null $subject
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AlbumActivity whereUserId($value)
 * @mixin \Eloquent
 */
class AlbumActivity extends Model
{
    use HasFactory;

    protected $fillable = ['album_id', 'user_id', 'action', 'subject_type', 'subject_id', 'metadata'];

    protected $casts = ['metadata' => 'array'];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
