<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $media_id
 * @property int $user_id
 * @property int $tagged_by_id
 * @property numeric $x_position
 * @property numeric $y_position
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Media $media
 * @property-read \App\Models\User $taggedBy
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag whereTaggedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag whereXPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PhotoTag whereYPosition($value)
 * @mixin \Eloquent
 */
class PhotoTag extends Model
{
    protected $fillable = [
        'media_id',
        'user_id',
        'tagged_by_id',
        'x_position',
        'y_position',
        'status',
    ];

    protected $casts = [
        'x_position' => 'decimal:2',
        'y_position' => 'decimal:2',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function taggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tagged_by_id');
    }

    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
