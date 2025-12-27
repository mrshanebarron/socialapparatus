<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $link_preview_id
 * @property string $linkable_type
 * @property int $linkable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LinkPreview $linkPreview
 * @property-read Model|\Eloquent $linkable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkPreviewUsage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkPreviewUsage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkPreviewUsage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkPreviewUsage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkPreviewUsage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkPreviewUsage whereLinkPreviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkPreviewUsage whereLinkableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkPreviewUsage whereLinkableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LinkPreviewUsage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LinkPreviewUsage extends Model
{
    use HasFactory;

    protected $fillable = ['link_preview_id', 'linkable_type', 'linkable_id'];

    public function linkPreview(): BelongsTo
    {
        return $this->belongsTo(LinkPreview::class);
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }
}
