<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $sent_digest_id
 * @property string $digestable_type
 * @property int $digestable_id
 * @property string $category
 * @property int $position
 * @property int $engagement_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $digestable
 * @property-read \App\Models\SentDigest $sentDigest
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem whereDigestableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem whereDigestableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem whereEngagementScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem whereSentDigestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DigestItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DigestItem extends Model
{
    protected $fillable = [
        'sent_digest_id', 'digestable_type', 'digestable_id',
        'category', 'position', 'engagement_score'
    ];

    public function sentDigest(): BelongsTo
    {
        return $this->belongsTo(SentDigest::class);
    }

    public function digestable(): MorphTo
    {
        return $this->morphTo();
    }
}
