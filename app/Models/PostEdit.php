<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $post_id
 * @property string|null $previous_body
 * @property array<array-key, mixed>|null $previous_media
 * @property string|null $edited_by_ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Post $post
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostEdit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostEdit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostEdit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostEdit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostEdit whereEditedByIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostEdit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostEdit wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostEdit wherePreviousBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostEdit wherePreviousMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostEdit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PostEdit extends Model
{
    protected $fillable = [
        'post_id',
        'previous_body',
        'previous_media',
        'edited_by_ip',
    ];

    protected $casts = [
        'previous_media' => 'array',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
