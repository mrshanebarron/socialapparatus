<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $link_preview_id
 * @property int|null $user_id
 * @property string $source_type
 * @property int $source_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $referer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LinkPreview $linkPreview
 * @property-read Model|\Eloquent $source
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick whereLinkPreviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick whereReferer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick whereSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkClick whereUserId($value)
 * @mixin \Eloquent
 */
class LinkClick extends Model
{
    use HasFactory;

    protected $fillable = ['link_preview_id', 'user_id', 'source_type', 'source_id', 'ip_address', 'user_agent', 'referer'];

    public function linkPreview(): BelongsTo
    {
        return $this->belongsTo(LinkPreview::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
