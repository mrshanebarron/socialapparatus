<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $animation_type
 * @property string $preview_path
 * @property bool $is_premium
 * @property int $coin_cost
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $preview_url
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker whereAnimationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker whereCoinCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker whereIsPremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker wherePreviewPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AvatarSticker whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AvatarSticker extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'animation_type', 'preview_path', 'is_premium', 'coin_cost', 'is_active'];

    protected $casts = ['is_premium' => 'boolean', 'is_active' => 'boolean'];

    public function getPreviewUrlAttribute(): ?string
    {
        return $this->preview_path ? Storage::url($this->preview_path) : null;
    }
}
