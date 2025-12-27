<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property array<array-key, mixed> $customizations
 * @property string|null $rendered_image
 * @property bool $is_primary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $rendered_image_url
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar whereCustomizations($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar whereRenderedImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAvatar whereUserId($value)
 * @mixin \Eloquent
 */
class UserAvatar extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'customizations', 'rendered_image', 'is_primary'];

    protected $casts = ['customizations' => 'array', 'is_primary' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRenderedImageUrlAttribute(): ?string
    {
        return $this->rendered_image ? Storage::url($this->rendered_image) : null;
    }
}
