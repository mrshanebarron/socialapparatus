<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $url
 * @property string $url_hash
 * @property string|null $title
 * @property string|null $description
 * @property string|null $image_url
 * @property string|null $image_path
 * @property string|null $site_name
 * @property string|null $favicon_url
 * @property string $type
 * @property string|null $video_url
 * @property array<array-key, mixed>|null $metadata
 * @property string $status
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $fetched_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LinkClick> $clicks
 * @property-read int|null $clicks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LinkPreviewUsage> $usages
 * @property-read int|null $usages_count
 * @method static Builder<static>|LinkPreview completed()
 * @method static Builder<static>|LinkPreview newModelQuery()
 * @method static Builder<static>|LinkPreview newQuery()
 * @method static Builder<static>|LinkPreview query()
 * @method static Builder<static>|LinkPreview whereCreatedAt($value)
 * @method static Builder<static>|LinkPreview whereDescription($value)
 * @method static Builder<static>|LinkPreview whereErrorMessage($value)
 * @method static Builder<static>|LinkPreview whereExpiresAt($value)
 * @method static Builder<static>|LinkPreview whereFaviconUrl($value)
 * @method static Builder<static>|LinkPreview whereFetchedAt($value)
 * @method static Builder<static>|LinkPreview whereId($value)
 * @method static Builder<static>|LinkPreview whereImagePath($value)
 * @method static Builder<static>|LinkPreview whereImageUrl($value)
 * @method static Builder<static>|LinkPreview whereMetadata($value)
 * @method static Builder<static>|LinkPreview whereSiteName($value)
 * @method static Builder<static>|LinkPreview whereStatus($value)
 * @method static Builder<static>|LinkPreview whereTitle($value)
 * @method static Builder<static>|LinkPreview whereType($value)
 * @method static Builder<static>|LinkPreview whereUpdatedAt($value)
 * @method static Builder<static>|LinkPreview whereUrl($value)
 * @method static Builder<static>|LinkPreview whereUrlHash($value)
 * @method static Builder<static>|LinkPreview whereVideoUrl($value)
 * @mixin \Eloquent
 */
class LinkPreview extends Model
{
    use HasFactory;

    protected $fillable = [
        'url', 'url_hash', 'title', 'description', 'image_url', 'image_path', 'site_name',
        'favicon_url', 'type', 'video_url', 'metadata', 'status', 'error_message', 'fetched_at', 'expires_at',
    ];

    protected $casts = ['metadata' => 'array', 'fetched_at' => 'datetime', 'expires_at' => 'datetime'];

    public function usages(): HasMany
    {
        return $this->hasMany(LinkPreviewUsage::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public static function getOrFetch(string $url): self
    {
        $urlHash = md5($url);
        return self::firstOrCreate(['url_hash' => $urlHash], ['url' => $url, 'status' => 'pending']);
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) return Storage::url($this->image_path);
        return $this->attributes['image_url'] ?? null;
    }
}
